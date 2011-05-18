<?php

  /*

   $Id: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

   

   CartStore eCommerce Software, for The Next Generation

   http://www.cartstore.com

   

   Copyright (c) 2008 Adoovo Inc. USA

   

   GNU General Public License Compatible

   */

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php

  echo HTML_PARAMS;

?>>

<head>

<meta http-equiv="Content-Type"

  content="text/html; charset=<?php

  echo CHARSET;

?>">

<title><?php

  echo TITLE;

?></title>

<base

  href="<?php

  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;

?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<!-- BEGIN CHECKOUT BY AMAZON CODE -->




<!-- end of order summary pop up -->

<!-- END CHECKOUT BY AMAZON CODE -->

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"

  leftmargin="0" rightmargin="0">

<!-- header //-->

<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%">

  <tr>

    <td width="<?php

  echo BOX_WIDTH;

?>" valign="top">

    <table border="0" width="<?php

  echo BOX_WIDTH;

?>">

      <!-- left_navigation //-->

<?php

  require(DIR_WS_INCLUDES . 'column_left.php');

?>

<!-- left_navigation_eof //-->

    </table>

    </td>

    <!-- body_text //-->

    <table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td>





  

  <?php

  echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));

?>

          

<?php

  if ($cart->count_contents() > 0) {

?>

  <div id="module-product">

        <h3><?php

      echo HEADING_TITLE;

?> <?php

      echo ': Subtotal';

?> <?php

      echo $currencies->format($cart->show_total());

?></h3>

<?php

      $info_box_contents = array();

      $info_box_contents[0][] = array('align' => '', 'params' => '', 'text' => "");

      $info_box_contents[0][] = array('params' => '', 'text' => "");

      $info_box_contents[0][] = array('align' => '', 'params' => '', 'text' => "");

      $info_box_contents[0][] = array('align' => '', 'params' => '', 'text' => "");

      $any_out_of_stock = 0;

      $products = $cart->get_products();

      for ($i = 0, $n = sizeof($products); $i < $n; $i++) {

          // Push all attributes information in an array

          if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {

              while (list($option, $value) = each($products[$i]['attributes'])) {

                  //clr 030714 move hidden field to if statement below

                  //echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);

                  //++++ QT Pro: Begin Changed code

                  if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {

                      $attributes = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix

                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa

                                      where pa.products_id = '" . $products[$i]['id'] . "'

                                       and pa.options_id = '" . $option . "'

                                       and pa.options_id = popt.products_options_id

                                                                            and pa.options_values_id = poval.products_options_values_id

                                       and popt.language_id = '" . $languages_id . "'

                                       and poval.language_id = '" . $languages_id . "'");

                  } else {

                      $attributes = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix

                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa

                                      where pa.products_id = '" . $products[$i]['id'] . "'

                                       and pa.options_id = '" . $option . "'

                                       and pa.options_id = popt.products_options_id

                                       and pa.options_values_id = '" . $value . "'

                                       and pa.options_values_id = poval.products_options_values_id

                                       and popt.language_id = '" . $languages_id . "'

                                       and poval.language_id = '" . $languages_id . "'");

                  }

                  //++++ QT Pro: End Changed Code

                  $attributes_values = tep_db_fetch_array($attributes);

                  //clr 030714 determine if attribute is a text attribute and assign to $attr_value temporarily

                  if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {

                      echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . TEXT_PREFIX . $option . ']', $products[$i]['attributes_values'][$option]);

                      $attr_value = $products[$i]['attributes_values'][$option];

                  } else {

                      echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);

                      $attr_value = $attributes_values['products_options_values_name'];

                  }

                  $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];

                  $products[$i][$option]['options_values_id'] = $value;

                  //clr 030714 assign $attr_value

                  $products[$i][$option]['products_options_values_name'] = $attr_value;

                  //          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];

                  //          $products[$i][$option]['options_values_id'] = $value;

                  //$products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];

                  $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];

                  $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];

                  //++++ QT Pro: Begin Changed code

                  $products[$i][$option]['track_stock'] = $attributes_values['products_options_track_stock'];

                  //++++ QT Pro: End Changed Code

              }

          }

      }

      for ($i = 0, $n = sizeof($products); $i < $n; $i++) {

          if (($i / 2) == floor($i / 2)) {

              $info_box_contents[] = array('params' => '<div class="productitem ui-widget ui-widget-content ui-corner-all"><div class="remove"><b>Remove</b>');

          } else {

              $info_box_contents[] = array('params' => ' <div class="productitem ui-widget ui-widget-content ui-corner-all"><div class="remove"><b>Remove</b>');

          }

          $cur_row = sizeof($info_box_contents) - 1;

          $info_box_contents[$cur_row][] = array('align' => '', 'params' => '', 'text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']));

          $products_name = '</div>

<div class="productimage">' . '  ' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><div class="clear"></div></div>' . '<div class="productdes"><h4><a class="cart_prod_name" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a></h4><p>';

          if (STOCK_CHECK == 'true') {

              //++++ QT Pro: Begin Changed code

              if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {

                  $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']);

              } else {

                  $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);

              }

              //++++ QT Pro: End Changed Code

              if (tep_not_null($stock_check)) {

                  $any_out_of_stock = 1;

                  $products_name .= $stock_check;

              }

          }

          if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {

              reset($products[$i]['attributes']);

              while (list($option, $value) = each($products[$i]['attributes'])) {

                  $products_name .= '<small><i>' . $products[$i][$option]['products_options_name'] . ' - ' . $products[$i][$option]['products_options_values_name'] . '</i></small><br>';

              }

          }

          $products_name .= '</p> <label>Qty:</label>' . '' . '';

          $info_box_contents[$cur_row][] = array('params' => '', 'text' => $products_name);

          $info_box_contents[$cur_row][] = array('align' => '', 'params' => '', 'text' => '' . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" onChange="UpdateCartQuantity();" id="qty_' . $products[$i]['id'] . '"') . '<span class="cart_quan_symb cartminus"><a href="javascript:changeQuantity(' . $products[$i]['id'] . ',-1)"> - </a></span><span class="cart_quan_symb cartplus" ><a href="javascript:changeQuantity(' . $products[$i]['id'] . ', 1)">/+ </a></span>' . tep_draw_hidden_field('products_id[]', $products[$i]['id']));

          $info_box_contents[$cur_row][] = array('align' => '', 'params' => '<div class="prize">', 'text' => '' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '<div class="clear"></div></div><div class="clear"></div></div><div class="clear"></div></div>');

      }

      new productListingBox($info_box_contents);

?>



<?php

      if ($any_out_of_stock == 1) {

          if (STOCK_ALLOW_CHECKOUT == 'true') {

?>

      <div class="stockWarning" align="center"><?php

              echo OUT_OF_STOCK_CAN_CHECKOUT;

?></div>



<?php

              } else

              {

?>

      <div class="stockWarning" align="center"><?php

                  echo OUT_OF_STOCK_CANT_CHECKOUT;

?></div>

      

<?php

              }

          }

?>

<div class="clear"></div>

<?php

          echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART);

?>



        <div class="shoppingcart_nav"><a class="button" href="javascript:history.go(-1)">Back</a>



<?php

          $back = sizeof($navigation->path) - 2;

          if (isset($navigation->path[$back])) {

?>

               

<?php

          }

?>

          <?php

          echo '<a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">Checkout</a>';

?>

               

            </form> 

          <?php

          // CHECKOUT BY AMAZON CODE

          // add Checkout by Amazon button to page if Checkout by Amazon module is enabled

      //    if (defined('MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS') && MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS == 'True') {

       //       include_once('checkout_by_amazon/checkout_by_amazon_main.php');

     //     }

          // END CHECKOUT BY AMAZON CODE

?>

        

    <?php

          // ** GOOGLE CHECKOUT **

          // Checks if the Google Checkout payment module has been enabled and if so 

          // includes gcheckout.php to add the Checkout button to the page 

          if (defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS') && MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True') {

              include_once('googlecheckout/gcheckout.php');

          }

          // ** END GOOGLE CHECKOUT **

?>

       <div class="subtotal"><b></b>

        <br>

<?php

          // echo '<a class="est_shipping" rel="lightbox-page"  href="' . tep_href_link(FILENAME_POPUP_SHIPPING) . '">' . '<img src="static/images/calculate_shipping.png" border="0"/></a>';   

?></div>

    <?php

          } else

          {

?>



     <?php

              new infoBox(array(array('text' => TEXT_CART_EMPTY)));

?><br>



      <?php

              echo '<a class="button" href="javascript:history.go(-1)">' . IMAGE_BUTTON_CONTINUE . '</a>';

?>

<?php

          }

?><div class="clear"></div>

 <span class="est_shipping">

   <?php

          include(DIR_WS_MODULES . FILENAME_ESTIMATED_SHIPPING);

?></span>

<a style="display: none"; href="subscribe.html"

          rel="popup">message</a></div>

        </div>

        </div>

        </form>

        </td>

      </tr>

    </table>

    <!-- body_text_eof //-->

    <td width="<?php

          echo BOX_WIDTH;

?>" valign="top">

    <table border="0" width="<?php

          echo BOX_WIDTH;

?>">

      <!-- right_navigation //-->

<?php

          require(DIR_WS_INCLUDES . 'column_right.php');

?>

<!-- right_navigation_eof //-->

    </table>

    </td>

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