<?php
  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);
?>
<!-- wishlist //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>WISHLIST</h3>
        <?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_CUSTOMER_WISHLIST);
  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_WISHLIST, '', 'NONSSL'));
  $info_box_contents = array();
  if (is_array($wishList->wishID) && !empty($wishList->wishID)) {
      reset($wishList->wishID);
      if (count($wishList->wishID) < MAX_DISPLAY_WISHLIST_BOX) {
          $wishlist_box = '';
          $counter = 1;
          while (list($wishlist_id, ) = each($wishList->wishID)) {
              $wishlist_id = tep_get_prid($wishlist_id);
              $products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where pd.products_id = '" . $wishlist_id . "' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_name");
              $products = tep_db_fetch_array($products_query);
              $wishlist_box .= '0' . $counter . '.';
              $wishlist_box .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id'], 'NONSSL') . '">' . $products['products_name'] . '</a>';
              $counter++;
          }
          $wishlist_box .= '';
      } else {
          $wishlist_box = '<div class="infoBoxContents">' . sprintf(TEXT_WISHLIST_COUNT, count($wishList->wishID)) . '</div>';
      }
  } else {
      $wishlist_box = '<div class="infoBoxContents"><center>' . BOX_WISHLIST_EMPTY . '</center></div>';
  }
  $info_box_contents[] = array('align' => '', 'text' => $wishlist_box);
  new infoBox($info_box_contents);
?>
      </div>
    </div>
  </div>
</div>
<!-- wishlist_eof //-->