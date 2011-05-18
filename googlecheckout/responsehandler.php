<?php
/*
  Copyright (C) 2007 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* **GOOGLE CHECKOUT ** v1.4.5
 * @version $Id: responsehandler.php 6952 2007-09-27 14:58:57Z ropu $
 * Script invoked for any callback notfications from the Checkout server
 * Can be used to process new order notifications, order state changes and risk notifications
 */
 
// 1. Setup the log file 
// 2. Parse the http header to verify the source
// 3. Parse the XML message
// 4. Trasfer control to appropriate function

// Error handler function
// Used to handle errors and log them in the GC log files
//function ErrorHandler($errno, $errstr, $errfile, $errline){
//    global $Gresponse;
//    switch ($errno) {
//    case E_USER_ERROR:
//      $err = "USER ERROR: [$errno] $errstr\n"
//                . "  Fatal error on line $errline in file $errfile\n"
//                . "Process aborted\n"; 
//      $Gresponse->log->logError($err);
//      $Gresponse->SendServerErrorStatus($err);
//      break;
//
//    case E_USER_WARNING:
//      $err = "WARNING: [$errno] $errstr\n"
//                . "  Error on line $errline in file $errfile\n"
//                . "Process aborted\n"; 
//      $Gresponse->log->logError($err);
//      $Gresponse->SendServerErrorStatus($err);
//      break;
//
//    case E_USER_NOTICE:
//      $err = "NOTICE: [$errno] $errstr\n"
//                . "  Error on line $errline in file $errfile\n"
//                . "Process continues.\n";
//      $Gresponse->log->logError($err);
////      $Gresponse->SendServerErrorStatus($err);
//      break;
//
//    default:
//      $err = "Unknown error type: [$errno] $errstr\n"
//                . " Error on line $errline in file $errfile\n"
//                . "Process continues.\n";
//      $Gresponse->log->logError($err);
////      $Gresponse->SendServerErrorStatus($err);
//      break;
//    }
//
//    /* Don't execute PHP internal error handler */
//    return true;
//}
error_reporting(E_ALL);

// temporal disable of multisocket 
define('MODULE_PAYMENT_GOOGLECHECKOUT_MULTISOCKET', 'False');


define('GC_STATE_NEW', 100);
define('GC_STATE_PROCESSING', 101);
define('GC_STATE_SHIPPED', 102);
define('GC_STATE_REFUNDED', 103);
define('GC_STATE_SHIPPED_REFUNDED', 104);
define('GC_STATE_CANCELED', 105);


chdir('./..');
$curr_dir = getcwd();
define('API_CALLBACK_ERROR_LOG', $curr_dir."/googlecheckout/logs/response_error.log");
define('API_CALLBACK_MESSAGE_LOG', $curr_dir."/googlecheckout/logs/response_message.log");

require_once($curr_dir.'/googlecheckout/library/googlemerchantcalculations.php');
require_once($curr_dir.'/googlecheckout/library/googleresult.php');
require_once ($curr_dir . '/googlecheckout/library/googlerequest.php');
require_once($curr_dir.'/googlecheckout/library/googleresponse.php');

$Gresponse = new GoogleResponse();
//Setup the log files
$Gresponse->SetLogFiles(API_CALLBACK_ERROR_LOG, API_CALLBACK_MESSAGE_LOG, L_ALL);
//set_error_handler("ErrorHandler");
// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
if (get_magic_quotes_gpc()) {
  $xml_response = stripslashes($xml_response);
}
list($root, $gc_data) = $Gresponse->GetParsedXML($xml_response);
if(isset($gc_data[$root]['shopping-cart']['merchant-private-data']['session-data']['VALUE'])) {
  list ($sess_id, $sess_name) = 
      explode(";", $gc_data[$root]['shopping-cart']['merchant-private-data']['session-data']['VALUE']);
    //If session management is supported by this PHP version
  if(function_exists('session_id'))
    session_id($sess_id);
  if(function_exists('session_name'))
    session_name($sess_name);
}
   
include('includes/application_top.php');
include('includes/modules/payment/googlecheckout.php');

if(tep_session_is_registered('cart') && is_object($cart)) {
  $cart->restore_contents();
} else {
  $Gresponse->SendServerErrorStatus("Shopping cart not obtained from session.");
}	

$googlepayment = new googlecheckout();
$Gresponse->SetMerchantAuthentication($googlepayment->merchantid, 
                                      $googlepayment->merchantkey);

// Check if is CGI install, if so .htaccess is needed
if(MODULE_PAYMENT_GOOGLECHECKOUT_CGI != 'True') {
  $Gresponse->HttpAuthentication();
}

switch ($root) {
  case "request-received": {
    process_request_received_response($Gresponse);
  break;
  }
  case "error": {
    process_error_response($Gresponse);
  break;
  }
  case "diagnosis": {
    process_diagnosis_response($Gresponse);
  break;
  }
  case "checkout-redirect": {
    process_checkout_redirect($Gresponse);
  break;
  }
  case "merchant-calculation-callback" :
    {
//      if (MODULE_PAYMENT_GOOGLECHECKOUT_MULTISOCKET == 'True') {
//        include_once ($curr_dir . '/googlecheckout/multisocket.php');
//        process_merchant_calculation_callback($Gresponse, 2.7, false);
//        break;
//      }
//    }
//  case "merchant-calculation-callback-single" :
//    {
// 			set_time_limit(5); 
    process_merchant_calculation_callback_single($Gresponse);
  break;
  }

  case "new-order-notification": {
    
    /*
     * 1. check if the users email exists
     *    1.a if not, create the user, and log in
     * 2. Check if exists as a GC user
     *    2.aAdd it the the google_checkout table to match buyer_id customer_id
     * 
     * 2. add the order to the logged user
     * 
     */
//    Check if the order was already processed
      $google_order = tep_db_fetch_array(tep_db_query("select orders_id ".
                                " from " . $googlepayment->table_order . " " .
                                " where google_order_number = " . 
                                $gc_data[$root]['google-order-number']['VALUE'] ));
      if($google_order['orders_id'] != '') {
//       Order already processed, send ACK http 200 to avoid notification resend
        $Gresponse->log->logError(sprintf(GOOGLECHECKOUT_ERR_DUPLICATED_ORDER,
                                   $gc_data[$root]['google-order-number']['VALUE'],
                                   $google_order['orders_id']));
        $Gresponse->SendAck(); 
      }
     
//    Check if the email exists
      $customer_exists = tep_db_fetch_array(tep_db_query("select customers_id from " .
      TABLE_CUSTOMERS . " where customers_email_address = '" .
      gc_makeSqlString($gc_data[$root]['buyer-billing-address']['email']['VALUE']) . "'"));

//    Check if the GC buyer id exists
      $customer_info = tep_db_fetch_array(tep_db_query("select gct.customers_id from " .
          $googlepayment->table_name . " gct " .
          " inner join " .TABLE_CUSTOMERS . " tc on gct.customers_id = tc.customers_id ".
          " where gct.buyer_id = " .
          gc_makeSqlString($gc_data[$root]['buyer-id']['VALUE'])));

      $new_user = false;
//    Ignore session to avoid mix of Cart-GC sessions/emails
//    GC email is the most important one
      if ($customer_exists['customers_id'] != '') {
        $customer_id = $customer_exists['customers_id'];
        tep_session_register('customer_id');
      }
      else if($customer_info['customers_id'] != ''){
        $customer_id = $customer_info['customers_id'];
        tep_session_register('customer_id');
      }
      else {
        list ($firstname, $lastname) = 
            explode(' ', gc_makeSqlString($gc_data[$root]['buyer-billing-address']['contact-name']['VALUE']), 2);
        $sql_data_array = array (
          'customers_firstname' => $firstname,
          'customers_lastname' => $lastname,
          'customers_email_address' => $gc_data[$root]['buyer-billing-address']['email']['VALUE'],
          'customers_telephone' => $gc_data[$root]['buyer-billing-address']['phone']['VALUE'],
          'customers_fax' => $gc_data[$root]['buyer-billing-address']['fax']['VALUE'],
          'customers_default_address_id' => 0,
          'customers_password' => tep_encrypt_password(gc_makeSqlString($gc_data[$root]['buyer-id']['VALUE'])),
          'customers_newsletter' => $gc_data[$root]['buyer-marketing-preferences']['email-allowed']['VALUE']=='true'?1:0
        );
        if (ACCOUNT_DOB == 'true') {
          $sql_data_array['customers_dob'] = 'now()';
        }
        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
        $customer_id = tep_db_insert_id();
        tep_session_register('customer_id');
        tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . "
                                      (customers_info_id, customers_info_number_of_logons,
                                       customers_info_date_account_created)
                                 values ('" . (int) $customer_id . "', '0', now())");
        tep_db_query("insert into " . $googlepayment->table_name . " " .
                      " values ( " . $customer_id . ", " .
                      $gc_data[$root]['buyer-id']['VALUE'] . ")");
        $new_user = true;
      }
      //      The user exists and is logged in
      //      Check database to see if the address exist.
      $address_book = tep_db_query("select address_book_id, entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . "
                      where  customers_id = '" . $customer_id . "'
                        and entry_street_address = '" . gc_makeSqlString($gc_data[$root]['buyer-shipping-address']['address1']['VALUE']) . "'
                          and entry_suburb = '" . gc_makeSqlString($gc_data[$root]['buyer-shipping-address']['address2']['VALUE']) . "'
                          and entry_postcode = '" . gc_makeSqlString($gc_data[$root]['buyer-shipping-address']['postal-code']['VALUE']) . "'
                          and entry_city = '" . gc_makeSqlString($gc_data[$root]['buyer-shipping-address']['city']['VALUE']) . "'
                        ");
      //      If not, add the addr as default one
      if (!tep_db_num_rows($address_book)) {
        $buyer_state = $gc_data[$root]['buyer-shipping-address']['region']['VALUE'];
        $zone_answer = tep_db_fetch_array(tep_db_query("select zone_id, zone_country_id from " .
        TABLE_ZONES . " where zone_code = '" . $buyer_state . "'"));
        list ($firstname, $lastname) = 
            explode(' ', gc_makeSqlString($gc_data[$root]['buyer-shipping-address']['contact-name']['VALUE']), 2);
        $sql_data_array = array (
          'customers_id' => $customer_id,
          'entry_gender' => '',
          'entry_company' => $gc_data[$root]['buyer-shipping-address']['company-name']['VALUE'],
          'entry_firstname' => $firstname,
          'entry_lastname' => $lastname,
          'entry_street_address' => $gc_data[$root]['buyer-shipping-address']['address1']['VALUE'],
          'entry_suburb' => $gc_data[$root]['buyer-shipping-address']['address2']['VALUE'],
          'entry_postcode' => $gc_data[$root]['buyer-shipping-address']['postal-code']['VALUE'],
          'entry_city' => $gc_data[$root]['buyer-shipping-address']['city']['VALUE'],
          'entry_state' => $buyer_state,
          'entry_country_id' => $zone_answer['zone_country_id'],
          'entry_zone_id' => $zone_answer['zone_id']
        );
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        $address_id = tep_db_insert_id();
        tep_db_query("update " . TABLE_CUSTOMERS . "
                                  set customers_default_address_id = '" . (int) $address_id . "'
                                  where customers_id = '" . (int) $customer_id . "'");
        $customer_default_address_id = $address_id;
        $customer_country_id = $zone_answer['zone_country_id'];
        $customer_zone_id = $zone_answer['zone_id'];
      } else {
        $customer_default_address_id = $address_book['address_book_id'];
        $customer_country_id = $address_book['entry_country_id'];
        $customer_zone_id = $address_book['entry_zone_id'];
      }
      $customer_first_name = $gc_data[$root]['buyer-billing-address']['contact-name']['VALUE'];
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');
      tep_session_register('customer_first_name');
//  Customer exists, is logged and address book is up to date

//  Create the order 
      if (isset ($gc_data[$root]['order-adjustment']['shipping']['merchant-calculated-shipping-adjustment']['shipping-name']['VALUE'])) {
        $shipping = $gc_data[$root]['order-adjustment']['shipping']['merchant-calculated-shipping-adjustment']['shipping-name']['VALUE'];
        $ship_cost = $gc_data[$root]['order-adjustment']['shipping']['merchant-calculated-shipping-adjustment']['shipping-cost']['VALUE'];
        $methods_hash = $googlepayment->getMethods();
        list ($a, $method_name) = explode(': ', $shipping, 2);
        $shipping_name = $methods_hash[$method_name][0];//name
        $shipping_code = $methods_hash[$method_name][2];//code
      } else if (isset ($gc_data[$root]['order-adjustment']['shipping']['flat-rate-shipping-adjustment']['shipping-name']['VALUE'])) {
        $shipping = $gc_data[$root]['order-adjustment']['shipping']['flat-rate-shipping-adjustment']['shipping-name']['VALUE'];
        $ship_cost = $gc_data[$root]['order-adjustment']['shipping']['flat-rate-shipping-adjustment']['shipping-cost']['VALUE'];
        $methods_hash = $googlepayment->getMethods();
        list ($a, $method_name) = explode(': ', $shipping, 2);
        $shipping_name = $methods_hash[$method_name][0];//name
        $shipping_code = $methods_hash[$method_name][2];//code
      } else if (isset ($gc_data[$root]['order-adjustment']['shipping']['carrier-calculated-shipping-adjustment']['shipping-name']['VALUE'])) {
        $shipping = $gc_data[$root]['order-adjustment']['shipping']['carrier-calculated-shipping-adjustment']['shipping-name']['VALUE'];
        $ship_cost = $gc_data[$root]['order-adjustment']['shipping']['carrier-calculated-shipping-adjustment']['shipping-cost']['VALUE'];
        $shipping_name = $shipping;
        $shipping_code = 'GCCarrierCalculated';//code
      } else {
        $shipping = 'GC Digital Delivery';
        $ship_cost = 0;
        $shipping_name = $shipping;//name
        $shipping_code = 'FreeGCDigital';//code
      }
      $tax_amt = $gc_data[$root]['order-adjustment']['total-tax']['VALUE'];
      //      $order_total = $gc_data[$root]['order-total']['VALUE'];

      require (DIR_WS_CLASSES . 'order.php');
      $order = new order();
      // load the selected shipping module
      $payment_method = $googlepayment->title;
      if(MODULE_PAYMENT_GOOGLECHECKOUT_MODE=='https://sandbox.google.com/checkout/'){
        $payment_method .= " - SANDBOX";
      }      
//      $method_name = '';
//      if (!empty ($shipping)) {
//        require (DIR_WS_CLASSES . 'shipping.php');
//        $shipping_modules = new shipping($shipping);
//        list ($a, $method_name) = explode(': ', $shipping, 2);
//      }
      //    Set up order info
      list ($order->customer['firstname'], $order->customer['lastname']) =
          explode(' ', $gc_data[$root]['buyer-billing-address']['contact-name']['VALUE'], 2);
      $order->customer['company'] = $gc_data[$root]['buyer-billing-address']['company-name']['VALUE'];
      $order->customer['street_address'] = $gc_data[$root]['buyer-billing-address']['address1']['VALUE'];
      $order->customer['suburb'] = $gc_data[$root]['buyer-billing-address']['address2']['VALUE'];
      $order->customer['city'] = $gc_data[$root]['buyer-billing-address']['city']['VALUE'];
      $order->customer['postcode'] = $gc_data[$root]['buyer-billing-address']['postal-code']['VALUE'];
      $order->customer['state'] = $gc_data[$root]['buyer-billing-address']['region']['VALUE'];
      $order->customer['country']['title'] = $gc_data[$root]['buyer-billing-address']['country-code']['VALUE'];
      $order->customer['telephone'] = $gc_data[$root]['buyer-billing-address']['phone']['VALUE'];
      $order->customer['email_address'] = $gc_data[$root]['buyer-billing-address']['email']['VALUE'];
      $order->customer['format_id'] = 2;
      list ($order->delivery['firstname'], $order->delivery['lastname']) = 
          explode(' ', $gc_data[$root]['buyer-shipping-address']['contact-name']['VALUE'], 2);
      $order->delivery['company'] = $gc_data[$root]['buyer-shipping-address']['company-name']['VALUE'];
      $order->delivery['street_address'] = $gc_data[$root]['buyer-shipping-address']['address1']['VALUE'];
      $order->delivery['suburb'] = $gc_data[$root]['buyer-shipping-address']['address2']['VALUE'];
      $order->delivery['city'] = $gc_data[$root]['buyer-shipping-address']['city']['VALUE'];
      $order->delivery['postcode'] = $gc_data[$root]['buyer-shipping-address']['postal-code']['VALUE'];
      $order->delivery['state'] = $gc_data[$root]['buyer-shipping-address']['region']['VALUE'];
      $order->delivery['country']['title'] = $gc_data[$root]['buyer-shipping-address']['country-code']['VALUE'];
      $order->delivery['format_id'] = 2;
      list ($order->billing['firstname'], $order->billing['lastname']) = 
          explode(' ', $gc_data[$root]['buyer-billing-address']['contact-name']['VALUE'], 2);
      $order->billing['company'] = $gc_data[$root]['buyer-billing-address']['company-name']['VALUE'];
      $order->billing['street_address'] = $gc_data[$root]['buyer-billing-address']['address1']['VALUE'];
      $order->billing['suburb'] = $gc_data[$root]['buyer-billing-address']['address2']['VALUE'];
      $order->billing['city'] = $gc_data[$root]['buyer-billing-address']['city']['VALUE'];
      $order->billing['postcode'] = $gc_data[$root]['buyer-billing-address']['postal-code']['VALUE'];
      $order->billing['state'] = $gc_data[$root]['buyer-billing-address']['region']['VALUE'];
      $order->billing['country']['title'] = $gc_data[$root]['buyer-billing-address']['country-code']['VALUE'];
      $order->billing['format_id'] = 2;
      $order->info['payment_method'] = $payment_method;
      $order->info['payment_module_code'] = $googlepayment->code;
      $order->info['shipping_method'] = $shipping_name;
      $order->info['shipping_module_code'] = $shipping_code;
      $order->info['cc_type'] = '';
      $order->info['cc_owner'] = '';
      $order->info['cc_number'] = '';
      $order->info['cc_expires'] = '';
      $order->info['order_status'] = GC_STATE_NEW;
      $order->info['tax'] = $tax_amt;
      $order->info['currency'] = $gc_data[$root]['order-total']['currency'];
      $order->info['currency_value'] = 1;
//      $customers_ip_address'] = $gc_data[$root]['shopping-cart']['merchant-private-data']['ip-address']['VALUE'];
      $order->info['comments'] = GOOGLECHECKOUT_STATE_NEW_ORDER_NUM .
        $gc_data[$root]['google-order-number']['VALUE'] . "\n" .
        GOOGLECHECKOUT_STATE_NEW_ORDER_MC_USED .
        ((@$gc_data[$root]['order-adjustment']['merchant-calculation-successful']['VALUE'] == 'true')?'True':'False') .
        ($new_user ? ("\n" . GOOGLECHECKOUT_STATE_NEW_ORDER_BUYER_USER .
        $gc_data[$root]['buyer-billing-address']['email']['VALUE'] . "\n" .
        GOOGLECHECKOUT_STATE_NEW_ORDER_BUYER_PASS . $gc_data[$root]['buyer-id']['VALUE']):'');

      $coupons = gc_get_arr_result(@$gc_data[$root]['order-adjustment']['merchant-codes']['coupon-adjustment']);
//      $gift_cert = get_arr_result(@$gc_data[$root]['order-adjustment']['merchant-codes']['gift-certificate-adjustment']);
      $items = gc_get_arr_result($gc_data[$root]['shopping-cart']['items']['item']);

      // Get Coustoms OT
      $ot_customs_total = 0;
      $ot_customs = array();
      $order->products = array();
      foreach ($items as $item) {
        if (isset ($item['merchant-private-item-data']['item']['VALUE'])) {
          $order->products[] = unserialize(base64_decode($item['merchant-private-item-data']['item']['VALUE']));
        } else
          if ($item['merchant-private-item-data']['order_total']['VALUE']) {
            $ot = unserialize(base64_decode($item['merchant-private-item-data']['order_total']['VALUE']));
            $ot_customs[] = $ot;
            $ot_value = $ot['value'] * (strrpos($ot['text'], '-') === false ? 1 : -1);
            $ot_customs_total += $currencies->get_value($gc_data[$root]['order-total']['currency']) * $ot_value;
          } else {
            // For Invoices!
            // Happy BDay ropu, 07/03
            $order->products[] = array (
              'qty' => $item['quantity']['VALUE'],
              'name' => $item['item-name']['VALUE'],
              'model' => $item['item-description']['VALUE'],
              'tax' => 0,
              'tax_description' => @$item['tax-table-selector']['VALUE'],
              'price' => $item['unit-price']['VALUE'],
              'final_price' => $item['unit-price']['VALUE'],
              'onetime_charges' => 0,
              'weight' => 0,
              'products_priced_by_attribute' => 0,
              'product_is_free' => 0,
              'products_discount_type' => 0,
              'products_discount_type_from' => 0,
              'id' => @$item['merchant-item-id']['VALUE']
            );
          }
      }
      $cart = new shoppingCart();
      $prod_attr = gc_get_prattr($order->products);
      
      foreach($prod_attr as $product_id => $item_data){
    //$products_id, $qty = '1', $attributes = '
        $cart->add_cart($product_id, $item_data['qty'] , $item_data['attr']);
      }
      // Update values so that order_total modules get the correct values
      $order->info['total'] = $gc_data[$root]['order-total']['VALUE'];
      $order->info['subtotal'] = $gc_data[$root]['order-total']['VALUE'] - 
                                ($ship_cost + $tax_amt) + 
                                @$coupons[0]['applied-amount']['VALUE'] - 
                                $ot_customs_total;
      $order->info['coupon_code'] = @$coupons[0]['code']['VALUE'];
      $order->info['shipping_method'] = $shipping;
      $order->info['shipping_cost'] = $ship_cost;
      $order->info['tax_groups']['tax'] = $tax_amt;
      $order->info['currency'] = $gc_data[$root]['order-total']['currency'];
      $order->info['currency_value'] = 1;

      require (DIR_WS_CLASSES . 'order_total.php');
      $order_total_modules = new order_total();
      // Disable OT sent as items in the GC cart
      foreach ($order_total_modules->modules as $ot_code => $order_total) {
        if (!in_array(substr($order_total, 0, strrpos($order_total, '.')), $googlepayment->ot_ignore)) {
          unset ($order_total_modules->modules[$ot_code]);
        }
      }
      $order_totals = $order_total_modules->process();
      //    Not necessary, OT already disabled 
      //      foreach($order_totals as $ot_code => $order_total){
      //        if(!in_array($order_total['code'], $googlepayment->ot_ignore)){
      //          unset($order_totals[$ot_code]);
      //        }
      //      }

      //    Merge all OT
      $order_totals = array_merge($order_totals, $ot_customs);
      if (isset ($gc_data[$root]['order-adjustment']['merchant-codes']['coupon-adjustment'])) {
        $order_totals[] = array (
          'code' => 'ot_coupon',
          'title' => "<b>" . MODULE_ORDER_TOTAL_COUPON_TITLE .
          " " . @$coupons[0]['code']['VALUE'] . ":</b>",
          'text' => $currencies->format(@$coupons[0]['applied-amount']['VALUE']*-1, 
                        false,@$coupons[0]['applied-amount']['currency'])
          ,
          'value' => @$coupons[0]['applied-amount']['VALUE'],
          'sort_order' => 280
        );
      }

      function OT_cmp($a, $b) {
        if ($a['sort_order'] == $b['sort_order'])
          return 0;
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
      }
      usort($order_totals, "OT_cmp");

      $sql_data_array = array('customers_id' => $customer_id,
                              'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                              'customers_company' => $order->customer['company'],
                              'customers_street_address' => $order->customer['street_address'],
                              'customers_suburb' => $order->customer['suburb'],
                              'customers_city' => $order->customer['city'],
                              'customers_postcode' => $order->customer['postcode'], 
                              'customers_state' => $order->customer['state'], 
                              'customers_country' => $order->customer['country']['title'], 
                              'customers_telephone' => $order->customer['telephone'], 
                              'customers_email_address' => $order->customer['email_address'],
                              'customers_address_format_id' => $order->customer['format_id'], 
                              'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'], 
                              'delivery_company' => $order->delivery['company'],
                              'delivery_street_address' => $order->delivery['street_address'], 
                              'delivery_suburb' => $order->delivery['suburb'], 
                              'delivery_city' => $order->delivery['city'], 
                              'delivery_postcode' => $order->delivery['postcode'], 
                              'delivery_state' => $order->delivery['state'], 
                              'delivery_country' => $order->delivery['country']['title'], 
                              'delivery_address_format_id' => $order->delivery['format_id'], 
                              'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
                              'billing_company' => $order->billing['company'],
                              'billing_street_address' => $order->billing['street_address'], 
                              'billing_suburb' => $order->billing['suburb'], 
                              'billing_city' => $order->billing['city'], 
                              'billing_postcode' => $order->billing['postcode'], 
                              'billing_state' => $order->billing['state'], 
                              'billing_country' => $order->billing['country']['title'], 
                              'billing_address_format_id' => $order->billing['format_id'], 
                              'payment_method' => $order->info['payment_method'], 
                              'cc_type' => $order->info['cc_type'], 
                              'cc_owner' => $order->info['cc_owner'], 
                              'cc_number' => $order->info['cc_number'], 
                              'cc_expires' => $order->info['cc_expires'], 
                              'date_purchased' => 'now()', 
                              'orders_status' => $order->info['order_status'], 
                              'currency' => $order->info['currency'], 
                              'currency_value' => $order->info['currency_value']);
      tep_db_perform(TABLE_ORDERS, $sql_data_array);
      $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
        $sql_data_array = array('orders_id' => $insert_id,
                                'title' => $order_totals[$i]['title'],
                                'text' => $order_totals[$i]['text'],
                                'value' => $order_totals[$i]['value'], 
                                'class' => $order_totals[$i]['code'], 
                                'sort_order' => $order_totals[$i]['sort_order']);
        tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
      }
    
      $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
      $sql_data_array = array('orders_id' => $insert_id, 
                              'orders_status_id' => $order->info['order_status'], 
                              'date_added' => 'now()', 
                              'customer_notified' => $customer_notification,
                              'comments' => $order->info['comments']);
      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    
    // initialized for the email confirmation
      $products_ordered = '';
      $subtotal = 0;
      $total_tax = 0;
      $total_weight = 0;
      $total_products_price = 0;
      $products_tax = 0;
      $total_cost = 0;
      
    
      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    // Stock Update - Joao Correia
        if (STOCK_LIMITED == 'true') {
          if (DOWNLOAD_ENABLED == 'true') {
            $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                                FROM " . TABLE_PRODUCTS . " p
                                LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                 ON p.products_id=pa.products_id
                                LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                 ON pa.products_attributes_id=pad.products_attributes_id
                                WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
    // Will work with only one option for downloadable products
    // otherwise, we have to build the query dynamically with a loop
            $products_attributes = @$order->products[$i]['attributes'];
            if (is_array($products_attributes)) {
              $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
            }
            $stock_query = tep_db_query($stock_query_raw);
          } else {
            $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
          if (tep_db_num_rows($stock_query) > 0) {
            $stock_values = tep_db_fetch_array($stock_query);
    // do not decrement quantities if products_attributes_filename exists
            if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
              $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
            } else {
              $stock_left = $stock_values['products_quantity'];
            }
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
              tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            }
          }
        }
    
    // Update products_ordered (for bestsellers list)
        tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
    
        $sql_data_array = array('orders_id' => $insert_id, 
                                'products_id' => tep_get_prid($order->products[$i]['id']), 
                                'products_model' => $order->products[$i]['model'], 
                                'products_name' => $order->products[$i]['name'], 
                                'products_price' => $order->products[$i]['price'], 
                                'final_price' => $order->products[$i]['final_price'], 
                                'products_tax' => $order->products[$i]['tax'], 
                                'products_quantity' => $order->products[$i]['qty']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $order_products_id = tep_db_insert_id();
    
    //------insert customer choosen option to order--------
        $attributes_exist = '0';
        $products_ordered_attributes = '';
        if (isset($order->products[$i]['attributes'])) {
          $attributes_exist = '1';
          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
            if (DOWNLOAD_ENABLED == 'true') {
              $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                                   from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                                   left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                    on pa.products_attributes_id=pad.products_attributes_id
                                   where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                    and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                    and pa.options_id = popt.products_options_id 
                                    and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                    and pa.options_values_id = poval.products_options_values_id 
                                    and popt.language_id = '" . $languages_id . "' 
                                    and poval.language_id = '" . $languages_id . "'";
              $attributes = tep_db_query($attributes_query);
            } else {
              $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            }
            $attributes_values = tep_db_fetch_array($attributes);
    
            $sql_data_array = array('orders_id' => $insert_id, 
                                    'orders_products_id' => $order_products_id, 
                                    'products_options' => $attributes_values['products_options_name'],
                                    'products_options_values' => $attributes_values['products_options_values_name'], 
                                    'options_values_price' => $attributes_values['options_values_price'], 
                                    'price_prefix' => $attributes_values['price_prefix']);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
    
            if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
              $sql_data_array = array('orders_id' => $insert_id, 
                                      'orders_products_id' => $order_products_id, 
                                      'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                      'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                      'download_count' => $attributes_values['products_attributes_maxcount']);
              tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
            }
            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
          }
        }
    //------insert customer choosen option eof ----
        $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
        $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
        $total_cost += $total_products_price;
    
        $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
      }







// FOR COUPON SUPPORT
//      $insert_id = $order->create($order_totals, 2);
////      $order_total_modules = new order_total();
//      // store the product info to the order
//      $order->create_add_products($insert_id);
////      $order_number_created'] = $insert_id;
//      //      Add coupon to redeem track
//      if (isset ($gc_data[$root]['order-adjustment']['merchant-codes']['coupon-adjustment'])) {
//        $sql = "select coupon_id
//                                from " . TABLE_COUPONS . "
//                                where coupon_code= :couponCodeEntered
//                                and coupon_active='Y'";
//        $sql = $db->bindVars($sql, ':couponCodeEntered', $coupons[0]['code']['VALUE'], 'string');
//
//        $coupon_result = tep_db_query($sql);
//        $cc_id = $coupon_result['coupon_id'];
//
//        tep_db_query("insert into " . TABLE_COUPON_REDEEM_TRACK . "
//                                    (coupon_id, redeem_date, redeem_ip, customer_id, order_id)
//                                    values ('" . (int) $cc_id . "', now(), '" .
//        $gc_data[$root]['shopping-cart']['merchant-private-data']['ip-address']['VALUE'] .
//        "', '" . (int) $customer_id . "', '" . (int) $insert_id . "')");
//        $cc_id = "";
//      }

      //Add the order details to the table
      // This table could be modified to hold the merchant id and key if required
      // so that different mids and mkeys can be used for different orders
      tep_db_query("insert into " . $googlepayment->table_order . " values (" . $insert_id . ", " .
      gc_makeSqlString($gc_data[$root]['google-order-number']['VALUE']) . ", " .
      gc_makeSqlFloat($gc_data[$root]['order-total']['VALUE']) . ")");

      $cart->reset(TRUE);
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');
      $Gresponse->SendAck();
    break;
  }
  case "order-state-change-notification": {
  process_order_state_change_notification($Gresponse, $googlepayment);
  break;
  }
  case "charge-amount-notification": {
  process_charge_amount_notification($Gresponse, $googlepayment);
  break;
  }
  case "chargeback-amount-notification": {
  process_chargeback_amount_notification($Gresponse);
  break;
  }
  case "refund-amount-notification": {
  process_refund_amount_notification($Gresponse, $googlepayment);
  break;
  }
  case "risk-information-notification": {
  process_risk_information_notification($Gresponse, $googlepayment);
  break;
  }
  default: {
    $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
    break;
  }
}
exit(0);

function process_request_received_response($Gresponse) {
}
function process_error_response($Gresponse) {
}
function process_diagnosis_response($Gresponse) {
}
function process_checkout_redirect($Gresponse) {
}

function process_merchant_calculation_callback_single($Gresponse) {
  global $googlepayment, $order, $cart, $total_weight, $total_count;
	list($root, $gc_data) = $Gresponse->GetParsedXML();
  $currencies = new currencies();

	// Get a hash array with the description of each shipping method 
	$methods_hash = $googlepayment->getMethods();

	require(DIR_WS_CLASSES .'order.php');
	$order = new order;

  $items = gc_get_arr_result($gc_data[$root]['shopping-cart']['items']['item']);

  // Get Coustoms OT
  $ot_customs_total = 0;
  $ot_customs = array();
  $order->products = array();
  foreach ($items as $item) {
    if (isset ($item['merchant-private-item-data']['item']['VALUE'])) {
      $order->products[] = unserialize(base64_decode($item['merchant-private-item-data']['item']['VALUE']));
    } else
      if ($item['merchant-private-item-data']['order_total']['VALUE']) {
        $ot = unserialize(base64_decode($item['merchant-private-item-data']['order_total']['VALUE']));
        $ot_customs[] = $ot;
        $ot_value = $ot['value'] * (strrpos($ot['text'], '-') === false ? 1 : -1);
        $ot_customs_total += $currencies->get_value($gc_data[$root]['order-total']['currency']) * $ot_value;
    } else {
      // For Invoices!
      // Happy BDay ropu, 07/03
      $order->products[] = array (
        'qty' => $item['quantity']['VALUE'],
        'name' => $item['item-name']['VALUE'],
        'model' => $item['item-description']['VALUE'],
        'tax' => 0,
        'tax_description' => @$item['tax-table-selector']['VALUE'],
        'price' => $item['unit-price']['VALUE'],
        'final_price' => $item['unit-price']['VALUE'],
        'onetime_charges' => 0,
        'weight' => 0,
        'products_priced_by_attribute' => 0,
        'product_is_free' => 0,
        'products_discount_type' => 0,
        'products_discount_type_from' => 0,
        'id' => @$item['merchant-item-id']['VALUE']
      );
    }
  }
  $ex_cart = $cart;
  $cart = new shoppingCart();
  
  $prod_attr = gc_get_prattr($order->products);
  
  foreach($prod_attr as $product_id => $item_data){
//$products_id, $qty = '1', $attributes = '
    $cart->add_cart($product_id, $item_data['qty'] , $item_data['attr']);
  }

  // Register a random ID in the session to check throughout the checkout procedure
  // against alterations in the shopping cart contents.
//  if (!tep_session_is_registered('cartID')) {
//    tep_session_register('cartID');
//  }
//  $cartID = $cart->cartID;

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

  // Create the results and send it
	$merchant_calc = new GoogleMerchantCalculations(DEFAULT_CURRENCY);

  // Loop through the list of address ids from the callback.
  $addresses = gc_get_arr_result($gc_data[$root]['calculate']['addresses']['anonymous-address']);
	// Get all the enabled shipping methods.
  require(DIR_WS_CLASSES .'shipping.php');
	
  // Required for some shipping methods (ie. USPS).
  require_once('includes/classes/http_client.php');
  foreach($addresses as $curr_address) {
    // Set up the order address.
    $curr_id = $curr_address['id'];
    $country = $curr_address['country-code']['VALUE'];
    $city = $curr_address['city']['VALUE'];
    $region = $curr_address['region']['VALUE'];
    $postal_code = $curr_address['postal-code']['VALUE'];

    $row = tep_db_fetch_array(tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_iso_code_2 = '". gc_makeSqlString($country) ."'"));
    $order->delivery['country'] = array('id' => $row['countries_id'], 
                                        'title' => $row['countries_name'], 
                                        'iso_code_2' => $country, 
                                        'iso_code_3' => $row['countries_iso_code_3']);
    $order->delivery['country_id'] = $row['countries_id'];
    $order->delivery['format_id'] = $row['address_format_id'];
	
    $row = tep_db_fetch_array(tep_db_query("select * from ". TABLE_ZONES ." where zone_code = '" . gc_makeSqlString($region) ."'"));
    $order->delivery['zone_id'] = $row['zone_id'];
    $order->delivery['state'] = $row['zone_name'];

    $order->delivery['city'] = $city;
    $order->delivery['postcode'] = $postal_code;
//    print_r($order);
		$shipping_modules = new shipping();
	
    // Loop through each shipping method if merchant-calculated shipping
    // support is to be provided
    //print_r($gc_data[$root]['calculate']['shipping']['method']);
	 if(isset($gc_data[$root]['calculate']['shipping'])) {
      $shipping = gc_get_arr_result($gc_data[$root]['calculate']['shipping']['method']);


			if(MODULE_PAYMENT_GOOGLECHECKOUT_MULTISOCKET == 'True') {
// Single
				// i get all the enabled shipping methods  
       	$name = $shipping[0]['name'];
//            Compute the price for this shipping method and address id
        list($a, $method_name) = explode(': ',$name);
				if((($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) && ($methods_hash[$method_name][1] == 'domestic_types'))
						||
					(($order->delivery['country']['id'] != SHIPPING_ORIGIN_COUNTRY) && ($methods_hash[$method_name][1] == 'international_types'))){
//								reset the shipping class to set the new address
							if (class_exists($methods_hash[$method_name][2])) {			        	
		        		$GLOBALS[$methods_hash[$method_name][2]] = new $methods_hash[$method_name][2];
							}
				}
      	$quotes =  $shipping_modules->quote('', $methods_hash[$method_name][2]);
			}
			else {
// Standard
        foreach($shipping as $curr_ship) {
         	$name = $curr_ship['name'];
//            Compute the price for this shipping method and address id
	        list($a, $method_name) = explode(': ',$name);
					if((($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) 
              && ($methods_hash[$method_name][1] == 'domestic_types'))
							||
						(($order->delivery['country']['id'] != SHIPPING_ORIGIN_COUNTRY) 
              && ($methods_hash[$method_name][1] == 'international_types'))){
//								reset the shipping class to set the new address
								if (class_exists($methods_hash[$method_name][2])) {			        	
			        		$GLOBALS[$methods_hash[$method_name][2]] = new $methods_hash[$method_name][2];
								}
					}
        }
				$quotes =  $shipping_modules->quote();
			}
			reset($shipping);
      foreach($shipping as $curr_ship) {
       	$name = $curr_ship['name'];
//            Compute the price for this shipping method and address id
        list($a, $method_name) = explode(': ',$name);
				unset($quote_povider);
				unset($quote_method);
				if((($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) 
            && ($methods_hash[$method_name][1] == 'domestic_types'))
						||
					(($order->delivery['country']['id'] != SHIPPING_ORIGIN_COUNTRY) 
            && ($methods_hash[$method_name][1] == 'international_types'))) {
					foreach($quotes as $key_provider => $shipping_provider) {
						// privider name (class)
						if($shipping_provider['id'] == $methods_hash[$method_name][2]) {
							// method name			
							$quote_povider = $key_provider;
							if(is_array($shipping_provider['methods']))
							foreach($shipping_provider['methods'] as $key_method => $shipping_method) {
								if($shipping_method['id'] == $methods_hash[$method_name][0]){
									$quote_method = $key_method;
									break;
								}										
							}
							break;
						}
					}
				}
        //if there is a problem with the method, i mark it as non-shippable
        if( isset($quotes[$quote_povider]['error']) ||
            !isset($quotes[$quote_povider]['methods'][$quote_method]['cost'])) {
        	$price = "9999.09";
        	$shippable = "false";
        } else {
        	$price = $quotes[$quote_povider]['methods'][$quote_method]['cost'];
        	$shippable = "true";
        }
        $merchant_result = new GoogleResult($curr_id);
        $merchant_result->SetShippingDetails($name, $currencies->get_value(DEFAULT_CURRENCY) * $price, $shippable);

        if($gc_data[$root]['calculate']['tax']['VALUE'] == "true") {
          //Compute tax for this address id and shipping type
          $amount = 15; // Modify this to the actual tax value
          $merchant_result->SetTaxDetails($currencies->get_value(DEFAULT_CURRENCY) * $amount);
        }
        
        if(isset($gc_data[$root]['calculate']['merchant-code-strings']['merchant-code-string'])){
          $codes = gc_get_arr_result($gc_data[$root]['calculate']['merchant-code-strings']['merchant-code-string']);
          foreach($codes as $curr_code) {
            //Update this data as required to set whether the coupon is valid, the code and the amount
            $coupons = new GoogleCoupons("true", $curr_code['code'], $currencies->get_value(DEFAULT_CURRENCY) * 5, "test2");
            $merchant_result->AddCoupons($coupons);
          }
        }
        $merchant_calc->AddResult($merchant_result);
      }
    } else {
        $merchant_result = new GoogleResult($curr_id);
        if($gc_data[$root]['calculate']['tax']['VALUE'] == "true") {
          //Compute tax for this address id and shipping type
          $amount = 15; // Modify this to the actual tax value
          $merchant_result->SetTaxDetails($currencies->get_value(DEFAULT_CURRENCY) * $amount);
        }
//        calculate_coupons($Gresponse, $merchant_result);
        $merchant_calc->AddResult($merchant_result);
    }
   }
   $cart = $ex_cart;
   
   $Gresponse->ProcessMerchantCalculations($merchant_calc);
}

function process_order_state_change_notification($Gresponse, $googlepayment) {
  list($root, $gc_data) = $Gresponse->GetParsedXML();
  $new_financial_state = $gc_data[$root]['new-financial-order-state']['VALUE'];
  $new_fulfillment_order = $gc_data[$root]['new-fulfillment-order-state']['VALUE'];

  $previous_financial_state = $gc_data[$root]['previous-financial-order-state']['VALUE'];
  $previous_fulfillment_order = $gc_data[$root]['previous-fulfillment-order-state']['VALUE'];

  $google_order_number = $gc_data[$root]['google-order-number']['VALUE'];
    
  $google_order = tep_db_fetch_array(tep_db_query("select orders_id from ". 
                  $googlepayment->table_order ." where google_order_number = '". 
                  gc_makeSqlString($google_order_number) ."'"));
		

  $update = false;
  if ($previous_financial_state != $new_financial_state) {
    switch($new_financial_state) {
      case 'REVIEWING':
        {
        break;
        }
      case 'CHARGEABLE':
        {
				$update = true;
          $orders_status_id = GC_STATE_NEW;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
          GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_financial_state."\n".
          GOOGLECHECKOUT_STATE_STRING_ORDER_READY_CHARGE;
				$customer_notified = 0;
        break;
        }
      case 'CHARGING':
        {
        break;
        }
      case 'CHARGED':
        {
				$update = true;
          $orders_status_id = GC_STATE_PROCESSING;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
                    GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_financial_state ;
				$customer_notified = 0;
        break;
        }
      

      case 'PAYMENT-DECLINED':
        {
				$update = true;
          $orders_status_id = GC_STATE_NEW;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
                   GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_financial_state .
                   GOOGLECHECKOUT_STATE_STRING_PAYMENT_DECLINED; 
				$customer_notified = 1;
        break;
        }
      case 'CANCELLED':
        {
				$update = true;
				$orders_status_id = GC_STATE_CANCELED;
				$customer_notified = 1;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
                    GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_financial_state ."\n".
          GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED;
        break;
        }
      case 'CANCELLED_BY_GOOGLE':
        {
				$update = true;
          $orders_status_id = GC_STATE_CANCELED;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
                    GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_financial_state ."\n".
          GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED_BY_GOOG;
				$customer_notified = 1;
        break;
        }
      default:
        break;
    }
  }  

  if($update) {
    $sql_data_array = array('orders_id' => $google_order['orders_id'],
                            'orders_status_id' => $orders_status_id,
                            'date_added' => 'now()',
                            'customer_notified' => $customer_notified,
	                          'comments' => $comments);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    tep_db_query("update ". TABLE_ORDERS . " set orders_status = '".
                  $orders_status_id."' where orders_id = '". 
                  gc_makeSqlInteger($google_order['orders_id']) ."'");
  }
    
  $update = false;   

  if($previous_fulfillment_order != $new_fulfillment_order) {
    switch($new_fulfillment_order) {
      case 'NEW':
        {
        break;
        }
      case 'PROCESSING' :
        {
//          $Gresponse->SendAck(false);
          $Grequest = new GoogleRequest($googlepayment->merchantid, 
                                        $googlepayment->merchantkey, 
                                        MODULE_PAYMENT_GOOGLECHECKOUT_MODE==
                                          'https://sandbox.google.com/checkout/'
                                          ?"sandbox":"production",
                                        DEFAULT_CURRENCY);
          $Grequest->SetLogFiles(API_CALLBACK_ERROR_LOG, API_CALLBACK_MESSAGE_LOG);
          $google_answer = tep_db_fetch_array(tep_db_query("SELECT go.google_order_number, go.order_amount, o.customers_email_address, gc.buyer_id, o.customers_id
                                          FROM " . $googlepayment->table_order . " go 
                                          inner join " . TABLE_ORDERS . " o on go.orders_id = o.orders_id
                                          inner join " . $googlepayment->table_name . " gc on gc.customers_id = o.customers_id
                                          WHERE go.orders_id = '" . (int)$google_order['orders_id'] ."'
                                          group by o.customers_id order by o.orders_id desc"));

          $first_order = tep_db_fetch_array(tep_db_query("SELECT customers_id, count(*) cant_orders
                                        FROM  " . TABLE_ORDERS . " 
                                        WHERE customers_id = '".$google_answer['customers_id']."'
                                        group by customers_id"));
    // Send buyers email and password if new user and first buy with GC in the site
          if($first_order['cant_orders'] == 1) {
            list($status,) = $Grequest->sendBuyerMessage($google_answer['google_order_number'],
                                      sprintf(GOOGLECHECKOUT_NEW_CREDENTIALS_MESSAGE,
                                              STORE_NAME,
                                              $google_answer['customers_email_address'], 
                                              $google_answer['buyer_id']), "true", 2);

            $comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE'] . "\n" .
            GOOGLECHECKOUT_STATE_STRING_NEW_STATE . $new_fulfillment_order. "\n";
    
            if($status != 200) {
              $comments .= "\n" . GOOGLECHECKOUT_ERR_SEND_NEW_USER_CREDENTIALS . "\n";
              $customer_notified = '0';
            }
            else {
              $comments .= GOOGLECHECKOUT_SUCCESS_SEND_NEW_USER_CREDENTIALS . "\n";
              $customer_notified = '1';
            }
            $comments .=  "Messsage:\n" . sprintf(GOOGLECHECKOUT_NEW_CREDENTIALS_MESSAGE,
                                    STORE_NAME,
                                    $google_answer['customers_email_address'], 
                                    $google_answer['buyer_id']);
            
            $update = true;
            $orders_status_id = GC_STATE_PROCESSING;
          }
          
          // Tell google witch is the Zencart's internal order Number        
          $Grequest->SendMerchantOrderNumber($google_answer['google_order_number'],
                                             $google_order['orders_id'],
                                             2);
        break;
        }
      case 'DELIVERED' :
        {
          $check_status = tep_db_fetch_array(tep_db_query("select orders_status from " . TABLE_ORDERS . "
                      where orders_id = '" . $google_order['orders_id'] . "'"));

          switch($check_status['orders_status']){
            case GC_STATE_REFUNDED:
              $orders_status_id = GC_STATE_SHIPPED_REFUNDED;
            break;
            case GC_STATE_PROCESSING:
            default;
              $orders_status_id = GC_STATE_SHIPPED;
            break;
          }

				$update = true;
				$comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE']. "\n".
                    GOOGLECHECKOUT_STATE_STRING_NEW_STATE. $new_fulfillment_order ."\n".
                    GOOGLECHECKOUT_STATE_STRING_ORDER_DELIVERED."\n"; 
				$customer_notified = 1;
        break;
        }
      case 'WILL_NOT_DELIVER':
        {
          $update = false;
          $orders_status_id = GC_STATE_CANCELED;
          $customer_notified = 1;
          $comments = GOOGLECHECKOUT_STATE_STRING_TIME . $gc_data[$root]['timestamp']['VALUE'] . "\n" .
          GOOGLECHECKOUT_STATE_STRING_NEW_STATE . $new_fulfillment_order . "\n" .
          GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED;
        break;
        }
      default:
         break;
    }
  }

  if($update) {
    $sql_data_array = array('orders_id' => $google_order['orders_id'],
                            'orders_status_id' => $orders_status_id,
                            'date_added' => 'now()',
	                          'customer_notified' => $customer_notified,
	                          'comments' => $comments);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    tep_db_query("update ". TABLE_ORDERS ." set orders_status = '". 
                    $orders_status_id ."' WHERE orders_id = '". 
                    gc_makeSqlInteger($google_order['orders_id']) ."'");
  }

  $Gresponse->SendAck();	  
}

// Update the order status upon completion of payment.
function process_charge_amount_notification($Gresponse, $googlepayment) {
  list($root, $gc_data) = $Gresponse->GetParsedXML();
  $google_order_number = $gc_data[$root]['google-order-number']['VALUE'];
  $google_order = tep_db_fetch_array(tep_db_query("select orders_id from ". 
                  $googlepayment->table_order ." where google_order_number = '". 
                  gc_makeSqlString($google_order_number) ."'"));

  $sql_data_array = array('orders_id' => $google_order['orders_id'],
                          'orders_status_id' => GC_STATE_PROCESSING,
                          'date_added' => 'now()',
                          'customer_notified' => 0,
                          'comments' => GOOGLECHECKOUT_STATE_STRING_LATEST_CHARGE .
                          $gc_data[$root]['latest-charge-amount']['currency'].
                          ' ' .$gc_data[$root]['latest-charge-amount']['VALUE']."\n". 
                          GOOGLECHECKOUT_STATE_STRING_TOTAL_CHARGE .
                          $gc_data[$root]['latest-charge-amount']['currency'].' ' .
                          $gc_data[$root]['total-charge-amount']['VALUE']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  // Adjust the orders_status value here if you need it to move to a different status upon payment.
  tep_db_query("update ". TABLE_ORDERS ." set orders_status = " . GC_STATE_PROCESSING . " where orders_id = '".
                                 gc_makeSqlInteger($google_order['orders_id']) ."'");

  $Gresponse->SendAck();
}

function process_chargeback_amount_notification($Gresponse) {
    $Gresponse->SendAck(); 
}
function process_refund_amount_notification($Gresponse, $googlepayment) {
  global $currencies;
  list ($root, $gc_data) = $Gresponse->GetParsedXML();
  $google_order_number = $gc_data[$root]['google-order-number']['VALUE'];
  $google_order = tep_db_fetch_array(tep_db_query("SELECT orders_id from " .
  "" . $googlepayment->table_order . " where google_order_number = " .
  "'" . gc_makeSqlString($google_order_number) . "'"));

  //   fwrite($message_log,sprintf("\n%s\n", $google_order['orders_id']));
  $check_status = tep_db_fetch_array(tep_db_query("select orders_status from " . TABLE_ORDERS . "
                          where orders_id = '" . $google_order['orders_id'] . "'"));

  switch($check_status['orders_status']){
    case GC_STATE_PROCESSING:
    case GC_STATE_REFUNDED:
      $orders_status_id = GC_STATE_REFUNDED;
    break;
    case GC_STATE_SHIPPED:
    case GC_STATE_SHIPPED_REFUNDED:
    default;
      $orders_status_id = GC_STATE_SHIPPED_REFUNDED;
    break;
  }

  $sql_data_array = array (
    'orders_id' => $google_order['orders_id'],
    'orders_status_id' => GC_STATE_PROCESSING,
    'date_added' => 'now()',
    'customer_notified' => 1,
    'comments' => GOOGLECHECKOUT_STATE_STRING_TIME .
    $gc_data[$root]['timestamp']['VALUE'] . "\n" .
    GOOGLECHECKOUT_STATE_STRING_LATEST_REFUND .
      $currencies->format($gc_data[$root]['latest-refund-amount']['VALUE'], 
                 false, $gc_data[$root]['latest-refund-amount']['currency']). "\n".
    GOOGLECHECKOUT_STATE_STRING_TOTAL_REFUND .
      $currencies->format($gc_data[$root]['total-refund-amount']['VALUE'], 
                 false, $gc_data[$root]['total-refund-amount']['currency'])
  );
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

  $sql_data_array = array (
    'orders_id' => $google_order['orders_id'],
    'title' => GOOGLECHECKOUT_STATE_STRING_GOOGLE_REFUND,
    'text' => '<font color="#800000">' .
      $currencies->format($gc_data[$root]['latest-refund-amount']['VALUE'] * -1, 
                 false, $gc_data[$root]['latest-refund-amount']['currency']). "\n".
    '</font>',
    'value' => $gc_data[$root]['latest-refund-amount']['VALUE'],
    'class' => 'ot_goog_refund',
    'sort_order' => 1001
  );

  tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

  $total = tep_db_fetch_array(tep_db_query("SELECT orders_total_id, text, value from " .
  "" . TABLE_ORDERS_TOTAL . " where orders_id = " .
  "'" . $google_order['orders_id'] . "' AND class = 'ot_total'"));

  $net_rev = tep_db_fetch_array(tep_db_query("SELECT orders_total_id, text, value from " .
  "" . TABLE_ORDERS_TOTAL . " where orders_id = " .
  "'" . $google_order['orders_id'] . "' AND class = 'ot_goog_net_rev'"));
  $sql_data_array = array (
    'orders_id' => $google_order['orders_id'],
    'title' => '<b>' . GOOGLECHECKOUT_STATE_STRING_NET_REVENUE . '</b>',
    'text' => '<b>' .
      $currencies->format(($total['value'] - 
                      ((double) $gc_data[$root]['total-refund-amount']['VALUE'])), 
                 false, $gc_data[$root]['total-refund-amount']['currency']). 
    '</b>', 'value' => ($total['value'] - 
      ((double) $gc_data[$root]['total-refund-amount']['VALUE'])), 
    'class' => 'ot_goog_net_rev',
    'sort_order' => 1010);

  if ($net_rev['orders_total_id'] == '') {
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  } else {
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array, 'update', "orders_total_id = '" .
    $net_rev['orders_total_id'] . "'");
  }

    $Gresponse->SendAck(); 
}

// Set an order back to pending if there's a problem with payment.
function process_risk_information_notification($Gresponse, $googlepayment) {
  list($root, $gc_data) = $Gresponse->GetParsedXML();
  $google_order_number = $gc_data[$root]['google-order-number']['VALUE'];
  $google_order = tep_db_fetch_array(tep_db_query("select orders_id from ". 
                    $googlepayment->table_order ." where google_order_number = '". 
                    gc_makeSqlString($google_order_number) ."'"));



  $sql_data_array = array('orders_id' => $google_order['orders_id'],
                          'orders_status_id' => GC_STATE_NEW,
                          'date_added' => 'now()',
                          'customer_notified' => 0,
                           'comments' => GOOGLECHECKOUT_STATE_STRING_RISK_INFO ."\n" .
        GOOGLECHECKOUT_STATE_STRING_RISK_ELEGIBLE.
        $gc_data[$root]['risk-information']['eligible-for-protection']['VALUE']."\n" .
        GOOGLECHECKOUT_STATE_STRING_RISK_AVS.
        $gc_data[$root]['risk-information']['avs-response']['VALUE']."\n" .
        GOOGLECHECKOUT_STATE_STRING_RISK_CVN.
        $gc_data[$root]['risk-information']['cvn-response']['VALUE']."\n" .
        GOOGLECHECKOUT_STATE_STRING_RISK_CC_NUM.
        $gc_data[$root]['risk-information']['partial-cc-number']['VALUE']."\n" .
        GOOGLECHECKOUT_STATE_STRING_RISK_ACC_AGE.
        $gc_data[$root]['risk-information']['buyer-account-age']['VALUE']."\n" 
                                          );    
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  tep_db_query("update ". TABLE_ORDERS ." set orders_status = '". GC_STATE_NEW .
                "' WHERE orders_id = '".
                      gc_makeSqlInteger($google_order['orders_id'])."'");

  $Gresponse->SendAck();
}
  
//Functions to prevent SQL injection attacks
function gc_makeSqlString($str) {
  return tep_db_input($str);
//  return addcslashes(stripcslashes($str), "'\"\\\0..\37!@\@\177..\377");
}

function gc_makeSqlInteger($val) {
  return ((settype($val, 'integer'))?($val):0); 
}

function gc_makeSqlFloat($val) {
  return ((settype($val, 'float'))?($val):0); 
}
/* In case the XML API contains multiple open tags
 with the same value, then invoke this function and
 perform a foreach on the resultant array.
 This takes care of cases when there is only one unique tag
 or multiple tags.
 Examples of this are "anonymous-address", "merchant-code-string"
 from the merchant-calculations-callback API
 */
function gc_get_arr_result($child_node) {
  $result = array();
  if(isset($child_node)) {
    if(gc_is_associative_array($child_node)) {
      $result[] = $child_node;
    }
    else {
      foreach($child_node as $curr_node){
        $result[] = $curr_node;
      }
    }
  }

  return $result;
}

// Returns true if a given variable represents an associative array
function gc_is_associative_array($var) {
  return is_array($var) && !is_numeric(implode('', array_keys($var)));
} 

function gc_get_prattr($order_items) {
  $rta = array();
  foreach($order_items as $item) {
    $tmp = array();
    $pieces = explode('{', $item['id']);
    for($i=1,$n=count($pieces); $i<$n; $i++){
      list($opt, $val) = explode('}', $pieces[$i]);
      $tmp[$opt] = $val;
    }
    $rta[$pieces[0]] = array('attr' => $tmp,
                             'qty' => $item['qty']);
  }
  return $rta;
}


?>