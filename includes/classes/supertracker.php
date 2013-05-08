<?php
/*
  $Id: supertracker.php,v3.1 2005/10/22

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class supertracker {
  function supertracker(){

	}

	function update() {
  	global $cart, $_GET, $customer_id;
//// **** CONFIGURATION SECTION  **** ////

   //Comma Separate List of IPs which should not be recorded, for instance, your own PCs IP address, or
	 //that of your server if you are using Cron Jobs, etc
   $excluded_ips = '';

//// **** CONFIGURATION SECTION EOF **** ////
    $record_session = true;
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if ($excluded_ips != '') {
  		$ex_array = explode(',',$excluded_ips);
  		 foreach ($ex_array as $key => $ex_ip) {
  		   if ($ip_address == $ex_ip) $record_session = false;
	  	 }
		}

/* EXCLUDE BOTS BEGIN - REMOVE THIS LINE IF YOU WANT TO EXCLUDE BOTS FROM THE STATS
$agent = $_SERVER['HTTP_USER_AGENT'];
$bot_here = false;
$arr = array("msnbot", "googlebot", "slurp", "abot", "dbot", "ebot", "hbot", "kbot", "mbot", "nbot", "obot", "pbot", "rbot", "sbot", "tbot", "ybot", "bot.", "crawl", "slurp", "spider", "accoona", "acoon", "ah-ha.com", "ahoy", "altavista", "ananzi", "anthill", "appie", "arachnophilia", "arale", "araneo", "aranha", "architext", "aretha", "arks", "aspseek", "asterias", "atlocal", "atn", "atomz", "augurfind", "backrub", "bannana_bot", "big brother", "bjaaland", "blackwidow", "bloodhound", "boitho", "booch", "bradley", "calif", "cassandra", "ccubee", "cfetch", "churl", "cienciaficcion", "cmc", "collective", "combine system", "computingsite", "cusco", "deepindex", "deweb", "die blinde kuh", "digger", "dmoz", "docomo", "download express", "dwcp", "ebiness", "e-collector", "ejupiter", "emacs-w3 search engine", "esther", "evliya celebi", "ezresult", "falcon", "felix ide", "ferret", "fetchrover", "fido", "findlinks", "fireball", "fish search", "fouineur", "funnelweb", "gazz", "gcreep", "getterroboplus", "geturl", "glx", "goforit", "golem", "grabber", "grapnel", "griffon", "gromit", "grub", "gulliver", "hamahakki", "harvest", "havindex", "helix", "heritrix", "hku www octopus", "homerweb", "htdig", "html index", "html_analyzer", "htmlgobble", "hubater", "hyper-decontextualizer", "ia_archiver", "ibm_planetwide", "ichiro", "iconsurf", "iltrovatore", "image.kapsi.net", "imagelock", "incywincy", "indexer", "infobee", "informant", "infoseek", "ingrid", "inktomisearch.com", "inspector web", "intelliagent", "internet shinchakubin", "ip3000", "iron33", "israeli-search", "ivia", "jack", "jakarta", "java/", "javabee", "jetbot", "jumpstation", "katipo", "kdd-explorer", "kilroy", "knowledge", "kototoi", "labelgrabber", "lachesis", "larbin", "legs", "libwww", "linkalarm", "link validator", "linkscan", "linkwalker", "lockon", "lwp", "lycos", "magpie", "mantraagent", "marvin/", "mattie", "mediafox", "mediapartners", "mercator", "merzscope", "miva", "mj12", "mnogosearch", "moget", "monster", "moose", "motor", "multitext", "muncher", "muscatferret", "mwd.search", "myweb", "nameprotect", "nationaldirectory", "nazilla", "ncsa beta", "nec-meshexplorer", "nederland.zoek", "netcarta webmap engine", "netmechanic", "netresearchserver", "netscoop", "newscan-online", "ng/", "nhse", "nomad", "nutch", "nzexplorer", "objectssearch", "occam", "omni", "open text", "openfind", "orb search", "osis-project", "pack rat", "pageboy", "parasite", "partnersite", "patric", "pear.", "pegasus", "peregrinator", "pgp key agent", "phantom", "phpdig", "picosearch", "piltdownman", "pimptrain", "pinpoint", "pioneer", "piranha", "plumtreewebaccessor", "pompos", "poppelsdorf", "poppi", "popular iconoclast", "rambler", "raven search", "roach", "road runner", "roadhouse", "robbie", "robofox", "robozilla", "rules", "salty", "sbider", "scooter", "scrubby", "search.", "searchprocess", "seeker", "semanticdiscovery", "senrigan", "sg-scout", "shai'hulud", "shark", "shopwiki", "sidewinder", "sift", "simmany", "site searcher", "site valet", "sitetech-rover", "skymob.com", "sleek", "smartwit", "sna-", "snooper", "sohu", "speedfind", "spinner", "spyder", "steeler/", "suke", "suntek", "supersnooper", "surfnomore", "sven", "sygol", "szukacz", "tach black widow", "tarantula", "templeton", "/teoma", "t-h-u-n-d-e-r-s-t-o-n-e", "titan", "titin", "tkwww", "toutatis", "t-rex", "tutorgig", "ucsd", "udmsearch", "ultraseek", "url check", "vagabondo", "valkyrie", "verticrawl", "victoria", "vision-search", "volcano", "voyager/", "w3c_validator", "w3m2", "w3mir", "wallpaper", "wanderer", "web core", "web hopper", "web wombat", "webbandit", "webcatcher", "webcopy", "webfoot", "weblayers", "weblinker", "weblog monitor", "webmirror", "webquest", "webreaper", "websitepulse", "websnarf", "webstolperer", "webvac", "webwalk", "webwatch", "webwombat", "webzinger", "wget", "whatuseek", "whizbang", "whowhere", "wild ferret", "wire", "worldlight", "wwwc", "xenu", "xget", "xift", "yandex", "zao/", "zippp", "zyborg");
foreach ($arr as $bot) {
  if (preg_match("/$bot/i", $agent)) {
    $bot_here = true;
  }
}
if ($bot_here) {
  $record_session = false;
}

 EXCLUDE BOTS END OF SECTION - REMOVE THIS LINE IF YOU WANT TO EXCLUDE BOTS FROM THE STATS */

//Big If statement that stops us doing anything more if this IP is one of the
//ones we have chosen to exclude
    if ($record_session) {

  		$existing_session = false;
  		$thirty_ago_timestamp = strtotime("now") - (30*60);
  		$thirty_mins_ago = date('Y-m-d H:i:s', $thirty_ago_timestamp);
  		$browser_string = addslashes(tep_db_input(urldecode($_SERVER['HTTP_USER_AGENT'])));
			$ip_array = explode ('.',$ip_address);
			$ip_start = $ip_array[0] . '.' . $ip_array[1];
      //Find out if this user already appears in the supertracker db table

	//First thing to try is customer_id, if they are signed in

       if (isset($customer_id)) {
    		$query = "select * from supertracker where customer_id ='" . $customer_id . "'  and last_click > '" . $thirty_mins_ago . "'";
    		$result = tep_db_query($query);
  	  	if (tep_db_num_rows ($result) > 0) {
    	  	$existing_session = true;
        }
			 }

			//Next, we try this: compare first 2 parts of the IP address (Class B), and the browser
			//Identification String, which give us a good chance of locating the details for a given user. I reckon
			//that the chances of having more than 1 user within a 30 minute window with identical values
			//is pretty small, so hopefully this will work and should be more reliable than using Session IDs....


  		if (!$existing_session) {
    		$query = "select * from supertracker where browser_string ='" . $browser_string . "' and ip_address like '" . $ip_start . "%' and last_click > '" . $thirty_mins_ago . "'";
    		$result = tep_db_query($query);
  	  	if (tep_db_num_rows ($result) > 0) {
    	  	$existing_session = true;
        }
			}

			//If that didn't work, and we have something in the cart, we can use that to try and find the
			//record instead. Obviously, people with things in their cart don't just appear from nowhere!
			if (!$existing_session) {
			  if ($cart->count_contents()>0) {
      		$query = "select * from supertracker where cart_total ='" . $cart->show_total() . "'  and last_click > '" . $thirty_mins_ago . "'";
      		$result = tep_db_query($query);
    	  	if (tep_db_num_rows ($result) > 0) {
      	  	$existing_session = true;
          }
				}
			}


  		//Having worked out if we have a new or existing user session lets record some details....!
  		if ($existing_session) {
  		 //Existing tracked session, so just update relevant existing details
  		  $tracking_data = tep_db_fetch_array($result);
  		  $tracking_id = $tracking_data['tracking_id'];
  		  $products_viewed=$tracking_data['products_viewed'];
  			$added_cart = $tracking_data['added_cart'];
  			$completed_purchase = $tracking_data['completed_purchase'];
  			$num_clicks = $tracking_data['num_clicks']+1;
  		  $categories_viewed = unserialize($tracking_data['categories_viewed']);
				$cart_contents = unserialize($tracking_data['cart_contents']);
				$cart_total = $tracking_data['cart_total'];
				$order_id = $tracking_data['order_id'];
        if (isset($customer_id)) $cust_id=$customer_id;
  		  else $cust_id=$tracking_data['customer_id'];

  		  $current_page=addslashes(tep_db_input(urldecode($_SERVER['PHP_SELF'])));
    		$last_click = date('Y-m-d H:i:s');


  			//Find out if the customer has added something to their cart for the first time
  			if (($added_cart!='true') && ($cart->count_contents()>0)) $added_cart = 'true';

  			//Has a purchase just been completed?
  			if ((strstr($current_page, 'checkout_success.php'))&& ($completed_purchase!='true')) {
				  $completed_purchase='true';
					$order_q = "select orders_id from orders where customers_id = '" . $cust_id . "' ORDER BY date_purchased DESC";
					$order_result = tep_db_query($order_q);
					if (tep_db_num_rows($order_result) > 0) {
					  $order_row = tep_db_fetch_array($order_result);
						$order_id = $order_row['orders_id'];
					}
				}

  			//If customer is looking at a product, add it to the list of products viewed
  			if (strstr($current_page, 'product_info.php')) {
  			  $current_product_id = (int)$_GET['products_id'];
  			  if (!strstr($products_viewed, '*' . $current_product_id . '?')) {
  			    //Product hasn't been previously recorded as viewed
  				  $products_viewed .= '*' . $current_product_id . '?';
  			  }
  			}

  			//Store away their cart contents
				//But, the cart is dumped at checkout, so we don't want to overwrite the stored cart contents
				//In this case
  			 $current_cart_contents = serialize($cart->contents);
				 if (strlen($current_cart_contents)>6) {
				   $cart_contents = $current_cart_contents;
					 $cart_total = $cart->show_total();
				 }



  			//If we are on index.php, but looking at category results, make sure we record which category
  			if (strpos($current_page, 'index.php')) {
  			  if (isset($_GET['cPath'])) {
  				  $cat_id = $_GET['cPath'];
  					$cat_id_array = explode('_',$cat_id);
  					$cat_id = $cat_id_array[sizeof($cat_id_array)-1];
  					$categories_viewed[$cat_id]=1;
  				}
  			}

  			$categories_viewed = serialize($categories_viewed);
  			$query = "UPDATE supertracker set last_click='" . $last_click . "', exit_page='" . $current_page . "', num_clicks='" . $num_clicks . "', added_cart='" . $added_cart . "', categories_viewed='" . $categories_viewed . "', products_viewed='" . $products_viewed . "', customer_id='" . $cust_id . "', completed_purchase='" . $completed_purchase . "', cart_contents='" . $cart_contents . "', cart_total = '" . $cart_total . "', order_id = '" . $order_id . "' where tracking_id='" . $tracking_id . "'";
  		  tep_db_query($query);

  		}
  		else {
  		 //New vistor, so record referrer, etc
			 //Next line defines pages on which a new visitor should definitely not be recorded
			 $prohibited_pages = 'login.php,checkout_shipping.php,checkout_payment.php,checkout_process.php,checkout_confirmation.php,checkout_success.php';
  		 $current_page=addslashes(tep_db_input(urldecode($_SERVER['PHP_SELF'])));

			 if (!strpos($prohibited_pages, $current_page)) {
    		 $refer_data = addslashes(tep_db_input(urldecode($_SERVER['HTTP_REFERER'])));
    		 $refer_data = explode('?', $refer_data);
    		 $referrer=$refer_data[0];
    		 $query_string=$refer_data[1];

    		 $ip = $_SERVER['REMOTE_ADDR'];
  			 $browser_string = addslashes(tep_db_input(urldecode($_SERVER['HTTP_USER_AGENT'])));

         include(DIR_WS_INCLUDES . "geoip.inc");
         $gi = geoip_open(DIR_WS_INCLUDES . "GeoIP.dat",GEOIP_STANDARD);
         $country_name = geoip_country_name_by_addr($gi, $ip);
      	 $country_code = strtolower(geoip_country_code_by_addr($gi, $ip));
         geoip_close($gi);


    		 $time_arrived = date('Y-m-d H:i:s');
    		 $landing_page = addslashes(tep_db_input(urldecode($_SERVER['REQUEST_URI'])));
         $query = "INSERT INTO `supertracker` (`ip_address`, `browser_string`, `country_code`, `country_name`, `referrer`,`referrer_query_string`,`landing_page`,`exit_page`,`time_arrived`,`last_click`) VALUES ('" . $ip . "','" . $browser_string . "','" . $country_code . "', '" . $country_name . "', '" . $referrer . "', '" . $query_string . "','" . $landing_page . "','" . $current_page . "','" . $time_arrived . "','" . $time_arrived . "')";
    		 tep_db_query($query);

			 }//end if for prohibited pages
    	}//end else
		}//End big If statement (Record Exclusion for certain IPs)

 	}//End function update
}//End Class

?>