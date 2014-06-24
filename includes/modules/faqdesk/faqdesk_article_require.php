<?php

//require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_REVIEWS_ARTICLE);

// lets retrieve all $_GET keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
    $get_params_back = $get_params;
}

$product = tep_db_query("
select faqdesk_question from " . TABLE_FAQDESK_DESCRIPTION . " where language_id = '" . $languages_id . "' 
and faqdesk_id = '" . $_GET['faqdesk_id'] . "'
");
$product_info_values = tep_db_fetch_array($product);
?>



<!-- BEGIN faqdesk_article_require //-->
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS . sprintf($product_info_values['faqdesk_question']); ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tableHeading"><?php echo TABLE_HEADING_NUMBER; ?></td>
		<td class="tableHeading"><?php echo TABLE_HEADING_AUTHOR; ?></td>
		<td align="center" class="tableHeading"><?php echo TABLE_HEADING_RATING; ?></td>
		<td align="center" class="tableHeading"><?php echo TABLE_HEADING_READ; ?></td>
		<td align="right" class="tableHeading"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
	</tr>
	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>

<?php
$reviews = tep_db_query("
select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " 
. TABLE_FAQDESK_REVIEWS . " where approved='1' and faqdesk_id = '" . $_GET['faqdesk_id'] . "' order by reviews_id DESC
");

if (tep_db_num_rows($reviews)) {
	$row = 0;
	while ($reviews_values = tep_db_fetch_array($reviews)) {
		$row++;
		if (strlen($row) < 2) {
			$row = '0' . $row;
		}
		$date_added = tep_date_short($reviews_values['date_added']);
		if (($row / 2) == floor($row / 2)) {
			echo '<tr class="productReviews-even">' . "\n";
		} else {
			echo '<tr class="productReviews-odd">' . "\n";
		}
		echo '<td class="smallText">' . $row . '.</td>' . "\n";
		echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a></td>' . "\n";
		echo '<td align="center" class="smallText">' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])) . '</td>' . "\n";
		echo '<td align="center" class="smallText">' . $reviews_values['reviews_read'] . '</td>' . "\n";
		echo '<td align="right" class="smallText">' . $date_added . '</td>' . "\n";
		echo '</tr>' . "\n";
	}
} else {
?>

	<tr class="productReviews-odd">
		<td colspan="5" class="smallText"><?php echo TEXT_NO_REVIEWS; ?></td>
	</tr>

<?php
}
?>

	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
		<td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
</table>


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
*/
?>

<!-- eof faqdesk_article_require //-->

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
