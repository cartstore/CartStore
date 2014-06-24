<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_INFO);

// set application wide parameters
// this query set is for FAQDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

// lets retrieve all $_GET keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
    $get_params_back = $get_params;
}

// BOF Wolfen added code to retrieve backpath
$get_backpath = tep_get_all_get_params();
$get_backpath_back = tep_get_all_get_params(array('faqdesk_id')); // for back button
$get_backpath = substr($get_backpath, 0, -14); //remove trailing &
if ($get_backpath_back != '') {
    $get_backpath_back = substr($get_backpath_back, 0, -14); //remove trailing &
} else {
    $get_backpath_back = $get_backpath;
}
// EOF Wolfen added code to retrieve backpath

// BOF Added by Wolfen
// calculate category path
if ($_GET['faqdeskPath']) {
$newsPath = $_GET['faqdeskPath'];
} elseif ($_GET['faqdesk_id'] && !$_GET['faqdeskPath']) {
$newsPath = faqdesk_get_product_path($_GET['faqdesk_id']);
} else {
$faqPath = '';
}
if (strlen($faqPath) > 0) {
	$faqPath_array = faqdesk_parse_category_path($faqPath);
	$faqPath = implode('_', $faqPath_array);
	$current_category_id = $faqPath_array[(sizeof($faqPath_array)-1)];
} else {
	$current_category_id = 0;
}

$breadcrumb->add(NAVBAR_HOME, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));
//$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));
//$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=', 'NONSSL'));
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<?php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE; ?></title>
<?php
}
?>

<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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



<table border="0" width="100%" cellspacing="3" cellpadding="3">
        <tr>
                <td>



<?php
$product_info = tep_db_query("
select p.faqdesk_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, 
p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, pd.faqdesk_extra_url, pd.faqdesk_extra_url_name, pd.faqdesk_extra_viewed, p.faqdesk_date_added, 
p.faqdesk_date_available 
from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . $_GET['faqdesk_id'] . "' 
and pd.faqdesk_id = '" . $_GET['faqdesk_id'] . "' and pd.language_id = '" . $languages_id . "'");

if (!tep_db_num_rows($product_info)) { // product not found in database
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="main"><br><?php echo TEXT_NEWS_NOT_FOUND; ?></td>
	</tr>
	<tr>
		<td align="right">
			<br>
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
		</td>
	</tr>
</table>

<?php
} else {
	tep_db_query("update " . TABLE_FAQDESK_DESCRIPTION . " set faqdesk_extra_viewed = faqdesk_extra_viewed+1 where faqdesk_id = '" . $_GET['faqdesk_id'] . "' and language_id = '" . $languages_id . "'");
	$product_info_values = tep_db_fetch_array($product_info);

if (($product_info_values['faqdesk_image'] != 'Array') && ($product_info_values['faqdesk_image'] != '')) {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}


if (($product_info_values['faqdesk_image_two'] != '') && ($product_info_values['faqdesk_image_two'] != '')) {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_two'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

if (($product_info_values['faqdesk_image_three'] != '') && ($product_info_values['faqdesk_image_three'] != '')) {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_three'], $product_info_values['faqdesk_question'], '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="pageHeading"><?php echo TEXT_FAQDESK_HEADING; ?></td>
		<td align="right">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr class="headerNavigation">
		<td class="tableHeading"><?php echo $product_info_values['faqdesk_question']; ?></td>
		</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td width="100%" class="main" valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_short']); ?>

<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_long']); ?>

<?php if ($product_info_values['faqdesk_extra_url']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php $faqlink = ($product_info_values['faqdesk_extra_url_name']); ?>
<?php echo sprintf(TEXT_FAQDESK_LINK . '<a href="%s" target="_blank"><u>' . $faqlink . '</u></a>.', tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['faqdesk_extra_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>
<!--
<?php if ($product_info_values['faqdesk_extra_url_name']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_FAQDESK_LINK, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['faqdesk_extra_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>
-->
<?php
$reviews = tep_db_query("
select count(*) as count from " . TABLE_FAQDESK_REVIEWS . " where approved='1' and faqdesk_id = '" 
. $_GET['faqdesk_id'] . "'
");
$reviews_values = tep_db_fetch_array($reviews);
?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_VIEWED . $product_info_values['faqdesk_extra_viewed'] ?></td>
	</tr>
<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
?>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS . ' ' . $reviews_values['count']; ?></td>
	</tr>
<?php
}
?>
</table>

		</td>
		<td width="" class="main" valign="top" align="center">
<?php
echo $insert_image;
echo $insert_image_two;
echo $insert_image_three;
?>
		</td>

	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
</table>

<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
	if ($reviews_values['count'] > 0) {
		require FILENAME_FAQDESK_ARTICLE_REQUIRE;
	}
}
?>
<?php
/*
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>
		<td align="right" class="main">
<?php 
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
?>
		</td>
	</tr>
</table>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
	<tr>
		<td class="main">
		<a href="<?php echo tep_href_link(FILENAME_FAQDESK_REVIEWS_ARTICLE, substr(tep_get_all_get_params(), 0, -1)); ?>">
<?php echo tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS); ?></a>
		</td>
		<td align="right" class="main">
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
		</td>
	</tr>
</table>
*/
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php 
if ( DISPLAY_FAQDESK_REVIEWS ) {
	echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_write_review.gif',
	IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
}
?>
		</td>
		<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

<?php
// BOF Wolfen added code for button case
if ($get_backpath_back = $get_backpath) {
echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_FAQDESK_INDEX, $get_backpath) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>' . tep_draw_separator('pixel_trans.gif', '10', '1') . '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
} else { 
echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
}
// EOF Wolfen added code for button case
?>		

		<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
	</tr>
</table>

		</td>
	</tr>
</table>

<?php } ?>
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

	This script is not part of the official CartStore distribution but an add-on contributed to the CartStore community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
