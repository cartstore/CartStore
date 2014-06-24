<?php
/*
rmairmail.php

Royal Mail Airmail

Shipping module for osCommerce

Version 2.2.2 - 4 April 2011

A shipping module for UK based osCommerce stores.

This version created by Chris Lander from an original contribution
 by Stuart Newton (contribution #4473).


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class rmairmail {
	var $code, $title, $description, $enabled, $num_zones;

	// class constructor
	function rmairmail() {

		global $order, $total_weight;

		$this->version = '2.2.2';
		$this->code = 'rmairmail';
		$this->title = MODULE_SHIPPING_RMAIRMAIL_TEXT_TITLE . ' v' . $this->version;
		$this->description = MODULE_SHIPPING_RMAIRMAIL_TEXT_DESCRIPTION . ' v' . $this->version;
		$this->sort_order = MODULE_SHIPPING_RMAIRMAIL_SORT_ORDER;
		$this->icon = DIR_WS_ICONS . 'shipping_rmairmail.gif'; // upload icon to catalog/images/icon directory
		$this->tax_class = MODULE_SHIPPING_RMAIRMAIL_TAX_CLASS;
		$this->enabled = ((MODULE_SHIPPING_RMAIRMAIL_STATUS == 'True') ? true : false);
		$this->num_zones = 2;
		$this->weight_split = ((MODULE_SHIPPING_RMAIRMAIL_WEIGHT_SPLIT == 'True') ? true : false);
		$this->value_split = ((MODULE_SHIPPING_RMAIRMAIL_VALUE_SPLIT == 'True') ? true : false);

		// Calculate shipping weight taking into account
		//  Maximum Package Weight you will ship
		//  Package Tare weight
		//  Larger packages percent increase
		//
		//  as is done in ..../includes/classes/shipping.php line# 70 - 79
		//
		if (SHIPPING_BOX_WEIGHT >= $total_weight*SHIPPING_BOX_PADDING/100) {
			$shipping_weight = $total_weight+SHIPPING_BOX_WEIGHT;
		} else {
			$shipping_weight = $total_weight + ($total_weight*SHIPPING_BOX_PADDING/		100);
		}

		// Default to split using store maximum shipping weight
		$my_max_shipping_weight = SHIPPING_MAX_WEIGHT;
		// Split shipment using Method Maximum Shipment Weight?
		if ($this->weight_split) {
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT) {
				$my_max_shipping_weight = MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT;
			}
		}

		// Split shipment into the required number of boxes
		if ($shipping_weight > $my_max_shipping_weight) {
			$shipping_num_boxes = ceil($shipping_weight/$my_max_shipping_weight);
			$shipping_weight = $shipping_weight/$shipping_num_boxes;
		}

		// Ensure we have Set number of boxes and weight
		$shipping_num_boxes = (isset($shipping_num_boxes) ? $shipping_num_boxes : 1);
		$shipping_weight = (isset($shipping_weight) ? $shipping_weight : 0);
		$my_shipping_num_boxes = $shipping_num_boxes;
		$my_shipping_weight = $shipping_weight;

		// Split shipment on method Maximum Shipment Value?
		if ($this->value_split) {
			// Calc the value and weight of each package being shipped
			if ( ($order->info['subtotal'] / $shipping_num_boxes) > MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		// Only ship if packet value exceeds method minimum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) < MODULE_SHIPPING_RMAIRMAIL_MIN_VALUE) {
			$this->enabled = false;
		}

		// Only ship if packet value does not exceed method maximum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE) {
			$this->enabled = false;
		}

		// Only ship if packet weight exceeds method minimum value
		if ( $my_shipping_weight < MODULE_SHIPPING_RMAIRMAIL_MIN_WEIGHT) {
			$this->enabled = false;
		}

		// Only ship if packet weight does not exceed method maximum value
		if ( $my_shipping_weight > MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT) {
			$this->enabled = false;
		}

		// Get the destination country
		$dest_country = $order->delivery['country']['iso_code_2']; # Get the destination country

		// Only Non-UK Customers to see shipping method. Hide for UK customers
		// Check if destination is a valid one for this method
		if (defined('MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES')) {
			$invalid_zones_table = constant('MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES');
			$invalid_zones = preg_split("/[,]/", $invalid_zones_table);
			if (in_array($dest_country, $invalid_zones)) { // Zone Invalid?
				$this->enabled= false;
			}else{ // Destination MUST be a Valid one
				$dest_zone = $this->num_zones;					// Set default shipping zone to last zone available
				for ($i=1; $i<=$this->num_zones; $i++) {
					$constant = 'MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_'.$i;
					if (defined($constant)) {
						$countries_table = constant('MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_' . $i);
						$country_zones = preg_split("/[,]/", $countries_table);
						if (in_array($dest_country, $country_zones)) {
							$dest_zone = $i;
							break;
						}
					}
				}
			}
		}
	}

	// class methods
	function quote($method = '') {
		global $order, $shipping_weight, $shipping_num_boxes;

		$error = false; // Reset error flag

		// Ensure we have Set number of boxes and weight
		$shipping_num_boxes = (isset($shipping_num_boxes) ? $shipping_num_boxes : 1);
		$shipping_weight = (isset($shipping_weight) ? $shipping_weight : 0);
		$my_shipping_num_boxes = $shipping_num_boxes;
		$my_shipping_weight = $shipping_weight;

		// Split shipment using Method Maximum Shipment Weight?
		if ($this->weight_split) {
			// Store Max Weight larger than Shipping MaxWeight?
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT) {
				// Calc the value and weight of each package being shipped
				$my_max_shipping_weight = MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT;
				if ($shipping_weight > $my_max_shipping_weight) {
					$my_shipping_num_boxes = ceil(($shipping_weight * $shipping_num_boxes)/$my_max_shipping_weight);
					$my_shipping_weight = ($shipping_weight * $shipping_num_boxes)/$my_shipping_num_boxes;
				}
			}
		}

		// Split shipment on method Maximum Shipment Value?
		if ($this->value_split) {
			// Calc the value and weight of each package being shipped
			if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		$shipping = -1;	// Flag no shipping cost available

		// Get the destination country
		$dest_country = $order->delivery['country']['iso_code_2']; # Get the destination country
		$dest_zone = 0; // Flag invalid destination

		// Only Non-UK Customers to see shipping method. Hide for UK customers
		// Check if destination is a valid one for this method
		if (defined('MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES')) {
			$invalid_zones_table = constant('MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES');
			$invalid_zones = preg_split("/[,]/", $invalid_zones_table);
			if (in_array($dest_country, $invalid_zones)) { // Zone Invalid?
				$dest_zone = 0;
			}else{ // Destination MUST be Valid so find which shipping zone destination belongs to
				$dest_zone = $this->num_zones;					// Set default shipping zone to last zone available
				for ($i=1; $i<=$this->num_zones; $i++) {
					$constant = 'MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_'.$i;
					if (defined($constant)) {
						$countries_table = constant('MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_' . $i);
						$country_zones = preg_split("/[,]/", $countries_table);
						if (in_array($dest_country, $country_zones)) {
							$dest_zone = $i;
							break;
						}
					}
				}
			}
		}

		// Invalid Destination Zone found?
		if ($dest_zone == 0) {
			$shipping_cost = 0;
			$shipping_method = MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONE;
		}else{ // Destination must be in a valid zone
			// Get the cost to ship to the destination zone
			$shipping = -1;	// Flag no shipping cost available
			$zones_cost = constant('MODULE_SHIPPING_RMAIRMAIL_COST_' . $dest_zone);
			
			$zones_table = preg_split("/[:,]/" , $zones_cost);
			$size = sizeof($zones_table);

			// Determine if shipping cost is available
			for ($i=0; $i<$size; $i+=2) {
				if ($my_shipping_weight <= $zones_table[$i]) {
					$shipping = $zones_table[$i+1];
					if(tep_not_null($method) ) {
						// Text shown on Checkout_Confirmation
						$shipping_method = ''; // Leaving this entry blank causes only the shipping title to show i.e Royal Mail 1st Class Std
					}else{
						// Text shown on Checkout_shipping
						$shipping_method = '';
						// Display delivery weight?
						if (constant('MODULE_SHIPPING_RMAIRMAIL_DISPLAY_WEIGHT') == 'True') {
							// Delivery Weight : x items of n.nnnn Kg's
							$shipping_method = MODULE_SHIPPING_RMAIRMAIL_TEXT_WAY. ' : ';
							if ($my_shipping_num_boxes > 1) { // Shipment split between several boxes/packets
								$shipping_method = $shipping_method . $shipping_num_boxes . ' '.MODULE_SHIPPING_RMAIRMAIL_TEXT_ITEMS.' ';
							}else{
								$shipping_method = $shipping_method . $shipping_num_boxes . ' '.MODULE_SHIPPING_RMAIRMAIL_TEXT_ITEM.' ';
							}
							$shipping_method = $shipping_method . $shipping_weight. ' ' . MODULE_SHIPPING_RMAIRMAIL_TEXT_UNITS;
						}
						// Display delivery times?
						if (constant('MODULE_SHIPPING_RMAIRMAIL_DISPLAY_TIME') == 'True') {
							// Ships within 3 to 5 days
						$shipping_method = $shipping_method . ' (';
						$shipping_method = $shipping_method . ucfirst(MODULE_SHIPPING_RMAIRMAIL_DELIVERY_SHIPPING_TIME) . ')';
						}
					}
					break;
				}
			}
		}

		// No shipping cost found?
		if ($shipping == -1) {
			$shipping_cost = 0;
			$shipping_method = MODULE_SHIPPING_RMAIRMAIL_UNDEFINED_RATE;
		// Shipping charge found, so add together all costs
		} else {
			$shipping_cost = ($shipping * $my_shipping_num_boxes) + constant('MODULE_SHIPPING_RMAIRMAIL_HANDLING_' . $this->num_zones);
		}

		$this->quotes = array('id' => $this->code,
			'module' => MODULE_SHIPPING_RMAIRMAIL_TEXT_TITLE,
			'methods' => array(array('id' => $this->code,
			'title' => $shipping_method,
			'cost' => $shipping_cost)));
	
		// Need to apply tax ?
		if ($this->tax_class > 0) {
			$this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		}

		if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
	
		if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONE;
	
		return $this->quotes;
		}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_RMAIRMAIL_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		// MODULE_SHIPPING_RMAIRMAIL_VERSION
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Version', 'MODULE_SHIPPING_RMAIRMAIL_VERSION', '2.2.2', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_STATUS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable RM Airmail', 'MODULE_SHIPPING_RMAIRMAIL_STATUS', 'True', 'Do you want to offer this shipping option?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_TAX_CLASS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_RMAIRMAIL_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
		// MODULE_SHIPPING_RMAIRMAIL_SORT_ORDER
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_RMAIRMAIL_SORT_ORDER', '8', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_MIN_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum weight to ship', 'MODULE_SHIPPING_RMAIRMAIL_MIN_WEIGHT', '0', 'Enter the minimum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum weight to ship', 'MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT', '2', 'Enter the maximum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_WEIGHT_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum weight to ship', 'MODULE_SHIPPING_RMAIRMAIL_WEIGHT_SPLIT', 'False', 'Do you want to split your shipment by maximum weight to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_MIN_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum value to ship', 'MODULE_SHIPPING_RMAIRMAIL_MIN_VALUE', '0', 'Enter the maximum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum value to ship', 'MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE', '46', 'Enter the maximum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMAIRMAIL_VALUE_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum value to ship', 'MODULE_SHIPPING_RMAIRMAIL_VALUE_SPLIT', 'False', 'Do you want to split your shipment by maximum value to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_DISPLAY_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery weight', 'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_WEIGHT', 'True', 'Do you want to display the shipping weight? (e.g. Delivery Weight : 2.7674 Kg\'s)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_DISPLAY_INSURANCE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Insurance', 'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_INSURANCE', 'True', 'Do you want to display the shipping insurance? (e.g. Insured upto &pound;500)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_DISPLAY_TIME
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery time', 'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_TIME', 'True', 'Do you want to display the shipping time? (e.g. Ships within 3 to 5 days)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invalid ISO Country Codes', 'MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES', 'GB', 'Enter the two digit ISO code for which this shipping method does NOT apply to. (Default: GB)', '6', '0', now())");

		for ($i = 1; $i <= $this->num_zones; $i++) {
			$default_countries = '';
			$shipping_table = '';
			$shipping_handling = '';
			if ($i == 1) {
				$default_countries = 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA';
				$shipping_table = '.1:1.49,.12:1.61,.14:1.79,.16:1.93,.18:2.11,.2:2.19,.22:2.29,.24:2.39,.26:2.49,.28:2.56,.3:2.61,.4:3.16,.5:3.71,.6:4.26,.7:4.81,.8:5.36,.9:5.91,1:6.46,1.1:7.01,1.2:7.56,1.3:8.11,1.4:8.66,1.5:9.21,1.6:9.76,1.7:10.31,1.8:10.86,1.9:11.41,2:11.96';
				$shipping_handling = 0;
			// MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Airmail Zone ".$i." Valid ISO Country Codes', 'MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are valid destinations for this method (Default: ".$default_countries.")', '6', '0', now())");
			}
			if ($i == 2) {
				$default_countries = 'All Others'; // this must be the lastest zone
				$shipping_table = '.1:2.07,.12:2.32,.14:2.6,.16:2.9,.18:3.2,.2:3.5,.22:3.8,.24:3.96,.26:4.06,.28:4.16,.3:4.26,.4:5.37,.5:6.48,.6:7.59,.7:8.7,.8:9.81,.9:10.92,1:12.03,1.1:13.14,1.2:14.25,1.3:15.36,1.4:16.47,1.5:17.58,1.6:18.69,1.7:19.8,1.8:20.91,1.9:22.02,2:23.13';
				$shipping_handling = 0;
			// MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Airmail Zone ".$i." Valid ISO Country Codes', 'MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_" . $i ."', '" . $default_countries . "', 'All other destinations (Default: ".$default_countries.")', '6', '0', now())");
			}
			// MODULE_SHIPPING_RMAIRMAIL_COST_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Airmail Zone ".$i." Rates', 'MODULE_SHIPPING_RMAIRMAIL_COST_" . $i ."', '" . $shipping_table . "', 'Enter values upto 5,2 decimal places. (12345.67) Example: 2:4.2,4:6.85 - Weights less than or equal to 2Kg would cost 4.20, Weights less than or equal to 4Kg but more than 2Kg will cost 6.85. Do not enter KG or currency symbols.', '6', '0', now())");
			// MODULE_SHIPPING_RMAIRMAIL_HANDLING_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Airmail Zone ".$i." Packaging / Handling Fee', 'MODULE_SHIPPING_RMAIRMAIL_HANDLING_" . $i ."', '" . $shipping_handling . "', 'If you want to add extra costs to customers for jiffy bags etc, the cost can be entered below (eg enter 1.50 for a value of 1.50)', '6', '0', now())");
		}
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('MODULE_SHIPPING_RMAIRMAIL_VERSION','" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		$keys = array('MODULE_SHIPPING_RMAIRMAIL_STATUS'
				,'MODULE_SHIPPING_RMAIRMAIL_TAX_CLASS'
				,'MODULE_SHIPPING_RMAIRMAIL_SORT_ORDER'
				,'MODULE_SHIPPING_RMAIRMAIL_MIN_WEIGHT'
				,'MODULE_SHIPPING_RMAIRMAIL_MAX_WEIGHT'
				,'MODULE_SHIPPING_RMAIRMAIL_WEIGHT_SPLIT'
				,'MODULE_SHIPPING_RMAIRMAIL_MIN_VALUE'
				,'MODULE_SHIPPING_RMAIRMAIL_MAX_VALUE'
				,'MODULE_SHIPPING_RMAIRMAIL_VALUE_SPLIT'
				,'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_WEIGHT'
				,'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_INSURANCE'
				,'MODULE_SHIPPING_RMAIRMAIL_DISPLAY_TIME'
				,'MODULE_SHIPPING_RMAIRMAIL_INVALID_ZONES'
			);
		for ($i=1; $i<=$this->num_zones; $i++) {
			$keys[] = 'MODULE_SHIPPING_RMAIRMAIL_COUNTRIES_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMAIRMAIL_COST_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMAIRMAIL_HANDLING_' . $i;
		}
		return $keys;
	}
}
?>