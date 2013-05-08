<?php
/*
  $Id: popup_extra_images.php,v 1.0 2003/06/11 Mikel Williams

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

	Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  $products_query = tep_db_query("select pd.products_name, pei.products_extra_image from " . TABLE_PRODUCTS_EXTRA_IMAGES . " pei left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pei.products_id = pd.products_id where pei.products_extra_images_id = '" . $_GET['peiID'] . "' and pd.language_id = '" . $languages_id . "'");
  $products_values = tep_db_fetch_array($products_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
<TITLE><?php echo $products_values['products_name']; ?></TITLE>
<BASE href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<SCRIPT language="javascript"><!--
var i=0;
function resize() {
	alert ('apnakjr') ;
  if (navigator.appName == 'Netscape') i=40;
	 if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);

	alert (document.images[0].width) ; 


  self.focus();
}
//--></SCRIPT>
</HEAD>
<BODY onLoad="resize();">
<?php echo tep_image(DIR_WS_IMAGES . $products_values['products_extra_image'], $products_values['products_name']); ?>
</BODY>
</HTML>
<?php require('includes/application_bottom.php'); ?>
