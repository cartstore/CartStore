<?php
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
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td >
            <!-- insert forum here -->
           <TABLE WIDTH=100% BORDER=0 cellspacing="0" cellpadding="0"><TR class="headerNavigation" height="20">
     <?php
          echo "<TD WIDTH=\"10%\" class=\"headerNavigation\">" . TEXT_FORUM_NAME . "</TD>";
          echo "<TD WIDTH=\"35%\" class=\"headerNavigation\">" . TEXT_FORUM_DESCRIPTION . "</TD>";
//          echo "<!--<TD WIDTH=10%>Last post</TD> -->";
          echo "<TD WIDTH=\"10%\" class=\"headerNavigation\" align=\"center\">" . TEXT_MESSAGE. "</TD>";
          echo "<TD WIDTH=\"5%\" class=\"headerNavigation\" align=\"center\">" . TEXT_THREADS . "</TD></TR>";

$number_of_forums= '0';

$forum_info_query = tep_db_query("SELECT forumid, title, description, active, displayorder, replycount, lastpost, threadcount, allowposting FROM ". TABLE_FORUM ."");
while ($forum_info =  tep_db_fetch_array($forum_info_query))
{
   $forumid = $forum_info['forumid'];
   $title = $forum_info['title'];
   $description = $forum_info['description'];
   $active = $forum_info['active'];
   $dis_order = $forum_ifo['displayorder'];
   $reply_count = $forum_info['replycount'];
   $lastpost = $forum_info['lastpost'];
   $threadcount = $forum_info['threadcount'];
   $allow_post = $forum_info['allowpost'];

   $number_of_forums++;

   if (($number_of_forums/2) == floor($number_of_forums/2)) {
        $list_class = "productListing-even";
      } else {
        $list_class = "productListing-odd";
      }

   echo "<TR class=\"" . $list_class . "\" height=\"30\">";
   echo "<TD WIDTH=\"15%\" class=\"productListing-data\" height=\"30\"><img src=\"images/design/board.png\" align=\"left\">&nbsp;<A HREF=\"forum.php?forumid=$forumid\"><b>" . $title . "</b></A></TD>";
   echo "<TD WIDTH=35% class=\"productListing-data\" height=\"30\">" . "$description" . "</TD>";
   //echo "<TD WIDTH=10% BGCOLOR=\"#FFBF5B\" height=\"20\">" . date("m/d/y g:i:s a", $lastpost) . "</TD>"; 
   echo "<TD WIDTH=10% align=center class=\"productListing-data\" height=\"30\">" . "$reply_count" . "</TD>";
   echo "<TD WIDTH=5% align=center class=\"productListing-data\" height=\"30\">" . "$threadcount" . "</TD></TR>";
}

echo "</TABLE>";
echo "<br>" . TEXT_EXPLANATION_FORUM;
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
