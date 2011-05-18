<?php
/*
  $Id: specials.php,v 1.49 2003/06/09 22:35:33 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SPECIALS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<link href="static/product_listing.css" rel="stylesheet" type="text/css">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF Separate Price Per Customer
//  global variable (session): $sppc_customers_group_id -> local variable $customer_group_id

  if(!tep_session_is_registered('sppc_customer_group_id')) {
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

/*   $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC"; */

  $specials_query_raw = "select p.products_model,p.products_image,p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and s.customers_group_id = ". (int)$customer_group_id." order by s.specials_date_added DESC";
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);
// EOF Separate Price Per Customer
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);



  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
<?php
   // BOF Separate Price Per Customer

    $row = 0;
    $specials_query = tep_db_query($specials_split->sql_query);

    $no_of_specials = tep_db_num_rows($specials_query);
// get all product prices from the table products_groups in one query
// traverse specials_query for products_id's, store the query result in a numbered array
   while ($_specials = tep_db_fetch_array($specials_query)) {
	$specials[] = $_specials;
	$list_of_prdct_ids[] = $_specials['products_id'];
   } // end while ($_specials = tep_db_fetch_array($specials_query))
// a line needed for the selection of the products_id's
  $pg_list_of_prdct_ids = "products_id = '".$list_of_prdct_ids[0]."' ";
  if ($no_of_specials > 1) {
   for ($n = 1 ; $n < count($list_of_prdct_ids) ; $n++) {
   $pg_list_of_prdct_ids .= "or products_id = '".$list_of_prdct_ids[$n]."' ";
   }
}
// now get all the customers_group_price's
$pg_query = tep_db_query("select products_id, customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where (".$pg_list_of_prdct_ids.") and customers_group_id =  '" . $customer_group_id . "'");
// put all the info in an array called new_prices
	while ($pg_array = tep_db_fetch_array($pg_query)) {
	$new_prices[] = array ('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['customers_group_price']);
	}
// we already got the results from the query and put them into an array, can't use while now
//    while ($specials = tep_db_fetch_array($specials_query)) {
	for ($x = 0; $x < $no_of_specials; $x++) {
      $row++;
// replace products prices with those from customers_group table
        if(!empty($new_prices)) {
	    for ($i = 0; $i < count($new_prices); $i++) {
		    if( $specials[$x]['products_id'] == $new_prices[$i]['products_id'] ) {
			$specials[$x]['products_price'] = $new_prices[$i]['products_price'];
		    }
	    }
	} // end if(!empty($new_prices)

   //   echo '            <td align="center" width="33%" class="smallText"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $specials[$x]['products_image'], $specials[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials[$x]['products_id']) . '">' . $specials[$x]['products_name'] . '</a><br><s>' . $currencies->display_price($specials[$x]['products_price'], tep_get_tax_rate($specials[$x]['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . $currencies->display_price($specials[$x]['specials_new_products_price'], tep_get_tax_rate($specials[$x]['products_tax_class_id'])) . '</span></td>' . "\n";
 // EOF Separate Price per Customer, specials code
 $pf->parse($specials[$x]);
            $prod_price = $pf->getPriceStringShort();
print('
                <td width="15">&nbsp;</td>
                <td width="139"   valign="bottom" align=left><table width="139"  border="0" cellspacing="0" cellpadding="0">
				<tr>
                    <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
 
 <tr>
    <td class="producttext"><div align="center">ITEM:' . $specials[$x]['products_model'].'&nbsp;
    <span class="specialprice">' . $prod_price.'</span></div></td>
  </tr>
 
 
 
</table>

</td>
                  </tr>
                  <tr>
                    <td >');
					 if($specials[$x]['products_image']!="" && file_exists(DIR_WS_IMAGES.'/'.$specials[$x]['products_image']))
   {
  
   print('<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $specials[$x]['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $specials[$x]['products_image'], $specials[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>');
   }else
   {
   
   print("&nbsp;");
   }
					print('</td>
                  </tr>
                  <tr>

                    <td height="35" colspan="2" align="center" class="listing_pro_name"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $specials[$x]['products_id']) . '">'.$specials[$x]['products_name'].'</a></b></td>
                  </tr>
                  <tr>
                    <td height="35"><table width="122" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="82" class="moreinfobox"><a  class="moreinfo" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials[$x]['products_id']) . '">More Info </a></td>
                          <td width="40"><a class="buy" href="' . tep_href_link(basename($PHP_SELF), 'action=buy_now&products_id=' . $specials[$x]['products_id']) . '">BUY</a></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td> ');

      if ((($row / PRODUCT_LIST_NUMCOL) == floor($row /PRODUCT_LIST_NUMCOL))) {

	 print('</tr>
              <tr class="horzdot">
                <td colspan='. PRODUCT_LIST_NUMCOL * 2 .' >&nbsp;</td>
               
              </tr>');
      }
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
