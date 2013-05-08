<?php



// IE fix to not cache any JSON data

header("Cache-Control: no-cache, must-revalidate");

header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");



require_once 'static/comments/classes/Comments.php';



$comment = new Comments;



// What type of request did we send?

switch($_GET['type'])

{

	case 'total':

		// Return Total Number of Entries

		print $comment->total(intval($_GET['id']));

	break;

	case 'load':

		// Return all Comments

		print $comment->showComments(intval($_GET['id']));

	break;

	case 'add':

		// Silently Add Comment into Database

		$id	= intval($_GET['id']);

		$name = addslashes($_GET['name']);

		$email = addslashes($_GET['email']);

		$text = addslashes($_GET['text']);

		$website = addslashes($_GET['website']);

		

		print $comment->js_add($id, $name, $email, $text, $website);

	break;

	default:

	break;

}

die;