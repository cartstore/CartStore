<?php
  require('includes/configure.php');
  require('includes/application_top.php');
  // *** BEGIN GOOGLE CHECKOUT ***
  require_once(DIR_FS_CATALOG . 'googlecheckout/inserts/admin/orders1.php');
  // *** END GOOGLE CHECKOUT ***


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
          } //if ($debug == 'yes')
          $index = 0;
          $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendor_order['vendors_id'] . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");
          while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
              $vendor_products[$index2]['vendor_orders_products'][$index] = array('Pqty' => $vendor_orders_products['products_quantity'], 'Pname' => $vendor_orders_products['products_name'], 'Pmodel' => $vendor_orders_products['products_model'], 'Pprice' => $vendor_orders_products['products_price'], 'Pvendor_name' => $vendor_orders_products['vendors_name'], 'Pcomments' => $vendor_orders_products['vendors_prod_comments'], 'PVprod_id' => $vendor_orders_products['vendors_prod_id'], 'PVprod_price' => $vendor_orders_products['vendors_product_price'], 'spacer' => '-');
              if ($debug == 'yes') {
                  echo 'The products query: ' . $vendor_orders_products['products_name'] . '<br>';
              } //if ($debug == 'yes')
              $subindex = 0;
              $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
              if (tep_db_num_rows($vendor_attributes_query)) {
                  while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
                      $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'], 'value' => $vendor_attributes['products_options_values'], 'prefix' => $vendor_attributes['price_prefix'], 'price' => $vendor_attributes['options_values_price']);
                      $subindex++;
                  } //while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query))
              } //if (tep_db_num_rows($vendor_attributes_query))
              $index++;
          } //while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query))
          $index2++;
          $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID . "'");
          $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
          if ($debug == 'yes') {
              echo 'The number of vendors: ' . sizeof($vendor_products) . '<br>';
          } //if ($debug == 'yes')
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
                      } //for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++)
                  } //if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0))
                  $email = $email . '<tr><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>&nbsp;&nbsp;<i>Option<br> ' . $product_attribs . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price'] . '</td><td>' . $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'] . '</b></td></tr>';
              } //for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++)
          } //for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++)
          $email = $email . '</table><br><HR><br>';
          tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID, $email . '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
          $vendor_order_sent = true;
          if ($debug == 'yes') {
              echo 'The $email(including headers:<br>Vendor Email Addy' . $the_email . '<br>Vendor Name' . $the_name . '<br>Vendor Contact' . $the_contact . '<br>Body--<br>' . $email . '<br>';
          } //if ($debug == 'yes')
          if ($vendor_order_sent == true) {
              tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = 'yes' where orders_id = '" . (int)$oID . "'");
          } //if ($vendor_order_sent == true)
      } //while ($vendor_order = tep_db_fetch_array($vendor_data_query))
      return true;
  } //function vendors_email($vendors_id, $oID, $status, $vendor_order_sent)

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_statuses[] = array('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
      $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  } //while ($orders_status = tep_db_fetch_array($orders_status_query))
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
if($HTTP_GET_VARS['listing']=="customers") { $sort_by = "o.customers_name"; }
elseif($HTTP_GET_VARS['listing']=="customers-desc") { $sort_by = "o.customers_name DESC"; }
elseif($HTTP_GET_VARS['listing']=="ottotal") { $sort_by = "order_total"; }
elseif($HTTP_GET_VARS['listing']=="ottotal-desc") { $sort_by = "order_total DESC"; }
elseif($HTTP_GET_VARS['listing']=="id-asc") { $sort_by = "o.orders_id"; }
elseif($HTTP_GET_VARS['listing']=="id-desc") { $sort_by = "o.orders_id DESC"; }
elseif($HTTP_GET_VARS['listing']=="status-asc") { $sort_by = "o.orders_status"; }
elseif($HTTP_GET_VARS['listing']=="status-desc") { $sort_by = "o.orders_status DESC"; }
else { $sort_by = "o.orders_id DESC"; }
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

              $usps_track_num = tep_db_prepare_input($_POST['usps_track_num']);
              $usps_track_num2 = tep_db_prepare_input($_POST['usps_track_num2']);
              $ups_track_num = tep_db_prepare_input($_POST['ups_track_num']);
              $ups_track_num2 = tep_db_prepare_input($_POST['ups_track_num2']);
              $fedex_track_num = tep_db_prepare_input($_POST['fedex_track_num']);
              $fedex_track_num2 = tep_db_prepare_input($_POST['fedex_track_num2']);
              $dhl_track_num = tep_db_prepare_input($_POST['dhl_track_num']);
              $dhl_track_num2 = tep_db_prepare_input($_POST['dhl_track_num2']);

              $delv_date = $_POST['d_date_year'] . "-" . $_POST['d_date_month'] . "-" . $_POST['d_date_day'];
              $slotid = tep_db_prepare_input($_POST['slotid']);
              tep_db_query("update " . TABLE_ORDERS . " set delivery_date = '" . tep_db_input($delv_date) . "', delivery_time_slotid = '" . $slotid . "' where orders_id = '" . (int)$oID . "'");
              $order_updated = false;
              $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status,usps_track_num, usps_track_num2, ups_track_num, ups_track_num2, fedex_track_num, fedex_track_num2, dhl_track_num, dhl_track_num2, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
              $check_status = tep_db_fetch_array($check_status_query);
              // always update date and time on order_status
              if ( ($check_status['orders_status'] != $status) || $comments != '' || ($status == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {
                  tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
                  $check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
                  $check_status2 = tep_db_fetch_array($check_status_query2);
                  if ( $check_status2['orders_status'] == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
                      tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . DOWNLOAD_MAX_DAYS . "', download_count = '" . DOWNLOAD_MAX_COUNT . "' where orders_id = '" . (int)$oID . "'");
                  }
                  if (SELECT_VENDOR_EMAIL_WHEN == 'Admin' || SELECT_VENDOR_EMAIL_WHEN == 'Both') {
                      if (isset($status)) {
                          $order_sent_query = tep_db_query("select vendor_order_sent, vendors_id from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . $oID . "'");
                          while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {
                              $order_sent_ckeck = $order_sent_data['vendor_order_sent'];
                              $vendors_id = $order_sent_data['vendors_id'];
                              if ($order_sent_ckeck == 'no') {
                                  $vendor_order_sent = false;
                                  vendors_email($vendors_id, $oID, $status, $vendor_order_sent);
                              } //if ($order_sent_ckeck == 'no')
                          } //while ($order_sent_data = tep_db_fetch_array($order_sent_query))
                      } //if (isset($status))
                  } //if (SELECT_VENDOR_EMAIL_WHEN == 'Admin' || SELECT_VENDOR_EMAIL_WHEN == 'Both')
                  $customer_notified = '0';
//                  if ($_POST['notify'] == 'on' & ($usps_track_num == '' & $usps_track_num2 == '' & $ups_track_num == '' & $ups_track_num2 == '' & $fedex_track_num == '' & $fedex_track_num2 == '' & $dhl_track_num == '' & $dhl_track_num2 == '')) {
                  if ($_POST['notify'] == 'on') {
                      if ($_POST['notify_comments'] == 'on') {
                          $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n";
                          if ($comments == null)
                              $notify_comments = '';
                      } //if ($HTTP_POST_VARS['notify_comments'] == 'on')

                      if ($num_rows == 0) {
                          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                          tep_mail($check_status['customers_name'], $check_status['customers_email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . ' ' . $insert_id . ' ' .EMAIL_TEXT_SUBJECT_2, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                      } //if ($num_rows == 0)
                      else {
                          if ($_POST['notify'] != 'on')
                              unset($notify_comments);
                          google_checkout_state_change($check_status, $status, $oID, $customer_notified, $notify_comments);
                      } //else
                      $customer_notified = '1';
                  } //if ($_POST['notify'] == 'on' & ($usps_track_num == '' & $usps_track_num2 == '' & $ups_track_num == '' & $ups_track_num2 == '' & $fedex_track_num == '' & $fedex_track_num2 == '' & $dhl_track_num == '' & $dhl_track_num2 == ''))
                  elseif ($HTTP_POST_VARS['notify'] == 'on' & ($usps_track_num == '' or $usps_track_num2 == '' or $ups_track_num == '' or $ups_track_num2 == '' or $fedex_track_num == '' or $fedex_track_num2 == '' or $dhl_track_num == '' or $dhl_track_num2 == '')) {
                      $notify_comments = '';
                      if ($HTTP_POST_VARS['notify_comments'] == 'on') {
                          $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n";
                          if ($comments == null)
                              $notify_comments = '';
                      } //if ($HTTP_POST_VARS['notify_comments'] == 'on')
                      if ($usps_track_num == null) {
                          $usps_text = '';
                          $usps_track = '';
                      } //if ($usps_track_num == null)
                      else {
                          $usps_text = 'USPS(1): ';
                          $usps_track_num_noblanks = str_replace(' ', '', $usps_track_num);
                          $usps_link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num_noblanks;
                          $usps_track = '<a class="button" target="_blank" href="' . $usps_link . '">' . $usps_track_num . '</a>' . "\n";
                      } //else
                      if ($usps_track_num2 == null) {
                          $usps_text2 = '';
                          $usps_track2 = '';
                      } //if ($usps_track_num2 == null)
                      else {
                          $usps_text2 = 'USPS(2): ';
                          $usps_track_num2_noblanks = str_replace(' ', '', $usps_track_num2);
                          $usps_link2 = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num2_noblanks;
                          $usps_track2 = '<a class="button" target="_blank" href="' . $usps_link2 . '">' . $usps_track_num2 . '</a>' . "\n";
                      } //else
                      if ($ups_track_num == null) {
                          $ups_text = '';
                          $ups_track = '';
                      } //if ($ups_track_num == null)
                      else {
                          $ups_text = 'UPS(1): ';
                          $ups_track_num_noblanks = str_replace(' ', '', $ups_track_num);
                          $ups_link = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';
                          $ups_track = '<a class="button" target="_blank" href="' . $ups_link . '">' . $ups_track_num . '</a>' . "\n";
                      } //else
                      if ($ups_track_num2 == null) {
                          $ups_text2 = '';
                          $ups_track2 = '';
                      } //if ($ups_track_num2 == null)
                      else {
                          $ups_text2 = 'UPS(2): ';
                          $ups_track_num2_noblanks = str_replace(' ', '', $ups_track_num2);
                          $ups_link2 = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num2_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';
                          $ups_track2 = '<a class="button" target="_blank" href="' . $ups_link2 . '">' . $ups_track_num2 . '</a>' . "\n";
                      } //else
                      if ($fedex_track_num == null) {
                          $fedex_text = '';
                          $fedex_track = '';
                      } //if ($fedex_track_num == null)
                      else {
                          $fedex_text = 'Fedex(1): ';
                          $fedex_track_num_noblanks = str_replace(' ', '', $fedex_track_num);
                          $fedex_link = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num_noblanks . '&action=track&language=english&cntry_code=us';
                          $fedex_track = '<a class="button" target="_blank" href="' . $fedex_link . '">' . $fedex_track_num . '</a>' . "\n";
                      } //else
                      if ($fedex_track_num2 == null) {
                          $fedex_text2 = '';
                          $fedex_track2 = '';
                      } //if ($fedex_track_num2 == null)
                      else {
                          $fedex_text2 = 'Fedex(2): ';
                          $fedex_track_num2_noblanks = str_replace(' ', '', $fedex_track_num2);
                          $fedex_link2 = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num2_noblanks . '&action=track&language=english&cntry_code=us';
                          $fedex_track2 = '<a class="button" target="_blank" href="' . $fedex_link2 . '">' . $fedex_track_num2 . '</a>' . "\n";
                      } //else
                      if ($dhl_track_num == null) {
                          $dhl_text = '';
                          $dhl_track = '';
                      } //if ($dhl_track_num == null)
                      else {
                          $dhl_text = 'DHL(1): ';
                          $dhl_track_num_noblanks = str_replace(' ', '', $dhl_track_num);
                          $dhl_link = 'http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=' . $dhl_track_num_noblanks . '&action=track&language=english&cntry_code=us';
                          $dhl_track = '<a class="button" target="_blank" href="' . $dhl_link . '">' . $dhl_track_num . '</a>' . "\n";
                      } //else
                      if ($dhl_track_num2 == null) {
                          $dhl_text2 = '';
                          $dhl_track2 = '';
                      } //if ($dhl_track_num2 == null)
                      else {
                          $dhl_text2 = 'DHL(2): ';
                          $dhl_track_num2_noblanks = str_replace(' ', '', $dhl_track_num2);
                          $dhl_link2 = 'http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=' . $dhl_track_num2_noblanks . '&action=track&language=english&cntry_code=us';
                          $dhl_track2 = '<a class="button" target="_blank" href="' . $dhl_link2 . '">' . $dhl_track_num2 . '</a>' . "\n";
                      } //else
                      $email = 'Dear ' . $check_status['customers_name'] . ',' . "\n\n" . STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . "<a HREF='" . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "'>" . 'order_id=' . (int)$oID . "</a>\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_TRACKING_NUMBER . "\n" . $usps_text . $usps_track . $usps_text2 . $usps_track2 . $ups_text . $ups_track . $ups_text2 . $ups_track2 . $fedex_text . $fedex_track . $fedex_text2 . $fedex_track2 . $dhl_text . $dhl_track . $dhl_text2 . $dhl_track2 . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                      tep_mail($check_status['customers_name'], $check_status['customers_email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . (int)$oID . EMAIL_TEXT_SUBJECT_2 . $orders_status_array[$status], $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                      $customer_notified = '1';
                  } //elseif ($HTTP_POST_VARS['notify'] == 'on' & ($usps_track_num == '' or $usps_track_num2 == '' or $ups_track_num == '' or $ups_track_num2 == '' or $fedex_track_num == '' or $fedex_track_num2 == '' or $dhl_track_num == '' or $dhl_track_num2 == ''))
                  elseif ($HTTP_POST_VARS['notify'] == 'on' & (tep_not_null($usps_track_num) & tep_not_null($usps_track_num2) & tep_not_null($ups_track_num) & tep_not_null($ups_track_num2) & tep_not_null($fedex_track_num) & tep_not_null($fedex_track_num2) & tep_not_null($dhl_track_num) & tep_not_null($dhl_track_num2))) {
                      $notify_comments = '';
                      if ($HTTP_POST_VARS['notify_comments'] == 'on') {
                          $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n";
                          if ($comments == null)
                              $notify_comments = '';
                      } //if ($HTTP_POST_VARS['notify_comments'] == 'on')
                      $usps_text = 'USPS(1): ';
                      $usps_track_num_noblanks = str_replace(' ', '', $usps_track_num);
                      $usps_link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num_noblanks;
                      $usps_track = '<a class="button" target="_blank" href="' . $usps_link . '">' . $usps_track_num . '</a>' . "\n";
                      $usps_text2 = 'USPS(2): ';
                      $usps_track_num2_noblanks = str_replace(' ', '', $usps_track_num2);
                      $usps_link2 = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num2_noblanks;
                      $usps_track2 = '<a class="button" target="_blank" href="' . $usps_link2 . '">' . $usps_track_num2 . '</a>' . "\n";
                      $ups_text = 'UPS(1): ';
                      $ups_track_num_noblanks = str_replace(' ', '', $ups_track_num);
                      $ups_link = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';
                      $ups_track = '<a class="button" target="_blank" href="' . $ups_link . '">' . $ups_track_num . '</a>' . "\n";
                      $ups_text2 = 'UPS(2): ';
                      $ups_track_num2_noblanks = str_replace(' ', '', $ups_track_num2);
                      $ups_link2 = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num2_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';
                      $ups_track2 = '<a class="button" target="_blank" href="' . $ups_link2 . '">' . $ups_track_num2 . '</a>' . "\n";
                      $fedex_text = 'Fedex(1): ';
                      $fedex_track_num_noblanks = str_replace(' ', '', $fedex_track_num);
                      $fedex_link = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num_noblanks . '&action=track&language=english&cntry_code=us';
                      $fedex_track = '<a class="button" target="_blank" href="' . $fedex_link . '">' . $fedex_track_num . '</a>' . "\n";
                      $fedex_text2 = 'Fedex(2): ';
                      $fedex_track_num2_noblanks = str_replace(' ', '', $fedex_track_num2);
                      $fedex_link2 = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num2_noblanks . '&action=track&language=english&cntry_code=us';
                      $fedex_track2 = '<a class="button" target="_blank" href="' . $fedex_link2 . '">' . $fedex_track_num2 . '</a>' . "\n";
                      $dhl_text = 'DHL(1): ';
                      $dhl_track_num_noblanks = str_replace(' ', '', $dhl_track_num);
                      $dhl_link = 'http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=' . $dhl_track_num_noblanks . '&action=track&language=english&cntry_code=us';
                      $dhl_track = '<a class="button" target="_blank" href="' . $dhl_link . '">' . $dhl_track_num . '</a>' . "\n";
                      $dhl_text2 = 'DHL(2): ';
                      $dhl_track_num2_noblanks = str_replace(' ', '', $dhl_track_num2);
                      $dhl_link2 = 'http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=' . $dhl_track_num2_noblanks . '&action=track&language=english&cntry_code=us';
                      $dhl_track2 = '<a class="button" target="_blank" href="' . $dhl_link2 . '">' . $dhl_track_num2 . '</a>' . "\n";
                      $email = 'Dear ' . $check_status['customers_name'] . ',' . "\n\n" . STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . "<a HREF='" . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "'>" . 'order_id=' . (int)$oID . "</a>\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_TRACKING_NUMBER . "\n" . $usps_text . $usps_track . $usps_text2 . $usps_track2 . $ups_text . $ups_track . $ups_text2 . $ups_track2 . $fedex_text . $fedex_track . $fedex_text2 . $fedex_track2 . $dhl_text . $dhl_track . $dhl_text2 . $dhl_track2 . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                      tep_mail($check_status['customers_name'], $check_status['customers_email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . (int)$oID . EMAIL_TEXT_SUBJECT_2 . $orders_status_array[$status], $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                      $customer_notified = '1';


                      $customer_notified = '1';
                  } //elseif ($HTTP_POST_VARS['notify'] == 'on' & (tep_not_null($usps_track_num) & tep_not_null($usps_track_num2) & tep_not_null($ups_track_num) & tep_not_null($ups_track_num2) & tep_not_null($fedex_track_num) & tep_not_null($fedex_track_num2) & tep_not_null($dhl_track_num) & tep_not_null($dhl_track_num2)))
                  else {
                     require_once(DIR_FS_CATALOG . 'googlecheckout/inserts/admin/orders3.php');
                  } //else
              } //if (($check_status['orders_status'] != $status) || tep_not_null($comments))
              if ((isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) || (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on'))) {
                  $comments = ENTRY_CONFIRMED_POINTS . $comments;
                  $customer_query = tep_db_query("SELECT customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
                  $customer_points = tep_db_fetch_array($customer_query);
                  if (tep_db_num_rows($customer_query)) {
                      if (tep_not_null(POINTS_AUTO_EXPIRES)) {
                          $expire = date('Y-m-d', strtotime('+ ' . POINTS_AUTO_EXPIRES . ' month'));
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $customer_points['points_pending'] . "', customers_points_expires = '" . $expire . "' WHERE customers_id = '" . (int)$customer_points['customer_id'] . "'");
                      } //if (tep_not_null(POINTS_AUTO_EXPIRES))
                      else {
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $customer_points['points_pending'] . "' WHERE customers_id = '" . (int)$customer_points['customer_id'] . "'");
                      } //else
                      if (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on')) {
                          tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
                      } //if (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on'))
                      if (isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) {
                          tep_db_query("UPDATE " . TABLE_CUSTOMERS_POINTS_PENDING . " SET points_status = 2 WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
                      } //if (isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on'))
                  } //if (tep_db_num_rows($customer_query))
              } //if ((isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) || (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on')))
              if (!$isAmazonOrder || $amazonProcessingTransactionId == null) {
                  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments) . "')");

                  tep_db_query("update " . TABLE_ORDERS . " set usps_track_num = '" . tep_db_input($usps_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set usps_track_num2 = '" . tep_db_input($usps_track_num2) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set ups_track_num = '" . tep_db_input($ups_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set ups_track_num2 = '" . tep_db_input($ups_track_num2) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set fedex_track_num = '" . tep_db_input($fedex_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set fedex_track_num2 = '" . tep_db_input($fedex_track_num2) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set dhl_track_num = '" . tep_db_input($dhl_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  tep_db_query("update " . TABLE_ORDERS . " set dhl_track_num2 = '" . tep_db_input($dhl_track_num2) . "' where orders_id = '" . tep_db_input($oID) . "'");
                  $order_updated = true;

              } //if (!$isAmazonOrder || $amazonProcessingTransactionId == null)
              else {
                  $customer_notified = '1';
                  $amazonComments = strlen($comments) > 0 ? $comments . "\n\n" : "";
                  $amazonComments = $amazonComments . AMAZON_PROCESSING_MESSAGE_ORDER_STATUS_UPDATE;
                  $amazonComments = str_replace("[TRANSACTION_ID]", $amazonProcessingTransactionId, $amazonComments);
                  $amazonComments = str_replace("[DATE_TIME]", date('r'), $amazonComments);
                  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($amazonComments) . "')");
              } //else
              if($HTTP_POST_VARS['clear_CC'] == 'on') {
                tep_db_query("update " . TABLE_ORDERS . " set cc_number = '' where orders_id = '" . tep_db_input($oID) . "'");
              }
              $order_updated = true;
              if ($order_updated == true) {
                  $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
              } //if ($order_updated == true)
              else {
                  $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
              } //else
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
              break;
          case 'deleteconfirm':
              $oID = tep_db_prepare_input($_GET['oID']);
              tep_remove_order($oID, $_POST['restock']);
              tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
              break;
      } //switch ($action)
  } //if (tep_not_null($action))
  if (($action == 'edit') && isset($_GET['oID'])) {
      $oID = tep_db_prepare_input($_GET['oID']);
      $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
      $order_exists = true;
      if (!tep_db_num_rows($orders_query)) {
          $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
      } //if (!tep_db_num_rows($orders_query))
  } //if (($action == 'edit') && isset($_GET['oID']))
  include(DIR_WS_CLASSES . 'order.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php
  echo TITLE;
?>
</title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

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
                <td colspan="3"><?php echo  tep_draw_separator(); ?>
                <b>Order ID: <?php echo $oID; ?></b></td>
              </tr>
              <tr>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main" valign="top"><b>
                        <?php
      echo ENTRY_CUSTOMER;
?>
                        </b></td>
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
                      <td class="main"><b>
                        <?php
      echo ENTRY_TELEPHONE_NUMBER;
?>
                        </b></td>
                      <td class="main"><?php
      echo $order->customer['telephone'];
?></td>
                    </tr>
                    <tr>
                      <td class="main"><b>
                        <?php
      echo ENTRY_EMAIL_ADDRESS;
?>
                        </b></td>
                      <td class="main"><?php
      echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>';
?></td>
                    </tr>
                  </table></td>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main" valign="top"><b>
                        <?php
      echo ENTRY_SHIPPING_ADDRESS;
?>
                        </b></td>
                      <td class="main"><?php
      echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>');
?></td>
                    </tr>
                  </table></td>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main" valign="top"><b>
                        <?php
      echo ENTRY_BILLING_ADDRESS;
?>
                        </b></td>
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
                <td class="main"><b>
                  <?php
      echo ENTRY_PAYMENT_METHOD;
?>
                  </b></td>
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
      } //if (tep_not_null($order->info["s_726"]) || tep_not_null($order->info["s_727"]) || tep_not_null($order->info["s_728"]))
?>
              <?php
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
          } //if ($order->customer['delivery_slotid'] > 0)
?></td>
              </tr>
              <?php
      } //if ($order->customer["s_736"] > 0)
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
      } //while ($orders_vendors_data = tep_db_fetch_array($orders_vendors_data_query))
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
      } //if (tep_not_null($order->orders_shipping_id))
      else {
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
                  } //if ($order->products[$i]['return'] != '1')
                  if (($orders_status == '1') or ($orders_status == '2')) {
                      $return_link = '';
                  } //if (($orders_status == '1') or ($orders_status == '2'))
              } //if (!tep_db_num_rows($returns_check_query))
              else {
                  $returns = tep_db_fetch_array($returns_check_query);
                  $return_link = '<a href=' . tep_href_link(FILENAME_RETURNS, 'cID=' . $returns['rma_value']) . '><font color=red><b><i>Returns</b></i></font></a>';
              } //else
              echo '          <tr class="dataTableRow">' . "\n" . '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" . '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'] . '&nbsp;&nbsp;' . $return_link;
              if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
                  for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
                      echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
                      if ($order->products[$i]['attributes'][$j]['price'] != '0')
                          echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                      echo '</i></small></nobr>';
                  } //for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++)
              } //if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0))
              echo '            </td>' . "\n" . '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" . '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
              echo '          </tr>' . "\n";
          } //for ($i = 0, $n = sizeof($order->products); $i < $n; $i++)
?>

              <!--          <tr>-->

              <?php
      } //else
      for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
          echo '              <tr>' . "\n" . '                <td colspan="7" align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" . '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" . '              </tr>' . "\n";
      } //for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++)
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
                <td class="smallText" align="center"><b>
                  <?php
      echo TABLE_HEADING_DATE_ADDED;
?>
                  </b></td>
                <td class="smallText" align="center"><b>
                  <?php
      echo TABLE_HEADING_CUSTOMER_NOTIFIED;
?>
                  </b></td>
                <td class="smallText" align="center"><b>
                  <?php
      echo TABLE_HEADING_STATUS;
?>
                  </b></td>
                <td class="smallText" align="center"><blockquote>
                    <?php
      echo TABLE_HEADING_COMMENTS;
?>
                  </blockquote></td>
              </tr>
              <?php
      $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
      if (tep_db_num_rows($orders_history_query)) {
          while ($orders_history = tep_db_fetch_array($orders_history_query)) {
              echo '          <tr>' . "\n" . '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" . '            <td class="smallText" align="center">';
              if ($orders_history['customer_notified'] == '1') {
                  echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
              } //if ($orders_history['customer_notified'] == '1')
              else {
                  echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
              } //else
              echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" . '            <td class="smallText"><blockquote>' . ($orders_history['comments']) . '</blockquote></td>' . "\n" . '          </tr>' . "\n";
          } //while ($orders_history = tep_db_fetch_array($orders_history_query))
      } //if (tep_db_num_rows($orders_history_query))
      else {
          echo '          <tr>' . "\n" . '            <td class="smallText" colspan="4">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" . '          </tr>' . "\n";
      } //else
?>
            </table></td>
        </tr>
        <tr>
          <td class="main"><br>
            <b>
            <?php
      echo TABLE_HEADING_COMMENTS;
?>
            </b></td>
        </tr>
        <tr>
          <td><?php
      echo tep_draw_separator('pixel_trans.png', '1', '5');
?></td>
        </tr>
        <tr>
          <?php
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

        <!-- Package Tracking Plus BEGIN -->
        <tr>
          <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php
      echo TABLE_HEADING_USPS_TRACKING;
?></b></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('usps_track_num', '40', '40', '', $order->info['usps_track_num']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php
      echo $order->info['usps_track_num'];
?>">Track It</a></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('usps_track_num2', '40', '40', '', $order->info['usps_track_num2']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=<?php
      echo $order->info['usps_track_num2'];
?>">Track It</a></td>
              </tr>
              <tr>
                <td colspan="5"><?php
      echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
              </tr>
              <tr>
                <td class="main"><b><?php
      echo TABLE_HEADING_UPS_TRACKING;
?></b></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('ups_track_num', '40', '40', '', $order->info['ups_track_num']);
?></td>
                <td><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php
      echo $order->info['ups_track_num'];
?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track It</a></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('ups_track_num2', '40', '40', '', $order->info['ups_track_num2']);
?></td>
                <td><a class="button" target="_blank" href="http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=<?php
      echo $order->info['ups_track_num2'];
?>&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package">Track It</a></td>
              </tr>
              <tr>
                <td colspan="5"><?php
      echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
              </tr>
              <tr>
                <td class="main"><b><?php
      echo TABLE_HEADING_FEDEX_TRACKING;
?></b></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('fedex_track_num', '40', '40', '', $order->info['fedex_track_num']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php
      echo $order->info['fedex_track_num'];
?>&action=track&language=english&cntry_code=us">Track It</a></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('fedex_track_num2', '40', '40', '', $order->info['fedex_track_num2']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://www.fedex.com/Tracking?tracknumbers=<?php
      echo $order->info['fedex_track_num2'];
?>&action=track&language=english&cntry_code=us">Track It</a></td>
              </tr>
              <tr>
                <td colspan="5"><?php
      echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
              </tr>
              <tr>
                <td class="main"><b><?php
      echo TABLE_HEADING_DHL_TRACKING;
?></b></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('dhl_track_num', '40', '40', '', $order->info['dhl_track_num']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php
      echo $order->info['dhl_track_num'];
?>&action=track&language=english&cntry_code=us">Track It</a></td>
                <td class="main"><?php
      echo tep_draw_textbox_field('dhl_track_num2', '40', '40', '', $order->info['dhl_track_num2']);
?></td>
                <td class="main"><a class="button" target="_blank" href="http://track.dhl-usa.com/atrknav.asp?ShipmentNumber=<?php
      echo $order->info['dhl_track_num2'];
?>&action=track&language=english&cntry_code=us">Track It</a></td>
              </tr>
              <tr>
                <td colspan="5"><?php
      echo tep_draw_separator('pixel_trans.gif', '1', '10');
?></td>
              </tr>
            </table></td>
        </tr>
        <!-- Package Tracking Plus END -->

        <tr>
          <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="2" width="500">
                    <?php

      $filtered_orders_statuses = $orders_statuses;
      for ($i = 0; $i < sizeof($filtered_orders_statuses); $i++) {
          $status = $filtered_orders_statuses[$i];
          if ($status['id'] == ORDERS_STATUS_SYSTEM_ERROR) {
              unset($filtered_orders_statuses[$i]);
              $filtered_orders_statuses = array_values($filtered_orders_statuses);
              break;
          } //if ($status['id'] == ORDERS_STATUS_SYSTEM_ERROR)
      } //for ($i = 0; $i < sizeof($filtered_orders_statuses); $i++)





?>
                    <tr>
                      <td class="main" colspan="3"><b>
                        <?php
      echo ENTRY_STATUS;
?>
                        </b>
                        <?php
      echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']);
?></td>
                    </tr>
                    <tr>
                      <td class="main"><b>
                        <?php
      echo ENTRY_NOTIFY_CUSTOMER;
?>
                        </b>
                        <?php
      echo tep_draw_checkbox_field('notify', '', true);
?></td>
                      <td class="main"><b>
                        <?php
      echo ENTRY_NOTIFY_COMMENTS;
?>
                        </b>
                        <?php
      echo tep_draw_checkbox_field('notify_comments', '', true);
?></td>
<td class="main"><b><?php echo ENTRY_NOTIFY_CLEAR_CC; ?></b> <?php echo tep_draw_checkbox_field('clear_CC', '', false); ?></td>
                   </tr>
                  </table></td>
                <td valign="top" ><?php
      echo tep_image_submit('button_update.png', IMAGE_UPDATE);
?></td>
<!-- *** BEGIN GOOGLE CHECKOUT *** -->
<?php
require_once(DIR_FS_CATALOG . 'googlecheckout/inserts/admin/orders3.php');
?>
<!-- *** END GOOGLE CHECKOUT *** -->
              </tr>

              <!-- // Points/Rewards Module V2.00 check_box_bof //-->

              <?php
      $p_status_query = tep_db_query("SELECT points_status FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
      if (tep_db_num_rows($p_status_query)) {
          echo '<tr><td class="main"><b>' . ENTRY_NOTIFY_POINTS . '</b>&nbsp;' . ENTRY_QUE_POINTS . tep_draw_checkbox_field('confirm_points', '', false) . '&nbsp;' . ENTRY_QUE_DEL_POINTS . tep_draw_checkbox_field('delete_points', '', false) . '&nbsp;&nbsp;</td></tr>';
      } //if (tep_db_num_rows($p_status_query))
?>

              <!-- // Points/Rewards Module V2.00 check_box_eof //-->

            </table></td>
            </form>
        </tr>
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
                <td class="pageHeading"><h3>
                    <?php
          echo HEADING_TITLE;
?>
                  </h3></td>
                <td class="pageHeading2" align="right"></td>
                <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <?php
          echo tep_draw_form('status', FILENAME_ORDERS, '', 'get');
?>
                      <td class="smallText" align="right"><?php
          echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"');
?></td>
                        </form>
                    </tr>
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
?>
                        &nbsp;</td>
                    </tr>
                    <?php
          if (isset($_GET['cID'])) {
              $cID = tep_db_prepare_input($_GET['cID']);
              $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
          } //if (isset($_GET['cID']))
          elseif (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0)) {
              $status = tep_db_prepare_input($_GET['status']);
              $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";
          } //elseif (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0))
          else {
              $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
          } //else
          $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
          $orders_query = tep_db_query($orders_query_raw);
          while ($orders = tep_db_fetch_array($orders_query)) {
              if ($orders['customers_id'] == 0)
                  $orders['customers_name'] = '' . $orders['customers_name'];
              if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
                  $oInfo = new objectInfo($orders);
              } //if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo))
              if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
                  echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
              } //if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id))
              else {
                  echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
              } //else
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
              } //if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id))
              else {
                  echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>';
              } //else
?>
                        &nbsp;</td>
                    </tr>
                    <?php
          } //while ($orders = tep_db_fetch_array($orders_query))
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
                      } //while ($orders_vendors_data = tep_db_fetch_array($orders_vendors_data_query))
                  } //if (isset($oInfo) && is_object($oInfo))
                  break;
          } //switch ($action)
          if ((tep_not_null($heading)) && (tep_not_null($contents))) {
              echo '     <td valign="top"  width="220px">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '            </td>' . "\n";
          } //if ((tep_not_null($heading)) && (tep_not_null($contents)))
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