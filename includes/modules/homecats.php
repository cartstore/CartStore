<?php
  
  
  $sql_query = "SELECT * FROM categories a, categories_description b WHERE a.categories_id=b.categories_id AND a.parent_id='0'";
  $rs_query = tep_db_query($sql_query);
  $cnt = 0;
  while ($dt_query = tep_db_fetch_array($rs_query)) {
      $categories_products_array[$cnt] = $dt_query;
      $cnt++;
  }
?>

<div class="moduletable">
<h1>Our Categories</h1>
<ul id="sublevel1">
<li>

    <?php
  if (sizeof($categories_products_array) <> '0') {
      print('');
      $row = 0;
      $col1 = 0;
      $count = 1;
      
      $rowcount_value = 3;
      $rowcount = 1;
      $total = sizeof($featured_products_array);
      $col = 0;
      for ($i = 0; $i < sizeof($categories_products_array); $i++) {
          $col++;
?>
<?php
          
?>

  
<?php
          print('
                ');
          echo '<!-- <a class="cat_image" href="' . $categories_products_array[$i]['categories_name'] . '-c-' . $categories_products_array[$i]['categories_id'] . '.html">' . tep_image(DIR_WS_IMAGES . $categories_products_array[$i]['categories_image'], $categories_products_array[$i]['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a> -->';
          print('<ul id="sublevel2"><li><a href="' . $categories_products_array[$i]['categories_name'] . '-c-' . $categories_products_array[$i]['categories_id'] . '.html">' . $categories_products_array[$i]['categories_name'] . '</a></li></ul>');
?>
  <?php
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
      print('');
  }
?>
</li>
</ul>
</div>
<div class="clear"></div>