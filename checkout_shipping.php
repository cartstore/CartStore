<?php
  require('includes/application_top.php');
  require('includes/classes/http_client.php');
  if (ONEPAGE_CHECKOUT_ENABLED == 'True' && SELECT_VENDOR_SHIPPING != 'true'){
      tep_redirect(tep_href_link(FILENAME_CHECKOUT, $_SERVER['QUERY_STRING'], 'SSL'));
  }
  
  if (!tep_session_is_registered('customer_id')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  if ($cart->count_contents() < 1) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
  if (!tep_session_is_registered('sendto')) {
      tep_session_register('sendto');
  //BOF WA State Tax Modification
  if (tep_session_is_registered('wa_dest_tax_rate')) tep_session_unregister('wa_dest_tax_rate');
  //EOF WA State Tax Modification
      $sendto = $customer_default_address_id;
  } else {
      if ($customer_id == 0) {
          $sendto = 1;
      } else {
          $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
          $check_address = tep_db_fetch_array($check_address_query);
          if ($check_address['total'] != '1') {
              $sendto = $customer_default_address_id;
              if (tep_session_is_registered('shipping'))
                  tep_session_unregister('shipping');
          }
      }
  }
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  if (!tep_session_is_registered('cartID'))
      tep_session_register('cartID');
  $cartID = $cart->cartID;
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  if (SKIP_SHIPPING_DOWNLOADS_ZERO_WEIGHT == 'Yes'){
    if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') || ($total_weight == 0)) {
      if (!tep_session_is_registered('shipping'))
          tep_session_register('shipping');
      $shipping = false;
      $sendto = false;
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
   }
  }
  if (SELECT_VENDOR_SHIPPING == 'true') {
      include(DIR_WS_CLASSES . 'vendor_shipping.php');
      $shipping_modules = new shipping;
  } else {
      include(DIR_WS_CLASSES . 'shipping.php');
      $shipping_modules = new shipping;
      $total_weight = $cart->show_weight();
      $cost = $cart->show_total();
      $total_count = $cart->count_contents();
  }
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
      $del_details = $_REQUEST['select_delv_time'];
      setcookie("DelvTimeCookie", $del_details, time() + 3600, "/");
      $del_temp = explode("~", $del_details);
      $del_date = $del_temp[0];
      $del_slotid = $del_temp[1];
      $del_cost = $del_temp[2];
      if (!tep_session_is_registered('comments'))
          tep_session_register('comments');
      if (tep_not_null($_POST['comments'])) {
          $comments = tep_db_prepare_input($_POST['comments']);
      }
      if (!tep_session_is_registered('shipping'))
          tep_session_register('shipping');
      if (SELECT_VENDOR_SHIPPING == 'true') {
          $total_shipping_cost = 0;
          $shipping_title = MULTIPLE_SHIP_METHODS_TITLE;
          $vendor_shipping = $cart->vendor_shipping;
          $shipping = array();
          foreach ($vendor_shipping as $vendor_id => $vendor_data) {
              $products_shipped = $_POST['products_' . $vendor_id];
              $products_array = explode("_", $products_shipped);
              $shipping_data = $_POST['shipping_' . $vendor_id];
              $shipping_array = explode("_", $shipping_data);
              $module = $shipping_array[0];
              $method = $shipping_array[1];
              $ship_tax = $shipping_array[2];
              if (is_object($$module) || ($module == 'free')) {
                  if ($module == 'free') {
                      $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
                      $quote[0]['methods'][0]['cost'] = '0';
                  } else {
                      $total_weight = $vendor_shipping[$vendor_id]['weight'];
                      $shipping_weight = $total_weight;
                      $cost = $vendor_shipping[$vendor_id]['cost'];
                      $total_count = $vendor_shipping[$vendor_id]['qty'];
                      $quote = $shipping_modules->quote($method, $module, $vendor_id);
                  }
                  if (isset($quote['error'])) {
                      tep_session_unregister('shipping');
                  } else {
                      if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
                          $output[$vendor_id] = array('id' => $module . '_' . $method, 'title' => $quote[0]['methods'][0]['title'], 'ship_tax' => $ship_tax, 'products' => $products_array, 'cost' => $quote[0]['methods'][0]['cost'], 'invcost' => $shipping_modules->get_shiptotal());
                          $total_ship_tax += $ship_tax;
                          $total_shipping_cost += $quote[0]['methods'][0]['cost'];
                      }
                  }
              }
          }
          if ($module == 'dly3datetime' && $del_cost > 0)
              $total_shipping_cost = $total_shipping_cost + $del_cost;
          if ($free_shipping == true) {
              $shipping_title = $quote[0]['module'];
          } elseif (count($output) < 2) {
              $shipping_title = $quote[0]['methods'][0]['title'];
          }
          $shipping = array('id' => $shipping, 'title' => $shipping_title, 'cost' => $total_shipping_cost, 'shipping_tax_total' => $total_ship_tax, 'vendor' => $output);
      } else {
          if ((isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_'))) {
              $shipping = $_POST['shipping'];
              list($module, $method) = explode('_', $shipping);
              if (is_object($$module) || ($shipping == 'free_free')) {
                  if ($shipping == 'free_free') {
                      $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
                      $quote[0]['methods'][0]['cost'] = '0';
                  } else {
                      $quote = $shipping_modules->quote($method, $module);
                  }
              }
              if (isset($quote['error'])) {
                  tep_session_unregister('shipping');
              } else {
                  if ($module == 'dly3datetime' && $del_cost > 0)
                      $total_shipping_cost = $quote[0]['methods'][0]['cost'] + $del_cost;
                  else
                      $total_shipping_cost = $quote[0]['methods'][0]['cost'];
                  if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
                      $shipping = array('id' => $shipping, 'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'), 'cost' => $total_shipping_cost);
                      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
                  }
              }
          } else {
              tep_session_unregister('shipping');
          }
      }
      tep_redirect(tep_href_link('checkout_payment.php', '', 'SSL'));
      exit;
  }
  $quotes = $shipping_modules->quote();
  if (!tep_session_is_registered('shipping') || (tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1)))
      $shipping = $shipping_modules->cheapest();
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));


  require(DIR_WS_INCLUDES . 'header.php');


  require(DIR_WS_INCLUDES . 'column_left.php');
?>
       
    <!-- body_text //-->
   	<?php echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          	
          <h1><?php
  echo HEADING_TITLE;
?></h1>

            <br>

            <div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?>
    <li><?php echo '<span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span>';?><br>

	<span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span>






    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></li>
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
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b>
                  <?php
  echo TABLE_HEADING_SHIPPING_ADDRESS;
?>
                  </b></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td></td>
                      <td class="main" valign="top">
                      	<?php
  echo TEXT_CHOOSE_SHIPPING_DESTINATION . '<br>
  
  ';
?>
                      	<?php
  echo '<p><b>Selected Address</b><br>';
?>

<?php
  echo tep_address_label($customer_id, $sendto, true, ' ', '<br>');
?>
</p>
<?php
  echo'
<a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="ui-icon ui-icon-gear" style="float:left"></span>Change Address</a>';
?>

                      	
                      	</td>
                      
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
     
        </tr>
        <?php
  if (tep_count_shipping_modules() > 0 || SELECT_VENDOR_SHIPPING == 'true') {
?>
        <tr>
          <td>
          	
          	<br><p></p>
          	
          	<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><span class="ui-icon ui-icon-forward ui-icon-shadow" style="float:left;"></span>
                  <?php
      echo TABLE_HEADING_SHIPPING_METHOD;
?>
                  </b></td>
              </tr>
            </table></td>
        </tr>
        <?php
      if (SELECT_VENDOR_SHIPPING == 'true') {
          require(DIR_WS_MODULES . 'vendor_shipping.php');
      } else {
          $quotes = $shipping_modules->quote();
          if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true')) {
              $pass = false;
              switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
                  case 'national':
                      if ($order->delivery['country_id'] == STORE_COUNTRY) {
                          $pass = true;
                      }
                      break;
                  case 'international':
                      if ($order->delivery['country_id'] != STORE_COUNTRY) {
                          $pass = true;
                      }
                      break;
                  case 'both':
                      $pass = true;
                      break;
              }
              $free_shipping = false;
              if (($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
                  $free_shipping = true;
                  include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
              }
          } else {
              $free_shipping = false;
          }
          if (!tep_session_is_registered('shipping') || (tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1)))
              $shipping = $shipping_modules->cheapest();
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox  fixwidthshipping">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
          if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
                    <tr>
                      <td><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <!--<td class="main" width="50%" valign="top"><?php
              echo TEXT_CHOOSE_SHIPPING_METHOD;
?></td>-->
                      <!-- PWA BOF -->
                      <td class="main" width="50%" valign="top"><?php
?></td>
                      <!-- PWA EOF -->
                      <td class="main" width="50%" valign="top" align="right"><?php
              echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif');
?></td>
                      <td><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                    </tr>
                    <?php
          } elseif ($free_shipping == false) {
?>
                    <tr>
                      <td><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <td class="main" width="100%" colspan="2"><?php
              echo TEXT_ENTER_SHIPPING_INFORMATION;
?></td>
                      <td><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                    </tr>
                    <?php
          }
          if ($free_shipping == true) {
?>
                    <tr>
                      <td><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>

                            <td class="main"><b>
                              <?php
              echo FREE_SHIPPING_TITLE;
?>
                              </b>&nbsp;
                              <?php
              echo $quotes[$i]['icon'];
?></td>

                          </tr>
                          <tr id="defaultSelected" class="ui-state-highlight ui-corner-all">
                            <td width="10"><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                            <td class="main" width="100%"><?php
              echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free');
?></td>
                            <td width="10"><?php
              echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                          </tr>
                        </table></td>

                    </tr>
                    <?php
              } else
              {
                  $radio_buttons = 0;
                  for ($i = 0, $n = sizeof($quotes); $i < $n; $i++) {
?>
                    <tr>

                      <td  colspan="3">





                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>

                            <td class="main"  colspan="3"><br><br>

<h3 class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-forward ui-icon-shadow" style="float:left"></span>
                           <?php
                      echo $quotes[$i]['module'];
?>
                              </h3>
                              <?php
                      if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) {
                          echo $quotes[$i]['icon'];
                      }
?></td>

                          </tr>
                          <?php
                      if (isset($quotes[$i]['error'])) {
?>

                            <td class="main" colspan="3"><?php
                          echo $quotes[$i]['error'];
?></td>

                          </tr>
                          <?php
                          } else
                          {
                              for ($j = 0, $n2 = sizeof($quotes[$i]['methods']); $j < $n2; $j++) {
                                  $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
                                  if (($checked == true) || ($n == 1 && $n2 == 1)) {
                                      echo '                  <tr>' . "\n";
                                  } else {
                                      echo '                  <tr class="moduleRow">' . "\n";
                                  }
                             $search = array(' regimark', ' tradmrk');
                             $replace = array('<sup>&reg;</sup>', '<sup>&trade;</sup>');
?>
                            <td class="main" width="75%"><?php
                                  echo str_replace($search, $replace, $quotes[$i]['methods'][$j]['title']);
?></td>
                            <?php
                                  if (($n > 1) || ($n2 > 1)) {
?>
                            <td class="main"><?php
                                      echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)));
?></td>
                            <td class="main" align="right"><?php
                                      echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked);
?></td>
                            <?php
                                      } else
                                      {
?>
                            <td class="main" align="right" colspan="2"><?php
                                          echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']);
?></td>
                            <?php
                                      }
?>

                          </tr>
                          <?php
                                      if ($quotes[$i]['id'] == "dly3datetime") {
?>
                          <tr>
                            <td class="main" colspan="5"><?php
                                          include("availability_checkout.php");
?></td>
                          </tr>
                          <?php
                                          } $radio_buttons++;
                                      }
                                  }
?>
                        </table></td>

                    </tr>
                    <?php
                              }
                          }
?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>

        </tr>
        <?php
                      }
                  }
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><br>
<div class="hide_comments"><b>
                    <?php
                  echo TABLE_HEADING_COMMENTS;
?>
                    </b></div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><div class="hide_comments">
                          <?php
                  echo tep_draw_textarea_field2('comments', 'soft', '47', '5');
?>
                        </div></td>
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
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="10"><?php
                  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                      <td class="main"><?php
                  echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b>';
?></td>
                      <td class="main" align="right"><?php
                  echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
?></td>
                      <td width="10"><?php
                  echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>


             <tr>
             <td>

                 <hr>
<div id="module-product">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	<li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="ui-state-default  ui-tabs-selected ui-state-active ui-corner-all"><span>1. ' . CHECKOUT_BAR_DELIVERY . '</span></a></li>';?>
    <li><?php echo '<span>2.  ' . CHECKOUT_BAR_PAYMENT . '</span>';?><br>

	<span>3. <?php
                                                                 echo CHECKOUT_BAR_CONFIRMATION;
?></span>






    <li>4. <?php  echo CHECKOUT_BAR_FINISHED; ?></li>
  </ul>
</div>


           
            
            </td>
        </tr>
      </table>
     </form>
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