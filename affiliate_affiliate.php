<?php

/*

  $Id: affiliate_affiliate.php,v 2.00 2003/10/12



 



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {

    $affiliate_username = tep_db_prepare_input($_POST['affiliate_username']);

    $affiliate_password = tep_db_prepare_input($_POST['affiliate_password']);



// Check if username exists

    $check_affiliate_query = tep_db_query("select affiliate_id, affiliate_firstname, affiliate_password, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($affiliate_username) . "'");

    if (!tep_db_num_rows($check_affiliate_query)) {

      $_GET['login'] = 'fail';

    } else {

      $check_affiliate = tep_db_fetch_array($check_affiliate_query);

// Check that password is good

      if (!tep_validate_password($affiliate_password, $check_affiliate['affiliate_password'])) {

        $_GET['login'] = 'fail';

      } else {

        $affiliate_id = $check_affiliate['affiliate_id'];

        tep_session_register('affiliate_id');



        $date_now = date('Ymd');



        tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_date_of_last_logon = now(), affiliate_number_of_logons = affiliate_number_of_logons + 1 where affiliate_id = '" . $affiliate_id . "'");



        tep_redirect(tep_href_link(FILENAME_AFFILIATE_SUMMARY,'','SSL'));

      }

    }

  }



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE);



  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));

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

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td rowspan="2" class="pageHeading" align="right">&nbsp;</td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {

    $info_message = TEXT_LOGIN_ERROR;

  }



  if (isset($info_message)) {

?>

      <tr>

        <td class="smallText"><?php echo $info_message; ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><?php echo tep_draw_form('login', tep_href_link(FILENAME_AFFILIATE, 'action=process', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main" width="50%" valign="top"><b><?php echo HEADING_NEW_AFFILIATE; ?></b></td>

            <td class="main" width="50%" valign="top"><b><?php echo HEADING_RETURNING_AFFILIATE; ?></b></td>

          </tr>

          <tr>

            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">

              <tr>

                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxContents">

                  <tr>

                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                  <tr>

                    <td class="main" valign="top"><?php echo TEXT_NEW_AFFILIATE . '<br><br>' . TEXT_NEW_AFFILIATE_INTRODUCTION; ?></td>

                  </tr>

                  <tr>

                    <td class="smallText" colspan="2"><?php echo '<a  href="' . tep_href_link(FILENAME_AFFILIATE_TERMS, '', 'SSL') . '">' . TEXT_NEW_AFFILIATE_TERMS . '</a>'; ?></td>

                  </tr>

                  <tr>

                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                </table></td>

              </tr>

            </table></td>

            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">

              <tr>

                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxContents">

                  <tr>

                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                  <tr>

                    <td class="main" colspan="2"><?php echo TEXT_RETURNING_AFFILIATE; ?></td>

                  </tr>

                  <tr>

                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                  <tr>

                    <td class="main"><b><?php echo TEXT_AFFILIATE_ID; ?></b></td>

                    <td class="main"><?php echo tep_draw_input_field('affiliate_username'); ?></td>

                  </tr>

                  <tr>

                    <td class="main"><b><?php echo TEXT_AFFILIATE_PASSWORD; ?></b></td>

                    <td class="main"><?php echo tep_draw_password_field('affiliate_password'); ?></td>

                  </tr>

                  <tr>

                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                  <tr>

                    <td class="smallText" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_AFFILIATE_PASSWORD_FORGOTTEN . '</a>'; ?></td>

                  </tr>

                  <tr>

                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                  </tr>

                </table></td>

              </tr>

            </table></td>

          </tr>

          <tr>

            <td width="50%" align="right" valign="top"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL') . '">Continue</a>'; ?></td>

            <td width="50%" align="right" valign="top"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>

          </tr>

        </table></form></td>

      </tr>

    </table></td>

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

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>