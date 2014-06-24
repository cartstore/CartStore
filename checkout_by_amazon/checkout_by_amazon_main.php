<?php
/**
 * @brief Generates the button to be displayed on the shopping cart page 
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Neil Corkum
 * @author Allison Naaktgeboren
 * @copyright Portions copyright 2007-2008 Amazon Technologies, Inc
 * @copyright Portions copyright osCommerce 2002-2008
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 * @note the javascript for 1click & jquery cannot go here because they belong in the <head>
 *       section of shopping_cart.php and this will be embedded in the middle 
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

require_once('includes/languages/'. $language .'/modules/payment/checkout_by_amazon.php');
require_once('includes/modules/payment/checkout_by_amazon.php');
require_once('checkout_by_amazon/checkout_by_amazon_cart.php');
require_once('checkout_by_amazon/checkout_by_amazon_constants.php');

/* display the button only if cart is not empty */
if(count($cart->contents) > 0){

$cba_module_info = new checkout_by_amazon();
$cba_cart = new CheckoutByAmazonCart($cart, $cba_module_info, $languages_id);
?>
 

 
<p>
    <?php 
  echo MODULE_PAYMENT_CHECKOUTBYAMAZON_USE_CBA_TEXT;
?>
<?php 
 echo $cba_cart->CheckoutButtonHtml();

 if ($cba_module_info->operating_env != 'Production') {
   echo HTML_SANDBOX_WARNING_OPENING;
   echo MODULE_PAYMENT_CHECKOUTBYAMAZON_USING_SANDBOX;
   echo HTML_SANDBOX_WARNING_CLOSING;
 }
?>
</p>
 
<?
}
?>
