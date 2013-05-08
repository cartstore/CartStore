<?php
/*
	This is the back-end PHP file for the CartStore AJAX Search Suggest

	You may use this code in your own projects as long as this
	copyright is left	in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

	The complete tutorial on how this works can be found at:
	http://www.dynamicajax.com/fr/AJAX_Suggest_Tutorial-271_290_312.html

	For more AJAX code and tutorials visit http://www.DynamicAJAX.com
	For more CartStore related tutorials and code examples visit http://www.cartstore.com

	Copyright 2006 Ryan Smith / 345 Technical / 345 Group.
*/
	require('includes/application_top.php');
	
	include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_EDIT);
	
	$sql = "select * from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id WHERE p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' AND (pd.products_name LIKE '%" . tep_db_input($_GET['search']) . "%' OR p.products_model LIKE '%" . tep_db_input($_GET['search']) . "%') LIMIT 25";
	
    $product_query = tep_db_query($sql);
?>
<table cellspacing="0" cellpadding="0" border="0"><tr><td>
<table>
<tr>
<td><?php echo TABLE_HEADING_PRODUCTS_STOCK; ?></td><td>&nbsp;&nbsp;&nbsp;</td><td><span class="bluelink"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></span></td><td>&nbsp;&nbsp;&nbsp;</td><td><span class="redlink"><?php echo TABLE_HEADING_PRODUCTS; ?></span></td>
		</tr>
  <?php
	
	while($product_array = tep_db_fetch_array($product_query)) {
	
	echo '<tr>';

   echo '<td><a href="edit_orders.php?action=add_product&oID=' .  $_GET['oID'] .'&step=2&add_product_products_id='. $product_array['products_id'] . '">' . $product_array['products_quantity'] . '</a></td><td>&nbsp;&nbsp;&nbsp;</td>';
  
   echo '<td><a href="edit_orders.php?action=add_product&oID=' .  $_GET['oID'] .'&step=2&add_product_products_id='. $product_array['products_id'] . '"><span class="bluelink">' . $product_array['products_model'] . '</span></a></td><td>&nbsp;&nbsp;&nbsp;</td>';

   echo '<td><a href="edit_orders.php?action=add_product&oID=' .  $_GET['oID'] .'&step=2&add_product_products_id='. $product_array['products_id'] . '"><span class="redlink">' . $product_array['products_name'] . '</span></a></td>' . "\n";
  
   echo '</tr>';
	
	}
	
	?>
   
   </table>
   </td></tr></table>