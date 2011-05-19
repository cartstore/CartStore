<?php
  
  if ($sts->display_template_output) {
      include DIR_WS_MODULES . 'sts_inc/sts_column_left.php';
  } else {
      
      if ((USE_CACHE == 'true') && empty($SID)) {
          echo tep_cache_categories_box();
      } else {
          include(DIR_WS_BOXES . 'categories.php');
      }
      
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
      if (AUTHOR_BOX_DISPLAY == 'true') {
          require(DIR_WS_BOXES . 'authors.php');
      }
      if (ARTICLE_BOX_DISPLAY == 'true') {
          require(DIR_WS_BOXES . 'articles.php');
      }
      if (SHOW_RSS_NEWS == 'true')
          if (basename($PHP_SELF) != 'rss_reader.php')
              include(DIR_WS_BOXES . 'rss_news.php');
      
  }
  
?>