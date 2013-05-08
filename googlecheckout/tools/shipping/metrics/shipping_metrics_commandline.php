<?php
/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Google Checkout v1.5.0
 * $Id: shipping_metrics_commandline.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Tool to test WS response time.
 * 
 * NOTE: This script MUST be placed in the googlecheckout/tools/shipping/metrics/ directory.
 */

// Set the shippers code you want to test
$shippers = array();
$shippers[] = "fedex1";
$shippers[] = "usps";

error_reporting(0);

chdir('./../../../..');
$curr_dir = getcwd();

include_once('includes/application_top.php');
// serialized cart, to avoid needing one in session
$cart = unserialize('O:12:"shoppingcart":5:{s:8:"contents";a:1:{i:6;a:1:{s:3:"qty";i:1;}}s:5:"total";d:30;s:6:"weight";d:7;s:6:"cartID";s:5:"62209";s:12:"content_type";s:8:"physical";}');

require(DIR_WS_CLASSES .'order.php');
$order = new order;

// Register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents.
if (!tep_session_is_registered('cartID')) {
  tep_session_register('cartID');
}

$cartID = $cart->cartID;

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();

// Get all the enabled shipping methods.
require(DIR_WS_CLASSES .'shipping.php');

// Required for some shipping methods (ie. USPS).
require_once('includes/classes/http_client.php');
  // Set up the order address.
$country = 'US';
$city = 'Miami';
$region = 'FL';
$postal_code = '33102';

$row = tep_db_fetch_array(tep_db_query("select * from ". TABLE_COUNTRIES ." where countries_iso_code_2 = '". $country ."'"));
$order->delivery['country'] = array('id' => $row['countries_id'],
                                    'title' => $row['countries_name'],
                                    'iso_code_2' => $country,
                                    'iso_code_3' => $row['countries_iso_code_3']);
$order->delivery['country_id'] = $row['countries_id'];
$order->delivery['format_id'] = $row['address_format_id'];

$row = tep_db_fetch_array(tep_db_query("select * from ". TABLE_ZONES ." where zone_code = '" . $region."'"));
$order->delivery['zone_id'] = $row['zone_id'];
$order->delivery['state'] = $row['zone_name'];

$order->delivery['city'] = $city;
$order->delivery['postcode'] = $postal_code;
$shipping_modules = new shipping();

foreach($shippers as $shipper) {
  list($start_m, $start_s) = explode(' ', microtime());
  $start = $start_m + $start_s;
  $quotes =  $shipping_modules->quote('', $shipper);
  list($end_m, $end_s) = explode(' ', microtime());
  $end = $end_m + $end_s;
   echo $shipper." took ".(number_format($end-$start, 5))." Secs\n";
}

list($start_m, $start_s) = explode(' ', microtime());
$start = $start_m + $start_s;
$quotes =  $shipping_modules->quote();
list($end_m, $end_s) = explode(' ', microtime());
$end = $end_m + $end_s;
echo "All quotes took ".(number_format($end-$start, 5))." Secs\n";

?>
