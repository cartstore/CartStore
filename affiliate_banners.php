<?php
/*
  $Id: affiliate_banners.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS));
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
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>

      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BANNERS, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_links.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_BANNERS_BANNERS ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BANNERS, '', 'SSL') . '">' . tep_image_button('button_affiliate_banners.gif', IMAGE_BANNERS) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_PRODUCT . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_links.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_BANNERS_PRODUCT ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'SSL') . '">' . tep_image_button('button_affiliate_product.gif', IMAGE_PRODUCT) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_BUILD . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_links.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_BANNERS_BUILD ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, '', 'SSL') . '">' . tep_image_button('button_affiliate_build_a_link.gif', IMAGE_BUILD) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_TEXT . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_links.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_BANNERS_TEXT ;?> x</td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT, '', 'SSL') . '">' . tep_image_button('button_affiliate_text.gif', IMAGE_TEXT) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>