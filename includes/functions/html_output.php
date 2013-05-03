<?php
/*
  $Id: html_output.php,v 1.56 2003/07/09 01:15:48 hpdl Exp $
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible
*/
////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
      global $seo_urls;
      if (!is_object($seo_urls)) {
          if (!class_exists('SEO_URL')) {
              include_once(DIR_WS_CLASSES . 'seo.class.php');
        }
          global $languages_id;
          $seo_urls = new SEO_URL($languages_id);
      }
      return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  }
////
// The HTML image wrapper function
// BOF Image Magic



function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
  global $product_info;


  //Allow for a new intermediate sized thumbnail size to be set
  //without any changes having to be made to the product_info page itself.
  //(see the lengths I go to to make your life easier :-)
  $page = '';
  $fix_png = false;
  $over_ride = false;
  if (strstr($_SERVER['PHP_SELF'],"product_info.php")) {

        if (isset($product_info['products_image'])
                   && $src == DIR_WS_IMAGES . $product_info['products_image']
                   && $product_info[products_id]==$_GET['products_id']
				   && IS_MOBILE_DEVICE == FALSE
				   
				   
				   )  
                   

                   
                   
                   {   //final check just to make sure that we don't interfere with other contribs
            $width = PRODUCT_INFO_IMAGE_WIDTH == 0?'':PRODUCT_INFO_IMAGE_WIDTH;
            $height = PRODUCT_INFO_IMAGE_HEIGHT == 0?'':PRODUCT_INFO_IMAGE_HEIGHT;
            $product_info_image=true;
            $page="prod_info";
        }
		 if (isset($extra_images['products_extra_image'])
                   && $src == DIR_WS_IMAGES . $extra_images['products_extra_image']
                   && $product_info[products_id]==$_GET['products_id'])  {   //final check just to make sure that we don't interfere with other contribs
            $width = PRODUCT_INFO_IMAGE_WIDTH == 0?'':PRODUCT_INFO_IMAGE_WIDTH;
            $height = PRODUCT_INFO_IMAGE_HEIGHT == 0?'':PRODUCT_INFO_IMAGE_HEIGHT;
            $product_info_image=true;
            $page="prod_info";
        }
  }

  //Detect whether this is a pop-up image
  if (strstr($_SERVER['PHP_SELF'],"popup_image.php")) $page="popup";

  //do we apply the IE PNG alpha transparency fix?
  if  (strstr(strtolower($src),".png") && CFG_PNG_BUG=="True") $fix_png = true;

  //send the image for processing unless told otherwise
  $image = '<img src="' . $src . '"'; //set up the image tag just in case we don't want to process
  if (CFG_MASTER_SWITCH=="On") $calculate = true;
  else $calculate=false;

  // Don't calculate if the image is set to a "%" width
  if (strstr($width,'%') == true || strstr($height,'%') == true) $calculate = false;
  // Dont calculate if a pixel image is being passed (hope you dont have pixels for sale)
  if (strstr($image, 'pixel')) $calculate = false;


  $image_size = @getimagesize($src);

  // Decide whether or not we want to process this image
  if (($width == '' && $height == '' && $page != 'popup' ) || ($width == $image_size[0] && $height == $image_size[0] && $page != 'popup')) {
        if (defined("CFG_PROCESS_GRAPHICS") && CFG_PROCESS_GRAPHICS=="False") $calculate = false; //looks like this is a store graphic rather than product image
  }
  // Is this image good to go?
  if (CONFIG_CALCULATE_IMAGE_SIZE && $calculate) {

  if ($image_size) {

      $ratio = $image_size[1] / $image_size[0];

      // Set the width and height to the proper ratio
      if (!$width && $height) {
        $ratio = $height / $image_size[1];
        $width = intval($image_size[0] * $ratio);
      } elseif ($width && !$height) {
        $ratio = $width / $image_size[0];
        $height = intval($image_size[1] * $ratio);
      } elseif (!$width && !$height && !$over_ride) {
        $width = $image_size[0];
        $height = $image_size[1];
      }
      //Encrypt the image filename if switched on
        if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY !="") {
              $result = '';
              $key=CFG_ENCRYPTION_KEY;
              for($i=0; $i<strlen($src); $i++) {
                  $char = substr($src, $i, 1);
                  $keychar = substr($key, ($i % strlen($key))-1, 1);
                  $char = chr(ord($char)+ord($keychar));
                  $result.=$char;
              }
              $src=urlencode(base64_encode($result));
        }

       //Return the html
        $image = '<img class="imageborder" src="imagemagic.php?img='.$src.'&amp;w='.
        tep_output_string($width).'&amp;h='.tep_output_string($height).'&amp;page='.$page.'"';

    } elseif (IMAGE_REQUIRED == 'false') {
      return false;
    }
  }

    //If the size asked for is greater than the image itself, we check the configs to see if this is allowed and if not over-ride
  if ($width > $image_size[0] || $height > $image_size[1]) {
        if (CFG_ALLOW_LARGER  != 'True'){
              $width=$image_size[0];
              $height=$image_size[1];
              $over_ride = true;
        }
  }
  // Add remaining image parameters if they exist
  if ($width) {
    $image .= ' width="' . tep_output_string($width) . '"';
  }

  if ($height) {
    $image .= ' height="' . tep_output_string($height) . '"';
  }

  if (tep_not_null($params)) $image .= ' ' . $params;

  $image .= ' border="0" alt="' . tep_output_string($alt) . '"';

  if (tep_not_null($alt)) {
    $image .= ' title="' . tep_output_string($alt) . '"';
  }

  if ($fix_png && CFG_MASTER_SWITCH=="On") {
        $image .= ' onload="fixPNG(this)"';
  }

  $image .= ' />';
  return $image;
}
//EOF Image Magic

function tep_image_r_path($src, $alt = '', $width = '', $height = '', $params = '') {
  global $product_info;

  //Allow for a new intermediate sized thumbnail size to be set
  //without any changes having to be made to the product_info page itself.
  //(see the lengths I go to to make your life easier :-)
  if (strstr($_SERVER['PHP_SELF'],"product_info.php")) {
             $width = PRODUCT_INFO_IMAGE_WIDTH == 0?'':PRODUCT_INFO_IMAGE_WIDTH;
            $height = PRODUCT_INFO_IMAGE_HEIGHT == 0?'':PRODUCT_INFO_IMAGE_HEIGHT;
            $product_info_image=true;
            $page="prod_info";

  }

  //Detect whether this is a pop-up image
  if (strstr($_SERVER['PHP_SELF'],"popup_image.php")) $page="popup";

  //do we apply the IE PNG alpha transparency fix?
  if  (strstr(strtolower($src),".png") && CFG_PNG_BUG=="True") $fix_png = true;

  //send the image for processing unless told otherwise
  $image = "' . $src . '"; //set up the image tag just in case we don't want to process
  if (CFG_MASTER_SWITCH=="On") $calculate = true;
  else $calculate=false;

  // Don't calculate if the image is set to a "%" width
  if (strstr($width,'%') == true || strstr($height,'%') == true) $calculate = false;
  // Dont calculate if a pixel image is being passed (hope you dont have pixels for sale)
  if (strstr($image, 'pixel')) $calculate = false;


  $image_size = @getimagesize($src);

  // Decide whether or not we want to process this image
  if (($width == '' && $height == '' && $page != 'popup' ) || ($width == $image_size[0] && $height == $image_size[0] && $page != 'popup')) {
        if (defined("CFG_PROCESS_GRAPHICS") && CFG_PROCESS_GRAPHICS=="False") $calculate = false; //looks like this is a store graphic rather than product image
  }
  // Is this image good to go?
  if (CONFIG_CALCULATE_IMAGE_SIZE && $calculate) {

  if ($image_size) {

      $ratio = $image_size[1] / $image_size[0];

      // Set the width and height to the proper ratio
      if (!$width && $height) {
        $ratio = $height / $image_size[1];
        $width = intval($image_size[0] * $ratio);
      } elseif ($width && !$height) {
        $ratio = $width / $image_size[0];
        $height = intval($image_size[1] * $ratio);
      } elseif (!$width && !$height && !$over_ride) {
        $width = $image_size[0];
        $height = $image_size[1];
      }
      //Encrypt the image filename if switched on
        if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY !="") {
              $result = '';
              $key=CFG_ENCRYPTION_KEY;
              for($i=0; $i<strlen($src); $i++) {
                  $char = substr($src, $i, 1);
                  $keychar = substr($key, ($i % strlen($key))-1, 1);
                  $char = chr(ord($char)+ord($keychar));
                  $result.=$char;
              }
              $src=urlencode(base64_encode($result));
        }

       //Return the html
        $image111 = 'imagemagic.php?img='.$src.'&amp;w='.
        tep_output_string($width).'&amp;h='.tep_output_string($height).'&amp;page='.$page.'';

    } elseif (IMAGE_REQUIRED == 'false') {
      return false;
    }
  }

    //If the size asked for is greater than the image itself, we check the configs to see if this is allowed and if not over-ride
  if ($width > $image_size[0] || $height > $image_size[1]) {
        if (CFG_ALLOW_LARGER  != 'True'){
              $width=$image_size[0];
              $height=$image_size[1];
              $over_ride = true;
        }
  }
  // Add remaining image parameters if they exist
  if ($width) {
    $image .= ' width="' . tep_output_string($width) . '"';
  }

  if ($height) {
    $image .= ' height="' . tep_output_string($height) . '"';
  }

  if (tep_not_null($params)) $image .= ' ' . $params;

  $image .= ' border="0" alt="' . tep_output_string($alt) . '"';

  if (tep_not_null($alt)) {
    $image .= ' title="' . tep_output_string($alt) . '"';
  }

  if ($fix_png && CFG_MASTER_SWITCH=="On") {
        $image .= ' onload="fixPNG(this)"';
  }

  $image .= ' />';
  return $image111;
}
//EOF Image Magic
////
////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;
$image_submit = '<input class="button btn btn-large" type="submit" value="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';
    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;
    $image_submit .= ' />';
    return $image_submit;
  }
 function tep_image_submit2($image, $alt = '', $parameters = '') {
    global $language;
$image_submit = '<input  type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" value="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';
    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;
    $image_submit .= ' />';
    return $image_submit;
  }
////
// Output a function button in the selected language
 function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;
    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters ,'');
  }
////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }
////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($parameters)) $form .= ' ' . $parameters;
    $form .= '>';
    return $form;
  }
////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= ' />';
	if (preg_match('/class="/',$field)){
		$field = preg_replace('/class="/','class="inputbox ',$field);
	} else {
		$field = str_replace('type="','class="inputbox" type="',$field);
	}
    return $field;
  }
////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input id="checkbox" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {
      $selection .= ' checked="checked"';
    }
    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;
    $selection .= ' />';
	$field = $selection;
	if (preg_match('/class="/',$field)){
		$field = preg_replace('/class="/','class="inputcheckbox ',$field);
	} else {
		$field = str_replace('type="','class="inputcheckbox" type="',$field);
	}
    return $field;
  }
////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }
////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }
////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea  name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }
    $field .= '</textarea>';
    return $field;
  }



function tep_draw_textarea_field2($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea  class="ckeditor" name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }
    $field .= '</textarea>';
    return $field;
  }
////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';
    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= ' />';
    return $field;
  }
////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;
    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }
////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select id="' . tep_output_string($name) . '" name="' . tep_output_string($name) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }
      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';
	if (preg_match('/class="/',$field)){
		$field = preg_replace('/class="/','class="inputbox ',$field);
	} else {
		$field = str_replace('type="','class="inputbox" type="',$field);
	}
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }
////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    // bof jefs42 mod - add default countries to top of select
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries_array[] = array('id' => '--', 'text' => "--------------");
    $countries_array[] = array('id' => STORE_COUNTRY, 'text' => tep_get_country_name(STORE_COUNTRY));
    // add additional "popular" countries
    $country_list = array(223, 222, 38, 13, 103, 99); // array of country id's to list (default country will be skipped if present
    foreach ($country_list as $country_id){
      if ($country_id != STORE_COUNTRY)
         $countries_array[] = array('id' => $country_id, 'text' => tep_get_country_name($country_id));
    }
    $countries_array[] = array('id' => '--', 'text' => "--------------");
    // eof jefs42 mod

    $countries = tep_get_countries();
    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }
    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
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
