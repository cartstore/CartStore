<?php

  require('includes/application_top.php');
  require('includes/functions/categories_description.php');
  global $customer_group_id;
  if (!isset($customer_group_id)) {
      $customer_group_id = '0';
  } //if (!isset($customer_group_id))
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
      $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $cateqories_products = tep_db_fetch_array($categories_products_query);
      if ($cateqories_products['total'] > 0) {
          $category_depth = 'products';
      } //if ($cateqories_products['total'] > 0)
      else {
          $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
          $category_parent = tep_db_fetch_array($category_parent_query);
          if ($category_parent['total'] > 0) {
              $category_depth = 'nested';
          } //if ($category_parent['total'] > 0)
          else {
              $category_depth = 'products';
          } //else
      } //else
  } //if (isset($cPath) && tep_not_null($cPath))
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
//  require_once("checkout_by_amazon/checkout_by_amazon_constants.php");
//  if ($_GET['amznPmtsReqId']) {
//      $cart->reset();
//  } //if ($_GET['amznPmtsReqId'])

  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- body //-->

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
  <td><?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <?php
  if ($category_depth == 'nested') {
      $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
      $category = tep_db_fetch_array($category_query);
?>
    <h1>
      <?php
      print($category['categories_name']);
?>
    </h1>
    <span class="breadcrumbs"><a class="linkup" href="index.php">Home</a> >>
    <?php
      echo $breadcrumb->trail(' >> ');
?>
    </span>
    <?php
      $cat_desc = "";
      if (isset($_REQUEST["cPath"]) && $_REQUEST["cPath"] != "") {
          $cat_desc = "";
          $c_id = $_REQUEST["cPath"];
          if ($c_id != "") {
              $cids = explode("_", $c_id);
              $cnt = count($cids);
              if ($cnt > 0) {
                  $mc_id = $cids[$cnt - 1];
              } //if ($cnt > 0)
          } //if ($c_id != "")
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $category_header = tep_get_category_heading_title((int)$sc_id);
              if (strlen($category_header) == 0) {
                  $category_header = $categories['categories_name'];
              } //if (strlen($category_header) == 0)
              if ($cnt > 0) {
                  $current_categorydesc_query = tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$mc_id . "'");
                  $current_categorydesc = tep_db_fetch_array($current_categorydesc_query);
                  $cat_desc = '<div class="cat_desc">' . $current_categorydesc["categories_htc_description"] . '</div>' . "\n";
              } //if ($cnt > 0)
          } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          echo $cat_desc;
      } //if (isset($_REQUEST["cPath"]) && $_REQUEST["cPath"] != "")
?>
    <ul id="categories">
    <li>
      <?php
      print($maincat);
      if (isset($cPath) && strpos('_', $cPath)) {
          $category_links = array_reverse($cPath_array);
          for ($i = 0, $n = sizeof($category_links); $i < $n; $i++) {
              $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
              $categories = tep_db_fetch_array($categories_query);
              if ($categories['total'] < 1) {
              } //if ($categories['total'] < 1)
              else {
                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and " . (YMM_FILTER_CATEGORIES_LISTING == 'Yes' ? YMM_get_categories_where((int)$category_links[$i], $YMM_where) : '') . " c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
                  break;
              } //else
          } //for ($i = 0, $n = sizeof($category_links); $i < $n; $i++)
      } //if (isset($cPath) && strpos('_', $cPath))
      else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_htc_description, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and " . ((YMM_FILTER_CATEGORIES_LISTING == 'Yes' && $YMM_where != "") ? YMM_get_categories_where((int)$current_category_id, $YMM_where) : '') . " c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
      } //else
      $number_of_categories = tep_db_num_rows($categories_query);
      $rows = 0;
      while ($categories = tep_db_fetch_array($categories_query)) {
          $rows++;
          $cPath_new = tep_get_path($categories['categories_id']);
          $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
          $category_header = "";
          $category_description = "";
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $category_header = tep_get_category_heading_title((int)$categories['categories_id']);
              if (strlen($category_header) == 0) {
                  $category_header = $categories['categories_name'];
              } //if (strlen($category_header) == 0)
          } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          if (tep_count_products_in_category((int)$categories['categories_id']) > 0) {
              echo '

<div class="cartstore_cat_head ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all"><h3><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a></h3></div><ul id="sublevel2" >';
              echo ' <a class="cat_image" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a> ';
              $categories_query12 = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_htc_description, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$categories['categories_id'] . "' and " . ((YMM_FILTER_CATEGORIES_LISTING == 'Yes' && $YMM_where != "") ? YMM_get_categories_where((int)$categories['categories_id'], $YMM_where) : '') . " c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
              $count = 0;
              while ($categories12 = tep_db_fetch_array($categories_query12)) {
                  if (tep_count_products_in_category((int)$categories12['categories_id']) > 0 || tep_has_category_subcategories($categories12['categories_id']))
                      print('<li><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_path($categories12['categories_id'])) . '">' . $categories12['categories_name'] . '</a></li>');
              } //while ($categories12 = tep_db_fetch_array($categories_query12))
              print('

</ul><div class="clear"></div>

');
              $category_header = "";
              $category_description = "";
              if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
                  $category_header = tep_get_category_heading_title((int)$categories['categories_id']);
                  if (strlen($category_header) == 0) {
                      $category_header = $categories['categories_name'];
                  } //if (strlen($category_header) == 0)
              } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
              if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
                  $category_description = tep_get_category_description((int)$categories['categories_id']);
              } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
              echo '';
              echo '' . "\n";
              if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
                  echo '' . "\n";
                  echo '' . "\n";
              } //if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories))
          } //if (tep_count_products_in_category((int)$categories['categories_id']) > 0)
      } //while ($categories = tep_db_fetch_array($categories_query))
      print('</li></ul>');
      $products_in_cat_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$current_category_id . "'");
      $products_in_cat = tep_db_fetch_array($products_in_cat_query);
      if ($products_in_cat['total'] == 0) {
          $new_products_category_id = $current_category_id;
          include_once(DIR_WS_MODULES . FILENAME_FEATURED);
      } //if ($products_in_cat['total'] == 0)
      print('');
?>
      <?php
  } //if ($category_depth == "s_22")
  elseif ($category_depth == 'products' || isset($_GET['manufacturers_id'])) {
      $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL, 'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME, 'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER, 'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE, 'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY, 'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT, 'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE, 'PRODUCT_LIST_MULTIPLE' => PRODUCT_LIST_MULTIPLE, 'PRODUCT_LIST_BUY_NOW_MULTIPLE' => PRODUCT_LIST_BUY_NOW_MULTIPLE, 'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
      asort($define_list);
      $column_list = array();
      reset($define_list);
      while (list($key, $value) = each($define_list)) {
          if ($value > 0)
              $column_list[] = $key;
      } //while (list($key, $value) = each($define_list))
      if (!tep_session_is_registered('sppc_customer_group_id')) {
          $customer_group_id = '0';
      } //if (!tep_session_is_registered('sppc_customer_group_id'))
      else {
          $customer_group_id = $sppc_customer_group_id;
      } //else
      if ($customer_group_id == '0') {
          tep_db_check_age_specials_retail_table();
      } //if ($customer_group_id == '0')
      $status_product_prices_table = false;
      $status_need_to_get_prices = false;
      if ((isset($_GET['sort'])) && (preg_match('/[1-8][ad]/', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0') {
          $_sort_col = substr($_GET['sort'], 0, 1);
          if ($column_list[$_sort_col - 1] == 'PRODUCT_LIST_PRICE') {
              $status_need_to_get_prices = true;
          } //if ($column_list[$_sort_col - 1] == 'PRODUCT_LIST_PRICE')
      } //if ((isset($_GET['sort'])) && (preg_match('/[1-8][ad]/', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0')
      if ($status_need_to_get_prices == true && $customer_group_id != '0') {
          $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES . $customer_group_id;
          tep_db_check_age_products_group_prices_cg_table($customer_group_id);
          $status_product_prices_table = true;
      } //if ($status_need_to_get_prices == true && $customer_group_id != '0')
      $select_column_list = '';
      for ($i = 0, $n = sizeof($column_list); $i < $n; $i++) {
          if (($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_PRICE')) {
              continue;
          } //if (($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_PRICE'))
          switch ($column_list[$i]) {
              case 'PRODUCT_LIST_MODEL':
                  $select_column_list .= 'p.products_model, ';
                  break;
              case 'PRODUCT_LIST_NAME':
                  $select_column_list .= 'pd.products_name, ';
                  break;
              case 'PRODUCT_LIST_MANUFACTURER':
                  $select_column_list .= 'm.manufacturers_name, ';
                  break;
              case 'PRODUCT_LIST_QUANTITY':
                  $select_column_list .= 'p.products_quantity, ';
                  break;
              case 'PRODUCT_LIST_IMAGE':
                  $select_column_list .= 'p.products_image, ';
                  break;
              case 'PRODUCT_LIST_WEIGHT':
                  $select_column_list .= 'p.products_weight, ';
                  break;
          } //switch ($column_list[$i])
      } //for ($i = 0, $n = sizeof($column_list); $i < $n; $i++)
      if (isset($_GET['manufacturers_id'])) {
          if ($current_category_id != "") {
              $table_cat = ', ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c';
              $condition = 'and p2c.categories_id = ' . (int)$current_category_id . ' and pd.products_id = p2c.products_id';
          } //if ($current_category_id != "")
          if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
              if ($status_product_prices_table == true) {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, pd.products_short, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
              } //if ($status_product_prices_table == true)
              else {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, p.products_price, p.products_tax_class_id, pd.products_short, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
              } //else
          } //if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id']))
          else {
              if ($status_product_prices_table == true) {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, pd.products_short, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
              } //if ($status_product_prices_table == true)
              else {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, p.products_price, p.products_tax_class_id, pd.products_short, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m " . $table_cat . ")  left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' " . $condition . "";
              } //else
          } //else
      } //if (isset($_GET['manufacturers_id']))
      else {
          if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
              if ($status_product_prices_table == true) {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, pd.products_short, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from (" . TABLE_PRODUCTS . " p) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
              } //if ($status_product_prices_table == true)
              else {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, p.products_price, p.products_tax_class_id, pd.products_short, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c) left join " . TABLE_SPECIALS_RETAIL_PRICES . " s using(products_id) where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
              } //else
          } //if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id']))
          else {
              if ($status_product_prices_table == true) {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, pd.products_short, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from ((" . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
              } //if ($status_product_prices_table == true)
              else {
                  $listing_sql = "select " . $select_column_list . " p.products_id,p.map_price,pd.products_url, p.msrp_price,pd.products_info_title, p.manufacturers_id, p.products_price, p.products_tax_class_id, pd.products_short, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p2c.products_id = s.products_id where " . (YMM_FILTER_PRODUCT_LISTING == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
              } //else
          } //else
      } //else
      $sort_col = $_GET['sort_id'];
      $listing_sql .= ' order by ';
      switch ($sort_col) {
          case 'high':
              $listing_sql .= "products_price desc ";
              break;
          case 'low':
              $listing_sql .= "products_price asc ";
              break;
          case 'title':
              $listing_sql .= "pd.products_name ";
              break;
          default:
              case 'sortorder':
                  $listing_sql .= "p.pSortOrder ";
                  break;
          default:
              $listing_sql .= "p.pSortOrder ";
              break;
      } //switch ($sort_col)
?>
      <?php
      if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
          $category_header = tep_get_category_heading_title((int)$current_category_id);
          if (strlen($category_header) == 0) {
              $category_header = HEADING_TITLE;
          } //if (strlen($category_header) == 0)
      } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
      if (PRODUCT_LIST_FILTER > 1) {
          if (isset($_GET['manufacturers_id'])) {
              $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
              print $filterlist_sql;
          } //if (isset($_GET['manufacturers_id']))
          else {
              $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
          } //else
          $filterlist_query = tep_db_query($filterlist_sql);
          if (tep_db_num_rows($filterlist_query) > 1) {
              echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '';
              if (isset($_GET['manufacturers_id'])) {
                  echo tep_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
                  $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
              } //if (isset($_GET['manufacturers_id']))
              else {
                  echo tep_draw_hidden_field('cPath', $cPath);
                  $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
              } //else
              echo tep_draw_hidden_field('sort', $_GET['sort']);
              while ($filterlist = tep_db_fetch_array($filterlist_query)) {
                  $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
              } //while ($filterlist = tep_db_fetch_array($filterlist_query))
              echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
              echo '</form>' . "\n";
          } //if (tep_db_num_rows($filterlist_query) > 1)
      } //if (PRODUCT_LIST_FILTER > 1)
      $image = DIR_WS_IMAGES . 'table_background_list.gif';
      if (isset($_GET['manufacturers_id'])) {
          $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
          $image = tep_db_fetch_array($image);
          $image = $image['manufacturers_image'];
      } //if (isset($_GET['manufacturers_id']))
      elseif ($current_category_id) {
          $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
          $image = tep_db_fetch_array($image);
          $image = $image['categories_image'];
      } //elseif ($current_category_id)
?>
      <?php
      $category_query = tep_db_query("select cd.categories_name, c.categories_image,cd.categories_htc_description,c.altProdDisplay from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
      $category = tep_db_fetch_array($category_query);
?>
      <h1>
        <?php
      print($category['categories_name']);
?>
      </h1>
      <a class="linkup" href="index.php">Home</a> >>
      <?php
      echo $breadcrumb->trail(' >> ');
?>
      <?php
      $cat_desc = "";
      if (isset($_REQUEST["cPath"]) && $_REQUEST["cPath"] != "") {
          $cat_desc = "";
          $c_id = $_REQUEST["cPath"];
          if ($c_id != "") {
              $cids = explode("_", $c_id);
              $cnt = count($cids);
              if ($cnt > 0) {
                  $mc_id = $cids[$cnt - 1];
              } //if ($cnt > 0)
          } //if ($c_id != "")
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $category_header = tep_get_category_heading_title((int)$sc_id);
              if (strlen($category_header) == 0) {
                  $category_header = $categories['categories_name'];
              } //if (strlen($category_header) == 0)
              if ($cnt > 0) {
                  $current_categorydesc_query = tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$mc_id . "'");
                  $current_categorydesc = tep_db_fetch_array($current_categorydesc_query);
                  $cat_desc = '<div class="category_desc">' . $current_categorydesc["categories_htc_description"] . '</div>' . "\n";
              } //if ($cnt > 0)
          } //if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          echo $cat_desc;
      } //if (isset($_REQUEST["cPath"]) && $_REQUEST["cPath"] != "")
?>
      <div class="clear"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <h1>
        <?php
      if ($_GET['manufacturers_id'] > 0 && $category['categories_name'] == "") {
          $manufacture_sql = "select manufacturers_name from " . TABLE_MANUFACTURERS . " where  manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
          $manufacture_query = tep_db_query($manufacture_sql);
          $manufacture = tep_db_fetch_array($manufacture_query);
          print $manufacture["manufacturers_name"];
      } //if ($_GET['manufacturers_id'] > 0 && $category['categories_name'] == "")
      else
          print($category['categories_name']);
?>
      </h1>
      <span class="breadcrumbs"><a class="linkup" href="index.php">Home</a> >>
      <?php
      echo $breadcrumb->trail(' >> ');
?>
      </span>
      <div class="cat_desc"><span class="cat_image_wrap">
        <?php
      print(tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], CATEGORY_IMAGE_WIDTH, CATEGORY_IMAGE_HEIGHT));
?>
        </span>
        <?php
      print($category['categories_htc_description']);
?>
      </div>
      <div class="clear"></div>
      <?php
      if ($category['altProdDisplay'] == 1) {
          include(DIR_WS_MODULES . 'product_listing_col_alt.php');
      } //if ($category['altProdDisplay'] == 1)
      else
          include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);

      } else
      {
?>

<?php

   require(DIR_FS_CATALOG . 'templates/includes/sts_templates/index_body.php');
?>


      <?php
      }
?>

      <?php
      require(DIR_WS_INCLUDES . 'column_right.php');
?>

      <?php
      require(DIR_WS_INCLUDES . 'footer.php');
?>

    <?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>