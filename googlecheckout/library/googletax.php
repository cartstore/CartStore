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
 * $Id: googletax.php 153 2009-01-30 00:16:37Z ed.davisson $
 * 
 * Classes used to handle tax rules and tables
 */

  /**
   * Represents a tax rule
   *
   * @see GoogleDefaultTaxRule
   * @see GoogleAlternateTaxRule
   *
   * @abstract
   */
  class GoogleTaxRule {

    var $tax_rate;

    var $world_area = false;
    var $country_codes_arr;
    var $postal_patterns_arr;
    var $state_areas_arr;
    var $zip_patterns_arr;
    var $country_area;

    function GoogleTaxRule() {
    }

    function SetWorldArea($world_area = true) {
      $this->world_area = $world_area;
    }

    function AddPostalArea($country_code, $postal_pattern = "") {
      $this->country_codes_arr[] = $country_code;
      $this->postal_patterns_arr[]= $postal_pattern;
    }

    function SetStateAreas($areas) {
      if(is_array($areas))
        $this->state_areas_arr = $areas;
      else
        $this->state_areas_arr = array($areas);
    }

    function SetZipPatterns($zips) {
      if(is_array($zips))
        $this->zip_patterns_arr = $zips;
      else
        $this->zip_patterns_arr = array($zips);
    }

    function SetCountryArea($country_area) {
      switch ($country_area) {
        case "CONTINENTAL_48":
        case "FULL_50_STATES":
        case "ALL":
          $this->country_area = $country_area;
        break;
        default:
          $this->country_area = "";
        break;
      }
    }
  }

  /**
   * Represents a default tax rule
   *
   * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_default-tax-rule <default-tax-rule>}
   */
  class GoogleDefaultTaxRule extends GoogleTaxRule {

    var $shipping_taxed = false;

    function GoogleDefaultTaxRule($tax_rate, $shipping_taxed = "false") {
      $this->tax_rate = $tax_rate;
      $this->shipping_taxed= $shipping_taxed;

      $this->country_codes_arr = array();
      $this->postal_patterns_arr = array();
      $this->state_areas_arr = array();
      $this->zip_patterns_arr = array();
    }
  }

  /**
   * Represents an alternate tax rule
   *
   * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_alternate-tax-rule <alternate-tax-rule>}
   */
  class GoogleAlternateTaxRule extends GoogleTaxRule {

    function GoogleAlternateTaxRule($tax_rate) {
      $this->tax_rate = $tax_rate;

      $this->country_codes_arr = array();
      $this->postal_patterns_arr = array();
      $this->state_areas_arr = array();
      $this->zip_patterns_arr = array();
    }

  }


  /**
   * Represents an alternate tax table
   *
   * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_alternate-tax-table <alternate-tax-table>}
   */
  class GoogleAlternateTaxTable {

    var $name;
    var $tax_rules_arr;
    var $standalone;

    function GoogleAlternateTaxTable($name = "", $standalone = "false") {
      if($name != "") {
        $this->name = $name;
        $this->tax_rules_arr = array();
        $this->standalone = $standalone;
      }
    }

    function AddAlternateTaxRules($rules) {
      $this->tax_rules_arr[] = $rules;
    }
  }


?>
