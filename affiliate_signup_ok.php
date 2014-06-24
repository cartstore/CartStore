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
	require('includes/application_top.php');
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SIGNUP_OK);	
	$breadcrumb->add(NAVBAR_TITLE);
	require(DIR_WS_INCLUDES . 'header.php');
	require(DIR_WS_INCLUDES . 'column_left.php'); 
?>
    <h1 class="main alert text-success text-center"><?php echo HEADING_TITLE; ?></h1>
	<p class="main alert alert-info"><?php echo TEXT_ACCOUNT_CREATED; ?></p>
	<p class="text-right"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . IMAGE_BUTTON_CONTINUE . '</a>'; ?></p>
<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
require(DIR_WS_INCLUDES . 'footer.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php');
