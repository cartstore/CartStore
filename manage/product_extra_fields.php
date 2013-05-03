<?php
/*  $Id: product_extra_field.php,v 2.0 2004/11/09 22:50:52 ChBu Exp $  CartStore eCommerce Software, for The Next Generation  http://www.cartstore.com  Copyright (c) 2008 Adoovo Inc. USA  GNU General Public License Compatible  *   * v2.0: added languages support*/
require('includes/application_top.php');
$action = (isset($_GET['action']) ? $_GET['action'] : '');
// Has "Remove" button been pressed?
if (isset($_POST['remove_x']) || isset($_POST['remove_y'])) $action='remove';if (tep_not_null($action)){  
	switch ($action) 
	{    
	case 'setflag':      
		$sql_data_array = array('products_extra_fields_status' => tep_db_prepare_input($_GET['flag']));
		
		tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $_GET['id']);      
		tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));		  
		break;    
	case 'add':      
		$sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($_POST['field']['name']),	                          'languages_id' => tep_db_prepare_input ($_POST['field']['language']),							  'products_extra_fields_order' => tep_db_prepare_input($_POST['field']['order']));
		
		tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'insert');
		tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
		break;
		
	case 'update':
	
		foreach ($_POST['field'] as $key=>$val) {
			$sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($val['name']),		                        'languages_id' =>  tep_db_prepare_input($val['language']),			   					'products_extra_fields_order' => tep_db_prepare_input($val['order']));
			
			tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $key);      
		
		}      
		
		tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));      
		break;    
	case 'remove':      
	//print_r($_POST['mark']);      
		if ($_POST['mark']) 
		{        
			foreach ($_POST['mark'] as $key=>$val) 
			{          
				tep_db_query("DELETE FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));          
				tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));
			
			}        
		tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));      
		}      
	break;  
	}
}
// Put languages information into an array for drop-down boxes  
$languages=tep_get_languages();  
$values[0]=array ('id' =>'0', 'text' => TEXT_ALL_LANGUAGES);  
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {	
	$values[$i+1]=array ('id' =>$languages[$i]['id'], 'text' =>$languages[$i]['name']);  
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
              </tr>
            </table></td>
        </tr>
        <tr>
          <td width="100%"><!--      <div style="font-family: verdana; font-weight: bold; font-size: 17px; margin-bottom: 8px; color: #727272;">       <?php echo SUBHEADING_TITLE; ?>      </div>      -->
            <br />
            <?php //echo tep_draw_form("add_field", FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=add', 'post'); ?>
            <?php echo tep_draw_form('add_field', FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=add', 'post'); ?>
            <table border="0" width="400" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field[name]', $field['name'], 'size=30', false, 'text', true);?> </td>
                <td class="dataTableContent" align="center"><?php echo tep_draw_input_field('field[order]', $field['order'], 'size=5', false, 'text', true);?> </td>
                <td class="dataTableContent" align="center"><?php		 echo tep_draw_pull_down_menu('field[language]', $values, '0', '');?>
                </td>
                <td class="dataTableHeadingContent" align="right"><?php echo tep_image_submit('button_add_field.png',IMAGE_ADD_FIELD)?> </td>
              </tr>
              </form>
              
            </table>
            <hr />
            <br>
            <?php       echo tep_draw_form('extra_fields', FILENAME_PRODUCTS_EXTRA_FIELDS,'action=update','post');      ?>
            <?php echo $action_message; ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="20">&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
              </tr>
              <?php $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");while ($extra_fields = tep_db_fetch_array($products_extra_fields_query)) {?>
              <tr>
                <td width="20"><?php echo tep_draw_checkbox_field('mark['.$extra_fields['products_extra_fields_id'].']', 1) ?> </td>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][name]', $extra_fields['products_extra_fields_name'], 'size=30', false, 'text', true);?> </td>
                <td class="dataTableContent" align="center"><?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][order]', $extra_fields['products_extra_fields_order'], 'size=5', false, 'text', true);?> </td>
                <td class="dataTableContent" align="center"><?php echo tep_draw_pull_down_menu('field['.$extra_fields['products_extra_fields_id'].'][language]', $values, $extra_fields['languages_id'], ''); ?> </td>
                <td  class="dataTableContent" align="center"><?php          if ($extra_fields['products_extra_fields_status'] == '1') {            echo tep_image(DIR_WS_IMAGES . 'icon_status_green.png', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=0&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.png', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';          }          else {            echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=1&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.png', IMAGE_ICON_STATUS_RED, 10, 10);          }         ?>
                </td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="4"><?php echo tep_image_submit('button_update_fields.png',IMAGE_UPDATE_FIELDS)?> &nbsp;&nbsp; <?php echo tep_image_submit('button_remove_fields.png',IMAGE_REMOVE_FIELDS,'name="remove"')?> </td>
              </tr>
              </form>
              
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
