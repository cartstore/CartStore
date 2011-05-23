<?php

/* $Id: authorizenet_aim.php 23rd August, 2006 18:50:00 Brent O'Keeffe $
   GNU General Public License Compatible

   CartStore eCommerce Software, for The Next Generation
   http://www.cartstore.com

   Original portions copyright 2003 osCommerce
   Updated portions copyright 2004 Jason LeBaron (jason@networkdad.com)
   Restoration of original portions and addition of new portions Copyright (c) 2008 Adoovo Inc. USA
   Updated portions and additions copyright 2006 Brent O'Keeffe - JK Consulting. (brent@jkconsulting.net)
*/


  class authorizenet_aim {
    var $code, $title, $description, $enabled, $response;

// class constructor
    function authorizenet_aim() {

      $this->code = 'authorizenet_aim';
     if ($_GET['main_page'] != '') {
       $this->title = MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CATALOG_TITLE; // Module title in Catalog
     } else {
       $this->title = MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_ADMIN_TITLE; // Module title it Admin
     }
      $this->description = MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_DESCRIPTION; // Description of Module in Admin
      $this->enabled = ((MODULE_PAYMENT_AUTHORIZENET_AIM_STATUS == 'True') ? true : false); // If the module is installed or not
      $this->sort_order = MODULE_PAYMENT_AUTHORIZENET_AIM_SORT_ORDER; // Sort Order of this payment option on the checkout_payment.php page
      $this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true); // checkout_process.php - page to go to on completion

	  if ((int)MODULE_PAYMENT_AUTHORIZENET_AIM_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_AUTHORIZENET_AIM_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

	}


// class methods

    function update_status() {
      global $order, $db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_AUTHORIZENET_AIM_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_AUTHORIZENET_AIM_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

    // Validate the credit card information via javascript (Number, Owner, and CVV Lengths)
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.authorizenet_aim_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet_aim_cc_number.value;' . "\n";
      if (MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV == 'True')  {
	    $js .= '    var cc_cvv = document.checkout_payment.authorizenet_aim_cc_cvv.value;' . "\n";
	  }
      $js .= '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
             '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_JS_CC_OWNER . '";' . "\n" .
             '      error = 1;' . "\n" .
             '    }' . "\n" .
             '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
             '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_JS_CC_NUMBER . '";' . "\n" .
             '      error = 1;' . "\n" .
             '    }' . "\n";
      if (MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV == 'True')  {
		$js .= '    if (cc_cvv == "" || cc_cvv.length < "3" || cc_cvv.length > "4") {' . "\n".
               '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_JS_CC_CVV . '";' . "\n" .
               '      error = 1;' . "\n" .
               '    }' . "\n" .
               '  }' . "\n";
      }

      return $js;
    }

    // Display Credit Card information on the checkout_payment.php page
    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
      if (MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV == 'True') {
	  $selection = array('id' => $this->code,
                         'module' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CATALOG_TITLE,
						 'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('authorizenet_aim_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('authorizenet_aim_cc_number')),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('authorizenet_aim_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet_aim_cc_expires_year', $expires_year)),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CVV,
                                                 'field' => tep_draw_input_field('authorizenet_aim_cc_cvv','',"size=4, maxlength=4"))));
      } else {
	  $selection = array('id' => $this->code,
                         'module' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CATALOG_TITLE,
						 'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('authorizenet_aim_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('authorizenet_aim_cc_number')),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('authorizenet_aim_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet_aim_cc_expires_year', $expires_year))));
      }
      return $selection;
    }


    // Evaluates the Credit Card Type for acceptance and validity of the Credit Card Number and Expiry Date
    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['authorizenet_aim_cc_number'], $_POST['authorizenet_aim_cc_expires_month'], $_POST['authorizenet_aim_cc_expires_year'], $_POST['authorizenet_aim_cc_cvv']);
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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet_aim_cc_owner=' . urlencode($_POST['authorizenet_aim_cc_owner']) . '&authorizenet_aim_cc_expires_month=' . $_POST['authorizenet_aim_cc_expires_month'] . '&authorizenet_aim_cc_expires_year=' . $_POST['authorizenet_aim_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    // Display Credit Card Information on the Checkout Confirmation Page
	function confirmation() {
      global $_POST;

	  if (MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV == 'True') {
	  $confirmation = array(//'title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CATALOG_TITLE, // Redundant
							'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_TYPE,
                                                    'field' => $this->cc_card_type),
							                  array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['authorizenet_aim_cc_owner']),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_NUMBER,
													'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['authorizenet_aim_cc_expires_month'], 1, '20' . $_POST['authorizenet_aim_cc_expires_year']))),
											  array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CVV,
                                                    'field' => $_POST['authorizenet_aim_cc_cvv'])));
      } else {
	  $confirmation = array(//'title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CATALOG_TITLE, // Redundant
							'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_TYPE,
                                                    'field' => $this->cc_card_type),
							                  array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['authorizenet_aim_cc_owner']),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['authorizenet_aim_cc_expires_month'], 1, '20' . $_POST['authorizenet_aim_cc_expires_year'])))));
      }

      return $confirmation;
    }

    function process_button() {
      // Hidden fields on the checkout confirmation page
	  $process_button_string = tep_draw_hidden_field('cc_owner', $_POST['authorizenet_aim_cc_owner']) .
                               tep_draw_hidden_field('cc_expires', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               tep_draw_hidden_field('cc_type', $this->cc_card_type) .
                               tep_draw_hidden_field('cc_number', $this->cc_card_number);
      if (MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV == 'True') {
        $process_button_string .= tep_draw_hidden_field('cc_cvv', $_POST['authorizenet_aim_cc_cvv']);
      }

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
	  return false;
    }

    function before_process() {
	  global $_POST, $response, $db, $order;

	  // DATA PREPARATION SECTION
      unset($submit_data);  // Cleans out any previous data stored in the variable

	  // Create a string that contains a listing of products ordered for the description field
	  $description = '';
	  for ($i=0; $i<sizeof($order->products); $i++) {
	    $description .= $order->products[$i]['name'] . '(qty: ' . $order->products[$i]['qty'] . ') + ';
	  }
	  // Strip the last "\n" from the string
	  $description = substr($description, 0, -2);

	  // Create a variable that holds the order time
	  $order_time = date("F j, Y, g:i a");

	  // Calculate the next expected order id
	  $new_order_id_qry = tep_db_query("show table status like '" . TABLE_ORDERS . "'");
          $new_order_id_result = tep_db_fetch_array($new_order_id_qry);
          $new_order_id = $new_order_id_result['Auto_increment'];

	  // Populate an array that contains all of the data to be submitted
	  $submit_data = array(
	  x_login => MODULE_PAYMENT_AUTHORIZENET_AIM_LOGIN, // The login name as assigned to you by authorize.net
	  x_tran_key => MODULE_PAYMENT_AUTHORIZENET_AIM_TXNKEY,  // The Transaction Key (16 digits) is generated through the merchant interface
	  x_relay_response => 'FALSE', // AIM uses direct response, not relay response
	  x_delim_data => 'TRUE', // The default delimiter is a comma
	  x_version => '3.1',  // 3.1 is required to use CVV codes
	  x_type => MODULE_PAYMENT_AUTHORIZENET_AIM_AUTHORIZATION_TYPE == 'Authorize' ? 'AUTH_ONLY': 'AUTH_CAPTURE',
	  x_method => 'CC', //MODULE_PAYMENT_AUTHORIZENET_AIM_METHOD == 'Credit Card' ? 'CC' : 'ECHECK',
	  x_amount => number_format($order->info['total'], 2),
	  x_card_num => $_POST['cc_number'],
	  x_exp_date => $_POST['cc_expires'],
	  x_card_code => $_POST['cc_cvv'] + 0, // CVV doesn't correctly get passed w/o explicit conversion
	  x_email_customer => MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_CUSTOMER == 'True' ? 'TRUE': 'FALSE',
	  x_email_merchant => MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_MERCHANT == 'True' ? 'TRUE': 'FALSE',
	  x_cust_id => $_SESSION['customer_id'],
	  x_customer_ip => $_SERVER['REMOTE_ADDR'],
	  x_invoice_num => $new_order_id,
	  x_first_name => $order->billing['firstname'],
	  x_last_name => $order->billing['lastname'],
	  x_company => $order->billing['company'],
	  x_address => $order->billing['street_address'],
	  x_city => $order->billing['city'],
	  x_state => $order->billing['state'],
	  x_zip => $order->billing['postcode'],
	  x_country => $order->billing['country']['title'],
	  x_phone => $order->customer['telephone'],
	  x_email => $order->customer['email_address'],
	  x_ship_to_first_name => $order->delivery['firstname'],
	  x_ship_to_last_name => $order->delivery['lastname'],
	  x_ship_to_address => $order->delivery['street_address'],
	  x_ship_to_city => $order->delivery['city'],
	  x_ship_to_state => $order->delivery['state'],
	  x_ship_to_zip => $order->delivery['postcode'],
	  x_ship_to_country => $order->delivery['country']['title'],
	  x_description => $description,
	  // Merchant defined variables go here
	  Date => $order_time,
	  IP => $_SERVER['REMOTE_ADDR'],
	  Session => tep_session_id());

	  if(MODULE_PAYMENT_AUTHORIZENET_AIM_TESTMODE == 'Test') {
	    $submit_data['x_test_request'] = 'TRUE';
	  } else {

	  $submit_data['x_test_request'] = 'FALSE';

      }

	  // concatenate the submission data and put into variable $data
	  while(list($key, $value) = each($submit_data)) {
	    $data .= $key . '=' . urlencode(preg_replace('/,/', '', $value)) . '&';
	  }

	  // Remove the last "&" from the string
	  $data = substr($data, 0, -1);


	  // Post order info data to Authorize.net
	  // cURL must be compiled into PHP
	  // Connection must be https
	  // Test or Live Server address set
	  // using 'Test' or 'Live' mode in
	  // osCommerce admin panel


	  unset($response);

	  if (MODULE_PAYMENT_AUTHORIZENET_AIM_TESTMODE == 'Test') {
      $url = 'https://secure.authorize.net/gateway/transact.dll'; // If this does not work then change 'secure' to 'certification' on this line
      } else {
      $url = 'https://secure.authorize.net/gateway/transact.dll';
      }

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL,$url);

      curl_setopt($ch, CURLOPT_VERBOSE, 0);

      curl_setopt($ch, CURLOPT_POST, 1);

      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	if(MODULE_PAYMENT_AUTHORIZENET_AIM_CURL_PROXY != 'none') {
          curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
          curl_setopt ($ch, CURLOPT_PROXY,MODULE_PAYMENT_AUTHORIZENET_AIM_CURL_PROXY);
	}

      $authorize = curl_exec($ch);

      curl_close ($ch);

      $response = split('\,', $authorize);

	  // Parse the response code and text for custom error display
	  $response_code = explode(',', $response[0]);
	  $response_text = explode(',', $response[3]);
	  $x_response_code = $response_code[0];
	  $x_response_text = $response_text[0];
      // If the response code is not 1 (approved) then redirect back to the payment page with the appropriate error message
	  if ($x_response_code != '1') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $x_response_text . ' - ' . urlencode(MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_DECLINED_MESSAGE), 'SSL', true, false));
      }
    }

    function after_process() {
	  return false;
    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_AUTHORIZENET_AIM_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {

      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_AIM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Authorize.net AIM Module', 'MODULE_PAYMENT_AUTHORIZENET_AIM_STATUS', 'True', 'Do you want to accept Authorize.net payments via the AIM Method?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET_AIM_LOGIN', 'Your User Name', 'The login username used for the Authorize.net service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_AIM_TXNKEY', '16 digit key', 'Transaction Key used for encrypting TP data', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_AIM_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Live\'), ', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Authorization Type', 'MODULE_PAYMENT_AUTHORIZENET_AIM_AUTHORIZATION_TYPE', 'Authorize/Capture', 'Do you want submitted credit card transactions to be authorized only, or authorized and captured?', '6', '0', 'tep_cfg_select_option(array(\'Authorize\', \'Authorize/Capture\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Merchant Notifications', 'MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_MERCHANT', 'True', 'Should Authorize.Net e-mail a receipt to the merchant?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Request CVV Number', 'MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV', 'True', 'Do you want to ask the customer for the card\'s CVV number', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_AUTHORIZENET_AIM_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_AUTHORIZENET_AIM_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_AUTHORIZENET_AIM_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CURL Proxy URL', 'MODULE_PAYMENT_AUTHORIZENET_AIM_CURL_PROXY', 'none', 'CURL Proxy URL.  Some hosting providers require you to use their CURL Proxy.  Enter the full URL here.  If Not necessary, use - none', '6', '0', now())");
    }

    function remove() {

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_AUTHORIZENET_AIM_STATUS', 'MODULE_PAYMENT_AUTHORIZENET_AIM_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_AIM_TXNKEY', 'MODULE_PAYMENT_AUTHORIZENET_AIM_TESTMODE', 'MODULE_PAYMENT_AUTHORIZENET_AIM_AUTHORIZATION_TYPE', 'MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_CUSTOMER', 'MODULE_PAYMENT_AUTHORIZENET_AIM_EMAIL_MERCHANT', 'MODULE_PAYMENT_AUTHORIZENET_AIM_USE_CVV', 'MODULE_PAYMENT_AUTHORIZENET_AIM_SORT_ORDER', 'MODULE_PAYMENT_AUTHORIZENET_AIM_ZONE', 'MODULE_PAYMENT_AUTHORIZENET_AIM_ORDER_STATUS_ID', 'MODULE_PAYMENT_AUTHORIZENET_AIM_CURL_PROXY'); //'MODULE_PAYMENT_AUTHORIZENET_AIM_METHOD'
    }
  }
?>