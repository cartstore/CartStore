<?php

  if (!tep_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
  } else {
      $customer_group_id = $sppc_customer_group_id;
  }
  if ($customer_group_id == '0') {
      $random_product_arr = tepps_special_product("select p.products_id,p.map_price, p.msrp_price, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image,pd.products_url, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by RAND() desc limit " . PWNO_OF_SPECIAL_DISPLAY);
  } else {
      $random_product_arr = tepps_special_product("select p.products_id,pd.products_url,p.map_price, p.msrp_price, pd.products_name, IF(pg.customers_group_price IS NOT NULL,pg.customers_group_price, p.products_price) as products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using (products_id, customers_group_id) where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and s.customers_group_id= '" . $customer_group_id . "' order by RAND() desc limit " . PWNO_OF_SPECIAL_DISPLAY);
  }
  if (isset($psSingleDisplay)) {
      if (tep_not_null($random_product)) {
?>
<!-- specials //-->
<?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);
          new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));
          $info_box_contents = array();
          $info_box_contents[] = array('align' => '', 'text' => '
                  <div class="box">
          <h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '"><h4>' . $random_product['products_name'] . '</a></h4>

          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
         Desc: ' . $random_product['products_short'] . '
        <a href="#" class="readon">More Info</a>

           <h4> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '"><h4>' . $random_product['products_name'] . '</a></h4>Desc: ' . $random_product['products_short'] . '<br />
<div class="bottom">
<div class="cart">
<a class="readon" href="#">More Info</a>
<input class="button" type="button" value="Add to Cart" input=""/>
</div><div class="orginalprice">
Original Price :<span>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div><div class="salesprice">
Sales Price :
<br/>
<span>' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div>
</div>   ');
          $info_box_contents[] = array('align' => '', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . BOX_SPECIALS_ALLPRODS . '</a>');
          new infoBox($info_box_contents);
?>
<!-- specials_eof //-->
<?php
      }
  } else {
      if (tep_not_null($random_product_arr)) {
          foreach ($random_product_arr as $random_product) {
?>
<div class="module special">
  <div>
    <div>
      <div>
        <h3>SPECIALS</h3>
        <!-- specials //-->
        <?php
              $info_box_contents = array();
              $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);
              new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));
              $info_box_contents = array();
              if ($random_product['map_price'] != "0.00") {
                  if ($_SESSION['customers_email_address'] != '') {
                      $whats_new_price = '<div class="orginalprice">
Original Price :<span>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div><div class="salesprice">

<span>' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div>';
                      $whats_new_price .= '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($random_product['map_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
                  } else {
                      $whats_new_price = '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($random_product['map_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
                  }
                  if ($_SESSION['customers_email_address'] == '') {
                      $whats_new_price .= '<br>

<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php">Login to See Price</a></span>';
                  }
              } elseif ($random_product['msrp_price'] != "0.00") {
                  $whats_new_price = '<div class="orginalprice">
Original Price :<span>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div><div class="salesprice">

<span>' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div>' . '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($random_product['msrp_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
              } else
                  $whats_new_price = '<div class="orginalprice">
Original Price :<span>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div><div class="salesprice">

<span>' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>
</div>';
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
              $info_box_contents[] = array('align' => '', 'text' => '<div class="box">



<h4><a class="special_title" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a></h4>


<a class="imagebox" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], 89, SMALL_IMAGE_HEIGHT) . '</a>

<span class="short_desc">' . $random_product['products_short'] . '</span>
<div class="clear"></div><a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $random_product['products_id']) . '">More Info</a>
<div class="clear"></div>
<div class="price">' . $whats_new_price . '</div>' . $newArea);
              $info_box_contents[] = array('align' => '', 'text' => '<div class="clear"></div>
              </div>



');
              $info_box_contents[] = array('align' => '', 'text' => '');
              new infoBox($info_box_contents);
?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<!-- specials_eof //-->
<?php
          }
      }
  }
?>