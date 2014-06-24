<?php
/*
  $Id: cache.php,v 1.23 2003/06/29 22:50:51 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    if ($action == 'reset') {
      tep_reset_cache_block($_GET['block']);
    }

    tep_redirect(tep_href_link(FILENAME_CACHE));
  }

// check if the cache directory exists
  if (is_dir(DIR_FS_CACHE)) {
    if (!is_writeable(DIR_FS_CACHE)) $messageStack->add(ERROR_CACHE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CACHE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

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
<table class="table">
          <tr>
            <td valign="top"><table class="table table-hover table-condensed table-responsive">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CACHE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_CREATED; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($messageStack->size < 1) {
    $languages = tep_get_languages();

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if ($languages[$i]['code'] == DEFAULT_LANGUAGE) {
        $language = $languages[$i]['directory'];
      }
    }

    for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
      $cached_file = preg_replace('/-language/', '-' . $language, $cache_blocks[$i]['file']);

      if (file_exists(DIR_FS_CACHE . $cached_file)) {
        $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cached_file));
      } else {
        $cache_mtime = TEXT_FILE_DOES_NOT_EXIST;
        $dir = dir(DIR_FS_CACHE);

        while ($cache_file = $dir->read()) {
          $cached_file = preg_replace('/-language/', '-' . $language, $cache_blocks[$i]['file']);

          if (preg_match('/^' . $cached_file . '/', $cache_file)) {
            $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cache_file));
            break;
          }
        }

        $dir->close();
      }
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)">
                <td class="dataTableContent"><?php echo $cache_blocks[$i]['title']; ?></td>
                <td class="dataTableContent"><?php echo $cache_mtime; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_reset.png', 'Reset', 13, 13) . '</a>'; ?>&nbsp;</td>
              </tr>
<?php
    }
  }
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_CACHE_DIRECTORY . ' ' . DIR_FS_CACHE; ?></td>
              </tr>
            </table> 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
