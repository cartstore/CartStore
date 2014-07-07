<?php
/*
  $Id: mm_templates.php 1751 2011-01-01 mail manager.php http:// www.css-oscommerce.com 00:00:00Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  	if (tep_not_null($action)) {
    	switch ($action) {
      		case 'insert':
      		case 'update':
        		if (isset($_POST['template_id'])) $template_id = tep_db_prepare_input($_POST['template_id']);
        		$title = tep_db_prepare_input($_POST['title']);
        		$htmlheader = tep_db_prepare_input($_POST['htmlheader']);
        		$htmlfooter = tep_db_prepare_input($_POST['htmlfooter']);
        		$txtheader = tep_db_prepare_input($_POST['txtheader']);
        		$txtfooter = tep_db_prepare_input($_POST['txtfooter']);

        		
          			$sql_data_array = array('title' => $title,
          									'htmlheader' => $htmlheader,
                                  			'htmlfooter' => $htmlfooter,
                                  			'txtheader' => $txtheader,
                                 			'txtfooter' => $txtfooter);

          		if ($action == 'insert') {
            		
            
            		tep_db_perform(TABLE_MM_TEMPLATES, $sql_data_array);
            		$template_id = tep_db_insert_id();
          	   } elseif ($action == 'update') {
            		tep_db_perform(TABLE_MM_TEMPLATES, $sql_data_array, 'update', "template_id = '" . (int)$template_id . "'");
          		
          		
          		tep_redirect(tep_href_link(FILENAME_MM_TEMPLATES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . $template_id));
        		} else {
          		$action = 'new';
        		}
        		break;
      		case 'deleteconfirm':
        		$template_id = tep_db_prepare_input($_GET['nID']);
        		tep_db_query("delete from " . TABLE_MM_TEMPLATES . " where template_id = '" . (int)$template_id . "'");
        		tep_redirect(tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page']));
       		 break;
      		case 'delete':
      		case 'new': if (!isset($_GET['nID'])) break;
        		$template_id = tep_db_prepare_input($_GET['nID']);

        	break;
    		}
  		}
?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<div class="page-header">
	<h1>
		<?php echo HEADING_TITLE; ?>
	</h1>
</div>




<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>

<!-- body_text //-->
    <td width="100%" valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  if ($action == 'new') {
    $form_action = 'insert';

    $parameters = array('title' => '',
                        'content' => '',
                        'txtcontent' => '',
                        'module' => '');

    $nInfo = new objectInfo($parameters);

    if (isset($_GET['nID'])) {
      $form_action = 'update';

      $nID = tep_db_prepare_input($_GET['nID']);

      $template_query = tep_db_query("select title, htmlheader, htmlfooter, txtheader, txtfooter from " . TABLE_MM_TEMPLATES . " where template_id = '" . (int)$nID . "'");
      $template = tep_db_fetch_array($template_query);

      $nInfo->objectInfo($template);
    } elseif ($_POST) {
      $nInfo->objectInfo($_POST);
    }

   
?>

      <tr><?php echo tep_draw_form('newsletter', FILENAME_MM_TEMPLATES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'action=' . $form_action); if ($form_action == 'update') echo tep_draw_hidden_field('template_id', $nID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">

          <tr>
            <td class="main"><?php echo TEXT_TEMPLATE_TITLE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('title', $nInfo->title, '', true); ?></td>
          </tr>

          <tr>
            <td class="main" valign="top"><?php echo TEXT_TEMPLATE_HTMLHEADER; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('htmlheader', 'soft', '100%', '20', $nInfo->htmlheader); ?></td>
          </tr>

          <tr>
            <td class="main" valign="top"><?php echo TEXT_TEMPLATE_HTMLFOOTER; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('htmlfooter', 'soft', '100%', '20', $nInfo->htmlfooter); ?></td>
          </tr>

          <tr>
            <td class="main" valign="top"><?php echo TEXT_TEMPLATE_TXTHEADER; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('txtheader', 'soft', '100%', '20', $nInfo->txtheader); ?></td>
          </tr>

          <tr>
            <td class="main" valign="top"><?php echo TEXT_TEMPLATE_TXTFOOTER; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('txtfooter', 'soft', '100%', '20', $nInfo->txtfooter); ?></td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td>
        	<table border="0" width="100%" cellspacing="0" cellpadding="2">
          		<tr>
            		<td class="main" align="right"><?php echo (($form_action == 'insert') ? tep_image_submit('button_save.gif', IMAGE_SAVE) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a class="btn btn-defualt" href="' . tep_href_link(FILENAME_MM_TEMPLATES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['nID']) ? 'nID=' . $_GET['nID'] : '')) . '">CANCEL</a>'; ?></td>
          		</tr>
        	</table>
        </td>
      	</form>
       </tr>
<?php
  } elseif ($action == 'preview') {
    $nID = tep_db_prepare_input($_GET['nID']);

    $newsletter_query = tep_db_query("select title, htmlheader, htmlfooter, txtheader, txtfooter from " . TABLE_MM_TEMPLATES . " where template_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);

    // compile preview mailpiece
	$output_content_html = $newsletter['htmlheader'].'
			<table cellpadding="10" cellspacing="10"><tr><td class="main"><br /><h1>Preview Content</h1> Go to admin/mm_templates.php line about 183 to edit this text.<br />
			This is just demonstation text for the template preview, and serves to show how this template will incorporate your real content. It appears only in the admin 
			preview, and does not appear in the email sent.<br /><br /></td></tr></table>'
			.$newsletter['htmlfooter'];
    
?>
      <tr><td align="right"><?php echo '<a class="btn btn-defualt" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">BACK</a>'; ?></td></tr>
      <tr><td><?php echo $output_content_html; ?></td></tr>
      <tr><td align="right"><?php echo '<a class="btn btn-defualt" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">BACK </a>'; ?></td></tr>
<?php
  }elseif ($action == 'confirm') {
    $nID = tep_db_prepare_input($_GET['nID']);

    $newsletter_query = tep_db_query("select title, htmlheader, htmlfooter, txtheader, txtfooter from " . TABLE_MM_TEMPLATES . " where template_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

  }else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="table table-hover">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TEMPLATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                 
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $templates_query_raw = "select template_id, title, htmlfooter, txtheader, txtfooter, length(htmlheader) as content_length from " . TABLE_MM_TEMPLATES . " order by template_id desc";
    $templates_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $templates_query_raw, $templates_query_numrows);
    $templates_query = tep_db_query($templates_query_raw);
    while ($template = tep_db_fetch_array($templates_query)) {
    if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $template['template_id']))) && !isset($nInfo) && (substr($action, 0, 3) != 'new')) {
        $nInfo = new objectInfo($template);
      }

      if (isset($nInfo) && is_object($nInfo) && ($template['template_id'] == $nInfo->template_id) ) {
        echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $nInfo->template_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $template['template_id']) . '\'">' . "\n";
      }
?>
                
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $template['template_id'] . '&action=preview') . '"><i class="fa fa-eye"></i></a>&nbsp;' . $template['title']; ?></td>
                <td class="dataTableContent" align="right"><?php echo number_format($template['content_length']) . ' bytes'; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nInfo) && is_object($nInfo) && ($template['template_id'] == $nInfo->template_id) ) { echo '<i class="fa fa-long-arrow-right"></i>'; } else { echo '<a href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $template['template_id']) . '"><i class="fa fa-hand-o-up"></i></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="3">
                	<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  	<tr>
                    	<td class="smallText" valign="top"><?php echo $templates_split->display_count($templates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS); ?></td>
                    	<td class="smallText" align="right"><?php echo $templates_split->display_links($templates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  	</tr>
                  	<tr>
                    	<td align="right" colspan="2"><?php echo '<a class="btn btn-defualt" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'action=new') . '">NEW</a>'; ?></td>
                  	</tr>
                	</table>
                </td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

      $contents = array('form' => tep_draw_form('newsletters', FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $nInfo->template_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTROO);
      $contents[] = array('text' => '<br><b>' . $nInfo->title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a class="btn btn-defualt" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">CANCEL</a>');
      break;
    default:
      if (is_object($nInfo)) {
        $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

          $contents[] = array('align' => 'center', 'text' => '
          				<a class="btn btn-defualt btn-xs" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $nInfo->template_id . '&action=new') . '">EDIT </a> 
          				<a class="btn btn-defualt btn-xs" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $nInfo->template_id . '&action=delete') . '">DELETE</a> 
          				<a class="btn btn-defualt btn-xs" href="' . tep_href_link(FILENAME_MM_TEMPLATES, 'page=' . $_GET['page'] . '&nID=' . $nInfo->template_id . '&action=preview') . '">PREVIEW</a>');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>

  </tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
