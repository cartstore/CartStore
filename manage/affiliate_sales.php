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

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if ($_GET['acID'] > 0) {

    $affiliate_sales_raw = "
      select asale.*, os.orders_status_name as orders_status, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_SALES . " asale 
      left join " . TABLE_ORDERS . " o on (asale.affiliate_orders_id = o.orders_id) 
      left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = " . $languages_id . ") 
      left join " . TABLE_AFFILIATE . " a on (a.affiliate_id = asale.affiliate_id) 
      where asale.affiliate_id = '" . $_GET['acID'] . "' 
      order by affiliate_date desc 
      ";
    $affiliate_sales_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_sales_raw, $affiliate_sales_numrows);

  } else {

    $affiliate_sales_raw = "
      select asale.*, os.orders_status_name as orders_status, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_SALES . " asale 
      left join " . TABLE_ORDERS . " o on (asale.affiliate_orders_id = o.orders_id) 
      left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = " . $languages_id . ") 
      left join " . TABLE_AFFILIATE . " a  on (a.affiliate_id = asale.affiliate_id) 
      order by affiliate_date desc 
      ";
    $affiliate_sales_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_sales_raw, $affiliate_sales_numrows);
  }
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="page-header"><h1>
<?php echo HEADING_TITLE; ?>

</h1></div>


<?php 
  if ($_GET['acID'] > 0) {
?>
    
    <p>
<?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('action'))) . '">' . IMAGE_BACK . '</a>'; ?>
</p>

<?php
  } else {
?>
  <p><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '') . '">' . IMAGE_BACK . '</a>'; ?></td>
</p>

<?php
  }
?>
          <table border="0" class="table">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VALUE; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PERCENTAGE; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SALES; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
<?php
  if ($affiliate_sales_numrows > 0) {
    $affiliate_sales_values = tep_db_query($affiliate_sales_raw);
    $number_of_sales = '0';
    while ($affiliate_sales = tep_db_fetch_array($affiliate_sales_values)) {
      $number_of_sales++;
      if (($number_of_sales / 2) == floor($number_of_sales / 2)) {
        echo '          <tr class="dataTableRowSelected">';
      } else {
        echo '          <tr class="dataTableRow">';
      }

      $link_to = '<a href="orders.php?action=edit&oID=' . $affiliate_sales['affiliate_orders_id'] . '">' . $affiliate_sales['affiliate_orders_id'] . '</a>';
?>
            <td class="dataTableContent"><?php echo $affiliate_sales['affiliate_firstname'] . " ". $affiliate_sales['affiliate_lastname']; ?></td>
            <td class="dataTableContent"><?php echo tep_date_short($affiliate_sales['affiliate_date']); ?></td>
            <td class="dataTableContent"><?php echo $link_to; ?></td>
            <td class="dataTableContent">&nbsp;&nbsp;<?php echo $currencies->display_price($affiliate_sales['affiliate_value'], ''); ?></td>
            <td class="dataTableContent"><?php echo $affiliate_sales['affiliate_percent'] . "%" ; ?></td>
            <td class="dataTableContent">&nbsp;&nbsp;<?php echo $currencies->display_price($affiliate_sales['affiliate_payment'], ''); ?></td>
            <td class="dataTableContent"><?php if ($affiliate_sales['orders_status']) echo $affiliate_sales['orders_status']; else echo TEXT_DELETED_ORDER_BY_ADMIN; ?></td>
<?php
    }
  } else {
?>
          <tr class="dataTableRowSelected">
            <td colspan="7" class="smallText"><?php echo TEXT_NO_SALES; ?></td>
          </tr>
<?php
  }
  if ($affiliate_sales_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
          <tr>
            <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $affiliate_sales_split->display_count($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SALES); ?></td>
                <td class="smallText" align="right"><?php echo $affiliate_sales_split->display_links($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
  }
?>
        </table> 

 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>