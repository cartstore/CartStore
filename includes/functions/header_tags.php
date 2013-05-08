<?php
// /catalog/includes/functions/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.0

////
// Get products_head_title_tag
// TABLES: products_description
function tep_get_header_tag_products_title($product_id) {
  global $languages_id, $_GET; 

  $product_header_tags = tep_db_query("select products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$_GET['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return clean_html_comments($product_header_tags_values['products_head_title_tag']);
  }


////
// Get products_head_keywords_tag
// TABLES: products_description
function tep_get_header_tag_products_keywords($product_id) {
  global $languages_id, $_GET; 

  $product_header_tags = tep_db_query("select products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$_GET['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return $product_header_tags_values['products_head_keywords_tag'];
  }


////
// Get products_head_desc_tag
// TABLES: products_description
function tep_get_header_tag_products_desc($product_id) {
  global $languages_id, $_GET; 

  $product_header_tags = tep_db_query("select products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and products_id = '" . (int)$_GET['products_id'] . "'");
  $product_header_tags_values = tep_db_fetch_array($product_header_tags);

  return $product_header_tags_values['products_head_desc_tag'];
  }
  
////
//used to add new pages without adding a lot of code
function tep_header_tag_page($htta, $htta_string, $htda, $htda_string, $htka, $htka_string) {
  global $tags_array;

  if ($htda=='1') {
    $tags_array['desc']= $htda_string . ' ' . HEAD_DESC_TAG_ALL;
  } else {
    $tags_array['desc']= $htda_string;
  }

  if ($htka=='1') {
    $tags_array['keywords']= $htka_string . ' ' . HEAD_KEY_TAG_ALL;
  } else {
    $tags_array['keywords']= $htka_string ;
  }
  
  if ($htta=='1') {
    $tags_array['title']= $htta_string . ' ' . HEAD_TITLE_TAG_ALL;
  } else {
    $tags_array['title']= $htta_string;
  }

  return $tags_array;
}
?>
