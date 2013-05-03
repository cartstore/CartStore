<?php
/*
 * Created on 20/03/2007
 * @author: Ropu
 * @version: 1.2
 *
 * Script to emulate a google checkout request to merchant response handler
 * Expected answer: A valid XML in response to your posted xml request.
 *                  No PHP errors warnings or any other string.
 *
 * CURL must be installed
 *
 * README:
 * Configure the parameters.
 * Place this script in your website.
 * Point your browser to this script.
 * Analize the response.
 */
 if(!isset($_POST['URL']) || empty($_POST['URL'])){
   $_POST['URL'] = 'https://your-site.com/googlecheckout/responsehandler.php';
 }

?>
<html>
<head>
  <title>Responsehandler Test</title>
  <script language="JavaScript" type="text/javascript">
   var help_texts = Array(
 												'The Merchant Id could be found in GoogleCheckout->Settings->integration',
 												'The Merchant Key could be found in GoogleCheckout->Settings->integration <b>(NO data will be recorded!)</b>',
 												'The full path to your responsehandler.php file<br/><small>(ie. https://your-site.com/ googlecheckout/responsehandler.php)<br/>Only ports 80 and 443 are available</small>',
 												'Here put the XML Request your want to test against your implementation<br/><small>(ie. New-order-notification, Merchant-calculation-callback)</small>)'
 												);

 /**
  * show_help
  * @param {int} help_index
  */
  function show_help(help_index) {
  	var help_div = document.getElementById('help');
  	var help_text = document.getElementById('help_text');

  	help_text.innerHTML = help_texts[help_index];
  	help_div.style.display = 'block';
  }
	</script>
  <link rel="stylesheet" type="text/css" href="../stylesheet.css">
<style type="text/css">

/* tables for merchant error details */
table.errorDetail tr th {
  font-family: Arial, sans-serif;
  padding-right: 5px;
  padding-bottom: 5px;
  vertical-align: top;
}
table.errorDetail tr td {
  padding-bottom: 5px;
  vertical-align: top;
}
pre.xmlCode {
  border: 1px solid #000055;
  background-color: #DEE8FA;
  width: 95%;
  overflow: auto;
  padding: 3px 3px 17px 3px; /* extra bottom padding for scrollbar on IE */
}

.MiddleMap {
  width: 75%;
  margin-left: auto;
  margin-right: auto;
  text-align: left;
}

.bordersAndPadding {
  border-right: solid 1px #CCCCCC;
  border-bottom: solid 1px #CCCCCC;
  padding: 5px
}
.ipLookupTableRow {
  font-family: Geneva, Arial, Helvetica, sans-serif;
}

.ipLookupTitleRow {
  font-weight: bold;
  font-family: Geneva, Arial, Helvetica, sans-serif;
}


</style>

</head>
<body>
  <h2 align=center>Responsehandler Test: Test your implementation</h2>

<?php

if(isset($_POST['submit'])){
	// Responsehandler.php URL
	$url = $_POST['URL'];
	// Your Merchant ID
	$merid = $_POST['sb_id'];
	// Your Merchant Key
	$merkey = $_POST['sb_key'];
	// Here put the xml u want to emulate! You can take the ones from googlecheckout/response_message.log
	$postargs = $_POST['xml'];



	// No need to touch anything below here.
	list($start_m, $start_s) = explode(' ', microtime());
	$start = $start_m + $start_s;
	$response = send_google_req($url, $merid, $merkey, $postargs);
	list($end_m, $end_s) = explode(' ', microtime());
	$end = $end_m + $end_s;

  $header_string = "Authorization: Basic XXXXXX:XXXXXX\n";
  $header_string .= "Content-Type: application/xml;charset=UTF-8\n";
  $header_string .= "Accept: application/xml;charset=UTF-8\n";

  $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
  $header_string .= "X-Origin-IP: " . $ip ."\n";
?>
  <table style="table-layout: fixed;" class="errorDetail" border="0" cellpadding="0" cellspacing="0" width="100%">
  	<col width="20%">
  	<col width="80%">
	  <tbody>
	  <tr>
		  <th>XML We Sent:</th>
		  <td>
			  <div style="width: 100%;">
			  	<pre class="xmlCode"><?php echo  htmlentities($header_string) . "\n".htmlentities($postargs);?></pre>
			  </div>
		  </td>
	  </tr>
	  <tr>
		  <th>XML We Received:</th>
		  <td>
			  <div style="width: 100%;">
			  	<pre class="xmlCode"><?php echo htmlentities($response);?></pre>
			  </div>
		  </td>
	  </tr>
	  </tbody>
  </table>

<?php
	echo "<xmp>";

	echo "\n\nTime to response: ". ($end-$start) ." segs";
	echo "\n\nNote: This script MUST response in less than 3 sec. so GC srv doesn't timeout.'";
	echo "</xmp>";
}
function send_google_req($url, $merid, $merkey, $postargs) {
  // Get the curl session object
  $session = curl_init($url);
  $headers = array();
  $headers[] = "Authorization: Basic ".base64_encode($merid.':'.$merkey);
  $headers[] = "Content-Type: application/xml;charset=UTF-8";
  $headers[] = "Accept: application/xml;charset=UTF-8";
  $headers[] = "User-Agent: Ropus ResponseHandler Test";

  $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
  $headers[] = "X-Origin-IP: " . $ip;


  // Set the POST options.
  curl_setopt($session, CURLOPT_POST, true);
  curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($session, CURLOPT_POSTFIELDS, trim($postargs));
  curl_setopt($session, CURLOPT_HEADER, true);
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
//  curl_setopt($session, CURLOPT_PROXY, '192.168.128.150:3128');
//  curl_setopt($session, CURLOPT_TIMEOUT, 10);

  // Set to valir ssl.crt certification
//curl_setopt($session, CURLOPT_CAINFO, "C:\\Program Files\\xampp\\apache\\conf\\ssl.crt\\ca-bundle.crt");

  // Do the POST and then close the session
  $response = curl_exec($session);
	if (curl_errno($session)) {
		return curl_error($session);
	} else {
	    curl_close($session);
	}

  // Get HTTP Status code from the response
  $status_code = array();
  return $response;
}
?>
  <form action="" method="post">
  <table border="1" cellpadding="2" cellspacing="0" align="center" width="90%">
    <tr>
      <th align="right">Merchant ID: </th>
      <td><input type="text" value="<?php echo @$_POST['sb_id'];?>" name="sb_id" size="100"/><a onclick="show_help(0);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a></td>
    </tr>
    <tr>
      <th align="right">Merchant Key: </th>
      <td><input type="text" value="<?php echo @$_POST['sb_key'];?>" name="sb_key" size="100"/><a onclick="show_help(1);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a></td>
    </tr>
    <tr>
      <th align="right">Merchant Calculation API URL:</th>
      <td><input type="text" value="<?php echo @$_POST['URL'];?>" name="URL" size="100"/><a onclick="show_help(2);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a>
      </td>
    </tr>
    <tr>
      <th colspan="2" align="center">XML Request<a onclick="show_help(3);" onmouseover="this.style.cursor='help'"><big>&nbsp;&nbsp;?&nbsp;&nbsp;</big></a></th>
    </tr>
    <tr>
      <td colspan="2">
  			<textarea name="xml" style="width:100%;height:500" cols="100"><?php echo @$_POST['xml'];?></textarea>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2"><input type="submit" name="submit" value="Test"/><div align=right><small>Coded by:<b><a href="http://ropu.woot.com.ar/">Ropu</a></b></small></div></td>
    </tr>
  </table>
  </form>
  <div id="help" style="display:none; position:absolute; top:10px; right:10px">
  <table width="200" border="1" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td align="right" bgcolor="#EEEEEE"><b>Help</b>&nbsp;&nbsp;<a style="align:right" href="javascript:document.getElementById('help').style.display='none';void(0);">[x]</a></td>
    </tr>
    <tr>
      <td colspan="2" id="help_text"></td>
    </tr>
  </table>
</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1481584-3";
urchinTracker();
</script>
</body>
</html>