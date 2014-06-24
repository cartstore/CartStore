<?php
/*
  $Id: cc.php,v 1.10 2002/11/01 05:14:11 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  define('MODULE_PAYMENT_CYBS_TEXT_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_CYBS_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any Future Date');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_FIRST_NAME', 'First Name:');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_LAST_NAME', 'Last Name:');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_CVV_NUMBER', 'Credit Card CVV Number:');
  define('MODULE_PAYMENT_CYBS_TEXT_CREDIT_CARD_EXPIRES', 'Expiration Date:');
  define('MODULE_PAYMENT_CYBS_TEXT_JS_CYBS_FIRST_NAME', '* The owner\'s first name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CYBS_TEXT_JS_CYBS_LAST_NAME', '* The owner\'s last name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CYBS_TEXT_JS_CYBS_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_CYBS_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try another card or contact customer service.');
  define('MODULE_PAYMENT_CYBS_TEXT_INVALID_FIELD', 'There has been an error processing your credit card. One or more fields is either invalid or missing. Please try again.');
  define('MODULE_PAYMENT_CYBS_TEXT_DECLINED_INSFUNDS_MESSAGE', 'Your credit card was declined due to insufficient funds. Please try another card or contact your bank for more information.');
  define('MODULE_PAYMENT_CYBS_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more information.');
  define('MODULE_PAYMENT_CYBS_TEXT_ERROR', 'Credit Card Error!');

  define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', 'The card type you have selected does not match the number entered.  Please try again.');
  define('TEXT_CCVAL_ERROR_CARD_CVV_MISSING', 'You must supply the CVV code for this credit card.  Please try again.');
  define('TEXT_CCVAL_ERROR_CARD_CVV_INVALID', 'The CVV code supplied is invalid.  Please be sure to check it for length and accuracy and try again.');
?>