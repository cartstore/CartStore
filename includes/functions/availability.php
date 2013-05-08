<?php
////
// Return day of week
  function tep_get_dayid($weekday) {
        $_query = tep_db_query("select * from sw_week_days where day='".$weekday."'");
    	$_res = tep_db_fetch_array($_query);
    return $_res['dayid'];
  }


////
// Returns Delivery time slots
  function tep_get_time_slots() {
    	$slot_query = tep_db_query("select * from sw_time_slots where 1");
    return $slot_query;
  
  }
 //Returns total count by date & time slot 
  function tep_get_total_count($date,$slotid) {
    $tot_query = tep_db_query("select Count(*) as num from orders where delivery_date='$date' and delivery_time_slotid=$slotid");
    $tot_res = tep_db_fetch_array($tot_query);
  return $tot_res['num'];
  
  }
  //Returns max_limit & cost if special time exists for a particular date & slot
  function tep_get_special_time($date,$slotid) {
    $special_time_query = tep_db_query("select * from sw_emargengency_delivery_time where delv_date ='$date' and slotid=$slotid");
    $special_time_res = tep_db_fetch_array($special_time_query);
	if(tep_db_num_rows($special_time_query)>0)
	  return $special_time_res;
	else
	   return 0;  
	}
  //Returns max_limit & cost from default table
   
   function tep_get_default_time($dayid,$slotid) {
    $default_time_query = tep_db_query("select * from sw_default_delivery_time where dayid ='$dayid' and slotid=$slotid");
    $default_time_res = tep_db_fetch_array($default_time_query);
	  return $default_time_res;
	}

?>