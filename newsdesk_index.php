<?php
require('includes/application_top.php');
require('includes/functions/newsdesk_general.php');

// set application wide parameters --- this query set is for NewsDesk
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
// calculate category path
if ($_GET['newsPath']) {
	$newsPath = $_GET['newsPath'];
} elseif ($_GET['newsdesk_id'] && !$_GET['manufacturers_id']) {
	$newsPath = newsdesk_get_product_path($_GET['newsdesk_id']);
} else {
	$newsPath = '';
}

if (strlen($newsPath) > 0) {
	$newsPath_array = newsdesk_parse_category_path($newsPath);
	$newsPath = implode('_', $newsPath_array);
	$current_category_id = $newsPath_array[(sizeof($newsPath_array)-1)];
} else {
	$current_category_id = 0;
}

if (isset($newsPath_array)) {
	$n = sizeof($newsPath_array);
	for ($i = 0; $i < $n; $i++) {
		$categories_query = tep_db_query(
		"select categories_name from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $newsPath_array[$i]
		. "' and language_id='" . $languages_id . "'"
		);
		if (tep_db_num_rows($categories_query) > 0) {
			$categories = tep_db_fetch_array($categories_query);
			$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath='
			. implode('_', array_slice($newsPath_array, 0, ($i+1)))));
		} else {
			break;
		}
	}
}


// the following newsPath references come from application_top.php
//$category_depth = 'top';
$category_depth = 'products';
if ($newsPath) {
	$category_parent_query = tep_db_query(
	"select count(*) as total from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . $current_category_id . "'"
	);

	$category_parent = tep_db_fetch_array($category_parent_query);
	if ($category_parent['total'] > 0) {
		$category_depth = 'nested'; // navigate through the categories
	} else {
		$category_depth = 'products'; // category has no products, but display the 'no products' message
	}
}
//}  // I lost track to what loop this is closing ... ugh I hate when this happens
// ------------------------------------------------------------------------------------------------------------------------------------------
// Output a form pull down menu
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function newsdesk_show_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {

$field = '<select name="' . $name . '"';
if ($params) $field .= ' ' . $params;
	$field .= '>';
	for ($i=0; $i<sizeof($values); $i++) {
		$field .= '<option value="' . $values[$i]['id'] . '"';
		if ( ($GLOBALS[$name] == $values[$i]['id']) || ($default == $values[$i]['id']) ) {
			$field .= ' SELECTED';
		}
		$field .= '>' . $values[$i]['text'] . '</option>';
	}
	$field .= '</select>';
	$field .= tep_hide_session_id();

	if ($required) $field .= NEWS_TEXT_FIELD_REQUIRED;

return $field;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_INDEX);
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: Header Tag Controller v2.6.3
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE; ?></title>
<?php
}
// EOF: Header Tag Controller v2.6.3
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function check_input(field_name, field_size, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == '' || field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_form(form_name) {
  if (submitted == true) {
    alert("Press Search Button To Continue");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Error\n";

  check_input("keywords", 1, "Please Enter Any Keywords For Search");


    if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<script>
function em() {
document.newsdesk_index.howmuch.value = ""
}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
<!-- body_text //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		    <td>
<?php
if ($category_depth == 'nested') {
	$category_query = tep_db_query(
	"select cd.categories_name, c.categories_image from " . TABLE_NEWSDESK_CATEGORIES . " c, " .  newsdesk_categories_description .
	" cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '"
	. $languages_id . "'"
	);

	$category = tep_db_fetch_array($category_query);
?>



<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's make a drop down with all the categories and subcategories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$info_box_contents = array();
if (MAX_MANUFACTURERS_LIST < 2) {
	$cat_choose = array(array('id' => '', 'text' => NEWS_BOX_CATEGORIES_CHOOSE));
} else {
	$cat_choose = '';
}
// Below lines changed by Wolfen
$categories_array = newsdesk_get_categories($cat_choose);
for ($i=0; $i<sizeof($categories_array); $i++) {
	$path = "";
	$parent_categories = array();
//	newsdesk_get_parent_categories($parent_categories, $categories_array[$i]['id']);
//	for ($j = sizeof($parent_categories) - 1; $j>=0; $j--) {
//		$path = ($path == "") ? $parent_categories[$j] : ($path . "_" . $parent_categories[$j]);
//	}
	$categories_array[$i]['id'] = ($path == "") ? $categories_array[$i]['id'] : ($path . "_" . $categories_array[$i]['id']);
}
 // -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</form>

<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// show search box
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$hide = tep_hide_session_id();
$info_box_contents = array();
$info_box_contents[] = array(
	'form'  => '<form name="quick_find_news" method="get" action=" onSubmit="return check_form(quick_find_news);" ' . tep_href_link(FILENAME_NEWSDESK_SEARCH_RESULT, '', 'NONSSL', false) . '">',
	'align' => '',
	'text'  =>

$hide . '<input type="text" name="keywords" size="20" maxlength="30" value="'
. htmlspecialchars(StripSlashes(@$_GET["keywords"]))
. '" style="width: ' . (BOX_WIDTH-30) . 'px">&nbsp;' . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH)
);
  new infoBox($info_box_contents);
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>

</form>
<!-- Wolfen added code EOF -->
<h1><?php echo HEADING_TITLE; ?> yy</h1>




<?php
if ($newsPath && preg_match('/_/', $newsPath)) {
// check to see if there are deeper categories within the current category
	$category_links = array_reverse($newsPath_array);
	$size = sizeof($category_links);
	for($i=0; $i<$size; $i++) {
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from "
		. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $category_links[$i]
		. "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
		if (tep_db_num_rows($categories_query) < 1) {
// do nothing, go through the loop
		} else {
			break; // we've found the deepest category the customer is in
		}
	}
} else {
$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from "
. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '"
. $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id
. "' order by sort_order, cd.categories_name");
}

if (($categories['categories_image'] == 'NULL') or categories) {
} else {
$print_echo = tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT);
}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
	$newsPath_new = newsdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

	echo '
' . "\n";

	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '' . "\n";
		echo '' . "\n";
	}
}
?>


<?php
} elseif ($category_depth == 'products') {
// create column list
$define_list = array(
	'NEWSDESK_IMAGE' => NEWSDESK_IMAGE,
     'NEWSDESK_ARTICLE_NAME' => NEWSDESK_ARTICLE_NAME,
	'NEWSDESK_ARTICLE_SHORTTEXT' => NEWSDESK_ARTICLE_SHORTTEXT,

	'NEWSDESK_DATE_AVAILABLE' => NEWSDESK_DATE_AVAILABLE,

);

	asort($define_list);

	$column_list = array();
	reset($define_list);
	while (list($column, $value) = each($define_list)) {
		if ($value) $column_list[] = $column;
	}

	$select_column_list = '';

	$size = sizeof($column_list);
	for ($col=0; $col<$size; $col++) {
		if ( ($column_list[$col] == 'NEWSDESK_ARTICLE_SHORTTEXT') || ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') ) {
			continue;
		}

		if ($select_column_list != '') {
			$select_column_list .= ', ';
		}
		switch ($column_list[$col]) {
		case 'NEWSDESK_IMAGE': $select_column_list .= 'p.newsdesk_image';
			break;
		case 'NEWSDESK_IMAGE_TWO': $select_column_list .= 'p.newsdesk_image_two';
			break;
		case 'NEWSDESK_IMAGE_THREE': $select_column_list .= 'p.newsdesk_image_three';
			break;
		case 'NEWSDESK_ARTICLE_URL': $select_column_list .= 'pd.newsdesk_article_url';
			break;
		case 'NEWSDESK_ARTICLE_URL_NAME': $select_column_list .= 'pd.newsdesk_article_url_name';
			break;
		case 'NEWSDESK_ARTICLE_DESCRIPTION': $select_column_list .= 'pd.newsdesk_article_description';
			break;
			case 'NEWSDESK_ARTICLE_NAME': $select_column_list .= 'pd.newsdesk_article_name';
		case 'NEWSDESK_ARTICLE_SHORTTEXT': $select_column_list .= 'pd.newsdesk_article_shorttext';
			break;

			break;
		case 'NEWSDESK_DATE_AVAILABLE': $select_column_list .= 'p.newsdesk_date_added';
			break;
		case 'NEWSDESK_STATUS': $select_column_list .= 'p.newsdesk_status';
			break;
		}
	}

	if ($select_column_list != '') {
		$select_column_list .= ', ';
	}

// show the products of a specified manufacturer
	if (isset($_GET['manufacturers_id'])) {
       if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
		} else {
// We show them all
$listing_sql = "select " . $select_column_list . "  p.newsdesk_id, p.newsdesk_status, p.newsdesk_date_added, pd.newsdesk_article_name, pd.newsdesk_article_shorttext,
pd.newsdesk_article_description, pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, NULL) as final_price from (" . TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m) left join " . TABLE_SPECIALS . " s on p.newsdesk_id = s.products_id where p.newsdesk_status = '1' and pd.newsdesk_id = p.newsdesk_id and pd.language_id = '" . (int)$languages_id . "' and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
}



// We build the categories-dropdown
$filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c, "
. TABLE_NEWSDESK_CATEGORIES . " c, "
. TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd
where p.newsdesk_status = '1' and p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id
and cd.language_id = '" . $languages_id . "' order by cd.categories_name";

		} else {

// show the products in a given categorie

		if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {

// We are asked to show only specific catgeory
$listing_sql = "select " . $select_column_list . " p.newsdesk_id, p.newsdesk_status, p.newsdesk_date_added, pd.newsdesk_article_name, pd.newsdesk_article_shorttext,
pd.newsdesk_article_description, pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, NULL) as final_price from (" . TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_NEWSDESK_TO_CATEGORIES . " p2c) left join " . TABLE_SPECIALS . " s on p.newsdesk_id = s.products_id where p.newsdesk_status = '1' and m.manufacturers_id = '" . $_GET['filter_id'] . "' and p.newsdesk_id = p2c.newsdesk_id and pd.newsdesk_id = p2c.newsdesk_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";

		} else {

// We show them all
$listing_sql = "select " . $select_column_list . " p.newsdesk_id, p.newsdesk_status, p.newsdesk_date_added, pd.newsdesk_article_name, pd.newsdesk_article_shorttext,
pd.newsdesk_article_description, pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, NULL) as final_price from ((" . TABLE_NEWSDESK_DESCRIPTION . " pd, " . TABLE_NEWSDESK . " p) left join " . TABLE_MANUFACTURERS . " m on p.newsdesk_id = m.manufacturers_id, " . TABLE_NEWSDESK_TO_CATEGORIES . " p2c) left join " . TABLE_SPECIALS . " s on p.newsdesk_id = s.products_id where p.newsdesk_status = '1' and p.newsdesk_id = p2c.newsdesk_id and pd.newsdesk_id = p2c.newsdesk_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
}

// We build the manufacturers Dropdown
$filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from "
. TABLE_NEWSDESK . " p, "
. TABLE_NEWSDESK_TO_CATEGORIES . " p2c, "
. TABLE_MANUFACTURERS . " m
where p.newsdesk_status = '1' and p.newsdesk_id = m.manufacturers_id and p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = '"
. $current_category_id . "' order by m.manufacturers_name";

	}

	$cl_size = sizeof($column_list);
	if ( (!$_GET['sort']) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'],0,1) > $cl_size) ) {
		for ($col=0; $col<$cl_size; $col++) {
			if ($column_list[$col] == 'NEWSDESK_DATE_AVAILABLE') {
				$_GET['sort'] = $col+1 . 'd';
				$listing_sql .= " order by p.newsdesk_date_added desc";
				break;
			}
		}
	} else {
		$sort_col = substr($_GET['sort'], 0 , 1);
		$sort_order = substr($_GET['sort'], 1);
		$listing_sql .= ' order by ';
		switch ($column_list[$sort_col-1]) {
	case 'NEWSDESK_IMAGE': $listing_sql .= "p.newsdesk_image " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_IMAGE_TWO': $listing_sql .= "p.newsdesk_image_two " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_IMAGE_THREE': $listing_sql .= "p.newsdesk_image_three " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_URL': $listing_sql .= "pd.newsdesk_article_url " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_URL_NAME': $listing_sql .= "pd.newsdesk_article_url_name " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $listing_sql .= "pd.newsdesk_article_description " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;

		break;
	case 'NEWSDESK_ARTICLE_NAME': $listing_sql .= "pd.newsdesk_article_name " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
		case 'NEWSDESK_ARTICLE_SHORTTEXT': $listing_sql .= "pd.newsdesk_article_shorttext " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
	case 'NEWSDESK_DATE_AVAILABLE': $listing_sql .= "p.newsdesk_date_added " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
	case 'NEWSDESK_STATUS': $listing_sql .= "p.newsdesk_status " . ($sort_order == 'd' ? "desc" : "") . ", p.newsdesk_date_added";
		break;
		}
	}
?>


<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's make a drop down with all the categories and subcategories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$info_box_contents = array();
if (MAX_MANUFACTURERS_LIST < 2) {
	$cat_choose = array(array('id' => '', 'text' => NEWS_BOX_CATEGORIES_CHOOSE));
} else {
	$cat_choose = '';
}
// Below lines changed by Wolfen
$categories_array = newsdesk_get_categories($cat_choose);
for ($i=0; $i<sizeof($categories_array); $i++) {
	$path = "";
	$parent_categories = array();
//	newsdesk_get_parent_categories($parent_categories, $categories_array[$i]['id']);
//	for ($j = sizeof($parent_categories) - 1; $j>=0; $j--) {
//		$path = ($path == "") ? $parent_categories[$j] : ($path . "_" . $parent_categories[$j]);
//	}
	$categories_array[$i]['id'] = ($path == "") ? $categories_array[$i]['id'] : ($path . "_" . $categories_array[$i]['id']);
}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
		</form>

<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// show search box
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$hide = tep_hide_session_id();
$info_box_contents = array();

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
?>

</form>
<!-- Wolfen added code EOF -->











<?php include(DIR_WS_MODULES . FILENAME_NEWSDESK_LISTING); ?>

<?php
}
?>

<!-- body_text_eof //-->

<!-- right_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>

<!-- right_navigation_eof //-->

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