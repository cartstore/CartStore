<?php
/*
$Id: quantumqgwdbe.php,v 1.40 2007/06/13 18:23:14 dgw_ Exp $

Released under the GNU General Public License

quantumqgwdbe.php  was developed for QuantumGateway

https://www.quantumgateway.com

by

Jerry Brown
Project Development
CDGcommerce
*/

class quantumqgwdbe {
	var $code, $title, $description, $enabled;
	
	// class constructor
	function quantumqgwdbe () {
	global $order;
		$this->code = 'quantumqgwdbe';
		$this->title = MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_TITLE;
		$this->public_title = MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_PUBLIC_TITLE;
		$this->description = MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_DESCRIPTION;
		$this->enabled = ((MODULE_PAYMENT_QUANTUMQGWDBE_STATUS == 'True') ?
		true : false);
		$this->sort_order = MODULE_PAYMENT_QUANTUMQGWDBE_SORT_ORDER;
		


      if ((int)MODULE_PAYMENT_QUANTUMQGWDBE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_QUANTUMQGWDBE_ORDER_STATUS_ID;
      }
		#################################################
		
		#################################################
		$this->quantum_gets_cc = ((MODULE_PAYMENT_QUANTUMQGWDBE_CC == 'True') ? true : false);
		$this->uses_cvv = ((MODULE_PAYMENT_QUANTUMQGWDBE_USESCVV == 'Y') ? true : false);
		$this->logo_url = MODULE_PAYMENT_QUANTUMQGWDBE_LOGO_URL;
		$this->bck_color = MODULE_PAYMENT_QUANTUMQGWDBE_BCK_COLOR;
		$this->text_color = MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_COLOR;
		$this->form_action_url = 'https://secure.quantumgateway.com/cgi/qgwdbe.php';
		$this->cvvtype[] = array('id' => '2', 'text' => 'CVV on card is unreadable');
		$this->cvvtype[] = array('id' => '9', 'text' => 'Card does not have CVV imprint');
		
		if (is_object($order)) $this->update_status();
	}
	
   function update_status() {
      global $order;


     }



	// class methods
	function javascript_validation() {
	
		// if quatnumgateway is collecting the CC#, then CreLoaded does not
		// allow the user to enter CC#, therefore we do not need to validate it
		
		if (!$this->quantum_gets_cc)
		{
			$js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
			'    var cc_owner = document.checkout_payment.quatnumgateway_cc_owner.value;' . "\n" .
			'    var cc_number = document.checkout_payment.quantumqgwdbe_cc_number.value;' . "\n" .
			'    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
			'      error_message = error_message + "' . MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_JS_CC_OWNER . '";' . "\n" .
			'      error = 1;' . "\n" .
			'    }' . "\n" .
			'    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
			'      error_message = error_message + "' . MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_JS_CC_NUMBER . '";' . "\n" .
			'      error = 1;' . "\n" .
			'    }' . "\n" .
			'  }' . "\n";
		}
		else
		{
			$js = '';
		}
		
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
		
		// Depending on whether CreLoaded is collecting the CC# or not.  If
		// CreLoaded is collecting then we must allow the user to enter it,
		// here we output the form fields to collect the cc#
		
		if (!$this->quantum_gets_cc)
		{
			$selection = array('id' => $this->code,
			'module' => $this->public_title,
			'fields' => array(
			array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_OWNER,
			'field' => tep_draw_input_field('quantumqgwdbe_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
			array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_NUMBER,
			'field' => tep_draw_input_field('quantumqgwdbe_cc_number')),
			array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_EXPIRES,
			'field' => tep_draw_pull_down_menu('quantumqgwdbe_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('quantumqgwdbe_cc_expires_year', $expires_year))			
			));
			if ($this->uses_cvv) {
				array_push($selection['fields'], array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_CVV,
			'field' => tep_draw_input_field('quantumqgwdbe_cvv','',"SIZE=4, MAXLENGTH=4") . ' ' .'<a href="cvv.html" target="_blank">' . '<u><i>' . '(What is CVV?)' . '</i></u></a>',
			'field' => tep_draw_input_field('quantumqgwdbe_cvv','',"SIZE=4, MAXLENGTH=4")),
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_NOT_CVV,
				'field' => tep_draw_checkbox_field('quantumqgwdbe_notcvv', '1')),
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_REASON_NOT_CVV,
				'field' => tep_draw_pull_down_menu('quantumqgwdbe_cvvtype', $this->cvvtype, '2'))
				);
			}
		}
		else
		{
			$selection = array('id' => $this->code,
			'module' => $this->title,
			'fields' => array(array('title' => 'Continue',
			'field' => tep_draw_hidden_field('quantumqgwdbe_cc_owner', ''))));
		}
		
		return $selection;
	}
	
	function pre_confirmation_check() {
		global $_REQUEST;
		
		// We don't confirm if CreLoaded is not collecting the CC#
		
		if (!$this->quantum_gets_cc) {
   	      $error = '';
		  if(trim($_REQUEST['quantumqgwdbe_cc_number']) == '') {
			$error = MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_MISSING_CC_NUMBER;
		  }
		  else {
			include(DIR_WS_CLASSES . 'cc_validation.php');
			
			$cc_validation = new cc_validation();
			$result = $cc_validation->validate($_REQUEST['quantumqgwdbe_cc_number'], $_REQUEST['quantumqgwdbe_cc_expires_month'], $_REQUEST['quantumqgwdbe_cc_expires_year']);
			
			switch ((int)$result) {
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
		  }
		  
		  if($error != '') {
			  // For compatability with security modules that scrub most punctuation characters from the
                          // query string, use a $_SESSION var instead of a query string parameter to pass the error 
			  // message to the next page.
			  $payment_error_return =
				// Note that $this->code is the ID of the payment module
				'payment_error=' . $this->code . // '&error=' . urlencode($error) . 
				'&quantumqgwdbe_cc_owner=' . urlencode($_REQUEST['quantumqgwdbe_cc_owner']) .
				'&quantumqgwdbe_cc_expires_month=' . urlencode($_REQUEST['quantumqgwdbe_cc_expires_month']) .
				'&quantumqgwdbe_cc_expires_year=' . urlencode($_REQUEST['quantumqgwdbe_cc_expires_year']);
			  
			  tep_session_register($this->code . '_payment_error');
			  $GLOBALS[$this->code . '_payment_error'] = $error;
			  
			  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		  }
		  
		  $this->cc_card_type = $cc_validation->cc_type;
		  $this->cc_card_number = $cc_validation->cc_number;
		  $this->cc_expiry_month = $cc_validation->cc_expiry_month;
		  $this->cc_expiry_year = $cc_validation->cc_expiry_year;
		  $x_Card_Code = $_REQUEST['quantumqgwdbe_cvv'];
		}
	}
	
	function confirmation() {
		global $_REQUEST;
		
		// don't confirm if CreLoaded is not collecting the CC#
		
		if (!$this->quantum_gets_cc)
		{
			$x_Card_Code=$_REQUEST['quantumqgwdbe_cvv'];
			$confirmation = array(
			  'title' => $this->cc_card_type . ' ' . $this->public_title,
			  'fields' => array(
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_OWNER,
					  'field' => $_REQUEST['quantumqgwdbe_cc_owner']),
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_NUMBER,
                                          // Show only the last 4 digits of the card number
					  'field' => str_repeat('X', (strlen($this->cc_card_number) - 4)) . substr($this->cc_card_number, -4)),
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_EXPIRES,
					  'field' => strftime('%B, %Y', mktime(0,0,0,$_REQUEST['quantumqgwdbe_cc_expires_month'], 1, '20' . $_REQUEST['quantumqgwdbe_cc_expires_year']))),
				array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_CVV,
					  'field' => $_REQUEST['quantumqgwdbe_cvv'])
			  )
			);
		}
		else
		{
			$confirmation = '';
		}
		
		return $confirmation;
	}
	
	function process_button() {
		global $_REQUEST, $order, $customer_id;
		
		$process_button_string =
		tep_draw_hidden_field ('gwlogin',
		MODULE_PAYMENT_QUANTUMQGWDBE_LOGIN);
		//tep_draw_hidden_field ('Redirect', '0');
		
		// if CreLoaded collected the CC# then send it on to eProc
		
		if (!$this->quantum_gets_cc)
		{
			if ($this->uses_cvv) 
			{
				if ((isset($_REQUEST['quantumqgwdbe_notcvv'])) && ($_REQUEST['quantumqgwdbe_notcvv'] == "1")) {
					if (($_REQUEST['quantumqgwdbe_cvvtype'] != "2") && ($_REQUEST['quantumqgwdbe_cvvtype'] != "9")) 
					{
						$process_button_string .= tep_draw_hidden_field('CVV2', $_REQUEST['quantumqgwdbe_cvv']) .
						tep_draw_hidden_field('CVVtype', '1');
					} 
					else 
					{
						$process_button_string .= tep_draw_hidden_field('CVVtype', $_REQUEST['quantumqgwdbe_cvvtype']);
					}
				} 
				else 
				{
					$process_button_string .= tep_draw_hidden_field('CVVtype', '1') . 
					tep_draw_hidden_field('CVV2', $_REQUEST['quantumqgwdbe_cvv']);	
				}
			} 
			else 
			{
				$process_button_string .= tep_draw_hidden_field('CVVtype', '0'); 
			}					
			$process_button_string .= tep_draw_hidden_field ('ccnum', $this->cc_card_number) .
			tep_draw_hidden_field('ccmo', $this->cc_expiry_month).
			tep_draw_hidden_field('ccyr', substr($this->cc_expiry_year, -2));
		}
		$process_button_string .= tep_draw_hidden_field('amount', number_format($order->info['total'],2,".","")) .	
		tep_draw_hidden_field('ID', tep_session_id ()) .
		tep_draw_hidden_field('override_email_customer', ((MODULE_PAYMENT_QUANTUMQGWDBE_EMAIL_CUSTOMER == 'True') ? 'Y': 'N'));
		if (MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY2 == "Y") {
			$process_button_string .= tep_draw_hidden_field ('RestrictKey', MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY);
		}
		
		// Unless $this->quantum_gets_cc is true (the user will type their name on the
		// Quantum gateway site), use the "Name on Credit Card" field from our site instead
		// of the billing name for card authorization.
		if($this->quantum_gets_cc) {
			$cc_owner_first = $order->billing['firstname'];
			$cc_owner_last = $order->billing['lastname'];
		}
		else {
		  $cc_owner = $_REQUEST['quantumqgwdbe_cc_owner'];
		  $space_pos = strpos($cc_owner, ' ');
		  if($space_pos === FALSE) {
			  $cc_owner_first = $cc_owner;
			  $cc_owner_last = '';
		  }
		  else {
			$cc_owner_first = substr($cc_owner, 0, $space_pos);
			$cc_owner_last = substr($cc_owner, strrpos($cc_owner, ' ') + 1);
		  }
		}
		
		$process_button_string .= tep_draw_hidden_field('MAXMIND', ((MODULE_PAYMENT_QUANTUMQGWDBE_MAXMIND == 'Y') ? 'Y': 'N')) .
		tep_draw_hidden_field ('company_logo', MODULE_PAYMENT_QUANTUMQGWDBE_LOGO_URL) .
		tep_draw_hidden_field ('bg_color', MODULE_PAYMENT_QUANTUMQGWDBE_BCK_COLOR) .
		tep_draw_hidden_field ('txt_color', MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_COLOR) .
		tep_draw_hidden_field('FNAME', $cc_owner_first) .
		tep_draw_hidden_field('LNAME', $cc_owner_last) .
		tep_draw_hidden_field('BADDR1', $order->billing['street_address']) .
		tep_draw_hidden_field('BCITY', $order->billing['city']) .
		tep_draw_hidden_field('BSTATE', $order->billing['state']) .
		tep_draw_hidden_field('BZIP1', $order->billing['postcode']) .
		tep_draw_hidden_field('BCOUNTRY', $order->billing['country']['iso_code_2']) .
		tep_draw_hidden_field('PHONE', $order->customer['telephone']) .
		tep_draw_hidden_field('BCUST_EMAIL', $order->customer['email_address']) .
		tep_draw_hidden_field('returning_visit', 'N') .
		tep_draw_hidden_field('ResponseMethod', 'GET') .
		
		tep_draw_hidden_field('post_return_url_approved', tep_href_link(FILENAME_CHECKOUT_PROCESS, '','SSL', true)) .
		tep_draw_hidden_field('post_return_url_declined',  tep_href_link(FILENAME_CHECKOUT_PROCESS, 'payment_error=' . $this->code . '&quantumqgwdbe_cc_owner=' . urlencode($_REQUEST['quantumqgwdbe_cc_owner']), 'SSL', true));
	
	
	
		// now take care of some cosmetic issues
		
		$process_button_string .= tep_draw_hidden_field(tep_session_name(),tep_session_id());
		return $process_button_string;
	}


	
	function before_process() {
		global $messageStack, $insert_id, $_REQUEST;
		$auth_response = $_REQUEST['auth_response'];
		$id = $_REQUEST['ID'];
		$session_name = tep_session_name ();
		$error = '';
		
                if(MODULE_PAYMENT_QUANTUMQGWDBE_MD5HASH != '' && $_REQUEST['md5_hash'] != '') {
		  // When the same secret MD5 key is entered in the Quantum gateway and on our site,
                  // we can use it to make sure the response to our payment comes from the Quantum 
                  // gateway instead of from a customer pretending that they paid.
		  $md5_expected = md5(MODULE_PAYMENT_QUANTUMQGWDBE_MD5HASH . MODULE_PAYMENT_QUANTUMQGWDBE_LOGIN . $_REQUEST['transID'] . $_REQUEST['amount']);
		  if($md5_expected != $_REQUEST['md5_hash']) {
			$error = "An unexpected response was received from the credit card processor.  Please email " . STORE_OWNER_EMAIL_ADDRESS . " for assistance.";
			
			tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO,
			  "WEB SITE Forged Gateway Response", 
			  "MD5 hash did not match on the following callback from Quantum Gateway: " 
			  . $this->cc_card_type 
			  . " AUTH: " . $_REQUEST['authCode'] . ". TransID: " . $_REQUEST['transID']
			  . " MD5 hash: " . $_REQUEST['md5_hash'] . " (expected " . $md5_expected . ")"
			  . ".",
			  STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			sleep(2); // Prevent a flood of forged transaction attempts.
		  }
                }
		
		if($error == '') {
		  $approved = (($_REQUEST['trans_result'] == "APPROVED") ? "1" : "0");
		  if ($approved == "0") {
			  $error = $_REQUEST['decline_reason'];
		  }
		}
		
		if($error != '') {
			// Use a $_SESSION var to report error message to avoid scrubbing of insecure characters from the URL
			$payment_error_return = 'payment_error=' . $this->code; // . '&error=' . urlencode($comments);
			tep_session_register($this->code . '_payment_error');
			$GLOBALS[$this->code . '_payment_error'] = $error;
			
			// We use tep_db_input around query string fields to prevent a malicious user
                        // from executing arbitrary SQL queries on our database.
			tep_db_query("UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " SET comments='" 
			  . tep_db_input("Credit Card payment.  " . $error . " " . $this->cc_card_type 
			  . " AUTH: " . $_REQUEST['authCode'] . ". TransID: " . $_REQUEST['transID']
			  . " MD5 hash: " . $_REQUEST['md5_hash'] . " (expected " . $md5_expected . ")"
			  . ".") . "', date_added=now() WHERE orders_id = '" . (int)$insert_id . "'");
		    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		}
	}
	
	function after_process() {
		return false;
	}
	
	function get_error() {
		// Use $GLOBALS[$this->code . '_payment_error'] (which points to $_SESSION[$this->code . '_payment_error'])
                // instead of $_GET['error'] to avoid problems with security modules that strips $_GET['error'] of most
                // punctuation characters.
		$error = array('title' => MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_ERROR,
		               'error' => $GLOBALS[$this->code . '_payment_error'] //stripslashes(urldecode($_GET['error']))
		);
		return $error;
	}
	
	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_QUANTUMQGWDBE_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}
	
	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable QuantumGateway Module', 'MODULE_PAYMENT_QUANTUMQGWDBE_STATUS', 'True', 'Do you want to accept QuantumGateway payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_QUANTUMQGWDBE_LOGIN', 'testing', 'The login username used for the QuantumGateway service', '6', '1', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable QuantumGateway To Collect CC#', 'MODULE_PAYMENT_QUANTUMQGWDBE_CC', 'False', 'Do you want QuantumGateway to Collect the Credit Card Number??', '6', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_QUANTUMQGWDBE_EMAIL_CUSTOMER', 'False', 'Should Quatnumgateway e-mail a receipt to the customer?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");	  
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Restrict Key', 'MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY2', 'N', 'Enable Restriction Key?', '6', '4', 'tep_cfg_select_option(array(\'Y\', \'N\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Restriction Key', 'MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY', '', 'Restriction Key used for restricting processing without key.  Note: You must have this enabled in your Quantumgateway Processing Config.', '6', '5', now())"); 
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MD5 Hash', 'MODULE_PAYMENT_QUANTUMQGWDBE_MD5HASH', '', 'MD5 Hash Key used to validate that a response from Quantumgateway has not been forged.  Note: You must have this same value entered in your Quantumgateway Processing Config.', '6', '6', now())");  
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Maxmind Fraud Control', 'MODULE_PAYMENT_QUANTUMQGWDBE_MAXMIND', 'Y', 'Enable?', '6', '7', 'tep_cfg_select_option(array(\'Y\', \'N\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Capture CVV2', 'MODULE_PAYMENT_QUANTUMQGWDBE_USESCVV', 'Y', 'Does user must type CVV code?.', '6', '8','tep_cfg_select_option(array(\'Y\', \'N\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('URL for Store Logo', 'MODULE_PAYMENT_QUANTUMQGWDBE_LOGO_URL', '', 'The URL to a logo to be used by eProcessing to display during transactions', '6', '9', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Background Color', 'MODULE_PAYMENT_QUANTUMQGWDBE_BCK_COLOR', '#FFFFFF', 'The Background Color in Hex format:', '6', '10', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Text Color', 'MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_COLOR', '#000000', 'The Color of the Text in Hex format:', '6', '11', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_QUANTUMQGWDBE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
	}
	
	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}
	
	function keys() {
		return array('MODULE_PAYMENT_QUANTUMQGWDBE_STATUS', 
		'MODULE_PAYMENT_QUANTUMQGWDBE_LOGIN',
		'MODULE_PAYMENT_QUANTUMQGWDBE_CC',
		'MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY2',
		'MODULE_PAYMENT_QUANTUMQGWDBE_TXNKEY',
		'MODULE_PAYMENT_QUANTUMQGWDBE_MD5HASH',
		'MODULE_PAYMENT_QUANTUMQGWDBE_EMAIL_CUSTOMER',
		'MODULE_PAYMENT_QUANTUMQGWDBE_LOGO_URL',
		'MODULE_PAYMENT_QUANTUMQGWDBE_BCK_COLOR',
		'MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_COLOR',
		'MODULE_PAYMENT_QUANTUMQGWDBE_MAXMIND',
		'MODULE_PAYMENT_QUANTUMQGWDBE_USESCVV',
		'MODULE_PAYMENT_QUANTUMQGWDBE_SORT_ORDER'
		);
	}
}
?>
