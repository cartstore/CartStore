<?php
/*
  $Id: header_tags_controller.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- header_tags_controller //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_HEADER_TAGS_CONTROLLER,
                     'link'  => tep_href_link(FILENAME_HEADER_TAGS_CONTROLLER, 'selected_box=header tags'));

  if ($selected_box == 'header tags') {
    $contents[] = array('text'  => 
	
	 tep_admin_files_boxes(FILENAME_HEADER_TAGS_CONTROLLER, BOX_HEADER_TAGS_ADD_A_PAGE) .
	 
	 tep_admin_files_boxes(FILENAME_HEADER_TAGS_ENGLISH, BOX_HEADER_TAGS_ENGLISH) .
	 
	 tep_admin_files_boxes(FILENAME_HEADER_TAGS_FILL_TAGS, BOX_HEADER_TAGS_FILL_TAGS));
 
                                   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- header_tags_controller_eof //-->
