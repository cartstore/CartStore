<?php
/**
 * @brief customer table data access object.
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Joshua Wong
 * @copyright 2008-2009 Amazon Technologies, Inc
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 *
 */
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
require_once('manage/includes/classes/object_info.php');

/**
 * Handles database persistance of customer and address_book tables.
 * Required for persisting an order.
 */
class AccountDAO
{
  /**
   * Constructor
   */
  function AccountDAO()
  {
  }


  /**
   * Get customer object by email address.
   * This query is indexed.
   */
  function getAccount($emailAddress) {
    // This query is copied directly from
    // account_edit.php, line 116
    $customer_query = tep_db_query("select customers_id, customers_gender, customers_firstname, customers_lastname," .
              " customers_email_address, customers_telephone, customers_password from " . TABLE_CUSTOMERS .
				   " where customers_email_address = '" .   tep_db_input($emailAddress) . "'");

    $customer = tep_db_fetch_array($customer_query);
    return $customer;
  }

  /**
   * Create customer account.
   *
   *
   *
   */
  function createAccount($firstname, $lastname, $email_address, $password, $telephone) {
    $sql_data_array = array('customers_firstname' => $firstname,
			    'customers_lastname' => $lastname,
			    'customers_email_address' => $email_address,
			    'customers_newsletter' => 0,
                            'customers_password' => tep_encrypt_password($password),
                            'customers_telephone' => $telephone);
    
    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
    $customer_id = tep_db_insert_id();

    // some statistics
    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created)   values ('" . (int)$customer_id . "', '0', now())");

    // mimic what is returned from the database
    $sql_data_array['customers_id'] = $customer_id;
    return $sql_data_array;
  }

  /**
   * Get addresses for a customer by primary key.
   *
   * Used in conjunction with getAddressBook.
   */
  function getAddress($addressId) {
    // This query is copied directly from
    // address_book.php, line 114
    $address_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company,   entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as   zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$addressId . "' order by firstname, lastname");

    $address = tep_db_fetch_array($address_query);
    return $address;
  }

  /**
   * Get all available addresses for a customer
   *
   */
  function getAddressBook($customerId) {
    // This query is copied directly from
    // address_book.php, line 114
    $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company,   entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as   zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customerId . "' order by firstname, lastname");
    $addresses = array();

    while ($address = tep_db_fetch_array($addresses_query)) {
        array_push($addresses, $address);
    }

    return $addresses;
  }

  /**
   * Create customer address.
   * Also, sets the new address as the customer's default address.
   *
   */
  function createAddress($customer_id, $firstname, $lastname, $street_address, $postcode, $city, $zoneId, $state, $countryId) {
    $sql_data_array = array('customers_id' => $customer_id,
			    'entry_firstname' => $firstname,
			    'entry_lastname' => $lastname,
			    'entry_street_address' => $street_address,
			    'entry_postcode' => $postcode,
			    'entry_city' => $city,
			    'entry_country_id' => $countryId);

    if (ACCOUNT_STATE == 'true') {
      if ($zoneId > 0) {
	$sql_data_array['entry_zone_id'] = $zoneId;
	$sql_data_array['entry_state'] = NULL;
      } else {
	$sql_data_array['entry_zone_id'] = '0';
	$sql_data_array['entry_state'] = $state;
      }
    }

    //echo "Created address: ";
    //var_dump($sql_data_array);

    $result = tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    if ($result) {
        $address_id = tep_db_insert_id();

        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" .   (int)$customer_id . "'");

        return $this->getAddress($address_id);
    }

    return NULL;
  }
}
?>
