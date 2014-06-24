<?php
/*
  $Id: affiliate_newsletter.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_NEWSLETTER);

  $newsletter_query = tep_db_query("select affiliate_newsletter from " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['newsletter_affiliate']) && is_numeric($_POST['newsletter_affiliate'])) {
      $newsletter_affiliate = tep_db_prepare_input($_POST['newsletter_affiliate']);
    } else {
      $newsletter_affiliate = '0';
    }

    if ($newsletter_affiliate != $newsletter['affiliate_newsletter']) {
      $newsletter_affiliate = (($newsletter['affiliate_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_newsletter = '" . (int)$newsletter_affiliate . "' where affiliate_id = '" . (int)$affiliate_id . "'");
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL'));
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

    <?php echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>

<div class="page-heading"> <h1><?php echo HEADING_TITLE; ?></h1></div>



<h3><?php echo MY_NEWSLETTERS_TITLE; ?></h3>


<div class="checkbox"><?php echo tep_draw_checkbox_field('newsletter_affiliate', '1', (($newsletter['affiliate_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_affiliate\')"'); ?>

   
<label><?php echo MY_NEWSLETTERS_AFFILIATE_NEWSLETTER; ?></label>
</div>

    <p><?php echo MY_NEWSLETTERS_AFFILIATE_NEWSLETTER_DESCRIPTION; ?></p>
                       
 


    <p>  <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></p>
</form>



<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>