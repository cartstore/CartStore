<?php
/*
  $Id: ot_redemptions.php,2.00 2006/JULY/06 15:55:30 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class ot_redemptions {
    var $title, $output;

    function ot_redemptions() {
      $this->code = 'ot_redemptions';
      $this->title = MODULE_ORDER_TOTAL_REDEMPTIONS_TITLE;
      $this->description = MODULE_ORDER_TOTAL_REDEMPTIONS_DESCRIPTION;
      if($this->check())
        $this->enabled = ((USE_REDEEM_SYSTEM == 'true') ? true : false);
      else
        $this->enabled = false;
      $this->sort_order = MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $customer_shopping_points_spending;
      
// if customer is using points to pay   
      if ($customer_shopping_points_spending > 0){
	      
        $order->info['total'] = $order->info['total'] - (tep_calc_shopping_pvalue($customer_shopping_points_spending));
	      
        $this->output[] = array('title' =>''. MODULE_ORDER_TOTAL_REDEMPTIONS_TEXT . ':',
                                'text' => '<font color="FF0000">-'.$currencies->format(tep_calc_shopping_pvalue($customer_shopping_points_spending), true, $order->info['currency'], $order->info['currency_value'].'</font>'),
                                'value' => tep_calc_shopping_pvalue($customer_shopping_points_spending));
	        
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER');
    }

    function install() {
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>