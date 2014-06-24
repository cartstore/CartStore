<?php
/*
  $Id: affiliate_sales.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SALES);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'));

  $affiliate_sales_raw = "
    select  a.*, o.orders_status as orders_status_id, os.orders_status_name as orders_status from " . TABLE_AFFILIATE_SALES . " a 
    left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id = o.orders_id) 
    left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
    where a.affiliate_id = '" . $affiliate_id . "'	 
    order by affiliate_date DESC
    ";

  $affiliate_sales_split = new splitPageResults($affiliate_sales_raw, MAX_DISPLAY_SEARCH_RESULTS);
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<div class="page-header"><h1> <?php echo HEADING_TITLE; ?></h1></div>


        
<p class="lead"><?php echo TEXT_AFFILIATE_HEADER . ' <b>' . tep_db_num_rows(tep_db_query($affiliate_sales_raw)); ?></b> </p>
    
            
      <table class="table">

          <tr>
            <th class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_VALUE; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_PERCENTAGE; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_SALES; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
<?php
  if ($affiliate_sales_split->number_of_rows > 0) {
    $affiliate_sales_values = tep_db_query($affiliate_sales_split->sql_query);
    $number_of_sales = 0;
    $sum_of_earnings = 0;
    while ($affiliate_sales = tep_db_fetch_array($affiliate_sales_values)) {
      $number_of_sales++;
      if ($affiliate_sales['orders_status_id'] >= AFFILIATE_PAYMENT_ORDER_MIN_STATUS) $sum_of_earnings += $affiliate_sales['affiliate_payment'];
      if (($number_of_sales / 2) == floor($number_of_sales / 2)) {
        echo '          <tr class="productListing-even">';
      } else {
        echo '          <tr class="productListing-odd">';
      }
?>
            <td class="smallText" align="center"><?php echo tep_date_short($affiliate_sales['affiliate_date']); ?></td>
            <td class="smallText" align="right"><?php echo $currencies->display_price($affiliate_sales['affiliate_value'], ''); ?></td>
            <td class="smallText" align="right"><?php echo $affiliate_sales['affiliate_percent'] . " %"; ?></td>
            <td class="smallText" align="right"><?php echo $currencies->display_price($affiliate_sales['affiliate_payment'], ''); ?></td>
            <td class="smallText" align="right"><?php if ($affiliate_sales['orders_status']) echo $affiliate_sales['orders_status']; else echo TEXT_DELETED_ORDER_BY_ADMIN; ?></td>
          </tr>
<?php
    }
  } else {
?>
          <tr class="productListing-odd">
            <td class="main" colspan="5"><?php echo TEXT_NO_SALES; ?></td>
          </tr>
<?php
  }
?>
</table>
<?php 
  if ($affiliate_sales_split->number_of_rows > 0) {
?>
     
    <p><?php echo $affiliate_sales_split->display_count(TEXT_DISPLAY_NUMBER_OF_SALES); ?></p>
        
        <ul class="pagination">
        
        <?php echo TEXT_RESULT_PAGE; ?> <?php echo $affiliate_sales_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
        </ul>
            
    
<?php
  }
?>
            <p><?php echo TEXT_INFORMATION_SALES_TOTAL . ' <b>' .  $currencies->display_price($sum_of_earnings,''), '</b></p><p>' . TEXT_INFORMATION_SALES_TOTAL2; ?></p>
         
            
                     
 
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>