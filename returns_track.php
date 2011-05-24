<?php

/*

$id author Puddled Internet - http://www.puddled.co.uk

  email support@puddled.co.uk

   osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2002 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');

      if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }



  if (!$_GET['action']){

      $_GET['action'] = 'returns_track';

  }

  if ($_GET['action']) {

    switch ($_GET['action']) {

    case 'returns_show':



       // first carry out a query on the database to see if there are any matching tickets

       $database_returns_query = tep_db_query("SELECT returns_id, returns_status FROM " . TABLE_RETURNS . " where customers_id = '" . $customer_id . "' and rma_value = '" . $_POST['rma'] . "' or rma_value = '" . $_GET['rma'] . "'");

       if (!tep_db_num_rows($database_returns_query)) {

           tep_redirect(tep_href_link('returns_track.php?error=yes'));

       } else {

          $returns_query = tep_db_fetch_array($database_returns_query);

          $returns_id = $returns_query['returns_id'];

          $returns_status_id = $returns_query['returns_status'];

          $returns_status_query = tep_db_query("SELECT returns_status_name FROM " . TABLE_RETURNS_STATUS . " where returns_status_id = " . $returns_status_id . " and language_id = '" . (int)$languages_id . "'");

          $returns_status_array = tep_db_fetch_array($returns_status_query);

          $returns_status = $returns_status_array['returns_status_name'];

          $returned_products_query = tep_db_query("SELECT * FROM " . TABLE_RETURNS_PRODUCTS_DATA . " op, " . TABLE_RETURNS . " o where o.returns_id = op.returns_id and op.returns_id = '" . $returns_id . "'");

          $returned_products = tep_db_fetch_array($returned_products_query);



              require(DIR_WS_CLASSES . 'order.php');

           $order = new order($returned_products['order_id']);



       }



    break;



}

}



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RETURNS_TRACK);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_RETURNS_TRACK, '', 'NONSSL'));

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

<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td>

            <h1><?php if ($_GET['action'] == 'returns_show') { echo TEXT_SUPPORT_STATUS . ': ' . $returns_status; } else { echo HEADING_TITLE; } ; ?></h1>

            



          <?php

      if ($_GET['action'] == 'returns_show') {

          include(DIR_WS_MODULES . 'returns_track.php');

     // }



      ?>

<?php

 //

?>

		



<?php

//}

?>



	 

      <!-- if RMA number doesn't exist, show error message //-->

    <?php

} else {

?>

     



                 <?php

                  if (isset($error)=='yes') {

                   $error_message = '' . TEXT_TRACK_DETAILS_1 . '<br>



                                     ';

                    new infoBox(array(array('text' => $error_message)));

                  // }

                    echo '<br /><br />';

              }

                    $returns = '<form action="' . $PHP_SELF . '?action=returns_show" method=post>

                             <br>

' . TEXT_TRACK_DETAILS_2 . '<br>

' . TEXT_YOUR_RMA_NUMBER . '<input type="text" name="rma" value="" size="20" class="inputbox"><br>

<input class="button" type=submit name="submit" value="' . TEXT_FIND_RETURN . '">

                             </form>





                             ';







                 new infoBox(array(array('text' => $returns)));







                 ?>



             

            

          

<?php

}



?>





            </td>

          </tr>

        </table>

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

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

