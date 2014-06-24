<?php
  function tep_show_topic2($counter)
  {
      global $tree, $topics_string, $tPath_array;
      for ($i = 0; $i < $tree[$counter]['level']; $i++) {
          $topics_string .= "";
      }
      $topics_string .= '<li><a href="';
      if ($tree[$counter]['parent'] == 0) {
          $tPath_new = 'tPath=' . $counter;
      } else {
          $tPath_new = 'tPath=' . $tree[$counter]['path'];
      }
      $topics_string .= tep_href_link(FILENAME_ARTICLES, $tPath_new) . '"><span>';
      if (isset($tPath_array) && in_array($counter, $tPath_array)) {
          $topics_string .= '<b>';
      }
      
      $topics_string .= $tree[$counter]['name'];
      if (isset($tPath_array) && in_array($counter, $tPath_array)) {
          $topics_string .= '</b>';
      }
      if (tep_has_topic_subtopics($counter)) {
          $topics_string .= ' -&gt;';
      }
      $topics_string .= '</span></a></li>';
      if (SHOW_ARTICLE_COUNTS == 'false') {
          $articles_in_topic = tep_count_articles_in_topic($counter);
          if ($articles_in_topic > 0) {
              $topics_string .= '&nbsp;(' . $articles_in_topic . ')';
          }
      }
      $topics_string .= '';
      if ($tree[$counter]['next_id'] != false) {
          tep_show_topic2($tree[$counter]['next_id']);
      }
  }
?>
<!-- topics //-->

<ul>
<li><a href="#"><span>Information</span></a>
  <ul>
    <?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_ARTICLES);
  new infoBoxHeading($info_box_contents, true, true);
  $topics_string = '';
  $tree = array();
  $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '0' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
  while ($topics = tep_db_fetch_array($topics_query)) {
      $tree[$topics['topics_id']] = array('name' => $topics['topics_name'], 'parent' => $topics['parent_id'], 'level' => 0, 'path' => $topics['topics_id'], 'next_id' => false);
      if (isset($parent_id)) {
          $tree[$parent_id]['next_id'] = $topics['topics_id'];
      }
      $parent_id = $topics['topics_id'];
      if (!isset($first_topic_element)) {
          $first_topic_element = $topics['topics_id'];
      }
  }
  
  if (tep_not_null($tPath)) {
      $new_path = '';
      reset($tPath_array);
      while (list($key, $value) = each($tPath_array)) {
          unset($parent_id);
          unset($first_id);
          $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$value . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
          if (tep_db_num_rows($topics_query)) {
              $new_path .= $value;
              while ($row = tep_db_fetch_array($topics_query)) {
                  $tree[$row['topics_id']] = array('name' => $row['topics_name'], 'parent' => $row['parent_id'], 'level' => $key + 1, 'path' => $new_path . '_' . $row['topics_id'], 'next_id' => false);
                  if (isset($parent_id)) {
                      $tree[$parent_id]['next_id'] = $row['topics_id'];
                  }
                  $parent_id = $row['topics_id'];
                  if (!isset($first_id)) {
                      $first_id = $row['topics_id'];
                  }
                  $last_id = $row['topics_id'];
              }
              $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
              $tree[$value]['next_id'] = $first_id;
              $new_path .= '_';
          } else {
              break;
          }
      }
  }
  tep_show_topic2($first_topic_element);
  $info_box_contents = array();
  $new_articles_string = '';
  $all_articles_string = '';
  if (DISPLAY_NEW_ARTICLES == 'true') {
      if (SHOW_ARTICLE_COUNTS == 'true') {
          
          
          
          $articles_new_query = tep_db_query("SELECT a.articles_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS_DESCRIPTION . " td, " . TABLE_AUTHORS . " au, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.authors_id = au.authors_id and a2t.topics_id = td.topics_id and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_id = a2t.articles_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' and a.articles_date_added > SUBDATE(now( ), INTERVAL '" . NEW_ARTICLES_DAYS_DISPLAY . "' DAY)");
          $articles_new_count = '';
      }
      if (strstr($_SERVER['PHP_SELF'], FILENAME_ARTICLES_NEW) or strstr($PHP_SELF, FILENAME_ARTICLES_NEW)) {
          $new_articles_string = '<b>';
      }
      if (strstr($_SERVER['PHP_SELF'], FILENAME_ARTICLES_NEW) or strstr($PHP_SELF, FILENAME_ARTICLES_NEW)) {
          $new_articles_string .= '</b>';
      }
      $new_articles_string .= $articles_new_count . '';
  }
  if (DISPLAY_ALL_ARTICLES == 'true') {
      if (SHOW_ARTICLE_COUNTS == 'true') {
          
          $articles_new_query = tep_db_query("SELECT a.articles_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS_DESCRIPTION . " td, " . TABLE_AUTHORS . " au, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.authors_id = au.authors_id and a2t.topics_id = td.topics_id and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_id = a2t.articles_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "'");
      } else {
          $articles_all_count = '';
      }
      if ($topic_depth == 'top') {
          $all_articles_string = '<b>';
      }
      if ($topic_depth == 'top') {
          $all_articles_string .= '</b>';
      }
      $all_articles_string .= $articles_all_count . '';
  }
  $info_box_contents[] = array('text' => $new_articles_string . $all_articles_string . $topics_string);
  new infoBox($info_box_contents);
?>
    <li><a href="articles.php"><span>All Articles</span></a></li>
  </ul>
</li>
<!-- topics_eof //-->