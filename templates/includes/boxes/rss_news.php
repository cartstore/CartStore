<!-- rss_news -->



<?php
  include(DIR_WS_CLASSES . '/' . FILENAME_LAST_RSS);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_RSS_NEWS);
  new infoBoxHeading($info_box_contents, false, false);
  $info_box_contents = array();

  $rss = new lastRSS;

  $rss->cache_dir = './rsscache';
  $rss->cache_time = 3600;
?>
<span class="nav-header"><i class="fa fa-rss"></i> RSS</span>

<div class="list-group">
<?php
  if ($rs = $rss->get(AZER_RSSNEWS_URL)) {

      foreach ($rs['items'] as $item) {
          echo "<a class=\"list-group-item\" href=\"$item[link]\" target=\"_blank\" > <p class=\"list-group-item-text\">" . $item['title'] . "</p></a>\n";
      }
  }
?>
</div>
  
<!-- rss_news_eof //-->