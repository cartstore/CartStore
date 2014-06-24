<?php
/**
 * @brief Handles database persistance of orders_status_history table.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
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
/**
 * Handles database persistance of orders_status_history table.
 * Required for persisting an order.
 */
class OrderStatusHistoryDAO
{
  /**
   * Constructor
   */
  function OrderStatusHistoryDAO()
  {
  }

  /**
   * Get or create a status history whose comment indicates that this is an Amazon
   * order.
   *
   * This is to differientate orders during shipment time.
   * If pending orders contains a list of CBA and non-CBA orders,
   * the annotation will indicate which order to ship confirm/refund/cancel through
   * Amazon.
   */
  function getOrCreateOrderStatusHistory($order, $orderId, $amazonOrderId, $orderData) {
    // de-dup orders in case document acknowledgement failed
    $storedOrderStatusHistory = $this->getOrderStatusHistoryWithComments(
          $orderId, $this->getAmazonOrderStatusHistoryAnnotation());
    if ($storedOrderStatusHistory != NULL) {
      return $storedOrderStatusHistory;
    }
    return $this->createOrderStatusHistory($order, $orderId, $amazonOrderId, $orderData);
  }

  /**
   * Create a status history whose comment indicates that this is an Amazon
   * order.
   *
   * This is to differientate orders during shipment time.
   * If pending orders contains a list of CBA and non-CBA orders,
   * the annotation will indicate which order to ship confirm through
   * Amazon.
   */
  function createOrderStatusHistory($order, $orderId, $amazonOrderId, $orderData) {
    $orderStatusId =  MODULE_PAYMENT_CHECKOUTBYAMAZON_ORDERS_STATUS_AMAZON_PROCESSING;
    $comments = AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION . $amazonOrderId;

    $comments = $comments . "\n\n" . AMAZON_PROCESSING_MESSAGE_ORDER_ITEM_METADATA_INFORMATION;

    for ($i = 0; $i < count($order->products); $i++) {
      $merchantOrderItemCode = $order->products[$i]['orders_product_id'];
      $amazonOrderItemCode = $order->products[$i]['amazon_id'];

      $comments = $comments . $merchantOrderItemCode .
                              ':' . $amazonOrderItemCode . "\n";
    }

    return $this->insertOrderStatusHistory($orderId, $orderStatusId, $comments);
  }

  function insertAmazonOrderStatusHistory($orders_id, $amzn_order_id, $xml, $transactionId, $status, $operation, $comments)   {
    $query = "select id from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = " . $orders_id . " and amazon_order_id = '" . $amzn_order_id  . "' and transactionID = '" . $transactionId . "' and status_of_operation = " . $status . " and amazon_order_status = '" . $operation . "' and comments = '" . tep_db_input($comments) . "'";
    $orders_status_query = tep_db_query($query);
    $modified_now = null;
    if($operation == AMAZON_STATUS_PAYMENT_PENDING || $operation == AMAZON_STATUS_UNSHIPPED)
	$modified_now = 'now()';
    
    if (!tep_db_num_rows($orders_status_query)) {

	   $sql_data_array = array(
        	'amazon_order_id' => $amzn_order_id,
	        'orders_id' => $orders_id,
	        'xml' => tep_db_input($xml),
        	'transactionID' => $transactionId,
	        'status_of_operation' => $status,
        	'amazon_order_status'   => $operation,
	        'comments' => tep_db_input($comments), 
        	'created_on' => 'now()',
	        'modified_on' => $modified_now
   	  );

	   tep_db_perform(TABLE_AMAZON_ORDERS_STATUS_HISTORY, $sql_data_array);
	   $insert_id = tep_db_insert_id();
   }
   else {
        $insert_id =  $orders_status_query['id'];
    }

   return $insert_id;
  }

  function insertOrderStatusHistory($orderId, $orderStatusId, $comments) {  
    // Append amazon order id here to be used for cancellation request.
    // Unfortunately, cancellation request requires amazon order id,
    // even though merchant order id is already mapped.

    $query = "select orders_status_history_id from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . $orderId . "' and orders_status_id = '" . tep_db_input($orderStatusId) . "' and comments = '" . tep_db_input($comments) . "'";
    $orders_status_query = tep_db_query($query);

    if (!tep_db_num_rows($orders_status_query)) {
    	$sql_data_array = array('orders_id' => $orderId,
			    'orders_status_id' => $orderStatusId,
			    'date_added' => 'now()',
                            'customer_notified' => 1,
                            'comments' => $comments);

	    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	    $insert_id = tep_db_insert_id();	
    }
    else {
	$insert_id =  $orders_status_query['orders_status_history_id'];
    }
    $sql_data_array['orders_status_history_id'] = $insert_id;
    return $insert_id;
  }

  function updateAmazonOrderStatusHistory($id, $orders_id, $amazn_status) {

	return tep_db_query("update " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . 
 	" set status_of_operation = '" . tep_db_input($amazn_status) . "', modified_on = now() " . 
	" where id = '" . (int)$id . "' and orders_id = '" . (int)$orders_id . "'");

  }

  /**
   * Update an status history.
   */
  function updateOrderStatusHistory($orderStatusHistoryId, $orderStatusId, $customerNotified, $comments) {
    return tep_db_query("update " . TABLE_ORDERS_STATUS_HISTORY .
        " set orders_status_id = '" . tep_db_input($orderStatusId) .
        "', customer_notified = '" . tep_db_input($customerNotified) .
        "', comments = '" . tep_db_input($comments) .
        "' where orders_status_history_id = '" . (int)$orderStatusHistoryId . "'");
  }

  /**
   * The annotation field value that indicates this order is an Amazon order.
   */
  function getAmazonOrderStatusHistoryAnnotation() {
    return AMAZON_PROCESSING_MESSAGE_ORDER_INFORMATION;
  }
                                                                                                                                                            
  /**
   * Get order status history by order id and order status id.
   */
  function getOrderStatusHistory($orderId, $orderStatusId) {
    // NOTE: This query is partially optimized
    // In the database there is an index on orders_id only.
    $query = "select orders_status_history_id, orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . "   where orders_id = '" . tep_db_input($orderId) . "' and orders_status_id = '" . tep_db_input($orderStatusId) . "' order by date_added";

    $orders_status_history_query = tep_db_query($query);

    if (tep_db_num_rows($orders_status_history_query)) {
      $orders_status_history = tep_db_fetch_array($orders_status_history_query);
      return $orders_status_history;
    }

    return NULL;
  }

  /**
   * Get order status history by order id and comment.
   * We are looking for the specific comment that annotates this order as an
   * Amazon order.
   */
  function getOrderStatusHistoryWithComments($orderId, $comments) {
    // NOTE: This query is partially optimized
    // In the database there is an index on orders_id only.
    $query = "select orders_status_history_id, orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($orderId) . "' and comments like '%" . tep_db_input($comments) . "%' order by date_added";
    $orders_status_history_query = tep_db_query($query);

    if (tep_db_num_rows($orders_status_history_query)) {
      $orders_status_history = tep_db_fetch_array($orders_status_history_query);
      return $orders_status_history;
    }

    return NULL;
  }
}
?>
