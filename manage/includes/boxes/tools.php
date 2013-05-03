<?php
/*
  $Id: tools.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'));

  if ($selected_box == 'tools') {
    $contents[] = array('text'  => 
	 tep_admin_files_boxes(FILENAME_BACKUP, BOX_TOOLS_BACKUP) .
	 tep_admin_files_boxes(FILENAME_GOOGLE_SITEMAP, BOX_GOOGLE_SITEMAP) .
	 
	 tep_admin_files_boxes(FILENAME_BANNER_MANAGER, BOX_TOOLS_BANNER_MANAGER) .
	 
	 tep_admin_files_boxes(FILENAME_CACHE, BOX_TOOLS_CACHE) .
	 
	  tep_admin_files_boxes(FILENAME_DEFINE_LANGUAGE, BOX_TOOLS_DEFINE_LANGUAGE) .
	  
	  tep_admin_files_boxes(FILENAME_FILE_MANAGER, BOX_TOOLS_FILE_MANAGER) .
	  
	  tep_admin_files_boxes(FILENAME_MAIL, BOX_TOOLS_MAIL) .
	  
	  tep_admin_files_boxes(FILENAME_SEO_ASSISTANT, BOX_TOOLS_SEO_ASSISTANT) .
	  
	   tep_admin_files_boxes(FILENAME_NEWSLETTERS, BOX_TOOLS_NEWSLETTER_MANAGER) .
	   
	    tep_admin_files_boxes(FILENAME_SERVER_INFO, BOX_TOOLS_SERVER_INFO) .
		tep_admin_files_boxes(FILENAME_EVENTS_MANAGER, BOX_TOOLS_EVENTS_MANAGER ).
	 tep_admin_files_boxes(FILENAME_WHOS_ONLINE, BOX_TOOLS_WHOS_ONLINE));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
