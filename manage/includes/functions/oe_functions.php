<?php
/*
  $Id: oe_functions.php, v1.0 2006/10/19 08:32:47 ams Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/


 // Function    : tep_hml_no_oe_quote
  // Arguments   : string	any string
  // Return      : strips apostrophes from strings
  //Used with Order Editor to workaround problem with apostrophes, double quotes, and line breaks
  
  function tep_html_no_oe_quote($string) {
  $string=str_replace('&#39;', '', $string);
  $string=str_replace("'", "", $string);
  $string=str_replace('"', '', $string);
  $string=preg_replace("/\\r\\n|\\n|\\r/", "<BR>", $string); 
  return $string;
	
  }
///end function tep_html_no_oe_quote

////

/// Begin mods for Order Editor
// Return the tax description for a zone / class
// TABLES: tax_rates;

  function tep_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return ENTRY_TAX;
    }
  }

////

// Function    : tep_get_country_id
  // Arguments   : country_name		country name string
  // Return      : country_id
  // Description : Function to retrieve the country_id based on the country's name
  function tep_get_country_id($country_name) {
    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");
    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }

   // Function    : tep_get_zone_id
  // Arguments   : country_id		country id string    zone_name		state/province name
  // Return      : zone_id
  // Description : Function to retrieve the zone_id based on the zone's name
  function tep_get_zone_id($country_id, $zone_name) {
    $zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and (zone_name = '" . $zone_name . "' OR zone_code = '" . $zone_name . "')");
    if (!tep_db_num_rows($zone_id_query)) {
      return 0;
    }
    else {
      $zone_id_row = tep_db_fetch_array($zone_id_query);
      return $zone_id_row['zone_id'];
    }
  }
  
// Function    : tep_html_quotes
  // Arguments   : string	any string
  // Return      : string with single quotes converted to html equivalent
  // Description : Function to change quotes to HTML equivalents for form inputs.
  function tep_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }


  ///this is a modified version of tep_js_zone_list designed for Order Editor
  ///originally written by Josh Dechant for the MOECTOE suite
  ///tweaked by djmonkey1 for Order Editor 2.7 and up
  function tep_oe_js_zone_list($country, $form, $field, $id, $id2) {
    $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    $output_string = '';
    while ($countries = tep_db_fetch_array($countries_query)) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      }

      $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");

      $num_state = 1;
      while ($states = tep_db_fetch_array($states_query)) {
        if ($num_state == '1') $output_string .= '    ' . $form . '[' . $field . '].options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        $output_string .= '    ' . $form . '[' . $field . '].options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $output_string .= '    setStateVisibility(' . $id . ', "hidden", ' . $id2 . ');' . "\n";
      $num_country++;
    }
    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '[' . $field . '].options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '    setStateVisibility(' . $id . ', "visible", ' . $id2 . ');' . "\n" . 
                      '  }' . "\n";

    return $output_string;
  }
  
  		//This function is written by Drako and is used to get the stock of a item knowing the product_ID
 	  function tep_get_products_inventory_stock($product_id) {
    		$product_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    		$product = tep_db_fetch_array($product_query);

    		return $product['products_quantity'];
  	  }
	  //END
	  
	  ////
// Output a selection field - alias function for tep_draw_oe_checkbox_field() and tep_draw_oe_radio_field()
//I had to draw up custom functions in order to pass parameters with checkbox fields, maybe radio fields too someday
  function tep_draw_oe_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameters = '') {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
	
	if (tep_not_null($parameters)) $selection .=  ' ' . $parameters;


    if ( ($checked == true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ($GLOBALS[$name] == 'on')) || (isset($value) && isset($GLOBALS[$name]) && (stripslashes($GLOBALS[$name]) == $value)) || (tep_not_null($value) && tep_not_null($compare) && ($value == $compare)) ) {
      $selection .= ' CHECKED';
    }

    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_oe_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
    return tep_draw_oe_selection_field($name, 'checkbox', $value, $checked, $compare, $parameters);
  }

////
// Output a form radio field
  function tep_draw_oe_radio_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
    return tep_draw_oe_selection_field($name, 'radio', $value, $checked, $compare, $parameters);
  }

////

/////end
?>