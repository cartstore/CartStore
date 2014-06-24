<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $value_page = (isset($_GET['value_page']) && is_numeric($_GET['value_page'])) ? $_GET['value_page'] : 1;

  $products_options_id = (isset($_GET['options_id'])) ? $_GET['options_id'] : NULL;

  $page_info = 'value_page=' . $value_page . '&options_id=' . $products_options_id;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'add_product_option_values':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$value_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$option_id . "', '" . (int)$value_id . "')");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info));
        break;
      case 'update_value':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name) . "' where products_options_values_id = '" . tep_db_input($value_id) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . (int)$option_id . "'  where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($_GET['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info));
        break;
	  case 'update_sort':
      	$sorts = $_POST['products_options_values_id'];
      	for($i=0, $n=sizeof($sorts); $i<$n; $i++){
      		tep_db_query("update ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS." set sort_order=$i where products_options_values_id=".(int)tep_db_prepare_input($sorts[$i]));
      	}
      	break;
    }
  }
?>




<?php
require (DIR_WS_INCLUDES . 'header.php');
?>
<script language="javascript" src="includes/general.js"></script>


<!-- value //-->
<?php
  if ($action == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$_GET['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
        

<?php echo $values_values['products_options_values_name']; ?>

 


<?php
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$_GET['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
<table>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                 

<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                 

                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" align="right" colspan="3"><br />
                    
                    <p><?php echo tep_draw_button(IMAGE_BACK, 'triangle-1-w', tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info, 'NONSSL')); ?></p></td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" align="right" colspan="3"><br /><?php echo tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=delete_value&value_id=' . $_GET['value_id'] . '&' . $page_info, 'NONSSL'), 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info, 'NONSSL')); ?>&nbsp;</td>
                  </tr>
<?php
    }
?>
              	</table></td>
              </tr>
<?php
  } else {
?>
			 
<?php
	$sortable = ($action != 'update_option_value' AND tep_not_null($products_options_id));
	$sortable = false;
    if ($sortable) {
?>
	              <form name="update_sort" action="<?php echo tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=update_sort&' . $page_info, 'NONSSL') ?>" method="POST">
<?php
    }
?>
		  
<div class="page-header"><h1>
<?php echo HEADING_TITLE_VAL; ?></h1></div>


				        <?php if ($sortable) {
				          echo tep_draw_button('Update Sort', '', null, 'primary');
				        } 
				          echo '<p>'. tep_draw_button('Back', 'triangle-1-w', tep_href_link(FILENAME_PRODUCTS_OPTIONS), 'primary') .'</p>';
						?>
				     

<?php
	$where_options = '';
	if (tep_not_null($products_options_id))
		$where_options = 'and pov2po.products_options_id=' . $products_options_id . ' ';
    $values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.sort_order, pov2po.products_options_id, po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id join " . TABLE_PRODUCTS_OPTIONS . " po on pov2po.products_options_id=po.products_options_id where pov.language_id = '" . (int)$languages_id . "' " . $where_options . "order by po.products_options_name, pov2po.sort_order, pov.products_options_values_id";

    $values_split = new splitPageResults($value_page, MAX_ROW_LISTS_OPTIONS, $values, $values_query_numrows);

    echo $values_split->display_links($values_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $value_page, 'options_id=' . $products_options_id, 'value_page');
?>
                     
<table class="table table-hover">
                   
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" style="width: 25px">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                      <td class="dataTableHeadingContent" style="width: 30%">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                      <td class="dataTableHeadingContent" style="width: 40%">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                      <td class="dataTableHeadingContent" style="width: 150px">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                  

			        <tbody id="optionsList" class="sortable_list">
<?php
    $next_id = 1;
    $rows = 0;
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = $values_values['products_options_name']; //tep_options_name($values_values['products_options_id']);
      $values_name = $values_values['products_options_values_name'];
      $rows++;
?>
                    <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=update_value&' . $page_info, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input class="form-control" type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br />';
        }
?>
                      <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                      <td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select class="form-control" name="option_id">
<?php
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) { 
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        } 
?>
                      </select>&nbsp;</td>
                      <td class="smallText"><?php echo $inputs; ?></td>
                      <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_save.png', IMAGE_SAVE) . '<a class="btn btn-default" href="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, $page_info, 'NONSSL'); ?>">Cancel</a>&nbsp;</td>
				
<?php
        echo '</form>';
      } else {
?>
                      <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                      <td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
                      <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
                      <td align="center" class="smallText">&nbsp;<?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info, 'NONSSL') . '">Edit</a>&nbsp;<a class="btn btn-default" href="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info, 'NONSSL'); ?>">Delete</a>&nbsp;
					      <?php echo tep_draw_hidden_field('products_options_values_id[]', $values_values['products_options_values_id']).tep_draw_hidden_field('options_values_sort', $values_values['sort_order']); ?>
				      </td>
<?php
      }
?>
			        </tr>
<?php
      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    }
?>
			       
<?php 
    if ($sortable) {
?>
                  </form>
<?php 
    }
?>
               
<?php
  if ($action != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'action=add_product_option_values&' . $page_info, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<select class="form-control" name="option_id">
<?php
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }

      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input class="form-control" type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br />';
      }
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.png', IMAGE_INSERT) ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
            

<?php
    }
  }
?>
            </table>


<?php if ($sortable) { ?>
<script type="text/javascript">
$('#optionsList').sortable({
});
</script>
<?php } ?>
 <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>