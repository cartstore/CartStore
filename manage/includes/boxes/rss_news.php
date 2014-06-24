<?php
/*
  $Id: rss_news.php,v 1.00 2003/10/02 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- rss news_bof //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_RSS_NEWS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=rss_news'));

  if ($selected_box == 'rss_news') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_RSS_NEWS_CREATE) . '" class="menuBoxContentLink">' . BOX_RSS_NEWS_CREATE . '</a><br>' );
  }
	
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- rss_news_eof //-->
