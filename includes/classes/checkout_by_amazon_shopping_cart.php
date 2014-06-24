<?php
  /**
   * @brief creates cart objects
   * @catagory osCommerce Checkout by Amazon Payment Module
   * @author Srilakshmi Gorur, Balachandar Muruganantham
   * @copyright 2009-2009 Amazon Technologies, Inc
   * @license GPL v2, please see LICENSE.txt
   * @access public
   * @version $Id: $
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

class shoppingCartAmazon extends shoppingCart{
  
  var $attributesArray;

  /* generates cart objects passing the items xml object */
  function generate_cart($items){
    $weight = 0;
    $total_count = 0; // total count of product quantity
    (float)$totals = 0;
    
    $itemCount = count($items);
    for ($i = 0; $i < $itemCount; $i++) {
      $myitem = $items[$i];
      $quantity = (int)$myitem->Quantity;
      $total_count = $total_count + $quantity;
      $weight = $weight + $quantity * $myitem->Weight->Amount;
      /* calculating the total */
      $totals = $totals + ($quantity * (float)$myitem->Price->Amount);
      $products_id = $myitem->SKU;
      $attributes = $this->getAttributes($products_id);
      $this->add_cart($products_id,$quantity,$attributes,false);
    }
    ob_writelog("cba cart obj ", $this);
  } 

  /* to parse the attributes */
  function getAttributes($products_id) {
    $this->parseSKU($products_id , '{', '}');
    return ($this->attributesArray);
  }

  /* to parse the SKU in order to fetch the attributes id*/
  function parseSKU($string, $start, $end) {
    if (strlen($string) >=1 || $string!=null || $string != '') {
      $ini_len = strpos($string, $start);
      $end_len = strpos($string, $end);
      $key  = substr($string, $ini_len+1, $end_len - $ini_len - 1);
      if ($end_len != FALSE)
        $i=$end_len;
      else
        $i=0;
      $end_val = 0;
      while ($i != 0 && $i <= strlen($string)) {
        $end_val = ($end_val * 10) + $string[$i];
        if ($string[$i] == '{' || $string[$i] == null || $string[$i] == '')
          break;
        $i++;
      }
      $end_val = $end_val /10;
      $value = $end_val;
      $value = $end_val;
      $string = substr_replace($string, '', 0, $end_len + 1);
      if ($key != FALSE && $value != FALSE) {
        $this->attributesArray[$key] = $value;
        $this->parseSKU($string, $start, $end);
      }
    }
  }
}
?>