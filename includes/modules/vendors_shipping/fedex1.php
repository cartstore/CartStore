<?php
/*
    CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible
*/


  class fedex1 {
    var $code, $title, $description, $sort_order, $icon, $enabled, $meter, $intl, $delivery_country_id, $delivery_zone_id;

// class constructor
    function fedex1() {
      //MVS
   global $order, $vendors_id;

 //   echo '<br>the sort order form FEDEX : ' . @constant ('MODULE_SHIPPING_FEDEX1_SORT_ORDER_' . $vendors_id);
 //   echo '<br>the vendors_id form FEDEX : ' . $vendors_id;
  //   $this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
      $this->code = 'fedex1';
      $this->title = MODULE_SHIPPING_FEDEX1_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FEDEX1_TEXT_DESCRIPTION;
  //  $this->sort_order = MODULE_SHIPPING_FEDEX1_SORT_ORDER_ . $vendors_id;
      $this->icon = DIR_WS_ICONS . 'shipping_fedex.gif';
   //MVS    $this->tax_class = MODULE_SHIPPING_FEDEX1_TAX_CLASS;
  //MVS    $this->enabled = ((MODULE_SHIPPING_FEDEX1_STATUS == 'True') ? true : false);
    //$this->meter = constant('MODULE_SHIPPING_FEDEX1_METER_' . $vendors_id);

// START
$this->delivery_country_id = $order->delivery['country']['id'];
$this->delivery_zone_id = $order->delivery['zone_id'];
// END


// You can comment out any methods you do not wish to quote by placing a // at the beginning of that line
// If you comment out 92 in either domestic or international, be
// sure and remove the trailing comma on the last non-commented line
      $this->domestic_types = array(
             '01' => 'Priority (by 10:30AM, later for rural)',
             '03' => '2 Day Air',
             '05' => 'Standard Overnight (by 3PM, later for rural)',
             '06' => 'First Overnight',
             '20' => 'Express Saver (3 Day)',
             '90' => 'Home Delivery',
             '92' => 'Ground Service'
             );

      $this->international_types = array(
             '01' => 'International Priority (1-3 Days)',
             '03' => 'International Economy (4-5 Days)',
             '06' => 'International First',
             '90' => 'Home Delivery',
             '92' => 'Ground Service'
             );
    }

    function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_FEDEX1_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FEDEX1_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FEDEX1_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $this->delivery_zone_id) {
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

    function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_FEDEX1_TAX_CLASS_' . $vendors_id);
      return $this->tax_class;
    }

    function zones($vendors_id='1') {
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FEDEX1_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FEDEX1_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
//          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
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

function sort_order($vendors_id='1') {
   $sort_order = @constant ('MODULE_SHIPPING_FEDEX1_SORT_ORDER_' . $vendors_id);
      if (tep_not_null($sort_order)) {
   $this->sort_order = $sort_order;
          } else {
     $this->sort_order = '-';
            }
         return $this->sort_order;
                }


// class methods
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $_POST, $shipping_weight, $order, $cart, $shipping_num_boxes;

   //  $shipping_weight = $cart->vendor_shipping[$vendors_id]['weight'];

      if (tep_not_null($method)) {
        $this->_setService($method);
      }

      if (constant('MODULE_SHIPPING_FEDEX1_ENVELOPE_' . $vendors_id) == 'True') {
        if ( ($shipping_weight <= .5 && constant('MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id) == 'LBS') ||
             ($shipping_weight <= .2 && constant('MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id) == 'KGS')) {
          $this->_setPackageType('06');
        } else {
          $this->_setPackageType('01');
        }
      } else {
        $this->_setPackageType('01');
      }

      if ($this->packageType == '01' && $shipping_weight < 1) {
        $this->_setWeight(1);
      } else {
        $this->_setWeight($shipping_weight);
      }

      $totals = $cart->vendor_shipping[$vendors_id]['cost'];
      $this->_setInsuranceValue($totals / $shipping_num_boxes, $vendors_id);

      $vendors_data_query = tep_db_query("select handling_charge,
                                                 handling_per_box,
                                                 vendor_country,
                                                 vendors_zipcode
                                          from " . TABLE_VENDORS . "
                                          where vendors_id = '" . (int)$vendors_id . "'"
                                        );
      $vendors_data = tep_db_fetch_array($vendors_data_query);
      $country_name = tep_get_countries($vendors_data['vendor_country'], true);
     if (isset($vendors_data['vendor_country']) && $vendors_data['vendor_country'] != '') {
      $countries_array = tep_get_countries($vendors_data['vendor_country'], true);
       $this->country = $countries_array['countries_iso_code_2'];
     } else {
       $countries_array = tep_get_countries(SHIPPING_ORIGIN_COUNTRY, true);
       $this->country = STORE_ORIGIN_COUNTRY;
     }

    //  $vendors_data = tep_db_fetch_array($vendors_data_query);

      $handling_charge = $vendors_data['handling_charge'];
      $handling_per_box = $vendors_data['handling_per_box'];
      if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
        $handling = $handling_charge;
      } else {
        $handling = $handling_per_box*$shipping_num_boxes;
      }

      $fedexQuote = $this->_getQuote($vendors_id);

      if (is_array($fedexQuote)) {
        if (isset($fedexQuote['error'])) {
          $this->quotes = array('module' => $this->title,
                                'error' => $fedexQuote['error']);
        } else {
          $this->quotes = array('id' => $this->code,
                                'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . strtolower(constant('MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id)) . ')');

          $methods = array();
          foreach ($fedexQuote as $type => $cost) {
            $skip = FALSE;
            $this->surcharge = 0;
            if ($this->intl === FALSE) {
              if (strlen($type) > 2 && constant('MODULE_SHIPPING_FEDEX1_TRANSIT_' . $vendors_id) == 'True') {
                $service_descr = $this->domestic_types[substr($type,0,2)] . ' (' . substr($type,2,1) . ' days)';
              } else {
                $service_descr = $this->domestic_types[substr($type,0,2)];
              }
              switch (substr($type,0,2)) {
                case 90:
                  if ($order->delivery['company'] != '') {
                    $skip = TRUE;
                  }
                  break;
                case 92:
                  if ($this->country == "CA") {
                    if ($order->delivery['company'] == '') {
                      $this->surcharge = constant('MODULE_SHIPPING_FEDEX1_RESIDENTIAL_' . $vendors_id);
                    }
                  } else {
                    if ($order->delivery['company'] == '') {
                      $skip = TRUE;
                    }
                  }
                  break;
                default:
                  if ($this->country != "CA" && substr($type,0,2) < "90" && $order->delivery['company'] == '') {
                    $this->surcharge = constant('MODULE_SHIPPING_FEDEX1_RESIDENTIAL_' . $vendors_id);
                  }
                  break;
              }
            } else {
              if (strlen($type) > 2 && constant('MODULE_SHIPPING_FEDEX1_TRANSIT_' . $vendors_id) == 'True') {
                $service_descr = $this->international_types[substr($type,0,2)] . ' (' . substr($type,2,1) . ' days)';
              } else {
                $service_descr = $this->international_types[substr($type,0,2)];
              }
            }
            if ($method) {
              if (substr($type,0,2) != $method) $skip = TRUE;
            }
            if (!$skip) {
              $methods[] = array('id' => substr($type,0,2),
                                 'title' => $service_descr,
                                 'cost' => ($handling + $shipping + constant('MODULE_SHIPPING_FEDEX1_SURCHARGE_' . $vendors_id) + $this->surcharge + $cost) * $shipping_num_boxes);
            }
          }

          $this->quotes['methods'] = $methods;
    //      $this->tax_class = constant('MODULE_SHIPPING_FEDEX1_TAX_CLASS_' . $vendors_id);
             if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
            }
        }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => 'An error occured with the fedex shipping calculations.<br>Fedex may not deliver to your country, or your postal code may be wrong.');
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    //MVS
    function check($vendors_id='1') {
      if (!isset($this->_check)) {
              //multi vendor add  "vendors_id = '". $vendors_id ."' and"
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX1_STATUS_" . $vendors_id . "' and vendors_id = '" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

      function install($vendors_id='1') {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Fedex Shipping', 'MODULE_SHIPPING_FEDEX1_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer Fedex shipping?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Display Transit Times', 'MODULE_SHIPPING_FEDEX1_TRANSIT_" . $vendors_id . "', 'True', 'Do you want to show transit times for ground or home delivery rates?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Your Fedex Account Number', 'MODULE_SHIPPING_FEDEX1_ACCOUNT_" . $vendors_id . "', 'NONE', 'Enter the fedex Account Number assigned to you, required', '6', '11', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Your Fedex Meter ID', 'MODULE_SHIPPING_FEDEX1_METER_" . $vendors_id . "', 'NONE', 'Enter the Fedex MeterID assigned to you, set to NONE to obtain a new meter number', '6', '12', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('cURL Path', 'MODULE_SHIPPING_FEDEX1_CURL_" . $vendors_id . "', 'NONE', 'Enter the path to the cURL program, normally, leave this set to NONE to execute cURL using PHP', '6', '12', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Debug Mode', 'MODULE_SHIPPING_FEDEX1_DEBUG_" . $vendors_id . "', 'False', 'Turn on Debug', '6', '19', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Weight Units', 'MODULE_SHIPPING_FEDEX1_WEIGHT_" . $vendors_id . "', 'LBS', 'Weight Units:', '6', '19', 'tep_cfg_select_option(array(\'LBS\', \'KGS\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('First line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_1_" . $vendors_id . "', 'NONE', 'Enter the first line of your ship from street address, required', '6', '13', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Second line of street address', 'MODULE_SHIPPING_FEDEX1_ADDRESS_2_" . $vendors_id . "', 'NONE', 'Enter the second line of your ship from street address, leave set to NONE if you do not need to specify a second line', '6', '14', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('City name', 'MODULE_SHIPPING_FEDEX1_CITY_" . $vendors_id . "', 'NONE', 'Enter the city name for the ship from street address, required', '6', '15', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('State or Province name', 'MODULE_SHIPPING_FEDEX1_STATE_" . $vendors_id . "', 'NONE', 'Enter the 2 letter state or province name for the ship from street address, required for Canada and US', '6', '16', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Postal code', 'MODULE_SHIPPING_FEDEX1_POSTAL_" . $vendors_id . "', 'NONE', 'Enter the postal code for the ship from street address, required', '6', '17', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Phone number', 'MODULE_SHIPPING_FEDEX1_PHONE_" . $vendors_id . "', 'NONE', 'Enter a contact phone number for your company, required', '6', '18', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Which server to use', 'MODULE_SHIPPING_FEDEX1_SERVER_" . $vendors_id . "', 'production', 'You must have an account with Fedex', '6', '19', 'tep_cfg_select_option(array(\'test\', \'production\'), ', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Drop off type', 'MODULE_SHIPPING_FEDEX1_DROPOFF_" . $vendors_id . "', '1', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', '6', '20', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Fedex surcharge?', 'MODULE_SHIPPING_FEDEX1_SURCHARGE_" . $vendors_id . "', '0', 'Surcharge amount to add to shipping charge?', '6', '21', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Show List Rates?', 'MODULE_SHIPPING_FEDEX1_LIST_RATES_" . $vendors_id . "', 'False', 'Show LIST Rates?', '6', '21', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Residential surcharge?', 'MODULE_SHIPPING_FEDEX1_RESIDENTIAL_" . $vendors_id . "', '0', 'Residential Surcharge (in addition to other surcharge) for Express packages within US, or ground packages within Canada?', '6', '21', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Insurance?', 'MODULE_SHIPPING_FEDEX1_INSURE_" . $vendors_id . "', 'NONE', 'Insure packages over what dollar amount?', '6', '22', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Envelope Rates?', 'MODULE_SHIPPING_FEDEX1_ENVELOPE_" . $vendors_id . "', 'False', 'Do you want to offer Fedex Envelope rates? All items under 1/2 LB (.23KG) will quote using the envelope rate if True.', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Sort rates: ', 'MODULE_SHIPPING_FEDEX1_WEIGHT_SORT_" . $vendors_id . "', 'High to Low', 'Sort rates top to bottom: ', '6', '19', 'tep_cfg_select_option(array(\'High to Low\', \'Low to High\'), ', now(), '" . $vendors_id . "')");
          tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Timeout in Seconds', 'MODULE_SHIPPING_FEDEX1_TIMEOUT_" . $vendors_id . "', 'NONE', 'Enter the maximum time in seconds you would wait for a rate request from Fedex? Leave NONE for default timeout.', '6', '22', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_FEDEX1_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '23', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_FEDEX1_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_FEDEX1_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '24', now(), '" . $vendors_id . "')");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    function keys($vendors_id) {
      return array('MODULE_SHIPPING_FEDEX1_STATUS_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_ACCOUNT_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_METER_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_CURL_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_DEBUG_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_SERVER_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_ADDRESS_1_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_ADDRESS_2_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_CITY_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_STATE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_POSTAL_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_PHONE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_DROPOFF_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_TRANSIT_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_SURCHARGE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_LIST_RATES_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_INSURE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_RESIDENTIAL_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_ENVELOPE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_WEIGHT_SORT_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_TIMEOUT_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_ZONE_' . $vendors_id, 'MODULE_SHIPPING_FEDEX1_SORT_ORDER_' . $vendors_id);
    }

    function _setService($service, $vendors_id) {
      $this->service = $service;
    }

    function _setWeight($pounds, $vendors_id = '1') {
      $this->pounds = sprintf("%01.1f", $pounds);
    }

    function _setPackageType($type, $vendors_id = '1') {
      $this->packageType = $type;
    }

    function _setInsuranceValue($order_amount, $vendors_id = '1') {
      if ($order_amount > constant('MODULE_SHIPPING_FEDEX1_INSURE_' . $vendors_id)) {
        $this->insurance = sprintf("%01.2f",$order_amount);
      } else {
        $this->insurance = 0;
      }
    }

    function _AccessFedex($data, $vendors_id = '1') {

      if (constant('MODULE_SHIPPING_FEDEX1_SERVER_' . $vendors_id) == 'production') {
        $this->server = 'gateway.fedex.com/GatewayDC';
      } else {
        $this->server = 'gatewaybeta.fedex.com/GatewayDC';
      }
      if (constant('MODULE_SHIPPING_FEDEX1_CURL_' . $vendors_id) == "NONE") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->server);
        if (constant('MODULE_SHIPPING_FEDEX1_TIMEOUT_' . $vendors_id) != 'NONE') curl_setopt($ch, CURLOPT_TIMEOUT, constant('MODULE_SHIPPING_FEDEX1_TIMEOUT_' . $vendors_id));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Referer: " . STORE_NAME,
                                                   "Host: " . $this->server,
                                                   "Accept: image/gif,image/jpeg,image/pjpeg,text/plain,text/html,*/*",
                                                   "Pragma:",
                                                   "Content-Type:image/gif"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $reply = curl_exec($ch);
        curl_close ($ch);
      } else {                         // STORE_NAME
        $this->command_line = constant('MODULE_SHIPPING_FEDEX1_CURL_' . $vendors_id) . " " . constant('MODULE_SHIPPING_FEDEX1_TIMEOUT_' . $vendors_id) == 'NONE' ? '' : '-m ' . constant('MODULE_SHIPPING_FEDEX1_TIMEOUT_' . $vendors_id) . " -s -e '" . STORE_NAME . "' --url https://" . $this->server . " -H 'Host: " . $this->server . "' -H 'Accept: image/gif,image/jpeg,image/pjpeg,text/plain,text/html,*/*' -H 'Pragma:' -H 'Content-Type:image/gif' -d '" . $data . "' 'https://" . $this->server . "'";
        exec($this->command_line, $this->reply);
        $reply = $this->reply[0];
      }
        return $reply;
    }

    function _getMeter($vendors_id = '1') {
      $data = '0,"211"'; // Transaction Code, required
      $data .= '10,"' . constant('MODULE_SHIPPING_FEDEX1_ACCOUNT_' . $vendors_id) . '"'; // Sender Fedex account number
      $data .= '4003,"' . STORE_OWNER . '"'; // Subscriber contact name
      $data .= '4007,"' . STORE_NAME . '"'; // Subscriber company name
      $data .= '4008,"' . constant('MODULE_SHIPPING_FEDEX1_ADDRESS_1_' . $vendors_id) . '"'; // Subscriber Address line 1
      if (constant('MODULE_SHIPPING_FEDEX1_ADDRESS_2_' . $vendors_id) != 'NONE') {
        $data .= '4009,"' . constant('MODULE_SHIPPING_FEDEX1_ADDRESS_2_' . $vendors_id) . '"'; // Subscriber Address Line 2
      }
      $data .= '4011,"' . constant('MODULE_SHIPPING_FEDEX1_CITY_' . $vendors_id) . '"'; // Subscriber City Name
      if (constant('MODULE_SHIPPING_FEDEX1_STATE_' . $vendors_id) != 'NONE') {
        $data .= '4012,"' . constant('MODULE_SHIPPING_FEDEX1_STATE_' . $vendors_id) . '"'; // Subscriber State code
      }
      $data .= '4013,"' . constant('MODULE_SHIPPING_FEDEX1_POSTAL_' . $vendors_id) . '"'; // Subscriber Postal Code
      $data .= '4014,"' . $this->country . '"'; // Subscriber Country Code
      $data .= '4015,"' . constant('MODULE_SHIPPING_FEDEX1_PHONE_' . $vendors_id) . '"'; // Subscriber phone number
      $data .= '99,""'; // End of Record, required
      if (constant('MODULE_SHIPPING_FEDEX1_DEBUG_' . $vendors_id) == 'True') echo "Data sent to Fedex for Meter for Vendor - " . $vendors_id . " : " . $data . "<br>";
      $fedexData = $this->_AccessFedex($data, $vendors_id);
      if (constant('MODULE_SHIPPING_FEDEX1_DEBUG_' . $vendors_id) == 'True') echo "Data returned from Fedex for Meter for Vendor - " . $vendors_id . " : " . $fedexData . "<br>";
      $meterStart = strpos($fedexData,'"498,"');

      if ($meterStart === FALSE) {
        if (strlen($fedexData) == 0) {
          $this->error_message = 'No response to CURL from Fedex server, check CURL availability, or maybe timeout was set too low, or maybe the Fedex site is down';
        } else {
          $fedexData = $this->_ParseFedex($fedexData, $vendors_id);
          $this->error_message = 'No meter number was obtained, check configuration. Error ' . $fedexData['2'] . ' : ' . $fedexData['3'];
        }
        return false;
      }

      $meterStart += 6;
      $meterEnd = strpos($fedexData, '"', $meterStart);
      $this->meter = substr($fedexData, $meterStart, $meterEnd - $meterStart);
      $meter_sql = "UPDATE " . TABLE_VENDOR_CONFIGURATION . " SET configuration_value='" . $this->meter . "' where configuration_key='MODULE_SHIPPING_FEDEX1_METER_" . $vendors_id . "'";
      tep_db_query($meter_sql);

      return true;
    }

    function _ParseFedex($data, $vendors_id) {
      $current = 0;
      $length = strlen($data);
      $resultArray = array();
      while ($current < $length) {
        $endpos = strpos($data, ',', $current);
        if ($endpos === FALSE) { break; }
        $index = substr($data, $current, $endpos - $current);
        $current = $endpos + 2;
        $endpos = strpos($data, '"', $current);
        $resultArray[$index] = substr($data, $current, $endpos - $current);
        $current = $endpos + 1;
      }
    return $resultArray;
    }

    function _getQuote($vendors_id = '1') {
      global $order, $customer_id, $sendto;

      if (constant('MODULE_SHIPPING_FEDEX1_ACCOUNT_' . $vendors_id) == "NONE" || strlen(constant('MODULE_SHIPPING_FEDEX1_ACCOUNT_' . $vendors_id)) == 0) {
        return array('error' => 'You forgot to set up your Fedex account number, this can be set up in Admin -> Modules -> Shipping');
      }
      if (constant('MODULE_SHIPPING_FEDEX1_ADDRESS_1_' . $vendors_id) == "NONE" || strlen(constant('MODULE_SHIPPING_FEDEX1_ADDRESS_1_' . $vendors_id)) == 0) {
        return array('error' => 'You forgot to set up your ship from street address line 1, this can be set up in Admin -> Modules -> Shipping');
      }
      if (constant('MODULE_SHIPPING_FEDEX1_CITY_' . $vendors_id) == "NONE" || strlen(constant('MODULE_SHIPPING_FEDEX1_CITY_' . $vendors_id)) == 0) {
        return array('error' => 'You forgot to set up your ship from City, this can be set up in Admin -> Modules -> Shipping');
      }
      if (constant('MODULE_SHIPPING_FEDEX1_POSTAL_' . $vendors_id) == "NONE" || strlen(constant('MODULE_SHIPPING_FEDEX1_POSTAL_' . $vendors_id)) == 0) {
        return array('error' => 'You forgot to set up your ship from postal code, this can be set up in Admin -> Modules -> Shipping');
      }
      if (constant('MODULE_SHIPPING_FEDEX1_PHONE_' . $vendors_id) == "NONE" || strlen(constant('MODULE_SHIPPING_FEDEX1_PHONE_' . $vendors_id)) == 0) {
        return array('error' => 'You forgot to set up your ship from phone number, this can be set up in Admin -> Modules -> Shipping');
      }
      if (constant('MODULE_SHIPPING_FEDEX1_METER_' . $vendors_id) == "NONE") {
        if ($this->_getMeter($vendors_id) === false) return array('error' => $this->error_message);
      }

      $data = '0,"25"'; // TransactionCode
      $data .= '10,"' . constant('MODULE_SHIPPING_FEDEX1_ACCOUNT_' . $vendors_id) . '"'; // Sender fedex account number
      //$data .= '498,"' . $this->meter . '"'; // Meter number
      $data .= '498,"' . constant('MODULE_SHIPPING_FEDEX1_METER_' . $vendors_id) . '"'; // Meter number
      $data .= '8,"' . constant('MODULE_SHIPPING_FEDEX1_STATE_' . $vendors_id) . '"'; // Sender state code
      $orig_zip = str_replace(array(' ', '-'), '', constant('MODULE_SHIPPING_FEDEX1_POSTAL_' . $vendors_id));
      $data .= '9,"' . $orig_zip . '"'; // Origin postal code
      $data .= '117,"' . $this->country . '"'; // Origin country
      $dest_zip = str_replace(array(' ', '-'), '', $order->delivery['postcode']);
      $data .= '17,"' . $dest_zip . '"'; // Recipient zip code
      if ($order->delivery['country']['iso_code_2'] == "US" || $order->delivery['country']['iso_code_2'] == "CA" || $order->delivery['country']['iso_code_2'] == "PR") {
        $state .= tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], ''); // Recipient state
        if ($state == "QC") $state = "PQ";
        $data .= '16,"' . $state . '"'; // Recipient state
      }
      $data .= '50,"' . $order->delivery['country']['iso_code_2'] . '"'; // Recipient country
      $data .= '75,"' . constant('MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id) . '"'; // Weight units
      if (constant('MODULE_SHIPPING_FEDEX1_WEIGHT_' . $vendors_id) == "KGS") {
        $data .= '1116,"C"'; // Dimension units
      } else {
        $data .= '1116,"I"'; // Dimension units
      }
      $data .= '1401,"' . $this->pounds . '"'; // Total weight
      $data .= '1529,"1"'; // Quote discounted rates
      if ($this->insurance > 0) {
        $data .= '1415,"' . $this->insurance . '"'; // Insurance value
        $data .= '68,"USD"'; // Insurance value currency
      }
      if ($order->delivery['company'] == '' && constant('MODULE_SHIPPING_FEDEX1_RESIDENTIAL_' . $vendors_id) == 0) {
        $data .= '440,"Y"'; // Residential address
      }else {
        $data .= '440,"N"'; // Business address, use if adding a residential surcharge
      }
      $data .= '1273,"' . $this->packageType . '"'; // Package type
      $data .= '1333,"' . constant('MODULE_SHIPPING_FEDEX1_DROPOFF_' . $vendors_id) . '"'; // Drop of drop off or pickup
      if (constant('MODULE_SHIPPING_FEDEX1_LIST_RATES_' . $vendors_id) == 'True') {
        $data .= '1529,"2"'; // Also return list rates
      }
      $data .= '99,""'; // End of record
      if (constant('MODULE_SHIPPING_FEDEX1_DEBUG_' . $vendors_id) == 'True') echo "Data sent to Fedex for Rating for Vendor - " . $vendors_id . " : " . $data . "<br>";
      $fedexData = $this->_AccessFedex($data, $vendors_id);
      if (constant('MODULE_SHIPPING_FEDEX1_DEBUG_' . $vendors_id) == 'True') echo "Data returned from Fedex for Rating for Vendor - " . $vendors_id . " : " . $fedexData . "<br>";
      if (strlen($fedexData) == 0) {
        $this->error_message = 'No data returned from Fedex, perhaps the Fedex site is down';
        return array('error' => $this->error_message);
      }
      $fedexData = $this->_ParseFedex($fedexData, $vendors_id);
      $i = 1;
      if ($this->country == $order->delivery['country']['iso_code_2']) {
        $this->intl = FALSE;
      } else {
        $this->intl = TRUE;
      }
      $rates = NULL;
      while (isset($fedexData['1274-' . $i])) {
        if ($this->intl) {
          if (isset($this->international_types[$fedexData['1274-' . $i]])) {
            if (constant('MODULE_SHIPPING_FEDEX1_LIST_RATES_' . $vendors_id) == 'False') {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1419-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1419-' . $i];
              }
            } else {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1528-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1528-' . $i];
              }
            }
          }
        } else {
          if (isset($this->domestic_types[$fedexData['1274-' . $i]])) {
            if (constant('MODULE_SHIPPING_FEDEX1_LIST_RATES_' . $vendors_id) == 'False') {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1419-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1419-' . $i];
              }
            } else {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1528-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1528-' . $i];
              }
            }
          }
        }
         $i++;
      }

      if (is_array($rates)) {
        if (constant('MODULE_SHIPPING_FEDEX1_WEIGHT_SORT_' . $vendors_id) == 'Low to High') {
          asort($rates);
        } else {
          arsort($rates);
        }
      } else {
        $this->error_message = 'No Rates Returned, ' . $fedexData['2'] . ' : ' . $fedexData['3'];
        return array('error' => $this->error_message);
      }

      return ((sizeof($rates) > 0) ? $rates : false);
    }
  }
?>