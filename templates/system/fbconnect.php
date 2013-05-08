<?php
//Facebook Connect
$fbconfig['appid' ]  = ""; //your application id
$fbconfig['api'   ]  = ""; //your api key
$fbconfig['secret']  = ""; //your application secret

    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));
    // We may or may not have this data based on a $_GET or $_COOKIE based session.
    // If we get a session here, it means we found a correctly signed session using
    // the Application Secret only Facebook and the Application know. We dont know
    // if it is still valid until we make an API call using the session. A session
    // can become invalid if it has already expired (should not be getting the
    // session back in this case) or if the user logged out of Facebook.
    $fbme = null;

    // Get User ID
    $user = $facebook->getUser();
    if ($user) {
      try {
        // Proceed knowing you have a logged in user who's authenticated.
        $fbme = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $user = $fbme = null;
      }
    }
?>