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


function tep_get_state_by_name($state) { 
$result = tep_db_query("select zone_code FROM " . TABLE_ZONES . " where  zone_name= '" . $state . "'");
$state_id = tep_db_fetch_array($result);

return $state_id['zone_code']; 

}

//set url
if(MODULE_PAYMENT_PAYPAL_EC_GATEWAY_SERVER == "Test") { 

$paypal['url']="https://api.sandbox.paypal.com/2.0/"; 
$paypal['express_checkout_url']="https://www.sandbox.paypal.com/cgi-bin/webscr"; 
} 

else { 
$paypal['url']="https://api.paypal.com/2.0/"; 
$paypal['express_checkout_url']="https://www.paypal.com/cgi-bin/webscr"; 

} 

//paypal account information 
$paypal['username']=MODULE_PAYMENT_PAYPAL_EC_USERNAME;
$paypal['password']=MODULE_PAYMENT_PAYPAL_EC_PASSWORD;
$paypal['api_access_username']=""; 

//configuration information  
$paypal['button_source']=MODULE_PAYMENT_PAYPAL_EC_BN; 
$paypal['action']=$_REQUEST[express];  //1=setExpressCheckOut 2=getExpressCheckOutDetails 3=doExpressCheckout 4=DirectPay
$paypal['notify_url']=MODULE_PAYMENT_PAYPAL_EC_IPN_URL; 
$paypal['return_url']=tep_href_link('paypal_wpp/ec/return.php', '', 'SSL');
$paypal['cancel_url']=tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL');
$paypal['version']="1.0"; 
$paypal['useLibCurl']=MODULE_PAYMENT_PAYPAL_EC_USE_LIB_CURL;
$paypal['curl_location']=MODULE_PAYMENT_PAYPAL_EC_CURL_PATH;
$paypal['certificate_file']=DIR_FS_CATALOG . MODULE_PAYMENT_PAYPAL_EC_CERT_FILE;

//transaction information 

$paypal['payment_action']=MODULE_PAYMENT_PAYPAL_EC_PAYMENT_ACTION; 
$paypal['token']=$paypal_token; 
$paypal['payer_id']=$pp_payer_id;
$paypal['invoice_id']=""; 
$paypal['order_description']="";  
$paypal['order_total']=$_REQUEST[amount];  
$paypal['item_total']="";
$paypal['shipping_total']="";  
$paypal['handling_total']="";
$paypal['tax_total']="";
$paypal['max_amount']=""; 
$paypal['currency']="USD";  
$paypal['ipaddress']=getenv('REMOTE_ADDR');
$paypal['merchant_session_id']=tep_session_id(); 

//credit card information 

$paypal['cc_type']=$_POST['paypal_cc_type'];
$paypal['cc_number']=$_POST['paypal_cc_number'];
$paypal['cc_exp_month']=$_POST['paypal_cc_expires_month']; 
$paypal['cc_exp_year']=$_POST['paypal_cc_expires_year'];
$paypal['cc_cvv2']=$_POST['paypal_cc_cvv2']; 


//payment page information 

$paypal['cpp_header_image']=""; 
$paypal['cpp_header_border_color']=""; 
$paypal['cpp_header_back_color']=""; 
$paypal['cpp_payflow_color']=""; 
$paypal['page_style']=""; 
$paypal['require_confirm_shipping']="";  //1=yes
$paypal['no_shipping']=""; //1=yes
$paypal['address_override']=""; //1=yes
$paypal['locale_code']= $language_code; 

//buyer information  

$paypal['name']=""; 
$paypal['firstname']=$_POST['paypal_cc_firstname']; 
$paypal['lastname']=$_POST['paypal_cc_lastname'];
$paypal['buyer_email']=$order->customer['email_address']; 
$paypal['address1']=$order->billing['street_address']; 
$paypal['address2']=""; 
$paypal['city']=$order->billing['city'];
if(($order->billing['country']['iso_code_2'] == 'US') && (strlen($order->billing['state']) > 2)) { $paypal['state']=tep_get_state_by_name($order->billing['state']); }
else { $paypal['state']=$order->billing['state']; } 
$paypal['zip']=$order->billing['postcode'];
$paypal['country']=$order->billing['country']['iso_code_2'];

//shipping information

$paypal['shipping_name']=$order->delivery['firstname'] . " " . $order->delivery['lastname'] ;
$paypal['shipping_address1']=$order->delivery['street_address']; 
$paypal['shipping_address2']="";
$paypal['shipping_city']=$order->delivery['city'] ;
if(($order->delivery['country']['iso_code_2'] == 'US') && (strlen($order->delivery['state']) > 2)) { $paypal['shipping_state']=tep_get_state_by_name($order->delivery['state']); }
else { $paypal['shipping_state']=$order->delivery['state']; }
$paypal['shipping_zip']=$order->delivery['postcode'];
$paypal['shipping_country']=$order->delivery['country']['iso_code_2']; 


//custom field

$paypal['custom']=""; 

?>