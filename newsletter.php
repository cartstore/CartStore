<?php
/*
  $Id: newsletter.php, v4.0 8/12/2006

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible
*/

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>: Newsletter Subscriptions</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
<tr>
<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
</table></td>
<!-- body_text //-->
<table width="100%" border="0">
  <tr>
    <td> 
<h1><?php echo HEADING_TITLE; ?></h1>
<?php

$PARAMS = ( isset( $_POST ) )
	? $_POST : $_GET;
	
	$list_firstname = $_GET[list_firstname];
	$list_lastname = $_GET[list_lastname];
	$list_email_add = $_GET[list_email_add];
	$list_on_off = $_GET[list_on_off];
	$check = $_GET[check];
	$list_firstname_error = "";
	$list_lastname_error = "";
	$list_email_add_error = "";

	function input_table( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $list_firstname_error, $list_lastname_error, $list_email_add_error)
			{
			print $PHP_SELF;
			print "<form name=\"maillist\" method=\"get\" action=\"newsletter.php\">
			<div class=\"clear\"></div>
			<div style=\"float:left; width:49%;\">
			
			
			
<strong>By joining the club you are eligible for <b>member only discounts</b>, coupons, free shipping, news and points and rewards. </strong>
<br><br>

You can unsubscribe at any moment by returning to this page.<br><br>
			
				</div>
	<div style=\"float:right; width:49%;\">			
				  <b>Please fill in the following fields<font color=\"#FF0000\">* Required</font></b><br>
		          <span>Name</span><input class=\"inputbox\" type=\"text\" name=\"list_firstname\" value=\"$list_firstname\" size=\"20\" maxlength=\"20\" >  $list_firstname_error
		  		  <br>
<span>Last name</span><input class=\"inputbox\" type=\"text\" name=\"list_lastname\" value=\"$list_lastname\" $size=\"20\" maxlength=\"20\">  $list_lastname_error
				  <br>
<span>Email address</span><font color=\"#FF0000\">* </font><input class=\"inputbox\" type=\"text\" name=\"list_email_add\" value=\"$list_email_add\" size=\"20\" maxlength=\"40\">  $list_email_add_error 
				 
				  					
<br>
<input type=\"radio\" name=\"list_on_off\" value=1 checked><font size=\"-2\">Subscribe</font>
									  <input type=\"radio\" name=\"list_on_off\" value=\"0\"><font size=\"-2\">Unsubscribe</font>
					  <input type=\"hidden\" name=\"check\" value=\"20\">
			
					  <input class=\"button\" type=\"submit\" border=\"0\"  name=\"Submit\" value=\"Submit\"> 
				   
				  </form>
				  
				  <div class=\"clear\"></div>";
			}

	
			
	function store_data( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $q_type )
			{
			$sql_data_array = array('customers_firstname' => $list_firstname,
'customers_lastname' => $list_lastname,
'customers_email_address' => $list_email_add,
'customers_newsletter' => $list_on_off);
			tep_db_perform(TABLE_NEWSLETTER, $sql_data_array, $q_type , "customers_email_address = '" . tep_db_input($list_email_add) . "'");
			}

		if ( $check == 20 )
			{
			if ( empty( $list_firstname ) )
				{
				$list_firstname_error = "<br>
<span class=\"error\">Please enter your name</span>";
				}
			if ( empty( $list_lastname ) )
				{
				$list_lastname_error = "<br>
<span class=\"error\">Please enter your last name</span>";
				}
			if ( empty( $list_email_add ) )
				{
				$list_email_add_error = "<br>
<span class=\"error\">Please enter a valid address email</span>";
						input_table( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $list_firstname_error, $list_lastname_error, $list_email_add_error);
				}
			else
				{
				if (!tep_validate_email($list_email_add)) {
					$list_email_add_error = "<br>
<span class=\"error\">Please enter a valid address email</span>";
					input_table( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $list_firstname_error, $list_lastname_error, $list_email_add_error);
					
				} else {


if ( $list_on_off != 1 ) 
{
//     $q_type = "update";
$check_query_up = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '$list_email_add'"); 
$check_up = tep_db_fetch_array($check_query_up);
if ($check_up['total'] > 0)
{
tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '0' where customers_email_address = '$list_email_add'");
print "<br><div align=\"center\">Your account has been updated. Thank you.<br><br></div>";
}
else
{     
tep_db_query("delete from " . TABLE_NEWSLETTER . " where customers_email_address = '$list_email_add'");
//     store_data( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $q_type );
print "<br><div align=\"center\">You have been successfully unsubscribed from our newsletter.<br><br></div>"; 

$name = $_GET[list_firstname];
$email_address = $_GET[list_email_add];

$email_text .= EMAIL_WELCOME2 . EMAIL_TEXT2;
tep_mail($name, $email_address, EMAIL_SUBJECT2, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

}               
} else 
{
// Si adresse existe dÈj?                    
$check_query = tep_db_query("select count(*) as total from " . TABLE_NEWSLETTER . " where customers_email_address = '$list_email_add'"); 
$check = tep_db_fetch_array($check_query); 

$check_query1 = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and customers_email_address = '$list_email_add'"); 
$check1 = tep_db_fetch_array($check_query1);

if ($check1['total'] > 0) 
{
print "<br><div align=\"center\">Your email account is already subscribed.<br><br></div>";
}
else
{
$check_query3 = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '$list_email_add'"); 
$check3 = tep_db_fetch_array($check_query3);
if ($check3['total'] > 0)
{
tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '1' where customers_email_address = '$list_email_add'");
print "<br><div align=\"center\">You have been successfully subscribed to our newsletter! <br><br></div>";
}
elseif ($check['total'] > 0) 
{ 
print "<br><div align=\"center\">Your email address already exists within our subscription list.<br><br></div>";
} 
else 
{
$q_type = "insert";
store_data( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $q_type );
print "<br><div align=\"center\">Thank you for your request: your email address has been successfully subscribed.<br><br></div>";

$name = $_GET[list_firstname];
$email_address = $_GET[list_email_add];

$email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_WARNING;
tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

}
}
}

} 
}
}   
else
			{
				input_table( $list_firstname, $list_lastname, $list_email_add, $list_on_off, $list_firstname_error, $list_lastname_error, $list_email_add_error);
			}	
		?>
 </td>
  </tr>
</table>

<!-- body_text_eof //-->
<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
</table></td>
</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>