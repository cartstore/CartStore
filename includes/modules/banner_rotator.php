<?php
/*
  $Id: banner_rotator.php 1.1 20100628 Kymation $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/


  $banner_query_raw = "
    select
      banners_id,
      banners_url,
      banners_image,
      banners_html_text
    from
      " . TABLE_BANNERS . "
    where
      banners_group = '" . BANNER_ROTATOR_GROUP . "'
      and language_id = '" . ( int )$languages_id . "'
      and status = 1
    order by
      " . BANNER_ORDER . "
    limit
      " . MAX_DISPLAY_BANNER_ROTATOR
  ;
  // print $banner_query_raw; exit();
  $banner_query = tep_db_query( $banner_query_raw );
  if( tep_db_num_rows( $banner_query ) > 0 ) {

?>
<!-- banner_rotator -->
  <div id="bannerRotator">
    <ul class="slides">
<?php
    while( $banner = tep_db_fetch_array( $banner_query ) ) {
      echo '      <li>';
      if( $banner['banners_url'] != '' ) {
        echo '<a href="' . tep_href_link( FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_blank">';
      }
      echo tep_image( DIR_WS_IMAGES . $banner['banners_image'], $banner['banners_html_text'] );
      if( $banner['banners_url'] != '' ) {
        echo '</a>';
      }
      echo '</li>';

      tep_update_banner_display_count( $banner['banners_id'] );
    }
?>
    </ul>
    <div id="bannerNav"></div>
  </div>
  <div class="divider-tall"></div>
<!-- banner_rotator_EOF -->
<?php
  }
?>