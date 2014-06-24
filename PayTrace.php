<?php
/*
  $Id: PayTrace.php,v 1.00 2006/04/15 01:02:00 project1000 Exp $

  PayTrace, The secure advantage.
  https://PayTrace.com

  Copyright (c) 2006 PayTrace, LLC
  
  
  *********************************
  
  
  This file should be located:
  
  /catalog/PayTrace.php
  
  
  
  *********************************
  
  PayTrace strongly advises all merchants using the PayTrace API to install use a SSL certificate 
  to encrypt all sensitive information entered in their shopping cart by the their customers.  
  Whether the merchant is using osCommerce or another solution, the use of SSL encryption is strongly 
  recommended.
  
  Additionally, PayTrace strongly advises that NO CARDHOLDER DATA (CHD) is stored by any merchant, and
  CSC values may never be stored.
  
  *********************************
  

  Released under the GNU General Public License
*/


require('includes/application_top.php');


if ( $_POST["UN"] == "" || $_POST["PSWD"] == "" ) {
    echo "Your transaction could not be processed. <a href=" . tep_href_link("index.php") . ">Return to catalog.</a><br>";
}

//process transaction
else { 

//format the parameter string to process a transaction through PayTrace
$parmlist = "parmlist=UN~" . $_POST["UN"] . "|PSWD~" . $_POST["PSWD"] . "|TERMS~" . $_POST["TERMS"] . "|";
$parmlist .= "METHOD~" . $_POST["METHOD"] . "|TRANXTYPE~" . $_POST["TRANXTYPE"] . "|";
$parmlist .= "CC~" . $_POST["CC"] . "|EXPMNTH~" . $_POST["EXPMNTH"] . "|EXPYR~" . $_POST["EXPYR"] . "|";
$parmlist .= "AMOUNT~" . $_POST["AMOUNT"] * .01 . "|CSC~" . $_POST["CSC"] . "|";
$parmlist .= "BADDRESS~" . $_POST["BADDRESS"] . "|BZIP~" . $_POST["BZIP"] . "|";



$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");

//point the cUrl to PayTrace's servers
$url = "https://paytrace.com/api/default.pay";

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

//Depending on your PHP Host, you may need to specify their proxy server
curl_setopt ($ch, CURLOPT_PROXY, $_POST["PROXY"]);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//http://64.202.165.130:3128
//The proxy information above is for GoDaddy.com

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $parmlist);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_TIMEOUT, 10);

// grab URL and pass it to the browser
$response = curl_exec($ch);

// close curl resource, and free up system resources
curl_close($ch);


//parse through the response.
$responseArr = explode('|', $response);
foreach ($responseArr as $pair ){
$tmp = explode('~',$pair);
$vars[$tmp[0]] = $tmp[1];
}

$approved = False;

//search through the name/value pairs for the APCODE
foreach($vars as $key => $value){

if ( $key == "APPCODE" ) {

   if ( $value != "" ) {

      $approved = True; 
   
   }
   
}
elseif ( $key == "ERROR" ) {
	
	$ErrorMessage .= $value;

}

} // end for loop

if ( $ErrorMessage != "" ) {

   echo "Your transaction was not successful per this response, " . $ErrorMessage . " <a href=" . tep_href_link("index.php") . ">Return to catalog.</a><br>";

} 

else {


	if ( $approved == True ) {
	
	    tep_redirect(tep_href_link("checkout_process.php"));
	
	}
	
	else {
	
		 echo "Your transaction was not successful was not approved. <a href=" . tep_href_link("index.php") . ">Return to catalog.</a><br>";

	
	} //end if transaction was approved


} //end if error message



} //end if/else that UN is empty

// code
?>