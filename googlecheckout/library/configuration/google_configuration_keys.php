<?php
/*
  Copyright (C) 2009 Google Inc.

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
 * $Id$
 * 
 * Keys for accessing Google Checkout configuration values.
 * 
 * We define these as functions (not constants or class members) so that
 * 
 *   1) We don't pollute the global namespace.
 *   2) An error is thrown if we try to access something that doesn't exist.
 * 
 * Note that the keys need to be unique.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleConfigurationKeys {
  
  // Constructor.
  function GoogleConfigurationKeys() {}
  
  // Function                                // Key
  function googleAnalyticsId()               { return "GOOGLE_ANALYTICS_ID"; }
  function usPoBox()                         { return "US_PO_BOX"; }
  function enableCarrierCalculatedShipping() { return "ENABLE_CARRIER_CALCULATED_SHIPPING"; }
  function roundingMode()                    { return "ROUNDING_MODE"; }
  function roundingRule()                    { return "ROUNDING_RULE"; }
  function htaccessAuthMode()                { return "HTACCESS_AUTH_MODE"; }
  function virtualGoods()                    { return "VIRTUAL_GOODS"; }
  function sandboxMerchantCallbackProtocol() { return "SANDBOX_MERCHANT_CALLBACK_PROTOCOL"; }
  function cartExpirationTime()              { return "CART_EXPIRATION_TIME"; }
  function useCartMessaging()                { return "USE_CART_MESSAGING"; }
  function thirdPartyTrackingUrl()           { return "THIRD_PARTY_TRACKING_URL"; }
  function restrictedCategories()            { return "RESTRICTED_CATEGORIES"; }
  function continueShoppingUrl()             { return "CONTINUE_SHOPPING_URL"; }
  function carrierCalculatedShipping()       { return "CARRIER_CALCULATED_SHIPPING"; }
  function merchantCalculatedShipping()      { return "MERCHANT_CALCULATED_SHIPPING"; }
  
  // Disabled.
  function multisocket()                     { return "MULTISOCKET"; }
  
  /**
   * "NONE" is used to represent an empty value.
   * 
   * TODO(eddavisson): We should probably find a better way to deal with this.
   */
  function nullValue() { return "NONE"; }
}

?>
