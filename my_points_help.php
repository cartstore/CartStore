<?php
/*
  $Id: my_points_help.php, v 2.00 2006/JULY/06 17:41:03 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MY_POINTS_HELP);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL'));

require(DIR_WS_INCLUDES . 'header.php');
 require(DIR_WS_INCLUDES . 'column_left.php'); ?>


<!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%">
<div class="page-header">
<h1>
<?php echo HEADING_TITLE; ?></h1> </div>

<?php echo TEXT_INFORMATION; ?>

            <p>
	<span class="pull-left">     
<a class="btn btn-default" href="javascript:history.go(-1)">Back</a></span>	

            </p>
 </td>
      </tr>
    </table>

<!-- body_text_eof //-->
 

<?php require(DIR_WS_INCLUDES . 'column_right.php'); 
 require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>