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
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<script type="text/javascript" language="javascript" src="includes/general.js"></script>




 <div class="page-header"><h1><a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <i class="fa fa-question-circle"></i>
                  </a>

<?php echo HEADING_TITLE; ?>
</h1></div>


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
    if (function_exists('AnnounceVersion')) {
	if (false) { //database option not available so ignore
 ?>
 <?php echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?> 
 
 
<?php 
	} else if (tep_not_null($versionStatus)) {
	echo '';
	} else {
	echo '';
?>
	
 

	 
<?php 
	} 
    } else { 
?>


<?php 
    } 
?>

<p>
<?php echo '<a href="' . tep_href_link(FILENAME_PHPIDS) . '?action=delete_all' .'">' . TEXT_DELETE_ALL . '</a>'; ?></p>

 <table  class="table table-hover table-condensed table-responsive">
	<tr class="dataTableHeadingRow">
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_IP; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_IMPACT; ?></td>
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
	    <td class="dataTableContent"><?php echo $ip['impact']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['created']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['name']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['value']; ?></td>
	    <td class="dataTableContent"><?php echo $ip['page']; ?></td>
	</tr>
<?php
    }
?>
  
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
	 
	    </td>
         </tr>
    </table>
 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>