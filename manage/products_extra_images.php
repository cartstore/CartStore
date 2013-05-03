<?php
/*
  $Id: manufacturers.php,v 1.55 2003/06/29 22:50:52 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/


  require('includes/application_top.php');
  
// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

// populate $products_array with available product model names
$products_array = array(array('id' => '', 'text' => TEXT_NONE));
$products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id order by p.products_model");
while ($products = tep_db_fetch_array($products_query)) {
	$products_array[] = array('id' => $products['products_id'],
									'text' => $products['products_name']);
//	$i++;
}
  
/* file name is passed in super global array $_FILES
$_FILES['userfile']['name']
The original name of the file on the client machine. 

$_FILES['userfile']['type']
The mime type of the file, if the browser provided this information. An example would be "image/gif". 

$_FILES['userfile']['size']
The size, in bytes, of the uploaded file. 

$_FILES['userfile']['tmp_name']
The temporary filename of the file in which the uploaded file was stored on the server. 

$_FILES['userfile']['error']

UPLOAD_ERR_OK No error occurred. 
UPLOAD_ERR_INI_SIZE The file was larger than PHP will accept, based on the upload_max_filesize configuration directive. 
UPLOAD_ERR_FORM_SIZE The file was larger than the maximum value specified by the MAX_FILE_SIZE hidden form element. 
UPLOAD_ERR_PARTIAL The file upload was cancelled before it was complete. 
UPLOAD_ERR_NOFILE The form was submitted, but no file was uploaded 
*/

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':	  
  				$sql_data_array = array('products_id' => tep_db_prepare_input($_POST['products_id']));
				if (isset($_FILES['pei_file']) && $_FILES['pei_file']['name'] != '') {
						//upload files to the images folder on the server; if SPECIAL_IMAGE_PATH is filled then it will upload the file to that subfolder path. NOTE: make sure that subfolder path exists on the server e.g. if special_IMAGE_PATH is set to "subfolderA/" then the image will be uploaded to "images/subfolderA/"
						$extra_image = new upload('pei_file', DIR_FS_CATALOG_IMAGES.$_POST['SPECIAL_IMAGE_PATH']);
						$sql_data_array = array_merge($sql_data_array, array('products_extra_image' => tep_db_prepare_input($_POST['SPECIAL_IMAGE_PATH'].$_FILES['pei_file']['name'])));
				}
				else {//OPTION 2 Already uploaded the file and want to update the image using path to the image file on the server from the "images/" folder e.g. if image.jpg file is in "subfolderA" then the path is "subfolderA/image.jpg"
						$sql_data_array = array_merge($sql_data_array, array('products_extra_image' => tep_db_prepare_input($_POST['products_extra_image'])));				
				}
				if ($action == 'save') {
					tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array, 'update', 'products_extra_images_id=' . tep_db_input($_GET['pId']));
				} else {
					tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array, 'insert');
				}
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $_GET['page'] . '&pId=' . $_GET['pId']));
				break;
	  case 'delete':
				$sql_data_array = array('products_extra_images_id' => tep_db_prepare_input($_GET['pId']));
				tep_db_query("DELETE FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_extra_images_id=" . $_GET['pId']);
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $_GET['page']));
			break;
    }  
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <TD class="dataTableHeadingContent" width=20%>Products Name</TD>
                <TD class="dataTableHeadingContent" width=50%><?php echo TABLE_HEADING_PRODUCTS_IMAGE; ?></TD>
                <TD class="dataTableHeadingContent" align="right" width=10%> <?php echo TABLE_HEADING_ACTION; ?></TD>
              </tr>
<?php
  $page = $_GET['page'];
  if (!$page) $page = 1;
  $pId = $_GET['pId'];
  if (!$pId) unset($pId);
  $products_extra_images_query_raw = "select pei.products_extra_image, pei.products_extra_images_id, pei.products_id, p.products_name from " . TABLE_PRODUCTS_EXTRA_IMAGES . " pei left join " . TABLE_PRODUCTS_DESCRIPTION . " p ON pei.products_id = p.products_id order by p.products_name";
  $products_extra_images_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_extra_images_query_raw, $products_extra_images_query_numrows);
  $products_extra_images_query = tep_db_query($products_extra_images_query_raw);
  while ($products_extra_image = tep_db_fetch_array($products_extra_images_query)) {
  	if (!isset($pId))
	  $pId = $products_extra_image['products_extra_images_id'];
    if ($products_extra_image['products_extra_images_id'] == $pId) {
	  $pInfo = new objectInfo($products_extra_image);
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $products_extra_image['products_extra_images_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $products_extra_image['products_extra_images_id']) . '\'">' . "\n";
    }
?>
				<td class="dataTableContent"><?php echo $products_extra_image['products_name']; ?></td>
				<td class="dataTableContent"><?php echo $products_extra_image['products_extra_image']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($products_extra_image['products_extra_images_id'] == $pId) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pID=' . $pId) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>				
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_extra_images_split->display_count($products_extra_images_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_PAGING_FORMAT); ?></td>
                    <td class="smallText" align="right"><?php echo $products_extra_images_split->display_links($products_extra_images_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page);  ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pInfo->products_extra_images_id . '&action=new') . '">' .  IMAGE_INSERT . '</a>'; ?></td>
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

      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_EXTRA_IMAGE . '</b>');

      $contents = array('form' => tep_draw_form('form_pei_insert', FILENAME_PRODUCTS_EXTRA_IMAGES , 'action=insert', 'POST', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_NAME . '<br>');
      $contents[] = array('text' => tep_draw_pull_down_menu('products_id', $products_array, $products_extra_image['products_id']));
          $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_IMAGE . '<br>');
	  $contents[] = array('text' => TEXT_SPECIAL_IMAGE_PATH . '<br>');
	  $contents[] = array('text' =>  tep_draw_input_field('SPECIAL_IMAGE_PATH','','size=50 value=""').'<br>' );
	  $contents[] = array('text' => tep_draw_file_field('pei_file'));
	  $contents[] = array('text' => '<br>' . UPDATE_EXTRA_IMAGE_OPTION );
	  $contents[] = array('text' =>  '<br>' .tep_draw_input_field('products_extra_image','','size=50 value=""') );
	  $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pId) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_EXTRA_IMAGE . '</b>');

      $contents = array('form' => tep_draw_form('form_pei_edit', FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pInfo->products_extra_images_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_NAME . '<br>');
      $contents[] = array('text' => tep_draw_pull_down_menu('products_id', $products_array, $pInfo->products_id));
          $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_IMAGE . '<br>');
	  $contents[] = array('text' => TEXT_SPECIAL_IMAGE_PATH . '<br>');
	  $contents[] = array('text' =>  tep_draw_input_field('SPECIAL_IMAGE_PATH','','size=50 value=""').'<br>' );
	  $contents[] = array('text' => tep_draw_file_field('pei_file'));
	  $contents[] = array('text' => '<br>' . UPDATE_EXTRA_IMAGE_OPTION );
	  $contents[] = array('text' =>  '<br>' .tep_draw_input_field('products_extra_image',$pInfo -> products_extra_image,'size=50 value=' . $pInfo -> products_extra_image) );
	  $contents[] = array('text' => '<br>' . $pInfo -> products_extra_image );
      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pInfo->products_extra_images_id) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('products_extra_image', FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pInfo->products_extra_images_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $pInfo->products_model . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pInfo->products_extra_images_id) . '">' .  IMAGE_CANCEL . '</a>');
      break; 
    default:
      if (isset($pId)) {
        $heading[] = array('text' => '<b>' . $pInfo -> products_model . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pId . '&action=edit') . '">' . IMAGE_EDIT . '</a><a class="button" href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_IMAGES, 'page=' . $page . '&pId=' . $pId . '&action=delete') . '">' .  IMAGE_DELETE . '</a>');
        $contents[] = array('text' => '<br>' . tep_info_image($pInfo -> products_extra_image, $pInfo -> products_model));
		$contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . (count($products_array)-1));
      }
      break;
  } // end of switch

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
