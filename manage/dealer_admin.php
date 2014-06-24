<?php

  require('includes/application_top.php');

?>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
 
<div class="page-header"><h1>Your Dealer Administration Interface</h1></div>

  <p>
The dealer admin may not be enabled for your site. The dealer admin is a searchable database of all your local vendors it is a paid for add on option.</p>




 
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
<table class="table">
<tr>
	<td>Dealer Name</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_name" SIZE=30></td>
	</tr>
<tr>
	<td valign="top">Address</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_address1" SIZE=30><br>
	<INPUT class="form-control"  TYPE="TEXT" NAME="dealer_address2" SIZE=30>	</td>
	</tr>
<tr>
	<td>City</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_city" SIZE=30></td>
	</tr>
<tr>
	<td>State</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_state" SIZE=30></td>
	</tr>
<tr>
	<td>Zip</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_zip" SIZE=30></td>
	</tr>
<tr>
	<td>Phone</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_phone" SIZE=30></td>
	</tr>
<tr>
	<td>Fax</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_fax" SIZE=30></td>
	</tr>
<tr>
	<td>Email</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_email" SIZE=30></td>
	</tr>
<tr>
	<td>URL</td>
	<td><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_url" SIZE=30></td>
	</tr>
<tr>
	<td colspan="2" align="right"><input type="submit" class="btn btn-default" NAME="Submit" VALUE="Add Dealer"></td>
	</tr>
</table>
</FORM>
 
	
	<table class="table">
<?php
$q = "SELECT * FROM dealers ORDER BY dealer_name"; 
$r = tep_db_query($q);

while($field=tep_db_fetch_array($r)) {
?>

<tr>
<FORM METHOD="POST" ACTION="dealer_admin.php">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="edit">
<INPUT TYPE="HIDDEN" NAME="dealer_id" VALUE="<?php echo $field[dealer_id]; ?>">
	<td class="smallText">Name:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_name" VALUE="<?php echo $field[dealer_name]; ?>" SIZE=30></td>
	<td class="smallText">Address<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_address1" VALUE="<?php echo $field[dealer_address1]; ?>" SIZE=30><br>
	<INPUT class="form-control"  TYPE="TEXT" NAME="dealer_address2" VALUE="<?php echo $field[dealer_address2]; ?>" SIZE=30></td>
	<td class="smallText">City:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_city" VALUE="<?php echo $field[dealer_city]; ?>" SIZE=20></td>
	<td class="smallText">State:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_state" VALUE="<?php echo $field[dealer_state]; ?>" SIZE=4></td>
	<td class="smallText">Zip:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_zip" VALUE="<?php echo $field[dealer_zip]; ?>" SIZE=10></td>
	</tr>
	<tr>
	<td class="smallText">Phone:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_phone" VALUE="<?php echo $field[dealer_phone]; ?>" SIZE=12></td>
	<td class="smallText">Fax:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_fax" VALUE="<?php echo $field[dealer_fax]; ?>" SIZE=12></td>
	<td class="smallText">Email:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_email" VALUE="<?php echo $field[dealer_email]; ?>" SIZE=12></td>
	<td class="smallText">URL:<br><INPUT class="form-control"  TYPE="TEXT" NAME="dealer_url" VALUE="<?php echo $field[dealer_url]; ?>" SIZE=30></td>
	<td class="smallText"><input type="submit" class="btn btn-default" NAME="Submit" VALUE="Edit"></td>
	</FORM>
	<FORM METHOD="POST" ACTION="dealer_admin.php">
	<INPUT TYPE="HIDDEN" NAME="action" VALUE="delete">
	<INPUT TYPE="HIDDEN" NAME="dealer_id" VALUE="<?php echo $field[dealer_id]; ?>">
	<td class="smallText"><input type="submit" class="btn btn-default" NAME="Submit" VALUE="Delete"></td>
	</FORM>	
	</tr>
	 

<?php
	}
	?>
	</table>  
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?> 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>