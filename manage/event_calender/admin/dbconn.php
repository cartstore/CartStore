<?php

	$db = dbconn(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_USERNAME,DB_SERVER_PASSWORD);
	
	function dbconn($server,$database,$user,$pass){
		$db = mysql_connect($server,$user,$pass);
		$db_select = mysql_select_db($database,$db);
		return $db;
    }
	
?>