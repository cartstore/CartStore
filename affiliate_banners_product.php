<?php
  require('includes/application_top.php');
  if (!tep_session_is_registered('affiliate_id')) {
      $navigation->set_snapshot();
      tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_PRODUCT);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
  $affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " where affiliate_products_id >'0' order by affiliate_banners_title");
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
?>

<h1>
  <?php
  echo HEADING_TITLE;
?>
</h1>
<?php
  echo TEXT_INFORMATION;
?>
<br>
<br>
<?php
  if (tep_db_num_rows($affiliate_banners_values)) {
      while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
          $affiliate_products_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $affiliate_banners['affiliate_products_id'] . "' and language_id = '" . $languages_id . "'");
          $affiliate_products = tep_db_fetch_array($affiliate_products_query);
          $prod_id = $affiliate_banners['affiliate_products_id'];
          $ban_id = $affiliate_banners['affiliate_banners_id'];
          switch (AFFILIATE_KIND_OF_BANNERS) {
              case 1:
                  if ($prod_id > 0) {
                      $link = '<a href="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTPS_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
                      $link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
                      $link2 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $affiliate_products['products_name'] . '</a>';
                  }
                  break;
              case 2:
                  if ($prod_id > 0) {
                      $link = '<a href="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
                      $link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
                      $link2 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $affiliate_products['products_name'] . '</a>';
                  }
                  break;
          }
          if ($prod_id > 0) {
?>
<table width="95%" align="center" border="0" cellpadding="4" cellspacing="0" class="infoBoxContents">
  <tr>
    <td class="infoBoxHeading" align="center"><?php
              echo TEXT_AFFILIATE_NAME;
?>
      &nbsp;
      <?php
              echo $affiliate_banners['affiliate_banners_title'];
?></td>
  </tr>
  <tr>
    <td class="smallText" align="center"><?php
              echo $link;
?></td>
  </tr>
  <tr>
    <td class="smallText" align="center"><?php
              echo TEXT_AFFILIATE_INFO;
?></td>
  </tr>
  <tr>
    <td class="smallText" align="center"><textarea cols="60" rows="4" class="boxText"><?php
              echo $link1;
?>
</textarea></td>
  </tr>
  <tr>
    <td>
    <td>
  </tr>
  <tr>
    <td class="smallText" align="center"><b>Text Version:</b>
      <?php
              echo $link2;
?></td>
  </tr>
  <tr>
    <td class="smallText" align="center"><?php
              echo TEXT_AFFILIATE_INFO;
?></td>
  </tr>
  <tr>
    <td class="smallText" align="center"><textarea cols="60" rows="3" class="boxText"><?php
              echo $link2;
?>
</textarea></td>
  </tr>
</table>
<?php
          }
      }
  }

  require(DIR_WS_INCLUDES . 'column_right.php');
  require(DIR_WS_INCLUDES . 'footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>