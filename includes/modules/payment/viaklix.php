<?php
/*

Elavon | ViaKLIX | NOVA | Virtual Merchant - Payment Module for osCommerce & CRE Loaded

 Copyright © (c) 2008 Black Table Media LLC

 Last update: June 2008

Selling this script without purchasing a sellers license, or claiming it to be your own,
is breaching United States copyright laws and will result in appropriate action
being taken under US law. A sellers license can also be requested by using
the form at http://www.blacktablemedia.com/contact/contact.php

*/

  class viaklix {
    var $code, $title, $description, $enabled;

// class constructor
    function viaklix() {
      global $order;

      $this->code = 'viaklix';
      $this->title = MODULE_PAYMENT_VIAKLIX_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_VIAKLIX_TEXT_DESCRIPTION;

      $this->sort_order = MODULE_PAYMENT_VIAKLIX_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_VIAKLIX_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_VIAKLIX_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_VIAKLIX_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

/* Viaklix first version uses this: https://www.viaKLIX.com/process.asp - without the '2'
-- The second version uses this -- https://www2.viaKLIX.com/process.asp, but since it's now virtual merchant use the one below

*/
      $this->form_action_url = 'https://www.myvirtualmerchant.com/VirtualMerchant/process.do';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_VIAKLIX_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_VIAKLIX_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
            '    var cc_owner = document.checkout_payment.viaklix_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.viaklix_cc_number.value;' . "\n" .
            '    var cvv_number = document.checkout_payment.viaklix_cvv_number.value;' . "\n" .
			'    var numericTest = /^[0-9][0-9][0-9]$|^[0-9][0-9][0-9][0-9]$/;' ."\n".
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_VIAKLIX_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_VIAKLIX_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cvv_number == "" || cvv_number.length < ' . CVV_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_VIAKLIX_TEXT_JS_CVV_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
			'    if (cvv_number.match(numericTest)) {' . "\n" .
			'      error = 0;' ."\n" .
			'    }' . "\n".
			'	 else {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_VIAKLIX_TEXT_JS_INVALID_CVV_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cvv_number.length > ' . CVV_NUMBER_MAX_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_VIAKLIX_TEXT_JS_MAX_CVV_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

 // below makes the input fields
    function selection() {
      global $order;

      for ($i=1; $i < 13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i) .' ' . strftime('%B',mktime(0,0,0,$i,1,2000))) ;
      }


      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $indicators[] = array('id'=>'present','text'=>'Present');
      $indicators[] = array('id'=>'Not Present','text'=>'Not Present');
      $indicators[] = array('id'=>'Bypassed','text'=>'Missing');
      $indicators[] = array('id'=>'Illegible','text'=>'Illegible');


      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('viaklix_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('viaklix_cc_number','','Autocomplete = off')),
                                           array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('viaklix_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('viaklix_cc_expires_year', $expires_year)),
                                           array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CVV_NUMBER,
                                                 'field' => tep_draw_input_field('viaklix_cvv_number','','Autocomplete = off size="5" maxlength="4" '))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['viaklix_cc_number'], $_POST['viaklix_cc_expires_month'], $_POST['viaklix_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&viaklix_cc_owner=' . urlencode($_POST['viaklix_cc_owner']) . '&viaklix_cc_expires_month=' . $_POST['viaklix_cc_expires_month'] . '&viaklix_cc_expires_year=' . $_POST['viaklix_cc_expires_year'] . '&viaklix_cvv_number=' . $_POST['viaklix_cvv_number']. '&viaklix_cvv_indicator=' . $_POST['viaklix_cvv_indicator'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;

    }

    function confirmation() {
      global $_POST;
      //detect if they put in a valid cvv and set indicator accoringly kps ;

    if (preg_match ('/(^[0-9][0-9][0-9]$|^[0-9][0-9][0-9][0-9]$)/',$_POST['viaklix_cvv_number']) == 1){
      $_POST['viaklix_cvv_indicator'] = 'Present';
    }else{
      $_POST['viaklix_cvv_indicator'] = 'Present';
      $_POST['viaklix_cvv_number'] ='';
    }
// end kps
      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['viaklix_cc_owner']),
                                              array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CVV_NUMBER,
                                                    'field' => $_POST['viaklix_cvv_number']),
                                              array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CVV_INDICATOR,
                                                    'field' => $_POST['viaklix_cvv_indicator']),
                                              array('title' => MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['viaklix_cc_expires_month'], 1, '20' . $_POST['viaklix_cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $_POST, $order, $currencies, $currency,$customer_id;

// MOD - select state abbreviation if available
    $country = $order->billing['country']['iso_code_2'];
    if (($country == 'US') || ($country == 'CA')) {
      $state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_id = '" . (int)$order->billing['zone_id'] . "'");
      $state_result = tep_db_fetch_array($state_query);
      $billState = $state_result['zone_code'];
    } else {
      $billState = $order->billing['state'];
    }

    $country = $order->delivery['country']['iso_code_2'];
    if (($country == 'US') || ($country == 'CA')) {
      $state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_id = '" . (int)$order->delivery['zone_id'] . "'");
      $state_result = tep_db_fetch_array($state_query);
      $shipState = $state_result['zone_code'];
    } else {
      $shipState = $order->delivery['state'];
    }
// EOM

// **Changes for Virtual Merchant - transaction_type is now ccsale and cvv2 is now cvv2_cvc2_indicator (This is also labeled wrong in their developer guide)

   $process_button_string =
    tep_draw_hidden_field('ssl_merchant_id', MODULE_PAYMENT_VIAKLIX_MERCHANT_ID) .
    tep_draw_hidden_field('ssl_user_id', MODULE_PAYMENT_VIAKLIX_USER_ID) .
    tep_draw_hidden_field('ssl_pin',MODULE_PAYMENT_VIAKLIX_PIN).
    tep_draw_hidden_field('ssl_amount', number_format($order->info['total'] * $currencies->get_value('USD'), 2, '.',',')) .
    tep_draw_hidden_field('ssl_salestax', number_format($order->info['tax'] * $currencies->get_value('USD'), 2, '.',',')) .
    tep_draw_hidden_field('ssl_show_form','FALSE').
    tep_draw_hidden_field('ssl_card_number', $_POST['viaklix_cc_number']) .
    tep_draw_hidden_field('ssl_transaction_type','ccsale').
    tep_draw_hidden_field('ssl_exp_date', $_POST['viaklix_cc_expires_month']. $_POST['viaklix_cc_expires_year']) .

    tep_draw_hidden_field('ssl_cvv2cvc2_indicator', $_POST['viaklix_cvv_indicator']) .
    tep_draw_hidden_field('ssl_cvv2cvc2', $_POST['viaklix_cvv_number']) .

    tep_draw_hidden_field('ssl_customer_code', $customer_id) .
    tep_draw_hidden_field('ssl_company', $order->billing['company']) .
    tep_draw_hidden_field('ssl_first_name', $order->billing['firstname']) .
    tep_draw_hidden_field('ssl_last_name', $order->billing['lastname']) .
    tep_draw_hidden_field('ssl_avs_address', substr($order->billing['street_address'],0,20)) . // Virtual merchant does not accept address longer than 20 characters so use substr - They only use address number to validate cc
    tep_draw_hidden_field('ssl_address2', $order->billing['suburb']) .
    tep_draw_hidden_field('ssl_city', $order->billing['city']) .
    tep_draw_hidden_field('ssl_state', $billState) .

    tep_draw_hidden_field('ssl_avs_zip', $order->billing['postcode']) .
    tep_draw_hidden_field('ssl_country', $order->billing['country']['title']['iso_code_2']) .
    tep_draw_hidden_field('ssl_phone', $order->customer['telephone']) .
    tep_draw_hidden_field('ssl_email', $order->customer['email_address']) .

    tep_draw_hidden_field('ssl_ship_to_company', $order->delivery['company']) .
    tep_draw_hidden_field('ssl_ship_to_first_name', $order->delivery['firstname']) .
    tep_draw_hidden_field('ssl_ship_to_last_name', $order->delivery['lastname']) .
    tep_draw_hidden_field('ssl_ship_to_avs_address', $order->delivery['street_address']) .
    tep_draw_hidden_field('ssl_ship_to_address2', $order->delivery['suburb']) .
    tep_draw_hidden_field('ssl_ship_to_city', $order->delivery['city']) .
// MOD - use state abbreviation if available
    tep_draw_hidden_field('ssl_ship_to_state', $shipState) .
//    tep_draw_hidden_field('ssl_ship_to_state', $order->delivery['state']) .
// EOM
    tep_draw_hidden_field('ssl_ship_to_avs_zip', $order->delivery['postcode']) .
    tep_draw_hidden_field('ssl_ship_to_country', $order->delivery['country']['title']['iso_code_2']) .
    tep_draw_hidden_field('ssl_description', "Customer's comment: ". substr($_POST['comments'],0,240)) .

    tep_draw_hidden_field('ssl_result_format', 'HTML') .
    tep_draw_hidden_field('ssl_receipt_apprvl_method', 'REDG') .
    tep_draw_hidden_field('ssl_receipt_decl_method', 'REDG') .
	tep_draw_hidden_field('ssl_receipt_apprvl_get_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
    tep_draw_hidden_field('ssl_receipt_decl_get_url', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&viaklix_cc_owner=' . urlencode($_POST['viaklix_cc_owner']) . '&passedvars=coming' , 'SSL', false));

   //$test = htmlspecialchars($process_button_string);
   //print_r ($test);

      if (MODULE_PAYMENT_VIAKLIX_TESTMODE == 'Test') $process_button_string .= tep_draw_hidden_field('ssl_test_mode', 'TRUE');

	//below was commented out but shouldn't be
    $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());
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

      $error = array('title' => VIAKLIX_ERROR_HEADING,
                     'error' => ((isset($_GET['ssl_result_message'])) ? stripslashes(urldecode($_GET['ssl_result_message'])) .' '.  VIAKLIX_ERROR_MESSAGE : VIAKLIX_ERROR_MESSAGE));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_VIAKLIX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable viaKLIX Module', 'MODULE_PAYMENT_VIAKLIX_STATUS', 'True', 'Do you want to accept viaKLIX payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('viaKLIX account Number', 'MODULE_PAYMENT_VIAKLIX_MERCHANT_ID', '99999', 'The account number used for the viaKLIX service (Not the MID(Mechant ID)!', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User ID', 'MODULE_PAYMENT_VIAKLIX_USER_ID', '99999', 'The user ID for the viaKLIX service', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User PIN', 'MODULE_PAYMENT_VIAKLIX_PIN', '0', 'The user PIN for the viaKLIX service', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_VIAKLIX_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_VIAKLIX_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_VIAKLIX_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_VIAKLIX_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_VIAKLIX_STATUS', 'MODULE_PAYMENT_VIAKLIX_MERCHANT_ID', 'MODULE_PAYMENT_VIAKLIX_USER_ID', 'MODULE_PAYMENT_VIAKLIX_PIN', 'MODULE_PAYMENT_VIAKLIX_TESTMODE','MODULE_PAYMENT_VIAKLIX_ZONE', 'MODULE_PAYMENT_VIAKLIX_ORDER_STATUS_ID', 'MODULE_PAYMENT_VIAKLIX_SORT_ORDER');
    }
  }
?>
