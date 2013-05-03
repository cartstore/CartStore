<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

require('includes/application_top.php');
$contents = "" ; 
$filPath = "../templates/includes/boxes/adsence2.php"; 
if ($_POST['submit'] == 'Update') {

	// Let's make sure the file exists and is writable first.
	if (is_writable($filPath)) {
		
		if (!$handle = fopen($filPath, 'wb')) {
			 echo "Cannot open file ($filPath)";
			 exit;
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, stripslashes($_POST['categories_htc_description'])) === FALSE) {
			echo "Cannot write to file ($filPath)";
			exit;
		}
		fclose($handle);
	} else {
		echo "The file $filPath is not writable";
	}
  }

//$handle1 = fopen($filPath, 'r');

$contents = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>




</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
    <h1>Adsence Editor</h1>
<form name="frmConfigration" method="post" enctype="multipart/form-data">  
<table border="0" cellpadding="1" cellspacing="1" >
	<tr>
		<td align="ceter">
		<span class="class="mceEditor"">
		<textarea wrap="soft" cols="100" rows="25" name="categories_htc_description"><?php echo htmlspecialchars(stripslashes(file_get_contents($filPath))); ?></textarea></span><br>

		
		</td>
	</tr>
	<tr>
		<td align="center">
		<input type="hidden" name="action" value="updateimage">
		<input type="submit" class="button" name="submit" value = "Update">

		</td>
	</tr>
</table>
</form>



    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>

</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>