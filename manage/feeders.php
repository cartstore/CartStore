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

     // require(DIR_WS_FUNCTIONS . 'version_checker.php');

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

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<link href="//codeorigin.jquery.com/ui/1.9.2/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" />
 	 	

<script language="javascript" src="includes/general.js"></script>








<!-- body_text //-->

<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>

Google&#153; Merchant Center </h1></div>
       <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-file-text fa-5x pull-left"></i>
Help for this section is not yet available.                          </div>
                      </div>
                  </div>   
              </div>    
<h3>You can also run this script automaticly from cron with:</h3> 

<h4><?php echo 'php -q '. DIR_FS_ADMIN . 'googlefeeder.php'; ?></h4> 

 

<p>You will need a google base account and you will need this script configured for ftp use as well as other settings. CartStore provides commercial configuration assistance for this google base script.

 

 </p>     

         

         <?php  

         if (function_exists('AnnounceVersion')) {

            if (false) { //requires database change so skip 

         ?>

            <?php // echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?> 

         <?php } else if (tep_not_null($versionStatus)) { 

           echo ' ';

         } else {

            echo tep_draw_form('version_check', 'feeders.php', '', 'post') . tep_draw_hidden_field('action', 'getversion'); 

         ?>

 

           </form>

         <?php } } else { ?>
 

         <?php } ?>         

       
      <p>   <?php echo '<a class="btn btn-default" href="' . tep_href_link('googlefeeder.php') . '" target=_blank">Create Google Feed File</a>'; ?></p>
  

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

