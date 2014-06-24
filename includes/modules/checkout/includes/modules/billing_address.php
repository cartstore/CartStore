<div id="billingAddress" style="">
<?php

if (tep_session_is_registered('customer_id'))
	{
	echo '<p><address>';
	echo tep_address_label($customer_id, $billto, true, ' ', '<br>');
	echo '</address></p>';
	}
else
	{
	if (tep_session_is_registered('onepage'))
		{
		$billingAddress = $onepage['billing'];
		$customerAddress = $onepage['customer'];
		}
?>
<table class="table-condensed">
<?php
if (ACCOUNT_GENDER == 'true' && !tep_session_is_registered('customer_id'))
	{
	$gender = $billingAddress['entry_gender'];
	if (isset($gender))
		{
		$male = ($gender == 'm') ? true : false;
		$female = ($gender == 'f') ? true : false;
		}
	else
		{
		$male = false;
		$female = false;
		}
?>
	<tr>
		<td class="main" colspan="2"><?php echo ENTRY_GENDER; ?>&nbsp;<?php echo tep_draw_radio_field('billing_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('billing_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE; ?></td>
	</tr>
<?php
	}
?>
	<tr>
		<td class="main" width="30%"><?php echo ENTRY_FIRST_NAME; ?>&nbsp;</td>
		<td class="main" width="70%"><?php echo tep_draw_input_field('billing_firstname', (isset($billingAddress) ? $billingAddress['firstname'] : ''), 'class="required" '); ?></td>
	</tr>
	<tr>
		<td class="main" width="30%"><?php echo ENTRY_LAST_NAME; ?>&nbsp;</td>
		<td class="main" width="70%"><?php echo tep_draw_input_field('billing_lastname', (isset($billingAddress) ? $billingAddress['lastname'] : ''), 'class="required" '); ?></td>
	</tr>
<?php
if (ACCOUNT_DOB == 'true' && !tep_session_is_registered('customer_id'))
	{
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_dob', (isset($customerAddress) ? $customerAddress['dob'] : ''), ''); ?></td>
	</tr>
<?php
	}
if (!tep_session_is_registered('customer_id'))
	{
?>
	<tr id="newAccountEmail">
		<td class="main" nowrap><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_email_address', (isset($customerAddress) ? $customerAddress['email_address'] : ''), 'class="required" '); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo ENTRY_TELEPHONE; ?></td>
		<td>
<?php
	if(ONEPAGE_TELEPHONE == 'True')
		echo tep_draw_input_field('billing_telephone', (isset($customerAddress) ? $customerAddress['telephone'] : ''), 'class="required" '); 
	else
		echo tep_draw_input_field('billing_telephone', (isset($customerAddress) ? $customerAddress['telephone'] : ''), ''); 
?>
		</td>
	</tr>
<?php
	}
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_COUNTRY; ?></td>
		<td class="main"><?php echo tep_get_country_list('billing_country', (isset($billingAddress) && tep_not_null($billingAddress['country_id']) ? $billingAddress['country_id'] : ONEPAGE_DEFAULT_COUNTRY), 'class="required" style="float:left;width:80%"'); ?></td>
	</tr>
<?php if (ACCOUNT_COMPANY == 'true')
	{
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_COMPANY; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_company', (isset($billingAddress) ? $billingAddress['company'] : ''), ''); ?></td>
	</tr>
<?php
	}
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_STREET_ADDRESS; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_street_address', (isset($billingAddress) ? $billingAddress['street_address'] : ''), 'class="required" '); ?></td>
	</tr>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_STREET_ADDRESS_2; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_street_address_2', (isset($billingAddress) ? $billingAddress['street_address_2'] : ''), ' '); ?></td>
	</tr>
<?php
if (ACCOUNT_SUBURB == 'true')
	{
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_SUBURB; ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'style="width:80%;float:left"'); ?></td>
	</tr>
<?php
	}
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_CITY; ?></td>
		<td class="main" ><?php echo tep_draw_input_field('billing_city', (isset($billingAddress) ? $billingAddress['city'] : ''), 'class="required" '); ?></td>
	</tr>
<?php
if (ACCOUNT_STATE == 'true')
	{
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_STATE; ?></td>
<?php
		$defaultCountry = (isset($billingAddress) && tep_not_null($billingAddress['country_id']) ? $billingAddress['country_id'] : ONEPAGE_DEFAULT_COUNTRY);
?>
		<td class="main" id="stateCol_billing"><?php echo $onePageCheckout->getAjaxStateField($defaultCountry);?></td>
	</tr>
<?php
	}
?>
	<tr>
		<td class="main" nowrap><?php echo ENTRY_POST_CODE ?></td>
		<td class="main"><?php echo tep_draw_input_field('billing_zipcode', (isset($billingAddress) ? $billingAddress['postcode'] : ''), 'class="required" '); ?></td>
	</tr>
<?php if(!tep_session_is_registered('customer_id'))
	{
?>
	<tr>
		<td colspan="2"><p class="text-info">After entering address info, click here <?php echo '<span class="btn btn-primary btn-sm" id="updateAddressBilling">Update</span>';?> to select shipping & payment method</p></td>
	</tr>
<?php
	if (ONEPAGE_ACCOUNT_CREATE != 'required')
		{
?>
		<tr>
			<td colspan="2" class="main"><p>If you would like to create an account please enter a password below</p></td>
		</tr>
<?php
		}
	if(ONEPAGE_ADDR_LAYOUT == 'vertical')
		{
?>
		<tr>
			<td class="main"><?php echo ENTRY_PASSWORD; ?></td>
			<td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo tep_draw_password_field('password', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" maxlength="40" ' : 'maxlength="40" ') . 'style="float:left;"'); ?></td>
			<td class="main"><?php echo tep_draw_password_field('confirmation', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" ' : '') . 'maxlength="40" style="float:left;"'); ?></td>
		</tr>
<?php
		}
	elseif(ONEPAGE_ADDR_LAYOUT == 'horizontal')
		{
?>
		<tr>
			<td class="main"><?php echo ENTRY_PASSWORD; ?></td>
			<td class="main"><?php echo tep_draw_password_field('password', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" maxlength="40" ' : 'maxlength="40" ') . 'style="float:left;"'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
			<td class="main"><?php echo tep_draw_password_field('confirmation', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="required" ' : '') . 'maxlength="40" style="float:left;"'); ?></td>
		</tr>
<?php
		}
?>
	<tr>
		<td class="main" colspan="2"><div id="pstrength_password"></div></td>
	</tr>
	<tr>
		<td class="main" colspan="2"><div class="checkbox newsletter">
  <label><?php echo ENTRY_NEWSLETTER; ?> </label> <?php echo tep_draw_checkbox_field('billing_newsletter', '1', (isset($customerAddress) && $customerAddress['newsletter'] == '1' ? true : false)); ?></div></td>
	</tr>
<?php
	}
?>
</table>
<?php
	}
?>
</div>
