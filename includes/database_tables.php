<?php
/*
  $Id: database_tables.php,v 1.1 2003/03/14 02:10:58 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
//MVS Start
  define('TABLE_ORDERS_SHIPPING','orders_shipping');
  define('TABLE_VENDORS', 'vendors');
  define('TABLE_VENDOR_CONFIGURATION', 'vendor_configuration');
  define('TABLE_VENDOR_CONFIGURATION_GROUP','vendor_configuration_group');
//addon for UPSXML dimensions
// Begin RMA Returns System
  define('TABLE_RETURN_REASONS', 'return_reasons');
  define('TABLE_RETURNS', 'returned_products');
  define('TABLE_RETURNS_STATUS', 'returns_status');
  define('TABLE_RETURNS_TEXT', 'return_text');
  define('TABLE_RETURNS_TOTAL', 'returns_total');
  define('TABLE_RETURNS_PRODUCTS_DATA', 'returns_products_data');
  define('TABLE_RETURN_PAYMENTS', 'refund_payments');
  define('TABLE_REFUND_METHOD', 'refund_method');
  define('TABLE_RETURNS_STATUS_HISTORY', 'returns_status_history');
// End RMA Returns System
  define('TABLE_PACKAGING', 'packaging');
//addon for UPSXML dimensions
//MVS End
// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_CUSTOMERS_POINTS_PENDING', 'customers_points_pending');//Points/Rewards Module V2.00
define('TABLE_FILES_UPLOADED', 'files_uploaded');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');
  define('TABLE_PRODUCTS_EXTRA_IMAGES', 'products_extra_images'); //Added for Extra Images Contribution
  define('TABLE_NEWSLETTER', 'maillist');
//++++ QT Pro: Begin Changed code
  define('TABLE_PRODUCTS_STOCK', 'products_stock');
//++++ QT Pro: End Changed Code

//Family products: Begin Changed code
 define('TABLE_FAMILIES', 'families');
define('TABLE_PRODUCTS_FAMILIES', 'products_families');
//Family: End Changed code

// Added for Xsell Products Mod
define('TABLE_PRODUCTS_XSELL', 'products_xsell');


define('TABLE_ARTICLE_REVIEWS', 'article_reviews');
define('TABLE_ARTICLE_REVIEWS_DESCRIPTION', 'article_reviews_description');
define('TABLE_ARTICLES', 'articles');
define('TABLE_ARTICLES_DESCRIPTION', 'articles_description');
define('TABLE_ARTICLES_TO_TOPICS', 'articles_to_topics');
define('TABLE_ARTICLES_XSELL', 'articles_xsell');
define('TABLE_AUTHORS', 'authors');
define('TABLE_AUTHORS_INFO', 'authors_info');
define('TABLE_TOPICS', 'topics');
define('TABLE_TOPICS_DESCRIPTION', 'topics_description');

 // START: Product Extra Fields
  define('TABLE_PRODUCTS_EXTRA_FIELDS', 'products_extra_fields');
  define('TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS', 'products_to_products_extra_fields');
  // END: Product Extra Fields

  define('TABLE_FAQDESK', 'faqdesk');
  define('TABLE_FAQDESK_DESCRIPTION', 'faqdesk_description');
  define('TABLE_FAQDESK_TO_CATEGORIES', 'faqdesk_to_categories');
  define('TABLE_FAQDESK_CATEGORIES', 'faqdesk_categories');
  define('TABLE_FAQDESK_CATEGORIES_DESCRIPTION', 'faqdesk_categories_description');
  define('TABLE_FAQDESK_CONFIGURATION', 'faqdesk_configuration');
  define('TABLE_FAQDESK_CONFIGURATION_GROUP', 'faqdesk_configuration_group');
  define('TABLE_FAQDESK_REVIEWS', 'faqdesk_reviews');
  define('TABLE_FAQDESK_REVIEWS_DESCRIPTION', 'faqdesk_reviews_description');

  // BEGIN newdesk
define('TABLE_NEWSDESK', 'newsdesk');
define('TABLE_NEWSDESK_DESCRIPTION', 'newsdesk_description');
define('TABLE_NEWSDESK_TO_CATEGORIES', 'newsdesk_to_categories');
define('TABLE_NEWSDESK_CATEGORIES', 'newsdesk_categories');
define('TABLE_NEWSDESK_CATEGORIES_DESCRIPTION', 'newsdesk_categories_description');
define('TABLE_NEWSDESK_CONFIGURATION', 'newsdesk_configuration');
define('TABLE_NEWSDESK_CONFIGURATION_GROUP', 'newsdesk_configuration_group');

define('TABLE_NEWSDESK_REVIEWS', 'newsdesk_reviews');
define('TABLE_NEWSDESK_REVIEWS_DESCRIPTION', 'newsdesk_reviews_description');
// END newsdesk

define('TABLE_WISHLIST', 'customers_wishlist');
define('TABLE_WISHLIST_ATTRIBUTES', 'customers_wishlist_attributes');
// VJ Links Manager v1.00 begin
  define('TABLE_LINK_CATEGORIES', 'link_categories');
  define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');
  define('TABLE_LINKS', 'links');
  define('TABLE_LINKS_DESCRIPTION', 'links_description');
  define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');
  define('TABLE_LINKS_STATUS', 'links_status');
  define('TABLE_LINKS_FEATURED', 'links_featured');
// VJ Links Manager v1.00 end

// BOF Separate Pricing per Customer
  define('TABLE_PRODUCTS_GROUPS', 'products_groups');
  define('TABLE_SPECIALS_RETAIL_PRICES', 'specials_retail_prices');
  define('TABLE_PRODUCTS_GROUP_PRICES', 'products_group_prices_cg_');
  define('TABLE_CUSTOMERS_GROUPS', 'customers_groups');
  // this will define the maximum time in minutes between updates of a products_group_prices_cg_# table
  // changes in table specials will trigger an immediate update if a query needs this particular table
  define('MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE', '15');
  // EOF Separate Pricing per Customer

  define('TABLE_ADMINISTRATORS', 'admin');

  //events_calendar
define('TABLE_EVENTS_CALENDAR', 'events_calendar');

// start indvship
  define('TABLE_PRODUCTS_SHIPPING', 'products_shipping');
  // end indvship
//Package Tracking Plus END
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS', 'products_attributes_download_groups');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES', 'products_attributes_download_groups_files');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES', 'products_attributes_download_groups_to_files');
  define('TABLE_PHPIDS', 'phpids_intrusions');
  define('TABLE_BANNED_IP', 'banned_ip');
/* CCGV - BEGIN */
  define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');
  define('TABLE_COUPON_GV_QUEUE', 'coupon_gv_queue');
  define('TABLE_COUPON_REDEEM_TRACK', 'coupon_redeem_track');
  define('TABLE_COUPON_EMAIL_TRACK', 'coupon_email_track');
  define('TABLE_COUPONS', 'coupons');
  define('TABLE_COUPONS_DESCRIPTION', 'coupons_description');
/* CCGV - END */

///Mail Manager
define('TABLE_MM_RESPONSEMAIL', 'mm_responsemail');
define('TABLE_MM_RESPONSEMAIL_RESTORE', 'mm_responsemail_backup');
define('TABLE_MM_RESPONSEMAIL_RESET', 'mm_responsemail_reset');
define('TABLE_MM_TEMPLATES', 'mm_templates');
define('TABLE_MM_BULKMAIL', 'mm_bulkmail');


?>
