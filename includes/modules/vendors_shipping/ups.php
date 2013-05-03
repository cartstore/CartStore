<?php
/*
$Id: ups.php,v 1.54 2003/04/08 23:23:42 dgw_ Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

/*
revised by Fritz Clapp as UPS Choice 1.8 2003/08/02
  filters service types to those selected in admin and saved in
  configuration table with key MODULE_SHIPPING_UPS_TYPES;
  suggests STD service as default for Canada;
  modified error message refers to failure to get quote;
  //multi vendor add  "vendors_id = '". $vendors_id ."' and" to all sql queries
*/

  class ups {
    var $code, $title, $descrption, $icon, $enabled, $types;

// class constructor
    function ups() {
      global $order, $vendors_id;

//MVS
//      $this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
      $this->code = 'ups';
      $this->title = MODULE_SHIPPING_UPS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_UPS_TEXT_DESCRIPTION;
      $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
      $this->delivery_country_id = $order->delivery['country']['id'];
      $this->delivery_zone_id = $order->delivery['zone_id'];
      $this->types = array('1DM' => 'Next Day Air Early AM',
                           '1DML' => 'Next Day Air Early AM Letter',
                           '1DA' => 'Next Day Air',
                           '1DAL' => 'Next Day Air Letter',
                           '1DAPI' => 'Next Day Air Intra (Puerto Rico)',
                           '1DP' => 'Next Day Air Saver',
                           '1DPL' => 'Next Day Air Saver Letter',
                           '2DM' => '2nd Day Air AM',
                           '2DML' => '2nd Day Air AM Letter',
                           '2DA' => '2nd Day Air',
                           '2DAL' => '2nd Day Air Letter',
                           '3DS' => '3 Day Select',
                           'GND' => 'Ground',
                           'GNDCOM' => 'Ground Commercial',
                           'GNDRES' => 'Ground Residential',
                           'STD' => 'Canada Standard',
                           'XPR' => 'Worldwide Express',
                           'XPRL' => 'worldwide Express Letter',
                           'XDM' => 'Worldwide Express Plus',
                           'XDML' => 'Worldwide Express Plus Letter',
                           'XPD' => 'Worldwide Expedited');
    }

//MVS start
     function sort_order($vendors_id='1') {
     $sort_order = @constant ('MODULE_SHIPPING_UPS_SORT_ORDER_' . $vendors_id);
     if (isset ($sort_order)) {        $this->sort_order = $sort_order;
     } else {
       $this->sort_order = '-';
     }
     return $this->sort_order;
   }

                function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_UPS_TAX_CLASS_' . $vendors_id);
                        return $this->tax_class;
    }

    function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_UPS_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_UPS_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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
// END

      return $this->enabled;
    }

                function zones($vendors_id='1') {
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_UPS_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
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
//MVS End

//Get a quote
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $_POST, $shipping_weight, $order, $cart, $shipping_num_boxes;

      if ( (tep_not_null($method)) && (isset($this->types[$method])) ) {
        $prod = $method;
      } else if ($order->delivery['country']['iso_code_2'] == 'CA') {
            $prod = 'STD';
      } else {
        $prod = 'GNDRES';
      }

      if ($method) $this->_upsAction('3'); // return a single quote

      $this->_upsProduct($prod);

//MVS Start
      $vendors_data_query = tep_db_query("select handling_charge,
                                                 handling_per_box,
                                                 vendor_country,
                                                 vendors_zipcode
                                          from " . TABLE_VENDORS . "
                                          where vendors_id = '" . (int)$vendors_id . "'"
                                        );
      $vendors_data = tep_db_fetch_array($vendors_data_query);
      $country_name = tep_get_countries($vendors_data['vendor_country'], true);
//MVS End

      $this->_upsDest($order->delivery['postcode'], $order->delivery['country']['iso_code_2']);
//MVS Start
      $this->_upsOrigin($vendors_data['vendors_zipcode'], $country_name['countries_iso_code_2']);
      $this->_upsRate(constant('MODULE_SHIPPING_UPS_PICKUP_' . $vendors_id));
      $this->_upsContainer(constant('MODULE_SHIPPING_UPS_PACKAGE_' . $vendors_id));
      $this->_upsRescom(constant('MODULE_SHIPPING_UPS_RES_' . $vendors_id));
//MVS end
      $this->_upsWeight($shipping_weight);
      $upsQuote = $this->_upsGetQuote();

      if ( (is_array($upsQuote)) && (sizeof($upsQuote) > 0) ) {
        $this->quotes = array('id' => $this->code,
                              'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'lbs)');

//MVS Start
        $handling_charge = $vendors_data['handling_charge'];
        $handling_per_box = $vendors_data['handling_per_box'];
        if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
          $handling = $handling_charge;
        } else {
          $handling = $handling_per_box*$shipping_num_boxes;
        }
//MVS End

        $methods = array();
        $allowed_methods = explode(", ", @constant ('MODULE_SHIPPING_UPS_TYPES_' . $vendors_id));
        $std_rcd = false;
        $qsize = sizeof($upsQuote);
        for ($i=0; $i<$qsize; $i++) {
          list($type, $cost) = each($upsQuote[$i]);
                  if ($type=='STD') {
                          if ($std_rcd) continue;
                          else $std_rcd = true;
                        };
                  if (!in_array($type, $allowed_methods)) continue;
//MVS - Changed 'cost' => ($cost * $shipping_num_boxes) + $handling;
          $methods[] = array('id' => $type,
                             'title' => $this->types[$type],
                             'cost' => ($cost * $shipping_num_boxes) + $handling);
        }

        $this->quotes['methods'] = $methods;
 //   $this->tax_class = constant(MODULE_SHIPPING_UPS_TAX_CLASS_ . $vendors_id);
                if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
           }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => 'We are unable to obtain a rate quote for UPS shipping.<br>Please contact the store if no other alternative is shown.');
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check($vendors_id='1') {
      if (!isset($this->_check)) {
              //multi vendor add  "vendors_id = '". $vendors_id ."' and"
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_STATUS_" . $vendors_id . "' and vendors_id = '" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install($vendors_id='1') {
    //multi vendor add 'vendors_id' to field names and '" . $vID . "', to values
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . $vendors_id . "', 'Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP_" . $vendors_id . "', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', '6', '0', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE_" . $vendors_id . "', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', '6', '0', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Residential Delivery?', 'MODULE_SHIPPING_UPS_RES_" . $vendors_id . "', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Handling Fee', 'MODULE_SHIPPING_UPS_HANDLING_" . $vendors_id . "', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . $vendors_id . "', 'Tax Class', 'MODULE_SHIPPING_UPS_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . $vendors_id . "', 'Shipping Zone', 'MODULE_SHIPPING_UPS_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Sort order of display.', 'MODULE_SHIPPING_UPS_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . $vendors_id . "', 'Shipping Methods', 'MODULE_SHIPPING_UPS_TYPES_" . $vendors_id . "', 'Nxt AM,Nxt AM Ltr,Nxt,Nxt Ltr,Nxt PR,Nxt Save,Nxt Save Ltr,2nd AM,2nd AM Ltr,2nd,2nd Ltr,3 Day Select,Ground,Canada,World Xp,World Xp Ltr, World Xp Plus,World Xp Plus Ltr,World Expedite', 'Select the USPS services to be offered.', '6', '13', 'tep_cfg_select_multioption(array(\'1DM\',\'1DML\', \'1DA\', \'1DAL\', \'1DAPI\', \'1DP\', \'1DPL\', \'2DM\', \'2DML\', \'2DA\', \'2DAL\', \'3DS\',\'GND\', \'STD\', \'XPR\', \'XPRL\', \'XDM\', \'XDML\', \'XPD\'), ', now() )");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '" . $vendors_id . "' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    function keys($vendors_id='1') {
      return array('MODULE_SHIPPING_UPS_STATUS_' . $vendors_id, 'MODULE_SHIPPING_UPS_PICKUP_' . $vendors_id, 'MODULE_SHIPPING_UPS_PACKAGE_' . $vendors_id, 'MODULE_SHIPPING_UPS_RES_' . $vendors_id, 'MODULE_SHIPPING_UPS_HANDLING_' . $vendors_id, 'MODULE_SHIPPING_UPS_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_UPS_ZONE_' . $vendors_id, 'MODULE_SHIPPING_UPS_SORT_ORDER_' . $vendors_id, 'MODULE_SHIPPING_UPS_TYPES_' . $vendors_id);
    }

    function _upsProduct($prod){
      $this->_upsProductCode = $prod;
    }

    function _upsOrigin($postal, $country){
      $this->_upsOriginPostalCode = $postal;
      $this->_upsOriginCountryCode = $country;
    }

    function _upsDest($postal, $country){
      $postal = str_replace(' ', '', $postal);

      if ($country == 'US') {
        $this->_upsDestPostalCode = substr($postal, 0, 5);
      } else {
        $this->_upsDestPostalCode = $postal;
      }

      $this->_upsDestCountryCode = $country;
    }

    function _upsRate($foo) {
      switch ($foo) {
        case 'RDP':
          $this->_upsRateCode = 'Regular+Daily+Pickup';
          break;
        case 'OCA':
          $this->_upsRateCode = 'On+Call+Air';
          break;
        case 'OTP':
          $this->_upsRateCode = 'One+Time+Pickup';
          break;
        case 'LC':
          $this->_upsRateCode = 'Letter+Center';
          break;
        case 'CC':
          $this->_upsRateCode = 'Customer+Counter';
          break;
      }
    }

    function _upsContainer($foo) {
      switch ($foo) {
        case 'CP': // Customer Packaging
          $this->_upsContainerCode = '00';
          break;
        case 'ULE': // UPS Letter Envelope
          $this->_upsContainerCode = '01';
          break;
        case 'UT': // UPS Tube
          $this->_upsContainerCode = '03';
          break;
        case 'UEB': // UPS Express Box
          $this->_upsContainerCode = '21';
          break;
        case 'UW25': // UPS Worldwide 25 kilo
          $this->_upsContainerCode = '24';
          break;
        case 'UW10': // UPS Worldwide 10 kilo
          $this->_upsContainerCode = '25';
          break;
      }
    }

    function _upsWeight($foo) {
      $this->_upsPackageWeight = $foo;
    }

    function _upsRescom($foo) {
      switch ($foo) {
        case 'RES': // Residential Address
          $this->_upsResComCode = '1';
          break;
        case 'COM': // Commercial Address
          $this->_upsResComCode = '2';
          break;
      }
    }

    function _upsAction($action) {
      /* 3 - Single Quote
         4 - All Available Quotes */

      $this->_upsActionCode = $action;
    }

    function _upsGetQuote() {
      if (!isset($this->_upsActionCode)) $this->_upsActionCode = '4';

      $request = join('&', array('accept_UPS_license_agreement=yes',
                                 '10_action=' . $this->_upsActionCode,
                                 '13_product=' . $this->_upsProductCode,
                                 '14_origCountry=' . $this->_upsOriginCountryCode,
                                 '15_origPostal=' . $this->_upsOriginPostalCode,
                                 '19_destPostal=' . $this->_upsDestPostalCode,
                                 '22_destCountry=' . $this->_upsDestCountryCode,
                                 '23_weight=' . $this->_upsPackageWeight,
                                 '47_rate_chart=' . $this->_upsRateCode,
                                 '48_container=' . $this->_upsContainerCode,
                                 '49_residential=' . $this->_upsResComCode));

      $http = new httpClient();
      if ($http->Connect('153.2.228.50', 80)) {
        $http->addHeader('Host', 'www.ups.com');
        $http->addHeader('User-Agent', 'osCommerce');
        $http->addHeader('Connection', 'Close');

        if ($http->Get('/using/services/rave/qcostcgi.cgi?' . $request)) $body = $http->getBody();

        $http->Disconnect();
      } else {
        return 'error';
      }
/*
    mail('you@yourdomain.com','UPS response',$body,'From: <you@yourdomain.com>');
*/
      $body_array = explode("\n", $body);

      $returnval = array();
      $errorret = 'error'; // only return error if NO rates returned

      $n = sizeof($body_array);
      for ($i=0; $i<$n; $i++) {
        $result = explode('%', $body_array[$i]);
        $errcode = substr($result[0], -1);
        switch ($errcode) {
          case 3:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 4:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 5:
            $errorret = $result[1];
            break;
          case 6:
            if (is_array($returnval)) $returnval[] = array($result[3] => $result[10]);
            break;
        }
      }
      if (empty($returnval)) $returnval = $errorret;

      return $returnval;
    }
  }
?>