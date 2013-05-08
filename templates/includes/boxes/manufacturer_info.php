TEST42<?php
  if (isset($_GET['products_id'])) {
      $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$_GET['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
      if (tep_db_num_rows($manufacturer_query)) {
          $manufacturer = tep_db_fetch_array($manufacturer_query);
?>
<!-- manufacturer_info //-->
<?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURER_INFO);
          new infoBoxHeading($info_box_contents, false, false);
          $manufacturer_info_string = '<div>';
          if (tep_not_null($manufacturer['manufacturers_image']))
              $manufacturer_info_string .= '' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '<br />
';
          if (tep_not_null($manufacturer['manufacturers_url']))
              $manufacturer_info_string .= '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a><br />
';
          $manufacturer_info_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</a>' . '</div>';
          $info_box_contents = array();
          $info_box_contents[] = array('text' => $manufacturer_info_string);
          new infoBox($info_box_contents);
?>
</td>
</tr>
<!-- manufacturer_info_eof //-->
<?php
      }
  }
?>

