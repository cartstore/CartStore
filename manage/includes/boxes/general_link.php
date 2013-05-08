<?php
/*
  $Id: links.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- links //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => 'Home Page Editor',
                     'link'  => tep_href_link('homepagecontent.php', 'selected_box=hpedit'));

  if ($selected_box == 'hpedit') {
    $contents[] = array('text'  => 
	 tep_admin_files_boxes('homepagecontent.php', "Home Page Editor Area 1") .
	 tep_admin_files_boxes('homepagecontent2.php', "Home Page Editor Area 2") .
	 tep_admin_files_boxes('homepagecontent3.php', "Home Page Editor Area 3") .
	  tep_admin_files_boxes('imageupload.php', "Upload General Use Images"));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- links_eof //-->
