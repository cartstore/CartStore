<?php
/**
 * @brief Handles database persistance of orders_status_history table.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
 * @copyright 2007-2009 Amazon Technologies, Inc
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
require_once('checkout_by_amazon_order_status_history_dao.php');
/**
 * Handles database persistance of orders_status_history table.
 * Required for persisting an order.
 */
class OrderDAO
{
  /**
   * Constructor
   */
  function OrderDAO()
  {
  }

  /**
   * Get order by email address and purchased date
   *
   */
  function getOrder($emailAddress, $purchasedDate) {
    // NOTE: This query is partially optimized
    // In the database there is an index on email address only.
    $query = "select orders_id, customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where customers_email_address = '" . tep_db_input($emailAddress) . "' and date_purchased = '" . $purchasedDate . "'";
    $order_query = tep_db_query($query);

    if (tep_db_num_rows($order_query)) {
      $order = tep_db_fetch_array($order_query);
      return $order;
    }

    return NULL;
  }

  function getOrderUsingOrderID($orderId) {
	$query = "select orders_id,customers_email_address, customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($orderId) . "'";
    $order_query = tep_db_query($query);

    if (tep_db_num_rows($order_query)) {
      $order = tep_db_fetch_array($order_query);
      return $order;
    }

    return NULL;


  }

  function getAmazonOrderID($orderId) {
    $amazonOrderId = NULL;

    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistoryWithComments(
        $orderId, $orderStatusHistoryDao->getAmazonOrderStatusHistoryAnnotation());
    if ($storedOrderStatusHistory != NULL) {
        $comments = split(AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION, $storedOrderStatusHistory['comments']);

        if ($comments != NULL && $comments[1] != NULL) {
            // only capture first line as order id, rest is metadata
            $comments = split("\n", $comments[1]);

            if ($comments != NULL && $comments[0] != NULL) {
                $amazonOrderId = $comments[0];
            }
        }
    }

    return $amazonOrderId;
  }

  function getOSCommerceOrderID($amazonOrderId) {
	$found = strstr($amazonOrderId, '-');
	if($found === false || strlen($amazonOrderId) < AMAZON_ORDERID_LENGTH)
		return;
	$query = "select orders_id from orders_status_history where comments like '%" . $amazonOrderId . "%'";
	$order_query = tep_db_query($query);
    	if (tep_db_num_rows($order_query)) {
	      $order = tep_db_fetch_array($order_query);
      	      return $order[orders_id];
    	}
    return null;
  }
  
  function getMerchantOrderItemCode($orderId, $sku) {
	$query = "select orders_products_id from orders_products where orders_id =  '" . $orderId . "'  and products_id =  '" . $sku . "'";
	$order_query = tep_db_query($query);
        if (tep_db_num_rows($order_query)) {
              $order = tep_db_fetch_array($order_query);
              return $order[orders_products_id];
        }
        return null;
  }

  function getOrderStatus($orderId) {
	$query = "select orders_status_id from orders_status_history where orders_id =  '" . $orderId . "' order by date_added desc limit 1";
	$order_query = tep_db_query($query);
     	if (tep_db_num_rows($order_query)) {
              $order = tep_db_fetch_array($order_query);
              return $order[orders_status_id];
        }
	return null;
  }

  function getOrderStatusName($orders_status_id, $languages_id) {
        $query = "select orders_status_name from orders_status where orders_status_id =  '" . $orders_status_id . "' and language_id = '" . $languages_id . "'";
        $order_query = tep_db_query($query);
        if (tep_db_num_rows($order_query)) {
              $order = tep_db_fetch_array($order_query);
              return $order[orders_status_name];
        }
        return null;
  }

  
  /**
   * Parses out amazon order item metadata in the format:
   *
   * Checkout by Amazon Order Number: 105-3298906-8953016
   *
   * Metadata:
   * 35:50066824683530
   * 36:11900693947026
   * ...
   */
  function getAmazonOrderItemMetaData($orderId) {
    $metaData = NULL;
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistoryWithComments(
        $orderId, $orderStatusHistoryDao->getAmazonOrderStatusHistoryAnnotation());
    if ($storedOrderStatusHistory != NULL) {
        // get start of Checkout by Amazon comments
        $comments = split(AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION, $storedOrderStatusHistory['comments']);
        // get metadata section of Checkout by Amazon comments
        if ($comments != NULL && $comments[1] != NULL) {
            $comments = split(AMAZON_PROCESSING_MESSAGE_ORDER_ITEM_METADATA_INFORMATION, $comments[1]);

            if ($comments != NULL && $comments[1] != NULL) {
                $metaData = array();
                $results = explode("\n", $comments[1]);

                foreach ($results as $result) {
                    if (trim($result) == '') {
                        continue;
                    }

                    $data = explode(':', $result);

                    $key = $data[0];
                    $value = $data[1];
                    $metaData[$key] = $value;
                }
            }
        }
    }
    return $metaData;
  }

  /**
   * Checks status history whose comment indicates that this is an Amazon
   * order.
   */
  function checkByAmazonOrderID($amazonOrderId) {
	$found = strstr($amazonOrderId, '-');
	if($found === false)
		return false;
	$query = "select orders_id from orders_status_history where comments like '%" . $amazonOrderId . "%'";
        $order_query = tep_db_query($query);
        if (tep_db_num_rows($order_query)) {
              return true;
        }
    
    return false;
  }

  /**
   * Checks status history whose comment indicates that this is an Amazon
   * order.
   */
  function isAmazonOrder($orderId) {
    if($this->checkByAmazonOrderID($orderId) == true)
	return true;
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistoryWithComments(
        $orderId, $orderStatusHistoryDao->getAmazonOrderStatusHistoryAnnotation());
    // this indicates that the order is an amazon order
    return $storedOrderStatusHistory != NULL;
  }

  /**
   * Checks for a delivered order
   */
  function isOrderDelivered($orderId) {
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistory(
        $orderId, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_DELIVERED);
    
    // this indicates that the order is an amazon order
    return $storedOrderStatusHistory != NULL;
  }

  /**
   * Checks for a cancelled order
   */
  function isOrderCancelled($orderId) {
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistory(
        $orderId, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED);
    
    // this indicates that the order is an amazon order
    return $storedOrderStatusHistory != NULL;
  }

  /**
   * Checks for a refunded order
   */
  function isOrderRefunded($orderId) {
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistory(
        $orderId, MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_REFUNDED);
    
    // this indicates that the order is an amazon order
    return $storedOrderStatusHistory != NULL;
  }


 /**
   * Checks for system error in amazon order
   */
  function isSystemError($orderId) {
    $orderStatusHistoryDao = new OrderStatusHistoryDAO();
    $storedOrderStatusHistory = $orderStatusHistoryDao->getOrderStatusHistory($orderId, AMAZON_STATUS_SYSTEM_ERROR);

    // this indicates that the order is an amazon order
    return $storedOrderStatusHistory != NULL;
  }

  /**
   * Get order by order id
   */
  function getOrderProducts($orderId) {
    $opTable = TABLE_ORDERS_PRODUCTS;
    $aopTable = TABLE_AMAZON_ORDERS_PRODUCTS;
                                                                                                                                                             
    $query = tep_db_query("select " . $opTable . ".orders_products_id as orders_products_id, orders_id, products_id, products_model, products_name, products_price, final_price, products_shipping, products_tax,  products_shipping_tax, products_quantity, products_promotion_tax, products_promotion_shipping, products_promotion_price, products_promotion_claim_code, products_promotion_merchant_promotion_id from " . $opTable . ", " . $aopTable . " where orders_id = '" . (int)$orderId . "' and " . $opTable . ".orders_products_id = " . $aopTable . ".orders_products_id");

    $product;
    $orderProducts = array();

    if (tep_db_num_rows($query)) {
      while ($product = tep_db_fetch_array($query)) {
        array_push($orderProducts, $product);
      }
    }

    return $orderProducts;
  }

  function updatePaymentMethod($order_id, $payment_method) {
        tep_db_query("update " . TABLE_ORDERS .
        " set payment_method = '" . tep_db_input($payment_method) . "' where orders_id = '" . tep_db_input($order_id) . "'");
  }
  /**
   * Cancels an order in oscommerce.
   * Evoked when a order is not available in the Amazon systems (indicating
   * it is cancelled on Amazon's side).
   */
  function updateOrderStatus($merchantOrderId, $status) {
    // update the order status in the database as cancelled,
    // along with a reason and a contact url for the merchant.
    tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$merchantOrderId . "'");
  }

  /**
   * Find order status by status name, such as 'Delivered', 'Cancelled', etc.
   *
   */
  function getOrderStatusByName($orderStatuses, $orderStatusName) {
      for ($i = 0; $i < count($orderStatuses); $i++) {
        // There are two elements in the status array:
        // id and text.
        $result = array_search($orderStatusName, $orderStatuses[$i]);

        if($result) {
            return $orderStatuses[$i]['id'];
        }
      }

      return NULL;
  }


  /**
   * Queries the database for all Amazon orders that have been marked as 
   * shipped, refunded or cancelled. If their document processing status has not 
   * been verified, return the order along with the transaction processing id.
   * The order monitor will verify that their status has been processed
   * successfully or not, and update the UI accordingly.
   */
/*
  function getOrdersWithPendingStatuses() {
    // query all orders who has an order status that is processing.
    $query = "select orders_status_history_id, orders_id, orders_status_id, date_added, customer_notified, comments from " .
        TABLE_ORDERS_STATUS_HISTORY .

        // make sure that the status in on of these three states
        " where orders_status_id in ('" . tep_db_input(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_DELIVERED) . "','" . tep_db_input(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_CANCELLED) . "','". tep_db_input(MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_REFUNDED) . "') " .

        // This comment indicates that we are getting the status of an amazon order
        " and comments like '%" . AMAZON_PROCESSING_MESSAGE_ORDERS_WITH_PENDING_STATUS . "%'" .
        " order by date_added, orders_status_history_id asc";

    $orders_query = tep_db_query($query);
    $orderStatuses = array();
    
    if (tep_db_num_rows($orders_query)) {
        while ($orderStatus = tep_db_fetch_array($orders_query)) {
            array_push($orderStatuses, $orderStatus);
        }

        return $orderStatuses;
    }
    
    return NULL;
  } 
*/
   function getOrdersWithPendingStatuses() {
    // query all orders who has an order status that is processing.
    $query = "select id, orders_id, amazon_order_id, amazon_order_status, status_of_operation, transactionID from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where amazon_order_status in ('" . AMAZON_STATUS_DELIVERED . "','" . AMAZON_STATUS_CANCELLED . "','" . AMAZON_STATUS_REFUNDED . "') and status_of_operation = '" . AMAZON_ORDER_INITIATED . "'";

    $orders_query = tep_db_query($query);
    $orderStatuses = array();

    if (tep_db_num_rows($orders_query)) {
        while ($orderStatus = tep_db_fetch_array($orders_query)) {
            array_push($orderStatuses, $orderStatus);
        }

        return $orderStatuses;
    }

    return NULL;

  }

  function isMerchantCancelled($orders_id) {
        $query = "select comments from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = " . $orders_id . " and amazon_order_status = '" . AMAZON_STATUS_CANCELLED . "'  order by created_on desc limit 1";
        $orders_query = tep_db_query($query);
	writelog("Cancel QUERY == " . $query);
        if (tep_db_num_rows($orders_query)) {
                $amazon_comments = tep_db_fetch_array($orders_query);
		if(!(strrpos($amazon_comments['comments'], MERCHANT_CANCEL_CONFIRMATION_TEXT) === false))
                        return true;
        }
        return false;
  }

  function getPossibleOperations($orders_id, $operation) {
        $flag = false;
        $query = "select amazon_order_status from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = " . $orders_id . " and status_of_operation = 1  order by created_on desc, id  desc limit 1";
        $orders_query = tep_db_query($query);
	switch($operation)
	{
		case AMAZON_STATUS_DELIVERED:
		case AMAZON_STATUS_CANCELLED:
			$status = AMAZON_STATUS_UNSHIPPED;
			$current_status = AMAZON_STATUS_CANCELLED . ", " . AMAZON_STATUS_DELIVERED;
			break;
		case AMAZON_STATUS_REFUNDED:
			$status = AMAZON_STATUS_DELIVERED;
		 	$current_status = AMAZON_STATUS_REFUNDED;
			break;

	}
        if (tep_db_num_rows($orders_query)) {
                $amazon_order_status = tep_db_fetch_array($orders_query);
		if(($operation == AMAZON_STATUS_DELIVERED || $operation == AMAZON_STATUS_CANCELLED) && $amazon_order_status['amazon_order_status'] == $status)
                        $flag =  true;	
                elseif($operation == AMAZON_STATUS_REFUNDED && $amazon_order_status['amazon_order_status'] == $status)
                        $flag =  true;
        }
        $query = "select amazon_order_status from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = " . $orders_id . " and status_of_operation = 0 and amazon_order_status in (" . $current_status . ") order by created_on desc, id  desc limit 1";
        $orders_query = tep_db_query($query);
        if (!tep_db_num_rows($orders_query)) {                                                                                              if($flag == true)
                        return true;
        }
        return false;

  }

}
?>
