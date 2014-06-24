<?php
/**
 * @brief Main entry point to processing orders.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
 * @copyright 2008-2012 Amazon Technologies, Inc
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

require_once('includes/application_top.php');
ini_set("display_errors","on");
error_reporting(E_ALL);
// Check for STS module installed, and disable if it is.
if (is_object($sts)) {
        $sts->display_template_output = false;
}

ini_set('include_path','.:' .
	DIR_FS_CATALOG . ":" .
	DIR_FS_CATALOG . "checkout_by_amazon:" .
	DIR_FS_CATALOG . "checkout_by_amazon/library/PHP_Compat-1.6.0a1:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/SOAP-0.12.0:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2/PEAR:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/HTTP-1.4.3:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_Mime-1.5.2:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_mimeDecode-1.5.0:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/Net_Socket-1.0.9:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/Net_URL-1.0.15:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Parser-1.3.1:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Serializer-0.19.0:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Util-1.2.1:" .
        DIR_FS_CATALOG . "checkout_by_amazon/library:" .
        ini_get('include_path'));

require_once('PHP/Compat/Function/strripos.php');
require_once('checkout_by_amazon_order_processor.php');
require_once('checkout_by_amazon_order_monitor.php');
require_once('PEAR/PEAR.php');
require_once('HTTP/Request.php');
require_once("checkout_by_amazon/checkout_by_amazon_constants.php");
require_once('checkout_by_amazon/library/callback/lib/amazon/lib/functions.php');
require_once(DIR_WS_CLASSES . 'shopping_cart.php');
/**
 * Class to process orders requests.
 * Access in the following manner:
 *
 * http://localhost:8080/catalog/checkout_by_amazon_order_request_handler.php?cbaAction=GetOrder
 *
 */
$action = $_REQUEST['cbaAction'];
$data = $_REQUEST['data'];
$ACTION_TYPE_GET_ORDER = 'GetOrder';
$ACTION_TYPE_SHIP_ORDER = 'ShipOrder';
$ACTION_TYPE_REFUND_ORDER = 'RefundOrder';
$ACTION_TYPE_CANCEL_ORDER = 'CancelOrder';
$ACTION_TYPE_MONITOR_ORDER_STATUS = 'MonitorOrderStatus';
$ACTION_TYPE_RESET_CART = 'ResetCart';



// process the request
$processor = new OrderProcessor();
$monitor = new OrderMonitor();
$utilDao = new UtilDAO();

////////////////////////////////////////////////////
//
// rest of actions require post order management to be enabled.
//
////////////////////////////////////////////////////

// close the session to not throw errors in PHP 5
// don't close it above as it clears out the session
// the session must remain open to have access to the cart variable.


////////////////////////////////////////////////////
//
// actions that do not require post order management
//
////////////////////////////////////////////////////
if ($action == $ACTION_TYPE_RESET_CART) {
   # $customer_id = $_SESSION['customer_id'];
	global $customer_id;
    //emptying the cart
       $cart->reset(true);
       $cart = new shoppingCart;

    // close the session to not throw errors in PHP 5

    $return_url = MODULE_PAYMENT_CHECKOUTBYAMAZON_RETURN_URL;
    $uri = '';

    foreach($HTTP_GET_VARS as $key=>$value) {
        $uri .= $key . '=' . urlencode($value) . "&";
    }
	
    if ($return_url != null && $return_url != '') {
        $parsed_url = parse_url($return_url);
        if (is_array($parsed_url) && isset($parsed_url["query"])) {
           tep_redirect($return_url . '&' . $uri);
        }
        else {
           tep_redirect($return_url . '?' . $uri);
        }
    } else {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'SSL'));
    }
    return;
}


elseif ($action == $ACTION_TYPE_GET_ORDER) {
    $processor->getOrders($data);
// TODO: Enable error response codes.
    header("HTTP/1.x 200 OK", true, 200);
}
elseif ($action == $ACTION_TYPE_SHIP_ORDER) {
    $orderFulfillment = $processor->shipOrder($data);
        //header("HTTP/1.x 200 OK", true, 200);
    
    // TODO: Enable error response codes.
    //header("HTTP/1.x 500 Internal Server Error", true, 500);
}
elseif ($action == $ACTION_TYPE_REFUND_ORDER) {
    $orderFulfillment = $processor->refundOrder($data);

    // TODO: Enable error response codes.
    //header("HTTP/1.x 200 OK", true, 200);
}
elseif ($action == $ACTION_TYPE_CANCEL_ORDER) {
    $orderFulfillment = $processor->cancelOrder($data);

    // TODO: Enable error response codes.
    //header("HTTP/1.x 200 OK", true, 200);
}
elseif ($action == $ACTION_TYPE_MONITOR_ORDER_STATUS) {
    // Monitor that shipments, refunds and cancellations actually succeed 
    // asynchronously. When a merchant submits one of the three actions,
    // the UI returns immediately, but this action monitors those submissions
    // in the background and updates the order status to an error state if they fail.
    $monitor->monitorOrderStatusUpdates($data);
}
elseif ($action != null) {
    // indicate a invalid request back to the client
    header("HTTP/1.x 400 Bad Request", true, 400);
}
      /**
      * Sync up Amazon orders by calling GetOrder and MonitorOrderStatus.
      */
     function sendOrderRequests() {
         // Asynchronously send message - don't bother to check response as that will
         // 'hang' the page while waiting
         // A lock prevents multiple GetOrder requests from executing at the same 
         // time.
         $urls = array();
         $url1 = HTTP_SERVER . DIR_WS_CATALOG . 'checkout_by_amazon_order_request_handler.php?cbaAction=MonitorOrderStatus';
         $url2 = HTTP_SERVER . DIR_WS_CATALOG . 'checkout_by_amazon_order_request_handler.php?cbaAction=GetOrder';
         $urls = array($url1, $url2);
         sendRequest($urls, ORDER_STATUS_REQUEST_TIMEOUT);
     }


    /**
     * Actually send the requests asynchronously.
     *
     */
    function sendRequest($urls, $timeout) {
        $chs = array();

        for ($i = 0; $i < count($urls); $i++) {
            $url = $urls[$i];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2); 
            curl_setopt ($ch, CURLOPT_CAINFO, 'checkout_by_amazon/ca-bundle.crt');
            curl_setopt ($ch, CURLOPT_CAPATH, 'checkout_by_amazon/ca-bundle.crt');
            curl_setopt ($ch, CURLOPT_USERAGENT, HTTP_USER_AGENT);


            // it takes two seconds to send the request under normal conditions
            // make sure not to drop the request initialization before that.
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            array_push($chs, $ch);
        }

        $mch = curl_multi_init();

        // now send the requests asynchronously.
        for ($i = 0; $i < count($chs); $i++) {
            $ch = $chs[$i];
            
            curl_multi_add_handle($mch, $ch);
            curl_multi_exec($mch, $active);
        }

        $currentTime = time();

        // wait for the connection for all urls to be established
        do {
            curl_multi_exec($mch, $active);
        } while($active > 0 && time() < $currentTime + $timeout);
    }
require_once('includes/application_bottom.php');
?>
