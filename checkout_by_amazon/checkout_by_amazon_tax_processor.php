<?php

/**
* Copyright 2008 Amazon.com, Inc., or its affiliates. All Rights Reserved.
*
* Licensed under the Apache License, Version 2.0 (the "License").
* You may not use this file except in compliance with the License.
* A copy of the License is located at
*
*    http://aws.amazon.com/apache2.0/
*
* or in the "license" file accompanying this file.
* This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing permissions
* and limitations under the License.
*
*
* @brief CallBack File
* @catagory Checkout By Amazon  - Tax Processor file
* @author Srilakshmi Gorur
* @version $Id: $
*
*/
require_once('checkout_by_amazon_util_dao.php');
function getTax($skuArray, $shipping) {
	$utilDao = new UtilDAO();
	$tax_rate = 0.0;
	$taxTableArray = array();
	foreach ($skuArray as $sku) {
		 $tax_rate = calculateTax($sku, $shipping, $utilDao);
                 $taxRule = array();
	         $taxRule['Rate'] = $tax_rate;
		 if ($utilDao->tep_get_configuration_value("MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED") == 'True') {
                	$taxRule['IsShippingTaxed'] = "true";
	         } else {
        	        $taxRule['IsShippingTaxed'] = "false";
       		 }

	           // 223 -> US 
        	if ($utilDao->tep_get_country_id($shipping[CountryCode]) == '223') {
	             $taxRule["PredefinedRegion"] = 'USAll';
          	}
	        else {
        	     $taxRule["PredefinedRegion"] = 'WorldAll';
           	}
	        $pos = strpos($sku, '{');
	        if($pos === false)
        	        $sku_mod = $sku;
                else
                	$sku_mod = substr($sku, 0, $pos);

           	$taxTableArray[]['TaxTable'] = array ('TaxTableId' => "Tax-for-SKU-".$sku_mod,
                                                      'TaxRules' => array('TaxRule' => $taxRule)                               
	                                              );

	}
        return $taxTableArray;

}
function calculateTax($sku, $shipping, $utilDao) {
/* Setting the Tax Table */
	$tax = tep_get_tax_rate($utilDao->tep_get_tax_class_id($sku), $utilDao->tep_get_country_id($shipping[CountryCode]), $utilDao->tep_get_zone_id($shipping[State])) / 100.00;
	return $tax;
}
?>
