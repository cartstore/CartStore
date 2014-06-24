<?php
/*
  $Id: affiliate_clicks.php,v 2.00 2003/10/12

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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_CLICKS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'));

  $affiliate_clickthroughs_raw = "
    select a.*, pd.products_name from " . TABLE_AFFILIATE_CLICKTHROUGHS . " a 
    left join " . TABLE_PRODUCTS . " p on (p.products_id = a.affiliate_products_id) 
    left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "') 
    where a.affiliate_id = '" . $affiliate_id . "'  ORDER BY a.affiliate_clientdate desc
    ";
  $affiliate_clickthroughs_split = new splitPageResults($affiliate_clickthroughs_raw, MAX_DISPLAY_SEARCH_RESULTS);

  $affiliate_clickthroughs_numrows_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . $affiliate_id . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_numrows_raw);
  $affiliate_clickthroughs_numrows = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs_numrows =$affiliate_clickthroughs_numrows['count'];
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<div class="page-header">   <h1>
    <?php echo HEADING_TITLE; ?></h1></div>


<p class="lead">   <?php echo TEXT_AFFILIATE_HEADER . ' <b>' . $affiliate_clickthroughs_numrows; ?></b> </p>


     <table class="table">
          <tr>
            <th class="infoBoxHeading"><?php echo TABLE_HEADING_DATE; ?><?php echo ''; ?></td>
 	        <th class="infoBoxHeading"><?php echo TABLE_HEADING_CLICKED_PRODUCT; ?><?php echo ''; ?></td>
	        <th class="infoBoxHeading"><?php echo TABLE_HEADING_REFFERED; ?><?php echo ''; ?></td>
          </tr>
<?php
  if ($affiliate_clickthroughs_split->number_of_rows > 0) {
    $affiliate_clickthroughs_values = tep_db_query($affiliate_clickthroughs_split->sql_query);
    $number_of_clickthroughs = '0';
    while ($affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_values)) {
      $number_of_clickthroughs++;

      if (($number_of_clickthroughs / 2) == floor($number_of_clickthroughs / 2)) {
        echo '          <tr class="productListing-even">';
      } else {
        echo '          <tr class="productListing-odd">';
      }
?>
            <td class="smallText"><?php echo tep_date_short($affiliate_clickthroughs['affiliate_clientdate']); ?></td>
<?php
      if ($affiliate_clickthroughs['affiliate_products_id'] > 0) {
        $link_to = '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $affiliate_clickthroughs['affiliate_products_id']) . '" target="_blank">' . $affiliate_clickthroughs['products_name'] . '</a>';
      } else {
        $link_to = "Startpage";
      }
?>
            <td class="smallText"><?php echo $link_to; ?></td>
            <td class="smallText"><?php echo $affiliate_clickthroughs['affiliate_clientreferer']; ?></td>
          </tr>

<?php
    }
  } else {
?>
        <p><?php echo TEXT_NO_CLICKS; ?></p>
          
<?php
  }
?>
       </table> 
<?php 
  if ($affiliate_clickthroughs_split->number_of_rows > 0) {
?>
<p>  <?php echo $affiliate_clickthroughs_split->display_count(TEXT_DISPLAY_NUMBER_OF_CLICKS); ?> </p>
<ul class="pagination">  <?php echo $affiliate_clickthroughs_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul>



<?php
  }
?>
               
        
        
<!-- body_text_eof //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>