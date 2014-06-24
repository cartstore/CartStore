<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
  echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);"') . tep_hide_session_id();
?>

<span class="pull-right">
<a class="" data-toggle="modal" data-target="#myModalsearch">
 <i class="fa fa-question-circle fa-3x"></i>
</a>
</span><h1>
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
  $info_box_contents[] = array('text' => '<div class="quick-search-wrap list-group"><div style="display: block; margin-left: 0%; padding: 5px; float: left;" id="quicksearch"></div></div><div class="clear"></div>');
  $info_box_contents[] = array('align' => '', 'text' => '<div class="form-group"> <label>' . TEXT_SEARCH_IN_DESCRIPTION .'</label>'. tep_draw_checkbox_field('search_in_description', '1', 'true') .'</div>')  ;
  new infoBox($info_box_contents);
?>


<div class="modal fade" id="myModalsearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabelsearch" aria-hidden="true">  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Search Help</h4>
      </div>
      <div class="modal-body">
        <p> Keywords may be separated by AND and/or OR statements for greater control of the search results.</p><p>For example, <u>Microsoft AND mouse</u> would generate a result set that contain both words. However, for <u>mouse OR keyboard</u>, the result set returned would contain both or either words.<br><br>Exact matches can be searched for by enclosing keywords in double-quotes.<br><br>For example, <u>"notebook computer"</u> would generate a result set which match the exact string.<br><br>Brackets can be used for further control on the result set.<br><br>For example, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<p>
<?php
  echo tep_image_submit('button_search.gif', IMAGE_BUTTON_SEARCH);
?>

</p>
<hr>


<div class="form-group"><label></label></div>
<div class="form-group"><label><?php
  echo ENTRY_CATEGORIES;
?></label><?php
  echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))));
?><?php
  echo tep_draw_checkbox_field('inc_subcat', '1', true) . ' ' . ENTRY_INCLUDE_SUBCATEGORIES;
?> </div>




<div class="form-group"><label> <?php
  echo ENTRY_MANUFACTURERS;
?></label> <?php
  echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS))));
?> </div>


<div class="form-group"><label><?php
  echo ENTRY_PRICE_FROM;
?></label><?php
  echo tep_draw_input_field('pfrom');
?></div>


<div class="form-group"><label><?php
  echo ENTRY_PRICE_TO;
?></label> <?php
  echo tep_draw_input_field('pto');
?></div>
 
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
  <?php
      // echo $attributes["products_options_name"]; ?> 
    <?php
?>
      
  <?php
  }
?>
 </form>
<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
  require(DIR_WS_INCLUDES . 'footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>