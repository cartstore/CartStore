<?php
/*
  $Id: storefeed.php,v 1.00 2004/09/07

  Store Data Feed admin box

  Contribution based on:

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
  
  Contribution created by: Chemo
*/
?>
<!-- feed //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => 'Store Feeds',
                     'link'  => tep_href_link('feeders.php', 'selected_box=feeds'));

  if ($selected_box == 'feeds') {
    $contents[] = array('text'  =>
	
	 tep_admin_files_boxes('froogle.php', 'Froogle') .
	 
	 tep_admin_files_boxes('yahoo.php', 'Yahoo') .
	 tep_admin_files_boxes('bizrate.php', 'Bizrate'));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- feed_eof //-->