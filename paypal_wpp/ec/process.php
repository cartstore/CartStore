<?php 
ob_start(); 
chdir('../../');

//application header file
require('includes/application_top.php');

//include configuration file
require_once('./paypal_wpp/includes/config_ec.inc.php');

//check to see if token already exists 
if(!(tep_session_is_registered('paypal_token'))) { 

 //include library file
require_once('./paypal_wpp/includes/lib.inc.php'); 

//include processing file 
require_once('./paypal_wpp/includes/upc_direct_paypal.php'); 

//set token
$paypal_token=$upc_results['Token']; 

}


else { 

$upc_results['Ack']="Success"; 
$paypal['action']=1;

}

//verify response from PayPal

switch($upc_results["Ack"]) { 

case "Success": //successful response received


//check to see what action to take

switch($paypal[action]) {


case 1: //setExpressCheckOut

//redirect user to PayPal to select checkout options



//redirect user to PayPal to select checkout options
echo "<script language='JavaScript1.3'>"; 
echo "window.location=\"$paypal[express_checkout_url]?cmd=_express-checkout&token=$paypal_token\""; 
echo "</script>"; 

break;

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

require('includes/application_bottom.php');
ob_end_flush(); 
?>