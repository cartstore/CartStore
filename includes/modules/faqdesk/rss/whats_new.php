<?php
/*
  $Id$

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2002-2003 osCommerce
  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Author : Rodolphe Quiedeville <rodolphe@quiedeville.org>

  GNU General Public License Compatible
*/

if ($random_product = tep_random_select("select products_id, products_image, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where products_status='1' order by products_date_added desc limit " . MAX_RANDOM_SELECT_NEW))
{
  $random_product['products_name'] = tep_get_products_name($random_product['products_id']);
  $random_product['specials_new_products_price'] = tep_get_products_special_price($random_product['products_id']);
    

  $whats_new_price =  $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
  

  if ($random_product['specials_new_products_price'])
    {

      $whats_new_price_special = $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
    }

}

print '    <item>' . "\n";

print '      <title>' . htmlspecialchars($random_product['products_name']) . '</title>'. "\n";

print '      <price>' . $whats_new_price . '</price>'. "\n";

if ($random_product['specials_new_products_price'])
{
  print '      <specialprice>' . $whats_new_price_special . '</specialprice>' . "\n";
}

print '      <link>' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], NONSSL) . '</link>' . "\n";

print '      <img>' . HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES . $random_product['products_image'] . '</img>' . "\n";
			  

print '    </item>' . "\n";

/*

	CartStore eCommerce Software, for The Next Generation ---- http://www.cartstore.com
	Copyright (c) 2008 Adoovo Inc. USA	GNU General Public License Compatible

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			FAQDesk
	version:        		1.01.0
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>
