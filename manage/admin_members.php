<?php
  require('includes/application_top.php');
  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;
  if ($_GET['action']) {
      switch ($_GET['action']) {
          case 'member_new':
              $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . "");
              while ($check_email = tep_db_fetch_array($check_email_query)) {
                  $stored_email[] = $check_email['admin_email_address'];
              }
              if (in_array($_POST['admin_email_address'], $stored_email)) {
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . 'mID=' . $_GET['mID'] . '&error=email&action=new_member'));
              } else {
                  function randomize()
                  {
                      $salt = "abchefghjkmnpqrstuvwxyz0123456789";
                      srand((double)microtime() * 1000000);
                      $i = 0;
                      while ($i <= 7) {
                          $num = rand() % 33;
                          $tmp = substr($salt, $num, 1);
                          $pass = $pass . $tmp;
                          $i++;
                      }
                      return $pass;
                  }
                  $makePassword = randomize();
                  $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['admin_groups_id']), 'admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']), 'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']), 'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']), 'admin_password' => tep_encrypt_password($makePassword), 'admin_created' => 'now()');
                  tep_db_perform(TABLE_ADMIN, $sql_data_array);
                  $admin_id = tep_db_insert_id();
                  tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $admin_id));
              }
              break;
          case 'member_edit':
              $admin_id = tep_db_prepare_input($_POST['admin_id']);
              $hiddenPassword = '-hidden-';
              $stored_email[] = 'NONE';
              $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
              while ($check_email = tep_db_fetch_array($check_email_query)) {
                  $stored_email[] = $check_email['admin_email_address'];
              }
              if (in_array($_POST['admin_email_address'], $stored_email)) {
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . 'mID=' . $_GET['mID'] . '&error=email&action=edit_member'));
              } else {
                  $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['admin_groups_id']), 'admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']), 'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']), 'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']), 'admin_modified' => 'now()');
                  tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');
                  tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_EDIT_SUBJECT, sprintf(ADMIN_EMAIL_EDIT_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $admin_id));
              }
              break;
          case 'member_delete':
              $admin_id = tep_db_prepare_input($_POST['admin_id']);
              tep_db_query("delete from " . TABLE_ADMIN . " where admin_id = '" . $admin_id . "'");
              tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page']));
              break;
          case 'group_define':
              $selected_checkbox = $_POST['groups_to_boxes'];
              $define_files_query = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " order by admin_files_id");
              while ($define_files = tep_db_fetch_array($define_files_query)) {
                  $admin_files_id = $define_files['admin_files_id'];
                  if (in_array($admin_files_id, $selected_checkbox)) {
                      $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['checked_' . $admin_files_id]));

                  } else {
                      $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['unchecked_' . $admin_files_id]));

                  }
                  tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array, 'update', 'admin_files_id = \'' . $admin_files_id . '\'');
              }
              tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_POST['admin_groups_id']));
              break;
          case 'group_delete':
              $set_groups_id = tep_db_prepare_input($_POST['set_groups_id']);
              tep_db_query("delete from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . $_GET['gID'] . "'");
              tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_groups_id admin_groups_id set( " . $set_groups_id . " ) NOT NULL DEFAULT '1' ");
              tep_db_query("delete from " . TABLE_ADMIN . " where admin_groups_id = '" . $_GET['gID'] . "'");
              tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=groups'));
              break;
          case 'group_edit':
              $admin_groups_name = ucwords(strtolower(tep_db_prepare_input($_POST['admin_groups_name'])));
              $name_replace = preg_replace("/ /", "%", $admin_groups_name);
              if (($admin_groups_name == '' || null) || (strlen($admin_groups_name) <= 5)) {
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET[gID] . '&gName=false&action=action=edit_group'));
              } else {
                  $check_groups_name_query = tep_db_query("select admin_groups_name as group_name_edit from " . TABLE_ADMIN_GROUPS . " where admin_groups_id <> " . $_GET['gID'] . " and admin_groups_name like '%" . $name_replace . "%'");
                  $check_duplicate = tep_db_num_rows($check_groups_name_query);
                  if ($check_duplicate > 0) {
                      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID'] . '&gName=used&action=edit_group'));
                  } else {
                      $admin_groups_id = $_GET['gID'];
                      tep_db_query("update " . TABLE_ADMIN_GROUPS . " set admin_groups_name = '" . $admin_groups_name . "' where admin_groups_id = '" . $admin_groups_id . "'");
                      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $admin_groups_id));
                  }
              }
              break;
          case 'group_new':
              $admin_groups_name = ucwords(strtolower(tep_db_prepare_input($_POST['admin_groups_name'])));
              $name_replace = preg_replace("/ /", "%", $admin_groups_name);
              if (($admin_groups_name == '' || null) || (strlen($admin_groups_name) <= 5)) {
                  tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET[gID] . '&gName=false&action=new_group'));
              } else {
                  $check_groups_name_query = tep_db_query("select admin_groups_name as group_name_new from " . TABLE_ADMIN_GROUPS . " where admin_groups_name like '%" . $name_replace . "%'");
                  $check_duplicate = tep_db_num_rows($check_groups_name_query);
                  if ($check_duplicate > 0) {
                      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID'] . '&gName=used&action=new_group'));
                  } else {
                      $sql_data_array = array('admin_groups_name' => $admin_groups_name);
                      tep_db_perform(TABLE_ADMIN_GROUPS, $sql_data_array);
                      $admin_groups_id = tep_db_insert_id();
                      $set_groups_id = tep_db_prepare_input($_POST['set_groups_id']);
                      $add_group_id = $set_groups_id . ',\'' . $admin_groups_id . '\'';
                      tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_groups_id admin_groups_id set( " . $add_group_id . ") NOT NULL DEFAULT '1' ");
                      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $admin_groups_id));
                  }
              }
              break;
      }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php
  echo TITLE;
?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="includes/general.js"></script>
<?php
  require('includes/account_check.js.php');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="1" cellpadding="1" class="columnLeft">
        <!-- left_navigation //-->
        <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
        <!-- left_navigation_eof //-->
      </table></td>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><h3><?php
  echo HEADING_TITLE;
?></h3></td>
                <td class="pageHeading2" align="right"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><?php
  if ($_GET['gPath']) {
      $group_name_query = tep_db_query("select admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = " . $_GET['gPath']);
      $group_name = tep_db_fetch_array($group_name_query);
      if ($_GET['gPath'] == 1) {
          echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']);
      } elseif ($_GET['gPath'] != 1) {
          echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath'] . '&action=group_define', 'post', 'enctype="multipart/form-data"');
          echo tep_draw_hidden_field('admin_groups_id', $_GET['gPath']);
      }
?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td colspan=2 class="dataTableHeadingContent">&nbsp;<?php
      echo TABLE_HEADING_GROUPS_DEFINE;
?></td>
                    </tr>
                    <?php
      $db_boxes_query = tep_db_query("select admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_groups_id as boxes_group_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '1' order by admin_files_name");
      while ($group_boxes = tep_db_fetch_array($db_boxes_query)) {
          $group_boxes_files_query = tep_db_query("select admin_files_id, admin_files_name, admin_groups_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' order by admin_files_name");
          $selectedGroups = $group_boxes['boxes_group_id'];
          $groupsArray = explode(",", $selectedGroups);
          if (in_array($_GET['gPath'], $groupsArray)) {
              $del_boxes = array($_GET['gPath']);
              $result = array_diff($groupsArray, $del_boxes);
              sort($result);
              $checkedBox = $selectedGroups;
              $uncheckedBox = implode(",", $result);
              $checked = true;
          } else {
              $add_boxes = array($_GET['gPath']);
              $result = array_merge($add_boxes, $groupsArray);
              sort($result);
              $checkedBox = implode(",", $result);
              $uncheckedBox = $selectedGroups;
              $checked = false;
          }
?>
                    <tr class="dataTableRowBoxes">
                      <td class="dataTableContent" width="23"><?php
          echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, '', 'id="groups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkGroups(this)"');
?></td>
                      <td class="dataTableContent"><b><?php
          echo ucwords(substr_replace($group_boxes['admin_boxes_name'], '', -4)) . ' ' . tep_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox);
?></b></td>
                    </tr>
                    <tr class="dataTableRow">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent"><table border="0" cellspacing="0" cellpadding="0">
                          <?php

          while ($group_boxes_files = tep_db_fetch_array($group_boxes_files_query)) {
              $selectedGroups = $group_boxes_files['admin_groups_id'];
              $groupsArray = explode(",", $selectedGroups);
              if (in_array($_GET['gPath'], $groupsArray)) {
                  $del_boxes = array($_GET['gPath']);
                  $result = array_diff($groupsArray, $del_boxes);
                  sort($result);
                  $checkedBox = $selectedGroups;
                  $uncheckedBox = implode(",", $result);
                  $checked = true;
              } else {
                  $add_boxes = array($_GET['gPath']);
                  $result = array_merge($add_boxes, $groupsArray);
                  sort($result);
                  $checkedBox = implode(",", $result);
                  $uncheckedBox = $selectedGroups;
                  $checked = false;
              }
?>
                          <tr>
                            <td width="20"><?php
              echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, '', 'id="subgroups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkSub(this)"');
?></td>
                            <td class="dataTableContent"><?php
              echo $group_boxes_files['admin_files_name'] . ' ' . tep_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);
?></td>
                          </tr>
                          <?php
          }
?>
                        </table></td>
                    </tr>
                    <?php
      }
?>
                    <tr class="dataTableRowBoxes">
                      <td colspan=2 class="dataTableContent" valign="top" align="right"><?php
      if ($_GET['gPath'] != 1) {
          echo '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']) . '">' . IMAGE_CANCEL . '</a> ' . tep_image_submit('button_save.png', IMAGE_INSERT);
      } else {
          echo tep_image_submit('button_back.png', IMAGE_BACK);
      }
?>
                        &nbsp;</td>
                    </tr>
                  </table>
                  </form>
                  <?php
  } elseif ($_GET['gID']) {
?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent">&nbsp;<?php
      echo TABLE_HEADING_GROUPS_NAME;
?></td>
                      <td class="dataTableHeadingContent" align="right"><?php
      echo TABLE_HEADING_ACTION;
?>&nbsp;</td>
                    </tr>
                    <?php
      $db_groups_query = tep_db_query("select * from " . TABLE_ADMIN_GROUPS . " order by admin_groups_id");
      $add_groups_prepare = '\'0\'';
      $del_groups_prepare = '\'0\'';
      $count_groups = 0;
      while ($groups = tep_db_fetch_array($db_groups_query)) {
          $add_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'';
          if (((!$_GET['gID']) || ($_GET['gID'] == $groups['admin_groups_id']) || ($_GET['gID'] == 'groups')) && (!$gInfo)) {
              $gInfo = new objectInfo($groups);
          }
          if ((is_object($gInfo)) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id)) {
              echo '                <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id'] . '&action=edit_group') . '\'">' . "\n";
          } else {
              echo '                <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id']) . '\'">' . "\n";
              $del_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'';
          }
?>
                    <td class="dataTableContent">&nbsp;<b><?php
          echo $groups['admin_groups_name'];
?></b></td>
                      <td class="dataTableContent" align="right"><?php
          if ((is_object($gInfo)) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id)) {
              echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png');
          } else {
              echo '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
          }
?>
                        &nbsp;</td>
                    </tr>
                    <?php
          $count_groups++;
      }
?>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php
      echo TEXT_COUNT_GROUPS . $count_groups;
?></td>
                            <td class="smallText" valign="top" align="right"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS) . '">' . IMAGE_BACK . '</a> <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id . '&action=new_group') . '">' . IMAGE_NEW_GROUP . '</a>';
?>&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
      } else
      {
?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_NAME;
?></td>
                      <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_EMAIL;
?></td>
                      <td class="dataTableHeadingContent" align="center"><?php
          echo TABLE_HEADING_GROUPS;
?></td>
                      <td class="dataTableHeadingContent" align="center"><?php
          echo TABLE_HEADING_LOGNUM;
?></td>
                      <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_ACTION;
?>&nbsp;</td>
                    </tr>
                    <?php
          $db_admin_query_raw = "select * from " . TABLE_ADMIN . " order by admin_firstname";
          $db_admin_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $db_admin_query_raw, $db_admin_query_numrows);
          $db_admin_query = tep_db_query($db_admin_query_raw);

          while ($admin = tep_db_fetch_array($db_admin_query)) {
              $admin_group_query = tep_db_query("select admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . $admin['admin_groups_id'] . "'");
              $admin_group = tep_db_fetch_array($admin_group_query);
              if (((!$_GET['mID']) || ($_GET['mID'] == $admin['admin_id'])) && (!$mInfo)) {
                  $mInfo_array = array_merge($admin, $admin_group);
                  $mInfo = new objectInfo($mInfo_array);
              }
              if ((is_object($mInfo)) && ($admin['admin_id'] == $mInfo->admin_id)) {
                  echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id'] . '&action=edit_member') . '\'">' . "\n";
              } else {
                  echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id']) . '\'">' . "\n";
              }
?>
                    <td class="dataTableContent">&nbsp;<?php
              echo $admin['admin_firstname'];
?>&nbsp;<?php
              echo $admin['admin_lastname'];
?></td>
                      <td class="dataTableContent"><?php
              echo $admin['admin_email_address'];
?></td>
                      <td class="dataTableContent" align="center"><?php
              echo $admin_group['admin_groups_name'];
?></td>
                      <td class="dataTableContent" align="center"><?php
              echo $admin['admin_lognum'];
?></td>
                      <td class="dataTableContent" align="right"><?php
              if ((is_object($mInfo)) && ($admin['admin_id'] == $mInfo->admin_id)) {
                  echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png');
              } else {
                  echo '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $admin['admin_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
              }
?>
                        &nbsp;</td>
                    </tr>
                    <?php
          }
?>
                    <tr>
                      <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php
          echo $db_admin_split->display_count($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MEMBERS);
?><br>
                              <?php
          echo $db_admin_split->display_links($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']);
?></td>
                            <td class="smallText" valign="top" align="right"><?php
          echo '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=groups') . '">' . IMAGE_GROUPS . '</a>';
          echo ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->admin_id . '&action=new_member') . '">' . IMAGE_NEW_MEMBER . '</a>';
?>&nbsp;</td>
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
          case 'new_member':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>');
              $contents = array('form' => tep_draw_form('newmember', FILENAME_ADMIN_MEMBERS, 'action=member_new&page=' . $page . 'mID=' . $_GET['mID'], 'post', 'enctype="multipart/form-data"'));
              if ($_GET['error']) {
                  $contents[] = array('text' => TEXT_INFO_ERROR);
              }
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_FIRSTNAME . '<br>&nbsp;' . tep_draw_input_field('admin_firstname'));
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_LASTNAME . '<br>&nbsp;' . tep_draw_input_field('admin_lastname'));
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_EMAIL . '<br>&nbsp;' . tep_draw_input_field('admin_email_address'));
             /* $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
              $groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS);
              while ($groups = tep_db_fetch_array($groups_query)) {
                  $groups_array[] = array('id' => $groups['admin_groups_id'], 'text' => $groups['admin_groups_name']);
              }
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_GROUP . '<br>&nbsp;' . tep_draw_pull_down_menu('admin_groups_id', $groups_array, '0'));*/
              $contents[] = array('align' => 'center', 'text' => '<br>' .tep_draw_hidden_field('admin_groups_id', '1'). tep_image_submit('button_insert.png', IMAGE_INSERT, 'onClick="validateForm();return document.returnValue"') . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . IMAGE_CANCEL . '</a>');
              break;
          case 'edit_member':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>');
              $contents = array('form' => tep_draw_form('newmember', FILENAME_ADMIN_MEMBERS, 'action=member_edit&page=' . $page . '&mID=' . $_GET['mID'], 'post', 'enctype="multipart/form-data"'));
              if ($_GET['error']) {
                  $contents[] = array('text' => TEXT_INFO_ERROR);
              }
              $contents[] = array('text' => tep_draw_hidden_field('admin_id', $mInfo->admin_id));
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_FIRSTNAME . '<br>&nbsp;' . tep_draw_input_field('admin_firstname', $mInfo->admin_firstname));
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_LASTNAME . '<br>&nbsp;' . tep_draw_input_field('admin_lastname', $mInfo->admin_lastname));
              $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_EMAIL . '<br>&nbsp;' . tep_draw_input_field('admin_email_address', $mInfo->admin_email_address));
              if ($mInfo->admin_id == 1) {
                  $contents[] = array('text' => tep_draw_hidden_field('admin_groups_id', $mInfo->admin_groups_id));
              } else {
                  $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
                  $groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS);
                  while ($groups = tep_db_fetch_array($groups_query)) {
                      $groups_array[] = array('id' => $groups['admin_groups_id'], 'text' => $groups['admin_groups_name']);
                  }
                  $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_GROUP . '<br>&nbsp;' . tep_draw_pull_down_menu('admin_groups_id', $groups_array, $mInfo->admin_groups_id));
              }
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.png', IMAGE_INSERT, 'onClick="validateForm();return document.returnValue"') . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . IMAGE_CANCEL . '</a>');
              break;
          case 'del_member':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE . '</b>');
              if ($mInfo->admin_id == 1 || $mInfo->admin_email_address == STORE_OWNER_EMAIL_ADDRESS) {
                  $contents[] = array('align' => 'center', 'text' => '<br><a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->admin_id) . '">' . IMAGE_BACK . '</a><br>&nbsp;');
              } else {
                  $contents = array('form' => tep_draw_form('edit', FILENAME_ADMIN_MEMBERS, 'action=member_delete&page=' . $page . '&mID=' . $admin['admin_id'], 'post', 'enctype="multipart/form-data"'));
                  $contents[] = array('text' => tep_draw_hidden_field('admin_id', $mInfo->admin_id));
                  $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_INTRO, $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . IMAGE_CANCEL . '</a>');
              }
              break;
          case 'new_group':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_GROUPS . '</b>');
              $contents = array('form' => tep_draw_form('new_group', FILENAME_ADMIN_MEMBERS, 'action=group_new&gID=' . $gInfo->admin_groups_id, 'post', 'enctype="multipart/form-data"'));
              if ($_GET['gName'] == 'false') {
                  $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;');
              } elseif ($_GET['gName'] == 'used') {
                  $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;');
              }
              $contents[] = array('text' => tep_draw_hidden_field('set_groups_id', substr($add_groups_prepare, 4)));
              $contents[] = array('text' => TEXT_INFO_GROUPS_NAME . '<br>');
              $contents[] = array('align' => 'center', 'text' => tep_draw_input_field('admin_groups_name'));
              $contents[] = array('align' => 'center', 'text' => '<br><a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id) . '">' . IMAGE_CANCEL . '</a> ' . tep_image_submit('button_next.png', IMAGE_NEXT));
              break;
          case 'edit_group':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');
              $contents = array('form' => tep_draw_form('edit_group', FILENAME_ADMIN_MEMBERS, 'action=group_edit&gID=' . $_GET['gID'], 'post', 'enctype="multipart/form-data"'));
              if ($_GET['gName'] == 'false') {
                  $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;');
              } elseif ($_GET['gName'] == 'used') {
                  $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;');
              }
              $contents[] = array('align' => 'center', 'text' => TEXT_INFO_EDIT_GROUP_INTRO . '<br>&nbsp;<br>' . tep_draw_input_field('admin_groups_name', $gInfo->admin_groups_name));
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id) . '">' . IMAGE_CANCEL . '</a>');
              break;
          case 'del_group':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_GROUPS . '</b>');
              $contents = array('form' => tep_draw_form('delete_group', FILENAME_ADMIN_MEMBERS, 'action=group_delete&gID=' . $gInfo->admin_groups_id, 'post', 'enctype="multipart/form-data"'));
              if ($gInfo->admin_groups_id == 1) {
                  $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO_NOT, $gInfo->admin_groups_name));
                  $contents[] = array('align' => 'center', 'text' => '<br><a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID']) . '">' . IMAGE_BACK . '</a><br>&nbsp;');
              } else {
                  $contents[] = array('text' => tep_draw_hidden_field('set_groups_id', substr($del_groups_prepare, 4)));
                  $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO, $gInfo->admin_groups_name));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID']) . '">' . IMAGE_CANCEL . '</a><br>&nbsp;');
              }
              break;
          case 'define_group':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>');
              $contents[] = array('text' => sprintf(TEXT_INFO_DEFINE_INTRO, $group_name['admin_groups_name']));
              if ($_GET['gPath'] == 1) {
                  $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']) . '">' . IMAGE_CANCEL . '</a><br>');
              }
              break;
          case 'show_group':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');
              $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . "");

              while ($check_email = tep_db_fetch_array($check_email_query)) {
                  $stored_email[] = $check_email['admin_email_address'];
              }
              if (in_array($_POST['admin_email_address'], $stored_email)) {
                  $checkEmail = "true";
              } else {
                  $checkEmail = "false";
              }
              $contents = array('form' => tep_draw_form('show_group', FILENAME_ADMIN_MEMBERS, 'action=show_group&gID=groups', 'post', 'enctype="multipart/form-data"'));
              $contents[] = array('text' => $define_files['admin_files_name'] . tep_draw_input_field('level_edit', $checkEmail));

              break;
          default:
              if (is_object($mInfo)) {
                  $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->admin_id . '&action=edit_member') . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->admin_id . '&action=del_member') . '">' . IMAGE_DELETE . '</a><br>&nbsp;');
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_FULLNAME . '</b><br>&nbsp;' . $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_EMAIL . '</b><br>&nbsp;' . $mInfo->admin_email_address);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_GROUP . '</b>' . $mInfo->admin_groups_name);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_CREATED . '</b><br>&nbsp;' . $mInfo->admin_created);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_MODIFIED . '</b><br>&nbsp;' . $mInfo->admin_modified);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGDATE . '</b><br>&nbsp;' . $mInfo->admin_logdate);
                  $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGNUM . '</b>' . $mInfo->admin_lognum);
                  $contents[] = array('text' => '<br>');
              } elseif (is_object($gInfo)) {
                  $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT_GROUPS . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gPath=' . $gInfo->admin_groups_id . '&action=define_group') . '">' . IMAGE_FILE_PERMISSION . '</a> <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id . '&action=edit_group') . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id . '&action=del_group') . '">' . IMAGE_DELETE . '</a>');
                  $contents[] = array('text' => '<br>' . TEXT_INFO_DEFAULT_GROUPS_INTRO . '<br>&nbsp');
              }
      }
      if ((tep_not_null($heading)) && (tep_not_null($contents))) {
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
<?php
      require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
