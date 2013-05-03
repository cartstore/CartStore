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

  define('MODULE_PAYMENT_CHARGEIT_TEXT_TITLE', 'Credit Card: (' . MODULE_PAYMENT_CHARGEIT_CC_ACCEPTED . ')'); // Credit cards accepted is set in the admin area.
  define('MODULE_PAYMENT_CHARGEIT_TEXT_DESCRIPTION', 'Virtual Merchant Payment Gateway<br /><br /><b>This module does require cURL to be compiled into PHP. Check your phpinfo page for cURL.</b><br /><br />Credit Card Test #:<br />Card: 5000300020003003<br />Expiration: 1209<br />CVV2: 123<br />Any value under $50.00');
  define('MODULE_PAYMENT_CHARGEIT_ERROR_HEADING', 'There has been an error processing your credit card');
  define('MODULE_PAYMENT_CHARGEIT_ERROR_MESSAGE', 'Please check your credit card details!');
  define('MODULE_PAYMENT_CHARGEIT_DECLINE_MESSAGE', 'Please check your information and try again or call our toll free line for help. This can occur when your billing address and zip code does not match your credit card\'s billing information, when you enter an invalid expiration date, or invalid cvv2 number.');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_OWNER', '<b>Credit Card Owner:</b>');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_NUMBER', '<b>Credit Card Number:</b>');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_CREDIT_CARD_EXPIRES', '<b>Credit Card Expiry Date:</b>');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_CVV_NUMBER', '<b><a href="#" onclick="window.open(\'images/cvv2.gif\',\'cvvwindow\',\'height=354,width=200,resizable=yes,toolbar=no,location=no,status=no\'); return false;">CVV2 Number: [?]</a></b>');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_CVV_INDICATOR','<b>CVV Status:</b>');

  // CVV2 text
  define('CVV_NUMBER_MIN_LENGTH', 3);
  define('CVV_NUMBER_MAX_LENGTH', 4);
  define('MODULE_PAYMENT_CHARGEIT_TEXT_JS_CVV_NUMBER', '* The cvv number must be at least ' . CVV_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_JS_MAX_CVV_NUMBER', '* The cvv number must be less than ' . CVV_NUMBER_MAX_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_JS_INVALID_CVV_NUMBER', '* The cvv number must be all numbers.\n');

  define('MODULE_PAYMENT_CHARGEIT_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CHARGEIT_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');

  define('MODULE_PAYMENT_CHARGEIT_CC_ERROR', 'Credit Card Error ');
  define('MODULE_PAYMENT_CHARGEIT_CC_ERROR_NAME', 'Name &amp; Expiration Date Entered: ');
  define('MODULE_PAYMENT_CHARGEIT_CC_ERROR_EXP', ' - Expiration Date Entered: ');

  // Error message text
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR1', 'Address Verification Response: ');
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR2', 'CVV2 Verification Response: ');
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_CUST_ID', 'Customer ID #');
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_ERROR_MSG', 'Error Message(s): ');
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_TRANS_MSG', 'Transaction Error');
  define('MODULE_PAYMENT_CHARGEIT_EMAIL_TRANS_INT_MSG', ' International');
  define('MODULE_PAYMENT_CHARGEIT_DECLINE_CALL_HELP1', ' For help processing this order call customer service at ');
  define('MODULE_PAYMENT_CHARGEIT_DECLINE_CALL_HELP2', ' and we will quickly process your order.');
  define('MODULE_PAYMENT_CHARGEIT_DECLINE_HELP_SORRY', 'The card entered is being declined. <b>This most often is due to incorrectly entered information.</b> Please try re-entering your information.');
?>