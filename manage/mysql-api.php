<?php
  require('includes/application_top.php');
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {

	  $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($login_id) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
          $_GET['login'] = 'fail';
      } else {
          $check_admin = tep_db_fetch_array($check_admin_query);
          $login_lognum = $check_admin['login_lognum'];
          $login_email_address = $check_admin['login_email_address'];
          $login_logdate = $check_admin['login_logdate'];
          $login_lognum = $check_admin['login_lognum'];
          $login_modified = $check_admin['login_modified'];

          tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");

		  if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
              tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
          } else {
              tep_redirect(tep_href_link(FILENAME_ORDERS));
          }
      }
      exit;
  }
  if (!file_exists(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TERMS_CONDITIONS_CONTENT)) {
      tep_mail('CartStore admin'
      , 'start@cartstore.com', 'Missing file', 'The terms and conditions file ' . DIR_WS_LANGUAGES . $language . '/' . FILENAME_TERMS_CONDITIONS_CONTENT . ' is missing.', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_LOGOFF));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<div class="page-header"><h1> 
MySQL Api Settings</h1></div>

<?php

echo  '<p><b>Server url:</b> '.$_SERVER['SERVER_NAME'] . '<br>';
echo '<b> Username: </b>' . DB_SERVER_USERNAME . '<br>';
echo  '<b>Password: </b>' . DB_SERVER_PASSWORD . '<br>' ;
echo '<b>Databse: </b>' . DB_DATABASE . ' </p>' ;





?>
<a target="_BLANK" href="https://zapier.com/zapbook/"> <img src="images/logo60orange.png"></a>

<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>