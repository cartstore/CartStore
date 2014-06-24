<?php
/*
  $Id: column_left.php,v 1.15 2003/07/01 14:34:54 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

// START STS 4.1

if ($sts->display_template_output) {
  include DIR_WS_MODULES.'sts_inc/sts_column_left.php';
} else {
//END STS 4.1
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }

//year make model
require(DIR_WS_BOXES . 'year_make_model.php');


  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }

  require(DIR_WS_BOXES . 'affiliate.php');
  require(DIR_WS_BOXES . 'whats_new.php');
  require(DIR_WS_BOXES . 'search.php');
  require(DIR_WS_BOXES . 'information.php');

  if (AUTHOR_BOX_DISPLAY == 'true'){
    require(DIR_WS_BOXES . 'authors.php');
  }
  if (ARTICLE_BOX_DISPLAY == 'true'){
    require(DIR_WS_BOXES . 'articles.php');
  }
  
  require(DIR_WS_BOXES . 'sociallogin.php');
  
     if (SHOW_RSS_NEWS == 'true')
    if (basename($PHP_SELF) != 'rss_reader.php')
      include(DIR_WS_BOXES . 'rss_news.php');
// START STS 4.1
}
// END STS 4.1
?>