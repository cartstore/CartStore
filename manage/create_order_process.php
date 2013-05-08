<?php
/*
  $Id: account_edit_process.php,v 1.2 2002/11/28 23:39:44 wilt Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);
 // if ($_POST['action'] != 'process') {
  //  tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL'));
  //}
  $customer_id = tep_db_prepare_input($_POST['customers_id']);
  $gender = tep_db_prepare_input($_POST['gender']);
  $firstname = tep_db_prepare_input($_POST['firstname']);
  $lastname = tep_db_prepare_input($_POST['lastname']);
  $dob = tep_db_prepare_input($_POST['dob']);
  $email_address = tep_db_prepare_input($_POST['email_address']);
  $telephone = tep_db_prepare_input($_POST['telephone']);
  $fax = tep_db_prepare_input($_POST['fax']);
  $newsletter = tep_db_prepare_input($_POST['newsletter']);
  $password = tep_db_prepare_input($_POST['password']);
  $confirmation = tep_db_prepare_input($_POST['confirmation']);
  $street_address = tep_db_prepare_input($_POST['street_address']);
  $company = tep_db_prepare_input($_POST['company']);
  $suburb = tep_db_prepare_input($_POST['suburb']);
  $postcode = tep_db_prepare_input($_POST['postcode']);
  $city = tep_db_prepare_input($_POST['city']);
  $zone_id = tep_db_prepare_input($_POST['zone_id']);
  $state = tep_db_prepare_input($_POST['state']);
  $country = tep_db_prepare_input($_POST['country']);
  $format_id = "1";
  $size = "1";
  $payment_method = DEFAULT_PAYMENT_METHOD;
  $new_value = "1";
  $error = false; // reset error flag
  $temp_amount = "0";
  $temp_amount = number_format($temp_amount, 2, '.', '');
  
  $currency_text = DEFAULT_CURRENCY . ", 1";
  if(IsSet($_POST['Currency']))
  {
  	$currency_text = tep_db_prepare_input($_POST['Currency']);
  }
  
  $currency_array = explode(",", $currency_text);
  
  $currency = $currency_array[0];
  $currency_value = $currency_array[1];
?>
<?php

    $sql_data_array = array('customers_id' => $customer_id,
							'customers_name' => $firstname . ' ' . $lastname,
							'customers_company' => $company,
                            'customers_street_address' => $street_address,
							'customers_suburb' => $suburb,
							'customers_city' => $city,
							'customers_postcode' => $postcode,
							'customers_state' => $state,
							'customers_country' => $country,
							'customers_telephone' => $telephone,
                            'customers_email_address' => $email_address,
							'customers_address_format_id' => $format_id,
							'delivery_name' => $firstname . ' ' . $lastname,
							'delivery_company' => $company,
                            'delivery_street_address' => $street_address,
							'delivery_suburb' => $suburb,
							'delivery_city' => $city,
							'delivery_postcode' => $postcode,
							'delivery_state' => $state,
							'delivery_country' => $country,
							'delivery_address_format_id' => $format_id,
							'billing_name' => $firstname . ' ' . $lastname,
							'billing_company' => $company,
                            'billing_street_address' => $street_address,
							'billing_suburb' => $suburb,
							'billing_city' => $city,
							'billing_postcode' => $postcode,
							'billing_state' => $state,
							'billing_country' => $country,
							'billing_address_format_id' => $format_id,
							'date_purchased' => 'now()', 
                            'orders_status' => DEFAULT_ORDERS_STATUS_ID,
							'currency' => $currency,
							'currency_value' => $currency_value,
							'payment_method' => $payment_method
							); 


							
      
 
  //old
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
 
 
    $sql_data_array = array('orders_id' => $insert_id,
                            //Comment out line you don't need
							//'new_value' => $new_value,	//for 2.2
							'orders_status_id' => $new_value, //for MS1 or 2.0
                            'date_added' => 'now()');
     tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  
  
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_SUBTOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00", 
                            'class' => "ot_subtotal", 
                            'sort_order' => "1");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);


   $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_DISCOUNT,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_customer_discount",
                            'sort_order' => "2");
   tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_DELIVERY,
                            'text' => $temp_amount,
                            'value' => "0.00", 
                            'class' => "ot_shipping", 
                            'sort_order' => "3");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_TAX,
                            'text' => $temp_amount,
                            'value' => "0.00", 
                            'class' => "ot_tax", 
                            'sort_order' => "4");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  
      $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_TOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00", 
                            'class' => "ot_total", 
                            'sort_order' => "5");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  

    tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $insert_id, 'SSL'));


  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>