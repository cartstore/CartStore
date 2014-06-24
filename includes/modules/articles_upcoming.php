<?php
/*
  $Id: articles_upcoming.php, v1.0 2003/12/04 12:00:00 ra Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

?>
<?php
  $expected_query = tep_db_query("select a.articles_id, a.articles_date_added, a.articles_date_available as date_expected, ad.articles_name, ad.articles_head_desc_tag, au.authors_id, au.authors_name, td.topics_id, td.topics_name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS_DESCRIPTION . " td, " . TABLE_AUTHORS . " au, " . TABLE_ARTICLES_DESCRIPTION . " ad where to_days(a.articles_date_available) > to_days(now()) and a.articles_id = a2t.articles_id and a2t.topics_id = td.topics_id and a.authors_id = au.authors_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' order by date_expected limit " . MAX_DISPLAY_UPCOMING_ARTICLES);
  if (tep_db_num_rows($expected_query) > 0) {
?>
<!-- upcoming_articles //-->
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main"><?php echo '<b>' . TEXT_UPCOMING_ARTICLES . '</b>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    while ($articles_expected = tep_db_fetch_array($expected_query)) {
?>
          <tr>
            <td valign="top" class="main" width="75%">
<?php
  echo '<font color="#999999"><b>' . $articles_expected['articles_name'] . '</b></font> ';
  if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true') {
   echo TEXT_BY . ' ' . $articles_expected['authors_name'];
  }
?>
            </td>
<?php
      if (DISPLAY_TOPIC_ARTICLE_LISTING == 'true') {
?>
            <td valign="top" class="main" width="25%" nowrap><?php echo TEXT_TOPIC . '&nbsp;' . $articles_expected['topics_name']; ?></td>
<?php
      }
?>
          </tr>
<?php
      if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true') {
?>
          <tr>
            <td class="main" style="padding-left:15px"><?php echo clean_html_comments(substr($articles_expected['articles_head_desc_tag'],0, MAX_ARTICLE_ABSTRACT_LENGTH)) . ((strlen($articles_expected['articles_head_desc_tag']) >= MAX_ARTICLE_ABSTRACT_LENGTH) ? '...' : ''); ?></td>
          </tr>
<?php
      }
?>
          <tr>
            <td class="smalltext" style="padding-left:15px"><?php echo TEXT_DATE_EXPECTED . ' ' . tep_date_long($articles_expected['date_expected']); ?></td>
          </tr>
<?php
      if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true' || DISPLAY_DATE_ADDED_ARTICLE_LISTING) {
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
     }
  } // End of listing loop
?>
        </table></td>
      </tr>
<!-- eof upcoming_articles //-->
<?php
  }
?>

