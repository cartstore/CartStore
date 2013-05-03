<?php
/**
* Elavon a.k.a. Nova or Virtual Merchant Payment Module for osCommerce
*
*************************************************************************
* ChargeIt prepares data according to Virtual Merchant's Developer's Guide.
* Then posts via https credit card transactions to Virtual Merchant's
* process.do. Submission and referer set by cURL. Transaction results are
* returned by process.do in ASCII pairs. ChargeIt interprets errors, attempts
* to resubmit declines, or display error to user and allow user to
* resubmit information. ChargeIt also auto submits DCC opt in information
* according to admin setup. Transaction errors can also be set to email
* an administrator.
*************************************************************************
*
* @package ChargeIt
* @link http://www.joomecom.com/ Ecommerce Applications
* @copyright Copyright 2008, Teradigm, Inc. All Rights Reserved.
* @author Zelf
* @version 1.2
*/

class ChargeIt {
	var $code, $title, $description, $enabled, $submit_data, $resubmitted, $responseAry, $internationalOrder, $avsResponseAry, $cvvResponseAry, $maxFieldLenAry, $testAry;

    function ChargeIt() {
		global $order;

		$this->code = 'chargeit';
		$this->title = MODULE_PAYMENT_CHARGEIT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_CHARGEIT_TEXT_DESCRIPTION;

		$this->sort_order = MODULE_PAYMENT_CHARGEIT_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_CHARGEIT_STATUS == 'True') ? true : false);

		if ((int)MODULE_PAYMENT_CHARGEIT_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_CHARGEIT_ORDER_STATUS_ID;
		}

		if (is_object($order)) {
			$this->update_status();
		}

		$this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true); // checkout_process.php - page to go to on completion
		$this->virtual_merchant_url = 'https://www.myvirtualmerchant.com/VirtualMerchant/process.do';
		$this->curl_referer = MODULE_PAYMENT_CHARGEIT_REFERER_URL;
		$this->submit_data = array(); // Array type used to prep transaction params for cURL post to virtual merchant
		$this->resubmitted = false; // Boolean used to test if transaction has been resubmitted.
		$this->responseAry = array(); // Array type used to capture virtual merchant results and error messages
		$this->internationalOrder = 0; // Used to tag international orders
		$this->avsResponseAry = array(
										'A' => 'Address matches - Zip Code does not match.',
										'B' => 'Street address match, Postal code in wrong format. (International issuer)',
										'C' => 'Street address and postal code in wrong formats',
										'D' => 'Street address and postal code match (international issuer)',
										'E' => 'AVS Error',
										'G' => 'Service not supported by non-US issuer',
										'I' => 'Address information not verified by international issuer.',
										'M' => 'Street Address and Postal code match (international issuer)',
										'N' => 'No Match on Address (Street) or Zip',
										'O' => 'No Response sent',
										'P' => 'Postal codes match, Street address not verified due to incompatible formats.',
										'R' => 'Retry, System unavailable or Timed out',
										'S' => 'Service not supported by issuer',
										'U' => 'Address information is unavailable',
										'W' => '9 digit Zip matches, Address (Street) does not match.',
										'X' => 'Exact AVS Match',
										'Y' => 'Address (Street) and 5-digit Zip match.',
										'Z' => '5 digit Zip matches, Address (Street) does not match.'
									 );

		$this->cvvResponseAry = array(
										'M' => 'CVV2 Match',
										'N' => 'CVV2 No match',
										'P' => 'Not Processed',
										'S' => 'Issuer indicates that CVV2 data should be present on the card, but the merchant has indicated that the CVV2 data is not resent on the card',
										'U' => 'Issuer has not certified for CVV2 or Issuer has not provided Visa with the CVV2 encryption Keys'
									 );

		$this->maxFieldLenAry = array(
										// Billing Info Max Lengths
										ssl_first_name => 20,
										ssl_last_name => 30,
										ssl_company => 50,
										ssl_avs_address => 20,
										ssl_address2 => 20,
										ssl_city => 30,
										ssl_state => 30,
										ssl_avs_zip => 9,
										ssl_country => 50,

										// Contact Info
										ssl_phone => 20,
										ssl_email => 100,

										// Shipping Info
										ssl_ship_to_company => 50,
										ssl_ship_to_first_name => 20,
										ssl_ship_to_last_name => 30,
										ssl_ship_to_avs_address => 20,
										ssl_ship_to_address2 => 20,
										ssl_ship_to_city => 30,
										ssl_ship_to_state => 30,
										ssl_ship_to_avs_zip => 9,
										ssl_ship_to_country => 50
									 );
    }

	function update_status() {
		global $order;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_CHARGEIT_ZONE > 0) ) {
			$check_flag = false;
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_CHARGEIT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");

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
		$js =   '  if (payment_value == "' . $this->code . '") {' . "\n" .
				'    var cc_owner = returnObjById(\'cc_owner\');' . "\n" .
				'    var cc_number = returnObjById(\'cc_number\');' . "\n" .
				'    var cvv_number = returnObjById(\'cvv_number\');' . "\n" .
				'    cc_owner = cc_owner.value;' . "\n" .
				'    cc_number = cc_number.value;' . "\n" .
				'    cvv_number = cvv_number.value;' . "\n" .
				'    var numericTest = /^[0-9][0-9][0-9]$|^[0-9][0-9][0-9][0-9]$/;' ."\n".
				'    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
				'      error_message = error_message + "' . MODULE_PAYMENT_CHARGEIT_TEXT_JS_CC_OWNER . '";' . "\n" .
				'      error = 1;' . "\n" .
				'    }' . "\n" .
				'    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
				'      error_message = error_message + "' . MODULE_PAYMENT_CHARGEIT_TEXT_JS_CC_NUMBER . '";' . "\n" .
				'      error = 1;' . "\n" .
				'    }' . "\n";

		if (MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED == 'True') {
			$js .=  '    if (cvv_number == "" || cvv_number.length < ' . CVV_NUMBER_MIN_LENGTH . ') {' . "\n" .
					'      error_message = error_message + "' . MODULE_PAYMENT_CHARGEIT_TEXT_JS_CVV_NUMBER . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n" .
					'    if (cvv_number.match(numericTest)) {' . "\n" .
					'      error = 0;' ."\n" .
					'    }' . "\n".
					'	 else {' . "\n" .
					'      error_message = error_message + "' . MODULE_PAYMENT_CHARGEIT_TEXT_JS_INVALID_CVV_NUMBER . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n" .
					'    if (cvv_number.length > ' . CVV_NUMBER_MAX_LENGTH . ') {' . "\n" .
					'      error_message = error_message + "' . MODULE_PAYMENT_CHARGEIT_TEXT_JS_MAX_CVV_NUMBER . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n";
		}

		$js .=  '  }' . "\n";

		return $js;
    }

    function selection() { // generate html input fields for cc information
		global $order;

		for ($i=1; $i < 13; $i++) {
			$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i) .' ' . strftime('%B',mktime(0,0,0,$i,1,2000))) ;
		}


		$today = getdate();
		for ($i=$today['year']; $i < $today['year']+10; $i++) {
			$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
		}

		$selection = array('id' => $this->code,
						 'module' => $this->title,
						 'fields' => array(array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_OWNER,
												 'field' => tep_draw_input_field('cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'], 'id="cc_owner"')),
										   array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_NUMBER,
												 'field' => tep_draw_input_field('cc_number','','id="cc_number" autocomplete="off"')),
										   array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_EXPIRES,
												 'field' => tep_draw_pull_down_menu('cc_expires_month', $expires_month, 'id="cc_expires_month"') . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year, 'id="cc_expires_year"')),
										   array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CVV_NUMBER,
												 'field' => tep_draw_input_field('cvv_number','','id="cvv_number" autocomplete="off" size="5" maxlength="4"'))));

		return $selection;
    }

    function pre_confirmation_check() {
		global $HTTP_POST_VARS;

		include(DIR_WS_CLASSES . 'chargeit_cc_validation.php');

		$chargeit_cc_validation = new chargeit_cc_validation();
		$result = $chargeit_cc_validation->validate($HTTP_POST_VARS['cc_number'], $HTTP_POST_VARS['cc_expires_month'], $HTTP_POST_VARS['cc_expires_year']);

		$error = '';
		switch ($result) {

			case -1:
				$error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($chargeit_cc_validation->cc_number, 0, 4));
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
			$payment_error_return = MODULE_PAYMENT_CHARGEIT_CC_ERROR . '|' . $error . '|' . MODULE_PAYMENT_CHARGEIT_CC_ERROR_NAME . $_POST['cc_owner'] . '|' . MODULE_PAYMENT_CHARGEIT_CC_ERROR_EXP . $_POST['cc_expires_month'] . '/' . $_POST['cc_expires_year'];
			tep_session_register('payment_error_return');
			$_SESSION['payment_error_return'] = $payment_error_return;
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
		}

		$this->cc_card_type = $chargeit_cc_validation->cc_type;
		$this->cc_card_number = $chargeit_cc_validation->cc_number;
		$this->cc_expires_month = $chargeit_cc_validation->cc_expires_month;
		$this->cc_expires_year = $chargeit_cc_validation->cc_expires_year;
    }

	function confirmation() {
		global $HTTP_POST_VARS;

		//detect if they put in a valid cvv and set indicator
		if (ereg ("(^[0-9][0-9][0-9]$|^[0-9][0-9][0-9][0-9]$)", $HTTP_POST_VARS['cvv_number']) == 1){
			$this->cvv_number = $HTTP_POST_VARS['cvv_number'];
			$this->cvv_indicator = 1;
		} else {
			$this->cvv_number = '';
			$this->cvv_indicator = 9;
		}

		if ($this->cvv_indicator == 1) {
			$confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
								  'fields' => array(array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_OWNER,
														  'field' => $HTTP_POST_VARS['cc_owner']),
													array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_NUMBER,
														  'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
													array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CVV_NUMBER,
														  'field' => $this->cvv_number),
													array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_EXPIRES,
														  'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])))));
		} else {
			$confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
								  'fields' => array(array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_OWNER,
														  'field' => $HTTP_POST_VARS['cc_owner']),
													array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_NUMBER,
														  'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
													array('title' => MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_EXPIRES,
														  'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])))));
		}


		return $confirmation;
    }

    function process_button() {
		// Hidden fields on the checkout confirmation page
		$process_button_string = tep_draw_hidden_field('cc_owner', $_POST['cc_owner']) .
							     tep_draw_hidden_field('cc_expires', $this->cc_expires_month . substr($this->cc_expires_year, -2)) .
							     tep_draw_hidden_field('cc_type', $this->cc_card_type) .
							     tep_draw_hidden_field('cc_number', $this->cc_card_number) .
							     tep_draw_hidden_field('cvv_number', $this->cvv_number) .
							     tep_draw_hidden_field('cvv_indicator', $this->cvv_indicator) .
								 tep_draw_hidden_field(tep_session_name(), tep_session_id());

		return $process_button_string;
	}

	function cURLDataStream($transaction_data) {
		// concatenate the submission data and put into variable $data
		while(list($key, $value) = each($transaction_data)) {
			$data .= $key . '=' . urlencode(ereg_replace(',', '', $value)) . '&';
		}

		// Remove the last "&" from the string
		$data = substr($data, 0, -1);

		unset($response);

		// Post order info data to Virtual Merchant
		// Requires cURL must be compiled into PHP
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->virtual_merchant_url); // url set in constructor
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_REFERER, $this->curl_referer);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$authorize = curl_exec($ch);
		curl_close($ch);

		$response = explode("\n", $authorize); // explode each line of response into an array

		$this->responseAry = array();

		foreach ($response as $line) {
			$codes = explode('=', $line);
			list($key, $value) = $codes;
			$this->responseAry[$key] = $value;
		}

		$this->testAry[] = $transaction_data;
		$this->testAry[] = $authorize;

		$this->msgResultTests(); // Test the results
	}

	function noCVV2Submit() {
		$this->submit_data['ssl_cvv2cvc2_indicator'] = 9; // Set indicator to Not Present
		$this->submit_data['ssl_cvv2cvc2'] = '1234'; // Set cvv2 number to any number, but cannot be empty if indicator is set to 9

		$this->resubmitted = true; // Flag that we have resubmitted the request.
		$this->cURLDataStream($this->submit_data);	// Re-submit sale transaction request to Virtual Merchant.
	}

	function sendDCC() {
		// Set dcc options.
		// if the user is outside of the United States Virtual Merchant returns dcc options.
		// This automates the process setting dcc to Y or N.  Merchant still gets exact amount in ssl_amount either way

		unset($dcc_data);  // Cleans out any previous data stored in the variable

		// format dcc data for submission
		$dcc_data = array (
							// Transaction settings
							id => $this->responseAry['id']
						  );

		// dccoption is set to Y if admin is set to True, and virtual merchant is able to identify conversion rates
		if (MODULE_PAYMENT_CHARGEIT_DCC == 'True' && $this->responseAry['ssl_conversion_rate'] != '' && $this->responseAry['ssl_cardholder_amount'] != '') {
			$dcc_data['dccoption'] = 'Y';
		} else {
			$dcc_data['dccoption'] = 'N';
		}

		$this->resubmitted = true; // Flag that we have resubmitted the request.
		$this->cURLDataStream($dcc_data);	// Submit dcc request to Virtual Merchant.
	}

	function msgResultTests() {
		global $order;

		if (array_key_exists('dccoption', $this->responseAry)) { // If the dccoption key exists then credit card info was valid.
			$this->internationalOrder = 1; // Tag as an international order
			$this->sendDCC(); // Submit exchange rate and dcc admin options.
		}

		// Uncomment to test output
		//if ($_SERVER['REMOTE_ADDR'] == 'My IP') {
		//	print_r($this->testAry);
		//	die();
		//}


		// Catch NON APPROVED transactions here.
		if ($this->responseAry['ssl_result'] != '0') {
			if ($this->responseAry['ssl_result_message'] != '') { // Catch non system errors e.g. declined or declined cvv2
				$errorMsg = $this->responseAry['ssl_result_message'];

				// Resubmit the transaction without cvv2 information unless it is international, which would have been already submitted.
				if (($errorMsg == 'DECLINED CVV2' || $errorMsg == 'DECLINED') && MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED == 'False' && !$this->internationalOrder && $this->resubmitted = false) {
					$this->noCVV2Submit();
				}
			}

			if ($this->responseAry['errorMessage'] != '') { // Catch system error messages e.g. Invalid Merchant Number. Add system errors to error msg.
				$errorMsg .= ' ' . $this->responseAry['errorCode'] . '. ' . $this->responseAry['errorName'] . '. ' . $this->responseAry['errorMessage'];
			}

			// Get avs and cvv2 error responses
			$specificErrors = "\n" . MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR1 . $this->avsResponseAry[$this->responseAry['ssl_avs_response']];
			$specificErrors .= "\n" . MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR2 . $this->cvvResponseAry[$this->responseAry['ssl_cvv2_response']];

			if (MODULE_PAYMENT_CHARGEIT_EMAIL_ERRORS == 'True') { // Email errors to admin email if set to true
				if ($this->internationalOrder) {
					$internationalText = MODULE_PAYMENT_CHARGEIT_EMAIL_TRANS_INT_MSG;
				}

				$message = MODULE_PAYMENT_CHARGEIT_EMAIL_CUST_ID . $_SESSION['customer_id'] . "\n" .
							$order->customer['email_address'] . "\n" .
							$order->customer['firstname'] . ' ' . $order->customer['lastname'] . "\n" .
							$order->billing['country']['title'] . "\n" .
							$cvvNumber . "\n" .
							MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR_MSG . $errorMsg . $specificErrors;

				tep_mail(MODULE_PAYMENT_CHARGEIT_EMAIL_TRANS_MSG, MODULE_PAYMENT_CHARGEIT_ADMIN_EMAIL, STORE_NAME . $internationalText . ' ' . MODULE_PAYMENT_CHARGEIT_EMAIL_TRANS_MSG, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			}

			$callForHelp = '';

			if (MODULE_PAYMENT_CHARGEIT_DECLINE_HELP != '') { // If a number to call for help is provided enter it here.
				$callForHelp = '<span style=\'color:#0054e3; font-weight:bold\'>' . MODULE_PAYMENT_CHARGEIT_DECLINE_CALL_HELP1 . MODULE_PAYMENT_CHARGEIT_DECLINE_HELP . MODULE_PAYMENT_CHARGEIT_DECLINE_CALL_HELP2 . '</span>';
			}

			// Return error
			$errorMsg = MODULE_PAYMENT_CHARGEIT_DECLINE_HELP_SORRY . $callForHelp;

			$payment_error_return = MODULE_PAYMENT_CHARGEIT_CC_ERROR . '|' . $errorMsg . '|' . MODULE_PAYMENT_CHARGEIT_CC_ERROR_NAME . $_POST['cc_owner'] . '|' . MODULE_PAYMENT_CHARGEIT_CC_ERROR_EXP . $_POST['cc_expires'] . ' (MMYY)';
			tep_session_register('payment_error_return');
			$_SESSION['payment_error_return'] = $payment_error_return;
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
		}
	}

	function before_process() {
		global $_POST, $order;

		// DATA PREPARATION SECTION
		unset($this->submit_data);  // Cleans out any previous data stored in the variable

		// Create an array containing products ordered for the description field
		$description = '';
		for ($i=0; $i < sizeof($order->products); $i++) {
			$description[] = $order->products[$i]['name'] . '(qty: ' . $order->products[$i]['qty'] . ')';
		}

		// implode array into | delimited string for description of transaction
		$description = implode('|', $description);

		// Calculate the next expected order id
		$next_order_id = "select (max(orders_id) + 1) as next_id from " . TABLE_ORDERS;
		$result = tep_db_query ($next_order_id);
		while ($row = tep_db_fetch_array($result)) {
			$new_order_id = $row['next_id'];
		}

		// Prep some data for transmission according to Virtual merchant requirements and variables.
		$billingState = $this->get_state_code($order->billing['state']);
		$deliveryState = $this->get_state_code($order->delivery['state']);
		$billToStreetAddress2 = (empty($order->billing['suburb'])) ? $order->billing['street_address2'] : $order->billing['suburb']; // Use suburb if not empty otherwise use address2
		$shipToStreetAddress2 = (empty($order->delivery['suburb'])) ? $order->delivery['street_address2'] : $order->delivery['suburb']; // Use suburb if not empty otherwise use address2
		$zipReplaceAry = array('-', ' ');
		$sslAVSZip = str_replace($zipReplaceAry, '', $order->billing['postcode']); // can only be 9 characters. Remove all spaces and hyphens.
		$testEmail = $this->validEmail($order->customer['email_address']); // Strict test for valid email. Virtual merchant will fail transaction if email is not valid.
		if ($testEmail) {
			$sslEmail = $order->customer['email_address'];
		} else {
			$sslEmail = '';
		}

		// clean $_POST
		$ssl_card_number = strip_tags($_POST['cc_number']);
		$ssl_exp_date = strip_tags($_POST['cc_expires']);
		$ssl_cvv2cvc2_indicator = strip_tags($_POST['cvv_indicator']);
		$ssl_cvv2cvc2 = strip_tags($_POST['cvv_number']);

		// Populate an array that contains all of the data to be submitted
		$this->submit_data = array (
								// Transaction settings
								ssl_merchant_id => MODULE_PAYMENT_CHARGEIT_ACCOUNT_ID, // The login name as assigned to you by Virtual Merchant
								ssl_user_id => MODULE_PAYMENT_CHARGEIT_USER_ID, // The login name you setup for your automated web transaction user
								ssl_pin => MODULE_PAYMENT_CHARGEIT_PIN, // The pin that was auto assigned to this new user
								ssl_transaction_type => 'CCSALE',
								ssl_show_form => 'FALSE', // Process transaction directly
								ssl_result_format => 'ASCII', // DO NOT CHANGE. The formatting type for result messages from Virtual Merchant

								// Transaction Info
								ssl_amount => number_format($order->info['total'], 2),
								ssl_card_number => $ssl_card_number,
								ssl_exp_date => $ssl_exp_date,
								ssl_customer_code => $_SESSION['customer_id'],
								invoice_number => $new_order_id,

								// Billing Info
								ssl_first_name => substr($order->billing['firstname'], 0, $this->maxFieldLenAry['ssl_first_name']),
								ssl_last_name => substr($order->billing['lastname'], 0, $this->maxFieldLenAry['ssl_last_name']),
								ssl_company => substr($order->billing['company'], 0, $this->maxFieldLenAry['ssl_company']),
								ssl_avs_address => substr($order->billing['street_address'], 0, $this->maxFieldLenAry['ssl_avs_address']), // Virtual merchant only accepts addresses up to 20 characters
								ssl_address2 => substr($billToStreetAddress2, 0, $this->maxFieldLenAry['ssl_address2']), // Virtual merchant only accepts addresses up to 20 characters
								ssl_city => substr($order->billing['city'], 0, $this->maxFieldLenAry['ssl_city']),
								ssl_state => substr($billingState, 0, $this->maxFieldLenAry['ssl_state']),
								ssl_avs_zip => substr($sslAVSZip, 0, $this->maxFieldLenAry['ssl_avs_zip']),
								ssl_country => substr($order->billing['country']['title'], 0, $this->maxFieldLenAry['ssl_country']),

								// Contact Info
								ssl_phone => substr($order->customer['telephone'], 0, $this->maxFieldLenAry['ssl_phone']),
								ssl_email => $sslEmail,

								// Shipping Info
								ssl_ship_to_company => $order->delivery['company'],
								ssl_ship_to_first_name => substr($order->delivery['firstname'], 0, $this->maxFieldLenAry['ssl_ship_to_first_name']),
								ssl_ship_to_last_name => substr($order->delivery['lastname'], 0, $this->maxFieldLenAry['ssl_ship_to_last_name']),
								ssl_ship_to_avs_address => substr($order->delivery['street_address'], 0, $this->maxFieldLenAry['ssl_ship_to_avs_address']),
								ssl_ship_to_address2 => substr($shipToStreetAddress2, 0, $this->maxFieldLenAry['ssl_ship_to_address2']),
								ssl_ship_to_city => substr($order->delivery['city'], 0, $this->maxFieldLenAry['ssl_ship_to_city']),
								ssl_ship_to_state => substr($deliveryState, 0, $this->maxFieldLenAry['ssl_ship_to_state']),
								ssl_ship_to_avs_zip => substr($order->delivery['postcode'], 0, $this->maxFieldLenAry['ssl_ship_to_avs_zip']),
								ssl_ship_to_country => substr($order->delivery['country']['title'], 0, $this->maxFieldLenAry['ssl_ship_to_country']),

								// Products purchased summary
								ssl_description => $description
							 );

		// If cvv2 is not required and no cvv2 number was passed then set indicator to "Not Present" - 0 = Bypassed, 1 = Present, 2 = Illegible, 9 = Not Present
		if (MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED == 'True') {
			$this->submit_data['ssl_cvv2cvc2_indicator'] = $ssl_cvv2cvc2_indicator;
			$this->submit_data['ssl_cvv2cvc2'] = $ssl_cvv2cvc2;
		} else if (MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED == 'False') {
			$this->submit_data['ssl_cvv2cvc2_indicator'] = 9;
			$this->submit_data['ssl_cvv2cvc2'] = '1234'; // Can be any number, but cannot be blank if indicator is not present.
		}

		// Test mode switch
		if(MODULE_PAYMENT_CHARGEIT_TESTMODE == 'Test') {
			$this->submit_data['ssl_test_mode'] = 'TRUE';
		} else {
			$this->submit_data['ssl_test_mode'] = 'FALSE';
		}

		$this->cURLDataStream($this->submit_data); // First attempt to submit sale transaction request to Virtual Merchant.
	}

	/**
	Virtual Merchant appears to be using strict adherence to RFC2821 & RFC2822 guidelines.
	preg_match set to strict for invalid characters.
	*/
	function validEmail($email) {
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else if (strlen($email) > $this->maxFieldLenAry['ssl_email']) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^(\\\\.|[A-Za-z0-9`_=\\/\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
					$isValid = false;
				}
			}

			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}

		return $isValid;
	}

	function after_process() {
		return false;
	}

	function get_error() {
		return false;
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CHARGEIT_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}

		return $this->_check;
	}

	function install() {
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Virtual Merchant Module', 'MODULE_PAYMENT_CHARGEIT_STATUS', 'True', 'Do you want to accept Virtual Merchant payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Virtual Merchant Account ID', 'MODULE_PAYMENT_CHARGEIT_ACCOUNT_ID', '0', 'The Account ID for your Virtual Merchant Account', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User ID', 'MODULE_PAYMENT_CHARGEIT_USER_ID', '0', 'The User ID for your Virtual Merchant Account User', '6', '3', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User PIN', 'MODULE_PAYMENT_CHARGEIT_PIN', '0', 'The User PIN for your Virtual Merchant Account', '6', '4', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Set Referer URL', 'MODULE_PAYMENT_CHARGEIT_REFERER_URL', 'https://secure.yoursite.com/checkout_confirmation.php', 'Set the authorized referer url you set in your Virtual Terminal Merchant Account.', '6', '5', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction mode', 'MODULE_PAYMENT_CHARGEIT_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '6', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_CHARGEIT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '7', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_CHARGEIT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '8', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set order status', 'MODULE_PAYMENT_CHARGEIT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '9', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable email errors to admin email below', 'MODULE_PAYMENT_CHARGEIT_EMAIL_ERRORS', 'False', 'Do you want to receive error emails from Virtual Merchant?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Admin email', 'MODULE_PAYMENT_CHARGEIT_ADMIN_EMAIL', 'me@mydomain.com', 'The email address to send Virtual Merchant error messages to.', '6', '11', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Call Number for Help', 'MODULE_PAYMENT_CHARGEIT_DECLINE_HELP', '800-111-2222', 'The number users can call if they are being declined. Remove number to not display a number.', '6', '12', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit cards accepted', 'MODULE_PAYMENT_CHARGEIT_CC_ACCEPTED', 'Visa, Mastercard, Amex, Discover', 'The credit cards you accept through your Virtual Merchant Account.', '6', '13', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Set CVV2 to Required', 'MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED', 'False', 'Do you want the cvv2 number to be required?', '6', '14', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable DCC', 'MODULE_PAYMENT_CHARGEIT_DCC', 'False', 'Do you want send International orders via DCC program?', '6', '15', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		return array('MODULE_PAYMENT_CHARGEIT_STATUS', 'MODULE_PAYMENT_CHARGEIT_ACCOUNT_ID', 'MODULE_PAYMENT_CHARGEIT_USER_ID', 'MODULE_PAYMENT_CHARGEIT_PIN', 'MODULE_PAYMENT_CHARGEIT_TESTMODE','MODULE_PAYMENT_CHARGEIT_ZONE', 'MODULE_PAYMENT_CHARGEIT_REFERER_URL', 'MODULE_PAYMENT_CHARGEIT_ORDER_STATUS_ID', 'MODULE_PAYMENT_CHARGEIT_SORT_ORDER', 'MODULE_PAYMENT_CHARGEIT_EMAIL_ERRORS', 'MODULE_PAYMENT_CHARGEIT_ADMIN_EMAIL', 'MODULE_PAYMENT_CHARGEIT_CC_ACCEPTED', 'MODULE_PAYMENT_CHARGEIT_CVV_REQUIRED', 'MODULE_PAYMENT_CHARGEIT_DCC', 'MODULE_PAYMENT_CHARGEIT_DECLINE_HELP');
	}

	function get_state_code($cState) {
		$stateCode = $cState;

		if ($cState == 'Alabama')
			$stateCode = 'AL';
		else if ($cState == 'Alaska')
			$stateCode = 'AK';
		else if ($cState == 'Arkansas')
			$stateCode = 'AR';
		else if ($cState == 'Arizona')
			$stateCode = 'AZ';
		else if ($cState == 'California')
			$stateCode = 'CA';
		else if ($cState == 'Colorado')
			$stateCode = 'CO';
		else if ($cState == 'Connecticut')
			$stateCode = 'CT';
		else if ($cState == 'District of Columbia')
			$stateCode = 'DC';
		else if ($cState == 'Delaware')
			$stateCode = 'DE';
		else if ($cState == 'Florida')
			$stateCode = 'FL';
		else if ($cState == 'Georgia')
			$stateCode = 'GA';
		else if ($cState == 'Hawaii')
			$stateCode = 'HI';
		else if ($cState == 'Iowa')
			$stateCode = 'IA';
		else if ($cState == 'Idaho')
			$stateCode = 'ID';
		else if ($cState == 'Illinois')
			$stateCode = 'IL';
		else if ($cState == 'Indiana')
			$stateCode = 'IN';
		else if ($cState == 'Kansas')
			$stateCode = 'KS';
		else if ($cState == 'Kentucky')
			$stateCode = 'KY';
		else if ($cState == 'Louisiana')
			$stateCode = 'LA';
		else if ($cState == 'Massachusetts')
			$stateCode = 'MA';
		else if ($cState == 'Maryland')
			$stateCode = 'MD';
		else if ($cState == 'Maine')
			$stateCode = 'ME';
		else if ($cState == 'Michigan')
			$stateCode = 'MI';
		else if ($cState == 'Minnesota')
			$stateCode = 'MN';
		else if ($cState == 'Missouri')
			$stateCode = 'MO';
		else if ($cState == 'Mississippi')
			$stateCode = 'MS';
		else if ($cState == 'Montana')
			$stateCode = 'MT';
		else if ($cState == 'North Carolina')
			$stateCode = 'NC';
		else if ($cState == 'North Dakota')
			$stateCode = 'ND';
		else if ($cState == 'Nebraska')
			$stateCode = 'NE';
		else if ($cState == 'New Hampshire')
			$stateCode = 'NH';
		else if ($cState == 'New Jersey')
			$stateCode = 'NJ';
		else if ($cState == 'New Mexico')
			$stateCode = 'NM';
		else if ($cState == 'Nevada')
			$stateCode = 'NV';
		else if ($cState == 'New York')
			$stateCode = 'NY';
		else if ($cState == 'Ohio')
			$stateCode = 'OH';
		else if ($cState == 'Oklahoma')
			$stateCode = 'OK';
		else if ($cState == 'Oregon')
			$stateCode = 'OR';
		else if ($cState == 'Pennsylvania')
			$stateCode = 'PA';
		else if ($cState == 'Rhode Island')
			$stateCode = 'RI';
		else if ($cState == 'South Carolina')
			$stateCode = 'SC';
		else if ($cState == 'South Dakota')
			$stateCode = 'SD';
		else if ($cState == 'Tennessee')
			$stateCode = 'TN';
		else if ($cState == 'Texas')
			$stateCode = 'TX';
		else if ($cState == 'Utah')
			$stateCode = 'UT';
		else if ($cState == 'Virginia')
			$stateCode = 'VA';
		else if ($cState == 'Vermont')
			$stateCode = 'VT';
		else if ($cState == 'Washington')
			$stateCode = 'WA';
		else if ($cState == 'Wisconsin')
			$stateCode = 'WI';
		else if ($cState == 'West Virginia')
			$stateCode = 'WV';
		else if ($cState == 'Wyoming')
			$stateCode = 'WY';

		return $stateCode;
	}
}
?>