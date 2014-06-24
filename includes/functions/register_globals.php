<?php

// >>> BEGIN REGISTER_GLOBALS
  // Work-around functions to allow disabling of register_globals in php.ini
  // These functions perform a similar operation as the 'link_session_variable'
  // function added to .../functions/sessions.php but for the GET, POST, etc
  // variables
  //
  // Parameters:
  // var_name - Name of session variable
  //
  // Returns:
  // None
  function link_get_variable($var_name)
  {
    // Map global to GET variable
    if (isset($_GET[$var_name]))
    {
      $GLOBALS[$var_name] =& $_GET[$var_name];
    }
  }

  function link_post_variable($var_name)
  {
    // Map global to POST variable
    if (isset($_POST[$var_name]))
    {
      $GLOBALS[$var_name] =& $_POST[$var_name];
    }
  }
  
  function link_session_variable($var_name, $map)
  {
    if ($map)
    {
      // Map global to session variable. If the global variable is already set to some value
      // then its value overwrites the session varibale. I **THINK** this is correct behaviour
      if (isset($GLOBALS[$var_name]))
      {
        $_SESSION[$var_name] = $GLOBALS[$var_name];
      }

      $GLOBALS[$var_name] =& $_SESSION[$var_name];
    }
    else
   {
      // Unmap global from session variable (note that the global variable keeps the value of
      // the session variable. This should be unnecessary but it reflects the same behaviour
      // as having register_globals enabled, so in case the OSC code assumes this behaviour,
      // it is reproduced here
      $nothing = 0;
      $GLOBALS[$var_name] =& $nothing;
      unset($GLOBALS[$var_name]);
      $GLOBALS[$var_name] = $_SESSION[$var_name];
    }
  }

// <<< END REGISTER_GLOBALS
