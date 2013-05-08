<?php
/*
  $Id: vendors.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com



  GNU General Public License Compatible
*/
?>
<!-- vendors //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => "Vendors",
                     'link'  => tep_href_link(FILENAME_VENDORS, 'selected_box=vendors'));

  if ($selected_box == 'vendors') {
   $contents[] = array('text'  => 
   
    tep_admin_files_boxes(FILENAME_VENDORS, BOX_VENDORS) .
	
	 tep_admin_files_boxes(FILENAME_PRODS_VENDORS, BOX_VENDORS_REPORTS_PROD) .
	 
	  tep_admin_files_boxes(FILENAME_ORDERS_VENDORS, BOX_VENDORS_ORDERS) .
	  
	   tep_admin_files_boxes(FILENAME_MOVE_VENDORS, BOX_MOVE_VENDOR_PRODS));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->