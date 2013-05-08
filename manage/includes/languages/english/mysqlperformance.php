<?php
/*
  $Id: mysqlperformance.php,v 1.0 2007/02/04 22:50:51 hpdl Exp $
  Language file
  Contribution made by Biznetstar.com 
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'MYSQL PERFORMANCE');

define('TABLE_HEADING_NUMBER', '#');
define('TABLE_HEADING_QUERY', 'Query');
define('TABLE_HEADING_QLOCATION', 'Query Location');
define('TABLE_HEADING_QUERY_TIME', 'Query Time');
define('TABLE_HEADING_DATE_CREATED', 'Date Created');

define('TEXT_NOTE_MYSQL_PERFORMANCE', 'Note: These are only the queries who took more than ' . MYSQL_PERFORMANCE_TRESHOLD . ' seconds. <br/>Oldest records are displayed first. ');
define('TEXT_NOTE_2_MYSQL_PERFORMANCE', '');
define('TEXT_DELETE_QUERY','DELETE Query from log.');
define('TEXT_INFO_HEADING_DELETE','DELETE query');
define('TEXT_INFO_DELETE_INTRO','Delete this query from log?');
define('TEXT_DELETE','Delete all records?');
define('IMAGE_BUTTON_DELETE','Delete all records');
define('IMAGE_BUTTON_CANCEL','Do not delete records');
?>
