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
 * $Id: carrier_calculated_methods.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Carrier Calculated Shipping Methods.
 *
 * These must be kept in sync with the shipping companies and shipping types
 * supported by the Google Checkout Carrier Calculated Shipping XML API.
 *
 * For more information, see:
 *
 *   {@link http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API_Carrier_Calculated_Shipping#tag_shipping-company}
 *
 * and:
 *
 *   {@link http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API_Carrier_Calculated_Shipping#tag_shipping-type}
 */

$cc_shipping_methods = array(
  'fedex' => array(
    'domestic_types' => array(
      'Ground' => 'Ground',
      'Home Delivery' => 'Home Delivery',
      'Express Saver' => 'Express Saver',
      '2Day' => '2Day',
      'Standard Overnight' => 'Standard Overnight',
      'Priority Overnight' => 'Priority Overnight',
      'First Overnight' => 'First Overnight',
    ),
    'international_types' => array(
    ),
  ),
  'ups' => array(
    'domestic_types' => array(
      'Ground' => 'Ground',
      '3 Day Select' => '3 Day Select',
      '2nd Day Air' => '2nd Day Air',
      // TODO(eddavisson): This was commented out before. Test that it works.
      '2nd Day Air AM' => ' 2nd Day Air AM',
      'Next Day Air Saver' => 'Next Day Air Saver',
      'Next Day Air' => 'Next Day Air',
      'Next Day Air Early AM' => 'Next Day Air Early AM',
    ),
    'international_types' => array(
    ),
  ),
  'usps' => array(
    'domestic_types' => array(
      'Media Mail' => 'Media Mail',
      'Parcel Post' => 'Parcel Post',
      'Express Mail' => 'Express Mail',
      // TODO(eddavisson): This was commented out before. Test that it works.
      'Priority Mail' => 'Priority Mail',
    ),
    'international_types' => array(
    ),
  ),
);

$cc_shipping_methods_names = array(
  'fedex' => 'FedEx',
  'ups' => 'UPS',
  'usps' => 'USPS',
);

?>
