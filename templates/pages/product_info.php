<table width="100%" border="0">
	<tr>
		<td><span class="clear"></span><span> <?php
echo $messageStack->output('upload');
			?></span><?php
echo tep_draw_form('cart_quantity',tep_href_link(FILENAME_PRODUCT_INFO,tep_get_all_get_params(array('action')).'action=add_product'),'post','enctype="multipart/form-data"');
		?>
		<?php
if ($product_check['total'] < 1) {
		?>
		<?php
new infoBox( array( array('text'=>TEXT_PRODUCT_NOT_FOUND)));
		?>
		<?php
echo '<a class="button" href="'.tep_href_link(FILENAME_DEFAULT).'">'.IMAGE_BUTTON_CONTINUE.'</a>';
		?>
		<?php
} else
{
$product_info_query = tep_db_query("select p.products_id,p.map_price, p.msrp_price,pd.products_info_title,p.products_status,pd.products_info_desc, pd.products_name, pd.products_description, pd.products_short, p.products_model, p.products_special, p.products_quantity, p.products_image, p.product_image_2, p.product_image_3, p.product_image_4, p.product_image_5, p.product_image_6, pd.products_url, p.products_price, NULL as specials_new_products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_info = tep_db_fetch_array($product_info_query);
tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
$pf->loadProductSppc((int)$_GET['products_id'], (int)$languages_id, $product_info);
$products_price = $pf->getPriceString();
$products_status = $product_info['products_status'];
if (tep_not_null($product_info['products_model'])) {
$products_name = $product_info['products_name'];
} else {
$products_name = $product_info['products_name'];
}
if (tep_session_is_registered('wishlist_id')) {
		?>
		<span class="messageStackSuccess"> <?php
echo PRODUCT_ADDED_TO_WISHLIST;
			?></span><?php
tep_session_unregister('wishlist_id');
}
		?>
		

		<h1><?php
echo $products_name;
		?></h1>
	<span class="breadcrumbs"> <?php
echo $breadcrumb->trail(' &raquo; ');
			?></span><?php
if (tep_not_null($product_info['products_image'])) {
		?><span class="pimage"
		style="margin-right: 10px; float: left">
		<script language="javascript"><!--

		document.write('<?php
echo '<a href="imagemagic.php?img=images/'.$product_info['products_image'].'&w=400&h=&page=popup" rel="lightbox">'.tep_image(DIR_WS_IMAGES.$product_info['products_image'],addslashes($product_info['products_name']),SMALL_IMAGE_WIDTH,SMALL_IMAGE_HEIGHT,' data-zoomsrc="images/'.$product_info['products_image'].'" class="imageborder" name="source" ').'<div class="clear"></div><center><b>Click here to enlarge</b></center></a><br>'.'';
		?>
');
//-->
		</script>
	 
		<span class="extra_images">

		<center>
		<?php
		if ($product_info['product_image_2'] != "") {
		echo '<a href="' . DIR_WS_IMAGES . $product_info['product_image_2'] . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_2'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT) . '</a>';
		?>
		<?php
}
		?>
		<?php
		if ($product_info['product_image_3'] != "") {
		echo '<a href="' . DIR_WS_IMAGES . $product_info['product_image_3'] . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_3'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT) . '</a>';
		?>
		<?php
}
		?>
		<?php
		if ($product_info['product_image_4'] != "") {
		echo '<a href="' . DIR_WS_IMAGES . $product_info['product_image_4'] . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_4'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT) . '</a>';
		?>
		<?php
}
		?>
		<?php
		if ($product_info['product_image_5'] != "") {
		echo '<a href="' . DIR_WS_IMAGES . $product_info['product_image_5'] . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_5'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT) . '</a>';
		?>
		<?php
}
		?>
		<?php
		if ($product_info['product_image_6'] != "") {
		echo '<a href="' . DIR_WS_IMAGES . $product_info['product_image_6'] . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['product_image_6'], $product_info['products_name'], 75, SMALL_IMAGE_HEIGHT) . '</a>';
		?>
		<?php
}
		?>
		<?php  //backwards compat with other popular extra iamage ext do not delete needed for migrations
		$totproducts_extra_images_query=tep_db_query("SELECT products_extra_image, products_extra_images_id FROM ".TABLE_PRODUCTS_EXTRA_IMAGES." WHERE products_id='".$product_info['products_id']."'");


		if (tep_db_num_rows($totproducts_extra_images_query) >= 1){	?>

		<?php
if(DISPLAY_EXTRA_IMAGES=='true') {
if($product_check['total']>=1) {
include (DIR_WS_INCLUDES.'products_extra_images.php');
}
}
		?>

		<?php	} else	{?>

		<?php }?>
	
			</center></span></span>

		<?php
}
if ($product_info['map_price'] != "0.00") {
	if (isset($_SESSION['customer_id'])) {
		$products_price = $products_price;
		$products_price .= '<br />
			<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($product_info['map_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
	} else {
		$products_price = '<br />
			<span class="map_name">MAP Price:</span> <span class="map_price">' . $currencies->display_price($product_info['map_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span><br>';
	}
	if (!isset($_SESSION['customer_id'])) {
		$products_price .= '<br>
			<span class="ourprice_name">Our Price</span> <br />
			<span class="our_price_price"><a href="login.php">Login to See Price</a></span>';
	}
} elseif ($product_info['msrp_price'] != "0.00") {
	//$products_price = '' . $products_price . '<ul class="product_price_page"> <li>MSRP Price <span>' . $currencies->display_price($product_info['msrp_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></li></ul>';
} else
	$products_price = $products_price;

if ($product_info['products_url'] != "") {
$newArea = '<div align="right">
<a class="button" href=' . $product_info['products_url'] . ' title="' . $product_info['products_url'] . '" >Buy From Partner </a></div>';
} elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
$newArea = '';
} elseif ($product_info['products_price'] > 0) {
$newArea = "<span class='buy_quan'><b>Qty</b><br> " . tep_draw_input_field('cart_quantity', $pf->adjustQty(1), 'size="6"') . " <br>
" . tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_buy_now.gif', IMAGE_BUTTON_IN_CART) . " </span>";
} else {
$newArea = '';
}
if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
$products_price = "";
} else
$products_price = $products_price;
		?>
		<?php
if($_SERVER['HTTPS']) {
echo '';
} else {
echo '
		';
}
		?>
		<span class="details"> <?php
		if ($product_info['products_model']) {
		?>
		<span class="pmodel"><b>SKU <?php
echo $product_info['products_model'];
		?></b></span>
		<br />
		<?php
}
		?>
		<?php
if($product_info['products_price']>0&&empty($product_info['products_url'])) {
echo '<a class="linkup2" href="'.tep_href_link(FILENAME_WISHLIST,tep_get_all_get_params(array('action','products_id')).'wishlist_x=true&products_id='.$product_info['products_id']).'" >[ Add to Wishlist ]</a>';
} else {
$urlContactUs="email_for_price.php?product_name=".addslashes(addslashes($product_info['products_name']))."&products_model=".$product_info['products_model'];
echo '<a href="'.$urlContactUs.'">[ Email for Price ]</a>';
}
		?>
		<?php
echo '&nbsp;&nbsp;<a  class="linkup2" href="'.tep_href_link('wishlist.php',tep_get_all_get_params(array('action')).'').'" > [ View Wishlist ]</a>';
		?>
		<br />
		<br />
		 <?php
if($product_info['products_price']>0) {
echo '<span class="price">'.$products_price .'</span><div class="btc"><img src="images/bitcoin_icon16x16.png"><span>BTC 0.21</span></div>';
echo '';
if((STOCK_CHECK=='true')&&($product_info['products_quantity']<1)) {
$status_p="<span class=\"orange\">Out of Stock</span>";
} elseif($product_info['products_date_available']>date('Y-m-d H:i:s')) {
$status_p='<span class=\"green\"><b>Available '.tep_date_long($product_info['products_date_available']).'</b></span>';
} else {
$status_p="<span class=\"green\"><b>In Stock!</b></span>";
}
} else {
$urlContactUs="email_for_price.php?product_name=".addslashes(addslashes($product_info['products_name']))."&products_model=".$product_info['products_model'];
echo '<p><span class="ui-icon ui-icon-mail-closed" style="float:left;"></span><a href="'.$urlContactUs.'"><b>Email us for special pricing!</b></a></p>';
echo '';
if((STOCK_CHECK=='true')&&($product_info['products_quantity']<1)) {
$status_p="Out of Stock";
} elseif($product_info['products_date_available']>date('Y-m-d H:i:s')) {
$status_p='<b>Available '.tep_date_long($product_info['products_date_available']).'</b>';
} else {
$status_p="<b>In Stock</b>";
}
}
		?>
	<span class="ui-state-highlight ui-corner-all "><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>Stock Status
		<?php
echo $status_p;
		?>
</span><br>
		
		<?php
if($product_info['products_info_title']!="") {
print('
	<span class="ui-state-highlight ui-corner-all "><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>Typiclly Ships In: '.$product_info['products_info_title']) .' Days</span><br>';
}
		?>
		
		 <?php
if((USE_POINTS_SYSTEM=='true')&&($product_info['products_price']>0)&&(DISPLAY_POINTS_INFO=='true')) {
if($new_price=tep_get_products_special_price($product_info['products_id'])) {
$products_price_points=tep_display_points($new_price,tep_get_tax_rate($product_info['products_tax_class_id'])).'rrr</span>';
} else {
$products_price_points=tep_display_points($product_info['products_price'],tep_get_tax_rate($product_info['products_tax_class_id']));
}
$products_points=tep_calc_products_price_points($products_price_points);
$products_points_value=tep_calc_price_pvalue($products_points);
if(USE_POINTS_FOR_SPECIALS=='true'||$new_price==false) {
echo '

	<span class="ui-state-highlight ui-corner-all "><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>'.sprintf(TEXT_PRODUCT_POINTS,number_format($products_points,POINTS_DECIMAL_PLACES),$currencies->format($products_points_value)).'</span><br>';
} else {
echo '

		<span class="ui-state-highlight ui-corner-all "><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>	 '.TEXT_PRODUCT_NO_POINTS.'</span><br>

		

		';
}
}
		?>
		<?php
list($products_id_clean)=explode('{',$product_info['products_id']);
$extra_fields_query=tep_db_query("
		SELECT pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value ,pef.products_extra_fields_status as status
		FROM ".TABLE_PRODUCTS_EXTRA_FIELDS." pef
		LEFT JOIN  ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." ptf
		ON ptf.products_extra_fields_id=pef.products_extra_fields_id
		WHERE ptf.products_id=".$products_id_clean." and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='".$languages_id."')
		ORDER BY products_extra_fields_order");
while($extra_fields=tep_db_fetch_array($extra_fields_query)) {
if(!$extra_fields['status'])
continue;
echo '
		<span class="ui-icon ui-icon-link" style="float:left;"></span>'.$extra_fields['name'].'<b>: '.$extra_fields['value'].'</b><br>';
}
		?>
		<?php
$extra_shipping_query=tep_db_query("select products_ship_price, products_ship_price_two from ".TABLE_PRODUCTS_SHIPPING." where products_id = '".(int)$product_info['products_id']."'");
if(tep_db_num_rows($extra_shipping_query)>0) {
$extra_shipping=tep_db_fetch_array($extra_shipping_query);
if($extra_shipping['products_ship_price']=='0.00') {
echo '<p><i>(Free Shipping for this Item)</i></p>';
} else {
echo '<p><i>(This item requires additional shipping of $'.$extra_shipping['products_ship_price'];
if(($extra_shipping['products_ship_price_two'])>0) {
echo ' for the first item, and $'.$extra_shipping['products_ship_price_two'].' for each additional item + regular shipping costs.)</i></p>';
} else {
echo ' + regular shipping costs.)</i></p>';
}
}
}
		?>
		<?php
if($product_info['products_date_available']>date('Y-m-d H:i:s'))
echo '<p><b>Order today ships on '.tep_date_short($product_info['products_date_available']).'</p>';
elseif(($product_info['products_special']!=1))
echo '';
else
echo '<p><span class="markProductOutOfStock">This product is ordered directly from the manufacturer when your order is placed.  Your order may take additonal time to receive.</span></p>';
		?>
		<?php
		if (tep_session_is_registered('affiliate_id')) {
		?>
		<?php
echo '<a class="button" href="'.tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD,'individual_banner_id='.$product_info['products_id']).'" target="_self">Build Affiliate Link </a>';
		?>
		<?php
}
		?>
		
		<p>
		<?php
$products_attributes_query=tep_db_query("select count(*) as total from ".TABLE_PRODUCTS_OPTIONS." popt, ".TABLE_PRODUCTS_ATTRIBUTES." patrib where patrib.products_id='".(int)$_GET['products_id']."' and patrib.options_id = popt.products_options_id and popt.language_id = '".(int)$languages_id."'");
$products_attributes=tep_db_fetch_array($products_attributes_query);
if($products_attributes['total']>0) {
$products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$_GET['products_id'])?$_GET['products_id']:(int)$_GET['products_id']);
require (DIR_WS_CLASSES.'pad_'.PRODINFO_ATTRIBUTE_PLUGIN.'.php');
$class='pad_'.PRODINFO_ATTRIBUTE_PLUGIN;
$pad=new $class($products_id);
echo '<div class="clear"></div>

<span class="opts2" style="float:left;"><b>Options</b><br>';
echo $pad->draw();
echo '</span>';
}
		?>
	 
		
		
		<?php echo $newArea;?>
	</p>
		</span> 
		
		
		
		
		<div class="clear"></div> <!-- begin tab pane //--> <div class="clear"></div>
		<br>
		<?php
		$product_description_string = $product_info['products_description'];
		$tab_array = preg_match_all("|#newtab#(.*)#/newtab#|Us", $product_description_string, $matches, PREG_SET_ORDER);
		if ($tab_array) {
		?>
		<div id="tabs">
		<ul>
		<?php
for($i=0,$n=sizeof($matches);$i<$n;$i++) {
$this_tab_name=preg_match_all("|#tabname#(.*)#/tabname#|Us",$matches[$i][1],$tabname,PREG_SET_ORDER);
if($this_tab_name) {
echo ''.'<li><a href="#tabs-'.$i.'"><span>'.$tabname[0][1].'</span></a></li>'.'';
echo '';
}
}
		?>
		</ul>
		<?php
for($i=0,$n=sizeof($matches);$i<$n;$i++) {
$this_tab_name=preg_match_all("|#tabname#(.*)#/tabname#|Us",$matches[$i][1],$tabname,PREG_SET_ORDER);
if($this_tab_name) {
if(preg_match_all("|#tabpage#(.*)#/tabpage#|Us",$matches[$i][1],$tabpage,PREG_SET_ORDER)) {
require ($tabpage[0][1]);
} elseif(preg_match_all("|#tabtext#(.*)#/tabtext#|Us",$matches[$i][1],$tabtext,PREG_SET_ORDER)) {
echo '<div id="tabs-'.$i.'">'.stripslashes($tabtext[0][1]).'</div>';
}
echo '

		';
}
}
		?>
		<?php
		if ($tab_array) {
		?>
		</div>
		<br>
		<center>
		<?php
echo $product_info['products_info_desc'];
		?>
		</center> <?php
} else
{
		?>
		<?php
}
		?>
		<?php
} else
{
		?>

		<!-- End Tab Pane //--> <?php
		if ($product_info['products_description']) {
		?>
		<div class="pdesc">
		<span class="product_title"><b>Products
		Description</b> </span>
		<div class="clear"></div>
		<?php
echo stripslashes($product_info['products_description']);
		?>	
		
		
		
		
		<div class="video" style="text-align: center; margin-bottom: 10px">
		<?php
echo $product_info['products_info_desc'];
		?>
		</div> <div class="clear"></div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<?php
		if (YMM_DISPLAY_DATA_ON_PRODUCT_INFO_PAGE == 'Yes') {
		?>
		<!-- YMM BOF -->
		<br>
		<br>
		<table width="100%" colspan="3">
		<td class="dataTableHeading" nowrap><b> <?php
echo TEXT_PRODUCTS_CAR_HEADING;
		?></b></td>
		<tr>
		<td class="dataTableContentUp"><?php
echo TEXT_PRODUCTS_CAR_MAKE;
		?></td>
		<td class="dataTableContentUp"><?php
echo TEXT_PRODUCTS_CAR_MODEL;
		?></td>
		<td class="dataTableContentUp"><?php
echo TEXT_PRODUCTS_CAR_YEARS;
		?></td>
		</tr>
		<?php
if(isset($_GET['products_id'])&&$_GET['products_id']!='') {
$q=tep_db_query("select * from products_ymm where products_id = ".(int)$_GET['products_id']);
if(tep_db_num_rows($q)>0) {
while($r=tep_db_fetch_array($q)) {
echo '<tr>

		<td class="dataTableContentLow">'.($r['products_car_make']!=''?$r['products_car_make']:'all').'</td>

		<td class="dataTableContentLow">'.($r['products_car_model']!=''?$r['products_car_model']:'all').'</td>

		<td class="dataTableContentLow">'.$r['products_car_year_bof'].' - '.$r['products_car_year_eof'].'</td>

		</tr>';
}
} else {
echo '<tr><td class="dataTableContentLow" colspan="3">Universal Product</td></tr>';
}
}
		?>
		</table>
		<!-- YMM EOF -->
		<?php
}
		?>
		
		<div class="clear"></div>
		</div>
		<div class="palsolisted">
		<br>
		<?php // to slow query 
//$category_to_product_query=tep_db_query("select pc.*,c.categories_name from ".TABLE_PRODUCTS_TO_CATEGORIES." pc,".TABLE_CATEGORIES_DESCRIPTION." c where products_id = '".(int)$_GET['products_id']."' and c.categories_id=pc.categories_id and c.language_id='".(int)$languages_id."'");
//if(tep_db_num_rows($category_to_product_query)>0) {
//print('<ul class="also_listed"><li><b>Listed in:</b></li>');
//while($category_to_product=tep_db_fetch_array($category_to_product_query)) {
//print('<li><a href="'.tep_href_link(FILENAME_DEFAULT,tep_get_path($category_to_product['categories_id'])).'"><span>'.$category_to_product['categories_name'].'</span></a></li>');
//}
//print('</ul><div class="clear"></div>');
//}
		?>
		</div> <?php
}
		?>
		<div class="clear"></div>
		 <span class="previews"> <?php?></span> <div class="clear"></div> <div class="clear"></div> <?php
}
		?>

		<!--          Get iew fot    --> <?php
if(!tep_session_is_registered('sppc_customer_group_id')) {
$customer_group_id='0';
} else {
$customer_group_id=$sppc_customer_group_id;
}
if($customer_group_id!='0') {
$products_extra_images_query=tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from ".TABLE_PRODUCTS_XSELL." xp, ".TABLE_PRODUCTS." p LEFT JOIN ".TABLE_PRODUCTS_GROUPS." pg using(products_id), ".TABLE_PRODUCTS_DESCRIPTION." pd where xp.products_id = '".$_GET['products_id']."' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '".$languages_id."' and p.products_status = '1' and pg.customers_group_id = '".$customer_group_id."' order by sort_order asc limit ".MAX_DISPLAY_ALSO_PURCHASED);
} else {
$products_extra_images_query=tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from ".TABLE_PRODUCTS_XSELL." xp, ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd where xp.products_id = '".$_GET['products_id']."' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '".$languages_id."' and p.products_status = '1' order by sort_order asc limit ".MAX_DISPLAY_ALSO_PURCHASED);
}
		?>
		<?php
/* Begin product_previous_next */
//	if (($product_check['total'] > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
//		include (DIR_WS_INCLUDES . 'products_next_previous.php');
//	}
/* End product_previous_next */
		?>
		
		<div class="addthis_toolbox addthis_default_style " width="100%" align="center">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_twitter"></a>
			<a class="addthis_button_delicious"></a>
			<a class="addthis_button_digg"></a>
			<a class="addthis_button_stumbleupon"></a>
			<a class="addthis_button_email"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript">
var addthis_config= {
"data_track_clickback":true
};

		</script><script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4daec72011d7ab91"></script>
		
		
		<div class="clear"></div>
		
			<?php
/* Begin product_previous_next */
if(($product_check['total']>0)&&((PREV_NEXT_BAR_LOCATION=='1')||(PREV_NEXT_BAR_LOCATION=='3'))) {
include (DIR_WS_INCLUDES.'products_next_previous.php');
}
/* End product_previous_next */
		?>
		<div class="clear"></div>
		
		
	
		
		<?php
		$num_products_xsell = tep_db_num_rows($products_extra_images_query);
		if ($num_products_xsell > 0) {
		?>
		<br>
		<span class="xsell"> <?php?></span> <?php
}
		?>
		<span class="xsell"> <?php
include (DIR_WS_MODULES.'auto_xsell_products.php');
		?></span> <?php
}
		?>
		<div class="clear"></div>
		<br>
		<span class="previews"> <?php
include (DIR_WS_MODULES.'product_reviews_info.php');
		?></span> </form></td>
	</tr>
</table>