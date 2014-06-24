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
              foreach ($send_to_array as $send_to) {
                if (preg_match('/(.*)\<([^>]+)\>/', $send_to, $send_email_array)){
                  $send_to_name = $send_email_array[1];
                  $send_to_addr = $send_email_array[2];
                } else {
                  $send_to_name = '';
                  $send_to_addr = $send_to;
                }
                tep_mail($send_to_name, $send_to_addr, $emailsubject, $enquiry, $name, $email_address);
              }
             tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
          }
      }
  }

  if (isset($_SESSION['customer_id'])){
   $c_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = " . (int)$_SESSION['customer_id']);
   if (tep_db_num_rows($c_query) > 0){
     $info = tep_db_fetch_array($c_query);
     $setname = $info['customers_firstname'].' '.$info['customers_lastname'];
     $setemail = $info['customers_email_address'];
   }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));

  require(DIR_WS_INCLUDES . 'header.php');

  require(DIR_WS_INCLUDES . 'column_left.php');
?>



<!-- body_text //-->



       <table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td>
            	
            	 <?php
  echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send'), 'post', 'data-ajax="false"');
?>
            	
            	
            	
   <div class="page-header">        	<h1><?php
  echo HEADING_TITLE;
?></h1></div> 

 
 

    <address><?php
  echo '<strong>' . STORE_NAME .'</strong><br>';
  
  echo nl2br(STORE_NAME_ADDRESS);
?></address>

             

<?php
  if ($messageStack->size('contact') > 0) {
?>

  <p><?php
      echo $messageStack->output('contact');
?></p>
       

<?php
  }
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

 <div class="alert alert-success">Your Request Submitted Successfully! </div>

    


<?php
      echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>';
?>

 
<?php
      } else
      {
?>

  <div class="form-group"><label><?php
          echo ENTRY_NAME;
?></label> <?php
          echo tep_draw_input_field('name',(isset($setname) ? $setname : ''));
?></div>

             <div class="form-group"><label><?php
          echo ENTRY_EMAIL;
?></label>
<?php
          echo tep_draw_input_field('email',(isset($setemail) ? $setemail : ''));
?>
</div> 
  
  <div class="form-group"><label> <?php
          echo ENTRY_ORDER_ID;
?> </label>

<?php
          echo tep_draw_input_field('order_id');
?>

</div>
  
 
<div class="form-group"><label><?php
          echo ENTRY_REASON;
?> </label>
              


                <select class="form-control" name="reason">

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
          //echo '<option value="' . REASONS6 . '">' . REASONS6 . '</option>';
?>

               </select></div>

<div class="form-group"><label><?php
          echo ENTRY_ENQUIRY;
?></label>
               <?php
          echo tep_draw_textarea_field2('enquiry', 'soft', 30, 15);
?></div> 

<div id="ajaxRecaptcha"></div>

    <?php
          echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
    ?>
       

<?php
      }
?>
    

    </form></td>
    </tr>
   </table>
<!-- body_text_eof //-->

<?php
      require(DIR_WS_INCLUDES . 'column_right.php');

      require(DIR_WS_INCLUDES . 'footer.php');

      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>