<?php
/*
 $Id: whos_online.php,v 2.00 2006/02/14 15:48:55 harley_vb Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

function request_uri() {

  if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
  }
  else {
    if (isset($_SERVER['argv'])) {
      $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
    }
    else {
      $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
    }
  }

  //return check_url($uri);
  return $uri;
}

  function tep_update_whos_online() {
// WOL 1.6 - Need access to spider_flag and user_agent and moved some assignments up here from below
    global $customer_id, $spider_flag, $user_agent;

		$wo_ip_address = tep_get_ip_address();
    $wo_last_page_url = request_uri();

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);
    $wo_session_id = tep_session_id();
    $user_agent = getenv("HTTP_USER_AGENT");
    $wo_user_agent = $user_agent;

// WOL 1.6 EOF

    if ( $customer_id > 0 ) {
    //if (tep_session_is_registered('customer_id')) {
      //$wo_session_id = tep_session_id();
      $wo_customer_id = $customer_id;

      $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
    if (( $spider_flag ) or (strpos ($user_agent, "Googlebot") > 0 )){
      // Bots are customerID = -1
      $wo_customer_id = -1;
      // The Bots name is extracted from the User Agent in the WOE Admin screen
      $wo_full_name = $user_agent;
      // Session IDs are the WOE primary key.  If a Bot doesn't have a session (normally shouldn't),
      //   use the IP Address as unique identifier, otherwise, use the session ID
      if ( $wo_session_id == "" )
        $wo_session_id = $wo_ip_address;
    } else {
      // Must be a Guest
      $wo_full_name = 'Guest';
      $wo_customer_id = 0;
    }
// WOL 1.6 EOF
    }


// remove entries that have expired
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

    $stored_customer_query = tep_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . tep_db_input($wo_session_id) . "'");
    $stored_customer = tep_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
      tep_db_query("update " . TABLE_WHOS_ONLINE . " set customer_id = '" . (int)$wo_customer_id . "', full_name = '" . tep_db_input($wo_full_name) . "', ip_address = '" . tep_db_input($wo_ip_address) . "', time_last_click = '" . tep_db_input($current_time) . "', last_page_url = '" . tep_db_input($wo_last_page_url) . "' where session_id = '" . tep_db_input($wo_session_id) . "'");
    } else {
      tep_db_query("insert into " . TABLE_WHOS_ONLINE . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url, http_referer, user_agent) values ('" . (int)$wo_customer_id . "', '" . tep_db_input($wo_full_name) . "', '" . tep_db_input($wo_session_id) . "', '" . tep_db_input($wo_ip_address) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($wo_last_page_url) . "', '" . (isset($_SERVER['HTTP_REFERER']) ? tep_db_input($_SERVER['HTTP_REFERER']) : '') . "', '" . tep_db_input($user_agent) . "')");
    }
  }
?>
