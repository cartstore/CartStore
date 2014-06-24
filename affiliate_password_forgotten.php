<?php
/*
  $Id: affiliate_password_forgotten.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 -2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_PASSWORD_FORGOTTEN);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $check_affiliate_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_password, affiliate_id from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . $_POST['email_address'] . "'");
    if (tep_db_num_rows($check_affiliate_query)) {
      $check_affiliate = tep_db_fetch_array($check_affiliate_query);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($newpass);
      tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_password = '" . $crypted_password . "' where affiliate_id = '" . $check_affiliate['affiliate_id'] . "'");
      
      tep_mail($check_affiliate['affiliate_firstname'] . " " . $check_affiliate['affiliate_lastname'], $_POST['email_address'], EMAIL_PASSWORD_REMINDER_SUBJECT, nl2br(sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass)), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_AFFILIATE, 'info_message=' . urlencode(TEXT_PASSWORD_SENT), 'SSL', true, false));
    } else {
      tep_redirect(tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, 'email=nonexistent', 'SSL'));
    }
  } else {

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php'); 
require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
  
  <table><tr><td>
<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>

<?php echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>
         
<div class="form-group"><label><?php echo ENTRY_EMAIL_ADDRESS; ?></label>

<?php echo tep_draw_input_field('email_address', '', ''); ?>


</div>

 <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?> 
<hr>
<?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . tep_image_button('', IMAGE_BUTTON_BACK) . '<i class="fa fa-arrow-left"></i> Back</a>'; ?>




<?php
  if (isset($_GET['email']) && ($_GET['email'] == 'nonexistent')) {
    echo '<div class="alert alert-danger">' .  TEXT_NO_EMAIL_ADDRESS_FOUND . '</div>';

  }
?>
        
</form></td>
      </tr>
    </table>
<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>