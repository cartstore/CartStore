<?php

/*

 $Id: sts_column_left.php,v 4.1 2006/03/05 22:06:41 rigadin Exp $

 CartStore eCommerce Software, for The Next Generation

 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible

 BASED ON STS v4.1 by Rigadin (rigadin@osc-help.net)

 Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com

 */

$sts -> start_capture();

// Get categories box from db or cache

if ((USE_CACHE == 'true') && empty($SID)) {

//	echo tep_cache_categories_box();

} else {

	include (DIR_WS_BOXES . 'categories.php');

}

$sts -> restart_capture('categorybox', 'box');

// Get manufacturer box from db or cache

if ((USE_CACHE == 'true') && empty($SID)) {

	echo tep_cache_manufacturers_box();

} else {

	include (DIR_WS_BOXES . 'manufacturers.php');

}

$sts -> restart_capture('manufacturerbox', 'box');

include (DIR_WS_BOXES . 'manufacturers_logos.php');

$sts -> restart_capture('manufacturers_logos', 'box');

//$sts->restart_capture ( 'links', 'box' );

//include (DIR_WS_BOXES . 'links.php');

// include(DIR_WS_BOXES . 'categorybox3.php');

// $sts->restart_capture ('categorybox3', 'none');

//require (DIR_WS_BOXES . 'information.php');

//$sts->restart_capture ( 'informationbox', 'box' ); // Get information box

include (DIR_WS_BOXES . 'quickfinder.php');

$sts -> restart_capture('quickfinder', 'box');

include (DIR_WS_BOXES . 'newsdesk.php');

$sts -> restart_capture('newsdekbox', 'box');

include (DIR_WS_BOXES . 'categorybox2.php');

$sts -> restart_capture('categorybox2', 'box');

include (DIR_WS_BOXES . 'categorybox5.php');

$sts -> restart_capture('categorybox5', 'box');

include (DIR_WS_BOXES . 'categorybox6.php');
$sts -> restart_capture('categorybox6', 'box');

include (DIR_WS_BOXES . 'shop_by_price.php');

$sts -> restart_capture('shop_by_price', 'box');

include (DIR_WS_MODULES . 'sorter.php');

$sts -> restart_capture('sorter', 'box');

include (DIR_WS_BOXES . 'articles.php');

$sts -> restart_capture('articles', 'box');

//require (DIR_WS_BOXES . 'menu_articles.php');

//$sts->restart_capture ( 'menu_articles', 'box' );

include (DIR_WS_BOXES . 'faqdesk.php');

$sts -> restart_capture('faqdeskbox', 'box');

include (DIR_WS_BOXES . 'year_make_model.php');

$sts -> restart_capture('year_make_model', 'box');



//require (DIR_WS_BOXES . 'whats_new.php');
// to slow query
//$sts -> restart_capture('whatsnewbox', 'box');

require (DIR_WS_BOXES . 'searchbox.php');

$sts -> restart_capture('searchbox', 'box');

require (DIR_WS_BOXES . 'search.php');

$sts -> restart_capture('search', 'box');

 
// Get information box

require (DIR_WS_MODULES . 'viewed_products.php');


$sts -> restart_capture('viewed_products', 'box');


//slow query
//require (DIR_WS_MODULES . 'upcoming_products.php');

//$sts -> restart_capture('upcoming_products', 'box');


// Get information box

require (DIR_WS_BOXES . 'shopping_cart.php');

$sts -> restart_capture('cartbox', 'box');
// Get shopping cart box

require (DIR_WS_BOXES . 'shopping_cart2.php');

$sts -> restart_capture('cartbox2', 'box');
// Get shopping cart box

if (isset($_GET['products_id']))

	include (DIR_WS_BOXES . 'manufacturer_info.php');

$sts -> restart_capture('maninfobox', 'box');
// Get manufacturer info box (empty if no product selected)

if (tep_session_is_registered('customer_id'))

	include (DIR_WS_BOXES . 'order_history.php');

$sts -> restart_capture('orderhistorybox', 'box');
// Get customer's order history box (empty if visitor not logged)

include (DIR_WS_BOXES . 'best_sellers.php');

$sts -> restart_capture('bestsellersbox_only', 'box');

include (DIR_WS_BOXES . 'selected_vehicle.php');

$sts -> restart_capture('selected_vehicle', 'box');

include (DIR_WS_BOXES . 'login.php');

$sts -> restart_capture('login', 'box');

include (DIR_WS_BOXES . 'newsletter.php');

$sts -> restart_capture('newsletter', 'box');

include (DIR_WS_BOXES . 'adsence.php');

$sts -> restart_capture('adsence', 'box');

include (DIR_WS_BOXES . 'adsence2.php');

$sts -> restart_capture('adsence2', 'box');

include (DIR_WS_BOXES . 'adsence3.php');

$sts -> restart_capture('adsence3', 'box');

include (DIR_WS_BOXES . 'rss_news.php');

$sts -> restart_capture('rss', 'box');

include (DIR_WS_BOXES . 'rss_news2.php');

$sts -> restart_capture('rss2', 'box');

include (DIR_FS_CATALOG . 'templates/system/event_calender/calendar.php');

$sts -> restart_capture('calendar', 'box');

if (isset($_GET['products_id'])) {

	include (DIR_WS_BOXES . 'product_notifications.php');

	$sts -> restart_capture('notificationbox', 'box');

	if (tep_session_is_registered('customer_id')) {

		$check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . ( int )$customer_id . "' and global_product_notifications = '1'");

		$check = tep_db_fetch_array($check_query);

		if ($check['count'] > 0) {

			$sts -> template['bestsellersbox'] = $sts -> template['bestsellersbox_only'];
			// Show bestseller box if customer asked for general notifications

		} else {

			$sts -> template['bestsellersbox'] = $sts -> template['notificationbox'];
			// Otherwise select notification box

		}

	} else {

		$sts -> template['bestsellersbox'] = $sts -> template['notificationbox'];
		//

	}

} else {

	$sts -> template['bestsellersbox'] = $sts -> template['bestsellersbox_only'];

	$sts -> template['notificationbox'] = '';

}

include (DIR_WS_BOXES . 'specials.php');

$sts -> restart_capture('specialbox', 'box');
// Get special box

include (DIR_WS_MODULES . 'banner_rotator.php');

$sts -> restart_capture('banner_rotator', 'box');
// Get special box

$sts -> template['specialfriendbox'] = $sts -> template['specialbox'];
// Shows specials or tell a friend

include (DIR_WS_BOXES . 'newsdesk_latest.php');

$sts -> restart_capture('newsdesk_latest', 'box');

if (isset($_GET['products_id']) && basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) {

	include (DIR_WS_BOXES . 'tell_a_friend.php');

	$sts -> restart_capture('tellafriendbox', 'box');
	// Get tell a friend box

	$sts -> template['specialfriendbox'] = $sts -> template['tellafriendbox'];
	// Shows specials or tell a friend

} else

	$sts -> template['tellafriendbox'] = '';

// Get languages and currencies boxes, empty if in checkout procedure

if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {

	include (DIR_WS_BOXES . 'languages.php');

	$sts -> restart_capture('languagebox', 'box');

	include (DIR_WS_BOXES . 'currencies.php');

	$sts -> restart_capture('currenciesbox', 'box');

} else {

	$sts -> template['languagebox'] = '';

	$sts -> template['currenciesbox'] = '';

}


require(DIR_WS_BOXES . 'sociallogin.php');
$sts -> restart_capture('sociallogin', 'box');

if (basename($PHP_SELF) != FILENAME_PRODUCT_REVIEWS_INFO) {

	require (DIR_WS_BOXES . 'reviews.php');

	$sts -> restart_capture('reviewsbox', 'box');
	// Get the reviews box

} else {

	$sts -> template['reviewsbox'] = '';

}
?>