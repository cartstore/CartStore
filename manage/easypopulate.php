<?php
/*
  $Id: easypopulate.php,v 2.76g 2007/01/20 22:50:52 surfalot Exp $
  Designed for CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2007 Todd Holforty mtholforty(at)surfalot(dot)com
  GNU General Public License Compatible
*/
// Current EP Version
define ('EP_CURRENT_VERSION', '2.76g-2.0');
//set_time_limit(300);
require('includes/application_top.php');
require_once('includes/database_tables.php');
// >>> BEGIN REGISTER_GLOBALS
link_get_variable('download');
link_get_variable('dltype');
link_get_variable('split');
link_post_variable('MAX_FILE_SIZE');
link_post_variable('buttoninsert');
link_post_variable('buttonsplit');
link_post_variable('localfile');
require('easypopulate_functions.php');
$system = tep_get_system_information();
//
//*******************************
//*******************************
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************
//////////////////////////////////////////////////////
// *** Show all these settings on EP main page ***
// use this to debug your settings. Copy the settings
// to your post on the forum if you need help.
//////////////////////////////////////////////////////
define ('EP_SHOW_EP_SETTINGS', true); // default is: false
// **** Temp directory ****
/* ////////////////////////////////////////////////////////////////////////
//
// *IF* you changed your directory structure from stock and do not
// have /catalog/temp/, then you'll need to change this accordingly.
//
// *IF* your shop is in the default /catalog/ installation directory
// on your website, skip this Temp Directory settings info.
//
///////////////////////////////////////////////////////////////////////////
   CREATING THE TEMP DIRECTORY
   If your shop is in the root of your public site ( /home/myaccount/public_html/index.php ),
   you should create a folder called temp from the root of your web space so that the
   full path looks like this: /home/myaccount/public_html/temp/
   Then you must set the permissions to 777. If you don't know how, ask your host.
   THE DIR_FS_DOCUMENT_ROOT SETTING
   DIR_FS_DOCUMENT_ROOT is set in your /catalog/admin/includes/configure.php
   You should look at the setting DIR_FS_DOCUMENT_ROOT setting.
   if it looks like this (recommended, but doesn't always work):
       define ('DIR_FS_DOCUMENT_ROOT', $DOCUMENT_ROOT);
   then leave it alone. If it looks like this:
       define ('DIR_FS_DOCUMENT_ROOT', '/home/myaccount/public_html');
   ask your host if the "/home/myaccount/public_html" portion points to your public
   web space and is correct. Whether you add the trailing slash on the
   path or not doesn't matter to this contrib, as long as you make the
   right choice on the following setting. The best thing is to leave it
   alone as long as your host can confirm it is correct and everything else
   is working fine. Having said that, NO trailing slash is technically correct.
   THE DIR_WS_CATALOG & DIR_FS_CATALOG SETTINGS
   DIR_WS_CATALOG & DIR_FS_CATALOG are set in your /catalog/admin/includes/configure.php
   They may look like this if your shop is in the root of your web space.
   If you have something different, don't just change it to this.
   There is probably a good reason. I'm providing this as a reference
   to you-all. The DIR_FS_DOCUMENT_ROOT, the DIR_WS_CATALOG, and the
   DIR_FS_CATALOG settings all combine to create the temp location below.
       define('DIR_WS_CATALOG', '/');
       define('DIR_FS_CATALOG', DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG);
   THIS EP_TEMP_DIRECTORY SETTING
   Next, the following setting should set so that the DIR_FS_CATALOG setting
   plus this following setting makes a correct full path to your temporary
   location, like this: /home/myaccount/public_html/temp/
   if /home/myaccount/public_html/temp/ is the correct full path to your temp
   location, then:
       define ('EP_TEMP_DIRECTORY', DIR_FS_CATALOG . 'temp/');
   is the correct setting here.  Wow, I really hope this stops the forum traffic about this !!
////////////////////////////////////////////////////////////////////////// */
// **** Temp directory ****
define ('EP_TEMP_DIRECTORY', DIR_FS_ADMIN . 'temp/');
//**** File Splitting Configuration ****
// we attempt to set the timeout limit longer for this script to avoid having to split the files
// NOTE:  If your server is running in safe mode, this setting cannot override the timeout set in php.ini
// uncomment this if you are not on a safe mode server and you are getting timeouts
// set_time_limit(330);
// if you are splitting files, this will set the maximum number of records to put in each file.
// if you set your php.ini to a long time, you can make this number bigger
define ('EP_SPLIT_MAX_RECORDS', 300);  // default, seems to work for most people.  Reduce if you hit timeouts
//define ('EP_SPLIT_MAX_RECORDS', 4); // for testing
//**** Image Defaulting ****
// set them to your own default "We don't have any picture" gif
//define ('EP_DEFAULT_IMAGE_MANUFACTURER', 'no_image_manufacturer.png');
//define ('EP_DEFAULT_IMAGE_PRODUCT', 'no_image_product.png');
//define ('EP_DEFAULT_IMAGE_CATEGORY', 'no_image_category.png');
// or let them get set to nothing
define ('EP_DEFAULT_IMAGE_MANUFACTURER', '');
define ('EP_DEFAULT_IMAGE_PRODUCT', '');
define ('EP_DEFAULT_IMAGE_CATEGORY', '');
//**** Status Field Setting ****
// Set the v_status field to "Inactive" if you want the status=0 in the system
define ('EP_TEXT_ACTIVE', 'Active');
define ('EP_TEXT_INACTIVE', 'Inactive');
// Set the v_status field to "Delete" if you want to remove the item from the system
define ('EP_DELETE_IT', 'Delete');
// If zero_qty_inactive is true, then items with zero qty will automatically be inactive in the store.
define ('EP_INACTIVATE_ZERO_QUANTITIES', false);  // default is false
//**** Size of products_model in products table ****
// set this to the size of your model number field in the db.  We check to make
// sure all models are no longer than this value. this prevents the database from
// getting fubared.  Just making this number bigger won't help your database!  They must match!
// If you increase the Model Number size, you must increase the size of the field
// in the database. Use a SQL tool like phpMyAdmin (see your host) and change the
// "products_model" field of the "products" table in your CartStore Database.
define ('EP_MODEL_NUMBER_SIZE', 60); // default is 12
//**** Price includes tax? ****
// Set the EP_PRICE_WITH_TAX to
// false if you want the price that is exported to be the same value as stored in the database (no tax added).
// true if you want the tax to be added to the export price and subtracted from the import price.
define ('EP_PRICE_WITH_TAX', false);  // default is false
//**** Price calculation precision ****
// NOTE: when entering into the database all prices will be converted to 4 decimal places.
define ('EP_PRECISION', 2);  // default is 2
// **** Quote -> Escape character conversion ****
// If you have extensive html in your descriptions and it's getting mangled on upload, turn this off
// set to true = replace quotes with escape characters
// set to false = no quote replacement
define ('EP_REPLACE_QUOTES', true);  // default is false
// **** Field Separator ****
// change this if you can't use the default of tabs
// Tab is the default, comma and semicolon are commonly supported by various progs
// Remember, if your descriptions contain this character, you will confuse EP!
// if EP_EXCEL_SAFE_OUTPUT if false (below) you must make EP_PRESERVE_TABS_CR_LF false also.
$ep_separator = "\t"; // tab is default
//$ep_separator = ',';  // comma
//$ep_separator = ';';  // semi-colon
//$ep_separator = '~';  // tilde
//$ep_separator = '*';  // splat
// *** Excel safe output ***
// this setting will supersede the previous $ep_separator setting and create a file
// that excel will import without spanning cells from embedded commas or tabs in your products.
// if EP_EXCEL_SAFE_OUTPUT if false (below) you must make EP_PRESERVE_TABS_CR_LF false also.
define ('EP_EXCEL_SAFE_OUTPUT', false); // default is: true
if (EP_EXCEL_SAFE_OUTPUT == true) {
  if ($language == 'english') {
    $ep_separator = ',';  // comma
  } elseif ($language == 'german') {
    $ep_separator = ';';  // semi-colon
  } else {
    $ep_separator = ',';  // comma  // default for all others.
  }
}
// if EP_EXCEL_SAFE_OUTPUT if true (above) there is an alternative line parsing routine
//  provided by Maynard that will use a manual php approach.  There is a bug in some
// PHP versions that may require you to use this routine.  This should also provide proper
// parsing when quotes are used within a string. I suspect this should also resolve an issue
// recently reported in which characters with a german "Umlaute" like ÄäÖöÜü at the Beginning
// of some text, they will disappear when importing some csv-file, reported by TurboTB.
define ('EP_EXCEL_SAFE_OUTPUT_ALT_PARCE', false); // default is: false
// *** Preserve Tabs, Carriage returns and Line feeds ***
// this setting will preserve the special chars that can cause problems in
// a text based output. When used with EP_EXCEL_SAFE_OUTPUT, it will safely
// preserve these elements in the export and import.
define ('EP_PRESERVE_TABS_CR_LF', false); // default is: false
// **** Max Category Levels ****
// change this if you need more or fewer categories.
// set this to the maximum depth of your categories.
define ('EP_MAX_CATEGORIES', 7); // default is 7
// VJ product attributes begin
// **** Product Attributes ****
// change this to false, if do not want to download product attributes
define ('EP_PRODUCTS_WITH_ATTRIBUTES', true);  // default is true
// change this to true, if you use QTYpro and want to set attributes stock with EP.
define ('EP_PRODUCTS_ATTRIBUTES_STOCK', true); // default is false
// change this if you want to download only selected product options (attributes).
// If you have a lot of product options, and your output file exceeds 256 columns,
// which is the max. limit MS Excel is able to handle, then load-up this array with
// attributes to skip when generating the export.
$attribute_options_select = '';
// $attribute_options_select = array('Size', 'Model'); // uncomment and fill with product options name you wish to download // comment this line, if you wish to download all product options
// VJ product attributes end
// ******************************************************************
// BEGIN Define Custom Fields for your products database
// ******************************************************************
// the following line is always left as is.
$custom_fields = array();
//
// The following setup will allow you to define any additional
// field into the "products" and "products_description" tables
// in your shop. If you have  installed a custom contribution
// that adds fields to these tables you may simply and easily add
// them to the EasyPopulate system.
//
// ********************
// ** products table **
// Lets say you have added a field to your "products" table called
// "products_upc". The header name in your import file will be
// called "v_products_upc".  Then below you will change the line
// that looks like this (without the comment double-slash at the beginning):
// $custom_fields[TABLE_PRODUCTS] = array(); // this line is used if you have no custom fields to import/export
//
// TO:
// $custom_fields[TABLE_PRODUCTS] = array( 'products_upc' => 'UPC' );
//
// If you have multiple fields this is what it would look like:
// $custom_fields[TABLE_PRODUCTS] = array( 'products_upc' => 'UPC', 'products_restock_quantity' => 'Restock' );
//
// ********************************
// ** products_description table **
// Lets say you have added a field to your "products_description" table called
// "products_short_description". The header name in your import file will be
// called "v_products_short_description_1" for English, "v_products_short_description_2" for German,
// "v_products_short_description_3" for Spanish. Other languages will vary. Be sure to use the
// langugage ID of the custom language you installed if it is other then the original
// 3 installed languages of CartStore. If you are unsure what language ID you need to
// use, do a complete export and examine the file headers EasyPopulate produces.
//
// Then below you will change the line that looks like this (without the comment double-slash at the beginning):
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array(); // this line is used if you have no custom fields to import/export
//
// TO:
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array( 'products_short_description' => 'short' );
//
// If you have multiple fields this is what it would look like:
// $custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array( 'products_short_description' => 'short', 'products_viewed' => 'Viewed' );
//
// the array format is: array( 'table_field_name' => 'Familiar Name' )
// the array key ('table_field_name') is always the exact name of the
// field in the table. The array value ('Familiar Name') is any text
// name that will be used in the custom EP export download checkbox.
//
// I believe this will only work for text/varchar and numeric field
// types.  If your custom field is a date/time or any other type, you
// may need to incorporate custom code to correctly import your data.
//
$custom_fields[TABLE_PRODUCTS] = array('products_mpn' => 'products_mpn','products_gtin' => 'products_gtin', 'products_price1' => 'products_price1', 'products_price2' => 'products_price2','products_price3' => 'products_price3', 'products_price4' => 'products_price4','products_price5' => 'products_price5', 'products_price6' => 'products_price6','products_price7' => 'products_price7', 'products_price8' => 'products_price8','products_price1_qty' => 'products_price1_qty', 'products_price2_qty' => 'products_price2_qty','products_price3_qty' => 'products_price3_qty', 'products_price4_qty' => 'products_price4_qty','products_price5_qty' => 'products_price5_qty', 'products_price6_qty' => 'products_price6_qty','products_price7_qty' => 'products_price7_qty', 'products_price8_qty' => 'products_price8_qty','products_qty_blocks'=> 'products_qty_blocks','products_special'=> 'products_special','products_featured_until'=> 'products_featured_until','products_featured'=> 'products_featured','vendors_id'=> 'vendors_id','map_price'=> 'map_price','msrp_price'=> 'msrp_price','pSortOrder'=> 'pSortOrder' ,'product_image_2'=> 'product_image_2','product_image_3'=> 'product_image_3','product_image_4'=> 'product_image_4','product_image_5'=> 'product_image_5','product_image_6'=> 'product_image_6'); // this line is used if you have no custom fields to import/export
$custom_fields[TABLE_PRODUCTS_DESCRIPTION] = array('products_short'=> 'products_short','products_seo_url'=> 'products_seo_url','products_info_title'=> 'products_info_title','products_info_desc'=> 'products_info_desc'); // this line is used if you have no custom fields to import/export
$custom_fields[TABLE_SPECIALS] = array(); // this line is used if you have no custom fields to import/export
//
// FINAL NOTE: this currently only works with the "products" & "products_description" table.
// If it works well and I don't get a plethora of problems reported,
// I may expand it to more tables. Feel free to make requests, but
// as always, only as me free time allows.
//
// ******************************************************************
// END Define Custom Fields for your products database
// ******************************************************************
// ****************************************
// Froogle configuration variables
// Here are some links regarding Bulk uploads
// http://www.google.com/base/attributes.html
// http://www.google.com/base/help/custom-attributes.html
// ****************************************
// **** Froogle product info page path ****
// We can't use the tep functions to create the link, because the links will point to the
// admin, since that's where we're at. So put the entire path to your product_info.php page here
define ('EP_FROOGLE_PRODUCT_INFO_PATH', HTTP_CATALOG_SERVER . DIR_WS_CATALOG . "product_info.php");
// **** Froogle product image path ****
// Set this to the path to your images directory
define ('EP_FROOGLE_IMAGE_PATH', HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES);
// **** Froogle - search engine friendly setting
// if your store has SEARCH ENGINE FRIENDLY URLS set, then turn this to true
// I did it this way because I'm having trouble with the code seeing the constants
// that are defined in other places.
define ('EP_FROOGLE_SEF_URLS', false);  // default is false
// **** Froogle Currency Setting
define ('EP_FROOGLE_CURRENCY', 'USD');  // default is 'USD'
// ****************************************
// End: Froogle configuration variables
//
// ***********************************
// *** Other Contributions Support ***
// ***********************************
// More Pics 6 v1.3
define ('EP_MORE_PICS_6_SUPPORT', false);  // default is false
//
// Header Tags Controller Support v2.0
define ('EP_HTC_SUPPORT', true);  // default is false
//
// Separate Pricing Per Customer (SPPC)
define ('EP_SPPC_SUPPORT', false);  // default is false
// ///////////////////////////////////////////////////////////////////////////////
// The following items are not complete and untested. Experiment at your own risk.
// ///////////////////////////////////////////////////////////////////////////////
//
// Extra Fields Contribution (***UNTESTED AND MAY NOT BE FUNCTIONAL***)
define ('EP_EXTRA_FIELDS_SUPPORT', true);  // default is false
//
// Unknown Image Contrib (***UNTESTED AND MAY NOT BE FUNCTIONAL***)
define ('EP_UNKNOWN_ADD_IMAGES_SUPPORT', false);  // default is false
//*******************************
//*******************************
// E N D
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************
//*******************************
//*******************************
// S T A R T
// INITIALIZATION
//*******************************
//*******************************
// modify tableBlock for use here.
  class epbox extends tableBlock {
    // constructor
    function epbox($contents, $direct_ouput = true) {
      $this->table_width = '';
      if (!empty($contents) && $direct_ouput == true) {
        echo $this->tableBlock($contents);
      }
    }
    // only member function
    function output($contents) {
      return $this->tableBlock($contents);
    }
  }
if (!empty($languages_id) && !empty($language)) {
  define ('EP_DEFAULT_LANGUAGE_ID', $languages_id);
  define ('EP_DEFAULT_LANGUAGE_NAME', $language);
} else {
  //elari check default language_id from configuration table DEFAULT_LANGUAGE
  $epdlanguage_query = tep_db_query("select languages_id, name from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
  if (tep_db_num_rows($epdlanguage_query) > 0) {
    $epdlanguage = tep_db_fetch_array($epdlanguage_query);
    define ('EP_DEFAULT_LANGUAGE_ID', $epdlanguage['languages_id']);
    define ('EP_DEFAULT_LANGUAGE_NAME', $epdlanguage['name']);
  } else {
    echo 'Strange but there is no default language to work... That may not happen, just in case... ';
  }
}
$languages = tep_get_languages();
// VJ product attributes begin
$attribute_options_array = array();
if (EP_PRODUCTS_WITH_ATTRIBUTES == true) {
    if (is_array($attribute_options_select) && (count($attribute_options_select) > 0)) {
        foreach ($attribute_options_select as $value) {
            $attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " where products_options_name = '" . $value . "'";
            $attribute_options_values = tep_db_query($attribute_options_query);
            if ($attribute_options = tep_db_fetch_array($attribute_options_values)){
                $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
            }
        }
    } else {
        $attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
        $attribute_options_values = tep_db_query($attribute_options_query);
        while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
            $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
        }
    }
}
// VJ product attributes end
// these are the fields that will be defaulted to the current values in
// the database if they are not found in the incoming file
$default_these = array();
foreach ($languages as $key => $lang){
  $default_these[] = 'v_products_name_' . $lang['id'];
  $default_these[] = 'v_products_description_' . $lang['id'];
  $default_these[] = 'v_products_url_' . $lang['id'];
  foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
    $default_these[] = 'v_' . $key . '_' . $lang['id'];
  }
  if (EP_HTC_SUPPORT == true) {
    $default_these[] = 'v_products_head_title_tag_' . $lang['id'];
    $default_these[] = 'v_products_head_desc_tag_' . $lang['id'];
    $default_these[] = 'v_products_head_keywords_tag_' . $lang['id'];
  }
}
$default_these[] = 'v_products_image';
foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
  $default_these[] = 'v_' . $key;
}
foreach ($custom_fields[TABLE_SPECIALS] as $key => $name) {
  $default_these[] = 'v_' . $key;
}
if (EP_MORE_PICS_6_SUPPORT == true) {
  $default_these[] = 'v_products_subimage1';
  $default_these[] = 'v_products_subimage2';
  $default_these[] = 'v_products_subimage3';
  $default_these[] = 'v_products_subimage4';
  $default_these[] = 'v_products_subimage5';
  $default_these[] = 'v_products_subimage6';
}
if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
  $default_these[] = 'v_products_mimage';
  $default_these[] = 'v_products_bimage';
  $default_these[] = 'v_products_subimage1';
  $default_these[] = 'v_products_bsubimage1';
  $default_these[] = 'v_products_subimage2';
  $default_these[] = 'v_products_bsubimage2';
  $default_these[] = 'v_products_subimage3';
  $default_these[] = 'v_products_bsubimage3';
}
$default_these[] = 'v_categories_id';
$default_these[] = 'v_products_price';
$default_these[] = 'v_products_quantity';
$default_these[] = 'v_products_weight';
$default_these[] = 'v_status_current';
$default_these[] = 'v_date_avail';
$default_these[] = 'v_date_added';
$default_these[] = 'v_tax_class_title';
$default_these[] = 'v_manufacturers_name';
$default_these[] = 'v_specials_new_products_price';
$default_these[] = 'v_manufacturers_id';
$filelayout = '';
$filelayout_count = '';
$filelayout_sql = '';
$fileheaders = '';
if ( !empty($_GET['dltype']) ) {
    // if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file
    list($filelayout, $filelayout_count, $filelayout_sql, $fileheaders) = ep_create_filelayout($_GET['dltype'], $attribute_options_array, $languages, $custom_fields); // get the right filelayout for this download
}
//*******************************
//*******************************
// E N D
// INITIALIZATION
//*******************************
//*******************************
//*******************************
//*******************************
// DOWNLOAD FILE (EXPORT)
//*******************************
//*******************************
if ( !empty($_GET['download']) && ($_GET['download'] == 'stream' or $_GET['download'] == 'activestream' or $_GET['download'] == 'tempfile') ){
    $filestring = ""; // this holds the csv file we want to download
    $result = tep_db_query($filelayout_sql);
    $row =  tep_db_fetch_array($result);
    // $EXPORT_TIME=time();  // start export time when export is started.
    $EXPORT_TIME = strftime('%Y%b%d-%H%I');
    if ($dltype=="froogle"){
        $EXPORT_TIME = "FroogleEP" . $EXPORT_TIME;
    } else {
        $EXPORT_TIME = "EP" . $EXPORT_TIME;
    }
    // Here we need to allow for the mapping of internal field names to external field names
    // default to all headers named like the internal ones
    // the field mapping array only needs to cover those fields that need to have their name changed
    if ( count($fileheaders) != 0 ){
        $filelayout_header = $fileheaders; // if they gave us fileheaders for the dl, then use them
    } else {
        $filelayout_header = $filelayout; // if no mapping was spec'd use the internal field names for header names
    }
    //We prepare the table heading with layout values
    foreach( $filelayout_header as $key => $value ){
        $filestring .= $key . $ep_separator;
    }
    // now lop off the trailing tab
    $filestring = substr($filestring, 0, strlen($filestring)-1);
    // set the type
    if ( $dltype == 'froogle' ){
        $endofrow = "\n";
    } else {
        // default to normal end of row
        $endofrow = $ep_separator . 'EOREOR' . "\n";
    }
    $filestring .= $endofrow;
    if ($_GET['download'] == 'activestream'){
      header("Content-type: application/vnd.ms-excel");
      header("Content-disposition: attachment; filename=$EXPORT_TIME" . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"));
      // Changed if using SSL, helps prevent program delay/timeout (add to backup.php also)
      //    header("Pragma: no-cache");
      if ($request_type== 'NONSSL'){
        header("Pragma: no-cache");
      } else {
        header("Pragma: ");
      }
      header("Expires: 0");
      echo $filestring;
    }
    $num_of_langs = count($languages);
    while ($row){
        // if the filelayout says we need a products_name, get it
        // build the long full froogle image path
        $row['v_products_fullpath_image'] = EP_FROOGLE_IMAGE_PATH . $row['v_products_image'];
        // Other froogle defaults go here for now
        $row['v_froogle_quantitylevel']     = $row['v_products_quantity'];
        $row['v_froogle_manufacturer_id']   = '';
        $row['v_froogle_exp_date']          = date('Y-m-d', strtotime('+30 days'));
        $row['v_froogle_product_type']      = $row['v_categories_id'];
        $row['v_froogle_product_id']        = $row['v_products_model'];
        $row['v_froogle_currency']          = EP_FROOGLE_CURRENCY;
        // names and descriptions require that we loop thru all languages that are turned on in the store
        foreach ($languages as $key => $lang){
            $lid = $lang['id'];
            // for each language, get the description and set the vals
            $sql2 = "SELECT *
                FROM ".TABLE_PRODUCTS_DESCRIPTION."
                WHERE
                    products_id = " . $row['v_products_id'] . " AND
                    language_id = '" . $lid . "'
                ";
            $result2 = tep_db_query($sql2);
            $row2 =  tep_db_fetch_array($result2);
            // I'm only doing this for the first language, since right now froogle is US only.. Fix later!
            // adding url for froogle, but it should be available no matter what
            if (EP_FROOGLE_SEF_URLS == true){
                // if only one language
                if ($num_of_langs == 1){
                    $row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '/products_id/' . $row['v_products_id'];
                } else {
                    $row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '/products_id/' . $row['v_products_id'] . '/language/' . $lid;
                }
            } else {
                if ($num_of_langs == 1){
                    $row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '?products_id=' . $row['v_products_id'];
                } else {
                    $row['v_froogle_products_url_' . $lid] = EP_FROOGLE_PRODUCT_INFO_PATH . '?products_id=' . $row['v_products_id'] . '&language=' . $lid;
                }
            }
            $row['v_products_name_' . $lid]          = $row2['products_name'];
            $row['v_products_description_' . $lid]   = $row2['products_description'];
            $row['v_products_url_' . $lid]           = $row2['products_url'];
            foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
              $row['v_' . $key . '_' . $lid]           = $row2[$key];
            }
            // froogle advanced format needs the quotes around the name and desc
            $row['v_froogle_products_name_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_name'])) . '"';
            $row['v_froogle_products_description_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_description'])) . '"';
            // support for Linda's Header Controller 2.0 here
            if(isset($filelayout['v_products_head_title_tag_' . $lid])){
                $row['v_products_head_title_tag_' . $lid]     = $row2['products_head_title_tag'];
                $row['v_products_head_desc_tag_' . $lid]      = $row2['products_head_desc_tag'];
                $row['v_products_head_keywords_tag_' . $lid]  = $row2['products_head_keywords_tag'];
            }
            // end support for Header Controller 2.0
        }
		// for the categories, we need to keep looping until we find the root category
		// start with v_categories_id
		// Get the category description
		// set the appropriate variable name
		// if parent_id is not null, then follow it up.
		// we'll populate an aray first, then decide where it goes in the
		$thecategory_id = $row['v_categories_id'];
		$fullcategory = ''; // this will have the entire category stack for froogle
		for( $categorylevel=1; $categorylevel<=EP_MAX_CATEGORIES; $categorylevel++){
			if ($thecategory_id){
				$sql3 = "SELECT parent_id,
								categories_image
						 FROM ".TABLE_CATEGORIES."
						 WHERE
								categories_id = " . $thecategory_id . '';
				$result3 = tep_db_query($sql3);
				if ($row3 = tep_db_fetch_array($result3)) {
					$temprow['v_categories_image_' . $categorylevel] = $row3['categories_image'];
				}
				foreach ($languages as $key => $lang){
					$sql2 = "SELECT categories_name
							 FROM ".TABLE_CATEGORIES_DESCRIPTION."
							 WHERE
									categories_id = " . $thecategory_id . " AND
									language_id = " . $lang['id'];
					$result2 = tep_db_query($sql2);
					if ($row2 =  tep_db_fetch_array($result2)) {
						$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = $row2['categories_name'];
					}
					if ($lang['id'] == '1') {
						//$fullcategory .= " > " . $row2['categories_name'];
						$fullcategory = $row2['categories_name'] . " > " . $fullcategory;
					}
				}
				// now get the parent ID if there was one
				$theparent_id = $row3['parent_id'];
				if ($theparent_id != ''){
					// there was a parent ID, lets set thecategoryid to get the next level
					$thecategory_id = $theparent_id;
				} else {
					// we have found the top level category for this item,
					$thecategory_id = false;
				}
			} else {
				$temprow['v_categories_image_' . $categorylevel] = '';
				foreach ($languages as $key => $lang){
					$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = '';
				}
			}
		}
		// now trim off the last ">" from the category stack
		$row['v_category_fullpath'] = substr($fullcategory,0,strlen($fullcategory)-3);
		// temprow has the old style low to high level categories.
		$newlevel = 1;
		// let's turn them into high to low level categories
		for( $categorylevel=EP_MAX_CATEGORIES; $categorylevel>0; $categorylevel--){
			$found = false;
			if ($temprow['v_categories_image_' . $categorylevel] != ''){
				$row['v_categories_image_' . $newlevel] = $temprow['v_categories_image_' . $categorylevel];
				$found = true;
			}
			foreach ($languages as $key => $lang){
				if ($temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] != ''){
					$row['v_categories_name_' . $newlevel . '_' . $lang['id']] = $temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']];
					$found = true;
				}
			}
			if ($found == true) {
				$newlevel++;
			}
		}
        // if the filelayout says we need a manufacturers name, get it
        if (isset($filelayout['v_manufacturers_name'])){
            if ($row['v_manufacturers_id'] != ''){
                $sql2 = "SELECT manufacturers_name
                    FROM ".TABLE_MANUFACTURERS."
                    WHERE
                    manufacturers_id = " . $row['v_manufacturers_id']
                    ;
                $result2 = tep_db_query($sql2);
                $row2 =  tep_db_fetch_array($result2);
                $row['v_manufacturers_name'] = $row2['manufacturers_name'];
            }
        }
		    // if the filelayout says we need a manufacturers name, get it
        if (isset($filelayout['v_specials_new_products_price'])){
            if ($row['v_products_id'] != ''){
                $sql2 = "SELECT specials_new_products_price
                    FROM ".TABLE_SPECIALS."
                    WHERE
                    products_id = " . $row['v_products_id']
                    ;
                $result2 = tep_db_query($sql2);
                $row2 =  tep_db_fetch_array($result2);
                $row['v_specials_new_products_price'] = $row2['specials_new_products_price'];
            }
        }
        // If you have other modules that need to be available, put them here
        // VJ product attribs begin
        if (isset($filelayout['v_attribute_options_id_1'])){
            $attribute_options_count = 1;
      foreach ($attribute_options_array as $attribute_options) {
                $row['v_attribute_options_id_' . $attribute_options_count]     = $attribute_options['products_options_id'];
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                    $lid = $languages[$i]['id'];
                    $attribute_options_languages_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' and language_id = '" . (int)$lid . "'";
                    $attribute_options_languages_values = tep_db_query($attribute_options_languages_query);
                    $attribute_options_languages = tep_db_fetch_array($attribute_options_languages_values);
                    $row['v_attribute_options_name_' . $attribute_options_count . '_' . $lid] = $attribute_options_languages['products_options_name'];
                }
                $attribute_values_query = "select products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' order by products_options_values_id";
                $attribute_values_values = tep_db_query($attribute_values_query);
                $attribute_values_count = 1;
                while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
                    $row['v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count]     = $attribute_values['products_options_values_id'];
                    $attribute_values_price_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$row['v_products_id'] . "' and options_id = '" . (int)$attribute_options['products_options_id'] . "' and options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "'";
                    $attribute_values_price_values = tep_db_query($attribute_values_price_query);
                    $attribute_values_price = tep_db_fetch_array($attribute_values_price_values);
                    $row['v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count]     = $attribute_values_price['price_prefix'] . $attribute_values_price['options_values_price'];
    //// attributes stock add start
    if ( EP_PRODUCTS_ATTRIBUTES_STOCK    == true ) {
           $stock_attributes = $attribute_options['products_options_id'].'-'.$attribute_values['products_options_values_id'];
           $stock_quantity_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$row['v_products_id'] . "' and products_stock_attributes = '" . $stock_attributes . "'");
           $stock_quantity = tep_db_fetch_array($stock_quantity_query);
           $row['v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count] = $stock_quantity['products_stock_quantity'];
     }
    //// attributes stock add end
                    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                        $lid = $languages[$i]['id'];
                        $attribute_values_languages_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "' and language_id = '" . (int)$lid . "'";
                        $attribute_values_languages_values = tep_db_query($attribute_values_languages_query);
                        $attribute_values_languages = tep_db_fetch_array($attribute_values_languages_values);
                        $row['v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid] = $attribute_values_languages['products_options_values_name'];
                    }
                    $attribute_values_count++;
                }
                $attribute_options_count++;
            }
        }
        // VJ product attribs end
		// products ymm start
		if (isset($filelayout['v_products_car_make_1'])){
			$products_ymm_query = tep_db_query("select products_car_make, products_car_model, products_car_year_bof, products_car_year_eof from products_ymm where products_id = '" . $row['v_products_id'] . "'");
			$ll = 1;
			while($products_ymm = tep_db_fetch_array($products_ymm_query)){
				$row['v_products_car_make_' . $ll]     = $products_ymm['products_car_make'];
				$row['v_products_car_model_' . $ll]     = $products_ymm['products_car_model'];
				$row['v_products_car_year_bof_' . $ll]     = $products_ymm['products_car_year_bof'];
				$row['v_products_car_year_eof_' . $ll]     = $products_ymm['products_car_year_eof'];
				$ll ++;
			}
		}
		// products ymm end
        // this is for the separate price per customer module
        if (isset($filelayout['v_customer_price_1'])){
            $sql2 = "SELECT
                    customers_group_price,
                    customers_group_id
                FROM
                    ".TABLE_PRODUCTS_GROUPS."
                WHERE
                products_id = " . $row['v_products_id'] . "
                ORDER BY
                customers_group_id"
                ;
            $result2 = tep_db_query($sql2);
            $ll = 1;
            $row2 =  tep_db_fetch_array($result2);
            while( $row2 ){
                $row['v_customer_group_id_' . $ll]     = $row2['customers_group_id'];
                $row['v_customer_price_' . $ll]     = $row2['customers_group_price'];
                $row2 = tep_db_fetch_array($result2);
                $ll++;
            }
        }
        if ($dltype == 'froogle'){
            // For froogle, we check the specials prices for any applicable specials, and use that price
            // by grabbing the specials id descending, we always get the most recently added special price
            // I'm checking status because I think you can turn off specials
            $sql2 = "SELECT
                    specials_new_products_price
                FROM
                    ".TABLE_SPECIALS."
                WHERE
                products_id = " . $row['v_products_id'] . " and
                status = 1 and
                expires_date < CURRENT_TIMESTAMP
                ORDER BY
                    specials_id DESC"
                ;
            $result2 = tep_db_query($sql2);
            $ll = 1;
            $row2 =  tep_db_fetch_array($result2);
            if( $row2 ){
                // reset the products price to our special price if there is one for this product
                $row['v_products_price']     = $row2['specials_new_products_price'];
            }
        }
        //elari -
        //We check the value of tax class and title instead of the id
        //Then we add the tax to price if EP_PRICE_WITH_TAX is set to true
        $row_tax_multiplier         = tep_get_tax_class_rate($row['v_tax_class_id']);
        $row['v_tax_class_title']   = tep_get_tax_class_title($row['v_tax_class_id']);
        $row['v_products_price']    = $row['v_products_price'] +
                (EP_PRICE_WITH_TAX == true ? round( ($row['v_products_price'] * $row_tax_multiplier / 100), EP_PRECISION) : 0);
        // Now set the status to a word the user specd in the config vars
        if ( $row['v_status'] == '1' ){
            $row['v_status'] = EP_TEXT_ACTIVE;
        } else {
            $row['v_status'] = EP_TEXT_INACTIVE;
        }
        // remove any bad things in the texts that could confuse EasyPopulate
        $therow = '';
        foreach( $filelayout as $key => $value ){
            //echo "The field was $key<br />";
            $thetext = $row[$key];
            // kill the carriage returns and tabs in the descriptions, they're killing me!
            if (EP_PRESERVE_TABS_CR_LF == false || $dltype == 'froogle') {
              $thetext = str_replace("\r",' ',$thetext);
              $thetext = str_replace("\n",' ',$thetext);
              $thetext = str_replace("\t",' ',$thetext);
            }
            if (EP_EXCEL_SAFE_OUTPUT == true && $dltype != 'froogle') {
              // use quoted values and escape the embedded quotes for excel safe output.
              $therow .= '"'.str_replace('"','""',$thetext).'"' . $ep_separator;
            } else {
              // and put the text into the output separated by $ep_separator defined above
              $therow .= $thetext . $ep_separator;
            }
        }
        // lop off the trailing separator, then append the end of row indicator
        $therow = substr($therow,0,strlen($therow)-1) . $endofrow;
        if ($_GET['download'] == 'activestream'){
          echo $therow;
        } else {
          $filestring .= $therow;
        }
        // grab the next row from the db
        $row =  tep_db_fetch_array($result);
    }
    // now either stream it to them or put it in the temp directory
    if ($_GET['download'] == 'activestream'){
        die();
    } elseif ($_GET['download'] == 'stream'){
        //*******************************
        // STREAM FILE
        //*******************************
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=$EXPORT_TIME" . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"));
        // Changed if using SSL, helps prevent program delay/timeout (add to backup.php also)
        //    header("Pragma: no-cache");
        if ($request_type== 'NONSSL'){
          header("Pragma: no-cache");
        } else {
          header("Pragma: ");
        }
        header("Expires: 0");
        echo $filestring;
        die();
    } elseif ($_GET['download'] == 'tempfile') {
        //*******************************
        // PUT FILE IN TEMP DIR
        //*******************************
        $tmpfname = EP_TEMP_DIRECTORY . "$EXPORT_TIME" . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt");
        //unlink($tmpfname);
        $fp = fopen( $tmpfname, "w+");
        fwrite($fp, $filestring);
        fclose($fp);
        echo "You can get your file in the Tools/File Manager here: " . EP_TEMP_DIRECTORY . "EP" . $EXPORT_TIME . ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt");
        die();
    }
}
//*******************************
//*******************************
// S T A R T
// PAGE DELIVERY
//*******************************
//*******************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"><!--
  function switchForm( field ) {
    var d = document;
    var frm = field.form;
    var tbl = d.getElementById('customtable');
    if(d.getElementById)
    {
      if(field.options[field.selectedIndex].text == 'Complete' || field.options[field.selectedIndex].text == 'Froogle')
      {
        tbl.style.backgroundColor='lightgrey';
        for ( var index = 0; index < frm.elements.length; index++ )
        {
            var oElement = frm.elements[ index ];
            if ( oElement.type == "checkbox" )
            {
                if ( oElement.checked )
                {
                  oElement.checked = false;
                }
                oElement.disabled = true;
            }
        }
      }
      else if(field.options[field.selectedIndex].text == 'Price/Qty' || field.options[field.selectedIndex].text == 'Categories' || field.options[field.selectedIndex].text == 'Attributes' )
      {
        tbl.style.backgroundColor='lightgrey';
        for ( var index = 0; index < frm.elements.length; index++ )
        {
            var oElement = frm.elements[ index ];
            if ( oElement.type == "checkbox" )
            {
                if ( oElement.checked )
                {
                  oElement.checked = false;
                }
                if ( (oElement.name == 'epcust_price' || oElement.name == 'epcust_quantity') && field.options[field.selectedIndex].text == 'Price/Qty' )
                {
                  oElement.disabled = false;
                  oElement.checked = true;
                }
                else if ( oElement.name == 'epcust_category' && field.options[field.selectedIndex].text == 'Categories' )
                {
                  oElement.disabled = false;
                  oElement.checked = true;
                }
                else if ( oElement.name == 'epcust_attributes' && field.options[field.selectedIndex].text == 'Attributes' )
                {
                  oElement.disabled = false;
                  oElement.checked = true;
                }
                else
                {
                  oElement.disabled = true;
                }
            }
        }
      }
      else
      {
        tbl.style.backgroundColor='white';
        for ( var index = 0; index < frm.elements.length; index++ )
        {
            var oElement = frm.elements[ index ];
            if ( oElement.type == "checkbox" )
            {
                oElement.disabled = false;
            }
        }
      }
    }
  }
//--></script>
<style type="text/css">
<!--
.hide {
	display: none;
}
-->
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft" valign="top">
<?php require(DIR_WS_INCLUDES . 'column_left.php');?>
<h1>CartStore Mass Product Import</h1>
          <div id="dialog-message" title="Backup First">

          <h3>Warning for advanced users only.</h3>

          For professional use only.

<br />

<br />

Please backup your database before performing any operations!

<br />

<br />

We accept <b>no liability</b> for damage done using this tool. This tool is for experienced professionals only, please contact storecoders.com for <a href="http://support.cartstore.com/" target="_BLANK"><u>professional service</u></a>.

</div>
<p class="smallText"><?php
//*******************************
//*******************************
// UPLOAD AND INSERT FILE
//*******************************
//*******************************
if (!empty($_POST['localfile']) or (isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==0)) {
    if (isset($_FILES['usrfl'])){
        // move the file to where we can work with it
        $file = tep_get_uploaded_file('usrfl');
        if (is_uploaded_file($file['tmp_name'])) {
            tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
        }
        echo "<p class=smallText>";
        echo "File uploaded. <br />";
        echo "Temporary filename: " . $file['tmp_name'] . "<br />";
        echo "User filename: " . $file['name'] . "<br />";
        echo "Size: " . $file['size'] . "<br />";
        // get the entire file into an array
        $readed = file(EP_TEMP_DIRECTORY . $file['name']);
    }
    if (!empty($_POST['localfile'])){
        // move the file to where we can work with it
        //$file = tep_get_uploaded_file('usrfl');
        //$attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
        //$attribute_options_values = tep_db_query($attribute_options_query);
        //$attribute_options_count = 1;
        //while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
        //if (is_uploaded_file($file['tmp_name'])) {
        //    tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
        //}
        echo "<p class=smallText>";
        echo "Filename: " . $_POST['localfile'] . "<br />";
        // get the entire file into an array
        $readed = file(EP_TEMP_DIRECTORY . $_POST['localfile']);
    }
    if (EP_EXCEL_SAFE_OUTPUT == true) {
        // do excel safe input
        $fp = fopen(EP_TEMP_DIRECTORY . (isset($_FILES['usrfl'])?$file['name']:$_POST['localfile']),'r') or die('##Can not open file for reading. Script will terminate.<br />');  // open file
        // determine the separator character.
        $header_line = fgets($fp);
        if (strpos($header_line,',') !== false) { $ep_separator = ','; }
        if (strpos($header_line,';') !== false) { $ep_separator = ';'; }
        if (strpos($header_line,"\t") !== false) { $ep_separator = "\t"; }
        if (strpos($header_line,'~') !== false) { $ep_separator = '~'; }
        if (strpos($header_line,'-') !== false) { $ep_separator = '-'; }
        if (strpos($header_line,'*') !== false) { $ep_separator = '*'; }
        fclose($fp);
        if (EP_EXCEL_SAFE_OUTPUT_ALT_PARCE == false) {
            unset($readed);                    // kill array setup with above code
            $readed = array();                 // start a new one for excel_safe_output
            $fp = fopen(EP_TEMP_DIRECTORY . (isset($_FILES['usrfl'])?$file['name']:$_POST['localfile']),'r') or die('##Can not open file for reading. Script will terminate.<br />');  // open file
            while($line = fgetcsv($fp,32768,$ep_separator))   // read new line (max 32K bytes)
            {
                unset($line[(sizeof($line)-1)]);  // remove EOREOR at the end of the array
                $readed[] = $line;                // add to array we will process later
            }
            $theheaders_array = $readed[0];     // pull out header line
            fclose($fp);                        // close file
        } else {
            // Maynard's CSV Fix ###################################################
            // some versions of fgetcsv don't like a field beginning with "", so we'll parse it ourselves
            // use readed, but parse it ourselves according to $ep_separator, and " as field encloser
            // I copied this from below:
            // now we string the entire thing together in case there were carriage returns in the data
              $newreaded = "";
              foreach ($readed as $read){
                $newreaded .= $read;
              }
              // now newreaded has the entire file together without the carriage returns.
              // if for some reason excel put qoutes around our EOREOR, remove them then split into rows
              $newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
              $readed = explode( $ep_separator . 'EOREOR',$newreaded);
            // dump the last entry - it's blank
            unset($readed[sizeof($readed)-1]);
            // parse it all!
            foreach ($readed as $key => $line) {
                $ppp = 0; // pointer
                $line1 = array();
                $remains = trim($line);
                while ($remains != '') {
                    if (substr($remains,0,1) == '"') {
                        // this is an enclosed portion
                        $ppp1 = -1; // so that our first loop points to the right place in $remains
                        $ppp2 = -1;
                        // we've got to find the next " that isn't ""
                        while($ppp1 < strlen($remains) && $ppp1 == $ppp2 && $ppp1 !== FALSE) {
                            $ppp1 = strpos($remains, '"', $ppp2+2);
                            $ppp2 = strpos($remains, '""', $ppp2+2); // use ppp2 because then """ will get properly understood
                        }
                        // now $ppp1 points to the closing "
                        // we are pulling out the determined chunk and
                        // replacing any double quotes with single quotes
                        // since that is how CSV handles quotes
                        $line1[] = str_replace('""','"',substr($remains,1,$ppp1-1));
                        // find the next separator - for a well formed CSV file, it MUST be a separator (and not in quoted text)
                        $ppp1 = strpos($remains, $ep_separator, $ppp1);
                        $remains = ($ppp1 !== false) ? substr($remains,$ppp1+1) : '';
                    } else {
                        // this is an un-enclosed portion - find the next separator
                        $ppp1 = strpos($remains, $ep_separator, 0);
                        $line1[] = ($ppp1 !== false) ? substr($remains,0,$ppp1) : $remains;
                        $remains = ($ppp1 !== false) ? substr($remains,$ppp1+1) : '';
                    }
                }
                // replace that line with our new array
                $readed[$key] = $line1;
            }
            $theheaders_array = $readed[0];     // pull out header line
            // EOF Maynard's CSV Fix ###################################################
        }
    } else {
      // do normal EP input
      // now we string the entire thing together in case there were carriage returns in the data
      $newreaded = "";
      foreach ($readed as $read){
        $newreaded .= $read;
      }
      // now newreaded has the entire file together without the carriage returns.
      // if for some reason excel put qoutes around our EOREOR, remove them then split into rows
      $newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
      $readed = explode( $ep_separator . 'EOREOR',$newreaded);
      // Now we'll populate the filelayout based on the header row.
      $theheaders_array = explode( $ep_separator, $readed[0] ); // explode the first row, it will be our filelayout
    }
    $lll = 0;
    $filelayout = array();
    foreach( $theheaders_array as $header ){
        $cleanheader = str_replace( '"', '', $header);
        // echo "Fileheader was $header<br /><br /><br />";
        $filelayout[ $cleanheader ] = $lll++; //
    }
    unset($readed[0]); //  we don't want to process the headers with the data
    // now we've got the array broken into parts by the expicit end-of-row marker.
    foreach ($readed as $tkey => $readed_row) {
      process_row($readed_row, $filelayout, $filelayout_count, $default_these, $ep_separator, $languages, $custom_fields);
    }
    // isn't working in PHP 5
    // array_walk($readed, $filelayout, $filelayout_count, $default_these, 'process_row');
//*******************************
//*******************************
// UPLOAD AND SPLIT FILE
//*******************************
//*******************************
} elseif (isset($_FILES['usrfl']) && isset($_GET['split']) && $_GET['split']==1) {
    // move the file to where we can work with it
    $file = tep_get_uploaded_file('usrfl');
    //echo "Trying to move file...";
    if (is_uploaded_file($file['tmp_name'])) {
        tep_copy_uploaded_file($file, EP_TEMP_DIRECTORY);
    }
    $infp = fopen(EP_TEMP_DIRECTORY . $file['name'], "r");
    //toprow has the field headers
    $toprow = fgets($infp,32768);
    $filecount = 1;
    echo "Creating file EP_Split" . $filecount . '.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt').' ...  ';
    $tmpfname = EP_TEMP_DIRECTORY . "EP_Split" . $filecount.'.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt');
    $fp = fopen( $tmpfname, "w+");
    fwrite($fp, $toprow);
    $linecount = 0;
    $line = fgets($infp,32768);
    while ($line){
        // walking the entire file one row at a time
        // but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
        $line = str_replace('"EOREOR"', 'EOREOR', $line);
        fwrite($fp, $line);
        if (strpos($line, 'EOREOR')){
            // we found the end of a line of data, store it
            $linecount++; // increment our line counter
            if ($linecount >= EP_SPLIT_MAX_RECORDS){
                echo "Added $linecount records and closing file... <br />";
                $linecount = 0; // reset our line counter
                // close the existing file and open another;
                fclose($fp);
                // increment filecount
                $filecount++;
                echo "Creating file EP_Split" . $filecount . '.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt').' ...  ';
                $tmpfname = EP_TEMP_DIRECTORY . "EP_Split" . $filecount.'.'.((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt');
                //Open next file name
                $fp = fopen( $tmpfname, "w+");
                fwrite($fp, $toprow);
            }
        }
        $line=fgets($infp,32768);
    }
    echo "Added $linecount records and closing file...<br /><br /> ";
    fclose($fp);
    fclose($infp);
    echo "You can download your split files in the Tools/Files under " . EP_TEMP_DIRECTORY;
}
//*******************************
//*******************************
// MAIN MENU
//*******************************
//*******************************
?>
      </p>
           <form enctype="multipart/form-data" action="easypopulate.php?split=0<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>" method="post"><?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
                <b>Upload and Import EP File</b>
                  <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <input class="inputbox" name="usrfl" type="file" size="50">
                  <select name="imput_mode">
                      <option value="normal">Normal</option>
                      <option value="addnew">Add New Only</option>
                      <option value="update">Update Only</option>
                  </select>
                  <input class="button" type="submit" class="button"  name="buttoninsert" value="Insert into db">
              </form>
<hr />
           <form enctype="multipart/form-data" action="easypopulate.php<?php if (defined('SID') && tep_not_null(SID)) { echo '?'.tep_session_name().'='.tep_session_id(); } ?>" method="post"><?php if (defined('SID') && tep_not_null(SID)) { echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); } ?>
               <b>Import EP file from Temp Dir</b>
                  <?PHP
                    //<input type="text" name="localfile" size="50">
                    $the_array = Array();
                    if (is_readable(EP_TEMP_DIRECTORY)) {
                        $handle = opendir(EP_TEMP_DIRECTORY);
                        while (false!== ($file = readdir($handle))) {
                          if ($file!= "." && $file!= ".." &&!is_dir($file)) {
                           $namearr = explode('.',$file);
                           if ($namearr[count($namearr)-1] == ((EP_EXCEL_SAFE_OUTPUT==true)?'csv':'txt')) $the_array[] = $file;
                          }
                        }
                        closedir($handle);
                    }
                    $array_size = count($the_array);
                    if($array_size == 0){
                      echo ('            <input class="inputbox" type="text" name="localfile" size="50">' . "\n");
                    } else {
                      echo ('            <select class="inputbox"  name="localfile" size="1">' . "\n");
                      foreach ($the_array as $list){
                       echo ('                <option value="' . $list . '">' . $list . '</option>' . "\n");
                      }
                      echo ('            </select>' . "\n");
                    }
                    ?>
                  <select class="inputbox"  name="imput_mode">
                      <option value="normal">Normal</option>
                      <option value="addnew">Add New Only</option>
                      <option value="update">Update Only</option>
                  </select>
                  <input type="submit" class="button"  name="buttoninsert" value="Insert into db">
                  <br />
             </form>
<hr />
<table width="100%" border="0" cellpadding="3" cellspacing="3"><tr><td width="50%" valign="top" bgcolor="#EEEEEE">
<b>Create then Download Files</b><br />
     Create entire file in server memory then stream download after completed.
        <p><!-- Download file links -  Add your custom fields here -->
          <a href="easypopulate.php?download=stream&dltype=full<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>">Download <b>Complete<?php if (EP_SPPC_SUPPORT == true) { echo ' w/SPPC'; } ?></b> <?php echo ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"); ?> file to edit</a><br />
<?php if (EP_EXTRA_FIELDS_SUPPORT == true) { ?>
          <a href="easypopulate.php?download=stream&dltype=extra_fields<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>">Download <b>Extra Fields</b> <?php echo ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"); ?> file to edit</a><br />
<?php } ?>
          <!-- VJ product attributes begin //-->
          <?php if (EP_PRODUCTS_WITH_ATTRIBUTES == true) { ?>
          <?php } ?>
          <!-- VJ product attributes end //-->
        </p><br />
        </td><td width="50%" valign="top" bgcolor="#EEEEEE">
        <p style="margin-top: 8px;"><b>Create Files in Temp Dir</b><br />
        <font size="-2">Create entire file in server memory then save to Temp Dir after completed.</font></p>
        <p>
          <a href="easypopulate.php?download=tempfile&dltype=full<?php if (defined('SID') && tep_not_null(SID)) { echo '&'.tep_session_name().'='.tep_session_id(); } ?>">Create <b>Complete</b> <?php echo ((EP_EXCEL_SAFE_OUTPUT == true)?".csv":".txt"); ?> file in temp dir</a><br />
          <!-- VJ product attributes begin //-->
          <?php if (EP_PRODUCTS_WITH_ATTRIBUTES == true) { ?>
          <?php } ?>
          <!-- VJ product attributes end //-->
        </p><br />
        </td></tr></table>
          <?php if (EP_SHOW_EP_SETTINGS == true) { ?>
        <h3>Settings & Info</h3>
             <p>EP Vers: CartStore 4 / Based on Easy Populate<br />
             <?php if (defined('PROJECT_VERSION')) { echo PROJECT_VERSION . '<br>'; } ?>
             <?php echo 'OS: ' . $system['system'] . ' ' . $system['kernel'] . '<br>'; ?>
             <?php echo 'HTTP: ' . $system['http_server'] . '<br>'; ?>
             <?php echo 'DB: ' . $system['db_version'] . '<br>'; ?>
             <?php echo 'PHP: ' . $system['php'] . ' (Zend: ' . $system['zend'] . ')' . '<br>'; ?>
             <br />Temp Directory:<br />
             <?php echo EP_TEMP_DIRECTORY; ?><br />
             <?php if (is_writable(EP_TEMP_DIRECTORY)) { ?><font color="#009900">Temp Dir is Writable</font><?php } else { ?><font color="#FF0000">Temp Dir is NOT Writable</font><?php } ?><br />
             Magic Quotes is: <?php if (ini_get('magic_quotes_runtime') == 1){ echo 'on'; } else { echo 'off'; } ?><br />
             register_globals is: <?php if (ini_get(register_globals)) { echo 'on'; } else { echo 'off'; } ?><br />
             Split files on: <?php echo EP_SPLIT_MAX_RECORDS; ?> records<br />
             Model Num Size: <?php echo EP_MODEL_NUMBER_SIZE; ?><br />
             Price with tax: <?php echo (EP_PRICE_WITH_TAX?'true':'false'); ?><br />
             Calc Precision: <?php echo EP_PRECISION; ?><br />
             Replace quotes: <?php echo (EP_REPLACE_QUOTES?'true':'false'); ?><br />
             Field seperator:
             <?php switch ($ep_separator) {
               case "\t";
                 echo 'tab';
                 break;
               case ",";
                 echo 'comma';
                 break;
               case ";";
                 echo 'semi-colon';
                 break;
               case "~";
                 echo 'tilde';
                 break;
               case "-";
                 echo 'dash';
                 break;
               case "*";
                 echo 'splat';
                 break;
             }
             ?><br />
             Excel safe output: <?php echo (EP_EXCEL_SAFE_OUTPUT?'true':'false'); ?><br />
             Preserve tab/cr/lf: <?php echo (EP_PRESERVE_TABS_CR_LF?'true':'false'); ?><br />
             Category depth: <?php echo EP_MAX_CATEGORIES; ?><br />
             Enable attributes: <?php echo (EP_PRODUCTS_WITH_ATTRIBUTES?'true':'false'); ?><br />
             SEF Froogle URLS: <?php echo (EP_FROOGLE_SEF_URLS?'true':'false'); ?><br />
             More Pics: <?php echo (EP_MORE_PICS_6_SUPPORT?'true':'false'); ?><br />
             Unknown Pics: <?php echo (EP_UNKNOWN_ADD_IMAGES_SUPPORT?'true':'false'); ?><br />
             HTC: <?php echo (EP_HTC_SUPPORT?'true':'false'); ?><br />
             SPPC: <?php echo (EP_SPPC_SUPPORT?'true':'false'); ?><br />
             Extra Fields: <?php echo (EP_EXTRA_FIELDS_SUPPORT?'true':'false'); ?><br />
             <br />
             </td></tr></table>
          <?php } ?>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<p> </p>
<p> </p><p><br />
</p></body>
</html>
<?php
//*******************************
//*******************************
// end: MAIN MENU
//*******************************
//*******************************
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// ep_create_filelayout()
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function ep_create_filelayout($dltype, $attribute_options_array, $languages, $custom_fields){
    // depending on the type of the download the user wanted, create a file layout for it.
    $fieldmap = array(); // default to no mapping to change internal field names to external.
    // build filters
    $sql_filter = '';
    if (!empty($_GET['epcust_category_filter'])) {
      $sub_categories = array();
      $categories_query_addition = 'ptoc.categories_id = ' . (int)$_GET['epcust_category_filter'] . '';
      tep_get_sub_categories($sub_categories, $_GET['epcust_category_filter']);
      foreach ($sub_categories AS $ckey => $category ) {
        $categories_query_addition .= ' or ptoc.categories_id = ' . (int)$category . '';
      }
      $sql_filter .= ' and (' . $categories_query_addition . ')';
    }
    if ($_GET['epcust_manufacturer_filter']!='') {
      $sql_filter .= ' and p.manufacturers_id = ' . (int)$_GET['epcust_manufacturer_filter'];
    }
    if ($_GET['epcust_status_filter']!='') {
      $sql_filter .= ' and p.products_status = ' . (int)$_GET['epcust_status_filter'];
    }
    // /////////////////////////////////////////////////////////////////////
    //
    // Start: Support for other contributions
    //
    // /////////////////////////////////////////////////////////////////////
    $ep_additional_layout_product = '';
    $ep_additional_layout_product_select = '';
    $ep_additional_layout_product_description = '';
    $ep_additional_layout_pricing = '';
    if ( $dltype == 'full'){
        foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
          $ep_additional_layout_product .= '$filelayout[\'v_' . $key . '\'] = $iii++;
                                            ';
          $ep_additional_layout_product_select .= 'p.' . $key . ' as v_' . $key . ',';
        }
    }
 foreach ($custom_fields[TABLE_SPECIALS] as $key => $name) {
          $ep_additional_layout_specials .= '$filelayout[\'v_' . $key . '\'] = $iii++;
                                            ';
          $ep_additional_layout_specials_select .= 'p.' . $key . ' as v_' . $key . ',';
        }
    if (EP_MORE_PICS_6_SUPPORT == true) {
      $ep_additional_layout_product .= '$filelayout[\'v_products_subimage1\'] = $iii++;
                                        $filelayout[\'v_products_subimage2\'] = $iii++;
                                        $filelayout[\'v_products_subimage3\'] = $iii++;
                                        $filelayout[\'v_products_subimage4\'] = $iii++;
                                        $filelayout[\'v_products_subimage5\'] = $iii++;
                                        $filelayout[\'v_products_subimage6\'] = $iii++;
                                        ';
    }
    if (EP_MORE_PICS_6_SUPPORT == true) {
      $ep_additional_layout_product_select .= 'p.products_subimage1 as v_products_subimage1, p.products_subimage2 as v_products_subimage2, p.products_subimage3 as v_products_subimage3, p.products_subimage4 as v_products_subimage4, p.products_subimage5 as v_products_subimage5, p.products_subimage6 as v_products_subimage6,';
    }
    if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
      $ep_additional_layout_product .= '$filelayout[\'v_products_mimage\'] = $iii++;
                                        $filelayout[\'v_products_bimage\'] = $iii++;
                                        $filelayout[\'v_products_subimage1\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage1\'] = $iii++;
                                        $filelayout[\'v_products_subimage2\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage2\'] = $iii++;
                                        $filelayout[\'v_products_subimage3\'] = $iii++;
                                        $filelayout[\'v_products_bsubimage3\'] = $iii++;
                                        ';
    }
    if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
      $ep_additional_layout_product_select .= 'p.products_mimage as v_products_mimage, p.products_bimage as v_products_bimage, p.products_subimage1 as v_products_subimage1, p.products_bsubimage1 as v_products_bsubimage1, p.products_subimage2 as v_products_subimage2, p.products_bsubimage2 as v_products_bsubimage2, p.products_subimage3 as v_products_subimage3, p.products_bsubimage3 as v_products_bsubimage3,';
    }
    if (EP_SPPC_SUPPORT == true) {
      $ep_additional_layout_pricing .= '$filelayout[\'v_customer_price_1\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_1\'] = $iii++;
                                        $filelayout[\'v_customer_price_2\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_2\'] = $iii++;
                                        $filelayout[\'v_customer_price_3\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_3\'] = $iii++;
                                        $filelayout[\'v_customer_price_4\'] = $iii++;
                                        $filelayout[\'v_customer_group_id_4\'] = $iii++;
                                        ';
    }
    if (EP_HTC_SUPPORT == true) {
      $ep_additional_layout_product_description .= '$filelayout[\'v_products_head_title_tag_\'.$lang[\'id\']]    = $iii++;
                                                    $filelayout[\'v_products_head_desc_tag_\'.$lang[\'id\']]     = $iii++;
                                                    $filelayout[\'v_products_head_keywords_tag_\'.$lang[\'id\']] = $iii++;
                                                    ';
    }
    // /////////////////////////////////////////////////////////////////////
    // End: Support for other contributions
    // /////////////////////////////////////////////////////////////////////
    switch( $dltype ){
    case 'full':
        // The file layout is dynamically made depending on the number of languages
        $iii = 0;
        $filelayout = array();
        $filelayout['v_products_model'] = $iii++;
        foreach ($languages as $key => $lang){
            $filelayout['v_products_name_'.$lang['id']]        = $iii++;
            $filelayout['v_products_description_'.$lang['id']] = $iii++;
            $filelayout['v_products_url_'.$lang['id']]         = $iii++;
            foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
              $filelayout['v_' . $key . '_'.$lang['id']]         = $iii++;
            }
            if (!empty($ep_additional_layout_product_description)) {
              eval($ep_additional_layout_product_description);
            }
        }
        $filelayout['v_products_image'] = $iii++;
        if (!empty($ep_additional_layout_product)) {
          eval($ep_additional_layout_product);
        }
        $filelayout['v_products_price']    = $iii++;
        if (!empty($ep_additional_layout_pricing)) {
          eval($ep_additional_layout_pricing);
        }
        $filelayout['v_products_quantity'] = $iii++;
        $filelayout['v_products_weight']   = $iii++;
        $filelayout['v_date_avail']        = $iii++;
        $filelayout['v_date_added']        = $iii++;
		// build the categories name section of the array based on the number of categores the user wants to have
		for($i=1; $i<EP_MAX_CATEGORIES+1; $i++){
			$filelayout['v_categories_image_' . $i] = $iii++;
			foreach ($languages as $key => $lang){
				$filelayout['v_categories_name_' . $i . '_' . $lang['id']] = $iii++;
			}
		}
        $filelayout['v_manufacturers_name'] = $iii++;
		$filelayout['v_specials_new_products_price'] = $iii++;
        // VJ product attribs begin
        $attribute_options_count = 1;
        foreach ($attribute_options_array as $tkey => $attribute_options_values) {
            $filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
            foreach ($languages as $tkey => $lang ) {
                $filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
            }
            $attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
            $attribute_values_values = tep_db_query($attribute_values_query);
            $attribute_values_count = 1;
            while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
                $filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                foreach ($languages as $tkey => $lang ) {
                    $filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
                }
                $filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                //// attributes stock add start
                if ( EP_PRODUCTS_ATTRIBUTES_STOCK == true ) {
                    $filelayout['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                }
                //// attributes stock add end
                $attribute_values_count++;
             }
            $attribute_options_count++;
        }
        // VJ product attribs end
		// products_ymm start
		$check_ymm = tep_db_fetch_array(tep_db_query("select max(total) as records from (select count(products_id) as total from products_ymm group by products_id) a"));
		$total_ymm = $check_ymm['records'];
		for($jj = 1; $jj <= $total_ymm; $jj++){
			$filelayout['v_products_car_make_' . $jj]  = $iii++;
			$filelayout['v_products_car_model_' . $jj]  = $iii++;
			$filelayout['v_products_car_year_bof_' . $jj]  = $iii++;
			$filelayout['v_products_car_year_eof_' . $jj]  = $iii++;
		}
		// products_ymm end
        $filelayout['v_tax_class_title']  = $iii++;
        $filelayout['v_status']           = $iii++;
        $filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_image as v_products_image,
            $ep_additional_layout_product_select
            p.products_price as v_products_price,
            p.products_weight as v_products_weight,
            p.products_date_available as v_date_avail,
            p.products_date_added as v_date_added,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity,
            p.manufacturers_id as v_manufacturers_id,
            subc.categories_id as v_categories_id,
            p.products_status as v_status
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;
        break;
    case 'priceqty':
        $iii = 0;
        $filelayout = array();
        $filelayout['v_products_model']    = $iii++;
        $filelayout['v_products_price']    = $iii++;
        $filelayout['v_products_quantity'] = $iii++;
        if (!empty($ep_additional_layout_pricing)) {
          eval($ep_additional_layout_pricing);
        }
        $filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_price as v_products_price,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity
            FROM
            ".TABLE_PRODUCTS." as p
            ";
        break;
    case 'custom':
        $iii = 0;
        $filelayout = array();
        $filelayout['v_products_model'] = $iii++;
        if (!empty($_GET['epcust_status'])) { $filelayout['v_status'] = $iii++; }
        foreach ($languages as $key => $lang){
            if (!empty($_GET['epcust_name'])) { $filelayout['v_products_name_'.$lang['id']] = $iii++; }
            if (!empty($_GET['epcust_description'])) { $filelayout['v_products_description_'.$lang['id']] = $iii++; }
            if (!empty($_GET['epcust_url'])) { $filelayout['v_products_url_'.$lang['id']] = $iii++; }
            foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
              if (!empty($_GET['epcust_' . $key])) { $filelayout['v_' . $key . '_'.$lang['id']] = $iii++; }
            }
        }
        if (!empty($_GET['epcust_image'])) {
          $filelayout['v_products_image'] = $iii++;
          if (!empty($ep_additional_layout_product)) {
            eval($ep_additional_layout_product);
          }
        }
        foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
            if (!empty($_GET['epcust_' . $key])) {
                $filelayout['v_' . $key] = $iii++;
                $ep_additional_layout_product_select .= 'p.' . $key . ' as v_' . $key . ',';
            }
        }
foreach ($custom_fields[TABLE_SPECIALS] as $key => $name) {
            if (!empty($_GET['epcust_' . $key])) {
                $filelayout['v_' . $key] = $iii++;
                $ep_additional_layout_specials_select .= 'p.' . $key . ' as v_' . $key . ',';
            }
        }
        if (!empty($_GET['epcust_price'])) { $filelayout['v_products_price'] = $iii++; }
        if (!empty($_GET['epcust_quantity'])) { $filelayout['v_products_quantity'] = $iii++; }
        if (!empty($_GET['epcust_weight'])) { $filelayout['v_products_weight'] = $iii++; }
        if (!empty($_GET['epcust_avail'])) { $filelayout['v_date_avail'] = $iii++; }
        if (!empty($_GET['epcust_date_added'])) { $filelayout['v_date_added'] = $iii++; }
		if (!empty($_GET['epcust_category'])) {
		  // build the categories name section of the array based on the number
		  // of categores the user wants to have
		  for($i=1; $i<=EP_MAX_CATEGORIES; $i++){
			$filelayout['v_categories_image_'.$i] = $iii++;
			foreach ($languages as $key => $lang){
			  $filelayout['v_categories_name_'.$i.'_'.$lang['id']] = $iii++;
			}
		  }
		}
        if (!empty($_GET['epcust_manufacturer'])) { $filelayout['v_manufacturers_name'] = $iii++; }
        if (!empty($_GET['epcust_attributes'])) {
          // VJ product attribs begin
          $attribute_options_count = 1;
          foreach ($attribute_options_array as $tkey => $attribute_options_values) {
            $filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
            foreach ($languages as $tkey => $lang ) {
                $filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
            }
            $attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
            $attribute_values_values = tep_db_query($attribute_values_query);
            $attribute_values_count = 1;
            while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
                $filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                foreach ($languages as $tkey => $lang ) {
                    $filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
                }
                $filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                //// attributes stock add start
                if ( EP_PRODUCTS_ATTRIBUTES_STOCK == true ) {
                    $filelayout['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                }
                //// attributes stock add end
                $attribute_values_count++;
             }
            $attribute_options_count++;
          }
          // VJ product attribs end
        }
        if (!empty($_GET['epcust_tax_class'])) { $filelayout['v_tax_class_title'] = $iii++; }
        if (!empty($_GET['epcust_comment'])) { $filelayout['v_products_comment'] = $iii++; }
        $filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_status as v_status,
            p.products_price as v_products_price,
            p.products_quantity as v_products_quantity,
            p.products_weight as v_products_weight,
            p.products_image as v_products_image,
            $ep_additional_layout_product_select
            p.manufacturers_id as v_manufacturers_id,
            p.products_date_available as v_date_avail,
            p.products_date_added as v_date_added,
            p.products_tax_class_id as v_tax_class_id,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;
        break;
    case 'category':
        $iii = 0;
        $filelayout = array();
        $filelayout['v_products_model'] = $iii++;
		for($i=1; $i<EP_MAX_CATEGORIES+1; $i++){
			$filelayout['v_categories_image_'.$i] = $iii++;
			foreach ($languages as $key => $lang){
					$filelayout['v_categories_name_'.$i.'_'.$lang['id']] = $iii++;
			}
		}
        $filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            ";
        break;
    case 'extra_fields':
    // start EP for product extra field ============================= DEVSOFTVN - 10/20/2005
        $iii = 0;
        $filelayout = array(
            'v_products_model'        => $iii++,
            'v_products_extra_fields_name'        => $iii++,
            'v_products_extra_fields_id'        => $iii++,
//            'v_products_id'        => $iii++,
            'v_products_extra_fields_value'        => $iii++,
                        );
        $filelayout_sql = "SELECT
                        p.products_id as v_products_id,
                        p.products_model as v_products_model,
                        subc.products_extra_fields_id as v_products_extra_fields_id,
                        subc.products_extra_fields_value as v_products_extra_fields_value,
                        ptoc.products_extra_fields_name as v_products_extra_fields_name
                        FROM
                        ".TABLE_PRODUCTS." as p,
                        ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." as subc,
                        ".TABLE_PRODUCTS_EXTRA_FIELDS." as ptoc
                        WHERE
                        p.products_id = subc.products_id AND
                        ptoc.products_extra_fields_id = subc.products_extra_fields_id
                        ";
    // end of EP for extra field code ======= DEVSOFTVN================
        break;
    case 'froogle':
        // this is going to be a little interesting because we need
        // a way to map from internal names to external names
        //
        // Before it didn't matter, but with froogle needing particular headers,
        // The file layout is dynamically made depending on the number of languages
        $iii = 0;
        $filelayout = array();
        $filelayout['v_froogle_products_url_1'] = $iii++;
        $filelayout['v_froogle_products_name_'.EP_DEFAULT_LANGUAGE_ID] = $iii++;
        $filelayout['v_froogle_products_description_'.EP_DEFAULT_LANGUAGE_ID] = $iii++;
        $filelayout['v_products_price'] = $iii++;
        $filelayout['v_products_fullpath_image'] = $iii++;
        $filelayout['v_froogle_product_id'] = $iii++;
        $filelayout['v_froogle_quantitylevel'] = $iii++;
        $filelayout['v_category_fullpath'] = $iii++;
        $filelayout['v_froogle_exp_date'] = $iii++;
        $filelayout['v_froogle_currency'] = $iii++;
        $iii=0;
        $fileheaders = array();
        // EP Support mapping new names to the export headers.
        // use the $fileheaders[''] vars to do that.
        $fileheaders['link'] = $iii++;
        $fileheaders['title'] = $iii++;
        $fileheaders['description'] = $iii++;
        $fileheaders['price'] = $iii++;
        $fileheaders['image_link'] = $iii++;
        $fileheaders['id'] = $iii++;
        $fileheaders['quantity'] = $iii++;
        $fileheaders['product_type'] = $iii++;
        $fileheaders['expiration_date'] = $iii++;
        $fileheaders['currency'] = $iii++;
        $filelayout_sql = "SELECT
            p.products_id as v_products_id,
            p.products_model as v_products_model,
            p.products_image as v_products_image,
            p.products_price as v_products_price,
            p.products_weight as v_products_weight,
            p.products_date_added as v_date_added,
            p.products_date_available as v_date_avail,
            p.products_tax_class_id as v_tax_class_id,
            p.products_quantity as v_products_quantity,
            p.manufacturers_id as v_manufacturers_id,
            subc.categories_id as v_categories_id
            FROM
            ".TABLE_PRODUCTS." as p,
            ".TABLE_CATEGORIES." as subc,
            ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
            WHERE
            p.products_id = ptoc.products_id AND
            ptoc.categories_id = subc.categories_id
            " . $sql_filter;
        break;
    // VJ product attributes begin
    case 'attrib':
        $iii = 0;
        $filelayout = array();
        $filelayout['v_products_model'] = $iii++;
        $attribute_options_count = 1;
        foreach ($attribute_options_array as $tkey1 => $attribute_options_values) {
            $filelayout['v_attribute_options_id_'.$attribute_options_count] = $iii++;
            foreach ($languages as $tkey => $lang ) {
                $filelayout['v_attribute_options_name_'.$attribute_options_count.'_'.$lang['id']] = $iii++;
            }
            $attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
            $attribute_values_values = tep_db_query($attribute_values_query);
            $attribute_values_count = 1;
            while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
                $filelayout['v_attribute_values_id_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                foreach ($languages as $tkey2 => $lang ) {
                    $filelayout['v_attribute_values_name_'.$attribute_options_count.'_'.$attribute_values_count.'_'.$lang['id']] = $iii++;
                }
                $filelayout['v_attribute_values_price_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                //// attributes stock add start
                if ( EP_PRODUCTS_ATTRIBUTES_STOCK    == true ) {
                    $header_array['v_attribute_values_stock_'.$attribute_options_count.'_'.$attribute_values_count] = $iii++;
                }
                //// attributes stock add end
                $attribute_values_count++;
            }
            $attribute_options_count++;
        }
        $filelayout_sql = "SELECT
                            p.products_id as v_products_id,
                            p.products_model as v_products_model
                            FROM
                            ".TABLE_PRODUCTS." as p
                            ";
        break;
        // VJ product attributes end
    }
    $filelayout_count = count($filelayout);
  return array($filelayout, $filelayout_count, $filelayout_sql, $fileheaders);
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//
// process_row()
//
//   Processes one row of the import file
//
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function process_row( $item1, $filelayout, $filelayout_count, $default_these, $ep_separator, $languages, $custom_fields ) {
    // first we clean up the row of data
    if (EP_EXCEL_SAFE_OUTPUT == true) {
      $items = $item1;
    } else {
      // chop blanks from each end
      $item1 = ltrim(rtrim($item1));
      // blow it into an array, splitting on the tabs
      $items = explode($ep_separator, $item1);
    }
    // make sure all non-set things are set to '';
    // and strip the quotes from the start and end of the stings.
    // escape any special chars for the database.
    foreach( $filelayout as $key => $value){
        $i = $filelayout[$key];
        if (isset($items[$i]) == false) {
            $items[$i]='';
        } else {
            // Check to see if either of the magic_quotes are turned on or off;
            // And apply filtering accordingly.
            if (function_exists('ini_get')) {
                //echo "Getting ready to check magic quotes<br />";
                if (ini_get('magic_quotes_runtime') == 1){
                    // The magic_quotes_runtime are on, so lets account for them
                    // check if the first & last character are quotes;
                    // if it is, chop off the quotes.
                    if (substr($items[$i],-1) == '"' && substr($items[$i],0,1) == '"'){
                        $items[$i] = substr($items[$i],2,strlen($items[$i])-4);
                    }
                    // now any remaining doubled double quotes should be converted to one doublequote
                    if (EP_REPLACE_QUOTES == true){
                        if (EP_EXCEL_SAFE_OUTPUT == true) {
                          $items[$i] = str_replace('\"\"',"&#34;",$items[$i]);
                        }
                     //   $items[$i] = str_replace('\"',"&#34;",$items[$i]);
                        $items[$i] = str_replace("\'","&#39;",$items[$i]);
                    }
                } else { // no magic_quotes are on
                    // check if the last character is a quote;
                    // if it is, chop off the 1st and last character of the string.
                    if (substr($items[$i],-1) == '"' && substr($items[$i],0,1) == '"'){
                        $items[$i] = substr($items[$i],1,strlen($items[$i])-2);
                    }
                    // now any remaining doubled double quotes should be converted to one doublequote
                    if (EP_REPLACE_QUOTES == true){
                        if (EP_EXCEL_SAFE_OUTPUT == true) {
                          $items[$i] = str_replace('""',"&#34;",$items[$i]);
                        }
                     //   $items[$i] = str_replace('"',"&#34;",$items[$i]);
					//	$items[$i] = str_replace('""',"&#34;",$items[$i]);
                        $items[$i] = str_replace('""','"',$items[$i]);
						 // $items[$i] = str_replace('""','&#39;',$items[$i]);
                        $items[$i] = str_replace("'","&#39;",$items[$i]);
                    }
                }
            }
        }
    }
    // /////////////////////////////////////////////////////////////
    // Do specific functions without processing entire range of vars
    // /////////////////////////////
    // first do product extra fields
    if (isset($items[$filelayout['v_products_extra_fields_id']]) ){
        // EP for product extra fields Contrib by minhmaster DEVSOFTVN ==========
        $v_products_extra_fields_id = $items[$filelayout['v_products_extra_fields_id']];
		$v_products_model = $items[$filelayout['v_products_model']];
//        $v_products_id    =    $items[$filelayout['v_products_id']];
        $v_products_extra_fields_value    =    $items[$filelayout['v_products_extra_fields_value']];
        $sql = "SELECT p.products_id as v_products_id FROM ".TABLE_PRODUCTS." as p WHERE p.products_model = '" . $v_products_model . "'";
        $result = tep_db_query($sql);
        $row =  tep_db_fetch_array($result); 

        $v_products_id = $row['v_products_id']; $v_products_model = $row['v_products_model']; 
		$sql_exist	=	"SELECT products_extra_fields_value FROM ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS. " WHERE (products_id ='".$row['v_products_id']. "') AND (products_extra_fields_id ='".$v_products_extra_fields_id ."')";
		if (tep_db_num_rows(tep_db_query($sql_exist)) > 0) {
			$sql_extra_field	=	"UPDATE ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." SET products_extra_fields_value='".$v_products_extra_fields_value."' WHERE (products_id ='". $row['v_products_id'] . "') AND (products_extra_fields_id ='".$v_products_extra_fields_id ."')";
			$str_err_report= " $v_products_extra_fields_id | $v_products_id  | $v_products_model | $v_products_extra_fields_value | <b><font color=black>Product Extra Fields Updated</font></b><br />";
		} else {
			$sql_extra_field	=	"INSERT INTO ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS."(products_id,products_extra_fields_id,products_extra_fields_value) VALUES ('". $row['v_products_id'] ."','".$v_products_extra_fields_id."','".$v_products_extra_fields_value."')";
			$str_err_report= " $v_products_extra_fields_id | $v_products_id | $v_products_model | $v_products_extra_fields_value | <b><font color=green>Product Extra Fields Inserted</font></b><br />";
		}

        $result = tep_db_query($sql_extra_field);
        echo $str_err_report;
        // end (EP for product extra fields Contrib by minhmt DEVSOFTVN) ============
    // /////////////////////
    // or do product deletes
    } elseif ( $items[$filelayout['v_status']] == EP_DELETE_IT ) {
      // Get the ID
      $sql = "SELECT p.products_id as v_products_id    FROM ".TABLE_PRODUCTS." as p WHERE p.products_model = '" . $items[$filelayout['v_products_model']] . "'";
      $result = tep_db_query($sql);
      $row =  tep_db_fetch_array($result);
      if (tep_db_num_rows($result) == 1 ) {
        tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $row['v_products_id'] . "'");
        $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $row['v_products_id'] . "'");
        $product_categories = tep_db_fetch_array($product_categories_query);
        if ($product_categories['total'] == '0') {
           tep_remove_product($row['v_products_id']);
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
        echo "Deleted product " . $items[$filelayout['v_products_model']] . " from the database<br />";
      } else {
          echo "Did not delete " . $items['v_products_model'] . " since it is not unique ";
      }
    // //////////////////////////////////
    // or do regular product processing
    // //////////////////////////////////
    } else {
        // /////////////////////////////////////////////////////////////////////
        //
        // Start: Support for other contributions in products table
        //
        // /////////////////////////////////////////////////////////////////////
        $ep_additional_select = '';
        if (EP_MORE_PICS_6_SUPPORT == true) {
          $ep_additional_select .= 'p.products_subimage1 as v_products_subimage1,p.products_subimage2 as v_products_subimage2,p.products_subimage3 as v_products_subimage3,p.products_subimage4 as v_products_subimage4,p.products_subimage5 as v_products_subimage5,p.products_subimage6 as v_products_subimage6,';
        }
        if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
          $ep_additional_select .= 'p.products_mimage as v_products_mimage,p.products_bimage as v_products_bimage,p.products_subimage1 as v_products_subimage1,p.products_bsubimage1 as v_products_bsubimage1,p.products_subimage2 as v_products_subimage2,p.products_bsubimage2 as v_products_bsubimage2,p.products_subimage3 as v_products_subimage3,p.products_bsubimage3 as v_products_bsubimage3,';
        }
        foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
          $ep_additional_select .= 'p.' . $key . ' as v_' . $key . ',';
        }
        // /////////////////////////////////////////////////////////////////////
        // End: Support for other contributions in products table
        // /////////////////////////////////////////////////////////////////////
        // now do a query to get the record's current contents
        $sql = "SELECT
                    p.products_id as v_products_id,
                    p.products_model as v_products_model,
                    p.products_image as v_products_image,
                    $ep_additional_select
                    p.products_price as v_products_price,
                    p.products_weight as v_products_weight,
                    p.products_date_available as v_date_avail,
                    p.products_date_added as v_date_added,
                    p.products_tax_class_id as v_tax_class_id,
                    p.products_quantity as v_products_quantity,
                    p.manufacturers_id as v_manufacturers_id,
                    subc.categories_id as v_categories_id,
                    p.products_status as v_status_current
                FROM
                    ".TABLE_PRODUCTS." as p,
                    ".TABLE_CATEGORIES." as subc,
                    ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
                WHERE
                    p.products_model = '" . $items[$filelayout['v_products_model']] . "' AND
                    p.products_id = ptoc.products_id AND
                    ptoc.categories_id = subc.categories_id
                LIMIT 1
            ";
        $result = tep_db_query($sql);
        $row =  tep_db_fetch_array($result);
        // determine processing status based on dropdown choice on EP menu
        if ((sizeof($row) > 1) && ($_POST['imput_mode'] == "normal" || $_POST['imput_mode'] == "update")) {
            $process_product = true;
        } elseif ((sizeof($row) == 1) && ($_POST['imput_mode'] == "normal" || $_POST['imput_mode'] == "addnew")) {
            $process_product = true;
        } else {
            $process_product = false;
        }
        if ($process_product == true) {
            while ($row){
                // OK, since we got a row, the item already exists.
                // Let's get all the data we need and fill in all the fields that need to be defaulted
                // to the current values for each language, get the description and set the vals
                foreach ($languages as $key => $lang){
                    // products_name, products_description, products_url
                    $sql2 = "SELECT *
                            FROM ".TABLE_PRODUCTS_DESCRIPTION."
                            WHERE
                                products_id = " . $row['v_products_id'] . " AND
                                language_id = '" . $lang['id'] . "'
                            LIMIT 1
                            ";
                    $result2 = tep_db_query($sql2);
                    $row2 =  tep_db_fetch_array($result2);
                    // Need to report from ......_name_1 not ..._name_0
                    $row['v_products_name_' . $lang['id']]         = $row2['products_name'];
                    $row['v_products_description_' . $lang['id']]     = $row2['products_description'];
                    $row['v_products_url_' . $lang['id']]         = $row2['products_url'];
                    foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
                      $row['v_' . $key . '_' . $lang['id']]         = $row2[$key];
                    }
                    // header tags controller support
                    if(isset($filelayout['v_products_head_title_tag_' . $lang['id'] ])){
                        $row['v_products_head_title_tag_' . $lang['id']]     = $row2['products_head_title_tag'];
                        $row['v_products_head_desc_tag_' . $lang['id']]     = $row2['products_head_desc_tag'];
                        $row['v_products_head_keywords_tag_' . $lang['id']]     = $row2['products_head_keywords_tag'];
                    }
                    // end: header tags controller support
                }
                // start with v_categories_id
                // Get the category description
                // set the appropriate variable name
                // if parent_id is not null, then follow it up.
				$thecategory_id = $row['v_categories_id'];
				for( $categorylevel=1; $categorylevel<=(EP_MAX_CATEGORIES+1); $categorylevel++){
					if ($thecategory_id){
						$sql3 = "SELECT parent_id,
						                categories_image
							     FROM ".TABLE_CATEGORIES."
							     WHERE
								        categories_id = " . $thecategory_id . '';
						$result3 = tep_db_query($sql3);
						if ($row3 = tep_db_fetch_array($result3)) {
							$temprow['v_categories_image_' . $categorylevel] = $row3['categories_image'];
						}
						foreach ($languages as $key => $lang){
							$sql2 = "SELECT categories_name
								     FROM ".TABLE_CATEGORIES_DESCRIPTION."
								     WHERE
									        categories_id = " . $thecategory_id . " AND
									        language_id = " . $lang['id'];
							$result2 = tep_db_query($sql2);
							if ($row2 =  tep_db_fetch_array($result2)) {
								$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = $row2['categories_name'];
							}
						}
						// now get the parent ID if there was one
						$theparent_id = $row3['parent_id'];
						if ($theparent_id != ''){
							// there was a parent ID, lets set thecategoryid to get the next level
							$thecategory_id = $theparent_id;
						} else {
							// we have found the top level category for this item,
							$thecategory_id = false;
						}
					} else {
						$temprow['v_categories_image_' . $categorylevel] = '';
						foreach ($languages as $key => $lang){
							$temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] = '';
						}
					}
				}
                // temprow has the old style low to high level categories.
				$newlevel = 1;
				// let's turn them into high to low level categories
				for( $categorylevel=EP_MAX_CATEGORIES+1; $categorylevel>0; $categorylevel--){
					$found = false;
					if ($temprow['v_categories_image_' . $categorylevel] != ''){
						$row['v_categories_image_' . $newlevel] = $temprow['v_categories_image_' . $categorylevel];
						$found = true;
					}
					foreach ($languages as $key => $lang){
						if ($temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']] != ''){
							$row['v_categories_name_' . $newlevel . '_' . $lang['id']] = $temprow['v_categories_name_' . $categorylevel . '_' . $lang['id']];
							$found = true;
						}
					}
					if ($found == true) {
						$newlevel++;
					}
				}
				// default the manufacturer
                if ($row['v_manufacturers_id'] != ''){
                    $sql2 = "SELECT manufacturers_name
                        FROM ".TABLE_MANUFACTURERS."
                        WHERE
                        manufacturers_id = " . $row['v_manufacturers_id']
                        ;
                    $result2 = tep_db_query($sql2);
                    $row2 =  tep_db_fetch_array($result2);
                    $row['v_manufacturers_name'] = $row2['manufacturers_name'];
                }
                //elari -
                //We check the value of tax class and title instead of the id
                //Then we add the tax to price if EP_PRICE_WITH_TAX is set to true
                $row_tax_multiplier = tep_get_tax_class_rate($row['v_tax_class_id']);
                $row['v_tax_class_title'] = tep_get_tax_class_title($row['v_tax_class_id']);
                if (EP_PRICE_WITH_TAX == true){
                    $row['v_products_price'] = $row['v_products_price'] + round(($row['v_products_price'] * $row_tax_multiplier / 100), EP_PRECISION);
                }
                // now create the internal variables that will be used
                // the $$thisvar is on purpose: it creates a variable named what ever was in $thisvar and sets the value
                foreach ($default_these as $tkey => $thisvar){
                    $$thisvar = $row[$thisvar];
                }
                $row =  tep_db_fetch_array($result);
            }
            // this is an important loop.  What it does is go thru all the fields in the incoming
            // file and set the internal vars. Internal vars not set here are either set in the
            // loop above for existing records, or not set at all (null values) the array values
            // are handled separatly, although they will set variables in this loop, we won't use them.
            foreach( $filelayout as $key => $value ){
                $$key = $items[ $value ];
            }
            //elari... we get the tax_clas_id from the tax_title
            //on screen will still be displayed the tax_class_title instead of the id....
            if ( isset( $v_tax_class_title) ){
                $v_tax_class_id          = tep_get_tax_title_class_id($v_tax_class_title);
            }
            //we check the tax rate of this tax_class_id
                $row_tax_multiplier = tep_get_tax_class_rate($v_tax_class_id);
            //And we recalculate price without the included tax...
            //Since it seems display is made before, the displayed price will still include tax
            //This is same problem for the tax_clas_id that display tax_class_title
            if (EP_PRICE_WITH_TAX == true){
                $v_products_price = round( $v_products_price / (1 + ($row_tax_multiplier * .01)), EP_PRECISION);
            }
            // if they give us one category, they give us all categories. convert data structure to a multi-dim array
            unset ($v_categories_name); // default to not set.
            unset ($v_categories_image); // default to not set.
			foreach ($languages as $key => $lang){
			  $baselang_id = $lang['id'];
			  break;
			}
            if ( isset( $filelayout['v_categories_name_1_' . $baselang_id] ) ){
				$v_categories_name = array();
				$v_categories_image = array();
                $newlevel = 1;
                for( $categorylevel=EP_MAX_CATEGORIES; $categorylevel>0; $categorylevel--){
					$found = false;
					if ($items[$filelayout['v_categories_image_' . $categorylevel]] != '') {
					  $v_categories_image[$newlevel] = $items[$filelayout['v_categories_image_' . $categorylevel]];
					  $found = true;
					}
					foreach ($languages as $key => $lang){
						if ( $items[$filelayout['v_categories_name_' . $categorylevel . '_' . $lang['id']]] != ''){
							$v_categories_name[$newlevel][$lang['id']] = $items[$filelayout['v_categories_name_' . $categorylevel . '_' . $lang['id']]];
							$found = true;
						}
                    }
					if ($found == true) {
					  $newlevel++;
					}
                }
                while( $newlevel < EP_MAX_CATEGORIES+1){
                    $v_categories_image[$newlevel] = ''; // default the remaining items to nothing
					foreach ($languages as $key => $lang){
                      $v_categories_name[$newlevel][$lang['id']] = ''; // default the remaining items to nothing
					}
                    $newlevel++;
                }
            }
            if (ltrim(rtrim($v_products_quantity)) == '') {
                $v_products_quantity = 1;
            }
            if (empty($v_date_avail)) {
                $v_date_avail = "'" . '0000-00-00 00:00:00' . "'";
            } else {
                $v_date_avail = "'" . date("Y-m-d H:i:s",strtotime($v_date_avail)) . "'";
            }
            if (empty($v_date_added)) {
                $v_date_added = "'" . date("Y-m-d H:i:s") . "'";
            } else {
                $v_date_added = "'" . date("Y-m-d H:i:s",strtotime($v_date_added)) . "'";
            }
            // default the stock if they spec'd it or if it's blank
            if (isset($v_status_current)){
              $v_db_status = strval($v_status_current); // default to current value
            } else {
              $v_db_status = '1'; // default to active
            }
            if (trim($v_status) == EP_TEXT_INACTIVE){
                // they told us to deactivate this item
                $v_db_status = '0';
            } elseif (trim($v_status) == EP_TEXT_ACTIVE) {
                $v_db_status = '1';
            }
            if (EP_INACTIVATE_ZERO_QUANTITIES == true && $v_products_quantity == 0) {
                // if they said that zero qty products should be deactivated, let's deactivate if the qty is zero
                $v_db_status = '0';
            }
            if ($v_manufacturer_id==''){
                $v_manufacturer_id="NULL";
            }
            if (trim($v_products_image)==''){
                $v_products_image = EP_DEFAULT_IMAGE_PRODUCT;
            }
            if (strlen($v_products_model) > EP_MODEL_NUMBER_SIZE ){
                echo "<font color='red'>" . strlen($v_products_model) . $v_products_model . "... ERROR! - Too many characters in the model number.<br />
                    12 is the maximum on a standard OSC install.<br />
                    Your maximum product_model length is set to ".EP_MODEL_NUMBER_SIZE.".<br />
                    You can either shorten your model numbers or increase the size of the<br />
                    \"products_model\" field of the \"products\" table in the database.<br />
                    Then change the setting at the top of the easypopulate.php file.</font>";
                die();
            }
            // OK, we need to convert the manufacturer's name into id's for the database
            if ( isset($v_manufacturers_name) && $v_manufacturers_name != '' ){
                $sql = "SELECT man.manufacturers_id
                    FROM ".TABLE_MANUFACTURERS." as man
                    WHERE
                        man.manufacturers_name = '" . tep_db_input($v_manufacturers_name) . "'";
                $result = tep_db_query($sql);
                $row =  tep_db_fetch_array($result);
                if ( $row != '' ){
                    foreach( $row as $item ){
                        $v_manufacturer_id = $item;
                    }
                } else {
                    // to add, we need to put stuff in categories and categories_description
                    $sql = "SELECT MAX( manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
                    $result = tep_db_query($sql);
                    $row =  tep_db_fetch_array($result);
                    $max_mfg_id = $row['max']+1;
                    // default the id if there are no manufacturers yet
                    if (!is_numeric($max_mfg_id) ){
                        $max_mfg_id=1;
                    }
                    // Uncomment this query if you have an older 2.2 codebase
                    /*
                    $sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
                        manufacturers_id,
                        manufacturers_name,
                        manufacturers_image
                        ) VALUES (
                        $max_mfg_id,
                        '$v_manufacturers_name',
                        '".EP_DEFAULT_IMAGE_MANUFACTURER."'
                        )";
                    */
                    // Comment this query out if you have an older 2.2 codebase
                    $sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
                        manufacturers_id,
                        manufacturers_name,
                        manufacturers_image,
                        date_added,
                        last_modified
                        ) VALUES (
                        $max_mfg_id,
                        '".tep_db_input($v_manufacturers_name)."',
                        '".EP_DEFAULT_IMAGE_MANUFACTURER."',
                        '".date("Y-m-d H:i:s")."',
                        '".date("Y-m-d H:i:s")."'
                        )";
                    $result = tep_db_query($sql);
                    $v_manufacturer_id = $max_mfg_id;
                    $sql = "INSERT INTO ".TABLE_MANUFACTURERS_INFO."(
                        manufacturers_id,
                        manufacturers_url,
                        languages_id
                        ) VALUES (
                        $max_mfg_id,
                        '',
                        '".EP_DEFAULT_LANGUAGE_ID."'
                        )";
                    $result = tep_db_query($sql);
                }
            }
            // if the categories names are set then try to update them
			foreach ($languages as $key => $lang){
			  $baselang_id = $lang['id'];
			  break;
			}
            if ( isset( $filelayout['v_categories_name_1_' . $baselang_id] ) ){
                // start from the highest possible category and work our way down from the parent
                $v_categories_id = 0;
                $theparent_id = 0;
                for ( $categorylevel=EP_MAX_CATEGORIES+1; $categorylevel>0; $categorylevel-- ){
				  //foreach ($languages as $key => $lang){
                    $thiscategoryname = $v_categories_name[$categorylevel][$baselang_id];
                    if ( $thiscategoryname != ''){
                        // we found a category name in this field, look for database entry
                        $sql = "SELECT cat.categories_id
                            FROM ".TABLE_CATEGORIES." as cat,
                                 ".TABLE_CATEGORIES_DESCRIPTION." as des
                            WHERE
                                cat.categories_id = des.categories_id AND
                                des.language_id = " . $lang['id'] . " AND
                                cat.parent_id = " . $theparent_id . " AND
                                des.categories_name like '" . tep_db_input($thiscategoryname) . "'";
                        $result = tep_db_query($sql);
                        $row =  tep_db_fetch_array($result);
                        if ( $row != '' ){
                            // we have an existing category, update image and date
							foreach( $row as $item ){
                                $thiscategoryid = $item;
                            }
                            $query = "UPDATE ".TABLE_CATEGORIES."
                                      SET
                                        categories_image='".tep_db_input($v_categories_image[$categorylevel])."',
                                        last_modified = '".date("Y-m-d H:i:s")."'
                                      WHERE
                                        categories_id = '".$row['categories_id']."'
                                      LIMIT 1";
                            tep_db_query($query);
                        } else {
                            // to add, we need to put stuff in categories and categories_description
                            $sql = "SELECT MAX( categories_id) max FROM ".TABLE_CATEGORIES;
                            $result = tep_db_query($sql);
                            $row =  tep_db_fetch_array($result);
                            $max_category_id = $row['max']+1;
                            if (!is_numeric($max_category_id) ){
                                $max_category_id=1;
                            }
                            $sql = "INSERT INTO ".TABLE_CATEGORIES." (
                                        categories_id,
                                        parent_id,
                                        categories_image,
                                        sort_order,
                                        date_added,
                                        last_modified
                                   ) VALUES (
                                        $max_category_id,
                                        $theparent_id,
                                        '".tep_db_input($v_categories_image[$categorylevel])."',
                                        0,
                                        '".date("Y-m-d H:i:s")."',
                                        '".date("Y-m-d H:i:s")."'
                                   )";
                            $result = tep_db_query($sql);
                            foreach ($languages as $key => $lang){
                                $sql = "INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION." (
                                                categories_id,
                                                language_id,
                                                categories_name
                                       ) VALUES (
                                                $max_category_id,
                                                '".$lang['id']."',
                                                '".(!empty($v_categories_name[$categorylevel][$lang['id']])?tep_db_input($v_categories_name[$categorylevel][$lang['id']]):'')."'
                                       )";
                                tep_db_query($sql);
                            }
                            $thiscategoryid = $max_category_id;
                        }
                        // the current catid is the next level's parent
                        $theparent_id = $thiscategoryid;
                        $v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
                    }
				 // }
                }
            }
            if ($v_products_model != "") {
                //   products_model exists!
                foreach ($items as $tkey => $item) {
                  print_el($item);
                }
                // process the PRODUCTS table
                $result = tep_db_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE (products_model = '". $v_products_model . "')");
                // First we check to see if this is a product in the current db.
                if (tep_db_num_rows($result) == 0)  {
                    //   insert into products
                    echo "<font color='green'> !New Product!</font><br />";
                    // /////////////////////////////////////////////////////////////////////
                    //
                    // Start: Support for other contributions
                    //
                    // /////////////////////////////////////////////////////////////////////
                    $ep_additional_fields = '';
                    $ep_additional_data = '';
                    foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                      $ep_additional_fields .= $key . ',';
                    }
                    foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                      $tmp_var = 'v_' . $key;
                      $ep_additional_data .= "'" . $$tmp_var . "',";
                    }
                    if (EP_MORE_PICS_6_SUPPORT == true) {
                      $ep_additional_fields .= 'products_subimage1,products_subimage2,products_subimage3,products_subimage4,products_subimage5,products_subimage6,';
                      $ep_additional_data .= "'$v_products_subimage1','$v_products_subimage2','$v_products_subimage3','$v_products_subimage4','$v_products_subimage5','$v_products_subimage6',";
                    }
                    if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
                      $ep_additional_fields .= 'products_mimage,products_bimage,products_subimage1,products_bsubimage1,products_subimage2,products_bsubimage2,products_subimage3,products_bsubimage3,';
                      $ep_additional_data .= "'$v_products_mimage','$v_products_bimage','$v_products_subimage1','$v_products_bsubimage1','$v_products_subimage2','$v_products_bsubimage2','$v_products_subimage3','$v_products_bsubimage3',";
                    }
                    // /////////////////////////////////////////////////////////////////////
                    // End: Support for other contributions
                    // /////////////////////////////////////////////////////////////////////
                    $query = "INSERT INTO ".TABLE_PRODUCTS." (
                                products_image,
                                $ep_additional_fields
                                products_model,
                                products_price,
                                products_status,
                                products_last_modified,
                                products_date_added,
                                products_date_available,
                                products_tax_class_id,
                                products_weight,
                                products_quantity,
                                manufacturers_id )
                              VALUES (
                                ".(!empty($v_products_image)?"'".$v_products_image."'":'NULL').",
                                $ep_additional_data
                                '$v_products_model',
                                '$v_products_price',
                                '$v_db_status',
                                '".date("Y-m-d H:i:s")."',
                                ".$v_date_added.",
                                ".$v_date_avail.",
                                '$v_tax_class_id',
                                '$v_products_weight',
                                '$v_products_quantity',
                                ".(!empty($v_manufacturer_id)?$v_manufacturer_id:'NULL').")
                                ";
                    $result = tep_db_query($query);
                    $v_products_id = tep_db_insert_id();
                } else {
                  // existing product(s), get the id from the query
                  // and update the product data
                  while ($row = tep_db_fetch_array($result)) {
                    $v_products_id = $row['products_id'];
                    echo "<font color='black'> Updated</font><br />";
                    // /////////////////////////////////////////////////////////////////////
                    //
                    // Start: Support for other contributions
                    //
                    // /////////////////////////////////////////////////////////////////////
                    $ep_additional_updates = '';
                    foreach ($custom_fields[TABLE_PRODUCTS] as $key => $name) {
                      $tmp_var = 'v_' . $key;
                      $ep_additional_updates .= $key . "='" . $$tmp_var . "',";
                    }
                    if (EP_MORE_PICS_6_SUPPORT == true) {
                      $ep_additional_updates .= "products_subimage1='$v_products_subimage1',products_subimage2='$v_products_subimage2',products_subimage3='$v_products_subimage3',products_subimage4='$v_products_subimage4',products_subimage5='$v_products_subimage5',products_subimage6='$v_products_subimage6',";
                    }
                    if (EP_UNKNOWN_ADD_IMAGES_SUPPORT == true) {
                      $ep_additional_updates .= "products_mimage='$v_products_mimage',products_bimage='$v_products_bimage',products_subimage1='$v_products_subimage1',products_bsubimage1='$v_products_bsubimage1',products_subimage2='$v_products_subimage2',products_bsubimage2='$v_products_bsubimage2',products_subimage3='$v_products_subimage3',products_bsubimage3='$v_products_bsubimage3',";
                    }
                    // /////////////////////////////////////////////////////////////////////
                    // End: Support for other contributions
                    // /////////////////////////////////////////////////////////////////////
                    $query = "UPDATE ".TABLE_PRODUCTS."
                              SET
                                products_price='$v_products_price',
                                products_image=".(!empty($v_products_image)?"'".$v_products_image."'":'NULL').",
                                $ep_additional_updates
                                products_weight='$v_products_weight',
                                products_tax_class_id='$v_tax_class_id',
                                products_date_available=".$v_date_avail.",
                                products_date_added=".$v_date_added.",
                                products_last_modified='".date("Y-m-d H:i:s")."',
                                products_quantity = $v_products_quantity,
                                manufacturers_id = ".(!empty($v_manufacturer_id)?$v_manufacturer_id:'NULL').",
                                products_status = $v_db_status
                              WHERE
                                (products_id = $v_products_id)
                              LIMIT 1";
                    tep_db_query($query);
                  }
                }
         if ( isset($v_specials_new_products_price) && $v_specials_new_products_price != '' ){
		  $result = tep_db_query("SELECT products_id FROM ".TABLE_SPECIALS." WHERE products_id = '". $v_products_id . "'");
           if (tep_db_num_rows($result) == 0)  {
		  $sql = "INSERT INTO ".TABLE_SPECIALS."(
                        products_id,
                        specials_new_products_price) VALUES (
                        $v_products_id,$v_specials_new_products_price)";
						$result = tep_db_query($sql);
			}else
			{
			 $sql = "update ".TABLE_SPECIALS." set specials_new_products_price=$v_specials_new_products_price where products_id=$v_products_id";
			$result = tep_db_query($sql);
			}
		 }
                // process the PRODUCTS_DESCRIPTION table
                foreach ($languages as $tkey => $lang){
                    $doit = false;
                    foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
                      if (isset($filelayout['v_' . $key . '_'.$lang['id']])) { $doit = true; }
                    }
                    if ( isset($filelayout['v_products_name_'.$lang['id']]) || isset($filelayout['v_products_description_'.$lang['id']]) || isset($filelayout['v_products_url_'.$lang['id']]) || isset($filelayout['v_products_head_title_tag_'.$lang['id']]) || $doit == true ) {
                        $sql = "SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE
                                products_id = $v_products_id AND
                                language_id = " . $lang['id'];
                        $result = tep_db_query($sql);
                        $products_var = 'v_products_name_'.$lang['id'];
                        $description_var = 'v_products_description_'.$lang['id'];
                        $url_var = 'v_products_url_'.$lang['id'];
                        // /////////////////////////////////////////////////////////////////////
                        //
                        // Start: Support for other contributions
                        //
                        // /////////////////////////////////////////////////////////////////////
                        $ep_additional_updates = '';
                        $ep_additional_fields = '';
                        $ep_additional_data = '';
                        foreach ($custom_fields[TABLE_PRODUCTS_DESCRIPTION] as $key => $name) {
                          $tmp_var = 'v_' . $key . '_' . $lang['id'];
                          $ep_additional_updates .= $key . " = '" . tep_db_input($$tmp_var) ."',";
                          $ep_additional_fields .= $key . ",";
                          $ep_additional_data .= "'". tep_db_input($$tmp_var) . "',";
                        }
                        // header tags controller support
                        if (isset($filelayout['v_products_head_title_tag_'.$lang['id']])){
                          $head_title_tag_var = 'v_products_head_title_tag_'.$lang['id'];
                          $head_desc_tag_var = 'v_products_head_desc_tag_'.$lang['id'];
                          $head_keywords_tag_var = 'v_products_head_keywords_tag_'.$lang['id'];
                          $ep_additional_updates .= "products_head_title_tag = '" . tep_db_input($$head_title_tag_var) ."', products_head_desc_tag = '" . tep_db_input($$head_desc_tag_var) ."', products_head_keywords_tag = '" . tep_db_input($$head_keywords_tag_var) ."',";
                          $ep_additional_fields .= "products_head_title_tag,products_head_desc_tag,products_head_keywords_tag,";
                          $ep_additional_data .= "'". tep_db_input($$head_title_tag_var) . "','". tep_db_input($$head_desc_tag_var) . "','". tep_db_input($$head_keywords_tag_var) . "',";
                        }
                        // end: header tags controller support
                        // /////////////////////////////////////////////////////////////////////
                        // End: Support for other contributions
                        // /////////////////////////////////////////////////////////////////////
                        // existing product?
                        if (tep_db_num_rows($result) > 0) {
                            // already in the description, let's just update it
                            $sql =
                                "UPDATE ".TABLE_PRODUCTS_DESCRIPTION."
                                 SET
                                    products_name='" . tep_db_input($$products_var) . "',
                                    products_description='" . tep_db_input($$description_var) . "',
                                    $ep_additional_updates
                                    products_url='" . $$url_var . "'
                                 WHERE
                                    products_id = '$v_products_id' AND
                                    language_id = '".$lang['id']."'
                                 LIMIT 1";
                            $result = tep_db_query($sql);
                        } else {
                            // nope, this is a new product description
                            $result = tep_db_query($sql);
                            $sql =
                                "INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
                                    ( products_id,
                                      language_id,
                                      products_name,
                                      products_description,
                                      $ep_additional_fields
                                      products_url
                                    )
                                 VALUES (
                                        '" . $v_products_id . "',
                                        " . $lang['id'] . ",
                                        '". tep_db_input($$products_var) . "',
                                        '". tep_db_input($$description_var) . "',
                                        $ep_additional_data
                                        '". $$url_var . "'
                                        )";
                            $result = tep_db_query($sql);
                        }
                    }
                }
                if (isset($v_categories_id)){
                    //find out if this product is listed in the category given
                    $result_incategory = tep_db_query('SELECT
                                '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id,
                                '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id
                                FROM
                                    '.TABLE_PRODUCTS_TO_CATEGORIES.'
                                WHERE
                                '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id='.$v_products_id.' AND
                                '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id='.$v_categories_id);
                    if (tep_db_num_rows($result_incategory) == 0) {
                        // nope, this is a new category for this product
                        $res1 = tep_db_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id)
                                              VALUES ("' . $v_products_id . '", "' . $v_categories_id . '")');
                    } else {
                        // already in this category, nothing to do!
                    }
                }
                // for the separate prices per customer (SPPC) module
                $ll=1;
                if (isset($v_customer_price_1)){
                    if (($v_customer_group_id_1 == '') AND ($v_customer_price_1 != ''))  {
                        echo "<font color=red>ERROR - v_customer_group_id and v_customer_price must occur in pairs</font>";
                        die();
                    }
                    // they spec'd some prices, so clear all existing entries
                    $result = tep_db_query('
                                DELETE
                                FROM
                                    '.TABLE_PRODUCTS_GROUPS.'
                                WHERE
                                    products_id = ' . $v_products_id
                                );
                    // and insert the new record
                    if ($v_customer_price_1 != ''){
                        $result = tep_db_query('
                                    INSERT INTO
                                        '.TABLE_PRODUCTS_GROUPS.'
                                    VALUES
                                    (
                                        ' . $v_customer_group_id_1 . ',
                                        ' . $v_customer_price_1 . ',
                                        ' . $v_products_id . '
                                        )'
                                    );
                    }
                    if ($v_customer_price_2 != ''){
                        $result = tep_db_query('
                                    INSERT INTO
                                        '.TABLE_PRODUCTS_GROUPS.'
                                    VALUES
                                    (
                                        ' . $v_customer_group_id_2 . ',
                                        ' . $v_customer_price_2 . ',
                                        ' . $v_products_id . '
                                        )'
                                    );
                    }
                    if ($v_customer_price_3 != ''){
                        $result = tep_db_query('
                                    INSERT INTO
                                        '.TABLE_PRODUCTS_GROUPS.'
                                    VALUES
                                    (
                                        ' . $v_customer_group_id_3 . ',
                                        ' . $v_customer_price_3 . ',
                                        ' . $v_products_id . '
                                        )'
                                    );
                    }
                    if ($v_customer_price_4 != ''){
                        $result = tep_db_query('
                                    INSERT INTO
                                        '.TABLE_PRODUCTS_GROUPS.'
                                    VALUES
                                    (
                                        ' . $v_customer_group_id_4 . ',
                                        ' . $v_customer_price_4 . ',
                                        ' . $v_products_id . '
                                        )'
                                    );
                    }
                }
                // end: separate prices per customer (SPPC) module
                // VJ product attribs begin
                if (isset($v_attribute_options_id_1)){
                    $attribute_rows = 1; // master row count
                    // product options count
                    $attribute_options_count = 1;
                    $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;
                    while (isset($$v_attribute_options_id_var) && !empty($$v_attribute_options_id_var)) {
                        // remove product attribute options linked to this product before proceeding further
                        // this is useful for removing attributes linked to a product
                        $attributes_clean_query = "delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "'";
                        tep_db_query($attributes_clean_query);
                        $attribute_options_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "'";
                        $attribute_options_values = tep_db_query($attribute_options_query);
                        // option table update begin
                        if ($attribute_rows == 1) {
                            // insert into options table if no option exists
                            if (tep_db_num_rows($attribute_options_values) <= 0) {
                                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                    $lid = $languages[$i]['id'];
                                  $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;
                                    if (isset($$v_attribute_options_name_var)) {
                                        $attribute_options_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";
                                        $attribute_options_insert = tep_db_query($attribute_options_insert_query);
                                    }
                                }
                            } else { // update options table, if options already exists
                                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                    $lid = $languages[$i]['id'];
                                    $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;
                                    if (isset($$v_attribute_options_name_var)) {
                                        $attribute_options_update_lang_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "' and language_id ='" . (int)$lid . "'";
                                        $attribute_options_update_lang_values = tep_db_query($attribute_options_update_lang_query);
                                        // if option name doesn't exist for particular language, insert value
                                        if (tep_db_num_rows($attribute_options_update_lang_values) <= 0) {
                                            $attribute_options_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";
                                            $attribute_options_lang_insert = tep_db_query($attribute_options_lang_insert_query);
                                        } else { // if option name exists for particular language, update table
                                            $attribute_options_update_query = "update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $$v_attribute_options_name_var . "' where products_options_id ='" . (int)$$v_attribute_options_id_var . "' and language_id = '" . (int)$lid . "'";
                                            $attribute_options_update = tep_db_query($attribute_options_update_query);
                                        }
                                    }
                                }
                            }
                        }
                        // option table update end
                        // product option values count
                        $attribute_values_count = 1;
                        $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
                        while (isset($$v_attribute_values_id_var) && !empty($$v_attribute_values_id_var)) {
                            $attribute_values_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "'";
                            $attribute_values_values = tep_db_query($attribute_values_query);
                            // options_values table update begin
                            if ($attribute_rows == 1) {
                                // insert into options_values table if no option exists
                                if (tep_db_num_rows($attribute_values_values) <= 0) {
                                    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                        $lid = $languages[$i]['id'];
                                        $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;
                                        if (isset($$v_attribute_values_name_var)) {
                                            $attribute_values_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . tep_db_input($$v_attribute_values_name_var) . "')";
                                            $attribute_values_insert = tep_db_query($attribute_values_insert_query);
                                        }
                                    }
                                    // insert values to pov2po table
                                    $attribute_values_pov2po_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "')";
                                    $attribute_values_pov2po = tep_db_query($attribute_values_pov2po_query);
                                } else { // update options table, if options already exists
                                    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                        $lid = $languages[$i]['id'];
                                        $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;
                                        if (isset($$v_attribute_values_name_var)) {
                                            $attribute_values_update_lang_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "' and language_id ='" . (int)$lid . "'";
                                            $attribute_values_update_lang_values = tep_db_query($attribute_values_update_lang_query);
                                            // if options_values name doesn't exist for particular language, insert value
                                            if (tep_db_num_rows($attribute_values_update_lang_values) <= 0) {
                                                $attribute_values_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . tep_db_input($$v_attribute_values_name_var) . "')";
                                                $attribute_values_lang_insert = tep_db_query($attribute_values_lang_insert_query);
                                            } else { // if options_values name exists for particular language, update table
                                                $attribute_values_update_query = "update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($$v_attribute_values_name_var) . "' where products_options_values_id ='" . (int)$$v_attribute_values_id_var . "' and language_id = '" . (int)$lid . "'";
                                                $attribute_values_update = tep_db_query($attribute_values_update_query);
                                            }
                                        }
                                    }
                                }
                            }
                            // options_values table update end
                            // options_values price update begin
                          $v_attribute_values_price_var = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;
                            if (isset($$v_attribute_values_price_var) && ($$v_attribute_values_price_var != '')) {
                                $attribute_prices_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id ='" . (int)$$v_attribute_options_id_var . "' and options_values_id = '" . (int)$$v_attribute_values_id_var . "'";
                                $attribute_prices_values = tep_db_query($attribute_prices_query);
                                $attribute_values_price_prefix = ($$v_attribute_values_price_var < 0) ? '-' : '+';
                                // if negative, remove the negative sign for storing since the prefix is stored in another field.
                                if ( $$v_attribute_values_price_var < 0 ) $$v_attribute_values_price_var = strval(-((int)$$v_attribute_values_price_var));
                                // options_values_prices table update begin
                                // insert into options_values_prices table if no price exists
                                if (tep_db_num_rows($attribute_prices_values) <= 0) {
                                    $attribute_prices_insert_query = "insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_id, options_id, options_values_id, options_values_price, price_prefix) values ('" . (int)$v_products_id . "', '" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "', '" . (float)$$v_attribute_values_price_var . "', '" . $attribute_values_price_prefix . "')";
                                    $attribute_prices_insert = tep_db_query($attribute_prices_insert_query);
                                } else { // update options table, if options already exists
                                    $attribute_prices_update_query = "update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $$v_attribute_values_price_var . "', price_prefix = '" . $attribute_values_price_prefix . "' where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "' and options_values_id ='" . (int)$$v_attribute_values_id_var . "'";
                                    $attribute_prices_update = tep_db_query($attribute_prices_update_query);
                                }
                            }
                            // options_values price update end
                            //////// attributes stock add start
                            $v_attribute_values_stock_var = 'v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count;
                            if (isset($$v_attribute_values_stock_var) && ($$v_attribute_values_stock_var != '')) {
                                $stock_attributes = $$v_attribute_options_id_var.'-'.$$v_attribute_values_id_var;
                                $attribute_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_id = '" . (int)$v_products_id . "' and products_stock_attributes ='" . $stock_attributes . "'");
                                // insert into products_stock_quantity table if no stock exists
                                if (tep_db_num_rows($attribute_stock_query) <= 0) {
                                    $attribute_stock_insert_query =tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " (products_id, products_stock_attributes, products_stock_quantity) values ('" . (int)$v_products_id . "', '" . $stock_attributes . "', '" . (int)$$v_attribute_values_stock_var . "')");
                                } else { // update options table, if options already exists
                                    $attribute_stock_insert_query = tep_db_query("update " . TABLE_PRODUCTS_STOCK. " set products_stock_quantity = '" . (int)$$v_attribute_values_stock_var . "' where products_id = '" . (int)$v_products_id . "' and products_stock_attributes = '" . $stock_attributes . "'");
                                    // turn on stock tracking on products_options table
                                    $stock_tracking_query = tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_track_stock = '1' where products_options_id = '" . (int)$$v_attribute_options_id_var . "'");
                                }
                            }
                            //////// attributes stock add end
                            $attribute_values_count++;
                            $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
                        }
                        $attribute_options_count++;
                        $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;
                    }
                    $attribute_rows++;
                }
                // VJ product attribs end
				// products ymm start
                    $ymm_rows = 1;
					$ymm_products_car_make_var = 'v_products_car_make_' . $ymm_rows;
					$ymm_products_car_model_var = 'v_products_car_model_' . $ymm_rows;
					$ymm_products_car_year_bof_var = 'v_products_car_year_bof_' . $ymm_rows;
					$ymm_products_car_year_eof_var = 'v_products_car_year_eof_' . $ymm_rows;
					tep_db_query("delete from products_ymm where products_id = '" . (int)$v_products_id . "'");
					while (isset($$ymm_products_car_make_var) && !empty($$ymm_products_car_make_var)) {
						$sql_data_array = array('products_id' => (int)$v_products_id,
												'products_car_make' => $$ymm_products_car_make_var,
												'products_car_model' => $$ymm_products_car_model_var,
												'products_car_year_bof' => $$ymm_products_car_year_bof_var,
												'products_car_year_eof' => $$ymm_products_car_year_eof_var);
						tep_db_perform('products_ymm', $sql_data_array);
						$ymm_rows ++;
						$ymm_products_car_make_var = 'v_products_car_make_' . $ymm_rows;
						$ymm_products_car_model_var = 'v_products_car_model_' . $ymm_rows;
						$ymm_products_car_year_bof_var = 'v_products_car_year_bof_' . $ymm_rows;
						$ymm_products_car_year_eof_var = 'v_products_car_year_eof_' . $ymm_rows;
					}
				// products ymm end
            } else {
                // this record was missing the product_model
                echo "<p class=smallText>No products_model field in record. This line was not imported: ";
                foreach ($items as $tkey => $item) {
                  print_el($item);
                }
                echo "<br /><br /></p>";
            }
            // end of row insertion code
        }
    // EP for product extra fields Contrib by minhmaster DEVSOFTVN ==========
    }
    // end (EP for product extra fields Contrib by minhmt DEVSOFTVN) ============
}
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>