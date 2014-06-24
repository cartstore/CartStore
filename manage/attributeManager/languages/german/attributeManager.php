<?
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

define('AM_AJAX_YES', 'Ja');
define('AM_AJAX_NO', 'Nein');
define('AM_AJAX_UPDATE', 'Aktualisieren');
define('AM_AJAX_CANCEL', 'Abbrechen');
define('AM_AJAX_OK', 'OK');

define('AM_AJAX_SORT', 'Sortieren:');
define('AM_AJAX_TRACK_STOCK', 'Lagerbestände suchen?');
define('AM_AJAX_TRACK_STOCK_IMGALT', 'Dieses Attribut in Lagerbeständen finden?');

define('AM_AJAX_ENTER_NEW_OPTION_NAME', 'Bitte einen neuen Optionsnamen eingeben');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', 'Bitte einen neuen Optionsnamen eingeben');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', 'Bitte einen neuen Namen für den Optionswert %s hinzufügen');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', 'Sind Sie sicher, dass Sie %s und all seine folgenden Werte für dieses Produkt löschen wollen?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', 'Sind Sie sicher, dass Sie %s von diesem Produkt löschen wollen?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', 'Sind Sie sicher, dass Sie die Lager-Kombination von diesem Produkt löschen wollen?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', 'Sind Sie sicher, dass Sie %s Template laden wollen? <br />Dieses wird die aktuellen Produkt Optionen Überschreiben und kann nicht rückgängig gemacht werden.');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', 'Bitte einen neuen Namen für das neue Template eingeben. Oder...');
define('AM_AJAX_NEW_NAME', 'Neuer Name:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... einen Existierenden zum überschreiben auswählen');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', 'Existiert:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', 'Bitte einen neuen Namen für das Template %s eingeben');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', 'Sind Sie sicher, möchten Sie das Template %s Löschen?<br>Dies kann nicht rückgängig gemacht werden!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', 'Fügt das links ausgewählte Attribut zu der Option %s hinzu');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', 'Fügt einen neuen Wert zu der Option %s hinzu');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', 'Löscht, die Option %1$s und die %2$d Optionswert(e) die nachfolgen, von diesem Produkt');
define('AM_AJAX_CHANGES', 'Ändern'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', 'Lädt das ausgewählte Template');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', 'Sichert das aktuelle Attribut als ein neues Template');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', 'Benennt das ausgewählte Template um');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', 'Löscht das ausgewählte Template');
define('AM_AJAX_NAME', 'Name');
define('AM_AJAX_ACTION', 'Aktion');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', 'Löschen %1$s von %2$s, aus diesem Produkt');
define('AM_AJAX_MOVES_VALUE_UP', 'Verschiebt Optionswert hoch');
define('AM_AJAX_MOVES_VALUE_DOWN', 'Verschiebt OPtionswert runter');
define('AM_AJAX_ADDS_NEW_OPTION', 'Fügt eine neue Option zur Liste hinzu');
define('AM_AJAX_OPTION', 'Option:');
define('AM_AJAX_VALUE', 'Wert:');
define('AM_AJAX_PREFIX', 'Prefix:');
define('AM_AJAX_PRICE', 'Preis:');
define('AM_AJAX_SORT', 'Sortieren:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', 'Fügt einen neuen Optionswert zur Liste hinzu');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', 'Fügt das Attribut zum aktuellen Produkt hinzu');
define('AM_AJAX_QUANTITY', 'Eigenschaft');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', 'Löscht diese Attribut kombination und löst Lagerbestände von diesem Produkt auf');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', 'Aktualisiert oder fügt die Attributkombination mit den angegebenen Eigenschaften hinzu');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- Templates --');

//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
define('AM_AJAX_FILENAME', 'Dateiname');
define('AM_AJAX_FILE_DAYS', 'Download-Tage');
define('AM_AJAX_FILE_COUNT', 'Max. Downloads');
define('AM_AJAX_DOWLNOAD_EDIT', 'Download-Option bearbeiten');
define('AM_AJAX_DOWLNOAD_ADD_NEW', 'Download-Option hinzufügen');
define('AM_AJAX_DOWLNOAD_DELETE', 'Download-Option löschen');
define('AM_AJAX_HEADER_DOWLNOAD_ADD_NEW', 'Download-Option hinzufügen für \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_EDIT', 'Download-Option für \"%s\" bearbeiten');
define('AM_AJAX_HEADER_DOWLNOAD_DELETE', 'Download-Option für \"%s\" löschen');
define('AM_AJAX_FIRST_SAVE', 'Produkt muss gespeichert sein, damit Optionen hinzugef&uuml;gt werden k&ouml;nnen');

//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

define('AM_AJAX_OPTION_NEW_PANEL','Neue Option:');
?>
