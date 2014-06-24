<?php
/*
  $Id: newthread.php,v 0.9c 2006/05/31 11:13:05 $
  Author: Puddled, Enhancemend: Karsten Ley
  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . 'forum.php');
  require(DIR_WS_LANGUAGES . $language . '/forums.php');
  
  // check to see if we are logged in first
  // if not user is redirected to login page
  
 // This allows only registered users to start a new thread in the user forums
    if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

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
<?php
   $forumid = $_GET['forumid'];
   $foruminfo_query = tep_db_query("SELECT title,description FROM forum WHERE forumid='$forumid'");
   $foruminfo = tep_db_fetch_array($foruminfo_query);
//   $title = $foruminfo['title'];
?>


    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Forum: <?php echo $foruminfo['title']; ?></td>
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
             if (isset($action)==0 or $action=="") {
  $action="newthread";
}

// ############################### start new thread ###############################
if ($action=="newthread" && tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {
/*
   $foruminfo_query = tep_db_query("SELECT title,description FROM forum WHERE forumid='$forumid'");
   $foruminfo = tep_db_fetch_array($foruminfo_query);
   $title = $foruminfo['title'];
    echo $title;
//   echo "id: " . $customer_id . "<br>";
//   echo "id: " . $customer_first_name . "<br>";
*/
   $customer_query = tep_db_query("select * from customers where customers_id = '$customer_id '");
   if (tep_db_num_rows($customer_query)) {

      $customer_info = tep_db_fetch_array($customer_query);
      echo"<FORM ACTION=\"newthread.php?forumid=" . $forumid . "\" METHOD=\"post\">";

      $username = $customer_info['customers_firstname'] . " " . $customer_info['customers_lastname'];
      $email_address = $customer_info['customers_email_address'];     

      ECHO ENTRY_EMAIL . $email_address . "<br>";
      ECHO ENTRY_NAME . $username . "<br>"; 
      echo "<input type=\"hidden\" name=\"username\" value=\"" . $username . "\">";
      echo "<input type=\"hidden\" name=\"email_address\" value=\"" . $email_address . "\">";

//      echo ENTRY_EMAIL . "<br>" . tep_draw_input_field('username', ($error ? $_POST['username'] : $email_address)); 
//      if ($error) echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; 
      echo "<br>" . TEXT_SUBJECT . ":  <INPUT TYPE=\"TEXT\" NAME=\"subject\">";
      echo "<br>" . TEXT_MESSAGE . ": <BR><TEXTAREA COLS=25 ROWS=10 name=\"m\"></TEXTAREA><BR>";
      echo "<INPUT TYPE=\"Submit\"><INPUT TYPE=HIDDEN NAME=\"action\" VALUE=\"postthread\"></FORM>";
   }
   else {
      echo "ERROR";
   }
}
// ############################### start post thread ###############################

if ($action=="postthread" && tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {

  // check for subject and message
  if (trim($subject)=="" or trim($m)=="") {
    echo "Please enter a subject";
    exit;
  }

  //check valid name
 if ($username=="" or trim($username)=="") {
    echo TEXT_INPUT_NAME;
    die;
 }  

  $foruminfo = tep_db_query("SELECT title FROM forum WHERE forumid= '$forumid'");
  $forumtitle = $foruminfo["title"];

  //create new thread
  tep_db_query("INSERT INTO thread VALUES ('','$subject','" . time() . "','$forumid','0','$username', '$username', '" .time(). "', '1')");
  // $forum->query($qs);
  $threadid = tep_db_insert_id();
  // create first post
  tep_db_query("INSERT INTO post VALUES ('','$threadid', '$forumid','$username','$email_address', '$subject',". time().",'$m','0')");

  // update forum stuff
  tep_db_query("UPDATE forum SET replycount=replycount+1,threadcount=threadcount+1,lastpost=".time()." WHERE forumid='$forumid'");


  // redirect
  echo "<HTML>\n<HEAD>";
  echo "<meta http-equiv=\"Refresh\" content=\"1; URL=showthread.php?threadid=$threadid&forumid=$forumid\">";
  echo "\n</HEAD>\n<BODY>";
  echo "Thank you for posting, $un2. You will now be taken to your post.";
  echo "\n</BODY>\n</HTML>";
}

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