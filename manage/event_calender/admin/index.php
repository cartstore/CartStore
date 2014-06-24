<?php

session_start();
include 'includes/configure.php';

//include('check.php');
include('dbconn.php');
include('header.php');


	$day = date('d');
	$month = date('m');
	$year = date('Y');
	
	echo "<h4>Welcome!</h4>";
	
	echo "<a class=\"btn btn-default\" href='cal_add.php' title='Add Event'>Add Event</a>";
	echo "<a class=\"btn btn-defailt\" href='cal_edit.php' title='Edit Event'>Edit Event</a><hr>";
	
	echo "<div id='upcoming'>";
	echo "<h3>Upcoming Events ".date('F', mktime(0,0,0,$month,1))." '".date('y')."</h3>";
	
	$sql = "SELECT * FROM calendar_event WHERE day >= '$day' AND month = '$month' AND year = '$year' LIMIT 0,10"; 
	$result = tep_db_query($sql);
	$count = tep_db_num_rows($result);
	
	if($count == 0) { echo "<div class=\"alert alert-info\">There are no further upcoming events this month.</div>"; }
		
	echo "<ul class='upcoming-events'>";
			
	while($row = tep_db_fetch_array($result)) { 
	
	echo "<li><a href='cal_edit.php?evid=".$row['id']."'>".$row['event'];
	echo "&nbsp;-&nbsp;<span class='upcoming-date'>".$row['day']."/".$row['month']."/".$row['year']."</span></a></li>";
	
	}
	
	echo "</ul>";
	
	echo "</div>";



include('footer.php');

?>