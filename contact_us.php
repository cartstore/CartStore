<?php
  require('includes/application_top.php');

  if (ACCOUNT_VALIDATION == 'true' && CONTACT_US_VALIDATION == 'true') {
      require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_VALIDATION);
      include_once('includes/functions/' . FILENAME_ACCOUNT_VALIDATION);
  } else {
      require('ext/recaptchalib.php');
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {

      if (ACCOUNT_VALIDATION == 'true' && CONTACT_US_VALIDATION == 'true') {
          $sql = "SELECT * FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "' LIMIT 1";
          if (!$result = tep_db_query($sql)) {
              $error = true;
              $entry_antirobotreg_error = true;
              $text_antirobotreg_error = ERROR_VALIDATION_1;
          } else {
              $entry_antirobotreg_error = false;
              $anti_robot_row = tep_db_fetch_array($result);
              if ((strtoupper($_POST['antirobotreg']) != $anti_robot_row['reg_key']) || ($anti_robot_row['reg_key'] == '') || (strlen($_POST['antirobotreg']) != ENTRY_VALIDATION_LENGTH)) {
                  $error = true;
                  $entry_antirobotreg_error = true;
                  $text_antirobotreg_error = ERROR_VALIDATION_2;
              } else {
                  $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "'";
                  if (!$result = tep_db_query($sql)) {
                      $error = true;
                      $entry_antirobotreg_error = true;
                      $text_antirobotreg_error = ERROR_VALIDATION_3;
                  } else {
                      $sql = "OPTIMIZE TABLE " . TABLE_ANTI_ROBOT_REGISTRATION . "";
                      if (!$result = tep_db_query($sql)) {
                          $error = true;
                          $entry_antirobotreg_error = true;
                          $text_antirobotreg_error = ERROR_VALIDATION_4;
                      } else {
                          $entry_antirobotreg_error = false;
                      }
                  }
              }
          }
          if ($entry_antirobotreg_error == true)
              $messageStack->add('contact', $text_antirobotreg_error);
      } else {
          $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
                                          $_SERVER["REMOTE_ADDR"],
                                          $_POST["recaptcha_challenge_field"],
                                          $_POST["recaptcha_response_field"]);
          if (!$resp->is_valid) {
              $error = $entry_antirobotreg_error = true;
              $messageStack->add('contact', RECAPTCHA_ERROR_MSG . "(" . $resp->error . ")");
          }
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
      } elseif (!$entry_antirobotreg_error == true) {
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



              <?php

          if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF, 'contact_us') && CONTACT_US_VALIDATION == 'true') {
?>

      <tr>

        <td class="main"><b><?php
              echo CATEGORY_ANTIROBOTREG;
?></b></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" cellspacing="2" cellpadding="2">

              <tr>

<?php
              if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF, 'contact_us') && CONTACT_US_VALIDATION == 'true') {
                  if ($is_read_only == false || (strstr($PHP_SELF, 'contact_us'))) {
                      $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE timestamp < '" . (time() - 3600) . "' OR session_id = '" . tep_session_id() . "'";
                      if (!$result = tep_db_query($sql)) {
                          die('Could not delete validation key');
                      }
                      $reg_key = gen_reg_key();
                      $sql = "INSERT INTO " . TABLE_ANTI_ROBOT_REGISTRATION . " VALUES ('" . tep_session_id() . "', '" . $reg_key . "', '" . time() . "')";
                      if (!$result = tep_db_query($sql)) {
                          die('Could not check registration information');
                      }
?>

                <tr>

                  <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2">

                    <tr>

                      <td class="main"><table border="0" cellspacing="0" cellpadding="2">

                        <tr>

                          <td class="main" width="100%" NOWRAP><span class="main"> <?php
                      echo ENTRY_ANTIROBOTREG;
?></span></td>

                        </tr>

                        <tr>

                          <td class="main" width="100%">

<?php
                      $check_anti_robotreg_query = tep_db_query("select session_id, reg_key, timestamp from anti_robotreg where session_id = '" . tep_session_id() . "'");
                      $new_guery_anti_robotreg = tep_db_fetch_array($check_anti_robotreg_query);
                      $validation_images = tep_image('validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id']);
                      if ($entry_antirobotreg_error == true) {
?>

<span>

<?php
                          echo '<img src="validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] . '" /> <br> ';
                          echo tep_draw_input_field('antirobotreg') . '';
                      } else {
?>

<span>

<?php
                          echo '<img src="validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] . '" /> <br> ';
                          echo tep_draw_input_field('antirobotreg', $account['entry_antirobotreg']) . ' ' . ENTRY_ANTIROBOTREG_TEXT;
                      }
                  }
              }
?>

</span>

                </td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td></td>

      </tr>

<?php
          } else {
?>
          <tr><td><?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?></td></tr>
<?php     } ?>





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

