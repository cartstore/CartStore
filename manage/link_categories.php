<?php
/*
  $Id: categories.php,v 1.16 2006/07/04 14:40:27 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');
// define our link functions
  require(DIR_WS_FUNCTIONS . 'links.php');
  
    // calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
       if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
       if ( ($_GET['flag'] == (int)STATUS_CATEGORIES_ENABLE_FLAG) || ($_GET['flag'] == (int)STATUS_CATEGORIES_DISABLE_FLAG)) {
        if (isset($_GET['lID'])) {
          tep_set_link_categories_status($_GET['lID'], $_GET['flag']);
        }
       }   
      }

      tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&lID=' . $_GET['lID']));
      break;
      case 'insert_category':
      case 'update_category':
      
        if (isset($_POST['link_categories_id'])) $link_categories_id = tep_db_prepare_input($_POST['link_categories_id']);
        $link_categories_sort_order = tep_db_prepare_input($_POST['link_categories_sort_order']);
        $link_categories_status = ((tep_db_prepare_input($_POST['link_categories_status']) == 'on') ? '1' : '0');

        $sql_data_array = array('link_categories_sort_order' => $link_categories_sort_order,
                                'link_categories_status' => $link_categories_status);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'link_categories_date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_LINK_CATEGORIES, $sql_data_array);

          $link_categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('link_categories_last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_LINK_CATEGORIES, $sql_data_array, 'update', "link_categories_id = '" . (int)$link_categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $link_categories_name_array = $_POST['link_categories_name'];
          $link_categories_description_array = $_POST['link_categories_description'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('link_categories_name' => tep_db_prepare_input($link_categories_name_array[$language_id]),
                                  'link_categories_description' => tep_db_prepare_input($link_categories_description_array[$language_id]));

          if ($action == 'insert_category') {
            $insert_sql_data = array('link_categories_id' => $link_categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_LINK_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_LINK_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "link_categories_id = '" . (int)$link_categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        if ($link_categories_image = new upload('link_categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_image = '" . tep_db_input($link_categories_image->filename) . "' where link_categories_id = '" . (int)$link_categories_id . "'");
        }

        tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $link_categories_id));
        break;
      case 'delete_category_confirm':
        if (isset($_POST['link_categories_id'])) {
          $link_categories_id = tep_db_prepare_input($_POST['link_categories_id']);

          $link_categories = tep_get_link_category_tree($link_categories_id, '', '0', '', true);
          $links = array();
          $links_delete = array();

          for ($i=0, $n=sizeof($link_categories); $i<$n; $i++) {
            $links_ids_query = tep_db_query("select links_id from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_categories[$i]['id'] . "'");

            while ($links_ids = tep_db_fetch_array($links_ids_query)) {
              $links[$links_ids['links_id']]['link_categories'][] = $link_categories[$i]['id'];
            }
          }

          reset($links);
          while (list($key, $value) = each($links)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['link_categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['link_categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where links_id = '" . (int)$key . "' and link_categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $links_delete[$key] = $key;
            }
          }

// removing link_categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($link_categories); $i<$n; $i++) {
            tep_remove_link_category($link_categories[$i]['id']);
          }

          reset($links_delete);
          while (list($key) = each($links_delete)) {
            tep_remove_link($key);
          }
        }

        tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath));
        break;
 
      case 'move_category_confirm':
        if (isset($_POST['link_categories_id']) && ($_POST['link_categories_id'] != $_POST['move_to_category_id'])) {
          $link_categories_id = tep_db_prepare_input($_POST['link_categories_id']);
          $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

          $path = explode('_', tep_get_generated_link_category_path_ids($new_parent_id));

          if (in_array($link_categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $link_categories_id));
          } else {
            tep_db_query("update " . TABLE_LINK_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', link_categories_last_modified = now() where link_categories_id = '" . (int)$link_categories_id . "'");

            tep_redirect(tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $new_parent_id . '&lID=' . $link_categories_id));
          }
        }

        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  //display error message if at least one category doesn't exist
  if (isset($_GET['no_categories']) && $_GET['no_categories'] == 'true')
  {
    $messageStack->add(ERROR_LINK_CATALOG_DOES_NOT_EXIST, 'error');
  }
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<div id="spiffycalendar" class="text"></div>
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
    <td width="100%" valign="top">

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_LINK_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_LINK_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_link_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          
          <?php
// BOC Sort Listing
          if (! tep_not_null($listing))
            $listing = 'title';
          switch ($listing) {              
              case "title":
              $order = "ld.link_categories_name";
              break;
              case "title-desc":
              $order = "ld.link_categories_name DESC";
              break;
              case "status":
              $order = "l.link_categories_status";
              break;
              case "status-desc":
              $order = "l.link_categories_status DESC";
              break;               
              default:
              $order = "l.link_categories_id DESC";
          } 
?>          
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">                  
                <td class="dataTableHeadingContent" align="center">
                  <?php echo (($listing=='title' or $listing=='title-desc') ? '<font color="FF0000"><b>' . TABLE_HEADING_LINK_CATEGORIES . '</b></font>' : '<b>'. TABLE_HEADING_LINK_CATEGORIES . '</b>'); ?><br>
                  <a href="<?php echo "$PHP_SELF?listing=title"; ?>"><?php echo ($listing=='title' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp;
                  <a href="<?php echo "$PHP_SELF?listing=title-desc"; ?>"><?php echo ($listing=='title-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>
                </td>                
                <td class="dataTableHeadingContent" align="center">
                  <?php echo (($listing=='status' or $listing=='status-desc') ? '<font color="FF0000"><b>' . TABLE_HEADING_STATUS . '</b></font>' : '<b>'. TABLE_HEADING_STATUS . '</b>'); ?><br>
                  <a href="<?php echo "$PHP_SELF?listing=status"; ?>"><?php echo ($listing=='status' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp;
                  <a href="<?php echo "$PHP_SELF?listing=status-desc"; ?>"><?php echo ($listing=='status-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>
                </td>                
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $link_categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = " and lcd.link_categories_name like '%" . $keywords . "%'";

      $link_categories_query = tep_db_query("select l.link_categories_id, ld.link_categories_name, ld.link_categories_name, l.link_categories_status, l.link_categories_image, l.parent_id, l.link_categories_sort_order, l.link_categories_date_added, l.link_categories_last_modified from " . TABLE_LINK_CATEGORIES . " l left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " ld on l.link_categories_id = ld.link_categories_id where ld.language_id = '" . (int)$languages_id . "' and ld.link_categories_name like '%" . tep_db_input($search) . "%' order by " . $order);
    } else {
      $link_categories_query = tep_db_query("select l.link_categories_id, ld.link_categories_name, ld.link_categories_name, l.link_categories_status, l.link_categories_image, l.parent_id, l.link_categories_sort_order, l.link_categories_date_added, l.link_categories_last_modified from " . TABLE_LINK_CATEGORIES . " l left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " ld on l.link_categories_id = ld.link_categories_id where l.parent_id = '" . (int)$current_category_id . "' and ld.language_id = '" . (int)$languages_id . "' order by " . $order);
    }
    while ($link_categories = tep_db_fetch_array($link_categories_query)) {
      $link_categories_count++;
      $rows++; 

// Get parent_id for sublink_categories if search
      if (isset($_GET['search'])) $cPath= $link_categories['parent_id'];

      if ((!isset($_GET['lID']) && !isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $link_categories['link_categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_link_category_count($link_categories['link_categories_id']));
        $category_links = array('links_count' => tep_links_in_category_count($link_categories['link_categories_id']));
   
        $cInfo_array = array_merge($link_categories, $category_childs, $category_links);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_path($link_categories['link_categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $link_categories['link_categories_id']) . '\'">' . "\n";
      }
     
      $catCount = tep_childs_in_link_category_count($link_categories['link_categories_id']);
  ?>
                <td class="dataTableContent">
                <?php 
                 if ($catCount)
                   echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_path($link_categories['link_categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.png', ICON_FOLDER) . '</a>&nbsp;<b>' . $link_categories['link_categories_name'] . '</b>&nbsp;( ' . $catCount . ' )';
                 else   
                   echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, tep_get_path($link_categories['link_categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.png', ICON_FOLDER) . '</a>&nbsp;<b>' . $link_categories['link_categories_name'] . '</b>&nbsp;'; 
                ?>
                </td>
                <td class="dataTableContent" align="center">
                <?php                   
                if ($link_categories['link_categories_status'] == STATUS_CATEGORIES_ENABLE_FLAG) {
                  echo tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;' . 
                  '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'action=setflag&flag=' . (int)STATUS_CATEGORIES_DISABLE_FLAG . '&lID=' . $link_categories['link_categories_id'] . '&lID=' . $link_categories['link_categories_id']) . '">' . 
                  tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) .'</a>';
                } else { 
                  echo 
                  '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'action=setflag&flag=' . (int)STATUS_CATEGORIES_ENABLE_FLAG . '&lID=' . $link_categories['link_categories_id'] . '&lID=' . $link_categories['link_categories_id']) . '">' . 
                  tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . 
                  tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10);
                }
                ?>            
                </td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $link_categories['link_categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }
    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_LINK_CATEGORIES . '&nbsp;' . $link_categories_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, $cPath_back . 'lID=' . $current_category_id) . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($_GET['search'])) echo '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.png', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&action=new_link') . '">' . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LINK_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_LINK_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_LINK_CATEGORIES_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']');
        }
        
        $link_category_description_inputs_string = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $link_category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
        }

        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('link_categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_SORT_ORDER . '<br>' . tep_draw_input_field('link_categories_sort_order', '', 'size="2"'));
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'on', true) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'off') . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LINK_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('link_categories', FILENAME_LINK_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']', tep_get_link_category_name($cInfo->link_categories_id, $languages[$i]['id']));
        }
        
        $link_category_description_inputs_string = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $link_category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', tep_get_link_category_description($cInfo->link_categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_LINK_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->link_categories_image, $cInfo->link_categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->link_categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_LINK_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('link_categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_LINK_CATEGORIES_SORT_ORDER . '<br>' . tep_draw_input_field('link_categories_sort_order', $cInfo->link_categories_sort_order, 'size="2"'));
        $contents[] = array('text' => '<br>' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'on', ($cInfo->link_categories_status == '1') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('link_categories_status', 'off', ($cInfo->link_categories_status == '0') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LINK_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('link_categories', FILENAME_LINK_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->link_categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->links_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_LINKS, $cInfo->links_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('link_categories', FILENAME_LINK_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_LINK_CATEGORIES_INTRO, $cInfo->link_categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->link_categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_link_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.png', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->link_categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_LINK_CATEGORIES, 'cPath=' . $cPath . '&lID=' . $cInfo->link_categories_id . '&action=move_category') . '">' . tep_image_button('button_move.png', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->link_categories_date_added));
            if (tep_not_null($cInfo->link_categories_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->link_categories_last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->link_categories_image, $cInfo->link_categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->link_categories_image);
            $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CATEGORY_DESCRIPTION . ' ' . $cInfo->link_categories_description);
            $contents[] = array('text' => '<br>' . TEXT_SUBLINK_CATEGORIES . ' ' . $cInfo->childs_count);
            $contents[] = array('text' => '<br>' . TEXT_SUBLINK_CATEGORIES_FULL_PATH . ' ' . tep_get_generated_link_category_path_names($cInfo->link_categories_id));
            $contents[] = array('text' => '<br>' . TEXT_SUBLINK_LINKS . ' ' . $cInfo->links_count);
          }
        } else { // create category/link info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');
          $contents[] = array('text' => TEXT_NO_CHILD_LINK_CATEGORIES);
        }
        break;
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
    </table>
 
    </td>
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
