<?php
  require('includes/application_top.php');
  if ( isset($_GET['imgName']) && trim($_GET['imgName'])!='') {
	  $stringPath = '../library/'.$_GET['imgName'];
	  $statusV = unlink($stringPath) 	;
	  if ($statusV) {
		header("Location: imageupload.php?act=dltd");
		exit();
	  } else {
		header("Location: imageupload.php?act=errN");
		exit();
	  }
  }

  if ( isset($_POST[upload]) && $_POST[upload] == 'Upload') {

	$path = DIR_FS_CATALOG . "library";
	for ($cnt_gra=0 ; $cnt_gra < 2 ; $cnt_gra++) {
		if ( trim( $_FILES['fileUpload']['name'][$cnt_gra] )!='' ) {
			$uploadfile = $path. "/".$_FILES['fileUpload']['name'][$cnt_gra] ;
			if ( move_uploaded_file($_FILES['fileUpload']['tmp_name'][$cnt_gra], $uploadfile ) ) {
				$m='s';
			}  else {
				header("Location: imageupload.php?m=f");
				exit();
			}

		} // end of if upload

	} // end of for
	if ($m=='s') {
		header("Location: imageupload.php?m=s");
		exit();
	}

} // end of file

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<script>
	function delImage(imgName) {
		if(confirm("Are you sure you wanted to delete image")) {
			location.href = 'imageupload.php?imgName='+imgName;
		}
	}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
		<table border="0" cellpadding="2" cellspacing="2" width="80%">
			<tr>
				<td colspan="4" class="pageHeading">Image Rotator</td>
			</tr>

			<tr height="10">
				<td colspan="4"></td>
			</tr>

			<tr height="10">
				<td colspan="4"> <span class="main">Use this upload form to upload images to your web site. Once uploaded they will go into a directory names library in the root of your web site. For example if you uploaded a image named image.jpg here, you could access that image via your web site by the following url http://www.yourdomain.com/library/image.jpg</span>
			  <p><b> Add Image</b></td>
			</tr>

			<?php
				if (isset($_GET['act']) && trim($_GET['act'])!='') {
			?>
			<tr >
				<td colspan="4"> <font color="red">
				<b>
				<?php
				if ($_GET['act']=='dltd')		{
					echo "Image Deleted successfully." ;
				} elseif ($_GET['act']=='errN') {
					echo "There is some permission problem you can not delete this image";
				} elseif ($_GET['m']=='f') {
					echo "There is some permission problem you can not delete this image";
				} elseif ($_GET['m']=='s') {
					echo "Image uploaded successfully";
				}
				?>
				</b>
				</font>
				</td>
			</tr>
			<?php
			}
			?>


			<tr>
			<td colspan="4">
			<div id="addImage">

			<form action="imageupload.php" method="post" enctype="multipart/form-data">
			<table border="0" cellspacing="4" cellpadding="4" width="94%">

			<?php
			for ($cnt = 1 ; $cnt < 3 ; $cnt++)	{
			?>
			<tr>
				<td width="40px">
					<b>File <?php echo $cnt; ?></b></td>
				<td class="dataTableHeadingContent">
					<input class="inputbox"  type="file" name="fileUpload[]">
				</td>
		    </tr>
			<?php
			}
			?>
			<td colspan="2"><div align="center">
	          <input type="submit" class="button" value="Upload" name="upload">
	          </div></td>
		    </tr>

			</table>
			</form>
			</div>
			</td>
			</tr>



			<?php
			if ($handle = opendir('./../library')) {
				$i = 0 ;
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$i++;
						echo '<tr class="dataTableHeadingContent">';
						echo '<td ><font color="black"><b>'.$i.'</b></font></td>';
						echo '<td ><input type="checkbox" name="imageName[]"></td>';
						echo '<td ><img src="../library/'.$file.'" border="0" width="50" height="100%"></td>';
						echo '<td><a href="imageupload.php?imgName='.$file.'" ><strong>Delete</strong></a></td>';
						echo '</tr>';
					}

				}
				closedir($handle);
			}
			?>

		</table>



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
