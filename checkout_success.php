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




 
      <?php       
        // AMAZON CODE -> START

        require_once("checkout_by_amazon/checkout_by_amazon_constants.php");
      ?>
      <script src=<?php echo(CBA_JQUERY_SETUP); ?> type="text/javascript"></script>
      <link href= <?php echo(CBA_STYLE_SHEET); ?> media="screen" rel="stylesheet" type="text/css"/>

      <link type="text/css" rel="stylesheet" media="screen" href=<?php echo(CBA_POPUP_STYLE_SHEET); ?>/>
      <script src=<?php 
            if(MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT == "Production"){echo(PROD_POPUP_ORDER_SUMMARY);} 
            else {echo(SANDBOX_POPUP_ORDER_SUMMARY);} 
           ?> type="text/javascript"></script>

      <?php        
        // AMAZON CODE -> END
      ?>


<!-- body_text //-->
   <table>
          <tr>
            <td>
    	
    	<?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?> 
  <div class="jumbotron">
              	
           <h1><span class="pull-left"></span> <?php echo HEADING_TITLE; ?></h1> 

<p><?php echo TEXT_SUCCESS; ?> Your Order # is <?php echo "$last_order" ; ?>. A invoice has been emailed to you. </p>


 
<?php
// Start - CREDIT CLASS Gift Voucher Contribution
  require('add_checkout_success.php');
// End - CREDIT CLASS Gift Voucher Contribution
 ?>
 
   <p><a class="btn btn-primary btn-lg" href="./">Continue Shopping</a></p>
   
<?php // echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>

<table> 
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
    </table></form>
   </div>
   
   
   <hr>
   
   	<h3>Quick Links</h3>

            	 <ul class="list-group">
                <li class="list-group-item"><a href="specials.php">Specials</a></li>
                             <li class="list-group-item"><a href="products_new.php">New Products</a></li>
                             
                             <li class="list-group-item"><a href="upcoming_products.php">Upcomming Products</a></li>

         
              
              </ul>
  </td></tr></table>
<!-- body_text_eof //-->
 
<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
