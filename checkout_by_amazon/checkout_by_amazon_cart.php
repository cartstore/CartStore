<?php
/**
 * @brief Class which represents the shopping Cart in terms of Checkout by Amazon 
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Neil Corkum
 * @author Allison Naaktgeboren
 * @author Joshua Wong
 * @author Srilakshmi Gorur
 * @copyright Portions copyright 2007-2009 Amazon Technologies, Inc
 * @copyright Portions copyright osCommerce, 2002-2008
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 * @note The osc_cart_id will become Checkout by Amazon's ClientRequestID.  If
 *	you choose to use your own checkout success return page, you are 
 *	responsible for using this ID to clear the customer cart
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
require_once('includes/configure.php'); 
require_once('checkout_by_amazon/checkout_by_amazon_item.php');
require_once('checkout_by_amazon/checkout_by_amazon_constants.php');
require_once('checkout_by_amazon/library/callback/lib/amazon/lib/functions.php');
require_once('checkout_by_amazon/library/XmlBuilder.php');
require_once('custom_data.php');

class CheckoutByAmazonCart {
	// products in cart
    var $item_array;
	// configurations associated with this instance of Checkout by Amazon
    var $cba_module_info;
	// cart identifer 
    var $osc_cart_id;
        // client request id
    var $client_request_id;
	// Shipping override
    var $standardShippingOverride;
    var $orderXml;
    var $cd;
/**
 * @brief constructor for the Amazon Cart, based on the osCommerce Cart
 * @param oscommerce_cart the osCommerce cart
 * @param cba_module_info the checkout_by_amazon instance
 * @param languages_id  the languae associated with this checkout cart  
 * @post an amazon cart has been created, based on the osCommerce cart 
 */
    function CheckoutByAmazonCart($oscommerce_cart, $cba_module_info,
				  $languages_id) {
	$this->cd = new CustomData();
	$this->item_array = array();
	$this->cba_module_info = $cba_module_info;
	$this->osc_cart_id = $oscommerce_cart->cartID;
        $this->client_request_id = $this->getClientRequestId();
	$this->xmlBuilder = new XmlBuilder();

	$cart_items = $oscommerce_cart->get_products();
	foreach($cart_items as $item) {
	    // get item description from db
	    $item_description =
		$this->fetchItemInfoFromDB('products_description',
					   TABLE_PRODUCTS_DESCRIPTION,
					   $item['id'], $languages_id);

	    // get item category id from db
	    $item_category_id =
		$this->fetchItemInfoFromDB('categories_id',
					   TABLE_PRODUCTS_TO_CATEGORIES,
					   $item['id'], null);
	    $price = $this->CalculateItemPrice($item);

            $title = $this->getItemTitle($item);
		$id = $item['id'];
	    $cba_item_attribs = array('id' => $id,
				      'title' => $title,
				      'description' => $item_description,
				      'price' => $price,
				      'quantity' => $item['quantity'],
				      'weight' => $item['weight'],
				      'category_id' => $item_category_id,
				      'tax' => $item['tax']);
	    $cba_item = new CheckoutByAmazonItem($cba_item_attribs);

	    array_push($this->item_array, $cba_item);
	}
    }
    
    /**
     * @brief Gets the item title, including product attributes.
     * For example: Matrox G200 MMS (Model: Premium, Memory: 16 mb)
     * @param The cart item object
     * @post an title with attributes appended to it has been created
     *
     */
    function getItemTitle($item) {
        $item_attributes = $item["attributes"];
        $title = $item['name'];
  
        $count = count($item_attributes);
        $i = 0;
  
        if ($count <= 0 || $item_attributes == "") {
            return $title;
        }
  
        if ($count > 0) {
            $title = $title . " (";
        }
  
        // iterate through each product attribute and append it to the title
        foreach($item_attributes as $attribute) {
            $item_options_value_id = $attribute;
            $itemId = (int)$item["id"];
  
            // get the product attribute name and value, a.k.a. Memory: 4 mb
            $query_string = "select opt.products_options_name, opt_val.products_options_values_name from products_attributes as attr, products_options as opt,products_options_values as opt_val where attr.options_values_id = '" . tep_db_input($item_options_value_id) . "' and attr.products_id = '" . tep_db_input($itemId) . "' and opt.products_options_id = attr.options_id and opt.language_id = '1' and opt_val.products_options_values_id = '" . tep_db_input($item_options_value_id) . "' and opt_val.language_id = '1'";
  
            $options_query_string = tep_db_query($query_string);
  
            if(tep_db_num_rows($options_query_string) > 0) {
                $attribute_info = tep_db_fetch_array($options_query_string);
  
                // The actual attribute to append to the title
                $item_options_name = $attribute_info['products_options_name'];
                $item_options_value = $attribute_info['products_options_values_name'];
  
                // some pretty formatting
                if ($i > 0) {
                    $title = $title . " ";
                }
  
                $title = $title . $item_options_name . ": " . $item_options_value;
  
                if (($i + 1) < $count) {
                    $title = $title . ",";
                }   
            }
  
            $i++;
        }
  
        // close the paranthesis where we were placing the attributes into.
        if ($count > 0) {
            $title = $title . ")";
        }
  
        return $title;
    }

  
    /*
     */
    function getClientRequestId() {
        $clientRequestId = '';
  
        // used for clearing the cart
        if($this->osc_cart_id) {
            $clientRequestId = HTML_BUTTON_CLIENT_REQUEST_ID_CART_ID . ':' . $this->osc_cart_id;
        }

        // Check if affiliates information is set.
        if($GLOBALS['HTTP_SESSION_VARS']['affiliate_ref'] != NULL) {
           if ($clientRequestId != '') {
               $clientRequestId .= ';';
           }

           $clientRequestId .= HTML_BUTTON_CLIENT_REQUEST_ID_AFFILIATE_REFERENCE_ID . ':' .
                               $GLOBALS['HTTP_SESSION_VARS']['affiliate_ref'] .
                               ';' . HTML_BUTTON_CLIENT_REQUEST_ID_AFFILIATE_CLICKTHROUGH_ID . ':' .
                               $GLOBALS['HTTP_SESSION_VARS']['affiliate_clickthroughs_id'];
        }

        return $clientRequestId;
    }

    /*
@brief calculates the price of an item with it's attrubes.
@param item a single cart item from oscommercecart.
@return price the final price of the item
*/

    function CalculateItemPrice($item) {
      if( !($item['attributes']) ) {
	return $item['price'];
      }

      $price = $item['price'];

      while( list($option, $value) = each($item['attributes'])) {
	$attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" 
					      . (int)$item['id'] . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
	$attribute_price = tep_db_fetch_array($attribute_price_query);
	if ($attribute_price['price_prefix'] == '+') {
	  $price +=$attribute_price['options_values_price'];
	} else {
	  $price -= $attribute_price['options_values_price'];
	}
 
	
      }

      return $price;
    }

/**
 * @brief creates an XML order for Checkout by Amazon based on the current cart
 * @return the Amazon cart converted into an XML string 
 * @note the string fields, ie SKU, Title, Description, are 
 *	truncated before sending to meet Checkout by Amazon  schema limits   
 */
    function GetOrderXml($apm) {
	global $order;
	$utilDao = new UtilDAO();
	$this->xmlBuilder->Push('Order', array('xmlns'=>XMLNS_VERSION_TAG));

	// clientrequest id, ie cart identifier 
	$this->generateClientRequestID();
	$this->xmlBuilder->Element('ClientRequestId', $this->client_request_id);

	//cart expiration date if set
	$expiration_date = $this->GetCartExpirationDate();
	if ($expiration_date) {
	    $this->xmlBuilder->Element('ExpirationDate', $expiration_date);
	}

	$this->xmlBuilder->Push('Cart');
	
	if((int)SHIPPING_ORIGIN_COUNTRY != (int)$order->delivery['country']['id']){
              $this->PreDefinedRegion="WorldAll";
        }else{
              $this->PreDefinedRegion="USAll";
        }

	// add each cart item & its attributes 
	$this->populateItems($apm, $apm);
        /* Custom Fields Mapping*/
	$this->populateCartCustomData();
	$this->xmlBuilder->Pop('Cart');

// Populate the Tax tables
        $this->populateTaxTables($apm, $utilDao);
// Populate the Shipping methods
        $this->populateShippingMethods($apm);
        $this->populateGenericData($apm);
// Populate the Shipping address
        $this->populateShippingAddress($apm, $utilDao);
// Populate Promotions box to be displayed or not
        $this->populatePromotions($apm);

	//Callbacks
	$this->populateCallbackData($apm);
        $this->xmlBuilder->Pop('Order');
	$xml = $this->xmlBuilder->GetXml();
	return utf8_encode($xml);
    }

      function populateGenericData($apm) {
	 $this->xmlBuilder->Element('IntegratorId',
                            $this->cba_module_info->integrator_id);
        $this->xmlBuilder->Element('IntegratorName',
                             $this->cba_module_info->integrator_name);
        //return/success url. This is to reset the cart and then redirect to the Merchant's return URL
        $link = '?cbaAction=ResetCart';
        $this->success_ret_url =tep_href_link('checkout_by_amazon_order_request_handler.php' . $link, '', 'SSL');
        if($apm === true)
                $this->success_ret_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');

        $this->success_ret_url = str_replace('&amp;', '&', $this->success_ret_url);

        $this->xmlBuilder->Element('ReturnUrl',
                             $this->success_ret_url);
        //cancel url
        $this->xmlBuilder->Element('CancelUrl',
                             tep_href_link('index.php', '', 'SSL'));

     }

	function populateItems($apm) {
	   $this->xmlBuilder->Push('Items');

        foreach($this->item_array as $item) {
            $this->xmlBuilder->Push('Item');

            $this->xmlBuilder->Element('SKU', substr($item->id, 0, MAX_SKU_LEN));
            $this->xmlBuilder->Element('MerchantId',
                                 $this->cba_module_info->merchant_id);
            $this->xmlBuilder->Element("Title" , substr($item->title, 0, MAX_TITLE_LEN));
            $this->xmlBuilder->Element('Description',
                                 substr($item->description, 0, MAX_DESC_LEN));
            // Price
            $this->xmlBuilder->Push('Price');
            $this->xmlBuilder->Element('Amount', $item->price);
            $this->xmlBuilder->Element('CurrencyCode', $item->currency);
            $this->xmlBuilder->Pop('Price');

            $this->xmlBuilder->Element('Quantity', $item->quantity);

                // Checkout by Amazon does not allow weights of 0
                // legitimate products, such as digital downloads have a weight of 0
            if($item->weight != 0) {
                    $this->xmlBuilder->Push('Weight');
                    $this->xmlBuilder->Element('Amount', $item->weight);
                    $this->xmlBuilder->Element('Unit', $item->weight_unit);
                    $this->xmlBuilder->Pop('Weight');
            }

            // item Category
            if($item->category_str == null) {
                    $item->category_str = "Uncategorised";
            }
                    $this->xmlBuilder->Element('Category', $item->category_str);
	    $taxTablesRequired = $shippingMethodsrequired = $apm;
	    if($taxTablesRequired == true) {
	            $this->xmlBuilder->Element('TaxTableId', "tax-rate-for-".substr($item->id, 0, MAX_SKU_LEN));
	    }
	    if($shippingMethodsrequired == true) {
	            $this->xmlBuilder->Push('ShippingMethodIds');
        	    $this->xmlBuilder->Element('ShippingMethodId', "ship-method-".substr($item->id, 0, MAX_SKU_LEN));
	            $this->xmlBuilder->Pop('ShippingMethodIds');
	    }

            // FulfillmentNetwork settings
            $this->xmlBuilder->Element('FulfillmentNetwork',
                                 $this->cba_module_info->fulfillment_network);
	    $this->populateItemCustomData((array)$item);
            $this->xmlBuilder->Pop('Item');
        }

        $this->xmlBuilder->Pop('Items');
}

    function populateItemCustomData($item) {
   /* Loads the Item custom xml */
  	$itemcustom = $this->cd->GetItemCustomXml($item);
	  if(count($itemcustom) > 0){
		  $this->xmlBuilder->Push('ItemCustomData');
		  $this->xmlBuilder->xml .= $this->array2xml($itemcustom,$this->xmlBuilder);
		  $this->xmlBuilder->Pop('ItemCustomData');
	  }
    }
    function populateCartCustomData() {
                /* Loads the Cart custom xml */
                 $cartcustom = $this->cd->GetCartCustomXml();
                 if(count($cartcustom) > 0){
                         $this->xmlBuilder->Push('CartCustomData');
                         $this->xmlBuilder->xml .= $this->array2xml($cartcustom,$this->xmlBuilder);
                         $this->xmlBuilder->Pop('CartCustomData');
                 }

    }

     function populateCallbackData($apm) {
	if($apm === true)
		return;
        if ($this->cba_module_info->callback_required) {
                $this->xmlBuilder->Push('OrderCalculationCallbacks');
                $this->xmlBuilder->Element('CalculateTaxRates',
                    $this->cba_module_info->callback_taxes);
                // not enabled right now since there is no default promotions plugin for
                // OSCommerce.
                $this->xmlBuilder->Element('CalculatePromotions',
                    'false');
                $this->xmlBuilder->Element('CalculateShippingRates',
                                 $this->cba_module_info->callback_shipping);
                $this->xmlBuilder->Element('OrderCallbackEndpoint',
                             $this->cba_module_info->callback_ret_url);
                $this->xmlBuilder->Element('ProcessOrderOnCallbackFailure',
                                 $this->cba_module_info->callback_processOrderOnFailure);
                $this->xmlBuilder->Pop('OrderCalculationCallbacks');
        }
      }





/**
  * @brief converts the associative array into XML
  */     
         function array2xml($data,$xb){
 
                 foreach($data as $key => $val){                 
                         if(is_array($val)){
                                 $xb->Push(ucfirst($key));
                                 $this->array2xml($val,$xb);
                                 $xb->Pop(ucfirst($key));
                         }else{
                                 if(trim($val) !="")
                                         $xb->Element(ucfirst($key),$val);
                         }
                 }
         }

/**
 * @brief encodes the var xml as a base64 string 
 * @return encoded string 
 */
    function GetEncodedOrderXml($apm = false) {
	$this->orderXml = $this->GetOrderXml($apm);
	return base64_encode($this->orderXml);
    }

/**
 * @brief returns the encrypted order signature
 * @return a based64 encoded encrypted order signature 
 * @see HMAC.php  
 */
    function GetOrderSignature() {
	$signature_calculator =
	    new Crypt_HMAC($this->cba_module_info->aws_secret_key, 'sha1');
	$signature = $signature_calculator->hash($this->orderXml);
	$binary_signature = pack('H*', $signature);
	return base64_encode($binary_signature);
    }

/**
 * @brief returns the expiration date for the current cart, based on
 *	the seller time limit & current time 
 * @return the expiration date as a string with preceding header 
 */
    function GetCartExpirationDate() {
	$cart_expiration = $this->cba_module_info->cart_expiration;
	$expiration_date = null;

	if ($cart_expiration > 0) {
	    $current_time = time();
	    $expiration_time = $current_time + (60 * $cart_expiration) - date('Z', $current_time);	// in UTC
	    $expiration_date = date('Y-m-d\TH:i:s\Z', $expiration_time);
	}
	return $expiration_date;
    }

/**
 * @brief generates the HTML checkout by amazon button, containing the order in XML
 * @return a string of code representing the button in HTML
 * @todo optimization to save & not rebuild XML (save db accesses)
 */
    function CheckoutButtonHtml() {

	    $code = HTML_BUTTON_FORM_METHOD ;
	    if (count($this->item_array) == 0) {
	      $code .= "shopping_cart.php";
	    }
	    else {
	      $code.= $this->cba_module_info->form_action_url;
	    }
	    $code.= HTML_BUTTON_INPUT_TYPES; 
	     $code.=
		$this->cba_module_info->
		signing ?  HTML_BUTTON_MERCHANT_SIGNED_ORDER :
		HTML_BUTTON_MERCHANT_UNSIGNED_ORDER;
	    $code.= HTML_BUTTON_BEGIN_ORDER;
	    $code.= $this->GetEncodedOrderXml();

	    // do signing if enabled
	    if ($this->cba_module_info->signing) {
		$code.= HTML_BUTTON_SIGNATURE;
		$code.= $this->GetOrderSignature();
		$code.= HTML_BUTTON_AWS_KEY_ID;
		$code.= $this->cba_module_info->aws_access_id;
	    }

 	$code.=
                HTML_BUTTON_MAIN_BUTTON_LINK.$this->cba_module_info->button_style . HTML_BUTTON_SIZE_TAG. $this->
                cba_module_info->button_size. HTML_BUTTON_END_IMAGE;
	return $code;

    }

/**
 * @brief gets an attribute of an osCommerce product from the 
 *	database 
 * @param item_attribute which property of the item is to be returned
 * @param table which mySQL table to look in
 * @param products_id item whose attribute is to be returned 
 * @param language_id, which language the this osCommerce is in. optional, 
 *	it will be left out of query if null
 * @pre params must all be correct mySQL names, no checking occurs 
 * @return desired attribute of requested item
 * @note tep_db_query() may be vulnerable to mySQL injection attacks, although this 
 *	function should never be invoked outside this class   
 */
    function fetchItemInfoFromDB($item_attribute, $table, $products_id,
				 $language_id) {
	$query_string = "select ";
	$query_string.= $item_attribute;
	$query_string.= " from ";
	$query_string.= $table;
	$query_string.= " where products_id = '";
	$query_string.= $products_id;

	if ($language_id) {
	    $query_string.= "' and language_id = '";
	    $query_string.= $language_id;
	}
	$query_string.= "'";

	$item_info_array = tep_db_fetch_array(tep_db_query($query_string));

	return $item_info_array[$item_attribute];
    }

    function populatePromotions($apm) {
	if($apm === false)
		return;
        $promo_code = "false";
        if (MODULE_PAYMENT_CHECKOUTBYAMAZON_DISABLE_PROMO_CODE == 'True')
                $promo_code = "true";
        $this->xmlBuilder->Element('DisablePromotionCode', $promo_code);
    }

    function populateShippingAddress($apm, $utilDao) {
        global $order;
        if($apm === false)
                return;
        $this->xmlBuilder->Push('ShippingAddresses');
        $this->xmlBuilder->Push('ShippingAddress');
        $this->xmlBuilder->Element('Name', $order->delivery[firstname]);
        $this->xmlBuilder->Element('AddressFieldOne', $order->delivery[street_address]);
	if($order->delivery[suburb] != null)
	        $this->xmlBuilder->Element('AddressFieldTwo', $order->delivery[suburb]);
        $this->xmlBuilder->Element('City', $order->delivery[city]);
        $zone = $utilDao->getZone($order->delivery[country][id], $order->delivery[state]);
	if($zone['zone_code'] != null)
	        $this->xmlBuilder->Element('State', $zone['zone_code']);

        $this->xmlBuilder->Element('PostalCode', $order->delivery[postcode]);
        $this->xmlBuilder->Element('CountryCode', $order->delivery[country][iso_code_2]);
        $this->xmlBuilder->Pop('ShippingAddress');
        $this->xmlBuilder->Pop('ShippingAddresses');
    }
 
   function generateClientRequestID() {
        global $cart;
        $clientRequestID = microtime(true);
        $clientRequestID.=$cart->cartID;
	$this->client_request_id = $clientRequestID;
    }

    function populateTaxTables($apm, $utilDao) {
        global $order;

	  if($apm === false)
		return;
          $this->xmlBuilder->Push('TaxTables');
          foreach($this->item_array as $item) {

          $this->xmlBuilder->Push('TaxTable');
                $this->xmlBuilder->Element('TaxTableId', "tax-rate-for-".substr($item->id, 0, MAX_SKU_LEN));
                $this->xmlBuilder->Push('TaxRules');
                  $this->xmlBuilder->Push('TaxRule');
                          $tax = tep_get_tax_rate($utilDao->tep_get_tax_class_id($item->id), $utilDao->tep_get_country_id($order->delivery[country][iso_code_2]), $utilDao->tep_get_zone_id($order->delivery[state])) / 100.00;
                          $this->xmlBuilder->Element('Rate', $tax);
                          $callback_is_shipping_taxed = "false";
                          if (MODULE_PAYMENT_CHECKOUTBYAMAZON_CALLBACK_IS_SHIPPING_TAXED == 'True')
                                $callback_is_shipping_taxed = "true";
                          $this->xmlBuilder->Element('IsShippingTaxed', $callback_is_shipping_taxed);
			  $this->xmlBuilder->Element('PredefinedRegion', $this->PreDefinedRegion);
                    $this->xmlBuilder->Pop('TaxRule');
                $this->xmlBuilder->Pop('TaxRules');
           $this->xmlBuilder->Pop('TaxTable');
         }
         $this->xmlBuilder->Pop('TaxTables');

    }
   function populateShippingMethods($apm) {
         if($apm === false)
                return;

            global $order;

            $this->xmlBuilder->Push('ShippingMethods');
            foreach($this->item_array as $item) {
              $this->xmlBuilder->Push('ShippingMethod');
                $this->xmlBuilder->Element('ShippingMethodId', "ship-method-".substr($item->id, 0, MAX_SKU_LEN));
                $this->xmlBuilder->Element('ServiceLevel', "Standard");
                $this->xmlBuilder->Push('Rate');
                        $this->xmlBuilder->Push('ShipmentBased');
                          $this->xmlBuilder->Element('Amount', $order->info[shipping_cost]);
                          $this->xmlBuilder->Element('CurrencyCode', $order->info[currency]);
                        $this->xmlBuilder->Pop('ShipmentBased');
                $this->xmlBuilder->Pop('Rate');
                $this->xmlBuilder->Push('IncludedRegions');
                $this->xmlBuilder->Element('PredefinedRegion', $this->PreDefinedRegion);
                $this->xmlBuilder->Pop('IncludedRegions');
                $this->xmlBuilder->Element('DisplayableShippingLabel', $order->info[shipping_method]);
              $this->xmlBuilder->Pop('ShippingMethod');
            }
            $this->xmlBuilder->Pop('ShippingMethods');
    }


}
?>
