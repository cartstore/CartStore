<?php
/**
 * @brief Base class for methods for monitoring whether an order status got updated or not.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
 * @copyright 2008-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 *
 */
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
require('includes/configure.php');
ini_set('include_path','.:' .
        DIR_FS_CATALOG . "checkout_by_amazon:" . 
          DIR_FS_CATALOG . "checkout_by_amazon/library/PHP_Compat-1.6.0a1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/SOAP-0.12.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2/PEAR:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/HTTP-1.4.3:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_Mime-1.5.2:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_mimeDecode-1.5.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Net_Socket-1.0.9:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/Net_URL-1.0.15:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Parser-1.3.1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Serializer-0.19.0:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Util-1.2.1:" .
          DIR_FS_CATALOG . "checkout_by_amazon/library:" .
          ini_get('include_path'));
define('WSDLPATH', 'file://' . DIR_FS_CATALOG . '/checkout_by_amazon/library/merchantAtAPIs/lib/amazon/merchant-interface-mime.wsdl');

require_once("merchantAtAPIs/lib/amazon/amazon_merchant_at_soap_client.php");

require_once(DIR_WS_MODULES . '/payment/checkout_by_amazon.php');
require_once('checkout_by_amazon_util_dao.php');
require_once('checkout_by_amazon_order_dao.php');
require_once('checkout_by_amazon_order_status_history_dao.php');
require_once('checkout_by_amazon_constants.php');



/**
 * Base class for methods for monitoring whether an order status got updated or 
 * not.
 *
 * OVERVIEW:
 *
 * When a merchant updates an order (shipped, refunded, cancelled),
 * an asynchronous request is sent to Amazon. Amazon returns an transaction id
 * for which the client can monitor the status of the request.
 *
 * This class does the monitoring and updates the order based on the successful 
 * or failed completion of the request.
 *
 * If the request succeeds, the order remains in the merchant selected states,
 * and the comments are updated to indicate success.
 * If the request failed, the order is moved to the SYSTEM_ERROR state.
 */
class OrderMonitor
{
    var $merchantAtClient;
    var $login;
    var $password;
    var $merchantToken;
    var $merchantName;
    var $utilDao;

    /**
     * Constructor
     */
    function OrderMonitor()
    {
      ///////////////////////////////////////////////////////////
      // Pull these from the oscommerce configuration table.
      // These values are set using the admin UI
      // in the CBA payments module.
      ///////////////////////////////////////////////////////////
      $cba_module_info = new checkout_by_amazon();

      $this->login = $cba_module_info->merchant_email;
      $this->password = $cba_module_info->merchant_password;
      $this->merchantToken = $cba_module_info->merchant_token;
      $this->merchantName = $cba_module_info->merchant_name;

      $this->merchantAtClient = new AmazonMerchantAtSoapClient($this->login, $this->password, $this->merchantToken, $this->merchantName);
      $this->utilDao = new UtilDAO();
    }

    /**
     * Monitors all order status update events to ensure that they completed 
     * successfully.
     *
     * Else, update the order status to system error, with details on
     * going to Seller Central to update the order explicitly,
     * and ways to contact TAM.
     */
    function monitorOrderStatusUpdates($data) {
        $parameters = $this->utilDao->getParameterMap($data);
        $isDebug = $parameters['isDebug'] == 'true' ? true : false;


        if (!$this->acquireLock($isDebug)) {
            return;
        }

        $this->monitor($isDebug);

        $this->releaseLock($isDebug);
    }


    function monitor($isDebug = false) {
	global $oscommerce_amazon_status_name, $oscommerce_amazon_order_status_mapping;
        // query all Amazon orders whose status updates have not been completed.
    
        $orderDao = new OrderDAO();
        $orderStatusHistoryDao = new OrderStatusHistoryDAO();

        // gets the processing status in the comment history, and the 
        // corresponding transaction id.
        // waits for document process status to complete on that id.
        // If it returns and error message, set that order into an error state.
        $ordersWithPendingStatus = $orderDao->getOrdersWithPendingStatuses();
        $this->utilDao->echoMessage("Got orders with pending status:<br/>", $isDebug);
        $this->utilDao->varDump($ordersWithPendingStatus, $isDebug);

        // Based on the result of processing,
        // we will update the pending status in order history to either success or fail.
        // we will also change all pending order status of an order to failed if a system error 
        // occurred.
        for ($i = 0; $i < count($ordersWithPendingStatus); $i++) {
            $orderStatus = $ordersWithPendingStatus[$i];

            $amazonProcessingTransactionId = $orderStatus['transactionID'];

            if (!$amazonProcessingTransactionId) {
                return;
            }
            
            $this->utilDao->echoMessage("Waiting for transaction to complete: " . $amazonProcessingTransactionId . "<br/>", $isDebug);

            // TODO: Refactor this into a function
	    $documentProcessingInfo = $this->merchantAtClient->waitForDocumentProcessingComplete($amazonProcessingTransactionId);
            $documentID = $documentProcessingInfo['processingReport']->documentID;

            if ($documentID != NULL) {
                // TODO: Refactor this into a utility function
                $processingReport = $this->merchantAtClient->getDocument($documentID);

	        $messagesSuccessful = $processingReport['Message']['ProcessingReport']['ProcessingSummary']['MessagesSuccessful'];
                $messagesWithError = $processingReport['Message']['ProcessingReport']['ProcessingSummary']['MessagesWithError'];


                if ($messagesSuccessful > 0 && $messagesWithError <= 0) {
                    $this->utilDao->echoMessage("Transaction completed successfully: " . $amazonProcessingTransactionId . "<br/>", $isDebug);

                    // get any existing merchant comment and prepend it to the 
                    // update so it is not lost.

                    $orderStatusHistoryDao->updateAmazonOrderStatusHistory($orderStatus['id'], $orderStatus['orders_id'], AMAZON_ORDER_SUCCESS);
	    	    $comments = "SUCCESS: Amazon Order " . $orderStatus['amazon_order_id'] . " " . $oscommerce_amazon_status_name[$orderStatus['amazon_order_status']];
		    $orderStatusHistoryDao->insertOrderStatusHistory($orderStatus['orders_id'], $oscommerce_amazon_order_status_mapping[$orderStatus['amazon_order_status']], $comments);
            	    $orderDao->updateOrderStatus($orderStatus['orders_id'], $oscommerce_amazon_order_status_mapping[$orderStatus['amazon_order_status']]);
                    continue;
                }
            }


            //////////////////////////////////////////////////////////
            //
            // Any other case is an error - set order status to
            // System Error
            //
            //////////////////////////////////////////////////////////

            $this->utilDao->echoMessage("Transaction failed: " . $amazonProcessingTransactionId . ". Updating order status to system error.<br/>", $isDebug);
            $this->utilDao->varDump($processingReport['Message'], $isDebug);

            $orderStatusHistoryDao->updateAmazonOrderStatusHistory($orderStatus['id'], $orderStatus['orders_id'], AMAZON_ORDER_FAILURE);
	    $comments = "FAILURE: Amazon Order " . $orderStatus['amazon_order_id'] . " could not be " . $oscommerce_amazon_status_name[$orderStatus['amazon_order_status']];
	    $orderStatusHistoryDao->insertOrderStatusHistory($orderStatus['orders_id'], $oscommerce_amazon_order_status_mapping[$orderStatus['amazon_order_status']], $comments);

            // Also update the order to have a failed order status
            $orderDao->updateOrderStatus($orderStatus['orders_id'], AMAZON_STATUS_SYSTEM_ERROR);
        }
    }


    /**
     *
     *
     */
    function acquireLock($isDebug = false) {
        return $this->utilDao->acquireLock(ORDER_STATUS_MONITOR_LOCK_KEY,
                                           ORDER_STATUS_MONITOR_LOCK_TIMEOUT, $isDebug);
    }

    /**
     * Releases the lock file by deleting it.
     */
    function releaseLock($isDebug = false) {
        return $this->utilDao->releaseLock(ORDER_STATUS_MONITOR_LOCK_KEY, $isDebug);
    }
}
?>
