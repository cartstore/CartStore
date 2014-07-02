<?php



$most_popular_query = tep_db_query("select p.products_id, sum(pd.products_viewed) as quantitysum, pd.products_name, pd.products_name, p.products_image, pd.products_description from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' group by pd.products_id order by quantitysum DESC, pd.products_name limit " . MAX_DISPLAY_MOST_POPULAR);
if (tep_db_num_rows($most_popular_query) >= MIN_DISPLAY_MOST_POPULAR) {
?>


<!-- best_sellers //-->


<div class="moduletable-green">
	<h3>Most Popular Products</h3>
	<ul class="clearfix"><?php
    $info_box_contents   = array();
    $info_box_contents[] = array(
        'text' => BOX_HEADING_MOST_POPULAR
    );

    new infoBoxHeading($info_box_contents, false, false);
    $rows              = 0;
    $MOST_POPULAR_list = '';

	while ($most_popular = tep_db_fetch_array($most_popular_query)) {
        $rows++;
        $MOST_POPULAR_list .= '<li class="clearfix">
		<div class="image-box"><a href="#"><img src="imagemagic.php?img=images/' . $most_popular['products_image'] . '&w=30&h=69&page="  alt=""></a>
		</div>
		<div class="text-box">
		<h4>
		<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $most_popular['products_id']) . '">' . $most_popular['products_name'] . '</a> 
		</h4>									
		<a class="more" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $most_popular['products_id']) . '">Learn More</a>

		</div>
		</li>';
    }

    $MOST_POPULAR_list .= '';
    $info_box_contents   = array();
    $info_box_contents[] = array(
        'text' => $MOST_POPULAR_list
    );

    new infoBox($info_box_contents);

?>      </div>
	<!-- best_sellers_eof //-->

	<?php
}
?>