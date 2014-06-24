<?php
/*
  $Id: reports.php,v 1.5 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));

  if ($selected_box == 'reports') {
    $contents[] = array('text'  => 
	'<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT2, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_REPORTS_SALES_REPORT2 . '</a><br>' . 
	
	 tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED) .
	 
	 tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED) .
	 
	 
	 tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS, BOX_REPORTS_ORDERS_TOTAL) .
	 
	 tep_admin_files_boxes(FILENAME_STATS_LOW_STOCK_ATTRIB, BOX_REPORTS_STATS_LOW_STOCK_ATTRIB) .
	 tep_admin_files_boxes(FILENAME_SUPERTRACKER,  BOX_REPORTS_SUPERTRACKER ) .	 
	 tep_admin_files_boxes(FILENAME_STATS_MONTHLY_SALES, BOX_REPORTS_MONTHLY_SALES) .
// Start - CREDIT CLASS Gift Voucher Contribution
	 tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS,BOX_REPORTS_ORDERS_TOTAL) . 
	 tep_admin_files_boxes(FILENAME_STATS_CREDITS,BOX_REPORTS_CREDITS) .
// End - CREDIT CLASS Gift Voucher Contribution	 
	tep_admin_files_boxes(FILENAME_WA_TAXES_REPORT, BOX_REPORTS_WA_TAXES_REPORT)

	 );
								   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
  

?>
            </td>
          </tr>
<!-- reports_eof //-->
