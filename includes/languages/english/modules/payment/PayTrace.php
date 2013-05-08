<?php
/*
  $Id: PayTrace.php,v 1.00 2006/04/15 01:02:00 project1000 Exp $

  PayTrace, The secure advantage.
  https://PayTrace.com

  Copyright (c) 2006 PayTrace, LLC
  
  
  *********************************
  
  
  This file should be located:
  
  /catalog/includes/languages/english/modules/payment/PayTrace.php  
  
  
  *********************************
  
  PayTrace strongly advises all merchants using the PayTrace API to install use a SSL certificate 
  to encrypt all sensitive information entered in their shopping cart by the their customers.  
  Whether the merchant is using osCommerce or another solution, the use of SSL encryption is strongly 
  recommended.
  
  Additionally, PayTrace strongly advises that NO CARDHOLDER DATA (CHD) is stored by any merchant, and
  CSC values may never be stored.
  
  *********************************
  

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PAYTRACE_TEXT_TITLE', 'PayTrace');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_DESCRIPTION', 'Card Test Info:<br><br>CC#: 4111111111111111<br>Expiration Date: Any');
  define('PAYTRACE_ERROR_HEADING', 'There has been an error processing your card');
  define('PAYTRACE_ERROR_MESSAGE', 'Please check your card details!');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_OWNER', 'Card Owner:');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_NUMBER', 'Card Number:');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_EXPIRES', 'Card Expiration Date:');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_CHECKNUMBER', 'Card Security Code (CSC):');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', '(3 or 4 digit security code.)');

  define('MODULE_PAYMENT_PAYTRACE_TEXT_JS_CC_OWNER', '* The owner\'s name of the card must be at least 3 characters.\n');
  define('MODULE_PAYMENT_PAYTRACE_TEXT_JS_CC_NUMBER', '* The card number must be at least 15 digits.\n');

?>
