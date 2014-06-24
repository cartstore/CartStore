<?php
/*
$id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_TITLE', 'Product Exchange / Return');
define('HEADING_TITLE_SEARCH', 'RMA Number:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Admin Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Return Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Request Date');
define('TABLE_HEADING_REASON', 'Reason');
define('TABLE_HEADING_ACTION', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_OLD_DEPT', 'Old Dept');
define('TABLE_HEADING_NEW_DEPT', 'New Dept');
define('TABLE_HEADING_OLD_ADMIN', 'Old Admin');
define('TABLE_HEADING_NEW_ADMIN', 'New Admin');

define('TABLE_HEADING_NEW_VALUE', 'New Value');
define('TABLE_HEADING_OLD_VALUE', 'Old Value');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('CUSTOMER_PREFERENCE', 'Customers Preference');

define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_STREET_ADDRESS', 'Address Line 1:');
define('ENTRY_SUBURB', 'Address Line 2:');
define('ENTRY_CITY', 'City:');
define('ENTRY_CONTACT_NAME', 'Contact Name:');
define('ENTRY_POST_CODE', 'Zip Code:');
define('ENTRY_STATE', 'State:');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_TELEPHONE', 'Telephone:');
define('ENTRY_EMAIL_ADDRESS', 'Contact Email:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Refund Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');
define('ENTRY_TICKET_DATE', 'Return submitted on');
define('ENTRY_DEPARTMENT', 'Department');
define('ENTRY_SUPPORTER', 'Assigned to');
define('ENTRY_PAYMENT_DATE', 'Refund Date:');
DEFINE('ENTRY_NOTIFY_CLOSE', '<font color=red>Close ticket</font>');
define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Ticket');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this return?');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', '');

// BEGIN - Product Serial Numbers
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'Serial Number');
// END - Product Serial Numbers
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('ORDER_NO', 'Order #: ');
define('TEXT_INVOICE_NO', 'Returns #: ');
define('TEXT_DATE_TIME', 'Request Date: ');
define('TEXT_IP_ADDRESS', 'RMA Number: ');
define('TEXT_IP_ADDRESS_HOST', 'IP Address Host: ');
define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');

define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Return Completed:');

define('TEXT_ALL_ORDERS', 'All Tickets');
define('TEXT_NO_ORDER_HISTORY', 'No Ticket History Available');
define('ENTRY_PAYMENT_REFERENCE', 'Refund Ref #:');
define('ENTRY_PAYMENT_AMOUNT', 'Refund Amount:');
define('ENTRY_RESTOCK_CHARGE', 'Apply Restock Charge:');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Ticket does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Ticket has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The Ticket was not updated.');
define('SUCCESS_RETURNED_GIFT', 'Refund by gift voucher');
define('SUCCESS_RETURN_CLOSED', 'Return now completed');
define('SUCCESS_PRODUCT_TO_STOCK','Product added back to stock');
define('TEXT_GIFT_COMMENT', 'Gift voucher comments<br><font color=8E8E8E>NOTE: GV will be issued in<br> original customers name.</font>');
define('TEXT_BACK_TO_STOCK', ' Add back to stock');
define('TEXT_COMPLETE_RETURN', 'Complete this return');
define('TEXT_CUSTOM_PREF_METHOD','Customer preferred method:');

define('TEXT_SUPPORT_ADDED', 'Your return request has been sent and recorded in our database.');
define('TEXT_SUPPORT_UPDATE', 'The return request you submitted, has now been updated.');
define('TEXT_SUPPORT_SOLVED', 'Your return request has now been resolved.');
define('TEXT_SUPPORT_ADDED_TO_FAQ', 'The return request you submitted has now been added to the FAQ page.');
define('TEXT_SUPPORT_CLOSED', 'This return request is now closed.');

define('EMAIL_SEPARATOR', '===================================');
define('EMAIL_TEXT_SUBJECT', STORE_NAME . ' ' .'Return Update');
define('EMAIL_TEXT_GV_SUBJECT', STORE_NAME . ' '. 'Credit for returned product');
define('EMAIL_TEXT_ORDER_NUMBER', 'Return Number:');
define('EMAIL_TEXT_DATE_ORDERED', 'Return Request Date:');
define('EMAIL_TEXT_STATUS_UPDATE', '<b>Your return has been updated to the following status:</b>' . "\n" . 'New status: %s');
define('EMAIL_TEXT_REPLY', 'Please reply to this email if you have any questions.');
define('EMAIL_TEXT_COMMENTS_UPDATE', '<b>Comments & Directions to Complete Your Return:</b>' . "\n" . "%s\n");
?>
