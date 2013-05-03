<?php
/*
  $Id: create_account_success.php,v 1.9 2002/11/19 01:48:08 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

define('NAVBAR_TITLE_1', 'Create an Account');
define('NAVBAR_TITLE_2', 'Success');
define('HEADING_TITLE', 'Your Account Has Been Created!');
define('TEXT_ACCOUNT_CREATED', '<p><b>Congratulations! </b>Your new account has been successfully created! </p>

<p>A confirmation has been sent to the provided email address. If you have not received it within the hour, please <a class="general_link" href="' . tep_href_link(FILENAME_CONTACT_US) . '">contact us</a>.</p>
');
// Points/Rewards system V2.00 BOF
define('TEXT_WELCOME_POINTS_TITLE', '<p>As part of our Welcome to new customers, we have credited your  <a class="general_link" href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '">Shopping Points Account</a>  with a total of %s Shopping Points worth %s');
define('TEXT_WELCOME_POINTS_LINK', ' Please refer to the  <a class="general_link" href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') . '">Reward Point Program FAQ</a> as conditions may apply.</p>');
// Points/Rewards system V2.00 EOF
?>