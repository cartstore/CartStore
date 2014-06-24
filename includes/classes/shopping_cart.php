<?php
/*
  $Id: shopping_cart.php,v 1.35 2003/06/25 21:14:33 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type;
    // start indvship
    var $shiptotal;
    // end indvship

    function shoppingCart() {
      $this->reset();
    }

    function restore_contents() {
/* CCGV - BEGIN */
      global $customer_id, $gv_id, $REMOTE_ADDR;
/* CCGV - END */

      if (!tep_session_is_registered('customer_id')) return false;

// insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
            if (isset($this->contents[$products_id]['attributes'])) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                //clr 031714 udate query to include attribute value. This is needed for text attributes.
                $attr_value = $this->contents[$products_id]['attributes_values'][$option];
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "', '" . tep_db_input($attr_value) . "')");
              }
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          }
        }
/* CCGV - BEGIN */
        if (tep_session_is_registered('gv_id')) {
          $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_id . "', '" . (int)$customer_id . "', now(),'" . $REMOTE_ADDR . "')");
          $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_id . "'");
          tep_gv_account_update($customer_id, $gv_id);
          tep_session_unregister('gv_id');
        }
/* CCGV - END */
      }

// reset per-session cart contents, but not the database contents
      $this->reset(false);

      $products_query = tep_db_query("select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
      while ($products = tep_db_fetch_array($products_query)) {
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
// attributes
        //CLR 020606 update query to pull attribute value_text. This is needed for text attributes.
        $attributes_query = tep_db_query("select products_options_id, products_options_value_id, products_options_value_text from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
          //CLR 020606 if text attribute, then set additional information

          if ($attributes['products_options_value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
            $this->contents[$products['products_id']]['attributes_values'][$attributes['products_options_id']] = $attributes['products_options_value_text'];
          }
        }
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {
      global $customer_id;

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

// start indvship
	  $this->shiptotal = '';
	  // end indvship

      if (tep_session_is_registered('customer_id') && ($reset_database == true)) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
      }

      unset($this->cartID);
      if (tep_session_is_registered('cartID')) tep_session_unregister('cartID');
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
      // BOF Separate Pricing Per Customer, Price Break 1.11.3 modification
      global $new_products_id_in_cart, $customer_id, $languages_id;

      $pf = new PriceFormatter;
      $pf->loadProduct($products_id, $languages_id);
      $qty = $pf->adjustQty($qty);
      // EOF Separate Pricing Per Customer, Price Break 1.11.3 modification

      $products_id = tep_get_uprid($products_id, $attributes);
      if ($notify == true) {
        $new_products_id_in_cart = $products_id;
        tep_session_register('new_products_id_in_cart');
      }

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty);
// insert into database
        if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");

        if (is_array($attributes)) {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            //CLR 020606 check if input was from text box.  If so, store additional attribute information
            //CLR 020708 check if text input is blank, if so do not add to attribute lists
            //CLR 030228 add htmlspecialchars processing.  This handles quotes and other special chars in the user input.
            $attr_value = NULL;
            $blank_value = FALSE;
            if (strstr($option, TEXT_PREFIX)) {
              if (trim($value) == NULL)
              {
                $blank_value = TRUE;
              } else {
                $option = substr($option, strlen(TEXT_PREFIX));
                $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                $this->contents[$products_id]['attributes_values'][$option] = $attr_value;
              }
            }

            if (!$blank_value)
            {
              $this->contents[$products_id]['attributes'][$option] = $value;
// insert into database
            //CLR 020606 update db insert to include attribute value_text. This is needed for text attributes.
            //CLR 030228 add tep_db_input() processing
              if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "', '" . tep_db_input($attr_value) . "')");
            }
          }
        }
      }
      $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function update_quantity($products_id, $quantity = '', $attributes = '') {
      global $customer_id;

      if (empty($quantity)) return true; // nothing needs to be updated if theres no quantity, so we return true..

      $this->contents[$products_id] = array('qty' => $quantity);
// update database
      if (tep_session_is_registered('customer_id')) tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");

      if (is_array($attributes)) {
        reset($attributes);
        while (list($option, $value) = each($attributes)) {
          //CLR 020606 check if input was from text box.  If so, store additional attribute information
          //CLR 030108 check if text input is blank, if so do not update attribute lists
          //CLR 030228 add htmlspecialchars processing.  This handles quotes and other special chars in the user input.
          $attr_value = NULL;
          $blank_value = FALSE;
          if (strstr($option, TEXT_PREFIX)) {
            if (trim($value) == NULL)
            {
              $blank_value = TRUE;
            } else {
              $option = substr($option, strlen(TEXT_PREFIX));
              $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
              $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
              $this->contents[$products_id]['attributes_values'][$option] = $attr_value;
            }
          }

          if (!$blank_value)
          {
            $this->contents[$products_id]['attributes'][$option] = $value;
// update database
            //CLR 020606 update db insert to include attribute value_text. This is needed for text attributes.
            //CLR 030228 add tep_db_input() processing
            if (tep_session_is_registered('customer_id')) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'");
          }
        }
      }
    }

    function cleanup() {
      global $customer_id;

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
// remove from database
          if (tep_session_is_registered('customer_id')) {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
          }
        }
      }
    }

    function count_contents() {  // get total number of items in cart
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {
      global $customer_id;

      //CLR 030228 add call tep_get_uprid to correctly format product ids containing quotes
      $products_id = tep_get_uprid($products_id, $attributes);

      unset($this->contents[$products_id]);
// remove from database
      if (tep_session_is_registered('customer_id')) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
      }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }


function calculate() {
     global $currencies;

/* CCGV - BEGIN */
      $this->total_virtual = 0;
/* CCGV - END */
      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;
// BOF Separate Pricing Per Customer, Price Break 1.11.3 mod
   global $languages_id;
   $pf = new PriceFormatter;

      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $qty = $this->contents[$products_id]['qty'];

// products price
    //    $product_query = tep_db_query("select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");

    //    if ($product = tep_db_fetch_array($product_query)) {
        if ($product = $pf->loadProduct($products_id, $languages_id)){
/* CCGV - BEGIN */
          $no_count = 1;
          $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $gv_result = tep_db_fetch_array($gv_query);
          if (preg_match('/^GIFT/', $gv_result['products_model'])) {
            $no_count = 0;
          }
/* CCGV - END */
          $prid = $product['products_id'];
         $products_tax = tep_get_tax_rate($product['products_tax_class_id']);
  //        $products_price = $product['products_price'];
          $products_price = $pf->computePrice($qty);
          $products_weight = $product['products_weight'];
// EOF Separate Pricing Per Customer, Price Break 1.11.3 mod

/* CCGV - BEGIN */
          $this->total_virtual += $currencies->calculate_price($products_price, $products_tax, ($qty * $no_count) );
          $this->weight_virtual += ($qty * $products_weight) * $no_count;
/* CCGV - END */

          $this->total += $currencies->calculate_price($products_price, $products_tax, $qty);
          $this->weight += ($qty * $products_weight);
        }

// attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
		   if ($value == 0) {

            $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "'");
			}
			else
			{

			 $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
			}
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            } else {
              $this->total -= $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            }
          }
        }
      }
    }

//////
//MVS Start
//  New method to provide cost, weight, quantity, and product IDs by vendor
//////
//Output array structure (example):
//shoppingcart Object
//(
//  [vendor_shipping] => array
//    (
//      [0] => array   //Number is the vendor_id
//        (
//          [weight] => 22.59
//          [cost] => 12.95
//          [qty] => 2
//          [products_id] => array
//            (
//              [0] => 12
//              [1] => 47
//            )
//        )
//      [12] => array
//        (
//          [weight] => 32.74
//          [cost] => 109.59
//          [qty] => 5
//          [products_id] => array
//            (
//              [0] => 2
//              [1] => 3
//              [2] => 37
//              [3] => 49
//            )
//        )
//    )
//)
    function vendor_shipping() {

      if (!is_array($this->contents)) return 0;  //Cart is empty

      $this->vendor_shipping = array();  //Initialize the output array
      reset($this->contents);            //  and reset the input array
      foreach ($this->contents as $products_id => $value) {  //$value is never used
        $quantity = $this->contents[$products_id]['qty'];

        $products_query = tep_db_query("select products_id,
                                               products_price,
                                               products_tax_class_id,
                                               products_weight,
                                               vendors_id
                                        from " . TABLE_PRODUCTS . "
                                        where products_id = '" . (int)$products_id . "'"
                                      );
        if ($products = tep_db_fetch_array($products_query)) {
          $products_price = $products['products_price'];
          $products_weight = $products['products_weight'];
          $vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
          $products_tax = tep_get_tax_rate($products['products_tax_class_id']);

          //Find special prices (if any)
          $specials_query = tep_db_query("select specials_new_products_price
                                          from " . TABLE_SPECIALS . "
                                          where products_id = '" . (int)$products_id . "'
                                            and status = '1'"
                                        );
          if (tep_db_num_rows ($specials_query)) {
            $specials = tep_db_fetch_array($specials_query);
            $products_price = $specials['specials_new_products_price'];
          }

          //Add values to the output array
          $this->vendor_shipping[$vendors_id]['weight'] += ($quantity * $products_weight);
          $this->vendor_shipping[$vendors_id]['cost'] += tep_add_tax($products_price, $products_tax) * $quantity;
          $this->vendor_shipping[$vendors_id]['qty'] += $quantity;
          $this->vendor_shipping[$vendors_id]['products_id'][] = $products_id; //There can be more than one product
        }

        // Add/subtract attributes prices (if any)
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          foreach ($this->contents[$products_id]['attributes'] as $option => $value) {


            $attribute_price_query = tep_db_query("select options_values_price,
                                                          price_prefix
                                                   from " . TABLE_PRODUCTS_ATTRIBUTES . "
                                                   where products_id = '" . (int)$products_id . "'
                                                     and options_id = '" . (int)$option . "'
                                                     and options_values_id = '" . (int)$value . "'"
                                                 );
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->vendor_shipping[$vendors_id]['cost'] += $quantity * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            } else {
              $this->vendor_shipping[$vendors_id]['cost'] -= $quantity * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            }
          }
        }
      }

      return $this->vendor_shipping;
    }
//MVS End

    function attributes_price($products_id) {
      $attributes_price = 0;

      if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
		if($value==0)
		{
		 $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' ");
		}else
		{
		 $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
		}

          $attribute_price = tep_db_fetch_array($attribute_price_query);
          if ($attribute_price['price_prefix'] == '+') {
            $attributes_price += $attribute_price['options_values_price'];
          } else {
            $attributes_price -= $attribute_price['options_values_price'];
          }
        }
      }

      return $attributes_price;
    }

function get_products() {
	    global $languages_id;
// BOF Separate Pricing Per Customer v4, Price Break 1.11.3 modification
      if (!is_array($this->contents)) return false;
      $pf = new PriceFormatter;

      $products_array = array();
      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
/*        $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
        if ($products = tep_db_fetch_array($products_query)) {
          $prid = $products['products_id'];
          $products_price = $products['products_price'];

          $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$prid . "' and status = '1'");
          if (tep_db_num_rows($specials_query)) {
            $specials = tep_db_fetch_array($specials_query);
            $products_price = $specials['specials_new_products_price'];
          } */

	  if ($products = $pf->loadProduct($products_id, $languages_id)) {
          $products_price = $pf->computePrice($this->contents[$products_id]['qty']);
// EOF Separate Pricing Per Customer v4, Price Break 1.11.3 modification

// start indvship
		  $products_shipping_query = tep_db_query("select products_ship_price, products_ship_price_two, products_ship_zip, products_ship_methods_id from " . TABLE_PRODUCTS_SHIPPING . " where products_id = '" . (int)$products_id . "'");
		  $products_shipping = tep_db_fetch_array($products_shipping_query);
		  // end indvship

          $products_array[] = array('id' => $products_id,
                                    'name' => $products['products_name'],
                                    'model' => $products['products_model'],
                                    'image' => $products['products_image'],
                                    'price' => $products_price,
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'weight' => $products['products_weight'],
                                    'final_price' => ($products_price + $this->attributes_price($products_id)),
                                    'tax_class_id' => $products['products_tax_class_id'],
                                    // start indvship
									'products_ship_price' => $products_shipping['products_ship_price'],
									'products_ship_price_two' => $products_shipping['products_ship_price_two'],
									'products_ship_zip' => $products_shipping['products_ship_zip'],
									// end  indvship
                                    //MVS start
                                    'vendors_id' => isset($products['vendors_id']) ? $products['vendors_id'] : '',
                                    'vendors_name' => isset($products['vendors_name']) ? $products['vendors_name'] : '',
                                    //MVS end
                                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''),
                                    'attributes_values' => (isset($this->contents[$products_id]['attributes_values']) ? $this->contents[$products_id]['attributes_values'] : ''));

        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

/* CCGV - BEGIN */
    function show_total_virtual() {
      $this->calculate();

      return $this->total_virtual;
    }

    function show_weight_virtual() {
      $this->calculate();

      return $this->weight_virtual;
    }
/* CCGV - END */
    function generate_cart_id($length = 5) {
      return tep_create_random_value($length, 'digits');
    }

	function get_content_type() {
		// start indvship
	  global $shipping_modules;
	  // end indvship
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = tep_db_fetch_array($virtual_check_query);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
/* CCGV - BEGIN */
          } elseif ($this->show_weight() == 0) {
            reset($this->contents);
            while (list($products_id, ) = each($this->contents)) {
              $virtual_check_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
              $virtual_check = tep_db_fetch_array($virtual_check_query);
              if ($virtual_check['products_weight'] == 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
/* CCGV - END */
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }




    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }

/* CCGV - BEGIN */
    function count_contents_virtual() {
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $no_count = false;
          $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
          $gv_result = tep_db_fetch_array($gv_query);
          if (preg_match('/^GIFT/', $gv_result['products_model'])) {
            $no_count=true;
          }
          if (NO_COUNT_ZERO_WEIGHT == 1) {
            $gv_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($products_id) . "'");
            $gv_result=tep_db_fetch_array($gv_query);
            if ($gv_result['products_weight']<=MINIMUM_WEIGHT) {
              $no_count=true;
            }
          }
          if (!$no_count) $total_items += $this->get_quantity($products_id);
        }
      }
      return $total_items;
    }
/* CCGV - END */
  }
?>
