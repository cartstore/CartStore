<?php
/*
  estimated_shipping.php v1.0 (by Wheel of Tiime)

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2002 Will Mays

  GNU General Public License Compatible
*/
chdir('../');
include("includes/application_top.php");
?>
<!-- estimated_shipping BOF//-->
<?php

	//// BEGIN:  Added for Estimated Shipping v1.0
	//   include estimated shipping functions
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ESTIMATED_SHIPPING);
	require(DIR_WS_FUNCTIONS . 'estimated_shipping_functions.php');
	//// END:  Added for Estimated Shipping v1.000

	if ($cart->count_contents() > 0 && (((substr(basename($PHP_SELF), 0, 8) != 'checkout') && substr(basename($PHP_SELF), 0, 7) != 'account'))) {
		$info_box_contents = array();
		$info_box_contents[] = array('align' => '',
									 'text'  => TABLE_HEADING_ESTIMATED_SHIPPING);


		if (tep_session_is_registered('country')) {
			if (isset($_POST['country'])) {
				$country = $_POST['country'];
			}
		}

		if (tep_session_is_registered('estzipcode')) {
			if (isset($_POST['estzipcode'])) {
				$estzipcode = $_POST['estzipcode'];
			}
		}

		if ((tep_session_is_registered('customer_id')) && (!tep_not_null($country))) {
			$country = $customer_country_id;
		}

		if ((tep_session_is_registered('customer_id')) && (!tep_not_null($estzipcode))) {
			$check_address_query = tep_db_query("select entry_postcode from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");
			$check_address = tep_db_fetch_array($check_address_query);
			$estzipcode = $check_address['entry_postcode'];


		}

		$info_box_contents = array();
//		$info_box_contents[] = array('form' => tep_draw_form('estimated_shipping', tep_href_link(basename($PHP_SELF), '',$request_type, false), 'post') .  tep_hide_session_id(),
//									 'align' => 'left',
//									 'text' => TEXT_EXPLAIN_ESTIMATED_SHIPPING . '<BR>' .  TEXT_COUNTRY_TO_SHIP_TO . '<BR><TABLE WIDTH="100%"><TR><TH>' . tep_get_country_list('country', $country, 'onChange="this.form.submit();" style="width: 100%"') . '</TH><TR></TABLE>' . tep_get_estimated_shipping_quotes($country,  $_GET['action'],$customer_country_id));


if (isset($_POST['estzipcode'])) { $estzipcode = $_POST['estzipcode'];} else { $estzipcode="";}


if (isset($_POST['country'])) { $country = $_POST['country']; }

if (isset($_POST['country']) && $_POST['country']!="" ) {


$list_ctyp=tep_get_estimated_shipping_quotes($country,  $_GET['action'],$customer_country_id);

} else { $list_ctyp="";}
		$info_box_contents[] = array('form' => tep_draw_form('estimated_shipping', tep_href_link(FILENAME_SHOPPING_CART, '',$request_type, false), 'post', 'id="estShipForm" onsubmit="return getEstShipping();"') .  tep_hide_session_id(),
									 'align' => '',
									 'text' => '
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td class="t1"><b >Shipping Estimator</b> </td>
   </tr>
  <tr>
    <td class="alltext">Use this form to estimate shipping on product in your cart </td>
  </tr>
  <tr>
    <td class="horzdot">&nbsp;</td>
  </tr>

  <tr>
    <td class="t1">Select country to ship to:</td>
  </tr>
  <tr>
    <td>' . tep_get_country_list('country', $country) . '</td>
  </tr>
  <tr>
    <td>' . ENTRY_POST_CODE . '&nbsp;' . tep_draw_input_field('estzipcode', $estzipcode,'MAXLENGTH="20" SIZE="10" VALUE=""') . '</td>
  </tr>
  <tr>
    <td>' . tep_image_submit('button_continue.gif', Submit) . '</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
 function getEstShipping(){
  var fields = jQuery("#estShipForm").serialize();
  jQuery("#est_shipping").html(\'<div align="center"><img src="images/loading.gif" alt="Loading..."></div>\');
  jQuery.ajax({
    url: "ext/estimated_shipping.php",
    data: fields,
    type: "post",
    success: function(data){
      jQuery("#est_shipping").html(data);
    },
    error: function(){
     jQuery.ajax({
       url: "ext/estimated_shipping.php",
       success: function(data){
         jQuery("#est_shipping").html(data);
       }
     });
    }
  });
  return false;
 }
</script>
' . $list_ctyp);
		new estimatedshippingBox($info_box_contents);
	} else {
		if (tep_session_is_registered('country')) {
			tep_session_unregister('country');
		}

		if (tep_session_is_registered('estzipcode')) {
			tep_session_unregister('estzipcode');
		}

	}
?>
<!-- estimated_shipping EOF //-->
