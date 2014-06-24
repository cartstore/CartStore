<?php
/*
  Copyright (C) 2007 Google Inc.

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

// Current plug-in version.
define('GOOGLECHECKOUT_FILES_VERSION', 'v1.5.0');

/**
 * Google Checkout v1.5.0
 * $Id$
 * 
 * This class is the actual payment module for Google Checkout.
 * 
 * Member variables refer to currently set parameter values from the database.
 */
class googlecheckout {
  // Table names.
  var $table_name = "google_checkout";
  var $table_order = "google_orders";  
  
  var $code, $title, $description, $merchantid, $merchantkey, $mode,
      $enabled, $shipping_support, $variant;
  var $schema_url, $base_url, $checkout_url, $checkout_diagnose_url, 
      $request_url, $request_diagnose_url;
  var $ignore_order_total;
  var $mc_shipping_methods, $mc_shipping_methods_names; 
  var $cc_shipping_methods, $cc_shipping_methods_names;
  var $gc_order_states;

  // Constructor.
  function googlecheckout() {
    global $order, $messageStack;
    global $language;
    
    require_once(DIR_FS_CATALOG .'/includes/languages/' . $language . '/modules/payment/googlecheckout.php');
    require(DIR_FS_CATALOG . '/googlecheckout/library/shipping/merchant_calculated_methods.php');
    require(DIR_FS_CATALOG . '/googlecheckout/library/shipping/carrier_calculated_methods.php');
    require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration.php');
    require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration_keys.php');
    require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_options.php');
    
    $config = new GoogleConfigurationKeys();
    
    $this->code = 'googlecheckout';
    $this->title = MODULE_PAYMENT_GOOGLECHECKOUT_TEXT_TITLE;
    $this->description = MODULE_PAYMENT_GOOGLECHECKOUT_TEXT_DESCRIPTION;
    $this->sort_order = 0;
    $this->mode = MODULE_PAYMENT_GOOGLECHECKOUT_STATUS;
    if (MODULE_PAYMENT_GOOGLECHECKOUT_MODE == 'https://sandbox.google.com/checkout/') {
      $this->merchantid = trim(MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID_SNDBOX);
      $this->merchantkey = trim(MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY_SNDBOX);
    } else {
      $this->merchantid = trim(MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID);
      $this->merchantkey = trim(MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY);
    }
    $this->mode = MODULE_PAYMENT_GOOGLECHECKOUT_MODE;
    $this->enabled = ((MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True') ? true : false);
    if (gc_configuration_table_ready()) {
      $this->continue_url = gc_get_configuration_value($config->continueShoppingUrl());
    } else {
    	// TODO(eddavisson)
    }
    	
    // These are the flat shipping methods. Add any other that is not merchant calculated.
    $this->shipping_support = array("flat", "item", "itemint", "table");

    $this->schema_url = "http://checkout.google.com/schema/2";
    $this->base_url = $this->mode."cws/v2/Merchant/" . $this->merchantid;
    $this->checkout_url =  $this->base_url . "/checkout";
    $this->checkout_diagnose_url = $this->base_url . "/checkout/diagnose";
    $this->request_url = $this->base_url . "/request";
    $this->request_diagnose_url = $this->base_url . "/request/diagnose";
    $this->variant = 'text';
 	  
    // TODO(eddavisson): Revise this comment.
    // These are all the available methods for each shipping provider, 
    // see that you must set flat methods too!}
    // CONSTRAINT: Method's names MUST be UNIQUE
  	// Script to create new shipping methods
  	// http://ur-site/googlecheckot/shipping_generator/
    // to manually edit, /googlecheckout/shipping/merchant_calculated_methods.php
    $this->mc_shipping_methods = $mc_shipping_methods;
    $this->mc_shipping_methods_names = $mc_shipping_methods_names;
    
    // Carrier Calculated shipping methods.
    $this->cc_shipping_methods = $cc_shipping_methods;
    $this->cc_shipping_methods_names = $cc_shipping_methods_names;

	  $this->ignore_order_total = array(
        'ot_subtotal',
        'ot_shipping',
        'ot_coupon',
        'ot_tax',
        'ot_gv',
        'ot_total',
    );
    $this->hash = NULL;
    
    $this->gc_order_states = array( 
        '100' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_NEW,
        '101' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_PROCESSING,
        '102' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_SHIPPED,
        '103' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_REFUNDED,
        '104' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_SHIPPED_REFUNDED,
        '105' => GOOGLECHECKOUT_CUSTOM_ORDER_STATE_CANCELED
    );
  
    // TODO(eddavisson): Factor out into function.
    $is_sandbox = (MODULE_PAYMENT_GOOGLECHECKOUT_MODE == 'https://sandbox.google.com/checkout/');
    if (isset($messageStack) && $is_sandbox) {
      $messageStack->add_session(GOOGLECHECKOUT_STRING_WARN_USING_SANDBOX, 'warning');
    }
  }
  
  function getMethods() {
  	if ($this->hash == NULL) {
      $rta = array();
  		$this->_gethash($this->mc_shipping_methods, $rta);
  		$this->hash = $rta;
  	}
    return $this->hash;
  }

  function _gethash($arr, &$rta, $path = array()) {
    if (is_array($arr)) {
      foreach ($arr as $key => $val) {
        $this->_gethash($arr[$key], $rta, array_merge(array($key), $path));
      }
  	} else {
      $rta[$arr] = $path;
    }
  }

  // Function used from Google sample code to sign the cart contents with the merchant key.
  function CalcHmacSha1($data) {
    $key = $this->merchantkey;
    $blocksize = 64;
    $hashfunc = 'sha1';
    if (strlen($key) > $blocksize) {
      $key = pack('H*', $hashfunc($key));
    }
    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack('H*', 
                 $hashfunc(($key^$opad).pack('H*', 
                                             $hashfunc(($key^$ipad).$data))));
    return $hmac; 
  }
		
  function update_status() {
  }

  function javascript_validation() {
    return false;
  }

  function selection() {
    return array('id' => $this->code, 'module' => $this->title);
  }

  function pre_confirmation_check() {
    return false;
  }

  function confirmation() {
    return false;
  }

  function process_button() {
  }

  function before_process() {
    // To avoid using Google Checkout in the regular checkout flow.
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    return false;
  }

  function after_process() {
    return false;
  }

  function output_error() {
    return false;
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query(
          "select configuration_value from ". TABLE_CONFIGURATION 
          . " where configuration_key = 'MODULE_PAYMENT_GOOGLECHECKOUT_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }
  
  // With custom set function.
  function insertConfiguration($title,
                               $key,
                               $default_value,
                               $description,
                               $sort_order,
                               $set_function = NULL) {
    // List of columns.
    $column_list = "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added";
    if (!is_null($set_function)) {
    	$column_list .= ", set_function";
    }
    $column_list .= ")";
    
    // List of values.
    // Group ID for all module options.
    $group_id = '6';
    $value_array = array($title, $key, $default_value, $description, $group_id, $sort_order, 'now()');
    if (!is_null($set_function)) {
      $value_array[] = $set_function;
    }
    $value_list = "('" . join("', '", $value_array) . "')";
    
    // Create and run query.
    $query = "insert into " . TABLE_CONFIGURATION . " " . $column_list . " values " . $value_list;
    //echo $query;
    tep_db_query($query);
  }
  
  function getLink($text, $url, $new_window) {
    $a = '<a';
    $a .= ' style="color:blue;text-decoration:underline"';
    $a .= ' href="' . $url . '"';
    if ($new_window) {
    	$a .= ' target="_blank"';
    }
    $a .= '>' . $text . '</a>';
    return $a;  	
  }

  function getOscLink($text, $path) {
    return $this->getLink($text, tep_href_link($path), false);
  }
  
  function getWarning($message) {
  	$warning = '<span style="color:red">';
    $warning .= $message;
    $warning .= '</span>';
    return $warning;
  }

  function install() {
    global $language;
    require_once(DIR_FS_CATALOG . 'includes/languages/' . $language . '/modules/payment/googlecheckout.php');
    tep_db_query(
        "ALTER TABLE ". TABLE_CONFIGURATION 
        . " CHANGE `configuration_value` `configuration_value` TEXT NOT NULL");

    // Options will appear in the same order as we insert them in the code
    // if we increment this variable each time.
    $sort_order = 0;

    // NOTE(eddavisson): The configuration titles and descriptions are stored
    // in 255-character fields, so we need to be careful not to exceed that limit.
    // This is especially easy to do when you embed html in the title/description,
    // as we do in many places below.

    // Dummy dashboard link.
    $this->insertConfiguration(
        "For more options, see the " . $this->getOscLink("Advanced Configuration Dashboard", "gc_dashboard.php"),
        'MODULE_PAYMENT_GOOGLECHECKOUT_LINK',
        '',
        '',        
        $sort_order++,
        'tep_cfg_select_option(array(),');

    // Version #.
    $this->insertConfiguration(
        'Google Checkout Module Version', 
        'MODULE_PAYMENT_GOOGLECHECKOUT_VERSION',
        GOOGLECHECKOUT_FILES_VERSION,
        'Version of the installed Module',
        $sort_order++, 
        'tep_cfg_select_option(array(\\\'' . GOOGLECHECKOUT_FILES_VERSION . '\\\'),');
        
    // Enable/Disable.
    $this->insertConfiguration(
        'Enable the Google Checkout Module', 
        'MODULE_PAYMENT_GOOGLECHECKOUT_STATUS', 
        'True', 
        'Select "True" to accept payments through Google Checkout on your site.', 
        $sort_order++,
        'tep_cfg_select_option(array(\\\'True\\\', \\\'False\\\'),');
        
    // Mode.
    $this->insertConfiguration(
         'Mode of Operation', 
         'MODULE_PAYMENT_GOOGLECHECKOUT_MODE', 
         'https://sandbox.google.com/checkout/', 
         'Select <b>sandbox.google.com</b> (for testing) or <b>checkout.google.com</b> (live).'
             . ' Make sure you have entered the corresponding ID/Key pair below.'
             . ' When you are done testing, switch this option to <b>checkout.google.com</b>.', 
         $sort_order++, 
         'tep_cfg_select_option(array(\\\'https://sandbox.google.com/checkout/\\\', \\\'https://checkout.google.com/\\\'),');
         
    // Production Merchant ID.
    // TODO(eddavisson): Add link to Google Checkout Merchant Console.    
    $this->insertConfiguration(
        'Google Checkout Production Merchant ID', 
        'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID', 
        '', 
        'Your Merchant ID can be found in the Google Checkout Merchant Console'
            . ' under ' . $this->getLink('"Integration->Settings"', 
                                         'https://checkout.google.com/sell/settings?section=Integration', 
                                         true)
            . '.',
        $sort_order++);
        
    // Production Merchant Key.
    // TODO(eddavisson): Add link to Google Checkout Merchant Console.    
    $this->insertConfiguration(
        'Google Checkout Production Merchant Key'
            . '<br/>' . $this->getWarning('Note: We strongly recommend that you do not share your Merchant Key with anyone.'), 
        'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY', 
        '', 
        'Your Merchant Key can also be found in the Google Checkout Merchant Console'
            . ' under ' . $this->getLink('"Integration->Settings"', 
                                         'https://checkout.google.com/sell/settings?section=Integration', 
                                         true)
            . '.',
        $sort_order++);
        
    // Sandbox Merchant ID.
    // TODO(eddavisson): Add link to Google Checkout Merchant Console.    
    $this->insertConfiguration(
        'Google Checkout Sandbox Merchant ID', 
        'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID_SNDBOX', 
        '', 
        'Your Merchant ID can be found in the Google Checkout Merchant Console'
            . ' under ' . $this->getLink('"Integration->Settings"', 
                                         'https://sandbox.google.com/checkout/sell/settings?section=Integration', 
                                         true)
            . '.',
        $sort_order++);

    // Sandbox Merchant Key.
    // TODO(eddavisson): Add link to Google Checkout Merchant Console.
    $this->insertConfiguration(
        'Google Checkout Sandbox Merchant Key'
            . '<br/>' . $this->getWarning('Note: We strongly recommend that you do not share your Merchant Key with anyone.'),
        'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY_SNDBOX', 
        '', 
        'Your Merchant ID can be found in the Google Checkout Merchant Console'
            . ' under ' . $this->getLink('"Integration->Settings"', 
                                         'https://sandbox.google.com/checkout/sell/settings?section=Integration', 
                                         true)
            . '.',
        $sort_order++);

    tep_db_query("create table if not exists ". $this->table_name ." (customers_id int(11), buyer_id bigint(20))");
    tep_db_query("create table if not exists ". $this->table_order ." (orders_id int(11), google_order_number bigint(20), order_amount decimal(15,4))");

    // Add Google Checkout custom order states.
    $languages = tep_get_languages();
    foreach($this->gc_order_states as $orders_status_id => $orders_status_name) {
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
        $order_status_id = tep_db_fetch_array(tep_db_query(
            "select orders_status_id from " . TABLE_ORDERS_STATUS
            . " where orders_status_id = '" . (int) $orders_status_id
            . "' and language_id = '" . (int) $language_id . "'"));
        
        $sql_data_array = array(
            'orders_status_name' => tep_db_prepare_input($orders_status_name),
            'orders_status_id' => $orders_status_id,
            'language_id' => $language_id
            );

        if ($order_status_id['orders_status_id'] == '') {
          tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array);
        } else {
          tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array, 'update', 
              "orders_status_id = '" . (int) $orders_status_id
              . "' and language_id = '" . (int) $language_id . "'");
        }
      }
    }
    
    // Custom Google configuration.
    $google_configuration = new GoogleConfiguration();
    $google_configuration->install();
    
    // Set defaults.
    // TODO(eddavisson): It's awkward to have to construct one of these
    // in addition to the GoogleConfiguration above.
    $google_options = new GoogleOptions();
    
  }

  function remove() {
    tep_db_query(
        "delete from ". TABLE_CONFIGURATION 
        . " where configuration_key in ('". implode("', '", $this->keys()) ."')");
    // Remove Google Checkout's additional tables.
    // TODO(eddavisson): Should we do this? Should we not?
  //tep_db_query("drop table " . $this->table_name);
  //tep_db_query("drop table " . $this->table_order);
  
    // Custom Google removal.
    $google_configuration = new GoogleConfiguration();
    $google_configuration->remove();
  }

  function keys() {
    return array(
      'MODULE_PAYMENT_GOOGLECHECKOUT_LINK',
      'MODULE_PAYMENT_GOOGLECHECKOUT_VERSION',
      'MODULE_PAYMENT_GOOGLECHECKOUT_STATUS', 
      'MODULE_PAYMENT_GOOGLECHECKOUT_MODE',
      'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID',
      'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY',
      'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID_SNDBOX',
      'MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY_SNDBOX',
      );
  }
}

?>