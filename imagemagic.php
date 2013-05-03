<?php
error_reporting(0);

  if (file_exists('includes/local/configure.php')) {

      include_once('includes/local/configure.php');
  } else {

      require_once('includes/configure.php');
  }


  require('includes/imagemagic/imagemagic.functions.php');
  chdir(DIR_FS_CATALOG);

  $server = DB_SERVER;

  $user = DB_SERVER_USERNAME;

  $password = DB_SERVER_PASSWORD;

  $dbname = DB_DATABASE;

  $config_values = "";
  $dbconn = @mysql_connect($server, $user, $password) or http_headers('', 'Error,Database Connection');
  @mysql_select_db($dbname, $dbconn) or http_headers('', 'Error,Database Connection');
  $sql = "select configuration_key as cfgKey, configuration_value as cfgValue from configuration where configuration_group_id='333' or configuration_group_id='4'";
  $result = @mysql_query($sql, $dbconn) or http_headers('', 'Error,Database Connection');
  while ($row = @mysql_fetch_array($result)) {

      if ($row['cfgKey'] != "LAST_HASH")
          $config_values .= $row['cfgKey'] . '=' . $row['cfgValue'];
      define($row['cfgKey'], $row['cfgValue']);
  }

  $append_hash = md5($config_values);

  if ($append_hash != LAST_HASH) {
      $sql = "update configuration set configuration_value ='" . $append_hash . "' where configuration_key='LAST_HASH'";
      $result = @mysql_query($sql, $dbconn) or fail(30);
      $cache_update = true;
  }

  $page_prefix = '';
  if ($_GET['w'] == SMALL_IMAGE_WIDTH || $_GET['h'] == SMALL_IMAGE_HEIGHT)
      $thumbnail_size = 1;
  elseif ($_GET['w'] == HEADING_IMAGE_WIDTH || $_GET['h'] == HEADING_IMAGE_HEIGHT)
      $thumbnail_size = 2;
  elseif ($_GET['w'] == SUBCATEGORY_IMAGE_WIDTH || $_GET['h'] == SUBCATEGORY_IMAGE_HEIGHT)
      $thumbnail_size = 3;
  if ($_GET['page'] == "prod_info") {
      $thumbnail_size = 4;
      $page_prefix = $page . "prod_info_";
  }
  if ($_GET['page'] == "popup") {
      $thumbnail_size = 5;
      $page_prefix = $page . "prod_info_";
  }

  $quality = 100;
  switch ($thumbnail_size) {
      case 1:
          if (FRAME_SMALL == "Yes")
              $frame = true;
          if (BEVEL_SMALL == "Yes")
              $bevel = true;
          if (USE_WATERMARK_IMAGE_SMALL == "Yes")
              $image_watermark = true;
          if (USE_WATERMARK_TEXT_SMALL == "Yes")
              $text_watermark = true;
          $quality = (int)SMALL_JPEG_QUALITY;
          break;
      case 2:
          if (FRAME_HEADING == "Yes")
              $frame = true;
          if (BEVEL_HEADING == "Yes")
              $bevel = true;
          if (USE_WATERMARK_IMAGE_HEADING == "Yes")
              $image_watermark = true;
          if (USE_WATERMARK_TEXT_HEADING == "Yes")
              $text_watermark = true;
          $quality = (int)HEADING_JPEG_QUALITY;
          break;
      case 3:
          if (FRAME_CATEGORY == "Yes")
              $frame = true;
          if (BEVEL_CATEGORY == "Yes")
              $bevel = true;
          if (USE_WATERMARK_IMAGE_CATEGORY == "Yes")
              $image_watermark = true;
          if (USE_WATERMARK_TEXT_CATEGORY == "Yes")
              $text_watermark = true;
          $quality = (int)CATEGORY_JPEG_QUALITY;
          break;
      case 4:
          if (FRAME_PRODUCT == "Yes")
              $frame = true;
          if (BEVEL_PRODUCT == "Yes")
              $bevel = true;
          if (USE_WATERMARK_IMAGE_PRODUCT == "Yes")
              $image_watermark = true;
          if (USE_WATERMARK_TEXT_PRODUCT == "Yes")
              $text_watermark = true;
          $quality = (int)PRODUCT_JPEG_QUALITY;
          break;
      case 5:
          if (FRAME_POPUP == "Yes")
              $frame = true;
          if (BEVEL_POPUP == "Yes")
              $bevel = true;
          if (USE_WATERMARK_IMAGE_POPUP == "Yes")
              $image_watermark = true;
          if (USE_WATERMARK_TEXT_POPUP == "Yes")
              $text_watermark = true;
          $quality = (int)POPUP_JPEG_QUALITY;
          break;
  }

  $use_resampling = CFG_USE_RESAMPLING == 'True' ? true : false;
  $use_truecolor = CFG_CREATE_TRUECOLOR == 'True' ? true : false;
  $gif_as_jpeg = CFG_GIFS_AS_JPEGS == 'True' ? true : false;
  $tn_server_cache = CFG_TN_SERVER_CACHE == 'True' ? true : false;
  $tn_path = CFG_TN_CACHE_DIRECTORY;
  $tn_browser_cache = CFG_TN_BROWSER_CACHE == 'True' ? true : false;
  $use404 = CFG_USE_404 == 'True' ? true : false;

  $show_original = true;

  if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY != "") {
      $result = '';
      $key = CFG_ENCRYPTION_KEY;
      $string = base64_decode($_GET['img']);
      for ($i = 0; $i < strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key)) - 1, 1);
          $char = chr(ord($char) - ord($keychar));
          $result .= $char;
      }
      $_GET['img'] = $result;
  }

  $image = @getimagesize($_GET['img']);


  if (!$image[0])
      http_headers('', 'Error,File path incorrect,check configure.php');

  if (($_GET['w'] > $image[0] || $_GET['h'] > $image[1]) && CFG_ALLOW_LARGER != 'True') {
      $over_ride_width = $image[0];
      $over_ride_height = $image[1];
  }

  $reduction_ratio = !isset($over_ride_width) ? $_GET['w'] / $image[0] : 1;

  if (empty($image) || empty($_GET['w']) || empty($_GET['h'])) {
      if (empty($image) || empty($show_original)) {
          http_headers();
      } else {
          $_GET['w'] = $image[0];
          $_GET['h'] = $image[1];
      }
  }

  if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg))
      $extension = "jpg";
  elseif ($image[2] == 1 && function_exists('imagegif'))
      $extension = "gif";
  elseif ($image[2] == 3 || $image[2] == 1)
      $extension = "png";

  if ($tn_server_cache)
      $filename = modify_tn_path($_GET['img'] . '.thumb_' . $page_prefix . $_GET['w'] . 'x' . $_GET['h'] . '_' . $append_hash . '.' . $extension, false);



  if ($tn_server_cache && file_exists($filename) && filemtime($filename) > filemtime($_GET['img'])) {
      $quality = 100;

      http_headers($filename);
      if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg)) {
          $src = imagecreatefromjpeg($filename);
          header('Content-type: image/jpeg');
          imagejpeg($src, '', $quality);
      } elseif ($image[2] == 1 && function_exists('imagegif')) {
          $src = imagecreatefromgif($filename);
          header('Content-type: image/gif');
          imagegif($src);
      } elseif ($image[2] == 3 || $image[2] == 1) {
          $src = imagecreatefrompng($filename);

          if (function_exists('imageSaveAlpha')) {
              imagesavealpha($src, true);
              imagealphablending($src, false);
          }
          header('Content-type: image/png');
          imagepng($src);
      } else {


          http_headers('', 'Error,Invalid image');
      }
  } else {

      if (function_exists('imagecreatetruecolor') && $use_truecolor && ($extension == "png" || $extension == "jpg")) {
          $tmp_img = imagecreatetruecolor(!isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height);
      } else {
          $tmp_img = imagecreate(!isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height);
      }
      $th_bg_color = imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
      imagefill($tmp_img, 0, 0, $th_bg_color);
      imagecolortransparent($tmp_img, $th_bg_color);

      if ($extension == "jpg" && function_exists('imagecreatefromjpeg')) {
          $src = imagecreatefromjpeg($_GET['img']);
      } elseif ($extension == "gif" && function_exists('imagecreatefromgif')) {
          $src = imagecreatefromgif($_GET['img']);
      } elseif (($extension == "png" || $extension == "gif") && function_exists('imagecreatefrompng')) {
          $src = imagecreatefrompng($_GET['img']);

          if (!$frame & !$bevel) {

              $tmp_img = imagecreatetruecolor(!isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height);
              imageantialias($tmp_img, true);
              imagealphablending($tmp_img, false);
              imagesavealpha($tmp_img, true);
              $transparent = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
              imagefilledrectangle($tmp_img, 0, 0, !isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height, $transparent);
          }
      } elseif ($extension == "gif" && function_exists('imagecreatefrompng')) {
          $src = imagecreatefrompng($_GET['img']);
      } else {


          http_headers('', 'Error,Image Not Valid');
      }

      if (CFG_CENTER_THUMB == "True" & CFG_ALLOW_LARGER == "True" && ($_GET['w'] > $image[0] || $_GET['h'] > $image[1])) {
          $cx = ($_GET['w'] - $image[0]) / 2;
          $cy = ($_GET['h'] - $image[1]) / 2;
          $over_ride_width = $image[0];
          $over_ride_height = $image[1];
      } else {
          $cx = 0;
          $cy = 0;
      }

      if (function_exists('imagecopyresampled') && $use_resampling) {
          imagecopyresampled($tmp_img, $src, $cx, $cy, 0, 0, !isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height, $image[0], $image[1]);
      } else {
          imagecopyresized($tmp_img, $src, $cx, $cy, 0, 0, !isset($over_ride_width) ? $_GET['w'] : $over_ride_width, !isset($over_ride_height) ? $_GET['h'] : $over_ride_height, $image[0], $image[1]);
      }

      if (BRIGHTNESS_ADJUST != "0")
          adjust_brightness($tmp_img, BRIGHTNESS_ADJUST);
      if (CONTRAST_ADJUST != "0")
          adjust_contrast($tmp_img, CONTRAST_ADJUST);
      if ($image_watermark)
          watermark_image($tmp_img, DIR_FS_CATALOG . 'includes/imagemagic/watermarks/' . WATERMARK_IMAGE, WATERMARK_IMAGE_POSITION, WATERMARK_IMAGE_OPACITY, WATERMARK_IMAGE_MARGIN);
      if ($frame)
          frame($tmp_img, FRAME_WIDTH, FRAME_EDGE_WIDTH, FRAME_COLOR, FRAME_INSIDE_COLOR1, FRAME_INSIDE_COLOR2);
      if ($bevel)
          bevel($tmp_img, BEVEL_HEIGHT, BEVEL_HIGHLIGHT, BEVEL_SHADOW);
      if ($text_watermark)
          watermark_text($tmp_img, WATERMARK_TEXT, WATERMARK_TEXT_SIZE, WATERMARK_TEXT_POSITION, WATERMARK_TEXT_COLOR, 'includes/imagemagic/fonts/' . WATERMARK_TEXT_FONT, WATERMARK_TEXT_OPACITY, WATERMARK_TEXT_MARGIN, WATERMARK_TEXT_ANGLE);

      if ($image[2] == 2 || ($image[2] == 1 && $gif_as_jpeg)) {
          if ($tn_server_cache) {
              $thumbnail = modify_tn_path($_GET['img'] . '.thumb_' . $page_prefix . $_GET['w'] . 'x' . $_GET['h'] . '_' . $append_hash . '.jpg', true);
              imagejpeg($tmp_img, $thumbnail, $quality);
              http_headers($thumbnail);
          } else {
              http_headers($_GET['img']);
          }
          header('Content-type: image/jpeg');
          imagejpeg($tmp_img, '', $quality);
      } elseif ($image[2] == 1 && function_exists('imagegif')) {
          if ($tn_server_cache) {
              $thumbnail = modify_tn_path($_GET['img'] . '.thumb_' . $page_prefix . $_GET['w'] . 'x' . $_GET['h'] . '_' . $append_hash . '.gif', true);
              imagegif($tmp_img, $thumbnail);
              http_headers($thumbnail);
          } else {
              http_headers($_GET['img']);
          }
          header('Content-type: image/gif');
          imagegif($tmp_img);
      } elseif ($image[2] == 3 || $image[2] == 1) {
          if ($tn_server_cache) {
              $thumbnail = modify_tn_path($_GET['img'] . '.thumb_' . $page_prefix . $_GET['w'] . 'x' . $_GET['h'] . '_' . $append_hash . '.png', true);
              imagepng($tmp_img, $thumbnail);
              http_headers($thumbnail);
          } else {
              http_headers($_GET['img']);
          }
          header('Content-type: image/png');
          imagepng($tmp_img);
      } else {


          http_headers();
      }

      imagedestroy($src);
      imagedestroy($tmp_img);
  }
  function modify_tn_path($file, $check_cache)
  {

      global $tn_path, $append_hash;
      if ($tn_path == '')
          return $file;
      else {

          if (substr($tn_path, 0, 1) == '\\' || substr($tn_path, 0, 1) == '/')
              $tn_path = substr($tn_path, 1);
          if (substr($tn_path, strlen($tn_path) - 1, 1) == '\\' || substr($tn_path, strlen($tn_path) - 1, 1) == '/')
              $tn_path = substr($tn_path, 0, strlen($tn_path) - 1);
          $tn_path .= '/';

          $create_path = dirname($tn_path . $file);
          if (!is_dir($create_path)) {
              if (!make_dirs($create_path))
                  http_headers('', "Cache Error,Cannot Create Dir.,Check The Readme");
          }

          if (CFG_CACHE_AUTO_CLEAN == "True" && $check_cache) {
              $cwd = getcwd();
              chdir($create_path);
              foreach (glob("*.*") as $filename) {
                  if (!is_dir($filename) && !strstr($filename, $append_hash)) {
                      unlink($filename);
                  }
              }
              chdir($cwd);
          }
          return $tn_path . $file;
      }
  }
  function make_dirs($path)
  {
      return is_dir($path) or (make_dirs(dirname($path), 0777) and mkdir($path, 0777));
  }
  function http_headers($file = '', $error = '')
  {








      global $use404, $tn_browser_cache;
      $quality = 100;
      if (isset($_SERVER["SERVER_PROTOCOL"]) && $_SERVER["SERVER_PROTOCOL"] == "HTTP/1.1")
          $httpProtocol = "HTTP/1.1";
      else
          $httpProtocol = "HTTP/1.0";
      if ($file != '' && file_exists($file)) {
          if (isset($_SERVER["HTTP_CACHE_CONTROL"])) {
              $tn_browser_cache = strtolower($_SERVER["HTTP_CACHE_CONTROL"]) == "no-cache" ? false : $tn_browser_cache;
          }

          $lastModified = filemtime($file);
          $lastModifiedGMT = $lastModified - date('Z');
          $lastModifiedHttpFormat = gmstrftime("%a, %d %b %Y %T %Z", $lastModified);

          $eTag = '"1fa44b7-' . dechex(filesize($file)) . "-" . dechex($lastModifiedGMT) . '"';
          if ($tn_browser_cache) {
              $lastModifiedFromHttp = "xxx";
              if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])) {
                  $lastModifiedFromHttp = ($_SERVER["HTTP_IF_MODIFIED_SINCE"] === "") ? "xxx" : $_SERVER["HTTP_IF_MODIFIED_SINCE"];
              }

              $foundETag = "";
              if (isset($_SERVER["HTTP_IF_NONE_MATCH"])) {
                  $foundETag = stripslashes($_SERVER["HTTP_IF_NONE_MATCH"]);
              }

              if ($lastModifiedFromHttp == $lastModifiedHttpFormat) {
                  $sameLastModified = true;
              } elseif (strpos($lastModifiedFromHttp, $lastModifiedHttpFormat) !== false) {
                  $sameLastModified = true;
              } else {
                  $sameLastModified = false;
              }
              if (($eTag == $foundETag) && $sameLastModified) {

                  $is304 = true;
              }
              else

                  $is304 = (($foundETag == "") && $sameLastModified);
              if ($is304) {


                  if ($lastModifiedGMT > 946080000) {


                      header("ETag: " . $eTag);
                  }
                  header("Status: 304 Not Modified");
                  header($httpProtocol . " 304 Not Modified");
                  header("Connection: close");
                  exit();
              }
          }


          header('Pragma: ');
          header('Expires: ');
          if ($tn_browser_cache) {
              if ($lastModifiedGMT > 946080000) {

                  header('ETag: ' . $eTag);
              }
              header('Last-Modified: ' . $lastModifiedHttpFormat);
              header('Cache-Control: private');
          } else {
              header('Cache-Control: no-cache');
          }
      } else {
          if ($use404 && $error == '') {


              header("TEST404: TEST404");
              header("Status: 404 Not Found");
              header($httpProtocol . " 404 Not Found");
              exit();
          } else {



              header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

              header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

              header("Cache-Control: no-store, no-cache, must-revalidate");

              header("Cache-Control: post-check=0, pre-check=0", false);

              header("Pragma: no-cache");
              header('Content-type: image/jpeg');
              if ($error == "")
                  $error = "Error";

              $src = imagecreate($_GET['w'], $_GET['h']);
              $bgc = imagecolorallocate($src, 255, 255, 255);
              $tc = imagecolorallocate($src, 0, 0, 0);
              $string = explode(',', $error);
              imagefilledrectangle($src, 0, 0, $_GET['w'], $_GET['h'], $bgc);
              foreach ($string as $error) {
                  imagestring($src, 1, 5, $line * imagefontheight(1), $error, $tc);
                  $line++;
              }
              imagejpeg($src, '', 100);
              imagedestroy($src);
              exit();
          }
      }
  }
  function watermark_text(&$gdimg, $text, $size, $alignment, $hex_color = '000000', $ttffont = '', $opacity = 100, $margin = 5, $angle = 0)
  {

      if (!function_exists('ImageTTFbBox'))
          http_headers('', "FreeType not supported,Switch off text watermarks");
      if (!$text || $ttffont == "" || $size == 0 || !@is_readable($ttffont) || !is_file($ttffont)) {
          return false;
      }
      imagealphablending($gdimg, true);
      $text = str_replace("\r\n", "\n", $text);
      $text = str_replace("\r", "\n", $text);
      $textlines = explode("\n", $text);
      $opacity = 100 - intval(max(min($opacity, 100), 0));
      $TTFbox = imagettfbbox($size, $angle, $ttffont, $text);
      $min_x = min($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
      $max_x = max($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);

      $text_width = round($max_x - $min_x);
      $min_y = min($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
      $max_y = max($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);

      $text_height = round($max_y - $min_y);
      $TTFboxChar = imagettfbbox($size, $angle, $ttffont, 'pH');
      $char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
      $char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
      $char_height = round($char_max_y - $char_min_y);
      $char_margin = 0;
      if ($angle != 0) {
          $TTFboxChar = imagettfbbox($size, 0, $ttffont, 'pH');
          $char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
          $char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
          $char_margin = round($char_max_y - $char_min_y);
      }
      switch ($alignment) {
          case 'Top':
              $text_origin_x = round((imagesx($gdimg) - $text_width) / 2);
              $text_origin_y = $char_height + $margin;
              break;
          case 'Bottom':
              $text_origin_x = round((imagesx($gdimg) - $text_width) / 2);
              $text_origin_y = imagesy($gdimg) - $TTFbox[1] - $margin;
              break;
          case 'Left':
              $text_origin_x = $margin;
              $text_origin_y = round((imagesy($gdimg) - $text_height) / 2) + $char_height;
              break;
          case 'Right':
              $text_origin_x = imagesx($gdimg) - $text_width + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
              $text_origin_y = round((imagesy($gdimg) - $text_height) / 2) + $char_height;
              break;
          case 'Center':
              $text_origin_x = round((imagesx($gdimg) - $text_width) / 2);
              $text_origin_y = round((imagesy($gdimg) - $text_height) / 2) + $char_height;
              break;
          case 'Top Left':
              $text_origin_x = $margin;
              $text_origin_y = $char_height + $margin;
              break;
          case 'Top Right':
              $text_origin_x = imagesx($gdimg) - $text_width + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
              $text_origin_y = $char_height + $margin;
              break;
          case 'Bottom Left':
              $text_origin_x = $margin;
              $text_origin_y = imagesy($gdimg) - $TTFbox[1] - $margin;
              break;
          case 'Bottom Right':
          default:
              $text_origin_x = imagesx($gdimg) - $text_width + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
              $text_origin_y = imagesy($gdimg) - $TTFbox[1] - $margin;
              break;
      }
      $letter_color_text = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hex_color, false, $opacity * 1.27);
      if ($alignment == 'Tiled') {
          $text_origin_y = 0 - $char_height;
          while (($text_origin_y - $text_height) < imagesy($gdimg)) {
              $text_origin_x = $margin + $char_margin;
              while ($text_origin_x < imagesx($gdimg)) {
                  imagettftext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);
                  $text_origin_x += ($text_width + $margin);
              }
              $text_origin_y += ($text_height + $margin);
          }
      } else {
          imagettftext($gdimg, $size, $angle, $text_origin_x + $char_margin, $text_origin_y, $letter_color_text, $ttffont, $text);
      }
      return true;
  }
  function watermark_image(&$gdimg_dest, $img_watermark_filename, $alignment = '*', $opacity = 95, $margin = 5)
  {
      global $image, $reduction_ratio;

      if ($image[2] == 1 || $img_watermark_filename == "" || !is_file($img_watermark_filename))
          return false;

      $image_params = @getimagesize($img_watermark_filename);
      if ($image_params[2] == 2)
          $img_watermark = imagecreatefromjpeg($img_watermark_filename);
      elseif ($image_params[2] == 1)
          $img_watermark = imagecreatefromgif($img_watermark_filename);
      elseif ($image_params[2] == 3)
          $img_watermark = imagecreatefrompng($img_watermark_filename);
      else
          return false;

      if (CFG_RESIZE_WATERMARK == "True" && $reduction_ratio != 1) {
          $width = intval($image_params[0] * $reduction_ratio);
          $height = intval($image_params[1] * $reduction_ratio);
          if ($image_params[2] == 1) {
              $tmp_img = imagecreate($width, $height);
              $th_bg_color = imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
              imagefill($tmp_img, 0, 0, $th_bg_color);
              imagecolortransparent($tmp_img, $th_bg_color);
          } elseif ($image_params[2] == 2) {
              $tmp_img = imagecreatetruecolor($width, $height);
              $th_bg_color = imagemagic_functions::ImageHexcolorAllocate($tmp_img, CFG_MATTE_COLOR);
              imagefill($tmp_img, 0, 0, $th_bg_color);
              imagecolortransparent($tmp_img, $th_bg_color);
          } elseif ($image_params[2] == 3) {
              $tmp_img = imagecreatetruecolor($width, $height);
              imageantialias($tmp_img, true);
              imagealphablending($tmp_img, false);
              imagesavealpha($tmp_img, true);
              $transparent = imagecolorallocatealpha($tmp_img, 255, 255, 255, 127);
              imagefilledrectangle($tmp_img, 0, 0, $width, $height, $transparent);
          }

          if (function_exists('imagecopyresampled') && $use_resampling) {
              imagecopyresampled($tmp_img, $img_watermark, 0, 0, 0, 0, $width, $height, $image_params[0], $image_params[1]);
          } else {
              imagecopyresized($tmp_img, $img_watermark, 0, 0, 0, 0, $width, $height, $image_params[0], $image_params[1]);
          }
          $img_watermark = $tmp_img;
          $image_params[0] = $width;
          $image_params[1] = $height;
      }
      if (is_resource($gdimg_dest) && is_resource($img_watermark)) {
          $watermark_source_x = 0;
          $watermark_source_y = 0;
          $img_source_width = imagesx($gdimg_dest);
          $img_source_height = imagesy($gdimg_dest);
          $watermark_source_width = imagesx($img_watermark);
          $watermark_source_height = imagesy($img_watermark);
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
                      imagealphablending($gdimg_tiledwatermark, false);
                      if (imagemagic_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
                          imagesavealpha($gdimg_tiledwatermark, true);
                      }
                      $text_color_transparent = imagemagic_functions::ImagecolorAllocateAlphaSafe($gdimg_tiledwatermark, 255, 0, 255, 127);
                      imagefill($gdimg_tiledwatermark, 0, 0, $text_color_transparent);
                      for ($x = $watermark_margin_x; $x < ($img_source_width + $watermark_source_width); $x += round($watermark_source_width + ((1 - $watermark_margin_percent) * $img_source_width))) {
                          for ($y = $watermark_margin_y; $y < ($img_source_height + $watermark_source_height); $y += round($watermark_source_height + ((1 - $watermark_margin_percent) * $img_source_height))) {
                              imagecopy($gdimg_tiledwatermark, $img_watermark, $x, $y, 0, 0, min($watermark_source_width, $img_source_width - $x - ((1 - $watermark_margin_percent) * $img_source_width)), min($watermark_source_height, $img_source_height - $y - ((1 - $watermark_margin_percent) * $img_source_height)));
                          }
                      }
                      $watermark_source_width = imagesx($gdimg_tiledwatermark);
                      $watermark_source_height = imagesy($gdimg_tiledwatermark);
                      $watermark_destination_x = 0;
                      $watermark_destination_y = 0;
                      imagedestroy($img_watermark);
                      $img_watermark = $gdimg_tiledwatermark;
                  }
                  break;
              case 'Top':
                  $watermark_destination_x = round((($img_source_width / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
                  $watermark_destination_y = $watermark_margin_y;
                  break;
              case 'Bottom':
                  $watermark_destination_x = round((($img_source_width / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
                  $watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
                  break;
              case 'Left':
                  $watermark_destination_x = $watermark_margin_x;
                  $watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
                  break;
              case 'Right':
                  $watermark_destination_x = round(($img_source_width - $watermark_source_width) * $watermark_margin_percent);
                  $watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
                  break;
              case 'Center':
                  $watermark_destination_x = round(($img_source_width / 2) - ($watermark_source_width / 2));
                  $watermark_destination_y = round(($img_source_height / 2) - ($watermark_source_height / 2));
                  break;
              case 'Top Left':
                  $watermark_destination_x = $watermark_margin_x;
                  $watermark_destination_y = $watermark_margin_y;
                  break;
              case 'Top Right':
                  $watermark_destination_x = round(($img_source_width - $watermark_source_width) * $watermark_margin_percent);
                  $watermark_destination_y = $watermark_margin_y;
                  break;
              case 'Bottom Left':
                  $watermark_destination_x = $watermark_margin_x;
                  $watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
                  break;
              case 'Bottom Right':
              default:
                  $watermark_destination_x = round(($img_source_width - $watermark_source_width) * $watermark_margin_percent);
                  $watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
                  break;
          }
          imagealphablending($gdimg_dest, false);

          imagesavealpha($gdimg_dest, true);
          imagesavealpha($img_watermark, true);

          imagemagic_functions::ImageCopyRespectAlpha($gdimg_dest, $img_watermark, $watermark_destination_x, $watermark_destination_y, 0, 0, $watermark_source_width, $watermark_source_height, $watermark_opacity_percent);
          return true;
      }
      return false;
  }
  function adjust_brightness(&$gdimg, $amount = 0)
  {
      global $image;
      if ($image[2] == 1 || $amount == 0)
          return false;
      $amount = max(-255, min(255, $amount));
      if (imagemagic_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && imagemagic_functions::gd_is_bundled()) {
          if (imagefilter($gdimg, IMG_FILTER_BRIGHTNESS, $amount)) {
              return true;
          }
      }
      $scaling = (255 - abs($amount)) / 255;
      $baseamount = (($amount > 0) ? $amount : 0);
      for ($x = 0; $x < imagesx($gdimg); $x++) {
          for ($y = 0; $y < imagesy($gdimg); $y++) {
              $OriginalPixel = imagemagic_functions::GetPixelcolor($gdimg, $x, $y);
              foreach ($OriginalPixel as $key => $value) {
                  $NewPixel[$key] = round($baseamount + ($OriginalPixel[$key] * $scaling));
              }
              $newcolor = imagecolorallocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
              imagesetpixel($gdimg, $x, $y, $newcolor);
          }
      }
      return true;
  }
  function adjust_contrast(&$gdimg, $amount = 0)
  {
      global $image;
      if ($image[2] == 1 || $amount == 0)
          return false;
      $amount = max(-255, min(255, $amount));
      if (imagemagic_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && imagemagic_functions::gd_is_bundled()) {
          if (imagefilter($gdimg, IMG_FILTER_CONTRAST, $amount)) {
              return true;
          }
          $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_CONTRAST, ' . $amount . ')', __FILE__, __LINE__);

      }
      if ($amount > 0) {
          $scaling = 1 + ($amount / 255);
      } else {
          $scaling = (255 - abs($amount)) / 255;
      }
      for ($x = 0; $x < imagesx($gdimg); $x++) {
          for ($y = 0; $y < imagesy($gdimg); $y++) {
              $OriginalPixel = imagemagic_functions::GetPixelcolor($gdimg, $x, $y);
              foreach ($OriginalPixel as $key => $value) {
                  $NewPixel[$key] = min(255, max(0, round($OriginalPixel[$key] * $scaling)));
              }
              $newcolor = imagecolorallocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
              imagesetpixel($gdimg, $x, $y, $newcolor);
          }
      }
  }
  function bevel(&$gdimg, $width, $hexcolor1, $hexcolor2)
  {
      $width = ($width ? $width : 5);
      $hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'CCCCCC');
      $hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');
      imagealphablending($gdimg, true);
      for ($i = 0; $i < $width; $i++) {
          $alpha = round(($i / $width) * 127);
          $color1[$i] = imagemagic_functions::ImageHexColorAllocate($gdimg, $hexcolor1, false, $alpha);
          $color2[$i] = imagemagic_functions::ImageHexColorAllocate($gdimg, $hexcolor2, false, $alpha);

          imageline($gdimg, $i, $i, $i, imagesy($gdimg) - $i, $color1[$i]);

          imageline($gdimg, $i, $i, imagesx($gdimg) - $i, $i, $color1[$i]);

          imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i, imagesx($gdimg) - $i, $i, $color2[$i]);

          imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i, $i, imagesy($gdimg) - $i, $color2[$i]);
      }
      return true;
  }
  function frame(&$gdimg, $frame_width, $edge_width, $hexcolor_frame, $hexcolor1, $hexcolor2)
  {
      $frame_width = ($frame_width ? $frame_width : 5);
      $edge_width = ($edge_width ? $edge_width : 1);
      $hexcolor_frame = ($hexcolor_frame ? $hexcolor_frame : 'CCCCCC');
      $hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'FFFFFF');
      $hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');
      $color_frame = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor_frame);
      $color1 = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor1);
      $color2 = imagemagic_functions::ImageHexcolorAllocate($gdimg, $hexcolor2);
      for ($i = 0; $i < $edge_width; $i++) {


          imageline($gdimg, $i, $i, $i, imagesy($gdimg) - $i, $color1);

          imageline($gdimg, $i, $i, imagesx($gdimg) - $i, $i, $color1);

          imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i, imagesx($gdimg) - $i, $i, $color2);

          imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i, $i, imagesy($gdimg) - $i, $color2);
      }
      for ($i = 0; $i < $frame_width; $i++) {

          imagerectangle($gdimg, $edge_width + $i, $edge_width + $i, imagesx($gdimg) - $edge_width - $i, imagesy($gdimg) - $edge_width - $i, $color_frame);
      }
      for ($i = 0; $i < $edge_width; $i++) {


          imageline($gdimg, $frame_width + $edge_width + $i, $frame_width + $edge_width + $i, $frame_width + $edge_width + $i, imagesy($gdimg) - $frame_width - $edge_width - $i, $color2);

          imageline($gdimg, $frame_width + $edge_width + $i, $frame_width + $edge_width + $i, imagesx($gdimg) - $frame_width - $edge_width - $i, $frame_width + $edge_width + $i, $color2);

          imageline($gdimg, imagesx($gdimg) - $frame_width - $edge_width - $i, imagesy($gdimg) - $frame_width - $edge_width - $i, imagesx($gdimg) - $frame_width - $edge_width - $i, $frame_width + $edge_width + $i, $color1);

          imageline($gdimg, imagesx($gdimg) - $frame_width - $edge_width - $i, imagesy($gdimg) - $frame_width - $edge_width - $i, $frame_width + $edge_width + $i, imagesy($gdimg) - $frame_width - $edge_width - $i, $color1);
      }
      return true;
  }
?>