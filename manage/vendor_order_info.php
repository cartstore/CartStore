<?php
?>

<tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" align="left"><?php
  echo TABLE_HEADING_PRODUCTS_VENDOR;
?></td>
      <td class="dataTableHeadingContent" align="left"><?php
  echo TABLE_HEADING_PRODUCTS;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_VENDORS_SHIP;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_SHIPPING_METHOD;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_SHIPPING_COST;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_PRODUCTS_MODEL;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_TAX;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_PRICE_EXCLUDING_TAX;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_PRICE_INCLUDING_TAX;
?></td>
      <td class="dataTableHeadingContent" align="center"><?php
  echo TABLE_HEADING_TOTAL_EXCLUDING_TAX;
?></td>
      <td class="dataTableHeadingContent" align="right"><?php
  echo TABLE_HEADING_TOTAL_INCLUDING_TAX;
?></td>
    </tr>
    <?php
  $package_num = sizeof($order->products);
  $box_num = $l + 1;
  for ($l = 0, $m = sizeof($order->products); $l < $m; $l++) {
      $ship_data_text = 'Shipment Number ' . $box_num++ . ' of ' . $package_num;
      echo '          <tr class="dataTableRow">' . "\n" . '          <td class="dataTableContent" valign="top">' . $order->products[$l]['Vname'] . '<br>' . $ship_data_text . '<br><a class="button" href="' . tep_href_link('vendor_packingslip.php', 'oID=' . $oID . '&vID=' . $order->products[$l]['Vid'] . '&text=' . $ship_data_text) . '">' . IMAGE_ORDERS_PACKINGSLIP . '</a></td>' . "\n";
      echo '         <td class="dataTableContent" valign="center" align="center"><a href="' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, '&vID=' . $order->products[$l]['Vid'] . '&oID=' . $oID . '&vOS=' . $order->products[$l]['Vorder_sent']) . '">Vendor Order Sent: <b>' . $order->products[$l]['Vorder_sent'] . '</a></b></td>';
      echo '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vmodule'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vmethod'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vcost'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">ship tax<br>' . $order->products[$l]['Vship_tax'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" . '         <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>';
      for ($i = 0, $n = sizeof($order->products[$l]['orders_products']); $i < $n; $i++) {
          echo '      <tr>' . "\n" . '      <td class="dataTableContent" valign="center" align="right">' . $order->products[$l]['orders_products'][$i]['qty'] . '&nbsp;x</td>' . "\n" . '      <td class="dataTableContent" valign="center" align="left">' . $order->products[$l]['orders_products'][$i]['name'];
          
          
          
          
          if (isset($order->products[$l]['orders_products'][$i]['attributes']) && (sizeof($order->products[$l]['orders_products'][$i]['attributes']) > 0)) {
              for ($j = 0, $k = sizeof($order->products[$l]['orders_products'][$i]['attributes']); $j < $k; $j++) {
                  echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['option'] . ': ' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['value'];
                  if ($order->products[$l]['orders_products'][$i]['attributes'][$j]['price'] != '0')
                      echo ' (' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$l]['orders_products'][$i]['attributes'][$j]['price'] * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                  echo '</i></small></nobr>';
              }
          }
          echo '    <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" . '    <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" . '    <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" . '    <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['model'] . '</td>' . "\n" . '    <td class="dataTableContent" align="center" valign="center">' . tep_display_tax_value($order->products[$l]['orders_products'][$i]['tax']) . '%</td>' . "\n" . '    <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$l]['orders_products'][$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '    <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'], $order->products[$l]['orders_products'][$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '    <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$l]['orders_products'][$i]['final_price'] * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '    <td class="dataTableContent" align="right" valign="center"><b>' . $currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'], $order->products[$l]['orders_products'][$i]['tax']) * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
          echo '    </tr>';
      }
  }
?>
   