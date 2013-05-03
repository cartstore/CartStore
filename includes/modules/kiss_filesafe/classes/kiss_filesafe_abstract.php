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
  * @copyright Copyright 2008-2009 FWR Media
  * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk
  * @lastdev $Author:: Rob                                              $:  Author of last commit
  * @lastmod $Date:: 2010-09-12 17:07:45 +0100 (Sun, 12 Sep 2010)       $:  Date of last commit
  * @version $Rev:: 12                                                  $:  Revision of last commit
  * @Id $Id:: kiss_filesafe_abstract.php 12 2010-09-12 16:07:45Z Rob    $:  Full Details
  */

  /**
  * abstract class extended as standard by Kiss_FileSafe
  *
  * This class constitutes the bulk of the KISS FileSafe model. Extends ArrayObject to make use of ArrayObject::storage
  *
  * @link http://www.fwrmedia.co.uk
  * @copyright Copyright 2008-2009 FWR Media
  * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk
  * @lastdev $Author:: Rob                                              $:  Author of last commit
  * @lastmod $Date:: 2010-09-12 17:07:45 +0100 (Sun, 12 Sep 2010)       $:  Date of last commit
  * @version $Rev:: 12                                                  $:  Revision of last commit
  * @Id $Id:: kiss_filesafe_abstract.php 12 2010-09-12 16:07:45Z Rob    $:  Full Details
  * @see Kiss_FileSafe
  */
  abstract class Kiss_FileSafe_Abstract extends ArrayObject {
    /**
    * Path to the osCommerce root directory
    *
    * @var string
    * @access protected
    */
    protected $root_path;
    /**
    * Name of this module
    *
    * @var string
    * @access protected
    */
    protected $script_name = 'KISS FileSafe';
    /**
    * Name of the file in which the serialized array of file data will be stored
    *
    * @var string
    * @access protected
    */
    protected $serialized_data_file = 'files_data.txt';
    /**
    * Name of the file where the time of the last run is stored
    *
    * @var string
    * @access protected
    */
    protected $runtime_file = 'runtime.txt';
    /**
    * Path to the serialized data file
    *
    * @var string
    * @access protected
    */
    protected $serialized_data_path;
    /**
    * full path to $runtime_file
    *
    * @var string
    * @access protected
    */
    protected $runtime_file_path;
    /**
    * array of file data as captured during the iteration of files
    *
    * @var array
    * @access protected
    */
    protected $files_data = array();
    /**
    * Whether the script is being run for the first time ( no legacy array of file data )
    *
    * @var array
    * @access protected
    */
    protected $first_run = false;
    /**
    * Output debug information on destruct
    * setting this to true forces debugging to be active
    * otherwise it can only be made active with authentication + ?debug in the querystring
    *
    * @var bool
    * @access protected
    */
    protected $debug = true;
    /**
    * Debug data container
    *
    * @var array
    * @access protected
    */
    protected $debug_data = array();
    /**
    * Set paths in the constructor
    * @access public
    */
    public function __construct( array $params = array() ) {
      date_default_timezone_set( $params['default_timezone'] );
      parent::__construct( $params, parent::ARRAY_AS_PROPS );
      // PHP 5.2.5 adds an additional / ( 5.2.0, 5.2.8 and 5.3.1 are fine
      $this->root_path = rtrim( str_replace( DIRECTORY_SEPARATOR, '/', realpath( dirname( __FILE__ ) . '/../../../../' ) ), '/' ) . '/';
      $this->module_path = str_replace( DIRECTORY_SEPARATOR, '/', realpath( dirname( __FILE__ ) . '/../' ) );
      $this->runtime_file_path = $this->module_path . '/data/' . $this->runtime_file;
      $this->serialized_data_path = $this->module_path . '/data/' . $this->serialized_data_file;
      $this->moduleAccess();
    }
    /**
    * Destructor outputs debug data
    *
    * Outpusts debug data if /$debug is set to true or if access is authenticated and debug is a key of _GET.
    *
    * @see Kiss_FileSafe_Abstract::$debug
    * @access public
    * @return void
    */
    public function __destruct() {
      if ( false !== $this->debug ) {
        echo $this->debugData();
      }
    }
    /**
    * Magic method for SPL ArrayObject
    *
    * @param string $target - property in ArrayObject::storage to retrieve
    * @return mixed / bool - returns a property if existant or bool false if not
    */
    public function __get( $target ) {
      if ( $this->offsetExists( $target ) ) {
        return $this->offsetGet( $target );
      }
      return false;
    }
    /**
    * Magic method for SPL ArrayObject
    * adds a key => value to ArrayObject::storage as long as it does not already exist
    *
    * @param string $key - property key
    * @param mixed $value - property value
    * @return void
    */
    public function __set( $key, $value ) {
      if ( !$this->offsetExists( $key ) ) {
        return $this->offsetSet( $key, $value );
      }
    }
    /**
    * abstract run() method given body in the child class
    * @access public
    * @return void
    */
    abstract public function run();
    /**
    * Attempt to set permissions of the module directory to writeable
    * @uses is_writable()
    * @uses clearstatcache()
    * @access protected
    * @return void
    */
    protected function moduleAccess() {
      if ( is_writable( $this->module_path . '/data' ) ) {
        $this->debug_data['module_dir_writeable'] = 'true';
        return;
      }
      @chmod( $this->module_path . '/data', 0755 );
      clearstatcache();
      if ( !is_writable( $this->module_path . '/data' ) ) {
        @chmod( $this->module_path . '/data', 0777 );
        clearstatcache();
      }
      if ( !is_writable( $this->module_path . '/data' ) ) {
        $this->debug_data['module_dir_writeable'] = 'false';
        trigger_error( __CLASS__ . '::' . __FUNCTION__ . ' cannot write to the module directory ' . str_replace( $this->root_path, '[PATH]', $this->module_path . '/data' ) . ', this will have to be done manually', E_USER_WARNING );
      }
    }
    /**
    * Iterate all directories in the osCommerce root if it is time to do so ( $run_frequency )
    * First run will set the array of accepted files, later runs will compare existing files
    * and send a report outlining new files added or files that have been modified.
    *
    * @see Kiss_FileSafe_Abstract::shouldRun() .. the script will abort if $run_frequency is not yet reached
    * @see Kiss_FileSafe_Abstract::preRun()
    * @uses SPL RecursiveDirectoryIterator and RecursiveIteratorIterator to iterate the directories
    * @uses SplFileInfo for file data
    * @uses file_put_contents() to store the data
    * @see Kiss_FileSafe_Abstract::serverUnload()
    * @see Kiss_FileSafe_Abstract::sendReports()
    * @access protected
    * @return bool false / void - returns false if Kiss_FileSafe_Abstract::shouldRun() returns false
    */
    protected function iterate() {
      if ( false === $this->shouldRun() ) {
        $this->debug_data['should_run'] = 'false';
        return false;
      } else $this->debug_data['should_run'] = 'true';
      $this->preRun();
      $start = microtime( true );
      $iteration_time = microtime( true );
      $filecount = 0;
      $script_slept = 0;
      $reported_files = array( 'unknown' => array(), 'modified' => array() );
      $it = new RecursiveDirectoryIterator( $this->root_path );
      foreach ( new RecursiveIteratorIterator( $it ) as $file ) {
        $file = str_replace( DIRECTORY_SEPARATOR, '/', $file );
        $md5_hash = md5( $file );
        /**
        * PHP 5.2.13 seems to think $this->ignore_directories is empty unless passed to another variable
        * Some issue with ArrayObject it seems
        */
        $ignore_directories = isset( $this->ignore_directories ) ? $this->ignore_directories : array();
        if ( is_array( $ignore_directories ) && !empty( $ignore_directories ) ) {
          foreach ( $ignore_directories as $target ) {
            if ( false !== strpos( $file, $this->root_path . trim( $target, '/' ) . '/' ) ) continue 2;
          }
        }
        $ignore_files = isset( $this->ignore_files ) ? $this->ignore_files : array();
        if ( is_array( $ignore_files ) && !empty( $ignore_files ) ) {
          foreach ( $ignore_files as $target ) {
            if ( false !== ( substr( $file, ( strlen( $file )-strlen( $target ) ), strlen( $file ) ) == $target ) ) continue 2;
          }
        }
        // Stop server overloading
        $this->serverUnload( $iteration_time, $script_slept );
        $filecount++;
        $fileInfo = new SplFileInfo( $file );
        // If this is not the first time ran and this file has never been encountered
        if ( !$this->first_run && !array_key_exists( $md5_hash, $this->files_data ) ) {
          $reported_files['unknown'][] = $fileInfo;
        }
        if ( false !== $this->first_run ) {
          $this->files_data[$md5_hash] = array( 'last_modified' => $fileInfo->getMTime() );
        } elseif( array_key_exists( $md5_hash, $this->files_data ) && ( $fileInfo->getMTime() !== $this->files_data[$md5_hash]['last_modified'] ) ) {
          $reported_files['modified'][] = $fileInfo;
        }
      } // end foreach
      if ( false !== $this->first_run ) {
        // Save the file data cache in serialized format
        file_put_contents( $this->serialized_data_path, serialize( $this->files_data ), LOCK_EX );
      }
      $this->debug_data['script_ran_for'] = number_format( microtime( true ) - $start, 2 );
      $this->debug_data['script_slept'] = $script_slept;
      $this->sendReports( $filecount, $reported_files );
      file_put_contents( $this->runtime_file_path, time(), LOCK_EX );
    }
    /**
    * Initiates the runtime report and identified reports where appropriate
    *
    * @param int $filecount - number of files checked
    * @param string $end - Total run time for the script
    * @param int $script_slept - Total time the script slept
    * @param array $reported_files - array of unknown and / or modified files, each being an instance of SplFileInfo
    * @access protected
    * @return void
    */
    protected function sendReports( $filecount, array $reported_files = array() ) {
      if ( false !== $this->send_runtime_report ) {
        $this->debug_data['send_runtime_report'] = 'true';
        $this->sendRuntimeReport( $filecount, $reported_files );
      } else $this->debug_data['send_runtime_report'] = 'false';

      if ( !empty( $reported_files['unknown'] ) || !empty( $reported_files['modified'] ) ) {
        $this->debug_data['send_identified_report'] = 'true';
        $this->sendIdentifiedReport( $reported_files );
      } else $this->debug_data['send_identified_report'] = 'false';
    }
    /**
    * Populate the runtime report which is then emailed
    *
    * @param int $filecount - number of files checked
    * @param string $end - Total run time for the script
    * @param mixed $script_slept - Total time the script slept
    * @param mixed $reported_files - array of unknown and / or modified files, each being an instance of SplFileInfo
    * @access protected
    * @return void
    */
    protected function sendRuntimeReport( $filecount, array $reported_files = array() ) {
      if ( false === $this->send_runtime_report ) {
        return false;
      }
      $email_body = '';
      $email_body .= 'File count: ' . $filecount . PHP_EOL .
      $this->script_name . ' ran for: ' . $this->debug_data['script_ran_for'] . ' seconds' . PHP_EOL .
      $this->script_name . ' paused ' . ( $this->debug_data['script_slept'] / $this->sleep_time ) . ' time(s) to unload server for a total of ' . $this->debug_data['script_slept'] . ' seconds' . PHP_EOL .
      'Actual parse time: ' . ( $this->debug_data['script_ran_for'] - $this->debug_data['script_slept'] ) . ' seconds' . PHP_EOL .
      $this->script_name . ' Identified Unknown Files:' . PHP_EOL . ( !empty( $reported_files['unknown'] ) ? implode( PHP_EOL, $reported_files['unknown'] ) : 'None' ) . PHP_EOL .
      $this->script_name . ' Identified Modified Files:' . PHP_EOL . ( !empty( $reported_files['modified'] ) ? implode( PHP_EOL, $reported_files['modified'] ) : 'None' ) . PHP_EOL;
      $this->mail( $this->runtime_report_subject, $email_body );
    }
    /**
    * Iterates $reported_files recording all unknown and modified files found
    * populating the email body
    *
    * @param array $reported_files - array of unknown and / or modified files, each being an instance of SplFileInfo
    * @see Kiss_FileSafe_Abstract::getFileData()
    * @see Kiss_FileSafe_Abstract::mail()
    * @access protected
    * @return void
    */
    protected function sendIdentifiedReport( array $reported_files = array() ) {
      $unknown = 'Unknown Files Identified:' . PHP_EOL;
      $modified = 'Modified Files Identified:' . PHP_EOL;
      print_r($reported_files);
      if ( !empty( $reported_files['unknown'] ) ) {
        foreach ( $reported_files['unknown'] as $index => $fileInfo ) {
          $this->getFileData( $unknown, $fileInfo );
        }
      } else $unknown .= 'None.' . PHP_EOL;
      if ( !empty( $reported_files['modified'] ) ) {
        foreach ( $reported_files['modified'] as $index => $fileInfo ) {
          $this->getFileData( $modified, $fileInfo );
        }
      } else $modified .= 'None.' . PHP_EOL;
      $identified_data = $unknown . PHP_EOL . PHP_EOL . $modified . PHP_EOL;
      $this->mail( $this->identified_report_subject, $identified_data );
    }
    /**
    * Extract the required information from $fileInfo
    * populates the $text_container
    *
    * @param string $text_container - email body ( passed by reference )
    * @param object $fileInfo SplFileInfo
    * @access protected
    * @return void
    */
    protected function getFileData( &$text_container, SplFileInfo $fileInfo ) {
      $text_container .=
      'File Name: '     . $fileInfo->getFilename() . PHP_EOL .
      'File Path: '     . $fileInfo->getPath() . PHP_EOL .
      'Last Modified: ' . $this->formatDate( $fileInfo->getMTime() ) . PHP_EOL .
      'Inode Change: '  . $this->formatDate( $fileInfo->getCTime() ) . PHP_EOL .
      'Group: '         . $fileInfo->getGroup() . PHP_EOL .
      'Permissions: '   . substr( sprintf( '%o', $fileInfo->getPerms() ), -4 ) . PHP_EOL .
      'Executable: '    . ( $fileInfo->isExecutable() ? 'yes' : 'no' ) . PHP_EOL .
      'File Size: '     . number_format( $fileInfo->getSize() / 1024, 2 ) . 'KB' . PHP_EOL .
      'File Type: '     . $fileInfo->getType() . PHP_EOL . PHP_EOL;
    }
    /**
    * Wrapper for date format
    *
    * @uses date() to return a formatted UNIX timestamp
    * @param mixed $time - UNIX timestamp
    * @access protected
    * @return string - formtted date
    */
    protected function formatDate( $time ) {
      return date( "d-m-Y H:i:s", $time );
    }
    /**
    * Send the emails
    *
    * @uses mail() to send emails
    * @param string $subject - email sunject
    * @param string $email_body - email body text
    * @access protected
    * @return void
    */
    protected function mail( $subject, $email_body ) {
      @mail( $this->admin_email, $subject, $email_body, $this->email_headers );
    }
    /**
    * Pause the script to prevent server load
    *
    * @uses sleep() to pause the script
    * @uses set_time_limit() to reset the script time limit
    * @uses ini_get() checks for safe mode where set_time_limit() will not work
    * @param string $iteration_time - UNIX timestamp of current time iterating between sleeps
    * @param int $script_slept - amount of time the script has currently splept in between iterations
    * @access protected
    * @return void
    */
    protected function serverUnload( &$iteration_time, &$script_slept ) {
      // Stop server overloading
      if ( ( microtime( true ) - $iteration_time ) >= $this->allowed_time_before_sleep ) {
        sleep( $this->sleep_time );
        $script_slept += $this->sleep_time;
        ini_get( 'safe_mode' ) ? null : set_time_limit( $this->script_time_limit );
        $iteration_time = microtime( true );
      }
    }
    /**
    * Identify if this a first run by checking for a stored array of file data
    * Resets the system if reset is a key of the querystring and access is authorised
    *
    * @uses file_get_contents() to retrieve the data
    * @uses unserialize()
    * @see Kiss_FileSafe_Abstract::$serialized_data_path
    * @see Kiss_FileSafe_Abstract::reset()
    * @access protected
    * @return void
    */
    protected function preRun() {
      if ( array_key_exists( 'reset', $_GET ) ) {
        $this->debug_data['set_to_reset_data'] = 'true';
        $this->reset();
      } else $this->debug_data['set_to_reset_data'] = 'false';
      $this->email_headers = 'From: ' . $this->from_email . PHP_EOL . 'X-Mailer: php';
      $this->first_run = false;
      if ( is_readable( $this->serialized_data_path ) ) {
        $this->files_data = unserialize( file_get_contents( $this->serialized_data_path ) );
      } else $this->first_run = true;
    }
    /**
    * The script should only run if it is time to do so
    * this is set by $run_frequency
    * @see Kiss_FileSafe_Abstract::$runtime_file
    * @access protected
    * @return bool
    *
    */
    protected function shouldRun() {
      if ( array_key_exists( $this->authentication_key, $_GET ) && ( $_GET[$this->authentication_key] == $this->authentication_value )
                                                                && ( strlen( $this->authentication_value ) > 5 ) ) {
        if ( array_key_exists( 'debug', $_GET ) ) {
          $this->debug = true;
        }
        $this->debug_data['authenticated'] = 'true';
        return true;
      } elseif( array_key_exists( $this->authentication_key, $_GET ) ) {
        $this->debug_data['authenticated'] = 'false';
      }
      if ( is_readable( $this->runtime_file_path ) ) {
        $this->debug_data['readable_runtime_file'] = 'true';
        $last_run = file_get_contents( $this->runtime_file_path );
        if ( time() >= ( $last_run + $this->run_frequency ) ) {
          return true;
        } else {
          return false;
        }
      } else $this->debug_data['readable_runtime_file'] = 'false';
      return true;
    }
    /**
    * Resets the KISS FileSafe system
    *
    * Deletes both the file cache and the file frequency file
    * This means that ALL files currently on the system will be accepted as safe
    * Will only run if reset is a key of the querystring as well as valid authorisation password
    *
    * @uses unlink() to delete the data files
    * @access protected
    * @return void
    */
    protected function reset() {
      @unlink( $this->serialized_data_path );
      @unlink( $this->runtime_file_path );
    }
    /**
    * Build debug data for output in the destructor
    *
    * @uses get_object_vars() to retrieve the class properties
    * @uses ArrayObject::getIterator()->getArrayCopy() to retrieve stored properties
    * @uses print_r() to format the output
    * @access protected
    * @return string - array formatted to string by print_r
    */
    protected function debugData() {
      $this->debug_data['files_in_array'] = count( $this->files_data );
      $data = get_object_vars( $this );
      // files_data array is too large and renders debug info unreadable
      unset( $data['files_data'] );
      // Retrieve data from arrayObject::storage
      $array_copy = $this->getIterator()->getArrayCopy();
      // merge the two arrays using UNION operator
      $data = (array)$data + (array)$array_copy;
      return '<pre>' . print_r( $data, true ) . '</pre>';
    }

  } // end class