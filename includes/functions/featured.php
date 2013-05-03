<?php
/*
  $Id: featured.php,v 2.5 2003/10/28 21:00:00 cieto Exp $

  cieto Featured Products 2.51 2.0 expire function
  cieto@msn.com

Made for:
  CartStore eCommerce Software, for The Next Generation 
  http://www.cartstore.com 
  Copyright (c) 2002 CartStore 
  GNU General Public License Compatible 
*/

////
// Sets the status of a featured product
  function tep_set_featured_status($products_id, $featured) {
    return tep_db_query("update " . TABLE_PRODUCTS . " set products_featured = '" . $featured . "', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
  }

////
// Auto expire products on special
  function tep_expire_featured() {
    $featured_query = tep_db_query("SELECT products_id from " . TABLE_PRODUCTS . " where products_featured = '1'");
    if (tep_db_num_rows($featured_query)) {
      while ($featured = tep_db_fetch_array($featured_query)) {
        tep_set_featured_status($featured['products_id'], '0');
      }
    }
  }
?>