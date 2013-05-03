<?php
ini_set('error_reporting', E_ALL ^ E_NOTICE);
/*
Team Do not remove comments in this file.

Turn off all error reporting
//error_reporting(0);

// Report simple running errors
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
//error_reporting(E_ALL ^ E_NOTICE);

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);

// Report all PHP errors
//error_reporting(-1);

// Same as error_reporting(E_ALL);
*/
  date_default_timezone_set('UTC');
  define('PAGE_PARSE_START_TIME', microtime());
   if (function_exists('ini_get')) {
      (!ini_get('register_globals')) or exit('FATAL ERROR: register_globals is enabled in php.ini, please disable it!');
  }
  if (file_exists('includes/local/configure.php'))
      include('includes/local/configure.php');
  require('includes/configure.php');
  define('PROJECT_VERSION', 'CartStore 2.0');
  if( empty( $PHP_SELF ) ) $PHP_SELF = ( ( ( strlen(ini_get('cgi.fix_pathinfo' ) ) > 0 ) && ( ( bool )ini_get('cgi.fix_pathinfo' ) == false ) ) || !isset($_SERVER['SCRIPT_NAME' ] ) ) ? basename( $_SERVER['PHP_SELF' ] ) : basename( $_SERVER[ 'SCRIPT_NAME' ] );
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');
  require(DIR_WS_INCLUDES . 'filenames.php');
  require(DIR_WS_INCLUDES . 'database_tables.php');
  define('BOX_WIDTH', 125);
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');
  require(DIR_WS_FUNCTIONS . 'database.php');
  tep_db_connect() or die('Unable to connect to database server!');
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }

  if (defined('PHPIDS_MODULE_ADMIN') && PHPIDS_MODULE_ADMIN === 'true') {
    include(DIR_FS_CATALOG . DIR_WS_MODULES . 'osc_phpids.php');
  }
  require(DIR_FS_CATALOG . 'includes/osc_sec.php');

  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');
  if (defined('PHPIDS_IP_BAN_MODULE') && PHPIDS_IP_BAN_MODULE === 'true') {
    include(DIR_FS_CATALOG . DIR_WS_MODULES . 'banned_ip.php');
  }
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');
  require(DIR_WS_CLASSES . 'logger.php');
  require(DIR_WS_CLASSES . 'shopping_cart.php');
  require(DIR_WS_FUNCTIONS . 'compatibility.php');
  if (!function_exists('session_start')) {
      define('PHP_SESSION_NAME', 'osCAdminID');
      define('PHP_SESSION_PATH', '/');
      define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);
      include(DIR_WS_CLASSES . 'sessions.php');
  }
  require(DIR_WS_FUNCTIONS . 'sessions.php');
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);
  if (function_exists('session_set_cookie_params')) {
      session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
      ini_set('session.cookie_lifetime', '0');
      ini_set('session.cookie_path', DIR_WS_ADMIN);
  }
  tep_session_start();
  if (!tep_session_is_registered('language') || isset($_GET['language'])) {
      if (!tep_session_is_registered('language')) {
          tep_session_register('language');
          tep_session_register('languages_id');
      }
      include(DIR_WS_CLASSES . 'language.php');
      $lng = new language();
      if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
          $lng->set_language($_GET['language']);
      } else {
          $lng->get_browser_language();
      }
      $language = $lng->language['directory'];
      $languages_id = $lng->language['id'];
  }

// redirect to login page if administrator is not yet logged in
  if (!tep_session_is_registered('login_id') && basename($_SERVER['PHP_SELF']) != FILENAME_PASSWORD_FORGOTTEN) {
    $redirect = false;
    $current_page = basename($_SERVER['PHP_SELF']);
// if the first page request is to the login page, set the current page to the index page
// so the redirection on a successful login is not made to the login page again
    if ( ($current_page == FILENAME_LOGIN) && !tep_session_is_registered('redirect_origin') ) {
      $current_page = FILENAME_DEFAULT;
      $HTTP_GET_VARS = array();
    }
    if ($current_page != FILENAME_LOGIN) {
      if (!tep_session_is_registered('redirect_origin')) {
        tep_session_register('redirect_origin');

        $redirect_origin = array('page' => $current_page,
                                 'get' => $HTTP_GET_VARS);
      }
// try to automatically login with the HTTP Authentication values if it exists
      if (!tep_session_is_registered('auth_ignore')) {
        if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
          $redirect_origin['auth_user'] = $_SERVER['PHP_AUTH_USER'];
          $redirect_origin['auth_pw'] = $_SERVER['PHP_AUTH_PW'];
        }
      }
      $redirect = true;
    }
    if (!isset($login_request) || isset($HTTP_GET_VARS['login_request']) || isset($HTTP_POST_VARS['login_request']) || isset($_COOKIE['login_request']) || isset($_SESSION['login_request']) || isset($_FILES['login_request']) || isset($_SERVER['login_request'])) {
      $redirect = true;
    }

    if ($redirect == true) {
      tep_redirect(tep_href_link(FILENAME_LOGIN, (isset($redirect_origin['auth_user']) ? 'action=process' : '')));
    }
    unset($redirect);
  }


  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($_SERVER['PHP_SELF']);

  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page) && !is_dir(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
      include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }
  require(DIR_WS_FUNCTIONS . 'localization.php');
  require(DIR_WS_FUNCTIONS . 'validations.php');
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;
  require(DIR_WS_CLASSES . 'split_page_results.php');
  require(DIR_WS_CLASSES . 'object_info.php');
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');
  require(DIR_WS_CLASSES . 'upload.php');
  if (isset($_GET['cPath'])) {
      $cPath = $_GET['cPath'];
  } else {
      $cPath = '';
  }
  if (tep_not_null($cPath)) {
      $cPath_array = tep_parse_category_path($cPath);
      $cPath = implode('_', $cPath_array);
      $current_category_id = $cPath_array[(sizeof($cPath_array) - 1)];
  } else {
      $current_category_id = 0;
  }
  if (!tep_session_is_registered('selected_box')) {
      tep_session_register('selected_box');
      $selected_box = 'configuration';
  }
  if (isset($_GET['selected_box'])) {
      $selected_box = $_GET['selected_box'];
  }
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true), array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true), array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true));
  $vendor_configuration_query = tep_db_query('select configuration_key as cfgKey,



configuration_value as cfgValue from ' . TABLE_VENDOR_CONFIGURATION);
  while ($vendor_configuration = tep_db_fetch_array($vendor_configuration_query)) {
      define($vendor_configuration['cfgKey'], $vendor_configuration['cfgValue']);
  }
  if (!defined('DEFAULT_CURRENCY')) {
      $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }
  if (!defined('DEFAULT_LANGUAGE')) {
      $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }
  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false)) {
      $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }

// LINE ADDED - MOD: CREDIT CLASS Gift Voucher Contribution
  require(DIR_WS_FUNCTIONS . 'add_ccgvdc_application_top.php');  // ICW CREDIT CLASS Gift Voucher Addittion
  require(DIR_WS_LANGUAGES . $language . '/add_ccgvdc.php'); // ICW CREDIT CLASS Gift Voucher Addittion
  require('includes/affiliate_application_top.php');
  define('TABLE_PRODUCTS_XSELL', 'products_xsell');
  require(DIR_WS_FUNCTIONS . 'articles.php');
  if (isset($_GET['tPath'])) {
      $tPath = $_GET['tPath'];
  } else {
      $tPath = '';
  }
  if (tep_not_null($tPath)) {
      $tPath_array = tep_parse_topic_path($tPath);
      $tPath = implode('_', $tPath_array);
      $current_topic_id = $tPath_array[(sizeof($tPath_array) - 1)];
  } else {
      $current_topic_id = 0;
  }
  if (basename($_SERVER['PHP_SELF']) != FILENAME_LOGIN && basename($_SERVER['PHP_SELF']) != FILENAME_PASSWORD_FORGOTTEN) {
      tep_admin_check_login();
  }
  if (count($_POST) > 0) {
      foreach ($_POST as $key => $value) {
          link_post_variable($key);
      }
  }
?>
