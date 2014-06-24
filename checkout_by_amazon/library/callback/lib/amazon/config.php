<?php
/**
 * @brief Configuration file.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Balachandar Muruganantham
 * @author Joshua Wong
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
define("LIB", DIR_FS_CATALOG . '/lib/');
define("LIB_CALLBACK", DIR_WS_CBA_LIB . 'callback/lib/amazon/lib/');

define('DEBUG',($_GET['isDebug'] == 'true'));

/////////////////////////////////////////////////////////////
//
// Define settings
//
/////////////////////////////////////////////////////////////

require_once(DIR_WS_MODULES . '/payment/checkout_by_amazon.php');
$cba_module_info = new checkout_by_amazon();

define('AWS_ACCESS_KEY',$cba_module_info->aws_access_id); // you can get this from http://sellercentral.amazon.com > Integration > Access Key */
define('AWS_SECRET_KEY',$cba_module_info->aws_secret_key); // you can get this from http://sellercentral.amazon.com > Integration > Access Key */
define('MERCHANT_ID',$cba_module_info->merchant_id); // You can get this from your seller central login - http://sellercentral.amazon.com */
define('MERCHANT_NAME', $cba_module_info->merchant_name); 
define('CALLBACK_URL','http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/catalog/checkout_by_amazon/library/callback/lib/amazon/CallBack.php'); 
define("INTEGRATOR_NAME", 'OSCommercev2.0');
define("INTEGRATOR_ID", 'A2E8RSUU6OBDEI');


/*
 *  Please *do not* edit the following settings
 */

define("XMLNS_VERSION_TAG", 'http://payments.amazon.com/checkout/2008-11-30/');

/* POST form keys sent from Checkout by Amazon. */
define("REQUEST_KEY","order-calculations-request");
define("REQUEST_UUID_KEY","UUID");
define("REQUEST_TIMESTAMP_KEY","Timestamp");
define("REQUEST_SIGNATURE_KEY","Signature");

/* Form keys sent to Checkout by Amazon in response. */
define("RESPONSE_KEY","order-calculations-response");
define("RESPONSE_AWS_KEY","aws-access-key-id");
define("RESPONSE_SIGNATURE_KEY","Signature");

/* Signature Algorithm used. */
define("HMAC_SHA1_ALGORITHM","sha1");

/* Schema Files */
define('CALLBACK_SCHEMA_FILE', $_SERVER["DOCUMENT_ROOT"] . '/catalog/checkout_by_amazon/library/callback/lib/amazon/schema/callback.xsd');
define('ORDER_SCHEMA_FILE', $_SERVER["DOCUMENT_ROOT"] . '/catalog/checkout_by_amazon/library/callback/lib/amazon/schema/order.xsd');

// Path setting
define('LOG_FILE', DIR_FS_CATALOG . '/checkout_by_amazon_callback.log');

$payment_url_array = array(
                           'SANDBOX' => 'https://payments-sandbox.amazon.com/checkout/',
                           'PROD' => 'https://payments.amazon.com/checkout/',
                           );

//including the required files
require_once (DIR_WS_CBA_LIB . 'HMAC.php');
require_once (LIB_CALLBACK . 'functions.php');

if(!DEBUG){
  error_reporting(1);
}
require_once(LIB_CALLBACK . 'order_callback_processor.php');
?>