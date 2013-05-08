<?php


  $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }
  if (DISPLAY_NEWS_CATAGORY_BOX) {
      $do_we_have_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
      $newsdesk_check = tep_db_num_rows($do_we_have_categories_query);
      if ($newsdesk_check > 0) {



          function newsedsk_box_has_category_subcategories($category_id)
          {
              $child_newsdesk_category_query = tep_db_query("select count(*) as count from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
              $child_category = tep_db_fetch_array($child_newsdesk_category_query);
              if ($child_category['count'] > 0) {
                  return true;
              } else {
                  return false;
              }
          }



          function newsedsk_box_count_products_in_category($category_id, $include_inactive = false)
          {
              $products_newsdesk_count = 0;
              if ($include_inactive) {
                  $products_newsdesk_newsdesk_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_TO_CATEGORIES . "
    p2c where p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = '" . $category_id . "'");
              } else {
                  $products_newsdesk_newsdesk_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_TO_CATEGORIES . " p2c where p.newsdesk_id = p2c.newsdesk_id and p.newsdesk_status = '1' and p2c.categories_id = '" . $category_id . "'");
              }
              $products_newsdesk = tep_db_fetch_array($products_newsdesk_newsdesk_query);
              $products_newsdesk_count += $products_newsdesk['total'];
              if (USE_RECURSIVE_COUNT == 'true') {
                  $child_categories_query = tep_db_query("select categories_id from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
                  if (tep_db_num_rows($child_categories_query)) {
                      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
                          $products_newsdesk_count += newsedsk_box_count_products_in_category($child_categories['categories_id'], $include_inactive);
                      }
                  }
              }
              return $products_newsdesk_count;
          }

          function newsedsk_show_category($counter)
          {

              global $foo_newdesk, $categories_newsdesk_string, $id;
              for ($a = 0; $a < $foo_newdesk[$counter]['level']; $a++) {
                  $categories_newsdesk_string .= "&nbsp;&nbsp;";
              }
              $categories_newsdesk_string .= '<a href="';
              if ($foo_newdesk[$counter]['parent'] == 0) {
                  $newsPath_new = 'newsPath=' . $counter;
              } else {
                  $newsPath_new = 'newsPath=' . $foo_newdesk[$counter]['path'];
              }
              $categories_newsdesk_string .= tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new);
              $categories_newsdesk_string .= '">';
              if (($id) && (in_array($counter, $id))) {
                  $categories_newsdesk_string .= '<b>';
              }

              $categories_newsdesk_string .= $foo_newdesk[$counter]['name'];
              if (($id) && (in_array($counter, $id))) {
                  $categories_newsdesk_string .= '</b>';
              }
              if (newsedsk_box_has_category_subcategories($counter)) {
                  $categories_newsdesk_string .= '-&gt;';
              }
              $categories_newsdesk_string .= '</a>';
              if (SHOW_COUNTS == 'true') {
                  $products_newsdesk_in_category = newsedsk_box_count_products_in_category($counter);
                  if ($products_newsdesk_in_category > 0) {
                      $categories_newsdesk_string .= '&nbsp;(' . $products_newsdesk_in_category . ')';
                  }
              }
              $categories_newsdesk_string .= '<br>';
              if ($foo_newdesk[$counter]['next_id']) {
                  newsedsk_show_category($foo_newdesk[$counter]['next_id']);
              }
          }

?>
<!-- categories //-->

<div><?php
          $newsdesk_box_contents = array();
          $newsdesk_box_contents[] = array('align' => 'right', 'text' => BOX_HEADING_NEWSDESK_CATEGORIES);
          new infoBoxHeading($newsdesk_box_contents, false, false);
          $categories_newsdesk_string = '';
          $categories_newsdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd
where c.catagory_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
          while ($categories_newsdesk = tep_db_fetch_array($categories_newsdesk_query)) {
              $foo_newdesk[$categories_newsdesk['categories_id']] = array('name' => $categories_newsdesk['categories_name'], 'parent' => $categories_newsdesk['parent_id'], 'level' => 0, 'path' => $categories_newsdesk['categories_id'], 'next_id' => false);
              if (isset($prev_id)) {
                  $foo_newdesk[$prev_id]['next_id'] = $categories_newsdesk['categories_id'];
              }
              $prev_id = $categories_newsdesk['categories_id'];
              if (!isset($counter)) {
                  $counter = $categories_newsdesk['categories_id'];
              }
          }

          if ($newsPath) {
              $new_path = '';
              $id = explode('_', $newsPath);
              reset($id);
              while (list($key, $value) = each($id)) {
                  unset($prev_id);
                  unset($first_id);
                  $categories_newsdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
    and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
                  $category_newsdesk_check = tep_db_num_rows($categories_newsdesk_query);
                  if ($category_newsdesk_check > 0) {
                      $new_path .= $value;
                      while ($row = tep_db_fetch_array($categories_newsdesk_query)) {
                          $foo_newdesk[$row['categories_id']] = array('name' => $row['categories_name'], 'parent' => $row['parent_id'], 'level' => $key + 1, 'path' => $new_path . '_' . $row['categories_id'], 'next_id' => false);
                          if (isset($prev_id)) {
                              $foo_newdesk[$prev_id]['next_id'] = $row['categories_id'];
                          }
                          $prev_id = $row['categories_id'];
                          if (!isset($first_id)) {
                              $first_id = $row['categories_id'];
                          }
                          $last_id = $row['categories_id'];
                      }
                      $foo_newdesk[$last_id]['next_id'] = $foo_newdesk[$value]['next_id'];
                      $foo_newdesk[$value]['next_id'] = $first_id;
                      $new_path .= '_';
                  } else {
                      break;
                  }
              }
          }
          newsedsk_show_category($counter);
          $newsdesk_box_contents = array();
          $newsdesk_box_contents[] = array('align' => 'right', 'text' => $categories_newsdesk_string);
          new infoBox($newsdesk_box_contents);
?>
  </div>
<!-- categories_eof //-->
<?php
      }
  } else {
  }
?>