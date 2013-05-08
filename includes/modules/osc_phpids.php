<?php
/*
  $Id: osc_phpids.php
  PHP Intrusion Detection System for osCommerce
  PHPIDS for osCommerce 1.6
  Date: June 13, 2010
  Created by celextel - www.celextel.com
  Module to include PHPIDS into osCommerce to log and prevent intrusions
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
 *
 * @uses http://php-ids.org/
 * @package PHPIDS
 * Requirements: PHP5, SimpleXML
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

// ** Begin Main Configuration **

    $oscBasePath = DIR_FS_CATALOG . DIR_WS_INCLUDES;
    $basePath = $oscBasePath . 'phpids/lib/';

    $mail_from_name = STORE_NAME;
    $mail_from_email = STORE_OWNER_EMAIL_ADDRESS;
    $mail_from = 'From: ' . $mail_from_name .'<' . $mail_from_email . '>';
    $mail_recipient = array(STORE_OWNER_EMAIL_ADDRESS, SEND_EXTRA_ORDER_EMAILS_TO);
    $mail_subject = 'PHPIDS detects an intrusion attempt at ' . $_SERVER['HTTP_HOST'];
/*
Default is 8. Change this value to a lesser or higher value as per your requirement to receive instant emails about the impacts.
*/
    $mail_log_impact = PHPIDS_MAIL_LOG_IMPACT;
/*
* Send rate
* If safemode is enabled, this property defines how often reports will be
* sent out. Default value is 15, which means that a mail will be sent on
* condition that the last email has not been sent earlier than 15 seconds ago.
*/
    $mail_interval = 15;

    $db_server = DB_SERVER;
    $db_name = DB_DATABASE;
    $db_user = DB_SERVER_USERNAME;
    $db_pwd = DB_SERVER_PASSWORD;
    $db_table = 'phpids_intrusions';
/*
Default is 4. Change this value to a lesser or higher value as per your requirement to log impacts in db.
*/
    $db_log_impact = PHPIDS_DB_LOG_IMPACT;

/*
Default is 70. Change this value to a lesser or higher value as per your requirement to ban an IP automatically.
*/
    $ip_ban_impact = PHPIDS_IP_BAN_IMPACT;

/*
Default is false. Change this value to 'true' only when you want to see the intrusion impact result on screen during test.
*/
    $show_result = PHPIDS_SHOW_RESULT;

/*
Default is false. Change this value to 'true' only if you want to log the intrusion impact result to the file.
*/
    $file_log = 'false';

/*
Set exception variables
*/
    $useExeptions = explode(', ', PHPIDS_EXCLUSIONS);
    if (!in_array(basename($_SERVER['PHP_SELF']), $useExeptions))

// ** End Main Configuration **

// set the include path properly for PHPIDS
set_include_path(
    get_include_path()
    . PATH_SEPARATOR
    . $basePath
);

require_once 'IDS/Init.php';

try {

/*
* It's pretty easy to get the PHPIDS running
* Define what to scan
*
* Please keep in mind what array_merge does and how this might interfer
* with your variables_order settings
*/
    $request = array(
       'REQUEST' => $_REQUEST,
        'GET' => $_GET,
        'POST' => $_POST,
        'COOKIE' => $_COOKIE
    );

    $init = IDS_Init::init($basePath . 'IDS/Config/Config.ini.php');

/*
* You can also reset the whole configuration
* array or merge in own data
*
* This usage doesn't overwrite already existing values
* $config->setConfig(array('General' => array('filter_type' => 'xml')));
*
* This does (see 2nd parameter)
* $config->setConfig(array('General' => array('filter_type' => 'xml')), true);
*
* or you can access the config directly like here:
*/

// General configuration
    $init->config['General']['base_path'] = $basePath . 'IDS/';
    $init->config['General']['use_base_path'] = true;
    $init->config['Caching']['caching'] = 'none';

// E-Mail configuration
    $init->config['Logging']['header'] = $mail_from;
    $init->config['Logging']['recipients'] = $mail_recipient;
    $init->config['Logging']['subject'] = $mail_subject;
    $init->config['Logging']['allowed_rate'] = $mail_interval;

// Database configuration
    $init->config['Logging']['wrapper'] = 'mysql:host='.$db_server.';port=3306;dbname='.trim($db_name, '`');
    $init->config['Logging']['user'] = $db_user;
    $init->config['Logging']['password'] = $db_pwd;
    $init->config['Logging']['table'] = $db_table;

// Exception list
    foreach ($useExeptions as $currentException) {
    $init->config['General']['exceptions'][] = $currentException;
		$init->config['General']['exceptions'][] = '$currentException';
	}

// Initiate the PHPIDS and fetch the results
    $ids = new IDS_Monitor($request, $init);
    $result = $ids->run();
    $impact = $result->getImpact();

/*
* That's it - now you can analyze the results:
*
* In the result object you will find any suspicious
* fields of the passed array enriched with additional info
*
* Note: it is moreover possible to dump this information by
* simply echoing the result object, since IDS_Report implemented
* a __toString method.
*/
    if (!$result->isEmpty()) {
    require_once 'IDS/Log/Composite.php';
    $compositeLog = new IDS_Log_Composite();

/*
Log to File when set value for $file_log is true - default is false,
*/
    if ($file_log == 'true') {
    require_once 'IDS/Log/File.php';
    $compositeLog->addLogger(IDS_Log_File::getInstance($init));
	}

/*
Receive Email of the impact when the impact score is greater or equal to the set value for $mail_log_impact,
*/
    if ($impact >= $mail_log_impact) {
    require_once 'IDS/Log/Email.php';
    $compositeLog->addLogger(IDS_Log_Email::getInstance($init));
        }

/*
Log to DB when the impact score is greater or equal to the set value for $db_log_impact, Creates the DB if it does not exist.
*/
    if ($impact >= $db_log_impact) {
    require_once 'IDS/Log/Database.php';
    $compositeLog->addLogger(IDS_Log_Database::getInstance($init));
        }

        $compositeLog->execute($result);
    }
}
    catch (Exception $e) {
             die('Exception: ' . $e->getMessage());
        }

/*
Show Result on Screen if the set value for $show_result is true.
*/
    if ($show_result == 'true') {
        echo $result;
	}

/*
Ban an IP automatically if
banned_ip.php file exists in the modules directory,
impact score is greater or equal to the set value for $ip_ban_impact and
set value for $show_result is false.
*/
 //   if ((file_exists(DIR_FS_CATALOG . DIR_WS_MODULES . 'banned_ip.php')) && ($impact >= $ip_ban_impact) && ($show_result == 'false')) {
//    header ("Location: " . HTTP_SERVER . "/banned.php");
 //   exit;
 //       }
?>