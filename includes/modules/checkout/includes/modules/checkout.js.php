<?php
//chdir("../../../../../");
//require_once("includes/application_top.php");
header("Content-type: text/javascript");
?>
var submitter = null;
var paymentVals = new Array();

function submitFunction() {
	submitter = 1;
}

var errCSS = {
	'border-color': 'red',
	'border-style': 'solid'
};

function bindAutoFill($el){
	if ($el.attr('type') == 'select-one'){
		var method = 'change';
	}else{
		var method = 'blur';
	}

	$el.blur(unsetFocus).focus(setFocus);

	if (document.attachEvent){
		$el.get(0).attachEvent('onpropertychange', function (){
			if (jQuery(event.srcElement).data('hasFocus') && jQuery(event.srcElement).data('hasFocus') == 'true') return;
			if (jQuery(event.srcElement).val() != '' && jQuery(event.srcElement).hasClass('required')){
				jQuery(event.srcElement).trigger(method);
			}
		});
	}else{
		$el.get(0).addEventListener('onattrmodified', function (e){
			if (jQuery(e.currentTarget).data('hasFocus') && jQuery(e.currentTarget).data('hasFocus') == 'true') return;
			if (jQuery(e.currentTarget).val() != '' && jQuery(e.currentTarget).hasClass('required')){
				jQuery(e.currentTarget).trigger(method);
			}
		}, false);
	}
}

function setFocus(){
	jQuery(this).data('hasFocus', 'true');
}

function unsetFocus(){
	jQuery(this).data('hasFocus', 'false');
}

var checkout = {
	charset: 'UTF-8',
	pageLinks: {},
	errors:true,
	checkoutClicked:false,
	amountRemaininginTotal:true,
	billingInfoChanged: false,
	shippingInfoChanged: false,
	fieldSuccessHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="success_icon ui-icon ui-icon-circle-check"></div>',
	fieldErrorHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="error_icon ui-icon ui-icon-circle-close"></div>',
	fieldRequiredHTML: '<div style="margin-left:1px;margin-top:1px;float:left;" class="required_icon ui-icon ui-icon-gear"></div>',
	bootboxDialog: '',
	showAjaxLoader: function ()
		{
				this.bootboxDialog = bootbox.dialog({
					message: '<p class="text-info text-center"><img src="includes/modules/checkout/images/ajax_load.gif"></p>',
					show: false,
					animate: false,
					className: 'bootboxAjaxMessages'
				});
		},
	hideAjaxLoader: function ()
		{
				jQuery(".bootboxAjaxMessages").modal('hide');
		},
	showAjaxMessage: function (message){
			jQuery('#checkoutButtonContainer').hide();
                        
                       jQuery("#loader-message").load("./loading.php");

                        
			
	},
	hideAjaxMessage: function (){
			jQuery('#checkoutButtonContainer').show();
			jQuery(".bootboxAjaxMessages").modal('hide');
                        jQuery(".bootboxAjaxMessages").modal('hide');
                    $('#loader-message').html("");
	},
	fieldErrorCheck: function ($element, forceCheck, hideIcon){

		forceCheck = forceCheck || false;
		hideIcon = hideIcon || false;
		var errMsg = this.checkFieldForErrors($element, forceCheck);
		if (hideIcon == false){
			if (errMsg != false){
				this.addIcon($element, 'error', errMsg);
				return true;
			}else{
				this.addIcon($element, 'success', errMsg);
				}
		}else{
			if (errMsg != false){
				return true;
			}
		}
		return false;
	},
	checkFieldForErrors: function ($element, forceCheck){
		var hasError = false;
		if ($element.is(':visible') && ($element.hasClass('required') || forceCheck == true)){
			var errCheck = getFieldErrorCheck($element);
			if (!errCheck.errMsg){
				return false;
			}

			switch($element.attr('type')){
				case 'password':
				if ($element.attr('name') == 'password'){
					if ($element.val().length < errCheck.minLength){
						hasError = true;
					}
				}else{
					if ($element.val() != jQuery(':password[name="password"]', jQuery('#billingAddress')).val() || $element.val().length <= 0){
						hasError = true;
					}
				}
				break;
				case 'radio':
				if (jQuery(':radio[name="' + $element.attr('name') + '"]:checked').size() <= 0){
					hasError = true;
				}
				break;
				case 'checkbox':
				if (jQuery(':checkbox[name="' + $element.attr('name') + '"]:checked').size() <= 0){
					hasError = true;
				}
				break;
				case 'select-one':
				if ($element.val() == ''){
					hasError = true;
				}
				break;
				default:
				if ($element.val().length < errCheck.minLength){
					hasError = true;
				}
				break;
			}
			if (hasError == true){
				return errCheck.errMsg;
			}
		}
		return hasError;
	},
	addIcon: function ($curField, iconType, title){
		title = title || false;
		$curField.parent().removeClass('has-warning has-error has-success');
		switch(iconType){
			case 'error':
			if (this.initializing == true){
				this.addRequiredIcon($curField, 'Required');
			}else{
				this.addErrorIcon($curField, title);
			}
			break;
			case 'success':
				this.addSuccessIcon($curField, title);
			break;
			case 'required':
				this.addRequiredIcon($curField, 'Required');
			break;
		}
	},
	addSuccessIcon: function ($curField, title){
		if (!$curField.parent().hasClass('has-success'));
			$curField.parent().addClass('has-success');
	},
	addErrorIcon: function ($curField, title){
		if (!$curField.parent().hasClass('has-error'));
			$curField.parent().addClass('has-error');
	},
	addRequiredIcon: function ($curField, title){
		if ($curField.hasClass('required')){
		}
	},
	clickButton: function (elementName){
		if (jQuery(':radio[name="' + elementName + '"]').size() <= 0){
			jQuery('input[name="' + elementName + '"]').trigger('click', true);
		}else{
			jQuery(':radio[name="' + elementName + '"]:checked').trigger('click', true);
		}
	},
	addRowMethods: function($row){
		$row.hover(function (){
			if (!jQuery(this).hasClass('moduleRowSelected')){
				jQuery(this).addClass('moduleRowOver');
			}
		}, function (){
			if (!jQuery(this).hasClass('moduleRowSelected')){
				jQuery(this).removeClass('moduleRowOver');
			}
		}).click(function (){
			if (!jQuery(this).hasClass('moduleRowSelected')){
				var selector = (jQuery(this).hasClass('shippingRow') ? '.shippingRow' : '.paymentRow') + '.moduleRowSelected';
				jQuery(selector).removeClass('moduleRowSelected');
				jQuery(this).removeClass('moduleRowOver').addClass('moduleRowSelected');
				if(jQuery(':radio', jQuery(this)).is(':disabled')!==true)
				if (!jQuery(':radio', jQuery(this)).is(':checked')){
					jQuery(':radio', jQuery(this)).attr('checked', 'checked').click();
				}
			}
		});
	},
	queueAjaxRequest: function (options){
		var checkoutClass = this;
		var o = {
			url: options.url,
			async: false,
			cache: options.cache || false,
			dataType: options.dataType || 'html',
			type: options.type || 'GET',
			contentType: options.contentType || 'application/x-www-form-urlencoded; charset=' + this.ajaxCharset,
			data: options.data || false,
			beforeSend: options.beforeSend || function (){
				checkoutClass.showAjaxMessage(options.beforeSendMsg || 'Ajax Operation, Please Wait...');
				checkoutClass.showAjaxLoader();
			},
			complete: function (){
					checkoutClass.hideAjaxMessage();
					if (document.ajaxq.q['orderUpdate'].length <= 0){
						if(checkoutClass.errors != true && checkoutClass.checkoutClicked == true){
							var buttonConfirmOrder = jQuery('.ui-dialog-buttonpane button:first');
							buttonConfirmOrder.removeClass('ui-state-disabled');
							jQuery('#imgDlgLgr').hide();
						}
						checkoutClass.hideAjaxLoader();
					}
			},
			success: options.success,
			error: function (XMLHttpRequest, textStatus, errorThrown){
				if (XMLHttpRequest.responseText == 'session_expired') document.location = this.pageLinks.shoppingCart;
				alert(options.errorMsg || 'There was an ajax error, please contact ' + checkoutClass.storeName + ' for support.');
			}
		};
		jQuery.ajaxq('orderUpdate', o);
	},
	updateAddressHTML: function (type){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: "action=" + (type == "shipping" ? "getShippingAddress" : "getBillingAddress"),
			type: "post",
			beforeSendMsg: "Updating " + (type == "shipping" ? "Shipping" : "Billing") + " Address",
			success: function (data){
				jQuery('#' + type + 'Address').html(data);
			},
			errorMsg: 'There was an error loading your ' + type + ' address, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	attachAddressFields: function(){
		var checkoutClass = this;
		jQuery('input, select', jQuery('#billingAddress')).each(function (){
			if (jQuery(this).attr('name') != undefined && jQuery(this).attr('type') != 'checkbox' && jQuery(this).attr('type') != 'radio'){
				jQuery(this).blur(function (){

					if (jQuery(this).hasClass('required')){
						checkoutClass.fieldErrorCheck(jQuery(this));

					}
				});
				bindAutoFill(jQuery(this));

				if (jQuery(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck(jQuery(this), true, true) == false){
						checkoutClass.addIcon(jQuery(this), 'success');
					}else{
						jQuery('input').addClass('fieldRed');
						checkoutClass.addIcon(jQuery(this), 'required');
					}
				}
			}
		});

		jQuery('input,select[name="billing_country"], ', jQuery('#billingAddress')).each(function (){
			var processFunction = function (){
				checkoutClass.billingInfoChanged = true;
				if (jQuery(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck(jQuery(this)) == false){
						checkoutClass.processBillingAddress(true);
					}
				}else{
					checkoutClass.processBillingAddress(true);
				}
			};

			jQuery(this).unbind('blur');
			if (jQuery(this).attr('type') == 'select-one'){
				jQuery(this).change(processFunction);
			}else{
				jQuery(this).blur(processFunction);
			}
			bindAutoFill(jQuery(this));
		});
		jQuery('input,select[name="shipping_country"]', jQuery('#shippingAddress')).each(function (){
			if (jQuery(this).attr('name') != undefined && jQuery(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					checkoutClass.shippingInfoChanged = true;

					if (jQuery(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck(jQuery(this)) == false){
							checkoutClass.processShippingAddress();
						}else{
							jQuery('#noShippingAddress').show();
							jQuery('#shippingMethods').hide();
						}
					}else{
						checkoutClass.processShippingAddress();
					}
				};

				jQuery(this).blur(processAddressFunction);
				bindAutoFill(jQuery(this));

				if (jQuery(this).hasClass('required')){
					var icon = 'required';
					if (jQuery(this).val() != '' && checkoutClass.fieldErrorCheck(jQuery(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon(jQuery(this), icon);
				}
			}
		});
		if(checkoutClass.stateEnabled == true)
		{

			jQuery('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = jQuery(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
				checkoutClass.addCountryAjax(jQuery(this), fieldType + '_state', 'stateCol_' + fieldType);

			});

			jQuery('*[name="billing_zipcode"], *[name="delivery_zipcode"]').each(function (){
				var processAddressFunction = checkoutClass.processBillingAddress;
				if (jQuery(this).attr('name') == 'delivery_zipcode'){
					processAddressFunction = checkoutClass.processShippingAddress;
				}
				var processFunction = function (){
					if (jQuery(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck(jQuery(this)) == false){
							processAddressFunction.call(checkoutClass);
						}
					}else{
						processAddressFunction.call(checkoutClass);
					}
				}

				if (jQuery(this).attr('type') == 'select-one'){
					jQuery(this).change(processFunction);
				}else{
					jQuery(this).blur(processFunction);
				}
				bindAutoFill(jQuery(this));
			});
		}
	},
	updateCartView: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: "action=updateCartView",
			type: "POST",
			beforeSendMsg: "Refreshing Shopping Cart",
			success: function (data){
				if (data == 'none'){
					document.location = checkoutClass.pageLinks.shoppingCart;
				}else{
					jQuery('#shoppingCart').html(data);

					jQuery('.removeFromCart').each(function (){
						checkoutClass.addCartRemoveMethod(jQuery(this));
					});
				}
			},
			errorMsg: 'There was an error refreshing the shopping cart, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	updateFinalProductListing: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=getProductsFinal',
			type: 'post',
			beforeSendMsg: 'Refreshing Final Product Listing',
			success: function (data){
				jQuery('.finalProducts').html(data);
			},
			errorMsg: 'There was an error refreshing the final products listing, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	setGV: function (status){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=setGV&cot_gv=' + status,
			type: 'post',
			beforeSendMsg: (status=='on'?'':'Un') + 'Setting Gift Voucher',
			dataType: 'json',
			success: function (data){
				checkoutClass.updateOrderTotals();

			},
			errorMsg: 'There was an error ' + (status=='on'?'':'Un') + 'setting Gift Voucher method, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	updateOrderTotals: function (){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			cache: false,
			data: 'action=getOrderTotals&randomNumber='+Math.random(),
			type: 'post',
			beforeSendMsg: 'Updating Order Totals',
			success: function (data){
				jQuery('.orderTotals').html(data);
				checkoutClass.hideAjaxLoader();
			},
			errorMsg: 'There was an error refreshing the shopping cart, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	updateRadiosforTotal: function(total){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			cache: false,
			data: 'action=updateRadiosforTotal',
			type: 'post',
			beforeSendMsg: 'Checking Totals',
			success: function (data){
				if(data == 0){
				checkoutClass.amountRemaininginTotal=false;
		  		jQuery('#paymentMethods input:radio').attr('disabled',true);
				}else{
				checkoutClass.amountRemaininginTotal=true;
				jQuery('#paymentMethods input:radio').attr('disabled',false);
				}

			},
			errorMsg: 'There was an error refreshing the shopping cart, please inform ' + checkoutClass.storeName + ' about this error.'
		});

	},
	updatePoints: function()
	{
		var checkoutClass = this;
		checkoutClass.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=updatePoints',
			type: 'post',
			beforeSendMsg: 'Updating Points',
			success: function (data){
				jQuery('#pointsSection').html(data);
					if(jQuery(':input[name="customer_points"]',jQuery(this)))
					{
						jQuery(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
							if (event.keyCode == '13') {
								if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
								{
									jQuery('input[name="customer_points"]').attr('disabled','true');
									checkoutClass.checkPoints();
									this.changed = true;
								}else
								{
									this.changed = false;
								}
								event.preventDefault();
								return false;
							}

						});
						jQuery(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
							if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								jQuery('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}else
							{
								checkoutClass.clearPoints();
							}
							return true;
						});

						jQuery(':input[name="customer_points"]').unbind('blur').blur(function() {
							if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								jQuery('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}
						});

					}
			},
			errorMsg: 'There was an error updating points, please inform IT Web Experts about this error.'
		});
		return false;
	},
	checkPoints: function()
	{
		var checkoutClass = this;
		checkoutClass.queueAjaxRequest({
			url: checkoutClass.pageLinks.checkout,
			data: 'action=redeemPoints&points=' + jQuery('input[name="customer_points"]').val(),
			type: 'post',
			beforeSendMsg: 'Validating Points',
			dataType: 'json',
			success: function (data){
				if (data.success == false){
					alert('You do not have ' + jQuery('input[name="customer_points"]').val() + ' points please enter a valid number of points');
				}
				jQuery('input[name="customer_points"]').removeAttr('disabled');
				checkoutClass.updatePoints();
				checkoutClass.updateOrderTotals();

			},
			errorMsg: 'There was an error redeeming points, please inform IT Web Experts about this error.'
		});
		return false;
	},
	clearPoints: function()
	{
		var checkoutClass = this;
		checkoutClass.queueAjaxRequest({
			url: checkoutClass.pageLinks.checkout,
			data: 'action=clearPoints',
			type: 'post',
			beforeSendMsg: 'Clearing Points',
			dataType: 'json',
			success: function (data){
				checkoutClass.updatePoints();
				checkoutClass.updateOrderTotals();

			},
			errorMsg: 'There was an error redeeming points, please inform IT Web Experts about this error.'
		});
		return false;
	},
	updateModuleMethods: function (action, noOrdertotalUpdate){
		var checkoutClass = this;
		var descText = (action == 'shipping' ? 'Shipping' : 'Payment');
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=update' + descText + 'Methods',
			type: 'post',
			beforeSendMsg: 'Updating ' + descText + ' Methods',
			success: function (data){
				jQuery('#no' + descText + 'Address').hide();
				jQuery('#' + action + 'Methods').html(data).show();
				if(action == 'payment')
				{
					if(jQuery('input[name="cot_gv"]', jQuery('#paymentMethods')))
					{
						jQuery('input[name="cot_gv"]', jQuery('#paymentMethods')).each(function (){
							jQuery(this).unbind('change').change(function (e){
								checkoutClass.setGV((jQuery(':checkbox[name="cot_gv"]').is(':checked'))?'on':'');
							});
						});
					}
					if(jQuery(':input[name="customer_points"]',jQuery(this)))
					{
						jQuery(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
							if (event.keyCode == '13') {
								if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
								{
									jQuery('input[name="customer_points"]').attr('disabled','true');
									checkoutClass.checkPoints();
									this.changed = true;
								}else
								{
									this.changed = false;
								}
								event.preventDefault();
								return false;
							}

						});
						jQuery(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
							if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								jQuery('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}else
							{
								checkoutClass.clearPoints();
							}
							return true;
						});

						jQuery(':input[name="customer_points"]').unbind('blur').blur(function() {
							if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								jQuery('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}
						});

					}

				}
				jQuery('.' + action + 'Row').each(function (){
					checkoutClass.addRowMethods(jQuery(this));

					jQuery('input[name="' + action + '"]', jQuery(this)).each(function (){
						var setMethod = checkoutClass.setPaymentMethod;
						if (action == 'shipping'){
							setMethod = checkoutClass.setShippingMethod;
						}
						jQuery(this).click(function (e, noOrdertotalUpdate){
							setMethod.call(checkoutClass, jQuery(this));
								checkoutClass.updateOrderTotals();
						});
					});
				});
				checkoutClass.clickButton(descText.toLowerCase());
			},
			errorMsg: 'There was an error updating ' + action + ' methods, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	updateShippingMethods: function (noOrdertotalUpdate){
		if (this.shippingEnabled == false){
			return false;
		}

		this.updateModuleMethods('shipping', noOrdertotalUpdate);
	},
	updatePaymentMethods: function (noOrdertotalUpdate){
		this.updateModuleMethods('payment', noOrdertotalUpdate);
	},
	setModuleMethod: function (type, method, successFunction){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=set' + (type == 'shipping' ? 'Shipping' : 'Payment') + 'Method&method=' + method,
			type: 'post',
			beforeSendMsg: 'Setting ' + (type == 'shipping' ? 'Shipping' : 'Payment') + ' Method',
			dataType: 'json',
			success: successFunction,
			errorMsg: 'There was an error setting ' + type + ' method, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	setShippingMethod: function ($button){
		if (this.shippingEnabled == false){
			return false;
		}

		var checkoutClass = this;
		this.setModuleMethod('shipping', $button.val(), function (data){
		});
	},
	setPaymentMethod: function ($button){
		var checkoutClass = this;
		this.setModuleMethod('payment', $button.val(), function (data){
			jQuery('.paymentFields').remove();
			if (data.inputFields != ''){

			// jQuery(data.inputFields).appendTo($button.parent().prev()); // throws ownerDocument of undefined error in Chrome
			$button.parent().prev().append(data.inputFields);

				jQuery('input,select,radio','.paymentFields').each( function ()
				{
					if(paymentVals[jQuery(this).attr('name')])
					{
						jQuery(this).val(paymentVals[jQuery(this).attr('name')]);
					}
					jQuery(this).blur(function (){
						paymentVals[jQuery(this).attr('name')] = jQuery(this).val();
					});
				});
			}
		});
	},
	loadAddressBook: function ($dialog, type){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: 'action=getAddressBook&addressType=' + type,
			type: 'post',
			beforeSendMsg: 'Loading Address Book',
			success: function (data){
				$dialog.html(data);
			},
			errorMsg: 'There was an error loading your address book, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	addCountryAjax: function ($input, fieldName, stateCol){
		var checkoutClass = this;
		$input.blur(function(event,callBack){
			if (jQuery(this).hasClass('required')){
				if (jQuery(this).val() != '' && jQuery(this).val() > 0){
					checkoutClass.addIcon(jQuery(this), 'success');
				} else {
					checkoutClass.addIcon(jQuery(this), 'error');
				}
			}
		});
		$input.change(function (event, callBack){
			var thisName = jQuery(this).attr('name');

			if (thisName == 'shipping_country')
			{
				checkoutClass.shippingInfoChanged = true;
			}else
			{
				checkoutClass.billingInfoChanged = true;
			}

			if (jQuery(this).hasClass('required')){
				if (jQuery(this).val() != '' && jQuery(this).val() > 0){
					checkoutClass.addIcon(jQuery(this), 'success');
				}
			}

			var $origStateField = jQuery('*[name="' + fieldName + '"]', jQuery('#' + stateCol));
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=countrySelect&fieldName=' + fieldName + '&cID=' + jQuery(this).val() + '&curValue=' + $origStateField.val(),
				type: 'post',
				beforeSendMsg: 'Getting Country\'s Zones',
				success: function (data){
					jQuery('#' + stateCol).html(data);
					var $curField = jQuery('*[name="' + fieldName + '"]', jQuery('#' + stateCol));
					jQuery("#billing_country, #billing_state, #shipping_country, #shipping_state").addClass('required');
					if ($curField.hasClass('required')){
						if (checkoutClass.fieldErrorCheck($curField, true, true) == false){
							checkoutClass.addIcon($curField, 'success');
						}else{
							checkoutClass.addIcon($curField, 'required');
						}
					}

					var processAddressFunction = checkoutClass.processBillingAddress;
					if (thisName == 'shipping_country'){
						processAddressFunction = checkoutClass.processShippingAddress;
					}

					var processFunction = function (){
						if (jQuery(this).hasClass('required')){
							if (checkoutClass.fieldErrorCheck(jQuery(this)) == false){
								processAddressFunction.call(checkoutClass);
							}
						}else{
							processAddressFunction.call(checkoutClass);
						}
					};

					bindAutoFill($curField);

					if ($curField.attr('type') == 'select-one'){
						$curField.change(processFunction);
					}else{
						$curField.blur(processFunction);
					}

					if (callBack){
						callBack.call(checkoutClass);
					}
				},
				errorMsg: 'There was an error getting states, please inform ' + checkoutClass.storeName + ' about this error.'
			});
		});
	},
	addCartRemoveMethod: function ($element){
		var checkoutClass = this;
		$element.click(function (){
			var $productRow = jQuery(this).parent().parent();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: jQuery(this).attr('linkData'),
				type: 'post',
				beforeSendMsg: 'Removing Product From Cart',
				dataType: 'json',
				success: function (data){
					if (data.products == 0){
						document.location = checkoutClass.pageLinks.shoppingCart;
					}else{
						$productRow.remove();
						checkoutClass.updateFinalProductListing();
						checkoutClass.updateShippingMethods(true);
						checkoutClass.updateOrderTotals();
					}
				},
				errorMsg: 'There was an error updating shopping cart, please inform ' + checkoutClass.storeName + ' about this error.'
			});
			return false;
		});
	},
	processBillingAddress: function (skipUpdateTotals){
		var hasError = false;
		var checkoutClass = this;
		jQuery('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], *[name="billing_state"]', jQuery('#billingAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck(jQuery(this), false, true) == true){
				hasError = true;
			}
		});
		if (hasError == true){
			return;
		}

		this.setBillTo();
		if (jQuery('#diffShipping').checked && this.loggedIn != true){

			this.setSendTo(true);
		}else{
			this.setSendTo(false);
		}
		if(skipUpdateTotals != true)
		{
			this.updateCartView();
			this.updateFinalProductListing();
			this.updatePaymentMethods(true);
			this.updateShippingMethods(true);
			this.updateOrderTotals();
		}
	},
	processShippingAddress: function (skipUpdateTotals){
		var hasError = false;
		var checkoutClass = this;
		jQuery('select[name="shipping_country"], input[name="shipping_street_address"], input[name="shipping_zipcode"], input[name="shipping_city"]', jQuery('#shippingAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck(jQuery(this), false, true) == true){
				hasError = true;
			}
		});
		if (hasError == true){
			return;
		}

		this.setSendTo(true);
		if (this.shippingEnabled == true && skipUpdateTotals != true){
			this.updateShippingMethods(true);
		}
		if(skipUpdateTotals != true)
		{
			this.updateCartView();
			this.updateFinalProductListing();
			this.updatePaymentMethods(true);
			this.updateShippingMethods(true);
			this.updateOrderTotals();
		}
	},
	setCheckoutAddress: function (type, useShipping){
		var checkoutClass = this;
		var selector = '#' + type + 'Address';
		var sendMsg = 'Setting ' + (type == 'shipping' ? 'Shipping' : 'Billing') + ' Address';
		var errMsg = type + ' address';
		if (type == 'shipping' && useShipping == false){
			selector = '#billingAddress';
			sendMsg = 'Setting Shipping Address';
			errMsg = 'billing address';
		}

		action = 'setBillTo';
		if (type == 'shipping'){
			action = 'setSendTo';
		}

		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			beforeSendMsg: sendMsg,
			dataType: 'json',
			data: 'action=' + action + '&' + jQuery('*', jQuery(selector)).serialize(),
			type: 'post',
			success: function (){
			},
			errorMsg: 'There was an error updating your ' + errMsg + ', please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	setBillTo: function (){
		this.setCheckoutAddress('billing', false);
	},
	setSendTo: function (useShipping){
		this.setCheckoutAddress('shipping', useShipping);
	},
	 checkAllErrors: function(){
			var checkoutClass = this;
			var errMsg = '';
			if (jQuery('.required_icon:visible', jQuery('#billingAddress')).size() > 0){
				errMsg += 'Please fill in all required fields in "Billing Address"' + "\n";
			}
			console.log(checkoutClass);
			if (checkoutClass.billingInfoChanged == true && jQuery('.required_icon:visible', jQuery('#billingAddress')).size() <= 0 && checkoutClass.loggedIn != true){
				checkoutClass.processBillingAddress();
				checkoutClass.billingInfoChanged = false;
			}
			if (jQuery('#diffShipping').is(':checked') == true && checkoutClass.loggedIn != true){
				if (checkoutClass.shippingInfoChanged == true && jQuery('.required_icon:visible', jQuery('#shippingAddress')).size() <= 0){
				checkoutClass.processShippingAddress();
				checkoutClass.shippingInfoChanged = false;
				}
			}
			if (jQuery('.error_icon:visible', jQuery('#billingAddress')).size() > 0){
				errMsg += 'Please correct fields with errors in "Billing Address"' + "\n";
			}

			if (jQuery('#diffShipping:checked').size() > 0){
				if (jQuery('.required_icon:visible', jQuery('#shippingAddress')).size() > 0){
					errMsg += 'Please fill in all required fields in "Shipping Address"' + "\n";
				}

				if (jQuery('.error_icon:visible', jQuery('#shippingAddress')).size() > 0){
					errMsg += 'Please correct fields with errors in "Shipping Address"' + "\n";
				}
			}

			if (errMsg != ''){
				errMsg = '------------------------------------------------' + "\n" +
				'                 Address Errors                 ' + "\n" +
				'------------------------------------------------' + "\n" +
				errMsg;
			}

			if(checkoutClass.amountRemaininginTotal == true){
				if (jQuery(':radio[name="payment"]:checked').size() <= 0){
				if (jQuery('input[name="payment"]:hidden').size() <= 0){
					errMsg += '------------------------------------------------' + "\n" +
					'           Payment Selection Error              ' + "\n" +
					'------------------------------------------------' + "\n" +
					'You must select a payment method.' + "\n";
				}
			}
				}

			if (checkoutClass.shippingEnabled === true){
				if (jQuery(':radio[name="shipping"]:checked').size() <= 0){
					if (jQuery('input[name="shipping"]:hidden').size() <= 0){
						errMsg += '------------------------------------------------' + "\n" +
						'           Shipping Selection Error             ' + "\n" +
						'------------------------------------------------' + "\n" +
						'You must select a shipping method.' + "\n";
					}
				}
			}
			if(checkoutClass.ccgvInstalled == true)
			{
				if(jQuery('input[name="gv_redeem_code"]').val() == 'redeem code')
				{
					jQuery('input[name="gv_redeem_code"]').val('');
				}
			}

			if(checkoutClass.kgtInstalled == true)
			{
				if(jQuery('input[name="coupon"]').val() == 'redeem code')
				{
					jQuery('input[name="coupon"]').val('');
				}
			}

			if (errMsg.length > 0){
				checkoutClass.errors = true;
				alert(errMsg);
				return false;
			}else{
				checkoutClass.errors = false;
				return true;
			}
		},
	initCheckout: function (){
		var checkoutClass = this;
		if (this.loggedIn == false){
			jQuery('#shippingAddress').hide();
			jQuery('#shippingMethods').html('');
		}
		jQuery("#billing_country, #billing_state, #shipping_country, #shipping_state").addClass('required');
		jQuery('#checkoutNoScript').remove();
		jQuery('#checkoutYesScript').show();

		jQuery('.removeFromCart').each(function (){
			checkoutClass.addCartRemoveMethod(jQuery(this));
		});


		this.updateFinalProductListing();
		this.updateOrderTotals();

		jQuery('#diffShipping').click(function (){
			if (this.checked){
				jQuery('#shippingAddress').show();
				jQuery('#shippingMethods').html('');
				jQuery('#noShippingAddress').show();
				jQuery('select[name="shipping_country"]').trigger('change');
			}else{
				jQuery('#shippingAddress').hide();
				var errCheck = checkoutClass.processShippingAddress();
				if (errCheck == ''){
					jQuery('#noShippingAddress').hide();
				}else{
					jQuery('#noShippingAddress').show();
				}
			}
		});


		if (this.loggedIn == true){
			jQuery('.shippingRow, .paymentRow').each(function (){
				checkoutClass.addRowMethods(jQuery(this));
			});

			jQuery('input[name="payment"]').each(function (){
				jQuery(this).click(function (){
					checkoutClass.setPaymentMethod(jQuery(this));
					checkoutClass.updateOrderTotals();
				});
			});

			if (this.shippingEnabled == true){
				jQuery('input[name="shipping"]').each(function (){
					jQuery(this).click(function (){
						checkoutClass.setShippingMethod(jQuery(this));
						checkoutClass.updateOrderTotals();
					});
				});
			}
		}

		if (jQuery('#paymentMethods').is(':visible')){
			this.clickButton('payment');
		}

		if (this.shippingEnabled == true){
			if (jQuery('#shippingMethods').is(':visible')){
				this.clickButton('shipping');
			}
		}

		jQuery('input, password', jQuery('#billingAddress')).each(function (){
			if (jQuery(this).attr('name') != undefined && jQuery(this).attr('type') != 'checkbox' && jQuery(this).attr('type') != 'radio'){
				if (jQuery(this).attr('type') == 'password'){
					jQuery(this).blur(function (){
						if (jQuery(this).hasClass('required')){
							checkoutClass.fieldErrorCheck(jQuery(this));
						}
					});
					/* Used to combat firefox 3 and it's auto-populate junk */
					jQuery(this).val('');

					if (jQuery(this).attr('name') == 'password'){
						jQuery(this).focus(function (){
							jQuery(':password[name="confirmation"]').val('');
						});

						var rObj = getFieldErrorCheck(jQuery(this));
						jQuery(this).pstrength({
							addTo: '#pstrength_password',
							minchar: rObj.minLength
						});
					}
				}else{
					jQuery(this).change(function (){
										   checkoutClass.billingInfoChanged = true;
						if (jQuery(this).hasClass('required')){
							checkoutClass.fieldErrorCheck(jQuery(this));
						}
					});
					bindAutoFill(jQuery(this));
				}

				if (jQuery(this).hasClass('required')){
					checkoutClass.billingInfoChanged = true;
					if (checkoutClass.fieldErrorCheck(jQuery(this), true, true) == false){
						checkoutClass.addIcon(jQuery(this), 'success');
					}else{
						checkoutClass.addIcon(jQuery(this), 'required');
					}
				}
			}
		});
			jQuery('#updateAddressBilling').click(function (){
		checkoutClass.billingInfoChanged = false;

		var red=0;
		jQuery('input', jQuery('#billingAddress')).each(function (){

if (jQuery(this).hasClass('required') ){
		if(checkoutClass.fieldErrorCheck(jQuery(this),true) == true){
			jQuery(this).addClass('fieldRed');
			red = 1;
			}else{
				jQuery(this).removeClass('fieldRed');

				red =0;
			}
}
	});
if(red==1)
alert('A required field was left blank. It is highlighted in red, please fill it in and click update');
else
checkoutClass.processBillingAddress();
	});


		jQuery('input[name="billing_email_address"]').each(function (){
			jQuery(this).unbind('blur').change(function (){
				var $thisField = jQuery(this);
				checkoutClass.billingInfoChanged = true;
				if (checkoutClass.initializing == true){
					checkoutClass.addIcon($thisField, 'required');
				}else{
					if (checkoutClass.fieldErrorCheck($thisField, true, true) == false){
						this.changed = false;
						if($thisField.val() == '')
						{
							checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
						}
						checkoutClass.queueAjaxRequest({
							url: checkoutClass.pageLinks.checkout,
							data: 'action=checkEmailAddress&emailAddress=' + $thisField.val(),
							type: 'post',
							beforeSendMsg: 'Checking Email Address',
							dataType: 'json',
							success: function (data){
								jQuery('.success, .error', $thisField.parent()).hide();
								if (data.success == 'false'){
									checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
									alert(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
								}else{
									checkoutClass.addIcon($thisField, 'success');
								}
							},
							errorMsg: 'There was an error checking email address, please inform ' + checkoutClass.storeName + ' about this error.'
						});
					}
				}
			}).keyup(function (){
				this.changed = true;
			});
			bindAutoFill(jQuery(this));
		});

		jQuery('input', jQuery('#shippingAddress')).each(function (){
			if (jQuery(this).attr('name') != undefined && jQuery(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					checkoutClass.shippingInfoChanged = true;
					if (jQuery(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck(jQuery(this)) == false){
						}else{
							jQuery('#noShippingAddress').show();
							jQuery('#shippingMethods').hide();
						}
					}
				};

				jQuery(this).change(processAddressFunction);
				bindAutoFill(jQuery(this));

				if (jQuery(this).hasClass('required')){
					var icon = 'required';
					if (jQuery(this).val() != '' && checkoutClass.fieldErrorCheck(jQuery(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon(jQuery(this), icon);
				}
			}
		});

		jQuery('#updateAddressShipping').click(function (){
		var redalert=0;
		checkoutClass.shippingInfoChanged = false;
		jQuery('input', jQuery('#shippingAddress')).each(function (){

if (jQuery(this).hasClass('required') ){
		if(checkoutClass.fieldErrorCheck(jQuery(this)) == true){
			jQuery(this).addClass('fieldRed');
			redalert = 1;
			}else{
				jQuery(this).removeClass('fieldRed');

				redalert =0;
			}

}
	});
if(redalert==1)
alert('A required field was left blank. It is highlighted in red, please fill it in and click update');
else
checkoutClass.processShippingAddress();
													});

		if(checkoutClass.stateEnabled == true)
		{
			jQuery('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = jQuery(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
				checkoutClass.addCountryAjax(jQuery(this), fieldType + '_state', 'stateCol_' + fieldType);
			});

			jQuery('*[name="billing_zipcode"], *[name="delivery_zipcode"]').each(function (){
				var processFunction = function (){
					if (jQuery(this).attr('name') == 'delivery_zipcode'){
						checkoutClass.shippingInfoChanged = true;
						jQuery("#updateAddressShipping").click();
					}else {
						checkoutClass.billingInfoChanged = true;
						jQuery("#updateAddressBilling").click();
					}
				}

				if (jQuery(this).attr('type') == 'select-one'){
					jQuery(this).change(processFunction);
				}else{
					jQuery(this).blur(processFunction);
				}
				bindAutoFill(jQuery(this));
			});
			jQuery('*[name="billing_state"], *[name="delivery_state"]').each(function (){
				var processFunction = function (){
					if (jQuery(this).hasClass('required')){
						checkoutClass.fieldErrorCheck(jQuery(this));
					}
				}
				if (jQuery(this).attr('type') == 'select-one'){
					jQuery(this).change(processFunction);
				}else{
					jQuery(this).blur(processFunction);
				}
				bindAutoFill(jQuery(this));
			});
		}
		jQuery('#updateCartButton').click(function (){

			checkoutClass.showAjaxLoader();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=updateQuantities&' + jQuery('input', jQuery('#shoppingCart')).serialize(),
				type: 'post',
				beforeSendMsg: 'Updating Product Quantities',
				dataType: 'json',
				success: function (){

					checkoutClass.updateCartView();
					checkoutClass.updateFinalProductListing();
					if (jQuery('#noPaymentAddress:hidden').size() > 0){
						checkoutClass.updatePaymentMethods();
						checkoutClass.updateShippingMethods(true);
					}
					checkoutClass.updateOrderTotals();

				},
				errorMsg: 'There was an error updating shopping cart, please inform ' + checkoutClass.storeName + ' about this error.'
			});
			return false;
		});


		if(checkoutClass.pointsInstalled == true)
		{
			jQuery(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
				if (event.keyCode == '13') {
					if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
					{
						jQuery('input[name="customer_points"]').attr('disabled','true');
						checkoutClass.checkPoints();
						this.changed = true;
					}else
					{
						this.changed = false;
					}
					event.preventDefault();
					return false;
				}
			});

			jQuery(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
				if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
				{
					jQuery('input[name="customer_points"]').attr('disabled','true');
					checkoutClass.checkPoints();
				}else
				{
					checkoutClass.clearPoints();
				}
				return true;
			});

			jQuery(':input[name="customer_points"]').unbind('blur').blur(function() {
				if(jQuery(':checkbox[name="use_shopping_points"]').is(':checked'))
				{
					jQuery('input[name="customer_points"]').attr('disabled','true');
					checkoutClass.checkPoints();
				}
			});

		}


		jQuery('#checkoutButton').click(function() {
				return checkoutClass.checkAllErrors();

		});

		if (checkoutClass.ccgvInstalled == true){
			jQuery('input[name="gv_redeem_code"]').focus(function (){
				if (jQuery(this).val() == 'redeem code'){
					jQuery(this).val('');
				}
			});

			jQuery('#voucherRedeem').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + jQuery('input[name="gv_redeem_code"]').val(),
					type: 'post',
					beforeSendMsg: 'Validating Coupon',
					dataType: 'json',
					success: function (data){
						if (data.success == false){
							alert('Coupon is either invalid or expired.');
						}
						checkoutClass.updateOrderTotals();
					},
					errorMsg: 'There was an error redeeming coupon, please inform ' + checkoutClass.storeName + ' about this error.'
				});
				return false;
			});
			if(jQuery('input[name="cot_gv"]'))
			{
				jQuery('input[name="cot_gv"]').each(function (){
					jQuery(this).unbind('change').change(function (e){
						checkoutClass.setGV((jQuery(':checkbox[name="cot_gv"]').is(':checked'))?'on':'');
					});
				});
			}
		}
		if (checkoutClass.kgtInstalled == true){
			jQuery('input[name="coupon"]').focus(function (){
				if (jQuery(this).val() == 'coupon code'){
					jQuery(this).val('');
				}
			});
			jQuery('#voucherRedeemCoupon').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + jQuery('input[name="coupon"]').val(),
					type: 'post',
					beforeSendMsg: 'Validating Coupon',
					dataType: 'json',
					success: function (data){
						if (data.success == false){
							alert('Coupon is either invalid or expired.');

						}
						checkoutClass.updateOrderTotals(true);
					},
					errorMsg: 'There was an error redeeming coupon, please inform ' + checkoutClass.storeName + ' about this error.'
				});
				return false;
			});
		}
		this.initializing = false;
	}
}
