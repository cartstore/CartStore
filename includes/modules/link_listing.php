<?php
/*
  $Id: link_listing.php,v 1.00 2003/10/03 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class linkListingBox extends tableBox {
    function linkListingBox($contents) {
      $this->table_parameters = 'class="linkListing"';
      $this->tableBox($contents, true);
    }
  }

  function tep_create_sort_link_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="linkListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

  $listing_split = new splitPageResults($listing_sql, MAX_LINKS_DISPLAY, 'l.links_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_LINKS_DISPLAY, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }

  $list_box_contents = array();

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'LINK_LIST_TITLE':
        $lc_text = TABLE_HEADING_LINKS_TITLE;
        $lc_align = '';
        break;
      case 'LINK_LIST_URL':
        $lc_text = TABLE_HEADING_LINKS_URL;
        $lc_align = '';
        break;
      case 'LINK_LIST_IMAGE':
        $lc_text = TABLE_HEADING_LINKS_IMAGE;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_DESCRIPTION':
        $lc_text = TABLE_HEADING_LINKS_DESCRIPTION;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_COUNT':
        $lc_text = TABLE_HEADING_LINKS_COUNT;
        $lc_align = '';
        break;
    }

    if ($column_list[$col] != 'LINK_LIST_IMAGE') {
      $lc_text = tep_create_sort_link_heading($_GET['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="linkListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="linkListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="linkListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;
      $openMode = (LINKS_OPEN_NEW_PAGE == 'True') ? 'blank' : 'self';
      
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'LINK_LIST_TITLE':
            $lc_align = '';
            if (LINKS_TITLES_AS_LINKS == 'True')
              $lc_text = $listing['links_title'];
            else
              $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) . '" target="_' . $openMode . '" title="' . $listing['links_title'] . '">' . $listing['links_title'] . '</a>';
            break;
          case 'LINK_LIST_URL':
            $lc_align = '';
            $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) .  '" target="_' . $openMode . '" title="' . $listing['links_title'] . '">' . $listing['links_url'] . '</a>';
            break;
          case 'LINK_LIST_DESCRIPTION':
            $lc_align = '';
            $lc_text = $listing['links_description'];
            break;
          case 'LINK_LIST_IMAGE':
            $lc_align = 'center';
            if (tep_not_null($listing['links_image_url'])) {
              $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) .  '" target="_' . $openMode . '">' . tep_links_image($listing['links_image_url'], $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) .  '" target="_' . $openMode . '">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, 'style="border: 3px double black"') . '</a>';
            }
            break;
          case 'LINK_LIST_COUNT':
            $lc_align = '';
            $lc_text = $listing['links_clicked'];
            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="linkListing-data"',
                                               'text'  => $lc_text);
      }
    }

    new linkListingBox($list_box_contents);
  } else {
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="linkListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="linkListing-data"',
                                   'text' => TEXT_NO_LINKS);

    new linkListingBox($list_box_contents);
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_LINKS_DISPLAY, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
