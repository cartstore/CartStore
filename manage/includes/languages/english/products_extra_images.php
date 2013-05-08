<?php
/*
  $Id: products_extra_images.php,v 1.0 2003/06/11 Mikel Williams

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

	Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Extra Product Images');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS_IMAGE', 'Image Path (from OSC images folder)');
define('TABLE_HEADING_PRODUCTS_ID', 'Products ID');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_PAGING_FORMAT', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> extra images)');
define('TEXT_HEADING_EDIT_EXTRA_IMAGE', 'Edit Extra Product Image');
define('TEXT_HEADING_NEW_EXTRA_IMAGE', 'New Extra Product Image');
define('TEXT_NEW_INTRO', 'Please fill out the following information for the new extra product image');
define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_PRODUCTS', 'Number of products:');

/* Added for small improvements in upload UI */
define('TEXT_PRODUCTS_NAME', 'Product Name:');
define('TEXT_PRODUCTS_IMAGE', 'Product Image:');

/* Added for fix and allows for setting customized paths to image on server*/
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_SPECIAL_IMAGE_PATH', 'If you are uploading a file but want to set a <BR>customized path, enter the path of subfolder(s) <BR><B>with</B> a forward slash at the end (subfolder(s) <BR>should exist within the <b>"images/"</b> folder). <BR>E.G. subfolderA/subsubfolderB/');
define('UPDATE_EXTRA_IMAGE_OPTION', 'OR if the file has already been uploaded <BR>(leave the field next to the <B>"Browse"</B> button blank), <BR>state path to image file from the <b>"images/"</b> folder. <BR>E.G. subfolderA/subsubfolderB/image.jpg');
/* Added for fix and allows for setting customized paths to image on server*/
?>
