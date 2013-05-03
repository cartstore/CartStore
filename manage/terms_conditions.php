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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php
  echo TITLE;
?></title>
<style type="text/css">
<!--
body {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 11px;
}
.small {
  font-size: 9px;
}
.Warning2 {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 14px;
  color: #000000;
  text-align: center;
  background-color: #cccccc;
}
.style1 {
  color: #F1F1F1
}
.terms2 {
  font-size: 11px;
}
-->
</style>
<script src="../templates/system/jquery/js/jquery.min.js" type="text/javascript"></script>
<script src="../templates/system/jquery/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="templates/jquery.init.local.js" type="text/javascript"></script>
<link href="templates/jquery-ui/css/cartstoreadmin/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<table width="100%" border="0" class="Warning2">
  <tr>
    <td>To access the control panel for this site you need to confirm your acceptance of the terms and conditions.</td>
  </tr>
</table>
<table border="0" width="100%"  cellspacing="0" cellpadding="0" align="center" valign="middle">
    <tr>
  
  <td><table border="0" width="100%"  cellspacing="0" cellpadding="1" align="center" valign="middle">
        <tr>
      
      <td align="center"><?php
  echo tep_draw_form('login', FILENAME_TERMS_CONDITIONS, '', 'post') . tep_draw_hidden_field('action', 'process');
?>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="100%"><table width="100%" cellpadding="3" cellspacing="0">
                <tr>
                  <td align="right" valign="middle"><?php
  echo tep_image_submit('button_confirm.png', IMAGE_BUTTON_LOGIN);
?></td>
                  <td align="left" valign="middle"><a class="button" href="logoff.php">Cancel</a>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td class="login_heading" valign="top"><div class="terms2"><?php
  echo HEADING_TERMS_CONDITIONS;
?></div></td>
          </tr>
          <tr>
            <td height="100%" valign="top" align="center"><table border="0" height="100%" cellspacing="0" cellpadding="1">
                <tr>
                  <td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2">
                      <tr>
                        <td>
                        	
                      
                        	
                        	<?php
  $all_terms_code = file_get_contents(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TERMS_CONDITIONS_CONTENT);
  echo $all_terms_code;
?></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
            </tr>
          
        </table>
        </form></td>
        </tr>
      
      <tr>
        <td><?php
  require(DIR_WS_INCLUDES . 'footer.php');
?></td>
      </tr>
    </table></td>
    </tr>
  
</table>
</body>
</html>
