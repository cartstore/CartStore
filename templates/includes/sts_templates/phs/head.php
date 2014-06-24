<?php
  // <!--//-- check store status --\\-->
  // If offline cartstore6irect to offline page, else continue
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
      // store closed, cartstore6irect to offline page
      tep_redirect(tep_href_link('offline.php', null, 'NONSSL'));
    }
  }
  // <!-- \\-- end of offline check --//-->
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
$headertags#
	<!-- Bootstrap -->
<link href="templates/includes/sts_templates/phs/css/bootstrap.min.css" rel="stylesheet">
<link href="templates/includes/sts_templates/phs/css/menu.css" rel="stylesheet">
<link href="templates/includes/sts_templates/phs/css/style.css" rel="stylesheet">
<link href="templates/includes/sts_templates/phs/css/media.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="templates/system/cartstore.css" rel="stylesheet" type="text/css" />
<link href="templates/system/cartstore.custom.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="templates/system/mojozoom/mojozoom.css" rel="stylesheet" />
<!-- Add fancyBox -->
<link rel="stylesheet" href="templates/system/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="templates/includes/sts_templates/phs/js/bootstrap.min.js"></script>
<script src="templates/system/bootbox.min.js" type="text/javascript"></script>
<script type="text/javascript" src="java.js.php"></script>
<script type="text/javascript" src="templates/system/mojozoom/mojozoom.js"></script>
<script type="text/javascript" src="templates/system/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
<script src="templates/system/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="//www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script src="templates/system/jquery.include.js" type="text/javascript"></script>
<script src="templates/system/jquery.phoenix.js"></script>
<script src="templates/system/jquery_init_local.js" type="text/javascript"></script>


<?php
if (defined('MODULE_SOCIAL_LOGIN_STATUS') && MODULE_SOCIAL_LOGIN_STATUS == 'true'):
 require(DIR_WS_INCLUDES . 'socialsetting.php');
 echo '<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$apikey.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>';
endif;
?>
</head>
