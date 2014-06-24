<?php
/*
rmsurface.php

Royal Mail Surface

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

class rmsurface {
	var $code, $title, $description, $enabled, $num_zones;

	// class constructor
	function rmsurface() {

		global $order, $total_weight;

		$this->version = '2.2.2';
		$this->code = 'rmsurface';
		$this->title = MODULE_SHIPPING_RMSURFACE_TEXT_TITLE . ' v' . $this->version;
		$this->description = MODULE_SHIPPING_RMSURFACE_TEXT_DESCRIPTION . ' v' . $this->version;
		$this->sort_order = MODULE_SHIPPING_RMSURFACE_SORT_ORDER;
		$this->icon = DIR_WS_ICONS . 'shipping_rmsurface.jpg'; // upload icon to catalog/images/icon directory
		$this->tax_class = MODULE_SHIPPING_RMSURFACE_TAX_CLASS;
		$this->enabled = ((MODULE_SHIPPING_RMSURFACE_STATUS == 'True') ? true : false);
		$this->num_zones = 1;
		$this->weight_split = ((MODULE_SHIPPING_RMSURFACE_WEIGHT_SPLIT == 'True') ? true : false);
		$this->value_split = ((MODULE_SHIPPING_RMSURFACE_VALUE_SPLIT == 'True') ? true : false);

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
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT) {
				$my_max_shipping_weight = MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT;
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
			if ( ($order->info['subtotal'] / $shipping_num_boxes) > MODULE_SHIPPING_RMSURFACE_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMSURFACE_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		// Only ship if packet value exceeds method minimum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) < MODULE_SHIPPING_RMSURFACE_MIN_VALUE) {
			$this->enabled = false;
		}

		// Only ship if packet value does not exceed method maximum value
		if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMSURFACE_MAX_VALUE) {
			$this->enabled = false;
		}

		// Only ship if packet weight exceeds method minimum value
		if ( $my_shipping_weight < MODULE_SHIPPING_RMSURFACE_MIN_WEIGHT) {
			$this->enabled = false;
		}

		// Only ship if packet weight does not exceed method maximum value
		if ( $my_shipping_weight > MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT) {
			$this->enabled = false;
		}

		// Get the destination country
		$dest_country = $order->delivery['country']['iso_code_2']; # Get the destination country

		// Only Non-UK Customers to see shipping method. Hide for UK customers
		// Check if destination is a valid one for this method
		if (defined('MODULE_SHIPPING_RMSURFACE_INVALID_ZONES')) {
			$invalid_zones_table = constant('MODULE_SHIPPING_RMSURFACE_INVALID_ZONES');
			$invalid_zones = preg_split("/[,]/", $invalid_zones_table);
			if (in_array($dest_country, $invalid_zones)) { // Zone Invalid?
				$this->enabled= false;
			}else{ // Destination MUST be a Valid one
				$dest_zone = $this->num_zones;					// Set default shipping zone to last zone available
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
			if (SHIPPING_MAX_WEIGHT > MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT) {
				// Calc the value and weight of each package being shipped
				$my_max_shipping_weight = MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT;
				if ($shipping_weight > $my_max_shipping_weight) {
					$my_shipping_num_boxes = ceil(($shipping_weight * $shipping_num_boxes)/$my_max_shipping_weight);
					$my_shipping_weight = ($shipping_weight * $shipping_num_boxes)/$my_shipping_num_boxes;
				}
			}
		}

		// Split shipment on method Maximum Shipment Value?
		if ($this->value_split) {
			// Calc the value and weight of each package being shipped
			if ( ($order->info['subtotal'] / $my_shipping_num_boxes) > MODULE_SHIPPING_RMSURFACE_MAX_VALUE) {
				$my_shipping_num_boxes = ceil($order->info['subtotal']/MODULE_SHIPPING_RMSURFACE_MAX_VALUE);
				$my_shipping_weight = ($shipping_weight * $shipping_num_boxes) / $my_shipping_num_boxes;
			}
		}

		$shipping = -1;	// Flag no shipping cost available

		// Get the destination country
		$dest_country = $order->delivery['country']['iso_code_2']; # Get the destination country
		$dest_zone = 0; // Flag invalid destination

		// Only Non-UK Customers to see shipping method. Hide for UK customers
		// Check if destination is a valid one for this method
		if (defined('MODULE_SHIPPING_RMSURFACE_INVALID_ZONES')) {
			$invalid_zones_table = constant('MODULE_SHIPPING_RMSURFACE_INVALID_ZONES');
			$invalid_zones = preg_split("/[,]/", $invalid_zones_table);
			if (in_array($dest_country, $invalid_zones)) { // Zone Invalid?
				$dest_zone = 0;
			}else{ // Destination MUST be Valid so find which shipping zone destination belongs to
				$dest_zone = $this->num_zones;					// Set default shipping zone to last zone available
			}
		}

		// Invalid Destination Zone found?
		if ($dest_zone == 0) {
			$shipping_cost = 0;
			$shipping_method = MODULE_SHIPPING_RMSURFACE_INVALID_ZONE;
		}else{ // Destination must be in a valid zone
			// Get the cost to ship to the destination zone
			$shipping = -1;	// Flag no shipping cost available
			$zones_cost = constant('MODULE_SHIPPING_RMSURFACE_COST_' . $dest_zone);
			
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
						if (constant('MODULE_SHIPPING_RMSURFACE_DISPLAY_WEIGHT') == 'True') {
							// Delivery Weight : x items of n.nnnn Kg's
							$shipping_method = MODULE_SHIPPING_RMSURFACE_TEXT_WAY. ' : ';
							if ($my_shipping_num_boxes > 1) { // Shipment split between several boxes/packets
								$shipping_method = $shipping_method . $shipping_num_boxes . ' '.MODULE_SHIPPING_RMSURFACE_TEXT_ITEMS.' ';
							}else{
								$shipping_method = $shipping_method . $shipping_num_boxes . ' '.MODULE_SHIPPING_RMSURFACE_TEXT_ITEM.' ';
							}
							$shipping_method = $shipping_method . $shipping_weight. ' ' . MODULE_SHIPPING_RMSURFACE_TEXT_UNITS;
						}
						// Display delivery times?
						if (constant('MODULE_SHIPPING_RMSURFACE_DISPLAY_TIME') == 'True') {
							// Ships within 3 to 5 days
						$shipping_method = $shipping_method . ' (';
						$shipping_method = $shipping_method . ucfirst(MODULE_SHIPPING_RMSURFACE_DELIVERY_SHIPPING_TIME) . ')';
						}
					}
					break;
				}
			}
		}

		// No shipping cost found?
		if ($shipping == -1) {
			$shipping_cost = 0;
			$shipping_method = MODULE_SHIPPING_RMSURFACE_UNDEFINED_RATE;
		// Shipping charge found, so add together all costs
		} else {
			$shipping_cost = ($shipping * $my_shipping_num_boxes) + constant('MODULE_SHIPPING_RMSURFACE_HANDLING_' . $this->num_zones);
		}

		$this->quotes = array('id' => $this->code,
			'module' => MODULE_SHIPPING_RMSURFACE_TEXT_TITLE,
			'methods' => array(array('id' => $this->code,
			'title' => $shipping_method,
			'cost' => $shipping_cost)));
	
		// Need to apply tax ?
		if ($this->tax_class > 0) {
			$this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		}

		if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
	
		if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_RMSURFACE_INVALID_ZONE;
	
		return $this->quotes;
		}

	function check() {
		if (!isset($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_RMSURFACE_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}

	function install() {
		// MODULE_SHIPPING_RMSURFACE_VERSION
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Version', 'MODULE_SHIPPING_RMSURFACE_VERSION', '2.2.2', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_STATUS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable RM Surface', 'MODULE_SHIPPING_RMSURFACE_STATUS', 'True', 'Do you want to offer this shipping option?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_TAX_CLASS
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_RMSURFACE_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
		// MODULE_SHIPPING_RMSURFACE_SORT_ORDER
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_RMSURFACE_SORT_ORDER', '11', 'Sort order of display (1 shown first 99 etc shown last to customer)', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_MIN_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum weight to ship', 'MODULE_SHIPPING_RMSURFACE_MIN_WEIGHT', '0', 'Enter the minimum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum weight to ship', 'MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT', '2', 'Enter the maximum weight to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_WEIGHT_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum weight to ship', 'MODULE_SHIPPING_RMSURFACE_WEIGHT_SPLIT', 'False', 'Do you want to split your shipment by maximum weight to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_MIN_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum value to ship', 'MODULE_SHIPPING_RMSURFACE_MIN_VALUE', '0', 'Enter the maximum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_MAX_VALUE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum value to ship', 'MODULE_SHIPPING_RMSURFACE_MAX_VALUE', '46', 'Enter the maximum value to ship', '6', '0', now())");
		// MODULE_SHIPPING_RMSURFACE_VALUE_SPLIT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Split shipments on maximum value to ship', 'MODULE_SHIPPING_RMSURFACE_VALUE_SPLIT', 'False', 'Do you want to split your shipment by maximum value to ship?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_DISPLAY_WEIGHT
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery weight', 'MODULE_SHIPPING_RMSURFACE_DISPLAY_WEIGHT', 'True', 'Do you want to display the shipping weight? (e.g. Delivery Weight : 2.7674 Kg\'s)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_DISPLAY_INSURANCE
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Insurance', 'MODULE_SHIPPING_RMSURFACE_DISPLAY_INSURANCE', 'True', 'Do you want to display the shipping insurance? (e.g. Insured upto &pound;500)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_DISPLAY_TIME
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display delivery time', 'MODULE_SHIPPING_RMSURFACE_DISPLAY_TIME', 'True', 'Do you want to display the shipping time? (e.g. Ships within 3 to 5 days)', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		// MODULE_SHIPPING_RMSURFACE_INVALID_ZONES
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Invalid ISO Country Codes', 'MODULE_SHIPPING_RMSURFACE_INVALID_ZONES', 'GB', 'Enter the two digit ISO code for which this shipping method does NOT apply to. (Default: GB)', '6', '0', now())");

		for ($i = 1; $i <= $this->num_zones; $i++) {
			$default_countries = '';
			$shipping_table = '';
			$shipping_handling = '';
			if ($i == 1) {
				$default_countries = 'All Destinations'; // this must be the lastest zone
				$shipping_table = '.1:1.12,.15:1.5,.2:1.89,.25:2.28,.3:2.64,.35:3.02,.4:3.42,.45:3.79,.5:4.16,.55:4.5,.6:4.84,.65:5.18,.7:5.52,.75:5.86,.8:6.2,.85:6.54,.9:6.88,.95:7.22,1:7.56,1.1:8.24,1.2:8.92,1.3:9.6,1.4:10.28,1.5:10.96,1.6:11.64,1.7:12.32,1.8:13,1.9:13.68,2:14.36';
				$shipping_handling = 0;
			// MODULE_SHIPPING_RMSURFACE_COUNTRIES_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Surface Zone ".$i." Valid ISO Country Codes', 'MODULE_SHIPPING_RMSURFACE_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Zone destinations (Default: ".$default_countries.")', '6', '0', now())");
			}
			// MODULE_SHIPPING_RMSURFACE_COST_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Surface Zone ".$i." Rates', 'MODULE_SHIPPING_RMSURFACE_COST_" . $i ."', '" . $shipping_table . "', 'Enter values upto 5,2 decimal places. (12345.67) Example: 2:4.2,4:6.85 - Weights less than or equal to 2Kg would cost 4.20, Weights less than or equal to 4Kg but more than 2Kg will cost 6.85. Do not enter KG or currency symbols.', '6', '0', now())");
			// MODULE_SHIPPING_RMSURFACE_HANDLING_$i
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('RM Surface Zone ".$i." Packaging / Handling Fee', 'MODULE_SHIPPING_RMSURFACE_HANDLING_" . $i ."', '" . $shipping_handling . "', 'If you want to add extra costs to customers for jiffy bags etc, the cost can be entered below (eg enter 1.50 for a value of 1.50)', '6', '0', now())");
		}
	}

	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('MODULE_SHIPPING_RMSURFACE_VERSION','" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		$keys = array('MODULE_SHIPPING_RMSURFACE_STATUS'
				,'MODULE_SHIPPING_RMSURFACE_TAX_CLASS'
				,'MODULE_SHIPPING_RMSURFACE_SORT_ORDER'
				,'MODULE_SHIPPING_RMSURFACE_MIN_WEIGHT'
				,'MODULE_SHIPPING_RMSURFACE_MAX_WEIGHT'
				,'MODULE_SHIPPING_RMSURFACE_WEIGHT_SPLIT'
				,'MODULE_SHIPPING_RMSURFACE_MIN_VALUE'
				,'MODULE_SHIPPING_RMSURFACE_MAX_VALUE'
				,'MODULE_SHIPPING_RMSURFACE_VALUE_SPLIT'
				,'MODULE_SHIPPING_RMSURFACE_DISPLAY_WEIGHT'
				,'MODULE_SHIPPING_RMSURFACE_DISPLAY_INSURANCE'
				,'MODULE_SHIPPING_RMSURFACE_DISPLAY_TIME'
				,'MODULE_SHIPPING_RMSURFACE_INVALID_ZONES'
			);
		for ($i=1; $i<=$this->num_zones; $i++) {
			$keys[] = 'MODULE_SHIPPING_RMSURFACE_COUNTRIES_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMSURFACE_COST_' . $i;
			$keys[] = 'MODULE_SHIPPING_RMSURFACE_HANDLING_' . $i;
		}
		return $keys;
	}
}
?>