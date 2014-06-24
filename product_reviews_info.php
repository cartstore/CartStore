<?php
/*
  $Id: product_reviews_info.php,v 1.50 2003/06/20 14:25:58 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');
// BOF Separate Pricing Per Customer
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id

  if(!tep_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }
// EOF Separate Pricing Per Customer

  if (isset($_GET['reviews_id']) && tep_not_null($_GET['reviews_id']) && isset($_GET['products_id']) && tep_not_null($_GET['products_id'])) {
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$_GET['reviews_id'] . "' and r.products_id = '" . (int)$_GET['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
    $review_check = tep_db_fetch_array($review_check_query);

    if ($review_check['total'] < 1) {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }

  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$_GET['reviews_id'] . "'");

  $review_query = tep_db_query("select rd.reviews_text, r.reviews_rating,pd.products_url, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$_GET['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
  // BOF Separate Pricing Per Customer

  if(!tep_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

     if ($customer_group_id !='0') {
	$customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $review['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
	  if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
	    $review['products_price'] = $customer_group_price['customers_group_price'];
	  }
     }
// EOF Separate Pricing Per Customer



  $review = tep_db_fetch_array($review_query);

  if ($new_price = tep_get_products_special_price($review['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
  }

  if (tep_not_null($review['products_model'])) {
    $products_name = $review['products_name'] . '<br><span class="smallText">[' . $review['products_model'] . ']</span>';
  } else {
    $products_name = $review['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

require(DIR_WS_INCLUDES . 'header.php');
 require(DIR_WS_INCLUDES . 'column_left.php');

if($review['products_url']!="")
{
$newArea='<span class="btn btn-default" ><a class="" href='.$review['products_url'].' >Buy From Partner </a>';
}elseif(HIDE_PRICE_NON_LOGGED=="true" && $_SESSION['customers_email_address']=='')
{
$newArea='';
}else
$newArea='<p><a class="btn btn-default" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' .  IMAGE_BUTTON_IN_CART . '</a></p>';
if(HIDE_PRICE_NON_LOGGED=="true" && $_SESSION['customers_email_address']=='')
{
$products_price="";
}else
$products_price=$products_price;

?>

<!-- body_text //-->





<div  itemscope itemtype="http://data-vocabulary.org/Review">
<div class="page-header"><h1>

      <?php include(DIR_WS_TEMPLATES . '/system/front-admin-editor/edit-review.php'); ?>

        Review on <?php echo '<span itemprop="itemreviewed">' . $products_name . '</span> ' . sprintf(TEXT_REVIEW_BY, '<span itemprop="reviewer">' . tep_output_string_protected($review['customers_name']) . '</span>'); ?> </h1></div>
<?php echo '<a class="btn btn-default btn-sm" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))) . '">' .  IMAGE_BUTTON_BACK . '</a>'; ?>

<span class="pull-right lead text-success">
<?php echo $products_price; ?></span>
<hr>

	  <!-- // Points/Rewards Module V2.00 bof //-->
<?php
    if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
     <blockquote>
<?php echo REVIEW_HELP_LINK; ?></blockquote>



<?php
  }
?>
<!-- // Points/Rewards Module V2.00 eof //-->


<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

<?php
  if (tep_not_null($review['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a class="fancybox" data-fancybox-group="gallery" href="' . DIR_WS_IMAGES . $review['products_image'] . '">' . tep_image(DIR_WS_IMAGES . $review['products_image'], addslashes($review['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '') . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $review['products_image']) . '" class="thumbnail fancybox">' . tep_image(DIR_WS_IMAGES . $review['products_image'], $review['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '</a>'; ?>
</noscript>
<?php
  }


?>

</div>

<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

<div class="popover fade right in" style="display: block;position: relative;">
    <div class="arrow"></div>

   <h3 class="popover-title"> <i class="fa fa-user"></i> <?php echo '' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($review['customers_name'])) . ''; ?></h3>
     <div class="popover-content">



<?php echo '<span itemprop="summary">' . tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])), 60, '') . '</span>';
 	if ($review['reviews_rating'] > 0):
		echo '<p class="pull-right star-rating">';
		for ($s = 0; $s < $review['reviews_rating']; $s++){
			echo '<i class="fa fa-star"></i>';
		}
		echo '</p>';
	endif;
	?>
         <p><small> <i><span itemprop="dtreviewed" content="<?php echo date("Y-m-d", strtotime($review['date_added'])); ?>"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review['date_added'])); ?></span></i></small><br><small>Rating: <span itemprop="rating"><?php echo $review['reviews_rating']; ?></span></small></p>


 <?php echo '<p>'.$newArea.'</p>';?>

</div>

</div>

</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



    <br> <p><?php echo '<a class="btn btn-primary btn-lg btn-block" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '">' .  IMAGE_BUTTON_WRITE_REVIEW . '</a>'; ?>

</p>
</div>
</td>
      </tr>
    </table>
</div>
<!-- body_text_eof //-->


<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
