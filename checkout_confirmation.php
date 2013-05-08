<?php
  require('includes/application_top.php');


/* One Page Checkout - BEGIN */
  if (ONEPAGE_CHECKOUT_ENABLED == 'True' && !isset($_SESSION['ppe_token']) && !isset($_SESSION['ppe_payerid']) && !isset($_SESSION['ppe_payerstatus']) && $_SESSION['ppe_payerstatus'] != 'verified'){
	        tep_redirect(tep_href_link(FILENAME_CHECKOUT, $_SERVER['QUERY_STRING'], 'SSL'));
  }
/* One Page Checkout - END */

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($_POST['payment'])) $payment = $_POST['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
/* CCGV - BEGIN */
  if ($credit_covers) $payment='credit_covers'; 
  require(DIR_WS_CLASSES . 'order_total.php');
/* CCGV - END */
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

/* CCGV - BEGIN */
  $order_total_modules = new order_total;
  $order_total_modules->collect_posts();
  $order_total_modules->pre_confirmation_check();

// >>> FOR ERROR gv_redeem_code NULL
if (isset($_POST['gv_redeem_code']) && ($_POST['gv_redeem_code'] == null)) {tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));}
// <<< end for error
  
  
  if (isset($_POST['customer_shopping_points_spending']) && USE_REDEEM_SYSTEM == 'true') {
      if (isset($_POST['customer_shopping_points_spending']) && tep_calc_shopping_pvalue($customer_shopping_points_spending) < $order->info['total'] && !is_object($$payment)) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REDEEM_SYSTEM_ERROR_POINTS_NOT), 'SSL'));
      } //if (isset($_POST['customer_shopping_points_spending']) && tep_calc_shopping_pvalue($customer_shopping_points_spending) < $order->info['total'] && !is_object($$payment))
      else {
          if (!tep_session_is_registered('customer_shopping_points_spending'))
              tep_session_register('customer_shopping_points_spending');
      } //else
  } //if (isset($_POST['customer_shopping_points_spending']) && USE_REDEEM_SYSTEM == 'true')
  if (isset($_POST['customer_referred']) && tep_not_null($_POST['customer_referred'])) {
      $valid_referral_query = tep_db_query("SELECT customers_id FROM " . TABLE_CUSTOMERS . " WHERE customers_email_address = '" . $_POST['customer_referred'] . "'");
      $valid_referral = tep_db_fetch_array($valid_referral_query);
      if (!tep_db_num_rows($valid_referral_query)) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
      } //if (!tep_db_num_rows($valid_referral_query))
      if ($_POST['customer_referred'] == $order->customer['email_address']) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_SELF), 'SSL'));
      } //if ($_POST['customer_referred'] == $order->customer['email_address'])
      else {
          $customer_referral = $valid_referral['customers_id'];
          if (!tep_session_is_registered('customer_referral'))
              tep_session_register('customer_referral');
      } //else
  }
/*  if ( ($payment_modules->selected_module != $payment) || ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {*/
//  CCGV + POINTS AND REWARDS MODULE for osc 2.2
if (((is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$credit_covers) && (!$customer_shopping_points_spending) ) || ( (is_object($$payment)) && ($$payment->enabled == false) ) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
      $payment_modules->pre_confirmation_check();
  } //if (is_array($payment_modules->modules))
  if (($total_weight > 0) || (SELECT_VENDOR_SHIPPING == 'true')) {
      include(DIR_WS_CLASSES . 'vendor_shipping.php');
  } //if (($total_weight > 0) || (SELECT_VENDOR_SHIPPING == 'true'))
  elseif (($total_weight > 0) || (SELECT_VENDOR_SHIPPING == 'false')) {
      include(DIR_WS_CLASSES . 'shipping.php');
  } //elseif (($total_weight > 0) || (SELECT_VENDOR_SHIPPING == 'false'))
  $shipping_modules = new shipping($shipping);

/* CCGV - BEGIN */
//  require(DIR_WS_CLASSES . 'order_total.php');
//  $order_total_modules = new order_total;
/* CCGV - END */
//  $order_total_modules->process();
// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
      $check_stock = '';
      for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
          if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
              $attributes = array();
              foreach ($order->products[$i]['attributes'] as $attribute) {
                  $attributes[$attribute['option_id']] = $attribute['value_id'];
              } //foreach ($order->products[$i]['attributes'] as $attribute)
              $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'], $attributes);
          } //if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes']))
          else {
              $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
          } //else
          if ($check_stock[$i]) {
              $any_out_of_stock = true;
          } //if ($check_stock[$i])
      } //for ($i = 0, $n = sizeof($order->products); $i < $n; $i++)
      if ((STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true)) {
          tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
      } //if ((STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true))
  } //if (STOCK_CHECK == 'true')

  if (tep_db_prepare_input($_POST['TermsAgree']) != 'true' and MATC_AT_CHECKOUT != 'false' && $payment_modules->selected_module != 'paypal_express') {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'matcerror=true', 'SSL'));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php
  echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">
<title>
<?php
  echo TITLE;
?>
</title>
<base href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--

function rowOverEffect(object) {

  if (document.checkout_confirmation.elements[object].parentNode.parentNode.className != 'moduleRowSelected') {

    document.checkout_confirmation.elements[object].parentNode.parentNode.className = 'moduleRowOver';

  }

}



function rowOutEffect(object) {

  if (document.checkout_confirmation.elements[object].checked) {

    document.checkout_confirmation.elements[object].parentNode.parentNode.className = 'moduleRowSelected';

  } else {

    document.checkout_confirmation.elements[object].parentNode.parentNode.className = 'infoBoxContents';

  }

}



function checkboxRowEffect(object) {

  document.checkout_confirmation.elements[object].checked = !document.checkout_confirmation.elements[object].checked;

  if(document.checkout_confirmation.elements[object].checked) {

    document.checkout_confirmation.elements[object].parentNode.parentNode.className = 'moduleRowSelected';

  } else {

    document.checkout_confirmation.elements[object].parentNode.parentNode.className = 'moduleRowOver';

  }

}


var win = null;

function NewWindow(mypage,myname,w,h,scroll){

LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;

TopPosition = (screen.height) ? (screen.height-h)/2 : 0;

settings =

'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'

win = window.open(mypage,myname,settings)

}

//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->

        <!-- left_navigation //-->
        <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
        <!-- left_navigation_eof //-->
  
    <!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>

          <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php
  echo HEADING_TITLE;
?></td>
                <td align="right">&nbsp;</td>
              </tr>
            </table>
            <br>

            <div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?>
    <li><?php echo '<a href=" ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span></a></li>';?>

 <li><a href="javascript:;" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span></a></li>


    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></span></li>
  </ul>
</div>


            </td>
        </tr>
        <tr>
          <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <?php
  $selected_time_slot = $_COOKIE['DelvTimeCookie'];
  $del_temp = explode("~", $selected_time_slot);
  $del_date = $del_temp[0];
  $del_slotid = $del_temp[1];
  if ($sendto != false) {
?>
                <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <!-- <tr>

                <td class="main"><?php
      echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>

              </tr> -->
                    <tr>
                      <!-- PWA BOF -->
                      <td class="main"><?php
      echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b>' . (($customer_id > 0 || (defined('PURCHASE_WITHOUT_ACCOUNT_SEPARATE_SHIPPING') && PURCHASE_WITHOUT_ACCOUNT_SEPARATE_SHIPPING == 'yes')) ? ' <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>' : '');
?></td>
                    </tr>
                    <!-- PWA EOF -->
                    <tr>
                      <td class="main"><?php
      echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');
?>
                        <br>
                      </td>
                    </tr>
                    <?php
      if ($order->info['shipping_method']) {
?>
                    <tr>
                      <td class="main"><?php
          echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
          echo $order->info['shipping_method'];
?></td>
                    </tr>
                    <?php
      } //if ($order->info["s_97"])
?>
                  </table></td>
                <?php
  } //if ($sendto != false)
?>
                <td width="<?php
  echo(($sendto != false) ? '70%' : '100%');
?>" valign="top">
<?php echo '<b>' . HEADING_PRODUCTS . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      	<thead>
      		<th style="text-align:center"><?php echo TABLE_HEADING_QTY; ?></th>
      		<th><?php echo TABLE_HEADING_ITEM; ?></th>
      		<th style="text-align:right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
  			<?php if (sizeof($order->info['tax_groups']) > 1) { ?>
      			<th style="text-align:right"><?php echo TABLE_HEADING_TAX; ?></th>
      		<?php } ?>
      		<th style="text-align:right"><?php echo TABLE_HEADING_ITEM_PRICE; ?></th>
      	</thead>
      	<tbody>
<?php
      for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
          echo '          <tr>' . "\n" . '            
          <td class="main" align="center" valign="top" width="10%">' . $order->products[$i]['qty'] . '</td>'
           . "\n" . '<td class="main" valign="top">' . $order->products[$i]['name'];
          if (STOCK_CHECK == 'true') {
              echo $check_stock[$i];
          } //if (STOCK_CHECK == 'true')
          if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0)) {
              for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
                  echo '<br><nobr><small>&nbsp;<i> ' . $order->products[$i]['attributes'][$j]['option'] . ' - ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
              } //for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++)
          } //if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0))
          echo '</td>' . "\n";
          echo '            <td width="20%" class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], 1) . '</td>' . "\n";
          if (sizeof($order->info['tax_groups']) > 1)
              echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
          echo '            <td width="20%" class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" . '          </tr>' . "\n";
      } //for ($i = 0, $n = sizeof($order->products); $i < $n; $i++)
?>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td class="main"><b>
            <?php
      echo HEADING_BILLING_INFORMATION;
?>
            </b></td>
        </tr>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <!-- <tr>

                <td class="main"><?php
      echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>

              </tr> -->
                    <tr>
                      <!-- PWA BOF -->
                      <td class="main"><?php
      echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a class="general_link" href="' . (($customer_id == 0) ? tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL') : tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL')) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>
                    </tr>
                    <!-- PWA EOF -->
                    <tr>
                      <td class="main"><?php
      echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>');
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
      echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php
      echo $order->info['payment_method'];
?></td>
                    </tr>
                  </table></td>
                <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
                    <?php
      if (MODULE_ORDER_TOTAL_INSTALLED) {
          $order_total_modules->process();
          echo $order_total_modules->output();
      } //if (MODULE_ORDER_TOTAL_INSTALLED)
?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
      echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
// Start - CREDIT CLASS Gift Voucher Contribution
  echo tep_draw_hidden_field('gv_redeem_code', $_POST['gv_redeem_code']); 
// End - CREDIT CLASS Gift Voucher Contribution
      if (is_array($payment_modules->modules)) {
          if ($confirmation = $payment_modules->confirmation()) {
?>
        <tr>
          <td><?php
              echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td class="main"><b>
            <?php
              echo HEADING_PAYMENT_INFORMATION;
?>
            </b></td>
        </tr>
        <tr>
          <td><?php
              echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main" colspan="4"><?php
              echo $confirmation['title'];
?></td>
                    </tr>
                    <?php
              for ($i = 0, $n = sizeof($confirmation['fields']); $i < $n; $i++) {
?>
                    <tr>
                      <td width="10"><?php
                  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <td class="main"><?php
                  echo $confirmation['fields'][$i]['title'];
?></td>
                      <td width="10"><?php
                  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <td class="main"><?php
                  echo $confirmation['fields'][$i]['field'];
?></td>
                    </tr>
                    <?php
              } //for ($i = 0, $n = sizeof($confirmation["s_187"]); $i < $n; $i++)
?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <?php
          } //if ($confirmation = $payment_modules->confirmation())
      } //if (is_array($payment_modules->modules))
?>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <?php
      if (tep_not_null($order->info['comments'])) {
?>
        <tr>
          <td class="main"><?php
          echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a class="general_link" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>';
?></td>
        </tr>
        <tr>
          <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php
          echo (tep_output_string($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']);
?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <?php
      } //if (tep_not_null($order->info["s_201"]))
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" class="main"><?php
      if (is_array($payment_modules->modules)) {
          echo $payment_modules->process_button();
      } //if (is_array($payment_modules->modules))
?>
                    </td></tr>
                  </table></td>
              </tr>
              <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2"  class="infoBox">
                    <tr class="infoBoxContents">
                      <td align="right"><?php
      echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER);
?></td>
                    </tr>
                  </table></td>
              </tr>

              </form>




            </table></td>
        </tr>

        <tr>
        <td>
        <hr>
<div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?>
    <li><?php echo '<a href=" ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="ui-state-default ui-corner-all"><span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span></a></li>';?>

 <li><a href="javascript:;" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span></a></li>


    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></span></li>
  </ul>
</div>



</td>


            </table>




            </td>
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
