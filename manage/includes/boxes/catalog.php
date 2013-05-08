<?php
/*
  $Id: catalog.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));

  if ($selected_box == 'catalog') {
    $contents[] = array('text'  =>
	tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS) .
	

	   // KIKOLEPPARD for multilanguage support Line Added New Atrributes Manager
      	tep_admin_files_boxes('new_attributes.php', BOX_CATALOG_CATEGORIES_ATTRIBUTE_MANAGER) .
								   // KIKOLEPPARD for multilanguage support Line Added New Atrributes Manager
		tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
                                  
		tep_admin_files_boxes('easypopulate.php', 'Easy Populate') .
		
		tep_admin_files_boxes(FILENAME_PRODUCTS_EXTRA_IMAGES, BOX_CATALOG_CATEGORIES_PRODUCTS_EXTRA_IMAGES) .						   
		tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS) .		
								  
         tep_admin_files_boxes(FILENAME_OPTIONS_IMAGES, BOX_CATALOG_OPTIONS_IMAGES) .                         
		 
		 tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS) .      
		 
		  tep_admin_files_boxes(FILENAME_SPECIALS, BOX_CATALOG_SPECIALS) .
		  
		   tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS) .
		   
		   tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED) .
		      tep_admin_files_boxes(FILENAME_QBI, 'QBI') .
		    tep_admin_files_boxes(FILENAME_PRODUCTS_EXTRA_FIELDS,  'Product Extra Fields'));
    // END: Product Extra Fields

  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
