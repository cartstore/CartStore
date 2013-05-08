<?php
// <!--//-- check store status --\\-->
// If offline redirect to offline page, else continue
// as normal
if (TAKE_STORE_OFFLINE == 'True') {
	$allowed_ip = false;
	$ips = explode(',', STORE_OFFLINE_ALLOW);
	foreach ($ips as $ip) {
		if (trim($ip) == $_SERVER['REMOTE_ADDR']) {
			$allowed_ip = true;
			break;
		}
	}
	if (!$allowed_ip && basename($_SERVER['SCRIPT_NAME']) != 'offline.php') {
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
		<meta http-equiv="imagetoolbar" content="no" />

		$headertags#

		<!-- JavaScript Starts --><!-- jQuery UI  |  http://jquery.com/  http://jqueryui.com/  http://jqueryui.com/docs/Theming  -->

		<script src="templates/system/jquery/js/jquery.min.js" type="text/javascript"></script>

		<script src="templates/includes/sts_templates/mobile/js/jquery.mobile.js" type="text/javascript"></script>

		<script type="text/javascript" src="templates/includes/sts_templates/mobile/js/klass.min.js"></script>

		<script src="templates/includes/sts_templates/mobile/js/code.photoswipe.jquery-3.0.5.js" type="text/javascript"></script>

		<script src="templates/system/jquery_init_local_mobile.js" type="text/javascript"></script>


		<script type="text/javascript" src="templates/system/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="templates/system/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="templates/system/recaptcha_ajax.js"></script>


						<script type="text/javascript" src="java.js.php"></script>
						
						
						
  
						
						

		<script src="templates/system/qtychg.js" type="text/javascript"></script>

		<script src="templates/system/jquery-validate/jquery.validate.min.js"></script>
				<script type="text/javascript" src="templates/includes/sts_templates/default/js/getCat.js"></script>

		<!-- JavaScript Ends -->
<link rel="stylesheet" href="templates/includes/sts_templates/mobile/css/jquery.mobile.structure.css" />
		<link rel="stylesheet" href="templates/includes/sts_templates/mobile/css/jquery_theme.css" type="text/css" />

 		<link href="templates/includes/sts_templates/mobile/css/photoswipe.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="templates/includes/sts_templates/mobile/css/custom.css" type="text/css" />


		<!-- iOS Features Start -->
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- iPhone ICON -->
		<link href="templates/includes/sts_templates/mobile/images/touch-icon-iphonecfcd.png" sizes="57x57" rel="apple-touch-icon">
		<!-- iPhone (Retina) ICON-->
		<link href="templates/includes/sts_templates/mobile/images/touch-icon-iphone4cfcd.png" sizes="114x114" rel="apple-touch-icon">
		<!-- iPhone SPLASHSCREEN-->
		<link href="templates/includes/sts_templates/mobile/images/startupcfcd.png" media="(device-width: 320px)" rel="apple-touch-startup-image">
		<!-- iPhone (Retina) SPLASHSCREEN-->
		<link href="templates/includes/sts_templates/mobile/images/startup2xcfcd.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<!-- iOS Features End -->

		<!-- CSS Starts -->
<?php
if (defined('MODULE_SOCIAL_LOGIN_STATUS') && MODULE_SOCIAL_LOGIN_STATUS == 'true'):
 require(DIR_WS_INCLUDES . 'socialsetting.php');
 echo '<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$apikey.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>';
endif;
?>
	</head>
