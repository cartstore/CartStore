<?php
/*
  $Id: quickfind.php,v 1.10 2005/08/04 23:25:46 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

*/

require('includes/application_top.php');
$results = array();
$q = '';
$name = '';
$id = '';
$url = '';
$osCsid = '';

$q = addslashes(preg_replace("%[^0-9a-zA-Z ]%", "", $_GET['keywords']) );
$osCsid = addslashes(preg_replace("%[^0-9a-zA-Z ]%", "", $_GET[tep_session_name()]) );

$limit = 10;

  if ( isset($q) && tep_not_null($q) ) {
   $query = tep_db_query("select pd.products_id, pd.products_name, p.products_model from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on (p.products_id = pd.products_id) where (pd.products_name like '%" . tep_db_input($q) . "%' or p.products_model like '%" . tep_db_input($q) . "%') and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name asc limit " . $limit);

    if ( tep_db_num_rows($query) ) {
      while ( $row = tep_db_fetch_array($query) ) {
	  
	    if ( isset($row['products_model']) && tep_not_null($row['products_model']) ) {
		  $model = ' [' . $row['products_model'] . ']';
		} else {
		  $model = '';
		}
	  
        $name = $row['products_name'];
        $id = $row['products_id'];
        $url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $id);
        $results[] = '<a href="' .  $url . '">' .  $name . '</a>' . $model . "\n";
	    // $results[] = '<a href="' . $url . '">' . $name . $model . '</a>' .  "\n";
      }
    } else {
      $results [] = 'No Quick Find Results';
    }
    echo implode('<br>' . "\n", $results);
    // To use <DOCTYPE> XHTML 1.0 or higher
	// echo implode('<br />' . "\n", $results); 
  } else {
    echo "Quick Find Results ...";
  }

?>
