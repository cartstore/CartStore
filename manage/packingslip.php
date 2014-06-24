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


            
<!-- body_text //-->
 <div class="container">
    <div class="page-header">
        <h1><?php echo nl2br(STORE_NAME_ADDRESS); ?></h1></div>
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
<table class="table">
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
  <table class="table">
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