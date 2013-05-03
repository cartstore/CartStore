<html>
<head>
<title>DE-CRYPT SAGEPAY</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
/*
decrypt_sagepay.php is not needed except for de-bugging and does not have to be uploaded to the live site.

To use decrypt_sagepay.php navigate to line 87 which should look like this:

$de_crypt = SimpleXor(base64_decode($Crypt),'geavj7yt4G6Wiy8x');

the string 'geavj7yt4G6Wiy8x' is a fake SAGEPAY password.

You must substitute yours (the same one you enter in the admin panel) here for decrypt_sagepay.php to work.

then go to around line 30 in sagepay_form.php

            if (MODULE_PAYMENT_SAGEPAY_FORM_TEST_STATUS == 'true') {
        $this->form_action_url = 'https://test.sagepay.com/vps2form/submit.asp';
        //$this->form_action_url = '../../decrypt_sagepay.php';

and uncomment the  line $this->form_action_url = '../../decrypt_sagepay.php';
*/


// Set $toBrowser to false if you want to log this information rather than showing it on a browser page.
// If logging is enabled, a file called sagepay.log with be created in your docRoot.
// ** For extra security, please remember to delete sagepay.log once you have finished debugging **

// Set $toBrowser to true if you want to show this information on a browser page.
$toBrowser = true;

function simpleXor($InString, $Key) {
      $KeyList = array();
      $output = "";

      for($i = 0; $i < strlen($Key); $i++){
        $KeyList[$i] = ord(substr($Key, $i, 1));
      }

      for($i = 0; $i < strlen($InString); $i++) {
        $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
      }

      return $output;
}

    function getToken($thisString) {

      $Tokens = array("Status","StatusDetail","VendorTxCode","VPSTxID","TxAuthNo","Amount","AVSCV2");

      $output = array();
      $resultArray = array();

      for ($i = count($Tokens)-1; $i >= 0 ; $i--){
        $start = strpos($thisString, $Tokens[$i]);
        if ($start !== false){
          $resultArray[$i]->start = $start;
          $resultArray[$i]->token = $Tokens[$i];
        }
      }

      sort($resultArray);

      for ($i = 0; $i<count($resultArray); $i++){
        $valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;
        if ($i==(count($resultArray)-1)) {
          $output[$resultArray[$i]->token] = substr($thisString, $valueStart);
        } else {
          $valueLength = $resultArray[$i+1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
          $output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
        }
      }

      return $output;
    }
// ** retrieve the information posted from the previous form. **
$ThisVendorTxCode = $_REQUEST['VendorTxCode'];
$VPSProtocol = $_REQUEST['VPSProtocol'];
$TxType = $_REQUEST['TxType'];
$Vendor = $_REQUEST['Vendor'];
$Crypt = $_REQUEST['crypt'];

$outStr = $ThisVendorTxCode."<BR>" . $VPSProtocol."<BR>" . $TxType."<BR>" . $Vendor."<BR>";

$de_crypt = SimpleXor(base64_decode($Crypt),'geavj7yt4G6Wiy8x');

 $resultArray = getToken($de_crypt);

       for ($i = 0; $i<count($resultArray); $i++){
        echo $resultArray[$i]."<BR>";
      }

$outStr .= $de_crypt."<BR>";

if ( $toBrowser )
{
    echo $outStr;
}
else
{
    error_log( $Crypt . "\n\n", 3, "./sagepay.log");
    error_log( nl2br( $outStr ) , 3, "./sagepay.log");
    echo "<h2>Payment Service Not Currently Available</h2><br>Please use the back button and select a different service.<br>Sorry.</h2>";
}
?>
</body>
</html>
