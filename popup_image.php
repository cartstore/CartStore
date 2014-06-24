<?php ob_start();
/*
  $Id: popup_image.php,v 1.18 2003/06/05 23:26:23 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  $products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pd.language_id = '" . (int)$languages_id . "'");
  $products = tep_db_fetch_array($products_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
 
<link  rel="stylesheet" href="static/product_listing.css" type="text/css">
<script language="javascript">
function change(file)
{

document.popupimage.source.src=file;
}
</script>
 
</head>
<body onLoad="resize();">
<form name="popupimage" >
<?php echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'],'','','name="source"'); ?>
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

$products_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $_GET['pID'] . "'");
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
		<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $extra_images['products_extra_image']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], 55, SMALL_IMAGE_HEIGHT) . '' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
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
 
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
