<?php
  require('includes/application_top.php');
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
      $email_address = tep_db_prepare_input($_POST['email_address']);
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
              tep_session_register('login_email_address');
              tep_session_register('login_id');
              tep_session_register('login_groups_id');
              tep_session_register('login_firstname');
              
              if ($login_lognum % 50 == 0) {
                  
                  tep_redirect(tep_href_link(FILENAME_TERMS_CONDITIONS));
                  
              }
              
              tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
              if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
                  tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
              } else {
                  tep_redirect(tep_href_link(FILENAME_ORDERS));
              }
          }
      }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>
<?php
  echo TITLE;
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/login/css/login_css.css" rel="stylesheet" type="text/css" />
<link href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery-ui/css/cartstoreadmin/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/jquery/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/jquery/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.mousewheel.pack.js"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>../templates/system/fancybox/jquery.easing.pack.js"></script>
<script type="text/javascript">
  

$(document).ready(function() {
               
   $(".button").button();
 
     
    });
</script>
</head>
<body>
<div id="admin-wrapper">
  <?php
  if ($_GET['login'] == 'fail') {
      $info_message = '<div id="dialog-message"  title="Incorrect Login">' . TEXT_LOGIN_ERROR . '</div>';
  }
  if (isset($info_message)) {
?>
  <div align="center">
    <?php
      echo $info_message;
?>
  </div>
  <?php
      } else
      {

      }
?>
  <div id="admin-header">
    <div class="admin-logo"><a href="#"><img src="templates/login/images/login_logo.jpg" width="307" height="104" alt="" /></a></div>
  </div>
  <div id="admin-title"><img src="templates/login/images/admin.jpg" width="38" height="275" alt="" /></div>
  <div id="login-form">
    <?php
      echo tep_draw_form('login', FILENAME_LOGIN, 'action=process');
?>
    <div class="inputWrap">
      <label class="label">eMail :</label>
      <input type="text" class="inputbox" value="" name="email_address"/>
      <div class="clear"></div>
    </div>
    <div class="inputWrap">
      <label class="label">Password  :</label>
      <input type="password" class="inputbox" value="" name="password"/>
      <div class="clear"></div>
    </div>
    <input type="submit" class="button" value="Login" />
    <br />
    <a href="password_forgotten.php" class="readon">Forgot Password?</a>
    <div class="clear"></div>
    </form>
  </div>
  <div id="admin-footer">Copyright CartStore&#8482; is a trademark of Adoovo Inc. CopyRight&copy; 2006-2011 Adoovo Inc.</div>
</div>
</body>
</html>