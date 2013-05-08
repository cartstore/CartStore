<?php
/*
  $Id: edit_orders.php,v 2.5 2006/04/28 10:42:44 ams Exp $
  english
  
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  
  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Edit Order');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'of');
define('HEADING_SUBTITLE', 'Please edit all parts as desired and click on the "Update" button below.');
define('HEADING_TITLE_STATUS', 'Status');
define('ADDING_TITLE', 'Add a product to this order');

define('HINT_UPDATE_TO_CC', 'Set payment method to ');
//ENTRY_CREDIT_CARD should be whatever is saved in your db as the payment method
//when your customer pays by Credit Card
define('ENTRY_CREDIT_CARD', 'Credit Card');
define('HINT_UPDATE_TO_CC2', ' and the other fields will be displayed automatically.  CC fields are hidden if any other payment method is selected.');
define('HINT_PRODUCTS_PRICES', 'Price and weight calculations are done on the fly, but you must hit update in order to save any changes.  Zero and negative values may be entered for quantity. If you want to delete a product, check the delete box and hit update. Weight fields are not editable.');
define('HINT_SHIPPING_ADDRESS', 'If the shipping destination is changed this may change the tax zone the order is in as well.  You will have to press the update button again to properly calculate tax totals in this case.');
define('HINT_TOTALS', 'Feel free to give discounts by adding negative values. Any field with a value of 0 is deleted when updating the order (exception: shipping).  Weight, subtotal, tax total, and total fields are not editable. On-the-fly calculations are estimates; small rounding differences are possible after updating.');
define('HINT_PRESS_UPDATE', 'Please click on "Update" to save all changes.');
define('HINT_BASE_PRICE', 'Price (base) is the products price before products attributes (ie, the catalog price of the item)');
define('HINT_PRICE_EXCL', 'Price (excl) is the base price plus any product attributes prices that may exist');
define('HINT_PRICE_INCL', 'Price (incl) is Price (excl) times tax');
define('HINT_TOTAL_EXCL', 'Total (excl) is Price (excl) times qty');
define('HINT_TOTAL_INCL', 'Total (incl) is Price (excl) times tax and qty');

define('TABLE_HEADING_COMMENTS', 'Comment');
define('TABLE_HEADING_STATUS', 'New Status');
define('TABLE_HEADING_QUANTITY', 'Qty');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS_WEIGHT', 'Weight');
define('TABLE_HEADING_PRODUCTS', 'Product');
define('TABLE_HEADING_TAX', 'Tax %');
define('TABLE_HEADING_BASE_PRICE', 'Price (base)');
define('TABLE_HEADING_UNIT_PRICE', 'Price (excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Price (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total (excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Amount');
define('TABLE_HEADING_TOTAL_WEIGHT', 'Total Weight: ');
define('TABLE_HEADING_DELETE', 'Delete?');
define('TABLE_HEADING_SHIPPING_TAX', 'Shipping tax: ');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer notified');
define('TABLE_HEADING_DATE_ADDED', 'Entry date');

define('ENTRY_CUSTOMER_NAME', 'Name');
define('ENTRY_CUSTOMER_COMPANY', 'Company');
define('ENTRY_CUSTOMER_ADDRESS', 'Customer Address');
define('ENTRY_CUSTOMER_SUBURB', 'Suburb');
define('ENTRY_CUSTOMER_CITY', 'City');
define('ENTRY_CUSTOMER_STATE', 'State');
define('ENTRY_CUSTOMER_POSTCODE', 'Postcode');
define('ENTRY_CUSTOMER_COUNTRY', 'Country');
define('ENTRY_CUSTOMER_PHONE', 'Phone');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Address');
define('ENTRY_CURRENCY_TYPE', 'Currency');
define('ENTRY_CURRENCY_VALUE', 'Currency Value');

define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address');
define('ENTRY_BILLING_ADDRESS', 'Billing Address');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub Total:');

//do not put a colon (" : ") in the definition of ENTRY_TAX
//ie entry should be 'Tax' NOT 'Tax:'
define('ENTRY_TAX', 'Tax');

define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Order Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Send comments:');

define('TEXT_NO_ORDER_HISTORY', 'No order found');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Your order has been updated');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Order date:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Thank you so much for your order with us!' . "\n\n" . 'The status of your order has been updated.' . "\n\n" . 'New status: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at the ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Here are the comments for your order:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: No such order.');
define('SUCCESS_ORDER_UPDATED', 'Completed: Order has been successfully updated.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Choose a product');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Choose an option');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Product has no options, so skipping...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'pieces of this product');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Add');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Choose a catalogue. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Choose a product. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Choose an option. ');

define('MENUE_TITLE_CUSTOMER', '1. Customer Data');
define('MENUE_TITLE_PAYMENT', '2. Payment Method');
define('MENUE_TITLE_ORDER', '3. Ordered Products');
define('MENUE_TITLE_TOTAL', '4. Discount, Shipping and Total');
define('MENUE_TITLE_STATUS', '5. Status and Notification');
define('MENUE_TITLE_UPDATE', '6. Update Data');
//add-on for downloads
define('ENTRY_DOWNLOAD_COUNT', 'Download #');
define('ENTRY_DOWNLOAD_FILENAME', 'Filename');
define('ENTRY_DOWNLOAD_MAXDAYS', 'Expiry days');
define('ENTRY_DOWNLOAD_MAXCOUNT', 'Downloads remaining');
?>