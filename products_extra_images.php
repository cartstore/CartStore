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

$products_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $product_info['products_id'] . "'");
if (tep_db_num_rows($products_extra_images_query) >= 1){
	$rowcount_value=3;  //number of extra images per row	
	$rowcount=1;
?>
<?php	
					
	//$products_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $product_info['products_id'] . "'");
	while ($extra_images = tep_db_fetch_array($products_extra_images_query)) {
?>

	<table width="61" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td height="32">&nbsp;</td>
              </tr>
              <tr>
                <td class="smallbx" style="padding-top:5px" height="61"><div align="center"><script LANGUAGE="JavaScript">
<!--
//document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_EXTRA_IMAGES, 'peiID=' . $extra_images['products_extra_images_id']) . '\\\')">'.tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT).'</a>'; ?>');
//-->


document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_EXTRA_IMAGES, 'peiID=' . $extra_images['products_extra_images_id']) . '\\\')"><img src="imagemagic.php?img='.DIR_WS_IMAGES . $extra_images['products_extra_image'].'&w=72&h=100%"   border="0" ></a>'; ?>');


//document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_EXTRA_IMAGES, 'peiID=' . $extra_images['products_extra_images_id']) . '\\\')"><img src="imagemagic.php?img='.DIR_WS_IMAGES . $extra_images['products_extra_image'].'&w=72&h=100%"   border="0" ></a>'; ?>');



</script>
<noscript>
		<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $extra_images['products_extra_image']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>
</div></td>
              </tr>
              <tr>
                <td height="7"></td>
              </tr>
              
            </table>


<?php
	if ($rowcount == $rowcount_value){echo '<br>'; $rowcount=1;}
	else {$rowcount=$rowcount+1;}
	}
?>	
<?php
}
?>    