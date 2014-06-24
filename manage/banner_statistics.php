<?php
/*
  $Id: banner_statistics.php,v 1.5 2003/06/20 00:30:15 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $type = (isset($_GET['type']) ? $_GET['type'] : '');

  $banner_extension = tep_banner_image_extension();

// check if the graphs directory exists
  $dir_ok = false;
  if (function_exists('imagecreate') && tep_not_null($banner_extension)) {
    if (is_dir(DIR_WS_IMAGES . 'graphs')) {
      if (is_writeable(DIR_WS_IMAGES . 'graphs')) {
        $dir_ok = true;
      } else {
        $messageStack->add(ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE, 'error');
      }
    } else {
      $messageStack->add(ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST, 'error');
    }
  }

  $banner_query = tep_db_query("select banners_title from " . TABLE_BANNERS . " where banners_id = '" . (int)$_GET['bID'] . "'");
  $banner = tep_db_fetch_array($banner_query);

  $years_array = array();
  $years_query = tep_db_query("select distinct year(banners_history_date) as banner_year from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$_GET['bID'] . "'");
  while ($years = tep_db_fetch_array($years_query)) {
    $years_array[] = array('id' => $years['banner_year'],
                           'text' => $years['banner_year']);
  }

  $months_array = array();
  for ($i=1; $i<13; $i++) {
    $months_array[] = array('id' => $i,
                            'text' => strftime('%B', mktime(0,0,0,$i)));
  }

  $type_array = array(array('id' => 'daily',
                            'text' => STATISTICS_TYPE_DAILY),
                      array('id' => 'monthly',
                            'text' => STATISTICS_TYPE_MONTHLY),
                      array('id' => 'yearly',
                            'text' => STATISTICS_TYPE_YEARLY));
?>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<?php echo tep_draw_form('year', FILENAME_BANNER_STATISTICS, '', 'get'); ?>
       

<div class="page-header"><h1><?php echo HEADING_TITLE; ?></h1></div>


     <div class="form-group"><label>
<?php echo TITLE_TYPE . ' </label>' . tep_draw_pull_down_menu('type', $type_array, (tep_not_null($type) ? $type : 'daily'), 'onChange="this.form.submit();"'); ?><noscript>


<input type="submit" class="btn btn-default" value="GO"></noscript>

</div>


<?php
  switch ($type) {
    case 'yearly': break;
    case 'monthly':
      echo '<div class="form-group"><label>'. TITLE_YEAR . '</label> ' . tep_draw_pull_down_menu('year', $years_array, (isset($_GET['year']) ? $_GET['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" class="btn btn-default" value="GO"></noscript></div>';
      break;
    default:
    case 'daily':
      echo '<div class="form-group"><label>'. TITLE_MONTH . ' </label>' . tep_draw_pull_down_menu('month', $months_array, (isset($_GET['month']) ? $_GET['month'] : date('n')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" class="btn btn-default" value="GO"></noscript></div>

     <div class="form-group"><label> ' . TITLE_YEAR . '</label> ' . tep_draw_pull_down_menu('year', $years_array, (isset($_GET['year']) ? $_GET['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" class="btn btn-default" value="GO"></noscript></div>';
      break;
  }
?>
            </td>
          <?php echo tep_draw_hidden_field('page', $_GET['page']) . tep_draw_hidden_field('bID', $_GET['bID']); ?></form></tr>
        <p>
<?php
  if (function_exists('imagecreate') && ($dir_ok == true) && tep_not_null($banner_extension)) {
    $banner_id = (int)$_GET['bID'];

    switch ($type) {
      case 'yearly':
        include(DIR_WS_INCLUDES . 'graphs/banner_yearly.php');
        echo tep_image(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banner_id . '.' . $banner_extension);
        break;
      case 'monthly':
        include(DIR_WS_INCLUDES . 'graphs/banner_monthly.php');
        echo tep_image(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banner_id . '.' . $banner_extension);
        break;
      default:
      case 'daily':
        include(DIR_WS_INCLUDES . 'graphs/banner_daily.php');
        echo tep_image(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banner_id . '.' . $banner_extension);
        break;
    }
?></p>
          <table class="table">
            <tr class="dataTableHeadingRow">
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SOURCE; ?></td>
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VIEWS; ?></td>
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CLICKS; ?></td>
           </tr>
<?php
    for ($i=0, $n=sizeof($stats); $i<$n; $i++) {
      echo '            <tr class="dataTableRow">' . "\n" .
           '              <td class="dataTableContent">' . $stats[$i][0] . '</td>' . "\n" .
           '              <td class="dataTableContent">' . number_format($stats[$i][1]) . '</td>' . "\n" .
           '              <td class="dataTableContent">' . number_format($stats[$i][2]) . '</td>' . "\n" .
           '            </tr>' . "\n";
    }
?>
          </table>
<?php
  } else {
    include(DIR_WS_FUNCTIONS . 'html_graphs.php');

    switch ($type) {
      case 'yearly':
        echo tep_banner_graph_yearly($_GET['bID']);
        break;
      case 'monthly':
        echo tep_banner_graph_monthly($_GET['bID']);
        break;
      default:
      case 'daily':
        echo tep_banner_graph_daily($_GET['bID']);
        break;
    }
  }
?>
         
        <p><?php echo '<a class="btn btn-default" href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . IMAGE_BACK . '</a>'; ?></p></td>
     
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
