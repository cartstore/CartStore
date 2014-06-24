<?php
/*
  $Id: affiliate_contact.php,v 2.00 2003/10/12

  OSC-Affiliate
  
  Contribution based on:
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ( ($_GET['action'] == 'send_email_to_user') && ($_POST['affiliate_email_address']) && (!$_POST['back_x']) ) {
    switch ($_POST['affiliate_email_address']) {
      case '***':
        $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " ");
        $mail_sent_to = TEXT_ALL_AFFILIATES;
        break;
//      case '**D':
//        $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_newsletter = '1'");
//        $mail_sent_to = TEXT_NEWSLETTER_AFFILIATE;
//        break;
      default:
        $affiliate_email_address = tep_db_prepare_input($_POST['affiliate_email_address']);

        $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($affiliate_email_address) . "'");
        $mail_sent_to = $_POST['affiliate_email_address'];
        break;
    }

    $from = tep_db_prepare_input($_POST['from']);
    $subject = tep_db_prepare_input($_POST['subject']);
    $message = tep_db_prepare_input($_POST['message']);

    // Instantiate a new mail object
    $mimemessage = new email(array('X-Mailer: osCommerce'));

    // Build the text version
    $text = strip_tags($text);
    if (EMAIL_USE_HTML == 'true') {
      $mimemessage->add_html($message);
    } else {
      $mimemessage->add_text($message);
    }

    // Send message
    $mimemessage->build_message();
    while ($mail = tep_db_fetch_array($mail_query)) {
      $mimemessage->send($mail['affiliate_firstname'] . ' ' . $mail['affiliate_lastname'], $mail['affiliate_email_address'], '', $from, $subject);
    }

    tep_redirect(tep_href_link(FILENAME_AFFILIATE_CONTACT, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($_GET['action'] == 'preview') && (!$_POST['affiliate_email_address']) ) {
    $messageStack->add(ERROR_NO_AFFILIATE_SELECTED, 'error');
  }

  if (tep_not_null($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
  }
?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<div class="page-header"><h1>
<?php echo HEADING_TITLE; ?>
</h1></div>



<?php
  if ( ($_GET['action'] == 'preview') && ($_POST['affiliate_email_address']) ) {
    switch ($_POST['affiliate_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_AFFILIATES;
        break;
//      case '**D':
//        $mail_sent_to = TEXT_NEWSLETTER_AFFILIATES;
//        break;
      default:
        $mail_sent_to = $_POST['affiliate_email_address'];
        break;
    }
?>
     
<?php echo tep_draw_form('mail', FILENAME_AFFILIATE_CONTACT, 'action=send_email_to_user'); ?>
           
<table class="table">
        

              <tr>
                <td class="smallText"><b><?php echo TEXT_AFFILIATE; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
            
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></td>
              </tr>
            
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></td>
              </tr>
         
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></td>
              </tr>
            
            </table>
<?php
/* Re-Post all POST'ed variables */
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (!is_array($_POST[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
              
<?php echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"'); ?>

 
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?>

           

          </form>

<?php
  } else {
?>
         
<?php echo tep_draw_form('mail', FILENAME_AFFILIATE_CONTACT, 'action=preview'); ?>
        
<table class="table">
             

<?php
    $affiliate = array();
    $affiliate[] = array('id' => '', 'text' => TEXT_SELECT_AFFILIATE);
    $affiliate[] = array('id' => '***', 'text' => TEXT_ALL_AFFILIATES);
//    $affiliate[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_AFFILIATES);
    $mail_query = tep_db_query("select affiliate_email_address, affiliate_firstname, affiliate_lastname from " . TABLE_AFFILIATE . " order by affiliate_lastname");
    while($affiliate_values = tep_db_fetch_array($mail_query)) {
      $affiliate[] = array('id' => $affiliate_values['affiliate_email_address'],
                           'text' => $affiliate_values['affiliate_lastname'] . ', ' . $affiliate_values['affiliate_firstname'] . ' (' . $affiliate_values['affiliate_email_address'] . ')');
    }
?>
              <tr>
                <td class="main"><?php echo TEXT_AFFILIATE; ?></td>
                <td><?php echo tep_draw_pull_down_menu('affiliate_email_address', $affiliate, $_GET['affiliate']);?></td>
              </tr>
             
              <tr>
                <td class="main"><?php echo TEXT_FROM; ?></td>
                <td><?php echo tep_draw_input_field('from', AFFILIATE_EMAIL_ADDRESS, 'size="60"'); ?></td>
              </tr>
            
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject', '', 'size="60"'); ?></td>
              </tr>
         
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
              </tr>
             
              <tr>
                <td colspan="2" align="right"><?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table> 
          </form> 
<?php
  }
?>
<!-- body_text_eof //-->
   
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
