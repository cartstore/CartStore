<?php
  if (isset($currencies) && is_object($currencies)) {
?>
<!-- currencies //-->

<div class="module">
  <div>
    <div>
      <div>
        <h3>CURRENCY SELECTOR</h3>
        <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_CURRENCIES);
      new infoBoxHeading($info_box_contents, false, false);
      reset($currencies->currencies);
      $currencies_array = array();
      while (list($key, $value) = each($currencies->currencies)) {
          $currencies_array[] = array('id' => $key, 'text' => $value['title']);
      }
      $hidden_get_variables = '';
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
          if (($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y')) {
              $hidden_get_variables .= tep_draw_hidden_field($key, $value);
          }
      }
      $info_box_contents = array();
      $info_box_contents[] = array('form' => tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get'), 'align' => '', 'text' => tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();"') . $hidden_get_variables . tep_hide_session_id());
      new infoBox($info_box_contents);
?>
      </div>
    </div>
  </div>
</div>
<!-- currencies_eof //-->
<?php
  }
?>