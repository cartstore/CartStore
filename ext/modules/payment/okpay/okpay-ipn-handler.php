<?php
/*
  $Id: okpay-ipn-handler.php 1778 2010-05-14 23:37:44Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  chdir('../../../../');
  require('includes/application_top.php');

  $parameters = 'ok_verify=true';

  reset($HTTP_POST_VARS);
  while (list($key, $value) = each($HTTP_POST_VARS)) {
    $parameters .= '&' . $key . '=' . urlencode(stripslashes($value));
  }

  $fsocket = false;
  $curl = false;
  $result = false;

  if ( (PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://www.okpay.com', 443, $errno, $errstr, 30)) ) {
    $fsocket = true;
  } elseif (function_exists('curl_exec')) {
    $curl = true;
  } elseif ($fp = @fsockopen('www.okpay.com', 80, $errno, $errstr, 30)) {
    $fsocket = true;
  }

  if ($fsocket == true) {
    $header = 'POST /ipn-verify.html HTTP/1.0' . "\r\n" .
              'Host: www.okpay.com\r\n' .
              'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
              'Content-Length: ' . strlen($parameters) . "\r\n" .
              'Connection: close' . "\r\n\r\n";

    @fputs($fp, $header . $parameters);

    $string = '';
    while (!@feof($fp)) {
      $res = @fgets($fp, 1024);
      $string .= $res;

      if ( ($res == 'VERIFIED') || ($res == 'INVALID') ) {
        $result = $res;

        break;
      }
    }

    @fclose($fp);
  } elseif ($curl == true) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.okpay.com/ipn-verify.html');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);

    curl_close($ch);
  }

  if ($result == 'VERIFIED')
  {
    if (isset($HTTP_POST_VARS['ok_invoice']) && is_numeric($HTTP_POST_VARS['ok_invoice']) && ($HTTP_POST_VARS['ok_invoice'] > 0))
    {
      $order_query = tep_db_query("select orders_status, currency, currency_value from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_POST_VARS['ok_invoice'] . "' and customers_id = '" . (int)$HTTP_POST_VARS['ok_item_1_custom_1_value'] . "'");
      if (tep_db_num_rows($order_query) > 0)
      {
        $order = tep_db_fetch_array($order_query);

        if ($order['orders_status'] == MODULE_PAYMENT_OKPAY_PREPARE_ORDER_STATUS_ID) {
          $sql_data_array = array('orders_id' => $HTTP_POST_VARS['ok_invoice'],
                                  'orders_status_id' => MODULE_PAYMENT_OKPAY_PREPARE_ORDER_STATUS_ID,
                                  'date_added' => 'now()',
                                  'customer_notified' => '0',
                                  'comments' => '');

          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID > 0 ? (int)MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID : (int)DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . (int)$HTTP_POST_VARS['ok_invoice'] . "'");
        }

        $total_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $HTTP_POST_VARS['ok_invoice'] . "' and class = 'ot_total' limit 1");
        $total = tep_db_fetch_array($total_query);

        $comment_status = $HTTP_POST_VARS['payment_status'] . ' (' . ucfirst($HTTP_POST_VARS['payer_status']) . '; ' . $currencies->format($HTTP_POST_VARS['mc_gross'], false, $HTTP_POST_VARS['mc_currency']) . ')';

        if ($HTTP_POST_VARS['payment_status'] == 'Pending') {
          $comment_status .= '; ' . $HTTP_POST_VARS['pending_reason'];
        } elseif ( ($HTTP_POST_VARS['payment_status'] == 'Reversed') || ($HTTP_POST_VARS['payment_status'] == 'Refunded') ) {
          $comment_status .= '; ' . $HTTP_POST_VARS['reason_code'];
        }

        if ($HTTP_POST_VARS['mc_gross'] != number_format($total['value'] * $order['currency_value'], $currencies->get_decimal_places($order['currency']))) {
          $comment_status .= '; PayPal transaction value (' . tep_output_string_protected($HTTP_POST_VARS['mc_gross']) . ') does not match order value (' . number_format($total['value'] * $order['currency_value'], $currencies->get_decimal_places($order['currency'])) . ')';
        }

        $sql_data_array = array('orders_id' => $HTTP_POST_VARS['ok_invoice'],
                                'orders_status_id' => (MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID > 0 ? (int)MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID : (int)DEFAULT_ORDERS_STATUS_ID),
                                'date_added' => 'now()',
                                'customer_notified' => '0',
                                'comments' => 'PayPal IPN Verified [' . $comment_status . ']');

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      }
    }
  } else {
    if (tep_not_null(MODULE_PAYMENT_OKPAY_DEBUG_EMAIL)) {
      $email_body = '$HTTP_POST_VARS:' . "\n\n";

      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        $email_body .= $key . '=' . $value . "\n";
      }

      $email_body .= "\n" . '$HTTP_GET_VARS:' . "\n\n";

      reset($HTTP_GET_VARS);
      while (list($key, $value) = each($HTTP_GET_VARS)) {
        $email_body .= $key . '=' . $value . "\n";
      }

      tep_mail('', MODULE_PAYMENT_OKPAY_DEBUG_EMAIL, 'PayPal IPN Invalid Process', $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }

    if (isset($HTTP_POST_VARS['ok_invoice']) && is_numeric($HTTP_POST_VARS['ok_invoice']) && ($HTTP_POST_VARS['ok_invoice'] > 0)) {
      $check_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_POST_VARS['ok_invoice'] . "' and customers_id = '" . (int)$HTTP_POST_VARS['custom'] . "'");
      if (tep_db_num_rows($check_query) > 0) {
        $comment_status = $HTTP_POST_VARS['payment_status'];

        if ($HTTP_POST_VARS['payment_status'] == 'Pending') {
          $comment_status .= '; ' . $HTTP_POST_VARS['pending_reason'];
        } elseif ( ($HTTP_POST_VARS['payment_status'] == 'Reversed') || ($HTTP_POST_VARS['payment_status'] == 'Refunded') ) {
          $comment_status .= '; ' . $HTTP_POST_VARS['reason_code'];
        }

        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . ((MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . $HTTP_POST_VARS['ok_invoice'] . "'");

        $sql_data_array = array('orders_id' => $HTTP_POST_VARS['ok_invoice'],
                                'orders_status_id' => (MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_OKPAY_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID,
                                'date_added' => 'now()',
                                'customer_notified' => '0',
                                'comments' => 'PayPal IPN Invalid [' . $comment_status . ']');

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      }
    }
  }

  require('includes/application_bottom.php');

?>