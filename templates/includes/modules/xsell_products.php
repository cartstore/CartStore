<div class="modulelist">
  <div class="module-product">
    <div class="mbottom">
      <div class="mTop">
        <h3>You Might Also Be Interested In</h3>
        <?php
  if (!tep_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
  } else {
      $customer_group_id = $sppc_customer_group_id;
  }
  if ($customer_group_id != '0') {
      $products_extra_images_query = tep_db_query("select distinct p.products_id, pd.products_url,p.map_price, p.msrp_price, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' and pg.customers_group_id = '" . $customer_group_id . "' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
  } else {
      $products_extra_images_query = tep_db_query("select distinct p.products_id, pd.products_url,p.map_price, p.msrp_price, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
  }
  $total = tep_db_num_rows($products_extra_images_query);
  if (tep_db_num_rows($products_extra_images_query) >= 1) {
      print('');
      $row = 0;
      $col = 0;
      $count = 1;

      $rowcount_value = 3;
      $rowcount = 1;
?>
        <?php

      while ($extra_images = tep_db_fetch_array($products_extra_images_query)) {
          if ($extra_images['map_price'] != "0.00") {
              if ($_SESSION['customers_email_address'] != '') {
                  $products_price = $extra_images['products_price'];
                  $products_price .= '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($extra_images['msrp_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</span><br>





<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($extra_images['map_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</span>';
              } else {
                  $products_price = '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($extra_images['msrp_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</span><br>





<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($extra_images['map_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</span>';
              }
              if ($_SESSION['customers_email_address'] == '') {
                  $products_price .= '<br>



<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php">Login to See Price</a></span>';
              }
          } elseif ($extra_images['msrp_price'] != "0.00") {
              $products_price = '<div class="price">' . $extra_images['products_price'] . '</div><span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($extra_images['msrp_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</span>';
          } elseif ($products_price = $extra_images['products_price'] > 0) {
              $products_price = $extra_images['products_price'];
          } else {
              $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashesextra_images['products_name'])) . "&products_model=" . $product_info['products_model'];
              $products_price = echo '<a href="' . $urlContactUs . '">Email for Price </a>';
          }
          if ($product_info['products_url'] != "") {
              $newArea = '<div align="right"><span class="alternate_buy" ><a class="button" href="' . $extra_images['products_url'] . '" title="' . $extra_images['products_url'] . '" >Partner Buy </a></div>';
          } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
              $newArea = '';
          } else
              $
              elseif ($products_price = $extra_images['products_price'] > 0) {
                  $newArea = '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_INFO . '?action=buy_now&products_id=' . $extra_images['products_id']) . '">Add to Cart</a>';
              } else {
                  $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashesextra_images['products_name'])) . "&products_model=" . $product_info['products_model'];
                  $newArea = '<a class="button" href="' . $urlContactUs . '">Email 4 Price</a>';
              }
          if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
              $products_price = "";
          } else
              $products_price = $extra_images['products_price'];
          print('');
          if ($extra_images['products_image'] != "" && file_exists(DIR_WS_IMAGES . '/' . $extra_images['products_image'])) {
              print('<div class="productWrap">





   <h4><a  class="pname" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . $extra_images['products_name'] . '</a>

   </h4>

   <center>

   <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '"><div class="image">' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>

   <a class="details" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">Details</a>



   </center>



   <div class="price">' . $products_price . '

   </div>

   ' . $newArea . '

   </div>



');
          } else {
              print("");
          }
          print('');
          $col++;
          if ($col > (PRODUCT_LIST_NUMCOL - 1) || $count == $total) {
              if ($total < PRODUCT_LIST_NUMCOL) {
                  print('');
              }
              print('');
              $row++;
          }
          $count++;
      }
      print('');
  }
?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>