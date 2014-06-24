<?php /*

 $Id: create_account_success.php,v 1.30 2003/06/05 23:27:00 hpdl Exp $

 CartStore eCommerce Software, for The Next Generation

 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible

 */

require ('includes/application_top.php');

require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

$breadcrumb -> add(NAVBAR_TITLE_1);

$breadcrumb -> add(NAVBAR_TITLE_2);

if (sizeof($navigation -> snapshot) > 0) {

	$origin_href = tep_href_link($navigation -> snapshot['page'], tep_array_to_string($navigation -> snapshot['get'], array(tep_session_name())), $navigation -> snapshot['mode']);

	$navigation -> clear_snapshot();

} else {

	$origin_href = tep_href_link(FILENAME_DEFAULT);

}

	require (DIR_WS_INCLUDES . 'header.php');

	require (DIR_WS_INCLUDES . 'column_left.php');
 ?>


<!-- body_text //-->

<table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td>
        	
        	   <div class="well">        	
        	   	<div class="page-heading">
<h1><?php echo HEADING_TITLE; ?></h1></div>



           <span class="pull-left" style="margin-right:14px;"><i class="fa fa-user fa-5x"></i></span>
              	
              	
              	<p><?php echo TEXT_ACCOUNT_CREATED; ?></p>
<hr>

<!-- Points/Rewards Module V2.00 bof-->

<?php if (NEW_SIGNUP_POINT_AMOUNT > 0) {

?>

    <span class="pull-left" style="margin-right:5px;">  <i class="fa fa-rocket fa-5x"></i> </span>       <p><?php echo sprintf(TEXT_WELCOME_POINTS_TITLE, number_format(NEW_SIGNUP_POINT_AMOUNT, POINTS_DECIMAL_PLACES), $currencies -> format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT))); ?>.</p>

              <p><?php echo TEXT_WELCOME_POINTS_LINK; ?></p>
<hr>
              

<?php } ?>               

<!-- Points/Rewards Module V2.00 eof-->

 

        

          <p><?php echo '<a class="btn btn-primary" href="' . $origin_href . '">Continue</a>'; ?></p>  
 <div class="clear"></div>

 </div>
 
              </td>

      </tr>

    </table>
	 
<?php
require (DIR_WS_INCLUDES . 'column_right.php');
 
require (DIR_WS_INCLUDES . 'footer.php');
 
	require (DIR_WS_INCLUDES . 'application_bottom.php');
 ?>
