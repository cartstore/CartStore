<?php
/*
  $Id: affiliate_banners_text.php,v 2.00 2003/10/12

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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_TEXT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT));

  $affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title");
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<div class="page-header"><h1>
    <?php echo HEADING_TITLE; ?></h1>
</div>
        
        
<p>   <?php echo TEXT_INFORMATION; ?></p>
   
<?php 
if (tep_db_num_rows($affiliate_banners_values)) { 

   while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) { 
$prod_id=$affiliate_banners['affiliate_products_id']; 
$prod_name=$affiliate_banners['affiliate_banners_title']; 
$ban_id=$affiliate_banners['affiliate_banners_id']; 
    switch (AFFILIATE_KIND_OF_BANNERS) { 
     case 1: 
   // Link to Products 
   if ($prod_id>0) { 

    $link= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0"></a>'; 
    $link2= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $prod_name . '</a>'; 
   } 
   // generic_link 
   else { 
    $link= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0"></a>'; 
    $link2= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $prod_name . '</a>'; 
             } 
   break; 
  case 2: 
   // Link to Products 
   if ($prod_id>0) { 

    $link= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0"></a>'; 
    $link2= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $prod_name . '</a>'; 
   } 
   // generic_link 
   else { 
    $link= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0"></a>'; 
    $link2= '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $prod_name . '</a>'; 
             } 
   break;  
     } 

?>
       <h3><?php echo TEXT_AFFILIATE_NAME; ?>&nbsp;<?php echo $affiliate_banners['affiliate_banners_title']; ?></h3>
       <p><b>Text Version:</b> <?php echo $link2; ?></p> 
         <p><?php echo TEXT_AFFILIATE_INFO; ?></p> 
 
             <textarea cols="50" rows="3" class="form-control"><?php echo $link2; ?></textarea> 
   
             <hr>
<? 
   }
}
?>
          
<!-- body_text_eof //-->
 <!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
 <!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>