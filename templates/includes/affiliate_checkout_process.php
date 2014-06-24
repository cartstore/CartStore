<?php
 // Begin Affiliate Program - Sales Tracking

$orders_total=$currencies->format($cart->show_total()- $total_tax); 

tep_session_register('orders_total'); 

$orders_id=$insert_id; 

tep_session_register('orders_id'); 

// End Affiliate Program - Sales Tracking 
?>
<!-- Start Affiliate Program - Sales Tracking --> 
<?php echo '<img src="https://shareasale.com/sale.cfm?amount='.$orders_total.'&tracking='.$orders_id.'&transtype=sale&merchantID=XXXX" width="1" height="1">'; tep_session_unregister('orders_total'); tep_session_unregister('orders_id'); ?> 

<!-- // End Affiliate Program - Sales Tracking -->