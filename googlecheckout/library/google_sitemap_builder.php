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
 * $Id: google_sitemap_builder.php 169 2009-02-17 21:53:06Z ed.davisson $
 * 
 * Generates a Sitemap feed.
 *
 * See: http://www.sitemaps.org/protocol.php
 *
 * @author Ed Davisson (ed.davisson@gmail.com)
 */

require_once(DIR_FS_CATALOG . 'googlecheckout/library/xml/google_xml_builder.php');

class GoogleSitemapBuilder {

  var $priorities = array('always' => "always",
                          'hourly' => "hourly",
                          'daily' => "daily",
                          'weekly' => "weekly",
                          'monthly' => "monthly",
                          'yearly' => "yearly",
                          'never' => "never");

  var $product_priority = 0.5;
  var $product_changefreq;

  var $index_priority = 1.0;
  var $index_changefreq;

  var $xml;

  /**
   * Constructor.
   */
  function GoogleSitemapBuilder() {
    $this->xml = new GoogleXmlBuilder();

    // TODO(eddavisson): Initialize outside of constructor?
    $this->product_changefreq = $this->priorities['weekly'];
    $this->index_changefreq = $this->priorities['daily'];
  }

  /**
   * Adds all information needed to create a Google Sitemap feed .
   */
  function get_xml() {
    $this->xml->Push("urlset",
        array("xmlns" => "http://www.sitemaps.org/schemas/sitemap/0.9"));
    $this->add_urls();

    $this->xml->Pop("urlset");

    return $this->xml->GetXml();
  }

  /**
   * Adds URLs (products) to the XML.
   */
  function add_urls() {
    $this->add_index_url();

    $products_query = $this->get_products_query();
    while ($product = tep_db_fetch_array($products_query)) {
      $this->add_product_url($product);
    }
  }

  function add_index_url() {
    $this->xml->Push('url');

    $this->add_loc(tep_href_link(FILENAME_DEFAULT));
    $this->add_changefreq($this->index_changefreq);
    $this->add_priority($this->index_priority);

    $this->xml->Pop('url');
  }

  /**
   * Adds a single item (product) to the XML.
   */
  function add_product_url($product) {
    if ($product['products_status'] == 1 && $product['products_quantity' >= 1]) {
      $this->xml->Push('url');

      $this->add_product_loc($product);
      $this->add_product_lastmod($product);
      $this->add_changefreq($this->product_changefreq);
      $this->add_priority($this->product_priority);

      $this->xml->Pop('url');
    }
  }

  /**
   * Returns a query over all products.
   *
   * TODO(eddavisson): Include "active" products only?
   */
  function get_products_query() {
    return tep_db_query(
      "select products_id, "
      . "products_quantity, "
      . "products_status, "
      . "products_date_added, "
      . "products_last_modified "
      . "from " . TABLE_PRODUCTS . " ");
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
   * Adds the 'loc' element for a product.
   */
  function add_product_loc($product) {
    $loc = tep_href_link(
        FILENAME_PRODUCT_INFO,
        'products_id=' . $product['products_id']);
    $this->add_loc($loc);
  }

  /**
   * Adds the 'lastmod' element for a product.
   */
  function add_product_lastmod($product) {
    $lastmod = ($product['products_last_modified'] == NULL)
        ? $product['products_date_added'] : $product['products_last_modified'];
    // Include the date only (not the time).
    $lastmod = substr($lastmod, 0, 10);
    $this->add_lastmod($lastmod);
  }

  /**
   * Adds the 'loc' element.
   */
  function add_loc($loc) {
    $this->add_if_not_empty('loc', $loc);
  }

  /**
   * Adds the 'lastmod' element.
   */
  function add_lastmod($lastmod) {
    $this->add_if_not_empty('lastmod', $lastmod);
  }

  /**
   * Adds the 'changefreq' element.
   */
  function add_changefreq($changefreq) {
    $this->add_if_not_empty('changefreq', $changefreq);
  }

  /**
   * Adds the 'priority' element.
   */
  function add_priority($priority) {
    $this->add_if_not_empty('priority', $priority);
  }

}

?>
