<?php
  require('includes/application_top.php');
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action)) {
      switch ($action) {
          case 'insert':
          case 'save':
              if (isset($_GET['mID']))
                  $manufacturers_id = tep_db_prepare_input($_GET['mID']);
              $manufacturers_name = tep_db_prepare_input($_POST['manufacturers_name']);
              $sql_data_array = array('manufacturers_name' => $manufacturers_name);
              if ($action == 'insert') {
                  $insert_sql_data = array('date_added' => 'now()');
                  $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                  tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
                  $manufacturers_id = tep_db_insert_id();
              } elseif ($action == 'save') {
                  $update_sql_data = array('last_modified' => 'now()');
                  $sql_data_array = array_merge($sql_data_array, $update_sql_data);
                  tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "'");
              }
              if($manufacturers_image['name']=='' && is_file(DIR_FS_CATALOG_IMAGES.$_POST['existing_manufacturers_image'])){
                      tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $_POST['existing_manufacturers_image'] . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
              }elseif ($manufacturers_image = new upload('manufacturers_image', DIR_FS_CATALOG_IMAGES)) {
                  tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image->filename . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
              }
              if($_POST['delete_manufacturers_image']=='on' && $action=='save'){
                  tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
                    unlink(DIR_FS_CATALOG_IMAGES.$_POST['existing_manufacturers_image']);
              }
              $languages = tep_get_languages();
              for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $manufacturers_url_array = $_POST['manufacturers_url'];
                  $language_id = $languages[$i]['id'];
                  $sql_data_array = array('manufacturers_url' => tep_db_prepare_input($manufacturers_url_array[$language_id]));
                  if ($action == 'insert') {
                      $insert_sql_data = array('manufacturers_id' => $manufacturers_id, 'languages_id' => $language_id);
                      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                      tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array);
                  } elseif ($action == 'save') {
                      tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "' and languages_id = '" . (int)$language_id . "'");
                  }
              }
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('manufacturers');
              }
              tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'mID=' . $manufacturers_id));
              break;
          case 'deleteconfirm':
              $manufacturers_id = tep_db_prepare_input($_GET['mID']);
              if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
                  $manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
                  $manufacturer = tep_db_fetch_array($manufacturer_query);
                  $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];
                  if (file_exists($image_location))
                      @unlink($image_location);
              }
              tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
              tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
              if (isset($_POST['delete_products']) && ($_POST['delete_products'] == 'on')) {
                  $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
                  while ($products = tep_db_fetch_array($products_query)) {
                      tep_remove_product($products['products_id']);
                  }
              } else {
                  tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
              }
              if (USE_CACHE == 'true') {
                  tep_reset_cache_block('manufacturers');
              }
              tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page']));
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
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php
  echo TABLE_HEADING_MANUFACTURERS;
?></td>
                      <td class="dataTableHeadingContent" align="right"><?php
  echo TABLE_HEADING_ACTION;
?>&nbsp;</td>
                    </tr>
                    <?php
  $manufacturers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
  $manufacturers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
  $manufacturers_query = tep_db_query($manufacturers_query_raw);
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
          $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
          $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);
          $mInfo_array = array_merge($manufacturers, $manufacturer_products);
          $mInfo = new objectInfo($mInfo_array);
      }
      if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
          echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
      } else {
          echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
      }
?>
                    <td class="dataTableContent"><?php
      echo $manufacturers['manufacturers_name'];
?></td>
                      <td class="dataTableContent" align="right"><?php
      if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
          echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png');
      } else {
          echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
      }
?>
                        &nbsp;</td>
                    </tr>
                    <?php
  }
?>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php
  echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS);
?></td>
                            <td class="smallText" align="right"><?php
  echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']);
?></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
  if (empty($action)) {
?>
                    <tr>
                      <td align="right" colspan="2" class="smallText"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=new') . '">' .  IMAGE_INSERT . '</a>';
?></td>
                    </tr>
                    <?php
  }
?>
                  </table></td>
                <?php
  $heading = array();
  $contents = array();
  switch ($action) {
      case 'new':
          $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>');
          $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
          $contents[] = array('text' => TEXT_NEW_INTRO);
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name'));
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . tep_draw_file_field('manufacturers_image'));
          $manufacturer_inputs_string = '';
          $languages = tep_get_languages();
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
              $manufacturer_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']');
          }
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' .  IMAGE_CANCEL . '</a>');
          break;
      case 'edit':
          $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');
          $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
          $contents[] = array('text' => TEXT_EDIT_INTRO);
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name', $mInfo->manufacturers_name));
          if($mInfo->manufacturers_image !='')$deleteBox='<br/><input type="checkbox" name="delete_manufacturers_image" value="on"/> Delete Manufacturers Image';else $deleteBox = '';
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' .tep_draw_hidden_field('existing_manufacturers_image',$mInfo->manufacturers_image) . tep_draw_file_field('manufacturers_image') . '<br>' . $mInfo->manufacturers_image.$deleteBox);

          $manufacturer_inputs_string = '';
          $languages = tep_get_languages();
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
              $manufacturer_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', tep_get_manufacturer_url($mInfo->manufacturers_id, $languages[$i]['id']));
          }
          $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . IMAGE_CANCEL . '</a>');
          break;
      case 'delete':
          $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');
          $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm'));
          $contents[] = array('text' => TEXT_DELETE_INTRO);
          $contents[] = array('text' => '<br><b>' . $mInfo->manufacturers_name . '</b>');
          $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);
          if ($mInfo->products_count > 0) {
              $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
              $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
          }
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' .  IMAGE_CANCEL . '</a>');
          break;
      default:
          if (isset($mInfo) && is_object($mInfo)) {
              $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');
              $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' .  IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' .  IMAGE_DELETE . '</a>');
              $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
              if (tep_not_null($mInfo->last_modified))
                  $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
              $contents[] = array('text' => '<br>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));
              $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
          }
          break;
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
