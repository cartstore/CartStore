<?php
/*
  $Id: links.php,v 1.00 2003/10/02 Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible
*/

//Check that the default settings have been changed for proper operation
function CheckSettings()
{
  global $messageStack, $language;

  $links_check_query = tep_db_query("select * from configuration_group where configuration_group_title = 'Links' LIMIT 1");
  $cgID = tep_db_fetch_array($links_check_query);
  $links_config_query = tep_db_query("select * from configuration where configuration_group_id = '" . $cgID['configuration_group_id']. "'");
  $reciprocal_required = false;
  $reciprocal_phase = false;
  while ($links_config = tep_db_fetch_array($links_config_query))
  {
    if ($links_config['configuration_key'] == "LINKS_RECIPROCAL_REQUIRED" && $links_config['configuration_value'] == 'True')
     $reciprocal_required = true;

    if ($links_config['configuration_key'] == "LINKS_CHECK_PHRASE" && $links_config['configuration_value'] == 'localhost')
     $reciprocal_phase = true;
  }

  if ($reciprocal_required && $reciprocal_phase)
  {
      $messageStack->add("The reciprocal check phase setting needs to be changed in admin->configuration->Links.");

      //check if the link exchange information has been changed from the default
      $filename = DIR_FS_CATALOG. DIR_WS_LANGUAGES . $language . '/links_submit.php';
      $fp = file($filename);

      for ($idx = 0; $idx < count($fp); ++$idx)
      {
        if (strpos($fp[$idx], "define('LINK_NAME', 'Link Name')") !== FALSE)
        {
          $messageStack->add("Link exchange information needs to be edited in " . $filename);
          break;
        }
      }
  }
}

function tep_get_category_id($cat_name)
{
  if ($cat_name == "Top")
    return 0;

  $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_name = '" . $cat_name . "' LIMIT 1 ");
  $categories = tep_db_fetch_array($categories_query);
  return ($categories['link_categories_id']);
}

function tep_get_link_category_name($link_category_id, $language_id) {
  $link_category_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "' and language_id = '" . (int)$language_id . "'");
  $link_category = tep_db_fetch_array($link_category_query);

  return $link_category['link_categories_name'];
}

function tep_get_link_category_name_from_linksid($links_id, $language_id) {
  $link_category_query = tep_db_query("select ld.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " ld, " . TABLE_LINKS_TO_LINK_CATEGORIES . " ltc where ltc.link_categories_id = ld.link_categories_id and ltc.links_id = '" . (int)$links_id . "' and ld.language_id = '" . (int)$language_id . "'");
  $link_category = tep_db_fetch_array($link_category_query);

  return $link_category['link_categories_name'];
}

function tep_get_link_category_description($link_category_id, $language_id) {
  $link_category_query = tep_db_query("select link_categories_description from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "' and language_id = '" . (int)$language_id . "'");
  $link_category = tep_db_fetch_array($link_category_query);

  return $link_category['link_categories_description'];
}

function tep_remove_link_category($link_category_id) {
  $link_category_image_query = tep_db_query("select link_categories_image from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
  $link_category_image = tep_db_fetch_array($link_category_image_query);

  $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_LINK_CATEGORIES . " where link_categories_image = '" . tep_db_input($link_category_image['link_categories_image']) . "'");
  $duplicate_image = tep_db_fetch_array($duplicate_image_query);

  if ($duplicate_image['total'] < 2) {
    if (file_exists(DIR_FS_CATALOG_IMAGES . $link_category_image['link_categories_image'])) {
      @unlink(DIR_FS_CATALOG_IMAGES . $link_category_image['link_categories_image']);
    }
  }

  tep_db_query("delete from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
  tep_db_query("delete from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "'");
  tep_db_query("delete from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
}

function tep_remove_link($link_id) {
  tep_db_query("delete from " . TABLE_LINKS . " where links_id = '" . (int)$link_id . "'");
  tep_db_query("delete from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where links_id = '" . (int)$link_id . "'");
  tep_db_query("delete from " . TABLE_LINKS_DESCRIPTION . " where links_id = '" . (int)$link_id . "'");
}

// clone of tep_info_image() sans file_exists (which doesn't work on remote files)
function tep_link_info_image($image, $alt, $width = '', $height = '') {
  if (tep_not_null($image)) {
    $image = tep_image($image, $alt, $width, $height);
  } else {
    $image = TEXT_IMAGE_NONEXISTENT;
  }

  return $image;
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
function CheckURL($url, $links_id)
{
  $lines = GetLinksFileArray($url);

  if (tep_not_null($lines))
  {
    $found = CheckSiteData($lines);

    if ($found == true)
    {
      $link_check_status_text = TEXT_INFO_LINK_CHECK_FOUND;

      $links_check_query = tep_db_query("SELECT links_id, date_last_checked, link_found FROM " .  TABLE_LINKS_CHECK . " where links_id = " . (int)$links_id );
      if (tep_db_num_rows($links_check_query) > 0)
        tep_db_query("update " . TABLE_LINKS_CHECK . " set link_found = '" . (int)$found  . "', date_last_checked = now() where links_id = '" . (int)$links_id  . "'");
      else
        tep_db_query("insert into " . TABLE_LINKS_CHECK . " (links_id, link_found, date_last_checked) values ('" . (int)$links_id . "', '" . (int)$found . "',  now()) ");
    }
    else
      $link_check_status_text = TEXT_INFO_LINK_CHECK_NOT_FOUND;
  } else
    $link_check_status_text = TEXT_INFO_LINK_CHECK_ERROR;

  return $link_check_status_text;
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

function tep_get_links_title($link_id, $language_id = 0) {
  global $languages_id;

  if ($language_id == 0) $language_id = $languages_id;
  $link_query = tep_db_query("select links_title from " . TABLE_LINKS_DESCRIPTION . " where links_id = '" . (int)$link_id . "' and language_id = '" . (int)$language_id . "'");
  $link = tep_db_fetch_array($link_query);

  return $link['links_title'];
}

function tep_get_links_description($link_id, $language_id) {
  $link_query = tep_db_query("select links_description from " . TABLE_LINKS_DESCRIPTION . " where links_id = '" . (int)$link_id . "' and language_id = '" . (int)$language_id . "'");
  $link = tep_db_fetch_array($link_query);

  return $link['links_description'];
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

function tep_get_link_path($current_category_id = '') {
  global $lPath_array;

  if ($current_category_id == '') {
    $lPath_new = implode('_', $lPath_array);
  } else {
    if (sizeof($lPath_array) == 0) {
      $lPath_new = $current_category_id;
    } else {
      $lPath_new = '';
      $last_category_query = tep_db_query("select parent_id from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$lPath_array[(sizeof($lPath_array)-1)] . "'");
      $last_category = tep_db_fetch_array($last_category_query);

      $current_category_query = tep_db_query("select parent_id from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$current_category_id . "'");
      $current_category = tep_db_fetch_array($current_category_query);

      if ($last_category['parent_id'] == $current_category['parent_id']) {
        for ($i = 0, $n = sizeof($lPath_array) - 1; $i < $n; $i++) {
          $lPath_new .= '_' . $lPath_array[$i];
        }
      } else {
        for ($i = 0, $n = sizeof($lPath_array); $i < $n; $i++) {
          $lPath_new .= '_' . $lPath_array[$i];
        }
      }

      $lPath_new .= '_' . $current_category_id;

      if (substr($lPath_new, 0, 1) == '_') {
        $lPath_new = substr($lPath_new, 1);
      }
    }
  }

  return 'lPath=' . $lPath_new;
}

function tep_get_link_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
  global $languages_id;

  if (!is_array($category_tree_array)) $category_tree_array = array();
  if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

  if ($include_itself) {
    $category_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and link_categories_id = '" . (int)$parent_id . "'");
    $category = tep_db_fetch_array($category_query);
    $category_tree_array[] = array('id' => $parent_id, 'text' => $category['link_categories_name']);
  }

  $categories_query = tep_db_query("select c.link_categories_id, cd.link_categories_name, c.parent_id from " . TABLE_LINK_CATEGORIES . " c left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " cd on c.link_categories_id = cd.link_categories_id where cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.link_categories_sort_order, cd.link_categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
    if ($exclude != $categories['link_categories_id']) $category_tree_array[] = array('id' => $categories['link_categories_id'], 'text' => $spacing . $categories['link_categories_name']);
    $category_tree_array = tep_get_link_category_tree($categories['link_categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
  }

  return $category_tree_array;
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
// TABLES: link_categories
function tep_childs_in_link_category_count($categories_id) {
  $categories_count = 0;

  $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_count++;
    $categories_count += tep_childs_in_link_category_count($categories['link_categories_id']);
  }
  return $categories_count;
}

////
// Sets the status of a link
function tep_set_link_categories_status($cat_id, $status) {
    return tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_status = '" . (int)$status . "', link_categories_last_modified = now() where link_categories_id = '" . (int)$cat_id . "'");
  if ($status == '10' || $status == '11') {
    $status -= 10;   //needed to distinguish between links status
    return tep_db_query("update " . TABLE_LINK_CATEGORIES . " set link_categories_status = '" . (int)$status . "', link_categories_last_modified = now() where link_categories_id = '" . (int)$cat_id . "'");
  } else {
    return -1;
  }
}
////
// Sets the status of a link
function tep_set_link_status($links_id, $status) {
  if ($status > '0') {
    return tep_db_query("update " . TABLE_LINKS . " set links_status = '" . (int)$status . "', links_last_modified = now() where links_id = '" . (int)$links_id . "'");
  } else {
    return -1;
  }
}

function tep_get_generated_link_category_path_ids($id, $from = 'category') {
  $calculated_category_path_string = '';
  $calculated_category_path = tep_generate_link_category_path($id, $from);
  for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
    for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
      $calculated_category_path_string .= $calculated_category_path[$i][$j]['id'] . '_';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -1) . '<br>';
  }
  $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

  if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

  return $calculated_category_path_string;
}

function tep_get_generated_link_category_path_names($id, $from = 'category') {
 global $languages_id;

 $path = '<br>';
 $ids = tep_get_generated_link_category_path_ids($id);
 $parts = explode("_", $ids);
 $parts = array_reverse($parts);

 for ($i = 0; $i < count($parts); ++$i)
 {
   $path .= tep_get_link_category_name($parts[$i], $languages_id);
   if ($i < count($parts) - 1)
    $path .= ' => ';
 }

 return $path;
}
?>
