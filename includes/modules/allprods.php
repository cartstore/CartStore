<?php
/*
  $Id: allprods.php,v4.3 2003/06/09 22:49:59 hpdl Exp $
  All Products v3.0 MS 2.2 with Images http://www.cartstore.com/community/contributions,1501 

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if(($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>  
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
<?php
  $row = 0;
  $listing_query = tep_db_query($listing_split->sql_query);
  while($listing = tep_db_fetch_array($listing_query)) {
    $row++;
    $str = array();
    echo '
    <td align="center" width="33%" class="smallText">';
    if(PRODUCT_LIST_MODEL > 0)
      $str['model'] = $listing['products_model'] . '<br>';
      if(PRODUCT_LIST_NAME > 0) {
        if(isset($_GET['manufacturers_id'])) {
          $str['name'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '<br>';
        } else {
          $str['name'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '<br>';
        }
      }  
      if(PRODUCT_LIST_MANUFACTURER > 0)
      $str['man'] =  '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '<br>';
      if(PRODUCT_LIST_PRICE > 0) {
        if(tep_not_null($listing['specials_new_products_price'])) {
          $str['price'] =  '<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span><br>';
        } else {
          $str['price'] =   $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '<br>';
        }
      }
      if(PRODUCT_LIST_QUANTITY > 0)
      $str['qty'] =  $listing['products_quantity'] . '<br>';
      if(PRODUCT_LIST_WEIGHT > 0)
      $str['weight'] = $listing['products_weight'] . '<br>';
      if(PRODUCT_LIST_IMAGE > 0) {
        if(isset($_GET['manufacturers_id'])) {
          $str['image'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>';
        } else {
          $str['image'] =  '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>';
        }
      }
      if(PRODUCT_LIST_BUY_NOW > 0)
      $str['buynow'] = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '<br>';
      for($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            echo $str['model'];
            break;
          case 'PRODUCT_LIST_NAME':
            echo $str['name'];
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            echo $str['man'];
            break;
          case 'PRODUCT_LIST_PRICE':
            echo $str['price'];
            break;
          case 'PRODUCT_LIST_QUANTITY':
            echo $str['qty'];
            break;
          case 'PRODUCT_LIST_WEIGHT':
            echo $str['weight'];
            break;
          case 'PRODUCT_LIST_IMAGE':
            echo $str['image'];
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            echo $str['buynow'];
            break;
        }
      }
      echo '
    </td>';
    if((($row / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($row / MAX_DISPLAY_CATEGORIES_PER_ROW))) {
?>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
<?php
    }
  }
?>
  </tr>
</table> 
<?php  
  if(($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
