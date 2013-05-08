<?php
/* 
	Copyright (c) 2005 Mr. Brian Burton - brian@dynamoeffects.com
     
     Since an EC transaction is a two step process, this script 
     
	GNU General Public License Compatible
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EC_PROCESS);
  require(DIR_WS_CLASSES . 'payment.php');

  if (tep_session_is_registered('paypal_error')) tep_session_unregister('paypal_error');

  if (isset($_GET['clearSess'])) {
    tep_session_unregister('paypal_ec_temp');
		tep_session_unregister('paypal_ec_token');
		tep_session_unregister('paypal_ec_payer_id');
		tep_session_unregister('paypal_ec_payer_info');
  }
  
  if (tep_paypal_wpp_enabled()) {
    $payment_modules = new payment('paypal_wpp');
    
    if(!tep_session_is_registered('paypal_ec_token')) {
      $payment_modules->ec_step1();
    } else {
      $payment_modules->ec_step2();
    }
  } 
?>

<html>
Processing...
</html>
