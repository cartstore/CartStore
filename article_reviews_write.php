<?php
  require('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  $article_info_query = tep_db_query("select a.articles_id, ad.articles_name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . (int)$_GET['articles_id'] . "' and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($article_info_query)) {
      tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
      $article_info = tep_db_fetch_array($article_info_query);
  }
  $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $customer = tep_db_fetch_array($customer_query);
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
      $rating = tep_db_prepare_input($_POST['rating']);
      $review = tep_db_prepare_input($_POST['review']);
      $error = false;
      if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
          $error = true;
          $messageStack->add('review', JS_REVIEW_TEXT);
      }
      if (($rating < 1) || ($rating > 5)) {
          $error = true;
          $messageStack->add('review', JS_REVIEW_RATING);
      }
      if ($error == false) {
          tep_db_query("insert into " . TABLE_ARTICLE_REVIEWS . " (articles_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$_GET['articles_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
          $insert_id = tep_db_insert_id();
          tep_db_query("insert into " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");
          tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params(array('action'))));
      }
  }
  $articles_name = $article_info['articles_name'];
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_REVIEWS_WRITE);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html <?php
  echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">
<?php
  
  
  
  if (file_exists(DIR_WS_INCLUDES . 'article_header_tags.php')) {
      require(DIR_WS_INCLUDES . 'article_header_tags.php');
  } else {
?>
<title><?php
      echo TITLE
?></title>
<?php
  }
  
?>
<base href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--

function checkForm() {

  var error = 0;

  var error_message = "<?php
  echo JS_ERROR;
?>";



  var review = document.article_reviews_write.review.value;



  if (review.length < <?php
  echo REVIEW_TEXT_MIN_LENGTH;
?>) {

    error_message = error_message + "<?php
  echo JS_REVIEW_TEXT;
?>";

    error = 1;

  }



  if ((document.article_reviews_write.rating[0].checked) || (document.article_reviews_write.rating[1].checked) || (document.article_reviews_write.rating[2].checked) || (document.article_reviews_write.rating[3].checked) || (document.article_reviews_write.rating[4].checked)) {

  } else {

    error_message = error_message + "<?php
  echo JS_REVIEW_RATING;
?>";

    error = 1;

  }



  if (error == 1) {

    alert(error_message);

    return false;

  } else {

    return true;

  }

}



function popupWindow(url) {

  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')

}

//--></script>
</head>

<body>

<!-- header //-->

<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<!-- header_eof //--> 

<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">
        
        <!-- left_navigation //-->
        
        <?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
        
        <!-- left_navigation_eof //-->
        
      </table></td>
    
    <!-- body_text //-->
    
    <td width="100%" valign="top"><?php
  echo tep_draw_form('article_reviews_write', tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, 'action=process&articles_id=' . $_GET['articles_id']), 'post', 'onSubmit="return checkForm();"');
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading" valign="top"><?php
  echo HEADING_TITLE . $articles_name . '\'';
?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php
  echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <?php
  if ($messageStack->size('review') > 0) {
?>
        <tr>
          <td><?php
      echo $messageStack->output('review');
?></td>
        </tr>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
?></td>
        </tr>
        <?php
  }
?>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main" colspan="2"><?php
  echo '<b>' . SUB_TITLE_FROM . '</b> ' . tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']);
?></td>
                    </tr>
                    <tr>
                      <td class="main"><b><?php
  echo SUB_TITLE_REVIEW;
?></b></td>
                      <td align="right" class="main"><?php
  echo TEXT_APPROVAL_WARNING;
?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                          <tr class="infoBoxContents">
                            <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                  <td class="main"><?php
  echo tep_draw_textarea_field('review', 'soft', 60, 15);
?></td>
                                </tr>
                                <tr>
                                  <td class="main"><?php
  echo '<b>' . SUB_TITLE_RATING . '</b> ' . TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD;
?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                          <tr class="infoBoxContents">
                            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td class="main"><?php
  echo '<a class="button" href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params(array('reviews_id', 'action'))) . '">' . IMAGE_BUTTON_BACK . '</a>';
?></td>
                                  <td class="main" align="right"><?php
  echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
            </table></td>
        </tr>
      </table>
      </form></td>
    
    <!-- body_text_eof //-->
    
    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="0" cellpadding="2">
        
        <!-- right_navigation //-->
        
        <?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>
        
        <!-- right_navigation_eof //-->
        
      </table></td>
  </tr>
</table>

<!-- body_eof //--> 

<!-- footer //-->

<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>

<!-- footer_eof //-->

</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>