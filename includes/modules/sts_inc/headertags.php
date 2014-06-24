<?php
/*
  $Id: headertags.php,v 3.0 2005/02/12 23:55:58 rigadin Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
v3.0 by Rigadin (rigadin@osc-help.net)
*/
  $sts->start_capture();
  if(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1)=='articles.php' || substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1)=='article_info.php')
  {
    if ( file_exists(DIR_WS_INCLUDES . 'article_header_tags.php') ) {
    include_once (DIR_WS_FUNCTIONS . 'clean_html_comments.php');
    include_once(DIR_WS_FUNCTIONS . 'header_tags.php');
    include(DIR_WS_INCLUDES . 'article_header_tags.php');
	 
  }
  }elseif(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1)=='newsdesk_info.php' || substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1)=='newsdesk_index.php')
  {
    if ( file_exists(DIR_WS_INCLUDES . 'newsdesk_header_tags.php') ) {
    include_once (DIR_WS_FUNCTIONS . 'clean_html_comments.php');
    include_once(DIR_WS_FUNCTIONS . 'header_tags.php');
    include(DIR_WS_INCLUDES . 'newsdesk_header_tags.php');
	 
  }
  }else
  {
  if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
    include_once (DIR_WS_FUNCTIONS . 'clean_html_comments.php');
    include_once(DIR_WS_FUNCTIONS . 'header_tags.php');
    include(DIR_WS_INCLUDES . 'header_tags.php');
	 
  }
  } 
  $sts->stop_capture('headertags');

?>
