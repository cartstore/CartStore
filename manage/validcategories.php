<?php
/*
  $Id: validcategories.php,v 0.01 2002/08/17 15:38:34 Richard Fielder

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 Richard Fielder

  Released under the GNU General Public License
*/

require('includes/application_top.php');


?>
<!DOCTYPE html>
 <html class=" js no-touch localstorage svg">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Valid Product List</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
 		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
 		<link href="./templates/responsive-red/assets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css">
 
		<link href="./templates/responsive-red/assets/bootstrap.css" media="all" rel="stylesheet" type="text/css">

 	  

		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
				<link href="//codeorigin.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css" rel="stylesheet">

		 

	</head>
	<body class="contrast-red " style="">
<table class="table table-hover table-condensed table-responsive">
<tr>
<td colspan="4">
<h3><?php echo TEXT_VALID_CATEGORIES_LIST; ?></h3>
</td>
</tr>
<?php
    echo "<tr><th>" . TEXT_VALID_CATEGORIES_ID . "</th><th>" . TEXT_VALID_CATEGORIES_NAME . "</th></tr><tr>";
    $result = tep_db_query("SELECT * FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' ORDER BY c.categories_id");
    if ($row = tep_db_fetch_array($result)) {
        do {
            echo "<td>".$row["categories_id"]."</td>\n";
            echo "<td>".$row["categories_name"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = tep_db_fetch_array($result));
    }
    echo "</table>\n";
?>
<p>
<input type="button" class="btn btn-default" value="Close Window" onClick="window.close()"> </p>

<!-- / jquery [required] -->
		<script src="./templates/responsive-red/assets/jquery.min.js" type="text/javascript"></script>
		<!-- / jquery mobile (for touch events) -->
		<script src="./templates/responsive-red/assets/jquery.mobile.custom.min.js" type="text/javascript"></script>
		<!-- / jquery migrate (for compatibility with new jquery) [required] -->
		<script src="./templates/responsive-red/assets/jquery-migrate.min.js" type="text/javascript"></script>
		<!-- / jquery ui -->
		<script src="./templates/responsive-red/assets/jquery-ui.min.js" type="text/javascript"></script>
		<!-- / jQuery UI Touch Punch -->
		<script src="./templates/responsive-red/assets/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
		<!-- / bootstrap [required] -->
		<script src="./templates/responsive-red/assets/bootstrap.js" type="text/javascript"></script>
		<!-- / modernizr -->
		<script src="./templates/responsive-red/assets/modernizr.min.js" type="text/javascript"></script>
		<!-- / retina -->
		<script src="./templates/responsive-red/assets/retina.js" type="text/javascript"></script>
		<!-- / theme file [required] -->
		<script src="./templates/responsive-red/assets/theme.js" type="text/javascript"></script>
 		<!-- / END - page related files and scripts [optional] -->
 		
 		
 		
 		<script src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery.init.local.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckfinder/ckfinder.js"></script>

<script language="javascript" src="<?php
  echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>includes/general.js"></script>



<script language="javascript" type="text/javascript">
<!--
function popUp(url) {
	var winHandle = randomString();
	newwindow=window.open(url,winHandle,'height=800,width=1000');
}

function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}

jQuery("form[name='search'] .dropdown-menu a").click(function(){
	$("form[name='search'] .dropdown-menu").find("a i").remove();
	$(this).append('<i class="fa fa-check"></i>');
	$("form[name='search']").attr('action',$(this).attr('data-target'));
});
// -->
</script>

	</body>
</html>
