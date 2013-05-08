<?php
/*
  One Page Checkout, Version: 1.08

  I.T. Web Experts
  http://www.itwebexperts.com

  Copyright (c) 2009 I.T. Web Experts

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Checkout');
define('NAVBAR_TITLE_1', 'Checkout');

define('HEADING_TITLE', 'Checkout');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TABLE_HEADING_BILLING_ADDRESS', 'Billing Address');

define('TABLE_HEADING_PRODUCTS_MODEL', 'Products Model');
define('TABLE_HEADING_PRODUCTS_NAME', 'Products Name');
define('TABLE_HEADING_PRODUCTS_QTY', 'Quantity');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Price Each');
define('TABLE_HEADING_PRODUCTS_FINAL_PRICE', 'Total Price');
define('TABLE_HEADING_PRODUCTS_REMOVE_ITEM', 'Remove Item');

//define('TABLE_HEADING_PRODUCTS', 'Shopping Cart');
//define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');

define('ENTRY_TELEPHONE', 'Telephone: ');

define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Please choose from your address book where you would like the items to be delivered to.');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');

define('TITLE_SHIPPING_ADDRESS', 'Shipping Address:');
define('TITLE_BILLING_ADDRESS', 'Billing Address:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping Method');
define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');

define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please select the preferred shipping method to use on this order.');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please select the preferred payment method to use on this order.');

define('TITLE_PLEASE_SELECT', 'Please Select');

define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');

define('TABLE_HEADING_COMMENTS', 'Add Comments About Your Order');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to confirm this order.');

define('TEXT_EDIT', 'Edit');

define('TEXT_SELECTED_SHIPPING_DESTINATION', 'This is the currently selected shipping address where the items in this order will be delivered to.');
define('TABLE_HEADING_NEW_ADDRESS', 'New Address');
define('TABLE_HEADING_EDIT_ADDRESS', 'Edit Address');
define('TEXT_CREATE_NEW_SHIPPING_ADDRESS', 'Please use the following form to create a new shipping address to use for this order.');
define('TABLE_HEADING_ADDRESS_BOOK_ENTRIES', 'Address Book Entries');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

/* CCGV - BEGIN */
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
										' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
/* CCGV - END */

define('TEXT_AGREE_TO_TERMS', 'I agree to the terms and conditions');

define('WINDOW_BUTTON_CANCEL', 'Cancel');
//define('WINDOW_BUTTON_CONTINUE', 'Continue');
define('WINDOW_BUTTON_CONTINUE', 'Confirm');
define('WINDOW_BUTTON_NEW_ADDRESS', 'New Address');
define('WINDOW_BUTTON_EDIT_ADDRESS', 'Edit Address');

define('TEXT_PLEASE_SELECT', 'Please Select');
define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');
define('IMAGE_UPDATE_CART', 'Update Cart');
define('IMAGE_LOGIN', 'Login');
define('TEXT_PAYMENT_METHOD_UPDATE_ERROR', 'Please try again and if problems persist, please try another payment method.');
define('TEXT_HAVE_COUPON_CCGV', '<b>Have A Coupon?</b>');
define('TEXT_HAVE_COUPON_KGT', '<b>Have A Coupon?</b>');
define('TEXT_EXISTING_CUSTOMER_LOGIN', 'Already have an account?');
define('TEXT_DIFFERENT_SHIPPING', 'Different from billing address?');
define('TEXT_SHIPPING_NO_ADDRESS', 'Please fill in <b>at least</b> your billing address to get shipping quotes.');
define('TEXT_CHECKOUT_UPDATE_VIEW_ORDER', 'to update/view your order.');
//define('CHECKOUT_BAR_CONFIRMATION', 'Checkout');
// Points/Rewards Module V2.1rc2a BOF
define('TABLE_HEADING_REDEEM_SYSTEM', 'Shopping Rewards Points Redemptions ');
define('TABLE_HEADING_REFERRAL', 'Referral System');
define('TEXT_REDEEM_SYSTEM_START', 'You have a credit balance of %s ,would you like to use it to pay for this order?<br />The estimated total of your purchase is: %s .');
define('TEXT_REDEEM_SYSTEM_SPENDING', 'Tick here to use Maximum Points allowed for this order. (%s points %s)&nbsp;&nbsp;->');
define('TEXT_REDEEM_SYSTEM_NOTE', '<span class="pointWarning">Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method</span>');
define('TEXT_REFERRAL_REFERRED', 'If you were referred to us by a friend please enter their email address here. ');
// Points/Rewards Module V2.1rc2a EOF
?>