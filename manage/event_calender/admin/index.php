<?php

session_start();
include 'includes/configure.php';

//include('check.php');
include('dbconn.php');
include('header.php');


	$day = date('d');
	$month = date('m');
	$year = date('Y');
	
	echo "<div class='success_message'>Welcome!</b></div>";
	
	echo "<span class='add'><a href='cal_add.php' title='Add Event'>Add Event</a></span>";
	echo "<span class='edit'><a href='cal_edit.php' title='Edit Event'>Edit Event</a></span>";
	
	echo "<div id='upcoming'>";
	echo "<h3>Upcoming Events ".date('F', mktime(0,0,0,$month,1))." '".date('y')."</h3>";
	
	$sql = "SELECT * FROM calendar_event WHERE day >= '$day' AND month = '$month' AND year = '$year' LIMIT 0,10"; 
	$result = mysql_query($sql);
	
	$count = mysql_num_rows($result);
	
	if($count == 0) { echo "<span class='error_message'>There are no further upcoming events this month.</span>"; }
		
	echo "<ul class='upcoming-events'>";
			
	while($row = mysql_fetch_array($result)) { 
	
	echo "<li><a href='cal_edit.php?evid=".$row['id']."'>".$row['event'];
	echo "&nbsp;-&nbsp;<span class='upcoming-date'>".$row['day']."/".$row['month']."/".$row['year']."</span></a></li>";
	
	}
	
	echo "</ul>";
	
	echo "</div>";



include('footer.php');

?>