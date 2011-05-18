<?php

	require_once 'static/comments/classes/Comments.php';

	

	$Comment = new Comments;

?>

<html>

	<head>

		<title>

			Comment System

		</title>

		<link rel="stylesheet" media="screen" href="css/main.css"/> 

		<link rel="stylesheet" media="screen" href="css/forms.css"/> 

		<link rel="stylesheet" media="screen" href="css/comments.css"/> 

	</head>

	<body>

		<table align="center">

			<tr>

				<td>

					<div id="content">

						<h1>

							Comment System (Non Ajax Version)

						</h1>

						<p>

							The Comment System is designed to be easily implemented with limited knowledge of PHP (optional Javascript knowledge). It is very easy to setup comments for any type of article or item. For instance to show the number of comments that have been made on a certain item you can use the following method.

							<br />

							<div class="code">

								(&lt;?php print $Comment->total(1); ?&gt;)

							</div>

							<h2>

								Produces the following: (<?php print $Comment->total(1); ?>)

							</h2>

							<hr>

							If you would like to display all the comments associated with the Identifier of 1, then all you need to do is simple call this function in php like so.

							<br /><br />

							<div class="code">

								&lt;?php<br />

									print $Comment->showComments(1);

									<br />

								?&gt;

							</div>

							<h2>

								This will display each comment on a new line that has been submitted for item #1

							</h2>

							

							<hr>

							

							The Comment System comes with a stand alone (no javascript) version as well as an AJAX version.

							<br /><br />

							<a href="index.php">Click here to view the Ajax Version</a> | <a href="administration.php">Click here to view Administration</a>

						</p>

						

						<!-- Comments -->

						<h1>

							Comments (<label id="lbl_comment_total"><?php print $Comment->total(1); ?></label>)

						</h1>

						<div id="div_Comments">

						<?php

							print $Comment->showComments(1);

						?>

						</div>

						<!-- Comment Form -->

						<h1>

							Add Comment

						</h1>

						<p>

							<?php

								if(isset($_GET['error']))

								{

									print '<div class="error">';

									$error = intval($_GET['error']);

									switch($error)

									{

										case 1:

											print 'Please fill in your name';

										break;

										case 2:

											print 'Please fill in your email';

										break;

										case 3:

											print 'Invalid Email';

										break;

										case 4:

											print 'Please fill in your comments';

										break;

									}

									print '</div>';

									print '<br />';

								}

							?>



							<?php

								print $Comment->showForm(1);

							?>

						</p>

						

					</div>

				</td>

			</tr>

		</table>

	</body>

</html>