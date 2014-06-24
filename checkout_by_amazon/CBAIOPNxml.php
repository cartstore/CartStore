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

class CBAIOPNxml {

  var $RequestXML;
  var $orderItems;
  var $notificationType;

  function CBAIOPNxml($request){
	$this->RequestXML = $request;
  }

  function setNotificationType($NotificationType) {
	$this->notificationType = $NotificationType;
  }

  function getNotificationType() {
	return $this->notificationType;
  }

  function getAmazonOrderID() {
        return $this->RequestXML->ProcessedOrder->AmazonOrderID;
  }

  function getNotificationReferenceId() {
        return $this->RequestXML->NotificationReferenceId;
  }
  
  function getOrderItems() {
        $items = $this->RequestXML->ProcessedOrder->ProcessedOrderItems->ProcessedOrderItem;
        // if the order contains a single item,
        // we convert the item to an items array for consistency
        $i=0;
        $orderItems=array();
        foreach($items as $item){
                $orderItems[$i] = $item;
                $i++;
        }
        return $orderItems;
  }
  function getOrderFulfillmentServiceLevel() {
	// This is for backward compatibility with 2.2 plug-in
	$cartCustomData = $this->RequestXML->ProcessedOrder->ProcessedOrderItems->ProcessedOrderItem->CartCustomData;
	if($cartCustomData == null) {
		$serviceLevel= $this->RequestXML->ProcessedOrder->DisplayableShippingLabel;
		return $serviceLevel;
	}
	$shippingServiceLevel = $this->RequestXML->ProcessedOrder->ShippingServiceLevel;
	switch($shippingServiceLevel){
	      case "NextDay":
	      case "Next":
        	$shippingServiceLevel = "OneDay";
	        break;
	      case "SecondDay":
	      case "second":
	        $shippingServiceLevel = "TwoDay";
        	break;
	      default:
        	break;
      }

	if($cartCustomData == null) 
		return $shippingServiceLevel;
	
	$country_code =  $this->getOrderFulfillmentAddressCountryCode();
 	$carrier = $cartCustomData->ShippingMapping->Carrier;
	$standardOverride = $cartCustomData->ShippingMapping->StandardOverride;
	$serviceLevel = $carrier;
	if($shippingServiceLevel == 'Standard' && $standardOverride != 'None') 	{
		$serviceLevel = $shippingServiceLevel .  " - " .  $standardOverride;
		return $serviceLevel;
	}
	if($country_code == 'US') 
		$serviceLevel = $serviceLevel . " - " . $cartCustomData->ShippingMapping->Domestic->$shippingServiceLevel;
	else
		$serviceLevel = $serviceLevel . " - " . $cartCustomData->ShippingMapping->International->$shippingServiceLevel;
	return $serviceLevel;
		
  }

    /**
     * Gets first name from IOPN order report
     */
    function getOrderFulfillmentAddressFirstName() {
        $name = $this->RequestXML->ProcessedOrder->ShippingAddress->Name;
        $i = strrpos($name, ' ');
	if($i === false) {
                return $name;
        }

	$firstName = substr($name, 0, $i);
        return $firstName;
    }

    /**
     * Gets last name from IOPN order report
     */
    function getOrderFulfillmentAddressLastName() {
        $name = $this->RequestXML->ProcessedOrder->ShippingAddress->Name;
        $i = strrpos($name, ' ');
	if($i === false) {
                return "";
        }
	$lastName = substr($name, $i + 1);
        return $lastName;
    }

    /**
     * Gets address field one from IOPN order report
     */
    function getOrderFulfillmentAddressFieldOne() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->AddressFieldOne;
    }

    /**
     * Gets address field two from IOPN order report
     */
    function getOrderFulfillmentAddressFieldTwo() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->AddressFieldTwo;
    }

    /**
     * Gets address city from IOPN order report
     */
    function getOrderFulfillmentAddressCity() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->City;
    }

    /**
     * Gets state or region from IOPN order report
     */
    function getOrderFulfillmentAddressStateOrRegion() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->State;
    }

    /**
     * Gets postal code from IOPN order report
     */
    function getOrderFulfillmentAddressPostalCode() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->PostalCode;
    }

    /**
     * Gets country code IOPN order report
     *
     */
    function getOrderFulfillmentAddressCountryCode() {
        return $this->RequestXML->ProcessedOrder->ShippingAddress->CountryCode;
    }


    /**
     * Gets buyer email address from IOPN order report
     *
     */
    function getOrderBuyerEmailAddress() {
	$buyerEmail = $this->RequestXML->ProcessedOrder->ProcessedOrderItems->ProcessedOrderItem->CartCustomData->MerchantData->Login;
	if($buyerEmail != null)
		return $buyerEmail;
        return $this->RequestXML->ProcessedOrder->BuyerInfo->BuyerEmailAddress;
    }
    /**
     * Parse string in the format:
     *
     * 2008-07-23T14:33:02-07:00
     *
     * and converts it to yyyyMMdd hhmmss (OSCommerce formatted)
     */

    function getOrderDate() {
        $date = $this->RequestXML->ProcessedOrder->OrderDate;

        $i = strpos($date, 'T');
        $yyyyMMdd = substr($date, 0, $i);
        $hhmmss = substr($date, $i+1, 8);

        return $yyyyMMdd . ' ' . $hhmmss;
    }

    /**
     * Gets buyer first name from IOPN order report
     *
     */
    function getOrderBuyerFirstName() {
        $buyerName = $this->RequestXML->ProcessedOrder->BuyerInfo->BuyerName;
        $i = strrpos($buyerName, ' ');
	if($i === false) {
                return $buyerName;
        }
	$firstName = substr($buyerName, 0, $i);
        return $firstName;
    }

    /**
     * Gets buyer last name from IOPN order report
     */
  function getOrderBuyerLastName() {
        $buyerName = $this->RequestXML->ProcessedOrder->BuyerInfo->BuyerName;
        $i = strrpos($buyerName, ' ');
        if($i === false) {
                return "";
        }
	$lastName = substr($buyerName, $i + 1);
        return $lastName;
    }
	
  function getOrderChannel() {
	return $this->RequestXML->ProcessedOrder->OrderChannel;
  }
  /**
     * Determines whether order is CBA order or not.
     * We do not process M@ or CBA sandbox orders via this ecommerce system.
     */

     function isCbaOrder() {
        // search for the customization info that matches orderChannel = Amazon
        // Checkout (Live).
        // It will indicate that this order is a production cba order.
        if (strcmp($this->RequestXML->ProcessedOrder->OrderChannel, AMAZON_APM_ORDER_CHANNEL_VALUE_LIVE) == 0 || strcmp($this->RequestXML->ProcessedOrder->OrderChannel, AMAZON_ORDER_CHANNEL_VALUE) == 0)
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
		} else	 {
                	return "UPDATED";
		}
        }
        else {
                return false;
        }
    }
    
    function getItemPriceComponents($item) {
        $itemPrice = $item['ItemCharges'];
        $itemPriceArray = $itemPrice->Component;

        if($itemPrice == null)
                return;
        $itemPriceComponents = (!is_array($itemPriceComponents)) ? array($itemPriceComponents) : $itemPriceComponents;
        $itemPriceComponents = array();
        $i=0;

        foreach($itemPriceArray as $elem) {
                $itemPriceComponents[$i] = $elem;
                $i++;
        }
        $itemTotal = 0.00;
        $itemSubTotal = 0.00;
        $itemShippingTotal = 0.00;
        $itemTaxTotal = 0.00;
        $itemShippingTaxTotal = 0.00;
        $itemPrincipalPromoTotal = 0.00;
        $itemShippingPromoTotal = 0.00;
	$itemPromotionTotal = 0.00;
        // Item price contains item price components, such as
        // Principal, Shipping, Tax, Shipping Tax and Commission
        //
        // SubTotal = sum of all item principal
        //
        // Total = sum of all item price components
        for ($k = 0; $k < count($itemPriceComponents); $k++) {
            $itemPriceComponentType = $itemPriceComponents[$k]->Type;
            $itemPriceComponentAmount = $itemPriceComponents[$k]->Charge->Amount;
            switch ($itemPriceComponentType) {
                case "Principal":
                    $itemSubTotal = (float)$itemSubTotal + (float)$itemPriceComponentAmount;
                    break;
                case "Shipping":
                    $itemShippingTotal = (float)$itemShippingTotal + (float)$itemPriceComponentAmount;
                    break;
                case "Tax":
                    $itemTaxTotal = (float)$itemTaxTotal + (float)$itemPriceComponentAmount;
                    break;
                case "ShippingTax":
                    $itemShippingTaxTotal = (float)$itemShippingTaxTotal + (float)$itemPriceComponentAmount;
                    break;
                case "PrincipalPromo":
                    $itemPrincipalPromoTotal = (float)$itemPrincipalPromoTotal + (float)$itemPriceComponentAmount;
                    break;
                case "ShippingPromo":
                    $itemShippingPromoTotal = (float)$itemShippingPromoTotal + (float)$itemPriceComponentAmount;
                    break;
                    // TODO: if there are any other types
                    // throw an error
            }

        }
         $itemPromotionTotal = (float)$itemPromotionTotal + (float)$itemPrincipalPromoTotal + (float)$itemShippingPromoTotal;	
	 $itemTotal = (float)$itemSubTotal + (float)$itemShippingTotal + (float)$itemTaxTotal + (float)$itemShippingTaxTotal - (float)$itemPromotionTotal;

         return array('total' => $itemTotal,
                      'sub_total' => $itemSubTotal,
                      'shipping_total' => $itemShippingTotal,
                      'tax_total' => $itemTaxTotal,
                      'shipping_tax_total' => $itemShippingTaxTotal,
                      'promotion_total' => $itemPromotionTotal,
                      'promotion_shipping_total' => $itemShippingPromoTotal);

    }


}
?>
