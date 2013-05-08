<?php
/*
  $Id: innovative.php,v 2.4.0 2005/05/25 12:58:00 willross Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  Copyright (c) 2005 Universal Joint Productions Co. - http://www.palaia.com/ujp/
  This module connects CartStore 2.0 (November 2003 or later) - 2.3
  to IMS/Innovative Merchant & Gateway Solutions:
*/

  define('MODULE_PAYMENT_INNOVATIVE_TEXT_TITLE', 'IMS Credit Card');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CVV2', 'CVV2:');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.<br>%s');
  define('MODULE_PAYMENT_INNOVATIVE_TEXT_ERROR', 'Credit Card Error!');
?>
