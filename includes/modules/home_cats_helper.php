<?php
/* 
  $Id: featured_products.php,v 2.51 2003/10/31 21:00:00 cieto Exp $
  
  cieto Featured Products 2.51 2.0 listing module
  cieto@msn.com

Made for:
  CartStore eCommerce Software, for The Next Generation 
  http://www.cartstore.com 
  Copyright (c) 2002 osCommerce 
  GNU General Public License Compatible 
  
*/
?>


<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <?php 
  if (sizeof($featured_products_array) <> '0') { 
   $col = 0; 
    for($i=0; $i<sizeof($featured_products_array); $i++) { 
      if ($featured_products_array[$i]['specials_price']) { 
        $products_price = '<s>' .  $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>'; 
      } else { 
        $products_price = $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])); 
      } 
   $col++; 
?>
<?php if($i==0 || $i==2 || $i==4 || $i==6  || $i==8  || $i==10){ echo"<td >&nbsp;</td>"; } else { echo "<td class='dotted_border' >&nbsp;</td>"; } ?>
	<td  valign="top" align="center">
	
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td  align="center" valign="top">
<!-- /////////////////// Product table will go here //////////////////////////-->

					<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="114">
							<table width="114" border="0" cellspacing="0" cellpadding="4">
                              <tr>
                                <td width="106">
								
								<table width="104" border="0" cellpadding="0" cellspacing="0" class="product_border">
                                  <tr>
                                    <td width="104">
									<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a>'; ?>
									
									
									</td>
                                  </tr>
								  <tr>
								  	<td>
									<?php echo '<center><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '" ><img src="templates/includes/sts_templates/default/images/more_info.jpg" border="0"></a>';?>
									</td>
								  </tr>
                                </table>
								</td>
                              </tr>
                            </table>
							</td>
                       </tr>
                    </table>
			</td>
        </tr>
 
  </table>
	  
   </td>

	<?php 
      if ((($col / FEATURED_PRODUCTS_COLUMNS) == floor($col / FEATURED_PRODUCTS_COLUMNS))) { 
?>
  </tr>


 <?php
if (($col / FEATURED_PRODUCTS_COLUMNS) == 6 ) { 
 echo "<tr><td colspan='4' >&nbsp;</td></tr>";
 } else {
 echo "<tr><td colspan='4' class='dotted_border2' >&nbsp;</td></tr>";
 }
?>


<!--   <tr>
    <td colspan="<?php echo FEATURED_PRODUCTS_COLUMNS; ?>" align="right" valign="top" class="main"></td>
  </tr>
 -->  <tr>
    <?php 
  } 
if (($i+1) != sizeof($featured_products_array)) { 
?>
    <?php 
      } 
    } 
  } 
?>


</td>

</tr>

</table>
<!-- -->
<!-- -->
