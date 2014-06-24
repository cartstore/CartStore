<?php
/*
  $Id: product_reviews.php,v 1.50 2003/06/09 23:03:55 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $product_info_query = tep_db_query("select p.products_id, p.products_model,p.map_price, p.msrp_price, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_PRODUCT_REVIEWS == 'Yes' ? $YMM_where : '') . " p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  print("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
	// BOF Separate Pricing Per Customer
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id

  if(!tep_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

     if ($customer_group_id !='0') {
	$customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
	  if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
	    $product_info['products_price'] = $customer_group_price['customers_group_price'];
	  }
     }
// EOF Separate Pricing Per Customer





  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }
if($product_info['map_price']!="0.00")
{
$products_price='<span class="msrp_name">MSRP Price:</span> <span class="msrp_price">'.$currencies->display_price($product_info['msrp_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</span><br>

<span class="map_name">MAP Price:</span> <span class="map_price">'.$currencies->display_price($product_info['map_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</span><br>

<span class="ourprice_name">Our Price:</span> <span class="our_price_price"><a href=�login.php�>Login to See Price</a></span>' ;
}elseif($product_info['msrp_price']!="0.00")
{
$products_price='<div class="price">'.$currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</div><span class="msrp_name">MSRP Price:</span> <span class="msrp_price">'.$currencies->display_price($product_info['msrp_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</span><br>';
}else
$products_price=$products_price;
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

 require(DIR_WS_INCLUDES . 'header.php');
require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- body_text //-->

<div class="page-header"><h1><?php echo $products_name; ?></h1></div>
    <?php echo '<a class="btn btn-default btn-sm" href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()) . '">' . IMAGE_BUTTON_BACK . '</a>'; ?>

  <span class="pull-right text-success lead"><?php echo $products_price; ?></span>

<hr>

	  <!-- // Points/Rewards Module V2.00 bof //-->
<?php
    if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
<blockquote>
<?php echo REVIEW_HELP_LINK; ?>

</blockquote>

<?php
  }
?>
<!-- // Points/Rewards Module V2.00 eof //-->


<?php
if($product_info['products_id']=="")
{
$product_id=$_GET['products_id'];
}else
{
$product_id=$product_info['products_id'];
}
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_id . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";

  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
               <p><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></p>

<ul class="pagination"><?php echo '' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></ul>





<?php
    }

    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
<div class="list-group" itemscope itemtype="http://data-vocabulary.org/Review">

<h4><i class="fa fa-user"></i> <?php echo '<a class="general_link" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><u><b>' . sprintf(TEXT_REVIEW_BY, '<span itemprop="reviewer">' . tep_output_string_protected($reviews['customers_name']) . '</span>') . '</b></u></a>'; ?></h4>

 <p class="list-group-item-text" itemprop="summary"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '');
 	if ($reviews['reviews_rating'] > 0):
		echo '<span class="pull-right star-rating">';
		for ($s = 0; $s < $reviews['reviews_rating']; $s++){
			echo '<i class="fa fa-star"></i>';
		}
		echo '</span>';
	endif;
 ?>


            <br>
<i itemprop="dtreviewed" datetime="<?php echo date("Y-m-d",strtotime($reviews['date_added'])); ?>"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></i>
    <div itemprop="itemreviewed"><?php echo $product_info['products_name']; ?></div>
    <div>Rating: <span itemprop="rating"><?php echo $reviews['reviews_rating']; ?></span></div>
</p>
<hr>
   </div>


<?php
    }
?>
<?php
  } else {
?>


<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

<ul class="pagination">

<?php echo ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
</ul>

<?php
  }
?>

    <?php echo '<p><a class="btn btn-primary btn-lg btn-block" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' .  IMAGE_BUTTON_WRITE_REVIEW . '</a></p><hr>'; ?>

<?php
  if (tep_not_null($product_info['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a class="thumbnail" href="' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '" rel="lightbox">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a class="" href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '') . '<br><span class="">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
</noscript>
<?php
  }

  echo '<p><a class="btn btn-primary btn-lg" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' .  IMAGE_BUTTON_IN_CART . '</a></p>';
?>
                 </td>
      </tr>
    </table>
<!-- body_text_eof //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php');
require(DIR_WS_INCLUDES . 'footer.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
