<?php
/*
  $Id: tools.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MAIL_MANAGER,
                     'link'  => tep_href_link(FILENAME_MM_RESPONSEMAIL, 'selected_box=mailmanager'));

  if ($selected_box == 'mailmanager') {
    $contents[] = array('text'  => 
    							   '<a href="' . tep_href_link(FILENAME_MM_RESPONSEMAIL) . '" class="menuBoxContentLink">' . BOX_MM_RESPONSEMAIL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_BULKMAIL) . '" class="menuBoxContentLink">' . BOX_MM_BULKMAIL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_TEMPLATES) . '" class="menuBoxContentLink">' . BOX_MM_TEMPLATES . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MM_EMAIL) . '" class="menuBoxContentLink">' . BOX_MM_EMAIL . '</a><br>' 				   
           						   );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
