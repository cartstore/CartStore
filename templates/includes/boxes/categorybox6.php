<?php
  $show_ulcats_as_box = true;
  $show_full_tree = true;
  $idname_for_menu = 'nav2';
  $classname_for_selected = 'selected2';
  $classname_for_parent = 'daddy';
  $before_nobox_html = '';
  $after_nobox_html = '<div style="clear: both;">';
  $GLOBALS['this_level'] = 0;
  if ($show_ulcats_as_box) {
      echo '';
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
      new infoBoxHeading($info_box_contents, true, false);
  }
  $categories_string = tep_make_cat_ullist2();
  if ($show_ulcats_as_box) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $categories_string);
      new infoBox($info_box_contents);
      echo '';
  } else {
      echo $before_nobox_html;
      echo $categories_string;
      echo $after_nobox_html;
  }
  function tep_make_cat_ullist2($rootcatid = 0, $maxlevel = 0)
  {
      global $idname_for_menu, $cPath_array, $show_full_tree, $languages_id, $YMM_where, $current_category_id;
      $a = 0;
      $output = '';
      $limitCat = MAX_CATEGORY_ITEM;
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
      $result = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where  c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . "' '" . $parent_query . "' order by sort_order, cd.categories_name");
      while ($row = tep_db_fetch_array($result)) {
          if (tep_count_products_in_category((int)$row['categories_id']))
              $table[$row['parent_id']][$row['categories_id']] = $row['categories_name'];
      }

      if (count($table) > 0){
        $output .= '<ul>';
        $output .= tep_make_cat_ulbranch2($rootcatid, $table, 0, $maxlevel, $limitCat);
        for ($nest = 0; $nest <= $GLOBALS['this_level']; $nest++) {
          $output .= '</ul>';
       }
      }
      return $output;
  }
  function tep_make_cat_ulbranch2($parcat, $table, $level, $maxlevel, $limitCat = 1)
  {
      global $a, $YMM_where;
      if (tep_count_products_in_category((int)$parcat)) {
          global $cPath_array, $classname_for_selected, $classname_for_parent;
          $list = $table[$parcat];
          $output = '';
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
                  $this_cat_class = $classname_for_selected . ' ';
              } else {
                  $this_cat_class = '';
              }
              if (tep_count_products_in_category((int)$key) <= 0 && $YMM_where != "") {
              } else {
                  $output .= '<li><a href="';
                  if (!$level) {
                      unset($GLOBALS['cPath_set']);
                      $GLOBALS['cPath_set'][0] = $key;
                      $cPath_new = 'cPath=' . $key;
                  } else {
                      $GLOBALS['cPath_set'][$level] = $key;
                      $cPath_new = 'cPath=' . implode("_", array_slice($GLOBALS['cPath_set'], 0, ($level + 1)));
                  }
                  if (tep_has_category_subcategories($key) && $classname_for_parent) {
                      $this_parent_class = '';
                  } else {
                      $this_parent_class = '';
                  }
                  $output .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"' . $this_parent_class . '><span>' . $val;
                  if (SHOW_COUNTS == 'true') {
                      $products_in_category = tep_count_products_in_category($key);
                      if ($products_in_category > 0) {
                          $output .= '' . '' . '';
                      }
                  }
                  $output .= '</span></a>';
                  if (!tep_has_category_subcategories($key)) {
                      $output .= '</li>' . "\n";
                  }
                  if ((isset($table[$key])) and (($maxlevel > $level + 1) or ($maxlevel == '0'))) {
                      $output .= tep_make_cat_ulbranch2($key, $table, $level + 1, $maxlevel, $limitCat);
                  }
                  if ($parcat == 0) {
                      $a++;
                  }
                  if ($a > $limitCat)
                      break;
              }
          }
      }
      return $output;
  }
?>
