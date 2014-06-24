<?php
/*
  $Id: group_download.php,v 1.0 2007/02/03 12:08:00 Alex Li Exp $

  Copyright (c) 2007 AlexStudio

  Released under the GNU General Public License
*/
?>
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td valign="top" width="35%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- File Groups //-->
<?php
    if ($action == 'delete_filegroup' && $_GET['filegroup_id'] != '0') {
      $filegroup_query = tep_db_query("select download_group_id, download_group_name from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where download_group_id = '" . (int)$_GET['filegroup_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $filegroup_array = tep_db_fetch_array($filegroup_query);
?>
              <tr>
                  <td class="pageHeading"><h3><?php echo $filegroup_array['download_group_name']; ?></h3></td>
                <td> </td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"> </td>
                  </tr>
<?php
      $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name
                                from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                where pd.products_id = p.products_id
                                and pd.language_id = '" . (int)$languages_id . "'
                                and po.language_id = '" . (int)$languages_id . "'
                                and pa.products_id = p.products_id
                                and pa.products_attributes_id = pad.products_attributes_id
                                and pad.products_attributes_filegroup_id='" . (int)$_GET['filegroup_id'] . "'
                                and po.products_options_id = pa.options_id
                                order by pd.products_name");
      if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPT_NAME; ?></td>
                  </tr>
                 
<?php
        while ($products_values = tep_db_fetch_array($products)) {
          $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText"><?php echo $products_values['products_id']; ?></td>
                    <td class="smallText"><?php echo $products_values['products_name']; ?></td>
                    <td class="smallText"><?php echo $products_values['products_options_name']; ?></td>
                  </tr>
<?php
        }
?>
                 
                  <tr>
                    <td class="main" colspan="3"><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['value_page']) ? 'value_page=' . $_GET['value_page'] . '&' : '') . (isset($_GET['attribute_page']) ? 'attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a></td>
                  </tr>
<?php
      } else {
        $files_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . "
                                     where download_group_id = '" . (int)$_GET['filegroup_id'] . "'");
        $file_num = tep_db_num_rows($files_query);
        if ($file_num > 0) {
?>
                  <tr class="attributes-odd">
                    <td class="main" colspan="3"><?php echo $file_num . '' . TEXT_FILE_IN_GROUP; ?></td>
                  </tr>
                
<?php
        }
?>
                  <tr>
                    <td class="main" colspan="3"><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_download_group&filegroup_id=' . $_GET['filegroup_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a></td>
                  </tr>
<?php
      }
?>
                </table></td>
              </tr>
<?php
    } else {
?>
              <tr>
                  <td colspan="3" class=""><h3><?php echo HEADING_TITLE_FILEGROUP ?></h3></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">
<?php
      $per_page = MAX_ROW_LISTS_OPTIONS;
      $file_groups = "select download_group_id, download_group_name from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS .
                     " where download_group_id != 0 and language_id = " . (int)$languages_id . " order by download_group_id";
      if (!isset($file_group_page)) $file_group_page = 1;
      $prev_file_group_page = $file_group_page - 1;
      $next_file_group_page = $file_group_page + 1;

      $file_group_query = tep_db_query($file_groups);

      $file_group_page_start = ($per_page * $file_group_page) - $per_page;
      $num_rows = tep_db_num_rows($file_group_query);

      if ($num_rows <= $per_page) $num_pages = 1;
      else if (($num_rows % $per_page) == 0) $num_pages = ($num_rows / $per_page);
      else $num_pages = ($num_rows / $per_page) + 1;
      $num_pages = (int)$num_pages;

      $file_groups = $file_groups . " LIMIT $file_group_page_start, $per_page";

      // Previous
      if ($prev_file_group_page) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&file_group_page=' . $prev_file_group_page) . '"> &lt;&lt; </a> | ';
      }

      for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $file_group_page) {
          echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'file_group_page=' . $i) . '">' . $i . '</a> | ';
        } else {
          echo '<b><font color=red>' . $i . '</font></b> | ';
        }
      }

      // Next
      if ($file_group_page != $num_pages) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'file_group_page=' . $next_file_group_page) . '"> &gt;&gt;</a> ';
      }
?>
                </td>
              </tr>
              <tr>
                <td colspan="3"></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILEGROUP_NAME ?></td>
                <td align="center" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION ?></td>
              </tr>
              <tr>
                <td colspan="3"></td>
              </tr>
<?php
      $next_id = 1;
      $rows = 0;
      $file_groups = tep_db_query($file_groups);
      $file_groups_num = tep_db_num_rows($file_groups);
      while ($file_groups_array = tep_db_fetch_array($file_groups)) {
        $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
        if ($action == 'update_filegroup' && $_GET['filegroup_id'] == $file_groups_array['download_group_id']) {
          echo '<form name="filegroups" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_filegroup_name&option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : '') . (isset($_GET['group_file_page']) ? '&group_file_page=' . $group_file_page : ''), 'NONSSL') . '" method="post">';
          $inputs = '';
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $file_group_name = tep_db_query("select download_group_name from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where download_group_id = '".(int)$file_groups_array['download_group_id']."' and language_id = '".(int)$languages[$i]['id']."'");
            $file_group_name = tep_db_fetch_array($file_group_name);
            $inputs .= $languages[$i]['code'] . ':<input class="form-control" type="text" name="file_group_name[' . $languages[$i]['id'] . ']" size="15" value="' . $file_group_name['download_group_name'] . '">';
          }
?>
                <td align="center" class="smallText"><?php echo $file_groups_array['download_group_id']; ?><input type="hidden" name="filegroup_id" value="<?php echo $file_groups_array['download_group_id']; ?>"></td>
                <td align="center" class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?><?php echo '<a href="'.tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL').'">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a></td>
<?php
          echo '</form>' . "\n";
        } else {
?>
                <td align="center" class="smallText"><?php echo $file_groups_array['download_group_id']; ?></td>
                <td class="smallText"><?php echo $file_groups_array['download_group_name']; ?></td>
                <td align="center" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_filegroup&filegroup_id=' . $file_groups_array['download_group_id'] . '&option_order_by=' . $option_order_by . '&file_group_page=' . $file_group_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_filegroup&filegroup_id=' . $file_groups_array['download_group_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a></td>
<?php
        }
?>
              </tr>
<?php
        $max_filegroup_id_query = tep_db_query("select max(download_group_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS);
        $max_filegroup_id_array = tep_db_fetch_array($max_filegroup_id_query);
        $next_id = $max_filegroup_id_array['next_id'];
      }
?>
              <tr>
                <td colspan="3"></td>
              </tr>
<?php
      if ($action != 'update_filegroup') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
        echo '<form name="filegroups" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_filegroups&file_group_page=' . $file_group_page, 'NONSSL') . '" method="post"><input type="hidden" name="filegroup_id" value="' . $next_id . '">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $inputs .= $languages[$i]['code'] . ':<input class="form-control"  type="text" name="file_group_name[' . $languages[$i]['id'] . ']" size="15">';
        }
?>
                <td align="center" class="smallText"><?php echo $next_id ?></td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
<?php
        echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="3"><td>
              </tr>
<?php
      }
    }
?>
            </table></td>
<!-- File Groups EOF //-->
            <td valign="top" width="65%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- Files in Groups //-->
<?php
    if ($action == 'delete_group_file') { // delete the file in group
      $file_query = tep_db_query("select padg.download_group_id, padg.download_group_name, padgf.download_group_filename, padg2f.download_group_file_description
                                  from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " padg
                                  left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " padgf
                                  on padg.download_group_id = padgf.download_group_id
                                  left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " padg2f
                                  on padgf.download_groups_file_id = padg2f.download_groups_file_id
                                  where padg.download_group_id = '" . (int)$_GET['filegroup_id'] . "'
                                  and padg.language_id = '" . (int)$languages_id . "'
                                  and padgf.download_groups_file_id = '" . (int)$_GET['group_file_id'] . "'
                                  and padg2f.language_id = '" . (int)$languages_id . "'");
      $file_array = tep_db_fetch_array($file_query);
?>
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE_DELETE_FILE; ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo "<b>" . $file_array['download_group_filename'] . "</b> - " . $file_array['download_group_file_description']; ?></td>
              </tr>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
               
                  <tr class="attributes-odd">
                    <td></td>
                    <td class="main" colspan="2"><?php echo TEXT_DELETE_FROM_GROUP . "<b>" . $file_array['download_group_id'] . "" . $file_array['download_group_name'] . "</b>"; ?></td>
                    <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_file_from_group&filegroup_id=' . $_GET['filegroup_id'] . '&group_file_id=' . $_GET['group_file_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : '') . (isset($_GET['group_file_page']) ? '&group_file_page=' . $group_file_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a></td>
                  </tr>
                 
<?php
      $filegroups_query = tep_db_query("select padg.download_group_id, padg.download_group_name
                                        from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " padg
                                        left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " padgf
                                        on padg.download_group_id = padgf.download_group_id
                                        where padg.language_id = '" . (int)$languages_id . "'
                                        and padg.download_group_id != '" . (int)$_GET['filegroup_id'] . "'
                                        and padgf.download_groups_file_id = '" . (int)$_GET['group_file_id'] . "'");
      if (tep_db_num_rows($filegroups_query)) {
        $rows = 1;
?>
                  <tr>
                    <td class="smallText" colspan="4"><?php echo TEXT_FILE_IN_OTHER_GROUPS; ?></td>
                  </tr>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" width="10"><?php echo TABLE_HEADING_ID; ?></td>
                    <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_FILEGROUP_NAME; ?></td>
                  </tr>
                
<?php
        while ($filegroup_array = tep_db_fetch_array($filegroups_query)) {
          $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText"><?php echo $filegroup_array['download_group_id']; ?></td>
                    <td class="smallText" colspan="3"><?php echo $filegroup_array['download_group_name']; ?></td>
                  </tr>
<?php
        }
        $rows++;
?>
                 
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td></td>
                    <td class="main" colspan="2"><?php echo TEXT_DELETE_FROM_ALL_GROUPS; ?></td>
                    <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_file_all_groups&group_file_id=' . $_GET['group_file_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : '') . (isset($_GET['group_file_page']) ? '&group_file_page=' . $group_file_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a></td>
                  </tr>
                 
<?php
      } else {
?>
                  <tr>
                    <td class="main" colspan="4"><?php echo TEXT_DELETE_NO_OTHER_GROUP; ?></td>
                  </tr>
<?php
      }
?>
                </table></td>
              </tr>
<?php
    } else if ($action == 'mass_group_file') { // #### Add Multiple Files to a Group Starts ################################

       $group_file_page = $_GET['group_file_page'];
       if (isset($_POST['filegroup_id']) && tep_not_null($_POST['filegroup_id'])) {
         $file_group_id = $_POST['filegroup_id'];
       } else if (isset($_GET['filegroup_id']) && tep_not_null($_GET['filegroup_id'])) {
         $file_group_id = $_GET['filegroup_id'];
       } else {
         $messageStack->add('No file group given!!', 'error');
         tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
       }
?>
              <tr>
                  <td class="" colspan="2"><h3><?php echo HEADING_TITLE_MASSIVE_FILES; ?></h3></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?></td>
              </tr>
<?php
      echo '<form name="massive_files" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_mass_files', 'NONSSL') . '" method="post">';
      
?>
              <tr>
                <td colspan="3"><table width="100%" cellspacing="0" cellpadding="0">
                  <tr>
<?php
      // get file group content from db
      $file_groups_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . "
                                         where language_id = '" . (int)$languages_id . "'
                                         and download_group_id = '" . (int)$file_group_id . "'");
      $file_groups = tep_db_fetch_array($file_groups_query);
?>
                    <td class="smallText" width="25%"><b><?php echo $file_groups['download_group_id']; ?><?php echo tep_draw_hidden_field('filegroup_id', $file_group_id) . $file_groups['download_group_name']; ?></b></td>
<?php
      // get file list of this group
      $group_file_query = tep_db_query("select download_group_filename from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . "
                                        where download_group_id = '" . (int)$file_group_id . "'
                                        order by download_group_filename");
      $file_array = array();
      // list all files in group
      if ($num_rows = tep_db_num_rows($group_file_query)) {
?>
                    <td width="75%"><table width="100%" cellspacing="0" cellpadding="2">
<?php
        while ($group_file_array = tep_db_fetch_array($group_file_query)) {
          $file_array[] = $group_file_array['download_group_filename'];
        }
        if ($num_rows > MAX_ROW_LISTS_OPTIONS) {
          $total_rows = (($num_rows % 3) == 0 ? ($num_rows/3) : (($num_rows/3) + 1));
?>
                      <tr>
<?php
          $index = 0;
          for ($col = 0; $col < 3; $col++) {
            $rows = 0;
?>
                        <td width="33%" valign="top" class="smallText">
<?php
            for ($i = 0; $i < $total_rows; $i++) {
              $rows++;
              if (tep_not_null($file_array[$index])) {
                echo '' . $file_array[$index] . '';
                $index++;
              }
            }
?>
                        </td>
<?php
          }
?>
                      </tr>
<?php
        } else {
?>
                      <tr>
                        <td class="smallText">
<?php
          foreach($file_array as $k => $filename) {
            echo $filename . '';
          }
?>
                        </td>
                      </tr>
<?php
        }
?>
                    </table></td>
<?php
      }
?>
                  </tr>
                </table></td>
              </tr>
            
<?php
      $file_folder = '';
      // get the folder path if a sub path is given
      if (isset($_GET['sub_path'])) {
        $check_path = realpath(DIR_FS_CATALOG_DOWNLOAD . $_GET['sub_path']) . '/';
        if (strstr($check_path, DIR_FS_CATALOG_DOWNLOAD)) {
          $file_folder_array = explode(DIR_FS_CATALOG_DOWNLOAD, $check_path);
          $file_folder = $file_folder_array[1];
        } else {
          $check_path = DIR_FS_CATALOG_DOWNLOAD;
        }
      } else $check_path = DIR_FS_CATALOG_DOWNLOAD;
?>
              <tr bgcolor="#666666">
                <td class="dataTableHeadingContent" colspan="3"><b><?php echo $check_path; ?></b></td>
              </tr>
            
<?php
      $ignored[] = ".";
      $ignored[] = ".htaccess";
      $ignored[] = "_vti_cnf";
      if (is_dir($check_path)) {
        if ($dh = opendir($check_path)) {
          while (($file = readdir($dh)) !== false) {
            if (!(array_search($file,$ignored) > -1)) {
              if (filetype($check_path . $file) == "dir") {
                $dirs_list[] = $check_path . $file;
              } else {
                $files_list[] = $check_path . $file;
              }
            }
          }
          closedir($dh);
        }
      }
      $subpath_all = $dirs_list;
      $file_all = $files_list;

      // generate sub folder list
      foreach($subpath_all as $k => $sub_path) {
        $sub_path_array = explode(DIR_FS_CATALOG_DOWNLOAD, $sub_path);
        $subfolder_list[] = $sub_path_array[1];
      }
      $subfolder_num = count($subfolder_list);
      if ($subfolder_num > 0) {
        sort($subfolder_list);
        $num_rows = (floor($subfolder_num/4) == ($subfolder_num/4)) ? ($subfolder_num/4) : (floor($subfolder_num/4) + 1);
?>
              <tr>
                <td colspan="3"><table width="100%" cellspacing="0" cellpadding="0">
<?php
        $index = 0;
        for ($rows = 0; $rows < $num_rows; $rows++) {
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-odd' : 'attributes-even'); ?>">
<?php
          for ($col = 0; $col < 4; $col++) {
            if (tep_not_null($subfolder_list[$index])) {
              $sub_array = explode($check_path, DIR_FS_CATALOG_DOWNLOAD . $subfolder_list[$index]);
              $subfolder_displayed = $sub_array[1];
              if ($subfolder_displayed == "..") {
                $subfolder_displayed = TEXT_UP_ONE_LEVEL;
              }
              $subfolder = $subfolder_list[$index];
              if (!strstr($subfolder_list[$index], "..") || $check_path != DIR_FS_CATALOG_DOWNLOAD) {
?>
                    <td width="25%"><table width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="20"><?php echo tep_image(DIR_WS_ICONS . 'folder.gif', ''); ?></td><td class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=mass_group_file&filegroup_id=' . $file_group_id . '&sub_path=' . $subfolder . '&group_file_page=' . $group_file_page, 'NONSSL') . '">' . $subfolder_displayed . '</a>'; ?></td>
                      </tr>
                    </table></td>
<?php
              } else $col--;
              $index++;
            } else {
?>
                    <td width="25%"><table width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="20"></td><td class="smallText"></td>
                      </tr>
                    </table></td>
<?php
            }
          }
?>
                  </tr>
<?php
        }
?>
                </table></td>
              </tr>
             
<?php
      }

      $file_all_num = sizeof($file_all);
      $no_file_found = 1;
      if ($file_all_num > 0) {
        // generate file list
        foreach($file_all as $k => $file_path) {
          $file_check_array = explode(DIR_FS_CATALOG_DOWNLOAD, $file_path);
          $file_path_array = explode($check_path, $file_path);
          if (!in_array($file_check_array[1], $file_array)) {
            $file_list[] = $file_path_array[1];
          }
        }

        $file_num = sizeof($file_list);
        if ($file_num > 0) {
          $no_file_found = 0;
          sort($file_list);
          $file_displayed = array();
          foreach($file_list as $i => $filename) {
            $file_displayed[] = tep_draw_checkbox_field('file_selected[]', $file_folder . $filename) . '</td><td class="smallText">' . $filename;
          }
          if ($file_num > MAX_ROW_LISTS_OPTIONS) {
            $total_rows = (($file_num % 3) == 0 ? ($file_num/3) : (($file_num/3) + 1));
?>
              <tr><td colspan="3"><table width="100%" cellspacing="0" cellpadding="0"><tr>
<?php
            $index = 0;
            for ($col = 0; $col < 3; $col++) {
              $rows = 0;
?>
                <td width="33%" valign="top"><table width="100%" cellspacing="0" cellpadding="2">
<?php
              for ($i = 0; $i < $total_rows; $i++) {
                $rows++;
                if (tep_not_null($file_displayed[$index])) {
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td class="smallText" width="30"><?php echo $file_displayed[$index]; ?></td>
                  </tr>
<?php
                  $index++;
                }
              }
?>
                </table></td>
<?php
            }
?>
              </tr></table></td></tr>
<?php
          } else {
?>
              <tr><td colspan="3"><table width="100%" cellspacing="0" cellpadding="0"><tr>
                <td colspan="3"><table width="100%" cellspacing="0" cellpadding="0">
<?php
            $total_rows = $file_num;
            $rows = 0;
            for ($i = 0; $i < $total_rows; $i++) {
              $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td class="smallText" width="30"><?php echo $file_displayed[$i]; ?></td>
                  </tr>
<?php
            }
?>
                </table></td>
              </tr></table></td></tr>
<?php
          }
?>
          
              <tr>
                <td colspan="3" align="right"><?php echo  tep_image_submit('button_insert.gif', IMAGE_INSERT); ?><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'group_file_page=' . $group_file_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a></td>
<?php
        }
      }
      if ($no_file_found) {
?>
              <tr>
                <td colspan="3" class="smallText"><b><?php echo TEXT_NO_FILE_IN_FOLDER; ?></b></td>
              </tr>
              <tr>
                <td colspan="3" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : '') . (isset($_GET['group_file_page']) ? '&group_file_page=' . $group_file_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a></td>
<?php
      }
?>
              </tr></form>
<?php
    // ################################################ Add Multiple Files to a Group Ends ################################
    } else {
?>
              <tr>
                  <td colspan="5" class=""><h3><?php echo HEADING_TITLE_FILEGROUP_FILES; ?></h3></td>
              </tr>
              <tr>
                <td colspan="5" class="smallText">
<?php
      $per_page = MAX_ROW_LISTS_OPTIONS;
      $group_files = "select padg.download_group_id, padg.download_group_name, padgf.download_groups_file_id, padgf.download_group_filename, padg2f.download_group_file_description
                      from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " padg
                      left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " padgf
                      on padg.download_group_id = padgf.download_group_id
                      left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " padg2f
                      on padgf.download_groups_file_id = padg2f.download_groups_file_id
                      where padg.language_id = '" . (int)$languages_id . "'
                      and padg2f.language_id = '" . (int)$languages_id . "'
                      order by padg.download_group_id, padgf.download_group_filename ";
      if (!isset($group_file_page)) $group_file_page = 1;
      $prev_group_file_page = $group_file_page - 1;
      $next_group_file_page = $group_file_page + 1;

      $group_file_query = tep_db_query($group_files);

      $group_file_page_start = ($per_page * $group_file_page) - $per_page;
      $num_rows = tep_db_num_rows($group_file_query);

      if ($num_rows <= $per_page) {
        $num_pages = 1;
      } else if (($num_rows % $per_page) == 0) {
        $num_pages = ($num_rows / $per_page);
      } else {
        $num_pages = ($num_rows / $per_page) + 1;
      }
      $num_pages = (int) $num_pages;

      $group_files = $group_files . " LIMIT $group_file_page_start, $per_page";

      // Previous
      if ($prev_group_file_page)  {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&group_file_page=' . $prev_group_file_page) . '"> &lt;&lt; </a> | ';
      }

      for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $group_file_page) {
           echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'group_file_page=' . $i) . '">' . $i . '</a> | ';
        } else {
           echo '<b><font color=red>' . $i . '</font></b> | ';
        }
      }

      // Next
      if ($group_file_page != $num_pages) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'group_file_page=' . $next_group_file_page) . '"> &gt;&gt;</a> ';
      }
?>
                </td>
              </tr>
             
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILEGROUP_NAME; ?></td>
                <td class="dataTableHeadingContent" width="160"><?php echo TABLE_HEADING_GROUPFILE_DESCRIPTION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_GROUPFILE_NAME; ?></td>
                <td class="dataTableHeadingContent" width="150"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
         
<?php
      $next_id = 1;
      $last_id = 0;
      $rows = 0;
      $group_files = tep_db_query($group_files);
      while ($group_files_array = tep_db_fetch_array($group_files)) {
        $group_name = $group_files_array['download_group_name'];
        $file_description = $group_files_array['download_group_file_description'];
        $file_name = $group_files_array['download_group_filename'];
        if ($rows === 0) $rows = 1;
        if ($last_id != $group_files_array['download_group_id']) {
          if ($last_id != 0) {
?>
              </table></td></tr>
<?php
          }
          $rows++;
          $last_id = $group_files_array['download_group_id'];
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                <td align="center" class="smallText"><?php echo $group_files_array['download_group_id']; ?></td>
                <td class="smallText"><?php echo $group_name; ?></td>
                <td class="smallText" colspan="3"><table width="100%" cellspacing="0" cellpadding="0">
<?php
        }
        if ($action == 'update_group_file' && $_GET['filegroup_id'] == $group_files_array['download_group_id'] && $_GET['group_file_id'] == $group_files_array['download_groups_file_id']) {
          echo '<form name="group_file" action="'.tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_file_desc&option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : '') . (isset($_GET['file_group_page']) ? '&file_group_page=' . $file_group_page : '') . (isset($_GET['group_file_page']) ? '&group_file_page=' . $group_file_page : ''), 'NONSSL').'" method="post">';
          $inputs = '';
          for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
            $file_desc = tep_db_query("select download_group_file_description from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " where download_groups_file_id = '" . (int)$group_files_array['download_groups_file_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
            $file_desc = tep_db_fetch_array($file_desc);
            $inputs .= $languages[$i]['code'] . ':<input class="form-control" type="text" name="file_desc[' . $languages[$i]['id'] . ']" size="15" value="' . $file_desc['download_group_file_description'] . '">';
          }
?>
                
                <td class="smallText"><?php echo $inputs; ?></td>
                <td class="smallText"><input type="hidden" name="file_id" value="<?php echo $group_files_array['download_groups_file_id']; ?>"><?php echo $file_name; ?></td>
                <td align="center" class="smallText"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a></td>
<?php
          echo '</form>';
        } else {
?>
                <tr>
                  <td class="smallText" width="160"><?php echo $file_description; ?></td>
                  <td class="smallText"><?php echo $file_name; ?></td>
                  <td align="right" class="smallText" width="150"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_group_file&filegroup_id=' . $group_files_array['download_group_id'] . '&group_file_id=' . $group_files_array['download_groups_file_id'] . (isset($_GET['group_file_page']) ? '&group_file_page=' . $_GET['group_file_page'] : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_group_file&filegroup_id=' . $group_files_array['download_group_id'] . '&group_file_id=' . $group_files_array['download_groups_file_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a></td>
                </tr>
<?php
        }
        $max_file_id_query = tep_db_query("select max(download_groups_file_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES);
        $max_file_id_array = tep_db_fetch_array($max_file_id_query);
        $next_id = $max_file_id_array['next_id'];
      }
      if ($last_id != 0) {
?>
              </table></td></tr>
<?php } ?>
              
<?php
      if ($file_groups_num === 0) {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                <td class="smallText" colspan="5"><?php echo TEXT_CREATE_GROUP_FIRST; ?></td>
              </tr>
<?php
      } else if ($action != 'update_group_file') {
        $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
        echo '<form name="group_files" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_group_file&group_file_page=' . $group_file_page, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">+</td>
                <td align="center" class="smallText"><select class="form-control" name="filegroup_id">
<?php
        $file_groups_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where language_id = '" . (int)$languages_id . "' and download_group_id != 0 order by download_group_id");
        while ($file_groups = tep_db_fetch_array($file_groups_query)) {
          echo '<option name="' . TEXT_OPTION_FILEGROUP . '" value="' . $file_groups['download_group_id'] . '">' . $file_groups['download_group_name'] . '</option>';
        }

        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $inputs .= $languages[$i]['code'] . ':<input class="form-control" type="text" name="file_desc[' . $languages[$i]['id'] . ']" size="15">';
        }
?>
                </select></td>
                <td class="smallText"><input type="hidden" name="groupfile_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?><?php echo tep_draw_input_field('input_filename', '', 'size="15"'); ?></td>
                <td align="center" class="smallText"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
<?php
        echo '</form>';
?>
              </tr>
             
              <tr class="<?php echo (floor(($rows + 1)/2) == (($rows + 1)/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
        echo '<form name="massive_files" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=mass_group_file&group_file_page=' . $group_file_page, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&gt;&gt;</td>
                <td align="center" class="smallText"><select class="form-control" name="filegroup_id">
<?php
        $file_groups_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where language_id = '" . (int)$languages_id . "' and download_group_id != 0 order by download_group_id");
        while ($file_groups = tep_db_fetch_array($file_groups_query)) {
          echo '<option name="' . TEXT_OPTION_FILEGROUP . '" value="' . $file_groups['download_group_id'] . '">' . $file_groups['download_group_name'] . '</option>';
        }

        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $inputs .= $languages[$i]['code'] . ':<input type="text" name="file_desc[' . $languages[$i]['id'] . ']" size="15">';
        }
?>
                </select></td>
                <td colspan="2" class="smallText" align="center"><?php echo TEXT_MASSIVE_INPUT; ?></td>
                <td align="center" class="smallText"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
<?php
        echo '</form>';
?>
              </tr>
             
<?php
      }
    }
?>
<!-- Files in Groups EOF //-->
            </table></td>
          </tr>
        </table></td>
      </tr>
