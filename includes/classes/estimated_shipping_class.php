<?php
/*
  $Id: estimated_shipping_class.php,v 1.0 2005/03/28
  Derived from order.php to be able to have an estimate shipping calculation

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

	class order {
		var $info, $totals, $products, $customer, $delivery, $content_type;

	function order($order_id = '') {
		$this->info = array();
		$this->totals = array();
		$this->products = array();
		$this->customer = array();
		$this->delivery = array();

		$this->cart();
	}

function cart() {
	global $customer_id, $sendto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $payment, $country_to_ship_to, $country_name, $estzipcode;

	$this->content_type = $cart->get_content_type();


	$this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
						'currency' => $currency,
						'currency_value' => $currencies->currencies[$currency]['value'],
						'payment_method' => $payment,
						'cc_type' => (isset($GLOBALS['cc_type']) ? $GLOBALS['cc_type'] : ''),
						'cc_owner' => (isset($GLOBALS['cc_owner']) ? $GLOBALS['cc_owner'] : ''),
						'cc_number' => (isset($GLOBALS['cc_number']) ? $GLOBALS['cc_number'] : ''),
						'cc_expires' => (isset($GLOBALS['cc_expires']) ? $GLOBALS['cc_expires'] : ''),
						'shipping_method' => $shipping['title'],
						'shipping_cost' => $shipping['cost'],
						'subtotal' => 0,
						'tax' => 0,
						'tax_groups' => array(),
						'comments' => (isset($GLOBALS['comments']) ? $GLOBALS['comments'] : ''));

	if (isset($GLOBALS[$payment]) && is_object($GLOBALS[$payment])) {
		$this->info['payment_method'] = $GLOBALS[$payment]->title;

		if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) {
			$this->info['order_status'] = $GLOBALS[$payment]->order_status;
		}
	}

	$country_iso = tep_get_countries_with_iso_codes($country_to_ship_to);

	$this->customer = array('firstname' => 'dummy',
							'lastname' => 'dummy',
							'company' => 'dummy',
							'street_address' => 'dummy',
							'suburb' => 'dummy',
							'city' => 'dummy',
							'postcode' => $estzipcode,
							'state' => 'dummy',
							'zone_id' => '0',
							'country' => array('id' => $country_to_ship_to,
							'title' => $country_name,
							'iso_code_2' => $country_iso['countries_iso_code_2'],
							'iso_code_3' => $country_iso['countries_iso_code_3']),
							'format_id' => tep_get_address_format_id($country_to_ship_to),
							'telephone' => 'dummy',
							'email_address' => 'dummy');

	$this->delivery = array('firstname' => 'dummy',
							'lastname' => 'dummy',
							'company' => 'dummy',
							'street_address' => '',
							'suburb' => '',
							'city' => '',
							'postcode' => $estzipcode,
							'state' => '',
							'zone_id' => '0',
							'country' => array('id' => $country_to_ship_to,
							'title' => $country_name,
							'iso_code_2' => $country_iso['countries_iso_code_2'],
							'iso_code_3' => $country_iso['countries_iso_code_3']),
							'country_id' => $country_to_ship_to,
							'format_id' => tep_get_address_format_id($country_to_ship_to));

	$this->billing = array('firstname' => 'dummy',
							'lastname' => 'dummy',
							'company' => 'dummy',
							'street_address' => 'dummy',
							'suburb' => 'dummy',
							'city' => 'dummy',
							'postcode' => $estzipcode,
							'state' => 'dummy',
							'zone_id' => '0',
							'country' => array('id' => $country_to_ship_to,
							'title' => $country_name,
							'iso_code_2' => $country_iso['countries_iso_code_2'],
							'iso_code_3' => $country_iso['countries_iso_code_3']),
							'country_id' => $country_to_ship_to,
							'format_id' => tep_get_address_format_id($country_to_ship_to));

	$index = 0;
	$products = $cart->get_products();
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {

		$this->products[$index] = array('qty' => $products[$i]['quantity'],
										'name' => $products[$i]['name'],
										'model' => $products[$i]['model'],
										'tax' => tep_get_tax_rate($products[$i]['tax_class_id'], $country_to_ship_to, '0'),
										'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $country_to_ship_to, '0'),
										'price' => $products[$i]['price'],
										'cost' => $products[$i]['cost'],
										'final_price' => $products[$i]['price'] + $cart->attributes_price($products[$i]['id']),
										'weight' => $products[$i]['weight'],
										'id' => $products[$i]['id']);

		if ($products[$i]['attributes']) {
			$subindex = 0;
			reset($products[$i]['attributes']);
			while (list($option, $value) = each($products[$i]['attributes'])) {
				$attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
				$attributes = tep_db_fetch_array($attributes_query);

				$this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
																		 'value' => $attributes['products_options_values_name'],
																		 'option_id' => $option,
																		 'value_id' => $value,
																		 'prefix' => $attributes['price_prefix'],
																		 'price' => $attributes['options_values_price']);

			$subindex++;
			}
		}

		$shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax']) * $this->products[$index]['qty'];
		$this->info['subtotal'] += $shown_price;

		$products_tax = $this->products[$index]['tax'];
		$products_tax_description = $this->products[$index]['tax_description'];
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
			$this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
			if (isset($this->info['tax_groups']["$products_tax_description"])) {
				$this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
			} else {
				$this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
			}
		} else {
			$this->info['tax'] += ($products_tax / 100) * $shown_price;
			if (isset($this->info['tax_groups']["$products_tax_description"])) {
				$this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
			} else {
				$this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
			}
		}

		$index++;
	}

	if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
	} else {
		$this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
	}
}
}
?>
