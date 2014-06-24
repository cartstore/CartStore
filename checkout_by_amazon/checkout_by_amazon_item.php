<?php
/**
 * @brief Class representing an Item to Checkout by Amazon 
 * @catagory osCommerce Checkout by Amazon Payment Module
 * @author Neil Corkum
 * @author Allison Naaktgeboren
 * @copyright Portions copyright 2007-2008 Amazon Technologies, Inc
 * @copyright Portions copyright osCommerce 2002-2008 
 * @license GPL v2, please see LICENSE.txt
 * @access public
 * @version $Id: $
 * @note only weight currently supported by Checkout by Amazon is lbs 
 * @note only currency currently supported by Checkout by Amazon is USD
 * @note id is SKU only 
 * @note the size limits on the SKU, category name, & description fields for Checkout by Amazon
 *	are enforced when creating the cart XML.  Any longer strings will be truncated to the
 *	limits specified in checkout_by_amazon_constants.php 
 * @note tep_get_category_name is copied from /admin/includes/functions/general.php.  However, this file cannot be included because /includes/functions/general.php is already in scope and contains several functions of same name, causing a namespace conflict 
 * 
 */
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

 class CheckoutByAmazonItem {
    var $id;
    var $title;
    var $price;
    var $currency = 'USD';
    var $quantity;
    var $item_description;
    var $weight;
    var $weight_unit = 'lb';
    var $category_id;
    var $category_str;

/**
 * @brief Constructor which takes in array of attributes from osCommerce product
 *	and converts them into a Checkout by Amazon Item 
 * @param item_attributes array of values associated with an item
 * @post an osCommerce product has been converted into a CBA item 
 */
    function CheckoutByAmazonItem($item_attributes) {
	global $languages_id;

	$this->id = $item_attributes['id'];
	$this->title = $item_attributes['title'];
	$this->description = $item_attributes['description'];
        $this->price = $item_attributes['price'];

        // round decimal places to two digits, as Checkout by Amazon only supports
        // this format (i.e. 10.413 is converted to 10.41).
        $this->price = is_numeric($this->price) ? round($this->price, 2) : $this->price;

	$this->quantity = $item_attributes['quantity'];
	$this->weight = $item_attributes['weight'];

	$this->category_id = $item_attributes['category_id'];
	$this->category_str =
	    tep_get_category_names($this->category_id, $languages_id);
    }
}

/**
 * @brief looks up the name of a category based on its numeric key
 * @copyright osCommerce 2002-2008 
 * @param category_id the key, integer, of the category
 * @param language_id the current language mode of osCommerce
 * @return the string name of that category 
 * @see /admin/includes/functions/general.php where this function originated.  
 */
function tep_get_category_names($category_id, $language_id)
{
    $category_query =
	tep_db_query("select categories_name from ".
		     TABLE_CATEGORIES_DESCRIPTION.
		     " where categories_id = '".(int) $category_id.
		     "' and language_id = '".(int) $language_id."'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_name'];
}

?>
