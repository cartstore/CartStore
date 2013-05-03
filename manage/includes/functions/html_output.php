<?php
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL')
  {
      if ($page == '') {
          die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
      }
      if ($connection == 'NONSSL') {
          $link = HTTP_SERVER . DIR_WS_ADMIN;
      } elseif ($connection == 'SSL') {
          if (ENABLE_SSL == 'true') {
              $link = HTTPS_SERVER . DIR_WS_ADMIN;
          } else {
              $link = HTTP_SERVER . DIR_WS_ADMIN;
          }
      } else {
          die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
      }
      if ($parameters == '') {
          $link = $link . $page . '?' . SID;
      } else {
          $link = $link . $page . '?' . $parameters . '&' . SID;
      }
      while ((substr($link, -1) == '&') || (substr($link, -1) == '?'))
          $link = substr($link, 0, -1);
      return $link;
  }
  function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL')
  {
      if ($connection == 'NONSSL') {
          $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
      } elseif ($connection == 'SSL') {
          if (ENABLE_SSL_CATALOG == 'true') {
              $link = HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;
          } else {
              $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
          }
      } else {
          die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
      }
      if ($parameters == '') {
          $link .= $page;
      } else {
          $link .= $page . '?' . $parameters;
      }
      while ((substr($link, -1) == '&') || (substr($link, -1) == '?'))
          $link = substr($link, 0, -1);
      return $link;
  }
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '')
  {
      $image = '<img class="imageborder" src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';
      if (tep_not_null($alt)) {
          $image .= ' title=" ' . tep_output_string($alt) . ' "';
      }
      if (tep_not_null($width) && tep_not_null($height)) {
          $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
      }
      if (tep_not_null($parameters))
          $image .= ' ' . $parameters;
      $image .= '>';
      return $image;
  }
  function tep_image_submit($image, $alt = '', $parameters = '')
  {
      global $language;
      $image_submit = '<input type="submit" class="button" value="' . tep_output_string($alt) . '"';
      if (tep_not_null($alt))
          $image_submit .= '';
      if (tep_not_null($parameters))
          $image_submit .= ' ' . $parameters;
      $image_submit .= '>';
      return $image_submit;
  }
  function tep_black_line()
  {
      return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1')
  {
      return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }
  function tep_image_button($image, $alt = '', $params = '')
  {
      global $language;
      return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
  }
  function tep_js_zone_list($country, $form, $field)
  {
      $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
      $num_country = 1;
      $output_string = '';
      while ($countries = tep_db_fetch_array($countries_query)) {
          if ($num_country == 1) {
              $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
          } else {
              $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
          }
          $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");
          $num_state = 1;
          while ($states = tep_db_fetch_array($states_query)) {
              if ($num_state == '1')
                  $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
              $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
              $num_state++;
          }
          $num_country++;
      }
      $output_string .= '  } else {' . "\n" . '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" . '  }' . "\n";
      return $output_string;
  }
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '')
  {
      $form = '<form name="' . tep_output_string($name) . '" action="';
      if (tep_not_null($parameters)) {
          $form .= tep_href_link($action, $parameters);
      } else {
          $form .= tep_href_link($action);
      }
      $form .= '" method="' . tep_output_string($method) . '"';
      if (tep_not_null($params)) {
          $form .= ' ' . $params;
      }
      $form .= '>';
      return $form;
  }
  function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true)
  {
      $field = '<input class="inputbox" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
      if (isset($GLOBALS[$name]) && ($reinsert_value == true) && is_string($GLOBALS[$name])) {
          $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
      } elseif (tep_not_null($value)) {
          $field .= ' value="' . tep_output_string($value) . '"';
      }
      if (tep_not_null($parameters))
          $field .= ' ' . $parameters;
      $field .= '>';
      if ($required == true)
          $field .= TEXT_FIELD_REQUIRED;
      return $field;
  }
  function tep_draw_password_field($name, $value = '', $required = false)
  {
      $field = tep_draw_input_field($name, $value, 'maxlength="40"', $required, 'password', false);
      return $field;
  }
  function tep_draw_file_field($name, $required = false)
  {
      $field = tep_draw_input_field($name, '', '', $required, 'file');
      return $field;
  }
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '')
  {
      $selection = '<input type="' . $type . '" name="' . $name . '"';
      if ($value != '') {
          $selection .= ' value="' . $value . '"';
      }
      if (($checked == true) || ($GLOBALS[$name] == 'on') || ($value && ($GLOBALS[$name] == $value)) || ($value && ($value == $compare))) {
          $selection .= ' CHECKED';
      }
      if ($parameter != '') {
          $selection .= ' ' . $parameter;
      }
      $selection .= '>';
      return $selection;
  }
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '')
  {
      return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
  }
  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '')
  {
      return tep_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
  }
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true)
  {
      $field = '<textarea class="inputbox"  name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
      if (tep_not_null($parameters))
          $field .= ' ' . $parameters;
      $field .= '>';
      if ((isset($GLOBALS[$name])) && ($reinsert_value == true)) {
          $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
      } elseif (tep_not_null($text)) {
          $field .= tep_output_string_protected($text);
      }
      $field .= '</textarea>';
      return $field;
  }



  function tep_draw_textbox_field($name, $size, $numchar, $value = '', $params = '', $reinsert_value = true)
  {
      $field = '<input type="text" name="' . $name . '" size="' . $size . '" maxlength="' . $numchar . '" value="';
      if ($params)
          $field .= '' . $params;
      $field .= '';
      if (($GLOBALS[$name]) && ($reinsert_value)) {
          $field .= $GLOBALS[$name];
      } elseif ($value != '') {
          $field .= trim($value);
      } else {
          $field .= trim($GLOBALS[$name]);
      }
      $field .= '">';
      return $field;
  }

  function tep_draw_textarea_field_ckeditor($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true)
  {
      $field = '<textarea class="ckeditor"  name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
      if (tep_not_null($parameters))
          $field .= ' ' . $parameters;
      $field .= '>';
      if ((isset($GLOBALS[$name])) && ($reinsert_value == true)) {
          $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
      } elseif (tep_not_null($text)) {
          $field .= tep_output_string_protected($text);
      }
      $field .= '</textarea>';
      return $field;
  }
  function tep_draw_hidden_field($name, $value = '', $parameters = '')
  {
      $field = '<input type="hidden" name="' . tep_output_string($name) . '"';
      if (tep_not_null($value)) {
          $field .= ' value="' . tep_output_string($value) . '"';
      } elseif (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) {
          $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
      }
      if (tep_not_null($parameters))
          $field .= ' ' . $parameters;
      $field .= '>';
      return $field;
  }
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false)
  {
      $field = '<select class="inputbox" name="' . tep_output_string($name) . '"';
      if (tep_not_null($parameters))
          $field .= ' ' . $parameters;
      $field .= '>';
      if (empty($default) && isset($GLOBALS[$name]))
          $default = stripslashes($GLOBALS[$name]);
      for ($i = 0, $n = sizeof($values); $i < $n; $i++) {
          $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
          if ($default == $values[$i]['id']) {
              $field .= ' SELECTED';
          }
          $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => 'r')) . '</option>';
      }
      $field .= '</select>';
      if ($required == true)
          $field .= TEXT_FIELD_REQUIRED;
      return $field;
  }
// Output a form muliple select menu
  function tep_draw_mselect_menu($name, $values, $selected_vals, $params = '', $required = false) {
    $field = '<select name="' . $name . '"';
    if ($params) $field .= ' ' . $params;
    $field .= ' multiple="multiple">';
    for ($i=0; $i<sizeof($values); $i++) {
    if ($values[$i]['id'])
    {
          $field .= '<option value="' . $values[$i]['id'] . '"';
          if ( ((strlen($values[$i]['id']) > 0) && ($GLOBALS[$name] == $values[$i]['id'])) ) {
            $field .= '  selected="selected"';
          }
            else
        {
            for ($j=0; $j<sizeof($selected_vals); $j++) {
                if ($selected_vals[$j]['id'] == $values[$i]['id'])
                {
                    $field .= ' selected="selected"';
                }
            }
        }
    }
      $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';

    if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }


  function tep_get_country_list($name, $selected = '', $parameters = '')
  {
      $countries = tep_get_countries();
      return tep_draw_pull_down_menu($name, $countries, $selected, $parameters);
  }
  
////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null) {
    static $button_counter = 1;

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($priority)) {
      $priority = 'secondary';
    }

    $button = '<span class="tdbLink">';

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button id="tdb' . $button_counter . '" type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= '>' . $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button .= '</span><script type="text/javascript">$("#tdb' . $button_counter . '").button(';

    $args = array();

    if ( isset($icon) ) {
      if ( !isset($params['iconpos']) ) {
        $params['iconpos'] = 'left';
      }

      if ( $params['iconpos'] == 'left' ) {
        $args[] = 'icons:{primary:"ui-icon-' . $icon . '"}';
      } else {
        $args[] = 'icons:{secondary:"ui-icon-' . $icon . '"}';
      }
    }

    if (empty($title)) {
      $args[] = 'text:false';
    }

    if (!empty($args)) {
      $button .= '{' . implode(',', $args) . '}';
    }

    $button .= ').addClass("ui-priority-' . $priority . '").parent().removeClass("tdbLink");</script>';

    $button_counter++;

    return $button;
  }
  
?>