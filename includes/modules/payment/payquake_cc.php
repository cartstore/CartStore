<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class payquake_cc {
    var $code, $title, $description, $enabled;

// class constructor
    function payquake_cc() {
      global $order;

      $this->code = 'payquake_cc';
      $this->title = MODULE_PAYMENT_PAYQUAKE_CC_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYQUAKE_CC_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYQUAKE_CC_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYQUAKE_CC_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PAYQUAKE_CC_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYQUAKE_CC_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYQUAKE_CC_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYQUAKE_CC_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.payquake_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.payquake_cc_number.value;' . "\n";

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $js .= '    var payquake_cc_cvc = document.checkout_payment.payquake_cc_cvc.value;' . "\n";
      }

      $js .= '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
             '      error_message = error_message + "' . MODULE_PAYMENT_PAYQUAKE_CC_JS_CC_OWNER . '";' . "\n" .
             '      error = 1;' . "\n" .
             '    }' . "\n" .
             '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
             '      error_message = error_message + "' . MODULE_PAYMENT_PAYQUAKE_CC_JS_CC_NUMBER . '";' . "\n" .
             '      error = 1;' . "\n" .
             '    }' . "\n";

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $js .= '    if (payquake_cc_cvc == "" || payquake_cc_cvc.length < 3) {' . "\n" .
               '      error_message = error_message + "' . MODULE_PAYMENT_PAYQUAKE_CC_JS_CC_CVC . '\n";' . "\n" .
               '      error = 1;' . "\n" .
               '    }' . "\n";
      }

      $js .= '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('payquake_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('payquake_cc_number')),
                                           array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('payquake_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('payquake_cc_expires_year', $expires_year))));

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $selection['fields'][] = array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_CVC,
                                       'field' => tep_draw_input_field('payquake_cc_cvc', '', 'size="5" maxlength="4"'));
      }

      return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['payquake_cc_number'], $HTTP_POST_VARS['payquake_cc_expires_month'], $HTTP_POST_VARS['payquake_cc_expires_year']);
      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&payquake_cc_owner=' . urlencode($HTTP_POST_VARS['payquake_cc_owner']) . '&payquake_cc_expires_month=' . $HTTP_POST_VARS['payquake_cc_expires_month'] . '&payquake_cc_expires_year=' . $HTTP_POST_VARS['payquake_cc_expires_year'] . (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True' ? '&payquake_cc_cvc=' . (int)$HTTP_POST_VARS['payquake_cc_cvc'] : '');

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = substr($cc_validation->cc_expiry_year, 2);
    }

    function confirmation() {
      global $HTTP_POST_VARS;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_OWNER,
                                                    'field' => $HTTP_POST_VARS['payquake_cc_owner']),
                                              array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$this->cc_expiry_month, 1, '20' . $this->cc_expiry_year)))));

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $confirmation['fields'][] = array('title' => MODULE_PAYMENT_PAYQUAKE_CC_CREDIT_CARD_CVC,
                                          'field' => (int)$HTTP_POST_VARS['payquake_cc_cvc']);
      }

      return $confirmation;
    }

    function process_button() {
      global $HTTP_POST_VARS;

      $process_button_string = tep_draw_hidden_field('payquake_cc_owner', $HTTP_POST_VARS['payquake_cc_owner']) .
                               tep_draw_hidden_field('payquake_cc_number', $this->cc_card_number) .
                               tep_draw_hidden_field('payquake_cc_expires_month', $this->cc_expiry_month) .
                               tep_draw_hidden_field('payquake_cc_expires_year', $this->cc_expiry_year);

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $process_button_string .= tep_draw_hidden_field('payquake_cc_cvc', (int)$HTTP_POST_VARS['payquake_cc_cvc']);
      }

      return $process_button_string;
    }

    function before_process() {
      global $customer_id, $order, $HTTP_POST_VARS;

      $this->pre_confirmation_check();

      $params = array('action' => 'ns_quicksale_cc',
                      'acctid' => MODULE_PAYMENT_PAYQUAKE_CC_ACCOUNT_ID,
                      'amount' => number_format($order->info['total'], 2),
                      'ccname' => $HTTP_POST_VARS['payquake_cc_owner'],
                      'expmon' => $this->cc_expiry_month,
                      'expyear' => $this->cc_expiry_year,
                      'authonly' => '1',
                      'ci_companyname' => $order->billing['company'],
                      'ci_billaddr1' => $order->billing['street_address'],
                      'ci_billcity' => $order->billing['city'],
                      'ci_billstate' => $order->billing['state'],
                      'ci_billzip' => $order->billing['postcode'],
                      'ci_billcountry' => $order->billing['country']['title'],
                      'ci_shipaddr1' => $order->delivery['street_address'],
                      'ci_shipcity' => $order->delivery['city'],
                      'ci_shipstate' => $order->delivery['state'],
                      'ci_shipzip' => $order->delivery['postcode'],
                      'ci_shipcountry' => $order->delivery['country']['title'],
                      'ci_phone' => $order->customer['telephone'],
                      'ci_email' => $order->customer['email_address'],
                      'email_from' => STORE_OWNER_EMAIL_ADDRESS,
                      'ci_ipaddress' => tep_get_ip_address(),
                      'merchantordernumber' => $customer_id);

      if (tep_not_null(MODULE_PAYMENT_PAYQUAKE_CC_3DES)) {
        $key = pack('H48', MODULE_PAYMENT_PAYQUAKE_CC_3DES);
        $data = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $this->cc_card_number, MCRYPT_MODE_ECB));

        $params['ccnum'] = $data;

        unset($key);
        unset($data);
      } else {
        $params['ccnum'] = $this->cc_card_number;
      }

      if (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True') {
        $params['cvv2'] = (int)$HTTP_POST_VARS['payquake_cc_cvc'];
      }

      if (tep_not_null(MODULE_PAYMENT_PAYQUAKE_CC_MERCHANT_PIN)) {
        $params['merchantPIN'] = MODULE_PAYMENT_PAYQUAKE_CC_MERCHANT_PIN;
      }

      $post_string = '';

      foreach ($params as $key => $value) {
        $post_string .= $key . '=' . urlencode(trim($value)) . '&';
      }

      $post_string = substr($post_string, 0, -1);

      $transaction_response = $this->sendTransactionToGateway('https://trans.merchantpartners.com/cgi-bin/process.cgi', $post_string);

      $error = false;

      if (!empty($transaction_response)) {
        $regs = explode("\n", trim($transaction_response));
        array_shift($regs);

        $result = array();

        foreach ($regs as $response) {
          $res = explode('=', $response, 2);

          $result[strtolower(trim($res[0]))] = trim($res[1]);
        }

        if ($result['status'] != 'Accepted') {
          $error = explode(':', $result['reason'], 3);
          $error = $error[2];

          if (empty($error)) {
            $error = MODULE_PAYMENT_PAYQUAKE_CC_ERROR_GENERAL;
          }
        }
      } else {
        $error = MODULE_PAYMENT_PAYQUAKE_CC_ERROR_GENERAL;
      }

      if ($error) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&payquake_cc_owner=' . urlencode($HTTP_POST_VARS['payquake_cc_owner']) . '&payquake_cc_expires_month=' . $this->cc_expiry_month . '&payquake_cc_expires_year=' . $this->cc_expiry_year . (MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC == 'True' ? '&payquake_cc_cvc=' . (int)$HTTP_POST_VARS['payquake_cc_cvc'] : ''), 'SSL'));
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_PAYQUAKE_CC_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYQUAKE_CC_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayQuake Credit Card Module', 'MODULE_PAYMENT_PAYQUAKE_CC_STATUS', 'False', 'Do you want to accept PayQuake credit card payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Account ID', 'MODULE_PAYMENT_PAYQUAKE_CC_ACCOUNT_ID', '', 'The account ID of the PayQuake account to use.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('3DES Encryption', 'MODULE_PAYMENT_PAYQUAKE_CC_3DES', '', 'Use this 3DES encryption key if it is enabled on the PayQuake Online Merchant Center.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant PIN', 'MODULE_PAYMENT_PAYQUAKE_CC_MERCHANT_PIN', '', 'Use this Merchant PIN if it is enabled on the PayQuake Online Merchant Center.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Verify With CVC', 'MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC', 'True', 'Verify the credit card with the billing address with the Credit Card Verification Checknumber (CVC)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYQUAKE_CC_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYQUAKE_CC_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYQUAKE_CC_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Program Location', 'MODULE_PAYMENT_PAYQUAKE_CC_CURL', '/usr/bin/curl', 'The location to the cURL program application.', '6', '0' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYQUAKE_CC_STATUS', 'MODULE_PAYMENT_PAYQUAKE_CC_ACCOUNT_ID', 'MODULE_PAYMENT_PAYQUAKE_CC_3DES', 'MODULE_PAYMENT_PAYQUAKE_CC_MERCHANT_PIN', 'MODULE_PAYMENT_PAYQUAKE_CC_VERIFY_WITH_CVC', 'MODULE_PAYMENT_PAYQUAKE_CC_ZONE', 'MODULE_PAYMENT_PAYQUAKE_CC_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYQUAKE_CC_SORT_ORDER', 'MODULE_PAYMENT_PAYQUAKE_CC_CURL');
    }

    function sendTransactionToGateway($url, $parameters) {
      $server = parse_url($url);

      if (isset($server['port']) === false) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if (isset($server['path']) === false) {
        $server['path'] = '/';
      }

      if (isset($server['user']) && isset($server['pass'])) {
        $header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
      }

      $connection_method = 0;

      if (function_exists('curl_init')) {
        $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
        curl_setopt($curl, CURLOPT_PORT, $server['port']);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

        $result = curl_exec($curl);

        curl_close($curl);
      } else {
        exec(escapeshellarg(MODULE_PAYMENT_PAYQUAKE_CC_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k', $result);
        $result = implode("\n", $result);
      }

      return $result;
    }
  }
?>