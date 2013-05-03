<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
  require('includes/application_top.php');
  // if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
function findexts ($filename)
{
	$filename = strtolower($filename) ;
	$exts = preg_split("/[\/\.]/", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
}

// #################### Begin Added CGV JONYO ######################
if ( isset($_POST[upload]) && $_POST[upload] == 'Upload')
{
	$path = DIR_FS_CATALOG . "uploaded_order_files";
	if (!is_dir($path))
	{
		mkdir($path, 0777);
		chmod($path, 0777);
	}
	$sql_customer = "SELECT customers_email_address FROM customers WHERE customers_id='".$_SESSION[customer_id]."'" ;
	$rs_customer = mysql_query($sql_customer) ;
	$dt_customer = mysql_fetch_array($rs_customer) ;
	$customers_email_address = $dt_customer['customers_email_address'] ;

	$orders_id = $_REQUEST['orders_id'];
	$txt_file_content = 'Customer email : '. $customers_email_address . '\n Order Number: '. $orders_id .'\n \n Files uploaded with order: \n \n';

	for ($cnt_gra=0 ; $cnt_gra < 6 ; $cnt_gra++)
	{
		if ( trim( $_FILES['fileUpload']['name'][$cnt_gra] )!='' )
		{
			$extension = findexts($_FILES['fileUpload']['name'][$cnt_gra]);
			$image_products_name = $orders_id."_".$_REQUEST['products_name'][$cnt_gra].".".$extension;

			$browse_path = HTTP_SERVER."/uploaded_order_files/";

			$uploadfile = $path. "/".$image_products_name ;


			if ( move_uploaded_file($_FILES['fileUpload']['tmp_name'][$cnt_gra], $uploadfile ) )
			{
				$txt_file_content .= $_REQUEST['products_name'][$cnt_gra] . " /image file uploaded and can be found here " .  $browse_path.$image_products_name."\n \n";
				$m='s';
			}
			else
			{
				header("Location: upload_graphic.php?m=f");
				exit();
			}

		} // end of if upload

	} // end of for

	$txt_file_content = strip_tags($txt_file_content, '\n') ;
	$file_path_txt = $path."/".$orders_id.".txt" ;
	$handle = fopen($file_path_txt,"w+") ;
	fwrite($handle, $txt_file_content);
	fclose($handle) ;

	$to = STORE_OWNER_EMAIL_ADDRESS;
	$subject = 'Orders Details Related to order Number:'.$orders_id;
	$message = $txt_file_content;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	$headers .= 'From: <'.STORE_OWNER_EMAIL_ADDRESS.'>' . "\r\n";
	// Mail it
	mail($to, $subject, $message, $headers);

	if ($m=='s')
	{
		header("Location: upload_graphic.php?m=s");
		exit();
	}

} // end of file

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<style type="text/css">
<!--
.style1 {font-size: 12px}
.style3 {
	font-size: 16px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">

	<form action="upload_graphic.php" method="post" enctype="multipart/form-data">
	  <div align="center">
	    <table border="0" cellpadding="1" cellspacing="2" width="80%">
	      <tr>
	        <td colspan="2" class="pageHeading" valign="top">Upload Images  </td>
		  </tr>
		  <tr height="16px">
	        <td colspan="2" class="pageHeading" valign="top">&nbsp;</td>
		  </tr>

		  <?php
		  if ( isset($_GET[m]) ) {
			  echo '<tr><td colspan="2" class="style1"><font color="red">';
			  if ($_GET[m] == 's') {
				echo "File was successfully uploaded!";
			  }
			  if ($_GET[m] == 'f') {
				echo "Possible file upload attack!";
			  }
			  echo '</font></td> </tr> <tr> ' ;
		  }
		  $orders_id = $_REQUEST['orders_id'];
		  $sql_query = "SELECT p.products_image, o.products_name FROM orders_products o, products p WHERE o.products_id = p.products_id	AND o.orders_id ='".$orders_id."'";
		  $rs_query = mysql_query($sql_query);
		  if ( mysql_num_rows($rs_query) > 0 )
		  {
			  while ( $dt_query = mysql_fetch_array($rs_query) )
			  {
			  ?>
			  <tr>
				<td width="100px">
				<?php echo tep_image(DIR_WS_IMAGES . $dt_query['products_image'], addslashes($dt_query['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' class="imageborder"');
				?>

				</td>
				<td>
				<span class="catfont"><?php echo $dt_query['products_name'];?></span> <br>
					<input type="file" name="fileUpload[]">
					<input type="hidden" name="products_name[]" value="<?php echo $dt_query['products_name'];?>">
				</td>
			  </tr>
			  <?php
			  }
		  }
		?>
	      <tr>
	        <td colspan="2"><div align="center">
			  <input type="hidden" name="orders_id" value="<?php echo $orders_id;?>" >
	          <input type="submit" class="button" value="Upload" name="upload">
	          </div></td>
		    </tr>
	        </table>
	    </div>
	</form>	</td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>