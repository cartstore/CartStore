<?php

/*

  $Id: easypopulate_functions.php,v 2.76a 2006/10/16 22:50:52 surfalot Exp $



  Designed for CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/

if (!function_exists(tep_get_uploaded_file)){

function tep_get_uploaded_file($filename) {

	if (isset($_FILES[$filename])) {

		$uploaded_file = array('name' => $_FILES[$filename]['name'],

		'type' => $_FILES[$filename]['type'],

		'size' => $_FILES[$filename]['size'],

		'tmp_name' => $_FILES[$filename]['tmp_name']);

	} elseif (isset($GLOBALS['_FILES'][$filename])) {

		global $_FILES;



		$uploaded_file = array('name' => $_FILES[$filename]['name'],

		'type' => $_FILES[$filename]['type'],

		'size' => $_FILES[$filename]['size'],

		'tmp_name' => $_FILES[$filename]['tmp_name']);

	} else {

		$uploaded_file = array('name' => $GLOBALS[$filename . '_name'],

		'type' => $GLOBALS[$filename . '_type'],

		'size' => $GLOBALS[$filename . '_size'],

		'tmp_name' => $GLOBALS[$filename]);

	}



return $uploaded_file;

}

}



// the $filename parameter is an array with the following elements:

// name, type, size, tmp_name

function tep_copy_uploaded_file($filename, $target) {

	if (substr($target, -1) != '/') $target .= '/';



	$target .= $filename['name'];



	move_uploaded_file($filename['tmp_name'], $target);

}



////

// Recursively go through the categories and retreive all sub-categories IDs

// TABLES: categories

if (!function_exists(tep_get_sub_categories)) {

  function tep_get_sub_categories(&$categories, $categories_id) {

    $sub_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");

    while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {

      if ($sub_categories['categories_id'] == 0) return true;

      $categories[sizeof($categories)] = $sub_categories['categories_id'];

      if ($sub_categories['categories_id'] != $categories_id) {

        tep_get_sub_categories($categories, $sub_categories['categories_id']);

      }

    }

  }

}



if (!function_exists(tep_get_tax_class_rate)){

function tep_get_tax_class_rate($tax_class_id) {

	$tax_multiplier = 0;

	$tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " WHERE  tax_class_id = '" . $tax_class_id . "' GROUP BY tax_priority");

	if (tep_db_num_rows($tax_query)) {

		while ($tax = tep_db_fetch_array($tax_query)) {

			$tax_multiplier += $tax['tax_rate'];

		}

	}

	return $tax_multiplier;

}

}



if (!function_exists(tep_get_tax_title_class_id)){

function tep_get_tax_title_class_id($tax_class_title) {

	$classes_query = tep_db_query("select tax_class_id from " . TABLE_TAX_CLASS . " WHERE tax_class_title = '" . $tax_class_title . "'" );

	$tax_class_array = tep_db_fetch_array($classes_query);

	$tax_class_id = $tax_class_array['tax_class_id'];

	return $tax_class_id ;

}

}



if (!function_exists(print_el)){

function print_el( $item2 ) {

	echo " | " . substr(strip_tags($item2), 0, 10);

}

}



if (!function_exists(print_el1)){

function print_el1( $item2 ) {

	echo sprintf("| %'.4s ", substr(strip_tags($item2), 0, 80));

}

}



?>