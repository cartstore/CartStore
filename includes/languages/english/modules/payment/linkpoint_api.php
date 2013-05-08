<?php
/*
  $Id: linpoint_api.php,v 1.20 2004/09/01 22:40:24 DJALEX Exp $

The Exchange Project - Community Made Shopping
http://www.theexchangeproject.org

Copyright (c) 2000,2001 The Exchange Project

  GNU General Public License Compatible
*/

  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_TITLE', 'Secure Credit Card Transaction');   
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#:4111111111111111<br>Expiry:Any');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_TYPE', 'Type:');   
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');    
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_CREDIT_CARD_CHECK_VALUE', 'Card Verification Value:');
  define('MODULE_PAYMENT_LINKPOINT_API_CVS_NOT_PRESENT', 'Not present or unreadable:');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_CREDIT_CARD_OWNER', 'Credit Card Name:');    
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');   
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');    
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_ERROR_MESSAGE', 'There has been an error processing you credit card, please wait 10 minutes before trying again.');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_DUPLICATE_MESSAGE', 'It appears you submitted your order more than once. If it was previously declined please wait 10 minutes.');
  define('MODULE_PAYMENT_LINKPOINT_API_TEXT_ERROR', 'Credit Card Error!');
  define('TEXT_SEARCH_CVS_HELP', 'What\'s this value [?]');

?>