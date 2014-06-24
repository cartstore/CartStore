<?php
  class rm1c2kg {
    var $code, $title, $description, $enabled, $num_zones, $order;

// class constructor
    function rm1c2kg() {
	global $shipping_weight;


      $this->code = 'rm1c2kg';
      $this->title = MODULE_SHIPPING_RM1C2KG_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_RM1C2KG_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_RM1C2KG_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_RM1C2KG_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_RM1C2KG_STATUS == 'True') ? true : false);
	  $this->maxWeight=MODULE_SHIPPING_RM1C2KG_MAX_WEIGHT;
	  $this->minWeight=MODULE_SHIPPING_RM1C2KG_MIN_WEIGHT;

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
      $this->num_zones = 2;
    }
// class methods
    function quote($method = '') {




      global $order, $shipping_weight, $shipping_num_boxes, $customer, $delivery, $sendto, $cart;




			if($dest_zipcode==''){
			  $priAdd_query = tep_db_query("select entry_postcode from address_book where address_book_id = '" . (int)$sendto . "'");
  			$priAdd = tep_db_fetch_array($priAdd_query);
			  $dest_zipcode = $priAdd['entry_postcode'];
			}else{
		    $dest_zipcode = $order->delivery['postcode'];
	    }
		//get the first two characters of the postcode!
	  $dest_zipcode = substr($dest_zipcode, 0,2);
	  //check if their is a digit as the second character.

$patterns[0] = '/0/';
$patterns[1] = '/1/';
$patterns[2] = '/2/';
$patterns[3] = '/3/';
$patterns[4] = '/4/';
$patterns[5] = '/5/';
$patterns[6] = '/6/';
$patterns[7] = '/7/';
$patterns[8] = '/8/';
$patterns[9] = '/9/';
$replacements = '';
$dest_zipcode =  preg_replace($patterns, $replacements, $dest_zipcode);
$dest_zipcode_checker=$dest_zipcode;
	/*
	  //now we do postcodes with digits.
	  $dest_zipcode=strtoupper($dest_zipcode);
	  if ($dest_zipcode =='PA'){
	  $area= substr($order->delivery['postcode'], 2,2);
	  $area = str_replace(" ", "", $area);
	  if ($area >= '20') $dest_zipcode = $dest_zipcode.'20';
	  }
	  if ($area < '20'){
	   $dest_zipcode = $dest_zipcode.$area;
	   }

	   //now with the PO codes
	   if ($dest_zipcode =='PO'){
	  $area= substr($order->delivery['postcode'], 2,2);
	  $area = str_replace(" ", "", $area);
	  if ($area >= '30' && $area <= '41') $dest_zipcode = $dest_zipcode.'30';
	  }
	  if ($area < '30'){ $dest_zipcode = $dest_zipcode;
	  }
	  */
      $dest_zone = 0;
      $error = false;
	  $dest_zipcode=strtoupper($dest_zipcode);
	       for ($i=1; $i<=$this->num_zones; $i++) {
       $zipcode_table = constant('MODULE_SHIPPING_RM1C2KG_CODES_' . $i);
       $zipcode_table = preg_split("/[, ]/", $zipcode_table);
       if (in_array($dest_zipcode, $zipcode_table)) {
         $dest_zone = $i;
         break;
       }
     }


			// iterate through all the zones first
	//		$reg = array();
    //  for ($i=1; $i<=$this->num_zones; $i++) {
    //    $zipcode_table = constant('MODULE_SHIPPING_RM1C2KG_CODES_' . $i);
		//echo $zipcode_table;
    //    $zipcode_zones = split("[,]", $zipcode_table);
    //    for ($j = 0; $j < count($zipcode_zones); $j++) {
	//        if (@ereg(trim(strtoupper($zipcode_zones[$j])), trim(strtoupper($dest_zipcode)), $reg)) {
	 //         $dest_zone = $i;
		//	  echo $dest_zone. ' llll<br><br>';
	 //         break;
	  //      }
     //   }
    //  }

      if ($dest_zone == 0) {
        $error = true;
      } else {
        $shipping = -1;
        $zipcode_cost = constant('MODULE_SHIPPING_RM1C2KG_COST_' . $dest_zone);

        $zipcode_table = preg_split("/[:,]/" , $zipcode_cost);

        $size = sizeof($zipcode_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $zipcode_table[$i]) {
            $shipping = $zipcode_table[$i+1];
            $shipping_method = MODULE_SHIPPING_RM1C2KG_TEXT_WAY . ' ' . $dest_zipcode . ' : ' . $shipping_weight . ' ' . MODULE_SHIPPING_RM1C2KG_TEXT_UNITS;
            $feecounter = $tableIdx;
			      $tableIdx = $i + 1;
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
          $shipping_method = MODULE_SHIPPING_RM1C2KG_UNDEFINED_RATE;
		} else {
          $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_RM1C2KG_HANDLING_' . $dest_zone);
        }
      }


       if($dest_zone ==1){
          if($cart->show_total() > 25){
				 $this->quotes = array('id' => $this->code,
                            'module' => 'FREE Delivery for orders over £25',
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => '0')));
				}else{
					 $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_RM1C2KG_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

				}

      }else{
				 $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_RM1C2KG_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

			}


      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_RM1C2KG_INVALID_ZONE;


	 if ($shipping_weight < $this->maxWeight && $shipping_weight >= $this->minWeight && $dest_zipcode_checker !=''){
      return $this->quotes;
	   }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_RM1C2KG_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Postcode Method', 'MODULE_SHIPPING_RM1C2KG_STATUS', 'True', 'Do you want to offer Postcode rate shipping/delivery?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_RM1C2KG_TAX_CLASS', '0', 'Use the following tax class on the shipping/delivery fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_RM1C2KG_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
        $default_zipcodes = '';
        if ($i == 1) {
          $default_zipcodes = 'AB, AL, B, BA, BB, BD, BH, BL, BN, BR, BS, BT, CA, CB, CF, CH, CM, CO, CR, CT, CV, CW, DA, DD, DE, DG, DH, DL, DN, DT, DY, E, EC, EH, EN, EX, FK, FY, G, GL, GU, HA, HD, HG, HP, HR, HS, HU, HX, IG, IM, IP, IV, KA, KT, KW, KY, L, LA, LD, LE, LL, LN, LS, LU, M, ME, MK, ML, N, NE, NG, NN, NP, NR, NW, OL, OX, PA, PE, PH, PL, PO, PR, RG, RH, RM, S, SA, SE, SG, SK, SL, SM, SN, SO, SP, SR, SS, ST, SW, SY, TA, TD, TF, TN, TQ, TR, TS, TW, UB, W, WA, WC, WD, WF, WN, WR, WS, WV, YO, ZE';
          $default_dlvtable = '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:6.50,45000:12.50,70000:18.50,90000:24.50, 115000:30.50,135000:36.50,160000:42.50,185000:48.50,210000:54.50,235000:60.50';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Postcode(s)', 'MODULE_SHIPPING_RM1C2KG_CODES_" . $i ."', '" . $default_zipcodes . "', 'Comma separated list of postcodes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping/Delivery Fee Table', 'MODULE_SHIPPING_RM1C2KG_COST_" . $i ."', '" . $default_dlvtable . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 4:5,8:7,... weights less than or equal to 4 would cost $5 for Zone " . $i . " destinations.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_RM1C2KG_HANDLING_" . $i."', '0', 'Handling Fee for this Postcode', '6', '0', now())");
		 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order min weight', 'MODULE_SHIPPING_RM1C2KG_MIN_WEIGHT', '0', 'Minimum weight in g(s) for order', '6', '0', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order max weight', 'MODULE_SHIPPING_RM1C2KG_MAX_WEIGHT', '2000', 'Maximum weight in g(s) for order', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_RM1C2KG_STATUS', 'MODULE_SHIPPING_RM1C2KG_TAX_CLASS', 'MODULE_SHIPPING_RM1C2KG_SORT_ORDER', 'MODULE_SHIPPING_RM1C2KG_MAX_WEIGHT', 'MODULE_SHIPPING_RM1C2KG_MIN_WEIGHT');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_SHIPPING_RM1C2KG_CODES_' . $i;
        $keys[] = 'MODULE_SHIPPING_RM1C2KG_COST_' . $i;
        $keys[] = 'MODULE_SHIPPING_RM1C2KG_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>
