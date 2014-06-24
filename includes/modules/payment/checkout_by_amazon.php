<?php
/**
 * @brief Defines the class representing the Checkout by Amazon Module
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Neil Corkum
 * @author Allison Naaktgeboren
 * @author Joshua Wong
 * @copyright Portions copyright 2007-2009 Amazon Technologies, Inc
 * @copyright portions copyright osCommerce, 2002-2008
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 * @note Although Fulfillment Network is an item level tag in the official xml schema, the 
 *	decision applies to whole inventory, and so is stored in this class  
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
require_once(DIR_FS_CATALOG.'manage/includes/configure.php');
ini_set('include_path','.:' .
        DIR_FS_CATALOG . ":" .
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

require_once(DIR_FS_CATALOG.'checkout_by_amazon/checkout_by_amazon_constants.php');
require_once(DIR_FS_CATALOG.'checkout_by_amazon/checkout_by_amazon_util_dao.php');
require_once(DIR_FS_CATALOG.'checkout_by_amazon/checkout_by_amazon_order_dao.php');
require_once(DIR_FS_CATALOG.'checkout_by_amazon/checkout_by_amazon_order_status_history_dao.php');
require_once(DIR_FS_CATALOG.'checkout_by_amazon/checkout_by_amazon_cart.php');
require_once(DIR_FS_CATALOG . "checkout_by_amazon/library/merchantAtAPIs/lib/amazon/amazon_merchant_at_soap_client.php");

class checkout_by_amazon {
    var $code, $title, $description, $enabled;
    var $sort_order, $form_action_url;
    var $aws_access_id, $aws_secret_key;
    var $signing;
    var $cart_expiration;
    var $merchant_id;
    // post-order management variables
    // Used to sync Amazon orders into the oscommerce databases.
    var $post_order_management_enabled;
    var $merchant_email;
    var $merchant_password;
    var $merchant_token;
    var $merchant_name;
    // other configuration to determine:
    //      production / sandbox environments
    //      etc.
    var $operating_env;
    var $callback_required, $callback_shipping, $callback_shipping_carrier, $callback_taxes, $callback_is_shipping_taxed, $callback_processOrderOnFailure, $standard_shipping_override;
    var $fulfillment_network;
    var $success_ret_url, $cancel_ret_url;
    var $integrator_id, $integrator_name;
    var $button_size, $button_style;
    var $orderXml;


/**
 * @brief creates a new instance of checkout_by_amazon, one per instance of osCommerce 
 * @post one instance of the class is created 
 */
    function checkout_by_amazon() {

	//accquiring path defintions
	require_once(DIR_FS_CATALOG.'manage/includes/configure.php');

	$this->code = 'checkout_by_amazon';
	$this->title = MODULE_PAYMENT_CHECKOUTBYAMAZON_TEXT_TITLE;
        $this->apm_title = MODULE_PAYMENT_CHECKOUTBYAMAZON_APM_TEXT_TITLE;
	$this->description =
	    MODULE_PAYMENT_CHECKOUTBYAMAZON_TEXT_DESCRIPTION;
	$this->sort_order = MODULE_PAYMENT_CHECKOUTBYAMAZON_SORT_ORDER;
	$this->enabled =
	    (MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS == 'True');
	$this->aws_access_id =
	    trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSACCESSID);
	$this->aws_secret_key =
	    trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSSECRETKEY);
	$this->merchant_id =
	    trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTID);

	if (MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT ==
	    'Production') {
	    $this->form_action_url = PROD_ENDPOINT.$this->merchant_id;	// URL to post production orders to
	} else {
	    // posting to sandbox url
	    $this->form_action_url = SANDBOX_ENDPOINT.$this->merchant_id;
	}

	$this->signing =
	    (MODULE_PAYMENT_CHECKOUTBYAMAZON_SIGNING == 'True');
	
	$this->cart_expiration =
	    (int) trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CART_EXPIRATION);
	$this->merchant_id =
            trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTID);

        // post-order management settings
        // Used to sync Amazon orders into the oscommerce databases.
        $this->post_order_management_enabled =
            (MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDER_MANAGEMENT == 'True');
	$this->merchant_email =
            trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTEMAIL);
	$this->merchant_password =
            trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTPASSWORD);
	$this->merchant_token =
            trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTTOKEN);
	$this->merchant_name =
            trim(MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTNAME);

        // production or sandbox
	$this->operating_env =
	    MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT;

	// Fulfillment by Amazon settings. We do not support Amazon fulfillment as of now
        $this->fulfillment_network = MERCHANT_FULFILLMENT;
	
	// Optional for Callback calculations
 	if ($this->callback_required = 
	    (MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_CALLBACK == 'True')) {
		if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_SHIPPING == 'True') {
			$this->callback_shipping = 'true';
                        $this->callback_shipping_carrier = MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER;
			$this->standard_shipping_override = MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE;
                } else {
                        $this->callback_shipping = 'false';
                }
		if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_TAXES == 'True') {
			$this->callback_taxes = 'true';
                    
                        if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED == 'True') {
                            $this->callback_is_shipping_taxed = 'true';
                        }
                        else {
                            $this->callback_is_shipping_taxed = 'false';
                        }
                } else {
			$this->callback_taxes = 'false';
                }
		
                // always sent to false - because we are doing a one-to-one 
                // shipping mapping.
                // In case the callback fails, we would not know how to perform
                // the reverse shipping mapping.
                $this->callback_processOrderOnFailure = 'false';

	}

	// Optional post checkout URLs. This is to reset the cart and then redirect to the Merchant's return URL
  $this->success_ret_url = HTTP_SERVER . DIR_WS_CATALOG . 'checkout_by_amazon_order_request_handler.php?cbaAction=ResetCart';

	if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_URL != NULL) {
	    $this->callback_ret_url =
		MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_URL;
	} else {
	    $this->callback_ret_url =
		HTTP_CATALOG_SERVER.DIR_WS_CATALOG.
		'index.php';
	}

	if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CANCEL_URL != NULL) {
	    $this->cancel_ret_url =
		MODULE_PAYMENT_CHECKOUTBYAMAZON_CANCEL_URL;
	} else {
	    $this->cancel_ret_url =
		HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'index.php';
	}

	$this->integrator_id = INTEGRATOR_ID;

	$this->integrator_name = INTEGRATOR_NAME;

	// setting checkout button style/size
	// since these will be used in url, case has to be removed
	$this->button_size =
	    strtolower(MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_SIZE);
	$this->button_style =
	    strtolower(MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_STYLE);

    }

/**
 * @brief selects the code string & the title 
 * @return returns false so that checkout by amazon doesnt get displayed in the Alternate Payment page
 */
    function selection() {
        return array('id' => $this->code,
	             'module' => $this->apm_title);
    }

/**
 * @brief addresses any calls which should take place during start up
 * @post ignores checkout by amazon module in the osCommer checkout page
 */
    function before_process() {
	return false;
    }

/**
 * @brief determines if config table has been turned on
 * @return number of rows within the config table 
 */
    function check() {
	if (!isset($this->_check)) {
	    $check_query =
		tep_db_query("select configuration_value from ".
			     TABLE_CONFIGURATION.
			     " where configuration_key = 'MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS'");
	    $this->_check = tep_db_num_rows($check_query);
	}
	return $this->_check;
    }

/**
 * @brief stub function required of /payment files 
 */
    function update_status() {
    }

/**
 * @brief stub function required of /payment files
 */
    function javascript_validation() {
	return false;
    }
/**
 * @brief stub function required of /payment files
 */
    function pre_confirmation_check() {
        $this->form_action_url.= "?order-channel=apm";
    }

/**
 * @brief stub function required of /payment files
 */
    function confirmation() {
	return true;
    }

/**
 * @brief stub function required of /payment files
 */
    function process_button() {
        global $cart, $order;
        $cba_cart = new CheckoutByAmazonCart($cart, $this, $languages_id);
            $code.= APM_HTML_BUTTON_INPUT_TYPES;
             $code.=
                $this->signing ?  HTML_BUTTON_MERCHANT_SIGNED_ORDER :
                HTML_BUTTON_MERCHANT_UNSIGNED_ORDER;
            $code.= HTML_BUTTON_BEGIN_ORDER;
        // Prepare the order XML for checkout by amazon alternate payment method
            $code.= $cba_cart->GetEncodedOrderXml(true);
            // do signing if enabled
            if ($this->signing) {
                $code.= HTML_BUTTON_SIGNATURE;
                $code.= $cba_cart->GetOrderSignature();
                $code.= HTML_BUTTON_AWS_KEY_ID;
                $code.= $this->aws_access_id;
            }
        $code.= "\"/>";
        return $code;
    }

  function _doVoid($oID) {
   global $db, $messageStack;
   require_once(DIR_FS_CATALOG . 'checkout_by_amazon/checkout_by_amazon_order_processor.php');

   $processor = new OrderProcessor();
   $utilDao = new UtilDAO();
   $login = MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTEMAIL;
   $password = MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTPASSWORD;
   $merchant_token = MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTTOKEN;
   $merchant_name = MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTNAME;
   $client =  new AmazonMerchantAtSoapClient($login, $password, $merchant_token, $merchant_name);

                /* Amazon Payments Code Starts Here */
   $amazon_order_id = trim(tep_db_input($_POST['amazon_order_id']));
   $status_code = trim(tep_db_input($_POST['status_code']));
   $orderDao = new OrderDAO();
   switch($status_code){
     case AMAZON_STATUS_CANCELLED:
        // Only cancel the order if it has not been delivered or
        // refunded.
        if ($orderDao->isOrderDelivered($oID) ||  $orderDao->isOrderRefunded($oID)) {
               $messageStack->add_session(AMAZON_WARNING_CANNOT_CANCEL_DELIVERED_ORDER, 'warning');
               tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
                break;
        }
        // TODO: Make this not go through a post and a facade instead                                                               // tell Amazon systems to cancel the order
        $data = array('OrderID' => $oID);
        $result = $processor->cancelOrder($data);

              // check if cancelling the order succeeded, if not print the
              // warning message and don't proceed.                                                                         
         if ($result == null) {
                $messageStack->add_session(AMAZON_WARNING_ORDER_NOT_UPDATED, 'warning');
                tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'))
;
                break;
         }

         // get transaction id to push in the status history comments
         $amazonProcessingTransactionId = $result;

         $messageStack->add_session(AMAZON_ORDER_SYSTEM_UPDATED, 'success');
         $operation = AMAZON_STATUS_CANCELLED;
         $comments = MERCHANT_CANCEL_CONFIRMATION_TEXT;
         break;
     case AMAZON_STATUS_DELIVERED:
        $shippingCarrier = tep_db_prepare_input($_POST['shippingCarriers']);
        $shippingService = tep_db_prepare_input($_POST['shipping_service']);
        $shippingTrackingNumber = tep_db_prepare_input($_POST['tracking_id']);
        if($shippingCarrier== '0' || empty($shippingService) || empty($shippingTrackingNumber)){
                $messageStack->add_session('Please fill the required values for confirming shipment', 'warning');
                tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL'));
        }else{
                $data = array('OrderID' => $oID,
                              'ShippingCarrier' => $shippingCarrier,
                              'ShippingService' => $shippingService,
                              'ShippingTrackingNumber' => $shippingTrackingNumber);
                $result = $processor->shipOrder($data);
              // check if shipping the order succeeded, if not print the
              // warning message and don't proceed.
                if ($result == null) {
                 $messageStack->add_session(AMAZON_WARNING_ORDER_NOT_UPDATED, 'warning');
                 tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'))
;
                 break;
              }


              // get transaction id to push in the status history comments
       $amazonProcessingTransactionId = $result;
       $messageStack->add_session(AMAZON_ORDER_SYSTEM_UPDATED, 'success');
       }
       $operation = AMAZON_STATUS_DELIVERED;
       $comments = $shippingCarrier . " => " . $shippingService . " => " . $shippingTrackingNumber;
       break;
     case AMAZON_STATUS_REFUNDED:
       $refund_reason = tep_db_prepare_input($_POST['refund_reason']);
       $comments = $refund_reason;
       if($refund_reason == '0'){
       $messageStack->add_session('Please select the reason for Refund!', 'warning');
       tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL'));
       }else{
        if (!$orderDao->isOrderDelivered($oID)) {
                $messageStack->add_session(AMAZON_WARNING_CANNOT_REFUND_ORDER, 'warning');
                tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'))
;
                break;
         }

              // TODO: Make this not go through a post and a facade instead
              // tell Amazon systems to refund the order
         $data = array('OrderID' => $oID,
                       'RefundReason' => $refund_reason);

         $result = $processor->refundOrder($data);
              // check if refunding the order succeeded, if not print the
              // warning message and don't proceed.
         if ($result == null) {
                $messageStack->add_session(AMAZON_WARNING_ORDER_NOT_UPDATED, 'warning');
                tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'))
;
                break;
          }

              // get transaction id to push in the status history comments
          $amazonProcessingTransactionId = $result;

          $messageStack->add_session(AMAZON_ORDER_SYSTEM_UPDATED, 'success');
        }
        $operation = AMAZON_STATUS_REFUNDED;
        break;
      default:
     // do nothing as of now
   }
   /* insert into amazon_orders_status_history */
   $statusHistory = new OrderStatusHistoryDAO();

   $amzn_order_id = $orderDao->getAmazonOrderID($oID); //get amazon order_id from osCommerce order_id
   $statusHistory->insertAmazonOrderStatusHistory($oID, $amzn_order_id, $processor->mfa_xml, $amazonProcessingTransactionId,
                                                  0, $operation, $comments);
  }


/**
 * @brief stub function required of /payment files
 */
    function after_process() {
        global $insert_id, $HTTP_GET_VARS, $order;
        $utilDao = new UtilDAO();
        $orderDao = new OrderDAO();
        $amznOrderID = $HTTP_GET_VARS['amznPmtsOrderIds'];
        $statusHistory = new OrderStatusHistoryDAO();
        $comments = AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION . $amznOrderID;
        $storedOrderStatusHistory = $statusHistory->getOrderStatusHistoryWithComments($insert_id, '');
        $statusHistory->updateOrderStatusHistory($storedOrderStatusHistory['orders_status_history_id'], MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING, true, $comments);
        $orderDao->updatePaymentMethod($insert_id, 'Amazon Payments');
        $utilDao->updateStatus(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING, $insert_id);

        return;
    }

/**
 * @brief stub function required of /payment files
 */
    function output_error() {
	return false;
    }

/**
 * @brief installs all the merchant settings in database 
 * @post entires are installed in database, with assiocated strings (will be visible in Admin UI)
 * @see keys() 
 * @note all entires in install must be entered into mySQL via keys()
 * @note this function required by this name for admin/modules.php. Allows it to be called in UI
 * @note the style types are hardcoded here, otherwise Button Style will not highlight
 *	default value in UI menu  
 */
    function install() {

        $mandatory_flag = "<font color=\'red\'><b> * </b></font>";

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<br/>GENERAL OPTIONS<br/><hr/><br/>Enable Checkout by Amazon', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS', 'True', '<br/>Allow customers to use Checkout by Amazon on your web store<br/><hr>".$mandatory_flag." Indicates mandatory parameters if \'Enable Checkout by Amazon\' is set to True.', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Checkout by Amazon merchantID".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTID', '', '<a href=\'https://sellercentral.amazon.com/gp/cba/seller/account/settings/user-settings-view.html/ref=sc_navbar_m1k_cba_order_pipe_settings\' target=\'_blank\'/>Click here to get your MerchantID</a>', '6', '4', now())");

        // post-order management information
        tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<br/><br/>ORDER MANAGEMENT OPTIONS<br/><hr/><br/>Enable Ord Mgmt', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDER_MANAGEMENT', 'False', '<br/>Manage orders placed through Checkout by Amazon within your OSCommerce admin UI<br/><hr>".$mandatory_flag." Indicates mandatory parameters if \'Enable Ord Mgmt\' is set to True.', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Login Id".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTEMAIL', '', '', '6', '4', now())");
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Password ".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTPASSWORD', '', '', '6', '4', now())");
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Token".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTTOKEN', '', '<a href=\'https://sellercentral.amazon.com/gp/seller/configuration/account-info-page.html/ref=sc_navbar_m1k_seller_cfg\' target=\'_blank\'/>Click here to get your Merchant Token</a>', '6', '4', now())");
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Name".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTNAME', '', '<a href=\'https://sellercentral.amazon.com/gp/seller/configuration/account-info-page.html/ref=sc_navbar_m1k_seller_cfg\' target=\'_blank\'/>Click here to get your Merchant Name</a>', '6', '4', now())");

        // signing
        tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<br/>SIGNING OPTIONS<br/><hr/><br/>Enable Order Signing', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_SIGNING', 'True', '<i>Please note that Amazon recommends Signed carts. The signature helps to validate the cart is not manipulated between your website and Amazon.</i><br/>".$mandatory_flag."Indicates mandatory params if \'Enable Order Signing\' is True', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Operating environment', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT', 'Sandbox', 'Select whether Checkout by Amazon should operate in the test sandbox or the live production environment. <br><i>Note: Currently Post Order Management cannot be tested on Sandbox</i>', '6', '3', 'tep_cfg_select_option(array(\'Production\', \'Sandbox\'), ', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Diagnostic Logging', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_DIAGNOSTIC_LOGGING', 'False', 'Enables diagnostic logging for debugging this OSCommerce plugin.', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("insert into ".TABLE_CONFIGURATION.
                       " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable Promotion code Display', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_DISABLE_PROMO_CODE','True', 'By enabling you can hide the input box where buyer enters the claim code and effectively prevent the promotion from being applied', '6', '3','tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
                     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('AWS Access ID".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSACCESSID', '', '<a href=\'https://sellercentral.amazon.com/gp/cba/seller/accesskey/showAccessKey.html/ref=sc_tab_home_cba_access_key\' target=\'_blank\'/>Click here to get your AWS Access ID</a>', '6', '4', now())");
		
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('AWS Secret Key ".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSSECRETKEY', '', '<a href=\'https://sellercentral.amazon.com/gp/cba/seller/accesskey/showAccessKey.html/ref=sc_tab_home_cba_access_key\' target=\'_blank\'/>Click here to get your AWS Secret Key</a>', '6', '4', now())");
	
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cart expiration time (in minutes)', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CART_EXPIRATION', '0', 'The number of minutes a cart is valid for (0 for no expiration)', '6', '4', now())");
	
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_SORT_ORDER', '0', 'Order in which different payment options you have enabled are displayed. Lowest is displayed first.', '6', '0', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Success Return Page', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_RETURN_URL','', 'Please enter the complete URL of the page you would like your customers to return after a purchase.  If you choose not to specify one, the index osCommerce page will be used', '6', '4', now())");

        // Map order status
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('<br/>MAPPING ORDER STATUS OPTIONS<br/><hr/><br/>New order Status', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING', '0', 'What should be the order status when a new order placed by your customer is pending review from Amazon? This state will indicate that the order CANNOT be processed from your end.<br />Recommended: <strong>Processing</strong>', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Ready to Ship order Status', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW', '0', 'What should be the order status after Amazon processes it? This state will indicate that the order is pending shipment from your end.<br/>Recommended: <strong>Pending</strong>', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Deliver order Status', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_DELIVERED', '0', 'What should be the order status after you deliver it? The order will move into this state when you click <img src=\'images/confirm_shipment.jpg\' align=\'absmiddle\'/> button.<br />Recommended: <strong>Delivered</strong>', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Cancel order Status', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED', '0', 'What should be the order status when the order gets canceled? The order will move into this state when you click <img src=\'images/cancel_order.jpg\' align=\'absmiddle\'/> button or when the buyer\amazon cancels it.<br />Recommended: <strong>Canceled</strong>', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Refund Order Status', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_REFUNDED', 0, 'What should be the order status after you apply a refund on it? The order will move into this state when you click <img src=\'images/refund_order.jpg\' align=\'absmiddle\'/> button.<br />Recommended: <strong>Refund</strong>', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
                                                                                                                                                                
        // callbacks
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<br/>CALLBACK OPTIONS<br/><hr/><br/>Enable Callbacks', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_CALLBACK','False', '<i>The Callback API lets you specify shipping and taxes using your own application logic at the time an order is placed when using Checkout by Amazon</i>', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	
        $callback_url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/catalog/checkout_by_amazon_callback_processor.php'; 
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Callback Page".$mandatory_flag."', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_URL','$callback_url', 'Please enter the complete URL of the Callback page. use HTTPS if you are Operating environment is <b>Production</b> else use HTTP.  If you choose not to specify one, the index osCommerce page will be used', '6', '4', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shipping Calculations', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_SHIPPING','True', 'Should dynamic shipping calculations be enabled as part of Callbacks', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("insert into ".TABLE_CONFIGURATION.
		       " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Carrier', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER','USPS', 'Please select which carrier should be used to compute shipping rates. You must install and enable the selected carrier in Administration > Modules > Shipping first.', '6', '3','tep_shipping_carrier_use_function', 'tep_pull_down_amz(\'MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER\',array(\'USPS\', \'UPS XML\',\'UPS Choice\', \'FedEx\', \'None\'),array(\'USPS\', \'UPSXML\',\'UPS\', \'FedEx1\', \'None\'),', now())");
        
       tep_db_query("insert into ".TABLE_CONFIGURATION.
                     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('CBA Standard Shipping Override', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE','None', 'Please select if you would like to override the shipping carrier selected above for CBA Standard Shipping method (Domestic only).', '6', '3', 'tep_std_ovr_use_function', 'tep_pull_down_amz(\'MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE\',array(\'Flat Rate\',\'Per Item\',\'Table Rate\',\'Zone Rates\',\'None\'),array(\'flat\',\'item\',\'table\',\'zones\',\'None\'),', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Tax Calculations', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_TAXES','True', 'Should dynamic tax calculations be enabled as part of Callbacks', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Is Shipping and Handling Taxed', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED','True', 'Please specify whether the shipping amount should be taxed as part of Callbacks', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        // other configuration
	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cancelation Return Page', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_CANCEL_URL', '', 'Please enter the complete URL of the page you would like your customers to return to if they abandon or cancel an order.  If you do not enter one, the default is the main osCommerce catalog page', '6', '4', now())");

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Checkout Button Size', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_SIZE', 'Large', 'Creates either a large(151x27) or medium(126x24) Checkout By Amazon button.', '6', '3', 'tep_cfg_select_option(array(\'Large\',\'Medium\'), ', now())");
	$cba_orange_button = "https://images-na.ssl-images-amazon.com/images/G/01/cba/images/buttons/btn_Chkout-orange-medium-white.gif";

	tep_db_query("insert into ".TABLE_CONFIGURATION.
		     " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Button Style', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_STYLE', 'Orange', 'Choose from two styles of buttons', '6', '3', 'tep_cfg_select_option(array(\'Orange\', \'Tan\'), ', now())");        

        // Enable storing shipping at the order product level as that is required for 
        // refund adjustments
        $createTableQuery = tep_db_query("show tables like '" . TABLE_AMAZON_ORDERS_PRODUCTS . "'");
	
        if (!tep_db_num_rows($createTableQuery)) {

            // TODO: Add warning that database table structure will be modified before module runs
            // TODO: Add warning if this update fails
            //
            // create amazon_orders_products table to store order shipping component, 
            // since this data is required for refunds and cancellations
            
            tep_db_query("create TABLE " . TABLE_AMAZON_ORDERS_PRODUCTS . " (" .
                         " orders_products_id int(11) not null," .
                         " products_shipping decimal(15,4) not null default 0," .
                         " products_shipping_tax decimal(15,4) not null default 0," .
                         " products_promotion_price decimal(15,4) not null default 0," .
                         " products_promotion_shipping decimal(15,4) not null default 0," .
                         " products_promotion_tax decimal(15,4) not null default 0," .
                         " products_promotion_claim_code varchar(64) not null default ''," .
                         " products_promotion_merchant_promotion_id varchar(64) not null default ''," .
                         " PRIMARY KEY (orders_products_id))");
        }

        $createTableQuery = tep_db_query("show tables like '" . TABLE_AMAZON_ORDERS_LOCK . "'");
	
        if (!tep_db_num_rows($createTableQuery)) {
            tep_db_query("create TABLE " . TABLE_AMAZON_ORDERS_LOCK . " (" .
                         " lock_key varchar(255) not null," .
                         " lock_value varchar(255) not null," .
                         " PRIMARY KEY (lock_key))");
        }

        $createTableQuery = tep_db_query("show tables like '" . TABLE_AMAZON_ORDERS_STATUS_HISTORY . "'");
        if (!tep_db_num_rows($createTableQuery)) {
            tep_db_query("CREATE TABLE " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . "(" .
                         "  id int(11) NOT NULL auto_increment, " .
                         "  amazon_order_id varchar(25) NOT NULL, " .
                         "  orders_id int(11) NOT NULL, " .
                         "  xml text  NOT NULL, " .
                         "  transactionID varchar(100) NOT NULL default '', " .
                         "  status_of_operation int(10) NOT NULL default '0', " .
                         "  amazon_order_status varchar(10) NOT NULL, " .
                         "  comments varchar(255) NOT NULL, " .
                         "  created_on datetime default NULL, " .
                         "  modified_on datetime default NULL, " .
                         "  PRIMARY KEY  (id), " .
                         "  UNIQUE KEY amazon_order_transaction_id_unique_key(amazon_order_id, transactionID, amazon_order_status, status_of_operation))");
        }

        $createTableQuery = tep_db_query("show tables like '" . TABLE_AMAZON_IOPN . "'");
        if (!tep_db_num_rows($createTableQuery)) {
            tep_db_query("CREATE TABLE " . TABLE_AMAZON_IOPN . "(" .
                         "  notificationReferenceID varchar(40) NOT NULL default '', " .
                         "  order_id varchar(20) NOT NULL default '0', " .
                         "  notification_txt text, " .
                         "  created_on datetime default NULL, " .
                         "  PRIMARY KEY  (notificationReferenceID))");
        }

        // insert the following states into the orders status table
        // these are not available in the default installation of OSCommerce

                                                                                                                                                            
        // The system error status
        tep_db_query("insert ignore into ".TABLE_ORDERS_STATUS.
                     " (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) values ('".AMAZON_STATUS_SYSTEM_ERROR."', '1', 'Amazon System Error', '1', '1')");
        tep_db_query("insert ignore into ".TABLE_ORDERS_STATUS.
                     " (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) values ('".AMAZON_STATUS_SYSTEM_ERROR."', '2', 'Amazon Error', '1', '1')");
        tep_db_query("insert ignore into ".TABLE_ORDERS_STATUS.
                     " (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) values ('".AMAZON_STATUS_SYSTEM_ERROR."', '3', 'Amazon Error', '1', '1')");


    }

/**
 * @brief removes a configuration entry from the mySQL database 
 * @post a configuration entry has been removed 
 */
    function remove() {
	tep_db_query("delete from ".TABLE_CONFIGURATION.
		     " where configuration_key in ('".implode("', '",$this->keys())."')");
    }

/**
 * @brief returns the list of configuration keys for merchant configuration 
 * @return an array of the configuration keys associated with the install function for the mySQL database 
 * @see install()
 */
    function keys() {
        return array('MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTID',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_SIZE',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_BUTTON_STYLE',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_SORT_ORDER',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CART_EXPIRATION',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CANCEL_URL',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_RETURN_URL',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_DISABLE_PROMO_CODE',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_DIAGNOSTIC_LOGGING',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_SIGNING',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSACCESSID',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_AWSSECRETKEY',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDER_MANAGEMENT',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTEMAIL',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTPASSWORD',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTTOKEN',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CBAMERCHANTNAME',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_DELIVERED',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_REFUNDED',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_CALLBACK',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_URL',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_SHIPPING',
		     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_TAXES',
                     'MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED');
    }
}

function tep_draw_input_password_field ($key_value, $key = '') {
    $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
    return '<input type="password" name="' . $name . '"/>';  
}

/*
 *  function to generate the html select drop down menu
 */
function tep_pull_down_amz($name, $names, $values, $key_value) {
    $field = '<select name="configuration[' . tep_output_string($name) . ']"';
    $field .= '>';

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]) . '"';
      if ($key_value == $values[$i]) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($names[$i], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    return $field;
}
?>
