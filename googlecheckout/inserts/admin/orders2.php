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
 * $Id: orders.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * This method is meant to be included in catalog/admin/orders.php.
 * 
 * TODO(eddavisson): Test!
 */
chdir("./..");
require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/googlecheckout.php');
require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration.php');
require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration_keys.php');
  
$config = new GoogleConfigurationKeys();

$payment_value= MODULE_PAYMENT_GOOGLECHECKOUT_TEXT_TITLE;
$num_rows = tep_db_num_rows(tep_db_query("select google_order_number from google_orders where orders_id= ". (int)$oID));

if ($num_rows != 0) {
  $customer_notified = google_checkout_state_change($check_status, $status, $oID, 
      (@$_POST['notify']=='on'?1:0), 
      (@$_POST['notify_comments']=='on'?$comments:''));
}
$customer_notified = isset($customer_notified)?$customer_notified:'0';
if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
  $notify_comments = '';
  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
  }
  $force_email = false;
  $use_cart_messaging = (gc_get_configuration_value($config->useCartMessaging()) == 'True');
  if ($num_rows != 0 && (strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH 
      && $user_cart_messaging)) {
    $force_email = true;
    $messageStack->add_session(GOOGLECHECKOUT_WARNING_SYSTEM_EMAIL_SENT, 'warning');          
  }

  if ($num_rows == 0 || $force_email) {
    // send emails, not a google order or configured to use both messaging systems
    $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
    tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    $customer_notified = '1';
    // send extra emails
  }
}

?>
