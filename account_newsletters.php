<?php
/*
  $Id: account_newsletters.php,v 1.3 2003/06/05 23:23:52 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
      $newsletter_general = tep_db_prepare_input($_POST['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }
	$test_email = $newsletter['customers_email_address'];
  $check_client_newsletter_true = tep_db_query("select count(*) as total from " . TABLE_NEWSLETTER . " where customers_email_address = '$test_email'"); 
  $check_client_new = tep_db_fetch_array($check_client_newsletter_true); 
 
     if ($check_client_new['total'] > 0)
  {     
   tep_db_query("delete from " . TABLE_NEWSLETTER . " where customers_email_address = '$test_email'");
     }

    if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
  
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td>

<?php echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
     
<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>



<h3><?php echo MY_NEWSLETTERS_TITLE; ?></b></h3>
      

<div class="checkbox">
<label><?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'); ?>
<b><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?> </b><br><i><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></i></label></div>

 

 
      <?php echo ' <span class="pull-left"><a class="btn btn-default" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . IMAGE_BUTTON_BACK. '</a></span>'; ?> 
       <span class="pull-right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?> </span> 
             </form> </td>
      </tr>
    </table> 
<!-- body_text_eof //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
