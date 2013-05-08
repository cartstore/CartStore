<?php
/*
  $Id: moneyorder.php,v 1.6 2003/01/24 21:36:04 thomasamoulton Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Check/Money Order');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Make Payable To:&nbsp;' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br><br>Send To:<br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . 'Your order will not ship until we receive payment.');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Make Payable To: ". MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\nSend To:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Your order will not ship until we receive payment.');
?>
