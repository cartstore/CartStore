<?php

/*

  $Id: create_account.php,v 1 2003/08/24 23:21:27 frankl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible



 THIS IS BETA - Use at your own risk!

  Step-By-Step Manual Order Entry Verion 0.5

  Customer Entry through Admin

*/



  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);



?>


<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<?php require('includes/form_check.js.php'); ?>


 


<form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();">
<input type="hidden" name="action" value="process">


<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a><?php echo HEADING_TITLE; ?></h1></div>

       <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-user fa-5x pull-left"></i>
 On this screen you can create a new customer account.                         </div>
                      </div>
                  </div>   
              </div>    


<?php

  if (sizeof($navigation->snapshot) > 0) {

?>

    <?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?> 

<?php

  }

?>

     

<?php

  //$email_address = tep_db_prepare_input($_GET['email_address']);

  $account['entry_country_id'] = STORE_COUNTRY;

  $account['entry_zone_id'] = STORE_ZONE;



  require(DIR_WS_MODULES . 'account_details.php');

?>

      <p>
<?php echo tep_image_submit('button_confirm.png', IMAGE_BUTTON_CONTINUE); ?></p>
</form>





<?php

    require(DIR_WS_INCLUDES . 'footer.php');

?>



<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>