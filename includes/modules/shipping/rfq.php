<?php
/*
  $Id: rfq.php,v 1.3 2006/02/13 14:29:56 naegle Exp $
  Based upon flat.php / spu.php by M. Halvorsen (http://www.arachnia-web.com)


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
   CHANGES: NOV-20-05
   - formatted to work with latest checkout procedure
   - updated the db queries
   CHANGES: NOV-21-05
   - included icon
   - fixed table configuration
   CHANGES: FEB-13-06
    - Added Disable Zone
  
*/

  class rfq {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function rfq() {
	  global $order;
      $this->code = 'rfq';
      $this->title = MODULE_SHIPPING_RFQ_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_RFQ_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_RFQ_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_rfq.gif';
      $this->enabled = ((MODULE_SHIPPING_RFQ_STATUS == 'True') ? true : false);
	  if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_RFQ_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id <> '" . MODULE_SHIPPING_RQF_ZONE . "' and zone_country_id <> '" . $order->delivery['country']['id'] . "' order by zone_id");
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
	  global $order;
	  
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_RFQ_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_RFQ_TEXT_WAY,
                                                     'cost' =>  MODULE_SHIPPING_RFQ_COST)));

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_RFQ_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shipping Quotes', 'MODULE_SHIPPING_RFQ_STATUS', 'True', 'Do you want to offer shipping quotes?', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Quote Fee', 'MODULE_SHIPPING_RFQ_COST', '0.00', 'What is the Quote Fee?', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_RFQ_ZONE', '0', 'If a zone is selected, DISABLE this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_RFQ_SORT_ORDER', '6', 'Sort order of display.', '6', '6', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_RFQ_STATUS', 'MODULE_SHIPPING_RFQ_COST', 'MODULE_SHIPPING_RFQ_SORT_ORDER', 'MODULE_SHIPPING_RFQ_ZONE');
    }
  }
?>
