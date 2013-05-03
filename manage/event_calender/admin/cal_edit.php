<?php
include 'includes/configure.php';
include('check.php');
include('dbconn.php');
include('header.php');


if(!$_GET['evid'] && !isset($_POST['do_edit']) && !isset($_POST['edit_event'])) {

$date = $_POST['date'];

?>

<h3>Edit Event</h3>

<p>Select the date of which the event is on</p>

<form action="" method="post">

<label>Date</label><input class="edit_search" type="text" name="date" id="datepicker" value="<?php echo $date; ?>" />
<input type="submit" class="button" value="Search" name="edit_event" />

</form>

<?php

$day = date('d');
$month = date('m');
$year = date('Y');

echo "<div id='upcoming'>";
echo "<h3>Upcoming Events ".date('F', mktime(0,0,0,$month,1))." '".date('y')."</h3>";

$sql = "SELECT * FROM calendar_event WHERE day >= '$day' AND month = '$month' AND year = '$year' LIMIT 0,10";
$result = mysql_query($sql);

echo "<ul class='upcoming-events'>";

while($row = mysql_fetch_array($result)) {

	echo "<li><a href='cal_edit.php?evid=".$row['id']."'>".$row['event'];
	echo "&nbsp;-&nbsp;<span class='upcoming-date'>".$row['day']."/".$row['month']."/".$row['year']."</span></a></li>";

}

echo "</ul>";

echo "</div>";

}

if($_GET['evid']) {

	$event_id = $_GET['evid'];

	$sql = "SELECT * FROM calendar_event WHERE id='$event_id'";
	$result = mysql_query($sql);

	$row = mysql_fetch_array($result);

	echo "<h3>Event Details</h3>";

?>

<form action="cal_edit.php" method="post">
<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
<label>Name</label><input type="text" name="name" value="<?php echo stripslashes($row['event']);?>" /><br />
<label>Descrption</label><textarea name="desc" class="ckeditor" cols="30" rows="10"/><?php echo stripslashes($row['description']);?></textarea>

<br /><br />

<label>Location</label><input type="text" name="location" value="<?php echo stripslashes($row['location']);?>" />

<br />

<label>Date</label><input type="text" name="date" id="datepicker" value="<?php echo $row['day'].'/'.$row['month'].'/'.$row['year'];?>" /><br /><br />

<?php

$from = str_split($row['time_from']);
$until = str_split($row['time_until']);

?>

<label>Time From (24hr)</label>
<select name="from">
<option selected value="<?php echo $from[0].$from[1];?>"><?php echo $from[0].$from[1];?></option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
</select>:
<select name="from2">
<option selected value="<?php echo $from[2].$from[3];?>"><?php echo $from[2].$from[3];?></option>
<option value="15">15</option>
<option value="30">30</option>
<option value="45">45</option>
<option value="00">00</option>
</select>
<br />

<label>Time Until (24hr)</label>
<select name="until">
<option selected value="<?php echo $until[0].$until[1];?>"><?php echo $until[0].$until[1];?></option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
</select>:
<select name="until2">
<option selected value="<?php echo $until[2].$until[3];?>"><?php echo $until[2].$until[3];?></option>
<option value="15">15</option>
<option value="30">30</option>
<option value="45">45</option>
<option value="00">00</option>
</select>

<br />
<div class="error_message">Delete this event? (Cannot be undone!) <input type="checkbox" class="checkbox" name="delete" value="delete_evid"></div>

<input type="submit" class="button" value="Confirm" name="do_edit" />
</form>

<?php

}

if(isset($_POST['edit_event'])) {

	$date = $_POST['date'];

	$d_for = explode('/', $date);

	$day = $d_for[0];
	$month = $d_for[1];
	$year = $d_for[2];

	$sql = "SELECT * FROM calendar_event WHERE day='$day' AND month='$month' AND year='$year'";
	$result = mysql_query($sql);

	//check that at least one row was returned
	$rowCheck = mysql_num_rows($result);

	echo '<h3>Search Results</h3>';

	if(!$date) {

	echo "<div class='error_message'>You must enter a date</div>";

	} elseif($rowCheck == '0') {

	echo "<div class='error_message'>No events found on <b>$day/$month/$year</b></div>";

	} else {

	echo '<div class="success_message">Found <b>'.$rowCheck.'</b> events on the <b>'.$day.'/'.$month.'/'.$year.'</b></div>';

	echo "<ul class='upcoming-events'>";

	while($row = mysql_fetch_array($result)) {

	echo "<li><a href='cal_edit.php?evid=".$row['id']."'>".$row['event'];
	echo "&nbsp;-&nbsp;<span class='upcoming-date'>".$row['day']."/".$row['month']."/".$row['year']."</span></a></li>";

	}

	echo "</ul>";

  }
}

if(isset($_POST['do_edit'])) {

	$id = $_POST['id'];
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$location = $_POST['location'];
	$from = $_POST['from'].$_POST['from2'];
	$until = $_POST['until'].$_POST['until2'];
	$delete = $_POST['delete'];

	$date = $_POST['date'];

	$d_for = explode('/', $date);

	$day = $d_for[0];
	$month = $d_for[1];
	$year = $d_for[2];

	if($delete == 'delete_evid') {

	$sql = "DELETE FROM calendar_event WHERE id='$id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h3>Deleted</h3>";
	echo "<div class='success_message'>This event has been deleted from your calendar.</div>";

	} else {

	if(!$name) { echo "You must enter an event name."; exit(); }
	if(!$location) { echo "You must enter a location for your event."; exit(); }

	$sql = "UPDATE calendar_event SET event='$name', description='$desc', location='$location', day='$day', month='$month', year='$year', time_from='$from', time_until='$until' WHERE id='$id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h3>Updated</h3>";
	echo "<div class='success_message'>Please check the updated event on your calendar.</div>";
	}
}

include('footer.php');

?>