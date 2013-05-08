<?php
/*
  $Id: view_families.php,v3.0 2003/09/16 11:51:52 blueline Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'view_family':
		$family = $_GET['family_id'];
		$family_products_query = tep_db_query("select distinct products_id from " . TABLE_PRODUCTS_FAMILIES . " where family_id = '" . $family . "'");
	  break;
    }
}

$family_query = tep_db_query("select * from families");
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
            <td class="pageHeading2" align="right"></td>
          </tr>
			<tr class="dataTableRow">
			<td class="dataTableContent">
			<?php if ((isset($_GET['action'])) && ($_GET['action'] == 'view_family')) {
					while ($family_products = tep_db_fetch_array($family_products_query)) {
						echo "<a href=\"" . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family_products['products_id'], 'SSL') . "\" target=\"_blank\"\">" . tep_get_products_name($family_products['products_id']) . "</a><br>";
					}
					echo "<br><a href=\"" . tep_href_link(FILENAME_VIEW_FAMILIES, '', 'SSL') . "\">Back to Families List</a>";
				  } else {
				    while ($families = tep_db_fetch_array($family_query)) {
						echo "<a href=\"" . tep_href_link(FILENAME_VIEW_FAMILIES, 'action=view_family&family_id=' . $families['family_id']) . "\">Family " . $families['family_name'] . "</a><br>";
					}
				  }
			?></td></tr>
		   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.png', '100%', '10'); ?></td>
      </tr>
    </table></td>
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