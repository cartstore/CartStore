<?php
  require(DIR_WS_LANGUAGES . $language . '/' . 'header_tags.php');
  $tags_array = array();

  switch (true) {

      case (strstr($_SERVER['PHP_SELF'], 'newsdesk_index.php') or strstr($PHP_SELF, 'newsdesk_index.php') or strstr($_SERVER['PHP_SELF'], 'newsdesk_info.php') or strstr($PHP_SELF, 'newsdesk_info.php')):
          $the_newsdesk_query = tep_db_query("

    SELECT

      categories_name

    FROM

      " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . "

    WHERE

      categories_id = '" . $_GET['newsPath'] . "'

    AND language_id='" . $languages_id . "'");
          $the_newsdesk_category = tep_db_fetch_array($the_newsdesk_query);
          $the_title = $the_newsdesk_category['categories_name'] . ' - ' . HEAD_TITLE_TAG_ALL;
          if ($_GET['newsdesk_id'] != '') {
              $the_newsdesk_id_query = tep_db_query("

      SELECT

        newsdesk_article_name

      FROM

        " . TABLE_NEWSDESK_DESCRIPTION . "

      WHERE

        newsdesk_id = '" . $_GET['newsdesk_id'] . "'

      AND language_id='" . $languages_id . "'");
              $the_newsdesk_article = tep_db_fetch_array($the_newsdesk_id_query);
              $the_title = $the_newsdesk_article['newsdesk_article_name'] . ' ' . $the_title;
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_ALLPRODS) or strstr($PHP_SELF, FILENAME_ALLPRODS)):
          $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
          $the_category = tep_db_fetch_array($the_category_query);
          $the_manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $_GET['manufacturers_id'] . "'");
          $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);
          if (HTDA_ALLPRODS_ON == '1') {
              $tags_array['desc'] = HEAD_DESC_TAG_ALLPRODS . ' ' . HEAD_DESC_TAG_ALL;
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_ALLPRODS;
          }
          if (HTKA_ALLPRODS_ON == '1') {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_ALLPRODS;
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALLPRODS;
          }
          if (HTTA_ALLPRODS_ON == '1') {
              $tags_array['title'] = HEAD_TITLE_TAG_ALLPRODS . ' ' . HEAD_TITLE_TAG_ALL . " " . $the_category['categories_name'] . $the_manufacturers['manufacturers_name'];
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_ALLPRODS;
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_ALLPRODS) or strstr($PHP_SELF, FILENAME_ALLPRODS)):
          $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
          $the_category = tep_db_fetch_array($the_category_query);
          $the_manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $_GET['manufacturers_id'] . "'");
          $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);
          if (HTDA_PRODUCTS_ALL_ON == '1') {
              $tags_array['desc'] = HEAD_DESC_TAG_PRODUCTS_ALL . ' ' . HEAD_DESC_TAG_ALL;
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_PRODUCTS_ALL;
          }
          if (HTKA_PRODUCTS_ALL_ON == '1') {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_PRODUCTS_ALL;
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_PRODUCTS_ALL;
          }
          if (HTTA_ALLPRODS_ON == '1') {
              $tags_array['title'] = HEAD_TITLE_TAG_PRODUCTS_ALL . ' ' . HEAD_TITLE_TAG_ALL . " " . $the_category['categories_name'] . $the_manufacturers['manufacturers_name'];
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_PRODUCTS_ALL;
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_DEFAULT) or strstr($PHP_SELF, FILENAME_DEFAULT)):
          $showCatTags = false;
          if ($category_depth == 'nested' || $category_depth == 'products') {
              $the_category_query = tep_db_query("select categories_name as name, categories_htc_title_tag as htc_title_tag, categories_htc_desc_tag as htc_desc_tag, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
              $showCatTags = true;
          } elseif (isset($_GET['manufacturers_id'])) {
              $the_category_query = tep_db_query("select m.manufacturers_name as name, mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
              $showCatTags = true;
          } else {
              $the_category_query = tep_db_query("select categories_name as name, categories_htc_title_tag as htc_title_tag, categories_htc_desc_tag as htc_desc_tag, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
          }
          $the_category = tep_db_fetch_array($the_category_query);
          if (HTDA_DEFAULT_ON == '1') {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['desc'] = $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_DEFAULT . ' ' . HEAD_DESC_TAG_ALL;
                  } else {
                      $tags_array['desc'] = $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
                  }
              } else {
                  $tags_array['desc'] = HEAD_DESC_TAG_DEFAULT . ' ' . HEAD_DESC_TAG_ALL;
              }
          } else {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['desc'] = $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_DEFAULT;
                  } else {
                      $tags_array['desc'] = $the_category['htc_desc_tag'];
                  }
              } else {
                  $tags_array['desc'] = HEAD_DESC_TAG_DEFAULT;
              }
          }
          if (HTKA_DEFAULT_ON == '1') {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['keywords'] = $the_category['htc_keywords_tag'] . ', ' . HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_DEFAULT;
                  } else {
                      $tags_array['keywords'] = $the_category['htc_keywords_tag'] . ', ' . HEAD_KEY_TAG_DEFAULT;
                  }
              } else {
                  $tags_array['keywords'] = HEAD_KEY_TAG_ALL . '' . HEAD_KEY_TAG_DEFAULT;
              }
          } else {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['keywords'] = $the_category['htc_keywords_tag'] . ', ' . HEAD_KEY_TAG_DEFAULT;
                  } else {
                      $tags_array['keywords'] = $the_category['htc_keywords_tag'];
                  }
              } else {
                  $tags_array['keywords'] = HEAD_KEY_TAG_DEFAULT;
              }
          }
          if (HTTA_DEFAULT_ON == '1') {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['title'] = $the_category['htc_title_tag'] . ' ' . HEAD_TITLE_TAG_DEFAULT . " " . $the_category['name'] . '  ' . HEAD_TITLE_TAG_ALL;
                  } else {
                      $tags_array['title'] = $the_category['htc_title_tag'] . HEAD_TITLE_TAG_ALL;
                  }
              } else {
                  $tags_array['title'] = HEAD_TITLE_TAG_DEFAULT . " " . $the_category['name'] . $the_category['htc_title_tag'] . '  ' . HEAD_TITLE_TAG_ALL;
              }
          } else {
              if ($showCatTags == true) {
                  if (HTTA_CAT_DEFAULT_ON == '1') {
                      $tags_array['title'] = $the_category['htc_title_tag'] . ' ' . HEAD_TITLE_TAG_DEFAULT;
                  } else {
                      $tags_array['title'] = $the_category['htc_title_tag'];
                  }
              } else {
                  $tags_array['title'] = HEAD_TITLE_TAG_DEFAULT;
              }
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_PRODUCT_INFO) or strstr($PHP_SELF, FILENAME_PRODUCT_INFO)):

          $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $_GET['products_id'] . "' and pd.products_id = '" . $_GET['products_id'] . "'" . " and pd.language_id ='" . $languages_id . "'");
          $the_product_info = tep_db_fetch_array($the_product_info_query);
          if (empty($the_product_info['products_head_desc_tag'])) {
              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {
                  $tags_array['desc'] = HEAD_DESC_TAG_PRODUCT_INFO;
              }
              if (HTDA_PRODUCT_INFO_ON == '1' || empty($tags_array['desc'])) {
                  $tags_array['desc'] .= HEAD_DESC_TAG_ALL;
              }
          } else {
              $tags_array['desc'] = $the_product_info['products_head_desc_tag'];
              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {
                  $tags_array['desc'] .= ' ' . HEAD_DESC_TAG_PRODUCT_INFO;
              }
              if (HTDA_PRODUCT_INFO_ON == '1') {
                  $tags_array['desc'] .= ' ' . HEAD_DESC_TAG_ALL;
              }
          }
          if (empty($the_product_info['products_head_keywords_tag'])) {
              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {
                  $tags_array['keywords'] = HEAD_KEY_TAG_PRODUCT_INFO;
              }
              if (HTKA_PRODUCT_INFO_ON == '1' || empty($tags_array['keywords'])) {
                  $tags_array['keywords'] .= HEAD_KEY_TAG_ALL;
              }
          } else {
              $tags_array['keywords'] = $the_product_info['products_head_keywords_tag'];
              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {
                  $tags_array['keywords'] .= ' ' . HEAD_KEY_TAG_PRODUCT_INFO;
              }
              if (HTKA_PRODUCT_INFO_ON == '1') {
                  $tags_array['keywords'] .= ' ' . HEAD_KEY_TAG_ALL;
              }
          }
          if (empty($the_product_info['products_head_title_tag'])) {

              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {


                  $tags_array['title'] = HEAD_TITLE_TAG_PRODUCT_INFO;
              }
              if (HTTA_PRODUCT_INFO_ON == '1' || empty($tags_array['title'])) {


                  $tags_array['title'] .= HEAD_TITLE_TAG_ALL;
              }
          } else {
              $tags_array['title'] = clean_html_comments($the_product_info['products_head_title_tag']);
              if (HTTA_CAT_PRODUCT_DEFAULT_ON == '1') {
                  $tags_array['title'] .= ' ' . HEAD_TITLE_TAG_PRODUCT_INFO;
              }
              if (HTTA_PRODUCT_INFO_ON == '1') {
                  $tags_array['title'] .= ' ' . HEAD_TITLE_TAG_ALL;
              }
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_PRODUCTS_NEW) or strstr($PHP_SELF, FILENAME_PRODUCTS_NEW)):
          if (HEAD_DESC_TAG_WHATS_NEW != '') {
              if (HTDA_WHATS_NEW_ON == '1') {
                  $tags_array['desc'] = HEAD_DESC_TAG_WHATS_NEW . ' ' . HEAD_DESC_TAG_ALL;
              } else {
                  $tags_array['desc'] = HEAD_DESC_TAG_WHATS_NEW;
              }
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_ALL;
          }
          if (HEAD_KEY_TAG_WHATS_NEW != '') {
              if (HTKA_WHATS_NEW_ON == '1') {
                  $tags_array['keywords'] = HEAD_KEY_TAG_WHATS_NEW . ' ' . HEAD_KEY_TAG_ALL;
              } else {
                  $tags_array['keywords'] = HEAD_KEY_TAG_WHATS_NEW;
              }
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALL;
          }
          if (HEAD_TITLE_TAG_WHATS_NEW != '') {
              if (HTTA_WHATS_NEW_ON == '1') {
                  $tags_array['title'] = HEAD_TITLE_TAG_WHATS_NEW . ' ' . HEAD_TITLE_TAG_ALL;
              } else {
                  $tags_array['title'] = HEAD_TITLE_TAG_WHATS_NEW;
              }
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_ALL;
          }
          break;

      case (strstr($_SERVER['PHP_SELF'], FILENAME_SPECIALS) or strstr($PHP_SELF, FILENAME_SPECIALS)):
          if (HEAD_DESC_TAG_SPECIALS != '') {
              if (HTDA_SPECIALS_ON == '1') {
                  $tags_array['desc'] = HEAD_DESC_TAG_SPECIALS . ' ' . HEAD_DESC_TAG_ALL;
              } else {
                  $tags_array['desc'] = HEAD_DESC_TAG_SPECIALS;
              }
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_ALL;
          }
          if (HEAD_KEY_TAG_SPECIALS == '') {

              $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
              $row = 0;
              $the_specials = '';
              while ($new_values = tep_db_fetch_array($new)) {
                  $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
              }
              if (HTKA_SPECIALS_ON == '1') {
                  $tags_array['keywords'] = $the_specials . ' ' . HEAD_KEY_TAG_ALL;
              } else {
                  $tags_array['keywords'] = $the_specials;
              }
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_SPECIALS . ' ' . HEAD_KEY_TAG_ALL;
          }
          if (HEAD_TITLE_TAG_SPECIALS != '') {
              if (HTTA_SPECIALS_ON == '1') {
                  $tags_array['title'] = HEAD_TITLE_TAG_SPECIALS . ' ' . HEAD_TITLE_TAG_ALL;
              } else {
                  $tags_array['title'] = HEAD_TITLE_TAG_SPECIALS;
              }
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_ALL;
          }
          break;

      case ((basename($PHP_SELF) == FILENAME_PRODUCT_REVIEWS) or (basename($PHP_SELF) == FILENAME_PRODUCT_REVIEWS_INFO)):
          if (HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO == '') {
              if (HTDA_PRODUCT_REVIEWS_INFO_ON == '1') {
                  $tags_array['desc'] = tep_get_header_tag_products_desc($_GET['reviews_id']) . ' ' . HEAD_DESC_TAG_ALL;
              } else {
                  $tags_array['desc'] = tep_get_header_tag_products_desc($_GET['reviews_id']);
              }
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO;
          }
          if (HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO == '') {
              if (HTKA_PRODUCT_REVIEWS_INFO_ON == '1') {
                  $tags_array['keywords'] = tep_get_header_tag_products_keywords($_GET['reviews_id']) . ' ' . HEAD_KEY_TAG_ALL;
              } else {
                  $tags_array['keywords'] = tep_get_header_tag_products_keywords($_GET['reviews_id']);
              }
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO;
          }
          if (HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO == '') {
              if (HTTA_PRODUCT_REVIEWS_INFO_ON == '1') {
                  $tags_array['title'] = ' Reviews: ' . tep_get_header_tag_products_title($_GET['reviews_id']) . HEAD_TITLE_TAG_ALL;
              } else {
                  $tags_array['title'] = tep_get_header_tag_products_title($_GET['reviews_id']);
              }
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO;
          }
          break;

      case ((basename($PHP_SELF) == FILENAME_PRODUCT_REVIEWS_WRITE)):
          if (HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE == '') {
              if (HTDA_PRODUCT_REVIEWS_WRITE_ON == '1') {
                  $tags_array['desc'] = tep_get_header_tag_products_desc($_GET['reviews_id']) . ' ' . HEAD_DESC_TAG_ALL;
              } else {
                  $tags_array['desc'] = tep_get_header_tag_products_desc($_GET['reviews_id']);
              }
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE;
          }
          if (HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE == '') {
              if (HTKA_PRODUCT_REVIEWS_WRITE_ON == '1') {
                  $tags_array['keywords'] = tep_get_header_tag_products_keywords($_GET['reviews_id']) . ' ' . HEAD_KEY_TAG_ALL;
              } else {
                  $tags_array['keywords'] = tep_get_header_tag_products_keywords($_GET['reviews_id']);
              }
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE;
          }
          if (HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE == '') {
              if (HTTA_PRODUCT_REVIEWS_WRITE_ON == '1') {
                  $tags_array['title'] = ' Reviews: ' . tep_get_header_tag_products_title($_GET['reviews_id']) . HEAD_TITLE_TAG_ALL;
              } else {
                  $tags_array['title'] = tep_get_header_tag_products_title($_GET['reviews_id']);
              }
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE;
          }
          break;

          case(strstr($_SERVER['PHP_SELF'], FILENAME_PRODUCT_REVIEWS_WRITE) or strstr($PHP_SELF, FILENAME_PRODUCT_REVIEWS_WRITE));
          $tags_array = tep_header_tag_page(HTTA_PRODUCT_REVIEWS_WRITE_ON, HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE, HTDA_PRODUCT_REVIEWS_WRITE_ON, HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE, HTKA_PRODUCT_REVIEWS_WRITE_ON, HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE);
          break;

          case(strstr($_SERVER['PHP_SELF'], FILENAME_ARTICLE_INFO) or strstr($PHP_SELF, FILENAME_ARTICLE_INFO));
          $the_article_info_query = tep_db_query("select a.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_title_tag, ad.articles_head_keywords_tag, ad.articles_head_desc_tag, ad.articles_url, a.articles_date_added, a.articles_date_available, a.authors_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . $_GET['articles_id'] . "' and ad.articles_id = '" . $_GET['articles_id'] . "'");
          $the_article_info_query = tep_db_query("select ad.language_id, a.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_title_tag, ad.articles_head_keywords_tag, ad.articles_head_desc_tag, ad.articles_url, a.articles_date_added, a.articles_date_available, a.authors_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . (int)$_GET['articles_id'] . "' and ad.articles_id = '" . (int)$_GET['articles_id'] . "'" . " and ad.language_id ='" . (int)$languages_id . "'");
          $the_article_info = tep_db_fetch_array($the_article_info_query);
          if (empty($the_article_info['articles_head_desc_tag'])) {
              $tags_array['desc'] = NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
          } else {
              if (HTDA_ARTICLE_INFO_ON == '1') {
                  $tags_array['desc'] = $the_article_info['articles_head_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
              } else {
                  $tags_array['desc'] = $the_article_info['articles_head_desc_tag'];
              }
          }
          if (empty($the_article_info['articles_head_keywords_tag'])) {
              $tags_array['keywords'] = NAVBAR_TITLE . ', ' . HEAD_KEY_TAG_ALL;
          } else {
              if (HTKA_ARTICLE_INFO_ON == '1') {
                  $tags_array['keywords'] = $the_article_info['articles_head_keywords_tag'] . ', ' . HEAD_KEY_TAG_ALL;
              } else {
                  $tags_array['keywords'] = $the_article_info['articles_head_keywords_tag'];
              }
          }
          if (empty($the_article_info['articles_head_title_tag'])) {
              $tags_array['title'] = HEAD_TITLE_TAG_ALL . ' - ' . NAVBAR_TITLE;
          } else {
              if (HTTA_ARTICLE_INFO_ON == '1') {
                  $tags_array['title'] = HEAD_TITLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLE_INFO . ' - ' . $topics['topics_name'] . $authors['authors_name'] . ' - ' . clean_html_comments($the_article_info['articles_head_title_tag']);
              } else {
                  $tags_array['title'] = clean_html_comments($the_article_info['articles_head_title_tag']);
              }
          }

      case (strstr($_SERVER['PHP_SELF'], FILENAME_ALLPRODS) or strstr($PHP_SELF, FILENAME_ALLPRODS)):
          $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
          $the_category = tep_db_fetch_array($the_category_query);
          $the_manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $_GET['manufacturers_id'] . "'");
          $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);
          if (HTDA_ALLPRODS_ON == '1') {
              $tags_array['desc'] = HEAD_DESC_TAG_ALLPRODS . ' ' . HEAD_DESC_TAG_ALL;
          } else {
              $tags_array['desc'] = HEAD_DESC_TAG_ALLPRODS;
          }
          if (HTKA_ALLPRODS_ON == '1') {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_ALLPRODS;
          } else {
              $tags_array['keywords'] = HEAD_KEY_TAG_ALLPRODS;
          }
          if (HTTA_ALLPRODS_ON == '1') {
              $tags_array['title'] = HEAD_TITLE_TAG_ALLPRODS . ' ' . HEAD_TITLE_TAG_ALL . " " . $the_category['categories_name'] . $the_manufacturers['manufacturers_name'];
          } else {
              $tags_array['title'] = HEAD_TITLE_TAG_ALLPRODS;
          }
          break;
          break;

      default:
          $tags_array['desc'] = HEAD_DESC_TAG_ALL;
          $tags_array['keywords'] = HEAD_KEY_TAG_ALL;
          $tags_array['title'] = HEAD_TITLE_TAG_ALL;
          break;
  }
  echo '  <title>' . $tags_array['title'] . '</title>' . "\n";
  if ($tags_array['keywords'] == ", ") {
      echo '  <meta name="Keywords" content=" fff" />' . "\n";
  } else {
      echo '  <meta name="Keywords" content="' . $tags_array['keywords'] . '" />' . "\n";
  }
  echo '  <meta name="Description" content="' . $tags_array['desc'] . '" />' . "\n";

  echo '<!-- EOF: Generated Meta Tags -->' . "\n";
?>