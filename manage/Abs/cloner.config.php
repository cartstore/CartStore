<?php
$_CONFIG['license_code']='';
$_CONFIG['backup_path']=DIR_FS_CATALOG;
$_CONFIG['clonerPath']=DIR_FS_ADMIN . "Abs/administrator/backups";

$admin_query = tep_db_query("select * from " . TABLE_ADMIN . " where admin_email_address = '" . $_SESSION['login_email_address'] ."'");
if (tep_db_num_rows($admin_query) > 0){
	$user = tep_db_fetch_array($admin_query);
	$_CONFIG['jcuser']=$user['admin_email_address'];
	$_CONFIG['jcpass']=$user['admin_password'];
} else {
	$_CONFIG['jcuser']='cdtycnoatvyancxdk';
	$_CONFIG['jcpass']='21232f297a57a5a743894a0e4a801fc3';
}

$_CONFIG['mysql_host']=DB_SERVER;
$_CONFIG['mysql_user']=DB_SERVER_USERNAME;
$_CONFIG['mysql_pass']=DB_SERVER_PASSWORD;
$_CONFIG['mysql_database']=DB_DATABASE;
$_CONFIG['select_folders']="";
$_CONFIG['select_lang']=$language;
$_CONFIG['secure_ftp']="0";
$_CONFIG['backup_compress']="0";
$_CONFIG['cron_logemail']="";
$_CONFIG['cron_exclude']="";
$_CONFIG['cron_send']="0";
$_CONFIG['cron_btype']="0";
$_CONFIG['cron_bname']="";
$_CONFIG['cron_ip']="";
$_CONFIG['cron_ftp_server']="";
$_CONFIG['cron_ftp_user']='';
$_CONFIG['cron_ftp_pass']='';
$_CONFIG['cron_ftp_path']="";
$_CONFIG['cron_ftp_delb']="";
$_CONFIG['databases_incl_list']="";
$_CONFIG['cron_sql_drop']="";
$_CONFIG['cron_email_address']="";
$_CONFIG['cron_file_delete']="0";
$_CONFIG['cron_file_delete_act']="";
$_CONFIG['mem']="";
$_CONFIG['backup_refresh']="1";
$_CONFIG['refresh_time']="1";
$_CONFIG['refresh_mode']="1";
$_CONFIG['recordsPerSession']="10000";
$_CONFIG['excludeFilesSize']="-1";
$_CONFIG['splitBackupSize']="2048";
$_CONFIG['backup_refresh_number']="100";
$_CONFIG['sql_mem']="";
$_CONFIG['enable_db_backup']="1";
$_CONFIG['zippath']="";
$_CONFIG['tarpath']="tar";
$_CONFIG['sqldump']="mysqldump --quote-names ";
$_CONFIG['system_dlink']="";
$_CONFIG['mosConfig_live_site']= parse_url( HTTP_SERVER, PHP_URL_HOST );
$_CONFIG['system_ftptransfer']="0";
$_CONFIG['system_mdatabases']="0";
$_CONFIG['add_backups_dir']="0";
$_CONFIG['cron_amazon_active']="";
$_CONFIG['cron_amazon_awsAccessKey']='';
$_CONFIG['cron_amazon_awsSecretKey']='';
$_CONFIG['cron_amazon_bucket']='';
$_CONFIG['cron_amazon_dirname']='';
$_CONFIG['cron_amazon_ssl']='';
$_CONFIG['debug']="0";

if (isset($_REQUEST['task']) && $_REQUEST['task'] == 'dologin'){
      $email_address = tep_db_prepare_input($_POST['username']);
      $password = tep_db_prepare_input($_POST['password']);

      $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
          $_GET['login'] = 'fail';
      } else {
          $check_admin = tep_db_fetch_array($check_admin_query);

          if (!tep_validate_password($password, $check_admin['login_password'])) {
              $_GET['login'] = 'fail';
          } else {
              if (tep_session_is_registered('password_forgotten')) {
                  tep_session_unregister('password_forgotten');
              }
              $login_id = $check_admin['login_id'];
              $login_groups_id = $check_admin['login_groups_id'];
              $login_firstname = $check_admin['login_firstname'];
              $login_email_address = $check_admin['login_email_address'];
              $login_logdate = $check_admin['login_logdate'];
              $login_lognum = $check_admin['login_lognum'];
              $login_modified = $check_admin['login_modified'];
              $clone = 1;
              tep_session_register('login_email_address');
              tep_session_register('login_id');
              tep_session_register('login_groups_id');
              tep_session_register('login_firstname');
			  tep_session_register('clone');
			  
			  tep_redirect('index.php');
          }
      }
      
}

?>