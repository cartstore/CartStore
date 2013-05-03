<?php
/*
  $Id: customers_points.php, v 1.60 2005/NOV/03 15:17:12 dgw_ Exp $
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
define('MOD_VER', '2.00');

define('HEADING_TITLE', 'Customers Qualified Points');
define('HEADING_RATE', 'Exchange Rates : ');
define('HEADING_AWARDS', 'Awards : ');
define('HEADING_REDEEM', 'Redeem : ');
define('HEADING_POINT', 'point');
define('HEADING_POINTS', 'points');
define('HEADING_TITLE_SEARCH', 'Search id, name or expire month(ie May=05)');

define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_DOB', 'Date of birth');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_POINTS', 'Points');
define('TABLE_HEADING_POINTS_VALUE', 'Value');
define('TABLE_HEADING_POINTS_EXPIRES', 'Expire');

define('TABLE_HEADING_SORT', 'Sort this row by ');
define('TABLE_HEADING_SORT_UA', ' --> A-B-C From Top');
define('TABLE_HEADING_SORT_U1', ' --> 1-2-3 From Top');
define('TABLE_HEADING_SORT_DA', ' --> Z-Y-X From Top');
define('TABLE_HEADING_SORT_D1', ' --> 3-2-1 From Top');

define('TEXT_SHOW_ALL', 'Show All');
define('TEXT_SORT_CUSTOMERS', 'Show Customers');
define('TEXT_SORT_POINTS', 'With points');
define('TEXT_SORT_NO_POINTS', 'Without points');
define('TEXT_SORT_BIRTH', 'B.day this month');
define('TEXT_SORT_BIRTH_NEXT', 'B.day next month');
define('TEXT_SORT_EXPIRE', 'Expire this month');
define('TEXT_SORT_EXPIRE_NEXT', 'Expire next month');
define('TEXT_SORT_EXPIRE_WIN', 'Expire within 1 month');


define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');

define('TEXT_INFO_HEADING_ADJUST_POINTS', 'Adjust Customer Points.');
define('TEXT_INFO_NUMBER_OF_ORDERS', 'Orders Total :');
define('TEXT_INFO_NUMBER_OF_PENDING', 'Total Pending points :');

define('TEXT_ADD_POINTS', 'Add Points.');
define('TEXT_ADD_POINTS_LONG', 'You can add points to customer with/without queuing points table.<br>Queuing will add a line to table with your comment else, points account updated only(if customer notify your comment will be added to the email) .');
define('TEXT_ADJUST_INTRO', 'This option enable you to quickly adjust the total amount of points.<br>Note that this will replace the current points amount and customer will not notified.');
define('TEXT_DELETE_POINTS', 'Remove Points.');
define('TEXT_DELETE_POINTS_LONG', 'You can remove points to customer with/without queuing points table.<br>Queuing will add a line to table with your comment else, points account updated only(if customer notify your comment will be added to the email) .');
define('TEXT_POINTS_TO_ADD', 'Points to Add :');
define('TEXT_POINTS_TO_ADJUST', 'New points amount :');
define('TEXT_POINTS_TO_DELETE', 'Points to Remove :');
define('TEXT_COMMENT', 'Comment :');

define('TEXT_QUEUE_POINTS_TABLE', 'Queue customers points table?');
define('TEXT_NOTIFY_CUSTOMER', 'Notify Customer');
define('TEXT_SET_EXPIRE', 'Set new expire date');

define('BUTTON_TEXT_ADD_POINTS', 'Add Points');
define('BUTTON_TEXT_DELETE_POINTS', 'Remove Points');
define('BUTTON_TEXT_ADJUST_POINTS', 'Adjust the current points amount');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Points Account Update .');
define('EMAIL_GREET_MR', '<b>Dear Mr. %s,</b>');
define('EMAIL_GREET_MS', 'Dear Ms. %s,');
define('EMAIL_GREET_NONE', 'Dear %s');
define('EMAIL_TEXT_INTRO', 'This is to inform you that your Shopping Points Account has been updated.');
define('EMAIL_TEXT_BALANCE_ADD', 'Congratulations! ' . "\n" . 'We have credited your account with total of %s points valued at %s');
define('EMAIL_TEXT_BALANCE_DEL', 'We are sorry but your Shopping Point account had been deducted with total of %s points valued at %s .');
define('EMAIL_TEXT_BALANCE', 'Your current Shopping Points balance is: %s points valued at %s .');
define('EMAIL_TEXT_EXPIRE', 'Points will expire at : %s .');
define('EMAIL_TEXT_POINTS_URL', 'For your convenience here is the link to your Shopping Points Account . %s');
define('EMAIL_TEXT_POINTS_URL_HELP', 'Our store Reward Point Program FAQ page located here . %s');
define('EMAIL_TEXT_COMMENT', 'Comment: %s');
define('EMAIL_TEXT_SUCCESS_POINTS', 'Points are available at your account, during the checkout procces you\'ll be able to pay for your order with your points balance. '. "\n" .'Thank you for shopping at ' . STORE_NAME . ' and we looking forward to serving you again.');
define('EMAIL_CONTACT', 'If you have any questions or for help with any of our online services, please email us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n" . 'This is an automated response, please do not reply!');

define('SUCCESS_POINTS_UPDATED', 'Success: Customer Points account has been successfully updated.');
define('SUCCESS_DATABASE_UPDATED', 'Queue Success: Database has been successfully updated with this comment " '. $comment . ' ".');
define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('WARNING_DATABASE_NOT_UPDATED', 'Warning: Empty fields, Nothing to change. The Database was not updated.');
define('POINTS_ENTER_JS_ERROR', 'Invalid entry! \n Only numbers are accepted!');

define('TEXT_LINK_CREDIT', 'Click here to run the <a href="customers_points_credit.php"><u>Auto Credit</u></a> or <a href="customers_points_expire.php"><u>Auto Expire</u></a> script manually.');
?>