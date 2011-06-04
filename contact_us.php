<?php
  require('includes/application_top.php');
  require('ext/recaptchalib.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
          $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
                                          $_SERVER["REMOTE_ADDR"],
                                          $_POST["recaptcha_challenge_field"],
                                          $_POST["recaptcha_response_field"]);
          if (!$resp->is_valid) {
              $error = true;
              $messageStack->add('contact', RECAPTCHA_ERROR_MSG . "(" . $resp->error . ")");
          }

      $name = tep_db_prepare_input($_POST['name']);
      $email_address = tep_db_prepare_input($_POST['email']);
      $order_id = tep_db_prepare_input($_POST['order_id']);
      if ($order_id <> null) {
          $enquiry = 'Order ID: ' . $order_id . "\n\n" . tep_db_prepare_input($_POST['enquiry']);
      } else {
          $enquiry = tep_db_prepare_input($_POST['enquiry']);
      }
      $emailsubject = tep_db_prepare_input($_POST['reason']) . ' ' . EMAIL_SUBJECT;
      if (!tep_validate_email($email_address)) {
          $error = true;
          $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
      } elseif (!$error == true) {
          tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $emailsubject, $enquiry, $name, $email_address);
          if (CONTACT_US_LIST != '') {
              $send_to_array = explode(",", CONTACT_US_LIST);
              preg_match('/\<[^>]+\>/', $send_to_array[$send_to], $send_email_array);
              $send_to_email = preg_replace("/>/", "", $send_email_array[0]);
              $send_to_email = preg_replace("/</", "", $send_to_email);
              tep_mail(preg_replace('/\<[^*]*/', '', $send_to_array[$send_to]), $send_to_email, $emailsubject, $enquiry, $name, $email_address);
              tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
          }
      }
  }
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php
  echo HTML_PARAMS;
?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">

<title><?php
  echo TITLE;
?></title>

<base href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->

<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>

    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->

<?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><?php
  echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send'));
?><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php
  echo HEADING_TITLE;
?></td>

            <td align="right">&nbsp;</td>

          </tr>

        </table><strong><?php
  echo nl2br(STORE_NAME_ADDRESS);
?></strong><br /><br />

               </td>

      </tr>

      <tr>

        <td></td>

      </tr>

<?php
  if ($messageStack->size('contact') > 0) {
?>

      <tr>

        <td><?php
      echo $messageStack->output('contact');
?></td>

      </tr>

      <tr>

        <td></td>

      </tr>

<?php
  }
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

      <tr>

        <td class="main" align="center"><strong>Your Request Submitted Successfully!</strong></td>

      </tr>

      <tr>

        <td></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td width="10"></td>

                <td align="right"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>';
?></td>

                <td width="10"></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

<?php
      } else
      {
?>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td rowspan="6" valign="top" width="0%">

            <!---BOF 1st column--->

             <table border="0" width="100%" cellspacing="0" cellpadding="2">

               <tr >

                <td class="main"  >



                </td>

                </tr>

              </table>

              </td>

              <!---EOF 1st column--->

              <!---BOF 2nd column--->

              <td valign="top" width="100%">

              <table border="0" width="100%" cellspacing="0" cellpadding="2">

               <tr>

                <td class="main"><?php
          echo ENTRY_NAME;
?></td>

              </tr>



              <tr>

                <td class="main"><?php
          echo tep_draw_input_field('name');
?></td>

              </tr>

              <tr>

                <td class="main"><?php
          echo ENTRY_EMAIL;
?></td>

              </tr>

              <tr>

                <td class="main"><?php
          echo tep_draw_input_field('email');
?></td>

              </tr>



              <tr>

                <td class="main"><?php
          echo ENTRY_ORDER_ID;
?></td>

              </tr>

              <tr>

                <td class="main"><?php
          echo tep_draw_input_field('order_id');
?></td>

              </tr>

               <tr>

                <td class="main"><?php
          echo ENTRY_REASON;
?><br />

                <select class="inputbox" name="reason">

  <?php
          echo '<option value="' . REASONS1 . '">' . REASONS1 . '</option>';
?>

  <?php
          echo '<option value="' . REASONS2 . '">' . REASONS2 . '</option>';
?>

  <?php
          echo '<option value="' . REASONS3 . '">' . REASONS3 . '</option>';
?>

  <?php
          echo '<option value="' . REASONS4 . '">' . REASONS4 . '</option>';
?>

  <?php
          echo '<option value="' . REASONS5 . '">' . REASONS5 . '</option>';
?>

  <?php
          echo '<option value="' . REASONS6 . '">' . REASONS6 . '</option>';
?>

               </select><br /></td>

              </tr>



              <tr>

                <td class="main"><?php
          echo ENTRY_ENQUIRY;
?></td>

              </tr>

              <tr>

                <td><?php
          echo tep_draw_textarea_field2('enquiry', 'soft', 30, 15);
?></td>

              </tr>
              <tr><td><?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?></td></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
      }
?>
    </table>
    <?php
          echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
?>
    </form></td>
<!-- body_text_eof //-->
    <td width="<?php
      echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
      echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php
      require(DIR_WS_INCLUDES . 'column_right.php');
?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php
      require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
</body>
</html>
<?php
      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>