<?php
  require('includes/application_top.php');
  $topic_depth = 'top';
  if (isset($tPath) && tep_not_null($tPath)) {
      $topics_articles_query = tep_db_query("SELECT COUNT(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where topics_id = '" . (int)$current_topic_id . "'");
      $topics_articles = tep_db_fetch_array($topics_articles_query);
      if ($topics_articles['total'] > 0) {
          $topic_depth = 'articles';
      } else {
          $topic_parent_query = tep_db_query("SELECT COUNT(*) as total from " . TABLE_TOPICS . " where parent_id = '" . (int)$current_topic_id . "'");
          $topic_parent = tep_db_fetch_array($topic_parent_query);
          if ($topic_parent['total'] > 0) {
              $topic_depth = 'nested';
          } else {
              $topic_depth = 'articles';
          }
      }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLES);
  if ($topic_depth == 'top' && !isset($_GET['authors_id'])) {
      $breadcrumb->add(NAVBAR_TITLE_DEFAULT, tep_href_link(FILENAME_ARTICLES));
  }
  if (file_exists(DIR_WS_INCLUDES . 'article_header_tags.php')) {
      require(DIR_WS_INCLUDES . 'article_header_tags.php');
  } else {
  }
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
     
 
  <!-- body_text //-->

  
  <?php
  if ($topic_depth == 'nested') {
      $topic_query = tep_db_query("select td.topics_name, td.topics_heading_title, td.topics_description from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'");
      $topic = tep_db_fetch_array($topic_query);
?>


<h1>
  <?php
      if (tep_not_null($topic['topics_heading_title'])) {
          echo $topic['topics_heading_title'];
      } else {
          echo HEADING_TITLE;
      }
?>
  1</h1>
  <?php
      if (tep_not_null($topic['topics_description'])) {
          echo $topic['topics_description'];
      }
      if (isset($tPath) && strpos('_', $tPath)) {
          $topic_links = array_reverse($tPath_array);
          for ($i = 0, $n = sizeof($topic_links); $i < $n; $i++) {
              $topics_query = tep_db_query("SELECT COUNT(*) as total from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$topic_links[$i] . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "'");
              $topics = tep_db_fetch_array($topics_query);
              if ($topics['total'] < 1) {
              } else {
                  $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$topic_links[$i] . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
                  break;
              }
          }
      } else {
          $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$current_topic_id . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
      }
      $new_articles_topic_id = $current_topic_id;
  } elseif ($topic_depth == 'articles' || isset($_GET['authors_id'])) {
      $topic_query = tep_db_query("select td.topics_name, td.topics_heading_title, td.topics_description from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'");
      $topic = tep_db_fetch_array($topic_query);
      if (isset($_GET['authors_id'])) {
          if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
              $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id from ((" . TABLE_ARTICLES . " a) left join " . TABLE_AUTHORS . " au using(authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES_TO_TOPICS . " a2t) left join " . TABLE_TOPICS_DESCRIPTION . " td using(topics_id) where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_status = '1' and au.authors_id = '" . (int)$_GET['authors_id'] . "' and a.articles_id = a2t.articles_id and ad.articles_id = a2t.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' and a2t.topics_id = '" . (int)$_GET['filter_id'] . "' order by a.articles_date_added desc, ad.articles_name";
          } else {
              $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id from ((" . TABLE_ARTICLES . " a) left join " . TABLE_AUTHORS . " au using(authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES_TO_TOPICS . " a2t) left join " . TABLE_TOPICS_DESCRIPTION . " td using(topics_id) where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_status = '1' and au.authors_id = '" . (int)$_GET['authors_id'] . "' and a.articles_id = a2t.articles_id and ad.articles_id = a2t.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' order by a.articles_date_added desc, ad.articles_name";
          }
      } else {
          if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
              $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id from ((" . TABLE_ARTICLES . " a) left join " . TABLE_AUTHORS . " au using(authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES_TO_TOPICS . " a2t) left join " . TABLE_TOPICS_DESCRIPTION . " td using(topics_id) where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_status = '1' and a.articles_id = a2t.articles_id and ad.articles_id = a2t.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' and a2t.topics_id = '" . (int)$current_topic_id . "' and au.authors_id = '" . (int)$_GET['filter_id'] . "' order by a.articles_date_added desc, ad.articles_name";
          } else {
              $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id from ((" . TABLE_ARTICLES . " a) left join " . TABLE_AUTHORS . " au using(authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES_TO_TOPICS . " a2t) left join " . TABLE_TOPICS_DESCRIPTION . " td using(topics_id) where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_status = '1' and a.articles_id = a2t.articles_id and ad.articles_id = a2t.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' and a2t.topics_id = '" . (int)$current_topic_id . "' order by a.articles_date_added desc, ad.articles_name";
          }
      }
      if (tep_not_null($topic['topics_heading_title'])) {
          echo '<h1>';
          echo $topic['topics_heading_title'];
          echo '</h1>';
      } else {
          echo HEADING_TITLE;
      }
      if (isset($_GET['authors_id'])) {
          $author_query = tep_db_query("select au.authors_name, aui.authors_description, aui.authors_url from " . TABLE_AUTHORS . " au, " . TABLE_AUTHORS_INFO . " aui where au.authors_id = '" . (int)$_GET['authors_id'] . "' and au.authors_id = aui.authors_id and aui.languages_id = '" . (int)$languages_id . "'");
          $authors = tep_db_fetch_array($author_query);
          $author_name = $authors['authors_name'];
          $authors_description = $authors['authors_description'];
          $authors_url = $authors['authors_url'];
          echo TEXT_ARTICLES_BY . $author_name;
      }
      if (ARTICLE_LIST_FILTER) {
          if (isset($_GET['authors_id'])) {
              $filterlist_sql = "select distinct t.topics_id as id, td.topics_name as name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where a.articles_status = '1' and a.articles_id = a2t.articles_id and a2t.topics_id = t.topics_id and a2t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' and a.authors_id = '" . (int)$_GET['authors_id'] . "' order by td.topics_name";
          } else {
              $filterlist_sql = "select distinct au.authors_id as id, au.authors_name as name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_AUTHORS . " au where a.articles_status = '1' and a.authors_id = au.authors_id and a.articles_id = a2t.articles_id and a2t.topics_id = '" . (int)$current_topic_id . "' order by au.authors_name";
          }
          $filterlist_query = tep_db_query($filterlist_sql);
          if (tep_db_num_rows($filterlist_query) > 1) {
              echo '<td align="right" class="main">' . tep_draw_form('filter', FILENAME_ARTICLES, 'get') . TEXT_SHOW . '&nbsp;';
              if (isset($_GET['authors_id'])) {
                  echo tep_draw_hidden_field('authors_id', $_GET['authors_id']);
                  $options = array(array('id' => '', 'text' => TEXT_ALL_TOPICS));
              } else {
                  echo tep_draw_hidden_field('tPath', $tPath);
                  $options = array(array('id' => '', 'text' => TEXT_ALL_AUTHORS));
              }
              echo tep_draw_hidden_field('sort', $_GET['sort']);
              while ($filterlist = tep_db_fetch_array($filterlist_query)) {
                  $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
              }
              echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
              echo '</form></td>' . "\n";
          }
      }
      if (tep_not_null($topic['topics_description'])) {
          echo $topic['topics_description'];
      }
      if (tep_not_null($authors_description)) {
          echo $authors_description;
      }
      if (tep_not_null($authors_url)) {
          echo sprintf(TEXT_MORE_INFORMATION, $authors_url);
      }
      include(DIR_WS_MODULES . FILENAME_ARTICLE_LISTING);
  } else {
      echo '<h1>'. HEADING_TITLE .'</h1>';
      echo '<p>' . TEXT_CURRENT_ARTICLES . '</p>';
      $articles_all_array = array();
      $articles_all_query_raw = "select a.articles_id, a.articles_date_added, ad.articles_name, ad.articles_head_desc_tag, au.authors_id, au.authors_name, td.topics_id, td.topics_name from ((" . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t) left join " . TABLE_TOPICS_DESCRIPTION . " td on a2t.topics_id = td.topics_id) left join " . TABLE_AUTHORS . " au on a.authors_id = au.authors_id, " . TABLE_ARTICLES_DESCRIPTION . " ad where (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) and a.articles_id = a2t.articles_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' order by a.articles_date_added desc, ad.articles_name";
      $listing_sql = $articles_all_query_raw;
      include(DIR_WS_MODULES . FILENAME_ARTICLE_LISTING);
  }
?>
 
  <!-- body_text_eof //--> 
  
  <!-- right_navigation //-->
  <?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>
  <div>

  
  <!-- body_eof //--> 

  <?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>
  <!-- footer_eof //-->
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>