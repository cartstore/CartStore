<?php
/*
  $Id: categories.php,v 1.23 2002/11/12 14:09:30 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  GNU General Public License Compatible

  Ti use this box call rss.php?box=newsdesk_latest
*/

$latest_news_var_query = tep_db_query(
'select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, pd.newsdesk_article_url_name, 
p.newsdesk_image, p.newsdesk_date_added, p.newsdesk_last_modified, 
p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' 
pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_NEWSDESK_NEWS);


$latest_news_string = '';

$row = 0;
while ($latest_news = tep_db_fetch_array($latest_news_var_query))  {
$latest_news['newsdesk'] = array(
		'name' => $latest_news['newsdesk_article_name'],
		'id' => $latest_news['newsdesk_id'],
		'date' => $latest_news['newsdesk_date_added'],
		'text' => $latest_news['newsdesk_article_shorttext']
	);

//
// Print
//
//header("Content-type: text/plain");

//require('includes/application_top.php');

print '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
print '<rss version="0.91">' . "\n";
print '<channel>' . "\n";
print "<source>" . '<br><a href="http://www.yourdomain.com/includes/modules/newsdesk/rss/newsdesk_latest.php">RSS Newsfeeds</a><br>' . "</source>\n";
print "<item>\n";
print "<title>" . $latest_news['newsdesk_article_name'] . "</title>\n";
print "<link>" . '<br><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, "newsdesk_id=" . $latest_news['newsdesk_id'])  . '">' . $latest_news['newsdesk_article_name'] . '<a/><br>' . "</link>\n";
print "<description>" . $latest_news['newsdesk_article_shorttext'] . "</description>\n";
print "</item>\n\n";

	$row++;
}
print "  </channel>\n";
print "</rss>\n";



require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

if (!strlen($box))
{ 
  $box = "categories";
}

$file = DIR_WS_RSS . $box . '.php';

if (file_exists($file))
{
  require($file); 
}
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>