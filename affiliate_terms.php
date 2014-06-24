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
    <div class="page-header"><h1><?php echo HEADING_TITLE; ?> </h1></div>
     <h3><?php echo HEADING_AFFILIATE_PROGRAM_TITLE; ?></h3>
  
<?php echo TEXT_INFORMATION; ?>

   
<?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL') . '">' . tep_image_button('', IMAGE_BUTTON_CONTINUE) . 'Continue</a>'; ?>
<hr>
<?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . tep_image_button('', IMAGE_BUTTON_LOGIN) . 'Back</a>'; ?>
</td>
      </tr>
    </table>

<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>