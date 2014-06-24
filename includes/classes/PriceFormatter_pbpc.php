<?php
/*
  $Id: PriceFormatter.php,v 1.6 2003/06/25 08:29:26 petri Exp $
  adapted for Separate Pricing Per Customer v4 2005/03/20
  adapted for price break per category 2005/09/03
  including an optimization to avoid double queries for the same info

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

/*
    PriceFormatter.php - module to support quantity pricing

    Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
*/

class PriceFormatter {
  var $hiPrice;
  var $lowPrice;
  var $quantity;
  var $hasQuantityPrice;

  function PriceFormatter($prices=NULL) {
    $this->productsID = -1;
//   BOF Price Break for SPPC mod, price break per category
    $this->category = '';
//   EOF Price Break for SPPC mod, price break per category
    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    $this->hiPrice=-1;
    $this->lowPrice=-1;

    for ($i=1; $i<=8; $i++){
      $this->quantity[$i] = -1;
      $this->prices[$i] = -1;
    }
    $this->thePrice = -1;
    $this->specialPrice = -1;
    $this->qtyBlocks = 1;

    if($prices)
      $this->parse($prices);
  }

  function encode() {
	$str = $this->productsID . ":"
	       . (($this->hasQuantityPrice == true) ? "1" : "0") . ":"
	       . (($this->hasSpecialPrice == true) ? "1" : "0") . ":"
	       . $this->quantity[1] . ":"
	       . $this->quantity[2] . ":"
	       . $this->quantity[3] . ":"
	       . $this->quantity[4] . ":"
		   . $this->quantity[5] . ":"
		   . $this->quantity[6] . ":"
		   . $this->quantity[7] . ":"
	       . $this->quantity[8] . ":"
	       . $this->price[1] . ":"
	       . $this->price[2] . ":"
	       . $this->price[3] . ":"
	       . $this->price[4] . ":"
		   . $this->price[5] . ":"
		   . $this->price[6] . ":"
		   . $this->price[7] . ":"
	       . $this->price[8] . ":"
	       . $this->thePrice . ":"
	       . $this->specialPrice . ":"
	       . $this->qtyBlocks . ":"
	       . $this->taxClass;
	return $str;
  }

  function decode($str) {
	list($this->productsID,
	     $this->hasQuantityPrice,
	     $this->hasSpecialPrice,
	     $this->quantity[1],
	     $this->quantity[2],
	     $this->quantity[3],
	     $this->quantity[4],
	     $this->quantity[5],
	     $this->quantity[6],
	     $this->quantity[7],
	     $this->quantity[8],
	     $this->price[1],
	     $this->price[2],
	     $this->price[3],
	     $this->price[4],
	     $this->price[5],
	     $this->price[6],
	     $this->price[7],
	     $this->price[8],
	     $this->thePrice,
	     $this->specialPrice,
	     $this->qtyBlocks,
	     $this->taxClass) = explode(":", $str);

	$this->hasQuantityPrice = (($this->hasQuantityPrice == 1) ? true : false);
	$this->hasSpecialPrice = (($this->hasSpecialPrice == 1) ? true : false);
  }

  function parse($prices) {
    $this->productsID = $prices['products_id'];
//   BOF Price Break for SPPC mod, price break per category
    $this->category = $prices['categories_id'];
//   EOF Price Break for SPPC mod, price break per category
    $this->hasQuantityPrice=false;
    $this->hasSpecialPrice=false;

    $this->quantity[1]=$prices['products_price1_qty'];
    $this->quantity[2]=$prices['products_price2_qty'];
    $this->quantity[3]=$prices['products_price3_qty'];
    $this->quantity[4]=$prices['products_price4_qty'];
    $this->quantity[5]=$prices['products_price5_qty'];
    $this->quantity[6]=$prices['products_price6_qty'];
    $this->quantity[7]=$prices['products_price7_qty'];
    $this->quantity[8]=$prices['products_price8_qty'];

    $this->thePrice=$prices['products_price'];
    $this->specialPrice=$prices['specials_new_products_price'];
    $this->hasSpecialPrice=tep_not_null($this->specialPrice);

	$this->price[1]=$prices['products_price1'];
    $this->price[2]=$prices['products_price2'];
    $this->price[3]=$prices['products_price3'];
    $this->price[4]=$prices['products_price4'];
	$this->price[5]=$prices['products_price5'];
	$this->price[6]=$prices['products_price6'];
	$this->price[7]=$prices['products_price7'];
    $this->price[8]=$prices['products_price8'];


     /*
       Change support special prices
	   If any price level has a price greater than the special
	   price lower it to the special price
	*/
	if ($this->hasSpecialPrice == true) {
		for($i=1; $i<=8; $i++) {
			if ($this->price[$i] > $this->specialPrice)
				$this->price[$i] = $this->specialPrice;
		}
	}
	//end changes to support special prices

    $this->qtyBlocks=$prices['products_qty_blocks'];

    $this->taxClass=$prices['products_tax_class_id'];

    if ($this->quantity[1] > 0) {
      $this->hasQuantityPrice = true;
      $this->hiPrice = $this->thePrice;
      $this->lowPrice = $this->thePrice;

      for($i=1; $i<=8; $i++) {
	if($this->quantity[$i] > 0) {
	  if ($this->price[$i] > $this->hiPrice) {
	    $this->hiPrice = $this->price[$i];
	  }
	  if ($this->price[$i] < $this->lowPrice) {
	    $this->lowPrice = $this->price[$i];
	  }
	}
      }
    }
  }
  // function loadProductSppc is Separate Pricing Per Customer only
  function loadProductSppc($product_id, $language_id=1, $product_info)
  {

  global $sppc_customer_group_id;
  if(!tep_session_is_registered('sppc_customer_group_id')) { 
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }
  if ($customer_group_id != '0') {
      $customer_group_price_query = tep_db_query("select customers_group_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$product_id. "' and customers_group_id =  '" . $customer_group_id . "'");
      
        if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
        $product_info['products_price']= $customer_group_price['customers_group_price'];
	for ($i = 1 ; $i < 9 ; $i++) {
		$product_info['products_price'.$i.''] = $customer_group_price['products_price'.$i.''];
		$product_info['products_price'.$i.'_qty'] = $customer_group_price['products_price'.$i.'_qty'];
	} // end if ($customer_group_price = tep_db_fetch_array($customer_group_price_query))
	$product_info['products_qty_blocks'] = $customer_group_price['products_qty_blocks'];
	} else { // there is no price for the item in products_groups: retail price breaks need to nulled
		for ($i = 1 ; $i < 9 ; $i++) {
		$product_info['products_price'.$i.''] = '0.0000';
		$product_info['products_price'.$i.'_qty'] = '0';
		} // end if ($customer_group_price = tep_db_fetch_array($customer_group_price_query))
		$product_info['products_qty_blocks'] = '1';
	}
  } // end if ($customer_group_id != '0')
  // now get the specials price for this customer_group and add it to product_info array
  $special_price_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = " . (int)$product_id . " and status ='1' and customers_group_id = '" . $customer_group_id . "'");
  if ($specials_price = tep_db_fetch_array($special_price_query)) {
	  $product_info['specials_new_products_price'] = $specials_price['specials_new_products_price'];
  }

    $this->parse($product_info);
    return $product_info;
  }

    function loadProduct($product_id, $language_id=1)
  {
  global $sppc_customer_group_id;
  if(!tep_session_is_registered('sppc_customer_group_id')) { 
  $customer_group_id = '0';
  } else {
   $customer_group_id = $sppc_customer_group_id;
  }

    $sql = "select pd.products_name, p.products_model, p.products_image, p.products_id," .
        " p.products_price, p.products_weight," .
        " p.products_price1,p.products_price2,p.products_price3,p.products_price4, p.products_price5,p.products_price6,p.products_price7,p.products_price8," .
        " p.products_price1_qty,p.products_price2_qty,p.products_price3_qty,p.products_price4_qty, p.products_price5_qty,p.products_price6_qty,p.products_price7_qty,p.products_price8_qty," .
        " p.products_qty_blocks," .
        " p.products_tax_class_id," .
        " NULL as specials_new_products_price" .
        " from " . TABLE_PRODUCTS_DESCRIPTION . " pd," .
        "      " . TABLE_PRODUCTS . " p" .
//   BOF Price Break for SPPC mod, price break per category
	" LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " using(products_id) " .
//   EOF Price Break for SPPC mod, price break per category
        " where p.products_status = '1'" .
        "   and p.products_id = '" . (int)$product_id . "'" .
        "   and pd.products_id = '" . (int)$product_id . "'" .
        "   and pd.language_id = '". (int)$language_id ."'";

    $product_info_query = tep_db_query($sql);
    $product_info = tep_db_fetch_array($product_info_query);

  if ($customer_group_id != '0') {
      $customer_group_price_query = tep_db_query("select customers_group_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$product_id. "' and customers_group_id =  '" . $customer_group_id . "'");
      
        if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
        $product_info['products_price']= $customer_group_price['customers_group_price'];
	for ($i = 1 ; $i < 9 ; $i++) {
		$product_info['products_price'.$i.''] = $customer_group_price['products_price'.$i.''];
		$product_info['products_price'.$i.'_qty'] = $customer_group_price['products_price'.$i.'_qty'];
	} // end if ($customer_group_price = tep_db_fetch_array($customer_group_price_query))
	$product_info['products_qty_blocks'] = $customer_group_price['products_qty_blocks'];
	} else { // there is no price for the item in products_groups: retail price breaks need to nulled
		for ($i = 1 ; $i < 9 ; $i++) {
		$product_info['products_price'.$i.''] = '0.0000';
		$product_info['products_price'.$i.'_qty'] = '0';
		} // end if ($customer_group_price = tep_db_fetch_array($customer_group_price_query))
		$product_info['products_qty_blocks'] = '1';
	}
  } // end if ($customer_group_id != '0')
  // now get the specials price for this customer_group and add it to product_info array
  $special_price_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = " . (int)$product_id . " and status ='1' and customers_group_id = '" . $customer_group_id . "'");
  if ($specials_price = tep_db_fetch_array($special_price_query)) {
	  $product_info['specials_new_products_price'] = $specials_price['specials_new_products_price'];
  }
    
    $this->parse($product_info);
    return $product_info;
  }

     function computePrice($qty, $no_of_other_items_in_cart_from_same_cat = 0)
  {
	$qty = $this->adjustQty($qty);
	$qty += $no_of_other_items_in_cart_from_same_cat;

	// Compute base price, taking into account the possibility of a special
	$price = ($this->hasSpecialPrice === TRUE) ? $this->specialPrice : $this->thePrice;

	for ($i=1; $i<=8; $i++)
		if (($this->quantity[$i] > 0) && ($qty >= $this->quantity[$i]))
			$price = $this->price[$i];

	return $price;
  }

  function adjustQty($qty) {
	// Force QTY_BLOCKS granularity
	$qb = $this->getQtyBlocks();
	if ($qty < 1)
		$qty = 1;

	if ($qb >= 1)
	{
		if ($qty < $qb)
			$qty = $qb;

		if (($qty % $qb) != 0)
			$qty += ($qb - ($qty % $qb));
	}
	return $qty;
  }

  function getQtyBlocks() {
    return $this->qtyBlocks;
  }

  //   BOF Price Break for SPPC mod, price break per category
  function get_category() {
    return $this->category;
  }
//   EOF Price Break for SPPC mod, price break per category

  function getPrice() {
    return $this->thePrice;
  }

  function getLowPrice() {
    return $this->lowPrice;
  }

  function getHiPrice() {
    return $this->hiPrice;
  }

  function hasSpecialPrice() {
    return $this->hasSpecialPrice;
  }

  function hasQuantityPrice() {
    return $this->hasQuantityPrice;
  }

  function getPriceString($style='productPriceInBox') {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
    	$lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
        $lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">';
	      $lc_text .= '&nbsp;<s>'
		. $currencies->display_price($this->thePrice,
				     tep_get_tax_rate($this->taxClass))
		. '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
		. $currencies->display_price($this->specialPrice,
				     tep_get_tax_rate($this->taxClass))
		. '</span>&nbsp;'
		.'</td></tr>';
    }
    else
    {
		$lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
		$lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">'
		. $currencies->display_price($this->thePrice,
		tep_get_tax_rate($this->taxClass))
		. '</td></tr>';
    }
      // If you want to change the format of the price/quantity table
      // displayed on the product information page, here is where you do it.

    if($this->hasQuantityPrice == true) {
		for($i=1; $i<=8; $i++) {
			if($this->quantity[$i] > 0) {
				$lc_text .= '<tr><td class='.$style.'>'
				. $this->quantity[$i]
				.'+&nbsp;</td><td class='.$style.'>'
				. $currencies->display_price($this->price[$i],
				tep_get_tax_rate($this->taxClass))
				.'</td></tr>';
			}
		}

		$lc_text .= '</table>';

      }
      else {
		if ($this->hasSpecialPrice == true) {
			$lc_text = '&nbsp;<s>'
			  . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
			  . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
			  . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
			  . '</span>&nbsp;';
		}
		else {
			$lc_text = '&nbsp;'
	  		. $currencies->display_price($this->thePrice,
				       tep_get_tax_rate($this->taxClass))
	  		. '&nbsp;';
		}
      	}

    return $lc_text;
  }

  function getPriceStringShort() {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
      $lc_text = '&nbsp;<s>'
	. $currencies->display_price($this->thePrice,
				     tep_get_tax_rate($this->taxClass))
	. '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
	. $currencies->display_price($this->specialPrice,
				     tep_get_tax_rate($this->taxClass))
	. '</span>&nbsp;';
    }
    else {
      if($this->hasQuantityPrice == true) {
	$lc_text = '&nbsp;'
	  . $currencies->display_price($this->lowPrice,
				       tep_get_tax_rate($this->taxClass))
	  . ' - '
	  . $currencies->display_price($this->hiPrice,
				       tep_get_tax_rate($this->taxClass))
	  . '&nbsp;';
      }
      else {
	$lc_text = '&nbsp;'
	  . $currencies->display_price($this->thePrice,
				       tep_get_tax_rate($this->taxClass))
	  . '&nbsp;';
      }
    }
    return $lc_text;
  }
}

?>
