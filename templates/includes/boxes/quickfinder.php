 <!-- categories //-->
        <?php
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
?>
<div class="module">
  <div>
    <div>
      <div>
        <h3>QUICK FINDER</h3>
        <!-- manufacturers //-->
        <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);
      $manufacturers_array = array();
      $manufacturers_array[] = array('id' => '', 'text' => 'Select Brand');
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
          $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers_name);
      } //while ($manufacturers = tep_db_fetch_array($manufacturers_query))
      $info_box_contents = array();
      $info_box_contents[] = array('text' => '<form method="get" action="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false) . '" name="manufacturers">' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : ''), 'onChange="return getCategory();" ') . tep_hide_session_id());

      new infoBox($info_box_contents);

	  echo '';
  } //if ($number_of_rows = tep_db_num_rows($manufacturers_query))
?>
        <?php
  function tep_get_paths2($categories_array = '', $parent_id = '0', $indent = '', $path = '')
  {
      global $languages_id;
      if (!is_array($categories_array))
          $categories_array = array();
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
      while ($categories = tep_db_fetch_array($categories_query)) {
          if ($parent_id == '0') {
              $categories_array[] = array('id' => $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
          } //if ($parent_id == '0')
          else {
              $categories_array[] = array('id' => $path . $parent_id . '_' . $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
          } //else
          if ($categories['categories_id'] != $parent_id) {
              $this_path = $path;
              if ($parent_id != '0')
                  $this_path = $path . $parent_id . '_';
              $categories_array = tep_get_paths2($categories_array, $categories['categories_id'], $indent . '', $this_path);
          } //if ($categories['categories_id'] != $parent_id)
      } //while ($categories = tep_db_fetch_array($categories_query))
      return $categories_array;
  } //function tep_get_paths2($categories_array = '', $parent_id = '0', $indent = '', $path = '')
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_CATEGORIES);
  new infoBoxHeading($info_box_contents, true, false);
  $cat_list = '';
  $mID = (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : '');
  if ($mID != "") {
      $categories_query = tep_db_query("SELECT distinct cd.categories_name, cd.categories_id FROM `products` p, categories_description cd, products_to_categories pc WHERE cd.categories_id = pc.categories_id AND p.products_id = pc.products_id AND p.manufacturers_id =$mID");
      if ($number_of_rows = tep_db_num_rows($categories_query)) {
?>
        <!-- manufacturers //-->
        <?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);
          $categories_array = array();
          $categories_array[] = array('id' => '', 'text' => 'Select Category');
          while ($categories = tep_db_fetch_array($categories_query)) {
              $categories_name = ((strlen($categories['categories_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($categories['categories_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $categories['categories_name']);
              $categories_array[] = array('id' => $categories['categories_id'], 'text' => $categories_name);
          } //while ($categories = tep_db_fetch_array($categories_query))
          $cat_list = tep_draw_pull_down_menu('cPath', $categories_array, (isset($_GET['cPath']) ? $current_category_id : ''), 'onchange="this.form.submit();"') . tep_hide_session_id();
      } //if ($number_of_rows = tep_db_num_rows($categories_query))
  } //if ($mID != "s_55")
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => '<div id="cat">' . $cat_list . '</div>');
  new infoBox($info_box_contents);
?>
        <!-- categories_eof //-->
<?php
    if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>