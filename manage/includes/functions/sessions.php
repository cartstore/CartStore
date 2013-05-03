<?php
  if ((PHP_VERSION >= 4.3) && ((bool)ini_get('register_globals') == false)) {
      @ini_set('session.bug_compat_42', 1);
      @ini_set('session.bug_compat_warn', 0);
  }
  if (STORE_SESSIONS == 'mysql') {
      if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
          $SESS_LIFE = 7200;
      }
      function _sess_open($save_path, $session_name)
      {
          return true;
      }
      function _sess_close()
      {
          return true;
      }
      function _sess_read($key)
      {
          $qid = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
          $value = tep_db_fetch_array($qid);

          if ($value['value']) {
              return $value['value'];
          }
          return false;
      }
      function _sess_write($key, $val)
      {
          global $SESS_LIFE;
          $expiry = time() + $SESS_LIFE;
          $value = $val;
          $qid = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
          $total = tep_db_fetch_array($qid);
          if ($total['total'] > 0) {
              return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
          } else {
              return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
          }
      }
      function _sess_destroy($key)
      {
          return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
      }
      function _sess_gc($maxlifetime)
      {
          tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");
          return true;
      }
      session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }
  function tep_session_start()
  {
      $success = session_start();
      if ($success && count($_SESSION)) {
          $session_keys = array_keys($_SESSION);
          foreach ($session_keys as $variable) {
              link_session_variable($variable, true);
          }
      }
      return $success;
  }
  function tep_session_register($variable)
  {
      link_session_variable($variable, true);
      return true;
  }
  function tep_session_is_registered($variable)
  {
      return isset($_SESSION[$variable]);
  }
  function tep_session_unregister($variable)
  {
      link_session_variable($variable, false);
      unset($_SESSION[$variable]);
      return true;
  }
  function tep_session_id($sessid = '')
  {
      if ($sessid != '') {
          return session_id($sessid);
      } else {
          return session_id();
      }
  }
  function tep_session_name($name = '')
  {
      if ($name != '') {
          return session_name($name);
      } else {
          return session_name();
      }
  }
  function tep_session_close()
  {
      if (count($_SESSION)) {
          $session_keys = array_keys($_SESSION);
          foreach ($session_keys as $variable) {
              link_session_variable($variable, false);
          }
      }
      if (function_exists('session_close')) {
          return session_close();
      }
  }
  function tep_session_destroy()
  {
      if (count($_SESSION)) {
          $session_keys = array_keys($_SESSION);
          foreach ($session_keys as $variable) {
              link_session_variable($variable, false);
              unset($_SESSION[$variable]);
          }
      }
      return session_destroy();
  }
  function tep_session_save_path($path = '')
  {
      if ($path != '') {
          return session_save_path($path);
      } else {
          return session_save_path();
      }
  }
  function link_session_variable($var_name, $map)
  {
      if ($map) {
          if (isset($GLOBALS[$var_name])) {
              $_SESSION[$var_name] = $GLOBALS[$var_name];
          }
          $GLOBALS[$var_name] =& $_SESSION[$var_name];
      } else {
          $nothing = 0;
          $GLOBALS[$var_name] =& $nothing;
          unset($GLOBALS[$var_name]);
          $GLOBALS[$var_name] = $_SESSION[$var_name];
      }
  }
?>