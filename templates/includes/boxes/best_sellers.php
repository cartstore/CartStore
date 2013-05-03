<?php
  if (isset($current_category_id) && ($current_category_id > 0)) {
      $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where " . (YMM_FILTER_BEST_SELLERS_BOX == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
      $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_BEST_SELLERS_BOX == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }
  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>
<!-- best_sellers //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>BEST SELLERS</h3>
        <ul>
          <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_BESTSELLERS);
      new infoBoxHeading($info_box_contents, false, false);
      $rows = 0;
      $bestsellers_list = '';
      while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
          $rows++;
          $bestsellers_list .= '<li><a class="l3" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '"><span class="number">' . tep_row_number_format($rows) . '.</span> ' . $best_sellers['products_name'] . '</a></li>';
      }
      $bestsellers_list .= '';
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $bestsellers_list);
      new infoBox($info_box_contents);
?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- best_sellers_eof //-->
<?php
  }
?>