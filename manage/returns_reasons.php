<?php
/*
$id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/

  require('includes/application_top.php');

  switch ($_GET['action']) {
    case 'insert':
    case 'save':
      $return_reason_id = tep_db_prepare_input($_GET['oID']);

      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $return_reason_name_array = $_POST['return_reason_name'];
        $language_id = $languages[$i]['id'];

        $sql_data_array = array('return_reason_name' => tep_db_prepare_input($return_reason_name_array[$language_id]));

        if ($_GET['action'] == 'insert') {
          if (!tep_not_null($return_reason_id)) {
            $next_id_query = tep_db_query("select max(return_reason_id) as return_reason_id from " . TABLE_RETURN_REASONS . "");
            $next_id = tep_db_fetch_array($next_id_query);
            $return_reason_id = $next_id['return_reason_id'] + 1;
          }

          $insert_sql_data = array('return_reason_id' => $return_reason_id,
                                   'language_id' => $language_id);
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_RETURN_REASONS, $sql_data_array);
        } elseif ($_GET['action'] == 'save') {
          tep_db_perform(TABLE_RETURN_REASONS, $sql_data_array, 'update', "return_reason_id = '" . tep_db_input($return_reason_id) . "' and language_id = '" . $language_id . "'");
        }
      }

      if ($_POST['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($return_reason_id) . "' where configuration_key = 'DEFAULT_RETURN_REASON'");
      }

      tep_redirect(tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $return_reason_id));
      break;
    case 'deleteconfirm':
      $oID = tep_db_prepare_input($_GET['oID']);

      $orders_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RETURN_REASON'");
      $orders_status = tep_db_fetch_array($orders_status_query);
      if ($orders_status['configuration_value'] == $oID) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_RETURN_REASON'");
      }

      tep_db_query("delete from " . TABLE_RETURN_REASONS . " where return_reason_id = '" . tep_db_input($oID) . "'");

      tep_redirect(tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page']));
      break;
    case 'delete':
      $oID = tep_db_prepare_input($_GET['oID']);
       /*
      $status_query = tep_db_query("select count(*) as count from " . TABLE_RETURN_REASONS . " where orders_status = '" . tep_db_input($oID) . "'");
      $status = tep_db_fetch_array($status_query);
       */
      $remove_status = true;
    /*  if ($oID == DEFAULT_ORDERS_STATUS_ID) {
        $remove_status = false;
        $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
      } elseif ($status['count'] > 0) {
        $remove_status = false;
        $messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
      } else {
        $history_query = tep_db_query("select count(*) as count from " . TABLE_RETURN_REASONS_HISTORY . " where '" . tep_db_input($oID) . "' in (new_value, old_value)");
        $history = tep_db_fetch_array($history_query);
        if ($history['count'] > 0) {
          $remove_status = false;
          $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
        }
      }
      break;  */
  }
?> 
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
 <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
 <div id="popupcalendar" class="text"></div>
 
<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>
 
 
 
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table  class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDERS_STATUS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $orders_status_query_raw = "select return_reason_id, return_reason_name from " . TABLE_RETURN_REASONS . " where language_id = '" . $languages_id . "' order by return_reason_id";
  $orders_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_status_query_raw, $orders_status_query_numrows);
  $orders_status_query = tep_db_query($orders_status_query_raw);
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    if (((!$_GET['oID']) || ($_GET['oID'] == $orders_status['return_reason_id'])) && (!$oInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $oInfo = new objectInfo($orders_status);
    }

    if ( (is_object($oInfo)) && ($orders_status['return_reason_id'] == $oInfo->return_reason_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $orders_status['return_reason_id']) . '\'">' . "\n";
    }

    if (DEFAULT_RETURN_REASON == $orders_status['return_reason_id']) {
      echo '                <td class="dataTableContent"><b>' . $orders_status['return_reason_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $orders_status['return_reason_name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent"><?php if ( (is_object($oInfo)) && ($orders_status['return_reason_id'] == $oInfo->return_reason_id) ) { echo '<i class="fa fa-long-arrow-right"></i>'; } else { echo '<a href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $orders_status['return_reason_id']) . '"><i class="fa fa-long-arrow-right"></i></a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TICKET_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (substr($_GET['action'], 0, 3) != 'new') {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&action=new') . '">' .  IMAGE_INSERT . '</a>'; ?></td>
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
  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $orders_status_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('return_reason_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page']) . '">' . IMAGE_CANCEL . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $orders_status_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('return_reason_name[' . $languages[$i]['id'] . ']', tep_get_return_reason_name($oInfo->return_reason_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      if (DEFAULT_ORDERS_STATUS_ID != $oInfo->return_reason_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id) . '">' .  IMAGE_CANCEL . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->return_reason_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id) . '">' . IMAGE_CANCEL . '</a>');
      break;
    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->return_reason_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id . '&action=edit') . '">' . IMAGE_EDIT . '</a> <a class="btn btn-default" href="' . tep_href_link(FILENAME_RETURNS_REASONS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->return_reason_id . '&action=delete') . '">' . IMAGE_DELETE. '</a>');

        $orders_status_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_return_reason_name($oInfo->return_reason_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $orders_status_inputs_string);
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
