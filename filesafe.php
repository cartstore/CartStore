<?php
  /**
  *
  * KISS FileSafe
  *
  * Informs by email of any newly introduced or modified files to the osCommerce system.
  * This file can be run directly or accessed via cron job ( wget for HTTP access ).
  * Unless authorisation querystring is present and correct this file can only be run once until the time frequency has expired.
  * Includes the Kiss_FileSafe class and calls the run() method to run the script.
  *
  * @see Kiss_FileSafe::run()
  * @package KISS_FileSafe
  * @category AddOns
  * @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
  * @link http://www.fwrmedia.co.uk
  * @copyright Copyright 2008-2009 FWR Media
  * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk
  * @lastdev $Author:: Rob                                              $:  Author of last commit
  * @lastmod $Date:: 2010-09-12 11:25:11 +0100 (Sun, 12 Sep 2010)       $:  Date of last commit
  * @version $Rev:: 11                                                  $:  Revision of last commit
  * @Id $Id:: filesafe.php 11 2010-09-12 10:25:11Z Rob                  $:  Full Details
  */

  /**
  * Include the Kiss_FileSafe class then call the run() method to run the script
  * @see Kiss_FileSafe::run()
  */
  require('includes/configure.php');
  require('includes/database_tables.php');
  require('includes/functions/database.php');
  tep_db_connect() or die('Unable to connect to database server!');
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
  }

  include_once 'includes/modules/kiss_filesafe/classes/kiss_filesafe.php';
  Kiss_FileSafe::i()->run();
