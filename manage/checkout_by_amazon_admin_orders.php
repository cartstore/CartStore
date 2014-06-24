<td class="main">
<?php
/* Amazon Payments Code Starts Here */
require_once('includes/application_top.php');
ini_set('include_path','.:' .
            DIR_FS_CATALOG . ":" .
            DIR_FS_CATALOG . "checkout_by_amazon:" .
        ini_get('include_path'));

require_once(DIR_FS_CATALOG . 'checkout_by_amazon/checkout_by_amazon_constants.php');
require_once(DIR_FS_CATALOG . 'checkout_by_amazon/checkout_by_amazon_order_dao.php');
require_once('checkout_by_amazon_order_request_handler.php');
require_once('checkout_by_amazon/checkout_by_amazon_constants.php');

global $shippingCarriers,  $refund_reason, $order, $oID, $oscommerce_amazon_order_status_mapping;

// Sync up amazon orders everytime page is hit.
// Lock ensures that only one request is running
// at anytime.
sendOrderRequests();

$orderDao = new OrderDAO();

$amazon_statuses = array();
/* Unshipped 0 Initated 1 Success 2 Failed 3 Timeout*/
$amazon_statuses[1000][0] = "The order placed by your customer is under review. Please wait for the review to complete.";
$amazon_statuses[1000][1] = "This order has been reviewed by Amazon. Please prepare to ship the order";

$amazon_statuses[1001][0] = "The order placed by your customer is under review. Please wait for the review to complete.";
$amazon_statuses[1001][1] = "This order has been reviewed by Amazon and is ready to ship. Please \"Confirm Shipment\" to charge the buyer's payment method and ship the order. You can also choose to \"Cancel Order\" also";

/* Shipment 0 Initated 1 Success 2 Failed 3 Timeout*/
$amazon_statuses[1002][0] = "Your \"Confirm Shipment\" request has been sent to Amazon. Please wait for the request to complete.";
$amazon_statuses[1002][1] = "Your \"Confirm Shipment\" request succeeded and the buyer's payment method has been charged. ";
$amazon_statuses[1002][2] = "We were unable to charge the customer's payment method.";

/* Cancel 0 Initated 1 Success 2 Failed 3 Timeout*/
$amazon_statuses[1003][0] = "Your \"Cancel Order\" request has been sent to Amazon. Please wait for the request to complete.";
$amazon_statuses[1003][1] = "The order has been Canceled. Note that the request might have been initiated by you or the buyer or by amazon.";
$amazon_statuses[1003][2] = "We were unable to process your \"Cancel Order\" request. ";

/* Refund 0 Initated 1 Success 2 Failed 3 Timeout*/
$amazon_statuses[1004][0] = "Your \"Refund\" request has been sent to Amazon. Please wait for the request to complete.";
$amazon_statuses[1004][1] = "Your \"Refund\" request has been processed and the money has been refunded to the customer's payment method.";
$amazon_statuses[1004][2] = "We were unable to process your \"Refund\" request";

$amazon_statuses[1005][1] = "This order cannot be managed from here. Please visit this (link) to update the status here. This could have happened if you updated the status of this order previously through Seller Central.";


$amazon_status_mapping = array(
			 1000 => "OrderReview",
			 1001 => "Unshipped",
			 1002 => "Shipment",
			 1003 => "Cancel",
			 1004 => "Refund",
			 1005 => "Error"
);
$isAmazonOrder = $orderDao->checkByAmazonOrderID($oID);
/* retrieves amazon order id from amazon payment status table */
if($isAmazonOrder) {
	$order_id = $orderDao->getOSCommerceOrderID($oID);	#amazon_order_id($zf_order_id);
	$amazon_order_id = $oID;
	$oID = $order_id;
}
else {
	$amazon_order_id = $orderDao->getAmazonOrderID($oID);
	$order_id = $oID;
}
?>
	<!-- Amazon Code Starts Here -->
	<script language="javascript">
	<!--
	function confirm_cancel(value){

		if(confirm("<?php echo ENTRY_AMAZON_CANCEL_CONFIRMATION_TEXT;?>")){
			setStatus(value);
			return true;
		}
	
		return false;
	}
	function refundreason(value){

		var refund_reason = document.amazon_payments.refund_reason;

		if(refund_reason.selectedIndex == 0){		
			alert("<?php echo ENTRY_AMAZON_SELECT_THE_REASON_FOR_REFUND; ?>");
			refund_reason.focus();
			return false;
		}
		setStatus(value);
	}

	function confirm_shipment(value){

		if(value == ''){
			return false;
		}

		var shippingCarriers = document.amazon_payments.shippingCarriers;

		if(shippingCarriers.selectedIndex == 0){		
			alert("<?php echo ENTRY_AMAZON_SHIPPING_CARRIER_TEXT; ?>");
			shippingCarriers.focus();
			return false;
		}

		var shipping_service = document.amazon_payments.shipping_service;

		if(shipping_service.value == ""){		
			alert("<?php echo ENTRY_AMAZON_SHIPPING_SERVICE_TEXT; ?>");
			shipping_service.focus();
			return false;
		}

		var tracking_id = document.amazon_payments.tracking_id;

		if(tracking_id.value == ""){		
			alert("<?php echo ENTRY_AMAZON_SHIPPING_TRACKING_TEXT; ?>");
			tracking_id.focus();
			return false;
		}

		setStatus(value);
	}

	function setStatus(value){
		var status = document.amazon_payments.status_code;
		status.value = value;
		document.amazon_payments.submit();
	}


	function disableNotifyCustomer(){
		var notify = document.status.notify;
		notify.disabled = "true";
	}

	//disableNotifyCustomer();
	//ajaxFunction();
	// -->
	</script>
<?php echo tep_draw_form('amazon_payments', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=amazon_order'); ?>
		<table border="0" cellpadding="4" width="100%">
		<tr>
           <td class="main"><strong><?php echo ENTRY_AMAZON_ORDERS_ID; ?></strong> <?php echo $amazon_order_id; ?> ( <a href="https://sellercentral.amazon.com/gp/orders-v2/details?ie=UTF8&orderID=<?php echo $amazon_order_id; ?>" target="_blank">View in Seller Central</a> )</td>
        </tr>
		<tr>
           <td class="main">
		   <b>Order Status History </b><a href="#" onclick="javascript:window.open('<?php echo DIR_WS_CATALOG . "checkout_by_amazon/";?>amazon_payments_what_this.html',
'mywindow','menubar=0,resizable=1,width=650,height=425');return false;"><img src="images/icon_info.gif" align="absmiddle" alt="What's this?" border="0"/></a><br/><br/>
			<?php

				$transaction_status_msg = array("Initiated", "<font color='green'><b>Success</b></font>", "<font color='red'><b>Failed</b></font>","<font color='orange'><b>Timeout</b></font>");
    $orders_history_query = tep_db_query("select amazon_order_id, status_of_operation, amazon_order_status, created_on, modified_on from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($order_id) . "' order by created_on");
    $latest_status_query = tep_db_query("select  amazon_order_status from " . TABLE_AMAZON_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($order_id) . "'  order by created_on desc, id desc limit 1");
    if (tep_db_num_rows($latest_status_query)) {
                $latest_orders_status = tep_db_fetch_array($latest_status_query);
    }

    if (tep_db_num_rows($orders_history_query)) {
	  $txn_table = '<table border="0" cellpadding="5" cellspacing="1" width="90%" bgcolor="#cccccc" class="main">';
          $txn_table .= '<tr bgcolor="#efefef"><th>Amazon Order Status</th><th>OSCommerce Order Status</th><th>Initiated On</th><th>Completed On</th><th>Comments</th></tr>';
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
	$transaction_status = $orders_history['status_of_operation'];
	if($latest_orders_status['amazon_order_status'] == AMAZON_STATUS_PAYMENT_PENDING)
		 $transaction_status = 0;
	$operation = $orders_history['amazon_order_status'];
        $amazon_status_name = $amazon_status_mapping[$operation];
	
	$osCommerce_status = $orderDao->getOrderStatusName($oscommerce_amazon_order_status_mapping[$operation], 1);
	$created_on = $orders_history['created_on'];
	$modified_on = $orders_history['modified_on'];
	$amazon_comments = $amazon_statuses[$operation][$transaction_status];
        $txn_table .= "<tr align='center' bgcolor='#FFFFFF'><td><b>$amazon_status_name</b>  ($transaction_status_msg[$transaction_status])</td><td>$osCommerce_status</td><td>$created_on</td><td>$modified_on</td><td align='left' width='50%'>$amazon_comments</td></tr>";
    }
                                       $txn_table .='<tr bgcolor="#FFFFFF"><td colspan="5">Note: The last row in this table reflects the latest update on this order.</td></tr>';
                                        $txn_table .= '</table>';
                                        echo $txn_table;
	}
					
	?>
			
		   </td>
        </tr>
		  <tr>
			<td class='main'>
				<?php		
				if($orderDao->getPossibleOperations($order_id, AMAZON_STATUS_DELIVERED)) {
					echo ENTRY_AMAZON_SHIPPING_CARRIER ." ". tep_draw_pull_down_menu('shippingCarriers', $shippingCarriers, 'length="32"') ." ". ENTRY_AMAZON_SHIPPING_SERVICE . tep_draw_input_field('shipping_service', '', 'length="32"') ." ". ENTRY_AMAZON_SHIPPING_TRACKING_NUMBER . tep_draw_input_field('tracking_id', '', 'length="32"');
				?> 
				<img src="images/confirm_shipment.jpg" align="absmiddle" style="cursor:pointer; cursor:hand;" onclick="javascript:confirm_shipment('<? echo AMAZON_STATUS_DELIVERED ?>');return false;"/>
				<?php
				}

				if($orderDao->getPossibleOperations($order_id, AMAZON_STATUS_CANCELLED)){
				?>
					<b>or</b> <img src="images/cancel_order.jpg" align="absmiddle" style="cursor:pointer; cursor:hand;" onclick="javascript:confirm_cancel('<? echo AMAZON_STATUS_CANCELLED ?>');return false;" />
				<?php
				}

				if($orderDao->getPossibleOperations($order_id, AMAZON_STATUS_REFUNDED)) {
					echo tep_draw_pull_down_menu('refund_reason', $refund_reason, 'length="32"');
				?>
				<img src="images/refund_order.jpg" align="absmiddle" style="cursor:pointer; cursor:hand;" onclick="javascript:refundreason('<? echo AMAZON_STATUS_REFUNDED ?>');return false;" />

				<?php
				}
				
				echo tep_draw_hidden_field('amazon_order_id',$amazon_order_id);
				echo tep_draw_hidden_field('status_code','0');
				?>
			</td>
		  </tr>
		  </table>
	</form>
 			<!-- Amazon Code Ends Here -->
</td>
