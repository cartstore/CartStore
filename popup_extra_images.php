<?php ob_start();
/*
  $Id: popup_extra_images.php,v 1.0 2003/06/11 Mikel Williams

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

	Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
?>
<link  rel="stylesheet" href="static/product_listing.css" type="text/css">
<script language="javascript">
function change(file)
{

document.popupimage.source.src=file;
}
</script>
<?php
  require('includes/application_top.php');

  $navigation->remove_current_page();

  $products_query = tep_db_query("select pd.products_name,pd.products_id, pei.products_extra_image from " . TABLE_PRODUCTS_EXTRA_IMAGES . " pei left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pei.products_id = pd.products_id where pei.products_extra_images_id = '" . $_GET['peiID'] . "' and pd.language_id = '" . $languages_id . "'");
  $products_values = tep_db_fetch_array($products_query);
 // print($products_values['products_id'].'adfddf');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
<TITLE><?php echo $products_values['products_name']; ?></TITLE>
<BASE href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<SCRIPT language="javascript"><!--
var i=0;
function resize() {
	
  if (navigator.appName == 'Netscape') i=40;
	 if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+240-i);

//	alert (document.images[0].width) ; 


  self.focus();
}
//--></SCRIPT>
</HEAD>
<BODY onLoad="resize();">
<form name="popupimage" >
<?php echo tep_image(DIR_WS_IMAGES . $products_values['products_extra_image'], $products_values['products_name'],'','','name="source"'); ?>
</form>
<?php
  /*
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
  
  Based on Extra images 1.4 from Mikel Williams
  Thanks to Mikel Williams, StuBo, moosey_jude and Randelia
  Modifications: Xav xpavyfr@yahoo.fr

  */

$products_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $products_values['products_id'] . "'");
if (tep_db_num_rows($products_extra_images_query) >= 1){
	$rowcount_value=3;  //number of extra images per row	
	$rowcount=1;
?>
<table width="61" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
<?php	
					
	//$products_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $product_info['products_id'] . "'");
	while ($extra_images = tep_db_fetch_array($products_extra_images_query)) {
?>

	
              
                <td height="61" align="center" class="smallbx" style="padding-top:5px"><div align="center">
<?php   echo tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], addslashes($extra_images['products_name']), 55, SMALL_IMAGE_HEIGHT, ' class="imageborder" onmouseover="change(\''. DIR_WS_IMAGES . $extra_images['products_extra_image']  .'\');"'); ?>





<noscript>
		<?php echo '<a class="general_link" href="' . tep_href_link(DIR_WS_IMAGES . $extra_images['products_extra_image']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], 55, SMALL_IMAGE_HEIGHT) . '' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>
</div></td><td>&nbsp;</td>
             
              
            


<?php
	if ($rowcount == $rowcount_value){echo '<br>'; $rowcount=1;}
	else {$rowcount=$rowcount+1;}
	}
?>	
<?php
}
?>    
 </tr>

           
</table>
<br><br>
<div  align="right"><a class="general_link" href="javascript:window.close();"><img src="static/images/close.jpg" border="0"></a></div>
</BODY>
</HTML>
<?php require('includes/application_bottom.php'); ?>
