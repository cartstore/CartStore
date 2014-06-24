<?php	 
/*  PHP RSS Reader v1.1
    By Richard James Kendall 
    Bugs to richard@richardjameskendall.com 
    Free to use, please acknowledge me 
    
    Place the URL of an RSS feed in the $file variable.
   	
   	The $rss_channel array will be filled with data from the feed,
   	every RSS feed is different by by and large it should contain:
   	
   	Array {
   		[TITLE] = feed title
   		[DESCRIPTION] = feed description
   		[LINK] = link to their website
   		
   		[IMAGE] = Array {
   					[URL] = url of image
   					[DESCRIPTION] = alt text of image
   				}
   		
   		[ITEMS] = Array {
   					[0] = Array {
   							[TITLE] = item title
   							[DESCRIPTION] = item description
   							[LINK = a link to the story
   						}
   					.
   					.
   					.
   				}
   	}
   	
   	By default it retrives the Reuters Oddly Enough RSS feed. The data is put into the array
   	structure so you can format the information as you see fit.
*/
set_time_limit(0);

$file = RSS_FEED_URL;
 
$rss_channel = array();
$currently_writing = "";
$main = "";
$item_counter = 0;

function startElement($parser, $name, $attrs) {
   	global $rss_channel, $currently_writing, $main;
   	switch($name) {
   		case "RSS":
   		case "RDF:RDF":
   		case "ITEMS":
   			$currently_writing = "";
   			break;
   		case "CHANNEL":
   			$main = "CHANNEL";
   			break;
   		case "IMAGE":
   			$main = "IMAGE";
   			$rss_channel["IMAGE"] = array();
   			break;
   		case "ITEM":
   			$main = "ITEMS";
   			break;
   		default:
   			$currently_writing = $name;
   			break;
   	}
}

function endElement($parser, $name) {
   	global $rss_channel, $currently_writing, $item_counter;
   	$currently_writing = "";
   	if ($name == "ITEM") {
   		$item_counter++;
   	}
}

function characterData($parser, $data) {
	global $rss_channel, $currently_writing, $main, $item_counter;
	if ($currently_writing != "") {
		switch($main) {
			case "CHANNEL":
				if (isset($rss_channel[$currently_writing])) {
					$rss_channel[$currently_writing] .= $data;
				} else {
					$rss_channel[$currently_writing] = $data;
				}
				break;
			case "IMAGE":
				if (isset($rss_channel[$main][$currently_writing])) {
					$rss_channel[$main][$currently_writing] .= $data;
				} else {
					$rss_channel[$main][$currently_writing] = $data;
				}
				break;
			case "ITEMS":
				if (isset($rss_channel[$main][$item_counter][$currently_writing])) {
					$rss_channel[$main][$item_counter][$currently_writing] .= $data;
				} else {
					//print ("rss_channel[$main][$item_counter][$currently_writing] = $data<br>");
					$rss_channel[$main][$item_counter][$currently_writing] = $data;
				}
				break;
		}
	}
}

if (SHOW_RSS_NEWS == 'true') {
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
	die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
	if (!xml_parse($xml_parser, $data, feof($fp))) {
		die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
	}
}
xml_parser_free($xml_parser);
}
?>