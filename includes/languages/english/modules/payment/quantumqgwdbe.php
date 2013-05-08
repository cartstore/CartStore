<?php
/*
  $Id: quantumqgwdbe.php,v 1.12 2002/11/18 14:45:20 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  eProcessingNetwork.php was developed for eProcessingNetwork

  http://www.quantumgateway.com

  by

  Andres Roca - CDG Commerce
  andresr@cdgcommerce.com
*/

  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_TITLE', 'QuantumGateway');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_PUBLIC_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>Visa: 4111111111111111<br>MasterCard: 5454545454545454<br>American Express: 370000000000002<br>Discover: 6011000000000012<br>JCB: 3530111333300000<br>Diners Club: 30000000000004<br>Expiry: Any date in future');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_OWNER', 'Name on Credit Card:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiration:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_CREDIT_CARD_CVV', 'Credit Card CVV:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_NOT_CVV', 'Check here if you can not type CVV code:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_REASON_NOT_CVV', 'Why you can not type CVV code?:');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');  
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_MISSING_CC_NUMBER', 'You must enter a credit card number.\n');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_QUANTUMQGWDBE_TEXT_ERROR', 'Credit Card Error!');
?>