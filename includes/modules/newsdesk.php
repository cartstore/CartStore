<div class="module-product">
  <div class="mbottom">
    <div class="mTop">
      <h3>Featured Articles</h3>
      
<?php

// set application wide parameters
// this query set is for NewsDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}

$newsdesk_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, pd.newsdesk_article_url_name, 
 p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed, 
 p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' 
 pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 and p.newsdesk_sticky = 0 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_NEWS);

if (!tep_db_num_rows($newsdesk_var_query)) { // there is no news
	echo '' . TEXT_NO_NEWSDESK_NEWS . '';

} else {

	$info_box_contents = array();
	$info_box_contents[] = array('align' => '',
                                 'text'  => TABLE_HEADING_NEWSDESK);
	new contentBoxHeading($info_box_contents);

	$info_box_contents = array();
	$row = 0;
	while ($newsdesk_var = tep_db_fetch_array($newsdesk_var_query)) {


if ( DISPLAY_NEWSDESK_IMAGE ) {
if ($newsdesk_var['newsdesk_image'] != '') {
$insert_image = '


		
';
}
}

if ( DISPLAY_NEWSDESK_IMAGE_TWO ) {
if ($newsdesk_var['newsdesk_image_two'] != '') {
$insert_image_two = '


		
';
}
}

if ( DISPLAY_NEWSDESK_IMAGE_THREE ) {
if ($newsdesk_var['newsdesk_image_three'] != '') {
$insert_image_three = '


		
';
}
}


if ( DISPLAY_NEWSDESK_VIEWCOUNT ) {
$insert_viewcount = '<i>' . TEXT_NEWSDESK_VIEWED . $newsdesk_var['newsdesk_article_viewed'] . '</i>';
}

if ( DISPLAY_NEWSDESK_READMORE ) {
$insert_readmore = '<a class="readon" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . TEXT_NEWSDESK_READMORE . 
'</a><div class="clear"></div></div></div>';
}

if ( DISPLAY_NEWSDESK_SUMMARY ) {
$insert_summary = ''. $newsdesk_var['newsdesk_article_shorttext'] . '';
}

if ( DISPLAY_NEWSDESK_HEADLINE ) {
$insert_headline = '<div class="articleWrap"><div class="article"><h5><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . $newsdesk_var['newsdesk_article_name'] . '</a></h5>' . $insert_image . '';
}

if ( DISPLAY_NEWSDESK_DATE ) {
$insert_date = '- <span class="newsdate">' . tep_date_long($newsdesk_var['newsdesk_date_added']) . '</span>';
}


		$info_box_contents[$row] = array(
			'align' => '',
			'params' => '',
			'text' => '

 ' . $insert_headline . $insert_date . '
	 ' . $insert_viewcount . '
		
		' . $insert_summary . '
		
		' . $insert_readmore . '

 
' . $insert_image_two . '
' . $insert_image_three . '

		 
'

		);
$insert_image = '';
$insert_image_two = '';
$insert_image_three = '';
		$row++;
	}

	new contentBox($info_box_contents);
 echo "<div class=\"clear\"> </div></div>";
}
?>
      <!-- newsdesk_eof //-->
      <?php
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
  </div>
</div>
