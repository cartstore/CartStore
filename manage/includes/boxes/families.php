<?php 
/*
  $Id: families.php,v 3.0 2003/09/01 19:54:53 blueline Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!--
 
          <tr>
            <td>
 <?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_FAMILIES,
                     'link'  => tep_href_link(FILENAME_MODIFY_FAMILIES, 'f_Id=1&selected_box=families'));

  if ($selected_box == 'families') {
    $contents[] = array('text'  =>
	
	tep_admin_files_boxes(FILENAME_VIEW_FAMILIES, BOX_FAMILIES_VIEW_FAMILIES) .
	
	tep_admin_files_boxes(FILENAME_MODIFY_FAMILIES, BOX_FAMILIES_MODIFY_FAMILIES) .
	
	tep_admin_files_boxes(FILENAME_SELECT_FAMILY_DISPLAY, BOX_FAMILIES_SELECT_DISPLAY) .
	
	tep_admin_files_boxes(FILENAME_ASSIGN_FAMILIES, BOX_FAMILIES_ASSIGN_FAMILIES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr> -->
<!-- families_eof //-->
