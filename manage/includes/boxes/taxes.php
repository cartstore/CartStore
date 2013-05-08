<?php
/*
  $Id: taxes.php,v 1.17 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- taxes //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                     'link'  => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'));

  if ($selected_box == 'taxes') {
    $contents[] = array('text'  =>
	
	 tep_admin_files_boxes(FILENAME_COUNTRIES, BOX_TAXES_COUNTRIES) .
	 
	 tep_admin_files_boxes(FILENAME_ZONES, BOX_TAXES_ZONES) .
	 
	 
	 tep_admin_files_boxes(FILENAME_GEO_ZONES, BOX_TAXES_GEO_ZONES) .
	 
	 tep_admin_files_boxes(FILENAME_TAX_CLASSES, BOX_TAXES_TAX_CLASSES) .
	 
	 tep_admin_files_boxes(FILENAME_TAX_RATES, BOX_TAXES_TAX_RATES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- taxes_eof //-->
