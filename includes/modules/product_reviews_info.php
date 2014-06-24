<?php
#July 11, 2005
#Version 1.0
#By Dan Sullivan
/*===============================================================================*/
	define('MAX_REVIEWS', 4); # Number of maximum reviews on front page
	define('NO_REVIEWS_TEXT', 'There are currently no reviews for this product.'); #Text
	define('BOX_REVIEWS_HEADER_TEXT', '<h3>Reviews on this item</h3>'); #Text
/*================================================================================*/


/* Microdata Aggregate Rich Snippet */
$reviews_query = tep_db_query("select count(r.reviews_rating) as total_reviews, (SUM(r.reviews_rating)/COUNT(r.reviews_rating)) as total_reviews_rating FROM reviews r WHERE r.products_id = '" . (int)$_GET['products_id'] . "'");
$reviews_info = tep_db_fetch_array($reviews_query);
if ($reviews_info['total_reviews'] > 0):
?>
  <div itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
    <span itemprop="itemreviewed"><?php echo $product_info['products_name']; ?></span>
    <img itemprop="photo" src="<?php echo tep_href_link( DIR_WS_IMAGES . $product_info['products_image'] ); ?>" style="width: 100px; height: auto;">
      <span itemprop="rating"><?php echo $reviews_info['total_reviews_rating']; ?></span>
      out of <span itemprop="best">5</span>
    based on <span itemprop="votes"><?php echo $reviews_info['total_reviews']; ?></span> ratings.
  </div>
<?php
endif;

$reviews_query = tep_db_query("select r.reviews_id, r.customers_name, r.date_added, rd.reviews_text, r.reviews_rating FROM reviews r, reviews_description rd WHERE r.reviews_id = rd.reviews_id AND r.products_id = '" . (int)$_GET['products_id'] . "' AND rd.languages_id = '" . (int)$languages_id . "' ORDER BY r.date_added DESC LIMIT " . MAX_REVIEWS);
if(tep_db_num_rows($reviews_query)>0)
print(BOX_REVIEWS_HEADER_TEXT);


$info_box_contents = array();

while ($reviews = tep_db_fetch_array($reviews_query)) {
  $info_box_contents[][0] = array('align' => 'left',
                                  'params' => 'class="alltext" valign="top"',
                                  'text' => '


                            <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">
                                  ' . $reviews['customers_name'] . ':</a> </h4>
                                  </span> ' . $reviews['reviews_text'].'</p>');




								  print('







  <h4> <i class="fa fa-user"></i>  <a class="alltextbold" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">
' . $reviews['customers_name'] . '
</a>  </h4>
<p><blockquote><span class="pull-right star-rating">');
	if ($reviews['reviews_rating'] > 0):
		for ($s = 0; $s < $reviews['reviews_rating']; $s++){
			echo '<i class="fa fa-star"></i>';
		}
	endif;
	echo  '</span>' . $reviews['reviews_text'].'</p></blockquote><hr>';
}

 if(tep_db_num_rows($reviews_query) > 0) {
$info_box_contents[][0] = array('align' => '',
                          'params' => '',
                          'text' => '<a class="" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . (int)$_GET['products_id']) . '"><span class="ui-icon ui-icon-comment" style="float:left;"></span>All Reviews</a>');
						  print('


						 <a class="btn btn-default btn-xs pull-right" href="' . tep_href_link("product_reviews_write.php", tep_get_all_get_params(array('action')).'products_id=' . $product_info['products_id']) . '" ><i class="fa fa-pencil"></i> Write Review </a> <a class="" style="" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . (int)$_GET['products_id']) . '" >
						 All Reviews</a><hr>');




} else {
  $info_box_contents[][0] = array('align' => '',
                          'params' => '',
                          'text' => NO_REVIEWS_TEXT);

						  //print(NO_REVIEWS_TEXT);
 echo '<p><a class="btn button" href="' . tep_href_link("product_reviews_write.php", tep_get_all_get_params(array('action')).'products_id=' . $product_info['products_id']) . '" ><i class="fa fa-pencil"></i> Write Review </a></p>';
}
//new contentBox($info_box_contents);
?>
