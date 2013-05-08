<?php
  function tep_show_category($counter)
  {
      global $tree, $categories_string, $cPath_array;
      for ($i = 0; $i < $tree[$counter]['level']; $i++) {
          $categories_string .= "&nbsp;&nbsp;";
      }

      if ($tree[$counter]['name'] != "") {

          $categories_string .= '
  <li><a href="';
          if ($tree[$counter]['parent'] == 0) {
              $cPath_new = 'cPath=' . $counter;
          } else {
              $cPath_new = 'cPath=' . $tree[$counter]['path'];
          }
          $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">';
          if (isset($cPath_array) && in_array($counter, $cPath_array)) {
              $categories_string .= '<span class="active">';
          }

          $categories_string .= $tree[$counter]['name'];
          if (isset($cPath_array) && in_array($counter, $cPath_array)) {
              $categories_string .= '</span>';
          }
          if (tep_has_category_subcategories($counter)) {
              $categories_string .= '';
          }
          $categories_string .= '</a></li>';
      }

      if (SHOW_COUNTS == 'true') {
          $products_in_category = tep_count_products_in_category($counter);
          if ($products_in_category > 0) {
              $categories_string .= '&nbsp;(' . $products_in_category . ')';
          }
      }

      if ($tree[$counter]['next_id'] != false) {
          tep_show_category($tree[$counter]['next_id']);
      }
  }
?>
<!-- categories //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false);
  $categories_string = '';
  $tree = array();
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where " . (YMM_FILTER_CATEGORIES_BOX == 'Yes' ? YMM_get_categories_where(0, $YMM_where) : '') . " c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . "' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
      $tree[$categories['categories_id']] = array('name' => $categories['categories_name'], 'parent' => $categories['parent_id'], 'level' => 0, 'path' => $categories['categories_id'], 'next_id' => false);
      if (isset($parent_id)) {
          $tree[$parent_id]['next_id'] = $categories['categories_id'];
      }
      $parent_id = $categories['categories_id'];
      if (!isset($first_element)) {
          $first_element = $categories['categories_id'];
      }
  }

  if (tep_not_null($cPath)) {
      $new_path = '';
      reset($cPath_array);
      while (list($key, $value) = each($cPath_array)) {
          unset($parent_id);
          unset($first_id);
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$value . "' and " . (YMM_FILTER_CATEGORIES_BOX == 'Yes' ? YMM_get_categories_where((int)$value, $YMM_where) : '') . " c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . "' order by sort_order, cd.categories_name");
          if (tep_db_num_rows($categories_query)) {
              $new_path .= $value;
              while ($row = tep_db_fetch_array($categories_query)) {





                  if (isset($parent_id)) {
                      $tree[$parent_id]['next_id'] = $row['categories_id'];
                  }
                  $parent_id = $row['categories_id'];
                  if (!isset($first_id)) {
                      $first_id = $row['categories_id'];
                  }
                  $last_id = $row['categories_id'];
              }
              $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
              $tree[$value]['next_id'] = $first_id;
              $new_path .= '_';
          } else {
              break;
          }
      }
  }
  tep_show_category($first_element);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $categories_string);


  new infoBox($info_box_contents);
?>
<!-- categories_eof //-->