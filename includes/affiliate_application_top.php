<?php
/*
  $Id: affiliate_application_top.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/


// Set the local configuration parameters - mainly for developers
require(DIR_WS_INCLUDES . 'affiliate_configure.php');

  require(DIR_WS_INCLUDES . 'affiliate_configure.php');
  require(DIR_WS_FUNCTIONS . 'affiliate_functions.php');

// define the database table names used in the contribution
  define('TABLE_AFFILIATE', 'affiliate_affiliate');
  define('TABLE_AFFILIATE_NEWS', 'affiliate_news');

// if you change this -> affiliate_show_banner must be changed too
  define('TABLE_AFFILIATE_BANNERS', 'affiliate_banners');
  define('TABLE_AFFILIATE_BANNERS_HISTORY', 'affiliate_banners_history');
  define('TABLE_AFFILIATE_CLICKTHROUGHS', 'affiliate_clickthroughs');
  define('TABLE_AFFILIATE_SALES', 'affiliate_sales');
  define('TABLE_AFFILIATE_PAYMENT', 'affiliate_payment');
  define('TABLE_AFFILIATE_PAYMENT_STATUS', 'affiliate_payment_status');
  define('TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY', 'affiliate_payment_status_history');

// define the filenames used in the project
  define('FILENAME_AFFILIATE_SUMMARY', 'affiliate_summary.php');
  define('FILENAME_AFFILIATE_LOGOUT', 'affiliate_logout.php');
  define('FILENAME_AFFILIATE', 'affiliate_affiliate.php'); 
  define('FILENAME_AFFILIATE_CONTACT', 'affiliate_contact.php');
  define('FILENAME_AFFILIATE_FAQ', 'affiliate_faq.php');
  define('FILENAME_AFFILIATE_ACCOUNT', 'affiliate_details.php');
  define('FILENAME_AFFILIATE_DETAILS', 'affiliate_details.php');
  define('FILENAME_AFFILIATE_DETAILS_OK', 'affiliate_details_ok.php');
  define('FILENAME_AFFILIATE_TERMS','affiliate_terms.php');
  define('FILENAME_AFFILIATE_TERMS_POPUP','affiliate_terms_popup.php');
  define('FILENAME_AFFILIATE_NEWS', 'affiliate_news.php');
  define('FILENAME_AFFILIATE_NEWSLETTER', 'affiliate_newsletter.php');

  define('FILENAME_AFFILIATE_HELP_1', 'affiliate_help1.php');
  define('FILENAME_AFFILIATE_HELP_2', 'affiliate_help2.php');
  define('FILENAME_AFFILIATE_HELP_3', 'affiliate_help3.php');
  define('FILENAME_AFFILIATE_HELP_4', 'affiliate_help4.php');
  define('FILENAME_AFFILIATE_HELP_5', 'affiliate_help5.php');
  define('FILENAME_AFFILIATE_HELP_6', 'affiliate_help6.php');
  define('FILENAME_AFFILIATE_HELP_7', 'affiliate_help7.php');
  define('FILENAME_AFFILIATE_HELP_8', 'affiliate_help8.php');
  define('FILENAME_AFFILIATE_HELP_9', 'affiliate_help9.php');
  define('FILENAME_AFFILIATE_HELP_10', 'affiliate_help10.php');
  define('FILENAME_AFFILIATE_HELP_11', 'affiliate_help11.php');
  define('FILENAME_AFFILIATE_HELP_12', 'affiliate_help12.php');
  define('FILENAME_AFFILIATE_HELP_13', 'affiliate_help13.php');
  define('FILENAME_AFFILIATE_HELP_14', 'affiliate_help14.php');
  define('FILENAME_AFFILIATE_HELP_15', 'affiliate_help15.php');
  define('FILENAME_AFFILIATE_HELP_16', 'affiliate_help16.php');
  define('FILENAME_AFFILIATE_HELP_17', 'affiliate_help17.php');
  define('FILENAME_AFFILIATE_HELP_18', 'affiliate_help18.php');
  define('FILENAME_AFFILIATE_HELP_19', 'affiliate_help19.php');
  define('FILENAME_AFFILIATE_HELP_20', 'affiliate_help20.php');
  define('FILENAME_AFFILIATE_HELP_21', 'affiliate_help21.php');
  define('FILENAME_AFFILIATE_HELP_22', 'affiliate_help22.php');
	define('FILENAME_AFFILIATE_BANNERS_CATEGORY', 'affiliate_banners_category.php');
  define('FILENAME_AFFILIATE_BANNERS_BUILD_CAT', 'affiliate_banners_build_cat.php');
  define('FILENAME_AFFILIATE_VALIDCATS', 'affiliate_validcats.php');
  define('FILENAME_AFFILIATE_INFO', 'affiliate_info.php');
	define('TABLE_AFFILIATE_NEWS_CONTENTS', 'affiliate_news_contents');
  define('FILENAME_AFFILIATE_BANNERS', 'affiliate_banners.php');
  define('FILENAME_AFFILIATE_BANNERS_BANNERS', 'affiliate_banners_banners.php');
  define('FILENAME_AFFILIATE_BANNERS_BUILD', 'affiliate_banners_build.php');
  define('FILENAME_AFFILIATE_BANNERS_PRODUCT', 'affiliate_banners_product.php');
  define('FILENAME_AFFILIATE_BANNERS_TEXT', 'affiliate_banners_text.php');
  define('FILENAME_AFFILIATE_SHOW_BANNER', 'affiliate_show_banner.php');

  define('FILENAME_AFFILIATE_REPORTS', 'affiliate_reports.php');
  define('FILENAME_AFFILIATE_CLICKS', 'affiliate_clicks.php');
  define('FILENAME_AFFILIATE_VALIDPRODUCTS', 'affiliate_validproducts.php');

  define('FILENAME_AFFILIATE_PASSWORD', 'affiliate_password.php');
  define('FILENAME_AFFILIATE_PASSWORD_FORGOTTEN', 'affiliate_password_forgotten.php');

  define('FILENAME_AFFILIATE_LOGOUT', 'affiliate_logout.php');
  define('FILENAME_AFFILIATE_SALES', 'affiliate_sales.php');
  define('FILENAME_AFFILIATE_SIGNUP', 'affiliate_signup.php');

  define('FILENAME_AFFILIATE_SIGNUP_OK', 'affiliate_signup_ok.php');
  define('FILENAME_AFFILIATE_PAYMENT', 'affiliate_payment.php');

// include the language translations
  require(DIR_WS_LANGUAGES . 'affiliate_' . $language . '.php');

  $affiliate_clientdate = (date ("Y-m-d H:i:s"));
  $affiliate_clientbrowser = $_SERVER["HTTP_USER_AGENT"];
  $affiliate_clientip = $_SERVER["REMOTE_ADDR"];
  $affiliate_clientreferer = $_SERVER["HTTP_REFERER"];

  if (!$_SESSION['affiliate_ref']) {
    tep_session_register('affiliate_ref');
    tep_session_register('affiliate_clickthroughs_id');
    if (($_GET['ref'] || $_POST['ref'])) {
      if ($_GET['ref']) $affiliate_ref = $_GET['ref'];
      if ($_POST['ref']) $affiliate_ref = $_POST['ref'];
      if ($_GET['products_id']) $affiliate_products_id = $_GET['products_id'];
      if ($_POST['products_id']) $affiliate_products_id = $_POST['products_id'];
      if ($_GET['affiliate_banner_id']) $affiliate_banner_id = $_GET['affiliate_banner_id'];
      if ($_POST['affiliate_banner_id']) $affiliate_banner_id = $_POST['affiliate_banner_id'];

      if (!$link_to) $link_to = "0";
      $sql_data_array = array('affiliate_id' => $affiliate_ref,
                              'affiliate_clientdate' => $affiliate_clientdate,
                              'affiliate_clientbrowser' => $affiliate_clientbrowser,
                              'affiliate_clientip' => $affiliate_clientip,
                              'affiliate_clientreferer' => $affiliate_clientreferer,
                              'affiliate_products_id' => $affiliate_products_id,
                              'affiliate_banner_id' => $affiliate_banner_id);
      tep_db_perform(TABLE_AFFILIATE_CLICKTHROUGHS, $sql_data_array);
      $affiliate_clickthroughs_id = tep_db_insert_id();

   // Banner has been clicked, update stats:
      if ($affiliate_banner_id && $affiliate_ref) {
        $today = date('Y-m-d');
        $sql = "select * from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . $affiliate_banner_id  . "' and  affiliate_banners_affiliate_id = '" . $affiliate_ref . "' and affiliate_banners_history_date = '" . $today . "'";
        $banner_stats_query = tep_db_query($sql);

     // Banner has been shown today
        if (tep_db_fetch_array($banner_stats_query)) {
        tep_db_query("update " . TABLE_AFFILIATE_BANNERS_HISTORY . " set affiliate_banners_clicks = affiliate_banners_clicks + 1 where affiliate_banners_id = '" . $affiliate_banner_id . "' and affiliate_banners_affiliate_id = '" . $affiliate_ref. "' and affiliate_banners_history_date = '" . $today . "'");
   // Initial entry if banner has not been shown
      } else {
        $sql_data_array = array('affiliate_banners_id' => $affiliate_banner_id,
                                'affiliate_banners_products_id' => $affiliate_products_id,
                                'affiliate_banners_affiliate_id' => $affiliate_ref,
                                'affiliate_banners_clicks' => '1',
                                'affiliate_banners_history_date' => $today);
        tep_db_perform(TABLE_AFFILIATE_BANNERS_HISTORY, $sql_data_array);
      }
    }

 // Set Cookie if the customer comes back and orders it counts
    setcookie('affiliate_ref', $affiliate_ref, time() + AFFILIATE_COOKIE_LIFETIME);
    }
    if ($_COOKIE['affiliate_ref']) { // Customer comes back and is registered in cookie
      $affiliate_ref = $_COOKIE['affiliate_ref'];
    }
  }

////
// Compatibility to older Snapshots

// set the type of request (secure or not)
  if (!isset($request_type)) $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// Emulate the breadcrumb class
  if (!class_exists(breadcrumb)) {
    class breadcrumb {
      function add($title, $link = '') {
        global $location;
        $location='&raquo; <a href="' . $link . '" class="headerNavigation">' . $title . '</a>';
      }
    }
    $breadcrumb = new breadcrumb;
  }
?>
