<?php
/*
$Id: paypal_ipn.php,v 1.1.2.2 2005/08/04 06:05:08 Michael Sasek Exp $

osCMax Power E-Commerce
http://oscdox.com

Copyright © 2004 osCommerce

Released under the GNU General Public License
*/

class paypal_ipn {
var $code, $title, $description, $enabled, $identifier;

// class constructor
function paypal_ipn() {
global $order;

$this->code = 'paypal_ipn';
$this->title = MODULE_PAYMENT_PAYPAL_IPN_TEXT_TITLE;
$this->description = MODULE_PAYMENT_PAYPAL_IPN_TEXT_DESCRIPTION;
$this->sort_order = MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER;
$this->enabled = ((MODULE_PAYMENT_PAYPAL_IPN_STATUS == 'True') ? true : false);
$this->identifier = 'osCommerce PayPal IPN v1.0';

if ((int)MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID > 0) {
$this->order_status = MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID;
}

if (is_object($order)) $this->update_status();


if (MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER == 'Live') {
$this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';
} else {
$this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}
}


// class methods
function update_status() {
global $order;

if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPAL_IPN_ZONE > 0) ) {
$check_flag = false;
$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_IPN_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
while ($check = tep_db_fetch_array($check_query)) {
if ($check['zone_id'] < 1) {
$check_flag = true;
break;
} elseif ($check['zone_id'] == $order->billing['zone_id']) {
$check_flag = true;
break;
}
}

if ($check_flag == false) {
$this->enabled = false;
}
}
}

function javascript_validation() {
return false;
}

 /*function selection() {
return array('id' => $this->code,
'module' => $this->title);
}*/
 function selection() {
      global $cart_PayPal_Standard_ID;

      if (tep_session_is_registered('cart_PayPal_Standard_ID')) {
        $order_id = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);

        $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');

        if (tep_db_num_rows($check_query) < 1) {
          tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');

          tep_session_unregister('cart_PayPal_Standard_ID');
        }
      }

      return array('id' => $this->code,
                   #'module' => $this->public_title);
                   'module' => $this->title);
                    }

/*function pre_confirmation_check() {
return false;
}*/
function pre_confirmation_check() {
      global $cartID, $cart;

      if (empty($cart->cartID)) {
        $cartID = $cart->cartID = $cart->generate_cart_id();
      }

      if (!tep_session_is_registered('cartID')) {
        tep_session_register('cartID');
      }
    }

function confirmation() {
global $cartID, $cart_PayPal_Standard_ID, $customer_id, $languages_id, $order, $order_total_modules;

#if (tep_session_is_registered('cartID')) {
if (array_key_exists('cartID', $_SESSION)) {
$insert_order = false;

/*if (tep_session_is_registered('cart_PayPal_Standard_ID')) {
$order_id = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);
*/
 if (tep_session_is_registered('cart_PayPal_Standard_ID')) {
    $order_id = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);
          
$curr_check = tep_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
$curr = tep_db_fetch_array($curr_check);

if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($cart_PayPal_Standard_ID, 0, strlen($cartID))) ) {
$check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');

if (tep_db_num_rows($check_query) < 1) {
tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');
}

$insert_order = true;
}
} else {
$insert_order = true;
}

if ($insert_order == true) {
$order_totals = array();
if (is_array($order_total_modules->modules)) {
reset($order_total_modules->modules);
while (list(, $value) = each($order_total_modules->modules)) {
$class = substr($value, 0, strrpos($value, '.'));
if ($GLOBALS[$class]->enabled) {
for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
$order_totals[] = array('code' => $GLOBALS[$class]->code,
                        'title' => $GLOBALS[$class]->output[$i]['title'],
                        'text' => $GLOBALS[$class]->output[$i]['text'],
                        'value' => $GLOBALS[$class]->output[$i]['value'],
                        'sort_order' => $GLOBALS[$class]->sort_order);
          }
       }
    }
  }
}
// Vor CAO
$sql_data_array = array('customers_id' => $customer_id,
'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
'customers_company' => $order->customer['company'],
'customers_street_address' => $order->customer['street_address'],
'customers_suburb' => $order->customer['suburb'],
'customers_city' => $order->customer['city'],
'customers_postcode' => $order->customer['postcode'],
'customers_state' => $order->customer['state'],
'customers_country' => $order->customer['country']['title'],
'customers_telephone' => $order->customer['telephone'],
'customers_email_address' => $order->customer['email_address'],
'customers_address_format_id' => $order->customer['format_id'],
'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
'delivery_company' => $order->delivery['company'],
'delivery_street_address' => $order->delivery['street_address'],
'delivery_suburb' => $order->delivery['suburb'],
'delivery_city' => $order->delivery['city'],
'delivery_postcode' => $order->delivery['postcode'],
'delivery_state' => $order->delivery['state'],
'delivery_country' => $order->delivery['country']['title'],
'delivery_address_format_id' => $order->delivery['format_id'],
'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
'billing_company' => $order->billing['company'],
'billing_street_address' => $order->billing['street_address'],
'billing_suburb' => $order->billing['suburb'],
'billing_city' => $order->billing['city'],
'billing_postcode' => $order->billing['postcode'],
'billing_state' => $order->billing['state'],
'billing_country' => $order->billing['country']['title'],
'billing_address_format_id' => $order->billing['format_id'],
'payment_method' => $order->info['payment_method'],
'cc_type' => $order->info['cc_type'],
'cc_owner' => $order->info['cc_owner'],
'cc_number' => $order->info['cc_number'],
'cc_expires' => $order->info['cc_expires'],
'date_purchased' => 'now()',
'orders_status' => $order->info['order_status'],
'currency' => $order->info['currency'],
'currency_value' => $order->info['currency_value']);

tep_db_perform(TABLE_ORDERS, $sql_data_array);

$insert_id = tep_db_insert_id();

for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
$sql_data_array = array('orders_id' => $insert_id,
'title' => $order_totals[$i]['title'],
'text' => $order_totals[$i]['text'],
'value' => $order_totals[$i]['value'],
'class' => $order_totals[$i]['code'],
'sort_order' => $order_totals[$i]['sort_order']);

tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
}

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
$sql_data_array = array('orders_id' => $insert_id,
'products_id' => tep_get_prid($order->products[$i]['id']),
'products_model' => $order->products[$i]['model'],
'products_name' => $order->products[$i]['name'],
'products_price' => $order->products[$i]['price'],
'final_price' => $order->products[$i]['final_price'],
'products_tax' => $order->products[$i]['tax'],
'products_quantity' => $order->products[$i]['qty']);

tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

$order_products_id = tep_db_insert_id();

$attributes_exist = '0';
if (isset($order->products[$i]['attributes'])) {
$attributes_exist = '1';
for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
if (DOWNLOAD_ENABLED == 'true') {
$attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
on pa.products_attributes_id=pad.products_attributes_id
where pa.products_id = '" . $order->products[$i]['id'] . "'
and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
and pa.options_id = popt.products_options_id
and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
and pa.options_values_id = poval.products_options_values_id
and popt.language_id = '" . $languages_id . "'
and poval.language_id = '" . $languages_id . "'";
$attributes = tep_db_query($attributes_query);
} else {
$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
}
$attributes_values = tep_db_fetch_array($attributes);

/*$sql_data_array = array('orders_id' => $insert_id,
'orders_products_id' => $order_products_id,
'products_options' => $attributes_values['products_options_name'],
'products_options_values' => $attributes_values['products_options_values_name'],
'options_values_price' => $attributes_values['options_values_price'],
'price_prefix' => $attributes_values['price_prefix']);

tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);*/

/*
// BOF Option Type Feature
$attr_name = $attributes_values['products_options_name'];
if ($attributes_values['products_options_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
$attr_name_sql_raw = 'SELECT po.products_options_name, poval.products_options_values_name FROM
' . TABLE_PRODUCTS_OPTIONS . ' po,
' . TABLE_PRODUCTS_OPTIONS_VALUES . ' poval,
' . TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
' pa.products_id="' . tep_get_prid($order->products[$i]['id']) . '" AND ' .
' pa.options_id="' . $order->products[$i]['attributes'][$j]['option_id'] . '" AND ' .
' pa.options_id=po.products_options_id AND ' .
' poval.products_options_values_id=po.products_options_id and '.
' po.language_id="' . $languages_id . '" ';
$attr_name_sql = tep_db_query($attr_name_sql_raw);
if ($arr = tep_db_fetch_array($attr_name_sql)) {
$attr_name = $arr['products_options_name'];
$attr_values_name = $arr['products_options_values_name'];
}
}
//EOF Option Type Feature
$sql_data_array = array('orders_id' => $insert_id,
'orders_products_id' => $order_products_id,
// BOF Option Type Feature
//'products_options' => $attributes_values['products_options_name'],
//'products_options_values' => $attributes_values['products_options_values_name'],
'products_options' => $attr_name,
'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
#'products_options_values' => $attr_values_name,
// EOF Option Type Feature
'options_values_price' => $attributes_values['options_values_price'],
'price_prefix' => $attributes_values['price_prefix']);
tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
*/
//------insert customer choosen option to order--------
            $attributes_exist = '0';
            if (isset($order->products[$i]['attributes'])) {
              $attributes_exist = '1';
              for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                if (DOWNLOAD_ENABLED == 'true') {
                  $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                       from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                       left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                       on pa.products_attributes_id=pad.products_attributes_id
                                       where pa.products_id = '" . $order->products[$i]['id'] . "'
                                       and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'";
                  $attributes = tep_db_query($attributes_query);
                } else {
                  $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
                }
                $attributes_values = tep_db_fetch_array($attributes);

                $sql_data_array = array('orders_id' => $insert_id,
                                        'orders_products_id' => $order_products_id,
                                        'products_options' => $attributes_values['products_options_name'],
                                        'products_options_values' => $attributes_values['products_options_values_name'],
                                        'options_values_price' => $attributes_values['options_values_price'],
                                        'price_prefix' => $attributes_values['price_prefix']);

                tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

                if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
                  $sql_data_array = array('orders_id' => $insert_id,
                                          'orders_products_id' => $order_products_id,
                                          'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                          'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                          'download_count' => $attributes_values['products_attributes_maxcount']);

                  tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
                }
              }
             }
//------insert customer choosen option eof ----
//////////////////////////////////////////////////////////////////////////////////////////////////
#if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
/*if (isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
$sql_data_array = array('orders_id' => $insert_id,
'orders_products_id' => $order_products_id,
'orders_products_filename' => $attributes_values['products_attributes_filename'],
'download_maxdays' => $attributes_values['products_attributes_maxdays'],
'download_count' => $attributes_values['products_attributes_maxcount']);

tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
}*/
}
}
}

/* tep_session_register('cart_PayPal_Standard_ID');
$cart_PayPal_Standard_ID = $cartID . '-' . $insert_id; */
// FS start
          $GLOBALS['cart_PayPal_Standard_ID'] = $cartID . '-' . $insert_id;
          // FS stop          
          tep_session_register('cart_PayPal_Standard_ID');
          // FS start
          // Terra register globals fix
          //$_SESSION['cart_PayPal_Standard_ID'] = $cartID . '-' . $insert_id;
          // FS stop
}
}

return false;
}

function process_button() {
global $customer_id, $order, $languages_id, $currencies, $currency, $cart_PayPal_Standard_ID, $shipping;
global $sendto;

if (MODULE_PAYMENT_PAYPAL_IPN_CURRENCY == 'Selected Currency') {
$my_currency = $currency;
} else {
$my_currency = substr(MODULE_PAYMENT_PAYPAL_IPN_CURRENCY, 5);
}
/* if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'CHF'))) {
$my_currency = 'CHF';
} */
if (!in_array($my_currency, array('AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'))) {
        $my_currency = 'USD';
}

$parameters = array();

if ( (MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE == 'Per Item') && (MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS == 'False') ) {
$parameters['cmd'] = '_cart';
$parameters['upload'] = '1';
////////////
//handling
/*
Von Ihnen erhobene Bearbeitungsgebühren. Dieser Betrag ist unabhängig von der Menge. Derselbe Betrag wird berechnet, egal wie viele Posten die Bestellung umfasst.
Standardwert: keine Bearbeitungsgebühren
*/
/*if (MODULE_PAYMENT_PAYPAL_FIX_FEE != '0') {
$handling_amount = MODULE_PAYMENT_PAYPAL_FIX_FEE;
$handling = $handling_amount;
} else {
$handling_amount = ($order->info['total'] / MODULE_PAYMENT_PAYPAL_FEE);
$handling = round(($order->info['total'] - $handling_amount),2);
}*/

  if (MODULE_ORDER_TOTAL_PAYPAL_TAX_CLASS > 0) {
            $cod_tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_PAYPAL_TAX_CLASS, $order->billing['country']['id'], $order->billing['zone_id']);
            $cod_tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_PAYPAL_TAX_CLASS, $order->billing['country']['id'], $order->billing['zone_id']);
}


if (MODULE_PAYMENT_PAYPAL_FIX_FEE != '0') {
$handling_amount = MODULE_PAYMENT_PAYPAL_FIX_FEE;
$handling = $handling_amount;
} else {
$handling_amount = ($order->info['subtotal'] * MODULE_PAYMENT_PAYPAL_FEE);
$handling = round(($handling_amount - $order->info['subtotal']),2);
}
//
$_handling_tax = '1.'.$cod_tax;
$handling = ($handling * $_handling_tax);
//
$parameters['handling'] = $handling;  //Bearbeitungsgebühren

//////////
for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
$item = $i+1;

$_products_tax = ('1.'.$order->products[$i]['tax']);
$tax_value = ($_products_tax * $order->products[$i]['final_price']) - $order->products[$i]['final_price'];
$_bruttopreis_item = $_products_tax * $order->products[$i]['final_price'];

$parameters['item_name_' . $item] = ' Art.nr.: ' . $order->products[$i]['model'] .' '. $order->products[$i]['name']. 'GEB:'.$parameters['handling'];
$parameters['amount_' . $item] = number_format($_bruttopreis_item * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
// Tax not Display. It not funktion
#$parameters['tax_' . $item] = number_format($order->info['tax'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
#$parameters['tax_' . $item] = number_format($tax_value * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
$parameters['quantity_' . $item] = $order->products[$i]['qty'];
$parameters['handling'] = $handling;  //Bearbeitungsgebühren
if ($i == 0) {
if (DISPLAY_PRICE_WITH_TAX == 'true') {
$shipping_cost = $order->info['shipping_cost'];
} else {
$module = substr($shipping['id'], 0, strpos($shipping['id'], '_'));
$shipping_tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
$shipping_cost = $order->info['shipping_cost'] + tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
}
$shipping_cost = $shipping_cost + $handling;
$parameters['shipping_' . $item] = number_format($shipping_cost * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
}

if (isset($order->products[$i]['attributes'])) {
for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
if (DOWNLOAD_ENABLED == 'true') {
$attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
on pa.products_attributes_id=pad.products_attributes_id
where pa.products_id = '" . $order->products[$i]['id'] . "'
and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
and pa.options_id = popt.products_options_id
and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
and pa.options_values_id = poval.products_options_values_id
and popt.language_id = '" . $languages_id . "'
and poval.language_id = '" . $languages_id . "'";
$attributes = tep_db_query($attributes_query);
} else {
$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
}
$attributes_values = tep_db_fetch_array($attributes);

// Unfortunately PayPal only accepts two attributes per product, so the
// third attribute onwards will not be shown at PayPal
$parameters['on' . $j . '_' . $item] = $attributes_values['products_options_name'];
$parameters['os' . $j . '_' . $item] = $attributes_values['products_options_values_name'];

}
}
}

$parameters['num_cart_items'] = $item;
} else {
$parameters['cmd'] = '_xclick';
#$parameters['item_name'] = STORE_NAME;
$parameters['item_name'] = STORE_NAME;
//$parameters['item_name'] .= '  Kundenname: '.$order->customer['firstname'] . ' ' . $order->customer['lastname'];
//handling
/*
Von Ihnen erhobene Bearbeitungsgebühren. Dieser Betrag ist unabhängig von der Menge. Derselbe Betrag wird berechnet, egal wie viele Posten die Bestellung umfasst.
Standardwert: keine Bearbeitungsgebühren
*/
/*if (MODULE_PAYMENT_PAYPAL_FIX_FEE != '0') {
$handling_amount = MODULE_PAYMENT_PAYPAL_FIX_FEE;
$handling = $handling_amount;
} else {
$handling_amount = ($order->info['total'] / MODULE_PAYMENT_PAYPAL_FEE);
$handling = round(($order->info['total'] - $handling_amount),2);
}*/
if (MODULE_PAYMENT_PAYPAL_FIX_FEE != '0') {
$handling_amount = MODULE_PAYMENT_PAYPAL_FIX_FEE;
$handling = $handling_amount;
} else {
$handling_amount = ($order->info['total'] * MODULE_PAYMENT_PAYPAL_FEE);
$handling = round(($handling_amount - $order->info['total']),2);
}
$parameters['handling'] = $handling;  //Bearbeitungsgebühren
////
 // Versand ohne Zuschlag für zahlweise paypal
$parameters['shipping'] = number_format($order->info['shipping_cost'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
//
// Versand mit Zuschlag für zahlweise paypal
#$parameters['shipping'] = number_format($order->info['shipping_cost'] * MODULE_PAYMENT_PAYPAL_FEE * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
// PandA.nl move tax to total amount
$parameters['tax'] = 0;
} else {
// default
$parameters['tax'] = number_format($order->info['tax'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
}
}

$parameters['business'] = MODULE_PAYMENT_PAYPAL_IPN_ID;
/*
if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
// PandA.nl move tax to total amount
$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost']) * MODULE_PAYMENT_PAYPAL_FEE * $currencies->get_value($my_currency)+ MODULE_PAYMENT_PAYPAL_FIX_FEE), $currencies->get_decimal_places($my_currency));
} else {
// default
$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost'] - $order->info['tax']) * MODULE_PAYMENT_PAYPAL_FEE * $currencies->get_value($my_currency)+ MODULE_PAYMENT_PAYPAL_FIX_FEE), $currencies->get_decimal_places($my_currency));
}
*/
if(MOVE_TAX_TO_TOTAL_AMOUNT == 'True') {
// PandA.nl move tax to total amount
#$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost']) * MODULE_PAYMENT_PAYPAL_FEE * $currencies->get_value($my_currency)+ MODULE_PAYMENT_PAYPAL_FIX_FEE), $currencies->get_decimal_places($my_currency));
$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost'] - $handling )  * $currencies->get_value($my_currency)), $currencies->get_decimal_places($my_currency));

} else {
// default
#$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost'] - $order->info['tax']) * MODULE_PAYMENT_PAYPAL_FEE * $currencies->get_value($my_currency)+ MODULE_PAYMENT_PAYPAL_FIX_FEE), $currencies->get_decimal_places($my_currency));
$parameters['amount'] = number_format((($order->info['total'] - $order->info['shipping_cost'] - $handling - $order->info['tax']) * $currencies->get_value($my_currency)), $currencies->get_decimal_places($my_currency));
}

/////
$parameters['currency_code'] = $my_currency;
$parameters['invoice'] = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);
$parameters['custom'] = $customer_id;
$parameters['no_shipping'] = '2';
$parameters['no_note'] = '1';
$parameters['handling'] = $handling;  //Bearbeitungsgebühren
$parameters['notify_url'] = tep_href_link('ext/modules/payment/paypal_ipn/ipn.php', '', 'SSL', false, false);
$parameters['return'] = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
$parameters['cancel_return'] = tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
$parameters['bn'] = $this->identifier;

if (tep_not_null(MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE)) {
$parameters['page_style'] = MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE;
}


//// Verschlüssellung
if (MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS == 'True') {
$parameters['cert_id'] = MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID;

$random_string = rand(100000, 999999) . '-' . $customer_id . '-';

$data = '';
while (list($key, $value) = each($parameters)) {
$data .= $key . '=' . $value . "\n";
}

$fp = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt', 'w');
fwrite($fp, $data);
fclose($fp);

unset($data);

if (function_exists('openssl_pkcs7_sign') && function_exists('openssl_pkcs7_encrypt')) {
openssl_pkcs7_sign(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt', MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY), file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt');

// remove headers from the signature
$signed = file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');
$signed = explode("\n\n", $signed);
$signed = base64_decode($signed[1]);

$fp = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', 'w');
fwrite($fp, $signed);
fclose($fp);

unset($signed);

openssl_pkcs7_encrypt(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt', MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');

// remove headers from the encrypted result
$data = file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
$data = explode("\n\n", $data);
$data = '-----BEGIN PKCS7-----' . "\n" . $data[1] . "\n" . '-----END PKCS7-----';

unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
} else {
exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -sign -in ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt -signer ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY . ' -inkey ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY . ' -outform der -nodetach -binary > ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');
unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'data.txt');

exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -encrypt -des3 -binary -outform pem ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY . ' < ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt > ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'signed.txt');

$fh = fopen(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt', 'rb');
$data = fread($fh, filesize(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt'));
fclose($fh);

unlink(MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY . '/' . $random_string . 'encrypted.txt');
}

$process_button_string = tep_draw_hidden_field('cmd', '_s-xclick') .
tep_draw_hidden_field('encrypted', $data);

unset($data);
} else {
while (list($key, $value) = each($parameters)) {
echo tep_draw_hidden_field($key, $value);
}
}

return $process_button_string;
}

/*function before_process() {
global $customer_id, $order, $sendto, $billto, $payment, $currencies, $cart, $cart_PayPal_Standard_ID;
global $$payment;

include(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;

$order_totals = $order_total_modules->process();

$order_id = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);

$sql_data_array = array('orders_id' => $order_id,
'orders_status_id' => $order->info['order_status'],
'date_added' => 'now()',
'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
'comments' => $order->info['comments']);

tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/

 function before_process() {
      global $customer_id, $order, $order_totals, $sendto, $billto, $languages_id, $payment, $currencies, $cart, $cart_PayPal_Standard_ID;
      global $$payment;

      $order_id = substr($cart_PayPal_Standard_ID, strpos($cart_PayPal_Standard_ID, '-')+1);

      $check_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      if (tep_db_num_rows($check_query)) {
        $check = tep_db_fetch_array($check_query);

        if ($check['orders_status'] == MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID) {
          $sql_data_array = array('orders_id' => $order_id,
                                  'orders_status_id' => MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID,
                                  'date_added' => 'now()',
                                  'customer_notified' => '0',
                                  'comments' => '');

          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
      }

      tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0 ? (int)MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : (int)DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . (int)$order_id . "'");

      $sql_data_array = array('orders_id' => $order_id,
                              'orders_status_id' => (MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0 ? (int)MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID : (int)DEFAULT_ORDERS_STATUS_ID),
                              'date_added' => 'now()',
                              'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                              'comments' => $order->info['comments']);

      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
$products_ordered = '';
$subtotal = 0;
$total_tax = 0;

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
if (STOCK_LIMITED == 'true') {
if (DOWNLOAD_ENABLED == 'true') {
$stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
FROM " . TABLE_PRODUCTS . " p
LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
ON p.products_id=pa.products_id
LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
ON pa.products_attributes_id=pad.products_attributes_id
WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
$products_attributes = $order->products[$i]['attributes'];
if (is_array($products_attributes)) {
$stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
}
$stock_query = tep_db_query($stock_query_raw);
} else {
$stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
}
if (tep_db_num_rows($stock_query) > 0) {
$stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
$stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
} else {
$stock_left = $stock_values['products_quantity'];
}
tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
}
}
}

// Update products_ordered (for bestsellers list)
tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");


//------insert customer choosen option to order--------
$attributes_exist = '0';
$products_ordered_attributes = '';

if (isset($order->products[$i]['attributes'])) {
$attributes_exist = '1';
for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
if (DOWNLOAD_ENABLED == 'true') {
$attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
on pa.products_attributes_id=pad.products_attributes_id
where pa.products_id = '" . $order->products[$i]['id'] . "'
and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
and pa.options_id = popt.products_options_id
and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
and pa.options_values_id = poval.products_options_values_id
and popt.language_id = '" . $languages_id . "'
and poval.language_id = '" . $languages_id . "'";
$attributes = tep_db_query($attributes_query);
} else {
$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
}
$attributes_values = tep_db_fetch_array($attributes);

#$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
////
#$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attr_values_name;
$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . tep_decode_specialchars($order->products[$i]['attributes'][$j]['value']);
//options_name
#$products_ordered_attributes .= "\n\t" . tep_decode_specialchars($order->products[$i]['attributes'][$j]['name']) . ' ' . tep_decode_specialchars($order->products[$i]['attributes'][$j]['value']);

////
}
}

//------insert customer choosen option eof ----
$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
$total_cost += $total_products_price;

#$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price2($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes .  "\n";

}

// lets start with the email confirmation
#$date33 = strftime(' %Y-%m');
//
$email_order = STORE_NAME . "\n" .
#$email_order = EMAIL_TEXT_GRUSS . ' ' . $order->customer['firstname']." ".$order->customer['lastname']. "\n" .
#EMAIL_TEXT_ANREDE . ' ' . STORE_NAME . "\n" .
EMAIL_SEPARATOR . "\n" .
EMAIL_TEXT_ORDER_NUMBER . '' . $order_id . "\n" .

//$_SESSION['customer_id']
EMAIL_SEPARATOR . "\n" .
#EMAIL_TEXT_ORDER_NUMBER . ' ' . $_SESSION['order_id'] . "\n" .
EMAIL_TEXT_INVOICE_URL . "\n" .
#EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false) . "\n" .
EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
if ($order->info['comments']) {
$email_order .= tep_db_output($order->info['comments']) . "\n\n";
}
$email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
EMAIL_SEPARATOR . "\n" .
$products_ordered .
EMAIL_SEPARATOR . "\n";

for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
$email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
}

if ($order->content_type != 'virtual') {
$email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
EMAIL_SEPARATOR . "\n" .
tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
}

$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
EMAIL_SEPARATOR . "\n" .
#tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
tep_address_label($customer_id, $billto, 0, '', "\n") . "\n";
EMAIL_SEPARATOR . "\n" .
EMAIL_TEXT_TELEFONNUMMER . $order->customer['telephone'] . "\n" .
EMAIL_SEPARATOR . "\n" .
EMAIL_TEXT_EMAIL . $order->customer['email_address'] . "\n" .
EMAIL_SEPARATOR . "\n\n";

if (is_object($$payment)) {
$email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
EMAIL_SEPARATOR . "\n";
$payment_class = $$payment;
$email_order .= $payment_class->title . "\n\n";
if ($payment_class->email_footer) {
$email_order .= $payment_class->email_footer . "\n\n";
}
}

tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT .' Order Number: ' . $order_id, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// send emails to other people
if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT .' Order Number : ' . $order_id, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}

// load the after_process function from the payment modules
$this->after_process();

$cart->reset(true);

// unregister session variables used during checkout
tep_session_unregister('sendto');
tep_session_unregister('billto');
tep_session_unregister('shipping');
tep_session_unregister('payment');
tep_session_unregister('comments');

tep_session_unregister('cart_PayPal_Standard_ID');

tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
}



function after_process() {
return false;
}

function output_error() {
return false;
}

function check() {
if (!isset($this->_check)) {
$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_IPN_STATUS'");
$this->_check = tep_db_num_rows($check_query);
}
return $this->_check;
}

function install() {
$check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [PayPal IPN]' limit 1");

if (tep_db_num_rows($check_query) < 1) {
$status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
$status = tep_db_fetch_array($status_query);

$status_id = $status['status_id']+1;

$languages = tep_get_languages();

foreach ($languages as $lang) {
tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Preparing [PayPal IPN]')");
}
} else {
$check = tep_db_fetch_array($check_query);

$status_id = $check['orders_status_id'];
}

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal IPN Module', 'MODULE_PAYMENT_PAYPAL_IPN_STATUS', 'False', 'Do you want to accept PayPal IPN payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
// bof PandA.nl move tax to total amount
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Move tax to total amount', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'True', 'Do you want to move the tax to the total amount? If true PayPal will allways show the total amount including tax. (needs Aggregate i.s.o. Per Item to function)', '6', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
// eof PandA.nl move tax to total amount

// für fee
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal Fee', 'MODULE_PAYMENT_PAYPAL_FEE" . $i."', '0', 'Insert a % PayPal feel - 1.04 = 4%', '6', '0', now())");

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal Fix Fee', 'MODULE_PAYMENT_PAYPAL_FIX_FEE" . $i."', '0', 'Insert a PayPal fix feel', '6', '0', now())");
// Ende für fee

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_IPN_ID', '', 'The e-mail address to use for the PayPal IPN service', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY', 'Selected Currency', 'The currency to use for transactions', '6', '6', 'tep_cfg_select_option(array(\'Selected Currency\',\'Only CHF\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_IPN_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Preparing Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID', '" . $status_id . "', 'Set the status of prepared orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set PayPal Acknowledged Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE', 'Per Item', 'Send individual items to PayPal or aggregate all as one total item?', '6', '6', 'tep_cfg_select_option(array(\'Per Item\',\'Aggregate\'), ', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Gateway Server', 'MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER', 'Testing', 'Use the testing (sandbox) or live gateway server for transactions?', '6', '6', 'tep_cfg_select_option(array(\'Testing\',\'Live\'), ', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Page Style', 'MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE', '', 'The page style to use for the transaction procedure (defined at your PayPal Profile page)', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug E-Mail Address', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL', '', 'All parameters of an Invalid IPN notification will be sent to this email address if one is entered.', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Encrypted Web Payments', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS', 'False', 'Do you want to enable Encrypted Web Payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Private Key', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY', '', 'The location of your Private Key to use for signing the data. (*.pem)', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Public Certificate', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY', '', 'The location of your Public Certificate to use for signing the data. (*.pem)', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPals Public Certificate', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY', '', 'The location of the PayPal Public Certificate for encrypting the data.', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your PayPal Public Certificate ID', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID', '', 'The Certificate ID to use from your PayPal Encrypted Payment Settings Profile.', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Working Directory', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY', '', 'The working directory to use for temporary files. (trailing slash needed)', '6', '4', now())");
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('OpenSSL Location', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL', '/usr/bin/openssl', 'The location of the openssl binary file.', '6', '4', now())");

}

function remove() {
tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
}

function keys() {
// PandA.nl move tax to total amount added: ", 'MOVE_TAX_TO_TOTAL_AMOUNT'"
return array('MODULE_PAYMENT_PAYPAL_IPN_STATUS', 'MOVE_TAX_TO_TOTAL_AMOUNT', 'MODULE_PAYMENT_PAYPAL_IPN_ID', 'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY','MODULE_PAYMENT_PAYPAL_FEE', 'MODULE_PAYMENT_PAYPAL_FIX_FEE', 'MODULE_PAYMENT_PAYPAL_IPN_ZONE', 'MODULE_PAYMENT_PAYPAL_IPN_PREPARE_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_IPN_GATEWAY_SERVER', 'MODULE_PAYMENT_PAYPAL_IPN_TRANSACTION_TYPE', 'MODULE_PAYMENT_PAYPAL_IPN_PAGE_STYLE', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL', 'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_WORKING_DIRECTORY', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL');
}
}


?>
