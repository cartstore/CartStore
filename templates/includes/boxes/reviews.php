<!-- reviews //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_REVIEWS);
  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_REVIEWS));
  $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_REVIEWS_BOX == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
  if (isset($_GET['products_id'])) {
      $random_select .= " and p.products_id = '" . (int)$_GET['products_id'] . "'";
  }
  $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
  $random_product = tep_random_select($random_select);
  $info_box_contents = array();
  if ($random_product) {
      
      $rand_review_query = tep_db_query("select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$random_product['reviews_id'] . "' and languages_id = '" . (int)$languages_id . "'");
      $rand_review = tep_db_fetch_array($rand_review_query);
      $rand_review_text = tep_break_string(tep_output_string_protected($rand_review['reviews_text']), 15, '');
      $info_box_contents[] = array('text' => '
<div class="module">
 <div>
  <div>
   <div><h3>REVIEWS</h3>

    <div class="box">
       <h4> <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $random_product['products_name'] . '</a></h4> 
  
    <div class="imgBox"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], 89, SMALL_IMAGE_HEIGHT) . '</a></div> 
  
  <div class="rating">
  <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '"><br />
' . $rand_review_text . ' ..</a><br><span class="star">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif', sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</span></center><br />
<center><a href="reviews.php">All Reviews</a></center>
</div>
</div>
</div>
</div>
</div>
</div>');
  } elseif (isset($_GET['products_id'])) {
      
      $info_box_contents[] = array('text' => '<div class="module">
<div>
<div>
<div><h3>WRITE REVIEW</h3>
  <center><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '"></a><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW . '</a></center>
</div>
</div>
</div>
</div>');
  } else {
 
      
     // $info_box_contents[] = array('text' => BOX_REVIEWS_NO_REVIEWS);
	  
	
  }
  new infoBox($info_box_contents);
?>
<!-- reviews_eof //-->