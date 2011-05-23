<?php
/*
  $Id: usps.php,v 1.47 2003/04/08 23:23:42 dgw_ Exp $
  ++++ modified as USPS Methods 2.7 03/26/04 by Brad Waite and Fritz Clapp ++++
  ++++ incorporating USPS revisions to service names ++++
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class usps {
    var $code, $title, $description, $icon, $enabled, $countries;

// class constructor
    function usps() {
      global $order;

      $this->code = 'usps';
      $this->title = MODULE_SHIPPING_USPS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_USPS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_USPS_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_usps.gif';
      $this->tax_class = MODULE_SHIPPING_USPS_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_USPS_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_USPS_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_USPS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

      $this->types = array('EXPRESS' => 'EXPRESS', 'FIRST CLASS' => 'First-Class Mail', 'PRIORITY' => 'PRIORITY', 'PARCEL' => 'Parcel', 'BPM' => 'Bound Printed Material', 'LIBRARY' => 'Library', 'MEDIA' => 'Media Mail');

      /*
      $this->intl_types = array('GXG Document' => 'Global Express Guaranteed Document Service',
                                'GXG Non-Document' => 'Global Express Guaranteed Non-Document Service',
                                'Express' => 'Global Express Mail (EMS)',
                                'Priority Lg' => 'Global Priority Mail - Flat-rate Envelope (Large)',
                                'Priority Sm' => 'Global Priority Mail - Flat-rate Envelope (Small)',
                                'Priority Var' => 'Global Priority Mail - Variable Weight (Single)',
                                'Airmail Letter' => 'Airmail Letter-post',
                                'Airmail Parcel' => 'Airmail Parcel Post',
                                'Surface Letter' => 'Economy (Surface) Letter-post',
                                'Surface Post' => 'Economy (Surface) Parcel Post');
      */

           $this->intl_types = array('GLOBAL EXPRESS' => 'Global Express Guaranteed',
                'GLOBAL EXPRESS NON-DOC RECT' => 'Global Express Guaranteed Non-Document Rectangular',
                'GLOBAL EXPRESS NON-DOC NON-RECT' => 'Global Express Guaranteed Non-Document Non-Rectangular',
                'EXPRESS MAIL INT' => 'Express Mail International (EMS)',
                'EXPRESS MAIL INT FLAT RATE ENV' => 'Express Mail International (EMS) Flat Rate Envelope',
                'PRIORITY MAIL INT' => 'Priority Mail International',
                'PRIORITY MAIL INT FLAT RATE ENV' => 'Priority Mail International Flat Rate Envelope',
                'PRIORITY MAIL INT FLAT RATE BOX' => 'Priority Mail International Flat Rate Box',
                'FIRST-CLASS MAIL INT' => 'First-Class Mail International');

      $this->countries = $this->country_list();
    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes, $transittime;

      if ( tep_not_null($method) && (isset($this->types[$method]) || in_array($method, $this->intl_types)) ) {
        $this->_setService($method);
      }

      $this->_setContainer('None');
      $this->_setSize('REGULAR');

// usps doesnt accept zero weight
      $shipping_weight = ($shipping_weight < 0.1 ? 0.1 : $shipping_weight);
      $shipping_pounds = floor ($shipping_weight);
      $shipping_ounces = round(16 * ($shipping_weight - floor($shipping_weight)));
      $this->_setWeight($shipping_pounds, $shipping_ounces);

// Added by Kevin Chen (kkchen@uci.edu); Fixes the Parcel Post Bug July 1, 2004
// Refer to http://www.usps.com/webtools/htm/Domestic-Rates.htm documentation
// Thanks Ryan
      if($shipping_pounds > 35 || $shipping_ounces < 6){
      $this->_setMachinable('False');
        }
      else{
      $this->_setMachinable('True');
      }
// End Kevin Chen July 1, 2004

      if (in_array('Display weight', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) {
        $shiptitle = ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'lbs)';
      } else {
        $shiptitle = '';
      }

      $uspsQuote = $this->_getQuote();

      if (is_array($uspsQuote)) {
        if (isset($uspsQuote['error'])) {
          $this->quotes = array('module' => $this->title,
                                'error' => $uspsQuote['error']);
        } else {
          $this->quotes = array('id' => $this->code,
                                'module' => $this->title . $shiptitle);

          $methods = array();
          $size = sizeof($uspsQuote);
          for ($i=0; $i<$size; $i++) {
            list($type, $cost) = each($uspsQuote[$i]);

            $title = ((isset($this->types[$type])) ? $this->types[$type] : $type);
            if(in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS)))    $title .= $transittime[$type];

            $methods[] = array('id' => $type,
                               'title' => $title,
                               'cost' => ($cost + MODULE_SHIPPING_USPS_HANDLING) * $shipping_num_boxes);
          }

          $this->quotes['methods'] = $methods;

          if ($this->tax_class > 0) {
            $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          }
        }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => MODULE_SHIPPING_USPS_TEXT_ERROR);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', 'True', 'Do you want to offer USPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID', 'NONE', 'Enter the USPS USERID assigned to you.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Password', 'MODULE_SHIPPING_USPS_PASSWORD', 'NONE', 'See USERID, above.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Which server to use', 'MODULE_SHIPPING_USPS_SERVER', 'production', 'An account at USPS is needed to use the Production server', '6', '0', 'tep_cfg_select_option(array(\'test\', \'production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_USPS_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Domestic Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES', 'EXPRESS, PRIORITY, FIRST CLASS, PARCEL, BMP, LIBRARY, MEDIA,', 'Select the domestic services to be offered:', '6', '14', 'tep_cfg_select_multioption(array(\'EXPRESS\', \'PRIORITY\', \'FIRST CLASS\', \'PARCEL\',\'BPM\',\'LIBRARY\',\'MEDIA\'), ',  now())");
      /*
      tep_db_query("insert into " . TABLE_CONFIGURATION . "  (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Int\'l Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_INTL', 'GXG Document, GXG Non-Document, Express, Priority Lg, Priority Sm, Priority Var, Airmail Letter, Airmail Parcel, Surface Letter, Surface Post', 'Select the international services to be offered:', '6', '15', 'tep_cfg_select_multioption(array(\'GXG Document\', \'GXG Non-Document\', \'Express\', \'Priority Lg\', \'Priority Sm\', \'Priority Var\', \'Airmail Letter\', \'Airmail Parcel\', \'Surface Letter\', \'Surface Post\'), ',  now())");
      */
      tep_db_query("insert into " . TABLE_CONFIGURATION . "  (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Int\'l Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_INTL', 'GLOBAL EXPRESS,GLOBAL EXPRESS NON-DOC RECT, GLOBAL EXPRESS NON-DOC NON-RECT, EXPRESS MAIL INT, EXPRESS MAIL INT FLAT RATE ENV, PRIORITY MAIL INT, PRIORITY MAIL INT FLAT RATE ENV, PRIORITY MAIL INT FLAT RATE BOX, FIRST-CLASS MAIL INT', 'Select the international services to be offered:', '6', '15', 'tep_cfg_select_multioption(array(\'GLOBAL EXPRESS\', \'GLOBAL EXPRESS NON-DOC RECT\', \'GLOBAL EXPRESS NON-DOC NON-RECT\', \'EXPRESS MAIL INT\', \'EXPRESS MAIL INT FLAT RATE ENV\', \'PRIORITY MAIL INT\', \'PRIORITY MAIL INT FLAT RATE ENV\', \'PRIORITY MAIL INT FLAT RATE BOX\', \'FIRST-CLASS MAIL INT\'), ',  now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('USPS Options', 'MODULE_SHIPPING_USPS_OPTIONS', 'Display weight, Display transit time', 'Select from the following the USPS options.', '6', '16', 'tep_cfg_select_multioption(array(\'Display weight\', \'Display transit time\'), ',  now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_USPS_STATUS', 'MODULE_SHIPPING_USPS_USERID', 'MODULE_SHIPPING_USPS_PASSWORD', 'MODULE_SHIPPING_USPS_SERVER', 'MODULE_SHIPPING_USPS_HANDLING', 'MODULE_SHIPPING_USPS_TAX_CLASS', 'MODULE_SHIPPING_USPS_ZONE', 'MODULE_SHIPPING_USPS_SORT_ORDER', 'MODULE_SHIPPING_USPS_OPTIONS', 'MODULE_SHIPPING_USPS_TYPES', 'MODULE_SHIPPING_USPS_TYPES_INTL');
    }

    function _setService($service) {
      $this->service = $service;
    }

    function _setWeight($pounds, $ounces=0) {
      $this->pounds = $pounds;
      $this->ounces = $ounces;
    }

    function _setContainer($container) {
      $this->container = $container;
    }

    function _setSize($size) {
      $this->size = $size;
    }

    function _setMachinable($machinable) {
      $this->machinable = $machinable;
    }

    function _getQuote() {
      global $order, $transittime;

      if(in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) $transit = TRUE;

      if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) {
        $request  = '<RateRequest USERID="' . MODULE_SHIPPING_USPS_USERID . '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">';
        $services_count = 0;

        if (isset($this->service)) {
          $this->types = array($this->service => $this->types[$this->service]);
        }

        $dest_zip = str_replace(' ', '', $order->delivery['postcode']);
        if ($order->delivery['country']['iso_code_2'] == 'US') $dest_zip = substr($dest_zip, 0, 5);

        reset($this->types);
        $allowed_types = explode(", ", MODULE_SHIPPING_USPS_TYPES);

        while (list($key, $value) = each($this->types)) {

      if ( !in_array($key, $allowed_types) ) continue;

          $request .= '<Package ID="' . $services_count . '">' .
                      '<Service>' . $key . '</Service>' .
                      '<ZipOrigination>' . SHIPPING_ORIGIN_ZIP . '</ZipOrigination>' .
                      '<ZipDestination>' . $dest_zip . '</ZipDestination>' .
                      '<Pounds>' . $this->pounds . '</Pounds>' .
                      '<Ounces>' . $this->ounces . '</Ounces>' .
                      '<Container>' . $this->container . '</Container>' .
                      '<Size>' . $this->size . '</Size>' .
                      '<Machinable>' . $this->machinable . '</Machinable>' .
                      '</Package>';

          if($transit){
            $transitreq  = 'USERID="' . MODULE_SHIPPING_USPS_USERID .
                         '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">' .
                         '<OriginZip>' . STORE_ORIGIN_ZIP . '</OriginZip>' .
                         '<DestinationZip>' . $dest_zip . '</DestinationZip>';

            switch ($key) {
              case 'EXPRESS':  $transreq[$key] = 'API=ExpressMail&XML=' .
                               urlencode( '<ExpressMailRequest ' . $transitreq . '</ExpressMailRequest>');
                               break;
              case 'PRIORITY': $transreq[$key] = 'API=PriorityMail&XML=' .
                               urlencode( '<PriorityMailRequest ' . $transitreq . '</PriorityMailRequest>');
                               break;
              case 'PARCEL':   $transreq[$key] = 'API=StandardB&XML=' .
                               urlencode( '<StandardBRequest ' . $transitreq . '</StandardBRequest>');
                               break;
              default:         $transreq[$key] = '';
                               break;
            }
          }

          $services_count++;
        }
        $request .= '</RateRequest>';

        $request = 'API=Rate&XML=' . urlencode($request);
      } else {
        $request  = '<IntlRateRequest USERID="' . MODULE_SHIPPING_USPS_USERID . '" PASSWORD="' . MODULE_SHIPPING_USPS_PASSWORD . '">' .
                    '<Package ID="0">' .
                    '<Pounds>' . $this->pounds . '</Pounds>' .
                    '<Ounces>' . $this->ounces . '</Ounces>' .
                    '<MailType>Package</MailType>' .
                    '<Country>' . $this->countries[$order->delivery['country']['iso_code_2']] . '</Country>' .
                    '</Package>' .
                    '</IntlRateRequest>';

        $request = 'API=IntlRate&XML=' . urlencode($request);
      }

      switch (MODULE_SHIPPING_USPS_SERVER) {
        case 'production': $usps_server = 'production.shippingapis.com'; //'stg-production.shippingapis.com'; // or  stg-secure.shippingapis.com //'production.shippingapis.com';
                           $api_dll = 'shippingapi.dll'; //'shippingapi.dll';
                           break;
        case 'test':
        default:           $usps_server = 'testing.shippingapis.com';
                           $api_dll = 'ShippingAPITest.dll';
                           break;
      }

      $body = '';

      $http = new httpClient();
      if ($http->Connect($usps_server, 80)) {
        $http->addHeader('Host', $usps_server);
        $http->addHeader('User-Agent', 'osCommerce');
        $http->addHeader('Connection', 'Close');

        if ($http->Get('/' . $api_dll . '?' . $request)) $body = $http->getBody();
//  mail('you@yourdomain.com','USPS rate quote response',$body,'From: <you@yourdomain.com>');
        if ($transit && is_array($transreq) && ($order->delivery['country']['id'] == STORE_COUNTRY)) {
          while (list($key, $value) = each($transreq)) {
            if ($http->Get('/' . $api_dll . '?' . $value)) $transresp[$key] = $http->getBody();
          }
        }

        $http->Disconnect();

      } else {
        return false;
      }

      $response = array();
      while (true) {
        if ($start = strpos($body, '<Package ID=')) {
          $body = substr($body, $start);
          $end = strpos($body, '</Package>');
          $response[] = substr($body, 0, $end+10);
          $body = substr($body, $end+9);
        } else {
          break;
        }
      }

      $rates = array();
      if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) {
        if (sizeof($response) == '1') {
          if (preg_match('/<Error>/', $response[0])) {
            $number = preg_match('/<Number>(.*)</Number>/', $response[0], $regs);
            $number = $regs[1];
            $description = preg_match('/<Description>(.*)</Description>/', $response[0], $regs);
            $description = $regs[1];

            return array('error' => $number . ' - ' . $description);
          }
        }

        $n = sizeof($response);
        for ($i=0; $i<$n; $i++) {
          if (strpos($response[$i], '<Postage>')) {
            $service = preg_match('/<Service>(.*)</Service>/', $response[$i], $regs);
            $service = $regs[1];
            $postage = preg_match('/<Postage>(.*)</Postage>/', $response[$i], $regs);
            $postage = $regs[1];

            $rates[] = array($service => $postage);

            if ($transit) {
              switch ($service) {
                case 'EXPRESS':     $time = preg_match('/<MonFriCommitment>(.*)</MonFriCommitment>/', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = '1 - 2 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } else {
                                      $time = 'Tomorrow by ' . $time;
                                    }
                                    break;
                case 'PRIORITY':    $time = preg_match('/<Days>(.*)</Days>/', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = '2 - 3 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
                case 'PARCEL':      $time = preg_match('/<Days>(.*)</Days>/', $transresp[$service], $tregs);
                                    $time = $tregs[1];
                                    if ($time == '' || $time == 'No Data') {
                                      $time = '4 - 7 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    } elseif ($time == '1') {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                                    } else {
                                      $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    }
                                    break;
                case 'First Class': $time = '2 - 5 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                                    break;
                default:            $time = '';
                                    break;
              }
              if ($time != '') $transittime[$service] = ' (' . $time . ')';
            }
          }
        }
      } else {
        if (preg_match('/<Error>/', $response[0])) {
          $number = preg_match('/<Number>(.*)</Number>/', $response[0], $regs);
          $number = $regs[1];
          $description = preg_match('/<Description>(.*)</Description>/', $response[0], $regs);
          $description = $regs[1];

          return array('error' => $number . ' - ' . $description);
        } else {
          $body = $response[0];
          $services = array();
          while (true) {
            if ($start = strpos($body, '<Service ID=')) {
              $body = substr($body, $start);
              $end = strpos($body, '</Service>');
              $services[] = substr($body, 0, $end+10);
              $body = substr($body, $end+9);
            } else {
              break;
            }
          }
          $allowed_types = array();
          foreach( explode(", ", MODULE_SHIPPING_USPS_TYPES_INTL) as $value ) $allowed_types[$value] = $this->intl_types[$value];

          $size = sizeof($services);
          for ($i=0, $n=$size; $i<$n; $i++) {
            if (strpos($services[$i], '<Postage>')) {
              $service = preg_match('/<SvcDescription>(.*)</SvcDescription>/', $services[$i], $regs);
              $service = $regs[1];
              $postage = preg_match('/<Postage>(.*)</Postage>/', $services[$i], $regs);
              $postage = $regs[1];
              $time = preg_match('/<SvcCommitments>(.*)</SvcCommitments>/', $services[$i], $tregs);
              $time = $tregs[1];
              $time = preg_replace('/Weeks$/', MODULE_SHIPPING_USPS_TEXT_WEEKS, $time);
              $time = preg_replace('/Days$/', MODULE_SHIPPING_USPS_TEXT_DAYS, $time);
              $time = preg_replace('/Day$/', MODULE_SHIPPING_USPS_TEXT_DAY, $time);

              if( !in_array($service, $allowed_types) ) continue;
              if (isset($this->service) && ($service != $this->service) ) {
                continue;
              }

              $rates[] = array($service => $postage);
          if ($time != '') $transittime[$service] = ' (' . $time . ')';
            }
          }
        }
      }

      return ((sizeof($rates) > 0) ? $rates : false);
    }

    function country_list() {
      $list = array('AF' => 'Afghanistan',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia-Herzegovina',
                    'BW' => 'Botswana',
                    'BR' => 'Brazil',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'MM' => 'Burma',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island (Australia)',
                    'CC' => 'Cocos Island (Australia)',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo (Brazzaville),Republic of the',
                    'ZR' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands (New Zealand)',
                    'CR' => 'Costa Rica',
                    'CI' => 'Cote d\'Ivoire (Ivory Coast)',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'TP' => 'East Timor (Indonesia)',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia, Republic of',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GB' => 'Great Britain and Northern Ireland',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GT' => 'Guatemala',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia, Republic of',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte (France)',
                    'MX' => 'Mexico',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco (France)',
                    'MN' => 'Mongolia',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn Island',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russia',
                    'RW' => 'Rwanda',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts (St. Christopher and Nevis)',
                    'LC' => 'Saint Lucia',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'YU' => 'Serbia-Montenegro',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovak Republic',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia (Falkland Islands)',
                    'KR' => 'South Korea (Korea, Republic of)',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau (Union) Group (Western Samoa)',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican City',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna Islands',
                    'WS' => 'Western Samoa',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe');

      return $list;
    }
  }
?>