<?php
class zones {
   var $code, $title, $description, $enabled, $num_zones;

// class constructor
   function zones() {
     $this->code = 'zones';
     $this->title = MODULE_SHIPPING_ZONES_TEXT_TITLE;
     $this->description = MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION;
     $this->sort_order = MODULE_SHIPPING_ZONES_SORT_ORDER;
     $this->icon = '';
     $this->tax_class = MODULE_SHIPPING_ZONES_TAX_CLASS;
     $this->enabled = ((MODULE_SHIPPING_ZONES_STATUS == 'True') ? true : false);

     // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
     $this->num_zones = ZONES_MODULE_NUMBER;
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

     for ($i=1; $i<=$this->num_zones; $i++) {
       $countries_table = constant('MODULE_SHIPPING_ZONES_COUNTRIES_' . $i);
       $country_zones = preg_split("/[,]/", $countries_table);
       if (in_array($dest_country, $country_zones)) {
         $dest_zone = $i;
         break;
       }
     }
//ECHO $dest_country.'-dfgfdg';exit;
     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $dest_zone = $this->num_zones;    // the zone is the lastest zone avalaible
     }
     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $error = true;      // this can no more achieve since by default the value is set to the max number of zones
     } else {
       $shipping = -1;
       $zones_cost = constant('MODULE_SHIPPING_ZONES_COST_' . $dest_zone);

       $zones_table = preg_split("/[:,]/" , $zones_cost);
       $size = sizeof($zones_table);
       for ($i=0; $i<$size; $i+=2) {
         if ($shipping_weight <= $zones_table[$i]) {
           $shipping = $zones_table[$i+1];
           $shipping_method = MODULE_SHIPPING_ZONES_TEXT_WAY . ' ' . $order->delivery['country']['title'] . ': ';
           if ($shipping_num_boxes > 1) {
             $shipping_method .= $shipping_num_boxes . 'x ';
           }
           $shipping_method .= $shipping_weight . ' ' . MODULE_SHIPPING_ZONES_TEXT_UNITS;
           break;
         }
       }

       if ($shipping == -1) {
         $shipping_cost = 0;
         $shipping_method = MODULE_SHIPPING_ZONES_UNDEFINED_RATE;
       } else {
         $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_ZONES_HANDLING_' . $dest_zone);
       }
     }

     $this->quotes = array('id' => $this->code,
                           'module' => MODULE_SHIPPING_ZONES_TEXT_TITLE,
                           'methods' => array(array('id' => $this->code,
                                                    'title' => $shipping_method,
                                                    'cost' => $shipping_cost)));

     if ($this->tax_class > 0) {
       $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
     }

     if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

     if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_ZONES_INVALID_ZONE;

     return $this->quotes;
   }

   function check() {
     if (!isset($this->_check)) {
       $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_STATUS'");
       $this->_check = tep_db_num_rows($check_query);
     }
     return $this->_check;
   }

   // elari - Added to select default country if not in listing
   function install() {
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Zones Method', 'MODULE_SHIPPING_ZONES_STATUS', 'True', 'Do you want to offer zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_ZONES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_ZONES_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
     for ($i = 1; $i <= $this->num_zones; $i++) {
       $default_countries = '';
       if ($i == 1) {
         $default_countries = 'GB, ';
         $shipping_table = '5:5.00,7:10.00,99:100.00';
       }
       if ($i == 2) {
         $default_countries = 'AT, BE, DE, FR, GL, IS, IE, IT, NL, NO, DK, PL, ES, SE, CH, FI, PT, IL, GR, US, ';
         $shipping_table = '2:10.00,5:15.00,99:150.00';
       }
       if ($i == 3) {
         $default_countries = 'All Others'; // this must be the lastest zone
         $shipping_table = '2:500.00,5:500.00,99:500.00';
       }
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_ZONES_COST_" . $i ."', '" . $shipping_table . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_" . $i ."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
	}
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Worldwide Delivery Title', 'MODULE_SHIPPING_ZONES_TEXT_TITLE', 'Worldwide Delivery', 'The text used as the title of this module', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Worldwide Delivery description Text', 'MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION', 'Delivery outside of Europe', 'The text used as the description of this module', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Worldwide Delivery Way Text', 'MODULE_SHIPPING_ZONES_TEXT_WAY', 'Shipping to', 'The text used as the description of this module', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Weight Text', 'MODULE_SHIPPING_ZONES_TEXT_UNITS', 'kg(s)', 'The text used in association to weight', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invalid Country Text', 'MODULE_SHIPPING_ZONES_INVALID_ZONE', 'Sorry, this method is not available for your country.', 'TThe text used to inform customer that this method is not available outside the UK', '7', '0', now())");
			 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Underfined Rate Text', 'MODULE_SHIPPING_ZONES_UNDEFINED_RATE', 'The shipping rate cannot be determined at this time.', 'The text used if weight is not covered', '7', '0', now())");

   }
   // elari - Added to select default country if not in listing

   function remove() {
     tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
   }

   function keys() {
     $keys = array('MODULE_SHIPPING_ZONES_STATUS', 'MODULE_SHIPPING_ZONES_TAX_CLASS', 'MODULE_SHIPPING_ZONES_SORT_ORDER', 'MODULE_SHIPPING_ZONES_TEXT_TITLE', 'MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION','MODULE_SHIPPING_ZONES_TEXT_WAY', 'MODULE_SHIPPING_ZONES_TEXT_UNITS', 'MODULE_SHIPPING_ZONES_INVALID_ZONE', 'MODULE_SHIPPING_ZONES_UNDEFINED_RATE');

     for ($i=1; $i<=$this->num_zones; $i++) {
       $keys[] = 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $i;
       $keys[] = 'MODULE_SHIPPING_ZONES_COST_' . $i;
       $keys[] = 'MODULE_SHIPPING_ZONES_HANDLING_' . $i;
     }

     return $keys;
   }
 }
?>
