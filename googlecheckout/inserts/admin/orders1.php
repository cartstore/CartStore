<?php
/*
  Copyright (C) 2008 Google Inc.

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

/**
 * Google Checkout v1.5.0
 * $Id: orders1.php 169 2009-02-17 21:53:06Z ed.davisson $
 * 
 * This method is meant to be included in catalog/admin/orders.php.
 */
 
define('GC_STATE_NEW', 100);
define('GC_STATE_PROCESSING', 101);
define('GC_STATE_SHIPPED', 102);
define('GC_STATE_REFUNDED', 103);
define('GC_STATE_SHIPPED_REFUNDED', 104);
define('GC_STATE_CANCELED', 105);

// Execute the cron hook.
require_once(DIR_FS_CATALOG . '/googlecheckout/library/google_cron_hook.php');
$google_cron_hook = new GoogleCronHook();
$google_cron_hook->execute();

function google_checkout_state_change($check_status, $status, $oID, 
                                      $cust_notify, $notify_comments) {
  global $db, $messageStack, $orders_statuses;

  define('API_CALLBACK_ERROR_LOG', 
         DIR_FS_CATALOG. "/googlecheckout/logs/response_error.log");
  define('API_CALLBACK_MESSAGE_LOG',
         DIR_FS_CATALOG . "/googlecheckout/logs/response_message.log");

  include_once(DIR_FS_CATALOG . '/includes/modules/payment/googlecheckout.php');
  include_once(DIR_FS_CATALOG . '/googlecheckout/library/googlerequest.php');
  require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration.php');
  require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration_keys.php');
  
  $config = new GoogleConfigurationKeys();

  $googlecheckout = new googlecheckout();
  
  $google_request = new GoogleRequest($googlecheckout->merchantid, 
                                      $googlecheckout->merchantkey, 
                                      MODULE_PAYMENT_GOOGLECHECKOUT_MODE ==
                                          'https://sandbox.google.com/checkout/'
                                           ? "sandbox" : "production",
                                      DEFAULT_CURRENCY);
  $google_request->SetLogFiles(API_CALLBACK_ERROR_LOG, API_CALLBACK_MESSAGE_LOG);

  $google_answer = tep_db_fetch_array(tep_db_query("SELECT go.google_order_number, go.order_amount, o.customers_email_address, gc.buyer_id, o.customers_id
                                  FROM " . $googlecheckout->table_order . " go 
                                  inner join " . TABLE_ORDERS . " o on go.orders_id = o.orders_id
                                  inner join " . $googlecheckout->table_name . " gc on gc.customers_id = o.customers_id
                                  WHERE go.orders_id = '" . (int)$oID ."'
                                  group by o.customers_id order by o.orders_id desc"));

  $google_order = $google_answer['google_order_number'];  
  $amount = $google_answer['order_amount'];  

  // If status update is from Google New -> Google Processing on the Admin UI
  // this invokes the processing-order and charge-order commands
  // 1->Google New, 2-> Google Processing
  if ($check_status['orders_status'] == GC_STATE_NEW 
      && $status == GC_STATE_PROCESSING && $google_order != '') {
    list($curl_status,) = $google_request->SendChargeOrder($google_order, $amount);
    if ($curl_status != 200) {
      $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_CHARGE_ORDER, 'error');
    } else {
      $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_CHARGE_ORDER, 'success');          
    }
    
    list($curl_status,) = $google_request->SendProcessOrder($google_order);
    if ($curl_status != 200) {
      $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_PROCESS_ORDER, 'error');
    } else {
      $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_PROCESS_ORDER, 'success');          
    }
  } 
  
  // If status update is from Google Processing or Google Refunded -> Google Shipped on the Admin UI
  // this invokes the deliver-order and archive-order commands
  // 2->Google Processing or Google Refunded, 3-> Google Shipped (refunded)
  else if (($check_status['orders_status'] == GC_STATE_PROCESSING 
      || $check_status['orders_status'] == GC_STATE_REFUNDED)
      && ($status == GC_STATE_SHIPPED || $status == GC_STATE_SHIPPED_REFUNDED)
      && $google_order != '') {
    $carrier = $tracking_no = "";
    // Add tracking Data
    if (isset($_POST['carrier_select']) && ($_POST['carrier_select'] != 'select') 
        && isset($_POST['tracking_number']) && !empty($_POST['tracking_number'])) {
      $carrier = $_POST['carrier_select'];
      $tracking_no = $_POST['tracking_number'];
      $comments = GOOGLECHECKOUT_STATE_STRING_TRACKING ."\n" .
                  GOOGLECHECKOUT_STATE_STRING_TRACKING_CARRIER . $_POST['carrier_select'] ."\n" .
                  GOOGLECHECKOUT_STATE_STRING_TRACKING_NUMBER . $_POST['tracking_number'] . "";
      tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
                  (orders_id, orders_status_id, date_added, customer_notified, comments)
                  values ('" . (int)$oID . "',
                  '" . tep_db_input(($check_status['orders_status']==GC_STATE_REFUNDED
                                    ?GC_STATE_SHIPPED_REFUNDED:GC_STATE_SHIPPED)) . "',
                  now(),
                  '" . tep_db_input($cust_notify) . "',
                  '" . tep_db_input($comments)  . "')");
       
    }
    
    list($curl_status,) = $google_request->SendDeliverOrder($google_order, $carrier,
        $tracking_no, ($cust_notify==1)?"true":"false");
    if ($curl_status != 200) {
      $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_DELIVER_ORDER, 'error');
    } else {
      $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_DELIVER_ORDER, 'success');          
    }
    
    list($curl_status,) = $google_request->SendArchiveOrder($google_order);
    if ($curl_status != 200) {
      $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_ARCHIVE_ORDER, 'error');
    } else {
      $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_ARCHIVE_ORDER, 'success');          
    }
  }
  
  // If status update is to Google Canceled on the Admin UI
  // this invokes the cancel-order and archive-order commands
  else if ($check_status['orders_status'] != GC_STATE_CANCELED 
      && $status == GC_STATE_CANCELED && $google_order != '') {
    if ($check_status['orders_status'] != GC_STATE_NEW){
      list($curl_status,) = $google_request->SendRefundOrder(
          $google_order, 0, GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED);
      if ($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_REFUND_ORDER, 'error');
      } else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_REFUND_ORDER, 'success');          
      }
    } else {
      // Tell google witch is the OSC's internal order Number        
      list($curl_status,) = $google_request->SendMerchantOrderNumber($google_order, $oID);
      if ($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_MERCHANT_ORDER_NUMBER, 'error');
      } else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_MERCHANT_ORDER_NUMBER, 'success');          
      }
    }
    // Is the order is not archive, I do it
    if ($check_status['orders_status'] != GC_STATE_SHIPPED 
        && $check_status['orders_status'] != GC_STATE_SHIPPED_REFUNDED){
      list($curl_status,) = $google_request->SendArchiveOrder($google_order);
      if ($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_ARCHIVE_ORDER, 'error');
      } else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_ARCHIVE_ORDER, 'success');          
      }
    }
    // Cancel the order
    list($curl_status,) = $google_request->SendCancelOrder(
        $google_order, GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED, $notify_comments);
    if ($curl_status != 200) {
      $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_CANCEL_ORDER, 'error');
    } else {
      $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_CANCEL_ORDER, 'success');          
    }
  } else if ($google_order != '' && $check_status['orders_status'] != $status) {
    $statuses = array();
    foreach ($orders_statuses as $status_array) {
      $statuses[$status_array['id']] = $status_array['text'];
    }
    $messageStack->add_session(sprintf(GOOGLECHECKOUT_ERR_INVALID_STATE_TRANSITION,
                               $statuses[$check_status['orders_status']],
                               $statuses[$status],
                               $statuses[$check_status['orders_status']]),
                               'error');
  }    
  
  // Send Buyer's message
  if ($cust_notify == 1 && isset($notify_comments) && !empty($notify_comments)) {
    $cust_notify_ok = '0';
    $use_cart_messaging = (gc_get_configuration_value($config->useCartMessaging()) == 'True');
    if (!((strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH)
        && $use_cart_messaging)) {
  
      list($curl_status,) = $google_request->sendBuyerMessage(
          $google_order, $notify_comments, "true");
      if ($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_MESSAGE_ORDER, 'error');
        $cust_notify_ok = '0';
      } else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_MESSAGE_ORDER, 'success');          
        $cust_notify_ok = '1';
      }
      
      if (strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH) {
        $messageStack->add_session(
        sprintf(GOOGLECHECKOUT_WARNING_CHUNK_MESSAGE, GOOGLE_MESSAGE_LENGTH), 'warning');          
      }
    }
    // Cust notified
    return $cust_notify_ok;
  }
  // Cust notified
  return '0';
}

?>
