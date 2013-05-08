<?php
/*
  $Id: customers.php,v 1.16 2003/07/09 01:18:53 hpdl Exp $
  adapted for Separate Pricing Per Customer v4.0 2005/01/28


  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'));

  if ($selected_box == 'customers') {
    $contents[] = array('text'  => 
	
	tep_admin_files_boxes('dealer_admin.php', 'Dealers Admin') .
	
	 tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS) .
	 
	 tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS, BOX_CUSTOMERS_POINTS) .
	 tep_admin_files_boxes('customers_groups.php', BOX_CUSTOMERS_GROUPS) .
	 
	 tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_PENDING, BOX_CUSTOMERS_POINTS_PENDING) .
	 
	// tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_REFERRAL, BOX_CUSTOMERS_POINTS_REFERRAL) .
	 
	 tep_admin_files_boxes(FILENAME_CREATE_ACCOUNT, BOX_MANUAL_ORDER_CREATE_ACCOUNT) .
	 
	 tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER) .
	 tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
