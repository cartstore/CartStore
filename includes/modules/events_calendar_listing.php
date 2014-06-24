<?php
/**
 * Event Calendar Listing.
 *  Input:
 *      $events_query_raw
 *      $listingTitle
 *      $displayPagingSuffix
 */

if(isset($listingTitle))
{
    echo '<H2>' . $listingTitle . '</H2>';
}

$events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
$events_query = tep_db_query($events_query_raw);

//Show Paging Header ?
if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')))
{
?>


           <div id="module-product">
           <div class="sort">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="smallText">
                        <?php
                            if(isset($displayPagingSuffix))
                            {
                                $displayPagingSuffix = ' : ' . $displayPagingSuffix;
                            }
                            echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES . $displayPagingSuffix);
                        ?>
                    </td>
                    <td align="right" class="smallText">
                        <?php echo TEXT_RESULT_PAGE . ' <ul>' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul>
                    </td>
                </tr>
            </table>
            </div></div>

<?php
}

$row = 0;
$list_box_contents = array();

//Add headings ...
$list_box_contents[$row][] = array('align' => '',
                                'params' => '',
                                'text' => 'Start ' . TEXT_EVENT_DATE . '');
$list_box_contents[$row][] = array('align' => '',
                                'params' => '',
                                'text' => 'End ' . TEXT_EVENT_DATE . '&nbsp;');
$list_box_contents[$row][] = array('align' => '',
                                'params' => '',
                                'text' => '&nbsp;' . TEXT_EVENT_TITLE . '<br />
');

//Add listing rows ...
$events_query = tep_db_query($events_split->sql_query);
while($events = tep_db_fetch_array($events_query))
{
    $row++;
    list($year, $month, $day) = preg_split ('/[\/\.-]/', $events['start_date']);
    $list_box_contents[$row][] = array('align'  => '',
                                       'params' => '',
                                       'text'   => date("F j, Y", mktime(0, 0, 0, $month, $day, $year)));
    $endDate = '-';
    if(isset($events['end_date']) && trim($events['end_date']) != '')
    {
        list($year, $month, $day) = preg_split ('/[\/\.-]/', $events['end_date']);
        $endDate = date("F j, Y", mktime(0, 0, 0, $month, $day, $year));
    }
    $list_box_contents[$row][] = array('align'  => '',
                                       'params' => '',
                                       'text'   => $endDate);
    $list_box_contents[$row][] = array('align' => '',
                                       'params' => '',
                                       'text'  =>  '
<a href="'
                                          . FILENAME_EVENTS_CALENDAR . '?select_event=' . $events['event_id'] . '">'
                                          . $events['title'] . '</a><br />
');
}

//Show listing
new productListingBox($list_box_contents);

if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')))
{
?>

             <div id="module-product">
           <div class="sort">
           <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="smallText">
                        <?php
                            if(isset($displayPagingSuffix))
                            {
                                $displayPagingSuffix = ' : ' . $displayPagingSuffix;
                            }
                            echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES . $displayPagingSuffix);
                        ?>
                    </td>
                    <td align="right" class="smallText">
                        <?php echo TEXT_RESULT_PAGE . '<ul> ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul>
                    </td>
                </tr>
            </table>
            </div>
            </div>


<?php
}
else
{
?>
            <b><?php echo TEXT_NO_EVENTS; ?></b>

<?php
}
?>