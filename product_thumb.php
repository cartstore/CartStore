<?php

// This is "On the Fly Thumbnailer with Caching Option" by Pallieter Koopmans.
// Based on Marcello Colaruotolo (1.5.1) which builds upon Nathan Welch (1.5)
// and Roberto Ghizzi. With improvements by @Quest WebDesign, http://atQuest.nl/
// and Martijn Loeffen (browser-caching and thumbnail-cache-dir)
//
// Scales product images dynamically, resulting in smaller file sizes, and keeps
// proper image ratio.
//
// Used in conjunction with modified tep_image in html_output.php (see: readme.txt).
//
// CONFIGURATION SETTINGS
//
// Server cache directory. Set the value below to true to generate resampled thumbnails
// resulting in smoother-looking images. Not supported in GD ver. < 2.01
$use_resampling = true;
//
// Create True Color Thumbnails? Better quality overall but set to false if you
// have GD version < 2.01 or if creating transparent thumbnails.
$use_truecolor = true;
//
// Output GIFs as JPEGS? Set this option to true if you have GD version > 1.6
// and want to output GIF thumbnails as JPGs instead of GIFs or PNGs. Note that your
// GIF transparencies will not be retained in the thumbnail if you output them
// as JPGs. If you have GD Library < 1.6 with GIF create support, GIFs will
// be output as GIFs. Set the "matte" color below if setting this option to true.
$gif_as_jpeg = false;
//
// Cache Images on the server? Set to true if you want to save requested thumbnails
// on disk. This will add to disk space but will save your processor from having to
// create the thumbnail for every visitor.
$tn_server_cache = true;
//
// Thumbnail Path. If server-caching is enabled, specify a sub-directory
// where the thumbnails should be kept. Use '' for the default images-directory,
// which is /catalog/images/
// Note: Make sure this path actually exists as a subdirectory and is writeable!
$tn_path = 'thumbnails/'; // The default is 'thumbnails/', should be chmod 777
//
// Cache Images in Browser-Cache? Set to true if you want browsers to be able to
// cache viewed thumbnails in their own cache. This will save bandwidth for every
// visitor that views the same thumbnail again.
$tn_browser_cache = true; // The default is true
//
// Send a 404 http response when an image is not found
// If set to false, will show a small error-image (as in version < 2.0.0)
$use404 = true; // The default is true
//
// Define RGB Color Value for background matte color if outputting GIFs as JPEGs
// Example: white is r=255, b=255, g=255; black is r=0, b=0, g=0; red is r=255, b=0, g=0;
$r = 255; // Red color value (0-255)
$g = 255; // Green color value (0-255)
$b = 255; // Blue color value (0-255)
//
// Allow the creation of thumbnail images that are larger than the original images:
$allow_larger = false; // The default is false.
// If allow_larger is set to false, you can opt to output the original image:
// Better leave it true if you want pixel_trans_* to work as expected
$show_original = true; // The default is true.
//
// END CONFIGURATION SETTINGS

// Note: In order to manually debug this script, you might want to comment
// some header() lines -- otherwise no output is shown.

// reverse strrchr(), taken from http://nl2.php.net/manual/en/function.strrchr.php
function reverse_strrchr($haystack, $needle)
{
	return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) +1 ) : false;
} 

function modify_tn_path($file)
{
	global $tn_path;
	
	if ($tn_path=='') return $file;
	else{
		// append the thumbnail-path to the path
		$pathSep = strstr(PHP_OS, "WIN") ? "\\" : "/";;
		$path = reverse_strrchr($file,$pathSep);
		if ($path===false) return $tn_path . $file;
		else return str_replace($path,$path . $tn_path,$file);
	}
}

function http_headers($file='')
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
	
	if (isset($_SERVER["SERVER_PROTOCOL"]) && $_SERVER["SERVER_PROTOCOL"] == "HTTP/1.1") 
		$httpProtocol = "HTTP/1.1";
	else
		$httpProtocol = "HTTP/1.0";
	
	if (file_exists($file))
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
		if ($use404)
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
			$src = imagecreate($_GET['w'], $_GET['h']);											// Create a blank image.
			$bgc = imagecolorallocate($src, 255, 255, 255);
			$tc  = imagecolorallocate($src, 0, 0, 0);
			imagefilledrectangle($src, 0, 0, $_GET['w'], $_GET['h'], $bgc);
			imagestring($src, 1, 5, 5, 'Error', $tc);
			imagejpeg($src, '', 100);
			imagedestroy($src);
			exit();
		}
	}
}

// Get the size of the image:
$image = @getimagesize($_GET['img']);

// Check the input variables and decide what to do:
if (empty($image) || empty($_GET['w']) || empty($_GET['h']) || (empty($allow_larger) && ($_GET['w'] > $image[0] || $_GET['h'] > $image[1])))
{
	if (empty($image) || empty($show_original))
	{
		http_headers();
	}
	else
	{
		// 2Do: Return the original image w/o making a copy (as that is what we currently do):
		$_GET['w'] = $image[0];
		$_GET['h'] = $image[1];
	}
}

// Create appropriate image header:
if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
{
	header('Content-type: image/jpeg');
	if ($tn_server_cache) $filename = modify_tn_path($_GET['img'] .'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.jpg');
}
elseif ($image[2] == 1 && function_exists('imagegif'))
{
	header('Content-type: image/gif');
	if ($tn_server_cache) $filename = modify_tn_path($_GET['img'] .'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.gif');
}
elseif ($image[2] == 3 || $image[2] == 1)
{
	header('Content-type: image/png');
	if ($tn_server_cache) $filename = modify_tn_path($_GET['img'] .'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.png');
}

// If you are required to set the full path for file_exists(), set this:
// $filename = '/your/path/to/catalog/'.$filename;

if (file_exists($filename) && $tn_server_cache && filemtime($filename) > filemtime($_GET['img']))
{
	// Output Cache Headers
	http_headers($filename);
	
	if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
	{
		$src = imagecreatefromjpeg($filename);
		imagejpeg($src, '', 100);
	}
	elseif ($image[2] == 1 && function_exists('imagegif'))
	{
		$src = imagecreatefromgif($filename);
		imagegif($src);
	}
	elseif ($image[2] == 3 || $image[2] == 1)
	{
		$src = imagecreatefrompng($filename);
		imagepng($src);
	}
	else
	{
		// Not an image or imagecreatefrom...-function does not exits.
		// Let's output an error
		http_headers();
	}
}
else
{
	// Create a new, empty image based on settings:
	if (function_exists('imagecreatetruecolor') && $use_truecolor && ($image[2] == 2 || $image[2] == 3))
	{
		$tmp_img = imagecreatetruecolor($_GET['w'],$_GET['h']);
	}
	else
	{
		$tmp_img = imagecreate($_GET['w'],$_GET['h']);
	}

	$th_bg_color = imagecolorallocate($tmp_img, $r, $g, $b);

	imagefill($tmp_img, 0, 0, $th_bg_color);
	imagecolortransparent($tmp_img, $th_bg_color);

	// Create the image to be scaled:
	if ($image[2] == 2 && function_exists('imagecreatefromjpeg'))
	{
		$src = imagecreatefromjpeg($_GET['img']);
	}
	elseif ($image[2] == 1 && function_exists('imagecreatefromgif'))
	{
		$src = imagecreatefromgif($_GET['img']);
	}
	elseif (($image[2] == 3 || $image[2] == 1) && function_exists('imagecreatefrompng'))
	{
		$src = imagecreatefrompng($_GET['img']);
	}
	else
	{
		// Not an image or imagecreatefrom...-function does not exits.
		// Let's output an error
		http_headers();
	}

	// Scale the image based on settings:
	if (function_exists('imagecopyresampled') && $use_resampling)
	{
		imagecopyresampled($tmp_img, $src, 0, 0, 0, 0, $_GET['w'], $_GET['h'], $image[0], $image[1]);
	}
	else
	{
		imagecopyresized($tmp_img, $src, 0, 0, 0, 0, $_GET['w'], $_GET['h'], $image[0], $image[1]);
	}

	// Output the image:
	if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
	{
		if ($tn_server_cache)
		{
			$thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.jpg');
			imagejpeg($tmp_img,$thumbnail, 100);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
		imagejpeg($tmp_img, '', 100);
	}
	elseif ($image[2] == 1 && function_exists('imagegif'))
	{
		if ($tn_server_cache)
		{
			$thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.gif');
			imagegif($tmp_img,$thumbnail);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
		imagegif($tmp_img);
	}
	elseif ($image[2] == 3 || $image[2] == 1)
	{
		if ($tn_server_cache)
		{
			$thumbnail = modify_tn_path($_GET['img'].'.thumb_'.$_GET['w'].'x'.$_GET['h'].'.png');
			imagepng($tmp_img,$thumbnail);
			http_headers($thumbnail);
		}
		else
		{
			http_headers($_GET['img']);
		}
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

?>