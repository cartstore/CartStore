<?php
  $authors_query = tep_db_query("select authors_id, authors_name from " . TABLE_AUTHORS . " order by authors_name");
  if ($number_of_author_rows = tep_db_num_rows($authors_query)) {
?>
<!-- authors //-->

<tr>
  <td><?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_AUTHORS);
      new infoBoxHeading($info_box_contents, true, true);
      if ($number_of_author_rows <= MAX_DISPLAY_AUTHORS_IN_A_LIST) {
          
          $authors_list = '';
          while ($authors = tep_db_fetch_array($authors_query)) {
              $authors_name = ((strlen($authors['authors_name']) > MAX_DISPLAY_AUTHOR_NAME_LEN) ? substr($authors['authors_name'], 0, MAX_DISPLAY_AUTHOR_NAME_LEN) . '..' : $authors['authors_name']);
              if (isset($_GET['authors_id']) && ($_GET['authors_id'] == $authors['authors_id']))
                  $authors_name = '<b>' . $authors_name . '</b>';
              $authors_list .= '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $authors['authors_id']) . '">' . $authors_name . '</a><br>';
          }
          $authors_list = substr($authors_list, 0, -4);
          $info_box_contents = array();
          $info_box_contents[] = array('text' => $authors_list);
      } else {
          
          $authors_array = array();
          if (MAX_AUTHORS_LIST < 2) {
              $authors_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
          }
          while ($authors = tep_db_fetch_array($authors_query)) {
              $authors_name = ((strlen($authors['authors_name']) > MAX_DISPLAY_AUTHOR_NAME_LEN) ? substr($authors['authors_name'], 0, MAX_DISPLAY_AUTHOR_NAME_LEN) . '..' : $authors['authors_name']);
              $authors_array[] = array('id' => $authors['authors_id'], 'text' => $authors_name);
          }
          $info_box_contents = array();
          $info_box_contents[] = array('form' => tep_draw_form('authors', tep_href_link(FILENAME_ARTICLES, '', 'NONSSL', false), 'get'), 'text' => tep_draw_pull_down_menu('authors_id', $authors_array, (isset($_GET['authors_id']) ? $_GET['authors_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_AUTHORS_LIST . '" style="width: 100%"') . tep_hide_session_id());
      }
      new infoBox($info_box_contents);
?>
  </td>
</tr>
<!-- authors_eof //-->
<?php
  }
?>