<!-- rss_news -->

<div class="module">
<div>
<div>
<div>
<h3>RSS</h3>
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
<ul>
<?php
  if ($rs = $rss->get(AZER_RSSNEWS_URL)) {

      foreach ($rs['items'] as $item) {
          echo "<li><a href=\"$item[link]\" target=\"_blank\" > <span>" . $item['title'] . "</span></a></li>\n";
      }
  }
?>
</ul>
<div style="text-align: center">
  <?php
  echo '' . tep_image(DIR_WS_IMAGES . 'xml_rss2.png', 'RSS') . '';
?>
</div>
</div>
</div>
</div>
</div>
<!-- rss_news_eof //-->