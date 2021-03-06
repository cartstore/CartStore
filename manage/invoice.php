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



<!DOCTYPE html>
 <html class=" js no-touch localstorage svg">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>CartStore Administration</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
 		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
 		<link href="./templates/responsive-red/assets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css">
 
		<link href="./templates/responsive-red/assets/bootstrap.css" media="all" rel="stylesheet" type="text/css">

 	  

		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
				<link href="//codeorigin.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css" rel="stylesheet">

		 

	</head>
	<body class="contrast-red " style="">
<div class="container">

 <div class="page-header">
        <h1>

            <?php echo nl2br(STORE_NAME_ADDRESS); ?>
            
            </h1></div>
            
       <?php //MVS Start this updates to STORE_NAME ?>
      
            
<?php //MVS End ?>
     
            
            
            
            <table class="table">
      <tr>
        <td colspan="2"> 
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
    </table>
    <table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $order->info['payment_method']; ?></td>
      </tr>
    </table>
    
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
  <?php // MVS start MVS Invoice, only if the the data is in the "orders_shipping" table ?>
  <?php if (tep_not_null($order->orders_shipping_id)) {  ?>
         </td></tr><tr>
              <td><table border="1" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_PRODUCTS_VENDOR; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_VENDORS_SHIP; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_SHIPPING_METHOD; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_SHIPPING_COST; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent"><?php echo

TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent"><?php echo

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
      '            <td class="dataTableContent" valign="center">' .

tep_display_tax_value($order->products[$l]['orders_products'][$i]['tax']) . '%</td>' . "\n"

.
      '            <td class="dataTableContent" valign="center"><b>' .

$currencies->format($order->products[$l]['orders_products'][$i]['final_price'], true,

$order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" valign="center"><b>' .

$currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'],

$order->products[$l]['orders_products'][$i]['tax']), true, $order->info['currency'],

$order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" valign="center"><b>' .

$currencies->format($order->products[$l]['orders_products'][$i]['final_price'] *

$order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'],

$order->info['currency_value']) . '</b></td>' . "\n" .
      '            <td class="dataTableContent" valign="center"><b>' .

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
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
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
      echo '        <td class="dataTableContent" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
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
        </table> 
 </div>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
	<!-- / jquery [required] -->
		<script src="./templates/responsive-red/assets/jquery.min.js" type="text/javascript"></script>
		<!-- / jquery mobile (for touch events) -->
		<script src="./templates/responsive-red/assets/jquery.mobile.custom.min.js" type="text/javascript"></script>
		<!-- / jquery migrate (for compatibility with new jquery) [required] -->
		<script src="./templates/responsive-red/assets/jquery-migrate.min.js" type="text/javascript"></script>
		<!-- / jquery ui -->
		<script src="./templates/responsive-red/assets/jquery-ui.min.js" type="text/javascript"></script>
		<!-- / jQuery UI Touch Punch -->
		<script src="./templates/responsive-red/assets/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
		<!-- / bootstrap [required] -->
		<script src="./templates/responsive-red/assets/bootstrap.js" type="text/javascript"></script>
		<!-- / modernizr -->
		<script src="./templates/responsive-red/assets/modernizr.min.js" type="text/javascript"></script>
		<!-- / retina -->
		<script src="./templates/responsive-red/assets/retina.js" type="text/javascript"></script>
		<!-- / theme file [required] -->
		<script src="./templates/responsive-red/assets/theme.js" type="text/javascript"></script>
 		<!-- / END - page related files and scripts [optional] -->
 		
 		
 		
 		<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery.init.local.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckfinder/ckfinder.js"></script>

<script language="javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>includes/general.js"></script>



<script language="javascript" type="text/javascript">
<!--
function popUp(url) {
	var winHandle = randomString();
	newwindow=window.open(url,winHandle,'height=800,width=1000');
}

function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}

jQuery("form[name='search'] .dropdown-menu a").click(function(){
	$("form[name='search'] .dropdown-menu").find("a i").remove();
	$(this).append('<i class="fa fa-check"></i>');
	$("form[name='search']").attr('action',$(this).attr('data-target'));
});
// -->
</script>

	</body>
</html>