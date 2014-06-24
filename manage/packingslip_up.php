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

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->

<table border="0" width="100%" cellspacing="0" cellpadding="2">

  <tr>

    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>

        <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'oscommerce.png', 'CartStore', '204', '50'); ?></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">

      <tr>

        <td colspan="2"><?php echo tep_draw_separator(); ?></td>

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

  <tr>

    <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

  </tr>

  <tr>

    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr class="dataTableHeadingRow">

        <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>

        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>

      </tr>

<?php

    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

      echo '      <tr class="dataTableRow">' . "\n" .

           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .

           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];



      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {

        for ($j=0, $k=sizeof($order->products[$i]['attributes']); $j<$k; $j++) {

          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];

          echo '</i></small></nobr>';

        }

      }



      echo '        </td>' . "\n" .

           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .

           '      </tr>' . "\n";

    }

?>

    </table></td>

  </tr>

</table>

<!-- body_text_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

