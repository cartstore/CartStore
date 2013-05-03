<?php
/*
  $Id: database.php,v 1.23 2003/06/20 00:18:30 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link, $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger;
      $logger->write($query, 'QUERY');
    }

    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (mysql_error()) $logger->write(mysql_error(), 'ERROR');
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_result($result, $row, $field = '') {
    return mysql_result($result, $row, $field);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string) {
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

////
// This deletes a family.
  function tep_remove_family($family_id) {
    tep_db_query("delete from " . TABLE_FAMILIES . " where family_id = '" . (int)$family_id . "'");
  }

////
// This provides the family name based on the id provided.
  function tep_get_family_name($family_id) {

    $family_query = tep_db_query("select family_name from " . TABLE_FAMILIES . " where family_id = '" . (int)$family_id . "'");
    $family = tep_db_fetch_array($family_query);

    return $family['family_name'];
  }

  function tep_get_all_families($families_array = '') {
    if (!is_array($families_array)) $families_array = array();

    $families_query = tep_db_query("select family_id, family_name from " . TABLE_FAMILIES . " order by family_name ASC");
    while ($families = tep_db_fetch_array($families_query)) {
      $families_array[] = array('id' => $families['family_id'], 'text' => $families['family_name']);
    }

    return $families_array;
  }

////
// Creates a pull-down list of family names
  function tep_get_family_list($name, $selected = '', $parameters = '') {
    $families_name_array = array(array('id' => '', 'text' => PULL_DOWN_FAMILY_DEFAULT));
    $families_name = tep_get_all_families();

    for ($i=0, $n=sizeof($families_name); $i<$n; $i++) {
      $families_name_array[] = array('id' => $families_name[$i]['id'], 'text' => $families_name[$i]['text']);
    }

    return tep_draw_pull_down_menu($name, $families_name_array, $selected, $parameters);
  }

  function tep_get_all_products($products_array = '') {
    if (!is_array($products_array)) $products_array = array();

    $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id order by pd.products_name ASC");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'], 'text' => $products['products_name']);
    }

    return $products_array;
  }

////
// Creates a pull-down list of product names
  function tep_get_product_list($name, $selected = '', $parameters = '') {
    $products_name_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $products_name = tep_get_all_products();

    for ($i=0, $n=sizeof($products_name); $i<$n; $i++) {
      $products_name_array[] = array('id' => $products_name[$i]['id'], 'text' => $products_name[$i]['text']);
    }

    return tep_draw_pull_down_menu($name, $products_name_array, $selected, $parameters);
  }
?>
