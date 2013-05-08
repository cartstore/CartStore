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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title><?php echo TITLE ?></title>

<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />





<?php require('includes/form_check.js.php'); ?>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

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

    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>

          </tr>

        </table></td>

      </tr>

<?php

  if (sizeof($navigation->snapshot) > 0) {

?>

      <tr>

        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td>

<?php

  //$email_address = tep_db_prepare_input($_GET['email_address']);

  $account['entry_country_id'] = STORE_COUNTRY;

  $account['entry_zone_id'] = STORE_ZONE;



  require(DIR_WS_MODULES . 'account_details.php');

?>

        </td>

      </tr>

      <tr>

        <td align="right" class="main"><br><?php echo tep_image_submit('button_confirm.png', IMAGE_BUTTON_CONTINUE); ?></td>

      </tr>

    </table></form></td>

<!-- body_text_eof //-->

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

    </table></td>

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php

    require(DIR_WS_INCLUDES . 'footer.php');

?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>