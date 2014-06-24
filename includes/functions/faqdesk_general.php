<?php

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Generate a path to categories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function faqdesk_get_path($current_category_id = '') {
global $faqPath_array;

if ($current_category_id) {
	$cp_size = sizeof($faqPath_array);
	if ($cp_size == 0) {
		$faqPath_new = $current_category_id;
	} else {
		$faqPath_new = '';
		$last_category_query = tep_db_query("select parent_id from " . TABLE_FAQDESK_CATEGORIES . " where categories_id = '" . $faqPath_array[($cp_size-1)] . "'");
		$last_category = tep_db_fetch_array($last_category_query);
		$current_category_query = tep_db_query("select parent_id from " . TABLE_FAQDESK_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
		$current_category = tep_db_fetch_array($current_category_query);
		if ($last_category['parent_id'] == $current_category['parent_id']) {
			for ($i=0; $i<($cp_size-1); $i++) {
				$faqPath_new .= '_' . $faqPath_array[$i];
			}
		} else {
			for ($i=0; $i<$cp_size; $i++) {
				$faqPath_new .= '_' . $faqPath_array[$i];
			}
		}
		$faqPath_new .= '_' . $current_category_id;
		if (substr($faqPath_new, 0, 1) == '_') {
			$faqPath_new = substr($faqPath_new, 1);
		}
	}
} else {
	$faqPath_new = implode('_', $faqPath_array);
}

return 'faqPath=' . $faqPath_new;

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Parse and secure the faqPath parameter values
function faqdesk_parse_category_path($faqPath) {
// make sure the category IDs are integers
$faqPath_array = array_map('tep_string_to_int', explode('_', $faqPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
$tmp_array = array();
$n = sizeof($faqPath_array);
for ($i=0; $i<$n; $i++) {
	if (!in_array($faqPath_array[$i], $tmp_array)) {
		$tmp_array[] = $faqPath_array[$i];
	}
}

return $tmp_array;

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Return true if the category has subcategories
// TABLES: categories
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function faqdesk_has_category_subcategories($category_id) {
$child_category_query = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
$child_category = tep_db_fetch_array($child_category_query);

if ($child_category['count'] > 0) {
	return true;
} else {
	return false;
}

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Construct a category path to the product
// TABLES: products_to_categories
function faqdesk_get_product_path($faqdesk_id) {
$faqPath = '';

$cat_count_sql = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . $faqdesk_id . "'");
$cat_count_data = tep_db_fetch_array($cat_count_sql);

if ($cat_count_data['count'] == 1) {
	$categories = array();

	$cat_id_sql = tep_db_query("select categories_id from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . $faqdesk_id . "'");
	$cat_id_data = tep_db_fetch_array($cat_id_sql);
	faqdesk_get_parent_categories($categories, $cat_id_data['categories_id']);

	$size = sizeof($categories)-1;
	for ($i = $size; $i >= 0; $i--) {
		if ($faqPath != '') $faqPath .= '_';
			$faqPath .= $categories[$i];
	}
	if ($faqPath != '') $faqPath .= '_';
		$faqPath .= $cat_id_data['categories_id'];
}

return $faqPath;

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
function faqdesk_get_parent_categories(&$categories, $categories_id) {
$parent_categories_query = tep_db_query("select parent_id from " . TABLE_FAQDESK_CATEGORIES . " where categories_id = '" . $categories_id . "'");

while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
	if ($parent_categories['parent_id'] == 0) return true;
		$categories[sizeof($categories)] = $parent_categories['parent_id'];
		if ($parent_categories['parent_id'] != $categories_id) {
			faqdesk_get_parent_categories($categories, $parent_categories['parent_id']);
		}
	}
}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------
function faqdesk_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
global $languages_id;

$parent_id = tep_db_prepare_input($parent_id);

if (!is_array($categories_array)) $categories_array = array();

$categories_query = tep_db_query(
"select c.categories_id, cd.categories_name from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " 
cd where c.catagory_status = '1' and parent_id = '" . tep_db_input($parent_id) . "' and c.categories_id = cd.categories_id and cd.language_id = '" 
. $languages_id . "' order by sort_order, cd.categories_name"
);

while ($categories = tep_db_fetch_array($categories_query)) {
	$categories_array[] = array(
		'id' => $categories['categories_id'],
		'text' => $indent . $categories['categories_name']
	);

	if ($categories['categories_id'] != $parent_id) {
		$categories_array = faqdesk_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
	}
}

return $categories_array;
}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------
// Return all subcategory IDs
// TABLES: categories
function faqdesk_get_subcategories(&$subcategories_array, $parent_id = 0) {
$subcategories_query = tep_db_query("select categories_id from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $parent_id . "'");

while ($subcategories = tep_db_fetch_array($subcategories_query)) {
	$subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
	if ($subcategories['categories_id'] != $parent_id) {
		faqdesk_get_subcategories($subcategories_array, $subcategories['categories_id']);
	}
}

}
// -------------------------------------------------------------------------------------------------------------------------------------------------------------


?>

<?php
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:			FAQDesk
	version:			1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
