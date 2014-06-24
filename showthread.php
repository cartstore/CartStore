<?php

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

$threadinfo_query= tep_db_query("SELECT title,forumid,lastpost,replycount+1 AS posts FROM " . TABLE_THREAD . " WHERE threadid='" . $_GET['threadid'] . "' AND forumid = '". $_GET['forumid'] . "'");
$ti = tep_db_fetch_array($threadinfo_query);

echo $title;

$posts_query= tep_db_query("SELECT dateline,postid,pagetext,title,username,email FROM " . TABLE_POST . " WHERE threadid='" . $_GET['threadid'] . "' AND forumid= '" . $_GET['forumid'] . "' ORDER BY dateline");
//Tabellenanfang

  echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" cellspacing=\"0\" cellpadding=\"1\"><TR class=\"headerNavigation\" height=\"20\">";
  echo "<TD WIDTH=20% class=\"headerNavigation\">" .  TEXT_AUTHOR .  "</TD>";
  echo "<TD WIDTH=* class=\"headerNavigation\">" .  TEXT_POST .  "</TD></TR>";

  $counter = 0;
  while ($post = tep_db_fetch_array($posts_query)) {
    $counter++;

    $postid=$post["postid"];
     if (($counter/2) == floor($counter/2)) {
          $list_class = "productListing-even";
        } else {
          $list_class = "productListing-odd";
        }

//     $postdate=date($dateformat,$post[dateline]+($timeoffset*3600));
//     $posttime=date($timeformat,$post[dateline]+($timeoffset*3600));

//     date("d.m.y g:i",$post["dateline"])

     $postitle=htmlspecialchars($post["title"]);
     $username=$post['username'];

     $userqs_query = tep_db_query("SELECT title FROM " . TABLE_POST . " WHERE username='$username'");
     $userqs = tep_db_fetch_array($userqs_query);
     $usertitle = $userqs["title"];
     $pagetext=$post["pagetext"];
     $pagetext=parsemessage($pagetext);

     echo "<TR class=\"" . $list_class . "\" height=\"20\" bgcolor=\"#FAFAFA\">";
//     echo "<TD WIDTH=\"10%\" class=\"productListing-data\" valign=\"top\">" . TEXT_USERNAME . ": " . $username . "<BR><small>" . TEXT_TITLE . ": " . $usertitle . "</small></TD>";
     echo "<TD WIDTH=\"10%\" class=\"productListing-data\" valign=\"top\"><b>" . $username . "</b><br><small>" . date('d.m.y g:i',$post['dateline']) . $posttime ."</TD>";
     echo "<TD WIDTH=* class=\"productListing-data\">";
     if($counter!=1)
      {
        echo "<STRONG>$postitle</STRONG><P>";
      }
        echo $pagetext;
        echo "</TD></TR>";
//        echo "</TD></TR><tr><td colspan=\"2\"></td></tr>";
      }
     echo "</TABLE>";

    $active_query = tep_db_query("SELECT allowposting FROM " . TABLE_THREAD . " WHERE threadid='" . $_GET['threadid'] ."'");
    $active = tep_db_fetch_array($active_query);
    $allow = $active['allowposting'];
    if ($allow == '1') {
//       echo "<a href=\"reply.php?threadid=" . $_GET['threadid'] . "&forumid=" . $_GET['forumid'] . "&action=newreply\">" . tep_image_button('button_write_review.gif', BUTTON_REPLY) . "</a>";
       echo "<b><A HREF=\"reply.php?threadid=" . $_GET['threadid'] . "&forumid=" . $_GET['forumid'] ."&action=newreply\">" . BUTTON_REPLY . "</A></b>";
    } else {
       echo TEXT_THREAD_CLOSED;
    }

echo'<br><br><A HREF="forums.php"><b>' . TEXT_FORUM_INDEX . '</b></A>';
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
