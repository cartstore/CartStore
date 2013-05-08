<?php
/*
  $Id: ipn.php,v 1.1.2.8 2004/12/07 15:46:08 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  chdir('../../../../');
  require('includes/application_top.php');

  $parameters = 'cmd=_notify-validate';

  foreach ($_POST as $key => $value) {
    $parameters .= '&' . $key . '=' . urlencode(stripslashes($value));
  }

  if (MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER == 'Live') {
    $server = 'www.paypal.com';
  } else {
    $server = 'www.sandbox.paypal.com';
  }

  $fsocket = false;
  $curl = false;
  $result = false;

  if ( (PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://' . $server, 443, $errno, $errstr, 30)) ) {
    $fsocket = true;
  } elseif (function_exists('curl_exec')) {
    $curl = true;
  } elseif ($fp = @fsockopen($server, 80, $errno, $errstr, 30)) {
    $fsocket = true;
  }

  if ($fsocket == true) {
    $header = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n" .
              'Host: ' . $server . "\r\n" .
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

    curl_setopt($ch, CURLOPT_URL, 'https://' . $server . '/cgi-bin/webscr');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);

    curl_close($ch);
  }

  if ($result == 'VERIFIED') {
    if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && ($_POST['invoice'] > 0)) {
      $order_query = tep_db_query("select currency, currency_value from " . TABLE_ORDERS . " where orders_id = '" . $_POST['invoice'] . "' and customers_id = '" . (int)$_POST['custom'] . "'");
      if (tep_db_num_rows($order_query) > 0) {
        $order = tep_db_fetch_array($order_query);

        $total_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $_POST['invoice'] . "' and class = 'ot_total' limit 1");
        $total = tep_db_fetch_array($total_query);

        $comment_status = $_POST['payment_status'] . ' (' . ucfirst($_POST['payer_status']) . '; ' . $currencies->format($_POST['mc_gross'], false, $_POST['mc_currency']) . ')';

        if ($_POST['payment_status'] == 'Pending') {
          $comment_status .= '; ' . $_POST['pending_reason'];
        } elseif ( ($_POST['payment_status'] == 'Reversed') || ($_POST['payment_status'] == 'Refunded') ) {
          $comment_status .= '; ' . $_POST['reason_code'];
        }

        $order_status_id = DEFAULT_ORDERS_STATUS_ID;

        if ($_POST['mc_gross'] == number_format($total['value'] * $order['currency_value'], $currencies->get_decimal_places($order['currency']))) {
          if (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) {
            $order_status_id = MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID;
          }
        }

        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . $order_status_id . "', last_modified = now() where orders_id = '" . $_POST['invoice'] . "'");

        $sql_data_array = array('orders_id' => $_POST['invoice'],
                                'orders_status_id' => $order_status_id,
                                'date_added' => 'now()',
                                'customer_notified' => '0',
                                'comments' => 'PayPal IPN Verified [' . $comment_status . ']');

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      }
    }
  } else {
    if (tep_not_null(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL)) {
      $email_body = '$_POST:' . "\n\n";
      foreach ($_POST as $key => $value) {
        $email_body .= $key . '=' . $value . "\n";
      }
      $email_body .= "\n" . '$_GET:' . "\n\n";
      foreach ($_GET as $key => $value) {
        $email_body .= $key . '=' . $value . "\n";
      }

      tep_mail('', MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL, 'PayPal IPN Invalid Process', $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }

    if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && ($_POST['invoice'] > 0)) {
      $check_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $_POST['invoice'] . "' and customers_id = '" . (int)$_POST['custom'] . "'");
      if (tep_db_num_rows($check_query) > 0) {
        $comment_status = $_POST['payment_status'];

        if ($_POST['payment_status'] == 'Pending') {
          $comment_status .= '; ' . $_POST['pending_reason'];
        } elseif ( ($_POST['payment_status'] == 'Reversed') || ($_POST['payment_status'] == 'Refunded') ) {
          $comment_status .= '; ' . $_POST['reason_code'];
        }

        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . ((MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . $_POST['invoice'] . "'");

        $sql_data_array = array('orders_id' => $_POST['invoice'],
                                'orders_status_id' => (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : DEFAULT_ORDERS_STATUS_ID,
                                'date_added' => 'now()',
                                'customer_notified' => '0',
                                'comments' => 'PayPal IPN Invalid [' . $comment_status . ']');

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      }
    }
  }

  require('includes/application_bottom.php');
?>