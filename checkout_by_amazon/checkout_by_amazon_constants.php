<?php
/**
 * @brief Various constants for Checkout by Amazon code  
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Allison Naaktgeboren
 * @author Joshua Wong
 * @copyright 2007-2012 Amazon Technologies, Inc
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
    // constant tags for XML
define("INTEGRATOR_NAME", 'CBA_OSCommerce_Standard_2.3.1_10_2012');
define("INTEGRATOR_ID", 'A2E8RSUU6OBDEI');
define("XMLNS_VERSION_TAG", 'http://payments.amazon.com/checkout/2009-05-15/');
define("AMAZON_ORDERID_LENGTH", 19);

	// maximum lengths of string fields in XML, (including 0)
define("MAX_DESC_LEN", 254);
define("MAX_TITLE_LEN", 99);
define("MAX_SKU_LEN", 99);
define("MAX_CATEGORY_LEN", 39);

	// types of fulfillment 
define("MERCHANT_FULFILLMENT", "MERCHANT");
define("AMAZON_FULFILLMENT", "AMAZON_NA");

	// button values                                          
define("STYLE0_NAME", 'tan');
define("STYLE1_NAME", 'orange');
define("STYLE1_DESC", 'Classic Amazon orange & blue.');
define("STYLE0_DESC", 'A sleek tan & blue combination.');

	//size of "order" tag 
define("LEN_ORDER_TAG", 5);
	
	//Endpoint URLs for orders
define("SANDBOX_ENDPOINT", 'https://payments-sandbox.amazon.com/checkout/');
define("PROD_ENDPOINT", 'https://payments.amazon.com/checkout/');

	//URLS for Amazon 1-click/express, order summary popup scripts
	//invoked from header of success page, see checkout_by_amazon_success.php  
define("SANDBOX_1_CLICK", '"https://images-na.ssl-images-amazon.com/images/G/01/cba/js/widget/sandbox/widget.js"');
define("PROD_1_CLICK", '"https://images-na.ssl-images-amazon.com/images/G/01/cba/js/widget/widget.js"');
define("CBA_POPUP_STYLE_SHEET",'"https://images-na.ssl-images-amazon.com/images/G/01/cba/styles/AmazonPaymentsThankYou.css"');

define("PROD_POPUP_ORDER_SUMMARY", '"https://images-na.ssl-images-amazon.com/images/G/01/cba/js/widget/AmazonPaymentsThankYou.js"');
define("SANDBOX_POPUP_ORDER_SUMMARY", '"https://images-na.ssl-images-amazon.com/images/G/01/cba/js/widget/sandbox/AmazonPaymentsThankYou.js"');


	//Style sheet, jquery setup scripts
define("CBA_JQUERY_SETUP",'"https://images-na.ssl-images-amazon.com/images/G/01/cba/js/jquery.js"');
define("CBA_STYLE_SHEET", '"https://images-na.ssl-images-amazon.com /images/G/01/cba/styles/one-click.css"');

	//partial strings in the button URL
define("HTML_BUTTON_FORM_METHOD",'<form method=POST action="');
define("HTML_BUTTON_INPUT_TYPES", '"><input type="hidden" name="order-input" value="type:');
define("HTML_BUTTON_MERCHANT_SIGNED_ORDER", 'merchant-signed-order/aws-accesskey/1');
define("HTML_BUTTON_MERCHANT_UNSIGNED_ORDER", 'unsigned-order');
define("HTML_BUTTON_BEGIN_ORDER", ';order:');
define("HTML_BUTTON_SIGNATURE",  ';signature:');
define("HTML_BUTTON_AWS_KEY_ID", ';aws-access-key-id:' );
define("HTML_BUTTON_MAIN_BUTTON_LINK", '"><input alt="Checkout by Amazon, Amazon Payments" src="https://payments.amazon.com/gp/cba/button?ie=UTF8&color=');
define("HTML_BUTTON_SIZE_TAG", '&size=');
define("HTML_BUTTON_END_IMAGE", '" type="image"></form>');
define("HTML_BUTTON_CLIENT_REQUEST_ID_CART_ID", 'cartId');
define("HTML_BUTTON_CLIENT_REQUEST_ID_AFFILIATE_REFERENCE_ID", 'affilRefId');
define("HTML_BUTTON_CLIENT_REQUEST_ID_AFFILIATE_CLICKTHROUGH_ID", 'affilClickId');
define("APM_HTML_BUTTON_INPUT_TYPES", '<input type="hidden" name="order-input" value="type:');
	//HTML string representing Sandbox environment warning 
define("HTML_SANDBOX_WARNING_OPENING",'' );
define("HTML_SANDBOX_WARNING_CLOSING", '');


        // define table required for storing additional post-order management data
define('TABLE_AMAZON_ORDERS_PRODUCTS', 'amazon_orders_products');
        // define table required for storing additional post-order management meta-data
define('TABLE_AMAZON_ORDERS_LOCK', 'amazon_orders_lock');
        // define table required for IOPN
define('TABLE_AMAZON_IOPN', 'amazon_iopn');
	// define table required for orders_status_history
define('TABLE_AMAZON_ORDERS_STATUS_HISTORY', 'amazon_orders_status_history');

        // Define order acknowledgement xml in parts
define('AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_START',
  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
  "     <AmazonEnvelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"amzn-envelope.xsd\">" .
  "      <Header>" .
  "         <DocumentVersion>1.01</DocumentVersion>" .
  "         <MerchantIdentifier>[MERCHANT TOKEN]</MerchantIdentifier>" .
  "      </Header>" .
  "      <MessageType>OrderAcknowledgement</MessageType>");

        // Map oscommerce order id to amazon order id
        // so that later, when the merchant ships the order,
        // they can use oscommerce's order id as a reference to the amazon
        // order in the ship confirmation request.
define('AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_BODY_START',
  "      <Message>" .
  "         <MessageID>[MESSAGE ID]</MessageID>" .
  "         <OrderAcknowledgement>" .
  "            <AmazonOrderID>[AMAZON ORDER ID]</AmazonOrderID>" .
  "          <StatusCode>Success</StatusCode>");

define('AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_BODY_ITEM',
  "      <Item>" .
  "         <AmazonOrderItemCode>[AMAZON ORDER ITEM ID]</AmazonOrderItemCode>" .
  "      </Item>");

define('AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_BODY_END',
  "         </OrderAcknowledgement>" .
  "      </Message>");

define('AMAZON_MESSAGE_ORDER_ACKNOWLEDGEMENT_END',
       "</AmazonEnvelope>");


define('AMAZON_MESSAGE_ORDER_CANCELLATION_BODY_START',
  "      <Message>" .
  "         <MessageID>[MESSAGE ID]</MessageID>" .
  "         <OperationType>Update</OperationType>" .
  "         <OrderAcknowledgement>" .
  "            <AmazonOrderID>[AMAZON ORDER ID]</AmazonOrderID>" .
  "          <StatusCode>Failure</StatusCode>");


        // Message indicates that an order is shipped.
define('AMAZON_MESSAGE_ORDER_FULFILLMENT_START',
  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
  "     <AmazonEnvelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"amzn-envelope.xsd\">" .
  "      <Header>" .
  "         <DocumentVersion>1.01</DocumentVersion>" .
  "         <MerchantIdentifier>[MERCHANT TOKEN]</MerchantIdentifier>" .
  "      </Header>" .
  "      <MessageType>OrderFulfillment</MessageType>");

define('AMAZON_MESSAGE_ORDER_FULFILLMENT_BODY_START',
  "      <Message>" .
  "         <MessageID>[MESSAGE ID]</MessageID>" .
  "         <OrderFulfillment>" .
  "            <AmazonOrderID>[AMAZON ORDER ID]</AmazonOrderID>" .
  "            <FulfillmentDate>[MERCHANT FULFILLMENT DATE]</FulfillmentDate>" .
  "            <FulfillmentData>" .
  "               <CarrierCode>[FULFILLMENT CARRIER CODE]</CarrierCode>" .
  "               <ShippingMethod>[FULFILLMENT SHIPPING METHOD]</ShippingMethod>" .
  "               <ShipperTrackingNumber>[FULFILLMENT SHIPPING TRACKING NUMBER]</ShipperTrackingNumber>" .
  "            </FulfillmentData>");

define('AMAZON_MESSAGE_ORDER_FULFILLMENT_BODY_END',
  "         </OrderFulfillment>" .
  "      </Message>");

define('AMAZON_MESSAGE_ORDER_FULFILLMENT_END',
       "</AmazonEnvelope>");


        // Message to indicates a order refund.
define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_START',
  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
  "     <AmazonEnvelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"amzn-envelope.xsd\">" .
  "      <Header>" .
  "         <DocumentVersion>1.01</DocumentVersion>" .
  "         <MerchantIdentifier>[MERCHANT TOKEN]</MerchantIdentifier>" .
  "      </Header>" .
  "      <MessageType>OrderAdjustment</MessageType>");


define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_BODY_START',
  "      <Message>" .
  "         <MessageID>[MESSAGE ID]</MessageID>" .
  "         <OrderAdjustment>" .
  "            <AmazonOrderID>[AMAZON ORDER ID]</AmazonOrderID>");


define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_START',  
  "            <AdjustedItem>" .
  "               <AmazonOrderItemCode>[AMAZON ORDER ITEM CODE]</AmazonOrderItemCode>" .
  "               <AdjustmentReason>[ADJUSTMENT REASON]</AdjustmentReason>" .
  "               <ItemPriceAdjustments>" .
  "                  <Component>" .
  "                     <Type>Principal</Type>" .
  "                     <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS PRINCIPAL]</Amount>" .
  "                  </Component>" .
  "                  <Component>" .
  "                     <Type>Shipping</Type>" .
  "                     <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS SHIPPING]</Amount>" .
  "                  </Component>" .
  "                  <Component>" .
  "                     <Type>Tax</Type>" .
  "                     <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS TAX]</Amount>" .
  "                  </Component>" .
  "                  <Component>" .
  "                     <Type>ShippingTax</Type>" .
  "                     <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS SHIPPING TAX]</Amount>" .
  "                  </Component>" .
  "               </ItemPriceAdjustments>");


define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_START',  
  "               <PromotionAdjustments>" .
  "                  [ITEM PRICE ADJUSTMENTS PROMOTION MERCHANT PROMOTION ID SECTION]" .
  "                  <Component>" .
  "                     <Type>Principal</Type>" .
  "                     <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS PROMOTION PRINCIPAL]</Amount>" .
  "                  </Component>" .
  "                  [ITEM PRICE ADJUSTMENTS PROMOTION SHIPPING SECTION]" .
  "               </PromotionAdjustments>");
define('AMAZON_MESSGAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_MERCHANT_PROMOTION_ID_SECTION',
  "<MerchantPromotionID>[ITEM PRICE ADJUSTMENTS PROMOTION MERCHANT PROMOTION ID]</MerchantPromotionID>");
define('AMAZON_MESSGAGE_PAYMENT_ADJUSTMENT_ITEM_PROMOTION_SHIPPING_SECTION',
    "<Component>" .
    "   <Type>Shipping</Type>" .
    "   <Amount currency=\"USD\">[ITEM PRICE ADJUSTMENTS PROMOTION SHIPPING]</Amount>" .
    "</Component>");

define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_ITEM_END',
  "            </AdjustedItem>");

define('AMAZON_MESSAGE_PAYMENT_ADJUSTMENT_END',
  "         </OrderAdjustment>" .
  "      </Message>" .
  "     </AmazonEnvelope>");


        // determine whether order is a Checkout by Amazon order or not.
define('AMAZON_ORDER_CHANNEL_KEY', 'orderChannel');
define('AMAZON_ORDER_CHANNEL_VALUE', 'Amazon Checkout (Live)');
define('AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE', 'Amazon Payment Method (Live)');
define('AMAZON_ORDER_URL_KEY', 'url');
define('AMAZON_ORDER_AFFILIATE_REFERENCE_KEY', 'affilRefId');
define('AMAZON_ORDER_AFFILIATE_CLICKTHROUGH_KEY', 'affilClickId');

define('AMAZON_PROCESSING_MESSAGE_ORDER_READY_TO_BE_SHIP', "Checkout by Amazon Order ready to be delivered");
define('AMAZON_PROCESSING_MESSAGE_ORDER_BUYER_AMAZON_CANCEL', "Checkout by Amazon Order cancelled by buyer/amazon");
define('AMAZON_PROCESSING_MESSAGE_ORDER_CANCELLED', 'Checkout by Amazon Order has been cancelled ');
define('AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION', 'Checkout by Amazon Order Number: ');
define('AMAZON_PROCESSING_MESSAGE_ORDER_ITEM_METADATA_INFORMATION', "Metadata:\n");
define('AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_UPDATE', "Order Status Notification:\n\nStatus: PROCESSING\nTransaction id: [TRANSACTION_ID]\nDate/Time: [DATE_TIME]\n\nDetails: We are now processing your order update at Amazon.\nPlease refresh this page in a couple of minutes to see if your update completed successfully.");
define('AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_UPDATE_COMPLETE', "Order Status Notification:\n\nStatus: SUCCESS\nTransaction id: [TRANSACTION_ID]\nDate/Time: [DATE_TIME]\n\nDetails: We have now completed processing your order update at Amazon.");
define('AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_UPDATE_FAILED', "Order Status Notification:\n\nStatus: FAILED\nTransaction id: [TRANSACTION_ID]\nDate/Time: [DATE_TIME]\n\nDetails: We have failed to process your order update at Amazon.");

define('AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_SYSTEM_ERROR', "Processing Checkout by Amazon order status update *FAILED*.\nThis may mean that there is a issue retrieving your order information from Amazon. Please check the status of your order in https://sellercentral.amazon.com.\nIf you still have problems with your order, please contact Amazon's Technical Account Management at: https://sellercentral.amazon.com/gp/contact-us/contact-amazon-form.html");
define('AMAZON_PROCESSING_MESSAGE_TRANSACTION_ID_EXPRESSION', "/Transaction id: [0-9]+/");
define('AMAZON_PROCESSING_MESSAGE_TRANSACTION_ID_SPLIT_EXPRESSION', "Transaction id: ");
define('AMAZON_PROCESSING_MESSAGE_ORDERS_WITH_PENDING_STATUS', "Order Status Notification:\n\nStatus: PROCESSING");

          // Amazon warning shown on orders details page if a merchant tries to 
          // mark an order as shipped, but Amazon systems are not updated.
define('AMAZON_WARNING_ORDER_NOT_UPDATED', 'Warning: Unable to communicate with Amazon systems that the order was shipped. Please try again in 15 minutes.');
define('AMAZON_WARNING_CANNOT_DELIVER_CANCELLED_OR_REFUNDED_ORDER', 'Warning: Unable to deliver an already cancelled or refunded order.');
define('AMAZON_WARNING_CANNOT_CANCEL_DELIVERED_ORDER', 'Warning: Unable to cancel an already delivered order. Please refund the order instead.');
define('AMAZON_WARNING_CANNOT_REFUND_ORDER', 'Warning: Unable to refund an order that has not been delivered yet. Please cancel the order instead.');
define('AMAZON_ORDER_SYSTEM_UPDATED', 'Success: Order update to Amazon in progress.');
define('AMAZON_ORDERS_SYNCHRONIZING', 'Success: Synchronizing with Amazon orders in progress.');

define('AMAZON_WARNING_CUSTOMER_NOTIFICATIONS_DISABLED', '(Disabled since Amazon<br/>already sends<br/>notification e-mails)');

          // Used in admin/orders.php to allow merchants to enter in shipping 
          // information for Amazon orders.
          // deserialize this constant to use it.
          // Unsupported carriers as of now: Airborne, Smartmail, LaPoste, PFI,
          // ParcelForce, RoyalMail, CanadaPost, Chronopost, DeutschePost, Hermes, ParcelNet, Eagle

// Required for Amazon Orders Status

define('AMAZON_STATUS_PAYMENT_PENDING', 1000);
define('AMAZON_STATUS_UNSHIPPED', 1001);
define('AMAZON_STATUS_DELIVERED', 1002);
define('AMAZON_STATUS_CANCELLED', 1003);
define('AMAZON_STATUS_REFUNDED', 1004);
define('AMAZON_STATUS_SYSTEM_ERROR', 1005);

// Type of operation/status of an order
$oscommerce_amazon_status_name = array(
	AMAZON_STATUS_PAYMENT_PENDING 	=> 'PROCESSING',
	AMAZON_STATUS_UNSHIPPED		=> 'PENDING',
	AMAZON_STATUS_DELIVERED		=> 'DELIVERED',
	AMAZON_STATUS_CANCELLED		=> 'CANCELLED',
	AMAZON_STATUS_REFUNDED		=> 'REFUNDED',
	AMAZON_STATUS_SYSTEM_ERROR 	=> 'SYSTEM ERROR'
);

// Define mapping 
$oscommerce_amazon_order_status_mapping = array(
	AMAZON_STATUS_PAYMENT_PENDING 	=> MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING,
	AMAZON_STATUS_UNSHIPPED		=> MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW,
	AMAZON_STATUS_DELIVERED		=> MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_DELIVERED,
	AMAZON_STATUS_CANCELLED		=> MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED,
	AMAZON_STATUS_REFUNDED		=> MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_REFUNDED,
	AMAZON_STATUS_SYSTEM_ERROR	=> AMAZON_STATUS_SYSTEM_ERROR
);

// Status of an Amazon Order
define('AMAZON_ORDER_INITIATED', 0);
define('AMAZON_ORDER_SUCCESS', 1);
define('AMAZON_ORDER_FAILURE', 2);
define('AMAZON_ORDER_TIMEOUT', 3);


define('AMAZON_ORDER_PROCESSING_INFO', 'Order Status Notification:');
define('CHECKOUT_BY_AMAZON_ORDERS', 'Checkout by Amazon Order Number:');

$shippingCarriers = array(
                array('id'=>'0','text'=>'Select a Carrier'),
                array('id'=>'USPS','text'=>'USPS'),
                array('id'=>'UPS','text'=>'UPS'),
                array('id'=>'FedEx','text'=>'FedEx'),
                array('id'=>'DHL','text'=>'DHL'),
                array('id'=>'NipponExpress','text'=>'NipponExpress')
);

define('AMAZON_SHIPPING_CARRIERS', serialize($shippingCarriers));

$refund_reason = array(
        array('id'=>'0','text'=>'Select a Reason'),
        array('id'=>'NoInventory','text'=>'No Inventory'),
        array('id'=>'CustomerReturn','text'=>'Customer Return'),
        array('id'=>'GeneralAdjustment','text'=>'General Adjustment'),
        array('id'=>'CouldNotShip','text'=>'Could Not Ship'),
        array('id'=>'DifferentItem','text'=>'Different Item'),
        array('id'=>'CustomerCancel','text'=>'Customer Cancel'),
        array('id'=>'ProductOutofStock','text'=>'Product Out of Stock'),
        array('id'=>'CustomerAddressIncorrect','text'=>'Customer Address Incorrect')
);


define('ENTRY_AMAZON_SHIPPING_CARRIER', 'Carrier:');
define('ENTRY_AMAZON_SHIPPING_SERVICE', 'Shipping Service:');
define('ENTRY_AMAZON_SHIPPING_TRACKING_NUMBER', 'Tracking ID:');

define('ORDER_SYNCHRONIZATION_LOCK_KEY', 'MODULE_PAYMENT_CHECKOUTBYAMAZON_GET_ORDER_LOCK_STATUS');
define('ORDER_STATUS_MONITOR_LOCK_KEY',  'MODULE_PAYMENT_CHECKOUTBYAMAZON_MONITOR_ORDER_LOCK_STATUS');
// 20 minutes
define('ORDER_SYNCHRONIZATION_LOCK_TIMEOUT',  1200);
define('ORDER_STATUS_MONITOR_LOCK_TIMEOUT',  1200);

// it takes two seconds to send the http request under normal conditions
// don't set this value too low or the http request will not be established.
define('ORDER_STATUS_REQUEST_TIMEOUT',  2);

//IOPN config settings
define('SUPPORT_SIGNED_CARTS','true');

define('MERCHANT_ACCESS_KEY_PROPERTIES_NAME', 'AWSSecretKeyList');
define('MERCHANT_PROPERTIES_FILE','prop/merchant.properties');

define('DEBUG',true);
/*
 *  Please *do not* edit the following settings
 */

define('TRUE_FLAG','true');
 // 15 minute window.
define('TIMESTAMP_WINDOW',900);

define("LIB",'checkout_by_amazon/library/');

// libraries required.
ini_set('include_path','.:' .
                       LIB . ':' .
                       ini_get('include_path'));

/* Signature Algorithm used. */
define("HMAC_SHA1_ALGORITHM","sha1");

/* Schema Files */
define('EVENT_NOTIFICATION_SCHEMA_FILE','schema/iopn.xsd');
define('ORDER_SCHEMA_FILE','schema/order.xsd');

//Path setting
define("LOG_DIR",'checkout_by_amazon/log/');
define('LOG_FILE', 'checkout_by_amazon_callback.log');

if(!DEBUG){
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
}
/* Needed for Orders Admin notification page */
define('ENTRY_AMAZON_ORDERS_ID', 'Amazon Order ID:');
define('ENTRY_AMAZON_SHIPPING_CARRIER', ' Carrier: ');
define('ENTRY_AMAZON_SHIPPING_SERVICE', ' Shipping Service: ');
define('ENTRY_AMAZON_SHIPPING_TRACKING_NUMBER', ' Shipment Tracking ID: ');

/* admin notification javascript alert messages */
define('MERCHANT_CANCEL_CONFIRMATION_TEXT', 'Merchant Initiated Cancel');
define('ENTRY_AMAZON_CANCEL_CONFIRMATION_TEXT', ' Are you sure you want to Cancel this Order? ');
define('ENTRY_AMAZON_SELECT_THE_REASON_FOR_REFUND', ' Please select the reason for the Refund! ');
define('ENTRY_AMAZON_SHIPPING_SERVICE_TEXT', ' Please enter the Shipping Service! ');
define('ENTRY_AMAZON_SHIPPING_CARRIER_TEXT', ' Please select the Shipping Carrier! ');
define('ENTRY_AMAZON_SHIPPING_TRACKING_TEXT', ' Please enter the Shipment Tracking ID! ');


/* User Agent used for WaterMarking */
define('HTTP_USER_AGENT','CBA_OSCommerce_Standard/2.3.1 (Language=PHP;ReleaseDate=10_2012)');

?>