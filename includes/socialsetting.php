<?php
if (defined('MODULE_SOCIAL_LOGIN_STATUS') && MODULE_SOCIAL_LOGIN_STATUS == 'true'):
// Getting api key.
      $apikey_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_API_KEY'");
    $apikey_array = tep_db_fetch_array($apikey_query);
    $apikey = trim($apikey_array['configuration_value']);

// Getting api secret.
      $apisecretkey_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SOCIAL_LOGIN_API_SECRET_KEY'");
    $apisecretkey_array = tep_db_fetch_array($apisecretkey_query);
    $apisecretkey = trim($apisecretkey_array['configuration_value']);

if(empty($apikey) && empty($apisecretkey)){
	$sociallogininterface = "<p style ='color:red;'>To activate your plugin, please log in to LoginRadius and get API Key & Secret. Web: <b><a href ='http://www.loginradius.com' target = '_blank'>www.LoginRadius.com</a></b></p>";
}
// Checking apikey and show interface.
    if (!empty($apikey) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $apikey) && !empty($apisecretkey) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $apisecretkey)) {	  		
		$http = "http://";
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' && isset($_SERVER['HTTPS'])) {
			$http = "https://";
			}
        $loc = urlencode($http.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        if(!tep_session_is_registered('customer_id')) {
	      $sociallogin_script = '<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$apikey.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>';
		  $sociallogininterface = '<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
	}
}
else {
	$sociallogininterface = "<p style ='color:red;'>Your LoginRadius API key and secret is not valid, please correct it or contact LoginRadius support at <b><a href ='http://www.loginradius.com' target = '_blank'>www.LoginRadius.com</a></b></p>";
}
endif;
?>