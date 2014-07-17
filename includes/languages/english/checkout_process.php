<?php
/*
  $Id: checkout_process.php,v 1.26 2002/11/01 04:22:05 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

//Package Tracking Plus BEGIN
define('EMAIL_TEXT_SUBJECT', 'Order Process');
define('EMAIL_TEXT_GREETING', '' . STORE_NAME . ' has received a order. Below is an invoice of the order for your records. ');
define('EMAIL_TEXT_SUBJECT_1', ' Order');
define('EMAIL_TEXT_SUBJECT_2', 'has been received.');
//Package Tracking Plus END
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', '');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_PRODUCTS', '');
define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
define('EMAIL_TEXT_TAX', 'Tax:        ');
define('EMAIL_TEXT_SHIPPING', 'Shipping: ');
define('EMAIL_TEXT_TOTAL', 'Total:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');


//Package Tracking Plus BEGIN
define('EMAIL_SEPARATOR', '');
define('EMAIL_INVOICE', ' Invoice');
define('TEXT_EMAIL_VIA', 'via');
//Package Tracking Plus END

// PWA BOF
define('EMAIL_WARNING', 'ATTENTION: This email address was given to us by someone who had visited our well known online store. If this was not done by you please email us at  ' . STORE_OWNER_EMAIL_ADDRESS . ' Thank you for shopping with us and have a great day.');
// PWA EOF

define('MODULE_PAYMENT_RFQ2_TEXT_EMAIL_FOOTER', 'Upon receiving your quote, you may choose to process the order. You will need to contact us with your payment information at this time. Your order will not ship until the full payment is received.');


define('EMAIL_TEXT_CONFIRM', 'Order Confirmation ');
define('TEXT_FROM', 'from');


?>