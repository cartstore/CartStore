     <div class="moduleRow shippingRow<?php echo ($checked ? ' moduleRowSelected' : '');?>">

         
         <?php
                 $search = array(' regimark', ' tradmrk');
                 $replace = array('&reg;', '&trade;');

  if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') {
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
    // disable free shipping for Alaska and Hawaii
    $zone_code = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '');
    if(in_array($zone_code, array('AK', 'HI'))) {
      $pass = false;
    }
  
  
    $free_shipping = false;
    if ($pass == true && ($order->info['total'] - $order->info['shipping_cost']) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
      $free_shipping = true;
      //include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
  $quotes = $shipping_modules->quote();
  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ){
    if (tep_session_is_registered('shipping')){
      tep_session_unregister('shipping');
    }
    tep_session_register('shipping');
    if($free_shipping == false)
      $shipping = $shipping_modules->cheapest();
    else
    {
      $shipping = array(
              'id' => 'free_free',
              'title' => FREE_SHIPPING_TITLE,
              'cost' => '0'
              );
    }
  }
?>

<?php
	if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
 <!-- <b> A <?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></b> -->

<?php
	} elseif ($free_shipping == false) {
?>
         <p><b> <?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></b></p>
 
<?php
	}

	if ($free_shipping == true) {
	  $checked = ($shipping['id'] == 'free_free'?true:false);
?>
 
 	    <b> <?php echo FREE_SHIPPING_TITLE; ?> <?php echo $quotes[$i]['icon']; ?></b>
 
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) //. tep_draw_hidden_field('shipping', 'free_free'); ?></div>

            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 radio" align=""><label><?php echo tep_draw_radio_field('shipping', 'free_free', $checked); ?></label></div> 
     
            <div class="clear"></div>
            <hr>
<?php
	} 
	if(sizeof($quotes) >= 1) {
	  $radio_buttons = 0;
	  for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
 <h4>
 	<?php echo str_replace($search, $replace, $quotes[$i]['module']); ?>
            <?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?>
 	</h4>
<?php
		if (isset($quotes[$i]['error'])) {
?>
   <div class="alert alert-warning">
			<?php echo $quotes[$i]['error']; ?>
		 
   	  </div>
<?php
		} else {
		  for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
			$checked = ($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id'] ? true : false);
?>
   
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8"><?php echo str_replace($search, $replace, $quotes[$i]['methods'][$j]['title']); ?></div> 
<?php
			if ( ($n > 1) || ($n2 > 1) ) {
?>
                 <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 radio" align=""><label><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?> </label> </div> 
            <div class="clear"></div>

            <hr>
    <?php
		} else {
				if ($checked) {
				 	$shipping_actual_tax = $quotes[$i]['tax'] / 100;
				 	$shipping_tax = $shipping_actual_tax * $quotes[$i]['methods'][$j]['cost'];

				 	$shipping['cost'] = $quotes[$i]['methods'][$j]['cost'];
				 	$shipping['shipping_tax_total'] = $shipping_tax;
				 	if (isset($onepage['info']['shipping_method']['cost'])) {
				 		$onepage['info']['shipping_method']['cost'] =
						$quotes[$i]['methods'][$j]['cost'];
				    $onepage['info']['shipping_method']['shipping_tax_total'] =
						$shipping_tax;
				 	}
				}

?>
		<div class="" align=""><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></div>  <div class="clear"></div>

            <hr>
<?php
		}
?>
    
<?php
			$radio_buttons++;
	}
?>

<?php
		}
	  }
	}
?>

                   </div>