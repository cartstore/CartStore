<?php
/*
 $Id: password_forgotten.php,v 1.50 2003/06/05 23:28:24 hpdl Exp $

 CartStore eCommerce Software, for The Next Generation
 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible
 */

require ('includes/application_top.php');

require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
	$email_address = tep_db_prepare_input($_POST['email_address']);

	$check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
	if (tep_db_num_rows($check_customer_query)) {
		$check_customer = tep_db_fetch_array($check_customer_query);

		$new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
		$crypted_password = tep_encrypt_password($new_password);

		tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_db_input($crypted_password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");

		tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $new_password), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

		$messageStack -> add_session('login', SUCCESS_PASSWORD_SENT, 'success');
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	} else {
		$messageStack -> add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
	}
}

$breadcrumb -> add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
$breadcrumb -> add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

require (DIR_WS_INCLUDES . 'header.php');

require (DIR_WS_INCLUDES . 'column_left.php');
 ?>

<table>
	<tr>

		<td width="100%" valign="top">
			

		<?php echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>
		<h1><?php echo HEADING_TITLE; ?></h1>
		

	
		<?php
if ($messageStack->size('password_forgotten') > 0) {
		?>
		

	<?php echo $messageStack -> output('password_forgotten'); ?>
			

		
		<?php
		}
		?>
		
<p><?php echo TEXT_MAIN; ?></p>

<?php echo '<b>' . ENTRY_EMAIL_ADDRESS . '</b> ' . tep_draw_input_field('email_address'); ?>

		
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
	
		<td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . IMAGE_BUTTON_BACK . '</a>'; ?></td>
		<td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>

		</tr>
		</table>

</form></td>
	</tr>
</table>

<?php
require (DIR_WS_INCLUDES . 'column_right.php');

require (DIR_WS_INCLUDES . 'footer.php');

require (DIR_WS_INCLUDES . 'application_bottom.php');
 ?>
