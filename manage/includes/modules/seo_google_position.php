<?php
/*
  SEO_Assistant for OSC 2.2 2.0 v2.0  08.03.2004
  Originally Created by: Jack York
  GNU General Public License Compatible
  CartStore eCommerce Software, for The Next Generation
  Copyright (c) 2008 Adoovo Inc. USA
*/
	if(!$firstpass && !empty($searchquery) && !empty($searchurl))
  {
  	$query = str_replace(" ","+",$searchquery);
  	$query = str_replace("%26","&",$query);

  // The number of hits per page.

  	$hits_per_page = 10;

  // Obviously, the total pages / queries we will be doing is
  // $searchtotal / $hits_per_page

  // This will be our rank

  	$position      = 0;

  // This is the rank minus the duplicates

  	$real_position = 0;

  	$found_google   = NULL;
  	$lastURL = NULL;

  	for($i=0;$i<$searchtotal && empty($found_google);$i+=$hits_per_page)
  	{
      // Open the search page.
      // We are filling in certain variables -
      // $query,$hits_per_page and $start.

		  $filename = "http://www.google.com/search?as_q=$query".
  			"&num={$hits_per_page}&hl=en&ie=UTF-8&btnG=Google+Search".
  			"&as_epq=&as_oq=&as_eq=&lr=&as_ft=i&as_filetype=".
  			"&as_qdr=all&as_nlo=&as_nhi=&as_occt=any&as_dt=i".
  			"&as_sitesearch=&safe=images&start=$i";

  		$file = fopen($filename, "r");
  		if (!$file)
  		{
  			echo "<p>Unable to open remote file $filename.\n";
  		}
  		else
  		{
      // Now load the file into a variable line at a time

  			while (!feof($file))
  			{
  				$var = fgets($file, 1024);

          // Try and find the font tag google uses to show the site URL
	        if (preg_match("/<p class=g><a href=([^>]*)>/i",$var,$out))
  				{
            // If we find it take out any <B> </B> tags - google does
            // highlight search terms within URLS

  					$out[1] = strtolower($out[1]);

           // Get the domain name by looking for the first /

           if ( preg_match( "/(http[s]?:\/\/([^\/]*))\//i", $out[1], $urlstring ) )
             $url = $urlstring[1];
  					$position++;

  // If you want to see the hits, set $showlinks_google to something

  					if($showlinks)
						  $siteresults_google[] = $url;

  // If the last result process is the same as this one, it
  // is a nest or internal domain result, so don't count it
  // on $real_position

  					if(strcmp($lastURL,$url)<>0)
						  $real_position++;

  					$lastURL = $url;

  // Else if the sites match we have found it!!!

  					if(strcmp($searchurl,$url)==0)
  					{
  						$found_google = $position;

  // We quit out, we don't need to go any further.

  						break;
  					}
  				}
	 			}
  		}
  		fclose($file);
  	}

    $siteName = 'Google';

  	if($found_google)
  	{
  		$result_google = "The site $searchurl is at position $found_google ".
  			  "( $real_position ) for the term <b>$searchquery</b>" . " on " . "$siteName";

	    $google_prev_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_GOOGLE . " where search_url = '$searchurl'" . " AND search_term = '$searchquery'" . " AND rank != 99999" . " ORDER BY date ASC") or die("Query failed");;
      $google_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_GOOGLE . " where search_url = '$searchurl'" . " AND search_term = '$searchquery'" . " AND rank != 99999" . " ORDER BY date ASC") or die("Query failed");;

			if (mysql_num_rows($google_query) < $maxEntries)
			{
			  tep_db_query("insert into " . TABLE_SEO_GOOGLE . "(date, search_url, search_term, rank, sites_searched ) values	(now(), '". $searchurl ."', '" . $searchquery . "', '". $found_google ."', '". $searchtotal ."' )      ");
			}
			else
			{
			  $whichRow = 0;
				$maxRow = 1;
				$latestDate = '';
				$nextDate = '';
				$firstDate = '';

        while ($google = tep_db_fetch_array($google_query))
				{
				   if (empty($firstDate))
				    $firstDate = $google['date'];

	  		   if (strcmp($latestDate, $google['date']) < 0 )
					 {
					   $latestDate = $google['date'];
						 $whichRow =$maxRow;
			     }
					 elseif (empty($nextDate))
					 {
					   $nextDate = $google['date'];
					 }
			     $maxRow++;
			  }

				$latestDate = ($whichRow == mysql_num_rows($google_query)) ? $firstDate : $nextDate;
			  tep_db_query("update " . TABLE_SEO_GOOGLE . " set search_url = '" . $searchurl . "', search_term = '" . $searchquery . "', rank = " . $found_google . ", sites_searched = " . (int)$searchtotal . ", date = now()" . " where search_url = '" . $searchurl . "' and search_term = '" . $searchquery . "' and date = '" . $latestDate ."'");
			}
	 	}
  	else
  	{
  		$result_google = "The site $searchurl is not in the top $searchtotal ".
  			  "for the term <b>$searchquery</b>" . " on " . "$siteName";
  	}
  }
?>
