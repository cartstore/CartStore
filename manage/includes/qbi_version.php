<?php
/*
$Id: qbi_version.php,v 2.10 2005/05/08 al Exp $
Quickbooks Import QBI
contribution for CartStore
ver 2.10 May 8, 2005
(c) 2005 Adam Liberman
www.libermansound.com
info@libermansound.com
Please use the osC forum for support.
GNU General Public License Compatible

    This file is part of Quickbooks Import QBI.

    Quickbooks Import QBI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Quickbooks Import QBI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Quickbooks Import QBI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// This constant must reflect the version number of QBI
define ("QBI_VER","2.10");

// Check if QB Import configured and installed
$resultqbc=mysql_query("SELECT * FROM ".TABLE_QBI_CONFIG) or die(header("Location: qbi_db.php?db_ver=0.00&qbi_vers=".QBI_VER));
if ($myrowqbc=tep_db_fetch_array($resultqbc) AND $myrowqbc["qbi_config_ver"]==QBI_VER) {
  if ($myrowqbc["qbi_config_active"]!=1 AND $PHP_SELF!=DIR_WS_ADMIN."qbi_config.php") header("Location: qbi_config.php?msg=1");
} else {
      header("Location: qbi_db.php?db_ver=".$myrowqbc["qbi_config_ver"]."&qbi_vers=".QBI_VER);
// Note: Absolutely no spaces allowed after the following php closing tag to avoid header error.
}
?>