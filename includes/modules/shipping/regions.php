<?php

/*

  $Id: regions.php, v 4.0 2004/12/22 14:29:56 Jorge Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2008 Adoovo Inc. USA  GNU General Public License Compatible

  -----------------------------------------------------------------------------
  REGIONS -  Rates Based on State or Regions (group of States) module for osC 2.2-CVS (new checkout)

  This module allows you create shipping regions by dividing states of the USA (or other country) in different groups. Each group will then have it's own shipping price which you can based on price or weight.

  This module is perfect when for those of you need to charge different when shipping to different parts of the country.

  Features
  1..Regions can be composed of US States or of any other country
  2..Order weight or price can be used to calculate shipping price.
  3..Any number of regions
  4..Handling fee can be added.

  Jorge

  Set the number of regions you need with
  $this->regions = xx;

  Please note that any country / state that is not in one of the groups
  will not be able to checkout if this the only shipping you provide.
  However it will display a nice message saying so.



  Written by Jorge (billythekid_2000@hotmail.com)

*/

  class regions {
    var $code, $title, $description, $enabled, $regions;

// class constructor
    function regions() {
      $this->code = 'regions';
      $this->title = MODULE_SHIPPING_REGIONS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_REGIONS_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_REGIONS_TAX_CLASS;
      $this->enabled = MODULE_SHIPPING_REGIONS_STATUS;
      $this->sort_order = MODULE_SHIPPING_REGIONS_SORT_ORDER;
      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF States NEEDED
      $this->regions = 6;
    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $cart, $total_count;

      if (MODULE_SHIPPING_REGIONS_MODE == 'price') {
        $order_total_price = $cart->show_total();
      }
      if (MODULE_SHIPPING_REGIONS_MODE == 'weight') {
        $order_total_price = $shipping_weight;
      }
      if (MODULE_SHIPPING_REGIONS_MODE == 'per_item') {
        $order_total_price = $total_count;
      }
      if (MODULE_SHIPPING_REGIONS_MODE == 'percentage') {
        $order_total_price = $cart->show_total();
      }

      $dest_state = $order->delivery['state'];
      $dest_country = $order->delivery['country']['title'];

      $dest_region = 0;
      $error = false;

      for ($i=1; $i<=$this->regions; $i++) {
        $regions_table = constant('MODULE_SHIPPING_REGIONS' . $i);
        $country_states_or_countries = preg_split("/[,]/", $regions_table);
        if (in_array($dest_state, $country_states_or_countries)) {
          $dest_region = $i;
          break;
        }
      }
      if ($dest_region == 0) {
	      for ($i=1; $i<=$this->regions; $i++) {
	        $regions_table = constant('MODULE_SHIPPING_REGIONS' . $i);
	        $country_states_or_countries = preg_split("/[,]/", $regions_table);
	        if (in_array($dest_country, $country_states_or_countries)) {
	          $dest_region = $i;
	          break;
	        }
	      }
      }

      if ($dest_region == 0) {
        $error = true;
      } else {
        $shipping = -1;
        $region_cost = constant('MODULE_SHIPPING_REGIONS_COST' . $i);

        $regions_table = preg_split("/[:,]/" , $region_cost);

        if (MODULE_SHIPPING_REGIONS_MODE == 'price') {
	        for ($i=0; $i<sizeof($regions_table); $i+=2) {
	          if ($order_total_price <= $regions_table[$i]) {
	            $shipping = $regions_table[$i+1];
	            $shipping_method = MODULE_SHIPPING_REGIONS_TEXT_WAY . ' ' . "$dest_state, $dest_country" . ' ' . MODULE_SHIPPING_REGIONS_TEXT_UNITS;
	            break;
	          }
	        }
        }
        if (MODULE_SHIPPING_REGIONS_MODE == 'weight') {
	        for ($i=0; $i<sizeof($regions_table); $i+=2) {
	          if ($order_total_price <= $regions_table[$i]) {
	            $shipping = $regions_table[$i+1];
	            $shipping_method = MODULE_SHIPPING_REGIONS_WEIGHT . ' ' . $shipping_weight . ' ' . MODULE_SHIPPING_REGIONS_WEIGHT_TEXT . ' '  . "$dest_state, $dest_country" . ' ' . MODULE_SHIPPING_REGIONS_TEXT_UNITS;
	            break;
	          }
	        }
        }
        if (MODULE_SHIPPING_REGIONS_MODE == 'per_item') {
	        for ($i=0; $i<sizeof($regions_table); $i+=2) {
	          if ($order_total_price <= $regions_table[$i]) {
	            $shipping = $regions_table[$i+1] * $order_total_price;
	            $shipping_method = MODULE_SHIPPING_REGIONS_ITEMS . ' ' . $total_count . ' ' . MODULE_SHIPPING_REGIONS_ITEMS_TEXT . ' ' . "$dest_state, $dest_country" . ' ' . MODULE_SHIPPING_REGIONS_TEXT_UNITS;
	            break;
	          }
	        }
        }
        if (MODULE_SHIPPING_REGIONS_MODE == 'percentage') {
	        for ($i=0; $i<sizeof($regions_table); $i+=2) {
	          if ($order_total_price <= $regions_table[$i]) {
	            $shipping = ($regions_table[$i+1] / 100) * $order_total_price;
	            $shipping_method = MODULE_SHIPPING_REGIONS_TEXT_WAY . ' ' . "$dest_state, $dest_country" . ' ' . MODULE_SHIPPING_REGIONS_TEXT_UNITS;
	            break;
	          }
	        }
        }


        if ($shipping == -1) {
          $shipping_cost = 0;
          $shipping_method = MODULE_SHIPPING_REGIONS_UNDEFINED_RATE;
        } else {
          $shipping_cost = ($shipping + MODULE_SHIPPING_REGIONS_HANDLING + SHIPPING_HANDLING);
        }
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_REGIONS_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

      if ($this->tax_class > 0) {
      	$this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_REGIONS_INVALID_ZONE;

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_REGIONS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable US Regions Method', 'MODULE_SHIPPING_REGIONS_STATUS', '1', 'Do you want to offer Regions rate shipping?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_REGIONS_SORT_ORDER', '1', 'Sort order of display.', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_REGIONS_TAX_CLASS', '1', 'Use the following tax class on the shipping fee.', '6', '2', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_REGIONS_HANDLING', '0.00', 'Handling Fee for this shipping method', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mode', 'MODULE_SHIPPING_REGIONS_MODE', 'price', 'Is the shipping table based on total weight, total amount of order, number of itmes, or a percentage of the total price.', '6', '4', 'tep_cfg_select_option(array(\'weight\', \'price\', \'per_item\', \'percentage\'), ', now())");
      for ($i = 1; $i <= $this->regions; $i++) {
        $default_countries = '';
        if ($i == 1) {
          $default_states_or_countries = 'California';
          $default_prices = '30:8.95,75:10.95,150:16.95,400:21.95,10000:25.95';
        }
        if ($i == 2) {
          $default_states_or_countries = 'Alabama,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,District of Columbia,Florida,Georgia,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana';
          $default_prices = '30:10.95,75:12.95,150:18.95,400:23.95,10000:27.95';
        }
        if ($i == 3) {
          $default_states_or_countries = 'Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming';
          $default_prices = '30:10.95,75:12.95,150:18.95,400:23.95,10000:27.95';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Region " . $i ." States/Countries', 'MODULE_SHIPPING_REGIONS" . $i ."', '" . $default_states_or_countries . "', 'Comma separated list of States and/or Countries', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Region " . $i ." Shipping Table', 'MODULE_SHIPPING_REGIONS_COST" . $i ."', '" . $default_prices . "' , '(weight/price/# of items/)<b>:</b>(shipping cost/percentage)', '6', '0', now())");
      }
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_REGIONS_STATUS', 'MODULE_SHIPPING_REGIONS_SORT_ORDER', 'MODULE_SHIPPING_REGIONS_HANDLING', 'MODULE_SHIPPING_REGIONS_MODE', 'MODULE_SHIPPING_REGIONS_TAX_CLASS');
      for ($i=1; $i<=$this->regions; $i++) {
        $keys[] = 'MODULE_SHIPPING_REGIONS' . $i;
        $keys[] = 'MODULE_SHIPPING_REGIONS_COST' . $i;
      }

      return $keys;
    }
  }
?>
