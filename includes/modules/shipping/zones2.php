<?php
class zones2 {
   var $code, $title, $description, $enabled, $num_zones2;

// class constructor
   function zones2() {
     $this->code = 'zones2';
     $this->title = MODULE_SHIPPING_ZONES2_TEXT_TITLE;
     $this->description = MODULE_SHIPPING_ZONES2_TEXT_DESCRIPTION;
     $this->sort_order = MODULE_SHIPPING_ZONES2_SORT_ORDER;
     $this->icon = '';
     $this->tax_class = MODULE_SHIPPING_ZONES2_TAX_CLASS;
		 $this->maxWeight=MODULE_SHIPPING_ZONES2_MAX_WEIGHT;
	    $this->minWeight=MODULE_SHIPPING_ZONES2_MIN_WEIGHT;
     $this->enabled = ((MODULE_SHIPPING_ZONES2_STATUS == 'True') ? true : false);

     // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES2 NEEDED
     $this->num_zones2 = ZONES_MODULE_NUMBER;
   }

// class methods
   function quote($method = '') {
     global $order, $shipping_weight, $shipping_num_boxes, $sendto, $customers_id;

      if ($customers_id == 0) { // PWA customer, address not stored so get country id from PWA order info
          global $order;
          if ($sendto == 1) {
              $address = $order->pwa_label_shipping;
          } else {
              $address = $order->pwa_label_customer;
          }
          $zoneRow['entry_country_id'] = $address['country_id'];
      } else { // normal, non-PWA customer/address
         $zoneQ=tep_db_query("select entry_country_id from address_book where address_book_id='".$sendto."'");
       $zoneRow=tep_db_fetch_array($zoneQ);
      }

		 $countryQ=tep_db_query("select countries_iso_code_2 from countries where countries_id='".$zoneRow['entry_country_id']."'");
 	   $countryRow=tep_db_fetch_array($countryQ);

     $dest_country = $countryRow['countries_iso_code_2'];//$order->delivery['country']['iso_code_2'];
     $dest_zone = 0;
     $error = false;

     for ($i=1; $i<=$this->num_zones2; $i++) {
       $countries_table = constant('MODULE_SHIPPING_ZONES2_COUNTRIES_' . $i);
       $country_zones2 = preg_split("/[,]/", $countries_table);
       if (in_array($dest_country, $country_zones2)) {
         $dest_zone = $i;
         break;
       }
     }
//ECHO $dest_country.'-dfgfdg';exit;
     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $dest_zone = $this->num_zones2;    // the zone is the lastest zone avalaible
     }
     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $error = true;      // this can no more achieve since by default the value is set to the max number of zones
     } else {
       $shipping = -1;
       $zones2_cost = constant('MODULE_SHIPPING_ZONES2_COST_' . $dest_zone);

       $zones2_table = preg_split("/[:,]/" , $zones2_cost);
       $size = sizeof($zones2_table);
       for ($i=0; $i<$size; $i+=2) {
         if ($shipping_weight <= $zones2_table[$i]) {
           $shipping = $zones2_table[$i+1];
           $shipping_method = MODULE_SHIPPING_ZONES2_TEXT_WAY . ' ' . $order->delivery['country']['title'] . ': ';
           if ($shipping_num_boxes > 1) {
             $shipping_method .= $shipping_num_boxes . 'x ';
           }
           $shipping_method .= $shipping_weight . ' ' . MODULE_SHIPPING_ZONES2_TEXT_UNITS;
           break;
         }
       }

       if ($shipping == -1) {
         $shipping_cost = 0;
         $shipping_method = MODULE_SHIPPING_ZONES2_UNDEFINED_RATE;
       } else {
         $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_ZONES2_HANDLING_' . $dest_zone);
       }
     }

     $this->quotes = array('id' => $this->code,
                           'module' => MODULE_SHIPPING_ZONES2_TEXT_TITLE,
                           'methods' => array(array('id' => $this->code,
                                                    'title' => $shipping_method,
                                                    'cost' => $shipping_cost)));

     if ($this->tax_class > 0) {
       $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
     }

     if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

     if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_ZONES2_INVALID_ZONE;

		 if($dest_country !='GB'){
	    if ($shipping_weight < $this->maxWeight && $shipping_weight >= $this->minWeight){
			  //if($shipping_cost > 0){
          return $this->quotes;
				//}
	    }
		 }
   }

   function check() {
     if (!isset($this->_check)) {
       $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES2_STATUS'");
       $this->_check = tep_db_num_rows($check_query);
     }
     return $this->_check;
   }

   // elari - Added to select default country if not in listing
   function install() {
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Zones Method', 'MODULE_SHIPPING_ZONES2_STATUS', 'True', 'Do you want to offer zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_ZONES2_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_ZONES2_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
		 for ($i = 1; $i <= $this->num_zones2; $i++) {
        $default_countries = '';
       if ($i == 1) {
      $default_countries = 'AT,BE,DE,FR,GL,IS,IE,IT,NL,NO,DK,PL,ES,SE,CH,FI,PT,IL,GR,';
         $shipping_table = '10:14.99';
       }
       if ($i == 2) {
         $default_countries = 'CA,US,';
         $shipping_table = '2.0:14.00,2.50:15.00,3.0:16.00,3.50:17.00,4.00:18.00,4.50:19.00,5.0:20.00,5.5:21.00,6.0:22.00,6.50:23.00,7.0:24.00.7.5:25.00,8.0:26.00,8.5:27.00,9.0:28.00,9.5:29.00,10.0:30.00';
       }
       if ($i == $this->num_zones2) {
         $default_countries = 'All Others'; // this must be the last zone
         $shipping_table = '2:500.00,5:500.00,99:500.00';
       }
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_ZONES2_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_ZONES2_COST_" . $i ."', '" . $shipping_table . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_ZONES2_HANDLING_" . $i ."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
			}
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone 2 Title', 'MODULE_SHIPPING_ZONES2_TEXT_TITLE', 'Zone 2 Title', 'The text used as the title of this module', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invalid Country Text', 'MODULE_SHIPPING_ZONES2_INVALID_ZONE', 'Sorry, this method is not available for your country.', 'The text used to inform customer that this method is not available outside the UK', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Underfined Rate Text', 'MODULE_SHIPPING_ZONES2_UNDEFINED_RATE', 'The shipping rate cannot be determined at this time.', 'The text used if weight is not covered', '7', '0', now())");
	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order min weight', 'MODULE_SHIPPING_ZONES2_MIN_WEIGHT', '0.5', 'Minimum weight in g(s) for order', '6', '7', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order max weight', 'MODULE_SHIPPING_ZONES2_MAX_WEIGHT', '10', 'Maximum weight in g(s) for order', '6', '8', now())");





   }
   // elari - Added to select default country if not in listing

   function remove() {
     tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
   }

   function keys() {
     $keys = array('MODULE_SHIPPING_ZONES2_STATUS', 'MODULE_SHIPPING_ZONES2_TAX_CLASS', 'MODULE_SHIPPING_ZONES2_SORT_ORDER', 'MODULE_SHIPPING_ZONES2_TEXT_TITLE', 'MODULE_SHIPPING_ZONES2_INVALID_ZONE', 'MODULE_SHIPPING_ZONES2_UNDEFINED_RATE','MODULE_SHIPPING_ZONES2_MIN_WEIGHT','MODULE_SHIPPING_ZONES2_MAX_WEIGHT');

     for ($i=1; $i<=$this->num_zones2; $i++) {
       $keys[] = 'MODULE_SHIPPING_ZONES2_COUNTRIES_' . $i;
       $keys[] = 'MODULE_SHIPPING_ZONES2_COST_' . $i;
       $keys[] = 'MODULE_SHIPPING_ZONES2_HANDLING_' . $i;
     }

     return $keys;
   }
 }
?>