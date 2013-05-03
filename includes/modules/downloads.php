<?php
/*
  $Id: downloads.php,v 1.3 2003/06/09 22:49:58 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
?>
<!-- downloads //-->
<?php
  if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
// Get last order id for checkout_success
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by orders_id desc limit 1");
    $orders = tep_db_fetch_array($orders_query);
    $last_order = $orders['orders_id'];
  } else {
    $last_order = $_GET['order_id'];
  }

// Now get all downloadable products in that order
  $downloads_query = tep_db_query("select o.orders_status, date_format(o.last_modified, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . (int)$customer_id . "' and o.orders_status >= " . DOWNLOADS_CONTROLLER_ORDERS_STATUS . " and o.orders_id = '" . (int)$last_order . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_filename != ''");
  if (tep_db_num_rows($downloads_query) > 0) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_DOWNLOAD; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<!-- list of products -->
<?php
    while ($downloads = tep_db_fetch_array($downloads_query)) {
// MySQL 3.22 does not have INTERVAL
      list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);

      $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
      $download_expiry = date('Y-m-d H:i:s', $download_timestamp);
?>
          <tr class="infoBoxContents">
<!-- left box -->
<?php
// The link will appear only if:
// - Download remaining count is > 0, AND
// - The file is present in the DOWNLOAD directory, AND EITHER
// - No expiry date is enforced (maxdays == 0), OR
// - The expiry date is not reached
      if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) {
// BOF Super Download Store v2.3.x mod
        $file_desc = 0;
        if (DOWNLOADS_CONTROLLER_FILEGROUP_STATUS == 'Yes') {
          // Check if a file description exists
          $file_query = tep_db_query("select padg2f.download_group_file_description
                                      from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_FILES . " padgf
                                      left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS_TO_FILES . " padg2f
                                      on padgf.download_groups_file_id = padg2f.download_groups_file_id
                                      where padgf.download_group_filename = '" . $downloads['orders_products_filename'] . "'
                                      and padg2f.language_id = '" . (int)$languages_id . "'");
          if (tep_db_num_rows($file_query) > 0) {
            $file_array = tep_db_fetch_array($file_query);
            if (tep_not_null($file_array['download_group_file_description'])) $file_desc = 1;
          }
        }
        $file_size = filesize(DIR_FS_DOWNLOAD . $downloads['orders_products_filename']);
        if ($file_size > (1024*1024)) {
          $factor = 1024*1024;
          $symbol = 'Mb';
        } else if ($file_size > 1024 ){
          $factor = 1024;
          $symbol = 'Kb';
        } else {
          $factor = 1;
          $symbol = 'Bytes';
        }
        switch ($symbol) {
          case 'Bytes' :
            $decimal = 0;
            break;
          default :
            $decimal = 2;
            break;
        }
        $file_size /= $factor;
        $file_size = number_format($file_size, $decimal) . $symbol;

        if ($file_desc == 1) {
          echo '            <td class="main" align="center">' . $downloads['products_name'] . ' - <a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&id=' . $downloads['orders_products_download_id']) . '">' . $file_array['download_group_file_description'] . '<br>' . tep_image_button('button_download.gif', 'Download') . '</a> - ' . $file_size . '</td>' . "\n";
        } else {
// WebMakers.com Added: Downloads Controller Show Button
          echo '            <td class="main" align="center"><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&id=' . $downloads['orders_products_download_id']) . '">' . $downloads['products_name'] . '<br>' . tep_image_button('button_download.gif', 'Download') . '</a> - ' . $file_size . '</td>' . "\n";
        }
// EOF Super Download Store v2.3.x mod
      } else {
        echo '            <td class="main">' . $downloads['products_name'] . '</td>' . "\n";
      }
?>
<!-- right box -->
<?php
// BOF: WebMakers.com Added: Downloads Controller
      echo '            <td class="main">' . TABLE_HEADING_DOWNLOAD_DATE . '<br>' . tep_date_long($download_expiry) . '</td>' . "\n" .
           '            <td class="main" align="right">' . $downloads['download_count'] . TABLE_HEADING_DOWNLOAD_COUNT . '</td>' . "\n" .
           '          </tr>' . "\n";
// EOF: WebMakers.com Added: Downloads Controller
    }
?>
        </table></td>
      </tr>
<?php
    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="smalltext" colspan="4"><p><?php printf(FOOTER_DOWNLOAD, '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . HEADER_TITLE_MY_ACCOUNT . '</a>'); ?></p></td>
      </tr>
<?php
    }
  }
  $downloads_check_query = tep_db_query("select o.orders_id, opd.orders_products_download_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.orders_id = opd.orders_id and o.orders_id = '" . (int)$last_order . "' and opd.orders_products_filename != ''");

if (tep_db_num_rows($downloads_check_query) > 0 && tep_db_num_rows($downloads_query) < 1) {
?>
      <tr>
        <td colspan="3" align="center" valign="top" class="main" height="30"><FONT FACE="Arial" SIZE=1 COLOR="FF000"><?php echo DOWNLOADS_CONTROLLER_ON_HOLD_MSG ?></FONT></td>
      </tr>
<?php } ?>
<!-- downloads_eof //-->
