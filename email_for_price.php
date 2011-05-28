<?php
/*
  $Id: contact_us.php,v 1.42 2003/06/12 12:17:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
  require('ext/recaptchalib.php');

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $name = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $enquiry = tep_db_prepare_input($_POST['enquiry']);

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
        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT_PRICE, $enquiry, $name, $email_address);
        tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
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
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_EMAIL_FOR_PRICE, 'action=send')); ?>
    <div class="email_for_price">
      <h1>Email for Price "<?php echo stripslashes(stripslashes($_GET['product_name']))?>" Part Number = <?php echo $_GET['products_model']?></h1>
        <?php
  if ($messageStack->size('contact') > 0) {
?>
   <br>
<?php echo $messageStack->output('contact'); ?><br>

        <?php
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

Thank you your message has been sent.
      <br>
<?php echo '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' .  IMAGE_BUTTON_CONTINUE . '</a>'; ?>
        <?php
  } else {
?>

       <?php echo ENTRY_NAME; ?><br>
<?php echo tep_draw_input_field('name'); ?><br>
<br>
<?php echo ENTRY_EMAIL; ?><br>
<?php echo tep_draw_input_field('email'); ?><br>
<br>
<?php echo ENTRY_ENQUIRY; ?><br>
<textarea rows="5" cols="20" wrap="soft" name="enquiry" class="ckeditor">I am  interested in special pricing you have for "<?php echo stripslashes(stripslashes($_GET['product_name']))?>" Part Number = <?php echo $_GET['products_model']?>. Please reply to my email address as soon as you can.</textarea>
<br><br>
 <?php
   echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
 ?>
<br><br>
<?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>

        <?php
  }
?>


      </td>
  </tr>
</table>
      </form>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
