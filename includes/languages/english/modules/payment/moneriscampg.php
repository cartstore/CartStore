<?php
/*
$Id: moneriscampg.php, v 1.1.0 August 3, 2007
  
Phet Chai/Spencer Lai
Copyright (C) 

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_TITLE', 'Credit Card Payments');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_DESCRIPTION', 'Moneris eSELECTplus Canadian Gateway Version 1.1.0');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_CVD', 'Creditcard Verification Digit:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NUMBER', 'Card\'s Street Number:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_STREET_NAME', 'Card\'s Street Name:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_ZIP', 'Card\'s Zip Code:');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  // Moneris Payment Processing Errors
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_DO_NOT_ACCEPT_ERROR', 'Card not accepted');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_RETRIES_EXCEEDED_ERROR', 'Number of retries exceeded, please use another card.');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_GLOBAL_ERROR', 'Failed to connect to gateway');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_CVD_ERROR', 'Card Verification Digit does not match');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_AVS_ERROR', 'Address Verification does not match');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_UNKNOWN_ERROR', 'Transaction Process Failed');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_VBV_FAILED_ERROR', 'Verified by Visa Failed');
  define('MODULE_PAYMENT_MONERISCAMPG_TEXT_MCSC_FAILED_ERROR', 'Master Card Secure Code Failed');
?>