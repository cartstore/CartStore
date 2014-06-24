<?php
/*
  $Id: innovative.php,v 2.4.0 2005/05/25 12:58:00 willross Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  This module connects osCommerce v2.22.0(November 2003 or later) - 2.3
  to IMS/Innovative Merchant & Gateway Solutions:

// ********************************************************************************************************
// ********************************************************************************************************
// ********************************************************************************************************
// ***************************** NOTE TO DEVELOPERS *******************************************
// ********************************************************************************************************
// ********************************************************************************************************
// ********************************************************************************************************
//
//	This code is provided by IGS as an example only.  It is intended to show how to
//	call the IGS platform.  This code is not intended to be
//	used for business applications without modification.  IGS makes not warranty to the
//	quality of this example for performing all known tasks.  Developers are expected to
//	read all documentation and user guides before performing testing and implementation.
//
//	The code contained in this example may be freely reused so long as this warning and
//	other header information in this file are not changed and are not removed.
//
//	By using this code the developers is accepting the responsibility of making sure
//	all error conditions and handling of said error conditions is accounted for.
//
//	The API code called by this example code can not be modified and / or redistributed
//	by any party without written permission from IGS.
//
//	Examples supplied by IGS programming support are version specific and may or
// 	may not work with future or past versions of the API.
//
// ********************************************************************************************************
// ********************************************************************************************************
// ********************************************************************************************************
// ********************************************************************************************************


*/

  class innovative {
    var $code, $title, $description, $enabled;

// class constructor
    function innovative() {
      $this->code = 'innovative';
      $this->title = MODULE_PAYMENT_INNOVATIVE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_INNOVATIVE_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_INNOVATIVE_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_INNOVATIVE_STATUS == 'True') ? true : false);
    }

   // this function connects to Gateway and reads the result
   function GetGatewayResult($fulltotal, $ordernumber,
                            $ccname, $baddress, $bcity,
                            $bstate, $bzip, $bcountry,
                            $bphone, $email, $trantype,
                            $username, $password, $ccidentifier1, $ccnumber, $month, $year)
   {
      // build the data string that contains the
      // credit card info and customer data

      $data  = "target_app=WebCharge_v5.06&";
      $data .= "fulltotal=$fulltotal&";
      $data .= "ordernumber=$ordernumber&";
      $data .= "ccname=$ccname&";
      $data .= "baddress=$baddress&";
      $data .= "bcity=$bcity&";
      $data .= "bstate=$bstate&";
      $data .= "bzip=$bzip&";
      $data .= "bcountry=$bcountry&";
      $data .= "bphone=$bphone&";
      $data .= "email=$email&";
      $data .= "trantype=$trantype&";
      $data .= "response_mode=simple&";
      $data .= "username=$username&";
      $data .= "pw=$password&";
      $data .= "ccidentifier1=$ccidentifier1&";
      $data .= "ccnumber=$ccnumber&";
      $data .= "month=$month&";
      $data .= "year=$year&";
      $data .= "connection_method=POST&";
      $data .= "delimited_fmt_field_delimiter==&";
      $data .= "delimited_fmt_include_fields=true&";
      $data .= "delimited_fmt_value_delimiter=|&";
      $data .= "delimitedresponse=Y&";
      $data .= "include_extra_field_in_response=N&";
      $data .= "last_used_response_num=5&";
      $data .= "response_fmt=delimited&";
      $data .= "upg_auth=zxcvlkjh&";
      $data .= "merch_ip=$REMOTE_ADDR&";
      $data .= "upg_version=version&";
      // if ($username == 'gatewaytest') $data .= "test_override_errors=y&"; // We are testing
      $data .= "yes=Y";


      // replace all whitespace with a plus sign for the query string
      $data = preg_replace("/ /", "+", $data);

      // post the data
      $cmd = MODULE_PAYMENT_INNOVATIVE_CURL . " -d \"$data\" " . MODULE_PAYMENT_INNOVATIVE_URL;
      // echo "curl command<br>" . $cmd . "<br>";
      exec($cmd, $return_string);

      // Set up default error condition
      $card_status[0] = "error";
      $card_status[1] = "Error accessing gateway";

      // split up the results into name=value pairs
      $tmp = explode("|", $return_string[0]);
      for($i=0;$i<count($tmp);$i++) {
         $tmp2 = explode("=", $tmp[$i]);
    // echo $tmp2[0] . " = " . $tmp2[1] . "<br>";
         if ($tmp2[0] == "approval") {
           $card_status[0] = "approved";
           $card_status[1] = $tmp2[1];
         } elseif ($tmp2[0] == "error") {
           $card_status[0] = "error";
           $card_status[1] = $tmp2[1];
         }
      }
      return $card_status;
   }

// class methods
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.innovative_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.innovative_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_INNOVATIVE_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_INNOVATIVE_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

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

	$good_cards[] = array('id' => '0', 'text' => 'Visa');
	$good_cards[] = array('id' => '1', 'text' => 'Master Card');
	$good_cards[] = array('id' => '2', 'text' => 'American Express');
	$good_cards[] = array('id' => '3', 'text' => 'Discover');

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('innovative_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => 'Card Type:',
                                                 'field' => tep_draw_pull_down_menu('cc_ignore_type', $good_cards)),
                                           array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('innovative_cc_number')),
  										   array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CVV2,
                                                 'field' => tep_draw_input_field('ccidentifier1')),
                                           array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('innovative_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('innovative_cc_expires_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['innovative_cc_number'], $_POST['innovative_cc_expires_month'], $_POST['innovative_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&innovative_cc_owner=' . urlencode($_POST['innovative_cc_owner']) . '&innovative_cc_expires_month=' . $_POST['innovative_cc_expires_month'] . '&innovative_cc_expires_year=' . $_POST['innovative_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $_POST;

// echo "complete " . $this->cc_card_number . "X" . $this->cc_expiry_month . "/" . $this->cc_expiry_year;
      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['innovative_cc_owner']),
											  array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CVV2,
                                                    'field' => $_POST['ccidentifier1']),
                                              array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['innovative_cc_expires_month'], 1, '20' . $_POST['innovative_cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $_POST;

      $process_button_string = tep_draw_hidden_field('cc_owner', $_POST['innovative_cc_owner']) .
                               tep_draw_hidden_field('cvv', $_POST['ccidentifier1']) .
                               tep_draw_hidden_field('cc_number', $this->cc_card_number) .
                               tep_draw_hidden_field('cc_month', $this->cc_expiry_month) .
                               tep_draw_hidden_field('cc_year', $this->cc_expiry_year);

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }

    function before_process() {
      global $_POST, $order;

// echo "complete " . $this->cc_card_number . "X" . $this->cc_expiry_month . "/" . $this->cc_expiry_year;
      $result = $this->GetGatewayResult(number_format($order->info['total'], 2, '.', ''), 0, $_POST['cc_owner'],
                                 $order->billing['street_address'], $order->billing['city'], $order->billing['state'],
                                 $order->billing['postcode'], $order->billing['country'],
                                 $order->customer['telephone'], $order->customer['email_address'],
                                 'sale', MODULE_PAYMENT_INNOVATIVE_USERID, MODULE_PAYMENT_INNOVATIVE_PASSWORD,
                                 $_POST['cvv'], $_POST['cc_number'], $_POST['cc_month'], $_POST['cc_year']);

// echo "Result = " . $result[0] . " " . strip_tags($result[1]);
      if ($result[0] <> "approved") {
        $message = sprintf(MODULE_PAYMENT_INNOVATIVE_TEXT_ERROR_MESSAGE, strip_tags($result[1]));
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($message), 'SSL', true, false));
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_INNOVATIVE_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_INNOVATIVE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Innovative Module', 'MODULE_PAYMENT_INNOVATIVE_STATUS', 'True', 'Do you want to accept Innovative payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Userid on Gateway', 'MODULE_PAYMENT_INNOVATIVE_USERID', 'gatewaytest', 'Contact payment processor to get a userid', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password on Gateway', 'MODULE_PAYMENT_INNOVATIVE_PASSWORD', 'GateTest2002', 'Contact payment processor to get a password', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Payment Server', 'MODULE_PAYMENT_INNOVATIVE_URL', 'http://transactions.innovativegateway.com/servlet/com.gateway.aai.Aai', 'URL of the payment processor', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Path to cURL', 'MODULE_PAYMENT_INNOVATIVE_CURL', '/usr/local/bin/curl', 'Path to the curl command on the serves', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PAYMENT_INNOVATIVE_SORT_ORDER', '0', 'Sort Order of this method', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_INNOVATIVE_STATUS', 'MODULE_PAYMENT_INNOVATIVE_USERID', 'MODULE_PAYMENT_INNOVATIVE_PASSWORD', 'MODULE_PAYMENT_INNOVATIVE_URL', 'MODULE_PAYMENT_INNOVATIVE_CURL', 'MODULE_PAYMENT_INNOVATIVE_SORT_ORDER');
    }
  }
?>
