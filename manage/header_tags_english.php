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


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>

<?php echo HEADING_TITLE_ENGLISH; ?></h1></div>
           <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-question-circle fa-5x pull-left"></i>
Help for this section is not yet available.                          </div>
                      </div>
                  </div>   
              </div> 
<p>
<?php echo TEXT_ENGLISH_TAGS; ?></p>


<?php echo tep_draw_form('header_tags', FILENAME_HEADER_TAGS_ENGLISH, '', 'post') . tep_draw_hidden_field('action', 'process'); ?>


<div class="form-group"><label>
<?php echo HEADING_TITLE_CONTROLLER_DEFAULT_TITLE; ?></label>


<?php echo tep_draw_input_field('main_title', tep_not_null($main_title) ? $main_title : '', 'maxlength="255", size="60"', false); ?> 
</div>


<div class="form-group"><label>
<?php echo HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION; ?></label>


<?php echo tep_draw_input_field('main_desc', tep_not_null($main_desc) ? $main_desc : '', 'maxlength="255", size="60"', false); ?>
</div>


<div class="form-group"><label>
<?php echo HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS; ?></label>


<?php echo tep_draw_input_field('main_keyword', tep_not_null($main_key) ? $main_key : '', 'maxlength="255", size="60"', false); ?>
</div>


         

         <?php for ($i = 0, $id = 0; $i < count($sections['titles']); ++$i, $id += 3) { ?>
 
 <table class="table">

         <tr>

          <td  class="smallText"><h3><?php echo $sections['titles'][$i]; ?></h3></td>

          <td class="smallText">HTTA: <?php echo tep_draw_checkbox_field($args['title_switch_name'][$i], '', $args['title_switch'][$i], ''); ?> </td>

          <td class="smallText">HTDA: <?php echo tep_draw_checkbox_field($args['desc_switch_name'][$i], '', $args['desc_switch'][$i], ''); ?> </td>

          <td class="smallText">HTKA: <?php echo tep_draw_checkbox_field($args['keyword_switch_name'][$i], '', $args['keyword_switch'][$i], ''); ?> </td>

          <td class="smallText">HTCA: <?php echo tep_draw_checkbox_field($args['cat_switch_name'][$i], '', $args['cat_switch'][$i], ''); ?> </td>

         

          <td> <script>document.writeln('<a style="cursor:hand" onclick="javascript:popup=window.open('

                                           + '\'<?php echo tep_href_link('header_tags_popup_help.php'); ?>\',\'popup\','

                                           + '\'scrollbars,resizable,width=520,height=550,left=50,top=50\'); popup.focus(); return false;">'

                                           + '<font color="red"><u><?php echo HEADING_TITLE_CONTROLLER_EXPLAIN; ?></u></font></a>');

         </script> </td>

     

         </tr>

         

         

         <tr>

      



       

            <td colspan="6">
            
            <div class="form-group"><label><?php echo HEADING_TITLE_CONTROLLER_TITLE; ?> </label>
 <?php echo tep_draw_input_field($id, $args['title'][$i], 'maxlength="255", size="60"', false, 300); ?> </div>

<div class="form-group"><label><?php echo HEADING_TITLE_CONTROLLER_DESCRIPTION; ?></label>
<?php echo tep_draw_input_field($id+1, $args['desc'][$i], 'maxlength="255", size="60"', false); ?> </div>

<div class="form-group"><label><?php echo HEADING_TITLE_CONTROLLER_KEYWORDS; ?></label>
<?php echo tep_draw_input_field($id+2, $args['keyword'][$i], 'maxlength="255", size="60"', false); ?> </div>

</td>

           </tr>

        

         <?php } ?> 

        </table>

   <p>
<?php echo (tep_image_submit('button_update.png', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_HEADER_TAGS_ENGLISH, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?>
</p>
      </form>




<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

