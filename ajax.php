<?php
require_once("includes/configure.php");
require_once("includes/database_tables.php");
define('ENTRY_EMAIL_ADDRESS_CREATE_EXISTS', 'is already taken, please enter a different address.');

mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
mysql_select_db(DB_DATABASE);

if (isset($_REQUEST['check-email'])){
          $check_email_query = mysql_query("select customers_id as id, customers_paypal_ec as ec from " . TABLE_CUSTOMERS . " where customers_email_address = '" . mysql_real_escape_string($_REQUEST['email_address']) . "'");
	echo mysql_error();
          if (mysql_num_rows($check_email_query) > 0) {
              $check_email = mysql_fetch_array($check_email_query);
              if ($check_email['ec'] == '1') {
                  mysql_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_email['id'] . "'");
                  mysql_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$check_email['id'] . "'");
                  mysql_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$check_email['id'] . "'");
                  mysql_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$check_email['id'] . "'");
                  mysql_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$check_email['id'] . "'");
                  mysql_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$check_email['id'] . "'");
				  print "true";
				  exit();
              } else {
                  print json_encode($_REQUEST['email_address'] . " " . ENTRY_EMAIL_ADDRESS_CREATE_EXISTS);
				  exit();
              }
          }
	print "true";
	exit();
}
