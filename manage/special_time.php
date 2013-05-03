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
      case 'insert':
	  	$delv_date= $_POST['d_date_year']."-".$_POST['d_date_month']."-".$_POST['d_date_day'];
        $slotid = tep_db_prepare_input($_POST['slotid']);
        $cost = tep_db_prepare_input($_POST['cost']);
        $max_limit = tep_db_prepare_input($_POST['max_limit']);

        tep_db_query("insert into sw_emargengency_delivery_time (delv_date, slotid, em_cost, em_max_limit) values ('" . tep_db_input($delv_date) . "', '" . tep_db_input($slotid) . "', '" . tep_db_input($cost) . "', '" . (int)$max_limit . "')");

        tep_redirect(tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME));
        break;
      case 'save':
        $id = tep_db_prepare_input($_GET['cID']);
        $cost = tep_db_prepare_input($_POST['em_cost']);
        $max = tep_db_prepare_input($_POST['em_max_limit']);

	      tep_db_query("update sw_emargengency_delivery_time set em_cost = '" . tep_db_input($cost) . "', em_max_limit = '" . tep_db_input($max) . "' where id = '" . (int)$id . "'");

        tep_redirect(tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $id));
        break;
		case 'deleteconfirm':
        $id = tep_db_prepare_input($_GET['cID']);

        tep_db_query("delete from sw_emargengency_delivery_time where id = '" . (int)$id . "'");
		 tep_redirect(tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page']));
        break;


    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">


<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h1><?php echo HEADING_TITLE; ?></h1></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
			  	 <td width="151" class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td width="122" class="dataTableHeadingContent"><?php echo TABLE_HEADING_SLOT; ?></td>

                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_MAX_LIMIT; ?></td>
                <td width="227" align="right" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

  $delivery_time_query_raw = "select * from sw_emargengency_delivery_time INNER JOIN sw_time_slots on sw_emargengency_delivery_time.slotid=sw_time_slots.slotid  order by sw_emargengency_delivery_time.slotid";

  $delivery_time_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $delivery_time_query_raw, $delivery_time_query_numrows);
  $delivery_time_query = tep_db_query($delivery_time_query_raw);
  while ($delivery_time = tep_db_fetch_array($delivery_time_query)) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $delivery_time['id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($delivery_time);
    }

    if (isset($cInfo) && is_object($cInfo) && ($delivery_time['id'] == $cInfo->id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $delivery_time['id']) . '\'">' . "\n";
    }
?>

				 <td class="dataTableContent"><?php echo $delivery_time['delv_date']; ?></td>
                <td class="dataTableContent"><?php echo $delivery_time['slot']; ?></td>

                <td class="dataTableContent" align="center" width="156"><?php echo $delivery_time['em_max_limit']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($delivery_time['id'] == $cInfo->id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $delivery_time['id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
			  <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $delivery_time_split->display_count($delivery_time_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_DELIVERY_TIME); ?></td>
                    <td class="smallText" align="right"><?php echo $delivery_time_split->display_links($delivery_time_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="3" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&action=new') . '">New Delivery Time</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>

            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
  	   case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_DELIVERY_TIME . '</b>');

      $contents = array('form' => tep_draw_form('delv', FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>'.TABLE_HEADING_DATE.':'.$cInfo->delv_date . '</b>');
	  $contents[] = array('text' => '<br><b>'.TABLE_HEADING_SLOT.':'.$cInfo->slot . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->id) . '">' .  IMAGE_CANCEL. '</a>');
      break;
  	  case 'new':
	   $d_date = explode("[-]", date('Y-m-d'));
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_EMERGENCY_TIME . '</b>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&action=insert'));

      $contents[] = array('text' => '<br>' . TABLE_HEADING_DATE . '<br>'.tep_draw_date_selector('d_date', mktime(0,0,0, $d_date[1], $d_date[2], $d_date[0])));
      $contents[] = array('text' => '<br>' . TABLE_HEADING_SLOT . '<br>' . tep_draw_pull_down_menu('slotid', tep_get_time_slots()));
    $costs = array();
    $costs[0] = array('id' => 0, 'text' =>'FREE');
	$costs[1] = array('id' => 1, 'text' =>'1');
	$costs[2] = array('id' => 3, 'text' =>'3');
	$costs[3] = array('id' => 5, 'text' =>'5');

      $contents[] = array('text' => '<br>' . TABLE_HEADING_MAX_LIMIT . '<br>' . tep_draw_input_field('max_limit'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page']) . '">' . IMAGE_CANCEL . '</a>');
      break;
      case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_DELIVERY . '</b>');

      $contents = array('form' => tep_draw_form('delivery', FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'].'&cID=' . $cInfo->id . '&action=save'));

      $contents[] = array('text' => '<br>' . TABLE_HEADING_DATE . '<br>' . $cInfo->delv_date);
	 $contents[] = array('text' => '<br>' . TABLE_HEADING_SLOT . '<br>' . $cInfo->slot);
	  $costs = array();
    $costs[0] = array('id' => 0, 'text' =>'FREE');
	$costs[1] = array('id' => 1, 'text' =>'1');
	$costs[2] = array('id' => 3, 'text' =>'3');
	$costs[3] = array('id' => 5, 'text' =>'5');


      $contents[] = array('text' => '<br>' . TEXT_INFO_EDIT_MAX_LIMIT . '<br>' . tep_draw_input_field('em_max_limit', $cInfo->em_max_limit));
          $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->id) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' .$cInfo->delv_date.'('.$cInfo->slot . ')</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&dayid=' . $_GET['dayid'] . '&cID=' . $cInfo->id . '&action=edit') . '">' .IMAGE_EDIT . '</a>&nbsp;<a class="button" href="' . tep_href_link(FILENAME_EMERGENCY_DELIVERY_TIME, 'page=' . $_GET['page'] . '&cID=' . $cInfo->id . '&action=delete') . '">' .  IMAGE_DELETE . '</a>');

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
        </table></td>
      </tr>
    </table>


    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>

</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>