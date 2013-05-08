<?php
/*
  $Id: download.php,v 1.9 2003/02/13 03:01:48 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  include('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) die;

// Check download.php was called with proper GET parameters
  if ((isset($_GET['order']) && !is_numeric($_GET['order'])) || (isset($_GET['id']) && !is_numeric($_GET['id'])) ) {
    die;
  }

// Check that order_id, customer_id and filename match
  $downloads_query = tep_db_query("select date_format(o.last_modified, '%Y-%m-%d') as date_purchased_day, o.orders_status, opd.download_maxdays, opd.download_count, opd.download_maxdays, opd.orders_products_filename from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . $customer_id . "' and o.orders_id = '" . (int)$_GET['order'] . "' and o.orders_status >= " . DOWNLOADS_CONTROLLER_ORDERS_STATUS . " and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_download_id = '" . (int)$_GET['id'] . "' and opd.orders_products_filename != ''");
  if (!tep_db_num_rows($downloads_query)) die;
  $downloads = tep_db_fetch_array($downloads_query);
// MySQL 3.22 does not have INTERVAL
  list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);
  $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);

// Die if time expired (maxdays = 0 means no time limit)
  if (($downloads['download_maxdays'] != 0) && ($download_timestamp <= time())) die;
// Die if remaining count is <=0
  if ($downloads['download_count'] <= 0) die;
// Die if file is not there
  if (!file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) die;
  else {
    $file_name = '';
    if (strstr($downloads['orders_products_filename'], '/')) { // file in a subfolder
      $file_dir = DIR_FS_DOWNLOAD;
      $file_array = explode('/', $downloads['orders_products_filename']);
      for ($i = 0; $i < count($file_array); $i++) {
        if (is_dir($file_dir . $file_array[$i])) {
          $file_dir .= $file_array[$i] . '/';
        } else if (is_file($file_dir . $file_array[$i])) {
          $file_name = $file_array[$i];
        }
      }
    } else {
      $file_name = $downloads['orders_products_filename'];
    }
  }
// Now decrement counter
  tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_count = download_count-1 where orders_products_download_id = '" . (int)$_GET['id'] . "'");

// Returns a random name, 16 to 20 characters long
// There are more than 10^28 combinations
// The directory is "hidden", i.e. starts with '.'
function tep_random_name()
{
  $letters = 'abcdefghijklmnopqrstuvwxyz';
  $dirname = '.';
  $length = floor(tep_rand(16,20));
  for ($i = 1; $i <= $length; $i++) {
   $q = floor(tep_rand(1,26));
   $dirname .= $letters[$q];
  }
  return $dirname;
}

// Unlinks all subdirectories and files in $dir
// Works only on one subdir level, will not recurse
function tep_unlink_temp_dir($dir)
{
  $h1 = opendir($dir);
  while ($subdir = readdir($h1)) {
// Ignore non directories
    if (!is_dir($dir . $subdir)) continue;
// Ignore . and .. and CVS
    if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') continue;
// Loop and unlink files in subdirectory
    $h2 = opendir($dir . $subdir);
    while ($file = readdir($h2)) {
      if ($file == '.' || $file == '..') continue;
      @unlink($dir . $subdir . '/' . $file);
    }
    closedir($h2);
    @rmdir($dir . $subdir);
  }
  closedir($h1);
}
function tep_download_buffered($filename)
{
  $buffersize = 1*(1024*1024); // how many bytes per chunk
  $buffer = '';
  $handle = fopen($filename, 'rb');
  if ($handle === false) {
    return false;
  }
  while (!feof($handle)) {
    $buffer = fread($handle, $buffersize);
    echo $buffer;
    flush();
  }
  $status = fclose($handle);
  return $status;
}
// Now send the file with header() magic
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-disposition: attachment; filename=\"" . $file_name . "\"");


  if (DOWNLOAD_BY_REDIRECT == 'true') {
// This will work only on Unix/Linux hosts
    tep_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
    $tempdir = tep_random_name();
    umask(0000);
    mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
    symlink(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'], DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $file_name);
    tep_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $file_name);
  } else {
// This will work on all systems, but will need considerable resources
// We could also loop with fread($fp, 4096) to save memory
    set_time_limit(0); // Prevent the script from timing out for large files
    tep_download_buffered(DIR_FS_DOWNLOAD . $downloads['orders_products_filename']);
  }
?>
