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
 * $Id: googleresult.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Used to create a Google Checkout result as a response to a 
 * merchant-calculations feedback structure, i.e shipping, tax, coupons and
 * gift certificates.
 * 
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_result <result>}
 */
 
  // refer to demo/responsehandlerdemo.php for usage of this code
  class GoogleResult {
    var $shipping_name;
    var $address_id;
    var $shippable;
    var $ship_price;

    var $tax_amount;

    var $coupon_arr = array();
    var $giftcert_arr = array();

    /**
     * @param integer $address_id the id of the anonymous address sent by 
     *                           Google Checkout.
     */
    function GoogleResult($address_id) {
      $this->address_id = $address_id;
    }

    function SetShippingDetails($name, $price, $shippable = "true") {
      $this->shipping_name = $name;
      $this->ship_price = $price;
      $this->shippable = $shippable;
    }

    function SetTaxDetails($amount) {
      $this->tax_amount = $amount;
    }

    function AddCoupons($coupon) {
      $this->coupon_arr[] = $coupon;
    }

    function AddGiftCertificates($gift) {
      $this->giftcert_arr[] = $gift;
    }
  }

 /**
  * This is a class used to return the results of coupons the buyer supplied in
  * the order page.
  * 
  * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_coupon-result <coupon-result>}
  */
  class GoogleCoupons {
    var $coupon_valid;
    var $coupon_code;
    var $coupon_amount;
    var $coupon_message;

    function googlecoupons($valid, $code, $amount, $message) {
      $this->coupon_valid = $valid;
      $this->coupon_code = $code;
      $this->coupon_amount = $amount;
      $this->coupon_message = $message;
    } 
  }

 /**
  * This is a class used to return the results of gift certificates
  * supplied by the buyer on the place order page
  * 
  * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_gift-certificate-result} <gift-certificate-result>
  */
  
  class GoogleGiftcerts {
    var $gift_valid;
    var $gift_code;
    var $gift_amount;
    var $gift_message;

    function googlegiftcerts($valid, $code, $amount, $message) {
      $this->gift_valid = $valid;
      $this->gift_code = $code;
      $this->gift_amount = $amount;
      $this->gift_message = $message;
    }
  }
?>
