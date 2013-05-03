<?php

/*

  $Id: server_info.php,v 1.6 2003/06/30 13:13:49 dgw_ Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');

  

  /********************** BEGIN VERSION CHECKER *********************/

  if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php'))

  {

     require(DIR_WS_LANGUAGES . $language . '/version_checker.php');

     require(DIR_WS_FUNCTIONS . 'version_checker.php');

     $contribPath = 'http://addons.oscommerce.com/info/4513';

     $currentVersion = 'GoogleBase V 2.5';

     $contribName = 'GoogleBase V';

     $versionStatus = '';

  }

  /********************** END VERSION CHECKER *********************/  

  

  $checkingVersion = false;

  $action = (isset($_POST['action']) ? $_POST['action'] : '');

  

  if (tep_not_null($action))

  {

     /********************** CHECK THE VERSION ***********************/

     if ($action == 'getversion')

     {

         $checkingVersion = true;

         if (isset($_POST['version_check']) && $_POST['version_check'] == 'on')

             $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);

     }

  }     



?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

	 	

<script language="javascript" src="includes/general.js"></script>

</head>

<body>

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

        <td><table border="0" width="95%" cellspacing="0" cellpadding="0">

            <tr>

               <td class="pageHeading" valign="top"><h1>GoogleBase Datafeed</h1></td>

            </tr>

            <tr>

               <td class="smallText" valign="top"><b>You can also run this script automaticly from cron with:</b><br>

<i><?php echo 'php -q '. DIR_FS_ADMIN . 'googlefeeder.php'; ?></i><br>

<div id="dialog-message"" title="Important">
You should create a cron job to run the feeder on a regular basis. 

<b>If the feed is not updated once a month, google may remove your 

products from their listings.</b> To prevent that, the cron job 

should be setup to run that often. I suggest once a week to be 

sure it catches any changes you may make to the products during 

the week. The syntax for the cron job varies from host to host 

but the above may work. If not, you will need to contact CartStore commercial support for the proper syntax.



</div>

You will need a google base account and you will need this script configured for ftp use as well as other settings. CartStore provides commercial configuration assistance for this google base script.<br>

<br>

Creates a data feed file for Google Base. This

Google Data Feeder, handles product duplicates, specials (if 

available), currency conversion, tax support, SEO links, html 

descriptions, full category trees, automatic upload and more. 
</td>

            </tr>

        </table></td>               

        <td><table border="0" width="100%">

         <tr>       

          <td class="smallText" align="right"></td>

         </tr>

         <?php  

         if (function_exists('AnnounceVersion')) {

            if (false) { //requires database change so skip 

         ?>

               <tr>

                  <td class="smallText" align="right" style="font-weight: bold; color: red;"><?php echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?></td>

               </tr>

         <?php } else if (tep_not_null($versionStatus)) { 

           echo '<tr><td class="smallText" align="right" style="font-weight: bold; color: red;">' . $versionStatus . '</td></tr>';

         } else {

           echo tep_draw_form('version_check', 'feeders.php', '', 'post') . tep_draw_hidden_field('action', 'getversion'); 

         ?>

               <tr>

                  <td class="smallText" align="right" style="font-weight: bold; color: red;"><INPUT TYPE="radio" NAME="version_check" onClick="this.form.submit();"></td>

               </tr>

           </form>

         <?php } } else { ?>

            <tr>

               <td class="smallText" align="right" style="font-weight: bold; color: red;"></td>

            </tr>

         <?php } ?>         

        </table></td>

       </tr>  

      <tr><td height="20"></td></tr>

      <tr>

       <td>

         <?php echo '<a class="button" href="' . tep_href_link('googlefeeder.php') . '" target=_blank">Create Google Feed File</a>'; ?>

       </td>

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

