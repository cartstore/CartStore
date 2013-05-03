<?php
/*
  $Id: affiliate_news.php,v 2.1 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliate_news //-->
<?php

// npe catalog begin add multi lingual news #change
//  $affiliate_news_query = tep_db_query('SELECT * from ' . TABLE_AFFILIATE_NEWS . ' WHERE status = 1 ORDER BY date_added DESC LIMIT ' . MAX_DISPLAY_AFFILIATE_NEWS);

    $affiliate_news_query = tep_db_query('select affiliate_news_headlines as headline, affiliate_news_contents as content,date_added, news_status from ' . TABLE_AFFILIATE_NEWS . ', ' . TABLE_AFFILIATE_NEWS_CONTENTS . " where news_id = affiliate_news_id and affiliate_news_languages_id = '" . $languages_id . "' and news_status = 1 order by date_added desc");

  if (!tep_db_num_rows($affiliate_news_query)) { // there is no news
    echo '<!-- ' . TEXT_NO_AFFILIATE_NEWS . ' -->';
  } else {

    $info_box_contents = array();
    $row = 0;
    while ($affiliate_news = tep_db_fetch_array($affiliate_news_query)) {
      $info_box_contents[$row] = array('align' => 'left',
                                       'params' => 'class="smallText" valign="top"',
                                       'text' => '<b>' . $affiliate_news['headline'] . '</b> - <i>' . tep_date_long($affiliate_news['date_added']) . '</i><br>' . nl2br($affiliate_news['content']) . '<br>');
      $row++;
    }
    new contentBox($info_box_contents);
  }
?>
<!-- affiliate_news_eof //-->
