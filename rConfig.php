<?php
include 'includes/configure.php';

$SimpleRatings->dbHost= DB_SERVER; //Database Host //Try "localhost"

$SimpleRatings->dbName= DB_DATABASE; //Database Name
$SimpleRatings->dbUser= DB_SERVER_USERNAME; //Database Username
$SimpleRatings->dbPass= DB_SERVER_PASSWORD; //Database Password

 
$SimpleRatings->ratingElements= 5; //How many ratings elements?
$SimpleRatings->rateStyle= "big"; //Choose your style: "small" - "large"

?>