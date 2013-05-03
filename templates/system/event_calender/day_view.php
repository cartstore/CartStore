<?php
// Works as of PHP 4.3.0
include '../../../includes/configure.php';
include('admin/dbconn.php');
include('functions/functions.php');

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo '<html>';
echo '<head><link rel="stylesheet" href="stylesheet_event_view.css" /><!--<link rel="stylesheet" href="greybox/gb_styles.css" />--></head>';
echo '<body>';

$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

list_events($day,$month,$year);

echo '</body>';
echo '</html>';

?>