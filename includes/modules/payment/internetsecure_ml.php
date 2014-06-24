<?php
  class internetsecure_ml {
    var $code, $title, $description, $enabled, $identifier;

// class constructor
    function internetsecure_ml() {
      global $order;

      $this->code = 'internetsecure_ml';
      $this->title = MODULE_PAYMENT_INTERNETSECURE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_INTERNETSECURE_CC_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_STATUS == 'True') ? true : false);
      $this->identifier = 'osCommerce InternetSecure MerchantLink';

      if ((int)MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_PREPARE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_PREPARE_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = 'https://secure.internetsecure.com/process.cgi';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_INTERNERSECURE_DL_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_INTERNERSECURE_DL_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

	    $billship = true;
			  if(($this->enabled == true) && !$billship ){
			    $this->enabled = false;
			  }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
	  $img_visa = '';
    $img_mc = '';
    $img_discover = '';
    $img_amex = '';
    $img_IS = 'images/paymentswithIS.png';
	  $cc_explain = MODULE_PAYMENT_INTERNETSECURE_CC_DESCRIPTION;
    $IS_cc_txt = tep_image($img_IS,' InternetSecure ','','','align="absmiddle"');
      $fields[] = array('title' => '', //MODULE_PAYMENT_INTERNETSECURE_TEXT_TITLE,
                        'field' => '<div>' . $IS_cc_txt . '</div><br/>' . $cc_explain );
      return array('id' => $this->code,
                   'module' => $this->title,
                   'fields' => $fields);
    }

    function pre_confirmation_check() {
      return false;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function confirmation() {
      global $cartID, $cart_InternetSecure_DL_ID, $customer_id, $languages_id, $order, $order_total_modules;

     // if (tep_session_is_registered('cartID')) {
	 // PandA.nl: register_globals fix
     if (array_key_exists('cartID', $_SESSION)) {
        $insert_order = false;

        if (tep_session_is_registered('cart_InternetSecure_DL_ID')) {
          $order_id = substr($cart_InternetSecure_DL_ID, strpos($cart_InternetSecure_DL_ID, '-')+1);

          $curr_check = tep_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
          $curr = tep_db_fetch_array($curr_check);

          //if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($cart_InternetSecure_DL_ID, 0, strlen($cartID))) ) {
            //$check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');
	    $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 2');
            $update_order = false;

            //if (tep_db_num_rows($check_query) < 1) {
              //tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
			if (tep_db_num_rows($check_query) == 1) {
			  $update_order = true;
              tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
              tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
              tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
              tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
              tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');
            }

            $insert_order = true;
          //}
        } else {
          $insert_order = true;
        }

        if ($insert_order == true) {
          $order_totals = array();
          if (is_array($order_total_modules->modules)) {
            reset($order_total_modules->modules);
            while (list(, $value) = each($order_total_modules->modules)) {
              $class = substr($value, 0, strrpos($value, '.'));
              if ($GLOBALS[$class]->enabled) {
                for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
                  if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                    $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                            'title' => $GLOBALS[$class]->output[$i]['title'],
                                            'text' => $GLOBALS[$class]->output[$i]['text'],
                                            'value' => $GLOBALS[$class]->output[$i]['value'],
                                            'sort_order' => $GLOBALS[$class]->sort_order);
                  }
                }
              }
            }
          }


          $sql_data_array = array('customers_id' => $customer_id,
                                  'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                                  'customers_company' => $order->customer['company'],
                                  'customers_street_address' => $order->customer['street_address'],
                                  'customers_suburb' => $order->customer['suburb'],
                                  'customers_city' => $order->customer['city'],
                                  'customers_postcode' => $order->customer['postcode'],
                                  'customers_state' => $order->customer['state'],
                                  'customers_country' => $order->customer['country']['title'],
                                  'customers_telephone' => $order->customer['telephone'],
                                  'customers_email_address' => $order->customer['email_address'],
                                  'customers_address_format_id' => $order->customer['format_id'],
                                  'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                                  'delivery_company' => $order->delivery['company'],
                                  'delivery_street_address' => $order->delivery['street_address'],
                                  'delivery_suburb' => $order->delivery['suburb'],
                                  'delivery_city' => $order->delivery['city'],
                                  'delivery_postcode' => $order->delivery['postcode'],
                                  'delivery_state' => $order->delivery['state'],
                                  'delivery_country' => $order->delivery['country']['title'],
                                  'delivery_address_format_id' => $order->delivery['format_id'],
                                  'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                                  'billing_company' => $order->billing['company'],
                                  'billing_street_address' => $order->billing['street_address'],
                                  'billing_suburb' => $order->billing['suburb'],
                                  'billing_city' => $order->billing['city'],
                                  'billing_postcode' => $order->billing['postcode'],
                                  'billing_state' => $order->billing['state'],
                                  'billing_country' => $order->billing['country']['title'],
                                  'billing_address_format_id' => $order->billing['format_id'],
                                  'payment_method' => $order->info['payment_method'],
                                  'cc_type' => $order->info['cc_type'],
                                  'cc_owner' => $order->info['cc_owner'],
                                  'cc_number' => $order->info['cc_number'],
                                  'cc_expires' => $order->info['cc_expires'],
                                  'date_purchased' => 'now()',
                                  'orders_status' => $order->info['order_status'],
                                  'currency' => $order->info['currency'],
                                  'currency_value' => $order->info['currency_value']
                                  );


		  if ( $update_order ){
		    tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = "' . (int)$order_id . '"');
            $insert_id = (int)$order_id;
		  } else {

          tep_db_perform(TABLE_ORDERS, $sql_data_array);

          $insert_id = tep_db_insert_id();
		  }

          for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
            $sql_data_array = array('orders_id' => $insert_id,
                                    'title' => $order_totals[$i]['title'],
                                    'text' => $order_totals[$i]['text'],
                                    'value' => $order_totals[$i]['value'],
                                    'class' => $order_totals[$i]['code'],
                                    'sort_order' => $order_totals[$i]['sort_order']);

            tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
          }

		     $sql_data_array = array('orders_id' => $insert_id,
                                    'orders_status_id' => $order->info['order_status'],
                                    'date_added' => 'now()',
						                         'customer_notified' => '0',
                                    'comments' => $order->info['comments']);
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


            //kgt - discount coupons this is updating properly the coupon has been applied to the order number of the customer
            if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
                if( tep_session_is_registered( 'coupon' ) && is_object( $order->coupon ) ) {
                  $sql_data_array = array( 'coupons_id' => $order->coupon->coupon['coupons_id'],
                                         'orders_id' => $insert_id );
                  tep_db_perform( TABLE_DISCOUNT_COUPONS_TO_ORDERS, $sql_data_array );
              }
            }
          //end kgt - discount coupons

          for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
            $sql_data_array = array('orders_id' => $insert_id,
                                    'products_id' => tep_get_prid($order->products[$i]['id']),
                                    'products_model' => $order->products[$i]['model'],
                                    'products_name' => $order->products[$i]['name'],
                                    'products_price' => $order->products[$i]['price'],
                                    'final_price' => $order->products[$i]['final_price'],
                                    'products_tax' => $order->products[$i]['tax'],
                                    'products_quantity' => $order->products[$i]['qty']);

            tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

            $order_products_id = tep_db_insert_id();

            $attributes_exist = '0';
            if (isset($order->products[$i]['attributes'])) {
              $attributes_exist = '1';
              for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                if (DOWNLOAD_ENABLED == 'true') {
                  $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                       from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                       left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                       on pa.products_attributes_id=pad.products_attributes_id
                                       where pa.products_id = '" . $order->products[$i]['id'] . "'
                                       and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'";
                  $attributes = tep_db_query($attributes_query);
                } else {
                  $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
                }
                $attributes_values = tep_db_fetch_array($attributes);

                $sql_data_array = array('orders_id' => $insert_id,
                                        'orders_products_id' => $order_products_id,
                                        'products_options' => $attributes_values['products_options_name'],
                                        'products_options_values' => $attributes_values['products_options_values_name'],
                                        'options_values_price' => $attributes_values['options_values_price'],
                                        'price_prefix' => $attributes_values['price_prefix']);

                tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

                if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
                  $sql_data_array = array('orders_id' => $insert_id,
                                          'orders_products_id' => $order_products_id,
                                          'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                          'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                          'download_count' => $attributes_values['products_attributes_maxcount']);

                  tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
                }
              }
            }
          }

        // FS start
        $GLOBALS['cart_InternetSecure_DL_ID'] = $cartID . '-' . $insert_id;
        // FS stop
          tep_session_register('cart_InternetSecure_DL_ID');
          // Terra register globals fix
          $_SESSION['cart_InternetSecure_DL_ID'] = $cartID . '-' . $insert_id;
        }
      }

      return false;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function process_button() {
      	global  $_SERVER, $customer_id, $order, $customer_province, $us_conversion_rate,
      	$can_conversion_rate,$currencies, $currency, $test_status,$default_rate, $merchant_ID;


	// This mod uses the 'Dynamic' method to post to IS
	//Currency Exchange rates
	$default_rate    	 = 1;
	$us_conversion_rate  = 1;
	$can_conversion_rate = 1;
	$merchant_ID = 0;

	$check_flag = false;
	$check_query = tep_db_query("select code,value from " . TABLE_CURRENCIES. " where code = 'USD' or code = 'CAD'");

	while ($check = tep_db_fetch_array($check_query)) {
	  if ($check['code']=='USD') {
	     $us_conversion_rate = $check['value'];
	  } elseif ($check['code'] == 'CAD') {
	     $can_conversion_rate = $check['value'];
      }
	}
	// Our currency switch
	$trx_currency ='';
 	switch (MODULE_PAYMENT_ISECURE_CURRENCY) {
        case 'Always USD':

			//set the rate to the database exchange rate
			$default_rate = $us_conversion_rate;

			//set the currency flag
			$trx_currency = '::{US}';

			//set the InternetSecure Merchant Account
			$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD;

			break;
        case 'Always CAD':

			//set the rate to the database exchange rate
			$default_rate = $can_conversion_rate;

			//set the currency flag
			$trx_currency = '::{}';

			//set the InternetSecure Merchant Account
			$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD;

			break;
     	case 'Either CAD or USD, else USD':
			switch ($currency)
			{
				case 'CAD':
					//set the rate to the database exchange rate
					$default_rate = $can_conversion_rate;

					//set the currency flag
					$trx_currency =  '::{}';

					//set the InternetSecure Merchant Account
					$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD;

					break;
				case 'USD':
					//set the rate to the database exchange rate
					$default_rate = $us_conversion_rate;

					//set the currency flag
					$trx_currency =  '::{US}';

					//set the InternetSecure Merchant Account
					$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD;

					break;
				default:
					//set the rate to the database exchange rate
					$default_rate = $us_conversion_rate;

					//set the currency flag
					$trx_currency =  '::{US}';

					//set the InternetSecure Merchant Account
					$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD;

				break;
     	  }
          break;
		case 'Either USD or CAD, else CAD':
		  switch ($currency)
		  {
			case 'CAD':
				//set the rate to the database exchange rate
				$default_rate = $can_conversion_rate;

				//set the currency flag
				$trx_currency =  '::{}';

				//set the InternetSecure Merchant Account
				$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD;
				break;

			case 'USD':
				//set the rate to the database exchange rate
				$default_rate = $us_conversion_rate;

				//set the currency flag
				$trx_currency =  '::{US}';

				//set the InternetSecure Merchant Account
				$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD;

				break;

			default:
				//set the rate to the database exchange rate
				$default_rate = $can_conversion_rate;

				//set the currency flag
				$trx_currency =  '::{}';

				//set the InternetSecure Merchant Account
				$merchant_ID = MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD;

				break;
		  }

		  break;
		}

// Our Test status switch
      switch (MODULE_PAYMENT_ISECURE_TEST_STATUS) {
        case 'Declined':
          $test_status = '{TESTD}';
          break;
        case 'Approved':
          $test_status = '{TEST}';
          break;
        case 'LIVE':
        default:
          $test_status = '';
          break;
      }
//We need the default currency of the merchant
//override the testing $price value
//$price = $order->info['total']* $currencies->get_value($currency);
//biling info

$cust_state = $order->billing['state'];
 //customer province
	  $check_flag1 = false;
	  $check_query = tep_db_query("select zone_code from " . TABLE_ZONES. " where zone_name = '$cust_state'");
	  while ($check = tep_db_fetch_array($check_query)) {
	  	$billing_province = $check['zone_code'];
	}

//product list
$prod_list ='';
$tax_total=0;
$shipping_cost =0;
$prodlist_size = sizeof($order->products);
$shipping_cost = number_format($order->info['shipping_cost']* $default_rate,2,'.',',');
$tax_total 	   = number_format($order->info['tax'] * $default_rate,2,'.',',');

$subtotal_for_coupons = 0;

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	$prod_qty    		=	$order->products[$i]['qty'];
	$prod_price  		=   number_format($order->products[$i]['final_price'] * $default_rate,2,'.',',');
	$prod_code	 		=   $order->products[$i]['model'];
	$prod_descr			=	$order->products[$i]['name'];

	$subtotal_for_coupons += number_format($prod_qty * $order->products[$i]['final_price'] * $default_rate,2,'.',',');
	//$prod_tax			=   $order->products[$i]['tax'];
	//$tax_total 			+=  ($prod_tax * $order->products[$i]['qty'] * $default_rate);

	$prod_list         =  $prod_list.''.$prod_price.'::' .$prod_qty.'::'.$prod_code.'::'.$prod_descr.''.$trx_currency.''.$test_status.'|' ;

}

$myorders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
$myorders = tep_db_fetch_array($myorders_query);

//COUPON START
//kgt - discount coupons this is updating properly the coupon has been applied to the order number of the customer
if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
  //get coupon value for the order and pass it as a variable to internetsecure
  $check_coupon_query = tep_db_query("select coupons_id from discount_coupons_to_orders where orders_id = ".$myorders['orders_id']."");
  //check if coupon actaully used for this order
  if(tep_db_num_rows($check_coupon_query)){
    //cycle through until last result becuase if user clicked back in their browser then a previous coupon might be used - so we eliminate this possibility
    while ($latest_coupon = tep_db_fetch_array($check_coupon_query)){
      $coupon_used = $latest_coupon['coupons_id'];
    }
    //select coupons_discount_amount and coupons_discount_type from discount_coupons
    //next caluate the actual discount amount and apply it
    $coupon_values_query = tep_db_query("select coupons_discount_amount, coupons_discount_type from discount_coupons where coupons_id = '".$coupon_used."'");
    if( tep_db_num_rows($coupon_values_query) == 1){
       //coupon and values exists now calculate the discount
        $the_coupon = tep_db_fetch_array($coupon_values_query);
        if( $the_coupon['coupons_discount_type'] == 'fixed' ){ //FIXED DISCOUNT
                $coupon_discount = -1 * $the_coupon['coupons_discount_amount'];
        }elseif($the_coupon['coupons_discount_type'] == 'percent'){ //PERCENTAGE DISCOUNT APPLIED TO SUB_TOTAL
                $coupon_discount = number_format(-1 * $subtotal_for_coupons  * $the_coupon['coupons_discount_amount'],2,'.',',');
        }else{ //SHIPPING DISCOUNT
                $coupon_discount = number_format(-1 * $shipping_cost * $the_coupon['coupons_discount_amount'],2,'.',',');
        }
   }
   $prod_list =  $prod_list.''.$coupon_discount.'::1::'.$coupon_used.'::Coupon Discount |' ;
  }
}
//COUPON END

//add the tax as a product line
$prod_list         =  $prod_list.''.$shipping_cost.'::1::SH::Shipping and Handling'.$trx_currency.''.$test_status.'|' ;
$prod_list         =  $prod_list.''.$tax_total.'::1::Tax::Taxes for order #' .   $myorders['orders_id']  . '|' ;




	$process_button_string =     tep_draw_hidden_field('MerchantNumber', $merchant_ID) .
	                             tep_draw_hidden_field('language', MODULE_PAYMENT_ISECURE_LANGUAGE) .
	                             tep_draw_hidden_field('ReturnURL', tep_href_link(FILENAME_CHECKOUT_PROCESS))   .
	                             tep_draw_hidden_field('xxxName', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .  //$order->billing['name']) .
	                             tep_draw_hidden_field('xxxAddress', $order->billing['street_address']) .
	                             tep_draw_hidden_field('xxxCity', $order->billing['city']) .
	                             tep_draw_hidden_field('xxxProvince', $billing_province) .
	                             tep_draw_hidden_field('xxxPostal', $order->billing['postcode']) .
	                             tep_draw_hidden_field('xxxCountry',$order->billing['country']['iso_code_2']) .
	                             tep_draw_hidden_field('xxxEmail',  $order->customer['email_address']) .
	                             tep_draw_hidden_field('xxxPhone', $order->customer['telephone']) .
								 							 tep_draw_hidden_field('xxxVar1', $myorders['orders_id']) .
								 							 tep_draw_hidden_field('xxxVar2', MODULE_PAYMENT_ISECURE_MYSECURITY_CODE) .
								               tep_draw_hidden_field('xxxVar3',  $customer_id) .
								               tep_draw_hidden_field('Products', ''.$prod_list.''.$trx_currency.''.$test_status.'') .
	                             tep_draw_hidden_field('cancel_return', tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL')) ;


      return $process_button_string;
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function before_process() {
      global $customer_id, $order, $sendto, $billto, $payment, $languages_id, $currencies, $cart, $cart_InternetSecure_DL_ID;
      global $$payment;
// missing attribute fix end

      //include(DIR_WS_CLASSES . 'order_total.php');
      //$order_total_modules = new order_total;
      //$order_totals = $order_total_modules->process();
      if(!class_exists('order_total')) {
				include_once(DIR_WS_CLASSES . 'order_total.php');
				$order_total_modules = new order_total;
				$order_totals = $order_total_modules->process();
			}


      $order_id = substr($cart_InternetSecure_DL_ID, strpos($cart_InternetSecure_DL_ID, '-')+1);

	  /* 1.4 change took this piece of code out
	  $sql_data_array = array('orders_id' => $order_id,
                              'orders_status_id' => $order->info['order_status'],
                              'date_added' => 'now()',
                              'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                              'comments' => $order->info['comments']);

      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	  */

// initialized for the email confirmation
      $products_ordered = '';
      $subtotal = 0;
      $total_tax = 0;

      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
        if (STOCK_LIMITED == 'true') {
          if (DOWNLOAD_ENABLED == 'true') {
            $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                                FROM " . TABLE_PRODUCTS . " p
                                LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                ON p.products_id=pa.products_id
                                LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                ON pa.products_attributes_id=pad.products_attributes_id
                                WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
            $products_attributes = $order->products[$i]['attributes'];
            if (is_array($products_attributes)) {
              $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
            }
            $stock_query = tep_db_query($stock_query_raw);
          } else {
            $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
          if (tep_db_num_rows($stock_query) > 0) {
            $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
            if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
              $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
            } else {
              $stock_left = $stock_values['products_quantity'];
            }
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
              tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            }
          }
        }

// Update products_ordered (for bestsellers list)
        tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

//------insert customer choosen option to order--------
        $attributes_exist = '0';
        $products_ordered_attributes = '';
        if (isset($order->products[$i]['attributes'])) {
          $attributes_exist = '1';
          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
            if (DOWNLOAD_ENABLED == 'true') {
              $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                   from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                   left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                   on pa.products_attributes_id=pad.products_attributes_id
                                   where pa.products_id = '" . $order->products[$i]['id'] . "'
                                   and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                   and pa.options_id = popt.products_options_id
                                   and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                   and pa.options_values_id = poval.products_options_values_id
                                   and popt.language_id = '" . $languages_id . "'
                                   and poval.language_id = '" . $languages_id . "'";
              $attributes = tep_db_query($attributes_query);
            } else {
              $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            }
            $attributes_values = tep_db_fetch_array($attributes);

            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
          }
        }
//------insert customer choosen option eof ----
        $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
        $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
        $total_cost += $total_products_price;

        $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
      }

// lets start with the email confirmation
      $email_order = STORE_NAME . "\n\n" . 'Thank you for ordering!' . "\n" .' We will email you once your order ships. If you have any quesions or need help, then please visit our website or you can email us.' . "\n\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                     EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false) . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

      if ($order->info['comments']) {
        $email_order .= tep_db_output($order->info['comments']) . "\n\n";
      }
      $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                      EMAIL_SEPARATOR . "\n" .
                      $products_ordered .
                      EMAIL_SEPARATOR . "\n";

      for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
        $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
      }

      if ($order->content_type != 'virtual') {
        $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                        EMAIL_SEPARATOR . "\n" .
                        tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
      }

      $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                      EMAIL_SEPARATOR . "\n" .
                      tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";

      if (is_object($$payment)) {
        $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                        EMAIL_SEPARATOR . "\n";
        $payment_class = $$payment;
        $email_order .= $payment_class->title . "\n\n";
        if ($payment_class->email_footer) {
          $email_order .= $payment_class->email_footer . "\n\n";
        }
      }

      tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], 'Order Number: ' .  ' ' . $order_id, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
        tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }

// load the after_process function from the payment modules
      $this->after_process();

      $cart->reset(true);

// unregister session variables used during checkout
      tep_session_unregister('sendto');
      tep_session_unregister('billto');
      tep_session_unregister('shipping');
      tep_session_unregister('payment');
      tep_session_unregister('comments');
      //kgt - discount coupons
      tep_session_unregister('coupon');
      //end kgt - discount coupons
      tep_session_unregister('cart_InternetSecure_DL_ID');
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function after_process() {
      return false;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function output_error() {
      return false;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable InternetSecure IPN Module', 'MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_STATUS', 'False', 'Do you want to accept InternetSecure payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_INTERNERSECURE_DL_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Preparing Order Status', 'MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_PREPARE_ORDER_STATUS_ID', '0', 'Set the status of prepared orders made with this payment module to this value', '6', '1', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_ISECURE_ORDER_STATUS_ID', '0', 'Set the status of an order when customer returns to site with this payment module to this value', '6', '4', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CAD Merchant ID', 'MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD', '0', 'Your CAD merchant ID at Internetsecure.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('USD Merchant ID', 'MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD', '0', 'Your USD merchant ID at Internetsecure.', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_ISECURE_CURRENCY', 'Always USD', 'The currency to use for credit card transactions', '6', '9', 'tep_cfg_select_option(array(\'Always USD\', \'Always CAD\', \'Either CAD or USD, else USD\', \'Either USD or CAD, else CAD\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Language', 'MODULE_PAYMENT_ISECURE_LANGUAGE', 'English', 'Transaction language (English, French, Spanish or Japanese) ', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_ISECURE_TEST_STATUS', 'Test or Live', 'Transaction mode to use for the Internetsecure service', '6', '10', 'tep_cfg_select_option(array(\'Declined\', \'Approved\', \'LIVE\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Security Code', 'MODULE_PAYMENT_ISECURE_MYSECURITY_CODE', '0', 'Enter a security code letter, numbers, and captial letters - MUST ENTER, DO NOT LEAVE BLANK', '6', '11', now())");
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   function keys() {
     return array('MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_STATUS', 'MODULE_PAYMENT_INTERNERSECURE_DL_ZONE', 'MODULE_PAYMENT_ISECURE_LANGUAGE','MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_PREPARE_ORDER_STATUS_ID', 'MODULE_PAYMENT_ISECURE_MYSECURITY_CODE', 'MODULE_PAYMENT_ISECURE_CURRENCY', 'MODULE_PAYMENT_ISECURE_TEST_STATUS','MODULE_PAYMENT_ISECURE_MERCHANT_ID_USD','MODULE_PAYMENT_ISECURE_MERCHANT_ID_CAD', 'MODULE_PAYMENT_ISECURE_ORDER_STATUS_ID', 'MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_SORT_ORDER');
   }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  }
?>
