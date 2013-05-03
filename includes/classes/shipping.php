<?php
/*
  $Id: shipping.php,v 1.23 2003/06/29 11:22:05 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

  class shipping {
    var $modules;

// class constructor
    function shipping($module = '') {
      /// start indvship
      // global $language, $PHP_SELF;
	  global $language, $PHP_SELF, $cart;
	  // New to fix attributes bug
	  $cart_products = $cart->get_products();
	  if (tep_not_null($cart_products)) {
			  $real_ids = array();
			  foreach($cart_products as $prod){
			  	$real_ids[] = tep_get_prid($prod['id']);
			  }
				$sql = "SELECT products_ship_methods_id FROM ".TABLE_PRODUCTS_SHIPPING." WHERE products_id IN (".implode(',',$real_ids).") AND products_ship_methods_id IS NOT NULL AND products_ship_methods_id <> ''";
				$query = mysql_query($sql);
			  // End new bug fix
				$allow_mod_array = array();
				while($rec = mysql_fetch_array($query)){
					if(empty($allow_mod_array)) $startedempty = true;
					$methods_array = array();
					$methods_array = explode(';',$rec['products_ship_methods_id']);
					if(!empty($methods_array)){
						foreach($methods_array as $method){
							$allow_mod_array[] = $method;
						}
					}
					if($startedempty){
						$startedempty = false;
					}else{
						$temp_array = array();
						foreach($allow_mod_array as $val){
							$temp_array[$val]++;
						}
						$allow_mod_array = array();
						foreach($temp_array as $key => $val){
							if($val > 1){
								$allow_mod_array[] = $key;
							}
						}
					}
				}
		}
// end indvship

      if (defined('MODULE_SHIPPING_INSTALLED') && tep_not_null(MODULE_SHIPPING_INSTALLED)) {
       // $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);

		   // BOF Separate Pricing Per Customer, next line original code
		 //   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
		 global $sppc_customer_group_id, $customer_id;
		 if(!tep_session_is_registered('sppc_customer_group_id')) {
		 $customer_group_id = '0';
		 } else {
		  $customer_group_id = $sppc_customer_group_id;
		 }
	   $customer_shipment_query = tep_db_query("select IF(c.customers_shipment_allowed <> '', c.customers_shipment_allowed, cg.group_shipment_allowed) as shipment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id =  '" . $customer_group_id . "'");
	   if ($customer_shipment = tep_db_fetch_array($customer_shipment_query)  ) {
		   if (tep_not_null($customer_shipment['shipment_allowed']) ) {
		  $temp_shipment_array = explode(';', $customer_shipment['shipment_allowed']);
		  $installed_modules = explode(';', MODULE_SHIPPING_INSTALLED);
		  for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
			  // check to see if a shipping module is not de-installed
			  if ( in_array($installed_modules[$n], $temp_shipment_array ) ) {
				  $shipment_array[] = $installed_modules[$n];
			  }
		  } // end for loop
		  $this->modules = $shipment_array;
	   } else {
		   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
	   }
	   } else { // default
		   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
	   }
		 // EOF Separate Pricing Per Customer

// start indvship
				if (tep_not_null($cart_products)) {
					$temp_array = $this->modules;
					$this->modules = array();
					foreach($temp_array as $val){
						if(mysql_num_rows($query)==0 || in_array(str_replace('.php','',$val),$allow_mod_array)) {
							$this->modules[] = $val;
						}
					}
				}
				// end indvship  


        $include_modules = array();

        if ( (tep_not_null($module)) && (in_array(substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $include_modules[] = array('class' => substr($module['id'], 0, strpos($module['id'], '_')), 'file' => substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)));
        } else {
          reset($this->modules);
          while (list(, $value) = each($this->modules)) {
            $class = substr($value, 0, strrpos($value, '.'));
            $include_modules[] = array('class' => $class, 'file' => $value);
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
          include(DIR_WS_LANGUAGES . $language . '/modules/shipping/' . $include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'shipping/' . $include_modules[$i]['file']);

          $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
        }
      }
    }

    function quote($method = '', $module = '') {
      global $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;

      $quotes_array = array();

      if (is_array($this->modules)) {
        $shipping_quoted = '';
        $shipping_num_boxes = 1;
        $shipping_weight = $total_weight;

        if (SHIPPING_BOX_WEIGHT >= $shipping_weight*SHIPPING_BOX_PADDING/100) {
          $shipping_weight = $shipping_weight+SHIPPING_BOX_WEIGHT;
        } else {
          $shipping_weight = $shipping_weight + ($shipping_weight*SHIPPING_BOX_PADDING/100);
        }

        if ($shipping_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
          $shipping_num_boxes = ceil($shipping_weight/SHIPPING_MAX_WEIGHT);
          $shipping_weight = $shipping_weight/$shipping_num_boxes;
        }

        $include_quotes = array();

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if (tep_not_null($module)) {
            if ( ($module == $class) && ($GLOBALS[$class]->enabled) ) {
              $include_quotes[] = $class;
            }
          } elseif ($GLOBALS[$class]->enabled) {
            $include_quotes[] = $class;
          }
        }

        $size = sizeof($include_quotes);
        for ($i=0; $i<$size; $i++) {
          $quotes = $GLOBALS[$include_quotes[$i]]->quote($method);
          if (is_array($quotes)) $quotes_array[] = $quotes;
        }
      }

      return $quotes_array;
    }
    
    //start indvship
	function get_shiptotal() {
	  global $cart, $order;
	  $this->shiptotal = '';
	  $products = $cart->get_products();
	  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	    if (tep_not_null($products[$i]['products_ship_price'])) {
	      $products_ship_price = $products[$i]['products_ship_price'];
	      $products_ship_price_two = $products[$i]['products_ship_price_two'];
	      $products_ship_zip = $products[$i]['products_ship_zip'];
	      $qty = $products[$i]['quantity'];
	      if(tep_not_null($products_ship_price) ||tep_not_null($products_ship_price_two)){
	        $this->shiptotal += ($products_ship_price);
	        if ($qty > 1) {
	          if (tep_not_null($products_ship_price_two)) {
	            $this->shiptotal += ($products_ship_price_two * ($qty-1));
	          } else {
	            $this->shiptotal += ($products_ship_price * ($qty-1));
	          }
	        }/////////////NOT HERE <<------------
	      }
	    }
	  }// CHECK TO SEE IF SHIPPING TO HOME COUNTRY, IF NOT INCREASE SHIPPING COSTS BY AMOUNT SET IN ADMIN/////////////move back here <<------------
	  if (($order->delivery['country']['id']) != INDIVIDUAL_SHIP_HOME_COUNTRY) {
	    if(INDIVIDUAL_SHIP_INCREASE > '0' || $this->shiptotal > '0') {
	      $this->shiptotal *= INDIVIDUAL_SHIP_INCREASE;
	    } else {
		  $this->shiptotal += INDIVIDUAL_SHIP_INCREASE *  $this->get_indvcount();
	    }
	    return $this->shiptotal;
		// not sure why this is needed, but it now works correctly for home country - by Ed
	  } else {
	  	 $this->shiptotal *= 1;
	     return $this->shiptotal;
	  }
	}

	function get_indvcount() {
	  global $cart;
	  $this->indvcount = '';
	  $products = $cart->get_products();
	  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	    if (tep_not_null($products[$i]['products_ship_price'])) {
	      $products_ship_price = $products[$i]['products_ship_price'];//}
	      $products_ship_price_two = $products[$i]['products_ship_price_two'];
	      if(is_numeric($products_ship_price)){
	        $this->indvcount += '1';
	      }
	    }
	  }
	  return $this->indvcount;
	}

	// end indvship

    function cheapest() {
      if (is_array($this->modules)) {
        $rates = array();

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $quotes = $GLOBALS[$class]->quotes;
            for ($i=0, $n=sizeof($quotes['methods']); $i<$n; $i++) {
              if (isset($quotes['methods'][$i]['cost']) && tep_not_null($quotes['methods'][$i]['cost'])) {
                $rates[] = array('id' => $quotes['id'] . '_' . $quotes['methods'][$i]['id'],
                                 'title' => $quotes['module'] . ' (' . $quotes['methods'][$i]['title'] . ')',
                                 'cost' => $quotes['methods'][$i]['cost']);
              }
            }
          }
        }

        $cheapest = false;
        for ($i=0, $n=sizeof($rates); $i<$n; $i++) {
          if (is_array($cheapest)) {
            if ($rates[$i]['cost'] < $cheapest['cost']) {
              $cheapest = $rates[$i];
            }
          } else {
            $cheapest = $rates[$i];
          }
        }

        return $cheapest;
      }
    }
  }
?>
