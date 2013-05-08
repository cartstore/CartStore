<?php
/*
  Copyright (C) 2009 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Google Checkout v1.5.0
 * $Id$
 *
 * Dashboard page for Google Checkout configuration.
 *
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

require('includes/application_top.php');

require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/option_renderer.php');
require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_options.php');

$options = new GoogleOptions();
$option_renderer = new GoogleOptionRenderer();

// If this was an update, parse the results and update.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  foreach ($options->getAllOptions() as $option) {
    $key = $option->getKey();
    if ($option->getOptionType() == "carrier_calculated_shipping"
        || $option->getOptionType() == "merchant_calculated_shipping") {
      $all_values = array();
      foreach ($_POST as $a => $b) {
        if (strpos($a, $key) === 0) {
          if ($b != '') {
            $all_values[] = $b;
          }
        }
      }
      $option->setValue(join(", ", $all_values));
    } else if ($option->getOptionType() == "boolean") {
      $option->setValue(!is_null($_POST[$key]));
    } else if (!is_null($_POST[$key])) {
      $value = $_POST[$key];
      $option->setValue($value);
    }
  }

  // Redirect to this page via GET.
  header('Location: ' . $_SERVER['PHP_SELF']);
}

// TODO(eddavisson): Hacky!
function get_response_handler_url() {
  $dummy = 'a';
	$admin_folder = tep_href_link($dummy);
  $catalog_folder = (ENABLE_SSL == 'true') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER;
  return $catalog_folder . '/googlecheckout/responsehandler.php';
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../googlecheckout/library/configuration/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="../googlecheckout/library/configuration/shipping_options.js"/>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading">Google Checkout Module Dashboard</td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
                  <td class="dataTableHeadingContent">Google Checkout Module Dashboard</td>
                </tr>
                  <td class="dataTableContent">

                    <!-- Begin Dashboard -->
                    <div class="container">
                      <span class="pagedescription">This page contains additional configuration options for the Google Checkout osCommerce module.</span><br/>
                      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <input type="hidden" name="isupdate" value="true"/>
                        <table class="config">
                          <tr class="section"><td colspan="2">Integration</td></tr>
                          <tr>
                            <td colspan="2">
                              <span class="title">API callback URL</span><br/>
                              <span class="description">
                                For Level 2 integration, copy and paste this URL into the "API callback URL" field in the Google Checkout Merchant Center
                                under "Integration->Settings" (links to this page in
                                <a class="google" href="https://sandbox.google.com/checkout/sell/settings?section=Integration" target="_blank">Sandbox</a> and
                                <a class="google" href="https://checkout.google.com/sell/settings?section=Integration" target="_blank">Production</a>):</span><br/><br/>
                              <span class="copypaste"><?php echo(get_response_handler_url()); ?></span>
                            </td>
                          </tr>
                          <tr class="section"><td colspan="2">Recommended Options</td></tr>
                          <?php
                            foreach ($options->getRecommendedOptions() as $option) {
                              echo($option_renderer->render($option));
                            }
                          ?>
                          <tr class="section"><td colspan="2">Shipping Options</td></tr>
                          <?php
                            foreach ($options->getShippingOptions() as $option) {
                              echo($option_renderer->render($option));
                            }
                          ?>
                          <tr class="section"><td colspan="2">Rounding Options</td></tr>
                          <?php
                            foreach ($options->getRoundingOptions() as $option) {
                              echo($option_renderer->render($option));
                            }
                          ?>
                          <tr class="section"><td colspan="2">Other Options</td></tr>
                          <?php
                            foreach ($options->getOtherOptions() as $option) {
                              echo($option_renderer->render($option));
                            }
                          ?>
                        </table>
                        <input type="submit" value="Save"/>
                      </form>
                    </div>
                    <!-- End Dashboard -->

                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table></td>
      </tr>
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
