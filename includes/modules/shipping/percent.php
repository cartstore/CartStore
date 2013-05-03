<?php
/*
adjusted to suit dpbuk business package
*/
class percent {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function percent() {
      global $order, $sendto;

      $this->code = 'percent';
      $this->title = MODULE_SHIPPING_PERCENT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_PERCENT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_PERCENT_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_PERCENT_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_PERCENT_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_PERCENT_ZONE > 0) ) {
        $check_flag = false;
				$zoneQ=tep_db_query("select entry_country_id from address_book where address_book_id='".$sendto."'");
			  $zoneRow=tep_db_fetch_array($zoneQ);				
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_PERCENT_ZONE . "' and zone_country_id = '" . $zoneRow['entry_country_id'] . "' order by zone_id");
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
      global $order, $cart;
	  
	  if (MODULE_SHIPPING_PERCENT_STATUS == 'True') {
        $order_total = $cart->show_total();
      }
	  if ($order_total >= MODULE_SHIPPING_PERCENT_LESS_THEN) {
      $shipping_percent = $order_total * MODULE_SHIPPING_PERCENT_RATE;
	  }
	  else {
	  $shipping_percent = MODULE_SHIPPING_PERCENT_FLAT_USE;
	  }
	  
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_PERCENT_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_PERCENT_TEXT_WAY,
                                                     'cost' => $shipping_percent)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_PERCENT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Percent Shipping', 'MODULE_SHIPPING_PERCENT_STATUS', 'True', 'Do you want to offer percent rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage Rate', 'MODULE_SHIPPING_PERCENT_RATE', '.18', 'The Percentage Rate all .01 to .99 for all orders using this shipping method.', '6', '0', now())");
	  	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage A Flat Rate for orders under', 'MODULE_SHIPPING_PERCENT_LESS_THEN', '34.75', 'A Flat Rate for all orders that are under the amount shown.', '6', '0', now())");
	  	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage A Flat Rate of', 'MODULE_SHIPPING_PERCENT_FLAT_USE', '6.50', 'A Flat Rate used for all orders.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Percentage Tax Class', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Percentage Shipping Zone', 'MODULE_SHIPPING_PERCENT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage Sort Order', 'MODULE_SHIPPING_PERCENT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())"); 
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage Title Text', 'MODULE_SHIPPING_PERCENT_TEXT_TITLE', 'Percentage of total', 'The text used as the title of this module', '7', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_PERCENT_STATUS', 'MODULE_SHIPPING_PERCENT_RATE', 'MODULE_SHIPPING_PERCENT_LESS_THEN', 'MODULE_SHIPPING_PERCENT_FLAT_USE', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', 'MODULE_SHIPPING_PERCENT_ZONE', 'MODULE_SHIPPING_PERCENT_SORT_ORDER','MODULE_SHIPPING_PERCENT_TEXT_TITLE');
    }
  }
?>
