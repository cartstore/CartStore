<?php
/*

  $Id: firstitemplus.php,v 1.0x 2004/02/06
                by MF, modeled after:

  $Id: table.php,v 1.26x 2003/01/31 $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  * Modifications by Christian Lescuyer <osc@goelette.net>
  * Copyright (c) 2003 Goélette http://www.goelette.net

  GNU General Public License Compatible
*/

  class firstitemplus {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function firstitemplus() {
      global $order;
      $this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
      $this->code = 'firstitemplus';
      $this->title = MODULE_SHIPPING_FIRSTITEMPLUS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FIRSTITEMPLUS_TEXT_DESCRIPTION;
     // $this->sort_order = MODULE_SHIPPING_FIRSTITEMPLUS_SORT_ORDER;
      $this->icon = '';
     // $this->enabled = ((MODULE_SHIPPING_FIRSTITEMPLUS_STATUS == 'True') ? true : false);

      if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
     }
  /* MVS
      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_FIRSTITEMPLUS_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_FIRSTITEMPLUS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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
      */

  //MVS start
              function sort_order($vendors_id='1') {
     $sort_order = @constant ('MODULE_SHIPPING_FIRSTITEMPLUS_SORT_ORDER_' . $vendors_id);
     if (isset ($sort_order)) {
       $this->sort_order = $sort_order;
     } else {
       $this->sort_order = '-';
     }
     return $this->sort_order;
   }

                function tax_class($vendors_id='1') {
      $this->tax_class = @constant('MODULE_SHIPPING_FIRSTITEMPLUS_TAX_CLASS_' . $vendors_id);
                        return $this->tax_class;
    }

 //MVS
                function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_FIRSTITEMPLUS_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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


                   function zones($vendors_id='1') {
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

    //MVS END
// class methods
      //MVS
      function quote($method = '', $module = '', $vendors_id = '1') {
      global $_POST, $shipping_weight, $order, $cart, $shipping_num_boxes;


      //MVS Start
      $vendors_data_query = tep_db_query("select handling_charge,
                                                 handling_per_box
                                          from " . TABLE_VENDORS . "
                                          where vendors_id = '" . (int)$vendors_id . "'"
                                        );
      $vendors_data = tep_db_fetch_array($vendors_data_query);

      $handling_charge = $vendors_data['handling_charge'];
      $handling_per_box = $vendors_data['handling_per_box'];
      if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
        $handling = $handling_charge;
      } else {
        $handling = $handling_per_box*$shipping_num_boxes;
      }

     // $shipping = @constant('MODULE_SHIPPING_FIRSTITEMPLUS_COST_' . $vendors_id) + $handling;
//MVS End


      $order_total = $cart->vendor_shipping[$vendors_id]['qty'];
    //  echo 'this is the count ' . $cart->vendor_shipping[$vendors_id]['qty'];

      $table_cost_first = @constant('MODULE_SHIPPING_FIRSTITEMPLUS_FIRST_' . $vendors_id);
      $table_cost_additional = @constant('MODULE_SHIPPING_FIRSTITEMPLUS_ADDITIONAL_' . $vendors_id);

                  $shipping = ($order_total < 2) ?  $table_cost_first : ($table_cost_first + (($order_total - 1) * $table_cost_additional) + $handling) ;
       //  echo 'This is the order_total' .$table_cost_first;
                  //MVS changed 'cost' => $shipping + MODULE_SHIPPING_FIRSTITEMPLUS_HANDLING
      $this->quotes = array('id' => $this->code,
                            'module' => $this->title,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => sprintf(MODULE_SHIPPING_FIRSTITEMPLUS_TEXT_WAY,$table_cost_first,$table_cost_additional),
                                                     'cost' => $shipping)));

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }
  /* MVS
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FIRSTITEMPLUS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
   */

         function check($vendors_id = '1') {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FIRSTITEMPLUS_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install($vendors_id) {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) VALUES ('Enable First Item Plus Additional Method', 'MODULE_SHIPPING_FIRSTITEMPLUS_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer \"First item $X, additional items $X\" shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Shipping Cost for First Item', 'MODULE_SHIPPING_FIRSTITEMPLUS_FIRST_" . $vendors_id . "', '6.00', 'The shipping cost is calculated using a cost for the the first item, then another cost for each additional item. Enter the cost of the first item (without dollar sign):', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Shipping Cost for Additional Items', 'MODULE_SHIPPING_FIRSTITEMPLUS_ADDITIONAL_" . $vendors_id . "', '1.00', 'Cost per additional item (without dollar sign):', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Handling Fee', 'MODULE_SHIPPING_FIRSTITEMPLUS_HANDLING_" . $vendors_id . "', '0', 'Additional handling fee for this shipping method.', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort order of display.', 'MODULE_SHIPPING_FIRSTITEMPLUS_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now(), '" . $vendors_id . "')");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    function keys($vendors_id) {
      return array('MODULE_SHIPPING_FIRSTITEMPLUS_STATUS_' . $vendors_id, 'MODULE_SHIPPING_FIRSTITEMPLUS_FIRST_' . $vendors_id, 'MODULE_SHIPPING_FIRSTITEMPLUS_ADDITIONAL_' . $vendors_id, 'MODULE_SHIPPING_FIRSTITEMPLUS_HANDLING_' . $vendors_id, 'MODULE_SHIPPING_FIRSTITEMPLUS_ZONE_' . $vendors_id, 'MODULE_SHIPPING_FIRSTITEMPLUS_SORT_ORDER_' . $vendors_id);
    }
  }
?>