<?php
/*
  $Id: attributeManagerConfig.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright � 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

require_once('attributeManager/classes/amDB.class.php');
require_once('attributeManager/includes/attributeManagerSessionFunctions.inc.php');

if(file_exists('attributeManager/languages/'.$_SESSION['language'].'/attributeManager.php'))
 include_once('attributeManager/languages/'.$_SESSION['language'].'/attributeManager.php');
else
 include_once('attributeManager/languages/'.'english'.'/attributeManager.php');

class attributeManagerConfig {
	
	var $arrConfig = array();
	
	function attributeManagerConfig() {
		
		
		/**
		 * Default admin interface language id
		 */
		$this->add('AM_DEFAULT_LANGUAGE_ID',$GLOBALS['languages_id']);
		
		/**
		 * Default admin interface template order
		 */
		$this->add('AM_DEFAULT_TEMPLATE_ORDER','123');
		
		/**
		 * Dont update the database untill the untill the end of the product addition process
		 */
		$this->add('AM_ATOMIC_PRODUCT_UPDATES', false);
		
		
		/**
		 * Use attribute templates?
		 * 
		 */
		$this->add('AM_USE_TEMPLATES',true);
		
		
		/**
		 * Template Table names
		 */
		$this->add('AM_TABLE_TEMPLATES','am_templates');
		$this->add('AM_TABLE_ATTRIBUTES_TO_TEMPLATES','am_attributes_to_templates');
		
		
		$this->add('AM_USE_SORT_ORDER' , true);

        /**
         * QT Pro plugin
         */
		$this->add('AM_USE_QT_PRO', true);
        $this->add('AM_DELETE_ZERO_STOCK', true); // if true, deletes options combinations with zero quantity from stock
		
        /**
         * Use More Product Weight plugin? (http://addons.oscommerce.com/info/2706) (added by RusNN)
         */
        $this->add('AM_USE_MPW', false);
        
		/**
		 * Sort order tables
		 */
		$this->add('AM_FIELD_OPTION_SORT_ORDER','products_attributes_sort_order'); // Sort column on Products_options table
		$this->add('AM_FIELD_OPTION_VALUE_SORT_ORDER','products_options_sort_order'); // Sort column on product_attributes table
	
		
		/**
		 * How do sort the drop down lists in the admin - purly asthetic
		 * options
		 * 1 = alpha
		 * 2 = default - by id
		 */
		$this->add('AM_DEFAULT_SORT_ORDER',1);
			
		/**
		 * Password for the session var - doesn't matter what it is. Mix it up if you fee like it :)
		 */
		$this->add('AM_VALID_INCLUDE_PASSWORD','asdfjkasdadfadsff');
		
		/**
		 * Variable names - Shouldn't need editing unless there are conflicts
		 */
		$this->add('AM_SESSION_VAR_NAME','am_session_var'); // main var for atomic
		$this->add('AM_SESSION_CURRENT_LANG_VAR_NAME','am_current_lang_session_var'); // current interface lang
		$this->add('AM_SESSION_CURRENT_TEMPLATE_ORDER','am_current_template_order'); // current template order
		$this->add('AM_SESSION_VALID_INCLUDE','am_valid_include'); // variable set on categories.php to make sure attributeManager.php has been included
		$this->add('AM_SESSION_SORT_ORDER_INSTALL_CHECKED','am_sort_order_checked');
        $this->add('AM_SESSION_MORE_PRODUCT_WEIGHT_INSTALL_CHECKED','am_more_product_weight_checked');
		$this->add('AM_SESSION_TEMPLATES_INSTALL_CHECKED','am_templates_checked');
		$this->add('AM_ACTION_GET_VARIABLE', 'amAction'); // attribute manager get variable name
		$this->add('AM_PAGE_ACTION_NAME','pageAction'); // attribute manager parent page action e.g. new_product
		
		/** 
		 * Install templates if not already done so 
		 */
		$this->installTemplates();
		
        /**
         * Install the sort order tables if they dont already exist
         */
         
        $this->installSortOrder();

		/**
		 * Install the More Product Weight fields if they dont already exist
		 */
		 
		$this->installMoreProductWeight();
	}
	
	function load() {
		if(0 !== count($this->arrConfig))
			foreach($this->arrConfig as $key => $value)
				define($key, $value);
	}
	
	function getValue($key) {
		if(array_key_exists($key, $this->arrConfig))
			return $this->arrConfig[$key];
		return false;
	}
	
	function add($key, $value) {
		$this->arrConfig[$key] = $value;
	}
	
	function installTemplates() {
		if($this->getValue('AM_USE_TEMPLATES') && !amSessionIsRegistered($this->getValue('AM_SESSION_SORT_ORDER_INSTALL_CHECKED')) && !amSessionIsRegistered($this->getValue('AM_SESSION_MORE_PRODUCT_WEIGHT_INSTALL_CHECKED')) ) {
									 
			amDB::query("CREATE TABLE IF NOT EXISTS ".$this->getValue('AM_TABLE_TEMPLATES')." (
					`template_id` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`template_name` VARCHAR( 255 ) NOT NULL
				)"
			);
			amDB::query("CREATE TABLE IF NOT EXISTS ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." (
					`template_id` INT( 5 ) UNSIGNED NOT NULL ,
					`options_id` INT( 5 ) UNSIGNED NOT NULL ,
					`option_values_id` INT( 5 ) UNSIGNED NOT NULL ,
					`price_prefix` char(1) default '+',
					`options_values_price` decimal(15,4) default 0,
					`products_options_sort_order` int default 0,
                    `weight_prefix` char(1) default '+',
                    `options_values_weight` decimal(6,3) default '0.000',
					INDEX ( `template_id` )
				)"
			);
			// Check if the user is updating from the older version
			$install_price_prefix=true;
			$install_options_values_price=true;
			$install_products_options_sort_order=true;
            $install_more_product_weight=true;

			// Fetch database Fields
			$attributeFields = amDB::query("SHOW COLUMNS FROM ". $this->getValue(AM_TABLE_ATTRIBUTES_TO_TEMPLATES));
			while($field = amDB::fetchArray($attributeFields)) 
				$fields[] = $field['Field'];
			
			if( !in_array('price_prefix',$fields) ){
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`price_prefix` char(1) default '+')");
			}
			if( !in_array('options_values_price',$fields) ){
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`options_values_price` decimal(15,4) default 0)");
			}
			if( !in_array('products_options_sort_order',$fields) ){
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`products_options_sort_order` int default 0)");
			}
            if( !in_array('weight_prefix',$fields) ){
              amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`weight_prefix` char(1) default '+')");
            }
            if( !in_array('options_values_weight',$fields) ){
              amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`options_values_weight` decimal(6,3) default '0.000')");
            }

			// register the checked session so that this check is only done once per session
			amSessionRegister('AM_SESSION_TEMPLATES_INSTALL_CHECKED',true);


		}
	}
	
	function installSortOrder() {
	
	
		if($this->getValue('AM_USE_SORT_ORDER') && !amSessionIsRegistered($this->getValue('AM_SESSION_SORT_ORDER_INSTALL_CHECKED'))) {
			
			
			// check that the fields are in the attributes table
			$attributeFields = amDB::query("SHOW COLUMNS FROM ". TABLE_PRODUCTS_ATTRIBUTES);
			while($field = amDB::fetchArray($attributeFields)) 
				$attrfields[] = $field['Field'];
			
			$attributeFields = amDB::query("SHOW COLUMNS FROM ". TABLE_PRODUCTS_OPTIONS);
			while($field = amDB::fetchArray($attributeFields)) 
				$optionsFields[] = $field['Field'];
			
			$attributeFields = amDB::query("SHOW COLUMNS FROM ". $this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES'));
			while($field = amDB::fetchArray($attributeFields)) 
				$soptionsFields[] = $field['Field'];


			$oInstalled = in_array($this->getValue('AM_FIELD_OPTION_SORT_ORDER'),$optionsFields);
			$ovInstalled = in_array($this->getValue('AM_FIELD_OPTION_VALUE_SORT_ORDER'),$attrfields);
			$soInstalled = in_array($this->getValue('AM_FIELD_OPTION_SORT_ORDER'),$soptionsFields);
			
			// if not add them
			if( ! $oInstalled ) 
				amDB::query("ALTER TABLE ".TABLE_PRODUCTS_OPTIONS." ADD COLUMN ".$this->getValue('AM_FIELD_OPTION_SORT_ORDER')." INT UNSIGNED NOT NULL DEFAULT '0'");
			
			if(!$ovInstalled) 
				amDB::query("ALTER TABLE ".TABLE_PRODUCTS_ATTRIBUTES." ADD COLUMN ".$this->getValue('AM_FIELD_OPTION_VALUE_SORT_ORDER')." INT UNSIGNED NOT NULL DEFAULT '0'");
				
			if(!$soInstalled && $this->getValue('AM_USE_SORT_ORDER')) 
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD COLUMN ".$this->getValue('AM_FIELD_OPTION_SORT_ORDER')." INT UNSIGNED NOT NULL DEFAULT '0'");
			
			// now reset all of the sort orders
			if(!$oInstalled || !$ovInstalled) {
				$allAttributes = amDB::getAll("select * from ".TABLE_PRODUCTS_ATTRIBUTES." order by products_id, options_id, options_values_id");
				
				$productId = $optionId = null;
				$oCount = $ovCount = 1;
				
				$updateValues = array();
				if(is_array($allAttributes)) {
					foreach($allAttributes as $attrib) {
						if($productId != $attrib['products_id']) {
							$oCount = $ovCount = 0;
							
						}
						if($optionId != $attrib['options_id']) {
							$oCount++;
							$ovCount = 0;
						}
						
						/** for dev only 
						$updateValues[$attrib['products_attributes_id']]['prdoucts_id'] = $attrib['products_id'];
						$updateValues[$attrib['products_attributes_id']]['options_id'] = $attrib['options_id'];
						$updateValues[$attrib['products_attributes_id']]['options_values_id'] = $attrib['options_values_id'];
						**/
						
						$updateValues[$attrib['products_attributes_id']]['option_sort'] = $oCount;
						$updateValues[$attrib['products_attributes_id']]['option_value_sort'] = ++$ovCount;
	
						
						$productId = $attrib['products_id'];
						$optionId = $attrib['options_id'];
					}

					foreach($updateValues as $attributeId => $sorts) 
						amDB::query("update ".TABLE_PRODUCTS_ATTRIBUTES." set ".$this->getValue('AM_FIELD_OPTION_SORT_ORDER')." = '{$sorts['option_sort']}', ".$this->getValue('AM_FIELD_OPTION_VALUE_SORT_ORDER')." = '{$sorts['option_value_sort']}' where products_attributes_id = '$attributeId' limit 1");
					
				}
				//echo '<pre style="text-align:left">'.print_r($updateValues,true);
			}

		// register the checked session so that this check is only done once per session
		amSessionRegister($this->getValue('AM_SESSION_SORT_ORDER_INSTALL_CHECKED'), true);

		}
		
	}

    function installMoreProductWeight() {
        if($this->getValue('AM_USE_MPW') && !amSessionIsRegistered($this->getValue('AM_SESSION_MORE_PRODUCT_WEIGHT_INSTALL_CHECKED'))) {
            // check that the fields are in the weights table
            $weightFields = amDB::query("SHOW COLUMNS FROM ". TABLE_PRODUCTS_ATTRIBUTES);
            while($field = amDB::fetchArray($weightFields)) 
                $pa_fields[] = $field['Field'];
            
            $weightFields = amDB::query("SHOW COLUMNS FROM ". TABLE_ORDERS_PRODUCTS_ATTRIBUTES);
            while($field = amDB::fetchArray($weightFields)) 
                $opa_Fields[] = $field['Field'];
            
            $weightFields = amDB::query("SHOW COLUMNS FROM ". $this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES'));
            while($field = amDB::fetchArray($weightFields)) 
                $tmpl_Fields[] = $field['Field'];

            $p_Type = '';
            $weightFields = amDB::query("SHOW COLUMNS FROM ". TABLE_PRODUCTS);
            while($field = amDB::fetchArray($weightFields)) {
                if ($field['Field'] == 'products_weight') {
                  $p_Type = $field['Type'];
                  break;
                }
            }

            // if not add them
            if(!in_array('weight_prefix', $pa_fields)) 
                amDB::query("ALTER TABLE ".TABLE_PRODUCTS_ATTRIBUTES." ADD COLUMN `weight_prefix` CHAR (1) NOT NULL");
            if(!in_array('options_values_weight', $pa_fields)) 
                amDB::query("ALTER TABLE ".TABLE_PRODUCTS_ATTRIBUTES." ADD COLUMN `options_values_weight` DECIMAL (6,3) DEFAULT '0.000' NOT NULL");
                
            if(!in_array('weight_prefix', $opa_Fields)) 
                amDB::query("ALTER TABLE ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." ADD COLUMN `weight_prefix` CHAR (1) NOT NULL");
            if(!in_array('options_values_weight', $opa_Fields)) 
                amDB::query("ALTER TABLE ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." ADD COLUMN `options_values_weight` DECIMAL (6,3) DEFAULT '0.000' NOT NULL");
            
            if(!in_array('weight_prefix', $tmpl_Fields) && $this->getValue('AM_USE_SORT_ORDER')) 
                amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD COLUMN `weight_prefix` CHAR (1) NOT NULL default '+'");
            if(!in_array('options_values_weight', $tmpl_Fields) && $this->getValue('AM_USE_SORT_ORDER')) 
                amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD COLUMN `options_values_weight` DECIMAL (6,3) DEFAULT '0.000' NOT NULL");
            
            // change field size of product weight
            if (($p_Type != '') && ($p_Type != strtoupper('DECIMAL(6,3)'))) {
                amDB::query("ALTER TABLE ".TABLE_PRODUCTS." CHANGE `products_weight` `products_weight` DECIMAL(6,3) DEFAULT '0.000' NOT NULL");
            }

            // register the checked session so that this check is only done once per session
            amSessionRegister($this->getValue('AM_SESSION_MORE_PRODUCT_WEIGHT_INSTALL_CHECKED'), true);
        }
    }

}

$config = new attributeManagerConfig();
$config->load();

?>