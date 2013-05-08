<?php
/*
  $Id: wa_taxes.php v2.1.7 1739 2008-07-28 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  
//BOF WA State Tax Modification
function parse_DOR(){  //Lookup WA State Sales tax from WA Department of Revenue website  
global $customer_zone_id, $customer_country_id, $billto, $sendto, $cart, $customer_id, $order, $wa_dest_tax_rate;
 tep_session_unregister('wa_dest_tax_rate');
  if (!tep_session_is_registered('wa_dest_tax_rate')){ // if the tax rate has already been retrieved don't do it again

    //get customer's address    
      $tax_address_query = tep_db_query("select ab.entry_street_address, ab.entry_city, ab.entry_postcode from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)(($cart->content_type == 'virtual' ) ? $billto : $sendto) . "'");
      if (($customer_id == 0) || ($order->customer['is_dummy_account'])) {
      	$address_noacc = $order->pwa_label_shipping;      $tax_address['entry_street_address'] = $address_noacc['street_address'];      $tax_address['entry_city'] = $address_noacc['city'];
      	$tax_address['entry_postcode'] = $address_noacc['postcode'];    
	  }else{      
      	$tax_address = tep_db_fetch_array($tax_address_query);    
	  }    
	  $url = "http://dor.wa.gov/AddressRates.aspx?output=text";
	  $url .= "&addr=".urlencode($tax_address['entry_street_address']); 
	  $url .= "&city=".urlencode($tax_address['entry_city']);    
	  $url .= "&zip=".urlencode($tax_address['entry_postcode']);
      $resultString = '';
     // Start a cURL session and get the data    
     if($handle = curl_init($url)){
      curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);      
      curl_setopt($handle, CURLOPT_HEADER, 0);      
      curl_setopt($handle, CURLOPT_FORBID_REUSE, 1);      
      curl_setopt($handle, CURLOPT_FRESH_CONNECT, 1);
      if($resultString = curl_exec($handle))
      	curl_close($handle);

      if (!empty($resultString)) {
        $results = explode(' ', $resultString);        
        foreach ($results as $key=>$value) {
        	          $breakPosition = strpos($value,"=");          
        	          $newKey = substr($value,0,$breakPosition);          
        	          $newValue = substr($value,$breakPosition+1);          
        	          $results[$newKey] = $newValue;        
        }
        $locationcode=$results['LocationCode'];
        $rate=$results['Rate']*100;
        $resultcode=$results['ResultCode'];
        // makes an array
        $wa_dest_tax_rate = array('locationcode' => $locationcode,'rate' => $rate,'resultcode' => $resultcode);
        tep_session_register('wa_dest_tax_rate');
               
        if ((int)$wa_dest_tax_rate['resultcode'] < 3){           
          return $results['Rate']*100;
        } //end if ((int)$results['ResultCode'] < 3
        
      } //end if (!empty($resultString))
       //we did not return a tax rate    
     } // end if($handle = curl_init($url))    
     // if we are here something is wrong & the stores tax rate will be used.
      tep_session_register('wa_dest_tax_rate');
      $wa_dest_tax_rate['rate']='false';
      $wa_dest_tax_rate['resultcode']='Failed to connect to the DOR API.';
                 
  }else{ // the session'wa_dest_tax_rate' is registered  (we have the tax rate) OR it is false(there was an error from DOR)  
    if ($wa_dest_tax_rate['rate'] != 'false'){    
      return $wa_dest_tax_rate['rate']; // use the tax rate previously gotten
    }else{
      //$wa_dest_tax_rate[rate] is false(there was an error from DOR) just continue to use the stores tax
      //uncomment the next line if you need to charge WA tax, but don't have an overall store tax rate
      //return $wa_dest_tax_rate[rate]=0.0900*100; 
    }
  }  // end if !tep_session_is_registered('wa_dest_tax_rate')
  
} // end parse_DOR function
//EOF WA State Tax Modification

function fetch_tax_error_code($DOR_code){
 if ($DOR_code='1'){
    return WA_DEST_TAX_ERROR_CODE_1.' '. WA_DEST_TAX_ERROR_CODE_MINOR. ' ' . WA_DEST_TAX_DOR_SITE;
  }else if($DOR_code='2'){
    return WA_DEST_TAX_ERROR_CODE_2.' '. WA_DEST_TAX_ERROR_CODE_MINOR. ' ' . WA_DEST_TAX_DOR_SITE;
  }else if($DOR_code='3'){
    return WA_DEST_TAX_ERROR_CODE_3. ' ' . WA_DEST_TAX_DOR_SITE;
  }else if($DOR_code='4'){
    return WA_DEST_TAX_ERROR_CODE_4. ' ' . WA_DEST_TAX_DOR_SITE;
  }else if($DOR_code='5'){
    return WA_DEST_TAX_ERROR_CODE_5. ' ' . WA_DEST_TAX_DOR_SITE;
  }else{
    return WA_DEST_TAX_ERROR_CODE_UNKNOWN. ' ' . WA_DEST_TAX_DOR_SITE;
  }
}

?>
