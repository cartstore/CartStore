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

       <div class="block block-list block-compare">
<div class="block-title">
<strong>
<span>Shop by Price </span>
</strong>
</div>
<div class="block-content">
         <ul class="nav nav-pills nav-stacked">
    	
         ';
  new infoBox($info_box_contents);
  echo '</ul>  
</div>
</div>';
?>
<!-- shop_by_price //-->