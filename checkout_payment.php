<?php
  require('includes/application_top.php');
  if (SELECT_VENDOR_SHIPPING == 'true') {
      if (!is_array($shipping['vendor']) || count($shipping['vendor']) != count($cart->vendor_shipping)) {
      } //if (!is_array($shipping['vendor']) || count($shipping['vendor']) != count($cart->vendor_shipping))

  } //if (SELECT_VENDOR_SHIPPING == 'true')
  $value = 'cartstorenet';
  setcookie("TestCookie", $value);
  setcookie("TestCookie", $value, time() + 3600);
  if (tep_session_is_registered('cot_gv'))
      tep_session_unregister('cot_gv');
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

  } //if (isset($cart->cartID) && tep_session_is_registered('cartID'))
  if (tep_session_is_registered('credit_covers'))
      tep_session_unregister('credit_covers');
  if (tep_session_is_registered('cot_gv'))
      tep_session_unregister('cot_gv');
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
  } //if (!tep_session_is_registered('billto'))

  else {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      $check_address = tep_db_fetch_array($check_address_query);
      if ($check_address['total'] != '1') {
          $billto = $customer_default_address_id;
          if (tep_session_is_registered('payment'))
              tep_session_unregister('payment');
      } //if ($check_address['total'] != '1')

  } //else
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->clear_posts();
  if (!tep_session_is_registered('comments'))
      tep_session_register('comments');
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  $total_count = $cart->count_contents_virtual();
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
?>
<?php
  echo $payment_modules->javascript_validation($coversAll);
?>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">
<tr>
  <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">
      <!-- left_navigation //-->
      <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      <!-- left_navigation_eof //-->
    </table></td>
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
?>
      <div class="ui-widget">
        <div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> <strong> Alert:</strong> Payment Error <br>
            <br>
            <?php
      echo $_GET['error_message'];
?>
          </p>
        </div>
      </div>
      <?php
  } //if (isset($_GET["s_69"]))
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
  } //if (isset($_GET["s_71"]))
?>
<?php
if($HTTP_GET_VARS['matcerror'] == 'true'){
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
        <?php
  $selected_time_slot = $_COOKIE['DelvTimeCookie'];
  $del_temp = explode("~", $selected_time_slot);
  $del_date = $del_temp[0];
  $del_slotid = $del_temp[1];
  for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      $pi_query = tep_db_query("SELECT products_image FROM " . TABLE_PRODUCTS . " WHERE products_id=" . tep_get_prid($order->products[$i]['id']));
      $pi = tep_db_fetch_array($pi_query);
      echo '          <tr>' . "\n" . '            <td width="10%" class="main" align="right" valign="top" width="30" nowrap>' . $order->products[$i]['qty'] . ' x&nbsp;</td>' . "\n" . '            <td class="main" valign="top">&nbsp;' . $order->products[$i]['name'] . '             <br><div class="checkout_pimage">' . tep_image(DIR_WS_IMAGES . $pi['products_image'], $order->products[$i]['name'], '45', '') . '</div>';
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
          echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '% </td>' . "\n";
      echo '            <td width="30%"class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . ' </td>' . "\n" . '          </tr>' . "\n";
  } //for ($i = 0, $n = sizeof($order->products); $i < $n; $i++)

?>
      </table>
      <table border="0" cellspacing="0" cellpadding="0">
        <?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
      echo $order_total_modules->output();
  } //if (MODULE_ORDER_TOTAL_INSTALLED)

?>
      </table>
      <h3><b>
        <?php
  echo TITLE_BILLING_ADDRESS;
?>
        </b> </h3>
      <?php
  echo '<a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="ui-icon ui-icon-pencil" style="float:left";></span>Change Address</a>';
?>
      <!-- PWA BOF -->
      <br>
      <!-- PWA EOF -->
      <?php
  echo tep_address_label($customer_id, $billto, true, ' ', '<br>');
?>
      <br>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
  echo $order_total_modules->credit_selection();
?>
      </table>
<?php
  echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"');
?>      <table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
        <tr class="infoBoxContents">

        <td><?php
  $selection = $payment_modules->selection();
  for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
      if (($selection[$i]['id'] == 'googlecheckout') || ($selection[$i]['id'] == 'checkout_by_amazon')) {
          array_splice($selection, $i, 1);
          $i--;
      } //if (($selection[$i]['id'] == 'googlecheckout') || ($selection[$i]['id'] == 'checkout_by_amazon'))

  } //for ($i = 0, $n = sizeof($selection); $i < $n; $i++)
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
          <h3 class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-zoomin" style="float:left";></span>
            <?php
?>
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
          <div class="payment_module_output" id="<?php
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

      if (tep_session_is_registered('customer_id')) {
          if ($gv_result['amount'] > 0) {
              echo '' . $gv_result['text'];
              echo $order_total_modules->sub_credit_selection();
          } //if ($gv_result['amount'] > 0)

      } //if (tep_session_is_registered('customer_id'))
?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
          <?php
      echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE,'id="TheSubmitButton"');
?>
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
          </div></td>
        </tr>

      </table>
      </form>
      <!-- body_text_eof //-->
    <td width="<?php
      echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
      echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">
          <!-- right_navigation //-->
          <?php
      require(DIR_WS_INCLUDES . 'column_right.php');
?>
          <!-- right_navigation_eof //-->
        </table></td>
    </tr>
  </table>
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