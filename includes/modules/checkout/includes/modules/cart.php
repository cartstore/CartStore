<div id="shoppingCart" style=""><table width="100%">
 <tr>
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_NAME;?></b></td>
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_QTY;?></b></td>
  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_PRODUCTS_PRICE;?></b></td>
  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_PRODUCTS_FINAL_PRICE;?></b></td>
  <td class="smallText" align="right"></td>
 </tr>
<?php
 for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	 $stockCheck = '';
	 if (STOCK_CHECK == 'true') {
		 $stockCheck = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
	 }

	 $productAttributes = '';
	 if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0) {
		 for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			 $productAttributes .= '<br><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		&nbsp;<i>' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small>' . tep_draw_hidden_field('id[' . $order->products[$i]['id'] . '][' . $order->products[$i]['attributes'][$j]['option_id'] . ']', $order->products[$i]['attributes'][$j]['value_id']);

		 }
	 }
?>
 <tr>
  <td class="main" valign="top"><b><?php echo $order->products[$i]['name'] . '</b>' . $stockCheck . $productAttributes;?></td>
  <td class="main" valign="top"><?php
   echo tep_draw_input_field('qty[' . $order->products[$i]['id'] . ']', $order->products[$i]['qty'], 'size="3" onkeyup="$(\'input[name^=qty]\').attr(\'readonly\', true); $(\'#updateCartButton\').trigger(\'click\')"');
  ?></td>
  <td class="main" align="right" valign="top"><?php
   echo $currencies->display_price($order->products[$i]['final_price'],$order->products[$i]['tax']);

  ?></td>
  <td class="main" align="right" valign="top"><?php
   echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
  ?></td>
  <td class="main" align="right" valign="top"><a href="Javascript:void();" linkData="action=removeProduct&pID=<?php echo $order->products[$i]['id'];?>" class="removeFromCart"><img src="<?php echo DIR_WS_MODULES;?>checkout/images/cross.gif"></a></td>
 </tr>
<?php
 }
?>
</table></div>
