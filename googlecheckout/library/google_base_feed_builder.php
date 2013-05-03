<?php
/*
  Copyright (C) 2008 Google Inc.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Google Checkout v1.5.0
 * $Id: google_base_feed_builder.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Generates a feed (RSS 2.0) compatible with Google Base for products.
 *
 * See http://base.google.com/support/bin/answer.py?answer=73932
 *
 * osCommerce MySQL tables of interest:
 *   categories
 *   categories_description
 *   products
 *   products_descriptions
 *   products_to_categories
 *   manufacturers
 *
 * Mapping of XML elements to table->columns:
 *
 * Required:
 *   description: products_description->products_description
 *   id: products->products_id
 *   link: products_description->products_url
 *   price: products->products_price
 *   title: products_description->products_name
 *
 * Recommended:
 *   brand: manufacturers->manufacturers_name
 *   condition: (not supported)
 *   image_link: products->products_image
 *   isbn: (not supported)
 *   mpn: (not supported)
 *   upc: (not supported)
 *   weight: products->products_weight
 *
 * Optional:
 *   color:
 *   expiration_date
 *   height: (not supported)
 *   length: (not supported)
 *   model_number: products->products_model
 *   payment_accepted: TODO(eddavisson)
 *   payment_notes: TODO(eddavisson)
 *   price_type: (not supported)
 *   product_type: categories_description->categories_name (calculated recursively)
 *   quantity: products->products_quantity
 *   shipping: TODO(eddavisson)
 *   size: (not supported)
 *   tax: TODO(eddavisson)
 *   width: (not supported)
 *   year: (not supported)
 *
 * TODO(eddavisson): How many more of these fields can we include?
 *
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

require_once(DIR_FS_CATALOG . 'googlecheckout/library/xml/google_xml_builder.php');

class GoogleBaseFeedBuilder {

  var $xml;
  var $languages_id;
  var $categories_tree;

  /**
   * Constructor.
   */
  function GoogleBaseFeedBuilder($languages_id) {
    $this->xml = new GoogleXmlBuilder();
    $this->languages_id = $languages_id;
    $this->categories_tree = $this->build_categories_tree();
  }

  /**
   * Adds all information needed to create a Google Base feed (RSS 2.0).
   */
  function get_xml() {
    $this->xml->Push("rss", array("version" => "2.0",
                                  "xmlns:g" => "http://base.google.com/ns/1.0"));
    $this->xml->Push("channel");

    $this->add_feed_info();
    $this->add_items();

    $this->xml->Pop("channel");
    $this->xml->Pop("rss");

    return $this->xml->GetXml();
  }

  /**
   * Adds feed info (title, link, description) to the XML.
   */
  function add_feed_info() {
    $title_query = tep_db_query(
        "select configuration_value "
        . "from configuration "
        . "where configuration_key = \"STORE_NAME\"");
    $row = tep_db_fetch_array($title_query);
    $title = $row['configuration_value'];

    $this->xml->Element('title', $title);
    $this->xml->Element('link', HTTP_SERVER . DIR_WS_HTTP_CATALOG);
    // osCommerce doesn't store a description of the store.
    $this->xml->Element('description', $title);
  }

  /**
   * Adds items (products) to the XML.
   */
  function add_items() {
    $products_query = $this->get_products_query();
    while ($product = tep_db_fetch_array($products_query)) {
      $this->add_item($product);
    }
  }

  /**
   * Adds a single item (product) to the XML.
   */
  function add_item($product) {
    $this->xml->Push('item');

    // Required, global namespace.
    $this->add_title($product);
    $this->add_link($product);
    $this->add_description($product);

    // Required, Google namespace.
    $this->add_brand($product);
    $this->add_id($product);
    $this->add_price($product);

    // Optional.
    $this->add_image_link($product);
    $this->add_weight($product);
    $this->add_model_number($product);
    $this->add_payment_notes($product);
    $this->add_product_type($product);
    $this->add_quantity($product);

    $this->xml->Pop('item');
  }

  /**
   * Builds the categories tree.
   */
  function build_categories_tree() {
    $categories_tree = array();
    $categories_query = $this->get_categories_query();
    while ($category = tep_db_fetch_array($categories_query)) {
      $categories_tree[$category['categories_id']] = array(
          'name' => $category['categories_name'],
          'parent_id' => $category['parent_id']);
    }
    return $categories_tree;
  }

  /**
   * Returns a query containing the information necessary
   * to build the categories tree.
   */
  function get_categories_query() {
    return tep_db_query(
      "select c.categories_id, "
      . "c.parent_id, "
      . "cd.categories_name "
      . "from " . TABLE_CATEGORIES . " c, "
      . TABLE_CATEGORIES_DESCRIPTION . " cd "
      . "where c.categories_id = cd.categories_id "
      . "and cd.language_id = " . (int) $this->languages_id . " ");
  }

  /**
   * Traverses the categories tree to construct an array of the
   * categories containing the provided category_id.
   */
  function create_category_array($category_id, &$array) {
    $name = $this->categories_tree[$category_id]['name'];
    array_push($array, $name);
    $parent_id = $this->categories_tree[$category_id]['parent_id'];
    if ($parent_id == 0) {
      $array = array_reverse($array);
      return;
    } else {
      $this->create_category_array($parent_id, $array);
    }
  }

  /**
   * Returns a query over all products containing the columns
   * needed to generate the field.
   */
  function get_products_query() {
    return tep_db_query(
      "select p.products_id, "
      . "p.products_price, "
      . "p.products_image, "
      . "p.products_weight, "
      . "p.products_model, "
      . "p.products_quantity, "
      . "pd.products_id, "
      . "pd.products_description, "
      . "pd.products_url, "
      . "pd.products_name, "
      . "m.manufacturers_name, "
      . "ptc.categories_id "
      . "from " . TABLE_PRODUCTS . " p, "
      . TABLE_PRODUCTS_DESCRIPTION . " pd, "
      . TABLE_MANUFACTURERS . " m, "
      . TABLE_PRODUCTS_TO_CATEGORIES . " ptc "
      . "where pd.products_id = p.products_id "
      . "and m.manufacturers_id = p.manufacturers_id "
      . "and ptc.products_id = p.products_id "
      . "and pd.language_id = " . (int) $this->languages_id . " ");
  }

  /**
   * Adds an element to the XML if content is non-empty.
   */
  function add_if_not_empty($element, $content) {
    if (!empty($content)) {
      $this->xml->Element($element, $content);
    }
  }

  /**
   * Adds the 'title' element.
   */
  function add_title($product) {
    $title = $product['products_name'];
    $this->add_if_not_empty('title', $title);
  }

  /**
   * Adds the 'link' element.
   */
  function add_link($product) {
    $link = tep_href_link(
        FILENAME_PRODUCT_INFO,
        'products_id=' . $product['products_id']);
    $this->add_if_not_empty('link', $link);
  }

  /**
   * Adds the 'brand' element.
   */
  function add_brand($product) {
    $brand = $product['manufacturers_name'];
    $this->add_if_not_empty('g:brand', $brand);
  }

  /**
   * Adds the 'description' element.
   *
   * As of 1/13/09, HTML is only supported in individually
   * posted items.
   *
   * See http://base.google.com/support/bin/answer.py?answer=46116.
   */
  function add_description($product) {
    $description = strip_tags($product['products_description']);
    $this->add_if_not_empty('description', $description);
  }

  /**
   * Adds the 'id' element.
   */
  function add_id($product) {
    $id = $product['products_id'];
    $this->add_if_not_empty('g:id', $id);
  }

  /**
   * Adds the 'price' element.
   */
  function add_price($product) {
    $price = round($product['products_price'], 2);
    $this->add_if_not_empty('g:price', $price);
  }

  /**
   * Adds the 'image_link' element.
   */
  function add_image_link($product) {
    $image_link = HTTP_SERVER . DIR_WS_HTTP_CATALOG
        . DIR_WS_IMAGES . $product['products_image'];
    $this->add_if_not_empty('g:image_link', $image_link);
  }

  /**
   * Adds the 'weight' element.
   */
  function add_weight($product) {
    $weight = $product['products_weight'];
    $this->add_if_not_empty('g:weight', $weight);
  }

  /**
   * Adds the 'model_number' element.
   */
  function add_model_number($product) {
    $model_number = $product['products_model'];
    $this->add_if_not_empty('g:model_number', $model_number);
  }

  /**
   * Adds the 'payment_notes' element.
   */
  function add_payment_notes($product) {
    // TODO(eddavisson): What should we actually say here?
    $payment_notes = "Google Checkout";
    $this->add_if_not_empty('g:payment_notes', $payment_notes);
  }

  function add_product_type($product) {
    $category_id = $product['categories_id'];
    $category_array = array();
    $this->create_category_array($category_id, $category_array);

    $product_type = "";
    $length = count($category_array);
    for ($i = 0; $i < $length; $i++) {
      $product_type .= $category_array[$i];
      if ($i != $length - 1) {
        $product_type .= " > ";
      }
    }

    $this->add_if_not_empty('g:product_type', $product_type);
  }

  /**
   * Adds the 'quantity' element.
   */
  function add_quantity($product) {
    $quantity = $product['products_quantity'];
    $this->add_if_not_empty('g:quantity', $quantity);
  }
}

?>
