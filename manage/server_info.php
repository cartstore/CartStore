<?php

/*

  $Id: server_info.php,v 1.6 2003/06/30 13:13:49 dgw_ Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/



  require('includes/application_top.php');



  $system = tep_get_system_information();

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

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>

            <td class="pageHeading2" align="right"></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td><table border="0" cellspacing="0" cellpadding="3">

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_HOST; ?></b></td>

                <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>

                <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>

                <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE; ?></b></td>

                <td class="smallText"><?php echo $system['db_version']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>

                <td class="smallText"><?php echo $system['date']; ?></td>

                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>

                <td class="smallText"><?php echo $system['db_date']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>

                <td colspan="3" class="smallText">Server side execution disabled for security. -CartStore</td>

              </tr>

              <tr>

                <td colspan="4"><?php echo tep_draw_separator('pixel_trans.png', '1', '5'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>

                <td colspan="3" class="smallText"><?php echo $system['http_server']; ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>

                <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>

      </tr>

      <tr>

        <td>

<?php

  if (function_exists('ob_start')) {

?>



<?php

    ob_start();

    phpinfo();

    $phpinfo = ob_get_contents();

    ob_end_clean();



    $phpinfo = str_replace('border: 1px', '', $phpinfo);

    preg_match('/<body>(.*)<\/body>/', $phpinfo, $regs);

    echo '<table border="1" cellpadding="3" width="100%" style="border: 0px;">' .

         '  <tr>' .

         '  </tr>' .

         '</table>';

    echo $regs[1];

  } else {

    phpinfo();

  }

?>

        </td>

      </tr>

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

