<?php
  if ($random_product = tep_random_select("select p.products_id,pd.products_url, p.products_image,p.map_price, p.msrp_price, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_WHATS_NEW_BOX == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id=pd.products_id order by p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW)) {
?>
<!-- whats_new //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>WHATS NEW</h3>
        <?php
      $random_product['products_name'] = tep_get_products_name($random_product['products_id']);
      $random_product['specials_new_products_price'] = tep_get_products_special_price($random_product['products_id']);
      if (!tep_session_is_registered('sppc_customer_group_id')) {
          $customer_group_id = '0';
      } else {
          $customer_group_id = $sppc_customer_group_id;
      }
      if ($customer_group_id != '0') {
          $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $random_product['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
          if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
              $random_product['products_price'] = $customer_group_price['customers_group_price'];
          }
      }
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_WHATS_NEW);
      new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));
      if (tep_not_null($random_product['specials_new_products_price'])) {
          $whats_new_price = '<s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br>';
          $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
      } else {
          $whats_new_price = $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
      }
      if ($random_product['map_price'] != "0.00") {
          if (isset($_SESSION['customers_email_address'])) {
              $whats_new_price = $whats_new_price;
              $whats_new_price .= '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($random_product['map_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
          } else {
              $whats_new_price = '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($random_product['map_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
          }
          if (isset($_SESSION['customers_email_address'])) {
              $whats_new_price .= '<br>

<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php">Login to See Price</a></span>';
          }
      } elseif ($random_product['msrp_price'] != "0.00") {
          $whats_new_price = '<div class="price">' . $whats_new_price . '</div><span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
      } else
          $whats_new_price = $whats_new_price;
      if ($random_product['products_url'] != "") {
          $newArea = '<div align="right"><span class="alternate_buy" ><a class="button" href=' . $random_product['products_url'] . '" title="' . $random_product['products_url'] . '" >Partner Buy </a></div>';
      } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
          $newArea = '';
      } elseif ($random_product['products_price'] > 0) {
          $newArea = '<form method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'products_id')) . 'action=buy_now&products_id=' . $random_product['products_id']) . '">
<input class="button" type="submit" value="Add to Cart" /></form>';
      } else {
          $newArea = '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">Read More</a>';
      }
      if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
          $whats_new_price = "";
      } else
          $whats_new_price = $whats_new_price;
      $info_box_contents = array();
      $info_box_contents[] = array('align' => '', 'text' => '
<div class="box"><h4><a class="special_title" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a></h4>

 <a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], 89, SMALL_IMAGE_HEIGHT) . '</a>
<span class="short_desc">' . $random_product['products_short'] . '</span> <div class="clear"/></div>

<a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">More Info</a>
<div class="clear"/></div>

<div class="price">
' . $whats_new_price . '</div>' . $newArea . '<div class="clear"/></div>');
      $info_box_contents[] = array('align' => '', 'text' => '');
      new infoBox($info_box_contents);
?>
        <center>
          <a class="h4" href="products_new.php">View all new products</a>
        </center>
      </div>
    </div>
  </div>
</div>
</div>
<!-- whats_new_eof //-->
<?php
  }
?>