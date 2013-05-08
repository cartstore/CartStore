<?php
                 $search = array(' regimark', ' tradmrk');
                 $replace = array('<sup>&reg;</sup>', '<sup>&trade;</sup>');

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
  <h3><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></h3>
<?php
	} elseif ($free_shipping == false) {
?>
  <h3><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></h3>
<?php
	}

	if ($free_shipping == true) {
	  $checked = ($shipping['id'] == 'free_free'?true:false);
?>
<table>
 <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
  <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
	    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
	    <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
	    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
    </tr>
    <tr class="moduleRow shippingRow<?php echo ($checked ? ' moduleRowSelected' : '');?>">
	    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
	    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) //. tep_draw_hidden_field('shipping', 'free_free'); ?></td>
	    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
      <td><?php echo tep_draw_radio_field('shipping', 'free_free', $checked); ?></td>
      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
    </tr>
  </table></td>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
 </tr>
</table>
<?php
	} 
	if(sizeof($quotes) >= 1) {
	  $radio_buttons = 0;
	  for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
 <h3 class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-forward ui-icon-shadow" style="float:left"></span>
 	<?php echo str_replace($search, $replace, $quotes[$i]['module']); ?>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?>
 	</h3>
<?php
		if (isset($quotes[$i]['error'])) {
?>
      <div class="ui-widget">
        <div style="margin-bottom: 10px; padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
          <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span> <strong> Alert:</strong> 
			<?php echo $quotes[$i]['error']; ?>
			</p>
   		</div>
   	  </div>
<?php
		} else {
		  for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
			$checked = ($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id'] ? true : false);
?>
   <div class="moduleRow shippingRow<?php echo ($checked ? ' moduleRowSelected' : '');?>">
   	<table width="90%" align="center" cellspacing="8" cellpadding="8"><tr>
		<td width="90%"><b><?php echo str_replace($search, $replace, $quotes[$i]['methods'][$j]['title']); ?></b></td>
<?php
			if ( ($n > 1) || ($n2 > 1) ) {
?>
		<td class="main" align="right"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
		<td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
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
		<td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
<?php
		}
?>
     </tr>
   </table>
<?php
			$radio_buttons++;
	}
?>
  </div>
<?php
		}
	  }
	}
?>