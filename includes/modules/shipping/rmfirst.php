<?php
/*
rmfirst.php

Royal Mail First Class Postage

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


class rmfirst {
	var $code, $title, $description, $enabled, $num_zones;

	// class constructor
	function rmfirst() {

		global $order, $total_weight;

		$this->version = '2.2.2';
		$this->code = 'rmfirst';
		$this->title = MODULE_SHIPPING_RMFIRST_TEXT_TITLE . ' v' . $this->version;
		$this->description = MODULE_SHIPPING_RMFIRST_TEXT_DESCRIPTION . ' v' . $this->version;
		$this->sort_order = MODULE_SHIPPING_RMFIRST_SORT_ORDER;
		$this->icon = DIR_WS_ICONS . 'shipping_rmuk.jpg'; // upload icon to catalog/images/icon directory
		$this->tax_class = MODULE_SHIPPING_RMFIRST_TAX_CLASS;
		$this->enabled = ((MODULE_SHIPPING_RMFIRST_STATUS == 'True') ? true : false);
		$this->num_zones = 1;
		$this->weight_split = ((MODULE_SHIPPING_RMFIRST_WEIGHT_SPLIT == 'True') ? true : false);
		$this->value_split = ((MODULE_SHIPPING_RMFIRST_VALUE_SPLIT == 'True') ? true : false);

		// Get the destination country
		$dest_country = $order->delivery['country']['iso_code_2']; # Get the destination country

		// Check if destination is a valid one for this method
		if (defined('MODULE_SHIPPING_RMFIRST_COUNTRIES_' . $this->num_zones)) {
			$countries_table = constant('MODULE_SHIPPING_RMFIRST_COUNTRIES_' . $this->num_zones);
			$country_zones = preg_split("/[,]/", $countries_table);
			if (!in_array($dest_country, $country_zones)) { // Zone NOT valid
				$this->enabled = false;
			}
		}

		// Calculate shipping weight taking into account
		//  Maximum Package Weight you will ship
		//  		Package Tare weight
		//  Larger packages percent increase
		//
		//  as is done in ..../includes/classes/shipping.php line# 70 - 79
		//
		if (SHIPPING_BOX_WEIGHT >= $total_weight*SHIPPING_BOX_PADDING/100) {
			$shipping_weight = $total_weight+SHIPPING_BOX_WEIGHT;
		} else {
			$shipping_weight = $total_weight + ($total_weight*SHIPPING_BOX_PADDING/100);
		}

		// Default to split using store maximum shipping weight
		$my_max_shipping_weight = SHIPPING_MAX_WEIGHT;
		// Split shipment using Method Maximum Shipment Weight?
		if ($this->weight_split) {
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMFIRST_MAX_WEIGHT) {
				$my_max_shipping_weight = MODULE_SHIPPING_RMFIRST_MAX_WEIGHT;
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
			if ( ($order->info['subtotal'] / $shipping_num_boxes) > MODULE_SHIPPING_RMFIRST_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMFIRST_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		// Only ship if packet value exceeds method minimum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) < MODULE_SHIPPING_RMFIRST_MIN_VALUE) {
			$this->enabled = false;
		}

		// Only ship if packet value does not exceed method maximum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMFIRST_MAX_VALUE) {
			$this->enabled = false;
		}
		// Only ship if packet weight exceeds method minimum value
		if ( $my_shipping_weight < MODULE_SHIPPING_RMFIRST_MIN_WEIGHT) {
			$this->enabled = false;
		}

		// Only ship if packet weight does not exceed method maximum value
		if ( $my_shipping_weight > MODULE_SHIPPING_RMFIRST_MAX_WEIGHT) {
			$this->enabled = false;
		}
	}

	// class methods
	function quote($method = '') {

		global $order, $shipping_weight, $shipping_num_boxes;

		$dest_country = $order->delivery['country']['iso_code_2']; // Get destination ISO code
		$dest_zone = 0; // Flag invalid destination
		$error = false; // Reset error flag

		// Ensure we have Set number of boxes and weight
		$shipping_num_boxes = (isset($shipping_num_boxes) ? $shipping_num_boxes : 1);
		$shipping_weight = (isset($shipping_weight) ? $shipping_weight : 0);
		$my_shipping_num_boxes = $shipping_num_boxes;
		$my_shipping_weight = $shipping_weight;

		// Split shipment using Method Maximum Shipment Weight?
		if ($this->weight_split) {
			// Store Max Weight larger than Shipping MaxWeight?
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMFIRST_MAX_WEIGHT) {
				// Calc the value and weight of each package being shipped
				$my_max_shipping_weight = MODULE_SHIPPING_RMFIRST_MAX_WEIGHT;
				if ($shipping_weight > $my_max_shipping_weight) {
					$my_shipping_num_boxes = ceil(($shipping_weight * $shipping_num_boxes)/$my_max_shipping_weight);
					$my_shipping_weight = ($shipping_weight * $shipping_num_boxes)/$my_shipping_num_boxes;
				}
			}
		}

		// Split shipment on method Maximum Shipment Value?
		if ($this->value_split) {
			// Calc the value and weight of each package being shipped
			if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMFIRST_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMFIRST_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		// Check if destination is a valid one for this method
		$countries_table = constant('MODULE_SHIPPING_RMFIRST_COUNTRIES_' . $this->num_zones);
		$country_zones = preg_split("/[,]/", $countries_table);
		if (in_array($dest_country, $country_zones)) {
			$dest_zone = $this->num_zones;
		}

		// Is the destination in a valid zone?
		if ($dest_zone == $this->num_zones) { // Destination in valid zone
			$shipping = -1;
			// Get the zone costs table
			$zones_cost = constant('MODULE_SHIPPING_RMFIRST_COST_' . $dest_zone);
			// Find the shipping cost
			$zones_table = preg_split("/[:,]/" , $zones_cost);
			$size = sizeof($zones_table);
			// Loop through costs until shipping weight less than or equal to weight in table
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
						if (constant('MODULE_SHIPPING_RMFIRST_DISPLAY_WEIGHT') == 'True') {
							// Delivery Weight : x items of n.nnnn Kg's
							$shipping_method = MODULE_SHIPPING_RMFIRST_TEXT_WAY. ' : ';
 							// Shipment split between several boxes/packets?
							if ($my_shipping_num_boxes > 1) { // More than 1 package
									$shipping_method = $shipping_method . $my_shipping_num_boxes . ' '.MODULE_SHIPPING_RMFIRST_TEXT_ITEMS.' ';
							}else{ // 1 package only
								$shipping_method = $shipping_method . $my_shipping_num_boxes . ' '.MODULE_SHIPPING_RMFIRST_TEXT_ITEM.' ';
							}
							// Weight units
							$shipping_method = $shipping_method . $my_shipping_weight. ' ' . MODULE_SHIPPING_RMFIRST_TEXT_UNITS;
						}
						// Display delivery times?
						if (constant('MODULE_SHIPPING_RMFIRST_DISPLAY_TIME') == 'True') {
							// Ships normally in 1 to 3 days
							$shipping_method = $shipping_method . ' (';
							$shipping_method = $shipping_method . MODULE_SHIPPING_RMFIRST_DELIVERY_SHIPPING_TIME . ')';
						}
					}
					// When shipping weight less than or equal to weight in table no need to go any further
					break;
				}
			}

			// Have we found a shipping cost?
			if ($shipping == -1) { // No shipping cost found
				$shipping_cost = 0;
				$shipping_method = MODULE_SHIPPING_RMFIRST_UNDEFINED_RATE;
			} else { // Shipping cost found
				$shipping_cost = ($shipping * $my_shipping_num_boxes) + constant('MODULE_SHIPPING_RMFIRST_HANDLING_' . $dest_zone);
			}
		}else{ // Destination NOT in valid zone
			$error = true;
		}

		// Build the returned array of shipping information
		$this->quotes = array(	'id' => $this->code,
					'module' => MODULE_SHIPPING_RMFIRST_TEXT_TITLE,
					'methods' => array(array('id' => $this->code,
					'title' => $shipping_method,
					'cost' => $shipping_cost)));

		// Do we need to apply tax?
		if ($this->tax_class > 0) {
			$this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		}

		if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

		// Error in calculating shipping cost?
		if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_RMFIRST_INVALID_ZONE;

		// Return shipping information
		return $this->quotes;
	}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_RMFIRST_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		// MODULE_SHIPPING_RMFIRST_VERSION
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Version', 'MODULE_SHIPPING_RMFIRST_VERSION', '2.2.2', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_STATUS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable RM First Class Postage', 'MODULE_SHIPPING_RMFIRST_STATUS', 'True', 'Do you want to offer this shipping option?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMFIRST_TAX_CLASS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_RMFIRST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
		// MODULE_SHIPPING_RMFIRST_SORT_ORDER
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_RMFIRST_SORT_ORDER', '1', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_MIN_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum weight to ship', 'MODULE_SHIPPING_RMFIRST_MIN_WEIGHT', '0', 'Enter the minimum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_MAX_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum weight to ship', 'MODULE_SHIPPING_RMFIRST_MAX_WEIGHT', '10', 'Enter the maximum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_WEIGHT_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum weight to ship', 'MODULE_SHIPPING_RMFIRST_WEIGHT_SPLIT', 'False', 'Do you want to split your shipment by maximum weight to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMFIRST_MIN_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum value to ship', 'MODULE_SHIPPING_RMFIRST_MIN_VALUE', '0', 'Enter the minimum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_MAX_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum value to ship', 'MODULE_SHIPPING_RMFIRST_MAX_VALUE', '46', 'Enter the maximum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMFIRST_VALUE_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum value to ship', 'MODULE_SHIPPING_RMFIRST_VALUE_SPLIT', 'False', 'Do you want to split your shipment by maximum value to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMFIRST_DISPLAY_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery weight', 'MODULE_SHIPPING_RMFIRST_DISPLAY_WEIGHT', 'True', 'Do you want to display the shipping weight? (e.g. Delivery Weight : 2.7674 Kg\'s)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMFIRST_DISPLAY_TIME
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery time', 'MODULE_SHIPPING_RMFIRST_DISPLAY_TIME', 'True', 'Do you want to display the shipping time? (e.g. Ships within 3 to 5 days)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

		for ($i = 1; $i <= $this->num_zones; $i++) {
			$default_countries = '';
			$shipping_table = '';
			$shipping_handling = '';
			if ($i == 1) {
				$default_countries = 'GB';
				$shipping_table = '.1:1.58,.25:1.96,.5:2.48,.75:3.05,1:3.71,1.25:4.9,1.5:5.66,1.75:6.42,2:7.18,4:8.95,6:12,8:15.05,10:18.1';
				$shipping_handling = 0;
			}
			// MODULE_SHIPPING_RMFIRST_COUNTRIES_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Valid ISO Country Codes', 'MODULE_SHIPPING_RMFIRST_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are valid destinations for this method (Default: ".$default_countries.")', '6', '0', now())");
			// MODULE_SHIPPING_RMFIRST_COST_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM First Class Rates', 'MODULE_SHIPPING_RMFIRST_COST_" . $i ."', '" . $shipping_table . "', 'Enter values upto 5,2 decimal places. (12345.67) Example: .1:1,.25:1.27 - Weights less than or equal to 0.1Kg would cost 1.00, Weights less than or equal to 0.25g but more than 0.1Kg will cost 1.27. Do not enter KG or currency symbols.', '6', '0', now())");
			// MODULE_SHIPPING_RMFIRST_HANDLING_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Packaging / Handling Fee', 'MODULE_SHIPPING_RMFIRST_HANDLING_" . $i ."', '" . $shipping_handling . "', 'If you want to add extra costs to customers for jiffy bags etc, the cost can be entered below (eg enter 1.50 for a value of 1.50)', '6', '0', now())");
		}
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('MODULE_SHIPPING_RMFIRST_VERSION','" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		// The order keys are placed in the array decides the order they are displayed on the admin panel
		$keys = array('MODULE_SHIPPING_RMFIRST_STATUS'
				,'MODULE_SHIPPING_RMFIRST_TAX_CLASS'
				,'MODULE_SHIPPING_RMFIRST_SORT_ORDER'
				,'MODULE_SHIPPING_RMFIRST_MIN_WEIGHT'
				,'MODULE_SHIPPING_RMFIRST_MAX_WEIGHT'
				,'MODULE_SHIPPING_RMFIRST_WEIGHT_SPLIT'
				,'MODULE_SHIPPING_RMFIRST_MIN_VALUE'
				,'MODULE_SHIPPING_RMFIRST_MAX_VALUE'
				,'MODULE_SHIPPING_RMFIRST_VALUE_SPLIT'
				,'MODULE_SHIPPING_RMFIRST_DISPLAY_WEIGHT'
				,'MODULE_SHIPPING_RMFIRST_DISPLAY_TIME'
			);
		for ($i=1; $i<=$this->num_zones; $i++) {
			$keys[] = 'MODULE_SHIPPING_RMFIRST_COUNTRIES_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMFIRST_COST_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMFIRST_HANDLING_' . $i;
		}

		return $keys;
	}
}
?>