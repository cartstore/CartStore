<?php
/*
  Copyright (C) 2007 Google Inc.

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
 * TODO(eddavisson): This class is illegible. Refactor.
 */

include_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

$cart->reset(true);

$products_list = tep_db_input(implode(',', explode(',', !empty($_GET['products_id'])?$_GET['products_id']:'-1')));
$product_check_query = tep_db_query(
    "select count(*) as total from "
        . TABLE_PRODUCTS . " p, "
        . TABLE_PRODUCTS_DESCRIPTION . " pd"
        . " where p.products_status = '1'"
        . " and p.products_id in (" . $products_list . ")"
        . " and pd.products_id = p.products_id"
        . " and pd.language_id = '" . (int)$languages_id . "'");
$product_check = tep_db_fetch_array($product_check_query);
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
    <table class="productListing" width="100%" cellspacing="0" cellpadding="1" border="0">
      <tr valign="top">
        <td valign="top" align="center">Thank you for buying with</td>
      </tr>
      <tr valign="top">
        <td valign="top" align="center"><img src="http://checkout.google.com/seller/images/google_checkout.gif" /></td>
      </tr>
      <tr valign="top">
        <td class="productListing-heading">You have just bought</td>
      </tr>
<?php

  if ($product_check['total'] < 1) {
?>
      <tr>
        <td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
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
<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id in (" . $products_list . ") and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    while ($product_info = tep_db_fetch_array($product_info_query)) {
      if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
      }

      if (tep_not_null($product_info['products_model'])) {
        $products_name = $product_info['products_name'] . '&nbsp&nbsp&nbsp<span class="smallText">[' . $product_info['products_model'] . ']</span>';
      } else {
        $products_name = $product_info['products_name'];
      }
?>
        <tr class="productListing-odd">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading" valign="top"><?php echo $products_name; ?></td>
<!--              <td class="pageHeading" align="right" valign="top"><?php echo $products_price; ?></td> -->
            </tr>
          </table></td>
        </tr>
        <tr>
          <td class="main">
<?php
      if (0 && tep_not_null($product_info['products_image'])) {
?>
            <table border="0" cellspacing="0" cellpadding="2" align="right">
              <tr>
                <td align="center" class="smallText">
  <script language="javascript"><!--
  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
  //--></script>
  <noscript>
  <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
  </noscript>
                </td>
              </tr>
            </table>
<?php
      }
    }
  }
?>
        </td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
          <?php

//          $products = '7,19,20,21,22';
/*
            if (isset($products)) {
              $orders_query = tep_db_query("select p.products_id, p.products_image from " .
                         "" . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " .
                         "" . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id in " .
                         "(" . $products_list . ")" .
                         " and opa.orders_id = opb.orders_id and opb.products_id not in " .
                         "(" . $products_list . ") and opb.products_id " .
                         "= p.products_id and opb.orders_id = o.orders_id and p.products_status " .
                         "= '1' group by p.products_id order by o.date_purchased " .
                         "desc limit " . MAX_DISPLAY_ALSO_PURCHASED);


              $num_products_ordered = tep_db_num_rows($orders_query);
              if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
          ?>
          <!-- also_purchased_products //-->
          <?php
                $info_box_contents = array();
                $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);

                new contentBoxHeading($info_box_contents);

                $row = 0;
                $col = 0;
                $info_box_contents = array();
                while ($orders = tep_db_fetch_array($orders_query)) {
                  $orders['products_name'] = tep_get_products_name($orders['products_id']);
                  $info_box_contents[$row][$col] = array('align' => 'center',
                                                         'params' => 'class="smallText" width="33%" valign="top"',
                                                         'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a>');
                  $col ++;
                  if ($col > 2) {
                    $col = 0;
                    $row ++;
                  }
                }

                new contentBox($info_box_contents);
          ?>
          <!-- also_purchased_products_eof //-->
          <?php
              }
            }
*/          ?>
        </td>
      </tr>
    </table></form></td>
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
