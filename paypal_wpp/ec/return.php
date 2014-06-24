<?php
ob_start(); 

chdir('../../');

require('includes/application_top.php');


if(isset($_GET['token'])) {

//register token in session for later use
if (!tep_session_is_registered('paypal_token')) { tep_session_register('paypal_token'); }

//register payerid
if (!tep_session_is_registered('pp_payer_id')) { tep_session_register('pp_payer_id'); }

//set paypal token variable returned from paypal

$paypal_token = $_GET['token'];

//set paypal action
$_REQUEST['express']=2; 

//include configuration file
require_once('./paypal_wpp/includes/config_ec.inc.php'); 

//include library file
require_once('./paypal_wpp/includes/lib.inc.php'); 

//include processing file 
require_once('./paypal_wpp/includes/upc_direct_paypal.php'); 

$pp_payer_id=$upc_results['PayerID']; 

//verify response from PayPal

switch($upc_results["Ack"]) { 

case "Success": //successful response received

//add shipping information to address book 


//redirect user to shipping checkout
if (tep_session_is_registered('customer_id')) { 


//get country id
$upc_results['Country']=tep_get_country_by_iso_code($upc_results['Country']); 

//prepare data for insertion 
if (ACCOUNT_COMPANY == 'true') { $company = tep_db_prepare_input($_POST['company']); }
$firstname = tep_db_prepare_input($upc_results['FirstName']);
$lastname = tep_db_prepare_input($upc_results['LastName']);
$street_address = tep_db_prepare_input($upc_results['Street1']) . " " . tep_db_prepare_input($upc_results['Street2']);
$postcode = tep_db_prepare_input($upc_results['PostalCode']);
$city = tep_db_prepare_input($upc_results['CityName']);
$country = tep_db_prepare_input($upc_results['Country']);
if (ACCOUNT_STATE == 'true') {$zone_id = false; $state = tep_db_prepare_input($upc_results['StateOrProvince']); }

//build sql query
$sql_data_array = array('customers_id' => $customer_id,
'entry_firstname' => $firstname,
'entry_lastname' => $lastname,
'entry_street_address' => $street_address,
'entry_postcode' => $postcode,
'entry_city' => $city,
'entry_country_id' => $country);

if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
if (ACCOUNT_STATE == 'true') {
if ($zone_id > 0) {
$sql_data_array['entry_zone_id'] = $zone_id;
$sql_data_array['entry_state'] = '';
} else {
$sql_data_array['entry_zone_id'] = '0';
$sql_data_array['entry_state'] = $state;
}
}

if (!tep_session_is_registered('sendto')) { tep_session_register('sendto'); }

tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

$sendto = tep_db_insert_id();

if (tep_session_is_registered('shipping')) { tep_session_unregister('shipping'); }

tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')); 

}

//redirect user to create account
else { 

//register session variables 
if (!tep_session_is_registered('pp_token')) { tep_session_register('pp_token'); }
if (!tep_session_is_registered('pp_payer_status')) { tep_session_register('pp_payer_status'); }
if (!tep_session_is_registered('pp_firstname')) {tep_session_register('pp_firstname'); }
if (!tep_session_is_registered('pp_lastname')) {tep_session_register('pp_lastname'); }
if (!tep_session_is_registered('pp_stree1')) {tep_session_register('pp_street1'); }
if (!tep_session_is_registered('pp_street2')) {tep_session_register('pp_street2'); }
if (!tep_session_is_registered('pp_city')) {tep_session_register('pp_city'); }
if (!tep_session_is_registered('pp_state')) {tep_session_register('pp_state'); }
if (!tep_session_is_registered('pp_zip')) {tep_session_register('pp_zip'); }
if (!tep_session_is_registered('pp_country')) {tep_session_register('pp_country'); }
if (!tep_session_is_registered('pp_phone')) {tep_session_register('pp_phone'); }
if (!tep_session_is_registered('pp_email')) {tep_session_register('pp_email'); }
if (!tep_session_is_registered('pp_business')) {tep_session_register('pp_business'); }

//map response to session variable names
$pp_token=$upc_results['Token']; 
$pp_payer_status=$upc_results['PayerStatus']; 
$pp_firstname=$upc_results['FirstName']; 
$pp_lastname=$upc_results['LastName']; 
$pp_street1=$upc_results['Street1']; 
$pp_street2=$upc_results['Street2']; 
$pp_city=$upc_results['CityName']; 
$pp_state=$upc_results['StateOrProvince']; 
$pp_zip=$upc_results['PostalCode']; 
$pp_country=tep_get_country_by_iso_code($upc_results['Country']);  
$pp_phone=$upc_results['ContactPhone']; 
$pp_email=$upc_results['Payer']; 
$pp_business=$upc_results['PayerBusiness']; 

tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 

}


break; 

case "Failure": //transaction error 

//redirect user and display error code and message from the gateway
tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . urlencode("($upc_results[ErrorCode]) $upc_results[LongMessage]"), 'SSL', true, false));

break;


default: //transaction error or warning

//redirect user and display general processing error
tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . urlencode(MODULE_PAYMENT_PAYPAL_EC_TEXT_PROCESS_ERROR), 'SSL', true, false));


break; 

}


  }



require('includes/application_bottom.php');
ob_end_flush(); 
?>