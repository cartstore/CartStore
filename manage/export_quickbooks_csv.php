<?php
/*
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $start = isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
  $end = isset($_GET['end']) ? $_GET['end'] : date("Y-m-d");
  $status = isset($_GET['status']) ? (int)$_GET['status'] : null;

  if (isset($_GET['generate'])) {
    generatecsv($start, $end, $status, $submitted);
  }


  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
 <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="includes/general.js"></script>
 
 <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
                    

Export to QuickBooks<br>
<small>
<?php echo strftime(DATE_FORMAT_LONG); ?></small></h1></div>

      <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa-database fa-5x pull-left"></i>
This screen allows you to export order information to a csv file which then may be imported into other software applications specifically Quickbooks.                                 </div>
                      </div>
                  </div>   
              </div>    

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#startdate, #enddate").datepicker({dateFormat: 'yy-mm-dd'});
  });
</script>

		<form method="GET" action="<?php echo $PHP_SELF; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
 <div class="form-group"><label>
<?php echo "Start Date #:"; ?></label>
<input type="text" class="form-control" name="start" size="12" id="startdate" value="<?php echo $start; ?>">
      </div> 
<div class="form-group"><label>
<?php echo "End Date #:" ; ?></label>
<input type="text" class="form-control" name="end" size="12" id="enddate" value="<?php echo $end; ?>">
        </div>
        <div class="form-group"><label> 
<?php echo "Order Status:"; ?></label>

            <?php
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
?>
      
<?php echo tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => 'All Orders')), $orders_statuses), $status); ?>
       
</div>
 
     <p>
<input type="submit" name="generate" class="btn btn-default" value="<?php echo "Generate"; ?>"></p>
</form>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');

// generates csv file from $start date to $end date, inclusive
function generatecsv($start, $end, $status, $submitted) {
    global $messageStack;
  $sql = "SELECT
 p.pSortOrder,
 o.quickbooksid,
 op.products_quantity,
 op.orders_id,
 o.date_purchased,
 op.products_id,
 o.orders_status,
 c.customers_dba
FROM
 " . TABLE_ORDERS_PRODUCTS . " op,
 " . TABLE_ORDERS . " o,
 " . TABLE_PRODUCTS . " p,
 " . TABLE_CUSTOMERS . " c
WHERE
  o.orders_id = op.orders_id
 AND
  p.products_id = op.products_id
 AND
  o.date_purchased >= '" . $start . "'
 AND
  o.date_purchased < DATE_ADD('" . $end . "',INTERVAL 1 DAY)
 AND
  c.customers_id = o.customers_id ";
if (!empty($status))
  $sql .= " AND o.orders_status = " . (int)$status;
$sql .= "
 ORDER BY
  c.customers_dba,
  op.orders_id,
  p.pSortOrder";

 $results_query = tep_db_query($sql);
 if (tep_db_num_rows($results_query) > 0){
  $csv = "pSortOrder,customers_dba,quickbooksid,products_quantity,orders_id,date_purchased\n";
  while ($row = tep_db_fetch_array($results_query)){
    $csv .= '"' . implode('","',array($row['pSortOrder'],$row['customers_dba'],$row['quickbooksid'],$row['products_quantity'],$row['orders_id'],$row['date_purchased'])) . '"' . "\n";
  }
  $filesize = strlen($csv);
  header("Content-type: text/csv");
  header('Content-disposition: attachment;filename=' . STORE_NAME . '-QuickBooks-Export-' . date("Ymd") . '.csv');
  header("Content-length: $filesize");
  print $csv;


  exit();
 } else {
  $messageStack->add("No orders found with current criteria.", 'error');
 }
}

function filter_text($text) {
$filter_array = array(",","\r","\n","\t");
return str_replace($filter_array,"",$text);
} // function for the filter
?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>