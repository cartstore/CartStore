<?php

/* -----------------05.08.2006 22:49-----------------

  Addon for Brian Lowe's (<blowe@wpcusrgrp.org>)
  categories_description

  Some Functions to get Category-Informations

  File:
  \includes\functions\categories_description.php

--------------------------------------------------*/


  // Returns a categorie's header
  //
  // TABLE: categories_description
  // IN:    category_id: The ID of the Category for which we are searching the Header
  //        language_id: In which Language do we need the Description?
  // Out:   The Header of the Category
  //
 if (!function_exists("tep_get_category_heading_title") ){
  function tep_get_category_heading_title($category_id, $language_id = '') {
    global $languages_id;

    // If no language is defined, the description in the
    // default language is returned
    if (!is_numeric($language_id)) $language_id = $languages_id;

    $category_query = tep_db_query("select categories_htc_title_tag " .
                                   "  from " . TABLE_CATEGORIES_DESCRIPTION .
                                   " where categories_id = '" . (int)$category_id . "'" .
                                   "   and language_id = '" .   (int)$language_id . "'");
    $categories_description = tep_db_fetch_array($category_query);

    return trim($categories_description['categories_htc_title_tag']);
  }
 }
  // Returns a categorie's description
  //
  // TABLE: categories_description
  // IN:    category_id: The ID of the Category for which we are searching the Description
  //        language_id: In which Language do we need the Description?
  // Out:   The Description of the Category
  //
 if (!function_exists("tep_get_category_description")){
  function tep_get_category_description($category_id, $language_id = '') {
    global $languages_id;

    // If no language is defined, the description in the
    // default language is returned
    if (!is_numeric($language_id)) $language_id = $languages_id;

    $category_query = tep_db_query("select categories_htc_description " .
                                   "  from " . TABLE_CATEGORIES_DESCRIPTION .
                                   " where categories_id = '" . (int)$category_id . "'" .
                                   "   and language_id = '" .   (int)$language_id . "'");
    $categories_description = tep_db_fetch_array($category_query);

    return trim($categories_description['categories_htc_description']);
  }
 }

?>
