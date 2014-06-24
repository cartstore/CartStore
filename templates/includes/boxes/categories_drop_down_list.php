<!-- categories //-->

<div><?php
  function tep_get_paths($categories_array = '', $parent_id = '0', $indent = '', $path = '')
  {
      global $languages_id;
      if (!is_array($categories_array))
          $categories_array = array();
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
      while ($categories = tep_db_fetch_array($categories_query)) {
          if ($parent_id == '0') {
              $categories_array[] = array('id' => $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
          } else {
              $categories_array[] = array('id' => $path . $parent_id . '_' . $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
          }
          if ($categories['categories_id'] != $parent_id) {
              $this_path = $path;
              if ($parent_id != '0')
                  $this_path = $path . $parent_id . '_';
              $categories_array = tep_get_paths($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;', $this_path);
          }
      }
      return $categories_array;
  }
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false);
  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form action="' . tep_href_link(FILENAME_DEFAULT) . '" method="get">' . tep_hide_session_id(), 'align' => 'left', 'text' => tep_draw_pull_down_menu('cPath', tep_get_paths(array(array('id' => '', 'text' => PULL_DOWN_DEFAULT))), $cPath, 'onchange="this.form.submit();"'));
  new infoBox($info_box_contents);
?>
  </div>
<!-- categories_eof //-->