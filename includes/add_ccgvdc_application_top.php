<?php
  define('FILENAME_GV_FAQ', 'gv_faq.php');
  define('FILENAME_GV_REDEEM', 'gv_redeem.php');
  define('FILENAME_GV_REDEEM_PROCESS', 'gv_redeem_process.php');
  define('FILENAME_GV_SEND', 'gv_send.php');
  define('FILENAME_GV_SEND_PROCESS', 'gv_send_process.php');
  define('FILENAME_PRODUCT_LISTING_COL', 'product_listing_col.php');
  define('FILENAME_POPUP_COUPON_HELP', 'popup_coupon_help.php');

  define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');
  define('TABLE_COUPON_GV_QUEUE', 'coupon_gv_queue');
  define('TABLE_COUPON_REDEEM_TRACK', 'coupon_redeem_track');
  define('TABLE_COUPON_EMAIL_TRACK', 'coupon_email_track');
  define('TABLE_COUPONS', 'coupons');
  define('TABLE_COUPONS_DESCRIPTION', 'coupons_description');

// Below are some defines which affect the way the discount coupon/gift voucher system work
// Be careful when editing them.
//
// Set the length of the redeem code, the longer the more secure
  define('SECURITY_CODE_LENGTH', '3');
//
// The settings below determine whether a new customer receives an incentive when they first signup
//
// Set the amount of a Gift Voucher that the new signup will receive, set to 0 for none
//  define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT', '10');  // placed in the admin configuration mystore
//
// Set the coupon ID that will be sent by email to a new signup, if no id is set then no email :)
//  define('NEW_SIGNUP_DISCOUNT_COUPON', '3'); // placed in the admin configuration mystore


////
// Create a Coupon Code. length may be between 1 and 16 Characters
// $salt needs some thought.

  function create_coupon_code($salt="secret", $length = SECURITY_CODE_LENGTH) {
    $ccid = md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    srand((double)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
      $id1=substr($ccid, $random_start,$length);        
      $query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . $id1 . "'");    
      if (tep_db_num_rows($query) == 0) $good_result = 1;
    }
    return $id1;
  }
////
// Update the Customers GV account
  function tep_gv_account_update($customer_id, $gv_id) {
    $customer_gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
    $coupon_gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'");
    $coupon_gv = tep_db_fetch_array($coupon_gv_query);
    if (tep_db_num_rows($customer_gv_query) > 0) {
      $customer_gv = tep_db_fetch_array($customer_gv_query);
      $new_gv_amount = $customer_gv['amount'] + $coupon_gv['coupon_amount'];
   // new code bugfix
   $gv_query = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "' where customer_id = '" . $customer_id . "'");  
	 // original code $gv_query = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "'");
    } else {
      $gv_query = tep_db_query("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . $customer_id . "', '" . $coupon_gv['coupon_amount'] . "')");
    }
  }
////
// Get tax rate from tax description
  function tep_get_tax_rate_from_desc($tax_desc) {
    $tax_query = tep_db_query("select tax_rate from " . TABLE_TAX_RATES . " where tax_description = '" . $tax_desc . "'");
    $tax = tep_db_fetch_array($tax_query);
    return $tax['tax_rate'];
  }
?>