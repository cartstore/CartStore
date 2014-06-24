<?php
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  English translation to AJAX-AttributeManager-V2.7
  
  by Shimon Doodkin
  http://help.me.pro.googlepages.com
  helpmepro1@gmail.com
*/

//attributeManagerPrompts.inc.php

define('AM_AJAX_YES', 'Yes');
define('AM_AJAX_NO', 'No');
define('AM_AJAX_UPDATE', 'Update');
define('AM_AJAX_CANCEL', 'Cancel');
define('AM_AJAX_OK', 'OK');

define('AM_AJAX_SORT', 'Sort:');
define('AM_AJAX_TRACK_STOCK', 'Track Stock?');
define('AM_AJAX_TRACK_STOCK_IMGALT', 'Track this attribute stock ?');
define('AM_AJAX_QT_PRO', 'Attribute Quantity Matrix');
define('AM_AJAX_ENTER_NEW_OPTION_NAME', 'Please enter a new Option Name');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', 'Please enter a new Option Name');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', 'Please enter a new Option Value Name to add to %s');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', 'Are you sure you want to remove %s and all the values below it from this product?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', 'Are you sure you want to remove %s from this product?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', 'Are you sure you want to remove this stock combination from this product?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', 'Are you sure you want to load the %s Template? <br />This will overwrite this products current options and cannot be undone.');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', 'Please enter a new name for the new Template. Or...');
define('AM_AJAX_NEW_NAME', 'New Name:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... Choose an existing one to overwrite');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', 'Existing:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', 'Please enter the new name for the %s Template');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', 'Are you sure you want to delete the %s Template?<br>This cannot be undone!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', 'Adds the selected attribute on the left to the %s option');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', 'Adds a new value to the %s option');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', 'Removes the option %1$s and the %2$d option value(s) below it  from this product');
define('AM_AJAX_CHANGES', 'Changes'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', 'Loads the selected template');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', 'Saves the current attributes as a new template');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', 'Renames the selected template');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', 'Deletes the selected template');
define('AM_AJAX_NAME', 'Name');
define('AM_AJAX_ACTION', 'Action');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', 'Removes %1$s from %2$s, from this product');
define('AM_AJAX_MOVES_VALUE_UP', 'Moves option value up');
define('AM_AJAX_MOVES_VALUE_DOWN', 'Moves option value down');
define('AM_AJAX_ADDS_NEW_OPTION', 'Adds a new option to the list');
define('AM_AJAX_OPTION', 'Option:');
define('AM_AJAX_VALUE', 'Value:');
define('AM_AJAX_PREFIX', 'Prefix:');
define('AM_AJAX_PRICE', 'Price:');
define('AM_AJAX_WEIGHT_PREFIX', 'Wgt.Prefix:');
define('AM_AJAX_WEIGHT', 'Weight:');
define('AM_AJAX_SORT', 'Sort:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', 'Adds a new option value to the list');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', 'Adds the attribute to the current product');
define('AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT', 'Deletes attribute or attribute combination from the current product');
define('AM_AJAX_QUANTITY', 'Quantity:');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', 'Removes this attribute combination and stock from this product');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', 'Update or Insert the attribute combination with the given quantity');
define('AM_AJAX_UPDATE_PRODUCT_QUANTITY', 'Set the given quantity to the current product');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- Templates --');

//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
define('AM_AJAX_FILENAME', 'File');
define('AM_AJAX_FILE_DAYS', 'Days');
define('AM_AJAX_FILE_COUNT', 'Max. downloads');
define('AM_AJAX_DOWLNOAD_EDIT', 'Edit download option');
define('AM_AJAX_DOWLNOAD_ADD_NEW', 'Add download option');
define('AM_AJAX_DOWLNOAD_DELETE', 'Delete download option');
define('AM_AJAX_HEADER_DOWLNOAD_ADD_NEW', 'Add download option for \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_EDIT', 'Edit download option for \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_DELETE', 'Delete download option from \"%s\"');
define('AM_AJAX_FIRST_SAVE', 'Save Product before adding options');

//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

define('AM_AJAX_OPTION_NEW_PANEL','New option:');
?>
