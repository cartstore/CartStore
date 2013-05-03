<?php
/*
  $Id: newsletter.php, v4.0 8/12/2006

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible
*/
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER);

	$list_firstname = isset($_POST['list_firstname']) ? $_POST['list_firstname'] : '';
	$list_lastname = isset($_POST['list_lastname']) ? $_POST['list_lastname'] : '';
	$list_email_add = isset($_POST['list_email_add']) ? $_POST['list_email_add'] : '';
	$list_on_off = isset($_POST['list_on_off']) ? $_POST['list_on_off'] : '';
	$check = isset($_POST['check']) ? $_POST['check'] : '';
	$list_firstname_error = "";
	$list_lastname_error = "";
	$list_email_add_error = "";

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
	$errors = false;
	if ( empty( $list_firstname ) ){
		$errors = true;
		$messageStack->add( 'header', "Please enter your name" );
	}
	if ( empty( $list_lastname ) ){
		$errors = true;
		$messageStack->add( 'header', "Please enter your last name" );
	}
	if ( empty( $list_email_add ) ) {
		$errors = true;
		$messageStack->add( 'header', "Please enter a valid address email" );
	} elseif (!tep_validate_email($list_email_add)) {
		$errors = true;
		$messageStack->add('header', "Please enter a valid address email" );
	} 
	if (!$errors){
		if ( $list_on_off != 1 ) {
			$check_query_up = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '$list_email_add'"); 
			$check_up = tep_db_fetch_array($check_query_up);
			if ($check_up['total'] > 0) {
				tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '0' where customers_email_address = '$list_email_add'");
				$messageStack->add( 'header', "Your account has been updated. Thank you.", 'success' );
			} else {
				tep_db_query("delete from " . TABLE_NEWSLETTER . " where customers_email_address = '$list_email_add'");
				$messageStack->add( 'header', "You have been successfully unsubscribed from our newsletter.", 'success' ); 
				$name = $_POST['list_firstname'];
				$email_address = $_POST['list_email_add'];
				$email_text .= EMAIL_WELCOME2 . EMAIL_TEXT2;
				tep_mail($name, $email_address, EMAIL_SUBJECT2, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			}
		} else {
			$check_query = tep_db_query("select count(*) as total from " . TABLE_NEWSLETTER . " where customers_email_address = '$list_email_add'"); 
			$check = tep_db_fetch_array($check_query); 
			$check_query1 = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and customers_email_address = '$list_email_add'"); 
			$check1 = tep_db_fetch_array($check_query1);
			if ($check1['total'] > 0) {
				$messageStack->add("header", "Your email account is already subscribed.");
			} else {
				$check_query3 = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '$list_email_add'"); 
				$check3 = tep_db_fetch_array($check_query3);
				if ($check3['total'] > 0) {
					tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '1' where customers_email_address = '$list_email_add'");
					$messageStack->add('header', "You have been successfully subscribed to our newsletter!", 'success' );
				} elseif ($check['total'] > 0) { 
					$messageStack->add( "header", "Your email address already exists within our subscription list.");
				} else {
					$q_type = "insert";
					$sql_data_array = array(
						'customers_firstname' => $list_firstname,
						'customers_lastname' => $list_lastname,
						'customers_email_address' => $list_email_add,
						'customers_newsletter' => $list_on_off);
					tep_db_perform(TABLE_NEWSLETTER, $sql_data_array, $q_type , "customers_email_address = '" . tep_db_input($list_email_add) . "'");
					$messageStack->add( 'header', "Thank you for your request: your email address has been successfully subscribed.", 'success');
					$name = $_POST['list_firstname'];
					$email_address = $_POST['list_email_add'];
					$email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_WARNING;
					tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				}
			}
		}
	}
}

	//input_table( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $list_firstname_error, $list_lastname_error, $list_email_add_error);


$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER));

require(DIR_WS_INCLUDES . 'header.php'); 
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
<table width="100%" border="0">
  <tr>
    <td> 
<h1><?php echo HEADING_TITLE; ?></h1>
<?php echo tep_draw_form('maillist', 'newsletter.php', 'post', '') . tep_draw_hidden_field('action', 'process'); ?>
			<div class="clear"></div>
			<div style="float:left; width:49%;">
				<strong>By joining the club you are eligible for <b>member only discounts</b>, coupons, free shipping, news and points and rewards. </strong>
				<br><br>
				You can unsubscribe at any moment by returning to this page.<br><br>
			</div>
			<div style="float:right; width:49%;">
				<b>Please fill in the following fields<font color="#FF0000">* Required</font></b><br>
				<span>Name</span><input class="inputbox" type="text" name="list_firstname" value="<?php echo $list_firstname; ?>" size="20" maxlength="20" >
				<br>
				<span>Last name</span><input class="inputbox" type="text" name="list_lastname" value="<?php echo $list_lastname; ?>" size="20" maxlength="20">
				<br>
				<span>Email address</span><font color="#FF0000">* </font><input class="inputbox" type="text" name="list_email_add" value="<?php echo $list_email_add; ?>" size="20" maxlength="40"> 
				<br>
				<input type="radio" name="list_on_off" value=1 checked><font size="-2">Subscribe</font>
				<input type="radio" name="list_on_off" value="0"><font size="-2">Unsubscribe</font>
				<input type="hidden" name="check" value="20">
			
					  <input class="button" type="submit" border="0"  name="Submit" value="Submit"> 
				   
				  </form>
				  
				  <div class="clear"></div>
 </td>
  </tr>
</table>

<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>