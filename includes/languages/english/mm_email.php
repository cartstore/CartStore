<?php
/*
  $Id: mail_manager.php 2011-06-30
  http://www.css-oscommerce.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Email Manager');

define('TABLE_HEADING_MAIL', 'Mailing');
define('TABLE_HEADING_ID', 'Mail id');
define('TABLE_HEADING_SIZE', 'Size');
define('TABLE_HEADING_TEMPLATE', 'Template');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_TEMPLATE', 'Template:');
define('TEXT_SEARCH', '<strong>Customer lookup:</strong><br />(exact or like)');
define('TEXT_CLICKCUS', 'Click email to select customer:');
define('TEXT_NOCUSTOMER', 'No customer match this search.');
define('TEXT_CUSTOMER', 'Customer:');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_MAIL_TITLE', 'Mail Title:');
define('TEXT_MAIL_CONTENT', 'Html Content:');
define('TEXT_MAIL_TXTCONTENT', 'Text Content:');
define('TEXT_MAIL_MAIL_ID', 'Mail Id:');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this Mail?');
define('TEXT_CONFIRM_RESTORE', 'Are you sure you want to restore this email to your last updated version?');
define('TEXT_CONFIRM_RESET', 'Are you sure you want to reset this email? All changes will be permanently deleted.');

define('ERROR_MAIL_TITLE', 'Error: Mail title required');
define('ERROR_MAIL_MODULE', 'Error: Mail module required');

define('TEXT_SUBJECT', 'Subject:');
define('TEXT_FROM', 'From:');
define('TEXT_TEST_MESSAGE', '<strong>test:</strong> Sends the selected letter to the store owner.');
define('TEXT_BACKUP_MESSAGE', '<strong>restore:</strong> Restores the selected letter to the last updated version.');
define('TEXT_RESET_MESSAGE', '<strong>reset:</strong> Resets the selected letter to the orginal installation.');
define('TEXT_NEWMAIL_WARNING', '<p> Warning: emails are selected by mail id number. Emails will be sent automatically according to the key below.
								For example the email given a mail_id of 0 will be sent everytime a new account is created.<br />
								</p><p><br /><ul>Key<li>mail id = 0 is the new account (create account) welcome email. Sent from the file create_account.php</li>
								<li>mail id = 1 is the email sent from checkout_process.php when a customer completes an order (order confirmation)</li>
								<li>mail id = 2 is the email sent from admin/order.php when store owner updates an order status (status update)</li><ul></p><br />');

define('TEXT_TEMPLATE_SELECT','Attach a Template to the selected email.<br />Templates are created in the template manager.');      	 
define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
?>
