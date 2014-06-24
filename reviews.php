<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_REVIEWS));


  require(DIR_WS_INCLUDES . 'header.php');


  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      

    <!-- body_text //-->
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                	
                	<div class="page-header"><h1><?php
                        
   include(DIR_WS_TEMPLATES . '/system/front-admin-editor/edit-all-reviews.php');

  echo HEADING_TITLE;
?></h1></div>



        <!-- // Points/Rewards Module V2.00 bof //-->
        <?php
  if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
      
<blockquote>
              <?php
      echo REVIEW_HELP_LINK;
?>
            </blockquote> 
        <?php
  }
?>
        <!-- // Points/Rewards Module V2.00 eof //-->
              <?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_REVIEWS == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
  if ($reviews_split->number_of_rows > 0) {
      if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>


    
                	
                	
                   
           
               <?php
      }
      $reviews_query = tep_db_query($reviews_split->sql_query);
      while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
            
            
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">      
<table class="table table-condensed">
            
        
                          <tr>
                           
                            <td valign="top">
<?php
          echo '<a class="" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name'], 120, SMALL_IMAGE_HEIGHT) . '</a>';
?></td>
                            <td valign="top" class="main">
                            	<h3>	<?php
          echo '<a class="" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . $reviews['products_name'] . '</a> <span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>';
?></h3>
                            	
                            	
                            	
                            	
                            	
                            	<p><?php
          echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, ' ') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '</p>

          
		  
          
          
          ';
 	if ($reviews['reviews_rating'] > 0):
		echo '<p class="star-rating">';
		for ($s = 0; $s < $reviews['reviews_rating']; $s++){
			echo '<i class="fa fa-star"></i>';
		}
		echo '</p>';
	endif;

		  echo '</p>';
?>

<i><?php
          echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added']));
?>

</i>
</td>
                            
                          </tr>
                        </table>
       </div>

              <?php
      }
?>
              <?php
      } else
      {
?>
             
<?php
          new infoBox(array(array('text' => TEXT_NO_REVIEWS)));
?>

              <?php
      }
      if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
             

                  <ul class="pagination">
      
                              <?php
          echo ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info')));
?>
                            </ul>
          <p>       <?php
          echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);
?>      </p>


              <?php
      }
?>
           
</td>
        </tr>
      </table>

    <!-- body_text_eof //-->


        <?php
      require(DIR_WS_INCLUDES . 'column_right.php');


      require(DIR_WS_INCLUDES . 'footer.php');


      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>