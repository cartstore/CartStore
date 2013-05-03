<?php


  $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }
  if (DISPLAY_FAQS_CATAGORY_BOX) {
      $do_we_have_categories_faq_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
      $faqdesk_check = tep_db_num_rows($do_we_have_categories_faq_query);
      if ($faqdesk_check > 0) {



          function FAQDesk_box_has_category_subcategories($category_id)
          {
              $child_faqdesk_category_query = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
              $child_category = tep_db_fetch_array($child_faqdesk_category_query);
              if ($child_category['count'] > 0) {
                  return true;
              } else {
                  return false;
              }
          }




          function FAQDesk_box_count_products_in_category($category_id, $include_inactive = false)
          {
              $products_faqdesk_count = 0;
              if ($include_inactive) {
                  $products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES . "
    p2c where p.faqdesk_id = p2c.faqdesk_id and p2c.categories_id = '" . $category_id . "'");
              } else {
                  $products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES . " p2c where p.faqdesk_id = p2c.faqdesk_id and p.faqdesk_status = '1' and p2c.categories_id = '" . $category_id . "'");
              }
              $products_faqdesk = tep_db_fetch_array($products_faqdesk_faqdesk_query);
              $products_faqdesk_count += $products_faqdesk['total'];
              if (USE_RECURSIVE_COUNT == 'true') {
                  $child_categories_query = tep_db_query("select categories_id from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $category_id . "'");
                  if (tep_db_num_rows($child_categories_query)) {
                      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
                          $products_faqdesk_count += FAQDesk_box_count_products_in_category($child_categories['categories_id'], $include_inactive);
                      }
                  }
              }
              return $products_faqdesk_count;
          }


          function FAQDesk_show_category($counter)
          {

              global $foo_faqdesk, $categories_faqdesk_string, $id;
              for ($a = 0; $a < $foo_faqdesk[$counter]['level']; $a++) {
                  $categories_faqdesk_string .= "&nbsp;&nbsp;";
              }
              $categories_faqdesk_string .= '<a href="';
              if ($foo_faqdesk[$counter]['parent'] == 0) {
                  $faqPath_new = 'faqPath=' . $counter;
              } else {
                  $faqPath_new = 'faqPath=' . $foo_faqdesk[$counter]['path'];
              }
              $categories_faqdesk_string .= tep_href_link(FILENAME_FAQDESK_INDEX, $faqPath_new);
              $categories_faqdesk_string .= '">';
              if (($id) && (in_array($counter, $id))) {
                  $categories_faqdesk_string .= '<b>';
              }

              $categories_faqdesk_string .= $foo_faqdesk[$counter]['name'];
              if (($id) && (in_array($counter, $id))) {
                  $categories_faqdesk_string .= '</b>';
              }
              if (FAQDesk_box_has_category_subcategories($counter)) {
                  $categories_faqdesk_string .= '-&gt;';
              }
              $categories_faqdesk_string .= '</a>';
              if (SHOW_COUNTS == 'true') {
                  $products_faqdesk_in_category = FAQDesk_box_count_products_in_category($counter);
                  if ($products_faqdesk_in_category > 0) {
                      $categories_faqdesk_string .= '&nbsp;(' . $products_faqdesk_in_category . ')';
                  }
              }
              $categories_faqdesk_string .= '<br>';
              if ($foo_faqdesk[$counter]['next_id']) {
                  FAQDesk_show_category($foo_faqdesk[$counter]['next_id']);
              }
          }

?>
<!-- categories //-->

<div><?php
          $faqdesk_box_contents = array();
          $faqdesk_box_contents[] = array('align' => 'left', 'text' => BOX_HEADING_FAQDESK_CATEGORIES);
          new infoBoxHeading($faqdesk_box_contents, false, false);
          $categories_faqdesk_string = '';
          $counter = 0;
          $categories_faqdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd
where c.catagory_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
          while ($categories_faqdesk = tep_db_fetch_array($categories_faqdesk_query)) {
              $foo_faqdesk[$categories_faqdesk['categories_id']] = array('name' => $categories_faqdesk['categories_name'], 'parent' => $categories_faqdesk['parent_id'], 'level' => 0, 'path' => $categories_faqdesk['categories_id'], 'next_id' => false);
              if (isset($prev_id)) {
                  $foo_faqdesk[$prev_id]['next_id'] = $categories_faqdesk['categories_id'];
              }
              $prev_id = $categories_faqdesk['categories_id'];
              if (!$counter) {
                  $counter = $categories_faqdesk['categories_id'];
              }
          }

          if ($faqPath) {
              $new_path = '';
              $id = explode('_', $faqPath);
              reset($id);
              while (list($key, $value) = each($id)) {
                  unset($prev_id);
                  unset($first_id);
                  $categories_faqdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . $value . "'
    and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id . "' order by sort_order, cd.categories_name");
                  $category_faqdesk_check = tep_db_num_rows($categories_faqdesk_query);
                  if ($category_faqdesk_check > 0) {
                      $new_path .= $value;
                      while ($row = tep_db_fetch_array($categories_faqdesk_query)) {
                          $foo_faqdesk[$row['categories_id']] = array('name' => $row['categories_name'], 'parent' => $row['parent_id'], 'level' => $key + 1, 'path' => $new_path . '_' . $row['categories_id'], 'next_id' => false);
                          if (isset($prev_id)) {
                              $foo_faqdesk[$prev_id]['next_id'] = $row['categories_id'];
                          }
                          $prev_id = $row['categories_id'];
                          if (!isset($first_id)) {
                              $first_id = $row['categories_id'];
                          }
                          $last_id = $row['categories_id'];
                      }
                      $foo_faqdesk[$last_id]['next_id'] = $foo_faqdesk[$value]['next_id'];
                      $foo_faqdesk[$value]['next_id'] = $first_id;
                      $new_path .= '_';
                  } else {
                      break;
                  }
              }
          }
          FAQDesk_show_category($counter);
          $faqdesk_box_contents = array();
          $faqdesk_box_contents[] = array('align' => 'left', 'text' => $categories_faqdesk_string);
          new infoBox($faqdesk_box_contents);
?>
  </div>
<!-- categories_eof //-->
<?php
      }
  } else {
  }
?>