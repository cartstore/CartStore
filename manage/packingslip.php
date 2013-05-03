<?php
/*
  $Id: packingslip.php,v 1.7 2003/06/20 00:40:10 hpdl Exp $

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
<?php include '../templates/includes/modules/general_area5.php';?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td class="pageHeading" align="right">&nbsp;</td>
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
<?php //MVS start

    $index = 0;
      $order_packslip_query = tep_db_query("select vendors_id, orders_products_id,

products_name, products_model, products_price, products_tax, products_quantity, final_price

from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
      while ($order_packslip_data = tep_db_fetch_array($order_packslip_query)) {
      $packslip_products[$index] = array('qty' =>

$order_packslip_data['products_quantity'],
                                        'name' => $order_packslip_data['products_name'],
                                        'model' => $order_packslip_data['products_model'],
                                        'tax' => $order_packslip_data['products_tax'],
                                        'price' => $order_packslip_data['products_price'],
                                        'final_price' =>

$order_packslip_data['final_price']);

        $subindex = 0;
        $packslip_attributes_query = tep_db_query("select products_options,

products_options_values, options_values_price, price_prefix from " .

TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and

orders_products_id = '" . (int)$order_packslip_data['orders_products_id'] . "'");
        if (tep_db_num_rows($packslip_attributes_query)) {
          while ($packslip_attributes = tep_db_fetch_array($packslip_attributes_query)) {
            $packslip_products[$index]['packslip_attributes'][$subindex] = array('option'

=> $packslip_attributes['products_options'],
                                                                     'value' =>

$packslip_attributes['products_options_values'],
                                                                     'prefix' =>

$packslip_attributes['price_prefix'],
                                                                     'price' =>

$packslip_attributes['options_values_price']);

            $subindex++;
          }
        }
        $index++;
      }
       ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS;

?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL;

?></td>
      </tr>
<?php
$package_num = sizeof($order->products);
$box_num = $l + 1;
      for ($i=0, $n=sizeof($packslip_products); $i<$n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="left">' .

$packslip_products[$i]['qty'] . '&nbsp;x&nbsp;&nbsp;' . $packslip_products[$i]['name'];
           if (isset($packslip_products[$i]['packslip_attributes']) &&

(sizeof($packslip_products[$i]['packslip_attributes']) > 0)) {
        for ($j=0, $k=sizeof($packslip_products[$i]['packslip_attributes']); $j<$k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' .

$packslip_products[$i]['packslip_attributes'][$j]['option'] . ': ' .

$packslip_products[$i]['packslip_attributes'][$j]['value'];
          echo '</i></small></nobr>';
        }
      }
           '        <td class="dataTableContent" valign="top" align="left">' .

$packslip_products[$i]['spacer'];



      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' .

$packslip_products[$i]['spacer'] . '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' .

$packslip_products[$i]['model'] . '</td>' . "\n" .
           '      </tr>' . "\n";
    }
//MVS end
?>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
