<?php
/*
  $Id: shop_by_price.php,v 2.5 2008/03/07 $

  Contribution by Meltus  http://www.highbarn-consulting.com
  Adapted for OsCommerce MS2 by Sylvio Ruiz suporte@leilodata.com
  Modified by Hugues Deriau on 09/23/2006 - display the price ranges in the selected currency
  Modified by Glassraven for dropdown list 24/10/2006 www.glassraven.com
  Modified by -GuiGui- (http://www.gpuzin.com) - 07/03/2008 - fix the title and work with the contribution " Product Listing in Columns"

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$sel_currency = array();

$price_ranges = Array( 	"Under " .  $currencies->format(10) ,
						"From " . $currencies->format(10) . " to " . $currencies->format(20),
						"From " . $currencies->format(20). " to " . $currencies->format(30),
						"From " . $currencies->format(30). " to " . $currencies->format(50),
						"Above " . $currencies->format(50),
						"Show all price ranges");

$price_min = Array(  0,
                    10,
					20,
					30,
					50,
					0);

$price_max = Array( 10,
                    20,
					30,
					50,
					0,
					0);

define('BOX_HEADING_SHOP_BY_PRICE', 'Shop By Price');

if (strstr($_SERVER['SCRIPT_NAME'],'shop_by_price')){
 if (isset($range) &&  isset($price_ranges[$range]) )
	define('HEADING_TITLE', 'Shop by Price - ' . $price_ranges[$range]);
 else
	define('HEADING_TITLE', 'Shop by Price');
define('NAVBAR_TITLE', 'Shop by Price');
define('TABLE_HEADING_BUY_NOW', 'Buy Now!');
define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_PRODUCTS', 'Product Name');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_WEIGHT', 'Weight');
// Product Listing in Columns - Start (You can remove those 3 lines if you are not using it).
define('TABLE_HEADING_MULTIPLE', 'Qty: ');
// Product Listing in Columns - End
define('TEXT_NO_PRODUCTS', '<p align="center"><br>Sorry no products currently available in this price range.</p>');
}
?>