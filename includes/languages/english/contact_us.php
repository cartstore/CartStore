<?php
/*
  $Id: contact_us.php,v 1.7 2002/11/19 01:48:08 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Contact Us');
define('NAVBAR_TITLE', 'Contact Us');
define('TEXT_SUCCESS', 'Your enquiry has been successfully sent to the Store Owner.');
define('EMAIL_SUBJECT', 'Enquiry from ' . STORE_NAME);

define('ENTRY_NAME', 'Full Name:');
define('ENTRY_EMAIL', 'E-Mail Address:');
define('ENTRY_ENQUIRY', 'Enquiry:');


// BOF Super Contact us enhancement 1.5
define('ENTRY_ORDER_ID', 'Order ID (if applicable):');
define('OPENING_HOURS', '<div class="contact_page">
For your convenience, please use the form to contact us with any query you may have.  Please ensure that you have checked our FAQ section first as you may find the answers to your queries there. If you are enquiring about an order, please provide us with your order number.<br><br>
Please allow up between 15 minutes to 24 hours for a reply.<br><br>Please help to keep our overheads low by using emails whenever possible. Low overheads translates into lower prices. That, benefits everyone. <br><br>Thank you!</div>
');
define('ENTRY_REASON', 'Email Subject: ');
define('SEND_TO_TEXT', 'Send Contact Form Email To:');
define('SEND_TO_TYPE', 'radio');  //this will create a radio buttons for your contact list
//define('SEND_TO_TYPE', '');     //Change to this for a dropdown menu.

define('REASONS1', ' General Inquiry');
define('REASONS2', ' Product Enquiry');
define('REASONS3', ' Technical Enquiry');
define('REASONS4', ' Sales Enquiry');
define('REASONS5', ' Support Request');
//define('REASONS6', ' Template Enquiry');
// BOF Super Contact us enhancement 1.5

?>