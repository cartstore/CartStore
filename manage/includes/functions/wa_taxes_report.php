<?php
/*
 $Id: wa_taxes_report.php v2.2b 1739 2008-07-28 lildog $

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2008 osCommerce

 Released under the GNU General Public License
 */

//BOF WA State Tax Modification
function parse_DOR($street_address, $address_city, $address_postcode) {
	$tax_address['entry_street_address'] = $street_address;
	$tax_address['entry_city'] = $address_city;
	$tax_address['entry_postcode'] = $address_postcode;
	$url = "http://dor.wa.gov/AddressRates.aspx?output=text";
	$url .= "&addr=" . urlencode($tax_address['entry_street_address']);
	$url .= "&city=" . urlencode($tax_address['entry_city']);
	$url .= "&zip=" . urlencode($tax_address['entry_postcode']);
	$resultString = '';

	// Start a cURL session and get the data
	if ($handle = curl_init($url)) {
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_HEADER, 0);
		curl_setopt($handle, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($handle, CURLOPT_FRESH_CONNECT, 1);
		if ($resultString = curl_exec($handle))
			curl_close($handle);

		if (!empty($resultString)) {
			$results = explode(' ', $resultString);
			foreach ($results as $key => $value) {
				$breakPosition = strpos($value, "=");
				$newKey = substr($value, 0, $breakPosition);
				$newValue = substr($value, $breakPosition + 1);
				$results[$newKey] = $newValue;
			}

			$locationcode = $results['LocationCode'];
			$rate = $results['Rate'] * 100;
			$resultcode = $results['ResultCode'];
			// makes an array
			$wa_dest_tax_rate = array('locationcode' => $locationcode, 'rate' => $rate, 'resultcode' => $resultcode);

			if ((int)$wa_dest_tax_rate['resultcode'] < 3) {
				return $results['LocationCode'];
			}
		}
	}
}

//// BOF Strip Currency Sign
// Strip currency signs from inputted data
// if you use a different currency symbol add it after \$,£,¢,´,?.
function tep_strip_currency_sign($data) {
	return trim($data, "\$,£,¢,´,?");
}

//// EOF Strip Currency Sign
?>