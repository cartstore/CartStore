<?php
/*

 $Id: worldzones.php,v 1.20 2003/06/15 19:48:09 thomasamoulton Exp $

  Some changes to zonesworld for MS2
  by Paul Mathot 2004/05/12
  added: $shipping_num_boxes and shippingtax
  it's a direct replacement for zones.php now
  credits to the osCommerce team and elari

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2002-2003 elari for osCommerce

 Released under the GNU General Public License

 USAGE
 By default, the module comes with support for 1 zone.  This can be
 easily changed by editing the line below in the zones constructor
 that defines $this->num_zones.

 elari
 i have made some change to this module, that come now with 3 default zone
 1 : NL can be changed for your country
 2 : AT,BE,GB,DE,FR,GL,IS,IE,IT,NO,DK,PL,ES,SE,CH,FI,PT,IL,GR   that are other European country except NL defined in zone 1
 3 : All Other World Country not defined.
     If you define more zone, keep in mind that this is the lastest zone that will be used for country not listed

 Alane #################
 I have added the option to figure shipping by percent of the order total.
 In the Zone X Shipping table just write the percentage of the order total
 you wish to use (ie 25% would be written as 0.25)
 #######################

 Next, you will want to activate the module by going to the Admin screen,
 clicking on Modules, then clicking on Shipping.  A list of all shipping
 modules should appear.  Click on the green dot next to the one labeled
 zones.php.  A list of settings will appear to the right.  Click on the
 Edit button.

 PLEASE NOTE THAT YOU WILL LOSE YOUR CURRENT SHIPPING RATES AND OTHER
 SETTINGS IF YOU TURN OFF THIS SHIPPING METHOD.  Make sure you keep a
 backup of your shipping settings somewhere at all times.

 If you want an additional handling charge applied to orders that use this
 method, set the Handling Fee field.

 Next, you will need to define which countries are in each zone.  Determining
 this might take some time and effort.  You should group a set of countries
 that has similar shipping charges for the same weight.  For instance, when
 shipping from the US, the countries of Japan, Australia, New Zealand, and
 Singapore have similar shipping rates.  As an example, one of my customers
 is using this set of zones:
   1: USA
   2: Canada
   3: Austria, Belgium, Great Britain, France, Germany, Greenland, Iceland,
      Ireland, Italy, Norway, Holland/Netherlands, Denmark, Poland, Spain,
      Sweden, Switzerland, Finland, Portugal, Israel, Greece
   4: Japan, Australia, New Zealand, Singapore
   5: Taiwan, China, Hong Kong

 When you enter these country lists, enter them into the Zone X Countries
 fields, where "X" is the number of the zone.  They should be entered as
 two character ISO country codes in all capital letters.  They should be
 separated by commas with no spaces or other punctuation. For example:
   1: US
   2: CA
   3: AT,BE,GB,FR,DE,GL,IS,IE,IT,NO,NL,DK,PL,ES,SE,CH,FI,PT,IL,GR
   4: JP,AU,NZ,SG
   5: TW,CN,HK

 Now you need to set up the shipping rate tables for each zone.  Again,
 some time and effort will go into setting the appropriate rates.  You
 will define a set of weight ranges and the shipping price for each
 range.  For instance, you might want an order than weighs more than 0
 and less than or equal to 3 to cost 5.50 to ship to a certain zone.
 This would be defined by this:  3:5.5

 You should combine a bunch of these rates together in a comma delimited
 list and enter them into the "Zone X Shipping Table" fields where "X"
 is the zone number.  For example, this might be used for Zone 1:
   1:3.5,2:3.95,3:5.2,4:6.45,5:7.7,6:10.4,7:11.85, 8:13.3,9:14.75,10:16.2,11:17.65,
   12:19.1,13:20.55,14:22,15:23.45

 The above example includes weights over 0 and up to 15.  Note that
 units are not specified in this explanation since they should be
 specific to your locale.

 CAVEATS
 At this time, it does not deal with weights that are above the highest amount
 defined.  This will probably be the next area to be improved with the
 module.  For now, you could have one last very high range with a very
 high shipping rate to discourage orders of that magnitude.  For
 instance:  999:1000

 If you want to be able to ship to any country in the world, you will
 need to enter every country code into the Country fields. For most
 shops, you will not want to enter every country.  This is often
 because of too much fraud from certain places. If a country is not
 listed, then the module will add a $0.00 shipping charge and will
 indicate that shipping is not available to that destination.
 PLEASE NOTE THAT THE ORDER CAN STILL BE COMPLETED AND PROCESSED!
 elari : this has been changed, now the country not listed use the rate defined for
 lastest zone

 It appears that the osC shipping system automatically rounds the
 shipping weight up to the nearest whole unit.  This makes it more
 difficult to design precise shipping tables.  If you want to, you
 can hack the shipping.php file to get rid of the rounding.

 Lastly, there is a limit of 255 characters on each of the Zone
 Shipping Tables and Zone Countries.

*/

 class worldzones {
   var $code, $title, $description, $enabled, $num_zones;

// class constructor
   function worldzones() {
     $this->code = 'worldzones';
     $this->title = MODULE_SHIPPING_WORLDZONES_TEXT_TITLE;
     $this->description = MODULE_SHIPPING_WORLDZONES_TEXT_DESCRIPTION;
     $this->sort_order = MODULE_SHIPPING_WORLDZONES_SORT_ORDER;
     $this->icon = '';
     $this->tax_class = MODULE_SHIPPING_WORLDZONES_TAX_CLASS;
     $this->enabled = ((MODULE_SHIPPING_WORLDZONES_STATUS == 'True') ? true : false);

     // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
     $this->num_zones = 3;
   }

// class methods
   function quote($method = '') {
     global $order, $cart, $shipping_weight, $shipping_num_boxes;

      if (MODULE_SHIPPING_WORLDZONES_MODE == 'price') {
        $order_total = $cart->show_total();
	  } elseif (MODULE_SHIPPING_WORLDZONES_MODE == 'percent') {  // Alane - percent mod
        $order_total = $cart->show_total();
      } else {
        $order_total = $shipping_weight;
      }

     $dest_country = $order->delivery['country']['iso_code_2'];
     $dest_zone = 0;
     $error = false;

     for ($i=1; $i<=$this->num_zones; $i++) {
       $countries_table = constant('MODULE_SHIPPING_WORLDZONES_COUNTRIES_' . $i);
       $country_zones = preg_split("/[,]/", $countries_table);
       if (in_array($dest_country, $country_zones)) {
         $dest_zone = $i;
         break;
       }
     }

     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $dest_zone = $this->num_zones;    // the zone is the lastest zone avalaible
     }
     // elari - Added to select default country if not in listing
     if ($dest_zone == 0) {
       $error = true;      // this can no more achieve since by default the value is set to the max number of zones
     } else {
       $shipping = -1;
       $zones_cost = constant('MODULE_SHIPPING_WORLDZONES_COST_' . $dest_zone);

// Alane - Begin percent mod
	if (MODULE_SHIPPING_WORLDZONES_MODE == 'percent'){
	   $shipping = $order_total * $zones_cost ;
	   $shipping_method = MODULE_SHIPPING_WORLDZONES_TEXT_WAY . ' ' . $order->delivery['country']['title'] . ': ' .MODULE_SHIPPING_WORLDZONES_TEXT_MODE ;
	   if ($shipping_num_boxes > 1) {
             $shipping_method .= $shipping_num_boxes . 'x ';
           }

	}  else {
// Alane - End percent mod
       $zones_table = preg_split("/[:,]/" , $zones_cost);
       $size = sizeof($zones_table);
       for ($i=0; $i<$size; $i+=2) {
         if ($order_total <= $zones_table[$i]) {
           $shipping = $zones_table[$i+1];
           $shipping_method = MODULE_SHIPPING_WORLDZONES_TEXT_WAY . ' ' . $order->delivery['country']['title'] . ': ';
           if ($shipping_num_boxes > 1) {
             $shipping_method .= $shipping_num_boxes . 'x ';
           }
           (MODULE_SHIPPING_WORLDZONES_MODE == 'price' ? $shipping_method .= MODULE_SHIPPING_WORLDZONES_TEXT_MODE : $shipping_method .= $order_total . ' ' . MODULE_SHIPPING_WORLDZONES_TEXT_UNITS );
           break;
         }
       }
	}

       if ($shipping == -1) {
         $shipping_cost = 0;
         $shipping_method = MODULE_SHIPPING_WORLDZONES_UNDEFINED_RATE;
       } else {
         $shipping_cost = ($shipping * $shipping_num_boxes) + constant('MODULE_SHIPPING_WORLDZONES_HANDLING_' . $dest_zone);
       }
     }

     $this->quotes = array('id' => $this->code,
                           'module' => MODULE_SHIPPING_WORLDZONES_TEXT_TITLE,
                           'methods' => array(array('id' => $this->code,
                                                    'title' => $shipping_method,
                                                    'cost' => $shipping_cost)));

     if ($this->tax_class > 0) {
       $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
     }

     if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

     if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_WORLDZONES_INVALID_ZONE;

     return $this->quotes;
   }

   function check() {
     if (!isset($this->_check)) {
       $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_WORLDZONES_STATUS'");
       $this->_check = tep_db_num_rows($check_query);
     }
     return $this->_check;
   }

   // elari - Added to select default country if not in listing
   function install() {
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable World Zones Method', 'MODULE_SHIPPING_WORLDZONES_STATUS', 'True', 'Do you want to offer world zone rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_WORLDZONES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_WORLDZONES_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
// Alane - begin percent mod - added percent option to selection
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Table Method', 'MODULE_SHIPPING_WORLDZONES_MODE', 'price' , 'The shipping cost is based on the order total or the total weight of the items ordered.', '6', '0', 'tep_cfg_select_option(array(\'weight\', \'price\',\'percent\'), ', now())");
//Alane - end percent mod
     for ($i = 1; $i <= $this->num_zones; $i++) {
        $default_countries = '';
       if ($i == 1) {
      $default_countries = 'US,CA';
         $shipping_table = '3.50:1.25,7:1.40,10.50:1.55,14:1.65,17.50:1.80,21:2,24.50:2.2';
       }
       if ($i == 2) {
         $default_countries = 'AT,BE,GB,DE,FR,GL,IS,IE,IT,NO,DK,PL,ES,SE,CH,FI,PT,IL,GR,NL';
         $shipping_table = '3.50:6.50,7:6.65,10.50:6.8,14:7.05,17.50:7.3,21:7.45,24.5:7.6';
       }
       if ($i == 3) {
         $default_countries = 'All Others'; // this must be the lastest zone
         $shipping_table = '3.50:8.50,7:8.75,10.50:9,14:9.25,17.50:9.5,21:9.75,24.5:10';
       }
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_WORLDZONES_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_WORLDZONES_COST_" . $i ."', '" . $shipping_table . "', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'MODULE_SHIPPING_WORLDZONES_HANDLING_" . $i ."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
     }
   }
   // elari - Added to select default country if not in listing

   function remove() {
     tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
   }

   function keys() {
     $keys = array('MODULE_SHIPPING_WORLDZONES_STATUS', 'MODULE_SHIPPING_WORLDZONES_TAX_CLASS', 'MODULE_SHIPPING_WORLDZONES_SORT_ORDER', 'MODULE_SHIPPING_WORLDZONES_MODE');

     for ($i=1; $i<=$this->num_zones; $i++) {
       $keys[] = 'MODULE_SHIPPING_WORLDZONES_COUNTRIES_' . $i;
       $keys[] = 'MODULE_SHIPPING_WORLDZONES_COST_' . $i;
       $keys[] = 'MODULE_SHIPPING_WORLDZONES_HANDLING_' . $i;
     }

     return $keys;
   }
 }
?>