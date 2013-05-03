<?php
  $login_request = true;
  $require_name = FALSE;

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
      $email_address = tep_db_prepare_input($_POST['email_address']);
      $firstname = tep_db_prepare_input($_POST['firstname']);
      $log_times = $_POST['log_times'] + 1;
      if ($log_times >= 4) {
          $password_forgotten = true;
          tep_session_register('password_forgotten');
      }
      
      $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
          $_GET['login'] = 'fail';
      } else {
          $check_admin = tep_db_fetch_array($check_admin_query);
          if ($require_name == TRUE && $check_admin['check_firstname'] != $firstname) {
              $_GET['login'] = 'fail';
          } else {
              $_GET['login'] = 'success';
              function randomize()
              {
                  $salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
                  srand((double)microtime() * 1000000);
                  $i = 0;
                  while ($i <= 7) {
                      $num = rand() % 33;
                      $tmp = substr($salt, $num, 1);
                      $pass = $pass . $tmp;
                      $i++;
                  }
                  return $pass;
              }
              $makePassword = randomize();
              tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
              tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
          }
      }
  }
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
<?php
  if ($_GET['login'] == 'success') {
      $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif ($_GET['login'] == 'fail') {
      $info_message = TEXT_FORGOTTEN_ERROR;
  }
?>
<div id="admin-wrapper_pf">
  <div id="admin-header">
    <div class="admin-logo"><a href="#"><img src="templates/login/images/login_logo.jpg" width="307" height="104" alt="" /></a></div>
  </div>
  <div id="admin-title"><img src="templates/login/images/admin.jpg" width="38" height="275" alt="" /></div>
  <div id="login-form">
<?php
  if (tep_session_is_registered('password_forgotten')) {
      echo TEXT_FORGOTTEN_FAIL;
?>
<br>
<?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . IMAGE_BACK . '</a>';
  } elseif (isset($success_message)) {
      echo $success_message;
?>
<br>
<?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . IMAGE_BACK . '</a>';
	} else {
		if (isset($info_message)) {
			echo $info_message;
		} else {
			echo tep_draw_hidden_field('log_times', '0');
		}
?>
    <?php
              echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process');
?>
<?php if ($require_name == TRUE): ?>
    <div class="inputWrap">
      <label class="label">First Name :</label>
      <input type="text" class="inputbox" onclick="value=''" value="First Name" name="firstname"/>
      <div class="clear"></div>
    </div>
<?php endif; ?>
    <div class="inputWrap">
      <label class="label">eMail  :</label>
      <input type="text" class="inputbox" onclick="value=''" value="eMail" name="email_address"/>
      <div class="clear"></div>
    </div>
    <input type="submit" class="button" class="login-button" value="Go" />
    <br />
    <a class="readon" href="login.php"><< Back</a>
    <div class="clear"></div>
    </form>
<?php
		echo tep_draw_hidden_field('log_times', $log_times);
	}
?>
  </div>
  <div id="admin-footer">Copyrights CartStore&#8482; is a trademark of Adoovo Inc. CopyRight&copy; 2006-2011 Adoovo Inc.</div>
</div>
</body>
</html>
