<?php
  
  
  
  
  
  require('includes/configure.php');
  ini_set('include_path', '.:' . DIR_FS_CATALOG . ":" . DIR_FS_CATALOG . "checkout_by_amazon/library/PHP_Compat-1.6.0a1:" . DIR_FS_CATALOG . "checkout_by_amazon/library/SOAP-0.12.0:" . DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2:" . DIR_FS_CATALOG . "checkout_by_amazon/library/PEAR-1.7.2/PEAR:" . DIR_FS_CATALOG . "checkout_by_amazon/library/HTTP-1.4.3:" . DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_Mime-1.5.2:" . DIR_FS_CATALOG . "checkout_by_amazon/library/Mail_mimeDecode-1.5.0:" . DIR_FS_CATALOG . "checkout_by_amazon/library/Net_Socket-1.0.9:" . DIR_FS_CATALOG . "checkout_by_amazon/library/Net_URL-1.0.15:" . DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Parser-1.3.1:" . DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Serializer-0.19.0:" . DIR_FS_CATALOG . "checkout_by_amazon/library/XML_Util-1.2.1:" . DIR_FS_CATALOG . "checkout_by_amazon/library:" . ini_get('include_path'));
  require_once('PEAR/PEAR.php');
  require_once('HTTP/Request.php');
  require_once('checkout_by_amazon/checkout_by_amazon_constants.php');
  require_once('checkout_by_amazon_order_dao.php');
  require_once('checkout_by_amazon_order_request_sender.php');
  
  
  
  $sender = new CBARequestSender();
  $sender->sendOrderRequests();
  
  
  
  
  
  require('includes/application_top.php');
  
  function vendors_email($vendors_id, $oID, $status, $vendor_order_sent)
  {
      $vendor_order_sent = false;
      $debug = 'no';
      $vendor_order_sent = 'no';
      $index2 = 0;
      
      $vendor_data_query = tep_db_query("select v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent from " . TABLE_VENDORS . " v,  " . TABLE_ORDERS_SHIPPING . " os where v.vendors_id=os.vendors_id and v.vendors_id='" . $vendors_id . "' and os.orders_id='" . (int)$oID . "' and v.vendors_status_send='" . $status . "'");
      while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
          $vendor_products[$index2] = array('Vid' => $vendor_order['vendors_id'], 'Vname' => $vendor_order['vendors_name'], 'Vemail' => $vendor_order['vendors_email'], 'Vcontact' => $vendor_order['vendors_contact'], 'Vaccount' => $vendor_order['account_number'], 'Vstreet' => $vendor_order['vendor_street'], 'Vcity' => $vendor_order['vendor_city'], 'Vstate' => $vendor_order['vendor_state'], 'Vzipcode' => $vendor_order['vendors_zipcode'], 'Vcountry' => $vendor_order['vendor_country'], 'Vaccount' => $vendor_order['account_number'], 'Vinstructions' => $vendor_order['vendor_add_info'], 'Vmodule' => $vendor_order['shipping_module'], 'Vmethod' => $vendor_order['shipping_method']);
          if ($debug == 'yes') {
              echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
          }
          $index = 0;
          $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendor_order['vendors_id'] . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");
          while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
              $vendor_products[$index2]['vendor_orders_products'][$index] = array('Pqty' => $vendor_orders_products['products_quantity'], 'Pname' => $vendor_orders_products['products_name'], 'Pmodel' => $vendor_orders_products['products_model'], 'Pprice' => $vendor_orders_products['products_price'], 'Pvendor_name' => $vendor_orders_products['vendors_name'], 'Pcomments' => $vendor_orders_products['vendors_prod_comments'], 'PVprod_id' => $vendor_orders_products['vendors_prod_id'], 'PVprod_price' => $vendor_orders_products['vendors_product_price'], 'spacer' => '-');
              
              if ($debug == 'yes') {
                  echo 'The products query: ' . $vendor_orders_products['products_name'] . '<br>';
              }
              $subindex = 0;
              $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
              if (tep_db_num_rows($vendor_attributes_query)) {
                  while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
                      $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'], 'value' => $vendor_attributes['products_options_values'], 'prefix' => $vendor_attributes['price_prefix'], 'price' => $vendor_attributes['options_values_price']);
                      $subindex++;
                  }
              }
              $index++;
          }
          $index2++;
          
          
          $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID . "'");
          $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
          if ($debug == 'yes') {
              echo 'The number of vendors: ' . sizeof($vendor_products) . '<br>';
          }
          $email = '';
          for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++) {
              $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
              $order_number = $oID;
              $vendors_id = $vendor_products[$l]['Vid'];
              $the_email = $vendor_products[$l]['Vemail'];
              $the_name = $vendor_products[$l]['Vname'];
              $the_contact = $vendor_products[$l]['Vcontact'];
              $email = '<b>To: ' . $the_contact . '  <br>' . $the_name . '<br>' . $the_email . '<br>' . $vendor_products[$l]['Vstreet'] . '<br>' . $vendor_products[$l]['Vcity'] . ', ' . $vendor_products[$l]['Vstate'] . '  ' . $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'From: ' . STORE_OWNER . '<br>' . STORE_NAME_ADDRESS . '<br>' . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . '<br>' . EMAIL_SEPARATOR . '<br>' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br>' . EMAIL_SEPARATOR . '<br>' . '<br> Shipping Method: ' . $vendor_products[$l]['Vmodule'] . ' -- ' . $vendor_products[$l]['Vmethod'] . '<br>' . EMAIL_SEPARATOR . '<br>' . '<br>Dropship deliver to:<br>' . $vendor_delivery_address_list['delivery_company'] . '<br>' . $vendor_delivery_address_list['delivery_name'] . '<br>' . $vendor_delivery_address_list['delivery_street_address'] . '<br>' . $vendor_delivery_address_list['delivery_city'] . ', ' . $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . '<br><br>';
              $email = $email . '<table width="75%" border=0 cellspacing="0" cellpadding="3">



    <tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td><td>Item Comments: </td></tr>';
              for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++) {
                  $product_attribs = '';
                  if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
                      for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                          $product_attribs .= '&nbsp;&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
                      }
                  }
                  $email = $email . '<tr><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>&nbsp;&nbsp;<i>Option<br> ' . $product_attribs . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price'] . '</td><td>' . $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'] . '</b></td></tr>';
              }
          }
          $email = $email . '</table><br><HR><br>';
          tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID, $email . '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
          $vendor_order_sent = true;
          if ($debug == 'yes') {
              echo 'The $email(including headers:<br>Vendor Email Addy' . $the_email . '<br>Vendor Name' . $the_name . '<br>Vendor Contact' . $the_contact . '<br>Body--<br>' . $email . '<br>';
          }
          if ($vendor_order_sent == true) {
              tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = 'yes' where orders_id = '" . (int)$oID . "'");
          }
          
      }
      return true;
  }
  
  define('STATE_PENDING', "1");
  define('STATE_PROCESSING', "2");
  define('STATE_DELIVERED', "3");
  function send_google_req($url, $merid, $merkey, $postargs, $message_log)
  {
      
      $session = curl_init($url);
      $header_string_1 = "Authorization: Basic " . base64_encode($merid . ':' . $merkey);
      $header_string_2 = "Content-Type: application/xml;charset=UTF-8";
      $header_string_3 = "Accept: application/xml;charset=UTF-8";
      fwrite($message_log, sprintf("\r\n%s %s %s\n", $header_string_1, $header_string_2, $header_string_3));
      
      curl_setopt($session, CURLOPT_POST, true);
      curl_setopt($session, CURLOPT_HTTPHEADER, array($header_string_1, $header_string_2, $header_string_3));
      curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
      curl_setopt($session, CURLOPT_HEADER, true);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      
      
      
      $response = curl_exec($session);
      if (curl_errno($session)) {
          die(curl_error($session));
      } else {
          curl_close($session);
      }
      fwrite($message_log, sprintf("\r\n%s\n", $response));
      
      $status_code = array();
      preg_match('/\d\d\d/', $response, $status_code);
      fwrite($message_log, sprintf("\r\n%s\n", $status_code[0]));
      
      switch ($status_code[0]) {
          case 200:
              
              break;
          case 503:
              die('Error 503: Service unavailable. An internal problem prevented us from returning data to you.');
              break;
          case 403:
              die('Error 403: Forbidden. You do not have permission to access this resource, or are over your rate limit.');
              break;
          case 400:
              die('Error 400: Bad request. The parameters passed to the service did not match as expected. The exact error is returned in the XML response.');
              break;
          default:
              die('Error :' . $status_code[0]);
      }
  }
  function google_checkout_state_change($check_status, $status, $oID, $cust_notify, $notify_comments)
  {
      
      
      
      define('API_CALLBACK_MESSAGE_LOG', DIR_FS_CATALOG . "/googlecheckout/response_message.log");
      define('API_CALLBACK_ERROR_LOG', DIR_FS_CATALOG . "/googlecheckout/response_error.log");
      include_once(DIR_FS_CATALOG . '/includes/modules/payment/googlecheckout.php');
      $googlepay = new googlecheckout();
      
      if (!$message_log = fopen(API_CALLBACK_MESSAGE_LOG, "a")) {
          error_func("Cannot open " . API_CALLBACK_MESSAGE_LOG . " file.\n", 0);
          exit(1);
      }
      $google_answer = tep_db_fetch_array(tep_db_query("select google_order_number, order_amount from " . $googlepay->table_order . " where orders_id = " . (int)$oID));
      $google_order = $google_answer['google_order_number'];
      $amt = $google_answer['order_amount'];
      if ($check_status['orders_status'] == STATE_PENDING && $status == STATE_PROCESSING) {
          if ($google_order != '') {
              $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>



                    <charge-order xmlns=\"" . $googlepay->schema_url . "\" google-order-number=\"" . $google_order . "\">



                    <amount currency=\"USD\">" . $amt . "</amount>



                    </charge-order>";
              fwrite($message_log, sprintf("\r\n%s\n", $postargs));
              send_google_req($googlepay->request_url, $googlepay->merchantid, $googlepay->merchantkey, $postargs, $message_log);
              $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>



                    <process-order xmlns=\"" . $googlepay->schema_url . "\" google-order-number=\"" . $google_order . "\"/> ";
              fwrite($message_log, sprintf("\r\n%s\n", $postargs));
              send_google_req($googlepay->request_url, $googlepay->merchantid, $googlepay->merchantkey, $postargs, $message_log);
          }
      }
      
      
      
      if ($check_status['orders_status'] == STATE_PROCESSING && $status == STATE_DELIVERED) {
          $send_mail = "false";
          if ($cust_notify == 1)
              $send_mail = "true";
          if ($google_order != '') {
              $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>



                     <deliver-order xmlns=\"" . $googlepay->schema_url . "\" google-order-number=\"" . $google_order . "\">



                     <send-email> " . $send_mail . "</send-email>



                     </deliver-order> ";
              fwrite($message_log, sprintf("\r\n%s\n", $postargs));
              send_google_req($googlepay->request_url, $googlepay->merchantid, $googlepay->merchantkey, $postargs, $message_log);
              $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>



                     <archive-order xmlns=\"" . $googlepay->schema_url . "\" google-order-number=\"" . $google_order . "\"/>";
              fwrite($message_log, sprintf("\r\n%s\n", $postargs));
              send_google_req($googlepay->request_url, $googlepay->merchantid, $googlepay->merchantkey, $postargs, $message_log);
          }
      }
      if (isset($notify_comments)) {
          $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>



                   <send-buyer-message xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"" . $google_order . "\">



                   <message>" . strip_tags($notify_comments) . "</message>



                   </send-buyer-message>";
          fwrite($message_log, sprintf("\r\n%s\n", $postargs));
          send_google_req($googlepay->request_url, $googlepay->merchantid, $googlepay->merchantkey, $postargs, $message_log);
      }
  }
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_statuses[] = array('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
      $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action)) {
      switch ($action) {
          
          
          
          
          
          
          
          case 'sync_amazon_order':
              
              
              
              
              
              
              $messageStack->add_session(AMAZON_ORDERS_SYNCHRONIZING, 'success');
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
              break;
              
              
              
              
              
          case 'update_order':
              $oID = tep_db_prepare_input($_GET['oID']);
              $status = tep_db_prepare_input($_POST['status']);
              $comments = tep_db_prepare_input($_POST['comments']);
              
              
              $delv_date = $_POST['d_date_year'] . "-" . $_POST['d_date_month'] . "-" . $_POST['d_date_day'];
              $slotid = tep_db_prepare_input($_POST['slotid']);
              tep_db_query("update " . TABLE_ORDERS . " set delivery_date = '" . tep_db_input($delv_date) . "', delivery_time_slotid = '" . $slotid . "' where orders_id = '" . (int)$oID . "'");
              
              $order_updated = false;
              $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
              $check_status = tep_db_fetch_array($check_status_query);
              if (($check_status['orders_status'] != $status) || tep_not_null($comments)) {
                  tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
                  
                  if (SELECT_VENDOR_EMAIL_WHEN == 'Admin' || SELECT_VENDOR_EMAIL_WHEN == 'Both') {
                      if (isset($status)) {
                          $order_sent_query = tep_db_query("select vendor_order_sent, vendors_id from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . $oID . "'");
                          while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {
                              $order_sent_ckeck = $order_sent_data['vendor_order_sent'];
                              $vendors_id = $order_sent_data['vendors_id'];
                              if ($order_sent_ckeck == 'no') {
                                  $vendor_order_sent = false;
                                  vendors_email($vendors_id, $oID, $status, $vendor_order_sent);
                              }
                              
                          }
                          
                      }
                      
                  }
                  
                  $customer_notified = '0';
                  if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
                      $notify_comments = '';
                      if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
                          $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
                      }
                      

                      $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
                      if ($num_rows == 0) {
                          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                          tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                      } else {
                          if ($_POST['notify'] != 'on')
                              unset($notify_comments);
                          google_checkout_state_change($check_status, $status, $oID, $customer_notified, $notify_comments);
                      }
                      
                      $customer_notified = '1';
                  }
                  $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                  tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                  $customer_notified = '1';
              }
              
              if ((isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) || (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on'))) {
                  $comments = ENTRY_CONFIRMED_POINTS . $comments;
                  $customer_query = tep_db_query("SELECT customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
                  $customer_points = tep_db_fetch_array($customer_query);
                  if (tep_db_num_rows($customer_query)) {
                      if (tep_not_null(POINTS_AUTO_EXPIRES)) {
                          $expire = date('Y-m-d', strtotime('+ ' . POINTS_AUTO_EXPIRES . ' month'));
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $customer_points['points_pending'] . "', customers_points_expires = '" . $expire . "' WHERE customers_id = '" . (int)$customer_points['customer_id'] . "'");
                      } else {
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $customer_points['points_pending'] . "' WHERE customers_id = '" . (int)$customer_points['customer_id'] . "'");
                      }
                      if (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on')) {
                          tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
                      }
                      if (isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) {
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS_POINTS_PENDING . " SET points_status = 2 WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
                      }
                  }
              }
              
              
              
              
              
              
              
              if (!$isAmazonOrder || $amazonProcessingTransactionId == null) {
                  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments) . "')");
              } else {
                  
                  
                  $customer_notified = '1';
                  
                  
                  
                  $amazonComments = strlen($comments) > 0 ? $comments . "\n\n" : "";
                  $amazonComments = $amazonComments . AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_UPDATE;
                  $amazonComments = str_replace("[TRANSACTION_ID]", $amazonProcessingTransactionId, $amazonComments);
                  $amazonComments = str_replace("[DATE_TIME]", date('r'), $amazonComments);
                  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($amazonComments) . "')");
              }
              
              
              
              
              
              $order_updated = true;
              if ($order_updated == true) {
                  $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
              } else {
                  $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
              }
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
              break;
          case 'deleteconfirm':
              $oID = tep_db_prepare_input($_GET['oID']);
              tep_remove_order($oID, $_POST['restock']);
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
              break;
      }
  }
  
  if (($action == 'edit') && isset($_GET['oID'])) {
      $oID = tep_db_prepare_input($_GET['oID']);
      $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
      $order_exists = true;
      if (!tep_db_num_rows($orders_query)) {
          $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
      }
  }
  include(DIR_WS_CLASSES . 'order.php');
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



  



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title><?php
  echo TITLE;
?></title>



<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />



   <script type="text/javascript" src="templates/admin/js/mootools-release-1.11.js"></script>



  <script type="text/javascript" src="templates/admin/js/mootools.bgiframe.js"></script>



  <script type="text/javascript" src="templates/admin/js/rokmoomenu.js"></script>



     



<script language="javascript" src="includes/general.js"></script>



</head>



<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">



<!-- header //-->



<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>



<!-- header_eof //-->







<!-- body //-->



<table border="0" width="100%" cellspacing="2" cellpadding="2">



  <tr>



    <td width="<?php
  echo BOX_WIDTH;
?>" valign="top"><table border="0" width="<?php
  echo BOX_WIDTH;
?>" cellspacing="1" cellpadding="1" class="columnLeft">



<!-- left_navigation //-->



<?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>



<!-- left_navigation_eof //-->



    </table></td>



<!-- body_text //-->



    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">



<?php
  if (($action == 'edit') && ($order_exists == true)) {
      $order = new order($oID);
?>



      <tr>



        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">



          <tr>



            <!-- PWA BOF -->



            <td class="pageHeading"><?php
      echo HEADING_TITLE . (($order->customer['id'] == 0) ? ' <b>no account!</b>' : '');
?></td>



            <!-- PWA EOF -->



<!--            <td class="pageHeading"><h3><?php
      echo HEADING_TITLE;
?></h3></td> -->



            <td class="pageHeading2" align="right"><?php
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
?></td>







      <!--



      <td class="pageHeading" align="right"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . IMAGE_BACK . '</a>';
?>



      </td>



      -->







            <td class="pageHeading" align="right"><?php
      echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_INVOICE . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . IMAGE_BACK . '</a> ';
?></td>







          </tr>



        </table></td>



      </tr>



      <tr>



        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">



          <tr>



            <td colspan="3"><?php
      echo tep_draw_separator();
?></td>



          </tr>



          <tr>



            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">



              <tr>



                <td class="main" valign="top"><b><?php
      echo ENTRY_CUSTOMER;
?></b></td>



                <td class="main"><?php
      echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>');
?></td>



              </tr>



              <tr>



                <td colspan="2"><?php
      echo tep_draw_separator('pixel_trans.png', '1', '5');
?></td>



              </tr>



              <tr>



                <td class="main"><b><?php
      echo ENTRY_TELEPHONE_NUMBER;
?></b></td>



                <td class="main"><?php
      echo $order->customer['telephone'];
?></td>



              </tr>



              <tr>



                <td class="main"><b><?php
      echo ENTRY_EMAIL_ADDRESS;
?></b></td>



                <td class="main"><?php
      echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>';
?></td>



              </tr>



            </table></td>



            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">



              <tr>



                <td class="main" valign="top"><b><?php
      echo ENTRY_SHIPPING_ADDRESS;
?></b></td>



                <td class="main"><?php
      echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>');
?></td>



              </tr>



            </table></td>



            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">



              <tr>



                <td class="main" valign="top"><b><?php
      echo ENTRY_BILLING_ADDRESS;
?></b></td>



                <td class="main"><?php
      echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>');
?></td>



              </tr>



            </table></td>



          </tr>



        </table></td>



      </tr>



      <tr>



        <td><?php
      echo tep_draw_separator('pixel_trans.png', '1', '10');
?></td>



      </tr>



      <tr>



        <td><table border="0" cellspacing="0" cellpadding="2">



          <tr>



            <td class="main"><b><?php
      echo ENTRY_PAYMENT_METHOD;
?></b></td>



            <td class="main"><?php
      echo $order->info['payment_method'];
?></td>



          </tr>



<?php
      if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>



          <tr>



            <td colspan="2"><?php
          echo tep_draw_separator('pixel_trans.png', '1', '10');
?></td>



          </tr>



          <tr>



            <td class="main"><?php
          echo ENTRY_CREDIT_CARD_TYPE;
?></td>



            <td class="main"><?php
          echo $order->info['cc_type'];
?></td>



          </tr>



          <tr>



            <td class="main"><?php
          echo ENTRY_CREDIT_CARD_OWNER;
?></td>



            <td class="main"><?php
          echo $order->info['cc_owner'];
?></td>



          </tr>



          <tr>



            <td class="main"><?php
          echo ENTRY_CREDIT_CARD_NUMBER;
?></td>



            <td class="main"><?php
          echo $order->info['cc_number'];
?></td>



          </tr>



          <tr>



            <td class="main"><?php
          echo ENTRY_CREDIT_CARD_EXPIRES;
?></td>



            <td class="main"><?php
          echo $order->info['cc_expires'];
?></td>



          </tr>



    



<?php
      }
?> <?php
      if ($order->customer['delivery_date'] > 0) {
?>



       <tr>



            <td class="main"><strong>Delivery Time</strong></td>



            <td class="main"><?php
          echo $order->customer['delivery_date'];
          if ($order->customer['delivery_slotid'] > 0) {
              $timeSlot_query = tep_db_query("SELECT * from sw_time_slots WHERE slotid = '" . $order->customer['delivery_slotid'] . "'");
              $timeSlot = tep_db_fetch_array($timeSlot_query);
              print '(' . $timeSlot['slot'] . ')';
          }
?></td>



          </tr>



<?php
      }
      
?>



          <tr>



            <td colspan="2"><?php
      echo tep_draw_separator('pixel_trans.png', '1', '10');
?></td>



          </tr>



<?php
      $orders_vendors_data_query = tep_db_query("select distinct ov.orders_id, ov.vendors_id, ov.vendor_order_sent, v.vendors_name from " . TABLE_ORDERS_SHIPPING . " ov, " . TABLE_VENDORS . " v where v.vendors_id=ov.vendors_id and orders_id='" . (int)$oID . "' group by vendors_id");
      while ($orders_vendors_data = tep_db_fetch_array($orders_vendors_data_query)) {
          echo '<tr class="dataTableRow"><td class="dataTableContent" valign="top" align="left">Order Sent to ' . $orders_vendors_data['vendors_name'] . ':<b> ' . $orders_vendors_data['vendor_order_sent'] . '</b><br></td></tr>';
      }
      
      
?>



        </table></td>



      </tr>



      <tr>



        <?php
      
?>



<!--       <tr> -->



<?php
      if (tep_not_null($order->orders_shipping_id)) {
          require('vendor_order_info.php');
      } else {
          
?>











        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">



          <tr class="dataTableHeadingRow">



            <td class="dataTableHeadingContent" colspan="2"><?php
          echo TABLE_HEADING_PRODUCTS;
?></td>



            <td class="dataTableHeadingContent"><?php
          echo TABLE_HEADING_PRODUCTS_MODEL;
?></td>



            <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_TAX;
?></td>



            <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_PRICE_EXCLUDING_TAX;
?></td>



            <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_PRICE_INCLUDING_TAX;
?></td>



            <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_TOTAL_EXCLUDING_TAX;
?></td>



            <td class="dataTableHeadingContent" align="right"><?php
          echo TABLE_HEADING_TOTAL_INCLUDING_TAX;
?></td>



          </tr>



<?php
          for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
              
              $returns_check_query = tep_db_query("SELECT r.rma_value, rp.products_id FROM " . TABLE_RETURNS . " r, " . TABLE_RETURNS_PRODUCTS_DATA . " rp where r.returns_id = rp.returns_id and r.order_id = '" . $oID . "' and rp.products_id = '" . $order->products[$i]['id'] . "' ");
              if (!tep_db_num_rows($returns_check_query)) {
                  if ($order->products[$i]['return'] != '1') {
                      $return_link = '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $oID . '&products_id=' . ($order->products[$i]['id']), 'NONSSL') . '"><u>' . '<font color="818180">Schedule Return</font>' . '</a></u>';
                  }
                  
                  
                  if (($orders_status == '1') or ($orders_status == '2')) {
                      $return_link = '';
                  }
              } else {
                  $returns = tep_db_fetch_array($returns_check_query);
                  $return_link = '<a href=' . tep_href_link(FILENAME_RETURNS, 'cID=' . $returns['rma_value']) . '><font color=red><b><i>Returns</b></i></font></a>';
              }
              
              echo '          <tr class="dataTableRow">' . "\n" . '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" . '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'] . '&nbsp;&nbsp;' . $return_link;
              if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
                  for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
                      echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
                      if ($order->products[$i]['attributes'][$j]['price'] != '0')
                          echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                      echo '</i></small></nobr>';
                  }
              }
              echo '            </td>' . "\n" . '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
              echo '          </tr>' . "\n";
          }
?>



<!--          <tr>-->



            <?php
          
      }
      
      for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
          echo '              <tr>' . "\n" . '                <td colspan="7" align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" . '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" . '              </tr>' . "\n";
      }
?>



<!--            </table></td>



          </tr>-->



        </table></td>



      </tr>



      <tr>



        <td><?php
      echo tep_draw_separator('pixel_trans.png', '1', '10');
?></td>



      </tr>



      <tr>



        <td class="statusbox"><table border="0" cellspacing="0" cellpadding="5">



          <tr>



            <td class="smallText" align="center"><b><?php
      echo TABLE_HEADING_DATE_ADDED;
?></b></td>



            <td class="smallText" align="center"><b><?php
      echo TABLE_HEADING_CUSTOMER_NOTIFIED;
?></b></td>



            <td class="smallText" align="center"><b><?php
      echo TABLE_HEADING_STATUS;
?></b></td>



            <td class="smallText" align="center"><b><?php
      echo TABLE_HEADING_COMMENTS;
?></b></td>



          </tr>



<?php
      $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
      if (tep_db_num_rows($orders_history_query)) {
          while ($orders_history = tep_db_fetch_array($orders_history_query)) {
              echo '          <tr>' . "\n" . '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" . '            <td class="smallText" align="center">';
              if ($orders_history['customer_notified'] == '1') {
                  echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
              } else {
                  echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
              }
              echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" . '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" . '          </tr>' . "\n";
          }
      } else {
          echo '          <tr>' . "\n" . '            <td class="smallText" colspan="4">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" . '          </tr>' . "\n";
      }
?>



        </table></td>



      </tr>



      <tr>



        <td class="main"><br><b><?php
      echo TABLE_HEADING_COMMENTS;
?></b></td>



      </tr>



      <tr>



        <td><?php
      echo tep_draw_separator('pixel_trans.png', '1', '5');
?></td>



      </tr>



      <tr><?php
      echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order');
?>



        <td class="main"><?php
      echo tep_draw_textarea_field('comments', 'soft', '60', '5');
?></td>



      </tr>



      <tr>



        <td><?php
      echo tep_draw_separator('pixel_trans.png', '1', '10');
?></td>



      </tr>



      <tr>



        <td><table border="0" cellspacing="0" cellpadding="2">



          <tr>



            <td><table border="0" cellspacing="0" cellpadding="2">



      <?php
      
      
      
      
      
      
      
      $orderDao = new OrderDAO();
      
      $filtered_orders_statuses = $orders_statuses;
      
      for ($i = 0; $i < sizeof($filtered_orders_statuses); $i++) {
          $status = $filtered_orders_statuses[$i];
          if ($status['id'] == ORDERS_STATUS_SYSTEM_ERROR) {
              unset($filtered_orders_statuses[$i]);
              $filtered_orders_statuses = array_values($filtered_orders_statuses);
              break;
          }
      }
      
      if (!$orderDao->isAmazonOrder($oID)) {
?>



            



             



<?php
          } else
          {
              
              
              
              $orderStatusDelivered = $orderDao->getOrderStatusByName($filtered_orders_statuses, 'Delivered');
              $shippingCarriers = unserialize(AMAZON_SHIPPING_CARRIERS);
?>



              <script language="javascript">



              function toggleShippingInput() {



                 var select = document.getElementById('AmazonOrderStatus');



                 var selectedOption = select.options[select.selectedIndex];







                 var displayDelivered = selectedOption.value == <?php
              echo $orderStatusDelivered
?> ? '' : 'none';



                 document.getElementById('AmazonShippingInput').style.display = displayDelivered;



              }



             </script>







              <tr>



                <td class="main"><b><?php
              echo ENTRY_STATUS;
?></b> <?php
              echo tep_draw_pull_down_menu('status', $filtered_orders_statuses, $order->info['orders_status'], "id=\"AmazonOrderStatus\" onchange=\"toggleShippingInput()\"");
?></td>



                <td></td>



              </tr>



              <tr>



                <td class="main" colspan="2">



                  <div id="AmazonShippingInput" style="display: none;">



                    <div style="margin-bottom: 5px;"><b><?php
              echo ENTRY_AMAZON_SHIPPING_CARRIER;
?></b> <?php
              echo tep_draw_pull_down_menu('ShippingCarrier', $shippingCarriers, 'USPS');
?><br/></div>



                    <div style="margin-bottom: 5px;"><b><?php
              echo ENTRY_AMAZON_SHIPPING_SERVICE;
?></b> <?php
              echo tep_draw_input_field('ShippingService', '');
?><br/></div>



                    <div style="margin-bottom: 5px;"><b><?php
              echo ENTRY_AMAZON_SHIPPING_TRACKING_NUMBER;
?></b> <?php
              echo tep_draw_input_field('ShippingTrackingNumber', '');
?></div>



                  </div>



                </td>



                <td></td>



              </tr>



              <tr>



              <tr>



                <td class="main"><b><?php
              echo ENTRY_NOTIFY_CUSTOMER;
?></b> <input type="checkbox" name="notify" disabled="true"></td>



                <td class="main"><b><?php
              echo ENTRY_NOTIFY_COMMENTS;
?></b> <?php
              echo tep_draw_checkbox_field('notify_comments', '', true);
?></td>



              </tr>



              <tr>



              <td class="main" style="font-size: xx-small"><i><?php
              echo AMAZON_WARNING_CUSTOMER_NOTIFICATIONS_DISABLED
?></i></td>



                <td class="main"></td>



              </tr>



<?php
          }
          
          
          
          
          
?>



              <tr>



                <td class="main"><b><?php
          echo ENTRY_STATUS;
?></b> <?php
          echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']);
?></td>



              </tr>



              <tr>



                <td class="main"><b><?php
          echo ENTRY_NOTIFY_CUSTOMER;
?></b> <?php
          echo tep_draw_checkbox_field('notify', '', true);
?></td>



                <td class="main"><b><?php
          echo ENTRY_NOTIFY_COMMENTS;
?></b> <?php
          echo tep_draw_checkbox_field('notify_comments', '', true);
?></td>



              </tr>



            </table></td>



            <td valign="top" ><?php
          echo tep_image_submit('button_update.png', IMAGE_UPDATE);
?></td>



          </tr>



      <!-- // Points/Rewards Module V2.00 check_box_bof //-->



<?php
          $p_status_query = tep_db_query("SELECT points_status FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
          if (tep_db_num_rows($p_status_query)) {
              echo '<tr><td class="main"><b>' . ENTRY_NOTIFY_POINTS . '</b>&nbsp;' . ENTRY_QUE_POINTS . tep_draw_checkbox_field('confirm_points', '', false) . '&nbsp;' . ENTRY_QUE_DEL_POINTS . tep_draw_checkbox_field('delete_points', '', false) . '&nbsp;&nbsp;</td></tr>';
          }
?>



<!-- // Points/Rewards Module V2.00 check_box_eof //-->



        </table></td>



      </form></tr>



    



      <tr>



        <td align="right"><?php
          echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_INVOICE . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . IMAGE_BACK . '</a> ';
?></td>







      </tr>







<?php
          } else
          {
?>



      <tr>



        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">



          <tr>



            <td class="pageHeading"><h3><?php
              echo HEADING_TITLE;
?></h3></td>



            <td class="pageHeading2" align="right"></td>



            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">



 



              <tr><?php
              echo tep_draw_form('status', FILENAME_ORDERS, '', 'get');
?>



                <td class="smallText" align="right"><?php
              echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"');
?></td>



              </form></tr>



            </table></td>



          </tr>



        </table></td>



      </tr>



      <tr>



        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">



          <tr>



            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">



              <tr class="dataTableHeadingRow">



                <td class="dataTableHeadingContent"><?php
              echo TABLE_HEADING_CUSTOMERS;
?></td>



                <td class="dataTableHeadingContent" align="right"><?php
              echo TABLE_HEADING_ORDER_TOTAL;
?></td>



                <td class="dataTableHeadingContent" align="center"><?php
              echo TABLE_HEADING_DATE_PURCHASED;
?></td>



                <td class="dataTableHeadingContent" align="right"><?php
              echo TABLE_HEADING_STATUS;
?></td>



                <td class="dataTableHeadingContent" align="right"><?php
              echo TABLE_HEADING_ACTION;
?>&nbsp;</td>



              </tr>



<?php
              if (isset($_GET['cID'])) {
                  $cID = tep_db_prepare_input($_GET['cID']);
                  $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
              } elseif (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0)) {
                  $status = tep_db_prepare_input($_GET['status']);
                  $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";
              } else {
                  $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
              }
              $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
              $orders_query = tep_db_query($orders_query_raw);
              while ($orders = tep_db_fetch_array($orders_query)) {
                  
                  if ($orders['customers_id'] == 0)
                      $orders['customers_name'] = '<b>!!</b> ' . $orders['customers_name'];
                  
                  if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
                      $oInfo = new objectInfo($orders);
                  }
                  if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
                      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
                  } else {
                      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
                  }
?>



                <td class="dataTableContent"><?php
                  echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.png', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name'];
?></td>



                <td class="dataTableContent" align="right"><?php
                  echo strip_tags($orders['order_total']);
?></td>



                <td class="dataTableContent" align="center"><?php
                  echo tep_datetime_short($orders['date_purchased']);
?></td>



                <td class="dataTableContent" align="right"><?php
                  echo $orders['orders_status_name'];
?></td>



                <td class="dataTableContent" align="right"><?php
                  if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
                      echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png', '');
                  } else {
                      echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
                  }
?>&nbsp;</td>



              </tr>



<?php
              }
?>



              <tr>



                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">



                  <tr>



                    <td class="smallText" valign="top"><?php
              echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
?></td>



                    <td class="smallText" align="right"><?php
              echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action')));
?></td>



                  </tr>



                </table></td>



              </tr>



            </table></td>



<?php
              $heading = array();
              $contents = array();
              switch ($action) {
                  case 'delete':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');
                      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
                      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
                      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . IMAGE_CANCEL . '</a>');
                      break;
                  default:
                      if (isset($oInfo) && is_object($oInfo)) {
                          $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');
                          $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . IMAGE_DETAILS . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . IMAGE_DELETE . '</a>');
                          $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . IMAGE_ORDERS_INVOICE . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $oInfo->orders_id) . '">' . IMAGE_EDIT . '</a>');
                          $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
                          if (tep_not_null($oInfo->last_modified))
                              $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
                          $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' ' . $oInfo->payment_method);
                          
                          $orders_vendors_data_query = tep_db_query("select distinct ov.orders_id, ov.vendors_id, ov.vendor_order_sent, v.vendors_name from " . TABLE_ORDERS_SHIPPING . " ov, " . TABLE_VENDORS . " v where v.vendors_id=ov.vendors_id and orders_id='" . $oInfo->orders_id . "' group by vendors_id");
                          while ($orders_vendors_data = tep_db_fetch_array($orders_vendors_data_query)) {
                              $contents[] = array('text' => '<hr>' . VENDOR_ORDER_SENT . '' . $orders_vendors_data['vendors_name'] . ': ' . $orders_vendors_data['vendor_order_sent'] . '');
                          }
                          
                      }
                      break;
              }
              if ((tep_not_null($heading)) && (tep_not_null($contents))) {
                  echo '     <td valign="top"  width="220px">' . "\n";
                  $box = new box;
                  echo $box->infoBox($heading, $contents);
                  echo '            </td>' . "\n";
              }
?>



          </tr>



        </table></td>



      </tr>



<?php
          }
?>



    </table></td>



<!-- body_text_eof //-->



  </tr>



</table>



<!-- body_eof //-->







<!-- footer //-->



<?php
          require(DIR_WS_INCLUDES . 'footer.php');
?>



<!-- footer_eof //-->



<br>



</body>



</html>



<?php
          require(DIR_WS_INCLUDES . 'application_bottom.php');
?>



