<?php
/*
  $Id: estimated_shipping.php v1.000 2005-03-28 Wheel of Time Exp $

  Estimated Shipping version 1.000, built for CartStore 2.0

  Copyright (c) 2004-2005 Howard van der Burgt

  GNU General Public License Compatible
*/

////
// Handle the estimated shipping quotes
function tep_get_estimated_shipping_quotes($country, $getvar, $customer_country) {
	global $_POST, $language, $country, $cart, $currencies, $country_to_ship_to, $country_name, $order, $total_weight, $total_count, $estzipcode;

	$return = '';

	if (tep_not_null($country)) {
		$country_name = tep_get_country_name($country);
		tep_session_register('country');
	}

	if (tep_not_null($estzipcode)) {
		tep_session_register('estzipcode');
	}

	$country_to_ship_to = $country;

	require('includes/classes/http_client.php');
	require(DIR_WS_CLASSES . 'estimated_shipping_class.php');
	$order = new order;

	$total_weight = $cart->show_weight();
	$total_count = $cart->count_contents();

	// load all enabled shipping modules
	require(DIR_WS_CLASSES . 'shipping.php');
	$shipping_modules = new shipping;


	/* Free shipping enabled */
	if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
		$pass = false;

		switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
			case 'national':
				if ($order->delivery['country_id'] == STORE_COUNTRY) {
					$pass = true;
				}
				break;
			case 'international':
				if ($order->delivery['country_id'] == STORE_COUNTRY) {
					$pass = true;
				}
				break;
			case 'both':
				$pass = true;
				break;
		}

		$free_shipping = false;
		if ( ($pass == true) && ($order->info['subtotal'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
			$free_shipping = true;

			include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
		}

		/* Free shipping disabled */
	} else {
		$free_shipping = false;
	}


	// process the selected shipping method

	if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

	if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
		if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
			$shipping = $_POST['shipping'];


			list($module, $method) = explode('_', $shipping);
			if ( is_object($$module) || ($shipping == 'usps_free') ) {
				if ($shipping == 'usps_free') {
					$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
					$quote[0]['methods'][0]['cost'] = '0';
				} else {
					$quote = $shipping_modules->quote($method, $module);
				}
				if (isset($quote['error'])) {
					tep_session_unregister('shipping');
				} else {
					if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
						$shipping = array('id' => $shipping,
										'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
										'cost' => $quote[0]['methods'][0]['cost']);
					}
				}
			} else {
				tep_session_unregister('shipping');
			}
		}
	} else {
		$shipping = false;
	}

	// get all available shipping quotes
	$quotes = $shipping_modules->quote();

	if ($free_shipping == true) {
		$quotes[] = array('module' => FREE_SHIPPING_TITLE,
							'methods' => array(array('id' => 'free',
							'title' => sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)),
							'cost' => 0.0)));
	}

	$return .= '<table width="100%" cellpadding="0" cellspacing="0"><th align="left" colspan="2"><span class="smallText">' . TABLE_HEADING_SHIPPING_METHOD . $country_name . '</span></th>';

	for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {

		if (($quotes[$i]['module'] == 'United Parcel Service' || $quotes[$i]['module'] == 'United States Postal Service') && (!tep_not_null($estzipcode))) {
			// skip output when empty zipcode for UPS or USPS
		} else {


	if(isset($_POST['estzipcode']) && $_POST['estzipcode']!=""){

			$return .= '<tr width="100%"><td class="ship_method" align="left" colspan="2" nowrap><h3 class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-transferthick-e-w" ;="" style="float:left"></span>' . $quotes[$i]['module'] . '</h3></td></tr>';

	}




			if (isset($quotes[$i]['error'])) {

			if(isset($_POST['estzipcode']) && $_POST['estzipcode']!=""){

				$return .= '<tr><td class="ship_method2" align="left" colspan="2"><b>' .$quotes[$i]['error'] . '</b></td>';

			}

			} else {
				for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
                    $search = array(' regimark', ' tradmrk');
                    $replace = array('<sup>&reg;</sup>', '<sup>&trade;</sup>');
					$return .= '<tr><td class="ship_service" align="left">' . str_replace($search, $replace, $quotes[$i]['methods'][$j]['title']) . '</td>';
					if ( ($n > 1) || ($n2 > 1) ) {
						 $return .= '<td class="ship_service_price" width="20%" align="right" nowrap>' . $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))) . '</td></tr>';
					} else {
						$return .= '<td class="ship_method" width="20%" align="right" nowrap>&nbsp;&nbsp;&nbsp;' . $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']) . '</td></tr>';
					}
				}
			}
		}
	}

	$return .= '</table><br>';
//	$return .= 'Total including shopping cart: ' . $cart->total;

return $return;
}
?>
