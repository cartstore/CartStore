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
 
require_once(DIR_FS_CATALOG . '/googlecheckout/library/configuration/google_configuration.php');

/**
 * Google Checkout v1.5.0
 * $Id$
 * 
 * Option containing an array of options.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleArrayOption /* implements GoogleOptionInterface */ {
	
  var $type = "array";
  
  var $title;
  var $description;  
  var $database_key;
  var $options;
  
  var $google_configuration;
  
  /**
   * Constructor.
   */
  function GoogleArrayOption($title, $description, $database_key, $options, $default) {
    $this->title = $title;
    $this->description = $description;
    $this->database_key = $database_key;
    $this->options = $options;
    
    $this->google_configuration = new GoogleConfiguration();
    $this->google_configuration->setDefault($this->database_key, $default);
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
  	return $this->google_configuration->setValue($this->database_key, $value);
  }
  
  function getHtml() {
    $current = $this->getValue();
    $html = '';
  	foreach ($this->options as $display => $value) {
  		$html .= '<input type="radio" name="' . $this->database_key . '" value="' . $value . '"';
      if ($current == $value) {
      	$html .= ' checked="checked"';
      }
      $html .= '/>';
      $html .= $display;
      $html .= '<br/>';
  	}
    return $html;
  }

}

?>
