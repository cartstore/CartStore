<?php
  class pfBy12 {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function pfBy12() {
	global $shipping_weight;
	
	
      $this->code = 'pfBy12';
      $this->title = MODULE_SHIPPING_PFBY12_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_PFBY12_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_PFBY12_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_PFBY12_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_PFBY12_STATUS == 'True') ? true : false);
	  $this->maxWeight=MODULE_SHIPPING_PFBY12_MAX_WEIGHT;
	  $this->minWeight=MODULE_SHIPPING_PFBY12_MIN_WEIGHT; 
	  $this->pcodeAllowed='true';

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
      $this->num_zones = 1;
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


$disallowed_postcodes= split("[, ]", MODULE_SHIPPING_PFBY12_DISALLOWED); 

  $priAdd_query = tep_db_query("select entry_postcode from address_book where address_book_id = '" . (int)$sendto . "'");
  			$priAdd = tep_db_fetch_array($priAdd_query);
			  $dest_zipcode2 = $priAdd['entry_postcode'];

$area= substr($dest_zipcode2, 0,4); 
$area= str_replace(' ','',$area); 
if (in_array($area, $disallowed_postcodes)){
//$this->pcodeAllowed=='false';
}

      $dest_zone = 0;
      $error = false;  	 
	  $dest_zipcode=strtoupper($dest_zipcode);	  
	       for ($i=1; $i<=$this->num_zones; $i++) {
       $zipcode_table = constant('MODULE_SHIPPING_PFBY12_CODES_' . $i); 
       $zipcode_table = split("[, ]", $zipcode_table);
        if (in_array($dest_zipcode, $zipcode_table) && $dest_zipcode !='') {
         $dest_zone = $i; 
         break;
       }
     }


			// iterate through all the zones first
	//		$reg = array();
    //  for ($i=1; $i<=$this->num_zones; $i++) {
    //    $zipcode_table = constant('MODULE_SHIPPING_PFBY12_CODES_' . $i);
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
        $zipcode_cost = constant('MODULE_SHIPPING_PFBY12_COST_' . $dest_zone);

        $zipcode_table = split("[:,]" , $zipcode_cost); 

        $size = sizeof($zipcode_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $zipcode_table[$i]) {
            $shipping = $zipcode_table[$i+1];  
            $shipping_method = MODULE_SHIPPING_PFBY12_TEXT_WAY . ' ' . $dest_zipcode . ' : ' . $shipping_weight . ' ' . MODULE_SHIPPING_PFBY12_TEXT_UNITS;
            $feecounter = $tableIdx;
			      $tableIdx = $i + 1;
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
          $shipping_method = MODULE_SHIPPING_PFBY12_UNDEFINED_RATE;
		} else {
          $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_PFBY12_HANDLING_' . $dest_zone);
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
                            'module' => MODULE_SHIPPING_PFBY12_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost))); 			
				
				}																						 
	
      }else{
	   $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_PFBY12_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost))); 		
			
			}			

													

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_PFBY12_INVALID_ZONE;  
	  
  
	 if ($shipping_weight < $this->maxWeight && $shipping_weight >= $this->minWeight && $this->pcodeAllowed=='true'){
      return $this->quotes;
	   }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_PFBY12_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Postcode Method', 'MODULE_SHIPPING_PFBY12_STATUS', 'True', 'Do you want to offer Postcode rate shipping/delivery?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_PFBY12_TAX_CLASS', '0', 'Use the following tax class on the shipping/delivery fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_PFBY12_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
        $default_zipcodes = '';
		$default_dlvtable = '';
          if ($i == 1) {
          $default_zipcodes = 'AB, AL, B, BA, BB, BD, BH, BL, BN, BR, BS, CA, CB, CF, CH, CM, CO, CR, CT, CV, CW, DA, DD, DE, DG, DH, DL, DN, DT, DY, E, EC, EH, EN, EX, FK, FY, G, GL, GU, HA, HD, HG, HP, HR, HU, HX, IG, IP, KT, KY, L, LA, LD, LE, LL, LN, LS, LU, M, ME, MK, ML, N, NE, NG, NN, NP, NR, NW, OL, OX,PE, PL, PO, PR, RG, RH, RM, S, SA, SE, SG, SK, SL, SM, SN, SO, SP, SR, SS, ST, SW, SY, TA, TD, TF, TN, TQ, TS, TW, UB, W, WA, WC, WD, WF, WN, WR, WS, WV, YO';
          $default_dlvtable = '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:10.00,50000:20.00,75000:30.00,100000:40.00,125000:50.00,150000:60.00,175000:70.00,200000:80.00,225000:90.00,250000:100.00';
        }
		if ($i == 2) {
          $default_zipcodes = 'HS,IV,KA,KW,PA,PH,ZE';
          $default_dlvtable = '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:16.00,50000:32.00,75000:48.00,100000:64.00,125000:80.00,150000:96.00,175000:112.00,200000:128.00,225000:144.00,250000:160.00';
        }
		if ($i == 3) {
          $default_zipcodes = 'TR,IM';
          $default_dlvtable = '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:24.00,50000:48.00,75000:72.00,100000:96.00,125000:120.00,150000:144.00,175000:168.00,200000:192.00,225000:216.00,250000:240.00';
        }
				if ($i == 4) {
          $default_zipcodes = 'BT';
          $default_dlvtable = '0:00,100:1.00,250:1.62,500:2.14,750:2.65,1000:3.25,1250:4.45,1500:5.15,1750:5.85,25000:24.00,50000:48.00,75000:72.00,100000:96.00,125000:120.00,150000:144.00,175000:168.00,200000:192.00,225000:216.00,250000:240.00';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Postcode(s)', 'MODULE_SHIPPING_PFBY12_CODES_" . $i ."', '" . $default_zipcodes . "', 'Comma separated list of postcodes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping/Delivery Fee Table', 'MODULE_SHIPPING_PFBY12_COST_" . $i ."', '" . $default_dlvtable . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 4:5,8:7,... weights less than or equal to 4 would cost $5 for Zone " . $i . " destinations.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_PFBY12_HANDLING_" . $i."', '0', 'Handling Fee for this Postcode', '6', '0', now())");
		 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order min weight', 'MODULE_SHIPPING_PFBY12_MIN_WEIGHT', '2000', 'Minimum weight in g(s) for order', '6', '0', now())");
		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order max weight', 'MODULE_SHIPPING_PFBY12_MAX_WEIGHT', '2000000000', 'Maximum weight in g(s) for order', '6', '0', now())");
 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Disallowed Postcodes', 'MODULE_SHIPPING_PFBY12_DISALLOWED', 'AB30, AB31, AB33, AB34, AB35, AB36, AB37, AB38, AB39, AB3, AB41, AB42, AB43, AB44, AB45, AB51, AB52, AB53, AB54, AB55, AB56, AB63, FK8, FK3, FK17, FK18, FK19, FK20, FK21, IV4, IV5, IV6, IV7, IV8, IV9, IV10, IV11, IV12, IV13, IV14, IV15, IV16, IV17, IV18, IV19, IV20, IV21, IV22, IV23, IV24, IV25, IV26, IV27, IV28, IV29, IV30, IV31, IV32, IV33, IV34, IV35, IV36, IV40, IV41, IV42, IV43, IV44, IV45, IV46, IV47, IV48, IV49, IV51, IV52, IV53, IV54, IV55, IV56, KA27, KA28, KW1, KW2, KW3, KW5, KW6, KW7, KW8, KW9, KW10, KW11, KW12, KW13, KW14, KW15, KW16, KW17, PW20, PW21, PW22, PW23, PW24, PW25, PW26, PW27, PW28, PW29, PW30, PW31, PW32, PW33, PW34, PW35, PW36, PW37, PW38, PW39, PW40, PW41, PW42, PW43, PW44, PW45, PW46, PW47, PW48, PW49, PW60, PW61, PW62, PW63, PW64, PW65, PW66, PW67, PW68, PW69, PW70, PW71, PW72, PW73, PW74, PW75, PW76, PW77, PW78, PH30, PH31, PH32, PH33, PH34, PH35, PH36, PH37, PH38, PH39, PH40, PH41, PH42, PH43, PH44, PH49, PH50, ZE1, ZE2, ZE3, TR21, TR22, TR23, TR24, TR25, BT74, BT92, BT93, BT94', 'Comma separated list of postcodes NOT ALLOWED', '6', '0', now())");	
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_PFBY12_STATUS', 'MODULE_SHIPPING_PFBY12_TAX_CLASS', 'MODULE_SHIPPING_PFBY12_SORT_ORDER', 'MODULE_SHIPPING_PFBY12_MAX_WEIGHT', 'MODULE_SHIPPING_PFBY12_MIN_WEIGHT', 'MODULE_SHIPPING_PFBY12_DISALLOWED');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_SHIPPING_PFBY12_CODES_' . $i;
        $keys[] = 'MODULE_SHIPPING_PFBY12_COST_' . $i;
        $keys[] = 'MODULE_SHIPPING_PFBY12_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>
