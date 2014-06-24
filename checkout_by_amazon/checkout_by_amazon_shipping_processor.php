<?php
  /**
   * @brief Makes call to the configured shipping carrier
   * calculates the shipping cost 
   * @catagory osCommerce Checkout by Amazon Payment Module
   * @author Balachandar Muruganantham 
   * @copyright 2009-2009 Amazon Technologies, Inc
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

    require(DIR_WS_CLASSES . 'shipping.php');

class ShippingProcessor {

  var $CurrencyCode = 'USD';
  var $PreDefinedRegion = "WorldAll";
  var $shippingMethodsArray = array();
  var $shippingMethodArray = array();
  /**
   * Constructor
   */
  function ShippingProcessor()
  {
  }

  /**
   */
  function getQuote($weight) {
    
    global $order, $shipping_weight, $shipping_num_boxes, $customer_id, $total_weight, $cart;
     // this will be aggregate sum of the weight of all items in the order
     $shipping_weight = $weight;
     $total_weight = $weight;
     $shipping_num_boxes = 1;
    $shipping_modules = new shipping();
    $quotes_all = $shipping_modules->quote();
    /* finding out the international option */
    if((int)SHIPPING_ORIGIN_COUNTRY != (int)$order->delivery['country']['id']){
      $this->PreDefinedRegion="WorldAll";
    }else{
      $this->PreDefinedRegion="USAll";
    }
    writelog("PreDefinedRegion -> " . $this->PreDefinedRegion);

                   /* Setting the shipping method */
                $costArray = array();
                $quoteArray = array();
 
    /* Setting the shipping method */
    $costArray = array();
    $quoteArray = array();
    $cnt = 0;
    for ($j = 0; $j < count($quotes_all); $j++) {
      $quotes=$quotes_all[$j];
	$count_methods = count($quotes['methods']);
	$cnt = $cnt + $count_methods;
      for ($i = 0; $i < $count_methods; $i++) {
        $method = $quotes['methods'][$i];
        $cost = (float)$method['cost'];
        $shipping_id = $method['id'];
        array_push($costArray,$cost);
        array_push($quoteArray,  strtoupper($quotes['id']) . " - " .  $method['title'] . " - $" . $cost);

        ////////////////////////////////////////////////////////////////////
        //
        // Now, construct the shipping method array from the results of the quote
        //
        ////////////////////////////////////////////////////////////////////
         
      }
    }
	ob_writelog("Cost array = " ,$costArray);
	ob_writelog("Quote array = " ,$quoteArray);
	asort($costArray);
 	$this->populateShipping($costArray, $quoteArray);	
    return $this->shippingMethodsArray;
  }

  function populateShipping($costArray, $quoteArray) {
	$MAX_SHIPPING_METHODS = 24;
	$standard = 8;
	$expedited = 15;
	$oneday = 20;
	$twoday = 25;
	$cnt = count($costArray);
	$diff = 0;
	$id = 1;
	if($cnt > $MAX_SHIPPING_METHODS) {
		$standard = round(7 * $cnt / $MAX_SHIPPING_METHODS) + 1;
		$expedited = round(7 * $cnt / $MAX_SHIPPING_METHODS) + 1 + $standard;
		$oneday = round(5 * $cnt / $MAX_SHIPPING_METHODS) + 1 + $expedited;
		$twoday = $cnt - ($standard + $expedited + $oneday) + 1;
	}
	$servicelevel = "Standard";
	   foreach ($costArray as $key => $cost) {

		switch($id) {
			case ($id < $standard): 
				$servicelevel = "Standard";
				break;
			case ($id < $expedited): 
				$servicelevel = "Expedited";
				break;
			case ($id < $oneday):
				$servicelevel = "OneDay";
                                break;
			case ($id < $twoday):
				$servicelevel = "TwoDay";
				break;
		}
		$this->populateShippingArray($id, $servicelevel, $cost, $quoteArray[$key]);
		$id = $id + 1;
	}	

  }

  function populateShippingArray($id, $shipping_method, $cost, $name) {
	   $this->shippingMethodArray['ShippingMethod']['ShippingMethodId'] = "ship-method-" . $id;
           $this->shippingMethodArray['ShippingMethod']['ServiceLevel'] = $shipping_method;
           $this->shippingMethodArray['ShippingMethod']['Rate']['ShipmentBased']['Amount'] = $cost;
           $this->shippingMethodArray['ShippingMethod']['Rate']['ShipmentBased']['CurrencyCode'] = $this->CurrencyCode;
           $this->shippingMethodArray['ShippingMethod']['IncludedRegions']['PredefinedRegion'] = $this->PreDefinedRegion;
	   $this->shippingMethodArray['ShippingMethod']['DisplayableShippingLabel'] = $name;
           array_push($this->shippingMethodsArray,$this->shippingMethodArray);
  }
}
?>
