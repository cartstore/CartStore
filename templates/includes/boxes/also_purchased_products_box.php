<?php
  if (isset($_GET['products_id'])) {
      $orders_query = tep_db_query("select 

  p.products_id,

  pd.products_short,

   p.products_image 

   from " . TABLE_ORDERS_PRODUCTS . " opa,

    " . TABLE_ORDERS_PRODUCTS . " opb,

     " . TABLE_PRODUCTS_DESCRIPTION . " pd,

     " . TABLE_ORDERS . " o,

      " . TABLE_PRODUCTS . "  p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
      $num_products_ordered = tep_db_num_rows($orders_query);
      if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>

<div class="module">
  <div>
    <div>
      <div>
        <h3>YOU MIGHT LIKE</h3>
        <div class="box">
          <!-- also_purchased_products //-->
          <?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => BOX_HEADING_CUSTOMERS_WHO_BOUGHT);
          new infoBoxHeading($info_box_contents, false, false);
          $row = 0;
          $col = 0;
          $info_box_contents = array();
          while ($orders = tep_db_fetch_array($orders_query)) {
              $orders['products_name'] = tep_get_products_name($orders['products_id']);
              $orders['products_short'] = tep_get_products_short_des($orders['products_id']);
              $info_box_contents[] = array('align' => '', 'params' => '', 'text' => '<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></h4><a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>

                         

                         <span class="short_desc">' . $orders['products_short'] . '</span>

  

  <div class="clear"></div>

  

  <a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">More Info</a>

<div class="clear"></div><hr>

                         ');
              $col++;
              if ($col > 2) {
                  $col = 0;
                  $row++;
              }
          }
          new infoBox($info_box_contents);
?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- also_purchased_products_eof //-->
<?php
      }
  }
?>