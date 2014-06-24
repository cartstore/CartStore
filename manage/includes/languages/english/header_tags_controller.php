<?php
/*
  $Id: header_tags.php,v 1.6 2005/04/10 14:07:36 hpdl Exp $
  Created by Jack York from http://www.CartStore.com
  
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
define('HEADING_TITLE_CONTROLLER', 'Header Tags Controller');
define('HEADING_TITLE_ENGLISH', 'Header Tags - English');
define('HEADING_TITLE_FILL_TAGS', 'Header Tags - Fill Tags');
define('TEXT_INFORMATION_ADD_PAGE', '<b>Add a New Page</b> - This option adds the code for a page into the files mentioned 
above. Note that it does not add an actual page. To add a page, enter the name of the file, with or without the .php extension..');
define('TEXT_INFORMATION_DELETE_PAGE', '<b>Delete a New Page</b> - This option will remove the code for a page from the
above files.'); 
define('TEXT_INFORMATION_CHECK_PAGES', '<b>Check Missing Pages</b> - This option allows you to check which files in your
shop do not have entries in the above files. Note that not all pages should have entries. For example,
any page that will use SSL like Login or Create Account. To view the pages, click Update and then select the drop down list.'); 

define('TEXT_PAGE_TAGS', 'In order for Header Tags to display information on a page, an entry for that
page must be made into the includes/header_tags.php and includes/languages/english/header_tags.php files
(where english would be the language you are using). The options on this page will allow you to add, delete
and check the code in those files.');
define('TEXT_ENGLISH_TAGS', 'The main purpose of Header Tags is to give each of the pages in your shop a 
unique title and meta tags for each page. The default settings will not do your shop any good and need to 
be changed on this page. Change them to use the main keyword you have chosen to use for your shop.
The individual sections are named after the page they belong to. So, to change the 
title of your home page, edit the title in the index section. Note that is the title of a section appears in
<font color="red">red</font>, it means that file does not have the required Header Tags code installed and,
as a result, will not display the title or meta tags defined here.');
define('TEXT_FILL_TAGS', 'This option allows you to fill in the meta tags added by
Header Tags. Select the appropriate setting for both the categories and products tags
and then click Update. If you select the Fill Only Empty Tags, then tags already
filled in will not be overwritten. If the Fill products meta description with Products Description option is
chosen, then the meta description tag will be filled with the products description. If a number is entered into the 
length box, the description will be truncated to that length.');

// header_tags_controller.php & header_tags_english.php
define('HEADING_TITLE_CONTROLLER_EXPLAIN', '(Explain)');
define('HEADING_TITLE_CONTROLLER_TITLE', 'Title:');
define('HEADING_TITLE_CONTROLLER_DESCRIPTION', 'Description:');
define('HEADING_TITLE_CONTROLLER_KEYWORDS', 'Keyword(s):');
define('HEADING_TITLE_CONTROLLER_PAGENAME', 'Page Name:');
define('HEADING_TITLE_CONTROLLER_PAGENAME_ERROR', 'Page name is already entered -> ');
define('HEADING_TITLE_CONTROLLER_PAGENAME_INVALID_ERROR', 'Page name is invalid -> ');
define('HEADING_TITLE_CONTROLLER_NO_DELETE_ERROR', 'Deleting %s is not allowed');

// header_tags_english.php
define('HEADING_TITLE_CONTROLLER_DEFAULT_TITLE', 'Default Title:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION', 'Default Description:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS', 'Default Keyword(s):');
// header_tags_fill_tags.php
define('HEADING_TITLE_CONTROLLER_CATEGORIES', 'CATEGORIES');
define('HEADING_TITLE_CONTROLLER_MANUFACTURERS', 'MANUFACTURERS');
define('HEADING_TITLE_CONTROLLER_PRODUCTS', 'PRODUCTS');
define('HEADING_TITLE_CONTROLLER_SKIPALL', 'Skip all tags');
define('HEADING_TITLE_CONTROLLER_FILLONLY', 'Fill only empty tags');
define('HEADING_TITLE_CONTROLLER_FILLALL', 'Fill all tags');
define('HEADING_TITLE_CONTROLLER_CLEARALL', 'Clear all tags');
?>
