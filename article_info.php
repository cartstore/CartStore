<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_INFO);
  $article_check_query = tep_db_query("SELECT COUNT(*) as total from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = '" . (int)$_GET['articles_id'] . "' and ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "'");
  $article_check = tep_db_fetch_array($article_check_query);
?>


<?php
  if (file_exists(DIR_WS_INCLUDES . 'article_header_tags.php')) {
      require(DIR_WS_INCLUDES . 'article_header_tags.php');
  } //if (file_exists(DIR_WS_INCLUDES . 'article_header_tags.php'))
  else {
?>
<?php
  } //else
?>

        
        <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
     
    
    <!-- body_text //-->
    
    <table border="0" width="100%" cellspacing="0"

      cellpadding="2">
      <tr>
        <td><?php
  if ($article_check['total'] < 1) {
?>
          <?php
      echo TEXT_ARTICLE_NOT_FOUND;
?>
          <?php
      } else
      {
          $article_info_query = tep_db_query("select a.articles_id, a.articles_date_added, a.articles_date_available, a.authors_id, ad.articles_name, ad.articles_description, ad.articles_url, au.authors_name from " . TABLE_ARTICLES . " a left join " . TABLE_AUTHORS . " au using(authors_id), " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = '" . (int)$_GET['articles_id'] . "' and ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "'");
          $article_info = tep_db_fetch_array($article_info_query);
          tep_db_query("update " . TABLE_ARTICLES_DESCRIPTION . " set articles_viewed = articles_viewed+1 where articles_id = '" . (int)$_GET['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");
          $articles_name = $article_info['articles_name'];
          $articles_author_id = $article_info['authors_id'];
          $articles_author = $article_info['authors_name'];
?>
          <span style="float: right;" id="article_info_share">
          <?php
          if ($_SERVER['HTTPS']) {
              echo '';
          } //if ($_SERVER['HTTPS'])
          else {
              echo '<script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=d0747722-a27b-4a5e-808e-52268da6c4ad&amp;type=website"></script>';
          } //else
?>
          </span>
          <h1 id="article_info">
            <?php
          echo $articles_name;
?>
          </h1>
          <?php
          include('rSystem.php');
?>
          <?php
          $SimpleRatings->create($article_info['articles_id']);
?>
          <?php
          if (tep_not_null($articles_author) && DISPLAY_AUTHOR_ARTICLE_LISTING == 'true')
              echo TEXT_BY . '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $articles_author_id) . '">' . $articles_author . '</a>';
?>
          <div class="article_desc">
          <?php
          echo stripslashes($article_info['articles_description']);
?>
          <br>
          
          <!-- Comments -->
          
          <hr>
          <div id="div_Comments"> (
            <?php
?>
            </h3>
            <?php
?>
            
            <!-- Comment Form -->
            
            <p>
              <?php
?>
            </p>
          </div>
          <?php
          if (tep_not_null($article_info['articles_url'])) {
?>
          <?php
              echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($article_info['articles_url']), 'NONSSL', true, false));
?>
          <?php
          } //if (tep_not_null($article_info["s_38"]))
          if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
              if ($article_info['articles_date_available'] > date('Y-m-d H:i:s')) {
?>
          <?php
                  } else
                  {
?>
          <?php
                  }
              } //if ($article_info["s_43"] > date("s_44"))
?>
          <?php
              if (ENABLE_ARTICLE_REVIEWS == 'true') {
                  $reviews_query = tep_db_query("SELECT COUNT(*) as count from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$_GET['articles_id'] . "' and approved = '1'");
                  $reviews = tep_db_fetch_array($reviews_query);
?>
          <?php
                  echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count'];
?>
          <?php
                  if ($reviews['count'] <= 0) {
?>
          <?php
                      echo '<a class="button" href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . IMAGE_BUTTON_WRITE_REVIEW . '</a>';
?>
          <?php
                      } else
                      {
?>
          <?php
                          echo '<a class="button" href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . IMAGE_BUTTON_WRITE_REVIEW . '</a> ';
?>
          <?php
                          echo '<a class="button" href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()) . '">' . IMAGE_BUTTON_REVIEWS . '</a>';
?>
          <?php
                      }
                  } //if ($reviews["s_52"] <= 0)
?>
          </form>
          
          <!-- tell_a_friend //-->
          
          <?php
                  if ($_GET['articles_id']) {
                      $xsell_query = tep_db_query("select distinct a.products_id, a.products_image, ad.products_name from " . TABLE_ARTICLES_XSELL . " ax, " . TABLE_PRODUCTS . " a, " . TABLE_PRODUCTS_DESCRIPTION . " ad where ax.articles_id = '" . $_GET['articles_id'] . "' and ax.xsell_id = a.products_id and a.products_id = ad.products_id and ad.language_id = '" . $languages_id . "' and a.products_status = '1' order by ax.sort_order asc limit " . MAX_DISPLAY_ARTICLES_XSELL);
                      $num_products_xsell = tep_db_num_rows($xsell_query);
                      if ($num_products_xsell >= MIN_DISPLAY_ARTICLES_XSELL) {
                          while ($dt_product_xsell = tep_db_fetch_array($xsell_query)) {
                              $products_id = $dt_product_xsell['products_id'];
                          } //while ($dt_product_xsell = tep_db_fetch_array($xsell_query))
                      } //if ($num_products_xsell >= MIN_DISPLAY_ARTICLES_XSELL)
                  } //if ($_GET['articles_id'])
                  if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true') {
                      if (isset($_GET['articles_id'])) {
                          $info_box_contents = array();
                          $info_box_contents[] = array('text' => BOX_TEXT_TELL_A_FRIEND);
                          new infoBoxHeading($info_box_contents, true, true);
                          $info_box_contents = array();
                          $info_box_contents[] = array('form' => tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'), 'align' => 'left', 'text' => TEXT_TELL_A_FRIEND . '&nbsp;' . tep_draw_input_field('to_email_address', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH - 30) . 'px"') . '&nbsp;<input type="hidden" name="products_id" value="' . $products_id . '">' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . tep_draw_hidden_field('articles_id', $_GET['articles_id']) . tep_hide_session_id());
                          new infoBox($info_box_contents);
                      } //if (isset($_GET['articles_id']))
                  } //if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true')
?>
          
          <!-- tell_a_friend_eof //-->
          
          <?php
                  if ((USE_CACHE == 'true') && !SID) {
                      include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
                  } //if ((USE_CACHE == 'true') && !SID)
                  else {
                      include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
                  } //else
              } //if (ENABLE_ARTICLE_REVIEWS == "s_45")
?></td>
      </tr>
    </table>
    
    <!-- body_text_eof //-->
    
 
        
        <?php
              require(DIR_WS_INCLUDES . 'column_right.php');
              require(DIR_WS_INCLUDES . 'footer.php');
              require(DIR_WS_INCLUDES . 'application_bottom.php');
?>