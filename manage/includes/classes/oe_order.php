<?php
/*
  $Id: oe_order.php,v 1.0 2006/10/19 16:23:08 ams Exp $
 
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class oe_order {
    var $info, $totals, $products, $customer, $delivery;

    function oe_order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }
//Begin Order Editor modifications
    function query($order_id) {
      $order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);

      $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array(
	  'title' => $totals['title'], 
	  'text' => $totals['text'], 
	  'class' => $totals['class'], 
	  'value' => $totals['value'],
	  'sort_order' => $totals['sort_order'], 
	  'orders_total_id' => $totals['orders_total_id']);
      }

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
						  'shipping_tax' => $order['shipping_tax'],
                          'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order['orders_status'],
                          'last_modified' => $order['last_modified']);

      $this->customer = array('name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
							  'country_id' => tep_get_country_id($order['customers_country']),
							  'zone_id' => tep_get_zone_id(tep_get_country_id($order['customers_country']), $order['customers_state']),
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
							  'country_id' => tep_get_country_id($order['delivery_country']),
							  'zone_id' => tep_get_zone_id(tep_get_country_id($order['delivery_country']), $order['delivery_state']),
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
							 'country_id' => tep_get_country_id($order['billing_country']),
							 'zone_id' => tep_get_zone_id(tep_get_country_id($order['billing_country']), $order['billing_state']),
                             'format_id' => $order['billing_address_format_id']);


    $countryid = tep_get_country_id($this->delivery["country"]);
	$zoneid = tep_get_zone_id($countryid, $this->delivery["state"]);

    $index = 0;
	$orders_products_query = tep_db_query("
     SELECT 
	 op.orders_products_id, 
	 op.products_name, 
	 op.products_model, 
	 op.products_price,
	 op.products_tax, 
	 op.products_quantity, 
	 op.final_price, 
	 p.products_tax_class_id,
	 p.products_weight,
	 p.products_id
  FROM " . TABLE_ORDERS_PRODUCTS . " op
  LEFT JOIN " . TABLE_PRODUCTS . " p
    ON op.products_id = p.products_id
 WHERE orders_id = '" . (int)$order_id . "'");
 
       while ($orders_products = tep_db_fetch_array($orders_products_query)) {
         $this->products[$index] = array(
		                        'qty' => $orders_products['products_quantity'],
                                'name' => $orders_products['products_name'],
                                'model' => $orders_products['products_model'],
                                'tax' => $orders_products['products_tax'],
        						'tax_description' => tep_get_tax_description($orders_products['products_tax_class_id'], $countryid, $zoneid),
                                'price' => $orders_products['products_price'],
                                'final_price' => $orders_products['final_price'],
								'weight' => $orders_products['products_weight'],
                                //START MOD per visualizzare le quantità dei prodotti disponibili nella riga di descrizione del prodotto
								'magazzino' => $orders_products['products_id'],
							    //END MOD per visualizzare le quantità dei prodotti disponibili nella riga di descrizione del prodotto
								'orders_products_id' => $orders_products['orders_products_id']);
								
 
        $subindex = 0;
        $attributes_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = 
			array('option' => $attributes['products_options'],
                  'value' => $attributes['products_options_values'],
                  'prefix' => $attributes['price_prefix'],
                 'price' => $attributes['options_values_price'],
		  'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);

            $subindex++;
          }
        }
        $index++;
      }
    }
  }
  //end Order Editor
?>
