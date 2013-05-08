<?php
/*
  $Id: affiliate_popup_image.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  reset($_GET);
  while (list($key, ) = each($_GET)) {
    switch ($key) {
      case 'banner':
        $banners_id = tep_db_prepare_input($_GET['banner']);

        $banner_query = tep_db_query("select affiliate_banners_title, affiliate_banners_image, affiliate_banners_html_text from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($banners_id) . "'");
        $banner = tep_db_fetch_array($banner_query);

        $page_title = $banner['affiliate_banners_title'];

        if ($banner['affiliate_banners_html_text']) {
          $image_source = $banner['affiliate_banners_html_text'];
        } elseif ($banner['affiliate_banners_image']) {
          $image_source = tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $banner['affiliate_banners_image'], $page_title);
        }
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $page_title; ?></title>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i = 40;
  window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
}
//--></script>
</head>

<body onload="resize();">

<?php echo $image_source; ?>

</body>

</html>