<?php

function count_events($day,$month,$year) {

	$sql = 'SELECT * FROM calendar_event WHERE day = "'.$day.'" AND month = "'.$month.'" AND year = "'.$year.'"';

	$query = mysql_query($sql);
	
	$num_rows = mysql_num_rows($query);
	
	if($num_rows > 0) { 
	
	if($num_rows > 1) { $event = "Events"; } else { $event = "Event"; }
	$counted = $num_rows . " " . $event;
	
	}

	
	return $counted;
		
}
function list_events($day,$month,$year) {

	$sql = 'SELECT * FROM calendar_event WHERE day = "'.$day.'" AND month = "'.$month.'" AND year = "'.$year.'" ORDER BY time_from';

	$query = mysql_query($sql);
	
	$num_rows = mysql_num_rows($query);
	
	echo "<div class='list'>";
	
	if($num_rows == 0) { 
		
		echo "No events"; 
		
		} else {
		
		echo "<div id='event_row_last'><b>";
		if($num_rows > 1) { echo "There are currently $num_rows events scheduled."; } else { echo "There is currently $num_rows event scheduled."; }
		echo "</b></div>";
		
		while($row = mysql_fetch_array($query)) {
		
		echo "<div id='event_row'>";
		
			echo "<h2>" . $row['event'] . "</h2>";
			echo "<p class='meta'>" . $row['location'] . " from " . $row['time_from'] . " to " . $row['time_until'] . "</p>";
			echo "<p>" . $row['description'] . "</p>";
		echo "</div>";
		
		}
	
	}
		
	echo "</div>"; 
}

?>