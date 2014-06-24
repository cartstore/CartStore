<?php
/*
  $Id: current_auctions.php,v 2.0 2004/09/15 17:37:59 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CURRENT_AUCTIONS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CURRENT_AUCTIONS));

switch (AUCTION_SORT) {
  case '1':
    $sort_text = SORT_TEXT_ITEM;
  break;
  case '2':
    $sort_text = SORT_TEXT_START;
  break;
  case '3':
    $sort_text = SORT_TEXT_END;
  break;
  case '4':
    $sort_text = SORT_TEXT_LOWEST;
  break;
  case '8':
    $sort_text = SORT_TEXT_NEWEST;
  break;
}

 require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
	
        <td class="main"><?php echo OPENING; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . "current_auctions_module.php"); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="smallText" align="center"><?php echo SORTED_BY . ': ' . $sort_text . '&nbsp;&nbsp;&nbsp;&nbsp;' . SHOWING . ': ' . AUCTION_DISPLAY . ' ' . SHOWING_ITEMS; ?></td>
      </tr>
    </table></td>


<?php require(DIR_WS_INCLUDES . 'column_right.php');
 require(DIR_WS_INCLUDES . 'footer.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
