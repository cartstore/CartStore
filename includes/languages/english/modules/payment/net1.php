<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// | Net1 Payment Services Payment Module          			              |
// | http://www.eftsecure.com/default.aspx			      	              |
// | 									          			              |
// | includes/languages/english/modules/payment/net1.php	              |
// | Released under GPL   												  |
// | Designed by Jeramie Risinger, gapc23, Bean Fairbanks 				  |
// | Version 1.0 May 25, 2005								              |
// +----------------------------------------------------------------------+
//$Id: net1.php 1 2005-05-25 12:00:00Z jersbox gapc23 beanfair$

  define('MODULE_PAYMENT_NET1_TEXT_CATALOG_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_NET1_TEXT_ADMIN_TITLE', 'Net1 Payment Services');
  define('MODULE_PAYMENT_NET1_TEXT_DESCRIPTION', '---NET1--- <br />http://www.eftsecure.com/default.aspx<br /><br />---TESTING INFO---<br />test card #: 4111111111111111<br />Exp.: Any future date<br />CVV #: 111<br />http://www.net1pays.com/MediaCenter/BankcardTestingProcedures.pdf');
  define('MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_NET1_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiration Date:');
  define('MODULE_PAYMENT_NET1_TEXT_CVV2_NUMBER', 'CVV Number');  
  define('MODULE_PAYMENT_NET1_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_NET1_TEXT_JS_CC_CVV', '* The CVV2 number must be at least 3 digits - look on the back of your credit card for 3 or 4 additional numbers.\n');
  define('MODULE_PAYMENT_NET1_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_NET1_TEXT_ERROR', 'Credit Card Error!');

?>