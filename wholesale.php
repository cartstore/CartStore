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
    <td width="100%" valign="top"><?php echo tep_draw_form('wholesale_request', tep_href_link(FILENAME_WHOLESALE, 'action=send')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('contact') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('contact'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>
      <tr>
        <td class="main" align="center"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a  class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' .  IMAGE_BUTTON_CONTINUE . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><font size="2"><?php echo ENTRY_WHOLESALE_TEXT; ?></font><br><br>
             <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="2"><?php echo ENTRY_NAME; ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo tep_draw_input_field('name'); ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo ENTRY_EMAIL; ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo tep_draw_input_field('email'); ?></td>
              </tr>
                <tr>
                <td class="main" colspan="2"><?php echo ENTRY_STORE_NAME; ?></td>
                </tr>
                <tr>
                <td class="main" colspan="2"><?php echo tep_draw_input_field('store_name'); ?>
                </td>
                </tr>

                <tr>
                <td class="main" colspan="2"><?php echo ENTRY_STORE_PHONE; ?></td>
                </tr>
                <tr>
                <td class="main" colspan="2"><?php echo tep_draw_input_field('store_phone'); ?>
                </td>
                </tr>

                <tr>
                <td class="main" width="40%"><?php echo ENTRY_STORE_ADDRESS; ?></td>
                <td> </td>
                </tr>
                <tr>
                <td class="main" width="40%"><?php echo tep_draw_textarea_field('store_address', 'soft', 20, 4); ?></td>
                <td> </td>
                </tr>

             <tr>
                <td class="main" colspan="2"><?php echo ENTRY_STORE_TAX_ID; ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo tep_draw_input_field('store_tax_id'); ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo ENTRY_ENQUIRY; ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo tep_draw_textarea_field2('enquiry', 'soft', 50, 15); ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php   echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></form></td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>