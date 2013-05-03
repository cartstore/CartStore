<?php
  // <!--//-- check store status --\\-->
  // If offline redirect to offline page, else continue
  // as normal
  if ( TAKE_STORE_OFFLINE == 'True' ) {
    $allowed_ip = false;
    $ips = explode(',',STORE_OFFLINE_ALLOW);
    foreach($ips as $ip) {
      if(trim($ip) == $_SERVER['REMOTE_ADDR']) {
        $allowed_ip = true;
        break;
      }
    }
    if ( !$allowed_ip && basename($_SERVER['SCRIPT_NAME']) != 'offline.php' ) {
      // store closed, redirect to offline page
      tep_redirect(tep_href_link('offline.php', null, 'NONSSL'));
    }
  }
  // <!-- \\-- end of offline check --//-->
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

$headertags#
 <link href="templates/includes/sts_templates/default/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="templates/includes/sts_templates/default/css/template_css.css" rel="stylesheet" type="text/css" />
<!--<link href="templates/includes/sts_templates/default/css/reset.css" rel="stylesheet" type="text/css" />-->
<link href="templates/system/cartstore.css" rel="stylesheet" type="text/css" />
<link href="templates/system/cartstore.custom.css" rel="stylesheet" type="text/css" />
<link href="templates/system/cartstore_banner_rotator.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/system/mojozoom/mojozoom.js"></script>
<link type="text/css" href="templates/system/mojozoom/mojozoom.css" rel="stylesheet" />
<script type="text/javascript" src="templates/includes/sts_templates/default/js/getCat.js"></script>
<!-- jQuery UI  |  http://jquery.com/  http://jqueryui.com/  http://jqueryui.com/docs/Theming  -->
<script src="templates/system/jquery/js/jquery.min.js" type="text/javascript"></script>

 <script src="templates/includes/sts_templates/default/js/bootstrap.min.js"></script>

 
 
<script type="text/javascript" src="java.js.php"></script>
<script type="text/javascript" src="templates/system/fancybox/jquery.mousewheel.pack.js"></script>
<script type="text/javascript" src="templates/system/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="templates/system/fancybox/jquery.fancybox.css" media="screen" />
<script src="templates/system/jquery.cookie.js" type="text/javascript"></script>
<script src="templates/system/jquery.include.js" type="text/javascript"></script>
<script src="templates/system/slideup.js" type="text/javascript"></script>
<script src="templates/system/expand.js" type="text/javascript"></script>
<script type="text/javascript" src="templates/includes/functions/bannerRotator.js"></script>
<script type="text/javascript" src="templates/system/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="templates/system/ckeditor/adapters/jquery.js"></script>
<link href="templates/system/event_calender/stylesheet.css" rel="stylesheet" type="text/css" media="all" />
<script src="templates/system/qtychg.js" type="text/javascript"></script>
<script src="templates/system/jquery_init_local.js" type="text/javascript"></script>
<script src="templates/system/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<?php
if (defined('MODULE_SOCIAL_LOGIN_STATUS') && MODULE_SOCIAL_LOGIN_STATUS == 'true'):
 require(DIR_WS_INCLUDES . 'socialsetting.php');
 echo '<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$apikey.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>';
endif;
?>
</head>
