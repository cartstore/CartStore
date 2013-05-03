<?php

define('HEADING_TITLE', 'FAQDesk ... Catagory and FAQ Management');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_FAQDESK', 'Question');
define('TABLE_HEADING_DATE', 'Date');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');

define('IMAGE_NEW_STORY', 'New FAQ');

define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_FAQDESK', 'FAQS:');
define('TEXT_NEW_FAQDESK', 'FAQS in the catagory &quot;%s&quot;');

define('TABLE_HEADING_LATEST_NEWS_HEADLINE', 'Headline');
define('TEXT_NEWS_ITEMS', 'FAQS:');
define('TEXT_INFO_HEADING_DELETE_ITEM', 'Delete Item');
define('TEXT_DELETE_ITEM_INTRO', 'Are you sure you want to permanently delete this item?');

define('TEXT_LATEST_NEWS_HEADLINE', 'Question:');
define('TEXT_FAQDESK_ANSWER_LONG', 'Long Answer:');

define('IMAGE_NEW_NEWS_ITEM', 'New FAQ');

define('TEXT_FAQDESK_STATUS', 'FAQ Status:');
define('TEXT_FAQDESK_AVAILABLE', 'In Print');
define('TEXT_FAQDESK_NOT_AVAILABLE', 'Out of Print');

define('TEXT_FAQDESK_URL', 'Extra URL:');
define('TEXT_FAQDESK_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_FAQDESK_URL_NAME', 'Name of the URL Link:');

define('TEXT_FAQDESK_ANSWER_SHORT', 'Short Answer:');
define('TEXT_FAQDESK_ANSWER_LONG', 'Long Answer:');
define('TEXT_FAQDESK_QUESTION', 'Question:');

define('TEXT_FAQDESK_DATE_AVAILABLE', 'Start Date:');
define('TEXT_FAQDESK_DATE_ADDED', 'This FAQ was submitted on:');

define('TEXT_FAQDESK_ADDED_LINK_HEADER', "This is the link you've added:");
define('TEXT_FAQDESK_ADDED_LINK_HEADER_NAME', "This is the link name you've added:");
define('TEXT_FAQDESK_ADDED_LINK', '<a href="http://%s" target="blank"><u>link name</u></a>');

define('TEXT_FAQDESK_AVERAGE_RATING', 'Average Rating:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_story', 'Please insert a new category or FAQ in<br>&nbsp;<br><b>%s</b>');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this FAQ to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'Delete FAQ');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'Move FAQ');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this FAQ?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_FAQDESK', '<b>WARNING:</b> There are %s FAQS still linked to this category!');

define('TEXT_MOVE_FAQDESK_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category');
define('TEXT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_SORT_ORDER', 'Sort Order:');

define('EMPTY_CATEGORY', 'Empty Category');

define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_AS_LINK', 'Link FAQ');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate FAQ');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link FAQS in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);

define('TEXT_FAQDESK_START_DATE', 'Start Date:');
define('TEXT_DATE_FORMAT', 'Date formated as:');

define('TEXT_SHOW_STATUS', 'Status');

define('TEXT_DELETE_IMAGE', 'Delete Image(s) ?');
define('TEXT_DELETE_IMAGE_INTRO', 'BEWARE:: Deleting this/these image(s) will completely remove it/them. If you use this/these image(s) elsewhere -- I warned you !!');

define('TEXT_FAQDESK_STICKY', 'Sticky Status');
define('TEXT_FAQDESK_STICKY_ON', 'ON');
define('TEXT_FAQDESK_STICKY_OFF', 'OFF');
define('TABLE_HEADING_STICKY', 'Sticky');

define('TEXT_FAQDESK_IMAGE', 'FAQ Image(s):');

define('TEXT_FAQDESK_IMAGE_ONE', 'Image one:');
define('TEXT_FAQDESK_IMAGE_TWO', 'Image two:');
define('TEXT_FAQDESK_IMAGE_THREE', 'Image three:');

define('TEXT_FAQDESK_IMAGE_SUBTITLE', 'Enter image title for image one:');
define('TEXT_FAQDESK_IMAGE_SUBTITLE_TWO', 'Enter image title for image two:');
define('TEXT_FAQDESK_IMAGE_SUBTITLE_THREE', 'Enter image title for image three:');

define('TEXT_FAQDESK_IMAGE_PREVIEW_ONE', 'FAQ Image number 1:');
define('TEXT_FAQDESK_IMAGE_PREVIEW_TWO', 'FAQ Image number 2:');
define('TEXT_FAQDESK_IMAGE_PREVIEW_THREE', 'FAQ Image number 3:');

/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:			FAQDesk
	version:			1.01.0
	date:       			06-05-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241

*/
?>
