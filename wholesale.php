<?php
/*
  $Id: contact_us.php,v 1.42 2003/06/12 12:17:07 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WHOLESALE);
  require('ext/recaptchalib.php');

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $name = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $enquiry = tep_db_prepare_input($_POST['enquiry']);
    $store_name = tep_db_prepare_input($_POST['store_name']);
    $store_phone = tep_db_prepare_input($_POST['store_phone']);
    $store_address = tep_db_prepare_input($_POST['store_address']);
    $store_tax_id = tep_db_prepare_input($_POST['store_tax_id']);

    if (!tep_validate_email($email_address)) {
      $error = true;
      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
                                      $_SERVER["REMOTE_ADDR"],
                                      $_POST["recaptcha_challenge_field"],
                                      $_POST["recaptcha_response_field"]);
      if (!$resp->is_valid) {
        $error = true;
        $messageStack->add('contact', RECAPTCHA_ERROR_MSG . "(" . $resp->error . ")");
      } else {
        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, 'Store Name:  ' . $store_name . "\r\n\r\n" . 'Name:  ' . $name . "\r\n\r\n" . 'Phone:  ' . $store_phone . "\r\n\r\n" . 'Address:  ' . $store_address . "\r\n\r\n" . 'Tax ID: ' . $store_tax_id . "\r\n\r\n" . 'Comments / Special Instructions:  ' . $enquiry, $name, $email_address);
        tep_redirect(tep_href_link(FILENAME_WHOLESALE, 'action=success'));
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WHOLESALE));

require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- body_text //-->
   
 <table><tr><td>  
   

<?php echo tep_draw_form('wholesale_request', tep_href_link(FILENAME_WHOLESALE, 'action=send')); ?>

    <div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>
 
<?php
  if ($messageStack->size('contact') > 0) {
?>
     
<?php
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>
 
   <?php echo '<a  class="btn btn-primary" href="' . tep_href_link(FILENAME_DEFAULT) . '">' .  IMAGE_BUTTON_CONTINUE . '</a>'; ?> 
<?php
  } else {
?>
 
            

<div class="form-group"><label><?php echo ENTRY_NAME; ?> </label>
           
<?php echo tep_draw_input_field('name'); ?></div>


<div class="form-group"><label><?php echo ENTRY_EMAIL; ?> </label>
              <?php echo tep_draw_input_field('email'); ?></div>
      <div class="form-group"><label><?php echo ENTRY_STORE_NAME; ?></label>
             <?php echo tep_draw_input_field('store_name'); ?></div>
<div class="form-group"><label><?php echo ENTRY_STORE_PHONE; ?></label>
	 <?php echo tep_draw_input_field('store_phone'); ?></div>
<div class="form-group"><label><?php echo ENTRY_STORE_ADDRESS; ?> </label>
               <?php echo tep_draw_textarea_field('store_address', 'soft', 20, 4); ?></div>
 <div class="form-group"><label><?php echo ENTRY_STORE_TAX_ID; ?></label>
        <?php echo tep_draw_input_field('store_tax_id'); ?></div>
 <div class="form-group"><label><?php echo ENTRY_ENQUIRY; ?></label>
 	 <?php echo tep_draw_textarea_field2('enquiry', 'soft', 50, 15); ?></div>
            <?php   echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?> 
         <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?> 
         
         
         
<?php
  }
?>
   </form>
    
    </td>
  </tr>
</table>

<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>