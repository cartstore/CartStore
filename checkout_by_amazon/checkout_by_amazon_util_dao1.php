<?php
/**
 * @brief Utiltity dao that retrieves country and zone objects from the database's corresponding tables.
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
require_once('includes/application_top.php');
#require_once('includes/classes/checkout_by_amazon_order.php');
require_once('manage/includes/classes/object_info.php');
require_once('checkout_by_amazon/checkout_by_amazon_includes.php');
require_once('checkout_by_amazon/library/callback/lib/amazon/config.php');
#require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
/**
 * Utiltity dao that retrieves country and zone objects from the database's 
 * corresponding tables. Also contains generic method to acquire and release locks.
 *
 */
class UtilDAO
{
  /**
   * Constructor
   */
  function UtilDAO()
  {
  }


  /**
   * Parses parameters in the url query format;
   * key1=value1&key2=value2
   *
   */
  function getParameterMap($data) {
    $parameterList = explode('&', $data);
    $parameters = array();

    for ($i = 0; $i < count($parameterList); $i++) {
        $parameter = explode('=', $parameterList[$i]);
        $parameters[$parameter[0]] = $parameter[1];
    }
    
    return $parameters;
  }


  /**
   * Get the country mapping row by country name.
   * The country object contains id, name, iso 2-letter code, iso 3-letter code 
   * and address_format_id.
   *
   */
  function getCountry($countryName) {
    // This query is copied from
    // admin/countries.php
    $countries_query = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . "   where countries_name = '" . tep_db_input($countryName) . "'");

    if (tep_db_num_rows($countries_query)) {
        $country = tep_db_fetch_array($countries_query);
        return $country;
    }

    return NULL;
  }
  
  /**
   * Get the country mapping row by iso 2-letter code.
   * The country object contains id, name, iso 2-letter code, iso 3-letter code 
   * and address_format_id.
   *
   * NOTE: This query is not indexed, but that should be okay since the country 
   * table is prety small (approx. 200 rows).
   */
  function getCountryByISOCode2($countryCode) {
    // This query is copied from
    // admin/countries.php
    $countries_query = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . "   where countries_iso_code_2 = '" . tep_db_input($countryCode) . "'");

    if (tep_db_num_rows($countries_query)) {
        $country = tep_db_fetch_array($countries_query);
        return $country;
    }

    return NULL;
  }

  /**
   * Get the country mapping row by primary key.
   * The country object contains id, name, iso 2-letter code, iso 3-letter code 
   * and address_format_id.
   */
  function getCountryByCountryId($countryId) {
    // This query is copied from
    // admin/countries.php
    $countries_query = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countryId . "'");
    
    if (tep_db_num_rows($countries_query)) {
        $country = tep_db_fetch_array($countries_query);
        return $country;
    }

    return NULL;
  }

  /**
   * Get the zone mapping row by country id and zone name.
   */
  function getZone($countryId, $zoneName) {
    // This query is copied from
    // address_book_process.php
    $zone_query = tep_db_query("select zone_id, zone_country_id, zone_code, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$countryId . "' and (zone_name = '" .   tep_db_input($zoneName) . "' or zone_code = '" . tep_db_input($zoneName) . "')");
    
    if (tep_db_num_rows($zone_query) == 1) {
        $zone = tep_db_fetch_array($zone_query);
        return $zone;
    }
    
    return NULL;
  }
  // Returns the country_id for a country
  // TABLES: countries
  function tep_get_country_id($country_code) {
        $country_query = tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where (countries_iso_code_2 = '" . $country_code . "' or countries_iso_code_3 = '" . $country_code . "') ;");
        $result = tep_db_fetch_array($country_query);
        return $result['countries_id'];
  }

  // Returns the zone_id for a ZONE
  // TABLES: zones
  function tep_get_zone_id($zone_name) {
        $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where (zone_name = '" . $zone_name . "' or zone_code = '" . $zone_name . "') ;");
        $result = tep_db_fetch_array($zone_query);
        return $result['zone_id'];
  }
  // Returns the entry company given customers_id
  // TABLES: zones
  function tep_get_entry_company($customers_id) {
        $company_query = tep_db_query("select entry_company from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customers_id . "' ;");
        $result = tep_db_fetch_array($company_query);
        return $result['entry_company'];
  }

  // Returns the products_tax_class_id for a product
  // TABLES: products
  function tep_get_tax_class_id($products_id) {
        $tax_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "' ;");
        $result = tep_db_fetch_array($tax_query);
        return $result['products_tax_class_id'];
  }

  // Returns the configuration value based on key
  // TABLES: configuration
  function tep_get_configuration_value($configuration_key) {
        $configuration_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key =  '" . $configuration_key . "';");
        $result = tep_db_fetch_array($configuration_query);
        return $result['configuration_value'];
  }

  // Returns the state code value based on state
  // TABLES: zones
  function tep_get_state_code($state_value) {
        $state_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where (zone_code =  '" . $state_value . "' or zone_name = '" . $state_value . "' );");
        $result = tep_db_fetch_array($state_query);
        return $result['zone_id'];
  }


  /**
	Funtion to enable 1-click and express checkout and also display 
        the CBA button. This method is being used by login.php and 
	shopping_cart.php to display the CBA button. 
  */
  function printButton() {
       global $cart, $language;
       require_once("checkout_by_amazon/checkout_by_amazon_constants.php");
    ?>
    <!--BEGINING CHECKOUT BY AMAZON SCRIPTS, 1click & express checkout  -->
    <script src=<?php echo(CBA_JQUERY_SETUP); ?> type="text/javascript"></script>

    <script src=<?php if(MODULE_PAYMENT_CHECKOUTBYAMAZON_OPERATING_ENVIRONMENT == 'Production'){echo(PROD_1_CLICK);} else {echo(SANDBOX_1_CLICK);}   ?> type="text/javascript"></script>

   <link href=<?php echo(CBA_STYLE_SHEET); ?> media="screen" rel="stylesheet" type="text/css"/>

   <?php
    // add Checkout by Amazon button to page if module is enabled
    if (defined('MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS') && MODULE_PAYMENT_CHECKOUTBYAMAZON_STATUS == 'True') {
      echo "</form>\n";
       include_once('checkout_by_amazon/checkout_by_amazon_main.php');
    }
  }

  function echoMessage($message, $isDebug = false) {
    writelog(date('c', time()) . ' [DEBUG] ' . $message);

    if ($isDebug) {
        echo date('c', time()) . ' [DEBUG] ' . $message;
    }
  }
  
  function varDump($message, $isDebug = false) {
     ob_start();
     var_dump($message);
     $dump = ob_get_contents();
     ob_end_clean();

     $this->echoMessage($dump, $isDebug);
  }

  /**
   * Acquire the lock or check if a previous lock expired before acquiring the 
   * lock.
   *
   * PHP 5 has a problem with using files
   * as locks, since it does not clear the stat cache properly.
   *
   * If a file is created, the stat cache will still mark the file as not 
   * created, even with an explicit call to fclose. The stat cache is only 
   * cleared when the PHP process is closed - but this is not a usable mechanism 
   * in this case.
   *
   * As a workaround, a database object is used as a lock instead.
   * This will be removed if/when PHP fixes there stat cache issue.
   */
  function acquireLock($lockKey, $lockTimeout, $isDebug) {
    // See if the lock exists by querying the database:
    // Lock Key can be: MODULE_PAYMENT_CHECKOUTBYAMAZON_GET_ORDER_LOCK_STATUS
    //                  MODULE_PAYMENT_CHECKOUTBYAMAZON_MONITOR_ORDER_LOCK_STATUS
    $query_string = "select UNIX_TIMESTAMP(), lock_value from " . TABLE_AMAZON_ORDERS_LOCK .
        " where lock_key = '" . tep_db_input($lockKey) . "'";
    $lock_query = tep_db_query($query_string);
    $rows = tep_db_num_rows($lock_query);
    $result_set = $rows > 0 ? tep_db_fetch_array($lock_query) : NULL;

    $lockExists = $rows > 0;
    $currentTime = $lockExists ? ($result_set['UNIX_TIMESTAMP()']) : NULL;
    $lastModifiedTime = $lockExists ? ($result_set['lock_value']) : NULL;

    if ($lockExists == false) {
      $this->echoMessage("Acquiring lock on lock key: " . $lockKey . "<br/>", $isDebug);
      $result = $this->createOrUpdateLock($lockKey);
      
      return $result;
    }
    else {
      if ($currentTime - $lockTimeout > $lastModifiedTime) {
	$this->echoMessage("Lock expired: Lock time: " . date('c', $lastModifiedTime) . ", " . $lastModifiedTime . " Current time: " . date('c', $currentTime) . ", " . $currentTime . "<br/>", $isDebug);
	$this->echoMessage(
            "Acquiring lock on lock key: " . $lockKey . "<br/>", $isDebug);
	return $this->createOrUpdateLock($lockKey);
      }
      else {
	$this->echoMessage(
            "Lock not yet expired: Lock time: " . date('c', $lastModifiedTime) . " Current time: " . date('c', $currentTime) . "<br/>", $isDebug);
	return false;
      }
    }
    
    return false;
  }

  /**
   * Releases the lock key by deleting it.
   */
  function releaseLock($lockKey, $isDebug) {

    $this->echoMessage("Releasing lock on lock key: " . $lockKey . "<br/>", $isDebug);

    $query = "delete from ".TABLE_AMAZON_ORDERS_LOCK." where lock_key = '" . tep_db_input($lockKey) . "'";
    
    $result = tep_db_query($query);
    //$this->echoMessage("Released lock using lock key: " . $lockKey . " result: . " . $result . "<br/>");

    return $result == 1;
  }

  /**
   *
   */
  function createOrUpdateLock($lockKey) {
      // first release all old locks
      $this->releaseLock($lockKey, false);

      $query = "replace into ".TABLE_AMAZON_ORDERS_LOCK." (lock_key, lock_value) values ('" . tep_db_input($lockKey) . "', UNIX_TIMESTAMP())";
      $result = tep_db_query($query);
      //$this->echoMessage("Created lock using lock key: " . $lockKey . " result: . " . $result . "<br/>");

      return $result == 1;
  }

// IOPN related methods

function persistIOPN($NotificationReferenceId, $amazonOrderID, $content) {
        $isExist = $this->isDuplicateIOPN($NotificationReferenceId, $amazonOrderID);
        if(!$isExist && $NotificationReferenceId != null) {
                tep_db_query("insert into amazon_iopn  (notificationReferenceID, order_id, notification_txt, created_on)   values ('" . $NotificationReferenceId . "', '" . $amazonOrderID . "', '" . tep_db_input($content) . "', now())");
        }
}

function isDuplicateIOPN($NotificationReferenceId, $amazonOrderID) {
        $iopn_query = tep_db_query("select notificationReferenceID from amazon_iopn where notificationReferenceID = '" . $NotificationReferenceId . "' and order_id = '" . $amazonOrderID . "'");
        if (tep_db_num_rows($iopn_query) > 0)
                return true;
        return false;
}

function existIOPN($amazonOrderID) {
        $iopn_query = tep_db_query("select notificationReferenceID from amazon_iopn where order_id = '" . $amazonOrderID . "'");
        if (tep_db_num_rows($iopn_query) > 0)
                return true;
        return false;
}

function updateStatus($orders_status, $orders_id) {
        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($orders_status) . "', last_modified = now() where orders_id = '" . (int)$orders_id . "'");

}

 function  updateInventory($request, $processor, $order_id) {
        $processor->setProductData($order, $request);
        $processor->updateCancelledOrder($order, $order_id);
  }

}


// Returns end user friendly values for standard shipping override.
// TABLES: configuration
function tep_std_ovr_use_function() {
  $std_ship_ovr = 'None';
  if (MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE == 'flat')
    $std_ship_ovr = 'Flat Rate';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE == 'item')
    $std_ship_ovr = 'Per Item';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE == 'table')
    $std_ship_ovr = 'Table Rate';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_STANDARD_OVERRIDE == 'zones')
    $std_ship_ovr = 'Zone Rates';

  return $std_ship_ovr;
}

function tep_shipping_carrier_use_function() {
  $shipping_carrier = 'None';
  if (MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER == 'USPS')
    $shipping_carrier = 'USPS';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER == 'UPSXML')
    $shipping_carrier = 'UPS XML';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER == 'UPS')
    $shipping_carrier = 'UPS Choice';
  else if (MODULE_PAYMENT_CHECKOUTBYAMAZON_SHIPPING_CARRIER == 'FedEx1')
    $shipping_carrier = 'Fedex';
  return $shipping_carrier;
}
/* Logs the POST request */
function LogRequest(){
  if($_POST){
    foreach ($_POST as $k => $v) {
      $logContent .= "$k = ".str_replace("\\\"","\"",$v)."\n";
    }
    writelog($logContent);
  }
}


?>
