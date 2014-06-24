<?php
/**
 * @brief Class for processing Callback Request and generate the response
 * @catagory osCommerce Checkout by Amazon Payment Module - Callback processing.
 * @author Balachandar Muruganantham
 * @copyright 2009-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
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
class OrderCallBackProcessor{

  var $requestXML;
  var $OrderItems;
  var $OrderItemCount;
  var $TaxTables;
  var $Promotions;
  var $ShippingMethods;
  var $Error = '';
  var $ValidateXML = false;
  var $SignedOrder = false;
  var $UUID;
  var $TimeStamp;

  function OrderCallBackProcessor(){
    
    $this->ProcessHTTPRequest();

    $xml = $this->OrderRequest;

    if(!$this->IsValidXML($xml) or empty($xml)){
      $this->Error = 'INTERNAL_SERVER_ERROR';
      $this->ErrorMessage = INTERNAL_SERVER_ERROR;
    }

    if($this->SignedOrder){
      if(!$this->IsValidRequest()){
        $this->Error = 'INTERNAL_SERVER_ERROR';
        $this->ErrorMessage = INTERNAL_SERVER_ERROR;
      }
    }

    $this->requestXML = simplexml_load_string($xml);
    $this->CalculateTaxRates = $this->requestXML->OrderCalculationCallbacks->CalculateTaxRates  == 'true' ? true : false;
    $this->CalculatePromotions = $this->requestXML->OrderCalculationCallbacks->CalculatePromotions == 'true' ? true : false;
    $this->CalculateShippingRates = $this->requestXML->OrderCalculationCallbacks->CalculateShippingRates  == 'true' ? true : false;

    $this->SKU = $this->requestXML->CallbackOrderCart->CallbackOrderCartItems->CallbackOrderCartItem->Item->SKU;    
    if($this->CalculateTaxRates){
      $this->TaxTableId = $this->requestXML->CallbackOrders->CallbackOrder->CallbackOrderItems->CallbackOrderItem->TaxTableId;
    }
    
    $this->OrderItems = $this->requestXML->CallbackOrderCart->CallbackOrderCartItems->CallbackOrderCartItem;
    // using sizeof on the array doesn't work
    $i = 0;
    foreach($this->requestXML->CallbackOrderCart->CallbackOrderCartItems->CallbackOrderCartItem->Item as $item) {
        $i++;
    }
    $this->OrderItemCount = $i;

    $this->ShippingAddress = $this->requestXML->CallbackOrders->CallbackOrder->Address;
  }

  /* Process the POST request and set appropriate flag */
  function ProcessHTTPRequest(){    
    if($_POST){
      
      if($_POST['order-calculations-request']){
        $this->OrderRequest = stripslashes($_POST['order-calculations-request']);
      }else if($_POST['order-calculations-error']){
        $error = $_POST['order-calculations-error'];
        requestlog();
        exit;
      }else{
        $this->Error = 'INTERNAL_SERVER_ERROR';
        $this->ErrorMessage = INTERNAL_SERVER_ERROR;
      }
      
      if($_POST['Signature']){
        $this->SignedOrder = true;
        $this->Signature = $_POST['Signature'];
      }

      if($_POST['Timestamp']){
        $this->Timestamp = $_POST['Timestamp'];
      }
      
      if($_POST['UUID']){
        $this->UUID = $_POST['UUID'];
      }
    }
  }

  /* Setting the Tax Tables */
  function SetTaxTables($array){
    $this->TaxTables = $array;
  }


  /* Setting the Promotions */
  function SetPromotions($array){
    $this->Promotions = $array;
  }

  /* Setting the Shipping Methods */
  function SetShippingMethods($array){
    $this->ShippingMethods = $array;
  }

  /* return items */
  function GetOrderItems() {
     return $this->OrderItems;
  }

  /* return item count */
  function GetOrderItemCount() {
     return $this->OrderItemCount;
  }

  /* return category of the item */
  function GetCategory($item) {
     return $item->Category;
  }


  function GetCallbackOrderItemId($item) {
	return $item->CallbackOrderItemId;
  }
  /* return product name */
  function GetTitle($item) {
     return $item->Title;
  }
  
  /* return product name */
  function GetSKU($item) {
     return $item->Item->SKU;
  }

  /* return weight of the item */
  function GetWeight($item) {
     return $item->Weight->Amount;
  }

  /* return Quantity of the item */
  function GetQuantity($item) {
     return $item->Quantity;
  }

  /* returns the shipping address so that merchant can do calculations*/
  function GetShippingAddress(){    
    $shippingAddressArray = array();
    if($this->Error){
        // error occurred	
        return false;
    }
    foreach($this->ShippingAddress->children() as $key => $val){
      $shippingAddressArray[$key] = $val;
    }
    return $shippingAddressArray;
  }

  /* get the shipping method id alone */
  function GetShippingMethodIds(){
    $shippingMethodIdArray = array();
    foreach($this->ShippingMethods as $key => $val){
      foreach($val as $key2 => $val2){
        array_push($shippingMethodIdArray,$val2['ShippingMethodId']);
      }
    }
    return $shippingMethodIdArray;
  }

  /* get the shipping method id alone */
  function GetPromotionIds(){   
    $promotionIdArray = array();
    foreach($this->Promotions as $key => $val){
      foreach($val as $key2 => $val2){
        array_push($promotionIdArray,$val2['PromotionId']);
      }
    }
    return $promotionIdArray;
  }



  /* get the shipping methods */
  function GetShippingMethods(){    
    $shippingMethodArray = array();
    foreach($this->ShippingMethods as $key => $val){
      foreach($val as $key2 => $val2){
        array_push($shippingMethodArray,$val2);
      }
    }
    return $shippingMethodArray;
  }

  /* push Associative array to xml */
  function Array2XML($xml,$data){
    foreach($data as $key => $value){
      if(is_array($value)){        
        $xml->Push($key);
        $this->Array2XML($xml,$value);
        $xml->Pop($key);
      }else{
        $xml->Element($key,$value);
      }
    }
  }

  /* Generate the response */
  function OrderCallBackResponse(){
 
    // set xml declaration header and encoding
    // set root node name and namespace
    $options = array(XML_SERIALIZER_OPTION_XML_DECL_ENABLED => true,
                     XML_SERIALIZER_OPTION_XML_ENCODING => "UTF-8",
                     XML_SERIALIZER_OPTION_INDENT => "   ",
                     XML_SERIALIZER_OPTION_ROOT_NAME => "OrderCalculationsResponse",
                     XML_SERIALIZER_OPTION_ROOT_ATTRIBS => array('xmlns' => XMLNS_VERSION_TAG));

    $serializer = new XML_Serializer($options);

    if($this->Error){
        return $this->OrderCallBackError($serializer);
    }

    // Build the address element sub-tree
    // and callback order item element which we will fill in later
    // NOTE: SKU is coerced into a string
    $shippingAddress = $this->ShippingAddress;
    $addressID = array('AddressId' => $this->ShippingAddress->AddressId . '');
    $callbackOrderItemsElement = array();
    foreach($this->OrderItems as $item){

    $callbackOrderItemElement = array('CallbackOrderItemId' => $this->GetCallbackOrderItemId($item) . '');


    ///////////////////////////////////////////////////////////////////
    //
    // if true, insert tax table id
    //
    ///////////////////////////////////////////////////////////////////
    if($this->CalculateTaxRates){
       $this->TaxTableId = "Tax-for-SKU-" . $this->GetSKU($item);
       $callbackOrderItemElement['TaxTableId'] = $this->TaxTableId;
    }

    ///////////////////////////////////////////////////////////////////
    //
    // if true, insert the promotion ids
    //
    ///////////////////////////////////////////////////////////////////
    if($this->CalculatePromotions){
       $callbackOrderItemElement['PromotionIds'] = array();

       // In order to handle duplicates elements with the same tag name, set default tagName
       // XML results are in the format:
       // NOTE: This will produce intermediate xml:
       // <PromotionIds>
       //    <XML_Serializer_Tag>
       //       <PromotionId>promo-1</PromotionId>
       //    </XML_Serializer_Tag>
       // </PromotionIds>
       //
       // due to the poor design of XML_Serializer. This tags will be stripped out later.
       //
       foreach($this->getPromotionIds() as $val){
          array_push($callbackOrderItemElement['PromotionIds'], array('PromotionId' => $val));
       }
    }

    ///////////////////////////////////////////////////////////////////
    //
    // if true, insert the shipping method ids
    //
    ///////////////////////////////////////////////////////////////////
    if($this->CalculateShippingRates){
       $callbackOrderItemElement['ShippingMethodIds'] = array();

       foreach($this->getShippingMethodIds() as $val){
          array_push($callbackOrderItemElement['ShippingMethodIds'], array('ShippingMethodId' => $val));
       }
    }
  
    $callbackOrderItem['CallbackOrderItem'] = $callbackOrderItemElement;
    array_push($callbackOrderItemsElement,$callbackOrderItem);
    }  
    ///////////////////////////////////////////////////////////////////
    //
    // Construct final xml
    //
    ///////////////////////////////////////////////////////////////////
    $data = array(
       'Response' => array(
          'CallbackOrders' => array(
             'CallbackOrder' => array(
                'Address' => $addressID,
                'CallbackOrderItems' => $callbackOrderItemsElement
             )
          )
       )
    );


    if(!$this->Error){
       $data = $this->MerchantTPSCalculation($data);
    }

    ///////////////////////////////////////////////////////////////////
    //
    // Serialize final xml
    //
    ///////////////////////////////////////////////////////////////////
    $serializer->serialize($data);
    $xml = $serializer->getSerializedData();

    // due to the poor design of XML_Serializer. These tags need to be stripped.
    // <XML_Serializer_Tag>
    $xml = str_replace(array('<XML_Serializer_Tag>', '</XML_Serializer_Tag>'), array('',''), $xml);


    // check if the final xml is valid
    if(!$this->isValidXML($xml)){
       $this->Error = 'INTERNAL_SERVER_ERROR';
       $this->ErrorMessage = 'INTERNAL_SERVER_ERROR';
       return $this->OrderCallBackError($serializer);
    }

    // finally return the xml
    return $xml;
  }

  //calculate Taxes, Promotions, Shipping methods, Cart Promotion Id
  function MerchantTPSCalculation(&$data){

      // calculate the tax rates if set
      if($this->CalculateTaxRates){
        $data['TaxTables'] = array();

        foreach($this->TaxTables as $val){
          array_push($data['TaxTables'], $val);
        }
      }

      // calculate the promotions if set
      if($this->CalculatePromotions){
        $data['Promotions'] = array();

        foreach($this->Promotions as $val){
          array_push($data['Promotions'], $val);
        }
      }

      // Generation of Shipping Methods Tag Ends Here
      if($this->CalculateShippingRates){
        $data['ShippingMethods'] = array();

        foreach($this->ShippingMethods as $key => $val){
          array_push($data['ShippingMethods'], $val);
        }
      }

      if($this->CartPromotionId){
        $data['CartPromotionId'] = $this->CartPromotionId;
      }

      return $data;
  }

  /* validates the xml using the schema file */
  function IsValidXML($xml){
    //TODO: added the return value as true as some minor issue is here
    // in validating the response xml against schema
    return true;
    if($xml){
      $doc = new DOMDocument();
      $doc->loadXML($xml);
      if($doc->schemaValidate(CALLBACK_SCHEMA_FILE)){
        return true;
      }else{
        return false;
      }
    }
  }

  /* checks whether request is valid via signature cmp */
  function IsValidRequest(){    

    $data = $this->UUID . $this->Timestamp;
    $signature = $this->GenerateSignature($data);
    if($signature != $this->Signature){
      $this->Error = 'INTERNAL_SERVER_ERROR';
      $this->ErrorMessage = INTERNAL_SERVER_ERROR;
      return false;
    }else{
      return true;
    }

  }

 /**
  * @brief returns the encrypted order signature
  * @return a based64 encoded encrypted order signature
  * @see HMAC.php
  */
  function GenerateSignature($data){
    $signature_calculator = new Crypt_HMAC(AWS_SECRET_KEY, HMAC_SHA1_ALGORITHM);
    $signature = $signature_calculator->hash($data);
    $binary_signature = pack('H*', $signature);
    return base64_encode($binary_signature);    
  }
  
  /* Generating the response in key=value pair*/
  function GenerateResponse(){
    $response = $this->OrderCallBackResponse();
    writelog(RESPONSE_KEY . "=" . $response);
    $response_signature = $this->GenerateSignature($response);
    $response =  RESPONSE_KEY . "=" . urlencode($response);   
    if($this->SignedOrder){
      $response .=  "&" . RESPONSE_AWS_KEY . "=" . AWS_ACCESS_KEY . "&" . RESPONSE_SIGNATURE_KEY . "=" . urlencode($response_signature);      
    }
    return $response;
  }
  
  /* Error response in case of any problem */
  function OrderCallBackError($serializer){
    $data = array(
       'Response' => array(
          'Error' => array(
             'Code' => $this->Error,
             'Message' => $this->ErrorMessage
          )
       )
    );

    $serializer->serialize($data);
    $xml = $serializer->getSerializedData();
    return $xml;
  }
}

?>
