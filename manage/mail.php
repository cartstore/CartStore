<?php
/*
  $Id: mail.php,v 1.31 2003/06/20 00:37:51 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($_POST['customers_email_address']) && !isset($_POST['back_x']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $customers_email_address = tep_db_prepare_input($_POST['customers_email_address']);

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
        $mail_sent_to = $_POST['customers_email_address'];
        break;
    }
		$from_name = tep_db_prepare_input($_POST['from_name']);
    $from = tep_db_prepare_input($_POST['from']);
    $subject = tep_db_prepare_input($_POST['subject']);
    $messageraw = tep_db_prepare_input(stripslashes($_POST['message']));
    $message = tep_add_base_ref($messageraw);
	
    
    while ($mail = tep_db_fetch_array($mail_query)) {
		  tep_mail($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], $subject, $message, $from_name, $from, true);
    }

    tep_redirect(tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && !isset($_POST['customers_email_address']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');
  }
?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<script language="javascript" src="includes/general.js"></script>

<div class="page-header"><h1>
<?php echo HEADING_TITLE; ?>

</h1></div>

<?php
  if ( ($action == 'preview') && isset($_POST['customers_email_address']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $mail_sent_to = $_POST['customers_email_address'];
        break;
    }
?>
        
<?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=send_email_to_user'); ?>
    

<div class="form-group"><label><?php echo TEXT_CUSTOMER; ?></label>

<?php echo $mail_sent_to; ?>
</div>


<div class="form-group"><label><?php echo TEXT_FROM; ?></label>
<?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></div>

<div class="form-group"><label><?php echo TEXT_SUBJECT; ?></label>
<?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></div>

<div class="form-group"><label><?php echo TEXT_MESSAGE; ?></label>
<?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></div>


<?php
/* Re-Post all POST'ed variables */
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (!is_array($_POST[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
               

<p><?php echo tep_image_submit('button_back.png', IMAGE_BACK, 'name="back"'); ?>

        
<?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_MAIL) . '">' .  IMAGE_CANCEL . '</a> ' . tep_image_submit('button_send_mail.png', IMAGE_SEND_EMAIL); ?>
</p>
          </form>

<?php
  } else {
?>
    
<?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=preview'); ?>
          


<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
?>
        
<div class="form-group"><label><?php echo TEXT_CUSTOMER; ?></label>
<?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''));?></div>

<div class="form-group"><label><?php echo TEXT_FROM; ?></label>
<?php echo tep_draw_input_field('from', EMAIL_FROM); ?></div>

<div class="form-group"><label><?php echo TEXT_SUBJECT; ?></label>
<?php echo tep_draw_input_field('subject'); ?></div>

<div class="form-group"><label><?php echo TEXT_MESSAGE; ?></label>
<?php echo tep_draw_textarea_field_redactor('message', 'soft', '60', '15'); ?></div>


<p>
<?php echo tep_image_submit('button_send_mail.png', IMAGE_SEND_EMAIL); ?>
</p>
          </form>

<?php
  }
?>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>