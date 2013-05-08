<?php
include 'includes/configure.php';
include('admin/dbconn.php');
include('functions/functions.php');

include('header.php');

$type = CAL_GREGORIAN;

$month = $_GET['month'];
if(!$_GET['month']) { $month = date('n'); } // Month ID, 1 through to 12.

$year = $_GET['year'];
if(!$_GET['month']) { $year = date('Y'); } // Year in 4 digit 2009 format.

$today = date('Y/n/d');

$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days in the chosen month to give to our function.

echo "<div id='calendar'>";


// Function for year change. //

$last_month = $month - 1;
$next_month = $month + 1;

$last_year = $year - 1;
$next_year = $year + 1;

	if($month == 12) {
	$change_year = $year;
	$change_month  = $last_month;
	} elseif($month == 1) {
	$change_year = $last_year;
	$change_month  = '12';
	} else {
	$change_year = $year;
	$change_month  = $last_month;
	}

	if($month == 1) {
	$change_year_next = $year;
	$change_month_next  = $next_month;
	} elseif($month == 12) {
	$change_year_next = $next_year;
	$change_month_next  = '1';
	} else {
	$change_year_next = $year;
	$change_month_next  = $next_month;
	}

// Do NOT edit the above. //

		echo "<div class='title_bar'>";

		echo "<a href='calendar.php?month=". $change_month ."&year=". $change_year ."'><div class='previous'></div></a>";
		echo "<a href='calendar.php?month=". $change_month_next ."&year=". $change_year_next ."'><div class='next'></div></a>";
		echo "<h3>" . date('F',  mktime(0,0,0,$month,1)) . "&nbsp;" . $year . "</h3>";


		echo "</div>";
echo "<div id='calendar_wrap'>";
for($i=1; $i<= $day_count; $i++) { // Start of for $i

	$date = $year.'/'.$month.'/'.$i;

	$get_name = date('l', strtotime($date));
	$month_name = date('F', strtotime($date));
	$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

	$count = count_events($i,$month,$year);

	echo "<a href='templates/system/event_calender/day_view.php?day=$i&month=$month&year=$year' title='$i $month_name' rel='day_view'>";
	echo "<div class='cal_day'>"; // Calendar Day

		echo "<div class='day_heading'>" . $day_name . "</div>";

		if($count >= 1) { echo "<div class='day_count'><span class='event'>" . $count . "</span></div>"; }

		if($today == $date) {
			echo "<div class='day_number today'>" . $i . "</div>";
		} else {
			echo "<div class='day_number'>" . $i . "</div>";
		}

	echo "</div>";
	echo "</a>";

} // EOF for $i

	echo "</div>";
?>

<script type="text/javascript">
$(document).ready(function(){ $("a[rel='day_view']").colorbox({width:"480px", height:"450px", iframe:true}); });
</script>
 <div class="clear"></div>
<?php include('admin/footer.php'); ?>