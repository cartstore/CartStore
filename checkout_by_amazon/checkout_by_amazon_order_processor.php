<?php
/**
 * @brief Syncs up Checkout by Amazon orders and processes them (ship, refund, cancel)
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
ini_set('include_path','.:' . DIR_FS_CATALOG . "checkout_by_amazon/library:"  . ini_get('include_path'));


require_once(DIR_FS_CATALOG . DIR_WS_MODULES . '/payment/checkout_by_amazon.php');
require_once('checkout_by_amazon_order_builder.php');
require_once("merchantAtAPIs/lib/amazon/amazon_merchant_at_soap_client.php");
require_once('checkout_by_amazon_order_dao.php');
require_once('checkout_by_amazon_order_status_history_dao.php');
require_once('library/callback/lib/amazon/lib/functions.php');
require_once('checkout_by_amazon/CBAMFAxml.php');

/**
 * This class persists an order into the OSCommerce databases.
 * We mimic checkout_process.php, order.php and order_total.php to achieve this 
 * functionality.
 *
 * Unfortunately, this means alot of copy and paste, as those php files
 * pull all its data from either the session or the database.
 * The files are not modularized/OO with well defined objects, that would 
 * support constructing an object via a different source (a.k.a. XML).
 * 
 */
class OrderProcessor extends OrderBuilder
{
    var $merchantAtClient;
    var $login;
    var $password;
    var $merchantToken;
    var $merchantName;
    var $utilDao;
    var $mfa_xml;
    /**
     * Constructor
     */
    function OrderProcessor()
    {
        parent::OrderBuilder();

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
     * Ship the order using the already binded merchant order id
     */
    function shipOrder($data)
    {
	$parameters = $data;
        $orderDao = new OrderDAO();
        $isDebug = $parameters['isDebug'] == 'true' ? true : false;
        $orderID = $parameters['OrderID'];
        $amazonOrderID = $orderDao->getAmazonOrderID($orderID);

        $fulfillmentCarrierCode = $parameters['ShippingCarrier'];
        $fulfillmentShippingMethod = $parameters['ShippingService'];
        $fulfillmentShippingTrackingNumber = $parameters['ShippingTrackingNumber'];
        /////////////////////////////////////////////////////////
        // Ship the order by making the request via MFA
        /////////////////////////////////////////////////////////
        // compose the message first
        $messageType = '_POST_ORDER_FULFILLMENT_DATA_';
        
        // NOTE: This date must be in the past
        $fulfillmentDate = gmdate('Y-m-d\TH:i:s');

        $orderFulfillment =
            str_replace("[MERCHANT TOKEN]", $this->merchantToken, AMAZON_MESSAGE_ORDER_FULFILLMENT_START);
            
        $orderFulfillmentBodyStart =
            str_replace("[MESSAGE ID]", 1, AMAZON_MESSAGE_ORDER_FULFILLMENT_BODY_START);
        $orderFulfillmentBodyStart =
            str_replace("[AMAZON ORDER ID]", $amazonOrderID, $orderFulfillmentBodyStart);
        $orderFulfillmentBodyStart =
            str_replace("[MERCHANT FULFILLMENT DATE]", $fulfillmentDate, $orderFulfillmentBodyStart);
        $orderFulfillmentBodyStart =
            str_replace("[FULFILLMENT CARRIER CODE]", $fulfillmentCarrierCode, $orderFulfillmentBodyStart);
        $orderFulfillmentBodyStart =
            str_replace("[FULFILLMENT SHIPPING METHOD]", $fulfillmentShippingMethod, $orderFulfillmentBodyStart);
        $orderFulfillmentBodyStart =
            str_replace("[FULFILLMENT SHIPPING TRACKING NUMBER]", $fulfillmentShippingTrackingNumber, $orderFulfillmentBodyStart);

        $orderFulfillment = $orderFulfillment . $orderFulfillmentBodyStart;
        $orderFulfillment = $orderFulfillment . AMAZON_MESSAGE_ORDER_FULFILLMENT_BODY_END . AMAZON_MESSAGE_ORDER_FULFILLMENT_END;

        $this->utilDao->echoMessage("Shipping order: " . $orderID . "<br/>", $isDebug);
	$this->mfa_xml = $orderFulfillment;
        $this->utilDao->varDump($this->mfa_xml, $isDebug);

        // Actually post the ship confirmation
        $result = $this->merchantAtClient->postDocument($messageType, $orderFulfillment);

        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);

        // Echo the transaction id so that it can be stored in the comments 
        // field of the order status history, and then monitored for completion
        // in the background.
        // NOTE: We don't wait for the response of transaction complete,
        // because this is handled in a separate background thread that uses
        // OrderMonitor.java.
    
        return $result;
    }


    /**
     * Refund the order using the already binded merchant order id
     */
    function refundOrder($data)
    {
	$parameters = $data;
        $isDebug = $parameters['isDebug'] == 'true' ? true : false;
        $orderDao = new OrderDAO();
        $orderID = $parameters['OrderID'];
        $amazonOrderID = $orderDao->getAmazonOrderID($orderID);
        $amazonOrderItemMetaData = $orderDao->getAmazonOrderItemMetaData($orderID);
        /////////////////////////////////////////////////////////
        // Refund the order by making the request via MFA
        /////////////////////////////////////////////////////////
	$messageType = '_POST_PAYMENT_ADJUSTMENT_DATA_';

        $paymentAdjustment =
            str_replace("[MERCHANT TOKEN]", $this->merchantToken, AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_START);

        $paymentAdjustmentBodyStart =
            str_replace("[MESSAGE ID]", 1, AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_BODY_START);
        $paymentAdjustmentBodyStart =
            str_replace("[AMAZON ORDER ID]", $amazonOrderID, $paymentAdjustmentBodyStart);
        $paymentAdjustment = $paymentAdjustment . $paymentAdjustmentBodyStart;

        // for each item in the order, add the adjustment
        $items = $orderDao->getOrderProducts($orderID);
        $adjustmentReason = $parameters['RefundReason'];
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $merchantOrderItemId = $item['orders_products_id'];
            $itemPrincipal = number_format($item['final_price'] * $item['products_quantity'], 2);
            $itemShipping = number_format($item['products_shipping'], 2);
            
            // this is stored as a tax rate, so we convert it to a tax amount.
            $itemTaxRate = $item['products_tax'] / 100;
            $itemTax = 0.0;

            if ($itemTaxRate > 0) {
                 $itemTotal = $item['products_price'] * $item['products_quantity'] + $item['products_shipping'] +
                              $item['products_promotion_price'] + $item['products_promotion_shipping'];
                 $itemTax = number_format(($itemTaxRate * $itemTotal) - $item['products_shipping_tax'] - $item['products_promotion_tax'], 2);
            }
       
            $itemShippingTax = number_format($item['products_shipping_tax'], 2);
            $itemPromotionClaimCode = $item['products_promotion_claim_code'];
            $itemPromotionMerchantPromotionId = $item['products_promotion_merchant_promotion_id'];
            $itemPromotionPrincipal = number_format($item['products_promotion_price'] - $item['products_promotion_shipping'] -
                                                    $item['products_promotion_tax'], 2);
            $itemPromotionShipping = number_format($item['products_promotion_shipping'], 2);

            $paymentAdjustmentItemStart =
                str_replace("[AMAZON ORDER ITEM CODE]", $amazonOrderItemMetaData[$merchantOrderItemId], AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_START);
            $paymentAdjustmentItemStart =
                str_replace("[ADJUSTMENT REASON]", $adjustmentReason, $paymentAdjustmentItemStart);
            $paymentAdjustmentItemStart =
                str_replace("[ITEM PRICE ADJUSTMENTS PRINCIPAL]", $itemPrincipal, $paymentAdjustmentItemStart);
            $paymentAdjustmentItemStart =
                str_replace("[ITEM PRICE ADJUSTMENTS SHIPPING]", $itemShipping, $paymentAdjustmentItemStart);
            $paymentAdjustmentItemStart =
                str_replace("[ITEM PRICE ADJUSTMENTS TAX]", $itemTax, $paymentAdjustmentItemStart);
            $paymentAdjustmentItemStart =
                str_replace("[ITEM PRICE ADJUSTMENTS SHIPPING TAX]", $itemShippingTax, $paymentAdjustmentItemStart);

            $paymentAdjustment = $paymentAdjustment . $paymentAdjustmentItemStart;

            // add any promotion adjustments to the refund message
            if ($itemPromotionPrincipal != 0 ||
                $itemPromotionShipping != 0) {

                $paymentAdjustmentItemPromotionStart =
                    str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION CLAIM CODE]", $itemPromotionClaimCode, AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_START);

                // add merchant promotion id if it exists - else, make it an 
                // empty section
                if ($itemPromotionMerchantPromotionId) {
                    $merchantPromotionIdSection = 
                        str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION MERCHANT PROMOTION ID]", $itemPromotionMerchantPromotionId, AMAZON_MESSGAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_MERCHANT_PROMOTION_ID_SECTION);

                    $paymentAdjustmentItemPromotionStart =
                    str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION MERCHANT PROMOTION ID SECTION]", $merchantPromotionIdSection, $paymentAdjustmentItemPromotionStart);
                }
                else {
                    $paymentAdjustmentItemPromotionStart =  str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION MERCHANT PROMOTION ID SECTION]", "",  $paymentAdjustmentItemPromotionStart);
                }

                $paymentAdjustmentItemPromotionStart =
                    str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION PRINCIPAL]", $itemPromotionPrincipal, $paymentAdjustmentItemPromotionStart);

                // add merchant shipping promotion section if it exists - else, make it an 
                // empty section
                if ($itemPromotionShipping != 0) {
                    $promotionShippingSection = 
                        str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION SHIPPING]", $itemPromotionShipping, AMAZON_MESSGAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_SHIPPING_SECTION);

                    $paymentAdjustmentItemPromotionStart =
                    str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION SHIPPING SECTION]", $promotionShippingSection, $paymentAdjustmentItemPromotionStart);
                }
                else {
                    $paymentAdjustmentItemPromotionStart =  str_replace("[ITEM PRICE ADJUSTMENTS PROMOTION SHIPPING SECTION]", "",  $paymentAdjustmentItemPromotionStart);
                }

                $paymentAdjustment = $paymentAdjustment . $paymentAdjustmentItemPromotionStart;
            }

            $paymentAdjustment = $paymentAdjustment . AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_END;
        }

        $paymentAdjustment = $paymentAdjustment . AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_END;

        $this->utilDao->echoMessage("Refunding order: " . $orderID . "<br/>", $isDebug);
        $this->utilDao->varDump($paymentAdjustment, $isDebug);

        $result = $this->merchantAtClient->postDocument($messageType, $paymentAdjustment);
	
        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);

        // Echo the transaction id so that it can be stored in the comments 
        // field of the order status history, and then monitored for completion
        // in the background.
        // NOTE: We don't wait for the response of transaction complete,
        // because this is handled in a separate background thread that uses
        // OrderMonitor.java.
	$this->mfa_xml = $paymentAdjustment;
        return $result;
    }


    /**
     * Cancel the order using the already binded merchant order id and merchant 
     * order item ids
     */
    function cancelOrder($data)
    {
	$parameters =  $data;
        $orderDao = new OrderDAO();

        $isDebug = $parameters['isDebug'] == 'true' ? true : false;
        $orderID = $parameters['OrderID'];
        $amazonOrderID = $orderDao->getAmazonOrderID($orderID);

        /////////////////////////////////////////////////////////
        // Cancel the order by making the request via MFA
        /////////////////////////////////////////////////////////
	$messageType = '_POST_ORDER_ACKNOWLEDGEMENT_DATA_';

        $orderCancellation =
            str_replace("[MERCHANT TOKEN]", $this->merchantToken, AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_START);

        $orderCancellationBodyStart =
            str_replace("[MESSAGE ID]", 1, AMAZON_MESSAGE_ORDER_CANCELLATION_BODY_START);
        $orderCancellationBodyStart =
            str_replace("[AMAZON ORDER ID]", $amazonOrderID, $orderCancellationBodyStart);
        $orderCancellation = $orderCancellation . $orderCancellationBodyStart;

        $orderCancellation = $orderCancellation . AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_BODY_END . AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_END;
        
        $this->utilDao->echoMessage("Cancelling order: " . $orderID . "<br/>", $isDebug);
        $this->utilDao->varDump($orderCancellation, $isDebug);

        $result = $this->merchantAtClient->postDocument($messageType, $orderCancellation);

        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);

        // Echo the transaction id so that it can be stored in the comments 
        // field of the order status history, and then monitored for completion
        // in the background.
        // NOTE: We don't wait for the response of transaction complete,
        // because this is handled in a separate background thread that uses
        // OrderMonitor.java.
	$this->mfa_xml = $orderCancellation;
        return $result;
    }


    /**
     * Process orders by:
     *
     * 1) get most recent orders
     * 2) Write or merge orders into the database.
     * 2a) Write with timestamp so that if the acknowledgement fails,
     *     we can de-dup the orders.
     * 3) acknowledge the orders
     */
    function getOrders($data)
    {
        $parameters = $this->utilDao->getParameterMap($data);

        $isDebug = $parameters['isDebug'] == 'true' ? true : false;

        if (!$this->acquireLock($isDebug)) {
            return;
        }
        $this->processOrderDocuments($data, $isDebug);

        $this->releaseLock($isDebug);
    }

    function processOrderDocuments($data, $isDebug = false) {
        // load selected payment module
        require(DIR_WS_CLASSES . 'payment.php');
        //$payment_modules = new payment($payment);

        // load the selected shipping module
        require(DIR_WS_CLASSES . 'shipping.php');
        //$shipping_modules = new shipping($shipping);

        require_once(DIR_WS_CLASSES . 'checkout_by_amazon_order.php');
        
        // construct an empty order and initialize it ourselves
        /////////////////////////////////////////////////////////
        // Get all order reports
        /////////////////////////////////////////////////////////
        $result = $this->merchantAtClient->getAllPendingDocumentInfo("_GET_ORDERS_DATA_");
        
        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);
    
        if ($result == NULL) {
            return;
        }

        // Process each order report and then acknowledge it
        // if it is not returned as an array, converted here
        $documentIDs = (!is_array($result)) ? array($result) : $result;
        $length = count($documentIDs);
        for ($i = 0; $i < $length; $i++) {
            $documentID = $documentIDs[$i]->documentID;
            $this->utilDao->echoMessage("Processing document: " . $documentID . "<br/>", $isDebug);

            /////////////////////////////////////////////////////////
            // Get specific order document
            /////////////////////////////////////////////////////////
            $result = $this->merchantAtClient->getDocument($documentID);
            $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
	    $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);
            $this->processOrders($result, $isDebug);
            
            // now acknowledge the document reports as finished processing
            // so we don't process them again.
            $this->utilDao->echoMessage("Acknowledging document: " . $documentID . "<br/>", $isDebug);

            $this->acknowledgeDocument($documentID);
        }
    }

     /**
      * Processes all the orders in the order report
      *
      */
     function processOrders($data, $isDebug = false) {
         $orders = $data['Message'];
         $orderIds = array();
 
         // if this order report only contains one order
         // convert it to array for consistency
         $orders = $orders['OrderReport'] != NULL ? array($orders) : $orders;
 
         // processes each individual order
         for ($i = 0; $i < count($orders); $i++) {
             // Returns an array of:
             //
             // 'AmazonOrderID'
             // 'OrderID'
             // 'OrderData' 
            $mfa_result = new CBAMFAxml($orders[$i]);
 
             $ids = $this->processOrder($mfa_result, $isDebug);
 
             if ($ids) {
                 array_push($orderIds, $ids);
             }
         }
     }
 
    /**
     * Stores the individual order in the database by:
     *
     * 1) Filter out any orders that are not Checkout by Amazon orders.
     * 2) Setting order totals and other order information.
     * 3) Persist or retrieve the new or existing customer in the database.
     *    Send out registration email if customer is new.
     * 4) Persist the order.
     *
     */
    function processOrder($request, $isDebug = false) {
        if (!$request->isCbaOrder()) {
            $this->utilDao->echoMessage("Ignoring processing of non-cba order: " . $request->getAmazonOrderID() . "<br/>", $isDebug);
            return;
        }
        if($request->isExistingUpdatedOrder() == "UPDATED") {
                  return;
  	}
        $this->utilDao->echoMessage("Processing cba order: " . $request->getAmazonOrderID() . "<br/>", $isDebug);

        // construct an empty order object and initialize it ourselves
        $orderDao = new OrderDAO();
        $order = $orderDao->getOrder($request->getOrderBuyerEmailAddress(), $request->getOrderDate());
        $orderId;
        $amazonOrderId = $request->getAmazonOrderID();
	$orderId = $orderDao->getOSCommerceOrderID($amazonOrderId);
	if($order == NULL) {
		$order = $orderDao->getOrderUsingOrderID($orderId);
	}
        if ($order != NULL) {
	    $orderId = $order['orders_id'];
	}
	else {
        	if(!($request->isExistingUpdatedOrder() == "EXISTING")) {
		    $orderId = $this->createOrder($order, $request, $isDebug);
		}
	}

	if ($orderId == NULL) {
	    return NULL;
        }
	
        $this->utilDao->echoMessage("Creating or updating order status history in database, amazon order id: " . $amazonOrderId . " order id: " . $orderId . "<br/>", $isDebug);

	// annotate that this is an Checkout by Amazon order
	// This is to differientate orders during shipment time.
	// If pending orders contains a list of CBA and non-CBA orders,
	// the annotation will indicate which order to ship confirm through
        // Amazon.
        $orderStatusHistoryDao = new OrderStatusHistoryDAO();
        $orderStatusHistoryID = $orderStatusHistoryDao->getOrCreateOrderStatusHistory($order, $orderId, $amazonOrderId, $orderData);
        if ($orderStatusHistoryID == NULL) {
            return;
        }
	else {
	    $orderPromotionTotal = 0.00;
	    $comments = AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION . $amazonOrderId;
    	    $comments = $comments . "\n\n" . AMAZON_PROCESSING_MESSAGE_ORDER_ITEM_METADATA_INFORMATION;
	    $items = $request->getOrderItems();
	    for ($i = 0; $i < count($items); $i++) {
              $item = $items[$i];
	      $item = (array)$item;
	      $pos = strpos($item['SKU'], '{');
              if($pos === false)
                $sku = $item['SKU'];
              else
                $sku = substr($item['SKU'], 0, $pos);

              $merchantOrderItemCode = $orderDao->getMerchantOrderItemCode($orderId, $sku);

	      $amazonOrderItemCode = $item['AmazonOrderItemCode'];
      	      $comments = $comments . $merchantOrderItemCode . ':' . $amazonOrderItemCode . "\n";
	    $orderStatusHistoryDao->updateOrderStatusHistory($orderStatusHistoryID['orders_status_history_id'], $orderStatusHistoryID['orders_status_id'] , true, $comments);
  	     $itemTotals = $request->getItemPriceComponents($item);
	     $query = "select orders_products_id from " . TABLE_AMAZON_ORDERS_PRODUCTS . " where orders_products_id = '" . $merchantOrderItemCode  . "'";
	     $orders_products_query = tep_db_query($query);
	     if (!tep_db_num_rows($orders_products_query)) {
		     $sql_data_array = array('orders_products_id' => $merchantOrderItemCode,
                                    'products_shipping' => $itemTotals['shipping_total'],
                                    'products_shipping_tax' => $itemTotals['shipping_tax_total'],
                                    'products_promotion_price' => $itemTotals['promotion_total'],
                                    'products_promotion_shipping' => $itemTotals['$promotion_shipping_total']);

        	    tep_db_perform(TABLE_AMAZON_ORDERS_PRODUCTS, $sql_data_array);     
	     }
	     $orderPromotionTotal = (float)$orderPromotionTotal + (float)$itemTotals['promotion_total'];
	    }
	    $this->updatePromotions($orderPromotionTotal, $orderId);
	    $request_class = get_class($request);
	    if($request_class == 'CBAIOPNxml')  {
		if($request->getNotificationType() == "NewOrderNotification") {
        		$this->utilDao->echoMessage("Creating status history in amazon table", $isDebug);
			$orderStatusHistoryDao->insertAmazonOrderStatusHistory($orderId, $amazonOrderId, '', 0, AMAZON_ORDER_SUCCESS, AMAZON_STATUS_PAYMENT_PENDING,  $comments);
			return array('AmazonOrderID' => $amazonOrderId,
        	        	     'OrderID' => $orderId,
	                	     'OrderData' => $orderData);
		}
	    }
 
	    $orderStatusHistoryDao->insertAmazonOrderStatusHistory($orderId, $amazonOrderId, '', 0, AMAZON_ORDER_SUCCESS, AMAZON_STATUS_UNSHIPPED,  AMAZON_PROCESSING_MESSAGE_ORDER_READY_TO_BE_SHIP);
	    $orderStatusHistoryDao->insertOrderStatusHistory($orderId, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW,  AMAZON_PROCESSING_MESSAGE_ORDER_READY_TO_BE_SHIP);
   	    $this->utilDao->updateStatus(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW, $orderId);
	}
        // map oscommerce order id to amazon order id
        // so that later, when the merchant ships the order,
        // they can use oscommerce's order id as a reference to the amazon 
        // order in the ship confirmation request.
       
	 return array('AmazonOrderID' => $amazonOrderId,
                     'OrderID' => $orderId,
                     'OrderData' => $orderData);
    }

    function updatePromotions($orderPromotionTotal, $orderId) {
	     $currencies = new currencies();
             // Insert into the orders_total table amazon specific promotion information
	     $query = "select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $orderId  . "' and class = 'ot_promo'";
             $promotions_query = tep_db_query($query);
             if (!tep_db_num_rows($promotions_query)) {
		$sql_data_array = array('orders_id' => $orderId,
				    'class' => 'ot_promo',
                                    'title' => 'Promotion:',
                                    'text' => "$" . $orderPromotionTotal,
                                    'value' => $orderPromotionTotal,
                                    'sort_order' => 3);
	             tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
		
	     $query = "select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $orderId  . "' and class = 'ot_total'";
             $promotions_query = tep_db_query($query);
             if (tep_db_num_rows($promotions_query)) {
		$text = tep_db_fetch_array($promotions_query);
		$value = $text['value'] - $orderPromotionTotal;
		$text = $currencies->format($value, true, $order->info['currency'], $order->info['currency_value']);
		tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value = value - '" . $value . "' , text =  '" . $text . "' where orders_id = '" . $orderId . "' and class = 'ot_total'");
		
	     }
	    } 
    }

    /**
     * Construct the order object and persist it to the database.
     *
     */   
    function createOrder (&$order, $request, $isDebug = false) {
        // construct an empty order object and initialize it ourselves
    //    $order = new orderAmazon('', false);
	$channel = $request->getOrderChannel();
	$request_class = get_class($request);
        //////////////////////////////////////////////////////////////////////
        //
        // Manually construct the order object to insert into the database.
        //
        //////////////////////////////////////////////////////////////////////
        $this->setOrderTotals($order, $request);

        $this->setOrderInformation($order, $channel, $request_class);

        $this->setFulfillmentData($order, $request);
        
        $this->setCustomer($order, $request);

        $this->setCustomerAddress($order, $request);

        $this->setProductData($order, $request);

        $this->utilDao->echoMessage("Creating cba order in database: ", $isDebug);
        $this->utilDao->varDump($order, $isDebug);
        $orderId = parent::createOrder($order, $request);
 
        // Sets affiliate data for osCAffiliates v2.8.
        $this->updateOrderAffiliateData($orderId, $order, $request);

        return $orderId;
    }

    /**
     * Gets the status of a postDocument - whether the post succeeded or failed.
     */
    function getDocumentProcessingReport($documentID) {
        $processingReport = $this->merchantAtClient->getDocument($documentID);
        
        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);

        $messagesSuccessful = $processingReport['Message']['ProcessingReport']['ProcessingSummary']['MessagesSuccessful'];
        $messagesWithError = $processingReport['Message']['ProcessingReport']['ProcessingSummary']['MessagesWithError'];
                
        return $processingReport;
    }


    /**
     * Acknowledge a document that we have completed processing.
     * Processing consists of the following steps:
     *
     * 1) Get order report
     * 2) Store orders in the database
     * 3) Update the order as an CBA order (set status history)
     */
    function acknowledgeDocument($documentID) {
        $documentIDs = array('string' => $documentID);
        
        $this->merchantAtClient->postDocumentDownloadAck($documentIDs);

        $this->utilDao->echoMessage("Got response: <br/>", $isDebug);
        $this->utilDao->varDump($this->merchantAtClient->client->getWire(), $isDebug);
    }


    function acquireLock($isDebug = false) {
        $response = $this->utilDao->acquireLock(ORDER_SYNCHRONIZATION_LOCK_KEY, ORDER_SYNCHRONIZATION_LOCK_TIMEOUT, $isDebug);
        return $response == true;
    }

    function releaseLock($isDebug = false) {
        $response = $this->utilDao->releaseLock(ORDER_SYNCHRONIZATION_LOCK_KEY, $isDebug);
        return $response == true;
    }
}
?>
