<?php
/**
 * Version 2.5
 * http://www.php-beginners.com/
 *
 * Legends:
 *		- long_text = this means that the file has long text without any space, a potential hacker code 
 *		- eval = eval is used mostly for hackers to hide their codes. example: eval(gzinflate(... or eval('DDsdf231Fee232ldk .....');
 * 		- c99madshell = a code found at the top of script like <?php $md5 = "....."; $wp_salt = "...";
 * 		- thumb - timthumb vulnerability
 * 
 * Output:
 *			1. ./decoder.php - long_text - 8z9icsn5bKRi74MLTW3saAuwa7zfMvzEb+RfU95CnMAzYQ9pmv
 *			#. ./[FILE_NAME] - [LEGEND]  - [CODE PORTION OF MATCH STRING]
 *
 *
 * Add
 * - $text([$param])
 * - \x73 or \x{73}  <= hex
 *
 * Bugs
 * - Match (----------------------------------------------) as long_text
 *
 * 
 */

set_time_limit(0);
ob_start();

header("Content-type:text/plain");

echo "This script scans for potentially malicious code, not all results are. A professional must review the results.";

$root = "./";
	
$aPatterns = array(
	"(?P<hex>\\\\x(?:{){0,1}\d{1,3}(?:}){0,1})",
	"(?P<varfunc>\$\w+\(.*\))",
	"(?P<god_mode_on><\?php\s*\/\*god_mode_on\*\/eval\(base64_decode\([\"'][^\"']{255,}[\"']\)\);\s*\/\*god_mode_off\*\/\s*\?>)",
	"(?P<htaccess>RewriteCond %{HTTP_REFERER}\s*\^\.\*\s*\([^\)]*[google|yahoo|bing|ask|wikipedia|youtube][^\)]*)",
	"(?P<JSCRIPT>^<script>.*<\/script>)",
	"(?P<GRMalware>^<\?php\s*if\(!function_exists\([^{]+\s*{\s*function[^}]+\s*}\s*[^\"']+\s*[\"'][^\"']+[\"'];\s*eval\s*\(.*\)\s*;\s*}\s*)",
	"(?P<c99>(<\?php)*\\\$md5\s*=\s*[\"|']\w+[\"|'];\s*\\\$wp_salt\s*=\s*[\w\(\),\"\'\;\$]+\s*\\\$wp_add_filter\s*=\s*create_function\(.*\);\s*\\\$wp_add_filter\(.*\);\s*(\?>)*)",
	"(?P<evl>eval\s*\([^\)]+)",
	"(?P<ltx>[a-zA-Z0-9\+\-\/]{50,})",
	"(?P<ifm><iframe[^>]*)",
	"(?P<mbd><embed[^>]*)",
	"(?P<tim>[T|t]imthumb)",
	"(?P<cfn>create_function[^\)]*)",
	"(?P<c64>base64_decode[^\)]*)",
);
$find ="(".implode('|', $aPatterns).")";

$except = array("rar", "zip", "mp3", "mp4", "mp3", "mov", "flv", "wmv", "swf", "png", "gif", "jpg", "bmp", "avi");
$only = array("php", "shtml", "html", "htm", "js", "css", "htaccess", "txt");
$infectedFiles = null;
$showOnlyInfectedFiles = true;


$infectedFiles = startScan($root);

echo "\n################################################################################\n";
echo "\n\nFound Files\nSummary. You can take a better look on files that matches a potential hack script.\n";
echo "\n";
if(is_array($infectedFiles))
$j=1;
foreach($infectedFiles AS $iFile){
	echo "{$j}. {$iFile}\n";
$j++; }
echo "\n";


/* functions */
function getAllFiles($dir){
global $except, $only;
	$filenames = null;
	if ($handle = opendir($dir)){
		while (false !== ($file = readdir($handle))) 
			if ($file != "." && $file != ".." && !is_dir($dir.$file) && ($dir != "." && $file != basename(__FILE__))){
				$path_parts = pathinfo($file);
				if(isset($path_parts['extension']) && array_search(strtolower($path_parts['extension']), $except) === false)
					if(array_search(strtolower($path_parts['basename']), $only) !== false || array_search(strtolower($path_parts['extension']), $only) !== false || sizeof($only) < 1)
						$filenames[] = $file;
			}
		closedir($handle);
	}

	return $filenames;
}

function getAllDirectories($dir){
	$directories = null;
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle)))
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$directories[] = $dir.$file;
		closedir($handle);
	}

	return $directories;
}


function startScan($root){
global $find, $infectedFiles, $showOnlyInfectedFiles;

	$root = str_replace("//", "/", $root);
	echo "\n".$root;
	$directories = getAllDirectories($root);

	ob_implicit_flush();
	ob_flush();
	sleep(1);
				 
	if(is_array($directories)){

		// get all files
		if(($tmp = getAllFiles($root)) !== null){
			$files = $tmp;
			foreach($files AS $file){
				$numMatches = checkMalware($root.$file, $find);

				if(!empty($numMatches[0])){
					echo "\n * ".$infectedFiles[] = $root.$file. " {$numMatches[1]}";
				}elseif(!$showOnlyInfectedFiles){
					$infectedFiles[] = $root.$file;
					echo "\n - ".$root.$file;
				}
				
				
				 ob_implicit_flush();
				 ob_flush();
				 sleep(1);
			}
		}

		foreach($directories AS $dir){
			 echo "\n".$dir;
			 ob_implicit_flush();
			 ob_flush();
			 sleep(1);
			 
			// get all files
			if(($tmp = getAllFiles($dir)) !== null){
				$files = $tmp;
				foreach($files AS $file){
					if($dir[strlen($dir)-1] === "/") $dir = substr($dir, 0, -1); 
					$numMatches = checkMalware($dir."/".$file, $find);
					if(!empty($numMatches[0])){
						echo "\n * ".$infectedFiles[] = $dir."/".$file. " {$numMatches[1]}";
					}elseif(!$showOnlyInfectedFiles) {
						$infectedFiles[] = $dir."/".$file;
						echo "\n - ".$infectedFiles[] = $dir."/".$file;
					}
				}
			}
			
			// gel all directories
			if($root[strlen($root)-1] === "/") $tmp_root = substr($root, 0, -1); 
			if(($tmp = getAllDirectories($dir."/")) !== null && $dir !== $tmp_root){
				foreach($tmp AS $d){
					$a = startScan($d."/");
					if(is_array($a))
						array_merge($infectedFiles, $a);
				}
				
			}
		}
	}else{
		// get all files
		if(($tmp = getAllFiles($root)) !== null){
			$files = $tmp;
			foreach($files AS $file){
				$numMatches = checkMalware($root.$file, $find);
				if(!empty($numMatches[0])){
					echo "\n * ".$infectedFiles[] = $root.$file. " {$numMatches[1]}";
				}elseif(!$showOnlyInfectedFiles){
					$infectedFiles[] = $root.$file;
					echo "\n - ".$root.$file;
				}
			}
		}
	}
	
 return $infectedFiles;
}

function checkMalware($filename, $find){
	$numMatches = null;
	$handle = fopen($filename, "r");
	$smatch = "\n\t -";
	
	if(filesize($filename) > 0){
		$contents = fread($handle, filesize($filename));

		$numMatches = preg_match_all('/'.$find.'/i', $contents, $matches, PREG_PATTERN_ORDER);
		
		$matches[0] = array_unique($matches[0]);
		
// print_r($matches);
		foreach($matches[0] AS $key => $_match){
			$_match = (isset($_match)) ? preg_replace("/[\s]+/i", " ", $_match) : "";
			
			$match = (isset($_match)) ? str_replace("\n", " ", substr($_match, 0, 10)) : "";
			$fragments = (isset($_match)) ? str_replace("\n", " ", substr($_match, 0, 50)) : "";
			
			
			if($key) $smatch .= "\n\t -";
	// c99, evl, ltx, ifm, mbd, tim, cfn, c64

			switch(strtolower(trim($match))){
				case "\$md5": 
				case "<?php \$md5": $smatch .= "c99madshell"; break;
				case "<?php if(!": $smatch .= "GRMalware"; break;
				case "timthumb";
				case "thumb": 
					$pattern_2 = 'define\s*\(\'VERSION\',\s*\'[23456789]\.[0-9]';
					// We have a timthumb script.  Now check to see if it is version 2.0 or greater.
					if ( ! preg_match( "~$pattern_2~", $contents ) ) 
						$smatch .= "timthumb vulnerability ";
					else
						$smatch .= "safe timthumb ";
				break;
				case "<?php /*go": $smatch .= "god_mode_on"; break;

				default:{
					if(isset($matches[0])){
						if(substr($_match, 0, 4) == "eval") $smatch .= "eval";
						elseif(substr($_match, 0, 7) == "<iframe") $smatch .= "iframe";
						elseif(substr($_match, 0, 6) == "<embed") $smatch .= "embed";
						elseif(substr($_match, 0, 11) == "RewriteCond") $smatch .= "htaccess";
						elseif(strlen($_match) > 49) $smatch .= "long_text";
						else $smatch .= "unknown";
					}
					else $smatch .= "unknown";
				}
			}
			
			$smatch .= " - " . $fragments;
			
			$numMatches = array($numMatches, $smatch."\n");
		}
		
	}
	fclose($handle);
	return $numMatches;
}


ob_end_flush();