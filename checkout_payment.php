<?php
  require('includes/application_top.php');
  if (SELECT_VENDOR_SHIPPING == 'true') {
      if (!is_array($shipping['vendor']) || count($shipping['vendor']) != count($cart->vendor_shipping)) {
      } //if (!is_array($shipping['vendor']) || count($shipping['vendor']) != count($cart->vendor_shipping))

  } //if (SELECT_VENDOR_SHIPPING == 'true')
  elseif (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      tep_redirect(tep_href_link(FILENAME_CHECKOUT, $_SERVER['QUERY_STRING'], 'SSL'));
  }

  $value = 'cartstorenet';
  setcookie("TestCookie", $value);
  setcookie("TestCookie", $value, time() + 3600);
  if (!tep_session_is_registered('customer_id')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  } //if (!tep_session_is_registered('customer_id'))

  if ($cart->count_contents() < 1) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  } //if ($cart->count_contents() < 1)

  if (!tep_session_is_registered('shipping')) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  } //if (!tep_session_is_registered('shipping'))

  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
      if ($cart->cartID != $cartID) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      } //if ($cart->cartID != $cartID)
  }
/* CCGV - BEGIN */
if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
if(tep_session_is_registered('cot_gv')) tep_session_unregister('cot_gv');
/* CCGV - END */

  if ((STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true')) {
      $products = $cart->get_products();
      $any_out_of_stock = 0;
      for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
          if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
              $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']);
          } //if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']))

          else {
              $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
          } //else

          if ($stock_check)
              $any_out_of_stock = 1;
      } //for ($i = 0, $n = sizeof($products); $i < $n; $i++)

      if ($any_out_of_stock == 1) {
          tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
          break;
      } //if ($any_out_of_stock == 1)

  } //if ((STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true'))
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);
  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  } else {
// verify the selected billing address
    if ( (is_array($billto) && empty($billto)) || is_numeric($billto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] != '1') {
        $billto = $customer_default_address_id;
        if (tep_session_is_registered('payment')) tep_session_unregister('payment');
      }
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
/* CCGV - BEGIN */
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->clear_posts();
/* CCGV - END */
  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (isset($_POST['comments']) && tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
/* CCGV - BEGIN */
$total_count = $cart->count_contents_virtual();
/* CCGV - END */
// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->


      <!-- left_navigation //-->
      <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      <!-- left_navigation_eof //-->
  
  <!-- body_text //-->
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>

    <td>
      <h1>
        <?php
  echo HEADING_TITLE;
?>
      </h1>
      <br>
      <div id="module-product">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
          <li>
            <?php
  echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';
?>
          <li><a href="javascript:;" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>2.
            <?php
  echo CHECKOUT_BAR_PAYMENT;
?>
            </span></a></li>
          <li><span>3.
            <?php
  echo CHECKOUT_BAR_CONFIRMATION;
?>
            <br>
            4.
            <?php
  echo CHECKOUT_BAR_FINISHED;
?>
            </span></li>
        </ul>
      </div>
      <?php
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
?>
      <div class="ui-widget">
        <div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> <strong> Alert:</strong>
            <?php
      echo tep_output_string_protected($error['title']);
?>
            <?php
      echo tep_output_string_protected($error['error']);
?>
          </p>
        </div>
      </div>
      <?php
  } //if (isset($_GET["s_64"]) && is_object(${$_GET["s_65"]}) && ($error = ${$_GET["s_66"]}->get_error()))
?>
<?php
  if (isset($_GET['error_message'])) {
      $error = $_GET['error_message'];
?>
      <div class="ui-widget">
        <div style="margin-top: 20px; padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span> <strong> Alert:</strong> The credit card processor said the following:  "
            <?php
      echo tep_output_string_protected($error);
?>
          </p>
        </div>
      </div>
    <?php if (!empty($error['error'])) { ?>
      <div class="ui-widget">
        <div style="margin-top: 20px; padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span> <strong> Alert:</strong> There was a payment error. This could be a problem with your address information or the card number entered incorrectly. If you think this is incorrect, then please return to the shopping cart and re-enter your information. If your attempt is declined again, please contact your card issuer and try another card.
            <?php
      echo tep_output_string_protected($error['error']);
?>
          </p>
        </div>
      </div>
<?php
    }
  } //if (isset($_GET["s_71"]))
?>
<?php
if($_GET['matcerror'] == 'true'){
?>
      <div class="ui-widget">
        <div style="margin-top: 20px; padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span> <strong> Alert:</strong> There was a payment error. This could be a problem with your address information or the card number entered incorrectly. If you think this is incorrect, then please return to the shopping cart and re-enter your information. If your attempt is declined again, please contact your card issuer and try another card.
            <?php echo tep_output_string_protected(MATC_ERROR); ?>
          </p>
        </div>
      </div>
<?php } ?>
      <h3><b>
        <?php
  echo HEADING_PRODUCTS;
?>
        </b> </h3>
      <?php
  echo ' <a class="general_link" href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
      	<thead>
      		<th style="text-align:center"><?php echo TABLE_HEADING_QTY; ?></th>
      		<th><?php echo TABLE_HEADING_ITEM; ?></th>
      		<th style="text-align:right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
      		<th style="text-align:right"><?php echo TABLE_HEADING_ITEM_PRICE; ?></th>
      	</thead>
      	<tbody>
        <?php
  $selected_time_slot = $_COOKIE['DelvTimeCookie'];
  $del_temp = explode("~", $selected_time_slot);
  $del_date = $del_temp[0];
  $del_slotid = $del_temp[1];
  for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      $pi_query = tep_db_query("SELECT products_image FROM " . TABLE_PRODUCTS . " WHERE products_id=" . tep_get_prid($order->products[$i]['id']));
      $pi = tep_db_fetch_array($pi_query);
      echo '          <tr>' . "\n" . '
      	<td width="10%" class="main" style="text-align:center; vertical-align: top">' . $order->products[$i]['qty'] . '</td>
		<td class="main" valign="top" width="50%">&nbsp;' . $order->products[$i]['name'] . '             <br><div class="checkout_pimage">' . tep_image(DIR_WS_IMAGES . $pi['products_image'], $order->products[$i]['name'], '45', '') . '</div>';
      if (STOCK_CHECK == 'true') {
          echo tep_check_stock(tep_get_prid($order->products[$i]['id']), $order->products[$i]['qty']);
      } //if (STOCK_CHECK == 'true')

      if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0)) {
          for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
              echo '<br><nobr><small> <i>' . $order->products[$i]['attributes'][$j]['option'] . ' - ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
          } //for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++)

      } //if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0))
      echo '<hr></td>' . "\n";
      if (sizeof($order->info['tax_groups']) > 1)
          echo '        <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '% </td>' . "\n";
      echo '            <td width="20%"class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], 1) . ' </td>' . "\n";
      echo '            <td width="20%"class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . ' </td>' . "\n" . '          </tr>' . "\n";
  } //for ($i = 0, $n = sizeof($order->products); $i < $n; $i++)

?>
		<tr>
			<td colspan="4" valign="top" align="right">
				<table border="0" cellspacing="0" cellpadding="2">
<?php
if (MODULE_ORDER_TOTAL_INSTALLED) $temp=$order_total_modules->process();

if (MODULE_ORDER_TOTAL_INSTALLED)
	{
	echo $order_total_modules->output();
	}
?>
				</table>
			</td>
		</tr>
		</tbody>
      </table>
<script language="javascript"><!--
var selected;
<!-- CCGV - BEGIN -->
var submitter = null;
function submitFunction()
	{
	submitter = 1;
	}
<!-- CCGV - END -->
function selectRowEffect(object, buttonSelect) {
/* CCGV - BEGIN */
	if (!document.checkout_payment.payment[0].disabled)
		{
/* CCGV - END */
		if (!selected)
			{
			if (document.getElementById)
				{
				selected = document.getElementById('defaultSelected');
				}
			else
				{
				selected = document.all['defaultSelected'];
				}
			}
		if (selected) selected.className = 'moduleRow';
		object.className = 'moduleRowSelected';
		selected = object;
		if (document.checkout_payment.payment[0])
			{
			document.checkout_payment.payment[buttonSelect].checked=true;
			}
		else
			{
			document.checkout_payment.payment.checked=true;
			}
/* CCGV - BEGIN */
		}
/* CCGV - END */
	}
function rowOverEffect(object)
	{
	if (object.className == 'moduleRow') object.className = 'moduleRowOver';
	}
function rowOutEffect(object)
	{
	if (object.className == 'moduleRowOver') object.className = 'moduleRow';
	}
<?php
/* CCGV - BEGIN */
$temp=$temp[count($temp)-1];
$temp=$temp['value'];
$gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
$gv_result = tep_db_fetch_array($gv_query);

if ($gv_result['amount']>=$temp)
	{
	$coversAll=true;
?>
	function clearRadeos(){
		
		document.checkout_payment.cot_gv.checked=!document.checkout_payment.cot_gv.checked;
		for (counter = 0; counter < document.checkout_payment.payment.length; counter++)
			{
			if (document.checkout_payment.cot_gv.checked)
				{
				document.checkout_payment.payment[counter].checked = false;
				document.checkout_payment.payment[counter].disabled=true;
				}
			else
				{
				document.checkout_payment.payment[counter].disabled=false;
				}
			}
		}
<?php
	}
else
	{ 
	$coversAll=false;?>
	function clearRadeos()
		{
		document.checkout_payment.cot_gv.checked=!document.checkout_payment.cot_gv.checked;
		}
<?php
	}
?>
//--></script>

<?php echo $payment_modules->javascript_validation($coversAll); ?>
<!-- CCGV - END -->
<!-- Start - CREDIT CLASS Gift Voucher Contribution -->
<?php // echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"'); ?>
<!-- End - CREDIT CLASS Gift Voucher Contribution -->
<div class="contentContainer">
<?php
/* CCGV - BEGIN */

	echo '<div class="contentText">' . $order_total_modules->credit_selection() . '</div>';

  	if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
?>
        <p class="messageStackError"><?php echo tep_output_string_protected($error['error']); ?></p>
<?php
 	}
//	echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"');
/* CCGV - END */
    ?><br><p></p>
      <h3><b>
        <?php
  echo TITLE_BILLING_ADDRESS;
?>
        </b> </h3>
        
        
        <table><tr>
        	<td> <?php
  echo tep_address_label($customer_id, $billto, true, ' ', '<br>');
?></td>
        	<td>  <?php
  echo '<a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="ui-icon ui-icon-gear" style="float:left";></span>Change Address</a>';
?></td>
        </tr>
        	</table>
    
      <!-- PWA BOF -->
      
      <!-- PWA EOF -->
     
      
<?php
 echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"');
?>      <table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
        <tr class="infoBoxContents">

        <td><?php
  $selection = $payment_modules->selection();
  // *** BEGIN GOOGLE CHECKOUT ***
  // Skips Google Checkout as a payment option on the payments page since that option
  // is provided in the checkout page.
  for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
    if ($selection[$i]['id'] == 'googlecheckout') {
      array_splice($selection, $i, 1);
      break;
    }
  }
  // *** END GOOGLE CHECKOUT ***
  if (sizeof($selection) > 1) {
?>
          <?php
      echo TEXT_SELECT_PAYMENT_METHOD;
?>
          <br>
          <h3><b>
            <?php
      echo TITLE_PLEASE_SELECT;
?>
            </b> </h3>
          <?php
      } else
      {
?>
          <?php
          echo TEXT_ENTER_PAYMENT_INFORMATION;
?>
          <?php
      }
      echo '

                      </td>
                      </tr>
                      </table>

<style type="text/css">
<!--

.demo .inputbox {
  display: block;
  clear: both;
}



.payment_module_output {
  padding: 10px;
  font-weight: bolder;
}
.ui-state-default {
  margin-bottom: 2px;

}



-->
</style>


                        <div class="demo">
';
      $radio_buttons = 0;
	  if (sizeof($selection) > 0){
          		echo '<script type="text/javascript">
 jQuery(document).ready(function(){
   jQuery(".payment_module_output").css("display","none");
   jQuery("p input[type=\'radio\']").click(function(){
     jQuery(".payment_module_output").slideUp();
     if (jQuery(this).is(":checked")){
        jQuery(this).parent(\'p\').next(\'div .payment_module_output\').slideDown();
     }
   })
 });
</script>';
	  }
      for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
?>
          <?php
          if (($selection[$i]['id'] == $payment) || ($n == 1)) {
              echo '' . "";
          } //if (($selection[$i]['id'] == $payment) || ($n == 1))

          else {
              echo '' . "";
          } //else

?>
          <h3 class="ui-state-default ui-corner-all ui-li ui-li-divider ui-bar-b ui-first-child">
      
            <?php
          if (sizeof($selection) > 1) {
              echo tep_draw_radio_field('payment', $selection[$i]['id']);
			            	echo $selection[$i]['module'];
			  
              
          } //if (sizeof($selection) > 1)

          else {
              echo tep_draw_hidden_field('payment', $selection[$i]['id']);
			            	 echo $selection[$i]['module'];
			  
             
          } //else

?>
          </h3>
          <?php
          if (isset($selection[$i]['error'])) {
?>
          <?php
              echo $selection[$i]['error'];
?>
          <?php
          } //if (isset($selection[$i]["s_146"]))
          elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>
          <div class="payment_module_outputXXX" style="display: block;padding:15px;" id="<?php
              echo $selection[$i]['id'];
?>">
            <?php
              for ($j = 0, $n2 = sizeof($selection[$i]['fields']); $j < $n2; $j++) {
?>
            <?php
                  echo $selection[$i]['fields'][$j]['title'];
?>
            <?php
                  echo $selection[$i]['fields'][$j]['field'];
?>
            <?php
              } //for ($j = 0, $n2 = sizeof($selection[$i]["s_151"]); $j < $n2; $j++)
?>
          </div>
          <?php
          } //elseif (isset($selection[$i]["s_148"]) && is_array($selection[$i]["s_149"]))
?>
          <?php
          $radio_buttons++;
      } //for ($i = 0, $n = sizeof($selection); $i < $n; $i++)

?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	if (tep_session_is_registered('customer_id')){
		if ($gv_result['amount'] > 0){
			//(fnzb) Store credit displayed with checkbox
			echo '<tr><td><table width="100%">
			             <tr><td colspan="4">' . VOUCHER_BALANCE_TITLE  . '</td></tr>';
			echo '<tr><td>' . tep_draw_separator('pixel_trans.gif','10','1') . '</td>
			              <td>' . VOUCHER_BALANCE . '&nbsp' . $currencies->format($gv_result['amount']) . '</td>
			              <td colspan="2">' . tep_draw_separator('pixel_trans.gif','35','1') . '</td>
			            </tr>';
            if ($order->info['total'] > $gv_result['amount']){
			echo '<tr><td>' . tep_draw_separator('pixel_trans.gif','10','1') . '</td>
                           <td colspan="2">' .MODULE_ORDER_TOTAL_GV_REQUIRE_PAYMENT . '</td>
                           <td>' . tep_draw_separator('pixel_trans.gif','25','1') . '</td>
                         </tr>';
            }						
			echo '<tr><td>' . tep_draw_separator('pixel_trans.gif','10','1') . '</td>
                           <td>' .MODULE_ORDER_TOTAL_GV_USER_PROMPT . '</td>
                           <td align="right">' . $order_total_modules->sub_credit_selection() . '</td>
                           <td>' . tep_draw_separator('pixel_trans.gif','25','1') . '</td>
                         </tr>';
			echo '</table></td></tr>';
		}
	}
/* CCGV - END */
?>          	
            <!-- Points/Rewards Module V2.00 Redeemption box bof -->
            <?php
      if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
          echo points_selection();
          if (tep_not_null(USE_REFERRAL_SYSTEM)) {
              echo referral_input();
          } //if (tep_not_null(USE_REFERRAL_SYSTEM))
           
      } //if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true'))
?>
            <!-- Points/Rewards Module V2.00 Redeemption box eof -->
            </tr>

          </table>
          </div>
          
          <div class="hide_comments"><b>
            <?php
      echo TABLE_HEADING_COMMENTS;
?>
            </b></div>
          <div class="hide_comments">
            <?php
      echo tep_draw_textarea_field2('comments', 'soft', '40', '5');
?>
          </div>
<?php
if(MATC_AT_CHECKOUT != 'false'){
   require(DIR_WS_MODULES . 'matc.php');
}
?>
          <br>
          <br>
            <div id="matc-submit-button">
            <?php  echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE,'id="TheSubmitButton"'); ?>
           </div>
          <hr>
          <div id="module-product">
            <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
              <li>
                <?php
      echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';
?>
              <li><a href="javascript:;" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>2.
                <?php
      echo CHECKOUT_BAR_PAYMENT;
?>
                </span></a></li>
              <li><span>3.
                <?php
      echo CHECKOUT_BAR_CONFIRMATION;
?>
                <br>
                4.
                <?php
      echo CHECKOUT_BAR_FINISHED;
?>
                </span></li>
            </ul>
          </div></form></td>
        </tr>

      </table>
      
      <!-- body_text_eof //-->
   
          <!-- right_navigation //-->
          <?php
      require(DIR_WS_INCLUDES . 'column_right.php');
?>
          <!-- right_navigation_eof //-->
      
  <!-- body_eof //-->
  <!-- footer //-->
  <?php
      require(DIR_WS_INCLUDES . 'footer.php');
?>
  <!-- footer_eof //-->
</body>
</html>
<?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
