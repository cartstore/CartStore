<?php
  $sql_query = "SELECT * FROM categories a, categories_description b WHERE a.categories_id=b.categories_id AND a.parent_id='0'";
  $rs_query = tep_db_query($sql_query);
  $cnt = 0;
  while ($dt_query = tep_db_fetch_array($rs_query)) {
      $categories_products_array[$cnt] = $dt_query;
      $cnt++;
  }

  if (sizeof($categories_products_array) <> '0') {
      print('
<div class="module-product">
  <div class="mbottom">
    <div class="mTop">
      <h3>Categories</h3>


');
      $row = 0;
      $col1 = 0;
      $count = 1;
      $rowcount_value = 3;
      $rowcount = 1;
      $total = sizeof($featured_products_array);
      $col = 0;
      for ($i = 0; $i < sizeof($categories_products_array); $i++) {
          $col++;

          print('
                ');
          echo '<div class="productWrap"><!-- <a class="cat_image" href="' . $categories_products_array[$i]['categories_name'] . '-c-' . $categories_products_array[$i]['categories_id'] . '.html">' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a> -->';
          print('<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories_products_array[$i]['categories_id'], 'NONSSL') . '">' . (!empty($categories_products_array[$i]['categories_image']) ? '<div>' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</div>' : '') . $categories_products_array[$i]['categories_name'] . '</a></div>');

          $col1++;
          if ($col1 > (3 - 1) || $count == $total) {
              if ($total == 2) {
                  print('');
              }
              print('');
              if ($total / 3 != $col + 1) {
                  print("");
              }
              $row++;
              $col1 = 0;
              $col++;
          }
          $count++;
      }
      print(' 
     <div class="clear"></div>
    </div>
  </div>
</div>

<div class="clear"></div>');
  }
?>