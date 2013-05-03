<!-- shop by price //-->
<?php
  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);
  $info_box_contents = array();
  if (!empty($shop_price_type) && $shop_price_type == 'dropdown') {
      $price_range_list = '';
      $price_range_list[] = array('id' => '0', 'text' => BOX_HEADING_SHOP_BY_PRICE);
      for ($range = 0; $range < sizeof($price_ranges); $range++) {
          $price_range_list[] = array('id' => $range, 'text' => $price_ranges[$range]);
      }
      $info_box_contents[] = array('form' => '<form name="shop_price" action="' . tep_href_link(FILENAME_SHOP_BY_PRICE) . '" method="get">' . tep_hide_session_id(), 'align' => '', 'text' => tep_draw_pull_down_menu('range', $price_range_list, $range, 'onchange="this.form.submit();"  size="' . 1 . '" style="width: 100%"') . tep_hide_session_id());
  } else {
      for ($range = 0; $range < sizeof($price_ranges); $range++) {
          $info_box_contents[] = array('align' => '', 'text' => '<li><a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $range, 'NONSSL') . '">' . $price_ranges[$range] . '</a></li>');
      }
  }
  echo '
<div class="module">
  <div>
    <div>
      <div>
        <h3>Shop by Price</h3>
        <ul>';
  new infoBox($info_box_contents);
  echo '</ul>  </div>
    </div>
  </div>
</div>';
?>
<!-- shop_by_price //-->