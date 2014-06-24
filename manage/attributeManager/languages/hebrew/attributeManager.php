<?
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Hebrew translation to AJAX-AttributeManager-V2.7
  
  by Shimon Doodkin
  http://help.me.pro.googlepages.com
  helpmepro1@gmail.com
*/

//attributeManagerPrompts.inc.php

define('AM_AJAX_YES', 'כן');
define('AM_AJAX_NO', 'לא');
define('AM_AJAX_UPDATE', 'עדכן');
define('AM_AJAX_CANCEL', 'ביטול');
define('AM_AJAX_OK', 'אישור');

define('AM_AJAX_SORT', 'סידור:');
define('AM_AJAX_TRACK_STOCK', 'מעקב מלאי?');
define('AM_AJAX_TRACK_STOCK_IMGALT', 'לעשות מעקב מלאי למוצר הזה ?');


define('AM_AJAX_ENTER_NEW_OPTION_NAME', 'נא להקליד שם חדש לאפשרות');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', 'נא להקליד שם חדש לערך של האפשרות');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', 'נא להקליד שם חדש של ערך אפשרות כדי להוסיף ל - %s');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', 'האם הינך בטוח שברצונך למחוק את האפשרות - %s ואת כל הערכים שהיא מכילה ?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', 'האם הינך בטוח שברצונך למחוק מהמוצר הזה את האפשרות - %s?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', 'האם הינך בטוח שברצונך למחוק מהמוצר הזה את שילוב המלאי הנבחר?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', 'האם ברצונך לטעון את התבנית - %s ? <br />פעולה זו תחליף את האפשרויות הנוכחיות של המוצר הזה ופעולה זו לא ניתנת לביטול.');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', 'נא להקליד שם חדש עבור התבנית. או...');
define('AM_AJAX_NEW_NAME', 'שם חדש:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... בחר תבנית קיימת כדי להחליף אותה');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', 'תבניות קיימות:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', 'נא להקליד שם חדש עבור התבנית - %s');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', 'האם הינך בטוח שברצונך למחוק את התבנית - %s?<br>פעולה זו אינה ניתנת לביטול!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', 'מוסיף את המאפיין הנבחר משמאל לאפשרות - %s ');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', 'מוסיף ערך חדש לאפשרות - %s');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', 'מוחק מהמוצר הזה, את האפשרות - %1$s ואת כל %2$d הערכים תחתיה');
define('AM_AJAX_CHANGES', 'משנה שפת צפייה'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', 'טוען את התבנית הנבחרת');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', 'שומר את המאפיינים הנוכחיים כתבנית חדשה');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', 'משנה שם לתבנית הנבחרת');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', 'מוחק את התבנית הנבחרת');
define('AM_AJAX_NAME', 'שם');
define('AM_AJAX_ACTION', 'פעולה');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', 'מוחק מהמוצר הזה, את - %1$s מתוך - %2$s');
define('AM_AJAX_MOVES_VALUE_UP', 'מזיז את האפשרות למעלה');
define('AM_AJAX_MOVES_VALUE_DOWN', 'מזיז את האפשרות למטה');
define('AM_AJAX_ADDS_NEW_OPTION', 'מוסיף אפשרות חדשה לרשימה');
define('AM_AJAX_OPTION', 'אפשרות:');
define('AM_AJAX_VALUE', 'ערך:');
define('AM_AJAX_PREFIX', 'מקדם:');
define('AM_AJAX_PRICE', 'מחיר:');
define('AM_AJAX_SORT', 'סידור:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', 'מוסיף ערך חדש לאפשרות');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', 'מוסיף את המאפיין למוצר הנוכחי');
define('AM_AJAX_QUANTITY', 'כמות');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', 'מוחק קומבינציה זאת של אפשרויות מהמוצר ומהמלאי');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', 'מעדכן במלאי את הקומבינציה של האפשרויות בכמות הנבחרת');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- תבניות --');
?>