<!-- languages //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>Language</h3>
        <ul id="languagebox">
          <?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_LANGUAGES);
  new infoBoxHeading($info_box_contents, false, false);
  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
      include(DIR_WS_CLASSES . 'language.php');
      $lng = new language;
  }
  $languages_string = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
      $languages_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES . $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> </li>';
  }
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => $languages_string);
  new infoBox($info_box_contents);
?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- languages_eof //-->