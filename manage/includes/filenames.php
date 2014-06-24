<?php
/*
  $Id: filenames.php,v 1.1 2003/06/20 00:18:30 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
//Admin begin
  define('FILENAME_ADMIN_ACCOUNT', 'admin_account.php');
  define('FILENAME_ADMIN_FILES', 'admin_files.php');
  define('FILENAME_ADMIN_MEMBERS', 'admin_members.php');
   //Mett
   // RMA Returns System
  define('FILENAME_RETURNS', 'returns.php');
  define('FILENAME_RETURN', 'return_product.php');
  define('FILENAME_RETURN_CONFIRM', 'return_confirm.php');
  define('FILENAME_RETURN_EMAILS', 'return_emails.php');
  define('FILENAME_RETURNS_REASONS', 'returns_reasons.php');
  define('FILENAME_RETURNS_TEXT', 'return_text.php');
  define('FILENAME_RETURNS_STATUS', 'returns_status.php');
  define('FILENAME_REFUND_METHODS', 'refund_methods.php');
  define('FILENAME_RETURNS_INVOICE', 'returns_invoice.php');
  define('FILENAME_ADMIN_MEMBERS_EDIT', 'admin_members_edit.php');
 //end Mett
define('FILENAME_FORBIDEN', 'forbiden.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
    //Product Inventory Editor START
  define('FILENAME_INVENTORY_EDITOR', 'product_list.php');
  //Product Inventory Editor END
//Admin end
// ########## Ajout/edite commande et compte client ##########
// Create Order & customers
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_CREATE_ORDER_PROCESS', 'create_order_process.php');
  define('FILENAME_CREATE_ORDER', 'create_order.php');
  define('FILENAME_EDIT_ORDERS', 'edit_orders.php');
define('FILENAME_DEALERS', 'dealer_admin.php');
// ########## END - Ajout/edite commande et compte client ##########

// Google site maps
define('FILENAME_GOOGLE_SITEMAPS', 'sitemaps.index.php');
//Fine

define('FILENAME_GOOGLE_SITEMAP', 'googlesitemap.php');

define('FILENAME_STATS_AD_RESULTS', 'stats_ad_results.php');
  define('FILENAME_BACKUP', 'backup.php');
  define('FILENAME_BANNER_MANAGER', 'banner_manager.php');
  define('FILENAME_BANNER_STATISTICS', 'banner_statistics.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');

  //Package Tracking Plus BEGIN
  define('FILENAME_CATALOG_TRACKING_NUMBER', 'tracking.php');
//Package Tracking Plus END
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'countries.php');
  define('FILENAME_CURRENCIES', 'currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  // Points/Rewards Module V2.00 BOF
  define('FILENAME_CUSTOMERS_POINTS', 'customers_points.php');
  define('FILENAME_CUSTOMERS_POINTS_PENDING', 'customers_points_pending.php');
  define('FILENAME_CUSTOMERS_POINTS_REFERRAL', 'customers_points_referral.php');
  define('FILENAME_CATALOG_MY_POINTS', 'my_points.php');
  define('FILENAME_CATALOG_MY_POINTS_HELP', 'my_points_help.php');
// Points/Rewards Module V2.00 EOF
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DEFINE_LANGUAGE', 'define_language.php');
  define('FILENAME_FILE_MANAGER', 'file_manager.php');
  define('FILENAME_GEO_ZONES', 'geo_zones.php');
  define('FILENAME_LANGUAGES', 'languages.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_MODULES', 'modules.php');
 define('FILENAME_MYSQL_PERFORMANCE', 'mysqlperformance.php');
  define('FILENAME_NEWSLETTERS', 'newsletters.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_ORDERS_INVOICE', 'invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'packingslip.php');
  define('FILENAME_ORDERS_STATUS', 'orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  // sort product options
  define('FILENAME_PRODUCTS_OPTIONS', 'products_options.php');
  define('FILENAME_PRODUCTS_OPTIONS_VALUES', 'products_options_values.php');
  // end sort product options
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SOCIALRUNNERCONNECTOR', 'SocialRunnerConnector.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_TERMS_CONDITIONS', 'terms_conditions.php') ;
  define('FILENAME_TERMS_CONDITIONS_CONTENT', 'terms_conditions_content.php') ;
  define('FILENAME_WA_TAXES_REPORT', 'wa_taxes_report.php');
  define('FILENAME_WHOS_ONLINE', 'whos_online.php');
  define('FILENAME_ZONES', 'zones.php');
  //MVS and vendors email Start
  define('FILENAME_VENDORS', 'vendors.php');
  define('FILENAME_VENDOR_MODULES', 'vendor_modules.php');
  define('FILENAME_PRODS_VENDORS', 'prods_by_vendor.php');
  define('FILENAME_ORDERS_VENDORS', 'orders_by_vendor.php');
  define('FILENAME_VENDORS_EMAIL_SEND', 'vendor_email_send.php');
  define('FILENAME_MOVE_VENDORS', 'move_vendor_prods.php');
   define('FILENAME_STATS_VENDORS', 'stats_sales_report2.php');


//MVS and vendors email End
  define('FILENAME_HEADER_TAGS_CONTROLLER', 'header_tags_controller.php');
define('FILENAME_HEADER_TAGS_ENGLISH', 'header_tags_english.php');
define('FILENAME_HEADER_TAGS_FILL_TAGS', 'header_tags_fill_tags.php');

define('FILENAME_PRODUCTS_EXTRA_IMAGES','products_extra_images.php'); //Added for Extra Images Contribution
define('FILENAME_PRODUCTS_EXTRA_IMAGES','products_extra_images.php'); //Added for Extra Images Contribution
define('FILENAME_STATS_MONTHLY_SALES', 'stats_monthly_sales.php');
  define('FILENAME_SEO_ASSISTANT', 'seo_assistant.php');
//++++ QT Pro: Begin Changed code
  define('FILENAME_STATS_LOW_STOCK_ATTRIB', 'stats_low_stock_attrib.php');
  define('FILENAME_STOCK', 'stock.php');
//++++ QT Pro: End Changed Code

//Family products: Begin Changed code
define('FILENAME_MODIFY_FAMILIES', 'modify_families.php');
define('FILENAME_FAMILY_PRODUCTS', 'family_products.php');
define('FILENAME_SELECT_FAMILY_DISPLAY', 'select_family_display.php');
define('FILENAME_ASSIGN_FAMILIES', 'assign_families.php');
define('FILENAME_VIEW_FAMILIES', 'view_families.php');
define('FILENAME_PRODUCT_INFO', 'product_info.php');
//Family: End Changed code

  define('FILENAME_XSELL_PRODUCTS', 'xsell.php');
//Options as Images Mod
  define ('FILENAME_OPTIONS_IMAGES', 'options_images.php');
  define('FILENAME_ARTICLE_REVIEWS', 'article_reviews.php');
  define('FILENAME_ARTICLES', 'articles.php');
  define('FILENAME_ARTICLES_CONFIG', 'articles_config.php');
  define('FILENAME_ARTICLES_XSELL', 'articles_xsell.php');
  define('FILENAME_AUTHORS', 'authors.php');

  // START: Product Extra Fields
  define('FILENAME_PRODUCTS_EXTRA_FIELDS', 'product_extra_fields.php');
// END: Product Extra Fields

  define('FILENAME_FAQDESK', 'faqdesk.php');
  define('FILENAME_FAQDESK_CONFIGURATION', 'faqdesk_configuration.php');
  define('FILENAME_FAQDESK_REVIEWS', 'faqdesk_reviews.php');
  define('EDITOR_IMAGE', DIR_WS_INCLUDES . 'modules/faqdesk/html_editor/editor_images');

  // Begin newsdesk
define('FILENAME_NEWSDESK', 'newsdesk.php');
define('FILENAME_NEWSDESK_CONFIGURATION', 'newsdesk_configuration.php');
define('FILENAME_NEWSDESK_REVIEWS', 'newsdesk_reviews.php');

define('EDITOR_IMAGE', DIR_WS_INCLUDES . 'modules/newsdesk/html_editor/editor_images');
// End newsdesk

// VJ Links Manager v1.00 begin
  define('FILENAME_LINKS', 'link_manage.php');
  define('FILENAME_LINK_CATEGORIES', 'link_manage.php');
  define('FILENAME_LINKS_CONTACT', 'links_contact.php');
  define('FILENAME_LINKS_CHECK', 'links_check.php');
  define('FILENAME_LINKS_FEATURED', 'links_featured.php');
  define('FILENAME_LINKS_STATUS', 'links_status.php');
// VJ Links Manager v1.00 end

  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_CREATE_ORDER_PROCESS', 'create_order_process.php');
  define('FILENAME_CREATE_ORDER', 'create_order.php');
  define('FILENAME_EDIT_ORDERS', 'edit_orders.php');
  define('FILENAME_ORDERS_EDIT', 'edit_orders.php');
 define('FILENAME_QBI', 'qbi_create.php');
  //KIKOLEPPARD New attribute manager start
define('FILENAME_NEW_ATTRIBUTE_MANAGER', 'new_attribute_manager.php');
define('FILENAME_STATS_SALES_REPORT2', 'stats_sales_report2.php');
//KIKOLEPPARD New attribute manager end
 define('FILENAME_SUPERTRACKER', 'supertracker.php');
    //RSS News
   define('FILENAME_RSS_NEWS', 'rss_news.php');
   define('FILENAME_RSS_NEWS_CREATE', 'rss_news_create.php');
     //DELIVERY MODULE - START
  /*#########Added By Skywebapps#################*/
  define('FILENAME_DEFAULT_DELIVERY_TIME', 'default_delivery_time.php');
  define('FILENAME_EMERGENCY_DELIVERY_TIME', 'special_time.php');
  //DELIVERY MODULE - END
    define('FILENAME_RECOVER_CART_SALES', 'recover_cart_sales.php');
  define('FILENAME_STATS_RECOVER_CART_SALES', 'stats_recover_cart_sales.php');
    define('FILENAME_CATALOG_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_CATALOG_ACCOUNT', 'account.php');
  define('FILENAME_CATALOG_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_CATALOG_LOGIN', 'login.php');
  //events_calendar
define('FILENAME_EVENTS_MANAGER', 'events_manager.php');
define('FILENAME_CONSTANT_CONTACT', 'constant_contact.php');
define('FILENAME_PHPIDS', 'phpids_report.php');
define('FILENAME_BANNED_IP', 'banned_ip.php');
// LINE ADDED: CREDIT CLASS Gift Voucher Contribution
  define('FILENAME_STATS_CREDITS', 'stats_credits.php');

// add CCGV define begin
  define('FILENAME_GV_QUEUE', 'gv_queue.php');
  define('FILENAME_GV_MAIL', 'gv_mail.php');
  define('FILENAME_GV_SENT', 'gv_sent.php');
  define('FILENAME_COUPON_ADMIN', 'coupon_admin.php');
// add CCGV define end

?>