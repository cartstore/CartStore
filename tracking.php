<?php
/*
  tracking.php,v 2.1 2008/03/08 12:00:01

osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TRACKING);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_TRACKING, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" colspan="2"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- Begin USPS Form -->
          <tr>
            <td colspan="3" align="center" class="main"><?php echo TEXT_INFORMATION_USPS; ?></td>
          </tr>
          <tr>
            <td></td>
	        <td align="center" class="main"><table border="0" cellspacing="0" cellpadding="2">
	          <?php echo '<td align="right">'.tep_draw_form('tracking', TEXT_LINK_USPS , 'submit','target="NEW" ');
		      echo tep_draw_input_field('origTrackNum', '', 'size="35" maxlength="34" ID="Enter number from shipping receipt:"');
		      echo '</td><td align="left">'.tep_image_submit('button_track.gif', IMAGE_BUTTON_USPSTRACK, 'align=middle');
	          ?> 
            </form></table></td>
            <td></td>
          </tr>
<!-- End USPS Form -->
<!-- Begin UPS Form -->
          <tr>
            <td colspan="3" align="center" class="main"><?php echo TEXT_INFORMATION_UPS; ?></td>
          </tr>
          <tr>
            <td></td>
	        <td align="center" class="main"><table border="0" cellspacing="0" cellpadding="2">
	          <?php echo '<td align="right">'.tep_draw_form('tracking', TEXT_LINK_UPS , 'submit','target="NEW"');
	          echo tep_draw_input_field('InquiryNumber1', '', 'size="35"');
		      echo tep_draw_hidden_field('TypeOfInquiryNumber', INQUIRY_TYPE);
		      echo tep_draw_hidden_field('UPS_HTML_Version',HTML_VERSION);
		      echo tep_draw_hidden_field('IATA', DEFAULT_COUNTRY);
		      echo tep_draw_hidden_field('Lang', DEFAULT_LANGUAGE);
		      echo '</td><td align="left">'.tep_image_submit('button_track.gif', IMAGE_BUTTON_UPSTRACK, 'align=middle');
	          ?> 
	        </form></table></td>
            <td></td>
          </tr>
<!-- End UPS Form -->
<!-- Begin Fedex Form -->
          <tr>
            <td colspan="3" align="center" class="main"><?php echo TEXT_INFORMATION_FEDEX; ?></td>
          </tr>
          <tr>
            <td></td>
	        <td align="center" class="main"><table border="0" cellspacing="0" cellpadding="2">
	          <?php echo '<td align="right">'.tep_draw_form('tracking', TEXT_LINK_FEDEX , 'submit', 'target="NEW"');
		      echo tep_draw_input_field('tracknumbers', '', 'size="35"');
		      echo tep_draw_hidden_field('action', 'track');
		      echo tep_draw_hidden_field('language', 'english');
		      echo tep_draw_hidden_field('cntry_code', 'us');
		      echo tep_draw_hidden_field('mps', 'y');
		      echo '</td><td align="left">'.tep_image_submit('button_track.gif', IMAGE_BUTTON_FEDEXTRACK, 'align=middle');
	          ?> 
            </form></table></td>
            <td></td>
          </tr>
<!-- End Fedex Form -->
<!-- Begin DHL Form -->
          <tr>
            <td colspan="3" align="center" class="main"><?php echo TEXT_INFORMATION_DHL; ?></td>
          </tr>
          <tr>
            <td></td>
	        <td align="center" class="main"><table border="0" cellspacing="0" cellpadding="2">
	          <?php echo '<td align="right">'.tep_draw_form('tracking', TEXT_LINK_DHL , 'submit','target="NEW" ');
		      echo tep_draw_input_field('ShipmentNumber', '', 'size="35"');
		      echo tep_draw_hidden_field('action', 'track');
		      echo tep_draw_hidden_field('language', 'english');
		      echo tep_draw_hidden_field('cntry_code', 'us');
		      echo '</td><td align="left">'.tep_image_submit('button_track.gif', IMAGE_BUTTON_DHLTRACK, 'align=middle');
	          ?> 
	        </form></table></td>
            <td></td>
          </tr>
	    </table></td>
	  </tr>
<!-- End DHL Form -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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