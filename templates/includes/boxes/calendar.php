<?php
  include 'includes/languages/english/events_calendar.php';
  $info_box_contents = array();
  $info_box_contents[] = array('align' => '', 'text' => BOX_HEADING_CALENDAR);
?>
<!-- events_calendar //-->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php
  echo '<div class="module">



<div>

<div>

<div>

<h3>CALENDER</h3>';
?>
      <?php


?>
      <!-- events_calendar //-->
      <SCRIPT LANGUAGE="JavaScript">
//<[CDATA[
    function jump(view, url)

    {

        if (document.all||document.getElementById)

        {

            month= document.calendar._month.options[document.calendar._month.selectedIndex].value;

            year=  document.calendar._year.options[document.calendar._year.selectedIndex].value;

            return url +'?_month='+ month +'&_year='+ year +'&year_view='+ view;

        }

    }
//]]>
</SCRIPT>
      <?php

  $cal = new Calendar;
  $cal->setStartDay(FIRST_DAY_OF_WEEK);
  $this_month = date('m');
  $this_year = date('Y');
  if ($_GET['_month']) {
      $month = $_month;
      $year = $_year;
      $a = $cal->adjustDate($month, $year);
      $month_ = $a[0];
      $year_ = $a[1];
  } else {
      $year = date('Y');
      $month = date('m');
      $month_ = $month;
      $year_ = $year;
  }
?>
      <form method="get" name="calendar" action="events_calendar.php">
        <table class="calendarBox" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="calendarBoxHeader" align="left"></td>
          </tr>
          <tr>
            <td align="center" valign="top"><?php
  echo $cal->getMonthView($month, $year);
?> </td>
          </tr>
          <tr>
            <td class="yearHeader" style="line-height: 2px;">&nbsp;</td>
          </tr>
          <tr>
            <td class="yearHeader" align="center" valign="top" nowrap><!-- Month List -->
              <select name="_month">
                <?php
  $monthShort = explode(",", MONTHS_SHORT_ARRAY);
  $month = date('m');
  while (list($key, $value) = each($monthShort)) {
      if ($_GET['_month']) {
          $selected = '';
          if ($key + 1 == $_month) {
              $selected = 'selected';
          }
          $key = $key + 1;
?>
                <option value="<?php
          echo $key;
?>" <?php
          echo $selected;
?> > <?php
          echo $value;
?> </option>
                <?php
      } else {
          $selected = '';
          if ($key + 1 == $month) {
              $selected = 'selected';
          }
          $key = $key + 1;
?>
                <option value="<?php
          echo $key;
?>" <?php
          echo $selected;
?> > <?php
          echo $value;
?> </option>
                <?php
      }
  }
?>
              </select>
              <select name="_year">
                <!-- Year List -->
                <?php
  $year = date('Y');
  $years = NUMBER_OF_YEARS;
  for ($y = 0; $y < $years; $y++) {
      $_y = $year + $y;
      if ($_GET['_month']) {
          if ($_y == $_year) {
              echo '<option value="' . $_y . '" selected>' . $_y . '</option>' . "\n";
          } else {
              echo '<option value="' . $_y . '">' . $_y . '</option>' . "\n";
          }
      } else {
          if ($_y == $year) {
              echo '<option value="' . $_y . '" selected>' . $_y . '</option>' . "\n";
          } else {
              echo '<option value="' . $_y . '">' . $_y . '</option>' . "\n";
          }
      }
  }
?>
              </select

            >
              <input type="button" class="yearHeaderButton" title="<?php
  echo BOX_GO_BUTTON_TITLE;
?>"

                value="<?php
  echo BOX_GO_BUTTON;
?>"

                onclick="top.window.location=jump(0,'<?php
  echo FILENAME_EVENTS_CALENDAR;
?>')"

            />
              <input type="button" class="yearHeaderButton" title="<?php
  echo BOX_YEAR_VIEW_BUTTON_TITLE;
?>"

                value="<?php
  echo BOX_YEAR_VIEW_BUTTON;
?>"

                onclick="top.window.location=jump(1,'<?php
  echo FILENAME_EVENTS_CALENDAR;
?>')"

            />
              <input class="yearHeaderButton" title="<?php
  echo BOX_TODAY_BUTTON_TITLE;
?>"

                value="<?php
  echo BOX_TODAY_BUTTON;
?>"

                onclick='top.calendar.location="<?php
  echo FILENAME_EVENTS_CALENDAR_CONTENT;
?>?_month=<?php
  echo $this_month . '&_year=' . $this_year
?>"'

            <?php
  if (($month != $this_month) || ($month_ != $this_month)) {
      $todayBtnType = 'button';
  } else {
      $todayBtnType = 'hidden';
  }
?>

                type="<?php
  echo $todayBtnType;
?>"/>
              <br />
              <br />
              <center>
                <a class="calendarBoxHeader" href="<?php
  echo tep_href_link(FILENAME_EVENTS_CALENDAR, 'view=all_events');
?>" title="<?php
  echo BOX_CALENDAR_TITLE;
?>" target="_parent"> <?php
  echo 'All Events';
?> </a>
              </center></td>
          </tr>
        </table>
      </form>
      <!-- events_calendar //-->
      <?php
  echo '</div>

</div>

</div>

</div>';
?>
    </td>
  </tr>
</table>
<!-- events_calendar //-->