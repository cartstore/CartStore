<?php
/*=======================================================================*\
|| #################### //-- SCRIPT INFO --// ########################### ||
|| #	Script name: meta_tags.php                                      # ||
|| #	Contribution: cDynamic Meta Tags                                # ||
|| #	Version: 1.3                                                    # ||
|| #	Date: April 15 2005                                             # ||
|| # ------------------------------------------------------------------ # ||
|| #################### //-- COPYRIGHT INFO --// ######################## ||
|| #	Copyright (C) 2005 Chris LaRocque								# ||
|| #																	# ||
|| #	This script is free software; you can redistribute it and/or	# ||
|| #	modify it under the terms of the GNU General Public License		# ||
|| #	as published by the Free Software Foundation; either version 2	# ||
|| #	of the License, or (at your option) any later version.			# ||
|| #																	# ||
|| #	This script is distributed in the hope that it will be useful,	# ||
|| #	but WITHOUT ANY WARRANTY; without even the implied warranty of	# ||
|| #	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the	# ||
|| #	GNU General Public License for more details.					# ||
|| #																	# ||
|| #	Script is intended to be used with:								# ||
|| #	CartStore eCommerce Software, for The Next Generation					# ||
|| #	http://www.cartstore.com										# ||
|| #	Copyright (c) 2008 Adoovo Inc. USA									# ||
|| ###################################################################### ||
\*========================================================================*/

##########################################################################################################
function meta_create_title($metatitle, $length = 70) {
	$metatitle = truncate_string(meta_simple_strip_tags($metatitle), $length);
	if (strlen($metatitle) > strlen($metatitle)) {
		$metatitle.= "...";
	}
	return $metatitle;
}

function meta_simple_strip_tags($str){
	$untagged = "";
	$skippingtag = false;
	for ($i = 0; $i < strlen($str); $i++) {
		if (!$skippingtag) {
			if ($str[$i] == "<") {
				$skippingtag = true;
			} else {
				if ($str[$i]==" " || $str[$i]=="\n" || $str[$i]=="\r" || $str[$i]=="\t") {
					$untagged .= " ";
				} else {
					$untagged .= $str[$i];
				}
			}
		} else {
			if ($str[$i] == ">") {
				$untagged .= " ";
				$skippingtag = false;
			}
		}
	}
	$untagged = preg_replace("/[\"\n\r\t\s ]+/i", " ", $untagged); // remove multiple spaces, returns, tabs, etc.
	if (substr($untagged,-1) == ' ') { $untagged = substr($untagged,0,strlen($untagged)-1); } // remove space from end of string
	if (substr($untagged,0,1) == ' ') { $untagged = substr($untagged,1,strlen($untagged)-1); } // remove space from start of string
	if (substr($untagged,0,12) == 'DESCRIPTION ') { $untagged = substr($untagged,12,strlen($untagged)-1); } // remove 'DESCRIPTION ' from start of string
	return $untagged;
}

// Small Strip Array
function xstripper($strip) {
$strip_array = array("&reg;", "®", "&trade;","™","&nbsp;","&amp;", "&", ":"); 
$strip=str_replace($strip_array, '',$strip);
  return meta_simple_strip_tags($strip);
}


// Strip HTML and Truncate to create a META description
function meta_create_meta_description($str, $length = 225) {
	$str=xstripper($str);
	$str=str_replace('- -', '',$str);
	$meta_description = truncate_string(meta_simple_strip_tags($str), $length);
	if (strlen($str) > $length) {	// only display ... if case original string is longer than allowed length
		$meta_description .= "...";
	}
	return $meta_description;
}

function meta_create_meta_keywords($str, $length = 200) {
$str=xstripper($str);
$exclude = array('description','save','month','year','hundreds','dollars','per',"a","ii","about","above","according","across","39","actually","ad","adj","ae","af","after","afterwards","ag","again","against","ai","al","all","almost","alone","along","already","also","although","always","am","among","amongst","an","and","another","any","anyhow","anyone","anything","anywhere","ao","aq","ar","are","aren","aren't","around","arpa","as","at","au","aw","az","b","ba","bb","bd","be","became","because","become","becomes","becoming","been","before","beforehand","begin","beginning","behind","being","below","beside","besides","between","beyond","bf","bg","bh","bi","billion","bj","bm","bn","bo","both","br","bs","bt","but","buy","bv","bw","by","bz","c","ca","can","can't","cannot","caption","cc","cd","cf","cg","ch","ci","ck","cl","click","cm","cn","co","co.","com","copy","could","couldn","couldn't","cr","cs","cu","cv","cx","cy","cz","d","de","did","didn","didn't","dj","dk","dm","do","does","doesn","doesn't","don","don't","down","during","dz","e","each","ec","edu","ee","eg","eh","eight","eighty","either","else","elsewhere","end","ending","enough","er","es","et","etc","even","ever","every","everyone","everything","everywhere","except","f","few","fi","fifty","find","first","five","fj","fk","fm","fo","for","former","formerly","forty","found","four","fr","free","from","further","fx","g","ga","gb","gd","ge","get","gf","gg","gh","gi","gl","gm","gmt","gn","go","gov","gp","gq","gr","gs","gt","gu","gw","gy","h","had","has","hasn","hasn't","have","haven","haven't","he","he'd","he'll","he's","help","hence","her","here","here's","hereafter","hereby","herein","hereupon","hers","herself","him","himself","his","hk","hm","hn","home","homepage","how","however","hr","ht","htm","html","http","hu","hundred","i","i'd","i'll","i'm","i've","i.e.","id","ie","if","il","im","in","inc","inc.","indeed","information","instead","int","into","io","iq","ir","is","isn","isn't","it","it's","its","itself","j","je","jm","jo","join","jp","k","ke","kg","kh","ki","km","kn","kp","kr","kw","ky","kz","l","la","last","later","latter","lb","lc","least","less","let","let's","li","like","likely","lk","ll","lr","ls","lt","ltd","lu","lv","ly","m","ma","made","make","makes","many","maybe","mc","md","me","meantime","meanwhile","mg","mh","microsoft","might","mil","million","miss","mk","ml","mm","mn","mo","more","moreover","most","mostly","mp","mq","mr","mrs","ms","msie","mt","mu","much","must","mv","mw","mx","my","myself","mz","n","na","namely","nc","ne","neither","net","netscape","never","nevertheless","new","next","nf","ng","ni","nine","ninety","nl","no","nobody","none","nonetheless","noone","nor","not","nothing","now","nowhere","np","nr","nu","nz","o","of","off","often","om","on","once","one","one's","only","onto","or","org","other","others","otherwise","our","ours","ourselves","out","over","overall","own","p","pa","page","pe","per","perhaps","pf","pg","ph","pk","pl","pm","pn","pr","pt","pw","py","q","qa","r","rather","re","recent","recently","reserved","ring","ro","ru","rw","s","sa","same","sb","sc","sd","se","seem","seemed","seeming","seems","seven","seventy","several","sg","sh","she","she'd","she'll","she's","should","shouldn","shouldn't","si","since","site","six","sixty","sj","sk","sl","sm","sn","so","some","somehow","someone","something","sometime","sometimes","somewhere","sr","st","still","stop","su","such","sv","sy","sz","t","taking","tc","td","ten","text","tf","tg","test","th","than","that","that'll","that's","the","their","them","themselves","then","thence","there","there'll","there's","thereafter","thereby","therefore","therein","thereupon","these","they","they'd","they'll","they're","they've","thirty","this","those","though","thousand","three","through","throughout","thru","thus","tj","tk","tm","tn","to","together","too","toward","towards","tp","tr","trillion","tt","tv","tw","twenty","two","tz","u","ua","ug","uk","um","under","unless","unlike","unlikely","until","up","upon","us","use","used","using","uy","uz","v","va","vc","ve","very","vg","vi","via","vn","vu","w","was","wasn","wasn't","we","we'd","we'll","we're","we've","web","webpage","website","welcome","well","were","weren","weren't","wf","what","what'll","what's","whatever","when","whence","whenever","where","whereafter","whereas","whereby","wherein","whereupon","wherever","whether","which","while","whither","who","who'd","who'll","who's","whoever","NULL","whole","whom","whomever","whose","why","will","with","within","without","won","won't","would","wouldn","wouldn't","ws","www","x","y","ye","yes","yet","you","you'd","you'll","you're","you've","your","yours","yourself","yourselves","yt","yu","z","za","zm","zr","10","z",);

	$str=str_replace(' `~`~`', ',',$str);
	$str=str_replace('`~`~`,', '',$str);
	$str=str_replace(', ,', ',',$str);
	$str=str_replace(',,,', ',',$str);
	$str=str_replace(',,', '',$str);
	$splitstr = @explode("~`~`~`~`", truncate_string(meta_simple_strip_tags(str_replace(array(",","."),",", $str)), $length));
	
	$new_splitstr = array();
	foreach ($splitstr as $spstr) {
		if (strlen($spstr) > 4 && !(in_array(strtolower($spstr), $new_splitstr)) && !(in_array(strtolower($spstr), $exclude))) {
			$new_splitstr[] = strtolower($spstr);
		}
	}
	
	return @implode(", ", $new_splitstr);
}

// Truncate string to a specified length.
function truncate_string($string, $length = 70){
if (strlen($string) > $length) {
	$split = preg_split("/\n/", wordwrap($string, $length));
	return ($split[0]);
  }
  return ($string);
}
?>