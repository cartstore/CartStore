<?php
/*
  $Id: affiliate_payment.php,v 2.00 2003/10/12

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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'));

  $affiliate_payment_raw = "
    select p.* , s.affiliate_payment_status_name 
           from " . TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE_PAYMENT_STATUS . " s 
           where p.affiliate_payment_status = s.affiliate_payment_status_id 
           and s.affiliate_language_id = '" . $languages_id . "' 
           and p.affiliate_id =  '" . $affiliate_id . "' 
           order by p.affiliate_payment_id DESC
           ";

  $affiliate_payment_split = new splitPageResults($affiliate_payment_raw, MAX_DISPLAY_SEARCH_RESULTS);
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<div class="page-header">
    
    <h1>  <?php echo HEADING_TITLE; ?></h1>

</div>
   
<p class="lead"> <?php echo TEXT_AFFILIATE_HEADER . ' <b>' . tep_db_num_rows(tep_db_query($affiliate_payment_raw)); ?></b</p>


     
<table class="table">
          <tr>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_PAYMENT_ID; ?></td>
            <th class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_PAYMENT; ?></td>
            <th class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
<?php
  if ($affiliate_payment_split->number_of_rows > 0) {
    $affiliate_payment_values = tep_db_query($affiliate_payment_split->sql_query);
    $number_of_payment = 0;
    while ($affiliate_payment = tep_db_fetch_array($affiliate_payment_values)) {
      $number_of_payment++;

      if (($number_of_payment / 2) == floor($number_of_payment / 2)) {
        echo '          <tr class="productListing-even">';
      } else {
        echo '          <tr class="productListing-odd">';
      }
?>
            <td class="smallText" align="right"><?php echo $affiliate_payment['affiliate_payment_id']; ?></td>
            <td class="smallText" align="center"><?php echo tep_date_short($affiliate_payment['affiliate_payment_date']); ?></td>
            <td class="smallText" align="right"><?php echo $currencies->display_price($affiliate_payment['affiliate_payment_total'], ''); ?></td>
            <td class="smallText" align="right"><?php echo $affiliate_payment['affiliate_payment_status_name']; ?></td>
          </tr>
<?php
    }
  } else {
?>
        
          <tr><td colspan="4"> <?php echo TEXT_NO_PAYMENTS; ?></td></tr>
          
<?php
  }
?>
         
</table>
<?php 
  if ($affiliate_payment_split->number_of_rows > 0) {
?>    
         
    <?php echo $affiliate_payment_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?>

    
  <ul class="pagination">      <?php echo TEXT_RESULT_PAGE; ?> <?php echo $affiliate_payment_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
  </ul>
          

<?php
  }
  $affiliate_payment_values = tep_db_query("select sum(affiliate_payment_total) as total from " . TABLE_AFFILIATE_PAYMENT . " where affiliate_id = '" . $affiliate_id . "'");
  $affiliate_payment = tep_db_fetch_array($affiliate_payment_values);
?>
          <p><?php echo TEXT_INFORMATION_PAYMENT_TOTAL . ' <b>' . $currencies->display_price($affiliate_payment['total'], ''); ?></b></p>
        
               

<!-- body_text_eof //-->
 <!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
 <!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>