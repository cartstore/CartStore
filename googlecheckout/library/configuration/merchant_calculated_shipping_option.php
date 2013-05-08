<?php
/*
  Copyright (C) 2009 Google Inc.

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
 * $Id$
 * 
 * Option for merchant calculated shipping.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
 
require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration.php');
require_once(DIR_FS_CATALOG . '/includes/modules/payment/googlecheckout.php');

class GoogleMerchantCalculatedShippingOption /* implements GoogleOptionInterface */ {
  
  var $type = "merchant_calculated_shipping";
  
  var $title;
  var $description;  
  var $database_key;
  
  var $google_configuration;
  
  function GoogleMerchantCalculatedShippingOption($title, $description, $database_key) {
    $this->title = $title;
    $this->description = $description;
    $this->database_key = $database_key;
    
    $this->google_configuration = new GoogleConfiguration();
  //$this->google_configuration->setDefault($this->database_key, $default);
  }

  function getOptionType() {
    return $this->type;
  }
  
  function getKey() {
  	return $this->database_key;
  }
  
  function getTitle() {
    return $this->title;
  }
  
  function getDescription() {
    return $this->description;
  }  
  
  function getValue() {
    return $this->google_configuration->getValue($this->database_key);
  }
  
  function setValue($value) {
    $this->google_configuration->setValue($this->database_key, $value);
  }
  
  function getHtml() {
    $google_checkout = new googlecheckout();
    
    $installed_modules = $this->getInstalledModules();
    
    // Check whether we have enabled any modules that are not
    // included in the "shipping_support" member of the googlecheckout class.
    // TODO(eddavisson): I don't really understand why we're doing this check,
    // and I'm not sure the logic for the check is even correct.
    $show_configuration = 
        count(array_keys($installed_modules)) 
        > count(array_intersect($google_checkout->shipping_support, array_keys($installed_modules)));
    if (!$show_configuration) {
      return '<i>No compatible shipping modules detected.</i>';
    }
    
    $db_value = $this->getValue();
    $key_values = explode(", ", $db_value);
    
    $html = '<table class="mcs">';
    foreach ($installed_modules as $i => $module) {
      if ($module['status'] && !in_array($module['code'], $google_checkout->shipping_support)) {
        $html .= '<tr class="head"><td colspan="3">';
        $html .= $module['title'];
        $html .= '</td></tr>';
        if (is_array($google_checkout->mc_shipping_methods[$module['code']])) {
          foreach ($google_checkout->mc_shipping_methods[$module['code']] as $type => $methods) {
            if (is_array($methods) && !empty($methods)) {
              if ($type == 'international_types') {
                $type_name = 'International';
              } else {
                $type_name = 'Domestic';
              }
              $html .= '<tr class="subhead"><td>Default</td><td>';
              $html .= $type_name;
              $html .= '</td></tr>';
              foreach ($methods as $method => $method_name) {
                $key = $module['code'] . $method . $type;
                $value = $this->compare($key, $key_values);
                
                $html .= '<tr>';
                
                // Input.
                $html .= '<td>';
                $input = '<input ';
                $input .= $this->getHandlers($key);
                $input .= ' size="6"';
                $input .= ' type="text"';
                $input .= ' name="null"';
                $input .= ' value="' . $value . '"';
                $input .= '/>';
                $html .= DEFAULT_CURRENCY . ' ' . $input;
                $html .= '</td>';                
                
                // Method name.                
                $html .= '<td>';
                $html .= $method_name;
                $html .= '</td>';
                
                // Hidden input.
                $input = '<input ';
                $input .= ' id="hidden_' . $key . '"';
                $input .= ' type="hidden"';
                $input .= ' name="' . $this->getKey() . $key . '"';
                $input .= '/>';
                $html .= $input;
                
                $html .= '</tr>';
              }
            }
          }
        }  
      }
    }
    $html .= '</table>';
    
    return $html;
  }  
  
  function getHandlers($key) {
    $js = '';
  	$js .= 'onBlur="vd_blur(this';
    $js .= ', \'' . $key . '\'';
    $js .= ', \'hid_' . $key . '\'';
    $js .= ')" ';
    $js .= 'onFocus="vd_focus(this';
    $js .= ', \'' . $key . '\'';
    $js .= ', \'hid_' . $key . '\'';
    $js .= ')"';
    return $js;
  }
  
  // TODO(eddavisson): WHAT?
  function compare($key, $data, $sep="_VD:", $default_value='1') {
  	foreach ($data as $value) {
  		list($key2, $value2) = explode($sep, $value);
      if ($key == $key2) {
      	return $value2;
      }
  	}
    return $default_value;
  }
  
  function getInstalledModules() {
    global $PHP_SELF, $language, $module_type;

    // Retrieve all shipping module files.    
    $module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
    $module_files = array();
    if ($directory = @dir($module_directory)) {
      while ($file = $directory->read()) {
        if (!is_dir($module_directory . $file)) {
          if (substr($file, strrpos($file, '.')) == $file_extension) {
            $module_files[] = $file;
          }
        }
      }
      sort($module_files);
      $directory->close();
    }
          
    // Retrieve the subset that exist as classes and are installed.
    $installed_modules = array();
    for ($i = 0, $n = sizeof($module_files); $i < $n; $i++) {
      $file = $module_files[$i];
      include_once(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
      include_once($module_directory . $file);
      
      $class = substr($file, 0, strrpos($file, '.'));
      if (tep_class_exists($class)) {
        $module = new $class;
        if ($module->check() > 0) {
          $installed_modules[$module->code] = array(
              'code' => $module->code,
              'title' => $module->title,
              'description' => $module->description,
              'status' => $module->check()
              );
        }
      }
    }
    
    return $installed_modules;
  }

}

?>
