<?php
/*
  $Id: banned_ip.php V1 2010
  Admin Root File
  Originally Created by: Your Friend Sky_Diver
  Modified by: celextel - www.celextel.com 
  PHP Intrusion Detection System for osCommerce
  PHPIDS for osCommerce 1.6
  Date: June 13, 2010
  Released under the GNU General Public License
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('includes/application_top.php');
	
// Sets the status
    function tep_set_ip_status($id, $ip_status) {
	if ($ip_status == '1') {
	return tep_db_query("update " . TABLE_BANNED_IP . " set ip_status = '1' where id = '" . (int)$id . "'");
	} elseif ($ip_status == '0') {
	return tep_db_query("update " . TABLE_BANNED_IP . " set ip_status = '0' where id = '" . (int)$id . "'");
	} else {
	return -1;
	}
    }

    $action = (isset($_GET['action']) ? $_GET['action'] : '');
    if (tep_not_null($action)) {
	switch ($action) {
	    case 'setflag':
		tep_set_ip_status($_GET['id'], $_GET['flag']);
		tep_redirect(tep_href_link(FILENAME_BANNED_IP));
		break;
	    case 'insert':
		$ip_address = tep_db_prepare_input($_POST['ip_address']);
		$reason = tep_db_prepare_input($_POST['reason']);
		$check_query = tep_db_query("select id from " . TABLE_BANNED_IP . " where ip_address = '" . tep_db_input($ip_address) . "' limit 1");
		if (tep_db_num_rows($check_query) < 1) {
		tep_db_query("insert into " . TABLE_BANNED_IP . " (ip_address, ip_status, reason) values ('" . tep_db_input($ip_address) . "', '0', '" . tep_db_input($reason) . "')");
		} else {
		$messageStack->add_session(ERROR_IP_EXISTS, 'error');
		}
		tep_redirect(tep_href_link(FILENAME_BANNED_IP));
		break;
	    case 'save':
		$ip_address = tep_db_prepare_input($_POST['ip_address']);
		$reason = tep_db_prepare_input($_POST['reason']);
		tep_db_query("update " . TABLE_BANNED_IP . " set ip_address = '" . tep_db_input($ip_address) . "' where id = '" . (int)$_GET['ipID'] . "'");
		tep_db_query("update " . TABLE_BANNED_IP . " set reason = '" . tep_db_input($reason) . "' where id = '" . (int)$_GET['ipID'] . "'");
		if (tep_not_null($ip_status)) {
		tep_db_query("update " . TABLE_BANNED_IP . " set ip_status = '0' where id = '" . (int)$_GET['ipID'] . "'");
		}
		tep_redirect(tep_href_link(FILENAME_BANNED_IP, 'ipID=' . (int)$_GET['ipID']));
		break;
	    case 'deleteconfirm':
		$id = tep_db_prepare_input($_GET['ipID']);
		tep_db_query("delete from " . TABLE_BANNED_IP . " where id = '" . (int)$id . "'");
		tep_redirect(tep_href_link(FILENAME_BANNED_IP));
		break;
	}
    }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" language="javascript" src="includes/general.js"></script>
</head>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
	    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table>
	    </td>
<!-- body_text //-->
	    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
	    <td>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	</tr>
    </table>  
	    </td>
	</tr>
	<tr>
	    <td>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	    <td valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BANNED_IP; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TIME; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_REASON; ?></td>
	    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_IP_STATUS; ?></td>
	    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
	</tr>
<?php
    $ip_query = tep_db_query("select id, ip_address, ip_status, reason, created from " . TABLE_BANNED_IP . " order by id");
    while ($ip = tep_db_fetch_array($ip_query)) {
	if ((!isset($_GET['ipID']) || (isset($_GET['ipID']) && ($_GET['ipID'] == $ip['id']))) && !isset($ipInfo) && (substr($action, 0, 3) != 'new')) {
	$ipInfo = new objectInfo($ip);
	}
	if ( (isset($ipInfo) && is_object($ipInfo)) && ($ip['id'] == $ipInfo->id) ) {
	echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id . '&action=edit') . '\'">' . "\n";
	} else {
	echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ip['id']) . '\'">' . "\n";
	}
?>
	    <td class="dataTableContent"><?php echo '<a target="_blank" href="http://www.ipinfodb.com/ip_locator.php?ip=' . $ip['ip_address'] . '">' . $ip['ip_address'] . '</a>'; ?></td>
	    <td class="dataTableContent"><?php echo $ip['created']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['reason']; ?></td>
	    <td  class="dataTableContent" align="center">
<?php
	if ($ip['ip_status'] == '0') {
	echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '&nbsp;<b>/</b>&nbsp;<a href="' . tep_href_link(FILENAME_BANNED_IP, 'action=setflag&flag=1&id=' . $ip['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
	} else {
	echo '<a href="' . tep_href_link(FILENAME_BANNED_IP, 'action=setflag&flag=0&id=' . $ip['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>&nbsp;<b>/</b>&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10);
	}
?>
	    </td>
	    <td class="dataTableContent" align="right"><?php if ( (isset($ipInfo) && is_object($ipInfo)) && ($ip['id'] == $ipInfo->id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ip['id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
	</tr>
<?php
    }
?>
	<tr>
	    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_BANNED_IP, 'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
	</tr>
    </table>
	    </td>
<?php
    $heading = array();
    $contents = array();
	switch ($action) {
	    case 'new':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_IP . '</b>');
		$contents = array('form' => tep_draw_form('ipaddress', FILENAME_BANNED_IP, 'action=insert'));
		$contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
		$contents[] = array('text' => '<br>' . TEXT_INFO_IP . '<br>' . tep_draw_input_field('ip_address'));
		$contents[] = array('text' => '<br>' . TABLE_HEADING_REASON . '<br>' . tep_draw_textarea_field('reason', 'soft', '40', '6', $ipInfo->reason));
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_BANNED_IP) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	    case 'edit':
		$heading[] = array('text' => '<b>' . $ipInfo->ip_address . '</b>');
		$contents = array('form' => tep_draw_form('ipaddress', FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id . '&action=save'));
		$contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
		$contents[] = array('text' => '<br>' . TEXT_INFO_IP . '<br>' . tep_draw_input_field('ip_address', $ipInfo->ip_address));
		$contents[] = array('text' => '<br>' . TABLE_HEADING_REASON . '<br>' . tep_draw_textarea_field('reason', 'soft', '40', '6', $ipInfo->reason));
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	    case 'delete':
		$heading[] = array('text' => '<b>' . $ipInfo->ip_address . '</b>');
		$contents = array('form' => tep_draw_form('ipaddress', FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id . '&action=deleteconfirm'));
		$contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
		$contents[] = array('text' => '<br><b>' . $ipInfo->ip_address . '</b>');
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
		default:
		if (isset($ipInfo) && is_object($ipInfo)) {
		$heading[] = array('text' => '<b>' . $ipInfo->ip_address . '</b>');
		$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BANNED_IP, 'ipID=' . $ipInfo->id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
		}
		break;
	}
    if ((tep_not_null($heading)) && (tep_not_null($contents))) {
	echo '<td width="25%" valign="top">' . "\n";
	$box = new box;
	echo $box->infoBox($heading, $contents);
	echo '</td>' . "\n";
    }
?>
	</tr>
    </table>
	    </td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>