<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// | Net1 Payment Services Payment Module          			              |
// | http://www.eftsecure.com/default.aspx         			              |
// | 									          			              |
// | includes/modules/payment/net1.php            			              |
// | Released under GPL   												  |
// | Designed by Jeramie Risinger, gapc23, Bean Fairbanks 				  |
// | Version 1.0 May 25, 2005								              |
// +----------------------------------------------------------------------+
//$Id: net1.php 1 2005-05-25 12:00:00Z jersbox gapc23 beanfair$

  class net1 {
    var $code, $title, $description, $enabled;

// class constructor
    function net1() {
      global $order;

      $this->code = 'net1';
     if ($_GET['main_page'] != '') {
      $this->title = MODULE_PAYMENT_NET1_TEXT_CATALOG_TITLE; // Payment module title in Catalog
     } else {
      $this->title = MODULE_PAYMENT_NET1_TEXT_ADMIN_TITLE; // Payment module title in Admin
     }
      $this->description = MODULE_PAYMENT_NET1_TEXT_DESCRIPTION; //Payment module description Admin
      $this->enabled = ((MODULE_PAYMENT_NET1_STATUS == 'True') ? true : false);
      $this->sort_order = MODULE_PAYMENT_NET1_SORT_ORDER;
      	  
	  $this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false); // Defined in filenames.php 
      
	  if ((int)MODULE_PAYMENT_NET1_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_NET1_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

	}


// class methods

    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_NET1_ZONE > 0) ) {
        $check_flag = false;
        $check = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_NET1_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($rec = tep_db_fetch_array($query)) {
          if ($rec['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($rec['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    /* Validate the credit card information structure (name, number format and CVV) via javascript.  
	This validates format only it does NOT validate credit card viabilty. */
 function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.net1_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.net1_cc_number.value;' . "\n" .
                        '         var cc_cvv = document.checkout_payment.net1_cc_cvv.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_NET1_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_NET1_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
                        '         if (cc_cvv == "" || cc_cvv.length < "3") {' . "\n".
                        '           error_message = error_message + "' . MODULE_PAYMENT_NET1_TEXT_JS_CC_CVV . '";' . "\n" .
                        '           error = 1;' . "\n" .
                        '         }' . "\n" .
            '  }' . "\n";

      return $js;
    }// end javascript_validation 

    // Display Credit Card Information Submission Fields on the Checkout Payment Page
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
                         'module' => MODULE_PAYMENT_NET1_TEXT_CATALOG_TITLE,
                         'fields' => array(array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('net1_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('net1_cc_number')),
                                           array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('net1_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('net1_cc_expires_year', $expires_year)),
                                           array('title' => MODULE_PAYMENT_NET1_TEXT_CVV2_NUMBER,
                                                 'field' => tep_draw_input_field('net1_cc_cvv','','SIZE=4, MAXLENGTH=4'))));

      return $selection;
    }//end selection
	
//Reports the appropriate error to the user if it doesn't pass the format validation
    function pre_confirmation_check() {
      global $_POST;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['net1_cc_number'], $_POST['net1_cc_expires_month'], $_POST['net1_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&net1_cc_owner_firstname=' . urlencode($_POST['net1_cc_owner_firstname']) . '&net1_cc_owner_lastname=' . urlencode($_POST['net1_cc_owner_lastname']) . '&net1_cc_expires_month=' . $_POST['net1_cc_expires_month'] . '&net1_cc_expires_year=' . $_POST['net1_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }//end pre_confirmation_check

//Creates an array of CC information to post to the confirmation page
    function confirmation() {
      global $_POST;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['net1_cc_owner']),
                                              array('title' => MODULE_PAYMENT_NET1_TEXT_CVV2_NUMBER,
                                                    'field' => $_POST['net1_cc_cvv']),
                                              array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['net1_cc_expires_month'], 1, '20' . $_POST['net1_cc_expires_year'])))));

      return $confirmation;
    } //end confirmation

//Sets the order number and hidden fields
    function process_button() {
      global $_POST, $order;
		 //assigns an orderID 
		 $query = tep_db_query("select * from " . TABLE_ORDERS . " order by orders_id desc limit 1");
	  	$last_order_id = tep_db_fetch_array($query);
	  	$new_order_id = $last_order_id['orders_id'];
	  	$new_order_id = ($new_order_id + 1);
	  
		
      	$process_button_string =
                               tep_draw_hidden_field('M_id', MODULE_PAYMENT_NET1_MERCHANT_ID) .
                               tep_draw_hidden_field('M_key', MODULE_PAYMENT_NET1_MERCHANT_KEY) .
                               tep_draw_hidden_field('T_amt', number_format($order->info['total'], 2)) .
                               tep_draw_hidden_field('T_ordernum', $new_order_id) .
                               tep_draw_hidden_field('C_cardnumber', $this->cc_card_number) .
                               tep_draw_hidden_field('C_exp', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               tep_draw_hidden_field('C_name', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
                               tep_draw_hidden_field('C_address', $order->billing['street_address']) .
                               tep_draw_hidden_field('C_city', $order->billing['city']) .
                               tep_draw_hidden_field('C_state', $order->billing['state']) .
                               tep_draw_hidden_field('C_zip', $order->billing['postcode']) .
                               tep_draw_hidden_field('C_email', $order->billing['email']) . 
                               tep_draw_hidden_field('C_cvv', $_POST['net1_cc_cvv']) .
                               tep_draw_hidden_field('T_code', "02");
		return $process_button_string;


    }//end process_button
	
    function before_process() {
      	global $_POST;
					  	
	  	//Net1's posting site for golden oldies and new hits...
	 	$eftsecure_url = 'https://va.eftsecure.net/cgi-bin/eftBankcard.dll?transaction';
	 	
		//should be set from the admin panel
	  	$data = "M_id=" . MODULE_PAYMENT_NET1_MERCHANT_ID;  //merchant id
		$data .= "&M_key=" . MODULE_PAYMENT_NET1_MERCHANT_KEY;  //merchant key

		/*Encode data to be sent to Net1.
		This stuff was grabbed from the earlier creation of
		the process_button_string (those little hidden input fields)
		All customer data should be encoded for security*/
		$data .= "&T_amt=" . urlencode( $_POST['T_amt'] );
		$data .= "&C_name=" . urlencode( $_POST['C_name'] );
		$data .= "&C_address=" . urlencode( $_POST['C_address'] );
		$data .= "&C_city=" . urlencode( $_POST['C_city'] );
		$data .= "&C_state=" . urlencode( $_POST['C_state'] );
		$data .= "&C_zip=" . urlencode( $_POST['C_zip'] );
		$data .= "&C_email=" . urlencode( $_POST['C_email'] );
		$data .= "&C_cardnumber=" . urlencode( $_POST['C_cardnumber'] );
		$data .= "&C_exp=" . urlencode( $_POST['C_exp'] );
		$data .= "&T_code=02";  //transaction type indicator
	
		//curl procedures
		$ch = curl_init(); //initialize the CURL library. 
		curl_setopt($ch, CURLOPT_URL, $eftsecure_url); // set the URL to post to. 
		curl_setopt($ch, CURLOPT_POST, 1); // tell it to POST not GET (you can GET but POST is //preferred)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // set the data to be posted. 
		/* this tells the library to return the 
		 data to you instead of writing it to a file */
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$res = curl_exec($ch); // make the post. 
		curl_close($ch); // shut down the curl library. 
		
	  	/*Simple check.  if the resp is not 'A' then fail and redirect.  
		Otherwise the default action is the	success page */
	  	if ( $res[1] != 'A' ) {
					if(stristr($res,'decline')){
						$error_msg = 'Card was Declined';
					}else{
						$error_msg = $res;
					}
             		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_NET1_TEXT_ERROR_MESSAGE.'<br>'.$error_msg), 'SSL', true, false));
	  	  	}
	        
    }// end before_process
	
    function after_process() {
     
	 return false;
	 
    }//end after_process

    //Grabs the text version of the error to create a user friendly error page
	function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_NET1_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }//end get_error

	//Checks the status of the module as set in the admin panel
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NET1_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }//end check
    
	// Installs the configuration for Net1 into the payments module in Admin
	function install() {
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable NetOne Module', 'MODULE_PAYMENT_NET1_STATUS', 'True', 'Do you want to accept Net1 payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_NET1_MERCHANT_ID', '', 'Merchant ID used for the Net1 service', '6', '2', now())");
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Key', 'MODULE_PAYMENT_NET1_MERCHANT_KEY', '', 'Merchant Key used for the Net1 service', '6', '3', now())");
     	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_NET1_TRANSACTION_TYPE', 'PreAuth', 'Transaction type to use for the Net1 service', '6', '4', 'tep_cfg_select_option(array(\'Sale\', \'AuthOnly\', \'Force\', \'Void\', \'Credit\'), ', now())");
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_NET1_CURRENCY', 'USD', 'The currency to use for credit card transactions', '6', '6', 'tep_cfg_select_option(array(\'CAD\', \'USD\'), ', now())");
     	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_NET1_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
     	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_NET1_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
     	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_NET1_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
   }//end install

	//Removes the configuration information from the Admin panel.  It does NOT remove the module
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    //Keys set in the Admin Panel	
	function keys() {
     return array('MODULE_PAYMENT_NET1_STATUS', 
	 			  'MODULE_PAYMENT_NET1_MERCHANT_ID', 
				  'MODULE_PAYMENT_NET1_MERCHANT_KEY', 
				  'MODULE_PAYMENT_NET1_TRANSACTION_TYPE', 
				  'MODULE_PAYMENT_NET1_CURRENCY', 
				  'MODULE_PAYMENT_NET1_ZONE', 
				  'MODULE_PAYMENT_NET1_ORDER_STATUS_ID', 
				  'MODULE_PAYMENT_NET1_SORT_ORDER');
   }//end keys
	
  }
?>