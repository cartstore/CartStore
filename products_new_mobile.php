<?php
/*
  $Id: products_new.php,v 1.27 2003/06/09 22:35:33 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_NEW));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
   <div id="products_new">
<?php
  $products_new_array = array();
  
  
	$column_list= array('PRODUCT_LIST_PRICE','PRODUCT_LIST_IMAGE','PRODUCT_LIST_BUY_NOW','PRODUCT_LIST_NAME');
	if ( (!isset($_GET['sort_id'])) ) {
     $listing_sql =  " order by pd.products_name ";  
    } else {
           switch ($_GET['sort_id']) {
        case 'low':
          $listing_sql = " order by p.products_price ";
          break;
        case 'high':
          $listing_sql = " order by p.products_price desc";
          break;
        case 'title':
          $listing_sql =  " order by pd.products_name ";
          break;
       }
      }
  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image,p.map_price, p.msrp_price, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_PRODUCTS_NEW == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' ".$listing_sql;
  
  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);
//print($products_new_query_raw);
  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<div class="clear"></div>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="main" align="center">
      </td>
		    <td class="smallText">&nbsp;</td>
          </tr>
        </table>

<div class="clear"></div>
<?php
  }
?>

            <h1><?php echo HEADING_TITLE; ?></h1>


<!------------------------------------------------------------------------------------->

<?php
  $products_new_array = array();
if ( (!isset($_GET['sort_id'])) ) {
     $listing_sql =  " order by pd.products_name ";  
    } else {
           switch ($_GET['sort_id']) {
        case 'low':
          $listing_sql = " order by p.products_price ";
          break;
        case 'high':
          $listing_sql = " order by p.products_price desc";
          break;
        case 'title':
          $listing_sql =  " order by pd.products_name ";
          break;
       }
      }
  $listing_sql = "select p.products_id, pd.products_name, p.products_image,p.map_price, pd.products_short, p.msrp_price, p.products_price,p.products_model, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where " . (YMM_FILTER_PRODUCTS_NEW == 'Yes' ? $YMM_where : '') . " p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' ".$listing_sql;
  
  
//  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_MOBILE); 
		?>
<?php
if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<div class="clear"></div>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
  <td class="smallText">&nbsp;</td>
  
</tr>
</table>
<div class="clear"></div>
<?php
}
?>		
		</div>

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
