<?php



require_once 'static/comments/classes/Comments.php';

require_once 'static/comments/classes/Validator.php';



$Comment = new Comments;

$validator = new Validator;



// Gather all the form input fields that are in the "comment" array "comment[]" and put them into a PHP Variable

$posted_comment = $_POST['comment'];



// Call the Add function of the class passing the correct parameters

if($Comment->add($posted_comment['id'], $posted_comment['name'], $posted_comment['email'], $posted_comment['comment'], $posted_comment['website']))

{

	// Everything went ok

	// Redirect the user back to the non-ajax page

	$back = $_SERVER['HTTP_REFERER'];

header("Location: $back");

}

else

{

	$error = '';

	

	// We have an error, shall we look up which one

	if(empty($posted_comment['id']))

	{

		$error = '0';

	}

	

	if(empty($posted_comment['name']))

	{

		$error = '1';

	}

	

	if(empty($posted_comment['email']))

	{

		$error = '2';

	}

	else

	{

		// Email is not empty does it validate

		if(! $validator->isEmail($posted_comment['email']))

		{

			$error = '3';

		}

	}

	

	if(empty($posted_comment['comment']))

	{

		$error = '4';

	}

	
$back = $_SERVER['HTTP_REFERER'];


	header('location: '.$back.'?error='. $error);

}



die;