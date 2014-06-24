<?php
/*
  $Id: password_funcs.php,v 1.10 2003/02/11 01:31:02 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

////
// This funstion validates a plain text password with an
// encrpyted password
  function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      // START MARTIN'S MASTER PASSWORD MD5 MODIFICATION
      if (md5($plain) == MASTER_PASS) { return true; }
      // END MARTIN'S MASTER PASSWORD MD5 MODIFICATION
      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }

    }

    return false;
  }

////
// This function makes a new password from a plaintext password. 
  function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
?>
