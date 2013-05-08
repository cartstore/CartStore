<?php
/*
 $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- returns //-->
          <tr>
            <td>
<?php

  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_RETURNS_HEADING,
                     'link'  => tep_href_link(FILENAME_RETURNS, 'selected_box=returns'));

  if ($selected_box == 'returns') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_RETURNS) . '" class="menuBoxContentLink">' . BOX_RETURNS_MAIN . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_RETURNS_REASONS) . '" class="menuBoxContentLink">' . BOX_RETURNS_REASONS . '</a><BR>' .
                                   '<a href="' . tep_href_link(FILENAME_REFUND_METHODS) . '" class="menuBoxContentLink">' . BOX_HEADING_REFUNDS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_RETURNS_STATUS) . '" class="menuBoxContentLink">' . BOX_RETURNS_STATUS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_RETURNS_TEXT) . '" class="menuBoxContentLink">' . BOX_RETURNS_TEXT . '</a><br>'

                                   );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- returns_eof //-->
