<?php
/*

  based on CVS version 1.40 of authorizenet.php (12/16/2002) and
  iongate contribution by Cheng

  Brian Yarger <byarger@weens.net>

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_REALEX_TEXT_TITLE', 'Realex Payments');
  define('MODULE_PAYMENT_REALEX_TEXT_DESCRIPTION', 'realAuth Remote Curl');
  define('MODULE_PAYMENT_REALEX_TEXT_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_OWNER', 'Card Holder Name:');
  define('MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_REALEX_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_REALEX_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_REALEX_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_REALEX_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_REALEX_TEXT_ERROR', 'Credit Card Error!');
  define('MODULE_PAYMENT_REALEX_TEXT_SERVICE_DESCRIPTION',
	       '
          
          <img src="'.DIR_WS_IMAGES.'cc/3v_logo.jpg" alt="3V Logo">&nbsp;&nbsp;&nbsp;
          <img src="'.DIR_WS_IMAGES.'cc/visa_logo.jpg" alt="Visa Logo">&nbsp;&nbsp;&nbsp;
          <img src="'.DIR_WS_IMAGES.'cc/visa_electron_logo.jpg" alt="Visa Electron Logo">
          ');
          define('MODULE_PAYMENT_REALEX_TEXT_SERVICE_DESCRIPTION2',
          '
		  <img src="'.DIR_WS_IMAGES.'cc/MasterCard_logo.jpg" alt="Mastercard Logo">&nbsp;&nbsp;&nbsp;
          <img src="'.DIR_WS_IMAGES.'cc/mastro_logo.jpg" alt="Mastro Logo">&nbsp;&nbsp;&nbsp;
          <img src="'.DIR_WS_IMAGES.'cc/Amex_Logo.jpg" alt="Amex Logo">&nbsp;&nbsp;&nbsp;
          <img src="'.DIR_WS_IMAGES.'cc/laser.gif" alt="laser Logo">
     ');  
     define('CVN_NUMBER', 'CVN Number:');
     define('CVN_EXPLANATION', '<strong onclick="openexpwindow();">What is this?</strong>');
     
?>