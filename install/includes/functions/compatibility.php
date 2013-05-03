<?php
/*
  $Id: compatibility.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  if (PHP_VERSION >= 4.1) {
    $_GET =& $_GET;
    $_POST =& $_POST;
    $_COOKIE =& $_COOKIE;
    $_SESSION =& $_SESSION;
    $_SERVER =& $_SERVER;
  } else {
    if (!is_array($_GET)) $_GET = array();
    if (!is_array($_POST)) $_POST = array();
    if (!is_array($_COOKIE)) $_COOKIE = array();
  }

  if (!function_exists('is_numeric')) {
    function is_numeric($param) {
      return preg_match('/^[0-9]{1,50}.?[0-9]{0,50}$/', $param);
    }
  }
?>
