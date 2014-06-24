<?php
/*
  $Id: product_info.php,v 4.1 2006/01/25 23:55:58 rigadin Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
Based on: Simple Template System (STS) - Copyright (c) 2004 Brian Gallagher - brian@diamondsea.com
STS v4.1 by Rigadin (rigadin@osc-help.net)
*/

	$products_id=intval($_GET['products_id']);
// Create variables for product ID, added in v4.0.6
$template['productsid'] = $product_info['products_id']; // Just for consistende with osC names
$template['product_popup_url'] = FILENAME_POPUP_IMAGE . "?pID=" . $product_info['products_id'];




// Start the "Add to Cart" form
    $template_pinfo['startform'] = tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
// Add the hidden form variable for the Product_ID
    $template_pinfo['startform'] .= tep_draw_hidden_field('products_id', $products_id);
    $template_pinfo['endform'] = "</form>";

// Get product information from products_id parameter
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    $template_pinfo['regularprice'] = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
	  $template_pinfo['regularpricestrike'] = "<s>" . $template_pinfo['regularprice'] . "</s>";
      $template_pinfo['specialprice'] = $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id']));
    } else {
      $template_pinfo['specialprice'] = '';
	  $template_pinfo['regularpricestrike'] = $template_pinfo['regularprice'];
    }

    $template_pinfo['productname'] = $product_info['products_name'];
    $template_pinfo['productmodel'] =  $product_info['products_model'];

if (tep_not_null($product_info['products_image'])) {
  $template_pinfo['imagesmall'] = tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"');
  $template_pinfo['imagelarge'] = tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), '','','');
  $template_pinfo['product_popup']= '<script language="javascript"><!--'."\n".
                                   'document.write(\'<a href="javascript:popupstsWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . (int)$products_id) . '\\\')">' . $template_pinfo['imagesmall'] . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>\');'."\n".
								   '//--></script>'."\n".
								   '<noscript>'."\n".
								   '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">'.$template_pinfo['imagesmall'] . '<br>' . TEXT_CLICK_TO_ENLARGE .'</a>'."\n".
								   '</noscript>'."\n";
} else {
  $template_pinfo['imagesmall'] ='';
  $template_pinfo['imagelarge'] ='';
  $template_pinfo['product_popup']='';
}

$template_pinfo['productdesc'] = stripslashes($product_info['products_description']);

// Get the number of product attributes (the select list options)
$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
$products_attributes = tep_db_fetch_array($products_attributes_query);
// If there are attributes (options), then...
if ($products_attributes['total'] > 0) {
  // Print the options header
  $template_pinfo['optionheader'] = TEXT_PRODUCT_OPTIONS;

  // Select the list of attribute (option) names
  $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

  while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
    $products_options_array = array();
    $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");

    // For each option name, get the individual attribute (option) choices
    while ($products_options = tep_db_fetch_array($products_options_query)) {
      $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);

      // If the attribute (option) has a price modifier, include it
      if ($products_options['options_values_price'] != '0') {
        $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
      }

    }

    // If we should select a default attribute (option), do it here
    if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
      $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
    } else {
      $selected_attribute = false;
    }

    $template_pinfo['optionnames'] .= $products_options_name['products_options_name'] . ':<br>';
    $template_pinfo['optionchoices'] .=  tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . "<br>";
  }
} else {
  // No options, blank out the template variables for them
  $template_pinfo['optionheader'] = '';
  $template_pinfo['optionnames'] = '';
  $template_pinfo['optionchoices'] = '';
}

// See if there are any reviews
$reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'");
$reviews = tep_db_fetch_array($reviews_query);
if ($reviews['count'] > 0) {
  $template_pinfo['reviews'] = TEXT_CURRENT_REVIEWS . ' ' . $reviews['count'];
} else {
  $template_pinfo['reviews'] = '';
}

// See if there is a product URL
if (tep_not_null($product_info['products_url'])) {
  $template_pinfo['moreinfolabel'] = TEXT_MORE_INFORMATION;
  $template_pinfo['moreinfourl'] = tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false);
} else {
  $template_pinfo['moreinfolabel'] = '';
  $template_pinfo['moreinfourl'] = '';
}

$template_pinfo['moreinfolabel'] = str_replace('%s', $template_pinfo['moreinfourl'], $template_pinfo['moreinfolabel']);

// See if product is not yet available
if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
  $template_pinfo['productdatelabel'] = TEXT_DATE_AVAILABLE;
  $template_pinfo['productdate'] = tep_date_long($product_info['products_date_available']);
} else {
  $template_pinfo['productdatelabel'] = TEXT_DATE_ADDED;
  $template_pinfo['productdate'] = tep_date_long($product_info['products_date_added']);
}

// Strip out %s values
$template_pinfo['productdatelabel'] = str_replace('%s.', '', $template['productdatelabel']);

// See if any product reviews
$template_pinfo['reviewsurl'] = tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params());
$template_pinfo['reviewsbutton'] = tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS);
$template_pinfo['addtocartbutton'] = tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART);

// See if any "Also Purchased" items. Feature added in v4.0.6
$sts->start_capture();
 if ((USE_CACHE == 'true') && empty($SID)) {
   echo tep_cache_also_purchased(3600);
 } else {
   include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
 }
$sts->stop_capture ('alsopurchased'); // Get the result to the main array
$template_pinfo['alsopurchased']= $sts->template['alsopurchased']; // Put it in the product info

?>