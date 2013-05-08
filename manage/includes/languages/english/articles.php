<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

define('HEADING_TITLE', 'Topics / Articles');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_TOPICS_ARTICLES', 'Topics / Articles');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_ARTICLES_CURRENT', 'Current:');

define('TEXT_NEW_ARTICLE', 'New Article in &quot;%s&quot;');
define('TEXT_TOPICS', 'Topics:');
define('TEXT_SUBTOPICS', 'Subtopics:');
define('TEXT_ARTICLES', 'Articles:');
define('TEXT_ARTICLES_AVERAGE_RATING', 'Average Rating:');
define('TEXT_ARTICLES_HEAD_TITLE_TAG', 'HTML Page Title:');
define('TEXT_ARTICLES_HEAD_DESC_TAG', 'Meta Description:<br><small>(Article Abstract =<br>first %s charachters)</small>');
define('TEXT_ARTICLES_HEAD_KEYWORDS_TAG', 'Meta Keywords:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Expected:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_NO_CHILD_TOPICS_OR_ARTICLES', 'Please insert a new topic or article in this level.');
define('TEXT_ARTICLE_MORE_INFORMATION', 'For more information, please visit this articles <a href="http://%s" target="blank"><u>web page</u></a>.');
define('TEXT_ARTICLE_DATE_ADDED', 'This article was added to our site on %s.');
define('TEXT_ARTICLE_DATE_AVAILABLE', 'This article will is expected on %s.');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_TOPICS_ID', 'Topic ID:');
define('TEXT_EDIT_TOPICS_NAME', 'Topic Name:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new topic you wish to copy this article to');
define('TEXT_INFO_CURRENT_TOPICS', 'Current Topics:');

define('TEXT_INFO_HEADING_NEW_TOPIC', 'New Topic');
define('TEXT_INFO_HEADING_EDIT_TOPIC', 'Edit Topic');
define('TEXT_INFO_HEADING_DELETE_TOPIC', 'Delete Topic');
define('TEXT_INFO_HEADING_MOVE_TOPIC', 'Move Topic');
define('TEXT_INFO_HEADING_DELETE_ARTICLE', 'Delete Article');
define('TEXT_INFO_HEADING_MOVE_ARTICLE', 'Move Article');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_TOPIC_INTRO', 'Are you sure you want to delete this topic?');
define('TEXT_DELETE_ARTICLE_INTRO', 'Are you sure you want to permanently delete this article?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s child-topics still linked to this topic!');
define('TEXT_DELETE_WARNING_ARTICLES', '<b>WARNING:</b> There are %s articles still linked to this topic!');

define('TEXT_MOVE_ARTICLES_INTRO', 'Please select which topic you wish <b>%s</b> to reside in');
define('TEXT_MOVE_TOPICS_INTRO', 'Please select which topic you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_TOPIC_INTRO', 'Please fill out the following information for the new topic');
define('TEXT_TOPICS_NAME', 'Topic Name:');
define('TEXT_SORT_ORDER', 'Sort Order:');

define('TEXT_EDIT_TOPICS_HEADING_TITLE', 'Topic Heading Title:');
define('TEXT_EDIT_TOPICS_DESCRIPTION', 'Topic Description:');

define('TEXT_ARTICLES_STATUS', 'Article Status:');
define('TEXT_ARTICLES_DATE_AVAILABLE', 'Date Expected:');
define('TEXT_ARTICLE_AVAILABLE', 'Published');
define('TEXT_ARTICLE_NOT_AVAILABLE', 'Draft');
define('TEXT_ARTICLES_AUTHOR', 'Author:');
define('TEXT_ARTICLES_NAME', 'Article Name:');
define('TEXT_ARTICLES_DESCRIPTION', 'Article Content:');
define('TEXT_ARTICLES_URL', 'Article URL:');
define('TEXT_ARTICLES_URL_WITHOUT_HTTP', '<small>(without http://)</small>');

define('EMPTY_TOPIC', 'Empty Topic');

define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_AS_LINK', 'Link article');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate article');

define('ERROR_CANNOT_LINK_TO_SAME_TOPIC', 'Error: Can not link articles in the same topic.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_TOPIC_TO_PARENT', 'Error: Topic cannot be moved into child topic.');

?>
