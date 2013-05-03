<?php

  require('includes/application_top.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />
   
	 	
<BODY
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
    <td width="100%" valign="top"><span class="headerBar">Your Dealer Administration Interface<br>
    </span><span class="small">The dealer admin may not be enabled for your site. The dealer admin is a searchable database of all your local vendors it is a paid for add on option.<br><br>

</span>
      <?php
	if ($_POST['action'] == "add") {
		$sql = "INSERT INTO dealers(dealer_name, dealer_address1, dealer_address2, dealer_city, dealer_state, dealer_zip, dealer_phone, dealer_fax, dealer_email, dealer_url) VALUES('" . $_POST['dealer_name'] . "', '" . $_POST['dealer_address1'] . "', '" . $_POST['dealer_address2'] . "', '" . $_POST['dealer_city'] . "', '" . $_POST['dealer_state'] . "', '" . $_POST['dealer_zip'] . "', '" . $_POST['dealer_phone'] . "', '" . $_POST['dealer_fax'] . "', '" . $_POST['dealer_email'] . "', '" . $_POST['dealer_url'] . "')";
		$r = tep_db_query($sql);
		}
	?>
<?php
	if ($_POST['action'] == "edit") {
		$sql = "UPDATE dealers SET dealer_name = '" . $_POST['dealer_name'] . "', dealer_address1 = '" . $_POST['dealer_address1'] . "', dealer_address2 = '" . $_POST['dealer_address2'] . "', dealer_city = '" . $_POST['dealer_city'] . "', dealer_state = '" . $_POST['dealer_state'] . "', dealer_zip = '" . $_POST['dealer_zip'] . "', dealer_phone = '" . $_POST['dealer_phone'] . "', dealer_fax = '" . $_POST['dealer_fax'] . "', dealer_email = '" . $_POST['dealer_email'] . "', dealer_url = '" . $_POST['dealer_url'] . "' WHERE dealer_id = " . $_POST['dealer_id'];
		$r = tep_db_query($sql);
		}
	?>
<?php
	if ($_POST['action'] == "delete") {
		$sql = "DELETE FROM dealers WHERE dealer_id = " . $_POST['dealer_id'];
		$r = tep_db_query($sql);
		}
	?>
	
	
<FORM METHOD="POST" ACTION="">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="add">
<table border="0" width="100%">
<tr>
	<td>Dealer Name</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_name" SIZE=30></td>
	</tr>
<tr>
	<td valign="top">Address</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_address1" SIZE=30><br>
	<INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_address2" SIZE=30>	</td>
	</tr>
<tr>
	<td>City</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_city" SIZE=30></td>
	</tr>
<tr>
	<td>State</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_state" SIZE=30></td>
	</tr>
<tr>
	<td>Zip</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_zip" SIZE=30></td>
	</tr>
<tr>
	<td>Phone</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_phone" SIZE=30></td>
	</tr>
<tr>
	<td>Fax</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_fax" SIZE=30></td>
	</tr>
<tr>
	<td>Email</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_email" SIZE=30></td>
	</tr>
<tr>
	<td>URL</td>
	<td><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_url" SIZE=30></td>
	</tr>
<tr>
	<td colspan="2" align="right"><input type="submit" class="button" NAME="Submit" VALUE="Add Dealer"></td>
	</tr>
</table>
</FORM>
<br><br>

	
	<table border="0" width="100%">
<?php
$q = "SELECT * FROM dealers ORDER BY dealer_name"; 
$r = tep_db_query($q);

while($field=mysql_fetch_array($r)) {
?>
<FORM METHOD="POST" ACTION="dealer_admin.php">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="edit">
<INPUT TYPE="HIDDEN" NAME="dealer_id" VALUE="<?php echo $field[dealer_id]; ?>">
<tr>
	<td class="smallText">Name:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_name" VALUE="<?php echo $field[dealer_name]; ?>" SIZE=30></td>
	<td class="smallText">Address<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_address1" VALUE="<?php echo $field[dealer_address1]; ?>" SIZE=30><br>
	<INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_address2" VALUE="<?php echo $field[dealer_address2]; ?>" SIZE=30></td>
	<td class="smallText">City:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_city" VALUE="<?php echo $field[dealer_city]; ?>" SIZE=20></td>
	<td class="smallText">State:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_state" VALUE="<?php echo $field[dealer_state]; ?>" SIZE=4></td>
	<td class="smallText">Zip:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_zip" VALUE="<?php echo $field[dealer_zip]; ?>" SIZE=10></td>
	</tr>
	<tr>
	<td class="smallText">Phone:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_phone" VALUE="<?php echo $field[dealer_phone]; ?>" SIZE=12></td>
	<td class="smallText">Fax:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_fax" VALUE="<?php echo $field[dealer_fax]; ?>" SIZE=12></td>
	<td class="smallText">Email:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_email" VALUE="<?php echo $field[dealer_email]; ?>" SIZE=12></td>
	<td class="smallText">URL:<br><INPUT class="inputbox"  TYPE="TEXT" NAME="dealer_url" VALUE="<?php echo $field[dealer_url]; ?>" SIZE=30></td>
	<td class="smallText"><input type="submit" class="button" NAME="Submit" VALUE="Edit"></td>
	</FORM>
	<FORM METHOD="POST" ACTION="dealer_admin.php">
	<INPUT TYPE="HIDDEN" NAME="action" VALUE="delete">
	<INPUT TYPE="HIDDEN" NAME="dealer_id" VALUE="<?php echo $field[dealer_id]; ?>">
	<td class="smallText"><input type="submit" class="button" NAME="Submit" VALUE="Delete"></td>
	</FORM>	
	</tr>
	<tr>
		<td height="10" colspan="5"><hr></td>
		</tr>

<?php
	}
	?>
	</table></td>
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