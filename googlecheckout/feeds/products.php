<?php
/*
  Copyright (C) 2008 Google Inc.

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

/**
 * Google Checkout v1.5.0
 * $Id: products.php 169 2009-02-17 21:53:06Z ed.davisson $
 * 
 * Generates a Google Base RSS feed.
 * 
 * If destination webserver supports the "AddType" .htaccess directive,
 * then "products.xml" in this same directory will generate the same
 * feed and meet the .xml file extension requirement.
 * 
 * Otherwise, this page generates a feed and copies it into
 * "products-static.xml" (also in this directory).
 */

// Require application_top.php to get access to configuration data.
chdir('./../..');
$curr_dir = getcwd(); 
require_once($curr_dir . '/includes/application_top.php'); 

// Require googlecheckout files.
require_once(DIR_FS_CATALOG . 'googlecheckout/library/google_base_feed_builder.php');

// Get the feed.
$google_base_feed_builder = new GoogleBaseFeedBuilder($languages_id);
$feed = $google_base_feed_builder->get_xml();

// And output it here.
header("Content-Type: text/xml; charset=utf-8");
echo($feed);

?>
