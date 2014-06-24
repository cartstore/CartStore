<?php

require_once('includes/application_top.php');

// require_once(DIR_FS_CATALOG . 'checkout_by_amazon/checkout_by_amazon_constants.php');

tep_db_connect() or die('Unable to connect to database server!');





// Returns the country_id for a country

// TABLES: countries

function tep_get_country_id($country_code) {

        $country_query = tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where (countries_iso_code_2 = '" . $country_code . "' or countries_iso_code_3 = '" . $country_code . "') ;");

        $result = tep_db_fetch_array($country_query);

        return $result['countries_id'];

}



// Returns the zone_id for a ZONE

// TABLES: zones

function tep_get_zone_id($zone_name) {

        $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where (zone_name = '" . $zone_name . "' or zone_code = '" . $zone_name . "') ;");

        $result = tep_db_fetch_array($zone_query);

        return $result['zone_id'];

}

// Returns the entry company given customers_id

// TABLES: zones

function tep_get_entry_company($customers_id) {

        $company_query = tep_db_query("select entry_company from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customers_id . "' ;");

        $result = tep_db_fetch_array($company_query);

        return $result['entry_company'];

}



// Returns the products_tax_class_id for a product

// TABLES: products

function tep_get_tax_class_id($products_id) {

        $tax_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "' ;");

        $result = tep_db_fetch_array($tax_query);

        return $result['products_tax_class_id'];



}

// Returns the products_class_id for a product

// TABLES: products_to_categories, categories_description, products

function tep_get_class_id($category_name, $title) {

        $classid_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id in (select distinct products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_name = \"" . $title . "\" and products_id in (select distinct products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id in (select distinct categories_id from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '" . $category_name . "' )))");

        $result = tep_db_fetch_array($classid_query);

        return $result['products_tax_class_id'];

}



// Returns the configuration value based on key

// TABLES: configuration

function tep_get_configuration_value($configuration_key) {

        $configuration_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key =  '" . $configuration_key . "';");

        $result = tep_db_fetch_array($configuration_query);

        return $result['configuration_value'];

}



// Returns the state code value based on state

// TABLES: zones

function tep_get_state_code($state_value) {

        $state_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where (zone_code =  '" . $state_value . "' or zone_name = '" . $state_value . "' );");

        $result = tep_db_fetch_array($state_query);

        return $result['zone_id'];

}



?>

