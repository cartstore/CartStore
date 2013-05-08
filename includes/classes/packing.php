<?php
/*
  $Id: packing.php, v1.0 2007/12/24 JanZ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
  Adapted from the UPSXML contribution
  
  dimensions support = 0: no dimensions support
  dimensions support = 1: ready-to-ship support only
  dimensions support = 2: full dimensions support
*/

  class packing {
    var $item, $totalWeight, $items_qty;

    function packing() {
    global $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $cart, $order;

        $this->unit_weight = SHIPPING_UNIT_WEIGHT;
        $this->unit_length = SHIPPING_UNIT_LENGTH;
        $this->items_qty = 0;
        $this->totalWeight = 0;
        $this->item = array();

        
        if (defined('SHIPPING_DIMENSIONS_SUPPORT') && SHIPPING_DIMENSIONS_SUPPORT == 'Ready-to-ship only') {
          $this->dimensions_support = 1;
        } elseif (defined('SHIPPING_DIMENSIONS_SUPPORT') && SHIPPING_DIMENSIONS_SUPPORT == 'With product dimensions') {
          $this->dimensions_support = 2;
        } else {
          $this->dimensions_support = 0;
        }

        if (defined('SHIPPING_STORE_BOXES_USED') && SHIPPING_STORE_BOXES_USED == 'true') {
          $this->store_boxes_used = 1; 
        } else {
          $this->store_boxes_used = 0;
        }
        
        if (method_exists($cart, 'get_products_for_packaging') ) {
          $productsArray = $cart->get_products_for_packaging();
        } else {
          $productsArray = $cart->get_products();
        }
        if ($this->dimensions_support > 0) {
          $productsArray = $this->more_dimensions_to_productsArray($productsArray);
          // debug only
          // echo '<pre>Products to pack:<br>';
          // print_r($productsArray);
         // exit; 
        } 

        if ($this->dimensions_support == 2) {
            // sort $productsArray according to ready-to-ship (first) and not-ready-to-ship (last)
            usort($productsArray, ready_to_shipCmp);
            // Use packing algorithm to return the number of boxes we'll ship
            $boxesToShip = $this->packProducts($productsArray);
            /* echo '<pre>Boxes to ship:<br>';
             print_r($boxesToShip);
             exit; */
            if ($this->store_boxes_used == 1) {
            $storeBoxesToShip = base64_encode(serialize($boxesToShip));
            $storeQueryArray = array('date' => 'now()', 'customers_id' => $_SESSION['customer_id'], 'boxes' => $storeBoxesToShip);
            tep_db_perform(TABLE_UPS_BOXES_USED, $storeQueryArray);
            }
            // Quote for the number of boxes
            for ($i = 0; $i < count($boxesToShip); $i++) {
                $this->_addItem($boxesToShip[$i]['length'], $boxesToShip[$i]['width'], $boxesToShip[$i]['height'], $boxesToShip[$i]['current_weight'], $boxesToShip[$i]['price']);
                $this->totalWeight += $boxesToShip[$i]['current_weight'];
            }
        } elseif ($this->dimensions_support == 1) {
            $this->totalWeight = 0;
            $total_non_ready_to_ship_weight = 0;
            $total_non_ready_to_ship_value = 0;
            // sort $productsArray according to ready-to-ship (first) and not-ready-to-ship (last)
            usort($productsArray, ready_to_shipCmp);
            $non_ready_to_shipArray = array();
            // walk through the productsArray, separate the items ready-to-ship and add them to
            // the items (boxes) list, add the weight to the totalWeight
            // and add the other items to a separate array
            for ($i = 0; $i < count($productsArray); $i++) {
                if ($productsArray[$i]['ready_to_ship'] == '1') {
                    for ($z = 0 ; $z < $productsArray[$i]['quantity']; $z++) {
                        $this->_addItem($productsArray[$i]['length'], $productsArray[$i]['width'], $productsArray[$i]['height'], $productsArray[$i]['weight'], $productsArray[$i]['final_price']);
                        $this->totalWeight += $productsArray[$i]['weight'];
                    } // end for ($z = 0 ; $z < $productsArray[$i]['quantity']; $z++)
                } // end if($productsArray['ready_to_ship'] == '1')
                else {
                    $non_ready_to_shipArray[] = $productsArray[$i];
                }
            } // end for ($i = 0; $i < count($productsArray); $i++)
            // Ready_to_ship items out of the way, now assess remaining weight and remaining value of products

            for ($x = 0 ; $x < count($non_ready_to_shipArray) ; $x++) {
                $total_non_ready_to_ship_weight += ($non_ready_to_shipArray[$x]['weight'] * $non_ready_to_shipArray[$x]['quantity']);
                $total_non_ready_to_ship_value += ($non_ready_to_shipArray[$x]['final_price'] * $non_ready_to_shipArray[$x]['quantity']);
            } // end for ($x = 0 ; count($non_ready_to_shipArray) ; $x++)
      
            if (tep_not_null($non_ready_to_shipArray)) {
                // adapted code from includes/classes/shipping.php
                $shipping_non_ready_to_ship_boxes = 1;
                $shipping_non_ready_to_ship_weight = $total_non_ready_to_ship_weight;
                if (SHIPPING_BOX_WEIGHT >= $total_non_ready_to_ship_weight*SHIPPING_BOX_PADDING/100) {
                  $total_non_ready_to_ship_weight = $total_non_ready_to_ship_weight+SHIPPING_BOX_WEIGHT;
                } else {
                  $total_non_ready_to_ship_weight += $total_non_ready_to_ship_weight*SHIPPING_BOX_PADDING/100;
                }
                if ($total_non_ready_to_ship_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
                    $shipping_non_ready_to_ship_boxes = ceil($total_non_ready_to_ship_weight/SHIPPING_MAX_WEIGHT);
                    $shipping_non_ready_to_ship_weight = round($total_non_ready_to_ship_weight/$shipping_non_ready_to_ship_boxes,1);
                }
                // end adapted code from includes/classes/shipping.php
                // weight and number of boxes of non-ready-to-ship is determined, now add them to the items list
                for ($y = 0; $y < $shipping_non_ready_to_ship_boxes ; $y++) {
                    $this->_addItem(0, 0, 0, $shipping_non_ready_to_ship_weight, number_format(($total_non_ready_to_ship_value/$shipping_non_ready_to_ship_boxes), 2, '.', ''));
                    $this->totalWeight += $shipping_non_ready_to_ship_weight;
                } // end for ($y = 0; $y < $shipping_non_ready_to_ship_boxes ; $y++)
            } // end if (tep_not_null($non_ready_to_shipArray))
       } // if/else ($this->dimensions_support == '#')
    } // end function packing($dimensions_support = '0')
    
    //********************************************
    function _addItem($length, $width, $height, $weight, $price = 0 ) {
        // Add box or item to shipment list. Round weights to 1 decimal places.
        if ((float)$weight < 1.0) {
            $weight = 1;
        } else {
            $weight = round($weight, 1);
        }
        $index = $this->items_qty;
        $this->item[$index]['item_length'] = ($length ? (string)$length : '0' );
        $this->item[$index]['item_width'] = ($width ? (string)$width : '0' );
        $this->item[$index]['item_height'] = ($height ? (string)$height : '0' );
        $this->item[$index]['item_weight'] = ($weight ? (string)$weight : '0' );
        $this->item[$index]['item_price'] = $price;
        $this->items_qty++;
    }

    //********************
    function getPackagesByVol() {
        $packages = array();
        $packages_query = tep_db_query("select *, (package_length * package_width * package_height) as volume from " . TABLE_PACKAGING . " order by volume");
        $counter = 0;
        while ($package = tep_db_fetch_array($packages_query)) {
            $packages[] = array(
            'id' => $package['package_id'],
            'name' => $package['package_name'],
            'description' => $package['package_description'],
            'length' => $package['package_length'],
            'width' => $package['package_width'],
            'height' => $package['package_height'],
            'empty_weight' => $package['package_empty_weight'],
            'max_weight' => $package['package_max_weight'],
            'volume' => $package['volume']);
// sort dimensions from low to high, used in the function fitsInBox
            $dimensions = array($package['package_length'], $package['package_width'], $package['package_height']);
            sort($dimensions);
              foreach($dimensions as $key => $value) {
                if ($key == 0 ) { $packages[$counter]['x'] = $value; }
                if ($key == 1 ) { $packages[$counter]['y'] = $value; }
                if ($key == 2 ) { $packages[$counter]['z'] = $value; }
              }
            $counter++;
        }
        return $packages;
    }

    //********************************
    function packProducts($productsArray) {
        $definedPackages = $this->getPackagesByVol();
        $emptyBoxesArray = array();
        for ($i = 0; $i < count($definedPackages); $i++) {
            $definedBox = $definedPackages[$i];
            $definedBox['remaining_volume'] = $definedBox['volume'];
            $definedBox['current_weight'] = $definedBox['empty_weight'];
            $emptyBoxesArray[] = $definedBox;
        }
          if (count($emptyBoxesArray) == 0) {
             print("ERROR: No boxes to ship unpackaged product<br />\n");
             break;
          }

        $packedBoxesArray = array();
        $currentBox = NULL;
        $index_of_largest_box = count($emptyBoxesArray)-1;
        // Get the product array and expand multiple qty items.
        $productsRemaining = array();
        for ($i = 0; $i < count($productsArray); $i++) {
          $product = $productsArray[$i];
            // sanity checks on the product, no need for ready-to-ship items
            if ((int)$product['ready_to_ship'] == 0) {
              $product['ready_to_ship'] = '1';
                 for ($x = 0; $x <= $index_of_largest_box; $x++) {
                   if ($this->fitsInBox($product, $emptyBoxesArray[$x])) {
                     $product['ready_to_ship'] = '0';
                     $product['largest_box_it_will_fit'] = $x;
                   } 
                 } // end for ($x = 0; $x <= $index_of_largest_box; $x++) 
            } // end if ((int)$product['ready_to_ship'] == 0)

            for ($j = 0; $j < $productsArray[$i]['quantity']; $j++) {
                $productsRemaining[] = $product;
            }
        } // end for ($i = 0; $i < count($productsArray); $i++)
        // make sure the products that did not fit the largest box and are now set as ready-to-ship
        // are out of the way as soon as possible
        usort($productsRemaining, ready_to_shipCmp);
        // Worst case, you'll need as many boxes as products ordered
        $index_of_largest_box_to_use = count($emptyBoxesArray) -1;
        while (count($productsRemaining)) {
            // Immediately set aside products that are already packed and ready.
            if ($productsRemaining[0]['ready_to_ship'] == '1') {
                $packedBoxesArray[] = array (
                'length' => $productsRemaining[0]['length'],
                'width' => $productsRemaining[0]['width'],
                'height' => $productsRemaining[0]['height'],
                'current_weight' => $productsRemaining[0]['weight'],
                'price' => $productsRemaining[0]['final_price']);
                $productsRemaining = array_slice($productsRemaining, 1);
                continue;
            }
            // Cycle through boxes, increasing box size if all doesn't fit
            // but if the remaining products only fit in a box of smaller size, use that one to pack it away
            for ($b = 0; $b < count($emptyBoxesArray) && tep_not_null($productsRemaining); $b++) {
                $result = $this->fitProductsInBox($productsRemaining, $emptyBoxesArray[$b], $packedBoxesArray, $b, $index_of_largest_box_to_use);
                $packedBoxesArray = $result['packed_boxes'];
                $productsRemaining = $result['remaining'];
                if (isset($result['index_of_largest_box_to_use']) && $result['index_of_largest_box_to_use'] >= 0 ) {
                  $index_of_largest_box_to_use = $result['index_of_largest_box_to_use'];
                }
            }
        } // end while

        return $packedBoxesArray;
    }

    //*****************************
    function fitsInBox($product, $box) {
        if ($product['x'] > $box['x'] || $product['y'] > $box['y'] || $product['z'] > $box['z']) {
            return false;
        } 

        if ($product['volume'] <= $box['remaining_volume']) {
            if ($box['max_weight'] == 0 || ($box['current_weight'] + $product['weight'] <= $box['max_weight'])) {
                return true;
            }
        }
        return false;
    }

    //***********************************
    function putProductInBox($product, $box) {
        $box['remaining_volume'] -= $product['volume'];
        $box['products'][] = $product;
        $box['current_weight'] += $product['weight'];
        $box['price'] += $product['final_price'];
        return $box;
    } 
    //*********************    
    function fitProductsInBox($productsRemaining, $emptyBox, $packedBoxesArray, $box_no, $index_of_largest_box) { 
        $currentBox = $emptyBox;
        $productsRemainingSkipped = array();
        $productsRemainingNotSkipped = array();
        $largest_box_in_skipped_products = -1;
        // keep apart products that will not fit this box anyway
        for ($p = 0; $p < count($productsRemaining); $p++) {
          if ($productsRemaining[$p]['largest_box_it_will_fit'] < $box_no) {
            $productsRemainingSkipped[] = $productsRemaining[$p];
            // check on skipped products: if they will not fit in the largest box
            // the $index_of_largest_box should be the one they *will* fit
            // otherwise the packing algorithm gets stuck in a loop
            if ($productsRemaining[$p]['largest_box_it_will_fit'] > $largest_box_in_skipped_products) {
              $largest_box_in_skipped_products = $productsRemaining[$p]['largest_box_it_will_fit'];
            }
          } else {
            $productsRemainingNotSkipped[] = $productsRemaining[$p];
          }
        }

        unset($productsRemaining);
        $productsRemaining = $productsRemainingNotSkipped;
        unset($productsRemainingNotSkipped);
        if (count($productsRemaining) == 0) {
          // products remaining are the ones that will not fit this box (productsRemaimingSkipped)
            $result_array = array('remaining' => $productsRemainingSkipped, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray, 'index_of_largest_box_to_use' => $largest_box_in_skipped_products);
            return ($result_array);
        }

        //Try to fit each product that can fit in box
        for ($p = 0; $p < count($productsRemaining); $p++) {
            if ($this->fitsInBox($productsRemaining[$p], $currentBox)) {
                //It fits. Put it in the box.
                $currentBox = $this->putProductInBox($productsRemaining[$p], $currentBox);
                if ($p == count($productsRemaining) - 1) {
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p + 1);
                    $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);

                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
            } else {
                if ($box_no == $index_of_largest_box) {
                    //We're at the largest box already, and it's full. Keep what we've packed so far and get another box.
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p);
                    $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);
                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
                // Not all of them fit. Stop packing remaining products and try next box.
                $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);
                $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                return ($result_array);
            } // end else
        } // end for ($p = 0; $p < count($productsRemaining); $p++)
    } // end function fitProductsInBox
    
// ******************************
  function more_dimensions_to_productsArray($productsArray) {
    $counter = 0;
      foreach ($productsArray as $key => $product) {
        // in case by accident or by choice length, width or height is not set
        // we will estimate it by using a set density and the product['weight'] variable
        // will only be used in the check for whether it fits the largest box
        // after that it will already be set, if product['weight'] is set at least
        if ($product['length'] == 0 || $product['width'] == 0 || $product['height'] == 0) {
            $density = 0.7;
            if ($this->unit_length == 'CM') {
                $product['length']=$product['width']=$product['height']= round(10*(pow($product['weight']/$density, 1/3)),1);
            } else {
                // non-metric: inches and pounds
                $product['length']=$product['width']=$product['height']= round(pow($product['weight']*27.67/$density, 1/3),1);
            }
        } // end if ($product['length'] == 0 || $product['width'] == 0 etc.
// sort dimensions from low to high, used in the function fitsInBox
        $dimensions = array($product['length'], $product['width'], $product['height']);
        sort($dimensions);
          foreach($dimensions as $key => $value) {
            if ($key == 0 ) { $productsArray[$counter]['x'] = $value; }
            if ($key == 1 ) { $productsArray[$counter]['y'] = $value; }
            if ($key == 2 ) { $productsArray[$counter]['z'] = $value; }
           }
        $productsArray[$counter]['volume'] = $product['length'] * $product['width'] * $product['height'];
        $counter++;
  } // end foreach ($productsArray as $key => $product)
    return($productsArray);
  }

  function getPackedBoxes() {
    return $this->item;
  }

  function getTotalWeight() {
    return $this->totalWeight;
  }

  function getNumberOfBoxes() {
    return $this->items_qty;
  }

  } // end class packing
// ******************************
function ready_to_shipCmp( $a, $b) {
    if ( $a['ready_to_ship'] == $b['ready_to_ship'] )
    return 0;
    if ( $a['ready_to_ship'] > $b['ready_to_ship'] )
    return -1;
    return 1;
}
?>