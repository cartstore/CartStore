<?php
error_reporting(E_ALL);
/*
  $Id: forums.php,v 0.1 2005/05/31 11:13 $
  Author Puddled - Modify Ley
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . 'forum.php');
  require(DIR_WS_LANGUAGES . $language . '/forums.php');
  define('TABLE_FORUM','forum');
  define('TABLE_THREAD','thread');
    
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FORUMS, '', 'NONSSL'));
   if (!isset($_GET['forumid'])){
    echo "<HTML>\n<HEAD>";
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php\">";
    echo "\n</HEAD>\n<BODY>";
    echo "\n</BODY>\n</HTML>";
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" align="center" width="100%" cellspacing="0" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<?php
   if(isset($_GET['forumid'])) 
   {
      $data = tep_db_query("select forumid, title FROM " . TABLE_FORUM ." WHERE forumid= '" . $_GET['forumid'] ."'");

		while($qs = tep_db_fetch_array($data))
		{
		   $forumid = $qs["forumid"];
		   $title = $qs["title"];
		}
		$qs = tep_db_fetch_array($data);

	}
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $title; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . '/table_background_reviews_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
<!-- insert forum here -->
<?php
   if(isset($_GET['forumid']))
      $data = tep_db_query("select forumid, title FROM " . TABLE_FORUM ." WHERE forumid= '" . $_GET['forumid'] ."'");

/*   while($qs = tep_db_fetch_array($data))
    {
       $forumid = $qs["forumid"];
       $title = $qs["title"];
    }
   echo $title;
*/
//ShowHeader($qs["title"], $qs["title"]);
  $thread = tep_db_query("SELECT threadid, title, lastpost, forumid, replycount, lastpostuser, postusername, dateline from " . TABLE_THREAD . " where forumid='" . $_GET['forumid'] . "' ORDER BY dateline DESC");
  $anzahl = tep_db_num_rows($thread);
  if ($anzahl == 0 ) {
	  echo TEXT_NEW_THREAD;
	  echo "<br><br><A HREF=newthread.php?forumid=" . $_GET['forumid'] . ">" . BUTTON_NEW_THREAD . "</A>";
//  exit;
  } 
  else {  
  echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" cellspacing=\"0\" cellpadding=\"5\"><TR class=\"headerNavigation\" height=\"20\">";
  echo "<TD COLSPAN=\"2\" WIDTH=30% class=\"headerNavigation\">" .  TEXT_TITLE .  "</TD>";
  echo "<TD WIDTH=20% class=\"headerNavigation\">" . TEXT_AUTHOR . "</TD>";
  echo "<TD WIDTH=10% class=\"headerNavigation\">" . TEXT_REPLIES . "</TD>";
  echo "<TD WIDTH=40% class=\"headerNavigation\">" . TEXT_LAST_POST . "</TD>";
  echo "</TR>";

  $number_of_threads = 0;
  while($something = tep_db_fetch_array($thread))
  {

    $number_of_threads++;
    if (($number_of_threads/2) == floor($number_of_threads/2)) {
         $list_class = "productListing-even";
       } else {
         $list_class = "productListing-odd";
       }
 
    echo "<TR class=" . $list_class . ">";
    echo "<TD class=\productListing-data\"><img src=\"images/design/cat_arrow_other.gif\"></td>";
    echo "<TD WIDTH=30% class=\"productListing-data\"><A HREF=\"showthread.php?threadid=" . $something['threadid'] . "&forumid=" . $something['forumid'] . "\"><b>" . $something["title"] . "</b></A></TD>";
    echo "<TD WIDTH=20% class=\"productListing-data\">" . $something["postusername"] . "</TD>";
    echo "<TD WIDTH=10% class=\"productListing-data\">" . $something["replycount"] . "</TD>";
    echo "<TD WIDTH=40% class=\"productListing-data\"> " . date("d.m.y g:i",$something["lastpost"]) . "<br><small>" . TEXT_POST_BY . $something["lastpostuser"] . "</small></TD>";
    echo "</TR>";
   }
  echo "</TABLE><P><br>";
  //if (tep_session_is_registered('customer_id')){
  echo TEXT_NEW_THREAD;
  echo "<br><br><b><A HREF=newthread.php?forumid=" . $_GET['forumid'] . ">" . BUTTON_NEW_THREAD . "</A></b>";
   }
 echo '<br><br><A HREF="forums.php"><b>' . TEXT_FORUM_INDEX . '</b></A>';
?>
<!-- end of forum insert -->
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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