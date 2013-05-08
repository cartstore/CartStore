<?php
/*
  $Id: cod.php,v 1.28 2003/02/14 05:51:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ccerr {
    var $code, $title, $description, $enabled;

// class constructor
    function ccerr() {
      global $order;

      $this->code = 'ccerr';
      $this->title = MODULE_PAYMENT_CCERR_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_CCERR_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_CCERR_SORT_ORDER;
      $this->enabled = false;
    }

// class methods
    function update_status() {
          $this->enabled = false;
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
      global $_GET;

      $error = array('title' => 'Error',
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
    // Check if module is installed or not
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CCERR_SORT_ORDER'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_CCERR_SORT_ORDER', '99', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_CCERR_SORT_ORDER');
    }
  }
?>
