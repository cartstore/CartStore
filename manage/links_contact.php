<?php

/*

  $Id: links_contact.php,v 1.00 2003/10/03 Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  $action = (isset($_GET['action']) ? $_GET['action'] : '');



  if ( ($action == 'send_email_to_user') && isset($_POST['link_partners_email_address']) && !isset($_POST['back_x']) ) {

    switch ($_POST['link_partners_email_address']) {

      case '***':

        $mail_query = tep_db_query("select distinct links_contact_name, links_contact_email from " . TABLE_LINKS);

        $mail_sent_to = TEXT_ALL_LINK_PARTNERS;

        break;

      default:

        $link_partners_email_address = tep_db_prepare_input($_POST['link_partners_email_address']);



        $mail_query = tep_db_query("select links_contact_email, links_contact_name from " . TABLE_LINKS . " where links_contact_email = '" . tep_db_input($link_partners_email_address) . "'");

        $mail_sent_to = $_POST['link_partners_email_address'];

        break;

    }



    $from = tep_db_prepare_input($_POST['from']);

    $subject = tep_db_prepare_input($_POST['subject']);

    $message = tep_db_prepare_input($_POST['message']);



    //Let's build a message object using the email class

    $mimemessage = new email(array('X-Mailer: CartStore'));

    // add the message to the object

    $mimemessage->add_text($message);

    $mimemessage->build_message();

    while ($mail = tep_db_fetch_array($mail_query)) {

      $mimemessage->send($mail['links_contact_name'], $mail['links_contact_email'], '', $from, $subject);

    }



    tep_redirect(tep_href_link(FILENAME_LINKS_CONTACT, 'mail_sent_to=' . urlencode($mail_sent_to)));

  }



  if ( ($action == 'preview') && !isset($_POST['link_partners_email_address']) ) {

    $messageStack->add(ERROR_NO_LINK_PARTNER_SELECTED, 'error');

  }



  if (isset($_GET['mail_sent_to'])) {

    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');

  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  if ( ($action == 'preview') && isset($_POST['link_partners_email_address']) ) {

    switch ($_POST['link_partners_email_address']) {

      case '***':

        $mail_sent_to = TEXT_ALL_LINK_PARTNERS;

        break;

      default:

        $mail_sent_to = $_POST['link_partners_email_address'];

        break;

    }

?>

          <tr><?php echo tep_draw_form('mail', FILENAME_LINKS_CONTACT, 'action=send_email_to_user'); ?>

            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_LINK_PARTNER; ?></b><br><?php echo $mail_sent_to; ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td>

<?php

/* Re-Post all POST'ed variables */

    reset($_POST);

    while (list($key, $value) = each($_POST)) {

      if (!is_array($_POST[$key])) {

        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));

      }

    }

?>

                <table border="0" width="100%" cellpadding="0" cellspacing="2">

                  <tr>

                    <td><?php echo tep_image_submit('button_back.png', IMAGE_BACK, 'name="back"'); ?></td>

                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINKS_CONTACT) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.png', IMAGE_SEND_EMAIL); ?></td>

                  </tr>

                </table></td>

              </tr>

            </table></td>

          </form></tr>

<?php

  } else {

?>

          <tr><?php echo tep_draw_form('mail', FILENAME_LINKS_CONTACT, 'action=preview'); ?>

            <td><table border="0" cellpadding="0" cellspacing="2">

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

<?php

    $link_partners = array();

    $link_partners[] = array('id' => '', 'text' => TEXT_SELECT_LINK_PARTNER);

    $link_partners[] = array('id' => '***', 'text' => TEXT_ALL_LINK_PARTNERS);



    $mail_query = tep_db_query("select distinct links_contact_email, links_contact_name from " . TABLE_LINKS . " order by links_contact_name");

    while($link_partners_values = tep_db_fetch_array($mail_query)) {

      $link_partners[] = array('id' => $link_partners_values['links_contact_email'],

                           'text' => $link_partners_values['links_contact_name'] . ' (' . $link_partners_values['links_contact_email'] . ')');

    }

?>

              <tr>

                <td class="main"><?php echo TEXT_LINK_PARTNER; ?></td>

                <td><?php echo tep_draw_pull_down_menu('link_partners_email_address', $link_partners, (isset($_GET['link_partner']) ? $_GET['link_partner'] : ''));?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_FROM; ?></td>

                <td><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_SUBJECT; ?></td>

                <td><?php echo tep_draw_input_field('subject'); ?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>

                <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

              </tr>

              <tr>

                <td colspan="2" align="right"><?php echo tep_image_submit('button_send_mail.png', IMAGE_SEND_EMAIL); ?></td>

              </tr>

            </table></td>

          </form></tr>

<?php

  }

?>

<!-- body_text_eof //-->

        </table></td>

      </tr>

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

