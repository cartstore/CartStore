<?php

/*

  $Id: links_status.php,v 1.00 2003/10/03 Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_STATUS);

  require(DIR_WS_FUNCTIONS . 'links.php');



  $checkState = '1';

    

  if (isset($_GET['action']) && $_GET['action'] == 'process')

  {

    if (isset($_POST['update_x']) && isset($_POST['ids']))

    {  

       $links_check_query = tep_db_query("SELECT l.links_id, links_contact_name, links_contact_email, links_url from " . TABLE_LINKS . " l left join " . TABLE_LINKS_DESCRIPTION . " ld on l.links_id = ld.links_id where ld.language_id = '" . $languages_id . "' or ld.language_id = '99'");

       while ($links = tep_db_fetch_array($links_check_query))

       {

         $box = sprintf("links_status_checkbox%d",$links['links_id']);

         if (in_array($box, $_POST['ids']))

         {

           if (isset($_POST['group1']) && $_POST['group1'] == 'delete_links')

           {

             tep_remove_link($links['links_id']);

             $links_statuses_query = tep_db_query("select count(*) as total from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");

             $links = tep_db_fetch_array($links_statuses_query);

             $checkState = $links['total']; 

           }

           else

           {

             tep_db_query("update " . TABLE_LINKS . " SET links_status = '" . $_POST['group1'] . "' where links_id = '" . $links['links_id'] . "'");

             $checkState = $_POST['group1'];

             

             if ($_POST['notify_link_partner'] == 'on') //let the link partner know about the change

             {

               $links_statuses_query = tep_db_query("select links_status_name from " . TABLE_LINKS_STATUS . " where links_status_id = '" . $_POST['group1'] . "' and language_id = '" . (int)$languages_id . "'");

               $links_status = tep_db_fetch_array($links_statuses_query);             

               $email = sprintf(EMAIL_TEXT_STATUS_UPDATE, $links['links_contact_name'], $links_status['links_status_name'], $links['links_url']) . "\n\n" . STORE_OWNER . "\n" . STORE_NAME;

               tep_mail($links['links_contact_name'], $links['links_contact_email'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

             }

           } 



           $page = (isset($_POST['page']) ? $_POST['page'] : '1');

         } 

       }

     }  

  }

  $linkShow = array();

  $links_status_query = tep_db_query("select links_status_id, links_status_name from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");

  $linkShow[] = array('id' => 'All', 'text' => 'All');

  while ($links_status = tep_db_fetch_array($links_status_query)) {

    $linkShow[] = array('id' => $links_status['links_status_id'],

                        'text' => $links_status['links_status_name']);

  }

  $showLinkStatus = 'All';

  if (isset($_GET['links_status_list'])) 

    $showLinkStatus = $_GET['links_status_list'];  

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	

<script language="JavaScript" type="text/javascript">

<!--



function CheckAll(form)

{

	for(var j = 0; j < document.links_status.length; j++)

  {

		if(document.links_status.elements[j].name == "ids[]")

    {

      if (document.links_status.elements[j].checked)

			document.links_status.elements[j].checked = false;

      else

			document.links_status.elements[j].checked = true;

		}

	}

}

//-->

</script> 

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

   <td width="100%" valign="top">

    <table border="3" width="100%" cellspacing="0" cellpadding="2">

      <tr>

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr><?php echo tep_draw_form('search', FILENAME_LINKS_STATUS, '', 'get') . tep_hide_session_id(); ?>

                <td class="pageHeading"><?php echo HEADING_TITLE_LINKS_STATUS; ?></td>

                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', 1, HEADING_IMAGE_HEIGHT); ?></td>

                <td class="pageHeading" align="right"><?php echo tep_draw_pull_down_menu('links_status_list', $linkShow, '',  'onChange="this.form.submit();"'); ?></td>

                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>

              </form></tr>

            </table></td>

          </tr>        

          <tr>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

          </tr>

          <tr>

            <td class="main"><?php echo TEXT_HEADING_SUB_TEXT; ?></td>

          </tr>          

        </table></td>

      </tr>         

      <?php echo tep_draw_form('links_status', FILENAME_LINKS_STATUS, tep_get_all_get_params(array('action')) . 'action=process', 'post', 'onSubmit="true;"') . tep_hide_session_id(); ?>

      <?php

      switch ($listing) {

          case "status":

          $order = "l.links_status";

          break;

          case "status-desc":

          $order = "l.links_status DESC";

          break;

          case "title":

          $order = "ld.links_title";

          break;

          case "title-desc":

          $order = "ld.links_title DESC";

          break;

          case "url":

          $order = "l.links_reciprocal_url";

          break;

          case "url-desc":

          $order = "l.links_reciprocal_url DESC";

          break;

          case "last_date":

          $order = "lc.date_last_checked";

          break;

          case "last_date-desc":

          $order = "lc.date_last_checked DESC";

          break;          

          default:

          $order = "l.links_id DESC";

      }      

      ?>    

      <tr>

       <td width="100%" valign="top"><table border="2" width="100%" cellspacing="0" cellpadding="1">

        <tr bgcolor="#c9c9c9">

         <th class="main" width="20"><?php echo TEXT_LINK_FOUND; ?></th>



         <th class="main" width="20"  align="left"><input type="checkbox" name="links_check_all" onClick="CheckAll(this.form);"></th>

         <th class="main" width="80">

          <?php echo (($listing=='status' or $listing=='status-desc') ? '<font color="FF0000"><b>' . TEXT_LINK_STATUS . '</b></font>' : '<b>'. TEXT_LINK_STATUS . '</b>'); ?><br>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=status'); ?>"><?php echo ($listing=='status' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=status-desc'); ?>"><?php echo ($listing=='status-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

         </th>         



         <th class="main"><?php echo TEXT_LINK_TITLE; ?>

          <?php echo (($listing=='title' or $listing=='title-desc') ? '<font color="FF0000"><b>' . TEXT_LINK_TITLE . '</b></font>' : '<b>'. TEXT_LINK_TITLE . '</b>'); ?><br>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=title'); ?>"><?php echo ($listing=='title' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=title-desc'); ?>"><?php echo ($listing=='title-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

         </th>

                

         <th class="main">

          <?php echo (($listing=='url' or $listing=='url-desc') ? '<font color="FF0000"><b>' . TEXT_LINK_URL . '</b></font>' : '<b>'. TEXT_LINK_URL . '</b>'); ?><br>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=url'); ?>"><?php echo ($listing=='url' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=url-desc'); ?>"><?php echo ($listing=='url-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

         </th>         

         

         <th class="main" width="90">

          <?php echo (($listing=='last_date' or $listing=='last_date-desc') ? '<font color="FF0000"><b>' . TEXT_LINK_LAST_DATE_CHECKED . '</b></font>' : '<b>'. TEXT_LINK_LAST_DATE_CHECKED . '</b>'); ?><br>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=last_date'); ?>"><?php echo ($listing=='last_date' ? '<font color="FF0000"><b>Asc</b></font>' : '<b>Asc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

          <a href="<?php echo tep_href_link(FILENAME_LINKS_STATUS, 'listing=last_date-desc'); ?>"><?php echo ($listing=='last_date-desc' ? '<font color="FF0000"><b>Desc</b></font>' : '<b>Desc</b>'); ?></a>&nbsp; <?php echo tep_hide_session_id(); ?>

         </th>         

        <tr> 

        <?php

        if (isset($_GET['search']) && tep_not_null($_GET['search'])) {

          $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));

          $where = " where l.links_status = '" . $showLinkStatus . "' and ld.links_title like '%" . $keywords . "%' or  l.links_url like '%" . $keywords . "%'" ;

        }

        else if ($showLinkStatus == 'All')

          $where = '';

        else

          $where = " where l.links_status = '" . $showLinkStatus . "'" . $search;



        $links_check_query_raw = "SELECT l.links_id, l.links_reciprocal_url, l.links_status, ld.links_title, lc.date_last_checked, lc.link_found, ls.links_status_name from " . TABLE_LINKS . " l LEFT JOIN " . TABLE_LINKS_DESCRIPTION . " ld on l.links_id = ld.links_id left join " . TABLE_LINKS_CHECK . " lc on l.links_id = lc.links_id left join " . TABLE_LINKS_STATUS . " ls on l.links_status = ls.links_status_id $where and ls.language_id = '" . $languages_id . "' order by " . $order;

        $links_split = new splitPageResults($_GET['page'], MAX_LINKS_DISPLAY, $links_check_query_raw, $links_query_numrows);

        $links_check_query = tep_db_query($links_check_query_raw);



        while ($links = tep_db_fetch_array($links_check_query)) 

        { 

          $img = ($links['link_found']) ? 'images/mark_check.jpg' : 'images/mark_x.jpg'; 

          $date = explode(" ",$links['date_last_checked']);

        ?>

        <tr>

         <td align="center"><img src="<?php echo $img; ?>" alt="" width="12" height="12"></td> 

         <td class="main" width="20" align="center" valign="middle"><input name="ids[]" type="checkbox" id="ids[]" value="links_status_checkbox<?php echo $links['links_id']; ?>" ></td>

         <td class="main" align="left"><?php echo  ' ' . $links['links_status_name']; ?></td>

         <td class="main"><?php echo $links['links_title']; ?></td>

         <td class="main"><?php echo '<a href="' . $links['links_reciprocal_url'] . '" target="_blank">' . $links['links_reciprocal_url'] . '</a>'; ?></td>

         <td class="main" align="center"><?php echo (tep_not_null($date[0]) ? $date[0] : '&nbsp;'); ?></td> 

        </tr>

        <?php } ?>      

       <table><td>

      </tr> 



      <tr><table border="0" width="100%" cellspacing="0" cellpadding="0">

  

      

      <?php if (tep_db_num_rows($links_check_query) > 0) {

       $links_statuses_query = tep_db_query("select links_status_name, links_status_id from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");

      ?> 

      <tr>

         <td valign="top"><table border="0" width="50%" cellspacing="0" cellpadding="0">

        <tr class="smallText" >         

           <td width="130"><?php echo TEXT_SET_TO; ?></td>

         <?php  while ($links_statuses = tep_db_fetch_array($links_statuses_query)) { 

         $checked = ($checkState == $links_statuses['links_status_id']) ? 'checked' : '';

         ?>         

           <td valign="top"><INPUT TYPE="radio" NAME="group1" VALUE="<?php echo $links_statuses['links_status_id']; ?>" <?php echo $checked; ?> ></td>

           <td valign="middle" width="50" ><?php echo $links_statuses['links_status_name']; ?></td>

         <?php } ?>

           <td valign="top"><INPUT TYPE="radio" NAME="group1" VALUE="delete_links" <?php echo $checked; ?> ></td>

           <td valign="middle" class="smallText" width="50"><?php echo TEXT_LINK_DELETE; ?></td>

        </tr>

        <tr class="smallText" >         

           <td width="130"><?php echo TEXT_NOTIFY; ?></td>

           <td><?php echo tep_draw_checkbox_field('notify_link_partner', '', false); ?> </td>

        </tr>           

       </table></td>

      </tr>  

        <?php } ?>



      <tr>

       <td width="100%"><?php echo tep_draw_separator('pixel_trans.png', '100%', '20'); ?></td>

      </tr>  

      

      <tr class="infoBoxContents">

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

              <td align="center" align="center"><?php echo tep_image_submit('button_update.png', IMAGE_BUTTON_UPDATE, 'name="update"'); ?></td>

          </tr>

        </table></td>

      </tr></form>      

        <tr>     

         <td aligh="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

           <td class="smallText"><?php echo $links_split->display_count($links_query_numrows, MAX_LINKS_DISPLAY, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>

           <td class="smallText" align="right"><?php echo $links_split->display_links($links_query_numrows, MAX_LINKS_DISPLAY, MAX_DISPLAY_PAGE_LINKS, $page, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?></td>

          </tr>

         </table></td>  

        </tr>

      

      </table>

      </tr>

      

  <!-- body_text_eof //-->

    </table>

   </td> 

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

