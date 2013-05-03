<?php

/*

  $Id: categories.php,v 1.26 2003/07/11 14:40:28 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

*/

//MVS Start

define('TEXT_PRODUCTS_VENDORS', 'Products Vendors');

define('TEXT_VENDORS_PRODUCT_PRICE_BASE', 'Vendors Price(Base):');

define('TEXT_PRODUCTS_VENDORS', 'Products Vendors');

define('TEXT_VENDORS_PROD_COMMENTS', 'Vendors Comments or <br>Special Instructions');

define('TEXT_VENDORS_PROD_ID', 'Vendors Item Number');

define('TEXT_VENDORS_PRODUCT_PRICE_INFO', 'Vendors Price:');

//MVS End



 define('TEXT_PRODUCTS_FEATURED', 'Featured on Hompage::');

  define('TEXT_PRODUCTS_SHORT', 'Products Short Description:');

define('TABLE_HEADING_FEATURED', 'Featured');

define('TEXT_PRODUCT_YES', 'Enabled');

define('TEXT_PRODUCT_NO', 'Disabled');

define('TEXT_BUY_NOW', 'Buy now');

define('TEXT_MORE_INFO', 'More information');

define('TEXT_PRODUCT_METTA_INFO', '<b>Meta Tag Information</b>');

define('TEXT_PRODUCTS_PAGE_TITLE', 'Product Title Tag:');

define('TEXT_PRODUCTS_HEADER_DESCRIPTION', 'Product Description Tag:');

define('TEXT_PRODUCTS_KEYWORDS', 'Product Keywords Tag:');





define('HEADING_TITLE', 'Categories and Products');

define('HEADING_TITLE_SEARCH', 'Search:');

define('HEADING_TITLE_GOTO', 'Go To:');



define('TABLE_HEADING_ID', 'ID');

define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories and Products <span class=note>Click on folder icon to enter that category</span>');

define('TABLE_HEADING_ACTION', 'Featured');

define('TABLE_HEADING_STATUS', 'Status');



define('TEXT_NEW_PRODUCT', 'Edit Product in &quot;%s&quot;');

define('TEXT_CATEGORIES', 'Categories:');

define('TEXT_SUBCATEGORIES', 'Subcategories:');

define('TEXT_PRODUCTS', 'Products:');

define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');

define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');

define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');

define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');

define('TEXT_DATE_ADDED', 'Date Added:');

define('TEXT_DATE_AVAILABLE', 'Date Available:');

define('TEXT_LAST_MODIFIED', 'Last Modified:');

define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in this level.');

define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');

define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');

define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');



define('TEXT_EDIT_INTRO', 'Please make any necessary changes');

define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');

define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');

define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');

define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');



define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this product to');

define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories:');



define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');

define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');

define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');

define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');

define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete Product');

define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');

define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');



define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');

define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?');



define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');

define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');



define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');

define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');

define('TEXT_MOVE', 'Move <b>%s</b> to:');



define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category');

define('TEXT_CATEGORIES_NAME', 'Category Name:');

define('TEXT_CATEGORIES_IMAGE', 'Category Image:');

define('TEXT_SORT_ORDER', 'Sort Order:');



define('TEXT_PRODUCTS_STATUS', 'Products Status:');

define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');

define('TEXT_PRODUCT_AVAILABLE', 'In Stock');

define('TEXT_PRODUCT_NOT_AVAILABLE', 'Out of Stock');

define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');

define('TEXT_PRODUCTS_NAME', 'Products Name:');

define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');

define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');

define('TEXT_PRODUCTS_MODEL', 'Products Model:');

define('TEXT_PRODUCTS_IMAGE', 'Products Image:');

define('TEXT_PRODUCTS_URL', 'Products URL:');

define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');

define('TEXT_PRODUCTS_PRICE_NET', 'Products Price (Net):');

define('TEXT_PRODUCTS_PRICE_GROSS', 'Products Price (Gross):');

define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');



define('EMPTY_CATEGORY', 'Empty Category');



define('TEXT_HOW_TO_COPY', 'Copy Method:');

define('TEXT_COPY_AS_LINK', 'Link product');

define('TEXT_COPY_AS_DUPLICATE', 'Duplicate product');



define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');

define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);

define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);

define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Error: Category cannot be moved into child category.');

// Price Break 1.11.3

define('TEXT_PRODUCTS_PRICE', 'Products Price:');

define('TEXT_PRODUCTS_QTY_BLOCKS', 'Quantity Blocks:');

define('TEXT_PRODUCTS_QTY_BLOCKS_INFO', '(can only order in blocks of X quantity)');

define('TEXT_PRODUCTS_PRICE1', 'Price break level 1:');

define('TEXT_PRODUCTS_PRICE1_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE2', 'Price break level 2:');

define('TEXT_PRODUCTS_PRICE2_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE3', 'Price break level 3:');

define('TEXT_PRODUCTS_PRICE3_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE4', 'Price break level 4:');

define('TEXT_PRODUCTS_PRICE4_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE5', 'Price break level 5:');

define('TEXT_PRODUCTS_PRICE5_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE6', 'Price break level 6:');

define('TEXT_PRODUCTS_PRICE6_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE7', 'Price break level 7:');

define('TEXT_PRODUCTS_PRICE7_QTY', '  Qty:');

define('TEXT_PRODUCTS_PRICE8', 'Price break level 8:');

define('TEXT_PRODUCTS_PRICE8_QTY', '  Qty:');

  define('TEXT_PRODUCTS_SEO_URL', 'Products SEO URL:');

  define('TEXT_EDIT_CATEGORIES_SEO_URL', 'Category SEO URL:');

  define('TEXT_CATEGORIES_SEO_URL', 'Category SEO URL:');



 define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Category Heading Title:');

 define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Category Description:');

 define('TEXT_TAB_EXTRAIMAGE', 'Images');
   define('TEXT_PRODUCT_IS_SPECIAL', 'Yes');
   define('TEXT_PRODUCTS_SPECIAL', 'Product a Special Order Item?:');
    define('TEXT_PRODUCT_NOT_SPECIAL', 'No');


			
//bof year make model
define('TEXT_PRODUCTS_CAR_MAKE', 'Vehicle Make');
define('TEXT_PRODUCTS_CAR_MODEL', 'Vehicle Model');
define('TEXT_PRODUCTS_CAR_YEARS', 'Vehicle Years Range');
define('TEXT_PRODUCTS_YMM_NEW', 'Add new:');
define('TEXT_PRODUCTS_YMM_DELETE', 'Delete');
//eof year make model

?>