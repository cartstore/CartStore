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

	
     
                 <span class="extra_image_item" style="margin:3px;> <script LANGUAGE="JavaScript">
<!--
//document.write('<?php echo '<a href="' . DIR_WS_IMAGES . $extra_images['products_extra_image'] .'>'.tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT).'</a>'; ?>');
//-->
</script>

<?php   echo '<a href="' . DIR_WS_IMAGES . $extra_images['products_extra_image'] .'" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], addslashes($extra_images['products_name']), 65, SMALL_IMAGE_HEIGHT, ' class="imageborder" ').'</a>'; ?>





<noscript>
		<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $extra_images['products_extra_image']) . '">' . tep_image(DIR_WS_IMAGES . $extra_images['products_extra_image'], $product_info['products_name'], 55, SMALL_IMAGE_HEIGHT) . '' . TEXT_CLICK_TO_ENLARGE . '</a></div>'; ?>
</noscript></span>

              
            


<?php
	if ($rowcount == $rowcount_value){echo ''; $rowcount=1;}
	else {$rowcount=$rowcount+1;}
	}
?>	
<?php
}
?>    
