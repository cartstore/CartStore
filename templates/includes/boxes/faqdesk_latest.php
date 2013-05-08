<?php
  
  
  $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }
  if (DISPLAY_LATEST_FAQS_BOX) {
?>
<!-- faqdesk //-->

<div>><?php
      
      
      $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
      while ($configuration = tep_db_fetch_array($configuration_query)) {
          define($configuration['cfgKey'], $configuration['cfgValue']);
      }
      $latest_news_var_query = tep_db_query('select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url, 
p.faqdesk_image, p.faqdesk_date_added, p.faqdesk_last_modified, 
p.faqdesk_date_available, p.faqdesk_status  from ' . TABLE_FAQDESK . ' p, ' . TABLE_FAQDESK_DESCRIPTION . ' 
pd WHERE pd.faqdesk_id = p.faqdesk_id and pd.language_id = "' . $languages_id . '" and faqdesk_status = 1 ORDER BY faqdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_FAQDESK_FAQS);
      if (!tep_db_num_rows($latest_news_var_query)) {
          
          
      } else {
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left', 'text' => BOX_HEADING_FAQDESK_LATEST);
          new infoBoxHeading($info_box_contents, false, false);
          $latest_news_string = '';
          $row = 0;
          while ($latest_news = tep_db_fetch_array($latest_news_var_query)) {
              $latest_news['faqdesk'] = array('name' => $latest_news['faqdesk_question'], 'id' => $latest_news['faqdesk_id'], 'date' => $latest_news['faqdesk_date_added'], );
              $latest_news_string .= '<a class="smallText" href="';
              $latest_news_string .= tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $latest_news['faqdesk_id']);
              $latest_news_string .= '">';
              $latest_news_string .= $latest_news['faqdesk_question'];
              $latest_news_string .= '</a>';
              $latest_news_string .= '<br>';
              $info_box_contents = array();
              $info_box_contents[] = array('align' => 'left', 'params' => 'class="smallText" valign="top"', 'text' => $latest_news_string);
              $row++;
          }
          new infoBox($info_box_contents);
      }
?>
    <!-- faqdesk_eof //-->
  </div>
<?php
      } else
      {
      }
?>
<?php
?>