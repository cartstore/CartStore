<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

require('includes/application_top.php');
$contents = "" ; 
$filPath = "../templates/includes/modules/static_menu1.php"; 
if ($_POST['submit'] == 'Update') {
	// Let's make sure the file exists and is writable first.
	if (is_writable($filPath)) {
		
		if (!$handle = fopen($filPath, 'wb')) {
			 echo "Cannot open file ($filPath)";
			 exit;
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, stripslashes($_POST['categories_htc_description'])) === FALSE) {
			echo "Cannot write to file ($filPath)";
			exit;
		}
		fclose($handle);
	} else {
		echo "The file $filPath is not writable";
	}
  }

//$handle1 = fopen($filPath, 'r');

$contents = '';
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>




   <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
Code Module Position 1</h1></div>
              <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-question-circle fa-5x pull-left"></i>
The code module positions are certain areas we may designate in your stores front end so that you may change its content that are to advanced of code to edit in a WYSIWYG editor. Such as advanced java script.                        </div>
                      </div>
                  </div>   
              </div>    
<form name="frmConfigration" method="post">  

<div class="form-group">
		<span class="class="mceEditor"">
		<textarea  class="form-control" name="categories_htc_description" wrap="soft" cols="100" rows="25"><?php echo htmlspecialchars(stripslashes(file_get_contents($filPath))); ?></textarea></span>

		<input type="hidden" name="action" value="updateimage">
		</div>
		<p></p><input type="submit" class="btn btn-default" name="submit" value = "Update"></p>


</form>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>