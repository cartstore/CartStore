<table border="0" width="100%" cellspacing="0" style="padding:15px;">
 <tr>
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_NAME;?></b></td>
  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_PRODUCTS_FINAL_PRICE;?></b></td>
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
             $productAttributes .= '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>' . tep_draw_hidden_field('id[' . $order->products[$i]['id'] . '][' . $order->products[$i]['attributes'][$j]['option_id'] . ']', $order->products[$i]['attributes'][$j]['value_id']);

         }
     }
?>     
 <tr>
  <td class="main" valign="top"><?php echo $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' ( ' . $order->products[$i]['model'] . ' ) ' . $stockCheck . $productAttributes;?></td>
  <td class="main" align="right" valign="top"><?php
   echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
  ?></td>
 </tr>
<?php           
 }
?>
</table>