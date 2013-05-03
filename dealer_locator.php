<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:37:59 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/



  require('includes/application_top.php');
  require('includes/functions/categories_description.php');
  global $customer_group_id;
  if(!isset($customer_group_id)) { $customer_group_id = '0'; }

// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
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
<div id="preloader">
    <div id="preloadIMG"><img src="loading.gif" alt="" /></div>
</div>

<!-- header //-->
<?php

require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
	    </table>
	</td>
<!-- body_text //-->
<?php
  if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");

    $category = tep_db_fetch_array($category_query);
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td>

			<table border="0" width="100%" cellspacing="0" cellpadding="2"><div class="cat_top">Choose a Sub Category in <?php print($category['categories_name']);?></div>


			  <tr>
<?php
    if (isset($cPath) && strpos('_', $cPath)) {
		// check to see if there are deeper categories within the current category
      	$category_links = array_reverse($cPath_array);
      	for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        	$categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        	$categories = tep_db_fetch_array($categories_query);
			if ($categories['total'] < 1) {
			  // do nothing, go through the loop
			} else {
			  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
			  break; // we've found the deepest category the customer is in
			}
	      } // end of for
    } else {


		 $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    }

    $number_of_categories = tep_db_num_rows($categories_query);
    $rows = 0;



	while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id']);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

	  //echo '<td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
	  //if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
	  //  echo '              </tr>' . "\n";
	  //  echo '              <tr>' . "\n";


	 $category_header  ="";
	 $category_description="";
     // Get Category Header from DB if allowed
      if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
         $category_header  = tep_get_category_heading_title((int)$categories['categories_id']);
             if ( strlen($category_header)==0 ) {
            // DB-Category Header is empty: use the default Category-Name
                $category_header  = $categories['categories_name'];
              }
      }
     // echo  '<br>'.$category_header;

	  // Get Category Description from DB if allowed

/*
	  if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
		$category_description = tep_get_category_description((int)$categories['categories_id']);
	   if (tep_not_null($category_description)) {
		   echo  '<div class="category_desc">' .
			$category_description .
			'</div>' . "\n";
		}
	  }
*/
echo "";


	echo '<td align="center" class="cat_td" width="' . $width . '" valign="top">';


	echo '<table class="cat_border" border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';

	echo '<td>';

	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	echo '<tr>';
	echo '<td>';

	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >';
	echo '<tr>';
	echo '<td ></td>';
	echo '<td><span class="catfont"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'.$categories['categories_name'].'</a></span></td>';
	echo '<td ></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td></td>';
	echo '<td>';
	//echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories[categories_image], $categories[categories_name],124,100);

	echo '<a class="cat_image" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT). '</a>';


	echo '</td>';
	echo '<td></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td></td>';
	echo '<td></td>';
	echo '<td></td>';
	echo '</tr>';

	echo '</table>';

	echo '</td>';
	echo '</tr>';
	echo '</table>';

	//echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'],138,65);

	 $category_header  ="";
	 $category_description="";
     // Get Category Header from DB if allowed
      if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
         $category_header  = tep_get_category_heading_title((int)$categories['categories_id']);
             if ( strlen($category_header)==0 ) {
            // DB-Category Header is empty: use the default Category-Name
                $category_header  = $categories['categories_name'];
              }
      }
    //  echo  '<br>'.$category_header;

	  // Get Category Description from DB if allowed
	  if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
		$category_description = tep_get_category_description((int)$categories['categories_id']);
	  // if (tep_not_null($category_description)) {
	//	   echo  '<div class="category_desc">' .
	//		$category_description .
	//		'</div>' . "\n";
	//	}
	  }

	//   <!-- Tom 002: End -->
	echo '</a>';

	echo '</td>';

	echo '</tr>';
	echo '<tr>';
	echo '<td align="center" class="border">';


	echo '</td>';
	echo '</tr>';
	echo '</table>';

	echo '</td>' . "\n";
    if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
       echo '</tr>' . "\n";
       echo '<tr>' . "\n";
     }
  }
  print('</tr><tr><td colspan='. MAX_DISPLAY_CATEGORIES_PER_ROW .'>');
  ///// Count proudct in a current Category/////////
  $products_in_cat_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$current_category_id . "'");
  $products_in_cat = tep_db_fetch_array($products_in_cat_query);
  if($products_in_cat['total'] == 0)
  {
  $new_products_category_id = $current_category_id;
 include_once(DIR_WS_MODULES . FILENAME_FEATURED);
  }
  print('</td></tr>');
  //////////////

// needed for the new products module shown below
 //   $new_products_category_id = $current_category_id;
?>
              </tr>
            </table>

			</td>
          </tr>

          <tr>
            <td></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php
  } elseif ($category_depth == 'products' || isset($_GET['manufacturers_id'])) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
						   //bof column product listing with attributes
						 'PRODUCT_LIST_MULTIPLE' => PRODUCT_LIST_MULTIPLE,
						 'PRODUCT_LIST_BUY_NOW_MULTIPLE' => PRODUCT_LIST_BUY_NOW_MULTIPLE,
						 //eof column product listing with attributes
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

	// BOF Separate Pricing Per Customer
   if(!tep_session_is_registered('sppc_customer_group_id')) {
     $customer_group_id = '0';
     } else {
      $customer_group_id = $sppc_customer_group_id;
   }
   // this will build the table with specials prices for the retail group or update it if needed
   // this function should have been added to includes/functions/database.php
   if ($customer_group_id == '0') {
   tep_db_check_age_specials_retail_table();
   }
   $status_product_prices_table = false;
   $status_need_to_get_prices = false;

   // find out if sorting by price has been requested
   if ( (isset($_GET['sort'])) && (preg_match('/[1-8][ad]/', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){
    $_sort_col = substr($_GET['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
      }
   }

   if ($status_need_to_get_prices == true && $customer_group_id != '0') {
   $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id;
   // the table with product prices for a particular customer group is re-built only a number of times per hour
   // (setting in /includes/database_tables.php called MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE, in minutes)
   // to trigger the update the next function is called (new function that should have been
   // added to includes/functions/database.php)
   tep_db_check_age_products_group_prices_cg_table($customer_group_id);
   $status_product_prices_table = true;

   } // end if ($status_need_to_get_prices == true && $customer_group_id != '0')
// EOF Separate Pricing Per Customer




    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
	 if ( ($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_PRICE') ) {
        continue;
      }
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
      }
    }

// show the products of a specified manufacturer
    if (isset($_GET['manufacturers_id'])) {
      if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {

// We are asked to show only a specific category
// BOF Separate Pricing Per Customer
	if ($status_product_prices_table == true) {
	$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
	} else { // either retail or no need to get correct special prices
	$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
	} // end else { // either retail...
// EOF Separate Pricing Per Customer
      } else {
// We show them all
// BOF Separate Pricing Per Customer
        if ($status_product_prices_table == true) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
	} else { // either retail or no need to get correct special prices
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m  left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
	} // end else { // either retail...
// EOF Separate Pricing Per Customer
      }
    } else {
// show the products in a given categorie
      if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only specific catgeory;
// BOF Separate Pricing Per Customer
        if ($status_product_prices_table == true) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } else { // either retail or no need to get correct special prices
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS_RETAIL_PRICES . " s using(products_id) where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } // end else { // either retail...
// EOF Separate Pricing Per Customer
      } else {
// We show them all
// BOF Separate Pricing Per Customer
        if ($status_product_prices_table == true) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } else { // either retail or no need to get correct special prices
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
      } // end else { // either retail...
// EOF Separate Pricing per Customer




      }
    }

    if ( (!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
          $_GET['sort'] = $i+1 . 'a';
          $listing_sql .= " order by pd.products_name";
          break;
        }
      }
    } else {
      $sort_col = substr($_GET['sort'], 0 , 1);
      $sort_order = substr($_GET['sort'], 1);
      $listing_sql .= ' order by ';
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $listing_sql .= "pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
      }
    }
?>
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>

			<table border="0" width="100%" cellspacing="0" cellpadding="0">
          		<tr>

            <?php

			 // Get Category Header from DB if allowed
              if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
                $category_header  = tep_get_category_heading_title((int)$current_category_id);
                if ( strlen($category_header)==0 ) {
                  // DB-Category Header is empty: use the default Category-Name
                  $category_header  = HEADING_TITLE;
                }
              }



/*
			  echo  $category_header;

              // Get Category Description from DB if allowed
              if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
                $category_description = tep_get_category_description((int)$current_category_id);
                if (tep_not_null($category_description)) {
                  echo  '<br>';
                  echo  '<div class="category_desc">' .
                        $category_description .
                        '</div>' . "\n";
                 }
              }

*/

// optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '';
        if (isset($_GET['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', $_GET['sort']);

        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form></td>' . "\n";
      }
    }

// Get the right image for the top-right
    $image = DIR_WS_IMAGES . '';
    if (isset($_GET['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
				<td align="right"></td>
			  </tr>
			</table>

		</td>
      </tr>
      <tr>
        <td><?php
			include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
			//include(DIR_WS_MODULES . 'product_listing.php');
		?></td>
      </tr>
    </table>

	</td>
<?php
  } else { // default page
?>
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td></td>
                  <td>&nbsp;</td>
                  <td><div align="right"></div></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td rowspan="4"></td>
            <td width="100%" colspan="2" class="infoBoxContents"><FORM METHOD="POST" ACTION="dealer_locator.php">
                <INPUT TYPE="HIDDEN" NAME="action" VALUE="search">
                <table border="0" width="100%">
                  <tr>
                    <td>Enter your Zip Code:</td>
                    <td><INPUT TYPE="TEXT" NAME="zip" SIZE=6></td>
                  </tr>
                  <tr>
                    <td>Distance from your Location:</td>
                    <td><SELECT NAME="distance">
                        <OPTION VALUE="5">5 miles</OPTION>
                        <OPTION VALUE="10">10 miles</OPTION>
                        <OPTION VALUE="25">25 miles</OPTION>
                        <OPTION VALUE="50">50 miles</OPTION>
                        <OPTION VALUE="100">100 miles</OPTION>
                        <OPTION VALUE="200">200 miles</OPTION>
                        <OPTION VALUE="300">300 miles</OPTION>
                      </SELECT>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><input type="submit" class="button" NAME="Submit" VALUE="Search">
                        </td.
	>
                    </tr>
                </table>
            </FORM>
                <?php
if ($action == "search") {
	?>
                <?php

$sql1 = "SELECT lat AS latitude_one, lon AS longitude_one FROM zipcodes WHERE zipcode = '$zip'";
		$result1 = tep_db_query($sql1);
		$row1 = mysql_fetch_array($result1);

		$latitude_one = $row1["latitude_one"];
		$longitude_one = $row1["longitude_one"];

//		echo ("<br>".$latitude_one. " Latitude");
//		echo ("<br>".$longitude_one." Longitude");
//		echo (" <br>" . $distance." Miles");

		$top_latitude =  abs($latitude_one + ($distance/68.703));
		$bottom_latitude =  abs($latitude_one - ($distance/68.703));
		$top_longitude = abs($longitude_one + ($distance/(69.172*(cos($latitude)))));
		$bottom_longitude = abs($longitude_one - ($distance/(69.172*(cos($latitude)))));

//		echo ("<br>" .$top_latitude." Top Latitude");
//		echo ("<br>" .$bottom_latitude." Bottom Latitude");


//		echo ("<br>" .$top_longitude." Top longitude");
//		echo ("<br>" .$bottom_longitude." Bottom longitude");
//		echo "---------------------------------------";
		$sql2= "SELECT DISTINCT zipcode FROM zipcodes WHERE lat < $top_latitude AND lat > $bottom_latitude AND lon < $top_longitude AND lon > $bottom_longitude";

		$result2 = tep_db_query($sql2);
		$row2 = mysql_fetch_array($result2);
		$total = mysql_num_rows($result2);
		/*for ($k = 0; $k < $total; $k++){
			echo "results: " . $row2[$k] . "<br><bR>";
		}*/


//		echo ("<br>" .$total." NUM ROWS");

/*		while($field=mysql_fetch_array($result2)) {
			echo "zip: " .$field[zipcode] . " -- name: " . $field[name] . "<br><br>";
			}
*/

		//-----------Compute Distance Between Latitudinal and Longitudinal Coordinates
		//		1 degree latitude = 110.6 km = 68.703 mi
		//		1 degree longitude = 111.3km * cos(latitude) = 69.172mi * cos(latitude)

		//
		//		$coord_computation = sin($latitude_one/57.3) * sin($latitude_two/57.3) +
    	//		cos($latitude_one/57.3) * cos($latitude_two/57.3) *
        //		cos($latitude_two/57.3 - $latitude_one/57.3)
		//		$distance_between = 3959 * acos($coord_computation)

		//-----------END COMPUTATION
?>
                <?php
$cnt = 0;
while($field=mysql_fetch_array($result2)) {
	$dealer_sql = "SELECT * FROM dealers WHERE dealer_zip = " . $field[zipcode];
	$dealer = tep_db_query($dealer_sql);
	while($field2=mysql_fetch_array($dealer)) {
		echo '<b>' . $field2[dealer_name] . '</b>' . '<br>';
		echo $field2[dealer_address1] . '<br>';
		echo $field2[dealer_address2] . '<br>';
		echo $field2[dealer_city] . ', ' . $field2[dealer_state] . ' ' . $field2[dealer_zip] . '<br>';
		echo 'Phone:  ' .$field2[dealer_phone] . '<br>';
		echo 'Fax: ' . $field2[dealer_fax] . '<br>';
		echo 'Email: <a href="mailto:' . $field2[dealer_email] . '">' . $field2[dealer_email] . '</a><br>';
		echo 'Website: <a href="' . $field2[dealer_url] . '">' . $field2[dealer_url] . '</a><br><br>';
		$cnt++;
		}
	}

if ($cnt == 0) {
	echo '<p>No results found, please expand your search area, or choose a different zip code.</p>';
	}
?>
                <?php
	}
	?>
            </td>
            <td rowspan="4"></td>
          </tr>
          <tr>
            <td colspan="2" class="infoBoxContents">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="infoBoxContents"><strong>Internet Based Dealers: </strong></td>
          </tr>
          <tr>
            <td class="infoBoxContents"> Control &amp; Power Equipment </td>
            <td class="infoBoxContents"><a href="http://www.metalfabtools.com">http://www.metalfabtools.com</a></td>
          </tr>
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top"></td>
                  <td></td>
                  <td  valign="top"><div align="right"></div></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>

	</td>
<?php
  }
?>
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