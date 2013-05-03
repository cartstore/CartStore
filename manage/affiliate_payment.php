<?php
/*
  $Id: affiliate_payment.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $payments_statuses = array();
  $payments_status_array = array();
  $payments_status_query = tep_db_query("select affiliate_payment_status_id, affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT_STATUS . " where affiliate_language_id = '" . $languages_id . "'");
  while ($payments_status = tep_db_fetch_array($payments_status_query)) {
    $payments_statuses[] = array('id' => $payments_status['affiliate_payment_status_id'],
                                 'text' => $payments_status['affiliate_payment_status_name']);
    $payments_status_array[$payments_status['affiliate_payment_status_id']] = $payments_status['affiliate_payment_status_name'];
  }

  switch ($_GET['action']) {
    case 'start_billing':
// Billing can be a lengthy process
      tep_set_time_limit(0);
// We are only billing orders which are AFFILIATE_BILLING_TIME days old
      $time = mktime(1, 1, 1, date("m"), date("d") - AFFILIATE_BILLING_TIME, date("Y"));
      $oldday = date("Y-m-d", $time);
// Select all affiliates who earned enough money since last payment
      $sql="
        SELECT a.affiliate_id, sum(a.affiliate_payment) 
          FROM " . TABLE_AFFILIATE_SALES . " a, " . TABLE_ORDERS . " o 
          WHERE a.affiliate_billing_status != 1 and a.affiliate_orders_id = o.orders_id and o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " and a.affiliate_date <= '" . $oldday . "' 
          GROUP by a.affiliate_id 
          having sum(a.affiliate_payment) >= '" . AFFILIATE_THRESHOLD . "'
        ";
      $affiliate_payment_query = tep_db_query($sql);

// Start Billing:
      while ($affiliate_payment = tep_db_fetch_array($affiliate_payment_query)) {

// mysql does not support joins in update (planned in 4.x)

// Get all orders which are AFFILIATE_BILLING_TIME days old
        $sql="
        SELECT a.affiliate_orders_id 
          FROM " . TABLE_AFFILIATE_SALES . " a, " . TABLE_ORDERS . " o 
          WHERE a.affiliate_billing_status!=1 and a.affiliate_orders_id=o.orders_id and o.orders_status>=" . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " and a.affiliate_id='" . $affiliate_payment['affiliate_id'] . "' and a.affiliate_date <= '" . $oldday . "'
        ";
        $affiliate_orders_query=tep_db_query ($sql);
        $orders_id ="(";
        while ($affiliate_orders = tep_db_fetch_array($affiliate_orders_query)) {
          $orders_id .= $affiliate_orders['affiliate_orders_id'] . ",";
        }
        $orders_id = substr($orders_id, 0, -1) .")";

// Set the Sales to Temp State (it may happen that an order happend while billing)
        $sql="UPDATE " . TABLE_AFFILIATE_SALES . " 
        set affiliate_billing_status=99 
          where affiliate_id='" .  $affiliate_payment['affiliate_id'] . "' 
          and affiliate_orders_id in " . $orders_id . " 
        ";
        tep_db_query ($sql);

// Get Sum of payment (Could have changed since last selects);
        $sql="
        SELECT sum(affiliate_payment) as affiliate_payment
          FROM " . TABLE_AFFILIATE_SALES . " 
          WHERE affiliate_id='" .  $affiliate_payment['affiliate_id'] . "' and  affiliate_billing_status=99 
        ";
        $affiliate_billing_query = tep_db_query ($sql);
        $affiliate_billing = tep_db_fetch_array($affiliate_billing_query);
// Get affiliate Informations
        $sql="
        SELECT a.*, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id 
          from " . TABLE_AFFILIATE . " a 
          left join " . TABLE_ZONES . " z on (a.affiliate_zone_id  = z.zone_id) 
          left join " . TABLE_COUNTRIES . " c on (a.affiliate_country_id = c.countries_id)
          WHERE affiliate_id = '" . $affiliate_payment['affiliate_id'] . "' 
        ";
        $affiliate_query=tep_db_query ($sql);
        $affiliate = tep_db_fetch_array($affiliate_query);

// Get need tax informations for the affiliate
        $affiliate_tax_rate = tep_get_affiliate_tax_rate(AFFILIATE_TAX_ID, $affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id']);
        $affiliate_tax = tep_round(($affiliate_billing['affiliate_payment'] * $affiliate_tax_rate / 100), 2); // Netto-Provision
        $affiliate_payment_total = $affiliate_billing['affiliate_payment'] + $affiliate_tax;
// Bill the order
        $affiliate['affiliate_state'] = tep_get_zone_code($affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id'], $affiliate['affiliate_state']);
        $sql_data_array = array('affiliate_id' => $affiliate_payment['affiliate_id'],
                                'affiliate_payment' => $affiliate_billing['affiliate_payment'],
                                'affiliate_payment_tax' => $affiliate_tax,
                                'affiliate_payment_total' => $affiliate_payment_total,
                                'affiliate_payment_date' => 'now()',
                                'affiliate_payment_status' => '0',
                                'affiliate_firstname' => $affiliate['affiliate_firstname'],
                                'affiliate_lastname' => $affiliate['affiliate_lastname'],
                                'affiliate_street_address' => $affiliate['affiliate_street_address'],
                                'affiliate_suburb' => $affiliate['affiliate_suburb'],
                                'affiliate_city' => $affiliate['affiliate_city'],
                                'affiliate_country' => $affiliate['countries_name'],
                                'affiliate_postcode' => $affiliate['affiliate_postcode'],
                                'affiliate_company' => $affiliate['affiliate_company'],
                                'affiliate_state' => $affiliate['affiliate_state'],
                                'affiliate_address_format_id' => $affiliate['address_format_id']);
        tep_db_perform(TABLE_AFFILIATE_PAYMENT, $sql_data_array);
        $insert_id = tep_db_insert_id(); 
// Set the Sales to Final State 
        tep_db_query("update " . TABLE_AFFILIATE_SALES . " set affiliate_payment_id = '" . $insert_id . "', affiliate_billing_status = 1, affiliate_payment_date = now() where affiliate_id = '" . $affiliate_payment['affiliate_id'] . "' and affiliate_billing_status = 99");

// Notify Affiliate
        if (AFFILIATE_NOTIFY_AFTER_BILLING == 'true') {
          $check_status_query = tep_db_query("select af.affiliate_email_address, ap.affiliate_lastname, ap.affiliate_firstname, ap.affiliate_payment_status, ap.affiliate_payment_date, ap.affiliate_payment_date from " . TABLE_AFFILIATE_PAYMENT . " ap, " . TABLE_AFFILIATE . " af where affiliate_payment_id  = '" . $insert_id . "' and af.affiliate_id = ap.affiliate_id ");
          $check_status = tep_db_fetch_array($check_status_query);
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_AFFILIATE_PAYMENT_INFO, 'payment_id=' . $insert_id, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['affiliate_payment_date']) . "\n\n" . EMAIL_TEXT_NEW_PAYMENT;
          tep_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
        }
      }
      $messageStack->add_session(SUCCESS_BILLING, 'success');

      tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'update_payment':
      $pID = tep_db_prepare_input($_GET['pID']);
      $status = tep_db_prepare_input($_POST['status']);

      $payment_updated = false;
      $check_status_query = tep_db_query("select af.affiliate_email_address, ap.affiliate_lastname, ap.affiliate_firstname, ap.affiliate_payment_status, ap.affiliate_payment_date, ap.affiliate_payment_date from " . TABLE_AFFILIATE_PAYMENT . " ap, " . TABLE_AFFILIATE . " af where affiliate_payment_id = '" . tep_db_input($pID) . "' and af.affiliate_id = ap.affiliate_id ");
      $check_status = tep_db_fetch_array($check_status_query);
      if ($check_status['affiliate_payment_status'] != $status) {
        tep_db_query("update " . TABLE_AFFILIATE_PAYMENT . " set affiliate_payment_status = '" . tep_db_input($status) . "', affiliate_last_modified = now() where affiliate_payment_id = '" . tep_db_input($pID) . "'");
        $affiliate_notified = '0';
// Notify Affiliate
        if ($_POST['notify'] == 'on') {
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $pID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_AFFILIATE_PAYMENT_INFO, 'payment_id=' . $pID, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['affiliate_payment_date']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $payments_status_array[$status]);
          tep_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
          $affiliate_notified = '1';
        }

        tep_db_query("insert into " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " (affiliate_payment_id, affiliate_new_value, affiliate_old_value, affiliate_date_added, affiliate_notified) values ('" . tep_db_input($pID) . "', '" . tep_db_input($status) . "', '" . $check_status['affiliate_payment_status'] . "', now(), '" . $affiliate_notified . "')");
        $order_updated = true;
      }

      if ($order_updated) {
       $messageStack->add_session(SUCCESS_PAYMENT_UPDATED, 'success');
      }

      tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $pID = tep_db_prepare_input($_GET['pID']);

      tep_db_query("delete from " . TABLE_AFFILIATE_PAYMENT . " where affiliate_payment_id = '" . tep_db_input($pID) . "'");
      tep_db_query("delete from " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " where affiliate_payment_id = '" . tep_db_input($pID) . "'");

      tep_redirect(tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action'))));
      break;
  }

  if ( ($_GET['action'] == 'edit') && tep_not_null($_GET['pID']) ) {
    $pID = tep_db_prepare_input($_GET['pID']);
    $payments_query = tep_db_query("select p.*,  a.affiliate_payment_check, a.affiliate_payment_paypal, a.affiliate_payment_bank_name, a.affiliate_payment_bank_branch_number, a.affiliate_payment_bank_swift_code, a.affiliate_payment_bank_account_name, a.affiliate_payment_bank_account_number from " .  TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE . " a where affiliate_payment_id = '" . tep_db_input($pID) . "' and a.affiliate_id = p.affiliate_id");
    $payments_exists = true;
    if (!$payments = tep_db_fetch_array($payments_query)) {
      $payments_exists = false;
      $messageStack->add(sprintf(ERROR_PAYMENT_DOES_NOT_EXIST, $pID), 'error');
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($_GET['action'] == 'edit') && ($payments_exists) ) {
    $affiliate_address['firstname'] = $payments['affiliate_firstname'];
    $affiliate_address['lastname'] = $payments['affiliate_lastname'];
    $affiliate_address['street_address'] = $payments['affiliate_street_address'];
    $affiliate_address['suburb'] = $payments['affiliate_suburb'];
    $affiliate_address['city'] = $payments['affiliate_city'];
    $affiliate_address['state'] = $payments['affiliate_state'];
    $affiliate_address['country'] = $payments['affiliate_country'];
    $affiliate_address['postcode'] = $payments['affiliate_postcode'];
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_AFFILIATE; ?></b></td>
                <td class="main"><?php echo tep_address_format($payments['affiliate_address_format_id'], $affiliate_address, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_AFFILIATE_PAYMENT; ?></b></td>
                <td class="main">&nbsp;<?php echo $currencies->format($payments['affiliate_payment_total']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_AFFILIATE_BILLED; ?></b></td>
                <td class="main">&nbsp;<?php echo tep_date_short($payments['affiliate_payment_date']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_AFFILIATE_PAYING_POSSIBILITIES; ?></b></td>
                <td class="main"><table border="1" cellspacing="0" cellpadding="5">
                  <tr>
<?php
  if (AFFILIATE_USE_BANK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER . '</b><br><br>' . TEXT_AFFILIATE_PAYMENT_BANK_NAME . ' ' . $payments['affiliate_payment_bank_name'] . '<br>' . TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER . ' ' . $payments['affiliate_payment_bank_branch_number'] . '<br>' . TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE . ' ' . $payments['affiliate_payment_bank_swift_code'] . '<br>' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME . ' ' . $payments['affiliate_payment_bank_account_name'] . '<br>' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER . ' ' . $payments['affiliate_payment_bank_account_number'] . '<br>'; ?></td>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_PAYPAL . '</b><br><br>' . TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL . '<br>' . $payments['affiliate_payment_paypal'] . '<br>'; ?></td>
<?php
  }
  if (AFFILIATE_USE_CHECK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_CHECK . '</b><br><br>' . TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE . '<br>' . $payments['affiliate_payment_check'] . '<br>'; ?></td>
<?php
  }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
<?php echo tep_draw_form('status', FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action')) . 'action=update_payment'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo PAYMENT_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $payments_statuses, $payments['affiliate_payment_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo PAYMENT_NOTIFY_AFFILIATE; ?></b><?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_NEW_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_OLD_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_AFFILIATE_NOTIFIED; ?></b></td>
          </tr>
<?php
    $affiliate_history_query = tep_db_query("select affiliate_new_value, affiliate_old_value, affiliate_date_added, affiliate_notified from " . TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY . " where affiliate_payment_id = '" . tep_db_input($pID) . "' order by affiliate_status_history_id desc");
    if (tep_db_num_rows($affiliate_history_query)) {
      while ($affiliate_history = tep_db_fetch_array($affiliate_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText">' . $payments_status_array[$affiliate_history['affiliate_new_value']] . '</td>' . "\n" .
             '            <td class="smallText">' . (tep_not_null($affiliate_history['affiliate_old_value']) ? $payments_status_array[$affiliate_history['affiliate_old_value']] : '&nbsp;') . '</td>' . "\n" .
             '            <td class="smallText" align="center">' . tep_date_short($affiliate_history['affiliate_date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($affiliate_history['affiliate_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
        }
        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="4">' . TEXT_NO_PAYMENT_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_INVOICE, 'pID=' . $_GET['pID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, 'pID=' . $pInfo->affiliate_payment_id. '&action=start_billing' ) . '">' . tep_image_button('button_affiliate_billing.gif', IMAGE_AFFILIATE_BILLING) . '</a>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_AFFILIATE_PAYMENT, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('sID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('status', FILENAME_AFFILIATE_PAYMENT, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PAYMENTS)), $payments_statuses), '', 'onChange="this.form.submit();"'); ?></td>
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFILIATE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NET_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_BILLED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($_GET['sID']) {
      // Search only payment_id by now
      $sID = tep_db_prepare_input($_GET['sID']);
      $payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s where p.affiliate_payment_id = '" . tep_db_input($sID) . "' and p.affiliate_payment_status = s.affiliate_payment_status_id and s.affiliate_language_id = '" . $languages_id . "' order by p.affiliate_payment_id DESC";
    } elseif (is_numeric($_GET['status'])) {
      $status = tep_db_prepare_input($_GET['status']);
      $payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s where s.affiliate_payment_status_id = '" . tep_db_input($status) . "' and p.affiliate_payment_status = s.affiliate_payment_status_id and s.affiliate_language_id = '" . $languages_id . "' order by p.affiliate_payment_id DESC";
    } else {
      $payments_query_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p , " . TABLE_AFFILIATE_PAYMENT_STATUS . " s where p.affiliate_payment_status = s.affiliate_payment_status_id and s.affiliate_language_id = '" . $languages_id . "' order by p.affiliate_payment_id DESC";
    }
    $payments_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $payments_query_raw, $payments_query_numrows);
    $payments_query = tep_db_query($payments_query_raw);
    while ($payments = tep_db_fetch_array($payments_query)) {
      if (((!$_GET['pID']) || ($_GET['pID'] == $payments['affiliate_payment_id'])) && (!$pInfo)) {
        $pInfo = new objectInfo($payments);
      }

      if ( (is_object($pInfo)) && ($payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $payments['affiliate_firstname'] . ' ' . $payments['affiliate_lastname']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['affiliate_payment'])); ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['affiliate_payment'] + $payments['affiliate_payment_tax'])); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($payments['affiliate_payment_date']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $payments['affiliate_payment_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($pInfo)) && ( $payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $payments_split->display_count($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?></td>
                    <td class="smallText" align="right"><?php echo $payments_split->display_links($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'pID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('payment', FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id. '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($pInfo)) {
        $heading[] = array('text' => '<b>[' . $pInfo->affiliate_payment_id . ']&nbsp;&nbsp;' . tep_datetime_short($pInfo->affiliate_payment_date) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id  . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_INVOICE, 'pID=' . $pInfo->affiliate_payment_id ) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> ');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td  width="25%" valign="top">' . "\n";

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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
