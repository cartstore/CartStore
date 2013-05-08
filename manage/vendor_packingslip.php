<?php
  /*
   $Id: vendor_packingslip.php by Craig Garrison Srwww.blucollarsales.comfor MVS V1.0 2006/03/25 JCK/CWG
   $Loc: /catalog/admin/ $
   $Mod: MVS V1.2.3 2009/11/13 kymation $
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com
   
   Copyright (c) 2006 osCommerce
   */
  require_once('includes/application_top.php');
  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $oID = (int)$_GET['oID'];
  $vID = (int)$_GET['vID'];
  include_once(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php
  echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">
<title><?php
  echo TITLE;
?></title><style type="text/css">
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
<?php include '../templates/includes/modules/general_area5.php';?>
<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php
  echo nl2br(STORE_NAME_ADDRESS);
?></td>
          <td class="pageHeading" align="right"> </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2"><?php
  echo tep_draw_separator();
?></td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php
  echo ENTRY_SOLD_TO;
?></b></td>
              </tr>
              <tr>
                <td class="main"><?php
  echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>');
?></td>
              </tr>
              <tr>
                <td><?php
  echo tep_draw_separator('pixel_trans.gif', '1', '5');
?></td>
              </tr>
              <tr>
                <td class="main"><?php
  echo $order->customer['telephone'];
?></td>
              </tr>
              <tr>
                <td class="main"><?php
  echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>';
?></td>
              </tr>
            </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php
  echo ENTRY_SHIP_TO;
?></b></td>
              </tr>
              <tr>
                <td class="main"><?php
  echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>');
?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php
  echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php
  echo ENTRY_PAYMENT_METHOD;
?></b></td>
          <td class="main"><?php
  echo $order->info['payment_method'];
?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php
  echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
  </tr>
  <!-- MVS new table for orders view, only if the order has vendors assigned to it -->
  <tr>
    <?php
  $index = 0;
  $order_packslip_query = tep_db_query("select vendors_id, orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "' and vendors_id = '" . $vID . "'");
  while ($order_packslip_data = tep_db_fetch_array($order_packslip_query)) {
      $packslip_products[$index] = array('qty' => $order_packslip_data['products_quantity'], 'name' => $order_packslip_data['products_name'], 'model' => $order_packslip_data['products_model'], 'tax' => $order_packslip_data['products_tax'], 'price' => $order_packslip_data['products_price'], 'final_price' => $order_packslip_data['final_price']);
      $subindex = 0;
      $packslip_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$order_packslip_data['orders_products_id'] . "'");
      if (tep_db_num_rows($packslip_attributes_query)) {
          while ($packslip_attributes = tep_db_fetch_array($packslip_attributes_query)) {
              $packslip_products[$index]['packslip_attributes'][$subindex] = array('option' => $packslip_attributes['products_options'], 'value' => $packslip_attributes['products_options_values'], 'prefix' => $packslip_attributes['price_prefix'], 'price' => $packslip_attributes['options_values_price']);
              $subindex++;
          }
      }
      $index++;
  }
?>
    <td class="dataTableContent"><?php
  echo $text;
?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" colspan="2" align="left"><?php
  echo TABLE_HEADING_PRODUCTS;
?></td>
          <td class="dataTableHeadingContent"><?php
  echo TABLE_HEADING_PRODUCTS_MODEL;
?></td>
        </tr>
        <?php
  $package_num = sizeof($order->products);
  $box_num = $l + 1;
  for ($i = 0, $n = sizeof($packslip_products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" . '        <td class="dataTableContent" valign="top" align="left">' . $packslip_products[$i]['qty'] . '&nbsp;x&nbsp;&nbsp;' . $packslip_products[$i]['name'];
      if (isset($packslip_products[$i]['packslip_attributes']) && (sizeof($packslip_products[$i]['packslip_attributes']) > 0)) {
          for ($j = 0, $k = sizeof($packslip_products[$i]['packslip_attributes']); $j < $k; $j++) {
              echo '<br><nobr><small>&nbsp;<i> - ' . $packslip_products[$i]['packslip_attributes'][$j]['option'] . ': ' . $packslip_products[$i]['packslip_attributes'][$j]['value'];
              echo '</i></small></nobr>';
          }
      }
      echo '</td>' . '        <td class="dataTableContent" valign="top" align="left">' . $packslip_products[$i]['spacer'] . '</td>' . "\n" . '        <td class="dataTableContent" valign="top">' . $packslip_products[$i]['model'] . '</td>' . "\n" . '      </tr>' . "\n";
  }
?>
      </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php
  require_once(DIR_WS_INCLUDES . 'application_bottom.php');
?>
