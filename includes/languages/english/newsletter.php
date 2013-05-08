<?php
/*
  $Id: newsletter.php, v4.0 8/12/2006

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible
*/

define('NAVBAR_TITLE', 'Club');
define('HEADING_TITLE', 'Club');

define('TEXT_INFORMATION', '
<br>
<strong>By joining the club you are eligible for member only discounts, coupons, free shipping, news and points and rewards. </strong>
<br>

You can unsubscribe at any moment by returning to this page.<br><br>');
// Subscription subject
define('EMAIL_SUBJECT', STORE_NAME .': Subscription Confirmation');
// Unsubscribe subject
define('EMAIL_SUBJECT2', STORE_NAME .': Successfully Unsubscribed');
// Subscription welcome message
define('EMAIL_WELCOME', 'Thank you for subscribing to ' . STORE_NAME . ' newsletter.' . "\n\n");
// Unsubscribe welcome message
define('EMAIL_WELCOME2', 'You have been successfully unsubscribed from the ' . STORE_NAME . ' newsletter.' . "\n\n");
// Subscription email text
define('EMAIL_TEXT', '<br>
<strong>By joining the club you are eligible for member only discounts, coupons, free shipping, news and points and rewards. </strong>
<br>' . "\n");
// Unsubscribe email text
define('EMAIL_TEXT2', 'Please be sure to visit our store again soon to find out about our latest products and specials. If you decide you would like to subscribe again, please click the link below:' . "\r" . '
http://'.$_SERVER['HTTP_HOST'] . DIR_WS_CATALOG . FILENAME_NEWSLETTER . '');
// Subscription warning message
define('EMAIL_WARNING', 'NOTE: This email address was submitted to us via our website. If you did not signup to become a subscriber, please click the link below to unsubscribe:' . "\r" . '
http://'.$_SERVER['HTTP_HOST'] . DIR_WS_CATALOG . FILENAME_NEWSLETTER . '');
?>