<?php 
//----------------------------------------------------------------------------------------
// Program: UPC(Universal Payment Connector) DIRECT for PayPal Websites Payment Pro 
// Version: 3.0
// Author: Sound Commerce LLC
// URL: http://www.creditcardscripts.com
// Copyright (c) 2005 Sound Commerce LLC
// All rights reserved.
//
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.
//
//----------------------------------------------------------------------------------------


//matches the macro values to the configuration settings 

$xml_config_array=array(
"PAYPAL_USERNAME"=>"$paypal[username]",
"PAYPAL_PASSWORD"=>"$paypal[password]",
"PAYPAL_API_ACCESS_USERNAME"=>"$paypal[api_access_username]", 
"PAYPAL_URL"=>"$paypal[url]",
"PAYPAL_BUTTON_SOURCE"=>"$paypal[button_source]",
"PAYPAL_NOTIFY_URL"=>"$paypal[notify_url]", 
"PAYPAL_RETURN_URL"=>"$paypal[return_url]",
"PAYPAL_CANCEL_URL"=>"$paypal[cancel_url]", 
"PAYPAL_VERSION"=>"$paypal[version]", 
"PAYPAL_PAYMENT_ACTION"=>"$paypal[payment_action]", 
"PAYPAL_TOKEN"=>"$paypal[token]", 
"PAYPAL_PAYER_ID"=>"$paypal[payer_id]", 
"PAYPAL_INVOICE_ID"=>"$paypal[invoice_id]", 
"PAYPAL_ORDER_DESCRIPTION"=>"$paypal[order_description]", 
"PAYPAL_ORDER_TOTAL"=>"$paypal[order_total]", 
"PAYPAL_ITEM_TOTAL"=>"$paypal[item_total]", 
"PAYPAL_SHIPPING_TOTAL"=>"$paypal[shipping_total]", 
"PAYPAL_HANDLING_TOTAL"=>"$paypal[handling_total]", 
"PAYPAL_TAX_TOTAL"=>"$paypal[tax_total]", 
"PAYPAL_MAX_AMOUNT"=>"$paypal[max_amount]", 
"PAYPAL_REQUIRE_CONFIRM_SHIPPING"=>"$paypal[require_confirm_shipping]",
"PAYPAL_CURRENCY"=>"$paypal[currency]", 
"PAYPAL_CPP_HEADER_IMAGE"=>"$paypal[cpp_header_image]", 
"PAYPAL_CPP_HEADER_BORDER_COLOR"=>"$paypal[cpp_header_border_color]", 
"PAYPAL_CPP_HEADER_BACK_COLOR"=>"$paypal[cpp_header_back_color]", 
"PAYPAL_CPP_PAYFLOW_COLOR"=>"$paypal[cpp_payflow_color]", 
"PAYPAL_PAGE_STYLE"=>"$paypal[page_style]", 
"PAYPAL_REQUIRE_CONFIRM_SHIPPING"=>"$paypal[require_confirm_shipping]", 
"PAYPAL_NO_SHIPPING"=>"$paypal[no_shipping]", 
"PAYPAL_ADDRESS_OVERRIDE"=>"$paypal[address_override]",  
"PAYPAL_LOCALE_CODE"=>"$paypal[US]",  
"PAYPAL_NAME"=>"$paypal[name]",  
"PAYPAL_FIRST_NAME"=>"$paypal[firstname]",  
"PAYPAL_LAST_NAME"=>"$paypal[lastname]",  
"PAYPAL_BUYER_EMAIL"=>"$paypal[buyer_email]",  
"PAYPAL_ADDRESS1"=>"$paypal[address1]",  
"PAYPAL_ADDRESS2"=>"$paypal[address2]",  
"PAYPAL_CITY"=>"$paypal[city]", 
"PAYPAL_STATE"=>"$paypal[state]", 
"PAYPAL_ZIP"=>"$paypal[zip]", 
"PAYPAL_COUNTRY"=>"$paypal[country]",
"PAYPAL_SHIPPING_NAME"=>"$paypal[shipping_name]",   
"PAYPAL_SHIPPING_ADDRESS1"=>"$paypal[shipping_address1]",  
"PAYPAL_SHIPPING_ADDRESS2"=>"$paypal[shipping_address2]",  
"PAYPAL_SHIPPING_CITY"=>"$paypal[shipping_city]", 
"PAYPAL_SHIPPING_STATE"=>"$paypal[shipping_state]", 
"PAYPAL_SHIPPING_ZIP"=>"$paypal[shipping_zip]", 
"PAYPAL_SHIPPING_COUNTRY"=>"$paypal[country]", 
"PAYPAL_CUSTOM"=>"$paypal[custom]",
"PAYPAL_CC_TYPE"=>"$paypal[cc_type]",  
"PAYPAL_CC_NUMBER"=>"$paypal[cc_number]", 
"PAYPAL_CC_EXP_MONTH"=>"$paypal[cc_exp_month]", 
"PAYPAL_CC_EXP_YEAR"=>"$paypal[cc_exp_year]", 
"PAYPAL_CC_CVV2"=>"$paypal[cc_cvv2]", 
"PAYPAL_IP_ADDRESS"=>"$paypal[ipaddress]",
"PAYPAL_MERCHANT_SESSION_ID"=>"$paypal[merchant_session_id]"
);


switch($paypal[action]) { 

case 1:

$paypal[xml_response_end_tag]="SetExpressCheckoutResponse"; 

break; 

case 2:

$paypal[xml_response_end_tag]="GetExpressCheckoutDetailsResponse"; 

break; 

case 3:

$paypal[xml_response_end_tag]="DoExpressCheckoutPaymentResponse"; 

break; 

case 4:

$paypal[xml_response_end_tag]="DoDirectPaymentResponse"; 

break;


} 


$counter=0; 
$xml_data= array();
$xml_current_tag_state='';

//XML FUNCTIONS

function startElementHandler($xml_link,$string,$array) { 
global $xml_data,$xml_current_tag_state;

$xml_current_tag_state=$string;



}

function characterDataHandler($xml_link,$string_data) { 
global $xml_data,$xml_current_tag_state;

if($xml_current_tag_state=='') { 

return; 

}

$xml_data[$xml_current_tag_state]=$string_data; 



}


function endElementHandler($xml_link,$string) {
global $xml_data,$xml_current_tag_state;
$xml_current_tag_state='';

if($string == $paypal[xml_response_end_tag]) { 


}

}



function createXMLParser() { 

$xml_link=xml_parser_create();

if($xml_link == false) { die('Unable to create XML Parser'); } 

return $xml_link;  


}



function parseXML($xml_link,$data,$endTag)  { 
global $counter,$xml_data,$xml_current_tag_state;

//set parser options
xml_parser_set_option($xml_link,XML_OPTION_CASE_FOLDING,false);

//set xml element handler
xml_set_element_handler($xml_link,"startElementHandler","endElementHandler"); 

//set character data handler
xml_set_character_data_handler($xml_link,"characterDataHandler"); 

$parseResult=@xml_parse($xml_link,$data); 



//check errors
if(!$parseResult && xml_get_error_code($xml_link) != XML_ERROR_NONE) { 

die('XmlParse Error:' . xml_error_string(xml_get_error_code($xml_link)) . 'at line' . xml_get_current_line_number($xml_link)); 

}

return $xml_data; 

} 






function fileRead($fileName) { 

if(is_readable($fileName)) { 

$fp=fopen($fileName,"r"); 

$fileInfo = fread($fp,filesize($fileName));

return $fileInfo; 

}

else { return false; } 

} 



function tep_get_country_by_iso_code($country) { 
$result = tep_db_query("select countries_id FROM " . TABLE_COUNTRIES . " where countries_iso_code_2 = '" . $country . "'");
$country_id = tep_db_fetch_array($result);

return $country_id['countries_id']; 

}


?> 