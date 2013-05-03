<?php
/*
  $Id: banned.php V1 2010
  Catalog Root File
  PHPIDS for osCommerce 1.6
  Date: June 13, 2010
  Copyright (c) Your Friend Sky_Diver
  Released under the GNU General Public License
*/
require('includes/application_top.php');

    $ip_2ban_address = tep_get_ip_address();
    $block_ip_query = tep_db_query("select * from " . TABLE_BANNED_IP . " where ip_address = '" . $ip_2ban_address . "'");
	if(tep_db_num_rows($block_ip_query) == 1) {
	} else {
	tep_db_query("insert into " . TABLE_BANNED_IP . " (ip_address, ip_status) values ('" . $ip_2ban_address . "', '0')");
	} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Banned</title>
<style type="text/css">
.intrusion_detected {
	font-size: 14px;
	padding: 5px;
}
</style>
</head>
<body>
<div class="intrusion_detected">
  <h2>Malicious Activity Detected</h2>
Your IP Address, <strong><?php echo $ip_2ban_address ?></strong> has been reported for site violations.<br /><br />
If you feel you have reached this page in error, please <a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>"><?php echo BOX_INFORMATION_CONTACT; ?></a> and provide your IP Address.<br />
<strong><br />
More Info:</strong> The activity you are performing on this website resembles that of a intrusion attempt and our system has stopped you from carrying out your action. <br />
</div>
</body>
</html>