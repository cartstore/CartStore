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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3>Export to QuickBooks&reg;</h3></td>
            <td class="menuboxheading" align="center"><?php echo strftime(DATE_FORMAT_LONG); ?></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#startdate, #enddate").datepicker({dateFormat: 'yy-mm-dd'});
  });
</script>

		<form method="GET" action="<?php echo $PHP_SELF; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <table border="0" cellpadding="0">
          <tr>
            <td><?php echo "Start Date #:"; ?></td>
            <td><input type="text" class="inputbox" name="start" size="12" id="startdate" value="<?php echo $start; ?>">
          </tr>
          <tr>
            <td><?php echo "End Date #:" ; ?></td>
            <td><input type="text" class="inputbox" name="end" size="12" id="enddate" value="<?php echo $end; ?>">
          </tr>
          <tr>
            <td><?php echo "Order Status:"; ?></td>
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
            <td><?php echo tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => 'All Orders')), $orders_statuses), $status); ?>
          </tr>
<!--
          <tr>
            <td><?php echo "Display Type:"; ?></td>
            <td><select class="inputbox" name="submitted">
                <option value="1">Create CSV File</option>

                <option value="2">Print to Screen</option>
              </select>
            </td>
          </tr>
-->
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="generate" class="button" value="<?php echo "Generate"; ?>"></td>
          </tr>
        </table>
      </td>
  </tr>
</table></form>

</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
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
<!-- footer_eof //-->
</body>
</html>
<font color="#FFCACB"
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>