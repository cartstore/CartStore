<?php
/*
    $Id: upsxml.php,v 1.3.7 2009/08/27 JanZ Exp $

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
    Insurance support by Joe McFrederick
*/

// Incorporate the XML conversion library 
  if (PHP_VERSION >= '5.0.0') { // PHP 5 does not need to use call-time pass by reference
    require_once (DIR_WS_CLASSES . 'xml_5.php');
  } else {
    require_once (DIR_WS_CLASSES . 'xml.php');
  }


class upsxml {
    var $code, $title, $description, $icon, $enabled, $types, $boxcount;

    //***************
    function upsxml() {
        global $order, $packing;
        $this->code = 'upsxml';
        $this->title = MODULE_SHIPPING_UPSXML_RATES_TEXT_TITLE;
        $this->description = MODULE_SHIPPING_UPSXML_RATES_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER;
        $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
        $this->tax_class = MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS;
        $this->enabled = ((MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') ? true : false);
        $this->access_key = MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY;
        $this->access_username = MODULE_SHIPPING_UPSXML_RATES_USERNAME;
        $this->access_password = MODULE_SHIPPING_UPSXML_RATES_PASSWORD;
        $this->access_account_number = MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER;
        // BLM 2-14-08 MANUAL NEGOTIATED RATE
		    // set the rate
		    $this->manual_negotiated_rate = intval(MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE);
        $this->use_negotiated_rates = MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES;
        $this->origin = MODULE_SHIPPING_UPSXML_RATES_ORIGIN;
        $this->origin_city = MODULE_SHIPPING_UPSXML_RATES_CITY;
        $this->origin_stateprov = MODULE_SHIPPING_UPSXML_RATES_STATEPROV;
        $this->origin_country = MODULE_SHIPPING_UPSXML_RATES_COUNTRY;
        $this->origin_postalcode = MODULE_SHIPPING_UPSXML_RATES_POSTALCODE;
        $this->pickup_method = MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD;
        $this->package_type = MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE;
// the variables for unit weight, unit length, and dimensions support were moved to
// shop admin -> Configuration -> Shipping/Packaging in
// version 1.3.0. Run the configuration_shipping.sql to add these to your configuration
        if (defined('SHIPPING_UNIT_WEIGHT')) {
          $this->unit_weight = SHIPPING_UNIT_WEIGHT;
        } else {
// for those who will undoubtedly forget or not know how to run the configuration_shipping.sql
// we will set the default to pounds (LBS) and inches (IN)
          $this->unit_weight = 'LBS';
        }
        if (defined('SHIPPING_UNIT_LENGTH')) {
          $this->unit_length = SHIPPING_UNIT_LENGTH;
        } else {
          $this->unit_length = 'IN';
        }
        if (defined('SHIPPING_DIMENSIONS_SUPPORT') && SHIPPING_DIMENSIONS_SUPPORT == 'Ready-to-ship only') {
          $this->dimensions_support = 1;
        } elseif (defined('SHIPPING_DIMENSIONS_SUPPORT') && SHIPPING_DIMENSIONS_SUPPORT == 'With product dimensions') {
          $this->dimensions_support = 2;
        } else {
          $this->dimensions_support = 0;
        }
        $this->email_errors = ((MODULE_SHIPPING_UPSXML_EMAIL_ERRORS == 'Yes') ? true : false);
        $this->handling_type = MODULE_SHIPPING_UPSXML_HANDLING_TYPE;
        $this->handling_fee = MODULE_SHIPPING_UPSXML_RATES_HANDLING;
        $this->quote_type = MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE;
        $this->customer_classification = MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE;
        $this->protocol = 'https';
        $this->host = ((MODULE_SHIPPING_UPSXML_RATES_MODE == 'Test') ? 'wwwcie.ups.com' : 'www.ups.com');
        $this->port = '443';
        $this->path = '/ups.app/xml/Rate';
        $this->transitpath = '/ups.app/xml/TimeInTransit';
        $this->version = 'UPSXML Rate 1.0001';
        $this->transitversion = 'UPSXML Time In Transit 1.0002';
        $this->timeout = '60';
        $this->xpci_version = '1.0001';
        $this->transitxpci_version = '1.0002';
        $this->items_qty = 0;
        $this->timeintransit = '0';
        $this->timeInTransitView = MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW;
        $this->weight_for_timeintransit = '0';
        $now_unix_time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        $this->today_unix_time = $now_unix_time;
        $this->today = date("Ymd");
        // insurance addition
        $this->insure_package = false;
        if (MODULE_SHIPPING_UPSXML_INSURE == 'True') { 
          $this->insure_package = true;
        }
        // end insurance addition
        $this->pkgvalue = ceil($order->info['subtotal']); // is divided by number of boxes later
        // to enable logging, create an empty "upsxml.log" file at the location you set below, give it write permissions (777) and uncomment the next line
//        $this->logfile = '/srv/www/htdocs/catalog/includes/modules/shipping/upsxml.log';
        // to enable logging of just the errors, do as above but call the file upsxml_error.log
//        $this->ups_error_file = '/srv/www/htdocs/catalog/includes/modules/shipping/upsxml_error.log';
        // when cURL is not compiled into PHP (Windows users, some Linux users)
        // you can set the next variable to "1" and then exec(curl -d $xmlRequest, $xmlResponse)
        // will be used
        $this->use_exec = '0';

        if (($this->enabled == true) && ((int)MODULE_SHIPPING_UPSXML_RATES_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_UPSXML_RATES_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }
            if ($check_flag == false) {
                $this->enabled = false;
            }
        }

        // Available pickup types - set in admin
        $this->pickup_methods = array(
            'Daily Pickup' => '01',
            'Customer Counter' => '03',
            'One Time Pickup' => '06',
            'On Call Air Pickup' => '07',
            'Suggested Retail Rates (UPS Store)' => '11',
            'Letter Center' => '19',
            'Air Service Center' => '20'
        );

        // Available package types
        $this->package_types = array(
            'UPS Letter' => '01',
            'Package' => '02',
            'UPS Tube' => '03',
            'UPS Pak' => '04',
            'UPS Express Box' => '21',
            'UPS 25kg Box' => '24',
            'UPS 10kg Box' => '25'
        );

        // Human-readable Service Code lookup table. The values returned by the Rates and Service "shop" method are numeric.
        // Using these codes, and the administratively defined Origin, the proper human-readable service name is returned.
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
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_02,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_11,
                '12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_12,
                '13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_13,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_65
            ),
            // European Union Origin
            'European Union Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_65,
                // next five services Poland domestic only
                '82' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_82,
                '83' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_83,
                '84' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_84,
                '85' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_85,
                '86' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_86
            ),
            // Puerto Rico Origin
            'Puerto Rico Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_02,
                '03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_03,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_08,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_65
            ),
            // Mexico Origin
            'Mexico Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_08,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_65
            ),
            // All other origins
            'All other origins' => array(
                // service code 7 seems to be gone after January 2, 2007
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_65
            )
        );
    } // end function upsxml

    // class methods
    function quote($method = '') {
        global $_POST, $order, $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $cart, $packing;
        // UPS purports that if the origin is left out, it defaults to the account's location. Yeah, right.
        $state = $order->delivery['state'];
        $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" .  tep_db_input($order->delivery['state']) . "' and zone_country_id = '" . $order->delivery['country']['id'] . "'");
        if (tep_db_num_rows($zone_query)) {
            $zone = tep_db_fetch_array($zone_query);
            $state = $zone['zone_code'];
        }
        $this->_upsOrigin(MODULE_SHIPPING_UPSXML_RATES_CITY, MODULE_SHIPPING_UPSXML_RATES_STATEPROV, MODULE_SHIPPING_UPSXML_RATES_COUNTRY, MODULE_SHIPPING_UPSXML_RATES_POSTALCODE);
        $this->_upsDest($order->delivery['city'], $state, $order->delivery['country']['iso_code_2'], $order->delivery['postcode']);
        
// the check on $packing being an object will puzzle people who do things wrong (no changes when 
// you enable dimensional support without changing checkout_shipping.php) but better be safe
        if ($this->dimensions_support > 0 && is_object($packing)) {
          $boxValue = 0;
          $totalWeight = $packing->getTotalWeight();
          $boxesToShip = $packing->getPackedBoxes();
          for ($i = 0; $i < count($boxesToShip); $i++) {
            $this->_addItem($boxesToShip[$i]['item_length'], $boxesToShip[$i]['item_width'], $boxesToShip[$i]['item_height'], $boxesToShip[$i]['item_weight'], $boxesToShip[$i]['item_price']);
          } // end for ($i = 0; $i < count($boxesToShip); $i++)
        } else {
            // The old method. Let osCommerce tell us how many boxes, plus the weight of each (or total? - might be sw/num boxes)
          $this->items_qty = 0; //reset quantities
// $this->pkgvalue has been set as order subtotal around line 108, it will cause overcharging
// of insurance if not divided by the number of boxes
          for ($i = 0; $i < $shipping_num_boxes; $i++) {
            $this->_addItem(0, 0, 0, $shipping_weight, number_format(($this->pkgvalue/$shipping_num_boxes), 2, '.', ''));
          }
        }

      // BOF Time In Transit: used for expected delivery dates
      // is skipped when set to "Not" in the admin
      if ($this->timeInTransitView != 'Not') {
        if ($this->dimensions_support > 0) {
            $this->weight_for_timeintransit = round($totalWeight,1);
        } else {
            $this->weight_for_timeintransit = round($shipping_num_boxes * $shipping_weight,1);
        }
        // Added to workaround time in transit error 270033 if total weight of packages is over 150lbs or 70kgs
        if (($this->weight_for_timeintransit > 150) && ($this->unit_weight == "LBS")) {
          $this->weight_for_timeintransit = 150;          
        } else if (($this->weight_for_timeintransit > 70) && ($this->unit_weight == "KGS")) {
          $this->weight_for_timeintransit = 70;          
        }
        // debug only:
        /*  echo '<pre>Packages and variables:<br />';
         print_r($this);
         echo '<br />';
         exit; */
        // make sure that when TimeinTransit fails to get results (error or not available)
        // this is not obvious to the client
        $_upsGetTimeServicesResult = $this->_upsGetTimeServices();
        if ($_upsGetTimeServicesResult != false && is_array($_upsGetTimeServicesResult)) {
          $this->servicesTimeintransit = $_upsGetTimeServicesResult;
        }
        if (isset($this->logfile)) {
          error_log("------------------------------------------\n", 3, $this->logfile);
          error_log("Time in Transit: " . $this->timeintransit . "\n", 3, $this->logfile);
        }
      } // end if ($this->timeInTransitView != 'Not') 
      // EOF Time In Transit

        $upsQuote = $this->_upsGetQuote();
        if ((is_array($upsQuote)) && (sizeof($upsQuote) > 0)) {
          if (defined('MODULE_SHIPPING_UPSXML_WEIGHT1') &&  MODULE_SHIPPING_UPSXML_WEIGHT1 == 'False') {
            $this->quotes = array('id' => $this->code, 'module' => $this->title);
            usort($upsQuote, array($this, "rate_sort_func"));
          } else {
            if ($this->dimensions_support > 0) {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $this->boxCount . ($this->boxCount > 1 ? ' pkgs, ' : ' pkg, ') . ceil($totalWeight) . ' ' . strtolower($this->unit_weight) . ' total)');
            } else {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $shipping_num_boxes . ($this->boxCount > 1 ? ' pkgs x ' : ' pkg x ') . ceil($shipping_weight) . ' ' . strtolower($this->unit_weight) . ' total)');
            }
            usort($upsQuote, array($this, "rate_sort_func"));
          } // end else/if if (defined('MODULE_SHIPPING_UPSXML_WEIGHT1')
            $methods = array();
            for ($i=0; $i < sizeof($upsQuote); $i++) {
                list($type, $cost) = each($upsQuote[$i]);
                if (strpos($type, ' (')) {
                  $basetype = substr($type, 0, strpos($type, ' ('));
                } else {
                  $basetype = $type;
                }
                // BOF limit choices, behaviour changed from versions < 1.2
                if ($this->exclude_choices($basetype)) continue;
                // EOF limit choices
                if ( $method == '' || $method == $basetype ) {
                    $_type = $type;

                    if ($this->timeInTransitView == "Raw") {
                      if (isset($this->servicesTimeintransit[$basetype])) {
                        $_type = $_type . ", ".$this->servicesTimeintransit[$basetype]["date"];
                      }        
                    } else {
                      if (isset($this->servicesTimeintransit[$basetype])) {
                        $eta_array = explode("-", $this->servicesTimeintransit[$basetype]["date"]);
                        $months = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $eta_arrival_date = $months[(int)$eta_array[1]]." ".$eta_array[2].", ".$eta_array[0];
                        $_type .= ", <acronym title='Estimated Delivery Date'>EDD</acronym>: ".$eta_arrival_date;
                      }          
                    }                    
					// changed to make handling percentage based
                    if ($this->handling_type == "Percentage") {
                        if ($_type) $methods[] = array('id' => $basetype, 'title' => $_type, 'cost' => ((($this->handling_fee * $cost)/100) + $cost));
                    } else {
                        if ($_type) $methods[] = array('id' => $basetype, 'title' => $_type, 'cost' => ($this->handling_fee + $cost));
                    }
                }
            }
            if ($this->tax_class > 0) {
                $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            }
            $this->quotes['methods'] = $methods;
        } else {
            if ( $upsQuote != false ) {
                $errmsg = $upsQuote;
            } else {
                $errmsg = MODULE_SHIPPING_UPSXML_RATES_TEXT_UNKNOWN_ERROR;
            }
            $errmsg .= '<br>' . MODULE_SHIPPING_UPSXML_RATES_TEXT_IF_YOU_PREFER . ' ' . STORE_NAME.' via <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><u>Email</u></a>.';
            $this->quotes = array('module' => $this->title, 'error' => $errmsg);
        }
        if (tep_not_null($this->icon)) {
            $this->quotes['icon'] = tep_image($this->icon, $this->title);
        }
        return $this->quotes;
    }

    //**************
    function check() {
        if (!isset($this->_check)) {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPSXML_RATES_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }

    //**************
    function install() {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable UPS Shipping', 'MODULE_SHIPPING_UPSXML_RATES_STATUS', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Rates Access Key', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY', '', 'Enter the XML rates access key assigned to you by UPS.', '6', '1', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Rates Username', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME', '', 'Enter your UPS Services account username.', '6', '2', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Rates Password', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD', '', 'Enter your UPS Services account password.', '6', '3', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Pickup Method', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD', 'Daily Pickup', 'How do you give packages to UPS (only used when origin is US)?', '6', '4', 'tep_cfg_select_option(array(\'Daily Pickup\', \'Customer Counter\', \'One Time Pickup\', \'On Call Air Pickup\', \'Letter Center\', \'Air Service Center\', \'Suggested Retail Rates (UPS Store)\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Packaging Type', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE', 'Package', 'What kind of packaging do you use?', '6', '5', 'tep_cfg_select_option(array(\'Package\', \'UPS Letter\', \'UPS Tube\', \'UPS Pak\', \'UPS Express Box\', \'UPS 25kg Box\', \'UPS 10kg box\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Classification Code', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE', '01', '01 - If you are billing to a UPS account and have a daily UPS pickup, 03 - If you do not have a UPS account or you are billing to a UPS account but do not have a daily pickup, 04 - If you are shipping from a retail outlet (only used when origin is US)', '6', '6', 'tep_cfg_select_option(array(\'01\', \'03\', \'04\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shipping Origin', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN', 'US Origin', 'What origin point should be used (this setting affects only what UPS product names are shown to the user)', '6', '7', 'tep_cfg_select_option(array(\'US Origin\', \'Canada Origin\', \'European Union Origin\', \'Puerto Rico Origin\', \'Mexico Origin\', \'All other origins\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Origin City', 'MODULE_SHIPPING_UPSXML_RATES_CITY', '', 'Enter the name of the origin city.', '6', '8', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Origin State/Province', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV', '', 'Enter the two-letter code for your origin state/province.', '6', '9', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Origin Country', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY', '', 'Enter the two-letter code for your origin country.', '6', '10', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Origin Zip/Postal Code', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE', '', 'Enter your origin zip/postalcode.', '6', '11', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Test or Production Mode', 'MODULE_SHIPPING_UPSXML_RATES_MODE', 'Test', 'Use this module in Test or Production mode?', '6', '12', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
// three configuration options were moved to shop Configuration -> Shipping/Packaging in v1.3.0
// those are (and were renamed to -> ): 
// MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT (LBS/KG) -> SHIPPING_UNIT_WEIGHT
// MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH (IN/CM) -> SHIPPING_UNIT_LENGTH
// MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORT -> SHIPPING_DIMENSIONS_SUPPORT
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Quote Type', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE', 'Commercial', 'Quote for Residential or Commercial Delivery', '6', '15', 'tep_cfg_select_option(array(\'Commercial\', \'Residential\'), ', now())");
       // next three keys added to be able to use negotiated rates (available from UPS since about July 2006)
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Negotiated rates', 'MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES', 'False', 'Do you receive discounted rates from UPS and want to use these for shipping quotes? <b>Note:</b>  You need to enter your UPS account number below.', '6', '25', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
         tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manual Negotiated Rate', 'MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE', '', 'Enter a negotiated rate manually. <b>Note:</b> If \'Negotiated Rates\' above is set to \'True\', This <b>WILL NOT</b> be applied. If using this option, set \'Negotiated Rates\' to \'False\'. Usage: \' 57 \' returns 57% of published UPS rate.', '6', '26', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Account Number', 'MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER', '', 'Enter your UPS Account number when you have and want to use negotiated rates.', '6', '27', now())");
        // added for handling type selection
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Handling Type', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE', 'Flat Fee', 'Handling type for this shipping method.', '6', '14', 'tep_cfg_select_option(array(\'Flat Fee\', \'Percentage\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING', '0', 'Handling fee for this shipping method.', '6', '16', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Currency Code', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE', '', 'Enter the 3 letter currency code for your country of origin. United States (USD)', '6', '2', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Insurance', 'MODULE_SHIPPING_UPSXML_INSURE', 'True', 'Do you want to insure packages shipped by UPS?', '6', '22', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '17', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_UPSXML_RATES_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '18', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '19', now())");
        // add key for disallowed shipping methods
        tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Disallowed Shipping Methods', 'MODULE_SHIPPING_UPSXML_TYPES', '', 'Select the UPS services <span style=\'color: red; font-weight: bold\'>not</span> to be offered.', '6', '20', 'get_multioption_upsxml',  'upsxml_cfg_select_multioption_indexed(array(\'US_01\', \'US_02\', \'US_03\', \'US_07\', \'US_54\', \'US_08\', \'CAN_01\', \'US_11\', \'US_12\', \'US_13\', \'US_14\', \'CAN_02\', \'US_59\', \'US_65\', \'CAN_14\', \'MEX_54\', \'EU_82\', \'EU_83\', \'EU_84\', \'EU_85\', \'EU_86\'), ',  now())");
        // add key for shipping delay, changed the constant from SHIPPING_DAYS_DELAY in v1.3.0
        tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Shipping Delay', 'MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY', '1', 'How many days from when an order is placed to when you ship it (Decimals are allowed). Arrival date estimations are based on this value.', '6', '21', NULL, now(), NULL, NULL)");
        // add key for enabling email error messages
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Email UPS errors', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS', 'Yes', 'Do you want to receive UPS errors by email?', '6', '24', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
        // add key for time in transit view type
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Time in Transit View Type', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW', 'Not', 'If and how the module should display the time in transit to the customer.', '6', '13', 'tep_cfg_select_option(array(\'Not\',\'Raw\', \'Detailed\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Weight', 'MODULE_SHIPPING_UPSXML_WEIGHT1', 'True', 'Do you want to show number of packages and package weight?', '6', '28', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    //****************
    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    //*************
    function keys() {
        return array('MODULE_SHIPPING_UPSXML_RATES_STATUS', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN', 'MODULE_SHIPPING_UPSXML_RATES_CITY', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE', 'MODULE_SHIPPING_UPSXML_RATES_MODE', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE', 'MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES', 'MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER', 'MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING', 'MODULE_SHIPPING_UPSXML_INSURE', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS', 'MODULE_SHIPPING_UPSXML_RATES_ZONE', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER', 'MODULE_SHIPPING_UPSXML_TYPES', 'MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW', 'MODULE_SHIPPING_UPSXML_WEIGHT1');
    }

    //***********************
    function _upsProduct($prod){
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
            $territories = array('AS','FM','GU','MH','MP','PR','PW','VI');
            if (in_array($this->_upsDestStateProv,$territories)) {
              $this->_upsDestCountryCode = $stateprov;
              }
        } else if ($country == 'BR') {
            $this->_upsDestPostalCode = substr($postal, 0, 5);
        } else {
            $this->_upsDestPostalCode = $postal;
        }
    }

    //************************
    function _upsAction($action) {
        // rate - Single Quote; shop - All Available Quotes
        $this->_upsActionCode = $action;
    }

    //********************************************
    function _addItem($length, $width, $height, $weight, $price = 0 ) {
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
        $this->item_price[$index] = $price;
        $this->items_qty++;
    }

    //*********************
    function _upsGetQuote() {

        // Create the access request
        $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username ."</UserId>\n".
        "   <Password>". $this->access_password ."</Password>\n".
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
        "   </Request>\n";
        // according to UPS the CustomerClassification and PickupType containers should
        // not be present when the origin country is non-US see:
        // http://forums.oscommerce.com/index.php?s=&showtopic=49382&view=findpost&p=730947
        if ($this->origin_country == 'US') {
        $ratingServiceSelectionRequestHeader .=
        "   <PickupType>\n".
        "       <Code>". $this->pickup_methods[$this->pickup_method] ."</Code>\n".
        "   </PickupType>\n";
        "   <CustomerClassification>\n".
        "       <Code>". $this->customer_classification ."</Code>\n".
        "   </CustomerClassification>\n";
        }
        $ratingServiceSelectionRequestHeader .=
        "   <Shipment>\n".
        "       <Shipper>\n";
        if ($this->use_negotiated_rates == 'True') {
        $ratingServiceSelectionRequestHeader .=
        "         <ShipperNumber>" . $this->access_account_number . "</ShipperNumber>\n";
        }
        $ratingServiceSelectionRequestHeader .=
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
        ($this->quote_type == "Residential" ? "<ResidentialAddressIndicator/>\n" : "") .
        "           </Address>\n".
        "       </ShipTo>\n";
        $numgroups = ceil($this->items_qty / 50); // UPS can only process 50 packages at once
        $xmlResult = '';
        for ($g = 0; $g < $numgroups; $g++) { // process each group of packages
        $ratingServiceSelectionRequestPackageContent = '';
        $start = $g * 50;
        $end = ($g + 1 == $numgroups) ? $this->items_qty : $start + 50; // if last group end with number of packages otherwise do 50 more
        for ($i = $start; $i < $end; $i++) {

            $ratingServiceSelectionRequestPackageContent .=
            "       <Package>\n".
            "           <PackagingType>\n".
            "               <Code>". $this->package_types[$this->package_type] ."</Code>\n".
            "           </PackagingType>\n";
            if ($this->dimensions_support > 0 && ($this->item_length[$i] > 0 ) && ($this->item_width[$i] > 0 ) && ($this->item_height[$i] > 0)) {

                $ratingServiceSelectionRequestPackageContent .=
                "           <Dimensions>\n".
                "               <UnitOfMeasurement>\n".
                "                   <Code>". $this->unit_length ."</Code>\n".
                "               </UnitOfMeasurement>\n".
                "               <Length>". $this->item_length[$i] ."</Length>\n".
                "               <Width>". $this->item_width[$i] ."</Width>\n".
                "               <Height>". $this->item_height[$i] ."</Height>\n".
                "           </Dimensions>\n";
            }

            $ratingServiceSelectionRequestPackageContent .=
            "           <PackageWeight>\n".
            "               <UnitOfMeasurement>\n".
            "                   <Code>". $this->unit_weight ."</Code>\n".
            "               </UnitOfMeasurement>\n".
            "               <Weight>". $this->item_weight[$i] ."</Weight>\n".
            "           </PackageWeight>\n";
            //"               <COD>\n".
            //"                   <CODFundsCode>0</CODFundsCode>\n".
            //"                   <CODCode>3</CODCode>\n".
            //"                   <CODAmount>\n".
            //"                       <CurrencyCode>USD</CurrencyCode>\n".
            //"                       <MonetaryValue>1000</MonetaryValue>\n".
            //"                   </CODAmount>\n".
            //"               </COD>\n".
          if ($this->insure_package == true) {
            $ratingServiceSelectionRequestPackageContent .=
            "           <PackageServiceOptions>\n".
            "               <InsuredValue>\n".
            "                   <CurrencyCode>".MODULE_SHIPPING_UPSXML_CURRENCY_CODE."</CurrencyCode>\n".
            "                   <MonetaryValue>".$this->item_price[$i]."</MonetaryValue>\n".
            "               </InsuredValue>\n".
            "           </PackageServiceOptions>\n";
            } // end if ($this->insure_package == true)
            $ratingServiceSelectionRequestPackageContent .=
            "       </Package>\n";
        }

        $ratingServiceSelectionRequestFooter = '';
        //"   <ShipmentServiceOptions/>\n".
           if ($this->use_negotiated_rates == 'True') {
        $ratingServiceSelectionRequestFooter .=
            "       <RateInformation>\n".
            "         <NegotiatedRatesIndicator/>\n".
            "       </RateInformation>\n";
           }
        $ratingServiceSelectionRequestFooter .=
        "   </Shipment>\n";
        // according to UPS the CustomerClassification and PickupType containers should
        // not be present when the origin country is non-US see:
        // http://forums.oscommerce.com/index.php?s=&showtopic=49382&view=findpost&p=730947
        if ($this->origin_country == 'US') {
        $ratingServiceSelectionRequestFooter .=
              "   <CustomerClassification>\n".
              "       <Code>". $this->customer_classification ."</Code>\n".
              "   </CustomerClassification>\n";
        }
        $ratingServiceSelectionRequestFooter .=
        "</RatingServiceSelectionRequest>\n";

        $xmlRequest = $accessRequestHeader .
        $ratingServiceSelectionRequestHeader .
        $ratingServiceSelectionRequestPackageContent .
        $ratingServiceSelectionRequestFooter;

        //post request $strXML;
        $result = $this->_post($this->protocol, $this->host, $this->port, $this->path, $this->version, $this->timeout, $xmlRequest);
        if ($xmlResult == '') { // if first group of packages
          $xmlResult = $result;
        } else { //if second group of packages then results must be combined into one larger result
          if (strpos($xmlResult, '</RatingServiceSelectionResponse>') !== false)
            $xmlResult = substr($xmlResult, 0, strpos($xmlResult, '</RatingServiceSelectionResponse>'));
          if (strpos($result, '</Response>') !== false)
            $result = substr($result, strpos($result, '</Response>') + 11);
          $xmlResult .= $result;
        }
        // BOF testing with a response from UPS saved as a text file
        // needs commenting out the line above: $xmlResult = $this->_post($this->protocol, etcetera
/*        $filename = '/srv/www/htdocs/catalog/includes/modules/shipping/example_response.xml';
        $fp = fopen($filename, "r") or die("couldn't open file");
        $xmlResult = "";
        while (! feof($fp)) {
          $xmlResult .= fgets($fp, 1024);
        } 
        // EOF testing with a text file */ 
        } //end groups loop
        return $this->_parseResult($xmlResult);
    }

    //******************************************************************
    function _post($protocol, $host, $port, $path, $version, $timeout, $xmlRequest) {
        $url = $protocol."://".$host.":".$port.$path;
        if (isset($this->logfile)) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("DATE AND TIME: ".date('Y-m-d H:i:s')."\n", 3, $this->logfile);
            error_log("UPS URL: " . $url . "\n", 3, $this->logfile);
        }
        if (function_exists('exec') && $this->use_exec == '1' ) {
            exec('which curl', $curl_output);
            if ($curl_output) {
                $curl_path = $curl_output[0];
            } else {
                $curl_path = 'curl'; // change this if necessary
            }
            if (isset($this->logfile)) {
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
            if (isset($this->logfile)) {
                error_log("UPS RESPONSE using exec(): " . implode('', $xmlResponse) . "\n", 3, $this->logfile);
            }
        } elseif ($this->use_exec == '1') { // if NOT (function_exists('exec') && $this->use_exec == '1'
            if (isset($this->logfile)) {
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

            if (isset($this->logfile)) {
                error_log("UPS REQUEST: " . $xmlRequest . "\n", 3, $this->logfile);
            }
            $xmlResponse = curl_exec ($ch);
            if (curl_errno($ch) && $this->logfile) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log("Error from cURL: " . $error_from_curl . "\n", 3, $this->logfile);
            }
            // send email if enabled in the admin section
            if (curl_errno($ch) && $this->email_errors) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log("Error from cURL: " . $error_from_curl . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if (curl_errno($ch) && $this->ups_error_file) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log(date('Y-m-d H:i:s')."\tcURL\t" . $error_from_curl . "\t" . $_SESSION['customer_id']."\n", 3, $this->ups_error_file);    
            }
            if (isset($this->logfile)) {
                error_log("UPS RESPONSE: " . $xmlResponse . "\n", 3, $this->logfile);
            }
            curl_close ($ch);
        }

        if(!$xmlResponse || strstr(strtolower(substr($xmlResponse, 0, 120)), "bad request"))  {
            /* Sometimes the UPS server responds with an HTML message (differing depending on whether the test server
               or the production server is used) but both have in the title tag "Bad request".
               Parsing this response will result in a fatal error:
               Call to a member function on a non-object in /blabla/includes/classes/xmldocument.php on line 57
               It only results in not showing Estimated Delivery Dates to the customer so avoiding the fatal error should do.
            */
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
            return implode('', $xmlResponse); // $xmlResponse is an array in this case
        } else {
            return $xmlResponse;
        }
    }

    //*****************************
    function _parseResult($xmlResult) {
        // Parse XML message returned by the UPS post server.
        $doc = XML_unserialize ($xmlResult);

        // Get version. Must be xpci version 1.0001 or this might not work.
        $responseVersion = $doc['RatingServiceSelectionResponse']['Response']['TransactionReference']['XpciVersion'];
        if ($this->xpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        }
        // Get response code: 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc['RatingServiceSelectionResponse']['Response']['ResponseStatusCode'];
        if ($responseStatusCode != '1') {
            $errorMsg = $doc['RatingServiceSelectionResponse']['Response']['Error']['ErrorCode'];
            $errorMsg .= ": ";
            $errorMsg .= $doc['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription'];
            // send email if enabled in the admin section
            if ($this->email_errors) {
                error_log("UPSXML Rates Error: " . $errorMsg . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tRates\t" . $errorMsg . "\t" . $_SESSION['customer_id']."\n", 3, $this->ups_error_file);    
            }
                return $errorMsg;
        }

        $ratedShipments = $doc['RatingServiceSelectionResponse']['RatedShipment'];

        $aryProducts = false;
        $upstemp = array();
        if (isset($doc['RatingServiceSelectionResponse']['RatedShipment'][0])) { // more than 1 rate
          for ($i = 0; $i < count($ratedShipments); $i++) {
            $serviceCode = $ratedShipments[$i]['Service']['Code'];
            if ($this->use_negotiated_rates == 'True' && isset($ratedShipments[$i]['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'])) {
                $totalCharge = $ratedShipments[$i]['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'];
               } elseif ($this->manual_negotiated_rate > 0) {
                 $totalCharge = $ratedShipments[$i]['TotalCharges']['MonetaryValue'] * ($this->manual_negotiated_rate/100);
               } else {
// standard UPS rates
                 $totalCharge = $ratedShipments[$i]['TotalCharges']['MonetaryValue'];
               }
              if (!($serviceCode && $totalCharge)) {
                continue;
              } 
            $ratedPackages = $ratedShipments[$i]['RatedPackage']; // only do this once for the first service given
            if (isset($ratedShipments[$i]['RatedPackage'][0])) { // multidimensional array of packages
              $boxCount = count($ratedPackages);
            } else {
              $boxCount = 1; // if there is only one package count($ratedPackages) returns
              // the number of fields in the array like TransportationCharges and BillingWeight
            }
            // if more than one group of packages, service codes will be repeated and therefore data needs to be combined
            $upstemp[$serviceCode]['charge'] += $totalCharge;
            $upstemp[$serviceCode]['boxes'] += $boxCount;
            $upstemp[$serviceCode]['billed_weight'] += $ratedShipments[$i]['BillingWeight']['Weight'];
            $upstemp[$serviceCode]['weight_code'] = $ratedShipments[$i]['BillingWeight']['UnitOfMeasurement']['Code'];
          } // end for ($i = 0; $i < count($ratedShipments); $i++)
          $i = 0;
          foreach ($upstemp as $key => $value) {
            $this->boxCount = $value['boxes']; // set total grouped package count
            $title = $this->service_codes[$this->origin][$key];
            if (MODULE_SHIPPING_UPSXML_WEIGHT1 == 'True')
              $title .= ' (' . UPSXML_TEXT_BILLED_WEIGHT . $value['billed_weight'] . ' ' . $value['weight_code'] . ')';
            $aryProducts[$i] = array($title => $value['charge']);
            $i++;
          }
        } elseif (isset($doc['RatingServiceSelectionResponse']['RatedShipment'])) { // only 1 rate
          $serviceCode = $ratedShipments['Service']['Code'];
            if ($this->use_negotiated_rates == 'True' && isset($ratedShipments['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'])) {
                $totalCharge = $ratedShipments['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'];
               } elseif ($this->manual_negotiated_rate > 0) {
                 $totalCharge = $ratedShipments['TotalCharges']['MonetaryValue'] * ($this->manual_negotiated_rate/100);
              } else {
// standard UPS rates
                $totalCharge = $ratedShipments['TotalCharges']['MonetaryValue'];
              }
              if (!($serviceCode && $totalCharge)) {
                return $aryProducts; // is false
              } 
            $ratedPackages = $ratedShipments['RatedPackage']; // only do this once for the first service given
            if (isset($ratedShipments['RatedPackage'][0])) { // multidimensional array of packages
              $this->boxCount = count($ratedPackages);
            } else {
              $this->boxCount = 1; // if there is only one package count($ratedPackages) returns
              // the number of fields in the array like TransportationCharges and BillingWeight
            }
            $title = $this->service_codes[$this->origin][$serviceCode];
            if (MODULE_SHIPPING_UPSXML_WEIGHT1 == 'True')
              $title .= ' (' . UPSXML_TEXT_BILLED_WEIGHT . $ratedShipments['BillingWeight']['Weight'] . ' ' . $ratedShipments['BillingWeight']['UnitOfMeasurement']['Code'] . ')';
            $aryProducts[] = array($title => $totalCharge);
        }
        return $aryProducts;
    }

    // BOF Time In Transit

    //********************
    function _upsGetTimeServices() {

        if (defined('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY')) {
            $shipdate = date("Ymd", $this->today_unix_time + (86400*MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY));
            $day_of_the_week = date ("w", $this->today_unix_time + (86400*MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY) ) ;
            if ($day_of_the_week == "0" || $day_of_the_week == "7") { // order supposed to leave on Sunday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY) + 86400);
            } elseif ($day_of_the_week == "6") { // order supposed to leave on Saturday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY) + 172800);
            } 
        } else {
            $shipdate = $this->today;
        }

        // Create the access request
        $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username ."</UserId>\n".
        "   <Password>". $this->access_password ."</Password>\n".
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
        "           <PoliticalDivision2>". $this->origin_city ."</PoliticalDivision2>\n".
        "           <PoliticalDivision1>". $this->origin_stateprov ."</PoliticalDivision1>\n".
        "           <CountryCode>". $this->_upsOriginCountryCode ."</CountryCode>\n".
        "           <PostcodePrimaryLow>". $this->origin_postalcode ."</PostcodePrimaryLow>\n".
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
        "   <ShipmentWeight>\n".
        "       <UnitOfMeasurement>\n".
        "           <Code>" . $this->unit_weight . "</Code>\n".
        "       </UnitOfMeasurement>\n".
        "       <Weight>" . $this->weight_for_timeintransit . "</Weight>\n".
        "   </ShipmentWeight>\n".
        "   <InvoiceLineTotal>\n".
        "       <CurrencyCode>" . MODULE_SHIPPING_UPSXML_CURRENCY_CODE . "</CurrencyCode>\n".
        "       <MonetaryValue>" . $this->pkgvalue . "</MonetaryValue>\n".
        "   </InvoiceLineTotal>\n".
        "   <PickupDate>" . $shipdate . "</PickupDate>\n".
        "</TimeInTransitRequest>\n";

        $xmlTransitRequest = $accessRequestHeader .
        $timeintransitSelectionRequestHeader;

        //post request $strXML;
        $xmlTransitResult = $this->_post($this->protocol, $this->host, $this->port, $this->transitpath, $this->transitversion, $this->timeout, $xmlTransitRequest);
        return $this->_transitparseResult($xmlTransitResult);
    }

    //***************************************
    
    // GM 11-15-2004: modified to return array with time for each service, as
    //                opposed to single transit time for hardcoded "GND" code

    function _transitparseResult($xmlTransitResult) {
         $transitTime = array();

        // Parse XML message returned by the UPS post server.
        $doc = XML_unserialize ($xmlTransitResult);
        // Get version. Must be xpci version 1.0001 or this might not work.
        // 1.0001 and 1.0002 seem to be very similar, forget about this for the moment
        /*        $responseVersion = $doc['TimeInTransitResponse']['Response']['TransactionReference']['XpciVersion'];
        if ($this->transitxpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        } */
        // Get response code. 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc['TimeInTransitResponse']['Response']['ResponseStatusCode'];
        if ($responseStatusCode != '1') {
            $errorMsg = $doc['TimeInTransitResponse']['Response']['Error']['ErrorCode'];
            $errorMsg .= ": ";
            $errorMsg .= $doc['TimeInTransitResponse']['Response']['Error']['ErrorDescription'];
            // send email if enabled in the admin section
            if ($this->email_errors) {
                error_log("UPSXML TimeInTransit Error: " . $errorMsg . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tTimeInTransit\t" . $errorMsg . "\t" . $_SESSION['customer_id'] ."\n", 3, $this->ups_error_file);    
            }
           //  return $errorMsg;
           return false;
        }

        if (isset($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'][0])) { // more than one EDD
               foreach ($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'] as $key_index => $service_array) {
                    // index by description because that's all we can relate back to the service 
                    // with (though it can probably return the code as well but they are very
                    // different from those used by the Rates Service and there is a lot of 
                    // duplication so pretty useless)
                    $serviceDesc = $service_array['Service']['Description'];
                    // hack to get EDD for UPS Saver recognized (Time in Transit uses UPS Worldwide Saver
                    // but the service in Rates and Services is called UPS Saver)
                    if ($serviceDesc == "UPS Worldwide Saver") {
                      $serviceDesc = "UPS Saver";
                    }
                    // only date is used so why bother with days and guaranteed?
                    // $transitTime[$serviceDesc]["days"] = $serviceSummary[$s]->getValueByPath("EstimatedArrival/BusinessTransitDays");
                    $transitTime[$serviceDesc]['date'] = $service_array['EstimatedArrival']['Date'];
                    // $transitTime[$serviceDesc]["guaranteed"] = $serviceSummary[$s]->getValueByPath("Guaranteed/Code");
                } // end foreach ($doc['TimeInTransitResponse']['ServiceSummary'] etc.
        } elseif (isset($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'])) { // only one EDD
          $serviceDesc = $doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary']['Service']['Description'];
          $transitTime[$serviceDesc]['date'] = $doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary']['EstimatedArrival']['Date'];
        } else {
          $errorMsg = MODULE_SHIPPING_UPSXML_TIME_IN_TRANSIT_TEXT_NO_RATES;
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tTimeInTransit\t" . $errorMsg . "\t" . $_SESSION['customer_id'] ."\n", 3, $this->ups_error_file);    
            }
          return false;
        }
        if (isset($this->logfile)) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            foreach($transitTime as $desc => $time) {
                error_log("Business Transit: " . $desc ." = ". $time["date"] . "\n", 3, $this->logfile);
            }
        }
        return $transitTime;
    }

    //EOF Time In Transit
 //  ***************************
  function exclude_choices($type) {
    // Used for exclusion of UPS shipping options, disallowed types are read from db (stored as 
    // short defines). The short defines are not used as such, to avoid collisions
    // with other shipping modules, they are prefixed with UPSXML_
    // These defines are found in the upsxml language file (UPSXML_US_01, UPSXML_CAN_14 etc.)
    $disallowed_types = explode(",", MODULE_SHIPPING_UPSXML_TYPES);
    if (strstr($type, "UPS")) {
        // this will chop off "UPS" from the beginning of the line - typically something like UPS Next Day Air (1 Business Days)
        $type_minus_ups = explode("UPS", $type );
        $type_root = trim($type_minus_ups[1]);
    } // end if (strstr($type, "UPS"):
    else { // service description does not contain UPS (unlikely)
        $type_root = trim($type);
    }
    for ($za = 0; $za < count ($disallowed_types); $za++ ) {
      // when no disallowed types are present, --none-- is in the db but causes an error because --none-- is
      // not added as a define
      if ($disallowed_types[$za] == '--none--' ) continue; 
        if ($type_root == constant('UPSXML_' . trim($disallowed_types[$za]))) {
            return true;
        } // end if ($type_root == constant(trim($disallowed_types[$za]))).
    }
    // if the type is not disallowed:
    return false;
  }
// Next function used for sorting the shipping quotes on rate: low to high is default.
  function rate_sort_func ($a, $b) {
    
   $av = array_values($a);
   $av = $av[0];
   $bv = array_values($b);
   $bv = $bv[0];

//  return ($av == $bv) ? 0 : (($av < $bv) ? 1 : -1); // for having the high rates first
  return ($av == $bv) ? 0 : (($av > $bv) ? 1 : -1); // low rates first
  
  }
} // end class upsxml
// Next two functions are used only in the admin for disallowed shipping options.
// The (short) constants like US_12, CAN_14 are stored in the database
// to stay below 255 characters. The defines themselves are found in the upsxml
// language file prefixed with UPSXML_ to avoid collisions with other shipping modules.
// They can be moved to admin/includes/function/general.php if you like but don't forget
// to remove them from this file in future updates or you will get an error in the admin
// about re-declaring functions
  function get_multioption_upsxml($values) {
         if (tep_not_null($values)) {
             $values_array = explode(',', $values);
             foreach ($values_array as $key => $_method) {
               if ($_method == '--none--') {
                 $method = $_method;
               } else {
                 $method = constant('UPSXML_' . trim($_method));
               }
               $readable_values_array[] = $method;
             }
             $readable_values = implode(', ', $readable_values_array);
             return $readable_values;
         } else {
           return '';
         }
  }
  
  function upsxml_cfg_select_multioption_indexed($select_array, $key_value, $key = '') {
    for ($i=0; $i<sizeof($select_array); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
      $key_values = explode( ", ", $key_value);
      if ( in_array($select_array[$i], $key_values) ) $string .= ' CHECKED';
      $string .= '> ' . constant('UPSXML_' . trim($select_array[$i]));
    } 
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';
    return $string;
  }
?>
