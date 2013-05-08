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
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=120,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_sales.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
          <tr>
            <td class="main" colspan="5"><?php echo TEXT_AFFILIATE_HEADER . ' <b>' . tep_db_num_rows(tep_db_query($affiliate_sales_raw)); ?></b></td>
          </tr>
          <tr>
            <td colspan="5"></td>
          </tr>
          <tr>
            <td class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DATE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_HELP_12) . '\')"> ' . TEXT_SALES_HELP . '</a>'; ?></td>
            <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_VALUE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_HELP_13) . '\')"> ' . TEXT_SALES_HELP . '</a>'; ?></td>
            <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_PERCENTAGE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_HELP_14) . '\')"> ' . TEXT_SALES_HELP . '</a>'; ?></td>
            <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_SALES; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_HELP_15) . '\')"> ' . TEXT_SALES_HELP . '</a>'; ?></td>
            <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_STATUS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_HELP_16) . '\')"> ' . TEXT_SALES_HELP . '</a>'; ?></td>
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
          <tr>
            <td colspan="5"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php 
  if ($affiliate_sales_split->number_of_rows > 0) {
?>
          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo $affiliate_sales_split->display_count(TEXT_DISPLAY_NUMBER_OF_SALES); ?></td>
                <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE; ?> <?php echo $affiliate_sales_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
<?php
  }
?>
            <td class="main" colspan="5"><br><?php echo TEXT_INFORMATION_SALES_TOTAL . ' <b>' .  $currencies->display_price($sum_of_earnings,''), '</b>' . TEXT_INFORMATION_SALES_TOTAL2; ?></td>
          </tr>
                <tr>
                  <td colspan="5"><?php echo tep_draw_separator(); ?></td>
                </tr>
                 <tr>
                  <td align="center" class="boxtext" colspan="5"><b><?php echo TEXT_SALES; ?><b></td>
                </tr>
                <tr>
                  <td colspan="5"><?php echo tep_draw_separator(); ?></td>
                </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>