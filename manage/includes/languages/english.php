<?php

/*

  $Id: english.php,v 1.106 2003/06/20 00:18:31 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/

//Admin begin

// header text in includes/header.php

define('HEADER_TITLE_ACCOUNT', 'My Account');

define('HEADER_TITLE_LOGOFF', 'Logoff');

//Product Inventory Editor START

define('BOX_CATALOG_INVENTORY_EDITOR', 'Inventory Editor');

//Product Inventory Editor END

define('BOX_REPORTS_SUPERTRACKER', 'Supertracker');

// Admin Account

define('BOX_HEADING_MY_ACCOUNT', 'My Account');



// configuration box text in includes/boxes/administrator.php

define('BOX_HEADING_ADMINISTRATOR', 'Administrator');

define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');

define('BOX_ADMINISTRATOR_MEMBER', 'Members');

//Mett

define('BOX_ADMINISTRATOR_MEMBERS_EDIT', 'Members Access');

//end Mett

define('BOX_ADMINISTRATOR_BOXES', 'File Access');

//DELIVERY MODULE - START

/*#########Added By Skywebapps#################*/

// text in includes/boxes/manage_delivey_time.php

define('BOX_DEFAULT_DELIVERY_TIME', 'Defaut Time');

define('BOX_SPECIAL_DELIVERY_TIME', 'Special Time');

//DELIVERY MODULE - END

// images

define('IMAGE_FILE_PERMISSION', 'File Permission');

define('IMAGE_GROUPS', 'Groups List');

define('IMAGE_INSERT_FILE', 'Insert File');

define('IMAGE_MEMBERS', 'Members List');

define('IMAGE_NEW_GROUP', 'New Group');

define('IMAGE_NEW_MEMBER', 'New Member');

define('IMAGE_NEXT', 'Next');



// constants for use in tep_prev_next_display function

define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');



define('TEXT_DISPLAY_NUMBER_OF_DELIVERY_TIME', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Delivery Time)');

define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');

//Admin end

//MVS start

define('VENDOR_IMAGE_MAIN_CONFIGURATION', 'Set Vendor Constants');

define('VENDOR_IMAGE_MANAGE_MODULES', 'Vendor Module Manager');

define('IMAGE_MANAGE', 'Manage');

define('VENDOR_HEADING_TITLE', 'Vendor Module Manager');

define('BOX_HEADING_VENDOR_MODULES', 'Vendor Management');

define('BOX_VENDOR_SELECT', 'Vendor Select');

define('BOX_VENDOR_MODULES_SHIPPING', 'Vendor Shipping');

define('BOX_VENDOR_CONFIGURATION', 'Vendor Config');

define('BOX_CATALOG_VENDORS', 'Vendor Manager');

define('BOX_HEADING_VENDORS', 'Vendors and Drop Shippers');

define('BOX_VENDORS', 'Vendor Manager');

define('BOX_VENDORS_REPORTS_PROD', 'Product Reports');

define('TEXT_DISPLAY_NUMBER_OF_VENDORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>



vendors)');

define('TEXT_CACHE_VENDORS', 'Vendors Box');

define('BOX_VENDORS_ORDERS', 'Vendors Orders List');

define('BOX_MOVE_VENDOR_PRODS', 'Move Products between Vendors');

//MVS End

// look in your $PATH_LOCALE/locale directory for available locales..

// on RedHat6.0 I used 'en_US'

// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'

// this may not work under win32 environments..



// Google SiteMaps

define('BOX_GOOGLE_SITEMAP', 'Google SiteMaps');

//Fine

//Options as Images Mod

define('BOX_CATALOG_OPTIONS_IMAGES', 'Options as Images');



define('BOX_CATALOG_CATEGORIES_PRODUCTS_EXTRA_IMAGES','Extra Images'); //Added for Extra Images Contribution

define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');





setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('IMAGE_ICON_STATUS_GREEN_LIGHT_FEATURED', 'Set active ' .DAYS_UNTIL_FEATURED_PRODUCTS . ' day from today');

define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()

define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()

define('DATE_FORMAT', 'm/d/Y'); // this is used for date()

define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()

// BOF Separate Pricing per Customer

define('ENTRY_CUSTOMERS_GROUP_NAME', 'Customer Group:');

// EOF Separate Pricing per Customer

define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');





define('BOX_REPORTS_AD_RESULTS', 'Ad Results');



////

// Return date in raw format

// $date should be in format mm/dd/yyyy

// raw date is in format YYYYMMDD, or DDMMYYYY

function tep_date_raw($date, $reverse = false) {

  if ($reverse) {

    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);

  } else {

    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);

  }

}



// Global entries for the <html> tag

define('HTML_PARAMS','dir="ltr" lang="en"');



// charset for web pages and emails

define('CHARSET', 'iso-8859-1');



// page title

define('TITLE', 'CartStore');



// header text in includes/header.php

define('HEADER_TITLE_TOP', 'Administration');

define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');

define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');

define('HEADER_TITLE_ADMINISTRATION', 'Administration');



// text for gender

define('MALE', 'Male');

define('FEMALE', 'Female');



// text for date of birth example

define('DOB_FORMAT_STRING', 'mm/dd/yyyy');



// configuration box text in includes/boxes/configuration.php

define('BOX_HEADING_CONFIGURATION', 'Configuration');

define('BOX_CONFIGURATION_MYSTORE', 'My Store');

define('BOX_CONFIGURATION_LOGGING', 'Logging');

define('BOX_CONFIGURATION_CACHE', 'Cache');



// modules box text in includes/boxes/modules.php

define('BOX_HEADING_MODULES', 'Modules');

define('BOX_MODULES_PAYMENT', 'Payment');

define('BOX_MODULES_SHIPPING', 'Shipping');

define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

define('BOX_MODULES_SOCIAL_LOGIN', 'Social Login');

// categories box text in includes/boxes/catalog.php

define('BOX_HEADING_CATALOG', 'Catalog');

define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');

define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Create Products Attribute Bases');

define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');

define('BOX_CATALOG_REVIEWS', 'Reviews');

define('BOX_CATALOG_SPECIALS', 'Specials');

define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');



// customers box text in includes/boxes/customers.php

define('BOX_HEADING_CUSTOMERS', 'Customers');

define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');

define('BOX_CUSTOMERS_ORDERS', 'Orders');

// BOF Separate Pricing Per Customer

define('BOX_CUSTOMERS_GROUPS', 'Customers Groups');

// EOF Separate Pricing Per Customer

define('BOX_CUSTOMERS_POINTS', 'Customers Points');// Points/Rewards Module V2.00

define('BOX_CUSTOMERS_POINTS_PENDING', 'Pending Points');// Points/Rewards Module V2.00

define('BOX_CUSTOMERS_POINTS_REFERRAL', 'Referral Points');// Points/Rewards Module V2.00



// taxes box text in includes/boxes/taxes.php

define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');

define('BOX_TAXES_COUNTRIES', 'Countries');

define('BOX_TAXES_ZONES', 'Zones');

define('BOX_TAXES_GEO_ZONES', 'Tax Zones');

define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');

define('BOX_TAXES_TAX_RATES', 'Tax Rates');



// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');
define('BOX_REPORTS_STATS_LOW_STOCK_ATTRIB', 'Low Stock Report');
define('BOX_REPORTS_WA_TAXES_REPORT', 'Wa Taxes');


// tools text in includes/boxes/tools.php

define('BOX_HEADING_TOOLS', 'Tools');

define('BOX_TOOLS_BACKUP', 'Database Backup');

define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');

define('BOX_TOOLS_CACHE', 'Cache Control');

define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');

define('BOX_TOOLS_FILE_MANAGER', 'File Manager');

define('BOX_TOOLS_MAIL', 'Send Email');

define('BOX_TOOLS_SEO_ASSISTANT', 'SEO Assistant');

define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');

define('BOX_TOOLS_SERVER_INFO', 'Server Info');

define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');



// localizaion box text in includes/boxes/localization.php

define('BOX_HEADING_LOCALIZATION', 'Localization');

define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');

define('BOX_LOCALIZATION_LANGUAGES', 'Languages');

define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');



// javascript messages

define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');



define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');

define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');



define('JS_PRODUCTS_NAME', '* The new product needs a name\n');

define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');

define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');

define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');

define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');

define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');

define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');



define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');



define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');

define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');

define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');

define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');

define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');

define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');

define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');

define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');

define('JS_STATE', '* The \'State\' entry is must be selected.\n');

define('JS_STATE_SELECT', '-- Select Above --');

define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');

define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');

define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');

define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');



define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');



define('CATEGORY_PERSONAL', 'Personal');

define('CATEGORY_ADDRESS', 'Address');

define('CATEGORY_CONTACT', 'Contact');

define('CATEGORY_COMPANY', 'Company');

define('CATEGORY_OPTIONS', 'Options');



define('ENTRY_GENDER', 'Gender:');

define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');

define('ENTRY_FIRST_NAME', 'First Name:');

define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');

define('ENTRY_LAST_NAME', 'Last Name:');

define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');

define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');

define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');

define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');

define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');

define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');

define('ENTRY_COMPANY', 'Company name:');

define('ENTRY_COMPANY_ERROR', '');

define('ENTRY_CUSTOMERS_DBA', 'Customers DBA:');
define('ENTRY_QUICKBOOKS_ID', 'QuickBooks ID:');



// BOF Separate Pricing Per Customer

define('ENTRY_COMPANY_TAX_ID', 'Company\'s tax id number:');

define('ENTRY_COMPANY_TAX_ID_ERROR', '');

define('ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION', 'Switch off alert for authentication:');

define('ENTRY_CUSTOMERS_GROUP_RA_NO', 'Alert off');

define('ENTRY_CUSTOMERS_GROUP_RA_YES', 'Alert on');

define('ENTRY_CUSTOMERS_GROUP_RA_ERROR', '');

// EOF Separate Pricing Per Customer



define('ENTRY_STREET_ADDRESS', 'Street Address:');

define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');

define('ENTRY_SUBURB', 'Suburb:');

define('ENTRY_SUBURB_ERROR', '');

define('ENTRY_POST_CODE', 'Post Code:');

define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');

define('ENTRY_CITY', 'City:');

define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');

define('ENTRY_STATE', 'State:');

define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');

define('ENTRY_COUNTRY', 'Country:');

define('ENTRY_COUNTRY_ERROR', '');

define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');

define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');

define('ENTRY_FAX_NUMBER', 'Fax Number:');

define('ENTRY_FAX_NUMBER_ERROR', '');

define('ENTRY_NEWSLETTER', 'Newsletter:');

define('ENTRY_NEWSLETTER_YES', 'Subscribed');

define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');

define('ENTRY_NEWSLETTER_ERROR', '');



// images

define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');

define('IMAGE_BACK', 'Back');

define('IMAGE_BACKUP', 'Backup');

define('IMAGE_CANCEL', 'Cancel');

define('IMAGE_CHECK_DENSITY', 'Check Density');

define('IMAGE_CONFIRM', 'Confirm');

define('IMAGE_CONTINUE', 'Continue');

define('IMAGE_COPY', 'Copy');

define('IMAGE_COPY_TO', 'Copy To');

define('IMAGE_DETAILS', 'Details');

define('IMAGE_DELETE', 'Delete');

define('IMAGE_EDIT', 'Edit');

define('IMAGE_EMAIL', 'Email');

define('IMAGE_FILE_MANAGER', 'File Manager');

define('IMAGE_GET_PAGE_RANK', 'Get Page Rank');

define('IMAGE_ICON_STATUS_GREEN', 'Active');

define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');

define('IMAGE_ICON_STATUS_RED', 'Inactive');

define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');

define('IMAGE_ICON_INFO', 'Info');

define('IMAGE_INSERT', 'Insert');

define('IMAGE_LINK_POPULARITY', 'Link Popularity');

define('IMAGE_LOCK', 'Lock');

define('IMAGE_MODULE_INSTALL', 'Install Module');

define('IMAGE_MODULE_REMOVE', 'Remove Module');

define('IMAGE_MOVE', 'Move');

define('IMAGE_NEW_BANNER', 'New Banner');

define('IMAGE_NEW_CATEGORY', 'New Category');

define('IMAGE_NEW_COUNTRY', 'New Country');

define('IMAGE_NEW_CURRENCY', 'New Currency');

define('IMAGE_NEW_FILE', 'New File');

define('IMAGE_NEW_FOLDER', 'New Folder');

define('IMAGE_NEW_LANGUAGE', 'New Language');

define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');

define('IMAGE_NEW_PRODUCT', 'New Product');

define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');

define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');

define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');

define('IMAGE_NEW_ZONE', 'New Zone');

define('IMAGE_ORDERS', 'Orders');

define('IMAGE_ORDERS_INVOICE', 'Invoice');

define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');

define('IMAGE_PREVIEW', 'Preview');

define('IMAGE_RESTORE', 'Restore');

define('IMAGE_RESET', 'Reset');

define('IMAGE_SAVE', 'Save');

define('IMAGE_SEARCH', 'Search');

define('IMAGE_SELECT', 'Select');

define('IMAGE_SEND', 'Send');

define('IMAGE_SEND_EMAIL', 'Send Email');

define('IMAGE_UNLOCK', 'Unlock');

define('IMAGE_UPDATE', 'Update');

define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');

define('IMAGE_UPLOAD', 'Upload');



define('ICON_CROSS', 'False');

define('ICON_CURRENT_FOLDER', 'Current Folder');

define('ICON_DELETE', 'Delete');

define('ICON_ERROR', 'Error');

define('ICON_FILE', 'File');

define('ICON_FILE_DOWNLOAD', 'Download');

define('ICON_FOLDER', 'Folder');

define('ICON_LOCKED', 'Locked');

define('ICON_PREVIOUS_LEVEL', 'Previous Level');

define('ICON_PREVIEW', 'Preview');

define('ICON_STATISTICS', 'Statistics');

define('ICON_SUCCESS', 'Success');

define('ICON_TICK', 'True');

define('ICON_UNLOCKED', 'Unlocked');

define('ICON_WARNING', 'Warning');



// constants for use in tep_prev_next_display function

define('TEXT_RESULT_PAGE', 'Page %s of %d');

define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');

define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');

define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');

define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');

define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');

define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');

define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');

define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');

define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');

define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');



define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');

define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');



define('TEXT_DEFAULT', 'default');

define('TEXT_SET_DEFAULT', 'Set as default');

define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');



define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');



define('TEXT_CACHE_CATEGORIES', 'Categories Box');

define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');

define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');



define('TEXT_NONE', '--none--');

define('TEXT_TOP', 'Top');



define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');

define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');

define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');

define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');

define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');

define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');

define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');



// header_tags_controller text in includes/boxes/header_tags_controller.php

define('BOX_HEADING_HEADER_TAGS_CONTROLLER', 'Header Tags');

define('BOX_HEADER_TAGS_ADD_A_PAGE', 'Page Control');

define('BOX_HEADER_TAGS_ENGLISH', 'Text Control');

define('BOX_HEADER_TAGS_FILL_TAGS', 'Fill Tags');



//START STS 4.1

define('BOX_MODULES_STS', 'STS');

//END STS 4.1



// seo assistant start

define('HEADING_TITLE_SEARCH', 'Search');

define('TEXT_POSITION', 'Positions for each keyword');



define('HEADING_TITLE_RANK', 'Page Ranking');

define('TEXT_RANK', 'Returns given google page rank for URL');



define('HEADING_TITLE_LINKPOP', 'Link Popularity');

define('TEXT_LINKPOP', 'Find Link Popularity for a given URL or compare with a comepetitior.');



define('HEADING_TITLE_DENSITY', 'Keyword Density');

define('TEXT_DENSITY', 'Check the density of words on your page');



define('HEADING_TITLE_CHECK_LINKS', 'Link Checker');

define('TEXT_CHECK_LINKS', 'Check your pages for broken links');

//seo assistant end

require(DIR_WS_LANGUAGES . 'add_ccgvdc_english.php');  // ICW CREDIT CLASS Gift Voucher Addittion



//Family products: Begin Changed code

define('BOX_HEADING_FAMILIES', 'Families');

define('BOX_FAMILIES_MODIFY_FAMILIES', 'Manage Families');

define('BOX_FAMILIES_SELECT_DISPLAY', 'Select Display');

define('BOX_FAMILIES_ASSIGN_FAMILIES', 'Assign Families');

define('BOX_FAMILIES_VIEW_FAMILIES', 'View Families');



define('PULL_DOWN_DEFAULT', 'Please Select a Product');

define('PULL_DOWN_FAMILY_DEFAULT', 'Please Select a Family');

//Family: End Changed code



define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');

// Article Manager

define('BOX_HEADING_ARTICLES', 'General Pages');

define('BOX_TOPICS_ARTICLES', 'Topics/Articles');

define('BOX_ARTICLES_CONFIG', 'Configuration');

define('BOX_ARTICLES_AUTHORS', 'Authors');

define('BOX_ARTICLES_REVIEWS', 'Reviews');

define('BOX_ARTICLES_XSELL', 'Cross-Sell Articles');

define('IMAGE_NEW_TOPIC', 'New Topic');

define('IMAGE_NEW_ARTICLE', 'New Article');

define('TEXT_DISPLAY_NUMBER_OF_AUTHORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> authors)');

//BEGIN -- faqdesk

define('BOX_HEADING_FAQDESK', 'FAQDesk');

define('BOX_FAQDESK', 'FAQ Management');

define('BOX_FAQDESK_REVIEWS', 'Reviews Management');

define('FAQDESK_ARTICLES', 'FAQS');

define('FAQDESK_REVIEWS', 'Reviews');

//END -- faqdesk



//BEGIN -- newsdesk

define('BOX_HEADING_NEWSDESK', 'News & Blog');

define('BOX_NEWSDESK', 'Article Management');

define('BOX_NEWSDESK_REVIEWS', 'Reviews Management');



define('NEWSDESK_ARTICLES', 'Articles');

define('NEWSDESK_REVIEWS', 'Reviews');

//END -- newsdesk



//BEGIN -- faqdesk

define('BOX_HEADING_NEWSDESK', 'FAQDesk');

define('BOX_NEWSDESK', 'FAQ Management');

define('BOX_NEWSDESK_REVIEWS', 'Reviews Management');



define('NEWSDESK_ARTICLES', 'FAQS');

//END -- newsdesk

define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');



// VJ Links Manager v1.00 begin

// links manager box text in includes/boxes/links.php

define('BOX_HEADING_LINKS', 'Links Manager');

define('BOX_LINKS_LINKS', 'Links');

define('BOX_LINKS_LINK_CATEGORIES', 'Link Categories');

define('BOX_LINKS_LINKS_CONTACT', 'Links Contact');

define('BOX_LINKS_LINKS_FEATURED', 'Links Featured');

define('BOX_LINKS_LINKS_STATUS', 'Links Status');

// VJ Links Manager v1.00 end





// pull down default text

define('PULL_DOWN_DEFAULT', 'Please Select');

define('TYPE_BELOW', 'Type Below');



define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');



define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');

define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');

define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');

define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/day/year).\n');

define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');

define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');

define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');

define('JS_CITY', '* The \'Suburb\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');

define('JS_STATE', '* The \'State\' entry must be selected.\n');

define('JS_STATE_SELECT', '-- Select Above --');

define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.\n');

define('JS_COUNTRY', '* The \'Country\' entry must be selected.\n');

define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');

define('JS_PASSWORD', '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');



define('CATEGORY_COMPANY', 'Company Details');

define('CATEGORY_PERSONAL', 'Personal Details');

define('CATEGORY_ADDRESS', 'Address');

define('CATEGORY_CONTACT', 'Contact Information');

define('CATEGORY_OPTIONS', 'Options');

define('CATEGORY_PASSWORD', 'Password');

define('CATEGORY_CORRECT', 'If this is the right customer, press the Confirm button below.');

define('ENTRY_CUSTOMERS_ID', 'ID:');

define('ENTRY_CUSTOMERS_ID_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_COMPANY', 'Company Name:');

define('ENTRY_COMPANY_ERROR', '');

define('ENTRY_COMPANY_TEXT', '');

define('ENTRY_GENDER', 'Gender:');

define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_FIRST_NAME', 'First Name:');

define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0F00">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_LAST_NAME', 'Last Name:');

define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');

define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(eg. 05/21/1970)</font></small>');

define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(eg. 05/21/1970) <font color="#FF0F00">required</font></small>');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');

define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">Your email address doesn\'t appear to be valid!</font></small>');

define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">email address already exists!</font></small>');

define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_STREET_ADDRESS', 'Street Address:');

define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_SUBURB', 'Suburb:');

define('ENTRY_SUBURB_ERROR', '');

define('ENTRY_SUBURB_TEXT', '');

define('ENTRY_POST_CODE', 'Post Code:');

define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_CITY', 'Suburb:');

define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_STATE', 'State/Province:');

define('ENTRY_STATE_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');

define('ENTRY_STATE_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_COUNTRY', 'Country:');

define('ENTRY_COUNTRY_ERROR', '');

define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');

define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_FAX_NUMBER', 'Fax Number:');

define('ENTRY_FAX_NUMBER_ERROR', '');

define('ENTRY_FAX_NUMBER_TEXT', '');

define('ENTRY_NEWSLETTER', 'Newsletter:');

define('ENTRY_NEWSLETTER_TEXT', '');

define('ENTRY_NEWSLETTER_YES', 'Subscribed');

define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');

define('ENTRY_NEWSLETTER_ERROR', '');

define('ENTRY_PASSWORD', 'Password:');

define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');

define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars</font></small>');

define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#FF0F00">required</font></small>');

define('PASSWORD_HIDDEN', '--HIDDEN--');



// manual order box text in includes/boxes/manual_order.php



define('BOX_HEADING_MANUAL_ORDER', 'Manual Orders');

define('BOX_MANUAL_ORDER_CREATE_ACCOUNT', 'Create Account');

define('BOX_MANUAL_ORDER_CREATE_ORDER', 'Create Order');



define('ENTRY_CUSTOMERS_PAYMENT_SET', 'Set payment modules for the customer group');

define('ENTRY_CUSTOMERS_PAYMENT_DEFAULT', 'Use settings from Configuration');

define('ENTRY_CUSTOMERS_SHIPPING_SET', 'Set shipping modules for the customer group');

define('ENTRY_CUSTOMERS_SHIPPING_DEFAULT', 'Use settings from Configuration');



define('TABLE_HEADING_CUSTOMERS_GROUPS', 'Customer Group');

define('TABLE_HEADING_REQUEST_AUTHENTICATION', 'Request Authentication');



define('ENTRY_CUSTOMERS_PAYMENT_SET_EXPLAIN', 'If you choose Set payment modules for the customer group but do not check any of the boxes, default settings will still be used.');



define('ENTRY_CUSTOMERS_SHIPPING_SET_EXPLAIN', 'If you choose Set shipping modules for the customer group but do not check any of the boxes, default settings will still be used.');

define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');

// KIKOLEPPARD New attribute manager start

define('BOX_CATALOG_CATEGORIES_ATTRIBUTE_MANAGER', 'Insert Attributes');

// KIKOLEPPARD New attribute manager end



   // RSS NEWS

   define('BOX_HEADING_RSS_NEWS', 'RSS News');

   define('BOX_RSS_NEWS_CREATE', 'RSS Create');

   // RSS NEWS



define('BOX_CATALOG_QBI', 'Quickbooks Import QBI');

// RMA

define('BOX_RETURNS_HEADING', 'Customer Returns');

define('BOX_RETURNS_REASONS', 'Return Reasons');

define('BOX_RETURNS_MAIN', 'Returned Products');

define('BOX_RETURNS_TEXT', 'Return Text Edit');

define('BOX_RETURNS_STATUS', 'Returns Status');

define('BOX_HEADING_REFUNDS', 'Refund Methods');

define('TEXT_DISPLAY_NUMBER_OF_TICKET_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tickets)');

  define('BOX_REPORTS_RECOVER_CART_SALES', 'Recovered Sales Results');

  define('BOX_TOOLS_RECOVER_CART', 'Recover Cart Sales');

  define('AM_AJAX_QT_PRO ', 'Attribute Quantity Matrix');

//Feeder Systems

define('BOX_FEEDERS_GOOGLE', 'Google Base');

define('TEXT_FEEDERS_GOOGLE', 'Create and Upload a GoogleBase datafeed');

   define('BOX_TOOLS_EVENTS_MANAGER', 'Events Manager');



define('IMAGE_NEW_EVENT', 'New Event');



define('ENTRY_STREET_ADDRESS_2', 'Street Address Line 2:');

define('ENTRY_STREET_ADDRESS_TEXT_2', '');

define('BOX_CONSTANT_CONTACT', 'Constant Contact');
	define('TEXT_DISPLAY_NUMBER_OF_QUERIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> queries)');
	define('BOX_TOOLS_MYSQL_PERFORMANCE', 'MySQL Performance');
	define('TEXT_DELETE','Delete all records?');
	define('IMAGE_BUTTON_DELETE','Delete all records');
	define('IMAGE_BUTTON_CANCEL','Do not delete records');
