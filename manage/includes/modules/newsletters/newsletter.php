<?php
/*
  $Id: newsletter.php, v4.0 8/12/2006

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  GNU General Public License Compatible
*/

  class newsletter {
    var $show_choose_audience, $title, $content;

    function newsletter($title, $content) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $_GET;

	$mail_query1 = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = 	'1'");
	$mail_query2 = tep_db_query("select count(*) as count from " . TABLE_NEWSLETTER . " where customers_newsletter 	= '1'");
      
	  
	$mail1 = tep_db_fetch_array($mail_query1);
	$mail2 = tep_db_fetch_array($mail_query2);
	  
	$mail['count'] = ($mail1['count'] + $mail2['count'] );

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['count']) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.png', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.png', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.png', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a class="button" href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . '">' . IMAGE_SEND . '</a> <a class="button" href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' .  IMAGE_CANCEL . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
      $mail_query1 = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
	  $mail_query2 = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . 		TABLE_NEWSLETTER . " where customers_newsletter = '1'");

      $mimemessage = new email(array('X-Mailer: CartStore bulk mailer'));

// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Newsletter v1.7 when WYSIWYG Disabled)
      if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
      $mimemessage->add_html($this->content);
      } else {
      $mimemessage->add_html($this->content);
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Newsletter v1.7 when WYSIWYG Enabled)
      }
      
      $mimemessage->build_message();
      while ($mail = tep_db_fetch_array($mail_query1)) {
        $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', EMAIL_FROM, $this->title);
      }
	  while ($mail = tep_db_fetch_array($mail_query2)) {
        $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], 	$mail['customers_email_address'], '', EMAIL_FROM, $this->title);
      	}

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
