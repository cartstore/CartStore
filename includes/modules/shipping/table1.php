<?php
  class table1 {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function table1() {
      global $order, $sendto, $shipping_weight;

      $this->code = 'table1';
      $this->title = MODULE_SHIPPING_TABLE1_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_TABLE1_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_TABLE1_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_TABLE1_TAX_CLASS;
			$this->maxWeight=MODULE_SHIPPING_TABLE1_MAX_WEIGHT;
	    $this->minWeight=MODULE_SHIPPING_TABLE1_MIN_WEIGHT; 
      $this->enabled = ((MODULE_SHIPPING_TABLE1_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_TABLE1_ZONE > 0) ) {
        $check_flag = false;
				$zoneQ=tep_db_query("select entry_country_id from address_book where address_book_id='".$sendto."'");
				$zoneRow=tep_db_fetch_array($zoneQ);
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_TABLE1_ZONE . "' and zone_country_id = '" . $zoneRow['entry_country_id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $order, $cart, $shipping_weight, $shipping_num_boxes, $customer, $delivery, $sendto, $cart;

			
			
	if($dest_zipcode==''){
			  $priAdd_query = tep_db_query("select entry_postcode from address_book where address_book_id = '" . (int)$sendto . "'");
  			$priAdd = tep_db_fetch_array($priAdd_query);
			  $dest_zipcode = $priAdd['entry_postcode'];
			}else{
		    $dest_zipcode = $order->delivery['postcode'];
	    }
		//get the first two characters of the postcode!	
	  $dest_zipcode = substr($dest_zipcode, 0,2);	 
	  //check if their is a digit as the second character.
	  
$patterns[0] = '/0/';
$patterns[1] = '/1/';
$patterns[2] = '/2/';
$patterns[3] = '/3/';
$patterns[4] = '/4/';
$patterns[5] = '/5/';
$patterns[6] = '/6/';
$patterns[7] = '/7/';
$patterns[8] = '/8/';
$patterns[9] = '/9/';
$replacements = '';	   
$dest_zipcode =  preg_replace($patterns, $replacements, $dest_zipcode);	


$disallowed_postcodes= split("[,]", MODULE_SHIPPING_TABLE1_DISALLOWED); 

  $priAdd_query = tep_db_query("select entry_postcode from address_book where address_book_id = '" . (int)$sendto . "'");
  			$priAdd = tep_db_fetch_array($priAdd_query);
			  $dest_zipcode2 = $priAdd['entry_postcode'];
				
$area= substr($dest_zipcode2, 0,4); 

$area= str_replace(' ','',$area);
 $area = strtoupper($area);
if (in_array($area, $disallowed_postcodes)){
//$this->pcodeAllowed=='false';
$block_module = 1;

}
//echo $area;
     /* $dest_zone = 0;
      $error = false;  	 
	  $dest_zipcode=strtoupper($dest_zipcode);	  
	     //  for ($i=1; $i<=$this->num_zones; $i++) {
       $zipcode_table = constant('MODULE_SHIPPING_TABLE1_CODES_' . $i); 
       $zipcode_table = split("[, ]", $zipcode_table);
        if ((in_array($dest_zipcode, $zipcode_table) && $dest_zipcode !='') or (in_array($area, $zipcode_table) && $dest_zipcode !='')) {
         $dest_zone = $i; 
				 $block_module = 1;
         break;
       }
     }*/
     
		 
			
      if (MODULE_SHIPPING_TABLE1_MODE == 'price') {
        $order_total = $cart->show_total();
      } else {
        $order_total = $cart->show_weight();
      }

      $table1_cost = split("[:,]" , MODULE_SHIPPING_TABLE1_COST);
      $size = sizeof($table1_cost);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($order_total <= $table1_cost[$i]) {
          $shipping = $table1_cost[$i+1];
          break;
        }
      }

			if ($shipping==''){
			$shipping = $table1_cost[$size-2];
			}
			
			

      if (MODULE_SHIPPING_TABLE1_MODE == 'weight') {
        $shipping = $shipping * $shipping_num_boxes;
      }
			if($shipping == 0.00){
      $this->quotes = array('id' => $this->code,
                            'module' => 'FREE Standard UK Delivery',
                            'methods' => array(array('id' => $this->code,
                                                     'title' => 'Free Standard UK Delivery',
                                                     'cost' => $shipping + MODULE_SHIPPING_TABLE1_HANDLING)));
			}else{
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_TABLE1_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_TABLE1_TEXT_WAY,
                                                     'cost' => $shipping + MODULE_SHIPPING_TABLE1_HANDLING)));			
			}

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

	    if ($shipping_weight < $this->maxWeight && $shipping_weight >= $this->minWeight && $block_module !=1){
        return $this->quotes;
	    }
			
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE1_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Table Method', 'MODULE_SHIPPING_TABLE1_STATUS', 'True', 'Do you want to offer table1 rate shipping?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_TABLE1_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '2', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_TABLE1_SORT_ORDER', '0', 'Sort order of display.', '6', '3', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_TABLE1_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '4', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Table 1 Title', 'MODULE_SHIPPING_TABLE1_TEXT_TITLE', 'Table 1 Title', 'The text used as the title of this module', '6', '5', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Table', 'MODULE_SHIPPING_TABLE1_COST', '25:8.50,50:5.50,10000:0.00', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', '6', '6', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_TABLE1_HANDLING', '0', 'Handling fee for this shipping method.', '6', '7', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Table Method', 'MODULE_SHIPPING_TABLE1_MODE', 'weight', 'The shipping cost is based on the order total or the total weight of the items ordered.', '6', '8', 'tep_cfg_select_option(array(\'weight\', \'price\'), ', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order min weight', 'MODULE_SHIPPING_TABLE1_MIN_WEIGHT', '2000', 'Minimum weight in g(s) for order', '6', '7', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order max weight', 'MODULE_SHIPPING_TABLE1_MAX_WEIGHT', '2000000000', 'Maximum weight in g(s) for order', '6', '8', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Disallowed Postcodes', 'MODULE_SHIPPING_TABLE1_DISALLOWED', 'IM6, AB30, AB31, AB33, AB34, AB35, AB36, AB37, AB38, AB39, AB3, AB41, AB42, AB43, AB44, AB45, AB51, AB52, AB53, AB54, AB55, AB56, AB63, FK8, FK3, FK17, FK18, FK19, FK20, FK21, IV4, IV5, IV6, IV7, IV8, IV9, IV10, IV11, IV12, IV13, IV14, IV15, IV16, IV17, IV18, IV19, IV20, IV21, IV22, IV23, IV24, IV25, IV26, IV27, IV28, IV29, IV30, IV31, IV32, IV33, IV34, IV35, IV36, IV40, IV41, IV42, IV43, IV44, IV45, IV46, IV47, IV48, IV49, IV51, IV52, IV53, IV54, IV55, IV56, KA27, KA28, KW1, KW2, KW3, KW5, KW6, KW7, KW8, KW9, KW10, KW11, KW12, KW13, KW14, KW15, KW16, KW17, PW20, PW21, PW22, PW23, PW24, PW25, PW26, PW27, PW28, PW29, PW30, PW31, PW32, PW33, PW34, PW35, PW36, PW37, PW38, PW39, PW40, PW41, PW42, PW43, PW44, PW45, PW46, PW47, PW48, PW49, PW60, PW61, PW62, PW63, PW64, PW65, PW66, PW67, PW68, PW69, PW70, PW71, PW72, PW73, PW74, PW75, PW76, PW77, PW78, PH30, PH31, PH32, PH33, PH34, PH35, PH36, PH37, PH38, PH39, PH40, PH41, PH42, PH43, PH44, PH49, PH50, ZE1, ZE2, ZE3, TR21, TR22, TR23, TR24, TR25, BT74, BT92, BT93, BT94', 'Comma separated list of postcodes NOT ALLOWED', '6', '0', now())");      
      
      
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_TABLE1_STATUS', 'MODULE_SHIPPING_TABLE1_TAX_CLASS', 'MODULE_SHIPPING_TABLE1_SORT_ORDER',  'MODULE_SHIPPING_TABLE1_ZONE', 'MODULE_SHIPPING_TABLE1_TEXT_TITLE', 'MODULE_SHIPPING_TABLE1_COST', 'MODULE_SHIPPING_TABLE1_HANDLING', 'MODULE_SHIPPING_TABLE1_MODE', 'MODULE_SHIPPING_TABLE1_MIN_WEIGHT', 'MODULE_SHIPPING_TABLE1_MAX_WEIGHT','MODULE_SHIPPING_TABLE1_DISALLOWED' );
    }
  }
?>