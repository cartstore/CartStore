<?php 
/*
  RSS News for OSC 2.2 MS2 v1.0  12.09.2004
  Originally Created by: Jack York
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

  require('includes/application_top.php');
  
  if (isset($_GET['action']) && ($_GET['action'] == 'get')) {
   $rss_title = (isset($_POST['rss_title']) ? $_POST['rss_title'] : '');
	 $rss_desc = (isset($_POST['rss_desc']) ? $_POST['rss_desc'] : '');
	 $rss_link = (isset($_POST['rss_link']) ? $_POST['rss_link'] : '');
   $rss_item_title = (isset($_POST['rss_item_title']) ? $_POST['rss_item_title'] : '');
	 $rss_item_desc = (isset($_POST['rss_item_desc']) ? $_POST['rss_item_desc'] : '');
	 $rss_item_link = (isset($_POST['rss_item_link']) ? $_POST['rss_item_link'] : '');
   $error = false;
 	 
 	 if (! tep_not_null($rss_title)) {
	  $error = true;
    $messageStack->add('Missing Title');
	 }
	 if (! tep_not_null($rss_desc)) {
	  $error = true;
    $messageStack->add('Missing Desctiption');
	 }  
	 if (! tep_not_null($rss_link)) {
	  $error = true;
    $messageStack->add('Missing Link');
	 }    
	 if (! tep_not_null($rss_item_title)) {
	  $error = true;
    $messageStack->add('Missing Item Title');
	 }
	 if (! tep_not_null($rss_item_desc)) {
	  $error = true;
    $messageStack->add('Missing Item Desctiption');
	 }  
	 if (! tep_not_null($rss_item_link)) {
	  $error = true;
    $messageStack->add('Missing Item Link');
	 } 
	
	 if ($error == false) { 
     $rss_file = DIR_FS_CATALOG . 'feeds/'. $rss_title . '.xml';
     $fp = fopen($rss_file, 'w');

		 if ($fp) {
			 $fileData = '<?xml version="1.0" ?>' . "\n" . 
                   '<rss version="2.0">' . "\n\n" .
			  					 '<channel>' . "\n" .
                   '<title>' . $rss_title . '</title>' . "\n" .
									 '<description>' . $rss_desc . '</description>' . "\n" .
									 '<link>' . $rss_link . '</link>' . "\n\n" .
									 '<item>' . "\n" .
									 '<title>' . $rss_item_title . '</title>' . "\n" .
									 '<description>' . $rss_item_desc . '</description>' . "\n" .
									 '<link>' . $rss_item_link . '</link>' . "\n" .
									 '</item>' . "\n\n" .
									 '</channel>' . "\n\n" .
									 '</rss>';
			 fputs($fp, $fileData); 
			 fclose($fp);
		 }
		 else
			$messageStack->add('Failed to write file');			
		}					 
	}   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
 
 			<tr> 
			 <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '5'); ?></td>
      </tr>	
			<tr>
       <td><?php echo tep_black_line(); ?></td>
      </tr>
			<tr>
		   <td align="right" > <?php echo tep_draw_form('rss_create', FILENAME_RSS_NEWS_CREATE,  'action=get'); ?></td>
      </tr>          
			   
				<tr class="infoBoxContents">
         <td><table border="0" cellspacing="2" cellpadding="2">
          <tr> 
			  	 <td>Title: </td>
           <td><?php echo tep_draw_input_field('rss_title', tep_not_null($rss_title) ? $rss_title : '', 'maxlength="255", size="40"',   false); ?> </td>
    	   	</tr>
					<tr>
					<tr> 
			  	 <td>Description: </td>
           <td><?php echo tep_draw_textarea_field('rss_desc', 'hard', 36, 5, tep_not_null($rss_desc) ? $rss_desc : ''); ?> </td>
    	   	</tr>
					<tr> 
			  	 <td>Link: </td>
           <td><?php echo tep_draw_input_field('rss_link', tep_not_null($rss_link) ? $rss_link : '', 'maxlength="255", size="40"',   false); ?> </td>
    	   	</tr>
					 <tr> 
			  	 <td>Item Title: </td>
           <td><?php echo tep_draw_input_field('rss_item_title', tep_not_null($rss_item_title) ? $rss_item_title : '', 'maxlength="255", size="40"',   false); ?> </td>
    	   	</tr>
					<tr>
					<tr> 
			  	 <td>Item Description: </td>
           <td><?php echo tep_draw_textarea_field('rss_item_desc', 'hard', 36, 5, tep_not_null($rss_item_desc) ? $rss_item_desc : ''); ?> </td>
    	   	</tr>
					<tr> 
			  	 <td>Item Link: </td>
           <td><?php echo tep_draw_input_field('rss_item_link', tep_not_null($rss_item_link) ? $rss_item_link : '', 'maxlength="255", size="40"',   false); ?> </td>
    	   	</tr>
   			<tr> 
		   	 <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
         </tr>	
         <tr>
 			     <td ><?php echo (tep_image_submit('button_create_feed.gif', 'Create RSS Feed File') ) . ' <a href="' . tep_href_link(FILENAME_RSS_NEWS_CREATE, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?></td>
 				  </tr>
			   </table></td>				 
				</tr>					 		  
      </form>
 
 
 
 
      </tr>
    </table></td>
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