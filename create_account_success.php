<?php

/*

  $Id: create_account_success.php,v 1.30 2003/06/05 23:27:00 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);



  $breadcrumb->add(NAVBAR_TITLE_1);

  $breadcrumb->add(NAVBAR_TITLE_2);



  if (sizeof($navigation->snapshot) > 0) {

    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);

    $navigation->clear_snapshot();

  } else {

    $origin_href = tep_href_link(FILENAME_DEFAULT);

  }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

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

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td class="pageHeading"><h1><?php echo HEADING_TITLE; ?></h1></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_ACCOUNT_CREATED; ?></td>

              </tr><!-- Points/Rewards Module V2.00 bof-->

<?php if (NEW_SIGNUP_POINT_AMOUNT > 0) {

?>

              <tr>

                <td class="main"><?php echo sprintf(TEXT_WELCOME_POINTS_TITLE, number_format(NEW_SIGNUP_POINT_AMOUNT,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT))); ?>.</td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_WELCOME_POINTS_LINK; ?></td>

              </tr>

<?php

   }

?>               

<!-- Points/Rewards Module V2.00 eof-->

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td align="right"><?php echo '<a class="button" href="' . $origin_href . '">Continue</a>'; ?></td>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

    </table>
		<!-- body_text_eof //-->

			<!-- right_navigation //-->
<?php
require (DIR_WS_INCLUDES . 'column_right.php');
?>
<!-- right_navigation_eof //-->
		
<!-- body_eof //-->
<!-- footer //-->
<?php
require (DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
