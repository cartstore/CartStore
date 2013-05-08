<?php
class osC_onePageCheckout
	{

	function osC_onePageCheckout()
		{
		$this->buildSession();
		}

	function reset()
		{
		$this->buildSession(true);
		}

	function buildSession($forceReset = false)
		{
		global $onepage, $payment, $shipping, $customer_id, $sendto, $billto;
		if (!tep_session_is_registered('onepage') || $forceReset === true)
			{
			if (tep_session_is_registered('onepage'))
				{
				tep_session_unregister('onepage');
				}
			if (tep_session_is_registered('payment'))
				{
				tep_session_unregister('payment');
				}
			if (tep_session_is_registered('shipping'))
				{
				tep_session_unregister('shipping');
				}
			if (tep_session_is_registered('billto'))
				{
				tep_session_unregister('billto');
				}
			if (tep_session_is_registered('sendto'))
				{
				tep_session_unregister('sendto');
				}
			if (tep_session_is_registered('customer_shopping_points_spending'))
				{
				tep_session_unregister('customer_shopping_points_spending');
				}

			$onepage = array(
			'info'           => array(
			'payment_method' => '', 'shipping_method' => '', 'comments' => '', 'coupon' => ''
			),
			'customer'       => array(
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'format_id' => '',  'telephone' => '', 'email_address' => '', 'password' => '', 'newsletter' => ''
			),
			'delivery'       => array(
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'country_id' => '', 'format_id' => ''
			),
			'billing'        => array(
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'country_id' => '', 'format_id' => ''
			),
			'create_account'  => false,
			'shippingEnabled' => true
			);
			$payment = false;
			$shipping = false;
			$sendto = 0;
			$billto = 0;
			tep_session_register('onepage');
			tep_session_register('payment');
			tep_session_register('shipping');
			tep_session_register('billto');
			tep_session_register('sendto');
			}
		if (tep_session_is_registered('customer_id') && is_numeric($customer_id))
			{
			$onepage['create_account'] = false;
			$QcustomerEmail = tep_db_query('select customers_email_address, customers_telephone from ' . TABLE_CUSTOMERS . ' where customers_id = "' . $customer_id . '"');
			$customerEmail = tep_db_fetch_array($QcustomerEmail);
			$onepage['customer']['email_address'] = $customerEmail['customers_email_address'];
			$onepage['customer']['telephone'] = $customerEmail['customers_telephone'];
			}
		}

	function fixZoneName($zone_id,$country,&$state)
		{
		if ( $zone_id >0 && $country>0 )
			{
			$zone_query = tep_db_query("select distinct zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . tep_db_input($zone_id) . "' ");
			if (tep_db_num_rows($zone_query) == 1)
				{
				$zone = tep_db_fetch_array($zone_query);
				$state = $zone['zone_name'];
				}
			}
		}

	function loadSessionVars($type = 'checkout'){
		global $order, $onepage, $payment, $shipping, $comments, $coupon;
		if (tep_not_null($onepage['info']['payment_method'])){
			$payment = $onepage['info']['payment_method'];
			if (isset($GLOBALS[$payment])){
				$pModule = $GLOBALS[$payment];
				if (isset($pModule->public_title)) {
					$order->info['payment_method'] = $pModule->public_title;
				} else {
					$order->info['payment_method'] = $pModule->title;
				}

				if (isset($pModule->order_status) && is_numeric($pModule->order_status) && ($pModule->order_status > 0)){
					$order->info['order_status'] = $pModule->order_status;
				}
			}
		}
		if (tep_not_null($onepage['info']['shipping_method'])){
			$shipping = $onepage['info']['shipping_method'];
			$order->info['shipping_method'] = $shipping['title'];
			$order->info['shipping_cost'] = $shipping['cost'];
		}
		if (tep_not_null($onepage['info']['comments'])){

			$comments = $onepage['info']['comments'];
			if (!tep_session_is_registered('comments')) tep_session_register('comments');
		}

		//BOF KGT
		if(defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true')
		{
			//kgt - discount coupons
			if (tep_not_null($onepage['info']['coupon'])) {
				$order->info['coupon'] = $onepage['info']['coupon'];
				if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
			}
			//end kgt - discount coupons
		}
		//EOF KGT

		if ($onepage['customer']['firstname'] == ''){
			$onepage['customer'] = array_merge($onepage['customer'], $onepage['billing']);
		}

		if ($onepage['delivery']['firstname'] == ''){
			$onepage['delivery'] = array_merge($onepage['delivery'], $onepage['billing']);
		}

		if (ACCOUNT_STATE == 'true') {
			$this->fixZoneName($onepage['customer']['zone_id'],$onepage['customer']['country']['id'],$onepage['customer']['state']);
			$this->fixZoneName($onepage['billing']['zone_id'],$onepage['billing']['country']['id'],$onepage['billing']['state']);
			$this->fixZoneName($onepage['delivery']['zone_id'],$onepage['delivery']['country']['id'],$onepage['delivery']['state']);
		}

		$order->customer = $onepage['customer'];
		$order->billing = $onepage['billing'];
		$order->delivery = $onepage['delivery'];
	}

	function init(){
		$this->verifyContents();
		if (!isset($_GET['payment_error'])){
			$this->reset();
		}

		if (STOCK_CHECK == 'true' && STOCK_ALLOW_CHECKOUT != 'true') {
			$this->checkStock();
		}

		$this->setDefaultSendTo();
		$this->setDefaultBillTo();

		$this->removeCCGV();
	}

	function fixTaxes(){
		global $cart, $order, $currencies, $onepage, $customer_id, $customer_country_id, $customer_zone_id;
		if ($cart->get_content_type() == 'virtual' && is_numeric($onepage['billing']['country_id'])) {
			$taxCountryID = $onepage['billing']['country_id'];
			$taxZoneID = $onepage['billing']['zone_id'];
		}elseif (is_numeric($onepage['delivery']['country_id'])){
			$taxCountryID = $onepage['delivery']['country_id'];
			$taxZoneID = $onepage['delivery']['zone_id'];
		}elseif (!tep_session_is_registered('customer_id')) {
			if (DISPLAY_PRICE_WITH_TAX == 'false'){
				$taxCountryID = 0;
				$taxZoneID = 0;
			}else{
				$taxCountryID = STORE_COUNTRY;
				$taxZoneID = STORE_ZONE;
			}
		}else{
			$taxCountryID = $customer_country_id;
			$taxZoneID = $customer_zone_id;
		}

		$products = $cart->get_products();
		if (sizeof($products) > 0){
			$order->info['subtotal'] = 0;
			$order->info['tax_groups'] = array();
			$order->info['tax'] = 0;
			//BOF KGT
			if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
				$valid_products_count = 0;
				$order->coupon->applied_discount = array();
			}
			//EOF KGT
			for ($i=0, $n=sizeof($products); $i<$n; $i++) {
				$taxClassID = $products[$i]['tax_class_id'];
				$order->products[$i]['tax'] = tep_get_tax_rate($taxClassID, $taxCountryID, $taxZoneID);
				$order->products[$i]['tax_description'] = tep_get_tax_description($taxClassID, $taxCountryID, $taxZoneID);

				//BOF KGT
				if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' && is_object( $order->coupon ) && !empty($order->coupon->coupon)){

					$applied_discount = 0;
					$discount = $order->coupon->calculate_discount( $order->products[$i], $valid_products_count );
					if( $discount['applied_discount'] > 0 ) $valid_products_count++;
					$shown_price = $order->coupon->calculate_shown_price( $discount, $order->products[$i] );
					//var_dump($shown_price);
					$shown_price = $shown_price['actual_shown_price'];
				} else {
					$shown_price = tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];

				}
				$order->info['subtotal'] += $shown_price;
				//EOF KGT


				$products_tax = $order->products[$i]['tax'];
				$products_tax_description = $order->products[$i]['tax_description'];
				//echo "tax: $products_tax \n";
				//echo "tax desc: $products_tax_description \n";
				if (DISPLAY_PRICE_WITH_TAX == 'true'){
					$order->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
					if (isset($order->info['tax_groups']["$products_tax_description"])) {
						$order->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
					}else{
						$order->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
					}
				}else{
					$order->info['tax'] += ($products_tax / 100) * $shown_price;
					if (isset($order->info['tax_groups']["$products_tax_description"])) {
						$order->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
					}else{
						$order->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
					}
				}
				//echo $shown_price."\n";
			}

			if (DISPLAY_PRICE_WITH_TAX == 'true'){
				$order->info['total'] = $order->info['subtotal'] + $order->info['shipping_cost'];
			}else{
				$order->info['total'] = $order->info['subtotal'] + $order->info['tax'] + $order->info['shipping_cost'];
			}

			//kgt - discount coupon
			if( defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' && is_object( $order->coupon )  && !empty($order->coupon->coupon))
			{
				//$order->coupon->applied_discount = $applied_discount;
				$order->info['total'] = $order->coupon->finalize_discount( $order->info );
				$onepage['coupon'] = $order->coupon;
			}
			//end kgt - discount coupon
		}
	}

	function checkEmailAddress($emailAddress, $ajax=true){
		$success = 'true';
		$errMsg = '';

		$Qcheck = tep_db_query('select customers_id from ' . TABLE_CUSTOMERS . ' where customers_email_address = "' . tep_db_prepare_input($emailAddress) . '"');
		if (tep_db_num_rows($Qcheck)){
			$success = 'false';
			$errMsg = 'Your email address already exists, please log into your account or use a different email address.';
		}else{
			require_once(DIR_WS_INCLUDES . 'functions/validations.php');
			if (tep_validate_email($emailAddress) === false){
				$success = 'false';
				$errMsg = 'The email address provided is invalid.';
			}
		}
		if($ajax == true)
		{
			return json_encode(array(
				"success" => $success,
				"errMsg" => $errMsg
      		));
		}else
		{
			return $success;
		}
	}

	function getAjaxStateField($manualCid = false, $key = 'billing'){
		global $onepage;
		if ($manualCid !== false){
			$country = $manualCid;
			$name = 'billing_state';
			if ($key != 'billing'){
				$name = $key . '_state';
			}
		}else{
			$country = $_POST['cID'];
			$name = $_POST['fieldName'];
			if ($name == 'billing_state'){
				$key = 'billing';
			}else{
				$key = 'delivery';
			}
		}
		$html = '';
		$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
		$check = tep_db_fetch_array($check_query);
		if ($check['total'] > 0) {
			$zones_array = array(
			array('id' => '', 'text' => TEXT_PLEASE_SELECT)
			);
			$zones_query = tep_db_query("select zone_id, zone_code, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
			$selected = '';
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				if ($selected == ''){
					if (isset($onepage[$key]['zone_id']) && $onepage[$key]['zone_id'] == $zones_values['zone_id']){
						$selected = $zones_values['zone_name'];
					}elseif (isset($onepage[$key]['state']) && $onepage[$key]['state'] == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}elseif (isset($_POST['curValue']) && $_POST['curValue'] == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}
				}
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			$html .= tep_draw_pull_down_menu($name, $zones_array, $selected, 'class="required" style="width:70%;float:left;"');
		} else {
			$html .= tep_draw_input_field($name, (isset($onepage[$key]['state']) ? $onepage[$key]['state']: ''), 'class="required" style="width:70%;float:left;"');
		}
		return $html;
	}
	
	function getAjaxStateFieldAddress($manualCid = false, $zone_id=0, $state=''){
		global $onepage;
		$country = $manualCid;
		$name = 'state';
		$key = '';
		$html = '';
		$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
		$check = tep_db_fetch_array($check_query);
		if ($check['total'] > 0) {
			$zones_array = array(
			array('id' => '', 'text' => TEXT_PLEASE_SELECT)
			);
			$zones_query = tep_db_query("select zone_id, zone_code, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
			$selected = '';
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				if ($zone_id >0 || !empty($state)){
					if ($zone_id == $zones_values['zone_id']){
						$selected = $zones_values['zone_name'];
					}elseif (!empty($state) && $state == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}elseif (isset($_POST['curValue']) && $_POST['curValue'] == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}
				}
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			$html .= tep_draw_pull_down_menu($name, $zones_array, $selected, 'class="required" style="width:70%;float:left;"');
		} else {
			$html .= tep_draw_input_field($name, (!empty($state) ? $state: ''), 'class="required" style="width:70%;float:left;"');
		}
		return $html;
	}

	function updateCartProducts($qtys, $ids){
		global $cart, $customer_shopping_points_spending;
		foreach($qtys as $pID => $qty){
			$cart->update_quantity($pID, $qty, $ids[$pID]);
		}
		if(tep_session_is_registered('customer_shopping_points_spending'))
		$this->redeemPoints($customer_shopping_points_spending);

		if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
			$json = json_encode(array(
          		"success" => "true"
			));
		}else{
			tep_redirect(tep_href_link(FILENAME_CHECKOUT));
		}
		return $json;
	}

	function removeProductFromCart($productID){
		global $cart, $customer_shopping_points_spending;
		$cart->remove($productID);

		if(tep_session_is_registered('customer_shopping_points_spending'))
		$this->redeemPoints($customer_shopping_points_spending);

		$json = '';
		if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
			$json = json_encode(array(
				"success" => "true",
				"products" => $cart->count_contents()
			));
		}else{
			tep_redirect(tep_href_link(FILENAME_CHECKOUT));
		}
		return $json;
	}

	function processAjaxLogin($emailAddress, $password)
		{
		global $cart, $customer_id, $onepage, $customer_default_address_id, $customer_first_name, $customer_country_id, $customer_zone_id, $sendto, $billto;
		$error = false;
		$check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($emailAddress) . "'");
		if (!tep_db_num_rows($check_customer_query))
			{
			$error = true;
			}
		 else
			{
			$check_customer = tep_db_fetch_array($check_customer_query);
			if (!tep_validate_password($password, $check_customer['customers_password']))
				{
				$error = true;
				}
			 else
				{
				if (SESSION_RECREATE == 'True')
					{
					tep_session_recreate();
					}
				$check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
				$check_country = tep_db_fetch_array($check_country_query);
				$customer_id = $check_customer['customers_id'];
				$customer_default_address_id = $check_customer['customers_default_address_id'];
				$customer_first_name = $check_customer['customers_firstname'];
				$customer_country_id = $check_country['entry_country_id'];
				$customer_zone_id = $check_country['entry_zone_id'];
				$onepage['customer']['email_address'] = $check_customer['customers_email_address'];
				$onepage['createAccount'] = false;
				if (!tep_session_is_registered('customer_id')) tep_session_register('customer_id');
				$sendto = $customer_default_address_id;
				$billto = $customer_default_address_id;
				$this->setDefaultSendTo();
				$this->setDefaultBillTo();
				if (!tep_session_is_registered('customer_default_address_id')) tep_session_register('customer_default_address_id');
				if (!tep_session_is_registered('customer_first_name')) tep_session_register('customer_first_name');
				if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
				if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');
				if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
				if (!tep_session_is_registered('billto')) tep_session_register('billto');
				tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
				$cart->restore_contents();
				}
			}
		$json = '';
		if ($error === false)
			{
			$json = json_encode(array("success" => "true", "msg" => "Loading your account info"));
			}
		else
			{
			$json = json_encode(array("success" => "false", "msg" => "Authorization Failed"));
			}
		return $json;
		}

	function setPaymentMethod($method){
		global $payment_modules, $language, $order, $cart, $payment, $onepage, $customer_shopping_points_spending;
		/* Comment IF statement below for oscommerce versions before MS2.2 RC2a */
		if (tep_session_is_registered('payment') && tep_not_null($payment) && $payment != $method){
		 $GLOBALS[$payment]->selection();
		}

		if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
			if(tep_session_is_registered('customer_shopping_points_spending'))
			//if($order->info['subtotal']<=0 || $order->info['total']<=0)
			if(($order->info['total']) <=0) //if(($order->info['total'] - $order->info['tax'] - $order->info['shipping_cost']) <=0)
			{
				$payment = '';
				$paymentMethod = '';
				$onepage['info']['payment_method'] = '';
				$onepage['info']['order_id'] = '';
				return json_encode(array(
          			"success" => "true",
          			"inputFields" => ""
          		));
			}
		}

		$payment = $method;
		if (!tep_session_is_registered('payment')){
			tep_session_register('payment');
		}
		$onepage['info']['payment_method'] = $method;

		$order->info['payment_method'] = $GLOBALS[$payment]->title;

		//BOF Tell Paypal to pre-recorded Order again or the new options will not be applied
		switch($GLOBALS[$payment]->code)
		{
			case 'paypal_ipn':
			case 'paypal_standard':
			case 'worldpay_junior':
				break;
			default:
				/* Comment line below for oscommerce versions before MS2.2 RC2a */
				$confirmation = $GLOBALS[$payment]->confirmation();

				/* Uncomment line below for oscommerce versions before MS2.2 RC2a */
				//$confirmation = $GLOBALS[$payment]->selection();
				break;
		}
		//EOF Tell Paypal to pre-recorded Order again or the new options will not be applied

		$inputFields = '';
		if ($confirmation !== false){
			for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
				$inputFields .= '<div>' . $confirmation['fields'][$i]['title'] . 
					$confirmation['fields'][$i]['field'] . '</div>';
			}

			if ($inputFields != ''){
				$inputFields = '<div class="paymentFields payment_module_outputXXX">' .
				$inputFields .
				'</div>';
			}
		}
		return json_encode(array(
			"success" => "true",
			"inputFields" => array($inputFields)
		));
	}

	function setGiftVoucher()
	{
		global $payment, $onepage, $order_total_modules, $credit_covers, $customer_id, $cot_gv, $ot_gv;

		if(isset($_POST['cot_gv']) && $_POST['cot_gv']=='on')
		{
			$total_gv_amount = 0;
			$gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
			if ($gv_result = tep_db_fetch_array($gv_query)) {
				$total_gv_amount = $gv_result['amount'];
				if($total_gv_amount >0)
				{
					tep_session_register('cot_gv');
				}else
				{
					tep_session_unregister('cot_gv');
				}
			}
		}else
		{
			$ot_gv->output = array();
			tep_session_unregister('cot_gv');
			tep_session_unregister('credit_covers');
			$order_total_modules->pre_confirmation_check();
		}

		return json_encode(array(
      		"success" => "true"
    	));
	}

	function redeemPoints($points)
	{
		global $customer_shopping_points_spending, $customer_id;
		if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true') && tep_session_is_registered('customer_id') && $customer_id>0) {
			if (isset($points) && is_numeric($points) && ($points > 0)) {
				$customer_shopping_points_spending = false;
				$customer_shopping_points = tep_get_shopping_points();
				$max_points = calculate_max_points($customer_shopping_points);
				if($points > tep_get_shopping_points($customer_id))
				{
					return json_encode(array("success" => "false"));
				}
				if($points > $max_points)
				$points = $max_points;

				$customer_shopping_points_spending = $points;
				if (!tep_session_is_registered('customer_shopping_points_spending')) tep_session_register('customer_shopping_points_spending');
				return json_encode(array("success" => "true"));
			}
		}
		return json_encode(array("success" => "false"));
	}

	function clearPoints()
	{
		global $customer_shopping_points_spending, $customer_id;
		if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true') && tep_session_is_registered('customer_id') && $customer_id>0) {
			$customer_shopping_points_spending = 0;
			if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
		}
		return json_encode(array("success" => "true"));
	}

	function setShippingMethod($method = ''){
		global $shipping_modules, $language, $order, $cart, $shipping, $onepage;
		if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') {
			$pass = false;

			switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
				case 'national':
					if ($order->delivery['country_id'] == STORE_COUNTRY) {
						$pass = true;
					}
					break;
				case 'international':
					if ($order->delivery['country_id'] != STORE_COUNTRY) {
						$pass = true;
					}
					break;
				case 'both':
					$pass = true;
					break;
			}

			// disable free shipping for Alaska and Hawaii
			$zone_code = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '');
			if(in_array($zone_code, array('AK', 'HI'))) {
				$pass = false;
			}

			$free_shipping = false;
			if ($pass == true && $order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
				$free_shipping = true;
				include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
			}
		} else {
			$free_shipping = false;
		}

		if (!tep_session_is_registered('shipping')){
			tep_session_register('shipping');
		}
		$shipping = false;
		$onepage['info']['shipping_method'] = false;

		if (tep_count_shipping_modules() > 0 || $free_shipping == true) {
			if (strpos($method, '_')) {
				$shipping = $method;

				list($module, $method) = explode('_', $shipping);
				global $$module;
				if (is_object($$module) || $shipping == 'free_free') {
					$quote = $shipping_modules->quote($method, $module);

					if (isset($quote['error'])) {
						unset($shipping);
					} else {
						if (isset($quote[0]['methods'][0]['title']) && isset($quote[0]['methods'][0]['cost']) || $shipping == 'free_free') {
							$shipping = array(
							'id' => $shipping,
							'title' => (($shipping == 'free_free') ?  FREE_SHIPPING_TITLE : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
							'cost' => (($shipping == 'free_free')?'0':$quote[0]['methods'][0]['cost'])
							);
							$onepage['info']['shipping_method'] = $shipping;
						}
					}
				} else {
					unset($shipping);
				}
			}
		}
		return json_encode(array(
        	"success" => "true"
      	));
	}

	function setCheckoutAddress($action){
		global $order, $onepage;
		if ($action == 'setSendTo' && !tep_not_null($_POST['shipping_country'])){
			$prefix = 'billing_';
		}else{
			$prefix = ($action == 'setSendTo' ? 'shipping_' : 'billing_');
		}

		if (ACCOUNT_GENDER == 'true') $gender = $_POST[$prefix . 'gender'];
		if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST[$prefix . 'company']);
		if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST[$prefix . 'suburb']);
		$zip_code = tep_db_prepare_input($_POST[$prefix . 'zipcode']);
		$country = tep_db_prepare_input($_POST[$prefix . 'country']);
		if (ACCOUNT_STATE == 'true') {
			if (isset($_POST[$prefix . 'zone_id'])) {
				$zone_id = tep_db_prepare_input($_POST[$prefix . 'zone_id']);
			} else {
					$zone_id = false;
			}
			if ($prefix == 'shipping_')
			{
				$state = tep_db_prepare_input($_POST['delivery_state']);
			}
			else
			{
				$state = tep_db_prepare_input($_POST[$prefix . 'state']);
			}
			$zone_name = '';
			$zone_id = 0;
			$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
			$check = tep_db_fetch_array($check_query);
			$entry_state_has_zones = ($check['total'] > 0);
			if ($entry_state_has_zones == true) {
				$zone_query = tep_db_query("select distinct zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
				if (tep_db_num_rows($zone_query) == 1) {
					$zone = tep_db_fetch_array($zone_query);
					$zone_id = $zone['zone_id'];
					$zone_name = $zone['zone_name'];
				}
			}
		}

		$QcInfo = tep_db_query('select * from ' . TABLE_COUNTRIES . ' where countries_id = "' . $country . '"');
		$cInfo = tep_db_fetch_array($QcInfo);
		if ($action == 'setBillTo')
		{
			$varName = 'billing';
			if (ACCOUNT_DOB == 'true' && tep_not_null($_POST[$prefix . 'dob'])) $dob = $_POST[$prefix . 'dob'];
		}
		else
		{
			$varName = 'delivery';
		}
		if ($action == 'setBillTo'){
			if (ACCOUNT_DOB == 'true'){
				$dob = tep_db_prepare_input($_POST[$prefix . 'dob']);
				$order->customer['dob'] = $dob;
				$onepage['customer']['dob'] = $dob;
			}
			if (tep_not_null($_POST['billing_email_address'])){
				$order->customer['email_address'] = tep_db_prepare_input($_POST['billing_email_address']);
				$onepage['customer']['email_address'] = $order->customer['email_address'];
			}
			if (tep_not_null($_POST['billing_telephone'])){
				$order->customer['telephone'] = tep_db_prepare_input($_POST['billing_telephone']);
				$onepage['customer']['telephone'] = $order->customer['telephone'];
			}
			if (tep_not_null($_POST['password'])){
				$onepage['customer']['password'] = tep_encrypt_password($_POST['password']);
			}
		}

		$order->{$varName}['gender'] = $gender;
		$order->{$varName}['firstname'] = tep_db_prepare_input($_POST[$prefix . 'firstname']);
		$order->{$varName}['lastname'] = tep_db_prepare_input($_POST[$prefix . 'lastname']);
		$order->{$varName}['company'] = $company;
		$order->{$varName}['street_address'] = tep_db_prepare_input($_POST[$prefix . 'street_address']);
		$order->{$varName}['suburb'] = $suburb;
		$order->{$varName}['city'] = tep_db_prepare_input($_POST[$prefix . 'city']);
		$order->{$varName}['postcode'] = $zip_code;
		$order->{$varName}['state'] = ((isset($zone_name) && tep_not_null($zone_name)) ? $zone_name : $state);
		$order->{$varName}['zone_id'] = $zone_id;
		$order->{$varName}['country'] = array(
		'id'         => $cInfo['countries_id'],
		'title'      => $cInfo['countries_name'],
		'iso_code_2' => $cInfo['countries_iso_code_2'],
		'iso_code_3' => $cInfo['countries_iso_code_3']
		);
		$order->{$varName}['country_id'] = $cInfo['countries_id'];
		$order->{$varName}['format_id'] = $cInfo['address_format_id'];
		if ($action == 'setSendTo' && !tep_not_null($_POST['shipping_firstname'])){
			$onepage['customer'] = array_merge($onepage['customer'], $order->billing);
		}

		$onepage[$varName] = array_merge($onepage[$varName], $order->{$varName});

		return json_encode(array('success'=>'true'));
	}

	function setAddress($addressType, $addressID){
		global $billto, $sendto, $customer_id, $onepage;
		switch($addressType){
			case 'billing':
				$billto = $addressID;
				if (!tep_session_is_registered('billto')) tep_session_register('billto');
				$sessVar = 'billing';
				break;
			case 'shipping':
				$sendto = $addressID;
				if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
				$sessVar = 'delivery';
				break;
		}

		$Qaddress = tep_db_query('select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from ' . TABLE_ADDRESS_BOOK . ' ab left join ' . TABLE_ZONES . ' z on (ab.entry_zone_id = z.zone_id) left join ' . TABLE_COUNTRIES . ' c on (ab.entry_country_id = c.countries_id) where ab.customers_id = "' . (int)$customer_id . '" and ab.address_book_id = "' . (int)$addressID . '"');
		$address = tep_db_fetch_array($Qaddress);

		$onepage[$sessVar] = array_merge($onepage[$sessVar], array(
		'firstname' => $address['entry_firstname'], 'lastname'       => $address['entry_lastname'],
		'company'   => $address['entry_company'],   'street_address' => $address['entry_street_address'],
		'suburb'    => $address['entry_suburb'],    'city'           => $address['entry_city'],
		'postcode'  => $address['entry_postcode'],  'state'          => $address['entry_state'],
		'zone_id'   => $address['entry_zone_id'],   'country' => array(
		'id'         => $address['countries_id'],         'title'      => $address['countries_name'],
		'iso_code_2' => $address['countries_iso_code_2'], 'iso_code_3' => $address['countries_iso_code_3']
		),
		'country_id' => $address['entry_country_id'], 'format_id' => $address['address_format_id']
		));

		if (ACCOUNT_STATE == 'true') {
			$this->fixZoneName($onepage[$sessVar]['zone_id'],$onepage[$sessVar]['country']['id'],$onepage[$sessVar]['state']);
		}

		return json_encode(array(
      		"success" => "true"
    	));
	}

	function saveAddress($action)
	{
	global $customer_id;
	if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
	if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
	$firstname = tep_db_prepare_input($_POST['firstname']);
	$lastname = tep_db_prepare_input($_POST['lastname']);
	$street_address = tep_db_prepare_input($_POST['street_address']);
	if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
	$postcode = tep_db_prepare_input($_POST['postcode']);
	$city = tep_db_prepare_input($_POST['city']);
	$country = tep_db_prepare_input($_POST['country']);
	if (isset($_POST['zone_id'])) 
		{
		$zone_id = tep_db_prepare_input($_POST['zone_id']);
		}
	else 
		{
		$zone_id = false;
		}
	$state = tep_db_prepare_input($_POST['state']);
	$zone_id = 0;
	$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
	$check = tep_db_fetch_array($check_query);
	$entry_state_has_zones = ($check['total'] > 0);
	if ($entry_state_has_zones == true)
		{
		$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
		if (tep_db_num_rows($zone_query) == 1)
			{
			$zone = tep_db_fetch_array($zone_query);
			$zone_id = $zone['zone_id'];
			}
		}
		$sql_data_array = array(
					'customers_id'         => $customer_id,
					'entry_firstname'      => $firstname,
					'entry_lastname'       => $lastname,
					'entry_street_address' => $street_address,
					'entry_postcode'       => $postcode,
					'entry_city'           => $city,
					'entry_country_id'     => $country);
		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		if (ACCOUNT_STATE == 'true')
			{
			if ($zone_id > 0)
				{
				$sql_data_array['entry_zone_id'] = $zone_id;
				$sql_data_array['entry_state'] = '';
				}
			else
				{
				$sql_data_array['entry_zone_id'] = '0';
				$sql_data_array['entry_state'] = $state;
				}
			}
		if ($action == 'saveAddress')
			{
			$Qcheck = tep_db_query('select address_book_id from ' . TABLE_ADDRESS_BOOK . ' where address_book_id = "' . $_POST['address_id'] . '" and customers_id = "' . $customer_id . '"');
			if (tep_db_num_rows($Qcheck))
				{
				tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', 'address_book_id = "' . $_POST['address_id'] . '"');
				}
			else
				{
				tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
				}
			}
	return json_encode(array(
			"success" => "true"
		));
	}

	function confirmCheckout(){
		global $customer_id, $comments, $order, $currencies, $request_type, $languages_id, $currency, $cart_PayPal_Standard_ID, $cart_PayPal_IPN_ID, $shipping, $cartID, $order_total_modules, $onepage, $credit_covers, $payment, $comments;

		if (tep_session_is_registered('customer_id')){
			$onepage['createAccount'] = false;
		}else{
			if (tep_not_null($_POST['password'])){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = $_POST['password'];
			}elseif (ONEPAGE_ACCOUNT_CREATE == 'create'){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
			}
		}

		$paymentMethod = $onepage['info']['payment_method'];

		$html = '';
		$infoMsg = 'Please press the continue button to confirm your order.';
		$formUrl = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type);
		if (tep_not_null($GLOBALS[$paymentMethod]->form_action_url)){
			$formUrl = $GLOBALS[$paymentMethod]->form_action_url;
			$infoMsg = 'Please press the continue button to proceed to the payment processors page.';
		}

		$GLOBALS[$paymentMethod]->pre_confirmation_check();

		$GLOBALS[$paymentMethod]->confirmation();

		$hiddenFields = $GLOBALS[$paymentMethod]->process_button();

		$html .= '<form name="redirectForm" action="' . $formUrl . '" method="POST">
				   <noscript>' .
		$infoMsg .
		tep_image_submit('button_continue.gif', IMAGE_CONTINUE) .
		'</noscript>' .
		tep_image_submit('button_continue.gif', IMAGE_CONTINUE, 'style="display:none;"') .
		$hiddenFields .
		'<script>
					 document.write(\'<img src="' . DIR_WS_MODULES . 'checkout/images/ajax-loader.gif"><br>Processing Order, Please Wait...\');
					 redirectForm.submit();
				   </script></form>';

		return $html;
	}
	function checkCartValidity($type = 'php', $redirect = true)
	{
		global $cart, $cartID;
		$invalid = false;
		if ($cart->count_contents() < 1) {
		}
		if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
			if ($cart->cartID != $cartID) {
				$invalid = true;
			}
		}


		if($invalid == true)
		{
			if($redirect == true)
			{
				$this->reset();
				tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			}
			else
			{
				if($type == 'php')
				return false;
				if($type == 'ajax')
				return json_encode(array("success" => "false"));
			}
		}
		if($type == 'php')
		return true;
		else
		return json_encode(array("success" => "true"));
	}

	function processCheckout(){
		global $customer_id, $comments, $coupon, $order, $currencies, $request_type, $languages_id, $currency,
		$customer_shopping_points_spending, $customer_referral, $cart_PayPal_Standard_ID, $cart_PayPal_IPN_ID,
		$cart_Worldpay_Junior_ID, $shipping, $cartID, $order_total_modules, $onepage, $credit_covers, $payment,
		$payment_modules;

		$comments = tep_db_prepare_input($_POST['comments']);
		if (!tep_session_is_registered('comments')) tep_session_register('comments');
		$onepage['customer']['comments'] = $_POST['comments'];
		//BOF KGT
		if(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS=='true')
		{
			$onepage['info']['coupon'] = $order->info['coupon'];
		}
		//EOF KGT
		$onepage['customer']['telephone'] = ((isset($_POST['billing_telephone']) && !empty($_POST['billing_telephone'])) ? $_POST['billing_telephone'] : '');
		$onepage['customer']['newsletter'] = (isset($_POST['billing_newsletter']) ? $_POST['billing_newsletter'] : '0');
		$order->customer = array_merge($order->customer,$onepage['customer']);

		if (tep_session_is_registered('customer_id')){
			$onepage['createAccount'] = false;
		}else{
			if (tep_not_null($_POST['password'])){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = $_POST['password'];
				$this->createCustomerAccount();
			}elseif (ONEPAGE_ACCOUNT_CREATE == 'create'){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
				$this->createCustomerAccount();
			}
		}
		$payment_modules->update_status();
		$paymentMethod = $onepage['info']['payment_method'];

		##### Points/Rewards Module V2.1rc2a check for error BOF #######
		if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
			if (isset($_POST['customer_shopping_points_spending']) && is_numeric($_POST['customer_shopping_points_spending']) && ($_POST['customer_shopping_points_spending'] > 0)) {
				$customer_shopping_points_spending = false;
				if($_POST['customer_shopping_points_spending']>tep_get_shopping_points($customer_id))
				{
					$_POST['customer_shopping_points_spending'] = tep_get_shopping_points($customer_id);
				}
				$customer_shopping_points = tep_get_shopping_points();
				$max_points = calculate_max_points($customer_shopping_points);
				if($points > $max_points)
				$points = $max_points;

				if (tep_calc_shopping_pvalue($_POST['customer_shopping_points_spending']) < $order->info['total'] && ($paymentMethod == '' || $paymentMethod == 'credit_covers')) {
					$customer_shopping_points_spending = false;
					tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REDEEM_SYSTEM_ERROR_POINTS_NOT), 'SSL'));
				} else {
					$customer_shopping_points_spending = $_POST['customer_shopping_points_spending'];
					if (!tep_session_is_registered('customer_shopping_points_spending')) tep_session_register('customer_shopping_points_spending');
				}
			}

			if (tep_not_null(USE_REFERRAL_SYSTEM)) {
				if (isset($_POST['customer_referred']) && tep_not_null($_POST['customer_referred'])) {
					$customer_referral = false;
					$check_mail = trim($_POST['customer_referred']);
					if (tep_validate_email($check_mail) == false) {
						tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_VALID), 'SSL'));
					} else {
						$valid_referral_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $check_mail . "' limit 1");
						$valid_referral = tep_db_fetch_array($valid_referral_query);
						if (!tep_db_num_rows($valid_referral_query)) {
							tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
						}

						if ($check_mail == $order->customer['email_address']) {
							tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_SELF), 'SSL'));
						} else {
							$customer_referral = $valid_referral['customers_id'];
							if (!tep_session_is_registered('customer_referral')) tep_session_register('customer_referral');
						}
					}
				}
			}
		}
		##### Points/Rewards Module V2.1rc2a check for error EOF #######

		if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
			// Start - CREDIT CLASS Gift Voucher Contribution
			if ($credit_covers) $paymentMethod = 'credit_covers';
			unset($_POST['gv_redeem_code']);
			unset($_POST['gv_redeem_code']);
			$order_total_modules->collect_posts();
			$order_total_modules->pre_confirmation_check();
			// End - CREDIT CLASS Gift Voucher Contribution
		}
		if(($order->info['total']) <=0) //if(($order->info['total'] - $order->info['tax'] - $order->info['shipping_cost']) <=0)
		{
			$payment = '';
			$paymentMethod = '';
			$onepage['info']['payment_method'] = '';
			//$onepage['info']['order_id'] = '';
		}

		$html = '';
		$hiddenFields = '';
		$infoMsg = 'Please press the continue button to confirm your order.';
		$formUrl = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type);
		if ($paymentMethod != '' && $paymentMethod != 'credit_covers'){
			if (tep_not_null($GLOBALS[$paymentMethod]->form_action_url)){
				$formUrl = $GLOBALS[$paymentMethod]->form_action_url;
				$infoMsg = 'Please press the continue button to proceed to the payment processors page.';
			}
			
			$GLOBALS[$paymentMethod]->pre_confirmation_check();
			$GLOBALS[$paymentMethod]->confirmation();

			if (tep_session_is_registered('cart_PayPal_IPN_ID')){
				$onepage['info']['order_id'] = substr($cart_PayPal_IPN_ID, strpos($cart_PayPal_IPN_ID, '-')+1);;
			}

			if (tep_session_is_registered('cart_PayPal_Standard_ID')){
				$onepage['info']['order_id'] = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);;
			}

			if (tep_session_is_registered('cart_Worldpay_Junior_ID')){
				$onepage['info']['order_id'] = substr($cart_Worldpay_Junior_ID, strpos($cart_Worldpay_Junior_ID, '-')+1);;
			}

			$hiddenFields = $GLOBALS[$paymentMethod]->process_button();
			if (tep_not_null($hiddenFields)){
				foreach($_POST as $varName => $val){
					if (is_array($_POST[$varName])){
						foreach($_POST[$varName] as $varName2 => $val2){
							$hiddenFields .= tep_draw_hidden_field($varName2, $val2);
						}
					}else{
						$hiddenFields .= tep_draw_hidden_field($varName, $val);
					}
				}
			}
		}
		$html .= '<form name="redirectForm" action="' . $formUrl . '" method="POST">
           	<noscript>' . $infoMsg . tep_image_submit('button_continue.gif',IMAGE_CONTINUE) . '</noscript>' . 
		tep_image_submit('button_continue.gif',IMAGE_CONTINUE,'style="display:none;"') . $hiddenFields . 
		'<script>
           		document.write(\'<div style="width:100%;height:100%;margin-left:auto;margin-top:auto;text-align:center"><img src="' . DIR_WS_HTTP_CATALOG . 'includes/modules/checkout/images/ajax-loader.gif"><br>Processing Order, Please Wait...</div>\');
            		setTimeout("redirectForm.submit()", 3000);  
           	</script></form>';


		return $html;
	}

	function createCustomerAccount(){
		global $currencies, $customer_id, $onepage, $customer_default_address_id, $customer_first_name, $customer_country_id, $customer_zone_id, $languages_id, $sendto, $billto;
		if ($onepage['createAccount'] === true && $this->checkEmailAddress($onepage['customer']['email_address'])){

			$sql_data_array = array(
			'customers_firstname'     => $onepage['billing']['firstname'],
			'customers_lastname'      => $onepage['billing']['lastname'],
			'customers_email_address' => $onepage['customer']['email_address'],
			'customers_telephone'     => $onepage['customer']['telephone'],
			'customers_fax'           => $onepage['customer']['fax'],
			'customers_newsletter'    => $onepage['customer']['newsletter'],
			'customers_password'      => tep_encrypt_password($onepage['customer']['password'])
			);

			if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $onepage['billing']['gender'];
			if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($onepage['customer']['dob']);

			tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
			$customer_id = tep_db_insert_id();

			$sql_data_array = array(
			'customers_id'         => $customer_id,
			'entry_firstname'      => $onepage['billing']['firstname'],
			'entry_lastname'       => $onepage['billing']['lastname'],
			'entry_street_address' => $onepage['billing']['street_address'],
			'entry_postcode'       => $onepage['billing']['postcode'],
			'entry_city'           => $onepage['billing']['city'],
			'entry_country_id'     => $onepage['billing']['country_id']
			);

			if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $onepage['billing']['gender'];
			if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $onepage['billing']['company'];
			if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $onepage['billing']['suburb'];
			if (ACCOUNT_STATE == 'true') {
				$state = $onepage['billing']['state'];
				$zone_name = '';

				$zone_id = 0;
				$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$onepage['billing']['country_id'] . "'");
				$check = tep_db_fetch_array($check_query);
				$entry_state_has_zones = ($check['total'] > 0);
				if ($entry_state_has_zones == true) {
					$zone_query = tep_db_query("select distinct zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$onepage['billing']['country_id'] . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
					if (tep_db_num_rows($zone_query) == 1) {
						$zone = tep_db_fetch_array($zone_query);
						$zone_id = $zone['zone_id'];
						$zone_name = $zone['zone_name'];
					}
				}

				if ($zone_id > 0) {
					$sql_data_array['entry_zone_id'] = $zone_id;
					$sql_data_array['entry_state'] = '';
				} else {
					$sql_data_array['entry_zone_id'] = '0';
					$sql_data_array['entry_state'] = $state;
				}
			}

			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

			$address_id = tep_db_insert_id();
			$billto = $address_id;
			$sendto = $address_id;

			$customer_default_address_id = $address_id;
			$customer_first_name = $onepage['billing']['firstname'];
			$customer_country_id = $onepage['billing']['country_id'];
			$customer_zone_id = $zone_id;

			if (isset($_POST['diffShipping'])){
				$sql_data_array = array(
				'customers_id'         => $customer_id,
				'entry_firstname'      => $onepage['delivery']['firstname'],
				'entry_lastname'       => $onepage['delivery']['lastname'],
				'entry_street_address' => $onepage['delivery']['street_address'],
				'entry_postcode'       => $onepage['delivery']['postcode'],
				'entry_city'           => $onepage['delivery']['city'],
				'entry_country_id'     => $onepage['delivery']['country_id']
				);

				if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $onepage['delivery']['gender'];
				if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $onepage['delivery']['company'];
				if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $onepage['delivery']['suburb'];
				if (ACCOUNT_STATE == 'true') {
					$state = $onepage['delivery']['state'];
					$zone_name = '';

					$zone_id = 0;
					$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$onepage['delivery']['country_id'] . "'");
					$check = tep_db_fetch_array($check_query);
					$entry_state_has_zones = ($check['total'] > 0);
					if ($entry_state_has_zones == true) {
						$zone_query = tep_db_query("select distinct zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$onepage['delivery']['country_id'] . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
						if (tep_db_num_rows($zone_query) == 1) {
							$zone = tep_db_fetch_array($zone_query);
							$zone_id = $zone['zone_id'];
							$zone_name = $zone['zone_name'];
						}
					}

					if ($zone_id > 0) {
						$sql_data_array['entry_zone_id'] = $zone_id;
						$sql_data_array['entry_state'] = '';
					} else {
						$sql_data_array['entry_zone_id'] = '0';
						$sql_data_array['entry_state'] = $state;
					}
				}

				tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
				$sendto = tep_db_insert_id();
			}

			tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
			tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

			$Qcustomer = tep_db_query('select customers_firstname, customers_lastname, customers_email_address from ' . TABLE_CUSTOMERS . ' where customers_id = "' . $customer_id . '"');
			$customer = tep_db_fetch_array($Qcustomer);
			$name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];

			if (ACCOUNT_GENDER == 'true') {
				if ($sql_data_array['entry_gender'] == ''){
					$email_text = sprintf(EMAIL_GREET_NONE, $customer['customers_firstname'] . ' ' . $customer['customers_lastname']);
				}elseif ($sql_data_array['entry_gender'] == 'm') {
					$email_text = sprintf(EMAIL_GREET_MR, $customer['customers_lastname']);
				} else {
					$email_text = sprintf(EMAIL_GREET_MS, $customer['customers_lastname']);
				}
			} else {
				$email_text = sprintf(EMAIL_GREET_NONE, $customer['customers_firstname']);
			}

			$email_text .= EMAIL_WELCOME;

			$email_text .= 'You can log into your account using the following' . "\n" .
			'Username: ' . $onepage['customer']['email_address'] . "\n" .
			'Password: ' . $onepage['customer']['password'] . "\n\n";

			$email_text .= EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;

			if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
				// Start - CREDIT CLASS Gift Voucher Contribution
				if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
					$coupon_code = create_coupon_code();
					tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
					$insert_id = tep_db_insert_id();
					tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $customer['customers_email_address'] . "', now() )");

					$email_text .= sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
					sprintf(EMAIL_GV_REDEEM, $coupon_code) . "\n\n" .
					EMAIL_GV_LINK . tep_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code,'NONSSL', false) . "\n\n";
				}

				if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
					$coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
					$coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'");
					$coupon = tep_db_fetch_array($coupon_query);
					$coupon_id = $coupon['coupon_id'];
					$coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$languages_id . "'");
					$coupon_desc = tep_db_fetch_array($coupon_desc_query);
					tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $customer['customers_email_address'] . "', now() )");
					$email_text .= EMAIL_COUPON_INCENTIVE_HEADER .  "\n" .
					sprintf("%s", $coupon_desc['coupon_description']) ."\n\n" .
					sprintf(EMAIL_COUPON_REDEEM, $coupon['coupon_code']) . "\n\n" . "\n\n";
				}
				// End - CREDIT CLASS Gift Voucher Contribution
			}
			$onepage['createAccount'] = false;
			tep_mail($name, $customer['customers_email_address'], EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

			if (isset($onepage['info']['order_id'])){
				tep_db_query('update ' . TABLE_ORDERS . ' set customers_id = "' . $customer_id . '" where orders_id = "' . $onepage['info']['order_id'] . '"');
				unset($onepage['info']['order_id']);
			}
			if (!tep_session_is_registered('customer_id')) tep_session_register('customer_id');
			if (!tep_session_is_registered('customer_default_address_id')) tep_session_register('customer_default_address_id');
			if (!tep_session_is_registered('customer_first_name')) tep_session_register('customer_first_name');
			if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
			if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');
			if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
			if (!tep_session_is_registered('billto')) tep_session_register('billto');
		}else
		{
			$onepage['createAccount'] = false;
			//tep_redirect(tep_href_link(FILENAME_CHECKOUT,'error='.url_encode('Your email address already exists in our records')));
		}
	}

	function redeemCoupon($code){
		//BOF KGT
		if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
			//EOF KGT
			global $customer_id, $order, $credit_covers;
			$error = false;
			if ($code) {
				// get some info from the coupon table
				$coupon_query = tep_db_query("select coupon_id, coupon_amount, coupon_type, coupon_minimum_order,uses_per_coupon, uses_per_user, restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='".$code."' and coupon_active='Y'");
				$coupon_result = tep_db_fetch_array($coupon_query);

				if ($coupon_result['coupon_type'] != 'G') {
					if (tep_db_num_rows($coupon_query) == 0) {
						$error = true;
						$errMsg = ERROR_NO_INVALID_REDEEM_COUPON;
					}

					$date_query = tep_db_query("select coupon_start_date from " . TABLE_COUPONS . " where coupon_start_date <= now() and coupon_code='".$code."'");
					if (tep_db_num_rows($date_query) == 0) {
						$error = true;
						$errMsg = ERROR_INVALID_STARTDATE_COUPON;
					}

					$date_query = tep_db_query("select coupon_expire_date from " . TABLE_COUPONS . " where coupon_expire_date >= now() and coupon_code='".$code."'");
					if (tep_db_num_rows($date_query) == 0) {
						$error = true;
						$errMsg = ERROR_INVALID_FINISDATE_COUPON;
					}

					$coupon_count = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id']."'");
					$coupon_count_customer = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id']."' and customer_id = '" . $customer_id . "' and customer_id>0");
					if (tep_db_num_rows($coupon_count) >= $coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0) {
						$error = true;
						$errMsg = ERROR_INVALID_USES_COUPON . $coupon_result['uses_per_coupon'] . TIMES;
					}

					if (tep_db_num_rows($coupon_count_customer) >= $coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0) {
						$error = true;
						$errMsg = ERROR_INVALID_USES_USER_COUPON . $coupon_result['uses_per_user'] . TIMES;
					}

					if ($error === false){
						global $order_total_modules, $cc_id;
						$cc_id = $coupon_result['coupon_id'];
						if (!tep_session_is_registered('cc_id')) tep_session_register('cc_id');
						$order_total_modules->pre_confirmation_check();
						if(!tep_session_is_registered('credit_covers')){ tep_session_register('credit_covers');$credit_covers=true; }
						return json_encode(array(
              				"success" => "true"
            			));
					}else{
						if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
						}
				}
			}
			//BOF KGT
		}else
		{
			if(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS=='true')
			{
				global $customer_id, $order;
				$check_code_query = tep_db_query( $sql = "SELECT dc.*
                                                  FROM " . TABLE_DISCOUNT_COUPONS . " dc
                                                  WHERE coupons_id = '".tep_db_input( $code )."'
                                                    AND ( coupons_date_start <= CURDATE() OR coupons_date_start IS NULL )
                                                    AND ( coupons_date_end >= CURDATE() OR coupons_date_end IS NULL )" );
				if( tep_db_num_rows( $check_code_query ) != 1 ) { //if no rows are returned, then they haven't entered a valid code
					$message =  ENTRY_DISCOUNT_COUPON_ERROR ; //display the error message
					return json_encode(array(
              			"success" => "false"
            		));
				} else {
					if(tep_session_is_registered('customer_id') && (int)$customer_id>0)
					{
						//customer_exclusions
						$check_user_query = tep_db_query( $sql = 'SELECT dc2u.customers_id
                                                      FROM '.TABLE_DISCOUNT_COUPONS_TO_CUSTOMERS.' dc2u
                                                      WHERE customers_id='.(int)$customer_id.'
                                                        AND coupons_id="'.tep_db_input( $code ).'"' );
						if( tep_db_num_rows( $check_user_query ) > 0 ) {
							$message =  ENTRY_DISCOUNT_COUPON_ERROR ; //display the error message
							//use this to debug exclusions:
							//$this->message( 'Customer exclusion check failed' );
							return json_encode(array(
              					"success" => "false"
            				));
						}
					}
					//shipping zone exclusions
					$delivery = $order->delivery;
					$check_user_query = tep_db_query($sql = 'SELECT dc2z.geo_zone_id
                                                    FROM '.TABLE_DISCOUNT_COUPONS_TO_ZONES.' dc2z
                                                    LEFT JOIN '.TABLE_ZONES_TO_GEO_ZONES.' z2g
                                                      USING( geo_zone_id )
                                                    WHERE ( z2g.zone_id='.(int)$delivery['zone_id'].' or z2g.zone_id = 0 or z2g.zone_id IS NULL )
                                                      AND ( z2g.zone_country_id='.(int)$delivery['country_id'].' or z2g.zone_country_id = 0 )
                                                      AND dc2z.coupons_id="'.tep_db_input( $code ).'"' );

					if (tep_db_num_rows( $check_user_query ) > 0 ) {
						$message =   ENTRY_DISCOUNT_COUPON_ERROR ; //display the error message
						return json_encode(array("success" => "false"));
					}
					//end shipping zone exclusions
					$row = tep_db_fetch_array( $check_code_query );
					$order->coupon = $row;
					return json_encode(array(
						"success" => "true"
					));
				}
			}
		}
		//EOF KGT
		return json_encode(array(
              "success" => "false", 
			  "message" => "7. end"
            ));
	}

	function getAddressFormatted($type){
		global $order;
		switch($type){
			case 'sendto':
				$address = $order->delivery;
				break;
			case 'billto':
				$address = $order->billing;
				break;
		}
		return tep_address_format($address['format_id'], $address, false, '', "\n");
	}

	function verifyContents(){
		global $cart;
		// if there is nothing in the customers cart, redirect them to the shopping cart page
		if ($cart->count_contents() < 1) {
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
		}
	}

	function checkStock(){
		global $cart;
		$products = $cart->get_products();
		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
				tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
				break;
			}
		}
	}

	function setDefaultSendTo(){
		global $sendto, $customer_id, $customer_default_address_id, $shipping;
		if (!tep_session_is_registered('sendto')) {
			$sendto = $customer_default_address_id;
			tep_session_register('sendto');
		} else {
			if ((is_array($sendto) && !tep_not_null($sendto)) || is_numeric($sendto)) {
				$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
				$check_address = tep_db_fetch_array($check_address_query);

				if ($check_address['total'] != '1') {
					$sendto = $customer_default_address_id;
					if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
				}
			}
		}
		if (empty($sendto)) $sendto = -42;
		$this->setAddress('shipping', $sendto);
	}

	function setDefaultBillTo(){
		global $billto, $customer_id, $customer_default_address_id, $shipping;
		if (!tep_session_is_registered('billto')) {

			$billto = $customer_default_address_id;
			tep_session_register('billto');
		} else {
			if ( (is_array($billto) && !tep_not_null($billto)) || is_numeric($billto) ) {
				$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
				$check_address = tep_db_fetch_array($check_address_query);

				if ($check_address['total'] != '1') {
					$billto = $customer_default_address_id;
					if (tep_session_is_registered('payment')) tep_session_unregister($payment);
				}
			}
		}
		if (empty($sendto)) $billto = -42;
		$this->setAddress('billing', $billto);
	}

	function removeCCGV(){
		global $credit_covers, $cot_gv;
		// Start - CREDIT CLASS Gift Voucher Contribution
		if (tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
		if (tep_session_is_registered('cot_gv')) tep_session_unregister('cot_gv');
		// End - CREDIT CLASS Gift Voucher Contribution
	}

	function decode_post_vars()
	{
		global $_POST;
		$_POST = $this->decode_inputs($_POST);
		$_POST = $this->decode_inputs($_POST);
	}

	function decode_inputs($inputs)
	{
		if (!is_array($inputs) && !is_object($inputs)) {
			if(function_exists('mb_check_encoding') && mb_check_encoding($inputs,'UTF-8'))
			return utf8_decode($inputs);
			else
			return $inputs;
		}
		elseif (is_array($inputs))
		{
			reset($inputs);
			while (list($key, $value) = each($inputs)) {
				$inputs[$key] = $this->decode_inputs($value);
			}
			return $inputs;
		}
		else
		{
			return $inputs;
		}
	}
}
?>