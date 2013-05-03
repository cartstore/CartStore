<?php
/*
  Copyright (C) 2009 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require_once(DIR_FS_CATALOG . 'googlecheckout/library/google_base_feed_builder.php');
require_once(DIR_FS_CATALOG . 'googlecheckout/library/google_sitemap_builder.php');

/**
 * Google Checkout v1.5.0
 * $Id$
 * 
 * We construct and execute a GoogleCronHook every time the merchant
 * visits the "orders" or "modules" page. This allows to simulate
 * a "cron" job for some calculations.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleCronHook {
	
  /**
   * Constructor.
   */
  function GoogleCronHook() {}
  
  /**
   * Add hooks here.
   */
  function execute() {
    global $languages_id;
    
    // Update Google Base (Product Search) feed.
    $google_base_feed_builder = new GoogleBaseFeedBuilder($languages_id);
    $file = fopen(DIR_FS_CATALOG . 'googlecheckout/feeds/products-static.xml', "w");
    if ($file) {
      fwrite($file, $google_base_feed_builder->get_xml());
      fclose($file);
    }
    
    // Update Site Map feed.
    $google_sitemap_builder = new GoogleSitemapBuilder();
    $file = fopen(DIR_FS_CATALOG . 'googlecheckout/feeds/sitemap-static.xml', "w");
    if ($file) {    
      fwrite($file, $google_sitemap_builder->get_xml());
      fclose($file);
    }
    
    // Record the time of the last update.
    $file = fopen(DIR_FS_CATALOG . 'googlecheckout/logs/last_updated.log', "w");
    if ($file) {
      fwrite($file, "Last updated: " . date("F j, Y, G:i a"));
      fclose($file);
    }
  }

}

?>
