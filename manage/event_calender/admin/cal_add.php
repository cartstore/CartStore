<?php
include 'includes/configure.php';
include('check.php');
include('dbconn.php');
include('header.php');


if(!isset($_POST['new_event']) && !isset($_POST['add_event'])) {

?>

<h3>Add Event</h3>

<form action="" method="post">

<label>Name</label><input type="text" name="name" /><br />
<label>Descrption</label><textarea name="desc" class="ckeditor" cols="30" rows="10"/><?php echo $row['description'];?></textarea>
<br /><br />

<label>Location</label><input type="text" name="location" />

<br />
<label>Date</label><input type="text" name="date" id="datepicker" />

<br /><br />

<label>Time From (24hr)</label>
<select name="from">
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
<option value="15">15</option>
<option value="30">30</option>
<option value="45">45</option>
<option value="00" selected="selected">00</option>
</select>
<br />

<label>Time Until (24hr)</label>
<select name="until">
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
<option value="15">15</option>
<option value="30">30</option>
<option value="45">45</option>
<option value="00" selected="selected">00</option>
</select>

<br /><br />

<input type="submit" class="button" value="Continue" name="new_event" />

</form>

<?php

}

// Get POST vars.

	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$location = $_POST['location'];
	$date = $_POST['date'];
	$from = $_POST['from'].$_POST['from2'];
	$until = $_POST['until'].$_POST['until2'];


if(isset($_POST['add_event']) && $_GET['add'] == 'new') {

	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$location = $_POST['location'];
	$from = $_POST['from'].$_POST['from2'];
	$until = $_POST['until'].$_POST['until2'];

	$date = $_POST['date'];

	$d_for = explode('/', $date);

	$day = $d_for[0];
	$month = $d_for[1];
	$year = $d_for[2];

	if(!$name) { echo "<div class='error_message'>You must enter an event name</div>"; exit(); }
	if(!$desc) { echo "<div class='error_message'>Please enter an event description</div>"; exit(); }
	if(!$location) { echo "<div class='error_message'>You must enter a location for your event</div>"; exit(); }
	if(!$date) { echo "<div class='error_message'>Your event must have a date</div>"; exit(); }

	$sql = "INSERT INTO calendar_event (event, description, location, day, month, year, time_from, time_until)
				VALUES ('$name', '$desc', '$location', '$day', '$month', '$year', '$from', '$until')";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h3>Success!</h3>";
	echo "<div class='success_message'>Your event has been added to the calendar.</div>";

}

if(isset($_POST['new_event'])) {

?>

<h3>Confirm Details</h3>

<p>Are these details correct?</p>

<?php echo "Event ".stripslashes($name)."<br />"; ?>
<?php echo "Description ".stripslashes($desc)."<br />"; ?>
<?php echo "location ".stripslashes($location)."<br />"; ?>
<?php echo "Date ".$date."<br />"; ?>
<?php echo "From ".$from."<br />"; ?>
<?php echo "Until ".$until."<br />"; ?>

<form action="?add=new" method="post">
<input type="hidden" name="name" value="<?php echo stripslashes($name); ?>" />
<input type="hidden" name="desc" value="<?php echo stripslashes($desc); ?>" />
<input type="hidden" name="location" value="<?php echo stripslashes($location); ?>" />
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<input type="hidden" name="from" value="<?php echo $from; ?>" />
<input type="hidden" name="until" value="<?php echo $until; ?>" />

<br /><br />

<input type="button" class="submit" value="No, back" name="back_event" onClick="history.go(-1)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" name="add_event" value="Confirm" />

</form>

<?php

}

include('footer.php');

?>