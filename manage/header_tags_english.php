<?php

/*

  $Id: header_tags_controller.php,v 1.0 2005/04/08 22:50:52 hpdl Exp $

  Originally Created by: Jack York - http://www.CartStore.com

  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/

 

  require('includes/application_top.php');

  require('includes/functions/header_tags.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_HEADER_TAGS_CONTROLLER);

  $filename = DIR_FS_CATALOG. 'templates/includes/languages/' . $language . '/header_tags.php';


  $formActive = false;

  

  /****************** READ IN FORM DATA ******************/

  $action = (isset($_POST['action']) ? $_POST['action'] : '');

  

  if (tep_not_null($action)) 

  {

      $main['title'] = $_POST['main_title'];  //read in the knowns

      $main['desc'] = $_POST['main_desc'];

      $main['keyword'] = $_POST['main_keyword'];



      $formActive = true;

      $args_new = array();

      $c = 0;

      $pageCount = TotalPages($filename);

      for ($t = 0, $c = 0; $t < $pageCount; ++$t, $c += 3) //read in the unknowns

      {

         $args_new['title'][$t] = $_POST[$c];

         $args_new['desc'][$t] = $_POST[$c+1];

         $args_new['keyword'][$t] = $_POST[$c+2];

        

         $boxID = sprintf("HTTA_%d", $t); 

         $args_new['HTTA'][$t] = $_POST[$boxID];

         $boxID = sprintf("HTDA_%d", $t); 

         $args_new['HTDA'][$t] = $_POST[$boxID];

         $boxID = sprintf("HTKA_%d", $t); 

         $args_new['HTKA'][$t] = $_POST[$boxID];

         $boxID = sprintf("HTCA_%d", $t); 

         $args_new['HTCA'][$t] = $_POST[$boxID];

      }   

  }



  /***************** READ IN DISK FILE ******************/

  $main_title = '';

  $main_desc = '';

  $main_key = '';

  $sections = array();      //used for unknown titles

  $args = array();          //used for unknown titles

  $ctr = 0;                 //used for unknown titles

  $findTitles = false;      //used for unknown titles

  $fp = file($filename);  



  for ($idx = 0; $idx < count($fp); ++$idx)

  { 

      if (strpos($fp[$idx], "define('HEAD_TITLE_TAG_ALL'") !== FALSE)

      {

//      echo 'SEND TITLE '.$main_title.' '. ' - '.$main['title'].' - '.$formActive.'<br>';

          $main_title = GetMainArgument($fp[$idx], $main['title'], $formActive);

      } 

      else if (strpos($fp[$idx], "define('HEAD_DESC_TAG_ALL'") !== FALSE)

      {

     // echo 'SEND DESC '.$main['desc']. ' '.$formActive.'<br>';

          $main_desc = GetMainArgument($fp[$idx], $main['desc'], $formActive);

      } 

      else if (strpos($fp[$idx], "define('HEAD_KEY_TAG_ALL'") !== FALSE)

      { 

          $main_key = GetMainArgument($fp[$idx], $main['keyword'], $formActive);

          $findTitles = true;  //enable next section            

      } 

      else if ($findTitles)

      {

          if (($pos = strpos($fp[$idx], '.php')) !== FALSE) //get the section titles

          {

              $sections['titles'][$ctr] = GetSectionName($fp[$idx]);   

              $ctr++; 

          }

          else                                   //get the rest of the items in this section

          {

              if (! IsComment($fp[$idx])) // && tep_not_null($fp[$idx]))

              {

                  $c = $ctr - 1;

                  if (IsTitleSwitch($fp[$idx]))

                  {

                     if ($formActive)

                     {

                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTTA'][$c]);

                     }                      

                     $args['title_switch'][$c] = GetSwitchSetting($fp[$idx]);

                     $args['title_switch_name'][$c] = sprintf("HTTA_%d",$c);                     

                  }

                  else if (IsDescriptionSwitch($fp[$idx]))

                  {

                     if ($formActive)

                     {

                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTDA'][$c]);

                     } 

                     $args['desc_switch'][$c] = GetSwitchSetting($fp[$idx]);

                     $args['desc_switch_name'][$c] = sprintf("HTDA_%d",$c);  

                  }

                  if (IsKeywordSwitch($fp[$idx]))

                  {

                     if ($formActive)

                     {

                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTKA'][$c]);

                     }   

                     $args['keyword_switch'][$c] = GetSwitchSetting($fp[$idx]);

                     $args['keyword_switch_name'][$c] = sprintf("HTKA_%d",$c);

                  }

                  else if (IsCatSwitch($fp[$idx]))

                  {

                     if ($formActive)

                     {

                       $fp[$idx] = ChangeSwitch($fp[$idx], $args_new['HTCA'][$c]); 

                     }  

                     $args['cat_switch'][$c] = GetSwitchSetting($fp[$idx]);

                     $args['cat_switch_name'][$c] = sprintf("HTCA_%d",$c);

                  }

                  else if (IsTitleTag($fp[$idx]))

                  {

                     $args['title'][$c] = GetArgument($fp[$idx], $args_new['title'][$c], $formActive);

                  } 

                  else if (IsDescriptionTag($fp[$idx])) 

                  {

                     $args['desc'][$c] = GetArgument($fp[$idx], $args_new['desc'][$c], $formActive);                   

                  }

                  else if (IsKeywordTag($fp[$idx])) 

                  {

                    $args['keyword'][$c] = GetArgument($fp[$idx], $args_new['keyword'][$c], $formActive);

                  }                                   

              }

          }

      }

  }



  /***************** WRITE THE FILE ******************/

  if ($formActive)

  {      

     WriteHeaderTagsFile($filename, $fp);  

  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo TITLE; ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

   

	 	



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

      <td class="HTC_Head"><?php echo HEADING_TITLE_ENGLISH; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

      </tr>

     <tr>

      <td class="HTC_subHead"><?php echo TEXT_ENGLISH_TAGS; ?></td>

     </tr>

     <tr>

      <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

     </tr>

     

     <!-- Begin of Header Tags -->

     <tr>

      <td align="right"><?php echo tep_draw_form('header_tags', FILENAME_HEADER_TAGS_ENGLISH, '', 'post') . tep_draw_hidden_field('action', 'process'); ?></td>

       <tr>

        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

     

         <tr>

          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_TITLE; ?></td>

          <td class="smallText" ><?php echo tep_draw_input_field('main_title', tep_not_null($main_title) ? $main_title : '', 'maxlength="255", size="60"', false); ?> </td>

         <tr> 

         <tr>

          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION; ?></td>

          <td class="smallText" ><?php echo tep_draw_input_field('main_desc', tep_not_null($main_desc) ? $main_desc : '', 'maxlength="255", size="60"', false); ?> </td>

         <tr> 

         <tr>

          <td class="smallText" width="20%" style="font-weight: bold;"><?php echo HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS; ?></td>

          <td class="smallText" ><?php echo tep_draw_input_field('main_keyword', tep_not_null($main_key) ? $main_key : '', 'maxlength="255", size="60"', false); ?> </td>

         <tr> 

         

         <?php for ($i = 0, $id = 0; $i < count($sections['titles']); ++$i, $id += 3) { ?>

         <tr>

          <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

         </tr>         

         <tr>

          <td colspan="3" ><table border="0" width="100%">

         <tr>

          <td colspan="3" class="smallText" width="20%" style="font-weight: bold; color: <?php echo getcolor($sections['titles'][$i]); ?>;"><?php echo $sections['titles'][$i]; ?></td>

          <td class="smallText">HTTA: </td>

          <td align="left"><?php echo tep_draw_checkbox_field($args['title_switch_name'][$i], '', $args['title_switch'][$i], ''); ?> </td>

          <td class="smallText">HTDA: </td>

          <td align="left"><?php echo tep_draw_checkbox_field($args['desc_switch_name'][$i], '', $args['desc_switch'][$i], ''); ?> </td>

          <td class="smallText">HTKA: </td>

          <td align="left"><?php echo tep_draw_checkbox_field($args['keyword_switch_name'][$i], '', $args['keyword_switch'][$i], ''); ?> </td>

          <td class="smallText">HTCA: </td>

          <td align="left"><?php echo tep_draw_checkbox_field($args['cat_switch_name'][$i], '', $args['cat_switch'][$i], ''); ?> </td>

         

          <td width="50%" class="smallText"> <script>document.writeln('<a style="cursor:hand" onclick="javascript:popup=window.open('

                                           + '\'<?php echo tep_href_link('header_tags_popup_help.php'); ?>\',\'popup\','

                                           + '\'scrollbars,resizable,width=520,height=550,left=50,top=50\'); popup.focus(); return false;">'

                                           + '<font color="red"><u><?php echo HEADING_TITLE_CONTROLLER_EXPLAIN; ?></u></font></a>');

         </script> </td>

     

         </tr>

          </table></td>

         </tr>

         

         <tr>

          <td colspan="3" ><table border="0" width="100%">

           <tr>

            <td width="2%">&nbsp;</td>

            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_TITLE; ?></td>

            <td class="smallText" ><?php echo tep_draw_input_field($id, $args['title'][$i], 'maxlength="255", size="60"', false, 300); ?> </td>

           </tr>

           <tr>

            <td width="2%">&nbsp;</td>

            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_DESCRIPTION; ?></td>

            <td class="smallText" ><?php echo tep_draw_input_field($id+1, $args['desc'][$i], 'maxlength="255", size="60"', false); ?> </td>

           </tr>

           <tr>

            <td width="2%">&nbsp;</td>

            <td class="smallText" width="12%"><?php echo HEADING_TITLE_CONTROLLER_KEYWORDS; ?></td>

            <td class="smallText" ><?php echo tep_draw_input_field($id+2, $args['keyword'][$i], 'maxlength="255", size="60"', false); ?> </td>

           </tr>

          </table></td>

         </tr>

         <?php } ?> 

        </table>

        </td>

       </tr>  

       <tr>

        <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

       </tr>

       <tr> 

        <td align="center"><?php echo (tep_image_submit('button_update.png', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_HEADER_TAGS_ENGLISH, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?></td>

       </tr>

      </form>

      </td>

     </tr>

     <!-- end of Header Tags -->



         

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

