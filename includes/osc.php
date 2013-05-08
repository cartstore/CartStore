<?php

  /**
   * @package osC_Sec Security Settings for osC_Sec.php
   * @author Te Taipo <rohepotae@gmail.com>
   * @copyright Copyleft (c) Hokioi-IT
   * @license http://opensource.org/licenses/gpl-license.php GNU Public License
   * @see readme.htm
   * @link http://addons.oscommerce.com/info/7834/
   **/
  if ( false !== strpos( $_SERVER['SCRIPT_NAME'], osc_selfchk() ) ) send404Header();

  /**
  * [[ SETTINGS ]] - stuff to edit
  * See: readme.htm for detailed
  * instructions.
  **/
  $timestampOffset = 12;                        # Set the time offset from GMT, example: a setting of -10 is GMT-10 which is Tahiti
  $httphost = str_replace(HTTP_COOKIE_DOMAIN,"",HTTP_SERVER);   		# enter your site host without http:// using this format www.yourwebsite.com
  $nonGETPOSTReqs = 0;				# 1 = Prevent security bylass attacks via forged requests, 0 = let it as it is
  $chkPostLocation = 0;				# 1 = Check to see if cookies and referer are set before accepting post vars, 0; don't (especially if using Paypal)
  $GETcleanup = 1;                              # 1 = Clean up $_GET variables, 0 = don't cleanup. Set this to 0 if this causes errors (for example with another addon)
  $testExpiredCookie = 1;			# 1 = Checks to see if the browser understands what to do with an expired cookie, 0 = don't check
  $arbitrarysession_block = 0;			# 1 = Prevents arbitrary session injections, 0 = leave it as it is

  /**
  * This section of settings is to allow osC_Sec.php
  * to ban an IP address if it breaks the rules
  *
  * Choose either $banipaddress to add to htaccess
  * or $useIPTRAP if you are using the IP Trap addon
   **/

  $banipaddress = 0;						# 1 = adds ip to htaccess for permanent ban, 0 = calls a page die if injection detected
  $htaccessfile = $dirFScatalog . ".htaccess";			# remember to change the write access of .htaccess to a writable setting

  $useIPTRAP = 0;						# 1 = add IPs to the IP Trap contribution, 0 = leave it off
  $ipTrappedURL = $dirFScatalog . "banned/IP_Trapped.txt"; 	# If you are using IP Trap make sure this is pointing to the IP_Trapped.txt file

  /**
  * Email settings: Don't use if your
  * Web Service Provider limits how
  * many emails per hour
   **/

  $emailenabled = 0;				# 1 = send yourself an email notification of injection attack, 0 = don't
  $youremail = STORE_OWNER_EMAIL;	# set your email address here so that the server can send you a notification of any action taken and why
  $fromemail = EMAIL_FROM;	# set up an email like securityscript@yourdomain.com where the attack notifications will come from

  $diagenabled = 0;				# 1 = automatically send an email to the developer with the ban IP address and the reason for the ban to help improve osC_Sec, 0 = don't
  $diagemail = "adoovo@gmail.com";	# this is the email of the developer of osC_Sec.php (see readme.htm)

  /*
  * END OF SETTINGS
  *****************************/

    $osC_Sec = new osC_Sec();
  $osC_Sec->Sentry( $timestampOffset,$nonGETPOSTReqs,$spiderBlock,$banipaddress,$useIPTRAP,
                    $ipTrapBlocked,$emailenabled,$youremail,$fromemail );

  $osc_check = false;

  /**
   * send404Header()
   *
   * @return
   */
  function send404Header() {
      $header = array( "HTTP/1.1 404 Not Found", "HTTP/1.1 404 Not Found", "Content-Length: 0" );
      foreach ( $header as $sent ) {
          header( $sent );
      }
      die();
  }
  /**
   * osc_selfchk()
   *
   * @return
   */
  function osc_selfchk() {
      $oscsecfilepath = str_replace( DIRECTORY_SEPARATOR, urldecode( "%2F" ), __file__ );
      $oscsecfilepath = explode( "/", $oscsecfilepath );
      if ( false !== is_array( $oscsecfilepath ) ) {
          $fileself = $oscsecfilepath[count( $oscsecfilepath ) - 1];
          if ( $fileself[0] == "/" ) {
              return $fileself;
          } else {
              return "/" . $fileself;
          }
      }
  }

?>