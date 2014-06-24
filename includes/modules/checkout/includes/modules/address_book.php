	<h3 class="text-left"><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES . tep_draw_hidden_field('action', 'selectAddress') . tep_draw_hidden_field('address_type', $addressType); ?></h3>

	<table class="table table-striped">
<?php
	  $radio_buttons = 0;

	  $checked = ($addressType == 'shipping' ? $sendto : $billto);
	  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_street_address_2 as street_address_2, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
	  while ($addresses = tep_db_fetch_array($addresses_query)) {
		$format_id = tep_get_address_format_id($addresses['country_id']);
?>
	   <tr class="moduleRow">
		<td class="main text-center" ><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $checked)); ?></td>
		  <td class="main text-left">
		  	<strong><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></strong>
		  	<blockquote><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br>'); ?></blockquote>
		  </td>
	 </tr>
<?php
		$radio_buttons++;
	  }
?>
	</table>
