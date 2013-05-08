<?php
/*
  $Id: order.php,v 1.7 2003/06/20 16:23:08 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class order {
    var $info, $totals, $products, $customer, $delivery;

    function order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {
     // $order_query = tep_db_query("select customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");

        // PWA BOF
      $order_query = tep_db_query("select customers_id, customers_name, quickbooksid, customers_company, customers_street_address,customers_street_address_2, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_street_address_2, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_street_address_2, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, usps_track_num, usps_track_num2, ups_track_num, ups_track_num2, fedex_track_num, fedex_track_num2, dhl_track_num, dhl_track_num2, currency, currency_value, date_purchased, orders_status, last_modified,delivery_date,delivery_time_slotid from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      // PWA EOF

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
                          'quickbooksid' => $order['quickbooksid'],

						  //Package Tracking Plus BEGIN
                          'usps_track_num' => $order['usps_track_num'],
                          'usps_track_num2' => $order['usps_track_num2'],
                          'ups_track_num' => $order['ups_track_num'],
                          'ups_track_num2' => $order['ups_track_num2'],
                          'fedex_track_num' => $order['fedex_track_num'],
                          'fedex_track_num2' => $order['fedex_track_num2'],
                          'dhl_track_num' => $order['dhl_track_num'],
                          'dhl_track_num2' => $order['dhl_track_num2'],
//Package Tracking Plus END
                          'last_modified' => $order['last_modified']);

      /*$this->customer = array('name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);
*/
// PWA BOF
      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                               'street_address_2' => $order['customers_street_address_2'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
							  'delivery_date'=>$order['delivery_date'],
							  'delivery_slotid'=>$order['delivery_time_slotid'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);
      // PWA EOF

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'street_address_2' => $order['delivery_street_address_2'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'street_address_2' => $order['billing_street_address_2'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);
							     $countryid = tep_get_country_id($this->delivery["country"]);
	$zoneid = tep_get_zone_id($countryid, $this->delivery["state"]);
							 //MVS Start
      $orders_shipping_id = '';
      $check_new_vendor_data_query = tep_db_query("select orders_shipping_id, orders_id,

vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost,

vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id

. "'");
      while ($checked_data = tep_db_fetch_array($check_new_vendor_data_query)) {
      	$this->orders_shipping_id = $checked_data['orders_shipping_id'];
      	      	//$orders_vendor_name = $checked_data['vendors_name'];
      	      	}
         if (tep_not_null($this->orders_shipping_id)) {
        /* MVS  */
 $index2 = 0;
              //let's get the Vendors
              $vendor_data_query = tep_db_query("select orders_shipping_id, orders_id,

vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost, shipping_tax,

vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id

. "'");
              while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {




    $this->products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                               'Vname' => $vendor_order['vendors_name'],
                                            'Vmodule' => $vendor_order['shipping_module'],
                                            'Vmethod' => $vendor_order['shipping_method'],
                                            'Vcost' => $vendor_order['shipping_cost'],
                                            'Vship_tax' => $vendor_order['shipping_tax'],
											'Vorders_shipping_id' => $vendor_order['orders_shipping_id'],
                                            'Vorder_sent' =>$vendor_order['vendor_order_sent'], //a yes=sent a no=not sent
                                            'Vnoname' => 'Shipper',
                                                 'spacer' => '-');

                                   $index = 0;
    $orders_products_query = tep_db_query("
	SELECT
	 op.orders_products_id,
	  op.products_returned,
	  op.products_id,
	 op.products_name,
	 op.products_model,
	 op.products_price,
	 op.products_tax,
	 op.products_quantity,
	 op.final_price,
	 op.vendors_id,
	 p.products_tax_class_id,
	 p.products_weight
  FROM " . TABLE_ORDERS_PRODUCTS . " op
  LEFT JOIN " . TABLE_PRODUCTS . " p
  ON op.products_id = p.products_id
  WHERE orders_id = '" . (int)$order_id . "'
  AND op.vendors_id = '" . (int)$vendor_order['vendors_id'] . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
      $this->products[$index2]['orders_products'][$index] = array('qty' =>
$orders_products['products_quantity'],
                                      'name' => $orders_products['products_name'],
									   'id' => $orders_products['products_id'],
      'return' => $orders_products['products_returned'],

                                      'tax' => $orders_products['products_tax'],
									  'tax_description' => tep_get_tax_description($orders_products['products_tax_class_id'], $countryid, $zoneid),
                                      'model' => $orders_products['products_model'],
                                      'price' => $orders_products['products_price'],
                                      'vendor_name' => $orders_products['vendors_name'],
                                      'vendor_ship' => $orders_products['shipping_module'],
                                      'shipping_method' =>

$orders_products['shipping_method'],
                                      'shipping_cost' => $orders_products['shipping_cost'],
                                      'final_price' => $orders_products['final_price'],
									  'weight' => $orders_products['products_weight'],
							'orders_products_id' => $orders_products['orders_products_id'],
                                      'spacer' => '-');
                                   //MVS end
        $subindex = 0;
     $attributes_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index2]['orders_products'][$index]['attributes'][$subindex] =
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
     $index2++;
    }
         } else {     // old order, use the regular osC data
//MVS End


      $index = 0;
     $orders_products_query = tep_db_query("
   SELECT
	 op.orders_products_id,
	 op.products_name,
	 op.products_returned,
	 op.products_id,
	 op.products_model,
	 op.products_price,
	 op.products_tax,
	 op.products_quantity,
	 op.final_price,
	 p.products_tax_class_id,
	 p.products_weight
  FROM " . TABLE_ORDERS_PRODUCTS . " op
  LEFT JOIN " . TABLE_PRODUCTS . " p
  ON op.products_id = p.products_id
  WHERE orders_id = '" . (int)$order_id . "'");

       while ($orders_products = tep_db_fetch_array($orders_products_query)) {
         $this->products[$index] = array('qty' => $orders_products['products_quantity'],
                                         'name' => $orders_products['products_name'],
										  'id' => $orders_products['products_id'],
      'return' => $orders_products['products_returned'],

                                         'model' => $orders_products['products_model'],
                                         'tax' => $orders_products['products_tax'],
        'tax_description' => tep_get_tax_description($orders_products['products_tax_class_id'], $countryid, $zoneid),
                                         'price' => $orders_products['products_price'],
                                         'final_price' => $orders_products['final_price'],
										 'weight' => $orders_products['products_weight'],
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
//MVS Start
}
//MVS End
?>
