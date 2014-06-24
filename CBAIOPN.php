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
* @brief Checkout By Amazon Instant Order Processing Notification entry point.
* @catagory Checkout By Amazon  - Instant Order Processing Notification
*
*/

require_once('includes/application_top.php');
require_once('includes/modules/payment/checkout_by_amazon.php');
require_once('checkout_by_amazon/checkout_by_amazon_constants.php');
require_once('checkout_by_amazon/checkout_by_amazon_order_processor.php');
require_once('checkout_by_amazon/library/callback/lib/amazon/lib/functions.php');
require_once('checkout_by_amazon/checkout_by_amazon_order_dao.php');
require_once('checkout_by_amazon/checkout_by_amazon_util_dao.php');
require_once('checkout_by_amazon/checkout_by_amazon_order_status_history_dao.php');
require_once('checkout_by_amazon/CBAIOPNProcessor.php');
require_once('checkout_by_amazon/CBAIOPNxml.php');


    $cbaiopn = new CBAIOPNProcessor();

    // Read merchant configurations.
    $cbaiopn->Initialize();	

    // Authenticates the request. 
    $cbaiopn->AuthenticateRequest(); 

    //Validate the Notification data 
    $cbaiopn->ValidateNotificationData();
    //Process the request xml. To be extended by merchant.
    $cbaiopn->ProcessRequestXML();

?>
