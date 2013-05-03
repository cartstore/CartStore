<?php
/*
  Modification By Antony Thomas October 27,2008.
   Totally changed the concept by Antony Thomas ,www.incello.com.
   (version 2.0-total admin controlled)
   Now new postal codes and zones cost can be added by the admin easily.Not like everything
   built into database as previously where admin    could not change the postalcodes or shipping costs
   October 27,2008 by Antony Thomas , www.incello.com  ,email- thomasrj123@yahoo.com

  VERY slight hack of dly.php by Dr. Bill Bailey, http://www.lowcarbnexus.com
  VERY slight hack of spu.php by dion made from original code by M. Halvorsen (http://www.arachnia-web.com)
  to allow local delivery from warehouse.  Dr. Bill Bailey, http://www.lowcarbnexus.com

  Made to work with latest check-out procedure by Matthijs (Mattice)
     >> e-mail:    mattice@xs4all.nl
     >> site:      http://www.matthijs.org




   CHANGES (v1.6) (wheeloftime):
   - added code for different delivery costs depending on weight or price.
   CHANGES (v1.5):
   - added code to enter maximum distance you will travel for local deliveries.
   CHANGES (v1.4):
   - added code to convert admin supplied postal codes to upper case.
   - added code to remove spaces from both admin & user supplied postal codes while comparing them.
   CHANGES (v1.3):
   - added code to convert user supplied postal codes to upper case.
   CHANGES (v1.2):
   - added selection of post codes (city codes) where this delivery is possible, it will not show up if the delivery is not
     in a selected city of the selected zone.
   CHANGES (v1.1):
   - added Minimum Total Order Value to configuration
   - updated code
   - added icon references
   CHANGES (v1.0):
   - formatted to work with latest checkout procedure
   - removed icon references
   - updated the db queries

  Released under the GNU General Public License

*/

  class dly3 {
    var $code, $title, $description, $icon, $enabled,$shiping_costs;

// class constructor
    function dly3() {
	  global $order;

      $this->code = 'dly3';
      $this->title = MODULE_SHIPPING_DLY3_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_DLY3_TEXT_DESCRIPTION;
	  $this->tax_class = MODULE_SHIPPING_DLY3_TAX_CLASS;
      $this->sort_order = MODULE_SHIPPING_DLY3_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_dly.gif'; // To remove icon change to: $this->icon = 'pixel_trans.gif';

	  $this->enabled = ((MODULE_SHIPPING_DLY3_STATUS == 'True') ? true : false);
// Beg Minimum Order Total required to activate module
      $this->min_order = MODULE_SHIPPING_DLY3_MINIMUM_ORDER_TOTAL;

    if ( ($order->info['total']) < ($this->min_order) ) {
          $this->enabled = false;
    }
// End Minimum Order Total required to activate module

	 // not nessary this code if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_DLY3_ZONE > 0) ) {

//modified code is below
if ($this->enabled == true) {
        $check_flag = false;
        $postzone_flag = false;


/*
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_DLY3_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
		//$zipcodes = explode (',', MODULE_SHIPPING_DLY3_ZIPCODE);

*/

//The above  MODULE_SHIPPING_DLY3_ZIPCODE code is only to check the order while entering the values such as shipping cost or postalcodes

//Postalcodes vary with the shipping zones.Cost also will vary.Our task is to find out the shipping cost for a postal code collection, //that is the shipping zone.

//foloowing code will check the customer postcode with the shiiping zone post codes.

		$zip_up0 = MODULE_SHIPPING_DLY3_ZIPCODE0;
		$zip_up0 = strtoupper($zip_up0);
		$zip_up0 = str_replace (' ', '', $zip_up0);
		$zipcodes0 = explode (',', $zip_up0);
		$order->delivery['postcode'] = strtoupper($order->delivery['postcode']);
		$order->delivery['postcode'] = str_replace (' ', '', $order->delivery['postcode']);


		$zip_up1 = MODULE_SHIPPING_DLY3_ZIPCODE1;
		$zip_up1 = strtoupper($zip_up1);
		$zip_up1 = str_replace (' ', '', $zip_up1);
		$zipcodes1 = explode (',', $zip_up1);



		$zip_up2 = MODULE_SHIPPING_DLY3_ZIPCODE2;
		$zip_up2 = strtoupper($zip_up2);
		$zip_up2 = str_replace (' ', '', $zip_up2);
		$zipcodes2 = explode (',', $zip_up2);

		$zip_up3 = MODULE_SHIPPING_DLY3_ZIPCODE3;
		$zip_up3 = strtoupper($zip_up3);
		$zip_up3 = str_replace (' ', '', $zip_up3);
		$zipcodes3 = explode (',', $zip_up3);


		$zip_up4 = MODULE_SHIPPING_DLY3_ZIPCODE4;
		$zip_up4 = strtoupper($zip_up4);
		$zip_up4 = str_replace (' ', '', $zip_up4);
		$zipcodes4 = explode (',', $zip_up4);


		$zip_up5 = MODULE_SHIPPING_DLY3_ZIPCODE5;
		$zip_up5 = strtoupper($zip_up5);
		$zip_up5 = str_replace (' ', '', $zip_up5);
		$zipcodes5 = explode (',', $zip_up5);

		$zip_up6 = MODULE_SHIPPING_DLY3_ZIPCODE6;
		$zip_up6 = strtoupper($zip_up6);
		$zip_up6 = str_replace (' ', '', $zip_up6);
		$zipcodes6 = explode (',', $zip_up6);

		$zip_up7 = MODULE_SHIPPING_DLY3_ZIPCODE7;
		$zip_up7 = strtoupper($zip_up7);
		$zip_up7 = str_replace (' ', '', $zip_up7);
		$zipcodes7 = explode (',', $zip_up7);


		$zip_up8 = MODULE_SHIPPING_DLY3_ZIPCODE8;
		$zip_up8 = strtoupper($zip_up8);
		$zip_up8 = str_replace (' ', '', $zip_up8);
		$zipcodes8 = explode (',', $zip_up8);


		$zip_up9 = MODULE_SHIPPING_DLY3_ZIPCODE9;
		$zip_up9 = strtoupper($zip_up9);
		$zip_up9 = str_replace (' ', '', $zip_up9);
		$zipcodes9 = explode (',', $zip_up9);

		$zip_up10 = MODULE_SHIPPING_DLY3_ZIPCODE10;
		$zip_up10 = strtoupper($zip_up10);
		$zip_up10 = str_replace (' ', '', $zip_up10);
		$zipcodes10 = explode (',', $zip_up10);

		$zip_up11 = MODULE_SHIPPING_DLY3_ZIPCODE11;
		$zip_up11 = strtoupper($zip_up11);
		$zip_up11 = str_replace (' ', '', $zip_up11);
		$zipcodes11 = explode (',', $zip_up11);

		$zip_up12 = MODULE_SHIPPING_DLY3_ZIPCODE12;
		$zip_up12 = strtoupper($zip_up12);
		$zip_up12 = str_replace (' ', '', $zip_up12);
		$zipcodes12 = explode (',', $zip_up12);


		$zip_up13 = MODULE_SHIPPING_DLY3_ZIPCODE13;
		$zip_up13 = strtoupper($zip_up13);
		$zip_up13 = str_replace (' ', '', $zip_up13);
		$zipcodes13 = explode (',', $zip_up13);


		$zip_up14 = MODULE_SHIPPING_DLY3_ZIPCODE14;
		$zip_up14 = strtoupper($zip_up14);
		$zip_up14 = str_replace (' ', '', $zip_up14);
		$zipcodes14 = explode (',', $zip_up14);


		$zip_up15 = MODULE_SHIPPING_DLY3_ZIPCODE15;
		$zip_up15 = strtoupper($zip_up15);
		$zip_up15 = str_replace (' ', '', $zip_up15);
		$zipcodes15 = explode (',', $zip_up15);

                $zip_up16 = MODULE_SHIPPING_DLY3_ZIPCODE16;
		$zip_up16 = strtoupper($zip_up16);
		$zip_up16 = str_replace (' ', '', $zip_up16);
		$zipcodes16 = explode (',', $zip_up16);

                $zip_up17 = MODULE_SHIPPING_DLY3_ZIPCODE17;
		$zip_up17 = strtoupper($zip_up17);
		$zip_up17 = str_replace (' ', '', $zip_up17);
		$zipcodes17 = explode (',', $zip_up17);

                $zip_up18 = MODULE_SHIPPING_DLY3_ZIPCODE18;
		$zip_up18 = strtoupper($zip_up18);
		$zip_up18 = str_replace (' ', '', $zip_up18);
		$zipcodes18 = explode (',', $zip_up18);

                $zip_up19 = MODULE_SHIPPING_DLY3_ZIPCODE19;
		$zip_up19 = strtoupper($zip_up19);
		$zip_up19 = str_replace (' ', '', $zip_up19);
		$zipcodes19 = explode (',', $zip_up19);

                $zip_up20 = MODULE_SHIPPING_DLY3_ZIPCODE20;
		$zip_up20 = strtoupper($zip_up20);
		$zip_up20 = str_replace (' ', '', $zip_up20);
		$zipcodes20 = explode (',', $zip_up20);

                $zip_up21 = MODULE_SHIPPING_DLY3_ZIPCODE21;
		$zip_up21 = strtoupper($zip_up21);
		$zip_up21 = str_replace (' ', '', $zip_up21);
		$zipcodes21 = explode (',', $zip_up21);

                $zip_up22 = MODULE_SHIPPING_DLY3_ZIPCODE22;
		$zip_up22 = strtoupper($zip_up22);
		$zip_up22 = str_replace (' ', '', $zip_up22);
		$zipcodes22 = explode (',', $zip_up22);

                //code is modified as follows
               if (in_array($order->delivery['postcode'], $zipcodes0)){

                 $check_flag = true;

               }elseif (in_array($order->delivery['postcode'], $zipcodes1)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes2)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes3)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes4)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes5)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes6)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes7)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes8)){

                 $check_flag = true;

                }elseif  (in_array($order->delivery['postcode'], $zipcodes9)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes10)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes11)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes12)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes13)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes14)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes15)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes16)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes17)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes18)){

                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes19)){
                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes20)){
                 $check_flag = true;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes21)){
                 $check_flag = true;

                }elseif  (in_array($order->delivery['postcode'], $zipcodes22)){
                 $check_flag = true;

               }

/*
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
		    if ((in_array($order->delivery['postcode'], $zipcodes)) or (MODULE_SHIPPING_DLY3_ZIPCODE0 == ''))
              $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
		      if ((in_array($order->delivery['postcode'], $zipcodes) or (MODULE_SHIPPING_DLY3_ZIPCODE0 == '')))
                $check_flag = true;
            break;
          }
		}

*/

		if ($check_flag == false) {
          $this->enabled = false;
        }

      }//end if
    }//end function dly3

// class methods

    function quote($method = '') {
	  global $order, $cart, $shipping_weight, $shipping_num_boxes;

      if (MODULE_SHIPPING_DLY3_MODE == 'price') {
        $order_total = $cart->show_total();
      } else {
        $order_total = $shipping_weight;
      }

                $zip_up0 = MODULE_SHIPPING_DLY3_ZIPCODE0;
		$zip_up0 = strtoupper($zip_up0);
		$zip_up0 = str_replace (' ', '', $zip_up0);
		$zipcodes0 = explode (',', $zip_up0);

		$zip_up1 = MODULE_SHIPPING_DLY3_ZIPCODE1;
		$zip_up1 = strtoupper($zip_up1);
		$zip_up1 = str_replace (' ', '', $zip_up1);
		$zipcodes1 = explode (',', $zip_up1);



		$zip_up2 = MODULE_SHIPPING_DLY3_ZIPCODE2;
		$zip_up2 = strtoupper($zip_up2);
		$zip_up2 = str_replace (' ', '', $zip_up2);
		$zipcodes2 = explode (',', $zip_up2);

		$zip_up3 = MODULE_SHIPPING_DLY3_ZIPCODE3;
		$zip_up3 = strtoupper($zip_up3);
		$zip_up3 = str_replace (' ', '', $zip_up3);
		$zipcodes3 = explode (',', $zip_up3);


		$zip_up4 = MODULE_SHIPPING_DLY3_ZIPCODE4;
		$zip_up4 = strtoupper($zip_up4);
		$zip_up4 = str_replace (' ', '', $zip_up4);
		$zipcodes4 = explode (',', $zip_up4);


		$zip_up5 = MODULE_SHIPPING_DLY3_ZIPCODE5;
		$zip_up5 = strtoupper($zip_up5);
		$zip_up5 = str_replace (' ', '', $zip_up5);
		$zipcodes5 = explode (',', $zip_up5);

		$zip_up6 = MODULE_SHIPPING_DLY3_ZIPCODE6;
		$zip_up6 = strtoupper($zip_up6);
		$zip_up6 = str_replace (' ', '', $zip_up6);
		$zipcodes6 = explode (',', $zip_up6);

		$zip_up7 = MODULE_SHIPPING_DLY3_ZIPCODE7;
		$zip_up7 = strtoupper($zip_up7);
		$zip_up7 = str_replace (' ', '', $zip_up7);
		$zipcodes7 = explode (',', $zip_up7);


		$zip_up8 = MODULE_SHIPPING_DLY3_ZIPCODE8;
		$zip_up8 = strtoupper($zip_up8);
		$zip_up8 = str_replace (' ', '', $zip_up8);
		$zipcodes8 = explode (',', $zip_up8);


		$zip_up9 = MODULE_SHIPPING_DLY3_ZIPCODE9;
		$zip_up9 = strtoupper($zip_up9);
		$zip_up9 = str_replace (' ', '', $zip_up9);
		$zipcodes9 = explode (',', $zip_up9);

		$zip_up10 = MODULE_SHIPPING_DLY3_ZIPCODE10;
		$zip_up10 = strtoupper($zip_up10);
		$zip_up10 = str_replace (' ', '', $zip_up10);
		$zipcodes10 = explode (',', $zip_up10);

		$zip_up11 = MODULE_SHIPPING_DLY3_ZIPCODE11;
		$zip_up11 = strtoupper($zip_up11);
		$zip_up11 = str_replace (' ', '', $zip_up11);
		$zipcodes11 = explode (',', $zip_up11);

		$zip_up12 = MODULE_SHIPPING_DLY3_ZIPCODE12;
		$zip_up12 = strtoupper($zip_up12);
		$zip_up12 = str_replace (' ', '', $zip_up12);
		$zipcodes12 = explode (',', $zip_up12);


		$zip_up13 = MODULE_SHIPPING_DLY3_ZIPCODE13;
		$zip_up13 = strtoupper($zip_up13);
		$zip_up13 = str_replace (' ', '', $zip_up13);
		$zipcodes13 = explode (',', $zip_up13);


		$zip_up14 = MODULE_SHIPPING_DLY3_ZIPCODE14;
		$zip_up14 = strtoupper($zip_up14);
		$zip_up14 = str_replace (' ', '', $zip_up14);
		$zipcodes14 = explode (',', $zip_up14);


		$zip_up15 = MODULE_SHIPPING_DLY3_ZIPCODE15;
		$zip_up15 = strtoupper($zip_up15);
		$zip_up15 = str_replace (' ', '', $zip_up15);
		$zipcodes15 = explode (',', $zip_up15);

                $zip_up16 = MODULE_SHIPPING_DLY3_ZIPCODE16;
		$zip_up16 = strtoupper($zip_up16);
		$zip_up16 = str_replace (' ', '', $zip_up16);
		$zipcodes16 = explode (',', $zip_up16);

                $zip_up17 = MODULE_SHIPPING_DLY3_ZIPCODE17;
		$zip_up17 = strtoupper($zip_up17);
		$zip_up17 = str_replace (' ', '', $zip_up17);
		$zipcodes17 = explode (',', $zip_up17);

                $zip_up18 = MODULE_SHIPPING_DLY3_ZIPCODE18;
		$zip_up18 = strtoupper($zip_up18);
		$zip_up18 = str_replace (' ', '', $zip_up18);
		$zipcodes18 = explode (',', $zip_up18);

                $zip_up19 = MODULE_SHIPPING_DLY3_ZIPCODE19;
		$zip_up19 = strtoupper($zip_up19);
		$zip_up19 = str_replace (' ', '', $zip_up19);
		$zipcodes19 = explode (',', $zip_up19);

                $zip_up20 = MODULE_SHIPPING_DLY3_ZIPCODE20;
		$zip_up20 = strtoupper($zip_up20);
		$zip_up20 = str_replace (' ', '', $zip_up20);
		$zipcodes20 = explode (',', $zip_up20);

                $zip_up21 = MODULE_SHIPPING_DLY3_ZIPCODE21;
		$zip_up21 = strtoupper($zip_up21);
		$zip_up21 = str_replace (' ', '', $zip_up21);
		$zipcodes21 = explode (',', $zip_up21);

                $zip_up22 = MODULE_SHIPPING_DLY3_ZIPCODE22;
		$zip_up22 = strtoupper($zip_up22);
		$zip_up22 = str_replace (' ', '', $zip_up22);
		$zipcodes22 = explode (',', $zip_up22);

                //code is modified as follows
               if (in_array($order->delivery['postcode'], $zipcodes0)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST0;


               }elseif (in_array($order->delivery['postcode'], $zipcodes1)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST1;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes2)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST2;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes3)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST3;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes4)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST4;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes5)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST5;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes6)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST6;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes7)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST7;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes8)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST8;


                }elseif  (in_array($order->delivery['postcode'], $zipcodes9)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST9;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes10)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST10;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes11)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST11;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes12)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST12;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes13)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST13;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes14)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST14;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes15)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST15;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes16)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST16;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes17)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST17;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes18)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST18;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes19)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST19;


               }elseif  (in_array($order->delivery['postcode'], $zipcodes20)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST20;

               }elseif  (in_array($order->delivery['postcode'], $zipcodes21)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST21;


                }elseif  (in_array($order->delivery['postcode'], $zipcodes22)){
                 $shiping_costs = MODULE_SHIPPING_DLY3_COST22;


               }

      $table_cost = preg_split("/[:,]/" , $shiping_costs);
      $shipping_rate = 0;
      $size = sizeof($table_cost);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($order_total <= $table_cost[$i]) {
          $shipping_rate = $table_cost[$i+1];
          break;
        }
      }

      if (MODULE_SHIPPING_DLY3_MODE == 'weight') {
        $shipping_rate = $shipping_rate * $shipping_num_boxes;
      }

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_DLY3_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_DLY3_TEXT_WAY,
                                                     'cost' =>  $shipping_rate)));

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

	  if ($this->tax_class > 0) {
       $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
     }

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_DLY3_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Local Delivery', 'MODULE_SHIPPING_DLY3_STATUS', 'True', 'Do you want to offer Local Delivery?', '6', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
// BOF Determine if costs table is based on price or weight
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Delivery Cost Method', 'MODULE_SHIPPING_DLY3_MODE', 'weight', 'The delivery cost is based on the order total or the total weight of the items ordered.', '6', '4', 'tep_cfg_select_option(array(\'weight\', \'price\'), ', now())");
// EOF Determine if costs table is based on price or weight

	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_DLY3_TAX_CLASS', '0', 'Use the following Tax Class on the Shipping Fee.', '6', '6', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
// Beg Minimum Order Total required to activate module
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum Order Total', 'MODULE_SHIPPING_DLY3_MINIMUM_ORDER_TOTAL', '0.00', 'What is the Minimum Order Total required for this option to be activated.', '6', '8', now())");
// End Minimum Order Total required to activate module
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Local Delivery Distance', 'MODULE_SHIPPING_DLY3_MAX_LOCAL_DISTANCE', '12 Km', 'What is the Maximum Local delivery distance which you will travel to deliver orders. [ ie. 12 Km ]', '6', '10', now())");

// Begin Shipping Zones
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_DLY3_ZONE', '', 'Only enable this shipping method for these SHIPPING ZONES . Separate with comma if several, empty if all. SHIPPING ZONES including letters must be in capital letters.', '6', '12', now())");
// End Shiiping Zones


// Begin ZipCode 0
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 0', 'MODULE_SHIPPING_DLY3_ZIPCODE0', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '14', now())");
// End ZipCode

 tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 0', 'MODULE_SHIPPING_DLY3_COST0', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '16', now())");

// Begin ZipCode 1
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 1', 'MODULE_SHIPPING_DLY3_ZIPCODE1', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '18', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost Zone 1', 'MODULE_SHIPPING_DLY3_COST1', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '20', now())");


// Begin ZipCode 2
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 2', 'MODULE_SHIPPING_DLY3_ZIPCODE2', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '22', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 2', 'MODULE_SHIPPING_DLY3_COST2', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '24', now())");

// Begin ZipCode 3
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 3', 'MODULE_SHIPPING_DLY3_ZIPCODE3', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '26', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 3', 'MODULE_SHIPPING_DLY3_COST3', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '28', now())");


// Begin ZipCode 4
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 4', 'MODULE_SHIPPING_DLY3_ZIPCODE4', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '30', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 4', 'MODULE_SHIPPING_DLY3_COST4', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '32', now())");

// Begin ZipCode 5
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 5', 'MODULE_SHIPPING_DLY3_ZIPCODE5', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '34', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 5', 'MODULE_SHIPPING_DLY3_COST5', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '36', now())");


// Begin ZipCode 6
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 6', 'MODULE_SHIPPING_DLY3_ZIPCODE6', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '38', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 6', 'MODULE_SHIPPING_DLY3_COST6', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '40', now())");


// Begin ZipCode 7
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 7', 'MODULE_SHIPPING_DLY3_ZIPCODE7', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '42', now())");
// End ZipCode


tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 7', 'MODULE_SHIPPING_DLY3_COST7', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '44', now())");


// Begin ZipCode 8
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 8', 'MODULE_SHIPPING_DLY3_ZIPCODE8', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '46', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 8', 'MODULE_SHIPPING_DLY3_COST8', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '48', now())");


// Begin ZipCode 9
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 9', 'MODULE_SHIPPING_DLY3_ZIPCODE9', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '50', now())");
// End ZipCode


tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 9', 'MODULE_SHIPPING_DLY3_COST9', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '52', now())");

// Begin ZipCode 10
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 10', 'MODULE_SHIPPING_DLY3_ZIPCODE10', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '54', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 10', 'MODULE_SHIPPING_DLY3_COST10', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '56', now())");


// Begin ZipCode 11
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 11', 'MODULE_SHIPPING_DLY3_ZIPCODE11', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '58', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 11', 'MODULE_SHIPPING_DLY3_COST11', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '60', now())");


// Begin ZipCode 12
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 12', 'MODULE_SHIPPING_DLY3_ZIPCODE12', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '62', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 12', 'MODULE_SHIPPING_DLY3_COST12', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '64', now())");


// Begin ZipCode 13
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 13 ', 'MODULE_SHIPPING_DLY3_ZIPCODE13', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '66', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 13', 'MODULE_SHIPPING_DLY3_COST13', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '68', now())");


// Begin ZipCode 14
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 14', 'MODULE_SHIPPING_DLY3_ZIPCODE14', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '70', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 14', 'MODULE_SHIPPING_DLY3_COST14', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '72', now())");


// Begin ZipCode 15
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 15', 'MODULE_SHIPPING_DLY3_ZIPCODE15', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '74', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 15', 'MODULE_SHIPPING_DLY3_COST15', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '76', now())");


// Begin ZipCode 16
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 16', 'MODULE_SHIPPING_DLY3_ZIPCODE16', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '78', now())");
// End ZipCode


tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 16', 'MODULE_SHIPPING_DLY3_COST16', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '80', now())");

// Begin ZipCode 17
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 17', 'MODULE_SHIPPING_DLY3_ZIPCODE17', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '82', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 17', 'MODULE_SHIPPING_DLY3_COST17', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '84', '4', now())");


// Begin ZipCode 18
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 18', 'MODULE_SHIPPING_DLY3_ZIPCODE18', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '86', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 18', 'MODULE_SHIPPING_DLY3_COST18', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '88', now())");

// Begin ZipCode 19
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 19', 'MODULE_SHIPPING_DLY3_ZIPCODE19', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '90', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 19', 'MODULE_SHIPPING_DLY3_COST19', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '92', now())");


// Begin ZipCode 20
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 20', 'MODULE_SHIPPING_DLY3_ZIPCODE20', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '94', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 20', 'MODULE_SHIPPING_DLY3_COST20', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '96', now())");


// Begin ZipCode 21
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 21', 'MODULE_SHIPPING_DLY3_ZIPCODE21', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '98', now())");
// End ZipCode
tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 21', 'MODULE_SHIPPING_DLY3_COST21', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '100', now())");


// Begin ZipCode 22
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zip codes 22', 'MODULE_SHIPPING_DLY3_ZIPCODE22', '', 'Only enable this shipping method for these ZIP / Post Codes. Separate with comma if several, empty if all. Postal Codes including letters must be in capital letters.', '6', '102', now())");
// End ZipCode

tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Local Delivery Cost zone 22', 'MODULE_SHIPPING_DLY3_COST22', '25:8.50,50:5.50,10000:0.00', 'The delivery cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc. Be aware: The Handling fee will NOT be added.', '6', '104', now())");


      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_DLY3_SORT_ORDER', '18', 'Sort order of display.', '6', '108', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_DLY3_STATUS',
// Begin costs method
                   'MODULE_SHIPPING_DLY3_MODE',
// End cost method
                   'MODULE_SHIPPING_DLY3_COST0',
                   'MODULE_SHIPPING_DLY3_COST1',
                   'MODULE_SHIPPING_DLY3_COST2',
                   'MODULE_SHIPPING_DLY3_COST3',
                   'MODULE_SHIPPING_DLY3_COST4',
                   'MODULE_SHIPPING_DLY3_COST5',
                   'MODULE_SHIPPING_DLY3_COST6',
                   'MODULE_SHIPPING_DLY3_COST7',
                   'MODULE_SHIPPING_DLY3_COST8',
                   'MODULE_SHIPPING_DLY3_COST9',
                   'MODULE_SHIPPING_DLY3_COST10',
                   'MODULE_SHIPPING_DLY3_COST11',
                   'MODULE_SHIPPING_DLY3_COST12',
                   'MODULE_SHIPPING_DLY3_COST13',
                   'MODULE_SHIPPING_DLY3_COST14',
                   'MODULE_SHIPPING_DLY3_COST15',
                   'MODULE_SHIPPING_DLY3_COST16',
                   'MODULE_SHIPPING_DLY3_COST17',
                   'MODULE_SHIPPING_DLY3_COST18',
                   'MODULE_SHIPPING_DLY3_COST19',
                   'MODULE_SHIPPING_DLY3_COST20',
                   'MODULE_SHIPPING_DLY3_COST21',
                   'MODULE_SHIPPING_DLY3_COST21',
                   'MODULE_SHIPPING_DLY3_COST22',
                   'MODULE_SHIPPING_DLY3_TAX_CLASS',
// Beg Minimum Order Total required to activate module
                   'MODULE_SHIPPING_DLY3_MINIMUM_ORDER_TOTAL',
// End Minimum Order Total required to activate module
		   'MODULE_SHIPPING_DLY3_MAX_LOCAL_DISTANCE',
// Begin ZipCode
                   'MODULE_SHIPPING_DLY3_ZIPCODE0',
                   'MODULE_SHIPPING_DLY3_ZIPCODE1',
                   'MODULE_SHIPPING_DLY3_ZIPCODE2',
                   'MODULE_SHIPPING_DLY3_ZIPCODE3',
                   'MODULE_SHIPPING_DLY3_ZIPCODE4',
                   'MODULE_SHIPPING_DLY3_ZIPCODE5',
                   'MODULE_SHIPPING_DLY3_ZIPCODE6',
                   'MODULE_SHIPPING_DLY3_ZIPCODE7',
                   'MODULE_SHIPPING_DLY3_ZIPCODE8',
                   'MODULE_SHIPPING_DLY3_ZIPCODE10',
                   'MODULE_SHIPPING_DLY3_ZIPCODE11',
                   'MODULE_SHIPPING_DLY3_ZIPCODE12',
                   'MODULE_SHIPPING_DLY3_ZIPCODE13',
                   'MODULE_SHIPPING_DLY3_ZIPCODE14',
                   'MODULE_SHIPPING_DLY3_ZIPCODE15',
                   'MODULE_SHIPPING_DLY3_ZIPCODE16',
                   'MODULE_SHIPPING_DLY3_ZIPCODE17',
                   'MODULE_SHIPPING_DLY3_ZIPCODE18',
                   'MODULE_SHIPPING_DLY3_ZIPCODE19',
                   'MODULE_SHIPPING_DLY3_ZIPCODE20',
                   'MODULE_SHIPPING_DLY3_ZIPCODE21',
                   'MODULE_SHIPPING_DLY3_ZIPCODE22',
// End ZipCode

                   'MODULE_SHIPPING_DLY3_SORT_ORDER',
                   'MODULE_SHIPPING_DLY3_ZONE');
    }
  }
?>
