<?php
include '../../../includes/configure.php';
session_start();
include('dbconn.php');


// User is already logged in, they don't need to se this page.

if(isset($_SESSION['username'])) {
	header( "Location: index.php" );
	exit();
}

if(isset($_POST['login'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	//check that the user is calling the page from the login form and not accessing it directly
	//and redirect back to the login form if necessary
	if (!isset($username) || !isset($password)) {
	header( "Location: login.php" );
	}
	//check that the form fields are not empty, and redirect back to the login page if they are
	elseif (empty($username) || empty($password)) {
	header( "Location: login.php" );
	} else {

	//convert the field values to simple variables

	//add slashes to the username and md5() the password
	$user = addslashes($_POST['username']);
	$pass = md5($_POST['password']);


	$sql = "SELECT * FROM calendar_users WHERE username='$user' AND password='$pass'";
	$result = mysql_query($sql);

	//check that at least one row was returned
	$rowCheck = mysql_num_rows($result);

	if($rowCheck > 0) {
	while($row = mysql_fetch_array($result)) {

	  //start the session and register a variable

	  session_start();
	  session_register('username');

	  //successful login code will go here...

	  header( "Location: index.php");
	  exit();

	  }

	  } else {

	  //if nothing is returned by the query, unsuccessful login code goes here...

	  $error = '<div class="error_message">Incorrect username or password. Please try again.</div>';
	  }
	}
}

include('header.php');

?>

<h3>Login</h3>

<?php echo $error; ?>

<form method="POST" action="">
<label>Username</label><input type="text" name="username" size="20">
<br />
<label>Password</label><input type="password" name="password" size="20">
<br />
<input type="submit" value="Submit" name="login">
</form>

<?php include('footer.php'); ?>