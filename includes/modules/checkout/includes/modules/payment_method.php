<?php
  $selection = $payment_modules->selection();
  
  $paymentMethod = '';
  if (tep_session_is_registered('onepage')){
	  $paymentMethod = $onepage['info']['payment_method'];
  }

  if ($paymentMethod == ''){
	$paymentMethod = ONEPAGE_DEFAULT_PAYMENT;
  }

  if (sizeof($selection) > 1) {
?>
 <!--  <h3><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></h3> -->
<?php
  } else {
?>
   <h3><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></h3>
<?php
  }
  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
   <div class="moduleRow paymentRow<?php echo ($selection[$i]['id'] == $paymentMethod ? ' moduleRowSelected' : '');?>">
   	 
       <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8"><?php echo $selection[$i]['module']; ?></div>
   		<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 radio" align=""><?php
	 		if (sizeof($selection) > 1) {
		 		echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $paymentMethod));
	 		} else {
		 		echo tep_draw_hidden_field('payment', $selection[$i]['id']);
	 		}
		?></div>
		 
  </div>
   
   <div class="clear"></div><hr>
<?php
	if (isset($selection[$i]['error'])) {
?>
	 <div class="alert alert-warning"> 
			<?php echo $selection[$i]['error']; ?>
   	  </div>
<?php
	} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) && ($selection[$i]['id'] == $paymentMethod)) {
?>
	<blockquote>
<?php
	  for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
	 <div><?php echo $selection[$i]['fields'][$j]['title']; ?>
	 <?php echo $selection[$i]['fields'][$j]['field']; ?></div>
<?php
	  }
?>
		</blockquote>
<?php
	}
?>
<?php
	$radio_buttons++;
  }

  // Start - CREDIT CLASS Gift Voucher Contribution
  if(MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') {
  if (tep_session_is_registered('customer_id')) {
	  $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
  	$gv_result = tep_db_fetch_array($gv_query);
    if ($gv_result['amount']>0){
  		echo '<table><tr><td width="10"></td><td colspan=2>' . "\n" .
  			 '              <table border="0" cellpadding="2" cellspacing="0" width="100%"><tr class="moduleRow" onclick="clearRadeos()">' . "\n" .
  			 '              <td width="10"></td><td class="main">' . $gv_result['text'];
  		echo $order_total_modules->sub_credit_selection();
		echo '</table>';
  	}
  }
  }
// End - CREDIT CLASS Gift Voucher Contribution

if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true')
  {?>
    <table>
    <?php
    $buysafe_module->draw_payment_page();
    ?>
    </table>
   <?php
  }
//BOF Points/Rewards
  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
    echo "<table>";
	  echo points_selection();
	  if (tep_not_null(USE_REFERRAL_SYSTEM) && (tep_count_customer_orders() == 0)) {
		  echo referral_input();
	  }
    echo "</table>";
  }
//EOF Points/Rewards
?>
