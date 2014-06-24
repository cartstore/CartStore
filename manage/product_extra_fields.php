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
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a><?php echo HEADING_TITLE; ?></h1></div>
             
          <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-plus-square-o fa-5x pull-left"></i>
 On this screen you can create extra fields which can be selected on the add/edit product page then shown on the front end. This feature also interacts with product listing filters such as filter by color or filter by your customer defined extra field name. Clothing and other part type stores often use this feature.                         </div>
                      </div>
                  </div>   
              </div>    


            <?php echo tep_draw_form('add_field', FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=add', 'post'); ?>
            <table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                
              </tr>
              <tr>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field[name]', $field['name'], 'size=30', false, 'text', true);?> </td>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field[order]', $field['order'], 'size=5', false, 'text', true);?> </td>
                <td class="dataTableContent"><?php		 echo tep_draw_pull_down_menu('field[language]', $values, '0', '');?> </td>
              </tr>
              </form>
              
            </table>
            
        <?php echo tep_image_submit('button_add_field.png',IMAGE_ADD_FIELD)?> 
 
            
            
            <hr />
            
        

            <?php       echo tep_draw_form('extra_fields', FILENAME_PRODUCTS_EXTRA_FIELDS,'action=update','post');      ?>
            <?php echo $action_message; ?>
            <table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="20">&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
              </tr>
              <?php $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");while ($extra_fields = tep_db_fetch_array($products_extra_fields_query)) {?>
              <tr>
                <td width="20"><?php echo tep_draw_checkbox_field('mark['.$extra_fields['products_extra_fields_id'].']', 1) ?> </td>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][name]', $extra_fields['products_extra_fields_name'], 'size=30', false, 'text', true);?> </td>
                <td class="dataTableContent"><?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][order]', $extra_fields['products_extra_fields_order'], 'size=5', false, 'text', true);?> </td>
                <td class="dataTableContent"><?php echo tep_draw_pull_down_menu('field['.$extra_fields['products_extra_fields_id'].'][language]', $values, $extra_fields['languages_id'], ''); ?> </td>
                <td  class="dataTableContent"><?php          if ($extra_fields['products_extra_fields_status'] == '1') {            echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=0&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '"><i class="fa fa-check-circle-o text-success"></i> </a>';          }          else {            echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=1&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '"><i class="fa fa-times-circle-o text-danger"></i></a>';          }         ?>
                </td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="4"><p><?php echo tep_image_submit('button_update_fields.png',IMAGE_UPDATE_FIELDS)?> &nbsp;&nbsp; <?php echo tep_image_submit('button_remove_fields.png',IMAGE_REMOVE_FIELDS,'name="remove"')?> </p></td>
              </tr>
              </form>
              
            </table> 
 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
