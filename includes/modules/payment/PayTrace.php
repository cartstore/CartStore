<?php
/*
  $Id: PayTrace.php,v 1.00 2006/04/15 01:02:00 project1000 Exp $

  PayTrace, The secure advantage.
  https://PayTrace.com

  Copyright (c) 2006 PayTrace, LLC
  
  
  *********************************
  
  
  This file should be located:
  
  /catalog/includes/modules/payment/PayTrace.php
  
  
  *********************************
  
  PayTrace strongly advises all merchants using the PayTrace API to install use a SSL certificate 
  to encrypt all sensitive information entered in their shopping cart by the their customers.  
  Whether the merchant is using osCommerce or another solution, the use of SSL encryption is strongly 
  recommended.
  
  Additionally, PayTrace strongly advises that NO CARDHOLDER DATA (CHD) is stored by any merchant, and
  CSC values may never be stored.
  
  *********************************
  

  Released under the GNU General Public License
*/

  class PayTrace {
    var $code, $title, $description, $enabled;

// class constructor
    function PayTrace() {
      global $order;

      $this->code = 'PayTrace';
      $this->title = MODULE_PAYMENT_PAYTRACE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYTRACE_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYTRACE_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYTRACE_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PAYTRACE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYTRACE_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = (tep_href_link('PayTrace.php','','SSL'));
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYTRACE_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYTRACE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
            '    var cc_owner = document.checkout_payment.PayTrace_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.PayTrace_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_PAYTRACE_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_PAYTRACE_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      for ($i=1; $i < 13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('PayTrace_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('PayTrace_cc_number')),
                                           array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('PayTrace_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('PayTrace_cc_expires_year', $expires_year)),
                                           array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_CHECKNUMBER,
                                                 'field' => tep_draw_input_field('PayTrace_cc_checkcode', '', 'size="4" maxlength="4"') . '&nbsp;<small>' . MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION . '</small>')));

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['PayTrace_cc_number'], $_POST['PayTrace_cc_expires_month'], $_POST['PayTrace_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&PayTrace_cc_owner=' . urlencode($_POST['PayTrace_cc_owner']) . '&PayTrace_cc_expires_month=' . $_POST['PayTrace_cc_expires_month'] . '&PayTrace_cc_expires_year=' . $_POST['PayTrace_cc_expires_year'] . '&PayTrace_cc_checkcode=' . $_POST['PayTrace_cc_checkcode'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $_POST;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['PayTrace_cc_owner']),
                                              array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['PayTrace_cc_expires_month'], 1, '20' . $_POST['PayTrace_cc_expires_year'])))));

      if (tep_not_null($_POST['PayTrace_cc_checkcode'])) {
        $confirmation['fields'][] = array('title' => MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_CHECKNUMBER,
                                          'field' => $_POST['PayTrace_cc_checkcode']);
      }

      return $confirmation;
    }

    function process_button() {
      global $_POST, $order, $currencies, $currency;

      $process_button_string = tep_draw_hidden_field('METHOD', 'ProcessTranx') .
                               tep_draw_hidden_field('TRANXTYPE', MODULE_PAYMENT_PAYTRACE_TYPE) .
                               tep_draw_hidden_field('UN', MODULE_PAYMENT_PAYTRACE_USER_ID) .
                               tep_draw_hidden_field('PSWD', MODULE_PAYMENT_PAYTRACE_PASSWORD) .
							   tep_draw_hidden_field('PROXY', MODULE_PAYMENT_PAYTRACE_PROXY) .
							   tep_draw_hidden_field('TERMS', 'Y') .
                               tep_draw_hidden_field('AMOUNT', number_format($order->info['total'] * 100 * $currencies->get_value('USD'), 0, '','')) .
                               tep_draw_hidden_field('EXPMNTH', $_POST['PayTrace_cc_expires_month']) .
                               tep_draw_hidden_field('EXPYR', $_POST['PayTrace_cc_expires_year']) .
                               tep_draw_hidden_field('CC', $_POST['PayTrace_cc_number']) .
                               tep_draw_hidden_field('CSC', $_POST['PayTrace_cc_checkcode']) .
							   tep_draw_hidden_field('BADDRESS', tep_output_string_protected($order->billing['street_address'])) .
							   tep_draw_hidden_field('BZIP', tep_output_string_protected($order->billing['postcode'])) .
                               tep_draw_hidden_field('silent_error_url', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&PayTrace_cc_owner=' . urlencode($_POST['PayTrace_cc_owner']), 'SSL', true));

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $_GET;

      $error = array('title' => PAYTRACE_ERROR_HEADING,
                     'error' => ((isset($_GET['error'])) ? stripslashes(urldecode($_GET['error'])) : PAYTRACE_ERROR_MESSAGE));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYTRACE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayTrace', 'MODULE_PAYMENT_PAYTRACE_STATUS', 'True', 'Do you want to accept PayTrace payments?<br>**SSL strongly recommended.**', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User Name', 'MODULE_PAYMENT_PAYTRACE_USER_ID', 'demo123', 'The user name for PayTrace.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User Password', 'MODULE_PAYMENT_PAYTRACE_PASSWORD', 'demo123', 'The password for PayTrace.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_PAYTRACE_TYPE', 'Authorization', 'PayTrace transaction type.', '6', '5', 'tep_cfg_select_option(array(\'Authorization\',\'Sale\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('HTTPS Proxy Server', 'MODULE_PAYMENT_PAYTRACE_PROXY', '', 'If your web host requires an SSL proxy server, enter the server\'s address as \'http://ServerNameOrIP:PortNumber\'', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYTRACE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYTRACE_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYTRACE_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYTRACE_STATUS', 'MODULE_PAYMENT_PAYTRACE_USER_ID', 'MODULE_PAYMENT_PAYTRACE_PASSWORD', 'MODULE_PAYMENT_PAYTRACE_TYPE', 'MODULE_PAYMENT_PAYTRACE_PROXY', 'MODULE_PAYMENT_PAYTRACE_ZONE', 'MODULE_PAYMENT_PAYTRACE_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYTRACE_SORT_ORDER');
    }
  }
?>
