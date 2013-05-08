<?php
/*
  SEO_Assistant for OSC 2.2 2.0 v2.0  08.03.2004
  SEO Originally Created by: Jack York
  GNU General Public License Compatible
  CartStore eCommerce Software, for The Next Generation
  Copyright (c) 2008 Adoovo Inc. USA
*/
function linkcheck($path, $engine) {
	global $results;
	global $total;

  if(!file_exists($path)) {
		$data = strtolower(strip_tags(implode("", file($path))));
    if (FALSE === strpos($engine, 'msn'))
		  $data = substr($data, strpos($data, "of about")+9, strlen($data));
    else
      $data = substr($data, strpos($data, "of")+3, strlen($data));
		$data = substr($data, 0, strpos($data, " "));

    if(preg_match("/[[:alpha:]]/i", $data)) {
			$results[$engine] = array('0', $path);
		} else {
			$results[$engine] = array($data, $path);
			$total+=str_replace(',', '', $data);
		}
	} else {
		$results[$engine] = array('n/a', $path);
	}
}

function get_link_popularity($link_url) {
	global $results;
	global $total;

 if($link_url) {
	// the results from Google and MSN can be extracted the same way so a function is used to simplify the code
	linkcheck("http://www.google.com/search?hl=en&lr=&ie=UTF-8&q=link%3A".$link_url, 'google');
	linkcheck("http://search.msn.com/results.aspx?FORM=MSNH&q=link%3A".$link_url, 'msn');

	// check Yahoo!
	$path ="http://search.yahoo.com/search?p=linkdomain%3A".$link_url."&ei=UTF-8&fr=fp-tab-web-t&cop=mss&tab=";
	if(!file_exists($path)) {
		$data = strtolower(implode("", file($path)));
		$data = substr($data, strpos($data, "of about")+9, strlen($data));
		$data = strip_tags(substr($data, 0, strpos($data, " ")));
		if(preg_match("/[[:alpha:]]/i", $data)) {
			$results['yahoo'] = array('0', $path);
		} else {
			$results['yahoo'] = array($data, $path);
			$total+=str_replace(',', '', $data);
		}
	} else {
		$results['yahoo'] = array('n/a', $path);
	}

	// check AlltheWeb
	$path ="http://www.alltheweb.com/search?cat=web&cs=utf8&q=link%3A".$link_url."&rys=0&_sb_lang=pref";
	if(!file_exists($path)) {
		$data = strtolower(strip_tags(implode("", file($path))));
		$data = substr($data, strpos($data, "1 -")+5, strlen($data));
		$data = substr($data, 0, strpos($data, "results"));
		$data = trim(substr($data, strpos($data, "of")+3, strlen($data)));
		if(preg_match("/[[:alpha:]]/i", $data)) {
			$results['alltheweb'] = array('0', $path);
		} else {
			$results['alltheweb'] = array($data, $path);
			$total+=str_replace(',', '', $data);
		}
	} else {
		$results['alltheweb'] = array('n/a', $path);
	}

	// check HotBot
	$path ="http://www.hotbot.com/default.asp?query=linkdomain%3A".$link_url."&ps=&loc=searchbox&tab=web&provKey=Inktomi";
	if(!file_exists($path)) {
		$data = strtolower(strip_tags(implode("", file($path))));
		$data = substr($data, strpos($data, "results 1 - ")+11, strlen($data));
		$data = substr($data, 0, strpos($data, ")"));
		$data = trim(substr($data, strpos($data, "of")+3, strlen($data)));
		if(preg_match("/[[:alpha:]]/i", $data)) {
			$results['hotbot'] = array('0', $path);
		} else {
			$results['hotbot'] = array($data, $path);
			$total+=str_replace(',', '', $data);
		}
	} else {
		$results['hotbot'] = array('n/a', $path);
	}
	//http://www.altavista.com/web/results?q=linkdomain%3Awww.24-7mobileaccessories.co.uk/&kgs=1&kls=0&stq=10
	//http://www.altavista.com/web/results?itag=wrx&pg=aq&aqmode=s&aqa=joe&aqp=&aqo=&aqn=&aqb=&kgs=0&kls=0&dt=tmperiod&d2=0&dfr%5Bd%5D=1&dfr%5Bm%5D=1&dfr%5By%5D=1980&dto%5Bd%5D=6&dto%5Bm%5D=9&dto%5By%5D=2004&filetype=&rc=dmn&swd=www.24-7mobileaccessories.co.uk&lh=&nbq=10
	//http://www.altavista.com/web/results?itag=wrx&pg=aq&aqmode=s&aqa=joe&aqp=&aqo=&aqn=&aqb=&kgs=1&kls=0&dt=tmperiod&d2=0&dfr%5Bd%5D=1&dfr%5Bm%5D=1&dfr%5By%5D=1980&dto%5Bd%5D=6&dto%5Bm%5D=9&dto%5By%5D=2004&filetype=&rc=dmn&swd=www.24-7mobileaccessories.co.uk&lh=&nbq=10
	//http://www.altavista.com/web/results?q=linkdomain%3Awww.mycandysupplier.com&kgs=1&kls=0&stq=10
	// check AltaVista
	$path ="http://www.altavista.com/web/results?q=linkdomain%3A".$link_url."&kgs=0&kls=0&stq=10";

	if(!file_exists($path)) {
		$data = strtolower(strip_tags(implode("", file($path))));
		$data = substr($data, strpos($data, "altavista found")+15, strlen($data));
		$data = trim(substr($data, 0, strpos($data, "results"))); //echo "$data<br>"; // TEST
		if(preg_match("/[[:alpha:]]/i", $data)) {
			$results['altavista'] = array('0', $path);
		} else {
			$results['altavista'] = array($data, $path);
			$total+=str_replace(',', '', $data);
		}
	} else {
		$results['altavista'] = array('n/a', $path);
	}

	// check for listing in DMOZ
	$path ="http://search.dmoz.org/cgi-bin/search?search=".str_replace("www.", "", $link_url);
	if(!file_exists($path)) {
		$data = strip_tags(implode("", file($path)));
		if(strpos($data, "No Open Directory Project results found")) {
			$results['dmoz'] = array('No', $path);
		} else {
			$results['dmoz'] = array('Yes', $path);
		}
	} else {
		$results['dmoz'] = array('n/a', $path);
	}

	// check for listing in Zeal
	$path ="http://www.zeal.com/search/results.jhtml?keyword=".$link_url."&scope=directory";
	if(!file_exists($path)) {
		$data = implode("", file($path)); //echo $data; // TEST
		if(strpos($data, "found no results")) {
			$results['zeal'] = array('No', $path);
		} else {
			$results['zeal'] = array('Yes', $path);
		}
	} else {
		$results['zeal'] = array('n/a', $path);
	}

	// get Alexa Traffic Rank
	$path ="http://www.alexa.com/data/details/main?q=&url=http://".$link_url;
	if(!file_exists($path)) {
		$data = strtolower(strip_tags(implode("", file($path))));
		$data = substr($data, strpos($data, "traffic rank for ")+17, strlen($data));
		$data = str_replace(str_replace('www.', '', $link_url), '', $data);
		$data = str_replace(':&nbsp;', '', $data);
		$data = trim(substr($data, 0, strpos(trim($data), ' ')-1)); //echo "$data<br>"; // TEST
		if(preg_match("/[[:alpha:]]/i", $data)) {
			$results['alexa'] = array('0', $path);
		} else {
			$results['alexa'] = array($data, $path);
		}
	} else {
		$results['alexa'] = array('n/a', $path);
	}
	return $results;
 }
 return false;
}
?>