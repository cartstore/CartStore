<?php
/*
  $Id: banned_ip.php V1 2010
  Catalog Module File
  Originally Created by: Your Friend Sky_Diver
  Modified by: celextel - www.celextel.com
  PHP Intrusion Detection System for osCommerce
  PHPIDS for osCommerce 1.6
  Date: June 13, 2010
  Released under the GNU General Public License
*/
    $ip_check = tep_get_ip_address();

    $check_ip_query = tep_db_query("select ip_address from " . TABLE_BANNED_IP . " where ip_status='0'");
    while ($check_ip = tep_db_fetch_array($check_ip_query))    {
    $db_ip_address = $check_ip['ip_address'];

	if ($db_ip_address == $ip_check) {
	    if ( (strstr($_SERVER['REQUEST_URI'],'banned.php')) || (strstr($_SERVER['REQUEST_URI'],'contact_us.php')) )  {
	    } else {
	    tep_redirect(HTTP_SERVER . '/banned.php');
	    }
	}
    }
?>