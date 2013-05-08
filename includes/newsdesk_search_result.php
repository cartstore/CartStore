<?php

require('includes/application_top.php');
require('includes/functions/newsdesk_general.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_SEARCH_RESULT);

// set application wide parameters --- this query set is for NewsDesk
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

$error = 0; // reset error flag to false
$errorno = 0;

if ( ($_GET['keywords'] == "" || strlen($_GET['keywords']) < 1) &&
	($_GET['dfrom'] == ""    || $_GET['dfrom'] == DOB_FORMAT_STRING || strlen($_GET['dfrom']) < 1 ) &&
	($_GET['dto'] == ""      || $_GET['dto']   == DOB_FORMAT_STRING || strlen($_GET['dto']) < 1) &&
	($_GET['pfrom'] == ""    || strlen($_GET['pfrom']) < 1) &&
	($_GET['pto'] == ""      || strlen($_GET['pto']) < 1) ) {
		$errorno += 1;
		$error = 1;
}

if ($_GET['dfrom'] == DOB_FORMAT_STRING)
	$dfrom_to_check = "";
else
	$dfrom_to_check = $_GET['dfrom'];

if ($_GET['dto'] == DOB_FORMAT_STRING)
	$dto_to_check = "";
else
	$dto_to_check = $_GET['dto'];

if (strlen($dfrom_to_check) > 0) {
	if (!tep_checkdate($dfrom_to_check, DOB_FORMAT_STRING, $dfrom_array)) {
		$errorno += 10;
	$error = 1;
	}
}

if (strlen($dto_to_check) > 0) {
	if (!tep_checkdate($dto_to_check, DOB_FORMAT_STRING, $dto_array)) {
		$errorno += 100;
		$error = 1;
	}
}

if (strlen($dfrom_to_check) > 0 && !(($errorno & 10) == 10) &&
	strlen($dto_to_check) > 0 && !(($errorno & 100) == 100)) {
	if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
		$errorno += 1000;
		$error = 1;
	}
}

if (strlen($_GET['pfrom']) > 0) {
	$pfrom_to_check = $_GET['pfrom'];
	if (!settype($pfrom_to_check, "double")) {
		$errorno += 10000;
		$error = 1;
	}
}

if (strlen($_GET['pto']) > 0) {
	$pto_to_check = $_GET['pto'];
	if (!settype($pto_to_check, "double")) {
		$errorno += 100000;
		$error = 1;
	}
}

if (strlen($_GET['pfrom']) > 0 && !(($errorno & 10000) == 10000) &&
	strlen($_GET['pto']) > 0 && !(($errorno & 100000) == 100000)) {
	if ($pfrom_to_check > $pto_to_check) {
		$errorno += 1000000;
		$error = 1;
	}
}

if (strlen($_GET['keywords']) > 0) {
	if (!tep_parse_search_string(stripslashes($_GET['keywords']), $search_keywords)) {
		$errorno += 10000000;
		$error = 1;
	}
}


//FILENAME_NEWSDESK_SEARCH
if ($error == 1) {
	tep_redirect(tep_href_link(FILENAME_NEWSDESK_INDEX, 'errorno=' . $errorno . '&' . tep_get_all_get_params(array('x', 'y')), 'NONSSL'));
} else {

	$breadcrumb->add(NAVBAR_HOME, tep_href_link(FILENAME_NEWSDESK_INDEX, '', 'NONSSL'));
	$breadcrumb->add(NAVBAR_TITLE2, tep_href_link(FILENAME_NEWSDESK_SEARCH_RESULT, 'keywords=' . $_GET['keywords']
	. '&search_in_description=' . $_GET['search_in_description'] . '&categories_id=' . $_GET['categories_id']
	. '&inc_subcat=' . $_GET['inc_subcat'] . '&pfrom='
	. $_GET['pfrom'] . '&pto=' . $_GET['pto'] . '&dfrom=' . $_GET['dfrom'] . '&dto=' . $_GET['dto']));
?>


<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
	require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
	<title><?php echo TITLE ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>
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
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
		<td class="pageHeading" align="right">
<?php echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
		<td>


<?php
// create column list
$define_list = array(
	'NEWSDESK_DATE_AVAILABLE' => NEWSDESK_DATE_AVAILABLE,
	'NEWSDESK_ARTICLE_DESCRIPTION' => NEWSDESK_ARTICLE_DESCRIPTION,
	'NEWSDESK_ARTICLE_SHORTTEXT' => NEWSDESK_ARTICLE_SHORTTEXT,
	'NEWSDESK_ARTICLE_NAME' => NEWSDESK_ARTICLE_NAME,
);

asort($define_list);

$column_list = array();
reset($define_list);
while (list($column, $value) = each($define_list)) {
	if ($value) $column_list[] = $column;
}

$select_column_list = '';

for ($col=0; $col<sizeof($column_list); $col++) {
	if ( ($column_list[$col] == 'NEWSDESK_DATE_AVAILABLE') || ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') ) {
		continue;
	}

	if ($select_column_list != '') {
		$select_column_list .= ', ';
	}

	switch ($column_list[$col]) {
	case 'NEWSDESK_DATE_AVAILABLE': $select_column_list .= 'p.newsdesk_date_added';
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $select_column_list .= 'pd.newsdesk_article_description';
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT': $select_column_list .= 'pd.newsdesk_article_shorttext';
		break;
	case 'NEWSDESK_ARTICLE_NAME': $select_column_list .= 'pd.newsdesk_article_name';
		break;
	}
}

if ($select_column_list != '') {
	$select_column_list .= ', ';
}

$select_str = "select distinct " . $select_column_list . " p.newsdesk_id, p.newsdesk_date_added, pd.newsdesk_article_name,
pd.newsdesk_article_description, pd.newsdesk_article_shorttext ";

$from_str = "from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_TO_CATEGORIES . " p2c";

$where_str = " where p.newsdesk_status = '1' and p.newsdesk_id = pd.newsdesk_id and pd.language_id = '" . $languages_id . "' and
p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = c.categories_id ";

if ($_GET['categories_id']) {
	if ($_GET['inc_subcat'] == "1") {
		$subcategories_array = array();
		newsdesk_get_subcategories($subcategories_array, $_GET['categories_id']);
		$where_str .= " and p2c.newsdesk_id = p.newsdesk_id and p2c.newsdesk_id = pd.newsdesk_id and (p2c.categories_id = '"
		. $_GET['categories_id'] . "'";

		for ($i=0; $i<sizeof($subcategories_array); $i++ ) {
			$where_str .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
		}
		$where_str .= ")";
	} else {
		$where_str .= " and p2c.newsdesk_id = p.newsdesk_id and p2c.newsdesk_id = pd.newsdesk_id and pd.language_id = '"
		. $languages_id . "' and p2c.categories_id = '" . $_GET['categories_id'] . "'";
	}
}

if ($_GET['keywords']) {
	if (tep_parse_search_string( StripSlashes($_GET['keywords']), $search_keywords)) {
		$where_str .= " and (";
		for ($i=0; $i<sizeof($search_keywords); $i++ ) {
			switch ($search_keywords[$i]) {
				case '(':
				case ')':
				case 'and':
				case 'or':
				$where_str .= " " . $search_keywords[$i] . " ";
			break;
			default:
$where_str .= "
(pd.newsdesk_article_name like '%" . AddSlashes($search_keywords[$i]) . "%' or
pd.newsdesk_article_shorttext like '%" . AddSlashes($search_keywords[$i]) . "%' or
pd.newsdesk_article_description like '%" . AddSlashes($search_keywords[$i]) . "%'";
				if ($_GET['search_in_description']) $where_str .= "
				or pd.newsdesk_article_description like '%" . AddSlashes($search_keywords[$i]) . "%'";
				$where_str .= ')';
			break;
			}
		}
		$where_str .= " )";
	}
}

if ($_GET['dfrom'] && $_GET['dfrom'] != DOB_FORMAT_STRING) {
	$where_str .= " and p.newsdesk_date_added >= '" . tep_date_raw($dfrom_to_check) . "'";
}

if ($_GET['dto'] && $_GET['dto'] != DOB_FORMAT_STRING) {
	$where_str .= " and p.newsdesk_date_added <= '" . tep_date_raw($dto_to_check) . "'";
}


if ( (!$_GET['sort']) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'],0,1) > sizeof($column_list)) ) {
	for ($col=0; $col<sizeof($column_list); $col++) {
		if ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') {
			$_GET['sort'] = $col+1 . 'a';
			$order_str .= " order by pd.newsdesk_article_name";
			break;
		}
	}
} else {
	$sort_col = substr($_GET['sort'], 0, 1);
	$sort_order = substr($_GET['sort'], 1);
	$order_str .= ' order by ';
	switch ($column_list[$sort_col-1]) {
	case 'NEWSDESK_DATE_AVAILABLE': $order_str .= "p.newsdesk_date_added " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	case 'NEWSDESK_ARTICLE_NAME': $order_str .= "pd.newsdesk_article_name " . ($sort_order == 'd' ? "desc" : "");
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT': $order_str .= "pd.newsdesk_article_shorttext " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $order_str .= "pd.newsdesk_article_description " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	}
}

$listing_sql = $select_str . $from_str . $where_str . $order_str;

require(DIR_WS_MODULES . FILENAME_NEWSDESK_LISTING);
?>


		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
	<!--	<td class="main">
<?php
//FILENAME_NEWSDESK_SEARCH
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, tep_get_all_get_params(array('sort', 'page', 'x', 'y')), 'NONSSL', true, false)
. '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>-->
<?php
// BOF Wolfen added code for back button
echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=1&') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>' . tep_draw_separator('pixel_trans.gif', '10', '1') . '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
// EOF Wolfen added code for back button
?>
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

<?php
}

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:		NewsDesk
	version:		1.48.2
	date:			17-07-2004 (dd/mm/yyyy)
	author:			Wolfen aka 241
	web site:		www..com

*/
?>
