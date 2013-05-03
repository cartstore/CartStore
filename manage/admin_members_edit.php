<?php
/*
  $Id: admin_members.php,v 1.29 2002/03/17 17:52:23 harley_vb Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;

  if ($_GET['action']) {
    switch ($_GET['action']) {

      case 'group_define':
        $selected_checkbox = $_POST['groups_to_boxes'];

        $define_files_query = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " order by admin_files_id");
        while ($define_files = tep_db_fetch_array($define_files_query)) {
          $admin_files_id = $define_files['admin_files_id'];

          if (in_array ($admin_files_id, $selected_checkbox)) {
            $sql_data_array = array('admin_id' => tep_db_prepare_input($_POST['checked_' . $admin_files_id]));
            //$set_group_id = $_POST['checked_' . $admin_files_id];
          } else {
            $sql_data_array = array('admin_id' => tep_db_prepare_input($_POST['unchecked_' . $admin_files_id]));
            //$set_group_id = $_POST['unchecked_' . $admin_files_id];
          }
		  //$sql_data_array = array('admin_access_values' => tep_db_prepare_input($_POST[ $admin_access_values]));
          tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array, 'update', 'admin_files_id = \'' . $admin_files_id . '\'');

        }

        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_POST['admin_id']));
        break;
      case 'group_delete':
        $set_admin_id = tep_db_prepare_input($_POST['set_admin_id']);

        tep_db_query("delete from " . TABLE_ADMIN . " where admin_id = '" . $_GET['gID'] . "'");
        tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_id admin_id set( " . $set_admin_id . " ) NOT NULL DEFAULT '1' ");
        tep_db_query("delete from " . TABLE_ADMIN . " where admin_id = '" . $_GET['gID'] . "'");

        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=groups'));
        break;
      case 'group_edit':
        $admin_firstname = ucwords(strtolower(tep_db_prepare_input($_POST['admin_firstname'])));
        $name_replace = preg_replace ("/ /", "%", $admin_firstname);

        if (($admin_firstname == '' || NULL) || (strlen($admin_firstname) <= 3) ) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET[gID] . '&gName=false&action=action=edit_group'));
        } else {
          $check_groups_name_query = tep_db_query("select admin_firstname as group_name_edit from " . TABLE_ADMIN . " where admin_id <> " . $_GET['gID'] . " and admin_firstname like '%" . $name_replace . "%'");
          $check_duplicate = tep_db_num_rows($check_groups_name_query);
          if ($check_duplicate > 0){
            tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET['gID'] . '&gName=used&action=edit_group'));
          } else {
            $admin_id = $_GET['gID'];
            tep_db_query("update " . TABLE_ADMIN . " set admin_firstname = '" . $admin_firstname . "' where admin_id = '" . $admin_id . "'");
            tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $admin_id));
          }
        }
        break;
      case 'group_new':
        $admin_firstname = ucwords(strtolower(tep_db_prepare_input($_POST['admin_firstname'])));
        $name_replace = preg_replace ("/ /", "%", $admin_firstname);

        if (($admin_firstname == '' || NULL) || (strlen($admin_firstname) <= 1) ) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET[gID] . '&gName=false&action=new_group'));
        } else {
          $check_groups_name_query = tep_db_query("select admin_firstname as group_name_new from " . TABLE_ADMIN . " where admin_firstname like '%" . $name_replace . "%'");
          $check_duplicate = tep_db_num_rows($check_groups_name_query);
          if ($check_duplicate > 0){
            tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET['gID'] . '&gName=used&action=new_group'));
          } else {
            $sql_data_array = array('admin_firstname' => $admin_firstname);
            tep_db_perform(TABLE_ADMIN, $sql_data_array);
            $admin_id = tep_db_insert_id();

            $set_admin_id = tep_db_prepare_input($_POST['set_admin_id']);
            $add_group_id = $set_admin_id . ',\'' . $admin_id . '\'';
            tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_id admin_id set( " . $add_group_id . ") NOT NULL DEFAULT '1' ");

            tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $admin_id));
          }
        }
        break;
		case 'add_access':
		$admin_files_id =tep_db_prepare_input($_POST['admin_files_id']);
		$admin_id = tep_db_prepare_input($_POST['admin_id']);
		$admin_access_values = tep_db_prepare_input($_POST['admin_access_values']);

		tep_db_query("insert into " . TABLE_ADMIN_ACCESS_FILES . " (admin_files_id, admin_id, admin_access_values) values ('" . (int)$admin_files_id . "', '" .(int) $admin_id . "', '" . (int)$admin_access_values . "')");
		 tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $admin_id. '&action=define_group'));
		break;

		case 'update_access':
		$gPath = tep_db_prepare_input($_POST['gPath']);
	$file_access_id = tep_db_prepare_input($_POST['file_access_id']);
		$admin_access_values = tep_db_prepare_input($_POST['admin_access_values']);
		$delete_value = tep_db_prepare_input($_POST['delete_value']);
			if ($delete_value != '1') {
			tep_db_query("update " . TABLE_ADMIN_ACCESS_FILES . " set admin_access_values = '" . $admin_access_values . "' where file_access_id = '" .  $file_access_id . "'");

			} else if ($delete_value = '1'){

		 tep_db_query("delete from " . TABLE_ADMIN_ACCESS_FILES . " where file_access_id = '" .  $file_access_id . "'");
		}
		 tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $gPath. '&action=define_group'));
		break;

    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<?php require('includes/account_check.js.php'); ?>
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
 if ($_GET['gPath']) {
   $group_name_query = tep_db_query("select admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id = " . $_GET['gPath']);
   $group_name = tep_db_fetch_array($group_name_query);

   if ($_GET['gPath'] == 1) {
     echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET['gPath']);
   } elseif ($_GET['gPath'] != 1) {
     echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $_GET['gPath'] . '&action=group_define', 'post', 'enctype="multipart/form-data"');
     echo tep_draw_hidden_field('admin_id', $_GET['gPath']);
   }
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td colspan=2 class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_GROUPS_DEFINE; ?></td>
              </tr>
<?php
  $db_boxes_query = tep_db_query("select admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_id as boxes_group_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '1' order by admin_files_name");
  while ($group_boxes = tep_db_fetch_array($db_boxes_query)) {
    $group_boxes_files_query = tep_db_query("select admin_files_id, admin_files_name, admin_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' order by admin_files_name");

    $selectedGroups = $group_boxes['boxes_group_id'];
    $groupsArray = explode(",", $selectedGroups);

    if (in_array($_GET['gPath'], $groupsArray)) {
      $del_boxes = array($_GET['gPath']);
      $result = array_diff ($groupsArray, $del_boxes);
      sort($result);
      $checkedBox = $selectedGroups;
      $uncheckedBox = implode (",", $result);
      $checked = true;
    } else {
      $add_boxes = array($_GET['gPath']);
      $result = array_merge ($add_boxes, $groupsArray);
      sort($result);
      $checkedBox = implode (",", $result);
      $uncheckedBox = $selectedGroups;
      $checked = false;
    }
?>
              <tr class="dataTableRowBoxes">
                <td class="dataTableContent" width="23"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, '', 'id="groups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkGroups(this)"'); ?></td>
                <td class="dataTableContent"><b><?php echo ucwords(substr_replace ($group_boxes['admin_boxes_name'], '', -4)) . ' ' . tep_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox); ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">
                  <table width="615" height="21" border="0" cellpadding="0" cellspacing="0">
                          <?php
     //$group_boxes_files_query = tep_db_query("select admin_files_id, admin_files_name, admin_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' order by admin_files_name");
     while($group_boxes_files = tep_db_fetch_array($group_boxes_files_query)) {
       $selectedGroups = $group_boxes_files['admin_id'];
       $groupsArray = explode(",", $selectedGroups);

       if (in_array($_GET['gPath'], $groupsArray)) {
         $del_boxes = array($_GET['gPath']);
         $result = array_diff ($groupsArray, $del_boxes);
         sort($result);
         $checkedBox = $selectedGroups;
         $uncheckedBox = implode (",", $result);
         $checked = true;
       } else {
         $add_boxes = array($_GET['gPath']);
         $result = array_merge ($add_boxes, $groupsArray);
         sort($result);
         $checkedBox = implode (",", $result);
         $uncheckedBox = $selectedGroups;
         $checked = false;
       }
?>
                          <tr>
                            <td width="20"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, '', 'id="subgroups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkSub(this)"'); ?></td>
                            <td width="154" class="dataTableContent"><?php echo $group_boxes_files['admin_files_name'] . ' ' . tep_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);?></td>

					  <td width="441" class="dataTableContent"> <div align="right">
                              <?php
			$admin_access_query = tep_db_query("select file_access_id, admin_files_id, admin_id from " .TABLE_ADMIN_ACCESS_FILES . " where admin_id='" . $_GET['gPath'] . "' and admin_files_id='" . $group_boxes_files['admin_files_id'] . "'");
$admin_access = tep_db_fetch_array($admin_access_query);
$admin_id = $admin_access['admin_id'];
$admin_files_id = $admin_access['admin_files_id'];
if ($_GET['gPath'] != 1 and $admin_files_id >0) { echo  '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath'] . '&file_access_id='.$admin_access['file_access_id'].'&action=edit_access').'">','<font color="#0066cc">' .TEXT_EDIT_ACCESS_VALUE . '</font></a>&nbsp;';
}
elseif ($admin_files_id=="" and $_GET['gPath'] != 1) {
echo '&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath'] . '&admin_files_id='.$group_boxes_files['admin_files_id'].'&action=insert_access').'">', '<font color="#FF0000">' .TEXT_ADD_ACCESS_VALUE . '</font></a> '; }


  ?>

                              </div></td>

                          </tr>
                          <?php
     }
?>
                        </table>
                </td>
              </tr>
<?php
  }
?>
              <tr class="dataTableRowBoxes">
                <td colspan=2 class="dataTableContent" valign="top" align="right"><?php if ($_GET['gPath'] != 1) { echo  '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_save.png', IMAGE_INSERT); } else { echo tep_image_submit('button_back.png', IMAGE_BACK); } ?>&nbsp;</td>
              </tr>
            </table></form>
<?php
 } elseif ($_GET['gID']) {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_GROUPS_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $db_groups_query = tep_db_query("select * from " . TABLE_ADMIN . " order by admin_id");

  $add_groups_prepare = '\'0\'' ;
  $del_groups_prepare = '\'0\'' ;
  $count_groups = 0;
  while ($groups = tep_db_fetch_array($db_groups_query)) {
    $add_groups_prepare .= ',\'' . $groups['admin_id'] . '\'' ;
    if (((!$_GET['gID']) || ($_GET['gID'] == $groups['admin_id']) || ($_GET['gID'] == 'groups')) && (!$gInfo) ) {
      $gInfo = new objectInfo($groups);
    }

    if ( (is_object($gInfo)) && ($groups['admin_id'] == $gInfo->admin_id) ) {
      echo '                <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $groups['admin_id'] . '&action=edit_group') . '\'">' . "\n";
      $current_row='dataTableContentSelectedList';
    } else {
      echo '                <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $groups['admin_id']) . '\'">' . "\n";
      $current_row='dataTableContent';
      $del_groups_prepare .= ',\'' . $groups['admin_id'] . '\'' ;
    }
?>
                <td class="<?php echo $current_row; ?>">&nbsp;<b><?php echo $groups['admin_firstname']; ?></b></td>
                <td class="<?php echo $current_row; ?>" align="right"><?php if ( (is_object($gInfo)) && ($groups['admin_id'] == $gInfo->admin_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $groups['admin_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    $count_groups++;
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_GROUPS . $count_groups; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT) . '">' .  IMAGE_BACK . '</a> <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gID=' . $gInfo->admin_id . '&action=new_group') . '">' .  IMAGE_NEW_GROUP . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php
 } else {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_GROUPS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LOGNUM; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $db_admin_query_raw = "select * from " . TABLE_ADMIN . " order by admin_firstname";

  $db_admin_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $db_admin_query_raw, $db_admin_query_numrows);
  $db_admin_query = tep_db_query($db_admin_query_raw);

//Mett
  while ($admin = tep_db_fetch_array($db_admin_query)) {
    $admin_group_query = tep_db_query("select a.admin_firstname, a.admin_lastname, a.admin_groups_id, b.admin_groups_id, b.admin_groups_name from " . TABLE_ADMIN . " a,  " . TABLE_ADMIN_GROUPS . " b where a.admin_groups_id  = b.admin_groups_id and a.admin_id = '" . $admin['admin_id'] . "'");
    $admin_group = tep_db_fetch_array ($admin_group_query);

    if (((!$_GET['mID']) || ($_GET['mID'] == $admin['admin_id'])) && (!$mInfo) ) {
      $mInfo_array = array_merge($admin, $admin_group);
      $mInfo = new objectInfo($mInfo_array);
    }

    if ( (is_object($mInfo)) && ($admin['admin_id'] == $mInfo->admin_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id'] . '&action=edit_member') . '\'">' . "\n";
      $current_row='dataTableContentSelectedList';
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id']) . '\'">' . "\n";
      $current_row='dataTableContent';
    }
?>
                <td class="<?php echo $current_row; ?>">&nbsp;<?php echo $admin['admin_firstname']; ?>&nbsp;<?php echo $admin['admin_lastname']; ?></td>
                <td class="<?php echo $current_row; ?>"><?php echo $admin['admin_email_address']; ?></td>
                <td class="<?php echo $current_row; ?>" align="center"><?php
				echo $admin_group['admin_groups_name'];
				 ?></td>
                <td class="<?php echo $current_row; ?>" align="center"><?php echo $admin['admin_lognum']; ?></td>
                <td class="<?php echo $current_row; ?>" align="right"><?php if ( (is_object($mInfo)) && ($admin['admin_id'] == $mInfo->admin_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $db_admin_split->display_count($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MEMBERS); ?><br><?php echo $db_admin_split->display_links($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>

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

    case 'new_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_GROUPS . '</b>');

      $contents = array('form' => tep_draw_form('new_group', FILENAME_ADMIN_MEMBERS_EDIT, 'action=group_new&gID=' . $gInfo->admin_id, 'post', 'enctype="multipart/form-data"'));
      if ($_GET['gName'] == 'false') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;');
      } elseif ($_GET['gName'] == 'used') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;');
      }
      $contents[] = array('text' => tep_draw_hidden_field('set_admin_id', substr($add_groups_prepare, 4)) );
      $contents[] = array('text' => TEXT_INFO_GROUPS_NAME . '<br>');
      $contents[] = array('align' => 'center', 'text' => tep_draw_input_field('admin_firstname'));
      $contents[] = array('align' => 'center', 'text' => '<br><a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath']) . '">' . IMAGE_CANCEL . '</a> ' . tep_image_submit('button_next.png', IMAGE_NEXT) );
      break;
    case 'edit_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');

      $contents = array('form' => tep_draw_form('edit_group', FILENAME_ADMIN_MEMBERS_EDIT, 'action=group_edit&gID=' . $_GET['gID'], 'post', 'enctype="multipart/form-data"'));
      if ($_GET['gName'] == 'false') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;');
      } elseif ($_GET['gName'] == 'used') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;');
      }
      $contents[] = array('align' => 'center', 'text' => TEXT_INFO_EDIT_GROUP_INTRO . '<br>&nbsp;<br>' . tep_draw_input_field('admin_firstname', $gInfo->admin_firstname));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath']) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    case 'del_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_GROUPS . '</b>');

      $contents = array('form' => tep_draw_form('delete_group', FILENAME_ADMIN_MEMBERS_EDIT, 'action=group_delete&gID=' . $gInfo->admin_id, 'post', 'enctype="multipart/form-data"'));
      if ($gInfo->admin_id == 1) {
        $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO_NOT, $gInfo->admin_firstname));
        $contents[] = array('align' => 'center', 'text' => '<br><a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath']) . '">' .  IMAGE_BACK . '</a><br>&nbsp;');
      } else {
        $contents[] = array('text' => tep_draw_hidden_field('set_admin_id', substr($del_groups_prepare, 4)) );
        $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO, $gInfo->admin_firstname));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $_GET['gPath']) . '">' . IMAGE_CANCEL . '</a><br>&nbsp;');
      }
      break;
    case 'define_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>');

      $contents[] = array('text' => sprintf(TEXT_INFO_DEFINE_INTRO, $group_name['admin_firstname'], '&nbsp;', $group_name['admin_lastname']));
      if ($_GET['gPath'] == 1) {
        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT) . '">' .  IMAGE_CANCEL . '</a><br>');
      }
      break;

	  case 'insert_access':
  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>');
		   $admin_access_query = tep_db_query("select admin_files_id, admin_files_name, admin_files_is_boxes from " .TABLE_ADMIN_FILES . " where admin_files_id=".$_GET['admin_files_id']." and admin_files_is_boxes = '0'");
$admin_access = tep_db_fetch_array($admin_access_query);
$admin_id= $_GET['gPath'];
$admin_files_id = $admin_access['admin_files_id'];

	  $contents = array('form' => tep_draw_form('insert_access',  FILENAME_ADMIN_MEMBERS_EDIT, '&action=add_access',  'post', 'enctype="multipart/form-data"'));
 $contents[] = array('text' => tep_draw_hidden_field('admin_id', $admin_id));
 $contents[] = array('text' => tep_draw_hidden_field('admin_files_id', $admin_files_id));
 $contents[] = array('text' => sprintf($admin_access['admin_files_name']));
	 $contents[] = array('text' => sprintf($group_name['admin_firstname'].'&nbsp;'.$group_name['admin_lastname']));
		$contents[] = array('text' =>  tep_draw_radio_field('admin_access_values', '2', true) . ' ' . TEXT_INFO_NO_RESTRICTIONS);
      	 $contents[] = array('text' =>  tep_draw_radio_field('admin_access_values', '3', false) . ' ' . TEXT_INFO_PARTIAL_ACCESS);
		 $contents[] = array('text' => tep_draw_radio_field('admin_access_values', '4', false) . ' ' . TEXT_INFO_READ_ONLY);
		 $contents[] = array('text' => tep_draw_radio_field('admin_access_values', '5', false) . ' ' . TEXT_INFO_FORBIDDEN);

	$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"') . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' .  IMAGE_CANCEL . '</a>');
	  echo '</form>';
	  break;

		   case 'edit_access':
  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>');
	   $admin_edit_query = tep_db_query("select a.file_access_id, a.admin_files_id, a.admin_id, a.admin_access_values, b.admin_files_name, b.admin_files_id from " .TABLE_ADMIN_ACCESS_FILES . " a inner join " .TABLE_ADMIN_FILES . " b on a.admin_files_id = b.admin_files_id where a.file_access_id='" . $file_access_id . "'");
	     $contents = array('form' => tep_draw_form('edit_access', FILENAME_ADMIN_MEMBERS_EDIT,  '&action=update_access', 'post', 'enctype="multipart/form-data"'));
while($admin_edit = tep_db_fetch_array($admin_edit_query)){
 $contents[] = array('text' => sprintf($admin_edit['admin_files_name']));
	 $contents[] = array('text' => sprintf($group_name['admin_firstname'].'&nbsp;'.$group_name['admin_lastname']));
	 $contents[] = array('text' => tep_draw_hidden_field('file_access_id', $admin_edit['file_access_id']));
	$contents[] = array('text' => tep_draw_hidden_field('gPath', $gPath));
	  switch ($admin_edit['admin_access_values']) {
      case $admin_edit['admin_access_values'] == 2: $norestrict = true; $partial = false; $readonly = false;$forbidden = false; break;
       case $admin_edit['admin_access_values'] == 3: $norestrict = false; $partial = true; $readonly = false; $forbidden = false; break;
       case $admin_edit['admin_access_values'] == 4: $norestrict = false; $partial = false; $readonly = true; $forbidden = false; break;
	   case $admin_edit['admin_access_values'] == 5: $norestrict = false; $partial = false; $readonly = true; $forbidden = true; break;
    }

		$contents[] = array('text' =>  tep_draw_radio_field('admin_access_values', '2', $norestrict) . ' ' . TEXT_INFO_NO_RESTRICTIONS);
      	 $contents[] = array('text' =>  tep_draw_radio_field('admin_access_values', '3', $partial) . ' ' . TEXT_INFO_PARTIAL_ACCESS);
		 $contents[] = array('text' => tep_draw_radio_field('admin_access_values', '4', $readonly) . ' ' . TEXT_INFO_READ_ONLY);
	$contents[] = array('text' => tep_draw_radio_field('admin_access_values', '5', $forbidden) . ' ' . TEXT_INFO_FORBIDDEN);
	//delete -reset
		$contents[] = array('text' => tep_draw_checkbox_field('delete_value', '1', $delete_value) . ' '.  TEXT_DELETE_ACCESS_VALUE);

   }
	 $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"') . ' <a  class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT) . '">' .  IMAGE_CANCEL . '</a>');
	  echo '</form>';
    break;

   case 'show_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');
        $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . "");
        //$stored_email[];
        while ($check_email = tep_db_fetch_array($check_email_query)) {
          $stored_email[] = $check_email['admin_email_address'];
        }

        if (in_array($_POST['admin_email_address'], $stored_email)) {
          $checkEmail = "true";
        } else {
          $checkEmail = "false";
        }
      $contents = array('form' => tep_draw_form('show_group', FILENAME_ADMIN_MEMBERS_EDIT, 'action=show_group&gID=groups', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => $define_files['admin_files_name'] . tep_draw_input_field('level_edit', $checkEmail));

      break;
    default:
      if (is_object($mInfo)) {
        $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $mInfo->admin_id . '&action=define_group') . '">' . IMAGE_FILE_PERMISSION . '</a>');
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_FULLNAME . '</b><br>&nbsp;' . $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_EMAIL . '</b><br>&nbsp;' . $mInfo->admin_email_address);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_GROUP . '</b>' . $mInfo->admin_firstname);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_CREATED . '</b><br>&nbsp;' . $mInfo->admin_created);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_MODIFIED . '</b><br>&nbsp;' . $mInfo->admin_modified);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGDATE . '</b><br>&nbsp;' . $mInfo->admin_logdate);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGNUM . '</b>' . $mInfo->admin_lognum);
        $contents[] = array('text' => '<br>');
      } elseif (is_object($gInfo)) {
        $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT_GROUPS . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS_EDIT, 'gPath=' . $gInfo->admin_id . '&action=define_group') . '">' .  IMAGE_FILE_PERMISSION . '</a> ');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DEFAULT_GROUPS_INTRO . '<br>&nbsp');
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