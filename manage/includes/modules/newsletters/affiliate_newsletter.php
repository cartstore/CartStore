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

  class affiliate_newsletter {
    var $show_choose_audience, $title, $content;

    function affiliate_newsletter($title, $content) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $_GET;

      $mail_query = tep_db_query("select count(*) as count from " . TABLE_AFFILIATE . " where affiliate_newsletter = '1'");
      $mail = tep_db_fetch_array($mail_query);

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_AFFILIATES, $mail['count']) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . '">' . tep_image_button('button_send.gif', IMAGE_SEND) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($affiliate_newsletter_id) {
      $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_newsletter = '1'");

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
      $mimemessage->add_text($this->content);
      $mimemessage->build_message();
      while ($mail = tep_db_fetch_array($mail_query)) {
        $mimemessage->send($mail['affiliate_firstname'] . ' ' . $mail['affiliate_lastname'], $mail['affiliate_email_address'], '', EMAIL_FROM, $this->title);
      }

      $affiliate_newsletter_id = tep_db_prepare_input($affiliate_newsletter_id);
      tep_db_query("update " . TABLE_AFFILIATE_NEWSLETTERS . " set date_sent = now(), status = '1' where affiliate_newsletters_id = '" . tep_db_input($affiliate_newsletter_id) . "'");
    }
  }
?>