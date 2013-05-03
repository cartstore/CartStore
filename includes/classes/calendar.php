<?php

// PHP Calendar Class Version 1.4 (5th March 2001)
//
// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
//
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly
// from the use of this script. The author of this software makes
// no claims as to its fitness for any purpose whatsoever. If you
// wish to use this software you should first satisfy yourself that
// it meets your requirements.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk

class Calendar
{
    /*
        Constructor for the Calendar class
    */
    function Calendar()
    {
    }

    /*
        Get the array of strings used to label the days of the week. This array contains seven
        elements, one for each day of the week. The first entry in this array represents Sunday.
    */
    function getDayNames()
    {
        return $this->dayNames;
    }

    /*
        Set the array of strings used to label the days of the week. This array must contain seven
        elements, one for each day of the week. The first entry in this array represents Sunday.
    */
    function setDayNames($names)
    {

        $this->dayNames = $names;
    }

    /*
        Get the array of strings used to label the months of the year. This array contains twelve
        elements, one for each month of the year. The first entry in this array represents January.
    */
    function getMonthNames()
    {
        $this->monthNames;
    }

    /*
        Set the array of strings used to label the months of the year. This array must contain twelve
        elements, one for each month of the year. The first entry in this array represents January.
    */
    function setMonthNames($names)
    {
         $this->monthNames = $names;
    }

    /*
        Gets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
      function getStartDay()
    {
        return $this->startDay;
    }

    /*
        Sets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    function setStartDay($day)
    {
        $this->startDay = $day;
    }


    /*
        Gets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function getStartMonth()
    {
        return $this->startMonth;
    }

    /*
        Sets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }


    /*
        Return the URL to link to in order to display a calendar for a given month/year.
        You must override this method if you want to activate the "forward" and "back"
        feature of the calendar.

        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.

        If the calendar is being displayed in "year" view, $month will be set to 1.
    */

    function getCalendarLink($month, $year)
    {
         return "?_month=$month&_year=$year";
    }

    function pad($s, $n)
    {
    $r = $s;
    while (strlen($r) < $n)
    {
    $r = "0".$r;
    }
    return $r;
    }

    function getFileName($day, $month, $year)
    {
    	return $this->pad($year, 4) ."-". $this->pad($month, 2) ."-". $this->pad($day, 2);
    }

    function getDbLink($day, $month, $year)
    {
		$dateString = $this->getFileName($day, $month, $year);

    	//get all events that have the provided date in their duration.
    	$request = tep_db_query("select start_date from " . TABLE_EVENTS_CALENDAR
    		. " where '" . $dateString . "' between start_date and end_date");
    	if(tep_db_num_rows($request) > 0)
    	{
			while($event = tep_db_fetch_array($request))
        	{	//get the first event for this day's start date for the link
        		list($year_start, $month_start, $day_start) = preg_split('/[/.-]/', $event['start_date']);
        		break;
        	}
        	$bname = FILENAME_EVENTS_CALENDAR;
    		return "$bname?_day=$day_start&_month=$month_start&_year=$year_start";
      	}
    }

    /*
        Return the HTML for the current month
    */
    function getCurrentMonthView()
    {
        $d = getdate(time());
        return $this->getMonthView($d["mon"], $d["year"]);
    }


    /*
        Return the HTML for the current year
    */
    function getCurrentYearView()
    {
        $d = getdate(time());
        return $this->getYearView($d["year"]);
    }


    /*
        Return the HTML for a specified month
    */
    function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year);
    }

    /*
        Return the HTML for a specified year
    */
    function getYearView($year)
    {
        return $this->getYearHTML($year);
    }

    /********************************************************************************

        The rest are private methods. No user-servicable parts inside.

        You shouldn't need to call any of these functions directly.

    *********************************************************************************/

    /*
        Calculate the number of days in a month, taking into account leap years.
    */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }

        $d = $this->daysInMonth[$month - 1];

        if ($month == 2)
        {
            // Check for leap year
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
        return $d;
    }

    /*
        Generate the HTML for a given month
    */
    function getMonthHTML($m, $y, $show = 1)
    {
        $s = "";

        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year  = $a[1];

    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));

    	$first = $date["wday"];
        $monthName = $this->monthNames[$month - 1];

    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);

    	$this_month = date('m');
        $this_year = date('Y');
    	$header = $monthName . (($show > 0) ? " " . $year : "");
    	$D = $this->monthNames[11].'&nbsp;';
    	$J = $this->monthNames[0].'&nbsp;';

    	if ($show == 1)
    	{
         $prevMonth = '<a rel="lightbox-page" href='. FILENAME_EVENTS_CALENDAR_CONTENT . $this->getCalendarLink($prev[0], $prev[1]).'  title='. $this->monthNames[$month - 2] . (($month-2 < 1) ? $D.($year-1) : '&nbsp;'. $year) .' >&lt;</a>';
         $nextMonth = '<a rel="lightbox-page" href='. FILENAME_EVENTS_CALENDAR_CONTENT . $this->getCalendarLink($next[0], $next[1]).'  title='. $this->monthNames[$month + 0] . (($month+0 > 11) ? $J.($year+1) : '&nbsp;'. $year) .' >&gt;</a>';
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}

    	$s .= "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
    	$s .= "<tr class=\"calendarHeader\">\n";
    	$linkHeader = tep_href_link(FILENAME_EVENTS_CALENDAR, "_month=$month&_year=$year");
    	$s .= "<td align=\"left\"><a href=\"$linkHeader\" >$header</a></td>\n";
        $s .= "<td align=\"center\" class=\"yearHeader\">\n";
      if(mktime (0,0,0,$month ,0,$year) > mktime (0,0,0,$this_month ,0,$this_year)){
        $s .= $prevMonth;
        }else{
 //       $s .= "&nbsp;";
		$s .= $prevMonth;
        }
        $s .= "</td>\n";
    	$s .= "<td align=\"center\" class=\"yearHeader\">$nextMonth</td></tr>\n";
    	$s .= "<tr><td colspan=\"3\">\n";
        $s .= "<table cellspacing=\"1\" cellpadding=\"0\" border=\"0\" class=\"calendarMonth\" width=\"100%\"><tr class=\"calendarHeader\">\n";
        $s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+1)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+2)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+3)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+4)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+5)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"middle\">" . $this->dayNames[($this->startDay+6)%7] . "</td>\n";
    	$s .= "</tr>\n";

    	// We need to work out what date to start at so that the first appears in the correct column
    	$d = $this->startDay + 1 - $first;
    	while ($d > 1)
    	{
    	    $d -= 7;
    	}

        // Make sure we know when today is, so that we can use a different CSS style
        $today = getdate(time());
        $start_day = $this->getStartDay();
        $click = BOX_CLICK_LINK;
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";
    	    for ($i = 0; $i < 7; $i++)
    	    {
        	    $class = (($i < (6-$start_day) &! $i <= $start_day) ? (($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar" ) : (($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendarWeekend"));
    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
	             $link = $this->getDbLink($d, $month, $year);
                 $s .= (($link == "") ? "<td class=\"$class\" align=\"left\" valign=\"bottom\">" : "<td class=\"$class\" onclick=top.window.location=\"$link\" align=\"left\" valign=\"bottom\" style=\"cursor: hand;\">" );
                 if($show == 1){
                 $s .= (($link == "") ? $d : "<a href=\"$link\" target=\"_parent\" title=\"$click\">$d</a>");
                 }else{
                 $s .= (($link == "") ? $d : "<a href=\"$link\" title=\"$click\">$d</a>");
                 }
    	        }
    	        else
    	        {
    	            $s .= "<td class=\"empty\">&nbsp;";
    	        }
      	        $s .= "</td>\n";
        	    $d++;
    	    }
    	    $s .= "</tr>\n";
    	}
    	$s .= "</table>\n";
    	$s .= "</td></tr></table>\n";
        return $s;
    }

    /*
        Generate the HTML for a given year
    */
    function getYearHTML($year)
    {
        $year_view = 1;
        $s = "";
    	$prev = FILENAME_EVENTS_CALENDAR . $this->getCalendarLink(1, $year - 1) .'&year_view=1';
    	$next = FILENAME_EVENTS_CALENDAR . $this->getCalendarLink(1, $year + 1) .'&year_view=1';
    	$this_year = date('Y');

        $s .= "<table align=\"center\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\" style=\"cursor: default\">\n";
        $s .= "<tr>";

        if($year > $this_year){
        $s .= "<td class=\"yearHeader\" align=\"center\" align=\"left\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")  . "</td>\n";
        }else{
        $s .= "<td class=\"yearHeader\" align=\"center\" align=\"left\">&nbsp;</td>\n";
        }

        $s .= "<td height=\"20\" class=\"yearHeader\" align=\"center\">" . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) ."</td>\n";
    	$s .= "<td class=\"yearHeader\" align=\"center\" align=\"right\">" . (($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";
        return $s;
    }

    /*
        Adjust dates to allow months > 12 and < 0. Just adjust the years appropriately.
        e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
    */
    function adjustDate($month, $year)
    {
        $a = array();
        $a[0] = $month;
        $a[1] = $year;
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        return $a;
    }

    /*
        The start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    var $startDay = 0;

    /*
        The start month of the year. This is the month that appears in the first slot
        of the calendar in the year view. January = 1.
    */
    var $startMonth = 1;

    /*
        The labels to display for the days of the week. The first entry in this array
        represents Sunday.
    */
    var $dayNames = array(Su,Mo,Tu,We,Th,Fr,Sa);

    /*
        The labels to display for the months of the year. The first entry in this array
        represents January.
    */
    var $monthNames = array(January,February,March,April,May,June,July,August,September,October,November,December);

    /*
        The number of days in each month. You're unlikely to want to change this...
        The first entry in this array represents January.
    */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
}

?>
