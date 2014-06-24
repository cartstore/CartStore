<?php
/*
  $Id: vendor_order_info.php
  By Craig Garrison Sr. (craig@blucollarsales.com) for Multi-Vendor Shipping
  for MVS V1.0 2006/03/25 JCK/CWG
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
  */
  ?>
<td><table border="1" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="main" align="left"><b><?php echo TABLE_HEADING_PRODUCTS_VENDOR; ?></b></td>
            <td class="main" align="left"><b><?php echo TABLE_HEADING_PRODUCTS; ?></b></td>

            <td class="main" align="center"><b><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_VENDORS_SHIP; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_SHIPPING_COST; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_TAX; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="main" align="right"><b><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></b></td>
          </tr>
<?php
$package_num = sizeof($these_products);
$box_num = $l - 1;
  for ($l=0, $m=sizeof($these_products); $l<$m; $l++) {
  echo '<tr class="dataTableRow">' . "\n" .
  '                <td class="main" valign="center"><b>Shipment Number ' . $box_num++ . '</b></td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['Vmodule'] . '</td>' . "\n" .
           '                <td class="main" valign="center" align="center">' . $these_products[$l]['Vmethod'] . '</td>' . "\n" .
           '                <td class="main" valign="center" align="center">' . $these_products[$l]['Vcost'] . '</td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['spacer'] . '</td>' . "\n" .
       '         <td class="main" valign="center" align="center">' . $these_products[$l]['spacer'] . '</td>';
    for ($i=0, $n=sizeof($these_products[$l]['orders_products']); $i<$n; $i++) {
      echo '          <tr>' . "\n" .

           '            <td class="main" valign="center" align="right">' . $these_products[$l]['orders_products'][$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="main" valign="center" align="left">' . $these_products[$l]['orders_products'][$i]['name'];

      if (isset($these_products[$l]['orders_products'][$i]['attributes']) && (sizeof($these_products[$l]['orders_products'][$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($these_products[$l]['orders_products'][$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $these_products[$l]['orders_products'][$i]['attributes'][$j]['option'] . ': ' . $these_products[$i]['orders_products'][$i]['attributes'][$j]['value'];
          if ($these_products[$l]['orders_products'][$i]['attributes'][$j]['price'] != '0') echo ' (' . $these_products[$l]['orders_products'][$i]['attributes'][$j]['prefix'] . $currencies->format($these_products[$l]['orders_products'][$i]['attributes'][$j]['price'] * $these_products[$l]['orders_products'][$i]['qty'], true, $these_info['currency'], $these_info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo     /*  //MVS   added lines for this data
      ['vendor_name'],
      ['vendor_ship'],
      ['shipping_method'],
      ['shipping_cost']
      */
       '            <td class="main" valign="center" align="center">' . $these_products[$l]['orders_products'][$i]['model'] . '</td>' . "\n" .
      '            <td class="main" valign="center" align="center">' . $these_products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
      '            <td class="main" valign="center" align="center">' . $these_products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
      '            <td class="main" valign="center" align="center">' . $these_products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .

           '            <td class="main" align="center" valign="center">' . tep_display_tax_value($these_products[$l]['orders_products'][$i]['tax']) . '%</td>' . "\n" .
           '            <td class="main" align="center" valign="center"><b>' . $currencies->format($these_products[$l]['orders_products'][$i]['final_price'], true, $these_info['currency'], $these_info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="main" align="right" valign="center"><b>' .  $currencies->format(tep_add_tax($these_products[$l]['orders_products'][$i]['final_price'], $these_products[$l]['orders_products'][$i]['tax']) * $these_products[$l]['orders_products'][$i]['qty'], true, $these_info['currency'], $these_info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>';
    }
    }
 ?>