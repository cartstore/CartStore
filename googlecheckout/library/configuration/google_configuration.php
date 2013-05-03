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
 * The GoogleConfiguration class encapsulates database setup and access
 * for Google Checkout configuration options.
 * 
 * @author Ed Davisson (ed.davisson@gmail.com)
 */
class GoogleConfiguration {
  
  var $table_name = "google_configuration";
  
  var $key_field = "google_configuration_key";
  var $key_type = "varchar(255)";
  
  var $value_field = "google_configuration_value";
  var $value_type = "text";
	
  /**
   * Constructor.
   */
  function GoogleConfiguration() {}
  
  function install() {
    $columns = "(" . join(", ", array(
        $this->key_field . " " . $this->key_type,
        $this->value_field . " " . $this->value_type,
        )) . ")";
        
    $query = "create table if not exists " . $this->table_name . " " . $columns;
    tep_db_query($query);    
  }
  
  function remove() {
    // TODO(eddavisson): Do we want to drop the table?
    $query = "drop table if exists " . $this->table_name;
  	tep_db_query($query);
  }
  
  function tableExists() {
  	$query_string = "show tables like '" . $this->table_name . "'";
    $query = tep_db_query($query_string);
    return tep_db_num_rows($query) > 0;
  }
  
  function getQuery($database_key) {
    $query_string = 
        "select " . $this->value_field 
        . " from " . $this->table_name
        . " where " . $this->key_field . "='" . $database_key . "'";
    return tep_db_query($query_string);  	
  }
  
  function getValue($database_key) {
    $query = $this->getQuery($database_key);
    if (tep_db_num_rows($query) > 0) {
      $results = tep_db_fetch_array($query);
      return $results[$this->value_field];
    }
    return NULL;
  }
  
  function insertValue($database_key, $value) {
  	$query_string = 
        "insert into " . $this->table_name
        . " (" . $this->key_field . "," . $this->value_field . ")"
        . " values ('" . $database_key . "', '" . $value . "')";
    tep_db_query($query_string);
  }
  
  /**
   * Set a value or insert it if it's not already there.
   */
  function setValue($database_key, $value) {
    // If not already there, insert it first.
    if (tep_db_num_rows($this->getQuery($database_key)) < 1) {
    	$this->insertValue($database_key, $value);
    } else {
    	$query_string = 
          "update " . $this->table_name
          . " set " . $this->value_field . "='" . $value . "'"
          . " where " . $this->key_field . "='" . $database_key . "'";
      tep_db_query($query_string);
    }
  }
  
  /**
   * Set the value only if it's not already there.
   */
  function setDefault($database_key, $value) {
  	if (tep_db_num_rows($this->getQuery($database_key)) < 1) {
  		$this->insertValue($database_key, $value);
  	}
  } 
}

function gc_configuration_table_ready() {
	$google_configuration = new GoogleConfiguration();
  return $google_configuration->tableExists();
}

function gc_get_configuration_value($key) {
	$google_configuration = new GoogleConfiguration();
  return $google_configuration->getValue($key);
}

?>
