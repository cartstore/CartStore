<?php
/*
  $Id: admin/attributes_groups.php v1.0 2006/11/15 JanZ Exp $
  for Separate Pricing Per Customer
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Copyright (c) 2006
  
  Released under the GNU General Public License 
*/

  require('includes/application_top.php');
	
	if(isset($_POST['submitbutton_x']) || isset($_POST['submitbutton_y'])) { // "save" button pressed
    $attributes_hide_from_groups = '@,';
          if ( $_POST['hide'] ) { // if any of the checkboxes are checked
		        foreach($_POST['hide'] as $val) {
		        $attributes_hide_from_groups .= (int)$val . ','; 
		        } // end foreach
	        }
    $attributes_hide_from_groups = substr($attributes_hide_from_groups,0,strlen($attributes_hide_from_groups)-1); // remove last comma
		$sql_data_array = array('attributes_hide_from_groups' => $attributes_hide_from_groups);

		// retail price
		if(isset($_POST['attr_cg']) && in_array($_POST['attr_cg'][0], $_POST['attr_cg'])) {
			$insert_sql_data = array('options_values_price' => tep_db_prepare_input($_POST['attr_cg'][0]['price']),
			                        'price_prefix' => tep_db_prepare_input($_POST['attr_cg'][0]['price_prefix']));
		}
		// update of table products_attributes with retail price and hide_from information
		$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
		tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', "products_attributes_id = '" . (int)$_POST['attribute_id'] . "'");
		// continue with prices for customer groups other than retail
		foreach ($_POST['attr_cg'] as $customer_group_id => $data_array) {
			// skip retail
			if ( $customer_group_id == '0') { continue; }
			// from hidden field, 1 if already in products_attributes_groups, 0 if not
			if ($data_array['in_db'] == '1' && isset($data_array['del'])) {
				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . (int)$_POST['attribute_id'] . "' and customers_group_id = '" . $customer_group_id . "'");
			} elseif ($data_array['in_db'] == '1' && !isset($data_array['del'])) {
				if (isset($sql_data_array)) { unset($sql_data_array); }
				$sql_data_array = array('options_values_price' => tep_db_prepare_input($data_array['price']),
			                        'price_prefix' => tep_db_prepare_input($data_array['price_prefix']));
				tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_GROUPS, $sql_data_array, 'update', "products_attributes_id = '" . (int)$_POST['attribute_id'] . "' and customers_group_id = '" . (int)$customer_group_id . "'");
			} // end elseif ($data_array['in_db'] == '1')
			elseif ($data_array['in_db'] == '0' && isset($data_array['insert'])) {
				// insert new row in products_attributes_groups
				if (isset($sql_data_array)) { unset($sql_data_array); }
								$sql_data_array = array('products_attributes_id' => (int)$_POST['attribute_id'],
								'customers_group_id' => (int)$customer_group_id,
								'options_values_price' => tep_db_prepare_input($data_array['price']),
			          'price_prefix' => tep_db_prepare_input($data_array['price_prefix']),
				// products_id from hidden field
								'products_id' => (int)$_POST['products_id']);
				tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_GROUPS, $sql_data_array);
			} // end elseif ($data_array['in_db'] == '0' && isset($data_array['insert']))
			
		} // end foreach ($_POST['attr_cg'] as $customer_group_id => $data_array))
    $messageStack->add(NUMBER_OF_SAVES . (isset($_POST['no_of_saves']) ? (int)$_POST['no_of_saves']+1 : 0), 'success'); 

	} // end if(isset($_POST['submitbutton_x']) || isset($_POST['submitbutton_y'])) 
	
if (!isset($_GET['attribute_id'])) {
	  $messageStack->add(ERROR_NO_ATTRIBUTE_ID, 'error');
  } elseif (isset($_GET['attribute_id']) && !tep_not_null($_GET['attribute_id'])) {
	  $messageStack->add(ERROR_NO_ATTRIBUTE_ID, 'error');
  } else {
	  $attribute_no = (int)$_GET['attribute_id'];
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }

	if (isset($attribute_no)) {
		  $product_and_option_name_query = tep_db_query("select pa.options_values_price, pa.price_prefix, pa.attributes_hide_from_groups, pa.products_id, pd.products_name, po.products_options_name, pov.products_options_values_name from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_OPTIONS . " po on pa.options_id = po.products_options_id left join " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on pa.options_values_id = pov.products_options_values_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id where pa.products_attributes_id = '" . $attribute_no . "' and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and  pov.language_id = '" . (int)$languages_id . "'");
		  $product_and_option_name = tep_db_fetch_array($product_and_option_name_query);
			// array with price and prefix for group retail
			$retail_price_and_prefix = array('options_values_price' => $product_and_option_name['options_values_price'], 'price_prefix' => $product_and_option_name['price_prefix']);
			// array for attribute_hide_from_groups
			$hide_from_groups_array = explode(',' , $product_and_option_name['attributes_hide_from_groups']);
			$hide_from_groups_array = array_slice($hide_from_groups_array, 1); // remove "@" from the array


		$attribute_cg_query = tep_db_query("select customers_group_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " pag where products_attributes_id = '" . $attribute_no . "'");

		while ($_attributes_cg = tep_db_fetch_array($attribute_cg_query)) {
					$attributes_cg[] = $_attributes_cg;
				}
  $no_of_attributes_cg = count($attributes_cg);
	
  $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . "  order by customers_group_id");

  while ($_customers_groups = tep_db_fetch_array($customers_groups_query)) {
		$customers_groups[] = $_customers_groups;
	}
	$no_of_customer_groups = count($customers_groups);
	for ($x = 0; $x < $no_of_customer_groups; $x++) {
		$new_attributes_cg[$x] = $customers_groups[$x];
		$new_attributes_cg['0'] = array_merge($new_attributes_cg['0'], $retail_price_and_prefix);
		for ($i = 0; $i < $no_of_attributes_cg; $i++) {
			// customer group 0 is not in the table products_attributes_groups but price and prefix are in
			// the table product_attributes
			if( $customers_groups[$x]['customers_group_id'] == $attributes_cg[$i]['customers_group_id'] ) {
			$new_attributes_cg[$x] = array_merge($new_attributes_cg[$x], $attributes_cg[$i]);
		    }
	  }
	} // end for ($x = 0; $x < $no_of_customer_groups; $x++)
// debug:
//			echo '<pre>Attributes: ';
//		print_r($new_attributes_cg);
?>
<div align="center" style="margin-top: 30px;">
<?php 
echo '<form name="attributes_groups" action="' . tep_href_link(FILENAME_ATTRIBUTES_GROUPS,'attribute_id=' . $attribute_no, 'NONSSL') . '"  method="post">' ."\n";
echo tep_draw_hidden_field('attribute_id', $attribute_no) . "\n";
echo tep_draw_hidden_field('products_id', (int)$product_and_option_name['products_id']) . "\n";
  if (isset($_POST['no_of_saves'])) {
	  $noofsaves = (int)$_POST['no_of_saves']+1;
  } else {
    $noofsaves = '0';
	}
echo tep_draw_hidden_field('no_of_saves', $noofsaves) . "\n";
?>
<table border="0" cellspacing="0" cellpadding="2">
		<tr>
      <td class="pageHeading" colspan="7"><?php echo HEADING_TITLE; ?></td>
		</tr>
    <tr>
      <td colspan="7"><?php echo tep_black_line(); ?></td>
    </tr>
    <tr class="dataTableHeadingRow">
		  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
		  <td class="dataTableHeadingContent" colspan="4">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
		</tr>
    <tr>
      <td colspan="7"><?php echo tep_black_line(); ?></td>
    </tr>
    <tr class="attributes-even">
		  <td class="smallText">&nbsp;<?php echo $attribute_no; ?>&nbsp;</td>
		  <td class="smallText" colspan="4">&nbsp;<?php echo $product_and_option_name['products_name']; ?>&nbsp;</td>
			<td class="smallText">&nbsp;<?php echo $product_and_option_name['products_options_name']; ?>&nbsp;</td>
			<td class="smallText">&nbsp;<?php echo $product_and_option_name['products_options_values_name']; ?>&nbsp;</td>
		</tr>
    <tr>
      <td style="margin-top: 50px;">&#160;</td>
      <td>&#160;</td>
      <td>&#160;</td>
      <td>&#160;</td>
      <td>&#160;</td>
      <td>&#160;</td>
      <td>&#160;</td>
    </tr>
    <tr>
      <td colspan="7"><?php echo tep_black_line(); ?></td>
    </tr>
    <tr class="dataTableHeadingRow">
		  <td class="dataTableHeadingContent" colspan="2">&nbsp;<?php echo TABLE_HEADING_GROUP_NAME; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_DELETE; ?>&nbsp;</td>
			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_INSERT; ?>&nbsp;</td>
			<td class="dataTableHeadingContent"align="center">&nbsp;<?php echo TABLE_HEADING_HIDDEN; ?>&nbsp;</td>
		</tr>
    <tr>
      <td colspan="7"><?php echo tep_black_line(); ?></td>
    </tr>
<?php
  $price_prefix_array = array(array('id' => '+', 'text' => '+'),
                              array('id' => '-', 'text' => '-'));
	$rows = 0;
	for ($x = 0; $x < $no_of_customer_groups; $x++) {
		echo '<tr class="' . (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd') . '">' . "\n";
		echo '<td class="smallText" colspan="2">' . $new_attributes_cg[$x]['customers_group_name'] . '</td>' . "\n";
		echo '<td class="smallText">' . tep_draw_pull_down_menu('attr_cg['. $new_attributes_cg[$x]['customers_group_id'] .'][price_prefix]', $price_prefix_array, (($new_attributes_cg[$x]['price_prefix'] == '-') ? '-' : '+')) . '</td>' . "\n";
		echo '<td class="smallText"><input name="attr_cg[' . $new_attributes_cg[$x]['customers_group_id'] . '][price]" value="' . $new_attributes_cg[$x]['options_values_price'] . '" size="8" /></td>' . "\n";
		if ($new_attributes_cg[$x]['customers_group_id'] != '0') {
		echo '<input type="hidden" name="attr_cg[' . $new_attributes_cg[$x]['customers_group_id'] . '][in_db]" value="' . 
		(isset($new_attributes_cg[$x]['price_prefix']) ? 1 : 0) . '">';
		}
		echo '<td class="smallText" align="center">' . ((isset($new_attributes_cg[$x]['price_prefix']) && $new_attributes_cg[$x]['customers_group_id'] != '0') ? tep_draw_checkbox_field('attr_cg[' . $new_attributes_cg[$x]['customers_group_id'] . '][del]') : '&nbsp;')  . '</td>' . "\n";
		echo '<td class="smallText" align="center">' . ((!isset($new_attributes_cg[$x]['price_prefix']) && $new_attributes_cg[$x]['customers_group_id'] != '0') ? tep_draw_checkbox_field('attr_cg[' . $new_attributes_cg[$x]['customers_group_id'] . '][insert]') : '&nbsp;')  . '</td>'. "\n";
		echo '<td class="smallText" align="center">' . tep_draw_checkbox_field('hide[' . $new_attributes_cg[$x]['customers_group_id'] . ']', $new_attributes_cg[$x]['customers_group_id'], (in_array($new_attributes_cg[$x]['customers_group_id'], $hide_from_groups_array )) ? 1 : 0) . '</td>'. "\n";
		$rows++;
		echo '</tr>' . "\n";
	} // end for ($x = 0; $x < $no_of_customer_groups; $x++)
?>
</table>
<?php echo '<p style="margin-top: 20px;">' . tep_image_submit('button_save.gif', IMAGE_SAVE, 'name="submitbutton"') . '&#160;' . tep_image_button('button_cancel.gif', IMAGE_CANCEL, 'onclick=\'self.close()\'') .'</p>' . "\n";
?>
</form>
</div>
<?php
} // end if (isset($attribute_no))
  else {
echo '<div align="center" style="margin-top: 50px;">' . "\n" . '<form name="close">' . "\n" . tep_image_button('button_cancel.gif', IMAGE_CLOSE, 'onclick=\'self.close()\'') .'</form>' . "\n" . '</div>' . "\n";
}
?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>