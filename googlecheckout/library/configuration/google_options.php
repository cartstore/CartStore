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

$configuration_dir = DIR_FS_CATALOG . '/googlecheckout/library/configuration/';
require_once($configuration_dir . 'array_option.php');
require_once($configuration_dir . 'boolean_option.php');
require_once($configuration_dir . 'carrier_calculated_shipping_option.php');
require_once($configuration_dir . 'merchant_calculated_shipping_option.php');
require_once($configuration_dir . 'text_option.php');
require_once($configuration_dir . 'google_configuration_keys.php');

/**
 * Google Checkout v1.5.0
 * $Id$
 * 
 * Class that encapsulates the Google Checkout configuration options.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleOptions {
	
  var $config;
  
  var $recommended_options;
  var $shipping_options;
  var $rounding_options;
  var $other_options;
  
  // Constructor.
  function GoogleOptions() {
  	$this->config = new GoogleConfigurationKeys();
    
    $this->recommended_options = array();
    $this->shipping_options = array();
    $this->rounding_options = array();
    $this->other_options = array();

    // Populate the options arrays.    
    $this->addOptions();
  }  
   
  function getRecommendedOptions() {
  	return $this->recommended_options;
  }
  
  function getShippingOptions() {
  	return $this->shipping_options;
  }
  
  function getRoundingOptions() {
  	return $this->rounding_options;
  }
  
  function getOtherOptions() {
  	return $this->other_options;
  }
  
  function getAllOptions() {
    return array_merge(
        $this->recommended_options,
        $this->shipping_options,
        $this->rounding_options,
        $this->other_options
        );
  }

  function addOptions() {
    // Recommended.
    $this->recommended_options[] = new GoogleTextOption(
        "Google Analytics ID",
        "Google Analytics ID (UA-XXXXXX-X). Set to \"NONE\" to disable."
            . " For more information, see Google Checkout's"
            . " <a class=\"google\" href=\"http://code.google.com/apis/checkout/developer/checkout_analytics_integration.html\" target=\"_blank\">Analytics integration documentation</a>.",
        $this->config->googleAnalyticsId(),
        "NONE");
    
    // Shipping.
    $this->shipping_options[] = new GoogleBooleanOption(
        "Ship to United States P.O. Boxes",
        "Check to allow shipping to United States P.O. Boxes",
        $this->config->usPoBox(),
        "True");
      
    $this->shipping_options[] = new GoogleBooleanOption(
        "Enable Carrier Calculated Shipping",
        "Check to use Google Checkout Carrier Calculated Shipping."
            . " This feature can be mixed with Flat Rate Shipping but not Merchant Calculated Shipping.",
        $this->config->enableCarrierCalculatedShipping(),
        "True");
        
    $this->shipping_options[] = new GoogleCarrierCalculatedShippingConfigOption(
        "Carrier Calculated Shipping Configuration",
        "This section can be used to configure carrier calculated shipping. The Default value will "
            . " be used if Google Checkout is unable to contact the carrier for a quote. Setting "
            . " the default to 0 will disable the method entirely. The Fixed and Variable values can "
            . " be used to modify the quote received by Google Checkout. The quote will be multiplied "
            . " by the Variable value and then added to the Fixed value before being shown to the buyer.",
        $this->config->carrierCalculatedShipping());
        
    $this->shipping_options[] = new GoogleMerchantCalculatedShippingOption(
        "Merchant Calculated Shipping Configuration",
        "This section can be used to configure merchant calculated shipping. The dollar amount for "
            . " each shipping method will be used if Google Checkout is unable to contact your "
            . " callback handler. Setting the default to 0 will disable the method entirely.",
        $this->config->merchantCalculatedShipping());    
        
    // Rounding.
    $this->rounding_options[] = new GoogleArrayOption(
        "Rounding Policy Mode",
        "Method for rounding costs to two decimal places. For more information, see"
            . " Google Checkout's <a class=\"google\" href=\"http://code.google.com/apis/checkout/developer/Google_Checkout_Rounding_Policy.html\" target=\"_blank\">rounding policy</a>.",
        $this->config->roundingMode(),
        array(
            "Up" => "UP",
            "Down" => "DOWN",
            "Ceiling" => "CEILING",
            "Half Up" => "HALF_UP",
            "Half Down" => "HALF_DOWN",
            "Half Even" => "HALF_EVEN",
            ),
        "HALF_EVEN");
        
    $this->rounding_options[] = new GoogleArrayOption(
        "Rounding Policy Rule",
        "Stage at which Google Checkout will apply rounding to the order.",
        $this->config->roundingRule(),
        array(
            "Per Line" => "PER_LINE",
            "Total" => "TOTAL",
            ),
        "PER_LINE");     
        
    // Other.
    $this->other_options[] = new GoogleBooleanOption(
        "Enable .htaccess Basic Authentication Mode",
        "Checking this option will disable PHP Basic Authentication, which is not compatible with CGI."
            . " This feature is used by the callback handler to validate Google Checkout messages."
            . " If you check this box, you will need to configure your .htaccess files by visiting"
            . " <a class=\"google\" href=\"htaccess.php\" target=\"_blank\">this page</a>.",
        $this->config->htaccessAuthMode(),
        "False");
    
    $this->other_options[] = new GoogleBooleanOption(
        "Disable Google Checkout for Virtual Goods",
        "If this option is checked, and there is a virtual good in the cart, the Google Checkout button will be disabled.",
        $this->config->virtualGoods(),
        "False");
    
    // TODO(eddavisson): Is this option ignored in production, or does
    // the merchant need to come back to change it?
    $this->other_options[] = new GoogleArrayOption(
        "Sandbox Callback Protocol",
        "Protocol for sandbox merchant callbacks (production environment always requires \"https\").",
        $this->config->sandboxMerchantCallbackProtocol(),
        array(
            "http" => "http",
            "https" => "https",
            ),
        "https");   
        
    $this->other_options[] = new GoogleTextOption(
        "Time to Cart Expiration (minutes)",
        "Time in minutes after which a cart will expire. If set to \"NONE\", carts will never expire.",
        $this->config->cartExpirationTime(),
        "NONE");
    
    $this->other_options[] = new GoogleBooleanOption(
        "Also Send Notifications Using osCommerce",
        "Check to send notifications to buyers using osCommerce's mailing system (in addition to the notifications Google Checkout will send)",
        $this->config->useCartMessaging(),
        "False");
    
    $this->other_options[] = new GoogleTextOption(
        "Third Party Tracking URL",
        "URL for a third party tracker. Set to \"NONE\" to disable.",
        $this->config->thirdPartyTrackingUrl(),
        "NONE");
    
    $this->other_options[] = new GoogleTextOption(
        "Restricted Product Categories",
        "A comma-separated list of product categories for which the Google Checkout button will be grayed out."
            . " For more information, please consult Google Checkout's" 
            . " <a class=\"google\" href=\"http://checkout.google.com/support/sell/bin/answer.py?answer=46174&topic=8681\" target=\"_blank\">policy</a>.",
        $this->config->restrictedCategories(),
        "");
        
    $this->other_options[] = new GoogleTextOption(
        "Continue Shopping URL",
        "The page to which customers will be redirected if they choose to continue shopping after they check out.",
        $this->config->continueShoppingUrl(),
        "gc_return.php");
  }
}

?>
