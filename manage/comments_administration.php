<?php

	require_once '../templates/system/comments/classes/Comments.php';

	

	$Comment = new Comments;

?>


					<div id="content">

						<h1>

							Comment System (Administration Page)

						</h1>

						<p>

							This is the Administration section where you can delete comments from the list.

							<br />

							<h2>

								How does it work?

							</h2>

							<hr>

							You list all of the comments for the item you are looking for, except you include a link that will delete the selected comment. (See below for an example)

							<br /><br />

							<a href="index.php">Click here to Return</a>

						</p>

						

						<!-- Comments -->

						<h1>

							Comments (<?php print $Comment->total(1); ?>)

						</h1>

						<div id="dv_comments">

							<?php

								// Load comments for item 1

								$results = $Comment->loadComments(1);

								

								foreach($results as $id => $comment)

								{

									print '<div class="comment_box">';

									print '<a href="comment_delete.php?id='. $comment['comment_id'] .'">delete comment</a><hr>';

									print '<span class="comment_text">#'. ($id + 1) .': '. stripslashes($comment['comment_text']) .'</span>';

									print '<br />';

									

									print '<div class="comment_posted">';

									print 'Posted by ';

									print '<span class="comment_name">'.stripslashes($comment['comment_name']) .'</span>';

									print ' on ';

									print '<span class="comment_date">'. date('D M d, Y g:i:s a', strtotime($comment['comment_date'])) .'</span>';

									print '</div>';

									print '</div>';

									print '&nbsp;';

								}

							?>

						</div>						

					</div>
