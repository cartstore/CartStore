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
?>  
<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
 <?php
 if ($listing_split->number_of_rows > 0) {
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
          switch ($column_list[$col]) {
            case 'LINK_LIST_TITLE':
              if (LINKS_TITLES_AS_LINKS == 'True')
                echo '<tr><td height="10">&nbsp;</td></tr><tr><td class="linkListingMain"><b>',$listing['links_title'].'</b></td></tr>';
              else
                echo '<tr><td height="10">&nbsp;</td></tr><tr><td class="linkListingMain"><b><a class="linkListingMain" href="' . tep_get_links_url($listing['links_id']) . '" target="_blank" title="' . $listing['links_title'] . '">' . $listing['links_title'] . '</a></b></td></tr>';
              break;
            case 'LINK_LIST_URL':
              echo '<tr><td class="linkListingMain"><a class="linkListingMain" href="' . tep_get_links_url($listing['links_id']) . '" target="_blank" title="' . $listing['links_title'] . '">' . $listing['links_url'] . '</a></td></tr>';
              break;
            case 'LINK_LIST_DESCRIPTION':
              echo '<tr><td class="linkListingMain">' .$listing['links_description'] . '</td></tr>';
              break;
            case 'LINK_LIST_IMAGE':
              if (tep_not_null($listing['links_image_url'])) {
                echo '<tr><td class="main"><a class="linkListingMain" href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_links_image($listing['links_image_url'], $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a></td></tr>';
              } else {
                echo '<tr><td class="linkListingMain"><a class="linkListingMain" href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, 'style="border: 3px double black"') . '</a></td></tr>';
              }        
              break;
            case 'LINK_LIST_COUNT':
              echo '<tr><td class="linkListingMain">'. $listing['links_clicked'].'</td></tr>';        
              break;
        }
      }
    }
  }
?>
  </td>
 </tr>
</table> 

 
<?php  
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
   <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_LINKS_DISPLAY, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
