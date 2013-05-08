<?php
  
  
  
  
  $item_column_number = 2;
  
  $item_title_on_newline = true;
  
  $valign = top;
  
  $hover = on;
  
  
  
  $hovercolor = "";
  
  $hoverborder = "";
  
  $regcolor = "";
  
  $regborder = "";
  
  $borderwidth = '';
  
  
  $item_div_options = '';
  $item_subcategories_options = '';
  
  
  
  
  if ($item_column_number < 1) {
      $item_column_number = 1;
  }
  if ($item_column_number > 9) {
      $item_column_number = 9;
  }
  if ($item_title_on_newline) {
      $item_separator = '';
  } else {
      $item_separator = '';
  }
  
  function preorder($cid, $level, $foo, $cpath)
  {
      global $categories_string, $_GET;
      
      if ($cid != 0) {
          for ($i = 0; $i < $level; $i++)
              $categories_string .= '';
          $categories_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath
=' . $cid) . '">';
          
          $bold = strstr($_GET['cPath'], $cpath . $cid . '_') || $_GET['cPath'] == $cpath . $cid;
          
          if ($bold)
              $categories_string .= '<b>';
          $categories_string .= $foo[$cid]['name'];
          if ($bold)
              $categories_string .= '</b>';
          $categories_string .= '</a>';
          
          if (SHOW_COUNTS == 'false') {
              $products_in_category = tep_count_products_in_category($cid);
              if ($products_in_category > 0) {
                  $categories_string .= '(' . $products_in_category . ')';
              }
          }
          $categories_string .= '';
      }
      
      if (is_array($foo)) {
          foreach ($foo as $key => $value) {
              if ($foo[$key]['parent'] == $cid) {
                  preorder($key, $level + 1, $foo, ($level != 0 ? $cpath . $cid . '_' : ''));
              }
          }
      }
  }
?>
<!-- main_categories //-->

 

 
<div class="moduletable-featured clearfix">
					<h3>Categories</h3>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false);
?>
 
 
<?php
  
  
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_CATEGORIES);
  
  
  
  
  $status = tep_db_num_rows(tep_db_query('describe categories status'));
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
          $cPath_new = tep_get_path($categories['categories_id']);
 
              if ($hover == 'on') {
                  $info_box_contents[$row][$col] = array('align' => '', 'params' => '', 'text' => '
				  
				<div class="catBox">
							<div class="imgBox">
								<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], 233, SUBCATEGORY_IMAGE_HEIGHT) . '</a>
							</div>
							<div class="catDesc">
								<h4><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $categories['categories_name'] . '</a></h4>	
								<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '" class="readon">More</a>
							</div>			
						</div>	
						
						
				  
				  ');
              } else {
                  
              }
              
              $col++;
              if ($col > ($item_column_number - 1)) {
                  $col = 0;
                  $row++;
              }
              
              } 
          }
          
          
          new contentBox($info_box_contents);
?></div>


<!-- main_categories_eof //-->