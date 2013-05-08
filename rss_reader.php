<?php
/*
  $Id: privacy.php,v 1.22 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RSS_READER);

  require(DIR_WS_MODULES . 'rss_reader.php');
	
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_RSS_READER));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
					<?php if (isset($rss_channel["IMAGE"])) { ?>
           <td><?php echo '<a target="_blank" title="'. $rss_channel["TITLE"] .'" href="' . $rss_channel["LINK"] . '">' .  '<img border="0" src="' .$rss_channel["IMAGE"]["URL"] .' align="middle" " alt="' .$rss_channel["IMAGE"]["TITLE"] .'"> ' . '</a>'; ?></td>
          <?php } else { ?>
           <td><?php echo $rss_channel["TITLE"]; ?></td>
					<?php } ?>    
          </tr>					
					<tr>
					<td><?php echo $rss_channel["DESCRIPTION"]; ?></td>
					</tr>					
					<tr>
					<td>
					<?php if (isset($rss_channel["ITEMS"])) {
	         if (count($rss_channel["ITEMS"]) > 0) {
		        for($i = 0;$i < count($rss_channel["ITEMS"]);$i++) {
					?>	
					 <table width="100%" border="1">
		        <tr>
						 <td width="100%"><h2>
						 <?php echo '<a target="_blank" href="' . $rss_channel["ITEMS"][$i]["LINK"] . '">' .  $rss_channel["ITEMS"][$i]["TITLE"]  . ' </a>'; ?></h2></td>
						 <tr>
						  <td><?php echo '<i>' . html_entity_decode($rss_channel["ITEMS"][$i]["DESCRIPTION"]) . '</i>'; ?> </td>
						 </tr>		          
			      </tr> 
			     </table>
		      <?php }	} else { ?>
		        <td><b>There are no articles in this feed.</b></td>
	        <?php } } ?>
					</td>
					</tr>
        </table></td>
      </tr>     
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
