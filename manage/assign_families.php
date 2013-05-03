<?php
/*
  $Id: assign_families.php,v3.0 2003/09/16 11:51:52 blueline Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

/*
A file to show all products in a family - cart and admin
Ability to show "random" fam. prods as well as "groups" of fams - for products with more than 1 family attachment.
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_family':
        if (isset($_POST['family_id'])) $family_id = tep_db_prepare_input($_GET['family_id']);

				for ($i=1; $i < '11'; $i++) {
					if ($_GET['prod_selected' . $i] != '') {
					$products_id = $_GET['prod_selected' . $i];
					$prod_check = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_FAMILIES . " where family_id = '" . (int)$family_id . "' and products_id = '" . (int)$products_id . "'");			while ($prod = tep_db_fetch_array($prod_check)) {
					$prod_results = tep_db_fetch_array($prod_check);
						if ($prod_results['total'] < '1') {
							tep_db_query("insert into " . TABLE_PRODUCTS_FAMILIES . " (family_id, products_id) values ('" . (int)$family_id . "', '" . (int)$products_id . "')");
						}

					}
				}
			}
        tep_redirect(tep_href_link(FILENAME_ASSIGN_FAMILIES, 'action=success'));
        break;
		case 'success':
		$msg .= "Your products have been assigned to their associated families.";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo TITLE; ?></title>

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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h3><?php echo HEADING_TITLE; ?></h3></td>
            <td class="pageHeading"><?php echo $TITLE1; ?></td>
            <td class="pageHeading2" align="right"></td>
          </tr>
<?php if ((isset($_GET['action'])) && ($_GET['action'] == 'success')) { ?> 
		    <tr>
		    <td class="pageHeading"><?php echo "<font color=\"red\">" . $msg . "</font>"; ?></td>
			</tr>
<?php } ?>
			<tr class="dataTableRow">
			<td class="dataTableContent">
			<?php 
			echo tep_draw_form('update_family', FILENAME_ASSIGN_FAMILIES, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'update_family');
			echo tep_get_family_list('family_id') . '<br>'; 
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected1') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected2') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected3') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected4') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected5') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected6') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected7') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected8') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected9') . '<br>';
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . tep_get_product_list('prod_selected10') . '<br>';																											
			echo 
			?></td></tr>
		   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.png', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_confirm.png', 'Confirm'); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.png', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
		  <?php echo $test; ?>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>