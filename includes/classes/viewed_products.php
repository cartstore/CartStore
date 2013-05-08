<?php

  class viewed_products {
    var $viewed_items;

    // reset the array
    function reset() {
      $this->viewed_items = array();
    }

    // add a product
    function add_viewed($products_id) {
      $this->viewed_items[] = $products_id;
    }

    // count the products
    function count_viewed() { 
      return sizeof($this->viewed_items);
    }

    // return the array
    function get_viewed_items() {
      return $this->viewed_items;
    }

    // empty the array
    function remove() {
      $this->reset();
    }
  }
?>