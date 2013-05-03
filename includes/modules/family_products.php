<?php
/*
 $Id: family_products.php,v 3.30 2003/09/04 18:04:58 blueline Exp $

 CartStore eCommerce Software, for The Next Generation
 http://www.cartstore.com

 Copyright (c) 2008 Adoovo Inc. USA

 GNU General Public License Compatible
*/
?>

<?php

//This is where the code for the infoBox starts:
if ((FAMILY_DISPLAY_TYPE == 'Box') && (FAMILY_DISPLAY_FORMAT == 'Seperate')){

$family_name_query = tep_db_query("select pf.family_id, f.family_name from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_FAMILIES . " f where pf.family_id = f.family_id and pf.products_id = '" . $products_id . "'");

while ($family_results = tep_db_fetch_array($family_name_query)) {

 $info_box_contents = array();

 if (FAMILY_HEADER_FORMAT == 'Family Name') {
 $info_box_contents[] = array('text' => $family_results['family_name']);
 } else {
 if (FAMILY_HEADER_TEXT == '') {
 $info_box_contents[] = array('text' => '<span class="regularprice" style="padding-left: 10px">Product in this family</span>');

 } else { $info_box_contents[] = array('text' => '<table class="pageHeading2" width="100%" border="0" cellspacing="2" cellpadding="0"><tr><td class="pageHeading2" height=25><span class="regularprice" style="padding-left: 10px">Product in this family</span></td></tr></table>');
 }
 }

$family_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pf.family_id = '" . (int)$family_results['family_id'] . "' and p.products_id = pf.products_id and p.products_id != '" . $products_id . "'" . $where_clause2 . " order by p.products_date_added desc limit 12");
$total=tep_db_num_rows($family_query);

 if (tep_db_num_rows($family_query) > 0) {
 //new contentBoxHeading($info_box_contents);
new contentBox($info_box_contents);

print('<table width="515" border="0" cellspacing="0" cellpadding="0"><tr>');
 $row = 0;
 $col = 0;
 $count=1;
 $info_box_contents = array();
 $where_clause2 = '';
 while ($family = tep_db_fetch_array($family_query)) {
$where_clause2 .= " and p.products_id != '" . $family['products_id'] . "'";
   $family['products_name'] = tep_get_products_name($family['products_id']);
  
$info_box_contents[$row][$col] = array('align' => 'center',
                                          'params' => 'class="smallText" width="33%" valign="top"',
                                          'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $family['products_image'], $family['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">' . $family['products_name'] . '</a><br>' . $currencies->display_price($family['products_price'], tep_get_tax_rate($family['products_tax_class_id'])));
print('
                <td width="15">&nbsp;</td>
                <td width="159"  class="verproductline" valign="bottom" align=left><table width="159"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td >');
					 if($family['products_image']!="" && file_exists(DIR_WS_IMAGES.'/'.$family['products_image']))
   {
  
   print('<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $family['products_image'], $family['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>');
   }else
   {
   
   print("&nbsp;");
   }
					print('</td>
                  </tr>
                  <tr>
                    <td height="25"><div align="center"><span class="specialprice">' . $currencies->display_price($family['products_price'], tep_get_tax_rate($family['products_tax_class_id'])).'</span></div></td>
                  </tr>
                  <tr>
                    <td height="35"><table width="122" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="82" class="moreinfobox"><a  class="moreinfo" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">More Info </a></td>
                          <td width="40"><a class="buy" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')).'action=buy_now&products_id=' . $family['products_id']) . '">BUY</a></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td> ');
   $col ++;
   
   if ($col > ( PRODUCT_LIST_NUMCOL - 1) || $count == $total) {
     $col = 0;
	 if($total<PRODUCT_LIST_NUMCOL)
	 {
	 for($col_count=1;$col_count=(PRODUCT_LIST_NUMCOL-$total);$col_count++)
	 {
print('<td width="163"   valign="bottom">&nbsp;</td>');	 }
}
	 print('</tr>
              <tr>
                <td colspan="2">&nbsp;</td>
               
              </tr>');
     $row ++;
   }
   $count++;
 }
print('</table>');
 //new contentBox($info_box_contents);
//echo '<p>';
}
}
}


if ((FAMILY_DISPLAY_TYPE == 'Box') && (FAMILY_DISPLAY_FORMAT == 'Random')){


$family_name_query = tep_db_query("select family_id from " . TABLE_PRODUCTS_FAMILIES . " where products_id = '" . $products_id . "'");
if (tep_db_num_rows($family_name_query) > '0') {


 $info_box_contents = array();
 if (FAMILY_HEADER_TEXT == '') {
 $info_box_contents[] = array('text' => TABLE_HEADING_FAMILY_PRODUCTS);
 } else {
 $info_box_contents[] = array('text' => FAMILY_HEADER_TEXT);
 }
$family_name_num_rows = tep_db_num_rows($family_name_query);
$num_of_rows_less_one = $family_name_num_rows - '1';

if (tep_db_num_rows($family_name_query) == '1') {

	$family_results = tep_db_fetch_array($family_name_query);
	$family_query = mysql_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pf.family_id = '" . (int)$family_results['family_id'] . "' and p.products_id = pf.products_id and p.products_id != '" . $products_id . "' order by p.products_date_added desc limit 0,12" );

} else {

	$where_clause = '';
	for ($i=0; $i < tep_db_num_rows($family_name_query); $i++) {
		$family_results = tep_db_fetch_array($family_name_query);
		if ($i < $num_of_rows_less_one) {
		$where_clause .= "(pf.family_id = '" . $family_results['family_id'] . "') OR ";
		} else {
		$where_clause .= "(pf.family_id = '" . $family_results['family_id'] . "')";
		}
	}

$family_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and (" . $where_clause . ") and p.products_id = pf.products_id and p.products_id != '" . $products_id . "' limit " . MAX_DISPLAY_NEW_PRODUCTS);
}

 if (tep_db_num_rows($family_query) > 0) {
// new contentBoxHeading($info_box_contents);
 new contentBox($info_box_contents);


 $row = 0;
 $col = 0;
 $info_box_contents = array();
$where_clause2 = '';
   for ($i=0, $j=1; ($i < 12) && ($i < tep_db_num_rows($family_query)); $i++, $j++) {

 if (tep_db_num_rows($family_name_query) == '1') {
   $family = tep_random_select("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = pf.products_id and p.products_id != '" . $products_id . "'" . $where_clause2);
 } else {
   $family = tep_random_select("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and (" . $where_clause . ") and p.products_id = pf.products_id and p.products_id != '" . $products_id . "'" . $where_clause2);
 }
 $where_clause2 .= " and p.products_id != '" . $family['products_id'] . "'";


   $family['products_name'] = tep_get_products_name($family['products_id']);
$info_box_contents[$row][$col] = array('align' => 'center',
                                          'params' => 'class="smallText" width="33%" valign="top"',
                                          'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $family['products_image'], $family['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $family['products_id']) . '">' . $family['products_name'] . '</a><br>' . $currencies->display_price($family['products_price'], tep_get_tax_rate($family['products_tax_class_id'])));


   $col ++;
   if ($col > 2) {
     $col = 0;
     $row ++;
   }
 }

 new contentBox($info_box_contents);
echo '<p>';
}
}
}
?>
<?php

//This is where the code for the list starts:
if ((FAMILY_DISPLAY_TYPE == 'List') && (FAMILY_DISPLAY_FORMAT == 'Seperate')){

$family_name_query = tep_db_query("select pf.family_id, f.family_name from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_FAMILIES . " f where pf.family_id = f.family_id and pf.products_id = '" . $products_id . "'");
while ($family_results = tep_db_fetch_array($family_name_query)) {

$family_query = tep_db_query("select distinct p.manufacturers_id, p.products_id, p.products_image, p.products_tax_class_id, p.products_price, s.specials_new_products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pf.family_id = '" . (int)$family_results['family_id'] . "' and p.products_id = pf.products_id and p.products_id != '" . $products_id . "'" . $where_clause2 . " order by p.products_date_added desc limit 12" );

   $define_list = array('PRODUCT_LIST_NAME' => '2',//PRODUCT_LIST_NAME,
                        'PRODUCT_LIST_PRICE' => '3',//PRODUCT_LIST_PRICE,
                        'PRODUCT_LIST_IMAGE' => '1',//PRODUCT_LIST_IMAGE,
                        'PRODUCT_LIST_BUY_NOW' => '4');//PRODUCT_LIST_BUY_NOW);

   asort($define_list);

   $column_list = array();
   reset($define_list);
   while (list($key, $value) = each($define_list)) {
     if ($value > 0) $column_list[] = $key;
   }

$list_box_contents = array();
 if (tep_db_num_rows($family_query) > 0) {

 if (FAMILY_HEADER_FORMAT == 'Family Name') {
   $list_box_contents[0][0] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;' . $family_results['family_name'] . '&nbsp;');
 } else {
 if (FAMILY_HEADER_TEXT == '') {
   $list_box_contents[0][0] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;' . TABLE_HEADING_FAMILY_PRODUCTS . '&nbsp;');
 } else {
     $list_box_contents[0][0] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;' . FAMILY_HEADER_TEXT . '&nbsp;');
 }
 }

for($i=1; $i<4; $i++) {
   $list_box_contents[0][$i] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;');
}
 for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
   switch ($column_list[$col]) {
     case 'PRODUCT_LIST_NAME':
       $lc_text = TABLE_HEADING_PRODUCTS;
       $lc_align = '';
       break;
     case 'PRODUCT_LIST_PRICE':
       $lc_text = TABLE_HEADING_PRICE;
       $lc_align = 'right';
       break;
     case 'PRODUCT_LIST_IMAGE':
       $lc_text = TABLE_HEADING_IMAGE;
       $lc_align = 'center';
       break;
     case 'PRODUCT_LIST_BUY_NOW':
       $lc_text = TABLE_HEADING_BUY_NOW;
       $lc_align = 'center';
       break;
   }

   if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
     $lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
   }

   $list_box_contents[1][] = array('align' => $lc_align,
                                   'params' => 'class="productListing-heading"',
                                   'text' => '&nbsp;' . $lc_text . '&nbsp;');
 }
   $rows = 0;

 $where_clause2 = '';
   while ($listing = tep_db_fetch_array($family_query)) {
$where_clause2 .= " and p.products_id != '" . $listing['products_id'] . "'";
   $listing['products_name'] = tep_get_products_name($listing['products_id']);
     $rows++;

     if (($rows/2) == floor($rows/2)) {
       $list_box_contents[] = array('params' => 'class="productListing-even"');
     } else {
       $list_box_contents[] = array('params' => 'class="productListing-odd"');
     }

     $cur_row = sizeof($list_box_contents) - 1;

     for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
       $lc_align = '';

       switch ($column_list[$col]) {
         case 'PRODUCT_LIST_NAME':
           $lc_align = '';
           if (isset($_GET['manufacturers_id'])) {
             $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
           } else {
             $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_PRICE':
           $lc_align = 'right';
           if (tep_not_null($listing['specials_new_products_price'])) {
             $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
           } else {
             $lc_text = '&nbsp;' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_IMAGE':
           $lc_align = 'center';
           if (isset($_GET['manufacturers_id'])) {
             $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
           } else {
             $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_BUY_NOW':
           $lc_align = 'center';
           $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
           break;
       }

       $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                              'params' => 'class="productListing-data"',
                                              'text'  => $lc_text);
     }
   }

   new productListingBox($list_box_contents);
}
echo '<p>';
}
}


if ((FAMILY_DISPLAY_TYPE == 'List') && (FAMILY_DISPLAY_FORMAT == 'Random')) {
$family_name_query = tep_db_query("select family_id from " . TABLE_PRODUCTS_FAMILIES . " where products_id = '" . $products_id . "'");
if (tep_db_num_rows($family_name_query) > '0') {

$family_name_num_rows = tep_db_num_rows($family_name_query);
$num_of_rows_less_one = $family_name_num_rows - '1';

if (tep_db_num_rows($family_name_query) == '1') {
$family_results = tep_db_fetch_array($family_name_query);
$family_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pf.family_id = '" . (int)$family_results['family_id'] . "' and p.products_id = pf.products_id and p.products_id != '" . $products_id . "' order by p.products_date_added desc limit 12");
} else {
$where_clause = '';
$where_clause2 = '';
for ($i=0; $i < tep_db_num_rows($family_name_query); $i++) {
$family_results = tep_db_fetch_array($family_name_query);
if ($i < $num_of_rows_less_one) {
$where_clause .= "(pf.family_id = '" . $family_results['family_id'] . "') OR ";
} else {
$where_clause .= "(pf.family_id = '" . $family_results['family_id'] . "')";
}
}
$family_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS_FAMILIES . " pf, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and (" . $where_clause . ") and p.products_id = pf.products_id and p.products_id != '" . $products_id . "' limit 12");
}

   $define_list = array('PRODUCT_LIST_NAME' => '2',//PRODUCT_LIST_NAME,
                        'PRODUCT_LIST_PRICE' => '3',//PRODUCT_LIST_PRICE,
                        'PRODUCT_LIST_IMAGE' => '1',//PRODUCT_LIST_IMAGE,
                        'PRODUCT_LIST_BUY_NOW' => '4');//PRODUCT_LIST_BUY_NOW);

   asort($define_list);

   $column_list = array();
   reset($define_list);
   while (list($key, $value) = each($define_list)) {
     if ($value > 0) $column_list[] = $key;
   }

$list_box_contents = array();
 if (tep_db_num_rows($family_query) > 0) {

 if (FAMILY_HEADER_TEXT == '') {
   $list_box_contents[0][0] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;' . TABLE_HEADING_FAMILY_PRODUCTS . '&nbsp;');
 } else {
     $list_box_contents[0][0] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;' . FAMILY_HEADER_TEXT . '&nbsp;');
 }

for($i=1; $i<5; $i++) {
   $list_box_contents[0][$i] = array('align' => 'left',
                                  'params' => 'class="productListing-heading"',
                                  'text' => '&nbsp;');
}
 for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
   switch ($column_list[$col]) {
     case 'PRODUCT_LIST_NAME':
       $lc_text = TABLE_HEADING_PRODUCTS;
       $lc_align = '';
       break;
     case 'PRODUCT_LIST_PRICE':
       $lc_text = TABLE_HEADING_PRICE;
       $lc_align = 'right';
       break;
     case 'PRODUCT_LIST_IMAGE':
       $lc_text = TABLE_HEADING_IMAGE;
       $lc_align = 'center';
       break;
     case 'PRODUCT_LIST_BUY_NOW':
       $lc_text = TABLE_HEADING_BUY_NOW;
       $lc_align = 'center';
       break;
   }

   if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
     $lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
   }

   $list_box_contents[1][] = array('align' => $lc_align,
                                   'params' => 'class="productListing-heading"',
                                   'text' => '&nbsp;' . $lc_text . '&nbsp;');
 }
   $rows = 0;

   while ($listing = tep_db_fetch_array($family_query)) {
   $listing['products_name'] = tep_get_products_name($listing['products_id']);
     $rows++;

     if (($rows/2) == floor($rows/2)) {
       $list_box_contents[] = array('params' => 'class="productListing-even"');
     } else {
       $list_box_contents[] = array('params' => 'class="productListing-odd"');
     }

     $cur_row = sizeof($list_box_contents) - 1;

     for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
       $lc_align = '';

       switch ($column_list[$col]) {
         case 'PRODUCT_LIST_NAME':
           $lc_align = '';
           if (isset($_GET['manufacturers_id'])) {
             $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
           } else {
             $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_PRICE':
           $lc_align = 'right';
           if (tep_not_null($listing['specials_new_products_price'])) {
             $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
           } else {
             $lc_text = '&nbsp;' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_IMAGE':
           $lc_align = 'center';
           if (isset($_GET['manufacturers_id'])) {
             $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
           } else {
             $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
           }
           break;
         case 'PRODUCT_LIST_BUY_NOW':
           $lc_align = 'center';
           $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
           break;
       }

       $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                              'params' => 'class="productListing-data"',
                                              'text'  => $lc_text);
     }
   }

   new productListingBox($list_box_contents);
}
}
}


?>
