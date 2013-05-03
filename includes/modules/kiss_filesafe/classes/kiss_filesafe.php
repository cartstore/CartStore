<?php
 /**
 *
 * KISS FileSafe
 *
 * Informs by email of any newly introduced or modified files to the osCommerce system
 *
 * @package KISS_FileSafe
 * @category AddOns
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link http://www.fwrmedia.co.uk
 * @copyright Copyright 2008-2010 FWR Media
 * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk
 * @lastdev $Author:: Rob                                              $:  Author of last commit
 * @lastmod $Date:: 2010-09-12 11:25:11 +0100 (Sun, 12 Sep 2010)       $:  Date of last commit
 * @version $Rev:: 11                                                  $:  Revision of last commit
 * @Id $Id:: kiss_filesafe.php 11 2010-09-12 10:25:11Z Rob             $:  Full Details
 */
  require_once 'kiss_filesafe_abstract.php';
  /**
  * Singleton instance of Kiss_FileSafe - extends Kiss_FileSafe_Abstract and implements the run() method
  *
  * @link http://www.fwrmedia.co.uk
  * @copyright Copyright 2008-2010 FWR Media
  * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk
  * @lastdev $Author:: Rob                                              $:  Author of last commit
  * @lastmod $Date:: 2010-09-12 11:25:11 +0100 (Sun, 12 Sep 2010)       $:  Date of last commit
  * @version $Rev:: 11                                                  $:  Revision of last commit
  * @Id $Id:: kiss_filesafe.php 11 2010-09-12 10:25:11Z Rob             $:  Full Details
  */
  class Kiss_FileSafe extends Kiss_FileSafe_Abstract {
    /**
    * Singleton instance of the class
    *
    * @var Kiss_FileSafe
    * @access private
    */
    private static $_singleton;
    /**
    * Do you want to receive a runtime report even if nothing is detected?
    *
    * @var bool
    * @access protected
    */
    protected $send_runtime_report = true;
    /**
    * Subject of the runtime report email
    *
    * @var string
    * @access protected
    */
    protected $runtime_report_subject = 'Kiss FileSafe Runtime Report';
    /**
    * Subject of the email where files have been identified
    *
    * @var string
    * @access protected
    */
    protected $identified_report_subject = 'URGENT! Kiss FileSafe Has Identified Files';
    /**
    * email header including the $from_email
    *
    * @var string
    * @access protected
    */
    protected $email_headers;
    /**
    * Constructor loads kiss_filesafe.ini and passes $params to parent constructor
    *
    * @see Kiss_FileSafe_Abstract
    */
    public function __construct() {
      // $params = parse_ini_file( realpath( dirname( __FILE__ ) . '/../kiss_filesafe.ini' ) );
      $params = $this->new_parse_ini( realpath( dirname( __FILE__ ) . '/../kiss_filesafe.ini' ) );
      $params['from_email'] = STORE_OWNER_EMAIL_ADDRESS;
      date_default_timezone_set( $params['default_timezone'] );
      parent::__construct( $params );
      if ( !self::$_singleton instanceof self ) {
        self::$_singleton = $this;
      }
    }

 private function new_parse_ini($f)
{

    // if cannot open file, return false
    if (!is_file($f))
        return false;

    $ini = file($f);

    // to hold the categories, and within them the entries
    $cats = array();

    foreach ($ini as $i) {
        $i = preg_replace("/\s*/","",$i);
        if (@preg_match('/\[(.+)\]/', $i, $matches)) {
            $last = $matches[1];
        } elseif (@preg_match('/(.+)=(.+)/', $i, $matches)) {
            $cats[$matches[1]] = $matches[2];
        }
    }

    return $cats;

}


    /**
    * Method ensures a singleton instance
    * @return Kiss_FileSafe
    */
    public static function i() {
      if ( !self::$_singleton instanceof self ) {
        self::$_singleton = new self;
      }
      return self::$_singleton;
    }
    /**
    * Set the email header then run the script
    * @see Kiss_FileSafe_Abstract
    * @access public
    * @return void
    */
    public function run() {
      if ( false == $this->kiss_filesafe_enabled ) {
        return false;
      }
      $this->iterate();
    }

  } // end class