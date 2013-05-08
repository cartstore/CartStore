<?php
/*
  $Id: attributeManagerUpdateAtomic.inc.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

require_once('attributeManager/classes/attributeManagerConfig.class.php');
require_once('attributeManager/classes/amDB.class.php');
require_once('attributeManager/classes/stopDirectAccess.class.php');


// Check the session var exists
if(is_array(${AM_SESSION_VAR_NAME}) && is_numeric($products_id)){
	foreach(${AM_SESSION_VAR_NAME} as $newAttribute) {
		
		$newAttributeData = array(
			'products_id' => $products_id,
			'options_id' => amDB::input($newAttribute['option_id']),
        		'options_values_id' => amDB::input($newAttribute['option_value_id']),
        		'options_values_price' => amDB::input($newAttribute['price']),
        		'price_prefix' => amDB::input($newAttribute['prefix'])
        	);

            if (AM_USE_MPW) {
              $newAttributeData['options_values_weight'] = amDB::input($newAttribute['weight']);
              $newAttributeData['weight_prefix'] = amDB::input($newAttribute['weight_prefix']);
            }

        	if (AM_USE_SORT_ORDER) {
        		$newAttributeData[AM_FIELD_OPTION_VALUE_SORT_ORDER] = amDB::input($newAttribute['sortOrder']);
        	}
		
		// insert it into the database
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES, $newAttributeData);
	}
	
	/**
	 * Delete the temporary session var
	 */
	amSessionUnregister(AM_SESSION_VAR_NAME);

	/**
	 * remove the direct access authorization so that if the session is hijacked they wont be able
	 * access the attributeManagerFile directly without first going to the product addition page.
	 * If thats not secured then it doesn't really matter what this script does they have compleate access anyway im not at fault
	 */
	stopDirectAccess::deAuthorise(AM_SESSION_VALID_INCLUDE);
}

?>
