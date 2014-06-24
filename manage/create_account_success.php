<?php
/*
  $Id: create_account_success.php,v 1 2003/08/24 23:21:26 frankl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible

  THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 0.5
  Customer Entry through Admin
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);


?>




<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


  
<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>
<p class="lead">
<?php echo TEXT_ACCOUNT_CREATED; ?></p>
<p>
<?php echo '<a class="btn btn-default" href="' . $origin_href . '">' . IMAGE_CONTINUE . '</a>'; ?></p>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>