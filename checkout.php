<?php
/*
  $Id: checkout.php 1739 2007-12-20 00:52:16Z hpdl $
  one page checkout modified further by G.L.Walker
  http://wsfive.com
  for use with:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
require('includes/application_top.php');
require('includes/classes/http_client.php');

  if (SELECT_VENDOR_SHIPPING == 'true'){
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, $_SERVER['QUERY_STRING'], 'SSL'));
  }


 // if (ONEPAGE_CHECKOUT_ON_OFF == 'false'){
 //     tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, $_SERVER['QUERY_STRING'], 'SSL'));
//  }

if (ONEPAGE_LOGIN_REQUIRED == 'true'){
	if (!tep_session_is_registered('customer_id')){
		$navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT));
		tep_redirect(tep_href_link(FILENAME_LOGIN));
	}
}

if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code'])){
	$_REQUEST['gv_redeem_code'] = '';
	$_POST['gv_redeem_code'] = '';
}

if(isset($_REQUEST['coupon']) && tep_not_null($_REQUEST['coupon']) && $_REQUEST['coupon'] == 'redeem code'){
	$_REQUEST['coupon'] = '';
	$_POST['coupon'] = '';
}

require(DIR_WS_MODULES . 'checkout/includes/classes/onepage_checkout.php');
$onePageCheckout = new osC_onePageCheckout();
if (!isset($_GET['rType']) && !isset($_GET['action']) && !isset($_POST['action']) && !isset($_GET['error_message']) && !isset($_GET['payment_error'])){
	$onePageCheckout->init();
}

//BOF KGT
if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
	if(isset($_POST['code'])) {
		if(!tep_session_is_registered('coupon'))
		tep_session_register('coupon');
		$coupon = $_POST['code'];
		}
}
//EOF KGT
require(DIR_WS_CLASSES . 'order.php');
$order = new order;

$onePageCheckout->loadSessionVars();
$onePageCheckout->fixTaxes();

require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;

$onePageCheckout->loadSessionVars();
$onePageCheckout->fixTaxes();

require(DIR_WS_CLASSES . 'shipping.php');
$shipping_modules = new shipping;

if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
$cartID = $cart->cartID;

if (!isset($_GET['action']) && !isset($_POST['action']))
	{
	if ($order->content_type == 'virtual' || $order->content_type == 'virtual_weight' )
		{
		$shipping = false;
		$sendto = false;
		}
	}
else {
	if ($cart->count_contents() < 1) {
		if ($cart->count_contents() < 1 && $_POST['action'] == 'processLogin') {
			print json_encode(array(
					'success' => 'redirect',
					'url' => tep_href_link(FILENAME_SHOPPING_CART)
			));
			exit();
		}
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	}
	if (isset($cart->cartID) && tep_session_is_registered('cartID')){
		if ($cart->cartID != $cartID){
			if ($cart->count_contents() < 1 && $_POST['action'] == 'processLogin') {
				print json_encode(array(
					'success' => 'redirect',
					'url' => tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')
				));
				exit();
			}
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
		}
	}
}

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();
/* CCGV - BEGIN */
if (method_exists($cart, 'count_contents_virtual'))
	{
	$total_count = $cart->count_contents_virtual();
	}
/* CCGV - END */

require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->process();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT);

$action = (isset($_POST['action']) ? $_POST['action'] : '');
if (isset($_POST['updateQuantities_x']))
	{
	$action = 'updateQuantities';
	}
if (isset($_GET['action']) && $_GET['action']=='process_confirm')
	{
	$action = 'process_confirm';
	}
if (tep_not_null($action))
	{
	ob_start();
	if(isset($_POST) && is_array($_POST)) $onePageCheckout->decode_post_vars();
	switch($action)
		{
		case 'process_confirm':
			echo $onePageCheckout->confirmCheckout();
			break;
		case 'process':
			echo $onePageCheckout->processCheckout();
			break;
		case 'countrySelect':
			echo $onePageCheckout->getAjaxStateField();
			break;
		case 'processLogin':
			echo $onePageCheckout->processAjaxLogin($_POST['email'], $_POST['pass']);
			break;
		case 'removeProduct':
			echo $onePageCheckout->removeProductFromCart($_POST['pID']);
			break;
		case 'updateQuantities':
			echo $onePageCheckout->updateCartProducts($_POST['qty'], $_POST['id']);
			break;
		case 'setPaymentMethod':
			echo $onePageCheckout->setPaymentMethod($_POST['method']);
			break;
		case 'setGV':
			echo $onePageCheckout->setGiftVoucher($_POST['method']);
			break;
		case 'redeemPoints':
			echo $onePageCheckout->redeemPoints($_POST['points']);
			break;
		case 'clearPoints':
			echo $onePageCheckout->clearPoints();
			break;
		case 'setShippingMethod':
			echo $onePageCheckout->setShippingMethod($_POST['method']);
			break;
		case 'setSendTo':
		case 'setBillTo':
			echo $onePageCheckout->setCheckoutAddress($action);
			break;
		case 'checkEmailAddress':
			echo $onePageCheckout->checkEmailAddress($_POST['emailAddress']);
			break;
		case 'saveAddress':
		case 'addNewAddress':
			echo $onePageCheckout->saveAddress($action);
			break;
		case 'selectAddress':
			echo $onePageCheckout->setAddress($_POST['address_type'], $_POST['address']);
			break;
		case 'redeemVoucher':
			echo $onePageCheckout->redeemCoupon($_POST['code']);
			break;
		case 'setMembershipPlan':
			echo $onePageCheckout->setMembershipPlan($_POST['planID']);
			break;
		case 'updateCartView':
			if ($cart->count_contents() == 0)
				{
				echo 'none';
				}
			else
				{
				include(DIR_WS_MODULES . 'checkout/includes/modules/cart.php');
				}
			break;
		case 'updatePoints':
		case 'updateShippingMethods':
			include(DIR_WS_MODULES . 'checkout/includes/modules/shipping_method.php');
			break;
		case 'updatePaymentMethods':
			include(DIR_WS_MODULES . 'checkout/includes/modules/payment_method.php');
			break;
		case 'getOrderTotals':
			if (MODULE_ORDER_TOTAL_INSTALLED) echo '<table class="table">' . $order_total_modules->output() . '</table>';
			break;
		case 'updateRadiosforTotal':
			$order_total_modules->output();
			echo $order->info['total'];
			break;
		case 'getProductsFinal':
			include(DIR_WS_MODULES . 'checkout/includes/modules/products_final.php');
			break;
		case 'getNewAddressForm':
		case 'getAddressBook':
			$addresses_count = tep_count_customer_address_book_entries();
			if ($action == 'getAddressBook')
				{
				$addressType = $_POST['addressType'];
				include(DIR_WS_MODULES . 'checkout/includes/modules/address_book.php');
				}
			else
				{
				include(DIR_WS_MODULES . 'checkout/includes/modules/new_address.php');
				}
			break;
		case 'getEditAddressForm':
			$aID = tep_db_prepare_input($_POST['addressID']);
			$Qaddress = tep_db_query('select * from ' . TABLE_ADDRESS_BOOK . ' where customers_id = "' . (int)$customer_id . '" and address_book_id = "' . (int)$aID . '"');
			$address = tep_db_fetch_array($Qaddress);
			include(DIR_WS_MODULES . 'checkout/includes/modules/edit_address.php');
			break;
		case 'getBillingAddress':
			include(DIR_WS_MODULES . 'checkout/includes/modules/billing_address.php');
			break;
		case 'getShippingAddress':
			include(DIR_WS_MODULES . 'checkout/includes/modules/shipping_address.php');
			break;
		}

	$content = ob_get_contents();
	ob_end_clean();
	if($action=='process') echo $content;
	else echo ($content);
	tep_session_close();
	tep_exit();
	}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT, '', $request_type));
function buildInfobox($header, $contents, $inset = false) {
	global $action;
	echo '' . $header . '';
	if ($inset)
		echo '<div>' . $contents . '</div>';
	else {
		echo '<div>' . $contents . '</div>';
	}
}

function fixSeoLink($url)
	{
	return str_replace('&amp;', '&', $url);
	}

  require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_INCLUDES . 'column_left.php');
  include(DIR_WS_MODULES . 'checkout/includes/template_top.php');
?>
<noscript>
	<p>Please follow the instructions for your web browser:<br /><br />Internet Explorer</p>
	<ol>
	<li>On the&nbsp;<strong>Tools</strong>&nbsp;menu, click&nbsp;<strong>Internet Options</strong>, and then click the&nbsp;<strong>Security</strong>&nbsp;tab.</li>
	<li>Click the&nbsp;<strong>Internet</strong>&nbsp;zone.</li>
	<li>If you do not have to customize your Internet security settings, click&nbsp;<strong>Default Level</strong>. Then do step 4<blockquote>If you have to customize your Internet security settings, follow these steps:<br />
	a. Click&nbsp;<strong>Custom Level</strong>.<br />
	b. In the&nbsp;<strong>Security Settings &ndash; Internet Zone</strong>&nbsp;dialog box, click&nbsp;<strong>Enable</strong>&nbsp;for&nbsp;<strong>Active Scripting</strong>&nbsp;in the&nbsp;<strong>Scripting</strong>section.</blockquote></li>
	<li>Click the&nbsp;<strong>Back</strong>&nbsp;button to return to the previous page, and then click the&nbsp;<strong>Refresh</strong>&nbsp;button to run scripts.</li>
	</ol>
	<p><br />Firefox</p>
	<ol>
	<li>On the&nbsp;<strong>Tools</strong>&nbsp;menu, click&nbsp;<strong>Options</strong>.</li>
	<li>On the&nbsp;<strong>Content</strong>&nbsp;tab, click to select the&nbsp;<strong>Enable JavaScript</strong>&nbsp;check box.</li>
	<li>Click the&nbsp;<strong>Go back one page</strong>&nbsp;button to return to the previous page, and then click the&nbsp;<strong>Reload current page</strong>&nbsp;button to run scripts.</li>
	</ol>
	<p>&nbsp;</p>
</noscript>
<div class="page-header">
  <span class="pull-right">
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link(FILENAME_DEFAULT, '', 'SSL')); ?>
  </span><h1>Checkout</h1>
</div>



<?php
  if (isset($_GET['error_message'])) {
      $error = $_GET['error_message'];
?>

 <div class="alert alert-danger"> <?php
      echo tep_output_string_protected($error);
?>

      </div>


    <?php if (!empty($error['error'])) { ?>
<div class="alert alert-danger">

            <?php
      echo tep_output_string_protected($error['error']);
?>


      </div>
<?php
    }
  } //if (isset($_GET["s_71"]))
?>

<?php echo tep_draw_form('checkout', tep_href_link(FILENAME_CHECKOUT, '', $request_type), 'post','id=onePageCheckoutForm') . tep_draw_hidden_field('action', 'process'); ?>

<?php
if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error()))
{
?>
   <div class="alert alert-block alert-danger fade in">
   	<button data-dismiss="alert" class="close" type="button">×</button>
       <h4 class="alert-heading"> <?php echo tep_output_string_protected($error['title']); ?></h4>

        <p><?php
		if($error['error']!='')
		echo htmlspecialchars_decode($error['error']);
		else
		echo TEXT_PAYMENT_METHOD_UPDATE_ERROR; ?></p>

		</div>

<?php
}
?>

<?php
//$header = '<div class="ui-widget-header" style="padding-left:5px;">' . TABLE_HEADING_PRODUCTS . '</div>';
ob_start();
//include(DIR_WS_MODULES . 'checkout/includes/modules/cart.php');
$cartContents = ob_get_contents();
ob_end_clean();
//$cartContents .= '' . (MODULE_ORDER_TOTAL_INSTALLED ? '<table class="table table-condensed">' . $order_total_modules->output() . '</table>' : '') . '';
//$cartContents .= '<div style="float:left"><span name="updateQuantities" id="updateCartButton"></span></div><br class="clearfix">';
if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
	{
	$cartContents .= 	'<div class="well"><div class="form-group hide-coupon">'
                . '<label class="control-label" for="inputEmail3"><i class="fa fa-tags"></i> ' . TEXT_HAVE_COUPON_CCGV . '</label>'
                . ''
                . ' <div class="">' . ''. tep_draw_input_field('gv_redeem_code', '') . '</div></div>' . ' '
                . '<div class="form-group"><div class="">'
                . ''
                . ''
                . ''
                . '<span class="btn btn-default hide-coupon" id="voucherRedeem">'. IMAGE_REDEEM_VOUCHER . '</span></div></div><div class="clear"></div><hr></div>';
	/* CCGV - BEGIN */
	if(MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
	if (tep_session_is_registered('customer_id'))
		{
		$gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
		$gv_result = tep_db_fetch_array($gv_query);
		if ($gv_result['amount']>0)
			{
			$cartContents .= '<div style="float:left;">' . $order_total_modules->sub_credit_selection() . '</div>';
			}
		}
	/* CCGV - END */
	}
//BOF KGT
if (defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true')
	{
	$cartContents .=	'<div class="form-group">'
                . '<label class="col-sm-2 control-label" for="inputEmail3">' . TEXT_HAVE_COUPON_CCGV . '</label>'
                . '<div class="col-sm-10">'
					 . tep_draw_input_field('coupon', '') .
						'</div></div>'
                . '<div class="form-group">'
                . '<div class="col-sm-offset-2 col-sm-10">' . tep_image_submit('button_redeem.gif', IMAGE_REDEEM_VOUCHER, 'id="voucherRedeemCoupon"') .
					'</div>'
                . '</div>';
	}
//EOF KGT
buildInfobox($header, $cartContents);
?>
<div style="width:100%; margin:auto;"></div>

<div style="float:left; width:<?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? '100%' : '49.5%'); ?>; <?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? 'margin:auto' : 'margin-right:0px;'); ?>;">
<?php
$header = '<h3>' . TABLE_HEADING_BILLING_ADDRESS . '</h3>';
ob_start();
include(DIR_WS_MODULES . 'checkout/includes/modules/billing_address.php');
$billingAddress = ob_get_contents();
ob_end_clean();
$billingAddress = '<div id="logInRow" style="' . (isset($_SESSION['customer_id']) ? ' display:none;' : '' ) . '"><div class="clear"></div><br><p>' . TEXT_EXISTING_CUSTOMER_LOGIN . ' <a id="loginButton" href="' . fixSeoLink(tep_href_link(FILENAME_LOGIN)) . '">' . tep_draw_button('Login', 'key') . '</a></p></div>' . $billingAddress;
$billingAddress .=  '<div style="float:right; padding:5px;' . (isset($_SESSION['customer_id']) ? '' : ' display:none;') . '"><a id="changeBillingAddress" href="' . tep_href_link('checkout_payment_address.php', '', $request_type) . '">' . tep_draw_button('Change Address') . '</a></div>';
buildInfobox($header, $billingAddress, true);
?>
</div>
<?php
if(ONEPAGE_ADDR_LAYOUT == 'vertical')
	{
?><div class="clear"></div>


	<div id="loader-message">
	
</div>


<div class="clear"></div>
<?php
	} else {
?>
	<div style="float:right; width:<?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? '100%' : '49.5%'); ?>; <?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? 'margin:auto' : 'margin-left:0px;'); ?>;">
<?php } ?>
<?php
if ($onepage['shippingEnabled'] === true) {
	$header = '<h3>' . TABLE_HEADING_SHIPPING_ADDRESS . '</h3>';
	ob_start();
	include(DIR_WS_MODULES . 'checkout/includes/modules/shipping_address.php');
	$shippingAddress = ob_get_contents();
	ob_end_clean();
	//$shippingAddress = '<div style="' . (isset($_SESSION['customer_id']) ? ' display:none;' : '' ) . '"><div class="panel panel-default">
  	//<div class="panel-body">' . TEXT_DIFFERENT_SHIPPING . ' <input type="checkbox" name="diffShipping" id="diffShipping" value="1"></div></div>' . $shippingAddress;
	//$shippingAddress .= '<div style="float:right;' . (isset($_SESSION['customer_id']) ? '' : ' display:none;') . '"><a id="changeShippingAddress" href="' . tep_href_link('checkout_shipping_address.php', '', $request_type) . '">' . tep_draw_button('Change Address') . '</a></div>';
	$shippingAddress = '<div><div class="" style="' . (isset($_SESSION['customer_id']) ? ' display:none;' : '' ) . '">
  	<div class="panel-body">' . TEXT_DIFFERENT_SHIPPING . ' <input type="checkbox" name="diffShipping" id="diffShipping" value="1"></div></div>' . $shippingAddress;
	$shippingAddress .= '<div style="float:right;' . (isset($_SESSION['customer_id']) ? '' : ' display:none;') . '"><a id="changeShippingAddress" href="' . tep_href_link('checkout_shipping_address.php', '', $request_type) . '">' . tep_draw_button('Change Address') . '</a></div>';
	buildInfobox($header, $shippingAddress, true);
	}
?>
</div>


<?php
if ($onepage['shippingEnabled'] === true){
	if (tep_count_shipping_modules() > 0){
		$header = '<h3>' . TABLE_HEADING_SHIPPING_METHOD . '</h3>';
		$shippingMethod = '';
		if (isset($_SESSION['customer_id']))
			{
			ob_start();
			include(DIR_WS_MODULES . 'checkout/includes/modules/shipping_method.php');
			$shippingMethod = ob_get_contents();
			ob_end_clean();
			}
		$shippingMethod = '<div id="noShippingAddress" class="main noAddress alert alert-info" style="' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '"><i class="fa fa-info-circle"></i> Fill in your <b>billing address</b> to get shipping quotes then click update above. </div><div id="shippingMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $shippingMethod . '</div>';
		buildInfobox($header, $shippingMethod);
		}
?>

<?php
	}
?>
<?php
$header = '<div class="ui-widget-header" style="padding-left:5px;">' . TABLE_HEADING_PAYMENT_METHOD . '</div>';
$paymentMethod = '';
if (isset($_SESSION['customer_id']))
	{
	ob_start();
	include(DIR_WS_MODULES . 'checkout/includes/modules/payment_method.php');
	$paymentMethod = ob_get_contents();
	ob_end_clean();
	}
$paymentMethod = '<div id="noPaymentAddress" class="noAddress alert alert-info"  style="' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '"><i class="fa fa-info-circle"></i> Fill in your <b>billing address</b> for payment options then click then click update above. </div><div id="paymentMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $paymentMethod . '</div>';
buildInfobox($header, $paymentMethod);
?>


<?php
$header = '<div class="ui-widget-header" style="padding-left:5px;">' . TABLE_HEADING_COMMENTS . '</div>';
ob_start();
include(DIR_WS_MODULES . 'checkout/includes/modules/comments.php');
$commentBox = ob_get_contents();
ob_end_clean();
buildInfobox($header, $commentBox);
?>


	<table width="100%">
		<tr id="checkoutYesScript" style="display:none;">
		<!--	<td id="checkoutMessage"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td> -->
			<td><div id="checkoutButtonContainer"><?php echo '<p><input type="submit" class="btn btn-primary btn-lg" id="checkoutButton" formUrl="' . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type) . '" value="Confirm Order" /></p>'; ?><input type="hidden" name="formUrl" id="formUrl" value=""></div><div id="paymentHiddenFields" style="display:none;"></div></td>
		</tr>
		<tr id="checkoutNoScript">
 			<td align="right"> <?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>to update/view your order.'; ?> <?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
		</tr>
	</table>
</form>

 
<!-- dialogs_bof //-->
<div id="loginBox" title="Log Into My Account" style="display:none;">
		<div class="form-group">
			<label class="control-label"><?php echo ENTRY_EMAIL_ADDRESS;?></label>
			<div class="input-group margin-bottom-sm">
				<span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i> </span>
				<?php echo tep_draw_input_field('email_address');?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label"><?php echo ENTRY_PASSWORD;?></label>
			<div class="input-group margin-bottom-sm">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<?php echo tep_draw_password_field('password');?>
			</div>
		</div>
		<div class="form-group">
			<div class="text-info text-center">
				<a class="" href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL');?>"><?php echo TEXT_PASSWORD_FORGOTTEN;?></a>
			</div>
		</div>
</div>

<div id="confirmationBox" title="Order Confirmation" style="display:none">
	<center>
		<br>Please review your order to make sure it's accurate and click Confirm Order after loading is complete<br>
		<img id="imgDlgLgr" src="includes/modules/checkout/images/ajax-loader.gif"><br>
	</center>
</div>
<?php if (isset($_GET['error_message'])) { ?>
	<script>
		jQuery(document).ready(function($){
			$("#updateAddressBilling").click();
		});
	</script>
<?php } ?>
<div id="addressBook" title="Address Book" style="display:none"></div>
<div id="newAddress" title="New Address" style="display:none"></div>
<div id="ajaxMessages" style="display:none;"></div>

<!-- dialogs_eof//-->
    <!-- body_text_eof //-->

    <!-- body_eof //-->



        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
        <!-- right_navigation_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');