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
			if ($(event.srcElement).data('hasFocus') && $(event.srcElement).data('hasFocus') == 'true') return;
			if ($(event.srcElement).val() != '' && $(event.srcElement).hasClass('required')){
				$(event.srcElement).trigger(method);
			}
		});
	}else{
		$el.get(0).addEventListener('onattrmodified', function (e){
			if ($(e.currentTarget).data('hasFocus') && $(e.currentTarget).data('hasFocus') == 'true') return;
			if ($(e.currentTarget).val() != '' && $(e.currentTarget).hasClass('required')){
				$(e.currentTarget).trigger(method);
			}
		}, false);
	}
}

function setFocus(){
	$(this).data('hasFocus', 'true');
}

function unsetFocus(){
	$(this).data('hasFocus', 'false');
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
	showAjaxLoader: function ()
		{
		$('#ajaxMessages').dialog('open');
		$('#ajaxLoader').show();
		},
	hideAjaxLoader: function ()
		{
		$('#ajaxLoader').hide();
		$('#ajaxMessages').dialog('close');
		},
	showAjaxMessage: function (message){
			$('#checkoutButtonContainer').hide();
		$('#ajaxMessages').show().html('<center>Loading....<br><img src="includes/modules/checkout/images/ajax_load.gif"><br>' + message + '</center>');
	},
	hideAjaxMessage: function (){
		$('#checkoutButtonContainer').show();
		$('#ajaxMessages').hide();
		
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
					if ($element.val() != $(':password[name="password"]', $('#billingAddress')).val() || $element.val().length <= 0){
						hasError = true;
					}
				}
				break;
				case 'radio':
				if ($(':radio[name="' + $element.attr('name') + '"]:checked').size() <= 0){
					hasError = true;
				}
				break;
				case 'checkbox':
				if ($(':checkbox[name="' + $element.attr('name') + '"]:checked').size() <= 0){
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
		$('.success_icon, .error_icon, .required_icon', $curField.parent()).hide();
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
		if ($('.success_icon', $curField.parent()).size() <= 0){
			$curField.parent().append(this.fieldSuccessHTML);
		}
		$('.success_icon', $curField.parent()).attr('title', title).show();
	},
	addErrorIcon: function ($curField, title){
		if ($('.error_icon', $curField.parent()).size() <= 0){
			$curField.parent().append(this.fieldErrorHTML);
		}
		$('.error_icon', $curField.parent()).attr('title', title).show();
	},
	addRequiredIcon: function ($curField, title){
		if ($curField.hasClass('required')){
			if ($('.required_icon', $curField.parent()).size() <= 0){
				$curField.parent().append(this.fieldRequiredHTML);
			}
			$('.required_icon', $curField.parent()).attr('title', title).show();
		}
	},
	clickButton: function (elementName){
		if ($(':radio[name="' + elementName + '"]').size() <= 0){
			$('input[name="' + elementName + '"]').trigger('click', true);
		}else{
			$(':radio[name="' + elementName + '"]:checked').trigger('click', true);
		}
	},
	addRowMethods: function($row){
		$row.hover(function (){
			if (!$(this).hasClass('moduleRowSelected')){
				$(this).addClass('moduleRowOver');
			}
		}, function (){
			if (!$(this).hasClass('moduleRowSelected')){
				$(this).removeClass('moduleRowOver');
			}
		}).click(function (){ 
			if (!$(this).hasClass('moduleRowSelected')){
				var selector = ($(this).hasClass('shippingRow') ? '.shippingRow' : '.paymentRow') + '.moduleRowSelected';
				$(selector).removeClass('moduleRowSelected');
				$(this).removeClass('moduleRowOver').addClass('moduleRowSelected');
				if($(':radio', $(this)).is(':disabled')!==true)
				if (!$(':radio', $(this)).is(':checked')){
					$(':radio', $(this)).attr('checked', 'checked').click();
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
							var buttonConfirmOrder = $('.ui-dialog-buttonpane button:first');
							buttonConfirmOrder.removeClass('ui-state-disabled');
							$('#imgDlgLgr').hide();
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
		$.ajaxq('orderUpdate', o);
	},
	updateAddressHTML: function (type){
		var checkoutClass = this;
		this.queueAjaxRequest({
			url: this.pageLinks.checkout,
			data: "action=" + (type == "shipping" ? "getShippingAddress" : "getBillingAddress"),
			type: "post",
			beforeSendMsg: "Updating " + (type == "shipping" ? "Shipping" : "Billing") + " Address",
			success: function (data){
				$('#' + type + 'Address').html(data);
			},
			errorMsg: 'There was an error loading your ' + type + ' address, please inform ' + checkoutClass.storeName + ' about this error.'
		});
	},
	attachAddressFields: function(){
		var checkoutClass = this;
		$('input', $('#billingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
				$(this).blur(function (){
					
					if ($(this).hasClass('required')){
						checkoutClass.fieldErrorCheck($(this));
						
					}
				});
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
						checkoutClass.addIcon($(this), 'success');
					}else{
						$('input').addClass('fieldRed');
						checkoutClass.addIcon($(this), 'required');
					}
				}
			}
		});

		$('input,select[name="billing_country"], ', $('#billingAddress')).each(function (){
			var processFunction = function (){
				checkoutClass.billingInfoChanged = true;
				if ($(this).hasClass('required')){
					if (checkoutClass.fieldErrorCheck($(this)) == false){
						checkoutClass.processBillingAddress(true);
					}
				}else{
					checkoutClass.processBillingAddress(true);
				}
			};
			
			$(this).unbind('blur');
			if ($(this).attr('type') == 'select-one'){
				$(this).change(processFunction);
			}else{
				$(this).blur(processFunction);
			}
			bindAutoFill($(this));
		});
		$('input,select[name="shipping_country"]', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					checkoutClass.shippingInfoChanged = true;
					
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							checkoutClass.processShippingAddress();
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}else{
						checkoutClass.processShippingAddress();
					}
				};
			
				$(this).blur(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});
		if(checkoutClass.stateEnabled == true)
		{

			$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = $(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
				checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);
	
			});

			$('*[name="billing_zipcode"], *[name="delivery_zipcode"]').each(function (){
				var processAddressFunction = checkoutClass.processBillingAddress;
				if ($(this).attr('name') == 'delivery_zipcode'){
					processAddressFunction = checkoutClass.processShippingAddress;
				}
				var processFunction = function (){
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
							processAddressFunction.call(checkoutClass);
						}
					}else{
						processAddressFunction.call(checkoutClass);
					}
				}
			
				if ($(this).attr('type') == 'select-one'){
					$(this).change(processFunction);
				}else{
					$(this).blur(processFunction);
				}
				bindAutoFill($(this));
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
					$('#shoppingCart').html(data);

					$('.removeFromCart').each(function (){
						checkoutClass.addCartRemoveMethod($(this));
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
				$('.finalProducts').html(data);
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
				$('.orderTotals').html(data);
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
		  		$('#paymentMethods input:radio').attr('disabled',true);
				}else{
				checkoutClass.amountRemaininginTotal=true;
				$('#paymentMethods input:radio').attr('disabled',false);
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
				$('#pointsSection').html(data);
					if($(':input[name="customer_points"]',$(this)))
					{
						$(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
							if (event.keyCode == '13') {
								if($(':checkbox[name="use_shopping_points"]').is(':checked'))
								{
									$('input[name="customer_points"]').attr('disabled','true');
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
						$(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
							if($(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								$('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}else
							{
								checkoutClass.clearPoints();
							}
							return true;
						});
						
						$(':input[name="customer_points"]').unbind('blur').blur(function() {
							if($(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								$('input[name="customer_points"]').attr('disabled','true');
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
			data: 'action=redeemPoints&points=' + $('input[name="customer_points"]').val(),
			type: 'post',
			beforeSendMsg: 'Validating Points',
			dataType: 'json',
			success: function (data){
				if (data.success == false){
					alert('You do not have ' + $('input[name="customer_points"]').val() + ' points please enter a valid number of points');
				}
				$('input[name="customer_points"]').removeAttr('disabled');
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
				$('#no' + descText + 'Address').hide();
				$('#' + action + 'Methods').html(data).show();
				if(action == 'payment')
				{ 
					if($('input[name="cot_gv"]', $('#paymentMethods')))
					{
						$('input[name="cot_gv"]', $('#paymentMethods')).each(function (){
							$(this).unbind('change').change(function (e){
								checkoutClass.setGV(($(':checkbox[name="cot_gv"]').is(':checked'))?'on':'');
							});
						});
					}
					if($(':input[name="customer_points"]',$(this)))
					{
						$(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
							if (event.keyCode == '13') {
								if($(':checkbox[name="use_shopping_points"]').is(':checked'))
								{
									$('input[name="customer_points"]').attr('disabled','true');
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
						$(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
							if($(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								$('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}else
							{
								checkoutClass.clearPoints();
							}
							return true;
						});
						
						$(':input[name="customer_points"]').unbind('blur').blur(function() {
							if($(':checkbox[name="use_shopping_points"]').is(':checked'))
							{
								$('input[name="customer_points"]').attr('disabled','true');
								checkoutClass.checkPoints();
							}
						});
						
					}
					
				}
				$('.' + action + 'Row').each(function (){
					checkoutClass.addRowMethods($(this));

					$('input[name="' + action + '"]', $(this)).each(function (){
						var setMethod = checkoutClass.setPaymentMethod;
						if (action == 'shipping'){
							setMethod = checkoutClass.setShippingMethod;
						}
						$(this).click(function (e, noOrdertotalUpdate){
							setMethod.call(checkoutClass, $(this));
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
			
			$('.paymentFields').remove();
			if (data.inputFields != ''){
				$(data.inputFields).insertAfter($button.parent().parent());
				$('input,select,radio','.paymentFields').each( function ()
				{
					if(paymentVals[$(this).attr('name')])
					{
						$(this).val(paymentVals[$(this).attr('name')]);
					}
					$(this).blur(function (){
						paymentVals[$(this).attr('name')] = $(this).val();
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
		$input.change(function (event, callBack){
			var thisName = $(this).attr('name');
			
			if (thisName == 'shipping_country')
			{
				checkoutClass.shippingInfoChanged = true;
			}else
			{
				checkoutClass.billingInfoChanged = true;
			}

			if ($(this).hasClass('required')){
				if ($(this).val() != '' && $(this).val() > 0){
					checkoutClass.addIcon($(this), 'success');
				}
			}
			
			var $origStateField = $('*[name="' + fieldName + '"]', $('#' + stateCol));
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=countrySelect&fieldName=' + fieldName + '&cID=' + $(this).val() + '&curValue=' + $origStateField.val(),
				type: 'post',
				beforeSendMsg: 'Getting Country\'s Zones',
				success: function (data){
					$('#' + stateCol).html(data);
					var $curField = $('*[name="' + fieldName + '"]', $('#' + stateCol));

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
						if ($(this).hasClass('required')){
							if (checkoutClass.fieldErrorCheck($(this)) == false){
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
			var $productRow = $(this).parent().parent();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: $(this).attr('linkData'),
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
		$('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], *[name="billing_state"]', $('#billingAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
				hasError = true;
			}
		});
		if (hasError == true){
			return;
		}

		this.setBillTo();
		if ($('#diffShipping').checked && this.loggedIn != true){

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
		$('select[name="shipping_country"], input[name="shipping_street_address"], input[name="shipping_zipcode"], input[name="shipping_city"]', $('#shippingAddress')).each(function (){
			if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
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
			data: 'action=' + action + '&' + $('*', $(selector)).serialize(),
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
			if ($('.required_icon:visible', $('#billingAddress')).size() > 0){
				errMsg += 'Please fill in all required fields in "Billing Address"' + "\n";
			}
			console.log(checkoutClass);
			if (checkoutClass.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).size() <= 0 && checkoutClass.loggedIn != true){
				checkoutClass.processBillingAddress();
				checkoutClass.billingInfoChanged = false;
			}
			if ($('#diffShipping').is(':checked') == true && checkoutClass.loggedIn != true){
				if (checkoutClass.shippingInfoChanged == true && $('.required_icon:visible', $('#shippingAddress')).size() <= 0){
				checkoutClass.processShippingAddress();
				checkoutClass.shippingInfoChanged = false;
				}
			}
			if ($('.error_icon:visible', $('#billingAddress')).size() > 0){
				errMsg += 'Please correct fields with errors in "Billing Address"' + "\n";
			}

			if ($('#diffShipping:checked').size() > 0){
				if ($('.required_icon:visible', $('#shippingAddress')).size() > 0){
					errMsg += 'Please fill in all required fields in "Shipping Address"' + "\n";
				}

				if ($('.error_icon:visible', $('#shippingAddress')).size() > 0){
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
				if ($(':radio[name="payment"]:checked').size() <= 0){
				if ($('input[name="payment"]:hidden').size() <= 0){
					errMsg += '------------------------------------------------' + "\n" +
					'           Payment Selection Error              ' + "\n" +
					'------------------------------------------------' + "\n" +
					'You must select a payment method.' + "\n";
				}
			}
				}

			if (checkoutClass.shippingEnabled === true){
				if ($(':radio[name="shipping"]:checked').size() <= 0){
					if ($('input[name="shipping"]:hidden').size() <= 0){
						errMsg += '------------------------------------------------' + "\n" +
						'           Shipping Selection Error             ' + "\n" +
						'------------------------------------------------' + "\n" +
						'You must select a shipping method.' + "\n";
					}
				}
			}
			if(checkoutClass.ccgvInstalled == true)
			{
				if($('input[name="gv_redeem_code"]').val() == 'redeem code')
				{
					$('input[name="gv_redeem_code"]').val('');
				}
			}

			if(checkoutClass.kgtInstalled == true)
			{
				if($('input[name="coupon"]').val() == 'redeem code')
				{
					$('input[name="coupon"]').val('');
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
			$('#shippingAddress').hide();
			$('#shippingMethods').html('');
		}

		$('#checkoutNoScript').remove();
		$('#checkoutYesScript').show();

		$('.removeFromCart').each(function (){
			checkoutClass.addCartRemoveMethod($(this));
		});


		this.updateFinalProductListing();
		this.updateOrderTotals();

		$('#diffShipping').click(function (){
			if (this.checked){
				$('#shippingAddress').show();
				$('#shippingMethods').html('');
				$('#noShippingAddress').show();
				$('select[name="shipping_country"]').trigger('change');
			}else{
				$('#shippingAddress').hide();
				var errCheck = checkoutClass.processShippingAddress();
				if (errCheck == ''){
					$('#noShippingAddress').hide();
				}else{
					$('#noShippingAddress').show();
				}
			}
		});


		if (this.loggedIn == true){
			$('.shippingRow, .paymentRow').each(function (){
				checkoutClass.addRowMethods($(this));
			});

			$('input[name="payment"]').each(function (){
				$(this).click(function (){
					checkoutClass.setPaymentMethod($(this));
					checkoutClass.updateOrderTotals();
				});
			});

			if (this.shippingEnabled == true){
				$('input[name="shipping"]').each(function (){
					$(this).click(function (){
						checkoutClass.setShippingMethod($(this));
						checkoutClass.updateOrderTotals();
					});
				});
			}
		}

		if ($('#paymentMethods').is(':visible')){
			this.clickButton('payment');
		}

		if (this.shippingEnabled == true){
			if ($('#shippingMethods').is(':visible')){
				this.clickButton('shipping');
			}
		}

		$('input, password', $('#billingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
				if ($(this).attr('type') == 'password'){
					$(this).blur(function (){
						if ($(this).hasClass('required')){
							checkoutClass.fieldErrorCheck($(this));
						}
					});
					/* Used to combat firefox 3 and it's auto-populate junk */
					$(this).val('');

					if ($(this).attr('name') == 'password'){
						$(this).focus(function (){
							$(':password[name="confirmation"]').val('');
						});

						var rObj = getFieldErrorCheck($(this));
						$(this).pstrength({
							addTo: '#pstrength_password',
							minchar: rObj.minLength
						});
					}
				}else{
					$(this).change(function (){
										   checkoutClass.billingInfoChanged = true;
						if ($(this).hasClass('required')){
							checkoutClass.fieldErrorCheck($(this));
						}
					});
					bindAutoFill($(this));
				}

				if ($(this).hasClass('required')){
					checkoutClass.billingInfoChanged = true;
					if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
						checkoutClass.addIcon($(this), 'success');
					}else{
						checkoutClass.addIcon($(this), 'required');
					}
				}
			}
		});
			$('#updateAddressBilling').click(function (){ 
		checkoutClass.billingInfoChanged = false;
		
		var red=0;
		$('input', $('#billingAddress')).each(function (){

if ($(this).hasClass('required') ){
		if(checkoutClass.fieldErrorCheck($(this),true) == true){
			$(this).addClass('fieldRed');
			red = 1;
			}else{
				$(this).removeClass('fieldRed');
				
				red =0;
			}
}
	});													
if(red==1) 
alert('A required field was left blank. It is highlighted in red, please fill it in and click update');
else
checkoutClass.processBillingAddress();
	});
		
		
		$('input[name="billing_email_address"]').each(function (){
			$(this).unbind('blur').change(function (){
				var $thisField = $(this);
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
								$('.success, .error', $thisField.parent()).hide();
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
			bindAutoFill($(this));
		});
		
		$('input', $('#shippingAddress')).each(function (){
			if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox'){
				var processAddressFunction = function (){
					checkoutClass.shippingInfoChanged = true;
					if ($(this).hasClass('required')){
						if (checkoutClass.fieldErrorCheck($(this)) == false){
						}else{
							$('#noShippingAddress').show();
							$('#shippingMethods').hide();
						}
					}
				};
			
				$(this).change(processAddressFunction);
				bindAutoFill($(this));

				if ($(this).hasClass('required')){
					var icon = 'required';
					if ($(this).val() != '' && checkoutClass.fieldErrorCheck($(this), true, true) == false){
						icon = 'success';
					}
					checkoutClass.addIcon($(this), icon);
				}
			}
		});
		
		$('#updateAddressShipping').click(function (){ 
		var redalert=0;
		checkoutClass.shippingInfoChanged = false;
		$('input', $('#shippingAddress')).each(function (){

if ($(this).hasClass('required') ){
		if(checkoutClass.fieldErrorCheck($(this)) == true){
			$(this).addClass('fieldRed');
			redalert = 1;
			}else{
				$(this).removeClass('fieldRed');
				
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
			$('select[name="shipping_country"], select[name="billing_country"]').each(function (){
				var $thisName = $(this).attr('name');
				var fieldType = 'billing';
				if ($thisName == 'shipping_country'){
					fieldType = 'delivery';
				}
				checkoutClass.addCountryAjax($(this), fieldType + '_state', 'stateCol_' + fieldType);
			});
		
			$('*[name="billing_zipcode"], *[name="delivery_zipcode"]').each(function (){
				var processFunction = function (){
					if ($(this).attr('name') == 'delivery_zipcode'){
						checkoutClass.shippingInfoChanged = true;
						$("#updateAddressShipping").click();
					}else {
						checkoutClass.billingInfoChanged = true;
						$("#updateAddressBilling").click();
					}
				}
			
				if ($(this).attr('type') == 'select-one'){
					$(this).change(processFunction);
				}else{
					$(this).blur(processFunction);
				}
				bindAutoFill($(this));
			});
			$('*[name="billing_state"], *[name="delivery_state"]').each(function (){
				var processFunction = function (){
					if ($(this).hasClass('required')){
						checkoutClass.fieldErrorCheck($(this));
					}
				}			
				if ($(this).attr('type') == 'select-one'){
					$(this).change(processFunction);
				}else{
					$(this).blur(processFunction);
				}
				bindAutoFill($(this));
			});
		}
		$('#updateCartButton').click(function (){
		
			checkoutClass.showAjaxLoader();
			checkoutClass.queueAjaxRequest({
				url: checkoutClass.pageLinks.checkout,
				data: 'action=updateQuantities&' + $('input', $('#shoppingCart')).serialize(),
				type: 'post',
				beforeSendMsg: 'Updating Product Quantities',
				dataType: 'json',
				success: function (){
					
					checkoutClass.updateCartView();
					checkoutClass.updateFinalProductListing();
					if ($('#noPaymentAddress:hidden').size() > 0){
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
			$(':input[name="customer_points"]').unbind('keypress').keypress(function(event){
				if (event.keyCode == '13') {
					if($(':checkbox[name="use_shopping_points"]').is(':checked'))
					{
						$('input[name="customer_points"]').attr('disabled','true');
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

			$(':checkbox[name="use_shopping_points"]').unbind('click').click(function() {
				if($(':checkbox[name="use_shopping_points"]').is(':checked'))
				{
					$('input[name="customer_points"]').attr('disabled','true');
					checkoutClass.checkPoints();
				}else
				{
					checkoutClass.clearPoints();
				}
				return true;
			});
			
			$(':input[name="customer_points"]').unbind('blur').blur(function() {
				if($(':checkbox[name="use_shopping_points"]').is(':checked'))
				{
					$('input[name="customer_points"]').attr('disabled','true');
					checkoutClass.checkPoints();
				}
			});
			
		}

		
		$('#checkoutButton').click(function() {
				return checkoutClass.checkAllErrors();
											
		});

		if (checkoutClass.ccgvInstalled == true){
			$('input[name="gv_redeem_code"]').focus(function (){
				if ($(this).val() == 'redeem code'){
					$(this).val('');
				}
			});

			$('#voucherRedeem').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + $('input[name="gv_redeem_code"]').val(),
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
			if($('input[name="cot_gv"]'))
			{
				$('input[name="cot_gv"]').each(function (){
					$(this).unbind('change').change(function (e){
						checkoutClass.setGV(($(':checkbox[name="cot_gv"]').is(':checked'))?'on':'');
					});
				});
			}
		}
		if (checkoutClass.kgtInstalled == true){
			$('input[name="coupon"]').focus(function (){
				if ($(this).val() == 'coupon code'){
					$(this).val('');
				}
			});
			$('#voucherRedeemCoupon').click(function (){
				checkoutClass.queueAjaxRequest({
					url: checkoutClass.pageLinks.checkout,
					data: 'action=redeemVoucher&code=' + $('input[name="coupon"]').val(),
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
