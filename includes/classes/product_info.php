<?php
/*
  $Id: product_info.php,v 1.97 2003/07/01 14:34:54 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  // BOF Separate Price per Customer
     if(!tep_session_is_registered('sppc_customer_group_id')) {
     $customer_group_id = '0';
     } else {
      $customer_group_id = $sppc_customer_group_id;
     }
   // EOF Separate Price per Customer

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script language="javascript"><!--
function popupWindow(url) {
 window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,res
izable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,l
eft=150')
}
//--></script>
<link href="/css/css.css" rel="stylesheet" type="text/css">
<!-- Begin tab pane //-->
<script type="text/javascript" src="includes/tabs/webfxlayout.js"></script>
<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="includes/tabs/tabpanewebfx.css" />
<script type="text/javascript" src="includes/tabs/tabpane.js"></script>
<!-- End tab pane //-->

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
	 <td width="<?php echo BOX_WIDTH; ?>" valign="top">
  		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
      		<!-- left_navigation //-->
      		<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
      		<!-- left_navigation_eof //--></tr></td>
  		</table>
  	</td>
<!-- body_text //-->
    <td width="100%" valign="top">
  		<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
  	<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <?php
  if ($product_check['total'] < 1) {
?>
    <tr>
      <td><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
    </tr>
    <tr>
      <td>
	  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
		</td>
    </tr>
    <?php
  } else {
  // BOF Separate Price per Customer, Price Break 1.11.3 mod
    $product_info_query = tep_db_query("select p.products_id,pd.products_info_title,p.products_status,pd.products_info_desc, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, NULL as specials_new_products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_qty_blocks, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
// EOF Separate Price per Customer, Price Break mod



    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
// BOF Separate Pricing per Customer, Price Break 1.11.3 mod
   $pf->loadProductSppc((int)$_GET['products_id'], (int)$languages_id, $product_info);
   $products_price = $pf->getPriceString();

// EOF Separate Pricing per Customer, Price Break 1.11.3 mod
$products_status = $product_info['products_status'];

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'];

    } else {
      $products_name = $product_info['products_name'];
    }
	//DISPLAY PRODUCT WAS ADDED TO WISHLIST IF WISHLIST REDIRECT IS ENABLED
	if(tep_session_is_registered('wishlist_id')) {
?>
	  <tr>
		<td class="messageStackSuccess"><?php echo PRODUCT_ADDED_TO_WISHLIST; ?></td>
	  </tr>
<?php
		tep_session_unregister('wishlist_id');
	}
?>

    <tr>
      <td><?php
    if (tep_not_null($product_info['products_image'])) {
//++++ QT Pro: Begin Changed code
?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>

<link href="templates/system/product_page.css" rel="stylesheet" type="text/css">

 <td valign="top" colspan="2"><!-- desc area -->
<table width="100%" class="prodborder" border="0">
     <tr>
        <td width="260"><div align="left"><?php echo $breadcrumb->trail(' &raquo; '); ?></div></td>
        <td width="257"><div align="right" class="textup">Item Number: <?php echo  $product_info['products_model'];?></div></td>
      </tr>
      <tr>
        <td colspan="2" class="horzdot"></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="407" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="15"><img src="images/send.gif" alt="" width="15" height="11"></td>
            <td width="90"><div align="center">		<?php echo '<a  class="linkup2" href="' . tep_href_link(FILENAME_TELL_A_FRIEND, tep_get_all_get_params()) . '">Send to a friend</a>'; ?></div>

			</td>
            <td width="10" class="verdot">&nbsp;</td>
            <td width="62"><div align="center"><a href="#" class="linkup2" onClick="print_option();">Print Page</a></div></td>
            <td width="23"><img src="images/print.gif" alt="" width="15" height="12"></td>
            <td width="4" class="verdot">&nbsp;</td>
            <td width="87"><div align="center">

			<?php echo '<a  class="linkup2" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')).'wishlist_x=true&products_id=' . $product_info['products_id']) . '" >Add to Wishlist</a>'; ?>

			</div></td>
			 <td width="20"><img src="images/wishlist.gif" alt="" width="15" height="12"></td>
            <td width="4" class="verdot">&nbsp;</td>
            <td width="83"><div align="center"><?php echo '<a  class="linkup2" href="' . tep_href_link("product_reviews_write.php", tep_get_all_get_params(array('action')).'products_id=' . $product_info['products_id']) . '" >Write a Review</a>'; ?></div></td>
            <td width="15"><img src="images/review.gif" alt="" width="12" height="13"></td>
          </tr>
        </table></td>
      </tr>

<tr><td align="left" valign="top" colspan="2">
<table align="center" width="100%" border="0" cellspacing="0" >


		  <tr>
			<td class="featimg" width="152" align="center" valign="top">

<!--<a href="" ><img src="/templates/images/dummy_04.png" border="0" /></a> -->
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupstsWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')" class="orange">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' class="imageborder"') . '<br><br><center><div align="center"><img src="images/click_here.gif" alt="" width="93" height="14" border="0" ></a><br><br></div>' . '</center></div>'; ?>');
//--></script>
                          <noscript>
                          <a href="$product_popup_url"  class="Product_Heading"  onClick="return popupstsWindow('$product_popup_url')">$imagesmall</a> <a href="$product_popup_url"  class="orange" onClick="return popupstsWindow('$product_popup_url')" >
                          </a>
                          </noscript>




			<br />

			    <noscript>
                          <a href="$product_popup_url"  class="orange"  onClick="return popupstsWindow('$product_popup_url')">$imagesmall</a> <a href="$product_popup_url"  class="orange" onClick="return popupstsWindow('$product_popup_url')" >
                         </a></noscript></td>
			<td width="791" align="left" valign="top" class="featstory">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<!--  BOF price-break-1.11.3  <td class="main" align="right"><?php // echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART);
 ?></td> -->
                 <td width="73%" valign="top" class="main" >
                  <table border="0" >
                    <tr><td width="681" ><span class="sampleproduct"><?php echo $products_name; ?></span>


					<?php echo $products_price;

					if ((STOCK_CHECK == 'true')&&($product_info['products_quantity'] < 1))
					{
					$status_p="Out of Stock";
					}else
					{
					$status_p="In Stock";
					}
					?>
							<span class="stock">Stock Status: <?php echo $status_p; ?> </span>

					    <span class="avail"><?php echo  $product_info['products_info_title'];?> </span> <span class="producttext"><?php echo  $product_info['products_info_desc'];?></span> <br><span class="producttext">Product Code: <?php echo  $product_info['products_model'];?> </span><br>
                    </td></tr>
                    <tr><td align="left"><?php
					// Points/Rewards system V2.00 BOF
    if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) { // check that the points system is enabled
      if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
        $products_price_points = tep_display_points($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
      } else {
        $products_price_points = tep_display_points($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
      }
      $products_points = tep_calc_products_price_points($products_price_points);
      $products_points_value = tep_calc_price_pvalue($products_points);
      if (USE_POINTS_FOR_SPECIALS == 'true' || $new_price == false){
        echo '<span class="producttext">' . sprintf(TEXT_PRODUCT_POINTS , number_format($products_points,POINTS_DECIMAL_PLACES), $currencies->format($products_points_value)) . '</span><br>';
      } else {
          echo '<span class="producttext">' . TEXT_PRODUCT_NO_POINTS . '</span><br>';
      }
    }
// Points/Rewards system V2.00 EOF
					?>
                      <?php //echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?>
					  <?php //echo tep_image_submit('button_wishlist.gif', 'Add to Wishlist', 'name="wishlist" value="wishlist"'); ?>

                    <?php //echo '<a href="shopping_cart.php" class="orange" >Shopping Cart</a>';?>


					<?php
// START: Extra Fields Contribution v2.0i  with fix
  list($products_id_clean) = explode('{', $product_info['products_id']);
  $extra_fields_query = tep_db_query("
                      SELECT pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value ,pef.products_extra_fields_status as status
                      FROM ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef
             LEFT JOIN  ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf
            ON ptf.products_extra_fields_id=pef.products_extra_fields_id
            WHERE ptf.products_id=".$products_id_clean." and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='".$languages_id."')
            ORDER BY products_extra_fields_order");

  while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
        if (! $extra_fields['status'])  // show only enabled extra field
           continue;
        echo '
	  <table border="0" cellspacing="0" cellpadding="0"><tr>
     <td class="main" align="left" vallign="middle"><span class="producttext">'.$extra_fields['name'].': </span>';
        echo '<span class="producttext">' .$extra_fields['value'].'</span><BR> </tr>
      </table>
	  ';
  }

// END: Extra Fields Contribution

?>
					<br>

					<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
					<td colspan="2">
					<?php

				//echo "select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'";
				//die ('cool........') ;
				    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");

	$products_attributes = tep_db_fetch_array($products_attributes_query);

    if ($products_attributes['total'] > 0) {
	/*
//		if (OPTIONS_AS_IMAGES_ENABLED == 'false'){
/////////////////////////////////////////////////////Option Images //////////////////////////////////////////////////
?>

          <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main" colspan="2"><?php echo TEXT_PRODUCT_OPTIONS; ?></td>
            </tr>
<?php
//			  <!-- /////////////////////START --------- -->
			//clr 030714 update query to pull option_type
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

	  //echo "select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name" ;

      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
				//clr 030714 add case statement to check option type
        switch ($products_options_name['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
            //CLR 030714 Add logic for text option
            $products_attribs_query = tep_db_query("select distinct patrib.options_values_price, patrib.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = '" . $products_options_name['products_options_id'] . "'");
            $products_attribs_array = tep_db_fetch_array($products_attribs_query);
            $tmp_html = '<input type="text" name ="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']" size="' . $products_options_name['products_options_length'] .'" maxlength="' . $products_options_name['products_options_length'] . '" value="' . $cart->contents[$_GET['products_id']]['attributes_values'][$products_options_name['products_options_id']] .'">  ' . $products_options_name['products_options_comment'] ;
            if ($products_attribs_array['options_values_price'] != '0') {
              $tmp_html .= '(' . $products_attribs_array['price_prefix'] . $currencies->display_price($products_attribs_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')';
            }
?>
            <tr>
              <td class="main"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
              <td class="main"><?php echo $tmp_html;  ?></td>
            </tr>
<?php
            break;

          case PRODUCTS_OPTIONS_TYPE_TEXTAREA:
            //CLR 030714 Add logic for text option
            $products_attribs_query = tep_db_query("select distinct patrib.options_values_price, patrib.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = '" . $products_options_name['products_options_id'] . "'");
            $products_attribs_array = tep_db_fetch_array($products_attribs_query);
		$tmp_html = '<textarea onKeyDown="textCounter(this,\'progressbar' . $products_options_name['products_options_id'] . '\',' . $products_options_name['products_options_length'] . ')"
								   onKeyUp="textCounter(this,\'progressbar' . $products_options_name['products_options_id'] . '\',' . $products_options_name['products_options_length'] . ')"
								   onFocus="textCounter(this,\'progressbar' . $products_options_name['products_options_id'] . '\',' . $products_options_name['products_options_length'] . ')"
								   wrap="soft"
								   name="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']"
								   rows=5
								   id="id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']"
								   value="' . $cart->contents[$_GET['products_id']]['attributes_values'][$products_options_name['products_options_id']] . '"></textarea>
						<div id="progressbar' . $products_options_name['products_options_id'] . '" class="progress"></div>
						<script>textCounter(document.getElementById("id[' . TEXT_PREFIX . $products_options_name['products_options_id'] . ']"),"progressbar' . $products_options_name['products_options_id'] . '",' . $products_options_name['products_options_length'] . ')</script>';?>	<!-- DDB - 041031 - Form Field Progress Bar //-->
            <tr>
<?php
            if ($products_attribs_array['options_values_price'] != '0') {
?>
              <td class="main"><?php echo $products_options_name['products_options_name'] . '<br>(' . $products_options_name['products_options_comment'] . ' ' . $products_attribs_array['price_prefix'] . $currencies->display_price($products_attribs_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . ')'; ?></td>
<?php       } else {
?>
              <td class="main"><?php echo $products_options_name['products_options_name'] . '<br>(' . $products_options_name['products_options_comment'] . ')'; ?></td>
<?php        }
?>
              <td class="main"><?php echo $tmp_html;  ?></td>
            </tr>
<?php
            break;

          case PRODUCTS_OPTIONS_TYPE_RADIO:
            //CLR 030714 Add logic for radio buttons
            $tmp_html = '<table>';
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'");
            $checked = true;
            while ($products_options_array = tep_db_fetch_array($products_options_query)) {
              $tmp_html .= '<tr><td class="main">';
              $tmp_html .= tep_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options_array['products_options_values_id'], $checked);
              $checked = false;
              $tmp_html .= $products_options_array['products_options_values_name'] ;
              $tmp_html .=$products_options_name['products_options_comment'] ;
              if ($products_options_array['options_values_price'] != '0') {
                $tmp_html .= '(' . $products_options_array['price_prefix'] . $currencies->display_price($products_options_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
              }
              $tmp_html .= '</tr></td>';
            }
            $tmp_html .= '</table>';
?>
            <tr>
              <td class="main"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
              <td class="main"><?php echo $tmp_html;  ?></td>
            </tr>

<?php
//Options as Images. This whole php clause needs to be added
//if (OPTIONS_AS_IMAGES_ENABLED == 'true') include ('options_images2.php');

/////////////////////////////////////////////////////Option Images //////////////////////////////////////////////////

?>


<?php
            break;
          case PRODUCTS_OPTIONS_TYPE_CHECKBOX:
            //CLR 030714 Add logic for checkboxes
            $products_attribs_query = tep_db_query("select distinct patrib.options_values_id, patrib.options_values_price, patrib.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = '" . $products_options_name['products_options_id'] . "'");
            $products_attribs_array = tep_db_fetch_array($products_attribs_query);
            echo '<tr><td class="main">' . $products_options_name['products_options_name'] . ': </td><td class="main">';
            echo tep_draw_checkbox_field('id[' . $products_options_name['products_options_id'] . ']', $products_attribs_array['options_values_id']);
            echo $products_options_name['products_options_comment'] ;
            if ($products_attribs_array['options_values_price'] != '0') {
              echo '(' . $products_attribs_array['price_prefix'] . $currencies->display_price($products_attribs_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
            }
            echo '</td></tr>';
            break;
          default:
            //clr 030714 default is select list
            //clr 030714 reset selected_attribute variable
            $selected_attribute = false;
        		$products_options_array = array();
        		$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        		while ($products_options = tep_db_fetch_array($products_options_query)) {
          		$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          		if ($products_options['options_values_price'] != '0') {
            		$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          		}
        		}

        		if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          		$selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
?>
            <tr>
              <td class="main"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
              <td class="main"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . $products_options_name['products_options_comment'];  ?></td>
            </tr>
<?php
        }  //clr 030714 end switch
      } //clr 030714 end while

?>

<!-- -----------END-------------- -->
</table>
<?php
*/
  //  }
	//Options as Images. Add the curly bracket as shown on the next line
//++++ QT Pro: End Changed Code
    //$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
  //  $products_attributes = tep_db_fetch_array($products_attributes_query);
  //  if ($products_attributes['total'] > 0) {
	//++++ QT Pro: Begin Changed code
		  $products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$_GET['products_id']) ? $_GET['products_id'] : (int)$_GET['products_id']);

		 require(DIR_WS_CLASSES . 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN . '.php');
		 $class = 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN;

		  $pad = new $class($products_id);
		  echo $pad->draw();
	//++++ QT Pro: End Changed Code


	}
?>

<?php
//Options as Images. This whole php clause needs to be added
//if (OPTIONS_AS_IMAGES_ENABLED == 'true') { include ('options_images2.php'); }

/////////////////////////////////////////////////////Option Images //////////////////////////////////////////////////

?>					</td>
				  </tr>
				</table>
					<span class="producttext"><?php echo TEXT_ENTER_QUANTITY . ": </span>" . tep_draw_input_field('cart_quantity', $pf->adjustQty(1), 'size="6"'); ?><p><?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('buy_now.gif', IMAGE_BUTTON_IN_CART); ?>

					</td>
                    </tr>
                  </table>                </td>
                 <td width="10%" align="right" valign="top" class="main" ><table align="center" width="100%" border="0" cellspacing="0" >
                   <tr>
                     <td align="right" valign="top"><?php $totproducts_extra_images_query = tep_db_query("SELECT products_extra_image, products_extra_images_id FROM " . TABLE_PRODUCTS_EXTRA_IMAGES . " WHERE products_id='" . $product_info['products_id'] . "'");
		?>
                         <table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                           <?php  if (tep_db_num_rows($totproducts_extra_images_query) >= 1){	?>
                           <tr>
                             <td colspan="3" align="right"><?php
						if (DISPLAY_EXTRA_IMAGES == 'true'){
							 if ($product_check['total'] >= 1) {
							   include (DIR_WS_INCLUDES . 'products_extra_images.php');
							 }
						}
					?>
                             </td>
                           </tr>
                           <?php	} else	{ ?>
                           <tr>
                             <th colspan="3" class="productviews">&nbsp;</th>
                           </tr>
                           <tr>
                             <?php }?>
                                          </table></td>
                   </tr>
  <td colspan="2"></td>
  </tr>
                 </table></td>
                 <!-- EOF price-break-1.11.3 -->
				  </tr>
				  <tr>
					<td align="center" colspan="3">
					<!--
					<a class="orange" href="javascript:popupWindow('popup_shipping.php')">estimate shipping</a> |
					--></td>
				  </tr>
				  <tr>
					<td align="center" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
				  </tr>
				  <tr>
					<td colspan="2" >
					<?php
    }

?>
</td>
				  </tr>
				</table>

						</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="left" valign="top" class="featimg"><table width="100%" border="0" cellspacing="0" cellpadding="0">



              <tr valign="top">
                <td ><span class="alltext">
<!-- begin tab pane //-->
<?php
  $product_description_string = $product_info['products_description'];
  $tab_array = preg_match_all ("|#newtab#(.*)#/newtab#|Us", $product_description_string, $matches, PREG_SET_ORDER);    // <new_tab>

if ($tab_array){ ?>
<div class="tab-pane" id="tabpane1" >

<script type="text/javascript">
tp = new WebFXTabPane(document.getElementById("tabpane1"));
</script>
<?php
  for ($i=0, $n=sizeof($matches); $i<$n; $i++) {
    $this_tab_name = preg_match_all ("|#tabname#(.*)#/tabname#|Us", $matches[$i][1], $tabname, PREG_SET_ORDER);

    if ($this_tab_name){
    echo '<div class="tab-page" id="tabPage' . $i . '" >' .
         '<h2 class="tab">' . $tabname[0][1] . '</h2>' .
         '<script type="text/javascript">tp.addTabPage(document.getElementById("tabPage' . $i . '"));</script>';


      if (preg_match_all ("|#tabpage#(.*)#/tabpage#|Us", $matches[$i][1], $tabpage, PREG_SET_ORDER)){
         require($tabpage[0][1]);
      }elseif (preg_match_all ("|#tabtext#(.*)#/tabtext#|Us", $matches[$i][1], $tabtext, PREG_SET_ORDER)){
         echo '<div class="toppad alltext">' . stripslashes($tabtext[0][1]) . '</div><br>';
      }
   echo '</div>';
    }
  }
 ?>
 </div>
 <?php
}else{

?>
<!-- End Tab Pane //-->
          <p><?php echo stripslashes($product_info['products_description']); ?></p>
<?php
}
?>
                 </span> <br></td>
              </tr>
              <tr>
                <td class="horzdot" >&nbsp;</td>
              </tr>
              <tr>
                <td  style="padding-left:15px"><?php include(DIR_WS_MODULES . 'product_reviews_info.php'); ?></td>
              </tr>
              <tr>
                <td class="horzdot" >&nbsp;</td>
              </tr>
			  <?php /*
<tr>
                <td >			  <?php
// START: Extra Fields Contribution v2.0i  with fix
  list($products_id_clean) = explode('{', $product_info['products_id']);
  $extra_fields_query = tep_db_query("
                      SELECT pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value ,pef.products_extra_fields_status as status
                      FROM ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef
             LEFT JOIN  ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf
            ON ptf.products_extra_fields_id=pef.products_extra_fields_id
            WHERE ptf.products_id=".$products_id_clean." and ptf.products_extra_fields_value<>'' and (pef.languages_id='0' or pef.languages_id='".$languages_id."')
            ORDER BY products_extra_fields_order");

  while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
        if (! $extra_fields['status'])  // show only enabled extra field
           continue;
        echo '
	  <table border="0" cellspacing="0" cellpadding="0"><tr>
     <td class="main" align="left" vallign="middle"><b><span class="extrafields">'.$extra_fields['name'].': </b></span>';
        echo '<i>' .$extra_fields['value'].'<BR></i> </tr>
      </table>
	  ';
  }

// END: Extra Fields Contribution

?></td>
              </tr>
			  */
			  ?>
              <tr>
                <td ><?php include(DIR_WS_MODULES . FILENAME_FAMILY_PRODUCTS); ?></td>
              </tr>




			  <tr>
			  <td>
			<!--          Get iew fot    -->
<?php
if(!tep_session_is_registered('sppc_customer_group_id')) {
 $customer_group_id = '0';
} else {
  $customer_group_id = $sppc_customer_group_id;
}


if ($customer_group_id != '0') {
$products_extra_images_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, IF(pg.customers_group_price IS NOT NULL, pg.customers_group_price, p.products_price) as products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' and pg.customers_group_id = '".$customer_group_id."' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
} else {
$products_extra_images_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
}
$num_products_xsell = tep_db_num_rows($products_extra_images_query);
if ($num_products_xsell > 0) {
?>



				<table width="100%" border="0" cellspacing="0" cellpadding="0">

				<tr>
					<th colspan="3" class="horzdot">&nbsp;</th>
				  </tr>
					<tr>
					<th colspan="3" class="regularprice" style="padding-left: 10px" height="25">You might also be intersted in</th>
				  </tr>
					<tr>
						<td colspan="3" align="left">
						  <?php include(DIR_WS_INCLUDES . 'xsell_products.php'); ?>
						</td>
					</tr>
					<tr>
					<th colspan="3" class="horzdot">&nbsp;</th>
				  </tr>
				  			    <tr>
                <td > <?php
//affiliate build a link begin
if (tep_session_is_registered('affiliate_id')) {
?>
<?php echo '<a  class="linkup2" href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, 'individual_banner_id=' . $product_info['products_id']) . '">Affilates: Click here to generate html code to insert in your web page for this product</a>'; ?>

<?php
}
//affiliate build a link begin
	     ?></td>
              </tr>
		  		</table>
<?php
}
?>
			<!--          Get iew fot    -->
			</td>
			  </tr>


            </table></td>
		    </tr>
		</table>

<!-- /////////////////////////////////////////////////////////////////// -->





</td></tr>


<tr>
<td colspan="2" valign="top" class="padbuttons">


</td>
</tr>
</table>
<?php }?>


					</td>
                </tr>


              </table></td>
          </tr>
        </table></td>
    </tr>


    </tr>

  </table>
  </form>
  </td>

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

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>