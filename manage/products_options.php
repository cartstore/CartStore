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

  $option_page = (isset($_GET['option_page']) && is_numeric($_GET['option_page'])) ? $_GET['option_page'] : 1;

  $page_info = 'option_page=' . $option_page;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($_POST['products_options_id']);
        $option_name_array = $_POST['option_name'];

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info));
        break;
      case 'update_option_name':
        $option_name_array = $_POST['option_name'];
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($option_name) . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($_GET['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info));
        break;
	  case 'update_sort':
      	$sorts = $_POST['products_sort_id'];
      	for($i=0, $n=sizeof($sorts); $i<$n; $i++){
      		tep_db_query("update ".TABLE_PRODUCTS_OPTIONS . " set products_options_sort_order=$i where products_options_id=".(int)tep_db_prepare_input($sorts[$i]));
      	}
      	break;
    }
  }
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>



<!-- options //-->
<?php
  if ($action == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$_GET['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
         

<?php echo $options_values['products_options_name']; ?>

              
               <table class="table table-hover table-condensed table-responsive">
               

<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                 
<?php
      $rows = 0;
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  
                  <tr>
                    <td colspan="3" class="main"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="smallText"><br /><?php echo tep_draw_button(IMAGE_BACK, 'triangle-1-w', tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info, 'NONSSL')); ?>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" align="right" colspan="3"><br /><?php echo tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=delete_option&option_id=' . $_GET['option_id'] . '&' . $page_info, 'NONSSL'), 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info, 'NONSSL')); ?>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table> 
<?php
  } else {
?>
		 
<?php
	$sortable = ($action != 'update_option');
	$sortable = false;
    if ($sortable) {
?>
	              <form name="update_sort" action="<?php echo tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=update_sort&' . $page_info, 'NONSSL') ?>" method="POST">
<?php
    }
?>
	<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
<?php echo HEADING_TITLE_OPT; ?></h1></div>
           
             <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-pencil-square-o fa-5x pull-left"></i>
The option name is the select lists label. The options values are the actual select list options that show on the product page in the front of the store. This is just another interface for editing product options in cartstore, there are several other areas as well. The easiest being on the add/edit product page however that portion doesn't support some more of the extended features of cartstores attribute system which is very robust.

The cartstore attribute system supports man different settings which may require professional assistance in setting up. For clothing stores for example they often require quantity tracking per option, for example they may have 10 in stock of XL Red Budweiser shirts and 14 SM Blue  Budweiser shirts. This setup will require alteration from cartstores default attribute setup.

Clothing stores often require images per attributes as part of customization to the product page.

Some stores require downloads beyond the default download features of cart store, this is on the advanced attribute edit page. 

CartStore can also track stock per option values.

For extended attribute functionality please request service from cartstore.                          </div>
                      </div>
                  </div>   
              </div>    
              <p><b>Note:</b> This is the basic attribute editor. if you need multiple option types or file downloads  you must use the <a href="products_attributes.php"><u>advanced option editor.</u></a></p>


				        <?php if ($sortable) {
				          echo tep_draw_button('Update Sort', '', null, 'primary');
				        } ?>
				    

<?php
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_sort_order, products_options_id";
    $options_split = new splitPageResults($option_page, 100, $options, $options_query_numrows);

    echo $options_split->display_links($options_query_numrows, 100, MAX_DISPLAY_PAGE_LINKS, $option_page, '', 'option_page');
?>
               

<table class="table table-hover">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" style="width: 10px; text-align: center;">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" style="padding-right: 100px">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
             


<?php
      if ($sortable) {
?>      	
      	 <tbody id="optionsList" class="sortable_list">
<?php }      	 	
    $next_id = 1;
    $rows = 0;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=update_option_name&' . $page_info, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $option_name = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input class="form-control" type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br />';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_OPTIONS, $page_info, 'NONSSL')); ?>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
?>
                <td align="center" class="smallText"><?php echo tep_draw_hidden_field('products_sort_id[]', $options_values['products_options_id']); ?>&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<a href="<?php echo tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'options_id=' . $options_values["products_options_id"]); ?>"><?php echo $options_values["products_options_name"]; ?></a>&nbsp;</td>
                <td align="right" class="smallText" style="150px; padding-right: 10px">&nbsp;<?php echo tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info, 'NONSSL')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=delete_product_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info, 'NONSSL')) . ' ' . tep_draw_button('Option\'s Values','',tep_href_link(FILENAME_PRODUCTS_OPTIONS_VALUES, 'options_id=' . $options_values["products_options_id"])); ?>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
      if (($sortable)) {
?>      	
     
<?php } ?>
              
 
<?php 
    if ($sortable) {
?>
                  </form>
<?php 
    }
?>
                
<?php
    if ($action != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_OPTIONS, 'action=add_product_options&' . $page_info, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" class="form-control" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;<br />';
      }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button(IMAGE_INSERT, 'plus'); ?>&nbsp;</td>
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
  //containment: 'parent',
  forcePlaceholderSize: true,
  helper: 'clone'
});
</script>
<?php } ?>
 <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>