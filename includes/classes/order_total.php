<?php
/*
  $Id: order_total.php,v 1.4 2003/02/11 00:04:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class order_total {
    var $modules;

// class constructor
    function order_total() {
      global $language;

      if (defined('MODULE_ORDER_TOTAL_INSTALLED') && tep_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
        $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          include(DIR_WS_LANGUAGES . $language . '/modules/order_total/' . $value);
          include(DIR_WS_MODULES . 'order_total/' . $value);

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

    function process() {
      $order_total_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->output = array();
            $GLOBALS[$class]->process();

            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }

      return $order_total_array;
    }

    function output() {
      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $size = sizeof($GLOBALS[$class]->output);
            for ($i=0; $i<$size; $i++) {
              $output_string .= '              <tr>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                '              </tr>';
            }
          }
        }
			}
		return $output_string;
		}
	
/* CCGV - BEGIN */
	function credit_selection()
		{
		$selection_string = '';
		$credit_class_string = '';
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			$header_string = 	'<h2>' . TABLE_HEADING_CREDIT . '</h2><div style="width:100%; margin:auto;"><span><font size="1pt">&nbsp;</font></span></div>';
			reset($this->modules);
			$output_string = '';
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				if ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)
					{
					if ($selection_string == '') $selection_string = $GLOBALS[$class]->credit_selection();
					if ($use_credit_string != '' || $selection_string != '')
						{
						$output_string = $selection_string;
						}
					}
				}
			if ($output_string != '')
				{
				$output_string = $header_string . $output_string;
				}
			}
		return $output_string;
		}
	
	function sub_credit_selection(){
		//$selection_string = '';
		$close_string = '';
		$credit_class_string = '';
		if (MODULE_ORDER_TOTAL_INSTALLED){
			reset($this->modules);
			$output_string = '';
			while (list(, $value) = each($this->modules)){
				$class = substr($value, 0, strrpos($value, '.'));
				
				if ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class){
					
					$use_credit_string = $GLOBALS[$class]->use_credit_amount();
					
					//if ($selection_string == '') $selection_string = $GLOBALS[$class]->credit_selection();
					if ($use_credit_string != ''){ // || $selection_string != ''){
						return $use_credit_string;
					}
				}
			}
		}
		return $output_string;
	}
	
	function update_credit_account($i, $order_id=0)
		{
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			reset($this->modules);
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) )
					{
					$GLOBALS[$class]->update_credit_account($i, $order_id);
					}
				}
			}
		}
	
	function collect_posts()
		{
		global $_POST,$_SESSION;
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			reset($this->modules);
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) )
					{
					$post_var = 'c' . $GLOBALS[$class]->code;
					if ($_POST[$post_var])
						{
						if (!tep_session_is_registered($post_var)) tep_session_register($post_var);
						 $_SESSION[$post_var] = $_POST[$post_var];
						}
					$GLOBALS[$class]->collect_posts();
					}
				}
			}
		}
	
	function pre_confirmation_check()
		{
		global $payment, $order, $credit_covers, $customer_id;
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			$total_deductions  = 0;
			reset($this->modules);
			$order_total = $order->info['total'];
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				$order_total = $this->get_order_total_main($class,$order_total);
				if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) )
					{
					$total_deductions = $total_deductions + $GLOBALS[$class]->pre_confirmation_check($order_total);
					$order_total = $order_total - $GLOBALS[$class]->pre_confirmation_check($order_total);
					}
				}
			$gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
			$gv_result=tep_db_fetch_array($gv_query);
			$gv_payment_amount = $gv_result['amount'];
			if ($order->info['total'] - $gv_payment_amount <= 0 )
				{
				if (tep_session_is_registered('cot_gv'))
					{
					if(!tep_session_is_registered('credit_covers')) tep_session_register('credit_covers');
					$credit_covers = true;
					}
				}
			else
				{
				if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
				}
			}
		}
	
	function apply_credit()
		{
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			reset($this->modules);
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) )
					{
					$GLOBALS[$class]->apply_credit();
					}
				}
			}
		}
	
	function clear_posts()
		{
		global $_POST,$_SESSION;
		if (MODULE_ORDER_TOTAL_INSTALLED)
			{
			reset($this->modules);
			while (list(, $value) = each($this->modules))
				{
				$class = substr($value, 0, strrpos($value, '.'));
				if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) )
					{
					$post_var = 'c' . $GLOBALS[$class]->code;
					if (tep_session_is_registered($post_var)) tep_session_unregister($post_var);
					}
				}
			}
		}
	
	function get_order_total_main($class, $order_total)
		{
		global $credit, $order;
		return $order_total;
		}
/* CCGV - END */
	}
?>
