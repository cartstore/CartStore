<?php
/*
  $Id: packingslip.php,v 1.1.1.1 2005/05/19 16:59:37 apike Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  ******************************************
  RMA Packing Slip Mod
  Created by Brett Stone-Gross
  brettsg@serialio.com
  8/19/2005
  ******************************************
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
  $rID = (int)$oID;
  $return_query = tep_db_query("select order_id from " . TABLE_RETURNS . " where returns_id = '" . (int)$oID . "'");
  $orderID = tep_db_fetch_array($return_query);
  $oID = $orderID['order_id'];
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");

  include(DIR_WS_CLASSES . 'returns.php');
  $order = new order($rID);

  /*Serialio.com Mod: Get shipping method*/
  $result = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' AND class = 'ot_shipping'");
  $method = mysql_fetch_array($result);
  $ship_method = $method[title];
  //Crop trailing colons off of title
  if ($ship_method{strlen($ship_method) - 1} == ':') //if the last character is a colon
  	{
  	$ship_method{strlen($ship_method) - 1} = ' '; //get rid of it.
  	}
  /*End Serialio.com Mod*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main"><b><?php echo nl2br(STORE_NAME_ADDRESS); ?></b></td>
        <td class="main" align="right"><b>
        <?php
        			echo "<p>Packing Slip for RMA #" . $order->info['rma_value']. "<br />";
        ?>

        </td>
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
            <td class="main"><b><?php echo "Ship To:"; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo $order->delivery['shipping_phone']; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo "Sold To:"; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php  echo $order->customer['telephone']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                 <td class="main" valign=top><b><?php echo "Comments:"; ?></b></td><td class=main><?php echo nl2br($order->info['comments']); ?></td>
               </tr>
              <tr>
                <?php
			          $order_status_query = tep_db_query("SELECT returns_status_name FROM " . TABLE_RETURNS_STATUS . " where returns_status_id = '" . $order->info['orders_status'] . "'");
			          $order_status  = tep_db_fetch_array($order_status_query);
				?>
                <td class="main" width=25%><b><?php echo "Status:"; ?></b></td><td width=65% class=main><?php echo $order_status['returns_status_name']; ?></td>
              </tr>
              <tr>
                <td class="main" width=25% valign=top><b><?php echo "Refund Method:"; ?></b></td><td width=65% class=main><?php echo $order->info['department']; ?></td>
              </tr>
      <?php /*End Serialio.com Mod*/ ?>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="1" width="95%" cellspacing="0" cellpadding="3">
      <tr>
      	<td class="main"><?php echo "Qty"; ?></td>
        <td class="main"><?php echo "Products"; ?></td>
        <td class="main"><?php echo "Products Model"; ?></td>
      </tr>
<?php
	   $rma_num_query = tep_db_query("SELECT returns_id FROM " . TABLE_RETURNS . " where rma_value='" . $order->info['rma_value'] ."'");
       while( $rma_num = tep_db_fetch_array($rma_num_query) ){
		$rma_query = tep_db_query("select * from returns_products_data where returns_id='" . $rma_num['returns_id'] ."'");
		$rma_data = tep_db_fetch_array($rma_query);

      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="left">' . $rma_data['products_quantity'] . '&nbsp;</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $rma_data['products_name'];



      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" align="left">' . $rma_data['products_model'] . '</td>' . "\n";
          }
?>

    </table></td>
  </tr>
</table>
<?php
/*Serialio.com Mod: XML-esque tags for custom scripting*/
print "
<!-- XML-ish tags for custom scripts
<Ship Company>{$order->delivery[company]}</Ship Company>
<Ship Name>{$order->delivery[name]}</Ship Name>
<Ship Adr1>{$order->delivery[street_address]}</Ship Adr1>
<Ship Adr2>{$order->delivery[suburb]}</Ship Adr2>
<Ship City>{$order->delivery[city]}</Ship City>
<Ship State>{$order->delivery[state]}</Ship State>
<Ship Postal>{$order->delivery[postcode]}</Ship Postal>
<Ship Country>{$order->delivery[country]}</Ship Country>
<Ship Phone>{$order->customer[telephone]}</Ship Phone>
<Ship Method>$ship_method</Ship Method>
-->
";
/*End Serialio.com Mod*/
?>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
