<?php
/* $Id$
CartStore eCommerce Software, for The Next Generation
http://www.cartstore.com
Copyright (c) 2008 Adoovo Inc. USA
GNU General Public License Compatible
xsell.php
Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com>
Complete Recoding From Stephen Walker admin@snjcomputers.com
*/
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
   $currencies = new currencies();
  switch($_GET['action']){
          case 'update_cross' :
                if ($_POST['product']){
            foreach ($_POST['product'] as $temp_prod){
          tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$temp_prod.'" and products_id = "'.$_GET['add_related_product_ID'].'"');
            }
          }

                $sort_start_query = tep_db_query('select sort_order from ' . TABLE_PRODUCTS_XSELL . ' where products_id = "'.$_GET['add_related_product_ID'].'" order by sort_order desc limit 1');
        $sort_start = tep_db_fetch_array($sort_start_query);

            $sort = (($sort_start['sort_order'] > 0) ? $sort_start['sort_order'] : '0');
                if ($_POST['cross']){
        foreach ($_POST['cross'] as $temp){
                        $sort++;
                        $insert_array = array();
                        $insert_array = array('products_id' => $_GET['add_related_product_ID'],
                                                  'xsell_id' => $temp,
                                                  'sort_order' => $sort);
              tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
                }
                }
        $messageStack->add(CROSS_SELL_SUCCESS, 'success');
        //Cache
        $cachedir = DIR_FS_CACHE_XSELL . $_GET['add_related_product_ID'];
        if(is_dir($cachedir))
		 {
		  rdel($cachedir);
		 }
        //Fin Cache
           break;
          case 'update_sort' :
        foreach ($_POST as $key_a => $value_a){
         tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set sort_order = "' . $value_a . '" where xsell_id = "' . $key_a . '"');
            }
        $messageStack->add(SORT_CROSS_SELL_SUCCESS, 'success');
           break;
  }
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<script language="JavaScript1.2">

function cOn(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
 }
}

function cOnA(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
 }
}

function cOut(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
 }
}
</script>
<script language="javascript" src="includes/general.js"></script>


 


 


<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a><?php echo HEADING_TITLE; ?></h1></div>

        <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-random fa-5x pull-left"></i>
                              <p>On the front end on the product pages there is a module called “You might also like” that module pulls in other products by default but you can also special define products you want to cross sell with other products here on this screen.</p>

<p>Edit the product you want to cross sell with other products by clicking “Edit” </p>

<p>Then on the next screen check which other products you want to cross sell with it.</p>

<p>Click update when you have made your selections.     </p>                     </div>
                      </div>
                  </div>   
              </div>    


<?php
  if ($_GET['add_related_product_ID'] == ''){
?>
  <table id="xsell"  class="table table-hover table-condensed table-responsive">
   <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="5%"><?php echo TABLE_HEADING_PRODUCT_ID;?></td>
    <td class="dataTableHeadingContent" width="5%"><?php echo TABLE_HEADING_PRODUCT_MODEL;?></td>
    <td class="dataTableHeadingContent" width="40%"><?php echo TABLE_HEADING_PRODUCT_NAME;?></td>
    <td class="dataTableHeadingContent" width="40"><?php echo TABLE_HEADING_CURRENT_SELLS;?></td>
    <td class="dataTableHeadingContent" colspan="2" nowrap align="center"><?php echo TABLE_HEADING_UPDATE_SELLS;?></td>
   </tr>
<?php
    $products_query_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by pd.products_name asc';
    $products_split = new splitPageResults($_GET['page'], 10000, $products_query_raw, $products_query_numrows);
    $products_query = tep_db_query($products_query_raw);
    while ($products = tep_db_fetch_array($products_query)) {
?>
   <tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);"  onClick=document.location.href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">
    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top" nowrap="nowrap">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, p.products_id, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');
        $i=0;
    while ($products_cross = tep_db_fetch_array($products_cross_query)){
                $i++;
?>
         <tr>
          <td class="dataTableContentxsell">&nbsp;<?php echo $i . '.&nbsp;&nbsp;<b>' . $products_cross['products_model'] . '</b>&nbsp;' . $products_cross['products_name'];?>&nbsp;</td>
         </tr>
<?php
        }
    if ($i <= 0){
?>
         --
<?php
        }else{
?>
         
<?php
}
?>
    </table></td>
    <td class="dataTableContentxsell" valign="top">&nbsp;<a href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>"><?php echo TEXT_EDIT_SELLS;?></a>&nbsp;</td>
    <td class="dataTableContent" valign="top" align="center">&nbsp;<?php echo (($i > 0) ? '<a href="' . tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'sort=1&add_related_product_ID=' . $products['products_id'], 'NONSSL') .'">'.TEXT_SORT.'</a>&nbsp;' : '--')?></td>
   </tr>
<?php
        }
?>
   <tr>
    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, 10000, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, 10000, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table></td>
   </tr>
  </table>
<?php
}elseif($_GET['add_related_product_ID'] != '' && $_GET['sort'] == ''){
        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
        $products_name = tep_db_fetch_array($products_name_query);
?>
              <h3><?php echo TEXT_SETTING_SELLS.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></h3>

  <table border="0" cellspacing="0" cellpadding="0" class="table table-hover table-condensed table-responsive">
   <tr>
    <td><?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');?><table cellpadding="1" cellspacing="1" border="0">
        
           <tr>
            <td><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products_name['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?>
            
                <p> <?php echo tep_image_submit('button_preview.png', IMAGE_UPDATE) . ' <a class="btn btn-default" href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">Cancel</a>';?></p>
            
            </td>
        
           </tr>
      
    
     <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" width="75">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_CROSS_SELL_THIS;?>&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
         </tr>
<?php
    $products_query_raw = 'select p.products_id, p.products_model, p.products_image, p.products_price, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by pd.products_name asc';
    $products_split = new splitPageResults($_GET['page'], 10000, $products_query_raw, $products_query_numrows);
    $products_query = tep_db_query($products_query_raw);
    while ($products = tep_db_fetch_array($products_query)) {
                $xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
?>
         <tr class="prod_pg_box">
          <td class="dataTableContent">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo ((is_file(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>No Image<br>');?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?></label>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
         </tr>
<?php
    }
?>
        </table></form></td>
   </tr>
   <tr>
    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, 10000, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, 1000, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table></td>
   </tr>
  </table>
<?php
}elseif($_GET['add_related_product_ID'] != '' && $_GET['sort'] != ''){
        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
        $products_name = tep_db_fetch_array($products_name_query);
?>
  <table class="table table-hover table-condensed table-responsive">
   <tr>
    <td><?php echo tep_draw_form('update_sort', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_sort', 'post');?><table cellpadding="1" cellspacing="1" border="0">
         <tr>
          <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">
           <tr class="dataTableHeadingRow">
            <td valign="top" align="center" colspan="2"><h3><?php echo TEXT_SETTING_SELLS.': '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></h3></td>
           </tr>
           <tr class="dataTableHeadingRow">
            <td align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products_name['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
            <td align="right" valign="bottom"><?php echo tep_image_submit('button_update.png') . '<br><br><a class="btn btn-default" href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">Cancel</a>';?></td>
           </tr>
          </table></td>
         </tr>
     <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
          <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_SORT;?>&nbsp;</td>
         </tr>

<?php
    $products_query_raw = 'select p.products_id as products_id, p.products_price, p.products_image, p.products_model, pd.products_name, p.products_id, x.products_id as xproducts_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc';
    $products_split = new splitPageResults($_GET['page'], 10000, $products_query_raw, $products_query_numrows);
        $sort_order_drop_array = array();
        for($i=1;$i<=$products_query_numrows;$i++){
        $sort_order_drop_array[] = array('id' => $i, 'text' => $i);
        }
    $products_query = tep_db_query($products_query_raw);
 while ($products = tep_db_fetch_array($products_query)){
?>
         <tr class="prod_pg_box">
          <td class="dataTableContent">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo ((is_file(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>'.TEXT_NO_IMAGE.'<br>');?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
          <td class="dataTableContent">&nbsp;<?php echo tep_draw_pull_down_menu($products['products_id'], $sort_order_drop_array, $products['sort_order']);?>&nbsp;</td>
     </tr>
<?php
}
?>
    </table></form></td>
   </tr>
   <tr>
    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, 1000, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, 1000, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table></td>
   </tr>
  </table>
<?php
}
?>
 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>