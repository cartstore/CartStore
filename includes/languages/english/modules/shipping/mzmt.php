<?php
/*
  $Id: mzmt.php,v 1.000 2004-10-29 Josh Dechant Exp $

  Copyright (c) 2004 Josh Dechant

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*//*
  Create text & icons for geo zones and their tables following template below where
    $n = geo zone number (in the shipping module) and
    $j = table number

  MODULE_SHIPPING_MZMT_GEOZONE_$n_TEXT_TITLE
  MODULE_SHIPPING_MZMT_GEOZONE_$n_ICON
  MODULE_SHIPPING_MZMT_GEOZONE_$n_TABLE_$j_TEXT_WAY

  Sample is setup for a 3x3 table (3 Geo Zones with 3 Tables each)
*/

define('MODULE_SHIPPING_MZMT_TEXT_TITLE', 'MultiGeoZone MultiTable');
define('MODULE_SHIPPING_MZMT_TEXT_DESCRIPTION', 'Multiple geo zone shipping with multiple tables to each geo zone.');

define('MODULE_SHIPPING_MZMT_GEOZONE_1_TEXT_TITLE', 'United Parcel Service (UPS)');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_ICON', 'shipping_ups.gif');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_1_TEXT_WAY', 'Ground');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_2_TEXT_WAY', 'Second Day');
define('MODULE_SHIPPING_MZMT_GEOZONE_1_TABLE_3_TEXT_WAY', 'Next Day');

define('MODULE_SHIPPING_MZMT_GEOZONE_2_TEXT_TITLE', 'MultiGeoZone MultiTable2');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_1_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_2_TABLE_3_TEXT_WAY', '');

define('MODULE_SHIPPING_MZMT_GEOZONE_3_TEXT_TITLE', 'MultiGeoZone MultiTable3');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_ICON', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_1_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_2_TEXT_WAY', '');
define('MODULE_SHIPPING_MZMT_GEOZONE_3_TABLE_3_TEXT_WAY', '');
?>
