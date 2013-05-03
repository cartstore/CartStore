<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_SAGEPAY_FORM_TEXT_TITLE', 'Credit Card - Sagepay Form');
  define('MODULE_PAYMENT_SAGEPAY_FORM_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4444333322221111<br>Expiry: Any');
  define('MODULE_PAYMENT_SAGEPAY_FORM_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_SAGEPAY_FORM_TEXT_ERROR_MESSAGE', 'There has been an error processing you credit card, please try again.');
  define('MODULE_PAYMENT_SAGEPAY_FORM_TEXT_ERROR', 'Credit Card Error!');
  define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered, %s/%s, is <font color=\"#FF0000\"><b>invalid</b></font>. Please check the date and try again.');
  define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The <b>%s</b> number entered, %s, is <font color=\"#FF0000\"><b>invalid</b></font>. Please check the number and try again.');
  define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are %s.<br>&nbsp;If that\'s correct, we don\'t accept that type of credit card.<br>&nbsp;If it\'s wrong, please try again.');
?>