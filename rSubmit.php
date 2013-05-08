<?php
include 'includes/application_top.php';
header("Cache-Control: no-cache");

header("Pragma: no-cache");



//Class

require('rClass.php');



//Call Function

$SimpleRatings->submit();

?>