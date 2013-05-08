<?php
/*
  Copyright (C) 2008 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Google Checkout v1.5.0
 * $Id: general.php 160 2009-02-17 20:10:40Z ed.davisson $
 * 
 * This is our hook into osCommerce's tep_remove_order() function.
 * 
 * It's meant to be included in catalog/admin/includes/functions/general.php.
 */

$status_query = tep_db_query(
    "select configuration_value from " . TABLE_CONFIGURATION
    . " where configuration_key = 'MODULE_PAYMENT_GOOGLECHECKOUT_STATUS'");
    
while ($status = tep_db_fetch_array($status_query)) {
  $status_flag = $status['configuration_value'];  
}

if ($status_flag == 'True') {
  require_once('../includes/modules/payment/googlecheckout.php');
  $google_checkout = new googlecheckout();
  tep_db_query(
      "delete from " . $google_checkout->table_order 
      . " where orders_id = '" . (int) $order_id . "'");
}

?>
