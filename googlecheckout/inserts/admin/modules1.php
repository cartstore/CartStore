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

/**
 * Google Checkout v1.5.0
 * $Id$
 * 
 * This code is meant to be included in catalog/admin/modules.php.
 * 
 * TODO(eddavisson): Seems like we shouldn't need to roll our own function to do this.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

// Execute the cron hook.
require_once(DIR_FS_CATALOG . '/googlecheckout/library/google_cron_hook.php');
$google_cron_hook = new GoogleCronHook();
$google_cron_hook->execute();
 
// TODO(eddavisson): Seems like we shouldn't need to roll our own function to do this. 
function gc_makeSqlString($str) {
  $single_quote = "'";
  $escaped_str = addcslashes(stripcslashes($str), "'\"\\\0..\37!@\177..\377");
  return ($single_quote.$escaped_str.$single_quote);
}

?>
