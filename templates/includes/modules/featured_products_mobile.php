 <ul data-role="listview" data-theme="b" data-divider-theme="a">
	<li data-role="listdivider" data-divider-theme="a">
		Featured Products 
	</li>
	<?php

	if (!tep_session_is_registered('sppc_customer_group_id')) {
		$customer_group_id = '0';
	} else {
		$customer_group_id = $sppc_customer_group_id;
	}
	if (sizeof($featured_products_array) <> '0') {
		for ($i = 0; $i < sizeof($featured_products_array); $i++) {
			if ($featured_products_array[$i]['specials_price']) {
				$products_price = '<s>' . $currencies -> display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s><span class="productSpecialPrice">' . $currencies -> display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
			} else {
				$products_price = $currencies -> display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id']));
			}
			$pf -> loadProduct($featured_products_array[$i]['id'], (int)$languages_id);
			$products_price = $pf -> getPriceString();
			if ($featured_products_array[$i]['image'] != "" && file_exists(DIR_WS_IMAGES . '/' . $featured_products_array[$i]['image'])) {
				$z_image = '' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], 82, SMALL_IMAGE_HEIGHT) . '';
			} else {
				$z_image = '<img src="imagemagic.php?img=images/noimage.jpg&w=82&h=82&page=">';
			}
			print('
<li class="ui-li-has-thumb">

<a data-transition="slidefade" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $featured_products_array[$i]['id']) . '">

 <div class="imageWrapper">

' . $z_image . '

</div>  
<h3>' . $featured_products_array[$i]['name'] . '</h3>






');
			if ($featured_products_array[$i]['map_price'] != "0.00") {
				if (isset($_SESSION['customers_email_address'])) {
					$whats_new_price = $products_price;
					$whats_new_price .= '';
				} else {
					$whats_new_price = '';
				}
				if (empty($_SESSION['customers_email_address'])) {
					$whats_new_price .= '

Login for Price';
				}
			} elseif ($featured_products_array[$i]['msrp_price'] != "0.00") {
				$whats_new_price = $products_price . '<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">' . $currencies -> display_price($featured_products_array[$i]['msrp_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>';
			} else
				$whats_new_price = $products_price;
			if ($featured_products_array[$i]['products_url'] != "") {
				$newArea = '';
			} elseif (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
				$newArea = '';
			} elseif ($featured_products_array[$i]['products_price'] > 0) {
				$newArea = '';
			} else {
				$newArea = '';
			}
			if (HIDE_PRICE_NON_LOGGED == "true" && $_SESSION['customers_email_address'] == '') {
				$whats_new_price = "";
			} elseif ($featured_products_array[$i]['products_price'] > 0) {
				$whats_new_price = $whats_new_price;
			} else {
				$whats_new_price = '';
			}
			print('
              <p class="collectionProductPrice">
						' . $whats_new_price . ' 
					</p> </a>
				</li> ');

		 
				}
			} else {
				 
			}
		 
		 
	 
?>
</ul>
 