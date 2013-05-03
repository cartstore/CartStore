<?php
/*
  $Id: phpids_report.php
  PHP Intrusion Detection System for osCommerce
  PHPIDS for osCommerce 1.6
  Date: June 13, 2010
  Created by celextel - www.celextel.com
  Module to include PHPIDS into osCommerce to log and prevent intrusions
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

  require('includes/application_top.php');

/********************** BEGIN VERSION CHECKER *********************/
    if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php'))  {
	require(DIR_WS_LANGUAGES . $language . '/version_checker.php');
	require(DIR_WS_FUNCTIONS . 'version_checker.php');
    }
/********************** END VERSION CHECKER *********************/

    $contribPath = 'http://addons.oscommerce.com/info/7374';
    $currentVersion = 'PHPIDS for osCommerce 1.6';
    $contribName = 'PHPIDS for osCommerce';
    $versionStatus = '';
    $supportThread = 'http://forums.oscommerce.com/topic/358046-php-intrusion-detection-system-for-oscommerce/';
    $authorSite = 'http://www.celextel.com/';
			
    $action = (isset($_GET['action']) ? $_GET['action'] : '');
    if (tep_not_null($action)) {
	switch ($action) {
	    case 'deleteconfirm':
		$id = tep_db_prepare_input($_GET['ipID']);
		tep_db_query("delete from " . TABLE_PHPIDS . " where id = '" . (int)$id . "'");
		tep_redirect(tep_href_link(FILENAME_PHPIDS));
		break;
	    case 'delete_all':
		tep_db_query("TRUNCATE TABLE ". TABLE_PHPIDS ."");
		tep_redirect(tep_href_link(FILENAME_PHPIDS));
		break;
	}
    }

/********************** BEGIN VERSION CHECKER *********************/
    $action2 = (isset($_POST['action']) ? $_POST['action'] : '');
    if (tep_not_null($action2)) {
	if ($action2 == 'getversion') {
	    if (isset($_POST['version_check']) && $_POST['version_check'] == 'on')
	    $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);
	}
    } 
/********************** END VERSION CHECKER *********************/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
.ds_small { 
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
font-weight:bold 
}
</style>
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
	<tr>
	    <td><?php echo '<a target="_blank" href="' . $contribPath . '">' . $currentVersion . '</a>'; ?></td>
	</tr>
	<tr>
	    <td><?php echo '<a target="_blank" href="' . $supportThread . '">' . HEADING_TITLE_SUPPORT_THREAD . '</a>'; ?></td>
	</tr>
	<tr>
	    <td><?php echo '<a target="_blank" href="' . $authorSite . '">' . HEADING_TITLE_AUTHOR . '</a>'; ?></td>
	</tr>
<?php
    if (function_exists('AnnounceVersion')) {
	if (false) { //database option not available so ignore
 ?>
	<tr>
	    <td class="ds_small"><?php echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?></td>
	</tr>
<?php 
	} else if (tep_not_null($versionStatus)) {
	echo '<tr><td class="ds_small">' . $versionStatus . '</td></tr>';
	} else {
	echo tep_draw_form('version_check', FILENAME_PHPIDS, '', 'post') . tep_draw_hidden_field('action', 'getversion');
?>
	<tr>
	    <td class="ds_small"><INPUT TYPE="radio" NAME="version_check" onClick="this.form.submit();"><?php echo TEXT_VERSION_CHECK_UPDATES; ?></td>
	</tr>
	</form>
<?php 
	} 
    } else { 
?>
	<tr>
	    <td class="ds_small"><?php echo TEXT_MISSING_VERSION_CHECKER; ?></td>
	</tr>
<?php 
    } 
?>
	<tr>
	    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PHPIDS) . '?action=delete_all' .'">' . TEXT_DELETE_ALL . '</a>'; ?></td>
	</tr>
    </table>
	    </td>
	</tr>
	<tr>
	    <td>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	    <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_IP; ?></td>
	    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_IMPACT; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VALUE; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE; ?></td>
	</tr>
<?php
    $ip_query = tep_db_query("select id, ip, name, impact, value, page, created from " . TABLE_PHPIDS . " order by id");
    while ($ip = tep_db_fetch_array($ip_query)) {
	if ((!isset($_GET['ipID']) || (isset($_GET['ipID']) && ($_GET['ipID'] == $ip['id']))) && !isset($ipInfo) && (substr($action, 0, 3) != 'new')) {
	$ipInfo = new objectInfo($ip);
	}
	if ( (isset($ipInfo) && is_object($ipInfo)) && ($ip['id'] == $ipInfo->id) ) {
	echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PHPIDS, 'ipID=' . $ipInfo->id . '&action=edit') . '\'">' . "\n";
	} else {
	echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PHPIDS, 'ipID=' . $ip['id']) . '\'">' . "\n";
	}
?>
	    <td class="dataTableContent"><?php echo '<a target="_blank" href="http://www.ipinfodb.com/ip_locator.php?ip=' . $ip['ip'] . '">' . $ip['ip'] . '</a>'; ?></td>
	    <td class="dataTableContent" align="center"><?php echo $ip['impact']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['created']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['name']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['value']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['page']; ?></td>
	</tr>
<?php
    }
?>
    </table>
	    </td>
<?php
    $heading = array();
    $contents = array();
	switch ($action) {
	    case 'delete':
		$heading[] = array('text' => '<center><b>' . $ipInfo->ip . '</b></center>');
		$contents = array('form' => tep_draw_form('ip', FILENAME_PHPIDS, 'ipID=' . $ipInfo->id . '&action=deleteconfirm'));
		$contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
		$contents[] = array('text' => '<br><b>' . $ipInfo->ip . '</b>');
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_PHPIDS, 'ipID=' . $ipInfo->id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
		default:
		if (isset($ipInfo) && is_object($ipInfo)) {
		$heading[] = array('text' => '<center><b>' . $ipInfo->ip . '</b></center>');
		$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PHPIDS, 'ipID=' . $ipInfo->id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
		}
		break;
	}
    if ((tep_not_null($heading)) && (tep_not_null($contents))) {
	echo '<td width="15%" valign="top">' . "\n";
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