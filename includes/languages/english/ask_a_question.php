<?php
/*
  $Id: tell_a_friend.php,v 1.7 2003/06/10 18:20:39 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
  
  Edit only lines 26 & 27.
*/

define('NAVBAR_TITLE', 'Ask a Question');

define('HEADING_TITLE', 'Ask a question about the:<br>%s');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Your Info');
define('FORM_TITLE_FRIEND_MESSAGE', 'Your Question');

define('FORM_FIELD_CUSTOMER_NAME', 'Your Name:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Your E-Mail Address:');


define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Your question about <b>%s</b> has been successfully sent...');

define('TEXT_EMAIL_SUBJECT', 'A question from %s');
define('TEXT_EMAIL_INTRO', '%s' . "\n\n" . 'A customer, %s, has a question about: %s - %s.');
define('TEXT_EMAIL_LINK', 'Here is the product link:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Regards,' . "\n\n" . '%s');

define('ERROR_FROM_NAME', 'Error: Your name must not be empty.');
define('ERROR_FROM_ADDRESS', 'Error: Your e-mail address must be a valid e-mail address.');
?>