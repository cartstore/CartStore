<?php
/**
 * @brief Amazon orders UI. Order management can be done here
 * @category osCommerce Checkout by Amazon Payment Module (Amazon Orders UI)
 * @author Srilakshmi Gorur
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

class CBAMFAxml{

  var $RequestXML;
  var $orderItems;
  function CBAMFAxml($request){
	$this->RequestXML = $request;
  }
  function getAmazonOrderID() {
        return $this->RequestXML["OrderReport"]["AmazonOrderID"];
  }
  function getOrderItems() {
      $items = $this->RequestXML['OrderReport']['Item'];
    
            // if the order contains a single item,
            // we convert the item to an items array for consistency
            $items = $items['AmazonOrderItemCode'] != NULL ? array($items) : $items;
        
            return $items;
  }

     /**
      * Get item price components, such as shipping, tax, subtotal and total.
      *
      */
     function getItemPriceComponents($item) {
         $itemPrice = $item['ItemPrice'];
         $itemPriceComponents = $itemPrice['Component'];
 
         $itemPriceComponents = (!is_array($itemPriceComponents)) ? array($itemPriceComponents) : $itemPriceComponents;
 
         $itemTotal = 0;
         $itemSubTotal = 0;
         $itemShippingTotal = 0;
         $itemTaxTotal = 0;
         $itemShippingTaxTotal = 0;
         
         // Item price contains item price components, such as
         // Principal, Shipping, Tax, Shipping Tax and Commission
         //
         // SubTotal = sum of all item principal
         // 
         // Total = sum of all item price components
         for ($k = 0; $k < count($itemPriceComponents); $k++) {
             $itemPriceComponentType = $itemPriceComponents[$k]['Type'];
             $itemPriceComponentAmount = $itemPriceComponents[$k]['Amount'];
 
             $itemTotal = $itemTotal + $itemPriceComponentAmount;
 
             switch ($itemPriceComponentType) {
                 case "Principal":
                     $itemSubTotal = $itemSubTotal + $itemPriceComponentAmount;               
                     break;
                 case "Shipping":
                     $itemShippingTotal = $itemShippingTotal + $itemPriceComponentAmount;               
                     break;
                 case "Tax":
                     $itemTaxTotal = $itemTaxTotal + $itemPriceComponentAmount;               
                     break;
                 case "ShippingTax":
                     $itemShippingTaxTotal = $itemShippingTaxTotal + $itemPriceComponentAmount;               
                     break;
                     // TODO: if there are any other types
                     // throw an error
             }
         }
 
         // promotions are handled not under item price components, but instead 
         // in its only xml tag element, i.e:
         // <PromotionAdjustments>
         //    <PromotionClaimCode>ABC123</PromotionClaimCode>
         //    <MerchantPromotionID>12345678</MerchantPromotionID>
         //    <Component>
         //       <Type>Principal</Type>
         //       <Amount currency="USD">-1.00</Amount>
         //    </Component>
         // </PromotionAdjustments>
         $itemPromotionTotal = 0;
         $itemPromotionSubTotal = 0;
         $itemPromotionShippingTotal = 0;
         $itemPromotionTaxTotal = 0;
 
         $itemPromotions = $item['Promotion'];
  	 $itemPromotions = $itemPromotions!= NULL  ? array($itemPromotions) : $itemPromotions;

	 for ($i = 0; $i < count($itemPromotions); $i++) {
                $itemPromotion = $itemPromotions[$i];

         $itemPromotionClaimCode = $itemPromotion['PromotionClaimCode'];
         $itemPromotionMerchantPromotionID = $itemPromotion['MerchantPromotionID'];
         $itemPromotionComponents = $itemPromotion['Component'];
 
         $itemPromotionComponents = (!is_array($itemPromotionComponents)) ?
             array($itemPromotionComponents) : $itemPromotionComponents;
 
         for ($k = 0; $k < count($itemPromotionComponents); $k++) {
             $itemPromotionType = $itemPromotionComponents[$k]['Type'];
             $itemPromotionAmount = $itemPromotionComponents[$k]['Amount'];
 
             $itemTotal = $itemTotal + $itemPromotionAmount;
             $itemPromotionTotal = $itemPromotionTotal + $itemPromotionAmount;
 
 
             switch ($itemPromotionType) {
                 case "Principal":
                     $itemPromotionSubTotal = $itemPromotionSubTotal + $itemPromotionAmount;
                     break;
                 case "Shipping":
                     $itemPromotionShippingTotal = $itemPromotionShippingTotal + $itemPromotionAmount;
                     break;
                 case "Tax":
                     $itemPromotionTaxTotal = $itemPromotionTaxTotal + $itemPromotionAmount;
                     break;
                 case "ShippingTax":
                     $itemPromotionTaxTotal = $itemPromotionTaxTotal + $itemPromotionAmount; 
                     break;
                     // TODO: if there are any other types
                     // throw an error
             }
         }
 	}
         // promotions are handled not unrder item price components, but instead 
         // in a separate component for refunds.
 
         return array('total' => $itemTotal,
                      'sub_total' => $itemSubTotal,
                      'shipping_total' => $itemShippingTotal,
                      'tax_total' => $itemTaxTotal,
                      'shipping_tax_total' => $itemShippingTaxTotal,
                      'promotion_claim_code' => $itemPromotionClaimCode,
                      'promotion_merchant_promotion_id' => $itemPromotionMerchantPromotionID,
                      'promotion_total' => $itemPromotionTotal,
                      'promotion_sub_total' => $itemPromotionSubTotal,
                      'promotion_shipping_total' => $itemPromotionShippingTotal,
                      'promotion_tax_total' => $itemPromotionTaxTotal);
     }
 
 

  function getOrderFulfillmentServiceLevel() {
	$serviceLevel= $this->getDisplayShippingLabel();
	if($serviceLevel==null) {
		$serviceLevel = $this->RequestXML["OrderReport"]["FulfillmentData"]["FulfillmentServiceLevel"];
	}
	return $serviceLevel;
		
  }

    /**
     * Gets first name from MFA order report
     */
    function getOrderFulfillmentAddressFirstName() {
        $name = $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["Name"];
        $i = strrpos($name, ' ');
	if($i === false) {
		return $name;
	}
        $firstName = substr($name, 0, $i);
        return $firstName;
    }

    /**
     * Gets last name from MFA order report
     */
    function getOrderFulfillmentAddressLastName() {
        $name = $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["Name"];
        $i = strrpos($name, ' ');
	if($i === false) {
                return "";
        }
        $lastName = substr($name, $i + 1);
        return $lastName;
    }

    /**
     * Gets address field one from MFA order report
     */
    function getOrderFulfillmentAddressFieldOne() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["AddressFieldOne"];
    }

    /**
     * Gets address field two from MFA order report
     */
    function getOrderFulfillmentAddressFieldTwo() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["AddressFieldTwo"];
    }

    /**
     * Gets address city from MFA order report
     */
    function getOrderFulfillmentAddressCity() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["City"];
    }

    /**
     * Gets state or region from MFA order report
     */
    function getOrderFulfillmentAddressStateOrRegion() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["StateOrRegion"];
    }

    /**
     * Gets postal code from MFA order report
     */
    function getOrderFulfillmentAddressPostalCode() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["PostalCode"];
    }

    /**
     * Gets country code MFA order report
     *
     */
    function getOrderFulfillmentAddressCountryCode() {
        return $this->RequestXML["OrderReport"]["FulfillmentData"]["Address"]["CountryCode"];
    }


    /**
     * Gets buyer email address from MFA order report
     *
     */
    function getOrderBuyerEmailAddress() {
	$channel = $this->getOrderChannel();
	if($channel == AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE)  {
	        $amzn_orderid = $this->getAmazonOrderID();
	        $cmnt = "%" . $amzn_orderid . "%";
        	$query = "select distinct orders_id from " . TABLE_ORDERS_STATUS_HISTORY . "   where comments like  '" . tep_db_input($cmnt) . "'";
	        $orders_query = tep_db_query($query);
        	if (tep_db_num_rows($orders_query)) {
			$result = tep_db_fetch_array($orders_query);
			$order_id = $result['orders_id'];
		}
		$query = "select customers_email_address from orders where orders_id = '" . (int)$order_id . "'";
		$orders_query = tep_db_query($query);
                if (tep_db_num_rows($orders_query)) {
                        $result = tep_db_fetch_array($orders_query);
			$buyerEmail = $result['customers_email_address'];
		}
	  return $buyerEmail;
	}
        return $this->RequestXML["OrderReport"]["BillingData"]["BuyerEmailAddress"];
    }
    /**
     * Parse string in the format:
     *
     * 2008-07-23T14:33:02-07:00
     *
     * and converts it to yyyyMMdd hhmmss (OSCommerce formatted)
     */

    function getOrderDate() {
        $date = $this->RequestXML["OrderReport"]["OrderDate"];

        $i = strpos($date, 'T');
        $yyyyMMdd = substr($date, 0, $i);
        $hhmmss = substr($date, $i+1, 8);

        return $yyyyMMdd . ' ' . $hhmmss;
    }

    /**
     * Gets buyer first name from MFA order report
     *
     */
    function getOrderBuyerFirstName() {
        $buyerName = $this->RequestXML["OrderReport"]["BillingData"]["BuyerName"];
        $i = strrpos($buyerName, ' ');
        if($i === false) {
                return $buyerName;
        }

        $firstName = substr($buyerName, 0, $i);
        return $firstName;
    }

    /**
     * Gets buyer last name from MFA order report
     */
  function getOrderBuyerLastName() {
        $buyerName = $this->RequestXML["OrderReport"]["BillingData"]["BuyerName"];
        $i = strrpos($buyerName, ' ');
        if($i === false) {
                return "";
        }

        $lastName = substr($buyerName, $i + 1);
        return $lastName;
    }

  function getDisplayShippingLabel() {
        $items = $this->getOrderItems();
        for ($i = 0; $i < count($items); $i++) {
                $item = $items[$i];
                $customizationInfos = $item["CustomizationInfo"];
                foreach ($customizationInfos as $customizationInfo) {
                    $cInfo = $customizationInfo;
                    if (strcmp($cInfo["Type"], "CBAShipLabel") == 0) {
                                return $cInfo["Data"];
                    }
                }
            }
  }
	
  function getOrderChannel() {
	$items = $this->getOrderItems();
	for ($i = 0; $i < count($items); $i++) {
                $item = $items[$i];
                $customizationInfos = $item["CustomizationInfo"];
                foreach ($customizationInfos as $customizationInfo) {
                    $cInfo = $customizationInfo;
                    if (strcmp($cInfo["Type"], AMAZON_ORDER_CHANNEL_KEY) == 0) {
			if (strcmp($cInfo["Data"], AMAZON_ORDER_CHANNEL_VALUE) == 0) {
	                        return(AMAZON_ORDER_CHANNEL_VALUE); 
			}
			elseif(strcmp($cInfo["Data"], AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE) == 0) {
				return (AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE);
			}
		    }
                }
            }
  }
  /**
     * Determines whether order is CBA order or not.
     * We do not process M@ or CBA sandbox orders via this ecommerce system.
     */

     function isCbaOrder() {
        // search for the customization info that matches orderChannel = Amazon
        // Checkout (Live).
        // It will indicate that this order is a production cba order.
        if (strcmp($this->getOrderChannel(), AMAZON_ORDER_CHANNEL_VALUE) == 0 || strcmp($this->getOrderChannel(), AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE) == 0)
                return true;
        return false;
    }
  
    function isExistingUpdatedOrder() {
        $amzn_orderid = $this->getAmazonOrderID();
        $cmnt = "%" . $amzn_orderid . "%";
        $query = "select comments from " . TABLE_ORDERS_STATUS_HISTORY . "   where comments like  '" . tep_db_input($cmnt) . "'";
        $orders_query = tep_db_query($query);
        if (tep_db_num_rows($orders_query)) {
                $comments = tep_db_fetch_array($orders_query);
                if(strstr($comments['comments'], AMAZON_PROCESSING_MESSAGE_ORDER_ITEM_METADATA_INFORMATION) == FALSE) {
                        return "EXISTING";
                } else   {
                        return "UPDATED";
                }
        }
        else {
                return false;
        }

    }
}
?>
