<?php
  if (sizeof($featured_products_array) <> '0') {
      if ($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') {
?>

<div class="moduletable-featuredProducts"><!-- moduletable-featuredProducts -->
						<h3>Featured Products</h3>
  <div class="product-list row">

   <?php
          } else
          {
?>
<div class="moduletable-featuredProducts"><!-- moduletable-featuredProducts -->
						<h3>Featured Products</h3>
                                                <div class="product-list row">
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
                  $products_price = '<s>' . $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s>' . $currencies->display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '';
              } else {
                  $products_price = $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id']));
              }
              $pf->loadProduct($featured_products_array[$i]['id'], (int)$languages_id);
              $products_price = $pf->getPriceString();
              if ($featured_products_array[$i]['image'] != "" && file_exists(DIR_WS_IMAGES . '/' . $featured_products_array[$i]['image'])) {
                  $z_image = '<a class="product-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
              } else {
                  $z_image = '<a class="product-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '"></a>';
              }
              print('
                  
<div class="product-box col-md-4 col-sm-4">
<div class="product-img">
' . $z_image . '</div>
<h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '" title="' . $featured_products_array[$i]['name'] . '">' . $featured_products_array[$i]['name'] . '</a></h4>




         
 


 

');
              if ($featured_products_array[$i]['map_price'] != "0.00") {
                  if (isset($_SESSION['customers_email_address'])) {
                      $whats_new_price = $products_price;
                      $whats_new_price .= '<!-- MSRP Price: ' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . ' -->

' . $currencies->display_price($featured_products_array[$i]['map_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '';
                  } else {
                      $whats_new_price = '<!-- MSRP Price: ' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . ' -->

' . $currencies->display_price($featured_products_array[$i]['map_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '';
                  }
                  if (empty($_SESSION['customers_email_address'])) {
                      $whats_new_price .= '

<a href="login.php">Login for Price</a>';
                  }
              } elseif ($featured_products_array[$i]['msrp_price'] != "0.00") {
                  $whats_new_price = $products_price . '<!-- MSRP Price: ' . $currencies->display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '--> ';
              } else
                  $whats_new_price = $products_price;
              if ($featured_products_array[$i]['products_url'] != "") {
                  $newArea = '<a class="buy-it-now" href=' . $featured_products_array[$i]['products_url'] . '" title="' . $featured_products_array[$i]['products_url'] . '" >Partner Buy </a>';
              } elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                  $newArea = '';
              } elseif ($featured_products_array[$i]['products_price'] > 0) {
                  $newArea = '<a class="buy-it-now" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id']) . '">Buy It Now</a>';
              } else {
                  $newArea = '<a class="buy-it-now" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id']) . '">Read More</a>';
              }
              if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
                  $whats_new_price = "";
              } elseif ($featured_products_array[$i]['products_price'] > 0) {
                  $whats_new_price = $whats_new_price;
              } else {
                  $whats_new_price = '';
              }
              print(' ');
              print(
              
			 
              
              '<div class="price">' . $whats_new_price . '</div>
                  
' .   $newArea . '
 </div>
              
          
 
 
        ');
              if (is_int(($i + 1) / 5)) {
                  if (($i + 1) < sizeof($featured_products_array)) {
                     
                  }
              } else {
              
              }
          }
       
      }
?>
        <?php
      if (sizeof($featured_products_array) <> '0') {
?>
  
</div></div>
<?php
          } else
          {
          }
?>
 