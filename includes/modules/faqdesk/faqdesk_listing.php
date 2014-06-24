<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="cat_description"><?php echo TABLE_HEADING_TOPICS; ?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>

<?php
$listing_numrows_sql = $listing_sql;
// $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_FAQDESK_SEARCH_RESULTS, '*', $_GET['page']);
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_FAQDESK_SEARCH_RESULTS, 'p.faqdesk_id');
// fix counted faqs
$listing_numrows = tep_db_query($listing_numrows_sql);
$listing_numrows = tep_db_num_rows($listing_numrows);

if ($listing_numrows > 0 && (FAQDESK_PREV_NEXT_BAR_LOCATION == '1' || FAQDESK_PREV_NEXT_BAR_LOCATION == '3')) {
?>

	<tr>
		<td>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
	    <td class="smallText">&nbsp;<?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?>&nbsp;</td>
		<td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_FAQDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</td>
	</tr>
</table>

		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator(); ?></td>
	</tr>

<?php
}
?>

	<tr>
		<td>

<?php
$list_box_contents = array();
$list_box_contents[] = array('params' => 'class="productListing-heading"');
$cur_row = sizeof($list_box_contents) - 1;

$cl_size = sizeof($column_list);
for ($col=0; $col<$cl_size; $col++) {
	switch ($column_list[$col]) {
	case 'FAQDESK_DATE_AVAILABLE':
		$lc_text = TABLE_HEADING_DATE_AVAILABLE;
		$lc_align = 'left';
		break;
	case 'FAQDESK_SHORT_ANSWER':
		$lc_text = TABLE_HEADING_ARTICLE_SHORTTEXT;
		$lc_align = 'left';
		break;
	case 'FAQDESK_LONG_ANSWER':
		$lc_text = TABLE_HEADING_ARTICLE_DESCRIPTION;
		$lc_align = 'left';
		break;
	case 'FAQDESK_QUESTION':
		$lc_text = TABLE_HEADING_ARTICLE_NAME;
		$lc_align = 'left';
		break;
}

// --------------------------------------------------------------------------------------------------------------------------------------------
// turn off links -- dummy value is set at "FAQDESK_ARTICLE_URL" since I want to keep all the links active
// --------------------------------------------------------------------------------------------------------------------------------------------
if ($column_list[$col] != 'FAQDESK_ARTICLE_URL' && $column_list[$col] )
	$lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
	$list_box_contents[$cur_row][] = array(
		'align' => $lc_align,
		'params' => 'class="productListing-heading"',
		'text'  => "&nbsp;" . $lc_text . "&nbsp;"
		);
}
// --------------------------------------------------------------------------------------------------------------------------------------------

if ($listing_numrows > 0) {
/*	$number_of_faqs = '0';
	$listing = tep_db_query($listing_sql);
	while ($listing_values = tep_db_fetch_array($listing)) {
		$number_of_faqs++; */

	$number_of_products = '0';
	$listing_query = tep_db_query($listing_split->sql_query);
	while ($listing_values = tep_db_fetch_array($listing_query)) {
		$number_of_products++;
if ( ($number_of_products/2) == floor($number_of_products/2) ) {
//		if ( ($number_of_faqs/2) == floor($number_of_faqs/2) ) {
			$list_box_contents[] = array('params' => 'class="productListing-even"');
		} else {
			$list_box_contents[] = array('params' => 'class="productListing-odd"');
		}

		$cur_row = sizeof($list_box_contents) - 1;
		$cl_size = sizeof($column_list);
		for ($col=0; $col<$cl_size; $col++) {
			$lc_align = '';
			switch ($column_list[$col]) {
		case 'FAQDESK_DATE_AVAILABLE':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_date_added'] . '</a>&nbsp;';
			break;
		case 'FAQDESK_LONG_ANSWER':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_answer_long'] . '</a>&nbsp;';
			break;
		case 'FAQDESK_SHORT_ANSWER':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_answer_short'] . '</a>&nbsp;';
			break;
		case 'FAQDESK_QUESTION':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_question'] . '</a>&nbsp;';
			break;
		}

			$list_box_contents[$cur_row][] = array(
				'align' => $lc_align,
				'params' => 'class="productListing-data"',
				'text'  => $lc_text
				);

		}
	}

	new tableBox($list_box_contents, true);

	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	} else {
?>

	<tr class="productListing-odd">
		<td class="smallText">&nbsp;<?php echo TEXT_NO_ARTICLES ?>&nbsp;</td>
	</tr>

<?php
}
?>

	<tr>
		<td><?php echo tep_draw_separator(); ?></td>
	</tr>

<?php
if ($listing_numrows > 0 && (FAQDESK_PREV_NEXT_BAR_LOCATION == '1' || FAQDESK_PREV_NEXT_BAR_LOCATION == '3')) {
?>

	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
	    <td class="smallText">&nbsp;<?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?>&nbsp;</td>
		<td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_FAQDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</td>
	</tr>
</table>
		</td>
	</tr>

<?php
}
?>

</table>

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
