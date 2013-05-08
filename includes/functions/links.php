<?php
/*
  $Id: links.php,v 1.00 2003/10/03 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

////
// Construct a path to the link
// TABLES: links_to_link_categories
  function tep_get_link_path($links_id) {
    $lPath = '';

    $category_query = tep_db_query("select l2c.link_categories_id from " . TABLE_LINKS . " l left join " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2c on l.links_id = l2c.links_id where l.links_id = '" . (int)$links_id . "' limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $lPath .= $category['link_categories_id'];
    }

    return $lPath;
  }

  function tep_get_parent_category($catID = '0') {
    global $languages_id;

    $categories_query = tep_db_query("select c.parent_id from " . TABLE_LINK_CATEGORIES . " c left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " cd on c.link_categories_id = cd.link_categories_id where c.link_categories_id = '" . $catID . "' and cd.language_id = '" . (int)$languages_id . "' LIMIT 1");

    if (tep_db_num_rows($categories_query) < 1)
     return 0;
    $categories = tep_db_fetch_array($categories_query);
    return $categories['parent_id'];
  }

  function tep_get_sublink_categories($parent_id = '0', $exclude = '', $category_subcats = '') {
    global $languages_id;

    $category_subcats = array();

    $categories_query = tep_db_query("select c.link_categories_id, cd.link_categories_name, c.parent_id from " . TABLE_LINK_CATEGORIES . " c left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " cd on c.link_categories_id = cd.link_categories_id where cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.link_categories_sort_order, cd.link_categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['link_categories_id'])
      {
        $category_subcats[$categories['link_categories_id']] =  $categories['link_categories_name'];
      }
    }

    return $category_subcats;
  }

////
// The HTML image wrapper function
  function tep_links_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    // VJ begin maintain image proportion
    $calculate_image_proportion = 'true';

    if( ($calculate_image_proportion == 'true') && (!empty($width) && !empty($height))) {
      if ($image_size = @getimagesize($src)) {
        $image_width = $image_size[0];
        $image_height = $image_size[1];

        if (($image_width != 1) && ($image_height != 1)) {
          $whfactor = $image_width/$image_height;
          $hwfactor = $image_height/$image_width;

          if ( !($image_width > $width) && !($image_height > $height)) {
            $width = $image_width;
            $height = $image_height;
          } elseif ( ($image_width > $width) && !($image_height > $height)) {
            $height = $width * $hwfactor;
          } elseif ( !($image_width > $width) && ($image_height > $height)) {
            $width = $height * $whfactor;
          } elseif ( ($image_width > $width) && ($image_height > $height)) {
            if ($image_width > $image_height) {
              $height = $width * $hwfactor;
            } else {
              $width = $height * $whfactor;
            }
          }
        }
      }
    }
    //VJ end maintain image proportion

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// Return the links url, based on whether click count is turned on/off
  function tep_get_links_url($identifier) {
    $links_query = tep_db_query("select links_id, links_url from " . TABLE_LINKS . " where links_id = '" . (int)$identifier . "'");

    $link = tep_db_fetch_array($links_query);

    if (ENABLE_LINKS_COUNT == 'True') {
      if (ENABLE_SPIDER_FRIENDLY_LINKS == 'True') {
        $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
        $spider_flag = false;

        if (tep_not_null($user_agent)) {
          $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

          for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
            if (tep_not_null($spiders[$i])) {
              if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
                $spider_flag = true;
                break;
              }
            }
          }
        }

        if ($spider_flag == true) {
          $links_string = $link['links_url'];
        } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&goto=' . $link['links_id']);
        }
      } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&goto=' . $link['links_id']);
      }
    } else {
      $links_string = $link['links_url'];
    }

    return $links_string;
  }

////
// Update the links click statistics
  function tep_update_links_click_count($links_id) {
    tep_db_query("update " . TABLE_LINKS . " set links_clicked = links_clicked + 1 where links_id = '" . (int)$links_id . "'");
  }

function GetLinksFileArray($path) //use curl if possible to read in site information
{
  $lines = array();

  if (function_exists('curl_init'))
  {
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $path);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    $lines = explode("\n", $file_contents);
  }
  else
  {
    $fd = fopen ($path, "r");
    while (!feof ($fd))
    {
      $buffer = fgets($fd, 4096);
      $lines[] = $buffer;
    }
    fclose ($fd);
  }
  return $lines;
}

function CheckSiteData($lines)
{
  $found = 0;
  $phases = explode(",", LINKS_CHECK_PHRASE);

  foreach ($lines as $line)
  {
    $page_line = trim($line);

    for ($i = 0; $i < count($phases); ++$i)
    {
      if (@preg_match("/".$phases[$i]."/i", $page_line))
      {
        $found = 1;
        break;
      }
    }
    if ($found)
      break;
  }
  return $found;
}
////
// Search the given page for the given phase(s)
function CheckURL($url)
{
  $lines = GetLinksFileArray($url);

  if (tep_not_null($lines))
    return CheckSiteData($lines);

  return 0;
}

 function tep_get_child_categories($parentID) {
    global $languages_id;
    $categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name, lcd.link_categories_description, lc.link_categories_image, lc.parent_id from " . TABLE_LINK_CATEGORIES . " lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lc.link_categories_id = lcd.link_categories_id where lc.link_categories_status = '1' and lc.parent_id = '" . $parentID  . "' and lcd.language_id = '" . (int)$languages_id . "' order by lc.link_categories_sort_order, lcd.link_categories_name");
    return $categories_query;
  }

  ////
// Count how many links exist in a category
// TABLES: links, links_to_link_categories, link_categories
  function tep_links_in_category_count($categories_id, $include_deactivated = false) {
    $links_count = 0;

    if ($include_deactivated) {
      $links_query = tep_db_query("select count(*) as total from " . TABLE_LINKS . " l left join " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2c on l.links_id = l2c.links_id where l2c.link_categories_id = '" . (int)$categories_id . "'");
    } else {
      $links_query = tep_db_query("select count(*) as total from " . TABLE_LINKS . " l left join " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2c on l.links_id = l2c.links_id where l.links_status = '2' and l2c.link_categories_id = '" . (int)$categories_id . "'");
    }

    $links = tep_db_fetch_array($links_query);
    $links_count += $links['total'];

    $childs_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    if (tep_db_num_rows($childs_query)) {
      while ($childs = tep_db_fetch_array($childs_query)) {
        $links_count += tep_links_in_category_count($childs['link_categories_id'], $include_deactivated);
      }
    }
    return $links_count;
  }

////
// Count how many subcategories exist in a category
// TABLES: categories
  function tep_childs_in_link_category($categories_id) {
    $categories_list = array();

    $categories_query = tep_db_query("select categories_id, link_categories_name from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_list[$ctr] = $categories['categories_name'];
      $categories_count += tep_childs_in_link_category($categories['categories_id']);
    }

    return $categories_count;
  }

  function tep_get_link_category_description($link_category_id, $language_id) {
    $link_category_query = tep_db_query("select link_categories_description from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "' and language_id = '" . (int)$language_id . "'");
    $link_category = tep_db_fetch_array($link_category_query);

    return $link_category['link_categories_description'];
  }

  function tep_generate_link_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    if ($from == 'link') {
      $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where links_id = '" . (int)$id . "'");

      while ($categories = tep_db_fetch_array($categories_query)) {
        if ($categories['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = tep_db_query("select ld.link_categories_name, l.parent_id from " . TABLE_LINK_CATEGORIES . " l left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " ld on l.link_categories_id = ld.link_categories_id where l.link_categories_id = '" . (int)$categories['link_categories_id'] . "' and ld.language_id = '" . (int)$languages_id . "'");
          $category = tep_db_fetch_array($category_query);
          $categories_array[$index][] = array('id' => $categories['link_categories_id'], 'text' => $category['link_categories_name']);
          if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_link_category_path($category['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = array_reverse($categories_array[$index]);
        }
        $index++;
      }
    } elseif ($from == 'category') {
      $category_query = tep_db_query("select ld.link_categories_name, l.parent_id from " . TABLE_LINK_CATEGORIES . " l left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " ld on l.link_categories_id = ld.link_categories_id where l.link_categories_id = '" . (int)$id . "' and ld.language_id = '" . (int)$languages_id . "'");
      $category = tep_db_fetch_array($category_query);
      $categories_array[$index][] = array('id' => $id, 'text' => $category['link_categories_name']);
      if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_link_category_path($category['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }
  function tep_output_generated_link_category_path($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = tep_generate_link_category_path($id, $from);
    for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

   function tep_get_category_id($cat_name)
  {
    if ($cat_name == "Top")
      return 0;

    $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_name = '" . $cat_name . "' LIMIT 1 ");
    $categories = tep_db_fetch_array($categories_query);
    return ($categories['link_categories_id']);
  }
?>