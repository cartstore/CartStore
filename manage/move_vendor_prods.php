<?php
/*
   $ID: move_vendor_prods.php (for use with MVS)
   by Craig Garrison Sr, BluCollar Sales
  for MVS V1.0 2006/04/01 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

?>

      <?php if ($action == 'update') {
     $count_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where vendors_id = '" . (int)$delete_vendors_id . "'");
     while ($count_products = tep_db_fetch_array($count_products_query)) {
         $num_products = $count_products['total'];
       }
                           $update_query  = "update " . TABLE_PRODUCTS . " SET vendors_id = '" . $new_vendors_id . "' where vendors_id = '" . $delete_vendors_id . "';"
;
                 @$update_result = tep_db_query($update_query);

     }
      ?>



<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>

<?php echo HEADING_TITLE; ?>

</h1></div>


              <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-file-text fa-5x pull-left"></i>
Help for this section is not yet available.                          </div>
                      </div>
                  </div>   
              </div>    
      <?php if ($action == 'update') {
     $vendor_name_deleted = tep_db_query("select vendors_name from " . TABLE_VENDORS . " where vendors_id = '" . $delete_vendors_id . "'");
    while ($vendor_deleted = tep_db_fetch_array($vendor_name_deleted)) {
      $deleted_vendor = $vendor_deleted['vendors_name'];
      }
     $vendor_name_moved = tep_db_query("select vendors_name from " . TABLE_VENDORS . " where vendors_id = '" . $new_vendors_id . "'");
    while ($vendor_moved = tep_db_fetch_array($vendor_name_moved)) {
      $moved_vendor = $vendor_moved['vendors_name'];
      }
      if ($update_result) {

      ?>
 
      <?php
     // echo '<br><b>The new Vendor\'s name:  ' . $moved_vendor;
      echo '<b>' . $num_products . '</b> products were moved from <b>' . $deleted_vendor . '</b> to <b>' . $moved_vendor . '</b>.<br> You can Go <a href="' . tep_href_link(FILENAME_MOVE_VENDORS) . '"><b>Back and start</b></a> again OR Go <a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Back To Vendors List</b></a>';
   } else {  ?>
 
 <?php  echo '<br><b>NO</b> products were moved from <b>' . $deleted_vendor . '</b> to <b>' . $moved_vendor . '</b>.<br> You should Go <a href="' . tep_href_link(FILENAME_MOVE_VENDORS) . '"><b>Back and start</b></a> over OR Go <a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Back To Vendors List</b></a>';
   }

      ?>
 
     <?php } elseif ($action == '') { ?>
   <?php echo '<p><a class="btn btn-defualt" href="' . tep_href_link(FILENAME_VENDORS) . '"> Back</a> </p>';?>


<?php echo '<h3>Select the vendors you plan to work with.</h3>'; ?>
    

<?php echo '<p class="text-danger"><b>This action is not easily reversible, and clicking the update button will perform this action immediately, there is no turning back.</b></p>'; ?>
  


            <?php
           echo tep_draw_form('move_vendor_form', FILENAME_MOVE_VENDORS, tep_get_all_get_params(array('action')) . ('action=update'), 'post');

    $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
    $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
    while ($vendors = tep_db_fetch_array($vendors_query)) {
      $vendors_array[] = array('id' => $vendors['vendors_id'],
                                     'text' => $vendors['vendors_name']);
    }
          ?>
             <div class="form-group"><label>
<?php echo TEXT_VENDOR_CHOOSE_MOVE . '</label>'; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('delete_vendors_id', $vendors_array);?>
</div>

  <div class="form-group"><label>
<?php echo TEXT_VENDOR_CHOOSE_MOVE_TO . '</label> '; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('new_vendors_id', $vendors_array);?>

</div>
<p>
<?php echo tep_image_submit('button_update.png', 'SUBMIT') . ' <a class="btn btn-default" href="' . tep_href_link(FILENAME_MOVE_VENDORS, tep_get_all_get_params(array('action'))) .'">' . IMAGE_CANCEL . '</a>';  ?>
</p>
                 <?php } ?>
           

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>