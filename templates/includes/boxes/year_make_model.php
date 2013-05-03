<?php
  if (AUTO_CONFIG == 'true') {
?>
<?php
      $Make_Model_Year_query = tep_db_query("select distinct products_car_make, products_car_model, products_car_year_bof, products_car_year_eof from products_ymm where products_car_make != '' and  products_car_model != '' and products_car_year_bof !=0 and products_car_year_eof != 0 order by products_car_make, products_car_model, products_car_year_bof, products_car_year_eof");
      if ($number_of_rows = tep_db_num_rows($Make_Model_Year_query)) {
?>
<!-- year make model //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>SELECT VEHICLE</h3>
        <p style="text-align: center;">
            You can disable this feature for stores that do not require it
        </p>
        <?php
          $info_box_contents = array();
          $info_box_contents[] = array('text' => '' . BOX_HEADING_MAKE_MODEL_YEAR . '');
          new infoBoxHeading($info_box_contents, false, false);

          $Make_array[] = array('id' => 'all', 'text' => 'Choose Vehicle');
          $Model_array[] = array('id' => 'all', 'text' => 'Choose Model');
          $Year_array[] = array('id' => 0, 'text' => 'Choose Year');
          $javascript = '<script language="javascript" type="text/javascript">
//<![CDATA[
var a = new Array();
var b = new Array();
var c = new Array();';
          $y = array();
          $M_a = array();
          $products_car_make_old = '';
          $products_car_model_old = '';
          while ($Makes = tep_db_fetch_array($Make_Model_Year_query)) {
              if (!isset($M_a[$Makes['products_car_make']]))
                  $Make_array[] = array('id' => $Makes['products_car_make'], 'text' => $Makes['products_car_make']);
              if (!isset($M_a[$Makes['products_car_make']][$Makes['products_car_model']]) && count($y) > 0) {
                  $M_a[$products_car_make_old][$products_car_model_old] = $y;
                  $y = array();
              }
              if ($Makes['products_car_year_bof'] != 0 && $Makes['products_car_year_eof'] != 0) {
                  if ($Makes['products_car_year_bof'] == $Makes['products_car_year_eof']) {
                      $y[$Makes['products_car_year_bof']] = 1;
                  } elseif ($Makes['products_car_year_bof'] < $Makes['products_car_year_eof']) {
                      while ($Makes['products_car_year_bof'] <= $Makes['products_car_year_eof']) {
                          $y[$Makes['products_car_year_bof']] = 1;
                          $Makes['products_car_year_bof']++;
                      }
                  }
              }
              $products_car_make_old = $Makes['products_car_make'];
              $products_car_model_old = $Makes['products_car_model'];
              $M_a[$Makes['products_car_make']][$Makes['products_car_model']] = array();
          }
          $M_a[$products_car_make_old][$products_car_model_old] = $y;
          $i = 0;
          foreach ($M_a as $k => $v) {
              $javascript .= 'a[' . $i . ']="' . $k . '";b[' . $i . ']=new Array(';
              $ii = 0;
              $s = '';
              foreach ($M_a[$k] as $kk => $vv) {
                  $javascript .= ($ii != 0 ? ',' : '') . '"' . $kk . '"';
                  $ss = '';
                  $iii = 0;
                  foreach ($M_a[$k][$kk] as $kkk => $vvv) {
                      $ss .= ($iii != 0 ? ',' : '') . $kkk;
                      $iii++;
                  }
                  if ($iii == 1)
                      $ss = '"' . $ss . '"';
                  $s .= 'c[' . $i . '][' . $ii . ']=new Array(' . $ss . ');';
                  $ii++;
              }
              $javascript .= ');c[' . $i . ']=new Array();' . $s;
              $i++;
          }
          $javascript .= '

function pop_model(){
  var o ="<select name=\"Model\" onChange=\"pop_year();\" style=\"width: 100%\"><option value=\"all\">Choose Model</option>";
  var sv = document.make_model_year.Make.value;
  if(sv != "all"){
    var v = a.length;
    while(v--) if(sv == a[v]) break;
    for(var i = 0; i < b[v].length; i++)
      o+="<option value=\""+b[v][i]+"\">"+b[v][i]+"</option>";
  }
  o+="</select>";
  document.getElementById("model_select").innerHTML= o;
    document.getElementById("year_select").innerHTML= "<select name=\"Year\" style=\"width: 100%\"><option value=\"0\">Choose Year</option></select>";
}

function pop_year(){
  var o ="<select name=\"Year\" style=\"width: 100%\" onChange=\"document.make_model_year.submit();\"><option value=\"0\">Choose Year</option>";
  var sv = document.make_model_year.Make.value;
  if(sv != "all"){
    var v = a.length;
    while(v--) if(sv == a[v]) break;
    var sv2 = document.make_model_year.Model.value;
      if(sv2 != "all"){
        var v2 = b[v].length;
        while(v2--) if(sv2 == b[v][v2]) break;
        for(var i = 0; i < c[v][v2].length; i++)
          o+="<option value=\""+c[v][v2][i]+"\">"+c[v][v2][i]+"</option>";
      }
  }
  o+="</select>";
  document.getElementById("year_select").innerHTML= o;
}

function clear_vehicle(){
  jQuery("form[name=\'make_model_year\'] select[name=\'Make\']").val("all");
  jQuery("form[name=\'make_model_year\'] select[name=\'Model\'] option[value!=\'all\']").remove();
  jQuery("form[name=\'make_model_year\'] select[name=\'Year\'] option[value!=\'0\']").remove();
}
//]]>
</script>';
          if (isset($Make_selected_var) && isset($M_a[$Make_selected_var])) {
              foreach ($M_a[$Make_selected_var] as $k => $v)
                  $Model_array[] = array('id' => $k, 'text' => $k);
          }
          if (isset($Make_selected_var) && isset($Model_selected_var) && isset($M_a[$Make_selected_var][$Model_selected_var]))
              foreach ($M_a[$Make_selected_var][$Model_selected_var] as $k => $v)
                  $Year_array[] = array('id' => $k, 'text' => $k);
          $script = basename($_SERVER['SCRIPT_NAME']);
          if ($script == 'index.php' && (!isset($cPath) || $cPath == '')) {
              if (defined('FILENAME_ALLPRODS_SEO') && ALL_PRODUCTS_SEO == 'true') {
                  $script = FILENAME_ALLPRODS_SEO;
              } elseif (defined('ALL_PRODUCTS') && ALL_PRODUCTS == 'true') {
                  $script = FILENAME_ALLPRODS;
              }
          }
          $hidden_get_variables = '';
          $keys = array('Year', 'Make', 'Model', tep_session_name(), 'x', 'y');
          if ($script == 'product_info.php') {
              if (isset($cPath) || $cPath != '') {
                  $_GET['cPath'] = $cPath;
                  $link = 'index.php?cPath=' . $cPath . '&Make=all&Model=all&Year=0';
              } else {
                  $link = 'index.php?Make=all&Model=all&Year=0';
              }
              $action = 'index.php';
              $keys[] = 'products_id';
          } elseif (SEO_ENABLED == 'true' && basename($PHP_SELF) != FILENAME_ADVANCED_SEARCH_RESULT) {
              $action = tep_href_link($script, tep_get_all_get_params(array('Make', 'Model', 'Year')), 'NONSSL', false);
              $link = tep_href_link($script, tep_get_all_get_params(array('Make', 'Model', 'Year')) . 'Make=all&Model=all&Year=0', 'NONSSL', false);
              $keys[] = 'cPath';
              $keys[] = 'products_id';
              $keys[] = 'manufacturers_id';
          } else {
              $action = $script;
              $link = $script . '?' . tep_get_all_get_params(array('Make', 'Model', 'Year')) . 'Make=all&Model=all&Year=0';
          }
          reset($_GET);
          while (list($key, $value) = each($_GET)) {
              if (!in_array($key, $keys))
                  $hidden_get_variables .= tep_draw_hidden_field($key, $value);
          }
          $info_box_contents = array();
          $info_box_contents[] = array("text" => $javascript);
          $info_box_contents[] = array('form' => tep_draw_form('make_model_year', $action, 'get'), 'text' => tep_draw_pull_down_menu('Make', $Make_array, (isset($Make_selected_var) ? $Make_selected_var : ''), 'onchange="pop_model();"  style="width: 100%"') . '<br /><br />' . '<span id="model_select">' . tep_draw_pull_down_menu('Model', $Model_array, (isset($Model_selected_var) ? $Model_selected_var : ''), 'onchange="pop_year();" style="width: 100%"') . '</span><br /><br />' . '<span id="year_select">' . tep_draw_pull_down_menu('Year', $Year_array, (isset($Year_selected_var) ? $Year_selected_var : ''), 'onchange="document.make_model_year.submit();" style="width: 100%"') . '</span><br /><br />' . $hidden_get_variables . tep_hide_session_id() . '<input class="button" type="submit" value="Go" />&nbsp;&nbsp;&nbsp;<a href="javascript: void(0)" onclick="clear_vehicle();return false">Clear Vehicle</a>');
          new infoBox($info_box_contents);
?>
      </div>
    </div>
  </div>
</div>
<!-- year make model_eof //-->
<?php
      }
?>
<?php
  }
?>