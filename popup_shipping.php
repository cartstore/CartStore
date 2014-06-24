<?php
/*
  $Id: popup_tracker.php,v 1.0 200/05/18 12:18:40 $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ESTIMATED_SHIPPING);
  $navigation->remove_current_page();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title>Shipping Estimator</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<link  rel="stylesheet" href="static/product_listing.css" type="text/css">

</head>


<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
	  <!-- BEGIN estimated shipping -->
   <?php include(DIR_WS_MODULES . FILENAME_ESTIMATED_SHIPPING); ?>
<!-- END estimated shipping -->
<tr><td colspan="2" class="horzdot">&nbsp;

</td></tr>
<tr><td>Actual shipping charges will be determined in checkout
</td>
  <td><p class="smallText" align="right"><?php echo '<a class="general_link" href="javascript:window.close()">' . 'CLOSE' . '</a>'; ?></p></td>
</tr>
<tr><td colspan="2" class="horzdot">

</td></tr
></table>

<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td height="35" align="right" valign="top">
<br></td>
</tr></table>
</body>
</html>`