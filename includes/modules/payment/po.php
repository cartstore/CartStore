<?php

/*

  $Id: po.php $

  Purchase Order Payment Module v1.3 2009-01-06

  by Assembler



  Released under the GNU General Public License

*/



  class po {

    var $code, $title, $description, $enabled;



// class constructor

    function po() {

      global $order;



      $this->code = 'po';

      $this->title = MODULE_PAYMENT_PO_TEXT_TITLE;

      $this->public_title = MODULE_PAYMENT_PO_TEXT_PUBLIC_TITLE;

      $this->description = MODULE_PAYMENT_PO_TEXT_DESCRIPTION;

      $this->sort_order = MODULE_PAYMENT_PO_SORT_ORDER;

      $this->enabled = ((MODULE_PAYMENT_PO_STATUS == 'True') ? true : false);



      if ((int)MODULE_PAYMENT_PO_ORDER_STATUS_ID > 0) {

        $this->order_status = MODULE_PAYMENT_PO_ORDER_STATUS_ID;

      }



      if (is_object($order)) $this->update_status();

    }



// class methods

    function update_status() {

      global $order;



      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PO_ZONE > 0) ) {

        $check_flag = false;

        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PO_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");

        while ($check = tep_db_fetch_array($check_query)) {

          if ($check['zone_id'] < 1) {

            $check_flag = true;

            break;

          } elseif ($check['zone_id'] == $order->billing['zone_id']) {

            $check_flag = true;

            break;

          }

        }



        if ($check_flag == false) {

          $this->enabled = false;

        }

      }

    }



    function javascript_validation() {

      return false;

    }



    function selection() {

      $selection = array('id' => $this->code,

                   'module' => $this->public_title);
                   
      $selection['fields'] = array(array('title' => MODULE_PAYMENT_PO_TEXT_PO_COMPANY,

                                        'field' => tep_draw_input_field('po_owner', $order->billing['company'])),

                                    array('title' => MODULE_PAYMENT_PO_TEXT_PO_NUMBER,

                                        'field' => tep_draw_input_field('po_number')));

      if (MODULE_PAYMENT_PO_HARDCOPY == 'True') {

        

        $po_send_methods = array(array('id' => 'Email',

                                     'text' => 'Attached to an Email'),

                               array('id' => 'Fax',

                                     'text' => 'Fax'),

                               array('id' => 'Mail',

                                     'text' => 'Regular Mail'));



        $selection['fields'][] = array('title' => MODULE_PAYMENT_PO_TEXT_HARDCOPY,

                                        'field' => tep_draw_pull_down_menu('po_hardcopy', $po_send_methods).'<br /><span class="smallText">' . MODULE_PAYMENT_PO_TEXT_HARDCOPY_MSG . '</span>');

      }

                   
    return $selection;

    }



    function pre_confirmation_check() {

      return false;

    }



    function confirmation() {

      global $order,$_POST;

      

      $confirmation = array('fields' => array(array('title' => MODULE_PAYMENT_PO_TEXT_PO_COMPANY,

                                                      'field' => $_POST['po_owner']),

                                                array('title' => MODULE_PAYMENT_PO_TEXT_PO_NUMBER,

                                                      'field' => $_POST['po_number'])));

      if (MODULE_PAYMENT_PO_HARDCOPY == 'True') {

        

        $po_send_methods = array(array('id' => 'Email',

                                     'text' => 'Attached to an Email'),

                               array('id' => 'Fax',

                                     'text' => 'Fax'),

                               array('id' => 'Mail',

                                     'text' => 'Regular Mail'));



        $confirmation['fields'][] = array('title' => MODULE_PAYMENT_PO_TEXT_HARDCOPY,

                                                     'field' => $_POST['po_hardcopy']);

      }

      return $confirmation;

    }



    function process_button() {
    	global $_POST;

       $process_button_string = tep_draw_hidden_field('po_owner', $_POST['po_owner']) .
                                tep_draw_hidden_field('po_number', $_POST['po_number']) . 
                                tep_draw_hidden_field('po_hardcopy',$_POST['po_hardcopy']);
                                
       return $process_button_string;                         

    }



    function before_process() {

      global $_POST, $order;

      

      $error = '';

      if (strlen($_POST['po_owner']) < 1) {

        $error = MODULE_PAYMENT_PO_TEXT_PO_COMPANY_ERROR;

     } elseif(strlen($_POST['po_number']) < 1){

        $error = MODULE_PAYMENT_PO_TEXT_PO_COMPANY_ERROR;

      }



      if ($error != '') {

        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error);

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));

      }





      $order->info['comments'] .= ' ' . MODULE_PAYMENT_PO_TEXT_PO_COMPANY . ' ' . tep_db_prepare_input($_POST['po_owner']) . ' ';

      $order->info['comments'] .= MODULE_PAYMENT_PO_TEXT_PO_NUMBER . ' ' . tep_db_prepare_input($_POST['po_number']) . ' ';

      if (isset($_POST['po_hardcopy'])) {

        $order->info['comments'] .= MODULE_PAYMENT_PO_TEXT_HARDCOPY . ' ' . tep_db_prepare_input($_POST['po_hardcopy']);

      }

    }



    function after_process() {

      global $insert_id;

    }



    function get_error() {

      global $_GET;



      $error = array('title' => MODULE_PAYMENT_PO_TEXT_ERROR,

                     'error' => stripslashes(urldecode($_GET['error'])));



      return $error;

    }



    function check() {

      if (!isset($this->_check)) {

        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PO_STATUS'");

        $this->_check = tep_db_num_rows($check_query);

      }

      return $this->_check;

    }



    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Purchase Order Module', 'MODULE_PAYMENT_PO_STATUS', 'True', 'Do you want to accept purchase order payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PO_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PO_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Require Hardcopy', 'MODULE_PAYMENT_PO_HARDCOPY', 'False', 'Do you want to require the customer to send a hardcopy?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PO_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

    }



    function remove() {

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");

    }



    function keys() {

      return array('MODULE_PAYMENT_PO_STATUS', 'MODULE_PAYMENT_PO_ZONE', 'MODULE_PAYMENT_PO_ORDER_STATUS_ID', 'MODULE_PAYMENT_PO_HARDCOPY', 'MODULE_PAYMENT_PO_SORT_ORDER');

    }

  }

?>