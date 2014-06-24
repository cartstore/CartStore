<?php
/*
  $Id: firstpluszones.php, v 1.00 1/3/5 $
  based upon
  
  Stuart Lawrence, www.Russianquarter.co.uk - www.dreamtimebooks.co.uk 
 
  osCommerce, Open Source E-Commerce Solutions

  Copyright (c) 2003 www.isii.co.uk

  Released under the GNU General Public License
*/

class firstpluszones {
    var $code, $title, $description, $icon, $enabled, $num_zones;

// class constructor
    function firstpluszones() {
     global $order;
      $this->code = 'firstpluszones';
      $this->title = MODULE_SHIPPING_FIRSTPLUSZONES_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FIRSTPLUSZONES_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_FIRSTPLUSZONES_SORT_ORDER;
      $this->tax_class = MODULE_SHIPPING_FIRSTPLUSZONES_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_FIRSTPLUSZONES_STATUS == 'True') ? true : false);

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
      $this->num_zones = 4;
    }
  
// class methods
    function quote($method = '') {
      global $cart, $order, $shipping_num_boxes, $shipping_weight;

      $dest_country = $order->delivery['country']['iso_code_2'];
      $dest_zone = 0;
      $error1 = false;
      $error2 = false;
      
      $order_total = $cart->count_contents();

      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant(MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_COUNTRIES_ . $i);
        $country_zones = split("[,]", $countries_table);
        if (in_array($dest_country, $country_zones)) {
          $dest_zone = $i;
          break;
        }
      }
	   //NO specified country (or *) then use this zone for all shipping rates
      if ($dest_zone == 0) {
		for ($i=1; $i<=$this->num_zones; $i++) {
		  $countries_table = constant(MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_COUNTRIES_ . $i);
		  if ($countries_table == '' or $countries_table == '*') {
		    $dest_zone = $i;
		    break;
		  }
		}
	  }
      if ($dest_zone == 0) {
        $error1 = true;
      } else {
        $table_cost_first = -1;
        
        $table_cost_first = constant(MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_FIRST_ . $dest_zone);
		$table_cost_additional = constant(MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_ADDITIONAL_ . $dest_zone);

        if ($table_cost_first == -1) {
          $error2 = true;
        } else {
           $shipping = ($order_total < 2) ?  $table_cost_first : ($table_cost_first + (($order_total - 1) * $table_cost_additional));           
        }
      }
	  
	  //extra
	  
			$cats_array = explode(',',MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_CATEGORIES);
			$cat_names = '';
			
			for($i=0, $x=sizeof($cats_array); $i<$x; $i++){
			     $cats_array[$i] = (int)$cats_array[$i];
           $cat_names .= tep_get_categories_name($cats_array[$i]).', ';
			}
			
			$cat_names = substr($cat_names, 0,-2);

			 $pID_list = $cart->get_product_id_list();

			 $pID_list = explode(',',$pID_list);
				for($i=0, $x=sizeof($pID_list); $i<$x; $i++){
				     $pID_list[$i] = (int)$pID_list[$i];
				}
				$pID_list = implode(',',$pID_list);

			 if (MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ALL_OR_ONE == 'One'){
			      $products = $cart->get_products();
						for($i=0, $x=sizeof($products); $i<$x; $i++){
										$check_query = tep_db_query('select * from '.TABLE_PRODUCTS_TO_CATEGORIES.' where categories_id in ('.implode(',',$cats_array).') and products_id="'.(int)$products[$i]['id'].'"');
										if (tep_db_num_rows($check_query))
										  $enable_rates = 1;
						}
			 } elseif (MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ALL_OR_ONE == 'All'){
					 $count = 0;
					for($i=0, $x=sizeof($cats_array); $i<$x; $i++){
										$check_query = tep_db_query('select * from '.TABLE_PRODUCTS_TO_CATEGORIES.' where categories_id="'.$cats_array[$i].'" and products_id in ('.$pID_list.')');
										if (tep_db_num_rows($check_query))
												$count++;
					}
					if ($count == sizeof($cats_array))
										  $enable_rates = 1;
			 } else {
			   $this->enabled = false;
			   return false;
			 }

			 if ( MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ONLY_OR_ANY == 'Only' ){
					$check_query = tep_db_query('select * from '.TABLE_PRODUCTS_TO_CATEGORIES.' where categories_id not in ('.MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_CATEGORIES.') and products_id in ('.$pID_list.')');
		      if (tep_db_num_rows($check_query))
										  $enable_rates = 0;
			 }
		 	  
	  //end extra
	 if($enable_rates==1){
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_FIRSTPLUSZONES_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => sprintf(MODULE_SHIPPING_FIRSTPLUSZONES_TEXT_WAY, $table_cost_first, $table_cost_additional),
                                                      'cost' => $shipping + constant(MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_HANDLING_ . $dest_zone))));             
}
      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error1 == true) $this->quotes['error'] = MODULE_SHIPPING_FIRSTPLUSZONES_ERROR1;
      if ($error2 == true) $this->quotes['error'] = MODULE_SHIPPING_FIRSTPLUSZONES_ERROR2;

      return $this->quotes;
    }
    
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FIRSTPLUSZONES_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Zones Method', 'MODULE_SHIPPING_FIRSTPLUSZONES_STATUS', 'True', 'Do you want to offer zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_FIRSTPLUSZONES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_FIRSTPLUSZONES_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
	  
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Categories List', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_CATEGORIES', '', 'For what categories do you want to offer shipping?<br />NOTE! not recurcive - select all subcategories if you need it.', '6', '0', 'tep_cfg_show_multicategories', 'tep_cfg_select_multicategories(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('All or One', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ALL_OR_ONE', 'All', 'Do you want to offer a shipping for orders with products from all mentioned categories, or with at least from one of them?', '6', '0', 'tep_cfg_select_option(array(\'All\', \'One\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Only or Any', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ONLY_OR_ANY', 'Only', 'Do you want to offer a shipping for orders with products only from mentioned categories, or with products from any categories (including mentioned)?', '6', '0', 'tep_cfg_select_option(array(\'Only\', \'Any\'), ', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
        $default_countries = '';
        if ($i == 1) {
          $default_countries = 'GB';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." First Item', 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_FIRST_" . $i ."', '3.50', 'Cost for First Item', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Additional Items', 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_ADDITIONAL_" . $i ."', '2.00', 'Cost for Additional Items', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling', 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_HANDLING_" . $i."', '0', 'Handling Fee', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_FIRSTPLUSZONES_STATUS', 'MODULE_SHIPPING_FIRSTPLUSZONES_TAX_CLASS', 'MODULE_SHIPPING_FIRSTPLUSZONES_SORT_ORDER', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_CATEGORIES', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ALL_OR_ONE', 'MODULE_SHIPPING_FIRSTPLUSZONES_PER_CATS_ONLY_OR_ANY');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_COUNTRIES_' . $i;
        $keys[] = 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_FIRST_' . $i;
        $keys[] = 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_ADDITIONAL_' . $i;
        $keys[] = 'MODULE_SHIPPING_FIRSTPLUSZONES_ZONES_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>