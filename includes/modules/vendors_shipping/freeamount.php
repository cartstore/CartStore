<?php
/*
  $Id$ freeamount.php 2
  $Loc: catalog/includes/modules/vendors_shipping/ $
  $Mod: MVS V1.2.1 2009/05/18 kymation $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  Released under the GNU General Public License

  ----------------------------------------------
  ane - 06/02/02 - modified freecount.php to
  allow for freeshipping on minimum order amount
  originally written by dwatkins 1/24/02
  Modified BearHappy 09/04/04
  ----------------------------------------------
*/

  class freeamount {
    var $code, $title, $description, $icon, $enabled;

    // class constructor
    function freeamount() {
      global $order, $vendors_id;

      //$this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];

      $this->code = 'freeamount';
      $this->title = MODULE_SHIPPING_FREEAMOUNT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FREEAMOUNT_TEXT_DESCRIPTION;
      // $this->sort_order = MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER;
      $this->icon = '';
      $this->delivery_country_id = $order->delivery['country']['id'];
      $this->delivery_zone_id = $order->delivery['zone_id'];
      //  $this->enabled = ((MODULE_SHIPPING_FREEAMOUNT_STATUS == 'True') ? true : false);
    }

    function sort_order($vendors_id = '1') {
      $sort_order = @ constant('MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER_' . $vendors_id);
      if (isset ($sort_order)) {
        $this->sort_order = $sort_order;
      } else {
        $this->sort_order = '-';
      }
      return $this->sort_order;
    }

    function tax_class($vendors_id = '1') {
      $this->tax_class = constant('MODULE_SHIPPING_FREEAMOUNT_TAX_CLASS_' . $vendors_id);
      return $this->tax_class;
    }

    function enabled($vendors_id = '1') {
      $this->enabled = false;
      $status = @ constant('MODULE_SHIPPING_FREEAMOUNT_STATUS_' . $vendors_id);
      if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if (($this->enabled == true) && ((int) constant('MODULE_SHIPPING_FREEAMOUNT_ZONE_' . $vendors_id) > 0)) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int) constant('MODULE_SHIPPING_FREEAMOUNT_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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
        } //if
      } //if

      return $this->enabled;
    } //function
    // end MVS
    
    function zones($vendors_id = '1') {
      if (($this->enabled == true) && ((int) constant('MODULE_SHIPPING_FREEAMOUNT_ZONE_' . $vendors_id) > 0)) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int) constant('MODULE_SHIPPING_FREEAMOUNT_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } /* $check */
          elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          } //elseif
        } //while

        if ($check_flag == false) {
          $this->enabled = false;
        } //if  flag
      } //if 1
      return $this->enabled;
    } //function
    //MVS End
    
    // class methods
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $order, $cart, $total_count, $shipping_weight, $shipping_num_boxes;

      $vendors_data_query = tep_db_query("select handling_charge,
                                                 handling_per_box,
                                                 vendor_country,
                                                 vendors_zipcode
                                         from " . TABLE_VENDORS . "
                                         where vendors_id = '" . (int) $vendors_id . "'");
      $vendors_data = tep_db_fetch_array($vendors_data_query);
      $country_name = tep_get_countries($vendors_data['vendor_country'], true);

      $handling_charge = $vendors_data['handling_charge'];
      $handling_per_box = $vendors_data['handling_per_box'];
      if ($handling_charge > $handling_per_box * $shipping_num_boxes) {
        $handling = $handling_charge;
      } else {
        $handling = $handling_per_box * $shipping_num_boxes;
      }
      $shipping = @ constant('MODULE_SHIPPING_FREEAMOUNT_COST_' . $vendors_id) + $handling +0.0001;
      /*
      @constant('MODULE_SHIPPING_FREEAMOUNT_DISPLAY_' . $vendors_id)
      $dest_country = $order->delivery['country']['id'];
      $currency = $order->info['currency'];
      */
      if ($shipping_weight > @ constant('MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX_' . $vendors_id))
        $this->quotes['error'] = MODULE_SHIPPING_FREEAMOUNT_TEXT_TO_HEIGHT . ' (' . $shipping_weight . ') ' . MODULE_SHIPPING_FREEAMOUNT_TEXT_UNIT;
//      if ($cart->show_total() < @ constant('MODULE_SHIPPING_FREEAMOUNT_AMOUNT_' . $vendors_id)) {
      if ($cart->vendor_shipping[$vendors_id]['cost'] < @ constant('MODULE_SHIPPING_FREEAMOUNT_AMOUNT_' . $vendors_id)) {
        //   if (@constant('MODULE_SHIPPING_FREEAMOUNT_DISPLAY_' . $vendors_id) == 'False') return;
        //   else $this->quotes['error'] = MODULE_SHIPPING_FREEAMOUNT_TEXT_ERROR;
      } else {

        $this->quotes = array (
          'id' => $this->code,
          'module' => MODULE_SHIPPING_FREEAMOUNT_TEXT_TITLE,
          'methods' => array (
            array (
              'id' => $this->code,
              'title' => MODULE_SHIPPING_FREEAMOUNT_TEXT_WAY,
              'cost' => $shipping
            )
          )
        );
      }
      if ($this->tax_class($vendors_id) > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon))
        $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check($vendors_id = '1') {
      if (!isset ($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREEAMOUNT_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install($vendors_id) {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Free Shipping with Minimum Purchase', 'MODULE_SHIPPING_FREEAMOUNT_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer minimum order free shipping?', '6', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id,  sort_order, date_added, vendors_id) values ('Maximum Weight', 'MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX_" . $vendors_id . "', '10', 'What is the maximum weight you will ship?', '6', '8', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Display', 'MODULE_SHIPPING_FREEAMOUNT_DISPLAY_" . $vendors_id . "', 'True', 'Do you want to display text way if the minimum amount is not reached?', '6', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id,  sort_order, date_added, vendors_id) values ('Minimum Cost', 'MODULE_SHIPPING_FREEAMOUNT_AMOUNT_" . $vendors_id . "', '50.00', 'Minimum order amount purchased before shipping is free?', '6', '8', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_FREEAMOUNT_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_FREEAMOUNT_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    function keys($vendors_id) {
      return array (
        'MODULE_SHIPPING_FREEAMOUNT_STATUS_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_SORT_ORDER_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_DISPLAY_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_TAX_CLASS_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_AMOUNT_' . $vendors_id,
        'MODULE_SHIPPING_FREEAMOUNT_ZONE_' . $vendors_id
      );
    }
  }

?>