<?php
/*
  $Id: affiliate_terms.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_TERMS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_TERMS));

require(DIR_WS_INCLUDES . 'header.php'); 
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>


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
        <td><table width="100%" border="0" cellspacing="0" cellpadding="1" class="infoBox">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td class="infoBoxHeading"><?php echo HEADING_AFFILIATE_PROGRAM_TITLE; ?></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="4" class="infoBoxContents">
              <tr>
                <td class="smallText"><?php echo TEXT_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="50%" align="right" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
            <td width="50%" align="left" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . tep_image_button('button_login.gif', IMAGE_BUTTON_LOGIN) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>