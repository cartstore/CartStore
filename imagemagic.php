<?php

// "Image Magic" by Tom Muldoon.
// Version 1.14
//
// Portions taken from 'On The Fly' Auto Thumbnailer using GD Library
//  - See readme file for credits
//
// If you find this contribution useful, I would appreciate a link to my site
// www.kitwear.com under the term 'football shirts' - Thanks
//

if (file_exists('includes/local/configure.php')) {
  	//use local dev params if available
	include('includes/local/configure.php');
} else {
	// include server parameters
	require('includes/configure.php');
}
require('includes/imagemagic/imagemagic.functions.php');
chdir (DIR_FS_CATALOG);

$server=DB_SERVER;      # host name of server running MySQL 
$user=DB_SERVER_USERNAME;       # existing login username for mysql 
$password=DB_SERVER_PASSWORD;   # login password for mysql username 
$dbname=DB_DATABASE;        # name of existing database to use  


//connect to database and get all config values
$config_values="";
$dbconn=@mysql_connect($server,$user,$password) or http_headers('','Error,Database Connection'); 
@mysql_select_db($dbname,$dbconn) or http_headers('','Error,Database Connection');
$sql="select configuration_key as cfgKey, configuration_value as cfgValue from configuration where configuration_group_id='333' or configuration_group_id='4'"; 
$result=@mysql_query($sql,$dbconn) or http_headers('','Error,Database Connection'); 
while ($row = @mysql_fetch_array($result)) { 
      if ($row['cfgKey'] != "LAST_HASH") $config_values.=$row['cfgKey'].'='.$row['cfgValue']; //to be fed to hashing function  
      define($row['cfgKey'], $row['cfgValue']);
}

//compute a hash of all the thumbnail config variables, so that if they change new cache files are created    
$append_hash=md5($config_values);

//have the config vars changed
if ($append_hash != LAST_HASH){
      $sql="update configuration set configuration_value ='".$append_hash."' where configuration_key='LAST_HASH'"; 
      $result=@mysql_query($sql,$dbconn) or fail(30);
      $cache_update=true;
}


// Get the type of thumbnail we are dealing with
if ( $_GET['w']== SMALL_IMAGE_WIDTH || $_GET['h'] == SMALL_IMAGE_HEIGHT) $thumbnail_size=1;
elseif ($_GET['w'] == HEADING_IMAGE_WIDTH || $_GET['h'] == HEADING_IMAGE_HEIGHT) $thumbnail_size=2;
elseif ($_GET['w'] == SUBCATEGORY_IMAGE_WIDTH || $_GET['h'] == SUBCATEGORY_IMAGE_HEIGHT) $thumbnail_size=3;


if ($_GET['page'] == "prod_info") {
      $thumbnail_size=4;
      $page_prefix = $page ."prod_info_";
}
if ($_GET['page'] == "popup") {
      $thumbnail_size=5;
      $page_prefix = $page ."prod_info_";
}

//Set the output quality and effects based on the type of thumbnail
$quality=100;
switch ($thumbnail_size) {
   case 1:
      if (FRAME_SMALL=="Yes") $frame=true;
      if (BEVEL_SMALL=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_SMALL =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_SMALL =="Yes") $text_watermark=true;
      $quality=(int)SMALL_JPEG_QUALITY;
   break;
   
   case 2:
      if (FRAME_HEADING=="Yes") $frame=true;
      if (BEVEL_HEADING=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_HEADING =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_HEADING =="Yes") $text_watermark=true;
      $quality=(int)HEADING_JPEG_QUALITY;
   break;

   case 3:
      if (FRAME_CATEGORY=="Yes") $frame=true;
      if (BEVEL_CATEGORY=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_CATEGORY =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_CATEGORY =="Yes") $text_watermark=true;
      $quality=(int)CATEGORY_JPEG_QUALITY;
   break;
   
   case 4:
      if (FRAME_PRODUCT=="Yes") $frame=true;
      if (BEVEL_PRODUCT=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_PRODUCT =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_PRODUCT =="Yes") $text_watermark=true;
      $quality=(int)PRODUCT_JPEG_QUALITY;
   break;

   case 5:
      if (FRAME_POPUP=="Yes") $frame=true;
      if (BEVEL_POPUP=="Yes") $bevel=true;
      if (USE_WATERMARK_IMAGE_POPUP =="Yes") $image_watermark=true;
      if (USE_WATERMARK_TEXT_POPUP =="Yes") $text_watermark=true;
      $quality=(int)POPUP_JPEG_QUALITY;
   break;      
}

// GET CONFIGURATION SETTINGS
$use_resampling = CFG_USE_RESAMPLING=='True'?true:false;
$use_truecolor = CFG_CREATE_TRUECOLOR=='True'?true:false;
$gif_as_jpeg = CFG_GIFS_AS_JPEGS=='True'?true:false;
$tn_server_cache = CFG_TN_SERVER_CACHE=='True'?true:false;
$tn_path = CFG_TN_CACHE_DIRECTORY;
$tn_browser_cache = CFG_TN_BROWSER_CACHE=='True'?true:false;
$use404 = CFG_USE_404=='True'?true:false; 
$show_original = true; // Not included in admin panel config.

//Decrypt the image filename if switched on
if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY !="") {
		$result = '';
		$key=CFG_ENCRYPTION_KEY;
		$string = base64_decode($_GET['img']);
		for($i=0; $i<strlen($string); $i++) {
		    $char = substr($string, $i, 1);
		    $keychar = substr($key, ($i % strlen($key))-1, 1);
		    $char = chr(ord($char)-ord($keychar));
		    $result.=$char;
		}
		$_GET['img']= $result;
}
   
// Get the size of the image:
$image = @getimagesize($_GET['img']);

// Give information if image not locateable
// Better than broken image due to div/0 error
if (!$image[0]) http_headers('','Error,File path incorrect,check configure.php');

// Do we allow thumbnails larger than the original
if (($_GET['w'] > $image[0] || $_GET['h'] > $image[1]) && CFG_ALLOW_LARGER  != 'True') {
      $over_ride_width=$image[0];
      $over_ride_height=$image[1];
}

// Work out the reduction ratio
$reduction_ratio = !isset($over_ride_width)?$_GET['w'] / $image[0]:1;

// Check the input variables and decide what to do:
if (empty($image) || empty($_GET['w']) || empty($_GET['h']))
{
	if (empty($image) || empty($show_original))
	{
		http_headers();
	}
	else
	{
		$_GET['w'] = $image[0];
		$_GET['h'] = $image[1];
	}
}

// Set the appropriate file extension:
if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg)) $extension="jpg";
elseif ($image[2] == 1 && function_exists('imagegif')) $extension="gif";
elseif ($image[2] == 3 || $image[2] == 1) $extension="png";

// If caching is switched on return the filename to check under and create the directory if it does not exist
if ($tn_server_cache) $filename = modify_tn_path($_GET['img'] .'.thumb_'.$page_prefix.$_GET['w'].'x'.$_GET['h'].'_'.$append_hash.'.'.$extension, false);

// If you are required to set the full path for file_exists(), uncomment this
// $filename = DIR_FS_CATALOG.$filename;

//check the cache for an existing copy, if there send it
if ($tn_server_cache && file_exists($filename) && filemtime($filename) > filemtime($_GET['img'])) {    
	$quality=100;
      // Output Cache Headers
	http_headers($filename);
	if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
	{
            $src = imagecreatefromjpeg($filename);
            header('Content-type: image/jpeg');
		imagejpeg($src, '', $quality);
	}
	elseif ($image[2] == 1 && function_exists('imagegif'))
	{
            $src = imagecreatefromgif($filename);
		header('Content-type: image/gif');
            imagegif($src);
	}
	elseif ($image[2] == 3 || $image[2] == 1)
	{
		$src = imagecreatefrompng($filename);
		//preserve alpha channel transparency in PNG images if PHP version > 4.3.2
            if (function_exists('imageSaveAlpha')) {
                  imageSaveAlpha($src, true);
                  ImageAlphaBlending($src, false); 
            }
            header('Content-type: image/png');
            imagepng($src);
	}
	else
	{
		// Not an image or imagecreatefrom...-function does not exits.
		// Let's output an error
		http_headers('','Error,Invalid image');
	}

} else {
	// No cached copy exists - Create a new, empty image based on settings:
	if (function_exists('imagecreatetruecolor') && $use_truecolor && ($extension =="png" || $extension=="jpg"))
	{
            $tmp_img = imagecreatetruecolor(!isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height);
	}
	else
	{
		$tmp_img = imagecreate(!isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height);
	}

	$th_bg_color =  imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
	imagefill($tmp_img, 0, 0, $th_bg_color);
	imagecolortransparent($tmp_img, $th_bg_color);

	// Create the image to be scaled:
	if ($extension=="jpg" && function_exists('imagecreatefromjpeg'))
	{
		$src = imagecreatefromjpeg($_GET['img']);
	}
	elseif ($extension=="gif" && function_exists('imagecreatefromgif'))
	{
		$src = imagecreatefromgif($_GET['img']);
	}
	elseif (($extension=="png" || $extension=="gif") && function_exists('imagecreatefrompng'))
	{
		$src = imagecreatefrompng($_GET['img']);
            //work-around fix to preserve alpha channel transparency in PNG images
            if (!$frame & !$bevel) { //isn't compatable with framing or bevelling
                  $tmp_img = imageCreateTrueColor(!isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height);
                  imageAntiAlias($tmp_img,true);
                  imagealphablending($tmp_img, false);
                  imagesavealpha($tmp_img,true);
                  $transparent = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
                  imagefilledrectangle($tmp_img, 0, 0, !isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height, $transparent);  
            }
	}
	elseif ($extension=="gif" && function_exists('imagecreatefrompng'))
	{
            $src = imagecreatefrompng($_GET['img']);
      }
      
      else
	{
		// Not an image or valid imagecreate function does not exits.
		// Let's output an error
		http_headers('', 'Error,Image Not Valid');
	}

      // If image is smaller than output and Center is on then reset center x and y
      if (CFG_CENTER_THUMB == "True" &CFG_ALLOW_LARGER == "True" && ($_GET['w'] > $image[0] || $_GET['h'] > $image[1])) {
            $cx=($_GET['w'] - $image[0]) / 2;
            $cy=($_GET['h'] - $image[1]) / 2;
            $over_ride_width=$image[0];
            $over_ride_height=$image[1];
      }
      else {
            $cx=0; $cy=0;
      }      
      
	// Scale the image based on settings:
	if (function_exists('imagecopyresampled') && $use_resampling)
	{
        imagecopyresampled($tmp_img, $src, $cx, $cy, 0, 0, !isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height, $image[0], $image[1]);
	}
	else
	{
		imagecopyresized($tmp_img, $src, $cx, $cy, 0, 0, !isset($over_ride_width)?$_GET['w']:$over_ride_width,!isset($over_ride_height)?$_GET['h']:$over_ride_height, $image[0], $image[1]);
	}

	//add selected custom filters to the image
	if (BRIGHTNESS_ADJUST != "0") adjust_brightness(&$tmp_img,BRIGHTNESS_ADJUST);
	if (CONTRAST_ADJUST != "0") adjust_contrast(&$tmp_img, CONTRAST_ADJUST);
	if ($image_watermark) watermark_image(&$tmp_img, DIR_FS_CATALOG.'includes/imagemagic/watermarks/'.WATERMARK_IMAGE ,WATERMARK_IMAGE_POSITION, WATERMARK_IMAGE_OPACITY, WATERMARK_IMAGE_MARGIN);
	if ($frame) frame(&$tmp_img, FRAME_WIDTH, FRAME_EDGE_WIDTH, FRAME_COLOR, FRAME_INSIDE_COLOR1, FRAME_INSIDE_COLOR2);
	if ($bevel) bevel (&$tmp_img, BEVEL_HEIGHT, BEVEL_HIGHLIGHT, BEVEL_SHADOW);
	if ($text_watermark) watermark_text(&$tmp_img, WATERMARK_TEXT, WATERMARK_TEXT_SIZE, WATERMARK_TEXT_POSITION, WATERMARK_TEXT_COLOR, 'includes/imagemagic/fonts/'.WATERMARK_TEXT_FONT, WATERMARK_TEXT_OPACITY, WATERMARK_TEXT_MARGIN, WATERMARK_TEXT_ANGLE);
      
    // Output the image:
	if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
	{
		if ($tn_server_cache)
		{		
           $thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$page_prefix.$_GET['w'].'x'.$_GET['h'].'_'.$append_hash.'.jpg', true);
           imagejpeg($tmp_img,$thumbnail, $quality);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
            header('Content-type: image/jpeg');
            imagejpeg($tmp_img,'',$quality);
	}
	elseif ($image[2] == 1 && function_exists('imagegif'))
	{
		if ($tn_server_cache)
		{
			$thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$page_prefix.$_GET['w'].'x'.$_GET['h'].'_'.$append_hash.'.gif', true);
                  imagegif($tmp_img,$thumbnail);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
            header('Content-type: image/gif');
            imagegif($tmp_img);
	}
	elseif ($image[2] == 3 || $image[2] == 1)
	{
		if ($tn_server_cache)
		{
			$thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$page_prefix.$_GET['w'].'x'.$_GET['h'].'_'.$append_hash.'.png', true);
            imagepng($tmp_img,$thumbnail);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
            header('Content-type: image/png');
            imagepng($tmp_img);
	}
	else
	{
		// Not an image or image...-function not supported
		// Let's output an error:
		http_headers();
	}

	// Clear the image from memory:
	imagedestroy($src);
	imagedestroy($tmp_img);
}

function modify_tn_path($file, $check_cache)
{
		//return $file;
		global $tn_path, $append_hash;
		
		if ($tn_path=='') return $file;
		else{
			// normalize all combinations of trailing or leading slash       
			if (substr($tn_path,0,1)=='\\' || substr($tn_path,0,1)=='/') $tn_path = substr($tn_path,1);
			if (substr($tn_path,strlen($tn_path)-1,1) == '\\' || substr($tn_path,strlen($tn_path)-1,1) == '/' ) $tn_path = substr($tn_path,0,strlen($tn_path)-1);
			$tn_path.='/';
            
            //create the directory tree if not already there
            $create_path=dirname($tn_path. $file);
            if (!is_dir($create_path)) { 
            	if (!make_dirs($create_path)) http_headers('',"Cache Error,Cannot Create Dir.,Check The Readme");
            }
            
            //clean up the cache if settings have changed
            if (CFG_CACHE_AUTO_CLEAN=="True" && $check_cache) {
                  $cwd=getcwd();
                  chdir($create_path);
                  foreach (glob("*.*") as $filename) {
                        if (!is_dir($filename) && !strstr($filename,$append_hash)) {
                              unlink($filename);
                        }
                  }  
                  chdir($cwd);
            }        
            return $tn_path. $file;
      }
}

function make_dirs($path) //creates directory tree recursively
{   
	return is_dir($path) or ( make_dirs(dirname($path), 0777) and mkdir($path, 0777) );
}

function http_headers($file='', $error='')
{
      //
	// This function supports the use of browser-caching (optional)
	//
	// A 304 (Not Modified) will be sent when the thumbnail has not changed
	//       since the time it was last cached by the client
	// A 200 (OK) will be sent when the thumbnail was not cached by the client
	//       or when the thumbnail was cached but changed afterwards
	// A 404 (Not Found) will be sent when the thumbnail is not found (optional)
	global $use404, $tn_browser_cache;
      $quality=100;
	
	if (isset($_SERVER["SERVER_PROTOCOL"]) && $_SERVER["SERVER_PROTOCOL"] == "HTTP/1.1") 
		$httpProtocol = "HTTP/1.1";
	else
		$httpProtocol = "HTTP/1.0";
	
	if ($file !='' && file_exists($file))
	{
		
		if (isset ($_SERVER["HTTP_CACHE_CONTROL"])) {
			$tn_browser_cache = strtolower($_SERVER["HTTP_CACHE_CONTROL"]) == "no-cache" ? false : $tn_browser_cache ;
		}
		
		//Build our entity tag, which is "inode-lastmodtime-filesize"
		$lastModified = filemtime($file);
		$lastModifiedGMT = $lastModified - date('Z');
		$lastModifiedHttpFormat = gmstrftime("%a, %d %b %Y %T %Z", $lastModified);
		// Don't use inode in eTag when you have multiple webservers, instead I use a dummy value (1fa44b7)
		$eTag = '"1fa44b7-' . dechex(filesize($file)) . "-" . dechex($lastModifiedGMT) . '"';
	
		if ($tn_browser_cache){
		
			$lastModifiedFromHttp = "xxx";
			if (isset ($_SERVER["HTTP_IF_MODIFIED_SINCE"])) {
				$lastModifiedFromHttp = ($_SERVER["HTTP_IF_MODIFIED_SINCE"] === "") ? "xxx" : $_SERVER["HTTP_IF_MODIFIED_SINCE"] ;
			}
			
			// Read sent eTag by browser
			$foundETag = "";
			if (isset ($_SERVER["HTTP_IF_NONE_MATCH"])) {
				$foundETag = stripslashes($_SERVER["HTTP_IF_NONE_MATCH"]);
			}
			
			// Last Modification Time
			if ($lastModifiedFromHttp == $lastModifiedHttpFormat) {
				$sameLastModified = true;
			}
			elseif (strpos($lastModifiedFromHttp,$lastModifiedHttpFormat) !== false){
				$sameLastModified = true;
			}
			else {
				$sameLastModified = false;
			}
			
			if (($eTag == $foundETag) && $sameLastModified){
				// same eTag and Last Modification Time (e.g. with Firefox)
				$is304 = true;
			}
			else
				// no eTag supplied, but Last Modification Time is unchanged (e.g. with IE 6.0)
				$is304 = (($foundETag == "") && $sameLastModified);

			if ($is304)
			{
				//
				// They already have an up to date copy so tell them
				if ($lastModifiedGMT > 946080000) {        // 946080000 = Dec 24, 1999 4PM
					// only send if valid eTag
					header("ETag: " . $eTag);
				}
				header("Status: 304 Not Modified");
				header($httpProtocol . " 304 Not Modified");
				header("Connection: close");
				exit();
			}
		}

		//
		// We have to send them the whole page
		header('Pragma: ');
		header('Expires: ');
		if ($tn_browser_cache){
			if ($lastModifiedGMT > 946080000) {        // 946080000 = Dec 24, 1999 4PM
				header('ETag: ' . $eTag);
			}
			header('Last-Modified: ' . $lastModifiedHttpFormat);
			header('Cache-Control: private');
		}
		else {
			header('Cache-Control: no-cache');
		}

	}
      else
	{
		if ($use404 && $error=='')
		{
			//
			// send them a 404 http response header
			header("TEST404: TEST404");
			header("Status: 404 Not Found");
			header($httpProtocol . " 404 Not Found");
			exit();
		}
		else
		{
			//
			// show a custom error-image (non-cacheable by the browser)			
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");								// Date in the past
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");	// Always modified
			header("Cache-Control: no-store, no-cache, must-revalidate");		// HTTP/1.1
			header("Cache-Control: post-check=0, pre-check=0", false);			// HTTP/1.1
			header("Pragma: no-cache");																			// HTTP/1.0
			header('Content-type: image/jpeg');
			if ($error=="") $error="Error";
                  $src = imagecreate($_GET['w'], $_GET['h']);											// Create a blank image.
			$bgc = imagecolorallocate($src, 255, 255, 255);
			$tc  = imagecolorallocate($src, 0, 0, 0);
			$string = explode(',',$error);
                  imagefilledrectangle($src, 0, 0, $_GET['w'], $_GET['h'], $bgc);
                  foreach ($string as $error) {
			      imagestring($src, 1, 5, $line*ImageFontHeight(1), $error, $tc);
                        $line++;
                  }
                  imagejpeg($src, '', 100);
			imagedestroy($src);
			exit();
		}
	}
}




function watermark_text(&$gdimg, $text, $size, $alignment, $hex_color='000000', $ttffont='', $opacity=100, $margin=5, $angle=0) {
	// text watermark requested
	
	if (!function_exists('ImageTTFbBox')) http_headers('',"FreeType not supported,Switch off text watermarks");
	
	if (!$text || $ttffont=="" || $size==0 || !@is_readable($ttffont) || !is_file($ttffont))  {
		return false;
	}
      
      ImageAlphaBlending($gdimg, true);

	$text = str_replace("\r\n", "\n", $text);
	$text = str_replace("\r",   "\n", $text);
	$textlines = explode("\n", $text);
           
	$opacity = 100 - intval(max(min($opacity, 100), 0));
	$TTFbox = ImageTTFbBox($size, $angle, $ttffont, $text);

	$min_x = min($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
	$max_x = max($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
	//$text_width = round($max_x - $min_x + ($size * 0.5));
	$text_width = round($max_x - $min_x);

	$min_y = min($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
	$max_y = max($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
	//$text_height = round($max_y - $min_y + ($size * 0.5));
	$text_height = round($max_y - $min_y);

	$TTFboxChar = ImageTTFbBox($size, $angle, $ttffont, 'pH');
	$char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
	$char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
	$char_height = round($char_max_y - $char_min_y);
      $char_margin=0;
      if ($angle !=0) {
            $TTFboxChar = ImageTTFbBox($size, 0, $ttffont, 'pH');
		$char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
		$char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
		$char_margin = round($char_max_y - $char_min_y);
      }
	switch ($alignment) {
		case 'Top':
			$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
			$text_origin_y = $char_height + $margin;
			break;

		case 'Bottom':
			$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
			$text_origin_y = ImageSY($gdimg) - $TTFbox[1] - $margin;
			break;

		case 'Left':
			$text_origin_x = $margin;
			$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
			break;

		case 'Right':
			$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
			$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
			break;

		case 'Center':
			$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
			$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
			break;

		case 'Top Left':
			$text_origin_x = $margin;
			$text_origin_y = $char_height + $margin;
			break;

		case 'Top Right':
			$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
			$text_origin_y = $char_height + $margin;
			break;

		case 'Bottom Left':
			$text_origin_x = $margin;
			$text_origin_y = ImageSY($gdimg) - $TTFbox[1] - $margin;
			break;

		case 'Bottom Right':
		default:
			$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
			$text_origin_y = ImageSY($gdimg) - $TTFbox[1] - $margin;
			break;
	}
	
      $letter_color_text = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hex_color, false, $opacity * 1.27);
	
      if ($alignment == 'Tiled') {

		$text_origin_y = 0-$char_height;
		while (($text_origin_y - $text_height) < ImageSY($gdimg)) {
			$text_origin_x = $margin +$char_margin;
			while ($text_origin_x < ImageSX($gdimg)) {
				ImageTTFtext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);
				$text_origin_x += ($text_width + $margin);
			}
			$text_origin_y += ($text_height + $margin);
		}

	} else {
		ImageTTFtext($gdimg, $size, $angle, $text_origin_x+$char_margin, $text_origin_y, $letter_color_text, $ttffont, $text);
	}

	return true;
}


function watermark_image(&$gdimg_dest, $img_watermark_filename, $alignment='*', $opacity=95, $margin=5) {
	global $image, $reduction_ratio;
      if($image[2] == 1 || $img_watermark_filename=="" || !is_file($img_watermark_filename)) return false; //no gifs allowed    
      
      //create the watermark file
      $image_params = @getimagesize($img_watermark_filename); 
      if ($image_params[2] == 2) $img_watermark = imageCreateFromJPEG($img_watermark_filename);      
      elseif ($image_params[2] == 1) $img_watermark = imageCreateFromGIF($img_watermark_filename); 
      elseif ($image_params[2] == 3) $img_watermark = imageCreateFromPNG($img_watermark_filename); 
      else return false;

      // calculate scaling width and height
      if (CFG_RESIZE_WATERMARK=="True" && $reduction_ratio != 1) {       
            
            $width = intval($image_params[0] * $reduction_ratio);            
            $height = intval($image_params[1] * $reduction_ratio);

            if ($image_params[2] == 1){
                  $tmp_img = imagecreate($width,$height);
                  $th_bg_color =  imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
	            imagefill($tmp_img, 0, 0, $th_bg_color);
	            imagecolortransparent($tmp_img, $th_bg_color);
            } elseif ($image_params[2] == 2) {
                  $tmp_img = imagecreatetruecolor($width, $height);
                  $th_bg_color =  imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
	            imagefill($tmp_img, 0, 0, $th_bg_color);
	            imagecolortransparent($tmp_img, $th_bg_color);
            } elseif ($image_params[2] == 3) {
                  $tmp_img = imageCreateTrueColor($width, $height);
                  imageAntiAlias($tmp_img,true);
                  imagealphablending($tmp_img, false);
                  imagesavealpha($tmp_img,true);
                  $transparent = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
                  imagefilledrectangle($tmp_img, 0, 0, $width,$height, $transparent);  
            }          
            
            //scale the watermark to the appropriate size
            if (function_exists('imagecopyresampled') && $use_resampling)
	      {
                  imagecopyresampled($tmp_img, $img_watermark, 0, 0, 0, 0, $width, $height, $image_params[0], $image_params[1]);
	      }
	      else
	      {
		      imagecopyresized($tmp_img, $img_watermark, 0, 0, 0, 0, $width, $height, $image_params[0], $image_params[1]);
	      }
            $img_watermark=$tmp_img;
            $image_params[0]=$width;
            $image_params[1]=$height;
      }
      
      if (is_resource($gdimg_dest) && is_resource($img_watermark)) {
		$watermark_source_x        = 0;
		$watermark_source_y        = 0;
		$img_source_width          = ImageSX($gdimg_dest);
		$img_source_height         = ImageSY($gdimg_dest);
		$watermark_source_width    = ImageSX($img_watermark);
		$watermark_source_height   = ImageSY($img_watermark);
		$watermark_opacity_percent = max(0, min(100, $opacity));
		if ($margin < 1) {
			$watermark_margin_percent = 1 - $margin;
		} else {
			$watermark_margin_percent = (100 - max(0, min(100, $margin))) / 100;
		}
		$watermark_margin_x = round((1 - $watermark_margin_percent) * $img_source_width);
		$watermark_margin_y = round((1 - $watermark_margin_percent) * $img_source_height);
		switch ($alignment) {
			case 'Tiled':
				if ($gdimg_tiledwatermark = imagemagic_functions::ImageCreateFunction($img_source_width, $img_source_height)) {

					ImageAlphaBlending($gdimg_tiledwatermark, false);
					if (imagemagic_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
						ImageSaveAlpha($gdimg_tiledwatermark, true);
					}
					$text_color_transparent = imagemagic_functions::ImagecolorAllocateAlphaSafe($gdimg_tiledwatermark, 255, 0, 255, 127);
					ImageFill($gdimg_tiledwatermark, 0, 0, $text_color_transparent);

					for ($x = $watermark_margin_x; $x < ($img_source_width + $watermark_source_width); $x += round($watermark_source_width + ((1 - $watermark_margin_percent) * $img_source_width))) {
						for ($y = $watermark_margin_y; $y < ($img_source_height + $watermark_source_height); $y += round($watermark_source_height + ((1 - $watermark_margin_percent) * $img_source_height))) {
							ImageCopy(
								$gdimg_tiledwatermark,
								$img_watermark,
								$x,
								$y,
								0,
								0,
								min($watermark_source_width,  $img_source_width  - $x - ((1 - $watermark_margin_percent) * $img_source_width)),
								min($watermark_source_height, $img_source_height - $y - ((1 - $watermark_margin_percent) * $img_source_height))
							);
						}
					}

					$watermark_source_width  = ImageSX($gdimg_tiledwatermark);
					$watermark_source_height = ImageSY($gdimg_tiledwatermark);
					$watermark_destination_x = 0;
					$watermark_destination_y = 0;

					ImageDestroy($img_watermark);
					$img_watermark = $gdimg_tiledwatermark;
				}
				break;

			case 'Top':
				$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
				$watermark_destination_y = $watermark_margin_y;
				break;

			case 'Bottom':
				$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
				$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
				break;

			case 'Left':
				$watermark_destination_x = $watermark_margin_x;
				$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
				break;

			case 'Right':
				$watermark_destination_x = round(($img_source_width - $watermark_source_width)  * $watermark_margin_percent);
				$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
				break;

			case 'Center':
				$watermark_destination_x = round(($img_source_width  / 2) - ($watermark_source_width  / 2));
				$watermark_destination_y = round(($img_source_height / 2) - ($watermark_source_height / 2));
				break;

			case 'Top Left':
				$watermark_destination_x = $watermark_margin_x;
				$watermark_destination_y = $watermark_margin_y;
				break;

			case 'Top Right':
				$watermark_destination_x = round(($img_source_width - $watermark_source_width)  * $watermark_margin_percent);
				$watermark_destination_y = $watermark_margin_y;
				break;

			case 'Bottom Left':
				$watermark_destination_x = $watermark_margin_x;
				$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
				break;

			case 'Bottom Right':
			default:
				$watermark_destination_x = round(($img_source_width  - $watermark_source_width)  * $watermark_margin_percent);
				$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
				break;
		}
		ImageAlphaBlending($gdimg_dest, false);
		//if (imagemagic_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
			ImageSaveAlpha($gdimg_dest, true);
			ImageSaveAlpha($img_watermark, true);
		//}
		imagemagic_functions::ImageCopyRespectAlpha($gdimg_dest, $img_watermark, $watermark_destination_x, $watermark_destination_y, 0, 0, $watermark_source_width, $watermark_source_height, $watermark_opacity_percent);

		return true;
	}
	return false;
}

function adjust_brightness(&$gdimg, $amount=0) {
	global $image;
      if($image[2] == 1 || $amount==0) return false;
	$amount = max(-255, min(255, $amount));

	if (imagemagic_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && imagemagic_functions::gd_is_bundled()) {
		if (ImageFilter($gdimg, IMG_FILTER_BRIGHTNESS, $amount)) {
			return true;
		}
	}

	$scaling = (255 - abs($amount)) / 255;
	$baseamount = (($amount > 0) ? $amount : 0);
	for ($x = 0; $x < ImageSX($gdimg); $x++) {
		for ($y = 0; $y < ImageSY($gdimg); $y++) {
			$OriginalPixel = imagemagic_functions::GetPixelcolor($gdimg, $x, $y);
			foreach ($OriginalPixel as $key => $value) {
				$NewPixel[$key] = round($baseamount + ($OriginalPixel[$key] * $scaling));
			}
			$newcolor = ImagecolorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
			ImageSetPixel($gdimg, $x, $y, $newcolor);
		}
	}
	return true;
}

function adjust_contrast(&$gdimg, $amount=0) {
	global $image;
      if($image[2] == 1 || $amount==0) return false;     
      $amount = max(-255, min(255, $amount));

	if (imagemagic_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && imagemagic_functions::gd_is_bundled()) {
		if (ImageFilter($gdimg, IMG_FILTER_CONTRAST, $amount)) {
			return true;
		}
		$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_CONTRAST, '.$amount.')', __FILE__, __LINE__);
		// fall through and try it the hard way
	}

	if ($amount > 0) {
		$scaling = 1 + ($amount / 255);
	} else {
		$scaling = (255 - abs($amount)) / 255;
	}
	for ($x = 0; $x < ImageSX($gdimg); $x++) {
		for ($y = 0; $y < ImageSY($gdimg); $y++) {
			$OriginalPixel = imagemagic_functions::GetPixelcolor($gdimg, $x, $y);
			foreach ($OriginalPixel as $key => $value) {
				$NewPixel[$key] = min(255, max(0, round($OriginalPixel[$key] * $scaling)));
			}
			$newcolor = ImagecolorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
			ImageSetPixel($gdimg, $x, $y, $newcolor);
		}
	}
}

function bevel(&$gdimg, $width, $hexcolor1, $hexcolor2) {
	$width     = ($width     ? $width     : 5);
	$hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'CCCCCC');
	$hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');

	ImageAlphaBlending($gdimg, true);
	for ($i = 0; $i < $width; $i++) {
		$alpha = round(($i / $width) * 127);
		$color1[$i] = imagemagic_functions::ImageHexColorAllocate($gdimg, $hexcolor1, false, $alpha);
		$color2[$i] = imagemagic_functions::ImageHexColorAllocate($gdimg, $hexcolor2, false, $alpha);

		ImageLine($gdimg,                   $i,                   $i,                   $i, ImageSY($gdimg) - $i, $color1[$i]); // left
		ImageLine($gdimg,                   $i,                   $i, ImageSX($gdimg) - $i,                   $i, $color1[$i]); // top
		ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i, ImageSX($gdimg) - $i,                   $i, $color2[$i]); // right
		ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i,                   $i, ImageSY($gdimg) - $i, $color2[$i]); // bottom
	}
	return true;
}  

function frame(&$gdimg, $frame_width, $edge_width, $hexcolor_frame, $hexcolor1, $hexcolor2) {
	$frame_width    = ($frame_width    ? $frame_width    : 5);
	$edge_width     = ($edge_width     ? $edge_width     : 1);
	$hexcolor_frame = ($hexcolor_frame ? $hexcolor_frame : 'CCCCCC');
	$hexcolor1      = ($hexcolor1      ? $hexcolor1      : 'FFFFFF');
	$hexcolor2      = ($hexcolor2      ? $hexcolor2      : '000000');

	$color_frame = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor_frame);
	$color1      = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor1);
	$color2      = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor2);
	for ($i = 0; $i < $edge_width; $i++) {
		// outer bevel
		ImageLine($gdimg,                   $i,                   $i,                   $i, ImageSY($gdimg) - $i, $color1); // left
		ImageLine($gdimg,                   $i,                   $i, ImageSX($gdimg) - $i,                   $i, $color1); // top
		ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i, ImageSX($gdimg) - $i,                   $i, $color2); // right
		ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i,                   $i, ImageSY($gdimg) - $i, $color2); // bottom
	}
	for ($i = 0; $i < $frame_width; $i++) {
		// actual frame
		ImageRectangle($gdimg, $edge_width + $i, $edge_width + $i, ImageSX($gdimg) - $edge_width - $i, ImageSY($gdimg) - $edge_width - $i, $color_frame);
	}
	for ($i = 0; $i < $edge_width; $i++) {
		// inner bevel
		ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color2); // left
		ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color2); // top
		ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color1); // right
		ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color1); // bottom
	}
	return true;
}
  
?>