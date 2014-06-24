<?php
/**
 * @brief Converts an Merchant At Feed document containing orders into OSCommerce order objects.
 *        Persists those orders into the database.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
 * @copyright 2008-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 *
 */
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
                                                                                                                                                             
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
                                                                                                                                                             
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

require('includes/configure.php');
require_once(DIR_WS_CLASSES . 'currencies.php');
define('WSDLPATH', 'file://' . DIR_FS_CATALOG . '/checkout_by_amazon/library/merchantAtAPIs/lib/amazon/merchant-interface-mime.wsdl');
require_once("merchantAtAPIs/lib/amazon/amazon_merchant_at_soap_client.php");
require_once('checkout_by_amazon_account_dao.php');
require_once('checkout_by_amazon_util_dao.php');
/**
 * Base class for utility methods for building and persisting an order.
 */
class OrderBuilder
{
    var $isCallbackEnabled;
    var $callbackShippingCarrier;
    var $standardShippingOverride;
    var $items;
    var $currencies;

    /**
     * Constructor
     */
    function OrderBuilder()
    {
       $cba_module_info = new checkout_by_amazon();
       $this->isCallbackEnabled = $cba_module_info->callback_required;
       $this->callbackShippingCarrier = $cba_module_info->callback_shipping_carrier;
       $this->standardShippingOverride = tep_std_ovr_use_function();
       $this->currencies = new currencies();
    }


    /**
     * Assign order totals to order by iterating through all the items and 
     * summing the item prices
     */
    function setOrderTotals(&$order, $request) {
        // set the order line item totals, an example from what is stored in the database:
        // +-----------------------+----------------+-------------+
        // | title                 | text           | class       |
        // +-----------------------+----------------+-------------+
        // | Sub-Total:            | $104.94        | ot_subtotal |
        // | Flat Rate (Best Way): | $5.00          | ot_shipping |
        // | Total:                | <b>$109.94</b> | ot_total    |
        // +-----------------------+----------------+-------------+
        $this->items = $request->getOrderItems();
        
        $orderFulfillmentServiceLevel = $request->getOrderFulfillmentServiceLevel();
        $orderSubTotal = 0.00;
        $orderShippingTotal = 0.00;
        $orderPromotionTotal = 0.00;
        $orderTaxTotal = 0.00;
        $orderTotal = 0.00;
        for ($i = 0; $i < count($this->items); $i++) {
            $item = $this->items[$i];
	    $item = (array)$item;
            $itemTotals = $request->getItemPriceComponents($item);

            $orderSubTotal = (float)$orderSubTotal + (float)$itemTotals['sub_total'];
            $orderShippingTotal = (float)$orderShippingTotal + (float)$itemTotals['shipping_total'];
            $orderPromotionTotal = (float)$orderPromotionTotal + (float)$itemTotals['promotion_total'];
            $orderTaxTotal = (float)$orderTaxTotal + (float)$itemTotals['tax_total'] + (float)$itemTotals['shipping_tax_total'];
            $orderTotal = (float)$orderTotal + (float)$itemTotals['total'];

        }

        // construct new order total object that will be stored into the database.
        // This mimics includes/classes/order_total.php
        $order_totals = array(
                              // Subtotal
                              array(// Maps to database column 'class'
                                    'code' => 'ot_subtotal',
                                    'title' => 'Sub-Total:',
                                    'text' => $this->currencies->format($orderSubTotal,true, $order->info['currency'], $order->info['currency_value']),
                                    'value' => $orderSubTotal,
                                    // display sort order
                                    'sort_order' => 1),
                              // Shipping
                              array('code' => 'ot_shipping',
                                    'title' => 'Shipping - ' . $orderFulfillmentServiceLevel . ':',
                                    'text' => $this->currencies->format($orderShippingTotal, true, $order->info['currency'], $order->info['currency_value']),
                                    'value' => $orderShippingTotal,
                                    'sort_order' => 2),
                              // Tax
                              array('code' => 'ot_tax',
                                    'title' => 'Tax:',
                                    'text' => $this->currencies->format($orderTaxTotal, true, $order->info['currency'], $order->info['currency_value']),
                                    'value' => $orderTaxTotal,
                                    'sort_order' => 3),
                              // Promo - note that oscommerce has no promotions
                              // in their engine.
                              array('code' => 'ot_promo',
                                    'title' => 'Promotion:',
                                    'text' => $this->currencies->format($orderPromotionTotal, true, $order->info['currency'], $order->info['currency_value']),
                                    'value' => $orderPromotionTotal,
                                    'sort_order' => 4),
                              // Total
                              array('code' => 'ot_total',
                                    'title' => 'Total:',
                                    'text' => '<b>' . $this->currencies->format($orderTotal, true, $order->info['currency'], $order->info['currency_value']) . '</b>',
                                    'value' => $orderTotal,
                                    'sort_order' => 5)
                             );
        
        $order->totals = $order_totals;
    }

    /**
     * Get individual order component total by code
     * (ot_tax, ot_shipping, ot_total, etc.)
     */
    function getOrderTotalComponent($order, $code) {
        for ($i = 0; $i < count($order->totals); $i++) {
            if ($order->totals[$i]['code'] == $code) {
                return $order->totals[$i];
            }
        }

        return NULL;
    }


    /**
     * Set order information without payment method information. 
     * This is not provided by Amazon for security reasons.
     */
    function setOrderInformation (&$order, $channel, $request_class) {
        $orderShippingTotal = $this->getOrderTotalComponent($order, 'ot_shipping');
        $orderSubTotal = $this->getOrderTotalComponent($order, 'ot_subtotal');
        $orderTaxTotal = $this->getOrderTotalComponent($order, 'ot_tax');

        if (strcmp($channel, AMAZON_ORDER_CHANNEL_VALUE) == 0)
                $channel = 'Checkout By Amazon';
        else if (strcmp($channel, AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE) == 0)
                 $channel = 'Amazon Payments';
	$order_status = MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING;
	if($request_class == 'CBAMFAxml')  {
		$order_status = MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW;
	}
        // set general order information
        $order->info = array('order_status' => $order_status,
                             'currency' => 'USD',
                             'currency_value' => 1,
                             'payment_method' => $channel,
                             'cc_type' => '',
                             'cc_owner' => '',
                             'cc_number' => '',
                             'cc_expires' => '',
                             'shipping_method' => $orderShippingTotal['title'],
                             'shipping_cost' => $orderShippingTotal['value'],
                             'subtotal' => $orderSubTotal['value'],
                             'tax' => $orderTaxTotal['value'],
                             'tax_groups' => array());
    }

    /**
     * Set order customer information without payment method information. 
     *
     */
    function setCustomer (&$order, $request) {
        // insert customer if it does not exist via
        // create_account.php
        $customer = $this->getOrCreateCustomer($order, $request);

        $order->customer = array('id' => $customer['customers_id'],
			         'name' => $customer['customers_firstname'] . " " . $customer['customers_lastname'],
                                 'telephone' => $customer['customers_telephone'],
                                 'email_address' => $customer['customers_email_address']);
    }

    /**
     * Create the address if necessary.
     * If we are shipping to the buyer instead of someone else,
     * we can fill in the customer's shipping address information as 
     * well.
     *
     * Assume order's fulfillment data is set first.
     */
    function setCustomerAddress (&$order, $request) {
        $fulfillmentFirstname = $order->delivery['firstname'];
        $fulfillmentLastname = $order->delivery['lastname'];

        $fulfillmentName = $fulfillmentFirstname . ' ' . $fulfillmentLastname;

        // create the customer address regardless of whether the 
        // buyer is the ship to recipient.
        // This is needed to ensure that catalog/admin/customers.php can load an address correctly.
        $address = $this->getOrCreateAddress($order, $request);
        if (strcmp($order->customer['name'], $fulfillmentName) == 0) {
            // see if we need to create the address 
            $address = $this->getOrCreateAddress($order, $request);

            $order->customer['street_address'] = $address['street_address'];
	    $order->customer['city'] = $address['city'];
	    $order->customer['postcode'] = $address['postcode'];
	    $order->customer['state'] = $this->delivery['state'];
	    $order->customer['country'] = $order->delivery['country'];
	    $order->customer['format_id'] = 2;
        }

        return NULL;
    }


    /**
     * Set where/how this order is to be delivered.
     */
    function setFulfillmentData (&$order, $request) {
        $countryCode = $request->getOrderFulfillmentAddressCountryCode();
        $country = $this->getCountryByCountryCode($countryCode);

        // retrieve data from the Fulfillment section of the xml
        $order->delivery = array('firstname' => $request->getOrderFulfillmentAddressFirstName(),
                                 'lastname' => $request->getOrderFulfillmentAddressLastName(),
         			 'company' => '',
                                 'street_address' => $request->getOrderFulfillmentAddressFieldOne(),
		        	 'suburb' => $request->getOrderFulfillmentAddressFieldTwo(),
        			 'city' => $request->getOrderFulfillmentAddressCity(),
	        		 'postcode' => $request->getOrderFulfillmentAddressPostalCode(),
                                 'state' => $request->getOrderFulfillmentAddressStateOrRegion(),

                                 'country' => array('id' => $country['countries_id'],
                                                    'title' => $country['countries_name'],
                                                    'iso_code_2' => $country['countries_iso_code_2'],
                                                    'iso_code_3' => $country['countries_iso_code_3']),
                                 // TODO: FIGURE OUT WHAT THIS IS USED FOR
                                 'format_id' => 2);
    }

    /**
     * Iterate through all order items in the xml
     * and set the order object
     */
    function setProductData (&$order, $request) {
        for ($i = 0; $i < count($this->items); $i++) {
            $item = $this->items[$i];
	    $item = (array)$item;
            $itemTotals = $request->getItemPriceComponents($item);
	    if($itemTotals == null)
                return;
            $order->products[$i] =
                array('qty' => $item['Quantity'],
		      'id' => $item['SKU'],
                      'amazon_id' => $item['AmazonOrderItemCode'],
                      'name' => $item['Title'],
                      'model' => '',
                      'shipping' => $itemTotals['shipping_total'],
                      'promotion_claim_code' => $itemTotals['promotion_claim_code'],
                      'promotion_merchant_promotion_id' => $itemTotals['promotion_merchant_promotion_id'],
                      'promotion_price' => $itemTotals['promotion_total'],
                      'promotion_tax' => $itemTotals['promotion_tax_total'],
                      'promotion_shipping' => $itemTotals['promotion_shipping_total'],
                      'tax' => $itemTotals['tax_total'],
                      'shipping_tax' => $itemTotals['shipping_tax_total'],

                      // this is the item price
                      'price' => $itemTotals['sub_total'] / $item['Quantity'],
                      'final_price' => $itemTotals['sub_total'] / $item['Quantity']);
        }

        // TODO: Enable product attributes (such as add-ons/size dimensions, etc.)
        /*
        $subindex = 0;
        $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " .   TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" .   (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
            while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
							       'value' => $attributes['products_options_values'],
							       'prefix' => $attributes['price_prefix'],
							       'price' => $attributes['options_values_price']);
            $subindex++;
            }
        }
        
        $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';
        $index++;
        */
    }

    /**
     * Actually persist the order.
     * De-dup existing orders by checking ordering customer and purchased date
     */
    function createOrder ($order, $request) {
        ///////////////////////////////////////////////////////////////////////////
        // This is copied directly from checkout_process.php, line 111.
        // Unfortunately, that php file is not modularized, so we cannot use it 
        // directly. We do not refactor that file as it may have been customized
        // by existing OSCommerce user installations
        ///////////////////////////////////////////////////////////////////////////
        $sql_data_array = array('customers_id' => $order->customer['id'],
                                'customers_name' => $order->customer['name'],
                                // Unused by Checkout by Amazon
                                //'customers_company' => $order->customer['company'],
                                'customers_street_address' => $order->customer['street_address'],
                                //'customers_suburb' => $order->customer['suburb'],
                                'customers_city' => $order->customer['city'],
                                'customers_postcode' => $order->customer['postcode'],
                                'customers_state' => $order->customer['state'],
                                'customers_country' => $order->customer['country']['title'],
                                'customers_telephone' => $order->customer['telephone'],
                                'customers_email_address' => $order->customer['email_address'],
                                // TODO: Figure out what this is for
                                'customers_address_format_id' => 2,
                                'delivery_name' => trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
                                //'delivery_company' => $order->delivery['company'],
                                'delivery_street_address' => $order->delivery['street_address'],
                                'delivery_suburb' => $order->delivery['suburb'],
		                'delivery_city' => $order->delivery['city'],
			        'delivery_postcode' => $order->delivery['postcode'],
                                'delivery_state' => $order->delivery['state'],
                                'delivery_country' => $order->delivery['country']['title'],
			        'delivery_address_format_id' => $order->delivery['format_id'],
			        //'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
			        //'billing_company' => $order->billing['company'],
			        //'billing_street_address' => $order->billing['street_address'],
			        //'billing_suburb' => $order->billing['suburb'],
			        //'billing_city' => $order->billing['city'],
			        //'billing_postcode' => $order->billing['postcode'],
			        //'billing_state' => $order->billing['state'],
			        //'billing_country' => $order->billing['country']['title'],
			        //'billing_address_format_id' => $order->billing['format_id'],
			        'payment_method' => $order->info['payment_method'],
			        //'cc_type' => $order->info['cc_type'],
			        //'cc_owner' => $order->info['cc_owner'],
			        //'cc_number' => $order->info['cc_number'],
			        //'cc_expires' => $order->info['cc_expires'],

                                'date_purchased' => $request->getOrderDate(),
			        'orders_status' => $order->info['order_status'],

                                'currency' => $order->info['currency'],
                                'currency_value' => $order->info['currency_value']);

        tep_db_perform(TABLE_ORDERS, $sql_data_array);
        $insert_id = tep_db_insert_id();

        // insert order totals
        for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
	    $sql_data_array = array('orders_id' => $insert_id,
				    'title' => $order->totals[$i]['title'],
				    'text' => $order->totals[$i]['text'],
				    'value' => $order->totals[$i]['value'],
				    'class' => $order->totals[$i]['code'],
				    'sort_order' => $order->totals[$i]['sort_order']);
            tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }

        // Check for QTPro, and create the order accordingly.

        if (file_exists(DIR_WS_CLASSES.'pad_base.php')) {
          $this->updateOrderQTPro($order, $insert_id);

        }
        else {
          $this->updateOrder($order, $insert_id);
        }

        // return the created order's id.
        return $insert_id;
    }


    /**
     * Sets affiliate data for osCAffiliates v2.8.
     *
     * Affiliate data is passed from a banner to the OSCommerce page,
     * when then stores the information in the session.
     * This information is passed along in the cart,
     * and returned in the order report.
     *
     * We retrieve the data from the order report,
     * and then update the affiliates sale in the database.
     */
    function updateOrderAffiliateData ($orderId, $order, $request) {
        $affiliateData = $this->getOrderAffiliateData($request);

        if ($affiliateData == NULL) {
           return;
        }
       
        $affiliate_ref = $affiliateData[AMAZON_ORDER_AFFILIATE_REFERENCE_KEY];
        $affiliate_clickthroughs_id = $affiliateData[AMAZON_ORDER_AFFILIATE_CLICKTHROUGH_KEY];

        if ($affiliate_clickthroughs_id == NULL) {
           return;
        }

        $affiliate_clickthroughs_query = tep_db_query("select * from " . TABLE_AFFILIATE_CLICKTHROUGHS .
                                                      " where affiliate_clickthrough_id = " .
                                                      $affiliate_clickthroughs_id);
        $affiliate_result_set = tep_db_fetch_array($affiliate_clickthroughs_query);
 
        if (!$affiliate_result_set) {
           return;
        }

        // set rest of data from clickthrough id
        $affiliate_clientdate = $affiliate_result_set['affiliate_clientdate'];
        $affiliate_clientbrowser = $affiliate_result_set['affiliate_clientbrowser'];
        $affiliate_clientip = $affiliate_result_set['affiliate_clientip'];

        // This is the order id that is used below.
        $insert_id = $orderId;

	/////////////////////////////////////////////////////////////////////////
	//
        // Copied from includes/affiliate_checkout_process.php
        // with slight modifications to use passed-in data instead
        // of from the session.
        //
	/////////////////////////////////////////////////////////////////////////
        
        // fetch the net total of an order
        $affiliate_total = 0;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          $affiliate_total += $order->products[$i]['final_price'] * $order->products[$i]['qty'];
        }
        $affiliate_total = tep_round($affiliate_total, 2);

        // Check for individual commission
        $affiliate_percentage = 0;

        if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
          $affiliate_commission_query = tep_db_query ("select affiliate_commission_percent from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_ref . "'");
          $affiliate_commission = tep_db_fetch_array($affiliate_commission_query);
          $affiliate_percent = $affiliate_commission['affiliate_commission_percent'];
        }

        if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
        $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);

        // insert the affiliates sales data
        $sql_data_array = array('affiliate_id' => $affiliate_ref,
                                'affiliate_date' => $affiliate_clientdate,
                                'affiliate_browser' => $affiliate_clientbrowser,
                                'affiliate_ipaddress' => $affiliate_clientip,
                                'affiliate_value' => $affiliate_total,
                                'affiliate_payment' => $affiliate_payment,
                                'affiliate_orders_id' => $insert_id,
                                'affiliate_clickthroughs_id' => $affiliate_clickthroughs_id,
                                'affiliate_percent' => $affiliate_percent,
                                'affiliate_salesman' => $affiliate_ref);
        tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);

        if (AFFILATE_USE_TIER == 'true') {
          $affiliate_tiers_query = tep_db_query ("SELECT aa2.affiliate_id, (aa2.affiliate_rgt - aa2.affiliate_lft) as height
                                                          FROM affiliate_affiliate AS aa1, affiliate_affiliate AS aa2
                                                          WHERE  aa1.affiliate_root = aa2.affiliate_root
                                                          AND aa1.affiliate_lft BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
                                                          AND aa1.affiliate_rgt BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
                                                          AND aa1.affiliate_id =  '" . $affiliate_ref . "'
                                                          ORDER by height asc limit 1, " . AFFILIATE_TIER_LEVELS . "
                                                  ");
          $affiliate_tier_percentage = split("[;]" , AFFILIATE_TIER_PERCENTAGE);
          $i=0;
          while ($affiliate_tiers_array = tep_db_fetch_array($affiliate_tiers_query)) {
            $affiliate_percent = $affiliate_tier_percentage[$i];
            $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
            if ($affiliate_payment > 0) {
              $sql_data_array = array('affiliate_id' => $affiliate_tiers_array['affiliate_id'],
                                      'affiliate_date' => $affiliate_clientdate,
                                      'affiliate_browser' => $affiliate_clientbrowser,
                                      'affiliate_ipaddress' => $affiliate_clientip,
                                      'affiliate_value' => $affiliate_total,
                                      'affiliate_payment' => $affiliate_payment,
                                      'affiliate_orders_id' => $insert_id,
                                      'affiliate_clickthroughs_id' => $affiliate_clickthroughs_id,
                                      'affiliate_percent' => $affiliate_percent,
                                      'affiliate_salesman' => $affiliate_ref);
              tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
            }
            $i++;
          }
        }

	/////////////////////////////////////////////////////////////////////////
	//
        // Copied from includes/affiliate_checkout_process.php
        // with slight modifications to use passed-in data instead
        // of from the session.
        // 
        // End of copy.
        //
	/////////////////////////////////////////////////////////////////////////
    }



    /**
     * Fetch existing account based on email address,
     * or create new one.
     *
     */
    function getOrCreateCustomer($order, $request) {
        // pad it so it doesn't conflict with existing addresses in elegant way
        // noone can reset this password or create this account beforehand manually
        // since it is not a valid formatted email address
        $buyerEmailAddress = $request->getOrderBuyerEmailAddress();

        $accountDao = new AccountDAO();

        $customer = $accountDao->getAccount($buyerEmailAddress);

        if (!$customer || $customer['customers_id'] == NULL) {
            // create customer account here
            // copied from create_account.php
            $firstname = $request->getOrderBuyerFirstName();
            $lastname = $request->getOrderBuyerLastName();
 
            $emailAddress = $buyerEmailAddress;
            $password = $this->getOrderBuyerPassword();
            $phoneNumber = '';

            $customer = $accountDao->createAccount($firstname, $lastname, $emailAddress, $password, $phoneNumber);
        }

        return $customer;
    }

    /**
     *
     * Assume fulfillment data is set first.
     */
    function getOrCreateAddress(&$order, $request) {
        $accountDao = new AccountDAO();

        $addressBook = $accountDao->getAddressBook($order->customer['id']);

        $address = $this->findAddress($order, $addressBook, $request);
        if (!$address) {
            $fulfillmentFirstname = $order->delivery['firstname'];
            $fulfillmentLastname = $order->delivery['lastname'];

            $zoneId = $this->getZoneId($order->delivery['country']['id'],
                                       $order->delivery['state']);

            return $accountDao->createAddress(
                $order->customer['id'],
                $fulfillmentFirstname,
                $fulfillmentLastname,
                $order->delivery['street_address'],
                $order->delivery['postcode'],
                $order->delivery['city'],
                $zoneId,
                $order->delivery['state'],
                $order->delivery['country']['id']);
        }

        return $address;
    }

    /**
     * Find if the order's delivery address already exists as part of the 
     * customer's address book.
     *
     */
    function findAddress ($order, $addressBook, $request) {
        for ($i = 0; $i < count($addressBook); $i++) {
            $address = $addressBook[$i];

            $country = $this->getCountryByCountryCode($request->getOrderFulfillmentAddressCountryCode());
            $zoneId = $this->getZoneId($order->delivery['country']['id'],
                                       $order->delivery['state']);

            if (strcmp($order->delivery['firstname'], $address['firstname']) == 0 &&
                strcmp($order->delivery['lastname'], $address['lastname']) == 0 &&
                strcmp($order->delivery['street_address'], $address['street_address']) == 0 &&
                strcmp($order->delivery['city'], $address['city']) == 0 &&
                (strcmp($zoneId, $address['zone_id']) == 0 ||
                 strcmp($order->delivery['state'], $address['state']) == 0) &&
                strcmp($order->delivery['postcode'], $address['postcode']) == 0 &&
                strcmp($order->delivery['country']['id'], $address['country_id']) == 0) {
                return $address;    
            }
        }

        return NULL;
    }

    /**
     * Convert MFA country code to OSCommerce country object.
     */
    function getCountryByCountryCode ($countryCode) {
        $utilDao = new UtilDAO();
        $country = $utilDao->getCountryByISOCode2($countryCode);
        return $country;
    }

    /**
     * Get OSCommerce country object by id.
     * Use to create a customer address
     */
    function getCountryByCountryId ($countryId) {
        $utilDao = new UtilDAO();
        $country = $utilDao->getCountryByCountryId($countryId);
        return $country;
    }

    /**
     * Get zone id by country id and name
     * Use to create a customer address
     *
     */
    function getZoneId ($countryId, $zoneName) {
        $zone = $this->getZone($countryId, $zoneName);
        $zoneId = ( $zone ) ? $zone['zone_id'] : NULL;

        return $zoneId;
    }
    
    /**
     * Get zone by country id and name.
     * Use to create a customer address
     */
    function getZone ($countryId, $zoneName) {
        $utilDao = new UtilDAO();
        $zone = ( $zoneName ) ? $utilDao->getZone($countryId, $zoneName) : NULL;
        return $zone;
    }
    

    /**
     * Create a random password to not allow customer to login per security.
     * Use max length.
     */
    function getOrderBuyerPassword() {
        $length = 40;
        // start with a blank password - add current time to make it unique.
        $password = "" . time();

        // define possible characters
        $possible = "0123456789abcdefghijklmnopqrstuvwxyz"; 
    
        $i = 0; 
    
        // add random characters to $password until $length is reached
        while ($i < $length) { 

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
            $password .= $char;
            $i++;
        }

        return $password;
    }


    /**
     * Gets affiliates data from MFA order report in the format:
     * <CustomizationInfo>
     *  <Type>url</Type> 
     *  <Data>ClientRequestId=cartId:04928;affilRefId:1;affilClickId:2,OrderChannel=Amazon Checkout (Live)</Data> 
     * </CustomizationInfo>
     */
    function getOrderAffiliateData($request) {
        for ($i = 0; $i < count($this->items); $i++) {
            $item = $this->items[$i];
	        $data = $item->ClientRequestId;
	      	$pos = strpos($data, AMAZON_ORDER_AFFILIATE_REFERENCE_KEY);
	  	if($pos !== false) {
                    $affiliateReference = strstr($data, AMAZON_ORDER_AFFILIATE_REFERENCE_KEY . ':');
                    $affiliateClickthrough = strstr($data, AMAZON_ORDER_AFFILIATE_CLICKTHROUGH_KEY . ':');

                    if ($affiliateReference) {
                        // tokenize list
                        list($affiliateReference) = split(';', $affiliateReference);
                        // split key value pair
                        list($extra, $affiliateReference) = split(':', $affiliateReference);
                        // tokenize list
		        list($affiliateClickthrough) = split(',', $affiliateClickthrough);
                        // split key value pair
		        list($extra, $affiliateClickthrough) = split(':', $affiliateClickthrough);
                        return array(AMAZON_ORDER_AFFILIATE_REFERENCE_KEY => $affiliateReference,
                                     AMAZON_ORDER_AFFILIATE_CLICKTHROUGH_KEY => $affiliateClickthrough);
		   }
                }
          }

        return NULL;
    }

    /**
       This is taken from checkout_process. It is used in the case of
       QTPro not installed. There is a separate function for QT being installed.
    **/
    function updateCancelledOrder(&$order, $insert_id) {
        // initialized for the email confirmation
        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          // Stock Update - Joao Correia
          if (STOCK_LIMITED == 'true') {
            if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                               ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          // Will work with only one option for downloadable products
          // otherwise, we have to build the query dynamically with a loop
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" .   $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
            } else {
                $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            }
            if (tep_db_num_rows($stock_query) > 0) {
              $stock_values = tep_db_fetch_array($stock_query);
              // do not decrement quantities if products_attributes_filename exists
              if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
                $stock_left = $stock_values['products_quantity'] + $order->products[$i]['qty'];
              } else {
                $stock_left = $stock_values['products_quantity'];
              }
              tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
                tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              }
            }
          }
	}

    }


    /**
       This is taken from checkout_process. It is used in the case of
       QTPro not installed. There is a separate function for QT being installed.
    **/

    function updateOrder(&$order, $insert_id) {
	/////////////////////////////////////////////////////////////////////////
	//
        // Insert order product information
        // copied directly from checkout_process.php
        //
	/////////////////////////////////////////////////////////////////////////


	// initialized for the email confirmation
	$products_ordered = '';
	$subtotal = 0;
	$total_tax = 0;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	  // Stock Update - Joao Correia
	  if (STOCK_LIMITED == 'true') {
	    if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                               ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
	  // Will work with only one option for downloadable products
	  // otherwise, we have to build the query dynamically with a loop
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" .   $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
	    } else {
                $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
	    }
	    if (tep_db_num_rows($stock_query) > 0) {
	      $stock_values = tep_db_fetch_array($stock_query);
	      // do not decrement quantities if products_attributes_filename exists
	      if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
		$stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
	      } else {
		$stock_left = $stock_values['products_quantity'];
	      }
              tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
	      if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
		tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
	      }
	    }
	  }
                                                                                                                                                             
	  // Update products_ordered (for bestsellers list)
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where   products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
         
          $order_products_tax = $order->products[$i]['tax'] + $order->products[$i]['shipping_tax'] + $order->products[$i]['promotion_tax'];
          $order_products_total = $order->products[$i]['price'] * $order->products[$i]['qty'] + $order->products[$i]['shipping'] +
                            $order->products[$i]['promotion_price'] + $order->products[$i]['promotion_shipping'];
          $order_products_tax_rate = $order_products_total > 0 ? ($order_products_tax / $order_products_total) * 100 : 0;          

	  $sql_data_array = array('orders_id' => $insert_id,
				  'products_id' => tep_get_prid($order->products[$i]['id']),
				  'products_model' => $order->products[$i]['model'],
				  'products_name' => $order->products[$i]['name'],
				  'products_price' => $order->products[$i]['price'],
				  'final_price' => $order->products[$i]['final_price'],
                                  // the product tax rate
				  'products_tax' => $order_products_tax_rate,
                                  'products_quantity' => $order->products[$i]['qty']);


	  tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
	  $order_products_id = tep_db_insert_id();
          $order->products[$i]['orders_product_id'] = $order_products_id;

	  if ($order_products_id != NULL) {
             // Insert into the amazon_order_products table amazon specific product information now
             $sql_data_array = array('orders_products_id' => $order_products_id,
                                     'products_shipping' => $order->products[$i]['shipping'],
                                     'products_shipping_tax' => $order->products[$i]['shipping_tax'],
                                     'products_promotion_price' => $order->products[$i]['promotion_price'],
                                     'products_promotion_shipping' => $order->products[$i]['promotion_shipping'],
                                     'products_promotion_tax' => $order->products[$i]['promotion_tax'],
                                     'products_promotion_claim_code' => $order->products[$i]['promotion_claim_code'],
                                     'products_promotion_merchant_promotion_id' => $order->products[$i]['promotion_merchant_promotion_id']);
             tep_db_perform(TABLE_AMAZON_ORDERS_PRODUCTS, $sql_data_array);
          }


	  //------insert customer choosen option to order--------
	  $attributes_exist = '0';
	  $products_ordered_attributes = '';
	  if (isset($order->products[$i]['attributes'])) {
	    $attributes_exist = '1';
	    for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
	      if (DOWNLOAD_ENABLED == 'true') {
            $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.   products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                 from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                 left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                  on pa.products_attributes_id=pad.products_attributes_id
                                 where pa.products_id = '" . $order->products[$i]['id'] . "'
                                  and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                  and pa.options_id = popt.products_options_id
                                  and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                  and pa.options_values_id = poval.products_options_values_id
                                  and popt.language_id = '" . $languages_id . "'
                                  and poval.language_id = '" . $languages_id . "'";
            $attributes = tep_db_query($attributes_query);
	      } else {
                  $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from   " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
	      }
	      $attributes_values = tep_db_fetch_array($attributes);
                                                                                                                                                             
	      $sql_data_array = array('orders_id' => $insert_id,
				      'orders_products_id' => $order_products_id,
				      'products_options' => $attributes_values['products_options_name'],
				      'products_options_values' => $attributes_values['products_options_values_name'],
				      'options_values_price' => $attributes_values['options_values_price'],
				      'price_prefix' => $attributes_values['price_prefix']);
	      tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
                                                                                                                                                             
	      if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) &&   tep_not_null($attributes_values['products_attributes_filename'])) {
		$sql_data_array = array('orders_id' => $insert_id,
					'orders_products_id' => $order_products_id,
					'orders_products_filename' => $attributes_values['products_attributes_filename'],
					'download_maxdays' => $attributes_values['products_attributes_maxdays'],
					'download_count' => $attributes_values['products_attributes_maxcount']);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
	      }
	      $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
	    }
	  }
	  //------insert customer choosen option eof ----
	  $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
	  $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
	  $total_cost += $total_products_price;
                                                                                                                                                             
	  $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $this->currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
        }
        ///////////////////////////////////////////////////////////////////////////
        //
        // End copying of code.
        // This is copied directly from checkout_process.php, line 111.
        // 
        ///////////////////////////////////////////////////////////////////////////

    }

    /*
      This updates an order in the case that QTPro is installed, which
      removes inventory by item type if necessary.  It is taken from the
      QTPro version of checkout_process fairly verbatim, with only one
      minor cha nge to get the attributes as necessary.
     */

    function updateOrderQTPro(&$order, $insert_id) {


      $products_ordered = '';
      $subtotal = 0;
      $total_tax = 0;

      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
        // Stock Update - Joao Correia
        //++++ QT Pro: Begin Changed code
        $products_stock_attributes=null;
        if (STOCK_LIMITED == 'true') {
          $products_attributes = $this->getProductAttributes($order->products[$i]);
          //      if (DOWNLOAD_ENABLED == 'true') {
          //++++ QT Pro: End Changed Code
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                          FROM " . TABLE_PRODUCTS . " p
                          LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                           ON p.products_id=pa.products_id
                          LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                           ON pa.products_attributes_id=pad.products_attributes_id
                          WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          // Will work with only one option for downloadable products
          // otherwise, we have to build the query dynamically with a loop
          //++++ QT Pro: Begin Changed code
          //      $products_attributes = $order->products[$i]['attributes'];
          //++++ QT Pro: End Changed Code
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
          if (tep_db_num_rows($stock_query) > 0) {
            $stock_values = tep_db_fetch_array($stock_query);
            //++++ QT Pro: Begin Changed code
            $actual_stock_bought = $order->products[$i]['qty'];
            $download_selected = false;
            if ((DOWNLOAD_ENABLED == 'true') && isset($stock_values['products_attributes_filename']) && tep_not_null($stock_values['products_attributes_filename'])) {
              $download_selected = true;
              $products_stock_attributes='$$DOWNLOAD$$';
            }
            //      If not downloadable and attributes present, adjust attribute stock
            if (!$download_selected && is_array($products_attributes)) {
              $all_nonstocked = true;
              $products_stock_attributes_array = array();
              foreach ($products_attributes as $attribute) {

                //**si** 14-11-05 fix missing att list
                //            if ($attribute['track_stock'] == 1) {
                //              $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
                $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
                if ($attribute['track_stock'] == 1) {
                  //**si** 14-11-05 end

                  $all_nonstocked = false;
                }
              } 
              if ($all_nonstocked) {
                $actual_stock_bought = $order->products[$i]['qty'];

                //**si** 14-11-05 fix missing att list
                asort($products_stock_attributes_array, SORT_NUMERIC);
                $products_stock_attributes = implode(",", $products_stock_attributes_array);
                //**si** 14-11-05 end

              }  else {
                asort($products_stock_attributes_array, SORT_NUMERIC);
                $products_stock_attributes = implode(",", $products_stock_attributes_array);
                $attributes_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
                if (tep_db_num_rows($attributes_stock_query) > 0) {
                  $attributes_stock_values = tep_db_fetch_array($attributes_stock_query);
                  $attributes_stock_left = $attributes_stock_values['products_stock_quantity'] - $order->products[$i]['qty'];
                  tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity = '" . $attributes_stock_left . "' where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
                  $actual_stock_bought = ($attributes_stock_left < 1) ? $attributes_stock_values['products_stock_quantity'] : $order->products[$i]['qty'];
                } else {
                  $attributes_stock_left = 0 - $order->products[$i]['qty'];
                  tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " (products_id, products_stock_attributes, products_stock_quantity) values ('" . tep_get_prid($order->products[$i]['id']) . "', '" . $products_stock_attributes . "', '" . $attributes_stock_left . "')");
                  $actual_stock_bought = 0;
                }
              }
            }
            //        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            //      }
            //      if (tep_db_num_rows($stock_query) > 0) {
            //        $stock_values = tep_db_fetch_array($stock_query);
            // do not decrement quantities if products_attributes_filename exists
            if (!$download_selected) {
              $stock_left = $stock_values['products_quantity'] - $actual_stock_bought;
              tep_db_query("UPDATE " . TABLE_PRODUCTS . " 
                        SET products_quantity = products_quantity - '" . $actual_stock_bought . "' 
                        WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              //++++ QT Pro: End Changed Code
              if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
                tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              }
            }
          }
          //++++ QT Pro: Begin Changed code
        }


        //**si** 14-11-05 fix missing att list
        else {
          if ( is_array($order->products[$i]['attributes']) ) {
            $products_stock_attributes_array = array();
            foreach ($order->products[$i]['attributes'] as $attribute) {
	      $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
            }
            asort($products_stock_attributes_array, SORT_NUMERIC);
            $products_stock_attributes = implode(",", $products_stock_attributes_array);
          }
        }
        //**si** 14-11-05 end



        //++++ QT Pro: End Changed Code
  
        $order_products_tax = $order->products[$i]['tax'] + $order->products[$i]['shipping_tax'] + $order->products[$i]['promotion_tax'];
        $order_products_total = $order->products[$i]['price'] * $order->products[$i]['qty'] + $order->products[$i]['shipping'] + $order->products[$i]['promotion_price'] + $order->products[$i]['promotion_shipping'];
        $order_products_tax_rate = $order_products_total > 0 ? ($order_products_tax / $order_products_total) * 100 : 0;
  
  
        // Update products_ordered (for bestsellers list)
        tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

        //++++ QT Pro: Begin Changed code
        if (!isset($products_stock_attributes)) $products_stock_attributes=null;
        $sql_data_array = array('orders_id' => $insert_id, 
                                'products_id' => tep_get_prid($order->products[$i]['id']), 
                                'products_model' => $order->products[$i]['model'], 
                                'products_name' => $order->products[$i]['name'], 
                                'products_price' => $order->products[$i]['price'], 
                                'final_price' => $order->products[$i]['final_price'], 
                                'products_tax' => $order_products_tax_rate, 
                                'products_quantity' => $order->products[$i]['qty'],
                                'products_stock_attributes' => $products_stock_attributes);
        //++++ QT Pro: End Changed Code
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $order_products_id = tep_db_insert_id();
        $order->products[$i]['orders_product_id'] = $order_products_id;

        if ($order_products_id != NULL) {
          // Insert into the amazon_order_products table amazon specific product information now
          $sql_data_array = array('orders_products_id' => $order_products_id,
                                  'products_shipping' => $order->products[$i]['shipping'],
                                  'products_shipping_tax' => $order->products[$i]['shipping_tax'],
                                  'products_promotion_price' => $order->products[$i]['promotion_price'],
                                  'products_promotion_shipping' => $order->products[$i]['promotion_shipping'],
                                  'products_promotion_tax' => $order->products[$i]['promotion_tax'],
                                  'products_promotion_claim_code' => $order->products[$i]['promotion_claim_code'],
                                  'products_promotion_merchant_promotion_id' => $order->products[$i]['promotion_merchant_promotion_id']);
          tep_db_perform(TABLE_AMAZON_ORDERS_PRODUCTS, $sql_data_array);
        }



        //------insert customer choosen option to order--------
        $attributes_exist = '0';
        $products_ordered_attributes = '';
        if (isset($order->products[$i]['attributes'])) {
          $attributes_exist = '1';
          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
            if (DOWNLOAD_ENABLED == 'true') {
              $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
              $attributes = tep_db_query($attributes_query);
            } else {
              $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            }
            $attributes_values = tep_db_fetch_array($attributes);

            $sql_data_array = array('orders_id' => $insert_id, 
                                    'orders_products_id' => $order_products_id, 
                                    'products_options' => $attributes_values['products_options_name'],
                                    'products_options_values' => $attributes_values['products_options_values_name'], 
                                    'options_values_price' => $attributes_values['options_values_price'], 
                                    'price_prefix' => $attributes_values['price_prefix']);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

            if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
              $sql_data_array = array('orders_id' => $insert_id, 
                                      'orders_products_id' => $order_products_id, 
                                      'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                      'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                      'download_count' => $attributes_values['products_attributes_maxcount']);
              tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
            }
            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
          }
        }
        //------insert customer choosen option eof ----
        $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
        $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
        $total_cost += $total_products_price;

        $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $this->currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
      }


    }

    /*
      Pulls out the option and value id's of each attribute based on the SKU.
      SKU is in the form '3{1}16{2}20' where 3 is the product id, and 1-16 is one attr and 2-20 is an
      other.
      it returns the list of attributes.

      This code includes a db query that will fail unless QTPro is installed.
    */
    function getProductAttributes($product) {
      $values = array();
      $attributes = array();

      $sku = $product['id'];

      $values = split('[{}]', $sku);

      // First value is the product id, so we don't care about that.
      for ($i = 1; $i < sizeof($values); $i +=2 ) {

        // Check to see if we need to track the stock for this:
        $query_raw = 'SELECT products_options_track_stock from products_options WHERE products_options_track_stock = 1 AND products_options_id = ' . $values[$i];

        $query = tep_db_query($query_raw);

        if (tep_db_num_rows($query) > 0) {
          $track = 1;
        }
        else {
          $track = 0;
        }


        $attributes[] = array('option_id' => $values[$i],
                              'value_id' => $values[$i+1],
                              'track_stock' => $track
                              );

      }

      if (sizeof($attributes) == 0) {
        return null;
      }
      else {
        return $attributes;
      }

    }


}
?>
