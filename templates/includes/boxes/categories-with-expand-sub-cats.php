<?php
  
  function preorder($cid, $level, $foo, $cpath)
  {
      global $categories_string, $_GET;
      
      if ($cid != 0) {
          for ($i = 0; $i < $level; $i++)
              $categories_string .= '  ';
          $categories_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath
=' . $cpath . $cid) . '">';
          
          $bold = strstr($_GET['cPath'], $cpath . $cid . '_') || $_GET['cPath'] == $cpath . $cid;
          
          if ($bold)
              $categories_string .= '<b>';
          $categories_string .= $foo[$cid]['name'];
          if ($bold)
              $categories_string .= '</b>';
          $categories_string .= '</a>';
          
          if (SHOW_COUNTS == 'true') {
              $products_in_category = tep_count_products_in_category($cid);
              if ($products_in_category > 0) {
                  $categories_string .= ' (' . $products_in_category . ')';
              }
          }
          $categories_string .= '';
      }
      
      
      function tep_show_category2($counter)
      {
          global $foo, $categories_string, $id;
          for ($a = 0; $a < $foo[$counter]['level']; $a++) {
              $categories_string .= "  ";
          }
      }
  }
?>
<!-- show_subcategories //-->
<?php
  
  
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false, false, true);
  
  
  
  
  
  $status = tep_db_num_rows(tep_db_query('describe ' . TABLE_CATEGORIES . ' status'));
  $query = "select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
            where c.categories_id = cd.categories_id";
  
  if ($status > 0)
      $query .= " and c.status = '1'";
  
  
  if (array_key_exists('cPath', $_GET)) {
      $all_levels = explode('_', $_GET['cPath']);
      $top_level = array_shift($all_levels);
      $all_levels = array();
      tep_get_parent_categories($all_levels, $top_level);
      if (count($all_levels) > 0) {
          $top_level = array_pop($all_levels);
      }
  } else {
      if (tep_not_null($_GET['products_id'])) {
          $all_levels = explode('_', tep_get_product_path($_GET['products_id']));
          $top_level = array_shift($all_levels);
      } else {
          
          
          $first_category_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name limit 1");
          $first_category_row = tep_db_fetch_array($first_category_query);
          $top_level = $first_category_row['categories_id'];
      }
  }
  
  
  
  
  
  $query .= " and cd.language_id='" . $languages_id . "'
            order by sort_order, cd.categories_name";
  $categories_query = tep_db_query($query);
  
  $categories_string = '';
  preorder(0, 0, $foo, '');
  
  
  
  $info_box_contents = array();
  $row = 0;
  $col = 0;
  while ($categories = tep_db_fetch_array($categories_query)) {
      if ($categories['parent_id'] == 0) {
          
          $temp_cPath_array = $cPath_array;
          unset($cPath_array);
          $cPath_new = tep_get_path($categories['categories_id']);
          $text_subcategories = '';
          $subcategories_query = tep_db_query($query);
          while ($subcategories = tep_db_fetch_array($subcategories_query)) {
              
              
              if ($subcategories['parent_id'] == $categories['categories_id'] && $categories['categories_id'] == $top_level) {
                  
                  
                  $cPath_new_sub = "cPath=" . $categories['categories_id'] . "_" . $subcategories['categories_id'];
                  $text_subcategories .= '<span class="subcat"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '">' . $subcategories['categories_name'] . '</a></span>' . " ";
              }
              
              } 
              $info_box_contents[$row] = array('align' => '', 'params' => '', 'text' => '<li><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '" class="categories_top">' . $categories['categories_name'] . '</a></li>' . $text_subcategories);
              $col++;
              if ($col > 0) {
                  $col = 0;
                  $row++;
              }
              
              $cPath_array = $temp_cPath_array;
          }
      }
      new infoBox($info_box_contents, true);
?>
<!-- show_subcategories_eof //-->