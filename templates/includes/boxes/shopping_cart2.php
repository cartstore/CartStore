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
      $cart_contents_string = '';
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