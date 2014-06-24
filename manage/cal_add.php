<?php



require ('includes/application_top.php');



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">







<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title><?php

echo TITLE;

?></title>



<link href="templates/admin/css/template_css.css" rel="stylesheet"

	type="text/css" />











<script language="javascript" src="includes/general.js"></script>







</head>



<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"

	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">





<!-- header //-->



<?php

require (DIR_WS_INCLUDES . 'header.php');

?>



<!-- header_eof //-->







<!-- body //-->



<table border="0" width="100%" cellspacing="2" cellpadding="2">



	<tr>



		<td width="<?php

		echo BOX_WIDTH;

		?>" valign="top">

		<table border="0" width="<?php

		echo BOX_WIDTH;

		?>" cellspacing="1"

			cellpadding="1" class="columnLeft">



			<!-- left_navigation //-->



<?php

require (DIR_WS_INCLUDES . 'column_left.php');

?>



<!-- left_navigation_eof //-->



		</table>

		</td>



		<!-- body_text //-->



		<td width="100%" valign="top">



		

        

        <?php include 'event_calender/admin/cal_add.php'; ?>



        







		</td>





		<!-- body_text_eof //-->



	</tr>



</table>



<!-- body_eof //-->







<!-- footer //-->



<?php

require (DIR_WS_INCLUDES . 'footer.php');

?>



<!-- footer_eof //-->



<br>



</body>







</html>



<?php

require (DIR_WS_INCLUDES . 'application_bottom.php'); ?>