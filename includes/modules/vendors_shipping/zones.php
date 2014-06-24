<?php
/*
  $Id: zones.php,v 1.20 2003/06/15 19:48:09 thomasamoulton Exp $
  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  USAGE
  By default, the module comes with support for 1 zone.  This can be
  easily changed by changing the value for Zones in the Vendors admin panel.

  Next, you will want to activate the module by going to the Admin screen,
  clicking on Modules, then clicking on Shipping.  A list of all shipping
  modules should appear.  Click on the green dot next to the one labeled
  zones.php.  A list of settings will appear to the right.  Click on the
  Edit button.

  PLEASE NOTE THAT YOU WILL LOSE YOUR CURRENT SHIPPING RATES AND OTHER
  SETTINGS IF YOU TURN OFF THIS SHIPPING METHOD.  Make sure you keep a
  backup of your shipping settings somewhere at all times.

  If you want an additional handling charge applied to orders that use this
  method, set the Handling Fee field.

  Next, you will need to define which countries are in each zone.  Determining
  this might take some time and effort.  You should group a set of countries
  that has similar shipping charges for the same weight.  For instance, when
  shipping from the US, the countries of Japan, Australia, New Zealand, and
  Singapore have similar shipping rates.  As an example, one of my customers
  is using this set of zones:
    1: USA
    2: Canada
    3: Austria, Belgium, Great Britain, France, Germany, Greenland, Iceland,
       Ireland, Italy, Norway, Holland/Netherlands, Denmark, Poland, Spain,
       Sweden, Switzerland, Finland, Portugal, Israel, Greece
    4: Japan, Australia, New Zealand, Singapore
    5: Taiwan, China, Hong Kong

  When you enter these country lists, enter them into the Zone X Countries
  fields, where "X" is the number of the zone.  They should be entered as
  two character ISO country codes in all capital letters.  They should be
  separated by commas with no spaces or other punctuation. For example:
    1: US
    2: CA
    3: AT,BE,GB,FR,DE,GL,IS,IE,IT,NO,NL,DK,PL,ES,SE,CH,FI,PT,IL,GR
    4: JP,AU,NZ,SG
    5: TW,CN,HK

  Now you need to set up the shipping rate tables for each zone.  Again,
  some time and effort will go into setting the appropriate rates.  You
  will define a set of weight ranges and the shipping price for each
  range.  For instance, you might want an order than weighs more than 0
  and less than or equal to 3 to cost 5.50 to ship to a certain zone.
  This would be defined by this:  3:5.5

  You should combine a bunch of these rates together in a comma delimited
  list and enter them into the "Zone X Shipping Table" fields where "X"
  is the zone number.  For example, this might be used for Zone 1:
    1:3.5,2:3.95,3:5.2,4:6.45,5:7.7,6:10.4,7:11.85, 8:13.3,9:14.75,10:16.2,11:17.65,
    12:19.1,13:20.55,14:22,15:23.45

  The above example includes weights over 0 and up to 15.  Note that
  units are not specified in this explanation since they should be
  specific to your locale.

  CAVEATS
  At this time, it does not deal with weights that are above the highest amount
  defined.  This will probably be the next area to be improved with the
  module.  For now, you could have one last very high range with a very
  high shipping rate to discourage orders of that magnitude.  For
  instance:  999:1000

  If you want to be able to ship to any country in the world, you will
  need to enter every country code into the Country fields. For most
  shops, you will not want to enter every country.  This is often
  because of too much fraud from certain places. If a country is not
  listed, then the module will add a $0.00 shipping charge and will
  indicate that shipping is not available to that destination.
  PLEASE NOTE THAT THE ORDER CAN STILL BE COMPLETED AND PROCESSED!

  It appears that the osC shipping system automatically rounds the
  shipping weight up to the nearest whole unit.  This makes it more
  difficult to design precise shipping tables.  If you want to, you
  can hack the shipping.php file to get rid of the rounding.

  Lastly, there is a limit of 255 characters on each of the Zone
  Shipping Tables and Zone Countries.

*/

  class zones {
    var $code, $title, $description, $enabled, $num_zones, $vendors_id; //multi vendor

// class constructor
    function zones() {
//MVS
      $this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
      $this->code = 'zones';
      $this->title = MODULE_SHIPPING_ZONES_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION;
      $this->icon = '';
    }

//MVS Start
                function sort_order($vendors_id='1') {
     $sort_order = @constant ('MODULE_SHIPPING_ZONES_SORT_ORDER_' . $vendors_id);
       if (isset ($sort_order)) {
       $this->sort_order = $sort_order;
      } else {
        $this->sort_order = '-';
      }
                        return $this->sort_order;
    }

                function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_ZONES_TAX_CLASS_' . $vendors_id);
                        return $this->tax_class;
    }

                function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_ZONES_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      return $this->enabled;
    }

//Set the number of zones used for this vendor
                function num_zones($vendors_id='1') {
      $vendors_data_query = tep_db_query("select zones
                                          from " . TABLE_VENDORS . "
                                          where vendors_id = '" . (int)$vendors_id . "'"
                                        );
      $vendors_data = tep_db_fetch_array($vendors_data_query);
      $this->num_zones = $vendors_data['zones'];
                        return $this->num_zones;
    }
//MVS End

//Get a quote
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $_POST, $shipping_weight, $order, $cart, $shipping_num_boxes;
//MVS Start
//return an error if the module is not enabled for this vendor
      if ($this->enabled($vendors_id) < 1) {
        $this->quotes['error'] = MODULE_SHIPPING_ZONES_INVALID_ZONE;
        return $this->quotes;
      }
//MVS End

      $dest_country = $order->delivery['country']['iso_code_2'];
      $dest_zone = 0;
      $error = false;

      for ($i=1; $i<=$this->num_zones($vendors_id); $i++) {
        $countries_table = constant('MODULE_SHIPPING_ZONES_COUNTRIES_' . $vendors_id . '_' . $i);
        $country_zones = preg_split("/[,]/", $countries_table);
        if (in_array($dest_country, $country_zones)) {
          $dest_zone = $i;
          break;
        }
      }

      if ($dest_zone == 0) {
        $error = true;
      } else {
        $shipping = -1;
        $zones_cost = constant('MODULE_SHIPPING_ZONES_COST_' . $vendors_id . '_' . $dest_zone);

        $zones_table = preg_split("/[:,]/" , $zones_cost);
        $size = sizeof($zones_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $zones_table[$i]) {
            $shipping = $zones_table[$i+1];
            $shipping_method = MODULE_SHIPPING_ZONES_TEXT_WAY . ' ' . $dest_country . ' : ' . $shipping_weight . ' ' . MODULE_SHIPPING_ZONES_TEXT_UNITS;
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
          $shipping_method = MODULE_SHIPPING_ZONES_UNDEFINED_RATE;
        } else {
//MVS Start
          $vendors_data_query = tep_db_query("select handling_charge,
                                                     handling_per_box
                                              from " . TABLE_VENDORS . "
                                              where vendors_id = '" . (int)$vendors_id . "'"
                                            );
          $vendors_data = tep_db_fetch_array($vendors_data_query);

          //Set handling to the handling per box times number of boxes, or handling charge if it is larger
          $handling_charge = $vendors_data['handling_charge'];
          $handling_per_box = $vendors_data['handling_per_box'];
          if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
            $handling = $handling_charge;
          } else {
            $handling = $handling_per_box*$shipping_num_boxes;
          }

          //Set handling to the module's handling charge if it is larger
          $module_handling = constant('MODULE_SHIPPING_ZONES_HANDLING_' . $vendors_id . '_' . $dest_zone);
          if ($module_handling > $handling) {
            $handling = $module_handling;
          }
          $shipping_cost = ($shipping * $shipping_num_boxes) + $handling;
//MVS End
        }
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_ZONES_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));
 // $this->tax_class = constant(MODULE_SHIPPING_ZONES_TAX_CLASS_ . $vendors_id);
      if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_ZONES_INVALID_ZONE;

      return $this->quotes;
    }

    function check($vendors_id='1') {
      if (!isset($this->_check)) {
      //multi vendor add  "vendors_id = '". $vendors_id ."' and"
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key = 'MODULE_SHIPPING_ZONES_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

//MVS start
    function install($vendors_id='1') {
            $vID = $vendors_id;
            //multi vendor add 'vendors_id' to field names and '" . $vID . "', to values
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $vendors_id . "', 'Enable Zones Method', 'MODULE_SHIPPING_ZONES_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . $vendors_id . "', 'Tax Class', 'MODULE_SHIPPING_ZONES_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Sort Order', 'MODULE_SHIPPING_ZONES_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones($vendors_id); $i++) {
        $default_countries = '';
        if ($i == 1) {
          $default_countries = 'US,CA';
        }
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Zone " . $i ." Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_" . $vendors_id . "_" . $i . "', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_ZONES_COST_" . $vendors_id . "_" . $i . "', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_" . $vendors_id . "_" . $i . "', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
      }
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    function keys($vendors_id) {
      $keys = array('MODULE_SHIPPING_ZONES_STATUS_' . $vendors_id, 'MODULE_SHIPPING_ZONES_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_ZONES_SORT_ORDER_' . $vendors_id);

      for ($i=1; $i<=$this->num_zones($vendors_id); $i++) {
        $keys[] = 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $vendors_id . '_' . $i;
        $keys[] = 'MODULE_SHIPPING_ZONES_COST_' . $vendors_id . '_' . $i;
        $keys[] = 'MODULE_SHIPPING_ZONES_HANDLING_' . $vendors_id . '_' . $i;
      }
//MVS End

      return $keys;
    }
  }
?>