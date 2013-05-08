<?php
require_once("../includes/configure.php");
require_once("../includes/filenames.php");
require_once("../includes/database_tables.php");
require_once("../includes/functions/database.php");

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * phpMyAdmin sample configuration, you can use it as base for
 * manual configuration. For easier setup you can use setup/
 *
 * All directives are explained in Documentation.html and on phpMyAdmin
 * wiki <http://wiki.phpmyadmin.net>.
 *
 * @version $Id$
 * @package phpMyAdmin
 */

if (!isset($_COOKIE['osCAdminID']))
   header("Location: ../login.php?phpMyAdmin=" . (isset($_SESSION['PMA_token']) ? $_SESSION['PMA_token'] : md5(uniqid(rand(), true))));

tep_db_connect();
$check_sql = "select * from " . TABLE_SESSIONS . " where `sesskey`='" . mysql_real_escape_string($_COOKIE['osCAdminID']) . "' and expiry > '" . time() . "'";
$check_query = tep_db_query($check_sql);
if (tep_db_num_rows($check_query) < 1){
   header("Location: ../login.php?phpMyAdmin=" . (isset($_SESSION['PMA_token']) ? $_SESSION['PMA_token'] : md5(uniqid(rand(), true))));
}
$row = tep_db_fetch_array($check_query);
$chunks = explode(";",$row['value']);
$data = array();
foreach ($chunks as $piece){
  list($key,$value) = explode("|",$piece);
  $data[$key] = unserialize($value);
}
if (!isset($data['login_id']))
   header("Location: ../login.php?phpMyAdmin=" . (isset($_SESSION['PMA_token']) ? $_SESSION['PMA_token'] : md5(uniqid(rand(), true))));

/*
 * This is needed for cookie based authentication to encrypt password in
 * cookie
 */
$cfg['blowfish_secret'] = 'cn859430vnc632807cx34067cx30'; /* YOU MUST FILL IN THIS FOR COOKIE AUTH! */

/*
 * Servers configuration
 */
$i = 0;

/*
 * First server
 */
$i++;
/* Authentication type */
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = DB_SERVER_USERNAME;
$cfg['Servers'][$i]['password'] = DB_SERVER_PASSWORD;
$cfg['Servers'][$i]['host'] = DB_SERVER;
/* Server parameters */

$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['compress'] = false;
/* Select mysqli if your server has it */
$cfg['Servers'][$i]['extension'] = 'mysql';
$cfg['Servers'][$i]['AllowNoPassword'] = false;

/* rajk - for blobstreaming */
$cfg['Servers'][$i]['bs_garbage_threshold'] = 50;
$cfg['Servers'][$i]['bs_repository_threshold'] = '32M';
$cfg['Servers'][$i]['bs_temp_blob_timeout'] = 600;
$cfg['Servers'][$i]['bs_temp_log_threshold'] = '32M';

/* User for advanced features */
// $cfg['Servers'][$i]['controluser'] = 'pma';
// $cfg['Servers'][$i]['controlpass'] = 'pmapass';
/* Advanced phpMyAdmin features */
// $cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
// $cfg['Servers'][$i]['bookmarktable'] = 'pma_bookmark';
// $cfg['Servers'][$i]['relation'] = 'pma_relation';
// $cfg['Servers'][$i]['table_info'] = 'pma_table_info';
// $cfg['Servers'][$i]['table_coords'] = 'pma_table_coords';
// $cfg['Servers'][$i]['pdf_pages'] = 'pma_pdf_pages';
// $cfg['Servers'][$i]['column_info'] = 'pma_column_info';
// $cfg['Servers'][$i]['history'] = 'pma_history';
// $cfg['Servers'][$i]['tracking'] = 'pma_tracking';
// $cfg['Servers'][$i]['designer_coords'] = 'pma_designer_coords';
/* Contrib / Swekey authentication */
// $cfg['Servers'][$i]['auth_swekey_config'] = '/etc/swekey-pma.conf';

/*
 * End of servers configuration
 */

/*
 * Directories for saving/loading files from server
 */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';

?>
