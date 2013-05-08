<?php
/*
 * Created on 28/03/2007
 *
 * Coded by: Ropu
 * Globant - Buenos Aires, Argentina  - z-tests_atx
 * Version 0.2
 */

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

// NOTE: This script MUST be placed in googlecheckout/shipping_generator/ directory

// Set the shippers code you want to test
$shippers = array();
//$shippers[] = "fedex1";
//$shippers[] = "upsxml";


if(isset($_POST['country'])) {
	error_reporting(E_ALL);
	chdir('./../..');
	$curr_dir = getcwd();

	include_once('includes/application_top.php');
	// serialized cart, to avoid needing one in session
	$cart = unserialize('O:12:"shoppingcart":5:{s:8:"contents";a:1:{i:6;a:1:{s:3:"qty";i:1;}}s:5:"total";d:30;s:6:"weight";d:7;s:6:"cartID";s:5:"62209";s:12:"content_type";s:8:"physical";}');
//	print_r($cart);

  $cart->total = $_POST['price'];
  $cart->weight = $_POST['weight'];
  $cart->contents[6]['qty'] = $_POST['cant'];
//  print_r($cart);
//die;
	require(DIR_WS_CLASSES .'order.php');
	$order = new order;

	// Register a random ID in the session to check throughout the checkout procedure
	// against alterations in the shopping cart contents.
	if (!tep_session_is_registered('cartID')) {
	  tep_session_register('cartID');
	}


	$total_weight = $cart->show_weight();
	$total_count = $cart->count_contents();

	// Get all the enabled shipping methods.
	require(DIR_WS_CLASSES .'shipping.php');

	// Required for some shipping methods (ie. USPS).
	require_once('includes/classes/http_client.php');
}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<title>Shipping Methods Generator</title>

<script language="JavaScript" type="text/javascript" src="multishipping_generator.js"></script>

</head>
<body bgcolor="#FFFFFF" text="#000000" link="#FF9966" vlink="#FF9966" alink="#FFCC99">

  <h2 align="center">Shipping Methods generator</h2>

 <form action="" method="post" onSubmit="document.getElementById('calculate').disabled=true;document.getElementById('calculate').value='Calculating...'">
    <table align="center" border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td>
          <table align="center" border="1" cellpadding="2" cellspacing="0">
            <tr>
              <th colspan="2">Domestic Shipping address</th>
            </tr>
            <tr>
              <th>Country:</th>
              <td>
                <input type="text" name="country" value="<?php echo isset($_POST['country'])?$_POST['country']:'US';?>"/>
              </td>
            </tr>
            <tr>
              <th>City:</th>
              <td>
                <input type="text" name="city" value="<?php echo isset($_POST['city'])?$_POST['city']:'Miami';?>"/>
              </td>
            </tr>
            <tr>
              <th>Region:</th>
              <td>
                <input type="text" name="region" value="<?php echo isset($_POST['region'])?$_POST['region']:'FL';?>"/>
              </td>
            </tr>
            <tr>
              <th>Postal Code:</th>
              <td>
                <input type="text" name="postalcode" value="<?php echo isset($_POST['postalcode'])?$_POST['postalcode']:'33102';?>"/>
              </td>
            </tr>
          </table>
        </td>
        <td>
          <table align="center" border="1" cellpadding="2" cellspacing="0">
            <tr>
              <th colspan="2">Int'l Shipping address</th>
            </tr>
            <tr>
              <th>Country:</th>
              <td>
                <input type="text" name="i_country" value="<?php echo isset($_POST['i_country'])?$_POST['i_country']:'GB';?>"/>
              </td>
            </tr>
            <tr>
              <th>City:</th>
              <td>
                <input type="text" name="i_city" value="<?php echo isset($_POST['i_city'])?$_POST['i_city']:'Glasgow';?>"/>
              </td>
            </tr>
            <tr>
              <th>Region:</th>
              <td>
                <input type="text" name="i_region" value="<?php echo isset($_POST['i_region'])?$_POST['i_region']:'LANARKSHIRE';?>"/>
              </td>
            </tr>
            <tr>
              <th>Postal Code:</th>
              <td>
                <input type="text" name="i_postalcode" value="<?php echo isset($_POST['i_postalcode'])?$_POST['i_postalcode']:'G42 8RB';?>"/>
              </td>
            </tr>
           </table>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table align="center" border="1" cellpadding="2" cellspacing="0">
            <tr>
              <th colspan="2">Cart Description</th>
            </tr>
            <tr>
              <th>Weight:</th>
              <td>
                <input type="text" name="weight" value="<?php echo isset($_POST['weight'])?$_POST['weight']:'7';?>"/>
              </td>
            </tr>
            <tr>
              <th>Quantity:</th>
              <td>
                <input type="text" name="cant" value="<?php echo isset($_POST['cant'])?$_POST['cant']:'1';?>"/>
              </td>
            </tr>
            <tr>
              <th>Total Price:</th>
              <td>
                <input type="text" name="price" value="<?php echo isset($_POST['price'])?$_POST['price']:'30';?>"/>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <th colspan="2">
          <input type="submit" name="calculate" id="calculate" value="Get Shipping Methods"/>
        </th>
      </tr>
    </table>
  </form>

<?php
if(isset($_POST['country'])) {
	$mc_shipping_methods = array();
  $mc_shipping_methods_names = array();

  $methods_duplicate = array();

	list($start_m, $start_s) = explode(' ', microtime());
	$start = $start_m + $start_s;

    // Set up the order address.
// Domestic
  $country = mysql_escape_string($_POST['country']);
  $city = mysql_escape_string($_POST['city']);
  $region = mysql_escape_string($_POST['region']);
  $postal_code = mysql_escape_string($_POST['postalcode']);

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
  $i_shipping_modules = $shipping_modules;
//  print_r($shipping_modules);
  $quotes =  $shipping_modules->quote();
  foreach($quotes as $shipper) {
    $methods = array();
    if(is_array(@$shipper['methods']) && !isset($shipper['error'])){
      foreach($shipper['methods'] as $method) {
        if(isset($methods_duplicate[$method['title']])) {
          $method['title'] .= "_" . $methods_duplicate[$method['title']]++;
        }
        else {
          $methods_duplicate[$method['title']] = 1;
        }
        $methods[$method['id']] = array('title' => htmlentities($method['title']),
                                        'cost' => $method['cost']);
      }
      $mc_shipping_methods[$shipper['id']]['domestic_types'] = $methods;
      if (class_exists($shipper['id'])) {
        $GLOBALS[$shipper['id']] = new $shipper['id'];
      }
      $mc_shipping_methods_names[$shipper['id']] = htmlentities($shipper['module']);
    }
  }
// int'l
  if(!empty($_POST['i_country'])) {
    $country = mysql_escape_string($_POST['i_country']);
    $city = mysql_escape_string($_POST['i_city']);
    $region = mysql_escape_string($_POST['i_region']);
    $postal_code = mysql_escape_string($_POST['i_postalcode']);

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
//    $shipping_modules = new shipping();
    $quotes =  $i_shipping_modules->quote();
    foreach($quotes as $shipper) {
      $methods = array();
      if(is_array(@$shipper['methods']) && !isset($shipper['error'])){
        foreach($shipper['methods'] as $method) {
          if(isset($methods_duplicate[$method['title']])) {
            $method['title'] .= "_" . $methods_duplicate[$method['title']]++;
          }
          else {
            $methods_duplicate[$method['title']] = 1;
          }
          $methods[$method['id']] = array('title' => htmlentities($method['title']),
                                          'cost' => $method['cost']);
        }
        $mc_shipping_methods[$shipper['id']]['international_types'] = $methods;
        if (class_exists($shipper['id'])) {
          $GLOBALS[$shipper['id']] = new $shipper['id'];
        }
        $mc_shipping_methods_names[$shipper['id']] = htmlentities($shipper['module']);
      }
    }
  }
	list($end_m, $end_s) = explode(' ', microtime());
	$end = $end_m + $end_s;
  echo "<h3>It took (Average) <b>".(number_format(($end-$start)/2, 5))."</b> Secs.</td></h3><br/>";
  include('multishipping_generator.php');
  echo "<script language='javascript'>show_help(4);</script>";
}
?>