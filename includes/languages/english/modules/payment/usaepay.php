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

  define('MODULE_PAYMENT_USAEPAY_VERSION', '2.0.0');
  define('MODULE_PAYMENT_USAEPAY_TEXT_TITLE', 'USAePay');
  define('MODULE_PAYMENT_USAEPAY_TEXT_DESCRIPTION', '<b>USAePay Real-Time processing module </b>v' . MODULE_PAYMENT_USAEPAY_VERSION . '<br>This module supports realtime credit card processing via the USAePay gateway.<br><br>' .
  		'<a href="http://help.usaepay.com/merchant/support/carts/oscommerce" target="_blank">View Documentation</a><br><br>' .
  		'<a href="https://secure.usaepay.com/login" target="_blank">Access Merchant Console</a><br><br>' .
  'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_USAEPAY_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_USAEPAY_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_USAEPAY_TEXT_CVV', 'CVV Number:');  
  define('MODULE_PAYMENT_USAEPAY_TEXT_POPUP_CVV_LINK', 'What\'s this?');
  define('MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_USAEPAY_TEXT_JS_CC_CVV', '* You must enter the 3 or 4 digit number on the back of your credit card');
  define('MODULE_PAYMENT_USAEPAY_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_USAEPAY_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
  define('MODULE_PAYMENT_USAEPAY_TEXT_ERROR', 'Credit Card Error!');
?>
