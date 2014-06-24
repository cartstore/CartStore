<?php
  if ($_GET['products_id']) {
      $xsell_query = tep_db_query("select distinct p.products_id,
                                             p.products_image,
                                             pd.products_name ,
               p.products_price ,
               p.products_tax_class_id,
               p.products_price ,
              p.products_model,
             p.products_price ,
           m.manufacturers_name
                             from ((" . TABLE_PRODUCTS_XSELL . " xp,
                                  " . TABLE_PRODUCTS . " p,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd )
left join manufacturers m on p.manufacturers_id = m.manufacturers_id)
                             where xp.products_id = '" . $_GET['products_id'] . "' and
                                   xp.xsell_id = p.products_id and
                                   p.products_id = pd.products_id and
                                   pd.language_id = '" . $languages_id . "' and
                                   p.products_status = '1'
                             order by xp.products_id asc
                             limit " . MAX_DISPLAY_ALSO_PURCHASED);
      $num_products_xsell = tep_db_num_rows($xsell_query);
      if ($num_products_xsell >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
 
   <div class="moduletable-featuredProducts">
<h3>You Might Like</h3>
<div class="product-list row">
  
    
                                  
                                  
        
        
<?php
          $info_box_contents = array();
          new contentBoxHeading($info_box_contents);
          $row = 0;
          $col = 0;
          $info_box_contents = array();
          while ($extra_images = tep_db_fetch_array($xsell_query)) {
              $extra_images['products_name'] = tep_get_products_name($extra_images['products_id']);
              $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
              
              
   <div class="product-box col-md-4 col-sm-4">
<div class="product-img">
       <a class="product-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>



</div>
<h4> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . $extra_images['products_name'] . '</a></h4>




        

 

 <div class="price">' . $price . '</div>
    ' . $newArea . '               
 
 </div>
              
                     
                     
                     
                     
                     
                     
                     
                     
                     ');
              $col++;
              if ($col > 2) {
                  $col = 0;
                  $row++;
              }
          }
          if ($num_products_xsell < MAX_DISPLAY_ALSO_PURCHASED) {
              $mtm = rand();
              $xsell_cat_query = tep_db_query("select categories_id
                                   from " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                   where products_id = '" . $_GET['products_id'] . "'");
              $xsell_cat_array = tep_db_fetch_array($xsell_cat_query);
              $xsell_category = $xsell_cat_array['categories_id'];
              $new_limit = MAX_DISPLAY_ALSO_PURCHASED - $num_products_xsell;
              $xsell_prod_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_price, pd.products_name
                             from " . TABLE_PRODUCTS . " p,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " pc,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd
                             where pc.categories_id = '" . $xsell_category . "' and
                                   p.products_id != '" . $_GET['products_id'] . "' and
                                   pc.products_id = p.products_id and
                                   p.products_id = pd.products_id and
                                   pd.language_id = '" . $languages_id . "' and
                                   p.products_status = '1'
                             order by rand($mtm) desc
                             limit " . $new_limit);
              while ($extra_images = tep_db_fetch_array($xsell_prod_query)) {
                  $extra_images['products_name'] = tep_get_products_name($extra_images['products_id']);
                  $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
                  
             <div class="product-box col-md-4 col-sm-4">
<div class="product-img">
       <a class="product-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>



</div>
<h4> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . $extra_images['products_name'] . '</a></h4>




        

 

 <div class="price">' . $price . '</div>
    ' . $newArea . '               
 
 </div>
                         
                         
                         
                         
                         
                         ');
                  $col++;
                  if ($col > 2) {
                      $col = 0;
                      $row++;
                  }
              }
          }
          new contentBox($info_box_contents);
?>
    
<?php
      } else {
          $info_box_contents = array();
          new contentBoxHeading($info_box_contents);
          $row = 0;
          $col = 0;
          $info_box_contents = array();
          $mtm = rand();
          $xsell_cat_query = tep_db_query("select categories_id
                                         from " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                         where products_id = '" . $_GET['products_id'] . "'");
          $xsell_cat_array = tep_db_fetch_array($xsell_cat_query);
          $xsell_category = $xsell_cat_array['categories_id'];
          $new_limit = MAX_DISPLAY_ALSO_PURCHASED - $num_products_xsell;
          $xsell_prod_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_price, p.products_tax_class_id, p.products_price, p.products_model, m.manufacturers_name
                                          from ((" . TABLE_PRODUCTS . " p,
                                               " . TABLE_PRODUCTS_TO_CATEGORIES . " pc,
                                               " . TABLE_PRODUCTS_DESCRIPTION . " pd )
                         left join manufacturers m on p.manufacturers_id = m.manufacturers_id)
                                          where pc.categories_id = '" . $xsell_category . "' and
                                                pc.products_id = p.products_id and
                                                p.products_id != '" . $_GET['products_id'] . "' and
                                                p.products_id = pd.products_id and
                                                pd.language_id = '" . $languages_id . "' and
                                                p.products_status = '1'
                                          order by rand($mtm) desc
                                          limit " . MAX_DISPLAY_ALSO_PURCHASED);
      if (tep_db_num_rows($xsell_prod_query) >= MIN_DISPLAY_ALSO_PURCHASED) {
?>

   <div class="moduletable-featuredProducts">
<h3>You Might Like</h3>
<div class="product-list row">
  
      
<?php
          while ($extra_images = tep_db_fetch_array($xsell_prod_query)) {
              $extra_images['products_name'] = tep_get_products_name($extra_images['products_id']);
              if ($extra_images['products_price'] > 0) {
                  $newArea = '<a class="buy-it-now" href="' . tep_href_link('index.php', 'products_id=' . $extra_images['products_id']) . '&action=buy_now">Add to Cart</a>';
                  $price = '' . $currencies->display_price($extra_images['products_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '';
              } else {
                  $newArea = '<a class="buy-it-now" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">Read More</a>';
                  $price = '';
              }
              if (HIDE_PRICE_NON_LOGGED == "true" && empty($_SESSION['customer_id'])){
                $price = '';
                $newArea = '<a class="buy-it-now" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">Read More</a>';
              }
              $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
              
<div class="product-box col-md-4 col-sm-4">
<div class="product-img">
       <a class="product-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>



</div>
<h4> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . $extra_images['products_name'] . '</a></h4>




        

 

 <div class="price">' . $price . '</div>
    ' . $newArea . '               
 
 </div>
 


 
              
              ');
              $col++;
              if ($col > 2) {
                  $col = 0;
                  $row++;
              }
          }
          new contentBox($info_box_contents);
        }
?>
    
      </div> </div>  
<?php
      }
  }
?>
