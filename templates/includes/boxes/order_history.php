<?php
  if (tep_session_is_registered('customer_id')) {
      
      $orders_query = tep_db_query("select distinct op.products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
      if (tep_db_num_rows($orders_query)) {
?>
<!-- customer_orders //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>Order History</h3>
        <ul>
          <?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => BOX_HEADING_CUSTOMER_ORDERS);
          new infoBoxHeading($info_box_contents, false, false);
          $product_ids = '';
          while ($orders = tep_db_fetch_array($orders_query)) {
              $product_ids .= (int)$orders['products_id'] . ',';
          }
          $product_ids = substr($product_ids, 0, -1);
          $customer_orders_string = '';
          $products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . (int)$languages_id . "' order by products_name");
          while ($products = tep_db_fetch_array($products_query)) {
              $customer_orders_string .= '' . '<li><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a></li>' . '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products['products_id']) . '">' . tep_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a>' . '';
          }
          $customer_orders_string .= '';
          $info_box_contents = array();
          $info_box_contents[] = array('text' => $customer_orders_string);
          new infoBox($info_box_contents);
?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- customer_orders_eof //-->
<?php
      }
  }
?>