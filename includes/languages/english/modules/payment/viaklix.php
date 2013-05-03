<?php
/*

 Elavon | ViaKLIX | NOVA | Virtual Merchant - Payment Module for osCommerce & CRE Loaded
  
 Copyright © (c) 2008 Black Table Media LLC
 
Selling this script without purchasing a sellers license, or claiming it to be your own, 
is breaching United States copyright laws and will result in appropriate action 
being taken under US law. A sellers license can also be requested by using
the form at http://www.blacktablemedia.com/contact/contact.php

*/

  define('MODULE_PAYMENT_VIAKLIX_TEXT_TITLE', 'Credit Card: Virtual Merchant'); // Edit the text here to change what appears on your site as the payment type header (what the user sees)
  define('MODULE_PAYMENT_VIAKLIX_TEXT_DESCRIPTION', 'Virtual Merchant Payment Gateway<br><br>Credit Card Test Info:<br><br>CC#: 5000300020003003<br>Expiry: 12/08<br>CVV: Any<br> Test Sale must be less than $50'); // The right test card number - test sale must be < 50
  define('VIAKLIX_ERROR_HEADING', 'There has been an error processing your credit card');
  define('VIAKLIX_ERROR_MESSAGE', 'Please check your credit card details!');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_OWNER', '<b>Credit Card Owner:</b>');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_NUMBER', '<b>Credit Card Number:</b>');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_CREDIT_CARD_EXPIRES', '<b>Credit Card Expire Date:</b>');

// below is the text the user sees to describe where to find the cvv number. paste <IMG src="images/cvv.gif"> in there to show an illustration

define('MODULE_PAYMENT_VIAKLIX_TEXT_CVV_NUMBER',
'<b><a href= "images/cvv.gif" alt="CVV">CVV</a> Number:</b><br> 
Visa & Mastercard <br />
CVV Numbers are on<br>
the back at the end of the<br>
card number.');

define('MODULE_PAYMENT_VIAKLIX_TEXT_CVV_INDICATOR','CVV Status:');

  define('CVV_NUMBER_MIN_LENGTH', 3);
  define('CVV_NUMBER_MAX_LENGTH', 4);
  define('MODULE_PAYMENT_VIAKLIX_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_JS_CVV_NUMBER', '* The cvv number must be at least ' . CVV_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_JS_MAX_CVV_NUMBER', '* The cvv number must be less than ' . CVV_NUMBER_MAX_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_VIAKLIX_TEXT_JS_INVALID_CVV_NUMBER', '* The cvv number must be all numbers.\n');
  define('TEXT_CVV_LINK', '<u>[help?]</u>');
  define('HEADING_CVV', 'Security Code Help Screen');
  define('TEXT_CVV', '<table align="center" cellspacing="2" cellpadding="5"><tr><td><span class="fancyText"><b>Visa, Mastercard 3 Digit Card Verification Number</b></span></td></tr><tr><td><span class="fancyText">For your safety and security, we require that you enter your card\'s verification number. The verification number is a 3-digit number printed on the back of your card. It appears after and to the right of your card number\'s last four digits.</span></td></tr><tr><td align="center"><IMG src="images/cv_card.gif"></td></tr></table><hr><table align="center" cellspacing="2" cellpadding="5" width="400"><tr><td><span class="fancyText"><b>American Express 4 Digit Card Verification Number</b> </span></td></tr><tr><td><span class="fancyText">For your safety and security, we require that you enter your card\'s verification number. The American Express verification number is a 4-digit number printed on the front of your card. It appears after and to the right of your card number.</span></td></tr><tr><td align="center"><IMG src="images/cv_amex_card.gif"></td></tr></table>');
  define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');
  

  


?>