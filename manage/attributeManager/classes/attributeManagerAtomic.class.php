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

class attributeManagerAtomic extends attributeManager {
	
	/**
	 * Holder for a reference to the session variable for storing temp data
	 * @access private
	 */
	var $arrSessionVar = array();
	
	/**
	 * __constrct - Assigns the session variable and calls the parent construct registers page actions
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $arrSessionVar array - passed by Ref
	 * @return void
	 */
	function attributeManagerAtomic(&$arrSessionVar) {
		
		parent::attributeManager();
		$this->arrSessionVar = &$arrSessionVar;
		
		$this->registerPageAction('addAttributeToProduct','addAttributeToProduct');
		$this->registerPageAction('addOptionValueToProduct','addOptionValueToProduct');
		$this->registerPageAction('addNewOptionValueToProduct','addNewOptionValueToProduct');
		$this->registerPageAction('removeOptionFromProduct','removeOptionFromProduct');
		$this->registerPageAction('removeOptionValueFromProduct','removeOptionValueFromProduct');
		// QT Pro Plugin
		$this->registerPageAction('removeStockOptionValueFromProduct','removeStockOptionValueFromProduct');
		$this->registerPageAction('addStockToProduct','addStockToProduct');
        $this->registerPageAction('updateProductStockQuantity','updateProductStockQuantity');
		// QT Pro Plugin
		$this->registerPageAction('update','update');
		if(AM_USE_SORT_ORDER) {
			$this->registerPageAction('moveOption','moveOption');
			$this->registerPageAction('moveOptionDown','moveOptionDown');
			$this->registerPageAction('moveOptionValue','moveOptionValue');
			$this->registerPageAction('moveOptionValueDown','moveOptionValueDown');
		}

	}
	
	//----------------------------------------------- page actions
	
	/**
	 * Adds the selected attribute to the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
function addAttributeToProduct($get) {
  if(isset($get['option_id']) === true) {
    $this->getAndPrepare('option_id', $get, $getArray['option_id']);
  }

  if(isset($get['option_value_id']) === true) {
    $this->getAndPrepare('option_value_id', $get, $getArray['option_value_id']);
  }

  if(isset($get['price']) === true) {
    $this->getAndPrepare('price', $get, $getArray['price']);
  }


  if(isset($get['prefix']) === true) {
    $this->getAndPrepare('prefix', $get, $getArray['prefix']);
  }

  if(isset($get['sortOrder']) === true  ) {
    $this->getAndPrepare('sortOrder', $get, $getArray['sortOrder']);
  } else {
    $getArray['sortOrder'] = -1;
  }
  
  if (AM_USE_MPW) {
    if(isset($get['weight']) === true) {
      $this->getAndPrepare('weight', $get, $getArray['weight']);
    }

    if(isset($get['weight_prefix']) === true) {
      $this->getAndPrepare('weight_prefix', $get, $getArray['weight_prefix']);
    }
  }

  //
  
  if((empty($getArray['price']))||($getArray['price']=='0')){
	$getArray['price']='0.0000';
  }else{
	if((empty($getArray['prefix']))||($getArray['prefix']==' ')){
		$getArray['prefix']='+';
	}
  }

  if(empty($getArray['prefix'])){
	$getArray['prefix']=' ';
  }
  
  $getArray['price']=sprintf("%01.4f", $getArray['price']);
  
  //
  
  if (AM_USE_MPW) {
    if((empty($getArray['weight']))||($getArray['weight']=='0')){
      $getArray['weight']='0.000';
    }else{
      if((empty($getArray['weight_prefix']))||($getArray['weight_prefix']==' ')){
        $getArray['weight_prefix']='+';
      }
    }

    if(empty($getArray['weight_prefix'])){
      $getArray['weight_prefix']=' ';
    }
    
    $getArray['weight']=sprintf("%01.3f", $getArray['weight']);
  }

//	echo '<br><br>Array arrSessionVar:: <br><br>';
//	print_r($getArray);

			// changes by mytool
			// get highest sort order value
			
	if (AM_USE_SORT_ORDER && $getArray['sortOrder'] == -1) {
			$insertIndex = -1;
			
			$result = $this->getSortedProductAttributes();
			
			// search for the current Sort Order where the new value needs to be added
			$i = -1;
			while ( list($key, $val) = each($result) ) {
   				$i++;
   				if( $key == $getArray['option_id'] ){
   					$insertIndex = $key;
   				}
   			}

			// if InsertIndex is still -1 then this is a new option and will be added at the end
			if($insertIndex > -1){
			
				$last = end( $result[$insertIndex]['values'] ) ;
	   			$getArray['sortOrder'] = (int)$last['sortOrder'] + 1;
			} else {
				$lastrow = end( $result ) ;
				$last = $lastrow['values'];
				$lastv = end( $last ) ;
	   			$getArray['sortOrder'] = (int)$lastv['sortOrder'] + 1;
			}
			// EO mytool
		}

  	$this->arrSessionVar[] = $getArray;
  
	}

	
	/**
	 * Adds an existing option value to a product
	 * @see addAttributeToProduct()
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addOptionValueToProduct($get) {
		$this->addAttributeToProduct($get);
	}
	
	/**
	 * Adds a new option value to the session then to the product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addNewOptionValueToProduct($get) {
		$returnInfo = $this->addOptionValue($get);
		$get['option_value_id'] = $returnInfo['selectedOptionValue'];
		$this->addAttributeToProduct($get);
		return false;
	}

	/**
	 * Removes a specific option and its option values from the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		foreach($this->arrSessionVar as $id => $res) 
			if(($res['option_id'] == $optionId)) 
				unset($this->arrSessionVar[$id]);
	}
	
	/**
	 * Removes a specific option value from a the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionValueFromProduct($get) {
		
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);

		foreach($this->arrSessionVar as $id => $res) {
			if(($res['option_id'] == $optionId) && ($res['option_value_id'] == $optionValueId)){
				unset($this->arrSessionVar[$id]);
			}
		}
	}
// QT pro
	/**
	 * Updates the quantity on the products stock table
	 * @access public
	 * @author Phocea
	 * @param $get $_GET
	 * @return void
	 */
    function addStockToProduct($get) {
      //customprompt();
      $this->getAndPrepare('stockQuantity',$get,$stockQuantity);
      //$this->getAndPrepare('option_id', $get, $optionId);
      //$this->getAndPrepare('option_value_id', $get, $optionValueId);
      //$this->getAndPrepare('price', $get, $price);
      //$this->getAndPrepare('prefix', $get, $prefix);
      //$this->getAndPrepare('sortOrder', $get, $sortOrder);
      
      $this->arrSessionVar[] = array(
      'products_stock_quantity' => $productStockQuantity
      );

    }

// QT pro
		
	/**
	 * Updates the price and prefix in the products attribute table
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function update($get) {
          if(isset($get['option_id']) === true) {
            $this->getAndPrepare('option_id', $get, $getArray['option_id']);
          }
  
          if(isset($get['option_value_id']) === true) {
            $this->getAndPrepare('option_value_id', $get, $getArray['option_value_id']);
          }
  
          if(isset($get['price']) === true) {
            $this->getAndPrepare('price', $get, $getArray['price']);
          }
  
          if(isset($get['prefix']) === true) {
            $this->getAndPrepare('prefix', $get, $getArray['prefix']);
          }
  
          if(isset($get['sortOrder']) === true) {
            $this->getAndPrepare('sortOrder', $get, $getArray['sortOrder']);
          }
		  
		  //
		  if((empty($getArray['price']))||($getArray['price']=='0')){
			$getArray['price']='0.0000';
		  }else{
			if((empty($getArray['prefix']))||($getArray['prefix']==' ')){
				$getArray['prefix']='+';
			}
		  }

		  $getArray['price']=sprintf("%01.4f", $getArray['price']);
  
          //
          if (AM_USE_MPW) {
            if(isset($get['weight']) === true) {
              $this->getAndPrepare('weight', $get, $getArray['weight']);
            }
  
            if(isset($get['weight_prefix']) === true) {
              $this->getAndPrepare('weight_prefix', $get, $getArray['weight_prefix']);
            }

            if((empty($getArray['weight']))||($getArray['weight']=='0')){
              $getArray['weight']='0.000';
            }else{
              if((empty($getArray['weight_prefix']))||($getArray['weight_prefix']==' ')){
                $getArray['weight_prefix']='+';
              }
            }

            $getArray['weight']=sprintf("%01.3f", $getArray['weight']);
          }

		foreach($this->arrSessionVar as $id => $res) {
			if(($res['option_id'] == $getArray['option_id']) && ($res['option_value_id'] == $getArray['option_value_id'])) {
				$debug.=$id."enter\r\n";
				$this->arrSessionVar[$id]['price'] = $getArray['price'];
				$this->arrSessionVar[$id]['prefix'] = $getArray['prefix'];
                
                if (AM_USE_MPW) {
                  $this->arrSessionVar[$id]['weight'] = $getArray['weight'];
                  $this->arrSessionVar[$id]['weight_prefix'] = $getArray['weight_prefix'];
                }
                
				if (AM_USE_SORT_ORDER) {
					$this->arrSessionVar[$id][AM_FIELD_OPTION_VALUE_SORT_ORDER] = $getArray['sortOrder'];
				}
			}
		}
		
	}
	
	//----------------------------------------------- page actions end
	
	/**
	 * Returns all of the products options and values in the session
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array
	 */
	function getAllProductOptionsAndValues($reset = false) {
		if(0 === count($this->arrAllProductOptionsAndValues) || true === $reset) {
			$this->arrAllProductOptionsAndValues = array();
			$allOptionsAndValues = $this->getAllOptionsAndValues();

		// Sort Option Names ABC
		for($z1=0;$z1<count($this->arrSessionVar);$z1++){
			$fch=false;
			$last_z2=-1;
			foreach($this->arrSessionVar as $z2 => $values){
				if($last_z2>-1){
					if(strcmp($allOptionsAndValues[$this->arrSessionVar[$last_z2]['option_id']]['name'],$allOptionsAndValues[$this->arrSessionVar[$z2]['option_id']]['name'])>0){
						$tempArr=$this->arrSessionVar[$last_z2];
						$this->arrSessionVar[$last_z2]=$this->arrSessionVar[$z2];
						$this->arrSessionVar[$z2]=$tempArr;
						$fch=true;
					}
				}
				$last_z2=$z2;
			}
			if(!$fch){
				break;
			}
		}

			$optionsId = null;
			foreach($this->arrSessionVar as $id => $res) {
				
				if($res['option_id'] != $optionsId) {
					$optionsId = $res['option_id'];
					$this->arrAllProductOptionsAndValues[$optionsId]['name'] = $allOptionsAndValues[$optionsId]['name'];
				}
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['name'] = $allOptionsAndValues[$optionsId]['values'][$res['option_value_id']];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['price'] = $res['price'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['prefix'] = $res['prefix'];
                
                if (AM_USE_MPW) {
                  $this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['weight'] = $res['weight'];
                  $this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['weight_prefix'] = $res['weight_prefix'];
                }
                
				if (AM_USE_SORT_ORDER) {
						$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['option_value_id']]['sortOrder'] = $res['sortOrder'];
				}
			}
			
			if (AM_USE_SORT_ORDER) {
				$this->sortProductAttributes();
			}
		}
		return $this->arrAllProductOptionsAndValues;
	}

	function getSortedProductAttributes(){
		return $this->getAllProductOptionsAndValues(true);
	}
	

  	function sortArrSessionVar(){
  	
  		if( count($this->arrSessionVar)){
  
  			$sortArray = $this->arrSessionVar;
  		
    		foreach ( $sortArray as $id => $res) {
    			$newSortArray[$res['sortOrder']] = $res;
    		}
 		
 			ksort($newSortArray, SORT_NUMERIC);
 		
 			$sortArray=$newSortArray;
 		
 			$this->arrSessionVar = $newSortArray;
 		  		
  			return $sortArray;
  		} else{
  			return $this->arrSessionVar;
  		}
  	}


	
	function sortProductAttributes(){
	
		$arrAllProductOptionsAndValues = $this->arrAllProductOptionsAndValues;
		
		$sortedArray = array();
		
		$sorter = -1;
		// reformat the $arrAllProductOptionsAndValues 
		while( list($key, $currentOption) = each($arrAllProductOptionsAndValues) ){
			while( list($currentOptionValuekey, $currentOptionValues) = each($currentOption['values']) ){
					$sorter++;
					
					$sortorder = $currentOptionValues['sortOrder'];
					// just to make shure there is a value in there
					if(! ($sortorder > -1) )$sortorder=$sorter;
					$sortedArray[$sortorder] =  array( 'optionValue-id' => $currentOptionValuekey,
														'optionValue-name' => $currentOptionValues['name'],
														'optionValue-price' => $currentOptionValues['price'],
														'optionValue-prefix' => $currentOptionValues['prefix'],
														'optionValue-sortorder' => $sortorder,
                                                        'optionValue-weight' => $currentOptionValues['weight'],
                                                        'optionValue-weight_prefix' => $currentOptionValues['weight_prefix'],
														'option-key' => $key,
														'option-name' => $currentOption['name']
													);
			}
		}
		
		// now sort by key, because that contains the sortorder
		ksort($sortedArray, SORT_NUMERIC);
		
		reset($sortedArray);
		
		$lastOption = current($sortedArray);
		
		$optionValues = array();
		$finalSortedArray = array();
		
		// now rebuild the $arrAllProductOptionsAndValues
		while( list($key, $currentOption) = each($sortedArray) ){
			if( $lastOption['option-key'] != $currentOption['option-key']){
				$lastOption = $currentOption;
				$optionValues = array();
			}
			$optionValues[$currentOption['optionValue-id']] = array(
																	'name' => $currentOption['optionValue-name'],
																	'price' => $currentOption['optionValue-price'],
																	'prefix' => $currentOption['optionValue-prefix'],
																	'sortOrder' => $currentOption['optionValue-sortorder'],
                                                                    'weight' => $currentOption['optionValue-weight'],
                                                                    'weight_prefix' => $currentOption['optionValue-weight_prefix'],
																);
			$finalSortedArray[ $currentOption['option-key'] ]	= array(  'name' => $currentOption['option-name'],
																			'values' => $optionValues ) ;
		}
		
		$this->arrAllProductOptionsAndValues = $finalSortedArray;
		//return $finalSortedArray;
	}
}
?>
