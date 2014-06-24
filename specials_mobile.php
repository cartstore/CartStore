<?php
  require('includes/application_top.php');
  require('includes/functions/categories_description.php');
  global $customer_group_id;
  if (!isset($customer_group_id)) {
      $customer_group_id = '0';
  }
  $category_depth = 'products';
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
  if (file_exists(DIR_WS_INCLUDES . 'header_tags.php')) {
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><h1>
        <?php
      echo HEADING_TITLE;
?>
      </h1>
<?php
      } else
      {
?>
      <h1>
        <?php
          echo HEADING_TITLE;
?>
      </h1>
      <?php
      }
      $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL, 'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME, 'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER, 'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE, 'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY, 'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT, 'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE, 'PRODUCT_LIST_MULTIPLE' => PRODUCT_LIST_MULTIPLE, 'PRODUCT_LIST_BUY_NOW_MULTIPLE' => PRODUCT_LIST_BUY_NOW_MULTIPLE, 'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
      asort($define_list);
      $column_list = array();
      reset($define_list);
      while (list($key, $value) = each($define_list)) {
          if ($value > 0)
              $column_list[] = $key;
      }
      if (!tep_session_is_registered('sppc_customer_group_id')) {
          $customer_group_id = '0';
      } else {
          $customer_group_id = $sppc_customer_group_id;
      }
      if ($customer_group_id == '0') {
          tep_db_check_age_specials_retail_table();
      }
      $status_product_prices_table = false;
      $status_need_to_get_prices = false;
      if ((isset($_GET['sort'])) && (preg_match('/[1-8][ad]/', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0') {
          $_sort_col = substr($_GET['sort'], 0, 1);
          if ($column_list[$_sort_col - 1] == 'PRODUCT_LIST_PRICE') {
              $status_need_to_get_prices = true;
          }
      }
      if ($status_need_to_get_prices == true && $customer_group_id != '0') {
          $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES . $customer_group_id;
          tep_db_check_age_products_group_prices_cg_table($customer_group_id);
          $status_product_prices_table = true;
      }
      $select_column_list = '';
      if ((!isset($_GET['sort_id']))) {
          $listing_sql = " order by pd.products_name ";
      } else {
          switch ($_GET['sort_id']) {
              case 'low':
                  $listing_sql = " order by p.products_price ";
                  break;
              case 'high':
                  $listing_sql = " order by p.products_price desc";
                  break;
              case 'title':
                  $listing_sql = " order by pd.products_name ";
                  break;
          }
      }
      if ($customer_group_id > 0)
          $customerQuery = " and s.customers_group_id = " . (int)$customer_group_id . " ";
      $listing_sql = "select p.products_model,p.products_image,p.products_id,p.map_price, p.msrp_price, pd.products_name, pd.products_short, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1'   $customerQuery " . $listing_sql;
      if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
          $category_header = tep_get_category_heading_title((int)$current_category_id);
          if (strlen($category_header) == 0) {
              $category_header = HEADING_TITLE;
          }
      }
      $image = DIR_WS_IMAGES . 'table_background_list.gif';
      if (isset($_GET['manufacturers_id'])) {
          $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
          $image = tep_db_fetch_array($image);
          $image = $image['manufacturers_image'];
      } elseif ($current_category_id) {
          $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
          $image = tep_db_fetch_array($image);
          $image = $image['categories_image'];
      }
      include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_MOBILE);
?></td>
  </tr>
</table>
<?php
      require(DIR_WS_INCLUDES . 'column_right.php');
      require(DIR_WS_INCLUDES . 'footer.php');
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>