<?php
  $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_image not like '' order by manufacturers_name");
  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
?>
<!-- manufacturers //-->

<div class="module-top manlogos">
  <div>
    <div>
      <div>
        <ul>
          <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);
      new infoBoxHeading($info_box_contents, false, false);
      if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {

          $manufacturers_list = '';
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
              if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturers['manufacturers_id']))
                  $manufacturers_name = '' . $manufacturers_name . '';
              $manufacturers_list .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></ li>';
          }
          $manufacturers_list = substr($manufacturers_list, 0, -4);
          $info_box_contents = array();
          $info_box_contents[] = array('text' => $manufacturers_list);
      } else {

          $manufacturers_array = array();
          if (MAX_MANUFACTURERS_LIST < 2) {
              $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
          }
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
              $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
              $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers_name);
          }

          $fullstring = '';
          $row = 0;
          $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
          if (tep_db_num_rows($manufacturers_query) >= '1') {
              while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                  $row++;
                  $fullstring .= '';
                  $fullstring .= '<a href=' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'], 'NONSSL', false) . '>';
                  if ($manufacturers['manufacturers_image']) {


                      $fullstring .= tep_image(DIR_WS_IMAGES . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
                      $fullstring .= $manufacturers['manufacturers_name'];

                  } else {
                      $fullstring .= '<a href=' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id'], 'NONSSL', false) . '>' . $manufacturers['manufacturers_name'];
                  }
                  $fullstring .= '</a>';
                  $fullstring .= '';
                  if ((($row / 2) == (double)floor($row / 2))) {
                      $fullstring .= '';
                  }
              }
          }
          $fullstring .= '';

          $info_box_contents = array();
          $info_box_contents[] = array('form' => tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'), 'text' => tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id() . ''

          .$fullstring);

      }
      new infoBox($info_box_contents);
?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- manufacturers_eof //-->
<?php
  }
?>