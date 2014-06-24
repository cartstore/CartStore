<?php
/*
  $Id: validation_png.php,v 2.1a 2006/09/28 18:44:27 alexstudio Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/
// include necessary pre-amble

define('ANTI_ROBOT_IMAGE_PHP_BITMAP_FONT', '5');

    error_reporting(0);
	require_once('includes/configure.php');
	define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
	require_once(DIR_WS_INCLUDES . 'filenames.php');
	require_once(DIR_WS_INCLUDES . 'database_tables.php');
	require_once(DIR_WS_FUNCTIONS . 'database.php');
	tep_db_connect() or die('Unable to connect to database server!');

	$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
	while ($configuration = tep_db_fetch_array($configuration_query)) {
		define($configuration['cfgKey'], $configuration['cfgValue']);
	}
// End - include

// Derived from the original contribution by AlexStudio
// Note to potential users of this code ...
//
// Remember this is released under the _GPL_ and is subject
// to that licence. Do not incorporate this within software 
// released or distributed in any way under a licence other
// than the GPL. We will be watching ... ;)

// Do we have an id? No, then just exit
if(empty($_GET['rsid']))
{
  echo 'Empty rsid!!';
  exit;
}

$s_id = tep_db_output($_GET['rsid']);

// Try and grab reg_key for this id and session

$check_anti_robotreg_query = tep_db_query("select reg_key from anti_robotreg where session_id = '$s_id'");
$new_query_for_reg_key = tep_db_fetch_array($check_anti_robotreg_query);

$code = $new_query_for_reg_key['reg_key'];

$total_code_width = (ANTI_ROBOT_IMAGE_USE_TTF=='true') ? 0 : ((imagefontwidth(ANTI_ROBOT_IMAGE_FONT_SIZE)+ANTI_ROBOT_IMAGE_WHITE_SPACE) * strlen($code));
$max_code_height = (ANTI_ROBOT_IMAGE_USE_TTF=='true') ? 0 : imagefontheight(ANTI_ROBOT_IMAGE_FONT_SIZE);
if (ANTI_ROBOT_IMAGE_USE_TTF=='true') {
  for ($i=0; $i < strlen($code); $i++) {
    $angle_char[] = rand(-10, 10);
    $char_bbox = imagettfbbox(ANTI_ROBOT_IMAGE_FONT_SIZE, $angle_char[$i], ANTI_ROBOT_IMAGE_TTF, $code[$i]);
	$width_char[] = max($char_bbox[2], $char_bbox[4]) - min($char_bbox[0], $char_bbox[6]) + ANTI_ROBOT_IMAGE_WHITE_SPACE;
    $total_code_width += $width_char[$i];
    $max_code_height = max($max_code_height, max($char_bbox[1],$char_bbox[3]) -  max($char_bbox[5], $char_bbox[7]));
  }
}

$height = (ANTI_ROBOT_IMAGE_HEIGHT) ? ANTI_ROBOT_IMAGE_HEIGHT : $max_code_height + ANTI_ROBOT_IMAGE_TOP_MARGIN;
$width = (ANTI_ROBOT_IMAGE_WIDTH) ? ANTI_ROBOT_IMAGE_WIDTH : $total_code_width;

$bgc = hexdec(ANTI_ROBOT_IMAGE_BACKGROUND_COLOR);
$tc = hexdec(ANTI_ROBOT_IMAGE_TEXT_COLOR);
$image = @imagecreatetruecolor($total_code_width, $max_code_height + ANTI_ROBOT_IMAGE_TOP_MARGIN);
imagefilledrectangle($image, 0, 0, $width, $height, $bgc);
$bg_color = imagecolorallocate($image, ($bgc >> 16) & 0xFF, ($bgc >> 8) & 0xFF, $bgc & 0xFF);
$fg_color = imagecolorallocate($image, ($tc >> 16) & 0xFF, ($tc >> 8) & 0xFF, $tc & 0xFF);

$pos_x = rand(0,ANTI_ROBOT_IMAGE_WHITE_SPACE-1);
for ($i=0; $i < strlen($code); $i++) {
  if (ANTI_ROBOT_IMAGE_USE_TTF=='true')
      imagettftext($image, ANTI_ROBOT_IMAGE_FONT_SIZE, $angle_char[$i], $pos_x, $max_code_height + ANTI_ROBOT_IMAGE_TOP_MARGIN/2, $fg_color, ANTI_ROBOT_IMAGE_TTF, $code[$i]);
  else
      imagechar($image, ANTI_ROBOT_IMAGE_PHP_BITMAP_FONT, $pos_x, ANTI_ROBOT_IMAGE_TOP_MARGIN/2+rand(-ANTI_ROBOT_IMAGE_TOP_MARGIN/2, ANTI_ROBOT_IMAGE_TOP_MARGIN/2), $code[$i], $fg_color);
  $pos_x += (ANTI_ROBOT_IMAGE_USE_TTF=='true') ? $width_char[$i] : imagefontwidth(ANTI_ROBOT_IMAGE_FONT_SIZE) + ANTI_ROBOT_IMAGE_WHITE_SPACE;
}

$resized_image = @imagecreatetruecolor($width, $height);
if ((ANTI_ROBOT_IMAGE_HEIGHT != 0) || (ANTI_ROBOT_IMAGE_WIDTH != 0))
    imagecopyresized($resized_image, $image, 0, 0, 0, 0, (ANTI_ROBOT_IMAGE_WIDTH) ? ANTI_ROBOT_IMAGE_WIDTH : $width, (ANTI_ROBOT_IMAGE_HEIGHT) ? ANTI_ROBOT_IMAGE_HEIGHT : $height, $total_code_width, $max_code_height + ANTI_ROBOT_IMAGE_TOP_MARGIN);
else
    $resized_image = $image;

if (ANTI_ROBOT_IMAGE_FILTER_GREYSCALE=='true')
    image_greyscale($resized_image);
if (ANTI_ROBOT_IMAGE_FILTER_NOISE=='true')
	image_noise($resized_image); 
if (ANTI_ROBOT_IMAGE_FILTER_SCATTER=='true')
    image_scatter($resized_image);
if (ANTI_ROBOT_IMAGE_FILTER_INTERLACE=='true')
    image_interlace($resized_image, $fg_color, $bg_color);



header('Content-Type: image/png');
header('Cache-control: no-cache, no-store');
imagepng($resized_image);
imagedestroy($image);
imagedestroy($resized_image);
exit;

function image_noise (&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);

   for ($x = 0; $x < $imagex; ++$x) {
      for ($y = 0; $y < $imagey; ++$y) {
         if (rand(0,1)) {
            $rgb = imagecolorat($image, $x, $y);
            $red = ($rgb >> 16) & 0xFF;
            $green = ($rgb >> 8) & 0xFF;
            $blue = $rgb & 0xFF;
            $modifier = rand(-128,128);
            $red += $modifier;
            $green += $modifier;
            $blue += $modifier;

            if ($red > 255) $red = 255;
            if ($green > 255) $green = 255;
            if ($blue > 255) $blue = 255;
            if ($red < 0) $red = 0;
            if ($green < 0) $green = 0;
            if ($blue < 0) $blue = 0;

            $newcol = imagecolorallocate($image, $red, $green, $blue);
            imagesetpixel($image, $x, $y, $newcol);
         }
      }
   }
}

function image_scatter(&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);

   for ($x = 0; $x < $imagex; ++$x) {
      for ($y = 0; $y < $imagey; ++$y) {
         $distx = rand(-1, 1);
         $disty = rand(-1, 1);

         if ($x + $distx >= $imagex) continue;
         if ($x + $distx < 0) continue;
         if ($y + $disty >= $imagey) continue;
         if ($y + $disty < 0) continue;

         $oldcol = imagecolorat($image, $x, $y);
         $newcol = imagecolorat($image, $x + $distx, $y + $disty);
         imagesetpixel($image, $x, $y, $newcol);
         imagesetpixel($image, $x + $distx, $y + $disty, $oldcol);
      }
   }
}

   function image_interlace (&$image, $fg=0, $bg=255) {
      $imagex = imagesx($image);
      $imagey = imagesy($image);

      $fg_red = ($fg >> 16) & 0xFF;
      $fg_green = ($fg >> 8) & 0xFF;
      $fg_blue = $fg & 0xFF;
      $bg_red = ($bg >> 16) & 0xFF;
      $bg_green = ($bg >> 8) & 0xFF;
      $bg_blue = $bg & 0xFF;
	  $red = ($fg_red+$bg_red)/2;
	  $green = ($fg_green+$bg_green)/2;
	  $blue = ($fg_blue+$bg_blue)/2;

      $band = imagecolorallocate($image, $red, $green, $blue);

      for ($y = 0; $y < $imagey; $y+=2) {
            for ($x = 0; $x < $imagex; ++$x) {
               imagesetpixel($image, $x, $y, $band);
            }
      }
   }

function image_greyscale (&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);
   for ($x = 0; $x <$imagex; ++$x) {
      for ($y = 0; $y <$imagey; ++$y) {
         $rgb = imagecolorat($image, $x, $y);
         $red = ($rgb >> 16) & 0xFF;
         $green = ($rgb >> 8) & 0xFF;
         $blue = $rgb & 0xFF;
         $grey = (int)(($red+$green+$blue)/3);
         $newcol = imagecolorallocate($image, $grey, $grey, $grey);
         imagesetpixel($image, $x, $y, $newcol);
      }
   }
}

?>
