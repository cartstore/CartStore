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
  <ul data-role="listview" data-theme="b" data-divider-theme="a">
<li data-role="list-divider" role="heading" class="ui-li ui-li-divider ui-bar-a">
				All Categories
                
					
				
            </li>

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

          echo '<li class="ui-li-has-thumb">
			<a data-transition="slide" href="' . tep_href_link(FILENAME_DEFAULT_MOBILE, 'cPath=' . $categories_products_array[$i]['categories_id'], 'NONSSL') . '">';
		  echo '<div class="imageWrapper">' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], 82, 82) . '</div>
			<h3>' . $categories_products_array[$i]['categories_name'] . '</h3></a></li>';

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
</ul>');
  }
?>