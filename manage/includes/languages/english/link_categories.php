<?php
/*
  $Id: link_categories.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Link Categories');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');
define('HEADING_TITLE_CATEGORIES_SORTBY', 'Sort Categories by');
define('HEADING_TITLE_CATEGORIES_SHOWBY', 'Show Categories by');
define('TABLE_HEADING_LINK_CATEGORIES', 'Categories');
define('TABLE_HEADING_LINK_CATEGORIES_COUNT', 'Sub Categories');
define('TABLE_HEADING_LINK_CATEGORY_REQUIRED', 'At least one category must exist before links can be added.');

define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_LINK_CATEGORIES', 'Categories:');
define('TEXT_SUBLINK_CATEGORIES', 'Subcategories:');
define('TEXT_SUBLINK_CATEGORIES_FULL_PATH', 'Full Path:');
define('TEXT_SUBLINK_LINKS', 'Links: (within all sub-categories)'); 
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Link ID:');
define('TEXT_EDIT_LINK_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_LINK_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_LINK_CATEGORIES_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_HEADING_NEW_LINK_CATEGORY', 'New Link Category');
define('TEXT_INFO_HEADING_EDIT_LINK_CATEGORY', 'Edit Link Category');
define('TEXT_INFO_HEADING_DELETE_LINK_CATEGORY', 'Delete Link Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');

define('TEXT_INFO_LINK_CATEGORY_COUNT', 'Links:');
define('TEXT_INFO_LINK_CATEGORY_STATUS', 'Status:');
define('TEXT_INFO_LINK_CATEGORY_DESCRIPTION', 'Description:');
define('TEXT_INFO_LINK_CATEGORY_SORT_ORDER', 'Sort Order:');
define('TEXT_DATE_LINK_CATEGORY_CREATED', 'Created on:');
define('TEXT_DATE_LINK_CATEGORY_LAST_MODIFIED', 'Last Modified:');

define('EMPTY_CATEGORY', 'Empty Category');
define('TEXT_NO_CHILD_LINK_CATEGORIES', 'No subcategories exist at this level');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_NEW_LINK_CATEGORIES_INTRO', 'Please fill out the following information for the new link category');
define('TEXT_EDIT_LINK_CATEGORIES_INTRO', 'Please make any necessary changes');
define('TEXT_DELETE_LINK_CATEGORIES_INTRO', 'Are you sure you want to delete this link category?');

define('TEXT_LINK_CATEGORIES_NAME', 'Category Name:');
define('TEXT_LINK_CATEGORIES_DESCRIPTION', 'Category Description:');
define('TEXT_LINK_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_LINK_CATEGORIES_SORT_ORDER', 'Sort Order:');
define('TEXT_LINK_CATEGORIES_STATUS', 'Status:');
define('TEXT_LINK_CATEGORIES_STATUS_ENABLE', 'Enable');
define('TEXT_LINK_CATEGORIES_STATUS_DISABLE', 'Disable');

define('TEXT_MOVE_LINKS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_LINK_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('STATUS_PENDING',  '1');
define('STATUS_APPROVED', '2');
define('STATUS_DISABLED', '3');
define('STATUS_CATEGORIES_ENABLE_FLAG',  '1');
define('STATUS_CATEGORIES_DISABLE_FLAG', '0');

define('TEXT_DELETE_WARNING_LINKS', '<b>WARNING:</b> There are %s links still linked to this category!');
define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');

define('TEXT_DISPLAY_NUMBER_OF_LINK_CATEGORIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> link categories)');

define('ERROR_LINK_CATALOG_DOES_NOT_EXIST', 'At least one category must exist before links may be added.');
?>
