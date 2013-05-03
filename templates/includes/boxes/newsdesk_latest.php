<?php
  if (DISPLAY_LATEST_NEWS_BOX) {
?>
<!-- newsdesk //-->


          <?php
      $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
      while ($configuration = tep_db_fetch_array($configuration_query)) {
          if (!defined($configuration['cfgKey']))
              define($configuration['cfgKey'], $configuration['cfgValue']);
      } //while ($configuration = tep_db_fetch_array($configuration_query))
      $latest_news_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, pd.newsdesk_article_url_name,
p.newsdesk_image, p.newsdesk_date_added, p.newsdesk_last_modified,
p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . '
pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_NEWSDESK_NEWS);
      if (!tep_db_num_rows($latest_news_var_query)) {
      } //if (!tep_db_num_rows($latest_news_var_query))
      else {
		  echo '<div class="module">
  <div>
    <div>
      <div>
        <h3>LATEST</h3>
        <ul>';
          $info_box_contents = array();
          $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_NEWSDESK_LATEST);
          new infoBoxHeading($info_box_contents, false, false);
          $latest_news_string = '';
          $row = 0;
          while ($latest_news = tep_db_fetch_array($latest_news_var_query)) {
              $latest_news['newsdesk'] = array('name' => $latest_news['newsdesk_article_name'], 'id' => $latest_news['newsdesk_id'], 'date' => $latest_news['newsdesk_date_added'], );
              $latest_news_string .= '<li><a class="smallText" href="';
              $latest_news_string .= tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $latest_news['newsdesk_id']);
              $latest_news_string .= '">';
              $latest_news_string .= $latest_news['newsdesk_article_name'];
              $latest_news_string .= '</a>';
              $latest_news_string .= '</li>';
              $info_box_contents = array();
              $info_box_contents[] = array('align' => '', 'params' => '', 'text' => $latest_news_string);
              $row++;
          } //while ($latest_news = tep_db_fetch_array($latest_news_var_query))
          new contentBox($info_box_contents);
		  echo '</ul>
      </div>
    </div>
  </div>
</div>';
      } //else
?>
          <!-- newsdesk_eof //-->
        
<?php
      } else
      {
      }
?>
