<?php
/*
  $Id: authors.php, v1.0 2003/12/04 12:00:00 ra Exp $

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
      case 'save':
        if (isset($_GET['auID'])) $authors_id = tep_db_prepare_input($_GET['auID']);
        $authors_name = tep_db_prepare_input($_POST['authors_name']);

        $sql_data_array = array('authors_name' => $authors_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_AUTHORS, $sql_data_array);
          $authors_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_AUTHORS, $sql_data_array, 'update', "authors_id = '" . (int)$authors_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $authors_desc_array = $_POST['authors_description'];
          $authors_url_array = $_POST['authors_url'];
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('authors_description' => tep_db_prepare_input($authors_desc_array[$language_id]),
                                  'authors_url' => tep_db_prepare_input($authors_url_array[$language_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('authors_id' => $authors_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_AUTHORS_INFO, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_AUTHORS_INFO, $sql_data_array, 'update', "authors_id = '" . (int)$authors_id . "' and languages_id = '" . (int)$language_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('authors');
        }

        tep_redirect(tep_href_link(FILENAME_AUTHORS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'auID=' . $authors_id));
        break;
      case 'deleteconfirm':
        $authors_id = tep_db_prepare_input($_GET['auID']);

        tep_db_query("delete from " . TABLE_AUTHORS . " where authors_id = '" . (int)$authors_id . "'");
        tep_db_query("delete from " . TABLE_AUTHORS_INFO . " where authors_id = '" . (int)$authors_id . "'");

        if (isset($_POST['delete_articles']) && ($_POST['delete_articles'] == 'on')) {
          $articles_query = tep_db_query("select articles_id from " . TABLE_ARTICLES . " where authors_id = '" . (int)$authors_id . "'");
          while ($articles = tep_db_fetch_array($articles_query)) {
            tep_remove_article($articles['articles_id']);
          }
        } else {
          tep_db_query("update " . TABLE_ARTICLES . " set authors_id = '' where authors_id = '" . (int)$authors_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('authors');
        }

        tep_redirect(tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page']));
        break;
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?> 
  <title><?php echo TITLE ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
  if (ARTICLE_WYSIWYG_ENABLE == 'Enable' && FCK_EDITOR != 'true') {
?>
<script language="Javascript1.2"><!-- // load htmlarea
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2  Articles Description HTML - Head
        _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
          var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
           if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
            if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
             if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
         <?php if (ARTICLE_MANAGER_WYSIWYG_BASIC == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
         document.write(' language="Javascript1.2"></scr' + 'ipt>');
            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
         <?php } else{ ?> if (win_ie_ver >= 5.5) {
         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
         document.write(' language="Javascript1.2"></scr' + 'ipt>');
            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
         <?php }?>
// --></script>
<?php
  }
?>

<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($action == 'new') {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_HEADING_NEW_AUTHOR; ?></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('authors', FILENAME_AUTHORS, 'action=insert', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" colspan="2"><?php echo TEXT_NEW_INTRO; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_AUTHORS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.png', '24', '15') . '&nbsp;' . tep_draw_input_field('authors_name', '', 'size="20"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
<?php
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_AUTHORS_DESCRIPTION; ?></td>
            <td>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;'; ?></td>
           <?php if (FCK_EDITOR == 'true') { ?>
                <td class="main"><?php echo tep_draw_fckeditor('authors_description[' . $languages[$i]['id'] . ']','700','300',''); ?></td>
           <?php } else { ?>
                  <td class="main" valign="top"><?php echo tep_draw_textarea_field('authors_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', ''); ?></td>
           <?php } ?>
                </tr>
              </table>
            </td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_AUTHORS_URL; ?></td>
            <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('authors_url[' . $languages[$i]['id'] . ']', '', 'size="30"'); ?></td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
<?php
  }
  // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 Authors Description HTML - Head
  if (ARTICLE_WYSIWYG_ENABLE == 'Enable' && FCK_EDITOR != 'true') {
?>
      <script language="JavaScript1.2" defer>
           var config = new Object();
           config.width = "<?php echo ARTICLE_MANAGER_WYSIWYG_WIDTH; ?>px";
           config.height = "<?php echo ARTICLE_MANAGER_WYSIWYG_HEIGHT; ?>px";
           config.bodyStyle = 'background-color: <?php echo ARTICLE_MANAGER_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo ARTICLE_MANAGER_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo ARTICLE_MANAGER_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo ARTICLE_MANAGER_WYSIWYG_FONT_SIZE; ?>pt;';
           config.debug = <?php echo ARTICLE_MANAGER_WYSIWYG_DEBUG; ?>;
      <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
        editor_generate('authors_description[<?php echo $languages[$i]['id']; ?>]',config);
      <?php } ?>
      </script>
<?php
  }
?>
      <tr>
        <td class="main">&nbsp;</td>
        <td class="main" align="left"><?php echo tep_draw_separator('pixel_trans.png', '24', '15') . '&nbsp;' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $_GET['auID']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>'; ?></td>
      </form>
      </tr>
          </tr>
        </table></td>
      </tr>
<?php
  } elseif ($action == 'edit') {

    $authors_query = tep_db_query("select authors_id, authors_name from " . TABLE_AUTHORS . " where authors_id = '" . $_GET['auID'] . "'");
    $authors = tep_db_fetch_array($authors_query)
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_HEADING_EDIT_AUTHOR; ?></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('authors', FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id'] . '&action=save', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" colspan="2"><?php echo TEXT_EDIT_INTRO; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_AUTHORS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.png', '24', '15') . '&nbsp;' . tep_draw_input_field('authors_name', $authors['authors_name'], 'size="20"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
<?php
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_AUTHORS_DESCRIPTION; ?></td>
            <td>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;'; ?></td>
           <?php if (FCK_EDITOR == 'true') { ?>
                <td class="main"><?php echo tep_draw_fckeditor('authors_description[' . $languages[$i]['id'] . ']','700','300', tep_get_author_description($authors['authors_id'], $languages[$i]['id'])); ?></td>
           <?php } else { ?>
                  <td class="main" valign="top"><?php echo tep_draw_textarea_field('authors_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', tep_get_author_description($authors['authors_id'], $languages[$i]['id'])); ?></td>
           <?php } ?>
                </tr>
              </table>
            </td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_AUTHORS_URL; ?></td>
            <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('authors_url[' . $languages[$i]['id'] . ']', tep_get_author_url($authors['authors_id'], $languages[$i]['id']), 'size="30"'); ?></td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
<?php
  }
  // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 Authors Description HTML - Head
  if (ARTICLE_WYSIWYG_ENABLE == 'Enable' && FCK_EDITOR != 'true') {
?>
      <script language="JavaScript1.2" defer>
           var config = new Object();
           config.width = "<?php echo ARTICLE_MANAGER_WYSIWYG_WIDTH; ?>px";
           config.height = "<?php echo ARTICLE_MANAGER_WYSIWYG_HEIGHT; ?>px";
           config.bodyStyle = 'background-color: <?php echo ARTICLE_MANAGER_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo ARTICLE_MANAGER_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo ARTICLE_MANAGER_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo ARTICLE_MANAGER_WYSIWYG_FONT_SIZE; ?>pt;';
           config.debug = <?php echo ARTICLE_MANAGER_WYSIWYG_DEBUG; ?>;
      <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
        editor_generate('authors_description[<?php echo $languages[$i]['id']; ?>]',config);
      <?php } ?>
      </script>
<?php
  }
?>
      <tr>
        <td class="main">&nbsp;</td>
        <td class="main" align="left"><?php echo tep_draw_separator('pixel_trans.png', '24', '15') . '&nbsp;' . tep_image_submit('button_save.png', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id']) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>'; ?></td>
      </form>
      </tr>
          </tr>
        </table></td>
      </tr>
<?php
  } elseif ($action == 'preview') {

    $authors_query = tep_db_query("select authors_id, authors_name from " . TABLE_AUTHORS . " where authors_id = '" . $_GET['auID'] . "'");
    $authors = tep_db_fetch_array($authors_query)

?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_ARTICLE_BY . $authors['authors_name']; ?></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" colspan="2" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo tep_get_author_description($authors['authors_id'], $languages[$i]['id']); ?></td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <?php if(tep_not_null(tep_get_author_url($authors['authors_id'], $languages[$i]['id']))) { ?>
          <tr>
            <td class="main" valign="top"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_get_author_url($authors['authors_id'], $languages[$i]['id'])); ?></td>
          <tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.png', '1', '10'); ?></td>
          </tr>
          <?php } ?>
<?php
  }
?>
      <tr>
        <td class="main" colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id']) . '">' . tep_image_button('button_back.png', IMAGE_BACK) . '</a>'; ?></td>
      </form>
      </tr>
          </tr>
        </table></td>
      </tr>
<?php } else { ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AUTHORS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $authors_query_raw = "select authors_id, authors_name, date_added, last_modified from " . TABLE_AUTHORS . " order by authors_name";
  $authors_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $authors_query_raw, $authors_query_numrows);
  $authors_query = tep_db_query($authors_query_raw);
  while ($authors = tep_db_fetch_array($authors_query)) {
    if ((!isset($_GET['auID']) || (isset($_GET['auID']) && ($_GET['auID'] == $authors['authors_id']))) && !isset($auInfo) && (substr($action, 0, 3) != 'new')) {
      $author_articles_query = tep_db_query("select count(*) as articles_count from " . TABLE_ARTICLES . " where authors_id = '" . (int)$authors['authors_id'] . "'");
      $author_articles = tep_db_fetch_array($author_articles_query);

     $auInfo_array = array_merge($authors, $author_articles);
      $auInfo = new objectInfo($auInfo_array);
    }

    if (isset($auInfo) && is_object($auInfo) && ($authors['authors_id'] == $auInfo->authors_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $authors['authors_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($auInfo) && is_object($auInfo) && ($authors['authors_id'] == $auInfo->authors_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.png'); } else { echo '<a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $authors['authors_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $authors_split->display_count($authors_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_AUTHORS); ?></td>
                    <td class="smallText" align="right"><?php echo $authors_split->display_links($authors_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $auInfo->authors_id . '&action=new') . '">' . tep_image_button('button_insert.png', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_AUTHOR . '</b>');

      $contents = array('form' => tep_draw_form('authors', FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $auInfo->authors_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $auInfo->authors_name . '</b>');

      if ($auInfo->articles_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_articles') . ' ' . TEXT_DELETE_ARTICLES);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_ARTICLES, $auInfo->articles_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.png', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $auInfo->authors_id) . '">' . tep_image_button('button_cancel.png', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($auInfo) && is_object($auInfo)) {
        $heading[] = array('text' => '<b>' . $auInfo->authors_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $auInfo->authors_id . '&action=edit') . '">' . tep_image_button('button_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $auInfo->authors_id . '&action=delete') . '">' . tep_image_button('button_delete.png', IMAGE_DELETE) . '</a><br>' . ' <a href="' . tep_href_link(FILENAME_AUTHORS, 'page=' . $_GET['page'] . '&auID=' . $_GET['auID']) . '&action=preview' . '">' . tep_image_button('button_preview.png', IMAGE_PREVIEW) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($auInfo->date_added));
        if (tep_not_null($auInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($auInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_ARTICLES . ' ' . $auInfo->articles_count);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td valign="top"  width="220px">' . "\n";

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