<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
  echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);"') . tep_hide_session_id();
?>

<h1>
  <?php
  echo HEADING_TITLE_1;
?>
</h1>
<?php
  if ($messageStack->size('search') > 0) {
      echo $messageStack->output('search');
  }
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_SEARCH_CRITERIA);
  new infoBoxHeading($info_box_contents, true, true);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => tep_draw_input_field('keywords', '', 'id="keywords" onKeyUp="loadXMLDoc_advanced(this.value)" autocomplete="off"'));
  $info_box_contents[] = array('text' => '<div class="quick-search-wrap"><div style="display: block; margin-left: 0%; padding: 5px; float: left;" id="quicksearch">Quick Find Results....</div></div>');
  $info_box_contents[] = array('align' => '', 'text' => tep_draw_checkbox_field('search_in_description', '1', 'true') . '<div class="clear"></div> ' . TEXT_SEARCH_IN_DESCRIPTION);
  new infoBox($info_box_contents);
?>
<div class="clear"></div>
<a href="#" onClick="javascript:window.open('<?php
  echo tep_href_link(FILENAME_POPUP_SEARCH_HELP);
?>', 'Estimate Shipping','scrollbars=yes,toolbar=no,menubar=no,status=no,width=400,height=400');" class="general_link">
<?php
  echo TEXT_SEARCH_HELP_LINK;
?>
</a><br />
<?php
  echo tep_image_submit('button_search.gif', IMAGE_BUTTON_SEARCH);
?>


<table id="extra-search" border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_CATEGORIES;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))));
?></td>
  </tr>
  <tr>
    <td class="fieldKey">&nbsp;</td>
    <td class="smallText"><?php
  echo tep_draw_checkbox_field('inc_subcat', '1', true) . ' ' . ENTRY_INCLUDE_SUBCATEGORIES;
?></td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_MANUFACTURERS;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS))));
?></td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_PRICE_FROM;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_input_field('pfrom');
?></td>
  </tr>
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_PRICE_TO;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_input_field('pto');
?></td>
  </tr>
<?php /*  
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_DATE_FROM;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_input_field('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"');
?></td>
  </tr>
  <tr>
    <td class="fieldKey"><?php
  echo ENTRY_DATE_TO;
?></td>
    <td class="fieldValue"><?php
  echo tep_draw_input_field('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"');
?></td>
  </tr>
 * 
 */?>
  <?php
  $attributes_query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "'");
  while ($attributes = tep_db_fetch_array($attributes_query)) {
?>
  <tr>
    <td class="fieldKey"><!--<?php
      echo $attributes["products_options_name"];
?>--></td>
    <?php
?>
      </td>
  </tr>
  <?php
  }
?>
</table>
</form>
<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
  require(DIR_WS_INCLUDES . 'footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>