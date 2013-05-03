<?php
  require('includes/application_top.php');
  link_files_variable('categories_image');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (preg_match("/(insert|update|setflag)/i", $action))
      include_once('includes/reset_seo_cache.php');
  if (tep_not_null($action)) {
      switch ($action) {
          case 'setflag':
              if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {
                  if (isset($_GET['pID'])) {
                      tep_set_product_status($_GET['pID'], $_GET['flag']);
                  }
                  if (USE_CACHE == 'true') {
                      tep_reset_cache_block('categories');
                      tep_reset_cache_block('also_purchased');
                  }
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
              break;
          case 'setflag_featured':
              if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {
                  if (isset($_GET['pID'])) {
                      tep_set_product_featured($_GET['pID'], $_GET['flag']);
                  }
                  if (USE_CACHE == 'true') {
                      tep_reset_cache_block('categories');
                      tep_reset_cache_block('also_purchased');
                  }
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
              break;
          case 'insert_category':
          case 'update_category':
              if (isset($_POST['categories_id']))
                  $categories_id = tep_db_prepare_input($_POST['categories_id']);
              $sort_order = tep_db_prepare_input($_POST['sort_order']);
              $altProdDisplay = tep_db_prepare_input($_POST['altProdDisplay']);
              $sql_data_array = array('sort_order' => $sort_order, 'altProdDisplay' => $altProdDisplay);
              if ($action == 'insert_category') {
                  $insert_sql_data = array('parent_id' => $current_category_id, 'date_added' => 'now()');
                  $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                  tep_db_perform(TABLE_CATEGORIES, $sql_data_array);
                  $categories_id = tep_db_insert_id();
              } elseif ($action == 'update_category') {
                  $update_sql_data = array('last_modified' => 'now()');
                  $sql_data_array = array_merge($sql_data_array, $update_sql_data);
                  tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
              }
              $languages = tep_get_languages();
              for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $categories_name_array = $_POST['categories_name'];
                  $categories_seo_url_array = $_POST['categories_seo_url'];
                  $categories_htc_title_array = $_POST['categories_htc_title_tag'];
                  $categories_htc_desc_array = $_POST['categories_htc_desc_tag'];
                  $categories_htc_keywords_array = $_POST['categories_htc_keywords_tag'];
                  $categories_htc_description_array = $_POST['categories_htc_description'];
                  $language_id = $languages[$i]['id'];
                  $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]), 'categories_seo_url' => tep_db_prepare_input($categories_seo_url_array[$language_id]), 'categories_htc_title_tag' => (tep_not_null($categories_htc_title_array[$language_id]) ? tep_db_prepare_input($categories_htc_title_array[$language_id]) : tep_db_prepare_input($categories_name_array[$language_id])), 'categories_htc_desc_tag' => (tep_not_null($categories_htc_desc_array[$language_id]) ? tep_db_prepare_input($categories_htc_desc_array[$language_id]) : tep_db_prepare_input($categories_name_array[$language_id])), 'categories_htc_keywords_tag' => (tep_not_null($categories_htc_keywords_array[$language_id]) ? tep_db_prepare_input($categories_htc_keywords_array[$language_id]) : tep_db_prepare_input($categories_name_array[$language_id])), 'categories_htc_description' => tep_db_prepare_input($categories_htc_description_array[$language_id]));
                  if ($action == 'insert_category') {
                      $insert_sql_data = array('categories_id' => $categories_id, 'language_id' => $languages[$i]['id']);
                      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                      tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
                  } elseif ($action == 'update_category') {
                      tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
                  }
              }

              if (isset($categories_image) && $categories_image !== "") {
                  if($categories_image['name']=='' && is_file(DIR_FS_CATALOG_IMAGES.$_POST['existing_categories_image'])){
                      tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $_POST['existing_categories_image'] . "' where categories_id = '" . (int)$categories_id . "'");
                  }elseif ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
                      tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
                }
              }
              if($_POST['delete_categories_image']=='on' && $action=='update_category'){
                  tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = '" . (int)$categories_id . "'");
                  unlink(DIR_FS_CATALOG_IMAGES.$_POST['existing_categories_image']);
              }
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('categories');
                  tep_reset_cache_block('also_purchased');
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
              break;
          case 'delete_category_confirm':
              if (isset($_POST['categories_id'])) {
                  $categories_id = tep_db_prepare_input($_POST['categories_id']);
                  if($categories_id == 0) {
                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
                    break;
                  }
                  $categories = tep_get_category_tree($categories_id, '', '0', '', true);
                  $products = array();
                  $products_delete = array();
                  for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
                      $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");
                      while ($product_ids = tep_db_fetch_array($product_ids_query)) {
                          $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
                      }
                  }
                  reset($products);
                  while (list($key, $value) = each($products)) {
                      $category_ids = '';
                      for ($i = 0, $n = sizeof($value['categories']); $i < $n; $i++) {
                          $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
                      }
                      $category_ids = substr($category_ids, 0, -2);
                      $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
                      $check = tep_db_fetch_array($check_query);
                      if ($check['total'] < '1') {
                          $products_delete[$key] = $key;
                      }
                  }
                  tep_set_time_limit(0);
                  for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
                      tep_remove_category($categories[$i]['id']);
                  }
                  reset($products_delete);
                  while (list($key) = each($products_delete)) {
                      tep_remove_product($key);
                  }
              }
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('categories');
                  tep_reset_cache_block('also_purchased');
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
              break;
          case 'delete_product_confirm':
              if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
                  $product_id = tep_db_prepare_input($_POST['products_id']);
                  mysql_query("delete from products_ymm where products_id=$product_id");
                  $product_categories = $_POST['product_categories'];
                  for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                      tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
                  }
                  $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
                  $product_categories = tep_db_fetch_array($product_categories_query);
                  if ($product_categories['total'] == '0') {
                      tep_remove_product($product_id);
                      tep_db_query("delete from " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$product_id);
                  }
              }
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('categories');
                  tep_reset_cache_block('also_purchased');
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
              break;
          case 'move_category_confirm':
              if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
                  $categories_id = tep_db_prepare_input($_POST['categories_id']);
                  $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
                  $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));
                  if (in_array($categories_id, $path)) {
                      $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');
                      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
                  } else {
                      tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
                      if (USE_CACHE == 'true') {
                          tep_reset_cache_block('categories');
                          tep_reset_cache_block('also_purchased');
                      }

                      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
                  }
              }
              break;
          case 'move_product_confirm':
              $products_id = tep_db_prepare_input($_POST['products_id']);
              $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
              $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
              $duplicate_check = tep_db_fetch_array($duplicate_check_query);
              if ($duplicate_check['total'] < 1)
                  tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('categories');
                  tep_reset_cache_block('also_purchased');
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
              break;
          case 'insert_product':
          case 'update_product':
              if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
                  $action = 'new_product';
              } else {
                  if (isset($_GET['pID']))
                  $products_id = tep_db_prepare_input($_GET['pID']);
                  if (isset($_GET['pID'])) $sr_oldProductData = tep_db_fetch_array(tep_db_query("select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'"));

                  $products_date_available = tep_db_prepare_input($_POST['products_date_available']);
                  $pSortOrder = tep_db_prepare_input($_POST['pSortOrder']);
                  $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';
                  $sql_data_array = array('products_quantity' => tep_db_prepare_input($_POST['products_quantity']), 'products_model' => tep_db_prepare_input($_POST['products_model']), 'products_mpn' => tep_db_prepare_input($_POST['products_mpn']), 'products_gtin' => tep_db_prepare_input($_POST['products_gtin']), 'vendors_prod_id' => tep_db_prepare_input($_POST['vendors_prod_id']), 'map_price' => tep_db_prepare_input($_POST['map_price']), 'msrp_price' => tep_db_prepare_input($_POST['msrp_price']), 'vendors_product_price' => tep_db_prepare_input($_POST['vendors_product_price']), 'vendors_id' => tep_db_prepare_input($_POST['vendors_id']), 'vendors_prod_comments' => tep_db_prepare_input($_POST['vendors_prod_comments']), 'products_price' => tep_db_prepare_input($_POST['products_price']), 'products_price1' => tep_db_prepare_input($_POST['products_price1']), 'products_price2' => tep_db_prepare_input($_POST['products_price2']), 'products_price3' => tep_db_prepare_input($_POST['products_price3']), 'products_price4' => tep_db_prepare_input($_POST['products_price4']), 'products_price5' => tep_db_prepare_input($_POST['products_price5']), 'products_price6' => tep_db_prepare_input($_POST['products_price6']), 'products_price7' => tep_db_prepare_input($_POST['products_price7']), 'products_price8' => tep_db_prepare_input($_POST['products_price8']), 'products_price1_qty' => (($i = tep_db_prepare_input($_POST['products_price1_qty'])) < 0) ? 0 : $i, 'products_price2_qty' => (($i = tep_db_prepare_input($_POST['products_price2_qty'])) < 0) ? 0 : $i, 'products_price3_qty' => (($i = tep_db_prepare_input($_POST['products_price3_qty'])) < 0) ? 0 : $i, 'products_price4_qty' => (($i = tep_db_prepare_input($_POST['products_price4_qty'])) < 0) ? 0 : $i, 'products_price5_qty' => (($i = tep_db_prepare_input($_POST['products_price5_qty'])) < 0) ? 0 : $i, 'products_price6_qty' => (($i = tep_db_prepare_input($_POST['products_price6_qty'])) < 0) ? 0 : $i, 'products_price7_qty' => (($i = tep_db_prepare_input($_POST['products_price7_qty'])) < 0) ? 0 : $i, 'products_price8_qty' => (($i = tep_db_prepare_input($_POST['products_price8_qty'])) < 0) ? 0 : $i, 'products_qty_blocks' => (($i = tep_db_prepare_input($_POST['products_qty_blocks'])) < 1) ? 1 : $i, 'products_date_available' => $products_date_available, 'pSortOrder' => $pSortOrder, 'products_weight' => tep_db_prepare_input($_POST['products_weight']), 'products_featured' => tep_db_prepare_input($_POST['products_featured']), 'products_status' => tep_db_prepare_input($_POST['products_status']), 'products_special' => tep_db_prepare_input($_POST['products_special']), 'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']), 'manufacturers_id' => tep_db_prepare_input($_POST['manufacturers_id']));
                  if (isset($_POST['products_image']) && tep_not_null($_POST['products_image']) && ($_POST['products_image'] != 'none')) {
                      $sql_data_array['products_image'] = tep_db_prepare_input($_POST['products_image']);
                  }
                  if (isset($_POST['products_image_2']) && tep_not_null($_POST['products_image_2']) && ($_POST['products_image_2'] != 'none')) {
                      $sql_data_array['product_image_2'] = tep_db_prepare_input($_POST['products_image_2']);
                  }
                  if (isset($_POST['products_image_3']) && tep_not_null($_POST['products_image_3']) && ($_POST['products_image_3'] != 'none')) {
                      $sql_data_array['product_image_3'] = tep_db_prepare_input($_POST['products_image_3']);
                  }
                  if (isset($_POST['products_image_4']) && tep_not_null($_POST['products_image_4']) && ($_POST['products_image_4'] != 'none')) {
                      $sql_data_array['product_image_4'] = tep_db_prepare_input($_POST['products_image_4']);
                  }
                  if (isset($_POST['products_image_5']) && tep_not_null($_POST['products_image_5']) && ($_POST['products_image_5'] != 'none')) {
                      $sql_data_array['product_image_5'] = tep_db_prepare_input($_POST['products_image_5']);
                  }
                  if (isset($_POST['products_image_6']) && tep_not_null($_POST['products_image_6']) && ($_POST['products_image_6'] != 'none')) {
                      $sql_data_array['product_image_6'] = tep_db_prepare_input($_POST['products_image_6']);
                  }
              $products_image = new upload('products_image');
              $products_image_2 = new upload('products_image_2');
              $products_image_3 = new upload('products_image_3');
              $products_image_4 = new upload('products_image_4');
              $products_image_5 = new upload('products_image_5');
              $products_image_6 = new upload('products_image_6');
              $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_2->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_3->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_4->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_5->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_6->set_destination(DIR_FS_CATALOG_IMAGES);
              if ($products_image->parse() && $products_image->save()) {
                  $sql_data_array['products_image'] = $products_image->filename;
              } else {
                  $sql_data_array['products_image'] = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
              }
              if ($products_image_2->parse() && $products_image_2->save()) {
                  $sql_data_array['product_image_2'] = $products_image_2->filename;
              } else {
                  $sql_data_array['product_image_2'] = (isset($_POST['products_previous_image_2']) ? $_POST['products_previous_image_2'] : '');
              }
              if ($products_image_3->parse() && $products_image_3->save()) {
                  $sql_data_array['product_image_3'] = $products_image_3->filename;
              } else {
                  $sql_data_array['product_image_3'] = (isset($_POST['products_previous_image_3']) ? $_POST['products_previous_image_3'] : '');
              }
              if ($products_image_4->parse() && $products_image_4->save()) {
                  $sql_data_array['product_image_4'] = $products_image_4->filename;
              } else {
                  $sql_data_array['product_image_4'] = (isset($_POST['products_previous_image_4']) ? $_POST['products_previous_image_4'] : '');
              }
              if ($products_image_5->parse() && $products_image_5->save()) {
                  $sql_data_array['product_image_5'] = $products_image_5->filename;
              } else {
                  $sql_data_array['product_image_5'] = (isset($_POST['products_previous_image_5']) ? $_POST['products_previous_image_5'] : '');
              }
              if ($products_image_6->parse() && $products_image_6->save()) {
                  $sql_data_array['product_image_6'] = $products_image_6->filename;
              } else {
                  $sql_data_array['product_image_6'] = (isset($_POST['products_previous_image_6']) ? $_POST['products_previous_image_6'] : '');
              }
                  if ($action == 'insert_product') {
                      $insert_sql_data = array('products_date_added' => 'now()');
                      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                      tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
                      require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php');
                      $products_id = tep_db_insert_id();
                      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
                      while ($customers_group = tep_db_fetch_array($customers_group_query)) {
                          $attributes_query = tep_db_query("select customers_group_id, customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where ((products_id = '" . $products_id . "') && (customers_group_id = " . $customers_group['customers_group_id'] . ")) order by customers_group_id");
                          $attributes = tep_db_fetch_array($attributes_query);
                          if (tep_db_num_rows($attributes_query) > 0) {
                              if ($_POST['sppcoption'][$customers_group['customers_group_id']]) {
                                  if (($_POST['sppcprice'][$customers_group['customers_group_id']] != $attributes['customers_group_price']) && ($attributes['customers_group_id'] == $customers_group['customers_group_id'])) {
                                      tep_db_query("update " . TABLE_PRODUCTS_GROUPS . " set customers_group_price = '" . $_POST['sppcprice'][$customers_group['customers_group_id']] . "' where customers_group_id = '" . $attributes['customers_group_id'] . "' and products_id = '" . $products_id . "'");
                                      $attributes = tep_db_fetch_array($attributes_query);
                                  } elseif (($_POST['sppcprice'][$customers_group['customers_group_id']] == $attributes['customers_group_price'])) {
                                      $attributes = tep_db_fetch_array($attributes_query);
                                  }
                              } else {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customers_group['customers_group_id'] . "' and products_id = '" . $products_id . "'");
                                  $attributes = tep_db_fetch_array($attributes_query);
                              }
                          } elseif (($_POST['sppcoption'][$customers_group['customers_group_id']]) && ($_POST['sppcprice'][$customers_group['customers_group_id']] != '')) {
                              tep_db_query("insert into " . TABLE_PRODUCTS_GROUPS . " (products_id, customers_group_id, customers_group_price) values ('" . $products_id . "', '" . $customers_group['customers_group_id'] . "', '" . $_POST['sppcprice'][$customers_group['customers_group_id']] . "')");
                              $attributes = tep_db_fetch_array($attributes_query);
                          }
                      }
                  } elseif ($action == 'update_product') {
                      $update_sql_data = array('products_last_modified' => 'now()');
                      $sql_data_array = array_merge($sql_data_array, $update_sql_data);
                      tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
                      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
                      while ($customers_group = tep_db_fetch_array($customers_group_query)) {
                          $attributes_query = tep_db_query("select customers_group_id, customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where ((products_id = '" . $products_id . "') && (customers_group_id = " . $customers_group['customers_group_id'] . ")) order by customers_group_id");
                          $attributes = tep_db_fetch_array($attributes_query);
                          if (tep_db_num_rows($attributes_query) > 0) {
                              if ($_POST['sppcoption'][$customers_group['customers_group_id']]) {
                                  if ($attributes['customers_group_id'] == $customers_group['customers_group_id']) {
                                      $sppc_update_query = "set ";
                                      if (isset($_POST['sppcprice'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= "customers_group_price = '" . $_POST['sppcprice'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_qty_blocks'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_qty_blocks = '" . $_POST['sppcproducts_qty_blocks'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price1'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price1 = '" . $_POST['sppcproducts_price1'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price1_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price1_qty = '" . $_POST['sppcproducts_price1_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price2'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price2 = '" . $_POST['sppcproducts_price2'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price2_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price2_qty = '" . $_POST['sppcproducts_price2_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price3'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price3 = '" . $_POST['sppcproducts_price3'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price3_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price3_qty = '" . $_POST['sppcproducts_price3_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price4'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price4 = '" . $_POST['sppcproducts_price4'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price4_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price4_qty = '" . $_POST['sppcproducts_price4_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price5'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price5 = '" . $_POST['sppcproducts_price5'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price5_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price5_qty = '" . $_POST['sppcproducts_price5_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price6'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price6 = '" . $_POST['sppcproducts_price6'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price6_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price6_qty = '" . $_POST['sppcproducts_price6_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price7'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price7 = '" . $_POST['sppcproducts_price7'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price7_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price7_qty = '" . $_POST['sppcproducts_price7_qty'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price8'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price8 = '" . $_POST['sppcproducts_price8'][$customers_group['customers_group_id']] . "', ";
                                      }
                                      if (isset($_POST['sppcproducts_price8_qty'][$customers_group['customers_group_id']])) {
                                          $sppc_update_query .= " products_price8_qty = '" . $_POST['sppcproducts_price8_qty'][$customers_group['customers_group_id']] . "' ";
                                      }
                                      $sppc_update_query = rtrim($sppc_update_query);
                                      $query_string_length = strlen($sppc_update_query);
                                      if (substr($sppc_update_query, -1) == ",") {
                                          $sppc_update_query = substr($sppc_update_query, $query_string_length - 1);
                                      }
                                      tep_db_query("update " . TABLE_PRODUCTS_GROUPS . " " . $sppc_update_query . " where customers_group_id = '" . $attributes['customers_group_id'] . "' and products_id = '" . $products_id . "'");
                                  }
                              } else {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customers_group['customers_group_id'] . "' and products_id = '" . $products_id . "'");
                                  $attributes = tep_db_fetch_array($attributes_query);
                              }
                          } elseif (($_POST['sppcoption'][$customers_group['customers_group_id']]) && ($_POST['sppcprice'][$customers_group['customers_group_id']] != '')) {
                              $sppc_insert_query = "set products_id = '" . $products_id . "', customers_group_id= '" . $customers_group['customers_group_id'] . "', ";
                              if (isset($_POST['sppcprice'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= "customers_group_price = '" . $_POST['sppcprice'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_qty_blocks'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_qty_blocks = '" . $_POST['sppcproducts_qty_blocks'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price1'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price1 = '" . $_POST['sppcproducts_price1'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price1_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price1_qty = '" . $_POST['sppcproducts_price1_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price2'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price2 = '" . $_POST['sppcproducts_price2'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price2_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price2_qty = '" . $_POST['sppcproducts_price2_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price3'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price3 = '" . $_POST['sppcproducts_price3'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price3_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price3_qty = '" . $_POST['sppcproducts_price3_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price4'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price4 = '" . $_POST['sppcproducts_price4'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price4_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price4_qty = '" . $_POST['sppcproducts_price4_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price5'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price5 = '" . $_POST['sppcproducts_price5'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price5_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price5_qty = '" . $_POST['sppcproducts_price5_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price6'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price6 = '" . $_POST['sppcproducts_price6'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price6_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price6_qty = '" . $_POST['sppcproducts_price6_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price7'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price7 = '" . $_POST['sppcproducts_price7'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price7_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price7_qty = '" . $_POST['sppcproducts_price7_qty'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price8'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price8 = '" . $_POST['sppcproducts_price8'][$customers_group['customers_group_id']] . "', ";
                              }
                              if (isset($_POST['sppcproducts_price8_qty'][$customers_group['customers_group_id']])) {
                                  $sppc_insert_query .= " products_price8_qty = '" . $_POST['sppcproducts_price8_qty'][$customers_group['customers_group_id']] . "' ";
                              }
                              $sppc_insert_query = rtrim($sppc_insert_query);
                              $query_string_length = strlen($sppc_insert_query);
                              if (substr($sppc_insert_query, -1) == ",") {
                                  $sppc_insert_query = substr($sppc_insert_query, $query_string_length - 1);
                              }
                              tep_db_query("insert into " . TABLE_PRODUCTS_GROUPS . " " . $sppc_insert_query . "");
                          }
                      }
                   #delete categories saved in the tables
                   tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '". $products_id . "'");
                  }
                  # create loop here to insert rows for multiple categories
                  $selected_catids = $_POST['categories_ids'];
                  if ($selected_catids){
                   foreach ($selected_catids as $current_category_id){
                      tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $current_category_id . "')");
                   }
                  }

                  if ($_POST['ymm_count'] > 0) {
                      $i = 1;
                      for ($i = 1; $i <= $_POST['ymm_count']; $i++) {
                          if (isset($_POST['delete_ymm_' . $i]))
                              $ids .= ($ids != '' ? ',' : '') . $_POST['delete_ymm_' . $i];
                      }
                      mysql_query("delete from products_ymm where id in ($ids)");
                  }
                  for ($i = 0; $i < (int)YMM_NUMBER_OF_INPUT_FIELDS; $i++) {
                      $products_car_make = tep_db_prepare_input(trim($_POST["products_car_make" . $i]));
                      $products_car_model = tep_db_prepare_input(trim($_POST["products_car_model" . $i]));
                      $products_car_year_bof = ($_POST["products_car_year_bof" . $i] > 1900 ? $_POST["products_car_year_bof" . $i] : 0);
                      $products_car_year_eof = ($_POST["products_car_year_eof" . $i] > 1900 ? $_POST["products_car_year_eof" . $i] : 0);
                      if ($products_car_make != '' || $products_car_model != '') {
                          if (strtolower($products_car_make) == 'all') {
                              mysql_query("delete from products_ymm where products_id = '" . (int)$products_id . "' ");
                          } else {
                              if (strtolower($products_car_model) == 'all')
                                  $products_car_model = '';
                              mysql_query("insert into products_ymm set products_id = '" . (int)$products_id . "', products_car_make = '" . $products_car_make . "', products_car_model = '" . $products_car_model . "', products_car_year_bof = $products_car_year_bof, products_car_year_eof = $products_car_year_eof ");
                          }
                      }
                  }
                  $languages = tep_get_languages();
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                      $language_id = $languages[$i]['id'];
                      $sql_data_array = array('products_name' => tep_db_prepare_input($_POST['products_name'][$language_id]), 'products_info_title' => tep_db_prepare_input($_POST['products_info_title'][$language_id]), 'products_info_desc' => tep_db_prepare_input($_POST['products_info_desc'][$language_id]), 'products_short' => tep_db_prepare_input($_POST['products_short'][$language_id]), 'products_description' => tep_db_prepare_input($_POST['products_description'][$language_id]), 'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]), 'products_seo_url' => tep_db_prepare_input($_POST['products_seo_url'][$language_id]), 'products_head_title_tag' => ((tep_not_null($_POST['products_head_title_tag'][$language_id])) ? tep_db_prepare_input($_POST['products_head_title_tag'][$language_id]) : tep_db_prepare_input($_POST['products_name'][$language_id])), 'products_head_desc_tag' => ((tep_not_null($_POST['products_head_desc_tag'][$language_id])) ? tep_db_prepare_input($_POST['products_head_desc_tag'][$language_id]) : tep_db_prepare_input($_POST['products_name'][$language_id])), 'products_head_keywords_tag' => ((tep_not_null($_POST['products_head_keywords_tag'][$language_id])) ? tep_db_prepare_input($_POST['products_head_keywords_tag'][$language_id]) : tep_db_prepare_input($_POST['products_name'][$language_id])));
                      if ($action == 'insert_product') {
                          $insert_sql_data = array('products_id' => $products_id, 'language_id' => $language_id);
                          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                      } elseif ($action == 'update_product') {
                          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
                      }
                  }
                  $extra_fields_query = tep_db_query("SELECT * FROM " . products_to_products_extra_fields . " WHERE products_id = " . (int)$products_id);
                  while ($products_extra_fields = tep_db_fetch_array($extra_fields_query)) {
                      $extra_product_entry[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
                  }
                  if ($_POST['extra_field']) {
                      foreach ($_POST['extra_field'] as $key => $val) {
                          if (isset($extra_product_entry[$key])) {
                              if ($val == '')
                                  tep_db_query("DELETE FROM " . products_to_products_extra_fields . " where products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
                              else
                                  tep_db_query("UPDATE " . products_to_products_extra_fields . " SET products_extra_fields_value = '" . tep_db_prepare_input($val) . "' WHERE products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
                          } else {
                              if ($val != '')
                                  tep_db_query("INSERT INTO " . products_to_products_extra_fields . " (products_id, products_extra_fields_id, products_extra_fields_value) VALUES ('" . (int)$products_id . "', '" . $key . "', '" . tep_db_prepare_input($val) . "')");
                          }
                      }
                  }
                  $sql_shipping_array = array('products_ship_zip' => tep_db_prepare_input($_POST['products_ship_zip']), 'products_ship_methods_id' => tep_db_prepare_input($_POST['products_ship_methods_id']), 'products_ship_price' => round(tep_db_prepare_input($_POST['products_ship_price']), 4), 'products_ship_price_two' => round(tep_db_prepare_input($_POST['products_ship_price_two']), 4));
                  $sql_shipping_id_array = array('products_id' => (int)$products_id);
                  $products_ship_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_SHIPPING . " WHERE products_id = " . (int)$products_id);
                  if (tep_db_num_rows($products_ship_query) > 0) {
                      if (($_POST['products_ship_zip'] == '') && ($_POST['products_ship_methods_id'] == '') && ($_POST['products_ship_price'] == '') && ($_POST['products_ship_price_two'] == '')) {
                          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_SHIPPING . " where products_id = '" . (int)$products_id . "'");
                      } else {
                          tep_db_perform(TABLE_PRODUCTS_SHIPPING, $sql_shipping_array, 'update', "products_id = '" . (int)$products_id . "'");
                      }
                  } else {
                      if (($_POST['products_ship_zip'] != '') || ($_POST['products_ship_methods_id'] != '') || ($_POST['products_ship_price'] != '') || ($_POST['products_ship_price_two'] != '')) {
                          $sql_ship_array = array_merge($sql_shipping_array, $sql_shipping_id_array);
                          tep_db_perform(TABLE_PRODUCTS_SHIPPING, $sql_ship_array, 'insert');
                      }
                  }
                  if (USE_CACHE == 'true') {
                      tep_reset_cache_block('categories');
                      tep_reset_cache_block('also_purchased');
                  }
                  require(DIR_WS_FUNCTIONS . 'SocialRunnerConnector.php');
                  SrBroadcast($products_id);
                  tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
              }
              break;
          case 'copy_to_confirm':
              if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
                  $products_id = tep_db_prepare_input($_POST['products_id']);
                  $categories_id = tep_db_prepare_input($_POST['categories_id']);
                  if ($_POST['copy_as'] == 'link') {
                      if ($categories_id != $current_category_id) {
                          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
                          $check = tep_db_fetch_array($check_query);
                          if ($check['total'] < '1') {
                              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
                          }
                      } else {
                          $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
                      }
                  } elseif ($_POST['copy_as'] == 'duplicate') {
                      $product_query = tep_db_query("select products_quantity, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks, products_model, products_mpn, products_gtin, vendors_prod_id, products_image, product_image_2,product_image_3,product_image_4,product_image_5,product_image_6, products_price, vendors_product_price,map_price,  msrp_price, vendors_prod_comments, products_date_added, products_date_available,pSortOrder, products_weight, products_status, products_tax_class_id, vendors_id, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
                      $product = tep_db_fetch_array($product_query);
                      tep_db_query("insert into " . TABLE_PRODUCTS . " (
products_quantity,
products_model,
products_mpn,
products_gtin,
vendors_prod_id,
products_image,
products_price,
products_price1,
products_price2,
products_price3,
products_price4,
products_price5,
products_price6,
products_price7,
products_price8,
products_price1_qty,
products_price2_qty,
products_price3_qty,
products_price4_qty,
products_price5_qty,
products_price6_qty,
products_price7_qty,
products_price8_qty,
products_qty_blocks,
vendors_product_price,
map_price,
msrp_price,
vendors_prod_comments,
products_date_added,
products_date_available,
pSortOrder,
products_weight,
products_status,
products_tax_class_id,
vendors_id,
manufacturers_id,
product_image_2,
product_image_3,
product_image_4,
product_image_5,
product_image_6)

values

('" . tep_db_input($product['products_quantity']) . "',
'" . tep_db_input($product['products_model']) . "',
'" . tep_db_input($product['products_mpn']) . "',
'" . tep_db_input($product['products_gtin']) . "',
'" . tep_db_input($product['vendors_prod_id']) . "',
'" . tep_db_input($product['products_image']) . "',
'" . tep_db_input($product['products_price']) . "',
'" . tep_db_input($product['products_price1']) . "',
'" . tep_db_input($product['products_price2']) . "',
'" . tep_db_input($product['products_price3']) . "',
'" . tep_db_input($product['products_price4']) . "',
'" . tep_db_input($product['products_price5']) . "',
'" . tep_db_input($product['products_price6']) . "',
'" . tep_db_input($product['products_price7']) . "',
'" . tep_db_input($product['products_price8']) . "',
'" . tep_db_input($product['products_price1_qty']) . "',
'" . tep_db_input($product['products_price2_qty']) . "',
'" . tep_db_input($product['products_price3_qty']) . "',
'" . tep_db_input($product['products_price4_qty']) . "',
'" . tep_db_input($product['products_price5_qty']) . "',
'" . tep_db_input($product['products_price6_qty']) . "',
'" . tep_db_input($product['products_price7_qty']) . "',
'" . tep_db_input($product['products_price8_qty']) . "',
'" . tep_db_input($product['products_qty_blocks']) . "',
'" . tep_db_input($product['vendors_product_price']) . "',
'" . tep_db_input($product['map_price']) . "',
'" . tep_db_input($product['msrp_price']) . "',
'" . tep_db_input($product['vendors_prod_comments']) . "',

now(), '" . tep_db_input($product['products_date_available']) . "',
'" . tep_db_input($product['pSortOrder']) . "',
'" . tep_db_input($product['products_weight']) . "',
'" . tep_db_input($product['products_status']) . "',
'" . (int)$product['products_tax_class_id'] . "',
'" . (int)$product['vendors_id'] . "',
'" . (int)$product['manufacturers_id'] . "',
 '" . tep_db_input($product['product_image_2']) . "',
'" . tep_db_input($product['product_image_3']) . "',
'" . tep_db_input($product['product_image_4']) . "',
'" . tep_db_input($product['products_image_5']) . "',
'" . tep_db_input($product['products_image_6']) . "')");
                      $dup_products_id = tep_db_insert_id();
                      $q = mysql_query("select * from products_ymm where products_id=$products_id");
                      if (mysql_num_rows($q) > 0) {
                          while ($r = mysql_fetch_assoc($q)) {
                              mysql_query("insert into products_ymm set products_id=$dup_products_id , products_car_make = '" . $r['products_car_make'] . "', products_car_model = '" . $r['products_car_model'] . "', products_car_year_bof = '" . $r['products_car_year_bof'] . "', products_car_year_eof = '" . $r['products_car_year_eof'] . "' ");
                          }
                      }
                      $description_query = tep_db_query("select language_id, products_name, products_seo_url, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_info_title,products_info_desc,products_short from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
                      while ($description = tep_db_fetch_array($description_query)) {
                          tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_seo_url, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag,products_info_title,products_info_desc,products_url, products_viewed,products_short) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_seo_url']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_info_title']) . "', '" . tep_db_input($description['products_info_desc']) . "', '" . tep_db_input($description['products_url']) . "', '0', '" . tep_db_input($description['products_short']) . "')");
                      }
                      $shipping_query = tep_db_query("select products_ship_methods_id, products_ship_zip from " . TABLE_PRODUCTS_SHIPPING . " where products_id = '" . (int)$products_id . "'");
                      while ($shipping = tep_db_fetch_array($shipping_query)) {
                          tep_db_query("insert into " . TABLE_PRODUCTS_SHIPPING . " (products_id, products_ship_methods_id, products_ship_zip) values ('" . (int)$dup_products_id . "', '" . tep_db_input($shipping['products_ship_methods_id']) . "', '" . tep_db_input($shipping['products_ship_zip']) . "')");
                      }
                      tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
                      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
                      $header = false;
                      while ($customers_group = tep_db_fetch_array($customers_group_query)) {
                          if (tep_db_num_rows($customers_group_query) > 0) {
                              $attributes_query = tep_db_query("select customers_group_id, customers_group_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $products_id . "' and customers_group_id = '" . $customers_group['customers_group_id'] . "' order by customers_group_id");
                              $customers_group_data = tep_db_fetch_array($attributes_query);
                              tep_db_query("insert into " . TABLE_PRODUCTS_GROUPS . " ( customers_group_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks, customers_group_id,products_id) values ('" . tep_db_input($customers_group_data['customers_group_price']) . "', '" . tep_db_input($customers_group_data['products_price1']) . "', '" . tep_db_input($customers_group_data['products_price2']) . "', '" . tep_db_input($customers_group_data['products_price3']) . "', '" . tep_db_input($customers_group_data['products_price4']) . "', '" . tep_db_input($customers_group_data['products_price5']) . "', '" . tep_db_input($customers_group_data['products_price6']) . "', '" . tep_db_input($customers_group_data['products_price7']) . "', '" . tep_db_input($customers_group_data['products_price8']) . "', '" . tep_db_input($customers_group_data['products_price1_qty']) . "', '" . tep_db_input($customers_group_data['products_price2_qty']) . "', '" . tep_db_input($customers_group_data['products_price3_qty']) . "', '" . tep_db_input($customers_group_data['products_price4_qty']) . "', '" . tep_db_input($customers_group_data['products_price5_qty']) . "', '" . tep_db_input($customers_group_data['products_price6_qty']) . "', '" . tep_db_input($customers_group_data['products_price7_qty']) . "', '" . tep_db_input($customers_group_data['products_price8_qty']) . "', '" . tep_db_input($customers_group_data['products_qty_blocks']) . "', '" . tep_db_input($customers_group['customers_group_id']) . "', '" . $dup_products_id . "')");
                          }
                      }
                      $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$products_id);
                      while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
                          tep_db_query("insert into " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " ( products_extra_fields_id, products_extra_fields_value,products_id) values ('" . tep_db_input($products_extra_fields['products_extra_fields_id']) . "', '" . tep_db_input($products_extra_fields['products_extra_fields_value']) . "', '" . $dup_products_id . "')");
                      }
                      $products_id = $dup_products_id;
                  }
                  if (USE_CACHE == 'true') {
                      tep_reset_cache_block('categories');
                      tep_reset_cache_block('also_purchased');
                  }
              }
              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
              break;
          case 'new_product_preview':
              $products_image = new upload('products_image');
              $products_image_2 = new upload('products_image_2');
              $products_image_3 = new upload('products_image_3');
              $products_image_4 = new upload('products_image_4');
              $products_image_5 = new upload('products_image_5');
              $products_image_6 = new upload('products_image_6');
              $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_2->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_3->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_4->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_5->set_destination(DIR_FS_CATALOG_IMAGES);
              $products_image_6->set_destination(DIR_FS_CATALOG_IMAGES);
              if ($products_image->parse() && $products_image->save()) {
                  $products_image_name = $products_image->filename;
              } else {
                  $products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
              }
              if ($products_image_2->parse() && $products_image_2->save()) {
                  $products_image_2_name = $products_image_2->filename;
              } else {
                  $products_image_2_name = (isset($_POST['products_previous_image_2']) ? $_POST['products_previous_image_2'] : '');
              }
              if ($products_image_3->parse() && $products_image_3->save()) {
                  $products_image_3_name = $products_image_3->filename;
              } else {
                  $products_image_3_name = (isset($_POST['products_previous_image_3']) ? $_POST['products_previous_image_3'] : '');
              }
              if ($products_image_4->parse() && $products_image_4->save()) {
                  $products_image_4_name = $products_image_4->filename;
              } else {
                  $products_image_4_name = (isset($_POST['products_previous_image_4']) ? $_POST['products_previous_image_4'] : '');
              }
              if ($products_image_5->parse() && $products_image_5->save()) {
                  $products_image_5_name = $products_image_5->filename;
              } else {
                  $products_image_5_name = (isset($_POST['products_previous_image_5']) ? $_POST['products_previous_image_5'] : '');
              }
              if ($products_image_6->parse() && $products_image_6->save()) {
                  $products_image_6_name = $products_image_6->filename;
              } else {
                  $products_image_6_name = (isset($_POST['products_previous_image_6']) ? $_POST['products_previous_image_6'] : '');
              }
              break;

          case 'ajaxDeleteImage':
                $products_id = (int)$_POST['products_id'];
                $imageID = (int)$_POST['image'];
                if ($imageID == 0)
                  $imagefield = 'products_image';
                else
                  $imagefield = 'product_image_'.$imageID;

                $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_id=" . $products_id . " and " . $imagefield . "!=''");
                if (is_resource($check_query)){
                 $check = tep_db_fetch_array($check_query);
                 if ($check['total'] == 1){
                   $res = tep_db_query("update " . TABLE_PRODUCTS . " set " . $imagefield . " = '' where products_id = " . $products_id);
                   if ($res)
                     print "Image successfully deleted!";
                   else
                     print "Unable to delete image: " . mysql_error();
                 }
                } else {
                  print "Image and/or product not found..can't delete.";
                }
                exit();
              break;
             case 'promote':
                if (isset($HTTP_GET_VARS['pID'])) {
                    $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
                    require(DIR_WS_FUNCTIONS . 'SocialRunnerConnector.php');
                    SrBroadcast($products_id);
                }

                tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath ));
                break;
      }
  }
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
      if (!is_writeable(DIR_FS_CATALOG_IMAGES))
          $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
      $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php
  echo TITLE;
?>
</title>
<?php
  require_once('attributeManager/includes/attributeManagerHeader.inc.php')
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
  leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="goOnLoad();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<!-- left_navigation //-->
<!-- left_navigation_eof //-->
<!-- body_text //-->
<?php
  if ($action == 'new_product') {
      $parameters = array('products_name' => '', 'products_short' => '', 'products_description' => '', 'products_info_title' => '', 'products_info_desc' => '', 'products_url' => '', 'products_seo_url' => '', 'products_id' => '', 'products_quantity' => '', 'products_model' => '', 'products_mpn' => '', 'products_gtin' => '', 'products_image' => '', 'products_image_2' => '', 'products_image_3' => '', 'products_image_4' => '', 'products_image_5' => '', 'products_image_6' => '', 'products_price' => '', 'products_price1' => '', 'products_price2' => '', 'products_price3' => '', 'products_price4' => '', 'products_price5' => '', 'products_price6' => '', 'products_price7' => '', 'products_price8' => '', 'products_price1_qty' => '', 'products_price2_qty' => '', 'products_price3_qty' => '', 'products_price4_qty' => '', 'products_price5_qty' => '', 'products_price6_qty' => '', 'products_price7_qty' => '', 'products_price8_qty' => '', 'products_qty_blocks' => '', 'products_weight' => '', 'products_date_added' => '', 'products_last_modified' => '', 'products_date_available' => '', 'pSortOrder' => '', 'products_featured' => '', 'products_status' => '', 'products_special' => '', 'products_tax_class_id' => '', 'vendors_product_price' => '', 'map_price' => '', 'msrp_price' => '', 'vendors_prod_comments' => '', 'vendors_prod_id' => '', 'vendors_id' => '', 'manufacturers_id' => '');
      $pInfo = new objectInfo($parameters);
      if (isset($_GET['pID']) && (!$_POST)) {
          $products_shipping_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_SHIPPING . " WHERE products_id=" . (int)$_GET['pID']);
          while ($products_shipping = tep_db_fetch_array($products_shipping_query)) {
              $products_ship_zip = $products_shipping['products_ship_zip'];
              $products_ship_methods_id = $products_shipping['products_ship_methods_id'];
              $products_ship_price = $products_shipping['products_ship_price'];
              $products_ship_price_two = $products_shipping['products_ship_price_two'];
          }
          $shipping = array('products_ship_methods_id' => $products_ship_methods_id, 'products_ship_zip' => $products_ship_zip, 'products_ship_price' => $products_ship_price, 'products_ship_price_two' => $products_ship_price_two);
          $pInfo->objectInfo($shipping);
          $product_query = tep_db_query("select pd.products_name,pd.products_info_desc,pd.products_info_title,pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_mpn, p.products_gtin, p.vendors_prod_id, p.products_image, p.product_image_2, p.product_image_3, p.product_image_4, p.product_image_5, p.product_image_6, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_special, p.products_qty_blocks, p.vendors_product_price,p.map_price,  p.msrp_price, p.products_weight, p.vendors_prod_comments, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available,p.pSortOrder,p.products_featured, p.products_status, p.products_tax_class_id, p.vendors_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
          $product = tep_db_fetch_array($product_query);
          $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$_GET['pID']);
          while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
              $extra_field[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
          }
          $extra_field_array = array('extra_field' => $extra_field);
          $pInfo->objectInfo($extra_field_array);
          $pInfo->objectInfo($product);
      } elseif (tep_not_null($_POST)) {
          $pInfo->objectInfo($_POST);
          $products_name = $_POST['products_name'];
          $products_info_title = $_POST['products_info_title'];
          $products_info_desc = $_POST['products_info_desc'];
          $products_short = $_POST['products_short'];
          $products_seo_url = $_POST['products_seo_url'];
          $products_description = $_POST['products_description'];
          $products_url = $_POST['products_url'];
      }
      $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
      $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
      }
      $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
      $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
      while ($vendors = tep_db_fetch_array($vendors_query)) {
          $vendors_array[] = array('id' => $vendors['vendors_id'], 'text' => $vendors['vendors_name']);
      }
      $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
      $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
      while ($tax_class = tep_db_fetch_array($tax_class_query)) {
          $tax_class_array[] = array('id' => $tax_class['tax_class_id'], 'text' => $tax_class['tax_class_title']);
      }

      $categories_query_selected = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
	  if (tep_db_num_rows($categories_query_selected) < 1)
      		$categories_array_selected = array(array('id' => $current_category_id));
	  else
	  	   $categories_array_selected = array(array('id' => ''));
      while ($categories = tep_db_fetch_array($categories_query_selected)) {
         $categories_array_selected[] = array('id' => $categories['categories_id']);
      }
      $categories_array = array(array('id' => '', 'text' => TEXT_NONE));
      #Categories list displays only for one languge (Deafault is English)
      $language_id = 1;
      $categories_array = tep_get_category_tree();

      $languages = tep_get_languages();
      if (!isset($pInfo->products_status))
          $pInfo->products_status = '1';
      switch ($pInfo->products_status) {
          case '0':
              $in_status = false;
              $out_status = true;
              break;
          case '1':
          default:
              $in_status = true;
              $out_status = false;
      }
      if (!isset($pInfo->products_featured))
          $pInfo->products_featured = '0';
      switch ($pInfo->products_featured) {
          case '1':
              $in_f_status = true;
              $out_f_status = false;
              break;
          case '0':
          default:
              $in_f_status = false;
              $out_f_status = true;
      }
      if (!isset($pInfo->products_special))
          $pInfo->products_special = '1';
      switch ($pInfo->products_special) {
          case '0':
              $in_special = false;
              $out_special = true;
              break;
          case '1':
          default:
              $in_special = false;
              $out_special = true;
      }
?>
<link rel="stylesheet" type="text/css"
  href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript"
  src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
//  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate","<?php echo $pInfo->products_date_available;?>",scBTNMODE_CUSTOMBLUE);
//  var FeaturedUntil = new ctlSpiffyCalendarBox("FeaturedUntil", "new_product", "products_featured_until","btnDate2","<?php echo $pInfo->products_featured_until;?>", scBTNMODE_CUSTOMBLUE);

var tax_rates = new Array();
<?php
      for ($i = 0, $n = sizeof($tax_class_array); $i < $n; $i++) {
          if ($tax_class_array[$i]['id'] > 0) {
              echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
          }
      }
?>
function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}
function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;
  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}
function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }
  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}
function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;
  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }
  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
function deleteImage(pID,imageID){
 var pname = "products_previous_image";
 if (imageID != "") pname = pname + "_" + imageID;
 var dcheck = confirm("Are you sure you want to delete this image: " + $("[name='"+pname+"']").val());
 if (dcheck){
   var loadSpan = $("#deletingImageBox"+imageID);
   loadSpan.html('<span style="padding-left: 10px; font-weight: bold; font-style: italic">Deleting image...<img src="templates/admin/images/loading.gif" alt="" /></span>');
   $.ajax({
       url: "categories.php?action=ajaxDeleteImage",
       data: {products_id: pID, image: imageID},
       type: 'post',
       success: function(html){
         $("#textImageBox"+imageID).css("text-decoration","line-through");
         $("[name='"+pname+"']").remove(); // remove hidden input field holding previous image value so it doesn't re-update the field
         loadSpan.html('<span style="padding-left: 10px; font-weight: bold; font-style: italic">'+html+'</span>');
       },
       error: function(e1, e2, e3){
           alert(e1 + ": " + e2);
       }
   });
 }
}

//--></script>
<div id="add_edit_product">
  <?php
      echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . "&action=update_product" : '&action=insert_product'), 'post', 'enctype="multipart/form-data"');
?>
<h3><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id));?></h3>

<div id="product-admin-tabs">
	<ul>
		<li><a href="#tab-general-options">General Options</a></li>
		<li><a href="#tab-image-upload">Images</a></li>
		<li><a href="#tab-attributes">Attributes</a></li>
		<li><a href="#tab-default-pricing">Price</a></li>
		<li><a href="#tab-price-breaks">Price Breaks</a></li>
		<li><a href="#tab-description">Description</a></li>
		<li><a href="#tab-multi-vendor">Vendor</a></li>
		<li><a href="#tab-meta-seo">Meta Tags</a></li>
		<li><a href="#tab-extra-fields">Extra Fields</a></li>
		<li><a href="#tab-auto-parts">Year Make Model</a></li>
				<li><a href="#tab-individual-shipping">Individual Shipping</a></li>

	</ul>
<!-- General Options tab -->
	<div id="tab-general-options">
		<label>Products Sort Order:</label>
		<?php echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('pSortOrder', $pInfo->pSortOrder, 'size=2'); ?>
    <div id="tooltip-sort" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Sort Order - You can leave this blank unless you want to control the order of listing in categories your products show." /> </div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_STATUS;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_radio_field('products_status', '1', $in_status) . '' . TEXT_PRODUCT_AVAILABLE . '' . tep_draw_radio_field('products_status', '0', $out_status) . '' . TEXT_PRODUCT_NOT_AVAILABLE;
?>
    <br />
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_FEATURED;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_radio_field('products_featured', '1', $in_f_status) . '' . TEXT_PRODUCT_YES . '' . tep_draw_radio_field('products_featured', '0', $out_f_status) . '' . TEXT_PRODUCT_NO;
?>
    <div id="tooltip-featured" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Featured Products - Featured products show on the home page and in category views." /> </div>
    <hr />
    <label>Featured Until
    <?php
      echo TEXT_PRODUCTS_DATE_AVAILABLE;
?>
    <small>(YYYY-MM-DD)</small></label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '';
	  echo '<span class="ui-cal">' . tep_draw_input_field('products_featured_until', $pInfo->products_featured_until) . '</span>';
?>
    <div id="tooltip-feat_until" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Featured Until - Product will no longer be featured if you enter a date here after that date, otherwise leave blank for a never ending featured product." /> </div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_DATE_AVAILABLE;
?>
    <small>(YYYY-MM-DD)</small></label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '';
	  echo '<span class="ui-cal">' . tep_draw_input_field('products_date_available', $pInfo->products_date_available) . '</span>';
?>
    <div id="tooltip-datea" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Date Available - This controls when the product will be available, it will display when available in the catalog. Useful for collecting pre-sales." /> </div>
    <hr />
    <?php
?>
    <label>
    <?php
      echo TEXT_PRODUCTS_SPECIAL;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.gif', '0', '0') . '' . tep_draw_radio_field('products_special', '1', $in_special) . '' . TEXT_PRODUCT_IS_SPECIAL . '' . tep_draw_radio_field('products_special', '0', $out_special) . '' . TEXT_PRODUCT_NOT_SPECIAL;
?>
    <div id="tooltip-specialorder" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Special Order Item? - If so this will display a message on the product page, like your product will take a extra 3 weeks to arrive." /> </div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_MANUFACTURER;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id);
?>
    <hr />
    <table>
    <tr valign="top"><td><label><?php echo TEXT_CATEGORIES; ?><td></label>
     <td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_mselect_menu('categories_ids[]', $categories_array, $categories_array_selected, 'size=10'); ?></td>
    </tr>
    </table>
    <hr />
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo TEXT_PRODUCTS_NAME;
?>
    </label>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])));
?>
    <span class="required">*</span>
    <hr />
    <?php
      }
?>
    <label>
    <?php
      echo TEXT_PRODUCTS_QUANTITY;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_quantity', $pInfo->products_quantity);
?>
    <span class="required">*</span>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_WEIGHT;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_weight', $pInfo->products_weight);
?>
    <div id="tooltip-weight" class="tooltip-toggler" style="display: inline"><img
  src="templates/admin/images/help_img.jpg"
  title="Products Wieght - if left blank or 0 the store will consider the item to be a non shipped item and will skip the shipping selection in checkout."
  width="16px" height="16px" border="0"></div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_MODEL;
?> 
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_model', $pInfo->products_model);
?>
    <span class="required">*</span>
    <div id="tooltip-model" class="tooltip-toggler" style="display: inline"><img
  src="templates/admin/images/help_img.jpg"
  title="Products Model Number - You must use unique characters <br> if you plan to use any of CSV imports capabilities."
  width="16px" height="16px" border="0"></div>

    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_MPN;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_mpn', $pInfo->products_mpn);
?><span class="required">*</span>
    <div id="tooltip-model" class="tooltip-toggler" style="display: inline"><img
  src="templates/admin/images/help_img.jpg"
  title="Needed for Google Base feed."
  width="16px" height="16px" border="0"></div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_GTIN;
?>
    </label>
<span class="required">*</span>
    <div id="tooltip-model" class="tooltip-toggler" style="display: inline"><img
  src="templates/admin/images/help_img.jpg"
  title="Needed for Google Base feed."
  width="16px" height="16px" border="0"></div>
<?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_gtin', $pInfo->products_gtin);
?>
    <span class="required">*</span>
    <div id="tooltip-model" class="tooltip-toggler" style="display: inline"><img
  src="templates/admin/images/help_img.jpg"
  title="Products Model Number - You must use unique characters <br> if you plan to use any of CSV imports capabilities."
  width="16px" height="16px" border="0"></div>

    <hr />
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo '<br>Alternate Buy Now Url';
?>
    </label>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : tep_get_products_url($pInfo->products_id, $languages[$i]['id'])));
?>
    <div id="tooltip-altbuy" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Alternate Buy Now URL - This is useful if you want to deflect sales of products to affiliate programs. You can still own a store but not have to worry about stocking and shipping. Or maybe in the future you plan on selling it and want to work on seo for this product!" /> </div>
    <hr />
    <?php
      }
?>
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo 'Processing Time (Days)';
?>
    </label>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('products_info_title[' . $languages[$i]['id'] . ']', (isset($products_info_title[$languages[$i]['id']]) ? stripslashes($products_info_title[$languages[$i]['id']]) : tep_get_products_info_title($pInfo->products_id, $languages[$i]['id'])));
?>
    <div id="tooltip-tship" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Processing Time (Days) - This will display a message for example “Typically Ships in “XX” Days” on the product page." /> </div>
    <?php
      }
?>
	</div>
<!--  end general options tab -->
<!-- Image Upload tab -->
	<div id="tab-image-upload">
    Main Product Image
    <?php
      echo '' . tep_draw_file_field('products_image') . '<span id="textImageBox">' . $pInfo->products_image . '</span>' . tep_draw_hidden_field('products_previous_image', $pInfo->products_image);
      if (!empty($pInfo->products_image))
       echo '<span id="deletingImageBox"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
    ?>
    <hr />
    Extra Image 2
    <?php
      echo '' . tep_draw_file_field('products_image_2') . '<span id="textImageBox2">' . $pInfo->product_image_2 . '</span>' . tep_draw_hidden_field('products_previous_image_2', $pInfo->product_image_2);
      if (!empty($pInfo->product_image_2))
       echo '<span id="deletingImageBox2"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'2\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
?>
    <hr />
    Extra Image 3
    <?php
      echo '' . tep_draw_file_field('products_image_3') . '<span id="textImageBox3">' . $pInfo->product_image_3 . '</span>' . tep_draw_hidden_field('products_previous_image_3', $pInfo->product_image_3);
      if (!empty($pInfo->product_image_3))
       echo '<span id="deletingImageBox3"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'3\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
?>
    <hr />
    Extra Image 4
    <?php
      echo '' . tep_draw_file_field('products_image_4') . '<span id="textImageBox4">' . $pInfo->product_image_4 . '</span>' . tep_draw_hidden_field('products_previous_image_4', $pInfo->product_image_4);
      if (!empty($pInfo->product_image_4))
       echo '<span id="deletingImageBox4"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'4\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
?>
    <hr />
    Extra Image 5
    <?php
      echo '' . tep_draw_file_field('products_image_5') . '<span id="textImageBox5">' . $pInfo->product_image_5 . '</span>' . tep_draw_hidden_field('products_previous_image_5', $pInfo->product_image_5);
      if (!empty($pInfo->product_image_5))
       echo '<span id="deletingImageBox5"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'5\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
?>
    <hr />
    Extra Image 6
    <?php
      echo '' . tep_draw_file_field('products_image_6') . '<span id="textImageBox6">' . $pInfo->product_image_6 . '</span>' . tep_draw_hidden_field('products_previous_image_6', $pInfo->product_image_6);
      if (!empty($pInfo->product_image_6))
       echo '<span id="deletingImageBox6"><a href="javascript:void(0)" onclick="deleteImage(' . $pInfo->products_id . ',\'6\')"><img height="16px" border="0"
  style="width:16px;margin-left: 10px" src="templates/admin/images/cross2.gif" /></a></span>';
?><br>

If you have old images in the deprecated extra images extension you can access them <a href="https://www.whataluckypet.com/manage/products_extra_images.php">here</a>
	</div>
<!-- end Image Upload tab -->
<!-- Attribute Options tab -->
	<div id="tab-attributes">
  	You can use this tool to enter in attributes to your products. If you need
    more advanced attribute options like file uploads or text input options
    you must first create those attribute bases in the <a href="products_attributes.php">attribute manager.</a> This tool can
    create new and insert drop down attributes and control quantities of
    these and sort order of them as well.<br />
    <?php require_once('attributeManager/includes/attributeManagerPlaceHolder.inc.php') ?>
	</div>
<!-- end attribute options tab -->
<!-- Indv Shiping tab -->
	<div id="tab-individual-shipping">
		
		
		<div class="ui-widget">
	<div style="margin-top: 10px; margin-bottom: 10px; padding: 0 .7em;" class="ui-state-highlight ui-corner-all">
		<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
		<strong>Note!</strong> This is only for those wishing to use individual shipping price module as opposed to other shipping modules. You may use indivdual shipping module by itself or with other modules however with other shipping modules turned on the individual shipping will be added to the shipping total in additon to the totals of the other shipping module. The individual shipping module is disabled by defualt.</p>
	</div>
	
	
</div>
		 
		
		
		
    <label><?php echo TEXT_PRODUCTS_ZIPCODE; ?></label>
    <?php
      echo tep_draw_separator('pixel_trans.gif', '24', '15') . '' . tep_draw_input_field('products_ship_zip', $pInfo->products_ship_zip);
      if (tep_not_null($pInfo->products_ship_zip))
          echo 'notnull';
      else
          echo 'null';
	?>
    <hr />
    <label><?php echo INDIV_SHIPPING_PRICE; ?></label>
    <?php
      echo tep_draw_separator('pixel_trans.gif', '24', '15') . '' . tep_draw_input_field('products_ship_price', $pInfo->products_ship_price);
      if (tep_not_null($pInfo->products_ship_price))
          echo 'notnull';
      else
          echo 'null';
	?>
    <hr />
    <label><?php echo EACH_ADDITIONAL_PRICE; ?></label>
		<?php
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '' . tep_draw_input_field('products_ship_price_two', $pInfo->products_ship_price_two);
			if (tep_not_null($pInfo->products_ship_price_two))
				echo 'notnull';
			else
				echo 'null';
		?>
	</div>
<!-- end indv shipping tab -->
<!-- Default Pricing tab -->
	<div id="tab-default-pricing">
    <label><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"');
?>
    <span class="required">*</span>
    <div class="tooltip-toggler" id="tooltip-taxClass"
  style="display: inline;"><img src="templates/admin/images/help_img.jpg"
  title="Taxable?  - You should choose a configured tax class."
  width="16px" height="16px" border="0"></div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_PRICE_NET;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price', $pInfo->products_price, 'onkeyup="updateGross()" onblur="updateGross()"');
?>
    <span class="required">*</span>
    <div id="tooltip-mprice" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Main Products Price - This is the main products price, if left blank then a message will be displayed to call for pricing." /> </div>
    <hr />
    <label>
    <?php
      echo TEXT_PRODUCTS_PRICE_GROSS;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'onkeyup="updateNet()" onblur="updateNet()"');
?>
    <div id="tooltip-pgross" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Products Price Gross - This is a auto calculated amount based on tax rates if tax rate selected." /> </div>
    <hr />
    <label>MSRP Price:</label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('msrp_price', $pInfo->msrp_price);
?>
    <div id="tooltip-msrp" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="MSRP Price - If a MSRP Price is entered it displays this amount in a fashion similar to this: MSRP Price $19.95" /> </div>
    <hr />
    <label>Map Price:</label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('map_price', $pInfo->map_price);
?>
    <div id="tooltip-manprice" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Manufacturer Minimum Advertised Price - If a amount is entered here the main products price will not be visible unless a customer is logged in our adds a item to their cart. This is to comply with manufacturer regulations about advertising a price lower then there suggested retail price." /> </div>
  <script language="javascript"><!--
	updateGross();
	//--></script>
	</div>
<!-- end default pricing tab -->
<!-- Qty Price Breaks tab -->
	<div id="tab-price-breaks">
		<?php
			$name_retail_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '0'");
			$name_retail = tep_db_fetch_array($name_retail_query);
		?>
	Help for quantity price breaks
    <div class="tooltip-wholesale"
  id="tooltip-<?php
      echo $name_retail['customers_group_name'];
?>"
  style="display: inline;"><img src="templates/admin/images/help_img.jpg"
  title="Quantity Pricing  - Fill in if you want to give quantity pricing to the default group of customers <?php
?>other wise leave blank ."
  border="0">
      <?php
?>
    </div>
    <table width="100%">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><label>Quantity Price Breaks for
          Default Group (Retail)
          <?php
?>
          <?php
?>
          </label></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_QTY_BLOCKS;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_qty_blocks', $pInfo->products_qty_blocks, 'size="10"') . '' . TEXT_PRODUCTS_QTY_BLOCKS_INFO
?>
        </td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE1;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price1', $pInfo->products_price1, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE1_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price1_qty', $pInfo->products_price1_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE2;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price2', $pInfo->products_price2, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE2_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price2_qty', $pInfo->products_price2_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE3;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price3', $pInfo->products_price3, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE3_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price3_qty', $pInfo->products_price3_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE4;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price4', $pInfo->products_price4, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE4_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price4_qty', $pInfo->products_price4_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE5;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price5', $pInfo->products_price5, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE5_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price5_qty', $pInfo->products_price5_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE6;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price6', $pInfo->products_price6, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE6_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price6_qty', $pInfo->products_price6_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE7;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price7', $pInfo->products_price7, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE7_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price7_qty', $pInfo->products_price7_qty, 'size="10"');
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
      echo TEXT_PRODUCTS_PRICE8;
?>
          </label>
          <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('products_price8', $pInfo->products_price8, 'size="10"');
?>
          <?php
      echo TEXT_PRODUCTS_PRICE8_QTY;
?>
          <?php
      echo tep_draw_input_field('products_price8_qty', $pInfo->products_price8_qty, 'size="10"');
?></td>
      </tr>
    </table>
    <!-- EOF Price Break 1.11.3 Retail -->
    <!-- BOF Separate Pricing Per Customer -->
    <?php
      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
      $header = false;
      while ($customers_group = tep_db_fetch_array($customers_group_query)) {
          if (tep_db_num_rows($customers_group_query) > 0) {
              $attributes_query = tep_db_query("select customers_group_id, customers_group_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $pInfo->products_id . "' and customers_group_id = '" . $customers_group['customers_group_id'] . "' order by customers_group_id");
          } else {
              $attributes = array('customers_group_id' => 'new');
          }
          if (!$header) {
?>
    <?php
              $header = true;
          }
?>
    <br />
    <table width="100%">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><label>Insert
          <?php
          echo $customers_group['customers_group_name'];
?>
          Group Pricing: </label>
          <?php
          if (isset($pInfo->sppcoption)) {
              echo tep_draw_checkbox_field('sppcoption[' . $customers_group['customers_group_id'] . ']', 'sppcoption[' . $customers_group['customers_group_id'] . ']', (isset($pInfo->sppcoption[$customers_group['customers_group_id']])) ? 1 : 0);
          } else {
              echo tep_draw_checkbox_field('sppcoption[' . $customers_group['customers_group_id'] . ']', 'sppcoption[' . $customers_group['customers_group_id'] . ']', true) . '' . $customers_group['customers_group_name'];
          }
?>
          Price 1x:
          <?php
          $customer_prices_set = false;
          $customer_prices_in_post = false;
          if ($attributes = tep_db_fetch_array($attributes_query)) {
              $customer_prices_set = true;
              echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcprice[' . $customers_group['customers_group_id'] . ']', $attributes['customers_group_price']);
          } else {
              if (isset($pInfo->sppcprice[$customers_group['customers_group_id']])) {
                  $customer_prices_in_post = true;
                  $sppc_cg_price = $pInfo->sppcprice[$customers_group['customers_group_id']];
              } else {
                  $sppc_cg_price = '';
              }
              echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcprice[' . $customers_group['customers_group_id'] . ']', $sppc_cg_price);
          }
?></td>
      </tr>
      <tr>
        <td><i>Note that if the price field for the customer group is left

          empty, <b>no</b> price and <b>no</b> price break levels and quantities

          for that customer group will be inserted in the database. If a field

          or fields is/are filled, but the checkbox is unchecked no price/price

          break levels etc. will be inserted either. If a price and price break

          levels are already inserted in the database, but the checkbox

          unchecked they will be removed from the database.</i><br />
          <br />
        </td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_QTY_BLOCKS;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_qty_blocks = $attributes['products_qty_blocks'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_qty_blocks = $pInfo->sppcproducts_qty_blocks[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_qty_blocks = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_qty_blocks[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_qty_blocks, 'size="10"') . '' . TEXT_PRODUCTS_QTY_BLOCKS_INFO;
?></td>
      </tr>
      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE1;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price1 = $attributes['products_price1'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price1 = $pInfo->sppcproducts_price1[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price1 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price1[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price1, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE1_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price1_qty = $attributes['products_price1_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price1_qty = $pInfo->sppcproducts_price1_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price1_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price1_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price1_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE2;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price2 = $attributes['products_price2'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price2 = $pInfo->sppcproducts_price2[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price2 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price2[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price2, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE2_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price2_qty = $attributes['products_price2_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price2_qty = $pInfo->sppcproducts_price2_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price2_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price2_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price2_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE3;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price3 = $attributes['products_price3'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price3 = $pInfo->sppcproducts_price3[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price3 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price3[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price3, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE3_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price3_qty = $attributes['products_price3_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price3_qty = $pInfo->sppcproducts_price3_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price3_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price3_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price3_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE4;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price4 = $attributes['products_price4'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price4 = $pInfo->sppcproducts_price4[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price4 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price4[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price4, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE4_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price4_qty = $attributes['products_price4_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price4_qty = $pInfo->sppcproducts_price4_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price4_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price4_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price4_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE5;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price5 = $attributes['products_price5'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price5 = $pInfo->sppcproducts_price5[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price5 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price5[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price5, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE5_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price5_qty = $attributes['products_price5_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price5_qty = $pInfo->sppcproducts_price5_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price5_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price5_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price5_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><?php
          echo TEXT_PRODUCTS_PRICE6;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price6 = $attributes['products_price6'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price6 = $pInfo->sppcproducts_price6[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price6 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price6[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price6, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE6_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price6_qty = $attributes['products_price6_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price6_qty = $pInfo->sppcproducts_price6_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price6_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price6_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price6_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><?php
          echo TEXT_PRODUCTS_PRICE7;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price7 = $attributes['products_price7'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price7 = $pInfo->sppcproducts_price7[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price7 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price7[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price7, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE7_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price7_qty = $attributes['products_price7_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price7_qty = $pInfo->sppcproducts_price7_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price7_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price7_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price7_qty, 'size="10"');
?></td>
      </tr>
      </tr>

      <tr>
        <td class="dataTableHeadingContent"><label>
          <?php
          echo TEXT_PRODUCTS_PRICE8;
?>
          </label>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price8 = $attributes['products_price8'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price8 = $pInfo->sppcproducts_price8[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price8 = '';
          }
          echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('sppcproducts_price8[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price8, 'size="10"');
?>
          <?php
          echo TEXT_PRODUCTS_PRICE8_QTY;
?>
          <?php
          if ($customer_prices_set == true) {
              $sppc_cg_products_price8_qty = $attributes['products_price8_qty'];
          } elseif ($customer_prices_in_post == true) {
              $sppc_cg_products_price8_qty = $pInfo->sppcproducts_price8_qty[$customers_group['customers_group_id']];
          } else {
              $sppc_cg_products_price8_qty = '';
          }
          echo tep_draw_input_field('sppcproducts_price8_qty[' . $customers_group['customers_group_id'] . ']', $sppc_cg_products_price8_qty, 'size="10"');
?></td>
      </tr>
      </tr>

    </table>
    <?php
      }
?>
	</div>
<!-- end qty price breaks tab -->
<!-- Descriptions tab -->
	<div id="tab-description">
    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
    <hr />
    <label><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?>
    <label>
    <div id="tooltip-tab" class="tooltip-toggler" style="display:inline;"><img
  src="templates/admin/images/help_img.jpg"
  title="Product Description - Enter the text you want in the products description area. "
  width="16px" height="16px" border="0"></div>
    <a style="display:block; font-style: italic; margin-left: 15px; font-weight: normal; font-size: .9em;" rel="lightbox-page" HREF="tabshowto.php">(How do I create tabs in my

    product description? ) </a>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_textarea_field_ckeditor('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])));
?>
    </span>
    <?php
      }
?>
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <br />
    <label>
    <?php
          if ($i == 0)
              echo TEXT_PRODUCTS_SHORT;
?>
    <label>
    <div id="tooltip-shortDesc" class="tooltip-toggler" style="display:inline;"><img
  src="templates/admin/images/help_img.jpg"
  title="Products Short Descritpion - The product short description shows in various other places besides the main product page where the main description shows. The products short description may or may not be enabled for display in your store."
  width="16px" height="16px" border="0"></div>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_textarea_field_ckeditor('products_short[' . $languages[$i]['id'] . ']', 'soft', '70', '4', (isset($products_short[$languages[$i]['id']]) ? $products_short[$languages[$i]['id']] : tep_get_products_short($pInfo->products_id, $languages[$i]['id'])));
?>
    <hr />
    <?php
      }
?>
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo 'Video Code or any Code, Enter YouTube Videos etc.';
?>
    </label>
    <div id="tooltip-vcode" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Video Code Price - You can enter in any code here, such as code from you tube etc." /> </div>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '<br />' . tep_draw_textarea_field('products_info_desc[' . $languages[$i]['id'] . ']', 'soft', '70', '4', (isset($products_info_desc[$languages[$i]['id']]) ? $products_info_desc[$languages[$i]['id']] : tep_get_products_info_desc($pInfo->products_id, $languages[$i]['id'])));
?>
    <?php
      }
?>
	</div>
<!-- end descriptions tab -->
<!-- MVS tab -->
	<div id="tab-multi-vendor">
    <label><?php echo TEXT_PRODUCTS_VENDORS; ?></label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_pull_down_menu('vendors_id', $vendors_array, $pInfo->vendors_id);
?>
    <div class="tooltip-toggler" id="tooltip-vendor"
  style="display: inline;"><img src="templates/admin/images/help_img.jpg"
  title="Multiple Vendor System - This is part of multi vendor system which can be enabled in your store. This allows assigning of diffferent shipping roles to differnt products and automation of vendor fulfilment emails."
  width="16px" height="16px" border="0"></div>
    <hr />
    <label>
    <?php
      echo TEXT_VENDORS_PRODUCT_PRICE_BASE;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('vendors_product_price', $pInfo->vendors_product_price, 'onKeyUp="updateNet()"');
?>
    <div id="tooltip-vprice" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Vendors Base Price - This price has no visibility in the catalog this is for internal record keeping only, so you know how much you are paying per product." /> </div>
    <hr />
    <label>
    <?php
      echo TEXT_VENDORS_PROD_COMMENTS;
?>
    </label>
    <div id="tooltip-vnotes" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Vendors Comments Notes - This has no visibility in the catalog this is for internal record keeping only." /> </div>
    <br />
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_textarea_field('vendors_prod_comments', 'soft', '70', '5', (isset($vendors_prod_comments) ? $vendors_prod_comments : tep_get_vendors_prod_comments($pInfo->products_id)));
?>
    <hr>
    <label>
    <?php
      echo TEXT_VENDORS_PROD_ID;
?>
    </label>
    <?php
      echo tep_draw_separator('pixel_trans.png', '0', '0') . '' . tep_draw_input_field('vendors_prod_id', $pInfo->vendors_prod_id);
?>
    <div id="tooltip-vitem" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Vendors Item Number - This has no visibility in the catalog this is for internal record keeping only." /> </div>
	</div>
<!-- end MVS tab -->
<!-- Meta/SEO tab -->
	<div id="tab-meta-seo">
    <?php
?>
    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
    <label><?php if ($i == 0) echo TEXT_PRODUCTS_SEO_URL; ?></label>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('products_seo_url[' . $languages[$i]['id'] . ']', (isset($products_seo_url[$languages[$i]['id']]) ? $products_seo_url[$languages[$i]['id']] : tep_get_products_seo_url($pInfo->products_id, $languages[$i]['id'])));
?>
    <div class="tooltip-toggler" id="tooltip-seo-url"
  style="display: inline;"><img src="templates/admin/images/help_img.jpg"
  title="Custom SEO URls - If you want custom SEO URL, leave blank for auto generated SEO URL. No strange characters allowed , only a,b,c,1,2,3 type characters."
  width="16px" height="16px" border="0"></div>
    <hr />
    <?php
      }
?>
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo TEXT_PRODUCTS_PAGE_TITLE;
?>
    </label>
    <div id="tooltip-mtitle" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Meta Products Title Tag - The Meta title tag is a in code tag that search engines use, the title is very important it’s the part that is displayed as title when someone searches on Google for example. You can leave this blank and the products name will automatically be used." /> </div>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '<br />' . tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? stripslashes($products_head_title_tag[$languages[$i]['id']]) : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id'])));
?>
    <hr />
    <?php
      }
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo TEXT_PRODUCTS_HEADER_DESCRIPTION;
?>
    </label>
    <div id="tooltip-mdesc" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Meta Products Description Tag - The Meta description tag is a in code tag that search engines use, the description is very important it’s the part that is displayed as description when someone searches on Google for example. This field also is where keywords and key phrases are matched. Both your title, description and in page description are important for search engine key phrase matching." /> </div>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '<br />' . tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? stripslashes($products_head_desc_tag[$languages[$i]['id']]) : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])));
?>
    <hr />
    <?php
      }
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
?>
    <label>
    <?php
          if ($i == 0)
              echo TEXT_PRODUCTS_KEYWORDS;
?>
    </label>
    <div id="tooltip-mkey" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Meta Keywords Tag - The Meta keyword tag is a in code tag that search engines use, the keyword tags are typically over abused and do not carry much weight with search engines. Search engines rely more on what’s actually in the page rather then what’s defined here." /> </div>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '<br />' . tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])));
      }
?>
	</div>
<!-- end meta/seo tab -->
<!-- Xtra Fields tab -->
	<div id="tab-extra-fields">
Product extra fields help
    <div id="tooltip-extraf" class="tooltip-toggler"
  style="display: inline; width: 0px;"><img height="16px" border="0"
  width="16px" src="templates/admin/images/help_img.jpg"
  title="Product Extra Fields - These are dynamic fields you can define in the menu product extra fields under catalog. These extra field entries give you places to define extra fields in your products. Product extra fields are typically good for repetitive field entries such as width, height etc. which are routine with some product types. Only if an extra field is entered will it show on the product." /> </div>
    <br />
    <?php
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $languages_array[$languages[$i]['id']] = $languages[$i];
      }
      $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");
      while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
          if ($extra_fields['languages_id'] == 0) {
              $m = tep_draw_separator('pixel_trans.png', '0', '0');
          } else
              $m = tep_image(DIR_WS_CATALOG_LANGUAGES . $languages_array[$extra_fields['languages_id']]['directory'] . '/images/' . $languages_array[$extra_fields['languages_id']]['image'], $languages_array[$extra_fields['languages_id']]['name']);
?>
    <label>
    <?php
          echo $extra_fields['products_extra_fields_name'];
?>
    :</label>
    <?php
          echo $m . '' . tep_draw_input_field("extra_field[" . $extra_fields['products_extra_fields_id'] . "]", $pInfo->extra_field[$extra_fields['products_extra_fields_id']]);
?>
    <br />
    <?php
      }
?>
	</div>
<!-- end xtra fields tab -->
<!-- Auto Parts tab -->
	<div id="tab-auto-parts">
  <?php
      if (AUTO_CONFIG == 'true') {
?>
    <table>
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php
          echo TEXT_PRODUCTS_YMM_DELETE;
?></td>
        <td class="dataTableHeadingContent"><?php
          echo TEXT_PRODUCTS_CAR_MAKE;
?></td>
        <td class="dataTableHeadingContent"><?php
          echo TEXT_PRODUCTS_CAR_MODEL;
?></td>
        <td class="dataTableHeadingContent" nowrap><?php
          echo TEXT_PRODUCTS_CAR_YEARS;
?></td>
      </tr>
      <tr>
        <td colspan="4"><br />
          <hr>
          <br />
        </td>
      </tr>
      <?php
          $i = 1;
          if ($_GET['pID'] && $_GET['pID'] != '') {
              $q = tep_db_query("select * from products_ymm where products_id = " . $_GET['pID']);
              if (tep_db_num_rows($q) > 0) {
                  while ($r = tep_db_fetch_array($q)) {
                      echo '<tr>

            <td class="dataTableContent"><input type="checkbox" name="delete_ymm_' . $i . '" value="' . $r['id'] . '" ' . (isset($_POST['delete_ymm_' . $i]) && $_POST['delete_ymm_' . $i] ? 'checked' : '') . '></td>

            <td class="dataTableContent">' . ($r['products_car_make'] != '' ? $r['products_car_make'] : 'all') . '</td>

            <td class="dataTableContent">' . ($r['products_car_model'] != '' ? $r['products_car_model'] : 'all') . '</td>

            <td class="dataTableContent">' . $r['products_car_year_bof'] . '-' . $r['products_car_year_eof'] . '</td>

          </tr>

          ';
                      $i++;
                  }
              } else {
                  echo '<tr>

            <td class="dataTableContent"></td>

            <td class="dataTableContent">all</td>

            <td class="dataTableContent">all</td>

            <td class="dataTableContent">0 - 0</td>

          </tr>';
              }
          }
?>
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td class="dataTableContent"><b>
          <?php
          echo TEXT_PRODUCTS_YMM_NEW;
?>
          --></b></td>
        <td class="dataTableContent"><?php
          echo tep_draw_input_field('products_car_make0', $pInfo->products_car_make0);
?></td>
        <td class="dataTableContent"><?php
          echo tep_draw_input_field('products_car_model0', $pInfo->products_car_model0);
?></td>
        <td class="dataTableContent"><?php
          echo tep_draw_input_field('products_car_year_bof0', $pInfo->products_car_year_bof0, 'size="4"') . ' - ' . tep_draw_input_field('products_car_year_eof0', $pInfo->products_car_year_eof0, 'size="4"');
?>
          <input type="hidden" name="ymm_count"
      value="<?php
          echo $i - 1;
?>"></td>
      </tr>
      <?php
          $pinfo_array = get_object_vars($pInfo);
          for ($i = 1; $i < (int)YMM_NUMBER_OF_INPUT_FIELDS; $i++) {
?>
      <tr>
        <td class="dataTableContent"></td>
        <td class="dataTableContent"><?php
              echo tep_draw_input_field('products_car_make' . $i, (isset($pinfo_array['products_car_make' . $i]) ? $pinfo_array['products_car_make' . $i] : ''));
?></td>
        <td class="dataTableContent"><?php
              echo tep_draw_input_field('products_car_model' . $i, (isset($pinfo_array['products_car_model' . $i]) ? $pinfo_array['products_car_model' . $i] : ''));
?></td>
        <td class="dataTableContent"><?php
              echo tep_draw_input_field('products_car_year_bof' . $i, (isset($pinfo_array['products_car_year_bof' . $i]) ? $pinfo_array['products_car_year_bof' . $i] : ''), 'size="4"') . ' - ' . tep_draw_input_field('products_car_year_eof' . $i, (isset($pinfo_array['products_car_year_eof' . $i]) ? $pinfo_array['products_car_year_eof' . $i] : ''), 'size="4"');
?></td>
      </tr>
      <?php
          }
?>
    </table>
  </div>
  <?php
      }
?>
	</div>
<!-- end auto parts tab -->

</div>
<script>
	$("#product-admin-tabs").tabs();
</script>





<?php
	echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.png', (isset($_GET['pID']) ? IMAGE_UPDATE : IMAGE_INSERT)) . '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . IMAGE_CANCEL . '</a>';
?>
</div>
</form>
<?php
  } elseif ($action == 'new_product_preview') {
      if (tep_not_null($_POST)) {
          $pInfo = new objectInfo($_POST);
          $products_name = $_POST['products_name'];
          $products_description = $_POST['products_description'];
          $products_short = $_POST['products_short'];
          $products_head_title_tag = $_POST['products_head_title_tag'];
          $products_head_desc_tag = $_POST['products_head_desc_tag'];
          $products_head_keywords_tag = $_POST['products_head_keywords_tag'];
          $products_seo_url = $_POST['products_seo_url'];
          $products_url = $_POST['products_url'];
          $products_info_title = $_POST['products_info_title'];
          $products_info_desc = $_POST['products_info_desc'];
      } else {
          $product_query = tep_db_query("select p.products_id, pd.language_id,
               pd.products_name, pd.products_description, pd.products_head_title_tag,pd.products_info_title,pd.products_info_desc, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_quantity,
               p.products_model, p.products_mpn, p.products_gtin, p.vendors_prod_id, p.products_image, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks,
               p.vendors_product_price,p.map_price, p.msrp_price, p.products_weight, p.vendors_prod_comments, p.products_date_added,
               p.products_last_modified, p.products_date_available,p.pSortOrder, p.products_status, p.manufacturers_id,
               p.vendors_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where
               p.products_id = pd.products_id and p.products_id = '" . (int)$_GET['pID'] . "'");
          $product = tep_db_fetch_array($product_query);
          $pInfo = new objectInfo($product);
          $products_image_name = $pInfo->products_image;
      }
      $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';
      echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
              $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_info_title = tep_get_products_info_title($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_info_desc = tep_get_products_info_desc($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_seo_url = tep_get_products_seo_url($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_short = tep_get_products_short($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
              $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
              $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
              $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
              $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
          } else {
              $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
              $pInfo->products_info_title = tep_db_prepare_input($products_info_title[$languages[$i]['id']]);
              $pInfo->products_info_desc = tep_db_prepare_input($products_info_desc[$languages[$i]['id']]);
              $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
              $pInfo->products_seo_url = tep_db_prepare_input($products_seo_url[$languages[$i]['id']]);
              $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
              $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
              $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
              $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
          }
?>
<div class="hide_preview">
  <h1>
    <?php
          echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . $pInfo->products_name;
?>
  </h1>
  <br />
  <?php
          echo $currencies->format($pInfo->products_price);
?>
  <?php
?>
  <br />
  <?php
          echo "Vendor Price: " . $currencies->format($pInfo->vendors_product_price);
?>
  <?php
?>
  <hr>
  <?php
          echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="0"');
?>
  <?php
          if ($_GET['read'] == 'only') {
              $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$_GET['pID']);
              while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
                  $extra_fields_array[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
              }
          } else {
              $extra_fields_array = $_POST['extra_field'];
          }
          $extra_fields_names_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE languages_id='0' or languages_id='" . (int)$languages[$i]['id'] . "' ORDER BY products_extra_fields_order");
          while ($extra_fields_names = tep_db_fetch_array($extra_fields_names_query)) {
              $extra_field_name[$extra_fields_names['products_extra_fields_id']] = $extra_fields_names['products_extra_fields_name'];
              echo '<B>' . $extra_fields_names['products_extra_fields_name'] . ':</B>' . stripslashes($extra_fields_array[$extra_fields_names['products_extra_fields_id']]) . '<BR>' . "\n";
          }
          echo "<br />" . $pInfo->products_description;
?>
  <div align="left">
    <?php
          echo '<b><u>' . $pInfo->products_name . '</u></b>';
?>
  </div>
  <br />
  <?php
          if ($pInfo->products_short != '') {
              echo $pInfo->products_short;
          } else {
              $bah = explode(" ", $pInfo->products_description);
              for ($desc = 0; $desc < MAX_FEATURED_WORD_DESCRIPTION; $desc++) {
                  echo "$bah[$desc] ";
              }
              echo '...';
          }
?>
  <br />
  <?php
          echo tep_image(DIR_WS_IMAGES . 'pixel_trans.png', '', '0', '0') . '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price);
?>
  <br>
  <?php
          echo TEXT_BUY_NOW;
?>
  </a>
  <?php
          echo TEXT_MORE_INFO;
?>
  </a> <br />
  <hr>
  <div class="pp_image">
    <?php
          echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="0"') . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . $pInfo->products_description;
?>
  </div>
  <?php
          if ($pInfo->products_url) {
?>
  <br />
  <?php
              echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url);
?>
  <?php
          }
?>
  <?php
          if ($pInfo->products_date_available > date('Y-m-d')) {
?>
  <br />
  <?php
              echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available));
?>
  <br />
  <?php
              } else
              {
?>
  <br />
  <?php
                  echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added));
?>
  <br />
  <?php
              }
?>
  <?php
          }
          if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
              if (isset($_GET['origin'])) {
                  $pos_params = strpos($_GET['origin'], '?', 0);
                  if ($pos_params != false) {
                      $back_url = substr($_GET['origin'], 0, $pos_params);
                      $back_url_params = substr($_GET['origin'], $pos_params + 1);
                  } else {
                      $back_url = $_GET['origin'];
                      $back_url_params = '';
                  }
              } else {
                  $back_url = FILENAME_CATEGORIES;
                  $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
              }
?>
  <?php
              echo '<a class="button" href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . IMAGE_BACK . '</a>';
?>
  <?php
              } else
              {
?>
</div>
<div class="hide_preview">
  <?php
                  reset($_POST);
                  while (list($key, $value) = each($_POST)) {
                      if (is_array($value)) {
                          while (list($k, $v) = each($value)) {
                              echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
                          }
                      } else {
                          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
                      }
                  }
                  $languages = tep_get_languages();
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                      echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_short[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_short[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
                      echo tep_draw_hidden_field('products_image_2', stripslashes($products_image_2_name));
                      echo tep_draw_hidden_field('products_image_3', stripslashes($products_image_3_name));
                      echo tep_draw_hidden_field('products_image_4', stripslashes($products_image_4_name));
                      echo tep_draw_hidden_field('products_image_5', stripslashes($products_image_5_name));
                      echo tep_draw_hidden_field('products_image_6', stripslashes($products_image_6_name));
                      if ($_POST['extra_field']) {
                          foreach ($_POST['extra_field'] as $key => $val) {
                              echo tep_draw_hidden_field('extra_field[' . $key . ']', stripslashes($val));
                          }
                      }
                      echo tep_draw_hidden_field('categories_htc_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_htc_description[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_info_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_info_title[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_info_desc[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_info_desc[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
                      echo tep_draw_hidden_field('products_seo_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_seo_url[$languages[$i]['id']])));
                  }
                  echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
                  echo '</div>';
                  echo '<div class="submit_product">Your Product is ready for publishing, please click update to continue or back to make changes or click cancel to quit<br><br>';
                  echo tep_image_submit('button_back.png', IMAGE_BACK, 'name="edit"') . '';
                  if (isset($_GET['pID'])) {
                      echo tep_image_submit('button_update.png', IMAGE_UPDATE);
                  } else {
                      echo tep_image_submit('button_insert.png', IMAGE_INSERT);
                  }
                  echo '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . IMAGE_CANCEL . '</a>';
?>
</div>
</form>
<?php
              }
          } else {
?>
<h3>
  <?php
              echo HEADING_TITLE;
?>
</h3>
<!-- <div class="searchwrap">  <?php
              echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
              echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
              echo '</form>';
?></div>-->
<div class="searchwrap">
  <?php
              echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
              echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
              echo '</form>';
?>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>

  <td valign="top">

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_CATEGORIES_PRODUCTS;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
              echo TABLE_HEADING_STATUS;
?></td>
      <td class="dataTableHeadingContent" align="right"><?php
              echo TABLE_HEADING_ACTION;
?>
        </td>
    </tr>
    <?php
              $categories_count = 0;
              $rows = 0;
              if (isset($_GET['search'])) {
                  $search = tep_db_prepare_input($_GET['search']);
                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_seo_url, c.categories_image,c.altProdDisplay, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
              } else {
                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_seo_url, c.categories_image,c.altProdDisplay, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
              }
              while ($categories = tep_db_fetch_array($categories_query)) {
                  $categories_count++;
                  $rows++;
                  if (isset($_GET['search']))
                      $cPath = $categories['parent_id'];
                  if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                      $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
                      $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));
                      $cInfo_array = array_merge($categories, $category_childs, $category_products);
                      $cInfo = new objectInfo($cInfo_array);
                  }
                  if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
                      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
                  } else {
                      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
                  }
?>
    <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '"></a><b>' . $categories['categories_name'] . '</b>';
?></td>
      <td class="dataTableContent" align="center"></td>
      <td class="dataTableContent" align="right"><?php
                  if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
                      echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', '');
                  } else {
                      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
                  }
?>
        </td>
    </tr>
    <?php
              }
              $products_count = 0;
              $products_count = 0;
              if (isset($_GET['search'])) {
                  $products_query = tep_db_query("select p.products_featured, p.products_id, pd.products_name,pd.products_info_title,pd.products_info_desc, pd.products_seo_url,p.products_quantity, p.products_image, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.vendors_product_price,p.map_price, p.msrp_price, p.vendors_prod_comments, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status,p.pSortOrder, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and (pd.products_name like '%" . tep_db_input($search) . "%'  or p.products_model like '%" . tep_db_input($search) . "%'  or p.products_mpn like '%" . tep_db_input($search) . "%'  or p.products_gtin like '%" . tep_db_input($search) . "%' or p.products_id like '%" . tep_db_input($search) . "%') order by pd.products_name");
              } else {
                  $products_query = tep_db_query("select p.products_featured, p.products_id, pd.products_name, pd.products_info_title,pd.products_info_desc, pd.products_seo_url,p.products_quantity, p.products_image, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.vendors_product_price,p.map_price,  p.msrp_price, p.vendors_prod_comments, p.products_date_added, p.products_last_modified, p.products_date_available,p.pSortOrder, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
              }
              while ($products = tep_db_fetch_array($products_query)) {
                  $products_count++;
                  $rows++;
                  if (isset($_GET['search']))
                      $cPath = $products['categories_id'];
                  if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                      $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
                      $reviews = tep_db_fetch_array($reviews_query);
                      $pInfo_array = array_merge($products, $reviews);
                      $pInfo = new objectInfo($pInfo_array);
                  }
                  if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product&read=only') . '\'">' . "\n";
                  } else {
                      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
                  }
?>
    <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.png', ICON_PREVIEW) . '</a>' . $products['products_name'];
?></td>
      <td class="dataTableContent" width="35" align="center"><?php
                  if ($products['products_status'] == '1') {
                      echo tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10) . '<div class="fixtable"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                  } else {
                      echo '<div class="fixtable"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>' . tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10);
                  }
?>
        </div>
      </td>
      <td class="<?php
                  echo $current_row;
?>" width="35"
        align="center" nowrap align="top">
      <?php
                  if ($products['products_featured'] == '1') {
                      echo tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10) . '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_featured&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                  } else {
                      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_featured&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', IMAGE_ICON_STATUS_GREEN_LIGHT_FEATURED, 10, 10) . '</a>' . tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10);
                  }
?>
      <div align="right">
        <?php
                  if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                      echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', '');
                  } else {
                      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
                  }
?>
      </div>
    </td>

    <?php
?>
    </tr>

    <?php
              }
              $cPath_back = '';
              if (sizeof($cPath_array) > 0) {
                  for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
                      if (empty($cPath_back)) {
                          $cPath_back .= $cPath_array[$i];
                      } else {
                          $cPath_back .= '_' . $cPath_array[$i];
                      }
                  }
              }
              $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
    <tr>
      <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php
              echo TEXT_CATEGORIES . '' . $categories_count . '<br>' . TEXT_PRODUCTS . '' . $products_count;
?></td>
            <td align="right" class="smallText"><?php
              if (sizeof($cPath_array) > 0)
                  echo '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . IMAGE_BACK . '</a>';
              if (!isset($_GET['search']))
                  echo '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . IMAGE_NEW_CATEGORY . '</a>' . (isset($current_category_id) && $current_category_id != 0 ? '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . IMAGE_NEW_PRODUCT . '</a>' : '');
?>
              </td>
          </tr>
        </table></td>
    </tr>
  </table>
  </td>

  <?php
              $heading = array();
              $contents = array();
              switch ($action) {
                  case 'new_category':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');
                      $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
                      $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);
                      $category_inputs_string = '';
                      $languages = tep_get_languages();
                      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
                          $category_htc_title_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']');
                          $category_htc_desc_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']');
                          $category_htc_keywords_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']');
                          $category_htc_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'hard', 30, 5, '');
                      }
                      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          $category_seo_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_seo_url[' . $languages[$i]['id'] . ']');
                      }
                      $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
                      $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_SEO_URL . $category_seo_string);
                      $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
                      $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
                       $contents[] = array('text' => '<br>Use alternate Product listing display?' . tep_draw_checkbox_field('altProdDisplay', 1, ($cInfo->altProdDisplay == 1) ? true : false));


                      $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
                      $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
                      $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
                      $contents[] = array('text' => '<br>' . 'Header Tags Categories Description' . $category_htc_description_string);
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'edit_category':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');
                      $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                      $contents[] = array('text' => TEXT_EDIT_INTRO);
                      $category_inputs_string = '';
                      $languages = tep_get_languages();
                      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
                          $category_htc_title_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_title($cInfo->categories_id, $languages[$i]['id']));
                          $category_htc_desc_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']));
                          $category_htc_keywords_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_keywords($cInfo->categories_id, $languages[$i]['id']));
                          $category_htc_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'hard', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id'])) . '</textarea>';
                      }
                      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          $category_seo_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '' . tep_draw_input_field('categories_seo_url[' . $languages[$i]['id'] . ']', tep_get_category_seo_url($cInfo->categories_id, $languages[$i]['id']));
                      }
                      $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
                      $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_SEO_URL . $category_seo_string);
                      $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name, '200', 'auto') . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
                      if($cInfo->categories_image!='')$deleteBox='<br/><input type="checkbox" name="delete_categories_image" value="on"/> Delete Categories Image';else $deleteBox='';
                      $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>'.tep_draw_hidden_field('existing_categories_image',$cInfo->categories_image) . tep_draw_file_field('categories_image').$deleteBox);
                      $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
                                          $contents[] = array('text' => '<div class="clear"></div>Use alternate Product listing display?<br>' . tep_draw_checkbox_field('altProdDisplay', 1, ($cInfo->altProdDisplay == 1) ? true : false));

                      $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
                      $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
                      $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
                      $contents[] = array('text' => '<br>' . 'Categories Description' . $category_htc_description_string);
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'delete_category':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');
                      $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                      $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
                      $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
                      if ($cInfo->childs_count > 0)
                          $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
                      if ($cInfo->products_count > 0)
                          $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'move_category':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');
                      $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                      $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
                      $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.png', IMAGE_MOVE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'delete_product':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');
                      $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                      $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
                      $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');
                      $product_categories_string = '';
                      $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                      for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                          $category_path = '';
                          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                              $category_path .= $product_categories[$i][$j]['text'] . '>>';
                          }
                          $category_path = $category_path;
                          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i]) - 1]['id'], true) . '' . $category_path . '<br>';
                      }
                      $product_categories_string = substr($product_categories_string, 0, -4);
                      $contents[] = array('text' => '<br>' . $product_categories_string);
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'move_product':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');
                      $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                      $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
                      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                      $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.png', IMAGE_MOVE) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  case 'copy_to':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');
                      $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                      $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
                      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
                      $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.png', IMAGE_COPY) . ' <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  default:
                      if ($rows > 0) {
                          if (isset($cInfo) && is_object($cInfo)) {
                              $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');
                              $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . IMAGE_DELETE . '</a> <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . IMAGE_MOVE . '</a>');
                              $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
                              if (tep_not_null($cInfo->last_modified))
                                  $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
                              $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, '200', '100%') . '<br>' . $cInfo->categories_image);
                              $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
                          } elseif (isset($pInfo) && is_object($pInfo)) {
                              $vendors_query_2 = tep_db_query("select v.vendors_id, v.vendors_name from vendors v, products p where v.vendors_id=p.vendors_id and p.products_id='" . $pInfo->products_id . "'");
                              while ($vendors_2 = tep_db_fetch_array($vendors_query_2)) {
                                  $current_vendor_name = $vendors_2['vendors_name'];
                              }
                              $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');
                              $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . IMAGE_DELETE . '</a><a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . IMAGE_MOVE . '</a> <a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . IMAGE_COPY_TO . '</a><a class="button" href="./new_attributes.php?action=select&current_product_id=' . $pInfo->products_id . '&cPath=' . $cPath . '">Edit Attributes</a><a class="button" href="' . tep_href_link("stock.php", 'product_id=' . $pInfo->products_id) . '">' . Stock . '</a><a class="button" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=promote') . '">Promote</a>');
//$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>' . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=promote') . '">Promote</a>');
                              $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
                              if (tep_not_null($pInfo->products_last_modified))
                                  $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
                              if (date('Y-m-d') < $pInfo->products_date_available)
                                  $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
                              $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, '200', '100%') . '<br>' . $pInfo->products_image);
                              $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . '<b> ' . $currencies->format($pInfo->products_price) . '</b><br>Vendor:  <b>' . $current_vendor_name . '</b><br>' . TEXT_VENDORS_PRODUCT_PRICE_INFO . '<b>' . $currencies->format($pInfo->vendors_product_price) . '</b><br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' <b>' . $pInfo->products_quantity . '</b>');
                              $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
                          }
                      } else {
                          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');
                          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
                      }
                      break;
              }
              if ((tep_not_null($heading)) && (tep_not_null($contents))) {
                  echo '            <td valign="top"  width="220px">' . "\n";
                  $box = new box();
                  echo $box->infoBox($heading, $contents);
                  echo '            </td>' . "\n";
              }
?>
  </td>

  </tr>

</table>
<?php
          }
?>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php
          require(DIR_WS_INCLUDES . 'footer.php');
?>
<script>
	$(document).ready(function(){
		$(".ui-cal input").datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
</script>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
          require(DIR_WS_INCLUDES . 'application_bottom.php');
?>