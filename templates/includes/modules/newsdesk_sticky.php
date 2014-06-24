<div id="bottom" class="clearfix">
			<div class="container">
					<div class="blogWrap clearfix"><!-- newsdesk //-->
 <?php

// set application wide parameters
// this query set is for NewsDesk


$configuration_query = tep_db_query ( "select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "" );
while ( $configuration = tep_db_fetch_array ( $configuration_query ) ) {
 if (!defined($configuration ['cfgKey']))
    define ( $configuration ['cfgKey'], $configuration ['cfgValue'] );
}

$newsdesk_var_query = tep_db_query ( 'select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext,
pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed,
p.newsdesk_date_available, p.newsdesk_status, p.newsdesk_sticky from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . '
pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1
and p.newsdesk_sticky = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_NEWS );

if (! tep_db_num_rows ( $newsdesk_var_query )) { // there is no news
	echo '<!-- ' . TEXT_NO_NEWSDESK_NEWS . ' -->';

} else {

	$row = 0;
	while ( $newsdesk_var = tep_db_fetch_array ( $newsdesk_var_query ) ) {

   $insert_headline = $insert_date = $insert_image = $insert_viewcount = $insert_image_two = $insert_image_three = $insert_content = '';
		if (STICKY_IMAGE) {
			if ($newsdesk_var ['newsdesk_image'] != '') {
				$insert_image = '



<a class="img-responsive" href="' . tep_href_link ( FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var ['newsdesk_id'] ) . '">' . tep_image ( DIR_WS_IMAGES . $newsdesk_var ['newsdesk_image'], '', '' ) . '</a>

';
			}
		}




		if (STICKY_NEWSDESK_READMORE) {
			$insert_readmore_s = '

<a class="read-more" href="' . tep_href_link ( FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var ['newsdesk_id'] ) . '">Read More</a> 

';
		}

		if (STICKY_ARTICLE_SHORTTEXT) {
			$insert_summary = '

	' . $newsdesk_var ['newsdesk_article_shorttext'] . '
';
		}

		if (STICKY_ARTICLE_NAME) {
			$insert_headline = '<a href="' . tep_href_link ( FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var ['newsdesk_id'] ) . '">' . $newsdesk_var ['newsdesk_article_name'] . '</a>';
		}

		if (STICKY_DATE_ADDED) {
			$insert_date = '- <span class="date">' . tep_date_long ( $newsdesk_var ['newsdesk_date_added'] ) . '</span>';
		}

		if (STICKY_ARTICLE_URL) {
			$insert_url = '
 ' . $newsdesk_var ['newsdesk_article_url'] . ' 
';
		}

		if (STICKY_ARTICLE_URL_NAME) {
			$insert_url = '
 ' . $newsdesk_var ['newsdesk_article_url_name'] . ' 
';
		}

		echo '
		 
		<div class="blog-item col-md-2 col-sm-2">
							<h3>' . $insert_headline . '</h3>
							<div class="item-image">' . $insert_image . '</div>
							  <div class="article_shorttext">' . $insert_summary . ' </div>
                                                            
							' . $insert_readmore_s . '
						</div>';

		$row ++;

		// WE need a better solution for my fudge code below ...
		$insert_image = '';
		$insert_image_two = '';
		$insert_image_three = '';

	}
}

?>
                                            
                                      </div>  </div></div><div class="clear"></div>
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