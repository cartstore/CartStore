<?php 


//xml routine 

$counter=0; 
$xml_current_tag_state='';




//read xml file

switch($paypal[action]) { 

case 1:

$paypal[xml_current_file]="setExpressCheckout.xml"; 

break; 

case 2:

$paypal[xml_current_file]="getExpressCheckoutDetails.xml"; 

break; 

case 3: 

$paypal[xml_current_file]="doExpressCheckout.xml"; 

break; 

case 4: 

$paypal[xml_current_file]="doDirectPayment.xml"; 

break; 

} 



$data=fileRead(DIR_FS_CATALOG . "paypal_wpp/includes/xml/$paypal[xml_current_file]"); 


//replace buyer email if empty
if(empty($paypal[buyer_email])) {
$data=ereg_replace("<BuyerEmail>PAYPAL_BUYER_EMAIL</BuyerEmail>","",$data); 
}

foreach($xml_config_array as $i=>$v) { 
$data=ereg_replace($i,$v,$data); 

} 




//check cURL Extension 

if(eregi("true",$paypal[useLibCurl])) {


if(extension_loaded('curl')) { 

//check for certificate file
if(file_exists($paypal[certificate_file])) { 






$ch=curl_init(); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_SSLCERTTYPE ,"PEM"); 
curl_setopt($ch, CURLOPT_SSLCERT,$paypal[certificate_file]);
curl_setopt($ch,CURLOPT_URL,$paypal[url]); 
curl_setopt($ch,CURLOPT_POST,1); 
curl_setopt($ch,CURLOPT_POSTFIELDS,$data); 

//Start ob to prevent curl_exec from displaying stuff. 
ob_start(); 
curl_exec($ch);

//Get contents of output buffer into the paypal_array. 
$paypal_array=ob_get_contents(); 
curl_close($ch);

//End ob and erase contents.  
ob_end_clean(); 

  } 

else { 
$upc_results['ErrorCode']="2030"; 
$upc_results['internal_error_code']="2030"; 
$upc_results['Ack']="Failure";
$upc_results['LongMessage']="Unable to find API certificate file.";
}


}

else { 

//set error message
$upc_results['ErrorCode']="2040"; 
$upc_results['internal_error_code']="2040"; 
$upc_results['Ack']="Failure";
$upc_results['LongMessage']="cURL Extension Not Found.";

}

  }
  






//Execute cURL via Command Line
  
else { 



//check safe mode
if(ini_get('safe_mode') == 1) { 
$upc_results['ErrorCode']="2050"; 
$upc_results['internal_error_code']="2050"; 
$upc_results['Ack']="Failure";
$upc_results['LongMessage']="Safe Mode must be turned Off."; 

} 

//check curl location 
if(!(@is_file($paypal[curl_location]))) { 
$upc_results['ErrorCode']="2060"; 
$upc_results['internal_error_code']="2060"; 
$upc_results['Ack']="Failure";
$upc_results['LongMessage']="Unable to locate cURL binary ($paypal[curl_location])."; 

}


//check errors 
if(empty($upc_results['LongMessage'])) { 

exec("$paypal[curl_location] -E \"$paypal[certificate_file]\" -d \"$data\" $paypal[url]",$paypal_array);

 } 
 
}




$xml_link=createXMLParser(); 

//parse xml file if script passes all internal errors
if(!(isset($upc_results['internal_error_code']))) { $upc_results=parseXML($xml_link,$paypal_array,$endTag) ; } 



?>