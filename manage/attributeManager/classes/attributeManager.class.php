<?php
/*
  $Id: attributeManager.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License

  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/


/*

interface attributeManagerInterface {
	function getAllProductOptionsAndValues();
	function removeOptionFromProduct();
	function addAttributeToProduct();
}
*/

/*abstract*/ class attributeManager /*implements attributeManagerInterface*/ {

	/**
	 * Holds all of the options in the database
	 * @access private
	 */
	var $arrAllOptions = array();

	/**
	 * Holds all of the option values in the database
	 * @access private
	 */
	var $arrAllOptionValues = array();

	/**
	 * Holds all of the options and their values where they are releated to each other
	 * @access private
	 */
	var $arrAllOptionsAndValues = array();

	/**
	 * Holds all of the current products options and option values
	 * @access protected
	 */
	var $arrAllProductOptionsAndValues = array();

	/**
	 * Currently selected language id
	 * @todo make multilingual
	 * @access private
	 */
	var $intLanguageId;

	/**
	 * Page actions
	 * @var $arrPageActions Array
	 * @access private
	 */
	var $arrPageActions = array();

	/**
	 * All templates
	 * @var $arrAllTemplatesAndAttributes Array
	 */
	var $arrAllTemplatesAndAttributes = array();

	/**
	 * __construct()-
	 * Sets up page actions and sets the interface language
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return void
	 */
	function attributeManager() {

		$this->setInterfaceLanguage();
		$this->setTemplateOrder();

		$this->registerPageAction('addOption','addOption');
		$this->registerPageAction('addOptionValue','addOptionValue');
		$this->registerPageAction('updateNewOptionValue','updateNewOptionValue');

		$this->registerPageAction('setTemplateOrder','setTemplateOrder');

		$this->registerPageAction('deleteTemplate','deleteTemplate');
		$this->registerPageAction('saveTemplate','saveTemplate');
		$this->registerPageAction('renameTemplate','renameTemplate');
		$this->registerPageAction('loadTemplate','loadTemplate');
		$this->registerPageAction('setInterfaceLanguage','setInterfaceLanguage');
	}




	//---------------------------------------------------------------------------------------------- core

	/**
	 * Sets the interface language
	 * @param $get $_GET (optional)
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return void
	 */
	function setInterfaceLanguage($get=array()) {
		// setting new interface language
		if(count($get) > 0) {
			if(is_numeric($get['language_id'])) {
				amSessionRegister(AM_SESSION_CURRENT_LANG_VAR_NAME);
				amSetSessionVariable(AM_SESSION_CURRENT_LANG_VAR_NAME,$get['language_id']);
				$this->intLanguageId = $get['language_id'];
			}
		}
		// not called from the page. ie costruct
		else {
			$langId = amGetSesssionVariable(AM_SESSION_CURRENT_LANG_VAR_NAME);
			if(false !== $langId)
				$this->intLanguageId = $langId;
			else
				$this->intLanguageId = AM_DEFAULT_LANGUAGE_ID;
		}
	}

	/**
	 * Returns the currently selected language id
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return int language id
	 */
	function getSelectedLanaguage() {
		return $this->intLanguageId;
	}

	/**
	 * Sets Template order
	 * @param $get $_GET (optional)
	 * @access public
	 * @author Tomasz Iwanow aka TomaszBG - microvision@gmail.com
	 * @return void
	 */
	function setTemplateOrder($get=array()){
		if(count($get) > 0) {
			if(isset($get['templateOrder'])) {
				amSessionRegister(AM_SESSION_CURRENT_TEMPLATE_ORDER);
				amSetSessionVariable(AM_SESSION_CURRENT_TEMPLATE_ORDER,$get['templateOrder']);
				$this->strTemplateOrder = $get['templateOrder'];
			}
		}else{
			$templateOrder = amGetSesssionVariable(AM_SESSION_CURRENT_TEMPLATE_ORDER);
			if(false !== $templateOrder)
				$this->strTemplateOrder = $templateOrder;
			else
				$this->strTemplateOrder = AM_DEFAULT_TEMPLATE_ORDER;
		}
	}

	/**
	 * Returns the currently selected Template order
	 * @access public
	 * @author Tomasz Iwanow aka TomaszBG - microvision@gmail.com
	 * @return str Template order
	 */
	function getTemplateOrder() {
		return $this->strTemplateOrder;
	}

	/**
	 * Gets all of the options in the database
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array all options
	 */
	function getAllOptions() {
		if(0 === count($this->arrAllOptions)) {
			$queryString = "select * from ".TABLE_PRODUCTS_OPTIONS." where language_id='".amDB::input($this->intLanguageId)."' order by ";
			$queryString .= !AM_USE_SORT_ORDER ?  "products_options_name" : AM_FIELD_OPTION_SORT_ORDER;
			$query = amDB::query($queryString);

			while($res = amDB::fetchArray($query))
				$this->arrAllOptions[$res['products_options_id']] = $res['products_options_name'];
		}

		return $this->arrAllOptions;
	}

	/**
	 * Gets all of the option values in the database
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array all option values
	 */
	function getAllOptionValues() {
		if(0 === count($this->arrAllOptionValues)) {
			$query = amDB::query("select * from ".TABLE_PRODUCTS_OPTIONS_VALUES." where language_id='".amDB::input($this->intLanguageId)."'");
			while($res = amDB::fetchArray($query))
				$this->arrAllOptionValues[$res['products_options_values_id']] = $res['products_options_values_name'];
		}
		return $this->arrAllOptionValues;
	}

	/**
	 * Returns an array of options with their related option values
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array All options and option values
	 */
	function getAllOptionsAndValues() {
		if(0 === count($this->arrAllOptionsAndValues)){

			$allOptions = $this->getAllOptions();
			$allOptionValues = $this->getAllOptionValues();

			$query = amDB::query("select * from ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS);

			$optionsId = null;
			while($res = amDB::fetchArray($query)) {
				if($res['products_options_id'] != $optionsId) {
					$optionsId = $res['products_options_id'];
					$this->arrAllOptionsAndValues[$optionsId]['name'] = $allOptions[$optionsId];
				}
				$this->arrAllOptionsAndValues[$optionsId]['values'][$res['products_options_values_id']] = $allOptionValues[$res['products_options_values_id']];
			}

			// add any options that are not yet assigned to the tpovtpo table
			foreach($allOptions as $optionId => $option)
				if(!array_key_exists($optionId, $this->arrAllOptionsAndValues))
					$this->arrAllOptionsAndValues[$optionId]['name'] = $allOptions[$optionId];

		}

		return $this->arrAllOptionsAndValues;
	}

	//---------------------------------------------------------------------------------------------- page actions

	/**
	 * Adds a new option to the database
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return array global variables to be set
	 */
	function addOption($get) {

		$this->getAndPrepare('options', $get, $options);
		if (AM_USE_SORT_ORDER) {
			$this->getAndPrepare('optionSort', $get, $optionSort);
		}
		if (AM_USE_QT_PRO) {
			$this->getAndPrepare('optionTrack', $get, $optionTrack);
		}
		if(strpos($options,'::'))
			$arrOptions = explode('::', $options);
		else
			$arrOptions[] = $options;

		$id = amDB::getNextAutoValue(TABLE_PRODUCTS_OPTIONS,'products_options_id');

		foreach($arrOptions as $option) {

			list($langId,$name) = explode(':',$option);

      if ($id == 0){
        $id = 1;
      }

			$arrData = array (
				'products_options_id' => $id,
				'language_id' => amDB::input($langId),
				'products_options_name' => amDB::input($name)
			);
			if (AM_USE_SORT_ORDER) {
				$arrData[AM_FIELD_OPTION_SORT_ORDER] = amDB::input($optionSort);
			}
			if (AM_USE_QT_PRO) {
				$arrData['products_options_track_stock'] = amDB::input($optionTrack);
			}

			amDB::perform(TABLE_PRODUCTS_OPTIONS,$arrData);
		}

		return array('selectedOption' => $id);
	}

	/**
	 * Adds a new option values to the database
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return array global variables to be set
	 */
	function addOptionValue($get) {

		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_values', $get, $optionValues);

		if(strpos($optionValues,'::'))
			$arrOptionValues = explode('::', $optionValues);
		else
			$arrOptionValues[] = $optionValues;

		$newId = amDB::getNextAutoValue(TABLE_PRODUCTS_OPTIONS_VALUES,'products_options_values_id');

		foreach($arrOptionValues as $optionValue) {

			list($langId,$name) = explode(':',$optionValue);

			if ($newId == 0) {
			  $newId = 1;
			}

			$ovData = array (
				'products_options_values_id' => $newId,
				'language_id' => amDB::input($langId),
				'products_options_values_name' => amDB::input($name)
			);

			amDB::perform(TABLE_PRODUCTS_OPTIONS_VALUES,$ovData);
		}

		$ov2oData = array(
				'products_options_id' => $optionId,
				'products_options_values_id' => $newId
			);

		amDB::perform(TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS,$ov2oData);

		return array('selectedOption' => $optionId, 'selectedOptionValue' => $newId);
	}

	/**
	 * Sets the selected box id
	 * @param $get $_GET
	 * @return array global variables to be set
	 */
	function updateNewOptionValue($get) {
		$this->getAndPrepare('option_id', $get, $selectedOption);
		return array('selectedOption' => $selectedOption);
	}

	//---------------------------------------------------------------------------------------------- Template page actions

	/**
	 * Takes a variable and prepares it for db
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $strIndex string array index
	 * @param $array array array
	 * @param Variable to set PASSED BY REF
	 * @return void
	 */
	function getAndPrepare($strIndex,$array, &$variable) {
		$variable = amDB::input($array[$strIndex]);
	}

	/**
	 * get all templates and their options and option values
	 * @access protected
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array
	 */
	function getAllTemplatesAndAttributes() {
		if(0 === count($this->arrAllTemplatesAndAttributes)) {


			// changes by mytool
			$query = "select * from ".AM_TABLE_TEMPLATES." at left join ".AM_TABLE_ATTRIBUTES_TO_TEMPLATES." aa2t using(template_id) order by at.template_id";
			if (AM_USE_SORT_ORDER) {
				$query .= ", aa2t.". AM_FIELD_OPTION_VALUE_SORT_ORDER ;
			}

			$ref = amDB::getAll($query);//, aa2t.options_id , aa2t.option_values_id
			// EOF changes by mytool

			$templateID = null;
			foreach($ref as $res) {
				if($templateID != $res['template_id']) {
					$templateID = $res['template_id'];
					$this->arrAllTemplatesAndAttributes[$templateID]['name'] = $res['template_name'];
				}
				$this->arrAllTemplatesAndAttributes[$templateID][$res['options_id']][] = $res['option_values_id'];

				if (AM_USE_SORT_ORDER) {
          			$this->arrAllTemplatesAndAttributes[$templateID]['sortOrder'][$res['option_values_id']] = $res[AM_FIELD_OPTION_VALUE_SORT_ORDER];
  				}
  				// Added by Red Earth Design, Inc. to populate price and prefix
  				$this->arrAllTemplatesAndAttributes[$templateID]['price_prefix'][$res['option_values_id']] = $res['price_prefix'];
  				$this->arrAllTemplatesAndAttributes[$templateID]['options_values_price'][$res['option_values_id']] = $res['options_values_price'];

                if (AM_USE_MPW) {
                  $this->arrAllTemplatesAndAttributes[$templateID]['weight_prefix'][$res['option_values_id']] = $res['price_weight'];
                  $this->arrAllTemplatesAndAttributes[$templateID]['options_values_weight'][$res['option_values_id']] = $res['options_values_weight'];
                }
           }
		}
		return $this->arrAllTemplatesAndAttributes;
	}

	/**
	 * Build the templates drop down box
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array
	 */
	function buildAllTemplatesDropDown($order_by='id') {
		$allTemplates = $this->getAllTemplatesAndAttributes();
		$returnArray = array(array('id' => '0', 'text' => AM_AJAX_TEMPLATES));

		foreach ($allTemplates as $templateID => $values) {
			$returnArray[] = array('id' => $templateID, 'text' => $values['name']);
		}

		// Sort ABC if required
		if($order_by=='abc'){
			for($z1=0;$z1<count($returnArray);$z1++){
				$last_z2=-1;
				foreach($returnArray as $z2 => $values) {
					if(($last_z2>0)){
						if(strcmp($returnArray[$last_z2]['text'],$returnArray[$z2]['text'])>0){
							$tempArr=$returnArray[$last_z2];
							$returnArray[$last_z2]=$returnArray[$z2];
							$returnArray[$z2]=$tempArr;
							$fch=true;
						}
					}
					$last_z2=$z2;
				}
			}
		}

		return $returnArray;
	}

	/**
	 * Deletes a given template
	 * @param get $_GET
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return void
	 */
	function deleteTemplate($get) {
		$this->getAndPrepare('template_id',$get,$templateId);
		amDB::query("delete from ".AM_TABLE_TEMPLATES." where template_id = '$templateId'");
		amDB::query("delete from ".AM_TABLE_ATTRIBUTES_TO_TEMPLATES." where template_id = '$templateId'");
	}

	/**
	 * Saves the current products attributes as a template
	 * @param $get $_GET
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array selected template
	 */
	function saveTemplate($get) {
		$this->getAndPrepare('template_name', $get, $templateName);
		$this->getAndPrepare('new_template_id', $get, $newTemplateId);



		$allProdOptionsAndValues = $this->getAllProductOptionsAndValues();

		if(strpos($templateName,':')) {
			$templateNameBits = explode(':',$templateName);
			$templateName = $templateNameBits[1];
		}

		if(0 !== count($allProdOptionsAndValues)) {
			if(0 != $newTemplateId) {
				amDB::query("delete from ".AM_TABLE_ATTRIBUTES_TO_TEMPLATES." where template_id='$newTemplateId'");
			}
			else {
				$data = array(
					'template_name' => $templateName
				);
				amDB::perform(AM_TABLE_TEMPLATES,$data);
				$newTemplateId = amDB::insertId();
			}
  			//echo '<br><br>Array ALLPRODOPTSAVALS:: <br><br>';
    		//print_r($allProdOptionsAndValues);

			foreach($allProdOptionsAndValues as $optionId => $values) {
				if(is_array($values['values'])) {
					foreach ($values['values'] as $optionValuesId => $allOptionValues){
						if (!AM_USE_SORT_ORDER) {
  							$data = array(
  								'template_id' => $newTemplateId,
  								'options_id' => $optionId,
  								'option_values_id' => $optionValuesId,
  								'price_prefix' => $values['values'][$optionValuesId]['prefix'],
  								'options_values_price' => $values['values'][$optionValuesId]['price']
                            );
  						} else {
	  						$data = array(
  								'template_id' => $newTemplateId,
  								'options_id' => $optionId,
  								'option_values_id' => $optionValuesId,
  								'price_prefix' => $values['values'][$optionValuesId]['prefix'],
  								'options_values_price' => $values['values'][$optionValuesId]['price'],
  								'products_options_sort_order' => $values['values'][$optionValuesId]['sortOrder']
  							);
  						}

                        if (AM_USE_MPW) {
                          $data['weight_prefix'] = $values['values'][$optionValuesId]['weight_prefix'];
                          $data['options_values_weight'] = $values['values'][$optionValuesId]['weight'];
                        }
  						//echo '<br><br>Array DATA:: <br><br>';
  						//print_r($data);
						amDB::perform(AM_TABLE_ATTRIBUTES_TO_TEMPLATES,$data);
					}
				}
			}
		}
		return array('selectedTemplate' => $newTemplateId);
	}

	/**
	 * renames the specified template
	 * @param $get $_GET
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array renamed template
	 */
	function renameTemplate($get) {

		$this->getAndPrepare('template_name', $get, $templateName);
		$this->getAndPrepare('template_id', $get, $templateId);

		if(strpos($templateName,':')) {
			$templateNameBits = explode(':',$templateName);
			$templateName = $templateNameBits[1];
		}
		$data = array(
			'template_name' => $templateName
		);

		amDB::perform(AM_TABLE_TEMPLATES,$data,'update',"template_id = '$templateId'");

		return array('selectedTemplate' => $templateId);
	}

	/**
	 * Loads the selected template
	 * @param $get $_GET
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array selected template
	 */
	function loadTemplate($get) {

		$this->getAndPrepare('template_id', $get, $templateId);

		$allProductsOptionsAndValues = $this->getAllProductOptionsAndValues();

		// used for checking the option still actualy exists in the database
		$allOptions = array_keys($this->getAllOptions());
		$allOptionValues = array_keys($this->getAllOptionValues());

		// first delete all the products existing options
		foreach($allProductsOptionsAndValues as $optionId => $dontNeed)
			$this->removeOptionFromProduct(array('option_id' => $optionId));

		// now add the ones in the template
		$allTemplatesAttributes = $this->getAllTemplatesAndAttributes();
		//echo '<br><br>Array ALLTEMPANDATTRIBS:: <br><br>';
  		//print_r($allTemplatesAttributes);

		$actionTaken = false;

		if(is_array($allTemplatesAttributes[$templateId])) {
			foreach ($allTemplatesAttributes[$templateId] as $optionsId => $values) {
				// check that the option id in the template is still in the database
				if(in_array($optionsId,$allOptions)) {
					if(is_array($values)) {
						foreach($values as $optionValuesId) {

							// check that the option values id still exists in the database
							if(in_array($optionValuesId,$allOptionValues)) {
							  if (!AM_USE_SORT_ORDER && !AM_USE_QT_PRO) {
  								$this->addAttributeToProduct(
  									array(
  										'option_id' => $optionsId,
  										'option_value_id' => $optionValuesId,
  										'price' => $allTemplatesAttributes[$templateId]['options_values_price'][$optionValuesId],
  										'prefix' => $allTemplatesAttributes[$templateId]['price_prefix'][$optionValuesId],
                      'weight' => $allTemplatesAttributes[$templateId]['options_values_weight'][$optionValuesId],
                      'weight_prefix' => $allTemplatesAttributes[$templateId]['weight_prefix'][$optionValuesId]
  									)
  								);
  							}
								if (AM_USE_SORT_ORDER && !AM_USE_QT_PRO) {
//								  echo 'TempID '.$templateId . ' OPTvalID ' . $optionValuesId . ' ';
									$this->addAttributeToProduct(
									  array(
  										'option_id' => $optionsId,
  										'option_value_id' => $optionValuesId,
  										'price' => $allTemplatesAttributes[$templateId]['options_values_price'][$optionValuesId],
  										'prefix' => $allTemplatesAttributes[$templateId]['price_prefix'][$optionValuesId],
									    'sortOrder' => $allTemplatesAttributes[$templateId]['sortOrder'][$optionValuesId],
                      'weight' => $allTemplatesAttributes[$templateId]['options_values_weight'][$optionValuesId],
                      'weight_prefix' => $allTemplatesAttributes[$templateId]['weight_prefix'][$optionValuesId],
									  )
									);
								}
								if (AM_USE_QT_PRO && !AM_USE_SORT_ORDER) {
									$this->addAttributeToProduct(
									  array(
  										'option_id' => $optionsId,
  										'option_value_id' => $optionValuesId,
  										'price' => $allTemplatesAttributes[$templateId]['options_values_price'][$optionValuesId],
  										'prefix' => $allTemplatesAttributes[$templateId]['price_prefix'][$optionValuesId],
									    'stockTracking' => '0',
                      'weight' => $allTemplatesAttributes[$templateId]['options_values_weight'][$optionValuesId],
                      'weight_prefix' => $allTemplatesAttributes[$templateId]['weight_prefix'][$optionValuesId]
									  )
									);
								}
								if (AM_USE_QT_PRO && AM_USE_SORT_ORDER) {
									$this->addAttributeToProduct(
									  array(
  										'option_id' => $optionsId,
  										'option_value_id' => $optionValuesId,
  										'price' => $allTemplatesAttributes[$templateId]['options_values_price'][$optionValuesId],
  										'prefix' => $allTemplatesAttributes[$templateId]['price_prefix'][$optionValuesId],
									    'sortOrder' => $allTemplatesAttributes[$templateId]['sortOrder'][$optionValuesId],
									    'stockTracking' => '0',
                      'weight' => $allTemplatesAttributes[$templateId]['options_values_weight'][$optionValuesId],
                      'weight_prefix' => $allTemplatesAttributes[$templateId]['weight_prefix'][$optionValuesId]
									  )
									);
								}
								$actionTaken = true;
							}
							else {
								// the option value no longer exists in the databse, delete if from all templates
								amDB::query("delete from ".AM_TABLE_ATTRIBUTES_TO_TEMPLATES." where  option_values_id = '$optionValuesId'");
							}
						}
					}
				}
				else {
					// the option no longer exists in the databse, delete it from all templates
					amDB::query("delete from ".AM_TABLE_ATTRIBUTES_TO_TEMPLATES." where  options_id = '$optionsId'");
				}
			}
		}
		// if something has been loaded reset the options and values
		if($actionTaken)
			$this->getAllProductOptionsAndValues(true);
		// otherwise nothing in the template exists anymore so just restore the original values, no harm done
		else
			$this->arrAllProductOptionsAndValues = $allProductsOptionsAndValues;

		return array('selectedTemplate' => $templateId);
	}

	//---------------------------------------------------------------------------------------------- Drop downs

	/**
	 * takes an array of key => value and formats them for the tep_draw_pull_down function
	 * @access private
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $array Array key value pair to be formated
	 * @return array global variables to be set
	 */
	function formatArrayForDropDown($array) {
		$arrReturn = array();
		foreach($array as $key => $value)
			$arrReturn[] = array('id' => $key, 'text' => $value);
		if(0 === count($arrReturn))
			return array(array('id' => '0', 'text' => '----'));

		return $arrReturn;
	}

	/**
	 * Builds an array for a drop down of available options
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $subtract bol - if true it will subtract the options that are already assigned to the product
	 * @return array formated for the osc dropdown box function array(array('id'=>$key,'text'=>$value))
	 */
	function buildOptionDropDown($subtract = true) {

		$allOptionsAndValues = $this->getAllOptionsAndValues();
		$allProductsOptionsAndValues = $this->getAllProductOptionsAndValues();

		$returnArray = array();

		foreach($allOptionsAndValues as $optionId => $optionValues)
			$returnArray[$optionId] = $optionValues['name'];

		// remove any already assigned
		if(true === $subtract) {

			if(0 !== count($allProductsOptionsAndValues)){
				// get all of the option ids from the return array that arn't already assigned to the product
				$nonAssignedIds = array_diff(array_keys($returnArray), array_keys($this->getAllProductOptionsAndValues()));

				$tRetrurnArray = $returnArray;

				$returnArray = array();

				// rebuild the array
				if(is_array($nonAssignedIds))
					foreach($nonAssignedIds as $id)
						$returnArray[$id] = $tRetrurnArray[$id];
			}
		}

		/**
		 * Sort the keys of the array alpha
		 * @todo make it case insensitive
		 */
		if(1 == AM_DEFAULT_SORT_ORDER)
			asort($returnArray);

		return $this->formatArrayForDropDown($returnArray);
	}

	/**
	 * Builds an array for a drop down of available option values
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $optionId int - if defined, will limit the option values to only ones that are below an option. Otherwise it will return all
	 * @param $subtract bol - if true it will subtract option values that are already assigned to this product with this option
	 * @return array formated for the osc dropdown box function array(array('id'=>$key,'text'=>$value))
	 */
	function buildOptionValueDropDown($optionId = null, $subtract = true) {

		$allOptionsAndValues = $this->getAllOptionsAndValues();

		$returnArray = array();

		// get all the values
		if(null === $optionId) {
			foreach($allOptionsAndValues as $option)
				if(is_array($option['values']))
					foreach($option['values'] as $optionValueId => $optionValueText)
						$returnArray[$optionValueId] = $optionValueText;
		}
		// just get the values for the specified option id
		else {
			if(array_key_exists($optionId,$allOptionsAndValues))
				if(is_array($allOptionsAndValues[$optionId]['values']))
					foreach($allOptionsAndValues[$optionId]['values'] as $optionValueId => $optionValueText)
						$returnArray[$optionValueId] = $optionValueText;
		}

		// get rid of any already specified
		if(true === $subtract) {

			$allProductsOptionsAndValues = $this->getAllProductOptionsAndValues();

			// get all of the values
			if(null === $optionId) {
				$tAll = array();

				foreach($allProductsOptionsAndValues as $optionId => $details)
					if(is_array($details['values']))
						foreach($details['values'] as $optionValueId => $optionValueText)
							if(!array_key_exists($optionValueId,$tAll)) // stop duplicates - there shouldn't be any, but you never know
								$tAll[$optionValueId] = $optionValueText;

				$allProductsOptionsAndValues = $tAll;
			}
			// if an option id is specified only return the values for that option id to compare
			else {
				$allProductsOptionsAndValues = $allProductsOptionsAndValues[$optionId]['values'];
			}

			// make sure that the product actually has one of the values for the current option to subtract, if not do
			if(0 !== count($allProductsOptionsAndValues)){

				// get all of the option value ids from the return array that arn't already assigned to the product
				$nonAssignedIds = array_diff(array_keys($returnArray),array_keys($allProductsOptionsAndValues));

				$tRetrurnArray = $returnArray;

				$returnArray = array();

				// rebuild the array
				if(is_array($nonAssignedIds))
					foreach($nonAssignedIds as $id)
						$returnArray[$id] = $tRetrurnArray[$id];
			}
		}

		/**
		 * Sort the keys of the array alpha
		 * @todo make it case insensitive
		 */
		if(1 == AM_DEFAULT_SORT_ORDER)
			asort($returnArray);

		return $this->formatArrayForDropDown($returnArray);
	}

	//---------------------------------------------------------------------------------------------- page action execution

	/**
	 * Registers a page action
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $strAction string action
	 * @param $strFunction string function name
	 * @return void
	 */
	function registerPageAction($strAction,$strFunction) {
		$this->arrPageActions[$strAction] = $strFunction;
	}

	/**
	 * Unregisters a page action
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $strAction string page action to be unregistered
	 * @return void
	 */
	function unregisterPageAction($strAction) {
		unset($this->arrPageActions[$strAction]);
	}

	/**
	 * Executes a page action
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return array global variables to be set
	 */
	function executePageAction($get) {
		$results = array();
		if(array_key_exists(AM_ACTION_GET_VARIABLE,$get)) {
			$actionKey = $get[AM_ACTION_GET_VARIABLE];
			if(array_key_exists($actionKey,$this->arrPageActions)){
				$functionName = $this->arrPageActions[$actionKey];
				if(method_exists($this,$functionName)) {
					$results = $this->$functionName($get);
				}
			}
		}
		return $results;
	}

	//---------------------------------------------------------------------------------------------- misc

	/**
	 * Nothing todo with the script just outputs stuff to browser used for debelopment
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return void
	 */
	function debugOutput($ent) {
		echo (is_array($ent) || is_object($ent)) ? '<pre style="text-align:left">'.print_r($ent, true).'</pre>' : $ent;
	}



}






?>