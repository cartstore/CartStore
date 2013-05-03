<?php
/*
  $Id: database.php,v 1.21 2003/06/09 21:21:59 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

/*
/////////////Joomla Db Connection Will Go Here/////////////////
error_reporting(E_ALL && ~E_NOTICE);
include("../configuration.php");
$hd = mysql_connect('localhost',$mosConfig_user, $mosConfig_password)
or die ("Unable to connect");
mysql_select_db ($mosConfig_db, $hd) or die ("Unable to select database");
//$link=dbLink();
$hd = mysql_connect('localhost',$mosConfig_user, $mosConfig_password)
or die ("Unable to connect");

/////////////////Drop Down wiht links start from here//////////////////

$query_lnks = "SELECT * FROM jos_weblinks WHERE approved=1 ORDER BY date DESC ";
$toplinks = mysql_query($query_lnks);
$drpdown ='


<SCRIPT LANGUAGE="JavaScript">
<!--
function gotourl()
{
document.location.href=document.gmform.jgm_lnk.value;
}
//-->
</SCRIPT>

<form name="gmform" action="">
<select name="jgm_lnk" onchange="return gotourl();">
<option value="" > --Select-- </option>';
while($row = mysql_fetch_array($toplinks))
{
$drpdown .='<option value="'.$row["url"].'">'.$row["title"].'</option>';
}
$drpdown .='</select></form>'; 
define('DRPDOWN', $drpdown);
/////////////////Drop Down wiht links start from here//////////////////
mysql_close ($hd);
/////////////Joomla Db Connection Will End Here/////////////////

*/
define('DRPDOWN', "");

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;
//    echo "<br>$query<br><br>";
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

//    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
    // limex: mod query performance START
    list($usec, $sec) = explode(" ", microtime());
    $start = (float)$usec + (float)$sec; 

    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
    list($usec, $sec) = explode(" ", microtime());

    $end = (float)$usec + (float)$sec; 
    $parsetime = $end - $start;
    $qlocation = $_SERVER["SCRIPT_FILENAME"];
    // limex: some queries come before having the config values. Default to 10 secs
    $mysql_perf_treshold = defined('MYSQL_PERFORMANCE_TRESHOLD') ? MYSQL_PERFORMANCE_TRESHOLD : 10 ; 
    if ($parsetime > $mysql_perf_treshold) { 
        $log_file=DIR_FS_CATALOG.'slow_queries/slow_query_log.txt';
        $slow_when=date('F j, Y, g:i a',time());
        $slow_query=tep_db_input($query)."\t".$qlocation."\t".$parsetime."\t".$slow_when."\r\n";
        $slow_log = fopen($log_file, 'a');
        fwrite($slow_log, $slow_query);
        fclose($slow_log);
    }
    // limex: mod query performance END

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

// DO NOT TOUCH _ BUG FIX

  function tep_db_insert_id($link = 'db_link') {
global $$link;
return mysql_insert_id( $$link );
}

// END DO NOT TOUCH BUG FIX

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

 //Family products: Begin Changed code
 ///
// Returns an array with family names for the Family Products v. 3.4 module.
// TABLES: families

function tep_get_family_names($family_names_array = '') {
if (!is_array($family_names_array)) $family_names_array = array();

$family_names_query = tep_db_query("select family_id, family_name from " . TABLE_FAMILIES . " order by family_id ASC");
while ($family_names = tep_db_fetch_array($orientation_query)) {
$family_names_array[] = array('id' => $family_names['family_id'], 'text' => $family_names['family_name']);
}

return $family_names_array;
}
//Family: End Changed code



// BOF Separate Pricing Per Customer, adapted from sample code in user comments on
  // http://www.php.net/manual/en/function.mysql-list-tables.php
  // Wrap DB_DATABASE with Back Ticks, Fixes Hyphens in Database Name, code from
  // Jef Stumpf/Krumbsnatcher: http://forums.CartStore.com/index.php?showtopic=53436&view=findpost&p=563454
  function tep_db_table_exists($table, $link = 'db_link') {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  while ($list_tables = tep_db_fetch_array($result)) {
	  if ($list_tables['Name'] == $table) {
		  return true;
	  }
	  }
	  return false;
  }

  function tep_db_check_age_specials_retail_table() {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  $last_update_table_specials = "2000-01-01 12:00:00";
	  $table_srp_exists = false;
	  while ($list_tables = tep_db_fetch_array($result)) {
	  if ($list_tables['Name'] == TABLE_SPECIALS_RETAIL_PRICES) {
	  $table_srp_exists = true;
	  $last_update_table_srp = $list_tables['Update_time'];
	  }
	  if ($list_tables['Name'] == TABLE_SPECIALS) {
	  $last_update_table_specials = $list_tables['Update_time'];
	  }
	  } // end while

	  if(!$table_srp_exists || ($last_update_table_specials > $last_update_table_srp)) {
	     if ($table_srp_exists) {
		     $query1 = "truncate " . TABLE_SPECIALS_RETAIL_PRICES . "";
		     if (tep_db_query($query1)) {
		 $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
		 $result =  tep_db_query($query2);
		 }
	     } else { // table specials_retail_prices does not exist
		     $query1 = "create table " . TABLE_SPECIALS_RETAIL_PRICES . " (products_id int NOT NULL default '0', specials_new_products_price decimal(15,4) NOT NULL default '0.0000', status tinyint, customers_group_id smallint, primary key (products_id) )" ;
		     $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
		     if( tep_db_query($query1) && tep_db_query($query2) ) {
			; // execution succesfull
		    }
	     } // end else
	  } // end if(!$table_srp_exists || ($last_update_table_specials....
  }

  function tep_db_check_age_products_group_prices_cg_table($customer_group_id) {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  $last_update_table_pgp = strtotime('2000-01-01 12:00:00');
	  $table_pgp_exists = false;
	  while ($list_tables = tep_db_fetch_array($result)) {
	  if ($list_tables['Name'] == TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id) {
	  $table_pgp_exists = true;
	  $last_update_table_pgp = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_SPECIALS ) {
	  $last_update_table_specials = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_PRODUCTS ) {
	  $last_update_table_products = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_PRODUCTS_GROUPS ) {
	  $last_update_table_products_groups = strtotime($list_tables['Update_time']);
	  }
	  } // end while

   if ($table_pgp_exists == false) {
      $create_table_sql = "create table " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . " (products_id int NOT NULL default '0', products_price decimal(15,4) NOT NULL default '0.0000', specials_new_products_price decimal(15,4) default NULL, status tinyint, primary key (products_id) )" ;
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($create_table_sql) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
	       return true;
              }
   } // end if ($table_pgp_exists == false)

   if ( ($last_update_table_pgp < $last_update_table_products && (time() - $last_update_table_products > (int)MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE * 60) ) || $last_update_table_specials > $last_update_table_pgp || $last_update_table_products_groups > $last_update_table_pgp ) { // then the table should be updated
      $empty_query = "truncate " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . "";
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($empty_query) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
	       return true;
              }
   } else { // no need to update
	   return true;
   } // end checking for update

  }

  // EOF Separate Pricing Per Customer



?>