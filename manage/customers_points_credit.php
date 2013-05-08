<?php
/*
  $Id: customers_points_credit.php, v 2.00 2006/JULY/07 11:03:40 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  include_once('includes/application_top.php');
  
  if ((USE_POINTS_SYSTEM == 'true') && (POINTS_AUTO_ON > 0)){
    $auto_credit_query = "SELECT unique_id, customer_id, orders_id, date_added, points_pending, points_type FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE date_added <= (CURDATE() - '" . (int)POINTS_AUTO_ON . "') AND points_status = 1 ORDER BY customer_id";
    $credit_rows = tep_db_query($auto_credit_query);
    
    echo '<p style="font-family: Tahoma, Arial, sans-serif; font-size: 12px;"><b>Points confirmed for the following rows...</b><br><br>For your convenience here is the cron command for your site:<br><br>php&nbsp; ' . $_SERVER["PATH_TRANSLATED"] . '<form><input name="print" type="button" value="Print this" onclick="window.print()"></form></p>';
      
    while($auto_credit = tep_db_fetch_array($credit_rows)){
	    
      if (tep_not_null(POINTS_AUTO_EXPIRES)){
        tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $auto_credit['points_pending'] . "', customers_points_expires = DATE_ADD(NOW(),INTERVAL '" . POINTS_AUTO_EXPIRES . "' MONTH) WHERE customers_id = '" . (int)$auto_credit['customer_id'] . "'");
      } else {
        tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '" . $auto_credit['points_pending'] . "' WHERE customers_id = '" . (int)$auto_credit['customer_id'] . "'");
      }

      tep_db_query("UPDATE " . TABLE_CUSTOMERS_POINTS_PENDING . " SET points_status = 2 WHERE unique_id = '". (int)$auto_credit['unique_id'] ."'");

      print $total_points_awarded = '<li style="font-family: Tahoma, Arial, sans-serif; font-size: 12px;">Customer id :' . (int)$auto_credit['customer_id'] .'&nbsp;&nbsp;Order id :' . (int)$auto_credit['orders_id'] .'&nbsp;&nbsp;Date :' . tep_date_short($auto_credit['date_added']) .'&nbsp;&nbsp;Total Points :' . number_format($auto_credit['points_pending'],POINTS_DECIMAL_PLACES) .'&nbsp;&nbsp;Points Type =' . $auto_credit['points_type'] .'</li>';
      $total_points_mail = $total_points_mail .= 'Customer id :' . (int)$auto_credit['customer_id'] .' Order id :' . (int)$auto_credit['orders_id'] .' Date :' . tep_date_short($auto_credit['date_added']) .' Total Points :' . number_format($auto_credit['points_pending'],POINTS_DECIMAL_PLACES) .' Points Type =' . $auto_credit['points_type']. "\n";
  
    }
    $points_subject = 'Points Auto confirmed.';
    $points_email = '<b>Points confirmed for the following rows...</b>'. "\n\n" . $total_points_mail;
    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $points_subject, $points_email, STORE_OWNER,   STORE_OWNER_EMAIL_ADDRESS);
  }
