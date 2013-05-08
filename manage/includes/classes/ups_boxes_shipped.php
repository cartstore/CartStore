<?php
/*
  $Id: ups_boxes_shipped.php,v 1.0 2007/09/09 JanZ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  class ups_boxes_shipped {
    var $info, $boxes, $customer;

    function ups_boxes_shipped($id) {
      $this->info = array();
      $this->boxes = array();
      $this->customer = array();

      $this->query($id);
    }

    function query($id) {
      $entry_query = tep_db_query("select c.customers_id, customers_firstname, customers_lastname, customers_email_address, customers_telephone, ubs.id, ubs.date, ubs.boxes from " . TABLE_UPS_BOXES_USED . " ubs, " . TABLE_CUSTOMERS . " c where ubs.id = '" . (int)$id . "' and c.customers_id = ubs.customers_id");
      $entry = tep_db_fetch_array($entry_query);

      $this->customer = array('name' => $entry['customers_firstname'] . ' ' . $entry['customers_lastname'], 'email_address' => $entry['customers_email_address'], 'telephone' => $entry['customers_telephone'], 'customers_id' => $entry['customers_id']);

      $this->boxes = unserialize(base64_decode($entry['boxes']));
      $this->info = array('id' => $entry['id'],
                          'date' => $entry['date'],
                          'first_name' => $entry['customers_firstname'],
                          'num_of_boxes' => count($this->boxes));
    }
  }
?>
