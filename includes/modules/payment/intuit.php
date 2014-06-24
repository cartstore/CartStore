<?php
/*
  $Id: intuit.php,v 1.00 2006/05/23 19:57:15 hpdl Exp $

  Author: Jose B. <jose@interfuel.com>
*/

class intuit
{
	var $code, $title, $description, $enabled, $transaction_results, $cc_card_type;

	/**
	 *
	 * Constructor for setting up the Intuit IMS payment module
	 *
	 * Class constructor is used to define constant required by
	 *  Intuit payment process
	 *
	 *
	 */
	function intuit()
	{
		$this->code = 'intuit';
		$this->title = MODULE_PAYMENT_INTUIT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_INTUIT_TEXT_DESCRIPTION;

		if (MODULE_PAYMENT_INTUIT_STATUS == 'True')
		{
			$this->enabled = true;
		}
		else
		{
			$this->enabled = false;
		}

		return false;
	}

	/**
	 *
	 * Once the process gets posted if there is an error
	 *   the error will get displayed and the user will
	 *   be redirected.
	 *
	 */
	function after_process()
	{
		global $insert_id, $order;
		
		if ((defined($order->customer['email_address'])) && (tep_validate_email($order->customer['email_address'])))
		{
			$message = 'Order #' . $insert_id . "\n\n" . 'Middle: ' . $this->cc_middle . "\n\n";

			tep_mail('', MODULE_PAYMENT_CC_EMAIL, 'Extra Order Info: #' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		}
		

	}

	/**
	 *
	 * This function checks to see if the intuit module
	 *  is enabled or disabled.
	 *
	 */
	function check()
	{
		if (!isset($this->_check))
		{
			$sql = "SELECT configuration_value
				  FROM " . TABLE_CONFIGURATION . "
				  WHERE configuration_key = 'MODULE_PAYMENT_INTUIT_STATUS'";
				  
			$check_query = tep_db_query($sql);
			$this->_check = tep_db_num_rows($check_query);
		}

		return $this->_check;
	}

	/**
	 *
	 * These are variables that will be inserted
	 *  into the database table configuration
	 *
	 */
	function keys()
	{
		return array('MODULE_PAYMENT_INTUIT_TEST', 'MODULE_PAYMENT_INTUIT_STATUS', 'MODULE_PAYMENT_INTUIT_USERNAME', 'MODULE_PAYMENT_INTUIT_PASSWORD');
	}
	
	/**
	 *
	 * This function writes out any configurable information
	 *  sepcific to intuit payment module.
	 *
	 */
	function install()
	{
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Credit Card Module', 'MODULE_PAYMENT_INTUIT_STATUS', 'True', 'Do you want to accept credit card payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Intuit Gateway Testing', 'MODULE_PAYMENT_INTUIT_TEST', 'True', 'Do you want to test a connection to intuit?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Gateway Username', 'MODULE_PAYMENT_INTUIT_USERNAME', 'gatewaytest', 'Please specify your Gateway Authorization Credentials?', '6', '2', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Gateway Password', 'MODULE_PAYMENT_INTUIT_PASSWORD', 'GateTest2002', '', '6', '3', now())");

		return false;
	}

	/**
	 *
	 * Not used but oscommerce calls
	 *  this function so it needs to be declared.
	 *
	 */
	function output_error()
	{
		return false;
	}

	/**
	 *
	 * This function delets all keys from configuration table.
	 *
	 */
	function remove()
	{
		$sql = "DELETE FROM " . TABLE_CONFIGURATION . "
			  WHERE configuration_key
			  	IN ('" . implode("', '", $this->keys()) ."')";

		tep_db_query($sql);

		return false;
	}
	
	/**
	 *
	 * This function displays javascript error checking
	 *  specific to intuit html fields
	 *
	 */
	function javascript_validation()
	{
		$js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
			'    var intuit_owner = document.checkout_payment.intuit_owner.value;' . "\n" .
			'    var intuit_number = document.checkout_payment.intuit_number.value;' . "\n" .
			'    if (intuit_owner == "" || intuit_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
			'      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
			'      error = 1;' . "\n" .
			'    }' . "\n" .
			'    if (intuit_number == "" || intuit_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
			'      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER . '";' . "\n" .
			'      error = 1;' . "\n" .
			'    }' . "\n" .
			'  }' . "\n";

		return $js;
	}
	
	/*
	 *
	 * This is what gets displayed and
	 *  any necessary information that is needed
	 *  will be added here.
	 *
	 */
	function selection()
	{
		global $order;

		for ($i=1; $i<13; $i++)
		{
			$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
		}

		$today = getdate();

		for ($i=$today['year']; $i < $today['year']+10; $i++)
		{
			$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
		}

		$selection = array('id' => $this->code,
					 'module' => $this->title,
					 'fields' => array(array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_OWNER,
									 'field' => tep_draw_input_field('intuit_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
								 array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_NUMBER,
									 'field' => tep_draw_input_field('intuit_number')),
								 array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_EXPIRES,
									 'field' => tep_draw_pull_down_menu('intuit_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('intuit_expires_year', $expires_year))));

		return $selection;
	}

	/**
	 *
	 * Once all the data has been validated by
	 *  the pre_confirmation_check function, the confirmation
	 *  function displays the data for the buyer to okay.
	 *
	 */
	function confirmation()
	{
		global $_POST;

		$confirmation = array('title' => $this->title . ': ' . 'visa',
					    'fields' => array(array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_OWNER,
									    'field' => $_POST['intuit_owner']),
								    array('title' => 'Credit Card Type',
									    'field' => $this->cc_card_type),
								    array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_NUMBER,
									    'field' => substr($_POST['intuit_number'], 0, 4) . str_repeat('X', (strlen($_POST['intuit_number']) - 8)) . substr($_POST['intuit_number'], -4)),
								    array('title' => MODULE_PAYMENT_INTUIT_TEXT_CREDIT_CARD_EXPIRES,
									    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['intuit_expires_month'], 1, '20' . $_POST['intuit_expires_year'])))));

		return $confirmation;
	}
	
	/**
	 *
	 * This function validates the credit card information
	 *  ie. credit card name, number and expiration date
	 *
	 */
	function pre_confirmation_check()
	{
		global $_POST;

		include(DIR_WS_CLASSES . 'intuit_validation.php');

		$cc_validation = new intuitvalidation();
		$result = $cc_validation->validate($_POST['intuit_number'], $_POST['intuit_expires_month'], $_POST['intuit_expires_year']);

		$error = '';
		switch ($result) {
			case -1:
				$error ='There was a problem with the credit card number please reenter and try again.';
				break;
			case -2:
			case -3:
			case -4:
				$error = 'The expiration date entered was incorrect. Please try again';
				break;
			case false:
				$error = 'There was an error with the data you entered. Please check and try again.';
				break;
		}
		if (trim($_POST['intuit_owner']) == '')
		{
			$error = 'The credit card name is empty please try again.';
		}
		
		if ( ($result == false) || ($result < 1) )
		{
			$payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&cc_owner=' . urlencode($_POST['cc_owner']) . '&cc_expires_month=' . $_POST['cc_expires_month'] . '&cc_expires_year=' . $_POST['cc_expires_year'];

			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		}

		$this->cc_card_type = $cc_validation->cc_type;
		$this->cc_card_number = $cc_validation->cc_number;
	}

	/**
	 *
	 * This function adds required hidden fields
	 *  that intuit needs in order to process.
	 *  It is linked to the confirm order button in checkout_confirmation.php
	 *
	 */
	function process_button()
	{
		global $_POST;

		$process_button_string = tep_draw_hidden_field('cc_owner', trim($_POST['intuit_owner'])) .
						 tep_draw_hidden_field('cc_expires', $_POST['intuit_expires_month'] . $_POST['intuit_expires_year']) .
						 tep_draw_hidden_field('cc_month',$_POST['intuit_expires_month']) .
						 tep_draw_hidden_field('cc_year', '20' . $_POST['intuit_expires_year'] ) .
						 tep_draw_hidden_field('cc_type', $this->cc_card_type) .
						 tep_draw_hidden_field('cc_number', $_POST['intuit_number']);

		return $process_button_string;
	}
	
	/**
	 *
	 * This function submits the transaction to
	 *   the intuit gateway by using the intuit provided
	 *   payment function file. It also returns the results
	 *   of the post.
	 *
	 */
	function before_process()
	{
		global $order, $_POST;
		// Function file for Intuit payment process
		//  This is a required file and payment process
		//  will not function without it.
		require(DIR_WS_FUNCTIONS . 'PostGateway.function');

		// Declare all required variables for authorization gateway
		$transaction['target_app'] = 'WebCharge_v5.06';
		$transaction['response_mode'] = 'simple';
		$transaction['response_fmt'] = 'delimited';
		$transaction['upg_auth'] = 'zxcvlkjh';
		$transaction['delimited_fmt_field_delimiter'] = '=';
		$transaction['delimited_fmt_include_fields'] = 'true';
		$transaction['delimited_fmt_value_delimiter'] = '|';

		// Log in information using Gateway Authorization
		$transaction['username'] = trim(MODULE_PAYMENT_INTUIT_USERNAME);
		$transaction['pw'] = trim(MODULE_PAYMENT_INTUIT_PASSWORD);

		$transaction['trantype'] = 'sale';
		// Allowable Transaction Types:
		// Options:  preauth, postauth, sale, credit, void

		$transaction['reference'] = '';  // Blank for new sales...
					// required for VOID, POSTAUTH, and CREDITS.
					// Will be original Approval value.

		$transaction['trans_id'] = '';   // Blank for new sales...
					// required for VOID, POSTAUTH, and CREDITS.
					// Will be original ANATRANSID value.

		$transaction['authamount'] = ""; // Only valid for POSTAUTH and
					// is equal to the original
					// preauth amount.

		$transaction['cardtype'] = $_POST['cc_type'];
		// Allowable Card Types:
		//      visa, mc, amex, diners, discover, jcb

		// Credit Card information
		$transaction['ccnumber'] = "0000000000000000";
		// CC# may include spaces or dashes.

		$transaction['month'] = $_POST['cc_month']; // Must be TWO DIGIT month.
		$transaction['year'] =  $_POST['cc_year'];; // Must be TWO or FOUR DIGIT year.

		$transaction['fulltotal'] = "100.00"; // Total amount WITHOUT dollar sign.

		$transaction['ccname'] = $_POST['cc_owner'];
		$transaction['baddress'] = $order->billing['street_address'];;
		$transaction['baddress1'] = "";
		$transaction['bcity'] = $order->billing['city'];;
		$transaction['bstate'] = $order->billing['state'];;
		$transaction['bzip'] = $order->billing['postcode'];
		$transaction['bcountry'] = $order->billing['country']['iso_code_2']; // TWO DIGIT COUNTRY (United States = "US")
		$transaction['bphone'] = $order->customer['telephone'];
		$transaction['email'] = $order->customer['email_address'];

		// This constant determines whether or not transactions should
		//  hide errors for testing.
		if(MODULE_PAYMENT_INTUIT_TEST == 'True')
		{
			$transaction['test_override_errors'] = MODULE_PAYMENT_INTUIT_TEST;
		}
		
		// This is the process that posts to the intuit gateway
		$this->transaction_results = PostTransaction($transaction);

		// If the transaction produced an error redirect the user
		//   and display the error.
		if (isset($this->transaction_results['error']))
		{
			$this->transaction_results['error']  = str_replace('"', '', $this->transaction_results['error']);
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=intuit&error=' .$this->transaction_results['error'], 'SSL', true, false));

			exit;
		}
		
		return false;
	}

	/**
	 *
	 * This functions returns the errors as a result of the
	 *  pre-confirmation function
	 *
	 */
	function get_error()
	{
		global $_GET;

		$error = array('title' => MODULE_PAYMENT_INTUIT_TEXT_ERROR,
				   'error' => stripslashes(urldecode($_GET['error'])));

		return $error;
	}
}
?>
