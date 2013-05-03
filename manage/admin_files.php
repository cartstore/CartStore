<?php
/*
  $Id: admin_files.php,v 1.29 2002/03/17 17:52:23 harley_vb Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;
  $current_files = DIR_FS_ADMIN;
  
  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'box_store':       
        $sql_data_array = array('admin_files_name' => tep_db_prepare_input($_GET['box']),
                                'admin_files_is_boxes' => '1');
        tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array);
        $admin_boxes_id = tep_db_insert_id();
        
        tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $admin_boxes_id));
        break;
      case 'box_remove':
        // NOTE: ALSO DELETE FILES STORED IN REMOVED BOX //
        $admin_boxes_id = tep_db_prepare_input($_GET['cID']);
        tep_db_query("delete from " . TABLE_ADMIN_FILES . " where admin_files_id = '" . $admin_boxes_id . "' or admin_files_to_boxes = '" . $admin_boxes_id . "'");
        
        tep_redirect(tep_href_link(FILENAME_ADMIN_FILES));
        break;
      case 'file_store':
        $sql_data_array = array('admin_files_name' => tep_db_prepare_input($_POST['admin_files_name']),
                                'admin_files_to_boxes' => tep_db_prepare_input($_POST['admin_files_to_boxes']));
        tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array);
        $admin_files_id = tep_db_insert_id();

        tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $admin_files_id));
        break;
      case 'file_remove':
        $admin_files_id = tep_db_prepare_input($_POST['admin_files_id']);      
        tep_db_query("delete from " . TABLE_ADMIN_FILES . " where admin_files_id = '" . $admin_files_id . "'");
        
        tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath']));
        break;
    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
<?php
 if ($_GET['fID'] || $_GET['cPath']) {
  //$current_box_query_raw = "select admin_files_name as admin_box_name from " . TABLE_ADMIN_FILES . " where admin_files_id = " . $_GET['cPath'] . " ";
  $current_box_query = tep_db_query("select admin_files_name as admin_box_name from " . TABLE_ADMIN_FILES . " where admin_files_id = " . $_GET['cPath']);
  $current_box = tep_db_fetch_array($current_box_query); 
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILENAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $db_file_query_raw = "select * from " . TABLE_ADMIN_FILES . " where admin_files_to_boxes = " . $_GET['cPath'] . " order by admin_files_name";
  $db_file_query = tep_db_query($db_file_query_raw);
  $file_count = 0;
  
  while ($files = tep_db_fetch_array($db_file_query)) {
    $file_count++;
    
    if (((!$_GET['fID']) || ($_GET['fID'] == $files['admin_files_id'])) && (!$fInfo) ) {
      $fInfo = new objectInfo($files);
    }

    if ( (is_object($fInfo)) && ($files['admin_files_id'] == $fInfo->admin_files_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=edit_file') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $files['admin_files_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($fInfo)) && ($files['admin_files_id'] == $fInfo->admin_files_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  } 
  
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_FILES . $file_count; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $_GET['cPath']) . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a>&nbsp<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . tep_image_button('button_admin_files.png', IMAGE_INSERT_FILE) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php
 } else {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="60%"><?php echo TABLE_HEADING_BOXES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php 
  $installed_boxes_query = tep_db_query("select admin_files_name as admin_boxes_name from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = 1 order by admin_files_name");
  $installed_boxes = array();
  while($db_boxes = tep_db_fetch_array($installed_boxes_query)) {
    $installed_boxes[] = $db_boxes['admin_boxes_name'];
  }
  
  $none = 0;
  $boxes = array();
  $dir = dir(DIR_WS_BOXES);
  while ($boxes_file = $dir->read()) {
    if ( (substr("$boxes_file", -4) == '.php') && !(in_array($boxes_file, $installed_boxes))){
      $boxes[] = array('admin_boxes_name' => $boxes_file,
                       'admin_boxes_id' => 'b' . $none);
    } elseif ( (substr("$boxes_file", -4) == '.php') && (in_array($boxes_file, $installed_boxes))) {
      $db_boxes_id_query = tep_db_query("select admin_files_id as admin_boxes_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = 1 and admin_files_name = '" . $boxes_file . "'");
      $db_boxes_id = tep_db_fetch_array($db_boxes_id_query);
      $boxes[] = array('admin_boxes_name' => $boxes_file,
                       'admin_boxes_id' => $db_boxes_id['admin_boxes_id']);
    }
    
  $none++;
  }
  $dir->close();
  sort($boxes);
  reset ($boxes);

  
  $boxnum = sizeof($boxes);
  $i = 0;
  while ($i < $boxnum) {
    if (((!$_GET['cID']) || ($_GET['none'] == $boxes[$i]['admin_boxes_id']) || ($_GET['cID'] == $boxes[$i]['admin_boxes_id'])) && (!$cInfo) ) {
      $cInfo = new objectInfo($boxes[$i]);
    }
    if ( (is_object($cInfo)) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id) ) {
      if ( substr("$cInfo->admin_boxes_id", 0,1) == 'b') {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $boxes[$i]['admin_boxes_id'] . '&action=store_file') . '\'">' . "\n";
      }
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
    }    
?>
                <td class="dataTableContent"><?php echo tep_image(DIR_WS_ICONS . 'folder.png', ICON_FOLDER) . ' <b>' . ucfirst (substr_replace ($boxes[$i]['admin_boxes_name'], '' , -4)) . '</b>'; ?></td>
                <td class="dataTableContent" align="center"><?php
                                               if ( (is_object($cInfo)) && ($_GET['cID'] == $boxes[$i]['admin_boxes_id'])) {
                                                 if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
                                                   echo tep_image(DIR_WS_IMAGES . 'icon_status_red.png', STATUS_BOX_NOT_INSTALLED, 10, 10) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id'] . '&box=' . $boxes[$i]['admin_boxes_name'] . '&action=box_store') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', STATUS_BOX_INSTALL, 10, 10) . '</a>';
                                                 } else {
                                                   echo '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $_GET['cID'] . '&action=box_remove') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', STATUS_BOX_REMOVE, 10, 10) . '</a>&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green.png', STATUS_BOX_INSTALLED, 10, 10);
                                                 }
                                               } else {
                                                 if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
                                                   echo tep_image(DIR_WS_IMAGES . 'icon_status_red.png', '', 10, 10) . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', '', 10, 10) . '</a>';
                                                 } else {
                                                   echo tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', '', 10, 10) . '</a>&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green.png', '', 10, 10);
                                                 }
                                               }
                                             ?>
                </td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $db_cat['admin_boxes_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
   $i++;
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php  echo TEXT_COUNT_BOXES . $boxnum; ?></td>
                    <td class="smallText" valign="top" align="right">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php  
 } 
?>
            </td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {  
    case 'store_file': 
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_FILE . '</b>');
      
      $file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' ");
      while ($fetch_files = tep_db_fetch_array($file_query)) {
        $files_array[] = $fetch_files['admin_files_name'];        
      }

      $file_dir = array();
      $dir = dir(DIR_FS_ADMIN);

      while ($file = $dir->read()) {
        if ((substr("$file", -4) == '.php') && $file != FILENAME_DEFAULT && $file != FILENAME_LOGIN && $file != FILENAME_LOGOFF && $file != FILENAME_FORBIDEN && $file != FILENAME_POPUP_IMAGE && $file != FILENAME_PASSWORD_FORGOTTEN && $file != FILENAME_ADMIN_ACCOUNT && $file != 'invoice.php' && $file != 'packingslip.php') {
            $file_dir[] = $file;
        }
      }
      
      $result = $file_dir;      
      if (sizeof($files_array) > 0) {
        $result = array_values (array_diff($file_dir, $files_array));
      }
      
      sort ($result);
      reset ($result);
      while (list ($key, $val) = each ($result)) {
        $show[] = array('id' => $val,
                        'text' => $val);
      }
      
      $contents = array('form' => tep_draw_form('store_file', FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=file_store', 'post', 'enctype="multipart/form-data"')); 
      $contents[] = array('text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</b>');
      $contents[] = array('text' => TEXT_INFO_NEW_FILE_INTRO );
      $contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . tep_draw_pull_down_menu('admin_files_name', $show, $show)); 
      $contents[] = array('text' => tep_draw_hidden_field('admin_files_to_boxes', $_GET['cPath']));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');    
      break;
    case 'remove_file': 
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FILE . '</b>');

      $contents = array('form' => tep_draw_form('remove_file', FILENAME_ADMIN_FILES, 'action=file_remove&cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'], 'post', 'enctype="multipart/form-data"')); 
      $contents[] = array('text' => tep_draw_hidden_field('admin_files_id', $_GET['fID']));
      $contents[] = array('text' =>  sprintf(TEXT_INFO_DELETE_FILE_INTRO, $fInfo->admin_files_name, ucfirst(substr_replace ($current_box['admin_box_name'], '', -4))) );    
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_confirm.png', IMAGE_CONFIRM) . ' <a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $_GET['fID']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');    
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFAULT_BOXES . $cInfo->admin_boxes_name . '</b>');
        if ( substr($cInfo->admin_boxes_id, 0,1) == 'b') {
        $contents[] = array('text' => '<b>' . $cInfo->admin_boxes_name . ' ' . TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED . '</b><br>&nbsp;');
        $contents[] = array('text' => TEXT_INFO_DEFAULT_BOXES_INTRO);
        } else {
        $contents = array('form' => tep_draw_form('newfile', FILENAME_ADMIN_FILES, 'cPath=' . $cInfo->admin_boxes_id . '&action=store_file', 'post', 'enctype="multipart/form-data"')); 
        $contents[] = array('align' => 'center', 'text' => tep_image_submit('button_admin_files.png', IMAGE_INSERT_FILE) );
        $contents[] = array('text' => tep_draw_hidden_field('this_category', $cInfo->admin_boxes_id));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DEFAULT_BOXES_INTRO);
        }
        $contents[] = array('text' => '<br>');
      }
      if (is_object($fInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . tep_image_button('button_admin_files.png', IMAGE_INSERT_FILE) . '</a> <a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->admin_files_id . '&action=remove_file') . '">' . tep_image_button('button_admin_remove.png', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DEFAULT_FILE_INTRO . ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)));
      }      
  }
  
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td valign="top"  width="220px">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>