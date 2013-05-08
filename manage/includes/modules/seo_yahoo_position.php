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

  	$hits_per_page = 100;

  // Obviously, the total pages / queries we will be doing is
  // $searchtotal / $hits_per_page

  // This will be our rank

  	$position      = 0;

  // This is the rank minus the duplicates

  	$real_position = 0;

  	$found_yahoo   = NULL;
  	$lastURL = NULL;

  	for($i=0;$i<$searchtotal && empty($found_yahoo);$i+=$hits_per_page)
  	{
      // Open the search page.
      // We are filling in certain variables -
      // $query,$hits_per_page and $start.

   		$page_var=$i+1;
			$filename = "http://search.yahoo.com/search?_adv_prop=web&x=op&ei=UTF-8".
                  "&prev_vm=p&va=$query&va_vt=any&vp=&vp_vt=any&vo=&vo_vt=any".
                  "&ve=&ve_vt=any&vd=all&vst=0&vs=&vf=all&vm=p".
                  "&vc=&fl=0&n={$hits_per_page}&b=$page_var";

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

          // Try and find the font tag yahoo uses to show the site URL

                if (preg_match("/<a class=yschttl  href=\".*\/\*-([^\"]*)\">/i",$var,$out))
  				{
            // If we find it take out any <B> </B> tags - yahoo does
            // highlight search terms within URLS

  					$out[1] = strtolower($out[1]);

           // Get the domain name by looking for the first /

           if ( preg_match( "/(http[s]?:\/\/([^\/]*))\//i", urldecode($out[1]), $urlstring ) )
             $url = $urlstring[1];
  					$position++;

  // If you want to see the hits, set $showlinks_yahoo to something

  					if($showlinks)
						  $siteresults_yahoo[] = $url;

  // If the last result process is the same as this one, it
  // is a nest or internal domain result, so don't count it
  // on $real_position

  					if(strcmp($lastURL,$url)<>0)
						  $real_position++;

  					$lastURL = $url;

  // Else if the sites match we have found it!!!

  					if(strcmp($searchurl,$url)==0)
  					{
  						$found_yahoo = $position;

  // We quit out, we don't need to go any further.

  						break;
  					}
  				}
   			}
  		}
  		fclose($file);
  	}

    $siteName = 'Yahoo';

  	if($found_yahoo)
  	{
  		$result_yahoo = "The site $searchurl is at position $found_yahoo ".
  			  "( $real_position ) for the term <b>$searchquery</b>" . " on " . "$siteName";

      $yahoo_prev_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_YAHOO . " where search_url = '$searchurl'" . " AND search_term = '$searchquery'" . " AND rank != 99999" . " ORDER BY date ASC") or die("Query failed");;
      $yahoo_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_YAHOO . " where search_url = '$searchurl'" . " AND search_term = '$searchquery'" . " AND rank != 99999" . " ORDER BY date ASC") or die("Query failed");;

			if (mysql_num_rows($yahoo_query) < $maxEntries)
			{
			  tep_db_query("insert into " . TABLE_SEO_YAHOO . "(date, search_url, search_term, rank, sites_searched ) values	(now(), '". $searchurl ."', '" . $searchquery . "', '". $found_yahoo ."', '". $searchtotal ."' )      ");
			}
			else
			{
			  $whichRow = 0;
				$maxRow = 1;
				$latestDate = '';
				$nextDate = '';
				$firstDate = '';

        while ($yahoo = tep_db_fetch_array($yahoo_query))
				{
	    		 if (empty($firstDate))
 				     $firstDate = $yahoo['date'];

	  		   if (strcmp($latestDate, $yahoo['date']) < 0 )
					 {
					   $latestDate = $yahoo['date'];
						 $whichRow =$maxRow;
			     }
					 elseif (empty($nextDate))
					 {
					   $nextDate = $yahoo['date'];
					 }
			     $maxRow++;
			  }

				$latestDate = ($whichRow == mysql_num_rows($yahoo_query)) ? $firstDate : $nextDate;
	      tep_db_query("update " . TABLE_SEO_YAHOO . " set search_url = '" . $searchurl . "', search_term = '" . $searchquery . "', rank = " . $found_yahoo . ", sites_searched = " . (int)$searchtotal . ", date = now()" . " where search_url = '" . $searchurl . "' and search_term = '" . $searchquery . "' and date = '" . $latestDate ."'");
			}
	 	}
  	else
  	{
  		$result_yahoo = "The site $searchurl is not in the top $searchtotal ".
  			  "for the term <b>$searchquery</b>" . " on " . "$siteName";
  	}
  }
?>
