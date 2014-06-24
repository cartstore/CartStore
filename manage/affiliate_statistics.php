<?php
/*
  $Id: affiliate_statistics.php,v 2.00 2003/10/12

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

  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY .  " where affiliate_banners_affiliate_id  = '" .  $_GET['acID'] . "'";
  $affiliate_banner_history_query = tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions = "n/a"; 
  
  $affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id ='" . $_GET['acID'] . "'");
 
  $affiliate = tep_db_fetch_array($affiliate_query);
  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  
  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . $_GET['acID'] . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs = $affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "
    select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a 
    left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) 
    where a.affiliate_id = '" . $_GET['acID'] . "' and o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . "
    ";
  $affiliate_sales_query = tep_db_query($affiliate_sales_raw);
  $affiliate_sales = tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions=$affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
	  $affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100,2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }

  if ($affiliate_sales['total'] > 0) {
    $affiliate_average = $affiliate_sales['total'] / $affiliate_sales['count'];
  } else {
    $affiliate_average = 0;
  }
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class=""page-header"><h1>
    <?php echo HEADING_TITLE; ?></h1>
</div>
        
        
<p> <?php echo '<a class="btn btn-defualt" href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('action'))) . '">' .  IMAGE_BACK . '</a>'; ?></p>

            
             
            
            <table class="table table-hover table-condensed table-responsive">
                 <tr>
                  <td width="35%"  class="dataTableContent"><b><?php echo TEXT_AFFILIATE_NAME; ?></b></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname']; ?></td>
                  <td width="35%"  class="dataTableContent"><b><?php echo TEXT_AFFILIATE_JOINDATE; ?></b></td>
                  <td width="15%" class="dataTableContent"><?php echo tep_date_short($affiliate['affiliate_date_account_created']); ?></td>
                </tr>
                <tr>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_IMPRESSIONS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_impressions; ?></td>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_VISITS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_clickthroughs; ?></td>
                </tr>
                <tr>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_TRANSACTIONS; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_sales['count']; ?></td>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_CONVERSION; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_conversions.' %';?></td>
                </tr>
                <tr>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_AMOUNT; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_sales['total'], ''); ?></td>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_AVERAGE; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_average, ''); ?></td>
                </tr>
                <tr>
                  <td width="35%"  class="dataTableContent"><?php echo TEXT_COMMISSION_RATE; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_percent, ' %'; ?></td>
                  <td width="35%"  class="dataTableContent"><b><?php echo TEXT_COMMISSION; ?></b></td>
                  <td width="15%" class="dataTableContent"><b><?php echo $currencies->display_price($affiliate_sales['payment'], ''); ?></b></td>
                </tr>
                 
                <tr>
                    <td class="dataTableContent" colspan="4"><i><p><?php echo TEXT_SUMMARY; ?></p></i></td>
                </tr>
                
                <tr>
                  <td  class="dataTableContent" colspan="4"><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, 'acID=' . $_GET['acID']) . '">  CLICKTHROUGHS </a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_SALES, 'acID=' . $_GET['acID']) . '">   SALES  </a>'; ?></td>
                </tr>
              
            </table> 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>