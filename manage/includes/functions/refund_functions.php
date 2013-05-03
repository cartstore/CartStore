<?php
/*
  $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  }
*/
 function tep_create_rma_value($length, $type = 'digits') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value)<$length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      } else if ($type == 'digits') {
        if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

  function tep_get_return_reason() {
    global $languages_id;

    $orders_status_array = array();
    $orders_status_query = tep_db_query("select return_reason_id, return_reason_name from " . TABLE_RETURN_REASONS . " where language_id = '" . $languages_id . "' order by return_reason_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array('id' => $orders_status['return_reason_id'],
                                     'text' => $orders_status['return_reason_name']
                                    );
    }

    return $orders_status_array;
  }

    function tep_get_return_reason_name($return_reason_id, $language_id = '') {
    global $languages_id;

    if ($return_reason_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select return_reason_name from " . TABLE_RETURN_REASONS . " where return_reason_id = '" . $return_reason_id . "' and language_id = '" . $language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['return_reason_name'];
  }



    function tep_calculate_deduct($price, $tax) {
    global $currencies;

    return (($price / 100) * $tax);
  }



     function tep_get_returns_status() {
    global $languages_id;

    $orders_status_array = array();
    $orders_status_query = tep_db_query("select returns_status_id, returns_status_name from " . TABLE_RETURNS_STATUS . " where language_id = '" . $languages_id . "' order by returns_status_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array('id' => $orders_status['returns_status_id'],
                                     'text' => $orders_status['returns_status_name']
                                    );
    }

    return $orders_status_array;
  }

    function tep_get_returns_status_name($returns_status_id, $language_id = '') {
    global $languages_id;

    if ($returns_status_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select returns_status_name from " . TABLE_RETURNS_STATUS . " where returns_status_id = '" . $returns_status_id . "' and language_id = '" . $language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['returns_status_name'];
  }

       function tep_get_refund_method() {
    global $languages_id;

    $orders_status_array = array();
    $orders_status_query = tep_db_query("select refund_method_id, refund_method_name from " . TABLE_REFUND_METHOD . " where language_id = '" . $languages_id . "' order by refund_method_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array('id' => $orders_status['refund_method_id'],
                                     'text' => $orders_status['refund_method_name']
                                    );
    }

    return $orders_status_array;
  }

    function tep_get_refund_method_name($refund_method_id, $language_id = '') {
    global $languages_id;

    if ($refund_method_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select refund_method_name from " . TABLE_REFUND_METHOD . " where refund_method_id = '" . $refund_method_id . "' and language_id = '" . $language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['refund_method_name'];
  }

   function tep_remove_return($order_id, $restock = false) {
    if ($restock == 'on') {
      $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_RETURNS_PRODUCTS_DATA . " where returns_id = '" . tep_db_input($order_id) . "'");
      while ($order = tep_db_fetch_array($order_query)) {
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . " where products_id = '" . $order['products_id'] . "'");
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_status = 1 where products_quantity > 0 and products_id = '" . $order['products_id'] . "'");
      }
    }

    tep_db_query("delete from " . TABLE_RETURNS . " where returns_id = '" . tep_db_input($order_id) . "'");
    tep_db_query("delete from " . TABLE_RETURNS_PRODUCTS_DATA . " where returns_id = '" . tep_db_input($order_id) . "'");
    tep_db_query("delete from " . TABLE_RETURN_PAYMENTS . " where returns_id = '" . tep_db_input($order_id) . "'");
  }
