<?php

/*

  $Id: account_history_info.php,v 1.100 2003/06/09 23:03:52 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }



  if (!isset($_GET['order_id']) || (isset($_GET['order_id']) && !is_numeric($_GET['order_id']))) {

    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));

  }



// Begin RMA Returns System - added order status ID to query

  $customer_info_query = tep_db_query("select customers_id, orders_status from " . TABLE_ORDERS . " where orders_id = '". (int)$_GET['order_id'] . "'");

  $customer_info = tep_db_fetch_array($customer_info_query);

  $orders_status = $customer_info['orders_status'];

  if ($customer_info['customers_id'] != $customer_id) {

    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));

  }

// End RMA Returns System



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);



  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));

  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'], 'SSL'));



  require(DIR_WS_CLASSES . 'order.php');

  $order = new order($_GET['order_id']);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

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

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td align="right">&nbsp;</td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main" colspan="2"><b><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></b></td>

          </tr>

          <tr>

            <td class="smallText"><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?></td>

            <td class="smallText" align="right"><?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

<?php

  if ($order->delivery != false) {

?>

            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main"><b><?php echo HEADING_DELIVERY_ADDRESS; ?></b></td>

              </tr>

              <tr>

                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>

              </tr>

<?php

    //MVS start

      $orders_shipping_id = '';

      $check_new_vendor_data_query = tep_db_query("select orders_shipping_id, orders_id, vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id . "'");

      while ($checked_data = tep_db_fetch_array($check_new_vendor_data_query)) {

              $orders_shipping_id = $checked_data['orders_shipping_id'];

                                }    //MVS end



                    if (tep_not_null($order->info['shipping_method'])) {

?>

              <tr>

                <td class="main"><b><?php echo HEADING_SHIPPING_METHOD; ?></b></td>

              </tr>

              <tr>

                <td class="main"><?php echo $order->info['shipping_method']; ?></td>

              </tr>

<?php

    }

?>

            </table></td>

<?php

  }

?>

            <td width="<?php echo (($order->delivery != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

			  <?php  if (tep_not_null($orders_shipping_id)) {   //MVS start

          require(DIR_WS_INCLUDES . 'vendor_order_data.php');

          require(DIR_WS_INCLUDES . 'vendor_order_info.php');

          } else {

         //MVS end ?>

                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  if (sizeof($order->info['tax_groups']) > 1) {

?>

                  <tr>

                    <td class="main" colspan="2"><b><?php echo HEADING_PRODUCTS; ?></b></td>

                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>

                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>

                  </tr>

<?php

  } else {

?>

                  <tr>

                    <td class="main" colspan="3"><b><?php echo HEADING_PRODUCTS; ?></b></td>

                  </tr>

<?php

  }



  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

   // Begin RMA Returns

if ($order->products[$i]['return'] == '1') {

$rma_query_one = tep_db_query("SELECT returns_id FROM " . TABLE_RETURNS_PRODUCTS_DATA . " where products_id = '" . $order->products[$i]['id'] . "' and order_id = '" . $_GET['order_id'] . "'");

$rma_query = tep_db_fetch_array($rma_query_one);

$rma_number_query = tep_db_query("SELECT rma_value FROM " . TABLE_RETURNS . " where returns_id = '" . $rma_query['returns_id'] . "'");

$rma_result = tep_db_fetch_array($rma_number_query);



$return_link = '<b>' . TEXT_RMA . ' #&nbsp;<u><a href="' . tep_href_link(FILENAME_RETURNS_TRACK, 'action=returns_show&rma=' . $rma_result['rma_value'], 'NONSSL') . '">' . $rma_result['rma_value'] . '</a></u></b>';

} else {

$return_link = '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $_GET['order_id'] . '&products_id=' . ($order->products[$i]['id']), 'NONSSL') . '"><b><u>' . TEXT_RETURN_PRODUCT .'</a></u></b>';

}

// Don't show Return link if order is still pending or processing

// You can change this or comment it out as best fits your store configuration

// My first contribution to anything open source, a bug fix to a long running RMA system. These were the changes I had to make to get it to work with a 2.2MS2 install.



if (($orders_status == '1') OR ($orders_status == '2') ) {

	$return_link = '';

}

    echo '          <tr>' . "\n" .

         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .

         '            <td class="main" valign="top">' . $order->products[$i]['name'];

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {

      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

        echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';

      }

    }

	echo $return_link;

// JLM: End RMA Returns



    echo '</td>' . "\n";



    if (sizeof($order->info['tax_groups']) > 1) {

      echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    }



    echo '            <td class="main" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .

         '          </tr>' . "\n";

  }

  //MVS Start

  }

//MVS End

?>

                </table></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="main"><b><?php echo HEADING_BILLING_ADDRESS; ?></b></td>

              </tr>

              <tr>

                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>

              </tr>

              <tr>

                <td class="main"><b><?php echo HEADING_PAYMENT_METHOD; ?></b></td>

              </tr>

              <tr>

                <td class="main"><?php echo $order->info['payment_method']; ?></td>

              </tr>

            </table></td>

            <td width="70%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {

    echo '              <tr>' . "\n" .

         '                <td class="main" align="right" width="100%">' . $order->totals[$i]['title'] . '</td>' . "\n" .

         '                <td class="main" align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .

         '              </tr>' . "\n";

  }

?>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo HEADING_ORDER_HISTORY; ?></b></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by osh.date_added");

  while ($statuses = tep_db_fetch_array($statuses_query)) {

    echo '              <tr>' . "\n" .

         '                <td class="main" valign="top" width="70">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .

         '                <td class="main" valign="top" width="70">' . $statuses['orders_status_name'] . '</td>' . "\n" .

         '                <td class="main" valign="top">' . (empty($statuses['comments']) ? '&nbsp;' : nl2br($statuses['comments'])) . '</td>' . "\n" .

         '              </tr>' . "\n";

  }

?>

            </table></td>

          </tr>

        </table></td>

      </tr>

<!-- Package Tracking Plus BEGIN -->
<?php
    if ($order->info['usps_track_num'] == NULL & $order->info['usps_track_num2'] == NULL & $order->info['ups_track_num'] == NULL & $order->info['ups_track_num2'] == NULL & $order->info['fedex_track_num'] == NULL & $order->info['fedex_track_num2'] == NULL & $order->info['dhl_track_num'] == NULL & $order->info['dhl_track_num2'] == NULL) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_TRACKING; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo TEXT_NO_TRACKING_AVAILABLE; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else if ($order->info['usps_track_num'] == NULL or $order->info['usps_track_num2'] == NULL or $order->info['ups_track_num'] == NULL or $order->info['ups_track_num2'] == NULL or $order->info['fedex_track_num'] == NULL or $order->info['fedex_track_num2'] == NULL or $order->info['dhl_track_num'] == NULL or $order->info['dhl_track_num2'] == NULL) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_TRACKING; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
      if ($order->info['usps_track_num'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">USPS(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>"><?php echo $order->info['usps_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>">Track</a></td>
			  </tr>

<?php
}
      if ($order->info['usps_track_num2'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">USPS(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>"><?php echo $order->info['usps_track_num2']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num2']; ?>">Track</a></td>
              </tr>
<?php
}
      if ($order->info['ups_track_num'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">UPS(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package"><?php echo $order->info['ups_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track</a></td>
			  </tr>
<?php
}
      if ($order->info['ups_track_num2'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">UPS(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num2']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package"><?php echo $order->info['ups_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num2']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track</a></td>
			  </tr>
<?php
}
      if ($order->info['fedex_track_num'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">Fedex(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['fedex_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
			  </tr>
<?php
}
      if ($order->info['fedex_track_num2'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">Fedex(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num2']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['fedex_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num2']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
			  </tr>
<?php
}
      if ($order->info['dhl_track_num'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">DHL(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['dhl_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
			  </tr>
<?php
}
      if ($order->info['dhl_track_num2'] == NULL) {
}else{
?>
              <tr>
			    <td class="main" align="left">DHL(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num2']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['dhl_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num2']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
			  </tr>
<?php
}
?>
            </table></td>
          </tr></table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else if ($order->info['usps_track_num'] != NULL & $order->info['usps_track_num2'] != NULL & $order->info['ups_track_num'] != NULL & $order->info['ups_track_num2'] != NULL & $order->info['fedex_track_num'] != NULL & $order->info['fedex_track_num2'] != NULL & $order->info['dhl_track_num'] != NULL & $order->info['dhl_track_num2'] != NULL) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_TRACKING; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
			    <td class="main" align="left">USPS(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>"><?php echo $order->info['usps_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>">Track</a></td>
			  </tr>
              <tr>
			    <td class="main" align="left">USPS(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num']; ?>"><?php echo $order->info['usps_track_num2']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php echo $order->info['usps_track_num2']; ?>">Track</a></td>
              </tr>
			  <tr>
			    <td class="main" align="left">UPS(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package"><?php echo $order->info['ups_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track</a></td>
			  </tr>
              <tr>
			    <td class="main" align="left">UPS(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num2']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package"><?php echo $order->info['ups_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php echo $order->info['ups_track_num2']; ?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track</a></td>
              </tr>
              <tr>
			    <td class="main" align="left">Fedex(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['fedex_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
			  </tr>
              <tr>
			    <td class="main" align="left">Fedex(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num2']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['fedex_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php echo $order->info['fedex_track_num2']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
              </tr>
              <tr>
			    <td class="main" align="left">DHL(1):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['dhl_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
              <tr>
			    <td class="main" align="left">DHL(2):</td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num2']; ?>&action=track&language=english&cntry_code=us"><?php echo $order->info['dhl_track_num']; ?></a></td>
                <td class="main" align="left"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php echo $order->info['dhl_track_num2']; ?>&action=track&language=english&cntry_code=us">Track</a></td>
              </tr>
            </table></td>
          </tr></table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php');
?>
<!-- Package Tracking Plus END -->

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <!-- // Points/Rewards Module V2.00 history_back_bof  //-->

	            <td class="main"><a class="button" href="javascript:history.go(-1)"><?php echo ''. IMAGE_BUTTON_BACK.''; ?></a></td>

<!-- // Points/Rewards Module V2.00 history_back_eof //-->

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

            </table></td>

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

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

