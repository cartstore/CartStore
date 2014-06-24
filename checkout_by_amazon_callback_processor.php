<?php
/**
 * @brief Processes callback calculations request dynamically.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Balachandar Muruganantham
 * @author Joshua Wong
 * @copyright 2008-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 *
 */
/*
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
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once('includes/application_top.php');

// Check for STS module installed, and disable if it is.
if (is_object($sts)) {
        $sts->display_template_output = false;
}

// close the session to not throw errors in PHP 5
require_once('includes/application_bottom.php');

//cba files
require_once("checkout_by_amazon/checkout_by_amazon_includes.php");
require_once('checkout_by_amazon/library/callback/lib/amazon/config.php');
require_once('checkout_by_amazon/checkout_by_amazon_util_dao.php');
require_once('checkout_by_amazon/checkout_by_amazon_tax_processor.php');
require_once('checkout_by_amazon/checkout_by_amazon_shipping_processor.php');
// load the oscommerce files
require_once(DIR_WS_CLASSES . 'payment.php');
require_once(DIR_WS_CLASSES . 'checkout_by_amazon_order.php');
require_once(DIR_WS_CLASSES . 'shipping.php');
require_once(DIR_WS_CLASSES . 'http_client.php');
require_once(DIR_WS_CLASSES . 'checkout_by_amazon_shopping_cart.php');

/* log the requests */
requestlog();
$cba_module_info = new checkout_by_amazon();
$cb = new OrderCallbackProcessor();
$items = $cb->GetOrderItems();
$itemCount = $cb->GetOrderItemCount();
$shippingAddress = $cb->GetShippingAddress();
$utilDao = new UtilDAO();
$cart = new shoppingCartAmazon();
$cart->generate_cart($items);
$totals = $cart->show_total();
$weight = $cart->show_weight();
$total_count=$cart->count_contents(); // total count of product quantity

///////////////////////////////////////////////////////////////////////
//
// Computing tax for multiple items
// Iterating through the items to get list of products_id. 
// Tax is calulated for each item. 
//
///////////////////////////////////////////////////////////////////////

if ($cba_module_info->callback_taxes == 'true') {
    
  $taxTablesArray = array();
  $taxTableArray = array();
  $skuArray = array();
  for ($i = 0; $i < $itemCount; $i++) {
    array_push($skuArray, $cb->GetSKU($items[$i]));	
  }
  writelog("Calculating tax for  " . $itemCount . " item/items \n\n");
  $taxTableArray = getTax($skuArray, $shippingAddress);
  array_push($taxTablesArray,$taxTableArray);

  /* Set Tax Tables Array */
  $cb->SetTaxTables($taxTablesArray);
}

/* Set Shipping Methods Array */
if ($cba_module_info->callback_shipping == 'true') {

  $postalCode = $shippingAddress['PostalCode'];
  $countryCode = $shippingAddress['CountryCode'];
  $state = $shippingAddress['State'];

  $order = new orderAmazon('', false);
  $country=$utilDao->getCountryByISOCode2($countryCode);
  $order->delivery['country']['iso_code_2'] = (string)$countryCode;
  $order->delivery['country']['id'] = $country['countries_id'];
  $order->delivery['postcode'] = $postalCode;
  $order->delivery['zone_id'] = $utilDao->tep_get_zone_id($state);

  $processor = new ShippingProcessor();
  $shippingMethodsArray = $processor->getQuote($weight);

  if($shippingMethodsArray){
   $cb->SetShippingMethods($shippingMethodsArray);
   ob_writelog("Got shipping amount from shipping carrier: ", $shippingMethodsArray);
  }else{
   writelog("Shipping Carrier and Shipping Override are None. Please change in Checkout by Amazon 2.0 Payment module");
  }
}

// Generating the response in key value pair, i.e.:
// order-calculations-response=[xml content here]
// Output the response body, which the Checkout by Amazon system will process
echo $cb->GenerateResponse();
?>
