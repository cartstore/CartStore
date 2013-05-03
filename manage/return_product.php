<?php
/*
  $id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 */

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($_GET['order_id']);
    require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
 // check to find out what thedefault reason for returning a product is
 $default_priority_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RETURN_REASON'");
 $default_priority = tep_db_fetch_array($default_priority_query);
 $default_refund_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_REFUND_METHOD'");
 $default_refund = tep_db_fetch_array($default_refund_query);

  if (!$_GET['action']){
      $_GET['action'] = 'new';
  }

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'insert':
      case 'update':
         // carry out a query on all the existing orders tables, to get the required information
         $rma_create = tep_create_rma_value(11);
         $returns_status_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RETURN_STATUS_ID'");
         $default_return = tep_db_fetch_array($returns_status_query);
         $order_returns_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id = op.orders_id and o.orders_id = '" . $_GET['oID'] . " ' and op.products_id = '" . $_GET['products_id'] . "'");
         $orders_return = tep_db_fetch_array($order_returns_query);
         //CHECKING FOR ERRORS BEFORE SUBMISSION
         if ($_POST['returns_quantity'] > $orders_return['products_quantity']) {
             tep_redirect(tep_href_link(FILENAME_RETURN, 'qty_error=yes&order_id=' . $_GET['oID'] . '&products_id=' . $_GET['products_id']));
         }
           if (tep_validate_email($_POST['customers_email_address']) == false) {
		      tep_redirect(tep_href_link(FILENAME_RETURN, 'email_error=yes&order_id=' . $_GET['oID'] . '&products_id=' . $_GET['products_id']));
         }
           if ($_POST['contact_user_name'] == '') {
		      tep_redirect(tep_href_link(FILENAME_RETURN, 'name_error=yes&order_id=' . $_GET['oID'] . '&products_id=' . $_GET['products_id']));
         }

         $customers_id = $orders_return['customers_id'];
         $rma_value = tep_db_prepare_input($rma_create);
         $order_id = $_GET['oID'];
         $customers_name = $orders_return['customers_name'];
         $customers_acct = $orders_return['customers_acct'];
         $customers_company = $orders_return['customers_company'];
         $customers_street_address = $orders_return['customers_street_address'];
         $customers_suburb = $orders_return['customers_suburb'];
         $customers_city = $orders_return['customers_city'];
         $customers_postcode = $orders_return['customers_postcode'];
         $customers_state = $orders_return['customers_state'];
         $customers_country = $orders_return['customers_country'];
         $customers_telephone = $orders_return['customers_telephone'];
         $customers_fax = $orders_return['customers_fax'];
         $customers_email = tep_db_prepare_input($_POST['customers_email_address']);
         $customers_address_format_id = $orders_return['customers_address_format_id'];
         $delivery_name = $orders_return['delivery_name'];
         $delivery_company = $orders_return['delivery_company'];
         $delivery_street_address = $orders_return['delivery_street_address'];
         $delivery_suburb = $orders_return['delivery_suburb'];
         $delivery_city = $orders_return['delivery_city'];
         $delivery_postcode = $orders_return['delivery_postcode'];
         $delivery_state = $orders_return['delivery_state'];
         $delivery_country = $orders_return['delivery_country'];
         $delivery_address_format_id = $orders_return['delivery_address_format_id'];
         $billing_name = $orders_return['billing_name'];
         $billing_acct = $orders_return['billing_acct'];
         $billing_company = $orders_return['billing_company'];
         $billing_street_address = $orders_return['billing_street_address'];
         $billing_suburb = $orders_return['billing_suburb'];
         $billing_city = $orders_return['billing_city'];
         $billing_postcode = $orders_return['billing_postcode'];
         $billing_state = $orders_return['billing_state'];
         $billing_country = $orders_return['billing_country'];
         $billing_address_format_id = $orders_return['billing_address_format_id'];
         $comments = tep_db_prepare_input($_POST['support_text']);
         $returns_status =  $default_return['configuration_value'];
         $returns_reason = tep_db_prepare_input($_POST['support_priority']);
         $products_model = $orders_return['products_model'];
         $products_name = $orders_return['products_name'];
         $products_price = $orders_return['products_price'];
         $products_tax = $orders_return['products_tax'];
         $discount_made = $orders_return['products_discount_made'];
         $contact_user_name = tep_db_prepare_input($_POST['contact_user_name']);

         // work out price with tax
         $price_inc_tax = $products_price + tep_calculate_tax($products_price, $products_tax);
         $price_inc_quantity = $price_inc_tax * $_POST['returns_quantity'];
         $final_price =  $price_inc_quantity;
         $products_quantity = $_POST['returns_quantity'];// $orders_return['products_quantity'];
         $serial_number = $orders_return['products_serial_number'];
         $currency = $orders_return['currency'];
         $currency_value = $orders_return['currency_value'];
         $refund_method = $_POST['refund_method'];

 // error checking goes in here
   $support_error = false;

// None currently   Email, name and qty are checked above
   if (!$support_error) {
          $sql_data_array = array('customers_id' => $customers_id,
                                  'rma_value' => $rma_value,
                                  'order_id' => $order_id,
                                  'customers_name' => $customers_name,
                                  'customers_acct' => $customers_acct,
                                  'customers_company' => $customers_company,
                                  'customers_street_address' => $customers_street_address,
                                  'customers_suburb' => $customers_suburb,
                                  'customers_city' => $customers_city,
                                  'customers_postcode' => $customers_postcode,
                                  'customers_state' => $customers_state,
                                  'customers_country' => $customers_country,
                                  'customers_telephone' => $customers_telephone,
                                  'customers_fax' => $customers_fax,
                                  'customers_email_address' => $customers_email,
                                  'customers_address_format_id' => $customers_address_format_id,
                                  'delivery_name' => $delivery_name,
                                  'delivery_company' => $delivery_company,
                                  'delivery_street_address' => $delivery_street_address,
                                  'delivery_suburb' => $delivery_suburb,
                                  'delivery_city' => $delivery_city,
                                  'delivery_postcode' => $delivery_postcode,
                                  'delivery_state' => $delivery_state,
                                  'delivery_country' => $delivery_country,
                                  'delivery_address_format_id' => $delivery_address_format_id,
                                  'billing_name' => $billing_name,
                                  'billing_acct' => $billing_acct,
                                  'billing_company' => $billing_company,
                                  'billing_street_address' => $billing_street_address,
                                  'billing_suburb' => $billing_suburb,
                                  'billing_city' => $billing_city,
                                  'billing_postcode' => $billing_postcode,
                                  'billing_state' => $billing_state,
                                  'billing_country' => $billing_country,
                                  'billing_address_format_id' => $billing_address_format_id,
                                  'comments' => $comments,
                                  'returns_status' => $returns_status,
                                  'returns_reason' => $returns_reason,
                                  'currency' => $currency,
                                  'currency_value' =>$currency_value,
                                  'contact_user_name' => $contact_user_name,
                                 );
          if ($_GET['action'] == 'insert') {
            $insert_sql_data = array('date_purchased' => 'now()',
                                     );

            // returns information table updated,
            tep_db_perform(TABLE_RETURNS, $sql_data_array);
            $ticket_id = tep_db_insert_id();


           // tep_db_query("insert into " . TABLE_RETURN_PAYMENTS . " values ('', '" . $ticket_id . "', '', '', '', '', '0.00')");

            tep_db_perform(TABLE_RETURNS, $insert_sql_data, 'update', 'returns_id = \'' . $ticket_id . '\'');
              // now update returns products, and history tables
             $data_insert_sql = array('returns_id' => $ticket_id,
                                      'order_id' => $order_id,
                                      'products_id' => $_GET['products_id'],
                                      'products_model' =>$products_model,
                                      'products_name' => $products_name,
                                      'products_price' => $products_price,
                                      'products_discount_made' => $discount_made,
                                      'final_price' => $final_price,
                                      'products_tax' => $products_tax,
                                      'products_quantity' => $products_quantity,
                                      'products_serial_number' => $serial_number,
                                      );
          $returns_payment_sql = array('returns_id' => $ticket_id,
                                       'refund_payment_name' => $refund_method,
                                       'refund_payment_value' => $final_price,
                                       );

            tep_db_perform(TABLE_RETURN_PAYMENTS, $returns_payment_sql);
            tep_db_perform(TABLE_RETURNS_PRODUCTS_DATA, $data_insert_sql);
            tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS . " set products_returned = 1 where orders_id = '" . $_GET['oID'] . "' and products_id = '" . $_GET['products_id'] . "'");

          }

		  // Add returns status to returns status history table added 12-22-05
		  tep_db_query("insert into " . TABLE_RETURNS_STATUS_HISTORY . " (returns_id , returns_status, date_added, customer_notified, comments) values ('" . $ticket_id . "','" .   $returns_status . "', now(), 1,'" . tep_db_input($comments) . "')");

          // now send email to customer

           require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RETURN_EMAILS);
           $email_text = EMAIL_TEXT_TICKET_OPEN;
           $email_text .= EMAIL_THANKS_OPEN . EMAIL_TEXT_OPEN . EMAIL_CONTACT_OPEN . EMAIL_WARNING_OPEN;
           tep_mail($contact_user_name, $customers_email, EMAIL_SUBJECT_OPEN . ' #' . $rma_value, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          // send email to alternate address
           if (strlen($support_alternate_email) > 0) {
                $email_text = EMAIL_TEXT_TICKET_OPEN;
                $email_text .= EMAIL_THANKS_OPEN . EMAIL_TEXT_OPEN . EMAIL_CONTACT_OPEN . EMAIL_WARNING_OPEN;
                tep_mail($contact_user_name, $support_alternate_email, EMAIL_SUBJECT_OPEN . ' #' . $rma_value, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
             }

          // now send an email to the default administrator to let them know of new ticket
           //  $default_admin_email = tep_db_query("SELECT admin_id FROM " . TABLE_SUPPORT_TICKETS . " where ticket_id = '" . $_GET['ticket_id'] . "' and customers_id = '" . $customer_id . "'");
           //  $default_email = tep_db_fetch_array($default_admin_email);
          //   $admin_email_query = tep_db_query("SELECT support_assign_email, support_assign_name FROM " . TABLE_SUPPORT_ASSIGN . " where support_assign_id = '" . $default_email['admin_id'] . "' and language_id = '" . $languages_id . "'");
          //   $admin_email = tep_db_fetch_array($admin_email_query);
             $email_text_admin = EMAIL_TEXT_TICKET_ADMIN;
             $email_text_admin .= EMAIL_THANKS_ADMIN . EMAIL_TEXT_ADMIN . EMAIL_WARNING_ADMIN;
             tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , EMAIL_SUBJECT_ADMIN .' #' . $rma_value, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          // redirect to confirmation
            tep_redirect(tep_href_link(FILENAME_RETURN . '?action=sent&rma_value='. $rma_value . '&return_id=' . $ticket_id));
        } else {
          $_GET['action'] = 'new';
        }
        break;
       case 'default':
       tep_redirect(tep_href_link(FILENAME_DEFAULT));
       break;
    }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RETURN);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script>
 var submitDone = false;

 function submitForm(myForm, button) {

      if (!submitDone) {
         submitDone = true;
         button.value = 'Please Wait';
         button.disabled = true;
         myForm.submit();
      }
      else {
        alert ("Already submitted, please wait!");
      }
   return true;
 }
</script>
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
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="634" valign="top" class="prodTableRight"><center><table border="0" width="615" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
if ($_GET['action'] == 'sent'){
           $text_query = tep_db_query("SELECT * FROM " . TABLE_RETURNS_TEXT . " where return_text_id = '1' and language_id = '" . $languages_id . "'");
           $text = tep_db_fetch_array($text_query);

        //   tep_db_query("INSERT into " . TABLE_RETURN_PAYMENTS . " values ('', '" . $_GET['id'] . "', '', '', '', '', '')");
             ?>
          <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="1">
          <tr>
            <td class="main"><?php echo TEXT_RMA_CREATED; ?></td>
          </tr>
           <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
          </tr>
          <tr>
		  <td class="main"><?php echo '<center><font color=cc0000 size=3px><b>' . TEXT_YOUR_RMA_NUMBER . $_GET['rma_value'] . '</b></font></center>'; ?></td>
          </tr>
           <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '20', '20'); ?></td>
          </tr>

         <tr>
            <td align="right" valign=bottom><br><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ORDERS) . '">' .  IMAGE_BACK . '</a>'; ?></td>
         </tr>
        </table></td>
      </tr>


          <?php
} else// if ($_GET['action'] == 'new')
{
         $account_query = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
         $account = tep_db_fetch_array($account_query);
         // query the order table, to get all the product details
         $returned_products_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where o.orders_id = op.orders_id and op.orders_id = '" . $_GET['order_id'] . "' and products_id = '" . $_GET['products_id'] . "'");
         $returned_products = tep_db_fetch_array($returned_products_query);
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><form name="longsubmit" action="return_product.php?action=insert&oID=<?php echo $_GET['order_id'] . '&products_id=' . $_GET['products_id']; ?>" method=post>
        <td><table border="0" cellspacing="0" cellpadding="2" width=100%>
        <?php
        if (isset($qty_error)=='yes') {
        ?> <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr>
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><font color="CC0000"><?php echo TEXT_ERROR; ?></font></b></td>
              </tr>

           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
            <td class="main" align="left" width="100%">
            <?php
         			echo '<b><font color="CC0000">' . TEXT_ERROR_QUANTITY . '</b></font>' . "\n";
            ?>
            </td>
            </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
              <?php
              }
            ?>
              <?php
	          if (isset($email_error)=='yes') {
	          ?> <tr>
	          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
	            <tr>
	              <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	                <tr>
	                  <td class="main"><b><font color="CC0000"><?php echo TEXT_ERROR; ?></font></b></td>
	                </tr>

	             </table></td>
	              <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	              <tr>
	              <td class="main" align="left" width="100%">
	              <?php
	  				    echo '<b><font color="CC0000">' . TEXT_ERROR_EMAIL . '</b></font>' . "\n";
	              ?>
	              </td>
	              </tr>
	              </table></td>
	            </tr>
	          </table></td>
	        </tr>
	        <tr>
	              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
	            </tr>
	                <?php
	                }
            ?>
              <?php
	          if (isset($name_error)=='yes') {
	          ?> <tr>
	          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
	            <tr>
	              <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	                <tr>
	                  <td class="main"><b><font color="CC0000"><?php echo TEXT_ERROR; ?></font></b></td>
	                </tr>

	             </table></td>
	              <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	              <tr>
	              <td class="main" align="left" width="100%">
	              <?php
	  				    echo '<b><font color="CC0000">' . TEXT_ERROR_CONTACT . '</b></font>' . "\n";
	              ?>
	              </td>
	              </tr>
	              </table></td>
	            </tr>
	          </table></td>
	        </tr>
	        <tr>
	              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
	            </tr>
	                <?php
	                }
            ?>

            <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" colspan="2"><b><?php echo TEXT_SUPPORT_RETURN_HEADING; ?></small></b></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_PRODUCT_RETURN; ?></b><BR></td>
              </tr>



            </table></td>
            <td width="70%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><b>Qty</b></td>

                    <td class="smallText" align="right"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main">&nbsp;</td>
                    <td class="main" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo HEADING_PRODUCTS; ?></b></td>
                  </tr>
<?php
  }

//  $ordered_product_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " where order_id = '" . $_GET


    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . tep_draw_input_field('returns_quantity', $returned_products['products_quantity'], 'size=5') . '</td>' . "\n" .
         '            <td class="main" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;' . $returned_products['products_name'];


echo '</td>' . "\n";
echo '            <td class="main" align="right" valign="top">' . $currencies->format(($returned_products['products_price'] + (tep_calculate_tax(($returned_products['products_price']),($returned_products['products_tax'])))) * ($returned_products['products_quantity'])) . '</td>' . "\n" .
         '          </tr>' . "\n";

?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>


        <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
              <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_BILLING_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <?php
    echo '              <tr>' . "\n" .
         '                <td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
         '                <td class="main" align="left" width=95%>' . tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>') . '<br><b>' . $order->customer['email_address'] . '</b></td>' . "\n" .
         '              </tr>' . "\n";
           ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_DELIVERY_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <?php
    echo '              <tr>' . "\n" .
         '                <td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
         '                <td class="main" align="left" width=95%>' . tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td>' . "\n" .
         '              </tr>' . "\n";
           ?>
            </table></td>
          </tr>
        </table></td>
      </tr>

      <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_USER_EMAIL; ?></b></td>
              </tr>

           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <?php
    echo '              <tr>' . "\n" .
         '                <td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
         '                <td class="main" align="left" width=95%>' .  tep_draw_input_field('customers_email_address') . '</td>' . "\n" .
         '              </tr>' . "\n";

           ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_USER_NAME; ?></b></td>
              </tr>

           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <?php
    echo '              <tr>' . "\n" .
         '                <td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
         '                <td class="main" align="left" width=95%>' .  tep_draw_input_field('contact_user_name') . '</td>' . "\n" .
         '              </tr>' . "\n";

           ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_WHY_RETURN; ?></b></td>
              </tr>
           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
            <td class=main width=5%>&nbsp;</td>
            <td class="main" width=95%><?php //echo tep_draw_input_field('link_url'); ?>
          <?php
            $priority_query = tep_db_query("select return_reason_id, return_reason_name from ". TABLE_RETURN_REASONS . " where language_id = '" . $languages_id . "' order by return_reason_id desc");
            $select_box = '<select name="support_priority"  size="' . MAX_MANUFACTURERS_LIST . '">';
             if (MAX_MANUFACTURERS_LIST < 2) {
                     }
               while ($priority_values = tep_db_fetch_array($priority_query)) {
                 $select_box .= '<option value="' . $priority_values['return_reason_id'] . '"';
                 if ($default_priority['configuration_value'] ==  $priority_values['return_reason_id']) $select_box .= ' SELECTED';
                 $select_box .= '>' . substr($priority_values['return_reason_name'], 0, 20) . '</option>';
              }
             $select_box .= "</select>";
             echo $select_box;
          ?>
            </td>
          </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<!-- Begin refund method selection -->
      <tr>
                <td class="main">&nbsp;</td>
              </tr>
       <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_PREF_REFUND_METHOD; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
            <td class=main width=5%>&nbsp;</td>
            <td class="main" width=95%><?php //echo tep_draw_input_field('link_url'); ?>
          <?php
            $refund_query = tep_db_query("select refund_method_id, refund_method_name from ". TABLE_REFUND_METHOD . " where language_id = '" . $languages_id . "' order by refund_method_id asc");
            $select_box = '<select name="refund_method"  size="' . MAX_MANUFACTURERS_LIST . '">';
             if (MAX_MANUFACTURERS_LIST < 2) {
                     }
               while ($refund_values = tep_db_fetch_array($refund_query)) {
                 $select_box .= '<option value="' . $refund_values['refund_method_name'] . '"';
                 if ($default_refund['configuration_value'] ==  $refund_values['refund_method_id']) $select_box .= ' SELECTED';
                 $select_box .= '>' . substr($refund_values['refund_method_name'], 0, 20) . '</option>';
              }
             $select_box .= "</select>";
             echo $select_box;
          ?>
          <br><br>
          <?php
         $charge_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
         $charge = tep_db_fetch_array($charge_query);
// Don't show re-stocking info if it's set to zero in Admin > Configuration > Stock
          if ($charge['configuration_value'] != 0) {
            echo TEXT_SUPPORT_SURCHARGE . $charge['configuration_value'] .'%' . TEXT_SUPPORT_SURCHARGE_TWO;
            } ?>
            </td>
          </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<!-- End refund method selection -->
                <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
       <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="dataTableRow">
            <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_TEXT; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
           </table></td>
            <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <?php
    echo '              <tr>' . "\n" .
         '                <td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
         '                <td class="main" align="left" width=95%>' . tep_draw_textarea_field('support_text', 'soft', '40', '7') . '</td>' . "\n" .
         '              </tr>' . "\n";

           ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
                <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

       </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" class="main" valign="top" nowrap align="center">



            <input type=submit value="Submit" onClick="return submitForm(document.longsubmit, this)"></td>
          </tr>
        </table></td>
      </form></tr>
<?php
}
?>




             <!--

             -->

            </td>
          </tr>
        </table></td>
      </tr>

    </table></td>
<!-- body_text_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
