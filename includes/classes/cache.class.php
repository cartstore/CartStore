<?php
/*=======================================================================*\
|| #################### //-- SCRIPT INFO --// ########################## ||
|| #	Script name: cache.class.php
|| #	Contribution: osC-Advanced Cache Class
|| #	Version: 1.1
|| #	Date: 31 January 2005
|| # ------------------------------------------------------------------ # ||
|| #################### //-- COPYRIGHT INFO --// ######################## ||
|| #	Copyright (C) 2005 Bobby Easland								# ||
|| #	Internet moniker: Chemo											# ||	
|| #	Contact: chemo@mesoimpact.com									# ||
|| #	Commercial Site: http://gigabyte-hosting.com/					# ||
|| #	GPL Dev Server: http://mesoimpact.com/							# ||
|| #																	# ||
|| #	This script is free software; you can redistribute it and/or	# ||
|| #	modify it under the terms of the GNU General Public License		# ||
|| #	as published by the Free Software Foundation; either version 2	# ||
|| #	of the License, or (at your option) any later version.			# ||
|| #																	# ||
|| #	This script is distributed in the hope that it will be useful,	# ||
|| #	but WITHOUT ANY WARRANTY; without even the implied warranty of	# ||
|| #	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the	# ||
|| #	GNU General Public License for more details.					# ||
|| #																	# ||
|| #	Script is intended to be used with:								# ||
|| #	osCommerce, Open Source E-Commerce Solutions					# ||
|| #	http://www.oscommerce.com										# ||
|| #	Copyright (c) 2003 osCommerce									# ||
|| ###################################################################### ||
\*========================================================================*/

class cache {
# temp resource container
	var $cache_query;
# cache memory container and parameter
	var $data, $keep_in_memory;
# languages
	var $lang_id;	
	
/*=======================================================================*\
###########################################################################
	class constructor
###########################################################################
\*=======================================================================*/
	# initialize with the actual languages_id or pass an integer
	function cache($languages_id, $memory = false){
		$this->lang_id = (int)$languages_id; //set language_id
		$this->keep_in_memory = $memory; // keep the data in memory?
		$this->data = array(); // initialize data array
		$this->cache_gc(); // clean up expired entries
	} # end class constructor

/*=======================================================================*\
###########################################################################
	function to save the cache to database
	$name => name of entry
	$value => data to be cached
	$method => EVAL, ARRAY, or RETURN
	$gzip => option to gzip the data, recommended to save space
	$global => setting to make the cached data global in scope
	$expires => in the format [ time interval (int)/ date interval (string) ]
###########################################################################
\*=======================================================================*/
	function save_cache($name, $value, $method='RETURN', $gzip=1, $global=0, $expires = '30/days'){
		# convert $expires to date in the future 
		$expires = $this->convert_time($expires); 
		
		# if the method is ARRAY serialize the data
		if ($method == 'ARRAY' ) $value = serialize($value);
		
		# check to see if it should be compressed
		$value = ( $gzip === 1 ? base64_encode(gzdeflate($value, 1)) : addslashes($value) ); // addslashes if not compressed
		
		# initialize the data array for either insert or update
		$sql_data_array = array('cache_id' => md5($name), // md5 it to get a unique name
								'cache_language_id' => (int)$this->lang_id,
								'cache_name' => $name,
								'cache_data' => $value,
								'cache_global' => (int)$global,
								'cache_gzip' => (int)$gzip,
								'cache_method' => $method,
								'cache_date' => date("Y-m-d h:i:s"),
								'cache_expires' => $expires
								);
    tep_db_perform('cache', $sql_data_array, 'insert on duplicate key update');
		
		# unset the variables...clean as we go
		unset($value, $expires, $sql_data_array);
		
	}# end function save_cache()
	
/*=======================================================================*\
###########################################################################
	function to get the cache from database
	$name is the cache name
		=> if no params are passed it will pull all the global 
		   cache entries and eval() them
###########################################################################
\*=======================================================================*/
	function get_cache($name = 'GLOBAL', $local_memory = false){
		# define the column select list
		$select_list = 'cache_id, cache_language_id, cache_name, cache_data, cache_global, cache_gzip, cache_method, cache_date, cache_expires';
		
		# global check, used below
		$global = ( $name == 'GLOBAL' ? true : false ); // was GLOBAL passed or is using the default?
		
		# switch the $name to determine the right query to run
		switch($name){
			case 'GLOBAL': // either using default or passed as GLOBAL
				$this->cache_query = tep_db_query("SELECT ".$select_list." FROM cache WHERE cache_language_id='".(int)$this->lang_id."' AND cache_global='1'");
				break;
				
			default: // anything other than default or GLOBAL
				$this->cache_query = tep_db_query("SELECT ".$select_list." FROM cache WHERE cache_id='".md5($name)."' AND cache_language_id='".(int)$this->lang_id."'");
				break;
		} # end switch ($name)
		
		# number of rows for the query
		$num_rows = tep_db_num_rows($this->cache_query);
		
		if ( $num_rows ){ // if there were rows returned let's loop the return
			$container = array();
			while($cache = tep_db_fetch_array($this->cache_query)){
				# grab the cache name
				$cache_name = $cache['cache_name']; // not really needed but it makes the code look cleaner
				
				# check to see if it is expired
				if ( $cache['cache_expires'] > date("Y-m-d h:i:s") ) { // not expired yet
				
					# determine whether data was compressed
					$cache_data = ( $cache['cache_gzip'] == 1 ? gzinflate(base64_decode($cache['cache_data'])) : stripslashes($cache['cache_data']) );
					
					# switch on the method
					switch($cache['cache_method']){
						case 'EVAL': // must be PHP code
							eval("$cache_data");
							break;
							
						case 'ARRAY': // it's an array, unserialize it
							$cache_data = unserialize($cache_data);							
						case 'RETURN': // it's regular data, just return it
						default:
							break;
					} # end switch ($cache['cache_method'])
					
					# copy the data to an array
					if ($global) $container['GLOBAL'][$cache_name] = $cache_data; // it's global
					else $container[$cache_name] = $cache_data; // not global
				
				} else { // cache is expired
					if ($global) $container['GLOBAL'][$cache_name] = false; // it's global
					else $container[$cache_name] = false; // not global
				}# end if ( $cache['cache_expires'] > date("Y-m-d h:i:s") )
			
				# if keep_in_memory is true save to array
				if ( $this->keep_in_memory || $local_memory ) {
					if ($global) $this->data['GLOBAL'][$cache_name] = $container['GLOBAL'][$cache_name]; // it's global
					else $this->data[$cache_name] = $container[$cache_name]; // not global
				}			
				
			} # end while ($cache = tep_db_fetch_array($this->cache_query))
			
			# unset some varaibles...clean as we go
			unset($cache_data);
			tep_db_free_result($this->cache_query);
			
			# switch on true, case num_rows
			switch (true) {
				case ($num_rows == 1): // only one row returned
					if ($global){ // is global
						# the value is false or is not set, return false
						if ($container['GLOBAL'][$cache_name] == false || !isset($container['GLOBAL'][$cache_name])) return false;
						else return $container['GLOBAL'][$cache_name]; // else return the value
					} else { // not global
						# the valu is false or is not set, return false
						if ($container[$cache_name] == false || !isset($container[$cache_name])) return false;
						else return $container[$cache_name]; // else return the value
					} # end if ($global)
					
				case ($num_rows > 1): // more than 1 row returned
				default: // might as well put a default in here :-)
					return $container; // return the data array
					break;
			}# end switch (true)
			
		} else { // there were no returned rows from the query: return false
			return false;
		}# end if ( $num_rows )
		
	} # end function get_cache()

/*=======================================================================*\
###########################################################################
	function to retrieve the cache from memory
	before it is in memory it must be called at least once by get_cache() !!
###########################################################################
\*=======================================================================*/
	function get_cache_memory($name, $method = 'RETURN'){
		# check to see if there is GLOBAL in memory first
		# if so, use that over non-GLOBAL		
		$data = ( isset($this->data['GLOBAL'][$name]) ? $this->data['GLOBAL'][$name] : $this->data[$name] );
		
		# sanity check to make sure the data has content
		if ( isset($data) && !empty($data) && $data != false ){ // data has content
			
			# switch on the method
			switch($method){
				case 'EVAL': // data must be PHP
					eval("$data");
					return true;
					break;
					
				case 'ARRAY': // already unserialized from get_cache()
				case 'RETURN':
				default:
					return $data;
					break;
			} # end switch ($method)
		
		} else { // data was not set or had no content
			return false;
		} # end if (isset($data) && !empty($data) && $data != false)
		 		
	} # end function get_cache_memory()

/*=======================================================================*\
###########################################################################
	function to do some basic GC
###########################################################################
\*=======================================================================*/
	function cache_gc(){
		# just deleting entries that are expired
		tep_db_query("DELETE FROM cache WHERE cache_expires <= '" . date("Y-m-d h:i:s") . "'" );
	}

/*=======================================================================*\
###########################################################################
	function to convert $expires datetime
	parameter is in the format [ time interval (int)/ date interval (string) ]
	30/d == 30 days, 1/m == 1 month, etc.
###########################################################################
\*=======================================================================*/
	function convert_time($expires){ //expires date interval must be spelled out and NOT abbreviated !!
		# explode the passed parameter
		$expires = explode('/', $expires);
		switch( strtolower($expires[1]) ){ // strtolower just in case :-)
			case 'seconds':
				$expires = mktime( date("h"), date("i"), date("s")+(int)$expires[0], date("m"), date("d"), date("Y") );
				break;
			
			case 'minutes':
				$expires = mktime( date("h"), date("i")+(int)$expires[0], date("s"), date("m"), date("d"), date("Y") );
				break;
			
			case 'hours':
				$expires = mktime( date("h")+(int)$expires[0], date("i"), date("s"), date("m"), date("d"), date("Y") );
				break;
			
			case 'days':
				$expires = mktime( date("h"), date("i"), date("s"), date("m"), date("d")+(int)$expires[0], date("Y") );
				break;
			
			case 'months':
				$expires = mktime( date("h"), date("i"), date("s"), date("m")+(int)$expires[0], date("d"), date("Y") );
				break;
			
			case 'years':
				$expires = mktime( date("h"), date("i"), date("s"), date("m"), date("d"), date("Y")+(int)$expires[0] );
				break;
			
			default: // if something fudged up then default to 1 month
				$expires = mktime( date("h"), date("i"), date("s"), date("m")+1, date("d"), date("Y") );
				break;
		} # end switch( strtolower($expires[1]) )
		
		# return the converted expiration date
		return date("Y-m-d h:i:s", $expires);
				
	} # end function convert_time()

/*=======================================================================*\
###########################################################################
	function to check if the cache is in the database and expired 
###########################################################################
\*=======================================================================*/
	function is_cached($name, &$is_cached, &$is_expired){ // NOTE: $is_cached and $is_expired is passed by reference !!
		# query for the expiration date
		$this->cache_query = tep_db_query("SELECT cache_expires FROM cache WHERE cache_id='".md5($name)."' AND cache_language_id='".(int)$this->lang_id."' LIMIT 1");
		
		# check to see if there were any rows returned
		$is_cached = ( tep_db_num_rows($this->cache_query ) ? true : false );
		
		if ($is_cached){ // there were rows returned
			# fetch the array
			$check = tep_db_fetch_array($this->cache_query);
			
			# check to see if it is expired
			$is_expired = ( $check['cache_expires'] <= date("Y-m-d h:i:s") ? true : false );
			
			# unset $check...clean as we go
			unset($check);
		}
		
		# free the result...clean as we go
		tep_db_free_result($this->cache_query);
	}# end function is_cached()
	
} # end of cache class
?>