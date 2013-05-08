<?php
$listing_numrows_sql = $listing_sql;
//$listing_split = new splitPageResults($listing_sql, $_GET['page'], MAX_DISPLAY_NEWSDESK_SEARCH_RESULTS, 'p.newsdesk_id');
// $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_NEWSDESK_SEARCH_RESULTS, '*', $_GET['page']);
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_NEWSDESK_SEARCH_RESULTS, 'p.newsdesk_id');
// fix counted products
$listing_numrows = tep_db_query($listing_numrows_sql);
$listing_numrows = tep_db_num_rows($listing_numrows);
if ($listing_numrows > 0 && (NEWSDESK_PREV_NEXT_BAR_LOCATION == '1' || NEWSDESK_PREV_NEXT_BAR_LOCATION == '3')) {
//if ( ($listing_split->number_of_rows > 0) && ( (NEWSDESK_PREV_NEXT_BAR_LOCATION == '1') || (NEWSDESK_PREV_NEXT_BAR_LOCATION == '3') ) ) {
?><h1><?php echo $categories['categories_name']; ?></h1>
<div id="module-product">
<div class="sort">
<table width="100%" >
	<tr>
		<td width="60%"><b><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></b></td>
		<td  width="40%"><ul><?php echo '' . $listing_split->display_links(MAX_DISPLAY_NEWSDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul></td>
	</tr>
</table>
	</div></div>
<?php
}
?>




<?php
$list_box_contents = array();
$list_box_contents[] = array('params' => '');
$cur_row = sizeof($list_box_contents) - 1;

for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
	case 'NEWSDESK_STATUS':
		$lc_text = TABLE_HEADING_STATUS;
		$lc_align = '';
		break;
	case 'NEWSDESK_DATE_AVAILABLE':
		$lc_text = TABLE_HEADING_DATE_AVAILABLE;
		$lc_align = '';
		break;
	case 'NEWSDESK_ARTICLE_NAME':
		$lc_text = TABLE_HEADING_ARTICLE_NAME;
		$lc_align = '';
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT':
		$lc_text = TABLE_HEADING_ARTICLE_SHORTTEXT;
		$lc_align = '';
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION':
		$lc_text = TABLE_HEADING_ARTICLE_DESCRIPTION;
		$lc_align = '';
		break;
	case 'NEWSDESK_ARTICLE_URL':
		$lc_text = TABLE_HEADING_ARTRICLE_URL;
		$lc_align = '';
		break;
	case 'NEWSDESK_ARTICLE_URL_NAME':
		$lc_text = TABLE_HEADING_ARTRICLE_URL_NAME;
		$lc_align = '';
		break;
	case 'NEWSDESK_IMAGE':
		$lc_text = TABLE_HEADING_IMAGE;
		$lc_align = '';
		break;
	case 'NEWSDESK_IMAGE_TWO':
		$lc_text = TABLE_HEADING_IMAGE;
		$lc_align = '';
		break;
	case 'NEWSDESK_IMAGE_THREE':
		$lc_text = TABLE_HEADING_IMAGE;
		$lc_align = '';
		break;
}

if ($column_list[$col] != 'NEWSDESK_ARTICLE_URL' && $column_list[$col] != 'NEWSDESK_ARTICLE_URL_NAME' && $column_list[$col] != 'NEWSDESK_IMAGE' && $column_list[$col] != 'NEWSDESK_IMAGE_TWO' && $column_list[$col] != 'NEWSDESK_IMAGE_THREE') // turn off links
	$lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
	$list_box_contents[$cur_row][] = array(
		'align' => $lc_align,
		'params' => '',
		'text'  => ""
		);
}

if ($listing_numrows > 0) {
	$number_of_products = '0';
	$listing_query = tep_db_query($listing_split->sql_query);
	while ($listing_values = tep_db_fetch_array($listing_query)) {
		$number_of_products++;

/*if ($listing_split->number_of_rows > 0) {
	$rows = 0;
  $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;*/

if ( ($number_of_products/2) == floor($number_of_products/2) ) {
			$list_box_contents[] = array('params' => '');
		} else {
			$list_box_contents[] = array('params' => '');
		}

		$cur_row = sizeof($list_box_contents) - 1;
		$cl_size = sizeof($column_list);
		for ($col=0; $col<$cl_size; $col++) {
			$lc_align = '';
			switch ($column_list[$col]) {
		case 'NEWSDESK_STATUS':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_status'] . '</a>&nbsp;<br />
';
				break;
		
		
		case 'NEWSDESK_ARTICLE_NAME':
			$lc_text = '<h3><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_article_name'] . '</a></h3>
';
			break;
		case 'NEWSDESK_ARTICLE_SHORTTEXT':
			$lc_text = '' . $listing_values['newsdesk_article_shorttext'] . '<br />
<a class="readon" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">Read More >></a><div class="article_seperator"></div><br />

';
			break;
		case 'NEWSDESK_ARTICLE_DESCRIPTION':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_article_description'] . '</a><br />
';
			break;
		case 'NEWSDESK_ARTICLE_URL':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_article_url'] . '</a><br />
';
			break;
		case 'NEWSDESK_ARTICLE_URL_NAME':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_article_url_name'] . '</a>&nbsp;';
			break;
		case 'NEWSDESK_IMAGE':
if (($listing_values['newsdesk_image'] != '') && ($listing_values['newsdesk_image'] != 'Array')) {
			$lc_align = '';
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing_values['newsdesk_image'], 
			$listing_values['newsdesk_article_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>&nbsp;' . 
			'<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . $listing_values['newsdesk_article_url'] . '</a>&nbsp;'
			;
} else {
			$lc_align = 'center';
			$lc_text = '&nbsp;';
}
			break;
		case 'NEWSDESK_IMAGE_TWO':
if (($listing_values['newsdesk_image_two'] != '') && ($listing_values['newsdesk_image_two'] != 'Array')) {
			$lc_align = 'center';
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing_values['newsdesk_image_two'], 
			$listing_values['newsdesk_article_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
} else {
			$lc_align = 'center';
			$lc_text = '&nbsp;';
}
			break;
		case 'NEWSDESK_IMAGE_THREE':
if (($listing_values['newsdesk_image_three'] != '') && ($listing_values['newsdesk_image_three'] != 'Array')) {
			$lc_align = 'center';
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, ($newsPath ? 'newsPath=' . $newsPath . '&' : '') 
			. 'newsdesk_id=' . $listing_values['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing_values['newsdesk_image_three'], 
			$listing_values['newsdesk_article_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
} else {
			$lc_align = 'center';
			$lc_text = '&nbsp;';
}
			break;

			}

			$list_box_contents[$cur_row][] = array(
				'align' => $lc_align,
				'params' => '',
				'text'  => $lc_text
				);

		}
	}

	new tableBox($list_box_contents, true);

	echo '' . "\n";
	echo '' . "\n";
	} else {
?>

	<h4><?php echo TEXT_NO_ARTICLES ?>&nbsp;</h4>
	 

<?php
}
?>

<hr />

<?php
 if ($listing_numrows > 0 && (NEWSDESK_PREV_NEXT_BAR_LOCATION == '1' || NEWSDESK_PREV_NEXT_BAR_LOCATION == '3')) {
 // if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>

	 
<div id="module-product">
<div class="sort">
<table width="100%" >
	<tr>
		<td width="60%"><b><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></b></td>
		<td  width="40%"><ul><?php echo '' . $listing_split->display_links(MAX_DISPLAY_NEWSDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul></td>
	</tr>
</table>
	</div></div>

<?php }
?>

 </td></tr></table>


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