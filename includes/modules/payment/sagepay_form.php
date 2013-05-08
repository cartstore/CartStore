<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class sagepay_form {
    var $code, $title, $description, $enabled;

// class constructor
    function sagepay_form() {
      global $order;
      $this->code = 'sagepay_form';
      $this->title = MODULE_PAYMENT_SAGEPAY_FORM_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_SAGEPAY_FORM_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_SAGEPAY_FORM_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_SAGEPAY_FORM_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_SAGEPAY_FORM_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_SAGEPAY_FORM_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      if (MODULE_PAYMENT_SAGEPAY_FORM_TEST_STATUS == 'true') {
        // normal test mode:
        $this->form_action_url = 'https://test.sagepay.com/vps2form/submit.asp';
        //$this->form_action_url = '../../decrypt_sagepay.php';
        // simulator
        //$this->form_action_url = 'https://test.sagepay.com/Simulator/VSPFormGateway.asp  ';  

      } else {
        $this->form_action_url = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
      }
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_SAGEPAY_FORM_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_SAGEPAY_FORM_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      return false;
    }

    function simpleXor($InString, $Key) {
      $KeyList = array();
      $output = "";

      for($i = 0; $i < strlen($Key); $i++){
        $KeyList[$i] = ord(substr($Key, $i, 1));
      }

      for($i = 0; $i < strlen($InString); $i++) {
        $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
      }

      return $output;
    }

    function getToken($thisString) {

      $Tokens = array("Status","StatusDetail","VendorTxCode","VPSTxID","TxAuthNo","Amount","AVSCV2");

      $output = array();
      $resultArray = array();

      for ($i = count($Tokens)-1; $i >= 0 ; $i--){
        $start = strpos($thisString, $Tokens[$i]);
        if ($start !== false){
          $resultArray[$i]->start = $start;
          $resultArray[$i]->token = $Tokens[$i];
        }
      }

      sort($resultArray);

      for ($i = 0; $i<count($resultArray); $i++){
        $valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;
        if ($i==(count($resultArray)-1)) {
          $output[$resultArray[$i]->token] = substr($thisString, $valueStart);
        } else {
          $valueLength = $resultArray[$i+1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
          $output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
        }
      }

      return $output;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    /** Added by CWS @ theINtoy.co.uk at version 1.18 **/
    function getShoppingCartInfo()
    {
        global $cart, $order;

      $retStr = "";

        if (MODULE_PAYMENT_SAGEPAY_FORM_SHOPCART == 'true') {
    $shipping = $order->info['shipping_cost'];
    $shipping = number_format($shipping, 2, '.', '');
    $products = $cart->get_products();
    $no_lines = sizeof($products);
    $no_lines = $no_lines + 1;

    $shippingStr .= ":Shipping:1:".$shipping.":----:".$shipping.":".$shipping;
    $basketStr = "Basket=".$no_lines;
    $moreStr .= ":More products...See Order.:1:----:----:----:----";
        $moreLen = strlen( $moreStr );
    /*
          Maxmium size of the basket field according to SAGEPAY Form Documentation V2.22
          is 7500 characters. Allowing 7495 as a small safety net.
    */
    $charCount = 7495 - strlen( $shippingStr ) - strlen( $basketStr );

    $detail = "";
        $linesAdded = 0;
        $i = 0;
        $n=sizeof($products);
        $finished = false;

      while ( ( $i<$n ) && ( !$finished ) ) {
          $desc = $products[$i]['name'];
        $desc  = str_replace(":", "", $desc);
        $qty = $products[$i]['quantity'];
        $price = $products[$i]['price'] + $cart->attributes_price($products[$i]['id']);
        $tax = $price/100 * tep_get_tax_rate($products[$i]['tax_class_id']);
        $tax = number_format($tax, 2, '.', '');
        $finalPrice = $price + $tax;
        $finalPrice = number_format($finalPrice, 2, '.', '');
        $lineTotal = $qty * $finalPrice;
        $lineTotal = number_format($lineTotal, 2, '.', '');
        $line = ":".$desc.":".$qty.":".$price.":".$tax.":".$finalPrice.":".$lineTotal;
        $line = str_replace("\n", "", $line);
            $line = str_replace("\r", "", $line);
            $len = strlen( $line );
            if ( ( $charCount - $moreLen - $len) > 0 ) {
                $linesAdded ++;
                $charCount -= $len;
                $detail .= $line;
            } else if ( $charCount - $moreLen > 0 ) {
                $detail .= $moreStr;
                $charCount -= $len;
                $linesAdded ++;
                $finished = true;
            }
            else {
                // We should not hit this point, but if we do lets fininsh
                $finished = true;
            }
            $i++;
      }

      if ( strlen( $detail ) > 0 )
      {
          $linesAdded ++;
          $retStr = "&" . "Basket=" . $linesAdded . $detail . $shippingStr;
      }
      }
      return $retStr;
    }


    function process_button() {
      global $order, $currencies, $currency, $customer_id;

      switch (MODULE_PAYMENT_SAGEPAY_FORM_CURRENCY) {
        case 'Default Currency':
          $sagepay_currency = DEFAULT_CURRENCY;
          break;
        case 'Any Currency':
        default:
          $sagepay_currency = $currency;
          break;
      }

      $plain = "VendorTxCode=" . MODULE_PAYMENT_SAGEPAY_FORM_VENDOR_NAME . date('YmdHis') . $customer_id . "&";
      $plain .= "Amount=" . number_format($order->info['total'] * $currencies->get_value($sagepay_currency), $currencies->get_decimal_places($sagepay_currency), '.', '') . "&";
      $plain .= "Currency=" . $sagepay_currency . "&";
    $plain .= "Description=" . "Goods bought from " . STORE_NAME . "&";
      $plain .= "SuccessURL=" . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true) . "&";
      $plain .= "FailureURL=" . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true) . "&";
      $plain .= "CustomerName=" . $order->billing['firstname'] . ' ' . $order->billing['lastname'] . "&";
      $plain .= "CustomerEmail=" . $order->customer['email_address'] . "&";
      $plain .= "VendorEMail=" . STORE_OWNER_EMAIL_ADDRESS . "&";
      
      
      // protocol 2.23 stuff
      $plain .= "BillingSurname=".$order->billing['lastname']."&";
      $plain .= "BillingFirstnames=".$order->billing['firstname']."&";
      $plain .= "BillingCity=".$order->billing['city']."&";
      $plain .= "BillingCountry=".$order->billing['country']['iso_code_2']."&";

        // Added to fix issue with US customers requiring billing & delivery state 
	$billstatequery = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name like '" . $order->billing['state'] . "' ");
	$bill_state = tep_db_fetch_array($billstatequery);
	$deliverystatequery = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name like '" . $order->delivery['state'] . "' ");
	$delivery_state = tep_db_fetch_array($deliverystatequery);

	if (($order->billing['country']['iso_code_2']) == 'US') {
      		$plain .= "BillingState=".$bill_state['zone_code']."&";
      		$plain .= "DeliveryState=".$delivery_state['zone_code']."&";
	}else{
		$plain .= "BillingState=" . "&";
		$plain .= "DeliveryState=" . "&";
	}
	// End Add

      $plain .= "DeliverySurname=".$order->delivery['lastname']."&";
      $plain .= "DeliveryFirstnames=".$order->delivery['firstname']."&";
      $plain .= "DeliveryCity=".$order->delivery['city']."&";
      $plain .= "DeliveryCountry=".$order->delivery['country']['iso_code_2']."&";
      // endof 2.23 stuff
      
      
      $delivery_address = $order->delivery['street_address'];
      if (ACCOUNT_SUBURB == 'true') {
        $delivery_address .= ',' . $order->delivery['suburb'];
      }

      $delivery_address .= ',' . $order->delivery['city'];

      if (ACCOUNT_STATE == 'true') {
        $delivery_address .= ',' . $order->delivery['state'];
      }

      $delivery_address .= ',' . $order->delivery['country']['title'];

      $billing_address = $order->billing['street_address'];

      if (ACCOUNT_SUBURB == 'true') {
        $billing_address .= ',' . $order->billing['suburb'];
      }

      $billing_address .= ',' . $order->billing['city'];

      if (ACCOUNT_STATE == 'true') {
        $billing_address .= ',' . $order->billing['state'];
      }

      $billing_address .= ',' . $order->billing['country']['title'];

      $plain .= "DeliveryAddress1=" . $delivery_address . "&";
      $plain .= "DeliveryPostCode=" . $order->delivery['postcode'] . "&";
      $plain .= "BillingAddress1=" . $billing_address . "&";
      $plain .= "BillingPostCode=" . $order->billing['postcode'] . "";

      $cart_string = $this->getShoppingCartInfo();
      $plain .= $cart_string;

      $crypt = base64_encode($this->SimpleXor($plain, MODULE_PAYMENT_SAGEPAY_FORM_PASSWORD));

    if (MODULE_PAYMENT_SAGEPAY_FORM_PREAUTH == 'true') {
        $transaction_type = 'AUTHENTICATE';
    } else {
       $transaction_type = 'PAYMENT';
       // $transaction_type = 'DEFERRED';
    }
    
//     $transaction_type = 'REPEAT';
    
      $process_button_string = tep_draw_hidden_field('VPSProtocol', '2.23') .
                               tep_draw_hidden_field('TxType', $transaction_type) .
                               tep_draw_hidden_field('Vendor', MODULE_PAYMENT_SAGEPAY_FORM_VENDOR_NAME) .
                               tep_draw_hidden_field('crypt', $crypt);

      return $process_button_string;
    }


    function before_process() {
      global $_GET, $crypt;
      $crypt = $_REQUEST['crypt'];
      $process_button_string = str_replace(" ", "+", $process_button_string);
      $Decoded = $this->SimpleXor(base64_decode(str_replace(" ", "+", $crypt)),MODULE_PAYMENT_SAGEPAY_FORM_PASSWORD);
      $values = $this->getToken($Decoded);

      $StatusDetail = $values['StatusDetail'];

      switch ($values['Status']) {
        case "OK":
        case "AUTHENTICATED":
        case "REGISTERED":
          $Status = true;
        break;

        default:
          $Status = false;
        break;
      }

      if ($Status !== true) {
        $sessionName = tep_session_name();
        $sessionId = $_GET[$sessionName];
        $hrefLink = tep_href_link( FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($StatusDetail), 'SSL', true, false );
        tep_redirect( $hrefLink );
      }

      return $Status;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $_GET;

      if (isset($_GET['message']) && (strlen($_GET['message']) > 0)) {
        $error = stripslashes(urldecode($_GET['message']));
      } else {
        $error = MODULE_PAYMENT_SAGEPAY_FORM_TEXT_ERROR_MESSAGE;
      }

      return array('title' => MODULE_PAYMENT_SAGEPAY_FORM_TEXT_ERROR,
                   'error' => $error);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SAGEPAY_FORM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Protx Form Module', 'MODULE_PAYMENT_SAGEPAY_FORM_STATUS', 'True', 'Do you want to accept Protx Form payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_SAGEPAY_FORM_VENDOR_NAME', 'TestVendor', 'Vendor Name to use with the Protx Form service', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'MODULE_PAYMENT_SAGEPAY_FORM_PASSWORD', 'testvendor', 'Password to use with the Protx Form service', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_SAGEPAY_FORM_CURRENCY', 'Any Currency', 'The currency to use for credit card transactions', '6', '3', 'tep_cfg_select_option(array(\'Any Currency\', \'Default Currency\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_SAGEPAY_FORM_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_SAGEPAY_FORM_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_SAGEPAY_FORM_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Test Mode', 'MODULE_PAYMENT_SAGEPAY_FORM_TEST_STATUS', 'true', 'Use Test Mode?', '6', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Pre-Authorisation', 'MODULE_PAYMENT_SAGEPAY_FORM_PREAUTH', 'true', 'Use Pre-Authorisation for all transactions?', '6', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shopping cart', 'MODULE_PAYMENT_SAGEPAY_FORM_SHOPCART', 'true', 'Send shopping cart details to Protx?', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_PAYMENT_SAGEPAY_FORM_STATUS', 'MODULE_PAYMENT_SAGEPAY_FORM_VENDOR_NAME', 'MODULE_PAYMENT_SAGEPAY_FORM_PASSWORD', 'MODULE_PAYMENT_SAGEPAY_FORM_CURRENCY', 'MODULE_PAYMENT_SAGEPAY_FORM_ZONE', 'MODULE_PAYMENT_SAGEPAY_FORM_ORDER_STATUS_ID', 'MODULE_PAYMENT_SAGEPAY_FORM_SORT_ORDER', 'MODULE_PAYMENT_SAGEPAY_FORM_TEST_STATUS', 'MODULE_PAYMENT_SAGEPAY_FORM_PREAUTH', 'MODULE_PAYMENT_SAGEPAY_FORM_SHOPCART');
    }
  }
?>
