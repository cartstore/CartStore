<?php
/*
  $Id: internetsecure_ml.php,v 1.8 2009/08/06 15:46:08 $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
   if ( $_POST['xxxVar2'] == MODULE_PAYMENT_ISECURE_MYSECURITY_CODE) {
    if (isset($_POST['xxxVar1']) && is_numeric($_POST['xxxVar1']) && ($_POST['xxxVar1'] > 0)) {
		$check_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $_POST['xxxVar1'] . "' and customers_id = '" . (int)$_POST['xxxVar3'] . "'");
        if (tep_db_num_rows($check_query) > 0) {
           $order_status_id = MODULE_PAYMENT_INTERNETSECURE_DL_ZONE_PREPARE_ORDER_STATUS_ID;
           if (MODULE_PAYMENT_ISECURE_ORDER_STATUS_ID > 0) {
            $order_status_id = MODULE_PAYMENT_ISECURE_ORDER_STATUS_ID;
           }
		   tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . $order_status_id . "', last_modified = now() where orders_id = '" . $_POST['xxxVar1'] . "'");
           $sql_data_array = array('orders_id' => $_POST['xxxVar1'],
                                   'orders_status_id' => $order_status_id,
                                   'date_added' => 'now()',
                                   'customer_notified' => '1');

           tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
         }
     }
   }
  require('includes/application_bottom.php');
?>