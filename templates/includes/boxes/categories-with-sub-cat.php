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
          $categories_string .= '<br>';
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
<div><?php
  
  
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false, false, true);
  
  
  
  
  
  $status = tep_db_num_rows(tep_db_query('describe ' . TABLE_CATEGORIES . ' status'));
  $query = "select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
            where c.categories_id = cd.categories_id";
  
  if ($status > 0)
      $query .= " and c.status = '1'";
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
              if ($subcategories['parent_id'] == $categories['categories_id']) {
                  $cPath_new_sub = "cPath=" . $categories['categories_id'] . "_" . $subcategories['categories_id'];
                  $text_subcategories .= '<div style="height: 25px; vertical-align:middle;padding-top:5px;" class="dottedborder"><img src="/store/includes/sts_templates/default/images/arrow.gif" style="margin-right: 5px;" height="7" width="8">' . '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '" class="menulink">' . '&nbsp;&nbsp;&nbsp;' . $subcategories['categories_name'] . '</a></div></div>' . " ";
              }
              
              } 
              $info_box_contents[$row] = array('align' => 'left', 'params' => 'class="smallText" width="125" valign="top"', 'text' => '<img src="/store/includes/sts_templates/default/images/menuleft.gif" width="19" height="25">' . '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '" class="menuback menutext" ><b>' . '&nbsp;' . $categories['categories_name'] . '</b></a><img src="/store/includes/sts_templates/default/images/menuright.gif" width="9" height="25">' . $text_subcategories);
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
</div>