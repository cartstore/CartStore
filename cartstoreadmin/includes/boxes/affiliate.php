<?php
/*
  $Id: affiliate.php,v 2.00 2003/10/12

  

  Contribution based on:

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
?>
<!-- affiliates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                     'link'  => tep_href_link(FILENAME_AFFILIATE_SUMMARY, 'selected_box=affiliate'));

  if ($selected_box == 'affiliate') {
    $contents[] = array('text'  => 
	
	 tep_admin_files_boxes(FILENAME_AFFILIATE_SUMMARY, BOX_AFFILIATE_SUMMARY) .
	 
	  tep_admin_files_boxes(FILENAME_AFFILIATE, BOX_AFFILIATE) .
	  
	   tep_admin_files_boxes(FILENAME_AFFILIATE_PAYMENT, BOX_AFFILIATE_PAYMENT) .
	   
	    tep_admin_files_boxes(FILENAME_AFFILIATE_SALES, BOX_AFFILIATE_SALES) .
		
		 tep_admin_files_boxes(FILENAME_AFFILIATE_CLICKS, BOX_AFFILIATE_CLICKS) .
		 
		  tep_admin_files_boxes(FILENAME_AFFILIATE_BANNER_MANAGER, BOX_AFFILIATE_BANNERS) .
		  
		   tep_admin_files_boxes(FILENAME_AFFILIATE_NEWS, BOX_AFFILIATE_NEWS) .
		   
		   
		    tep_admin_files_boxes(FILENAME_AFFILIATE_NEWSLETTERS, BOX_AFFILIATE_NEWSLETTER_MANAGER) .
			
			 tep_admin_files_boxes(FILENAME_AFFILIATE_CONTACT, BOX_AFFILIATE_CONTACT));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliates_eof //-->