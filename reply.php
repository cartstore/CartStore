<?php
/*
  $Id: reply.php,v 0.8 2005/05/31 11:13:05 $
  Autor Puddled - Enhancement: Ley
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com
  Copyright (c) 2008 Adoovo Inc. USA  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . 'forum.php');
  require(DIR_WS_LANGUAGES . $language . '/forums.php');
  define('TABLE_FORUM','forum');
  define('TABLE_THREAD','thread');
  define('TABLE_POST','post');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FORUMS, '', 'NONSSL'));
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
             $forumid = $_GET['forumid'];
  $threadid = $_GET['threadid'];


if ($action=="newreply") {

  $foruminfo_query=tep_db_query("SELECT title,description FROM " . TABLE_FORUM . "  WHERE forumid='" . $_GET['forumid'] . "'");
  while($foruminfo = tep_db_fetch_array($foruminfo_query))
  {
     $title = $foruminfo['title'];
     $description = $foruminfo['description'];
  }

  echo "<FORM ACTION=\"reply.php?threadid=" . $_GET['threadid'] . "&forumid=" . $_GET['forumid'] . "\" METHOD=\"post\">";
 if (tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {

    $customer_query = tep_db_query("select * from customers where customers_id = '$customer_id '");
    if (tep_db_num_rows($customer_query)) {

       $customer_info = tep_db_fetch_array($customer_query);
       echo"<FORM ACTION=\"newthread.php?forumid=" . $forumid . "\" METHOD=\"post\">";

       $username = $customer_info['customers_firstname'] . " " . $customer_info['customers_lastname'];
//       echo "das: " . $customer_info['customers_email_address'] , "<br>";
       $email_address = $customer_info['customers_email_address'];     

       ECHO ENTRY_EMAIL . $email_address . "<br>";
       ECHO ENTRY_NAME . $username . "<br>"; 
       echo "<input type=\"hidden\" name=\"username\" value=\"" . $username . "\">";
       echo "<input type=\"hidden\" name=\"email_address\" value=\"" . $email_address . "\">";
    }
 }
 else {
   echo ENTRY_NAME . "<br>" . tep_draw_input_field('username', ($error ? $_POST['username'] : $username)) . "<br>";
   echo ENTRY_EMAIL . "<br>" . tep_draw_input_field('email_address', ($error ? $_POST['email_address'] : $email_address));
      if ($error) echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR . "<br>";

 }
  $title_query = tep_db_query("SELECT title FROM " . TABLE_POST . " WHERE threadid='" . $_GET['threadid'] . "' AND forumid ='" . $_GET['forumid'] . "' ORDER BY dateline desc");
  $last_title=tep_db_fetch_array($title_query);
  
 
   echo TEXT_SUBJECT . ': <INPUT TYPE="TEXT" NAME="subject" value="RE: ' . $last_title['title'] . '"> <BR>';
   echo TEXT_MESSAGE . ': <TEXTAREA COLS=25 ROWS=10 name="m"></TEXTAREA><BR><input type="submit" class="button"><INPUT TYPE=HIDDEN NAME="action" VALUE="postreply"></FORM>';
  echo '<TABLE WIDTH=100% BORDER=0>';
  echo '<br><br>' . TEXT_PREVIOUS . '<br>';

$counter = 0;
$posts_query = tep_db_query("SELECT * FROM " . TABLE_POST . " WHERE threadid='" . $_GET['threadid'] . "' AND forumid ='" . $_GET['forumid'] . "' ORDER BY dateline asc");
while($post=tep_db_fetch_array($posts_query))
 {
   $counter++;
     if (($counter/2) == floor($counter/2)) {
          $list_class = "productListing-even";
        } else {
          $list_class = "productListing-odd";
        }

  $username=htmlspecialchars($post["username"]);
  $pagetext=$post["pagetext"];
  $pagetext=parsemessage($pagetext);

  echo "<TR class=\"" . $list_class . "\" height=\"20\">";
  echo "<TD WIDTH=\"10%\" class=\"productListing-data\">" . $username . "</TD>";
  echo "<TD WIDTH=* class=\"productListing-data\">" . $pagetext . "</TD></TR>";
 }
 echo "</TABLE>";

 echo"<br><br><A HREF=\"forums.php\"><b>" . TEXT_FORUM_INDEX . "</b></A>";
 echo"<br><a href=\"showthread.php?threadid=" . $_GET['threadid'] . "&forumid=" . $_GET['forumid'] . "\"><b>" . TEXT_BACK . "</b></a>";

  // ############################### start post thread ###############################
}
if ($action=="postreply") {

  // check for message
  if (trim($m)=="") {
    echo "Please enter a subject";
    exit;
  }

  $foruminfo_query=tep_db_query("SELECT title, forumid FROM " . TABLE_FORUM ." WHERE forumid= '" . $_GET['forumid'] . "'");
  $foruminfo = tep_db_fetch_array($foruminfo_query);
  $forumtitle=$foruminfo["title"];
  $forumid= $foruminfo["forumid"];
  // create post
  tep_db_query("INSERT INTO " . TABLE_POST . " VALUES ('','" . $_GET['threadid'] . "', '" . $_GET['forumid'] ."', '$username','$email_address', '$subject',". time().",'$m','1')");

  // update forum stuff
  tep_db_query("UPDATE " . TABLE_FORUM . " SET replycount=replycount+1 WHERE forumid= '" . $_GET['forumid'] . "'");
  tep_db_query("UPDATE ". TABLE_THREAD . " SET replycount=replycount+1,lastpostuser='$username',lastpost=".time()." WHERE threadid='" . $_GET['threadid'] . "' AND forumid='" . $_GET['forumid'] ."'");
  // redirect
  echo "<HTML>\n<HEAD>";
  echo "<meta http-equiv=\"Refresh\" content=\"1; URL=showthread.php?threadid=$threadid&forumid=$forumid\">";
  echo "\n</HEAD>\n<BODY>";
//  echo "Thank you for posting, $un2. You will now be taken to your post.";
  echo TEXT_THANK_YOU_POSTING;
  echo "\n</BODY>\n</HTML>";
  }
// tep_redirect(tep_href_link('./fourms_php.php', '', 'NONSSL'));

?>
            
            <!-- end of forum insert -->

            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php //echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
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
