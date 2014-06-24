<?php

/*

  $Id: header_tags_fill_tags.php,v 1.0 2005/08/25

  Originally Created by: Jack York - http://www.CartStore.com

  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/

 

  require('includes/application_top.php'); 

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_HEADER_TAGS_CONTROLLER);

 

  /****************** READ IN FORM DATA ******************/

  $categories_fill = $_POST['group1'];

  $manufacturers_fill = $_POST['group2'];

  $products_fill = $_POST['group3'];

  $productsMetaDesc = $_POST['group4'];

  $productsMetaDescLength = $_POST['fillMetaDescrlength'];

 

  $checkedCats = array();

  $checkedManuf = array();

  $checkedProds = array();

  $checkedMetaDesc = array();

  

  $languages = tep_get_languages();

  $languages_array = array();

  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {

    $languages_array[] = array('id' => $languages[$i]['id'], // $i + 1, 

                               'text' => $languages[$i]['name']);

  }

  $langID = $languages_id; 

  $updateDP = false;

  $updateTextCat = '';

  $updateTextManuf = '';

  $updateTextProd = '';

    

  /****************** FILL THE CATEGORIES ******************/

   

  if (isset($categories_fill))

  {

    $langID = $_POST['fill_language'];

    

    if ($categories_fill == 'none') 

    {

       $checkedCats['none'] = 'Checked';

    }

    else

    { 

      $categories_tags_query = tep_db_query("select categories_name, categories_id, categories_htc_title_tag, categories_htc_desc_tag, categories_htc_keywords_tag, language_id from  " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . $langID . "'");

      while ($categories_tags = tep_db_fetch_array($categories_tags_query))

      {

        $updateDP = false;

        

        if ($categories_fill == 'empty')

        {

           if (! tep_not_null($categories_tags['categories_htc_title_tag']))

           {

             $updateDB = true;

             $updateTextCat = 'Empty Category tags have been filled.';

           }  

           $checkedCats['empty'] = 'Checked';

        }

        else if ($categories_fill == 'full')

        {

           $updateDB = true;

           $updateTextCat = 'All Category tags have been filled.';

           $checkedCats['full'] = 'Checked';

        }

        else      //assume clear all

        {

           tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_htc_title_tag='', categories_htc_desc_tag = '', categories_htc_keywords_tag = '' where categories_id = '" . $categories_tags['categories_id']."' and language_id  = '" . $langID . "'");

           $updateTextCat = 'All Category tags have been cleared.';

           $checkedCats['clear'] = 'Checked';

        }      

             

        if ($updateDB)

          tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_htc_title_tag='".addslashes($categories_tags['categories_name'])."', categories_htc_desc_tag = '". addslashes($categories_tags['categories_name'])."', categories_htc_keywords_tag = '". addslashes($categories_tags['categories_name']) . "' where categories_id = '" . $categories_tags['categories_id']."' and language_id  = '" . $langID . "'");

      }

    }

  }

  else

    $checkedCats['none'] = 'Checked';

   

  /****************** FILL THE MANUFACTURERS ******************/

   

  if (isset($manufacturers_fill))

  {

    $langID = $_POST['fill_language'];

    

    if ($manufacturers_fill == 'none') 

    {

       $checkedManuf['none'] = 'Checked';

    }

    else

    { 

      $manufacturers_tags_query = tep_db_query("select m.manufacturers_name, m.manufacturers_id, mi.languages_id, mi.manufacturers_htc_title_tag, mi.manufacturers_htc_desc_tag, mi.manufacturers_htc_keywords_tag from " . TABLE_MANUFACTURERS . " m, " . TABLE_MANUFACTURERS_INFO . " mi where mi.languages_id = '" . $langID . "'");

      while ($manufacturers_tags = tep_db_fetch_array($manufacturers_tags_query))

      {

        $updateDP = false;

        

        if ($manufacturers_fill == 'empty')

        {

           if (! tep_not_null($manufacturers_tags['manufacturers_htc_title_tag']))

           {

             $updateDB = true;

             $updateTextManuf = 'Empty Manufacturers tags have been filled.';

           }  

           $checkedManuf['empty'] = 'Checked';

        }

        else if ($manufacturers_fill == 'full')

        {

           $updateDB = true;

           $updateTextManuf = 'All Manufacturers tags have been filled.';

           $checkedManuf['full'] = 'Checked';

        }

        else      //assume clear all

        {

           tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set manufacturers_htc_title_tag='', manufacturers_htc_desc_tag = '', manufacturers_htc_keywords_tag = '' where manufacturers_id = '" . $manufacturers_tags['manufacturers_id']."' and languages_id  = '" . $langID . "'");

           $updateTextManuf = 'All Manufacturers tags have been cleared.';

           $checkedManuf['clear'] = 'Checked';

        }      

             

        if ($updateDB)

          tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set manufacturers_htc_title_tag='".addslashes($manufacturers_tags['manufacturers_name'])."', manufacturers_htc_desc_tag = '". addslashes($manufacturers_tags['manufacturers_name'])."', manufacturers_htc_keywords_tag = '". addslashes($manufacturers_tags['manufacturers_name']) . "' where manufacturers_id = '" . $manufacturers_tags['manufacturers_id']."' and languages_id  = '" . $langID . "'");

      }

    }

  }

  else

    $checkedManuf['none'] = 'Checked';

       

  /****************** FILL THE PRODUCTS ******************/  

  

  if (isset($products_fill))

  {

    $langID = $_POST['fill_language'];

    

    if ($products_fill == 'none') 

    {

       $checkedProds['none'] = 'Checked';

    }

    else

    { 

      $products_tags_query = tep_db_query("select products_name, products_description, products_id, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, language_id from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . $langID . "'");

      while ($products_tags = tep_db_fetch_array($products_tags_query))

      {

        $updateDP = false;

        

        if ($products_fill == 'empty')

        {

          if (! tep_not_null($products_tags['products_head_title_tag']))

          {

            $updateDB = true;

            $updateTextProd = 'Empty Product tags have been filled.';

          }  

          $checkedProds['empty'] = 'Checked';

        }

        else if ($products_fill == 'full')

        {

          $updateDB = true;

          $updateTextProd = 'All Product tags have been filled.';

          $checkedProds['full'] = 'Checked';

        }

        else      //assume clear all

        {

          tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_head_title_tag='', products_head_desc_tag = '', products_head_keywords_tag =  '' where products_id = '" . $products_tags['products_id'] . "' and language_id='". $langID ."'");

          $updateTextProd = 'All Product tags have been cleared.';

          $checkedProds['clear'] = 'Checked';

        }

               

        if ($updateDB)

        {

          if ($productsMetaDesc == 'fillMetaDesc_yes')          //fill the description with all or part of the 

          {                                                     //product description

            if (! empty($products_tags['products_description']))

            {

              if (isset($productsMetaDescLength) && (int)$productsMetaDescLength > 3 && (int)$productsMetaDescLength < strlen($products_tags['products_description']))

                $desc = substr($products_tags['products_description'], 0, (int)$productsMetaDescLength);

              else if ((int)$productsMetaDescLength <= 3)       //length not entered or too small    

                $desc = $products_tags['products_description']; //so use the whole description

            }   

            else

              $desc = $products_tags['products_name'];  



            $checkedMetaDesc['no'] = '';

            $checkedMetaDesc['yes'] = 'Checked';

          }  

          else

          {        

            $desc = $products_tags['products_name'];           

            $checkedMetaDesc['no'] = 'Checked';

            $checkedMetaDesc['yes'] = '';

          }  



          tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_head_title_tag='".addslashes($products_tags['products_name'])."', products_head_desc_tag = '". addslashes(strip_tags($desc))."', products_head_keywords_tag =  '" . addslashes($products_tags['products_name']) . "' where products_id = '" . $products_tags['products_id'] . "' and language_id='". $langID ."'");

        } 

      }  

    }

  }

  else

  { 

    $checkedProds['none'] = 'Checked';

    $checkedMetaDesc['no'] = 'Checked';

    $checkedMetaDesc['yes'] = '';

  }

 

?>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
 <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a><?php echo HEADING_TITLE_FILL_TAGS; ?>
</h1></div>
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
  <?php echo TEXT_FILL_TAGS; ?></p>
<?php echo tep_draw_form('header_tags', FILENAME_HEADER_TAGS_FILL_TAGS, '', 'post') . tep_draw_hidden_field('action', 'process'); ?>

<div class="form-group"><label>
Language </label>
<?php echo tep_draw_pull_down_menu('fill_language', $languages_array, $langID);?>

</div>

<b>Fill products meta description with Products Description?</b>

<div class="radio">
    
<INPUT TYPE="radio" NAME="group4" VALUE="fillMetaDesc_yes"<?php echo $checkedMetaDesc['yes']; ?>> Yes
</div>
<div class="radio">
<INPUT TYPE="radio" NAME="group4" VALUE="fillmetaDesc_no"<?php echo $checkedMetaDesc['no']; ?>> No

</div>
  <?php echo '  '. tep_draw_input_field('fillMetaDescrlength', '', 'maxlength="255", size="5" placeholder="Limit to characters ie enter 250 here to limit meta desc character count"', false) . ''; ?> 

     <hr>



      



 


  



        
<div class="form-group"><label>
<?php echo HEADING_TITLE_CONTROLLER_CATEGORIES; ?></label>
<div class="radio"><INPUT TYPE="radio" NAME="group1" VALUE="none" <?php echo $checkedCats['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group1" VALUE="empty"<?php echo $checkedCats['empty']; ?> > <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group1" VALUE="full" <?php echo $checkedCats['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group1" VALUE="clear" <?php echo $checkedCats['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></div>
</div>

<div class="form-group"><label>
 <?php echo HEADING_TITLE_CONTROLLER_MANUFACTURERS; ?> </label>
<div class="radio"><INPUT TYPE="radio" NAME="group2" VALUE="none" <?php echo $checkedManuf['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group2" VALUE="empty" <?php echo $checkedManuf['empty']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group2" VALUE="full" <?php echo $checkedManuf['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group2" VALUE="clear" <?php echo $checkedManuf['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></div>
</div>

<div class="form-group"><label>
 <?php echo HEADING_TITLE_CONTROLLER_PRODUCTS; ?></label>
<div class="radio"><INPUT TYPE="radio" NAME="group3" VALUE="none" <?php echo $checkedProds['none']; ?>> <?php echo HEADING_TITLE_CONTROLLER_SKIPALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group3" VALUE="empty" <?php echo $checkedProds['empty']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLONLY; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group3" VALUE="full" <?php echo $checkedProds['full']; ?>> <?php echo HEADING_TITLE_CONTROLLER_FILLALL; ?></div>
<div class="radio"><INPUT TYPE="radio" NAME="group3" VALUE="clear" <?php echo $checkedProds['clear']; ?>> <?php echo HEADING_TITLE_CONTROLLER_CLEARALL; ?></div>
</div>



<P>

<?php echo (tep_image_submit('button_update.png', IMAGE_UPDATE) ) . ' <a href="' . tep_href_link(FILENAME_HEADER_TAGS_ENGLISH, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?>
</P>

         <?php if (tep_not_null($updateTextCat)) { ?>

<?php echo $updateTextCat; ?>


          <?php }  

           if (tep_not_null($updateTextManuf)) { ?>

       
<?php echo $updateTextManuf; ?>


      


         <?php } 

           if (tep_not_null($updateTextProd)) { ?>

      
<?php echo $updateTextProd; ?>


         <?php } ?> 

       

      </form>

  

         






<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>



<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

