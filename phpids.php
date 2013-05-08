<?php set_include_path(
   get_include_path()
   . PATH_SEPARATOR
   . 'C:/wamp2/www/phpids/lib/'
);

if (!session_id()) {
   session_start();
}
require_once 'IDS/Init.php';
try {
   $request = array(
       'REQUEST' => $_REQUEST,
       'GET' => $_GET,
       'POST' => $_POST,
       'COOKIE' => $_COOKIE
   );
   $init = IDS_Init::init(dirname(__FILE__) .
'/phpids/lib/IDS/Config/Config.ini');
   $init->config['General']['base_path'] = dirname(__FILE__) .
'/phpids/lib/IDS/';
   $init->config['General']['use_base_path'] = true;
   $init->config['Caching']['caching'] = 'none';
   $ids = new IDS_Monitor($request, $init);
   $result = $ids->run();
   if (!$result->isEmpty()) {
	    echo $result;
       require_once 'IDS/Log/File.php';
       require_once 'IDS/Log/Composite.php';
       $compositeLog = new IDS_Log_Composite();
       $compositeLog->addLogger(IDS_Log_File::getInstance($init));
       $compositeLog->execute($result);
	    die('<h1>The activity has triggered our security systems</h1>');
   } else {
 }
} catch (Exception $e) {
   printf(
       'An error occured: %s',
       $e->getMessage()
   );
}
?>