<?php
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License

  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

// change the directory upone for application top includes
chdir('../');
//ini_set('include_path', dirname(dirname(__FILE__)) . (((substr(strtoupper(PHP_OS),0,3)) == "WIN") ? ";" : ":") . ini_get('include_path'));

// OSC application top needed for sessions, defines and functions
require_once('includes/application_top.php');

// db wrapper
require_once('attributeManager/classes/amDB.class.php');

// session functions
require_once('attributeManager/includes/attributeManagerSessionFunctions.inc.php');

// config
require_once('attributeManager/classes/attributeManagerConfig.class.php');

// misc functions
require_once('attributeManager/includes/attributeManagerGeneralFunctions.inc.php');

// parent class
require_once('attributeManager/classes/attributeManager.class.php');

// instant class
require_once('attributeManager/classes/attributeManagerInstant.class.php');

// atomic class
require_once('attributeManager/classes/attributeManagerAtomic.class.php');

// security class
require_once('attributeManager/classes/stopDirectAccess.class.php');

// check that the file is allowed to be accessed
stopDirectAccess::checkAuthorisation(AM_SESSION_VALID_INCLUDE);


// get an instance of one of the attribute manager classes
$attributeManager =& amGetAttributeManagerInstance($_GET);

// do any actions that should be done
$globalVars = $attributeManager->executePageAction($_GET);


// set any global variables from the page action execution
if(0 !== count($globalVars) && is_array($globalVars))
	foreach($globalVars as $varName => $varValue)
		$$varName = $varValue;


// get the current products options and values
$allProductOptionsAndValues = $attributeManager->getAllProductOptionsAndValues(true);
//$SortedProductAttributes = $attributeManager->sortArrSessionVar();


// count the options
$numOptions = count($allProductOptionsAndValues);
// output a response header
//header('Content-type: text/html; charset=ISO-8859-1');
header('Content-type: text/html; charset='.CHARSET);

//$attributeManager->debugOutput($allProductOptionsAndValues);
//$attributeManager->debugOutput($SortedProductAttributes);
//$attributeManager->debugOutput($attributeManager);

// include any prompts
require_once('attributeManager/includes/attributeManagerPrompts.inc.php');

if(!isset($_GET['target']) || 'topBar' == $_GET['target'] ) {
	if(!isset($_GET['target']))
		echo '<div id="topBar">';
?>

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<?php
		$languages = tep_get_languages();
		if(count($languages) > 1) {
			foreach ($languages as $amLanguage) {
			?>
			&nbsp;<input type="image" <?php echo ($attributeManager->getSelectedLanaguage() == $amLanguage['id']) ? 'style="padding:1px;border:1px solid black" onClick="return false" ' :'onclick="return amSetInterfaceLanguage(\''.$amLanguage['id'].'\');" '?> src="<?php echo DIR_WS_CATALOG_LANGUAGES . $amLanguage['directory'] . '/images/' . $amLanguage['image']?>"  border="0" title="<?php echo AM_AJAX_CHANGES; ?>" />
			<?php
			}
		}
		?>
		</td>
		<td align="right">

		<?php
		if(false !== AM_USE_TEMPLATES) {
			?>
			<div  style="padding:5px 3px 5px 0px">
				<input type="image" <?php if($attributeManager->getTemplateOrder()=='123'){echo 'style="border:1px solid #DDDDDD;"';} ?> src="attributeManager/images/icon_123.png" onclick="return amTemplateOrder('123');" border="0" title="" />
				<input type="image" <?php if($attributeManager->getTemplateOrder()=='abc'){echo 'style="border:1px solid #DDDDDD;"';} ?> src="attributeManager/images/icon_abc.png" onclick="return amTemplateOrder('abc');" border="0" title="" />
				&nbsp;
				<?php echo tep_draw_pull_down_menu('template_drop',$attributeManager->buildAllTemplatesDropDown($attributeManager->getTemplateOrder()),(0 == $selectedTemplate) ? '0' : $selectedTemplate,'id="template_drop" style="margin-bottom:3px"');	?>
				&nbsp;
				<input type="image" src="attributeManager/images/icon_load.png" onclick="return customTemplatePrompt('loadTemplate');" border="0" title="<?php echo AM_AJAX_LOADS_SELECTED_TEMPLATE; ?>" />
				&nbsp;
				<input type="image" src="attributeManager/images/icon_save.png" onclick="return customPrompt('saveTemplate');" border="0" title="<?php echo AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE; ?>" />
				&nbsp;
				<input type="image" src="attributeManager/images/icon_rename.png" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="<?php echo AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE; ?>" />
				&nbsp;
				<input type="image" src="attributeManager/images/icon_delete.png" onclick="return customTemplatePrompt('deleteTemplate');" border="0" title="<?php echo AM_AJAX_DELETES_THE_SELECTED_TEMPLATE; ?>" />
				&nbsp;
			</div>
			<?php
		}
		?>
		</td>
	</tr>
</table>
<?php
	if(!isset($_GET['target']))
		echo '</div>';
} // end target = topBar

if(!isset($_GET['target']))
	echo '<div id="attributeManagerAll">';
?>
<?php
if(!isset($_GET['target']) || 'currentAttributes' == $_GET['target']) {
	if(!isset($_GET['target']))
		echo '<div id="currentAttributes">';
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr class="header">
			<td width="50" align="center">
				<input type="image" src="attributeManager/images/icon_plus.gif" onclick="return amShowHideAllOptionValues([<?php echo implode(',',array_keys($allProductOptionsAndValues));?>],true);" border="0" />
				&nbsp;
				<input type="image" src="attributeManager/images/icon_minus.gif" onclick="return amShowHideAllOptionValues([<?php echo implode(',',array_keys($allProductOptionsAndValues));?>],false);" border="0" />
			</td>
			<td>
				<?php echo AM_AJAX_NAME; ?>
			</td>

			<td align="right">
				<span style="margin-right:40px"><?php echo AM_AJAX_ACTION; ?></span>
			</td>
		</tr>

	<?php
	if(0 < $numOptions) {
		foreach($allProductOptionsAndValues as $optionId => $optionInfo){
			$numValues = count($optionInfo['values']);
	?>
			<tr class="option">
				<td align="center">
				<input type="image" border="0" id="show_hide_<?php echo $optionId; ?>" src="attributeManager/images/icon_plus.gif" onclick="return amShowHideOptionsValues(<?php echo $optionId; ?>);" />

				</td>
				<td>
					<?php echo "{$optionInfo['name']} ($numValues)";?>
				</td>

				<td align="right">
					<?php echo tep_draw_pull_down_menu("new_option_value_$optionId",$attributeManager->buildOptionValueDropDown($optionId),$selectedOptionValue,'style="margin:3px 0px 3px 0px;" id="new_option_value_'.$optionId.'"')?>
					<input type="image" src="attributeManager/images/icon_add.png" value="Add" border="0" onclick="return amAddOptionValueToProduct('<?php echo $optionId?>');" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION, $optionInfo['name'])); ?>" />

					<input type="image" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_ADDS_NEW_VALUE_TO_OPTION,$optionInfo['name'])) ?>" border="0" src="attributeManager/images/icon_add_new.png" onclick="return customPrompt('amAddNewOptionValueToProduct','<?php echo addslashes("option_id:$optionId::option_name:".str_replace('"','&quot;',$optionInfo['name']))?>');" />
<?php
if(false){
?>
<!--					<input type="image" src="attributeManager/images/icon_rename.png" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="Renames the selected template" />-->
<?php
}
?>
					<input type="image" border="0" onClick="return customPrompt('amRemoveOptionFromProduct','<?php echo addslashes("option_id:$optionId::option_name:".str_replace('"','&quot;',$optionInfo['name']))?>');" src="attributeManager/images/icon_delete.png" title="<?php echo htmlspecialchars(addslashes(sprintf(AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES,$optionInfo['name'],$numValues))) ?>" />


					<?php
					if(AM_USE_SORT_ORDER) {
					?>
					<input type="image" onclick="return amMoveOption('<?php echo 'option_id:'.$optionId ; ?>', 'up');" src="attributeManager/images/icon_up.png" title="<?php echo AM_AJAX_MOVES_OPTION_UP; ?>" />
					<input type="image" onclick="return amMoveOption('<?php echo 'option_id:'.$optionId ; ?>', 'down');" src="attributeManager/images/icon_down.png" title="<?php echo AM_AJAX_MOVES_OPTION_DOWN; ?>" />
					<?php
					}
					?>
				</td>
			</tr>

<!-- ----- -->
<!-- Show Option Values -->
<!-- ----- -->
	<?php
			if(0 < $numValues){
				foreach($optionInfo['values'] as $optionValueId => $optionValueInfo) {
	?>

			<tr class="optionValue" id="trOptionsValues_<?php echo $optionId; ?>" style="display:none" >
				<td align="center">
					<img src="attributeManager/images/icon_arrow.gif" />
				</td>
				<td>
					<?php echo $optionValueInfo['name']; ?>

				</td>
				<td align="right">
<?php
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: Add Buttons for functionality
//-----------------------------


					if($optionValueInfo['products_attributes_filename']){

						?>

					<input type="image" border="0" onClick="return customPrompt('amEditDownloadForProduct','<?php echo addslashes('option_id:' . $optionId . '::products_attributes_filename:' . $optionValueInfo['products_attributes_filename'] . '::products_attributes_maxdays:'.$optionValueInfo['products_attributes_maxdays']  . '::products_attributes_maxcount:'.$optionValueInfo['products_attributes_maxcount'] .'::option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name'] .'::products_attributes_id:'.$optionValueInfo['products_attributes_id']))?>');" src="attributeManager/images/icon_down_edit.png" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_EDIT,$optionValueInfo['name'],$optionInfo['name'])) ?>" />
					<input type="image" border="0" onClick="return customPrompt('amDeleteDownloadForProduct','<?php echo addslashes('option_id:' . $optionId . '::option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name']) .'::products_attributes_id:'.$optionValueInfo['products_attributes_id'])?>');" src="attributeManager/images/icon_down_delete.png" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_DELETE,$optionValueInfo['name'],$optionInfo['name'])) ?>" style="margin-right: 30px;" />

					<?php
					} else {
					?>
					<input type="image" border="0" onClick="return customPrompt('amAddNewDownloadForProduct','<?php echo addslashes('option_id:' . $optionId .'::option_value_id:'.$optionValueId . '::option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name']).'::products_attributes_id:'.$optionValueInfo['products_attributes_id'])?>');" src="attributeManager/images/icon_download.png" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_ADD_NEW,$optionValueInfo['name'],$optionInfo['name'])) ?>" style="margin-right: 30px;" />
<?php
					}


//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
?>

					<span style="margin-right:41px;">
					<?php echo drawDropDownPrefix('id="prefix_'.$optionValueId.'" style="margin:3px 0px 3px 0px;" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'prefix\');"',$optionValueInfo['prefix']);?><?php echo tep_draw_input_field("price_$optionValueId",$optionValueInfo['price'],' style="margin:3px 0px 3px 0px;" id="price_'.$optionValueId.'" size="7" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'price\');"'); ?>
<?php
// More Product Weight added by RusNN
  if (AM_USE_MPW) {
    echo drawDropDownWeightPrefix('id="weight_prefix_'.$optionValueId.'" style="margin:3px 0px 3px 0px;" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'weight_prefix\');"',$optionValueInfo['weight_prefix']);
    echo tep_draw_input_field("weight_$optionValueId",$optionValueInfo['weight'],' style="margin:3px 0px 3px 0px;" id="weight_'.$optionValueId.'" size="7" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'weight\');"');
  }
?>
					<?php
					if(AM_USE_SORT_ORDER) {
/*					?>
					<?php echo tep_draw_input_field("sortOrder_$optionValueId",$optionValueInfo['sortOrder'],' style="margin:3px 0px 3px 0px;" id="sortOrder_'.$optionValueId.'" size="4" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\');"'); ?>
					<?php
*/					}
					?>
					</span>
<?php
if(false){
?>
<!--					<input type="image" src="attributeManager/images/icon_rename.png" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="Renames the selected template" />-->
<?php
}
?>
					<input type="image" border="0" onClick="return customPrompt('amRemoveOptionValueFromProduct','<?php echo addslashes("option_id:$optionId::option_value_id:$optionValueId::option_value_name:".str_replace('"','&quot;',$optionValueInfo['name']))?>');" src="attributeManager/images/icon_delete.png" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION,$optionValueInfo['name'],$optionInfo['name'])) ?>" />
					<?php
					if(AM_USE_SORT_ORDER) {
					?>
						<input type="image" onclick="return amMoveOptionValue('<?php echo 'option_id:'.$optionId.'::option_value_id:'.$optionValueId.'::products_attributes_id:'.$optionValueInfo['products_attributes_id']; ?>', 'up');" src="attributeManager/images/icon_up.png" title="<?php echo AM_AJAX_MOVES_VALUE_UP; ?>" />
						<input type="image" onclick="return amMoveOptionValue('<?php echo 'option_id:'.$optionId.'::option_value_id:'.$optionValueId.'::products_attributes_id:'.$optionValueInfo['products_attributes_id']; ?>', 'down');" src="attributeManager/images/icon_down.png" title="<?php echo AM_AJAX_MOVES_VALUE_DOWN; ?>" />
					<?php
					}
					?>
				</td>
			</tr>
	<?php
				}
			}
		}
	}
	?>
<!-- ----- -->
<!-- EOF Show Option Values -->
<!-- ----- -->
	</table>
	<?php
	if(!isset($_GET['target']))
		echo '</div>';
} // end target = currentAttributes

if(!isset($_GET['target']) || 'newAttribute' == $_GET['target'] ) {
	if(!isset($_GET['target']))
		echo '<div id="newAttribute">';

	// check to see if the selected option isset if it isn't pick the first otion in the dropdown
	$optionDrop = $attributeManager->buildOptionDropDown();

	if(!is_numeric($selectedOption)) {
		foreach($optionDrop as $key => $value) {
			if(tep_not_null($value['id'])){
				$selectedOption = $value['id'];
				break;
			}
		}
	}

	$optionValueDrop = $attributeManager->buildOptionValueDropDown($selectedOption);
?>
<!-- ----- -->
<!-- SHOW NEW OPTION PANEL on Bottom -->
<!-- ----- -->
		<div class="newOptionPanel-header">
			<?php echo AM_AJAX_OPTION_NEW_PANEL; ?>
		</div>
	<table border="0"  cellpadding="0" cellspacing="0">
		<tr>
			<td align="right" valign="middle" class="newOptionPanel-label">
				<?php echo AM_AJAX_OPTION; ?> <?php echo tep_draw_pull_down_menu('optionDropDown',$optionDrop,$selectedOption,'id="optionDropDown" onChange="return amUpdateNewOptionValue(this.value);" class="optionDropDown"')?>
			</td>
			<td align="right" valign="middle" class="newOptionPanel-button">
				<input border="0"  type="image" src="attributeManager/images/icon_add_new.png" onclick="return customPrompt('amAddOption');" title="<?php echo AM_AJAX_ADDS_NEW_OPTION; ?>" />
			</td>
			<td align="right" valign="middle" class="newOptionPanel-label">
				<?php echo AM_AJAX_VALUE; ?> <?php echo tep_draw_pull_down_menu('optionValueDropDown',$optionValueDrop,(is_numeric($selectedOptionValue) ? $selectedOptionValue : ''),'id="optionValueDropDown" class="optionValueDropDown"')?>
			</td>
			<td align="right" valign="middle" class="newOptionPanel-button">
					<input border="0" type="image" src="attributeManager/images/icon_add_new.png" onclick="return customPrompt('amAddOptionValue');" title="<?php echo AM_AJAX_ADDS_NEW_OPTION_VALUE; ?>" />
			</td>
			<td valign="top" class="newOptionPanel-label">
				<?php echo AM_AJAX_PREFIX; ?> <?php echo drawDropDownPrefix('id="prefix_0"')?>
			</td>
			<td valign="top" class="newOptionPanel-label">
				<?php echo AM_AJAX_PRICE; ?> <?php echo tep_draw_input_field('newPrice','','size="4" id="newPrice"'); ?>
			</td>
<?php
// More Product Weight added by RusNN
  if (AM_USE_MPW) {
?>
      <td valign="top" class="newOptionPanel-label">
        <?php echo AM_AJAX_WEIGHT_PREFIX; ?> <?php echo drawDropDownWeightPrefix('id="weight_prefix_0"')?>
      </td>
      <td valign="top" class="newOptionPanel-label">
        <?php echo AM_AJAX_WEIGHT; ?> <?php echo tep_draw_input_field('newWeight','','size="4" id="newWeight"'); ?>
      </td>
<?php
  }
?>
			<?php
			if(AM_USE_SORT_ORDER) {
			?>
			<!--
			<td valign="top" class="newOptionPanel-label">
				<?php echo AM_AJAX_SORT; ?> <?php echo tep_draw_input_field('newSort','','size="4" id="newSort"'); ?>
			</td>
			-->
			<?php
			} else {
			?>
			<td valign="top">
				<?php echo tep_draw_hidden_field('newSort','','size="4" id="newSort"'); ?>
			</td>
			<?php
			}
			?>

			<td align="right" valign="middle" class="newOptionPanel-button">
				<input type="image" src="attributeManager/images/icon_add.png" value="Add" onclick="return amAddAttributeToProduct();" title="<?php echo AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT; ?>" border="0"  />
			</td>
		</tr>
	</table>
<?php
	if(!isset($_GET['target']))
		echo '</div>';
} // end target = newAttribute
if(!isset($_GET['target']))
	echo '</div>';
?>
<?php
// Modified by RusNN
if (AM_USE_QT_PRO) {
  $products_id = tep_db_prepare_input($_GET['products_id']);

   if(!isset($_GET['target']) || 'currentProductStockValues' == $_GET['target']) {
	if(!isset($_GET['target']))
		echo '<div id="currentProductStockValues">';

    $q=tep_db_query($sql="select products_name, products_options_name as _option, products_attributes.options_id as _option_id, products_options_values_name as _value, products_attributes.options_values_id as _value_id from ".
                  "products_description, products_attributes, products_options, products_options_values where ".
                  "products_attributes.products_id = products_description.products_id and ".
                  "products_attributes.products_id = '" . $products_id . "' and ".
                  "products_attributes.options_id = products_options.products_options_id and ".
                  "products_attributes.options_values_id = products_options_values.products_options_values_id and ".
                  "products_description.language_id = " . (int)$languages_id . " and ".
                  "products_options_values.language_id = " . (int)$languages_id . " and products_options.products_options_track_stock = 1 and ".
                  "products_options.language_id = " . (int)$languages_id . " order by products_attributes.options_id, products_attributes.options_values_id");
  if (tep_db_num_rows($q)>0) {
    $flag = true;

    while($list=tep_db_fetch_array($q)) {
      $options[$list[_option_id]][]=array($list[_value],$list[_value_id]);
      $option_names[$list[_option_id]]=$list[_option];
      $product_name=$list[products_name];
    }
  } else {
    $flag = false;
  }
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr class="header">
			<td width="50" align="center">
				&nbsp;
			</td>
			<td>
				<?php echo AM_AJAX_QT_PRO; ?>
			</td>

			<td align="right" colspan="<?php echo (sizeof($options)+2); ?>">
				<span style="margin-right:40px"><?php echo AM_AJAX_ACTION; ?></span>
			</td>
		</tr>
<?php
  if ($flag) {
?>		<tr class="option">
			<td align="center">
			<input type="image" border="0" id="show_hide_9999" src="attributeManager/images/icon_plus.gif" onclick="return amShowHideOptionsValues(9999);" />
			</td>
<?php
    while(list($k,$v)=each($options)) {
?>
			<td>
				<?php echo $option_names[$k]; ?>
			</td>
<?php
      $title[$title_num]=$k;
    }
?>
			<td align="right">
				<span style="margin-right:41px;">
				<?php echo AM_AJAX_QUANTITY; ?>
				</span>
			</td>
		</tr>
<?php
    $q=tep_db_query("select * from " . TABLE_PRODUCTS_STOCK . " where products_id='" . $products_id . "' order by products_stock_attributes");
    while($rec=tep_db_fetch_array($q)) {
      $val_array=explode(",",$rec[products_stock_attributes]);
?>
		<tr class="optionValue" id="trOptionsValues_9999" style="display:none" >
			<td align="center">
				<?php echo $rec[products_stock_id]; ?>
				<img src="attributeManager/images/icon_arrow.gif" />
			</td>
<?php
      foreach($val_array as $val) {
        if (preg_match("/^(\d+)-(\d+)$/",$val,$m1)) {
?>
			<td>
				&nbsp;&nbsp;&nbsp;<?php echo tep_values_name($m1[2]); ?>
			</td>
<?php
        } else {
?>
       			<td>
       				&nbsp;
       			</td>
<?php
        }
      }
      for($i=0;$i<sizeof($options)-sizeof($val_array);$i++) {
?>
       			<td>
       				&nbsp;
       			</td>
<?php
      }
?>
			<td align="right">
				<span style="margin-right:41px;">
				<?php echo tep_draw_input_field("productStockQuantity_$rec[products_stock_id]", $rec[products_stock_quantity], ' style="margin:3px 0px 3px 0px;" id="productStockQuantity_'.$rec[products_stock_id].'" size="4" onChange="return amUpdateProductStockQuantity(\''.$rec[products_stock_id].'\');"'); ?>
				</span>
				<input type="image" border="0" onClick="return customPrompt('amRemoveStockOptionValueFromProduct','<?php echo addslashes("option_id:$rec[products_stock_id]")?>');" src="attributeManager/images/icon_delete.png" title="<?php echo AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT; ?>" />
			</td>
		</tr>
<?php
    }
?>
<?php
  }
?>
	</table>
<?php
	if(!isset($_GET['target']))
		echo '</div>';
	} // end target = currentStockValues
if(!isset($_GET['target']) || 'newProductStockValue' == $_GET['target'] ) {

	if(!isset($_GET['target']))
		echo '<div id="newProductStockValue">';
?>
	<table border="0" cellpadding="3">
		<tr>
			<td align="right" valign="top">
<?php
  if ($flag) {
    // There are number of options, assigned to product. Allow to add this in combination with quantity (RusNN)
    reset($options);
    $i=0;
    while(list($k,$v)=each($options)) {
      echo "<td><select name=option$k id=option$k>";
      $dropDownOptions[] = 'option'.$k;
      foreach($v as $v1) {
        echo "<option value=".$v1[1].">".$v1[0];
      }
      echo "</select></td>";
      $i++;
    }
    $db_quantity = 1; // pre set value for 1 qty of options combination
  } else {
    // No options available for product. Should work with product quantity only. Get it from DB (RusNN)
    $q=tep_db_query("select products_quantity, products_name from " . TABLE_PRODUCTS . " p,products_description pd where pd.products_id= p.products_id and p.products_id='" . $products_id ."'");
    $list=tep_db_fetch_array($q);
    $db_quantity=$list[products_quantity];
    $dropDownOptions = array();
  }
?>
            <td><?php echo AM_AJAX_QUANTITY; ?></td>
            <td>
                <?php echo tep_draw_input_field("stockQuantity", $db_quantity, ' style="margin:3px 0px 3px 0px;" id="stockQuantity" size="4"'); ?>
            </td>
            <td>
                <input type="image" src="attributeManager/images/icon_add.png" value="Add" onclick="return amAddStockToProduct('<?php echo implode(",", $dropDownOptions); ?>');" title="<?php echo ($flag) ? AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY : AM_AJAX_UPDATE_PRODUCT_QUANTITY;?>" border="0"  />
            </td>
		</tr>
	</table>
<?php
	if(!isset($_GET['target']))
		echo '</div>';
} // end target = newProductStockValue
if(!isset($_GET['target']))
	echo '</div>';
?>
<?php
} // End QT Pro Plugin
?>