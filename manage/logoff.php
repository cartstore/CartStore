<?php
/*
  $Id: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

//tep_session_destroy();
  tep_session_unregister('login_id');
  tep_session_unregister('login_firstname');
  tep_session_unregister('login_groups_id');
  tep_session_unregister('clone');
  tep_redirect('index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

</head>
<body>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<h1><h3><?php echo HEADING_TITLE; ?></h3></h1>
<?php echo TEXT_MAIN; ?><br>
<?php echo '<a class="button" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' .  IMAGE_BACK . '</a>'; ?><br>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?><br>


</body>

</html>
