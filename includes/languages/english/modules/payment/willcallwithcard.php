<?php
/*
  $Id: moneyorder.php,v 1.6 2003/01/24 21:36:04 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_WCWC_TEXT_TITLE', 'Will Call With Credit Card Info');
  define('MODULE_PAYMENT_WCWC_TEXT_DESCRIPTION', 'Call this phone number:&nbsp;' . MODULE_PAYMENT_WCWC_PHONE . '<br><br>Call during these hours:&nbsp;' . MODULE_PAYMENT_WCWC_HOURS . ((MODULE_PAYMENT_WCWC_FAX != '') ? '<br><br>Or fax the credit card information to this fax number:&nbsp;' . MODULE_PAYMENT_WCWC_FAX : '') . '<br><br>You will need to provide your name, the order number, the complete credit card number, the expiration date, and the security code printed on the card.<br><strong>Your order will not ship until we receive payment.</strong><br><br>Note: For security reasons we recommend that you do not call from a wireless phone.');
  define('MODULE_PAYMENT_WCWC_TEXT_EMAIL_FOOTER', "Call this phone number: ". MODULE_PAYMENT_WCWC_PHONE . "\n\nCall during these hours: " . MODULE_PAYMENT_WCWC_HOURS . ((MODULE_PAYMENT_WCWC_FAX != '') ? "\n\nOr fax the credit card information to this fax number: " . MODULE_PAYMENT_WCWC_FAX : '') . "\n\nYou will need to provide your name, the order number, the complete credit card number, the expiration date, and the security code printed on the card.\n\nYour order will not ship until we receive payment.\n\nNote: For security reasons we recommend that you do not call from a wireless phone.");
?>
