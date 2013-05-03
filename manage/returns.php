<?php
/*
  $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  // You may choose whatever status you wish to be green here
  define('GREEN_STATUS', 2);

  require('includes/application_top.php');
  include(DIR_WS_CLASSES . 'returns.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  include(DIR_WS_LANGUAGES . $language . '/' . 'returns.php');
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select returns_status_id, returns_status_name from " . TABLE_RETURNS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['returns_status_id'],
                               'text' => $orders_status['returns_status_name']);
    $orders_status_array[$orders_status['returns_status_id']] = $orders_status['returns_status_name'];
  }

  //language query
 $languages = tep_get_languages();
 $languages_array = array();
 $languages_selected = DEFAULT_LANGUAGE;
 for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
   $languages_array[] = array('id' => $languages[$i]['code'],
                              'text' => $languages[$i]['name']);
   if ($languages[$i]['directory'] == $language) {
     $languages_selected = $languages[$i]['code'];
   }
 }

  switch ($_GET['action']) {
    case 'update_order':
      $oID = tep_db_prepare_input($_GET['oID']);
      $status = tep_db_prepare_input($_POST['status']);
      $comments = tep_db_prepare_input($_POST['comments']);
      $order_updated = false;

       if ($_POST['restock_charge'] == 'on') {
          $restock_query = tep_db_query("SELECT configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
          $restock = tep_db_fetch_array($restock_query);
          $tax = $restock['configuration_value'];
         // $refund_with_tax = tep_add_tax($_POST['refund_amount'], $_POST['add_tax']);
          $refund_with_tax = $_POST['refund_amount'];
           $work_out_charge = (($refund_with_tax / 100) * $tax);
          $final_price =  ($refund_with_tax - $work_out_charge);
          $payment_method_query = tep_db_query("SELECT refund_method_name FROM " . TABLE_REFUND_METHOD . " where refund_method_id = '" . $_POST['department'] . "' and language_id = '" . $languages_id . "'");
          $payment_method = tep_db_fetch_array($payment_method_query);

            $payment_method = $payment_method['refund_method_name'];
            $payment_ref = $_POST['refund_reference'];
            $payment_date = $_POST['refund_date'];

            $sql_data_array = array('customer_method' => $payment_method,
                                     'refund_payment_value' => $final_price,
                                     'refund_payment_date' => 'now()',
                                     'refund_payment_reference' => $payment_ref,
                                     'refund_payment_deductions' => $work_out_charge,
                                     );

            tep_db_perform(TABLE_RETURN_PAYMENTS, $sql_data_array,  'update', 'returns_id = \'' . $oID . '\'');
           $order_update = true;
        } else {
           // $refund_with_tax = tep_add_tax($_POST['refund_amount'], $_POST['add_tax']);
            $refund_with_tax = $_POST['refund_amount'];
            $final_price = $refund_with_tax;
            $payment_method = $payment_method['payment_option_name'];
            $payment_ref = $_POST['refund_reference'];
            $payment_date = $_POST['refund_date'];
            $work_out_charge = '0';
            $sql_data_array = array('customer_method' => $_POST['department'],
                                     'refund_payment_value' => $final_price,
                                     'refund_payment_date' => 'now()',
                                     'refund_payment_reference' => $payment_ref,
                                     'refund_payment_deductions' => $work_out_charge,
                                     );

            tep_db_perform(TABLE_RETURN_PAYMENTS, $sql_data_array,  'update', 'returns_id = \'' . $oID . '\'');
             $order_updated= true;
     }

      // query and send routine for gv-refund / exchange product refund
      if ($_POST['gv_refund'] == 'on' && $_POST['complete'] == 'on') {
		  include(DIR_WS_LANGUAGES . $language . '/' . 'gv_mail.php');
		  $refund_amount_query = tep_db_query("select r.refund_payment_value, m.customers_email_address, m.customers_name, m.customers_id FROM " . TABLE_RETURN_PAYMENTS . " r, " . TABLE_RETURNS . " m where m.returns_id = r.returns_id and r.returns_id = '" . $oID . "'");
          $refund_amount = tep_db_fetch_array($refund_amount_query);
          $refund = $refund_amount['refund_payment_value'];
          $address = $refund_amount['customers_email_address'];
		  //additional query for ccGV System
          $gv_name_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $refund_amount['customers_email_address'] . "'");
   		  $gv_name = tep_db_fetch_array($gv_name_query );
   		  $firstname = $gv_name['customers_firstname'];
   		  $lastname = $gv_name['customers_lastname'];
		  $fullname = $firstname . $lastname;
		  $customer = $refund_amount['customers_id'];
          $gv_comments = $_POST['gv_comments'];
          $salt=$address;
          $gvid = md5(uniqid("","salt"));
          $gvid .= md5(uniqid("","salt"));
          $gvid .= md5(uniqid("","salt"));
          $gvid .= md5(uniqid("","salt"));
          srand((double)microtime()*1000000); // seed the random number generator
          $random_start = @rand(0, (128-16));
          $id1=substr($gvid, $random_start,16);
          $message = tep_db_prepare_input($gv_comments);
          $message .= "\n\n" .'Here is your credit for your return, you may use it on any of our products or share it with others if you like.' . "\n";
          $message .= "<br>" . TEXT_GV_WORTH  .  $currencies->format($refund) . "\n\n";
          $message .= TEXT_TO_REDEEM;
          $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
          $message .= '<b>To redeem please click here:</b>' . ' ' . "<a HREF='" . tep_catalog_href_link('gv_redeem.php', 'gv_no=' . $id1,'NONSSL',false) . "'>" . tep_catalog_href_link('gv_redeem.php', 'gv_no=' . $id1,'NONSSL',false) . "</a>\n\n" ;
          $message .= TEXT_OR_VISIT . HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . TEXT_ENTER_CODE . "\n\n\n";
          $message .= 'For more information on how our Gift Vouchers work and how you can share them with others visit our Gift Voucher FAQ at: ' . "<a HREF='" . tep_catalog_href_link('gv_faq.php') . "'>" . tep_catalog_href_link('gv_faq.php') . "</a>";

          // Send message containing store credit to customer

          tep_mail($fullname, $address, EMAIL_TEXT_GV_SUBJECT, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          // gv_tracking appears to have been deprecated long ago
          /* now create the tracking entry
          $gv_query=tep_db_query("insert into gv_tracking (gv_number, date_created, customer_id_sent, sent_firstname, sent_surname, emailed_to, gv_amount) values ('".$id1."', NOW(),'" . $customer . "','Sent by','Admin','" . $address . "','" . $refund . "')");  */

   		  //update the coupon table of ccGV System
  		  $gv_insert_query = tep_db_query ("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', 'G', '" . $refund . "', now())");
  		  $insert_id = tep_db_insert_id($insert_query);
   		  //update the coupon email track table of the ccgv system
  		  $gv_insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, sent_lastname, emailed_to, date_sent) values ('" . $insert_id . "','" . $customer . "','" . $firstname . "','" . $lastname . "','" . $address . "', now() )");
          // now update the refund table to reflect gv as refund method, and set the payment reference as the gv id
          $payment_name = 'Store Credit';
          $gv_update_sql = array('customer_method' => $payment_name,
                                 'refund_payment_value' => $refund,
                                 'refund_payment_reference' => $id1,
                                 'refund_payment_date' => 'now()',

                                 );
          tep_db_perform(TABLE_RETURN_PAYMENTS, $gv_update_sql, 'update', 'returns_id = \'' . $oID . '\'');
          $order_update = true;
          $refund_by_gv = true;

     }



      $check_status_query = tep_db_query("select customers_name, contact_user_name, customers_email_address, returns_status, date_purchased, comments from " . TABLE_RETURNS . " where returns_id = '" . tep_db_input($oID) . "'");
      $check_status = tep_db_fetch_array($check_status_query);
      if ($check_status['returns_status'] != $status) {
        tep_db_query("update " . TABLE_RETURNS . " set returns_status = '" . tep_db_input($status) . "', last_modified = now() where returns_id = '" . tep_db_input($oID) . "'");
     }

		// Begin: Shimon Pozin Dec. 8th, 2005
		$notify_status = ($_POST['notify'] == 'on') ? 1 : 0;

		tep_db_query("insert into " . TABLE_RETURNS_STATUS_HISTORY . " (returns_id , returns_status, date_added, customer_notified, comments) values ('" . tep_db_input($oID) . "','" .   $status . "', now(), '" . $notify_status . "','" . tep_db_input($comments) . "')");
		// End: Shimon Pozin Dec. 8th, 2005

    // tep_db_query("UPDATE " . TABLE_RETURN_PAYMENTS . " set refund_payment_value = '" . $final_price . "' where returns_id = '" . $_GET['oID'] . "' ");

        $customer_notified = '0';
        if ($_POST['notify'] == 'on') {
          $notify_comments = '';
          if ($_POST['notify_comments'] == 'on') {
            $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
          }
	    $updateOrder = new order($oID);
          $email = STORE_NAME . "<br>" . HEADING_TITLE . ' ' . TABLE_HEADING_STATUS . "<br>" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n" . "<a HREF='" .  tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $updateOrder->info['order_id'], 'SSL'). "'>" .tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $updateOrder->info['order_id'], 'SSL'). "</a>\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . "\n\n"  . $notify_comments;

          tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          $customer_notified = '1';
        }

       // tep_db_query("insert into " . TABLE_RETURNS_STATUS_HISTORY . " (returns_id, new_value, old_value, date_added, customer_notified) values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', '" . $check_status['orders_status'] . "', now(), '" . $customer_notified . "')");

        $order_updated = true;

      if ($_POST['complete'] == 'on') {
         $notify_comments = '';
         tep_db_query("UPDATE " . TABLE_RETURNS . " set returns_date_finished = now(), date_finished = now() where returns_id = '" . $oID . "'");
         $order_update = true;
         $return_complete = true;

       }

      if ($_POST['restock_products'] == 'on') {
          $order_query_restock = tep_db_query("SELECT products_id, products_quantity FROM " . TABLE_RETURNS_PRODUCTS_DATA . " where returns_id = '" . $oID . "'");
          $order_query = tep_db_fetch_array($order_query_restock);
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order_query['products_quantity'] . " where products_id = '" . $order_query['products_id'] . "'");

          $order_update = true;
          $restock_complete = true;

      }

      if ($check_status['comments'] != $comments) {
        tep_db_query("update " . TABLE_RETURNS . " set comments = '" . tep_db_input($comments) . "' where returns_id = '" . tep_db_input($oID) . "'");
        $order_updated = true;
      }

      if ($order_updated) {
       $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
         if ($refund_by_gv) {
             $messageStack->add_session(SUCCESS_RETURNED_GIFT, 'success');
         }

         if($return_complete) {
             $messageStack->add_session(SUCCESS_RETURN_CLOSED, 'success');
         }

         if($restock_complete) {
             $messageStack->add_session(SUCCESS_PRODUCT_TO_STOCK, 'success');
         }

      } else {
        $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
      }

      tep_redirect(tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $oID = tep_db_prepare_input($_GET['oID']);

      tep_remove_return($oID, $_POST['restock']);

      tep_redirect(tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action'))));
break;

// Remove CVV Number

  }

  if ( ($_GET['action'] == 'edit') && ($_GET['oID']) ) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select returns_id from " . TABLE_RETURNS . " where returns_id = '" . tep_db_input($oID) . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>
<link href="templates/admin/css/template_css.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="includes/general.js"></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr>
<td width="<?php echo BOX_WIDTH; ?>" valign="top">
   <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
   <tr><td>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
	</td>
	</tr>
  </table>
  </td>
<!-- body_text //-->
<td width="100%" valign="top">
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
  <?php
  if ( ($_GET['action'] == 'edit') && ($order_exists) ) {
    $order = new order($oID);

  $support_departments = array();
  $support_department_array = array();
  $support_department_query = tep_db_query("select * from " . TABLE_REFUND_METHOD . " ");
  while ($support_department = tep_db_fetch_array($support_department_query)) {
    $support_departments[] = array('id' => $support_department['refund_method_name'],
                               'text' => $support_department['refund_method_name']);
    $support_department_array[$support_department['refund_method_id']] = $support_department['refund_method_name'];
  }




  $restock_query = tep_db_query("SELECT configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
  $restock = tep_db_fetch_array($restock_query);

$return_complete_query = tep_db_query("SELECT returns_date_finished FROM " . TABLE_RETURNS . " where returns_id = '" . $oID . "'");
$return_complete = tep_db_fetch_array($return_complete_query);

if ($return_complete['returns_date_finished'] != '0000-00-00 00:00:00') {
 ?>
  <tr>
    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
          <td class="pageHeading" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('action'))) . '">' .  IMAGE_BACK . '</a>'; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="3"><?php echo tep_draw_separator(); ?></td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b>Shipping Address</b></td>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, true, ' ', '<br>'); ?></td>
              </tr>

              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, true, ' ', '<br>'); ?></td>
              </tr>
            </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b>Billing Address</b></td>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, true, ' ', '<br>'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <?php
// BOF: WebMakers.com Added: Show Order Info
?>
      <!-- add Order # // -->
	  <tr>
        <td class="main"><b><?php echo ORDER_NO; ?></b></td>
        <td class="main"><?php echo $order->info['order_id']; ?></td>
      </tr>
	  <!-- add Returns #  -->
      <tr>
        <td class="main"><b><?php echo TEXT_INVOICE_NO; ?></b></td>
        <td class="main"><?php echo tep_db_input($oID); ?></td>
      </tr>
      <!-- add date/time // -->
      <tr>
        <td class="main"><b><?php echo TEXT_DATE_TIME; ?></b></td>
        <td class="main"><?php echo tep_datetime_short($order->info['date_purchased']); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_IP_ADDRESS; ?></b></td>
        <td class="main"><?php echo $order->info['rma_value']; ?></td>
      </tr>
      <?php
// EOF: WebMakers.com Added: Show Order Info
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <?php
    if ( (($order->info['cc_type']) || ($order->info['cc_owner']) || ($order->info['cc_number']) || ($order->info['cvvnumber'])) ) {
?>
      <tr>
        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
        <td class="main"><?php echo $order->info['cc_type']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
        <td class="main"><?php echo $order->info['cc_owner']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
        <td class="main"><?php echo $order->info['cc_number']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
        <td class="main"><?php echo $order->info['cc_expires']; ?></td>
      </tr>
      <?php
}
?>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          <td class="dataTableHeadingContent" align="right">Deductions</td>
          <td class="dataTableHeadingContent" align="right">Refund Amount</td>
        </tr>
        <?php
// for ($i=0; $i<sizeof($order->products); $i++) {
       $refunds_payment_query = tep_db_query("SELECT * FROM " . TABLE_RETURN_PAYMENTS . " where returns_id = '" . $oID . "'");
       $refund = tep_db_fetch_array($refunds_payment_query);



      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products['name'];



      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products['final_price'] * $order->products['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products['final_price'], $order->products['tax']) * $order->products['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
            '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($refund['refund_payment_deductions']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($refund['refund_payment_value']) . '</b></td>' . "\n";
       echo '          </tr>' . "\n";
//}
?>
        <tr>
          <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
              <?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
      <td>
		    <?php echo tep_draw_form('status', FILENAME_RETURNS, tep_get_all_get_params(array('action')) . 'action=update_order');
           $order_status_query = tep_db_query("SELECT returns_status_name FROM " . TABLE_RETURNS_STATUS . " where returns_status_id = '" . $order->info['orders_status'] . "'");
           $order_status  = tep_db_fetch_array($order_status_query);
	        ?>
		  <table border="0" cellspacing="0" cellpadding="2">
			  <!-- New code to display status history for completed returns -->
			  <tr>
                      <td colspan="2">
					      <table border="1" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                          </tr>
                          <?php
    $returns_history_query = tep_db_query("select returns_status, date_added, customer_notified, comments from " . TABLE_RETURNS_STATUS_HISTORY . " where returns_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($returns_history_query)) {
      while ($returns_history = tep_db_fetch_array($returns_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($returns_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($returns_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$returns_history['returns_status']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(tep_db_output($returns_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } elseif(strlen($order->info['comments']) != 0) {

	          // Shimon Pozin Dec. 8th 2005 - old code for completed return
			  echo '          <tr>' . "\n" .
			       '          <td class="smallText">' . $order->info['refund_date'] . "</td>\n" .
			       '          <td class="smallText">' . tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n" .
                   '          <td class=smallText>' . $order_status['returns_status_name'] . "</td>\n" .
			       '          <td class=smallText>' . nl2br($order->info['comments']) . "</td>\n" .
			       '          </tr>';

	} else { // Invoked when old code (without status history) is phased out in the next version

        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
    ?>
                        </table></td>
                    </tr>

					<!-- End of the code to show history of status for completed returns -->


              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" width=25% valign=top><b><?php echo CUSTOMER_PREFERENCE; ?></b></td>
                <td width=65% class=main><?php echo $order->info['department']; ?></td>
              </tr>
              <tr>
                <td class="main" width=25% valign=top><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
                <td width=65% class=main><?php echo $order->info['customer_method']; ?></td>
              </tr>
              <tr>
                <td class="main" width=25% valign=top><b><?php echo ENTRY_PAYMENT_REFERENCE; ?></b></td>
                <td width=65% class=main><?php echo $order->info['payment_reference']; ?></td>
              </tr>
              <?php
               $price_new = $order->info['refund_amount'];

              ?>
              <tr>
                <td class="main" width=25%><b><?php echo ENTRY_PAYMENT_AMOUNT; ?></b></td>
                <td width=65% class=main><?php echo $currencies->format($price_new ); ?></td>
              </tr>
              <tr>
                <td class="main" width=25%><b><?php echo ENTRY_PAYMENT_DATE; ?></b></td>
                <td width=65% class=main><?php echo tep_date_short($order->info['refund_date']); ?></td>
              </tr>
              <?php
              $restock_query = tep_db_query("SELECT configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
              $restock = tep_db_fetch_array($restock_query);
              $tax = $restock['configuration_value'];
              $work_out_charge = ((tep_add_tax($order->info['refund_amount'],$order->products['tax']) / 100) * $tax);
              echo '<input type=hidden name=add_tax value=' . $order->products['tax'] . '>';
              ?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
			  </form>
            </table></td>
  </tr>
  <?php
 // }
  ?>
  <?php
} else {
?>
  <tr>
    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
          <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
          <td class="pageHeading" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('action'))) . '">' .  IMAGE_BACK . '</a>'; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="3"><?php echo tep_draw_separator(); ?></td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, true, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b>Contact Email:</b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
              <?php
              $contact_name_query = tep_db_query("select r.refund_payment_value, m.customers_email_address, m.customers_name, m.contact_user_name, m.customers_id FROM " . TABLE_RETURN_PAYMENTS . " r, " . TABLE_RETURNS . " m where m.returns_id = r.returns_id and r.returns_id = '" . $oID . "'");
              $contact_name = tep_db_fetch_array($contact_name_query);
              $contact_user_name = $contact_name['contact_user_name'];
              if ($contact_user_name == '') {
              $contact_user_name = NA;
              }
              ?>
              <tr>
                <td class="main"><b><?php echo ENTRY_CONTACT_NAME; ?></b></td>
                <td class="main"><?php echo $contact_user_name; ?></td>
              </tr>
            </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format(1, $order->customer, true, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
            </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, true, ' ', '<br>'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <?php
// BOF: WebMakers.com Added: Show Order Info
?>
      <!-- add Order # // -->
      <tr>
        <td class="main"><b><?php echo ORDER_NO; ?></b></td>
        <td class="main"><?php echo '<a href="' . tep_href_link("orders.php", 'oID=' . $order->info['order_id'] . '&action=edit')  . '" TARGET="_blank"><u>' . $order->info['order_id']; ?></u></a></td>
      </tr>
      <!-- add Return # // -->
      <tr>
        <td class="main"><b><?php echo TEXT_INVOICE_NO; ?></b></td>
        <td class="main"><?php echo tep_db_input($oID); ?></td>
      </tr>
      <!-- add date/time // -->
      <tr>
        <td class="main"><b><?php echo TEXT_DATE_TIME; ?></b></td>
        <td class="main"><?php echo tep_datetime_short($order->info['date_purchased']); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_IP_ADDRESS; ?></b></td>
        <td class="main"><?php echo $order->info['rma_value']; ?></td>
      </tr>
      <?php
// EOF: WebMakers.com Added: Show Order Info
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $order->info['payment_method']; ?></td>
      </tr>
      <?php
    if ( (($order->info['cc_type']) || ($order->info['cc_owner']) || ($order->info['cc_number']) || ($order->info['cvvnumber'])) ) {
?>
      <tr>
        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
        <td class="main"><?php echo $order->info['cc_type']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
        <td class="main"><?php echo $order->info['cc_owner']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
        <td class="main"><?php echo $order->info['cc_number']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
        <td class="main"><?php echo $order->info['cc_expires']; ?></td>
      </tr>
      <?php
    }
?>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
        </tr>
        <?php
   // for ($i=0; $i<sizeof($order->products); $i++) {

      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products['name'];



      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products['final_price'] * $order->products['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products['final_price'], $order->products['tax']) * $order->products['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
   // }
?>
        <tr>
          <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
              <?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <!-- New code to display status history for completed returns -->
			  <tr>
                      <td colspan="2">
					      <table border="1" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
                            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                          </tr>
                          <?php
    $returns_history_query = tep_db_query("select returns_status, date_added, customer_notified, comments from " . TABLE_RETURNS_STATUS_HISTORY . " where returns_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($returns_history_query)) {
      while ($returns_history = tep_db_fetch_array($returns_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($returns_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($returns_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$returns_history['returns_status']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(tep_db_output($returns_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } elseif(strlen($order->info['comments']) != 0) {

	          // Shimon Pozin Dec. 8th 2005 - old code for completed return
			  echo '          <tr>' . "\n" .
			       '          <td class="smallText">' . $order->info['refund_date'] . "</td>\n" .
			       '          <td class="smallText">' . tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n" .
                   '          <td class=smallText>' . $order_status['returns_status_name'] . "</td>\n" .
			       '          <td class=smallText>' . nl2br($order->info['comments']) . "</td>\n" .
			       '          </tr>';

	} else { // Invoked when old code (without status history) is phased out in the next version

        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
    ?>
                        </table></td>
                    </tr>

					<!-- End of the code to show history of status for completed returns -->
  <tr>
    <td class="main"><br>
      <b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
  </tr>
  <tr><?php echo tep_draw_form('status', FILENAME_RETURNS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
    <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', ''); ?></td>
  </tr>
  <tr>
    <td><?php include ("comment_bar_return.html"); ?>
</td>
</tr>
<tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
</tr>
<tr>
<td><table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" width=25%><b><font color="CC0000"><?php echo ENTRY_STATUS; ?></font></b></td>
            <td width=65% class=main><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" width=25% valign=top><b><font color="CC0000"><?php echo ENTRY_PAYMENT_METHOD; ?></font></b></td>
            <td width=65% class=main><?php echo tep_draw_pull_down_menu('department', $support_departments); ?></td>
          </tr>
          <tr>
            <td class=main width=25% valign=top><b><?php echo TEXT_CUSTOM_PREF_METHOD; ?></b></td>
            <td width=65% class=main><b><?php echo $order->info['department']; ?></b></td>
          </tr>
          <tr>
            <td class="main" width=25% valign=top><b><?php echo ENTRY_PAYMENT_REFERENCE; ?></b></td>
            <td width=65% class=main><?php echo tep_draw_input_field('refund_reference', $order->info['payment_reference']); ?></td>
          </tr>
          <?php
               $price_new = $order->info['refund_amount'];

              ?>
          <tr>
            <td class="main" width=25%><b><font color="CC0000"><?php echo ENTRY_PAYMENT_AMOUNT; ?></font></b></td>
            <td width=65% class=main><?php echo tep_draw_input_field('refund_amount', $price_new ); ?></td>
          </tr>
          <tr>
            <td class="main" width=25%><b><?php echo ENTRY_PAYMENT_DATE; ?></b></td>
            <td width=65% class=main><?php echo tep_draw_input_field('refund_date', tep_date_short($order->info['refund_date'])); ?></td>
          </tr>
          <?php
              $restock_query = tep_db_query("SELECT configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
              $restock = tep_db_fetch_array($restock_query);
              $tax = $restock['configuration_value'];
              $work_out_charge = ((tep_add_tax($order->info['refund_amount'],$order->products['tax']) / 100) * $tax);
              echo '<input type=hidden name=add_tax value=' . $order->products['tax'] . '>';
              ?>
          <tr>
            <td class="main" width=25%><b><?php echo ENTRY_RESTOCK_CHARGE; ?></b></td>
            <td width=65% class=main><?php echo tep_draw_checkbox_field('restock_charge', '', false); ?>&nbsp;&nbsp;(<?echo $currencies->format($work_out_charge); ?>)</td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" width=25%><b><?php echo SUCCESS_RETURNED_GIFT; ?></b></td>
            <td width=65% class=main><?php echo tep_draw_checkbox_field('gv_refund', '', false); ?></td>
          </tr>
          <tr>
            <td class=main valign=top><b><?php echo TEXT_GIFT_COMMENT; ?></b></td>
            <td class="main"><?php echo tep_draw_textarea_field('gv_comments', 'soft', '60', '5'); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" align="left" width="30%"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b><?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
            <td class="main" align="left" width="30%"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b><?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
          </tr>
          <tr>
            <td class="main" align="left" width="30%"><b><?php echo TEXT_COMPLETE_RETURN; ?></b><?php echo tep_draw_checkbox_field('complete', '', false); ?></td>
            <td class="main" align="left" width="30%"><b><?php echo TEXT_BACK_TO_STOCK; ?></b><?php echo tep_draw_checkbox_field('restock_products', '', false); ?></td>
          </tr>
        </table></td>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td valign="top" align=right><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
    </TR>
    </tr>
  </table></td>
</tr>
</form>
<tr>
  <td colspan="2" align="right"><?php echo

				'<a class="button" href="' . tep_href_link(FILENAME_RETURNS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_INVOICE . '</a> <a class="button" href="' . tep_href_link("returns_packingslip.php", 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('action'))) . '">' .  IMAGE_BACK . '</a>'; ?></td>
</tr>
<?php

}
  } else {
// Product return request listing ?>
<tr>
  <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
        <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr><?php echo tep_draw_form('returns', FILENAME_RETURNS, '', 'get'); ?>
              <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('cID', '', 'size="15"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form>
            </tr>
            <tr><?php echo tep_draw_form('status', FILENAME_RETURNS, '', 'get'); ?>
              <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => 'All Returns')), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>
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
            <tr class="dataTableHeadingRow">
              <?php // ADDED BY BURT ?>
              <td class="dataTableHeadingContent" align="center">RMA #</td>
              <?php // END BURT ?>
              <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
              <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REASON; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
            </tr>
            <?php
    if ($_GET['cID']) {
      $cID = tep_db_prepare_input($_GET['cID']);
// NEW "IF ELSEIF ELSE" ADDED BY BURT SO REPLACE THE OLD ONE WITH THIS

      $orders_query_raw = "select o.returns_id, o.returns_status, o.customers_name, o.contact_user_name, o.rma_value, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.return_reason_name, op.final_price, o.returns_date_finished, rs.returns_status_name from " . TABLE_RETURNS . " o left join " . TABLE_RETURNS_PRODUCTS_DATA . " op on (o.returns_id = op.returns_id), " . TABLE_RETURN_REASONS . " s " . ", " . TABLE_RETURNS_STATUS . " rs where o.returns_reason = s.return_reason_id and s.language_id = '" . $languages_id . "' and o.rma_value = '" . $cID . "' and o.returns_status = rs.returns_status_id order by o.returns_id DESC";
     } elseif ($_GET['status']) {
      $status = tep_db_prepare_input($_GET['status']);

     $orders_query_raw = "select o.returns_id, o.returns_status, o.customers_name, o.contact_user_name, o.rma_value, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.return_reason_name, op.final_price, o.returns_date_finished, rs.returns_status_name from " . TABLE_RETURNS . " o left join " . TABLE_RETURNS_PRODUCTS_DATA . " op on (o.returns_id = op.returns_id), " . TABLE_RETURN_REASONS . " s " . ", " . TABLE_RETURNS_STATUS . " rs where o.returns_reason = s.return_reason_id and s.language_id = '" . $languages_id . "' and o.returns_status = '" . $status . "' and o.returns_status = rs.returns_status_id order by o.returns_id DESC";

      } else {
      $orders_query_raw = "select o.returns_id, o.returns_status, o.customers_name, o.contact_user_name, o.rma_value, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.return_reason_name, op.final_price, o.returns_date_finished, rs.returns_status_name from " . TABLE_RETURNS . " o left join " . TABLE_RETURNS_PRODUCTS_DATA . " op on (o.returns_id = op.returns_id), " . TABLE_RETURN_REASONS . " s " . ", " . TABLE_RETURNS_STATUS . " rs where o.returns_reason = s.return_reason_id and s.language_id = '" . $languages_id . "' and o.returns_status = rs.returns_status_id order by o.returns_id DESC";
    }
// END BURT
    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
      if (((!$_GET['oID']) || ($_GET['oID'] == $orders['returns_id'])) && (!$oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if ( (is_object($oInfo)) && ($orders['returns_id'] == $oInfo->returns_id) ) {
        echo '              <tr class="dataTableRowSelected">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['returns_id']) . '\'">' . "\n";
      }

	  if($orders['returns_status'] == GREEN_STATUS){
	  	$order_done = '<font color="green">';
	  }
	  elseif ($orders['returns_date_finished'] == '0000-00-00 00:00:00' && !$return_complete) {
		$order_done = '<font color="red">';
	  }
	  else {
		$order_done = '';
	  }

	  if ($orders['contact_user_name'] == '') {
		$return_customer_name = $orders['customers_name'];
	  } else {
		$return_customer_name = ($orders['contact_user_name'] . '-' . $orders['customers_name']);
	  }
?>
            <?php // ADDED BY BURT ?>
            <td class="dataTableContent" align="center"><b><?php echo $orders['rma_value']; ?></b></a>
                <?php // END BURT ?>
              <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['returns_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '&nbsp;' . $order_done . $return_customer_name . '</font></a>' ; ?></td>
              <td class="dataTableContent" align="right"><?php echo $currencies->format($orders['final_price']); ?></td>
              <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
              <td class="dataTableContent" align="right"><?php echo $orders['return_reason_name']; ?></td>
              <td class="dataTableContent" align="right"><?php echo $orders['returns_status_name'];?></td>
            </tr><?php
    } // WHILE LOOP
?>
            <tr>
              <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
        <?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->returns_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->returns_id) . '">' . IMAGE_CANCEL . '</a>');
      break;
    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->returns_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->returns_id . '&action=edit') . '">' .  IMAGE_EDIT . '</a> <a class="button" href="' . tep_href_link("returns_packingslip.php", 'oID=' . $oInfo->returns_id) . '" TARGET="_blank">' .  IMAGE_ORDERS_PACKINGSLIP . '</a> <a class="button" href="' . tep_href_link(FILENAME_RETURNS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->returns_id . '&action=delete') . '">' .  IMAGE_DELETE . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_RETURNS_INVOICE, 'oID=' . $oInfo->returns_id) . '" TARGET="_blank">' .  IMAGE_ORDERS_INVOICE .

// Begin IceTheNet Repair Packing Slip Mod
// If you really need a packing slip then you can modify admin/packingslip.php
// and add IMAGE_ORDER_PACKINGSLIP to application_top_refund.php

 /* '</a> <a href="' . tep_href_link(FILENAME_RETURNS_PACKINGSLIP, 'oID=' . $oInfo->returns_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) .
 */
//End IceTheNet Fix Packing Slip Mod

 '</a>');   // don’t forget this very important

        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . tep_date_short($oInfo->returns_date_finished));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="225px" valign="top">' . "\n";

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
</table>
</td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
