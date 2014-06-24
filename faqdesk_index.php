<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_INDEX);

// set application wide parameters --- this query set is for FAQDesk
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
}
// calculate category path
if ($_GET['faqPath']) {
    $faqPath = $_GET['faqPath'];
} elseif ($_GET['faqdesk_id']) {
    $faqPath = faqdesk_get_product_path($_GET['faqdesk_id']);
} else {
    $faqPath = '';
}
// caluculate something ??? like what?  current category??
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

if (isset($faqPath_array)) {
    $n = sizeof($faqPath_array);
    for ($i = 0; $i < $n; $i++) {
        $categories_query = tep_db_query(
        "select categories_name from " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $faqPath_array[$i]
        . "' and language_id='" . $languages_id . "'"
        );
        if (tep_db_num_rows($categories_query) > 0) {
            $categories = tep_db_fetch_array($categories_query);
            $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='
            . implode('_', array_slice($faqPath_array, 0, ($i+1)))));
        } else {
            break;
        }
    }
/*
  if ($_GET['faqPath']) {
    $categories_query = tep_db_query("select categories_name from " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $_GET['categories_id'] . "'");
    $categories = tep_db_fetch_array($categories_query);
    $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=' . $faqPath . '&categories_id=' . $_GET['categories_id']));
//            $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='
//            . implode('_', array_slice($faqPath_array, 0, ($i+1)))));
  }
*/
}

// the following faqPath references come from application_top.php
$category_depth = 'top';
if ($faqPath) {
///*
// IF this area is included problems occur when trying to view unpopulated catagories
// OR!!! is this not a but???
// Well which the @!#p$@ is it?  Regular products shows the catagory while the below won't @!#@&@

    $categories_products_query = tep_db_query(
    "select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where categories_id = '" . $current_category_id . "'"
    );

    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
        $category_depth = 'products'; // display products
    } else {
    $category_parent_query = tep_db_query(
    "select count(*) as total from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $current_category_id . "'"
    );

    $category_parent = tep_db_fetch_array($category_parent_query);
        if ($category_parent['total'] > 0) {
            $category_depth = 'nested'; // navigate through the categories
        } else {
            $category_depth = 'products'; // category has no products, but display the 'no products' message
        }
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------------
// Output a form pull down menu
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function faqdesk_show_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {

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

    if ($required) $field .= FAQ_TEXT_FIELD_REQUIRED;

return $field;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
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
<table border="0" width="100%" cellspacing="1" cellpadding="1">
    <tr>
	<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
</table>
      </td>
<!-- body_text //-->
        <td width="100%" valign="top">

<?php
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// let's pick up information for the top area of the category listings
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
$category_query = tep_db_query(
"select cd.categories_name, c.categories_image, cd.categories_heading_title, cd.categories_description from "
. TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.categories_id = '"
. $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'"
);
$category = tep_db_fetch_array($category_query);
?>

<?php
if ($category_depth == 'nested') {
} elseif ($category_depth == 'products') {
	include(FILENAME_FAQDESK_SHOW);
}
echo tep_draw_separator('pixel_trans.gif', '1', '30');
include(FILENAME_FAQDESK_STICKY);
// ------------------------------------------------------------------------------------------------------------------------------------------
// Let's close up the middle area and the add the remaining html for the page
// ------------------------------------------------------------------------------------------------------------------------------------------
?>
<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="pageHeading" width="">
<?php
if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
	echo $category['categories_heading_title'];
} else {
	echo $category['categories_name'];
}
?>		</td>
		<td class="pageHeading" align="right" width="">
<?php
if (($category['categories_image'] == 'NULL') || ($category['categories_image'] == '')) {
echo tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
?>		</td>
	</tr>
<?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
	<tr>
		<td class="cat_description" align="left" colspan="2"><?php echo $category['categories_description']; ?>		</td>
	</tr>
<?php } ?>
</table>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
<?php
if ($faqPath && preg_match('/_/', $faqPath)) {
// check to see if there are deeper categories within the current category
	$category_links = array_reverse($faqPath_array);
	for($i=0; $i<sizeof($category_links); $i++) {

$categories_query = tep_db_query(
"select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $category_links[$i] . "'
and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name"
);

		if (tep_db_num_rows($categories_query) < 1) {
			// do nothing, go through the loop
	//	} else {
			} // Wolfen added } instead of the one after break;
			break; // we've found the deepest category the customer is in
	//	}
	}
} else {

$categories_query = tep_db_query(
"select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, "
. TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $current_category_id . "'
and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name"
);

}
// BOF: Modified to fix image display problem

//if (($categories['categories_image'] == 'NULL') || ($categories['categories_image'] == '')) {

if (!tep_not_null($categories['categories_image'])) {

// EOF: Modified to fix image display problem

echo tep_draw_separator('pixel_trans.gif', '1', '1');

} else {

echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);

}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
	$faqPath_new = faqdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
	echo '
		<td align="left" class="faqcat" style="width: ' . $width . '" valign="top"> <a href="'
		. tep_href_link(FILENAME_FAQDESK_INDEX, $faqPath_new, 'NONSSL') . '">';
// BOF: Modified to fix image display problem

//if (($categories['categories_image'] = 'NULL') or ($categories['categories_image'] = '')) {

if (!tep_not_null($category['categories_image'])) {

// EOF: Modified to fix image display problem

echo tep_draw_separator('pixel_trans.gif', '1', '1');

} else {

echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);

}
	echo '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>
</table>



</td>
<!-- body_text_eof //-->
		    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
	author:				Carsten aka moyashi
	web site:			www..com
	modified code by:		Wolfen aka 241
*/
?>
