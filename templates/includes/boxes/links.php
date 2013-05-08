<?php
  $link_featured_query = tep_db_query("select link_title,link_url,link_description,link_codes,link_found, links_image from links where link_state=1 order by links_id");
  $link_result = tep_db_query("select * from links  order by links_id");
?>
<!-- links.php //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>LINKS</h3>
        <ul>
          <?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_LINKS);
  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_LINKS));
  $info_box_contents = array();
  while ($link = tep_db_fetch_array($link_featured_query)) {
      $info_box_contents[] = array('align' => '', 'text' => '<li><a class="featuredlinksBox" href="' . $link['link_url'] . '" target="_' . $openMode . '" title="' . $link['link_title'] . '"><img style="display: inline-block;"  src="http://open.thumbshots.org/image.pxf?url=' . $link['link_url'] . '" width="' . BOX_WIDTH . '" alt="' . $link['link_title'] . '"><br>' . $link['link_title'] . '</a></li>');
  }
  new infoBox($info_box_contents);
?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- links.php_eof //-->