<?php
/*
  $Id: stats_monthly_sales.php, v 2.2 2005/12/24  $

  contributed by Fritz Clapp <fritz@sonnybarger.com>

This report displays a summary of monthly or daily totals:
	gross income (order totals)
	subtotals of all orders in the selected period
	nontaxed sales subtotals
	taxed sales subtotals
	tax collected
	shipping/handling charges
	low order fees (if present)
	gift vouchers (or other addl order total component, if present)

The data comes from the orders and orders_total tables, therefore this report
works only for CartStore snapshots since 2002/04/08 (including MS1 and 2.0).

Data is reported as of order purchase date.

If an order status is chosen, the report summarizes orders with that status.

Version 2.0 introduces the capability to "drill down" on any month
to report the daily summary for that month.

Report rows are initially shown in newest to oldest, top to bottom,
but this order may be inverted by clicking the "Invert" control button.

Version 2.1 adds a popup display that lists the various types (and their
subtotals) comprising the tax values in the report rows.

**NOTE:
This Version 2.2 has columns that summarize nontaxed and taxed order subtotals.
The taxed column summarizes subtotals for orders in which tax was charged.
The nontaxed column is the subtotal for the row less the taxed column value.

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
//
// entry for help popup window
if (isset($_GET['help'])){
  echo TEXT_HELP;
  exit;
};
//
// entry for bouncing csv string back as file
if (isset($_POST['csv'])) {
if ($_POST['saveas']) {  // rebound posted csv as save file
		$savename= $_POST['saveas'] . ".csv";
		}
		else $savename='unknown.csv';
$csv_string = '';
if ($_POST['csv']) $csv_string=$_POST['csv'];
  if (strlen($csv_string)>0){
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-Disposition: attachment; filename=$savename");
  echo $csv_string;
  }
  else echo "CSV string empty";
exit;
};
//
// entry for popup display of tax detail
// show=ot_tax
if (isset($_GET['show'])) {
	$ot_type = tep_db_prepare_input($_GET['show']);
	$sel_month = tep_db_prepare_input($_GET['month']);
	$sel_year = tep_db_prepare_input($_GET['year']);
	$sel_day = 0;
	if (isset($_GET['day'])) $sel_day = tep_db_prepare_input($_GET['day']);
	$status = '';
	if ($_GET['status']) $status = tep_db_prepare_input($_GET['status']);
	// construct query for selected detail
	$detail_query_raw = "select sum(ot.value) amount, ot.title description from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
	if ($status<>'') $detail_query_raw .= "o.orders_status ='" . $status . "' and ";
	$detail_query_raw .= "ot.class = '" . $ot_type . "' and month(o.date_purchased)= '" . $sel_month . "' and year(o.date_purchased)= '" . $sel_year . "'";
	if ($sel_day<>0) $detail_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sel_day . "'";
	$detail_query_raw .= " group by ot.title";
	$detail_query = tep_db_query($detail_query_raw);
	echo "<!doctype html public \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html " . HTML_PARAMS . "><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . CHARSET . "\">" . "<title>" . TEXT_DETAIL . "</title><link rel=\"stylesheet\" type=\"text/css\" href=\"includes/stylesheet.css\"></head><body><br><table width=\"80%\" align=center><caption align=center>";
	if ($sel_day<>0) echo $sel_day . "/" ;
	echo $sel_year . "/" . $sel_month;
	if ($sel_day<>0) echo "/" . $sel_day;
	if ($status<>'') echo "<br>" . HEADING_TITLE_STATUS . ":" . "&nbsp;" . $status;
	echo "</caption>";

	while ($detail_line = tep_db_fetch_array($detail_query)) {
	echo "<tr class=dataTableRow><td align=left width='75%'>" . $detail_line['description'] . "</td><td>" . number_format($detail_line['amount'],2) . "</td></tr>";}
	echo "</table></body>";
exit;
};
//
// main entry for report display
?>


<?php
// set printer-friendly toggle
(tep_db_prepare_input($_GET['print']=='yes')) ? $print=true : $print=false;
// set inversion toggle
(tep_db_prepare_input($_GET['invert']=='yes')) ? $invert=true : $invert=false;
?>
<!-- header //-->
<?php if(!$print) require(DIR_WS_INCLUDES . 'header.php'); ?>
 

<?php  // suppress left column for printer-friendly version
	if(!$print) {?>
	 
<?php	};	?>






		<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
                     <?php if ($print) {
	echo "" . STORE_NAME ."";
	};
        ?><br> 
        <small><?php echo HEADING_TITLE; ?></small></h1></div>
         <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
<i class="fa fa fa-university fa-5x pull-left"></i>
 
<p>
<b>How to view and use the store income summary report</b>
</p><p align="justify" class="main">
<b>Reporting store activity by month</b>
</p><p align="justify" class="smallText">
When initially selected from the Reports menu, this report displays a financial summary of all orders in the store database, by month.  Each month of the store's history is summarized in a row, showing the store income and its components, and listing the amounts of taxes, shipping and handling charges, low order fees and gift vouchers. (If the store does not have low order fees or gift vouchers enabled, these columns are omitted from the report.)  Activity is reported as of the date of purchase.
</p><p align="justify" class="smallText">
The top row is the current month, and the rows under it summarize each month of the store's order history.  Beneath the rows of each calendar year is a footer line, summarizing that year's totals in each column of the report. 
</p><p align="justify" class="smallText">
To invert the order of the rows, click the "Invert" button.
</p><p align="justify" class="main">
<b>Reporting monthly summary by days</b>
</p><p align="justify" class="smallText">
The summary of daily activity within any month may be displayed by clicking on the month's name, at the left of the row.  To return from the daily summary to the monthly summary, click the "Back" button in the daily display.
</p><p align="justify" class="main">
<b>What the columns represent (headers explained)</b>
</p><p align="justify" class="smallText">
On the left, the month and year of the row are stated.  The other columns are, left to right:
</p><ul><li class="smallText"><b>Gross Income</b> - the total of all orders  
</li><li class="smallText"><b>Order Subtotal</b> - the total sales of products purchased in the month
<br>Then, the product sales are broken into two categories:
</li><li class="smallText"><b>Nontaxed sales</b> - the subtotal of sales which were not taxed, and 
</li><li class="smallText"><b>Taxed sales</b> - the subtotal of sales which were taxed
</li><li class="smallText"><b>Taxes collected</b> - the amount collected from customers for taxes
</li><li class="smallText"><b>Shipping &amp; handling</b> - the total shipping and handling charges collected  
</li><li class="smallText"><b>Tax on shipping</b> - Tax on shipping and handling charges
</li><li class="smallText"><b>Low order fees</b> and <b>Gift Vouchers</b> - if the store has low order fees enabled, and/or gift vouchers, the totals of these are shown in separate columns
</li></ul>
<p align="justify" class="main">
<b>Selecting report summary by status</b>
</p><p align="justify" class="smallText">
To show the monthly or daily summary information for just one Order Status, select the status in the drop-down box at the upper right of the report screen.  Depending on the store's setup for these values, there may be a status for "Pending" or "Shipped" for instance.  Change this status and the report will be recalculated and displayed. 
</p><p align="justify" class="main">
<b>Showing detail of taxes</b>
</p><p align="justify" class="smallText">
The amount of tax in any row of the report is a link to a popup window, which shows the name of the tax classes charged and their individual amounts.
</p><p align="justify" class="main">
<b>Printing the report</b>
</p><p align="justify" class="smallText">
To view the report in a printer-friendly window, click on the "Print" button, then use your browser's print command in the File menu.  The store name and headers are added to show what orders were selected, and when the report was generated. 
</p><p align="justify" class="main">
<b>Saving report values to a file</b>
</p><p align="justify" class="smallText">
To save the values of the report to a local file, click on the Save CSV button at the bottom of the report.  The report values will be sent to your browser in a text file, and you will be prompted with a Save File dialog box to choose where to save the file.  The contents of the file are in Comma Separated Value (CSV) format, with a line for each row of the report beginning with the header line, and each value in the row is separated by commas. This file can be conveniently and accurately imported to common spreadsheet financial and statistical tools, such as Excel and QuattroPro. The file is provided to your browser with a suggested file name consisting of the report name, status selected, and date/time. <br><br>
</p><p class="smallText"> 
</p>                              </div>
                      </div>
                  </div>   
              </div>    
<div class="container">

<?php
// detect whether this is monthly detail request
$sel_month = 0;
	if ($_GET['month']&& $_GET['year']) {
	$sel_month = tep_db_prepare_input($_GET['month']);
	$sel_year = tep_db_prepare_input($_GET['year']);
	};
// get list of orders_status names for dropdown selection
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                 'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
	  };
// name of status selection
$orders_status_text = TEXT_ALL_ORDERS;
if ($_GET['status']) {
  $status = tep_db_prepare_input($_GET['status']);
  $orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "' and orders_status_id =" . $status);
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	  $orders_status_text = $orders_status['orders_status_name'];}
				};
if (!$print) { ?>
 
			<table class="table table-hover table-condensed table-responsive">
			  <tr><td>
				<?php echo tep_draw_form('status', FILENAME_STATS_MONTHLY_SALES, '', 'get');
				// get list of orders_status names for dropdown selection
				  $orders_statuses = array();
				  $orders_status_array = array();
				  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
				  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
				    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
			         'text' => $orders_status['orders_status_name']);
					$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
				  };
                echo '<div class="form-group"><label>'. HEADING_TITLE_STATUS . '</label>' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?>
				<input type="hidden" name="selected_box" value="reports"></div>
				<?php
					if ($sel_month<>0)
					echo "<input type='hidden' name='month' value='" . $sel_month . "'><input type='hidden' name='year' value='" . $sel_year . "'>";
					if ($invert) echo "<input type='hidden' name='invert' value='yes'>";
				?></form>
				</td>
              </tr>
             </table>
			  
<?php		}; ?>
 
<?php if ($print) { ?>
			 
				<table>
				<tr><td class="smallText"><?php echo HEADING_TITLE_REPORTED . ": "; ?></td>
				<td width="8"></td>
				<td class="smallText" align="left"><?php echo date(ltrim(TEXT_REPORT_DATE_FORMAT)); ?></td>
				</tr>
				<tr><td class="smallText" align="left">
				<?php echo HEADING_TITLE_STATUS . ": ";  ?></td>
				<td width="8"></td>
				<td class="smallText" align="left">
				<?php echo $orders_status_text;?>
				</td>
				</tr>
				<table>
			 
<?php 	};	 ?>
       
<?php if(!$print) { ?>
<!--
row for buttons to print, save, and help
-->
	
 
		 		 
<?php	};
//
// determine if loworder fee is enabled in configuration, include/omit the column
$loworder_query_raw = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key =" . "'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE'";
$loworder = false;
$loworder_query = tep_db_query($loworder_query_raw);
if (tep_db_num_rows($loworder_query)>0) {
	$low_setting=tep_db_fetch_array($loworder_query);
	if ($low_setting['configuration_value']=='true') $loworder=true;
};
//
// if there are extended class values in orders_table
// create extra column so totals are comprehensively correct
$class_val_subtotal = "'ot_subtotal'";
$class_val_tax = "'ot_tax'";
$class_val_shiphndl = "'ot_shipping'";
$class_val_loworder = "'ot_loworderfee'";
$class_val_total = "'ot_total'";
	$extra_class_query_raw = "select value from " . TABLE_ORDERS_TOTAL . " where class <> " . $class_val_subtotal . " and class <>" . $class_val_tax . " and class <>" . $class_val_shiphndl . " and class <>" . $class_val_loworder . " and class <>" . $class_val_total;
	$extra_class = false;
	$extra_class_query = tep_db_query($extra_class_query_raw);
	if (tep_db_num_rows($extra_class_query)>0) $extra_class = true;
// start accumulator for the report content mirrored in CSV
$csv_accum = '';
?> 
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			<table class="table table-hover table-condensed table-responsive">
<tr class="dataTableHeadingRow">
<td class="dataTableHeadingContent" width='45' align='left' valign="bottom"><?php
if ($sel_month == 0) mirror_out(TABLE_HEADING_MONTH); else mirror_out(TABLE_HEADING_MONTH); ?>
</td>
<td class="dataTableHeadingContent" width='35' align='left' valign="bottom"><?php
if ($sel_month == 0) mirror_out(TABLE_HEADING_YEAR); else mirror_out(TABLE_HEADING_DAY); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_INCOME); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_SALES); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_NONTAXED); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_TAXED); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_TAX_COLL); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_SHIPHNDL); ?></td>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_SHIP_TAX); ?></td>
<?php
if ($loworder) { ?>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_LOWORDER); ?></td>
<?php }; ?>
<?php
if ($extra_class) { ?>
<td class="dataTableHeadingContent" width='70' valign="bottom"><?php mirror_out(TABLE_HEADING_OTHER); ?></td>
<?php }; ?>
</tr>
<?php
// clear footer totals
	$footer_gross = 0;
	$footer_sales = 0;
	$footer_sales_nontaxed = 0;
	$footer_sales_taxed = 0;
	$footer_tax_coll = 0;
	$footer_shiphndl = 0;
	$footer_shipping_tax = 0;
	$footer_loworder = 0;
	$footer_other = 0;
// new line for CSV
$csv_accum .= "\n";
// order totals, the driving force
$status = '';
$sales_query_raw = "select sum(ot.value) gross_sales, monthname(o.date_purchased) row_month, year(o.date_purchased) row_year, month(o.date_purchased) i_month, dayofmonth(o.date_purchased) row_day  from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
if ($_GET['status']) {
  $status = tep_db_prepare_input($_GET['status']);
  $sales_query_raw .= "o.orders_status =" . $status . " and ";
	};
$sales_query_raw .= "ot.class = " . $class_val_total;
if ($sel_month<>0) $sales_query_raw .= " and month(o.date_purchased) = " . $sel_month;
$sales_query_raw .= " group by year(o.date_purchased), month(o.date_purchased)";
if ($sel_month<>0) $sales_query_raw .= ", dayofmonth(o.date_purchased)";
$sales_query_raw .=  " order by o.date_purchased ";
if ($invert) $sales_query_raw .= "asc"; else $sales_query_raw .= "desc";
$sales_query = tep_db_query($sales_query_raw);
$num_rows = tep_db_num_rows($sales_query);
if ($num_rows==0) echo '<tr><td class="smalltext">' . TEXT_NOTHING_FOUND . '</td></tr>';
$rows=0;
//
// loop here for each row reported
while ($sales = tep_db_fetch_array($sales_query)) {
	$rows++;
	if ($rows>1 && $sales['row_year']<>$last_row_year) {  // emit annual footer
?>
<tr class="dataTableHeadingRow active">
<td class="dataTableHeadingContent">
<?php
	if ($sales['row_year']==date("Y")) mirror_out(TABLE_FOOTER_YTD);
	else
		if ($sel_month==0) mirror_out(TABLE_FOOTER_YEAR);
		else
			mirror_out(strtoupper(substr($sales['row_month'],0,3)));
?>
</td>
<td class="dataTableHeadingContent">
<?php mirror_out($last_row_year); ?></td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_gross,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales_nontaxed,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales_taxed,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_tax_coll,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_shiphndl,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format(($footer_shipping_tax <= 0) ? 0 : $footer_shipping_tax,2)); ?>
</td>
<?php if ($loworder) { ?>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_loworder,2)); ?>
</td>
<?php }; ?>
<?php if ($extra_class) { ?>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_other,2)); ?>
</td>
<?php };
// clear footer totals
$footer_gross = 0;
$footer_sales = 0;
$footer_sales_nontaxed = 0;
$footer_sales_taxed = 0;
$footer_tax_coll = 0;
$footer_shiphndl = 0;
$footer_shipping_tax = 0;
$footer_loworder = 0;
$footer_other = 0;
// new line for CSV
$csv_accum .= "\n";
?>
</tr>
<?php };
//

// determine net sales for row

// Retrieve totals for products that are zero VAT rated
$net_sales_query_raw = "select sum(op.final_price * op.products_quantity) net_sales from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on (o.orders_id = op.orders_id) where op.products_tax = 0 and ";
if ($status<>'') $net_sales_query_raw .= "o.orders_status ='" . $status . "' and ";
$net_sales_query_raw .= " month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
if ($sel_month<>0) $net_sales_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";

$net_sales_query = tep_db_query($net_sales_query_raw);
$net_sales_this_row = 0;
if (tep_db_num_rows($net_sales_query) > 0)
	$zero_rated_sales_this_row = tep_db_fetch_array($net_sales_query);

// Retrieve totals for products that are NOT zero VAT rated
$net_sales_query_raw = "select sum(op.final_price * op.products_quantity) net_sales, sum(op.final_price * op.products_quantity * (1 + (op.products_tax / 100.0))) gross_sales, sum((op.final_price * op.products_quantity * (1 + (op.products_tax / 100.0))) - (op.final_price * op.products_quantity)) tax from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on (o.orders_id = op.orders_id) where op.products_tax <> 0 and ";
if ($status<>'') $net_sales_query_raw .= "o.orders_status ='" . $status . "' and ";
$net_sales_query_raw .= " month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
if ($sel_month<>0) $net_sales_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";

$net_sales_query = tep_db_query($net_sales_query_raw);
$net_sales_this_row = 0;
if (tep_db_num_rows($net_sales_query) > 0)
	$net_sales_this_row = tep_db_fetch_array($net_sales_query);

// Total tax. This is needed so we can calculate any tax that has been added to the postage
$tax_coll_query_raw = "select sum(ot.value) tax_coll from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
if ($status<>'') $tax_coll_query_raw .= "o.orders_status ='" . $status . "' and ";
$tax_coll_query_raw .= "ot.class = " . $class_val_tax . " and month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
if ($sel_month<>0) $tax_coll_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";
$tax_coll_query = tep_db_query($tax_coll_query_raw);
$tax_this_row = 0;
if (tep_db_num_rows($tax_coll_query)>0)
	$tax_this_row = tep_db_fetch_array($tax_coll_query);

//
// shipping and handling charges for row
$shiphndl_query_raw = "select sum(ot.value) shiphndl from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
if ($status<>'') $shiphndl_query_raw .= "o.orders_status ='" . $status . "' and ";
$shiphndl_query_raw .= "ot.class = " . $class_val_shiphndl . " and month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
if ($sel_month<>0) $shiphndl_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";
$shiphndl_query = tep_db_query($shiphndl_query_raw);
$shiphndl_this_row = 0;
if (tep_db_num_rows($shiphndl_query)>0)
	$shiphndl_this_row = tep_db_fetch_array($shiphndl_query);
//
// low order fees for row
$loworder_this_row = 0;
if ($loworder) {
	$loworder_query_raw = "select sum(ot.value) loworder from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
	if ($status<>'') $loworder_query_raw .= "o.orders_status ='" . $status . "' and ";
	$loworder_query_raw .= "ot.class = " . $class_val_loworder . " and month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
	if ($sel_month<>0) $loworder_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";
	$loworder_query = tep_db_query($loworder_query_raw);
	if (tep_db_num_rows($loworder_query)>0)
	$loworder_this_row = tep_db_fetch_array($loworder_query);
};
//
// additional column if extra class value in orders_total table
$other_this_row = 0;
if ($extra_class) {
	$other_query_raw = "select sum(ot.value) other from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where ";
	if ($status<>'') $other_query_raw .= "o.orders_status ='" . $status . "' and ";
	$other_query_raw .= "ot.class <> " . $class_val_subtotal . " and class <> " . $class_val_tax . " and class <> " . $class_val_shiphndl . " and class <> " . $class_val_loworder . " and class <> " . $class_val_total . " and month(o.date_purchased)= '" . $sales['i_month'] . "' and year(o.date_purchased)= '" . $sales['row_year'] . "'";
	if ($sel_month<>0) $other_query_raw .= " and dayofmonth(o.date_purchased) = '" . $sales['row_day'] . "'";
	$other_query = tep_db_query($other_query_raw);
	if (tep_db_num_rows($other_query)>0)
	$other_this_row = tep_db_fetch_array($other_query);
	};

// Correct any rounding errors
	$net_sales_this_row['net_sales'] = (floor(($net_sales_this_row['net_sales'] * 100) + 0.5)) / 100;
	$net_sales_this_row['tax'] = (floor(($net_sales_this_row['tax'] * 100) + 0.5)) / 100;
	$zero_rated_sales_this_row['net_sales'] = (floor(($zero_rated_sales_this_row['net_sales'] * 100) + 0.5)) / 100;
	$tax_this_row['tax_coll'] = (floor(($tax_this_row['tax_coll'] * 100) + 0.5)) / 100;

// accumulate row results in footer
	$footer_gross += $sales['gross_sales']; // Gross Income
	$footer_sales += $net_sales_this_row['net_sales'] + $zero_rated_sales_this_row['net_sales']; // Product Sales
	$footer_sales_nontaxed += $zero_rated_sales_this_row['net_sales']; // Nontaxed Sales
	$footer_sales_taxed += $net_sales_this_row['net_sales']; // Taxed Sales
	$footer_tax_coll += $net_sales_this_row['tax']; // Taxes Collected
	$footer_shiphndl += $shiphndl_this_row['shiphndl']; // Shipping & handling
        $footer_shipping_tax += ($tax_this_row['tax_coll'] - $net_sales_this_row['tax']); // Shipping Tax
	$footer_loworder += $loworder_this_row['loworder'];
	if ($extra_class) $footer_other += $other_this_row['other'];
?>
<tr class="dataTableRow">
<td class="dataTableContent">
<?php  // live link to report monthly detail
if ($sel_month == 0	&& !$print) {
	echo "<a href='" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "&month=" . $sales['i_month'] . "&year=" . $sales['row_year'] . "' title='" . TEXT_BUTTON_REPORT_GET_DETAIL . "'>";
	}
mirror_out(substr($sales['row_month'],0,3));
if ($sel_month == 0 && !$print) echo '</a>';
?>
</td>
<td class="dataTableContent">
<?php
if ($sel_month==0) mirror_out($sales['row_year']);
else mirror_out($sales['row_day']);
$last_row_year = $sales['row_year']; // save this row's year to check for annual footer
?>
</td>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($sales['gross_sales'],2)); ?></td>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($net_sales_this_row['net_sales'] + $zero_rated_sales_this_row['net_sales'],2)); ?></td>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($zero_rated_sales_this_row['net_sales'],2)); ?></td>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($net_sales_this_row['net_sales'],2)); ?></td>
<td class="dataTableContent" width='70'>
<?php
	// make this a link to the detail popup if nonzero
	if (!$print && ($net_sales_this_row['tax']>0)) {
		echo "<a href=\"#\" onClick=\"window.open('" . $_SERVER['PHP_SELF'] . "?&show=ot_tax&year=" . $sales['row_year'] . "&month=" . $sales['i_month'];
		if ($sel_month<>0) echo "&day=" . $sales['row_day'];
		if ($status<>'') echo "&status=" . $status;
		echo "','detail',config='height=200,width=400,scrollbars=1, resizable=1')\" title=\"Show detail\">";
	};
	mirror_out(number_format($net_sales_this_row['tax'],2));
	if (!$print && $net_sales_this_row['tax']>0) echo "</a>";
?></td>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($shiphndl_this_row['shiphndl'],2)); ?></td>
<td class="dataTableContent" width='70'><?php $sh_tax = $tax_this_row['tax_coll'] - $net_sales_this_row['tax']; mirror_out(number_format(($sh_tax <= 0) ? 0 : $sh_tax,2)); ?></td>
<?php if ($loworder) { ?>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($loworder_this_row['loworder'],2)); ?></td>
<?php }; ?>
<?php
if ($extra_class) { ?>
<td class="dataTableContent" width='70'><?php mirror_out(number_format($other_this_row['other'],2)); ?></td>
<?php }; ?>
</tr>
<?php
// new line for CSV
$csv_accum .= "\n";
//
//
// output footer below ending row
if ($rows==$num_rows){
?>
<tr class="dataTableHeadingRow active">
<td class="dataTableHeadingContent">
<?php
	if ($sel_month<>0)
	mirror_out(strtoupper(substr($sales['row_month'],0,3)));
	else
	{if ($sales['row_year']==date("Y")) mirror_out(TABLE_FOOTER_YTD);
	 else mirror_out(TABLE_FOOTER_YEAR);};
?>
</td>
<td class="dataTableHeadingContent">
<?php mirror_out($sales['row_year']); ?></td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_gross,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales_nontaxed,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_sales_taxed,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_tax_coll,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_shiphndl,2)); ?>
</td>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format(($footer_shipping_tax <= 0) ? 0 : $footer_shipping_tax,2)); ?>
</td>
<?php if ($loworder) { ?>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_loworder,2)); ?>
</td>
<?php }; ?>
<?php if ($extra_class) { ?>
<td class="dataTableHeadingContent" width='70'>
<?php mirror_out(number_format($footer_other,2)); ?>
</td>
<?php };
// clear footer totals
$footer_gross = 0;
$footer_sales = 0;
$footer_sales_nontaxed = 0;
$footer_sales_taxed = 0;
$footer_tax_coll = 0;
$footer_shiphndl = 0;
$footer_shipping_tax = 0;
$footer_loworder = 0;
$footer_other = 0;
// new line for CSV
$csv_accum .= "\n";
?>
</tr>
<?php };
  };
// done with report body
//
// button for Save CSV
if ($num_rows>0 && !$print) {
?>
<tr>
				<td class="smallText" colspan="4"><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post><input type='hidden' name='csv' value='<?php echo $csv_accum; ?>'><input type='hidden' name='saveas' value='sales_report_<?php
					//suggested file name for csv, include year and month if detail
					//include status if selected, end with date and time of report
				if ($sel_month<10) $sel_month_2 = "0" . $sel_month;
				else $sel_month_2 = $sel_month;
				if ($sel_month<>0) echo $sel_year . $sel_month_2 . "_";
				if (strpos($orders_status_text,' ')) echo substr($orders_status_text, 0, strpos($orders_status_text,' ')) . "_" . date("YmdHi"); else echo $orders_status_text . "_" . date("YmdHi");
				?>'><input type="submit" class="btn btn-default" value="<?php echo TEXT_BUTTON_REPORT_SAVE ;?>"></form>
				</td>
</tr>
<?php }; // end button for Save CSV ?> 
            </table></td>
          </tr>
        </table> 
<?php  // suppress footer for printer-friendly version
	if(!$print) require(DIR_WS_INCLUDES . 'footer.php');
        
        else {;?> 
				 
<?php } ; ?>


 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');

function mirror_out ($field) {
	global $csv_accum;
	echo $field;
	$field = strip_tags($field);
	$field = preg_replace ("/,/","",$field);
	if ($csv_accum=='') $csv_accum=$field;
	else
	{if (strrpos($csv_accum,chr(10)) == (strlen($csv_accum)-1)) $csv_accum .= $field;
		else $csv_accum .= "," . $field; };
	return;
};

?>
