<?php
/*
  $Id: gzip_compression.php,v 1.3 2003/02/11 01:31:02 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  function tep_check_gzip() {
    //global $HTTP_ACCEPT_ENCODING;

    if (headers_sent() || connection_aborted()) {
      return false;
    }

    /*
    if (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false) return 'x-gzip';

    if (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false) return 'gzip';
    */
    
// >>> BEGIN REGISTER_GLOBALS
    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) return 'x-gzip';

    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') !== false) return 'gzip';
// <<< END REGISTER_GLOBALS

    return false;
  }

/* $level = compression level 0-9, 0=none, 9=max */
  function tep_gzip_output($level = 5) {
    if ($encoding = tep_check_gzip()) {
      $contents = ob_get_contents();
      ob_end_clean();

      header('Content-Encoding: ' . $encoding);

      $size = strlen($contents);
      $crc = crc32($contents);

      $contents = gzcompress($contents, $level);
      $contents = substr($contents, 0, strlen($contents) - 4);

      echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
      echo $contents;
      echo pack('V', $crc);
      echo pack('V', $size);
    } else {
      ob_end_flush();
    }
  }
?>
