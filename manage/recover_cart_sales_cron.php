<?php
/*
 $Id: recover_cart_sales_cron.php $
 Recover Cart Sales Tool v2.30
*/

//
// REPLACE THE PATH TO YOUR OWN CATALOG DIRECTORY AND SET YOUR LANGUAGE
//
  chdir('/home/XYZ/public_html/store/catalog/admin');
	//chdir('/home/XYZ/public_html/store/catalog/admin');
  $language = 'english';

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// language
  include(DIR_WS_CLASSES . 'language.php');
  $lng = new language();
  $lng->set_language($language);
  $language = $lng->language['directory'];
  $languages_id = $lng->language['id'];

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// Application  
  require(DIR_WS_CLASSES . 'recover_cart_sales.php');

  $rcs = new recover_cart_sales(RCS_BASE_DAYS, RCS_SKIP_DAYS);
  
  //Query using defaults
  $custids = $rcs->processSearch();
  echo $rcs->getInfoBox();
  
  //Send emails
  $rcs->processEmail($custids);
  echo $rcs->getInfoBox();
  
?>