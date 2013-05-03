<?php
  /*
  $Id: edit_orders.php for MVS, v2.6.4 2006/10/15 10:42:44 ams Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  Multi Vendor Shipping is the brainchild of Craig Garrison Sr. and Jim Keebaugh
    http://www.cartstore.com/community/contributions,4129

  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
    http://www.cartstore.com/community/contributions,1435

  Order Editor for MVS by djmonkey1

*/

  // First things first: get the required includes, classes, etc.
   require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

  //set a default tax class
  //shipping tax is added to the default tax class
  $default_tax_class = 1;

   //check for MVS
   $this_is_an_mvs_order = false;
   $check_for_mvs_query = tep_db_query("
   SELECT orders_shipping_id
   FROM " . TABLE_ORDERS_SHIPPING . "
  WHERE orders_id = '" . $_GET['oID'] . "'");

	  while ($checked_data = tep_db_fetch_array($check_for_mvs_query)) {
      	$mvs_shipping_id = $checked_data['orders_shipping_id'];
      	      	}

         if (tep_not_null($mvs_shipping_id)) {
				 $this_is_an_mvs_order = true;
				 }

		 //end check for MVS

 // Then we get down to the nitty gritty
   $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  // Update Inventory Quantity
  if (tep_not_null($action)) {
    switch ($action) {

	// 1. UPDATE ORDER ###############################################################################################
	case 'update_order':
		$oID = tep_db_prepare_input($_GET['oID']);
		$order = new order($oID);
		$status = tep_db_prepare_input($_POST['status']);
        $quickbooksid = tep_db_prepare_input($_POST['quickbooksid']);
		//tax business
		//Following three functions are defined in includes/functions/general.php
		$countryid = tep_get_country_id($_POST['update_delivery_country']);
		$zoneid = tep_get_zone_id($countryid, $_POST['update_delivery_state']);
		$default_tax_name  = tep_get_tax_description($default_tax_class, $countryid, $zoneid);

		//figure out the new currency value
		$currency_value_query = tep_db_query("SELECT
		value FROM " . TABLE_CURRENCIES . " WHERE code = '" . $_POST['update_info_payment_currency'] . "'");
		$currency_value = tep_db_fetch_array($currency_value_query);


		// 1.1 UPDATE ORDER INFO #####

		$UpdateOrders = "UPDATE " . TABLE_ORDERS . " SET
	    customers_name = '" . tep_db_input(stripslashes($_POST['update_customer_name'])) . "',
	    customers_company = '" . tep_db_input(stripslashes($_POST['update_customer_company'])) . "',
	    customers_street_address = '" .
		tep_db_input(stripslashes($_POST['update_customer_street_address'])) . "',

	    customers_suburb = '" . tep_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
	    customers_city = '" . tep_db_input(stripslashes($_POST['update_customer_city'])) . "',
	    customers_state = '" . tep_db_input(stripslashes($_POST['update_customer_state'])) . "',
	    customers_postcode = '" . tep_db_input($_POST['update_customer_postcode']) . "',
	    customers_country = '" . tep_db_input(stripslashes($_POST['update_customer_country'])) . "',
		customers_telephone = '" . tep_db_input($_POST['update_customer_telephone']) . "',
	    customers_email_address = '" . tep_db_input($_POST['update_customer_email_address']) . "',";

		$UpdateOrders .= "
		billing_name = '" . tep_db_input(stripslashes($_POST['update_billing_name'])) . "',
		billing_company = '" . tep_db_input(stripslashes($_POST['update_billing_company'])) . "',
	    billing_street_address = '" .
		tep_db_input(stripslashes($_POST['update_billing_street_address'])) . "',

		billing_suburb = '" . tep_db_input(stripslashes($_POST['update_billing_suburb'])) . "',
		billing_city = '" . tep_db_input(stripslashes($_POST['update_billing_city'])) . "',
		billing_state = '" . tep_db_input(stripslashes($_POST['update_billing_state'])) . "',
		billing_postcode = '" . tep_db_input($_POST['update_billing_postcode']) . "',
		billing_country = '" . tep_db_input(stripslashes($_POST['update_billing_country'])) . "',";

 if (!$this_is_an_mvs_order) {//shipping_tax is only an input to the orders table on a non-MVS order
		$UpdateOrders .= "shipping_tax = '" . tep_db_input($_POST['update_shipping_tax']) . "',";
		}

		$UpdateOrders .= "
		delivery_name = '" . tep_db_input(stripslashes($_POST['update_delivery_name'])) . "',
		delivery_company = '" . tep_db_input(stripslashes($_POST['update_delivery_company'])) . "',
		delivery_street_address = '" .
		tep_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',

		delivery_suburb = '" . tep_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
		delivery_city = '" . tep_db_input(stripslashes($_POST['update_delivery_city'])) . "',
		delivery_state = '" . tep_db_input(stripslashes($_POST['update_delivery_state'])) . "',
		delivery_postcode = '" . tep_db_input($_POST['update_delivery_postcode']) . "',
		delivery_country = '" . tep_db_input(stripslashes($_POST['update_delivery_country'])) . "',
		payment_method = '" . tep_db_input(stripslashes($_POST['update_info_payment_method'])) . "',
	    currency = '" . tep_db_input($_POST['update_info_payment_currency']) . "',
	    currency_value = '" . tep_db_input($currency_value['value']) . "',
		cc_type = '" . tep_db_input($_POST['update_info_cc_type']) . "',
		cc_owner = '" . tep_db_input($_POST['update_info_cc_owner']) . "',
		cc_number = '" . tep_db_input($_POST['update_info_cc_number']) . "',
		cc_expires = '" . tep_db_input($_POST['update_info_cc_expires']) . "',
        quickbooksid = '" . $quickbooksid . "'";

		$UpdateOrders .= " where orders_id = '" . tep_db_input($_GET['oID']) . "';";

		tep_db_query($UpdateOrders);
		$order_updated = true;

    // 1.2 UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

    $check_status_query = tep_db_query("SELECT
	customers_name, customers_email_address, orders_status, date_purchased
	FROM " . TABLE_ORDERS . " WHERE orders_id = '" . (int)$oID . "'");
    $check_status = tep_db_fetch_array($check_status_query);

  if (($check_status['orders_status'] != $_POST['status']) || (tep_not_null($_POST['comments']))) {

        tep_db_query("UPDATE " . TABLE_ORDERS . " SET
					  orders_status = '" . tep_db_input($_POST['status']) . "',
                      last_modified = now()
                      WHERE orders_id = '" . (int)$oID . "'");

		 // Notify Customer ?
      $customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
			  $notify_comments = '';
			  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
			  }

			  $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
			  $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE2);
			  tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  $customer_notified = '1';
			}

			tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . "
			(orders_id, orders_status_id, date_added, customer_notified, comments)
			values ('" . tep_db_input($_GET['oID']) . "',
				'" . tep_db_input($_POST['status']) . "',
				now(),
				" . tep_db_input($customer_notified) . ",
				'" . tep_db_input($_POST['comments'])  . "')");
			}

	// 1.3 UPDATE PRODUCTS #####
		$RunningSubTotal = 0;
		$RunningTax = array($default_tax_name => 0);

    // Do pre-check for subtotal field existence
		$ot_subtotal_found = false;
		$ot_total_found = false;
		if (is_array ($_POST['update_totals'])) {
	foreach($_POST['update_totals'] as $total_details) {
		  extract($total_details,EXTR_PREFIX_ALL,"ot");
			if($ot_class == "ot_subtotal") {
			  $ot_subtotal_found = true;
    	break;
			}

			if($ot_class == "ot_total"){
			$ot_total_found = true;
			break;
			}
		}//end foreach()
		}//end if (is_array())

		// 1.3.1 Update orders_products Table

		////////////////////////////MVS
		//1.3.1.1 Update orders_shipping table
		//if there are no products associated with the vendor, we delete the vendor
		//but, as of right now, not before we give Admin the chance to send out a new vendors email
		if ($this_is_an_mvs_order) {
		if (is_array ($_POST['update_shipping'])) {
		foreach ($_POST['update_shipping'] as $orders_shipping_id => $shipping_details) {


		if (isset($delete_this_vendor_check)) unset($delete_this_vendor_check);
		$save_this_vendor = false;
		$delete_this_vendor_query = tep_db_query("
		SELECT orders_products_id
		FROM " . TABLE_ORDERS_PRODUCTS . "
		WHERE vendors_id = '" . $shipping_details["vendors_id"] . "'
		AND orders_id = '" . (int)$oID . "'");
		while ($delete_this_vendor_result = tep_db_fetch_array($delete_this_vendor_query)) {
		       $delete_this_vendor_check = $delete_this_vendor_result['orders_products_id'];
			   }
			    if (tep_not_null($delete_this_vendor_check)) {
				//then there is something in the orders_products table
				$save_this_vendor = true;
				}

		if ($save_this_vendor) {

		$Query = "UPDATE " . TABLE_ORDERS_SHIPPING . " SET
					shipping_method = '" . $shipping_details["method"] . "',
					shipping_tax = '" . $shipping_details["tax"] . "',
					shipping_cost = '" . $shipping_details["cost"] . "',
					shipping_module = '" . $shipping_details["module"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_shipping_id = '$orders_shipping_id';";
				tep_db_query($Query);

	//we only add tax to the sum if there are products for the vendor
	if (DISPLAY_PRICE_WITH_TAX == 'true') {
	$RunningTax[$default_tax_name] += $shipping_details["tax"] * $shipping_details["cost"] / ($shipping_details["tax"] + 100);
		} else {
	$RunningTax[$default_tax_name] += ($shipping_details["tax"] / 100) * $shipping_details["cost"];
		}//end if DISPLAY_PRICE_WITH_TAX == true


		} else { //if we don't update, we delete!
		//this only works for the first vendor in the array?

		$Query = " DELETE FROM " . TABLE_ORDERS_SHIPPING . "
		           WHERE orders_id = '" . (int)$oID . "'
				   AND vendors_id = '" . $shipping_details["vendors_id"] . "'
		           AND orders_shipping_id = '" . $orders_shipping_id . "';";
				   tep_db_query($Query);

		}//end if ($delete_this_vendor[$orders_shipping_id])
		}//end foreach ($_POST['update_shipping'] as $orders_shipping_id => $shipping_details)
		}//end if (is_array ($_POST['update_shipping']))
		}//end if ($this_is_an_mvs_order)

		/////////////////////////////end MVS

		if (is_array ($_POST['update_products'])){
		foreach($_POST['update_products'] as $orders_products_id => $products_details)	{
		if (!tep_not_null($products_details["qty"])) $products_details["qty"] = 0;

			// 1.3.1.1 Update Inventory Quantity
			$order_query = tep_db_query("SELECT products_id, products_quantity
			FROM " . TABLE_ORDERS_PRODUCTS . "
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '$orders_products_id'");
			$order = tep_db_fetch_array($order_query);

			// First we do a stock check

			if ($products_details["qty"] != $order['products_quantity']){
			$quantity_difference = ($products_details["qty"] - $order['products_quantity']);
				if (STOCK_LIMITED == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET
					products_quantity = products_quantity - " . $quantity_difference . ",
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
				}
			}

			 //Then we check if the product should be deleted
			 if (isset($products_details['delete'])){
			 //update quantities first
			 if (STOCK_LIMITED == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET
					products_quantity = products_quantity + " . $products_details["qty"] . ",
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					}

            //then delete the little bugger
			$Query = "DELETE FROM " . TABLE_ORDERS_PRODUCTS . "
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);

				// and all its attributes
				if(isset($products_details[attributes]))
				{
				$Query = "DELETE FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
				WHERE orders_id = '" . (int)$oID . "'
				AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);

                $Query2 = "DELETE FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "
                WHERE orders_id = '" . (int)$oID . "'
                AND orders_products_id = '$orders_products_id';";
                tep_db_query($Query2);
				}



			}// end of if (isset($products_details['delete']))

			   else { // if we don't delete, we update
				$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					products_model = '" . $products_details["model"] . "',
					products_name = '" . tep_html_quotes($products_details["name"]) . "',
					products_price = '" . $products_details["price"] . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);

   				//update subtotal and total during update function
				if (DISPLAY_PRICE_WITH_TAX == 'true') {
				$RunningSubTotal += (($products_details['tax']/100 + 1) * ($products_details['qty'] * $products_details['final_price']));
				} else {
				$RunningSubTotal += $products_details["qty"] * $products_details["final_price"];
				}

				$RunningTax[$products_details['tax_description']] += (($products_details['tax']/100) * ($products_details['qty'] * $products_details['final_price']));

				// Update Any Attributes
				if(isset($products_details[attributes]))
				{ foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details) {
					$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						products_options = '" . $attributes_details["option"] . "',
						products_options_values = '" . $attributes_details["value"] . "',
						options_values_price ='" . $attributes_details["price"] . "',
						price_prefix ='" . $attributes_details["prefix"] . "'
						where orders_products_attributes_id = '$orders_products_attributes_id';";
						tep_db_query($Query);
					}//end of foreach($products_details["attributes"]
				}// end of if(isset($products_details[attributes]))
				}// end of if/else (isset($products_details['delete']))

		}//end of foreach
		}//end of if (is_array())

		//1.3.5
		//update any downloads that may exist
      if (is_array($_POST['update_downloads'])) {
	  foreach($_POST['update_downloads'] as $orders_products_download_id => $download_details) {
		$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
					orders_products_filename = '" . $download_details["filename"] . "',
					download_maxdays = '" . $download_details["maxdays"] . "',
					download_count = '" . $download_details["maxcount"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_download_id = '$orders_products_download_id';";
					tep_db_query($Query);
				}
				}

		// 1.4 UPDATE SHIPPING, CUSTOM FEES, DISOUNTS, TAXES, AND TOTALS #####

	// 1.4.0.1 Shipping Tax
		if (!$this_is_an_mvs_order) {
			if (is_array ($_POST['update_totals'])){
			foreach($_POST['update_totals'] as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_shipping")//a good place to add in custom total components
				{
				    if (DISPLAY_PRICE_WITH_TAX == 'true') {//the shipping charge includes tax
			$RunningTax[$default_tax_name] += ($ot_value * $_POST['update_shipping_tax']) / ($_POST['update_shipping_tax'] + 100);
					} else { //shipping tax is in addition to the shipping charge
	$RunningTax[$default_tax_name] += (($_POST['update_shipping_tax'] / 100) * $ot_value);
					}
				}
			  }
		    }
			}//end if this is not an mvs order

		//1.4.1.0
		$RunningTotal = 0;
		$sort_order = 0;

			// 1.4.1.1  If ot_tax doesn't exist, but $RunningTax has been calculated, create an appropriate entry in the db and add tax to the subtotal or total as appropriate
			if (array_sum($RunningTax) != 0) {
			foreach ($RunningTax as $key => $val) {

			if (is_array ($_POST['update_totals'])){//1
			foreach($_POST['update_totals'] as $total_details)	{//2
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				$ot_tax_found = 0;
				 if (($ot_class == "ot_tax") && (preg_replace("/:$/","",$ot_title) == $key))
				 {//3
					$ot_tax_found = 1;
					break;
					}//end 3
				}//end 2
//bizzarro code needed to input text value into db properly
//I still don't understand why
//text = '" . $currencies->format($val, true, $order->info['currency'], $order->info['currency_value']) . "',
//isn't adequate.  Maybe I never will
	if ($ot_class == "ot_total" || $ot_class == "ot_tax" || $ot_class == "ot_subtotal" ||
	$ot_class == "ot_shipping" || $ot_class == "ot_custom" || $ot_class == "ot_loworderfee") {
		$order = new order($oID);
        $RunningTax[$default_tax_name] += 0 * $products_details['tax'] / $order->info['currency_value'] / 100 ;
		  }//end bizarro code
				}// end 1

				if (($val > 0) && ($ot_tax_found != 1)) {
				$sort_order++;
			$Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " SET
			orders_id = '" . (int)$oID . "',
			title ='" . $key . ":',
            text = '" . $currencies->format($val, true, $order->info['currency'], $order->info['currency_value']) . "',
		    value = '" . $val . "',
			class = 'ot_tax',
			sort_order = '2'";
			tep_db_query($Query);
			$ot_tax_found = 1;

			if (DISPLAY_PRICE_WITH_TAX != 'true') {
				 $RunningTotal += $val;
				} //end if (DISPLAY_PRICE_WITH_TAX != 'true')
				} //end if (($val > 0) && ($ot_tax_found != 1)) {
			 } //end foreach ($RunningTax as $key => $val)
		} //end if (array_sum($RunningTax) != 0)

  ////////////////////OPTIONAL- create entries for subtotal and/or total if none exists
				/*
			//1.4.1.2
			/////////////////////////Add in subtotal to db if it doesn't already exist
			if (($RunningSubTotal >0) && ($ot_subtotal_found != true)) {
				$Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
							orders_id = "' . (int)$oID . '",
							title ="' . ENTRY_SUB_TOTAL . '",
							text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				            value = "' . $RunningSubTotal . '",
							class = "ot_subtotal",
							sort_order = "1"';
						tep_db_query($Query);
						$ot_subtotal_found = true;
						$RunningTotal += $RunningSubTotal;
						}

						//1.4.1.3
  /////////////////////////Add in total to db if it doesn't already exist
			if (($RunningTotal >0) && ($ot_total_found != true)) {
				$Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
							orders_id = "' . (int)$oID . '",
							title ="' . ENTRY_TOTAL . '",
							text = "' . $currencies->format($RunningTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				            value = "' . $RunningTotal . '",
							class = "ot_total",
							sort_order = "4"';
						tep_db_query($Query);
						$ot_total_found = true;
						}
						*/
  //////////////////////////end optional section

	// 1.4.2. Summing up total
			if (is_array ($_POST['update_totals'])) {
			foreach($_POST['update_totals'] as $total_index => $total_details)	{

			extract($total_details,EXTR_PREFIX_ALL,"ot");
			if (trim($ot_title)) {
			     $sort_order++;

					if ($ot_class == "ot_subtotal") {
						$ot_value = $RunningSubTotal;
					}

					if ($ot_class == "ot_tax") {
						$ot_value = $RunningTax[preg_replace("/:$/","",$ot_title)];
					}

				   	if ($ot_class == "ot_total") {
					$ot_value = $RunningTotal;

				    if ( !$ot_subtotal_found )
				    { // There was no subtotal on this order, lets add the running subtotal in.
				     $ot_value +=  $RunningSubTotal;
				     }
				     }

			  // Set $ot_text (display-formatted value)
              $order = new order($oID);
              $ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);

				if ($ot_class == "ot_total") {
				$ot_text = "<b>" . $ot_text . "</b>";
					}

					if($ot_total_id > 0) { // Already in database --> Update
						$Query = "UPDATE " . TABLE_ORDERS_TOTAL . " SET
							title = '" . $ot_title . "',
							text = '" . $ot_text . "',
							value = '" . $ot_value . "',
							sort_order = '" . $sort_order . "'
							WHERE orders_total_id = '". $ot_total_id . "'
							AND orders_id = '" . (int)$oID . "'";
						tep_db_query($Query);
					} else { // New Insert (ie ot_custom)
						$Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " SET
							orders_id = '" . (int)$oID . "',
							title = '" . $ot_title . "',
							text = '" . $ot_text . "',
							value = '" . $ot_value . "',
							class = '" . $ot_class . "',
							sort_order = '" . $sort_order . "'";
						tep_db_query($Query);
					}

					if ($ot_class == "ot_tax") {

					if (DISPLAY_PRICE_WITH_TAX != 'true') {
					//we don't add tax to the total here because it's already added to the subtotal
						$RunningTotal += $ot_value;
						}
						} else {
						$RunningTotal += $ot_value;
						}
				}

	if (!trim($ot_value) && ($ot_class != "ot_shipping") && ($ot_class != "ot_subtotal") && ($ot_class != "ot_total")) { // value = 0 => Delete Total Piece

					$Query = "DELETE from " . TABLE_ORDERS_TOTAL . "
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_total_id = '$ot_total_id'";
					tep_db_query($Query);
				}

		}
}//end if (is_array())

		// 1.5 SUCCESS MESSAGE #####

		if ($order_updated)	{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));

	break;

	// 2. ADD A PRODUCT ###############################################################################################
	case 'add_product':

		if($_POST['step'] == 5)
		{
		// 2.1 GET ORDER INFO #####

			$oID = tep_db_prepare_input($_GET['oID']);
			$order = new order($oID);
			$AddedOptionsPrice = 0;

			//tax business
			// Following three functions are defined in includes/functions/general.php
			$countryid = tep_get_country_id($order->delivery["country"]);
			$zoneid = tep_get_zone_id($countryid, $order->delivery["state"]);
			$default_tax_name  = tep_get_tax_description($default_tax_class, $countryid, $zoneid);

		// 2.1.1 Get Product Attribute Info
			if(is_array ($_POST['add_product_options']))
			{
				foreach($_POST['add_product_options'] as $option_id => $option_value_id)
				{
					$result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . "
					pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po
					ON po.products_options_id=pa.options_id
					LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
					ON pov.products_options_values_id=pa.options_values_id
					WHERE products_id=" . $_POST['add_product_products_id'] . "
					and options_id=" . $option_id . "
					and options_values_id=" . $option_value_id . "
					and po.language_id = '" . (int)$languages_id . "'
					and pov.language_id = '" . (int)$languages_id . "'");

					$row = tep_db_fetch_array($result);
					extract($row, EXTR_PREFIX_ALL, "opt");
					if ($opt_price_prefix == '-')
					{$AddedOptionsPrice -= $opt_options_values_price;}
					else //default to positive
					{$AddedOptionsPrice += $opt_options_values_price;}
					$option_value_details[$option_id][$option_value_id] = array (
					"options_values_price" => $opt_options_values_price,
					"price_prefix" => $opt_price_prefix);
					$option_names[$option_id] = $opt_products_options_name;
					$option_values_names[$option_value_id] = $opt_products_options_values_name;

		//add on for downloads
		if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
        from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
        where products_attributes_id='" . $opt_products_attributes_id . "'";

		$download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $filename[$option_id] = $download['products_attributes_filename'];
          $maxdays[$option_id]  = $download['products_attributes_maxdays'];
          $maxcount[$option_id] = $download['products_attributes_maxcount'];
        } //end if (tep_db_num_rows($download_query) > 0) {
		} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads

				} //end foreach($_POST['add_product_options'] as $option_id => $option_value_id)
			} //end if(is_array ($_POST['add_product_options']))

	// 2.1.2 Get Product Info
	//modified for MVS (added vendors id and vendors name)
				$InfoQuery = "SELECT
	p.products_model, p.products_price, p.vendors_id,
	pd.products_name, p.products_tax_class_id, v.vendors_name
				from " . TABLE_PRODUCTS . " p
				LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
				ON pd.products_id=p.products_id
				LEFT JOIN " . TABLE_VENDORS . " v
				ON p.vendors_id=v.vendors_id
				WHERE p.products_id=" . $_POST['add_product_products_id'] . "
				AND pd.language_id = '" . (int)$languages_id . "'";
			    $result = tep_db_query($InfoQuery);

			$row = tep_db_fetch_array($result);
			extract($row, EXTR_PREFIX_ALL, "p");

			// 2.1.3  Pull specials price from db if there is an active offer
			$special_price = tep_db_query("
			SELECT specials_new_products_price
			FROM " . TABLE_SPECIALS . "
			WHERE products_id =". $_POST['add_product_products_id'] . "
			AND status");
			$new_price = tep_db_fetch_array($special_price);

			if ($new_price)
			{ $p_products_price = $new_price['specials_new_products_price']; }

			// 2.2 UPDATE ORDER ####
            $Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS . " SET
              orders_id = '" . (int)$oID . "',
              products_id = '" . $_POST['add_product_products_id'] . "',
              products_model = '" . $p_products_model . "',
              products_name = '" . tep_html_quotes($p_products_name) . "',
              products_price = '". $p_products_price . "',
              final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
           products_tax = '" . tep_get_tax_rate($p_products_tax_class_id, $countryid, $zoneid) . "',
              products_quantity = '" . $_POST['add_product_quantity'] . "',
			  vendors_id = '" . $p_vendors_id . "'";
              tep_db_query($Query);
              $new_product_id = tep_db_insert_id();
			  //end MVS

		/// 2.2.0.1
	    //update the orders_shipping table if necessary
		//if the product added is from a vendor not previously represented in the order,
		//we have to add in an appropriate entry, otherwise the product won't show up in the order

			if ($this_is_an_mvs_order) {
			$orders_shipping_exists = false;
			//getting MySQL error from this query?
			$orders_shipping_exists_query = tep_db_query("
			SELECT orders_shipping_id
			FROM " . TABLE_ORDERS_SHIPPING . "
			WHERE orders_id = '" . (int)$oID . "'
			AND vendors_id = '" . $p_vendors_id . "'");
			while ($this_result = tep_db_fetch_array($orders_shipping_exists_query)){
			$orders_shipping_exists_id = $this_result['orders_shipping_id'];
			}

			if (tep_not_null($orders_shipping_exists_id)) {
			$orders_shipping_exists = true;
			}

			if (!$orders_shipping_exists) {
			//we don't know the shipping module, method, or cost, so we skip them
			//perhaps in the future we can modify the add a product case
			//to pull installed shipping modules for the vendor?
			//also, what to do about the vendors email?
			$add_a_row_into_orders_shipping = "
			INSERT INTO	" . TABLE_ORDERS_SHIPPING . "
			SET orders_id = '" . (int)$oID . "',
			vendors_id = '" . $p_vendors_id . "',
			vendors_name =  '" . $p_vendors_name . "'";
			tep_db_query($add_a_row_into_orders_shipping);
			} //end if (!$orders_shipping_exists)
		    }  //end if ($this_is_an_mvs_order)


			//end update for orders_shipping table

			// 2.2.1 Update inventory Quantity
			//This is only done if store is set up to use stock
			if (STOCK_LIMITED == 'true'){
			tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET
			products_quantity = products_quantity - " . $_POST['add_product_quantity'] . "
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");
			}

			//2.2.1.1 Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
			products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");

			//2.2.1.2 keep a record of the products attributes
			if (is_array ($_POST['add_product_options'])) {
				foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
				$Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " SET
						orders_id = '" . (int)$oID . "',
						orders_products_id = '" . $new_product_id . "',
						products_options = '" . $option_names[$option_id] . "',
						products_options_values = '" .
						tep_db_input($option_values_names[$option_value_id]) . "',

                       options_values_price = '" .
		$option_value_details[$option_id][$option_value_id]['options_values_price'] . "',

						price_prefix = '" .
						$option_value_details[$option_id][$option_value_id]['price_prefix'] . "'";

					tep_db_query($Query);

					//add on for downloads
		if (DOWNLOAD_ENABLED == 'true' && isset($filename[$option_id])) {

		$Query2 = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
				orders_id = '" . (int)$oID . "',
				orders_products_id = '" . $new_product_id . "',
				orders_products_filename = '" . $filename[$option_id] . "',
				download_maxdays = '" . $maxdays[$option_id] . "',
	            download_count = '" . $maxcount[$option_id] . "'";

					tep_db_query($Query2);

       	} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads

				}
			}

			// 2.2.2 Calculate Tax and Sub-Totals
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = array($default_tax_name => 0);



			//just adding in shipping tax, don't mind me
			//it only works this way for non-MVS orders
		if (!$this_is_an_mvs_order) {
		$ot_shipping_query = tep_db_query("
		SELECT value
		FROM " . TABLE_ORDERS_TOTAL . "
		WHERE orders_id = '" . (int)$oID . "'
		AND class = 'ot_shipping'");

		while ($ot_shipping_info = tep_db_fetch_array($ot_shipping_query)) {
      	$ot_shipping_value = $ot_shipping_info['value'];

		if (DISPLAY_PRICE_WITH_TAX == 'true') {
			$RunningTax[$default_tax_name] += ($ot_shipping_value * $order->info['shipping_tax'] / ($order->info['shipping_tax'] + 100));
				} else {
  $RunningTax[$default_tax_name] += (($order->info['shipping_tax'] / 100) * $ot_shipping_value);

					}// end if (DISPLAY_PRICE_WITH_TAX == 'true') {
					}// end if ($ot_shipping_value['class'] == 'ot_shipping')
					}// end if (!$this_is_an_mvs_order)

		// end shipping tax calcs


		 // This calculation of Subtotal and Tax is part of the 'add a product' process
		if ($this_is_an_mvs_order) {
		for ($l=0, $m=sizeof($order->products); $l<$m; $l++) {
		for ($i=0, $n=sizeof($order->products[$l]['orders_products']); $i<$n; $i++) {
		//we total up the new way
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$RunningSubTotal += (($order->products[$l]['orders_products'][$i]['tax'] / 100 + 1) * ($order->products[$l]['orders_products'][$i]['qty'] * $order->products[$l]['orders_products'][$i]['final_price']));
		} else {
		$RunningSubTotal += ($order->products[$l]['orders_products'][$i]['qty'] * $order->products[$l]['orders_products'][$i]['final_price']);
		}

		$RunningTax[$order->products[$l]['orders_products'][$i]['tax_description']] += (($order->products[$l]['orders_products'][$i]['tax'] / 100) * ($order->products[$l]['orders_products'][$i]['qty'] * $order->products[$l]['orders_products'][$i]['final_price']));

		}//end for ($i=0, $n=sizeof($order->products[$l]['orders_products']); $i<$n; $i++)
		}//end for ($l=0, $m=sizeof($order->products); $l<$m; $l++)

		} else { //this is not an MVS order

  for ($i=0; $i<sizeof($order->products); $i++) {


		//non-MVS order; we do it the old way
		if (DISPLAY_PRICE_WITH_TAX == 'true') {
		$RunningSubTotal += (($order->products[$i]['tax'] / 100 + 1) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
		} else {
		$RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
		}

		$RunningTax[$order->products[$i]['tax_description']] += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));

		}// end of for ($i=0; $i<sizeof($order->products); $i++) {
		}//end of if $this_is_an_mvs_order



		// 2.2.2.1 Tax
		foreach ($RunningTax as $key => $val) {
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
			text = "' . $currencies->format($val, true, $order->info['currency'], $order->info['currency_value']) . '",
			value = "' . $val . '"
			WHERE class= "ot_tax"
			AND (title = "' . $key . ':" OR title = "' . $key . '")
			AND orders_id= "' . (int)$oID . '"';
			tep_db_query($Query);
			}


			// 2.2.2.2 Sub-Total
			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' SET
				text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $RunningSubTotal . '"
				WHERE class="ot_subtotal"
				AND orders_id= "' . (int)$oID . '"';
			tep_db_query($Query);

			// 2.2.2.3 Total
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
			$Query = 'SELECT sum(value)
			AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total"
			AND class != "ot_tax"
			AND orders_id= "' . (int)$oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			} else {
			$Query = 'SELECT sum(value)
			AS total_value from ' . TABLE_ORDERS_TOTAL . '
			WHERE class != "ot_total"
			AND orders_id= "' . (int)$oID . '"';
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row['total_value'];
			}

			$Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
             text = "' . $currencies->format($Total, true, $order->info['currency'], $order->info['currency_value']) . '",
				value = "' . $Total . '"
				WHERE class="ot_total" and orders_id= "' . (int)$oID . '"';
			tep_db_query($Query);

			// 2.3 REDIRECTION #####
			tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));

		}

	  break;

  }
}

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  define('HINT_DELETE_POSITION', '<font color="#FF0000">Hint: </font>To delete a product set its quantity to "0".');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />


<script language="javascript" src="includes/general.js"></script>

<script language="javascript" type="text/javascript">
//the searchDropdown function is used in conjunction with optional code found at lines 2049 at 2063

function searchDropdown(searchStr,xid) {
		    var ddObj = document.getElementById(xid);
			var index = ddObj.getElementsByTagName('option');
			for(var i = 0; i < index.length; i++) {
				if(index[i].firstChild.nodeValue.toLowerCase().substring(0,searchStr.length) == searchStr.toLowerCase()) {
					index[i].selected = true;
					break;
				}
			}
		}

</script>

</head>
<body>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor='white'
tipobj.style.width='200'
}
}

document.onmousemove=positiontip

</script>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php if (($action == 'edit') && ($order_exists == true)) { $order = new order($oID); ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . '&nbsp;(' . HEADING_TITLE_NUMBER . '&nbsp;' . $oID . '&nbsp;' . HEADING_TITLE_DATE  . '&nbsp;' . tep_datetime_short($order->info['date_purchased']) . ')'; ?></td>
            <td class="pageHeading2" align="right"></td>
             <td class="pageHeading" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&action=edit') . '">' .  IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' .  IMAGE_ORDERS_INVOICE . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . IMAGE_BACK . '</a> '; ?></td>
          </tr>
		</table></td>
      </tr>

<!-- Begin Addresses Block -->
     <tr><?php echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
	  </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>

	<!-- Begin Update Block -->
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="update1"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="update2" width="10">&nbsp;</td>
              <td class="update3" width="10">&nbsp;</td>
              <td class="update4" width="10">&nbsp;</td>
              <td class="update5" width="120" align="center"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
	<!-- End of Update Block -->

      <tr>
	    <td class="SubTitle" valign="bottom"><?php echo MENUE_TITLE_CUSTOMER; ?></td>
	  </tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>

			<tr>
			  <td>
<strong>QuickBooks ID:</strong> &nbsp;<input type="text" width="30" name="quickbooksid" value="<?php echo tep_html_quotes($order->info['quickbooksid']); ?>">
<table border="0" class="dataTableRow" cellpadding="2" cellspacing="0">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="80"></td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_CUSTOMER_ADDRESS; ?></td>
    <td class="dataTableHeadingContent" width="6">&nbsp;</td>
    <td class="dataTableHeadingContent" width="150" onMouseover="ddrivetip('<?php echo HINT_SHIPPING_ADDRESS; ?>')"; onMouseout="hideddrivetip()"><?php echo ENTRY_SHIPPING_ADDRESS; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
	 <td class="dataTableHeadingContent" width="6">&nbsp;</td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_BILLING_ADDRESS; ?></td>
  </tr>
 <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
 <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COMPANY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_company" size="30" value="<?php echo tep_html_quotes($order->customer['company']); ?>" /></span></td>
		<td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_company" size="30" value="<?php echo tep_html_quotes($order->delivery['company']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input name="update_billing_company" size="30" value="<?php echo tep_html_quotes($order->billing['company']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_NAME; ?>: </b></td>
    <td><span class="main"><input class="inputbox" name="update_customer_name" size="30" value="<?php echo tep_html_quotes($order->customer['name']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_name" size="30" value="<?php echo tep_html_quotes($order->delivery['name']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_name" size="30" value="<?php echo tep_html_quotes($order->billing['name']); ?>" /></span></td>
  </tr>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_ADDRESS; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_street_address" size="30" value="<?php echo tep_html_quotes($order->customer['street_address']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_street_address" size="30" value="<?php echo tep_html_quotes($order->delivery['street_address']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_street_address" size="30" value="<?php echo tep_html_quotes($order->billing['street_address']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_SUBURB; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_suburb" size="30" value="<?php echo tep_html_quotes($order->customer['suburb']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_suburb" size="30" value="<?php echo tep_html_quotes($order->delivery['suburb']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_suburb" size="30" value="<?php echo tep_html_quotes($order->billing['suburb']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_CITY; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_city" size="30" value="<?php echo tep_html_quotes($order->customer['city']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_city" size="30" value="<?php echo tep_html_quotes($order->delivery['city']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_city" size="30" value="<?php echo tep_html_quotes($order->billing['city']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_STATE == 'true') {
?>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_STATE; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_state" size="30" value="<?php echo tep_html_quotes($order->customer['state']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_state" size="30" value="<?php echo tep_html_quotes($order->delivery['state']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_state" size="30" value="<?php echo tep_html_quotes($order->billing['state']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_POSTCODE; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_postcode" size="30" value="<?php echo $order->customer['postcode']; ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_postcode" size="30" value="<?php echo $order->delivery['postcode']; ?>" /></span></td>
	 <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_postcode" size="30" value="<?php echo $order->billing['postcode']; ?>" /></span></td>
  </tr>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COUNTRY; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_country" size="30" value="<?php echo tep_html_quotes($order->customer['country']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_delivery_country" size="30" value="<?php echo tep_html_quotes($order->delivery['country']); ?>" /></span></td>
	<td>&nbsp;</td>
    <td><span class="main"><input class="inputbox"  name="update_billing_country" size="30" value="<?php echo tep_html_quotes($order->billing['country']); ?>" /></span></td>
  </tr>
   <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_PHONE; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_telephone" size="30" value="<?php echo $order->customer['telephone']; ?>" /></span></td>
   <td colspan="4"></td>
  </tr>
  <tr class="dataTableProducts">
    <td class="main"><b><?php echo ENTRY_CUSTOMER_EMAIL; ?>: </b></td>
    <td><span class="main"><input class="inputbox"  name="update_customer_email_address" size="30" value="<?php echo $order->customer['email_address']; ?>" /></span></td>
  <td colspan="4"></td>
	</tr>
</table>
				</td>
			</tr>
<!-- End Addresses Block -->

      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_PAYMENT; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>
      <tr>
	      <td>

<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td colspan="2" class="dataTableHeadingContent" valign="bottom" onMouseover="ddrivetip('<?php echo HINT_UPDATE_TO_CC . ENTRY_CREDIT_CARD . HINT_UPDATE_TO_CC2; ?>')"; onMouseout="hideddrivetip()"><?php echo ENTRY_PAYMENT_METHOD; ?> <img src="images/icon_info.png" border="0" width="13" height="13" /></td>
	<td></td>
	<td class="dataTableHeadingContent"><?php echo ENTRY_CURRENCY_TYPE; ?></td>
	<td></td>
	<td class="dataTableHeadingContent"><?php echo ENTRY_CURRENCY_VALUE; ?></td>
	</tr>
  <tr class="dataTableProducts">
	  <td colspan="2" class="main">
	  <?php
	  //START for payment dropdown menu use this by quick_fixer
  		if (DISPLAY_PAYMENT_METHOD_DROPDOWN == 'true') {

		  // Get list of all payment modules available
  $enabled_payment = array();
  $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir( $module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  // For each available payment module, check if enabled
  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];

    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
    include($module_directory . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        // If module enabled create array of titles
      	$enabled_payment[] = array('id' => $module->title, 'text' => $module->title);

		//if the payment method is the same as the payment module title then don't add it to dropdown menu
		if ($module->title == $order->info['payment_method']) {
			$paymentMatchExists='true';
		}
      }
   }
 }
 		//just in case the payment method found in db is not the same as the payment module title then make it part of the dropdown array or else it cannot be the selected default value
		if ($paymentMatchExists !='true') {
			$enabled_payment[] = array('id' => $order->info['payment_method'], 'text' => $order->info['payment_method']);
 }
 $enabled_payment[] = array('id' => 'Other', 'text' => 'Other');
		//draw the dropdown menu for payment methods and default to the order value
	  		echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" onChange="init()"');
		}
	  	else {
		//draw the input field for payment methods and default to the order value
	  ?><input class="inputbox"  name="update_info_payment_method" size="35" value="<?php echo $order->info['payment_method']; ?>" id="update_info_payment_method" onKeyUp="init()"/><?php
	  }
	  //END for payment dropdown menu use this by quick_fixer
	?></td>

	<td width="20">
	</td>

	<td><?php
	///get the currency info
reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

echo tep_draw_pull_down_menu('update_info_payment_currency', $currencies_array, $order->info['currency'], 'id="update_info_payment_currency" onChange="currency()"');

?>
</td>

<td width="10">
</td>

	  <td><input class="inputbox"  name="update_info_payment_currency_value" size="15" readonly="readonly" id="update_info_payment_currency_value" value="<?php echo $order->info['currency_value']; ?>" /></td>
	</tr>

	<!-- Begin Credit Card Info Block -->
	  <tr class="dataTableProducts"><td>

	  <table id="optional">
	 <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	    <td class="main"><input class="inputbox"  name="update_info_cc_type" size="10" value="<?php echo $order->info['cc_type']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input class="inputbox"  name="update_info_cc_owner" size="20" value="<?php echo $order->info['cc_owner']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input class="inputbox"  name="update_info_cc_number" size="20" value="<?php echo $order->info['cc_number']; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input class="inputbox"  name="update_info_cc_expires" size="4" value="<?php echo $order->info['cc_expires']; ?>" maxlength="4" /></td>
	  </tr>
	  </table>

	  </td></tr>

  <!-- End Credit Card Info Block -->

</table>

   </td>
      </tr>

<!-- End Payment Block -->

      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
	      <td class="SubTitle" onMouseover="ddrivetip('<?php echo HINT_PRODUCTS_PRICES; ?>')"; onMouseout="hideddrivetip()"><?php echo MENUE_TITLE_ORDER; ?> <img src="images/icon_info.png" border= "0" width="13" height="13" /></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>
      <tr>
	      <td>

	<?php if ($this_is_an_mvs_order) {
	require ('includes/order_editor_mvs.php');
	//the javascript functions and sections 3 & 4 are different for mvs orders
	//keeping these sections in separate files makes it a lot easier to work on them
	} else {
	require ('includes/order_editor.php');
	}?>

		</td>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
	<!-- End Order Total Block -->

	<!-- Begin Status Block -->
      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_STATUS; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>
      <tr>
        <td class="main">

<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo HEADING_TITLE_STATUS; ?></td>
   <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
   </tr>
<?php
$orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
if (tep_db_num_rows($orders_history_query)) {
  while ($orders_history = tep_db_fetch_array($orders_history_query)) {
    echo '  <tr class="dataTableProducts">' . "\n" .
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="center">';
    if ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    } else {
      echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
    }
    echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
   echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
           '    <td class="smallText" align="left">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
  echo '  </tr>' . "\n";
  }
} else {
  echo '  <tr class="dataTableProducts">' . "\n" .
       '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
       '  </tr>' . "\n";
}
?>
</table>

			  </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>
      <tr>
			  <td>

<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
    <td class="main" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
  </tr>
	<tr class="dataTableProducts">
	  <td>
		  <table border="0" cellspacing="0" cellpadding="2">
        <tr class="dataTableProducts">
          <td class="main"><b><?php echo ENTRY_STATUS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
        </tr>
        <tr class="dataTableProducts">
          <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
        </tr>
        <tr class="dataTableProducts">
          <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify_comments', '', false); ?></td>
        </tr>
     </table>
	  </td>
    <td class="main" width="10">&nbsp;</td>
    <td class="main">
    <?php echo tep_draw_textarea_field('comments', 'soft', '40', '5', ''); ?>
    </td>
  </tr>
</table>
			  </td>
			</tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
	<!-- End of Status Block -->

	<!-- Begin Update Block -->

      <tr>
	      <td class="SubTitle"><?php echo MENUE_TITLE_UPDATE; ?></td>
			</tr>
      <tr>
	      <td><?php echo tep_draw_separator('pixel_trans.png', '1', '1'); ?></td>
      </tr>
      <tr>
	      <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="update1"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="update2" width="10">&nbsp;</td>
              <td class="update3" width="10">&nbsp;</td>
              <td class="update4" width="10">&nbsp;</td>
              <td class="update5" width="120" align="center"><?php echo tep_image_submit('button_update.png', IMAGE_UPDATE); ?></td>
	          </tr>
          </table>
				</td>
      </tr>
	<!-- End of Update Block -->

      </form>

<?php
}
if($action == "add_product")
{
?>
      <tr>
        <td width="100%">
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
		      <td class="pageHeading"><?php echo ADDING_TITLE; ?> (No. <?php echo $oID; ?>)</td>
              <td class="pageHeading2" align="right"></td>
              <td class="pageHeading" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action'))) . '">' .  IMAGE_BACK . '</a>'; ?></td>
            </tr>
          </table>
				</td>
      </tr>

<?php
	// ############################################################################
	//   Get List of All Products
	// ############################################################################
    //Modified for MVS
		$result = tep_db_query("
		SELECT products_name, p.products_id, p.vendors_id, categories_name, ptc.categories_id
		FROM " . TABLE_PRODUCTS . " p
		LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
		ON pd.products_id=p.products_id
		LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
		ON ptc.products_id=p.products_id
		LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
		ON cd.categories_id=ptc.categories_id
		WHERE pd.language_id = '" . (int)$languages_id . "'
		ORDER BY categories_name");
		while($row = tep_db_fetch_array($result))
		{
			extract($row,EXTR_PREFIX_ALL,"db");
			$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			$CategoryList[$db_categories_id] = $db_categories_name;
			$LastCategory = $db_categories_name;
		}

	// ############################################################################
	//   Add Products Steps
	// ############################################################################
	echo '<tr><td><table border="0">' . "\n";

		// Set Defaults
			if(!isset($_POST['add_product_categories_id']))
			$add_product_categories_id = 0;

			if(!isset($_POST['add_product_products_id']))
			$add_product_products_id = 0;

			// Step 1: Choose Category
			echo '<tr class="dataTableRow">' . tep_draw_form('addProduct', FILENAME_ORDERS_EDIT,'oID=' . $_GET['oID'] . '&action=' . $_GET['action']) . "\n";
			echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 1:</b></td>' .  "\n";
			echo '<td class="dataTableContent" valign="top">';
			if (isset($_POST['add_product_categories_id'])) {
			$current_category_id = $_POST['add_product_categories_id'];
			}
			//echo '<input type="text" name="search" onKeyUp="searchDropdown(this.value,\'apcid\');" />';
			echo ' ' . tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();" id="apcid"');
			echo '<input type="hidden" name="step" value="2">' . "\n";
			echo '</td>' . "\n";
			echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP1 . '</td>' . "\n";
			echo '</form></tr>' . "\n";
			echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";

		// Step 2: Choose Product
           if(($_POST['step'] > 1) && ($_POST['add_product_categories_id'] > 0))
		   {
           echo '<tr class="dataTableRow">' . tep_draw_form('addProduct', FILENAME_ORDERS_EDIT,'oID=' . $_GET['oID'] . '&action=' . $_GET['action']) . "\n";
           echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 2: </b></td>' . "\n";
           echo '<td class="dataTableContent" valign="top">' . "\n";
		   //echo '<input type="text" name="search" onKeyUp="searchDropdown(this.value,\'appid\');" />';
           echo '<select name="add_product_products_id" onChange="this.form.submit();" id="appid">' . "\n";
           $ProductOptions = "<option value='0'>" . ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
           asort($ProductList[$_POST['add_product_categories_id']]);
           foreach($ProductList[$_POST['add_product_categories_id']] as $ProductID => $ProductName)
           {
              $ProductOptions .= "<option value='$ProductID'> $ProductName\n";
           }
		   if(isset($_POST['add_product_products_id'])){
         $ProductOptions = str_replace("value='" . $_POST['add_product_products_id'] . "'", "value='" . $_POST['add_product_products_id'] . "' selected=\"selected\"", $ProductOptions);
           }
		   echo ' ' . $ProductOptions .  ' ';
           echo '</select></td>' . "\n";
           echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
           echo '<input type="hidden" name="step" value="3">' . "\n";
           echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP2 . '</td>' . "\n";
           echo '</form></tr>' . "\n";
           echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
           }

		// Step 3: Choose Options
		if(($_POST['step'] > 2) && ($_POST['add_product_products_id'] > 0))

		{
			// Get Options for Products
           $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $_POST['add_product_products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] == 0) {
				echo '<tr class="dataTableRow">' . "\n";
				echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td>' . "\n";
				echo '<td class="dataTableContent" valign="top" colspan="2"><i>' . ADDPRODUCT_TEXT_OPTIONS_NOTEXIST . '</i></td>' . "\n";
				echo '</tr>' . "\n";
				$_POST['step'] = 4;
			}
			else //product options exist
			{
			echo '<tr class="dataTableRow">' . tep_draw_form('addProduct', FILENAME_ORDERS_EDIT,'oID=' . $_GET['oID'] . '&action=' . $_GET['action']) . "\n";
				echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td><td class="dataTableContent" valign="top">';

				$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $_POST['add_product_products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $_POST['add_product_products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }

        if(isset($_POST['add_product_options'])) {
          $selected_attribute = $_POST['add_product_options'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
			echo $products_options_name['products_options_name'] . ':' . "\n";
			echo tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . '<br />' . "\n";
		}

				echo '</td>';
				echo '<td class="dataTableContent" align="center"><input type="submit" class="button" value="' . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . '">';
				echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id']. '>';
				echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
				echo '<input type="hidden" name="step" value="4">';
				echo '</td>' . "\n";
				echo '</form></tr>' . "\n";
			}

			echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
		}

		// Step 4: Confirm
		if($_POST['step'] > 3)

		{
		   	echo '<tr class="dataTableRow">' . tep_draw_form('addProduct', FILENAME_ORDERS_EDIT,'oID=' . $_GET['oID'] . '&action=' . $_GET['action']) . "\n";
			echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 4: </b></td>';
			echo '<td class="dataTableContent" valign="top"><input class="inputbox"  name="add_product_quantity" size="2" value="1"> ' . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . '</td>';
			echo '<td class="dataTableContent" align="center"><input class="inputbox"  type="submit" value="' . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . '">';

			if(is_array ($_POST['add_product_options']))
			{
				foreach($_POST['add_product_options'] as $option_id => $option_value_id)
				{
					echo '<input type="hidden" name="add_product_options[' . $option_id . ']" value="' . $option_value_id . '">';
				}
			}
			echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
			echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
			echo '<input type="hidden" name="step" value="5">';
			echo '</td>' . "\n";
			echo '</form></tr>' . "\n";
		}

		echo '</table></td></tr>' . "\n";
}
?>
    </table></td>
<!-- body_text_eof //-->
  </tr></table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>