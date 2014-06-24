<?php
/*
  $Id: categories.php,v 1.146 2003/07/11 14:40:27 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

 $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'save':
        $defaultid = tep_db_prepare_input($_GET['cID']);
        $cost = tep_db_prepare_input($_POST['cost']);
        $max = tep_db_prepare_input($_POST['max']);

	      tep_db_query("update sw_default_delivery_time set cost = '" . tep_db_input($cost) . "', max_limit = '" . tep_db_input($max) . "' where defaultid = '" . (int)$defaultid . "'");

        tep_redirect(tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $defaultid));
        break;

    }
  }
?>
 <?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="spiffycalendar" class="text"></div>
 
 

<div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>
<?php echo HEADING_TITLE; ?></h1></div>

  <div class="panel-group" id="accordion">
                  <div class="clear"></div>
                  <div class="panel panel-default">

                      <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body"><i class="fa fa-question-circle fa-5x pull-left"></i>
Help for this section is not yet available.                          </div>
                      </div>
                  </div>   
              </div>    
<?php

	 $days = array();
  $days_array = array();
  $days_query = tep_db_query("select dayid, day from sw_week_days");
   while ($day = tep_db_fetch_array($days_query)) {
    $days[] = array('id' => $day['dayid'],
                               'text' => $day['day']);
    $days_array[$days['dayid']] = $day['day'];
  }
    echo tep_draw_form('selectday', FILENAME_DEFAULT_DELIVERY_TIME, '', 'get');
    echo '<div class="form-group"><label>'.HEADING_TITLE_SELECT_DAY . '</label>' . tep_draw_pull_down_menu('dayid', $days, $_GET['dayid'], 'onChange="this.form.submit();"');
    echo '</div></form>';
?>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SLOT; ?></td>

                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MAX_LIMIT; ?></td>
                <td align="right" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if($_REQUEST['dayid']=="")
  {
  $delivery_time_query_raw = "select * from sw_default_delivery_time INNER JOIN sw_time_slots on sw_default_delivery_time.slotid=sw_time_slots.slotid where sw_default_delivery_time.dayid=1 order by sw_default_delivery_time.slotid";
}
 else
 {
  $delivery_time_query_raw = "select * from sw_default_delivery_time INNER JOIN sw_time_slots on sw_default_delivery_time.slotid=sw_time_slots.slotid where sw_default_delivery_time.dayid=".$_REQUEST['dayid']." order by sw_default_delivery_time.slotid";
 }

  $delivery_time_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $delivery_time_query_raw, $delivery_time_query_numrows);
  $delivery_time_query = tep_db_query($delivery_time_query_raw);
  while ($delivery_time = tep_db_fetch_array($delivery_time_query)) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $delivery_time['defaultid']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($delivery_time);
    }

    if (isset($cInfo) && is_object($cInfo) && ($delivery_time['defaultid'] == $cInfo->defaultid)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->defaultid . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $delivery_time['defaultid']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $delivery_time['slot']; ?></td>

                <td class="dataTableContent" width="262"><?php echo $delivery_time['max_limit']; ?></td>
                <td class="dataTableContent"><?php if (isset($cInfo) && is_object($cInfo) && ($delivery_time['defaultid'] == $cInfo->defaultid) ) { echo '<i class="fa fa-long-arrow-right"></i>'; } else { echo '<a href="' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $delivery_time['defaultid']) . '"><i class="fa fa-hand-o-up"></i></a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
    <?php
  if (empty($action)) {
?>
               
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
      case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_DELIVERY . '</b>');

      $contents = array('form' => tep_draw_form('delivery', FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $cInfo->defaultid . '&action=save'));

     $contents[] = array('text' => '<br>' . TABLE_HEADING_SLOT . '<br>' . $cInfo->slot);
	  $costs = array();

    $costs[0] = array('id' => 0, 'text' =>'FREE');
	$costs[1] = array('id' => 1, 'text' =>'1');
	$costs[2] = array('id' => 3, 'text' =>'3');
	$costs[3] = array('id' => 5, 'text' =>'5');



      $contents[] = array('text' => '<br>' . TEXT_INFO_EDIT_MAX_LIMIT . '<br>' . tep_draw_input_field('max', $cInfo->max_limit));
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->defaultid) . '">' .  IMAGE_CANCEL . '</a>');
      break;
	   case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_DELIVERY . '</b>');

      $contents = array('form' => tep_draw_form('delivery', FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $cInfo->defaultid . '&action=save'));

     $contents[] = array('text' => '<br>' . TABLE_HEADING_SLOT . '<br>' . $cInfo->slot);
	  $costs = array();

    $costs[0] = array('id' => 0, 'text' =>'FREE');
	$costs[1] = array('id' => 1, 'text' =>'1');
	$costs[2] = array('id' => 3, 'text' =>'3');
	$costs[3] = array('id' => 5, 'text' =>'5');



      $contents[] = array('text' => '<br>' . TEXT_INFO_EDIT_MAX_LIMIT . '<br>' . tep_draw_input_field('max', $cInfo->max_limit));
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->defaultid) . '">' . IMAGE_CANCEL . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->slot . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="btn btn-default" href="' . tep_href_link(FILENAME_DEFAULT_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'] . '&cID=' . $cInfo->defaultid . '&action=edit') . '">' . IMAGE_EDIT . '</a>');

      }
      break;
  }

 if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="220px" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>