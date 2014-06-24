<?php
  /*
   
   
   
   orders_by_vendor.php V1.0 2006/03/25
   
   by Craig Garrison Sr
   
   www.blucollarsales.com
   
   for MVS V1.0 2006/03/25 JCK/CWG
   
   CartStore eCommerce Software, for The Next Generation
   
   http://www.cartstore.com
   
   
   
   Copyright (c) 2008 Adoovo Inc. USA
   
   
   
   GNU General Public License Compatible
   
   */
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $vendor_query_raw = "select vendors_id as id, vendors_name as name from " . TABLE_VENDORS . " order by name";
  $vendor_query = tep_db_query($vendor_query_raw);
  if (isset($vID)) {
      $vendors_id = $vID;
  }
  if ($by == 'date') {
      $by = 'date_purchased';
  } elseif ($by == 'customer') {
      $by = 'customers_id';
  } elseif ($by == 'customer') {
      $by = 'status';
  } elseif ($by == 'sent') {
      $by == 'sent';
  } else {
      $by = 'orders_id';
  }
  if (isset($line)) {
      $line == $line;
  } else {
      $line = 'desc';
  }
?>




<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<script language="javascript" src="includes/general.js"></script>


<div class="page-header"><h1><?php
  echo HEADING_TITLE;
?></h1></div>




                    <?php
  $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
  $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
  while ($vendors = tep_db_fetch_array($vendors_query)) {
      $vendors_array[] = array('id' => $vendors['vendors_id'], 'text' => $vendors['vendors_name']);
  }
?>
                 
<?php
  echo '<a class="pull-right" href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id) . '">Click to reset form</a><br>';
?>


<?php
  echo '<a class="pull-right" href="' . tep_href_link(FILENAME_VENDORS) . '">Go To Vendors List</a>';
?>
                   



<div class="form-group">
<?php
  echo tep_draw_form('vendors_report', FILENAME_ORDERS_VENDORS) . '<label>'.  TABLE_HEADING_VENDOR_CHOOSE . ' </label>';
?>

<?php
  echo tep_draw_pull_down_menu('vendors_id', $vendors_array, '', 'onChange="this.form.submit()";');
?>
                      </form>
                      
                      </div>
                      
                      
      <div class="form-group"><label>                
<?php
  echo 'Filter by email sent: </label><a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&sent=yes') . '">YES</a> <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&sent=no') . '">NO</a>';
?>
</div>
                    <?php
  if ($line == 'asc') {
      if (isset($status)) {
?>
       
<?php
          echo '<div class="form-group pull-right"><i class="fa fa-sort"></i> <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=desc' . '&sent=' . $sent . '&status=' . $status) . '">DESCENDING</a></div>';
?>

                    <?php
          } else
          {
?>
                  
<?php
              echo '<div class="form-group pull-right"><i class="fa fa-sort"></i> <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=desc' . '&sent=' . $sent) . '"> DESCENDING</a> </div>';
?>

                    <?php
          }
?>
                    <?php
      } elseif (!isset($status)) {
?>
                
<?php
          echo '<div class="form-group pull-right"><i class="fa fa-sort"></i> <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=asc' . '&sent=' . $sent) . '"> ASCENDING</a> </div>';
?>

                    <?php
          } else
          {
?>
            <?php
              echo '<div class="form-group pull-right"><i class="fa fa-sort"></i> <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, '&vendors_id=' . $vendors_id . '&line=asc' . '&sent=' . $sent . '&status=' . $status) . '"> ASCENDING</a> </div>';
?>
                    <?php
          }
?>
                    <?php
          $orders_statuses = array();
          $orders_status_array = array();
          $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
          while ($orders_status = tep_db_fetch_array($orders_status_query)) {
              $orders_statuses[] = array('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
              $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
          }
?>
              
<?php
          echo '<div class="form-group"><label>'. tep_draw_form('status_report', FILENAME_ORDERS_VENDORS . '?&vendors_id=' . $vendors_id) . '<label>'. HEADING_TITLE_STATUS . ' </label>';
          echo tep_draw_pull_down_menu('status', $orders_statuses, '', 'onChange="this.form.submit()";') .'</div>';
?>
                      </form>

                <?php
          // if (isset($_POST['vendors_id'])) { 
?>
           
<table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top"><table class="table">
                        <tr class="dataTableHeadingRow">
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_VENDOR;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_ORDER_ID;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_CUSTOMERS;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_ORDER_TOTAL;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_DATE_PURCHASED;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_STATUS;
?></td>
                          <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_ORDER_SENT;
?>&nbsp;</td>
                        </tr>
                        <?php
          $vend_query_raw = "select vendors_name as name from " . TABLE_VENDORS . " where vendors_id = '" . $vendors_id . "'";
          $vend_query = tep_db_query($vend_query_raw);
          $vendors = tep_db_fetch_array($vend_query);
?>
                        <tr class="dataTableRow">
                          <td class="dataTableContent"><?php
          echo '<a href="' . tep_href_link(FILENAME_VENDORS, '&vendors_id=' . $vendors_id . '&action=edit') . '" TARGET="_blank"><b>' . $vendors['name'] . '</a></b>';
?></td>
                          <td class="dataTableContent"><?php
          echo '';
?></td>
                          <td class="dataTableContent"><?php
          echo '';
?></td>
                          <td class="dataTableContent"><?php
          echo '';
?></td>
                          <td class="dataTableContent"><?php
          echo '';
?></td>
                          <td class="dataTableContent"><?php
          echo '';
?></td>
                          <td class="dataTableContent">  
                            Send Email</td>
                        </tr>
                        <?php
          $index1 = 0;
          if ($sent == 'yes') {
              $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' and vendor_order_sent='yes' group by orders_id " . $line . "");
          } elseif ($sent == 'no') {
              $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' and vendor_order_sent='no' group by orders_id " . $line . "");
          } else {
              $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' group by orders_id " . $line . "");
          }
          while ($vendors_orders_data = tep_db_fetch_array($vendors_orders_data_query)) {
              //  $vendors_orders_id = $vendors_orders_list_data['orders_id'];
              //  $vendors_products_id = $vendors_orders_list_data['v_products_id'];
              $index2 = 0;
              $vendors_products_data_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id='" . $vendors_orders_data['v_products_id'] . "' and language_id = '" . $languages_id . "'");
              //  while ($vendors_products_data = tep_db_fetch_array($vendors_products_data_query)) {
              $index3 = 0;
              if (isset($status)) {
                  $orders_query = tep_db_query("select distinct o.customers_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = '" . $status . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and ot.class = 'ot_total' and o.orders_id =  '" . $vendors_orders_data['orders_id'] . "' order by o." . $by . " ASC");
              } else {
                  $orders_query = tep_db_query("select distinct o.customers_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and ot.class = 'ot_total' and o.orders_id =  '" . $vendors_orders_data['orders_id'] . "' order by o." . $by . " ASC");
              }
              while ($vendors_orders = tep_db_fetch_array($orders_query)) {
                  $raw_date_purchased = $vendors_orders['date_purchased'];
                  if (tep_not_null($raw_date_purchased)) {
                      list($date_2, $time_2) = explode(' ', $raw_date_purchased);
                      list($year, $month, $day) = explode('-', $date_2);
                      $date_purchased = ((strlen($day) == 1) ? '0' . $day : $day) . '/' . ((strlen($month) == 1) ? '0' . $month : $month) . '/' . $year;
                  }
?>
                        <tr class="dataTableRow">
                          <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $vendors_orders_data['orders_id'] . '&action=edit') . '" TARGET="_blank"><b>View this order</b></a>';
?></td>
                          <td class="dataTableContent"><?php
                  echo $vendors_orders['orders_id'];
?></td>
                          <!--     <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $vendors_orders_data['v_products_id']) . '" TARGET="_blank"><b>' . $vendors_products_data['products_name'] . '</a>';
?></td>  -->
                          <td class="dataTableContent"><?php
                  echo ' from <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $vendors_orders['customers_id'] . '&action=edit') . '" TARGET="_blank"><b>' . $vendors_orders['customers_name'] . '</b></a>';
?></td>
                          <td class="dataTableContent"><?php
                  echo strip_tags($vendors_orders['order_total']);
?></td>
                          <td class="dataTableContent"><?php
                  echo $date_purchased;
?></td>
                          <td class="dataTableContent"><?php
                  echo $vendors_orders['orders_status_name'];
?></td>
                          <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, '&vID=' . $vendors_id . '&oID=' . $vendors_orders_data['orders_id'] . '&vOS=' . $vendors_orders_data['vendor_order_sent']) . '"><b>' . $vendors_orders_data['vendor_order_sent'] . '</a></b>';
?></td>
                        </tr>
                        <?php
                  $index3++;
              }
              $index2++;
              //}
              $index1++;
          }
?>
                      </table></td>
                  </tr>
                </table> 
    <!-- body_eof //-->
    <!-- footer //-->
    <?php
          require(DIR_WS_INCLUDES . 'footer.php');
?>
   
<?php
          require(DIR_WS_INCLUDES . 'application_bottom.php');
?>