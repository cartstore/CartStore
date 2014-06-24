<?php

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (@$_GET['action'] == 'process') {
	$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
	$customer_values = tep_db_fetch_array($customer);
	$date_now = date('Ymd');

	tep_db_query("insert into " . TABLE_NEWSDESK_REVIEWS . " (newsdesk_id, customers_id, customers_name, reviews_rating, date_added) values ('" . $_GET['newsdesk_id'] . "', '" . $customer_id . "', '" . addslashes($customer_values['customers_firstname']) . ' ' . addslashes($customer_values['customers_lastname']) . "', '" . $_POST['rating'] . "', now())");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . $insert_id . "', '" . $languages_id . "', '" . $_POST['review'] . "')");

	tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $_POST['get_params'], 'NONSSL'));
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

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_REVIEWS_WRITE);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL'));

$product = tep_db_query("select pd.newsdesk_article_name, p.newsdesk_image from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . $_GET['newsdesk_id'] . "' and pd.newsdesk_id = p.newsdesk_id and pd.language_id = '" . $languages_id . "'");

$product_info_values = tep_db_fetch_array($product);

$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
$customer_values = tep_db_fetch_array($customer);

if ($product_info_values['newsdesk_image'] != '') {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $product_info_values['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$product_info_values['newsdesk_image'], '', '') . '</a>
		</td>
	</tr>
</table>
';
 }

//' . tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], $product_info_values['newsdesk_article_name'] '', '') . '

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--
function checkForm() {
var error = 0;
var error_message = "<?php echo JS_ERROR; ?>";
var review = document.newsdesk_reviews_write.review.value;

if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
	error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
	error = 1;
}

if ((document.newsdesk_reviews_write.rating[0].checked) || (document.newsdesk_reviews_write.rating[1].checked) || (document.newsdesk_reviews_write.rating[2].checked) || (document.newsdesk_reviews_write.rating[3].checked) || (document.newsdesk_reviews_write.rating[4].checked)) {
} else {
	error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
	error = 1;
}

if (error == 1) {
	alert(error_message);
	return false;
} else {
	return true;
}
}
//--></script>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="includes/menu_animation.js"></SCRIPT>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- coolMenu //-->
<?php require(DIR_WS_INCLUDES . 'coolmenu.php'); ?>
<!-- coolMenu_eof //-->
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->

</table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">


<form name="newsdesk_reviews_write" method="post" action="<?php echo tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, 'action=process&newsdesk_id=' . $_GET['newsdesk_id'], 'NONSSL'); ?>" onSubmit="return checkForm();">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
		<td class="pageHeading" align="right">
<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
		</td>
	</tr>
	<tr>
		<td class="footer" colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="main" valign="top">

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main" width="50%">
		<b>
<?php echo SUB_TITLE_PRODUCT; ?>
		</b>
<?php echo $product_info_values['newsdesk_article_name']; ?>
		</td>
	</tr>
	<tr>
		<td class="main">
		<b>
<?php echo SUB_TITLE_FROM; ?>
		</b>
<?php echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];?>
		</td>
	</tr>
	<tr>
		<td class="main">
		<b>
<?php echo SUB_TITLE_RATING; ?>
		</b>
<?php echo TEXT_BAD; ?>
<input type="radio" name="rating" value="1">
<input type="radio" name="rating" value="2">
<input type="radio" name="rating" value="3">
<input type="radio" name="rating" value="4">
<input type="radio" name="rating" value="5">
<?php echo TEXT_GOOD; ?>
		</td>
	</tr>
</table>


		</td>
		<td class="main" valign="top">
<?php echo $insert_image; ?>
		</td>
	</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_textarea_field('review', 'soft', 60, 15);?></td>
	</tr>
	<tr>
		<td class="smallText"><?php echo TEXT_NO_HTML; ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>
		<td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
	</tr>
</table>

<input type="hidden" name="get_params" value="<?php echo $get_params; ?>">
</form>

		</td>

<!-- body_text_eof //-->
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

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

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk(Persian Support)
	version:        		1.48.2F
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www.ariait.ir
	modified code by:		Ali Masooumi Fariman (masooumi@gmail.com)
*/
?>