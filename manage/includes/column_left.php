
<?php
/*
  $Id: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
if (tep_admin_check_boxes('administrator.php') == true) {
    require(DIR_WS_BOXES . 'administrator.php');
  }
  if (tep_admin_check_boxes('configuration.php') == true) {
    require(DIR_WS_BOXES . 'configuration.php');
  }

 if (tep_admin_check_boxes('catalog.php') == true) {
    require(DIR_WS_BOXES . 'catalog.php');
  }

   // MVS Start
    if (tep_admin_check_boxes('vendors.php') == true) {
    require(DIR_WS_BOXES . 'vendors.php');
  }

  // MVS End
      if (tep_admin_check_boxes('articles.php') == true) {
    require(DIR_WS_BOXES . 'articles.php');
  }

// Begin newsdesk
   if (tep_admin_check_boxes('newsdesk.php') == true) {
  require(DIR_WS_BOXES . 'newsdesk.php');
  }

// End newsdesk
  
 //  if (tep_admin_check_boxes('faqdesk.php') == true) {
//   require(DIR_WS_BOXES . 'faqdesk.php');
//  }
//Family products: Begin Changed code
 //     if (tep_admin_check_boxes('families.php') == true) {
 //   require(DIR_WS_BOXES . 'families.php');
 // }

//Family: End Changed code
      if (tep_admin_check_boxes('modules.php') == true) {
    require(DIR_WS_BOXES . 'modules.php');
  }

      if (tep_admin_check_boxes('customers.php') == true) {
    require(DIR_WS_BOXES . 'customers.php');
  }

// Start - CREDIT CLASS Gift Voucher Contribution
      if (tep_admin_check_boxes('gv_admin.php') == true) {
        require(DIR_WS_BOXES . 'gv_admin.php');
	  }
// End - CREDIT CLASS Gift Voucher Contribution


     if (tep_admin_check_boxes('taxes.php') == true) {
    require(DIR_WS_BOXES . 'taxes.php');
  }

if (tep_admin_check_boxes('localization.php') == true) {
    require(DIR_WS_BOXES . 'localization.php');
  }

if (tep_admin_check_boxes('reports.php') == true) {
    require(DIR_WS_BOXES . 'reports.php');
  }
if (tep_admin_check_boxes('affiliate.php') == true) {
    require(DIR_WS_BOXES . 'affiliate.php');
  }
if (tep_admin_check_boxes('tools.php') == true) {
    require(DIR_WS_BOXES . 'tools.php');
  }
//if (tep_admin_check_boxes('storefeed.php') == true) {
 //   require(DIR_WS_BOXES . 'storefeed.php');
 // }
if (tep_admin_check_boxes('header_tags_controller.php') == true) {
    require(DIR_WS_BOXES . 'header_tags_controller.php');
  }

if (tep_admin_check_boxes('gv_admin.php') == true) {
    require(DIR_WS_BOXES . 'gv_admin.php');
  }



// VJ Links Manager v1.00 end
  if (tep_admin_check_boxes('general_link.php') == true) {
    require(DIR_WS_BOXES . 'general_link.php');
  }



?>
