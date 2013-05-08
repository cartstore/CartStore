<?php
/*
  $Id: checkout_success.php,v 1.49 2003/06/09 23:03:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

/* One Page Checkout - BEGIN */
  if (ONEPAGE_CHECKOUT_ENABLED == 'True' && SELECT_VENDOR_SHIPPING != 'true'){
      if (!tep_session_is_registered('onepage')){
          if (!tep_session_is_registered('customer_id')) {
              tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
          }
      }else{
          require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT);
          require_once('includes/functions/password_funcs.php');
          require(DIR_WS_MODULES . 'checkout/includes/classes/onepage_checkout.php');
          $onePageCheckout = new osC_onePageCheckout();
          $onePageCheckout->createCustomerAccount();
      }
  }else{
      if (!tep_session_is_registered('customer_id')) {
          tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
      }
  }
/* One Page Checkout - END */

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $notify_string = 'action=notify&';
    $notify = $_POST['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

   // tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));

   // PWA BOF
       if($customer_id != 0)
       {
         tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
       }
       else
       {
         tep_session_unregister('pwa_array_customer');
         tep_session_unregister('pwa_array_address');
         tep_session_unregister('pwa_array_shipping');
         tep_session_unregister('customer_id');
         tep_redirect(tep_href_link(FILENAME_DEFAULT));
       }
      // PWA EOF
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }

require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="4" cellpadding="2">
          <tr>
            <td valign="top" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
              <div class="pageHeading"><?php echo HEADING_TITLE; ?></div> <br><?php echo TEXT_SUCCESS; ?><br><br>
              <!--<div align="center"><a href="javascript:popupPrintReceipt('<?php echo tep_href_link(FILENAME_ORDERS_PRINTABLE2, 'oID=' . $last_order); ?>')">
           <b>Print Reciept</b></a></div>

           -->Your Order # is <?php echo "$last_order" ; ?>. <br>
A invoice has been emailed to you. <br>

  <!-- <iframe class="autoHeight" width="100%" scrolling="no" height="288" frameborder="0" name="iframe" src="print_order2.php?oID=<?php echo $last_order;?>"></iframe> -->








            </td>
          </tr>
        </table></td>
      </tr>
<?php
// Start - CREDIT CLASS Gift Voucher Contribution
  require('add_checkout_success.php');
// End - CREDIT CLASS Gift Voucher Contribution
 ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"></td>
                <td width="50%"></td>
              </tr>
            </table></td>
            <td width="25%"></td>
            <td width="25%"></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"></td>
                <td width="50%"></td>
              </tr>
            </table></td>
          </tr>
       <!--   <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr> -->
        </table></td>
      </tr>
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
<?php //---PayPal WPP Modification START ---// ?>
<?php
  $customer_id_temp = $customer_id;
  if (tep_paypal_wpp_enabled()) {
    if ($paypal_ec_temp) {
        tep_session_unregister('customer_id');
        tep_session_unregister('customer_default_address_id');
        tep_session_unregister('customer_first_name');
        tep_session_unregister('customer_country_id');
        tep_session_unregister('customer_zone_id');
        tep_session_unregister('comments');
        //$cart->reset();
        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customer_id . "'");
    }

    tep_session_unregister('paypal_ec_temp');
    tep_session_unregister('paypal_ec_token');
    tep_session_unregister('paypal_ec_payer_id');
    tep_session_unregister('paypal_ec_payer_info');
  }
?>
<?php //---PayPal WPP Modification END ---// ?>
    </table></form></td>
<!-- body_text_eof //-->
 
<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
