<?php
  /*
   $Id: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

   CartStore eCommerce Software, for The Next Generation
   http://www.cartstore.com

   Copyright (c) 2008 Adoovo Inc. USA

   GNU General Public License Compatible
   */
  require("includes/application_top.php");
  if ($cart->count_contents() > 0) {
    include(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment;
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));

  require(DIR_WS_INCLUDES . 'header.php');

  require(DIR_WS_INCLUDES . 'column_left.php');

  echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));

  if ($cart->count_contents() > 0) {
?>

      <div class="page-header">         	
		
		  <a class="btn btn-default pull-right" href="javascript:history.go(-1)">Back</a>

		  <h1><?php echo HEADING_TITLE;?> </h1></div>




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
              $info_box_contents[] = array('params' => '');
          } else {
              $info_box_contents[] = array('params' => ' ');
          }
          $cur_row = sizeof($info_box_contents) - 1;
           $products_name = '
 





<div class="shopping-cart-page ">

<table class="table table-bordered table-condensed ">

<tr rowspan="4" class="active">

<td colspan="4">

<h3><a style="margin-left:20px;" class="pull-right" href="'.tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product&cart_delete[]=' . $products[$i]['id'] . '&products_id[]=' . $products[$i]['id']).'"><i class="fa fa-times-circle"></i></a>


<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a>
</h3>

</td>

</tr>



<tr>

<td>' . '<p><div  class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><a class="" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div></p>





';
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
                  $products_name .= '
<span class="label label-primary">' . $products[$i][$option]['products_options_name'] . '  ' . $products[$i][$option]['products_options_values_name'] . '</span>
';
              }
          }
          $products_name .= '' . '' . '';
          $info_box_contents[$cur_row][] = array('params' => '', 'text' => $products_name);
          $info_box_contents[$cur_row][] = array('align' => '', 'params' => '
         
         
          </td>
         
         <td class="ui-state-default ui-state-highlight ui-state-active center"><label>Total Price</label><br>
         
         
         ', 'text' => '' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '
       
          </td>
		      <td class="ui-state-default ui-state-highlight ui-state-active center"><label>Unit Price</label><br>
         ' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1) . '
		 </td>
          
  
          </td>

		  
		  
		  <td class="ui-state-default ui-state-highlight ui-state-active center">
		  
	<div class="qty_wrap"> ' . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" onChange="UpdateCartQuantity();" id="qty_' . $products[$i]['id'] . '"') . '
		<a href="javascript:changeQuantity(\'' . $products[$i]['id'] . '\', 1)"><p><center><i class="fa fa-plus-square fa-2x"></i></a>
		<a href="javascript:changeQuantity(\'' . $products[$i]['id'] . '\', -1)"><i class="fa fa-minus-square-o fa-2x"></i></a></p></center>
	' . tep_draw_hidden_field('products_id[]', $products[$i]['id']) .'

	</div>
		</td>
		
		
		
	 
		  
		  
		  
		  

		  
		  </tr>
		  </table>
		  
		  
	</div>
		  
		  
		  
   
          
          ');
      }
      new productListingBox($info_box_contents);
?>
 <script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("input[name^=cart_delete]").click(function(){
      jQuery("form[name=cart_quantity]").submit();
    });
  })
 </script>
<?php
      if ($any_out_of_stock == 1) {
          if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
     <div class="alert alert-info"><?php
              echo OUT_OF_STOCK_CAN_CHECKOUT;
?></div>
<?php
              } else
              {
?>
      <div class="alert alert-warning"><?php
                  echo OUT_OF_STOCK_CANT_CHECKOUT;
?></div>

<?php
              }
          }
?>




<div class="clear"></div>


<p>
<span class="pull-left">
<?php
          echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART);
?>
       
        </span>

<span class="pull-right">
        	
        	 	  
	 
        	
<?php
          $back = sizeof($navigation->path) - 2;
          if (isset($navigation->path[$back])) {
?>

<?php
          }
?>
          <?php
          echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><i class="fa fa-shopping-cart"></i> Checkout <span class="hidden-xs">' . $currencies->format($cart->show_total()) .'</span></a>';
?>
             </span>
</p>
            </form>
            <div class="clear"></div>
            
<?php
   // AMAZON CODE -> START
 print ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">';
       require_once("checkout_by_amazon/checkout_by_amazon_util_dao.php");
       $utilDao = new UtilDAO();
       $utilDao->printButton();
  // AMAZON CODE -> END
       
       print '  </div>';

            if (defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS') && MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True' && SELECT_VENDOR_SHIPPING == 'false') {
            include_once('googlecheckout/gcheckout.php');
          }

    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();
    if (!empty($initialize_checkout_methods)) {
?>
                
         
  <p><?php // echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></p>
<?php
      reset($initialize_checkout_methods);
      while (list(, $value) = each($initialize_checkout_methods)) {
?>
  <p><?php echo $value; ?></p>
  
   
<?php
      }
    }
?>
<div class="clear"></div>
<hr>

 <div class="est_shipping col-lg-12 col-md-12 col-sm-12 col-xs-12 row" id="est_shipping">
  <script type="text/javascript">
   jQuery(document).ready(function(){
     jQuery.ajax({
       url: "ext/estimated_shipping.php",
       success: function(data){
         jQuery("#est_shipping").html(data);
       }
     })
   });
  </script>
 </div>

      
    <?php
          } else
          {
?>
     <?php
              new infoBox(array(array('text' => TEXT_CART_EMPTY)));
?><br>
      <?php
              echo '<a class="btn btn-default" href="javascript:history.go(-1)">' . IMAGE_BUTTON_CONTINUE . '</a>';
?>
<?php
          }
?><div class="clear"></div>
<!--<a style="display: none"; href="subscribe.html"
          rel="popup">message</a>--> 
       
    <!-- body_text_eof //-->
   

<?php
          require(DIR_WS_INCLUDES . 'column_right.php');

          require(DIR_WS_INCLUDES . 'footer.php');

          require(DIR_WS_INCLUDES . 'application_bottom.php');
?>