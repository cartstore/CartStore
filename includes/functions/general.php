<?php


  include(DIR_WS_FUNCTIONS . 'register_globals.php');

  function tepps_special_product($query)
  {
      $random_product = '';
      $random_query = tep_db_query($query);
      $num_rows = tep_db_num_rows($random_query);
      if ($num_rows > 0) {
          while ($ran = tep_db_fetch_array($random_query)) {
              $random_product[] = $ran;
          }
      }
      return $random_product;
  }


  function tep_get_slot($slotid)
  {
      $tot_query = tep_db_query("select slot from sw_time_slots where slotid=$slotid");
      $tot_res = tep_db_fetch_array($tot_query);
      return $tot_res['slot'];
  }



  function tep_exit()
  {
      tep_session_close();
      exit();
  }


  function tep_redirect($url)
  {
      if ((strstr($url, "\n") != false) || (strstr($url, "\r") != false)) {
          tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
      }
      if ((ENABLE_SSL == true) && (getenv('HTTPS') == 'on')) {

          if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) {


              $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER));
          }
      }
      header('Location: ' . $url);
      tep_exit();
  }


  function tep_parse_input_field_data($data, $parse)
  {
      return strtr(trim($data), $parse);
  }
  function tep_output_string($string, $translate = false, $protected = false)
  {
      if ($protected == true) {
          return htmlspecialchars($string);
      } else {
          if ($translate == false) {
              return tep_parse_input_field_data($string, array('"' => '&quot;'));
          } else {
              return tep_parse_input_field_data($string, $translate);
          }
      }
  }
  function tep_output_string_protected($string)
  {
      return tep_output_string($string, false, true);
  }
  function tep_sanitize_string($string)
  {
      $string = preg_replace('/ +/', ' ', trim($string));
   return ($string);
  }


  function tep_random_select($query)
  {
      $random_product = '';
      $random_query = tep_db_query($query);
      $num_rows = tep_db_num_rows($random_query);
      if ($num_rows > 0) {
          $random_row = tep_rand(0, ($num_rows - 1));
          tep_db_data_seek($random_query, $random_row);
          $random_product = tep_db_fetch_array($random_query);
      }
      return $random_product;
  }



  function tep_get_products_name($product_id, $language = '')
  {
      global $languages_id;
      if (empty($language))
          $language = $languages_id;
      $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
      $product = tep_db_fetch_array($product_query);
      return $product['products_name'];
  }
  function tep_get_products_short_des($product_id, $language = '')
  {
      global $languages_id;
      if (empty($language))
          $language = $languages_id;
      $product_query = tep_db_query("select products_short from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
      $product = tep_db_fetch_array($product_query);
      return $product['products_short'];
  }



  function tep_get_products_special_price($product_id)
  {

      global $sppc_customer_group_id;
      if (!tep_session_is_registered('sppc_customer_group_id')) {
          $customer_group_id = '0';
      } else {
          $customer_group_id = $sppc_customer_group_id;
      }
      $product_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status and customers_group_id = '" . (int)$customer_group_id . "'");

      $product = tep_db_fetch_array($product_query);
      return $product['specials_new_products_price'];
  }





  function tep_get_products_stock($products_id, $attributes = array())
  {
      global $languages_id;
      $products_id = tep_get_prid($products_id);


      if (sizeof($attributes) > 0) {
          $all_nonstocked = true;
          $attr_list = '';
          $options_list = implode(",", array_keys($attributes));
          $track_stock_query = tep_db_query("select products_options_id, products_options_track_stock from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id in ($options_list) and language_id= '" . (int)$languages_id . "' order by products_options_id");
          while ($track_stock_array = tep_db_fetch_array($track_stock_query)) {
              if ($track_stock_array['products_options_track_stock']) {
                  $attr_list .= $track_stock_array['products_options_id'] . '-' . $attributes[$track_stock_array['products_options_id']] . ',';
                  $all_nonstocked = false;
              }
          }
          $attr_list = substr($attr_list, 0, strlen($attr_list) - 1);
      }
      if ((sizeof($attributes) == 0) | ($all_nonstocked)) {
          $stock_query = tep_db_query("select products_quantity as quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
      } else {
          $stock_query = tep_db_query("select products_stock_quantity as quantity from " . TABLE_PRODUCTS_STOCK . " where products_id='" . (int)$products_id . "' and products_stock_attributes='$attr_list'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
          $stock = tep_db_fetch_array($stock_query);
          $quantity = $stock['quantity'];
      } else {
          $quantity = 0;
      }
      return $quantity;

  }




  function tep_check_stock($products_id, $products_quantity, $attributes = array())
  {
      $stock_left = tep_get_products_stock($products_id, $attributes) - $products_quantity;

      $out_of_stock = '';
      if ($stock_left < 0) {
          $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
      }
      return $out_of_stock;
  }


  function tep_break_string($string, $len, $break_char = '-')
  {
      $l = 0;
      $output = '';
      for ($i = 0, $n = strlen($string); $i < $n; $i++) {
          $char = substr($string, $i, 1);
          if ($char != ' ') {
              $l++;
          } else {
              $l = 0;
          }
          if ($l > $len) {
              $l = 1;
              $output .= $break_char;
          }
          $output .= $char;
      }
      return $output;
  }


  function tep_get_all_get_params($exclude_array = '')
  {
      global $_GET;
      if (!is_array($exclude_array))
          $exclude_array = array();
      $get_url = '';
      if (is_array($_GET) && (sizeof($_GET) > 0)) {
          reset($_GET);
          while (list($key, $value) = each($_GET)) {
              if ((strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y')) {
                  $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
              }
          }
      }
      return $get_url;
  }



  function tep_get_countries($countries_id = '', $with_iso_codes = false)
  {
      $countries_array = array();
      if (tep_not_null($countries_id)) {
          if ($with_iso_codes == true) {
              $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
              $countries_values = tep_db_fetch_array($countries);
              $countries_array = array('countries_name' => $countries_values['countries_name'], 'countries_iso_code_2' => $countries_values['countries_iso_code_2'], 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
          } else {
              $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
              $countries_values = tep_db_fetch_array($countries);
              $countries_array = array('countries_name' => $countries_values['countries_name']);
          }
      } else {
          $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
          while ($countries_values = tep_db_fetch_array($countries)) {
              $countries_array[] = array('countries_id' => $countries_values['countries_id'], 'countries_name' => $countries_values['countries_name']);
          }
      }
      return $countries_array;
  }


  function tep_get_countries_with_iso_codes($countries_id)
  {
      return tep_get_countries($countries_id, true);
  }


  function tep_get_path($current_category_id = '')
  {
      global $cPath_array;
      if (tep_not_null($current_category_id)) {
          $cp_size = sizeof($cPath_array);
          if ($cp_size == 0) {
              $cPath_new = $current_category_id;
          } else {
              $cPath_new = '';
              $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size - 1)] . "'");
              $last_category = tep_db_fetch_array($last_category_query);
              $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
              $current_category = tep_db_fetch_array($current_category_query);
              if ($last_category['parent_id'] == $current_category['parent_id']) {
                  for ($i = 0; $i < ($cp_size - 1); $i++) {
                      $cPath_new .= '_' . $cPath_array[$i];
                  }
              } else {
                  for ($i = 0; $i < $cp_size; $i++) {
                      $cPath_new .= '_' . $cPath_array[$i];
                  }
              }
              $cPath_new .= '_' . $current_category_id;
              if (substr($cPath_new, 0, 1) == '_') {
                  $cPath_new = substr($cPath_new, 1);
              }
          }
      } else {
          $cPath_new = implode('_', $cPath_array);
      }
      return 'cPath=' . $cPath_new;
  }


  function tep_browser_detect($component)
  {


      return stristr($_SERVER['HTTP_USER_AGENT'], $component);
  }


  function tep_get_country_name($country_id)
  {
      $country_array = tep_get_countries($country_id);
      return $country_array['countries_name'];
  }



  function tep_get_zone_name($country_id, $zone_id, $default_zone)
  {
      $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
      if (tep_db_num_rows($zone_query)) {
          $zone = tep_db_fetch_array($zone_query);
          return $zone['zone_name'];
      } else {
          return $default_zone;
      }
  }



  function tep_get_zone_code($country_id, $zone_id, $default_zone)
  {
      $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
      if (tep_db_num_rows($zone_query)) {
          $zone = tep_db_fetch_array($zone_query);
          return $zone['zone_code'];
      } else {
          return $default_zone;
      }
  }


  function tep_round($number, $precision)
  {
      if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.') + 1)) > $precision)) {
          $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);
          if (substr($number, -1) >= 5) {
              if ($precision > 1) {
                  $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision - 1) . '1');
              } elseif ($precision == 1) {
                  $number = substr($number, 0, -1) + 0.1;

              } else {
                  $number = substr($number, 0, -1) + 1;
              }
          } else {
              $number = substr($number, 0, -1);
          }
      }
      return $number;
  }



  function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
  {


      global $customer_zone_id, $customer_country_id, $sppc_customer_group_tax_exempt;
      if (!tep_session_is_registered('sppc_customer_group_tax_exempt')) {
          $customer_group_tax_exempt = '0';
      } else {
          $customer_group_tax_exempt = $sppc_customer_group_tax_exempt;
      }
      if ($customer_group_tax_exempt == '1') {
          return 0;
      }

      if (($country_id == -1) && ($zone_id == -1)) {
          if (!tep_session_is_registered('customer_id')) {
              $country_id = STORE_COUNTRY;
              $zone_id = STORE_ZONE;
          } else {
              $country_id = $customer_country_id;
              $zone_id = $customer_zone_id;
          }
      }
      $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
      if (tep_db_num_rows($tax_query)) {
          $tax_multiplier = 1.0;
          while ($tax = tep_db_fetch_array($tax_query)) {
              $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
          }
          return($tax_multiplier - 1.0) * 100;
      } else {
          return 0;
      }
  }



  function tep_get_tax_description($class_id, $country_id, $zone_id)
  {
      $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
      if (tep_db_num_rows($tax_query)) {
          $tax_description = '';
          while ($tax = tep_db_fetch_array($tax_query)) {
              $tax_description .= $tax['tax_description'] . ' + ';
          }
          $tax_description = substr($tax_description, 0, -3);
          return $tax_description;
      } else {
          return TEXT_UNKNOWN_TAX_RATE;
      }
  }


  function tep_add_tax($price, $tax)
  {
      global $currencies;




      global $sppc_customer_group_show_tax;
      global $sppc_customer_group_tax_exempt;
      if (!tep_session_is_registered('sppc_customer_group_show_tax')) {
          $customer_group_show_tax = '1';
      } else {
          $customer_group_show_tax = $sppc_customer_group_show_tax;
      }


      if ((DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) && ($customer_group_show_tax == '1')) {

          return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
      } else {
          return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
      }
  }

  function tep_calculate_tax($price, $tax)
  {
      global $currencies;
      return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }



  function tep_count_products_in_category($category_id, $include_inactive = false)
  {
      global $YMM_where;
      $products_count = 0;
      if ($include_inactive == true) {
          $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where " . (YMM_FILTER_COUNT_PRODUCTS_IN_CATEGORY == 'Yes' ? $YMM_where : '') . " p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
      } else {
          $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where " . (YMM_FILTER_COUNT_PRODUCTS_IN_CATEGORY == 'Yes' ? $YMM_where : '') . " p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
      }
      $products = tep_db_fetch_array($products_query);
      $products_count += $products['total'];
      $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
      if (tep_db_num_rows($child_categories_query)) {
          while ($child_categories = tep_db_fetch_array($child_categories_query)) {
              $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
          }
      }
      return $products_count;
  }



  function tep_has_category_subcategories($category_id)
  {
      $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
      $child_category = tep_db_fetch_array($child_category_query);
      if ($child_category['count'] > 0) {
          return true;
      } else {
          return false;
      }
  }



  function tep_get_address_format_id($country_id)
  {
      $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
      if (tep_db_num_rows($address_format_query)) {
          $address_format = tep_db_fetch_array($address_format_query);
          return $address_format['format_id'];
      } else {
          return '1';
      }
  }



  function tep_address_format($address_format_id, $address, $html, $boln, $eoln)
  {
      $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
      $address_format = tep_db_fetch_array($address_format_query);
      $company = tep_output_string_protected($address['company']);
      if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
          $firstname = tep_output_string_protected($address['firstname']);
          $lastname = tep_output_string_protected($address['lastname']);
      } elseif (isset($address['name']) && tep_not_null($address['name'])) {
          $firstname = tep_output_string_protected($address['name']);
          $lastname = '';
      } else {
          $firstname = '';
          $lastname = '';
      }
      $street = tep_output_string_protected($address['street_address']);
      // Second Address Field mod:
    $street_2 = tep_output_string_protected($address['street_address_2']);
// :Second Address Field mod
      $suburb = tep_output_string_protected($address['suburb']);
      $city = tep_output_string_protected($address['city']);
      $state = tep_output_string_protected($address['state']);
      if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
          $country = tep_get_country_name($address['country_id']);
          if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
              $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
          }
      } elseif (isset($address['country']) && tep_not_null($address['country'])) {
          $country = tep_output_string_protected($address['country']);
      } else {
          $country = '';
      }
      $postcode = tep_output_string_protected($address['postcode']);
      $zip = $postcode;
      if ($html) {

          $HR = '<hr>';
          $hr = '<hr>';
          if (($boln == '') && ($eoln == "\n")) {

              $CR = '<br>';
              $cr = '<br>';
              $eoln = $cr;
          } else {

              $CR = $eoln . $boln;
              $cr = $CR;
          }
      } else {

          $CR = $eoln;
          $cr = $CR;
          $HR = '----------------------------------------';
          $hr = '----------------------------------------';
      }
      $statecomma = '';
      $streets = $street;
      // Second Address Field mod:
    if ($street_2 != '') $streets = $streets . $cr . $street_2;
// :Second Address Field mod
      if ($suburb != '')
          $streets = $street . $cr . $suburb;
      if ($country == '')
          $country = tep_output_string_protected($address['country']);
      if ($state != '')
          $statecomma = $state . ', ';
      $fmt = $address_format['format'];
      eval("\$address = \"$fmt\";");
      if ((ACCOUNT_COMPANY == 'true') && (tep_not_null($company))) {
          $address = $company . $cr . $address;
      }
      return $address;
  }



  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n")
  {

      if ($customers_id == 0) {
          global $order;
          if ($address_id == 1) {
              $address = $order->pwa_label_shipping;
          } else {
              $address = $order->pwa_label_customer;
          }
      } else {
          $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address,entry_street_address_2 as street_address_2,  entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
          $address = tep_db_fetch_array($address_query);
      }

      $format_id = tep_get_address_format_id($address['country_id']);
      return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }
  function tep_row_number_format($number)
  {
      if (($number < 10) && (substr($number, 0, 1) != '0'))
          $number = '0' . $number;
      return $number;
  }
  function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '')
  {
      global $languages_id;
      if (!is_array($categories_array))
          $categories_array = array();
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
      while ($categories = tep_db_fetch_array($categories_query)) {
          $categories_array[] = array('id' => $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
          if ($categories['categories_id'] != $parent_id) {
              $categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
          }
      }
      return $categories_array;
  }
  function tep_get_manufacturers($manufacturers_array = '')
  {
      if (!is_array($manufacturers_array))
          $manufacturers_array = array();
      $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
      }
      return $manufacturers_array;
  }



  function tep_get_subcategories(&$subcategories_array, $parent_id = 0)
  {
      $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
      while ($subcategories = tep_db_fetch_array($subcategories_query)) {
          $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
          if ($subcategories['categories_id'] != $parent_id) {
              tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
          }
      }
  }


  function tep_date_long($raw_date)
  {
      if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == ''))
          return false;
      $year = (int)substr($raw_date, 0, 4);
      $month = (int)substr($raw_date, 5, 2);
      $day = (int)substr($raw_date, 8, 2);
      $hour = (int)substr($raw_date, 11, 2);
      $minute = (int)substr($raw_date, 14, 2);
      $second = (int)substr($raw_date, 17, 2);
      return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
  }




  function tep_date_short($raw_date)
  {
      if (($raw_date == '0000-00-00 00:00:00') || empty($raw_date))
          return false;
      $year = substr($raw_date, 0, 4);
      $month = (int)substr($raw_date, 5, 2);
      $day = (int)substr($raw_date, 8, 2);
      $hour = (int)substr($raw_date, 11, 2);
      $minute = (int)substr($raw_date, 14, 2);
      $second = (int)substr($raw_date, 17, 2);
      if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
          return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
      } else {
          return preg_replace('/2037' . '$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
      }
  }


  function tep_parse_search_string($search_str = '', &$objects)
  {
      $search_str = trim(strtolower($search_str));

      $pieces = explode('[[:space:]]+', $search_str);
      $objects = array();
      $tmpstring = '';
      $flag = '';
      for ($k = 0; $k < count($pieces); $k++) {
          while (substr($pieces[$k], 0, 1) == '(') {
              $objects[] = '(';
              if (strlen($pieces[$k]) > 1) {
                  $pieces[$k] = substr($pieces[$k], 1);
              } else {
                  $pieces[$k] = '';
              }
          }
          $post_objects = array();
          while (substr($pieces[$k], -1) == ')') {
              $post_objects[] = ')';
              if (strlen($pieces[$k]) > 1) {
                  $pieces[$k] = substr($pieces[$k], 0, -1);
              } else {
                  $pieces[$k] = '';
              }
          }

          if ((substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"')) {
              $objects[] = trim($pieces[$k]);
              for ($j = 0; $j < count($post_objects); $j++) {
                  $objects[] = $post_objects[$j];
              }
          } else {

              $tmpstring = trim(preg_replace('/"/', ' ', $pieces[$k]));

              if (substr($pieces[$k], -1) == '"') {

                  $flag = 'off';
                  $objects[] = trim($pieces[$k]);
                  for ($j = 0; $j < count($post_objects); $j++) {
                      $objects[] = $post_objects[$j];
                  }
                  unset($tmpstring);

                  continue;
              }

              $flag = 'on';

              $k++;

              while (($flag == 'on') && ($k < count($pieces))) {
                  while (substr($pieces[$k], -1) == ')') {
                      $post_objects[] = ')';
                      if (strlen($pieces[$k]) > 1) {
                          $pieces[$k] = substr($pieces[$k], 0, -1);
                      } else {
                          $pieces[$k] = '';
                      }
                  }

                  if (substr($pieces[$k], -1) != '"') {

                      $tmpstring .= ' ' . $pieces[$k];

                      $k++;
                      continue;
                  } else {
                      $tmpstring .= ' ' . trim(preg_replace('/"/', ' ', $pieces[$k]));

                      $objects[] = trim($tmpstring);
                      for ($j = 0; $j < count($post_objects); $j++) {
                          $objects[] = $post_objects[$j];
                      }
                      unset($tmpstring);

                      $flag = 'off';
                  }
              }
          }
      }

      $temp = array();
      for ($i = 0; $i < (count($objects) - 1); $i++) {
          $temp[] = $objects[$i];
          if (($objects[$i] != 'and') && ($objects[$i] != 'or') && ($objects[$i] != '(') && ($objects[$i + 1] != 'and') && ($objects[$i + 1] != 'or') && ($objects[$i + 1] != ')')) {
              $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
          }
      }
      $temp[] = $objects[$i];
      $objects = $temp;
      $keyword_count = 0;
      $operator_count = 0;
      $balance = 0;
      for ($i = 0; $i < count($objects); $i++) {
          if ($objects[$i] == '(')
              $balance--;
          if ($objects[$i] == ')')
              $balance++;
          if (($objects[$i] == 'and') || ($objects[$i] == 'or')) {
              $operator_count++;
          } elseif (($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')')) {
              $keyword_count++;
          }
      }
      if (($operator_count < $keyword_count) && ($balance == 0)) {
          return true;
      } else {
          return false;
      }
  }


  function tep_checkdate($date_to_check, $format_string, &$date_array)
  {
      $separator_idx = -1;
      $separators = array('-', ' ', '/', '.');
      $month_abbr = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
      $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
      $format_string = strtolower($format_string);
      if (strlen($date_to_check) != strlen($format_string)) {
          return false;
      }
      $size = sizeof($separators);
      for ($i = 0; $i < $size; $i++) {
          $pos_separator = strpos($date_to_check, $separators[$i]);
          if ($pos_separator != false) {
              $date_separator_idx = $i;
              break;
          }
      }
      for ($i = 0; $i < $size; $i++) {
          $pos_separator = strpos($format_string, $separators[$i]);
          if ($pos_separator != false) {
              $format_separator_idx = $i;
              break;
          }
      }
      if ($date_separator_idx != $format_separator_idx) {
          return false;
      }
      if ($date_separator_idx != -1) {
          $format_string_array = explode($separators[$date_separator_idx], $format_string);
          if (sizeof($format_string_array) != 3) {
              return false;
          }
          $date_to_check_array = explode($separators[$date_separator_idx], $date_to_check);
          if (sizeof($date_to_check_array) != 3) {
              return false;
          }
          $size = sizeof($format_string_array);
          for ($i = 0; $i < $size; $i++) {
              if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm')
                  $month = $date_to_check_array[$i];
              if ($format_string_array[$i] == 'dd')
                  $day = $date_to_check_array[$i];
              if (($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa'))
                  $year = $date_to_check_array[$i];
          }
      } else {
          if (strlen($format_string) == 8 || strlen($format_string) == 9) {
              $pos_month = strpos($format_string, 'mmm');
              if ($pos_month != false) {
                  $month = substr($date_to_check, $pos_month, 3);
                  $size = sizeof($month_abbr);
                  for ($i = 0; $i < $size; $i++) {
                      if ($month == $month_abbr[$i]) {
                          $month = $i;
                          break;
                      }
                  }
              } else {
                  $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
              }
          } else {
              return false;
          }
          $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
          $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
      }
      if (strlen($year) != 4) {
          return false;
      }
      if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
          return false;
      }
      if ($month > 12 || $month < 1) {
          return false;
      }
      if ($day < 1) {
          return false;
      }
      if (tep_is_leap_year($year)) {
          $no_of_days[1] = 29;
      }
      if ($day > $no_of_days[$month - 1]) {
          return false;
      }
      $date_array = array($year, $month, $day);
      return true;
  }


  function tep_is_leap_year($year)
  {
      if ($year % 100 == 0) {
          if ($year % 400 == 0)
              return true;
      } else {
          if (($year % 4) == 0)
              return true;
      }
      return false;
  }


  function tep_create_sort_heading($sortby, $colnum, $heading)
  {
      global $PHP_SELF;
      $sort_prefix = '';
      $sort_suffix = '';
      if ($sortby) {
          $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">';
          $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
      }
      return $sort_prefix . $heading . $sort_suffix;
  }



  function tep_get_parent_categories(&$categories, $categories_id)
  {
      $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
      while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
          if ($parent_categories['parent_id'] == 0)
              return true;
          $categories[sizeof($categories)] = $parent_categories['parent_id'];
          if ($parent_categories['parent_id'] != $categories_id) {
              tep_get_parent_categories($categories, $parent_categories['parent_id']);
          }
      }
  }



  function tep_get_product_path($products_id)
  {
      $cPath = '';
      $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
      if (tep_db_num_rows($category_query)) {
          $category = tep_db_fetch_array($category_query);
          $categories = array();
          tep_get_parent_categories($categories, $category['categories_id']);
          $categories = array_reverse($categories);
          $cPath = implode('_', $categories);
          if (tep_not_null($cPath))
              $cPath .= '_';
          $cPath .= $category['categories_id'];
      }
      return $cPath;
  }


  function tep_get_uprid($prid, $params)
  {
      $uprid = $prid;
      if ((is_array($params)) && (!strstr($prid, '{'))) {
          while (list($option, $value) = each($params)) {

              $uprid = $uprid . '{' . $option . '}' . htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
          }

          } else
          {
              $uprid = htmlspecialchars(stripslashes($uprid), ENT_QUOTES);
          }
          if ((is_array($params)) && (!strstr($prid, '{'))) {
              while (list($option, $value) = each($params)) {
                  $uprid = $uprid . '{' . $option . '}' . $value;
              }
          }
          return $uprid;
      }


      function tep_get_prid($uprid)
      {
          $pieces = explode('{', $uprid);
          return $pieces[0];
      }


      function tep_customer_greeting()
      {
          global $customer_id, $customer_first_name;
          if (tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {
              $greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
          } else {
              $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
          }
          return $greeting_string;
      }













      function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address)
      {
          if (SEND_EMAILS != 'true')
              return false;

          $message = new email(array('X-Mailer: CartStore Mailer'));

          $text = strip_tags($email_text);
          if (EMAIL_USE_HTML == 'true') {
              $message->add_html($email_text, $text);
          } else {
              $message->add_text($text);
          }

          $message->build_message();
          $result = $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
          return $result;
      }


      function tep_has_product_attributes($products_id)
      {
          $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
          $attributes = tep_db_fetch_array($attributes_query);
          if ($attributes['count'] > 0) {
              return true;
          } else {
              return false;
          }
      }


      function tep_word_count($string, $needle)
      {
          $temp_array = explode($needle, $string);
          return sizeof($temp_array);
      }
      function tep_count_modules($modules = '')
      {
          $count = 0;
          if (empty($modules))
              return $count;
          $modules_array = explode(';', $modules);
          for ($i = 0, $n = sizeof($modules_array); $i < $n; $i++) {
              $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));
              if (is_object($GLOBALS[$class])) {
                  if ($GLOBALS[$class]->enabled) {
                      $count++;
                  }
              }
          }
          return $count;
      }
      function tep_count_payment_modules()
      {
          return tep_count_modules(MODULE_PAYMENT_INSTALLED);
      }
      function tep_count_shipping_modules()
      {
          return tep_count_modules(MODULE_SHIPPING_INSTALLED);
      }
      function tep_create_random_value($length, $type = 'mixed')
      {
          if (($type != 'mixed') && ($type != 'chars') && ($type != 'digits'))
              return false;
          $rand_value = '';
          while (strlen($rand_value) < $length) {
              if ($type == 'digits') {
                  $char = tep_rand(0, 9);
              } else {
                  $char = chr(tep_rand(0, 255));
              }
              if ($type == 'mixed') {
                  if (preg_replace('/^[a-z0-9]$/i', $char))
                      $rand_value .= $char;
              } elseif ($type == 'chars') {
                  if (preg_replace('/^[a-z]$/i', $char))
                      $rand_value .= $char;
              } elseif ($type == 'digits') {
                  if (preg_match('/^[0-9]$/', $char))
                      $rand_value .= $char;
              }
          }
          return $rand_value;
      }
      function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&')
      {
          if (!is_array($exclude))
              $exclude = array();
          $get_string = '';
          if (sizeof($array) > 0) {
              while (list($key, $value) = each($array)) {
                  if ((!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y')) {
                      $get_string .= $key . $equals . $value . $separator;
                  }
              }
              $remove_chars = strlen($separator);
              $get_string = substr($get_string, 0, -$remove_chars);
          }
          return $get_string;
      }
      function tep_not_null($value)
      {
          if (is_array($value)) {
              if (sizeof($value) > 0) {
                  return true;
              } else {
                  return false;
              }
          } else {
              if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
                  return true;
              } else {
                  return false;
              }
          }
      }


      function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES)
      {
          if (strpos($value, '.')) {
              $loop = true;
              while ($loop) {
                  if (substr($value, -1) == '0') {
                      $value = substr($value, 0, -1);
                  } else {
                      $loop = false;
                      if (substr($value, -1) == '.') {
                          $value = substr($value, 0, -1);
                      }
                  }
              }
          }
          if ($padding > 0) {
              if ($decimal_pos = strpos($value, '.')) {
                  $decimals = strlen(substr($value, ($decimal_pos + 1)));
                  for ($i = $decimals; $i < $padding; $i++) {
                      $value .= '0';
                  }
              } else {
                  $value .= '.';
                  for ($i = 0; $i < $padding; $i++) {
                      $value .= '0';
                  }
              }
          }
          return $value;
      }



      function tep_currency_exists($code)
      {
          $code = tep_db_prepare_input($code);
          $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
          if (tep_db_num_rows($currency_code)) {
              return $code;
          } else {
              return false;
          }
      }
      function tep_string_to_int($string)
      {
          return(int)$string;
      }


      function tep_parse_category_path($cPath)
      {

          $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

          $tmp_array = array();
          $n = sizeof($cPath_array);
          for ($i = 0; $i < $n; $i++) {
              if (!in_array($cPath_array[$i], $tmp_array)) {
                  $tmp_array[] = $cPath_array[$i];
              }
          }
          return $tmp_array;
      }


      function tep_rand($min = null, $max = null)
      {
          static $seeded;
          if (!isset($seeded)) {
              mt_srand((double)microtime() * 1000000);
              $seeded = true;
          }
          if (isset($min) && isset($max)) {
              if ($min >= $max) {
                  return $min;
              } else {
                  return mt_rand($min, $max);
              }
          } else {
              return mt_rand();
          }
      }
      function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0)
      {
          setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
      }
      function tep_get_ip_address()
      {
          if (isset($_SERVER)) {
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
              } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                  $ip = $_SERVER['HTTP_CLIENT_IP'];
              } else {
                  $ip = $_SERVER['REMOTE_ADDR'];
              }
          } else {
              if (getenv('HTTP_X_FORWARDED_FOR')) {
                  $ip = getenv('HTTP_X_FORWARDED_FOR');
              } elseif (getenv('HTTP_CLIENT_IP')) {
                  $ip = getenv('HTTP_CLIENT_IP');
              } else {
                  $ip = getenv('REMOTE_ADDR');
              }
          }
          return $ip;
      }
      function tep_count_customer_orders($id = '', $check_session = true)
      {
          global $customer_id;
          if (is_numeric($id) == false) {
              if (tep_session_is_registered('customer_id')) {
                  $id = $customer_id;
              } else {
                  return 0;
              }
          }
          if ($check_session == true) {
              if ((tep_session_is_registered('customer_id') == false) || ($id != $customer_id)) {
                  return 0;
              }
          }
          $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
          $orders_check = tep_db_fetch_array($orders_check_query);
          return $orders_check['total'];
      }
      function tep_count_customer_address_book_entries($id = '', $check_session = true)
      {
          global $customer_id;
          if (is_numeric($id) == false) {
              if (tep_session_is_registered('customer_id')) {
                  $id = $customer_id;
              } else {
                  return 0;
              }
          }
          if ($check_session == true) {
              if ((tep_session_is_registered('customer_id') == false) || ($id != $customer_id)) {
                  return 0;
              }
          }
          $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
          $addresses = tep_db_fetch_array($addresses_query);
          return $addresses['total'];
      }

      function tep_convert_linefeeds($from, $to, $string)
      {
          if ((PHP_VERSION < "4.0.5") && is_array($from)) {
              return preg_replace('/(' . implode('|', $from) . ')/', $to, $string);
          } else {
              return str_replace($from, $to, $string);
          }
      }

      function tep_paypal_wpp_enabled()
      {
          $paypal_wpp_check = tep_db_query("SELECT configuration_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_DP_STATUS' AND configuration_value = 'True'");
          if (tep_db_num_rows($paypal_wpp_check)) {
              return true;
          } else {
              return false;
          }
      }




      function tep_decode_specialchars($string)
      {
          $string = str_replace('&gt;', '>', $string);
          $string = str_replace('&lt;', '<', $string);
          $string = str_replace('&#039;', "'", $string);
          $string = str_replace('&quot;', "\"", $string);
          $string = str_replace('&amp;', '&', $string);
          return $string;
      }
      include('includes/functions/refund_functions.php');

      function YMM_get_categories_where($id, $where)
      {
          global $languages_id;
          $cq = tep_db_query("SELECT c.categories_id, c.parent_id FROM " . TABLE_CATEGORIES . " c," . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id AND cd.language_id = '" . (int)$languages_id . "'");
          $inc_cat = array();
          while ($r = tep_db_fetch_array($cq))
              $inc_cat[] = array('id' => $r['categories_id'], 'parent' => $r['parent_id']);
          $cat_info = array();
          for ($i = 0; $i < sizeof($inc_cat); $i++)
              $cat_info[$inc_cat[$i]['id']] = array('parent' => $inc_cat[$i]['parent'], 'path' => array($inc_cat[$i]['id']));
          for ($i = 0; $i < sizeof($inc_cat); $i++) {
              $cat_id = $inc_cat[$i]['id'];
              while ($cat_info[$cat_id]['parent'] != 0) {
                  $cat_info[$inc_cat[$i]['id']]['path'][] = $cat_info[$cat_id]['parent'];
                  if ($cat_info[$cat_id]['parent'] == $id)
                      $cat_info[$inc_cat[$i]['id']]['ind'] = count($cat_info[$inc_cat[$i]['id']]['path']) - 2;
                  $cat_id = $cat_info[$cat_id]['parent'];
              }
              $cat_info[$inc_cat[$i]['id']]['path'][] = 0;
              if ($cat_info[$cat_id]['parent'] == $id)
                  $cat_info[$inc_cat[$i]['id']]['ind'] = count($cat_info[$inc_cat[$i]['id']]['path']) - 2;
          }
          $ids = '';
          for ($i = 0; $i < sizeof($inc_cat); $i++) {
              if (isset($cat_info[$inc_cat[$i]['id']]['ind'])) {
                  $q = tep_db_query("select
      c.categories_id
      FROM
      " . TABLE_PRODUCTS . " p,
      " . TABLE_PRODUCTS_TO_CATEGORIES . " pc,
      " . TABLE_CATEGORIES . " c,
      " . TABLE_CATEGORIES_DESCRIPTION . " cd
      WHERE $where
      p.products_id = pc.products_id AND c.categories_id = pc.categories_id AND
      p.products_status = '1' AND
      c.categories_id = " . $cat_info[$inc_cat[$i]['id']]['path'][0] . " AND
      c.categories_id = cd.categories_id AND
      cd.language_id = '" . (int)$languages_id . "' LIMIT 1");
                  if (tep_db_num_rows($q) == 1)
                      $ids .= ($ids != '' ? ',' : '') . $cat_info[$inc_cat[$i]['id']]['path'][$cat_info[$inc_cat[$i]['id']]['ind']];
              }
          }
          return($ids != '' ? ' c.categories_id in (' . $ids . ') and ' : ' c.categories_id in (0) and  ');
      }
?>