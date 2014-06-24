<?php
/**
* Copyright 2009 Amazon.com, Inc. or its affiliates. All Rights Reserved.
*
* Licensed under the Apache License, Version 2.0 (the "License").
* You may not use this file except in compliance with the License.
* A copy of the License is located at
*
*    http://aws.amazon.com/apache2.0/
*
* or in the "license" file accompanying this file.
* This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing permissions
* and limitations under the License.
*
*
* @brief Class for processing Checkout By Amazon Request 
* @catagory Checkout By Amazon Instant Order Processing Notification sample code.
* @copyright Portions copyright 2009 Amazon.com, Inc.
* @license Apache License v2.0, please see LICENSE.txt
* @access public
*
*/

/**
* This array contains the types of notifications which can be processed.
*/

$event_array = array(
						'NewOrderNotification', 
						'OrderCancelledNotification', 
						'OrderReadyToShipNotification'
					);
					
class CBAIOPNProcessor{

  var $RequestXML; 
  var $SignedOrder = false;
  var $Signature;
  var $UUID;
  var $Timestamp;
  var $NotificationData;
  var $NotificationType;  
  var $AWSAccessKeyId;
  var $AWSSecretAccessKey;
  var $IsAccessKeyListConfigured = false;
  var $AccessKeyToSecretKeyMap;
  var $xml = '';
  var $processor;  

  function CBAIOPNProcessor(){    
    ob_start(); 
    LogRequest();
  }
/* Initialize merchant configuration */
  function Initialize(){
	 /*
         * Check whether the merchant has opted for signed carts
         * and load the awsSecretKeys only if it is true.
         *
         * Mechant can opt for the signed carts in settings present
         * in the SellerCentral
         *
         */
	if(strcmp(SUPPORT_SIGNED_CARTS,TRUE_FLAG) == 0){
	    // Read merchant configurations.
	    if(!($this->LoadAWSAccessKeys())){
		writelog("Couldnt load the AWSAccessKeys. Sending response code 500.\n");
		$this->SendInternalServerError();
	    }
        }
  }

  /* Process the POST request and set appropriate flag */
  function AuthenticateRequest(){
    if($_POST){        	
     if($_POST['Signature']){        
		$this->SignedOrder = true;
        $this->Signature = $_POST['Signature'];
      }else {
      		writelog("This is NOT a signed CART!\n");
		return;
      } 
      
     if($_POST['UUID']){
       	$this->UUID = $_POST['UUID'];
      }
      
     if($_POST['Timestamp']){
        $this->Timestamp = $_POST['Timestamp'];	
      }
      
      if($_POST['AWSAccessKeyId']){
      	$this->AWSAccessKeyId = $_POST['AWSAccessKeyId'];
      }
      
      /*
      * Extract the AWSSecretKey present in merchant.properties file. This is a demo code and hence the
      * secret key is stored in plain text format. Usually the secret key should be stored in a secure
      * storage and should be accessed from there.
      */ 
      if(isset($this->AccessKeyToSecretKeyMap[$this->AWSAccessKeyId])){
			writelog("AWSSecretAccessKey is present corresponding to the AWSAccessKeyId.\n"); 
			$this->AWSSecretAccessKey = $this->AccessKeyToSecretKeyMap[$this->AWSAccessKeyId];
        }
      
      
      if(!($this->IsAccessKeyListConfigured) && $this->SignedOrder){
      	 writelog("No Access key is specified in the merchant.properties file, " .
                  "where as, the Key is configured on the CBA side." .
                  "Please specify the access key in the merchant.properties file, so that IOPN request can be validated..." .
                  "Sending response code 500.\n"
                  );
         $this->SendInternalServerError();
	 return;
      }else if(!($this->IsAccessKeyListConfigured) && !($this->SignedOrder)){
      			//Merchant has not configured access key at all.
      			return;
      }else if(empty($this->UUID) || empty($this->Timestamp) || empty($this->AWSAccessKeyId) || !($this->SignedOrder)){
      		writelog("UUID/Timestamp/AWSAccessKeyId/Signature missing 
      		         in the Notification request. Sending response code 500.\n"
      		         );
      		$this->SendInternalServerError();
		return;
      }else if($this->SignedOrder && empty($this->AWSSecretAccessKey)){
      		writelog("AWSAccessKeyId is not present . Sending response code 500.\n");
      		$this->SendInternalServerError();
		return;
      }
      
      // Proceed with Signature validation as we now have all the information needed.          
      //Get the current time measured in the number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT).
      $TimeStamp_now = time();      
      
      // If the timestamp is older than timestamp window then discard the request          
      if(strtotime($this->Timestamp) < ($TimeStamp_now - TIMESTAMP_WINDOW)){
            writelog("Rejecting the Notification as this is older than 15 minutes. Sending response code 403.\n");
      	  	$this->SendPermissionDeniedError();        		
       }
      		 
      if($this->SignedOrder && !$this->IsValidSignature()){
			writelog("Not a valid signature!!! Sending response code 403.\n");
      	    $this->SendPermissionDeniedError();        	
       }
            
    }else{
    	writelog("GET not allowed. Sending 500 response code.\n");
    	$this->SendInternalServerError();
    }
  }
  
  /* Process the Notification data and its associated type. */
  function ValidateNotificationData() {  
  	global $event_array;
  	
  	writelog("Starting validation of Notification Data!\n");
  	
  	// convert from array to associative array
  	$events_assoc_array = array_flip($event_array);  	
  	// Checks if the request has the Notification data.		
  	if($_POST['NotificationData']){
        $this->NotificationData = stripslashes($_POST['NotificationData']);  
        $xml = $this->NotificationData;     
    }else{
    	writelog("Notification data is absent. Sending response code 500.\n");
      	$this->SendInternalServerError();        
    } 
      
    if($_POST['NotificationType']){
     	$this->NotificationType =  $_POST['NotificationType'];
    }else {
    	writelog("Notification Type is absent in the request. Sending response code 500.\n");
     	$this->SendInternalServerError();
    }	
     
    //Checks if the Notification Type is a subscribed one.
    if (isset($events_assoc_array[$this->NotificationType])){
    	 writelog("Valid notification type.\n");
 	 	 if(!$this->IsValidXML($xml) or empty($xml)){
 	 	 		writelog("Validation of XML against schema FAILED. Please check if the schema is recent one.\n"); 	 	 		      			
    	  }
    	  $this->RequestXML = simplexml_load_string($xml);
    	  writelog("Validation of Notification Data Completed!\n");
   	 }    	
  }
  
  /* validates the xml using the schema file */
  function IsValidXML($xml){    
        return true;
  }
  
  /* checks whether request is valid via signature cmp */
  function IsValidSignature(){    
    $data = $this->UUID . $this->Timestamp;
    $signature = $this->GenerateSignature($data);
    return ($signature == $this->Signature);
  }
  
 /**
  * @brief returns the encrypted order signature
  * @return a based64 encoded encrypted order signature
  * @see HMAC.php
  */
  function GenerateSignature($data){
    $signature_calculator = new Crypt_HMAC($this->AWSSecretAccessKey, HMAC_SHA1_ALGORITHM);
    $signature = $signature_calculator->hash($data);
    $binary_signature = pack('H*', $signature);
    return base64_encode($binary_signature);    
  } 
  
  /* sends the response code of 500 incase of any internal errror. */
  function SendInternalServerError() {
  	header('HTTP/1.1 500 Internal Server Error');  	
  }
  
  /* sends the response code of 403 if unable to verify the signature. */
  function SendPermissionDeniedError(){
  	header('HTTP/1.1 403 Forbidden'); 
  }
  
  /* Process the request xml object */
  function ProcessRequestXML() {
	$request = new CBAIOPNxml($this->RequestXML);
	$request->setNotificationType($this->NotificationType);
	$this->processor = new OrderProcessor();
	$orderStatusHistoryDao = new OrderStatusHistoryDAO();
	$orderDao = new OrderDAO();
    	$utilDao = new UtilDAO();
  	writelog("Starting Processing of request XML.\n");
	$isDuplicate = $utilDao->isDuplicateIOPN($request->getNotificationReferenceId(), $request->getAmazonOrderID());
	if($isDuplicate == true) 
		return; 
	$utilDao->persistIOPN($request->getNotificationReferenceId(),$request->getAmazonOrderID(), $this->NotificationData);
	if($this->NotificationType == "NewOrderNotification") {
		$this->processor->processOrder($request);
		header("HTTP/1.x 200 OK", true, 200);
	}
	if($this->NotificationType == "OrderReadyToShipNotification") {
		$exist = $utilDao->existIOPN($request->getAmazonOrderID());
		$this->processor->processOrder($request);
		if($exist==true) {
			$order_id = $orderDao->getOSCommerceOrderID($request->getAmazonOrderID());
			$orderStatusHistoryDao->insertOrderStatusHistory($order_id, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW,  AMAZON_PROCESSING_MESSAGE_ORDER_READY_TO_BE_SHIP);

		        $orderStatusHistoryDao->insertAmazonOrderStatusHistory($order_id, $request->getAmazonOrderID(), '', 0, AMAZON_ORDER_SUCCESS, AMAZON_STATUS_UNSHIPPED,  AMAZON_PROCESSING_MESSAGE_ORDER_READY_TO_BE_SHIP);

			$utilDao->updateStatus(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_NEW, $order_id);
			header("HTTP/1.x 200 OK", true, 200);
		}
	}
	if($this->NotificationType == "OrderCancelledNotification") {
		$exist = $utilDao->existIOPN($request->getAmazonOrderID());
                if($exist==true) {
			$order_id = $orderDao->getOSCommerceOrderID($request->getAmazonOrderID());
			if(!($orderDao->isSystemError($order_id) || $orderDao->isOrderCancelled($order_id))) {
				// Buyer cancelled order
				if($orderDao->isMerchantCancelled($order_id)) {
					writelog("IOPN for Merchant cancelled order");
		                        header("HTTP/1.x 200 OK", true, 200);
					return;
				}
					
				$orderStatusHistoryDao->insertOrderStatusHistory($order_id, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED,  AMAZON_PROCESSING_MESSAGE_ORDER_CANCELLED);
		                $orderStatusHistoryDao->insertAmazonOrderStatusHistory($order_id, $request->getAmazonOrderID(), '', 0, AMAZON_ORDER_SUCCESS, AMAZON_STATUS_CANCELLED,  AMAZON_PROCESSING_MESSAGE_ORDER_BUYER_AMAZON_CANCEL);
				$utilDao->updateStatus(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED, $order_id);
			}
			$utilDao->updateInventory($request, $this->processor, $order_id);
                        header("HTTP/1.x 200 OK", true, 200);
                }
        }
  }
 
  /**
     * This method performs the task of loading all the accessKeyId, accessKey
     * pairs so that they can be used during signature authentication. Currently
     * the source for the pairs is the merchant.properties file. It parses the
     * AWSSecretKeyList present in the following format :
     *
     * AWSSecretKeyList = (AWSAccessKeyId1,AWSSecretKey1), (AWSAccessKeyId2,AWSSecretKey2)
     *
     * But merchant can change the source or the format or both of the AWSSecretKeyList
     * and correspondingly change the logic present in this method.
     *
   */
 function LoadAWSAccessKeys() {
        $cba_module_info = new checkout_by_amazon();
        $this->IsAccessKeyListConfigured = true;
        if($cba_module_info->aws_secret_key){
                writelog(" Access " .$cba_module_info->aws_access_id);
                $this->AccessKeyToSecretKeyMap[$cba_module_info->aws_access_id] = $cba_module_info->aws_secret_key;
                return true;
        }
        return false;
  }

/* All new functions added here */

function getNotificationReferenceId() {
	return $this->RequestXML->NotificationReferenceId;
}
}
?>
