<?php
/*
  $Id: invoice.php,v 1.6 2003/06/20 00:37:30 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 14px;
	font-weight: normal;
	color: #000000;
}
.dataTableHeadingRow {
	background-color: #000000;
	color: #FFFFFF;
}
-->
</style>
<link href="includes/stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
       <?php //MVS Start this updates to STORE_NAME ?>
        <td class="pageHeading" align="right">&nbsp;</td>
<?php //MVS End ?>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><?php echo tep_draw_separator(); ?>
        <h3>Order ID: <?php echo $oID; ?></h3>
        </td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.png', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo $order->customer['telephone']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $order->info['payment_method']; ?></td>
      </tr>
    </table></td>
  </tr>
  <?php

if($order->customer['delivery_date']>0)
		  {
		  ?>
		   <tr>
            <td class="main">
			<table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b>Delivery Time</b></td>
        <td class="main"><?php echo $order->customer['delivery_date'];
			if($order->customer['delivery_slotid'] >0)
			{
			$timeSlot_query = tep_db_query("SELECT * from sw_time_slots WHERE slotid = '" . $order->customer['delivery_slotid'] . "'");
		    $timeSlot = tep_db_fetch_array($timeSlot_query);
			print '('.$timeSlot['slot'].')';
			}
			 ?></td>
      </tr>
    </table>
			</td>
          </tr>
<?php
}
   //MVS start ?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
  </tr>
  <tr>
  <?php // MVS start MVS Invoice, only if the the data is in the "orders_shipping" table ?>
  <?php if (tep_not_null($order->orders_shipping_id)) {  ?>
         </td></tr><tr>
              <td><table border="1" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="left"><?php echo

TABLE_HEADING_PRODUCTS_VENDOR; ?></td>
            <td class="dataTableHeadingContent" align="left"><?php echo

TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_VENDORS_SHIP; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_SHIPPING_METHOD; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_SHIPPING_COST; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo

TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo

TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
$package_num = sizeof($order->products);
$box_num = $l + 1;
echo '<td class="dataTableContent">There will be <b>at least ' . $package_num .

'</b><br>packages shipped.</td>';
  for ($l=0, $m=sizeof($order->products); $l<$m; $l++) {
  echo '         <tr class="dataTableRow">' . "\n" .
       '         <td class="dataTableContent" valign="center">Shipment Number ' .

$box_num++ . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['Vmodule'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['Vmethod'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['Vcost'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">ship tax<br>'

. $order->products[$l]['Vship_tax'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['spacer'] . '</td>';
       for ($i=0, $n=sizeof($order->products[$l]['orders_products']); $i<$n; $i++) {
  echo '          <tr>' . "\n" .

       '            <td class="dataTableContent" valign="center" align="right">' .

$order->products[$l]['orders_products'][$i]['qty'] . '&nbsp;x</td>' . "\n" .
       '            <td class="dataTableContent" valign="center" align="left">' .

$order->products[$l]['orders_products'][$i]['name'];

      if (isset($order->products[$l]['orders_products'][$i]['attributes']) &&

(sizeof($order->products[$l]['orders_products'][$i]['attributes']) > 0)) {
        for ($j = 0, $k =

sizeof($order->products[$l]['orders_products'][$i]['attributes']); $j < $k; $j++) {
  echo '<br><nobr><small>&nbsp;<i> - ' .

$order->products[$l]['orders_products'][$i]['attributes'][$j]['option'] . ': ' .

$order->products[$i]['orders_products'][$i]['attributes'][$j]['value'];
      if ($order->products[$l]['orders_products'][$i]['attributes'][$j]['price'] != '0')

echo ' (' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['prefix'] .

$currencies->format($order->products[$l]['orders_products'][$i]['attributes'][$j]['price']

* $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'],

$order->info['currency_value']) . ')';
  echo '</i></small></nobr>';
        }
      }
  echo     /*  //MVS   added lines for this data
      ['vendor_name'],
      ['vendor_ship'],
      ['shipping_method'],
      ['shipping_cost']
      */
      '            <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
      '            <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
      '            <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
      '            <td class="dataTableContent" valign="center" align="center">' .

$order->products[$l]['orders_products'][$i]['model'] . '</td>' . "\n" .
      '            <td class="dataTableContent" align="center" valign="center">' .

tep_display_tax_value($order->products[$l]['orders_products'][$i]['tax']) . '%</td>' . "\n"

.
      '            <td class="dataTableContent" align="center" valign="center"><b>' .

$currencies->format($order->products[$l]['orders_products'][$i]['final_price'], true,

$order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" align="center" valign="center"><b>' .

$currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'],

$order->products[$l]['orders_products'][$i]['tax']), true, $order->info['currency'],

$order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" align="center" valign="center"><b>' .

$currencies->format($order->products[$l]['orders_products'][$i]['final_price'] *

$order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'],

$order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" align="right" valign="center"><b>' .

$currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'],

$order->products[$l]['orders_products'][$i]['tax']) *

$order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'],

$order->info['currency_value']) . '</b></td>' . "\n";
  echo '          </tr>';
    }
    }
?>
          <tr>
            <td align="right" colspan="12"><table border="0" cellspacing="0"

cellpadding="2">
<?php          } else {  // MVS end it is an old order  ?>


    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
      </tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
      <tr>
        <?php //MVS Start ?>
<?php  }
// MVS End
     for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="smallText" colspan="7">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="smallText" colspan="7">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
