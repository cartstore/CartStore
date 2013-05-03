<!-- shopping_cart //-->
<?php
  if ($cart->count_contents() > 0) {
?>

<div class="module special">
  <div>
    <div>
      <div>
        <h3>SHOPPING CART</h3>
        <div class="box">
          <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);
      new infoBoxHeading($info_box_contents, false, true, tep_href_link(FILENAME_SHOPPING_CART));
// Start - CREDIT CLASS Gift Voucher Contribution
// CREDIT CLASS script moved for compatibility with STS
//$cart_contents_string = '';
  $cart_contents_string ="
<script language=\"javascript\">
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>";
// End - CREDIT CLASS Gift Voucher Contribution
      if ($cart->count_contents() > 0) {
          $cart_contents_string = '';
          $products = $cart->get_products();
          for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
              $cart_contents_string .= '';
              if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
                  $cart_contents_string .= '';
              } else {
                  $cart_contents_string .= '';
              }
              $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
              if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
                  $cart_contents_string .= '';
              } else {
                  $cart_contents_string .= '';
              }
              $cart_contents_string .= $products[$i]['name'] . '</span></a><br /><br />

';
              if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
                  tep_session_unregister('new_products_id_in_cart');
              }
          }
          $cart_contents_string .= '';
      } else {
          $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
      }
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $cart_contents_string);
      if ($cart->count_contents() > 0) {
          $info_box_contents[] = array('text' => tep_draw_separator());
          $info_box_contents[] = array('align' => '', 'text' => $currencies->format($cart->show_total()));
      }
// Start - CREDIT CLASS Gift Voucher Contribution
  if (tep_session_is_registered('customer_id')) {
    $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
    $gv_result = tep_db_fetch_array($gv_query);
    if ($gv_result['amount'] > 0 ) {
      $info_box_contents[] = array('text' => '<hr />');
      $info_box_contents[] = array('text' => '<div class="smalltext"><strong>' . VOUCHER_BALANCE . '</strong><span style="float: right">' . $currencies->format($gv_result['amount']) . '</span></div>');
      $info_box_contents[] = array('text' => '<div class="smalltext" style="text-align: right"><a href="'. tep_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></div>');
    }
  }
  if (tep_session_is_registered('gv_id')) {
    $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'");
    $coupon = tep_db_fetch_array($gv_query);
    $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
    $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>');

  }
if (tep_session_is_registered('cc_id') && $cc_id) {
 $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id = '" . $cc_id . "'");
 $coupon = tep_db_fetch_array($coupon_query);
 $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cc_id . "' and language_id = '" . $languages_id . "'");
 $coupon_desc = tep_db_fetch_array($coupon_desc_query);
 $text_coupon_help = sprintf("%s",$coupon_desc['coupon_name']);
   $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
   $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="infoBoxContents">' . CART_COUPON . $text_coupon_help . '<br>' . '</td></tr></table>');
   }  
// End - CREDIT CLASS Gift Voucher Contribution
	  
	  
      new infoBox($info_box_contents);
?>
          <br />
          <a class="button" href="checkout_shipping.php">Checkout</a>
          <div class="clear"/>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php
  }
?>
<!-- shopping_cart_eof //-->