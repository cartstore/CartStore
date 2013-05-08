<?php
  class item3 {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function item3() {
      global $order;

      $this->code = 'item3';
      $this->title = MODULE_SHIPPING_ITEM3_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_ITEM3_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_ITEM3_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_ITEM3_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_ITEM3_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_ITEM3_ZONE > 0) ) {
        $check_flag = false;
				$zoneQ=tep_db_query("select entry_country_id from address_book where address_book_id='".$sendto."'");
			  $zoneRow=tep_db_fetch_array($zoneQ);	
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_ITEM3_ZONE . "' and zone_country_id = '" . $zoneRow['entry_country_id'] . "' order by zone_id");
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
    }

// class methods
    function quote($method = '') {
      global $order, $total_count;


      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_ITEM3_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_ITEM3_TEXT_WAY,
																										 'cost' => (MODULE_SHIPPING_ITEM3_COST * $total_count) + MODULE_SHIPPING_ITEM3_HANDLING)));
																										 //'cost' => MODULE_SHIPPING_ITEM3_COST + MODULE_SHIPPING_ITEM3_HANDLING)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ITEM3_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Item Shipping', 'MODULE_SHIPPING_ITEM3_STATUS', 'True', 'Do you want to offer per item3 rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Item Shipping Shipping Cost', 'MODULE_SHIPPING_ITEM3_COST', '2.50', 'The shipping cost will be multiplied by the number of item3s in an order that uses this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Item Shipping Handling Fee', 'MODULE_SHIPPING_ITEM3_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Item Shipping Tax Class', 'MODULE_SHIPPING_ITEM3_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Item Shipping Shipping Zone', 'MODULE_SHIPPING_ITEM3_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Item Shipping Sort Order', 'MODULE_SHIPPING_ITEM3_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Per Item Title Text', 'MODULE_SHIPPING_ITEM3_TEXT_TITLE', 'Per Item 3', 'The text used as the title of this module', '7', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_ITEM3_STATUS', 'MODULE_SHIPPING_ITEM3_TAX_CLASS', 'MODULE_SHIPPING_ITEM3_SORT_ORDER', 'MODULE_SHIPPING_ITEM3_ZONE', 'MODULE_SHIPPING_ITEM3_TEXT_TITLE', 'MODULE_SHIPPING_ITEM3_COST', 'MODULE_SHIPPING_ITEM3_HANDLING');
    }
  }				
?>