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

$filPath = "../templates/includes/boxes/adsence.php"; 

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
                  </a>  Adsence Code Position 1</h1></div>
           <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-question-circle fa-5x pull-left"></i>
The Adsence code inserter allows you to paste in your Adsence code so that your Adsence ads may show in certain areas such as blog articles or anywhere else in  your template.                        </div>
                      </div>
                  </div>   
              </div>   
<form name="frmConfigration" method="post" enctype="multipart/form-data">  

 

		<span class="class="mceEditor"">

<div class="form-group">
		<textarea class="form-control" wrap="soft" cols="100" rows="25" name="categories_htc_description"><?php echo htmlspecialchars(stripslashes(file_get_contents($filPath))); ?></textarea></span> 
</div>

    <p>
		<input type="hidden" name="action" value="updateimage">

		<input type="submit" class="btn btn-default" name="submit" value = "Update">
    </p>


		</td>

	</tr>

</table>

</form>







    </td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>



</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>