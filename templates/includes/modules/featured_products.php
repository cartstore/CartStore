<?php
  if (sizeof($featured_products_array) <> '0') {
      if ($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') {
?>

<div class="clear"></div>
<div class="module-product">
<div class="mbottom">
<div class="mTop">
  <h3>Featured Products</h3>
  <?php
          } else
          {
?>
  <div class="module-product">
    <div class="mbottom">
      <div class="mTop">
        <h3>Featured Products</h3>
        <?php
          }
      }
      if (!tep_session_is_registered('sppc_customer_group_id')) {
          $customer_group_id = '0';
      } else {
          $customer_group_id = $sppc_customer_group_id;
      }
      if (sizeof($featured_products_array) <> '0') {
          for ($i = 0; $i < sizeof($featured_products_array); $i++) {
              if ($featured_products_array[$i]['specials_price']) {
                  $products_price = '<s>' . $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s><span class="productSpecialPrice">' . $currencies->display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
              } else {
                  $products_price = $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id']));
              }
              $pf->loadProduct($featured_products_array[$i]['id'], (int)$languages_id);
              $products_price = $pf->getPriceString();
              if ($featured_products_array[$i]['image'] != "" && file_exists(DIR_WS_IMAGES . '/' . $featured_products_array[$i]['image'])) {
                  $z_image = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
              } else {
                  $z_image = "&nbsp;";
              }
              print('
<div class="productWrap">

<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '" title="' . $featured_products_array[$i]['name'] . '">' . $featured_products_array[$i]['name'] . '</a></h4>

<center>' . $z_image . '</center>');
              if ($featured_products_array[$i]['map_price'] != "0.00") {
                  if (isset($_SESSION['customers_email_address'])) {
                      $whats_new_price = $products_price;
                      $whats_new_price .= '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($featured_products_array[$i]['map_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
                  } else {
                      $whats_new_price = '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>

<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($featured_products_array[$i]['map_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
                  }
                  if (empty($_SESSION['customers_email_address'])) {
                      $whats_new_price .= '

<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href="login.php">Login for Price</a></span>';
                  }
              } elseif ($featured_products_array[$i]['msrp_price'] != "0.00") {
                  $whats_new_price = $products_price . '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
              } else
                  $whats_new_price = $products_price;
              if ($featured_products_array[$i]['products_url'] != "") {
                  $newArea = '<div align="center"><span class="alternate_buy" ><a class="button" href=' . $featured_products_array[$i]['products_url'] . '" title="' . $featured_products_array[$i]['products_url'] . '" >Partner Buy </a></div>';
              } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                  $newArea = '';
              } elseif ($featured_products_array[$i]['products_price'] > 0) {
                  $newArea = '<a class="button" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id']) . '">Add to Cart</a>';
              } else {
                  $newArea = '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id']) . '">Read More</a>';
              }
              if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                  $whats_new_price = "";
              } elseif ($featured_products_array[$i]['products_price'] > 0) {
                  $whats_new_price = $whats_new_price;
              } else {
                  $whats_new_price = '';
              }
              print(' <div class="price">' . $whats_new_price . '</div><div class="clear"></div>');
              print($newArea . '<div class="hidden">
<span class="model"> ' . $featured_products_array[$i]['products_model'] . '</span>


Desc:' . $featured_products_array[$i]['shortdescription'] . '

<a class="readon" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '">More Info</a>
<form method="post" action="">
<input class="button" type="submit" value="Add to Cart" /></form>
</div>
</div>
        ');
              if (is_int(($i + 1) / 5)) {
                  if (($i + 1) < sizeof($featured_products_array)) {
                      print('
        ');
                  }
              } else {
                  print('
      ');
              }
          }
          print('
    ');
      }
?>
        <?php
      if (sizeof($featured_products_array) <> '0') {
?>
        <div class="clear"/>
      </div>
    </div>
  </div>
</div>
<?php
          } else
          {
          }
?>