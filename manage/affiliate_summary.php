<?php
/*
  $Id: affiliate_summary.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // delete clickthroughs
  if (AFFILIATE_DELETE_CLICKTHROUGHS != 'false' && is_numeric(AFFILIATE_DELETE_CLICKTHROUGHS)) {
    $time = mktime (1,1,1,date("m"),date("d") - AFFILIATE_DELETE_CLICKTHROUGHS, date("Y"));
    $time = date("Y-m-d", $time);
    tep_db_query("delete from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_clientdate < '". $time . "'");
  }
  // delete old records from affiliate_banner_history
  if (AFFILIATE_DELETE_AFFILIATE_BANNER_HISTORY != 'false' && is_numeric(AFFILIATE_DELETE_AFFILIATE_BANNER_HISTORY)) {
    $time = mktime (1,1,1,date("m"),date("d") - AFFILIATE_DELETE_AFFILIATE_BANNER_HISTORY, date("Y"));
    $time = date("Y-m-d", $time);
    tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_history_date < '". $time . "'");
  }


  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY . "";
  $affiliate_banner_history_query = tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions = "n/a";

  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . "";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs = $affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "
            select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a 
            left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
            where o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " 
            ";

  $affiliate_sales_query= tep_db_query($affiliate_sales_raw);
  $affiliate_sales= tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions = $affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
	$affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100,2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }

  $affiliate_amount = $affiliate_sales['total'];
  if ($affiliate_transactions > 0) {
	$affiliate_average = tep_round($affiliate_amount / $affiliate_transactions, 2);
  } else {
    $affiliate_average = "n/a";
  }

  $affiliate_commission = $affiliate_sales['payment'];

  $affiliates_raw = "select count(*) as count from " . TABLE_AFFILIATE . "";
  $affiliates_raw_query = tep_db_query($affiliates_raw);
  $affiliates_raw = tep_db_fetch_array($affiliates_raw_query);
  $affiliate_number = $affiliates_raw['count'];
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="page-header"><h1>
<?php echo HEADING_TITLE; ?></h1></div>



          <table width="100%" border="0" cellpadding="4" cellspacing="2" class="table">
              <center>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AFFILIATES; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_number; ?></td>
                  <td width="35%" align="right" class="dataTableContent"></td>
                  <td width="15%" class="dataTableContent"></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_IMPRESSIONS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_impressions; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_VISITS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_clickthroughs; ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_TRANSACTIONS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_transactions; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_CONVERSION; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_conversions;?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AMOUNT; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_amount, ''); ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AVERAGE; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_average, ''); ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_COMMISSION_RATE; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo tep_round(AFFILIATE_PERCENT, 2) . ' %'; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><b><?php echo TEXT_COMMISSION; ?></b></td>
                  <td width="15%" class="dataTableContent"><b><?php echo $currencies->display_price($affiliate_commission, ''); ?></b></td>
                </tr>
             
                <tr>
                  <td align="center" class="dataTableContent" colspan="4"><b><?php echo TEXT_SUMMARY; ?></b></td>
                </tr>
              
                <tr>
                  <td align="right" class="dataTableContent" colspan="4"><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '') . '">' . IMAGE_BANNERS . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '') . '">' . IMAGE_CLICKTHROUGHS . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '') . '">' . IMAGE_SALES . '</a>'; ?></td>
                </tr>
              </center>
            </table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>