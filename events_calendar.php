<?php
/*
  $Id: events_calendar v2.00 2003/06/16 18:09:20 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EVENTS_CALENDAR);

define('SECTION', NAVBAR_TITLE);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_EVENTS_CALENDAR, '', 'NONSSL'));

//add breadcrumb for requested
if(isset($single_event) || $_GET['select_event'])
{
    $navbarEventTitle = NAVBAR_EVENT_TITLE_DETAIL;
}
else if($_GET['year_view'] == 1)
{
    $navbarEventTitle = NAVBAR_EVENT_TITLE_YEAR;
}
else if($_GET['_day'])
{
    $navbarEventTitle = NAVBAR_EVENT_TITLE_DAY;
}
else if($_GET['view'] == 'all_events')
{
    $navbarEventTitle = NAVBAR_EVENT_TITLE_ALL;
}
else
{
    $navbarEventTitle = NAVBAR_EVENT_TITLE_MONTH;
}
$breadcrumb->add($navbarEventTitle, $_SERVER["REQUEST_URI"], '', 'NONSSL');

$i =1;
$cal = new Calendar;
$cal->setStartDay(FIRST_DAY_OF_WEEK);
$this_month = date('m');
$this_year = date('Y');

if ($_GET['_month'])
{
    $month = $_month;
    $year = $_year;
    $a = $cal->adjustDate($month, $year);
    $month_ = $a[0];
    $year_= $a[1];
}
else
{
    $year = $this_year;
    $month = $this_month;
    $yeventear_= $year;
    $month_= $month;
    $year_= $year;
}
if($_GET['_day'])
{
    $ev_query = tep_db_query("select event_id from " . TABLE_EVENTS_CALENDAR
        . " where DAYOFMONTH(start_date)= '" . $_day . "' and MONTH(start_date) = '" . $_month
        . "' and YEAR(start_date) = '" . $_year . "' AND language_id = '" . $languages_id . "'");
    if(tep_db_num_rows($ev_query) == 1)
    {
        $ev = tep_db_fetch_array($ev_query);
        $single_event = true;
        $select_event = $ev['event_id'];
    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
    <TITLE><?php echo TITLE . ' - ' . NAVBAR_TITLE; ?></TITLE>
    <META http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<META name="KeyWords" content="">
	<META name="Description" content="">
    <BASE href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <LINK rel="stylesheet" type="text/css" href="stylesheet.css">
    <LINK rel="shortcut icon" href="favicon.ico" >
    <LINK rel="icon" href="favicon.ico" >
</HEAD>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?> " valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?> " cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
             <td class="pageHeading"><?php echo HEADING_TITLE ?></td>
                </tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                <tr>
                    <td  class="main" nowrap>
                    <?php
                        echo tep_draw_form('goto_event', FILENAME_EVENTS_CALENDAR, '', 'get');
                        $ev_query = tep_db_query("select *, DAYOFMONTH(start_date) AS day, MONTH(start_date) AS month, YEAR(start_date) AS year"
                            . " from " . TABLE_EVENTS_CALENDAR
                            . " where start_date >= '" . date('Y-m-d H:i:s') . "' and language_id = '" . $languages_id . "'"
                            . " order by start_date");
                        if(tep_db_num_rows($ev_query) > 0)
                        {
                            $event_array[]  = array('id' => '', 'text' => TEXT_SELECT_EVENT);
                            while ($q_events = tep_db_fetch_array($ev_query))
                            {
                                $year = $q_events['year'];
                                $month = $q_events['month'];
                                $day = $q_events['day'];
                                $event_array[] = array('id' => $q_events['event_id'], 'text' => $cal->monthNames[$month - 1] . ' ' . $day . ' -> ' . $q_events['title']);
                            }
                            echo tep_draw_pull_down_menu('select_event', $event_array, NULL, 'onChange="(this.value != \'\') ? this.form.submit() : \'\' " ;', $required = false);
                        }
                    ?>
                    </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
<?php
$dateDisplayFormat = "F d, Y";

if(isset($single_event) || $_GET['select_event'])
{   //Show Details of a single event.
    $events_query = tep_db_query("select *, DAYOFMONTH(start_date) AS event"
        . " from " . TABLE_EVENTS_CALENDAR
        . " where event_id = '" . $select_event . "' and language_id = '" . $languages_id . "'");

    while($events = tep_db_fetch_array($events_query))
    {
        list($year, $month, $day) = preg_split('/[\/\.-]/', $events['start_date']);
        $date_start = date($dateDisplayFormat, mktime(0,0,0,$month,$day,$year));
?>
            <H2><?php echo $events['title']?></H2>
<?php
        if($events['end_date'])
        {
            list($year_end, $month_end, $day_end) = preg_split('/[\/\.-]/', $events['end_date']);
            $date_end = date($dateDisplayFormat, mktime(0,0,0,$month_end,$day_end,$year_end));
        }
        $event_array = array('id' => $events['event_id'],
                             'title' => $events['title'],
                             'image' => $events['event_image'],
                             'description' => $events['description'],
                             'first_day' => $date_start,
                             'last_day' => $date_end,
                             'OSC_link' => $events['OSC_link'],
                             'link' => $events['link']);
        $clsp = 2;
?>
            <table width="100%" cellspacing="0" cellpadding="4" class="event_description">
                <tr>
                    <td class="event_header_dates" nowrap>
                        <?php
                            if($event_array['last_day'])
                            {
                                echo '<b>' . TEXT_EVENT_START_DATE . '</b>';
                            }
                        ?>
                        &nbsp;&nbsp;<?php echo $event_array['first_day'];?>
                    </td>
<?php
        if($event_array['last_day'])
        {
?>
                    <td class="event_header_dates" nowrap>
                        <b><?php echo TEXT_EVENT_END_DATE;?></b>&nbsp;&nbsp;<?php echo $event_array['last_day'];?>
                    </td>
<?php
            $clsp++;
        }
?>
                    <td width="100%" class="event" nowrap>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="<?php echo $clsp;?>" class="event_description">
                        <H4><?php echo TEXT_EVENT_DESCRIPTION;?></H4>
<?php
        if ($event_array['image'])
        {
?>
                        <table border="0" cellspacing="0" cellpadding="0" align="right">
                            <tr>
                                <td class="main">
                                    <?php echo tep_image(DIR_WS_IMAGES .'events_images/' . $event_array['image'], $event_array['title'], '', '', 'align="right" hspace="5" vspace="5"');?>
                                </td>
                            </tr>
                        </table>
<?php
        }
        echo stripslashes($event_array['description']);
?>
                    </td>
<?php
        if($event_array['OSC_link'])
        {
?>
                </tr>
                <tr>
                    <td colspan="<?php echo $clsp;?>"  align="left" class="event_header">
                        <?php echo TEXT_EVENT_OSC_LINK;?>&nbsp;&nbsp;
                        <a href="<?php echo $event_array['OSC_link'];?>">
                            <?php echo $event_array['OSC_link'];?>
                        </a>
                    </td>
<?php
        }
        if($event_array['link'])
        {
?>
                </tr>
                <tr>
                    <td colspan="<?php echo $clsp;?>" align="left" class="event_header">
                        <?php echo TEXT_EVENT_LINK;?>&nbsp;&nbsp;
                        <a href="http://<?php echo $event_array['link'];?>" target="_blank">
                            <?php echo $event_array['link'];?>
                        </a>
                    </td>
<?php
        }
?>
                </tr>
            </table>
<?php
    }

    //Show all other events for the same day or during the duration of the selected event.
    $beginDay = $year . '-' . $month . '-' . $day;
    $endDay = $year_end . '-' . $month_end . '-' . $day_end;
    $other_events_query = tep_db_query("select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR
        . " where ( (start_date BETWEEN '" . $beginDay . "' and '". $endDay . "')"
        . "			or (end_date BETWEEN '" . $beginDay . "' and '" . $endDay . "')"
        . "         or ( (start_date <= '" . $beginDay . "' and start_date <= '" . $endDay . "')"
        . "             and (end_date >= '" . $beginDay . "' and end_date >= '" . $endDay . "') ) )"
        . " and language_id = '" . $languages_id . "' and event_id != '" . $select_event
        . "' order by start_date");
    if (tep_db_num_rows($other_events_query) > 0)
    {
?>
            &nbsp;<h3><?php echo TEXT_OTHER_EVENTS;?></h3>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" class="event_header">

<?php
        while ($other_events = tep_db_fetch_array($other_events_query))
        {
            $event_array = array('id' => $other_events['event_id'],
                                 'event' => $other_events['event'],
                                 'title' => $other_events['title']);
?>
                <tr>
                    <td align="center" width="24" class="event_header" nowrap>
                        <b><?php echo $i; ?></b>
                    </td>
                    <td width="100%" class="event">
                        <a href="<?php echo FILENAME_EVENTS_CALENDAR;?>?select_event=<?php echo $event_array['id'];?>">
                            <?php echo $event_array['title'];?>
                        </a>
                    </td>
                </tr>
<?php
            $i++;
        }
?>
            </table>
<?php
    }
}
elseif($_GET['year_view'] == 1)
{   //Show the full year view.
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td><?php echo $cal->getYearView($year_); ?></td>
                </tr>
            </table>
<?php
}
elseif($_GET['_day'])
{   //Show all Events for the specified date.
    $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from " . TABLE_EVENTS_CALENDAR
    	. " where '" . $_year . "-" . $_month . "-" . $_day . "' BETWEEN start_date and end_date"
        . " and language_id = '" . $languages_id . "' order by start_date";

    $listingTitle = date($dateDisplayFormat, mktime(0, 0, 0, $_month, $_day, $_year));
    $displayPagingSuffix = $listingTitle;
    require(DIR_WS_MODULES . 'events_calendar_listing.php');
}
else if($_GET['view'] == 'all_events')
{   //Show all Events from current date.
    $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from " . TABLE_EVENTS_CALENDAR
        . " where (start_date >= '" . date('Y-m-d H:i:s') . "' or end_date >= '" . date('Y-m-d H:i:s') . "')"
        . " and language_id = '" . $languages_id . "' order by start_date";

    $listingTitle = 'All Events';
    $displayPagingSuffix = NULL;
    require(DIR_WS_MODULES . 'events_calendar_listing.php');
}
else
{   //Show All Events for the current or specified month/year
    $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from " . TABLE_EVENTS_CALENDAR
        . " where ((MONTH(start_date) = '" . $month_ . "' and YEAR(start_date) = '" . $year_ . "')"
        . "        or (MONTH(end_date) = '" . $month_ . "' and YEAR(end_date) = '" . $year_ . "'))"
        . " and language_id = '" . $languages_id . "'  order by start_date";

    $months = $cal->monthNames[$month_ - 1];

    $listingTitle = $months . ' ' . $year_;
    $displayPagingSuffix = $listingTitle;
    require(DIR_WS_MODULES . 'events_calendar_listing.php');
}
?>
        </td>
    </tr>
</table>
</td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</BODY>
</HTML>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
