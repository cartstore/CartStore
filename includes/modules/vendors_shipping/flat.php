<?php
/*
  $Id: flat.php,v 1.40 2003/02/05 22:41:52 hpdl Exp $
  Modified for MVS V1.1 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class flat {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function flat() {
      global $order, $vendors_id;

//MVS
      $this->code = 'flat';
      $this->title = MODULE_SHIPPING_FLAT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FLAT_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->delivery_country_id = $order->delivery['country']['id'];
      $this->delivery_zone_id = $order->delivery['zone_id'];
    }
//MVS start
       function sort_order($vendors_id='1') {
      if (defined (@constant ('MODULE_SHIPPING_FLAT_SORT_ORDER_' . $vendors_id))) {
        $this->sort_order = @constant('MODULE_SHIPPING_FLAT_SORT_ORDER_' . $vendors_id);
      } else {
        $this->sort_order = '-';
      }
                        return $this->sort_order;
    }

                function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_FLAT_TAX_CLASS_' . $vendors_id);
                        return $this->tax_class;
    }

               function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_FLAT_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FLAT_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FLAT_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FLAT_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FLAT_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

//Get a shippoing quote
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $_POST, $order, $total_count, $shipping_weight, $shipping_num_boxes;

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

      $handling_charge = $vendors_data['handling_charge'];
      $handling_per_box = $vendors_data['handling_per_box'];
      if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
        $handling = $handling_charge;
      } else {
        $handling = $handling_per_box*$shipping_num_boxes;
      }
      $shipping = @constant('MODULE_SHIPPING_FLAT_COST_' . $vendors_id) + $handling;
//MVS End

//MVS - changed 'cost' => $shipping
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_FLAT_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_FLAT_TEXT_WAY,
                                                     'cost' => $shipping)));

 // $this->tax_class = constant(MODULE_SHIPPING_FLAT_TAX_CLASS_ . $vendors_id);

      if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check($vendors_id = '1') {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FLAT_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install($vendors_id = '1') {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer flat rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Shipping Cost', 'MODULE_SHIPPING_FLAT_COST_" . $vendors_id . "', '5.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_FLAT_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_FLAT_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_FLAT_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '0', now(), '" . $vendors_id . "')");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
      }

    function keys($vendors_id) {
      return array('MODULE_SHIPPING_FLAT_STATUS_' . $vendors_id, 'MODULE_SHIPPING_FLAT_COST_' . $vendors_id, 'MODULE_SHIPPING_FLAT_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_FLAT_ZONE_' . $vendors_id, 'MODULE_SHIPPING_FLAT_SORT_ORDER_' . $vendors_id);
    }
  }
?>