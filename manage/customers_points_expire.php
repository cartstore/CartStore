<?php
/*
  $Id: customers_points_expire.php, v 2.00 2006/JULY/07 11:05:12 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  include_once('includes/application_top.php');
  
if ((USE_POINTS_SYSTEM == 'true') && tep_not_null(POINTS_AUTO_EXPIRES)){
  tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = NULL, customers_points_expires = NULL WHERE customers_points_expires < CURDATE()");

  if (tep_not_null(POINTS_EXPIRES_REMIND)){
  
    include_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CUSTOMERS_POINTS_PENDING);

    $customer_query = tep_db_query("SELECT customers_gender, customers_lastname, customers_firstname, customers_email_address, customers_shopping_points, customers_points_expires FROM " . TABLE_CUSTOMERS . " WHERE (CURDATE() + '". (int)POINTS_EXPIRES_REMIND ."') = customers_points_expires");
    while($customer = tep_db_fetch_array($customer_query)){
    $customers_email_address = $customer['customers_email_address'];
    $gender = $customer['customers_gender'];
    $first_name = $customer['customers_firstname'];
    $last_name = $customer['customers_lastname'];
    $name = $first_name . ' ' . $last_name;

    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $greet = sprintf(EMAIL_GREET_MR, $last_name);
      } else {
        $greet = sprintf(EMAIL_GREET_MS, $last_name);
      }
    } else {

    $greet = sprintf(EMAIL_GREET_NONE, $first_name);
    }
    $can_use = "\n\n" . EMAIL_TEXT_SUCCESS_POINTS;

    $email_text = $greet  . "\n" . EMAIL_EXPIRE_INTRO . "\n" . sprintf(EMAIL_EXPIRE_DET, number_format($customer['customers_shopping_points'],POINTS_DECIMAL_PLACES), tep_date_short($customer['customers_points_expires'])) . "\n" . EMAIL_EXPIRE_TEXT . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS, '', 'SSL')) . "\n\n" . sprintf(EMAIL_TEXT_POINTS_URL_HELP, tep_catalog_href_link(FILENAME_CATALOG_MY_POINTS_HELP, '', 'NONSSL')) . $can_use . "\n" . EMAIL_CONTACT . "\n" . EMAIL_SEPARATOR . "\n" . '<b>' . STORE_NAME . '</b>.' . "\n";

    tep_mail($name, $customer['customers_email_address'], EMAIL_EXPIRE_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
  }
}
