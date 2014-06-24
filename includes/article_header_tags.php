<?php

/*
  $Id: article_header_tags.php, v1.0 2003/12/04 12:00:00 ra Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

// Modification of Header Tags Contribution
// WebMakers.com Added: Header Tags Generator v2.0 

require(DIR_WS_LANGUAGES . $language . '/' . 'article_header_tags.php');

echo '<meta name="reply-to" content="' . HEAD_REPLY_TAG_ALL . '">' . "\n";

$the_desc='';
$the_key_words='';
$the_title='';

// Define specific settings per page:
switch (true) {

// ARTICLES.PHP
    case ((strstr($_SERVER['PHP_SELF'],'articles.php') or strstr($PHP_SELF,'articles.php')) &! strstr($PHP_SELF,'new_articles.php')):
	
    $the_topic_query = tep_db_query("select td.topics_name from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'");
    $the_topic = tep_db_fetch_array($the_topic_query);

    $the_authors_query= tep_db_query("select authors_name from " . TABLE_AUTHORS . " where authors_id = '" . (int)$_GET['authors_id'] . "'");
    $the_authors = tep_db_fetch_array($the_authors_query);

    if (HTDA_ARTICLES_ON=='1') {
      $the_desc= HEAD_DESC_TAG_ARTICLES . '. ' . HEAD_DESC_TAG_ALL;
    } else {
      $the_desc= HEAD_DESC_TAG_ARTICLES;
    }

    if (HTKA_ARTICLES_ON=='1') {

      if (tep_not_null($the_topic['topics_name'])) {
        $the_key_words .= $the_topic['topics_name'];
      } else {
        if (tep_not_null($the_authors['authors_name'])) {
          $the_key_words .= $the_authors['authors_name'];
        }
      }

      $the_key_words = HEAD_KEY_TAG_ARTICLES . ', ' . $the_key_words . ', ' . HEAD_KEY_TAG_ALL;

    } else {
      $the_key_words= HEAD_KEY_TAG_ARTICLES;
    }

    if (HTTA_ARTICLES_ON=='1') {
      $the_title= HEAD_TITLE_TAG_ALL . '' . HEAD_TITLE_TAG_ARTICLES;

      if (tep_not_null($the_topic['topics_name'])) {
        $the_title .= '' . $the_topic['topics_name'];
      } else {
        if (tep_not_null($the_authors['authors_name'])) {
          $the_title .= TEXT_BY . $the_authors['authors_name'];
        }
      }

    } else {
      $the_title= HEAD_TITLE_TAG_ARTICLES;
    }

    break;

// ARTICLE_INFO.PHP
  case ( strstr($_SERVER['PHP_SELF'],'article_info.php') or strstr($PHP_SELF,'article_info.php') ):

    $the_article_info_query = tep_db_query("select a.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_title_tag, ad.articles_head_keywords_tag, ad.articles_head_desc_tag, ad.articles_url, a.articles_date_added, a.articles_date_available, a.authors_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . $_GET['articles_id'] . "' and ad.articles_id = '" . $_GET['articles_id'] . "'");

    $the_article_info_query = tep_db_query("select ad.language_id, a.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_title_tag, ad.articles_head_keywords_tag, ad.articles_head_desc_tag, ad.articles_url, a.articles_date_added, a.articles_date_available, a.authors_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . (int)$_GET['articles_id'] . "' and ad.articles_id = '" . (int)$_GET['articles_id'] . "'" . " and ad.language_id ='" .  (int)$languages_id . "'");
	
    $the_article_info = tep_db_fetch_array($the_article_info_query);

    if (empty($the_article_info['articles_head_desc_tag'])) {
    
	  $the_desc= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    } else {
	
      if ( HTDA_ARTICLE_INFO_ON=='1' ) {
	  
        $the_desc= strip_tags($the_article_info['articles_head_desc_tag']) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= strip_tags($the_article_info['articles_head_desc_tag']);
      }
    }
	

    if (empty($the_article_info['articles_head_keywords_tag'])) {
      $the_key_words= HEAD_KEY_TAG_ALL;
    } else {
      if ( HTKA_ARTICLE_INFO_ON=='1' ) {
        $the_key_words= strip_tags($the_article_info['articles_head_keywords_tag']) . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= strip_tags($the_article_info['articles_head_keywords_tag']);
      }
    }

    if (empty($the_article_info['articles_head_title_tag'])) {
      $the_title= HEAD_TITLE_TAG_ALL . '' . NAVBAR_TITLE;
    } else {
      if ( HTTA_ARTICLE_INFO_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_ALL . '' .  HEAD_TITLE_TAG_ARTICLE_INFO . '' . $topics['topics_name'] . $authors['authors_name'] . '' . clean_html_comments($the_article_info['articles_head_title_tag']);
      } else {
        $the_title= strip_tags($the_article_info['articles_head_title_tag']);
      }
    }

    break;

// ARTICLES_NEW.PHP
  case ( strstr($_SERVER['PHP_SELF'],'articles_new.php') or strstr($PHP_SELF,'articles_new.php') ):
    if ( HEAD_DESC_TAG_ARTICLES_NEW!='' ) {
      if ( HTDA_ARTICLES_NEW_ON=='1' ) {
        $the_desc= HEAD_DESC_TAG_ARTICLES_NEW . '. ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= HEAD_DESC_TAG_ARTICLES_NEW;
      }
    } else {
      $the_desc= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_ARTICLES_NEW=='' ) {
      // Build a list of ALL new article names to put in keywords
      $articles_new_array = array();
      $articles_new_query_raw = "select ad.articles_name from " . TABLE_ARTICLES . " a left join " . TABLE_AUTHORS . " au on (a.authors_id = au.authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' order by a.articles_date_added DESC, ad.articles_name";
      $articles_new_split = new splitPageResults($articles_new_query_raw, MAX_NEW_ARTICLES_PER_PAGE);
      $articles_new_query = tep_db_query($articles_new_split->sql_query);

      $row = 0;
      $the_new_articles='';
      while ($articles_new = tep_db_fetch_array($articles_new_query)) {
        $the_new_articles .= clean_html_comments($articles_new['articles_name']) . ', ';
      }
      if ( HTKA_ARTICLES_NEW_ON=='1' ) {
        $the_key_words= NAVBAR_TITLE . ', ' . $the_new_articles . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= NAVBAR_TITLE . ', ' . $the_new_articles;
      }
    } else {
      $the_key_words= HEAD_KEY_TAG_ARTICLES_NEW . ', ' . HEAD_KEY_TAG_ALL;
    }

    if ( HEAD_TITLE_TAG_ARTICLES_NEW!='' ) {
      if ( HTTA_ARTICLES_NEW_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_ALL . '' . HEAD_TITLE_TAG_ARTICLES_NEW;
      } else {
        $the_title= HEAD_TITLE_TAG_ARTICLES_NEW;
      }
    } else {
      $the_title= HEAD_TITLE_TAG_ALL . '' . NAVBAR_TITLE;
    }

    break;

// ARTICLES_REVIEWS_INFO.PHP and ARTICLES_REVIEWS.PHP
  case ( strstr($_SERVER['PHP_SELF'],'article_reviews_info.php') or strstr($_SERVER['PHP_SELF'],'article_reviews.php') or strstr($PHP_SELF,'article_reviews_info.php') or strstr($PHP_SELF,'article_reviews.php') ):
    if ( HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTDA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $the_desc= NAVBAR_TITLE . '. ' . tep_get_header_tag_articles_desc(isset($_GET['reviews_id'])) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= NAVBAR_TITLE . '. ' . tep_get_header_tag_articles_desc(isset($_GET['reviews_id']));
      }
    } else {
      $the_desc= HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO;
    }

    if ( HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTKA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $the_key_words= NAVBAR_TITLE . ', ' . tep_get_header_tag_articles_keywords(isset($_GET['reviews_id'])) . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= NAVBAR_TITLE . ', ' . tep_get_header_tag_articles_keywords(isset($_GET['reviews_id']));
      }
    } else {
      $the_key_words= HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO;
    }

    if ( HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTTA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_ALL . '' . HEADING_TITLE . tep_get_header_tag_articles_title(isset($_GET['reviews_id']));
      } else {
        $the_title= tep_get_header_tag_articles_title(isset($_GET['reviews_id']));
      }
    } else {
      $the_title= HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO;
    }

    break;

// ALL OTHER PAGES NOT DEFINED ABOVE
  default:
    $the_desc= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    $the_key_words= NAVBAR_TITLE . ', ' . HEAD_KEY_TAG_ALL;
    $the_title= HEAD_TITLE_TAG_ALL . '' . NAVBAR_TITLE;
    break;

  }
  
echo '<meta name="description" content="' . $the_desc . '">' . "\n";
echo '<meta name="keywords" content="' . $the_key_words . '">' . "\n";
echo '<title>' . $the_title . '</title>' . "\n";

?>