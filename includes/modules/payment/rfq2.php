<?
/*
  $Id: rfq2.php,v 1.0 2005/06/06 20:12:34 DCN Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

Request for Quote contribution for osCommerce 2.1
  This version was contributed by Dan Naegle (dan_naegle@yahoo.com)

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  class rfq2 {
    var $code, $title, $description, $enabled;

// class constructor
    function rfq2() {
      global $order;

      $this->code = 'rfq2';
      $this->title = MODULE_PAYMENT_RFQ2_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_RFQ2_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_RFQ2_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_RFQ2_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_RFQ2_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_RFQ2_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    
      $this->email_footer = MODULE_PAYMENT_RFQ2_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_RFQ2_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_RFQ2_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

// disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_RFQ2_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Quote Order Module', 'MODULE_PAYMENT_RFQ2_STATUS', 'True', 'Do you want to accept Requests for Quotes?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Request for Quote Zone', 'MODULE_PAYMENT_RFQ2_ZONE', '0', 'If a zone is selected, only enable this request module for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('For more info contact:', 'MODULE_PAYMENT_RFQ2_PAYTO', '', 'Who is the contact for quotations?', '6', '1', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_RFQ2_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_RFQ2_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_RFQ2_STATUS', 'MODULE_PAYMENT_RFQ2_ZONE', 'MODULE_PAYMENT_RFQ2_PAYTO', 'MODULE_PAYMENT_RFQ2_ORDER_STATUS_ID', 'MODULE_PAYMENT_RFQ2_SORT_ORDER');
    }
  }
?>