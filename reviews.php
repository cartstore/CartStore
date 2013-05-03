<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_REVIEWS));


  require(DIR_WS_INCLUDES . 'header.php');


  require(DIR_WS_INCLUDES . 'column_left.php');
?>
      

    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php
  echo HEADING_TITLE;
?></td>
                <td align="right">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <!-- // Points/Rewards Module V2.00 bof //-->
        <?php
  if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td class="main"><h3>
              <?php
      echo REVIEW_HELP_LINK;
?>
            </h3></td>
        </tr>
        <?php
  }
?>
        <!-- // Points/Rewards Module V2.00 eof //-->
        <tr>
          <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_REVIEWS == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
  if ($reviews_split->number_of_rows > 0) {
      if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
              <tr>
                <td><div id="module-product">
                  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
      
                              <?php
          echo ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info')));
?>
                            </ul>
                 <?php
          echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);
?>      
                  </div></td>
              </tr>
              <tr>
                <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
              </tr>
              <?php
      }
      $reviews_query = tep_db_query($reviews_split->sql_query);
      while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php
          echo '<a class="reviews_name" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . $reviews['products_name'] . '</a> <span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>';
?></td>
                      <td class="smallText" align="right"><?php
          echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added']));
?></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                    <tr class="infoBoxContents">
                      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td width="10"><?php
          echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                            <td width="<?php
          echo SMALL_IMAGE_WIDTH + 10;
?>" align="center" valign="top" class="main"><?php
          echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
?></td>
                            <td valign="top" class="main"><?php
          echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br>') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>';
?></td>
                            <td width="10" align="right"><?php
          echo tep_draw_separator('pixel_trans.gif', '10', '1');
?></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
              </tr>
              <?php
      }
?>
              <?php
      } else
      {
?>
              <tr>
                <td><?php
          new infoBox(array(array('text' => TEXT_NO_REVIEWS)));
?></td>
              </tr>
              <tr>
                <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
              </tr>
              <?php
      }
      if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
              <tr>
                <td><div id="module-product">
                  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
      
                              <?php
          echo ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info')));
?>
                            </ul>
                 <?php
          echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);
?>      
                  </div></td>
              </tr>
              <tr>
                <td><?php
          echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
              </tr>
              <?php
      }
?>
            </table></td>
        </tr>
      </table></td>
    <!-- body_text_eof //-->


        <?php
      require(DIR_WS_INCLUDES . 'column_right.php');


      require(DIR_WS_INCLUDES . 'footer.php');


      require(DIR_WS_INCLUDES . 'application_bottom.php');
?>