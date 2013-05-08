<?php

  /**
   * @package osC_Sec Security Class for Oscommerce / Digistore
   * @author Te Taipo <rohepotae@gmail.com>
   * @copyright (c) Hokioi-IT
   * @license http://opensource.org/licenses/gpl-license.php GNU Public License
   * @version $Id: osC_Sec.php 4.2[r5]
   * @see readme.htm
   * @link http://addons.oscommerce.com/info/7834/
   **/

  # switch off server error 'notices'
  error_reporting( 6135 );

  # set the POSIX locale
  setlocale( LC_CTYPE, "C" );

  # prevent direct viewing of osC_Sec.php
  if ( false !== strpos( strtolower( $_SERVER[ "SCRIPT_NAME" ] ), osCSec_selfchk() ) ) senda404Header();
  
  # include the settings file osc.php
  if ( file_exists( rtrim( dirname( __file__ ), '/\\' ) . DIRECTORY_SEPARATOR . 'osc.php' ) ) {
     require_once( rtrim( dirname( __file__ ), '/\\' ) . DIRECTORY_SEPARATOR . 'osc.php' );
  }
  if ( !isset( $osc_check ) )
     die( "<center><font face=verdana size=1>osC_Sec Warning: upload the latest version of the osc.php file before proceeding.</font></center>");
  
  class osC_Sec {

    function Sentry( $timestampOffset=0,$nonGETPOSTReqs=0,$spiderBlock=0,$banipaddress=0,$useIPTRAP=0,
                     $ipTrapBlocked=NULL,$emailenabled=0,$youremail=NULL,$fromemail=NULL ) {
      
      global $PHP_SELF;
      $this->_timestampOffset = $timestampOffset;
      $this->_nonGETPOSTReqs = $nonGETPOSTReqs;
      $this->_banipaddress = $banipaddress;
      $this->_useIPTRAP = $useIPTRAP;
      $this->_ipTrapBlocked = $ipTrapBlocked;
      $this->_emailenabled = $emailenabled;
      $this->_youremail = $youremail;
      $this->_fromemail = $fromemail;

      $this->_currentVersion = "4.2[r5]";
      $this->_oscsec_threshold = false;
      $this->_oscsec_reason = NULL;
      $this->_osCCookieID1 = "osCsid";
      $this->_osCCookieID2 = "osCAdminID";
      $this->_http_server = ( getenv( "HTTPS" ) == "on" ) ? "https://" : "http://";

      # check settings are correct
      $this->chkSetup();

      # make sure $_SERVER[ "REQUEST_URI" ] is set
      $this->oscsec_fix_server_vars();
      
      # reliably set $PHP_SELF as a filename
      $PHP_SELF = $this->phpSelfFix();

      # ban bad harvesting spiders
      if ( isset( $spiderBlock ) && ( false !== ( bool )$spiderBlock ) ) $this->badArachnid();

      # set the host address to be used in the email notification and htaccess
      $this->_oschttphost = preg_replace( "/^(?:([^\.]+)\.)?domain\.com$/", "\1", $_SERVER[ "SERVER_NAME" ] );
    
      # set the path to the htaccess in the root catalog
      if ( $this->_banipaddress ) $this->_oschtaccessfile = $this->strCharsfrmStr( $this->getDir() . ".htaccess", "//", "/" );

      # set the path to the IP_Trapped.txt file
      if ( $this->_useIPTRAP ) $this->_ipTrappedURL = $this->strCharsfrmStr( $this->getDir() . "banned/IP_Trapped.txt", "//", "/" );

      # if ip address already in the trapped banlist, redirect to blocked.php
      if ( false !== $this->ipTrapped() ) {
          header( "Location: " . $this->_ipTrapBlocked );
          exit;
      }

      # prevent non-standard requests:
      if ( ( false !== $this->oscSecBypass() ) && ( false !== ( bool )$this->_nonGETPOSTReqs ) ) $this->checkReqType();

      # check for database injection attempts
      $this->dbShield();

      # check _GET requests against the blacklist
      $this->getShield();

      # check _POST variables against the blacklist
      $this->postShield();

      # run through $_COOKIE checking against blacklists
      $this->cookieShield();
      
      # PHP5 with register_long_arrays off? From SoapCMS Core Security Class
      if ( @phpversion() >= "5.0.0"
            && ( !ini_get( "register_long_arrays" )
            || @ini_get( "register_long_arrays" ) == "0"
            || strtolower( @ini_get( "register_long_arrays" ) ) == "off" ) ) {

            $HTTP_POST_VARS = $_POST;
            $HTTP_GET_VARS = $_GET;
            $HTTP_SERVER_VARS = $_SERVER;
            $HTTP_COOKIE_VARS = $_COOKIE;
            $HTTP_ENV_VARS = $_ENV;
            $HTTP_POST_FILES = $_FILES;

            # _SESSION is the only superglobal which is conditionally set
            if ( isset( $_SESSION ) ) $HTTP_SESSION_VARS = $_SESSION;
      }

      # merge $_REQUEST with cleaned _GET and _POST excluding _COOKIE data
      $_REQUEST = array_merge( $_GET, $_POST );

    } // end of Sentry function

    /**
     * banChecker()
     * 
     * @return
     */
    function banChecker() {
        ( ( false !== $this->oscSecBypass() ) && ( false !== $this->getThreshold() ) ) ? $this->tinoRahui() : NULL;
    }
    /**
     * getThreshold()
     * 
     * @return
     */
    function getThreshold() {
        if ( false !== ( bool )$this->_oscsec_threshold ) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * osCEmailer()
     * 
     * @return
     */
    function osCEmailer() {

        # full printout of server variables
        $fullreport = true;
  
        # disable the emailer if htaccess not writable when .htaccess banning is enabled
        if ( false !== ( bool )$this->_banipaddress ) {
            if ( !$this->hCoreFileChk( $this->_oschtaccessfile ) ) return;
        }
  
        # send the notification
        if ( ( false !== ( bool )$this->_banipaddress ) && ( false !== ( bool )$this->_emailenabled ) ) {
            if ( ( false !== ( bool )$this->_banipaddress ) && ( $this->hCoreFileChk( $this->_oschtaccessfile ) ) ) {
                $banAction = "htaccess banned";
            } elseif ( ( false !== ( bool )$this->_useIPTRAP ) ) {
                $banAction = "IP Trap banned";
            }
            if ( !isset( $this->_timestampOffset ) ) $this->_timestampOffset = 0;
            $timestamp = gmdate( "D, d M Y H:i:s", time() + ( $this->_timestampOffset * 3600 ) );
            $to = $this->_youremail;
            $subject = $this->_oschttphost . " " . ( substr( $this->_oscsec_reason, 0, 60 ) ) . "...";
            $body = "This IP [ " . $this->getRealIP() . " ] has been " . $banAction . " on the http://" . $this->_oschttphost .
                " website by osC_Sec.php version " . $this->_currentVersion . "\n\nREASON FOR BAN: " . $this->_oscsec_reason . "\n\nTime of ban: " .
                $timestamp . "\n";
            $body .= "\n.------------[ ALL \$_GET VARIABLES ]-------------\n#\n";
            if ( !empty( $_GET ) ) {
                $sDimGET = $this->array_flatten( $_GET, true );
                foreach ( $sDimGET as $k => $v ) {
                    if ( empty( $v ) ) $v = "NULL";
                    if ( !is_array( $k ) && !is_array( $v ) ) $body .= "# - " . $k . " = " . htmlspecialchars( $v ) . "\n";
                }
            } else {
                $body .= "# - No \$_GET data\n";
            }
            $body .= "#\n`--------------------------------------------------------\n";
            $body .= "\n.---------[ ALL \$_POST FORM VARIABLES ]-------\n#\n";
            if ( ( isset( $_POST ) ) && ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) ) {
                $sDimPOST = $this->array_flatten( $_POST, true );
                foreach ( $sDimPOST as $k => $v ) {
                    if ( empty( $v ) ) $v = "NULL";
                    if ( !is_array( $k ) && !is_array( $v ) ) $body .= "# - " . $k . " = " . htmlspecialchars( $v ) . "\n";
                }
            } else {
                $body .= "# - No POST form data\n";
            }
            $body .= "#\n`--------------------------------------------------------\n";
            $body .= "\n.------------[ \$_SERVER VARIABLES ]--------------\n#\n";
            if ( false !== $fullreport ) {
                $sDimSERVER = $this->array_flatten( $_SERVER, true );
                foreach ( $sDimSERVER as $k => $v ) {
                    if ( empty( $v ) ) $v = "NULL";
                    if ( !is_array( $k ) && !is_array( $v ) ) $body .= "# - " . $k . " = " . htmlspecialchars( $v ) . "\n";
                }
            } else {
                # short report
                $serverVars = new ArrayIterator( array( "HTTP_HOST", "HTTP_USER_AGENT", "SERVER_ADDR", "REMOTE_ADDR",
                    "DOCUMENT_ROOT", "SCRIPT_FILENAME", "REQUEST_METHOD", "REQUEST_URI", "SCRIPT_NAME", "QUERY_STRING",
                    "HTTP_X_CLUSTER_CLIENT_IP", "HTTP_X_FORWARDED_FOR", "HTTP_X_ORIGINAL_URL", "ORIG_PATH_INFO",
                    "HTTP_X_REWRITE_URL", "HTTP_CLIENT_IP", "HTTP_PROXY_USER", "REDIRECT_URL", "SERVER_SOFTWARE" ) );
                while ( $serverVars->valid() ) {
                    if ( array_key_exists( $serverVars->current(), $_SERVER ) && !empty( $_SERVER[$serverVars->current()] ) ) {
                        $body .= "# - \$_SERVER[ \"" . $serverVars->current() . "\" ] = " . $_SERVER[$serverVars->current()] .
                            "\n";
                    }
                    $serverVars->next();
                }
  
            }
  
            $body .= "# - $PHP_SELF filename ( osC_Sec ) = " . $this->phpSelfFix() . "\n";
            $body .= "#\n`--------------------------------------------------------\n\n";
            $body .= "OTHER INFO\n";
            $body .= $this->_oschtaccessfile;
            $body .= "\n";
            $body .= "is htaccess writeable = " . ( $this->hCoreFileChk( $this->_oschtaccessfile ) );
            $body .= "\n\nResolve IP address: http://en.utrace.de/?query=" . $this->getRealIP() . "\n";
            $body .= "Search Project Honeypot: http://www.projecthoneypot.org/ip_" . $this->getRealIP() . "\n\n";
            $body .= "This email was generated by osC_Sec. To disable email notifications," .
                " open osc.php file, and in the Settings section change $emailenabled" . " = 1 to $emailenabled = 0\n\n";
            $body .= "Keep up with the latest version of osC_Sec.php at http://addons.oscommerce.com/info/7834 and http://goo.gl/dQ3jH\n";
            $body .= "See discussions at http://www.digistore.co.nz/forum/viewtopic.php?f=10&t=7" .
                " or email rohepotae@gmail.com with any suggestions.\n\n";
            $from = "From: " . $this->_fromemail;
            ( mail( $to, $subject, $body, $from ) );
        }
        return;
    }
    /**
     * senda403Header()
     * 
     * @return
     */
    function senda403Header() {
        $header = array( "HTTP/1.1 403 Access Denied", "Status: 403 Access Denied", "Content-Length: 0" );
        foreach ( $header as $sent ) {
            header( $sent );
        }
        die();
    }

    /**
     * tinoRahui()
     * 
     * @return
     */
    function tinoRahui() {
        if ( ( $this->_banipaddress ) && ( $this->hCoreFileChk( $this->_oschtaccessfile ) ) ) {
            # send an email
            $this->osCEmailer();
            # add ip to htaccess
            $this->htaccessbanip( $this->getRealIP() );
            # call an access denied header
            $this->senda403Header();
            return;
        } elseif ( ( false !== ( bool )$this->_useIPTRAP ) && ( $this->hCoreFileChk( $this->_ipTrappedURL ) ) ) {
            # send an email
            $this->osCEmailer();
            # add ip to iptrap ban file
            $this->ipTrapban( $this->getRealIP() );
            # redirect to blocked.php
            header( "Location: " . $this->_ipTrapBlocked );
            exit;
        } elseif ( ( false !== ( bool )$this->_banipaddress ) && ( !$this->hCoreFileChk( $this->_oschtaccessfile ) ) ) {
            # if non-wrtiable htaccess then call an access denied header
            $this->senda403Header();
            return;
        } elseif ( ( false !== ( bool )$this->_useIPTRAP ) && ( !$this->hCoreFileChk( $this->_ipTrappedURL ) ) ) {
            # if non-wrtiable iptrap file then call an access denied header
            header( "Location: " . $this->_ipTrapBlocked );
            exit;
        } elseif ( ( false === ( bool )$this->_banipaddress ) && ( false === ( bool )$this->_useIPTRAP ) ) {
            # if no banip or iptrap then call an access denied header
            $this->senda403Header();
            return;
        }
    }
    /**
     * databaseShield()
     */
    function dbShield() {
      if ( false === $this->oscSecBypass() ) return;
      $v = $_SERVER[ "QUERY_STRING" ];
      if ( strlen( $v ) > 0 ) {
          $orig_v = $v;
      } else return;
  
      if ( ( "POST" !== $_SERVER[ "REQUEST_METHOD" ] ) && ( is_array( $_GET ) ) ) {
         foreach( $_GET as $k => $v ) {
           if ( ( !empty( $v ) ) && ( is_array( $v ) ) ) $v = implode(" ", $v );
             if ( ( !empty( $v ) ) && ( !is_array( $v ) ) ) {
                if ( false !== ( bool )$this->injectionMatch( strtolower( $v ) ) ) {
                       $this->_oscsec_reason .= "osC_Sec detected a database injection attempt: [ " . stripslashes( $orig_v ) . " ]. ";
                       $this->_oscsec_threshold = true;
                       $this->banChecker();
                       return;
                }
                $v = base64_decode( urldecode( $v ) );
                if ( false !== ( bool )$this->injectionMatch( strtolower( $v ) ) ) {
                  $this->_oscsec_reason .= "osC_Sec detected a database injection attempt: [ " . stripslashes( $orig_v ) . " ]. ";
                  $this->_oscsec_threshold = true;
                  $this->banChecker();
                  return;
                }
             }
         }
      }
    }
    /**
     * postShield()
     * 
     * @return
     */
    function postShield() {
        if ( ( !isset( $_POST ) ) || ( "POST" !== $_SERVER[ "REQUEST_METHOD" ] )
            || ( false === $this->oscSecBypass() ) ) return;
        $oscsec_postvar_blacklist = array( "eval(base64_decode(", "eval(", "passthru(base64_decode", "base64_", "table_schema", ",0x3a,", "concat(", "unescape(",
            "fromcharcode", "php/login", "pwtoken_get", "php_uname", "passthru","%23include+<", "-1+union+select+", "cookie=4","allow_url_fopen", "shell_exec", 
            "get_defined_vars(", "strrev(", "%22\"%2f", "error_reporting(0)", "fwrite(", "+or+benchmark(", "waitfor delay","gzinflate(", "or \"=\"", "or%20\"=\"",
            "or%20\%27=\%27", "or%20\%22=\%22", "or \%22=\%22", "prompt(","php_value%20auto", "php_value+auto", "file_get_contents(", "setcookie(" );

        $pnodes = $this->array_flatten( $_POST, false );
        $i = 0;
        while ( $i < count( $pnodes ) ) {
            $pnode = $pnodes[$i];
            $pnode64 = strtolower( base64_decode( $pnodes[$i] ) );
            foreach ( $oscsec_postvar_blacklist as $blacklisted ) {
                $blacklisted = strtolower( $blacklisted );
                if ( ( is_string( $pnodes[$i] ) ) && ( strlen( $pnodes[$i] ) > 0 ) ) {
                    if ( ( false !== strpos( $pnode64, $blacklisted ) ) || ( false !== strpos( $pnode64, urldecode( $blacklisted ) ) ) ) {
                        $this->_oscsec_reason .= "osC_Sec blacklisted base64 encoded item is banned: " . htmlspecialchars( stripslashes( $blacklisted ) ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    } elseif ( ( false !== strpos( $pnode, $blacklisted ) ) || ( false !== strpos( strtolower( urldecode( $pnode ) ), urldecode( $blacklisted ) ) ) ) {
                        $this->_oscsec_reason .= "osC_Sec blacklisted item is banned: " . htmlspecialchars( stripslashes( $blacklisted ) ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    }
                }
            }
            $i++;
        }
    }
    /**
     * getShield()
     * 
     * @return
     */
    function getShield() {
        if ( false === $this->oscSecBypass() ) return;
        $oscsec_reqvar_blacklist = array(
        "php/login", "eval(base64_decode(", "asc%3Deval", "asc%3Deval", "eval%28", "eval%2528", "eval(", "fromCharCode", "; base64", "base64_", "base64,", 
        "_START_", "onerror=alert(", "mysql_query", "../cmd", "rush=", "pwtoken_get", "EXTRACTVALUE(", "phpinfo()", "1=1--", "%000", "current_user()", "lpad(", 
        "php_uname", "%3Cform", "passthru(", "sha1(", "\..\..", "<%3Fphp", "}%00.", "%%", "1+and+1", "/iframe", "\$_GET", "unhex(", "ob_starting", "%20and%201=1",
        "document.cookie", "onload%3d", "onunload%3d", "PHP_SELF", "etc/passwd", "shell_exec", "data://", "\$_SERVER", "substr(", "information_schema",
        "\$_POST", "cookie=4", "\$_SESSION", "\$_REQUEST", "GLOBALS[", "\$HTTP_", ".php/admin", "hex_ent", "mosConfig_", "cookies=1", "%3C@replace(",
        "inurl:", "replace(", "onload=", "/iframe", "return%20clk", "login.php?action=backupnow", "php/password_for", "@@datadir", "@@version",
        "=alert(", "version()", "localhost", "})%3B", "/FRAMESET", "Set-Cookie", "JHs=", "%253C%2Fscript%253E" );

        $sqlfilematchlist = "\balias\b|\.bat|bin|\bboot\b|config|\benviron\b|etc|\.exe|\.ht|\.ini|
                            \.js|\blib\b|log|\bproc\b|\bsql\b|tmp|\.txt|\bvar\b|(?:uploa|passw)d";
        $sqlfilematchlist = preg_replace( "/[\s]/i", "", $sqlfilematchlist );

        $thenode = strtolower( $_SERVER[ "REQUEST_URI" ] );
        $v = urldecode( $thenode ); // first of two urldecodes
        $v = preg_replace( "/[^\w\s\p{L}\d\r?,=@%:{}\/.-]/i", "", urldecode( $v ) );

        if ( ( false !== ( bool )preg_match("/mouse(?:down|over)/i", $v ) )
            && ( false !== ( bool )preg_match("/c(?:path|tthis|t\(this)|(?:forgotte|admi)n|sqlpatch|,,|ftp:|(?:aler|promp)t/i", $v ) ) ) {
            $injectattempt = true;
        } elseif ( ( ( false !== strpos( $v, "ftp:" ) ) && ( substr_count( $v, "ftp" ) > 1 ) )
            && ( false !== ( bool )preg_match("/@|:|\/\//i", $v ) ) ) {
            $injectattempt = true;
        } elseif ( ( false !== ( bool )preg_match("/(?:showimg|cookie|cookies)=/i", $v ) ) && ( "POST" == $_SERVER[ "REQUEST_METHOD" ] ) ) {
            $injectattempt = true;
        } elseif ( ( ( substr_count( $v, "../" ) > 2 ) || ( substr_count( $v, "..//" ) > 2 ) )
            && ( false !== ( bool )preg_match("/$sqlfilematchlist/i", $v ) ) ) {
            $injectattempt = true;
        } elseif ( ( false !== strpos( $v, "http:" ) )
            && ( false !== ( bool )preg_match("/(?:dir|path)=/i", $v ) ) ) {
            $injectattempt = true;
        } else $injectattempt = false;
        if ( false !== ( bool )$injectattempt ) {
            $this->_oscsec_reason .= "osC_Sec detected an attempt to read or include unauthorized file content. ";
            $this->_oscsec_threshold = true;
            $this->banChecker();
            return;
        }
        foreach ( $oscsec_reqvar_blacklist as $blacklisted ) {
            $blacklisted = strtolower( urldecode( $blacklisted ) );
            # check the request_uri against the blacklist irregardless of request type
            if ( ( false !== strpos( $thenode, $blacklisted ) ) ||
                 ( false !== strpos( urldecode( urldecode( $thenode ) ), urldecode( $blacklisted ) ) ) ) {
                $this->_oscsec_reason .= "osC_Sec blacklist item request_uri is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                $this->_oscsec_threshold = true;
                $this->banChecker();
                return;
            }
        }
        # run through the blacklist after filtering out banned characters
        if ( ( "POST" !== $_SERVER[ "REQUEST_METHOD" ] ) && ( is_array( $_GET ) ) ) {
            foreach( $_GET as $k => $v ) {
              if ( ( !empty( $v ) ) && ( is_array( $v ) ) ) $v = implode(" ", $v );
                if ( ( !empty( $v ) ) && ( !is_array( $v ) ) ) {
                  $v = preg_replace( "/[^\w\s\p{L}\d\r?,€=@%:{}\/.-]/i", "", urldecode( $v ) );
                  foreach ( $oscsec_reqvar_blacklist as $blacklisted ) {
                    $blacklisted = strtolower( urldecode( $blacklisted ) );
                    if ( ( false !== strpos( $v, $blacklisted ) )
                         || ( false !== strpos( urldecode( urldecode( $v ) ), urldecode( $blacklisted ) ) ) ) {
                            $this->_oscsec_reason .= "osC_Sec listed query string item is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                            $this->_oscsec_threshold = true;
                            $this->banChecker();
                            return;
                    }
                  }
                }
            }
        }
        # check each part of the query string against the list
        $gnodes = explode( "&", $_SERVER[ "QUERY_STRING" ] );
        $i = 0;
        while ( $i < count( $gnodes ) ) {
            if ( is_string( $gnodes[$i] ) ) {
                $tmp = explode( "=", $gnodes[$i] );
                if ( is_array( $tmp ) ) {
                    $gvar = $tmp[count( $tmp ) - count( $tmp )];
                    $gval = $tmp[count( $tmp ) - 1];
                }
                $gvar = strtolower( $gvar );
                $gval = strtolower( $gval );
                $x = 0;
                foreach ( $oscsec_reqvar_blacklist as $blacklisted ) {
                    $blacklisted = strtolower( urldecode( $blacklisted ) );
                    if ( ( false !== strpos( $gvar, $blacklisted ) ) || ( false !== strpos( urldecode( urldecode( $gvar ) ), urldecode( $blacklisted ) ) ) ) {
                        $this->_oscsec_reason .= "getShield() listed query_string variable is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    }
                    if ( ( $x < ( count( $oscsec_reqvar_blacklist ) - 1 ) ) && ( ( false !== strpos( $gval, $blacklisted ) ) ||
                        ( false !== strpos( urldecode( urldecode( $gval ) ), urldecode( $blacklisted ) ) ) ) ) {
                        $this->_oscsec_reason .= "osC_Sec blacklist query_string value is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    }
                    $x++;
                }
            }
            $i++;
        }
    }
    /**
     * cookieShield()
     * 
     * @return
     */
    function cookieShield() {
        if ( false === $this->oscSecBypass() ) return;
        $oscsec_cookie_blacklist = array( "eval(", "base64_", "fromCharCode", "%27/*", "%27+and", "prompt(", "\"+OR+(", "\"%20OR%20(", "\"+OR+(", ")=\"",
                                          "ZXZhbCg=", "ZnJvbUNoYXJDb2Rl", "U0VMRUNULyoqLw==", "Ki9XSEVSRS8q" );

        $cnodekeys = array_keys( $_COOKIE );
        $cnodevals = array_values( $_COOKIE );

        if ( !empty( $cnodevals ) ) {
          if ( is_array( $cnodevals ) ) {
             $v = implode(" ", $cnodevals );
          } else $v = $cnodevals;
          if ( !is_array( $v ) ) {
              $orig_v = $v;
              $injectattempt = $this->injectionMatch( $v );
              if ( isset( $injectattempt ) && ( false !== ( bool )$injectattempt ) ) {
                      $this->_oscsec_reason .= "osC_Sec detected malicious cookie content: [ " . stripslashes( $orig_v ) . " ].";
                      $this->_oscsec_threshold = true;
                      $this->banChecker();
                      return;
              }
          }
        }
        $i = 0;
        while ( $i < count( $cnodekeys ) ) {
            $cnodekey = strtolower( $cnodekeys[$i] );
            $cnodeval = strtolower( $cnodevals[$i] );
            if ( ( is_string( $cnodekeys[$i] ) ) ) {
                foreach ( $oscsec_cookie_blacklist as $blacklisted ) {
                    $blacklisted = strtolower( $blacklisted );
                    if ( ( false !== strpos( $cnodekey, $blacklisted ) ) || ( false !== strpos( urldecode( urldecode( $cnodekey ) ), urldecode( $blacklisted ) ) ) ) {
                        $this->_oscsec_reason .= "osC_Sec \$cnodekeys listed item is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    }
                    if ( ( false !== strpos( $cnodeval, $blacklisted ) ) || ( false !== strpos( urldecode( urldecode( $cnodeval ) ), urldecode( $blacklisted ) ) ) ) {
                        $this->_oscsec_reason .= "osC_Sec \$cnodevals listed item is banned: " . htmlspecialchars( $blacklisted ) . ". ";
                        $this->_oscsec_threshold = true;
                        $this->banChecker();
                        return;
                    }
                }
            }
            $i++;
        }
    }
    /**
     * injectionMatch()
     * 
     * @param mixed $string
     * @return
     */
    function injectionMatch( $string ) {
      $string = urldecode( $string );
      $string = preg_replace( "/[^\w\s\p{L}\d\r?,(=@%:{}\/.-]/i", "", urldecode( $string ) ); // urldecode twice
      $string = strtolower( $string );
      $string = str_replace( "//", " ", $string );
      $sqlmatchlist = "(?:ascii|base64|bin|benchmark|cast|chr|char|charset|collation|concat|concat_ws|
                        conv|convert|count|database|decode|diff|distinct|elt|encode|encrypt|
                        extract|field|floor|format|hex|if|in|insert|instr|interval|lcase|left|
                        length|load_file|locate|lock|log|lower|lpad|ltrim|max|md5|mid|mod|now|
                        null|ord|password|position|quote|rand|repeat|replace|reverse|right|rlike|
                        row_count|rpad|rtrim|_set|schema|sha1|sha2|sleep|soundex|space|strcmp|
                        substr|substr_index|substring|sum|time|trim|truncate|ucase|unhex|upper|
                        _user|user|values|varchar|version|xor)\(|\(0x|0x|@@|cast|integer";
                        $sqlmatchlist = preg_replace( "/[\s]/i", "", $sqlmatchlist );
      if ( false !== ( bool )preg_match("/\bdrop\b/i", $string )
          && false !== ( bool )preg_match("/\btable\b|\buser\b/i", $string )
          && false !== ( bool )preg_match("/--|\//i", $string ) ) {
            return true;
      } elseif ( ( false !== strpos( $string, "grant" ) )
              && ( false !== strpos( $string, "all" ) )
              && ( false !== strpos( $string, "privileges" ) ) ) {
            return true;
      } elseif ( false !== preg_match_all( "/\bload\b|\bdata\b|\binfile\b|\btable\b|\bterminated\b/i", $string, $matches ) > 3 ) {
            return true;
      } elseif ( ( ( false !== ( bool )preg_match("/select|declare/i", $string ) )
        || ( false !== ( bool )preg_match("/\band\b/i", $string ) ) || ( false !== ( bool )preg_match("/\bif\b/i", $string ) ) )
        && ( false !== preg_match_all( "/$sqlmatchlist/", $string, $matches ) > 0 ) ) {
            return true;
      } elseif ( false !== preg_match_all( "/$sqlmatchlist/", $string, $matches ) > 1 ) {
            return true;
      } elseif ( false !== strpos( $string, "update" ) && false !== ( bool )preg_match("/\bset\b/i", $string )
            && ( false !== ( bool )preg_match("/\bcolumn\b|\bdata\b|concat\(|\bemail\b|\blogin\b|\bname\b|\bpass\b|\btable\b|\bwhere\b|\buser\b|\bval\b|0x/i", $string ) ) ) {
            return true;
      }
      $string = preg_replace( "/[^\w\s\p{L}\d\r?,=@%:{}\/.-]/i", "", $string );
      $sqlmatchlist = "--|\ball\b|_and|ascii|b(?:enchmark|etween|in|itlength|ulk)|
                       c(?:ast|har|ookie|ollate|oncat|urrent)|\bdate\b|dump|e(?:lt|xport)|
                       false|\bfield\b|fetch|format|\bfrom\b|function|\bhaving\b|
                       b|i(?:dentity|nforma|nstr|nto)|\bif\b|l(?:case|eft|ength|ike|oad|ocate|ower|pad|trim)|
                       m(:?ake|atch|d5|id)|not_like|not_regexp|outfile|p(?:ass|ost|osition|riv)|\bquote\b|
                       \br(?:egexp\b|ename\b|epeat\b|eplace\b|equest\b|everse\b|eturn\b|ight\b|like\b|pad\b|trim\b)|
                       \bs(?:ql\b|hell\b|trcmp\b|ubstr\b)|\bt(?:able\b|rim\b|rue\b|runcate\b)|
                       u(?:case|nhex|pdate|pper|ser)|values|varchar|\bwhen\b|with|0x|
                       _(?:decrypt|encrypt|get|post|server|cookie|global|or|request|xor)|
                       (?:column|load|not|octet|table|xp)_";
                      $sqlmatchlist = preg_replace( "/[\s]/i", "", $sqlmatchlist );
      if ( false !== strpos( $string, "by" ) && ( false !== ( bool )preg_match("/\border\b|\bgroup\b/i", $string ) )
                && ( false !== ( bool )preg_match("/\bcolumn\b|\bdesc\b|\berror\b|\bfrom\b|\bhav\b|\blimit\b|\boffset\b|\btable\b|\/|--/i", $string ) ) ) {
            return true;
      } elseif ( ( false !== ( bool )preg_match("/\btable\b|\bcolumn\b/i", $string  ) ) && false !== strpos( $string, "exists" )
                && ( false !== ( bool )preg_match("/\bif\b|\berror\b|\buser\b|\bno\b/i", $string ) ) ) {
            return true;
      } elseif ( ( ( false !== strpos( $string, "waitfor" ) && false !== strpos( $string, "delay" ) && ( ( bool )preg_match( "/(:)/i", $string ) ) )
         || false !== strpos( $string, "nowait" ) )
                && ( false !== ( bool )preg_match("/--|\/|\blimit\b|\bshutdown\b|\bupdate\b|\bdesc\b/i", $string ) ) ) {
            return true;
      } elseif ( false !== ( bool )preg_match("/\binto\b/i", $string )
              && ( false !== ( bool )preg_match("/\boutfile\b/i", $string ) ) ) {
            return true;
      } elseif ( false !== ( bool )preg_match("/\bdrop\b/i", $string )
              && ( false !== ( bool )preg_match("/\buser\b/i", $string ) ) ) {
            return true;
      } elseif ( ( ( false !== strpos( $string, "create" ) && false !== ( bool )preg_match( "/\btable\b|\buser\b/i", $string ) )
         || ( false !== strpos( $string, "delete" ) && false !== strpos( $string, "from" ) ) 
         || ( false !== strpos( $string, "insert" ) && ( false !== ( bool )preg_match("/\bexec\b|\binto\b|from/i", $string ) ) )
         || ( false !== strpos( $string, "select" ) && ( false !== ( bool )preg_match( "/\bcase\b|from|\bif\b|\binto\b|ord|union/i", $string ) ) ) )
            && ( false !== ( bool )preg_match( "/$sqlmatchlist/i", $string ) ) ) {
            return true;
      } elseif ( false !== strpos( $string, "null" ) ) {
        $nstring = preg_replace( "/[^a-z]/i", "", urldecode( $string ) );
        if ( false !== ( bool )preg_match( "/(null){2,}/i", $nstring ) ) {
            return true;
        } else return false;
      } else return false;
    }
    /**
     * htaccessbanip()
     * 
     * @param mixed $banip
     * @return
     */
    function htaccessbanip( $banip ) {
        if ( !isset( $this->_oschtaccessfile ) ) return $this->senda403Header();
        $limitend = "# End of $this->_oschttphost Osc_Sec Ban\n";
        $newline = "deny from $banip\n";
        if ( file_exists( $this->_oschtaccessfile ) ) {
            $mybans = file( $this->_oschtaccessfile );
            $lastline = "";
            if ( in_array( $newline, $mybans ) ) exit();
            if ( in_array( $limitend, $mybans ) ) {
                $i = count( $mybans ) - 1;
                while ( $mybans[$i] != $limitend ) {
                    $lastline = array_pop( $mybans ) . $lastline;
                    $i--;
                }
                $lastline = array_pop( $mybans ) . $lastline;
                $lastline = array_pop( $mybans ) . $lastline;
                array_push( $mybans, $newline, $lastline );
            } else {
                array_push( $mybans, "\n\n# $this->_oschttphost Osc_Sec Ban\n", "order allow,deny\n", $newline,
                    "allow from all\n", $limitend );
            }
        } else {
            $mybans = array( "# $this->_oschttphost Osc_Sec Ban\n", "order allow,deny\n", $newline, "allow from all\n", $limitend );
        }
        $myfile = fopen( $this->_oschtaccessfile, "w" );
        fwrite( $myfile, implode( $mybans, "" ) );
        fclose( $myfile );
    }
    /**
     * ipTrapped()
     * 
     * @return
     */
    function ipTrapped() {
        if ( false !== ( bool )$this->_useIPTRAP ) {
            # if IP is already in IP Trap list then redirect
            $mybans = file( $this->_ipTrappedURL );
            $mybans = array_values( $mybans );
            foreach ( $mybans as $i => $value ) {
                if ( strlen( $mybans[$i] > 0 ) ) {
                    # find IP address in IP Trap ban list
                    if ( false !== strpos( $mybans[$i], $this->getRealIP() ) ) {
                        $this->_emailenabled = 0;
                        return true;
                    }
                }
            }
        }
        return false;
    }
    /**
     * my_array_filter_fn()
     * 
     * @param mixed $val
     * @return
     */
    function my_array_filter_fn( $val ) {
        $val = trim( $val );
        $allowed_vals = array( "0" );
        return in_array( $val, $allowed_vals, true ) ? true : ( $val ? true : false );
    }
    /**
     * ipTrapban()
     * 
     * @param mixed $banip
     * @return
     */
    function ipTrapban( $banip ) {
        $bannedAlready = false;
        $limitend = "\n";
        $newline = "$banip";
        if ( file_exists( $this->_ipTrappedURL ) ) {
            $mybans = file( $this->_ipTrappedURL );
            $lastline = "";
            $mybans = array_filter( $mybans, array( "osC_Sec", "my_array_filter_fn" ) );
            $mybans = array_values( $mybans );
            $endIPTrapIP = "999.999.999.999";
            foreach ( $mybans as $i => $value ) {
                if ( strlen( $mybans[$i] > 0 ) ) {
                    if ( false !== strpos( $mybans[$i], $newline ) ) $bannedAlready = true;
                }
            }
            foreach ( $mybans as $i => $value ) {
                if ( false !== strpos( $mybans[$i], " " ) ) $mybans[$i] = str_replace( " ", "", $mybans[$i] );
                if ( ( false === ( bool )preg_match( "`[\r\n]`", $mybans[$i] ) ) ) $mybans[$i] = $mybans[$i] . "\n";
            }
            if ( false !== ( bool )$bannedAlready ) {
                $myfile = fopen( $this->_ipTrappedURL, "w" );
                fwrite( $myfile, implode( $mybans, "" ) );
                fclose( $myfile );
            }
            if ( false === ( bool )$bannedAlready ) {
                if ( ( false !== strpos( $mybans[$i], $endIPTrapIP ) ) ) unset( $mybans[$i] );
                if ( in_array( $limitend, $mybans ) ) {
                    $i = count( $mybans ) - 1;
                    while ( $mybans[$i] != $limitend ) {
                        $lastline = array_pop( $mybans ) . $lastline;
                        $i--;
                    }
                    array_push( $mybans, $newline, $endIPTrapIP );
                } else {
                    array_push( $mybans, "\n", $newline, $endIPTrapIP );
                }
            } else {
                if ( false === ( bool )$bannedAlready ) {
                    $mybans = array( "\n", $newline, $endIPTrapIP );
                }
            }
            if ( false === ( bool )$bannedAlready ) {
                $mybans = array_filter( $mybans, array( "osC_Sec", "my_array_filter_fn" ) );
                $mybans = array_values( $mybans );
                $i = 0;
                foreach ( $mybans as $i => $value ) {
                    if ( false !== strpos( $mybans[$i], " " ) ) $mybans[$i] = str_replace( " ", "", $mybans[$i] );
                    if ( ( false === ( bool )preg_match( "`[\r\n]`", $mybans[$i] ) ) ) $mybans[$i] = $mybans[$i] . "\n";
                }
                $myfile = fopen( $this->_ipTrappedURL, "w" );
                fwrite( $myfile, implode( $mybans, "" ) );
                fclose( $myfile );
            }
        }
    }
    /**
     * hCoreFileChk()
     * 
     * @param mixed $filename
     * @return
     */
    function hCoreFileChk( $filename ) {
        if ( is_writable( $filename ) ) {
            return true;
        }
        return false;
    }
    /**
     * checkfilename()
     * 
     * @param mixed $fname
     * @return
     */
    function checkfilename( $fname ) {
        if ( ( !empty( $fname ) ) && ( substr_count( $fname, ".php" ) == 1 ) && ( substr_count( $fname, "." ) == 1 ) ) {
            if ( ( ( strlen( $fname ) ) - ( strpos( $fname, "." ) ) ) <> 4 ) {
                return false;
            } elseif ( ( false !== is_readable( $fname ) ) || ( false !== strpos( $_SERVER[ "SCRIPT_NAME" ], "ext/modules/" ) ) ) return true;
        } else return false;
        return false;
    }
    /**
     * phpSelfFix()
     * 
     * @return
     */
    function phpSelfFix() {
        global $PHP_SELF;
        if ( false !== ( bool )ini_get( "register_globals" ) || ( !isset( $HTTP_SERVER_VARS ) ) ) $HTTP_SERVER_VARS = $_SERVER;
        if ( isset( $PHP_SELF ) ) {
            # earlier script has set the $PHP_SELF
            $filename = $PHP_SELF;
            if ( false === $this->checkfilename( $filename ) ) {
                $filename = NULL;
            } else return $filename;
        } else $filename = NULL;

        # this is the RC3 standard code
        $filename = ( ( ( strlen( ini_get( "cgi.fix_pathinfo" ) ) > 0 )
                     && ( ( bool )ini_get( "cgi.fix_pathinfo" ) == false ) )
                     || !isset( $HTTP_SERVER_VARS[ "SCRIPT_NAME"] ) ) ?
                     basename( $HTTP_SERVER_VARS[ "PHP_SELF"] ) :
                     basename( $HTTP_SERVER_VARS[ "SCRIPT_NAME" ] );
                    if ( false === $this->checkfilename( $filename ) ) {
                        $filename = NULL;
                    } else return $filename;
  
        # if RC3 fails then try a version of FWR Media's $PHP_SELF code.
        if ( empty( $filename ) && ( false !== strpos( $_SERVER[ "SCRIPT_NAME" ], ".php" ) ) ) {
            preg_match( "@[a-z0-9_]+\.php@i", $_SERVER[ "SCRIPT_NAME" ], $matches );
            if ( is_array( $matches ) && ( array_key_exists( 0, $matches ) )
                && ( substr( $matches[0], -4, 4 ) == ".php" )
                && ( is_readable( $matches[0] )
                || ( false !== strpos( $_SERVER[ "SCRIPT_NAME" ], "ext/modules/" ) ) ) ) {
                $filename = $matches[0];
            }
            if ( false === $this->checkfilename( $filename ) ) {
                $filename = NULL;
            } else return $filename;
        }
  
        # if that fails then try osC_Sec $PHP_SELF code
        if ( empty( $filename ) && isset( $_SERVER[ "SCRIPT_FILENAME" ] ) && ( "" !== $_SERVER[ "SCRIPT_FILENAME" ] ) ) {
            $tmp = explode( "/", $_SERVER[ "SCRIPT_FILENAME" ] );
            if ( is_array( $tmp ) ) {
                $filename = $tmp[count( $tmp ) - 1];
            }
            if ( false !== $this->checkfilename( $filename ) ) {
                return $filename;
            }
        }
        if ( ( $_SERVER[ "PHP_SELF" ] == "/" ) || ( $_SERVER[ "SCRIPT_NAME" ] == "/" ) ) return "index.php";
    }
    /**
     * hex_str()
     * 
     * @param mixed $hex
     * @return
     */
    function hex_str( $hex ) {
        $string = "";
        for ( $i = 0; $i < strlen( $hex ) - 1; $i += 2 ) {
            $string .= chr( hexdec( $hex[$i] . $hex[$i + 1] ) );
        }
        return $string;
    }
    /**
     * array_flatten()
     * 
     * @param mixed $array
     * @param bool $preserve_keys
     * @return
     */
    function array_flatten( $array, $preserve_keys = false ) {
      if ( !$preserve_keys ) {
          $array = array_values( $array );
      }
      $flattened_array = array();
      foreach ( $array as $k => $v ) {
          if ( is_array( $v ) ) {
              $flattened_array = array_merge( $flattened_array, $this->array_flatten( $v, $preserve_keys ) );
          } elseif ( $preserve_keys ) {
              $flattened_array[ $k ] = $v;
          } else {
              $flattened_array[] = $v;
          }
      }
      return $flattened_array;
    }
    /**
     * huriWhakamuri()
     * 
     * @param mixed $rotator
     * @return
     */
    function huriWhakamuri( $rotator ) {
        $nodal = "";
        for ( $ctr = 0; $ctr <= strlen( $rotator ) - 1; $ctr++ ) {
            $nodal .= $rotator{strlen( $rotator ) - $ctr - 1};
        }
        return ( $nodal );
    }
    /**
     * oscSecBypass()
     * 
     * @return
     */
    function oscSecBypass() {
        global $PHP_SELF;
        if ( !isset( $PHP_SELF ) ) $PHP_SELF = $this->phpSelfFix();
        
        $filename_bypass = array();
        $dir_bypass = array();
        $osCSec_exfrmBanlist = array();
        
        # list of files to bypass. I have added a few for consideration. Try to keep this list short
        $filename_bypass = array( "sitemonitor", "protx_process.php", "dps_pxpay_result_handler.php",
            "express.php", "express_uk.php", "standard_ipn.php", "ipn.php", "express_payflow.php", "quickpay.php" );
        # bypass all files in a directory. Use this sparingly
        $dir_bypass = array( "/ext/modules/payment" );
  
        # list of IP exceptions. These examples belong to Paypal.
        $osCSec_exfrmBanlist = array( "216.113.188.202", "216.113.188.203", "216.113.188.204",
        "216.113.169.205", "216.113.188.39", "216.113.188.71", "66.211.168.91", "66.211.168.123",
        "216.113.188.52", "216.113.188.84", "66.211.168.92", "66.211.168.124", "216.113.188.10",
        "66.211.168.126", "216.113.188.11", "66.211.168.125", "66.211.168.195", "66.211.170.66",
        "66.211.168.158", "66.135.197.160", "66.211.168.194", "216.113.188.100", "66.211.168.93",
        "66.211.168.65", "66.211.168.97", "66.211.168.193", "66.211.168.209", "66.211.169.2",
        "66.211.169.65", "64.4.241.16", "64.4.241.32", "64.4.241.33", "64.4.241.34", "64.4.241.35",
        "64.4.241.36", "64.4.241.37", "64.4.241.38", "64.4.241.39", "64.4.241.49", "64.4.241.65",
        "64.4.241.129", "216.113.188.32", "216.113.188.33", "216.113.188.34", "216.113.188.35",
        "216.113.188.64", "216.113.188.65", "216.113.188.66", "216.113.188.67", "66.211.168.136",
        "66.211.168.66", "216.113.188.129", "216.113.188.130", "66.4.241.39", "66.211.168.142",
        "66.211.168.150" );
        
        $realip = $this->getRealIP();
        if ( false === empty( $osCSec_exfrmBanlist ) ) {
          foreach ( $osCSec_exfrmBanlist as $exCeptions ) {
              if ( false !== ( strlen( $realip ) == strlen( $exCeptions ) )
                  && ( false !== strpos( $realip, $exCeptions ) ) ) {
                  return false;
              }
          }
        }
        if ( false === empty( $filename_bypass ) ) {
          foreach ( $filename_bypass as $filename ) {
              if ( false !== ( strlen( $PHP_SELF ) == strlen( $filename ) )
                  && ( false !== strpos( $PHP_SELF, $filename ) ) ) {
                  return false;
              }
          }
        }
        if ( false === empty( $dir_bypass ) ) {
          foreach ( $dir_bypass as $dirname ) {
              if ( false !== strpos( $_SERVER[ "SCRIPT_NAME" ], $dirname ) ) {
                  return false;
              }
          }
        }
        return true;
    }
    /**
     * checkReqType()
     * 
     * @return
     */
    function checkReqType() {
        if ( false === $this->oscSecBypass() ) return;
        $reqType = $_SERVER[ "REQUEST_METHOD" ];
        $req_whitelist = array( "GET", "OPTIONS", "HEAD", "POST" );
        # first check for numbers in REQUEST_METHOD
        if ( false !== ( bool )preg_match( "/[0-9]+/", $reqType ) ) {
            $this->_oscsec_reason .= " Request method [ " . $_SERVER[ "REQUEST_METHOD" ] .
                " ] should not contain numbers. ";
            $this->_oscsec_threshold = true;
            $this->banChecker();
        }
        # then make sure its all UPPERCASE (for servers that do not filter the case of the request method)
        if ( false === ctype_upper( $reqType ) ) {
            $this->_oscsec_reason .= " Request method [ " . $_SERVER[ "REQUEST_METHOD" ] .
                " ] should be in all uppercase letters. ";
            $this->_oscsec_threshold = true;
            $this->banChecker();
            # lastly check against the whitelist
        } elseif ( false === in_array( $reqType, $req_whitelist ) ) {
            $this->_oscsec_reason .= " Request method [ " . $_SERVER[ "REQUEST_METHOD" ] .
                " ] is neither GET, OPTIONS, HEAD or POST. ";
            $this->_oscsec_threshold = true;
            $this->banChecker();
        }
    }
    /**
     * chkSetup()
     * 
     * @return
     */
    function chkSetup() {
        # Make sure $banipaddress and $useIPTRAP are not both activated at the same time
        if ( ( ( bool )$this->_banipaddress ) && ( ( bool )$this->_useIPTRAP ) ) die( "<p align=center><font face=verdana size=1>" .
                "<strong>WARNING</strong>: Choose either \$banipaddress or \$useIPTRAP, not both thanks.</font></p>" );
        # if using IPTrap, Make sure $ipTrapBlocked is set
        if ( ( ( bool )$this->_useIPTRAP ) && ( empty( $this->_ipTrapBlocked ) ) ) die( "<p align=center><font face=verdana size=1>" .
                "<strong>WARNING</strong>: \$ipTrapBlocked cannot be left empty in osc.php" );
        # Warn if osc.php missing or unreadable
        if ( !is_readable( rtrim( dirname( __file__ ), "/\\" ) . DIRECTORY_SEPARATOR . "osc.php" ) ) {
            die( "<p align=center><font face=verdana size=1>" .
                "<strong>WARNING</strong>: Cannot find the osc.php file</font></p>" );
        }
    }
    /**
     * getDir()
     * 
     * @return
     */
    function getDir() {
        if ( ( defined( "DIR_FS_CATALOG" ) ) && ( "/" !== substr( DIR_FS_CATALOG, -1 ) ) ) {
            $dirFS = DIR_FS_CATALOG . "/";
        } elseif ( defined( "DIR_FS_CATALOG" ) ) {
            $dirFS = DIR_FS_CATALOG;
        } elseif ( !defined( "DIR_FS_CATALOG" ) ) {
            $rootDir = $_SERVER[ "SCRIPT_NAME" ];
            if ( false !== strpos( $rootDir, "/" ) ) {
                if ( $rootDir[0] == "/" ) {
                    $rootDir = substr( $rootDir, 1 );
                    $pos = strpos( strtolower( $rootDir ), strtolower( "/" ) );
                    $pos += strlen( "." ) - 1;
                    $rootDir = substr( $rootDir, 0, $pos );
                    if ( "/" !== substr( $rootDir, -1 ) ) $rootDir = "/" . $rootDir . "/";
                }
            }
            $dirFS = $_SERVER[ "DOCUMENT_ROOT" ] . $rootDir;
            while ( ( false !== strpos( $dirFS, "//" ) ) ) {
                $dirFS = str_replace( "//", "/", $dirFS );
            }
        }
        return $dirFS;
    }
    /**
     * oscsec_fix_server_vars()
     * 
     * @return
     */
    function oscsec_fix_server_vars() {
        if ( empty( $_SERVER[ "REQUEST_URI" ] ) || ( php_sapi_name() != "cgi-fcgi" && false !== ( bool )preg_match( "/^Microsoft-IIS\//", $_SERVER[ "SERVER_SOFTWARE" ] ) ) ) {
            if ( isset( $_SERVER[ "HTTP_X_ORIGINAL_URL" ] ) ) {
                $_SERVER[ "REQUEST_URI" ] = $_SERVER[ "HTTP_X_ORIGINAL_URL" ];
            } else
                if ( isset( $_SERVER[ "HTTP_X_REWRITE_URL" ] ) ) {
                    $_SERVER[ "REQUEST_URI" ] = $_SERVER[ "HTTP_X_REWRITE_URL" ];
                } else {
                    if ( !isset( $_SERVER[ "PATH_INFO" ] ) && isset( $_SERVER[ "ORIG_PATH_INFO" ] ) ) $_SERVER[ "PATH_INFO" ] = $_SERVER[ "ORIG_PATH_INFO" ];
                    if ( isset( $_SERVER[ "PATH_INFO" ] ) ) {
                        if ( $_SERVER[ "PATH_INFO" ] == $_SERVER[ "SCRIPT_NAME" ] ) {
                            $_SERVER[ "REQUEST_URI" ] = $_SERVER[ "PATH_INFO" ];
                        } else {
                            $_SERVER[ "REQUEST_URI" ] = $_SERVER[ "SCRIPT_NAME" ] . $_SERVER[ "PATH_INFO" ];
                        }
                    }
                    if ( !empty( $_SERVER[ "QUERY_STRING" ] ) ) {
                        $_SERVER[ "REQUEST_URI" ] .= "?" . $_SERVER[ "QUERY_STRING" ];
                    }
                }
        }
        if ( isset( $_SERVER[ "SCRIPT_FILENAME" ] ) && ( strpos( $_SERVER[ "SCRIPT_FILENAME" ], "php.cgi" ) == strlen( $_SERVER[ "SCRIPT_FILENAME" ] ) - 7 ) ) {
            $_SERVER[ "SCRIPT_FILENAME" ] = $_SERVER[ "PATH_TRANSLATED" ];
        }
    }
    /**
     * check_ip()
     * 
     * @param mixed $ip
     * @return
     */
    function check_ip( $ip ) {
      if ( function_exists( 'filter_var' )
          && defined( 'FILTER_VALIDATE_IP' )
          && defined( 'FILTER_FLAG_IPV4' )
          && defined( 'FILTER_FLAG_IPV6' ) ) {
          if ( filter_var( $ip, FILTER_VALIDATE_IP,
                                FILTER_FLAG_IPV4 ||
                                FILTER_FLAG_IPV6 ) === false ) {
                                $this->senda403Header();
          } else return $ip;
      }
      if ( preg_match( '/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip ) ) {
        $parts = explode( '.', $ip );
  
        foreach ( $parts as $ip_parts ) {
          if ( ( intval( $ip_parts ) > 255 ) || ( intval( $ip_parts ) < 0 ) ) {
             $this->senda403Header(); // number is not within 0-255
          }
        }
        return $ip;
      } else $this->senda403Header();
    }
   
    /**
     * getRealIP()
     * 
     * @return
     */
    function getRealIP() {
      global $_SERVER;
      $ip_addresses = array();
      if ( isset( $_SERVER ) ) {
        # other 'cloud' cluster configurations
        if ( ( getenv( "HTTPS" ) == "on" )
            && isset( $_SERVER[ "HTTP_X_CLUSTER_CLIENT_IP" ] )
            && !empty( $_SERVER[ "HTTP_X_CLUSTER_CLIENT_IP" ] ) ) {
                  # even though the users ip address will be the HTTP_X_FORWARDED_FOR IP,
                  # this header can be easily spoofed so cannot be relied upon for a true
                  # ip address. So at the very least, make sure the REMOTE_ADDR ip address
                  # cannot be banned, but still allow malicious requests to be blocked.
                  $this->_banipaddress = 0;
                  $this->_useIPTRAP = 0;
        }
        # Get the REMOTE_ADDR
        $ip_addresses[] = $_SERVER[ "REMOTE_ADDR" ];        
          
        # Set the first IP address in the array as the users IP
        foreach ( $ip_addresses as $ip ) {
          if ( !empty( $ip ) && $this->check_ip( $ip ) ) {
            $ip_address = $ip;
            break;
          }
        }
        return $ip_address;
      }
    }

    /**
     * versionCheck()
     * 
     * @return
     */
    function versionCheck() {
        if ( !defined( 'PROJECT_VERSION' ) ) return true;
        $restrictedlist = array( "osCMax", "2.2" );
        foreach ( $restrictedlist as $osversion ) {
            if ( false !== strpos( PROJECT_VERSION, $osversion ) ) {
                return true;
            }
        }
        return false;
    }
    /**
     * strCharsfrmStr()
     * 
     * @param mixed $string
     * @param mixed $strip
     * @param mixed $replace
     * @return
     */
    function strCharsfrmStr( $string, $strip, $replace ) {
        $x = ( false !== strpos( $string, $strip ) ) ? true : false;
        while ( false !== $x ) {
            $string = str_replace( $strip, $replace, $string );
            $x = ( false !== strpos( $string, $strip ) ) ? true : false;
        }
        return $string;
    }
    /**
     * Bad Spider Block
     */
    function badArachnid() {
        if ( false === $this->oscSecBypass() ) return;
        if ( isset( $_SERVER[ "HTTP_USER_AGENT" ] ) ) {
          $badagentlist = array( "Baidu", "WebLeacher", "autoemailspider", "MSProxy", "Yeti", "Twiceler", "blackhat", "Mail.Ru", "fuck" );
          $lcUserAgent = strtolower( $_SERVER[ "HTTP_USER_AGENT" ] );
          foreach ( $badagentlist as $badagent ) {
              $badagent = strtolower( $badagent );
              if ( false !== strpos( $lcUserAgent, $badagent ) ) {
                  $header = array( "HTTP/1.1 404 Not Found", "HTTP/1.1 404 Not Found", "Content-Length: 0" );
                  foreach ( $header as $sent ) {
                      header( $sent );
                  }
                  die();
              }
          }
        }
    }
  } // end of class
  
  /**
   * osCSec_selfchk()
   * 
   * @return
   */
  function osCSec_selfchk() {
      $oscsecfilepath = str_replace( DIRECTORY_SEPARATOR, urldecode( "%2F" ), __file__ );
      $oscsecfilepath = explode( "/", $oscsecfilepath );
      if ( is_array( $oscsecfilepath ) ) {
          $fileself = $oscsecfilepath[count( $oscsecfilepath ) - 1];
          if ( $fileself[0] == "/" ) {
              return $fileself;
          } else {
              return "/" . $fileself;
          }
      }
  }
  /**
   * senda404Header()
   * 
   * @return
   */
  function senda404Header() {
      $header = array( "HTTP/1.1 404 Not Found", "HTTP/1.1 404 Not Found", "Content-Length: 0" );
      foreach ( $header as $sent ) {
          header( $sent );
      }
      die();
  }
    
?>