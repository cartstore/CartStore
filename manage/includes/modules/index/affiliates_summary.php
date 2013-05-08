<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License

  This is just the code from the install instructions for OSCAffiliate2.8 to make it work on OSCommerce Online Merchant v2.2 rc1

  Upload this file to admin\includes\modules\index folder to show Affiliate Summary on main Admin page.

*/
?>


<table border="0" width="100%" cellspacing="0" cellpadding="4">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent"><?php echo 'Affiliate Report' ?></td>
  </tr>
<?php
  $affiliate_sales_raw = "select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " ";
  $affiliate_sales_query= tep_db_query($affiliate_sales_raw);
  $affiliate_sales= tep_db_fetch_array($affiliate_sales_query);

  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ";
  $affiliate_clickthroughs_query=tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs= tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs=$affiliate_clickthroughs['count'];

  $affiliate_transactions=$affiliate_sales['count'];
  if ($affiliate_transactions>0) {
  	$affiliate_conversions = tep_round($affiliate_transactions/$affiliate_clickthroughs,6)."%";
  }
  else $affiliate_conversions="n/a";

  $affiliate_amount=$affiliate_sales['total'];
  if ($affiliate_transactions>0) {
  	$affiliate_average=tep_round($affiliate_amount/$affiliate_transactions,2);
  }
  else {
  	$affiliate_average="n/a";
  }
  $affiliate_commission=$affiliate_sales['payment'];

  $affiliates_raw = "select count(*) as count from " . TABLE_AFFILIATE . "";
  $affiliates_raw_query=tep_db_query($affiliates_raw);
  $affiliates_raw = tep_db_fetch_array($affiliates_raw_query);
  $affiliate_number= $affiliates_raw['count'];


 
$text  = BOX_ENTRY_AFFILIATES . ' ' . $affiliate_number . '<br>' .
                                   BOX_ENTRY_CONVERSION . ' ' . $affiliate_conversions . '<br>' .
                                   BOX_ENTRY_COMMISSION . ' ' . $currencies->display_price($affiliate_commission, '');
 echo '  <tr class="dataTableRow" >' .
         '    <td class="dataTableContent"> '.$text . '</td>' .
          
         '  </tr>';

?>
</table>