<?php
 /*
 $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License


*/
?>
<?php require('includes/application_top.php');?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "<?php echo HTTP_SERVER . DIR_WS_ADMIN . 'htmlarea/' ?>";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="popupcalendar" class="text"></div>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    
     <td class="pageHeading" valign="top"><h3><?php
       echo "Returns Text";
 ?> </h3><?php


  if ($REQUEST_METHOD=="POST")
  {
  
    mysql_query('REPLACE INTO return_text VALUES (1, "' . $languages_id . '", "'  . $aboutus .'")')
          or die(mysql_error());
  }

  $sql=mysql_query("SELECT * FROM return_text where return_text_id = '1' and language_id = '" . $languages_id . "'")
    or die(mysql_error());
  $row=mysql_fetch_array($sql);

?>

<table width="98%" align="center" border="0" cellpadding="0" cellspacing="0">
<form name="aboutusform" method="Post" action="">
<tr>
  <td width="400px" valign="top"><b>Returns Text</b><br>
  <textarea name="aboutus" cols="75" rows="15"><?php echo $row['return_text_one'] ?></textarea></td>
    <br>
  <script language="JavaScript1.2" defer>
editor_generate('aboutus');
</script>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td align="right"><input type="submit" class="button" name="Save" value="Save" style="width: 70px" /></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>
</form>
</table>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<p> </p>
<p> </p><p><br>
</p></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
