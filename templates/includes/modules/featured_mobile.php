<?php
  $configuration_query = tep_db_query("SELECT configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (!defined($configuration['cfgKey']))
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }
  if (FEATURED_PRODUCTS_DISPLAY == 'true') {
?>
<!-- body //-->
<?php
      $featured_products_category_id = (isset($new_products_category_id) ? $new_products_category_id : 0);
      if ((!isset($featured_products_category_id)) || ($featured_products_category_id == '0')) {
          $featured_products_query_raw = "SELECT p.products_id,pd.products_url, pd.products_name,p.map_price, p.msrp_price, pd.products_description, pd.products_short, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price,p.products_model, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks from ((" . TABLE_PRODUCTS . " p) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' where " . (YMM_FILTER_NEW_PRODUCTS == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_featured = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS;
      } else {

          $featured_products_query_raw = "SELECT distinct p.products_id,pd.products_url, pd.products_name,p.map_price, p.msrp_price, p.products_image, p.products_tax_class_id, pd.products_description, pd.products_short, s.status as specstat, s.specials_new_products_price,p.products_model, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks from ((" . TABLE_PRODUCTS . " p) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' where " . (YMM_FILTER_NEW_PRODUCTS == 'Yes' ? $YMM_where : '') . " p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$featured_products_category_id . "' and p.products_status = '1' and p.products_featured = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS;
      }
      $featured_products_query = tep_db_query($featured_products_query_raw);
      while ($featured_products = tep_db_fetch_array($featured_products_query)) {
          //$featured_products_array[] = array('id' => $featured_products['products_id'], 'name' => $featured_products['products_name'], 'map_price' => $featured_products['map_price'], 'msrp_price' => $featured_products['msrp_price'], 'products_model' => $featured_products['products_model'], 'description' => $featured_products['products_description'], 'shortdescription' => $featured_products['products_short'], 'image' => $featured_products['products_image'], 'price' => $featured_products['products_price'], 'products_url' => $featured_products['products_url'], 'products_price' => $featured_products['products_price'], 'specials_price' => $featured_products['specials_new_products_price'], 'specials_new_products_price' => $featured_products['specials_new_products_price'], 'tax_class_id' => $featured_products['products_tax_class_id'], 'manufacturer' => $featured_products['manufacturers_name']);
          $featured_products_array[] = array('id' => $featured_products['products_id'], 'name' => $featured_products['products_name'], 'map_price' => $featured_products['map_price'], 'msrp_price' => $featured_products['msrp_price'], 'products_model' => $featured_products['products_model'], 'description' => $featured_products['products_description'], 'shortdescription' => $featured_products['products_short'], 'image' => $featured_products['products_image'], 'price' => $featured_products['products_price'], 'products_url' => $featured_products['products_url'], 'products_price' => $featured_products['products_price'], 'specials_price' => $featured_products['specials_new_products_price'], 'specials_new_products_price' => $featured_products['specials_new_products_price'], 'tax_class_id' => $featured_products['products_tax_class_id']);
      }
      require(DIR_WS_MODULES . FILENAME_FEATURED_PRODUCTS_MOBILE);
  } else {

      if (FEATURED_PRODUCTS_NEW_DISPLAY == 'true') {
          include(DIR_WS_MODULES . FILENAME_FEATURED_PRODUCTS_MOBILE);
      }
  }
?>