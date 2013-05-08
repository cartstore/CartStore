<?php

function tep_get_uploaded_file($filename) {

	if (isset($_FILES[$filename])) {

		$uploaded_file = array('name' => $_FILES[$filename]['name'],

		'type' => $_FILES[$filename]['type'],

		'size' => $_FILES[$filename]['size'],

		'tmp_name' => $_FILES[$filename]['tmp_name']);

	} elseif (isset($GLOBALS['_FILES'][$filename])) {

		global $_FILES;



		$uploaded_file = array('name' => $_FILES[$filename]['name'],

		'type' => $_FILES[$filename]['type'],

		'size' => $_FILES[$filename]['size'],

		'tmp_name' => $_FILES[$filename]['tmp_name']);

	} else {

		$uploaded_file = array('name' => $GLOBALS[$filename . '_name'],

		'type' => $GLOBALS[$filename . '_type'],

		'size' => $GLOBALS[$filename . '_size'],

		'tmp_name' => $GLOBALS[$filename]);

	}



return $uploaded_file;

}



// the $filename parameter is an array with the following elements:

// name, type, size, tmp_name

function tep_copy_uploaded_file($filename, $target) {

	if (substr($target, -1) != '/') $target .= '/';



	$target .= $filename['name'];



	move_uploaded_file($filename['tmp_name'], $target);

}

