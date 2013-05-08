<?php
/*
  $Id: modify_families.php,v 3.0 2003/09/01 20:07:51 blueline Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert_family':
      case 'update_family':
        if (isset($_POST['family_id'])) $family_id = tep_db_prepare_input($_POST['family_id']);

        if ($action == 'insert_family') {
          $insert_sql_data = array('family_id' => '',
                                   'family_name' => $_POST['family_name']);

          tep_db_perform(TABLE_FAMILIES, $insert_sql_data);

          $family_id = tep_db_insert_id();
        } elseif ($action == 'update_family') {
          $update_sql_data = array('family_name' => $_POST['family_name']);

          tep_db_perform(TABLE_FAMILIES, $update_sql_data, 'update', "family_id = '" . (int)$family_id . "'");
        }

          $family_name_array = $_POST['family_name'];

        tep_redirect(tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $family_id));
        break;
      case 'delete_family_confirm':
        if (isset($_POST['family_id'])) {
          $family_id = tep_db_prepare_input($_POST['family_id']);

          tep_remove_family($family_id);
        }
		
        tep_redirect(tep_href_link(FILENAME_MODIFY_FAMILIES, 'cPath=' . $cPath));
        break;
    }
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
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center">&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $families_count = 0;
    $rows = 0;
      $families_query = tep_db_query("select family_id, family_name from " . TABLE_FAMILIES . " order by family_name ASC");
	  
    while ($families = tep_db_fetch_array($families_query)) {
      $families_count++;
      $rows++;

// Get parent_id for subcategories if search

      if (((isset($_GET['f_Id']) && ($_GET['f_Id'] == $families['family_id']))) && !isset($f_Info) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($families['family_id']));
        $category_products = array('products_count' => tep_products_in_category_count($families['family_id']));

        $f_Info_array = array_merge($families, $category_childs, $category_products);
        $f_Info = new objectInfo($f_Info_array);
      }

      if (isset($f_Info) && is_object($f_Info) && ($families['family_id'] == $f_Info->family_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";// onclick="document.location.href=\'' . tep_href_link(FILENAME_MODIFY_FAMILIES, tep_get_path($families['family_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $families['family_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<b>' . $families['family_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($f_Info) && is_object($f_Info) && ($families['family_id'] == $f_Info->family_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $families['family_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $families_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $current_category_id) . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($_GET['search'])) echo '<a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'cPath=' . $cPath . '&action=new_family') . '">' . tep_image_button('button_new_category.png', IMAGE_NEW_FAMILY) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_family':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_FAMILY . '</b>');

        $contents = array('form' => tep_draw_form('families', FILENAME_MODIFY_FAMILIES, 'action=insert_family', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_FAMILY_INTRO);

        $category_inputs_string = '';
        $category_inputs_string .= '<br>' . tep_draw_input_field('family_name');

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_family':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_FAMILY . '</b>');

        $contents = array('form' => tep_draw_form('families', FILENAME_MODIFY_FAMILIES, 'action=update_family', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('family_id', $f_Info->family_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $category_inputs_string .= '<br>' . tep_draw_input_field('family_name', tep_get_family_name($f_Info->family_id));

        $contents[] = array('text' => '<br>' . TEXT_EDIT_FAMILIES_NAME . $category_inputs_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, '&f_Id=' . $f_Info->family_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_family':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FAMILY . '</b>');

        $contents = array('form' => tep_draw_form('families', FILENAME_MODIFY_FAMILIES, 'action=delete_family_confirm') . tep_draw_hidden_field('family_id', $f_Info->family_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $f_Info->family_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'cPath=' . $cPath . '&f_Id=' . $f_Info->family_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($f_Info) && is_object($f_Info)) {
            $heading[] = array('text' => '<b>' . $f_Info->family_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $f_Info->family_id . '&action=edit_family') . '">' . tep_image_button('button_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=' . $f_Info->family_id . '&action=delete_family') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a>');
          }
        } else {
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '          <td valign="top"  width="220px">' . "\n";

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