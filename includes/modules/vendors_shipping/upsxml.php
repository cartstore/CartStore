<?php
/*
    $Id: upsxml.php,v 1.1.4 2004/12/19 13:30:00 sgo Exp $
  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

    Original copyright (c) 2003 Torin Walker
    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU General Public License for more details.
    You should have received a copy of the GNU General Public License along with this program;
    If not, you may obtain one by writing to and requesting one from:
    The Free Software Foundation, Inc.,
    59 Temple Place, Suite 330,
    Boston, MA 02111-1307 USA

    Written by Torin Walker.
    Some code/style borrowed from both Fritz Clapp's UPS Choice 1.7 Module,
    and Kelvin, Kenneth, and Tom St.Croix's Canada Post 3.1 Module.
*/

  require ('includes/classes/xmldocument.php');
// if using the optional dimensional support, set to 1, otherwise leave as 0
  define('DIMENSIONS_SUPPORTED', 0);

  class upsxml {
    var $code, $title, $description, $icon, $enabled, $types, $boxcount;

    //***************
    function upsxml() {
        global $_POST, $shipping_weight, $order, $cart, $shipping_num_boxes;

        $this->delivery_country_id = $order->delivery['country']['id'];
        $this->delivery_zone_id = $order->delivery['zone_id'];
        $this->code = 'upsxml';
        $this->title = MODULE_SHIPPING_UPSXML_RATES_TEXT_TITLE;
        $this->description = MODULE_SHIPPING_UPSXML_RATES_TEXT_DESCRIPTION;
        $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
        $this->protocol = 'https';
        $this->port = '443';
        $this->path = '/ups.app/xml/Rate';
        $this->transitpath = '/ups.app/xml/TimeInTransit';
        $this->version = 'UPSXML Rate 1.0001';
        $this->transitversion = 'UPSXML Time In Transit 1.0001';
        $this->timeout = '60';
        $this->xpci_version = '1.0001';
        $this->transitxpci_version = '1.0001';
        $this->items_qty = 0;
        $this->timeintransit = '0';
        $this->today = date("Ymd");
//        $this->host = ((constant('MODULE_SHIPPING_UPSXML_RATES_TEST_MODE_' . $vendors_id) == 'Test') ? 'wwwcie.ups.com' : 'wwwcie.ups.com');

// to enable logging, create an empty "upsxml.log" file at the location you set below, give it write permissions (777) and uncomment the next line
  //   $this->logfile = '/var/www/html/3/vendor-files/upsxml.log';

// when cURL is not compiled into PHP (Windows users, some Linux users)
// you can set the next variable to "1" and then exec(curl -d $xmlRequest, $xmlResponse)
// will be used
        $this->use_exec = '0';

        // Available pickup types - set in admin
        $this->pickup_methods = array(
            'Daily Pickup' => '01',
            'Customer Counter' => '03',
            'One Time Pickup' => '06',
            'On Call Air Pickup' => '07',
            'Letter Center' => '09',
            'Air Service Center' => '10'
        );

        // Available package types
        $this->package_types = array(
            'Unknown' => '00',
            'UPS Letter' => '01',
            'Customer Package' => '02',
            'UPS Tube' => '03',
            'UPS Pak' => '04',
            'UPS Express Box' => '21',
            'UPS 25kg Box' => '24',
            'UPS 10kg Box' => '25'
        );

        // Human-readable Service Code lookup table. The values returned by the Rates and Service "shop" method are numeric.
        // Using these codes, and the admininstratively defined Origin, the proper human-readable service name is returned.
        // Note: The origin specified in the admin configuration affects only the product name as displayed to the user.
        $this->service_codes = array(
            // US Origin
            'US Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_02,
                '03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_03,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_11,
                '12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_12,
                '13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_13,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_54,
                '59' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_59,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_65
            ),
            // Canada Origin
            'Canada Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_01,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_11,
                '12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_12,
                '13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_13,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_54
            ),
            // European Union Origin
            'European Union Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_54,
                '64' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_64,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_65
            ),
            // Puerto Rico Origin
            'Puerto Rico Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_02,
                '03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_03,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_08,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_54
            ),
            // Mexico Origin
            'Mexico Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_08,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_54
            ),
            // All other origins
            'All other origins' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_08,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_54
            )
        );
    }

    function access_key($vendors_id='1') {
      $this->access_key = constant('MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_' . $vendors_id);
      return $this->access_key;
    }

    function access_username($vendors_id='1') {
      $this->access_username = constant('MODULE_SHIPPING_UPSXML_RATES_USERNAME_' . $vendors_id);
      return $this->access_username;
    }

    function access_password($vendors_id='1') {
      $this->access_password = constant('MODULE_SHIPPING_UPSXML_RATES_PASSWORD_' . $vendors_id);
      return $this->access_password;
    }

    function origin($vendors_id='1') {
      $this->origin = constant('MODULE_SHIPPING_UPSXML_RATES_ORIGIN_' . $vendors_id);
      return $this->origin;
    }

    function origin_city($vendors_id='1') {
      $this->origin_city = constant('MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id);
      return $this->origin_city;
    }

    function origin_stateprov($vendors_id='1') {
      $this->origin_stateprov = constant('MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id);
      return $this->origin_stateprov;
    }

    function origin_country($vendors_id='1') {
      $this->origin_country = constant('MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id);
      return $this->origin_country;
    }

    function origin_postalcode($vendors_id='1') {
      $this->origin_postalcode = constant('MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id);
      return $this->origin_postalcode;
    }

    function pickup_method($vendors_id='1') {
      $this->pickup_method = constant('MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_' . $vendors_id);
      return $this->pickup_method;
    }

    function package_type($vendors_id='1') {
      $this->package_type = constant('MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_' . $vendors_id);
      return $this->package_type;
    }

    function unit_weight($vendors_id='1') {
      $this->unit_weight = constant('MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT_' . $vendors_id);
      return $this->unit_weight;
    }

    function unit_length($vendors_id='1') {
      $this->unit_length = constant('MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH_' . $vendors_id);
      return $this->unit_length;
    }

    function handling_fee($vendors_id='1') {
      $this->handling_fee = constant('MODULE_SHIPPING_UPSXML_RATES_HANDLING_' . $vendors_id);
      return $this->handling_fee;
    }

    function quote_type($vendors_id='1') {
      $this->quote_type = constant('MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_' . $vendors_id);
      return $this->quote_type;
    }

    function customer_classification($vendors_id='1') {
      $this->customer_classification = constant('MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_' . $vendors_id);
      return $this->customer_classification;
    }

    function host($vendors_id) {
      $this->host = @constant('MODULE_SHIPPING_UPSXML_RATES_TEST_MODE_' . $vendors_id) == 'Test' ? 'wwwcie.ups.com' : 'wwwcie.ups.com';
      return $this->host;
    }

   function sort_order($vendors_id='1') {
     $sort_order = @constant ('MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_' . $vendors_id);
     if (isset ($sort_order)) {
       $this->sort_order = $sort_order;
     } else {
       $this->sort_order = '-';
     }
     return $this->sort_order;
   }

    function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_' . $vendors_id);
      return $this->tax_class;
    }

    function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          }
           elseif ($check['zone_id'] == $this->delivery_zone_id) {
            $check_flag = true;
            break;
            }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }//if
      }//if
      return $this->enabled;
    }

    function zones($vendors_id='1') {
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_zone_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $this->delivery_zone_id) {
            $check_flag = true;
            break;
          } //if
        }//while

        if ($check_flag == false) {
          $this->enabled = false;
        }//if
      }//if
      return $this->enabled;
    }//function

    // class methods
    function quote($method = '', $module = '', $vendors_id = '1') {
        global $_POST, $order, $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $cart;
        // UPS purports that if the origin is left out, it defaults to the account's location. Yeah, right.
        $state = $order->delivery['state'];
        $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" .  $order->delivery['state'] . "'");
        if (tep_db_num_rows($zone_query)) {
            $zone = tep_db_fetch_array($zone_query);
            $state = $zone['zone_code'];
        }
        $this->_upsOrigin(constant('MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id));
        $this->_upsDest($order->delivery['city'], $state, $order->delivery['country']['iso_code_2'], $order->delivery['postcode']);
        $productsArray = $cart->get_products();

        if (DIMENSIONS_SUPPORTED) {
            // sort $productsArray according to ready-to-ship (first) and not-ready-to-ship (last)
            usort($productsArray, ready_to_shipCmp);
            // Use packing algoritm to return the number of boxes we'll ship
            $boxesToShip = $this->packProducts($productsArray);
            // Quote for the number of boxes
            for ($i = 0; $i < count($boxesToShip); $i++) {
                $this->_addItem($boxesToShip[$i]['length'], $boxesToShip[$i]['width'], $boxesToShip[$i]['height'], $boxesToShip[$i]['current_weight']);
                $totalWeight += $boxesToShip[$i]['current_weight'];
            }
        } else {
            // The old method. Let osCommerce tell us how many boxes, plus the weight of each (or total? - might be sw/num boxes)
                        $this->items_qty = 0; //reset quantities
            for ($i = 0; $i < $shipping_num_boxes; $i++) {
                $this->_addItem (0, 0, 0, $shipping_weight);
            }
        }

// BOF Time In Transit: comment out this section if you don't want/need to have
// expected delivery dates

        $this->servicesTimeintransit = $this->_upsGetTimeServices($vendors_id);
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("Time in Transit: " . $this->timeintransit . "\n", 3, $this->logfile);
        }

// EOF Time In Transit

        $upsQuote = $this->_upsGetQuote($vendors_id);
        if ((is_array($upsQuote)) && (sizeof($upsQuote) > 0)) {
            if (DIMENSIONS_SUPPORTED) {
                $this->quotes = array('id' => $this->code,
                                      'module' => $this->title . ' (' . $this->boxCount . ($this->boxCount > 1 ? ' pkg(s), ' : ' pkg, ') . $totalWeight . ' ' . strtolower($this->unit_weight) . ' total)'
                                           );
            } else {
                $this->quotes = array('id' => $this->code,
                                            'module' => $this->title . ' (' . $shipping_num_boxes . ($this->boxCount > 1 ? ' pkg(s) x ' : ' pkg x ') . $shipping_weight . ' ' . strtolower($this->unit_weight) . ' total)'
                                     );
            }

            $methods = array();
            for ($i=0; $i < sizeof($upsQuote); $i++) {
                list($type, $cost) = each($upsQuote[$i]);
                // BOF limit choices
                if (!exclude_choices($type, $vendors_id)) continue;
                // EOF limit choices
                if ( $method == '' || $method == $type ) {
                         $_type = $type;
                                 if (isset($this->servicesTimeintransit[$type])) {
                                        $_type = $_type . ", ".$this->servicesTimeintransit[$type]["date"];
                                }

                // instead of just adding the expected delivery date as ", yyyy-mm-dd"
                // you might like to change this to your own liking for example by commenting the
                // three lines above this and uncommenting/changing the next:
                // START doing things differently
                /*

                                 if (isset($this->servicesTimeintransit[$type])) {
                                 $eta_array = explode("-", $this->servicesTimeintransit[$type]["date"]);
                                 $months = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                 $eta_arrival_date = $months[(int)$eta_array[1]]." ".$eta_array[2].", ".$eta_array[0];
                                $_type .= ", <acronym title='Estimated Delivery Date'>EDD</acronym>: ".$eta_arrival_date;
                                }
  END of doing things differently:                */

                    $methods[] = array('id' => $type, 'title' => $_type, 'cost' => ($this->handling_fee + $cost));
                }
            }
            if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
           }
            $this->quotes['methods'] = $methods;
        } else {
            if ( $upsQuote != false ) {
                $errmsg = $upsQuote;
            } else {
                $errmsg = MODULE_SHIPPING_UPSXML_RATES_TEXT_UNKNOWN_ERROR;
            }
            $errmsg .= '<br>' . MODULE_SHIPPING_UPSXML_RATES_TEXT_IF_YOU_PREFER . ' ' . STORE_NAME.' via <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><u>Email</U></a>.';
            $this->quotes = array('module' => $this->title, 'error' => $errmsg);
        }
        if (tep_not_null($this->icon)) {
            $this->quotes['icon'] = tep_image($this->icon, $this->title);
        }
        return $this->quotes;
    }

    //**************
    function check($vendors_id='1') {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key = 'MODULE_SHIPPING_UPSXML_RATES_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    //**************
    function install($vendors_id='1') {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable UPS Shipping', 'MODULE_SHIPPING_UPSXML_RATES_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Access Key', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_" . $vendors_id . "', '', 'Enter the XML rates access key assigned to you by UPS.', '6', '1', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Username', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME_" . $vendors_id . "', '', 'Enter your UPS Services account username.', '6', '2', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Password', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD_" . $vendors_id . "', '', 'Enter your UPS Services account password.', '6', '3', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Pickup Method', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_" . $vendors_id . "', 'Daily Pickup', 'How do you give packages to UPS?', '6', '4', 'tep_cfg_select_option(array(\'Daily Pickup\', \'Customer Counter\', \'One Time Pickup\', \'On Call Air Pickup\', \'Letter Center\', \'Air Service Center\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Packaging Type', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_" . $vendors_id . "', 'Customer Package', 'What kind of packaging do you use?', '6', '5', 'tep_cfg_select_option(array(\'Customer Package\', \'UPS Letter\', \'UPS Tube\', \'UPS Pak\', \'UPS Express Box\', \'UPS 25kg Box\', \'UPS 10kg box\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Customer Classification Code', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_" . $vendors_id . "', '01', '01 - If you are billing to a UPS account and have a daily UPS pickup, 03 - If you do not have a UPS account or you are billing to a UPS account but do not have a daily pickup, 04 - If you are shipping from a retail outlet', '6', '6', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Shipping Origin', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN_" . $vendors_id . "', 'US Origin', 'What origin point should be used (this setting affects only what UPS product names are shown to the user)', '6', '7', 'tep_cfg_select_option(array(\'US Origin\', \'Canada Origin\', \'European Union Origin\', \'Puerto Rico Origin\', \'Mexico Origin\', \'All other origins\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin City', 'MODULE_SHIPPING_UPSXML_RATES_CITY_" . $vendors_id . "', '', 'Enter the name of the origin city.', '6', '8', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin State/Province', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV_" . $vendors_id . "', '', 'Enter the two-letter code for your origin state/province.', '6', '9', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin Country', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY_" . $vendors_id . "', '', 'Enter the two-letter code for your origin country.', '6', '10', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin Zip Code', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_" . $vendors_id . "', '', 'Enter your origin zip/postcode.', '6', '11', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Test or Production Mode', 'MODULE_SHIPPING_UPSXML_RATES_MODE_" . $vendors_id . "', 'Test', 'Use this module in Test or Production mode?', '6', '12', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Unit Weight', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT_" . $vendors_id . "', 'LBS', 'By what unit are your packages weighed?', '6', '13', 'tep_cfg_select_option(array(\'LBS\', \'KGS\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Unit Length', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH_" . $vendors_id . "', 'IN', 'By what unit are your packages sized?', '6', '14', 'tep_cfg_select_option(array(\'IN\', \'CM\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Quote Type', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_" . $vendors_id . "', 'Commercial', 'Quote for Residential or Commercial Delivery', '6', '15', 'tep_cfg_select_option(array(\'Commercial\', \'Residential\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Handling Fee', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING_" . $vendors_id . "', '0', 'Handling fee for this shipping method.', '6', '16', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '17', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_UPSXML_RATES_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '18', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort order of display.', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display. Lowest is displayed first.', '6', '19', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Shipping Methods', 'MODULE_SHIPPING_UPSXML_TYPES_" . $vendors_id . "', 'Next Day Air, 2nd Day Air, Ground, Worldwide Express, Standard, 3 Day Select', 'Select the UPS services to be offered.', '6', '20', 'tep_cfg_select_multioption(array(\'Next Day Air\', \'2nd Day Air\', \'Ground\', \'Worldwide Express\', \'Worldwide Expedited\', \'Standard\', \'3 Day Select\', \'Next Day Air Saver\', \'Next Day Air Early A.M.\', \'Worldwide Express Plus\', \'2nd Day Air A.M.\', \'Express NA1\', \'Express Saver\'), ',  now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function, vendors_id) values ('Shipping Delay', 'SHIPPING_DAYS_DELAY_" . $vendors_id . "', '1', 'How many days from when an order is placed to when you ship it (Decimals are allowed). Arrival date estimations are based on this value.', '6', '21', NULL, now(), NULL, NULL, '" . $vendors_id . "')");
    }

    //****************
    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    //*************
    function keys($vendors_id) {
      return array('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_USERNAME_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_PASSWORD_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_ORIGIN_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_MODE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_HANDLING_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_' . $vendors_id,
                   'MODULE_SHIPPING_UPSXML_TYPES_' . $vendors_id,
                   'SHIPPING_DAYS_DELAY_' . $vendors_id
                  );
    }

    //***********************
    function _upsProduct($prod, $vendors_id){
        $this->_upsProductCode = $prod;
    }

    //**********************************************
    function _upsOrigin($city, $stateprov, $country, $postal){
        $this->_upsOriginCity = $city;
        $this->_upsOriginStateProv = $stateprov;
        $this->_upsOriginCountryCode = $country;
        $postal = str_replace(' ', '', $postal);
        if ($country == 'US') {
            $this->_upsOriginPostalCode = substr($postal, 0, 5);
        } else {
            $this->_upsOriginPostalCode = $postal;
        }
    }

    //**********************************************
    function _upsDest($city, $stateprov, $country, $postal) {
        $this->_upsDestCity = $city;
        $this->_upsDestStateProv = $stateprov;
        $this->_upsDestCountryCode = $country;
        $postal = str_replace(' ', '', $postal);
        if ($country == 'US') {
            $this->_upsDestPostalCode = substr($postal, 0, 5);
        } else {
            $this->_upsDestPostalCode = $postal;
        }
    }

    //************************
    function _upsAction($action, $vendors_id) {
        // rate - Single Quote; shop - All Available Quotes
        $this->_upsActionCode = $action;
    }

    //********************************************
    function _addItem($length, $width, $height, $weight) {
        // Add box or item to shipment list. Round weights to 1 decimal places.
        if ((float)$weight < 1.0) {
            $weight = 1;
        } else {
            $weight = round($weight, 1);
        }
        $index = $this->items_qty;
        $this->item_length[$index] = ($length ? (string)$length : '0' );
        $this->item_width[$index] = ($width ? (string)$width : '0' );
        $this->item_height[$index] = ($height ? (string)$height : '0' );
        $this->item_weight[$index] = ($weight ? (string)$weight : '0' );
        $this->items_qty++;
    }

    //********************
    function getPackages() {
        $packages = array();
        $packages_query = tep_db_query("select * from " . TABLE_PACKAGING . " order by package_cost;");
        while ($package = tep_db_fetch_array($packages_query)) {
            $packages[] = array(
            'id' => $package['package_id'],
            'name' => $package['package_name'],
            'description' => $package['package_description'],
            'length' => $package['package_length'],
            'width' => $package['package_width'],
            'height' => $package['package_height'],
            'empty_weight' => $package['package_empty_weight'],
            'max_weight' => $package['package_max_weight'],
            'cost' => $package['package_cost']);
        }
        return $packages;
    }

    //********************************
    function packProducts($productsArray) {
        // A very simple box packing algorithm. Given a list of packages, returns an array of boxes.
        // This algorithm is trivial. It works on the premise that you have selected boxes that fit your products, and that their volumes are resonable multiples
        // of the products they store. For example, if you sell CDs and these CDs are 5x5x0.5", your boxes should be 5x5x0.5 (1 CD mailer), 5x5x2.5 (5 CD mailer)
        // and 5x5x5 (10 CD mailer). No matter how many CDs a customer buys, this routine will always find the optimal packing.
        // Your milage may differ, depending on what variety of products you sell, and how they're boxed. I just made up this algorithm in a hurry to fill a small
        // niche. You are encouraged to find better algorithms. Better algorithms mean better packaging, resulting in higher quoting accuracy and less loss due to
        // inaccurate quoting. The algorithm proceeds as follows:
        // Get the first, smallest box, and try to put everything into it. If not all of it fits, try fitting it all into the next largest box. Keep increasing
        // the size of the box until no larger box can be obtained, then spill over into a second, smallest box. Once again, increase the box size until
        // everything fits, or spill over again. Repeat until everything is boxed. The cost of a box determines the order in which it is tried. There will definitely
        // be cases where it is cheaper to send two small packages rather than one larger one. In that case, you'll need a better algorithm.
        // Get the available packages and "prepare" empty boxes with weight and remaining volume counters. (Take existing box and add 'remaining_volume' and 'current_weight';
        $definedPackages = $this->getPackages();
        $emptyBoxesArray = array();
        for ($i = 0; $i < count($definedPackages); $i++) {
            $definedBox = $definedPackages[$i];
            $definedBox['remaining_volume'] = $definedBox['length'] * $definedBox['width'] * $definedBox['height'];
            $definedBox['current_weight'] = $definedBox['empty_weight'];
            $emptyBoxesArray[] = $definedBox;
        }
        $packedBoxesArray = array();
        $currentBox = NULL;
        // Get the product array and expand multiple qty items.
        $productsRemaining = array();
        for ($i = 0; $i < count($productsArray); $i++) {
            $product = $productsArray[$i];
            for ($j = 0; $j < $productsArray[$i]['quantity']; $j++) {
                $productsRemaining[] = $product;
            }
        }
        // Worst case, you'll need as many boxes as products ordered.
        while (count($productsRemaining)) {
            // Immediately set aside products that are already packed and ready.
            if ($productsRemaining[0]['ready_to_ship'] == '1') {
                $packedBoxesArray[] = array (
                'length' => $productsRemaining[0]['length'],
                'width' => $productsRemaining[0]['width'],
                'height' => $productsRemaining[0]['height'],
                'current_weight' => $productsRemaining[0]['weight']);
                $productsRemaining = array_slice($productsRemaining, 1);
                continue;
            }
            //Cylcle through boxes, increasing box size if all doesn't fit.
            if (count($emptyBoxesArray) == 0) {
                print_r("ERROR: No boxes to ship unpackaged product<br>");
                break;
            }
            for ($b = 0; $b < count($emptyBoxesArray); $b++) {
                $currentBox = $emptyBoxesArray[$b];
                //Try to fit each product in box
                for ($p = 0; $p < count($productsRemaining); $p++) {
                    if ($this->fitsInBox($productsRemaining[$p], $currentBox)) {
                        //It fits. Put it in the box.
                        $currentBox = $this->putProductInBox($productsRemaining[$p], $currentBox);
                        if ($p == count($productsRemaining) - 1) {
                            $packedBoxesArray[] = $currentBox;
                            $productsRemaining = array_slice($productsRemaining, $p + 1);
                            break 2;
                        }
                    } else {
                        if ($b == count($emptyBoxesArray) - 1) {
                            //We're at the largest box already, and it's full. Keep what we've packed so far and get another box.
                            $packedBoxesArray[] = $currentBox;
                            $productsRemaining = array_slice($productsRemaining, $p + 1);
                            break 2;
                        }
                        // Not all of them fit. Stop packing remaining products and try next box.
                        break;
                    }
                }
            }
        }

        return $packedBoxesArray;
    }

    //*****************************
    function fitsInBox($product, $box) {
        $productVolume = $product['length'] * $product['width'] * $product['height'];
        if ($productVolume <= $box['remaining_volume']) {
            if ($box['max_weight'] == 0 || ($box['current_weight'] + $product['weight'] <= $box['max_weight'])) {
                return true;
            }
        }
        return false;
    }

    //***********************************
    function putProductInBox($product, $box) {
        $productVolume = $product['length'] * $product['width'] * $product['height'];
        $box['remaining_volume'] -= $productVolume;
        $box['products'][] = $product;
        $box['current_weight'] += $product['weight'];
        return $box;
    }

    //*********************
    function _upsGetQuote($vendors_id='1') {

        // Create the access request
        $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key($vendors_id) ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username($vendors_id) ."</UserId>\n".
        "   <Password>". $this->access_password($vendors_id) ."</Password>\n".
        "</AccessRequest>\n";

        $ratingServiceSelectionRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<RatingServiceSelectionRequest xml:lang=\"en-US\">\n".
        "   <Request>\n".
        "       <TransactionReference>\n".
        "           <CustomerContext>Rating and Service</CustomerContext>\n".
        "           <XpciVersion>". $this->xpci_version ."</XpciVersion>\n".
        "       </TransactionReference>\n".
        "       <RequestAction>Rate</RequestAction>\n".
        "       <RequestOption>shop</RequestOption>\n".
        "   </Request>\n".
        "   <PickupType>\n".
        "       <Code>". $this->pickup_methods[$this->pickup_method($vendors_id)] ."</Code>\n".
        "   </PickupType>\n".
        "   <Shipment>\n".
        "       <Shipper>\n".
        "           <Address>\n".
        "               <City>". $this->_upsOriginCity ."</City>\n".
        "               <StateProvinceCode>". $this->_upsOriginStateProv ."</StateProvinceCode>\n".
        "               <CountryCode>". $this->_upsOriginCountryCode ."</CountryCode>\n".
        "               <PostalCode>". $this->_upsOriginPostalCode ."</PostalCode>\n".
        "           </Address>\n".
        "       </Shipper>\n".
        "       <ShipTo>\n".
        "           <Address>\n".
        "               <City>". $this->_upsDestCity ."</City>\n".
        "               <StateProvinceCode>". $this->_upsDestStateProv ."</StateProvinceCode>\n".
        "               <CountryCode>". $this->_upsDestCountryCode ."</CountryCode>\n".
        "               <PostalCode>". $this->_upsDestPostalCode ."</PostalCode>\n".
        ($this->quote_type($vendors_id) == "Residential" ? "<ResidentialAddressIndicator/>\n" : "") .
        "           </Address>\n".
        "       </ShipTo>\n";
        for ($i = 0; $i < $this->items_qty; $i++) {

            $ratingServiceSelectionRequestPackageContent .=
            "       <Package>\n".
            "           <PackagingType>\n".
            "               <Code>". $this->package_types[$this->package_type($vendors_id)] ."</Code>\n".
            "           </PackagingType>\n";
            if (DIMENSIONS_SUPPORTED) {

                $ratingServiceSelectionRequestPackageContent .=
                "           <Dimensions>\n".
                "               <UnitOfMeasurement>\n".
                "                   <Code>". $this->unit_length($vendors_id) ."</Code>\n".
                "               </UnitOfMeasurement>\n".
                "               <Length>". $this->item_length[$i] ."</Length>\n".
                "               <Width>". $this->item_width[$i] ."</Width>\n".
                "               <Height>". $this->item_height[$i] ."</Height>\n".
                "           </Dimensions>\n";
            }

            $ratingServiceSelectionRequestPackageContent .=
            "           <PackageWeight>\n".
            "               <UnitOfMeasurement>\n".
            "                   <Code>". $this->unit_weight($vendors_id) ."</Code>\n".
            "               </UnitOfMeasurement>\n".
            "               <Weight>". $this->item_weight[$i] ."</Weight>\n".
            "           </PackageWeight>\n".
            //"           <PackageServiceOptions>\n."
            //"               <COD>\n".
            //"                   <CODFundsCode>0</CODFundsCode>\n".
            //"                   <CODCode>3</CODCode>\n".
            //"                   <CODAmount>\n".
            //"                       <CurrencyCode>USD</CurrencyCode>\n".
            //"                       <MonetaryValue>1000</MonetaryValue>\n".
            //"                   </CODAmount>\n".
            //"               </COD>\n".
            //"               <InsuredValue>\n".
            //"                   <CurrencyCode>USD</CurrencyCode>\n".
            //"                   <MonetaryValue>1000</MonetaryValue>\n".
            //"               </InsuredValue>\n".
            //"           </PackageServiceOptions>\n".
            "       </Package>\n";
        }

        $ratingServiceSelectionRequestFooter =
        //"   <ShipmentServiceOptions/>\n".
        "   </Shipment>\n".
        "   <CustomerClassification>\n".
        "       <Code>". $this->customer_classification($vendors_id) ."</Code>\n".
        "   </CustomerClassification>\n".
        "</RatingServiceSelectionRequest>\n";

        $xmlRequest = $accessRequestHeader .
        $ratingServiceSelectionRequestHeader .
        $ratingServiceSelectionRequestPackageContent .
        $ratingServiceSelectionRequestFooter;

                                $host = $this->host($vendors_id);

        //post request $strXML;
        $xmlResult = $this->_post($this->protocol, $host, $this->port, $this->path, $this->version, $this->timeout, $xmlRequest);

        return $this->_parseResult($xmlResult, $vendors_id);
    }

    //******************************************************************
    function _post($protocol, $host, $port, $path, $version, $timeout, $xmlRequest) {
        $url = $protocol."://".$host.":".$port.$path;
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("DATE AND TIME: ".date('Y-m-d H:i:s')."\n", 3, $this->logfile);
            error_log("UPS URL: " . $url . "\n", 3, $this->logfile);
        }
        if (function_exists('exec') && $this->use_exec == '1' ) {
        exec('which curl', $curl_output);
        if ($curl_output) {
        $curl_path = $curl_output[0];
        }
        else {
        $curl_path = 'curl'; // change this if necessary
        }
        if ($this->logfile) {
            error_log("UPS REQUEST using exec(): " . $xmlRequest . "\n", 3, $this->logfile);
        }
// add option -k to the statement: $command = "".$curl_path." -k -d \"". etcetera if you get
// curl error 60: error setting certificate verify locations
// using addslashes was the only way to avoid UPS returning the 1001 error: The XML document is not well formed
        $command = "".$curl_path." -d \"".addslashes($xmlRequest)."\" ".$url."";
        exec($command, $xmlResponse);
        if ( empty($xmlResponse) && $this->logfile) { // using exec no curl errors can be retrieved
            error_log("Error from cURL using exec() since there is no \$xmlResponse\n", 3, $this->logfile);
        }
        if ($this->logfile) {
            error_log("UPS RESPONSE using exec(): " . $xmlResponse[0] . "\n", 3, $this->logfile);
        }
        } elseif ($this->use_exec == '1') { // if NOT (function_exists('exec') && $this->use_exec == '1'
        if ($this->logfile) {
            error_log("Sorry, exec() cannot be called\n", 3, $this->logfile);
        }
        } else { // default behavior: cURL is assumed to be compiled in PHP
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // uncomment the next line if you get curl error 60: error setting certificate verify locations
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // uncommenting the next line is most likely not necessary in case of error 60
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int)$timeout);

        if ($this->logfile) {
            error_log("UPS REQUEST: " . $xmlRequest . "\n", 3, $this->logfile);
        }
        $xmlResponse = curl_exec ($ch);
        if (curl_errno($ch) && $this->logfile) {
        $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
            error_log("Error from cURL: " . $error_from_curl . "\n", 3, $this->logfile);
        }
        if ($this->logfile) {
            error_log("UPS RESPONSE: " . $xmlResponse . "\n", 3, $this->logfile);
        }
        curl_close ($ch);
        }

        if(!$xmlResponse)  {
            $xmlResponse = "<?xml version=\"1.0\"?>\n".
            "<RatingServiceSelectionResponse>\n".
            "   <Response>\n".
            "       <TransactionReference>\n".
            "           <CustomerContext>Rating and Service</CustomerContext>\n".
            "           <XpciVersion>1.0001</XpciVersion>\n".
            "       </TransactionReference>\n".
            "       <ResponseStatusCode>0</ResponseStatusCode>\n".
            "       <ResponseStatusDescription>". MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_UNKNOWN_ERROR ."</ResponseStatusDescription>\n".
            "   </Response>\n".
            "</RatingServiceSelectionResponse>\n";
        return $xmlResponse;
        }
        if ($this->use_exec == '1') {
        return $xmlResponse[0]; // $xmlResponse is an array in this case
        } else {
        return $xmlResponse;
        }
    }

    //*****************************
    function _parseResult($xmlResult, $vendors_id) {
        // Parse XML message returned by the UPS post server.
        $doc = new XMLDocument();
        $xp = new XMLParser();
        $xp->setDocument($doc);
        $xp->parse($xmlResult);
        $doc = $xp->getDocument();
        // Get version. Must be xpci version 1.0001 or this might not work.
        $responseVersion = $doc->getValueByPath('RatingServiceSelectionResponse/Response/TransactionReference/XpciVersion');
        if ($this->xpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        }
        // Get response code. 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc->getValueByPath('RatingServiceSelectionResponse/Response/ResponseStatusCode');
        if ($responseStatusCode != '1') {
            $errorMsg = $doc->getValueByPath('RatingServiceSelectionResponse/Response/Error/ErrorCode');
            $errorMsg .= ": ";
            $errorMsg .= $doc->getValueByPath('RatingServiceSelectionResponse/Response/Error/ErrorDescription');
            return $errorMsg;
        }
        $root = $doc->getRoot();
        $ratedShipments = $root->getElementsByName("RatedShipment");
        $aryProducts = false;
        for ($i = 0; $i < count($ratedShipments); $i++) {
            $serviceCode = $ratedShipments[$i]->getValueByPath("/Service/Code");
            $totalCharge = $ratedShipments[$i]->getValueByPath("/TotalCharges/MonetaryValue");
            if (!($serviceCode && $totalCharge)) {
                continue;
            }
            $ratedPackages = $ratedShipments[$i]->getElementsByName("RatedPackage");
            $this->boxCount = count($ratedPackages);
            $gdaysToDelivery = $ratedShipments[$i]->getValueByPath("/GuaranteedDaysToDelivery");
            $scheduledTime = $ratedShipments[$i]->getValueByPath("/ScheduledDeliveryTime");
            $title = '';
            $title = $this->service_codes[$this->origin($vendors_id)][$serviceCode];

                  /* we don't want to use this, it may conflict with time estimation
            if ($gdaysToDelivery) {
                $title .= ' (';
                $title .= $gdaysToDelivery . " Business Days";
                if ($scheduledTime) {
                    $title .= ' @ ' . $scheduledTime;
                }
                $title .= ')';
            } elseif ($this->timeintransit > 0) {
                $title .= ' (';
                $title .= $this->timeintransit . " Business Days";
                $title .= ')';
            }
                  */
            $aryProducts[$i] = array($title => $totalCharge);
        }
        return $aryProducts;
    }

// BOF Time In Transit

// GM 11-15-2004: renamed from _upsGetTime()

    //********************
    function _upsGetTimeServices($vendors_id) {

      if (constant('SHIPPING_DAYS_DELAY_' . $vendors_id) > 0) {
        $shipdate = date("Ymd", time()+(86400*constant('SHIPPING_DAYS_DELAY_' . $vendors_id)));
      } else {
        $shipdate = $this->today;
      }

        // Create the access request
      $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key($vendors_id) ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username($vendors_id) ."</UserId>\n".
        "   <Password>". $this->access_password($vendors_id) ."</Password>\n".
        "</AccessRequest>\n";

        $timeintransitSelectionRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<TimeInTransitRequest xml:lang=\"en-US\">\n".
        "   <Request>\n".
        "       <TransactionReference>\n".
        "           <CustomerContext>Time in Transit</CustomerContext>\n".
        "           <XpciVersion>". $this->transitxpci_version ."</XpciVersion>\n".
        "       </TransactionReference>\n".
        "       <RequestAction>TimeInTransit</RequestAction>\n".
        "   </Request>\n".
        "   <TransitFrom>\n".
        "       <AddressArtifactFormat>\n".
        "           <PoliticalDivision2>". $this->origin_city($vendors_id) ."</PoliticalDivision2>\n".
        "           <PoliticalDivision1>". $this->origin_stateprov($vendors_id) ."</PoliticalDivision1>\n".
        "           <CountryCode>". $this->_upsOriginCountryCode ."</CountryCode>\n".
        "           <PostcodePrimaryLow>". $this->origin_postalcode($vendors_id) ."</PostcodePrimaryLow>\n".
        "       </AddressArtifactFormat>\n".
        "   </TransitFrom>\n".
        "   <TransitTo>\n".
        "       <AddressArtifactFormat>\n".
        "           <PoliticalDivision2>". $this->_upsDestCity ."</PoliticalDivision2>\n".
        "           <PoliticalDivision1>". $this->_upsDestStateProv ."</PoliticalDivision1>\n".
        "           <CountryCode>". $this->_upsDestCountryCode ."</CountryCode>\n".
        "           <PostcodePrimaryLow>". $this->_upsDestPostalCode ."</PostcodePrimaryLow>\n".
        "           <PostcodePrimaryHigh>". $this->_upsDestPostalCode ."</PostcodePrimaryHigh>\n".
        "       </AddressArtifactFormat>\n".
        "   </TransitTo>\n".
        "   <PickupDate>" . $shipdate . "</PickupDate>\n".
        "   <ShipmentWeight>\n".
        "       <UnitOfMeasurement>\n".
        "           <Code>" . $this->unit_weight($vendors_id) . "</Code>\n".
        "       </UnitOfMeasurement>\n".
        "       <Weight>10</Weight>\n".
        "   </ShipmentWeight>\n".
        "   <InvoiceLineTotal>\n".
        "       <CurrencyCode>USD</CurrencyCode>\n".
        "       <MonetaryValue>100</MonetaryValue>\n".
        "   </InvoiceLineTotal>\n".
        "</TimeInTransitRequest>\n";

        $xmlTransitRequest = $accessRequestHeader .
        $timeintransitSelectionRequestHeader;

        //post request $strXML;
        $xmlTransitResult = $this->_post($this->protocol, $this->host($vendors_id), $this->port, $this->transitpath, $this->transitversion, $this->timeout, $xmlTransitRequest);
        return $this->_transitparseResult($xmlTransitResult);
    }

//***************************************

// GM 11-15-2004: modified to return array with time for each service, as
//                opposed to single transit time for hardcoded "GND" code

    function _transitparseResult($xmlTransitResult) {
               $transitTime = array();

        // Parse XML message returned by the UPS post server.
        $doc = new XMLDocument();
        $xp = new XMLParser();
        $xp->setDocument($doc);
        $xp->parse($xmlTransitResult);
        $doc = $xp->getDocument();
        // Get version. Must be xpci version 1.0001 or this might not work.
        $responseVersion = $doc->getValueByPath('TimeInTransitResponse/Response/TransactionReference/XpciVersion');
        if ($this->transitxpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        }
        // Get response code. 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc->getValueByPath('TimeInTransitResponse/Response/ResponseStatusCode');
        if ($responseStatusCode != '1') {
            $errorMsg = $doc->getValueByPath('TimeInTransitResponse/Response/Error/ErrorCode');
            $errorMsg .= ": ";
            $errorMsg .= $doc->getValueByPath('TimeInTransitResponse/Response/Error/ErrorDescription');
            return $errorMsg;
        }
        $root = $doc->getRoot();
        $rootChildren = $root->getChildren();
        for ($r = 0; $r < count($rootChildren); $r++) {
            $elementName = $rootChildren[$r]->getName();
            if ($elementName == "TransitResponse") {
                $transitResponse = $root->getElementsByName("TransitResponse");
                $serviceSummary = $transitResponse['0']->getElementsByName("ServiceSummary");
                $this->numberServices = count($serviceSummary);
                for ($s = 0; $s < $this->numberServices ; $s++) {
                                 // index by Desc because that's all we can relate back to the service with
                                // (though it can probably return the code as well..)
                    $serviceDesc = $serviceSummary[$s]->getValueByPath("Service/Description");

                                $transitTime[$serviceDesc]["days"] = $serviceSummary[$s]->getValueByPath("EstimatedArrival/BusinessTransitDays");
                                $transitTime[$serviceDesc]["date"] = $serviceSummary[$s]->getValueByPath("EstimatedArrival/Date");
                                $transitTime[$serviceDesc]["guaranteed"] = $serviceSummary[$s]->getValueByPath("Guaranteed/Code");
                }
            }
        }
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
                  foreach($transitTime as $desc => $time) {
                     error_log("Business Transit: " . $desc ." = ". $time["date"] . "\n", 3, $this->logfile);
                  }
        }
        return $transitTime;
    }

//EOF Time In Transit

}

//***************************
  function exclude_choices($type, $vendors_id) {
    // used for exclusion of UPS shipping options, read from db
    $allowed_types = explode(",", @constant('MODULE_SHIPPING_UPSXML_TYPES_' . $vendors_id));
    if (strstr($type, "UPS")) {
        // this will chop off "UPS" from the beginning of the line - typically something like UPS Next Day Air (1 Business Days)
      $type_minus_ups = explode("UPS", $type );
      if (strstr($type, "(")) {
            // this will chop off (x Business Days)
        $type_minus_bd = explode("(", $type_minus_ups[1] );
            // get rid of white space with trim
        $type_root = trim($type_minus_bd[0]);
      } else { // end if (strstr($type, "("))
            // if service description contains UPS but not (x Business days):
        $type_root = trim($type_minus_ups[1]);
      } // end if (strstr($type, "UPS"):
    } elseif (strstr($type, "(")) {
        // if service description doesn't contain UPS, but does (x Business Days):
      $type_minus_ups_bd = explode("(", $type );
      $type_root = trim($type_minus_ups_bd[0]);
    } else { // service description neither contain UPS nor (x Business Days)
      $type_root = trim($type);
    }
    for ($za = 0; $za < count ($allowed_types); $za++ ) {
      if ($type_root == trim($allowed_types[$za])) {
        return true;
        exit;
      } // end if ($type_root == $allowed_types[$za] ...
    }
    // if the type is not allowed:
    return false;
}

//******************************
function ready_to_shipCmp( $a, $b) {
    if ( $a['ready_to_ship'] == $b['ready_to_ship'] )
    return 0;
    if ( $a['ready_to_ship'] > $b['ready_to_ship'] )
    return -1;
    return 1;
}

?>