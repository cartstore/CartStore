<?php



require('includes/application_top.php');

require('includes/functions/newsdesk_general.php');



if ($_GET['action']) {

	switch ($_GET['action']) {

	case 'update':

		$reviews_id = tep_db_prepare_input($_GET['rID']);

		$reviews_rating = tep_db_prepare_input($_POST['reviews_rating']);

		$last_modified = tep_db_prepare_input($_POST['last_modified']);

		$reviews_text = tep_db_prepare_input($_POST['reviews_text']);



		tep_db_query("update " . TABLE_NEWSDESK_REVIEWS . " set reviews_rating = '" . tep_db_input($reviews_rating) . "', last_modified = now() where reviews_id = '" . tep_db_input($reviews_id) . "'");



		tep_db_query("update " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " set reviews_text = '" . tep_db_input($reviews_text) . "' where reviews_id = '" . tep_db_input($reviews_id) . "'");



		tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));

		break;

	case 'deleteconfirm':

		$reviews_id = tep_db_prepare_input($_GET['rID']);



		tep_db_query("delete from " . TABLE_NEWSDESK_REVIEWS . " where reviews_id = '" . tep_db_input($reviews_id) . "'");

		tep_db_query("delete from " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " where reviews_id = '" . tep_db_input($reviews_id) . "'");



		tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page']));

		break;



	case 'approve_review':

		$reviews_id = tep_db_prepare_input($_GET['rID']);

		tep_db_query("update " . TABLE_NEWSDESK_REVIEWS . " set approved=1 where reviews_id = " . $reviews_id);

		tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));

		break;

	case 'disapprove_review':

		$reviews_id = tep_db_prepare_input($_GET['rID']);

		tep_db_query("update " . TABLE_NEWSDESK_REVIEWS . " set approved=0 where reviews_id = " . $reviews_id);

		tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));

		break;



	}

}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	


<script language="javascript" src="includes/general.js"></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">



<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

	<tr>

		<td width="<?php echo BOX_WIDTH; ?>" valign="top">

<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">



<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->



</table>

		</td>

<!-- body_text //-->

		<td width="100%" valign="top">





<table border="0" width="100%" cellspacing="0" cellpadding="2">

	<tr>

		<td width="100%">



<table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

		<td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>

		<td class="pageHeading2" align="right"></td>

	</tr>

</table>



		</td>

	</tr>



<?php

if ($_GET['action'] == 'edit') {

	$rID = tep_db_prepare_input($_GET['rID']);



	$reviews_query = tep_db_query("select r.reviews_id, r.newsdesk_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_NEWSDESK_REVIEWS . " r, " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . tep_db_input($rID) . "' and r.reviews_id = rd.reviews_id");



	$reviews = tep_db_fetch_array($reviews_query);



	$products_query = tep_db_query("select newsdesk_image, newsdesk_image_two, newsdesk_image_three from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "'");



	$products = tep_db_fetch_array($products_query);



	$newsdesk_article_name_query = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "' and language_id = '" . $languages_id . "'");



	$products_name = tep_db_fetch_array($newsdesk_article_name_query);



	$rInfo_array = array_merge($reviews, $products, $products_name);

	$rInfo = new objectInfo($rInfo_array);

?>



	<tr>

<?php echo tep_draw_form('review', FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=preview'); ?>

		<td>



<table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" valign="top">

		<b>

<?php echo ENTRY_PRODUCT; ?>

		</b>

<?php echo $rInfo->newsdesk_article_name; ?>

		<br>

		<b>

<?php echo ENTRY_FROM; ?>

		</b>

<?php echo $rInfo->customers_name; ?>

		<br><br>

		<b>

<?php echo ENTRY_DATE; ?>

		</b>

<?php echo tep_date_short($rInfo->date_added); ?>

		</td>

	</tr>

</table>



		</td>

	</tr>

	<tr>

		<td>



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" valign="top">



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" valign="top">

		<b>

<?php echo ENTRY_REVIEW; ?>

		</b>

		<br><br>

<?php echo tep_draw_textarea_field('reviews_text', 'soft', '60', '15', $rInfo->reviews_text); ?>

		</td>

	</tr>

	<tr>

		<td class="smallText" align="right"><?php echo ENTRY_REVIEW_TEXT; ?></td>

	</tr>

</table>



		</td>

		<td class="main" valign="top">



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image_two, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image_three, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

</table>



		</td>

	</tr>

</table>



	</tr>

	<tr>

		<td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

	</tr>

	<tr>

		<td class="main">

		<b>

<?php echo ENTRY_RATING; ?>

		</b>&nbsp;

<?php echo TEXT_BAD; ?>

		&nbsp;

<?php for ($i=1; $i<=5; $i++) echo tep_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating) . '&nbsp;'; echo TEXT_GOOD; ?>

		</td>

	</tr>

	<tr>

		<td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

	</tr>

	<tr>

		<td align="right" class="main">

<?php

echo tep_draw_hidden_field('reviews_id', $rInfo->reviews_id) . tep_draw_hidden_field('newsdesk_id', $rInfo->products_id) 

. tep_draw_hidden_field('customers_name', $rInfo->customers_name) . tep_draw_hidden_field('newsdesk_article_name', $rInfo->newsdesk_article_name) 

. tep_draw_hidden_field('newsdesk_image', $rInfo->newsdesk_image) . tep_draw_hidden_field('newsdesk_image_two', $rInfo->newsdesk_image_two) 

. tep_draw_hidden_field('newsdesk_image_three', $rInfo->newsdesk_image_three) . tep_draw_hidden_field('date_added', $rInfo->date_added) 

. tep_image_submit('button_preview.png', IMAGE_PREVIEW) . ' <a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' 

. $_GET['page'] . '&rID=' . $_GET['rID']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>';

?>

		</td>

</form>

	</tr>



<?php

} elseif ($_GET['action'] == 'preview') {

	if ($_POST) {

		$rInfo = new objectInfo($_POST);

	} else {



		$reviews_query = tep_db_query("select r.reviews_id, r.newsdesk_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_NEWSDESK_REVIEWS . " r, " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $_GET['rID'] . "' and r.reviews_id = rd.reviews_id");



		$reviews = tep_db_fetch_array($reviews_query);



		$products_query = tep_db_query("select newsdesk_image, newsdesk_image_two, newsdesk_image_three from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "'");



		$products = tep_db_fetch_array($products_query);



		$newsdesk_article_name_query = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "' and language_id = '" . $languages_id . "'");



		$products_name = tep_db_fetch_array($newsdesk_article_name_query);



		$rInfo_array = array_merge($reviews, $products, $products_name);

		$rInfo = new objectInfo($rInfo_array);

	}

?>



	<tr>

<?php echo tep_draw_form('update', FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"'); ?>

		<td>

<table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" valign="top">

		<b>

<?php echo ENTRY_PRODUCT; ?>

		</b>

<?php echo $rInfo->newsdesk_article_name; ?>

		<br>

		<b>

<?php echo ENTRY_FROM; ?>

		</b>

<?php echo $rInfo->customers_name; ?>

		<br><br>

		<b>

<?php echo ENTRY_DATE; ?>

		</b>

<?php echo tep_date_short($rInfo->date_added); ?>

		</td>

	</tr>

</table>

	</tr>

	<tr>

		<td>



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td valign="top" class="main">



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td valign="top" class="main">

		<b>

<?php echo ENTRY_REVIEW; ?>

		</b>

		<br><br>

<?php echo nl2br(htmlspecialchars(tep_break_string($rInfo->reviews_text, 15))); ?>

		</td>

	</tr>

</table>



		</td>

		<td valign="top" class="main">



<table witdh="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image_two, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

	<tr>

		<td class="main" align="center">

<?php 

echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->newsdesk_image_three, $rInfo->newsdesk_article_name, '', '', 'hspace="5" vspace="5"');

?>

		</td>

	</tr>

</table>



		</td>

	</tr>

</table>



		</td>

	</tr>

	<tr>

		<td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

	</tr>

	<tr>

		<td class="main">

		<b>

<?php echo ENTRY_RATING; ?>

		</b>

		&nbsp;

<?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.png', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)); ?>

		&nbsp;

		<small>[

<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating); ?>

		]</small>

		</td>

	</tr>

	<tr>

		<td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

	</tr>



<?php

if ($_POST) {

/* Re-Post all POST'ed variables */

	reset($_POST);

		while(list($key, $value) = each($_POST)) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes($value)) . '">';

?>



	<tr>

		<td align="right" class="smallText">

<?php echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a> ' . tep_image_submit('button_update.png', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>'; ?>

		</td>

</form>

	</tr>



<?php

} else {

	if ($_GET['origin']) {

		$back_url = $_GET['origin'];

		$back_url_params = '';

	} else {

		$back_url = FILENAME_NEWSDESK_REVIEWS;

		$back_url_params = 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id;

	}

?>



	<tr>

		<td align="right">

<?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a>'; ?>

		</td>

	</tr>



<?php

	}

} else {

?>



	<tr>

		<td>

<table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

		<td valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="2">

	<tr class="dataTableHeadingRow">

		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>

		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_RATING; ?></td>

		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>

<td class="dataTableHeadingContent" align="center"><?php echo TEXT_APPROVED; ?></td>

		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>

	</tr>



<?php

$reviews_query_raw = "select reviews_id, newsdesk_id, date_added, last_modified, reviews_rating, approved from " . TABLE_NEWSDESK_REVIEWS . " order by date_added DESC";



$reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $reviews_query_raw, $reviews_query_numrows);

$reviews_query = tep_db_query($reviews_query_raw);

while ($reviews = tep_db_fetch_array($reviews_query)) {

	if ( ((!$_GET['rID']) || ($_GET['rID'] == $reviews['reviews_id'])) && (!$rInfo) ) {

		$reviews_text_query = tep_db_query("select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from " . TABLE_NEWSDESK_REVIEWS . " r, " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id");



		$reviews_text = tep_db_fetch_array($reviews_text_query);



		$products_image_query = tep_db_query("select newsdesk_image, newsdesk_image_two, newsdesk_image_three from " . TABLE_NEWSDESK . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "'");



		$products_image = tep_db_fetch_array($products_image_query);



		$products_name_query = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "' and language_id = '" . $languages_id . "'");



		$products_name = tep_db_fetch_array($products_name_query);



		$reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_NEWSDESK_REVIEWS . " where newsdesk_id = '" . $reviews['newsdesk_id'] . "'");



		$reviews_average = tep_db_fetch_array($reviews_average_query);



		$review_info = array_merge($reviews_text, $reviews_average, $products_name);

		$rInfo_array = array_merge($reviews, $review_info, $products_image);

		$rInfo = new objectInfo($rInfo_array);

	}



	if ( (is_object($rInfo)) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) {

		echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '\'">' . "\n";

	} else {

		echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id']) . '\'">' . "\n";

	}

?>



		<td class="dataTableContent">

<?php echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'preview.png', ICON_PREVIEW) . '</a>&nbsp;' . newsdesk_get_products_name($reviews['newsdesk_id']); ?>

		</td>

		<td class="dataTableContent" align="right"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.png'); ?>

		</td>

		<td class="dataTableContent" align="right"><?php echo tep_date_short($reviews['date_added']); ?></td>

<td class="dataTableContent" align="center"><?php echo $reviews['approved']==1?tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10):tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10); ?></td>

		<td class="dataTableContent" align="right">

<?php if ( (is_object($rInfo)) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>

		&nbsp;

		</td>

	</tr>



<?php

	}

?>



	<tr>

		<td colspan="4">

<table border="0" width="100%" cellspacing="0" cellpadding="2">

	<tr>

		<td class="smallText" valign="top">

<?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>

		</td>

		<td class="smallText" align="right">

<?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>

		</td>

	</tr>

</table>

		</td>

	</tr>

</table>

		</td>



<?php

$heading = array();

$contents = array();

	switch ($_GET['action']) {

	case 'delete':

		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');



		$contents = array('form' => tep_draw_form('reviews', FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm'));

		$contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);

		$contents[] = array('text' => '<br><b>' . $rInfo->newsdesk_article_name . '</b>');

		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');

		break;

	default:

	if (is_object($rInfo)) {

		$heading[] = array('text' => '<b>' . $rInfo->newsdesk_article_name . '</b>');



		$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a>');



if ($rInfo->approved==0){

	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=approve_review') . '">' . tep_image_button('button_review_approve.png', TEXT_APPROVE) . '</a>');

}

elseif ($rInfo->approved==1) {

	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=disapprove_review') . '">' . tep_image_button('button_review_disapprove.png', TEXT_DISAPPROVE) . '</a>');

}

else {

	$contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_APPROVED . ': ' . "Unknown" );

}

		$contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($rInfo->date_added));

		if (tep_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($rInfo->last_modified));

			$contents[] = array('text' => '<br>' . tep_info_image($rInfo->newsdesk_image, $rInfo->newsdesk_article_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));

			$contents[] = array('text' => '<br>' . tep_info_image($rInfo->newsdesk_image_two, $rInfo->newsdesk_article_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));

			$contents[] = array('text' => '<br>' . tep_info_image($rInfo->newsdesk_image_three, $rInfo->newsdesk_article_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));

			$contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);

			$contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.png'));

			$contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);

			$contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');

			$contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');



		}

		break;

}



if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

	echo '<td valign="top"  width="220px">' . "\n";



	$box = new box;

	echo $box->infoBox($heading, $contents);



	echo '</td>' . "\n";

}

?>



	</tr>

</table>

		</td>

	</tr>



<?php

}

?>



</table>

		</td>

<!-- body_text_eof //-->

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

	author:				Carsten aka moyashi

	web site:			www..com

	modified code by:		Wolfen aka 241

*/

?>