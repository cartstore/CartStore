<?php
/*
  Copyright (C) 2008 Google Inc.

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
 * $Id: merchant_calculated_methods.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Merchant Calculated Shipping Methods.
 *
 * This class is a hack.
 *
 * It contains the hard-coded names of the shipping companies and
 * methods for the most popular osCommerce shipping modules, including
 * the built-in modules (flat, per item, etc.)
 *
 * We should eventually figure out how to dynamically poll the modules to
 * determine the shipping methods they offer.
 *
 * In the mean time, if you want to install a shipping module not listed
 * here, you'll need to manually edit this file in order for Google Checkout
 * to use it for merchant calculated shipping.
 *
 * You can also use the Shipping Method Generator script located
 * in catalog/googlecheckout/shipping_generator/shipping_method_generator.php
 * (you can access it in your browser).
 *
 * The shipping method names must be unique.
 * TODO(eddavisson): Unique across all methods or unique per shipping company?
 */

$mc_shipping_methods = array(
  'usps' => array(
    'domestic_types' => array(
      'Express' => 'Express Mail',
      'First Class' => 'First-Class Mail',
      'Priority' => 'Priority Mail',
      'Parcel' => 'Parcel Post'
    ),
    'international_types' => array(
      'GXG Document' => 'Global Express Guaranteed Document Service',
      'GXG Non-Document' => 'Global Express Guaranteed Non-Document Service',
      'Express' => 'Global Express Mail (EMS)',
      'Priority Lg' => 'Global Priority Mail - Flat-rate Envelope (large)',
      'Priority Sm' => 'Global Priority Mail - Flat-rate Envelope (small)',
      'Priority Var' => 'Global Priority Mail - Variable Weight Envelope (single)',
      'Airmail Letter' => 'Airmail Letter Post',
      'Airmail Parcel' => 'Airmail Parcel Post',
      'Surface Letter' => 'Economy (Surface) Letter Post',
      'Surface Post' => 'Economy (Surface) Parcel Post'
    ),
  ),
  'fedex1' => array(
    'domestic_types' => array(
      '01' => 'Priority (by 10:30AM, later for rural)',
      '03' => '2 Day Air',
      '05' => 'Standard Overnight (by 3PM, later for rural)',
      '06' => 'First Overnight',
      '20' => 'Express Saver (3 Day)',
      '90' => 'Home Delivery',
      '92' => 'Ground Service'
    ),
    'international_types' => array(
      '01' => 'International Priority (1-3 Days)',
      '03' => 'International Economy (4-5 Days)',
      '06' => 'International First',
      '90' => 'International Home Delivery',
      '92' => 'International Ground Service'
    ),
  ),
  'upsxml' => array(
    'domestic_types' => array(
      'UPS Ground' => 'UPS Ground',
      'UPS 3 Day Select' => 'UPS 3 Day Select',
      'UPS 2nd Day Air A.M.' => 'UPS 2nd Day Air A.M.',
      'UPS 2nd Day Air' => 'UPS 2nd Day Air',
      'UPS Next Day Air Saver' => 'UPS Next Day Air Saver',
      'UPS Next Day Air Early A.M.' => 'UPS Next Day Air Early A.M.',
      'UPS Next Day Air' => 'UPS Next Day Air'
    ),
    'international_types' => array(
      'UPS Worldwide Expedited' => 'UPS Worldwide Expedited',
      'UPS Saver' => 'UPS Saver'
    ),
  ),
  'zones' => array(
    'domestic_types' => array(
      'zones' => 'Zones Rates'
    ),
    'international_types' => array(
      'zones' => 'Zones Rates intl'
    ),
  ),
  'flat' => array(
    'domestic_types' => array(
      'flat' => 'Flat Rate Per Order'
    ),
    'international_types' => array(
      'flat' => 'Flat Rate Per Order intl'
    ),
  ),
  'item' => array(
    'domestic_types' => array(
      'item' => 'Flat Rate Per Item'
    ),
    'international_types' => array(
    ),
  ),
  'itemint' => array(
    'domestic_types' => array(
    ),
    'international_types' => array(
      'itemint' => 'Flat Rate Per Item intl'
    ),
  ),
  'table' => array(
    'domestic_types' => array(
      'table' => 'Table'
    ),
    'international_types' => array(
      'table' => 'Table intl'
    ),
  ),
);

$mc_shipping_methods_names = array(
  'usps' => 'USPS',
  'fedex1' => 'FedEx',
  'upsxml' => 'Ups',
  'zones' => 'Zones',
  'flat' => 'Flat Rate',
  'item' => 'Item',
  'itemint' => 'Item Inter',
  'table' => 'Table',
);

?>
