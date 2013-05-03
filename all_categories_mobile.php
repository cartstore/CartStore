<?php
  require('includes/application_top.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php
  echo HTML_PARAMS;
?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php
  echo CHARSET;
?>">

<title><?php
  echo TITLE;
?></title>

<base href="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->

<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<!-- header_eof //-->



<!-- body //-->



<!-- left_navigation //-->

<?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>

<!-- left_navigation_eof //-->



<!-- body_text //-->

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td>
    	
    	 <div class="bluebg">
    	 	
    	 	<?php
  include(DIR_WS_MODULES . 'homecats_mobile.php');
?>


</div>

 



</td>

  </tr>

</table>



<!-- body_text_eof //-->

  

<!-- right_navigation //-->

<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>

<!-- right_navigation_eof //-->

    </table></td>

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>

<!-- footer_eof //-->

</body>

</html>

<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>