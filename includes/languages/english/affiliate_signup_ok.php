<?php
/*
  $Id: affiliate_signup_ok.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Affiliate Signup');
define('HEADING_TITLE', 'Congratulations!');
define('TEXT_ACCOUNT_CREATED', 'Congratulations! Your new Affiliate account application has been submitted! You will shortly receive an email containing important information regarding your Affiliate Account, including you affiliate login details. If you have not received it within the hour, please <a href="' . tep_href_link('affiliate_contact.php') . '">contact us</a>.<br><br>If you have <small><b>ANY</b></small> questions about the affiliate program, please <a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT) . '">contact us</a>.');
?>