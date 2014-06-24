<?php
/*
 *
 * USAePay Payment Module
 *
 * 	v2.0   June 23, 2009
 *
 * 	Based on code by osCommerce, Zen-Cart, Jason LeBaron,
 * 		Tim McEwen and others. Includes USAePay PHP library.
 *
 * 	Released under the GNU General Public License
 *
 * 	For additional information on installing/using this module:
 *
 * 		http://help.usaepay.com/merchant/support/carts/oscommerce
 */


class usaepay {
	var $code, $title, $description, $enabled;

	// class constructor
	function usaepay() {
		global $order;

		$this->code = 'usaepay';
		$this->title = MODULE_PAYMENT_USAEPAY_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_USAEPAY_TEXT_DESCRIPTION;
		$this->enabled = ((MODULE_PAYMENT_USAEPAY_STATUS == 'True') ? true : false);
		$this->sort_order = MODULE_PAYMENT_USAEPAY_SORT_ORDER;

		if ((int)MODULE_PAYMENT_USAEPAY_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_USAEPAY_ORDER_STATUS_ID;
		}

		if (is_object($order)) $this->update_status();

		// Set integration method
		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE != 'Local') {
			$this->form_action_url = 'https://' . (MODULE_PAYMENT_USAEPAY_TRANSACTION_MODE == 'Sandbox' ? 'sandbox' : 'secure') . '.usaepay.com/interface/epayform';
		}
	}

	// class methods
	function update_status() {
		global $order;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_USAEPAY_ZONE > 0) ) {
			$check_flag = false;
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_USAEPAY_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

	/*
	 * Javascript validation for serverside (local) integration  (payment form will validate info on usaepay server)
	 */
	function javascript_validation() {
		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
			$js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
					'    var usaepay_cc_owner = document.checkout_payment.usaepay_cc_owner.value;' . "\n" .
					'    var usaepay_cc_number = document.checkout_payment.usaepay_cc_number.value;' . "\n" .
					'    var usaepay_cc_cvv = document.checkout_payment.usaepay_cc_cvv.value;' . "\n" .
					'    if (usaepay_cc_owner == "" || usaepay_cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
					'      error_message = error_message + "' . MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_OWNER . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n" .
					'    if (usaepay_cc_number == "" || usaepay_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
					'      error_message = error_message + "' . MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_NUMBER . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n" .
					'    if (usaepay_cc_cvv == "" || usaepay_cc_cvv.length < "3") {' . "\n".
					'      error_message = error_message + "' . MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_CVV . '";' . "\n" .
					'      error = 1;' . "\n" .
					'    }' . "\n" .
					'  }' . "\n";
			return $js;
		} else {
			return false;
		}
	}

	/*
	 * Build the fields that are to be show on the checkout payment screen
	 *  if using payment form,  no fields to be collected
	 */
	function selection() {
		global $order;

		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
			for ($i=1; $i<13; $i++) {
				$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
			}

			$today = getdate();
			for ($i=$today['year']; $i < $today['year']+10; $i++) {
				$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
			}

			$selection = array('id' => $this->code,
									'module' => $this->title,
									'fields' => array(array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_OWNER,
																	'field' => $order->billing['firstname'] . ' ' . $order->billing['lastname']),
															array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_NUMBER,
																	'field' => tep_draw_input_field('usaepay_cc_number')),
															array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_EXPIRES,
																	'field' => tep_draw_pull_down_menu('usaepay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('usaepay_cc_expires_year', $expires_year)),
															array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CVV,
																	'field' => tep_draw_input_field('usaepay_cc_cvv', '', "size=4, maxlength=4"))
															)
									);
			// someday we need to have help for cvv but it doesn't look like oscommerce has a common cvv help page like zencart
			//											array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CVV . ' ' .'<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_CVV_HELP) . '\')">' . MODULE_PAYMENT_USAEPAY_API_TEXT_POPUP_CVV_LINK . '</a>',
			//													'field' => tep_draw_input_field('usaepay_cc_cvv', '', "size=4, maxlength=4"))

		} else {
			$selection = array('id' => $this->code,
				'module' => $this->title);
		}

		return $selection;
	}

	/*
	 * Validate credit card data (if not using the paymentform)
	 */
	function pre_confirmation_check() {
		global $_POST;

		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
			include(DIR_WS_CLASSES . 'cc_validation.php');

			$cc_validation = new cc_validation();
			$result = $cc_validation->validate($_POST['usaepay_cc_number'], $_POST['usaepay_cc_expires_month'], $_POST['usaepay_cc_expires_year']);

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
				$payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&usaepay_cc_owner=' . urlencode($_POST['usaepay_cc_owner']) . '&usaepay_cc_expires_month=' . $_POST['usaepay_cc_expires_month'] . '&usaepay_cc_expires_year=' . $_POST['usaepay_cc_expires_year'];
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
			}

			$this->cc_card_type = $cc_validation->cc_type;
			$this->cc_card_number = $cc_validation->cc_number;
			$this->cc_expiry_month = $cc_validation->cc_expiry_month;
			$this->cc_expiry_year = $cc_validation->cc_expiry_year;
		} else {
			return false;
		}
	}

	/*
	 * Build list of fields to display on the checkout confirmation page
	 */
	function confirmation() {
		global $_POST, $order;

		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
			$confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
											'fields' => array(array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_OWNER,
															'field' => $order->billing['firstname'] . ' ' . $order->billing['lastname']),
														array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_NUMBER,
															'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
														array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_EXPIRES,
															'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['usaepay_cc_expires_month'], 1, '20' . $_POST['usaepay_cc_expires_year']))),
														array('title' => MODULE_PAYMENT_USAEPAY_TEXT_CVV,
															'field' => ($_POST['usaepay_cc_cvv']?'XXX':'-'))
														),
										);

			return $confirmation;
		} else {
			return false;
		}
	}

	/*
	 * Build fields for final checkout screen
	 */
	function process_button() {
		global $_SERVER, $order, $currencies, $customer_id;


		// If we are in local mode,  then render hidden fields for payment data
		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {
			$process_button_string = tep_draw_hidden_field('cc_owner', $_POST['usaepay_cc_owner']) .
				tep_draw_hidden_field('cc_expires', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
				tep_draw_hidden_field('cc_type', $this->cc_card_type) .
				tep_draw_hidden_field('cc_number', $this->cc_card_number).
				tep_draw_hidden_field('cc_cvv', $_POST['usaepay_cc_cvv']);
			$process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

		// payment form mode,  render fields required for payment form
		}else {

			if (MODULE_PAYMENT_USAEPAY_TRANSACTION_TYPE == 'True') {
				$transaction_command = 'authonly';
			} else {
				$transaction_command = 'sale';
			}

			$last_order_id_res = tep_db_query("select * from " . TABLE_ORDERS . " order by orders_id desc limit 1");
			$last_order_id = tep_db_fetch_array($last_order_id_res);
			$new_order_id = $last_order_id['orders_id'];
			$new_order_id = ($new_order_id + 1);

			$process_button_string = tep_draw_hidden_field('UMkey', MODULE_PAYMENT_USAEPAY_KEY) .
				tep_draw_hidden_field('UMamount', number_format($order->info['total'],2)) .
				tep_draw_hidden_field('UMredirApproved', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true)) .
				tep_draw_hidden_field('UMredirDeclined', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code, 'NONSSL', true)) .
				tep_draw_hidden_field('UMname', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
				tep_draw_hidden_field('UMstreet', $order->billing['street_address']) .
				tep_draw_hidden_field('UMzip', $order->billing['postcode']);

			// optional pin
			if(strlen(MODULE_PAYMENT_USAEPAY_PIN))
			{
				$key=mktime();
				$process_button_string .= tep_draw_hidden_field('UMmd5hash', md5($transaction_command . ":" . MODULE_PAYMENT_USAEPAY_PIN . ":" . number_format($order->info['total'],2). ":" . $new_order_id . ":" . $key));
				$process_button_string .= tep_draw_hidden_field('UMmd5key', $key);
			}

			// billing fields
			$process_button_string .= tep_draw_hidden_field('UMbillfname', $order->billing['firstname']) .
				tep_draw_hidden_field('UMbilllname', $order->billing['lastname']) .
				tep_draw_hidden_field('UMbillstreet', $order->billing['street_address']) .
				tep_draw_hidden_field('UMbillcity', $order->billing['city']);

			if ($order->billing['country']['iso_code_2'] == 'US') {
				$billing_state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_id = '" . (int)$order->billing['zone_id'] . "'");
				$billing_state = tep_db_fetch_array($billing_state_query);
				$process_button_string .= tep_draw_hidden_field('UMbillstate', $billing_state['zone_code']);
			} else {
				$process_button_string .= tep_draw_hidden_field('UMbillstate', $order->billing['state']);
			}

			$process_button_string .= tep_draw_hidden_field('UMbillzip', $order->billing['postcode']) .
				tep_draw_hidden_field('UMbillcountry', $order->billing['country']['iso_code_2']) .
				tep_draw_hidden_field('UMbillphone', $order->customer['telephone']) .
				tep_draw_hidden_field('UMcustemail', $order->customer['email_address']) .
				tep_draw_hidden_field('UMshipfname', $order->delivery['firstname']) .
				tep_draw_hidden_field('UMshiplname', $order->delivery['lastname']) .
				tep_draw_hidden_field('UMshipstreet', $order->delivery['street_address']) .
				tep_draw_hidden_field('UMshipcity', $order->delivery['city']) .
				tep_draw_hidden_field('UMshipstate', $order->delivery['state']) .
				tep_draw_hidden_field('UMshipzip', $order->delivery['postcode']) .
				tep_draw_hidden_field('UMshipcountry', $order->delivery['country']['iso_code_2']) .
				tep_draw_hidden_field('UMcustreceipt', (MODULE_PAYMENT_USAEPAY_EMAIL_CUSTOMER=='True'?"Yes":"No")) .
				tep_draw_hidden_field('UMcommand', $transaction_command) .
				tep_draw_hidden_field('UMinvoice', $new_order_id) .
				tep_draw_hidden_field('UMorderid', $new_order_id) .
				tep_draw_hidden_field('UMcustid', $customer_id) .
				tep_draw_hidden_field('UMdescription', 'osCommerce Order') .
				tep_draw_hidden_field('UMsoftware', 'osCommerce') .
				tep_draw_hidden_field('UMip', $_SERVER['REMOTE_ADDR']);

		}
		return $process_button_string;
	}

	/*
	 * Send transaction to usaepay (if we are in local mode)
	 */
	function before_process() {
		global $order, $messageStack, $customer_id;

		if (MODULE_PAYMENT_USAEPAY_INPUT_MODE == 'Local') {

			$order->info['cc_expires'] = $_POST['cc_expires'];
			$order->info['cc_type'] = $_POST['cc_type'];
			$order->info['cc_owner'] = ($order->billing['firstname'] . ' ' . $order->billing['lastname']);
			$order->info['cc_number'] = substr($_POST['cc_number'],0,4) . 'XXXXXXXX' . substr($_POST['cc_number'],-4);
			$order->info['cc_cvv'] = 'XXX';

			// Calculate the next expected order id
			$last_order_id_res = tep_db_query("select * from " . TABLE_ORDERS . " order by orders_id desc limit 1");
			$last_order_id = tep_db_fetch_array($last_order_id_res);
			$new_order_id = $last_order_id['orders_id'];
			$new_order_id = ($new_order_id + 1);

			// Instantiate USAePay class
			$tran = new umTransaction;

			// Basic set of parameters
			$tran->key=			(MODULE_PAYMENT_USAEPAY_KEY); // USA ePay Source Key
			$tran->pin=			(MODULE_PAYMENT_USAEPAY_PIN); // USA ePay Source Key Pin
			$tran->command=	(MODULE_PAYMENT_USAEPAY_TRANSACTION_TYPE == 'True'? 'authonly': 'sale');
			$tran->ip=			tep_get_ip_address(); // IP address of client, used for fraud checks
			$tran->software=	"osCommerce";
			$tran->usesandbox=(MODULE_PAYMENT_USAEPAY_TRANSACTION_MODE == 'Sandbox' ? true : false );

			// Credit Card Data
			$tran->card			= ($_POST['cc_number']);
			$tran->exp			= ($_POST['cc_expires']);
			$tran->cvv2			= ($_POST['cc_cvv']);
			$tran->cardholder	= ($order->billing['firstname'] . ' ' . $order->billing['lastname']);
			$tran->street		= ($order->billing['street_address']);
			$tran->zip			= ($order->billing['postcode']);

			// Amounts   - Subtotal is comented out because oscommerce is susceptable to the php currency rounding bug which could cause the transaction to go out of balance
			//$tran->subtotal	= $order->info['subtotal'];
			$tran->tax			= number_format($order->info['tax'],2,'.','');
			$tran->shipping	= $order->info['shipping_cost'];
			if(@$order->info['coupon_code']) {
				$tran->discount= number_format(($order->info['shipping_cost']+$order->info['tax']+$order->info['subtotal'])- $order->info['total'],2,'.','');
			}
			$tran->amount		= (number_format($order->info['total'], 2));

			// Order details
			$tran->invoice			= ($new_order_id);
			$tran->orderid			= ($new_order_id);
			$tran->custnum			= $customer_id;
			$tran->description	= (STORE_NAME);
			$tran->comments		= (@$order->info['coupon_code']?'Coupon Code: ' . $order->info['coupon_code'] . "\nCustomer Comments: ":'') .$order->info['comments'];

			// Line Items
			foreach($order->products as $lineitem) {
				$description='';
				if (isset($lineitem['attributes'])) {
					foreach($lineitem['attributes'] as $attr)
						$attrs[] = $attr['option'] . ': ' . $attr['value'];
				}
				$description = implode("\n", $attrs);
				$tran->addLine($lineitem['model'], $lineitem['name'], $description, $lineitem['price'], $lineitem['qty'], (@$lineitem['tax']>0?'Y':'N'));
			}

			$tran->custemail	= ($order->customer['email_address']);
			$tran->custreceipt= (MODULE_PAYMENT_USAEPAY_API_EMAIL_CUSTOMER == 'True' ? 'true': 'false'); // send an email to customer from USA ePay ?

			// Electronic Check Transaction Parameters - Future Development
			//        $tran->routing=($_POST['echeck_routing']); // Bank Routing Number
			//        $tran->account=($_POST['echeck_account_number']); // Bank Account Number
			//        $tran->ssn=($_POST['customer_ssn']); // Customer Social Security Number - Required for echecks
			//        $tran->dlnum=($_POST['customer_dlnum']); // Customer Drivers License Number
			//        $tran->dlstate=($_POST['customer_dlstate']); // Customer Drivers License Issuing State

			// Billing Information
			$tran->billfname=($order->billing['firstname']);
			$tran->billlname=($order->billing['lastname']);
			$tran->billcompany=($order->billing['company']);
			$tran->billstreet=($order->billing['street_address']);
			$tran->billstreet2=($order->billing['suburb']);
			$tran->billcity=($order->billing['city']);
			$tran->billstate=($order->billing['state']);
			$tran->billzip=($order->billing['postcode']);
			$tran->billcountry=($order->billing['country']['title']);
			$tran->billphone=($order->customer['telephone']);
			$tran->email=($order->customer['email_address']);

			// Shipping Information
			$tran->shipfname=($order->delivery['firstname']);
			$tran->shiplname=($order->delivery['lastname']);
			$tran->shipcompany=($order->delivery['company']);
			$tran->shipstreet=($order->delivery['street_address']);
			$tran->shipstreet2=($order->delivery['suburb']);
			$tran->shipcity=($order->delivery['city']);
			$tran->shipstate=($order->delivery['state']);
			$tran->shipzip=($order->delivery['postcode']);
			$tran->shipcountry=($order->delivery['country']['title']);
			$tran->shipphone=($order->customer['telephone']);

			$usaepay_error = (!($tran->Process()));

			$result = $tran->result;
			$resultcode = $tran->resultcode;
			$authcode = $tran->authcode;
			$refnum = $tran->refnum;
			$batch = $tran->batch;
			$avs_result = $tran->avs_result;
			$cvv2_result = $tran->cvv2_result;
			$error = $tran->error;
			$errorcode = $tran->errorcode;
			$curlerror = $tran->curlerror;
			$acsurl = $tran->acsurl;
			$pareq = $tran->pareq;
			$response_msg_to_customer  = $reason = $tran->error;


			//print_r($tran);
			//print_r($order);

			//die('Process this!');

			// DATABASE SECTION
			// Insert the send and receive response data into the database.
			// This can be used for testing or for implementation in other applications
			// This can be turned on and off if the Admin Section
			if (MODULE_PAYMENT_USAEPAY_API_STORE_DATA == 'True') {

				// Insert the data into the database
				tep__db_query("insert into " . TABLE_USAEPAY_API . "  (id, customer_id, order_id, result_code, auth_code,
					ref_num, batch, avs_result, cvv2_result, error,
					error_code, curl_error, acs_url, pareq, reason)
					values ('', '" . $_SESSION['customer_id'] . "',
					'" . $new_order_id . "', '" . $resultcode . "',
					'" . $authcode . "', '" . $refnum . "', '" . $batch . "',
					'" . $avs_result . "', '" . $cvv2_result . "', '" . $error . "',
					'" . $errorcode . "', '" . $curlerror . "', '" . $acsurl . "',
					'" . $pareq . "', '" . $reason . "')");
			}

			if ($usaepay_error) {
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . rawurlencode($error), 'SSL'));

			}

		} else {
			return false;
		}
	}

	function after_process() {
		return false;
	}

	function get_error() {
		global $_GET;

		if (isset($_GET['UMerror']) && tep_not_null($_GET['UMerror'])) {
			$error = stripslashes(urldecode($_GET['UMerror']));
		} else if (isset($_GET['error']) && tep_not_null($_GET['error'])) {
			$error = stripslashes(urldecode($_GET['error']));
		} else {
			$error = MODULE_PAYMENT_USAEPAY_TEXT_ERROR_MESSAGE;
		}

		return array('title' => MODULE_PAYMENT_USAEPAY_TEXT_ERROR,
			'error' => $error);
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_USAEPAY_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {

		$install = array(
			"('Enable USAePay Module', 'MODULE_PAYMENT_USAEPAY_STATUS', 'True', 'Do you want to accept USAePay payments?', '6', '0', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())",
			"('Source Key', 'MODULE_PAYMENT_USAEPAY_KEY', 'Ll6l5j0Yq0HmNOs55kx0nS87pWqcU5aA', 'The source key created in USAePay console:', '6', '0', NULL, NULL, now())",
			"('Source Pin', 'MODULE_PAYMENT_USAEPAY_PIN', '1234321', 'The source pin used to create verify transaction authenticity (must match pin setup in console):', '6', '0', NULL, NULL, now())",
			"('Transaction Mode', 'MODULE_PAYMENT_USAEPAY_TRANSACTION_MODE', 'Production', 'USAePay server to use. (Please note that you must have a sandbox account,  and you must use a source key created on the sandbox. For more information, <a href=\"http://help.usaepay.com/developer/sandbox\">see the sandbox help page</a>', '6', '0', NULL, 'tep_cfg_select_option(array(\'Production\', \'Sandbox\'), ', now())",
			"('Transaction Method', 'MODULE_PAYMENT_USAEPAY_METHOD', 'Credit Card', 'Transaction method used for processing orders', '6', '0', NULL, 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ', now())",
			"('Queue Transaction', 'MODULE_PAYMENT_USAEPAY_TRANSACTION_TYPE', 'False', 'Should transactions be queued for your review at USAePay? ', '6', '4', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())",
			"('Credit Card Collection', 'MODULE_PAYMENT_USAEPAY_INPUT_MODE', 'Local', 'Should the credit card details be collected locally or remotely using the USAePay Payment Form?', '6', '5', NULL, 'tep_cfg_select_option(array(\'Local\', \'PayForm\'), ', now())",
			"('Customer Notifications', 'MODULE_PAYMENT_USAEPAY_EMAIL_CUSTOMER', 'False', 'Should USAePay e-mail a receipt to the customer?', '6', '0', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())",
			"('Sort order of display.', 'MODULE_PAYMENT_USAEPAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', NULL, NULL, now())",
			"('Payment Zone', 'MODULE_PAYMENT_USAEPAY_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())",
			"('Set Order Status', 'MODULE_PAYMENT_USAEPAY_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(',  now())"
		);
		foreach($install as $line) {
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function,  date_added) values $line");
		}

	}


	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		return array('MODULE_PAYMENT_USAEPAY_STATUS', 'MODULE_PAYMENT_USAEPAY_KEY', 'MODULE_PAYMENT_USAEPAY_PIN', 'MODULE_PAYMENT_USAEPAY_TRANSACTION_MODE', 'MODULE_PAYMENT_USAEPAY_METHOD','MODULE_PAYMENT_USAEPAY_TRANSACTION_TYPE', 'MODULE_PAYMENT_USAEPAY_INPUT_MODE', 'MODULE_PAYMENT_USAEPAY_EMAIL_CUSTOMER', 'MODULE_PAYMENT_USAEPAY_ZONE', 'MODULE_PAYMENT_USAEPAY_ORDER_STATUS_ID', 'MODULE_PAYMENT_USAEPAY_SORT_ORDER');
	}

}


class umTransaction {

	// Required for all transactions
	var $key;			// Source key
	var $pin;			// Source pin (optional)
	var $amount;		// the entire amount that will be charged to the customers card
						// (including tax, shipping, etc)
	var $invoice;	// invoice number.  must be unique.  limited to 10 digits.  use orderid if you need longer.

	// Required for Commercial Card support
	var $ponum;			// Purchase Order Number
	var $tax;			// Tax

	// Amount details (optional)
	var $tip; 			// Tip
	var $shipping;		// Shipping charge
	var $discount; 		// Discount amount (ie gift certificate or coupon code)
	var $subtotal; 		// if subtotal is set, then
						// subtotal + tip + shipping - discount + tax must equal amount
						// or the transaction will be declined.  If subtotal is left blank
						// then it will be ignored
	var $currency;		// Currency of $amount

	// Required Fields for Card Not Present transacitons (Ecommerce)
	var $card;			// card number, no dashes, no spaces
	var $exp;			// expiration date 4 digits no /
	var $cardholder; 	// name of card holder
	var $street;		// street address
	var $zip;			// zip code

	// Fields for Card Present (POS)
	var $magstripe;  	// mag stripe data.  can be either Track 1, Track2  or  Both  (Required if card,exp,cardholder,street and zip aren't filled in)
	var $cardpresent;   // Must be set to true if processing a card present transaction  (Default is false)
	var $termtype;  	// The type of terminal being used:  Optons are  POS - cash register, StandAlone - self service terminal,  Unattended - ie gas pump, Unkown  (Default:  Unknown)
	var $magsupport;  	// Support for mag stripe reader:   yes, no, contactless, unknown  (default is unknown unless magstripe has been sent)
	var $contactless;  	// Magstripe was read with contactless reader:  yes, no  (default is no)
	var $dukpt;			// DUK/PT for PIN Debit
	var $signature;     // Signature Capture data

	// fields required for check transactions
	var $account;		// bank account number
	var $routing;		// bank routing number
	var $ssn;			// social security number
	var $dlnum;			// drivers license number (required if not using ssn)
	var $dlstate;		// drivers license issuing state
	var $checknum;		// Check Number
	var $accounttype;       // Checking or Savings
	var $checkformat;	// Override default check record format
	var $checkimage_front;    // Check front
	var $checkimage_back;		// Check back


	// Fields required for Secure Vault Payments (Direct Pay)
	var $svpbank;		// ID of cardholders bank
	var $svpreturnurl;	// URL that the bank should return the user to when tran is completed
	var $svpcancelurl; 	// URL that the bank should return the user if they cancel



	// Option parameters
	var $origauthcode;	// required if running postauth transaction.
	var $command;		// type of command to run; Possible values are:
						// sale, credit, void, preauth, postauth, check and checkcredit.
						// Default is sale.
	var $orderid;		// Unique order identifier.  This field can be used to reference
						// the order for which this transaction corresponds to. This field
						// can contain up to 64 characters and should be used instead of
						// UMinvoice when orderids longer that 10 digits are needed.
	var $custid;   // Alpha-numeric id that uniquely identifies the customer.
	var $description;	// description of charge
	var $cvv2;			// cvv2 code
	var $custemail;		// customers email address
	var $custreceipt;	// send customer a receipt
	var $ignoreduplicate; // prevent the system from detecting and folding duplicates
	var $ip;			// ip address of remote host
	var $testmode;		// test transaction but don't process it
	var $usesandbox;    // use sandbox server instead of production
	var $timeout;       // transaction timeout.  defaults to 45 seconds
	var $gatewayurl;   	// url for the gateway
	var $proxyurl;		// proxy server to use (if required by network)
	var $ignoresslcerterrors;  // Bypasses ssl certificate errors.  It is highly recommended that you do not use this option.  Fix your openssl installation instead!
	var $cabundle;      // manually specify location of root ca bundle (useful of root ca is not in default location)
	var $transport;     // manually select transport to use (curl or stream), by default the library will auto select based on what is available

	// Card Authorization - Verified By Visa and Mastercard SecureCode
	var $cardauth;    	// enable card authentication
	var $pares; 		//

	// Third Party Card Authorization
	var $xid;
	var $cavv;
	var $eci;

	// Recurring Billing
	var $recurring;		//  Save transaction as a recurring transaction:  yes/no
	var $schedule;		//  How often to run transaction: daily, weekly, biweekly, monthly, bimonthly, quarterly, annually.  Default is monthly.
	var $numleft; 		//  The number of times to run. Either a number or * for unlimited.  Default is unlimited.
	var $start;			//  When to start the schedule.  Default is tomorrow.  Must be in YYYYMMDD  format.
	var $end;			//  When to stop running transactions. Default is to run forever.  If both end and numleft are specified, transaction will stop when the ealiest condition is met.
	var $billamount;	//  Optional recurring billing amount.  If not specified, the amount field will be used for future recurring billing payments
	var $billtax;
	var $billsourcekey;

	// Billing Fields
	var $billfname;
	var $billlname;
	var $billcompany;
	var $billstreet;
	var $billstreet2;
	var $billcity;
	var $billstate;
	var $billzip;
	var $billcountry;
	var $billphone;
	var $email;
	var $fax;
	var $website;

	// Shipping Fields
	var $delivery;		// type of delivery method ('ship','pickup','download')
	var $shipfname;
	var $shiplname;
	var $shipcompany;
	var $shipstreet;
	var $shipstreet2;
	var $shipcity;
	var $shipstate;
	var $shipzip;
	var $shipcountry;
	var $shipphone;

	// Custom Fields
	var $custom1;
	var $custom2;
	var $custom3;
	var $custom4;
	var $custom5;
	var $custom6;
	var $custom7;
	var $custom8;
	var $custom9;
	var $custom10;
	var $custom11;
	var $custom12;
	var $custom13;
	var $custom14;
	var $custom15;
	var $custom16;
	var $custom17;
	var $custom18;
	var $custom19;
	var $custom20;


	// Line items  (see addLine)
	var $lineitems;


	var $comments; // Additional transaction details or comments (free form text field supports up to 65,000 chars)

	var $software; // Allows developers to identify their application to the gateway (for troubleshooting purposes)


	// response fields
	var $rawresult;		// raw result from gateway
	var $result;		// full result:  Approved, Declined, Error
	var $resultcode; 	// abreviated result code: A D E
	var $authcode;		// authorization code
	var $refnum;		// reference number
	var $batch;		// batch number
	var $avs_result;		// avs result
	var $avs_result_code;		// avs result
	var $avs;  					// obsolete avs result
	var $cvv2_result;		// cvv2 result
	var $cvv2_result_code;		// cvv2 result
	var $vpas_result_code;      // vpas result
	var $isduplicate;      // system identified transaction as a duplicate
	var $convertedamount;  // transaction amount after server has converted it to merchants currency
	var $convertedamountcurrency;  // merchants currency
	var $conversionrate;  // the conversion rate that was used
	var $custnum;  //  gateway assigned customer ref number for recurring billing

	// Cardinal Response Fields
	var $acsurl;	// card auth url
	var $pareq;		// card auth request
	var $cctransid; // cardinal transid


	// Errors Response Feilds
	var $error; 		// error message if result is an error
	var $errorcode; 	// numerical error code
	var $blank;			// blank response
	var $transporterror; 	// transport error


	// Constructor
	function umTransaction()
	{
		// Set default values.
		$this->command="sale";
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Transaction not processed yet.";
		$this->timeout=45;
		$this->cardpresent=false;
		$this->lineitems = array();
		if(isset($_SERVER['REMOTE_ADDR'])) $this->ip=$_SERVER['REMOTE_ADDR'];
		$this->software="USAePay PHP API v" . USAEPAY_VERSION;

	}

	/**
	 * Add a line item to the transaction
	 *
	 * @param string $sku
	 * @param string $name
	 * @param string $description
	 * @param double $cost
	 * @param string $taxable
	 * @param int $qty
	 */
	function addLine($sku, $name, $description, $cost, $qty, $taxable)
	{
		$this->lineitems[] = array(
				'sku' => $sku,
				'name' => $name,
				'description' => $description,
				'cost' => $cost,
				'taxable' => $taxable,
				'qty' => $qty
			);
	}

	function clearLines()
	{
		$this->lineitems=array();
	}


	/**
	 * Verify that all required data has been set
	 *
	 * @return string
	 */
	function CheckData()
	{
		if(!$this->key) return "Source Key is required";
		if(in_array(strtolower($this->command), array("cc:capture", "cc:refund", "refund", "check:refund","capture", "creditvoid")))
		{
			if(!$this->refnum) return "Reference Number is required";
		}else if(in_array(strtolower($this->command), array("svp")))
		{
			if(!$this->svpbank) return "Bank ID is required";
			if(!$this->svpreturnurl) return "Return URL is required";
			if(!$this->svpcancelurl) return "Cancel URL is required";
		}  else {
			if(in_array(strtolower($this->command), array("check:sale","check:credit", "check", "checkcredit","reverseach") )) {
					if(!$this->account) return "Account Number is required";
					if(!$this->routing) return "Routing Number is required";
			} else {
				if(!$this->magstripe) {
					if(!$this->card) return "Credit Card Number is required ({$this->command})";
					if(!$this->exp) return "Expiration Date is required";
				}
			}
			$this->amount=preg_replace("/[^[:digit:].]/","",$this->amount);
			if(!$this->amount) return "Amount is required";
			if(!$this->invoice && !$this->orderid) return "Invoice number or Order ID is required";
			if(!$this->magstripe) {
				//if(!$this->cardholder) return "Cardholder Name is required";
				//if(!$this->street) return "Street Address is required";
				//if(!$this->zip) return "Zipcode is required";
			}
		}
		return 0;
	}

	/**
	 * Send transaction to the USAePay Gateway and parse response
	 *
	 * @return boolean
	 */
	function Process()
	{
		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}

		// format the data
		$data =
			array("UMkey" => $this->key,
			"UMcommand" => $this->command,
			"UMauthCode" => $this->origauthcode,
			"UMcard" => $this->card,
			"UMexpir" => $this->exp,
			"UMbillamount" => $this->billamount,
			"UMamount" => $this->amount,
			"UMinvoice" => $this->invoice,
			"UMorderid" => $this->orderid,
			"UMponum" => $this->ponum,
			"UMtax" => $this->tax,
			"UMtip" => $this->tip,
			"UMshipping" => $this->shipping,
			"UMdiscount" => $this->discount,
			"UMsubtotal" => $this->subtotal,
			"UMcurrency" => $this->currency,
			"UMname" => $this->cardholder,
			"UMstreet" => $this->street,
			"UMzip" => $this->zip,
			"UMdescription" => $this->description,
			"UMcomments" => $this->comments,
			"UMcvv2" => $this->cvv2,
			"UMip" => $this->ip,
			"UMtestmode" => $this->testmode,
			"UMcustemail" => $this->custemail,
			"UMcustreceipt" => $this->custreceipt,
			"UMrouting" => $this->routing,
			"UMaccount" => $this->account,
			"UMssn" => $this->ssn,
			"UMdlstate" => $this->dlstate,
			"UMdlnum" => $this->dlnum,
			"UMchecknum" => $this->checknum,
			"UMaccounttype" => $this->accounttype,
			"UMcheckformat" => $this->checkformat,
			"UMcheckimagefront" => base64_encode($this->checkimage_front),
			"UMcheckimageback" => base64_encode($this->checkimage_back),
			"UMcheckimageencoding" => 'base64',
			"UMrecurring" => $this->recurring,
			"UMbillamount" => $this->billamount,
			"UMbilltax" => $this->billtax,
			"UMschedule" => $this->schedule,
			"UMnumleft" => $this->numleft,
			"UMstart" => $this->start,
			"UMexpire" => $this->end,
			"UMbillsourcekey" => ($this->billsourcekey?"yes":""),
			"UMbillfname" => $this->billfname,
			"UMbilllname" => $this->billlname,
			"UMbillcompany" => $this->billcompany,
			"UMbillstreet" => $this->billstreet,
			"UMbillstreet2" => $this->billstreet2,
			"UMbillcity" => $this->billcity,
			"UMbillstate" => $this->billstate,
			"UMbillzip" => $this->billzip,
			"UMbillcountry" => $this->billcountry,
			"UMbillphone" => $this->billphone,
			"UMemail" => $this->email,
			"UMfax" => $this->fax,
			"UMwebsite" => $this->website,
			"UMshipfname" => $this->shipfname,
			"UMshiplname" => $this->shiplname,
			"UMshipcompany" => $this->shipcompany,
			"UMshipstreet" => $this->shipstreet,
			"UMshipstreet2" => $this->shipstreet2,
			"UMshipcity" => $this->shipcity,
			"UMshipstate" => $this->shipstate,
			"UMshipzip" => $this->shipzip,
			"UMshipcountry" => $this->shipcountry,
			"UMshipphone" => $this->shipphone,
			"UMcardauth" => $this->cardauth,
			"UMpares" => $this->pares,
			"UMxid" => $this->xid,
			"UMcavv" => $this->cavv,
			"UMeci" => $this->eci,
			"UMcustid" => $this->custid,
			"UMcardpresent" => ($this->cardpresent?"1":"0"),
			"UMmagstripe" => $this->magstripe,
			"UMdukpt" => $this->dukpt,
			"UMtermtype" => $this->termtype,
			"UMmagsupport" => $this->magsupport,
			"UMcontactless" => $this->contactless,
			"UMsignature" => $this->signature,
			"UMsoftware" => $this->software,
			"UMignoreDuplicate" => $this->ignoreduplicate,
			"UMrefNum" => $this->refnum);

		// tack on custom fields
		for($i=1; $i<=20; $i++)
		{
			if($this->{"custom$i"}) $data["UMcustom$i"] = $this->{"custom$i"};
		}

		// tack on line level detail
		$c=1;
		foreach($this->lineitems as $lineitem)
		{
			$data["UMline{$c}sku"] = $lineitem['sku'];
			$data["UMline{$c}name"] = $lineitem['name'];
			$data["UMline{$c}description"] = $lineitem['description'];
			$data["UMline{$c}cost"] = $lineitem['cost'];
			$data["UMline{$c}taxable"] = $lineitem['taxable'];
			$data["UMline{$c}qty"] = $lineitem['qty'];
			$c++;
		}

		// Create hash if pin has been set.
		if(trim($this->pin))
		{
			// generate random seed value
			$seed = microtime(true) . rand();

			// assemble prehash data
			$prehash = $this->command . ":" . trim($this->pin) . ":" . $this->amount . ":" . $this->invoice . ":" . $seed;

			// if sha1 is available,  create sha1 hash,  else use md5
			if(function_exists('sha1')) $hash = 's/' . $seed . '/' . sha1($prehash) . '/n';
			else $hash = 'm/' . $seed . '/' . md5($prehash) . '/n';

			// populate hash value
			$data['UMhash'] = $hash;
		}

		// Figure out URL
		$url = ($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/gate");
		if($this->usesandbox) $url = "https://sandbox.usaepay.com/gate";

		// Post data to Gateway
		$result=$this->httpPost($url, $data);
		if($result===false) return false;

		// result is in urlencoded format, parse into an array
		parse_str($result,$tmp);

		// check to make sure we received the correct fields
		if(!isset($tmp["UMversion"]) || !isset($tmp["UMstatus"]))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error parsing data from card processing gateway.";
			$this->errorcode=10132;
			return false;
		}

		// Store results
		$this->result=(isset($tmp["UMstatus"])?$tmp["UMstatus"]:"Error");
		$this->resultcode=(isset($tmp["UMresult"])?$tmp["UMresult"]:"E");
		$this->authcode=(isset($tmp["UMauthCode"])?$tmp["UMauthCode"]:"");
		$this->refnum=(isset($tmp["UMrefNum"])?$tmp["UMrefNum"]:"");
		$this->batch=(isset($tmp["UMbatch"])?$tmp["UMbatch"]:"");
		$this->avs_result=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->avs_result_code=(isset($tmp["UMavsResultCode"])?$tmp["UMavsResultCode"]:"");
		$this->cvv2_result=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");
		$this->cvv2_result_code=(isset($tmp["UMcvv2ResultCode"])?$tmp["UMcvv2ResultCode"]:"");
		$this->vpas_result_code=(isset($tmp["UMvpasResultCode"])?$tmp["UMvpasResultCode"]:"");
		$this->convertedamount=(isset($tmp["UMconvertedAmount"])?$tmp["UMconvertedAmount"]:"");
		$this->convertedamountcurrency=(isset($tmp["UMconvertedAmountCurrency"])?$tmp["UMconvertedAmountCurrency"]:"");
		$this->conversionrate=(isset($tmp["UMconversionRate"])?$tmp["UMconversionRate"]:"");
		$this->error=(isset($tmp["UMerror"])?$tmp["UMerror"]:"");
		$this->errorcode=(isset($tmp["UMerrorcode"])?$tmp["UMerrorcode"]:"10132");
		$this->custnum=(isset($tmp["UMcustnum"])?$tmp["UMcustnum"]:"");

		// Obsolete variable (for backward compatibility) At some point they will no longer be set.
		$this->avs=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->cvv2=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");


		if(isset($tmp["UMcctransid"])) $this->cctransid=$tmp["UMcctransid"];
		if(isset($tmp["UMacsurl"])) $this->acsurl=$tmp["UMacsurl"];
		if(isset($tmp["UMpayload"])) $this->pareq=$tmp["UMpayload"];

		if($this->resultcode == "A") return true;
		return false;

	}

	function buildQuery($data)
	{
		if(function_exists('http_build_query')) return http_build_query($data);

		$tmp=array();
		foreach($data as $key=>$val) $tmp[] = rawurlencode($key) . '=' . rawurlencode($val);

		return implode('&', $tmp);

	}

	function httpPost($url, $data)
	{
		// if transport was not specified,  auto select transport
		if(!$this->transport)
		{
			if(function_exists("curl_version")) {
				$this->transport='curl';
			} else if(function_exists('stream_get_wrappers'))  {
				if(in_array('https',stream_get_wrappers())){
					$this->transport='stream';
				}
			}
		}


		// Use selected transport to post request to the gateway
		switch($this->transport)
		{
			case 'curl': return $this->httpPostCurl($url, $data);
			case 'stream': return $this->httpPostPHP($url, $data);
		}

		// No HTTPs libraries found,  return error
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Libary Error: SSL HTTPS support not found";
		$this->errorcode=10130;
		return false;
	}

	function httpPostCurl($url, $data)
	{

		//init the connection
		$ch = curl_init($url);
		if(!is_resource($ch))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Libary Error: Unable to initialize CURL ($ch)";
			$this->errorcode=10131;
			return false;
		}

		// set some options for the connection
		curl_setopt($ch,CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, ($this->timeout>0?$this->timeout:45));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

		// Bypass ssl errors - A VERY BAD IDEA
		if($this->ignoresslcerterrors)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		// apply custom ca bundle location
		if($this->cabundle)
		{
			curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);
		}

		// set proxy
		if($this->proxyurl)
		{
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, $this->proxyurl);
		}

		// rawurlencode data
		$data = $this->buildQuery($data);

		// attach the data
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

		// run the transfer
		$result=curl_exec ($ch);

		//get the result and parse it for the response line.
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			$this->transporterror=$this->curlerror=curl_error($ch);
			curl_close ($ch);
			return false;
		}
		curl_close ($ch);
		$this->rawresult=$result;

		if(!$result) {
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Blank response from card processing gateway.";
			$this->errorcode=10132;
			return false;
		}

		// result will be on the last line of the return
		$tmp=explode("\n",$result);
		$result=$tmp[count($tmp)-1];

		return $result;
	}

	function httpPostPHP($url, $data)
	{


		// rawurlencode data
		$data = $this->buildQuery($data);

		// set stream http options
		$options = array(
			'http'=> array(
				'method'=>'POST',
	            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
	                . "Content-Length: " . strlen($data) . "\r\n",
	            'content' => $data,
	            'timeout' => ($this->timeout>0?$this->timeout:45),
	            'user_agent' => 'uePHPLibary v' . USAEPAY_VERSION . ($this->software?'/' . $this->software:'')
	        ),
	        'ssl' => array(
	            'verify_peer' => ($this->ignoresslcerterrors?false:true),
	            'allow_self_signed' => ($this->ignoresslcerterrors?true:false)
			)
		);

		if($this->cabundle) $options['ssl']['cafile'] = $this->cabundle;

		if(trim($this->proxyurl)) $options['http']['proxy'] = $this->proxyurl;


		// create stream context
		$context = stream_context_create($options);

		// post data to gateway
		$fd = fopen($url, 'r', null, $context);
		if(!$fd)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Unable to open connection to gateway";
			$this->errorcode=10132;
			$this->blank=1;
			if(function_exists('error_get_last')) {
				$err=error_get_last();
				$this->transporterror=$err['message'];
			} else if(isset($GLOBALS['php_errormsg'])) {
				$this->transporterror=$GLOBALS['php_errormsg'];
			}
			//curl_close ($ch);
			return false;
		}

		// pull result
		$result = stream_get_contents($fd);

		// check for a blank response
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			fclose($fd);
			//$this->curlerror=curl_error($ch);
			//curl_close ($ch);
			return false;
		}

		fclose($fd);
		return $result;

	}
}





//  umVerifyCreditCardNumber
//  Validates a credit card and returns the type of card.
//
//	Card Types:
//		 1	Mastercard
//		 2	Visa
//		 3	American Express
//		 4	Diners Club/Carte Blanche
//		10	Discover
//		20	enRoute
//		28	JCB


/**
 * Evaluates a creditcard number and if valid, returns the card type code
 *
 * @param ccnum string
 * @return int
 */
function umVerifyCreditCardNumber($ccnum)
{
	global $umErrStr;


	//okay lets do the stupid
	$ccnum=str_replace("-","",$ccnum);
	$ccnum=str_replace(" ","",$ccnum);
	$ccnum=str_replace("/","",$ccnum);

	if(!preg_match('/^[[:digit:]]{1,200}$/', $ccnum)) {$umErrStr="Cardnumber contains characters that are not numbers";  return 0;}
	if(!preg_match('/^[[:digit:]]{13,16}$/', $ccnum)) {$umErrStr="Cardnumber is not between 13 and 16 digits long";  return 0;}


	// Run Luhn Mod-10 to ensure proper check digit
	$total=0;
	$y=0;
	for($i=strlen($ccnum)-1; $i >= 0; $i--)
	{
		if($y==1) $y=2; else $y=1;         //multiply every other digit by 2
		$tmp=substr($ccnum,$i,1)*$y;
		if($tmp >9) $tmp=substr($tmp,0,1) + substr($tmp,1,1);
		$total+=$tmp;
	}
	if($total%10) {$umErrStr="Cardnumber fails Luhn Mod-10 check digit";  return 0;}


	switch(substr($ccnum,0,1))
	{
		case 2: //enRoute - First four digits must be 2014 or 2149. Only valid length is 15 digits
			if((substr($ccnum,0,4) == "2014" || substr($ccnum,0,4) == "2149") && strlen($ccnum) == 15) return 20;
			break;
		case 3: //JCB - Um yuck, read the if statement below, and oh by the way 300 through 309 overlaps with diners club.  bummer.
			if((substr($ccnum,0,4) == "3088" ||	substr($ccnum,0,4) == "3096" || substr($ccnum,0,4) == "3112" || substr($ccnum,0,4) == "3158" ||	substr($ccnum,0,4) == "3337" ||
				(substr($ccnum,0,8) >= "35280000" ||substr($ccnum,0,8) <= "358999999")) && strlen($ccnum)==16)
			{
				return 28;
			} else {
				switch(substr($ccnum,1,1))
				{
					case 4:
					case 7: // American Express - First digit must be 3 and second digit 4 or 7. Only Valid length is 15
						if(strlen($ccnum) == 15) return 3;
						break;
					case 0:
					case 6:
					case 8: //Diners Club/Carte Blanche - First digit must be 3 and second digit 0, 6 or 8. Only valid length is 14
						if(strlen($ccnum) == 14) return 4;
						break;
				}
			}
			break;
		case 4: // Visa - First digit must be a 4 and length must be either 13 or 16 digits.
			if(strlen($ccnum) == 13 || strlen($ccnum) == 16)
			{
				 return 2;
			}
			break;

		case 5: // Mastercard - First digit must be a 5 and second digit must be int the range 1 to 5 inclusive. Only valid length is 16
			if((substr($ccnum,1,1) >=1 && substr($ccnum,1,1) <=5) && strlen($ccnum) == 16)
			{
				return 1;
			}
			break;
		case 6: // Discover - First four digits must be 6011. Only valid length is 16 digits.
			if(substr($ccnum,0,4) == "6011" && strlen($ccnum) == 16) return 10;
	}


	// couldn't match a card profile. time to call it quits and go home.  this goose is cooked.
	$umErrStr="Cardnumber did not match any known creditcard profiles";
	return 0;
}




?>