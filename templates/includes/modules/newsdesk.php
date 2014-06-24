<?php
  
  
  $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      if (!defined($configuration['cfgKey']))
          define($configuration['cfgKey'], $configuration['cfgValue']);
  } //while ($configuration = tep_db_fetch_array($configuration_query))
  $newsdesk_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, pd.newsdesk_article_url_name,
 p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed,
 p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . '
 pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 and p.newsdesk_sticky = 0 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_NEWS);
  if (!tep_db_num_rows($newsdesk_var_query)) {
      
      echo '' . TEXT_NO_NEWSDESK_NEWS . '';
  } //if (!tep_db_num_rows($newsdesk_var_query))
  else {
	  echo '
	  
	  ';
      $info_box_contents = array();
      $info_box_contents[] = array('align' => '', 'text' => TABLE_HEADING_NEWSDESK);
      new contentBoxHeading($info_box_contents);
      $info_box_contents = array();
      $row = 0;
      while ($newsdesk_var = tep_db_fetch_array($newsdesk_var_query)) {
          if (DISPLAY_NEWSDESK_IMAGE) {
              if ($newsdesk_var['newsdesk_image'] != '') {
                  $insert_image = '



';
              } //if ($newsdesk_var['newsdesk_image'] != '')
          } //if (DISPLAY_NEWSDESK_IMAGE)
          if (DISPLAY_NEWSDESK_IMAGE_TWO) {
              if ($newsdesk_var['newsdesk_image_two'] != '') {
                  $insert_image_two = '



';
              } //if ($newsdesk_var['newsdesk_image_two'] != '')
          } //if (DISPLAY_NEWSDESK_IMAGE_TWO)
          if (DISPLAY_NEWSDESK_IMAGE_THREE) {
              if ($newsdesk_var['newsdesk_image_three'] != '') {
                  $insert_image_three = '



';
              } //if ($newsdesk_var['newsdesk_image_three'] != '')
          } //if (DISPLAY_NEWSDESK_IMAGE_THREE)
          if (DISPLAY_NEWSDESK_VIEWCOUNT) {
              $insert_viewcount = '<i>' . TEXT_NEWSDESK_VIEWED . $newsdesk_var['newsdesk_article_viewed'] . '</i>';
          } //if (DISPLAY_NEWSDESK_VIEWCOUNT)
          if (DISPLAY_NEWSDESK_READMORE) {
              $insert_readmore = '<a class="readon" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . TEXT_NEWSDESK_READMORE . '</a>';
          } //if (DISPLAY_NEWSDESK_READMORE)
          if (DISPLAY_NEWSDESK_SUMMARY) {
              $insert_summary = '' . $newsdesk_var['newsdesk_article_shorttext'] . '';
          } //if (DISPLAY_NEWSDESK_SUMMARY)
          if (DISPLAY_NEWSDESK_HEADLINE) {
              $insert_headline = ' <a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . $newsdesk_var['newsdesk_article_name'] . '</a>' . $insert_image . '';
          } //if (DISPLAY_NEWSDESK_HEADLINE)
          if (DISPLAY_NEWSDESK_DATE) {
              $insert_date = '- <span class="newsdate">' . tep_date_long($newsdesk_var['newsdesk_date_added']) . '</span>';
          } //if (DISPLAY_NEWSDESK_DATE)
          $info_box_contents[$row] = array('align' => '', 'params' => '', 'text' => '
<div class="media">
<div class="media-body">
    <h4 class="media-heading">
    
 ' . $insert_headline .'
</h4>
     ' . $insert_summary . ' ' . $insert_readmore . ' </div>
<hr></div>

');
        
          $row++;
      } //while ($newsdesk_var = tep_db_fetch_array($newsdesk_var_query))
      
      echo '     <div class="page-title">
<h2 class="subtitle">Blog</h2>
</div>
      <div class="">
<div class="">
 

 ';
      new contentBox($info_box_contents);
      echo ' </div>
</div>  
';
  } //else
?>
 
      <!-- newsdesk_eof //-->
      <?php
?>

