<script type="text/javascript" language="javascript" src="<?php echo DIR_WS_MODULES; ?>checkout/ext/jquery/jquery.ajaxq-0.0.1.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_WS_MODULES; ?>checkout/ext/jquery/jQuery.pstrength.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_WS_MODULES; ?>checkout/includes/modules/checkout.js"></script>
<script>
 jQuery('.pstrength-minchar').css({'font-size' : '10px'});
 jQuery('.fieldRed').css({'background':'#F00'});
 jQuery('.dec').css({ 
 	'background-position': '-16px -190px',
 	'float': 'left' 
 });


function CVVPopUpWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}

function CVVPopUpWindowEx(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=510,screenX=150,screenY=150,top=150,left=150')
}

  var onePage = checkout;
  onePage.initializing = true;
  onePage.ajaxCharset = '<?php echo CHARSET;?>';
  onePage.storeName = '<?php echo STORE_NAME; ?>';
  onePage.loggedIn = <?php echo (tep_session_is_registered('customer_id') ? 'true' : 'false');?>;
  onePage.stateEnabled = <?php echo (ACCOUNT_STATE == 'true' ? 'true' : 'false');?>;
  onePage.ccgvInstalled = <?php echo ((defined(MODULE_ORDER_TOTAL_COUPON_STATUS) && MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ? 'true' : 'false');?>;
  //BOF KGT
  onePage.kgtInstalled = <?php echo ((defined('MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true') ? 'true' : 'false');?>;
  //EOF KGT
  //BOF POINTS
  onePage.pointsInstalled = <?php echo (((defined(USE_POINTS_SYSTEM) && USE_POINTS_SYSTEM == 'true') && (defined(USE_REDEEM_SYSTEM) && USE_REDEEM_SYSTEM == 'true')) ? 'true' : 'false');?>;
  //EOF POINTS
  onePage.shippingEnabled = <?php echo ($onepage['shippingEnabled'] === true ? 'true' : 'false');?>;
  onePage.pageLinks = {
	  checkout: '<?php echo fixSeoLink(tep_href_link(FILENAME_CHECKOUT, session_name() . '=' . session_id() . '&rType=ajax', $request_type));?>',
	  shoppingCart: '<?php echo fixSeoLink(tep_href_link(FILENAME_SHOPPING_CART));?>'
  }

  function getFieldErrorCheck($element){
	  var rObj = {};
	  switch($element.attr('name')){
		  case 'billing_firstname':
		  case 'shipping_firstname':
			  rObj.minLength = <?php echo addslashes(ENTRY_FIRST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_FIRST_NAME_ERROR);?>';
		  break;
		  case 'billing_lastname':
		  case 'shipping_lastname':
			  rObj.minLength = <?php echo addslashes(ENTRY_LAST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_LAST_NAME_ERROR);?>';
		  break;
		  case 'billing_email_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_EMAIL_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_EMAIL_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_street_address':
		  case 'shipping_street_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_STREET_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_zipcode':
		  case 'shipping_zipcode':
			  rObj.minLength = <?php echo addslashes(ENTRY_POSTCODE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_POST_CODE_ERROR);?>';
		  break;
		  case 'billing_city':
		  case 'shipping_city':
			  rObj.minLength = <?php echo addslashes(ENTRY_CITY_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_CITY_ERROR);?>';
		  break;
		  case 'billing_dob':
			  rObj.minLength = <?php echo addslashes(ENTRY_DOB_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_DATE_OF_BIRTH_ERROR);?>';
		  break;
		  case 'billing_telephone':
			  rObj.minLength = <?php echo addslashes(ENTRY_TELEPHONE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR);?>';
		  break;
		  case 'billing_country':
		  case 'shipping_country':
			  rObj.errMsg = '<?php echo addslashes(ENTRY_COUNTRY_ERROR);?>';
		  break;
		  case 'billing_state':
		  case 'delivery_state':
			  rObj.minLength = <?php echo addslashes(ENTRY_STATE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STATE_ERROR);?>';
		  break;
		  case 'password':
		  case 'confirmation':
			  rObj.minLength = <?php echo addslashes(ENTRY_PASSWORD_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_PASSWORD_ERROR);?>';
		  break;
	  }
	return rObj;
  }

$(document).ready(function ()
	{
	$('#pageContentContainer').show();
	$('#ajaxMessages').dialog(
		{
		shadow: true,
		modal: true,
		width: 400,
		height: 150,
		open: function (event, ui)
			{
			$(this).parent().children().children('.ui-dialog-title').hide();
			$(this).parent().children().children('.ui-dialog-titlebar').hide();
			$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
			}
		});
	$('#confirmationBox').dialog(
		{
		autoOpen: false,
		resizable: false,
		shadow: false,
		height:300,
		modal:true,
		width:430,
		open: function (){}
		});

	var loginBoxOpened = false;
	$('#loginButton').click(function ()
		{
		if (loginBoxOpened)
			{
			$('#loginBox').dialog('open');
			return false;
			}
		$('#loginBox').dialog(
			{
			resizable: false,
			shadow: false,
			height:250,
			width:350,
			open: function ()
				{
				var $dialog = this;
				$('input', $dialog).keypress(function (e)
					{
					if (e.which == 13)
						{
						$('#loginWindowSubmit', $dialog).click();
						}
					});
				$('#loginWindowSubmit', $dialog).hover(function ()
					{
					this.style.cursor = 'pointer';
					}, function ()
					{
					this.style.cursor = 'default';
					}).click(function ()
					{
					var $this = $(this);
					$this.hide();
					var email = $('input[name="email_address"]', $dialog).val();
					var pass = $('input[name="password"]', $dialog).val();
					
					onePage.queueAjaxRequest({
						url: onePage.pageLinks.checkout,
						data: 'action=processLogin&email=' + email + '&pass=' + pass,
						type: 'post',
						beforeSend: function (){
							onePage.showAjaxMessage('Refreshing Shopping Cart');
							if ($('#loginStatus', $this.parent()).size() <= 0){
								$('<div>')
								.attr('id', 'loginStatus')
								.html('Processing Login')
								.attr('align', 'center')
								.insertAfter($this);
							}
						},
						success: function (data)
							{
							var txt = jQuery.parseJSON(data);
							if (txt.success === "true")
								{ 
								onePage.loggedIn = true;
/*							    $('#loginStatus', $dialog).html(txt.msg);
								$('#logInRow').hide();
								$('#changeBillingAddressTable').show();
								$('#changeShippingAddressTable').show();
								$('#newAccountEmail').remove();
								$('#diffShipping').parent().parent().parent().remove();
								onePage.updateAddressHTML('billing');
								onePage.updateAddressHTML('shipping');
								$('#shippingAddress').show();
								var updateTotals = true;
								onePage.updateCartView();
								onePage.updateFinalProductListing();
								onePage.updatePaymentMethods();
								if ($(':radio[name="payment"]:checked').size() > 0)
									{
									onePage.setPaymentMethod($(':radio[name="payment"]:checked'));
									updateTotals = false;
									}
								onePage.updateShippingMethods();
								if ($(':radio[name="shipping"]:checked').size() > 0)
									{
									onePage.setShippingMethod($(':radio[name="shipping"]:checked'));
									updateTotals = false;
									}
								if (updateTotals == true)
									{
									onePage.updateOrderTotals();
									}
								$('#loginBox').dialog('destroy');*/
								window.location.reload();
								}
							else
								{
								$('#logInRow').show();
								$('#loggedInRow').hide();
								$('#loginStatus', $dialog).html(txt.msg);
								setTimeout(function ()
									{
									$('#loginStatus').remove();
									$('#loginWindowSubmit').show();
									}, 6000);
								setTimeout(function ()
									{
									$('#loginStatus').html('Try again in 3');
									}, 3000);
								setTimeout(function ()
									{
									$('#loginStatus').html('Try again in 2');
									}, 4000);
								setTimeout(function ()
									{
									$('#loginStatus').html('Try again in 1');
									}, 5000);
								}
							},
						errorMsg: 'There was an error logging in, please inform <?php echo STORE_NAME; ?> about this error.'
						});
					});
				}
			});
		loginBoxOpened = true;
		return false;
		});

		$('#changeBillingAddress, #changeShippingAddress').click(function ()
			{
			var addressType = 'billing';
			if ($(this).attr('id') == 'changeShippingAddress')
				{
				addressType = 'shipping';
				}
			$('#addressBook').clone().show().appendTo(document.body).dialog(
				{
				shadow: false,
				width: 550,
				minWidth: 550,
				open: function ()
					{
					onePage.loadAddressBook($(this), addressType);
					},
				buttons:
					{
					'<?php echo addslashes(WINDOW_BUTTON_CANCEL);?>': function ()
						{
						var $this = $(this);
						var action = $('input[name="action"]', $this).val();
						if (action == 'selectAddress')
							{
							$this.dialog('close');
							}
						else if (action == 'addNewAddress' || action == 'saveAddress')
							{
							onePage.loadAddressBook($this, addressType);
							}
						},
					'<?php echo addslashes(WINDOW_BUTTON_CONTINUE);?>': function ()
						{
						var $this = $(this);
						var action = $('input[name="action"]', $this).val();
						if (action == 'selectAddress')
							{
							onePage.queueAjaxRequest(
								{
								url: onePage.pageLinks.checkout,
								beforeSendMsg: 'Setting Address',
								dataType: 'json',
								data: $(':input, :radio', this).serialize(),
								type: 'post',
								success: function (data)
									{
									$this.dialog('close');
									if (addressType == 'shipping')
										{
										onePage.updateAddressHTML('shipping');
										onePage.updateShippingMethods();
										}
									else
										{
										onePage.updateAddressHTML('billing');
										onePage.updatePaymentMethods();
										}
									},
								errorMsg: 'There was an error changing your address, please inform <?php echo STORE_NAME; ?> about this error.'
								});
							}
						else if (action == 'addNewAddress')
							{
							onePage.queueAjaxRequest(
								{
								url: onePage.pageLinks.checkout,
								beforeSendMsg: 'Saving New Address',
								dataType: 'json',
								data: $(":input", this).serialize(),
								type: 'post',
								success: function (data)
									{
									onePage.loadAddressBook($this, addressType);
									},
								errorMsg: 'There was an error adding your new address, please inform <?php echo STORE_NAME; ?> about this error.'
								});
							}
						else if (action == 'saveAddress')
							{ 
							var errors = false;
							$('input[name="firstname"],input[name="lastname"],input[name="street_address"],input[name="city"],*[name="state"],input[name="postcode"],select[name="country"]', this).each( function()
								{
								if($(this).val() == '')
								errors = true;
								});
							if(errors == false)
								{
								onePage.queueAjaxRequest(
									{ 
									url: onePage.pageLinks.checkout,
									beforeSendMsg: 'Updating Address',
									dataType: 'json',
									data: $(":input", this).serialize(),
									type: 'post',
									success: function (data){ 
										onePage.loadAddressBook($this, addressType);
										},
									errorMsg: 'There was an error saving your address, please inform <?php echo STORE_NAME; ?> about this error.'
									});
								}
							  else
								{
								alert('Please fill all the required fields to save this address');
								}
							}
						},
					'<?php echo addslashes(WINDOW_BUTTON_NEW_ADDRESS);?>': function ()
						{
						var $this = $(this);
						onePage.queueAjaxRequest(
							{
							url: onePage.pageLinks.checkout,
							data: 'action=getNewAddressForm',
							type: 'post',
							beforeSendMsg: 'Loading New Address Form',
							success: function (data)
								{
								$this.html(data);
								if(onePage.stateEnabled == true)
									{
									onePage.addCountryAjax($('select[name="country"]', $this), 'state', 'stateCol')
									}
								},
							errorMsg: 'There was an error loading new address form, please inform <?php echo STORE_NAME; ?> about this error.'
							});
						},
					'<?php echo addslashes(WINDOW_BUTTON_EDIT_ADDRESS);?>': function ()
						{
						var $this = $(this);
						onePage.queueAjaxRequest(
							{
							url: onePage.pageLinks.checkout,
							data: 'action=getEditAddressForm&addressID=' + $(':radio[name="address"]:checked', $this).val(),
							type: 'post',
							beforeSendMsg: 'Loading Edit Address Form',
							success: function (data)
								{
								$this.html(data);
								if(onePage.stateEnabled == true)
									{
									onePage.addCountryAjax($('select[name="country"]', $this), 'state', 'stateCol')
									}
								},
						errorMsg: 'There was an error loading edit address form, please inform <?php echo STORE_NAME; ?> about this error.'
							});
						}
					}
				});
			return false;
			});

	onePage.initCheckout();
});

<?php
// Start - CREDIT CLASS Gift Voucher Contribution
if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
if (MODULE_ORDER_TOTAL_INSTALLED)
	$temp=$order_total_modules->process();
	$temp=$temp[count($temp)-1];
	$temp=$temp['value'];

	$gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
	$gv_result = tep_db_fetch_array($gv_query);

if ($gv_result['amount']>=$temp){ $coversAll=true;
/*
?>
  function clearRadeos(){
	document.checkout.cot_gv.checked=!document.checkout.cot_gv.checked;
	for (counter = 0; counter < document.checkout.payment.length; counter++) {
	  // If a radio button has been selected it will return true
	  // (If not it will return false)
	  if (document.checkout.cot_gv.checked){
		document.checkout.payment[counter].checked = false;
		document.checkout.payment[counter].disabled=true;
	  } else {
		document.checkout.payment[counter].disabled=false;
	  }
	}
  }
<?php
} else {
  $coversAll=false;?>

  function clearRadeos(){
	document.checkout.cot_gv.checked=!document.checkout.cot_gv.checked;
  }
<?php 
*/
  }
}?>
function clearRadeos(){
	 return true;
  }
</script>
<!-- ONE PAGE END -->