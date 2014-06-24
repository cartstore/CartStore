<?php
  $show_ulcats_as_box = false;
  $idname_for_menu = 'mainlevel';
  $classname_for_selected = 'active';
  $classname_for_parent = 'mainlevel';
  $before_nobox_html = '';
  $after_nobox_html = '';
  
  $GLOBALS['this_level'] = 0;
  
  if ($show_ulcats_as_box) {
      echo '<tr><td>';
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
      new infoBoxHeading($info_box_contents, true, false);
  }
  
  $categories_string = tep_make_cat_ullist();
  
  if ($show_ulcats_as_box) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $categories_string);
      new infoBox($info_box_contents);
      echo '</td></tr>';
  } else {
      echo $before_nobox_html;
      echo $categories_string;
      echo $after_nobox_html;
  }
  
  function tep_make_cat_ullist($rootcatid = 0, $maxlevel = 0)
  {
      global $idname_for_menu, $cPath_array, $show_full_tree, $languages_id;
      
      if (!$show_full_tree) {
          $parent_query = 'AND (c.parent_id = "0"';
          if (isset($cPath_array)) {
              $cPath_array_temp = $cPath_array;
              foreach ($cPath_array_temp as $key => $value) {
                  $parent_query .= ' OR c.parent_id = "' . $value . '"';
              }
              unset($cPath_array_temp);
          }
          $parent_query .= ')';
      } else {
          $parent_query = '';
      }
      $result = tep_db_query('select c.categories_id, cd.categories_name, c.parent_id from ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd where c.categories_id = cd.categories_id and cd.language_id="' . (int)$languages_id . '" ' . $parent_query . ' order by sort_order, cd.categories_name');
      while ($row = tep_db_fetch_array($result)) {
          $table[$row['parent_id']][$row['categories_id']] = $row['categories_name'];
      }
      $output .= '<ul id="' . $idname_for_menu . '">';
      $output .= tep_make_cat_ulbranch($rootcatid, $table, 0, $maxlevel);
      
      for ($nest = 0; $nest <= $GLOBALS['this_level']; $nest++) {
          $output .= '</ul>';
      }
      return $output;
  }
  
  function tep_make_cat_ulbranch($parcat, $table, $level, $maxlevel)
  {
      global $cPath_array, $classname_for_selected, $classname_for_parent;
      $list = $table[$parcat];
      while (list($key, $val) = each($list)) {
          if ($GLOBALS['this_level'] != $level) {
              if ($GLOBALS['this_level'] < $level) {
                  $output .= "\n" . '<ul>';
              } else {
                  for ($nest = 1; $nest <= ($GLOBALS['this_level'] - $level); $nest++) {
                      $output .= '</ul></li>' . "\n";
                  }
              }
              $GLOBALS['this_level'] = $level;
          }
          if (isset($cPath_array) && in_array($key, $cPath_array) && $classname_for_selected) {
              $this_cat_class = ' class="' . $classname_for_selected . '"';
          } else {
              $this_cat_class = '';
          }
          
          global $current_category_id;
          if ($current_category_id > 0 && $current_category_id == $key) {
              $output .= '<li id="selected"><a id="active_menu"  href="';
          } else
              $output .= '<li class="cat_lev_' . $level . '"><a href="';
          if (!$level) {
              unset($GLOBALS['cPath_set']);
              $GLOBALS['cPath_set'][0] = $key;
              $cPath_new = 'cPath=' . $key;
          } else {
              $GLOBALS['cPath_set'][$level] = $key;
              $cPath_new = 'cPath=' . implode("_", array_slice($GLOBALS['cPath_set'], 0, ($level + 1)));
          }
          if (tep_has_category_subcategories($key) && $classname_for_parent) {
              $this_parent_class = ' class="' . $classname_for_parent . '"';
          } else {
              $this_parent_class = '';
          }
          $output .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"' . $this_parent_class . '>' . $val;
          if (SHOW_COUNTS == 'false') {
              $products_in_category = tep_count_products_in_category($key);
              if ($products_in_category > 0) {
                  $output .= '&nbsp;(' . $products_in_category . ')';
              }
          }
          $output .= '</a>';
          if (!tep_has_category_subcategories($key)) {
              $output .= '</li>' . "\n";
          }
          if ((isset($table[$key])) and (($maxlevel > $level + 1) or ($maxlevel == '0'))) {
              $output .= tep_make_cat_ulbranch($key, $table, $level + 1, $maxlevel);
          }
      }
      
      return $output;
  }
?>
</ul>