<?php
#July 11, 2005
#Version 1.0
#By Dan Sullivan
/*===============================================================================*/
	define('MAX_REVIEWS', 4); # Number of maximum reviews on front page
	define('NO_REVIEWS_TEXT', 'There are currently no reviews for this product.'); #Text
	define('BOX_REVIEWS_HEADER_TEXT', '<h3>Reviews on this item</h3>'); #Text
/*================================================================================*/

$reviews_query = tep_db_query("select r.reviews_id, r.customers_name, r.date_added, rd.reviews_text, r.reviews_rating FROM reviews r, reviews_description rd WHERE r.reviews_id = rd.reviews_id AND r.products_id = '" . (int)$_GET['products_id'] . "' AND rd.languages_id = '" . (int)$languages_id . "' ORDER BY r.date_added DESC LIMIT " . MAX_REVIEWS);
if(tep_db_num_rows($reviews_query)>0)
print(BOX_REVIEWS_HEADER_TEXT);


$info_box_contents = array();

while ($reviews = tep_db_fetch_array($reviews_query)) {
  $info_box_contents[][0] = array('align' => 'left',
                                  'params' => 'class="alltext" valign="top"',
                                  'text' => '<p><span class="star" style="float:left;">' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</span><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><span class="ui-icon ui-icon-person" style="float:left";></span><span class="alltextbold">' . $reviews['customers_name'] . ':</span>&nbsp;</a>  ' . $reviews['reviews_text'].'</p>');
								  print('<p><span class="star"  style="float:left;">' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</span><a class="alltextbold" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><span class="ui-icon ui-icon-person" style="float:left";></span><span class="alltextbold">' . $reviews['customers_name'] . ':</span>&nbsp;</a>   <span class="alltext">' . $reviews['reviews_text'].'</span></p>');
}

 if(mysql_num_rows($reviews_query) > 0) {
$info_box_contents[][0] = array('align' => '',
                          'params' => '',
                          'text' => '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . (int)$_GET['products_id']) . '"><span class="ui-icon ui-icon-comment" style="float:left;"></span>All Reviews</a>');
						  print('<a class="button" style="float:left;" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . (int)$_GET['products_id']) . '" ><span class="ui-icon ui-icon-comment" style="float:left;"></span>All Reviews</a><a class="button" href="' . tep_href_link("product_reviews_write.php", tep_get_all_get_params(array('action')).'products_id=' . $product_info['products_id']) . '" >Write Review </a>');

						  
						  

} else {
  $info_box_contents[][0] = array('align' => '',
                          'params' => '',
                          'text' => NO_REVIEWS_TEXT);
						 
						  //print(NO_REVIEWS_TEXT);
 echo '<p><a class="button" href="' . tep_href_link("product_reviews_write.php", tep_get_all_get_params(array('action')).'products_id=' . $product_info['products_id']) . '" >Write Review </a></p>'; 
}
//new contentBox($info_box_contents);
?>
