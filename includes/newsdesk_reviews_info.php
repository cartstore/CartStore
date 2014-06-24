<?php

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_REVIEWS_INFO);

// lets retrieve all $_GET keys and values..
$get_params = tep_get_all_get_params(array('reviews_id'));
$get_params = substr($get_params, 0, -1); //remove trailing &

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL'));
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupImageWindow(url) {
window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->

</table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php
tep_db_query("update " . TABLE_NEWSDESK_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $_GET['reviews_id'] . "'");

$reviews = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.newsdesk_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read from " . TABLE_NEWSDESK_REVIEWS . " r, " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $_GET['reviews_id'] . "' and r.reviews_id = rd.reviews_id");

$reviews_values = tep_db_fetch_array($reviews);

$reviews_text = htmlspecialchars($reviews_values['reviews_text']);
$reviews_text = tep_break_string($reviews_text, 60, '-<br>');

$product = tep_db_query("select p.newsdesk_id, pd.newsdesk_article_name, p.newsdesk_image from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . $reviews_values['newsdesk_id'] . "' and pd.newsdesk_id = p.newsdesk_id and pd.language_id = '". $languages_id . "'");

$product_info_values = tep_db_fetch_array($product);
?>

	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info_values['products_name']); ?></td>
		<td class="pageHeading" align="right">
<?php
echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']), '', '');
?>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_PRODUCT; ?></b> <?php echo $product_info_values['newsdesk_article_name']; ?></td>
		<td class="smallText" rowspan="3" align="center">
<?php echo tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], $product_info_values['newsdesk_article_name'], '', '', 'align="center" hspace="5" vspace="5"'); ?>
		</td>
	</tr>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_FROM; ?></b> <?php echo $reviews_values['customers_name']; ?></td>
	</tr>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_DATE; ?></b> <?php echo tep_date_long($reviews_values['date_added']); ?></td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
	</tr>
	<tr>
		<td class="main"><br><?php echo nl2br($reviews_text); ?></td>
	</tr>
	<tr>
		<td class="main">
<br><b>
<?php
echo SUB_TITLE_RATING; ?></b> <?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])); ?> <small>[<?php echo sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']); ?>]</small>
		</td>
	</tr>
	<tr>
		<td>
		<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main">
		</td>
		<td align="right" class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>

		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
		</td>
<!-- body_text_eof //-->
		    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->

</table>
		</td>
	</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>