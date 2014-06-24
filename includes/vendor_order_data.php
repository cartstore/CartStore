<?php
/*
  $Id: vendor_order_data.php V1.1
  By Craig Garrison Sr. (craig@blucollarsales.com) for Multi-Vendor Shipping
  for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

 $index2 = 0;
              //let's get the Vendors
              $vendor_data_query = tep_db_query("select orders_shipping_id, orders_id, vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id . "'");
	
              while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {

    $these_products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                     'Vname' => $vendor_order['vendors_name'],
                                     'Vmodule' => $vendor_order['shipping_module'],
                                     'Vmethod' => $vendor_order['shipping_method'],
                                     'Vcost' => $vendor_order['shipping_cost'],
                                     'Vnoname' => 'Shipper',
                                     'spacer' => '-');

                                   $index = 0;
    $orders_products_query = tep_db_query("select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price, vendors_id from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' and vendors_id = '" . (int)$vendor_order['vendors_id'] . "'");

      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
      $these_products[$index2]['orders_products'][$index] = array('qty' => $orders_products['products_quantity'],
                                      'name' => $orders_products['products_name'],
                                      'tax' => $orders_products['products_tax'],
                                      'model' => $orders_products['products_model'],
                                      'price' => $orders_products['products_price'],
                                      'vendor_name' => $orders_products['vendors_name'],
                                      'vendor_ship' => $orders_products['shipping_module'],
                                      'shipping_method' => $orders_products['shipping_method'],
                                      'shipping_cost' => $orders_products['shipping_cost'],
                                      'final_price' => $orders_products['final_price'],
                                      'spacer' => '-');

        $subindex = 0;
      $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
          $these_products[$index2]['orders_products'][$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                     'value' => $attributes['products_options_values'],
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }
        $index++;
      }
     $index2++;
    }
    // let's build the email confirmation without html
    $package_num = sizeof($these_products);
$box_num = $l + 1;
$products_ordered .= '' . "\n\t";
 for ($l=0, $m=sizeof($these_products); $l<$m; $l++) {
$products_ordered .=
  "\n" . 'Shipment Number ' . $box_num++ . ' '  .
       $these_products[$l]['spacer'] . ' ' .
       $these_products[$l]['Vmodule'] . ' ' .
       $these_products[$l]['spacer'] . ' ' .
       $these_products[$l]['Vmethod'] . ' ' .
       $these_products[$l]['spacer'] . ' ' .
       $these_products[$l]['Vcost'] . "\n\t";
           for ($i=0, $n=sizeof($these_products[$l]['orders_products']); $i<$n; $i++) {
      $products_ordered .= "\n\t" .
           $these_products[$l]['orders_products'][$i]['qty'] . '  x  ' . ' ' .
           $these_products[$l]['orders_products'][$i]['name'] . ' ' .
           $these_products[$l]['spacer'] . ' ' .
           $these_products[$l]['orders_products'][$i]['model'] . "\n\t";

      if (isset($these_products[$l]['orders_products'][$i]['attributes']) && (sizeof($these_products[$l]['orders_products'][$i]['attributes']) > 0)) {

        for ($j = 0, $k = sizeof($these_products[$l]['orders_products'][$i]['attributes']); $j < $k; $j++) {
         $products_ordered .= "\n\t" . 'Options Selected - ' . $these_products[$l]['orders_products'][$i]['attributes'][$j]['option'] . ': ' . $these_products[$l]['orders_products'][$i]['attributes'][$j]['value'];
          if ($these_products[$l]['orders_products'][$i]['attributes'][$j]['price'] != '0')                    $products_ordered .= ' (' . $these_products[$l]['orders_products'][$i]['attributes'][$j]['prefix'] . $currencies->format($these_products[$l]['orders_products'][$i]['attributes'][$j]['price'] * $these_products[$l]['orders_products'][$i]['qty'], true, $these_info['currency'], $these_info['currency_value']) . ')' . "\n\t";
        }
      }
                    $products_ordered .=  "\n\t"  . 'Tax ' . tep_display_tax_value($these_products[$l]['orders_products'][$i]['tax']) . '%  ' . "\n\t"  .
           'Price Per Item - '  . $currencies->format($these_products[$l]['orders_products'][$i]['final_price'], true, $these_info['currency'], $these_info['currency_value']) . "\n\t" .
           'Total Without Tax - '  . $currencies->format($these_products[$l]['orders_products'][$i]['final_price'] * $these_products[$l]['orders_products'][$i]['qty'], true, $these_info['currency'], $these_info['currency_value']) . "\n\t" .
           'Total Including Tax - '  . $currencies->format(tep_add_tax($these_products[$l]['orders_products'][$i]['final_price'], $these_products[$l]['orders_products'][$i]['tax']) * $these_products[$l]['orders_products'][$i]['qty'], true, $these_info['currency'], $these_info['currency_value']) . "\n\t";
     }
    }
    ?>