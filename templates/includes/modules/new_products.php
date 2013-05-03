<!-- new_products //-->
<?php
  if ((!isset($new_products_category_id)) || ($new_products_category_id == '0')) {
      $new_products_query = tep_db_query("select p.products_id, p.products_image,pd.products_url,p.map_price, p.msrp_price, p.products_tax_class_id, p.products_price as products_price from " . TABLE_PRODUCTS . " p," . TABLE_PRODUCTS_DESCRIPTION . " pd  where " . (YMM_FILTER_NEW_PRODUCTS == 'Yes' ? $YMM_where : '') . " products_status = '1' and pd.language_id = '" . (int)$languages_id . "'  and p.products_id = pd.products_id order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } //if ((!isset($new_products_category_id)) || ($new_products_category_id == '0'))

  else {
      $new_products_query = tep_db_query("select distinct p.products_id,pd.products_url, p.products_image,p.map_price, p.msrp_price, p.products_tax_class_id, p.products_price as products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c," . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_NEW_PRODUCTS == 'Yes' ? $YMM_where : '') . " p.products_id = p2c.products_id and p.products_id = pd.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'  order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } //else

  if (!tep_session_is_registered('sppc_customer_group_id')) {
      $customer_group_id = '0';
  } //if (!tep_session_is_registered('sppc_customer_group_id'))

  else {
      $customer_group_id = $sppc_customer_group_id;
  } //else

  if (($no_of_new_products = tep_db_num_rows($new_products_query)) > 0) {
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));
  new contentBoxHeading($info_box_contents);
      while ($_new_products = tep_db_fetch_array($new_products_query)) {
          $new_products[] = $_new_products;
          $list_of_prdct_ids[] = $_new_products['products_id'];
      } //while ($_new_products = tep_db_fetch_array($new_products_query))

      $select_list_of_prdct_ids = "products_id = '" . $list_of_prdct_ids[0] . "' ";
      if ($no_of_new_products > 0) {
          echo '<div class="module-product">
  <div class="mbottom">
    <div class="mTop">
      <h3>New Products</h3>';
          for ($n = 1; $n < count($list_of_prdct_ids); $n++) {
              $select_list_of_prdct_ids .= "or products_id = '" . $list_of_prdct_ids[$n] . "' ";
          } //for ($n = 1; $n < count($list_of_prdct_ids); $n++)

          }
          if ($customer_group_id != '0') {
              $pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where (" . $select_list_of_prdct_ids . ") and pg.customers_group_id = '" . $customer_group_id . "'");
              while ($pg_array = tep_db_fetch_array($pg_query)) {
                  $new_prices[] = array('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price'], 'specials_new_products_price' => '');
              } //while ($pg_array = tep_db_fetch_array($pg_query))

              for ($x = 0; $x < $no_of_new_products; $x++) {
                  if (!empty($new_prices)) {
                      for ($i = 0; $i < count($new_prices); $i++) {
                          if ($new_products[$x]['products_id'] == $new_prices[$i]['products_id']) {
                              $new_products[$x]['products_price'] = $new_prices[$i]['products_price'];
                          } //if ($new_products[$x]['products_id'] == $new_prices[$i]['products_id'])

                          }
                      } //for ($i = 0; $i < count($new_prices); $i++)

                      }
                  } //if (!empty($new_prices))

                  $specials_query = tep_db_query("select products_id, specials_new_products_price from specials where (" . $select_list_of_prdct_ids . ") and status = '1' and customers_group_id = '" . $customer_group_id . "' ");
                  while ($specials_array = tep_db_fetch_array($specials_query)) {
                      $new_s_prices[] = array('products_id' => $specials_array['products_id'], 'specials_new_products_price' => $specials_array['specials_new_products_price']);
                  } //while ($specials_array = tep_db_fetch_array($specials_query))

                  if (!empty($new_s_prices)) {
                      for ($x = 0; $x < $no_of_new_products; $x++) {
                          for ($i = 0; $i < count($new_s_prices); $i++) {
                              if ($new_products[$x]['products_id'] == $new_s_prices[$i]['products_id']) {
                                  $new_products[$x]['products_price'] = $new_s_prices[$i]['specials_new_products_price'];
                              } //if ($new_products[$x]['products_id'] == $new_s_prices[$i]['products_id'])

                              }
                          } //for ($i = 0; $i < count($new_s_prices); $i++)

                          }
                          $row = 0;
                          $col = 0;
                          $info_box_contents = array();
                          for ($x = 0; $x < $no_of_new_products; $x++) {
                              $new_products[$x]['products_name'] = tep_get_products_name($new_products[$x]['products_id']);
                              $new_products[$x]['products_short'] = tep_get_products_short_des($new_products[$x]['products_id']);
                              if ($new_products[$x]['map_price'] != "0.00") {
                                  if ($_SESSION['customers_email_address'] != '') {
                                      $whats_new_price .= '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($new_products[$x]['msrp_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($new_products[$x]['map_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</span>';
                                  } //if ($_SESSION['customers_email_address'] != '')

                                  else {
                                      $whats_new_price = '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($new_products[$x]['msrp_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($new_products[$x]['map_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</span>';
                                  } //else

                                  if ($_SESSION['customers_email_address'] == '') {
                                      $whats_new_price .= '

<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php">Login for Price</a></span>';
                                  } //if ($_SESSION['customers_email_address'] == '')

                                  }
                                  elseif ($new_products[$x]['msrp_price'] != "0.00") {
                                      $whats_new_price = '<div class="price">' . $currencies->display_price($new_products[$x]['products_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</div><span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($new_products[$x]['msrp_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '</span>';
                                  } //elseif ($new_products[$x]['msrp_price'] != "0.00")

                                  elseif ($new_products[$x]['products_price'] > 0) {
                                      $whats_new_price = $currencies->display_price($new_products[$x]['products_price'], tep_get_tax_rate($new_products[$x]['products_tax_class_id'])) . '';
                                  } //elseif ($new_products[$x]['products_price'] > 0)

                                  else {
                                      $urlContactUs = "email_for_price.php?product_name=" . addslashes(addslashes($new_products['products_name'])) . "&products_model=" . $new_products['products_model'];
                                      $whats_new_price = '';
                                  } //else

                                  if ($new_products[$i]['products_url'] != "") {
                                      $newArea = '<div align="center"><span class="alternate_buy" ><a class="button" href=' . $new_products[$x]['products_url'] . '" title="' . $new_products[$x]['products_name'] . '" >Partner Buy </a></div>';
                                  } //if ($new_products[$i]['products_url'] != "")

                                  elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                                      $newArea = '';
                                  } //elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '')

                                  elseif ($new_products[$x]['products_price'] > 0) {
                                      $newArea = ' <a class="button" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products[$x]['products_id']) . '" title="Order/Purchase ' . $new_products[$x]['products_name'] . '">Add to Cart</a>';
                                  } //elseif ($new_products[$x]['products_price'] > 0)

                                  else {
                                      $newArea = '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products[$x]['products_id']) . '">Read More</a>';
                                  } //else

                                  if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                                      $whats_new_price = "";
                                  } //if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '')

                                  else
                                      $whats_new_price = $whats_new_price;
                                  if ($new_products[$x]['products_price'] > 0) {
                                      $button = '<input class="button" type="submit" value="Add to Cart" />';
                                  } //if ($new_products[$x]['products_price'] > 0)

                                  else {
                                      $button = '';
                                  } //else

                                  $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '<div class="productWrap">

                       <h4><a title ="' . $new_products[$x]['products_name'] . '" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products[$x]['products_id']) . '">' . $new_products[$x]['products_name'] . '</a></h4>

                       <center><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products[$x]['products_image'], $new_products[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
                       </center>

                       <div class="price">' . $whats_new_price . '</div>

                      ' . $newArea . '

                       </div>



                       <div class="np_hide">

                       Desc : ' . $new_products[$x]['products_short'] . '
<a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products[$x]['products_id']) . '">More Info</a>
<form method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products[$x]['products_id']) . '">


<input class="button" type="submit" value="Add to Cart" /></form>
</div>

');
                                  $col++;
                                  if ($col > 2) {
                                      $col = 0;
                                      $row++;
                                  } //if ($col > 2)

                                  }
                              } //if ($new_products[$x]['map_price'] != "0.00")

                              new contentBox($info_box_contents);
                              if ($no_of_new_products > 0) {
                                  echo '<div class="clear"></div>
    </div>
  </div>
</div><div class="clear"></div>
';
                              } //if ($no_of_new_products > 1)

?>