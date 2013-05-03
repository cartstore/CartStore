<?php
/*
  $Id: categories.php,v 1.23 2002/11/12 14:09:30 dgw_ Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  GNU General Public License Compatible

  Ti use this box call rss.php?box=categories
*/

$latest_news_var_query = tep_db_query(
'select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url, pd.faqdesk_extra_url_name, 
p.faqdesk_image, p.faqdesk_date_added, p.faqdesk_last_modified, 
p.faqdesk_date_available, p.faqdesk_status  from ' . TABLE_FAQDESK . ' p, ' . TABLE_FAQDESK_DESCRIPTION . ' 
pd WHERE pd.faqdesk_id = p.faqdesk_id and pd.language_id = "' . $languages_id . '" and faqdesk_status = 1 ORDER BY faqdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_FAQDESK_FAQS);


$latest_news_string = '';

$row = 0;
while ($latest_news = tep_db_fetch_array($latest_news_var_query))  {
$latest_news['faqdesk'] = array(
		'name' => $latest_news['faqdesk_question'],
		'id' => $latest_news['faqdesk_id'],
		'date' => $latest_news['faqdesk_date_added'],
	);

//
// Print
//
print "<item>\n";
print "<title>".$latest_news['faqdesk_question'] ."</title>\n";
print "<link>".tep_href_link(FILENAME_FAQDESK_INFO, "faqdesk_id=".$latest_news['faqdesk_id'])  . "</link>\n";
print "</item>\n\n";

	$row++;
}
/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
