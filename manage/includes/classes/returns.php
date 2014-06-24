<?php
/*
  $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery;

    function order($returns_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($returns_id);
    }

    function query($returns_id) {
// purchaseorder added account_name, account_number, po_number
      $order_query = tep_db_query("select rma_value, order_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, cvvnumber, comments, currency, currency_value, date_purchased, returns_status, last_modified, customers_fax, account_name, account_number, po_number, returns_status from " . TABLE_RETURNS . " where returns_id = '" . tep_db_input($returns_id) . "'");
//    $order_query = tep_db_query("select customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, comments, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($order_id) . "'");
      $order = tep_db_fetch_array($order_query);

      $refund_payment_query= tep_db_query("SELECT * from " . TABLE_RETURN_PAYMENTS . " where returns_id = '" . tep_db_input($returns_id) . "'");
      $refund_payment = tep_db_fetch_array($refund_payment_query);
      

    /*  $totals_query = tep_db_query("select title, text from " . TABLE_RETUR_TOTAL . " where returns_id = '" . tep_db_input($returns_id) . "' order by sort_order");
     while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text']);
      }  */

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'rma_value' => $order['rma_value'],
                          'order_id' => $order['order_id'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],

                          'comments' => $order['comments'],
                         'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order['returns_status'],
                          'date_finished' => $order['date_finished'],
                          //
                          'customer_method' => $refund_payment['customer_method'],
                          'department' => $refund_payment['refund_payment_name'],
                          'payment_reference' => $refund_payment['refund_payment_reference'],
                          'refund_amount' => $refund_payment['refund_payment_value'],
                          'refund_date' => $refund_payment['refund_payment_date'],
                          //
                            'last_modified' => $order['last_modified']);

      $this->customer = array('name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'fax' => $order['customers_fax'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

// changed by manOn

// BEGIN - Product Serial Numbers
      $orders_products_query = tep_db_query("select returns_products_id, products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price, products_serial_number, products_discount_made  from " . TABLE_RETURNS_PRODUCTS_DATA . " where returns_id ='" . tep_db_input($returns_id) . "'");
// END - Product Serial Numbers

     while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $this->products = array('qty' => $orders_products['products_quantity'],
                                        'name' => $orders_products['products_name'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        //'final_price' => $orders_products['final_price'],
                                        'id' => $orders_products['products_id'],


                                        'final_price' => $orders_products['products_price']);

      }
    }
  }
?>
