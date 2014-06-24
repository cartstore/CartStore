<?php
/*
  $Id: attributeManagerPrompts.inc.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License

  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

function yesNoButtons($section) {
return '
<div class="popupFooter">
			<input type="submit" value="'.AM_AJAX_YES.'" onClick="return '.$section.'();" class="amSubmitButton" />&nbsp;
			<input type="submit" value="'.AM_AJAX_NO.'" onClick="removeCustomPrompt();" class="amSubmitButton" />
</div>';
}

function updateCancelButtons($section) {
return '
<div class="popupFooter">
	<input type="submit" value="'.AM_AJAX_UPDATE.'" onClick="return '.$section.'();" class="amSubmitButton" />&nbsp;
	<input type="submit" value="'.AM_AJAX_CANCEL.'" onClick="removeCustomPrompt();" class="amSubmitButton" />
</div>';
}

function languageTextFields() {

$return = '
<table>';
	$languages = tep_get_languages();
	foreach ($languages as $amLanguage) {
$return .='
	<tr>
		<td align="right">'. tep_image(DIR_WS_CATALOG_LANGUAGES . $amLanguage['directory'] . '/images/' . $amLanguage['image'], $amLanguage['name']).'</td>
		<td>'.tep_draw_input_field('text_field_'.$amLanguage['id'],'','id="'.$amLanguage['id'].'"').'</td>
	</tr>';
	}
if ($_GET['section']== 'amAddOption') {
$style = 'style="margin:3px 0px 3px 0px;" id="stockTracking_1" size="4"';
$optionSortDrop = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
$return .= '	<tr>';
if(AM_USE_SORT_ORDER) {
	$return .= '<td>'.AM_AJAX_SORT.tep_draw_pull_down_menu('optionSortDropDown', $optionSortDrop, '', 'id="optionSortDropDown"').'</td>';
} else {
	$return .= tep_draw_hidden_field('optionSortDropDown', '0', 'id="optionSortDropDown"');
}

if(AM_USE_QT_PRO) {
	$return .='	<td>'.AM_AJAX_TRACK_STOCK.' <img src="attributeManager/images/icon_unchecked.gif" id="imgCheck_1" onclick="checkBox(1)" title="'.AM_AJAX_TRACK_STOCK_IMGALT.'" />
		    '. tep_draw_hidden_field('stockTracking_1', '0', $style).'
			</td>';
} else {
	$return .= tep_draw_hidden_field('stockTracking_1', '0', $style);
}
$return .='	</tr>';
}
$return .= '
</table>';
return $return;
}


function okButton() {
	return '<input type="submit" align="center" value="'.AM_AJAX_OK.'" onClick="removeCustomPrompt();" />';
}

class amPopups {
	var $header = '';
	var $contents = '';

	function setHeader($string) {
		$this->header .= $string;
	}

	function addToContents($string) {
		$this->contents .= $string;
	}

	function output() {
		return '
		<div id="popupHeading">'.stripcslashes($this->header).'</div>
		<div id="popupContainer">'.$this->contents.'</div>';
	}
}

// check that it is a prompt section
if(isset($_GET[AM_ACTION_GET_VARIABLE]) && $_GET[AM_ACTION_GET_VARIABLE] == 'prompt') {

	// de encode the extra gets string
	if(isset($_GET['gets'])) {
		$arrExtraValues = array();
		$valuePairs = array();

		if(strpos($_GET['gets'],'::'))
			$valuePairs = explode('::',$_GET['gets']);
		else
			$valuePairs[] = $_GET['gets'];

		foreach($valuePairs as $pair)
			if(strpos($pair,':')) {
				list($extraKey, $extraValue) = explode(':',$pair);
				$arrExtraValues[$extraKey] = $extraValue;
			}
	}

	switch($_GET['section']) {
		case 'amAddOption':
			$amPopup = new amPopups();
			$amPopup->setHeader(AM_AJAX_ENTER_NEW_OPTION_NAME);
			$amPopup->addToContents(languageTextFields());
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			echo $amPopup->output();
			break;
		case 'amAddOptionValue':
			$amPopup = new amPopups();
			$amPopup->setHeader(AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME);
			$amPopup->addToContents(languageTextFields());
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			echo $amPopup->output();
			break;
		case 'amAddNewOptionValueToProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO, $arrExtraValues['option_name']));
			$amPopup->addToContents(languageTextFields());
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			echo $amPopup->output();
			break;
		case 'amRemoveOptionFromProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES, $arrExtraValues['option_name']));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			echo $amPopup->output();
			break;
		case 'amRemoveOptionValueFromProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_PROMPT_REMOVE_OPTION, $arrExtraValues['option_value_name']));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			$amPopup->addToContents(tep_draw_hidden_field('option_value_id',$arrExtraValues['option_value_id'],'id="option_value_id"'));
			echo $amPopup->output();
			break;
//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
		case 'amAddNewDownloadForProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_HEADER_DOWLNOAD_ADD_NEW , $arrExtraValues['option_value_name']));
			//$amPopup->addToContents(languageTextFields());
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILENAME.'</div><div class="amField">'. tep_draw_input_field('products_attributes_filename','','id="products_attributes_filename"') );
			// only for TinyMCE filemanager
/*			$amPopup->addToContents('<a href="#" onClick="mcFileManager.open(document.forms[0].name,\'products_attributes_filename\',\'\',\'\',{document_base_url : \''.  DIR_WS_CATALOG . 'download/\',relative_urls:true,remove_script_host:true,rootpath:\''.DIR_FS_CATALOG.'download/\',remember_last_path:false});">' );
			$amPopup->addToContents( tep_image(DIR_WS_ADMIN. DIR_WS_INCLUDES . 'tiny_mce/themes/advanced/images/browse.gif', 'browse' ));
			$amPopup->addToContents('</a>');
*/			// EOF only for TinyMCE filemanager
			$amPopup->addToContents('</div>');
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILE_DAYS.'</div><div class="amField">'. tep_draw_input_field('products_attributes_maxdays','7','id="products_attributes_maxdays"') . '</div>');
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILE_COUNT.'</div><div class="amField">'. tep_draw_input_field('products_attributes_maxcount','5','id="products_attributes_maxcount"') . '</div>');
			//$amPopup->addToContents($_GET['section']);
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			$amPopup->addToContents(tep_draw_hidden_field('products_attributes_id',$arrExtraValues['products_attributes_id'],'id="products_attributes_id"'));
			echo $amPopup->output();
			break;
		case 'amEditDownloadForProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_HEADER_DOWLNOAD_EDIT , $arrExtraValues['option_value_name']));
			//$amPopup->addToContents(languageTextFields());
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILENAME.'</div><div class="amField">'.tep_draw_input_field('products_attributes_filename',$arrExtraValues['products_attributes_filename'],'id="products_attributes_filename"') );
			// only for TinyMCE filemanager
/*			$amPopup->addToContents('<a href="#" onClick="mcFileManager.open(document.forms[0].name,\'products_attributes_filename\',\'\',\'\',{document_base_url : \''.  DIR_WS_CATALOG . 'download/\',relative_urls:true,remove_script_host:true,rootpath:\''.DIR_FS_CATALOG.'download/\',remember_last_path:false});">' );
			$amPopup->addToContents( tep_image(DIR_WS_ADMIN. DIR_WS_INCLUDES . 'tiny_mce/themes/advanced/images/browse.gif', 'browse' ));
			$amPopup->addToContents('</a>');
*/			// EOF only for TinyMCE filemanager
			$amPopup->addToContents('</div>');
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILE_DAYS.'</div><div class="amField">' .tep_draw_input_field('products_attributes_maxdays',$arrExtraValues['products_attributes_maxdays'],'id="products_attributes_maxdays"') . '</div>');
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents('<DIV class="amFieldset">');
			$amPopup->addToContents('<div class="amFieldLabel">' .AM_AJAX_FILE_COUNT.'</div><div class="amField">' .tep_draw_input_field('products_attributes_maxcount',$arrExtraValues['products_attributes_maxcount'],'id="products_attributes_maxcount"') . '</div>');
			$amPopup->addToContents('</DIV>');
			//$amPopup->addToContents($_GET['section']);
			$amPopup->addToContents('</DIV>');
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			$amPopup->addToContents(tep_draw_hidden_field('products_attributes_id',$arrExtraValues['products_attributes_id'],'id="products_attributes_id"'));
			echo $amPopup->output();
			break;
		case 'amDeleteDownloadForProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_HEADER_DOWLNOAD_DELETE, $arrExtraValues['option_value_name']));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			$amPopup->addToContents(tep_draw_hidden_field('products_attributes_id',$arrExtraValues['products_attributes_id'],'id="products_attributes_id"'));
			echo $amPopup->output();
			break;
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

// For QT Pro Plugin -- added by Phocea
		case 'amRemoveStockOptionValueFromProduct':
			$amPopup = new amPopups();
			$amPopup->setHeader(AM_AJAX_PROMPT_STOCK_COMBINATION);
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('option_id',$arrExtraValues['option_id'],'id="option_id"'));
			echo $amPopup->output();
			break;
// For QT Pro Plugin -- added by Phocea
		case 'loadTemplate':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_PROMPT_LOAD_TEMPLATE, $arrExtraValues['template_name']));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('template_id',$arrExtraValues['template_id'],'id="template_id"'));
			echo $amPopup->output();
			break;
		case 'saveTemplate':
			$amPopup = new amPopups();
			$amPopup->setHeader(AM_AJAX_NEW_TEMPLATE_NAME_HEADER);
			$amPopup->addToContents(AM_AJAX_NEW_NAME.'&nbsp'.tep_draw_input_field('template_name','','id="template_name" onchange="((this.value != \'\') ? document.getElementById(\'existing_template\').selectedIndex = 0 : \'\')"'));
			$templatesDrop = $attributeManager->buildAllTemplatesDropDown();
			$amPopup->setHeader(AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE);
			$amPopup->addToContents('<br /><br />'. AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE .'&nbsp'.  tep_draw_pull_down_menu('existing_template',$templatesDrop,'0','id="existing_template" onChange="document.getElementById(\'template_name\').value=\'\';"'));
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('template_id',$arrExtraValues['template_id'],'id="template_id"'));
			echo $amPopup->output();
			break;
		case 'renameTemplate':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME, $arrExtraValues['template_name']));
			$amPopup->addToContents(AM_AJAX_NEW_NAME.'&nbsp'.tep_draw_input_field('template_new_name','','id="template_new_name"'));
			$amPopup->addToContents(updateCancelButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('template_id',$arrExtraValues['template_id'],'id="template_id"'));
			echo $amPopup->output();
			break;
		case 'deleteTemplate':
			$amPopup = new amPopups();
			$amPopup->setHeader(sprintf(AM_AJAX_PROMPT_DELETE_TEMPLATE, $arrExtraValues['template_name']));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('template_id',$arrExtraValues['template_id'],'id="template_id"'));
			echo $amPopup->output();
			break;
		case 'debug':
			$amPopup = new amPopups();
			$amPopup->setHeader(implode($valuePairs, ','));
			$amPopup->addToContents(yesNoButtons($_GET['section']));
			$amPopup->addToContents(tep_draw_hidden_field('debug',$arrExtraValues['debug'],'id="debug"'));
			echo $amPopup->output();
			break;
	}



}

?>
