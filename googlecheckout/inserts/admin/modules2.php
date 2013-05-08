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
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

// fix configuration no saving -
reset($_POST['configuration']);
// end fix    
while (list($key, $value) = each($_POST['configuration'])) {
  // Checks if module is of type google checkout and also verfies if this configuration is 
  // for the check boxes for the shipping options           
  if (is_array($value)) {
    $value = implode(", ", $value);
    $value = ereg_replace (", --none--", "", $value);
  }
  // Change this query to use gc_makeSqlString()
  tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = " . gc_makeSqlString($value) . " where configuration_key = " . gc_makeSqlString($key));
}

?>
