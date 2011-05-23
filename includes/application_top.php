<?php

// STEP 1: include the thumbsup.php file before any output





/*

  $Id: application_top.php,v 1.280 2003/07/12 09:38:07 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



// start the timer for the page parse time log


ini_set('date.timezone', 'America/Vancouver'); 


define ( 'PAGE_PARSE_START_TIME', microtime () );



// set the level of error reporting

error_reporting ( E_ALL & ~ E_NOTICE );



// check if register_globals is enabled.

// since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.





if (function_exists ( 'ini_get' )) {

	//(!ini_get('register_globals')) or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');

	(! ini_get ( 'register_globals' )) or exit ( 'FATAL ERROR: register_globals is enabled in php.ini, please disable it!' );

}



// Set the local configuration parameters - mainly for developers

if (file_exists ( 'includes/local/configure.php' ))

	include ('includes/local/configure.php');



// include server parameters

require ('includes/configure.php');



if (strlen ( DB_SERVER ) < 1) {

	if (is_dir ( 'install' )) {

		header ( 'Location: install/index.php' );

	}

}



// define the project version

define ( 'PROJECT_VERSION', 'CartStore 2.0' );



// set the type of request (secure or not)

$request_type = (getenv ( 'HTTPS' ) == 'on') ? 'SSL' : 'NONSSL';



// set php_self in the local scope

if (! isset ( $PHP_SELF ))

	$PHP_SELF = $_SERVER ['PHP_SELF'];

  // Security Pro by FWR Media
  include_once DIR_WS_MODULES . 'fwr_media_security_pro.php';
  $security_pro = new Fwr_Media_Security_Pro;
  // If you need to exclude a file from cleansing then you can add it like below
  //$security_pro->addExclusion( 'some_file.php' );
  $security_pro->cleanse( $PHP_SELF );
  // End - Security Pro by FWR Media

if ($request_type == 'NONSSL') {

	define ( 'DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG );

} else {

	define ( 'DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG );

}



// include the list of project filenames

require (DIR_WS_INCLUDES . 'filenames.php');



// include the list of project database tables

require (DIR_WS_INCLUDES . 'database_tables.php');



// customization for the design layout

define ( 'BOX_WIDTH', 125 ); // how wide the boxes should be in pixels (default: 125)





// include the database functions

require (DIR_WS_FUNCTIONS . 'database.php');

// start indvship
  function tep_get_configuration_key_value($lookup) {
	$configuration_query_raw= tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='" . $lookup . "'");
	$configuration_query= tep_db_fetch_array($configuration_query_raw);
	$lookup_value= $configuration_query['configuration_value'];
	return $lookup_value;
  }
// end indvship

// make a connection to the database... now

tep_db_connect () or die ( 'Unable to connect to database server!' );



// set the application parameters

$configuration_query = tep_db_query ( 'select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION );

while ( $configuration = tep_db_fetch_array ( $configuration_query ) ) {

	define ( $configuration ['cfgKey'], $configuration ['cfgValue'] );

}

//MVS Start

// set the vendor shipping constants

$vendor_configuration_query = tep_db_query ( 'select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_VENDOR_CONFIGURATION );

while ( $vendor_configuration = tep_db_fetch_array ( $vendor_configuration_query ) ) {

	define ( $vendor_configuration ['cfgKey'], $vendor_configuration ['cfgValue'] );

}

//MVS End

// if gzip_compression is enabled, start to buffer the output

if ((GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded ( 'zlib' )) && (PHP_VERSION >= '4')) {

	if (($ini_zlib_output_compression = ( int ) ini_get ( 'zlib.output_compression' )) < 1) {

		if (PHP_VERSION >= '4.0.4') {

			ob_start ( 'ob_gzhandler' );

		} else {

			include (DIR_WS_FUNCTIONS . 'gzip_compression.php');

			ob_start ();

			ob_implicit_flush ();

		}

	} else {

		ini_set ( 'zlib.output_compression_level', GZIP_LEVEL );

	}

}



// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled

if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {

	if (strlen ( getenv ( 'PATH_INFO' ) ) > 1) {

		$GET_array = array ();

		$PHP_SELF = str_replace ( getenv ( 'PATH_INFO' ), '', $PHP_SELF );

		$vars = explode ( '/', substr ( getenv ( 'PATH_INFO' ), 1 ) );

		for($i = 0, $n = sizeof ( $vars ); $i < $n; $i ++) {

			if (strpos ( $vars [$i], '[]' )) {

				$GET_array [substr ( $vars [$i], 0, - 2 )] [] = $vars [$i + 1];

			} else {

				$_GET [$vars [$i]] = $vars [$i + 1];

			}

			$i ++;

		}



		if (sizeof ( $GET_array ) > 0) {

			while ( list ( $key, $value ) = each ( $GET_array ) ) {

				$_GET [$key] = $value;

			}

		}

	}

}



// define general functions used application-wide

require (DIR_WS_FUNCTIONS . 'general.php');

require (DIR_WS_FUNCTIONS . 'html_output.php');



// include calendar class

require (DIR_WS_CLASSES . 'calendar.php');



// set the cookie domain

$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);

$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);



// include cache functions if enabled

if (USE_CACHE == 'true')

	include (DIR_WS_FUNCTIONS . 'cache.php');



// include shopping cart class

require (DIR_WS_CLASSES . 'shopping_cart.php');



// include viewed products class

require (DIR_WS_CLASSES . 'viewed_products.php');



// include wishlist class

require (DIR_WS_CLASSES . 'wishlist.php');



// include navigation history class

require (DIR_WS_CLASSES . 'navigation_history.php');



// some code to solve compatibility issues

require (DIR_WS_FUNCTIONS . 'compatibility.php');



// check if sessions are supported, otherwise use the php3 compatible session class

if (! function_exists ( 'session_start' )) {

	define ( 'PHP_SESSION_NAME', 'osCsid' );

	define ( 'PHP_SESSION_PATH', $cookie_path );

	define ( 'PHP_SESSION_DOMAIN', $cookie_domain );

	define ( 'PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY );



	include (DIR_WS_CLASSES . 'sessions.php');

}



// define how the session functions will be used

require (DIR_WS_FUNCTIONS . 'sessions.php');



// set the session name and save path

tep_session_name ( 'osCsid' );

tep_session_save_path ( SESSION_WRITE_DIRECTORY );



// set the session cookie parameters

if (function_exists ( 'session_set_cookie_params' )) {

	session_set_cookie_params ( 0, $cookie_path, $cookie_domain );

} elseif (function_exists ( 'ini_set' )) {

	ini_set ( 'session.cookie_lifetime', '0' );

	ini_set ( 'session.cookie_path', $cookie_path );

	ini_set ( 'session.cookie_domain', $cookie_domain );

}



// set the session ID if it exists

if (isset ( $_POST [tep_session_name ()] )) {

	tep_session_id ( $_POST [tep_session_name ()] );

} elseif (($request_type == 'SSL') && isset ( $_GET [tep_session_name ()] )) {

	tep_session_id ( $_GET [tep_session_name ()] );

}



// start the session

$session_started = false;

if (SESSION_FORCE_COOKIE_USE == 'True') {

	tep_setcookie ( 'cookie_test', 'please_accept_for_session', time () + 60 * 60 * 24 * 30, $cookie_path, $cookie_domain );



	if (isset ( $_COOKIE ['cookie_test'] )) {

		tep_session_start ();

		$session_started = true;

	}

} elseif (SESSION_BLOCK_SPIDERS == 'True') {

	$user_agent = strtolower ( getenv ( 'HTTP_USER_AGENT' ) );

	$spider_flag = false;



	if (tep_not_null ( $user_agent )) {

		$spiders = file ( DIR_WS_INCLUDES . 'spiders.txt' );



		for($i = 0, $n = sizeof ( $spiders ); $i < $n; $i ++) {

			if (tep_not_null ( $spiders [$i] )) {

				if (is_integer ( strpos ( $user_agent, trim ( $spiders [$i] ) ) )) {

					$spider_flag = true;

					break;

				}

			}

		}

	}



	if ($spider_flag == false) {

		tep_session_start ();

		$session_started = true;

	}

} else {

	tep_session_start ();

	$session_started = true;

}



// set SID once, even if empty

$SID = (defined ( 'SID' ) ? SID : '');



// verify the ssl_session_id if the feature is enabled

if (($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true)) {

	$ssl_session_id = getenv ( 'SSL_SESSION_ID' );

	if (! tep_session_is_registered ( 'SSL_SESSION_ID' )) {

		$SESSION_SSL_ID = $ssl_session_id;

		tep_session_register ( 'SESSION_SSL_ID' );

	}



	if ($SESSION_SSL_ID != $ssl_session_id) {

		tep_session_destroy ();

		tep_redirect ( tep_href_link ( FILENAME_SSL_CHECK ) );

	}

}



// verify the browser user agent if the feature is enabled

if (SESSION_CHECK_USER_AGENT == 'True') {

	$http_user_agent = getenv ( 'HTTP_USER_AGENT' );

	if (! tep_session_is_registered ( 'SESSION_USER_AGENT' )) {

		$SESSION_USER_AGENT = $http_user_agent;

		tep_session_register ( 'SESSION_USER_AGENT' );

	}



	if ($SESSION_USER_AGENT != $http_user_agent) {

		tep_session_destroy ();

		tep_redirect ( tep_href_link ( FILENAME_LOGIN ) );

	}

}



// verify the IP address if the feature is enabled

if (SESSION_CHECK_IP_ADDRESS == 'True') {

	$ip_address = tep_get_ip_address ();

	if (! tep_session_is_registered ( 'SESSION_IP_ADDRESS' )) {

		$SESSION_IP_ADDRESS = $ip_address;

		tep_session_register ( 'SESSION_IP_ADDRESS' );

	}



	if ($SESSION_IP_ADDRESS != $ip_address) {

		tep_session_destroy ();

		tep_redirect ( tep_href_link ( FILENAME_LOGIN ) );

	}

}



// create the shopping cart & fix the cart if necesary

if (tep_session_is_registered ( 'cart' ) && is_object ( $cart )) {

	if (PHP_VERSION < 4) {

		$broken_cart = $cart;

		$cart = new shoppingCart ();

		$cart->unserialize ( $broken_cart );

	}

} else {

	tep_session_register ( 'cart' );

	$cart = new shoppingCart ();

}



// include currencies class and create an instance

require (DIR_WS_CLASSES . 'currencies.php');

$currencies = new currencies ();

// include the price formatter for the price breaks contribution

require (DIR_WS_CLASSES . 'PriceFormatter.php');

$pf = new PriceFormatter ();

// include the mail classes

require (DIR_WS_CLASSES . 'mime.php');

require (DIR_WS_CLASSES . 'email.php');



// set the language

if (! tep_session_is_registered ( 'language' ) || isset ( $_GET ['language'] )) {

	if (! tep_session_is_registered ( 'language' )) {

		tep_session_register ( 'language' );

		tep_session_register ( 'languages_id' );

	}



	include (DIR_WS_CLASSES . 'language.php');

	$lng = new language ();



	if (isset ( $_GET ['language'] ) && tep_not_null ( $_GET ['language'] )) {

		$lng->set_language ( $_GET ['language'] );

	} else {

		$lng->get_browser_language ();

	}



	$language = $lng->language ['directory'];

	$languages_id = $lng->language ['id'];

}



// include the language translations

require (DIR_WS_LANGUAGES . $language . '.php');



// Ultimate SEO URLs BEGIN

include_once (DIR_WS_CLASSES . 'seo.class.php');

if (! is_object ( $seo_urls )) {

	$seo_urls = new SEO_URL ( $languages_id );

}

// Ultimate SEO URLs END





// currency

if (! tep_session_is_registered ( 'currency' ) || isset ( $_GET ['currency'] ) || ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency))) {

	if (! tep_session_is_registered ( 'currency' ))

		tep_session_register ( 'currency' );



	if (isset ( $_GET ['currency'] )) {

		if (! $currency = tep_currency_exists ( $_GET ['currency'] ))

			$currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

	} else {

		$currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

	}

}



// navigation history

if (tep_session_is_registered ( 'navigation' )) {

	if (PHP_VERSION < 4) {

		$broken_navigation = $navigation;

		$navigation = new navigationHistory ();

		$navigation->unserialize ( $broken_navigation );

	}

} else {

	tep_session_register ( 'navigation' );

	$navigation = new navigationHistory ();

}

$navigation->add_current_page ();



//BEGIN allprods modification

if (ALL_PRODUCTS == 'false' and strstr ( $PHP_SELF, ALL_PRODUCTS_FILENAME )) {

	tep_redirect ( tep_href_link ( FILENAME_DEFAULT ) );

}

//END allprods modification





// infobox

require (DIR_WS_CLASSES . 'boxes.php');



// initialize the message stack for output messages

require (DIR_WS_CLASSES . 'message_stack.php');

$messageStack = new messageStack ();



// Shopping cart actions





// wishlist data

if (! tep_session_is_registered ( 'wishList' )) {

	tep_session_register ( 'wishList' );

	$wishList = new wishlist ();

}



//Wishlist actions (must be before shopping cart actions)

if (isset ( $_GET ['wishlist_x'] )) {

	if (isset ( $_GET ['products_id'] )) {

		if (isset ( $_POST ['id'] )) {

			$attributes_id = $_POST ['id'];

			tep_session_register ( 'attributes_id' );

		}

		$wishlist_id = $_GET ['products_id'];

		tep_session_register ( 'wishlist_id' );

	}

	tep_redirect ( tep_href_link ( FILENAME_WISHLIST ) );

}



if (isset ( $_GET ['action'] )) {

	// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled

	if ($session_started == false) {

		tep_redirect ( tep_href_link ( FILENAME_COOKIE_USAGE ) );

	}



	if (DISPLAY_CART == 'true') {

		$goto = FILENAME_SHOPPING_CART;

		$parameters = array ('action', 'cPath', 'products_id', 'pid' );

	} else {

		$goto = basename ( $PHP_SELF );

		if ($_GET ['action'] == 'buy_now') {

			$parameters = array ('action', 'pid', 'products_id' );

		} else {

			$parameters = array ('action', 'pid' );

		}

	}

	switch ($_GET ['action']) {

		// customer wants to update the product quantity in their shopping cart

		case 'update_product' :

			for($i = 0, $n = sizeof ( $_POST ['products_id'] ); $i < $n; $i ++) {

				if (in_array ( $_POST ['products_id'] [$i], (is_array ( $_POST ['cart_delete'] ) ? $_POST ['cart_delete'] : array ()) )) {

					$cart->remove ( $_POST ['products_id'] [$i] );

				} else {

					if (PHP_VERSION < 4) {

						// if PHP3, make correction for lack of multidimensional array.

						reset ( $_POST );

						while ( list ( $key, $value ) = each ( $_POST ) ) {

							if (is_array ( $value )) {

								while ( list ( $key2, $value2 ) = each ( $value ) ) {

									if (preg_match ( "/(.*)\]\[(.*)/", $key2, $var )) {

										$id2 [$var [1]] [$var [2]] = $value2;

									}

								}

							}

						}

						$attributes = ($id2 [$_POST ['products_id'] [$i]]) ? $id2 [$_POST ['products_id'] [$i]] : '';

					} else {

						$attributes = ($_POST ['id'] [$_POST ['products_id'] [$i]]) ? $_POST ['id'] [$_POST ['products_id'] [$i]] : '';

					}

					$cart->add_cart ( $_POST ['products_id'] [$i], $_POST ['cart_quantity'] [$i], $attributes, false );

				}

			}

			tep_redirect ( tep_href_link ( $goto, tep_get_all_get_params ( $parameters ) ) );

			break;

		// customer adds a product from the products page

		/*     case 'add_product' :    if (isset($_POST['products_id']) && is_numeric($_POST['products_id']) && ($_POST['products_id']==(int)$_POST['products_id'])) {

//++++ QT Pro: Begin Changed code

                                $attributes=array();

                                if (isset($_POST['attrcomb']) && (preg_match("/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/",$_POST['attrcomb']))) {

                                  $attrlist=explode(',',$_POST['attrcomb']);

                                  foreach ($attrlist as $attr) {

                                    list($oid, $oval)=explode('-',$attr);

                                    if (is_numeric($oid) && $oid==(int)$oid && is_numeric($oval) && $oval==(int)$oval)

                                      $attributes[$oid]=$oval;

                                  }

                                }

                                if (isset($_POST['id']) && is_array($_POST['id'])) {

                                  foreach ($_POST['id'] as $key=>$val) {

                                    if (is_numeric($key) && $key==(int)$key && is_numeric($val) && $val==(int)$val)

                                      $attributes=$attributes + $_POST['id'];

                                  }

                                }

                                $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $attributes))+ $_POST['cart_quantity'], $attributes);





//++++ QT Pro: End Changed Code

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;*/



		case 'add_product' :

			if (isset ( $_POST ['products_id'] ) && is_numeric ( $_POST ['products_id'] ) && ($_POST ['products_id'] == ( int ) $_POST ['products_id'])) {

				// iii 030813 added: File uploading: save uploaded files with unique file names



                                 $attributes = $_POST ['id'];

				if (isset ( $_POST ['attrcomb'] ) && (preg_match ( "/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/", $_POST ['attrcomb'] ))) {

					$attributes = array ();

					$attrlist = explode ( ',', $_POST ['attrcomb'] );

					foreach ( $attrlist as $attr ) {

						list ( $oid, $oval ) = explode ( '-', $attr );

						if (is_numeric ( $oid ) && $oid == ( int ) $oid && is_numeric ( $oval ) && $oval == ( int ) $oval)

							$attributes [$oid] = $oval;

					}

				}





	if ($_POST ['number_of_uploads'] > 0) {



						require (DIR_WS_CLASSES . 'upload.php');

						for($i = 1; $i <= $_POST ['number_of_uploads']; $i ++) {

							if (tep_not_null ( $_FILES ['id'] ['tmp_name'] [TEXT_PREFIX . $_POST [UPLOAD_PREFIX . $i]] ) and ($_FILES ['id'] ['tmp_name'] [TEXT_PREFIX . $_POST [UPLOAD_PREFIX . $i]] != 'none')) {

								$products_options_file = new upload ( 'id' );

								$products_options_file->set_destination ( DIR_FS_UPLOADS );

								if ($products_options_file->parse ( TEXT_PREFIX . $_POST [UPLOAD_PREFIX . $i] )) {

									if (tep_session_is_registered ( 'customer_id' )) {

										tep_db_query ( "insert into " . TABLE_FILES_UPLOADED . " (sesskey, customers_id, files_uploaded_name) values('" . tep_session_id () . "', '" . $customer_id . "', '" . tep_db_input ( $products_options_file->filename ) . "')" );

									} else {

										tep_db_query ( "insert into " . TABLE_FILES_UPLOADED . " (sesskey, files_uploaded_name) values('" . tep_session_id () . "', '" . tep_db_input ( $products_options_file->filename ) . "')" );

									}

									$insert_id = tep_db_insert_id ();

									$attributes [TEXT_PREFIX . $_POST [UPLOAD_PREFIX . $i]] = $insert_id . $products_options_file->filename;

									$products_options_file->set_filename ( "$insert_id" . $products_options_file->filename );

									if (! ($products_options_file->save ())) {

										break 2;

									}

								} else {

									break 2;

								}

							} else { // No file uploaded -- use previous value

								$attributes [TEXT_PREFIX . $_POST [UPLOAD_PREFIX . $i]] = $_POST [TEXT_PREFIX . UPLOAD_PREFIX . $i];

							}

						}

					}







				$cart->add_cart ( $_POST ['products_id'], $cart->get_quantity ( tep_get_uprid ( $_POST ['products_id'], $attributes ) ) + $_POST ['cart_quantity'], $attributes );





			}

			tep_redirect ( tep_href_link ( $goto, tep_get_all_get_params ( $parameters ) ) );

			break;



		// performed by the 'buy now' button in product listings and review page

		case 'buy_now' :



			if (isset ( $_GET ['products_id'] )) {

				$q = $_GET ['qty'];

				if ($q < 1)

					$q = 1;

				if (tep_has_product_attributes ( $_GET ['products_id'] )) {

					tep_redirect ( tep_href_link ( FILENAME_PRODUCT_INFO, 'products_id=' . $_GET ['products_id'] ) );

				} else {

					$cart->add_cart ( $_GET ['products_id'], $cart->get_quantity ( $_GET ['products_id'] ) + $q );

				}

			}

			//header("location:index.php?".tep_get_all_get_params($parameters));

			tep_redirect ( tep_href_link ( $goto, tep_get_all_get_params ( $parameters ) ) );

			break;

		case 'notify' :

			if (tep_session_is_registered ( 'customer_id' )) {

				if (isset ( $_GET ['products_id'] )) {

					$notify = $_GET ['products_id'];

				} elseif (isset ( $_GET ['notify'] )) {

					$notify = $_GET ['notify'];

				} elseif (isset ( $_POST ['notify'] )) {

					$notify = $_POST ['notify'];

				} else {

					tep_redirect ( tep_href_link ( basename ( $PHP_SELF ), tep_get_all_get_params ( array ('action', 'notify' ) ) ) );

				}

				if (! is_array ( $notify ))

					$notify = array ($notify );

				for($i = 0, $n = sizeof ( $notify ); $i < $n; $i ++) {

					$check_query = tep_db_query ( "select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify [$i] . "' and customers_id = '" . $customer_id . "'" );

					$check = tep_db_fetch_array ( $check_query );

					if ($check ['count'] < 1) {

						tep_db_query ( "insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify [$i] . "', '" . $customer_id . "', now())" );

					}

				}

				tep_redirect ( tep_href_link ( basename ( $PHP_SELF ), tep_get_all_get_params ( array ('action', 'notify' ) ) ) );

			} else {

				$navigation->set_snapshot ();

				tep_redirect ( tep_href_link ( FILENAME_LOGIN, '', 'SSL' ) );

			}

			break;

		case 'notify_remove' :

			if (tep_session_is_registered ( 'customer_id' ) && isset ( $_GET ['products_id'] )) {

				$check_query = tep_db_query ( "select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $_GET ['products_id'] . "' and customers_id = '" . $customer_id . "'" );

				$check = tep_db_fetch_array ( $check_query );

				if ($check ['count'] > 0) {

					tep_db_query ( "delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $_GET ['products_id'] . "' and customers_id = '" . $customer_id . "'" );

				}

				tep_redirect ( tep_href_link ( basename ( $PHP_SELF ), tep_get_all_get_params ( array ('action' ) ) ) );

			} else {

				$navigation->set_snapshot ();

				tep_redirect ( tep_href_link ( FILENAME_LOGIN, '', 'SSL' ) );

			}

			break;

		case 'cust_order' :

			if (tep_session_is_registered ( 'customer_id' ) && isset ( $_GET ['pid'] )) {

				if (tep_has_product_attributes ( $_GET ['pid'] )) {

					tep_redirect ( tep_href_link ( FILENAME_PRODUCT_INFO, 'products_id=' . $_GET ['pid'] ) );

				} else {

					$cart->add_cart ( $_GET ['pid'], $cart->get_quantity ( $_GET ['pid'] ) + 1 );

				}

			}

			tep_redirect ( tep_href_link ( $goto, tep_get_all_get_params ( $parameters ) ) );

			break;

	}

}



// include the who's online functions

if (basename ( $PHP_SELF ) != FILENAME_EVENTS_CALENDAR_CONTENT) {

	require (DIR_WS_FUNCTIONS . 'whos_online.php');

	tep_update_whos_online ();

}



// include the password crypto functions

require (DIR_WS_FUNCTIONS . 'password_funcs.php');



// include validation functions (right now only email address)

require (DIR_WS_FUNCTIONS . 'validations.php');



// split-page-results

require (DIR_WS_CLASSES . 'split_page_results.php');



// infobox

// require(DIR_WS_CLASSES . 'boxes.php');

// Points/Rewards Module V2.00

require (DIR_WS_FUNCTIONS . 'redemptions.php');



// auto activate and expire banners

require (DIR_WS_FUNCTIONS . 'banner.php');

tep_activate_banners ();

tep_expire_banners ();



// auto expire special products

require (DIR_WS_FUNCTIONS . 'specials.php');

tep_expire_specials ();



// auto expire featured products

require (DIR_WS_FUNCTIONS . 'featured.php');

tep_expire_featured ();



// calculate category path

if (isset ( $_GET ['cPath'] )) {

	$cPath = $_GET ['cPath'];

} elseif (isset ( $_GET ['products_id'] ) && ! isset ( $_GET ['manufacturers_id'] )) {

	$cPath = tep_get_product_path ( $_GET ['products_id'] );

} else {

	$cPath = '';

}



if (tep_not_null ( $cPath )) {

	$cPath_array = tep_parse_category_path ( $cPath );

	$cPath = implode ( '_', $cPath_array );

	$current_category_id = $cPath_array [(sizeof ( $cPath_array ) - 1)];

} else {

	$current_category_id = 0;

}



// include the breadcrumb class and start the breadcrumb trail

require (DIR_WS_CLASSES . 'breadcrumb.php');

$breadcrumb = new breadcrumb ();



$breadcrumb->add ( HEADER_TITLE_TOP, HTTP_SERVER );

$breadcrumb->add ( HEADER_TITLE_CATALOG, tep_href_link ( FILENAME_DEFAULT ) );



// add category names or the manufacturer name to the breadcrumb trail

if (isset ( $cPath_array )) {

	for($i = 0, $n = sizeof ( $cPath_array ); $i < $n; $i ++) {

		$categories_query = tep_db_query ( "select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . ( int ) $cPath_array [$i] . "' and language_id = '" . ( int ) $languages_id . "'" );

		if (tep_db_num_rows ( $categories_query ) > 0) {

			$categories = tep_db_fetch_array ( $categories_query );

			$breadcrumb->add ( $categories ['categories_name'], tep_href_link ( FILENAME_DEFAULT, 'cPath=' . implode ( '_', array_slice ( $cPath_array, 0, ($i + 1) ) ) ) );

		} else {

			break;

		}

	}

} elseif (isset ( $_GET ['manufacturers_id'] )) {

	$manufacturers_query = tep_db_query ( "select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . ( int ) $_GET ['manufacturers_id'] . "'" );

	if (tep_db_num_rows ( $manufacturers_query )) {

		$manufacturers = tep_db_fetch_array ( $manufacturers_query );

		$breadcrumb->add ( $manufacturers ['manufacturers_name'], tep_href_link ( FILENAME_DEFAULT, 'manufacturers_id=' . $_GET ['manufacturers_id'] ) );

	}

}



// add the products model to the breadcrumb trail

if (isset ( $_GET ['products_id'] )) {

	$model_query = tep_db_query ( "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . ( int ) $_GET ['products_id'] . "'" );

	if (tep_db_num_rows ( $model_query )) {

		$model = tep_db_fetch_array ( $model_query );

		$breadcrumb->add ( $model ['products_name'], tep_href_link ( FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET ['products_id'] ) );

	}

}



// include the articles functions

require (DIR_WS_FUNCTIONS . 'articles.php');

require (DIR_WS_FUNCTIONS . 'article_header_tags.php');



// calculate topic path

if (isset ( $_GET ['tPath'] )) {

	$tPath = $_GET ['tPath'];

} elseif (isset ( $_GET ['articles_id'] ) && ! isset ( $_GET ['authors_id'] )) {

	$tPath = tep_get_article_path ( $_GET ['articles_id'] );

} else {

	$tPath = '';

}



if (tep_not_null ( $tPath )) {

	$tPath_array = tep_parse_topic_path ( $tPath );

	$tPath = implode ( '_', $tPath_array );

	$current_topic_id = $tPath_array [(sizeof ( $tPath_array ) - 1)];

} else {

	$current_topic_id = 0;

}



// add topic names or the author name to the breadcrumb trail

if (isset ( $tPath_array )) {

	for($i = 0, $n = sizeof ( $tPath_array ); $i < $n; $i ++) {

		$topics_query = tep_db_query ( "select topics_name from " . TABLE_TOPICS_DESCRIPTION . " where topics_id = '" . ( int ) $tPath_array [$i] . "' and language_id = '" . ( int ) $languages_id . "'" );

		if (tep_db_num_rows ( $topics_query ) > 0) {

			$topics = tep_db_fetch_array ( $topics_query );

			$breadcrumb->add ( $topics ['topics_name'], tep_href_link ( FILENAME_ARTICLES, 'tPath=' . implode ( '_', array_slice ( $tPath_array, 0, ($i + 1) ) ) ) );

		} else {

			break;

		}

	}

} elseif (isset ( $_GET ['authors_id'] )) {

	$authors_query = tep_db_query ( "select authors_name from " . TABLE_AUTHORS . " where authors_id = '" . ( int ) $_GET ['authors_id'] . "'" );

	if (tep_db_num_rows ( $authors_query )) {

		$authors = tep_db_fetch_array ( $authors_query );

		$breadcrumb->add ( 'Articles by ' . $authors ['authors_name'], tep_href_link ( FILENAME_ARTICLES, 'authors_id=' . $_GET ['authors_id'] ) );

	}

}



// add the articles name to the breadcrumb trail

if (isset ( $_GET ['articles_id'] )) {

	$article_query = tep_db_query ( "select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . ( int ) $_GET ['articles_id'] . "'" );

	if (tep_db_num_rows ( $article_query )) {

		$article = tep_db_fetch_array ( $article_query );

		if (isset ( $_GET ['authors_id'] )) {

			$breadcrumb->add ( $article ['articles_name'], tep_href_link ( FILENAME_ARTICLE_INFO, 'authors_id=' . $_GET ['authors_id'] . '&articles_id=' . $_GET ['articles_id'] ) );

		} else {

			$breadcrumb->add ( $article ['articles_name'], tep_href_link ( FILENAME_ARTICLE_INFO, 'tPath=' . $tPath . '&articles_id=' . $_GET ['articles_id'] ) );

		}

	}

}



// initialize the message stack for output messages

//  require(DIR_WS_CLASSES . 'message_stack.php');

//  $messageStack = new messageStack;





// set which precautions should be checked

define ( 'WARN_INSTALL_EXISTENCE', 'true' );

define ( 'WARN_CONFIG_WRITEABLE', 'true' );

define ( 'WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true' );

define ( 'WARN_SESSION_AUTO_START', 'true' );

define ( 'WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true' );

// START STS 4.1

require (DIR_WS_CLASSES . 'sts.php');

$sts = new sts ();

$sts->start_capture ();

// END STS 4.1





require (DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php'); // ICW CREDIT CLASS Gift Voucher Addittion





// Include

require (DIR_WS_INCLUDES . 'affiliate_application_top.php');



// PWA BOF

if (tep_session_is_registered ( 'customer_id' ) && $customer_id == 0 && substr ( basename ( $PHP_SELF ), 0, 7 ) == 'account')

	tep_redirect ( tep_href_link ( FILENAME_SHOPPING_CART ) );

	// PWA EOF

define ( 'DIR_WS_RSS', DIR_WS_INCLUDES . 'modules/faqdesk/rss/' );

require_once ('includes/functions/clean_html_comments.php');

// Supertracker

require (DIR_WS_CLASSES . 'supertracker.php');

$tracker = new supertracker ();

$tracker->update ();



//bof year make model

$expire = time () + 60 * 60 * 24 * 90;

$where = "";

$YMM_where = "";



if (isset ( $_GET ['Make'] )) {

	$_SESSION ['Make_selected'] = $_GET ['Make'];

	if ($_GET ['Make'] != 'all')

		$Make_selected_var = $_GET ['Make'];

} elseif (isset ( $_SESSION ['Make_selected'] ) && $_SESSION ['Make_selected'] != 'all')

	$Make_selected_var = $_SESSION ['Make_selected'];



if (isset ( $_GET ['Model'] )) {

	$_SESSION ['Model_selected'] = $_GET ['Model'];

	if ($_GET ['Model'] != 'all')

		$Model_selected_var = $_GET ['Model'];

} elseif (isset ( $_SESSION ['Model_selected'] ) && $_SESSION ['Model_selected'] != 'all')

	$Model_selected_var = $_SESSION ['Model_selected'];



if (isset ( $_GET ['Year'] )) {

	$_SESSION ['Year_selected'] = $_GET ['Year'];

	if ($_GET ['Year'] != 0)

		$Year_selected_var = $_GET ['Year'];

} elseif (isset ( $_SESSION ['Year_selected'] ) && $_SESSION ['Year_selected'] != 0)

	$Year_selected_var = $_SESSION ['Year_selected'];



if (isset ( $Make_selected_var ))

	$where .= " (products_car_make='" . $Make_selected_var . "' ) ";



if (isset ( $Model_selected_var ))

	$where .= ($where != '' ? ' and ' : '') . " (products_car_model='" . $Model_selected_var . "') ";



if (isset ( $Year_selected_var ))

	$where .= ($where != '' ? ' and ' : '') . " ((products_car_year_bof <= '" . $Year_selected_var . "' and products_car_year_eof >= '" . $Year_selected_var . "')) ";



if ($where != '') {



	$q = tep_db_query ( "SELECT DISTINCT products_id FROM products_ymm WHERE " . $where );



	$ids = '';



	if (mysql_num_rows ( $q ) > 0) {



		while ( $r = tep_db_fetch_array ( $q ) )

			$ids .= ($ids != '' ? ',' : '') . $r ['products_id'];



	}



	$q = tep_db_query ( "SELECT products_id FROM " . TABLE_PRODUCTS . " WHERE products_id not in (SELECT DISTINCT products_id FROM products_ymm)  and products_status = 1" );



	if (mysql_num_rows ( $q ) > 0) {



		while ( $r = tep_db_fetch_array ( $q ) )

			$ids .= ($ids != '' ? ',' : '') . $r ['products_id'];



	}



	$YMM_where .= " p.products_id in ($ids) and ";



}

//eof year make model