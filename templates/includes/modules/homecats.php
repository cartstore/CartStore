<?php
  $sql_query = "SELECT * FROM categories a, categories_description b WHERE a.categories_id=b.categories_id AND a.parent_id='0'";
  $rs_query = tep_db_query($sql_query);
  $cnt = 0;
  while ($dt_query = tep_db_fetch_array($rs_query)) {
      $categories_products_array[$cnt] = $dt_query;
      $cnt++;
  }

  if (sizeof($categories_products_array) <> '0') {
      print('<div class="page-title">
<h3 class="subtitle">Categories</h3>
</div>
 
');
      $row = 0;
      $col1 = 0;
      $count = 1;
      $rowcount_value = 3;
      $rowcount = 1;
      $total = sizeof($categories_products_array);
      $col = 0;
      for ($i = 0; $i < sizeof($categories_products_array); $i++) {
          //<!-- <a class="cat_image" href="' . $categories_products_array[$i]['categories_name'] . '-c-' . $categories_products_array[$i]['categories_id'] . '.html">' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a> -->';
          print('<div class="item-allcats col-lg-3 col-md-3 col-sm-3 col-xs-12 center"><a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories_products_array[$i]['categories_id'], 'NONSSL') . '" class="">' . (!empty($categories_products_array[$i]['categories_image']) ? '' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '' : '') . '<span>' . $categories_products_array[$i]['categories_name'] . '</span></a></div>');
          $col++;
          if ($col == $rowcount_value && $count != $total){
				echo '';
				$col = 0;
				$row++;
			}
          $count++;
      }
      echo '
          ';

  }
?>