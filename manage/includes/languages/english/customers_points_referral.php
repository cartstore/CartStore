<?php
/*
  $Id: customers_points_pending.php, v 1.60 2005/NOV/03 15:17:12 dgw_ Exp $
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
define('MOD_VER', '2.00');

define('HEADING_TITLE', 'Referral- Review Pending Points');
define('HEADING_RATE', 'Exchange Rates : ');
define('HEADING_AWARDS', 'Awards : ');
define('HEADING_REDEEM', 'Redeem : ');
define('HEADING_POINT', 'point');
define('HEADING_POINTS', 'points');
define('HEADING_TITLE_SEARCH', 'Search Customer ID:');

define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_POINTS_TYPE', 'Points Type');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');
define('TABLE_HEADING_POINTS_STATUS', 'Points Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_POINTS', 'Points');
define('TABLE_HEADING_POINTS_VALUE', 'Value');

define('TABLE_HEADING_SORT', 'Sort this rows by ');
define('TABLE_HEADING_SORT_UA', ' --> A-B-C From Top');
define('TABLE_HEADING_SORT_U1', ' --> 1-2-3 From Top');
define('TABLE_HEADING_SORT_DA', ' --> Z-Y-X From Top');
define('TABLE_HEADING_SORT_D1', ' --> 3-2-1 From Top');

define('TEXT_DEFAULT_REFERRAL', 'Referral Points');
define('TEXT_DEFAULT_REVIEWS', 'Review Points');
define('TEXT_TYPE_REFERRAL', 'Referral');
define('TEXT_TYPE_REVIEW', 'Review');

define('TEXT_POINTS_PENDING', 'Pending');
define('TEXT_POINTS_CONFIRMED', 'Confirmed');
define('TEXT_POINTS_CANCELLED', 'Cancelled');
define('TEXT_SHOW_ALL', 'Show All');

define('TEXT_INFO_POINTS_COMMENT', 'Current Points Comment : ');
define('TEXT_INFO_ORDER_ID', 'Order Id:');
define('TEXT_INFO_ORDER_TOTAL', 'Order Total:');
define('TEXT_INFO_ORDER_STATUS', 'Order Status:');
define('TEXT_INFO_PRODUCT_ID', 'Product Id:');
define('TEXT_INFO_REVIEW_ID', 'Review Id:');
define('TEXT_INFO_PRODUCT_NAME', 'Product Name:');
define('TEXT_INFO_REFERRED', 'Referred:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');
define('TEXT_INFO_CURRENT_BALANCE', 'Current Points Balance:');

define('TEXT_INFO_HEADING_ADJUST_POINTS', 'Adjust Pending Points.');
define('TEXT_INFO_HEADING_DELETE_RECORD', 'Delete record');
define('TEXT_INFO_HEADING_PENDING_NO', 'Pending points for order no.');
define('TEXT_CONFIRM_POINTS', 'Confirm Pending Points to Customer ?');
define('TEXT_CONFIRM_POINTS_LONG', 'You can confirm points to customer with/without queuing points table.<br>confirming points without queuing will remove this line from table else, the Current points status will replaced with "Confirmed" .');
define('TEXT_CANCEL_POINTS', 'Cancel Customer Pending Points?');
define('TEXT_CANCEL_POINTS_LONG', 'You can cancel points to customer with/without queuing points table.<br>Cancelling points without queuing will remove this line from table else, pending points status will show "Cancelled" and default comment will be replaced with your Cancellation Reason.');
define('TEXT_CANCELLATION_REASON', 'Cancellation Reason :');
define('TEXT_ADJUST_INTRO', 'This option enable you to adjust the total amount of pending points before confirming them.<br>Note that this will replace the current pending points amount and can not be undone.');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this record ?<br>This will remove the recored from database.');
define('TEXT_POINTS_TO_ADJUST', 'New points amount :');
define('TEXT_ROLL_POINTS', 'Roll Back points.');
define('TEXT_ROLL_POINTS_LONG', 'This option enable you to rollback confirmed points to pending status.<br>Points will be deducted from customer account and status will show default pending status.');
define('TEXT_ROLL_REASON', 'Roll Back Reason :');

define('TEXT_QUEUE_POINTS_TABLE', 'Queue customers points table');
define('TEXT_NOTIFY_CUSTOMER', 'Notify Customer');
define('TEXT_SET_EXPIRE', 'Set new expire date');

define('BUTTON_TEXT_ADJUST_POINTS', 'Adjust the current pending points amount');
define('BUTTON_TEXT_CANCEL_PENDING_POINTS', 'Cancel Customer Points');
define('BUTTON_TEXT_CONFIRM_PENDING_POINTS', 'Confirm Points to Customer');
define('BUTTON_TEXT_REMOVE_RECORD', 'Delete this record from databse');
define('BUTTON_TEXT_ROLL_POINTS', 'Roll Back points to pending status');
define('ICON_PREVIEW_EDIT', 'View order details or edit status');
define('ICON_REVIEWS_EDIT', 'View or edit Review contains');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Points Account Update .');
define('EMAIL_GREET_MR', 'Dear Mr. %s,');
define('EMAIL_GREET_MS', 'Dear Ms. %s,');
define('EMAIL_GREET_NONE', 'Dear %s');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_ORDER_STAUTS', 'Order Status:');
define('EMAIL_TEXT_INTRO', 'This is to inform you that your Shopping Points Account has been updated.');
define('EMAIL_TEXT_BALANCE_CANCELLED', 'We are sorry but we had to cancel your points for the following details.');
define('EMAIL_TEXT_BALANCE_CONFIRMED', 'Points confirmed for the following details.');
define('EMAIL_TEXT_BALANCE_ROLL_BACK', 'Confirmed Points for the following details as been returned to previous status.');
define('EMAIL_TEXT_ROLL_COMMENT', 'Comment :');
define('EMAIL_TEXT_BALANCE', 'Your current Shopping Points balance is: %s points valued at %s .');
define('EMAIL_TEXT_EXPIRE', 'Points will expire at : %s .');
define('EMAIL_TEXT_POINTS_URL', 'For your convenience here is the link to your Shopping Points Account . %s');
define('EMAIL_TEXT_POINTS_URL_HELP', 'Our store Reward Point Program FAQ page located here . %s');
define('EMAIL_TEXT_COMMENT', 'Cancellation Reason :');
define('EMAIL_TEXT_SUCCESS_POINTS', 'Points are available at your account, during the checkout procces you\'ll be able to pay for your order with your points balance. '. "\n" .'Thank you for shopping at ' . STORE_NAME . ' and we looking forward to serving you again.');
define('EMAIL_CONTACT', 'If you have any questions or for help with any of our online services, please email us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n" . 'This is an automated response, please do not reply!');

define('SUCCESS_POINTS_UPDATED', 'Success: Customer Points account has been successfully updated.');
define('SUCCESS_DATABASE_UPDATED', 'Queue Success: Database has been successfully updated and points status set to  ' . TEXT_POINTS_CANCELLED . '  with this comment " '. $comment_cancel . ' ".');
define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('NOTICE_RECORED_REMOVED', 'Notice: The points record row no. ' . $uID . ' has been deleted from database.');
define('WARNING_DATABASE_NOT_UPDATED', 'Warning: Empty fields, Nothing to change. The Database was not updated.');
define('POINTS_ENTER_JS_ERROR', 'Invalid entry! \n Only numbers are accepted!');

define('TEXT_LINK_CREDIT', 'Click here to run the <a href="customers_points_credit.php"><u>Auto Credit</u></a> or <a href="customers_points_expire.php"><u>Auto Expire</u></a> script manually.');
?>
