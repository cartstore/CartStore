<?php
/*
  $Id: affiliate.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=affiliate'));

  if ($selected_box == 'affiliate') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_SUMMARY . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_PAYMENT . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_SALES . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_CLICKS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_BANNERS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_NEWS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_NEWSLETTER_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_CONTACT . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliates_eof //-->