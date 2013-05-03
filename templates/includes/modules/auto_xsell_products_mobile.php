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
	<ul style="margin-top:15px;" data-role="listview" data-theme="b" data-divider-theme="a" class="subFeaturesCollectionListing">
		
		<li data-role="list-divider">Related Products</li>
		
<?php
          $info_box_contents = array();
          new contentBoxHeading($info_box_contents);
          $row = 0;
          $col = 0;
          $info_box_contents = array();
          while ($extra_images = tep_db_fetch_array($xsell_query)) {
              $extra_images['products_name'] = tep_get_products_name($extra_images['products_id']);
              $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
              
              
             <li class="ui-li-has-thumb">
             <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">
             
           <div class="imageWrapper">
           
           ' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '
           
           
           </div>
           
           <h3 class="collectionItemTitle">' . $extra_images['products_name'] . '</h3>
           
           
           
          ' .
                     ((HIDE_PRICE_NON_LOGGED == "true" && empty($_SESSION['customer_id'])) ? '
                     
                     
                    <p class="collectionProductPrice">' . $currencies->display_price($extra_images['products_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</p>' : '') .
                      
           
                     '</a></li>
                     
                     
                     
                     
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
                  $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '<div class="productWrap item' . $i++ . '"><div class="pimg"><center><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></center></div><h4><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">' . $extra_images['products_name'] . '</a></h4>' .
                        ((HIDE_PRICE_NON_LOGGED == "true" && empty($_SESSION['customer_id'])) ? '<div class="price">' . $currencies->display_price($extra_images['products_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</div>' : '') .
                        '<div class="model">Model: <span> ' . $extra_images['products_model'] . '</span></div>
                         <div class="make">Make: <span>' . $extra_images['manufacturers_name'] . '</span></div>
                         <a class="readon_p" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">More Info</a>' .
                         ((HIDE_PRICE_NON_LOGGED == "true" && empty($_SESSION['customer_id'])) ? '<a class="button" href="' . tep_href_link('index.php', 'products_id=' . $extra_images['products_id']) . '&action=buy_now">Add to Cart</a>' : '') .
                         '</div>');
                  $col++;
                  if ($col > 2) {
                      $col = 0;
                      $row++;
                  }
              }
          }
          new contentBox($info_box_contents);
?>
      </ul>
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

	<ul style="margin-top:15px;" data-role="listview" data-theme="b" data-divider-theme="a" class="subFeaturesCollectionListing">
		<li data-role="list-divider">Related Products</li>
		
		
<?php
          while ($extra_images = tep_db_fetch_array($xsell_prod_query)) {
              $extra_images['products_name'] = tep_get_products_name($extra_images['products_id']);
              if ($extra_images['products_price'] > 0) {
                  $newArea = '';
                  $price = ' <p class="collectionProductPrice">' . $currencies->display_price($extra_images['products_price'], tep_get_tax_rate($extra_images['products_tax_class_id'])) . '</p>';
              } else {
                  $newArea = '';
                  $price = '';
              }
              if (HIDE_PRICE_NON_LOGGED == "true" && empty($_SESSION['customer_id'])){
                $price = '';
                $newArea = '';
              }
              $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
              
              
              
          <li class="ui-li-has-thumb">
          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $extra_images['products_id']) . '">
          
       <div class="imageWrapper">
       
       ' . tep_image(DIR_WS_IMAGES . $extra_images['products_image'], $extra_images['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '
       </div>
       
       <h3 class="collectionItemTitle">' . $extra_images['products_name'] . '</h3>
       
       
       
       
       ' . $price . '

       </a>
	    </li>
       
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
    </ul>
<?php
      }
  }
?>
