<?php error_reporting(0);

/*

  $Id: current_auctions_module.php,v 2.0 2004/09/15 22:49:58 hpdl Exp $



  CartStore eCommerce Software, for The Next Generation

  http://www.cartstore.com



  Copyright (c) 2008 Adoovo Inc. USA



  GNU General Public License Compatible

*/

?>

<!-- current_auctions //-->

<table id="cur_acutions"  border="0" width="100%" cellspacing="0" cellpadding="2">

  <tr>

    <td class="infoBoxHeading" align="center" width="15%"><?php echo TABLE_HEADING_ITEM; ?></td>

    <td class="infoBoxHeading" align="center" width="25%"><?php echo TABLE_HEADING_TITLE; ?></td>

    <td class="infoBoxHeading" width="10%"><?php echo TABLE_HEADING_START; ?></td>

    <td class="infoBoxHeading" width="15%"><?php echo TABLE_HEADING_END; ?></td>

    <td class="infoBoxHeading" width="10%"><?php echo TABLE_HEADING_TIME; ?></td>

    <td class="infoBoxHeading" align="center" width="15%"><?php echo TABLE_HEADING_PRICE; ?></td>

    <td class="infoBoxHeading" align="center" width="10%"><?php echo TABLE_HEADING_BIDDER; ?></td>

  </tr>



<?php

// Build the ebay url

$URL = 'http://cgi6.ebay.com/ws/eBayISAPI.dll?ViewListedItems&userid=' . EBAY_USERID . '&include=0&since=' . AUCTION_ENDED . '&sort=' . AUCTION_SORT . '&rows=0';



// Where to Start grabbing and where to End grabbing

$GrabStart = '<tr bgcolor=\"#ffffff\">';

$GrabEnd = 'About eBay';



// Open the file

$file = fopen("$URL", "r");



// Read the file



if (!function_exists('file_get_contents')) {

     $r = fread($file, 80000);

}

else {

    $r = file_get_contents($URL);

}





// Grab just the contents we want

$stuff = preg_match("/$GrabStart(.*)$GrabEnd/i", $r, $content);



if (tep_not_null($stuff)) {



// Get rid of some rubbish we don't need.

// And set things up to be split into lines and items.



$content[1] = str_replace("\r\n", "", $content[1]);

$content[1] = str_replace("\n", "", $content[1]);

$content[1] = str_replace("\r", "", $content[1]);

$content[1] = str_replace("</td>", "[ITEMS]", $content[1]);

$content[1] = str_replace("</tr>", "[LINES]\n", $content[1]);

$content[1] = preg_replace("'<[\/\!]*?[^<>]*?>'si" , "" , $content[1]);



// Line used during debug (for troubleshooting)

// echo "<hr>$content[1]<br><br> <hr>";





// Close the file

fclose($file);



$stuff = $content[1];



// Build our first array for EOF

$items = explode("[LINES]",$stuff);



// Loop through our lines



$count = "0";



foreach ($items as $listing) {

	// Break apart each line into individual items

	list($Itemnum,$Start,$End,$Price,$Title,$HighBidder ) = explode("[ITEMS]",$listing);



      // We want to get rid of the (*) from the High Bidder

      $HighBidder = str_replace("(*)" , "" , $HighBidder);



      // We want change the Available to the Buy It Now logo for the High Bidder

      $buy_it_now = tep_image(DIR_WS_IMAGES . 'icons/buy_it_now.gif');

      $HighBidder = str_replace("Available" , $buy_it_now , $HighBidder);



      // Get rid of some rubbish from itemnum we don't need.

      $Itemnum = str_replace("\n", "", $Itemnum);



	//Use a countdown to get Time Left

	//We first need to break apart End and convert the months to numbers

	$seperate = preg_split('/[- :]/', $End);



	$seperate[0] = str_replace("Jan", "1", $seperate[0]);

	$seperate[0] = str_replace("Feb", "2", $seperate[0]);

	$seperate[0] = str_replace("Mar", "3", $seperate[0]);

	$seperate[0] = str_replace("Apr", "4", $seperate[0]);

	$seperate[0] = str_replace("May", "5", $seperate[0]);

	$seperate[0] = str_replace("Jun", "6", $seperate[0]);

	$seperate[0] = str_replace("Jul", "7", $seperate[0]);

	$seperate[0] = str_replace("Aug", "8", $seperate[0]);

	$seperate[0] = str_replace("Sep", "9", $seperate[0]);

	$seperate[0] = str_replace("Oct", "10", $seperate[0]);

	$seperate[0] = str_replace("Nov", "11", $seperate[0]);

	$seperate[0] = str_replace("Dec", "12", $seperate[0]);



    	$month = $seperate[0];

    	$day = $seperate[1];

    	$year = $seperate[2];

    	$hour = $seperate[3] + AUCTION_TIMEZONE;

    	$minute = $seperate[4];

	$second = $seperate[5];



	// mktime is the marked time, and time() is the current time.

	$target = mktime($hour,$minute,$second,$month,$day,$year);

	$diff = $target - time();



	$days = ($diff - ($diff % 86400)) / 86400;

	$diff = $diff - ($days * 86400);

	$hours = ($diff - ($diff % 3600)) / 3600;

	$diff = $diff - ($hours * 3600);

	$minutes = ($diff - ($diff % 60)) / 60;

	$diff = $diff - ($minutes * 60);

	$seconds = ($diff - ($diff % 1)) / 1;



	// next we put it into a presentable format

	$Time_Left =  $days . "d" . " " . $hours . "h" . " " . $minutes . "m";



	// and last we want to print auction ended when the auction has ended

	if ($seconds <= 0) {

		$TimeLeft = "Auction Ended";

		}

	else {

		$TimeLeft = $Time_Left;

		}



		// Make sure we have content to print out and print it

		if ($Start && $End && $Title && ($count < AUCTION_DISPLAY)) {



			$count++;



			$class = ( $class == "infoBoxContents" ) ? "smallText" : "infoBoxContents";



                      // If Thumbnails are enabled show them

                      if (DISPLAY_THUMBNAILS == 1) {

	                echo '<tr>

                               <td class="' . $class . '" align="center" width="15%"><a href="' . AUCTION_URL . '/ws/eBayISAPI.dll?ViewItem&item=' . $Itemnum . '" target="_blank" class="auction_link"><img src="http://thumbs.ebaystatic.com/pict/' . $Itemnum . '.jpg">' . $Itemnum . '</a></td>

                               <td class="' . $class . '" width="25%"><a href="' . AUCTION_URL . '/ws/eBayISAPI.dll?ViewItem&item=' . $Itemnum . '" target="_blank">' . $Title . '</a></td>

                               <td class="' . $class . '" width="10%">' . $Start . '</td>

                               <td class="' . $class . '" width="15%">' . $End . '</td>

                               <td class="' . $class . '" align="center" width="10%"><font color="#FF0000">' . $TimeLeft . '</font></td>

                               <td class="' . $class . '" align="center" width="15%"><font color="#008000">' . $Price . '</font></td>

                               <td class="' . $class . '" align="center" width="10%">' . $HighBidder . '</td>

                             </tr>';



                      // Otherwise just show the Bid Now link

                      } else {

	                echo '<tr>

                               <td class="' . $class . '" align="center" width="15%"><a href="' . AUCTION_URL . '/ws/eBayISAPI.dll?ViewItem&item=' . $Itemnum . '" target="_blank">' . $Itemnum . '</a></td>

                               <td class="' . $class . '" width="25%"><a href="' . AUCTION_URL . '/ws/eBayISAPI.dll?ViewItem&item=' . $Itemnum . '" target="_blank">' . $Title . '</a></td>

                               <td class="' . $class . '" width="10%">' . $Start . '</td>

                               <td class="' . $class . '" width="15%">' . $End . '</td>

                               <td class="' . $class . '" align="center" width="10%"><font color="#FF0000">' . $TimeLeft . '</font></td>

                               <td class="' . $class . '" align="center" width="15%"><font color="#008000">' . $Price . '</font></td>

                               <td class="' . $class . '" align="center" width="10%">' . $HighBidder . '</td>

                             </tr>';

                            }



		}



	}

} else {

  echo '<tr>

          <td class="main" align="center" colspan="7"><br>' . TEXT_NO_PRODUCTS . '<br></td>

        </tr>';

}

?>

</table>



<!-- current_auctions_eof //-->

