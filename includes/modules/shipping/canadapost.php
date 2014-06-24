<?php
/*
  $Id: canadapost.php,v 0.3 2003/05/25 hpdl Exp $

  Before use this class, you should open a Canada  
  Post Eparcel Account, and change the CPCIP to 
  your ID. Visit www.canadapost.ca for detail. 
   
  XML connection method with Canada Post. 

  This version only works with osCommerce MS1 version.
   
  Copyright (c) 2002,2003 Kelvin Zhang (kelvin@syngear.com) 
  Modified by Kenneth Wang (kenneth@cqww.net), 2003.05.25

  Released under the GNU General Public License 
*/

  class canadapost {
    var $code, $title, $descrption, $icon, $enabled, $types;

// class constructor
    function canadapost() {
      global $order;

      $this->code = 'canadapost';
      $this->title = MODULE_SHIPPING_CANADAPOST_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_CANADAPOST_TEXT_DESCRIPTION . '<p><b>CPC Profile</b><br><a href=http://sellonline.canadapost.ca/servlet/LogonServlet?Language=0 target=_blank><u>> Modify my profile <</u></a>';      $this->icon = DIR_WS_ICONS . 'shipping_canadapost.gif';
      $this->comments = '';
      $this->sort_order = MODULE_SHIPPING_CANADAPOST_SORT_ORDER;
      $this->tax_class = MODULE_SHIPPING_CANADAPOST_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_CANADAPOST_STATUS == 'True') ? true : false);
      
      $this->server = MODULE_SHIPPING_CANADAPOST_SERVERIP;
      $this->port = MODULE_SHIPPING_CANADAPOST_SERVERPOST;
      $this->language = MODULE_SHIPPING_CANADAPOST_LANGUAGE;
      $this->CPCID = MODULE_SHIPPING_CANADAPOST_CPCID;
      $this->turnaround_time = MODULE_SHIPPING_CANADAPOST_TIME;
      $this->items_qty = 0;
      $this->items_price = 0;

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_CANADAPOST_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_CANADAPOST_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $_POST, $order, $shipping_weight, $shipping_num_boxes, $cart;
      $this->items_qty = 0;
      $this->items_price = 0;

      $country_name = tep_get_countries(STORE_COUNTRY, true);
      $this->_canadapostOrigin(SHIPPING_ORIGIN_ZIP, $country_name['countries_iso_code_2']);
      $this->_canadapostDest($order->delivery['city'], $order->delivery['state'], $order->delivery['country']['iso_code_2'], $order->delivery['postcode']);

      $products_array = $cart->get_products();
      for ($i=0; $i<count($products_array); $i++) 
        $this->_addItem ($products_array[$i][quantity], $products_array[$i][final_price], $products_array[$i][weight], 
          		$this->default_length, $this->default_width, $this->default_height, $products_array[$i][name]);
     
      $canadapostQuote = $this->_canadapostGetQuote();
      if ( (is_array($canadapostQuote)) && (sizeof($canadapostQuote) > 0) ) {
        $this->quotes = array('id' => $this->code,
                              'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'kgs)');

        $methods = array();
        for ($i=0; $i<sizeof($canadapostQuote); $i++) {
          list($type, $cost) = each($canadapostQuote[$i]);

	  if ( $method == '' || $method == $type ) {
            $methods[] = array('id' => $type,
                             'title' => $type,
                             'cost' => (SHIPPING_HANDLING + MODULE_SHIPPING_CANADAPOST_HANDLING + $cost) * $shipping_num_boxes);
	  }
        }

        $this->quotes['methods'] = $methods;
      } else {
      	if ( $canadapostQuote != false ) {
      	    $errmsg = $canadapostQuote;
      	} else {
      	    $errmsg = 'An unknown error occured with the canadapost shipping calculations.';
	}
      	$errmsg .= '<br>If you prefer to use canadapost as your shipping method, please contact the store owner.';
        $this->quotes = array('module' => $this->title,
                              'error' => $errmsg);
                             
      }

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
      if (tep_not_null($this->comments)) $this->quotes['comments'] = $this->comments;

      return $this->quotes;
    }
    

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_CANADAPOST_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CanadaPost Shipping', 'MODULE_SHIPPING_CANADAPOST_STATUS', 'True', 'Do you want to offer Canada Post shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter CanadaPost Server IP', 'MODULE_SHIPPING_CANADAPOST_SERVERIP', '206.191.4.228', 'ip address of canada post server', '6', '11', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter CanadaPost Server Port', 'MODULE_SHIPPING_CANADAPOST_SERVERPOST', '30000', 'service port of canadapast server', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Selected Language(optional)', 'MODULE_SHIPPING_CANADAPOST_LANGUAGE', 'en', 'canada posr support two languages. en: english fr: franch', '6', '13', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Your CanadaPost Customer ID', 'MODULE_SHIPPING_CANADAPOST_CPCID', 'CPC_DEMO_XML', '(Canada Post Customer ID)Merchant Identification assigned by Canada Post', '6', '14', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Turn Around Time(optional)', 'MODULE_SHIPPING_CANADAPOST_TIME', '8', 'Turn Around Time (hours)', '6', '15', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_CANADAPOST_HANDLING', '0', 'Handling fee for this shipping method. Can also be used as an allowance for extra packaging weight etc.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_CANADAPOST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_CANADAPOST_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_CANADAPOST_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
  }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_CANADAPOST_STATUS', 'MODULE_SHIPPING_CANADAPOST_SERVERIP',
                    'MODULE_SHIPPING_CANADAPOST_SERVERPOST', 'MODULE_SHIPPING_CANADAPOST_LANGUAGE',
                    'MODULE_SHIPPING_CANADAPOST_CPCID', 'MODULE_SHIPPING_CANADAPOST_TIME',
                    'MODULE_SHIPPING_CANADAPOST_HANDLING',
                    'MODULE_SHIPPING_CANADAPOST_TAX_CLASS', 'MODULE_SHIPPING_CANADAPOST_ZONE', 
                    'MODULE_SHIPPING_CANADAPOST_SORT_ORDER');
    }


    function _canadapostOrigin($postal, $country){
      $this->_canadapostOriginPostalCode = str_replace(' ', '', $postal);
      $this->_canadapostOriginCountryCode = $country;
    }


    function _canadapostDest($dest_city,$dest_province,$dest_country,$dest_zip){
      $this->dest_city = $dest_city;
      $this->dest_province = $dest_province;
      $this->dest_country = $dest_country;
      $this->dest_zip = str_replace(' ', '', $dest_zip);
    }


    /*
      Add items to parcel. If $readytoship=1, this item will be shipped in its oringinal box
    */
    function _addItem ($quantity, $rate, $weight, $length, $width, $height, $description, $readytoship=0) {
      $index = $this->items_qty;
      $this->item_quantity[$index] = (string)$quantity;
      $this->item_weight[$index] = ( $weight ? (string)$weight : '0' );
      $this->item_length[$index] = ( $length ? (string)$length : '0' );
      $this->item_width[$index] = ( $width ? (string)$width : '0' );
      $this->item_height[$index] = ( $height ? (string)$height : '0' );
      $this->item_description[$index] = $description;
      $this->item_readytoship[$index] = $readytoship;
      $this->items_qty ++;
      $this->items_price += $quantity * $rate;
    }


    /* 
      using HTTP/POST send message to canada post server
    */
    function _sendToHost($host,$port,$method,$path,$data,$useragent=0) {
	// Supply a default method of GET if the one passed was empty
	if (empty($method))
	    $method = 'GET';
	$method = strtoupper($method);
	if ($method == 'GET')
	    $path .= '?' . $data;
	$buf = "";
	// try to connect to Canada Post server, for 2 second
	$fp = @fsockopen($host, $port, $errno, $errstr, 2);
	if ( $fp ) {
	  fputs($fp, "$method $path HTTP/1.1\n");
	  fputs($fp, "Host: $host\n");
	  fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	  fputs($fp, "Content-length: " . strlen($data) . "\n");
	  if ($useragent)
		fputs($fp, "User-Agent: u-feel.com / osCommerce\n");
	  fputs($fp, "Connection: close\n\n");
	  if ($method == 'POST')
		fputs($fp, $data);

	  while (!feof($fp))
		$buf .= fgets($fp,128);
	  fclose($fp);
	} else {
	  $buf = '<?xml version="1.0" ?><eparcel><error><statusMessage>Cannot reach Canada Post Server. You may refresh this page (Press F5) to try again.</statusMessage></error></eparcel>'; 
	}

	return $buf;
    }



    /*
      Get Canada Post shipping products that are available for current parcels
      This function will return an array include all available products. e.g:
        Array ( 
          [0] => Array ( 
            [name] => Priority Courier 
            [rate] => 25.35 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-27 
            [deliveryDayOfWeek] => 3 
            [nextDayAM] => true 
            [packingID] => P_0 
          ) 
          [1] => Array ( 
            [name] => Xpresspost 
            [rate] => 14.36 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-27 
            [deliveryDayOfWeek] => 3 
            [nextDayAM] => false 
            [packingID] => P_0 
          ) 
          [2] => Array ( 
            [name] => Regular 
            [rate] => 12.36 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-28 
            [deliveryDayOfWeek] => 4 
            [nextDayAM] => false 
            [packingID] => P_0 
          ) 
        )
      If the parcels can't be shipped or other error, this function will return 
      error message. e.g: "The parcel is too large to delivery."
    */ 
    function _canadapostGetQuote() {
	$strXML = "<?xml version=\"1.0\" ?>";

	// set package configuration.
	$strXML .= "<eparcel>\n";
	$strXML .= "        <language>" . $this->language . "</language>\n";
	$strXML .= "        <ratesAndServicesRequest>\n";
	$strXML .= "                <merchantCPCID>" . $this->CPCID . "</merchantCPCID>\n";
	$strXML .= "                <fromPostalCode>" . $this->_canadapostOriginPostalCode . "</fromPostalCode>\n";
	$strXML .= "                <turnAroundTime>" . $this->turnaround_time . "</turnAroundTime>\n";
	$strXML .= "                <itemsPrice>" . (string)$this->items_price . "</itemsPrice>\n";
	
	// add items information.
	$strXML .= "            <lineItems>\n";
	for ($i=0; $i < $this->items_qty; $i++) {
	        $this->item_description[$i] = str_replace("&", "and", $this->item_description[$i]);
		$strXML .= "	    <item>\n";
		$strXML .= "                <quantity>" . $this->item_quantity[$i] . "</quantity>\n";
		$strXML .= "                <weight>" . $this->item_weight[$i] . "</weight>\n";
		$strXML .= "                <length>" . $this->item_length[$i] . "</length>\n";
		$strXML .= "                <width>" . $this->item_width[$i] . "</width>\n";
		$strXML .= "                <height>" . $this->item_height[$i] . "</height>\n";
		$strXML .= "                <description>" . $this->item_description[$i] . "</description>\n";
		if ($this->item_readytoship[$i]) $strXML .= "                <readyToShip/>\n";
		$strXML .= "	    </item>\n";
	}
	$strXML .= "           </lineItems>\n";
	
	// add destination information.
	$strXML .= "               <city>" . $this->dest_city . "</city>\n";
	$strXML .= "               <provOrState>" . $this->dest_province . "</provOrState>\n";
	$strXML .= "               <country>" . $this->dest_country . "</country>\n";
	$strXML .= "               <postalCode>" . $this->dest_zip . "</postalCode>\n";
	$strXML .= "        </ratesAndServicesRequest>\n";
	$strXML .= "</eparcel>\n";
	
	//print $strXML;
	if ($resultXML = $this->_sendToHost($this->server,$this->port,'POST','',$strXML)) {
  	    return $this->_parserResult($resultXML);
	} else {
	    return false;
	}
    }	


    /*
      Parser XML message returned by canada post server.
    */
    function _parserResult($resultXML) {
    	$statusMessage = substr($resultXML, strpos($resultXML, "<statusMessage>")+strlen("<statusMessage>"), strpos($resultXML, "</statusMessage>")-strlen("<statusMessage>")-strpos($resultXML, "<statusMessage>"));
    	//print "message = $statusMessage";
    	if ($statusMessage == 'OK') {
    		$strProduct = substr($resultXML, strpos($resultXML, "<product id=")+strlen("<product id=>"), strpos($resultXML, "</product>")-strlen("<product id=>")-strpos($resultXML, "<product id="));
    		$index = 0;
    		$aryProducts = false;
    		while (strpos($resultXML, "</product>")) {
    			$name = substr($resultXML, strpos($resultXML, "<name>")+strlen("<name>"), strpos($resultXML, "</name>")-strlen("<name>")-strpos($resultXML, "<name>"));
    			$rate = substr($resultXML, strpos($resultXML, "<rate>")+strlen("<rate>"), strpos($resultXML, "</rate>")-strlen("<rate>")-strpos($resultXML, "<rate>"));
    			$shippingDate = substr($resultXML, strpos($resultXML, "<shippingDate>")+strlen("<shippingDate>"), strpos($resultXML, "</shippingDate>")-strlen("<shippingDate>")-strpos($resultXML, "<shippingDate>"));
    			$deliveryDate = substr($resultXML, strpos($resultXML, "<deliveryDate>")+strlen("<deliveryDate>"), strpos($resultXML, "</deliveryDate>")-strlen("<deliveryDate>")-strpos($resultXML, "<deliveryDate>"));
    			$deliveryDayOfWeek = substr($resultXML, strpos($resultXML, "<deliveryDayOfWeek>")+strlen("<deliveryDayOfWeek>"), strpos($resultXML, "</deliveryDayOfWeek>")-strlen("<deliveryDayOfWeek>")-strpos($resultXML, "<deliveryDayOfWeek>"));
    			$nextDayAM = substr($resultXML, strpos($resultXML, "<nextDayAM>")+strlen("<nextDayAM>"), strpos($resultXML, "</nextDayAM>")-strlen("<nextDayAM>")-strpos($resultXML, "<nextDayAM>"));
    			$packingID = substr($resultXML, strpos($resultXML, "<packingID>")+strlen("<packingID>"), strpos($resultXML, "</packingID>")-strlen("<packingID>")-strpos($resultXML, "<packingID>"));
    			$aryProducts[$index] = array( $name . ', ' . $deliveryDate => $rate);
    			$index++;
    			$resultXML = substr($resultXML, strpos($resultXML, "</product>") + strlen("</product>"));
    		}
    		return $aryProducts;
    	} else {
    		if (strpos($resultXML, "<error>")) return $statusMessage;
    		else return false;
    	}
    }


  }
?>
