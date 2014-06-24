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

define('AM_AJAX_YES', '��');
define('AM_AJAX_NO', '���');
define('AM_AJAX_UPDATE', '��������');
define('AM_AJAX_CANCEL', '��������');
define('AM_AJAX_OK', '��');

define('AM_AJAX_SORT', '������� ����������:');
define('AM_AJAX_TRACK_STOCK', '����������� ���-��?');
define('AM_AJAX_TRACK_STOCK_IMGALT', '����������� ���-�� ������� ��������?');

define('AM_AJAX_ENTER_NEW_OPTION_NAME', '������� �������� ����� �����');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', '������� �������� ����� �����');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', '������� �������� ����� ����� ����������� � %s');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', '�� �������, ��� ������ ������� %s � ��� ��������� �������� ��� ����� ������?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', '�� �������, ��� ������ ������� %s ��� ����� ������?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', '�� �������, ��� ������ ������� ��� ���������� ����� ��� ������?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', '�� �������, ��� ������ ��������� ������ %s? <br />��� ������� ����� ������ ����� ��������. ��������� ���������� ����� ��������.');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', '������� �������� ��� ������ �������. ���...');
define('AM_AJAX_NEW_NAME', '����� ������������:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... �������� ������������ ��� ��� ������');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', '������������:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', '������� ����� �������� ��� ������� %s');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', '�� �������, ��� ������ ������� ������ %s?<br>��������� ������ ����� ��������!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', '�������� ��������� ������� � ����� %s');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', '�������� ����� �������� � ����� %s');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', '������� ����� %1$s � %2$d �������� ������ ����� � ����� ������');
define('AM_AJAX_CHANGES', '���������'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', '��������� ��������� ������');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', '��������� ������� ��������� � �������� �������');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', '������������� ��������� ������');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', '������� ��������� ������');
define('AM_AJAX_NAME', '������������');
define('AM_AJAX_ACTION', '��������');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', '������� %1$s �� ����� %2$s ����� ������');
define('AM_AJAX_MOVES_VALUE_UP', '����������� ����� �����');
define('AM_AJAX_MOVES_VALUE_DOWN', '����������� ����� ����');
define('AM_AJAX_ADDS_NEW_OPTION', '�������� ����� ����� � ������');
define('AM_AJAX_OPTION', '�����:');
define('AM_AJAX_VALUE', '��������:');
define('AM_AJAX_PREFIX', '����.����:');
define('AM_AJAX_PRICE', '����:');
define('AM_AJAX_WEIGHT_PREFIX', '����.���:');
define('AM_AJAX_WEIGHT', '���:');
define('AM_AJAX_SORT', '�������:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', '�������� ����� �������� ����� � ������');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', '�������� ����� ����� � ������');
define('AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT', '������� ��� ����� ��� ���������� �����');
define('AM_AJAX_QUANTITY', '����������:');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', '������� ���������� ����� � �� ���������� ��� ����� ������');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', '�������� ��� �������� ���������� ����� � ��������� �����������');
define('AM_AJAX_UPDATE_PRODUCT_QUANTITY', '���������� ��������� ���������� ������');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- ������� --');

//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
define('AM_AJAX_FILENAME', '����');
define('AM_AJAX_FILE_DAYS', '����');
define('AM_AJAX_FILE_COUNT', '�������� ����������');
define('AM_AJAX_DOWLNOAD_EDIT', '������������� ����� ����������');
define('AM_AJAX_DOWLNOAD_ADD_NEW', '�������� ����� ����������');
define('AM_AJAX_DOWLNOAD_DELETE', '������� ����� ����������');
define('AM_AJAX_HEADER_DOWLNOAD_ADD_NEW', '�������� ����� ���������� ��� \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_EDIT', '������������� ����� ���������� ��� \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_DELETE', '������� ����� ���������� ��� \"%s\"');
define('AM_AJAX_FIRST_SAVE', '��������� ����� ����� ����������� �����.');

//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

define('AM_AJAX_OPTION_NEW_PANEL','����� �����:');
?>
