<?php
/*
    $Id: upsxml.php,v 1.2.3 2006/02/07 DefelRadar Exp $

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

require ('includes/classes/xmldocument.php');
// if using the optional dimensional support, set to 1, otherwise leave as 0
// define('DIMENSIONS_SUPPORTED', 1);
// obsolete: is set in admin now

class upsxml {
    var $code, $title, $description, $icon, $enabled, $types, $boxcount;

    //***************
    function upsxml() {
        global $order;
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
        $this->origin = MODULE_SHIPPING_UPSXML_RATES_ORIGIN;
        $this->origin_city = MODULE_SHIPPING_UPSXML_RATES_CITY;
        $this->origin_stateprov = MODULE_SHIPPING_UPSXML_RATES_STATEPROV;
        $this->origin_country = MODULE_SHIPPING_UPSXML_RATES_COUNTRY;
        $this->origin_postalcode = MODULE_SHIPPING_UPSXML_RATES_POSTALCODE;
        $this->pickup_method = MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD;
        $this->package_type = MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE;
        $this->unit_weight = MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT;
        $this->unit_length = MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH;
        if (MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORT == 'Ready-to-ship only') {
          $this->dimensions_support = 1;
        } elseif (MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORT == 'With product dimensions') {
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
        if (MODULE_SHIPPING_UPSXML_INSURE == 'False')
          { $this->pkgvalue = 100; }
        if (MODULE_SHIPPING_UPSXML_INSURE == 'True')
          { $this->pkgvalue = ceil($order->info['subtotal']); }
        // end insurance addition
        // to enable logging, create an empty "upsxml.log" file at the location you set below, give it write permissions (777) and uncomment the next line
        //      $this->logfile = '/tmp/upsxml.log';
        
        // to enable logging of just the errors, do as above but call the file upsxml_error.log
        //      $this->ups_error_file = '/srv/www/htdocs/catalog/includes/modules/shipping/upsxml_error.log';
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
                '59' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_59
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
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_65
            ),
            // European Union Origin
            'European Union Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_07,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_54,
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

    // class methods
    function quote($method = '') {
        global $_POST, $order, $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $cart;
        // UPS purports that if the origin is left out, it defaults to the account's location. Yeah, right.
        $state = $order->delivery['state'];
        $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" .  $order->delivery['state'] . "'");
        if (tep_db_num_rows($zone_query)) {
            $zone = tep_db_fetch_array($zone_query);
            $state = $zone['zone_code'];
        }
        $this->_upsOrigin(MODULE_SHIPPING_UPSXML_RATES_CITY, MODULE_SHIPPING_UPSXML_RATES_STATEPROV, MODULE_SHIPPING_UPSXML_RATES_COUNTRY, MODULE_SHIPPING_UPSXML_RATES_POSTALCODE);
        $this->_upsDest($order->delivery['city'], $state, $order->delivery['country']['iso_code_2'], $order->delivery['postcode']);
        $productsArray = $cart->get_products();

        if ($this->dimensions_support == '2') {
            // sort $productsArray according to ready-to-ship (first) and not-ready-to-ship (last)
            usort($productsArray, ready_to_shipCmp);
            // Use packing algoritm to return the number of boxes we'll ship
            $boxesToShip = $this->packProducts($productsArray);
            // Quote for the number of boxes
            for ($i = 0; $i < count($boxesToShip); $i++) {
                $this->_addItem($boxesToShip[$i]['length'], $boxesToShip[$i]['width'], $boxesToShip[$i]['height'], $boxesToShip[$i]['current_weight']);
                $totalWeight += $boxesToShip[$i]['current_weight'];
            }
        } elseif ($this->dimensions_support == '1') {
            $totalWeight = 0;
            $total_non_ready_to_ship_weight = 0;
            // sort $productsArray according to ready-to-ship (first) and not-ready-to-ship (last)
            usort($productsArray, ready_to_shipCmp);
            $non_ready_to_shipArray = array();
            // walk through the productsArray, separate the items ready-to-ship and add them to
            // the items (boxes) list, add the weight to the totalWeight
            // and add the other items to a separate array
            for ($i = 0; $i < count($productsArray); $i++) {
                if ($productsArray[$i]['ready_to_ship'] == '1') {
                    for ($z = 0 ; $z < $productsArray[$i]['quantity']; $z++) {
                        $this->_addItem($productsArray[$i]['length'], $productsArray[$i]['width'], $productsArray[$i]['height'], $productsArray[$i]['weight']);
                        $totalWeight += $productsArray[$i]['weight'];
                    } // end for ($z = 0 ; $z < $productsArray[$i]['quantity']; $z++)
                } // end if($productsArray['ready_to_ship'] == '1')
                else {
                    $non_ready_to_shipArray[] = $productsArray[$i];
                }
            } // end for ($i = 0; $i < count($productsArray); $i++)
            // Ready_to_ship items out of the way, now assess remaining weight of products

            for ($x = 0 ; $x < count($non_ready_to_shipArray) ; $x++) {
                $total_non_ready_to_ship_weight += ($non_ready_to_shipArray[$x]['weight'] * $non_ready_to_shipArray[$x]['quantity']);
            } // end for ($x = 0 ; count($non_ready_to_shipArray) ; $x++)
      
            if (tep_not_null($non_ready_to_shipArray)) {
                // adapted code from includes/classes/shipping.php
                $shipping_non_ready_to_ship_boxes = 1;
                $shipping_non_ready_to_ship_weight = $total_non_ready_to_ship_weight;
                if (SHIPPING_BOX_WEIGHT >= $total_non_ready_to_ship_weight*SHIPPING_BOX_PADDING/100) {
                  $total_non_ready_to_ship_weight = $total_non_ready_to_ship_weight+SHIPPING_BOX_WEIGHT;
                } else {
                  $total_non_ready_to_ship_weight += $total_non_ready_to_ship_weight*SHIPPING_BOX_PADDING/100;
                }
                if ($total_non_ready_to_ship_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
                    $shipping_non_ready_to_ship_boxes = ceil($total_non_ready_to_ship_weight/SHIPPING_MAX_WEIGHT);
                    $shipping_non_ready_to_ship_weight = round($total_non_ready_to_ship_weight/$shipping_non_ready_to_ship_boxes,1);
                }
                // end adapted code from includes/classes/shipping.php
                // weight and number of boxes of non-read-to-ship is determined, now add them to the items list
                for ($y = 0; $y < $shipping_non_ready_to_ship_boxes ; $y++) {
                    $this->_addItem(0, 0, 0, $shipping_non_ready_to_ship_weight );
                    $totalWeight += $shipping_non_ready_to_ship_weight;
                } // end for ($y = 0; $y < $shipping_non_ready_to_ship_boxes ; $y++)
            } // end if (tep_not_null($non_ready_to_shipArray))
        } else {
            // The old method. Let osCommerce tell us how many boxes, plus the weight of each (or total? - might be sw/num boxes)
            $this->items_qty = 0; //reset quantities
            for ($i = 0; $i < $shipping_num_boxes; $i++) {
                $this->_addItem(0, 0, 0, $shipping_weight);
            }
        }

        // BOF Time In Transit: comment out this section if you don't want/need to have
        // expected delivery dates
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
        /* echo '<pre>Packages and variables:<br />';
         print_r($this);
         echo '<br />';
         exit;  */
        $this->servicesTimeintransit = $this->_upsGetTimeServices();
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("Time in Transit: " . $this->timeintransit . "\n", 3, $this->logfile);
        }

        // EOF Time In Transit

        $upsQuote = $this->_upsGetQuote();
        if ((is_array($upsQuote)) && (sizeof($upsQuote) > 0)) {
            if ($this->dimensions_support > 0) {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $this->boxCount . ($this->boxCount > 1 ? ' pkg(s), ' : ' pkg, ') . round($totalWeight,0) . ' ' . strtolower($this->unit_weight) . ' total)');
            } else {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $shipping_num_boxes . ($this->boxCount > 1 ? ' pkg(s) x ' : ' pkg x ') . round($shipping_weight,0) . ' ' . strtolower($this->unit_weight) . ' total)');
            }
            $methods = array();
            for ($i=0; $i < sizeof($upsQuote); $i++) {
                list($type, $cost) = each($upsQuote[$i]);
                // BOF limit choices, behaviour changed from versions < 1.2
                if (exclude_choices($type)) continue;
                // EOF limit choices
                if ( $method == '' || $method == $type ) {
                    $_type = $type;

                    if ($this->timeInTransitView == "Raw") {
                      if (isset($this->servicesTimeintransit[$type])) {
                        $_type = $_type . ", ".$this->servicesTimeintransit[$type]["date"];
                      }        
                    } else {
                      if (isset($this->servicesTimeintransit[$type])) {
                        $eta_array = explode("-", $this->servicesTimeintransit[$type]["date"]);
                        $months = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $eta_arrival_date = $months[(int)$eta_array[1]]." ".$eta_array[2].", ".$eta_array[0];
                        $_type .= ", <acronym title='Estimated Delivery Date'>EDD</acronym>: ".$eta_arrival_date;
                      }          
                    }                    
                    
                    // changed to make handling percentage based
                    if ($this->handling_type == "Percentage") {
                        $methods[] = array('id' => $type, 'title' => $_type, 'cost' => ((($this->handling_fee * $cost)/100) + $cost));
                    } else {
                        $methods[] = array('id' => $type, 'title' => $_type, 'cost' => ($this->handling_fee + $cost));
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
            $errmsg .= '<br>' . MODULE_SHIPPING_UPSXML_RATES_TEXT_IF_YOU_PREFER . ' ' . STORE_NAME.' via <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><u>Email</U></a>.';
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
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Unit Weight', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT', 'LBS', 'By what unit are your packages weighed?', '6', '13', 'tep_cfg_select_option(array(\'LBS\', \'KGS\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Unit Length', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH', 'IN', 'By what unit are your packages sized?', '6', '14', 'tep_cfg_select_option(array(\'IN\', \'CM\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Dimensions Support', 'MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORT', 'No', 'Do you use the additional dimensions support (read dimensions.txt in the package)?', '6', '23', 'tep_cfg_select_option(array(\'No\', \'Ready-to-ship only\', \'With product dimensions\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Quote Type', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE', 'Commercial', 'Quote for Residential or Commercial Delivery', '6', '15', 'tep_cfg_select_option(array(\'Commercial\', \'Residential\'), ', now())");
        // added for handling type selection
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Handling Type', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE', 'Flat Fee', 'Handling type for this shipping method.', '6', '16', 'tep_cfg_select_option(array(\'Flat Fee\', \'Percentage\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING', '0', 'Handling fee for this shipping method.', '6', '16', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Currency Code', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE', '', 'Enter the 3 letter currency code for your country of origin. United States (USD)', '6', '2', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Insurance', 'MODULE_SHIPPING_UPSXML_INSURE', 'True', 'Do you want to insure packages shipped by UPS?', '6', '22', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '17', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_UPSXML_RATES_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '18', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '19', now())");
        // add key for disallowed shipping methods
        tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disallowed Shipping Methods', 'MODULE_SHIPPING_UPSXML_TYPES', '', 'Select the UPS services <span style=\'color: red; font-weight: bold\'>not</span> to be offered.', '6', '20', 'tep_cfg_select_multioption(array(\'Next Day Air\', \'2nd Day Air\', \'Ground\', \'Worldwide Express\', \'Worldwide Express Plus\', \'Worldwide Expedited\', \'Express\', \'Standard\', \'3 Day Select\', \'Next Day Air Saver\', \'Next Day Air Early A.M.\', \'Expedited\', \'2nd Day Air A.M.\', \'Express Saver\', \'Express Early A.M.\', \'Express Plus\'), ',  now())");
        // add key for shipping delay
        tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('', 'Shipping Delay', 'SHIPPING_DAYS_DELAY', '1', 'How many days from when an order is placed to when you ship it (Decimals are allowed). Arrival date estimations are based on this value.', '6', '21', NULL, now(), NULL, NULL)");
        // add key for enabling email error messages
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Email UPS errors', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS', 'Yes', 'Do you want to receive UPS errors by email?', '6', '24', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
        // add key for time in transit view type
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Time in Transit View Type', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW', 'Raw', 'How the module should display the time in transit to the customer.', '6', '16', 'tep_cfg_select_option(array(\'Raw\', \'Detailed\'), ', now())");
    }

    //****************
    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    //*************
    function keys() {
        // add MODULE_SHIPPING_UPSXML_TYPES to end of array for selectable shipping methods
        return array('MODULE_SHIPPING_UPSXML_RATES_STATUS', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN', 'MODULE_SHIPPING_UPSXML_RATES_CITY', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE', 'MODULE_SHIPPING_UPSXML_RATES_MODE', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH', 'MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORT', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING', 'MODULE_SHIPPING_UPSXML_INSURE', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS', 'MODULE_SHIPPING_UPSXML_RATES_ZONE', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER', 'MODULE_SHIPPING_UPSXML_TYPES', 'SHIPPING_DAYS_DELAY', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW');
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
    function getPackagesByVol() {
        $packages = array();
        $packages_query = tep_db_query("select *, (package_length * package_width * package_height) as volume from " . TABLE_PACKAGING . " order by volume;");
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
      'volume' => $package['volume'] );
        }
        return $packages;
    }

    //********************************
    function packProducts($productsArray) {
        $definedPackages = $this->getPackagesByVol();
        $emptyBoxesArray = array();
        for ($i = 0; $i < count($definedPackages); $i++) {
            $definedBox = $definedPackages[$i];
            $definedBox['remaining_volume'] = $definedBox['volume'];
            $definedBox['current_weight'] = $definedBox['empty_weight'];
            $emptyBoxesArray[] = $definedBox;
        }
        $packedBoxesArray = array();
        $currentBox = NULL;
        $index_of_largest_box = count($emptyBoxesArray)-1;
        // Get the product array and expand multiple qty items.
        $productsRemaining = array();
        for ($i = 0; $i < count($productsArray); $i++) {
            $product = $productsArray[$i];
            // sanity checks on the product
            if (!$this->fitsInBox($product, $emptyBoxesArray[$index_of_largest_box])) {
                $product['ready_to_ship'] = '1';
            }
            if (($product['length'] == 0 || $product['width'] == 0 || $product['height'] == 0) && $product['weight'] > 0) {
                $density = 0.7;
                if ($this->unit_length == 'CM') {
                    $product['length']=$product['width']=$product['height']= round(10*(pow($product['weight']/$density, 1/3)),1);
                } else {
                // non-metric: inches and pounds
                $product['length']=$product['width']=$product['height']= round(pow($product['weight']*27.67/$density, 1/3),1);
                }
            } // end sanity check
            for ($j = 0; $j < $productsArray[$i]['quantity']; $j++) {
                $productsRemaining[] = $product;
            }
        }
        // make sure the products that did not fit the largest box and are now set as ready-to-ship
        // are out of the way as soon as possible
        usort($productsRemaining, ready_to_shipCmp);
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
            //Cycle through boxes, increasing box size if all doesn't fit.
            if (count($emptyBoxesArray) == 0) {
                print("ERROR: No boxes to ship unpackaged product<br />\n");
                break;
            }
            for ($b = 0; $b < count($emptyBoxesArray) && tep_not_null($productsRemaining); $b++) {
                $result = $this->fitProductsInBox($productsRemaining, $emptyBoxesArray[$b], $packedBoxesArray, $b, count($emptyBoxesArray) -1 );
                $packedBoxesArray = $result['packed_boxes'];
                $productsRemaining = $result['remaining'];
            }
        } // end while

        return $packedBoxesArray;
    }

    //*****************************
    function fitsInBox($product, $box) {
        // in case by accident or by choice length, width or height is not set
        // we will estimate it by using a set density and the product['weight'] variable
        // will only be used in the check for whether it fits the largest box
        // after that it will already be set, if product['weight'] is set at least
        if ($product['length'] == 0 || $product['width'] == 0 || $product['height'] == 0) {
            $density = 0.7;
            if ($this->unit_length == 'CM') {
                $product['length']=$product['width']=$product['height']= round(10*(pow($product['weight']/$density, 1/3)),1);
            } else {
                // non-metric: inches and pounds
                $product['length']=$product['width']=$product['height']= round(pow($product['weight']*27.67/$density, 1/3),1);
            }
        } 
        $productVolume = $product['length'] * $product['width'] * $product['height'];
        // check if the diagonal of the product is not bigger than the diagonal of the box
        if ( ( pow($product['length'],2) + pow($product['width'],2) + pow($product['height'],2) ) > ( pow($box['length'],2) + pow($box['width'],2) + pow($box['height'],2)) ) {
            return false;
        } 

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
    function fitProductsInBox($productsRemaining, $emptyBox, $packedBoxesArray, $box_no, $index_of_largest_box) { 
        $currentBox = $emptyBox;
        //Try to fit each product in box
        for ($p = 0; $p < count($productsRemaining); $p++) {
            if ($this->fitsInBox($productsRemaining[$p], $currentBox)) {
                //It fits. Put it in the box.
                $currentBox = $this->putProductInBox($productsRemaining[$p], $currentBox);
                if ($p == count($productsRemaining) - 1) {
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p + 1);
                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
            } else {
                if ($box_no == $index_of_largest_box) {
                    //We're at the largest box already, and it's full. Keep what we've packed so far and get another box.
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p);
                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
                // Not all of them fit. Stop packing remaining products and try next box.
                $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                return ($result_array);
            } // end else
        } // end for
    } // end function

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
        }
        $ratingServiceSelectionRequestHeader .=
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
        ($this->quote_type == "Residential" ? "<ResidentialAddressIndicator/>\n" : "") .
        "           </Address>\n".
        "       </ShipTo>\n";
        for ($i = 0; $i < $this->items_qty; $i++) {

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
            "           </PackageWeight>\n".
            "           <PackageServiceOptions>\n".
            //"               <COD>\n".
            //"                   <CODFundsCode>0</CODFundsCode>\n".
            //"                   <CODCode>3</CODCode>\n".
            //"                   <CODAmount>\n".
            //"                       <CurrencyCode>USD</CurrencyCode>\n".
            //"                       <MonetaryValue>1000</MonetaryValue>\n".
            //"                   </CODAmount>\n".
            //"               </COD>\n".
            "               <InsuredValue>\n".
            "                   <CurrencyCode>".MODULE_SHIPPING_UPSXML_CURRENCY_CODE."</CurrencyCode>\n".
            "                   <MonetaryValue>".$this->pkgvalue."</MonetaryValue>\n".
            "               </InsuredValue>\n".
            "           </PackageServiceOptions>\n".
            "       </Package>\n";
        }

        $ratingServiceSelectionRequestFooter =
        //"   <ShipmentServiceOptions/>\n".
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
        $xmlResult = $this->_post($this->protocol, $this->host, $this->port, $this->path, $this->version, $this->timeout, $xmlRequest);
        return $this->_parseResult($xmlResult);
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
            } else {
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
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
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
            if ($this->logfile) {
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
            return $xmlResponse[0]; // $xmlResponse is an array in this case
        } else {
            return $xmlResponse;
        }
    }

    //*****************************
    function _parseResult($xmlResult) {
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
            $title = $this->service_codes[$this->origin][$serviceCode];

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
    function _upsGetTimeServices() {

        if (defined('SHIPPING_DAYS_DELAY')) {
            $shipdate = date("Ymd", $this->today_unix_time + (86400*SHIPPING_DAYS_DELAY));
            $day_of_the_week = date ("w", $this->today_unix_time + (86400*SHIPPING_DAYS_DELAY) ) ;
            if ($day_of_the_week == "0" || $day_of_the_week == "7") { // order supposed to leave on Sunday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*SHIPPING_DAYS_DELAY) + 86400);
            } elseif ($day_of_the_week == "6") { // order supposed to leave on Saturday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*SHIPPING_DAYS_DELAY) + 172800);
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
        $doc = new XMLDocument();
        $xp = new XMLParser();
        $xp->setDocument($doc);
        $xp->parse($xmlTransitResult);
        $doc = $xp->getDocument();
        // Get version. Must be xpci version 1.0001 or this might not work.
        // 1.0001 and 1.0002 seem to be very similar, forget about this for the moment
        /*        $responseVersion = $doc->getValueByPath('TimeInTransitResponse/Response/TransactionReference/XpciVersion');
        if ($this->transitxpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        } */
        // Get response code. 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc->getValueByPath('TimeInTransitResponse/Response/ResponseStatusCode');
        if ($responseStatusCode != '1') {
            $errorMsg = $doc->getValueByPath('TimeInTransitResponse/Response/Error/ErrorCode');
            $errorMsg .= ": ";
            $errorMsg .= $doc->getValueByPath('TimeInTransitResponse/Response/Error/ErrorDescription');
            // send email if enabled in the admin section
            if ($this->email_errors) {
                error_log("UPSXML TimeInTransit Error: " . $errorMsg . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tTimeInTransit\t" . $errorMsg . "\t" . $_SESSION['customer_id'] ."\n", 3, $this->ups_error_file);    
            }
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
function exclude_choices($type) {
    // used for exclusion of UPS shipping options, disallowed types are read from db
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
        if ($type_root == trim($disallowed_types[$za])) {
            return true;
            exit;
        } // end if ($type_root == $disallowed_types[$za] ...
    }
    // if the type is not disallowed:
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
